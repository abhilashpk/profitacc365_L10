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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Supplier Payment
                            </h3>
                           
						   <div class="pull-right">
								@permission('pv-print')
								 <a href="{{ url('supplier_payment/print/'.$crrow->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endpermission
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
							@php $ispdc = false; @endphp
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('supplier_payment/update/'.$crrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="1">
								
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								<input type="hidden" name="pv_id" id="pv_id" value="{{$crrow->id}}">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="10">PV - Payment Voucher</option>
										</select>
                                    </div>
                                </div>
								
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
								
								<br/>
								<fieldset>
								<legend><h5>Transactions</h5></legend>
										{{--*/ $i = 0; $num = count($invoicerow); /*--}}
										<input type="hidden" id="rowNum" value="{{$num}}">
										<input type="hidden" id="remitem" name="remove_item">
										<div class="itemdivPrnt">
										 	@php $chkno = $chkdate = ''; $ischk = false; @endphp      
										@foreach($invoicerow as $item)
										{{--*/ $i++; /*--}}
										
											<div class="itemdivChld">							
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_{{$i}}">
												@if($item->category=='PDCI')
												@php $ispdc = true; @endphp
													@if($isdept) <!-- PDC WITH DEPARTEMNT -->
														<div class="col-xs-1" style="width:11%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
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
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
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
														<div class="col-xs-1 nopdc1" style="width:12%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-2 nopdc2" style="width:12%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2 nopdc3" style="width:10%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														<div class="col-xs-1 nopdc4" style="width:7%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															</select>
														</div>
														<div class="col-xs-2 nopdc5" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:8%;">
															<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
															<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:9%;">
															<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:9%;">
															<span class="small">Bank</span> 
															<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
															<option value="">Select Bank...</option>
																@foreach($banks as $bank)
																<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:9%;">
															<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_account_id}}">
															<span class="small">Party Name</span> <input type="text" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
														</div>
														
														<div class="col-sm-2 nopdc6" style="width:9%;"> 
															<span class="small">Job</span> 
															<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@endif <!-- END PDC WITHOUT DEPARTEMNT -->
													
												@elseif($item->category=='BANK')
												    @if($isdept) <!-- BANK WITH DEPARTEMNT -->
														<div class="col-xs-1" style="width:15%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-2" style="width:18%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:9%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
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

														<div class="col-xs-1" style="width:8%;">
															<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
															<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
														</div>
														
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
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
														
														<div class="col-sm-2" style="width:8%;"> 
															<span class="small">Job</span> 
															<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@else  <!-- BANK WITHOUT DEPARTEMNT -->
														<div class="col-xs-1 nopdc1" style="width:17%;"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="bill_type[]" value="{{$item->bill_type}}" id="biltyp_{{$i}}">
															<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
														</div>
													
														<div class="col-xs-2 nopdc2" style="width:22%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2 nopdc3" style="width:10%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Dr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Dr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														<div class="col-xs-1 nopdc4" style="width:7%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															</select>
														</div>
														<div class="col-xs-2 nopdc5" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:8%;">
															<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
															<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
														</div>
														
														<div class="col-xs-1 pdcfm" style="width:9%;">
															<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>
													
														<div class="col-sm-2 nopdc6" style="width:12%;"> 
															<span class="small">Job</span> 
															<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@endif <!-- END BANK WITHOUT DEPARTEMNT -->
													
												@else  <!-- NON PDC SECTION -->
													@if($isdept) <!-- NON PDC WITH DEPARTEMNT -->
														<div class="col-sm-2"> <span class="small">Account Name</span>
															<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
															<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
															<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
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
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
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
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
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
															
															<input type="hidden" id="invoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{$item->purchase_invoice_id}}">
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
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->purchase_invoice_id}}">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
														</div>
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
																<option value="{{($item->entry_type=='Cr')?'Dr':'Cr'}}">{{($item->entry_type=='Cr')?'Dr':'Cr'}}</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-sm-2" style="width:17%;"> 
															<span class="small">Job</span> 
															<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
													
														<div class="col-xs-1 abc" style="width:3%;"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
															
															<?php if($crrow->group_id!=1) { ?> <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button><?php } ?>
														</div>
													@endif <!-- END NON PDC WITHOUT DEPARTEMNT -->
														<div id="chqdtl_{{$i}}" class="divchq" style="display: none;">
															<div class="col-xs-2">
																<span class="small">Bank</span> 
																<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																<option value="">Select Bank...</option>
																	@foreach($banks as $bank)
																	<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																	@endforeach
																</select>
															</div>
															
															<div class="col-sm-2"> 
																<span class="small">Cheque No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
																<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
															</div>
															
															<div class="col-xs-2">
																<span class="small">Cheque Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
															</div>
															
															<div class="col-xs-2">
																<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_account_id}}">
																<span class="small">Party Name</span> <input type="text" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
															</div>
														</div>
												@endif	
												</div>
												
												<div class="form-group">
													
													<div class="col-xs-15">
														<div class="col-sm-2"> </div>
														
														
													</div>	
												</div>
												
											</div>
										@endforeach
										</div>
								</fieldset>
								
								<hr/>
								<?php if($crrow->group_id==1) { ?>
								<div class="form-group" id="trndtl_1" class="divTrn">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-5"> 
											<span class="small"><b>Supplier Name</b></span><input type="text" id="supname_1" value="{{$crrow->supplier_name}}" name="supplier_name" class="form-control" >
										</div>
										<div class="col-xs-5">
											<span class="small"><b>TRN No</b></span> <input type="text" id="trnno_1" name="trn_no" value="{{$crrow->trn_no}}" class="form-control">
										</div>
                                </div>
								<?php } ?>
								
								<!--	<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Cheque Datails</label>
                                    <div class="col-sm-1">
										<label class="radio-inline iradio"></label>
											<input type="checkbox" class="debit_icheck bank-chk" id="is_debit" name="is_debit" {{($ischk)?'checked':''}} value="1">
                                    </div>
									<span class="bnk-info">
                                    <label for="input-text" class="col-sm-1 control-label">Cheque No</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="chkno" name="chq_no" value="{{$chkno}}">
                                    </div>
									<label for="input-text" class="col-sm-2 control-label">Cheque Date</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="chkdt" name="chq_date" data-language='en' value="{{$chkdate}}">
                                    </div>
									</span>
                                </div> -->
								
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
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<script>

//$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } )
$('#chkdt').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy'});
"use strict";

$(document).ready(function () {
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}

	@if(!$ischk)
		$('.bnk-info').hide();
	@endif
	
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
		});
		
	});
	
});

$(function(){
	var rowNum = $('#rowNum').val();
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  //$('#chqdtl_1').toggle();
  
  $(document).on('click', '.btn-add-item', function(e)  { 
		var group_id = $('#groupid_'+rowNum).val();
		var refno = $('#ref_'+rowNum).val();
		var curNum = rowNum;
		rowNum++; 
		$('#rowNum').val(rowNum);
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
			newEntry.find($('input[name="je_id[]"]')).attr('id', 'jeid_' + rowNum);
			newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
			newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val;
		//	newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
		     newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
			newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
			newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
			newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
			newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
			newEntry.find($('input[name="purchase_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
			newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rowNum);
			newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum);
			newEntry.find($('input[name="oldcheque_no[]"]')).attr('id', 'oldchkno_' + rowNum);
			newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);

			newEntry.find($('input[name="department[]"]')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
			newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
			newEntry.find($('.btn-remove-item')).attr('data-id','rem_'+rowNum);
			
			var des = $('input[name="description[]"]').val();
			//$('#amount_'+rowNum).val('');
			// var am = $("#amount_1").val();
			// console.log(am);
			// var ma = $("#amount_"+rowNum).val(parseFloat($("#amount_"+(rowNum-1)).val()));
			// console.log(am);
			if(group_id!='1') {
				if( $('#acnttype_'+curNum+' option:selected').val()=='Cr') { 
					
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
					getNetTotal();
					
				} else {
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
					
				}
			}

			$('#draccount_'+rowNum).val('');
			$('#draccountid_'+rowNum).val('');
			$('#amount_'+rowNum).val('');
			$('#ref_'+rowNum).val('');
			//$('#descr_'+rowNum).val('');

			//$('#ref_'+rowNum).val(''); $('#amount_'+rowNum).val(''); $('#jeid_'+rowNum).val('');
			
			//newEntry.find('input').val(''); 
			newEntry.find('#jeid_' + rowNum).val(''); 
			newEntry.find($('input[name="description[]"]')).val(des);
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show()
			
			/*$('.chqdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy'
			});*/
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#jeid_'+curNum).val():remitem+','+$('#jeid_'+curNum).val();
		$('#remitem').val(ids);
		
		$(this).parents('.itemdivChld:first').remove();
		
		getNetTotal();
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	/*$('.chqdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy'
	});*/
	
	$(document).on('keyup', '.jvline-amount', function(e) {
		getNetTotal();
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
	$(document).on('click', '#account_modal .custRow', function(e) { 
		var num = $('#num').val();
		$('#draccount_'+num).val( $(this).attr("data-name") );
		$('#draccountid_'+num).val( $(this).attr("data-id") );
		$('#groupid_'+num).val( $(this).attr("data-group") );
		
		if($(this).attr("data-group")=='PDCR') { 
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			if($('#jeid_'+num).val()!='')
			    $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num+'/'+$('#jeid_'+num).val() );
			else
			    $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
			
			/* if( $('#chqdtl_'+num).is(":hidden") )
				$('#chqdtl_'+num).toggle(); */
				
		} else if($(this).attr("data-group")=='PDCI') { 
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			if($('#jeid_'+num).val()!='')
			    $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num+'/'+$('#jeid_'+num).val() );
			else
			    $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
			
			/* if( $('#chqdtl_'+num).is(":hidden") )
				$('#chqdtl_'+num).toggle(); */
		} else if($(this).attr("data-group")=='BANK') { 
		    
			$('#chktype').val('BANK');
			//$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
			var trnurl = "{{ url('journal/set_transactions/') }}";
			if($('#jeid_'+num).val()!='')
			    $('#trns_'+num).load( trnurl+'/BANKS/'+$(this).attr("data-id")+'/'+num+'/'+$('#jeid_'+num).val() );
			else
			    $('#trns_'+num).load( trnurl+'/BANKS/'+$(this).attr("data-id")+'/'+num );
			
		} else {
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
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
			
			getNetTotal();
			 //$('#trns_'+num+' .nopdc').toggleClass('nopdc col-xs-2');
			 //$('#trns_'+num+' .nopdc').toggleClass('nopdc col-xs-3');
			 
			/* if( $('#chqdtl_'+num).is(":visible") )
				$('#chqdtl_'+num).toggle(); */
		} 
		// else {
		// 	//$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
		// 	//var trnurl = "{{ url('journal/set_transactions/') }}";
		// 	//$('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
			
		// 	/* if( $('#chqdtl_'+num).is(":visible") )
		// 		$('#chqdtl_'+num).toggle(); */
		// }
		
		 if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER') {// group.id
		 	$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
		 } else {// group.id
		 	$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control">');
		 }
	});
	


    
    $(document).on('click', '#paccount_data .custRow', function(e) { //paccount_data
		var num = $('#anum').val();
		$('#party_'+num).val( $(this).attr("data-name") );
		$('#partyac_'+num).val( $(this).attr("data-id") );
		checkChequeNo(num);
		
	});
	
	 $(document).on('change', '.line-bank', function(e) { 
	    var res = this.id.split('_');
		var curNum = res[1];

		checkChequeNo(curNum);
	});
	
	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {

		var res = this.id.split('_');
		var curNum = res[1];
		checkChequeNo(curNum);
		
		/*var chqno = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		var oldcqno = $('#oldchkno_'+curNum).val();
		var bank = $('#bankid_'+curNum+' option:selected').val();
		if(oldcqno != chqno) {
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
		}*/
	});
	
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="jobcod[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
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
		//console.log($('#groupid_'+curNum).val());
		if( $('#groupid_'+curNum).val()=='CUSTOMER') { //customer type.............
			var purl = "{{ url('sales_invoice/get_invoice/') }}/"+$('#draccountid_'+curNum).val();
			var reid = $('#jeid_'+curNum).val();
			if((reid!='') && (this.value!='')) {
				$('#invoiceData').load(purl+'/'+curNum+'/'+this.value+'/'+reid, function(result){ //.modal-body item
					$('#myModal').modal({show:true});
				});
			} else {
				var pvid = $('#pv_id').val();
				$('#invoiceData').load(purl+'/'+curNum+'/'+pvid, function(result){ //.modal-body item
					$('#myModal').modal({show:true});
				});
			}
		} else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
			
		   var pvid = $('#pv_id').val();
		   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   $('#invoiceData').load(url+'/1/PV/'+pvid, function(result){ 
				$('#myModal').modal({show:true});
		   });
		
			/* var purl = "{{ url('purchase_invoice/get_invoice/') }}/"+$('#draccountid_'+curNum).val();
			var reid = $('#jeid_'+curNum).val();
			if((reid!='') && (this.value!='')) {
				$('#invoiceData').load(purl+'/'+curNum+'/'+this.value+'/'+reid, function(result){ //.modal-body item
					$('#myModal').modal({show:true});
				});
			} else {
				var pvid = $('#pv_id').val();
				$('#invoiceData').load(purl+'/'+curNum+'/'+pvid, function(result){ //.modal-body item
					$('#myModal').modal({show:true});
				});
			} */
		}
	});
	
	$(document).on('click', '.add-invoice', function(e)  { 

		var refs = [], amounts = [], types = [], invIds = [], billTypes = [], actAmts = [];
		$("input[name='tag[]']:checked").each(function(){
			var idx = this.id.split('_')[1];
			refs.push($('#refid_' + idx).val());
			amounts.push($('#lineamnt_' + idx).val());
			types.push($('#trtype_' + idx).val());
			invIds.push($('#sinvoiceid_' + idx).val());
			billTypes.push($('#billtype_' + idx).val());
			actAmts.push($('#hidamt_' + idx).val());
		});
  
		if (!refs.length){ alert('Please select at least one reference invoice.'); return; }

		var baseRow    = parseInt($('#bnum').val(), 10);
		var curRowNum  = parseInt($('#rowNum').val(), 10);
		var $container = $('.controls .itemdivPrnt');

		var drac = $('#draccount_'+baseRow).val();
		var dracid = $('#draccountid_'+baseRow).val();

		for (var i = 0; i < refs.length; i++){
			if (i > 0){
			var $current = $('.itemdivChld:last', $container);
			var $new     = $($current.clone()).appendTo($container);
			curRowNum++;
			$('#rowNum').val(curRowNum);

			$new.find('label.error').remove();
			$new.find('.has-error,.is-invalid,.is-valid').removeClass('has-error is-invalid is-valid');

			$new.find('[id]').each(function(){
				var nid = $(this).attr('id');
				if (nid) $(this).attr('id', nid.replace(/\d+$/, curRowNum));
			});
			$new.find('.classtrn').attr('id', 'trns_' + curRowNum);
			$new.find('.btn-remove-item').attr('data-id', 'rem_' + curRowNum);
			$new.find('.divchq').attr('id','chqdtl_' + curRowNum).hide();
			$new.find('.refdata').attr('id','refdata_' + curRowNum);
			}

			$('#ref_' + curRowNum).val(refs[i]);

			var invAmt = parseFloat(amounts[i]) || 0;
			if (!invAmt || invAmt <= 0) invAmt = parseFloat(actAmts[i]) || 0;
			$('#amount_' + curRowNum).val(invAmt ? invAmt.toFixed(2) : '');

			$('#invoiceid_' + curRowNum).val(invIds[i]);
			$('#biltyp_'   + curRowNum).val(billTypes[i]);
			$('#acnttype_' + curRowNum).html('<option value="' + types[i] + '">' + types[i] + '</option>');

			// copy account info from base row
			$('#draccount_'   + curRowNum).val($('#draccount_'   + baseRow).val());
			$('#draccountid_' + curRowNum).val($('#draccountid_' + baseRow).val());
			$('#groupid_'     + curRowNum).val($('#groupid_'     + baseRow).val());

		}

		getNetTotal();
		$('#reference_modal').modal('hide');

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
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#party_'+num).val( $(this).attr("data-name") );
		$('#partyac_'+num).val( $(this).attr("data-id") );
	});
	
});

