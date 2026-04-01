<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        $channels = [
            new \Illuminate\Broadcasting\Channel('orders'),
        ];

        if ($this->order->client_id) {
            $channels[] = new PrivateChannel('client.' . $this->order->client_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'OrderStatusChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'orderId'     => $this->order->id,
            'orderNumber' => $this->order->order_number,
            'status'      => $this->order->status->value,
            'statusLabel' => $this->order->status->label(),
            'techName'    => $this->order->technician?->name,
        ];
    }
}
