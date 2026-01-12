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
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
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
                    <a href="#">Work Order</a>
                </li>
                <li class="active">
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Work Order 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmWorkorder" id="frmWorkorder" action="{{url('ms_workorder/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO. No.</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="wono" readonly value="{{$wono}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Date Time</label>
										<div class="col-sm-9">
											<input type="text" name="creation_datetime" id="creation_datetime" class="form-control" value="{{$nowdate}}" autocomplete="off">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Enquiry No.</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="enquiry_no" name="enquiry_no" value="{{($werow!='')?$werow->enq_no:''}}" readonly onclick="getDocument()" autocomplete="off" placeholder="Click here to open enquiry list">
											<input type="hidden" name="enquiry_id" id="enquiry_id" value="{{($werow!='')?$werow->id:''}}">
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Project Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="job_name" name="job_name" autocomplete="off" data-toggle="modal" data-target="#job_modal">
											<input type="hidden" name="job_id" id="job_id">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Location</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="location" name="location" autocomplete="on" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Location</label>
										<div class="col-sm-9">
											<select id="location_id" class="form-control select2" style="width:100%" name="location_id">
												<option value="" <?php echo ($werow=='')?'selected':'';?>>Select Location..</option>
												@foreach($location as $row)
												<option value="{{ $row->id }}" <?php if($werow!='') { ?>{{($werow->location_id==$row->id)?'selected':''}}<?php } ?>>{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Customer</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="customer_name" value="{{($werow!='')?$werow->customer:''}}" name="customer_name" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
											<input type="hidden" name="customer_id" id="customer_id" value="{{($werow!='')?$werow->customer_id:''}}">
											<div id="caddress"></div>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Description</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="description" name="description" value="{{($werow!='')?$werow->description:''}}" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Type</label>
										<div class="col-sm-9">
											<select id="wo_type" class="form-control select2" style="width:100%" name="wo_type">
												<option value="" <?php echo ($werow=='')?'selected':'';?>>Select Work TYpe..</option>
												@foreach($wotype as $row)
												<option value="{{ $row->id }}" <?php if($werow!='') { ?>{{($werow->type_id==$row->id)?'selected':''}}<?php } ?>>{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Technician</label>
										<div class="col-sm-9">
											<select id="technician_id" class="form-control select2" style="width:100%" name="technician_id">
												<option value="" selected>Select Technician..</option>
												@foreach($technician as $row)
												<option value="{{ $row->id }}">{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="timeDivPrnt">
										<div class="timeDivChld">	
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Scheduled Time</label>
												<div class="col-sm-4">
													<input type="text" class="form-control timein" name="time_in[]" placeholder="Time in" autocomplete="off" >
												</div>
												<div class="col-sm-4">
													<input type="text" class="form-control timeout" name="time_out[]" placeholder="Time out" autocomplete="off" >
												</div>
												<div class="col-sm-1">
													<button type="button" class="btn-success btn-add-time" >
														<i class="fa fa-fw fa-plus-square"></i>
													 </button>
													 <button type="button" class="btn-danger btn-remove-time" >
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Total Time</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="total_time" name="total_time" readonly autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Status</label>
										<div class="col-sm-9">
											<select id="status" class="form-control select2" style="width:100%" name="status">
												<option value="" selected>Pending</option>
												<option value="1">Hold</option>
												<option value="2">Ongoing</option>
												<option value="3">Completed</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Remarks</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="remarks" value="{{($werow!='')?$werow->remarks:''}}" name="remarks" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Closed Date</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="closed_datetime" name="closed_datetime" autocomplete="off" >
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('ms_workorder') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
									
									<div id="job_modal" class="modal fade animated" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Projects</h4>
												</div>
												<div class="modal-body" id="jobData">
													
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div>
									
									<div id="customer_modal" class="modal fade animated" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Customer</h4>
												</div>
												<div class="modal-body" id="customerData">
													
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>
											</div>
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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

        <!-- end of page level js -->

<script>
"use strict";
$('.btn-remove-time').hide(); 
$(document).ready(function () {
    $('#frmWorkorder').bootstrapValidator({
        fields: {
            creation_datetime: {
                validators: {
                    notEmpty: {
                        message: 'Creation date is required and cannot be empty!'
                    }
                }
            },
			customer_name: {
                validators: {
                    notEmpty: {
                        message: 'Customer name is required and cannot be empty!'
                    }
                }
            },
			wo_type: {
                validators: {
                    notEmpty: {
                        message: 'Work type is required and cannot be empty!'
                    }
                }
            },
			technician: {
                validators: {
                    notEmpty: {
                        message: 'Technician name is required and cannot be empty!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmWorkorder').data('bootstrapValidator').resetForm();
    });
	
});


$(document).on('click', '.btn-add-time', function(e)  { 

	e.preventDefault();
	var controlForm = $('.timeDivPrnt'),
	currentEntry = $(this).parents('.timeDivChld:first'),
	newEntry = $(currentEntry.clone()).appendTo(controlForm);
	newEntry.find('input').val(''); 
	controlForm.find('.btn-add-time:not(:last)').hide();
	controlForm.find('.btn-remove-time').show();
	
	$('.timein').datetimepicker({
        datepicker: false,
        lang: 'en',
        format: 'H:i'
    });
	
	$('.timeout').datetimepicker({
        datepicker: false,
        lang: 'en',
        format: 'H:i'
    });
	
}).on('click', '.btn-remove-time', function(e) {
	
	$(this).parents('.timeDivChld:first').remove();
	$('.timeDivPrnt').find('.timeDivChld:last').find('.btn-add-time').show();
	if ( $('.timeDivPrnt').children().length == 1 ) {
		$('.timeDivPrnt').find('.btn-remove-time').hide();
	}
	
	e.preventDefault();
	return false;
});

var joburl = "{{ url('ms_jobmaster/get_jobs/') }}";
$('#job_name').click(function() {
	$('#jobData').load(joburl, function(result) {
		$('#myModal').modal({show:true}); $('.input-sm').focus()
	});
});
	
$(document).on('click', '.jobRow', function(e) {
	$('#job_name').val($(this).attr("data-name"));
	$('#job_id').val($(this).attr("data-id"));
	e.preventDefault();
});
	
	
var custurl = "{{ url('ms_customer/get_customer/') }}";
$('#customer_name').click(function() {
	$('#customerData').load(custurl, function(result) {
		$('#myModal').modal({show:true}); $('.input-sm').focus()
	});
});
	
$(document).on('click', '.customerRow', function(e) {
	$('#customer_name').val($(this).attr("data-name"));
	$('#customer_id').val($(this).attr("data-id"));
	$('#caddress').html('<b>Phone: '+$(this).attr("data-phone")+', Address: '+$(this).attr("data-address")+', City: '+$(this).attr("data-city")+', Area: '+$(this).attr("data-area")+'</b>');
	e.preventDefault();
});

$(document).on('blur keyup change', '.timeout', function(e) {
	
	var diff = 0;
	$( '.timeDivChld' ).each(function() { 
		var start_time = $(this).find('.timein').val();
		var end_time = $(this).find('.timeout').val();
		diff = diff + ( new Date("1970-1-1 " + end_time) - new Date("1970-1-1 " + start_time) ) / 1000 / 60 / 60; 
	});
	
	$('#total_time').val(diff);
});

function getDocument() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('ms_workenquiry/enquiry_list') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop
