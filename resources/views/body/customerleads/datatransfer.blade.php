@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
		<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
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
              CRM
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> CRM
                    </a>
                </li>
                <li>
                    <a href="#">Data Transfer</a>
                </li>
                
            </ol>
        </section>
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Data Transfer
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmtransfer" id="frmtransfer"  action="{{url('customerleads/transfersave')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">From Transfer</label>
									<div class="col-sm-4">
										<select class="form-control" name="from_transfer" id="from_transfer" />
										<option value="">Select User</option>
										@foreach($userss_id as $row)
										<option value="{{$row->id}}">{{$row->name}}</option>
										@endforeach
										</select>
									</div>
								
									<label for="input-text" class="col-sm-2 control-label">To Transfer</label>
									<div class="col-sm-4">
										<select class="form-control" name="to_transfer" id="to_transfer" />
										<option value="">Select User</option>
										@foreach($userss_id as $row)
										<option value="{{$row->id}}">{{$row->name}}</option>
										@endforeach
										</select>
									</div>
								</div>	
                             
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Customers</label>
									<div class="col-sm-4">
										<select class="form-control select2" name="customers[]" multiple id="select21" />
										<option value="">Select Customers</option>
										@foreach($customers as $row)
										<option value="{{$row->id}}">{{$row->master_name}}</option>
										@endforeach
										</select>
									</div>
								</div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('customerleads/data_transfer') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
									</div>
								 </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
	
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
 $(document).ready(function () {
	
	$(document).ready(function () {
		$("#select21").select2({
			theme: "bootstrap",
			placeholder: "Customers"
		});
	});

     $('#frmtransfer').bootstrapValidator({
         fields: {
 		
					 
            from_transfer: {
                 validators: {
                     notEmpty: {
                         message: 'From Transfer is required and cannot be empty!'
                     }
                 }
             },
 			to_transfer: {
                 validators: {
                     notEmpty: {
                        message: 'To Transfer is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmtransfer').data('bootstrapValidator').resetForm();
     });
});
</script>
@stop
