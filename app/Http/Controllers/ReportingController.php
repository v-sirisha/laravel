<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lang;
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
use App\Models\pt_raw_data;

use App\Http\Requests;

class ReportingController extends Controller
{
    protected $pr, $pt,$pt_dates;
    public function _construct(platforms $pt, platform_dates $pt_dates, PR $pr){
        $this->pt = $pt;
        $this->pt_dates = $pt_dates;
        $this->pr = $pr;
    }
    public function getFinalData(){

        /*Platform_data table display */

        $data = platforms::paginate(100);
        foreach ($data as $key => $value) {
            $value->date = Carbon::parse($value->date)->format('Y-m-d');
        }
        return view('final-data',compact('data'));
    }

    public function addPRDetails(Request $request){
        /* Add pr data in pr_table */
        PR::firstOrCreate($request->except(['_token']));
    }

    public function getPRView(Request $request){
        $site_name = $request->site_name;
        $ad_unit = $request->ad_unit;
        $data = DB::table('platform_data')->leftjoin('PR',function($leftjoin){
                    $leftjoin->on('platform_data.platform_name','=','PR.platform_name')->on('platform_data.site_name','=','PR.site_name')
                    ->on('platform_data.tag_name','=','PR.tag_name');
                })->leftjoin('io_product','io_product.final_placement_tag','=','PR.final_placement_name')
                ->select('platform_data.*','PR.pp_name','PR.product_name','PR.final_placement_name','PR.actual_ad_unit','io_product.*')->paginate(100);
                
        foreach ($data as $key => $value) {
            $value->date = Carbon::parse($value->date)->format('Y-m-d');
        }
        return view('pr-data',compact('data'));
    }

    public function storedata(pt_request $request,$platform){

        $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:00');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:00');
        $data_exist = count(platform_dates::where('platform_name',$request->platform_name)->get());

