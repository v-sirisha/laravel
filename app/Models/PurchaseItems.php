<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItems extends Model
{
    protected $table = 'purchase_items';
    protected $fillable = ['product_id','total_price','quantity','transaction_id'];
}
