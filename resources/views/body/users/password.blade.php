@extends('layouts/default')

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
                Users
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-group"></i> User Management
                    </a>
                </li>
                <li class="active">
                    Password Reset
                </li>
            </ol>
        </section>
        <!--section ends-->
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Password Reset
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<form role="form" method="POST" name="frmPasswordReset" id="frmPasswordReset" action="{{ url('users/reset/password') }}">
							<div class="row">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Current Password:</strong>
										<input type="password" class="form-control" id="password" name="password" placeholder="Password">
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>New Password:</strong>
										<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password">
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="form-group">
										<strong>Confirm Password:</strong>
										<input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirm-Password">
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-12 col-md-12 text-center">
										<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
							</form>
							<!--</form>-->
                        </div>
                    </div>
                </div>
            </div>
       
            <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	var url = "{{ url('unit/checkname/') }}";
    $('#frmUnit').bootstrapValidator({
        fields: {
            unit_name: {
                validators: {
                    notEmpty: {
                        message: 'The unit name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                unit_name: validator.getFieldElements('unit_name').val()
                            };
                        },
                        message: 'The unit name is not available'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmUnit').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
