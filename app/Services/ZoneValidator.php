<?php

namespace App\Services;

use App\Models\DeliveryZone;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\DB;

class ZoneValidator
{
    /**
     * Returns the first matching active zone for the restaurant that contains the point,
     * or null if none matches.
     */
    public function findMatchingZone(int $restaurantId, Point $point): ?DeliveryZone
    {
        // 1) Check polygon zones (ST_Contains)
        $polygonZone = DeliveryZone::query()
            ->where('restaurant_id', $restaurantId)
            ->where('active', true)
            ->where('type', 'polygon')
            ->whereContains('polygon', $point)
            ->first();

        if ($polygonZone) return $polygonZone;

        // 2) Check radius zones (ST_Distance_Sphere in meters)
        return DeliveryZone::query()
            ->where('restaurant_id', $restaurantId)
            ->where('active', true)
            ->where('type', 'radius')
            ->whereNotNull('center')
            ->whereNotNull('radius_km')
            ->whereDistanceSphere('center', $point, '<=', 5000)
            ->first();
    }

    public function isInside(int $restaurantId, Point $point): bool
    {
        return (bool) $this->findMatchingZone($restaurantId, $point);
    }
}
