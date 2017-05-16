<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PR extends Model
{
    protected $table = 'PR';
    protected $fillable = ['io_publisher_name','site_name','ad_unit','pubmanager','optimization_manager','date_of_onbording'];
}
