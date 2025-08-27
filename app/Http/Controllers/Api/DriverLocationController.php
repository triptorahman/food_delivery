<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryMan;

use MatanYadaev\EloquentSpatial\Objects\Point;

class DriverLocationController extends Controller
{
    public function updateLocation(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $driver = DeliveryMan::where('user_id', auth()->id())->firstOrFail();

        // Check for pending or accepted assignments
        $hasActiveAssignment = \App\Models\DeliveryAssignment::where('delivery_man_id', $driver->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();



        if ($hasActiveAssignment) {
            return response()->json([
                'status' => false,
                'message' => 'Location update cancelled: driver has active/pending assignment.'
            ], 403);
        }

        $driver->update([
            'location' => new Point($data['lat'], $data['lng']),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully.'
        ]);
    }

    public function updateStatus(DeliveryMan $driver, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:offline,available,busy',
        ]);

        $driver = DeliveryMan::where('user_id', auth()->id())->firstOrFail();

        // Check for pending or accepted assignments
        $hasActiveAssignment = \App\Models\DeliveryAssignment::where('delivery_man_id', $driver->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();



        if ($hasActiveAssignment) {
            return response()->json([
                'status' => false,
                'message' => 'Status update cancelled: driver has active/pending assignment.'
            ], 403);
        }


        $driver->update(['status' => $data['status']]);

        return response()->json([
            'status' => true,
            'message' => 'Driver status updated successfully.'
        ]);
    }
}
