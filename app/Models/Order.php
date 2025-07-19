<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function order_detail(){
        return $this->hasMany(OrderDetail::class, 'order_id')->withTrashed();
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    public function getOrderNumber(){
        $sequence = $this->id;
        if($sequence > 4435){
            $greater_than_4435 = $sequence - 4435;
            $sequence = 1 + $greater_than_4435;
        }
        return $sequence;
    }
}