        if($data_exist > 0){
            platform_dates::where('platform_name',$request->platform_name)->update(['start_date'=>$start_date,'end_date'=>$end_date]);
        }
        else{
            platform_dates::firstOrCreate(["platform_name"=>$request->platform_name,"start_date"=>$start_date,"end_date"=>$end_date]);
        }
        try {
            Excel::load($request->file('excel-file'), function ($reader) use($request){

                $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:00');
                $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:00');
                platforms::whereBetween('date',[$start_date,$end_date])->where('platform_name',$request->platform_name)->delete();
                
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
                        $row['tag_id'] = "default";
                    }
                    if(isset($rowdata['tag_name']))    
                        $row['tag_name'] = $rowdata['tag_name'];
                    else
                        $row['tag_name'] = "default";

                    if(isset($rowdata['site_name']))
                        $row['site_name'] = $rowdata['site_name'];
                    else
                        $row['site_name'] = "default";

                    if(isset($rowdata['ad_unit']))
                        $row['ad_unit'] = $rowdata['ad_unit'];
                    else
                        $row['ad_unit'] = "default";

                    if(isset($rowdata['device']))
                        $row['device'] = $rowdata['device'];
                    else
                        $row['device'] = "mixed";

                    if(isset($rowdata['country']))
                        $row['country'] = $rowdata['country'];
                    else
                         $row['country'] = "default";

                    if(isset( $rowdata['buyer']))
                        $row['buyer'] = $rowdata['buyer'];
                    else
                         $row['buyer'] = "default";

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
                    if($row['tag_id'] != "")
                        $tag['tag'] = $row['tag_id'];
                    else
                        $tag['tag'] = $row['tag_name'];

                    $raw_data['date'] = $row['date'];
                    $raw_data['ad_unit'] = $row['ad_unit'];
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
                    pt_raw_data::firstOrCreate($raw_data);
                }
            });
        } catch (Exception $e) {
            
        }
        return redirect()->back();
    }
    public function exportToPRExcel(){
        
        Excel::create('PR-Report', function($excel) {
            $excel->sheet('Sheet 1',function($sheet){

                $data = DB::table('platform_data')->leftjoin('PR',function($leftjoin){
                    $leftjoin->on('platform_data.platform_name','=','PR.platform_name')->on('platform_data.site_name','=','PR.site_name')
                    ->on('platform_data.tag_name','=','PR.tag_name');
                })->leftjoin('io_product','io_product.final_placement_tag','=','PR.final_placement_name')
                ->select('platform_data.*','PR.pp_name','PR.product_name','PR.final_placement_name','PR.actual_ad_unit','io_product.*')->paginate(100);
                
                foreach ($data as $key => $value) {
                    $value->date = Carbon::parse($value->date)->format('Y-m-d');
                    $final[] = array(
                        $value->platform_name,
                        $value->date,
                        $value->site_name,
                        $value->tag_id,
                        $value->tag_name,
                        $value->ad_unit,
                        $value->device,
                        $value->country,
                        $value->buyer,
                        $value->adserver_impressions,
                        $value->ssp_impressions,
                        $value->filled_impressions,
                        $value->gross_revenue,
                        $value->pp_name,
                        $value->product_name,
                        $value->actual_ad_unit,
                        $value->final_placement_name,
                        $value->deal_type,
                        $value->date_of_io_creation,
                        $value->publisher_manager,
                        $value->ym_manager,
                        $value->publisher_url,
                        $value->publisher_category,
                        $value->country_origin,
                        $value->language,
                        $value->business_name,
                        $value->billing_currency,
                    );
                }
                $sheet->fromArray($final, null, 'A1', false, false);
                $headings = array('Platform Name','Date', 'Site Name','Tag Id','Tag Name','Ad Unit','Device','Country','Buyer','Adserver Impressions','SSP Impressions','Filled Impressions','Gross Revenue','PP Name','Product Name','Actual Ad Unit','Final Placement name','Deal Type','Date of IO creation','Publisher Manager','YM Manager','Publisher Url','Publisher Category','Country of Origin','language','Business Name','Billing Currency');
                $sheet->prependRow(1, $headings);
            });
            
        })->export('xlsx');
    }
    public function importToDB(Request $request){
        try {
            Excel::load($request->file('file'), function ($reader) use($request){
                foreach ($reader->toArray() as $row) {
                    //dd($row);
                    switch ($request->table_name) {
                        case 'PR':
                            /* get placement tag from tag_index */

                            $data = tag::where('platform_name',$row['platform_name'])->where('site_name',$row['site_name'])
                            ->where('tag_id',$row['tag_id'])->where('tag_name',$row['tag_name'])->join('tag_index','tag.id','=','tag_index.tag_id')
                            ->get(['placement_tag']);

                            if(count($data) == 0){

                                /* update tag_index with tag_id and placement_tag */

                                $tag_index = tag::where('platform_name',$row['platform_name'])->where('site_name',$row['site_name'])
                                ->where('tag_id',$row['tag_id'])->where('tag_name',$row['tag_name'])->value('tag.id');
                                if($tag_index){
                                    $tag_index_details = tag_index::firstOrCreate(['tag_id'=>$tag_index,'final_placement_name'=>$row['final_placement_name']]);

                                    $pr_data['tag_index_placement'] = $tag_index_details['final_placement_name'];
                                    $pr_data['pp_name'] = $row['pp_name'];
                                    $pr_data['product_name'] = $row['product_name'];
                                    $pr_data['actual_ad_unit'] = $row['actual_ad_unit'];

                                    PR_table::firstOrCreate($pr_data);
                                }
                                else{

                                    /* tag details not in the platform_data */
                                }
                                //PR::firstOrCreate($row);
                            }
                            else{
                                /* If placement tag exist (duplicate data) : delete- update | nothing */
                            }
                            break;
                        case 'io_product':
                            $pr_tb_data = PR_table::where('tag_index_placement',$row['final_placement_tag'])->get();
                            if(count($pr_tb_data) == 0){
                                io_product::firstOrCreate($row);
                            }
                            else{
                                /*Duplicate row */
                            }
                            break;
                        case 'country':
                            $country = country::where('country_name',$row['country_name'])->value('id');
                            if(!$country){
                                country::firstOrCreate($row);
                            }
                            else{
                                /* Country exist in Table */
                                //country::where('country',$row['country'])->update('country_group'=>$row['country_group']);
                            }
                            
                            break;
                        case 'device':
                            $device = device::where('device_name',$row['device_name'])->value('id');
                            if(!$device){
                                device::firstOrCreate($row);
                            }
                            else{
                                /* device exist in Table */
                                //device::where('device_name',$row['country'])->update('country_group'=>$row['country_group']);
                            }
                            
                            break;
                        default:
                            break;
                    }
                    
                }
            });
        }
        catch(Exception $e){

        }
    }
    public function task(){
        return (PR::pluck('platform_name'));

        $pr = null;
        $io = null;

        $ids = tag::pluck('id');
        $tagIds = tag_index::pluck('tag_id');
        $miss_ids = array_except($ids,$tagIds);

        if(count($miss_ids) > 0){
            $miss_pr = tag::whereIn('id',$miss_ids)->get();
        }

        $tag_index_placement = PR_table::pluck('tag_index_placement');
        $final_placement_tag = io_product::pluck('final_placement_tag');
        $miss_placement = array_except($tag_index_placement,$final_placement_tag);

    }
    public function download_miss_data_excel($type){
        switch ($type) {
            case 'PR':
                Excel::create('PR-data', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){

                        $ids = tag::pluck('id');
                        $tagIds = tag_index::pluck('tag_id');
                        $miss_ids = array_except($ids,$tagIds);
                        if(count($miss_ids) > 0){
                            $miss_pr = tag::whereIn('id',$miss_ids)->get();
                        } 
                        
                        foreach ($miss_pr as $key => $value) {

                            $value->pp_name = "";
                            $value->product_name = "";
                            $value->actual_ad_unit = "";
                            $value->final_placement_name = "";

                            $final[] = array(
                                $value->platform_name,
                                $value->site_name,
                                $value->tag_id,
                                $value->tag_name,
                                $value->pp_name,
                                $value->product_name,
                                $value->actual_ad_unit,
                                $value->final_placement_name,
                            );
                        }
                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Platform Name', 'Site Name','Tag Id','Tag Name');
                        $sheet->prependRow(1, $headings);
                    });
                    
                })->export('xlsx');
                break;
            case 'io':
               Excel::create('io-data', function($excel) {
                    $excel->sheet('Sheet 1',function($sheet){

                        $tag_index_placement = PR_table::pluck('tag_index_placement');
                        $final_placement_tag = io_product::pluck('final_placement_tag');
                        $miss_placement = array_except($tag_index_placement,$final_placement_tag); 

                        for ($i=0; $i < count($miss_placement); $i++) { 

                            $final[] = array(
                                $miss_placement[$i],
                                "","","","","","","","","","",""
                            );
                        }

                        $sheet->fromArray($final, null, 'A1', false, false);
                        $headings = array('Final Placement name','Deal Type','Date of IO creation','Publisher Manager','YM Manager','Publisher Url','Publisher Category','Country of Origin','language','Business Name','Billing Currency');
                        $sheet->prependRow(1, $headings);
                    });
                })->export('xlsx');
                break;
            default:
                # code...
                break;
        }
    }
}
