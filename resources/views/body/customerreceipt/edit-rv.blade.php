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
                   Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Customer Receipt
                            </h3>
                           
						   <div class="pull-right">
								@permission('pv-print')
								 <a href="{{ url('customer_receipt/printgrp/'.$crrow->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endpermission
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmCustReceipt" id="frmCustReceipt" action="{{ url('customer_receipt/update/'.$crrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="1">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								<input type="hidden" name="pv_id" id="pv_id" value="{{$crrow->id}}">
                                <input type="hidden" name="voucher_type" value="10">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
										@foreach($vouchertype as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
										@endforeach
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PV. No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" value="{{$crrow->voucher_no}}" readonly name="voucher_no">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PV. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" value="{{date('d-m-Y', strtotime($crrow->voucher_date))}}" data-language='en' autocomplete="off" id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
                                <?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
								 <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" value="<?php echo (old('salesman'))?old('salesman'):$salesman->salesman; ?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
										<input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (old('salesman_id'))?old('salesman_id'):$salesman->salesman_id; ?>">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								
								<br/>
								<fieldset>
								<legend><h4>Transactions</h4></legend>
										{{--*/ $i = 0; $num = count($invoicerow); /*--}}
										<input type="hidden" id="rowNum" value="{{$num}}">
										<input type="hidden" id="remitem" name="remove_item">
										<div class="itemdivPrnt">
										@php $chkno = $chkdate = ''; $ischk = false; @endphp      
										@foreach($invoicerow as $item)
										{{--*/ $i++; /*--}}
										
											<div class="itemdivChld">							
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_{{$i}}">
												@if($item->category=='PDCR')
													@if($isdept) <!-- PDC WITH DEPARTEMNT -->
														<div class="col-xs-1" style="width:11%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-2" style="width:11%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:9%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														<div class="col-xs-1" style="width:6%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-sm-2" style="width:8%;"> 
                                                            <span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
                                                            <input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                                        </div>
														
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
															<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
														</div>
														
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>
														
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Bank</span> 
															<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
															<option value="">Select Bank...</option>
																@foreach($banks as $bank)
																<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-xs-1" style="width:8%;">
															<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_account_id}}">
															<span class="small">Party Name</span> <input type="text" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
														</div>
														
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Department</span> 
															<select id="dept_{{$i}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
																<option value="">Department...</option>
																@foreach($departments as $department)
																<option value="{{ $department->id }}" <?php if($item->department_id==$department->id) echo 'selected';?>>{{ $department->name }}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@else  <!-- PDC WITHOUT DEPARTEMNT -->
														<div class="col-xs-1" style="width:12%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-2" style="width:12%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:7%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															</select>
														</div>
                                                        
                                                        <div class="col-xs-1" style="width:9%;">
															<span class="small">Bank</span> 
															<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
															<option value="">Select Bank...</option>
																@foreach($banks as $bank)
																<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																@endforeach
															</select>
														</div>

														<div class="col-xs-1" style="width:8%;">
															<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
															<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
														</div>
														
														<div class="col-xs-1" style="width:9%;">
															<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>
														
														<div class="col-xs-1" style="width:9%;">
															<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_account_id}}">
															<span class="small">Party Name</span> <input type="text" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
														</div>
														
                                                        <div class="col-sm-2" style="width:9%;"> 
                                                            <span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
                                                            <input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{isset($item->code)?$item->code:''}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                                        </div>

														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														</div>
													@endif <!-- END PDC WITHOUT DEPARTEMNT -->
													
												@else  <!-- NON PDC SECTION -->
													@if($isdept) <!-- NON PDC WITH DEPARTEMNT -->
														<div class="col-sm-2"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-3" style="width:20%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:12%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														<div class="col-xs-1" style="width:7%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
																<option value="{{($item->entry_type=='Cr')?'Dr':'Cr'}}">{{($item->entry_type=='Cr')?'Dr':'Cr'}}</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:12%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-sm-2" style="width:15%;"> 
                                                            <span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
                                                            <input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                                        </div>
														
														<div class="col-xs-3" style="width:13%;">
															<span class="small">Department</span> 
															<select id="dept_{{$i}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
																<option value="">Department...</option>
																@foreach($departments as $department)
																<option value="{{ $department->id }}" <?php if($item->department_id==$department->id) echo 'selected';?>>{{ $department->name }}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@else <!-- NON PDC WITHOUT DEPARTEMNT -->
													
														<?php if($item->category=='BANK') {
															$chkno = $item->cheque_no;
															$chkdate = ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));
															$ischk = true;
														}
													?>
														<div class="col-sm-2"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															</select>
														</div>

                                                        <div class="col-sm-2" style="width:17%;"> 
                                                            <span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
                                                            <input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{isset($item->code)?$item->code:''}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                                        </div>

														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															
														</div>
													@endif <!-- END NON PDC WITHOUT DEPARTEMNT -->
                                                        <input type="hidden" name="bank_id[]">
                                                        <input type="hidden" name="cheque_no[]">
                                                        <input type="hidden" name="oldcheque_no[]">
                                                        <input type="hidden" name="cheque_date[]">
                                                        <input type="hidden" name="party_name[]">
                                                        <input type="hidden" name="partyac_id[]">
														
												@endif	
												</div>
												
											</div>
										@endforeach
										</div>
								</fieldset>
								
                                <hr/>
                                <div >
                                    <button type="button" class="btn-info btn-sm more-info btn-add-item" data-type="Dr"><i class="fa fa-fw fa-plus-square"></i> Add Dr</button>
                                    <button type="button" class="btn-info btn-sm more-info btn-add-item" data-type="Cr"><i class="fa fa-fw fa-plus-square"></i> Add Cr</button>
                                    <button type="button" class="btn-info btn-sm more-info btn-add-item" data-type="PDC"><i class="fa fa-fw fa-plus-square"></i> Add PDC</button>
                                    <button type="button" class="btn-info btn-sm more-info btn-add-item" data-type="Bank"><i class="fa fa-fw fa-plus-square"></i> Add Bank</button>
                                </div>

								<hr/>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly value="{{$crrow->debit}}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00" value="{{$crrow->credit}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00" value="{{number_format($crrow->difference)}}">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('customer_receipt') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('customer_receipt') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
                            </form>
								
							</div>
                        </div>
                    </div>
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
"use strict";

$(document).ready(function () {
	var urlvchr = "{{ url('customer_receipt/checkvchrno/') }}"; //CHNG
	$('#onAccount').toggle(); $('#debit_entry').toggle();
//$('#frmCustReceipt').bootstrapValidator({   
    var options = {
         fields: {
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
			cheque_no: {
                validators: {
					callback: {
                            message: 'The cheque no is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCustReceipt').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
                        }
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
           /* "account_name[]": {
                validators: {
                    notEmpty: {
                        message: 'account name is required and cannot be empty!'
                    }
                }
            },
            "line_amount[]": {
                validators: {
                    notEmpty: {
                        message: 'amount is required and cannot be empty!'
                    }
                }
            }*/
            
          }
        };
        
        $('#frmCustReceipt').bootstrapValidator(options);
    //});  

});   

$(function(){

    $(document).on('click','.datepicker--cell-day', function() {
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'cheque_date[]');
    })

    var rowNum={{$num}};
    $(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; 
            e.preventDefault();
            var controlForm; var newEntry;
            var currentEntry = $('.itemdivChld:first');

                if($(this).attr("data-type") == 'Dr' || $(this).attr("data-type") == 'Cr') {
                    if(currentEntry.length==1) {
                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld:first'); 
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);
                    } else {

                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld-1st:first');
                        newEntry = $(currentEntry.clone()).appendTo(controlForm); 
                    }

                    newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
                    newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
                    newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
                    newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
                    newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
                    newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
                    newEntry.find($('input[name="je_id[]"]')).attr('id', 'jeid_' + rowNum);
                    newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
                    newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
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
                    newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rowNum);
                    newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
                    newEntry.find($('input[name="oldcheque_no[]"]')).attr('id', 'oldchkno_' + rowNum);
                    newEntry.find($('.btn-remove-item')).attr('data-id','rem_'+rowNum);
                    var des = $('input[name="description[]"]').val();
                    newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
			        newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
                    $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="'+$(this).attr("data-type")+'">'+$(this).attr("data-type")+'</option>');
                    $('#chqdtl_'+rowNum).html('');
                    
                    $("#amount_"+rowNum).val(parseFloat($("#amount_"+rowNum).val()));
                    $('#draccount_'+rowNum).val(''); $('#descr_'+rowNum).val('');
                    $('#draccountid_'+rowNum).val(''); $('#ref_'+rowNum).val('');
                    $('#chkno_'+rowNum).val(''); $('#amount_'+rowNum).val('');
                    $('#chkdate_'+rowNum).val(''); $('#jeid_'+rowNum).val('');
                    $('#party_'+rowNum).val(''); $('#invoiceid_'+rowNum).val('');
                    $('#partyac_'+rowNum).val(''); $('#biltyp_'+rowNum).val('');
                    $('#groupid_'+rowNum).val(''); $('#tr_id'+rowNum).val('');
                    $('#inv_id'+rowNum).val(''); $('#actamt_'+rowNum).val('');
                    $('#jobid_'+rowNum).val(''); $('#jobcod_'+rowNum).val('');
                    newEntry.find($('input[name="description[]"]')).val(des);
                    

                } else if($(this).attr("data-type") == 'PDC'){

                    //$('#chktype').val('PDCR');
                    if(currentEntry.length==1) {
                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld:first');
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);
                    } else {
                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld-1st:first'); 
                        newEntry = $(currentEntry.clone()).appendTo(controlForm); 
                    }

                    newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
                    $('#trns_'+rowNum).html('');

                    var trnurl = "{{ url('customer_receipt/set_transactions/') }}";
                        $('#trns_'+rowNum).load( trnurl+'/PDC/0/'+rowNum, function(e) {				
                    });

                } else if($(this).attr("data-type") == 'Bank') {

                    //$('#chktype').val('BANK');
                    if(currentEntry.length==1) {
                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld:first'); 
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);

                    } else {

                        controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.itemdivChld-1st:first'); 
                        newEntry = $(currentEntry.clone()).appendTo(controlForm); 
                    }

                    newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
                    $('#trns_'+rowNum).html('');

                    var trnurl = "{{ url('customer_receipt/set_transactions/') }}";
                    $('#trns_'+rowNum).load( trnurl+'/Bank/0/'+rowNum, function(e) {				
                        
                    });
                }

                newEntry.find($('#frmCustReceipt')).bootstrapValidator('addField',"account_name[]");

    }).on('click', '.btn-remove-item', function(e)
    {   
        var res = $(this).attr('data-id').split('_');
        var curNum = res[1]; var ids;
        
        var remitem = $('#remitem').val();
        ids = (remitem=='')?$('#jeid_'+curNum).val():remitem+','+$('#jeid_'+curNum).val();
        $('#remitem').val(ids);
        
        $(this).parents('.itemdivChld:first').remove();
        
        getNetTotal();
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');

        e.preventDefault();
        return false;
    });

    var acurl = "{{ url('account_master/get_accounts/') }}";
    $(document).on('click', 'input[name="account_name[]"]', function(e) {
        var res = this.id.split('_');
        var curNum = res[1]; 
        $('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
            $('#myModal').modal({show:true}); $('.input-sm').focus();
        });
    });

    $(document).on('click', '#account_data .custRow', function(e) { 
        var num = $('#account_data #num').val(); var vatasgn = $(this).attr("data-vatassign");
        $('.itemdivPrnt #draccount_'+num).val( $(this).attr("data-name") );
        $('.itemdivPrnt #draccountid_'+num).val( $(this).attr("data-id") );
        $('.itemdivPrnt #groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
        $('.itemdivPrnt #vatamt_'+num).val( $(this).attr("data-vat") );

        if( $(this).attr("data-group")=='CUSTOMER') { 
            $('.itemdivPrnt #refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
        } else {// group.id
            var nm = num - 1;
            if(nm > 0)
                var refval = $('#ref_'+nm).val();
            else
                refval = '';
            $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+refval+'" name="reference[]" class="form-control">');
        }

        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'account_name[]');
    });


    $(document).on('keyup', '.jvline-amount', function(e) { 
        getNetTotal();
        // Revalidate the date when user change it
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'debit');
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'credit');
    });

    $(document).on('click', '.ref-invoice', function(e) {
        var res = this.id.split('_');
        var curNum = res[1]; 
        
        if( $('.itemdivPrnt #groupid_'+curNum).val()=='CUSTOMER' ) { //supplier type.........
            var url = "{{ url('account_enquiry/os_bills/') }}/"+$('.itemdivPrnt #draccountid_'+curNum).val();
            $('#invoiceData').load(url+'/'+curNum, function(result) {
                $('#myModal').modal({show:true});
            });
        } else if( $('.itemdivPrnt #groupid_'+curNum).val()=='VATIN' || $('#groupid_'+curNum).val()=='VATOUT') { //supplier type.........
            var url = "{{ url('account_enquiry/os_bills/') }}/"+$('.itemdivPrnt #draccountid_'+curNum).val();
            $('#invoiceData').load(url+'/'+curNum, function(result) {
                $('#myModal').modal({show:true});
            });
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

    $(document).on('click', '#paccount_data .custRow', function(e) { //paccount_data
        var num = $('#anum').val();
        $('#party_'+num).val( $(this).attr("data-name") );
        $('#partyac_'+num).val( $(this).attr("data-id") );
        
        $('#frmCustReceipt').bootstrapValidator('revalidateField', 'party_name[]');
        checkChequeNo(num);
    });

    $(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
        var res = this.id.split('_');
        var curNum = res[1];
        checkChequeNo(curNum);
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
        
        var no = $('#bnum').val(); //var rowNum;
        var j = 0; rowNum = parseInt(no);
        var drac = $('.itemdivPrnt #draccount_'+no).val();
        var dracid = $('.itemdivPrnt #draccountid_'+no).val();
        var rnum = $('#rowNum').val();
        console.log('r '+no);
        $.each(refs,function(i) {
            if(j>0) {
                var controlForm; var newEntry;
                var currentEntry = $('.itemdivChld:first');
                if(currentEntry.length==1) {
                    controlForm = $('.controls .itemdivPrnt'),
                    currentEntry = $('.itemdivChld:first'); 
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);
                } else {

                    controlForm = $('.controls .itemdivPrnt'),
                    currentEntry = $('.itemdivChld-1st:first');
                    newEntry = $(currentEntry.clone()).appendTo(controlForm); 
                }
                
                rowNum++;
                rnum++; 
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
            $('.itemdivPrnt #draccount_'+no).val(drac);
            $('.itemdivPrnt #draccountid_'+no).val(dracid);
            $('.itemdivPrnt #ref_'+no).val( refs[i] );
            $('.itemdivPrnt #amount_'+no).val(amounts[i])
            $('.itemdivPrnt #invid_'+no).val( ids[i] );
            $('.itemdivPrnt #acnttype_'+no).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
            $('.itemdivPrnt #actamt_'+no).val( actamt[i] );
            $('.itemdivPrnt #invoiceid_'+no).val( invid[i] );
            $('.itemdivPrnt #biltyp_'+no).val( btype[i] );
            j++; no++;
        });
        getNetTotal();

        $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
        $('#frmJournal').bootstrapValidator('revalidateField', 'credit');
        $('#is_onaccount').val(0);
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
        var num =$('#jobData #num').val();
        $('.itemdivPrnt #jobcod_'+num).val($(this).attr("data-cod"));
        $('.itemdivPrnt #jobid_'+num).val($(this).attr("data-id"));
        e.preventDefault();
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
    var chqno = $('#chkno_'+curNum).val();
    var ac = $('#partyac_'+curNum).val();
        
    $.ajax({
        url: "{{ url('account_master/check_chequeno/') }}",
        type: 'get',
        data: 'chqno='+chqno+'&bank_id='+bank+'&ac_id='+ac,
        success: function(data) { 
            if(data=='') {
                alert('Cheque no is duplicate!');
                $('#chkno_'+curNum).val('');
            }
        }
    })

}
</script>
@stop
