@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Journal
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li>
                    <a href="#">Journal</a>
                </li>
                <li class="active">
                    Add New
                </li>
            </ol>
        </section>
		
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
				<?php if(sizeof($vouchers)==0) { ?>
				<div class="alert alert-warning">
					<p>
						Journal voucher is not found! Please create a voucher in Account Settings.
					</p>
				</div>
				<?php } else { ?>
				
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Journal 
                            </h3>
                           
						   <div class="pull-right">



							<?php if($printid) { ?>
								@permission('jv-print')
								<form class="form-horizontal" role="form" method="GET" target="_blank" name="frmItem" id="frmItem" action="{{ url('journal/getvoucherprint') }}">
						   <div class="form-group">
						   <?php //if(!empty($remarks)){ ?> 
							<div class="col-xs-4">
									<select id="voucher_typeprint" class="form-control select2" style="width:100%" name="voucher_typeprint">
									<option value="0">--Select voucher Type --</option>
									
									<option value="16">JV - Journal Voucher</option>
											<option value="5">PIN - Purchase Invoice(Non-Stock)</option>
											<option value="6">SIN - Sales Invoice(Non-Stock)</option>
											<option value="9">RV - Receipt Voucher</option>
											<option value="10">PV - Payment Voucher</option>
										</select> 
										</div>
										<div class="col-xs-4">
                                        <input type="text" class="form-control pull-right"  style="width:50%"  name="voucherprnt_no" placeholder="voucher no" autocomplete="off"  id="voucherprnt_no"   />
										</div>
                                
								<button type="submit" class="btn btn-info btn-sm" > <span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span></button></div></form>
								 <!-- <a href="{{ url('journal/print/'.$printid->id) }}" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a> -->
								@endpermission
							<?php } ?>
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('journal/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="16">JV - Journal Voucher</option>
											<option value="5">PIN - Purchase Invoice(Non-Stock)</option>
											<option value="6">SIN - Sales Invoice(Non-Stock)</option>
											<option value="9">RV - Receipt Voucher</option>
											<option value="10">PV - Payment Voucher</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
											@foreach($vouchers as $voucher)
											<option value="{{$voucher->id}}">{{$voucher->voucher_name}}</option>
											@endforeach
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JV. No.</label>
									<input type="hidden" name="curno" id="curno">
									<input type="hidden" value="{{$voucher->prefix}}" name="prefix">
									<input type="hidden" value="{{$vchrdata['vno']}}" name="vno">
									<input type="hidden" value="{{$voucher->is_prefix}}" name="is_prefix">
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" value="{{$vchrdata['voucher_no']}}" readonly name="voucher_no">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JV. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" autocomplete="off" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
									<input type="hidden" name="chktype" id="chktype">
									<input type="hidden" name="is_onaccount" id="is_onaccount" value="1">
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Transactions</h5></legend>
										<div class="itemdivPrnt">
											<div class="itemdivChld">
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_1" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" id="draccountid_1">
														<input type="hidden" name="group_id[]" id="groupid_1">
														<input type="hidden" name="vatamt[]" id="vatamt_1">
														
														<input type="hidden" id="invoiceid_1" name="sales_invoice_id[]">
														<input type="hidden" name="bill_type[]" id="biltyp_1">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_1" autocomplete="off" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_1" class="refdata">
															<input type="text" id="ref_1" name="reference[]" autocomplete="off" class="form-control">
															</div>
															<input type="hidden" name="inv_id[]" id="invid_1">
															<input type="hidden" name="actual_amount[]" id="actamt_1">
														</div>
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_1" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr">Dr</option>
																<option value="Cr">Cr</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_1" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-sm-2" style="width:17%;"> 
															<span class="small">Job</span> 
															<select id="jobid_1" class="form-control select2 line-job" style="width:100%" name="job_id[]">
																<option value="">Select Job...</option>
																@foreach($jobs as $job)
																<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
																@endforeach
															</select>
														</div>
														@if($isdept)
														<div class="col-xs-3" style="width:13%;">
															<span class="small">Department</span> 
															<select id="dept_1" class="form-control select2 line-dept" style="width:100%" name="department[]">
																<option value="">Department...</option>
																@foreach($departments as $department)
																<option value="{{ $department->id }}">{{ $department->name }}</option>
																@endforeach
															</select>
														</div>
														@endif
														<div class="col-xs-1 abc" style="width:3%;"><br/>
															<button type="button" class="btn-danger btn-remove-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
															 <button type="button" class="btn-success btn-add-item" >
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
														</div>
														
														<div id="chqdtl_1" class="divchq" style="display:none;">
															
															<div class="col-sm-2"> 
																<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
															</div>
															
															<div class="col-xs-2">
																<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
															</div>
															
															<div class="col-xs-2">
																<span class="small">Bank</span> 
																<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																	<option value="">Select Bank...</option>
																	@foreach($banks as $bank)
																	<option value="{{$bank['id']}}">{{$bank['code'].' - '.$bank['name']}}</option>
																	@endforeach
																</select>
															</div>
															
															<div class="col-xs-2">
																<input type="hidden" name="partyac_id[]" id="partyac_1">
																<span class="small">Party Name</span> <input type="text" id="party_1" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
															</div>
															
														</div>
												</div>
												
											</div>
										</div>
								</fieldset>
								
								<hr/>
								
								<div class="form-group" id="trndtl_1" class="divTrn">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-5"> 
											<span class="small"><b>Supplier Name</b></span><input type="text" autocomplete="off" id="supname_1" name="supplier_name" class="form-control" >
										</div>
										<div class="col-xs-5">
											<span class="small"><b>TRN No</b></span> <input type="text" autocomplete="off" id="trnno_1" name="trn_no" class="form-control">
										</div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JV Type</label>
                                    <div class="col-sm-10">
                                        <select id="jvtype" class="form-control select2" style="width:100%" name="jvtype">
											<option value="">Normal</option>
											<option value="RC">Recurring</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Recurring Period</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="rcperiod" name="rcperiod">
                                    </div>
									<div class="col-sm-1">
										<button type="button" class="btn btn-primary addRecu">Add</button>
									</div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('journal') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('journal') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
								
                            </form>
							
							<div id="account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="account_data">
											</div>
										</div>
									</div>
								</div> 
								
								<div id="reference_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog" style="width:60%;">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Invoice</h4>
											</div>
											<div class="modal-body" id="invoiceData">
												
											</div>
										</div>
									</div>
								</div> 
								
								<div id="paccount_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="paccount_data">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> 
								
							</div>
                        </div>
                    </div>
				<?php } ?>
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

<script>
//console.log(localStorage.getItem("vType")+' '+localStorage.getItem("vName")+' '+localStorage.getItem("vNumber"));
$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
//$('#voucher_type select').val("5").change();
//var v=5;
//$('#voucher_type option[value='+v+']').attr('selected','selected');
"use strict";

$(document).ready(function () {
	
	//AUTO LOADING LAST ADDED VOUCHER....
	if(localStorage.getItem("vType")!=null && localStorage.getItem("vName")!=null) {
		
		$.get("{{ url('journal/getvouchertype/') }}/" + localStorage.getItem("vType"), function(data) { 
			$('#voucher').empty();
			//$('#voucher').append('<option value="">Select Voucher...</option>');
			$.each(data, function(value, display){
				 $('#voucher').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
			
		let vchr_id = localStorage.getItem("vName"); 
		$.get("{{ url('journal/getvoucher/') }}/" + vchr_id, function(data) { 
			$('#voucher_no').val(data);
			$('#curno').val(data);
			
			localStorage.setItem("vName", vchr_id);
			$('#voucher_type option[value='+localStorage.getItem("vType")+']').attr('selected','selected');
			$('#voucher option[value='+localStorage.getItem("vName")+']').attr('selected','selected');
		});
	}
	
	var trnurl = "{{ url('journal/set_transactions/') }}";
	$('#trns_1').load( trnurl+'/CASH/CASH/1');
	$('.btn-remove-item').hide(); 
	
	var urlvchr = "{{ url('journal/checkvchrno/') }}"; //CHNG
     $('#frmJournal').bootstrapValidator({
         fields: {
            voucher_type: {
                validators: {
                    notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    }
                }
            },
			voucher_no: {
                validators: {
                    notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    },
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val(),
								vtype: validator.getFieldElements('voucher_type').val()
                            };
                        },
                        message: 'This Voucher No. is already exist!'
                    }
                }
            },
			"account_name[]": {
                validators: {
                    notEmpty: {
                        message: 'The account name is required and cannot be empty!'
                    }
                }
            },
			"description[]": {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            },
			"line_amount[]": {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			"cheque_no[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque no is required and cannot be empty!'
                    }
                }
            },
			"cheque_date[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque date is required and cannot be empty!'
                    }
                }
            },
			"bank_id[]": {
                validators: {
                    notEmpty: {
                        message: 'The bank is required and cannot be empty!'
                    }
                }
            },
			debit: {
                validators: {
                    identical: {
                        field: 'credit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            },
            credit: {
                validators: {
                    identical: {
                        field: 'debit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            }
			
        }
        
    }).on('reset', function (event) {
        $('#frmJournal').data('bootstrapValidator').resetForm();
    }); 
	
	
	$("#chequeInput").toggle();
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		
	});
	
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$('#amount_fc').val('');$("#currency_rate").val('');
	});
	
	
	$('#voucher_type').on('change', function(e){
		$('#voucher_no').val('');
		var vchr_id = e.target.value; 
		$.get("{{ url('journal/getvouchertype/') }}/" + vchr_id, function(data) { 
			$('#voucher').empty();
			$('#voucher').append('<option value="">Select Voucher...</option>');
			$.each(data, function(value, display){
				 $('#voucher').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
		
	});
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('journal/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			$('#voucher_no').val(data);
			$('#curno').val(data); //CHNG
			
			localStorage.setItem("vType", $('#voucher_type option:selected').val());
			localStorage.setItem("vName", vchr_id);
		});
		
	});
	
});

