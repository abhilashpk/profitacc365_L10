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
								@can('jv-print')
								<form class="form-horizontal" role="form" method="GET" target="_blank" name="frmItem" id="frmItem" action="{{ url('journal/getvoucherprint') }}">
								
								<div class="form-group">
						  
							<div class="col-xs-4">
									<select id="voucher_typeprint" class="form-control select2" style="width:100%" name="voucher_typeprint" required>
    									
    									<option value="16">JV - Journal Voucher</option>
									</select> 
										</div>
										<div class="col-xs-3">
                                            <input type="text" class="form-control pull-right" name="voucherprnt_no" placeholder="voucher no" autocomplete="off"  id="voucherprnt_no"   />
										</div>
										
										<div class="col-xs-3">
                                            <button type="submit" class="btn btn-info btn-sm" > <span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span></button></div>
										</div>
                                
								</form>
								 
								@endcan
							
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('journal/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="1">
								<input type="hidden" name="status" id="status">
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="16">JV - Journal Voucher</option>
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
									<input type="hidden" name="curno" id="curno" value="{{$vchrdata['voucher_no']}}">
									<input type="hidden" value="{{$voucher->prefix}}" name="prefix">
									<input type="hidden" value="{{$vchrdata['vno']}}" name="vno">
									<input type="hidden" value="{{$voucher->is_prefix}}" name="is_prefix">
									<div class="col-sm-10">
										<div class="input-group">
										<input type="text" class="form-control" id="voucher_no" placeholder="{{$vchrdata['voucher_no']}}" readonly name="voucher_no">
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
														<input type="hidden" name="group_id[]" id="groupid_1" data-acct-group="" data-vat-rate="" data-vat-flag="0">
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
														<input type="hidden" name="job_id[]" id="jobid_1" >
														<input type="text" id="jobcod_1" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														
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
															<div class="col-xs-2">
																<span class="small">Bank</span> 
																<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																	<option value="">Select Bank...</option>
																	@foreach($banks as $bank)
																	<option value="{{$bank['id']}}">{{$bank['code']}}</option>
																	@endforeach
																</select>
															</div>
															<div class="col-sm-2"> 
																<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
															</div>
															
															<div class="col-xs-2">
																<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
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
							<!--	<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Cheque Datails</label>
                                    <div class="col-sm-1">
										<label class="radio-inline iradio"></label>
											<input type="checkbox" class="debit_icheck bank-chk" id="is_debit" name="is_debit" value="1">
                                    </div>
									<span class="bnk-info">
                                    <label for="input-text" class="col-sm-1 control-label">Cheque No</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="chkno" name="chq_no">
                                    </div>
									<label for="input-text" class="col-sm-2 control-label">Cheque Date</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="chkdt" name="chq_date" data-language='en'>
                                    </div>
									</span>
                                </div>-->
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Enable On Account Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" id="is_onacnt" name="is_onaccount" value="1">
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
								
								<div class="form-group rcEntry">
                                    <label for="input-text" class="col-sm-2 control-label">Recurring Period</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="rcperiod" name="rcperiod">
                                    </div>
									<div class="col-sm-1">
										<button type="button" class="btn btn-primary addRecu">Add</button>
									</div>
                                </div>
								
								<div class="toRc"></div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<div class="modal fade" id="journalConfirmModal" tabindex="-1" role="dialog" aria-labelledby="journalConfirmLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary" style="color:#fff;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="journalConfirmLabel"><i class="fa fa-check-circle"></i> Ready to submit?</h4>
            </div>
            <div class="modal-body">
                <p class="m-b-15">Please review the summary below before posting this journal voucher.</p>
                <ul class="list-group m-b-15">
                    <li class="list-group-item">
                        <span class="text-muted" style="color: blue;">Voucher No.</span>
                        <span class="pull-right" id="confirmVoucherNo">-</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted" style="color: blue;">Voucher Date</span>
                        <span class="pull-right" id="confirmVoucherDate">-</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted" style="color: blue;">Total Debit</span>
                        <span class="pull-right text-success" id="confirmDebit">0.00</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted" style="color: blue;">Total Credit</span>
                        <span class="pull-right text-success" id="confirmCredit">0.00</span>
                    </li>
                    <li class="list-group-item" id="confirmDifferenceRow">
                        <span class="text-muted" style="color: blue;">Difference</span>
                        <span class="pull-right text-danger" id="confirmDifference">0.00</span>
                    </li>
                    <li class="list-group-item">
                        <span class="text-muted" style="color: blue;">Line Count</span>
                        <span class="pull-right" id="confirmLineCount">0</span>
                    </li>
                </ul>
                <div class="well well-sm">
                    <strong>Line Preview</strong>
                    <div class="table-responsive m-t-10">
                        <table class="table table-condensed table-striped" id="voucherConfirmLines">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th class="text-right">Amount</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="no-data">
                                    <td colspan="5" class="text-muted text-center">No lines captured yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small m-b-0" id="confirmExtraLines" style="display:none;"></p>
                </div>
                <div class="alert alert-warning m-b-0" id="confirmWarning" style="display:none;">
                    <i class="fa fa-exclamation-triangle"></i> Debit and Credit totals must match before posting.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-pencil"></i> Keep Editing</button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn">
                    <i class="fa fa-paper-plane"></i> Looks Good, Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#voucher_date').data('datepicker').selectDate(new Date());
$('.bnk-info').hide();
$('#chkdt').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
"use strict";

$(document).ready(function () {
	window.journalSubmitting = false;
	var $confirmModal = $('#journalConfirmModal');
	var $confirmBtn = $('#confirmSubmitBtn');
	var confirmBtnDefaultHtml = $confirmBtn.html();
	var confirmBtnProcessingHtml = '<i class="fa fa-spinner fa-spin"></i> Posting...';
	function formatAmount(val){
		var num = parseFloat(val);
		if(isNaN(num)){ return '0.00'; }
		return num.toFixed(2);
	}
	function safeText(val){
		if(!val){ return '-'; }
		var txt = $.trim(val);
		return txt === '' ? '-' : txt;
	}
	function populateJournalConfirmSummary(){
		var voucherNo = $('#voucher_no').val();
		var voucherDate = $('#voucher_date').val();
		var debit = $('#debit').val();
		var credit = $('#credit').val();
		var difference = $('#difference').val();
		var lineCount = $('.itemdivPrnt .itemdivChld').length;
		$('#confirmVoucherNo').text(safeText(voucherNo));
		$('#confirmVoucherDate').text(safeText(voucherDate));
		$('#confirmDebit').text(formatAmount(debit));
		$('#confirmCredit').text(formatAmount(credit));
		var diffNum = parseFloat(difference) || 0;
		$('#confirmDifference').text(formatAmount(diffNum));
		$('#confirmDifferenceRow').toggle(Math.abs(diffNum) > 0.009);
		$('#confirmWarning').toggle(Math.abs(diffNum) > 0.009);
		$('#confirmLineCount').text(lineCount);
		var $tbody = $('#voucherConfirmLines tbody');
		$tbody.empty();
		var maxPreview = 3;
		$('.itemdivPrnt .itemdivChld').each(function(index){
			if(index >= maxPreview){ return false; }
			var $row = $(this);
			var accountName = $row.find('input[name="account_name[]"]').val();
			var reference = $row.find('input[name="reference[]"]').val();
			var amountField = $row.find('input[name="line_amount[]"]');
			var idAttr = amountField.attr('id');
			var idx = idAttr ? parseInt(idAttr.split('_')[1], 10) : (index + 1);
			var typeVal = $('#acnttype_'+idx).val() || '-';
			var amountVal = $('#amount_'+idx).val();
			var $tr = $('<tr></tr>');
			$('<td></td>').text(index + 1).appendTo($tr);
			$('<td></td>').text(safeText(accountName)).appendTo($tr);
			$('<td></td>').text(typeVal || '-').appendTo($tr);
			$('<td class="text-right"></td>').text(formatAmount(amountVal)).appendTo($tr);
			$('<td></td>').text(safeText(reference)).appendTo($tr);
			$tbody.append($tr);
		});
		if($tbody.children().length === 0){
			var $empty = $('<tr class="no-data"></tr>');
			$('<td colspan="5" class="text-muted text-center">No lines captured yet.</td>').appendTo($empty);
			$tbody.append($empty);
		}
		var extra = lineCount - Math.min(lineCount, maxPreview);
		if(extra > 0){
			$('#confirmExtraLines').text(extra + ' additional line(s) not shown in this preview.').show();
		} else {
			$('#confirmExtraLines').hide().text('');
		}
	}
	window.openJournalConfirmModal = function(form){
		if(!$confirmBtn.length){
			HTMLFormElement.prototype.submit.call(form);
			return;
		}
		populateJournalConfirmSummary();
		$confirmBtn.prop('disabled', false).removeClass('disabled').html(confirmBtnDefaultHtml);
		$confirmBtn.off('click').on('click', function(){
			var $btn = $(this);
			$btn.prop('disabled', true).addClass('disabled').html(confirmBtnProcessingHtml);
			window.journalSubmitting = true;
			$confirmModal.modal('hide');
			setTimeout(function(){
				if(form){
					HTMLFormElement.prototype.submit.call(form);
				}
				window.journalSubmitting = false;
				$confirmBtn.prop('disabled', false).removeClass('disabled').html(confirmBtnDefaultHtml);
			}, 150);
		});
		$confirmModal.modal({ backdrop: 'static', keyboard: false });
	};
	$confirmModal.on('hidden.bs.modal', function(){
		if(!window.journalSubmitting){
			$confirmBtn.prop('disabled', false).removeClass('disabled').html(confirmBtnDefaultHtml);
		}
	});

    $('#trndtl_1').hide();
        @if($settings->pv_approval==1) 
			if(vchr_id==10) {
				$('#status').val(0);
			} else
				$('#status').val(1);
		@else
		    $('#status').val(1);
		@endif
	
	var trnurl = "{{ url('journal/set_transactions/') }}";
	$('.btn-remove-item').hide(); 

	$('.bank-chk').on('ifChecked', function(event){ 
		$('.bnk-info').show();
	});
	
	$('.bank-chk').on('ifUnchecked', function(event){ 
		$('.bnk-info').hide();
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
	
		@if($settings->pv_approval==1) 
			if(vchr_id==10) {
				$('#status').val(0);
			} else
				$('#status').val(1);
		@else
		    $('#status').val(1);
		@endif
		
	});

	function baseGroupFor(idx){
		var $field = $('#groupid_'+idx);
		var grp = ($field.attr('data-acct-group') || $field.attr('data-group') || '').trim();
		if(!grp){
			grp = ($field.val() || '').trim();
		}
		return grp.toUpperCase();
	}
	function vatRateFor(idx){
		var rate = parseFloat($('#vatamt_'+idx).val());
		if(!isFinite(rate)){
			var attrRate = parseFloat($('#groupid_'+idx).attr('data-vat-rate'));
			rate = isFinite(attrRate) ? attrRate : 0;
		}
		return rate;
	}
	function vatFlagFor(idx){
		var val = ($('#groupid_'+idx).val() || '').toString().trim();
		return val === '1';
	}
	function setGroupMeta(idx, baseGroup, vatRate, vatFlag){
		var $field = $('#groupid_'+idx);
		if(!$field.length){ return; }
		if(typeof baseGroup !== 'undefined'){
			$field.attr('data-acct-group', (baseGroup || '').toUpperCase());
		}
		if(typeof vatRate !== 'undefined'){
			$field.attr('data-vat-rate', vatRate);
		}
		if(typeof vatFlag !== 'undefined'){
			var flagVal = (vatFlag === true || vatFlag === '1' || vatFlag === 1) ? '1' : '0';
			$field.attr('data-vat-flag', flagVal);
		}
	}
		
$(function(){
		
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  var rowNum = 1;
  
  $(document).on('click', '.btn-add-item', function(e)  { 

    if (typeof window.jvValidateRow === 'function') {
        if (!window.jvValidateRow(rowNum)) {
            e.preventDefault();
            return false;
        }
    }

    var group_id = $('#groupid_' + rowNum).val(); //alert(group_id);
    var refno = $('#ref_' + rowNum).val();
    var curNum = rowNum;
    var baseGroup = baseGroupFor(curNum);
    var vatPercent = vatRateFor(curNum);
    var isVatAccount = vatFlagFor(curNum);
    var excludedGroups = ['PDCI', 'PDCR', 'BANK'];
    var requiresVatAccount = isVatAccount && Math.abs(vatPercent - 5) < 0.00001 && excludedGroups.indexOf(baseGroup) === -1;

    // increment rowNum now (keeps original flow)
    rowNum++;
    e.preventDefault();

    var controlForm = $('.controls .itemdivPrnt'),
        currentEntry = $(this).parents('.itemdivChld:first'),
        newEntry = $(currentEntry.clone()).appendTo(controlForm);

    // assign IDs for the newly cloned row using current rowNum
    newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
    newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
    newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
    newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
    newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum).attr('data-acct-group', '').attr('data-vat-rate', '').attr('data-vat-flag', '0');
    newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
    newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
    newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
    newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
    newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
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

    if ($('#groupid_' + curNum).val() == 'PDCI' || $('#groupid_' + curNum).val() == 'PDCR') {
        $('#chkno_' + rowNum).hide();
        $('#chkdate_' + rowNum).hide();
        $('#party_' + rowNum).hide();
        $('#bankid_' + rowNum).hide();
        newEntry.find($('.pdcfm')).hide();
        newEntry.find($('.col-xs-2')).css('width', '20%');
        newEntry.find($('.col-xs-1')).css('width', '15%');
    }

    // set initial values / clear fields for the new row
    $("#amount_" + rowNum).val(parseFloat($("#amount_" + rowNum).val() || 0));
    $('#draccount_' + rowNum).val('');
    $('#draccountid_' + rowNum).val('');
    $('#chkno_' + rowNum).val('');
    $('#chkdate_' + rowNum).val('');
    $('#party_' + rowNum).val('');
    $('#partyac_' + rowNum).val('');
    $('#jobcod_' + rowNum).val('');
    $('#jobid_' + rowNum).val('');

    if (!isVatAccount) {
        if ($('#acnttype_' + curNum + ' option:selected').val() == 'Dr') {
            $('#acnttype_' + rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
            getNetTotal();
        } else {
            $('#acnttype_' + rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
            getNetTotal();
        }
    }

    if ($('#infodivPrntItm_' + rowNum).is(":visible"))
        $('#infodivPrntItm_' + rowNum).toggle();

    controlForm.find('.btn-remove-item').show();

    // --- VAT flow (if required) ---
    if (requiresVatAccount) { // VAT input expense type (non PDC/BANK, 5% only)

        // local confirm (no global window.con)
        var confirmed = confirm('Do you want to update VAT account?');
        if (confirmed) {
            // read prev-row values safely before calling setGroupMeta
            var prevAmount = parseFloat($('#amount_' + (rowNum - 1)).val() || 0);
            var prevVat = parseFloat($('#vatamt_' + (rowNum - 1)).val() || 0);
            var vatAmount = (prevAmount * prevVat / 100) || 0;

            // populate VAT master account into the current newly-created row
            $('#draccount_' + rowNum).val('<?php echo ($account)?$account->master_name:'';?>');
            $('#draccountid_' + rowNum).val('<?php echo ($account)?$account->expense_account:'';?>');
            $('#groupid_' + rowNum).val('<?php echo ($account)?$account->id:'';?>'); // VAT master id

            // call setGroupMeta with the proper vat value
            if (typeof setGroupMeta === 'function') {
                setGroupMeta(rowNum, 'VAT', prevVat, 1);
            }

            // put computed VAT amount into this VAT line (format to 2 decimals)
            $('#amount_' + rowNum).val(vatAmount.toFixed(2));
            $('#ref_' + rowNum).val(refno);

            // temporarily hide add button while cloning balancing row
            controlForm.find('.btn-add-item').hide();

            // recompute running total safely
            var amt = 0;
            $('.jvline-amount').each(function () {
                amt += parseFloat(this.value || 0);
            });

            // clone a balancing entry (this becomes the third row)
            var currentEntry2 = $('.btn-add-item').parents('.itemdivChld:first');
            var balancingEntry = $(currentEntry2.clone()).appendTo(controlForm);

            // increment rowNum for the balancing row index
            rowNum++;

            // assign IDs for balancing row
            balancingEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
            balancingEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
            balancingEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
            balancingEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
            balancingEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum).attr('data-acct-group', '').attr('data-vat-rate', '').attr('data-vat-flag', '0');
            balancingEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
            balancingEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
            balancingEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val(amt.toFixed(2));
            balancingEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
            balancingEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
            balancingEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
            balancingEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
            balancingEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
            balancingEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum);
            balancingEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
            balancingEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
            balancingEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
            balancingEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
            balancingEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
            balancingEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
            balancingEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
            balancingEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);

            // set amount to sum of previous two lines (safe reads)
            var v1 = parseFloat($('#amount_' + (rowNum - 1)).val() || 0);
            var v2 = parseFloat($('#amount_' + (rowNum - 2)).val() || 0);
            $('#amount_' + rowNum).val((v1 + v2).toFixed(2));

            // FORCE this balancing (third) row to be Credit (Cr)
            var $typeSelBal = $('#acnttype_' + rowNum);
            if ($typeSelBal.length) {
                $typeSelBal.find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
                $typeSelBal.val('Cr').trigger('change');
            }

            // recalc totals
            if (typeof getNetTotal === 'function') {
                getNetTotal();
            }

            // revalidate debit/credit if validator present
            try {
                if ($('#frmJournal').data('bootstrapValidator')) {
                    $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
                    $('#frmJournal').bootstrapValidator('revalidateField', 'credit');
                }
            } catch (err) {
                // ignore validator errors
            }

            // restore add button(s)
            controlForm.find('.btn-add-item').show();

        } else {
            // user declined confirm -> keep normal clone as created earlier
            var amt = 0;
            $('.jvline-amount').each(function () {
                amt = parseFloat((this.value == '') ? 0 : this.value) + amt;
            });

            newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
            newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
            newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
            newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
            newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum).attr('data-acct-group', '').attr('data-vat-rate', '').attr('data-vat-flag', '0');
            newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
            newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
            newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val(amt);
            newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
            newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
            newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
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

            if ($('#acnttype_' + curNum + ' option:selected').val() == 'Dr') {
                $('#acnttype_' + rowNum).find('option').remove().end().append('<option value="Cr">Cr</option>');
            }
            getNetTotal();
        }
    } // end VAT flow

	
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		getNetTotal();
		e.preventDefault();
		return false;
	});
	
	$(document).on('blur', '#voucher_no', function(e) {  
		if(parseInt($(this).val()) > parseInt($('#curno').val())) {
			alert('Voucher no is greater than current range!');
			$('#voucher_no').val('');
		}
	});
	
	$(document).on('change', '.line-type', function(e) {
		
		getNetTotal();
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
	$(document).on('click', '#account_data .custRow', function(e) { //.accountRow
		var num = $('#num').val(); 
		var vatasgn = $(this).attr("data-vatassign");
		var baseGroup = $(this).attr("data-group");
		var vatRate = $(this).attr("data-vat");
		var isVatAccount = (vatasgn && vatasgn !== '0');
		var $typeSel = $('#acnttype_'+num);
		var prevType = $typeSel.val();
		$('#draccount_'+num).val( $(this).attr("data-name") );
		$('#draccountid_'+num).val( $(this).attr("data-id") );
		$('#groupid_'+num).val( isVatAccount ? $(this).attr("data-vatassign") : baseGroup );
		setGroupMeta(num, baseGroup, vatRate, isVatAccount ? 1 : 0);
		$('#vatamt_'+num).val( vatRate );

		if($(this).attr("data-group")=='PDCR') { 
			$('#chktype').val('PDCR');
			$typeSel.find('option').remove().end().append('<option value="Dr">Dr</option>');
			$typeSel.val('Dr').trigger('change');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			$('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
	
		} else if($(this).attr("data-group")=='PDCI') { 
			$('#chktype').val('PDCI');
			$typeSel.find('option').remove().end().append('<option value="Cr">Cr</option>');
			$typeSel.val('Cr').trigger('change');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			$('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num, function(e) {				
				$("#descr_"+num).val($("#descr_1").val());
				$("#ref_"+num).val($("#ref_1").val());	
				//$("#amount_"+num).val($("#amount_1").val());
				$("#amount_"+num).val(($('#amount_'+(rowNum-1)).val()));
				//$("#amount_"+num).val($("#amount_1").val());
			});
			
        } else if($(this).attr("data-group")=='BANK') { 
			$('#chktype').val('PDCR');
			$typeSel.find('option').remove().end().append('<option value="Dr">Dr</option>');
			$typeSel.val('Dr').trigger('change');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			$('#trns_'+num).load( trnurl+'/BANKJ/'+$(this).attr("data-id")+'/'+num );
		
		} else {
			$typeSel.find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
			if(prevType && $typeSel.find('option[value="'+prevType+'"]').length){
				$typeSel.val(prevType);
			} else {
				$typeSel.val('Dr');
			}
			$typeSel.trigger('change');
			//$('#trns_'+num+' .nopdc').removeAttr("style"); 
			//$('#trns_'+num+' .col-xs-2').removeAttr("style"); 
			//console.log('dd '+num);
			$('#trns_'+num+' .pdcfm').hide();
			$('#trns_'+num+' .nopdc1').attr("style", "width:17%;");
			$('#trns_'+num+' .nopdc2').attr("style", "width:25%;");
			$('#trns_'+num+' .nopdc3').attr("style", "width:15%;");
			$('#trns_'+num+' .nopdc4').attr("style", "width:8%;");
			$('#trns_'+num+' .nopdc5').attr("style", "width:13%;");
			$('#trns_'+num+' .nopdc6').attr("style", "width:17%;");

			//$('#chktype').val('');
			//$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
		  //   if( $('#chkno_'+num).is(":visible") )
				// $('#chkno_'+num).toggle(); 
				
		}
		
		if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER' || $(this).attr("data-group")=='VATIN' || $(this).attr("data-group")=='VATOUT' || $(this).attr("data-group")=='CASH') {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
			
            $('#invoiceData').html('<div>Do you want to treat this as an advance payment? &nbsp; <button type="button" class="btn btn-primary add-adv-grp" data-num="'+num+'" data-dismiss="modal" style="margin-bottom:15px;">Yes</button> &nbsp; &nbsp; <button type="button" class="btn btn-primary add-adv-no" data-num="'+num+'" data-dismiss="modal" style="margin-bottom:15px;">No</button></div>');
		} else {// group.id
			var nm = num - 1;
			if(nm > 0)
				var refval = $('#ref_'+nm).val();
			else
				refval = '';
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+refval+'" name="reference[]" class="form-control">');
		}
		
		if( $(this).attr("data-vatassign")=='1') {// group.id
			//$('#trndtl_'+num).toggle();
		}
		
		getNetTotal();
	});

	$(document).on('click', '.add-adv', function(e){
		e.preventDefault();
		var no = parseInt($('#bnum').val(),10);            
		$('#ref_'+no).val('ADV');
		$('#ref_'+no).attr("readonly", true);
	});

		$(document).on('click', '.add-adv-grp', function(e){
		e.preventDefault();
		var no = parseInt($(this).attr("data-num"),10);            
		$('#ref_'+no).val('ADV');
		$('#ref_'+no).attr("readonly", true);
	});

	$(document).on('click', '.add-adv-no', function(e){
		e.preventDefault();
		var no = parseInt($(this).attr("data-num"),10);            
		$('#ref_'+no).val('');
		$('#ref_'+no).attr("readonly", false);
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";  
	$(document).on('click', 'input[name="party_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { //paccount_data
		var num = $('#anum').val();
		$('#party_'+num).val( $(this).attr("data-name") );
		$('#partyac_'+num).val( $(this).attr("data-id") );
		checkChequeNo(num);
	});


	$(document).on('keyup', '.jvline-amount', function(e) { 
		getNetTotal();
	});

	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1];

		checkChequeNo(curNum);
	});
	
	$(document).on('change', '.line-bank', function(e) { 
	    var res = this.id.split('_');
		var curNum = res[1];

		checkChequeNo(curNum);
	});
	
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="jobcod[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		console.log(curNum);
		$('#jobData').load(joburl+'/'+curNum, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#job_modal .jobRow', function(e) {
		var num =$('#num').val();
		$('#jobcod_'+num).val($(this).attr("data-cod"));
		$('#jobid_'+num).val($(this).attr("data-id"));
		e.preventDefault();
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
		
		var baseRow = parseInt($('#bnum').val(), 10);
		var no = baseRow; //var rowNum;
		var j = 0; rowNum = baseRow;
		var drac = $('#draccount_'+baseRow).val();
		var dracid = $('#draccountid_'+baseRow).val();
		var baseGroupVal = $('#groupid_'+baseRow).val();
		var baseGroupAttr = $('#groupid_'+baseRow).attr('data-acct-group') || baseGroupFor(baseRow);
		var baseVatAttr = $('#groupid_'+baseRow).attr('data-vat-rate');
		if(typeof baseVatAttr === 'undefined' || baseVatAttr === ''){
			baseVatAttr = vatRateFor(baseRow);
		}
		var baseVatFlagAttr = $('#groupid_'+baseRow).attr('data-vat-flag');
		var baseVatFlag = (typeof baseVatFlagAttr !== 'undefined') ? baseVatFlagAttr : (vatFlagFor(baseRow) ? '1' : '0');
		var rnum = $('#rowNum').val();
		console.log('r '+no);
		$.each(refs,function(i) {
			if(j>0) {
				var controlForm = $('.controls .itemdivPrnt'),
				currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);
				rowNum++;
				rnum++; 
				newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
				newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
				newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
				newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
				newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum).attr('data-acct-group','').attr('data-vat-rate','').attr('data-vat-flag','0');
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
				setGroupMeta(rowNum, baseGroupAttr, baseVatAttr, baseVatFlag);
				
			} 
			$('#draccount_'+no).val(drac);
			$('#draccountid_'+no).val(dracid);
			$('#groupid_'+no).val(baseGroupVal);
			setGroupMeta(no, baseGroupAttr, baseVatAttr, baseVatFlag);
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
    
     $('.onacnt_icheck').on('ifChecked', function(event){ 
        $("[name='reference[]']").val('ADV'); 
        $( '.acname' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1]; 
		  if($('#groupid_'+n).val()=='SUPPLIER' || $('#groupid_'+n).val()=='CUSTOMER') {
            $('#ref_'+n).attr('readonly', true); 
		  }
          
        });
    });
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
        $("[name='reference[]']").val('')
        $("[name='reference[]']").attr('readonly', false);
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


