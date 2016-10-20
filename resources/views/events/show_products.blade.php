@extends('layouts.app')
@section('content')
<div class="col-md-8 col-md-offset-2">
	<h4 class="text-center">Products Details</h4>
	<table class="table table-spriped">
		@foreach($productsinfo as $pro)
		{{$pro->product_id}}
		<tr>
			<td>{{$pro->id}}</td>
			<td>{{$pro->product_id}}</td>
			<td>{{$pro->name}}</td>
			<td>{{$pro->product_description}}</td>
			<td>{{$pro->quantity}}</td>
		</tr>
		@endforeach
	</table>
</div>
@stop