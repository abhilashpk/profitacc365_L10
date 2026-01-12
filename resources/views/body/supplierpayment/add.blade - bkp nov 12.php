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
                    Add New
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
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
				<?php if(sizeof($vouchers)==0) { ?>
				<div class="alert alert-warning">
					<p>
						Payment voucher is not found! Please create a voucher in Account Settings.
					</p>
				</div>
				<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Supplier Payment 
                            </h3>
                           
						   <div class="pull-right">
							<?php if($printid) { ?>
								@permission('pv-print')
								 <a href="{{ url('supplier_payment/print/'.$printid->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endpermission
							<?php } ?>
							</div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmSupPayment" id="frmSupPayment" action="{{ url('supplier_payment/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="0">
								<input type="hidden" name="is_advNcash" id="is_advNcash">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								<input type="hidden" name="status" value="{{($settings->pv_approval==1)?0:1}}">
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
											<option value="PDCI" <?php if(old('voucher_type')=='PDCI') echo 'selected'; ?>>PDC</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PV. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{$vchrdata['voucher_no']}}">
                                    <div class="col-sm-9">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$vchrdata['voucher_no']}}">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PV. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off"  id="voucher_date" <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?> placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Credit Account</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cr_account" name="cr_account" value="{{(old('cr_account'))?old('cr_account'):$vchrdata['account_name']}}" data-toggle="modal" data-target="#cr_account_modal" autocomplete="off" ><!--data-toggle="modal" data-target="#cr_account_modal"-->
										<input type="hidden" id="cr_account_id" name="cr_account_id" value="{{(old('cr_account_id'))?old('cr_account_id'):$vchrdata['id']}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Reference</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="reference" autocomplete="off" name="reference" value="{{old('reference')}}">
                                    </div>
                                </div>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="description" autocomplete="off" name="description" value="{{old('description')}}">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label <?php if($errors->has('amount')) echo 'form-error';?>">Amount</label>
                                    <div class="col-sm-9">
										<div class="input-group">
											<span class="input-group-addon">Cr<input type="hidden" id="transaction" value="Cr" name="transaction"></span>
											<input type="number" step="any" class="form-control <?php if($errors->has('amount')) echo 'form-error';?>" id="amount" name="amount">
											<span class="input-group-addon">.00</span>
										</div>
                                    </div>
                                </div>
									
								<div id="chequeInput">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('amount')) echo 'form-error';?>">Bank</label>
										<div class="col-sm-9">
											<select id="bank_id" class="form-control select2 <?php if($errors->has('amount')) echo 'form-error';?>" style="width:100%" name="bank_id">
												<option value="">Select Bank...</option>
												@foreach($banks as $bank)
												<option value="{{$bank['id']}}" <?php if(old('bank_id')==$bank['id']) echo 'selected'; ?>>{{$bank['code'].' - '.$bank['name']}}</option>
												@endforeach
											</select>
										</div>
									</div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('amount')) echo 'form-error';?>">Cheque No</label>
										<div class="col-sm-9">
											<input type="text" class="form-control <?php if($errors->has('amount')) echo 'form-error';?>" id="cheque_no" value="{{old('cheque_no')}}" autocomplete="off" name="cheque_no">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label <?php if($errors->has('amount')) echo 'form-error';?>">Cheque Date</label>
										<div class="col-sm-9">
											<input type="text" class="form-control <?php if($errors->has('amount')) echo 'form-error';?>" id="cheque_date" value="{{old('cheque_date')}}" autocomplete="off" name="cheque_date" data-language='en' readonly>
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
                                    <label for="input-text" class="col-sm-3 control-label <?php if($errors->has('supplier_account')) echo 'form-error';?>">Supplier Account</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control <?php if($errors->has('supplier_account')) echo 'form-error';?>" id="supplier_account" name="supplier_account" value="{{old('supplier_account')}}" autocomplete="off" data-toggle="modal" data-target="#supplier_modal">
										<input type="hidden" class="form-control" id="supplier_id" name="supplier_id" value="{{old('supplier_id')}}">
                                    </div>
                                </div>
								
								<?php if($formdata['tr_description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Trn. Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tr_description" autocomplete="off" name="tr_description" placeholder="Description">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="tr_description" id="tr_description">
								<?php } ?>
								
								<?php if($formdata['depositor']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Depositor/Transferer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="depositor" autocomplete="off" name="depositor" placeholder="Depositor/Transferer">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="depositor" id="depositor">
								<?php } ?>
								
								<br/>
								<fieldset>
								<legend><h5>Supplier Debit Transactions</h5></legend>
										<div class="table-responsive item-data" id="drTransactionData">
										</div>
								</fieldset>
								
							<!--	<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Enable Adjustment Entry</label>
                                    <div class="col-sm-9">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="debit_icheck" id="is_debit" name="is_debit" value="1">
                                    </div>
                                </div>-->
								
								<div id="debit_entry">
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Debit Account</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="dr_entry_ac" name="dr_entry_ac" autocomplete="off" placeholder="Debit Account" data-toggle="modal" data-target="#dr_account_modal">
											<input type="hidden" id="dr_entry_ac_id" name="dr_entry_ac_id">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Description</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="dr_entry_desc" name="dr_entry_desc" autocomplete="off" placeholder="Description">
										</div>
									</div>
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Amount</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" id="dr_entry_amount" name="dr_entry_amount" step="any" autocomplete="off" placeholder="0.00">
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
                                        <input type="number" class="form-control" id="on_amount" step="any" autocomplete="off" name="on_amount" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Total Debit</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Total Credit</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00">
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
                                        <a href="{{ url('supplier_payment') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('supplier_payment') }}" class="btn btn-warning">Clear</a>
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
								
								<div id="dr_account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="dr_account_data">
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
				<?php } ?>
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

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } )
$('#cheque_date').datepicker( { autoClose:true } );