$(function(){
	
		
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
   $('#trndtl_1').toggle(); //$('#chqdtl_1').toggle();
  var rowNum = 1;
  
  $(document).on('click', '.btn-add-item', function(e)  { 
  
        var group_id = $('#groupid_'+rowNum).val();
		var refno = $('#ref_'+rowNum).val();
		var curNum = rowNum;
		rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
			newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
			newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
			newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
			newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
			newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
			newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
			newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
			newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
			newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
			newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
			newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
			newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
			newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
			newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
			newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum);
			newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
			
			$("#amount_"+rowNum).val(parseFloat($("#amount_"+rowNum).val()));
			$('#draccount_'+rowNum).val('');
			$('#draccountid_'+rowNum).val('');
			//$('#actamt_'+rowNum).val('');
			//$('#amount_'+rowNum).val('');
			if(group_id!='1') {
				if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') { 
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
					getNetTotal(); 
					$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
				} else {
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
					/* $('#debit').val($('#credit').val());
					$('#difference').val(0.00); */
					 $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
				}
			}
				
			//newEntry.find($('input[name="description[]"]')).val(des);
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			//controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			if(group_id=='1') {// group.id VAT input expense type.
				//console.log('vat');
		    	//New Change[Kavya]
				window.con = confirm('Do you want to update VAT account?');
				//End of Changes[Kavya]
				if(con) {
					$('#draccount_'+rowNum).val( '<?php echo ($account)?$account->master_name:'';?>' );
					$('#draccountid_'+rowNum).val( '<?php echo ($account)?$account->expense_account:'';?>' );
					$('#groupid_'+rowNum).val( '<?php echo ($account)?$account->id:'';?>' ); //VAT master id
					
					var amount = parseFloat($('#amount_'+(rowNum-1)).val());
					var vat = parseFloat($('#vatamt_'+(rowNum-1)).val());
					var vatamt = amount * vat / 100;
					$('#amount_'+rowNum).val(vatamt);
					$('#ref_'+rowNum).val(refno);
					controlForm.find('.btn-add-item').hide();
					
					var amt = 0;
					$( '.jvline-amount' ).each(function() { 
						amt = amt + parseFloat( (this.value=='')?0:this.value );
					});
	
					var controlForm = $('.controls .itemdivPrnt'),
					currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
					newEntry = $(currentEntry.clone()).appendTo(controlForm);
					rowNum++;
					newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
					newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
					newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
					newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
					newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
					newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
					newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
					newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val(amt);
					newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
					newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
					newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
					newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
					newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
					newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
					newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
					newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
					newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
					newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
					newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
					newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
					newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
					var des = $('input[name="description[]"]').val();
					
					//New Change By Kavya
					
					$("#amount_"+rowNum).val((parseFloat($("#amount_"+(rowNum-1)).val()))+(parseFloat($("#amount_"+(rowNum-2)).val())));
															
				    if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') { 
					
						$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option>');				
						$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
						$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
						
					} 
					getNetTotal();
					$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
					
					//End Of Changes[Kavya]
					
					
					//newEntry.find('input').val('');
					$('#trndtl_'+rowNum).toggle();
										
					//controlForm.find('.btn-add-item:not(:last)').hide();
				//	controlForm.find('.btn-remove-item').show();
			    	controlForm.find('.btn-add-item').show();
					
					

					//newEntry.find('.abc').html('');
				}
				
				//New Change By Kavya

				else{				
					
					var amt = 0;
					$( '.jvline-amount' ).each(function() { 
						amt = parseFloat( (this.value=='')?0:this.value );
					});				
				
					newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
					newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
					newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
					newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
					newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
					newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
					newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
					newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val(amt);
					newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
					newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
					newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
					newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
					newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
					newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
					newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
					newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
					newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
					newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
					var des = $('input[name="description[]"]').val();
					var acc = $('input[name="account_name[]"]').val();

					

					if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') { 					
						$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option>');				
						$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
						$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
			  	 	} 	
			  	 	getNetTotal();
			  	 	$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
				}		
				// End of changes{Kavya}
			}
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	/* $('.chqdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',
		autoClose: 1
	}); */
	
				
	$(document).on('keyup', '.jvline-amount', function(e) { console.log('a');
		getNetTotal();
	});
	
	$(document).on('change', '.line-type', function(e) {
		
		getNetTotal();
		$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
		$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
	});
	
		
	//new change............
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="account_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	//new change.................
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val(); var vatasgn = $(this).attr("data-vatassign");
		$('#draccount_'+num).val( $(this).attr("data-name") );
		$('#draccountid_'+num).val( $(this).attr("data-id") );
		$('#groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
		$('#vatamt_'+num).val( $(this).attr("data-vat") );
		
		if($(this).attr("data-group")=='PDCR') { 
			$('#chktype').val('PDCR');
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			$('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
			
			/* if( $('#chqdtl_'+num).is(":hidden") )
				$('#chqdtl_'+num).toggle(); */
				
		} else if($(this).attr("data-group")=='PDCI') { 
			$('#chktype').val('PDCI');
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			//New Change[Kavya]
			$('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num, function(e) {				
				$("#descr_"+num).val($("#descr_1").val());
				$("#ref_"+num).val($("#ref_1").val());	
				//$("#amount_"+num).val($("#amount_1").val());
				$("#amount_"+num).val(($('#amount_'+(rowNum-1)).val()));
				if(con)		
				$("#amount_"+num).val((parseFloat($("#amount_"+(rowNum-1)).val()))+(parseFloat($("#amount_"+(rowNum-2)).val())));						
				//$("#amount_"+num).val((parseFloat($("#amount_1").val()))+parseFloat($("#amount_2").val()));
				else
				$("#amount_"+num).val(($('#amount_'+(rowNum-1)).val()));
				//$("#amount_"+num).val($("#amount_1").val());
			});
			
			//End Of Changes[Kavya]
		
		} else {
			//$('#chktype').val('');
			//$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
		  //   if( $('#chkno_'+num).is(":visible") )
				// $('#chkno_'+num).toggle(); 
				
		}
		
		if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER' || $(this).attr("data-group")=='VATIN' || $(this).attr("data-group")=='VATOUT') {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
		} else {// group.id
			var nm = num - 1;
			if(nm > 0)
				var refval = $('#ref_'+nm).val();
			else
				refval = '';
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+refval+'" name="reference[]" class="form-control">');
		}
		
		if( $(this).attr("data-vatassign")=='1') {// group.id
			$('#trndtl_'+num).toggle();
		}
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="party_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#num').val();
		$('#party_'+num).val( $(this).attr("data-name") );
		$('#partyac_'+num).val( $(this).attr("data-id") );
	});
	
	$(document).on('blur', '#voucher_date', function(e) { 
        // Revalidate the date when user change it
        //$('#frmJournal').bootstrapValidator('revalidateField', 'voucher_date');
		$('#frmJournal').bootstrapValidator('revalidateField', 'voucher_no');
	});
	
	$(document).on('keyup', '.jvline-amount', function(e) { 
        // Revalidate the date when user change it
        $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
		$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
	});
	
	/* $(document).on('blur', '.acname', function(e) { 
        // Revalidate the date when user change it
        $('#frmJournal').bootstrapValidator('revalidateField', 'account_name[]');
	});
	
	$(document).on('blur', '.pname', function(e) { 
        // Revalidate the date when user change it
        $('#frmJournal').bootstrapValidator('revalidateField', 'party_name[]');
	}); */
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
		$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
	});

	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		var chqno = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		var bank = $('#bankid_'+curNum+' option:selected').val();
		
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno+'&bank_id='+bank,
			success: function(data) { 
				if(data=='') {
					alert('Cheque no is duplicate!');
					$('#chkno_'+curNum).val('');
				}
			}
		})
	});
	
	$(document).on('click', '.ref-invoice', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		if( $('#groupid_'+curNum).val()=='CUSTOMER') { //customer type.............
		   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   $('#invoiceData').load(url+'/'+curNum, function(result) {
			  $('#myModal').modal({show:true});
		   });
		
			/* var url = "{{ url('sales_invoice/get_invoice/') }}/"+$('#draccountid_'+curNum).val();
			$('#invoiceData').load(url+'/'+curNum, function(result){ 
				$('#myModal').modal({show:true});
			}); */
		} else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
			var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   $('#invoiceData').load(url+'/'+curNum, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   
			/* var url = "{{ url('purchase_invoice/get_invoice/') }}/"+$('#draccountid_'+curNum).val();
			$('#invoiceData').load(url+'/'+curNum, function(result){ //.modal-body item
				$('#myModal').modal({show:true});
			}); */
		} else if( $('#groupid_'+curNum).val()=='VATIN' || $('#groupid_'+curNum).val()=='VATOUT') { //supplier type.........
		   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   $('#invoiceData').load(url+'/'+curNum, function(result) {
			  $('#myModal').modal({show:true});
		   });
		}
	});
	
	$(document).on('click', '.add-invoice', function(e)  { 
	
		var refs = []; var amounts = []; var type = []; var ids = []; var actamt = []; var invid = []; var btype = [];
		$("input[name='tag[]']:checked").each(function() { 
			var res = this.id.split('_');
			var curNum = res[1];
			ids.push($(this).val());
			refs.push( $('#refid_'+curNum).val() );
			amounts.push( $('#lineamnt_'+curNum).val() );
			type.push( $('#trtype_'+curNum).val() ); //actype_
			actamt.push( $('#hidamt_'+curNum).val() );
			invid.push( $('#sinvoiceid_'+curNum).val() );
			btype.push( $('#billtype_'+curNum).val() );
		});
		
		var no = $('#num').val(); //var rowNum;
		var j = 0; //rowNum = parseInt(no);
		var drac = $('#draccount_'+no).val();
		var dracid = $('#draccountid_'+no).val();
		
		$.each(refs,function(i) {
			if(j>0) {
				var controlForm = $('.controls .itemdivPrnt'),
				currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);
				rowNum++;
				newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
				newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
				newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
				newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
				newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
				newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
				newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
				newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
				newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
				newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
				newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
				newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
				newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
				newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
				newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
				newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
				newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
				
			} 
			$('#draccount_'+no).val(drac);
			$('#draccountid_'+no).val(dracid);
			$('#ref_'+no).val( refs[i] );
			$('#amount_'+no).val(amounts[i])
			$('#invid_'+no).val( ids[i] );
			$('#acnttype_'+no).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
			$('#actamt_'+no).val( actamt[i] );
			$('#invoiceid_'+no).val( invid[i] );
			$('#biltyp_'+no).val( btype[i] );
			j++; no++;
		});
		getNetTotal();
	
		$('#frmJournal').bootstrapValidator('revalidateField', 'debit');
		$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
		$('#is_onaccount').val(0);
	});
	
	//CHNG
	$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
});

