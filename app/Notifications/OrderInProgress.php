<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Order;

class OrderInProgress extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'delivery_address' => $this->order->delivery_address,
            'message' => 'Your order is now in progress and a driver is on the way.'
        ]);
    }

}
