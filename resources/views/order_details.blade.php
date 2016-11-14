@extends('layouts.app')
@section('content')
<div id="all">
	<div id="content">
        <div class="container">

            <div class="col-md-12">

                <ul class="breadcrumb">
                    <li><a href="index.html">Home</a>
                    </li>
                    <li><a href="#">My orders</a>
                    </li>
                    <li>Order # 1735</li>
                </ul>

            </div>

            <div class="col-md-3">
                <!-- *** CUSTOMER MENU ***
_________________________________________________________ -->
                <div class="panel panel-default sidebar-menu">

                    <div class="panel-heading">
                        <h3 class="panel-title">Customer section</h3>
                    </div>

                    <div class="panel-body">

                        <ul class="nav nav-pills nav-stacked">
                            <li class="active">
                                <a href="customer-orders.html"><i class="fa fa-list"></i> My orders</a>
                            </li>
                            <li>
                                <a href="customer-wishlist.html"><i class="fa fa-heart"></i> My wishlist</a>
                            </li>
                            <li>
                                <a href="customer-account.html"><i class="fa fa-user"></i> My account</a>
                            </li>
                            <li>
                                <a href="index.html"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /.col-md-3 -->

                <!-- *** CUSTOMER MENU END *** -->
            </div>

            <div class="col-md-9" id="customer-order">
                <div class="box">
                    <h1>Order #1735</h1>
                    <p class="lead">Order # {{$order[0]->id}} was placed on <strong>{{date('d|m|Y',strtotime($order[0]->order_date))}}</strong> and is currently <strong>Being {{$order[0]->order_status}}</strong>.</p>
                    <p class="text-muted">If you have any questions, please feel free to <a href="contact.html">contact us</a>, our customer service center is working for you 24/7.</p>

                    <hr>

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
                                @foreach($orderdetails as $item)
                                <tr>
                                    <td>
                                        <a href="#">
                                            <img src="{{asset('images/productimages').'/'.$item->image}}" alt="White Blouse Armani">
                                        </a>
                                    </td>
                                    <td><a href="#">{{$item->name}}</a>
                                    </td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->total_price * $item->quantity}}</td>
                                    <td>&#8377; 0.00</td>
                                    <td>&#8377; {{$item->total_price}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Order subtotal</th>
                                    <th>&#8377; {{$subTotal}}</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Shipping and handling</th>
                                    <th>&#8377; 0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Tax</th>
                                    <th>&#8377; 0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-right">Total</th>
                                    <th>&#8377; {{$subTotal}}</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <!-- /.table-responsive -->

                    <div class="row addresses">
                        <div class="col-md-6">
                            <h2>Invoice address</h2>
                            <p>{{$guest[0]->name}}
                                <br>{{$guest[0]->street}}
                                <br>{{$guest[0]->city}} - {{$guest[0]->pincode}}
                                <br>{{$guest[0]->state}} - {{$guest[0]->country}}</p>
                        </div>
                        <div class="col-md-6">
                            <h2>Shipping address</h2>
                            <p>{{$guest[0]->name}}
                                <br>{{$guest[0]->street}}
                                <br>{{$guest[0]->city}} - {{$guest[0]->pincode}}
                                <br>{{$guest[0]->state}} - {{$guest[0]->country}}</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- /.container -->
    </div>
    <!-- /#content -->
</div>
@stop
@section('script')
@stop