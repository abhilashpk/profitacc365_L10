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
                                <i class="fa fa-fw fa-crosshairs"></i>  Edit Work Order 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmWorkorder" id="frmWorkorder" action="{{url('ms_workorder/update/'.$worow->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO. No.</label>
										<div class="col-sm-9">
											<input type="text" name="wo_no" id="wo_no" class="form-control" value="{{$worow->wo_no}}" readonly autocomplete="off">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO. Date Time</label>
										<div class="col-sm-9">
											<input type="text" name="creation_datetime" id="creation_datetime" class="form-control" value="{{date('d-m-Y H:i', strtotime($worow->creation_datetime))}}" autocomplete="off">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Enquiry No.</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="enquiry_no" name="enquiry_no" value="{{$worow->enq_no}}" autocomplete="off" readonly>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Project Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="job_name" name="job_name" autocomplete="off" value="{{$worow->jobname}}" data-toggle="modal" data-target="#job_modal">
											<input type="hidden" name="job_id" id="job_id" value="{{$worow->job_id}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Location</label>
										<div class="col-sm-9">
											<select id="location_id" class="form-control select2" style="width:100%" name="location_id">
												<option value="" selected>Select Location..</option>
												@foreach($location as $row)
												<option value="{{ $row->id }}" {{($row->id==$worow->location_id)?'selected':''}}>{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Customer</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{$worow->customer}}" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
											<input type="hidden" name="customer_id" id="customer_id" value="{{$worow->customer_id}}">
											<div id="caddress"></div>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Description</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="description" name="description" value="{{$worow->description}}" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Type</label>
										<div class="col-sm-9">
											<select id="wo_type" class="form-control select2" style="width:100%" name="wo_type">
												<option value="" selected>Select Work TYpe..</option>
												@foreach($wotype as $row)
												<option value="{{ $row->id }}" {{($row->id==$worow->type_id)?'selected':''}}>{{ $row->name }}</option>
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
												<option value="{{ $row->id }}" {{($row->id==$worow->technician_id)?'selected':''}}>{{ $row->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<input type="hidden" name="rem_time" id="remtime">
									<div class="timeDivPrnt">
										<?php if(count($times)==0) { ?>
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
										<?php } else { ?>
										@foreach($times as $row)
										<div class="timeDivChld">	
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Scheduled Time</label>
												<div class="col-sm-4">
													<input type="hidden" name="timeid[]" value="{{$row->id}}">
													<input type="text" class="form-control timein" name="time_in[]" placeholder="Time in" value="{{$row->time_in}}" autocomplete="off" >
												</div>
												<div class="col-sm-4">
													<input type="text" class="form-control timeout" name="time_out[]" placeholder="Time out" value="{{$row->time_out}}" autocomplete="off" >
												</div>
												<div class="col-sm-1">
													<button type="button" class="btn-success btn-add-time" >
														<i class="fa fa-fw fa-plus-square"></i>
													 </button>
													 <button type="button" class="btn-danger btn-remove-time" data-id="{{$row->id}}">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
											</div>
										</div>
										@endforeach
										<?php } ?>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Total Time</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="total_time" name="total_time" value="{{$worow->total_time}}" readonly autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Status</label>
										<div class="col-sm-9">
											<select id="status" class="form-control select2" style="width:100%" name="status">
												<option value="" {{($worow->status==0)?'selected':''}}>Pending</option>
												<option value="1" {{($worow->status==1)?'selected':''}}>Hold</option>
												<option value="2" {{($worow->status==2)?'selected':''}}>Ongoing</option>
												<option value="3" {{($worow->status==3)?'selected':''}}>Completed</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Remarks</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="remarks" name="remarks" value="{{$worow->remarks}}" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Closed Date</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="closed_datetime" name="closed_datetime" value="{{($worow->closed_datetime=='0000-00-00 00:00:00')?'':date('d-m-Y H:i', strtotime($worow->closed_datetime))}}" autocomplete="off" >
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

$('.timeDivPrnt').find('.btn-add-time:not(:last)').hide();
if ( $('.timeDivPrnt').children().length == 1 ) {
	$('.timeDivPrnt').find('.btn-remove-time').hide();
}
	
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
	
	var remtime = $('#remtime').val(); var ids;
	ids = (remtime=='')?$(this).attr("data-id"):remtime+','+$(this).attr("data-id");
	$('#remtime').val(ids);
		
	$(this).parents('.timeDivChld:first').remove();
	$('.timeDivPrnt').find('.timeDivChld:last').find('.btn-add-time').show();
	if ( $('.timeDivPrnt').children().length == 1 ) {
		$('.timeDivPrnt').find('.btn-remove-time').hide();
	}
	
	
	var diff = 0;
	$( '.timeDivChld' ).each(function() { 
		var start_time = $(this).find('.timein').val();
		var end_time = $(this).find('.timeout').val();
		diff = diff + ( new Date("1970-1-1 " + end_time) - new Date("1970-1-1 " + start_time) ) / 1000 / 60 / 60; 
	});
	
	$('#total_time').val(diff);
	
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

</script>
@stop
