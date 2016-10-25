<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Create_Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['productid','name','product_description','quantity','image'];

    /* Relationship b/w users and products */

    public function users(){
    	return $this->hasMany('App\Models\sales','sale_product_id','productid');
    }
}
