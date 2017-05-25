<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    protected $table = 'tag';
    protected $fillable = ['platform_name','tag_id','tag_name','site_name'];
    
    
    public function scopeJoinIndexIo($query)
    {
        return $query->join('pt_raw_data','pt_raw_data.tag','=','tag.id')
        			->join('country','country_name','=','country')
        			->join('device','device_name','=','device')
        			->join('tag_index', 'tag_index.tag_id', '=', 'tag.id')
                    ->join('PR_table', 'PR_table.tag_index_placement', '=', 'tag_index.final_placement_name')
                    ->join('io_product','final_placement_tag','=','final_placement_name');
    }
}

