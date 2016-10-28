@extends('layouts.app')
@section('content')
	<div id="all">
        <div id="content">
            <div class="container">
            	 <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a>
                        </li>
                        <li>Checkout - Address</li>
                    </ul>
                </div>
                
                <div class="col-md-9" id="checkout">

                    <div class="box">
                        <h1>Checkout</h1>
                        <ul class="nav nav-pills nav-justified">
                            <li class="active current"><a href="#addressSec" data-href="#addressSec" data-toggle="tab"><i class="fa fa-map-marker"></i><br>Address</a>
                            </li>
                            <li class="disabled"><a data-href="#deliverySection" data-toggle="tab"><i class="fa fa-truck"></i><br>Delivery Method</a>
                            </li>
                            <li class="disabled"><a data-href="#paymentSection" data-toggle="tab"><i class="fa fa-money"></i><br>Payment Method</a>
                            </li>
                            <li class="disabled"><a data-href="#orderReviewSec" data-toggle="tab"><i class="fa fa-eye"></i><br>Order Review</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="addressSec" class="tab-pane active">
                                <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="firstname">Firstname</label>
                                                <input type="text" class="form-control" id="firstname">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="lastname">Lastname</label>
                                                <input type="text" class="form-control" id="lastname">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="company">Company</label>
                                                <input type="text" class="form-control" id="company">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="street">Street</label>
                                                <input type="text" class="form-control" id="street">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="city">Company</label>
                                                <input type="text" class="form-control" id="city">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="zip">ZIP</label>
                                                <input type="text" class="form-control" id="zip">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <select class="form-control" id="state">
                                                    <option value="blr">blr</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <select class="form-control" id="country">
                                                    <option value="india">india</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="phone">Telephone</label>
                                                <input type="text" class="form-control" id="phone">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.row -->
                                </div>

                                <div class="box-footer">
                                    <div class="pull-left">
                                        <a href="{{url('cartview')}}" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to basket</a>
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary next">Continue to Delivery Method<i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>



                            <!-- delivery method  start -->
                            <div id="deliverySection" class="tab-pane">
                                <div class="content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box shipping-method">

                                                <h4>USPS Next Day</h4>

                                                <p>Get it right on next day - fastest option possible.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="delivery" value="delivery1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box shipping-method">

                                                <h4>USPS Next Day</h4>

                                                <p>Get it right on next day - fastest option possible.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="delivery" value="delivery2">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="box shipping-method">

                                                <h4>USPS Next Day</h4>

                                                <p>Get it right on next day - fastest option possible.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="delivery" value="delivery3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.content -->

                                <div class="box-footer">
                                    <div class="pull-left">
                                        <a href="basket.html" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to Addresses</a>
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary">Continue to Payment Method<i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- delivery method end -->

                            <!-- payment method  start -->
                            <div id="paymentSection" class="tab-pane">
                                <div class="content ">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box payment-method">

                                                <h4>Paypal</h4>

                                                <p>We like it all.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="payment" value="payment1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="box payment-method">

                                                <h4>Payment gateway</h4>

                                                <p>VISA and Mastercard only.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="payment" value="payment2">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="box payment-method">

                                                <h4>Cash on delivery</h4>

                                                <p>You pay when you get it.</p>

                                                <div class="box-footer text-center">

                                                    <input type="radio" name="payment" value="payment3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->

                                </div>
                                <!-- /.content -->

                                <div class="box-footer">
                                    <div class="pull-left">
                                        <a href="basket.html" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to Shipping method</a>
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary">Continue to Order review<i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- payment method end -->

                            <!-- order review start  -->
                            <div id="orderReviewSec" class="tab-pane">
                                <div class="content ">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit price</th>
                                                    <th>Discount</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            <img src="img/detailsquare.jpg" alt="White Blouse Armani">
                                                        </a>
                                                    </td>
                                                    <td><a href="#">White Blouse Armani</a>
                                                    </td>
                                                    <td>2</td>
                                                    <td>$123.00</td>
                                                    <td>$0.00</td>
                                                    <td>$246.00</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <a href="#">
                                                            <img src="img/basketsquare.jpg" alt="Black Blouse Armani">
                                                        </a>
                                                    </td>
                                                    <td><a href="#">Black Blouse Armani</a>
                                                    </td>
                                                    <td>1</td>
                                                    <td>$200.00</td>
                                                    <td>$0.00</td>
                                                    <td>$200.00</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5">Total</th>
                                                    <th>$446.00</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.content -->

                                <div class="box-footer">
                                    <div class="pull-left">
                                        <a href="checkout3.html" class="btn btn-default"><i class="fa fa-chevron-left"></i>Back to Payment method</a>
                                    </div>
                                    <div class="pull-right">
                                        <button type="submit" class="btn btn-primary">Place an order<i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- order review end -->
                        </div>
                    </div>
                    <!-- /.box -->


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
                                        <th>$446.00</th>
                                    </tr>
                                    <tr>
                                        <td>Shipping and handling</td>
                                        <th>$10.00</th>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <th>$0.00</th>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <th>$456.00</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <!-- /.col-md-3 -->

            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
            </div>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript">
        $(document).on('click','.next',function(event){
            var textvalid = true;
            var selectvalid = true;
            var radiovalid = true;
            var tab = $('.tab-pane.active');
            $('input', tab).each(function(){
                if(!$(this).val()){
                    textvalid = false;
                } 
            });
            $('select', tab).each(function(){
                if(!$(this).val()){
                    selectvalid = false;
                } 
            });
            if($('.tab-pane.active').find(':radio').length > 0){
                if ($('.tab-pane.active:has(:radio:checked)').length>0) {
                   console.log('calling radio : if')
                }
                else{
                    radiovalid = false;
                }
            }
            var valid = textvalid && selectvalid && radiovalid;
            if(valid){
                var href = $('.nav-pills .active').next().find('a').attr('data-href');
                $('.nav-pills .active').next().find('a').attr('href',href);
                $('.nav-pills .active').next().find('a').trigger('click');
                $('.tab-pane.active .btn-primary').addClass('next');
            }
            else{
                console.log('calling else');
            }
        });
    </script>
@stop