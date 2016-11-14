@extends('layouts.app')
@section('content')
<div id="all">
	 <div id="content">
        <div class="container">

            <div class="col-md-12">

                <ul class="breadcrumb">
                    <li><a href="#">Home</a>
                    </li>
                    <li>My orders</li>
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

            <div class="col-md-9" id="customer-orders">
                <div class="box">
                    <h1>My orders</h1>

                    <p class="lead">Your orders on one place.</p>
                    <p class="text-muted">If you have any questions, please feel free to <a href="contact.html">contact us</a>, our customer service center is working for you 24/7.</p>

                    <hr>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($totalorders as $order)
                                <tr>
                                    <th>{{$order->id}}</th>
                                    <td>{{date('d-m-Y', strtotime($order->order_date))}}</td>
                                    <td>&#8377;  {{$order->total}}</td>
                                    <td><span class="label label-info">{{$order->order_status}}</span>
                                    </td>
                                    <td><a href="{{url('order-details').'/'.$order->id}}" class="btn btn-primary btn-sm">View</a>
                                    </td>
                                </tr>
                               @endforeach 
                        </table>
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