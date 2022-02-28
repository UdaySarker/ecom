<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table="refund";
    protected $fillable=['order_id','user_id','delivery_status','type','payment_method','order_amount','refund_amount','reason','description','admin_status'];
}
