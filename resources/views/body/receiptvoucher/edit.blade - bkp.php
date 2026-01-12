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
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i>  Edit Receipt Voucher
                            </h3>
                           <div class="pull-right">
								@permission('rv-print')
								 <a href="{{ url('customer_receipt/print2/'.$crrow->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
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

<script>

//$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$(document).ready(function () {
	
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	    	
	$("#chequeInput").toggle();
			
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
var supurl = "{{ url('sales_invoice/salesman_data/') }}";
	$(document).on('click', 'input[name="salesman[]"]', function(e) {
	    var res = this.id.split('_');
		var curNum = res[1];
		$('#salesmanData').load(supurl+'/'+curNum, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#salesman_modal .salesmanRow', function(e) {
		 var num =$('#num').val();
		$('#salesman_'+num).val($(this).attr("data-name"));
		$('#salesmanid_'+num).val($(this).attr("data-id"));
		e.preventDefault();
	});
	
$(function(){
	var rowNum = $('#rowNum').val();
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  //$('#chqdtl_1').toggle();
  
//ED12 
 $(document).on('click', '.btn-add-item', function(e)  { 
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
			newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
			//newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
			 newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
			newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
			newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
			newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
			newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
			newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
			newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rowNum);
			newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum);
			newEntry.find($('input[name="oldcheque_no[]"]')).attr('id', 'oldchkno_' + rowNum);

			newEntry.find($('input[name="department[]"]')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
			newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
			newEntry.find($('.btn-remove-item')).attr('data-id','rem_'+rowNum);
			
			newEntry.find($('input[name="salesman_idd[]"]')).attr('id', 'salesmanid_' + rowNum);
			newEntry.find($('input[name="salesman[]"]')).attr('id', 'salesman_' + rowNum);
            newEntry.find($('.salem')).attr('id', 'salem_' + rowNum);
			
			var des = $('input[name="description[]"]').val();
			newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
			$('#ref_'+rowNum).val(''); $('#amount_'+rowNum).val(''); 
			$('#jeid_'+rowNum).val('');

			$('#draccount_'+rowNum).val('');
			$('#draccountid_'+rowNum).val('');
			  $('#salesman_'+rowNum).val('');
			$('#salesmanid_'+rowNum).val('');
			//newEntry.find('input').val('');  cheque_date[]
			newEntry.find($('input[name="description[]"]')).val(des);
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
             if( $('#salem_'+rowNum).is(":visible") ) 
                 $('#salem_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show()
			
			newEntry.find( $('.chqdate').datepicker({
				language: 'en',
				autoClose:true,
				dateFormat: 'dd-mm-yyyy'
			}) );
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
	
	$('.chqdate').datepicker({
		language: 'en',
		autoClose:true,
		dateFormat: 'dd-mm-yyyy'
	});
	
	////ED12
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
			$('#myModal').modal({show:true});
		});
	});
	
	//new change.................
	$(document).on('click', '#account_modal .custRow', function(e) { //.accountRow
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
			    $('#trns_'+num).load( trnurl+'/BANK/'+$(this).attr("data-id")+'/'+num+'/'+$('#jeid_'+num).val() );
			else
			    $('#trns_'+num).load( trnurl+'/BANK/'+$(this).attr("data-id")+'/'+num );
			
		} else {
		    
		    if($(this).attr("data-group")=='CUSTOMER'){
		        if( $('#salem_'+num).is(":hidden") )
				$('#salem_'+num).toggle();
				
		    	$('#salesmanid_'+num).val( $(this).attr("data-salesmanid") );
	        	$('#salesman_'+num).val( $(this).attr("data-salesman") );
		    }
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
			
			
		}
		
		if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER') {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
		} else {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control">');
		}
	});
	
	
	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		//var chqno = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		checkChequeNo(curNum);
	});
	
	//ED12
	$(document).on('click', '.ref-invoice', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		if( $('#groupid_'+curNum).val()=='CUSTOMER') { //customer type.............
		
			   var rvid = $('#rv_id').val();
			   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
			   $('#invoiceData').load(url+'/'+curNum+'/RV/'+rvid, function(result){ 
					$('#myModal').modal({show:true});
			   });
			
		} else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
			var url = "{{ url('purchase_invoice/get_invoice/') }}/"+$('#draccountid_'+curNum).val();
			var reid = $('#jeid_'+curNum).val();
			if((reid!='') && (this.value!='')) {
				$('#invoiceData').load(url+'/'+curNum+'/'+this.value+'/'+reid, function(result){
					$('#myModal').modal({show:true});
				});
			} else {
				var rvid = $('#rv_id').val();
				$('#invoiceData').load(url+'/'+curNum+'/'+rvid, function(result){ 
					$('#myModal').modal({show:true});
				});
			}
		}
	});
	
	//ED12
	$(document).on('click', '.add-invoice', function(e)  { 
	
		var refs = []; var amounts = []; var type = []; var ids = []; var actamt = []; var btype = []; var nwar = []; var inv = [];
		$("input[name='tag[]']:checked").each(function() { 
			if(this.className=='tag-line-nw') {
				nwar.push( $('#refid_'+curNum).val() );
				//console.log('hh'+nwar);
			}
			var res = this.id.split('_');
			var curNum = res[1];
			ids.push($(this).val());
			refs.push( $('#refid_'+curNum).val() );
			amounts.push( $('#lineamnt_'+curNum).val() );
			type.push( $('#trtype_'+curNum).val() );
			actamt.push( $('#hidamt_'+curNum).val() );
			btype.push( $('#billtype_'+curNum).val() );
			inv.push($('#sinvoiceid_'+curNum).val());
		});
		
		var no = $('#bnum').val(); //var rowNum;
		rowNum = parseInt(no);
		var rnum = $('#rowNum').val();
		var j = 0;//rowNum-1; 
		
		$.each(refs,function(i) { //console.log(i);
			if(j>0) {  //console.log('j'+j);
				var controlForm = $('.controls .itemdivPrnt'),
					currentEntry = $('.btn-add-item').parents('.itemdivChld:last'),
					newEntry = $(currentEntry.clone()).appendTo(controlForm);
					rowNum++; 
					rnum++; 
					newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rnum);
					newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rnum);
					newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rnum);
					newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rnum);
					newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rnum);
					newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rnum);
					newEntry.find($('.line-type')).attr('id', 'acnttype_' + rnum);
					newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rnum);
					newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rnum);
					//newEntry.find($('.line-job')).attr('id', 'jobid_' + rnum);
					 newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rnum);
			        newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rnum);
					newEntry.find($('.line-dept')).attr('id', 'dept_' + rnum);
					newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rnum);
					newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rnum); 
					newEntry.find($('.line-bank')).attr('id', 'bankid_' + rnum);
					newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rnum);
					newEntry.find($('.refdata')).attr('id', 'refdata_' + rnum);
					newEntry.find($('input[name="je_id[]"]')).attr('id', 'jeid_' + rnum);
					newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rnum);
					newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rnum);
					newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rnum);

					newEntry.find($('input[name="department[]"]')).attr('id', 'dept_' + rnum);
					newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rnum);
					newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rnum);
					newEntry.find($('.btn-remove-item')).attr('data-id','rem_'+rnum);
					newEntry.find($('input[name="salesman_idd[]"]')).attr('id', 'salesmanid_' + rowNum);
			         newEntry.find($('input[name="salesman[]"]')).attr('id', 'salesman_' + rowNum);
			         newEntry.find($('.salem')).attr('id', 'salem_' + rowNum);

					$('#jeid_'+rowNum).val('');
					
					
			} //console.log(no+' ab '+ids[i]);

			if(no < $('#rowNum').val()) {
				$('#ref_'+rowNum).val( refs[i] );
				$('#amount_'+rowNum).val(amounts[i]);
				$('#invid_'+rowNum).val( inv[i] );
				$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
				$('#biltyp_'+rowNum).val( btype[i] );
				//$('#jeid_'+rnum).val('');//$('#jeid_'+rowNum).val('');
				$('#invoiceid_'+rowNum).val(inv[i]);
				$('#actamt_'+rowNum).val('');
				$('#trid_'+rowNum).val('');
				$('#draccount_'+rowNum).val( $('#draccount_'+no).val() );
				$('#draccountid_'+rowNum).val( $('#draccountid_'+no).val() );
				
				
			} else { 
				$('#ref_'+rnum).val( refs[i] );
				$('#amount_'+rnum).val(amounts[i]);
				$('#invid_'+rnum).val( inv[i] );
				$('#acnttype_'+rnum).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
				$('#biltyp_'+rnum).val( btype[i] );
				//$('#jeid_'+rnum).val('');
				$('#invoiceid_'+rnum).val(inv[i]);
				$('#actamt_'+rnum).val('');
				$('#trid_'+rnum).val('');
				$('#draccount_'+rnum).val( $('#draccount_'+no).val() );
				$('#draccountid_'+rnum).val( $('#draccountid_'+no).val() );

				
			}

			j++;
		});
		getNetTotal();
	
	});

    
    $(document).on('change', '.line-bank', function(e) { 
	    var res = this.id.split('_');
		var curNum = res[1];

		checkChequeNo(curNum);
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="party_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#anum').val();
		$('#party_'+num).val( $(this).attr("data-name") );
		$('#partyac_'+num).val( $(this).attr("data-id") );
		checkChequeNo(num);
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
	$( '.itemdivPrnt .jvline-amount' ).each(function() {
		var res = this.id.split('_');
		var curNum = res[1];
		if(this.value!='') {
			if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' )
				drLineTotal = drLineTotal + parseFloat( (this.value=='')?0:this.value );
			else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' )
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
		//getNetTotal();
	} else {
		$("#lineamnt_"+curNum).val('');
		//getNetTotal();
	}
	
}

</script>
@stop
