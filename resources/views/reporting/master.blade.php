<!DOCTYPE html>
<html>
	<head>
		<title>Profitability Report Database</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	    <meta name="mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('css/select2.min.css')}}" />
		<link rel="stylesheet" href="{{asset('css/datepicker.min.css')}}" />
		<link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{asset('css/reporting.css')}}" />
		@yield('css')
	</head>
	<body>
		<header>
			@yield('header')
		</header>		
		<section class="col-md-12 col-sm-12 col-xs-12 content_wrapper">
			@yield('content')
		</section>
		<footer>
			@yield('footer')
		</footer>
		<script src="{{asset('js/jquery.min.js')}}"></script>
		<script src="{{asset('js/bootstrap.min.js')}}"></script>
		<script src="{{asset('js/select2.full.min.js')}}"></script>
		<script src="{{asset('js/datepicker.min.js')}}"></script>
		<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
		@yield('script')
	</body>
</html>