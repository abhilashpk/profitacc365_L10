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
                 Maintenance System
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-cogs"></i> Maintenance System
                    </a>
                </li>
                <li>
                    <a href="#">Customers</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Customer 
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCustomer" id="frmCustomer" action="{{ url('ms_customer/update/'.$cstrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="veh_id" value="<?php echo $cstrow->id; ?>">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Name</label>
										<div class="col-sm-10">
											<input type="text" name="name" id="name" class="form-control" autocomplete="off" value="<?php echo $cstrow->name; ?>">
										</div>
									</div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Phone No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $cstrow->phone; ?>" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Address</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="address" name="address" value="<?php echo $cstrow->address; ?>" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">City</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="city" name="city" value="<?php echo $cstrow->city; ?>" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Area</label>
										<div class="col-sm-10">
											<select id="area" class="form-control select2" style="width:100%" name="area">
												<option value="" selected>Select Area..</option>
												@foreach($area as $row)
												<option value="{{ $row->id }}" {{($row->id==$cstrow->area)?'selected':''}}>{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">FAX</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="fax" name="fax" value="<?php echo $cstrow->fax; ?>" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('ms_customer') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                            </form>
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
	var urlcode = "{{ url('vehicle/checkregno/') }}";
    $('#frmCustomer').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'Name is required and cannot be empty!'
                    }
                }
            },
			phone: {
                validators: {
                    notEmpty: {
                        message: 'Phone is required and cannot be empty!'
                    }
                }
            },
			address: {
                validators: {
                    notEmpty: {
                        message: 'Address is required and cannot be empty!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmCustomer').data('bootstrapValidator').resetForm();
    });
	
});
</script>
@stop
