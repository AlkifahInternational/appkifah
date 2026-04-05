<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $locale = $notifiable->locale ?? app()->getLocale();
        
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'client_name' => $this->order->client->name,
            'message' => $locale === 'ar' 
                ? "طلب جديد رقم #{$this->order->order_number} من {$this->order->client->name}"
                : "New order #{$this->order->order_number} from {$this->order->client->name}",
            'url' => route('admin.orders.detail', $this->order->id),
        ];
    }
}
