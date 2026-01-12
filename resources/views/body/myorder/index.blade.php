<!DOCTYPE html>
<html>

<head>
    <title>Courier APP - Login</title>
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
                <img src="{{asset('assets/numaklogo-small.jpg')}}"  alt="logo"/><br/><br/>
				 Driver Log in <i class="icon-login icons"></i>
            </h2>
        </div>
        <div class="panel-body social col-sm-offset-2">
            
           @if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
            <div class="clearfix">
                <div class="col-xs-12 col-sm-9">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr"></span>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-sm-6 form_width">
                     <form class="form-horizontal" role="form" method="POST" action="{{ url('/myorder/login') }}">
						{{ csrf_field() }} <br/>
						<div class="form-group">
                            <label > Enter your Login Code</label>
                               
                                <input type="text" class="form-control" id="code" name="code" placeholder="Login Code">
							
                        </div>
                        
                        <div class="form-group">
                            <input type="submit" value="Log in" class="btn btn-primary btn-block"/>
                        </div>
                      
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
