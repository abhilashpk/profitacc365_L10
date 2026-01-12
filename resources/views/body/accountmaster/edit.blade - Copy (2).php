@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
@stop
<style>
.curr { padding:0px !important; }
.rate { padding:2px !important; }
.col-xs-1-qtr { width:5% !important; float: left; }
.col-xs-1-half { width:10% !important; float: left; }
.col-xs-2-half { width:20% !important; float: left; }

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>
	
{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Master</a>
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
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Account Master 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmMaster" id="frmMaster" action="{{ url('account_master/update/'.$masterrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="ac_id" value="{{ $masterrow->id }}">
								<input type="hidden" name="bcurrency" value="{{$settings->bcurrency_id}}">
								
								<div class="form-group">

                                  <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Account Type</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="actype_id" class="form-control select2" style="width:100%" name="actype_id">
                                            <option value="">Select Account Type...</option>
											@foreach ($acctype as $type)
											@if($typeid==$type['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $type['id'] }}" {{$sel}}>{{ $type['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">

                                  <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account Category</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="category_id" class="form-control select2" style="width:100%" name="category_id">
                                            @foreach ($category as $cat)
											@if($masterrow->account_category_id==$cat['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $cat['id'] }}" {{$sel}}>{{ $cat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account Group</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="group_id" class="form-control select2" style="width:100%" name="group_id">
                                            @foreach ($groups as $group)
											@if($masterrow->account_group_id==$group['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $group['id'] }}" {{$sel}}>{{ $group['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<input type="hidden" name="category" id="category" value="{{$masterrow->category}}">
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Account ID</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_id" readonly name="account_id" value="{{ $masterrow->account_id }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account Master</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="master_name" name="master_name" value="{{ $masterrow->master_name }}">
                                    </div>
                                </div>
								<?php if($masterrow->category=='CUSTOMER' || $masterrow->category=='SUPPLIER') { ?>
								<div id="addressDtls">
									<hr/>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Address</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="address" name="address" value="{{$masterrow->address}}" placeholder="Address1, Address2">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">City</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="city" name="city" value="{{$masterrow->city}}" placeholder="City">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">State</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="state" name="state" value="{{$masterrow->state}}" placeholder="State">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Country</label>
										<div class="col-sm-10">
											<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
												<option value="">Select Country...</option>
												@foreach ($country as $con)
												@if($masterrow->country_id==$con['id'])
												{{--*/ $sel = "selected" /*--}}
												@else
												{{--*/ $sel = "" /*--}}	
												@endif
												<option value="{{ $con['id'] }}" {{$sel}}>{{ $con['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Area</label>
										<div class="col-sm-10">
											 <select id="area_id" class="form-control select2" style="width:100%" name="area_id">
												<option value="">Select Country...</option>
												@foreach ($area as $ar)
												@if($masterrow->area_id==$ar['id'])
												{{--*/ $sel = "selected" /*--}}
												@else
												{{--*/ $sel = "" /*--}}	
												@endif
												<option value="{{ $ar['id'] }}" {{$sel}}>{{ $ar['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Pin</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="pin" name="pin" value="{{$masterrow->pin}}" placeholder="Pin">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Phone</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="phone" name="phone" value="{{$masterrow->phone}}" placeholder="Phone">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Email</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="email" name="email" value="{{$masterrow->email}}" autocomplete="off" placeholder="Email">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Contact Person</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="contact_name" name="contact_name" value="{{$masterrow->contact_name}}" placeholder="Contact Person">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">TRN No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="vat_no" name="vat_no" value="{{$masterrow->vat_no}}" placeholder="TRN No.">
										</div>
									</div>
								</div>
								<?php } ?>
								
								<?php if($masterrow->category == 'CUSTOMER' || $masterrow->category == 'SUPPLIER') { ?>
								<fieldset>
								<legend><h5>Opening Balance Details</h5></legend>
								{{--*/ $i = 0; $num = count($opnbalance); /*--}}
								<input type="hidden" id="rowNum" value="{{($num==0)?1:$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								<?php if($num==0) { ?>
									<div class="itemdivChld">
											<div class="form-group">
												<div class="col-xs-12">
													<div class="col-sm-1"> <span class="small">Tr. Type</span>
													<select id="trtype_1" class="form-control select2 line-tr" style="width:100%;padding:0px !important;" name="tr_type[]">
													<!--<option value="">Select</option>
													<option value="Dr">Dr</option><option value="Cr">Cr</option>-->
													</select>
													</div> 
												
													<div class="col-xs-1-half">
														<span class="small">Tr. Date</span> <input type="text" id="trdate_1" autocomplete="off" readonly name="tr_date[]" class="form-control trdate" data-language='en'>
													</div>
													<div class="col-xs-2">
														<span class="small">Ref. No</span> <input type="text" id="refno_1" autocomplete="off" name="reference_no[]" class="form-control">
													</div>
													<div class="col-xs-2-half">
														<span class="small">Description</span> <input type="text" id="description_1" autocomplete="off" name="description[]" class="form-control"> 
													</div>
													
													<div class="col-xs-1">
														<span class="small">Currency</span> 
														<select id="occrncy_1" class="form-control select2 curr" name="currency[]">
															@foreach($currency as $curr)
															<option value="{{$curr['id']}}">{{$curr['code']}}</option>
															@endforeach
														</select>
													</div>
													
													<div class="col-xs-1-half">
														<span class="small">Amount</span> <input type="number" id="amount_1" step="any" autocomplete="off" name="amount[]" class="form-control line-amount">
													</div>
													
													<div class="col-xs-1">
														<span class="small">Rate</span> <input type="number" id="rate_1" step="any" autocomplete="off" name="rate[]" class="form-control rate" readonly>
													</div>
													<div class="col-xs-1-half">
														<span class="small">Convrt Amt</span> <input type="number" id="cnvtamt_1" step="any" autocomplete="off" name="cnvt_amt[]" class="form-control">
													</div>
													<div class="col-xs-1"><span class="small">&nbsp; </span><br/>
														<button type="button" class="btn-success btn-danger btn-remove-item" data-id="rem_1">
															<i class="fa fa-fw fa-minus-square"></i>
														</button><br/>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														</button>
														
													</div>
												</div>	
											</div>
											<hr/>
										</div>
								<?php } else { ?>
								
								@foreach($opnbalance as $row)
								{{--*/ $i++; /*--}}
									<div class="itemdivChld">							
										<div class="form-group">
											<div class="col-xs-12">
												<div class="col-sm-1"> 
													<span class="small">Tr. Type</span>
													<input type="hidden" name="tr_id[]" id="trid_{{$i}}" value="{{$row->id}}">
													<select id="trtype_{{$i}}" class="form-control select2 line-tr" style="width:100%" name="tr_type[]">
													<option value="Dr" <?php if($row->tr_type=='Dr') echo 'selected';?>>Dr</option><option value="Cr" <?php if($row->tr_type=='Cr') echo 'selected';?>>Cr</option>
													</select>
												</div>
											
												<div class="col-xs-1-half">
													<span class="small">Tr. Date</span> <input type="text" id="trdate_{{$i}}" name="tr_date[]" autocomplete="off" value="{{date('d-m-Y', strtotime($row->tr_date))}}" class="form-control trdate" data-language='en'>
												</div>
												<div class="col-xs-2">
													<span class="small">Ref. No</span> <input type="text" id="refno_{{$i}}" name="reference_no[]" autocomplete="off" value="{{$row->reference_no}}" class="form-control">
												</div>
												<div class="col-xs-2-half">
													<span class="small">Description</span> <input type="text" id="description_{{$i}}" name="description[]" autocomplete="off" value="{{$row->description}}" class="form-control"> 
												</div>
												
												<div class="col-xs-1">
													<span class="small">Currency</span> 
													<select id="occrncy_{{$i}}" class="form-control select2 curr" name="currency[]">
														@foreach($currency as $curr)
														<option value="{{$curr['id']}}" <?php if($row->currency_id==$curr['id']) echo 'selected';?>>{{$curr['code']}}</option>
														@endforeach
													</select>
												</div>
													
												<div class="col-xs-1-half">
													<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" step="any" name="amount[]" autocomplete="off" value="{{$row->fc_amount}}" class="form-control line-amount">
												</div>
												
												<div class="col-xs-1">
													<span class="small">Rate</span> <input type="number" id="rate_{{$i}}" step="any" autocomplete="off" name="rate[]" class="form-control rate" value="{{($row->rate==0)?'':$row->rate}}" readonly>
												</div>
												
												<div class="col-xs-1-half">
													<span class="small">Convrt Amt</span> <input type="number" id="cnvtamt_{{$i}}" step="any" autocomplete="off" name="cnvt_amt[]" value="{{$row->amount}}" class="form-control">
												</div>
													
												<div class="col-xs-1"><br/>
												
													<button type="button" class="btn-success btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														</button><br/>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														</button>
													
												</div>
											</div>	
										</div>

										<hr/>
									</div>
								@endforeach
								<?php } ?>
								</div>
								</fieldset>
								<?php } else if($masterrow->category == 'PDCR' || $masterrow->category == 'PDCI') { ?>
								<div id="ob_chqdetail">
									<fieldset>
									<legend><h5>Opening Balance Details</h5></legend>
									<div class="itemdivPrntch">
										{{--*/ $i = 0; $num = count($opnbalance); /*--}}
										<input type="hidden" id="rowNum" value="{{($num==0)?1:$num}}">
										<input type="hidden" id="remitem" name="remove_item">
										<?php if($num==0) { ?>
											<div class="itemdivChldch">							
											<div class="form-group">
												<div class="col-sm-2"> <span class="small">Amount </span>
													<input type="number" id="amountch_{{$i}}" step="any" name="amount[]" autocomplete="off" class="form-control chline-amount">
												</div>
												<div class="col-xs-10">
													<div class="col-xs-3">
														<span class="small">Cheque No.</span> 
														<input type="text" id="chqno_{{$i}}" name="cheque_no[]" autocomplete="off" class="form-control">
													</div>
													<div class="col-xs-4">
														<span class="small">Cheque Date</span> 
														<input type="text" id="chqdate_{{$i}}" name="cheque_date[]" autocomplete="off" class="form-control chkdate" data-language='en'>
													</div>
													<div class="col-xs-3">
														 <span class="small">Bank </span>
														 <select id="bank_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank[]">
															<option value="">Select</option>
															@foreach($banks as $bank)
															<option value="{{$bank['id']}}">{{$bank['code']}}</option>
															@endforeach
														</select>
													</div>
												</div>	
											</div>
											
											<div class="form-group">
												<div class="col-sm-2"> 
													<span class="small">Transaction Type</span> 
													<select id="trtypech_{{$i}}" class="form-control select2 linech-tr" style="width:100%" name="tr_type[]">
															<?php if($masterrow->category == 'PDCR') { ?>
																<option value="Dr">Dr</option>
															<?php } else { ?>
																<option value="Cr">Cr</option>
															<?php } ?>
														</select>
												</div>
												<div class="col-xs-10">
													<div class="col-xs-3">
														<span class="small">Account Name</span> <input type="text" id="frmaccount_{{$i}}" name="frmaccount_name[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" id="frmaccountid_{{$i}}" name="frmaccount_id[]" class="form-control">
														<input type="hidden" id="refnoc_{{$i}}" name="reference_no[]" class="form-control">
													</div>
													<div class="col-xs-4">
														<span class="small">Transaction Date </span> <input type="text" id="trdate_{{$i}}" autocomplete="off" readonly name="tr_date[]" class="form-control chtrdate" data-language='en'>
													</div>
													<div class="col-xs-3">
														<span class="small">Description</span> <input type="text" id="description_{{$i}}" autocomplete="off" name="description[]" class="form-control"> 
													</div>
													<div class="col-xs-1"><span class="small">&nbsp; </span>
														 <button type="button" class="btn btn-success btn-add-itemch" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>
													</div>
												</div>	
											</div>

										</div>
										<?php } else { ?>
										@foreach($opnbalance as $row)
										{{--*/ $i++; /*--}}
										<div class="itemdivChldch">							
											<div class="form-group">
												<input type="hidden" name="tr_id[]" id="trid_{{$i}}" value="{{$row->id}}">
												<div class="col-sm-2" style="width:13%"> <span class="small">Amount </span>
													<span class="small">Amount</span> <input type="number" autocomplete="off" id="amountch_{{$i}}" step="any" name="amount[]" value="{{$row->amount}}" autocomplete="off" class="form-control chline-amount">
												</div>
												<div class="col-sm-2" style="width:10%">
													<span class="small">Cheque No.</span><input type="text" id="chqno_{{$i}}" name="cheque_no[]" class="form-control"  autocomplete="off"value="{{$row->cheque_no}}">
												</div>
												<div class="col-sm-2" style="width:13%">
													<span class="small">Cheque Date</span> <input type="text" id="chqdate_{{$i}}" autocomplete="off" value="{{date('d-m-Y',strtotime($row->cheque_date))}}" name="cheque_date[]" class="form-control chkdate" data-language='en'>
												</div>
												<div class="col-sm-1" style="width:10%">
													<span class="small">Bank</span> 
													<select id="bank_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank[]">
														@foreach($banks as $bank)
														<option value="{{$bank['id']}}" <?php if($row->bank_id==$bank['id']) echo 'selected';?>>{{$bank['name']}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-1" style="width:7%"> 
													<span class="small">Tr. Type</span>
													<select id="trtypech_{{$i}}" class="form-control select2 linech-tr" style="width:100%" name="tr_type[]">
													<option value="{{$row->tr_type}}">{{$row->tr_type}}</option>
													</select>
												</div>
												<div class="col-sm-2" style="width:13%">
													<span class="small">Account Name</span> <input type="text" id="frmaccount_{{$i}}" name="frmaccount_name[]" value="{{$row->from_account}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal">
													<input type="hidden" id="frmaccountid_{{$i}}" name="frmaccount_id[]" class="form-control" value="{{$row->frmaccount_id}}">
													<input type="hidden" id="refnoc_{{$i}}" name="reference_no[]" class="form-control">
												</div>
												<div class="col-sm-2" style="width:13%">
													<span class="small">Tr. Date</span> <input type="text" id="trdate_{{$i}}" name="tr_date[]" value="{{date('d-m-Y',strtotime($row->tr_date))}}" autocomplete="off" class="form-control chtrdate" data-language='en'>
												</div>
												<div class="col-sm-2" style="width:15%">
													<span class="small">Description</span> <input type="text" id="description_{{$i}}" name="description[]" autocomplete="off" value ="{{$row->description}}"class="form-control"> 
												</div>
												<div class="col-sm-1" style="width:3%"><br/>
													<button type="button" class="btn-success btn-danger btn-remove-itemch" data-id="rem_{{$i}}">
														<i class="fa fa-fw fa-minus-square"></i>
													</button>
													 <button type="button" class="btn-success btn-add-itemch" >
														<i class="fa fa-fw fa-plus-square"></i>
													 </button>
												</div>
											</div>
										</div>
										@endforeach
										<?php } ?>
									</div>
									</fieldset>
								</div>
								<?php } else { ?>
								<hr/>
								<div class="form-group" id="trtype">
                                    <label for="input-text" class="col-sm-2 control-label">Transaction Type</label>
                                    <div class="col-sm-10">
                                        <select id="transaction" class="form-control select2" style="width:100%" name="transaction">
											<option value="Dr" <?php if($masterrow->transaction_type=='Dr') echo 'selected';?>>Dr</option>
											<option value="Cr" <?php if($masterrow->transaction_type=='Cr') echo 'selected';?>>Cr</option>
                                        </select>
                                    </div>
                                </div>
								<?php } ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Open Balance Net</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="op_balance" step="any" name="op_balance" value="{{ $masterrow->op_balance }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Closing Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="cl_balance" step="any" readonly name="cl_balance" value="{{ $masterrow->cl_balance }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Foreign Currency</label>
                                    <div class="col-sm-10">
                                        <select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
											<option value="">Select Currency...</option>
											@foreach ($currency as $cur)
											@if($masterrow->currency_id==$cur['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $cur['id'] }}" {{ $sel }}>{{ $cur['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">FC Open Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="fcop_balance" name="fcop_balance" step="any" value="{{ $masterrow->fcop_balance }}">
                                    </div>
                                </div>
								<hr/>
                                
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                        <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											<option value="">Select Department...</option>
											@foreach ($department as $dep)
											@if($masterrow->department_id==$dep['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $dep['id'] }}" {{ $sel }}>{{ $dep['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <select id="salesman_id" class="form-control select2" style="width:100%" name="salesman_id">
											<option value="">Select Salesman...</option>
											@foreach ($salesman as $sal)
											@if($masterrow->salesman_id==$sal['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $sal['id'] }}" {{ $sel }}>{{ $sal['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Credit Limit</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" step="any" id="credit_limit" name="credit_limit" value="{{ $masterrow->credit_limit }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Due Days</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="duedays" name="duedays" value="{{ $masterrow->duedays }}">
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
											<option value="">Select Terms...</option>
											@foreach ($terms as $tem)
											@if($masterrow->terms_id==$tem['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $tem['id'] }}" {{ $sel }}>{{ $tem['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Assignable?</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" <?php if($masterrow->vat_assign==1) echo 'checked';?> id="vat_assign" name="vat_assign" value="1">
                                    </div>
                                </div>
								
								<div class="form-group" id="vatPntg">
                                    <label for="input-text" class="col-sm-2 control-label">VAt Percentage</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control" id="vat_perc" value="{{$masterrow->vat_percentage}}" name="vat_percentage" placeholder="VAT %">
                                    </div>
                                </div>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Assignable?</label>
									@if($masterrow->job_assign==0)
									{{--*/ $chk1 = "checked";
											$chk2 = "";
									/*--}}
									@else
									{{--*/ $chk2 = "checked";
											$chk1 = "";
									/*--}}	
									@endif
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="job_assign" value="0" {{$chk1}}>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="job_assign" value="1" {{$chk2}}>
                                            Yes
                                        </label>
                                    </div>
                                </div>
																
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Compulsary</label>
									@if($masterrow->job_compulsary==0)
									{{--*/ $chk1 = "checked";
											$chk2 = "";
									/*--}}
									@else
									{{--*/ $chk2 = "checked";
											$chk1 = "";
									/*--}}	
									@endif
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="job_compulsary" value="0" {{$chk1}}>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="job_compulsary" value="1" {{$chk2}}>
                                            Yes
                                        </label>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Hide</label>
									@if($masterrow->is_hide==0)
									{{--*/ $chk1 = "checked";
											$chk2 = "";
									/*--}}
									@else
									{{--*/ $chk2 = "checked";
											$chk1 = "";
									/*--}}	
									@endif
									<div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="is_hide" value="0" {{$chk1}}>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="is_hide" value="1" {{$chk2}}>
                                            Yes
                                        </label>
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
							
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('account_master') }}" class="btn btn-danger">Cancel</a>
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

<script>
"use strict";

$(document).ready(function () {
	
	<?php if($masterrow->vat_assign==1) { ?>
	
		if( $("#vatPntg").is(":hidden") )
			$('#vatPntg').toggle();
		
	<?php } else { ?>
	
		if( $("#vatPntg").is(":visible") )
			$('#vatPntg').toggle();
		
	<?php } ?>
	
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	$('.itemdivPrntch').find('.btn-add-itemch:not(:last)').hide();
	if ( $('.itemdivPrntch').children().length == 1 ) {
		$('.itemdivPrntch').find('.btn-remove-itemch').hide();
	}
	
	//$('#addressDtls').toggle();
	var urlcode = "{{ url('itemmaster/checkcode/') }}";
	var urldesc = "{{ url('itemmaster/checkdesc/') }}";
	var urlname = "{{ url('account_master/checkname/') }}";
	<?php if( ($masterrow->category=='SUPPLIER' || $masterrow->category=='CUSTOMER') && ($masterrow->op_balance==0) ) { ?>
	$('#trtype_1').find('option').end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
	<?php } ?>
    $('#frmMaster').bootstrapValidator({
        fields: {
			actype_id: { validators: { notEmpty: { message: 'The account type is required and cannot be empty!' } } },
			category_id: { validators: { notEmpty: { message: 'The category name is required and cannot be empty!' } } },
			group_id: { validators: { notEmpty: { message: 'The group name is required and cannot be empty!' } } },
			account_id: { validators: { notEmpty: { message: 'The account id is required and cannot be empty!' } } },
            master_name: { validators: { 
					notEmpty: { message: 'The account master is required and cannot be empty!' },
					remote: { url: urlname,
							  data: function(validator) {
								return { master_name: validator.getFieldElements('master_name').val(),
										 id: validator.getFieldElements('ac_id').val()
								};
							  },
							  message: 'This account name is already exist!'
                    }
                }
            },
			//'tr_type[]': { validators: { notEmpty: { message: 'The transaction type is required and cannot be empty!' } } },
			//'reference_no[]': { validators: { notEmpty: { message: 'The reference no is required and cannot be empty!' } } },
			//'tr_date[]': { validators: { notEmpty: { message: 'The transaction date is required and cannot be empty!' } } },
            //'amount[]': { validators: { notEmpty: { message: 'The amount is required and cannot be empty!' } } },
			//'cheque_no[]': { validators: { notEmpty: { message: 'The checque no is required and cannot be empty!' } } },
			//'cheque_date[]': { validators: { notEmpty: { message: 'The checque date is required and cannot be empty!' } } },
			//'bank[]': { validators: { notEmpty: { message: 'The bank is required and cannot be empty!' } } },
			//op_balance: { validators: { notEmpty: { message: 'The open balance is required and cannot be empty!' } } },
          
        }
        
    }).on('reset', function (event) {
        $('#frmMaster').data('bootstrapValidator').resetForm();
    });
});

$('#actype_id').on('change', function(e){
	var type_id = e.target.value;

	$.get("{{ url('accategory/getcategory/') }}/" + type_id, function(data) {
		$('#category_id').empty();
		 $('#category_id').append('<option value="">Select Account Category...</option>');
		$.each(data, function(value, display){
			 $('#category_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});

$('#category_id').on('change', function(e){
	var cat_id = e.target.value;

	$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
		$('#group_id').empty();
		 $('#group_id').append('<option value="">Select Account Group...</option>');
		$.each(data, function(value, display){
			 $('#group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});

$('#group_id').on('change', function(e){
	var group_id = e.target.value;
	$.get("{{ url('account_master/getcode/') }}/" + group_id, function(data) {
		//$('#account_id').val(data);
		var val = $.parseJSON(data);
		$('#account_id').val(val.code);
		$('#category').val(val.category);
		cat = val.category; var ttype = val.trtype;
		
		if(val=='CUSTOMER' || val=='SUPPLIER'){
			$('#actype').val('SupCus');
			if( $('#addressDtls').is(":hidden") )
				$('#addressDtls').toggle();
			
			if( $('#ob_detail').is(":hidden") ) {
				$('#ob_detail').toggle();
				$('#trtype_1').find('option').end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
			}
			
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
			
			if( $('#ob_chqdetail').is(":visible") )
				$('#ob_chqdetail').toggle();
			
		} else if(cat=='PDCR' || cat=='PDCI'){
			$('#actype').val('Chq');
			if( $('#addressDtls').is(":visible") )
				$('#addressDtls').toggle();
			
			if( $('#ob_chqdetail').is(":hidden") ) {
				$('#ob_chqdetail').toggle();
				if(cat=='PDCR')
					$('#trtypech_1').find('option').end().append('<option value="Dr">Dr</option>');
				else if(cat=='PDCI')
					$('#trtypech_1').find('option').end().append('<option value="Cr">Cr</option>');
				
			}
			
			if( $('#ob_detail').is(":visible") )
					$('#ob_detail').toggle();
				
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
		} else {
			 $("#transaction option[value='"+ttype+"']").attr("selected", "selected");
			$('#actype').val('');
			if( $('#addressDtls').is(":visible") )
				$('#addressDtls').toggle();
			
			if( $('#ob_detail').is(":visible") )
				$('#ob_detail').toggle();
			
			if( $('#trtype').is(":hidden") )
				$('#trtype').toggle();
			
			if( $('#ob_chqdetail').is(":visible") )
				$('#ob_chqdetail').toggle();
		}
	});
});



//NEW CHNG....
function getLineTotal() {
	var lineTotal = 0; var dramount = 0; var cramount = 0; var amount = 0;
	$( '.line-amount' ).each(function() {
		
		if(this.value!='') {
			var elmnt = this.id;
			var res = elmnt.split('_');
			var n = res[1]; 
			
			var rate = parseFloat( ($('#rate_'+n).val()=='') ? 1 :  $('#rate_'+n).val() ); 
			var amnt = parseFloat(this.value) * rate;
		
			if( $('#trtype_'+n+' option:selected').val()=='Dr' )
				dramount = dramount + amnt;
			else if( $('#trtype_'+n+' option:selected').val()=='Cr' )
				cramount = cramount + amnt;
			
			amount = dramount - cramount;
		}
			
	}); return amount; 
}

function getLineTotalChq() {
	var lineTotal = 0; var dramount = 0; var cramount = 0; var amount = 0;
	$( '.chline-amount' ).each(function() { 
		
		if(this.value!='') {
			var elmnt = this.id;
			var res = elmnt.split('_');
			var n = res[1]; 
		
			if( $('#trtypech_'+n+' option:selected').val()=='Dr' ){
				dramount = dramount + parseFloat(this.value); 
			}else if( $('#trtypech_'+n+' option:selected').val()=='Cr' ){
				cramount = cramount + parseFloat(this.value); 
			}
			amount = dramount - cramount;
		}
			
	}); return amount; 
}

$(function() {
	$('input[type=number]').on('mousewheel',function(e){ $(this).blur(); }); //NEW CHNG...
	var rowNum = $('#rowNum').val(); //NEW CHNG... 
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
         var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-tr')).attr('id', 'trtype_' + rowNum);
			newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rowNum);
			newEntry.find($('input[name="tr_date[]"]')).attr('id', 'trdate_' + rowNum);
			newEntry.find($('input[name="reference_no[]"]')).attr('id', 'refno_' + rowNum);
			newEntry.find($('input[name="description[]"]')).attr('id', 'description_' + rowNum);
			newEntry.find($('input[name="amount[]"]')).attr('id', 'amount_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chqno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chqdate_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bank_' + rowNum);
			
			newEntry.find($('input[name="rate[]"]')).attr('id', 'rate_' + rowNum);
			newEntry.find($('input[name="cnvt_amt[]"]')).attr('id', 'cnvtamt_' + rowNum);
			newEntry.find($('.curr')).attr('id', 'occrncy_' + rowNum);
			
			newEntry.find('input').val(''); 
			newEntry.find('.line-tr select').find('option').remove().end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');//new change
			
			newEntry.find('.trdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy',maxDate: new Date('{{$obto}}')//minDate: new Date('{{$obfrom}}'),
			});
			
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//NEW CHNG...
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#trid_'+curNum).val():remitem+','+$('#trid_'+curNum).val();
		$('#remitem').val(ids);
		
		$(this).parents('.itemdivChld:first').remove();
		
		var res = getLineTotal();
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	 //rowNum++;
	//cheque transaction entry form......
	$(document).on('click', '.btn-add-itemch', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrntch'),
            currentEntry = $(this).parents('.itemdivChldch:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.linech-tr')).attr('id', 'trtypech_' + rowNum);
			newEntry.find($('input[name="tr_id[]"]')).attr('id', 'trid_' + rowNum);
			newEntry.find($('input[name="tr_date[]"]')).attr('id', 'trdate_' + rowNum);
			newEntry.find($('input[name="frmaccount_name[]"]')).attr('id', 'frmaccount_' + rowNum);
			newEntry.find($('input[name="frmaccount_id[]"]')).attr('id', 'frmaccountid_' + rowNum);
			newEntry.find($('input[name="reference_no[]"]')).attr('id', 'refno_' + rowNum);
			newEntry.find($('input[name="description[]"]')).attr('id', 'description_' + rowNum);
			newEntry.find($('input[name="amount[]"]')).attr('id', 'amountch_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chqno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chqdate_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bank_' + rowNum);
			newEntry.find('input').val(''); 
			newEntry.find('.line-bank').find('option').end().append('<option value="" selected>Select</option>');//new change
			//newEntry.find('.linech-tr').find('option').remove().end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');//new change
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-itemch')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
			controlForm.find('.btn-add-itemch:not(:last)').hide();
			controlForm.find('.btn-remove-itemch').show();
			
			newEntry.find('.chtrdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$obfrom}}'),maxDate: new Date('{{$obto}}')
			});
			
			newEntry.find('.chkdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy'
			});
			
    }).on('click', '.btn-remove-itemch', function(e)
    { 
		//NEW CHNG...
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#trid_'+curNum).val():remitem+','+$('#trid_'+curNum).val();
		$('#remitem').val(ids);
		
		$(this).parents('.itemdivChldch:first').remove();
		
		var res = getLineTotalChq();
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
		
		e.preventDefault();
		return false;
	});
	
	$(document).on('blur', '.line-amount', function(e) {
		var res = getLineTotal(); 
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
	});
	
	$(document).on('blur', '.chline-amount', function(e) {
		var res = getLineTotalChq();
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
	});
	
	$(document).on('change', '.linech-tr', function(e) {
		var res = getLineTotalChq();
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
	});
	
	$('.trdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',maxDate: new Date('{{$obto}}')//minDate: new Date('{{$obfrom}}'),
	}); 
	
	$('.chtrdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$obfrom}}'),maxDate: new Date('{{$obto}}')
	});
	
	$('.chkdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy'
	});
	
	//new change............
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="frmaccount_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var dpt;
		if($('#department_id').length) {
			dpt = $( "#department_id option:selected" ).val();
		} else {
			dpt = '';
		}
		
		if(dpt!='') {
			$('#account_data').load(acurl+'/'+curNum+'/'+dpt, function(result){ //.modal-body item
				$('#myModal').modal({show:true});
			});
		} else {	
			$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
				$('#myModal').modal({show:true});
			});
		}
		
		/* $('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		}); */
	});
	
	//new change.................
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val();
		$('#frmaccount_'+num).val( $(this).attr("data-name") );
		$('#frmaccountid_'+num).val( $(this).attr("data-id") );
	});
	
	$('.onacnt_icheck').on('ifChecked', function(event){ 
		$('#vatPntg').toggle();
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#vatPntg').toggle();
	});
	
	$(document).on('change', '.curr', function(e) { //$('.oc-curr').on('change', function(e){
		var res = this.id.split('_');
		var curNum = res[1]; 
		var curr_id = e.target.value; 
		
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#rate_'+curNum).val((data==0)?'':data);
			
			var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
			var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
			$('#cnvtamt_'+curNum).val( amt * rate);
		});
		
		getLineTotal();
	});
	
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
		var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
		$('#cnvtamt_'+curNum).val( amt * rate);
		
		getLineTotal();
	});
});

</script>
@stop
