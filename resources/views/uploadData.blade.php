<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<style type="text/css">
	.container{margin-top:50px;}
	</style>
</head>
<body>
	<div class="container">
		<form action="{{url('/store-data/rubicon')}}" class="col-md-6 col-md-offset-3 text-center" enctype="multipart/form-data" method="POST">
			{{ csrf_field() }}
			<div class="form-group"><input type="file" class="form-control" name='excel-file' required></div>
			<div class="form-group"><button type="submit" class="btn btn-primary">Upload</button></div>
		</form>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	</script>
</body>
<footer>
</footer>
</html>