<!DOCTYPE html>
<html>

<head>
    <title>Lockscreen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- styles -->
    <!--page level css -->
    <link href="{{asset('assets/css/lockscreen2.css')}}" rel="stylesheet">
    <!--end page level css-->
</head>

<body class="background-img">
<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
			<div class="lockscreen-container">
			@if(Session::has('error'))
			<div id="output" class="alert alert-danger animated fadeInUp">{{ Session::get('error') }}</div>
			@endif
			
			@if(Session::has('message'))
			<div id="output" class="alert alert-success animated fadeInUp">{{ Session::get('message') }}</div>
			@endif
            <?php if(!$loggedin) { ?>
                <div id="output"></div>
                <div class="user-name">
                    <h4 class="text-center">Profit ACC 365</h4>
                    <small>SUPERADMIN</small>
                </div>
                <div class="avatar"></div>
                <div class="form-box">
                    <form method="POST" name="frmProfit" id="frmProfit" action="{{url('settings/login')}}">
                        <div class="form">
                            <h4>
                                <small class="locked">Enter the Password to Login</small>
                            </h4>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                            <button class="btn btn-info login" id="index" type="submit">GO</button>
                        </div>
                    </form>
                </div>
			<?php } else { ?>
                <div id="output"></div>
                <div class="user-name">
                    <h4 class="text-center">Profit ACC 365</h4>
                    <small>Switch Database</small>
                </div>
               
                <div class="form-box">
                    <form method="POST" name="frmProfit" id="frmProfit" action="{{url('settings/submit_dbswitch')}}">
                        <div class="form">
                            <h4>
                                <small class="locked">Enter the Year of Database</small>
                            </h4>
                            <input type="text" name="year" class="form-control" placeholder="Year">
                            <button class="btn btn-info login" id="index" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
			<?php } ?>
			</div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- page css -->
<script src="{{asset('assets/js/lockscreen2.js')}}"></script>
<!-- end of page css -->
</body>

</html>