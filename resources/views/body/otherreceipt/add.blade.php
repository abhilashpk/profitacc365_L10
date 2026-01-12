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
                Other Receipt
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
                    <a href="#">Other Receipt</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Receipt 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls">
                            <form class="form-horizontal" role="form" method="POST" name="frmOthrReceipt" id="frmOthrReceipt" action="{{ url('other_receipt/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="">Select Voucher Type...</option>
											<option value="CASH">Cash</option>
											<option value="BANK">Bank</option>
											<option value="PDCR">PDC</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
                                           <option value="">Select Voucher...</option>
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Debit Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="dr_account" name="dr_account" autocomplete="off" data-toggle="modal" data-target="#dr_account_modal">
										<input type="hidden" id="dr_account_id" name="dr_account_id">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference" name="reference">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-addon">Dr<input type="hidden" id="transaction" value="Dr" name="transaction"></span>
											<input type="number" step="any" class="form-control" id="amount" name="amount">
											<span class="input-group-addon">.00</span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                         <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                         <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
                                            <option value="">Select Department...</option>
											@foreach($departments as $department)
											<option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1">
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}">{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount in FC</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="amount_fc" name="amount_fc" readonly>
                                    </div>
                                </div>
								<div id="chequeInput">
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheque No</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_no" name="cheque_no">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheque Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_date" name="cheque_date" data-language='en' readonly>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Bank</label>
										<div class="col-sm-10">
											<select id="bank_id" class="form-control select2" style="width:100%" name="bank_id">
												<option value="">Select Bank...</option>
												@foreach($banks as $bank)
												<option value="{{$bank['id']}}">{{$bank['code'].' - '.$bank['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="customer_account" name="customer_account" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
										<input type="hidden" class="form-control" id="customer_id" name="customer_id">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tr_description" name="tr_description" placeholder="Description">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Depositor/Transferer</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="depositor" name="depositor" placeholder="Depositor/Transferer">
                                    </div>
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Customer Credit Transactions</h5></legend>
										<div class="itemdivPrnt">
											<div class="itemdivChld">							
												
												<div class="form-group">
													<div class="col-sm-3"> <span class="small">Credit Account</span><input type="text" id="craccount_1" name="credit_account[]" class="form-control" placeholder="Credit Account" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" id="craccountid_1" name="cr_account_id[]">
													</div>
													<div class="col-xs-15">
														<div class="col-xs-3">
															<span class="small">Reference</span> <input type="text" id="crref_1" name="cr_reference[]" class="form-control" placeholder="Reference">
														</div>
														<div class="col-xs-3">
															<span class="small">Description</span> <input type="text" id="crdesc_1" name="cr_description[]" class="form-control" placeholder="Description">
														</div>
														<div class="col-xs-2">
															<span class="small">Amount</span> <input type="number" id="cramount_1" step="any" name="cr_amount[]" class="form-control line-amount" placeholder="Amount">
														</div>
														
													</div>	
												</div>
												
												<div class="form-group">
													<div class="col-sm-3"> <span class="small">Job</span> 
													<select id="jobid_1" class="form-control select2 line-job" style="width:100%" name="cr_job_id[]">
														<option value="">Select Job...</option>
														@foreach($jobs as $job)
														<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
														@endforeach
													</select>
													</div>
													<div class="col-xs-15">
														<div class="col-xs-3">
															<span class="small">FC Amount</span> <input type="number" id="amountfc_1" name="cr_amount_fc[]" class="form-control" readonly placeholder="FC Amount">
														</div>
														<div class="col-xs-3">
															<span class="small">FC</span> <input type="text" id="fc_1" name="fc[]" class="form-control" readonly placeholder="FC">
														</div>
														<div class="col-xs-2">
															<span class="small">FC Rate</span> <input type="number" id="currate_1" step="any" readonly name="cr_currency_rate[]" class="form-control" readonly placeholder="FC Rate">
														</div>
														<div class="col-xs-1">
															 <button type="button" class="btn btn-success btn-add-item" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
														</div>
													</div>	
												</div>
												
												<hr/>
											</div>
										</div>
								</fieldset>
								
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
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('other_receipt') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                               
							    <div id="account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="account_data">
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
								
								<div id="dr_account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Debit Account</h4>
											</div>
											<div class="modal-body" id="drAccountData">
												
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

<script>
"use strict";

$(document).ready(function () {

    $('#frmCustReceipt').bootstrapValidator({
        fields: {
            voucher_type: {
                validators: {
                    notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    }
                }
            },
			voucher_date: {
                validators: {
                    notEmpty: {
                        message: 'The voucher date is required and cannot be empty!'
                    }
                }
            },
			dr_account: {
                validators: {
                    notEmpty: {
                        message: 'The debit account is required and cannot be empty!'
                    }
                }
            },
			description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            },
			amount: {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			customer_account: {
                validators: {
                    notEmpty: {
                        message: 'The customer account is required and cannot be empty!'
                    }
                }
            },
			cheque_no: {
                validators: {
					callback: {
                            message: 'The cheque no is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCustReceipt').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
                        }
                    /* notEmpty: {
                        message: 'The cheque no is required and cannot be empty!'
                    } */
                }
            },
			cheque_date: {
                validators: {
                    callback: {
                            message: 'The cheque date is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCustReceipt').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
                        }
                }
            },
			bank_id: {
                validators: {
                    callback: {
                            message: 'The bank is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCustReceipt').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
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
        
    }).on('change', '[name="voucher_type"]', function(e) {
        $('#frmCustReceipt').formValidation('revalidateField', 'cheque_no');
		$('#frmCustReceipt').formValidation('revalidateField', 'cheque_date');
		$('#frmCustReceipt').formValidation('revalidateField', 'bank_id');
    })
	
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
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			getNetTotal();
		});
		
	});
	
	$('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
		$.get("{{ url('account_master/get_account/') }}/" + vchr, function(data) {
			 //$('#dr_account').val(data.account_id+' - '+data.account_name);
			//$('#dr_account_id').val(data.id);
		});
		if(vchr=='BANK' || vchr=='PDCR') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
		}
	});
	
});

