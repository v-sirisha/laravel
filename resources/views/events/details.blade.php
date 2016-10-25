@extends('layouts.app')
@section('content')
<div class="row" id="content">
@include('sidenav')
<div class="col-md-9">
    <div class="row" id="productMain">
        <div class="col-sm-6">
            <div id="mainImage">
                <img src="{{asset('images/productimages'.'/'.$record[0]->image)}}" alt="" class="img-responsive">
            </div>

            <div class="ribbon sale">
                <div class="theribbon">SALE</div>
                <div class="ribbon-background"></div>
            </div>
            <!-- /.ribbon -->

            <div class="ribbon new">
                <div class="theribbon">NEW</div>
                <div class="ribbon-background"></div>
            </div>
            <!-- /.ribbon -->

        </div>
        <div class="col-sm-6">
            <div class="box">
                <h1 class="text-center">{{$record[0]->name}}</h1>
                <p class="goToDescription"><a href="#details" class="scroll-to">Scroll to product details, material & care and sizing</a>
                </p>
                <p class="price">{{$record[0]->price}}</p>

                <p class="text-center buttons">
                    <a href="basket.html" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add to cart</a> 
                    <a href="basket.html" class="btn btn-default"><i class="fa fa-heart"></i> Add to wishlist</a>
                </p>


            </div>

            <div class="row" id="thumbs">
                <div class="col-xs-4">
                    <a href="img/detailbig1.jpg" class="thumb">
                        <img src="img/detailsquare.jpg" alt="" class="img-responsive">
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="img/detailbig2.jpg" class="thumb">
                        <img src="img/detailsquare2.jpg" alt="" class="img-responsive">
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="img/detailbig3.jpg" class="thumb">
                        <img src="img/detailsquare3.jpg" alt="" class="img-responsive">
                    </a>
                </div>
            </div>
        </div>

    </div>


    <div class="box" id="details">
        <p>
            <h4>Product details</h4>
            <p>{{$record[0]->product_description}}</p>
            <p>Available Quantity : {{$record[0]->quantity}}</p>
            <p>{{$record[0]->price}}</p>
            <p class="text-center buttons">
                <a href="{{url('/checkout')}}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Buy Now</a> 
            </p>

            <hr>
            <div class="social">
                <h4>Show it to your friends</h4>
                <p>
                    <a href="#" class="external facebook" data-animate-hover="pulse"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="external gplus" data-animate-hover="pulse"><i class="fa fa-google-plus"></i></a>
                    <a href="#" class="external twitter" data-animate-hover="pulse"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="email" data-animate-hover="pulse"><i class="fa fa-envelope"></i></a>
                </p>
            </div>
    </div>
</div>
</div>
@stop