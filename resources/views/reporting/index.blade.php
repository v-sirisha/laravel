@extends('reporting.master')
@section('action_title')
Profitability Report Database
@stop
@section('header')
	@include('reporting.header')
@stop
@section('content')
	<div class="col-md-3 col-sm-3 col-xs-12 subsection">
		<div class="col-md-12 padding0" id="pt_upload_div">
			<h5 class="text-center">UPLOAD RAW DATA</h5>
			<form action="{{url('/store-data/rubicon')}}" class="col-md-12 col-xs-12 col-sm-12" enctype="multipart/form-data" method="POST">
				{{ csrf_field() }}
				<div class="form-group"><select class="form-control platform_sel" name='platform_name' ></select>
					<p id="pt_modified" class="pmargin"></p></div>
				<div class="form-group"><input class="form-control datepicker" name='start_date' placeholder='Start date of excel data' ></div>
				<div class="form-group"><input class="form-control datepicker" name='end_date' placeholder='End date of excel data' ></div>
				<div class="form-group"><input type="file" class="form-control" name='excel-file' ></div>
				<div class="form-group text-center"><button type="submit" class="btn btn-warning">Upload</button></div>
			</form>
		</div>
		<div class="col-md-12 col-xs-12 col-sm-12 padding0">
			<h5 class="text-center">UPLOAD LOOKUP TABLES</h5>
			<form action="{{url('/importToData')}}" class="col-md-12" enctype="multipart/form-data" method="POST">
				<div class="form-group">
					<select type="text" class="form-control" name='table_name' placeholder='Select Lookup Table Name' id="table_name">
					</select>
				</div>
				<div class="form-group"><input type="file" class="form-control" name='file' ></div>
				<div class="form-group text-center"><button type="submit" class="btn btn-warning">Upload</button></div>
			</form>
		</div>
	</div>
	<div class="col-md-5 col-sm-5 col-xs-12 subsection">
		<div class="col-md-12 col-xs-12 col-sm-12 padding0 warning_div">
			@if($pr_miss)
				<p><span class='glyphicon glyphicon-warning-sign'></span>&nbsp;  Parent Placement Lookup Table is incomplete</p>
			@endif
			@if($io_miss)
				<p><span class='glyphicon glyphicon-warning-sign'></span>&nbsp;  Publisher Details Table is incomplete</p>
			@endif
			@if(isset($country))
				<p><span class='glyphicon glyphicon-warning-sign'></span>&nbsp;  Device Type Lookup Table is incomplete</p>
			@endif
			@if(isset($device))
				<p><span class='glyphicon glyphicon-warning-sign'></span>&nbsp;  Country Group Lookup Table is incomplete</p>
			@endif
		</div>
		<div class="col-ms-12 col-xs-12 col-sm-12 padding0">
			<h5 class="text-center">UPDATE LOOKUPS</h5>
			@if($pr_miss)
				<div class="row">
					<p class="col-md-4">Parent Placement</p>
					<p class="col-md-4"><a href="{{url('update/PR')}}">Update Onscreen </a></p>
					<p class="col-md-4"><a href="{{url('download-excel/PR')}}">Update in Excel</a></p>
				</div>
			@endif
			@if($io_miss)
				<div class="row">
					<p class="col-md-4">Parent Publisher</p>
					<p class="col-md-4"><a href="{{url('update/io_product')}}">Update Onscreen</a></p>
					<p class="col-md-4"><a href="{{url('download-excel/io_product')}}">Update in Excel</a></p>
				</div>
			@endif
			<div class="row">
				<p class="col-md-4">Country Group</p>
				<p class="col-md-4"><a href="{{url('update/country')}}"> Update Onscreen</a></p>
				<p class="col-md-4"><a href="{{url('download-excel/country')}}">Update in Excel</a></p>
			</div>
			<div class="row">
				<p class="col-md-4">Device Type</p>
				<p class="col-md-4"><a href="{{url('update/device')}}">Update Onscreen</a></p>
				<p class="col-md-4"><a href="{{url('download-excel/device')}}">Update in Excel</a></p>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 subsection"></div>
@stop
@section('footer')
	@include('reporting.footer')
@stop
@section('script')
	<script type="text/javascript">
		var Platform_names_arr = ['AdTag','Rubicon','AdXTag','AdXDynamic','OpenX','PubMatic','PulsePoint-Dir','PulsePoint-FP','Sovrn','Matomy','MobFox'];
		var ad_unit = ['728x90','300x250','300x50','320x50','160x600','120x600','300x600','VAST','728x90_1','728x90_2','728x90_3','300x250_1','300x250_2','300x250_3','160x600_LHS','160x600_RHS'];
		var table_name = ['Parent Placement','Publisher Details','Country Group','Device Type'];
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
			$('#table_name').select2({
				placeholder:'Select Lookup Table ',
				data:table_name
			});
			$('#table_name').select2('val','all');
			$('.datepicker').datepicker();
		});
		$('.platform_sel').on('change',function(){
			$('#pt_modified').text('');
			var type = $(this).val();
			$.ajax({
				url:"{{url('get-lastmodified-date')}}"+'/'+type,
				type:"GET",
				success:function(data){
					console.log('data : '+data);
					if(data)
						$('#pt_modified').text('Updated till - '+data);
				}
			})
		})
	</script>
@stop