<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Notifications\Notifiable;

class DeliveryMan extends Model
{
    use HasSpatial, Notifiable, HasFactory;

    protected $fillable = ['name','phone','status','location','last_seen_at'];

    protected $casts = [
        'location' => Point::class,
        'last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
