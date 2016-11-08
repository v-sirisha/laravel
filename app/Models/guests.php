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
    protected $fillable = [
        'name', 'email', 'password',
    ];
}
