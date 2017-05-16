<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class platforms extends Model
{
    protected $table = 'platforms_data';
    protected $fillable = ['date','site_name','ad_unit','ad_requests','paid_impressions','revenue','platform_name'];
}
