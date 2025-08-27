<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Restaurant extends Model
{
    use HasFactory, HasSpatial;

    protected $fillable = ['name', 'location', 'address', 'phone', 'owner_id'];

    protected $casts = [
        'location' => Point::class,
    ];

    public function zones() { return $this->hasMany(DeliveryZone::class); }
}