$(function(){
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  
  var custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customer_account').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_account').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('sales_invoice/get_invoice/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	});
	
	$(document).on('blur', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		getNetTotal();
	});
	
	$(document).on('blur', '#amount', function(e) {
		getNetTotal();
	});
	
	$('#dr_account').click(function() {
		var vtype = $('#voucher_type option:selected').val();
		vtype = (vtype=='')?0:vtype;
		var acnturl = "{{ url('account_master/get_account_list/') }}/"+vtype; 
		$('#drAccountData').load(acnturl, function(result) { 
			$('#myModal').modal({show:true});
		});
	});
	
		
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		var vchr_type = $('#voucher_type option:selected').val();
		$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr_type, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#dr_account').val(data.account_name);
				$('#dr_account_id').val(data.id);
			} else {
				$('#dr_account').val('');
				$('#dr_account_id').val('');
			}
		});
	});
	
	$(document).on('click', '.accountRow', function(e) {
		$('#dr_account').val( $(this).attr("data-code")+" - "+$(this).attr("data-name") );
		$('#dr_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('blur', '#cheque_no', function(e) {
		var chqno = this.value;
		
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno,
			success: function(data) { 
				if(data=='') {
					alert('Cheque no is duplicate!');
					$('#cheque_no').val('');
				}
			}
		})
	});
	
	var rowNum = 1;
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum); 
			newEntry.find($('input[name="credit_account[]"]')).attr('id', 'craccount_' + rowNum);
			newEntry.find($('input[name="cr_account_id[]"]')).attr('id', 'craccountid_' + rowNum);
			newEntry.find($('input[name="cr_refernce[]"]')).attr('id', 'crref_' + rowNum);
			newEntry.find($('input[name="cr_description[]"]')).attr('id', 'crdesc_' + rowNum);
			newEntry.find($('input[name="cr_amount[]"]')).attr('id', 'cramount_' + rowNum);
			newEntry.find($('input[name="amount_fc[]"]')).attr('id', 'amountfc_' + rowNum);
			newEntry.find($('input[name="fc[]"]')).attr('id', 'fc_' + rowNum);
			newEntry.find($('input[name="currency_rate[]"]')).attr('id', 'currate_' + rowNum);
			newEntry.find('input').val(''); 
			controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		e.preventDefault();
		return false;
	});
	
		
	$(document).on('change', '.submit', function(e) { 
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	var acurl = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="credit_account[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	//new change.................
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#craccount_'+num).val( $(this).attr("data-name") );
		$('#craccountid_'+num).val( $(this).attr("data-id") );
		
	});
	
});

function getNetTotal() {
	var amount = parseFloat( ($('#amount').val()=='') ? 0 : $('#amount').val() );
	var lineTotal = 0;
	$( '.line-amount' ).each(function() {
	  var lineAmount = parseFloat( (this.value=='')?0:this.value );
	  lineTotal = lineTotal + lineAmount;
	  
	  if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var res = this.id.split('_');
		var curNum = res[1];
		var amount_fc = lineAmount * parseFloat( $('#currency_rate').val() );
		$("#amountfc_"+curNum).val(amount_fc.toFixed(2));
		$("#currate_"+curNum).val($('#currency_rate').val());
		$("#fc_"+curNum).val($('#currency_id option:selected').text());
	  }
	});
	var difference = amount - lineTotal;
	$("#debit").val(amount.toFixed(2));
	$("#credit").val(lineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var amount_fc = parseFloat($('#currency_rate').val()) * amount;
		$('#amount_fc').val(amount_fc.toFixed(2));
	}
	
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

var popup;
function getCrAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('sales_invoice/customer_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop
