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
                Customer Receipt
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
                    <a href="#">Customer Receipt</a>
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
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
				<?php if(sizeof($vouchers)==0) { ?>
				<div class="alert alert-warning">
					<p>
						Receipt voucher is not found! Please create a voucher in Account Settings.
					</p>
				</div>
				<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Customer Receipt 
                            </h3>
                           
						   <div class="pull-right">
							<?php if($printid) { ?>
								@can('rv-print')
								 <a href="{{ url('customer_receipt/printgrp/'.$printid->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
							<?php } ?>
							</div>
							
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCustReceipt" id="frmCustReceipt" action="{{ url('customer_receipt/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="from_jv" value="0">
                                <input type="hidden" name="is_advNcash" id="is_advNcash">
                                @if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Department</label>
                                    <div class="col-sm-9">
                                       <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher</label>
                                    <div class="col-sm-9">
                                       <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['vid'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach	
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">CASH/BANK/PDC</label>
                                    <div class="col-sm-9">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="CASH" <?php if(old('voucher_type')=='CASH') echo 'selected'; ?>>Cash</option>
											<option value="BANK" <?php if(old('voucher_type')=='BANK') echo 'selected'; ?>>Bank</option>
											<option value="PDCR" <?php if(old('voucher_type')=='PDCR') echo 'selected'; ?>>PDC</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">RV. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{$vchrdata['voucher_no']}}">
                                    <div class="col-sm-9">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$vchrdata['voucher_no']}}">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">RV. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?>  id="voucher_date" autocomplete="off" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Debit Account</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="dr_account" name="dr_account" value="{{(old('dr_account'))?old('dr_account'):$vchrdata['account_name']}}" autocomplete="off" data-toggle="modal" data-target="#dr_account_modal">
										<input type="hidden" id="dr_account_id" name="dr_account_id" value="{{(old('dr_account_id'))?old('dr_account_id'):$vchrdata['id']}}">
                                    </div>
                                </div>
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Reference</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="reference" name="reference" autocomplete="off" value="{{old('reference')}}">
                                    </div>
                                </div>
                                	<?php } else { ?>
									<input type="hidden" name="reference" id="reference">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="description" name="description" autocomplete="off" value="{{old('description')}}">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label <?php if($errors->has('amount')) echo 'form-error';?>">Amount</label>
                                    <div class="col-sm-9">
										<div class="input-group">
											<span class="input-group-addon">Dr<input type="hidden" id="transaction" value="Dr" name="transaction" ></span>
											<input type="number" step="any" class="form-control <?php if($errors->has('amount')) echo 'form-error';?>" id="amount" name="amount" value="0">
											<span class="input-group-addon">.00</span>
										</div>
                                    </div>
                                </div>
								
								<div id="chequeInput">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('bank_id')) echo 'form-error';?>">Bank</label>
										<div class="col-sm-9">
											<select id="bank_id" class="form-control select2 <?php if($errors->has('bank_id')) echo 'form-error';?>" style="width:100%" name="bank_id">
												<option value="">Select Bank...</option>
												@foreach($banks as $bank)
												<option value="{{$bank['id']}}" <?php if(old('bank_id')==$bank['id']) echo 'selected'; ?>>{{$bank['code'].' - '.$bank['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('cheque_no')) echo 'form-error';?>">Cheque No</label>
										<div class="col-sm-9">
											<input type="text" class="form-control <?php if($errors->has('cheque_no')) echo 'form-error';?>" id="cheque_no" name="cheque_no" autocomplete="off">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('cheque_date')) echo 'form-error';?>">Cheque Date</label>
										<div class="col-sm-9">
											<input type="text" class="form-control <?php if($errors->has('cheque_date')) echo 'form-error';?>" id="cheque_date" name="cheque_date" value="{{old('cheque_date')}}" autocomplete="off" data-language='en' readonly>
										</div>
									</div>
									
								</div>
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Job</label>
                                    <div class="col-sm-9">
                                          <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
										<input type="hidden" name="job_id" id="job_id">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								
								<?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Salesman</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="salesman" id="salesman" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
										<input type="hidden" name="salesman_id" id="salesman_id">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
																
								<?php if($formdata['foreign_currency']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Foreign Currency</label>
									<div class="col-xs-9">
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
											<input type="text" name="currency_rate" id="currency_rate" autocomplete="off" class="form-control" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Amount in FC</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="amount_fc" name="amount_fc" readonly>
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="currency_rate" id="currency_rate">
									<input type="hidden" name="amount_fc" id="amount_fc">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label <?php if($errors->has('customer_account')) echo 'form-error';?>">Customer Account</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control <?php if($errors->has('customer_account')) echo 'form-error';?>" value="{{old('customer_account')}}" id="customer_account" name="customer_account" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
										<input type="hidden" class="form-control" id="customer_id" name="customer_id" value="{{old('customer_id')}}">
                                    </div>
                                </div>
								
								<?php if($formdata['tr_description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Trn. Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tr_description" name="tr_description" autocomplete="off" placeholder="Description">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="tr_description" id="tr_description">
								<?php } ?>
								
								<?php if($formdata['depositor']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Depositor/Transferer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="depositor" name="depositor" autocomplete="off" placeholder="Depositor/Transferer">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="depositor" id="depositor">
								<?php } ?>
								
								<br/>
								<fieldset>
								<legend><h5>Customer Credit Transactions</h5></legend>
										<div class="table-responsive item-data" id="crTransactionData">
										</div>
								</fieldset>
								
								<!--<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Enable Adjustment Entry</label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="credit_icheck" id="is_credit" name="is_credit" value="1">
                                    </div>
                                </div>-->
								
								<div id="credit_entry">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Credit Account</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="cr_entry_ac" name="cr_entry_ac" autocomplete="off" placeholder="Credit Account" data-toggle="modal" data-target="#cr_account_modal">
											<input type="hidden" id="cr_entry_ac_id" name="cr_entry_ac_id">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Description</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="cr_entry_desc" name="cr_entry_desc" autocomplete="off" placeholder="Description">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Amount</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" id="cr_entry_amount" name="cr_entry_amount" step="any" autocomplete="off" placeholder="0.00">
										</div>
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Enable On Account Entry</label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" id="is_onacnt" name="is_onaccount" value="1">
                                    </div>
                                </div>
								
								<div class="form-group" id="onAccount">
                                    <label for="input-text" class="col-sm-3 control-label">Advance Amount</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" step="any" id="on_amount" name="on_amount" autocomplete="off" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Total Debit</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="debit" name="debit" value="{{(old('debit'))}}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Total Credit</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="credit" name="credit" value="{{(old('credit'))}}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Difference</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('customer_receipt') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('customer_receipt') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
                            </form>
							
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
								<div id="salesman_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Salesman</h4>
                                        </div>
                                        <div class="modal-body" id="salesmanData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            
        <div id="job_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Job Master</h4>
					</div>
					<div class="modal-body" id="jobData">
						
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
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="cr_account_data">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script>

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#cheque_date').datepicker( { autoClose:true } );

"use strict";

$(document).ready(function () { 
	$('#onAccount').toggle(); $('#credit_entry').toggle();
	var urlvchr = "{{ url('customer_receipt/checkvchrno/') }}"; //CHNG
    

    $('#frmCustReceipt').bootstrapValidator({
        fields: {
            voucher_type: {
                validators: {
                   /*  notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    } */
                }
            },
			voucher_no: {
                validators: {
                     notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    }, 
					/*remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This Voucher No. is already exist!'
                    }*/
                }
            },
			amount: {
                validators: {
                    notEmpty: {
                        message: 'The debit amount is required and cannot be empty!'
                    }
                }
            },
			customer_account: {
                validators: {
                    notEmpty: {
                        message: 'The customer account is required and cannot be empty!'
                    }
                }
            },/*
			bank_id: {
                validators: {
                    notEmpty: {
                        message: 'Bank name is required and cannot be empty!'
                    }
                }
            },
			
			cheque_date: {
                validators: {
                    notEmpty: {
                        message: 'Cheque date is required and cannot be empty!'
                    }
                }
            },
            cheque_no: {
                validators: {
                    notEmpty: {
                        message: 'Cheque no is required and cannot be empty!'
                    }
                }
            },*/
			cr_entry_ac: {
                validators: {
                    notEmpty: {
                        message: 'The credit enty account is required and cannot be empty!'
                    }
                }
            },
			cr_entry_amount: {
                validators: {
                    notEmpty: {
                        message: 'The credit enty amount is required and cannot be empty!'
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
        /* $('#frmCustReceipt').formValidation('revalidateField', 'cheque_no');
		$('#frmCustReceipt').formValidation('revalidateField', 'cheque_date');
		$('#frmCustReceipt').formValidation('revalidateField', 'bank_id'); */
    })
    

    $(document).on('change', '#voucher_type', function(e) { //$('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
	

		//$.get("{{ url('account_master/get_account/') }}/" + vchr, function(data) {
			 //$('#dr_account').val(data.account_id+' - '+data.account_name);
			//$('#dr_account_id').val(data.id);
		//});
		
		var vchr_id = $('#voucher option:selected').val();
		if($('#department_id').length) {
			
			$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr+'/'+$('#department_id option:selected').val(), function(data) {
				$('#voucher_no').val(data.voucher_no);
				if(data.id!=null && data.account_name!=null) {
					$('#dr_account').val(data.account_name);
					$('#dr_account_id').val(data.id);
				} else {
					$('#dr_account').val('');
					$('#dr_account_id').val('');
				}
			});
			
		} else {
			
			$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
				$('#voucher_no').val(data.voucher_no);
				if(data.id!=null && data.account_name!=null) {
					$('#dr_account').val(data.account_name);
					$('#dr_account_id').val(data.id);
				} else {
					$('#dr_account').val('');
					$('#dr_account_id').val('');
				}
			});
		}
		
		if(vchr=='BANK' || vchr=='PDCR') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
				
		    if(vchr=='PDCR') {
		        $('#frmCustReceipt').bootstrapValidator('addField', 'cheque_no', {
                        validators: {
                            notEmpty: {
                                message: 'Cheque No cannot be empty'
                            }
                        }
                   });
                   
                 $('#frmCustReceipt').bootstrapValidator('addField', 'cheque_date', {
                        validators: {
                            notEmpty: {
                                message: 'Cheque date cannot be empty'
                            }
                        }
                   });
                   
                   $('#frmCustReceipt').bootstrapValidator('addField', 'bank_id', {
                        validators: {
                            notEmpty: {
                                message: 'Bank cannot be empty'
                            }
                        }
                   });

		    } else {
		        
		        $('#frmCustReceipt').bootstrapValidator('removeField', 'cheque_no');
		        //$('#frmCustReceipt').bootstrapValidator('validate');
		    }
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
		}
	});
	
   
    
	/* .on('reset', function (event) {
        $('#frmCustReceipt').data('bootstrapValidator').resetForm();
    }); */
	
	$(document).on('click','.datepicker--cell-day', function() {
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'cheque_date');
	})

	<?php if( (old('voucher_type')=='BANK') || (old('voucher_type')=='PDCR') ) { ?>
		if( $("#chequeInput").is(":hidden") )
			$("#chequeInput").toggle();
	<?php } else { ?>
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
	<?php } ?>
	
	<?php if( (old('customer_id')!='') || (old('customer_account')!='') ) { ?>
	   var url = "{{ url('sales_invoice/get_invoice/') }}/{{old('customer_id')}}";
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	<?php } ?> 
	
	//$("#chequeInput").toggle();
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
	
	
	$('.onacnt_icheck').on('ifChecked', function(event){ 
		$('#onAccount').toggle();
		$('#crTransactionData').toggle();
		
		var onamt = ($('#on_amount').val()=='')?0:$('#on_amount').val();
		$("#credit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
		
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
		
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#onAccount').toggle();
		$('#crTransactionData').toggle();
	});
	
	
	var joburl = "{{ url('jobmaster/jobb_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobbRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var slurl = "{{ url('sales_invoice/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(slurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	
	//new change............FEB 15 '19
	$('.credit_icheck').on('ifChecked', function(event){ 
		$('#credit_entry').toggle();
		calculateDiscount();
		// Revalidate the date when user change it
        //$('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		//$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
		
	});
	
	$('.credit_icheck').on('ifUnchecked', function(event){ 
		$('#cr_entry_amount').val(0)
		$('#credit_entry').toggle();
		calculateDiscount();
		// Revalidate the date when user change it
        //$('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		//$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
});

$(function(){
    
    
	
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  
   $('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('customer_receipt/getdeptvoucher/') }}/" + dept_id, function(data) { 
			$('#voucher_no').val(data[0].voucher_no);
			$('#dr_account').val(data[0].account_name);
			$('#dr_account_id').val(data[0].id);
			$('#voucher').find('option').remove().end();
			 $.each(data, function(key, value) {  
				$('#voucher').find('option').end()
						.append($("<option></option>")
						.attr("value",value.voucher_id)
						.text(value.voucher_name)); 
			});
		});
	});
	
	var custurl;
	$('#customer_account').click(function() {
		if($('#department_id').length)
			custurl = "{{ url('sales_invoice/customer_datadpt/') }}"+'/'+$('#department_id option:selected').val();
		else
			custurl = "{{ url('sales_order/customer_data/') }}";
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
		});
	});
		
	/* $(document).on('click', '.custRow', function(e) {
		$('#customer_account').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('sales_invoice/get_invoice/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	}); */
	
	$(document).on('click', '.custRow', function(e) {
		e.preventDefault();
		$('#customer_account').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'customer_account');
		
		checkChequeNo();

	   var url = "{{ url('account_enquiry/os_bills/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	});
	
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	//new change............FEB 15 '19
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //calculateDiscount();
		getNetTotal();
		
		
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('keyup', '#amount', function(e) {
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	
	$('#dr_account').click(function() {
		var vtype = $('#voucher_type option:selected').val();
		vtype = (vtype=='')?0:vtype;
		var acnturl = "{{ url('account_master/get_account_list/') }}/"+vtype; 
		$('#drAccountData').load(acnturl, function(result) { 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#drAccountData .accountRow', function(e) {
		$('#dr_account').val( $(this).attr("data-code")+" - "+$(this).attr("data-name") );
		$('#dr_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		var vchr_type = $('#voucher_type option:selected').val();
		
		if($('#department_id').length) {
			
			$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr_type+'/'+$('#department_id option:selected').val(), function(data) {
				$('#voucher_no').val(data.voucher_no);
				$('#curno').val(data.voucher_no); //CHNG
				if(data.id!=null && data.account_name!=null) {
					$('#dr_account').val(data.account_name);
					$('#dr_account_id').val(data.id);
				} else {
					$('#dr_account').val('');
					$('#dr_account_id').val('');
				}
			});
			
		} else {
		
			$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr_type, function(data) {
				$('#voucher_no').val(data.voucher_no);
				$('#curno').val(data.voucher_no); //CHNG
				if(data.id!=null && data.account_name!=null) {
					$('#dr_account').val(data.account_name);
					$('#dr_account_id').val(data.id);
				} else {
					$('#dr_account').val('');
					$('#dr_account_id').val('');
				}
			});
		}
	});
	
	$(document).on('blur', '#cheque_no', function(e) {
		checkChequeNo();
	});
	
	$(document).on('change', '#bank_id', function(e) {
		checkChequeNo();
	});
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('keyup', '#on_amount', function(e) {
		var onamt = ($('#on_amount').val()=='')?0:$('#on_amount').val();
		$("#credit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
		
		//$('#frmCustReceipt').formValidation('revalidateField', 'debit');
		//$('#frmCustReceipt').formValidation('revalidateField', 'credit');
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
	
	//CHNG
	$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	
	//new change............FEB 14 '19
	
	$(document).on('keyup', '#cr_entry_amount', function(e) {
		calculateDiscount();
		// Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	});
		
	$(document).on('click','.tag-line-nw',function() {
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
		$('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
	})

	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="cr_entry_ac"]', function(e) {
		var curNum = 1;
		$('#cr_account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#cr_account_data .accountRow', function(e) { 
		$('#cr_entry_ac').val( $(this).attr("data-name") );
		$('#cr_entry_ac_id').val( $(this).attr("data-id") );
	});
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
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
	
});

//Updated... Feb 14 '19
function getNetTotal() {
	var amount = parseFloat( ($('#amount').val()=='') ? 0 : $('#amount').val() );
	var lineTotal = 0; var cramt = 0; var dramt = 0; var val;
	$( '.line-amount' ).each(function() { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		/* val = parseFloat( (this.value=='')?0:this.value ); console.log('TY '+$('#actype_'+curNum).val()+' '+val);
		lineTotal = lineTotal + val;  */
		if($('#actype_'+curNum).val()=='Cr') { 
			val = parseFloat( (this.value=='')?0:this.value );
			cramt = cramt + val;
			//lineTotal = lineTotal + val;
		} else {
			val = parseFloat( (this.value=='')?0:this.value );
			dramt = dramt + val; 
			//lineTotal = lineTotal + val; 
		}
	}); 
	
	if( $("#onAccount").is(":visible") ) { 
	    dramt = parseFloat($('#on_amount').val());
	}
	
	amount = amount + cramt;
	lineTotal = dramt;
	//lineTotal = dramt - cramt;
	var difference = amount - lineTotal;
	$("#debit").val(amount.toFixed(2));
	$("#credit").val(lineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var amount_fc = parseFloat($('#currency_rate').val()) * amount;
		$('#amount_fc').val(amount_fc.toFixed(2));
	}
	
	var datAdv = false;
	$('.tag-line-nw:checked').each(function() {
        //console.log('adv '+dataAdv);
        if($(this).data('adv')==1)
            datAdv = true;
    });
    if(datAdv==true)
         $('#is_advNcash').val(1);
    else
         $('#is_advNcash').val('');


	//$('.credit_icheck').on('ifChecked', function(event){ 
		//calculateDiscount();
	//});
}

//Updated... Feb 16 '19
function calculateDiscount() {
	
	var discount = parseFloat( ($('#cr_entry_amount').val()!='')?$('#cr_entry_amount').val():0 );
	var dbt = parseFloat( ($('#amount').val()!='')?$('#amount').val():0 );
	var dbtamt = dbt+discount;
	
	$('#debit').val((dbtamt).toFixed(2));
	var dif = dbtamt - parseFloat( $('#credit').val() );
	$('#difference').val(dif.toFixed(2));
	//getNetTotal();
}

function getTag(e) { 
	
	var res = e.id.split('_');
	var curNum = res[1];
	if( $("#tag_"+curNum).is(':checked') ) {
		var curamount = $("#hidamt_"+curNum).val();
		$("#lineamnt_"+curNum).val(curamount);	

		$('input[type="checkbox"][data-adv="1"]').change(function() {
			if (this.checked) {
			// Uncheck and disable all other data-adv="1" checkboxes
			$('input[type="checkbox"][data-adv="1"]').not(this)
				.prop('checked', false)
				.prop('disabled', true);
			} else {
			// Re-enable all data-adv="1" checkboxes
			$('input[type="checkbox"][data-adv="1"]')
				.prop('disabled', false)
				.prop('checked', false);
			}
		});

		getNetTotal();
	} else {
		$("#lineamnt_"+curNum).val('');
		getNetTotal();
	}
}


function checkChequeNo() {

	var chqno = $('#cheque_no').val();
	var bank = $('#bank_id option:selected').val();
	var cst = $('#customer_id').val();
	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+chqno+'&bank_id='+bank+'&ac_id='+cst,
		success: function(data) { 
			if(data=='') {
				alert('Cheque no is duplicate!');
				$('#cheque_no').val('');
			}
		}
	})
}

</script>
@stop
