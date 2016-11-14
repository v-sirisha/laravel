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

use App\Http\Requests;

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
    public function sendSMS($mobile, $message)
    {
        $mobile = '7676071132';
        /* url we need to subscribe with any service provider*/
        $url = "http://alerts.solutionsinfini.com/api/v3/index.php?method=sms&api_key=A3caa5f2f890236eedd53a527703e8d9b1&to="
            . $mobile . "&sender=laravel&message=" . str_replace("&", "and", $message) . "&format=json";

        $url = str_replace(" ", "%20", $url);
        $this->dispatch(new SendBulkSMS($url));
    }
}