"use strict";

$(document).ready(function () {
	var urlvchr = "{{ url('supplier_payment/checkvchrno/') }}"; //CHNG
	$('#onAccount').toggle(); $('#debit_entry').toggle();
    $('#frmSupPayment').bootstrapValidator({
        fields: {
            voucher_type: {
               /*  validators: {
                    notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    }
                } */
            },
			voucher_no: {
                validators: {
                   /*  notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This Voucher No. is already exist!'
                    }
                }
            },
			/* voucher_date: {
                validators: {
                    notEmpty: {
                        message: 'The voucher date is required and cannot be empty!'
                    }
                }
            }, 
			
			 description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            }, */
			cr_account: {
                validators: {
                    notEmpty: {
                        message: 'The debit account is required and cannot be empty!'
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
            },
			dr_entry_ac: {
                validators: {
                    notEmpty: {
                        message: 'The debit entry account is required and cannot be empty!'
                    }
                }
            },
			dr_entry_amount: {
                validators: {
                    notEmpty: {
                        message: 'The debit entry amount is required and cannot be empty!'
                    }
                }
            }
        }
        
    }).on('change', '[name="voucher_type"]', function(e) {
       // $('#frmSupPayment').formValidation('revalidateField', 'cheque_no');
		//$('#frmSupPayment').formValidation('revalidateField', 'cheque_date');
		//$('#frmSupPayment').formValidation('revalidateField', 'bank_id');
    })
	/* .on('reset', function (event) {
        $('#frmSupPayment').data('bootstrapValidator').resetForm();
    }); */

	$(document).on('click','.datepicker--cell-day', function() {
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'cheque_date');
	})
	
	<?php if( (old('voucher_type')=='BANK') || (old('voucher_type')=='PDCI') ) { ?>
		if( $("#chequeInput").is(":hidden") )
			$("#chequeInput").toggle();
	<?php } else { ?>
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
	<?php } ?>
	
	<?php if( (old('supplier_id')!='') || (old('supplier_account')!='') ) { ?>
	   var spid = $('#supplier_id').val();
	   var url = "{{ url('purchase_invoice/get_invoice/') }}/{{old('supplier_id')}}";
	   $('#drTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
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
		$('#drTransactionData').toggle();
		
		var onamt = ($('#on_amount').val()=='')?0:$('#on_amount').val();
		$("#debit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
		
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#onAccount').toggle();
		$('#drTransactionData').toggle();
	});
	
	$(document).on('click','.tag-line-nw',function() {
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	})

	$(document).on('change', '#voucher_type', function(e) {  //$('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
		/*$.get("{{ url('account_master/get_account/') }}/" + vchr, function(data) {
			$('#cr_account').val(data.account_id+' - '+data.account_name);
			$('#cr_account_id').val(data.id);
		}); */
		
		var vchr_id = $('#voucher option:selected').val(); 
		if($('#department_id').length) {
			
			$.get("{{ url('supplier_payment/getvoucher/') }}/" + vchr_id+'/'+vchr+'/'+$('#department_id option:selected').val(), function(data) {
				$('#voucher_no').val(data.voucher_no);
				if(data.id!=null && data.account_name!=null) {
					$('#cr_account').val(data.account_name);
					$('#cr_account_id').val(data.id);
				} else {
					$('#cr_account').val('');
					$('#cr_account_id').val('');
				}
			});
			
		} else {
			$.get("{{ url('supplier_payment/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
				$('#voucher_no').val(data.voucher_no);
				if(data.id!=null && data.account_name!=null) {
					$('#cr_account').val(data.account_name);
					$('#cr_account_id').val(data.id);
				} else {
					$('#cr_account').val('');
					$('#cr_account_id').val('');
				}
			});
		}
		
		if(vchr=='BANK' || vchr=='PDCI') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
				
			/*if(vchr=='PDCI') {
		        $('#frmSupPayment').bootstrapValidator('addField', 'cheque_no', {
                        validators: {
                            notEmpty: {
                                message: 'Cheque No cannot be empty'
                            }
                        }
                   });
                   
                 $('#frmSupPayment').bootstrapValidator('addField', 'cheque_date', {
                        validators: {
                            notEmpty: {
                                message: 'Cheque date cannot be empty'
                            }
                        }
                   });
                   
                   $('#frmSupPayment').bootstrapValidator('addField', 'bank_id', {
                        validators: {
                            notEmpty: {
                                message: 'Bank cannot be empty'
                            }
                        }
                   });

		    } else {
		        
		        $('#frmSupPayment').bootstrapValidator('removeField', 'cheque_no');
		    }*/
		    
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
		}
	});
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		var vchr_type = $('#voucher_type option:selected').val();
		if($('#department_id').length) {
			
			$.get("{{ url('supplier_payment/getvoucher/') }}/" + vchr_id+'/'+vchr_type+'/'+$('#department_id option:selected').val(), function(data) {
				$('#voucher_no').val(data.voucher_no);
				$('#curno').val(data.voucher_no); //CHNG
				if(data.id!=null && data.account_name!=null) {
					$('#cr_account').val(data.account_name);
					$('#cr_account_id').val(data.id);
				} else {
					$('#cr_account').val('');
					$('#cr_account_id').val('');
				}
			});
			
		} else {
			$.get("{{ url('supplier_payment/getvoucher/') }}/" + vchr_id+'/'+vchr_type, function(data) {
				$('#voucher_no').val(data.voucher_no);
				$('#curno').val(data.voucher_no); //CHNG
				if(data.id!=null && data.account_name!=null) {
					$('#cr_account').val(data.account_name);
					$('#cr_account_id').val(data.id);
				} else {
					$('#cr_account').val('');
					$('#cr_account_id').val('');
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
	
	//new change............FEB 15 '19
	$('.debit_icheck').on('ifChecked', function(event){ 
		$('#debit_entry').toggle();
		calculateDiscount();
		// Revalidate the date when user change it
        //$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		//$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
		
	});
	
	$('.debit_icheck').on('ifUnchecked', function(event){ 
		$('#dr_entry_amount').val(0)
		$('#debit_entry').toggle();
		calculateDiscount();
		// Revalidate the date when user change it
        //$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		//$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	
});

$(function(){
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('supplier_payment/getdeptvoucher/') }}/" + dept_id, function(data) { 
			$('#voucher_no').val(data[0].voucher_no);
			$('#cr_account').val(data[0].account_name);
			$('#cr_account_id').val(data[0].id);
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
	$('#supplier_account').click(function() {
		if($('#department_id').length)
			var custurl = "{{ url('purchase_invoice/supplier_datadpt/') }}"+'/'+$('#department_id option:selected').val();
		else
			var custurl = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
		});
	});
	
	/* $(document).on('click', '.supp', function(e) {
		$('#supplier_account').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('purchase_invoice/get_invoice/') }}/"+$(this).attr("data-id");
	   $('#drTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	}); */
	
	
	$(document).on('click', '#supplierData .supp', function(e) {
		$('#supplier_account').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));

		$('#frmSupPayment').bootstrapValidator('revalidateField', 'supplier_account');
		e.preventDefault();
		checkChequeNo();

	   var url = "{{ url('account_enquiry/os_bills/') }}/"+$(this).attr("data-id");
	   $('#drTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
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
	
	
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');

	});
	
	
	//new change............FEB 15 '19
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //calculateDiscount();
		getNetTotal();
		
		
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('keyup', '#amount', function(e) {
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	
	
	$('#cr_account').click(function() {
		var vtype = $('#voucher_type option:selected').val();
		vtype = (vtype=='')?0:vtype;
		var acnturl = "{{ url('account_master/get_account_list/') }}/"+vtype; 
		$('#drAccountData').load(acnturl, function(result) { 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#drAccountData .accountRow', function(e) {
		$('#cr_account').val( $(this).attr("data-code")+" - "+$(this).attr("data-name") );
		$('#cr_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('keyup', '#on_amount', function(e) {
		var onamt = ($('#on_amount').val()=='')?0:$('#on_amount').val();
		$("#debit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
		
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
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
	
	//new change............FEB 14 '19
	
	$(document).on('keyup', '#dr_entry_amount', function(e) {
		calculateDiscount();
		// Revalidate the date when user change it
        $('#frmSupPayment').bootstrapValidator('revalidateField', 'debit');
		$('#frmSupPayment').bootstrapValidator('revalidateField', 'credit');
	});
		
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_entry_ac"]', function(e) {
		var curNum = 1;
		$('#dr_account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#dr_account_data .accountRow', function(e) { 
		$('#dr_entry_ac').val( $(this).attr("data-name") );
		$('#dr_entry_ac_id').val( $(this).attr("data-id") );
	});
	
	
	
});

function getNetTotal1() {
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
	    cramt = parseFloat($('#on_amount').val());
	}
	
	amount = amount + dramt;
	lineTotal = cramt;
	//lineTotal = dramt - cramt;
	var difference = amount - lineTotal;
	$("#debit").val(lineTotal.toFixed(2));
	$("#credit").val(amount.toFixed(2));
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
}

//Updated... Feb 16 '19
function calculateDiscount() {
	
	var discount = parseFloat( ($('#dr_entry_amount').val()!='')?$('#dr_entry_amount').val():0 );
	var dbt = parseFloat( ($('#amount').val()!='')?$('#amount').val():0 );
	var dbtamt = dbt+discount;
	
	$('#credit').val((dbtamt).toFixed(2));
	var dif = dbtamt - parseFloat( $('#debit').val() );
	$('#difference').val(dif.toFixed(2));
	//getNetTotal();
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

function checkChequeNo() {

	var chqno = $('#cheque_no').val();
	var bank = $('#bank_id option:selected').val();
	var cst = $('#supplier_id').val();
	
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno+'&bank_id='+bank+'&ac_id='+cst+'&type=1',
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
