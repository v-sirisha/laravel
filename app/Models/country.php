<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    protected $table = 'country';
    protected $fillable = ['country_name','analytics_country_group','deal_country_group'];
    public function scopeJoinTagPlatform(){
    	return $this->join('pt_raw_data','country','=','country.id')
    				->join('tag','tag.id','=','pt_raw_data.tag');
    }
    public function sspsum()
	{
	    return $this->jointagplatform();
	}
}
