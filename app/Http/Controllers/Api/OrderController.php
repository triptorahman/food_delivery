<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\ZoneValidator;
use App\Services\NearestDriverAssigner;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        protected ZoneValidator $zoneValidator,
        protected NearestDriverAssigner $assigner
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id'   => 'required|exists:restaurants,id',
            'delivery_address'=> 'required|string',
            'lat'             => 'required|numeric',
            'lng'             => 'required|numeric',
        ]);

        $point = new Point($data['lat'], $data['lng']);

        $zone = $this->zoneValidator->findMatchingZone($data['restaurant_id'], $point);
        if (!$zone) {
            return response()->json(['message' => 'Delivery address is outside delivery zones.'], 422);
        }

        $result = DB::transaction(function () use ($data, $point) {
            $order = Order::create([
                'restaurant_id'    => $data['restaurant_id'],
                'delivery_address' => $data['delivery_address'],
                'delivery_point'   => $point,
                'status'           => 'pending',
            ]);

            $meters = config('delivery.assignment_search_radius');
            $assignment = $this->assigner->assign($order, $meters);

            return [$order, $assignment];
        });

        [$order, $assignment] = $result;

        if (!$assignment) {
            $order->update(['status' => 'awaiting_driver']);
            return response()->json([
                'order' => $order,
                'message' => 'No available drivers nearby at the moment.'
            ], 201);
        }

        return response()->json([
            'order' => $order->fresh(),
            'assignment' => $assignment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
