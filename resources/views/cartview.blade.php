@extends('layouts.app')
@section('content')
<div id="all">

        <div id="content">
            <div class="container">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a>
                        </li>
                        <li>Shopping cart</li>
                    </ul>
                </div>

                <div class="col-md-9" id="basket">

                    <div class="box">

                        <form method="post" action="checkout1.html">

                            <h1>Shopping cart</h1>
                            <p class="text-muted">You currently have 3 item(s) in your cart.</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Product</th>
                                            <th>Quantity</th>
                                            <th>Unit price</th>
                                            <th>Discount</th>
                                            <th colspan="2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($cart))
                                        	@foreach($cart as $cartDetail)
    	                                        <tr id="{{$cartDetail->id}}">
    	                                            <td>
    	                                                <a href="#">
    	                                                    <img src="{{asset('images/productimages').'/'.$cartDetail->image}}" alt="White Blouse Armani">
    	                                                </a>
    	                                            </td>
    	                                            <td><a href="#">{{$cartDetail->name}}</a>
    	                                            </td>
    	                                            <td>
    	                                                <input type="number" value="{{$cartDetail->qty}}" class="form-control qty">
    	                                            </td>
    	                                            <td>&#8377 {{$cartDetail->price}}</td>
    	                                            <td>&#8377 0.00</td>
    	                                            <td class="subtotal{{$cartDetail->id}}">&#8377 {{$cartDetail->subtotal}}</td>
    	                                            <td><a data-href="{{url('cart/remove').'/'.$cartDetail->id}}" class="removeItem"><i class="fa fa-trash-o"></i></a>
    	                                            </td>
    	                                        </tr>
                                            @endforeach
                                        @else
                                            <tr><td>Your Cart is Empty</td></tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">Total</th>
                                            <th colspan="2" class="sub_total">&#8377 {{$total}}</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->

                            <div class="box-footer">
                                <div class="pull-left">
                                    <a href="{{url('show/all')}}" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continue shopping</a>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-default"><i class="fa fa-refresh"></i> Update basket</button>
                                    <a type="submit" class="btn btn-primary" href='{{url("/checkout_page")}}'>Proceed to checkout <i class="fa fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div>
                    <!-- /.box -->


                    <div class="row same-height-row">
                        <div class="col-md-3 col-sm-6">
                            <div class="box same-height">
                                <h3>You may also like these products</h3>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="detail.html">
                                                <img src="img/product2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="detail.html">
                                                <img src="img/product2_2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail.html" class="invisible">
                                    <img src="img/product2.jpg" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product -->
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="detail.html">
                                                <img src="img/product1.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="detail.html">
                                                <img src="img/product1_2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail.html" class="invisible">
                                    <img src="img/product1.jpg" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product -->
                        </div>


                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="detail.html">
                                                <img src="img/product3.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="detail.html">
                                                <img src="img/product3_2.jpg" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="detail.html" class="invisible">
                                    <img src="img/product3.jpg" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>

                                </div>
                            </div>
                            <!-- /.product -->
                        </div>

                    </div>


                </div>
                <!-- /.col-md-9 -->

                <div class="col-md-3">
                    <div class="box" id="order-summary">
                        <div class="box-header">
                            <h3>Order summary</h3>
                        </div>
                        <p class="text-muted">Shipping and additional costs are calculated based on the values you have entered.</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Order subtotal</td>
                                        <th class="sub_total text-right" id="order_sub">&#8377 {{$total}}</th>
                                    </tr>
                                    <tr>
                                        <td>Shipping and handling</td>
                                        <th class="text-right">&#8377 0.00</th>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <th class="text-right">&#8377 0.00</th>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <th class='text-right' id="order_total">&#8377 {{$total}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>


                    <div class="box">
                        <div class="box-header">
                            <h4>Coupon code</h4>
                        </div>
                        <p class="text-muted">If you have a coupon code, please enter it in the box below.</p>
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <span class="input-group-btn">
					            <button class="btn btn-primary" type="button"><i class="fa fa-gift"></i></button>
				                </span>
                            </div>
                            <!-- /input-group -->
                        </form>
                    </div>
                </div>
                <!-- /.col-md-3 -->

            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
  </div>
   <!-- /#all -->

@stop
@section('script')
<script type="text/javascript">
	$(document).on('click','.removeItem',function(){
		var url = $(this).attr('data-href');
		$.ajax({
			type:'get',
			url:url,
			success:function(res){
				if(res != false){
                    updatePage(res,'delete');
				}
			}
		});
	});
    $(document).on('focusout','.qty',function(){
        var qty = parseInt($(this).val());
        var url = '{{url("cart/update")}}';
        var id = $(this).closest('tr').attr('id');
        var response;
        $.ajax({
            type:'get',
            url: url + '/' +id+'/'+qty,
            success:function(res){
                updatePage(res,'update');
            }
        });
    });
    function updatePage(res,fn){
        if(fn == 'update'){
            if(res[0].id == 'deleted'){
                $('tr#'+res[0].productid).remove();
            }
            else{
                $('.subtotal'+res[0].id).html('&#8377; '+res[0].subtotal);
            }
        }
        else if(fn == 'delete'){
            $('tr#'+res.id).remove();
            $('.cartCount').text(res.count+' items in cart');
        }
        $('#order_total').html('&#8377; '+res.total);
        $('.sub_total').html('&#8377; '+res.total);

    }
</script>
@stop