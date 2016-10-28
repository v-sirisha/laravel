<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
   
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100' rel='stylesheet' type='text/css'>
    <link href="{{asset('css/template/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('css/template/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">
    <link href="{{asset('css/template/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/template/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{asset('css/template/owl.theme.css')}}" rel="stylesheet">
     <!-- theme stylesheet -->
    <link href="{{asset('css/template/style.default.css')}}" rel="stylesheet" id="theme-stylesheet">

    <!-- your stylesheet with modifications -->
    <link href="{{asset('css/template/custom.css')}}" rel="stylesheet">

    <script src="{{asset('js/template/respond.min.js')}}"></script>

    <link rel="shortcut icon" href="{{asset('css/template/favicon.png')}}">
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
    @yield('css')
</head>
<body id="app-layout">
    <header>
        @include('header')
    </header>
    <div class="container">
         @yield('content')
    </div>
   
    <footer>
        @include('footer')
    </footer>
    <!-- JavaScripts -->
    
    <script src="{{asset('js/template/jquery-1.11.0.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/jquery.cookie.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/waypoints.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/modernizr.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/bootstrap-hover-dropdown.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/template/owl.carousel.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{asset('js/template/front.js')}}"></script>-->
    @yield('script')
</body>
</html>
