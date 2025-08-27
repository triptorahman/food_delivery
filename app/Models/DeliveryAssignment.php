<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['order_id','delivery_man_id','status','responded_at'];
    
    public function order() { return $this->belongsTo(Order::class); }
    public function driver() { return $this->belongsTo(DeliveryMan::class,'delivery_man_id'); }
}
