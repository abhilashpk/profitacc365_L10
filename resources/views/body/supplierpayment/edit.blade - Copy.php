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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Supplier Payment
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
                    <a href="#">Supplier Payment</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Supplier Payment 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmSupPayment" id="frmSupPayment" action="{{ url('supplier_payment/update/'.$crrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="0">
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php if($crrow->voucher_no==$voucher->id) echo 'selected'; ?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="CASH" <?php if($crrow->voucher_type=='CASH') echo 'selected'; ?>>Cash</option>
											<option value="BANK" <?php if($crrow->voucher_type=='BANK') echo 'selected'; ?>>Bank</option>
											<option value="PDCR" <?php if($crrow->voucher_type=='PDCR') echo 'selected'; ?>>PDC</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{ $crrow->voucher_no }}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" value="{{ date('d-m-Y', strtotime($crrow->voucher_date)) }}" name="voucher_date" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								<input type="hidden" name="pventry_id[]" value="{{ $invoicerow[0]->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Credit Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="cr_account" value="{{ $invoicerow[0]->master_name }}" name="cr_account" autocomplete="off" data-toggle="modal" data-target="#cr_account_modal">
										<input type="hidden" id="cr_account_id" name="cr_account_id" value="{{ $invoicerow[0]->account_id }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference" autocomplete="off" value="{{ $invoicerow[0]->reference }}" name="reference">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" autocomplete="off" value="{{ $invoicerow[0]->description }}" name="description">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-addon">Cr<input type="hidden" id="transaction" value="Cr" name="transaction"></span>
											<input type="number" step="any" class="form-control" id="amount" name="amount" value="{{$crrow->debit}}">
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
											<option value="{{ $job['id'] }}" <?php if($invoicerow[0]->job_id==$job['id']) echo 'selected';?>>{{ $job['name'] }}</option>
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
											<option value="{{ $department['id'] }}" <?php if($invoicerow[0]->department_id==$department['id']) echo 'selected';?>>{{ $department['name'] }}</option>
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
												<option value="{{$curr['id']}}" <?php if($invoicerow[0]->currency_id==$curr['id']) echo 'selected';?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" autocomplete="off" class="form-control" value="{{ $invoicerow[0]->currency_rate }}" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount in FC</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="amount_fc" name="amount_fc" readonly value="{{ $invoicerow[0]->amount_fc }}">
                                    </div>
                                </div>
								<div id="chequeInput">
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheque No</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_no" autocomplete="off" name="cheque_no" value="{{ $invoicerow[0]->cheque_no }}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Cheque Date</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="cheque_date" autocomplete="off" name="cheque_date" data-language='en' readonly value="<?php echo ($invoicerow[0]->cheque_date!='0000-00-00')?date('d-m-Y', strtotime($invoicerow[0]->cheque_date)):'';?>">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Bank</label>
										<div class="col-sm-10">
											<select id="bank_id" class="form-control select2" style="width:100%" name="bank_id">
												<option value="">Select Bank...</option>
												@foreach($banks as $bank)
												<option value="{{$bank['id']}}" <?php if($invoicerow[0]->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
								<input type="hidden" name="pventry_id[]" value="{{ $invoicerow[1]->id }}">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="supplier_account" value="{{$invoicerow[1]->master_name}}" name="supplier_account" autocomplete="off" data-toggle="modal" data-target="#supplier_modal">
										<input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="{{ $invoicerow[1]->account_id }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="tr_description" autocomplete="off" name="tr_description" value="{{ $crrow->tr_description }}" placeholder="Description">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Depositor/Transferer</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="depositor" name="depositor" autocomplete="off" value="{{ $crrow->depositor }}" placeholder="Depositor/Transferer">
                                    </div>
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Supplier Debit Transactions</h5></legend>
										<div class="table-responsive item-data" id="drTransactionData">
											<div class="col-xs-15">
												<table class="table table-bordered table-hover">
													<thead>
													<tr>
														<th>Invoice Date</th>
														<th>Reference No</th>
														<th>Tag</th>
														<th>Type</th>
														<th>Assign Amount</th>
														<th>Balance</th>
														<th>Invoice Amount</th>
														<th>FC Amount</th>
														<th>FC</th>
														<th>FC Rate</th>
														<th>Description <input type="hidden" name="remove_item" id="remitem"/></th>
													</tr>
													</thead>
													<tbody>
													{{--*/ $i = 0; /*--}}
													@foreach($invoices as $row)
													{{--*/ $i++; /*--}}
													<?php 
														$isid = false; $asgamount = ''; $id='';
														foreach($invoicetrrow as $inv) {
															if($inv->purchase_invoice_id==$row->id && $inv->bill_type=='PI') {
																$isid = true;
																$asgamount = $inv->assign_amount;
																$id = $inv->id;
															}
														}
													?>
													<tr>
														<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }} <input type="hidden" name="id[]" value="{{ $id }}"></td>
														<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
														<td><input type="checkbox" id="tag_{{$i}}" <?php if($isid) echo 'checked'; ?> name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
														<td>Dr</td>
														<td><input type="number" value="{{$asgamount}}" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
														<?php 
															//$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
															$amount = $row->balance_amount;
														?>
														<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
														<input type="hidden" name="purchase_invoice_id[]" value="{{ $row->id }}">
														<input type="hidden" name="bill_type[]" value="PI">
														<td>{{ number_format($amount,2) }}</td>
														<td>{{ number_format($row->net_amount,2) }}</td>
														<td>{{ number_format($row->net_amount_fc,2) }}</td>
														<td>{{ $fc=($row->is_fc==1)?'Yes':'No' }}</td>
														<td>{{ number_format($row->currency_rate,2) }}</td>
														<td>{{ $row->description }}</td>
													</tr>
													@endforeach
													
													{{--*/ $i = count($invoices); /*--}}
													@foreach($openbalances as $row)
													{{--*/ $i++; /*--}}
													<?php 
														$isid = false; $asgamount = $id = '';
														foreach($invoicetrrow as $inv) {
															if($inv->purchase_invoice_id==$row->id && $inv->bill_type=='OB') {
																$isid = true;
																$asgamount = $inv->assign_amount;
																$id = $inv->id;
															}
														}
													?>
													<tr>
														<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}
														<input type="hidden" name="id[]" value="{{ $id }}">
														</td>
														<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
														<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" <?php if($isid) echo 'checked'; ?> class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
														<td>Dr</td>
														<td><input type="number" value="{{$asgamount}}" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
														<?php 
															//$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
															$amount = $row->balance_amount;
														?>
														<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
														<input type="hidden" name="purchase_invoice_id[]" value="{{ $row->id }}">
														<input type="hidden" name="bill_type[]" value="OB">
														<td>{{ number_format($amount,2) }}</td>
														<td>{{ number_format($row->net_amount,2) }}</td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>
													@endforeach
													</tbody>
												</table>
											</div>
										</div>
								</fieldset>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">On Account</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" id="is_onacnt" <?php if($invoicerow[1]->is_onaccount==1) echo 'checked';?> name="is_onaccount" value="1">
                                    </div>
                                </div>
								
								<div class="form-group" id="onAccount">
                                    <label for="input-text" class="col-sm-2 control-label">Advance Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="on_amount" name="on_amount" autocomplete="off" value="{{$invoicerow[1]->amount}}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" value="{{ number_format($crrow->debit,2) }}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly value="{{ number_format($crrow->credit,2) }}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" value="{{ number_format($crrow->difference,2) }}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('supplier_payment') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                               
								<div id="supplier_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Supplier</h4>
											</div>
											<div class="modal-body" id="supplierData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> 
								
								<div id="cr_account_modal" class="modal fade animated" role="dialog">
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
<script>
"use strict";

$(document).ready(function () {
	
	<?php if($invoicerow[1]->is_onaccount==1) { ?>
	
		if( $("#onAccount").is(":hidden") )
			$('#onAccount').toggle();
		
		if( $("#drTransactionData").is(":visible") )
			$('#drTransactionData').toggle();
	
	<?php } else { ?>
	
		if( $("#onAccount").is(":visible") )
			$('#onAccount').toggle();
		
		if( $("#drTransactionData").is(":hidden") )
			$('#drTransactionData').toggle();
		
	<?php } ?>
	
    $('#frmSupPayment').bootstrapValidator({
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
			cr_account: {
                validators: {
                    notEmpty: {
                        message: 'The debit account is required and cannot be empty!'
                    }
                }
            },
			/* description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            }, */
			amount: {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			supplier_account: {
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
                                var voucher_type = $('#frmSupPayment').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCI') ? true : (value !== '');
                            }
                        }
                }
            },
			cheque_date: {
                validators: {
                    callback: {
                            message: 'The cheque date is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmSupPayment').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCI') ? true : (value !== '');
                            }
                        }
                }
            },
			bank_id: {
                validators: {
                    callback: {
                            message: 'The bank is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmSupPayment').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCI') ? true : (value !== '');
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
        $('#frmSupPayment').formValidation('revalidateField', 'cheque_no');
		$('#frmSupPayment').formValidation('revalidateField', 'cheque_date');
		$('#frmSupPayment').formValidation('revalidateField', 'bank_id');
    }).on('blur', '.line-amount', function(e) { 
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	}).on('click', '#frmSupPayment', function(e) { 
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	/* .on('reset', function (event) {
        $('#frmSupPayment').data('bootstrapValidator').resetForm();
    }); */
	
	<?php if($crrow->voucher_type=='CASH') { ?>
		$("#chequeInput").toggle();
	<?php } ?>
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
	
	$('.onacnt_icheck').on('ifChecked', function(event){ 
		$('#onAccount').toggle();
		$('#drTransactionData').toggle();
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#onAccount').toggle();
		$('#drTransactionData').toggle();
	});
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			getNetTotal();
		});
		
	});
	
	/* $('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
		
		if(vchr=='BANK' || vchr=='PDCI') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
		}
	}); */
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		var vchr_type = $('#voucher_type option:selected').val();
		$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr_type, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#cr_account').val(data.account_name);
				$('#cr_account_id').val(data.id);
			} else {
				$('#cr_account').val('');
				$('#cr_account_id').val('');
			}
		});
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
	
	//remove check box items.......
	$(document).on('click', '.tag-line', function(e) { 
		var remitem = $('#remitem').val(); var ids;
	   if($(this).is(":not(:checked)")) {
		  //alert(this.value);
		  ids = (remitem=='')?this.value:remitem+','+this.value;
		  $('#remitem').val(ids);
	   }
		
	});
	
});

