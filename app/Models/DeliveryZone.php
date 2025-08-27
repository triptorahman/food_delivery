<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DeliveryZone extends Model
{
    use HasSpatial;

    protected $fillable = ['restaurant_id','type','polygon','center','radius_km','active'];

    protected $casts = [
        'polygon' => Polygon::class,
        'center'  => Point::class,
    ];

    public function restaurant() { return $this->belongsTo(Restaurant::class); }
}
