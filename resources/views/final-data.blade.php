<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" />
	<style type="text/css">
	.container{margin-top:50px;}
	</style>
</head>
<body>
	<div class="container">
		<table id="final-table" class="table table-striped">
			<thead>
				<th>Platform</th>
				<th>Date</th>
				<th>Site Name</th>
				<th>Ad Unit</th>
				<th>Ad Requests</th>
				<th>Paid Impressions</th>
				<th>Revenue</th>

			</thead>
			<tbody></tbody>
		</table>
		<div class='col-md-12'>
			<form action="{{url('/exportdata-excel')}}" method="GET">
				<button type="submit" class="btn btn-primary" id="export">Export To Excel</button>
			</form>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#final-table').dataTable({
	            "ajax":{
	                "url":"{{url('/get-finaldata')}}",
	                "type":"GET",
	                dataSrc:"",
	            },
	            "columns":[{'data':'platform_name'},{'data':'date'},{'data':'site_name'},{'data':'ad_unit'},{'data':'ad_requests'},{'data':'paid_impressions'},{'data':'revenue'}]
	        });
		});
	</script>
</body>
<footer>
</footer>
</html>