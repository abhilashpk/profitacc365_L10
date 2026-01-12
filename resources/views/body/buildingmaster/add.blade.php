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
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesOrder" id="frmSalesOrder" action="{{ url('buildingmaster/save') }}">
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
									<label for="input-text" class="col-sm-3 control-label">Document</label>
									<div class="col-sm-9">
										<input type="file" id="input-23" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('job_order/upload/')}}" multiple="">
										<div id="files_list"></div>
										<p id="loading"></p>
										<input type="hidden" name="photo_name" id="photo_name">
									</div>
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

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

$(document).ready(function () {
 	var urlcode = "{{ url('buildingmaster/checkcode/') }}";
     
	$('#frmSalesOrder').validate({
		rules: {
			buildingcode: {
				required: true,
				remote: {
					url: "{{ url('buildingmaster/checkcode') }}",
					type: "get",
				}
			},
			buildingname: {
				required: true
			}
		},
		messages: {
			buildingcode: {
				required: "Building code is required",
				remote: "Building code already exists"
			}
		}
	});

});


	//calculation item net total, tax and discount...

	
	//calculation item line total and discount...


	

	
	
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
	
	


});
	
</script>
@stop
