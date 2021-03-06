<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cart;
use Cache;
use Storage;
use Carbon\Carbon;
use App\Models\Create_Product;
use App\Models\User_Cart;
use App\Models\Cart_Details;

class CartController extends Controller
{
    public function cart($id) {
        $product_id =$id;
        $product = Create_Product::where('id',$product_id)->get();
        Cart::add(array('id' => $product_id, 'name' => $product[0]->name, 'qty' => 1, 'price' => $product[0]->price,'image'=>$product[0]->image));
	    $cart = $this->getcartCount();
	    $cartItems = $this->getCart();
	    $item = array('id' => $product_id, 'name' => $product[0]->name, 'qty' => 1, 'price' => $product[0]->price,'image'=>$product[0]->image);
	    $this->storeCart();
	    if(Auth::user())
	    	$this->addCartDB($item);
		return $cart;
	}
	public function getcartCount(){
		$cart = $this->getCart();
		$count = count($cart);
		if($count < 0)
			$count = 0;
		return $count;
	}
	public function showCart(){
		$cart = $this->getCart();
		$total = Cart::total();
		return view('cartview',compact('cart','total'));
	}
	public function destroy(){
		Cart::destroy();
	}
	public function itemRemove($id){
		$rowId = Cart::search(array('id' => $id));
		if($rowId == false)
			$returnVal = false;
		else{
			Cart::remove($rowId[0]);
			$returnVal = $id;
			if(Auth::user())
				$this->removeCartDB($id);
		}
		$this->storeCart();
		$count = $this->getcartCount();
		$arr = ['id'=>$id,'count'=>$count,'total' => Cart::total()];
		return $arr;
	}
	public function getCart(){
		$cart = Cart::content();
		return $cart;
	}
	public function destroyCart(){
		Cart::destroy();
	}
	public function updateCart($id,$qty){
		$rowId = Cart::search(array('id' => $id));
		Cart::update($rowId[0], $qty);
		$returnval = Cart::get($rowId[0]);
		if(!$returnval)
			$returnval=['id'=>'deleted','productid'=>$id];
		$returnval = [$returnval,'total' => Cart::total()];
		$this->storeCart();
		if(Auth::user())
			$this->updateCartDB($id,$qty);
		return $returnval;
	}
	public function updateCartDB($id,$qty){
		$user_id = Auth::user()->id;
		$cart_id = User_Cart::where('user_id',$user_id)->value('id');
		Cart_Details::where('cart_id',$cart_id)->where('product_id',$id)->update(['quantity' => $qty]);
	}
	public function removeCartDB($id){
		$user_id = Auth::user()->id;
		$cart_id = User_Cart::where('id',$user_id)->value('id');
		Cart_Details::where('cart_id',$cart_id)->where('product_id',$id)->delete();
	}
	public function addCartDB($item){
		$item['quantity'] = $item['qty'];
		$item['product_id'] = $item['id'];
		$item['product_name'] = $item['name'];
		$user_id = Auth::user()->id;
		$cart_id = User_Cart::where('id',$user_id)->value('id');
		$item['cart_id'] = $cart_id;
		Cart_Details::create($item);
	}
	public function cartToDB(){
		$user_id = Auth::user()->id;
		$status = null;
		$cart_id =User_Cart::where('user_id',$user_id)->where('status','true')->value('id');
		if($cart_id){
			Cart_Details::where('cart_id',$cart_id)->delete();
		}
		else{
			$usercart['user_id'] = $user_id;
			$usercart['status'] = 'true';
			$suc = User_Cart::create($usercart);
			$cart_id = $suc->id;
		}
		$cart = Cart::content();
		if($cart){
			foreach ($cart as $product) {
				$item['product_id'] = $product->id;
	            $item['product_name'] = $product->name;
	            $item['quantity'] = $product->qty;
	            $item['image'] = $product->image;
	            $item['price'] = $product->price;
	            $item['cart_id'] = $cart_id;
	            $itemres = Cart_Details::create($item)  ;
	        }
		}
    }
    public function storeCart(){
    	$cart = $this->getCart();
    	Storage::disk('local')->put('cart',json_encode($cart));
    }
    public function getStoredCart(){
    	if(Storage::disk('local')->has('cart')){
    		$storecart = Storage::disk('local')->get('cart');
	   		foreach (json_decode($storecart) as $itemdetail){
	   			Cart::add(array('id' => $itemdetail->id, 'name' => $itemdetail->name, 'qty' => $itemdetail->qty, 'price' => $itemdetail->price,'image'=>$itemdetail->image));
	   		}
    	}
    }
}
