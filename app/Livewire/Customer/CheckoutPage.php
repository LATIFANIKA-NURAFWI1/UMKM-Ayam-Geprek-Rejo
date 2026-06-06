<?php

namespace App\Livewire\Customer;

use App\Exceptions\InsufficientStockException;
use App\Models\Member;
use App\Services\OrderService;
use App\Services\VoucherService;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.customer')]
#[Title('Checkout')]
class CheckoutPage extends Component
{
    // ── Cart ──────────────────────────────────────────────────────────────────

    /** @var array<int|string, array{id: int, name: string, price: float, quantity: int, subtotal: float, image: string|null}> */
    public array $cart = [];

    // ── Order Info ────────────────────────────────────────────────────────────

    public string $customerName  = '';
    public string $orderType     = 'dine_in';
    public string $paymentMethod = 'qris';
    public string $orderNotes    = '';
    public string $tableNumber   = '';

    // ── Voucher ───────────────────────────────────────────────────────────────

    public string $voucherCode     = '';
    public float  $voucherDiscount = 0.0;
    public bool   $voucherApplied  = false;
    public string $voucherError    = '';

    // ── Member ────────────────────────────────────────────────────────────────

    public string  $memberPhone      = '';
    public string  $memberPin        = '';
    public ?int    $loggedInMemberId = null;
    public string  $loggedInMemberName = '';
    public int     $memberPoints     = 0;
    public int     $pointsToRedeem   = 0;
    public bool    $showMemberForm   = false;
    public string  $memberLoginError = '';

    // ── General ───────────────────────────────────────────────────────────────

    public string $orderError = '';

    // =========================================================================
    // LIFECYCLE
    // =========================================================================

    public function mount(): void
    {
        $this->cart        = session('cart', []);
        $this->tableNumber = session('qr_source', '');

        // Restore member session if previously logged in
        $memberId = session('checkout_member_id');
        if ($memberId) {
            $member = Member::find($memberId);
            if ($member && $member->is_active) {
                $this->loggedInMemberId   = $member->id;
                $this->loggedInMemberName = $member->name;
                $this->memberPoints       = $member->points;
            } else {
                session()->forget('checkout_member_id');
            }
        }
    }

    // =========================================================================
    // COMPUTED PROPERTIES
    // =========================================================================

    #[Computed]
    public function subtotal(): float
    {
        return (float) collect($this->cart)->sum(fn ($item) => $item['subtotal'] ?? 0);
    }

    #[Computed]
    public function pointsDiscountAmount(): float
    {
        // 1 poin = Rp 1
        return (float) $this->pointsToRedeem;
    }

    #[Computed]
    public function totalAmount(): float
    {
        return max(0.0, $this->subtotal - $this->voucherDiscount - $this->pointsDiscountAmount);
    }

    // =========================================================================
    // VOUCHER
    // =========================================================================

    public function applyVoucher(): void
    {
        $this->voucherError = '';

        if (empty(trim($this->voucherCode))) {
            $this->voucherError = 'Masukkan kode voucher terlebih dahulu.';
            return;
        }

        try {
            /** @var VoucherService $voucherService */
            $voucherService = app(VoucherService::class);

            $result = $voucherService->validateAndCalculate(
                $this->voucherCode,
                $this->subtotal,
                $this->loggedInMemberId
            );

            $this->voucherDiscount = (float) $result['discount'];
            $this->voucherApplied  = true;
        } catch (\InvalidArgumentException $e) {
            $this->voucherDiscount = 0.0;
            $this->voucherApplied  = false;
            $this->voucherError    = $e->getMessage();
        }
    }

    public function removeVoucher(): void
    {
        $this->voucherCode     = '';
        $this->voucherDiscount = 0.0;
        $this->voucherApplied  = false;
        $this->voucherError    = '';
    }

    // =========================================================================
    // MEMBER
    // =========================================================================

    public function loginMember(): void
    {
        $this->memberLoginError = '';

        if (empty($this->memberPhone) || empty($this->memberPin)) {
            $this->memberLoginError = 'Nomor HP dan PIN wajib diisi.';
            return;
        }

        $member = Member::active()->byPhone($this->memberPhone)->first();

        if (! $member || ! Hash::check($this->memberPin, $member->pin)) {
            $this->memberLoginError = 'Nomor HP atau PIN salah.';
            return;
        }

        session(['checkout_member_id' => $member->id]);

        $this->loggedInMemberId   = $member->id;
        $this->loggedInMemberName = $member->name;
        $this->memberPoints       = $member->points;
        $this->showMemberForm     = false;
        $this->memberPhone        = '';
        $this->memberPin          = '';
    }

    public function logoutMember(): void
    {
        session()->forget('checkout_member_id');

        $this->loggedInMemberId   = null;
        $this->loggedInMemberName = '';
        $this->memberPoints       = 0;
        $this->pointsToRedeem     = 0;
        $this->showMemberForm     = false;

        // Also reset voucher since it might be member-exclusive
        if ($this->voucherApplied) {
            $this->removeVoucher();
        }
    }

    // =========================================================================
    // ORDER PLACEMENT
    // =========================================================================

    public function placeOrder(): void
    {
        $this->orderError = '';

        $this->validate([
            'customerName'  => 'required|min:2|max:100',
            'paymentMethod' => 'required|in:qris,cash',
        ]);

        if (empty($this->cart)) {
            $this->orderError = 'Keranjang belanja kosong. Silakan pilih menu terlebih dahulu.';
            return;
        }

        // Map session cart structure → OrderService format
        $cartForService = collect($this->cart)
            ->map(fn ($item) => [
                'menu_item_id' => (int) $item['id'],
                'quantity'     => (int) $item['quantity'],
                'notes'        => null,
            ])
            ->values()
            ->toArray();

        try {
            /** @var OrderService $orderService */
            $orderService = app(OrderService::class);

            $order = $orderService->createOrder([
                'cart'             => $cartForService,
                'member_id'        => $this->loggedInMemberId,
                'voucher_code'     => $this->voucherApplied ? $this->voucherCode : null,
                'points_to_redeem' => $this->pointsToRedeem,
                'table_number'     => $this->tableNumber ?: null,
                'type'             => $this->orderType,
                'payment_method'   => $this->paymentMethod,
                'order_notes'      => $this->orderNotes ?: null,
            ]);

            // Persist customer name for display on payment/success pages
            session(['order_customer_name' => $this->customerName]);

            // Clear cart
            session(['cart' => []]);

            $this->redirect(route('order.payment', $order->id), navigate: true);

        } catch (InsufficientStockException $e) {
            $this->orderError = $e->getUserFriendlyMessage();
        } catch (\InvalidArgumentException $e) {
            $this->orderError = $e->getMessage();
        } catch (\Throwable $e) {
            $this->orderError = 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.';
        }
    }

    // =========================================================================
    // RENDER
    // =========================================================================

    public function render()
    {
        return view('livewire.customer.checkout-page');
    }
}
