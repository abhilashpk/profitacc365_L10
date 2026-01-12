<!DOCTYPE html>
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>
        @section('title')
            ProfitAcc 365
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link href="{{asset('assets/css/menubarfold.css')}}" rel="stylesheet">
@yield('header_styles')
<!-- end of global css -->
</head>
<body class="skin-coreplus">
<!-- header logo: style can be found in header-->
<header class="header">
    <nav class="navbar" role="navigation">
        <a href="#">
            <img src="{{asset('assets/numaklogo-small.jpg')}}" alt="logo"/>
        </a>
    </nav>
</header>
<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div class="wrapper row-offcanvas row-offcanvas-left">
   
    <aside class="right-side">

        <!-- Content -->
        @yield('content')

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>

<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
<script src="{{asset('assets/js/custom_js/menubarfold.js')}}" type="text/javascript"></script>
</body>

</html>
