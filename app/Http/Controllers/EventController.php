<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use App\User;
use Lang;
use App\Models\Create_Product;
use App\Models\guests;
use App\Models\Purchase;
use App\Models\PurchaseItems;
use Cart;
use App\Http\Controllers\CartController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendBulkSMS;
use Carbon\Carbon;
use Session;
use App\Models\user_personal_details;
use Hash;
use App\Http\Requests;
use App\Http\Requests\pt_request;
use App\Http\Requests\PasswordRequest;
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

class EventController extends Controller
{
    use DispatchesJobs;
    protected $AuthUser, $product,$pr, $pt,$pt_dates;
    public function __construct(User $user,Create_Product $prod, platforms $pt, platform_dates $pt_dates, PR $pr){
    	$this->AuthUser = Auth::user();
    	$this->product = $prod;
        $this->pt = $pt;
        $this->pt_dates = $pt_dates;
        $this->pr = $pr;
    }
    public function welcome(Request $request){
        if(Auth::user()){
            $cartObj = new CartController();
            $cartObj->cartToDB();
        }
        else{
            if(!$request->session()->has('localstorage')){
                $cartObj = new CartController();
                $cartObj->getStoredCart();
                Session::set('localstorage', 'true');
            }
        }
        return view('events.show_products')->with('key','all');
    }
    public function create(Request $req){
    	$productDetails = $req->all();
        $file = $req->file('image');
        $productDetails['image'] = $this->saveImage($file);
    	Create_Product::create($productDetails)  ;
        return redirect('/');
    }
    public function product_details($id){
        $record = Create_Product::where('id',$id)->get();
        return view('events.details',compact('record'));
    }
    public function saveImage($image,$oldImage = null){
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image_path = public_path('images/productimages/');
        Image::make($image->getRealPath())->save($image_path . $image_name);
        if (!is_null($oldImage))
            File::delete($image_path . '/' . $oldImage);
        return $image_name;
    }
    public function checkoutCart(){
        $cartObj = new CartController();
        $cart =$cartObj->getCart();
        $total = Cart::total();
        return view('checkout',compact('cart','total'));
    }
    public function getRecords($key){
        
        if($key == 'all'){
            $records = Create_Product::get();
         }
        else{
            $records = Create_Product::where('name','LIKE', '%'.$key.'%')->get();
        } 
        return $records;
    }
    /*can use resource route */
    public function show_products($key){
        return view('events.show_products')->with('key',$key);
    }
    public function placeOrder(Request $req){
        $data = $req->data;
        $cartObj = new CartController();
        $cart =$cartObj->getCart();
        $total =Cart::total();;
        $guest['name'] = $data['firstname'].' '.$data['lastname'];
        $guest['email'] = $data['email'];
        $guest['mobile'] = $data['phone'];
        $guest['street'] = $data['street'];
        $guest['city'] = $data['city'];
        $guest['pincode'] = $data['zip'];
        $guest['state'] = $data['state'];
        $guest['country'] = $data['country'];
        $responsedata = guests::create($guest)  ;
        $res['user_id'] = $responsedata->id;
        $res['order_date'] = Carbon::now();
        $res['order_status'] = 'ordered';
        $res['total'] = $total;
        $purchaserow = Purchase::create($res);
        foreach ($cart as $product) {
            $item['transaction_id'] = $purchaserow->id;
            $item['product_id'] = $product->id;
            $item['total_price'] = $product->subtotal;
            $item['quantity'] = $product->qty;
            $item['image'] = $product->image;
            $itemres = purchaseItems::create($item)  ;
        }

        $message = "Hi " . $responsedata->name . "! Here's to notify, that your order is submitted successfully ";
        $this->sendSMS($responsedata->mobile, $message);
        if($purchaserow->id)
            return array('txnId'=>$purchaserow->id);
        else
            return "fail";
    }

    public function getOrderHistory($id){
        $totalorders =  Purchase::where('user_id',$id)->get();
        return view('orders_history',compact('totalorders'));
    }
    public function getOrderDetails($id){
        $order =  Purchase::where('id',$id)->get();
        $guest = guests::where('id',$id)->get();
        $orderdetails = purchaseItems::where('transaction_id',$id)->get();
        $subTotal =  Purchase::where('id',$id)->value('total');
        return view('order_details',compact('orderdetails'))->with('subTotal',$subTotal)->with('order',$order)->with('guest',$guest);
    }
    public function getUserDetails($id){
        $user_id = user_personal_details::where('user_id',$id)->value('user_id');
        if($user_id){
           $user = user_personal_details::where('user_id',$user_id)->get();
           $email = User::where('id',$id)->value('email');
           $user[0]->email = $email;
        }
        else{
            $user = User::where('id',$id)->get();
        }
        
        return view('customer_account',compact('user'));
    }

