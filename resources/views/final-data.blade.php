<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" />
	<style type="text/css">
	.container-fluid{margin:30px;overflow: auto;font-size: 12px;}
	span.table_label {
	    font-size: 16px;
	    margin-left: 35%;
	}
	</style>
</head>
<body>
	<div class="container-fluid">
		<table id="final-table" class="table table-striped">
			<thead>
				<th>Platform</th>
				<th>Date</th>
				<th>Site Name</th>
				<th>Tag Id</th>
				<th>Tag Name</th>
				<th>Ad Unit</th>
				<th>Device</th>
				<th>Country</th>
				<th>Buyer</th>
				<th>Adserver Iimpressions</th>
				<th>SSP Impressions</th>
				<th>Filled Impressions</th>
				<th>Gross Revenue</th>
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
			/*$('#final-table').dataTable({
	            "ajax":{
	                "url":"{{url('/get-finaldata')}}",
	                "type":"GET",
	                dataSrc:"",
	            },
	            "columns":[{'data':'platform_name'},{'data':'date'},{'data':'site_name'},{'data':'tag_id'},{'data':'tag_name'},{'data':'ad_unit'},{'data':'device'},{'data':'country'},{'data':'buyer'},{'data':'adserver_impressions'},{'data':'ssp_impressions'},{'data':'filled_impressions'},{'data':'gross_revenue'}]
	        });*/
		$('#final-table').dataTable();
		$('#final-table_length').after('<span class="table_label">PLATFORM DATA</span>')
		});
	</script>
</body>
<footer>
</footer>
</html>