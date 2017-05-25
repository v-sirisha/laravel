<div class="container-fluid" id="header">
	<div class="col-md-3">
		<a href="{{url('/')}}"><img src="{{asset('images/logo1.jpg')}}" alt='datawrkz'></a>
	</div>
	<div class="col-md-5 col-xs-5 col-sm-12">
        <h3 class="text-center">Profitability Report Database</h3>
  	</div>
  	<div class="col-md-4 col-xs-4 col-sm-4 ">
		<ul class="nav navbar-nav navbar-right">
			<li class="active"><a href="{{url('/')}}">HOME</a></li>
			<li class="active"><a href="{{url('/login')}}">LOGIN</a></li>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">TEMPLATES
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="{{url('/downloads/PR')}}">IO Publisher</a></li>
					<li><a href="{{url('/downloads/io_product')}}">Parent Publisher</a></li>
					<li><a href="{{url('/downloads/country')}}">Country</a></li>
					<li><a href="{{url('/downloads/device')}}">Device</a></li>
				</ul>
			</li>
			<li class="active"><a href="{{url('/logout')}}">LOGOUT</a></li>
		</ul>
  	</div>
</div>