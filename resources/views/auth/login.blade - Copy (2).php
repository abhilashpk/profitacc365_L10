<!DOCTYPE html>
<html>

<head>
    <title>Profit ACC 365 - ERP Software - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}" />-->
	<link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- Bootstrap -->
    <!-- global css -->
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <!-- end of global css -->
    <!--page level css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link href="{{asset('assets/css/login2.css')}}" rel="stylesheet">
    <!--end page level css-->
</head>

<body>
<!--<div class="preloader">
    <div class="loader_img"><img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64"></div>
</div>-->
<div class="container">
    <div class="row" id="form-login">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 login-content">
            <div class="row">
                <div class="col-md-10">
                    <div class="header">
                        <h2 class="text-center">
                            Login
                            <small> with</small>
                            Profit ACC
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row row-bg-color">
                <div class="col-md-8 core-login">
                     <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label" for="email">EMAIL</label>
                                    <div class="input-group">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label" for="password">PASSWORD</label>
                                    <input id="password" type="password" class="form-control" name="password">
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <!--<input type="checkbox" name="remember" id="remember"> &nbsp;
                            <label for="remember"> Remember Me </label>
                            <a href="{{URL::to('forgot_password')}}" id="forgot" class="text-primary forgot1  pull-right"> Forgot Password? </a>-->
                        </div>
                        <div class="form-group ">
                            <button type="submit"  class="btn btn-primary login-btn">Login</button>
                            <br>
                            
                        </div>
                    </form>
                </div>
				<div class="logo-fit">
					 <img src="{{asset('assets/numaklogo-small.jpg')}}" alt="logo"/>
				</div>
                
            </div>
        </div>
    </div>
</div>
</body>
<!-- global js -->
<script src="{{asset('assets/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/backstretch.js')}}"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/login.js')}}"></script>
<!-- end of page level js -->


</html>
