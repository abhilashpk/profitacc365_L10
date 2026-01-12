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

.itemdivChld .form-group {
    margin-left: 0px !important;
    margin-right: 0px !important;
}

.itemdivChldch .form-group {
    margin-left: 0px !important;
    margin-right: 0px !important;
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
                    Add New
                </li>
            </ol>
        </section>
        <!--section ends-->
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Account Master 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls" id="frmMaster1"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmMaster" id="frmMaster" action="{{ url('account_master/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="actype" id="actype" value="{{$actype}}">
								<input type="hidden" name="bcurrency" value="{{$settings->bcurrency_id}}">

								<div class="form-group">
                                   <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Account Type</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="actype_id" class="form-control select2" style="width:100%" name="actype_id">
                                            <option value="">Select Account Type...</option>
											@foreach ($acctype as $type)
											@if($actypid==$type['id'])
											@php $sel = "selected" @endphp
											@else
											@php  $sel = "" @endphp
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
                                            <option value="">Select Account Category...</option>
											@foreach ($category as $cat)
											@if($catid==$cat['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
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
                                            <option value="">Select Account Group...</option>
											<?php if($catid!='') {?>
											@foreach ($groups as $group)
											@if($gpid==$group['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $group['id'] }}" {{$sel}}>{{ $group['name'] }}</option>
											@endforeach
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								<input type="hidden" name="category" id="category" value="{{$catgy}}">
								<input type="hidden" name="typetr" id="typetr" value="{{$typetr}}">
								<div class="form-group">
                                    <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account ID</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_id" name="account_id" value="{{$accode}}" placeholder="Account ID">
                                    </div>
                                </div>
								
								<div class="form-group">
                                     <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Account Master</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="master_name" name="master_name" autocomplete="off" placeholder="Account Master">
                                    </div>
                                </div>
								
								@if($formdata['ac_no']==1)
								<div class="form-group">
                                    <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account No</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account No">
                                    </div>
                                </div>
                                @endif
                                
								<div class="form-group" id="area_id">
									<font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Area</b></label></font>
										<div class="col-sm-10">
											 <select id="area_id" class="form-control select2" style="width:100%" name="area_id">
												<option value="">Select Area...</option>
												@foreach ($area as $ar)
												<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>										
									
									<div class="form-group" id="vat_no">
									<font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>TRN No.</b></label></font>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="vat_no" name="vat_no" autocomplete="off" placeholder="TRN No.">
										</div>
									</div>
								
								
								<div id="addressDtls"><hr/>
									<h5 class="DLS2"><a href=""><b>Add Address Details</b></a></h5>
									<div id="viewDetails">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Address1</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="address" name="address" autocomplete="off" placeholder="Address1">
											</div>
										</div>
									
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Address2</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="city" name="city" autocomplete="off" placeholder="Address2">
											</div>
										</div>
									
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Address3</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="state" name="state" autocomplete="off" placeholder="Address3">
											</div>
										</div>
									
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Country</label>
											<div class="col-sm-10">
												<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
													<option value="">Select Country...</option>
													@foreach ($country as $con)
														<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
													@endforeach
												</select>
											</div>
										</div>

										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Phone</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="phone" name="phone" autocomplete="off" placeholder="Phone">
											</div>
										</div>
									
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Email</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="email" name="email" autocomplete="off" placeholder="Email">
											</div>
										</div>
									
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Contact Person</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="contact_name" name="contact_name" autocomplete="off" placeholder="Contact Person">
											</div>
										</div>
									</div>
									<hr/>		
									<?php if($formdata['salesman']==1) { ?>
									<div class="form-group">
                                   		<label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    	<div class="col-sm-10">
                                        	<select id="salesman_id" class="form-control select2" style="width:100%" name="salesman_id">
												<option value="">Select Salesman...</option>
													@foreach ($salesman as $sal)
													<option value="{{ $sal['id'] }}">{{ $sal['name'] }}</option>
												@endforeach
                                        	</select>
                                    	</div>
                                	</div><hr/>
									<?php } else { ?>
										<input type="hidden" name="salesman" id="salesman">
									<?php } ?>
									<!-- <hr/> -->

									<?php if($formdata['credit_limit']==1) { ?>
										<div class="form-group">
                                    		<label for="input-text" class="col-sm-2 control-label">Credit Limit</label>
                                    		<div class="col-sm-10">
                                        		<input type="number" class="form-control" step="any" id="credit_limit" name="credit_limit" placeholder="Credit Limit">
                                    		</div>
                                		</div>
									<?php } else { ?>
										<input type="hidden" name="credit_limit" id="credit_limit">
									<?php } ?>
								
									<?php if($formdata['due_days']==1) { ?>
										<div class="form-group">
                                    		<label for="input-text" class="col-sm-2 control-label">Due Days</label>
                                    		<div class="col-sm-10">
                                        		<input type="number" class="form-control" id="duedays" name="duedays" placeholder="Due Days">
                                    		</div>
                                		</div>
									<?php } else { ?>
										<input type="hidden" name="due_days" id="due_days">
									<?php } ?>

									<?php if($formdata['terms']==1) { ?>
										<div class="form-group">
                                    		<label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    		<div class="col-sm-10">
                                        		<select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
													<option value="">Select Terms...</option>
													@foreach ($terms as $desc)
														<option value="{{ $desc['id'] }}">{{ $desc['description'] }}</option>
													@endforeach
                                       	 		</select>
                                    		</div>
                                		</div>
									<?php } else { ?>
										<input type="hidden" name="terms" id="terms">
									<?php } ?>
									
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Foreign Currency</label>
                                    <div class="col-sm-10">
                                        <select id="currency_id" class="form-control select2 fcurrency" style="width:100%" name="currency_id">
                                            <option value="">Select</option>
											@foreach ($bcurrency as $cur)
											<option value="{{ $cur->id }}" {{($settings->fcurrency_id==$cur->id)?'selected':''}}>{{ $cur->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								</div>
									<!-- <hr/> -->
									
								
								
								<div id="ob_detail">
									<fieldset>
									<legend><h5>Opening Balance Details</h5></legend>
									<div class="itemdivPrnt">
										<div class="itemdivChld">
											<div>
												<?php if($formdata['ob_other_details']==1) { ?>
												<div class="col-xs-12">
													<div class="col-sm-1 form-group" style="width:5%;"> <span class="small">Type</span> 
													<select id="trtype_1" class="form-control select2 line-tr" style="padding:0px !important;" name="tr_type[]">
													<option value="">Type</option>
													<option value="Dr">Dr</option><option value="Cr">Cr</option>
													</select>
													</div>
												
													<div class="form-group col-xs-1-half">
														<span class="small">Tr. Date</span> <input type="text" id="trdate_1" autocomplete="off" readonly name="tr_date[]" class="form-control trdate" data-language='en'>
													</div>
													<div class="col-xs-1 form-group">
														<span class="small">Ref. No</span> <input type="text" id="refno_1" autocomplete="off" name="reference_no[]" class="form-control ref-no">
													</div>
													<div class="col-xs-1 form-group" style="width:12%; !important;">
														<span class="small">Description</span> <input type="text" id="description_1" autocomplete="off" name="description[]" class="form-control"> 
													</div>
													
													<div class="col-xs-1 form-group" style="width:8%; !important;">
														<span class="small">Currency</span> 
														<select id="occrncy_1" class="form-control select2 curr" name="currency[]">
														    <option value="{{$settings->bcurrency_id}}">Select</option>
															@foreach($bcurrency as $curr)
															<option value="{{$curr->id}}" >{{$curr->code}}</option>
															@endforeach
														</select>
													</div>
													 
													<div class="col-xs-1-half form-group">
														<span class="small lbamt">Amount</span> <input type="number" id="amount_1" step="any" autocomplete="off" name="amount[]" class="form-control line-amount">
													</div>
													
													<div class="col-xs-1 form-group" style="width:5%; !important;">
														<span class="small">Rate</span> <input type="number" id="rate_1" step="any" autocomplete="off" name="rate[]" class="form-control rate" value="1">
													</div>
													<div class="col-xs-1 form-group" style="width:8%; !important;">
														<span class="small lbamtfc">FC Amt</span> <input type="number" id="cnvtamt_1" step="any" autocomplete="off" name="cnvt_amt[]" class="form-control cnvt-amt" >
													</div>
													<div class="col-xs-1 form-group" style="width:10% !important;">
														<span class="small">Salesman</span> 
														<select id="sman_1" class="form-control select2 slmn" name="slsman[]">
															<option value="">Select</option>
															@foreach($salesman as $sm)
															<option value="{{$sm['id']}}">{{$sm['name']}}</option>
															@endforeach
														</select>
													</div>
													<div class="col-xs-1 form-group" style="width:8% !important;">
														<span class="small">Job No</span> <input type="text" id="jbno_1" autocomplete="off" name="jobno[]" class="form-control">
													</div>
													<div class="col-xs-1 form-group" style="width:9% !important;">
														<span class="small">Due Date</span> <input type="text" id="duedate_1" autocomplete="off" name="duedate[]" class="form-control duedate">
													</div>
													<div class="col-xs-1 form-group" style="width:5%; !important;">
														<br/>
														<button type="button" class="btn btn-danger btn-xs btn-remove-item" data-id="rem_1"><i class="fa fa-fw fa-minus-square"></i></button>
														<br/>
														<button type="button" class="btn btn-success btn-xs btn-add-item"><i class="fa fa-fw fa-plus-square"></i></button>
													</div>
												</div>	
												<?php } else { ?>
												<div class="col-xs-12">
													<div class="col-sm-1 form-group" style="width:5% !important;"> <span class="small">Tr. Type</span>
													<select id="trtype_1" class="form-control select2 line-tr" style="width:100%;padding:0px !important;" name="tr_type[]">
													<option value="">Select</option>
													<option value="Dr">Dr</option><option value="Cr">Cr</option>
													</select>
													</div>
												
													<div class="col-xs-1 form-group" style="width:9% !important;">
														<span class="small">Tr. Date</span> <input type="text" id="trdate_1" autocomplete="off" readonly name="tr_date[]" class="form-control trdate" data-language='en'>
													</div>
													<div class="col-xs-2 form-group" style="width:8% !important;">
														<span class="small">Ref. No</span> <input type="text" id="refno_1" autocomplete="off" name="reference_no[]" class="form-control ref-no">
													</div>
													<div class="col-xs-2 form-group" style="width:14% !important;">
														<span class="small">Description</span> <input type="text" id="description_1" autocomplete="off" name="description[]" class="form-control"> 
													</div>
													
													<div class="col-xs-1 form-group" style="width:7% !important;">
														<span class="small">Currency</span> 
														<select id="occrncy_1" class="form-control select2 curr" name="currency[]">
														    <option value="{{$settings->bcurrency_id}}">Select</option>
															@foreach($bcurrency as $curr)
															<option value="{{$curr->id}}">{{$curr->code}}</option>
															@endforeach
														</select>
													</div>
													
													<div class="col-xs-1 form-group" style="width:10% !important;">
														<span class="small lbamt">Amount</span> <input type="number" id="amount_1" step="any" autocomplete="off" name="amount[]" class="form-control line-amount">
													</div>
													
													<div class="col-xs-1 form-group" style="width:5% !important;">
														<span class="small">Rate</span> <input type="number" id="rate_1" step="any" autocomplete="off" name="rate[]" class="form-control rate" readonly>
													</div>
													<div class="col-xs-1 form-group" style="width:9% !important;">
														<span class="small lbamtfc">FC Amt</span> <input type="number" id="cnvtamt_1" step="any" autocomplete="off" name="cnvt_amt[]" class="form-control cnvt-amt">
													</div>
													<div class="col-xs-1 form-group" style="width:10% !important;">
														<span class="small">Salesman</span> 
														<select id="sman_1" class="form-control select2 slmn" name="slsman[]">
															<option value="">Select</option>
															@foreach($salesman as $sm)
															<option value="{{$sm['id']}}">{{$sm['name']}}</option>
															@endforeach
														</select>
													</div>
													<div class="col-xs-1 form-group" style="width:8% !important;">
														<span class="small">Job No</span> <input type="text" id="jbno_1" autocomplete="off" name="jobno[]" class="form-control">
													</div>
													<div class="col-xs-1 form-group" style="width:9% !important;">
														<span class="small">Due Date</span> <input type="text" id="duedate_1" autocomplete="off" name="duedate[]" class="form-control duedate">
													</div>
													<div class="col-xs-1 form-group" style="width:4% !important;">
														<br/>
														<button type="button" class="btn-success btn-danger btn-remove-item">
															<i class="fa fa-fw fa-minus-square"></i>
														</button><br/>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														</button>
													</div>
												</div>
												<?php } ?>
											</div>

										</div>
									</div>
									</fieldset>
								</div>
								
								
								<div id="ob_chqdetail">
									<fieldset>
									<legend><h5>Opening Balance Details</h5></legend>
									<div class="itemdivPrntch">
										<div class="itemdivChldch">							
										<div class="col-xs-12">
												<div class="col-sm-2 form-group" style="width:13%"> <span class="small">Amount </span>
													<input type="number" id="amountch_1" step="any" name="amount[]" autocomplete="off" class="form-control chline-amount">
												</div>
												<div class="col-sm-1 form-group" style="width:10%">
													 <span class="small">Bank </span>
													 <select id="bank_1" class="form-control select2 line-bank" style="width:100%" name="bank[]">
														<option value="">Select</option>
														@foreach($banks as $bank)
														<option value="{{$bank['id']}}">{{$bank['code']}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-2 form-group" style="width:10%">
													<span class="small">Cheque No.</span> 
													<input type="text" id="chqno_1" name="cheque_no[]" autocomplete="off" class="form-control">
												</div>
												<div class="col-sm-2 form-group" style="width:13%">
													<span class="small">Cheque Date</span> 
													<input type="text" id="chqdate_1" name="cheque_date[]" autocomplete="off" class="form-control chkdate" data-language='en'>
												</div>
												
												<div class="col-sm-1 form-group" style="width:7%"> 
													<span class="small">Tr.Type</span> 
													<select id="trtypech_1" class="form-control select2 linech-tr" style="width:100%" name="tr_type[]">
														<option value="Dr">Dr</option>
													</select>
												</div>
												
												<div class="col-sm-2 form-group" style="width:13%">
													<span class="small">Account Name</span> <input type="text" id="frmaccount_1" name="frmaccount_name[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal">
													<input type="hidden" id="frmaccountid_1" name="frmaccount_id[]" class="form-control">
													<input type="hidden" id="refnoc_1" name="reference_no[]" class="form-control">
												</div>
												<div class="col-sm-2 form-group" style="width:13%; display:none;">
													<span class="small">Tr. Date </span> 
													<input type="hidden" readonly name="tr_date[]">
													
												</div>
												<div class="col-sm-2 form-group" style="width:15%">
													<span class="small">Description</span> <input type="text" id="description_1" autocomplete="off" name="description[]" class="form-control"> 
												</div>
												<div class="col-sm-1" style="width:3%"><br/>
													 <button type="button" class="btn-success btn-danger btn-remove-itemch">
														<i class="fa fa-fw fa-minus-square"></i>
													</button>
													<button type="button" class="btn-success btn-add-itemch" >
														<i class="fa fa-fw fa-plus-square"></i>
													</button>
													
												</div>
											</div>
										</div>
									</div>
									</fieldset>
								</div>
								
								<hr/>
								<div class="form-group" id="trtype">
                                    <label for="input-text" class="col-sm-2 control-label">Transaction Type</label>
                                    <div class="col-sm-10">
                                        <select id="transaction" class="form-control select2" style="width:100%" name="transaction">
											<option value="Dr">Dr</option>
											<option value="Cr">Cr</option>
                                        </select>
                                    </div>
                                </div>
								
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Open Balance Net</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="op_balance" step="any" name="op_balance" placeholder="Open Balance">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">FC Open Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="fcop_balance" name="fcop_balance" step="any" placeholder="FC Open Balance">
                                    </div>
                                </div>
								<hr/>
                                
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                        <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											@if($deptid=='')
											<option value="">Select Department...</option>
											@endif
											@foreach($department as $dep)
											<option value="{{ $dep->id }}">{{ $dep->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
							
								<!-- <hr/> -->
								
								<?php if($formdata['vat_assignable']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Assignable?</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" id="vat_assign" name="vat_assign" value="1">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="vat_assignable" id="vat_assignable">
								<?php } ?>
								
								<div class="form-group" id="vatPntg">
                                    <label for="input-text" class="col-sm-2 control-label">VAt Percentage</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control" id="vat_perc" name="vat_percentage" placeholder="VAT %">
                                    </div>
                                </div>
								<hr/>
								
								<?php if($formdata['job_assignable']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Assignable?</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="job_assign" value="0" checked>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="job_assign" value="1">
                                            Yes
                                        </label>
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="job_assignable" id="job_assignable">
								<?php } ?>
												
								<?php if($formdata['job_compulsary']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Compulsary</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="job_compulsary" value="0" checked>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="job_compulsary" value="1">
                                            Yes
                                        </label>
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="job_compulsary" id="job_compulsary">
								<?php } ?>
								
								<?php if($formdata['hide']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Hide</label>
									<div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="is_hide" value="0" checked>
                                            No
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="is_hide" value="1">
                                            Yes
                                        </label>
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="hide" id="hide">
								<?php } ?>
                                
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
var cat;
$('#trdate_1').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#trdate_2').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#chqdate_1').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#viewDetails').hide();
$(document).ready(function () {
    initializeValidator();
    
	$('#vatPntg').toggle();
	//$('#addressDtls').toggle(); $('#ob_detail').toggle(); $('#trtype').toggle();
	//$('#ob_chqdetail').show(); 
	var catdft = $('#category').val(); 
	var typetr = $('#typetr').val(); 
	
	if( catdft=='CUSTOMER' || catdft=='SUPPLIER'){ 
			
			$('#actype').val('SupCus');
			if( $('#addressDtls').is(":hidden") )
				$('#addressDtls').toggle();
			
			if( $('#ob_detail').is(":hidden") ) {
				$('#ob_detail').toggle();
				$('#trtype_1').find('option').end().append('<option value="">Tr</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
			}
			
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
			
			if( $('#ob_chqdetail').is(":visible") )
				$('#ob_chqdetail').toggle();
			
			//$('#ob_chqdetail').html(''); //###############check it
			
		} else if(catdft=='PDCR' || catdft=='PDCI'){
			$('#actype').val('Chq');
			if( $('#addressDtls').is(":visible") )
				$('#addressDtls').toggle();
			
			if( $('#ob_chqdetail').is(":hidden") ) {
				$('#ob_chqdetail').toggle();
			}

			if(catdft=='PDCR')
				$('#trtypech_1').find('option').remove().end().append('<option value="Dr">Dr</option>');
			else if(catdft=='PDCI')
				$('#trtypech_1').find('option').remove().end().append('<option value="Cr">Cr</option>');

				//$('#ob_detail').html(''); //###############check it
				
			if( $('#ob_detail').is(":visible") )
					$('#ob_detail').toggle();
				
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
		} else {
			
			 $("#transaction option[value='"+typetr+"']").attr("selected", "selected");
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
		
		
	
	$('.btn-remove-item').hide();
	
	$('.chline-amount').on('blur',  function(e) { 
		var res = getLineTotalChq();
		$('#op_balance').val(res.toFixed(2));

    		$('#frmMaster').bootstrapValidator('addField', 'bank[]');
    		$('#frmMaster').bootstrapValidator('addField', 'cheque_no[]');
    		$('#frmMaster').bootstrapValidator('addField', 'cheque_date[]');
    		$('#frmMaster').bootstrapValidator('addField', 'frmaccount_name[]');
    		
    		// Dynamically add a validation rule
            $('#frmMaster').data('bootstrapValidator')
                .addField('bank[]', {
                    validators: {
                        notEmpty: {
                            message: 'Reference no is required!'
                        }
                    }
            });
            
            $('#frmMaster').data('bootstrapValidator')
                .addField('cheque_no[]', {
                    validators: {
                        notEmpty: {
                            message: 'Cheque no is required!'
                        }
                    }
            });
            
            $('#frmMaster').data('bootstrapValidator')
                .addField('cheque_date[]', {
                    validators: {
                        notEmpty: {
                            message: 'Cheque date is required!'
                        }
                    }
            });
            
            $('#frmMaster').data('bootstrapValidator')
                .addField('frmaccount_name[]', {
                    validators: {
                        notEmpty: {
                            message: 'Party account is required!'
                        }
                    }
            });
	   
	});
	
    
});

function initializeValidator() {
    var urlname = "{{ url('account_master/checkname/') }}";
    var urlacno = "{{ url('account_master/checkacno/') }}";
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
								return { master_name: validator.getFieldElements('master_name').val() };
							  },
							  message: 'This account name is already exist!'
                    }
                }
            },
            account_no: { validators: { 
					remote: { url: urlacno,
							  data: function(validator) {
								return { account_no: validator.getFieldElements('account_no').val() };
							  },
							  message: 'This account no is already exist!'
                    }
                }
            },
			//'tr_type[]': { validators: { notEmpty: { message: 'The transaction type is required and cannot be empty!' } } },
			//'reference_no[]': { validators: { notEmpty: { message: 'The reference no is required and cannot be empty!' } } },
			//'tr_date[]': { validators: { notEmpty: { message: 'The transaction date is required and cannot be empty!' } } },
            //'amount[]': { validators: { notEmpty: { message: 'The amount is required and cannot be empty!' } } },
			/*'cheque_no[]': { validators: { notEmpty: { message: 'The cheque no is required and cannot be empty!' } } },
			'cheque_date[]': { validators: { notEmpty: { message: 'The cheque date is required and cannot be empty!' } } },
			'bank[]': { validators: { notEmpty: { message: 'The bank is required and cannot be empty!' } } },
			'frmaccount_name[]': { validators: { notEmpty: { message: 'Account name is required and cannot be empty!' } } }*/
        }
        
    }).on('reset', function (event) {
        $('#frmMaster').data('bootstrapValidator').resetForm();
    });
}

$(document).on('click', '.DLS2', function(e) { e.preventDefault();
	$('#viewDetails').toggle();
});

$('.fcurrency').on('change', function(e){
	var fcid = e.target.value;
	$('.lbamt').html('FC Amt');
	$('.lbamtfc').html('Amount');
	console.log(fcid);
	$.get("{{ url('currency/getcurrency/') }}/" + fcid, function(data) {
	$('.curr').empty();
	$.each(data, function(value, display){
	$('.curr').append('<option value="' + display.id + '">' + display.code + '</option>');
	});
	});
	$.get("{{ url('currency/getrate/') }}/" + fcid, function(data) {
			$('.rate').val((data==0)?1:data);
			
			var amt = parseFloat( ($('.line-amount').val()=='') ? 0 :  $('.line-amount').val() );
			var rate = parseFloat( ($('.rate').val()=='') ? 1 :  $('.rate').val() );
			$('.cnvt-amt').val( amt * rate);
		});
	getLineTotal();	
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

//SEP25
$(document).on('keyup', '.cnvt-amt', function(e) { e.preventDefault();
//$('.cnvt-amt').on('keyup', function(e) {
    
    var res = this.id.split('_');
	var curNum = res[1]; 

    var fcamt = parseFloat( ($('#cnvtamt_'+curNum).val()=='') ? 0 :  $('#cnvtamt_'+curNum).val() );
	var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
	var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
	if(rate > 1)
	    $('#amount_'+curNum).val( fcamt * rate);
	else
	    $('#cnvtamt_'+curNum).val( amt * rate);
    
    var res = getLineTotal();    
	$('#op_balance').val(res.toFixed(2));
});

$(document).on('blur', '.cnvt-amt', function(e) { e.preventDefault();
//$('.cnvt-amt').on('keyup', function(e) {
    getLineTotal();
    var res = this.id.split('_');
	var curNum = res[1]; 

    var fcamt = parseFloat( ($('#cnvtamt_'+curNum).val()=='') ? 0 :  $('#cnvtamt_'+curNum).val() );
	var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
	var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
	if(rate > 1)
	    $('#amount_'+curNum).val( fcamt * rate);
	else
	    $('#cnvtamt_'+curNum).val( amt * rate);
    
    
});



$('#group_id').on('change', function(e){
	var group_id = e.target.value;
	$.get("{{ url('account_master/getcode/') }}/" + group_id, function(data) {
		var val = $.parseJSON(data);
		$('#account_id').val(val.code);
		$('#category').val(val.category);
		$('#typetr').val(val.trtype);
		cat = val.category; var ttype = val.trtype;
		
		if( cat=='CUSTOMER' || cat=='SUPPLIER'){ 
			$('#frmMaster #area_id').show();
			 $('#frmMaster #vat_no').show();
			$('#actype').val('SupCus');
			if( $('#addressDtls').is(":hidden") )
				$('#addressDtls').toggle();
			
			if( $('#ob_detail').is(":hidden") ) {
				$('#ob_detail').toggle();
				$('#trtype_1').find('option').remove().end();
				$('#trtype_1').find('option').end().append('<option value="">Tr</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
			}
			
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
			
			if( $('#ob_chqdetail').is(":visible") )
				$('#ob_chqdetail').toggle();
			
		} else if(cat=='PDCR' || cat=='PDCI'){ 
		    $('#frmMaster #area_id').hide();
			 $('#frmMaster #vat_no').hide();
			$('#actype').val('Chq');
			if( $('#addressDtls').is(":visible") )
				$('#addressDtls').toggle();
			
			if( $('#ob_chqdetail').is(":hidden") ) {
				$('#ob_chqdetail').toggle();
				$('#trtype_1').find('option').remove().end();
			}

			if(cat=='PDCR')
				$('#trtypech_1').find('option').remove().end().append('<option value="Dr">Dr</option>');
			else if(cat=='PDCI') { console.log(cat)
				$('#trtypech_1').find('option').remove().end().append('<option value="Cr">Cr</option>');
			}
			
			if( $('#ob_detail').is(":visible") )
					$('#ob_detail').toggle();
				
			if( $('#trtype').is(":visible") )
				$('#trtype').toggle();
		} else {
			 $('#frmMaster #area_id').hide();
			  $('#frmMaster #vat_no').hide();
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
		//console.log(data.code); console.log(data);
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
			
			var fcamt = parseFloat( ($('#cnvtamt_'+n).val()=='') ? 0 :  $('#cnvtamt_'+n).val() );
        	var rate = parseFloat( ($('#rate_'+n).val()=='') ? 1 :  $('#rate_'+n).val() );
        	var amt = parseFloat( ($('#amount_'+n).val()=='') ? 0 :  $('#amount_'+n).val() );
        	if(rate > 1)
        	    var amnt = parseFloat(fcamt) * rate; //$('#amount_'+n).val( fcamt * rate);
        	else
        	    var amnt = parseFloat(this.value) * rate; //$('#cnvtamt_'+n).val( amt * rate);
	    
	    
			//var rate = parseFloat( ($('#rate_'+n).val()=='') ? 1 :  $('#rate_'+n).val() );
			//var amnt = parseFloat(this.value) * rate;
		
			if( $('#trtype_'+n+' option:selected').val()=='Dr' )
				dramount = dramount + amnt;
			else if( $('#trtype_'+n+' option:selected').val()=='Cr' )
				cramount = cramount + amnt;
			
			amount = dramount - cramount;
		}
			
	}); return amount; 
}

function getLineTotalFC() {
	var lineTotal = 0; var dramount = 0; var cramount = 0; var amount = 0;
	$( '.line-amount' ).each(function() {
		
		if(this.value!='') {
			var elmnt = this.id;
			var res = elmnt.split('_');
			var n = res[1];
			
			//var rate = parseFloat( ($('#rate_'+n).val()=='') ? 1 :  1 );
			//var amnt = parseFloat(this.value) * rate;
			
			var fcamt = parseFloat( ($('#cnvtamt_'+n).val()=='') ? 0 :  $('#cnvtamt_'+n).val() );
        	var rate = parseFloat( ($('#rate_'+n).val()=='') ? 1 :  $('#rate_'+n).val() );
        	var amt = parseFloat( ($('#amount_'+n).val()=='') ? 0 :  $('#amount_'+n).val() );
        	if(rate > 1)
        	    var amnt = parseFloat(fcamt) * rate; //$('#amount_'+n).val( fcamt * rate);
        	else
        	    var amnt = parseFloat(this.value) * rate; //$('#cnvtamt_'+n).val( amt * rate);
			
		
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

function validateCheque(no) { 
	var curId = 'chqno_'+no;
	var curChq = $('#chqno_'+no).val();
	var curBank = $('#bank_'+no+' option:selected').val();
	var curIdBnk = 'bank_'+no;
	var curAc = $('#frmaccountid_'+no).val();
	var curIdAc = 'frmaccountid_'+no;
	

	$('input[name^="cheque_no[]"]').each(function() {
		var r = this.id.split('_');
		var n = r[1];
		var runChq = $('#chqno_'+n).val();
		var runBank = $('#bank_'+n+' option:selected').val();
		var runIdBnk = 'bank_'+n;
		var runAc = $('#frmaccountid_'+n).val();
		var runIdAc = 'frmaccountid_'+n;
		if((curChq==runChq && curId != this.id) && (curBank==runBank && curIdBnk != runIdBnk) && (curAc==runAc && curIdAc != runIdAc)) {
			alert('Cheque no is duplicate!');
			$('#chqno_'+no).val('');
			//$('#frmMaster').bootstrapValidator('resetField', "cheque_no[]");
			$('#frmMaster').bootstrapValidator('resetForm', false);
		}
	});

}

function checkChequeNo(curNum) {

	var bank = $('#bank_'+curNum+' option:selected').val();
	var ac = $('#frmaccountid_'+curNum).val();
	var chqno = $('#chqno_'+curNum).val();

	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+chqno+'&bank_id='+bank+'&ac_id='+ac,
		success: function(data) {  
			if(data=='') {
				alert('Cheque no is duplicate!');
				$('#chqno_'+curNum).val('');
				$('#frmMaster').bootstrapValidator('resetField', "cheque_no[]");
			}
		}
	})
}

$(function() {

	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		e.preventDefault();
		var res = this.id.split('_');
		var no = res[1];
		validateCheque(no);

	});
	
	$(document).on('change', '.line-bank', function(e) {
	    e.preventDefault();
		var res = this.id.split('_');
		var no = res[1]; 
		validateCheque(no);
		
	});  


	$('input[type=number]').on('mousewheel',function(e){ $(this).blur(); }); //NEW CHNG...
	var rowNum = 1;
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-tr')).attr('id', 'trtype_' + rowNum);
			//newEntry.find($('.linech-tr')).attr('id', 'trtypech_' + rowNum);
			newEntry.find($('input[name="tr_date[]"]')).attr('id', 'trdate_' + rowNum);
			newEntry.find($('input[name="reference_no[]"]')).attr('id', 'refno_' + rowNum);
			newEntry.find($('input[name="description[]"]')).attr('id', 'description_' + rowNum);
			newEntry.find($('input[name="amount[]"]')).attr('id', 'amount_' + rowNum);
			//newEntry.find($('input[name="amountch[]"]')).attr('id', 'amountch_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chqno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chqdate_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bank_' + rowNum);
			
			newEntry.find($('input[name="rate[]"]')).attr('id', 'rate_' + rowNum);
			newEntry.find($('input[name="cnvt_amt[]"]')).attr('id', 'cnvtamt_' + rowNum);
			newEntry.find($('.curr')).attr('id', 'occrncy_' + rowNum);
			
			newEntry.find($('input[name="loc_proj[]"]')).attr('id', 'locproj_' + rowNum);
			newEntry.find($('input[name="eqp_type[]"]')).attr('id', 'eqptype_' + rowNum);
			newEntry.find($('input[name="lpo_no[]"]')).attr('id', 'lpono_' + rowNum);

			newEntry.find($('.slmn')).attr('id', 'sman_' + rowNum);
			newEntry.find($('input[name="jobno[]"]')).attr('id', 'jbno_' + rowNum);
			newEntry.find($('input[name="duedate[]"]')).attr('id', 'duedate_' + rowNum);
			
			newEntry.find('input').val(''); 
			
			
		var curid=	$('#currency_id').val();
		if(curid==''){
		    newEntry.find($('#rate_'+rowNum)).val(1);
		}
		else{
			$.get("{{ url('currency/getrate/') }}/" + curid, function(data) {
			$('#rate_'+rowNum).val(data);
			//console.log(data);
		});
		}
			
			$('.duedate').datepicker({
				language: 'en',autoClose:true,
				dateFormat: 'dd-mm-yyyy'
			});
			
			$('.trdate').datepicker({
				language: 'en',autoClose:true,
				dateFormat: 'dd-mm-yyyy',maxDate: new Date('{{$obfrom}}') //minDate: new Date('{{$obfrom}}'),
			});
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//NEW CHNG...
		$(this).parents('.itemdivChld:first').remove();
		
		var res = getLineTotal();
		$('#op_balance').val(res.toFixed(2));
		var curid=	$('#currency_id').val();
		if(curid!=''){
			var fcres = getLineTotalFC(); 
		$('#fcop_balance').val(fcres.toFixed(2));
		}
				
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	$(document).on('click', '.datepicker--cell-day', function(e) { 
	   $('#frmMaster').bootstrapValidator('revalidateField', 'cheque_date[]');
	    
	});
	
		$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
	//	$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#fcop_balance').val(data);
			//console.log(data);
		});
	});
	
	
	//cheque transaction entry form......
	$(document).on('click', '.btn-add-itemch', function(e)  { 
        rowNum++; 
		e.preventDefault();
		var catdft = $('#category').val(); 
		
        if(catdft=='PDCR')
            var trnType = 'Dr';
        else if(catdft=='PDCI')
            var trnType = 'Cr';
        var frmTmplate = `<div class="itemdivChldch">
                           <div class="col-xs-12">
                			<div class="form-group">
								<div class="col-sm-2" style="width:13%"> <span class="small">Amount </span>
									<input type="number" id="amountch_`+rowNum+`" step="any" name="amount[]" autocomplete="off" class="form-control chline-amount">
								</div>
								<div class="col-sm-1" style="width:10%">
									 <span class="small">Bank </span>
									 <select id="bank_`+rowNum+`" class="form-control select2 line-bank" style="width:100%" name="bank[]">
										<option value="">Select</option>
										@foreach($banks as $bank)
										<option value="{{$bank['id']}}">{{$bank['code']}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-2" style="width:10%">
									<span class="small">Cheque No.</span> 
									<input type="text" id="chqno_`+rowNum+`" name="cheque_no[]" autocomplete="off" class="form-control">
								</div>
								<div class="col-sm-2" style="width:13%">
									<span class="small">Cheque Date</span> 
									<input type="text" id="chqdate_`+rowNum+`" name="cheque_date[]" autocomplete="off" class="form-control chkdate" data-language='en'>
								</div>
								
								<div class="col-sm-1" style="width:7%"> 
									<span class="small">Tr.Type</span> 
									<select id="trtypech_`+rowNum+`" class="form-control select2 linech-tr" style="width:100%" name="tr_type[]">
										<option value="`+trnType+`">`+trnType+`</option>
									</select>
								</div>
								
								<div class="col-sm-2" style="width:13%">
									<span class="small">Account Name</span> <input type="text" id="frmaccount_`+rowNum+`" name="frmaccount_name[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal">
									<input type="hidden" id="frmaccountid_`+rowNum+`" name="frmaccount_id[]" class="form-control">
									<input type="hidden" id="refnoc_`+rowNum+`" name="reference_no[]" class="form-control">
								</div>
								<div class="col-sm-2" style="width:13%; display:none;">
									<span class="small">Tr. Date </span> 
									<input type="hidden" readonly name="tr_date[]">
									
								</div>
								<div class="col-sm-2" style="width:15%">
									<span class="small">Description</span> <input type="text" id="description_`+rowNum+`" autocomplete="off" name="description[]" class="form-control"> 
								</div>
								<div class="col-sm-1" style="width:3%"><br/>
									 <button type="button" class="btn-success btn-danger btn-remove-itemch">
										<i class="fa fa-fw fa-minus-square"></i>
									</button>
									<button type="button" class="btn-success btn-add-itemch" >
										<i class="fa fa-fw fa-plus-square"></i>
									</button>
								</div>	
								</div>
							</div>
                		</div>`;
                		
        $('.itemdivPrntch').append(frmTmplate);
        
        $('.chkdate').datepicker({
    		language: 'en',autoClose:true,
    		dateFormat: 'dd-mm-yyyy'
    	});
    	
    	$('#frmMaster').bootstrapValidator('addField', "bank[]");
	    $('#frmMaster').bootstrapValidator('addField', "cheque_no[]"); 
	    $('#frmMaster').bootstrapValidator('addField', "cheque_date[]");
	    $('#frmMaster').bootstrapValidator('addField', "frmaccount_name[]");
			
    }).on('click', '.btn-remove-itemch', function(e)
    { 
		//NEW CHNG...
		$(this).parents('.itemdivChldch:first').remove();
		
		var res = getLineTotalChq();
		$('#op_balance').val(res.toFixed(2));
		$('#cl_balance').val(res.toFixed(2));
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-itemch').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-itemch').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	$('.trdate').datepicker({
		language: 'en',autoClose:true,
		dateFormat: 'dd-mm-yyyy',maxDate: new Date('{{$obfrom}}')//minDate: new Date('{{$obfrom}}'),
	});
	
	$('.chtrdate').datepicker({
		language: 'en',autoClose:true,
		dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$obfrom}}'),maxDate: new Date('{{$obto}}')
	});
	
	$('.chkdate').datepicker({
		language: 'en',autoClose:true,
		dateFormat: 'dd-mm-yyyy'
	});

	$('.duedate').datepicker({
		language: 'en',autoClose:true,
		dateFormat: 'dd-mm-yyyy'
	});
	
	$(document).on('blur', '.line-amount', function(e) {
		var res = getLineTotal(); 
		$('#op_balance').val(res.toFixed(2));
		var curid=	$('#currency_id').val();
		if(curid!=''){
			var fcres = getLineTotalFC(); 
		    $('#fcop_balance').val(fcres.toFixed(2));
		}
		$('#frmMaster').bootstrapValidator('addField', 'reference_no[]');
		
		// Dynamically add a validation rule
        $('#frmMaster').data('bootstrapValidator')
            .addField('reference_no[]', {
                validators: {
                    notEmpty: {
                        message: 'Reference no is required!'
                    }
                }
            });

	});
	
	
	
	
	
	/*$(document).on('blur', 'input[name="reference_no[]"]', function(e) {
		var refno = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		$.ajax({
			url: "{{ url('account_master/check_refno/') }}",
			type: 'get',
			data: 'refno='+refno,
			success: function(data) { 
				if(data=='') {
					alert('Reference no is duplicate!');
					$('#refno_'+curNum).val('');$('#refnoc_'+curNum).val('');
				}
			}
		})
	});*/
	
	/*$(document).on('blur', 'input[name="tr_date[]"]', function(e) {
		var trndate = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		$.ajax({
			url: "{{ url('account_master/check_trndate/') }}",
			type: 'get',
			data: 'trndate='+trndate,
			success: function(data) { console.log(data);
				if(data=='') {
					alert('Invalid transaction date, should be preior to Accounting period!');
					$('#trdate_'+curNum).val('');
				}
			}
		})
	});*/
	
	/*$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1];
		checkChequeNo(curNum);
	});*/
	
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
	});
	
	//new change.................
	$(document).on('click', '.custRow', function(e) { 
		var num = $('#num').val();
		$('#frmaccount_'+num).val( $(this).attr("data-name") );
		$('#frmaccountid_'+num).val( $(this).attr("data-id") );
		 
		validateCheque(num);
		checkChequeNo(num);
		$('#frmMaster').bootstrapValidator('revalidateField', 'frmaccount_name[]');
	});
	
	$('.onacnt_icheck').on('ifChecked', function(event){ 
		$('#vatPntg').toggle();
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#vatPntg').toggle();
	});
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	$(document).on('change', '.curr', function(e) { //$('.oc-curr').on('change', function(e){
		var res = this.id.split('_');
		var curNum = res[1]; 
		var curr_id = e.target.value; 
		
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#rate_'+curNum).val((data==0)?1:data);
			
			//var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
			//var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
			//$('#cnvtamt_'+curNum).val( amt * rate);
			
			//------
			var fcamt = parseFloat( ($('#cnvtamt_'+curNum).val()=='') ? 0 :  $('#cnvtamt_'+curNum).val() );
        	var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
        	var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
        	if(rate > 1)
        	    $('#amount_'+curNum).val( fcamt * rate);
        	else
        	    $('#cnvtamt_'+curNum).val( amt * rate);
		});
		
		getLineTotal();
	});
	
	$(document).on('keyup', '.line-amount,.rate', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		//var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
		//var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
	    //$('#cnvtamt_'+curNum).val( amt * rate);
		
		//-----
		var fcamt = parseFloat( ($('#cnvtamt_'+curNum).val()=='') ? 0 :  $('#cnvtamt_'+curNum).val() );
    	var rate = parseFloat( ($('#rate_'+curNum).val()=='') ? 1 :  $('#rate_'+curNum).val() );
    	var amt = parseFloat( ($('#amount_'+curNum).val()=='') ? 0 :  $('#amount_'+curNum).val() );
    	if(rate > 1)
    	    $('#amount_'+curNum).val( fcamt * rate);
    	else
    	    $('#cnvtamt_'+curNum).val( amt * rate);
		
		//getLineTotal();
		var res = getLineTotal(); 
		$('#op_balance').val(res.toFixed(2));
		var curid=	$('#currency_id').val();
		if(curid!=''){
			var fcres = getLineTotalFC(); 
		$('#fcop_balance').val(fcres.toFixed(2));
		}
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

$(document).on('blur', 'input[name="reference_no[]"]', function(e) { 
	let myarray = []; 
		var r = (this.id).split('_');
		var k = r[1]; 
	$( '.ref-no' ).each(function() { 
		var elmnt = this.id;
		var res = elmnt.split('_');
		var n = res[1]; 
		myarray.push($("#refno_" + n).val());
	});

	for (var i = 0; i < myarray.length; i++) {
		for (var j = i + 1; j < myarray.length; j++) {
			if (i == j || myarray[i] == "" || myarray[j] == "")
				continue;
			if (myarray[i] == myarray[j]) {
				$('#refno_'+k).val(''); $('#refno_'+k).focus();
				alert('Reference no is duplicate!');
				return false;
			}
		}
	}
});

$(document).on('keyup', '#op_balance', function(e) { 
	var tamt = getLineTotal(); console.log(tamt);
	if(tamt > 0) {
	    alert('Amount editing not allowed!');
	    $('#op_balance').val(tamt);
	    return false;
	}
});


</script>
@stop
