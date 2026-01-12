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
                    <a href="#">Work Enquiry</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i>  Edit Work Enquiry 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmWorkenquiry" id="frmWorkenquiry" action="{{url('ms_workenquiry/update/'.$worow->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Enquiry No</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="enqno" readonly value="{{$worow->enq_no}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">WO Date Time</label>
										<div class="col-sm-9">
											<input type="text" name="creation_datetime" id="creation_datetime" class="form-control" value="{{date('d-m-Y H:i', strtotime($worow->enquiry_datetime))}}" autocomplete="off">
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
										<label for="input-text" class="col-sm-3 control-label">Location</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="location" name="location" value="{{$worow->location}}" autocomplete="on" >
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
										<label for="input-text" class="col-sm-3 control-label">Status</label>
										<div class="col-sm-9">
										<?php if($worow->status!=1) { ?>
											<select id="status" class="form-control select2" style="width:100%" name="status">
												<option value="" {{($worow->status==0)?'selected':''}}>Open</option>
												<option value="2" {{($worow->status==2)?'selected':''}}>Cancelled</option>
											</select>
										<?php } else { ?>
										<input type="hidden" name="status" value="1">
										<input type="text" class="form-control" value="Transfered" readonly >
										<?php } ?>
										</div>
									</div>
									
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Remarks</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="remarks" name="remarks" value="{{$worow->remarks}}" autocomplete="off" >
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label"></label>
										<div class="col-sm-9">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('ms_workenquiry') }}" class="btn btn-danger">Cancel</a>
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

$(document).ready(function () {
    $('#frmWorkenquiry').bootstrapValidator({
        fields: {
            creation_datetime: {
                validators: {
                    notEmpty: {
                        message: 'Enquiry date is required and cannot be empty!'
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
            }
        }
        
    }).on('reset', function (event) {
        $('#frmWorkenquiry').data('bootstrapValidator').resetForm();
    });
	
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

</script>
@stop
