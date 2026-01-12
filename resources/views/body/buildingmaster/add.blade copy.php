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
              Building Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Building Master</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Building 
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmBuilding" id="frmBuilding" enctype="multipart/form-data" action="{{url('buildingmaster/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Building Code</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="buildingcode" name="buildingcode" autocomplete="off" placeholder="Building Code">
										</div>
									</div>
									
									<!--<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Building Type</label>
										<div class="col-sm-9">
											<select class="form-control" name="type" id="type" />
											<option value="">Select Type</option>
											<option value="Flat">Flat</option>
											<option value="Villas">Villas</option>
											<option value="Shops">Shops</option>
											</select>
										</div>
									</div>-->
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Prefix</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="prefix" name="prefix" autocomplete="off" placeholder="Prefix">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Building Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="buildingname" name="buildingname" autocomplete="off" placeholder="Building Name">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Description</label>
										<div class="col-sm-9">
											<textarea class="form-control" id="description" name="description" ></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Owner Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="ownername" name="ownername" autocomplete="off" placeholder="Owner Name">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Plot No. </label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="location" name="location" autocomplete="off" placeholder="Plote No.">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Location </label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="location" name="location" autocomplete="off" placeholder="Location">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Area</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="area" name="area" autocomplete="off" placeholder="Area">
										</div>
									</div>
									
                                    <div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Contact No. </label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="mobno" name="mobno" autocomplete="off" placeholder="Contact No.">
										</div>
									</div>

                                    <div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Document </label>
										<div class="col-sm-9">
										<input type="file" id="input-23" name="image" class="file-loading" data-show-preview="true" data-url="{{url('buildingmaster/upload/')}}" multiple="">
										<div id="files_list"></div>
										<p id="loading"></p>
										<input type="text" name="photo_name" id="photo_name">		</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Other Details</label>
										<div class="col-sm-9">
											<textarea class="form-control" id="description" name="description" autocomplete="off"></textarea>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('buildingmaster') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
 $(document).ready(function () {
 	var urlcode = "{{ url('buildingmaster/checkcode/') }}";
     $('#frmBuilding').bootstrapValidator({
         fields: {
 			buildingcode: {
                 validators: {
                     notEmpty: {
                         message: 'Building code is required and cannot be empty!'
                     },
					 remote: { url: urlcode,
							   data: function(validator) {
								return { buildingcode: validator.getFieldElements('buildingcode').val() };
							  },
							  message: 'Building code is not available'
                    }
                 }
             },
 			type: {
                 validators: {
                     notEmpty: {
                         message: 'Type is required and cannot be empty!'
                     }
                 }
             },
 			buildingname: {
                 validators: {
                     notEmpty: {
                        message: 'Building name is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmBuilding').data('bootstrapValidator').resetForm();
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
		   $('#photo_name').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
		   $('#loading').text('Completed.');
	   }
   });
</script>
@stop