function checkChequeNo(curNum) { 

	var bank = $('#bankid_'+curNum+' option:selected').val();
	var chqno = ($('#chkno_'+curNum).val() || '').trim();
	var ac = $('#partyac_'+curNum).val();
	var $field = $('#chkno_'+curNum);
	var serverErrorId = 'chkno_'+curNum+'-server-error';

	if(!$field.length){
		return;
	}
	if(!bank || !chqno || !ac){
		$('#'+serverErrorId).remove();
		$field.removeClass('is-invalid');
		return;
	}
		
	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+encodeURIComponent(chqno)+'&bank_id='+encodeURIComponent(bank)+'&ac_id='+encodeURIComponent(ac),
		success: function(data) { 
			$('#'+serverErrorId).remove();
			if(data=='') {
				$field.addClass('is-invalid');
				$('<label>', {
					id: serverErrorId,
					class: 'text-danger small',
					text: 'Cheque no is duplicate!'
				}).insertAfter($field);
				$field.val('').focus();
			} else {
				$field.removeClass('is-invalid');
			}
		}
	});

}

$('.rcEntry').toggle(); $('.toRc').toggle();
$(document).on('change', '#jvtype', function(e) {
	$('.rcEntry').toggle();
	$('.toRc').toggle();
});

$(document).on('click', '.addRecu', function(e) {
	let vno = $('#voucher_no').val();
	let vdate = $('#voucher_date').val();
	let per = $('#rcperiod').val();
	
	var formData = $(".itemdivPrnt :input").serialize();
	formData += '&vno='+vno+'&vdate='+vdate+'&per='+per;  
	$.ajax({
		url: "{{ url('journal/recurring_add/') }}",
		type: 'post',
		data: formData, 
		success: function(data) { 
			$('.toRc').html(data);
		}
	});
});


