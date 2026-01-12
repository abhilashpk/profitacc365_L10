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
                Leads
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i> Leads
                    </a>
                </li>
                
				<li class="active">
                    Add
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Lead 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmLeads" id="frmLeads" action="{{ url('leads/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Lead No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lead_no" readonly name="lead_no" value="{{$voucherno}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Lead Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Type</label>
                                    <div class="col-sm-10">
										<select id="customer_type" class="form-control select2" style="width:100%" name="customer_type">
                                            <option value="0">New</option>
                                            <option value="1">Existing</option>
                                        </select>
                                    </div>
                                </div>
							
							<div id="old_cust">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" data-toggle="modal" data-target="#customer_modal" placeholder="Customer Name">
										<input type="hidden" name="customer_id" id="customer_id">
                                    </div>
                                </div>
							</div>
							
							<div id="new_cust">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Fax</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contact Person</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Contact Person">
                                    </div>
                                </div>
							</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Lead Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Lead Remarks</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                       <select id="salesman_id" class="form-control select2" style="width:100%" name="salesman_id">
                                            <option value="">Select Salesman...</option>
											<?php foreach($salesman as $row) { ?>
                                            <option value="{{$row->id}}">{{$row->name}}</option>
											<?php } ?>
                                        </select>
									</div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-10">
                                        <select id="lead_status" class="form-control select2" style="width:100%" name="lead_status">
                                            <option value="Suspect">Suspect</option>
                                            <option value="Prospective">Prospective</option>
                                            <option value="Enquiry">Enquiry</option>
                                        </select>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('leads') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                
								<div id="customer_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Customer</h4>
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

	
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
        <!-- end of page level js -->

<script>

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$('#issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
$(document).ready(function () {
	$('#old_cust').toggle();
    $('#frmLeads').bootstrapValidator({
        fields: {
            customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
            title: { validators: { notEmpty: { message: 'Title is required and cannot be empty!' } }},
            remarks: { validators: { notEmpty: { message: 'Remarks is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmLeads').data('bootstrapValidator').resetForm();
    });
});

$(function() {	
	$('#customer_type').on('change', function(e){
		if(e.target.value=='0') {
			if( $('#new_cust').is(":hidden") )
				$('#new_cust').toggle();
			
			if( $('#old_cust').is(":visible") )
				$('#old_cust').toggle();
			
		} else {
			if( $('#new_cust').is(":visible") )
				$('#new_cust').toggle();
			
			if( $('#old_cust').is(":hidden") )
				$('#old_cust').toggle();
		}
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	
	$(document).on('click', '.custRow', function(e) { //console.log($(this).attr("data-trnno"));
	
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
	});
});
</script>
@stop
