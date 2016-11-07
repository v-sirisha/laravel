<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use App\User;
use App\Models\Create_Product;
use Cart;
use App\Http\Controllers\CartController;

use App\Http\Requests;

class EventController extends Controller
{
    protected $AuthUser, $product;
    public function _construct(User $user,Create_Product $prod){
    	$this->AuthUser = Auth::user();
    	$this->product = $prod;
    }
    public function create(Request $req){
    	$productDetails = $req->all();
        $file = $req->file('image');
        $productDetails['image'] = $this->saveImage($file);
    	Create_Product::create($productDetails)  ;
        return redirect('/');
    }
    public function product_details($id){
        $record = Create_Product::where('productid',$id)->get();
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
    }
}
