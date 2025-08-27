<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Order extends Model
{
    use HasFactory, HasSpatial;

    protected $fillable = [
        'restaurant_id','delivery_address','delivery_point','status','assigned_delivery_man_id'
    ];

    protected $casts = [
        'delivery_point' => Point::class,
    ];

    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function driver() { return $this->belongsTo(DeliveryMan::class,'assigned_delivery_man_id'); }
    public function assignments() { return $this->hasMany(DeliveryAssignment::class); }
}
