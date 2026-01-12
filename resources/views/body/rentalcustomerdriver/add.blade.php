@extends('layouts/default')

{{-- Page title --}}
@section('title')

    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/iCheck/css/all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css') }}"
        media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/formelements.css') }}">
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Customer's Driver
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-home"></i> RentalEntry
                </a>
            </li>
            <li>
                <a href="#"> Customer's Driver</a>
            </li>
            <li class="active">
                Add New
            </li>
        </ol>
    </section>
    <!--section ends-->
    @if (Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-crosshairs"></i> New  Customer's Driver
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmCdriver" id="frmCdriver"
                            action="{{ url('rental_customerdriver/save') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="customer" id="select23" style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($customer as $crow)
											<option value="{{$crow->id}}">{{$crow->master_name}}</option>
											@endforeach
										</select>
                                    </div>
                            </div>

                            <div class="form-group">
                               <label for="input-text" class="col-sm-2 control-label">Driver</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="driver[]" id="select22" multiple style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($driver as $prow)
											<option value="{{$prow->id}}">{{$prow->driver_name}}</option>
											@endforeach
										</select>
								   </div>
                            </div>



                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('rental_customerdriver') }}" class="btn btn-danger">Cancel</a>
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
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/custom_js/form_elements.js') }}"></script>
    <!-- end of page level js -->

    <script>
        "use strict";

        $(document).ready(function() {

        $("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Driver"
    });
	
	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Select Customer"
    });
         
        });
    </script>
@stop
