@extends('reporting.master')
@section('action_title')
Update Io Product Details
@stop
@section('header')
	@include('reporting.header')
@stop
@section('content')
	<div class="container-fluid table_wrapper">		
		@if($type == "PR")
			<table class="table table-bordered" id="pr_table">
				@if(count($data) > 0)
					<thead>
						<th>PF Name</th>
						<th>Site Name</th>
						<th>Tag Id</th>
						<th>Tag Name</th>
						<th>Size</th>
						<th>Io publisher Name</th>
						<th>Product Name</th>
						<th>Io Size</th>
						<th>Edit</th>
					</thead>
					<tbody>
						@foreach($data as $key=>$val)
							<tr id='pr{{$key}}'>
								<form id="form{{$key}}">
									<input type="hidden" name="type" value="PR">
									<td id='f{{$key}}'><input type="text" name="platform_name" value="{{$val->platform_name}}" disabled></td>
									<td><input type='text' name="site_name" value="{{$val->site_name}}" disabled></td>
									<td><input type='text' name="tag_id" value="{{$val->tag_id}}" disabled></td>
									<td><input type="text" name="tag_name" value="{{$val->tag_name}}" disabled></td>
									<td><input type="text" name='size' value="{{$val->size}}" disabled></td>
									<td><input type="text" name="io_publisher_name" value="{{$val->io_publisher_name}}" disabled></td>
									<td><input type="text" name="product_name" value="{{$val->product_name}}" disabled></td>
									<td><input type='text' name="io_size" value="{{$val->io_size}}" disabled></td>
									<td  class="edit cursorCls" data-id = "{{$key}}"><span class="glyphicon glyphicon-edit" data-id="{{$key}}"></span></td>
								</form>
							</tr>
						@endforeach
					</tbody>
				@else
					<tbody><th class="text-center">YOUR PR DATA IS UP TO DATE</th></tbody>
				@endif
			</table>
		@elseif($type == 'io_product')
			<table class="table table-bordered" id="yr_table">
				<thead>
					<th>Placement Tag</th>
					<th>Ad Unit Size</th>
					<th>Deal Type</th>
					<th>Date of Io creation</th>
					<th>Publisher Manager</th>
					<th>YM Manager</th>
					<th>Publisher url</th>
					<th>Publisher Category</th>
					<th>Country Origin</th>
					<th>Language</th>
					<th>Business Name</th>
					<th>Billing Currency</th>
					<th>Edit</th>
				</thead>
				<tbody>
					@if(count($data['io_null_arr']) > 0)
						@foreach($data['io_null_arr'] as $key=>$val)
							<tr id="pr{{$key}}">
								<form id="form{{$key}}">
									<input type="hidden" name="type" value="io_product">
									<td><input type="text" name="final_placement_tag" value="{{$val->final_placement_name}}" readonly></td>
									<td><input type="text" name="ad_unit_size" value="{{$val->ad_unit_size}}" readonly></td>
									<td><input type="text" name="deal_type" value="{{$val->deal_type}}" disabled></td>
			                        <td><input type="text" name="date_of_io_creation" value="{{$val->date_of_io_creation}}" disabled></td>
			                        <td><input type="text" name="publisher_manager" value="{{$val->publisher_manager}}" disabled></td>
			                        <td><input type="text" name="ym_manager" value="{{$val->ym_manager}}" disabled></td>
			                        <td><input type="text" name="publisher_url" value="{{$val->publisher_url}}" disabled></td>
			                        <td><input type="text" name="publisher_category" value="{{$val->publisher_category}}" disabled></td>
			                        <td><input type="text" name="country_origin" value="{{$val->country_origin}}" disabled></td>
			                        <td><input type="text" name="language" value="{{$val->language}}" disabled></td>
			                        <td><input type="text" name="business_name" value="{{$val->business_name}}" disabled></td>
			                        <td><input type="text" name="billing_currency" value="{{$val->billing_currency}}" disabled></td>
									<td class="edit cursorCls" data-id = "{{$key}}"><span class="glyphicon glyphicon-edit"></span>Edit</td>
								</form>
							</tr>
						@endforeach
					@else
						@if(count($data['io_miss']) > 0)
							@foreach($data['io_miss'] as $key=>$val)
								<tr id="pr{{$val}}">
									<form id="form{{$val}}">
										<input type="hidden" name="type" value="io_product">
										<td><input type="text" name="final_placement_name" value="{{$val}}" readonly></td>
										<td><input type="text" name="ad_unit_size" value="" readonly></td>
										<td><input type="text" name="deal_type" value="" disabled></td>
				                        <td><input type="text" name="date_of_io_creation" value="" disabled></td>
				                        <td><input type="text" name="publisher_manager" value="" disabled></td>
				                        <td><input type="text" name="ym_manager" value="" disabled></td>
				                        <td><input type="text" name="publisher_url" value="" disabled></td>
				                        <td><input type="text" name="publisher_category" value="" disabled></td>
				                        <td><input type="text" name="country_origin" value="" disabled></td>
				                        <td><input type="text" name="language" value="" disabled></td>
				                        <td><input type="text" name="business_name" value="" disabled></td>
				                        <td><input type="text" name="billing_currency" value="" disabled></td>
										<td class="edit cursorCls" data-id = "{{$val}}"><span class="glyphicon glyphicon-edit"></span>Edit</td>
									</form>
								</tr>
							@endforeach
						@else
							<th class="text-center">YOUR PUBLISHER DATA IS UP TO DATE</th>
						@endif
					@endif
				</tbody>
			</table>
		@elseif($type == 'country')
			<table class="table table-bordered" id="country">
				@if(count($data) > 0)
					<thead>
						<tr>
							<th>Country</th>
							<th>Analytic Country Group</th>
							<th>Deal Country Group</th>
						</tr>					
					</thead>
					<tbody>					
						@foreach($data as $key=>$val)
							<tr id="pr{{$key}}">
								<form id="form{{$key}}">
									<input type="hidden" name="type" value="country">
									<td><input type="text" name="country_name" value="{{$val->country_name}}" readonly></td>
									<td><input type="text" name="analytics_country_group" value="{{$val->analytics_country_group}}" disabled></td>
									<td><input type="text" name="deal_country_group" value="{{$val->deal_country_group}}" disabled></td>
									<td class="edit cursorCls" data-id = "{{$key}}"><span class="glyphicon glyphicon-edit"></span>Edit</td>
								</form>
							</tr>
						@endforeach
						</tr>
					</tbody>
				@else
					<tbody><th class="text-center">YOUR COUNTRY TABLE IS UP TO DATE</th></tbody>
				@endif
			</table>
		@elseif($type == 'device')
			<table class="table table-bordered" id="device">
				@if(count($data) > 0)
					<thead>
						<tr>
							<th>Device Name</th>
							<th>Device Group</th>
						</tr>					
					</thead>
					<tbody>					
						@foreach($data as $key=>$val)
							<tr id="pr{{$key}}">
								<form id="form{{$key}}">
									<input type="hidden" name="type" value="device">
									<td><input type="text" name="device_name" value="{{$val->device_name}}" readonly></td>
									<td><input type="text" name="device_group" value="{{$val->device_group}}" disabled></td>
									<td class="edit cursorCls" data-id = "{{$key}}"><span class="glyphicon glyphicon-edit"></span>Edit</td>
								</form>
							</tr>
						@endforeach
						</tr>
					</tbody>
				@else
					<tbody><th class="text-center">YOUR DEVICE TABLE IS UP TO DATE</th></tbody>
				@endif
			</table>
		@elseif($type == 'deal_rate')
			<table class="table table-bordered" id="device">
				@if(count($data) > 0)
					<thead>
						<tr>
							<th>Parent Placement Name</th>
							<th>Device Group</th>
							<th>Deal Country Group</th>
							<th>Deal Rate</th>
						</tr>					
					</thead>
					<tbody>					
						@foreach($data as $key=>$val)
							<tr id="pr{{$key}}">
								<form id="form{{$key}}">
									<input type="hidden" name="type" value="deal_rate">
									<td><input type="text" name="parent_placement_name" value="{{$val->parent_placement_name}}" readonly></td>
									<td><input type="text" name="device_group" value="{{$val->device_group}}" readonly></td>
									<td><input type="text" name="deal_country_group" value="{{$val->deal_country_group}}" readonly></td>
									<td><input type="text" name="deal_rate" value="{{$val->deal_rate}}" disabled></td>
									<td class="edit cursorCls" data-id = "{{$key}}"><span class="glyphicon glyphicon-edit"></span>Edit</td>
								</form>
							</tr>
						@endforeach
						</tr>
					</tbody>
				@else
					<tbody><th class="text-center">YOUR DEVICE TABLE IS UP TO DATE</th></tbody>
				@endif
			</table>
		@endif
	</div>
