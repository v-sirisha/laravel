<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart_Details extends Model
{
    protected $table = 'cart_details';
    protected $fillable = ['cart_id','product_id','product_name','price','quantity','image'];
}
