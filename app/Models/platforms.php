<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class platforms extends Model
{
    protected $table = 'platform_data';
    protected $fillable = ['date','platform_name','tag_id','tag_name','site_name','ad_unit','device','country','buyer','adserver_impressions','ssp_impressions','filled_impressions','gross_revenue'];
}
