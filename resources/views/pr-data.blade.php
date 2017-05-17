<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" />
	<style type="text/css">
		.container-fluid{margin:30px;overflow: auto;font-size: 12px;}
	</style>
</head>
<body>
	<div class="container-fluid">
		<h4 class="text-center">PR Details</h4>
		<table class="table table-bordered" id="pr_table">
			<thead>
				<th>PF Name</th>
				<th>Date</th>
				<th>Site Name</th>
				<th>Tag Id</th>
				<th>Tag Name</th>
				<th>Ad Unit</th>
				<th>Device</th>
				<th>Country</th>
				<th>Buyer</th>
				<th>Ad Server Impressions</th>
				<th>SSP Impressions</th>
				<th>Filled Impressions</th>
				<th>Gross Revenue</th>
				<th>PP Name</th>
				<th>Product Name</th>
				<th>Placement Tag</th>
				<th>Actual ad unit</th>
			</thead>
			<tbody>
				@foreach($data as $key=>$val)
					<tr>
						<td>{{$val->platform_name}}</td>
						<td>{{$val->date}}</td>
						<td>{{$val->site_name}}</td>
						<td>{{$val->tag_id}}</td>
						<td>{{$val->tag_name}}</td>
						<td>{{$val->ad_unit}}</td>
						<td>{{$val->device}}</td>
						<td>{{$val->country}}</td>
						<td>{{$val->buyer}}</td>
						<td>{{$val->adserver_impressions}}</td>
						<td>{{$val->ssp_impressions}}</td>
						<td>{{$val->filled_impressions}}</td>
						<td>{{$val->gross_revenue}}</td>
						<td>{{$val->pp_name}}</td>
						<td>{{$val->product_name}}</td>
						<td>{{$val->final_placement_name}}</td>
						<td>{{$val->actual_ad_unit}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<div class="col-md-12 text-center">{{$data->render()}}</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#pr_table').dataTable();
		});
	</script>
</body>
<footer>
</footer>
</html>