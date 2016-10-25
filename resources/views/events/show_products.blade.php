@extends('layouts.app')
@section('css')

@stop
@section('content')
<div id="all">

    <div id="content">
        <div class="container">

            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="#">Home</a>
                    </li>
                    <li>Ladies</li>
                </ul>
            </div>

            @include('sidenav')

            <div class="col-md-9">
                <div class="box">
                    <h1>Ladies</h1>
                    <p>In our Ladies department we offer wide selection of the best products we have found and carefully selected worldwide.</p>
                </div>

                <div class="box info-bar">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 products-showing">
                            Showing <strong>12</strong> of <strong>25</strong> products
                        </div>

                        <div class="col-sm-12 col-md-8  products-number-sort">
                            <div class="row">
                                <form class="form-inline">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="products-number">
                                            <strong>Show</strong>  <a href="#" class="btn btn-default btn-sm btn-primary">12</a>  <a href="#" class="btn btn-default btn-sm">24</a>  <a href="#" class="btn btn-default btn-sm">All</a> products
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="products-sort-by">
                                            <strong>Sort by</strong>
                                            <select name="sort-by" class="form-control">
                                                <option>Price</option>
                                                <option>Name</option>
                                                <option>Sales first</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row products">

                    
                </div>

                <div class="pages">

                    <p class="loadMore">
                        <a href="#" class="btn btn-primary btn-lg"><i class="fa fa-chevron-down"></i> Load more</a>
                    </p>

                    <!-- <ul class="pagination">
                        <li><a href="#">&laquo;</a>
                        </li>
                        <li class="active"><a href="#">1</a>
                        </li>
                        <li><a href="#">2</a>
                        </li>
                        <li><a href="#">3</a>
                        </li>
                        <li><a href="#">4</a>
                        </li>
                        <li><a href="#">5</a>
                        </li>
                        <li><a href="#">&raquo;</a>
                        </li>
                    </ul> -->
                </div>


            </div>
            <!-- /.col-md-9 -->
        </div>
        <!-- /.container -->
    </div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		getProducts();
	});
	function getProducts(){
		$.ajax({
			type:'GET',
			url:'{{url("/getprod")}}',
			success:function(res){
				if(res != null){
					displayRecords(res);
				}	
			},
			error:function(res){
				console.log('error : '+res);
			}
		});
	}
	function displayRecords(data){
		$.each(data, function (i,obj){
			var img = "{{asset('images/productimages')}}"+"/"+obj['image'];
			var prod_link = "{{url('/detail')}}"+"/"+obj['productid'];
			var html  ='<div class="col-md-4 col-sm-6">';
				html +='<div class="product">';
				html +=' <div class="flip-container">';
				html +='<div class="flipper">';
				html +=' <div class="front">';
				html +='<a href="'+prod_link+'">';
				html +="<img src='"+img+"' class='img-responsive'>";
				html +='</a>';
				html +='</div>';
				html +='<div class="back">';
				html +='<a href="'+prod_link+'">';
				html +="<img src='"+img+"' class='img-responsive'>";
				html +='</a>';
				html +='</div>';
				html +='</div>';
				html +='</div>';
				html +='<a href="'+prod_link+'" class="invisible">';
				html +="<img src='"+img+"' class='img-responsive'>";
				html +='</a>';

				html +='<div class="text">';
				html +=' <h3><a href="'+prod_link+'">'+obj["name"]+'</a></h3>';
				html +='<p class="price">$143.00</p>';
				html +="<p>"+obj['product_description']+"</p>";
				html +="<p>"+obj['quantity']+"</p>";
				html +='<p class="buttons">';
				html +='<a href="'+prod_link+'" class="btn btn-default">View detail</a>';
				html +='<a href="'+prod_link+'" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Add to cart</a>';
				html +='</p>';

				html +="</div></div></div>";
			$('.products').append(html);
		});
	}
</script>
@stop