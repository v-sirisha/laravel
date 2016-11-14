<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class guests extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'guests';
    protected $fillable = [
        'name', 'email', 'password','street','city','pincode','state','country',
    ];
}
