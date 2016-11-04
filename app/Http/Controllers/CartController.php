<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Cart;
use Cache;
use Carbon\Carbon;
use App\Models\Create_Product;

class CartController extends Controller
{
    public function cart($id) {
        $product_id =$id;
        $product = Create_Product::where('productid',$product_id)->get();
        Cart::add(array('id' => $product_id, 'name' => $product[0]->name, 'qty' => 1, 'price' => $product[0]->price,'image'=>$product[0]->image));
	    $cart = $this->getcartCount();
	    $cartItems = $this->getCart();
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
		return $returnval;
	}
}
