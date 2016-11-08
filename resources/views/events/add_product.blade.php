@extends('layouts.app')
@section('content')
{!! Form::open(array('url' => 'create_product','files'=> true,)) !!}
	{{csrf_field()}}
	<div class='col-md-4 col-md-offset-4'>
		<div class="form-group">{{Form::text('name',null,['class'=>'form-control','placeholder'=>'product name'])}}</div>
		<div class="form-group">{{Form::text('product_description',null,['class'=>'form-control','placeholder'=>'description'])}}</div>
		<div class="form-group">{{Form::text('price',null,['class'=>'form-control','placeholder'=>'price'])}}</div>
		<div class="form-group">{{Form::number('quantity',null,['class'=>'form-control','placeholder'=>'Quantity'])}}</div>
		<div class="form-group"><input type="file" name="image" class="form-control"></div>
		<div class='form-group text-right'><button type='submit' class='btn btn-primary'>Add Product</button></div>
	</div>
{!! Form::close() !!}
@stop