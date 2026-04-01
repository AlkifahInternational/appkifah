<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        $channels = [
            new Channel('orders'), // Admin + Manager listen here
        ];

        // Also notify the assigned technician directly
        if ($this->order->technician_id) {
            $channels[] = new PrivateChannel('technician.' . $this->order->technician_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'OrderPlaced';
    }

    public function broadcastWith(): array
    {
        return [
            'orderId'     => $this->order->id,
            'orderNumber' => $this->order->order_number,
            'total'       => $this->order->total,
            'address'     => $this->order->address,
            'isUrgent'    => $this->order->urgency?->value === 'urgent',
            'clientName'  => $this->order->client?->name ?? 'Guest',
            'status'      => $this->order->status->value,
        ];
    }
}
