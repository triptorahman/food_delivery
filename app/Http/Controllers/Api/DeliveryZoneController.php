<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Restaurant, DeliveryZone};
use MatanYadaev\EloquentSpatial\Objects\{Point, Polygon};
use MatanYadaev\EloquentSpatial\Enums\Srid;

class DeliveryZoneController extends Controller
{
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
    public function store(Restaurant $restaurant, Request $request)
    {
        $data = $request->validate([
            'type'       => 'required|in:polygon,radius',
            'polygon'    => 'nullable|array',
            'geojson'    => 'nullable|string',
            'center_lat' => 'nullable|numeric',
            'center_lng' => 'nullable|numeric',
            'radius_km'  => 'nullable|numeric|min:0.1',
        ]);

        $zone = DeliveryZone::where('restaurant_id', $restaurant->id)->first();



        if ($data['type'] === 'polygon') {
            if (!empty($data['geojson'])) {
                $polygon = Polygon::fromJson($data['geojson'], Srid::WGS84->value);
            } else {
                $polygon = Polygon::fromArray($data['polygon'], Srid::WGS84->value);
            }

            if ($zone) {
                $zone->update([
                    'type' => 'polygon',
                    'polygon' => $polygon,
                    'center' => null,
                    'radius_km' => null,
                    'active' => true,
                ]);
            } else {
                $zone = DeliveryZone::create([
                    'restaurant_id' => $restaurant->id,
                    'type' => 'polygon',
                    'polygon' => $polygon,
                    'active' => true,
                ]);
            }
        } else {
            $request->validate([
                'center_lat' => 'required|numeric',
                'center_lng' => 'required|numeric',
                'radius_km'  => 'required|numeric|min:0.1',
            ]);

            if ($zone) {
                $zone->update([
                    'type' => 'radius',
                    'center' => new Point($data['center_lat'], $data['center_lng']),
                    'radius_km' => $data['radius_km'],
                    'polygon' => null,
                    'active' => true,
                ]);
            } else {
                $zone = DeliveryZone::create([
                    'restaurant_id' => $restaurant->id,
                    'type' => 'radius',
                    'center' => new Point($data['center_lat'], $data['center_lng']),
                    'radius_km' => $data['radius_km'],
                    'active' => true,
                ]);
            }
        }

        return response()->json([
            'message' => 'Delivery zone saved successfully.',
            'zone' => $zone,
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
