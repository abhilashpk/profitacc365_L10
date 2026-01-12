<!DOCTYPE html>
<html>

<head>
    <title>Profit ACC 365 - ERP Software - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- end of bootstrap -->
    <!--page level css -->
    <link type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/vendors/iCheck/css/all.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/login.css')}}" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/simple-line-icons/css/simple-line-icons.css')}}">
    
	
    <!--end page level css-->
</head>

<body>

<div class="container">
    <div class="row">
        <div class="panel-header">
            <h2 class="text-center">
                <img src="{{asset('assets/numaklogo-small.jpg')}}" alt="logo"/><br/><br/>
				 Log in <i class="icon-login icons"></i>
            </h2>
        </div>
        <div class="panel-body social col-sm-offset-2">
            
           
            <div class="clearfix">
                <div class="col-xs-12 col-sm-9">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr"></span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-sm-6 form_width">
                     <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
						{{ csrf_field() }} <br/>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="sr-only"> E-mail</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope text-primary"></i></span>
                                <input type="email" class="form-control  form-control-lg" id="email" name="email" value="{{ old('email') }}" placeholder="E-mail">
                            </div>
							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
                        </div>
						
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock text-primary"></i></span>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
                            </div>
							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
                        </div>
                        <div class="form-group checkbox">
                            
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Log in" class="btn btn-primary btn-block"/>
                        </div>
                        <!--<a href="forgot_password.html" id="forgot" class="forgot"> Forgot Password? </a>-->
                    </form>
                </div>
				
				<div class="col-xs-12 col-sm-9">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr"></span>
                </div>
				
            </div>
			
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/login2.js')}}"></script>
<!-- end of page level js -->
</body>

</html>
