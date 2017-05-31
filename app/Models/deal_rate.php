<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class deal_rate extends Model
{
    protected $table = 'deal_rate';
    protected $fillable = ['parent_placement_name','device_group','deal_country_group','deal_rate'];
}
