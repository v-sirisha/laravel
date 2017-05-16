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
		<h4 class="text-center">PR Details</h4>
		<table class="table table-striped" id="pr_table">
			<thead>
				<th>PF Name</th>
				<th>Date</th>
				<th>Site Name</th>
				<th>Ad Unit</th>
				<th>Ad Requests</th>
				<th>Paid Impressions</th>
				<th>Revenue</th>
				<th>IO Pub Name</th>
				<th>PubManager</th>
				<th>Opt Manager</th>
				<th>Date of Onboard</th>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$.ajax({
				type:'GET',
				url:"{{url('/get-pr')}}",
				data:{
					'site_name':"{{$site_name}}",
					'ad_unit':"{{$ad_unit}}"
				},
				success:function(data){
					if(data.length > 0){
						displayTable(data);
					}
				},
				error:function(data){
					console.log('error: '+data)
				}
			});
		});
		function displayTable(data){
			$.each(data,function(i,obj){
				var html = "<tr>";
					html += "<td>"+obj.platform_name+"</td>";					
					html += "<td>"+obj.date+"</td>";
					html += "<td>"+obj.site_name+"</td>";
					html += "<td>"+obj.ad_unit+"</td>";
					html += "<td>"+obj.ad_requests+"</td>";
					html += "<td>"+obj.paid_impressions+"</td>";
					html += "<td>"+obj.revenue+"</td>";
					html += "<td>"+obj.io_publisher_name+"</td>";
					html += "<td>"+obj.pubmanager+"</td>";
					html += "<td>"+obj.optimization_manager+"</td>";
					html += "<td>"+obj.date_of_onbording+"</td>";
					html += "</tr>";
				$('#pr_table tbody').append(html);
				$('#pr_table').dataTable();
				$('#pr_div').show();
			});
		}
	</script>
</body>
<footer>
</footer>
</html>