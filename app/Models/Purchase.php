<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';
    protected $fillable = ['user_id','order_date','total','order_status'];

    /* Relationship b/w users and products */

    public function users(){
    	return $this->hasMany('App\Models\PurchaseItems','id','id');
    }
}