function getNetTotal() {
	var drLineTotal = 0; var crLineTotal = 0;
	$( '.jvline-amount' ).each(function() {
		var res = this.id.split('_');
		var curNum = res[1];
		if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' ) {
			drLineTotal = drLineTotal + parseFloat( ($(this).val()=='')?0:$(this).val() ); 
		} else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' ) {
			crLineTotal = crLineTotal + parseFloat( (this.value=='')?0:this.value );
		}
		
	});
	var difference = drLineTotal - crLineTotal;
	$("#debit").val(drLineTotal.toFixed(2));
	$("#credit").val(crLineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var amount_fc = parseFloat($('#currency_rate').val()) * amount;
		$('#amount_fc').val(amount_fc.toFixed(2));
	}
	
}
	
var popup;
function getDrAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('account_master/get_all_account/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getTag(e) {
	var res = e.id.split('_');
	var curNum = res[1];
	if( $("#tag_"+curNum).is(':checked') ) {
		var curamount = $("#hidamt_"+curNum).val();
		$("#lineamnt_"+curNum).val(curamount);	
		getNetTotal();
	} else {
		$("#lineamnt_"+curNum).val('');
		getNetTotal();
	}
	
}

$(document).on('click', '.addRecu', function(e) {
	let vno = $('#voucher_no').val();
	let vdate = $('#voucher_date').val();
	let cac = $('#cash_ac').val();
	let cacid = $('#cash_acid').val();
	let ref = $('#refno').val();
	let dec = $('#tenant').val();
	$.ajax({
		url: "{{ url('journal/recurring_add/') }}",
		type: 'get',
		data: 'amount='+amt+'&inst='+inst+'&cac='+cac+'&cacid='+cacid+'&ref='+ref+'&dec='+dec,
		success: function(data) { 
			$('#receipt_add').html(data);
			return true;
		}
	});
});

</script>
@stop
