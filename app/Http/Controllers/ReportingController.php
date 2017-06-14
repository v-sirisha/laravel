<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Lang;
use Schema;
use Carbon\Carbon;
use App\Http\Requests\pt_request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\platforms;
use App\Models\platform_dates;
use App\Models\country;
use App\Models\device;
use App\Models\PR;
use App\Models\PR_table;
use App\Models\io_product;
use App\Models\tags;
use App\Models\tag_index;
use App\Models\pt_raw_data;
use App\Models\deal_rate;

use App\Http\Requests;

class ReportingController extends Controller
{
    protected $pt_raw_data,$pt_dates,$tags,$pr,$tag_index,$io;
    public function __construct(tags $tag, pt_raw_data $pt, platform_dates $pt_dates, PR_table $io, io_product $pr, tag_index $tag_index,Country $country){
        $this->pt_raw_data = $pt;
        $this->pt_dates = $pt_dates;
        $this->io = $io;
        $this->pr = $pr;
        $this->tags = $tag;
        $this->tag_index = $tag_index;
        $this->country = $country;
    }
    public function index(){
        $pr_miss = $this->task('PR');
        $io_miss = count($this->task('io_product'));
        $parent_publishers = io_product::distinct()->pluck('parent_publisher');
        $ym_managers = io_product::distinct()->pluck('ym_manager');
        $product_names = pr_table::distinct()->pluck('product_name');
        $deal_miss = count($this->task('deal_rate'));
        $country = $this->task('country');
        $device = $this->task('device');
        $gen_col = array('id','created_at','updated_at','tag_index_placement','tag','size','device','country','buyer','final_placement_tag','final_placement_name');
        $columns = Schema::getColumnListing('pt_raw_data');
        array_unshift($columns, 'platform_name');
        $columns = array_merge($columns,Schema::getColumnListing('PR_table'));
        $columns = array_merge($columns,Schema::getColumnListing('device'));        
        $columns = array_merge($columns,Schema::getColumnListing('country'));
        $columns = array_merge($columns,Schema::getColumnListing('io_product'));
        $columns = array_unique(array_diff($columns, $gen_col));
        $col_arr = array();
        foreach ($columns as $key => $value) {
           $str = str_replace('_', ' ', $value);
           $str = ucfirst($str);
           $col_arr[$value] = $str;
        } 
        $columns = $col_arr;
        return view('reporting.index',compact('deal_miss','pr_miss','io_miss','parent_publishers','ym_managers','product_names','country','device','columns'));
    }
    public function storedata(Request $request,$platform){
        //dd($request->all());

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:00');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:00');
        $data_exist = count(platform_dates::where('platform_name',$request->platform_name)->get());
        $data = pt_raw_data::whereBetween('date',[$start_date,$end_date])->leftjoin('tag',function($leftjoin) use($request){
            $leftjoin->on('tag.id','=','pt_raw_data.tag');
        })->where('tag.platform_name',$request->platform_name)->delete();
        if($data_exist > 0){
            platform_dates::where('platform_name',$request->platform_name)->update(['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        else{
            platform_dates::firstOrCreate(["platform_name"=>$request->platform_name,"start_date"=>$start_date,"end_date"=>$end_date]);
        }
        try {
            Excel::load($request->file('excel-file'), function ($reader) use($request){
               // dd('calling');
                $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:00');
                $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:00');
                //pt_raw_data::leftjoin('tag','tag.id','=','pt_raw_data.tag')->whereBetween('date',[$start_date,$end_date])->where('tag.platform_name',$request->platform_name)->delete();
                
                foreach ($reader->toArray() as $row) {

                    $rowdata = $row;
                    $row = null;

                    if(!is_a($rowdata['date'], 'DateTime')){
                        $rowdata['date'] = Carbon::parse($rowdata['date'])->format('Y-m-d H:i:00');
                    }
                    $row['date'] = $rowdata['date'];
                    $row['platform_name'] = $request->platform_name;

                    if(isset($rowdata['tag_id'])){
                        $row['tag_id'] = $rowdata['tag_id'];
                    }
                    else{
                        $row['tag_id'] = null;
                    }
                    if(isset($rowdata['tag_name']))    
                        $row['tag_name'] = $rowdata['tag_name'];
                    else
                        $row['tag_name'] = null;

                    if(isset($rowdata['site_name']))
                        $row['site_name'] = $rowdata['site_name'];
                    else
                        $row['site_name'] = null;

                    if(isset($rowdata['size']))
                        $row['size'] = $rowdata['size'];
                    else
                        $row['size'] = null;

                    if(isset($rowdata['device']))
                        $row['device'] = $rowdata['device'];
                    else
                        $row['device'] = "mixed";

                    if(isset($rowdata['country']))
                        $row['country'] = $rowdata['country'];
                    else
                         $row['country'] = null;

                    if(isset( $rowdata['buyer']))
                        $row['buyer'] = $rowdata['buyer'];
                    else
                         $row['buyer'] = null;

                    if(isset( $rowdata['adserver_impressions']))
                        $row['adserver_impressions'] = $rowdata['adserver_impressions'];
                    else
                         $row['adserver_impressions'] = 0;

                    if(isset($rowdata['ssp_impressions']))
                        $row['ssp_impressions'] = $rowdata['ssp_impressions'];
                    else
                         $row['ssp_impressions'] = 0;

                    if(isset($rowdata['filled_impressions']))
                        $row['filled_impressions'] = $rowdata['filled_impressions'];
                    else
                        $row['filled_impressions'] = 0;

                    if(isset($rowdata['gross_revenue']))
                        $row['gross_revenue'] = $rowdata['gross_revenue'];
                    else
                         $row['gross_revenue'] = 0.00;

                    $tag['platform_name'] = $row['platform_name'];
                    $tag['tag_id'] = $row['tag_id'];
                    $tag['tag_name'] = $row['tag_name'];
                    $tag['site_name'] = $row['site_name'];

                    if($row['country'] != null){
                        $countryId = country::where('country_name',$row['country'])->value('id');
                        if($countryId){
                            $row['country'] = $countryId;
                        }
                        else{
                            $country = country::firstOrCreate(['country_name'=>$row['country'],'analytics_country_group'=>"",'deal_country_group'=>""]);
                            if($country){
                                $row['country'] = $country->id;
                            }
                        }
                    }
                    if($row['device'] != null){
                        $deviceId = device::where('device_name',$row['device'])->value('id');
                        if($deviceId){
                            $row['device'] = $deviceId;
                        }
                        else{
                            $device = device::firstOrCreate(['device_name'=>$row['device'],'device_group'=>""]);
                            if($device){
                                $row['device'] = $device->id;
                            }
                        }
                    }
                    //dd($row['country']); 

                    $raw_data['date'] = $row['date'];
                    $raw_data['size'] = $row['size'];
                    $raw_data['device'] = $row['device'];
                    $raw_data['country'] = $row['country'];
                    $raw_data['buyer'] = $row['buyer'];
                    $raw_data['adserver_impressions'] = $row['adserver_impressions'];
                    $raw_data['ssp_impressions'] = $row['ssp_impressions'];
                    $raw_data['filled_impressions'] = $row['filled_impressions'];
                    $raw_data['gross_revenue'] = $row['gross_revenue'];

                    $tag_exist = tags::where('platform_name',$request->platform_name)->where('site_name',$tag['site_name'])->where('tag_id',$tag['tag_id'])->where('tag_name',$tag['tag_name'])->value('id');
                    
                    if(!$tag_exist){
                        $tag_detail = tags::firstOrCreate($tag);
                        $raw_data['tag'] = $tag_detail['id'];
                    }
                    else{
                        $raw_data['tag'] = $tag_exist;
                    }
                    //dd($raw_data);
                    pt_raw_data::firstOrCreate($raw_data);
                }
            })->setFileName($request->platform_name)->store('xls');;
        } catch (Exception $e) {
            dd('catch');
        }
        return redirect()->back();
    }
    public function importToDB(Request $request){
        try {
            Excel::load($request->file('file'), function ($reader) use($request){
                foreach ($reader->toArray() as $row) {
                    //dd($row);
                    switch ($request->table_name) {
                        case 'PR':
                            /* get placement tag from tag_index */
                            $data = tags::where('platform_name',$row['platform_name'])->where('site_name',$row['site_name'])
                            ->where('tag.tag_id',$row['tag_id'])->where('tag_name',$row['tag_name'])->join('tag_index','tag.id','=','tag_index.tag_id')
                            ->get(['final_placement_name']);

                            $tagId = tags::where('platform_name',$row['platform_name'])->where('site_name',$row['site_name'])
                                        ->where('tag.tag_id',$row['tag_id'])->where('tag_name',$row['tag_name'])->value('id');
                            //dd($row['io_publisher_name']);
                            if($tagId && $row['io_publisher_name'] != null && $row['product_name'] != null && $row['io_size'] != null){
                                $pr_data['io_publisher_name'] = $row['io_publisher_name'];
                                $pr_data['product_name'] = $row['product_name'];
                                $pr_data['io_size'] = $row['io_size'];

                                $pr_details  = PR_table::where('io_publisher_name',$row['io_publisher_name'])
                                        ->where('product_name',$row['product_name'])
                                        ->where('io_size',$row['io_size'])->first();
                                if(count($pr_details) == 0){
                                    $pr_data['tag_index_placement'] = $row['io_publisher_name'].'_'.$row['product_name'].'_'.$row['io_size'];
                                    $pr_details = PR_table::firstOrCreate($pr_data);
                                }
                                //dd($pr_details);
                                $prId = $pr_details['io_publisher_name'].'_'.$pr_details['product_name'].'_'.$pr_details['io_size'];
                                $exist_tagIndex = tag_index::where('tag_id',$tagId)->first();
                                if($exist_tagIndex){
                                    $tag_index_details = tag_index::where('tag_id',$exist_tagIndex->id)->update(['final_placement_name'=>$prId]);
                                }
                                else{
                                    $tag_index_details = tag_index::firstOrCreate(['tag_id'=>$tagId,'final_placement_name'=>$prId]);
                                }
                            }
                            else{

                            }
                            break;
                        case 'io_product':
                            //dd($row);
                            if($row['date_of_io_creation'] != null){
                                if(!is_a($row['date_of_io_creation'], 'DateTime')){
                                    $row['date_of_io_creation'] = Carbon::parse($row['date_of_io_creation'])->format('Y-m-d H:i:00');
                                }
                            }
                            
                        
                            $pr_tb_placementId = tag_index::where('final_placement_name',$row['final_placement_tag'])->first();
                            
                            if($pr_tb_placementId){
                                $io_product_placementId = io_product::where('final_placement_tag',$row['final_placement_tag'])->value('final_placement_tag');
                                
                                $data['final_placement_tag'] = $row['final_placement_tag'];
                                $data['ad_unit_size'] = $row['ad_unit_size'];
                                $data['deal_type'] = $row['deal_type'];
                                $data['parent_publisher'] = $row['parent_publisher'];
                                $data['date_of_io_creation'] = $row['date_of_io_creation'];
                                $data['publisher_manager'] = $row['publisher_manager'];
                                $data['ym_manager'] = $row['ym_manager'];
                                $data['publisher_url'] = $row['publisher_url'];
                                $data['publisher_category'] = $row['publisher_category'];
                                $data['country_origin'] = $row['country_origin'];
                                $data['language'] = $row['language'];
                                $data['business_name'] = $row['business_name'];
                                $data['billing_currency'] = $row['billing_currency'];
                                //dd($data);
                                if($io_product_placementId){
                                    io_product::where('final_placement_tag',$row['final_placement_tag'])->update($data);
                                }
                                else{
                                    io_product::firstOrCreate($data);
                                }
                            }
                            else{
                                /* final placemet tag not exist in pr_table new entry in io_excel */
                            }
                            break;
                        case 'country':
                            $country = country::where('country_name',$row['country'])->value('id');
                            if(!$country){
                                country::firstOrCreate(["country_name"=>$row['country'],"analytics_country_group"=>$row['analytics_country_group'],"deal_country_group"=>$row['deal_country_group']]);
                            }
                            else{
                                country::where('country_name',$row['country'])->update(["analytics_country_group" => $row['analytics_country_group'],"deal_country_group" => $row['deal_country_group']]);
                            }
                            
                            break;
                        case 'device':
                            $device = device::where('device_name',$row['device'])->value('id');
                            if(!$device){
                                device::firstOrCreate(["device_name"=>$row['device'],"device_group"=>$row['device_group']]);
                            }
                            else{
                                device::where('device_name',$row['device'])->update(["device_group"=>$row['device_group']]);
                            }
                            
                            break;
                        case 'deal_rate':
                            $deal_rate = deal_rate::where('parent_placement_name',$row['parent_placement_name'])
                                        ->where('deal_country_group',$row['deal_country_group'])
                                        ->where('device_group',$row['device_group'])->get();
                            if($deal_rate){
                                deal_rate::where('parent_placement_name',$row['parent_placement_name'])
                                        ->where('deal_country_group',$row['deal_country_group'])
                                        ->where('device_group',$row['device_group'])
                                        ->update(['parent_placement_name'=>$row['parent_placement_name'],'deal_country_group'=>$row['deal_country_group'],'device_group'=>$row['device_group'],'deal_rate'=>$row['deal_rate']]);
                            }
                            break;
                        default:
                        dd('calling');
                            break;
                    }
                }
            });
            return redirect()->back();
        }
        catch(Exception $e){

        }
    }
    public function task($table){
        $pr = null;
        $io = null;
        if($table == "PR"){

            $ids = tags::pluck('id')->toArray();
            $tagIds = tag_index::distinct()->pluck('tag_id')->toArray();

            $pr_miss_ids = array_diff($ids, $tagIds);

            $pr_null_placement = pr_table::where('io_publisher_name', '=', '')->orWhereNull('io_publisher_name')
                        ->orWhere('product_name', '=', '')->orWhereNull('product_name')
                        ->orWhere('io_size', '=', '')->orWhereNull('io_size')
                        ->pluck('tag_index_placement'); /* exist in pr_table but empty values */

            $empty_id_arr = tag_index::whereIn('final_placement_name',$pr_null_placement)->get(['tag_id'])->toArray();

            $total_ids = array_merge($pr_miss_ids,$empty_id_arr);
            //dd($pr_miss_ids,$empty_id_arr,$total_ids);
            $pr_null_arr = null;
            if(count($total_ids) > 0){
                $pr_null_arr = tags::whereIn('tag.id',$total_ids)
                            ->leftjoin('tag_index','tag.id','=','tag_index.tag_id')
                            ->leftjoin('PR_table','tag_index.final_placement_name','=','PR_table.tag_index_placement')
                            ->get(['tag.*','tag_index.final_placement_name','PR_table.*']);
            }
            else{
                $this->getDealRate();
            }
            //dd($pr_null_arr);
            return $pr_null_arr;
        }
        else if($table == "io_product") {

            $tag_index_placement = tag_index::distinct()->pluck('final_placement_name')->toArray();
            $final_placement_tag = io_product::distinct()->pluck('final_placement_tag')->toArray();

            $io_miss = array_diff($tag_index_placement, $final_placement_tag); /* exist in pr_table but not in io_product */

            $io_null_ids = io_product::where('deal_type', '=', '')->orWhereNull('deal_type')
                        ->orWhere('parent_publisher', '=', '')->orWhereNull('parent_publisher')
                        ->orWhere('ad_unit_size', '=', '')->orWhereNull('ad_unit_size')
                        ->orWhere('date_of_io_creation', '=', '')->orWhereNull('date_of_io_creation')
                        ->orWhere('parent_publisher', '=', '')->orWhereNull('parent_publisher')
                        ->orWhere('ym_manager', '=', '')->orWhereNull('ym_manager')
                        ->orWhere('publisher_url', '=', '')->orWhereNull('publisher_url')
                        ->orWhere('publisher_category', '=', '')->orWhereNull('publisher_category')
                        ->orWhere('country_origin', '=', '')->orWhereNull('country_origin')
                        ->orWhere('language', '=', '')->orWhereNull('language')
                        ->orWhere('business_name', '=', '')->orWhereNull('business_name')
                        ->orWhere('billing_currency', '=', '')->orWhereNull('billing_currency')
                        ->pluck('final_placement_tag')->toArray(); /* placement tag exist in io_product null values */

            
            
            $total_ids = array_merge($io_miss,$io_null_ids);
            //$io_null_arr = io_product::whereIn('final_placement_tag',$io_null_ids)->get();
            $io_null_arr = tag_index::whereIn('final_placement_name',$total_ids)
                            ->leftjoin('io_product','tag_index.final_placement_name','=','io_product.final_placement_tag')
                            ->get();
            //dd($io_null_arr);
            return $io_null_arr;
        }
        else if($table == "country"){
            $miss_countries = country::where('analytics_country_group','=', '')->orWhereNull('analytics_country_group')
                        ->orWhere('deal_country_group', '=', '')->orWhereNull('deal_country_group')
                        ->get();
            return $miss_countries;
        }
        else if($table == 'device'){
            $miss_device = device::where('device_group','=', '')->orWhereNull('device_group')
                        ->get();
            return $miss_device;
        }
        else if($table == 'deal_rate'){
            $deal_miss = deal_rate::where('deal_rate','=','')->orWhereNull('deal_rate')
                        ->get();
            return $deal_miss;
        }
    }
    public function download_miss_data_excel($type){
       // dd($type);
        switch ($type) {
            case 'PR':
                Excel::create('PR-data', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){

                        $total_arr = $this->task('PR');
                        //dd($total_arr);
                        foreach ($total_arr as $key => $value) {

                            $final[] = array(
                                $value->platform_name,
                                $value->site_name,
                                $value->tag_id,
                                $value->tag_name,
                                $value->size,
                                $value->io_publisher_name,
                                $value->product_name,
                                $value->io_size,
                            );
                        }
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Platform Name', 'Site Name','Tag Id','Tag Name','Size','IO Publisher Name','Product Name','Io Size');
                        $sheet->prependRow(1, $headings);
                    });
                    
                })->export('xlsx');
                return view('/');
                break;
            case 'io_product':
               Excel::create('io-data', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $io_arr = $this->task('io_product');
                        
                        if(count($io_arr)>0){
                            foreach ($io_arr as $key => $value) {
                                $final[] = array(
                                    $value->final_placement_name,
                                    $value->ad_unit_size,
                                    $value->parent_publisher,
                                    $value->deal_type,
                                    $value->date_of_io_creation,
                                    $value->publisher_manager,
                                    $value->ym_manager,
                                    $value->publisher_url,
                                    $value->publisher_category,
                                    $value->country_origin,
                                    $value->language,
                                    $value->business_name,
                                    $value->billing_currency
                                );
                            }
                        }

                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Final Placement Tag','Ad Unit Size','Parent Publisher','Deal Type','Date of IO creation','Publisher Manager','YM Manager','Publisher Url','Publisher Category','Country Origin','language','Business Name','Billing Currency');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'country':
                Excel::create('country', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $miss = $this->task('country');
                        foreach ($miss as $key => $value) {
                            $final[] = array(
                                $value->country_name,
                                $value->analytics_country_group,
                                $value->deal_country_group
                            );
                        }
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Country','Analytics Country Group','Deal Country Group');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'device':
                Excel::create('device', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $miss = $this->task('device');
                        foreach ($miss as $key => $value) {
                            $final[] = array(
                                $value->device_name,
                                $value->device_group
                            );
                        }
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Device','Device Group');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'deal_rate':
                Excel::create('deal rate', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $miss = $this->task('deal_rate');
                        foreach ($miss as $key => $value) {
                            $final[] = array(
                                $value->parent_placement_name,
                                $value->device_group,
                                $value->deal_country_group,
                                $value->deal_rate
                            );
                        }
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Parent Placement Name','Device Group','Deal Country Group','Deal Rate');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            default:
                # code...
                break;
        }
    }
    public function updateon_screen($type){
        $data = $this->task($type);
        //dd($data);
        return view('reporting.update_screen',compact('data','type'));
    }
    public function getEndDate($pt_name){
        $date = platform_dates::where('platform_name',$pt_name)->value('end_date');
        if($date)
            $date = Carbon::parse($date)->format('d M Y');
        return $date;
    }
    public function defaultTemplates($type){
        switch ($type) {
            case 'tags':                
                Excel::create('tags', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $final[] = array('','','','','','','','','','','','');
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Date','Site Name','Tag Id','Tag Name','Size','Country','Device','Buyer','AdServer Impressions','SSP Impressions','Filled Impressions','Gross Revenue');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');                
                break;
            case 'PR':
                Excel::create('Io Publisher', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $final[] = array('','','','','','','','');
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Platform Name','Site name','Tag Id','Tag Name','Io Publisher Name','Product Name','Size');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'io_product':
                Excel::create('Publisher Manager', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $final[] = array('','','','','','','','','','','','','');
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Final Placement name','Ad Unit Size','Deal Type','Date of IO creation','Publisher Manager','YM Manager','Publisher Url','Publisher Category','Country of Origin','language','Business Name','Billing Currency');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'country':
                Excel::create('Country', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $final[] = array('','','');
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Country','Analytics Country Group','Deal Country Group');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            case 'device':
                Excel::create('Device', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){
                        $final[] = array('','');
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Device','Device Group');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            default:
                # code...
                break;
        }
    }
    public function getFinalPR_Report(Request $request){
        //dd($request->all());
        if(isset($request->control_1)){
            $columns = $request->control_1;
        }
        else{
            $columns = null;
        }

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:00');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:00');
        $data = $this->tags->joinindexio()->whereBetween('date',[$start_date,$end_date])->get();
        
        //dd($data);
        if(isset($request->product_name)){
            $data = $data->where('product_name',$request->product_name);
        }
        if(isset($request->publisher_manager)){
            $data = $data->where('publisher_manager',$request->publisher_manager);
        }
        if(isset($request->ym_manager)){
            $data = $data->where('ym_manager',$request->ym_manager);
        }
        
        //dd($data);
        if(count($data) > 0){
            Excel::create('Io Publisher', function($excel) use($data,$columns){
                $excel->sheet('Sheet 1',function($sheet) use($data,$columns){
                    $final = array();
                    foreach ($data as $key => $value) {
                        $final2 = array();
                        for ($i=0; $i < count($columns); $i++) { 
                           array_push($final2, $value->$columns[$i]);
                        }
                        $final[$key] = $final2;
                    }
                    $sheet->fromArray($final, null, 'A1', false, false);
                    $sheet->prependRow(1, $columns);
                });
            })->export('xlsx');
        }
        else{
            $msg = 'empty';
            return $msg;
        } 
    }
    public function onScreenAction(Request $request){
        $type = $request->type;
        //return ($request->all());
        switch ($type) {
            case 'PR':
                $platform_name = $request->platform_name;
                $site_name =  $request->site_name;
                $tag_id =  $request->tag_id;
                $tag_name =  $request->tag_name;
                $id = tags::where('platform_name',$platform_name)
                        ->where('site_name',$site_name)
                        ->where('tag_id',$tag_id)
                        ->where('tag_name',$tag_name)->value('id');
                $tag_index_id = tag_index::where('tag_id',$id)->get();
                $final_placement_name = $request->io_publisher_name.'_'.$request->product_name.'_'.$request->io_size;
                if(count($tag_index_id)>0){
                    $success = PR_table::where('tag_index_placement',$final_placement_name)
                            ->update(['io_publisher_name'=>$request->io_publisher_name,'product_name'=>$request->product_name,'io_size'=>$request->io_size]);
                }
                else{

                    $success = PR_table::firstOrCreate(['tag_index_placement'=>$request->final_placement_name,'io_publisher_name'=>$request->io_publisher_name,'product_name'=>$request->product_name,'io_size'=>$request->io_size]);
                    if($success){
                        tag_index::firstOrCreate(['tag_id'=>$id,'final_placement_name'=>$request->final_placement_name]);
                    }
                    
                }
                return $success;
                break;
            case 'io_product':
                $data =  $request->except(['type']);
                $success = io_product::where('final_placement_tag',$request->final_placement_tag)
                            ->update($data);
                return ($success);
                break;
            case 'country':
                $data =  $request->except(['type']);
                $success = country::where('country_name',$request->country_name)
                            ->update($data);
                return $success;
                break;
            case 'device':
                $data =  $request->except(['type']);
                $success = device::where('device_name',$request->device_name)
                            ->update($data);
                return $success;
                break;
            case 'deal_rate':
                $data =  $request->except(['type']);
                $success = deal_rate::where('parent_placement_name',$request->parent_placement_name)
                            ->where('deal_country_group',$request->deal_country_group)
                            ->where('device_group',$request->device_group)
                            ->update($data);
                return $success;
                break;
            default:
                # code...
                break;
        }
    }
    public function getDealRate(){
        $final = array();
        $data = tags::leftjoin('pt_raw_data','tag.id','=','pt_raw_data.tag')
                ->leftjoin('tag_index','tag.id','=','tag_index.tag_id')
                ->leftjoin('device','pt_raw_data.device','=','device.id')
                ->leftjoin('country','pt_raw_data.country','=','country.id')->get(['tag_index.final_placement_name','device.device_group','country.deal_country_group']);
        foreach ($data as $key => $value) {
            $success = deal_rate::where('parent_placement_name',$value->parent_placement_name)
                            ->where('deal_country_group',$value->deal_country_group)
                            ->where('device_group',$value->device_group)->get();
            if(!$success)
            deal_rate::firstOrCreate(['parent_placement_name'=>$value->final_placement_name,'deal_country_group'=>$value->deal_country_group,'device_group'=>$value->device_group,'deal_rate'=>'']);
        }
    }
}

