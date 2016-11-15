<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_personal_details extends Model
{
    protected $table = 'user_personal_details';
    protected $fillable = ['user_id','firstname','lastname','street','city','country','pincode','state','mobile','company'];
}
