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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
              Tenant Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Contract_Connection
                    </a>
                </li>
                <li>
                    <a href="#">Tenant Master</a>
                </li>
                <li class="active">
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Tenant
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmTenant" id="frmTenant"   action="{{url('tenantmaster/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="category" value="{{$category}}">

									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Customer Name</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Address </label>
										<div class="col-sm-4">
									       <input type="text" class="form-control" id="address" name="address" placeholder="Address">
										</div>
									</div>
										
                               
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Country</label>
										<div class="col-sm-4">
											<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
												<option value="">Select Country...</option>
												@foreach ($country as $con)
												<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
												@endforeach
											</select>
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Area </label>
										<div class="col-sm-4">
									      <select id="area_id" class="form-control select2" style="width:100%" name="area_id">
												<option value="">Select Area...</option>
												@foreach ($area as $ar)
												<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Phone</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Email </label>
										<div class="col-sm-4">
									       <input type="text" class="form-control" id="email" name="email" placeholder="Email">
										</div>
									</div>
										
                                
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Passport No.</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" id="passport_no" name="passport_no" autocomplete="off" placeholder="Passport No.">
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Passport Exp. </label>
										<div class="col-sm-4">
									       <input type="text" class="form-control" id="passport_exp" name="passport_exp" autocomplete="off" data-language='en' placeholder="Passport Exp.">
										</div>
									</div>

									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Nationality </label>
								        <div class="col-sm-4">
										<input type="text" class="form-control" id="nationality" name="nationality" autocomplete="off" placeholder="Nationality">
									</div>
									<label for="input-text" class="col-sm-2 control-label">Document</label>
									<div class="col-sm-4">
										<input type="file" id="input-23" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('job_order/upload/')}}" multiple="">
										<div id="files_list"></div>
										<p id="loading"></p>
										<input type="hidden" name="photo_name" id="photo_name">
									</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Ejarie</label>
										<div class="col-sm-4">
									       <input type="text" class="form-control" autocomplete="off" id="ejarie" name="ejarie" data-language='en' placeholder="Ejarie">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('tenantmaster') }}" class="btn btn-danger">Cancel</a>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>
<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>


<script>
"use strict";
$('#passport_exp').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#ejarie').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

 $(document).ready(function () {
	 var urlcode = "{{ url('flatmaster/checkcode/') }}";
     $('#frmFlat').bootstrapValidator({
         fields: {
 			building_id: {
                 validators: {
                     notEmpty: {
                         message: 'Building is required and cannot be empty!'
                     }
                 }
             },
 			flat_no: {
                 validators: {
                     notEmpty: {
                         message: 'Flat no is required and cannot be empty!'
                     },
					 remote: { url: urlcode,
						   data: function(validator) {
							return { flat_no: validator.getFieldElements('flat_no').val(),bid: validator.getFieldElements('building_id').val() };
						  },
						  message: 'Flat no is not available'
						}
                 }
				 
             },
 			flat_name: {
                 validators: {
                     notEmpty: {
                        message: 'Flat name is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmFlat').data('bootstrapValidator').resetForm();
     });
});

	
	
$(function() {	
	
    $('#input-23').fileupload({
       dataType: 'json',
       add: function (e, data) {
           $('#loading').text('Uploading...');
           data.submit();
       },
       done: function (e, data) {
           var pn = $('#photo_name').val();
           console.log(pn);
           $('#photo_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
           $('#loading').text('Completed.');
       }
   });
   
});
</script>
@stop