function checkChequeNo(curNum) { 
	var chqno = $('#chkno_'+curNum).val();
	var oldcqno = $('#oldchkno_'+curNum).val();
	var bank = $('#bankid_'+curNum+' option:selected').val();
	var ac = $('#partyac_'+curNum).val();
	if(oldcqno != chqno) {
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
}

function getNetTotal() {
	var drLineTotal = 0; var crLineTotal = 0;
	$( '.jvline-amount' ).each(function() {
		var res = this.id.split('_');
		var curNum = res[1];
		if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' )
			drLineTotal = drLineTotal + parseFloat( (this.value=='')?0:this.value );
		else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' )
			crLineTotal = crLineTotal + parseFloat( (this.value=='')?0:this.value );
		//console.log('cr'+crLineTotal);
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

</script>

<script>
(function(){
  "use strict";

  /* ---------- utilities ---------- */
  function rowIdxFrom(container){ // container is .classtrn or any descendant
    var $row = $(container).closest('.classtrn');
    var id = $row.attr('id') || '';              // e.g. "trns_7"
    return id.split('_').pop();                  // "7"
  }
  function isPdcRow(idx){
    var g = $('#groupid_'+idx).val();
    return (g === 'PDCI' || g === 'PDCR' || g === 'BANK'); // PV needs bank details for PDCI; BANK rows may carry cheque fields
  }
  function customErrorPlacement(error, element){
    // place error after select2 UI, or near account_name for hidden account_id
    if (element.hasClass('select2-hidden-accessible') && element.next('.select2').length){
      error.insertAfter(element.next('.select2'));
      return;
    }
    var id = element.attr('id') || '';
    if (id.indexOf('draccountid_') === 0){
      var idx = id.split('_').pop();
      error.insertAfter($('#draccount_'+idx));
      return;
    }
    error.insertAfter(element);
  }
  function refreshButtons(){
    var $wrap = $('.controls .itemdivPrnt');
    var $rows = $wrap.find('.itemdivChld');
    $rows.find('.btn-add-item').hide();
    $rows.find('.btn-remove-item').show();
    if ($rows.length === 1){ $rows.find('.btn-remove-item').hide(); }
    $rows.last().find('.btn-add-item').show();
  }
  function totalsBalanced(){
    var dr = parseFloat($('#debit').val()||0);
    var cr = parseFloat($('#credit').val()||0);
    return Math.abs(dr - cr) < 0.00001;
  }

  /* ---------- validator setup ---------- */
  if (!$.validator){ return; }

 $.validator.addMethod('balanced', function(value, element){
	// Always recompute totals fresh before checking
	getNetTotal();

	var debit  = parseFloat($('#debit').val())  || 0;
	var credit = parseFloat($('#credit').val()) || 0;

	// Only enforce balance if there's at least one transaction row
	var hasTxn = $('.itemdivPrnt .classtrn').length > 0;
	if (!hasTxn) return true;

	return Math.abs(debit - credit) < 0.001;
	}, 'Debit and Credit totals must be equal.'
);


  var validator = $('#frmJournal').validate({
    ignore: [],
    errorClass: 'text-danger small',
    errorElement: 'label',
    errorPlacement: customErrorPlacement,
    highlight: function(el){ $(el).addClass('is-invalid'); },
    unhighlight: function(el){ $(el).removeClass('is-invalid'); },
    rules: {
      voucher_type: { required:true },
      voucher:      { required:true },
      voucher_no:   { required:true },
      voucher_date: { required:true },
      debit:  { number:true, balanced:true },
	  credit: { number:true, balanced:true }

    },
    messages: {
      voucher_type: "Please select voucher type",
      voucher:      "Please select voucher",
      voucher_no:   "Voucher number is required",
      voucher_date: "Voucher date is required"
    }
  });

  /* ---------- per-row rules ---------- */
  function addRulesForRow(idx){
    function add(sel, rules){ var $el = $(sel); if ($el.length){ $el.rules('remove'); $el.rules('add', rules); } }

    add('#draccountid_'+idx, { required:true, messages:{ required:'Account is required' }});
    add('#acnttype_'  +idx, { required:true, messages:{ required:'Type is required' }});
    add('#amount_'    +idx, { required:true, number:true, min:0.01,
                              messages:{ required:'Amount is required', min:'Amount must be > 0' }});
    add('#ref_'       +idx, { required:true, messages:{ required:'Reference is required' }});

    if (isPdcRow(idx)){
      // Only enforce cheque fields when visible rows actually have them
      if ($('#bankid_'+idx).length)  add('#bankid_'+idx, { required:true, messages:{ required:'Bank is required' }});
      if ($('#chkno_' +idx).length)  add('#chkno_' +idx, { required:true, messages:{ required:'Cheque no is required' }});
      if ($('#chkdate_'+idx).length) add('#chkdate_'+idx, { required:true, messages:{ required:'Cheque date is required' }});
      if ($('#party_' +idx).length)  add('#party_' +idx, { required:true, messages:{ required:'Party is required' }});
    } else {
      // ensure cheque rules removed if user changes account to non-PDC/BANK
      ['#bankid_','#chkno_','#chkdate_','#party_'].forEach(function(prefix){
        var $e = $(prefix+idx); if ($e.length) $e.rules('remove');
      });
    }
  }

  function validateRow(idx){
    addRulesForRow(idx);
    var ok = true;
    ['#draccountid_','#acnttype_','#amount_','#ref_'].forEach(function(p){
      var $el = $(p+idx); if ($el.length){ ok = validator.element($el) && ok; }
    });
    if (isPdcRow(idx)){
      ['#bankid_','#chkno_','#chkdate_','#party_'].forEach(function(p){
        var $el = $(p+idx); if ($el.length){ ok = validator.element($el) && ok; }
      });
    }
    // Force validation messages to show for all invalid inputs
	$('#trns_' + idx + ' :input:visible').each(function(){
	validator.element(this);
	});
	return ok;

  }

  function validateAllRows(){
    var allOk = true;
    $('.itemdivPrnt .classtrn').each(function(){
      var idx = (this.id||'').split('_').pop();
      if (!validateRow(idx)) allOk = false;
    });
    return allOk;
  }

  /* ---------- duplicate cheque (Bank+Cheque+Party) ---------- */
  function clearDupErrors(){
    $('.dup-error-label').remove();
    $('input[name="cheque_no[]"]').removeClass('is-invalid');
  }
  function checkDuplicateChequeTriplet(){
    clearDupErrors();
    var seen = {};
    var dupFound = false;

    $('.itemdivPrnt .classtrn').each(function(){
      var idx   = (this.id||'').split('_').pop();
      var bank  = ($('#bankid_'+idx).val()||'').trim();
      var chq   = ($('#chkno_' +idx).val()||'').trim();
      var party = ($('#partyac_'+idx).val()||'').trim();
      if (bank && chq && party){
        var key = bank+'|'+chq+'|'+party;
        if (seen[key]){
          dupFound = true;
          [seen[key], idx].forEach(function(i){
            var $t = $('#chkno_'+i);
            if ($t.length && !$t.next('.dup-error-label').length){
              $('<label class="text-danger small dup-error-label">Duplicate cheque for same bank & party</label>').insertAfter($t);
              $t.addClass('is-invalid');
            }
          });
        } else {
          seen[key] = idx;
        }
      }
    });
    return !dupFound;
  }

  /* ---------- hooks to your existing UI ---------- */

  // keep “+” only on last; already in your script, but ensure it runs after dom changes
  refreshButtons();
  $(document).on('click', '.btn-add-item, .btn-remove-item', function(){ setTimeout(refreshButtons, 0); });

  // validate last row before allowing add
  $(document).on('click', '.btn-add-item', function(e){
    var lastIdx = rowIdxFrom(this);
    if (!validateRow(lastIdx)){ e.preventDefault(); return false; }
  });

  // recompute balanced rule on amount/type changes
  $(document).on('keyup change', '.jvline-amount, .line-type', function(){
    // your existing getNetTotal() already updates totals
    $('#debit').valid(); $('#credit').valid();
  });

  // re-run duplicate cheque check when any cheque/bank/party changes
  $(document).on('keyup blur change', 'input[name="cheque_no[]"], select[name="bank_id[]"], input[name="party_name[]"]', function(){
    checkDuplicateChequeTriplet();
  });

  // attach rules to all existing rows on load
  $('.itemdivPrnt .classtrn').each(function(){
    var idx = (this.id||'').split('_').pop();
    addRulesForRow(idx);
  });

  // final submit — no alerts, only inline errors
$('#frmJournal').on('submit', function(e){
	clearDupErrors();

	// --- STEP 1: Force validation on every visible input in every row ---
	var allOk = true;
	$('.itemdivPrnt .classtrn').each(function(){
		var idx = (this.id || '').split('_').pop();
		if (!validateRow(idx)) {
		allOk = false;
		}
	});

	// --- STEP 2: Run form-level checks ---
	var ok = $(this).valid();
	ok = allOk && ok;
	ok = checkDuplicateChequeTriplet() && ok;

	// --- STEP 3: Check if totals balance ---
	getNetTotal();
	if (!totalsBalanced()) {
		$('#difference').next('.error-balance').remove();
		$('<label class="text-danger small error-balance">Debit and Credit must balance</label>').insertAfter('#difference');
		ok = false;
	}

	// --- STEP 4: Stop submission if anything invalid ---
	if (!ok) {
		e.preventDefault();
		// optional: scroll to first error
		$('html, body').animate({ scrollTop: $('.text-danger:first').offset().top - 80 }, 400);
		$('.is-invalid:visible:first').focus();
		return false;
	}

	// --- STEP 5: Submit only when all rules pass ---
	return true;
	});


})();
</script>



@stop
