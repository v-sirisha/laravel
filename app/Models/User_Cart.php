<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Cart extends Model
{
    protected $table = 'user_cart';
    protected $fillable = ['user_id','status'];
}
