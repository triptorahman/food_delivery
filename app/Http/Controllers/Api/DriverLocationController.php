<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryMan;

use MatanYadaev\EloquentSpatial\Objects\Point;

class DriverLocationController extends Controller
{
    public function updateLocation(DeliveryMan $driver, Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $driver->update([
            'location' => new Point($data['lat'], $data['lng']),
        ]);

        return response()->json(['ok' => true]);
    }

    public function updateStatus(DeliveryMan $driver, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:offline,available,busy',
        ]);
        $driver->update(['status' => $data['status']]);

        return response()->json(['ok' => true]);
    }
}
