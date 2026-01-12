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
<style>
.error-field {
  border-color: #d9534f !important;
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px #ce8483 !important;
}

.has-error .form-control {
  border-color: #d9534f;
}

.text-danger {
  color: #d9534f;
  font-size: 12px;
  display: block;
  margin-top: 2px;
  padding-left: 5px;
}

.select2-container.has-error .select2-selection {
  border-color: #d9534f !important;
}

.form-group.has-error {
  margin-bottom: 5px;
}

/* Ensure proper spacing for row fields */
.classtrn .col-xs-1,
.classtrn .col-xs-2,
.classtrn .col-xs-3 {
  margin-bottom: 5px;
}

.classtrn .form-control {
  margin-bottom: 2px;
}
</style>
{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Receipt Voucher
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
                    <a href="#">Receipt Voucher</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i>  Edit Receipt Voucher
                            </h3>
                           <div class="pull-right">
								@can('rv-print')
								 <a href="{{ url('customer_receipt/print2/'.$crrow->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
							@php $ispdc = false; @endphp
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('customer_receipt/update/'.$crrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="from_jv" value="1">
								<input type="hidden" name="from_rv" value="1">
								<input type="hidden" name="rv_id" id="rv_id" value="{{$crrow->id}}">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="{{$crrow->voucher_type}}">RV - Receipt Voucher</option>
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
                                    <label for="input-text" class="col-sm-2 control-label">RV. No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" value="{{$crrow->voucher_no}}" readonly name="voucher_no">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">RV. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" value="{{date('d-m-Y', strtotime($crrow->voucher_date))}}" data-language='en'  id="voucher_date" placeholder="Voucher Date" autocomplete="off"/>
                                    </div>
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Transactions</h5></legend>
										{{--*/ $i = 0; $num = count($invoicerow); /*--}}
										<input type="hidden" id="rowNum" value="{{$num}}">
										<input type="hidden" id="remitem" name="remove_item">
										<div class="itemdivPrnt">
										@foreach($invoicerow as $item)
										{{--*/ $i++; /*--}}
										
											<div class="itemdivChld">							
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_{{$i}}">
												@if($item->category=='PDCR')
													@php $ispdc = true; @endphp
													@if($isdept)
													<div class="col-xs-1 nopdc" style="width:11%;"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="text" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}"> 
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-2" style="width:11%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2" style="width:9%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
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
													<div class="col-xs-1" style="width:10%;">
														<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
													</div>
													<div class="col-xs-1" style="width:8%;"> 
														<span class="small">Job</span> 
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Bank</span> 
														<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
														<option value="">Select Bank...</option>
															@foreach($banks as $bank)
															<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code']}}</option>
															@endforeach
														</select>
													</div>

													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
														<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
													</div>
													
													
													
													<div class="col-xs-1 pdcfm" style="width:8%;">
														<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_id}}">
														<span class="small">Pty. Name</span> <input type="text" id="party_{{$i}}" autocomplete="off" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
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
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													@else <!-- PDC W/O DEPARTMENT -->
													<div class="col-xs-1 nopdc1" style="width:12%;"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}">
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-2 nopdc2" style="width:12%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2 nopdc3" style="width:10%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
														</div>
														<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
													</div>
													<div class="col-xs-1 nopdc4" style="width:7%;">
														<span class="small">Type</span> 
														<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
															<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
														</select>
													</div>
													<div class="col-xs-1 nopdc5" style="width:10%;">
														<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
													</div>
													

													<div class="col-xs-1 pdcfm" style="width:9%;">
														<span class="small">Bank</span> 
														<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
														<option value="">Select Bank...</option>
															@foreach($banks as $bank)
															<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code']}}</option>
															@endforeach
														</select>
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
														<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:9%;">
														<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
													</div>
													
													
													
													<div class="col-xs-1 pdcfm" style="width:9%;">
														<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_id}}">
														<span class="small">Pty. Name</span> <input type="text" id="party_{{$i}}" autocomplete="off" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
													</div>
													<div class="col-xs-1 nopdc6" style="width:9%;"> 
														<span class="small">Job</span> 
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</div>
													
													<div class="col-xs-1 abc" style="width:3%;"><br/>
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													@endif  <!--PDC W/O DEPARTMENT ENDIF-->
													
												@elseif($item->category=='BANK') <!--BANK W/O DEPARTMENT ENDIF-->
													@if($isdept)
													<div class="col-xs-1 nopdc" style="width:18%;"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="text" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}"> 
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-2" style="width:13%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2" style="width:12%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
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
													<div class="col-xs-1" style="width:10%;">
														<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
													</div>
													<div class="col-xs-1" style="width:8%;"> 
														<span class="small">Job</span> 
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</div>

													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
														<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:8%;">
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
													
													<div class="col-xs-1 abc" style="width:3%;"><br/>
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													@else <!-- BANK W/O DEPARTMENT -->
													<div class="col-xs-1 nopdc1" style="width:18%;"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}">
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-2 nopdc2" style="width:18%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2 nopdc3" style="width:12%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
														</div>
														<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
													</div>
													<div class="col-xs-1 nopdc4" style="width:7%;">
														<span class="small">Type</span> 
														<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
															<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
														</select>
													</div>
													<div class="col-xs-1 nopdc5" style="width:10%;">
														<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
													</div>
													

													<div class="col-xs-1 pdcfm" style="width:8%;">
														<span class="small">Chq. No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
														<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
													</div>
													
													<div class="col-xs-1 pdcfm" style="width:9%;">
														<span class="small">Chq. Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" autocomplete="off" class="form-control chqdate" data-language='en'>
													</div>
													
												
													<div class="col-xs-1 nopdc6" style="width:14%;"> 
														<span class="small">Job</span> 
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</div>
													
													<div class="col-xs-1 abc" style="width:3%;"><br/>
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													@endif  <!--BANK W/O DEPARTMENT ENDIF-->	
													
													
												@else  <!--PDC ELSE (CASH)-->
													@if($isdept) <!-- CASH DEPARTMENT -->
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}"> 
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-3" style="width:20%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2" style="width:12%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
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
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													@else  <!-- CASH DEPARTMENT ELSE -->
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}">
														<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
														<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
														
														<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}" value="{{$item->bill_type}}"> 
														<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->tr_entry_id}}">
													</div>
													
													<div class="col-xs-3" style="width:20%;">
														<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->description}}" name="description[]" class="form-control">
													</div>
													<div class="col-xs-2" style="width:14%;">
														<span class="small">Reference</span> 
														<div id="refdata_{{$i}}" class="refdata">
														<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" class="form-control <?php if($item->entry_type=='Cr') echo 'ref-invoice'; ?>" <?php if($item->entry_type=='Cr') { ?>data-toggle="modal" data-target="#reference_modal" <?php } ?>>
														</div>
														<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="{{$item->sales_invoice_id}}">
														<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->amount}}">
													</div>
													<div class="col-xs-1" style="width:8%;">
														<span class="small">Type</span> 
														<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
															<option value="{{$item->entry_type}}">{{$item->entry_type}}</option>
															<option value="{{($item->entry_type=='Cr')?'Dr':'Cr'}}">{{($item->entry_type=='Cr')?'Dr':'Cr'}}</option>
														</select>
													</div>
													<div class="col-xs-2" style="width:12%;">
														<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
													</div>
													<div class="col-sm-2" style="width:13%;"> 
														<span class="small">Job</span> 
														<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</div>
													@if($item->category=='CUSTOMER')
													<div id="salem_1"class="col-sm-2 salem" style="width:11%;"> 
															<span class="small">Salesman</span> 
														<input type="hidden" name="salesman_idd[]" id="salesmanid_{{$i}}" value="{{$item->salesman_id}}" >
														<input type="text" id="salesman_{{$i}}" autocomplete="off" name="salesman[]" class="form-control"  value="{{$item->salesman}}" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
															
														</div>
														@else
														<input type="hidden" name="salesman_idd[]" id="salesmanid_{{$i}}" >
													@endif
													<div class="col-xs-1 abc" style="width:3%;"><br/>
														<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
													</div>
													<input type="hidden" name="department[]" id="dept_{{$i}}">
													@endif
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
																<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_id}}">
																<span class="small">Party Name</span> <input type="text" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
															</div>
														</div>
												@endif		
												</div>
												
											</div>
										@endforeach
										</div>
								</fieldset>
								
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
								
							</div>
                        </div>
                    </div>
                </div>
            </div>
   
        </section>
@stop

{{-- page level scripts --}}

@section('footer_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

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

// === Flags ===
var IS_PDC = {{ (isset($ispdc) && $ispdc) ? 'true' : 'false' }};
var rowNum = {{ $num }};

// ===================== Validation Setup =====================
(function setupValidation() {

    // Custom rule: DR = CR
    jQuery.validator.addMethod("balanced", function(value, element) {
        var dr = parseFloat($('#debit').val() || 0);
        var cr = parseFloat($('#credit').val() || 0);
        return Math.abs(dr - cr) < 0.00001;
    }, "Debit and Credit must be equal.");

    $('#frmJournal').validate({
    // ✅ Don’t ignore hidden elements inside table rows
    ignore: [],
    errorClass: 'text-danger',
    errorElement: 'span',
    focusInvalid: false,

    rules: {
        voucher_date: { required: true },
        'account_name[]': { required: true },
        'reference[]': { required: true },
        'line_amount[]': { required: true, number: true, min: 0.01 },
        debit: { balanced: true },
        credit: { balanced: true }
    },
    messages: {
        voucher_date: "Voucher date is required.",
        'account_name[]': "Account name is required.",
        'reference[]': "Reference is required.",
        'line_amount[]': {
            required: "Amount is required.",
            number: "Please enter a valid number.",
            min: "Amount must be greater than 0"
        }
    },

    // ✅ Corrected errorPlacement to show messages in correct cell
    errorPlacement: function (error, element) {
        var $row = element.closest('.classtrn');

        // Select2 fields — place after visible container
        if (element.hasClass('select2-hidden-accessible') && element.next('.select2').length) {
            error.insertAfter(element.next('.select2'));
            return;
        }

        // If row exists, put message right below the element
        if ($row.length) {
            error.insertAfter(element);
            return;
        }

        // fallback default
        error.insertAfter(element);
    },

    highlight: function (element) {
        $(element).closest('.form-group, .classtrn').addClass('has-error');
        $(element).addClass('error-field');
    },
    unhighlight: function (element) {
        $(element).closest('.form-group, .classtrn').removeClass('has-error');
        $(element).removeClass('error-field');
    },

    invalidHandler: function (event, validator) {
        // Force redraw for select2 errors
        $('.select2').each(function () {
            $(this).next('.select2').find('.select2-selection').addClass('error-field');
        });
    },

    submitHandler: function (form) {
        var dr = parseFloat($('#debit').val() || 0);
        var cr = parseFloat($('#credit').val() || 0);
        if (Math.abs(dr - cr) > 0.00001) {
            $('#debit').valid();
            $('#credit').valid();
            return false;
        }
        if (!validatePDCFields()) return false;
        form.submit();
    }
});


    // Apply validation per row
    function applyRowValidation(rowNum) {
        $('#draccount_' + rowNum).rules('remove');
        $('#ref_' + rowNum).rules('remove');
        $('#amount_' + rowNum).rules('remove');
        $('#bankid_' + rowNum).rules('remove');
        $('#chkno_' + rowNum).rules('remove');
        $('#chkdate_' + rowNum).rules('remove');
        $('#party_' + rowNum).rules('remove');

        $('#draccount_' + rowNum).rules('add', {
            required: true, messages: { required: "Account name is required" }
        });
        $('#ref_' + rowNum).rules('add', {
            required: true, messages: { required: "Reference is required" }
        });
        $('#amount_' + rowNum).rules('add', {
            required: true, number: true, min: 0.01,
            messages: {
                required: "Amount is required",
                number: "Please enter a valid number",
                min: "Amount must be greater than 0"
            }
        });

        applyPDCValidation(rowNum);
    }

    // Apply PDC rules
    function applyPDCValidation(rowNum) {
        var group = $('#groupid_' + rowNum).val();
        if (group === 'PDCR' || group === 'PDCI') {
            $('#bankid_' + rowNum).rules('add', { required: true, messages: { required: "Bank is required for PDC" } });
            $('#chkno_' + rowNum).rules('add', { required: true, messages: { required: "Cheque no is required for PDC" } });
            $('#chkdate_' + rowNum).rules('add', { required: true, messages: { required: "Cheque date is required for PDC" } });
            $('#party_' + rowNum).rules('add', { required: true, messages: { required: "Party name is required for PDC" } });
        } else {
            $('#bankid_' + rowNum).rules('remove', 'required');
            $('#chkno_' + rowNum).rules('remove', 'required');
            $('#chkdate_' + rowNum).rules('remove', 'required');
            $('#party_' + rowNum).rules('remove', 'required');
        }
    }

    // Validate all existing rows
    function applyValidationToAllRows() {
        $('.itemdivPrnt .itemdivChld').each(function() {
            var rowId = $(this).find('.classtrn').attr('id');
            if (rowId) {
                var rowNum = rowId.split('_')[1];
                applyRowValidation(rowNum);
            }
        });
    }

    // Validate PDC before submit
    function validatePDCFields() {
        var isValid = true;
        var errorMessages = [];
        $('.itemdivPrnt .itemdivChld').each(function(index) {
            var rowNum = $(this).find('.classtrn').attr('id').split('_')[1];
            var group = $('#groupid_' + rowNum).val();
            if (group === 'PDCR' || group === 'PDCI') {
                if (!$('#bankid_' + rowNum).val()) { errorMessages.push("Row " + (index+1) + ": Bank is required"); isValid = false; }
                if (!$('#chkno_' + rowNum).val()) { errorMessages.push("Row " + (index+1) + ": Cheque no is required"); isValid = false; }
                if (!$('#chkdate_' + rowNum).val()) { errorMessages.push("Row " + (index+1) + ": Cheque date is required"); isValid = false; }
                if (!$('#party_' + rowNum).val()) { errorMessages.push("Row " + (index+1) + ": Party name is required"); isValid = false; }
            }
        });
        if (!isValid && errorMessages.length > 0) {
            alert("Please fix the following:\n\n" + errorMessages.join('\n'));
        }
        return isValid;
    }

    $(document).ready(function() {
        applyValidationToAllRows();
    });

    // Expose globally
    window.addRulesForRow = applyRowValidation;
    window.applyPDCValidation = applyPDCValidation;
    window.validatePDCFields = validatePDCFields;

})(); // end setupValidation
// ===================== PAGE INIT & VOUCHER HOOKS =====================
$(document).ready(function () {

    // Keep existing button visibility logic
    $('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
    if ($('.itemdivPrnt').children().length == 1) {
        $('.itemdivPrnt').find('.btn-remove-item').hide();
    }

    // Currency toggles (retained)
    $("#chequeInput").toggle();
    $("#currency_rate").prop('disabled', true);
    $("#currency_id").prop('disabled', true);

    $('.custom_icheck').on('ifChecked', function() {
        $("#currency_id, #currency_rate").prop('disabled', false);
    });
    $('.custom_icheck').on('ifUnchecked', function() {
        $("#currency_id, #currency_rate").prop('disabled', true);
        $('#amount_fc').val(''); $("#currency_rate").val('');
    });

    // Voucher date picker (retained)
    $('#voucher_date').datepicker({ autoClose:true, dateFormat: 'dd-mm-yyyy' });

    // Voucher type -> vouchers list
    $('#voucher_type').on('change', function(e){
        $('#voucher_no').val('');
        var vchr_id = e.target.value;
        $.get("{{ url('journal/getvouchertype/') }}/" + vchr_id, function(data) {
            $('#voucher').empty().append('<option value="">Select Voucher...</option>');
            $.each(data, function(_, display){
                $('#voucher').append('<option value="' + display.id + '">' + display.name + '</option>');
            });
        });
    });

    // Voucher -> voucher number
    $('#voucher').on('change', function(e){
        var vchr_id = e.target.value;
        $.get("{{ url('journal/getvoucher/') }}/" + vchr_id, function(data) {
            $('#voucher_no').val(data);
        });
    });

});

// ===================== ADD / REMOVE ROWS =====================
$(function(){

    // Prevent scroll increment on number inputs
    $(':input[type=number]').on('mousewheel',function(){ $(this).blur(); });

    // -------- Add Row ----------
    $(document).on('click', '.btn-add-item', function(e)  {
        e.preventDefault();

        rowNum++;
        $('#rowNum').val(rowNum);

        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        // Re-ID + clear inputs/selects for cloned row
        newEntry.find('input, select').each(function() {
            var id = $(this).attr('id');
            if (id) {
                var base = id.split('_')[0];
                $(this).attr('id', base + '_' + rowNum);
            }
            var name = $(this).attr('name');
            if (name && name.indexOf('[]') !== -1) {
                $(this).val('');
            }
        });

        // Reset specific hidden fields used by your logic
        newEntry.find('input[name="account_id[]"]').val('');
        newEntry.find('input[name="job_id[]"]').val('');
        newEntry.find('input[name="sales_invoice_id[]"]').val('');
        newEntry.find('input[name="tr_id[]"]').val('');
        newEntry.find('input[name="bill_type[]"]').val('');
        newEntry.find('input[name="actual_amount[]"]').val('');
        newEntry.find('input[name="oldcheque_no[]"]').val('');
        newEntry.find('input[name="partyac_id[]"]').val('');
        newEntry.find('input[name="salesman_idd[]"]').val('');

        // Update container IDs
        newEntry.find('.refdata').attr('id', 'refdata_' + rowNum);
        newEntry.find('.divchq').attr('id', 'chqdtl_' + rowNum);
        newEntry.find('.salem').attr('id', 'salem_' + rowNum);
        newEntry.find('.classtrn').attr('id', 'trns_' + rowNum);

        // Clear visuals
        newEntry.find('input[type="text"]').val('');
        newEntry.find('input[type="number"]').val('');
        newEntry.find('select').val('').trigger('change');

        // Button visibility
        controlForm.find('.btn-add-item').hide();
        controlForm.find('.btn-remove-item').show();
        newEntry.find('.btn-add-item').show();

        // Re-init datepicker on cloned PDC date fields
        newEntry.find('.chqdate').datepicker({
            language: 'en',
            autoClose:true,
            dateFormat: 'dd-mm-yyyy'
        });

        // ✅ Safe Select2 init to avoid "select2 is not a function"
        if ($.fn.select2) {
            newEntry.find('.select2').select2();
        }

        // ✅ Apply validation rules to the new row
        setTimeout(function() {
            if (typeof window.addRulesForRow === 'function') {
                window.addRulesForRow(rowNum);
            }
        }, 100);
    });

    // -------- Remove Row ----------
    $(document).on('click', '.btn-remove-item', function(e) {
        e.preventDefault();

        // ✅ Safe fallback for missing data-id (fixes .split error)
        var dataId = $(this).attr('data-id');
        if (!dataId) {
            dataId = $(this).closest('.classtrn').attr('id') || '';
        }
        var res = dataId.split('_');
        var curNum = res[1] || 0;

        var remitem = $('#remitem').val();
        var ids = (remitem=='') ? $('#jeid_'+curNum).val() : remitem+','+$('#jeid_'+curNum).val();
        $('#remitem').val(ids);

        // Remove the row
        $(this).parents('.itemdivChld:first').remove();

        // Recalc totals
        getNetTotal();

        // Buttons visibility refresh
        var $rows = $('.itemdivPrnt .itemdivChld');
        $rows.find('.btn-add-item').hide();
        $rows.last().find('.btn-add-item').show();
        if ($rows.length == 1) {
            $rows.find('.btn-remove-item').hide();
        } else {
            $rows.find('.btn-remove-item').show();
        }

        // Re-validate form after removal
        $('#frmJournal').valid();

        return false;
    });

    // ========== Real-time validation triggers ==========
    $(document).on('blur', 'input[name="account_name[]"]', function() { $(this).valid(); });
    $(document).on('blur', 'input[name="reference[]"]',    function() { $(this).valid(); });
    $(document).on('blur', 'input[name="line_amount[]"]',  function() {
        $(this).valid();
        getNetTotal();
        $('#debit').valid();
        $('#credit').valid();
    });
    $(document).on('change', 'select[name="bank_id[]"]',   function() { $(this).valid(); });
    $(document).on('blur', 'input[name="cheque_no[]"]',    function() { $(this).valid(); });
    $(document).on('blur', 'input[name="cheque_date[]"]',  function() { $(this).valid(); });
    $(document).on('blur', 'input[name="party_name[]"]',   function() { $(this).valid(); });
    $(document).on('blur', '#voucher_date',                function() { $(this).valid(); });

    // When changing Dr/Cr, refresh totals and balance check
    $(document).on('change', '.line-type', function() {
        getNetTotal();
        $('#debit').valid();
        $('#credit').valid();
    });

    // Extra PDC live checks (kept)
    $(document).on('change', 'select[name="bank_id[]"]', function() {
        var r = this.id.split('_')[1];
        var g = $('#groupid_' + r).val();
        if (g === 'PDCR' || g === 'PDCI') $(this).valid();
    });
    $(document).on('blur', 'input[name="cheque_no[]"]', function() {
        var r = this.id.split('_')[1];
        var g = $('#groupid_' + r).val();
        if (g === 'PDCR' || g === 'PDCI') $(this).valid();
    });
    $(document).on('blur', 'input[name="cheque_date[]"]', function() {
        var r = this.id.split('_')[1];
        var g = $('#groupid_' + r).val();
        if (g === 'PDCR' || g === 'PDCI') $(this).valid();
    });
    $(document).on('blur', 'input[name="party_name[]"]', function() {
        var r = this.id.split('_')[1];
        var g = $('#groupid_' + r).val();
        if (g === 'PDCR' || g === 'PDCI') $(this).valid();
    });

});
// ===================== MODAL LOADERS & ACCOUNT LINKS =====================

// --- Account Selection Modal ---
var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="account_name[]"]', function () {
    var res = this.id.split('_');
    var curNum = res[1];
    $('#account_data').load(acurl + '/' + curNum, function () {
        $('#account_modal').modal({ show: true });
    });
});

$(document).on('click', '#account_modal .custRow', function (e) {
    var num = $('#num').val();
    $('#draccount_' + num).val($(this).attr("data-name"));
    $('#draccountid_' + num).val($(this).attr("data-id"));
    $('#groupid_' + num).val($(this).attr("data-group"));

    // Apply PDC validation if needed
    if (typeof window.applyPDCValidation === 'function') {
        window.applyPDCValidation(num);
    }

    var grp = $(this).attr("data-group");
    var trnurl = "{{ url('journal/set_transactions/') }}";

    // --- PDCR and PDCI ---
    if (grp === 'PDCR' || grp === 'PDCI') {
        $('#acnttype_' + num).empty().append('<option value="' + (grp === 'PDCR' ? 'Dr' : 'Cr') + '">' + (grp === 'PDCR' ? 'Dr' : 'Cr') + '</option>');
        var mode = 'PDC';
        var id = $(this).attr("data-id");
        var jeid = $('#jeid_' + num).val();
        if (jeid)
            $('#trns_' + num).load(trnurl + '/' + mode + '/' + id + '/' + num + '/' + jeid);
        else
            $('#trns_' + num).load(trnurl + '/' + mode + '/' + id + '/' + num);

    } else if (grp === 'BANK') {
        // --- BANK ---
        var id = $(this).attr("data-id");
        var jeid = $('#jeid_' + num).val();
        if (jeid)
            $('#trns_' + num).load(trnurl + '/BANK/' + id + '/' + num + '/' + jeid);
        else
            $('#trns_' + num).load(trnurl + '/BANK/' + id + '/' + num);

    } else {
        // --- CASH / CUSTOMER / SUPPLIER etc ---
        if (grp === 'CUSTOMER') {
            if ($('#salem_' + num).is(":hidden")) $('#salem_' + num).toggle();
            $('#salesmanid_' + num).val($(this).attr("data-salesmanid"));
            $('#salesman_' + num).val($(this).attr("data-salesman"));
        }

        $('#acnttype_' + num).empty().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
        $('#trns_' + num + ' .pdcfm').hide();

        // reset column widths for non-PDC rows
        $('#trns_' + num + ' .nopdc1').attr("style", "width:17%;");
        $('#trns_' + num + ' .nopdc2').attr("style", "width:25%;");
        $('#trns_' + num + ' .nopdc3').attr("style", "width:15%;");
        $('#trns_' + num + ' .nopdc4').attr("style", "width:8%;");
        $('#trns_' + num + ' .nopdc5').attr("style", "width:13%;");
        $('#trns_' + num + ' .nopdc6').attr("style", "width:17%;");

        getNetTotal();
    }

    // Reference field type update 
    if (grp === 'SUPPLIER' || grp === 'CUSTOMER') {
        $('#refdata_' + num).html('<input type="text" id="ref_' + num + '" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
    } else {
        $('#refdata_' + num).html('<input type="text" id="ref_' + num + '" name="reference[]" class="form-control">');
    }

    $('#draccount_' + num).valid();
});

// ===================== JOB MODAL =====================
var joburl = "{{ url('jobmaster/job_data/') }}";
$(document).on('click', 'input[name="jobcod[]"]', function () {
    var res = this.id.split('_');
    var curNum = res[1];
    $('#jobData').load(joburl + '/' + curNum, function () {
        $('#job_modal').modal({ show: true });
        $('.input-sm').focus();
    });
});

$(document).on('click', '#job_modal .jobRow', function (e) {
    var num = $('#num').val();
    $('#jobcod_' + num).val($(this).attr("data-cod"));
    $('#jobid_' + num).val($(this).attr("data-id"));
    e.preventDefault();
});

// ===================== SALESMAN MODAL =====================
var supurl = "{{ url('sales_invoice/salesman_data/') }}";
$(document).on('click', 'input[name="salesman[]"]', function () {
    var res = this.id.split('_');
    var curNum = res[1];
    $('#salesmanData').load(supurl + '/' + curNum, function () {
        $('#salesman_modal').modal({ show: true });
    });
});

$(document).on('click', '#salesman_modal .salesmanRow', function (e) {
    var num = $('#num').val();
    $('#salesman_' + num).val($(this).attr("data-name"));
    $('#salesmanid_' + num).val($(this).attr("data-id"));
    e.preventDefault();
});

// ===================== PARTY ACCOUNT MODAL =====================
var acurlall = "{{ url('account_master/get_account_all/') }}";
$(document).on('click', 'input[name="party_name[]"]', function () {
    var res = this.id.split('_');
    var curNum = res[1];
    $('#paccount_data').load(acurlall + '/' + curNum, function () {
        $('#paccount_modal').modal({ show: true });
    });
});

$(document).on('click', '#paccount_data .custRow', function (e) {
    var num = $('#anum').val();
    $('#party_' + num).val($(this).attr("data-name"));
    $('#partyac_' + num).val($(this).attr("data-id"));
    checkChequeNo(num);
    $('#party_' + num).valid();
});

// ===================== REFERENCE MODAL =====================
$(document).on('click', '.ref-invoice', function () {
    var res = this.id.split('_');
    var curNum = res[1];

    if ($('#groupid_' + curNum).val() == 'CUSTOMER') {
        var rvid = $('#rv_id').val();
        var url = "{{ url('account_enquiry/os_bills/') }}/" + $('#draccountid_' + curNum).val();
        $('#invoiceData').load(url + '/' + curNum + '/RV/' + rvid, function () {
            $('#reference_modal').modal({ show: true });
        });
    } else if ($('#groupid_' + curNum).val() == 'SUPPLIER') {
        var url = "{{ url('purchase_invoice/get_invoice/') }}/" + $('#draccountid_' + curNum).val();
        var reid = $('#jeid_' + curNum).val();
        if ((reid != '') && (this.value != '')) {
            $('#invoiceData').load(url + '/' + curNum + '/' + this.value + '/' + reid, function () {
                $('#reference_modal').modal({ show: true });
            });
        } else {
            var rvid = $('#rv_id').val();
            $('#invoiceData').load(url + '/' + curNum + '/' + rvid, function () {
                $('#reference_modal').modal({ show: true });
            });
        }
    }
});

// ===================== CHECK CHEQUE NUMBER =====================
function checkChequeNo(curNum) {
    var chqno = $('#chkno_' + curNum).val();
    var oldcqno = $('#oldchkno_' + curNum).val();
    var bank = $('#bankid_' + curNum + ' option:selected').val();
    var ac = $('#partyac_' + curNum).val();

    if (oldcqno != chqno) {
        $.ajax({
            url: "{{ url('account_master/check_chequeno/') }}",
            type: 'get',
            data: 'chqno=' + encodeURIComponent(chqno) + '&bank_id=' + encodeURIComponent(bank) + '&ac_id=' + encodeURIComponent(ac),
            success: function (data) {
                if (data == '') {
                    alert('Cheque no is duplicate!');
                    $('#chkno_' + curNum).val('');
                    $('#chkno_' + curNum).valid();
                }
            }
        });
    }
}

// ===================== NET TOTAL CALCULATION =====================
function getNetTotal() {
    var drLineTotal = 0, crLineTotal = 0;
    $('.itemdivPrnt .jvline-amount').each(function () {
        var res = this.id.split('_');
        var curNum = res[1];
        if (this.value != '') {
            if ($('#acnttype_' + curNum + ' option:selected').val() == 'Dr')
                drLineTotal += parseFloat(this.value || 0);
            else if ($('#acnttype_' + curNum + ' option:selected').val() == 'Cr')
                crLineTotal += parseFloat(this.value || 0);
        }
    });

    var difference = drLineTotal - crLineTotal;
    $("#debit").val(drLineTotal.toFixed(2));
    $("#credit").val(crLineTotal.toFixed(2));
    $("#difference").val(difference.toFixed(2));

    if ($("#is_fc").prop('checked') == true && $('#currency_rate').val() != '') {
        var amount = parseFloat($('#amount').val() || 0);
        var amount_fc = parseFloat($('#currency_rate').val()) * amount;
        $('#amount_fc').val(amount_fc.toFixed(2));
    }
}
</script>


@stop
