<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class create_orders extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id','name','mobile','email','address','deliveryMethod','payment'];

    /* Relationship b/w users and products */

    public function users(){
    	return $this->hasMany('App\Models\users','id','user_id');
    }
    public function orderItems(){
    	return $this->hasMany('App\Models\items','order_id','orderId');
    }
}
