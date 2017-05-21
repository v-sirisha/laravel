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
				<thead>
					<th>PF Name</th>
					<th>Site Name</th>
					<th>Tag Id</th>
					<th>Tag Name</th>
					<th>Io publisher Name</th>
					<th>Product Name</th>
					<th>Actual ad unit</th>
					<th>Edit</th>
				</thead>
				<tbody>
					@foreach($data as $key=>$val)
						<tr>
							<td>{{$val->platform_name}}</td>
							<td>{{$val->site_name}}</td>
							<td>{{$val->tag_id}}</td>
							<td>{{$val->tag_name}}</td>
							<td>{{$val->io_publisher_name}}</td>
							<td>{{$val->product_name}}</td>
							<td>{{$val->actual_ad_unit}}</td>
							<td><span class="glyphicon glyphicon-edit"></span></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@elseif($type == 'io_product')
			<table class="table table-bordered" id="yr_table">
				<thead>
					<th>Placement Tag</th>
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
					@foreach($data as $key=>$val)
						<tr>
							<td>{{$val->final_placement_tag}}</td>
							<td>{{$val->deal_type}}</td>
	                        <td>{{$val->date_of_io_creation}}</td>
	                        <td>{{$val->publisher_manager}}</td>
	                        <td>{{$val->ym_manager}}</td>
	                        <td>{{$val->publisher_url}}</td>
	                        <td>{{$val->publisher_category}}</td>
	                        <td>{{$val->country_origin}}</td>
	                        <td>{{$val->language}}</td>
	                        <td>{{$val->business_name}}</td>
	                        <td>{{$val->billing_currency}}</td>
							<td><span class="glyphicon glyphicon-edit"></span></td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@elseif($type == 'country')
			<table class="table table-bordered" id="country">
				<thead>
					<tr>
						<th>Country</th>
						<th>Analytic Country Group</th>
						<th>Deal Country Group</th>
					</tr>					
				</thead>
				<tbody>					
					@foreach($data as $key=>$val)
						<tr>
							<td>{{$val->country_name}}</td>
							<td>{{$val->analytics_country_group}}</td>
							<td>{{$val->deal_country_group}}</td>
						</tr>
					@endforeach
					</tr>
				</tbody>
			</table>
		@elseif($type == 'device')
			<table class="table table-bordered" id="country">
				<thead>
					<tr>
						<th>Device Name</th>
						<th>Device Group</th>
					</tr>					
				</thead>
				<tbody>					
					@foreach($data as $key=>$val)
						<tr>
							<td>{{$val->device_name}}</td>
							<td>{{$val->device_group}}</td>
						</tr>
					@endforeach
					</tr>
				</tbody>
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
		});
	</script>
@stop
</body>
<footer>
</footer>
</html>