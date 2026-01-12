@extends('layouts/myorder')

    {{-- Page title --}}
    @section('title')
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Dashboard <div class="pull-right"> <a href="index"><h4><b>{{Session::get('driver')}}</b></h4></a></div>
            </h1>
            <ol class="breadcrumb">
                <li>
					<b><div class="pull-right"> Dashboard | <a href="{{ url('myorder/list') }}">Orders</a> | <a href="{{ url('myorder/logout') }}">Logout</a></div></b>
                </li>
				
            </ol>
			
        </section>
		
      	
        <section class="content">
            <div class="row">
				<div class="col-md-4">
				
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-align-justify"></i> Order Summary
                            </h3>
                            <span class="pull-right">
                                   
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <dl>
									<dd>
                                       <b>Delivered: {{$summary['delivered']}}</b>
                                    </dd>
									<dd>
                                       <b><a href="{{ url('myorder/pending') }}">Pending: {{$summary['pending']}}</a></b>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
					
					
                </div>
			</div>
        
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

</script>
@stop
