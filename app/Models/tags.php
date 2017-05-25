<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    protected $table = 'tag';
    protected $fillable = ['platform_name','tag_id','tag_name','site_name'];
    
    
    public function scopeJoinIndexIo($query)
    {
        return $query->join('pt_raw_data','tag.id','=','pt_raw_data.tag')
        			->join('country','pt_raw_data.country','=','country.id')
        			->join('device','pt_raw_data.device','=','device.id')
        			->join('tag_index', 'tag.id', '=', 'tag_index.tag_id')
                    ->join('PR_table', 'tag_index.final_placement_name','=', 'PR_table.tag_index_placement')
                    ->join('io_product','tag_index.final_placement_name','=','io_product.final_placement_tag');
    }
}

