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
              Flat Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Flat Master</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Flat 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" name="frmFlat" id="frmFlat" action="{{url('flatmaster/update/'.$frow->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" id="id" value="{{ $frow->id }}">
                                <input type="hidden" name="current_image" value="{{$frow->docname;}}">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Building</label>
										<div class="col-sm-10">
											<select class="form-control" name="building_id" id="buildingmaster" />
											<option value="">Select Building</option>
											@foreach($buildingmaster as $row)
											<option value="{{$row->id}}" {{($frow->building_id==$row->id)?'selected':''}}>{{$row->buildingcode}}</option>
											@endforeach
											</select>
										</div>
									</div>
                               
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Flat No</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="flat_no" name="flat_no" value="{{$frow->flat_no}}" autocomplete="off" placeholder="Flat No">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Flat Name</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="flat_name" name="flat_name" value="{{$frow->flat_name}}" autocomplete="off" placeholder="Flat Name">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Flat Description</label>
										<div class="col-sm-10">
											<textarea class="form-control" id="description" name="description" autocomplete="off">{{$frow->description}}</textarea>
										</div>
									</div>
									
                                    <div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Document</label>
									<div class="col-sm-9">
									<?php if($photos!='') { $arrp = explode(',',$photos); $i=1; ?>
										@foreach($arrp as $prow)
										<div class="file-preview">
											<div class="close fileinput-remove removeP" data-val="{{$prow}}">×</div>
											<div class="file-drop-disabled">
												<div class="file-preview-thumbnails">
													<div class="file-live-thumbs">
														<a href="{{asset('uploads/joborder/'.$prow)}}" target="_blank">View Image {{$i}}</a>
													</div>
												</div>
												<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
												<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
											</div>
										</div>
										<?php $i++; ?>
										@endforeach
									<?php } ?>
										<input type="file" id="input-23" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('job_order/upload/')}}" multiple="">
										<div id="files_list"></div>
										<p id="loading"></p>
										<input type="hidden" name="photo_name" id="photo_name" value="{{$photos}}">
										<input type="hidden" name="old_photo_name" id="old_photo_name" value="{{$photos}}">
										<input type="hidden" name="rem_photo_name" id="rem_photo_name">
									</div>
								</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('flatmaster') }}" class="btn btn-danger">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

        <!-- end of page level js -->
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
	 $('#frmFlat').validate({
        rules: {
            building_id: {
                required: true
            },
            flat_no: {
                required: true,
                remote: {
                    url: "{{ url('flatmaster/checkcode') }}",
                    type: "get",
                    data: {
                        flat_no: function () {
                            return $('#flat_no').val();
                        },
                        bid: function () {
                            return $('#building_id').val();
                        },
                        id: function () {
                            return $('#id').val(); // 👈 ignore current record
                        }
                    }
                }
            },
            flat_name: {
                required: true
            }
        },

        messages: {
            building_id: {
                required: "Building is required and cannot be empty!"
            },
            flat_no: {
                required: "Flat no is required and cannot be empty!",
                remote: "Flat no is not available"
            },
            flat_name: {
                required: "Flat name is required and cannot be empty!"
            }
        },

        errorElement: 'span',
        errorClass: 'help-block text-danger',

        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        }
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


$(document).on('click', '.removeP', function(e) {  
   var con = confirm('Are you sure to remove this image?');
   if(con) {
       var fnames = $('#photo_name').val().replace($(this).attr("data-val"),'');
       $('#photo_name').val(fnames);
       
       var rp = $('#rem_photo_name').val();
       $('#rem_photo_name').val( (rp=='')?$(this).attr("data-val"):rp+','+$(this).attr("data-val") );
       
       $(this).parents('.file-preview').remove();
   }
});
   
});

</script>
@stop
