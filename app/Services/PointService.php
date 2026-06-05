<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Order;
use App\Models\PointLog;
use Illuminate\Support\Facades\Log;

class PointService
{
    /**
     * Formula: poin = floor(total_amount / 100)
     * Artinya: setiap Rp 100 = 1 poin.
     */
    public function calculateEarnedPoints(float $totalAmount): int
    {
        return (int) floor($totalAmount / 100);
    }

    /**
     * Tambah poin ke member dan catat di point_logs.
     * HARUS dipanggil dalam DB::transaction.
     *
     * @param  Member  $member
     * @param  Order   $order
     * @return int  jumlah poin yang diperoleh
     */
    public function earnPoints(Member $member, Order $order): int
    {
        $pointsEarned = $this->calculateEarnedPoints((float) $order->total_amount);

        if ($pointsEarned <= 0) {
            return 0;
        }

        $newBalance = $member->points + $pointsEarned;

        // Update saldo poin member
        $member->increment('points', $pointsEarned);

        // Catat di point_logs
        PointLog::create([
            'member_id'   => $member->id,
            'order_id'    => $order->id,
            'type'        => 'earn',
            'points'      => $pointsEarned,
            'balance_after' => $newBalance,
            'description' => "Poin dari order #{$order->order_number}",
        ]);

        // Update statistik order
        $order->update(['points_earned' => $pointsEarned]);

        Log::info("PointService: Member#{$member->id} earn {$pointsEarned} poin dari order#{$order->id}. Saldo: {$newBalance}");

        return $pointsEarned;
    }

    /**
     * Redeem poin saat checkout.
     * Dipanggil SEBELUM order dibuat, untuk menghitung discount dari poin.
     *
     * @param  Member  $member
     * @param  int     $pointsToRedeem  Jumlah poin yang ingin digunakan
     * @param  float   $orderTotal      Total sebelum redeem
     * @return array{discount: float, points_used: int}
     *
     * @throws \InvalidArgumentException
     */
    public function calculateRedemption(Member $member, int $pointsToRedeem, float $orderTotal): array
    {
        if ($pointsToRedeem <= 0) {
            return ['discount' => 0.0, 'points_used' => 0];
        }

        if ($member->points < $pointsToRedeem) {
            throw new \InvalidArgumentException(
                "Poin tidak mencukupi. Saldo: {$member->points}, diminta: {$pointsToRedeem}."
            );
        }

        // 1 poin = Rp 1 (sesuai model Member)
        $discountValue = $member->pointsToRupiah($pointsToRedeem);

        // Redeem tidak boleh melebihi total order
        $actualDiscount = min($discountValue, $orderTotal);
        $actualPoints   = (int) $actualDiscount; // 1 poin = Rp 1

        return [
            'discount'    => $actualDiscount,
            'points_used' => $actualPoints,
        ];
    }

    /**
     * Eksekusi pemotongan poin setelah order confirmed.
     * HARUS dipanggil dalam DB::transaction.
     *
     * @param  Member  $member
     * @param  Order   $order
     * @param  int     $pointsToRedeem
     */
    public function redeemPoints(Member $member, Order $order, int $pointsToRedeem): void
    {
        if ($pointsToRedeem <= 0) {
            return;
        }

        if ($member->points < $pointsToRedeem) {
            throw new \InvalidArgumentException(
                "Poin tidak mencukupi saat eksekusi redeem. Saldo: {$member->points}."
            );
        }

        $newBalance = $member->points - $pointsToRedeem;
        $member->decrement('points', $pointsToRedeem);

        PointLog::create([
            'member_id'     => $member->id,
            'order_id'      => $order->id,
            'type'          => 'redeem',
            'points'        => -$pointsToRedeem,
            'balance_after' => $newBalance,
            'description'   => "Redeem poin untuk order #{$order->order_number}",
        ]);

        Log::info("PointService: Member#{$member->id} redeem {$pointsToRedeem} poin. Saldo: {$newBalance}");
    }

    /**
     * Adjustment poin manual oleh admin.
     */
    public function adjustPoints(Member $member, int $delta, string $reason): void
    {
        $newBalance = $member->points + $delta;

        if ($newBalance < 0) {
            throw new \InvalidArgumentException('Saldo poin tidak boleh negatif.');
        }

        $member->update(['points' => $newBalance]);

        PointLog::create([
            'member_id'     => $member->id,
            'order_id'      => null,
            'type'          => 'adjustment',
            'points'        => $delta,
            'balance_after' => $newBalance,
            'description'   => $reason,
        ]);
    }
}
