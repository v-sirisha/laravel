<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.5.2/datepicker.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" />
	<style type="text/css">
	.container,.tab-pane{margin-top:50px;}
	span.select2.select2-container.select2-container--default.select2-container--focus,.select2-container{width: 100% !important;}
	</style>
</head>
<body>
	<div class="container">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#upload" data-toggle="tab">Upload Platform Data</a></li>
			<li><a href="#addPr" data-toggle="tab">Add PR</a></li>
			<li><a href="#getPr" data-toggle="tab">Get PR</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="upload">
				<h4 class="text-center">UPLOAD EXCEL FILE</h4>
				<form action="{{url('/store-data/rubicon')}}" class="col-md-6 col-md-offset-3" enctype="multipart/form-data" method="POST">
					{{ csrf_field() }}
					<div class="form-group"><select class="form-control platform_sel" name='platform_name' ></select></div>
					<div class="form-group"><input class="form-control datepicker" name='start_date' placeholder='Start date of excel data' ></div>
					<div class="form-group"><input class="form-control datepicker" name='end_date' placeholder='End date of excel data' ></div>
					<div class="form-group"><input type="file" class="form-control" name='excel-file' ></div>
					<div class="form-group text-center"><button type="submit" class="btn btn-primary">Upload</button></div>
				</form>
				<div class="col-md-12 text-center"><a href="{{url('/final-data')}}" class="btn btn-primary">VIEW DATA</a></div>
				<div class="col-md-12" style="margin-top:30px;">
					<form action="{{url('/importToData')}}" class="col-md-6 col-md-offset-3" enctype="multipart/form-data" method="POST">
						<div class="form-group"><input type="file" class="form-control" name='file' ></div>
						<div class="form-group text-center"><button type="submit" class="btn btn-primary">Upload</button></div>
					</form>
				</div>
				
			</div>
			<div class="tab-pane fade" id="addPr">
				<h4 class="text-center">PR DETAILS</h4>
				<form action="{{url('/add-pr')}}" method="POST" class="col-md-6 col-md-offset-3">
					<input type="hidden" name="_token" id="with_csrf-token" value="{{ Session::token() }}"/>
					<div class="form-group">
						<input type="text" name="io_publisher_name" class='form-control' placeholder="Enter PR Name" required>
					</div>
					<div class="form-group">
						<input type="text" name="site_name" class='form-control' placeholder="Enter Site Name" required>
					</div>
					<div class="form-group">
						<input type="text" name="ad_unit" class='form-control ad_unit' placeholder="Enter Ad Unit" required>
					</div>
					<div class="form-group">
						<input type="text" name="pubmanager" class='form-control' placeholder="Enter Publisher Manger Name" required>
					</div>
					<div class="form-group">
						<input type="text" name="optimization_manager" class='form-control' placeholder="Enter Optimization Manager Name" required>
					</div>
					<div class="form-group">
						<input type="text" name="date_of_onbording" class='form-control datepicker' placeholder="Date of on Bording" required>
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary">ADD PR</button>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="getPr">
				<h4 class="text-center">PR REPORT</h4>
				<form action="{{url('/pr-view')}}" method="GET" class="col-md-6 col-md-offset-3">
					<!-- <div class="form-group col-md-3">
						<select class="form-control platform_sel" name='platform_name' required></select>
					</div> -->
					<div class="form-group">
						<input class="form-control" name='site_name' type="text" placeholder="Site Name" required></select>
					</div>
					<div class="form-group"><input name="ad_unit" class="form-control ad_unit" required></div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary" id="pr_btn">Get PR</button>
					</div>
				</form>
			</div>
		</div>
		
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.5.2/datepicker.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript">
		var Platform_names_arr = ['AdTag','Rubicon','AdXTag','AdXDynamic','OpenX','PubMatic','PulsePoint-Dir','PulsePoint-FP','Sovrn','Matomy','MobFox'];
		var ad_unit = ['728x90','300x250','300x50','320x50','160x600','120x600','300x600','VAST','728x90_1','728x90_2','728x90_3','300x250_1','300x250_2','300x250_3','160x600_LHS','160x600_RHS'];
		$(document).ready(function(){
			$('.platform_sel').select2({
				placeholder:'Select Platform',
				data:Platform_names_arr
			});
			$('.platform_sel').select2('val','all');
			$('.ad_unit').select2({
				placeholder:'Select Ad Unit',
				data:ad_unit
			});
			$('.ad_unit').select2('val','all');
			$('.datepicker').datepicker();
		});
		
	</script>
</body>
<footer>
</footer>
</html>