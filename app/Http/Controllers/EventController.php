<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use App\User;
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
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\platforms;

class EventController extends Controller
{
    use DispatchesJobs;
    protected $AuthUser, $product;
    public function _construct(User $user,Create_Product $prod){
    	$this->AuthUser = Auth::user();
    	$this->product = $prod;
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
    public function storedata(Request $request,$platform){
        try {
            Excel::load($request->file('excel-file'), function ($reader) {

                foreach ($reader->toArray() as $row) {
                    $rowdata = $row;

                    $row = null;
                    $row['date'] = $rowdata['date'];
                    $row['site_name'] = $rowdata['site_name'];
                    $row['ad_unit'] = $rowdata['ad_unit'];
                    $row['ad_requests'] = $rowdata['ad_requests'];
                    $row['paid_impressions'] = $rowdata['paid_impressions'];
                    $row['revenue'] = $rowdata['revenue'];

                    $exist = platforms::where('date',$row['date'])->where('site_name',$row['site_name'])->where('ad_unit',$row['ad_unit'])->first();
                    
                    if($exist){
                        platforms::where('date',$row['date'])->where('site_name',$row['site_name'])->where('ad_unit',$row['ad_unit'])->delete();
                        platforms::firstOrCreate($row);
                    }
                    else{
                        platforms::firstOrCreate($row);
                    }
                }
            });
            
        } catch (Exception $e) {
            
        }
        return redirect('final-data');
    }
    public function getFinalData(){
        $data = platforms::get();
        return $data;
    }
}