/* ==================== JV VALIDATION SCRIPT (No Alerts) ==================== */
$(function () {

  // ---------- Custom Balanced Rule ----------
  $.validator.addMethod("balanced", function () {
    var dr = parseFloat($("#debit").val()) || 0;
    var cr = parseFloat($("#credit").val()) || 0;
    return dr.toFixed(2) === cr.toFixed(2);
  }, "Debit and Credit totals must be equal.");

  // ---------- Initialize Form Validation ----------
  window.validator = $("#frmJournal").validate({
    ignore: [],
    errorClass: "text-danger small",
    errorElement: "label",
    highlight: function (element) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element) {
      $(element).removeClass("is-invalid");
    },
    rules: {
      voucher_type: { required: true },
      voucher: { required: true },
      voucher_no: { required: true },
      voucher_date: { required: true },
      debit: { required: true, number: true, min: 0, balanced: true },
      credit: { required: true, number: true, min: 0, balanced: true },
    },
    invalidHandler: function(event, validator){
      validateAllRows();
      if (validator.errorList && validator.errorList.length){
        setTimeout(function(){
          $(validator.errorList[0].element).focus();
        }, 0);
      }
    },
    submitHandler: function(form){
      if(!validateAllRows()){
        var $first = $('.is-invalid:visible').first();
        if($first.length){ $first.focus(); }
        return false;
      }
      if(!checkDuplicateCheque()){
        alert("Duplicate Cheque No detected for same Bank and Party. Please correct it before submitting.");
        return false;
      }
      var debit  = parseFloat($("#debit").val())  || 0;
      var credit = parseFloat($("#credit").val()) || 0;
      if(debit.toFixed(2) !== credit.toFixed(2)){
        alert("Debit and Credit must be equal before submitting.");
        $("#credit").focus();
        return false;
      }
      openJournalConfirmModal(form);
      return false;
    }
  });

  // ---------- Add Validation Rules for Each Row ----------
  function addRulesForRow(rowNum) {
    $("#draccount_" + rowNum).rules("add", { required: true });
    $("#ref_" + rowNum).rules("add", { required: true });
    $("#amount_" + rowNum).rules("add", { required: true, number: true, min: 0.01 });
    $("#acnttype_" + rowNum).rules("add", { required: true });

    var group_id = $("#groupid_" + rowNum).val();
    if (group_id === "PDCR" || group_id === "PDCI" || group_id === "BANK") {
      $("#bankid_" + rowNum).rules("add", { required: true });
      $("#chkno_" + rowNum).rules("add", { required: true });
      $("#chkdate_" + rowNum).rules("add", { required: true });
      $("#party_" + rowNum).rules("add", { required: true });
    } else {
      $("#bankid_" + rowNum).rules("remove");
      $("#chkno_" + rowNum).rules("remove");
      $("#chkdate_" + rowNum).rules("remove");
      $("#party_" + rowNum).rules("remove");
    }
  }

  // ---------- Validate Single Row ----------
  function validateRow(rowNum) {
    addRulesForRow(rowNum);
    var valid = true;
    [
      "#draccount_" + rowNum,
      "#ref_" + rowNum,
      "#amount_" + rowNum,
      "#acnttype_" + rowNum,
      "#bankid_" + rowNum,
      "#chkno_" + rowNum,
      "#chkdate_" + rowNum,
      "#party_" + rowNum,
    ].forEach(function (id) {
      if ($(id).length) valid = $("#frmJournal").validate().element($(id)) && valid;
    });
    return valid;
  }

  // ---------- Validate All Rows ----------
  function validateAllRows() {
    var ok = true;
    $(".classtrn").each(function () {
      var id = $(this).attr("id");
      var rowNum = id ? id.split("_")[1] : 1;
      if (!validateRow(rowNum)) ok = false;
    });
    return ok;
  }

  // ---------- Check Duplicate Cheque Numbers ----------
  function checkDuplicateCheque() {
    $(".dup-error").remove();
    var seen = {};
    var dup = false;

    $("input[name='cheque_no[]']").each(function () {
      var idx = $(this).attr("id").split("_")[1];
      var chq = $(this).val().trim();
      var bank = $("#bankid_" + idx).val();
      var party = $("#partyac_" + idx).val();
      $(this).removeClass("is-invalid");
      if (chq && bank && party) {
        var key = bank + "|" + chq + "|" + party;
        if (seen[key]) {
          dup = true;
          $(this)
            .addClass("is-invalid")
            .after('<label class="text-danger small dup-error">Duplicate cheque found</label>');
        } else seen[key] = true;
      }
    });
    return !dup;
  }

  // ---------- On Submit ----------
  // ---------- Dynamic Row Validation Rebuild ----------
  $(document).on("click", ".btn-add-item, .btn-remove-item", function () {
    setTimeout(function () {
      $(".classtrn").each(function () {
        var id = $(this).attr("id");
        var rowNum = id ? id.split("_")[1] : 1;
        addRulesForRow(rowNum);
      });
    }, 100);
  });

  // ---------- Fix Button Visibility ----------
  $(document).on("click", ".btn-add-item, .btn-remove-item", function () {
    var rows = $(".itemdivChld");
    rows.find(".btn-add-item").hide();
    rows.find(".btn-remove-item").show();
    if (rows.length === 1) rows.find(".btn-remove-item").hide();
    rows.last().find(".btn-add-item").show();
  });

  window.jvValidateRow = validateRow;

});
/* ==================== END JV VALIDATION SCRIPT ==================== */

</script>


@stop