    public function sendEmailReminder(Request $request)
    { 
       
        $data = $request->all();
        \Mail::send('events.emails',$data, function ($message) use ($data)
        {
            $message->from($data['email'], $data['firstName']);
            $message->to('vangarasirishait@gmail.com')->subject($data['subject']);
        });
        \Mail::send('events.emails',['firstName'=>$data['firstName'],'ack'=>'true'], function ($message) use ($data)
        {
            $message->from(env('MAIL_USERNAME'),env('MAIL_NAME'));
            $message->to($data['email'])->subject($data['subject']);            
        });
        return redirect()->back();
    }
    public function updateUserDetails(Request $request, $id){
        $data = $request->all();
        $user_id = user_personal_details::where('user_id',$id)->value('user_id');
        unset($data['_token']);
        if($user_id){
           user_personal_details::where('user_id',$id)->update($data);
        }
        else{
            $data['user_id']=$id;
            user_personal_details::create($data);
        }
        return $this->getUserDetails($id);
    }
    public function changePassword(PasswordRequest $request, $id)
    {
        $input = $request->all();
        $user = User::find($id);
        if (Hash::check($input['oldPassword'], $user->password)) {
            if($input['newPassword'] == $input['confirmPassword']){
                $user->password = Hash::make($input['newPassword']);
                $user->save();
                return redirect()->back()->withErrors(array('Success' => 'Password saved successfully.'));
            }
            else {
                return redirect()->back()->withErrors(array('message' => 'New password and confirm password do not match. Try again.'));
            }
        } else {
            return redirect()->back()->withErrors(array('message' => 'Your old password is incorrect.'));
        }
    }
    public function sendSMS($mobile, $message)
    {
        $mobile = '7676071132';
        /* url we need to subscribe with any service provider*/
        $url = "http://alerts.solutionsinfini.com/api/v3/index.php?method=sms&api_key=A3caa5f2f890236eedd53a527703e8d9b1&to="
            . $mobile . "&sender=laravel&message=" . str_replace("&", "and", $message) . "&format=json";

        $url = str_replace(" ", "%20", $url);
        $this->dispatch(new SendBulkSMS($url));
    }
    public function storedata(pt_request $request,$platform){

        //dd($request->all());
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
                $exist = platforms::whereBetween('date',[$start_date,$end_date])->where('platform_name',$request->platform_name)->delete();
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
                        $row['tag_name'] = "";
                    if(isset($rowdata['site_name']))
                        $row['site_name'] = $rowdata['site_name'];
                    else
                        $row['site_name'] = "";
                    if(isset($rowdata['ad_unit']))
                        $row['ad_unit'] = $rowdata['ad_unit'];
                    else
                        $row['ad_unit'] = "";
                    if(isset($rowdata['device']))
                        $row['device'] = $rowdata['device'];
                    else
                        $row['device'] = "mixed";
                    if(isset($rowdata['country']))
                        $row['country'] = $rowdata['country'];
                    else
                         $row['country'] = "";
                    if(isset( $rowdata['buyer']))
                        $row['buyer'] = $rowdata['buyer'];
                    else
                         $row['buyer'] = "";
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

                    $tag_exist = tags::where('platform_name',$request->platform_name)->where('site_name',$tag['site_name'])->where('tag_id',$tag['tag_id'])->where('tag_name',$tag['tag_name'])->first();
                    
                    if($tag_exist == 0){
                        $tag_detail = tags::firstOrCreate($tag);
                        $raw_data['tag'] = $tag_detail['id'];
                    }
                    else{
                        $raw_data['tag'] = $tag_exist['id'];
                    }
                    pt_raw_data::firstOrCreate($raw_data);
                    //platforms::firstOrCreate($row);
                }
            });
        } catch (Exception $e) {
            
        }
        return redirect()->back();
    }
    public function getFinalData(){
        $data = platforms::paginate(100);
        foreach ($data as $key => $value) {
            $value->date = Carbon::parse($value->date)->format('Y-m-d');
        }
        return view('final-data',compact('data'));
    }
    public function addPRDetails(Request $request){
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

                            if(count($data) = 0){

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
}
