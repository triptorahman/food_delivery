<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\DeliveryAssignment;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DeliveryAssignmentCreated extends Notification
{
    use Queueable;

    public function __construct(public DeliveryAssignment $assignment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }


    public function toDatabase($notifiable): DatabaseMessage
    {
        $order = $this->assignment->order;
        return new DatabaseMessage([
            'assignment_id' => $this->assignment->id,
            'order_id'      => $order->id,
            'delivery_address' => $order->delivery_address,
            'lat' => $order->delivery_point->latitude,
            'lng' => $order->delivery_point->longitude,
        ]);
    }
}
