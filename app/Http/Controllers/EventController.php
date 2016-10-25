<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use App\User;
use App\Models\Create_Product;

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
    public function show_products(){
    	$productsinfo = Create_Product::all();
    	return view('events.show_products',compact('productsinfo'));
    }
    public function getRecords(){
        $records = Create_Product::all();
        return $records;
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
}
