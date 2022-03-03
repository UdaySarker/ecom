<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    protected $fillable=['order_id','order_date','delivery_date','delivery_status'];
    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }
}
