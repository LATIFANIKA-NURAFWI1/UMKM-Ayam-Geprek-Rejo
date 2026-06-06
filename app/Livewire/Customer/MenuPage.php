<?php

namespace App\Livewire\Customer;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.customer')]
#[Title('Menu - Geprek Rejo')]
class MenuPage extends Component
{
    /** Currently filtered category (null = semua) */
    public ?int $activeCategory = null;

    /** Live search query */
    public string $searchQuery = '';

    // ── Lifecycle ────────────────────────────────────────────────────────────────

    public function mount(Request $request): void
    {
        // Simpan QR source code ke session jika ada di URL (?src=...)
        if ($src = $request->query('src')) {
            session(['qr_source' => $src]);
        }
    }

    // ── Cart Actions ─────────────────────────────────────────────────────────────

    /** Tambah item ke cart, atau increment qty jika sudah ada */
    public function addToCart(int $menuItemId): void
    {
        $item = MenuItem::available()->findOrFail($menuItemId);
        $cart = session('cart', []);

        if (isset($cart[$menuItemId])) {
            $cart[$menuItemId]['quantity']++;
            $cart[$menuItemId]['subtotal'] = $cart[$menuItemId]['quantity'] * $cart[$menuItemId]['price'];
        } else {
            $cart[$menuItemId] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => (float) $item->price,
                'quantity' => 1,
                'subtotal' => (float) $item->price,
                'image'    => $item->image,
            ];
        }

        session(['cart' => $cart]);
    }

    /** Hapus item dari cart sepenuhnya */
    public function removeFromCart(int $menuItemId): void
    {
        $cart = session('cart', []);
        unset($cart[$menuItemId]);
        session(['cart' => $cart]);
    }

    /** Increment qty item yang sudah ada di cart */
    public function increaseQty(int $menuItemId): void
    {
        $cart = session('cart', []);

        if (isset($cart[$menuItemId])) {
            $cart[$menuItemId]['quantity']++;
            $cart[$menuItemId]['subtotal'] = $cart[$menuItemId]['quantity'] * $cart[$menuItemId]['price'];
            session(['cart' => $cart]);
        }
    }

    /** Decrement qty; hapus item jika qty mencapai 0 */
    public function decreaseQty(int $menuItemId): void
    {
        $cart = session('cart', []);

        if (isset($cart[$menuItemId])) {
            $cart[$menuItemId]['quantity']--;

            if ($cart[$menuItemId]['quantity'] <= 0) {
                unset($cart[$menuItemId]);
            } else {
                $cart[$menuItemId]['subtotal'] = $cart[$menuItemId]['quantity'] * $cart[$menuItemId]['price'];
            }

            session(['cart' => $cart]);
        }
    }

    /** Reset semua filter (search + kategori) */
    public function clearFilters(): void
    {
        $this->searchQuery = '';
        $this->activeCategory = null;
    }

    // ── Computed Properties ──────────────────────────────────────────────────────

    /** Isi cart dari session */
    #[Computed]
    public function cart(): array
    {
        return session('cart', []);
    }

    /** Total jumlah item (sum of quantities) */
    #[Computed]
    public function cartCount(): int
    {
        return (int) array_sum(array_column(session('cart', []), 'quantity'));
    }

    /** Total harga cart (sum of subtotals) */
    #[Computed]
    public function cartTotal(): float
    {
        return (float) array_sum(array_column(session('cart', []), 'subtotal'));
    }

    /** Menu items dengan filter kategori + search, scope available + ordered */
    #[Computed]
    public function menuItems()
    {
        return MenuItem::available()
            ->ordered()
            ->with('category')
            ->when($this->activeCategory, fn ($q) => $q->where('category_id', $this->activeCategory))
            ->when(
                trim($this->searchQuery),
                fn ($q) => $q->where('name', 'like', '%' . trim($this->searchQuery) . '%')
            )
            ->get();
    }

    /** Semua kategori aktif, terurut */
    #[Computed]
    public function categories()
    {
        return Category::active()->ordered()->get();
    }

    // ── Navigation ───────────────────────────────────────────────────────────────

    /** Redirect ke halaman checkout, atau flash error jika cart kosong */
    public function goToCheckout(): void
    {
        if (empty(session('cart', []))) {
            session()->flash('cart_error', 'Keranjang masih kosong. Pilih menu dulu ya! 🛒');

            return;
        }

        $this->redirect(route('order.checkout'), navigate: true);
    }
}
