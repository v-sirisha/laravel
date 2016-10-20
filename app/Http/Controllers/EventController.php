<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
    	Create_Product::create($productDetails)  ;
        return redirect('/');
    }
    public function show_products(){
    	$productsinfo = Create_Product::all();
    	return view('events.show_products',compact('productsinfo'));
    }
}