@stop
@section('footer')
	@include('reporting.footer')
@stop
@section('script')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#pr_table').dataTable();
			$('#pr_table_length').after('<span class="table_label">Update PR Data</span>');
			$('#yr_table').dataTable();
			$('#yr_table_length').after('<span class="table_label">Update Io Product Details</span>');
			$('#country').dataTable();
			$('#country_table_length').after('<span class="table_label">Update Country Details</span>');
			$('#device').dataTable();
			$('#device_table_length').after('<span class="table_label">Update Country Details</span>');
		});
		$(document).on('click','.edit',function(){
			$(this).removeClass('edit');
			var id = $(this).attr('data-id');
			$('#pr'+id+' input').removeAttr('disabled');
			$(this).text('update');
			$(this).addClass('update');
		});
		$(document).on('click','.update',function(){
			$(this).removeClass('update');
			var id = $(this).attr('data-id');
			$(this).html('<span class="glyphicon glyphicon-edit"></span>');
			$(this).addClass('edit');
			console.log("url : {{url('/update-row')}}");
			console.log('form data : '+$('#form'+id).serialize())
			$.ajax({
				url:"{{url('/update-row')}}",
				data:$('#form'+id).serialize(),
				success:function(data){
					$('#pr'+id+' input').attr('disabled','disabled');
					console.log('data : '+JSON.stringify(data))
				}
			})
		});
	</script>
@stop
</body>
<footer>
</footer>
</html>