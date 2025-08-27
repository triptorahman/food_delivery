<?php

namespace App\Services;

use App\Models\DeliveryMan;
use App\Models\DeliveryAssignment;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class NearestDriverAssigner
{
    public function findNearestAvailable(Point $toPoint, int $maxMeters = 5000, $orderId = null): ?DeliveryMan
    {
        $query = DeliveryMan::query()
            ->where('status', 'available')
            ->whereNotNull('location');

        if ($orderId) {
            $excludedDriverIds = DeliveryAssignment::where('order_id', $orderId)
                ->where('status', 'rejected')
                ->pluck('delivery_man_id');
            $query->whereNotIn('id', $excludedDriverIds);
        }

        return $query
            ->whereDistanceSphere('location', $toPoint, '<=', $maxMeters)
            ->orderByDistanceSphere('location', $toPoint, 'asc')
            ->first();
    }

    public function assign(Order $order, int $maxMeters = 5000): ?DeliveryAssignment
    {
        $driver = $this->findNearestAvailable($order->delivery_point, $maxMeters, $order->id);
        if (!$driver) return null;

        // create assignment
        $assignment = DeliveryAssignment::create([
            'order_id' => $order->id,
            'delivery_man_id' => $driver->id,
            'status' => 'pending',
        ]);

        $order->update(['status' => 'awaiting_driver']);

        // notify driver
        $driver->notify(new \App\Notifications\DeliveryAssignmentCreated($assignment));

        return $assignment;
    }
}
