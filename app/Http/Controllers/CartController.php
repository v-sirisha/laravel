<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cart;
use Cache;
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
	    if(Auth::user())
	    	$this->addCartDB($item);
		return $cart;
	}
	public function getcartCount(){
		$cart = Cart::content();
		$count = count($cart);
		if($count < 0)
			$count = 0;
		return $count;
	}
	public function showCart(){
		$cart = Cart::content();
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
		$count = $this->getcartCount();
		$arr = ['id'=>$id,'count'=>$count,'total' => Cart::total()];
		return $arr;
	}
	public function getCart(){
		$cart = Cart::content();
		return $cart;
	}
	public function updateCart($id,$qty){
		$rowId = Cart::search(array('id' => $id));
		Cart::update($rowId[0], $qty);
		$returnval = Cart::get($rowId[0]);
		if(!$returnval)
			$returnval=['id'=>'deleted','productid'=>$id];
		$returnval = [$returnval,'total' => Cart::total()];
		if(Auth::user())
			$this->updateCartDB($id,$qty);
		return $returnval;
	}
	public function updateCartDB($id,$qty){
		$user_id = Auth::user()->id;
		$cart_id = User_Cart::where('id',$user_id)->value('id');
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
		//dd($item);
		Cart_Details::create($item);
	}
	public function cartToDB(){
		$user_id = 2;
		$status = null;
		$cart_id =User_Cart::where('user_id',$user_id)->where('status','true')->value('id');
		if($cart_id){
			dd('calling1');
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
	            $item['product_name'] = $product->product_name;
	            $item['quantity'] = $product->qty;
	            $item['image'] = $product->image;
	            $item['price'] = $product->price;
	            $itemres = Cart_Details::create($item)  ;
	        }
		}
        dd('calling2');
    }
}
