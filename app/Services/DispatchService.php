<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TechnicianProfile;
use App\Enums\OrderStatus;

class DispatchService
{
    /**
     * Find nearest verified+available technicians using Haversine formula,
     * then assign to the first one (closest). Others get queued in session.
     */
    public static function dispatch(Order $order): ?TechnicianProfile
    {
        if (!$order->latitude || !$order->longitude) {
            // No coords — just find any available tech
            $profile = TechnicianProfile::where('is_verified', true)
                ->where('is_available', true)
                ->first();
        } else {
            // Haversine query — find 5 nearest available technicians
            $lat = $order->latitude;
            $lng = $order->longitude;

            $profile = TechnicianProfile::selectRaw("
                    technician_profiles.*,
                    ( 6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )) AS distance
                ", [$lat, $lng, $lat])
                ->where('is_verified', true)
                ->where('is_available', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<', 50) // within 50km
                ->orderBy('distance')
                ->first();
        }

        if ($profile) {
            /* 
            // DISABLING AUTO-ASSIGNMENT FOR PRODUCTION
            // The order should remain PENDING until manually assigned or accepted.
            $order->update([
                'technician_id' => $profile->user_id,
                'status'        => OrderStatus::ASSIGNED,
            ]);

            // Increment total_jobs counter
            $profile->increment('total_jobs');
            */
        }

        return $profile;
    }

    /**
     * Credit technician wallet after order completion.
     */
    public static function creditWallet(Order $order, float $commissionRate = 0.15): void
    {
        if (!$order->technician_id) return;

        $agentShare = $order->total * (1 - $commissionRate);

        $wallet = \App\Models\Wallet::firstOrCreate(
            ['user_id' => $order->technician_id],
            ['balance' => 0, 'total_earned' => 0]
        );

        $wallet->increment('balance', $agentShare);
        $wallet->increment('total_earned', $agentShare);

        \App\Models\WalletTransaction::create([
            'wallet_id'   => $wallet->id,
            'type'        => 'credit',
            'amount'      => $agentShare,
            'description' => "Order #{$order->order_number} completed",
        ]);
    }
}