$(function(){
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  
  var custurl = "{{ url('purchase_invoice/supplier_data/') }}";
	$('#supplier_account').click(function() {
		$('#supplierData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.supp', function(e) {
		$('#supplier_account').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('purchase_invoice/get_invoice/') }}/"+$(this).attr("data-id");
	   $('#drTransactionData').load(url, function(result) {
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
	
	
	$('#cr_account').click(function() {
		var vtype = $('#voucher_type option:selected').val();
		vtype = (vtype=='')?0:vtype;
		var acnturl = "{{ url('account_master/get_account_list/') }}/"+vtype; 
		$('#drAccountData').load(acnturl, function(result) { 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRow', function(e) {
		$('#cr_account').val( $(this).attr("data-code")+" - "+$(this).attr("data-name") );
		$('#cr_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('blur', '#on_amount', function(e) { 
		var onamt = $('#on_amount').val();
		$("#debit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
	});
	
	$('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
		/*$.get("{{ url('account_master/get_account/') }}/" + vchr, function(data) {
			$('#cr_account').val(data.account_id+' - '+data.account_name);
			$('#cr_account_id').val(data.id);
		}); */
		
		var vchr_id = $('#voucher option:selected').val(); 
		$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#cr_account').val(data.account_name);
				$('#cr_account_id').val(data.id);
			} else {
				$('#cr_account').val('');
				$('#cr_account_id').val('');
			}
		});
		
		if(vchr=='BANK' || vchr=='PDCI') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
		}
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
	var amount = parseFloat( ($('#amount').val()=='') ? 0 : $('#amount').val() );
	var lineTotal = 0;
	$( '.line-amount' ).each(function() {
	  lineTotal = lineTotal + parseFloat( (this.value=='')?0:this.value );
	});
	var difference = lineTotal - amount;
	$("#credit").val(amount.toFixed(2));
	$("#debit").val(lineTotal.toFixed(2));
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

</script>
@stop
