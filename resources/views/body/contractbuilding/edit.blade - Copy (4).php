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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	 
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->

@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
              Contract 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Contract </a>
                </li>
                <li class="active">
                    View Contract
                </li>
            </ol>
        </section>
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Contract 
                            </h3>
                           
						   <div class="pull-right">
								<a href="{{ url('contractbuilding/add') }}" class="btn btn-info btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
									</span> Add New
								</a>
							</div>
                        </div>
						
						<div class="panel-body">
                             
							<div class="bs-example">
                                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                    <li {{(Session::get('active')=="home") ? "class=active" : ""}}>
                                        <a href="#home" data-toggle="tab">Contract Entry</a>
                                    </li>
                                    <li {{(Session::get('active')=="rentallo") ? "class=active" : ""}}>
                                        <a href="#rentallo" data-toggle="tab">Rent Allocation</a>
                                    </li>
									<li {{(Session::get('active')=="receipt") ? "class=active" : ""}}>
                                        <a href="#receipt" data-toggle="tab">Receipt</a>
                                    </li>
									<li {{(Session::get('active')=="deposit") ? "class=active" : ""}}>
                                        <a href="#deposit" data-toggle="tab">Deposit RV</a>
                                    </li>
									<li {{(Session::get('active')=="otherrv") ? "class=active" : ""}}>
                                        <a href="#otherrv" data-toggle="tab">Other RV</a>
                                    </li>
									<li {{(Session::get('active')=="payment") ? "class=active" : ""}}>
                                        <a href="#payment" data-toggle="tab">Payment</a>
                                    </li>
                                </ul>
								
                                <div id="myTabContent" class="tab-content">
									<div class="tab-pane fade {{(Session::get('active')=='home') ? ' active in' : '' }}" id="home">
										<form class="form-horizontal" role="form" method="POST" name="frmContract" id="frmContract" action="{{url('contractbuilding/update/'.$crow->id)}}">
											<input type="hidden" name="_token" value="{{ csrf_token() }}">
											<br/>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Building</label>
												<div class="col-sm-4">
													<select class="form-control" name="building_id" id="building_id"/>
													<option value="">Select</option>
													@foreach($buildingmaster as $row)
													<option value="{{$row->id}}" {{($crow)?(($crow->building_id==$row->id)?'selected':''):''}}>{{$row->buildingcode}}</option>
													@endforeach
													</select>
												</div>
												
												<label for="input-text" class="col-sm-2 control-label">Contract Date</label>
												<div class="col-sm-4">
													<input type="text" readonly  class="form-control" id="con_date" name="date" value="{{($crow)?date('d-m-Y',strtotime($crow->contract_date)):''}}" data-language='en'  placeholder="{{date('d-m-Y')}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Contract No.</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="contract_no" id="contract_no" value="{{($crow)?$crow->contract_no:''}}" readonly >
												</div>
												
												<label for="input-text" class="col-sm-2 control-label">SI. No.</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" name="si_no" readonly value="{{($crow)?$crow->si_no:$sirow->voucher_no}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label"><b>Tenant</b></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="customer_account" value="{{($crow)?$crow->master_name:''}}" name="customer_account" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
													<input type="hidden" id="customer_id" name="customer_id" value="{{$crow->customer_id}}">
													<input type="hidden" name="je_id[]" value="{{$jerow[0]->id}}">
													<input type="hidden" name="jid" value="{{$jerow[0]->jid}}">
												</div>
												
												<label for="input-text" class="col-sm-2 control-label">Flat</label>
												<div class="col-sm-4">
													<select class="form-control" name="flat_no" id="flat_id" />
													@if($crow)
													<option value="{{$crow->flat_no}}">{{$crow->flat}}</option>
													@endif
													@foreach($flats as $flat)
													<option value="{{$flat->id}}">{{$flat->flat_no}}</option>
													@endforeach
													</select>
												</div>
											</div>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Contarct Start Date</label>
											<div class="col-sm-4">
												<input type="text" class="form-control pull-right" name="start_date" value="{{($crow)?date('d-m-Y',strtotime($crow->start_date)):''}}" autocomplete="off" data-language='en' readonly id="start_date" />
											</div>
										
											<label for="input-text" class="col-sm-2 control-label">Contarct End Date</label>
											<div class="col-sm-4">
												<input type="text" class="form-control pull-right" name="end_date" value="{{($crow)?date('d-m-Y',strtotime($crow->end_date)):''}}" data-language='en' autocomplete="off" readonly id="end_date" />
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Contract Duration</label>
											<div class="col-sm-3" id="durM">
												<select class="form-control" name="duration" id="duration" />
												<option value="">Duration</option>
												@foreach($duration as $dur)
												<option value="{{$dur->duration_month}}" {{($crow)?(($crow->duration==$dur->duration_month)?'selected':''):''}}>{{$dur->duration_month}} Months</option>
												@endforeach
												</select>
											</div>
											<div class="col-sm-3" id="durD">
												<input type="text" class="form-control" id="durationD" name="durationD" value="{{$crow->duration}}" placeholder="Duration in Days">
											</div>
											
											<div class="col-sm-1">
												<input type="checkbox"  name="chkedit" id="chkedit" value="1" {{($crow->is_day==1)?"checked":""}}> Edit
											</div>
											
											<label for="input-text" class="col-sm-2 control-label"><b>Rent Amount</b></label>
											<div class="col-sm-4">
												<input type="number" class="form-control" id="rent_amount" value="{{($crow)?$crow->rent_amount:''}}" step="any" name="rent_amount" autocomplete="off" placeholder="Rent Amount">
											</div>
										</div>
										
										
										
										<h5 class="DT2"><a href="">View Owner Details</a></h5>
										<div id="owndetils">
										<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Owner Name</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="owner_name" name="owner_name" value="{{($crow)?$crow->master_name:''}}" readonly placeholder="Owner Name">
												</div>
											
												<label for="input-text" class="col-sm-2 control-label">Location </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="location" name="location" value="{{($crow)?$crow->master_name:''}}" readonly placeholder="Location">
												</div>
											</div>
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Plot No.</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="plot_no" name="plot_no" value="{{($crow)?$crow->master_name:''}}" readonly placeholder="Plot No.">
												</div>
											
												<label for="input-text" class="col-sm-2 control-label">Contact No. </label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="contact_no" name="passport_no" value="{{($crow)?$crow->master_name:''}}" readonly placeholder="Contact No.">
												</div>
											</div>
										</div>
											<hr/>
											
											 <div class="form-group">
												<label for="input-text" class="col-sm-3 control-label"><b>Contract Amount</b></label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="amt" value="{{($crow)?$crow->rent_amount:''}}" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Prepaid Income</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_1" name="acname[]" value="{{($acrow[0])?$acrow[0]->acname:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_1" name="acid[]" value="{{$acrow[0]->account_id}}">
													<input type="hidden" name="je_id[]" value="{{$jerow[1]->id}}">
													<input type="hidden" id="is_day" value="{{($crow)?$crow->is_day:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" id="acamt_1" step="any" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->prepaid_income])?$acamt[$acrow->prepaid_income]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" id="actax_1" step="any" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->prepaid_income])?$acamt[$acrow->prepaid_income]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_1" value="{{($acrow)?$acrow->pi_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Deposit @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[1]) && $payacnts[1]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->deposit:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_2" name="acname[]" value="{{($acrow)?$acrow->acname2:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_2" name="acid[]" value="{{$acrow->deposit}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[2])?$jerow[2]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_2" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->deposit])?$acamt[$acrow->deposit]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_2" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->deposit])?$acamt[$acrow->deposit]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_2" value="{{($acrow)?$acrow->d_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Security Deposit @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[2]) && $payacnts[2]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->water_ecty:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_3" name="acname[]" value="{{($acrow)?$acrow->acname3:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_3" name="acid[]" value="{{$acrow->water_ecty}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[3])?$jerow[3]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_3" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->water_ecty])?$acamt[$acrow->water_ecty]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_3" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->water_ecty])?$acamt[$acrow->water_ecty]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_3" value="{{($acrow)?$acrow->we_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Commission @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[3]) && $payacnts[3]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->commission:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_4" name="acname[]" value="{{($acrow)?$acrow->acname4:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_4" name="acid[]" value="{{$acrow->commission}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[4])?$jerow[4]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_4" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->commission])?$acamt[$acrow->commission]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_4" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->commission])?$acamt[$acrow->commission]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_4" value="{{($acrow)?$acrow->c_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Other Deposit A/c. @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[4]) && $payacnts[4]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->other_deposit:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_5" name="acname[]" value="{{($acrow)?$acrow->acname5:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_5" name="acid[]" value="{{$acrow->other_deposit}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[5])?$jerow[5]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_5" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->other_deposit])?$acamt[$acrow->other_deposit]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_5" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->other_deposit])?$acamt[$acrow->other_deposit]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_5" value="{{($acrow)?$acrow->od_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Parking Imcome A/c. @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[5]) && $payacnts[5]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->parking:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_6" name="acname[]" value="{{($acrow)?$acrow->acname6:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_6" name="acid[]" value="{{$acrow->parking}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[6])?$jerow[6]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_6" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->parking])?$acamt[$acrow->parking]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_6" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->parking])?$acamt[$acrow->parking]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_6" value="{{($acrow)?$acrow->p_tax:''}}">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-3 control-label">Ejarie Fee A/c. @if($crow && $crow->renew_id!='')<input type="checkbox" {{ (isset($payacnts[6]) && $payacnts[6]->is_add==1) ? "checked" : "" }} name="check[{{($acrow)?$acrow->ejarie_fee:''}}]" value="1"> @endif</label>
												<div class="col-sm-5">
													<input type="text" class="form-control" id="acname_7" name="acname[]" value="{{($acrow)?$acrow->acname7:''}}" data-toggle="modal" data-target="#ac_modal" readonly>
													<input type="hidden" id="acid_7" name="acid[]" value="{{$acrow->ejarie_fee}}">
													<input type="hidden" name="je_id[]" value="{{isset($jerow[7])?$jerow[7]->id:''}}">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_7" name="acamount[]" value="{{($acrow)?(isset($acamt[$acrow->ejarie_fee])?$acamt[$acrow->ejarie_fee]['amount']:''):''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_7" name="actax[]" value="{{($acrow)?(isset($acamt[$acrow->ejarie_fee])?$acamt[$acrow->ejarie_fee]['tax']:''):''}}" placeholder="Tax">
													<input type="hidden" id="istx_7" value="{{($acrow)?$acrow->ef_tax:''}}">
												</div>
											</div>
											<!-- NOV26 -->
											<input type="hidden" name="je_id[]" value="{{isset($jerow[8])?$jerow[8]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[9])?$jerow[9]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[10])?$jerow[10]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[11])?$jerow[11]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[12])?$jerow[12]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[13])?$jerow[13]->id:''}}">
											<input type="hidden" name="je_id[]" value="{{isset($jerow[14])?$jerow[14]->id:''}}">
											<div class="form-group">
												<label for="input-text" class="col-sm-8 control-label"><b>Total</b></label>
												<div class="col-sm-4">
													<input type="number" class="form-control" id="total" step="any" name="total" value="{{$total}}" readonly placeholder="Total">
												</div>
											</div><div class="form-group">
												<label for="input-text" class="col-sm-8 control-label"><b>Tax Total</b></label>
												<div class="col-sm-4">
													<input type="number" class="form-control" id="txtotal" step="any" name="tax_total" value="{{$txtotal}}" readonly placeholder="Tax Total">
												</div>
											</div>
											<div class="form-group">
												<label for="input-text" class="col-sm-8 control-label"><b>Grand Total</b></label>
												<div class="col-sm-4">
													<input type="number" class="form-control" id="gtotal" step="any" name="grand_total" value="{{($crow)?$crow->grand_total:''}}" readonly placeholder="Grand Total">
												</div>
											</div>
											
											
											<br/>
											<fieldset><legend><h4 class="DT"><a href="">View Tenant Details</a></h4></legend>
											<div id="tenantDtls">
												<div class="form-group">
													<label for="input-text" class="col-sm-2 control-label">Passport No.</label>
													<div class="col-sm-4">
														<input type="text" class="form-control" id="passport_no" name="passport_no" autocomplete="off" placeholder="Passport No.">
													</div>
												
													<label for="input-text" class="col-sm-2 control-label">Passport Exp. </label>
													<div class="col-sm-4">
														<input type="text" class="form-control" id="passport_exp" name="passport_exp" autocomplete="off" placeholder="Passport Exp.">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-2 control-label">Nationality </label>
													<div class="col-sm-4">
														<input type="text" class="form-control" id="nationality" name="nationality" autocomplete="off" placeholder="Nationality">
													</div>
												
													<label for="input-text" class="col-sm-2 control-label">Document</label>
													<div class="col-sm-4">
														<input id="input-23" name="document" type="file" class="file-loading" data-show-preview="true">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-2 control-label">File No.</label>
													<div class="col-sm-4">
													<input type="text" class="form-control" id="file_no" name="file_no" autocomplete="off" placeholder="File No.">
													</div>
													
													<label for="input-text" class="col-sm-2 control-label">Terms Of Payment </label>
													<div class="col-sm-4">
													<input type="text" class="form-control" id="terms" name="terms" autocomplete="off" placeholder="Terms Of Payment">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-2 control-label">Observations </label>
													<div class="col-sm-10">
													<input type="text" class="form-control" id="observations" name="observations" autocomplete="off" placeholder="Observations">
													</div>
												</div>
												
												<div class="form-group">
													<label for="input-text" class="col-sm-2 control-label">Other Observations </label>
													<div class="col-sm-10">
													<input type="text" class="form-control" id="observations_ot" name="observations_ot" autocomplete="off" placeholder="Other Observations">
													</div>
												</div>
												
												<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Description</label>
												<div class="col-sm-10">
													<textarea class="form-control" id="description" rows="3" name="description" ></textarea>
												</div>
												</div>
											</div>
											</fieldset>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label"></label>
												<div class="col-sm-10">
													<button type="submit" class="btn btn-primary">Update</button>
													 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
													<a href="{{ url('contractbuilding/printcontr/'.$crow->id.'/'.$printz[0]->id) }}" target="_blank" class="btn btn-info">Print Contract</a>
										 <a href="{{ url('contractbuilding/printinvo/'.$crow->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info">Print Invoice</a> 
												</div>
											</div>
											</form>
                                    </div>
									
                                    <div class="tab-pane fade {{(Session::get('active')=='rentallo') ? ' active in' : '' }}" id="rentallo">
                                        <form class="form-horizontal" role="form" method="POST" name="frmRentAllo" action="{{url('contractbuilding/save_rentallo')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" value="{{($crow)?$crow->id:''}}">
										<br/>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Income Account(Cr)</label>
											<div class="col-sm-7">
												<input type="text" class="form-control" name="inc_account" id="inc_account" value="{{($acrow)?$acrow->acname14:''}}" readonly>
												<input type="hidden" name="inc_acid" id="inc_acid" value="{{($acrow)?$acrow->rental_income:''}}">
											</div>
											<div class="col-sm-3">
												<input type="number" class="form-control" name="ra_amount" id="ra_amount" step="any" value="{{($crow)?$crow->rent_amount:''}}" readonly>
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Prepaid Account(Dr)</label>
											<div class="col-sm-7">
												<input type="text" class="form-control" name="preinc_account" id="preinc_account" value="{{($acrow)?$acrow->acname1:''}}" readonly>
												<input type="hidden" name="preinc_acid" id="preinc_acid" value="{{($acrow)?$acrow->prepaid_income:''}}">
												<input type="hidden" id="str_date" value="{{($crow)?$crow->start_date:''}}">
												<input type="hidden" id="ed_date" value="{{($crow)?$crow->end_date:''}}">
												<input type="hidden" id="custname" value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" id="refno" name="reference" value="{{($crow)?$crow->contract_no:''}}">
											</div>
											<div class="col-sm-3">
												<button class="btn btn-primary allocate" type="button">Reallocate</button>
											</div>
										</div>
										<div id="res_allocate">
											@if($jvs)
												@php $i=0; @endphp
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>#</th> <th>JV No.</th> <th>JV Date</th> <th>Amount</th> <th>Description</th>
													</tr>
												</thead>
												<tbody>
												@php $i=1; @endphp
												@foreach($jvs as $row)
													<tr>
														<td>{{$i++}}</td>
														<td>{{$row->voucher_no}}</td>
														<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
														<td>{{number_format($row->amount,2)}}</td>
														<td>{{($crow)?$crow->master_name:''}}/
														{{($crow)?date('d-m-Y',strtotime($crow->start_date)):''}} to 
															{{($crow)?date('d-m-Y',strtotime($crow->end_date)):''}}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										@endif
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												<button type="submit" class="btn btn-primary">Update</button>
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 @if($jvs)
												 <a href="{{ url('contractbuilding/printjv/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
												@endif
											</div>
										</div>
										
										</form>
                                    </div>
									
									<div class="tab-pane fade {{(Session::get('active')=='receipt') ? ' active in' : '' }}" id="receipt">
										<form class="form-horizontal" role="form" method="POST" name="frmReceipt" action="{{url('contractbuilding/save_receipt')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" value="{{($crow)?$crow->id:''}}">
										<input type="hidden" name="cash_ac" id="cash_ac" value="{{($rvrow)?$rvrow->cash:''}}">
										<input type="hidden" name="cash_acid" id="cash_acid" value="{{($rvrow)?$rvrow->cashid:''}}">
										<input type="hidden" name="pd_ac" id="pd_ac" value="{{($rvrow)?$rvrow->pdc:''}}">
										<input type="hidden" name="pd_acid" id="pd_acid" value="{{($rvrow)?$rvrow->pdcid:''}}">
										<input type="hidden" name="bk_ac" id="bk_ac" value="{{($rvrow)?$rvrow->bank:''}}">
										<input type="hidden" name="bk_acid" id="bk_acid" value="{{($rvrow)?$rvrow->bankid:''}}">
										<br/>
										<div class="form-group">
											<label for="input-text" class="col-sm-1 control-label">RV No.</label>
											<div class="col-sm-2">
												<input type="text" class="form-control" name="rv_no" id="rv_no" readonly value="{{($rvs)?$rvs[0]->voucher_no:$rvrow->voucher_no}}" >
											</div>
											<label for="input-text" class="col-sm-2 control-label">RV Date</label>
											<div class="col-sm-2">
												<input type="text" class="form-control" name="rv_date" id="rv_date" value="{{($rvs)?date('d-m-Y',strtotime($rvs[0]->voucher_date)):date('d-m-Y')}}" autocomplete="off" data-language='en' readonly />
											</div>
											<label for="input-text" class="col-sm-1 control-label">Tenant(Cr)</label>
											<div class="col-sm-3">
												<input type="text" class="form-control" name="tenant" id="tenant" readonly value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" name="tenant_id" id="tenant_id" readonly value="{{($crow)?$crow->customer_id:''}}">
												<input type="hidden" name="je_id[]" value="{{($rvs)?$rvs[0]->id:''}}">
											</div>											
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-1 control-label">Amount</label>
											<div class="col-sm-2">
												<input type="number" class="form-control" id="rv_amount" step="any" name="rv_amount" placeholder="Amount" value="{{($rvs)?(($rvs[0]->amount==$crow->rent_amount)?$rvs[0]->amount:$crow->rent_amount):$crow->rent_amount}}">
											</div>
											<label for="input-text" class="col-sm-2 control-label">Installment</label>
											<div class="col-sm-2">
												<input type="number" class="form-control" id="installment" step="any" value="{{($rvs)?$rvs[0]->installment:''}}" name="installment">
											</div>
											<label for="input-text" class="col-sm-1 control-label"></label>
											<div class="col-sm-3"><button type="button" class="btn btn-primary rvadd">Add</button></div>
										</div>
										
										<fieldset>
											<legend><h5>Transactions (Dr)</h5></legend>
											<div class="col-xs-15" id="receipt_add">
												@if($rvs) @php $i=1; @endphp
												@foreach($rvs as $row)
												@if($row->entry_type=='Dr')
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Debit A/c.1</span> <input type="text" id="dracname_{{$i}}" value="{{$row->master_name}}" autocomplete="off" name="drac_name[]" class="form-control">
													<input type="hidden" id="dracid_{{$i}}" name="drac_id[]" value="{{$row->account_id}}">
													<input type="hidden" name="je_id[]" value="{{$row->id}}">
												</div>
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Ref.No.</span> 
													<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$row->reference}}" autocomplete="off" class="form-control">
												</div>
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Description</span> 
													<input type="text" id="desc_{{$i}}" name="description[]" value="{{$row->description}}" autocomplete="off" class="form-control">
												</div>

												<div class="col-xs-1" style="width:7%;">
													<span class="small">P.Mode</span> 
													<select id="trtype_{{$i}}" class="form-control trtype" style="width:100%" name="tr_type[]">
														<option value="C" {{($row->currency_id==0)?'selected':''}}>C</option>
														<option value="B" {{($row->currency_id==1)?'selected':''}}>B</option>
														<option value="P" {{($row->currency_id==2)?'selected':''}}>P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$row->amount}}" autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>
												
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Bank</span> 
													<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
														<option value="">Bank</option>
														@foreach($banks as $bank)
														<option value="{{$bank->id}}" {{($bank->id==$row->bank_id)?'selected':''}}>{{$bank->code}}</option>
														@endforeach
													</select>
												</div>
												
												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_{{$i}}" value="{{$row->cheque_no}}" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_{{$i}}" value="{{($row->cheque_no!='')?date('d-m-Y',strtotime($row->cheque_date)):''}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
												</div>

												<div class="col-xs-3" style="width:13%;">
													<span class="small">Remarks</span> <input type="text" id="rmrk_{{$i}}" name="remarks[]" value="" class="form-control">
												</div>
												@php $i++; @endphp
												@endif
												@endforeach
												@endif
											</div>	
										</fieldset><br/>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												<button type="submit" class="btn btn-primary">Update</button>
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 @if($rvs)
												 <a href="{{ url('contractbuilding/printrv/RV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
												@endif
											</div>
										</div>
										</form>
									</div>
									
									<div class="tab-pane fade {{(Session::get('active')=='deposit') ? ' active in' : '' }}" id="deposit">
										<form class="form-horizontal" role="form" method="POST" name="frmDeposit" action="{{url('contractbuilding/save_deposit')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" value="{{($crow)?$crow->id:''}}">
										<input type="hidden" name="cash_ac" id="cash_ac" value="{{($rvrow)?$rvrow->cash:''}}">
										<input type="hidden" name="cash_acid" id="cash_acid" value="{{($rvrow)?$rvrow->cashid:''}}">
										<input type="hidden" name="pd_ac" id="pd_ac" value="{{($rvrow)?$rvrow->pdc:''}}">
										<input type="hidden" name="pd_acid" id="pd_acid" value="{{($rvrow)?$rvrow->pdcid:''}}">
										<input type="hidden" name="bk_ac" id="bk_ac" value="{{($rvrow)?$rvrow->bank:''}}">
										<input type="hidden" name="bk_acid" id="bk_acid" value="{{($rvrow)?$rvrow->bankid:''}}">
										<br/>
										<div class="form-group">
											<label for="input-text" class="col-sm-1 control-label">RV No.</label>
											<div class="col-sm-1">
												<input type="text" class="form-control" name="rv_no" id="drv_no" readonly value="{{($drvs)?$drvs[0]->voucher_no:$rvrow->voucher_no}}">
											</div>
											<label for="input-text" class="col-sm-1 control-label">RV Date</label>
											<div class="col-sm-2" style="width:12%;">
												<input type="text" class="form-control" name="drv_date" id="drv_date" value="{{($drvs)?date('d-m-Y',strtotime($drvs[0]->voucher_date)):date('d-m-Y')}}" autocomplete="off" data-language='en' readonly />
											</div>
											<label for="input-text" class="col-sm-1 control-label">Amount</label>
											<div class="col-sm-2" style="width:12%;">@php $pyamt = (isset($payacnts[1]->amount))?$payacnts[1]->amount:0; @endphp
												<input type="number" class="form-control" step="any" id="drv_amount" name="rv_amount" value="{{($drvs)?(($drvs[0]->amount==$pyamt)?$drvs[0]->amount:$pyamt):$pyamt}}" placeholder="Amount">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Tenant(Cr)</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" name="tenant" readonly value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" name="tenant_id" readonly value="{{($crow)?$crow->customer_id:''}}">
												<input type="hidden" name="je_id[]" value="{{($drvs)?$drvs[0]->id:''}}">
											</div>	
										</div>
										
										<fieldset>
											<legend><h5>Transaction(Dr)</h5></legend>
											@if(!$drvs)
											<div class="col-xs-15">
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Debit A/c.</span> <input type="text" id="ddracname_1" value="{{($rvrow)?$rvrow->cash:''}}" autocomplete="off" name="drac_name[]" class="form-control">
													<input type="hidden" id="ddracid_1" name="drac_id[]" value="{{($rvrow)?$rvrow->cashid:''}}" >
												</div>
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Ref.No.</span> 
													<input type="text" id="dref_1" name="reference[]" value="{{($crow)?$crow->contract_no:''}}" autocomplete="off" class="form-control">
												</div>
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Description</span> 
													<input type="text" id="ddesc_1" name="description[]" value="{{($crow)?('Deposit/'.$crow->contract_no.'/'.$crow->master_name):''}}" autocomplete="off" class="form-control">
												</div>

												<div class="col-xs-1" style="width:7%;">
													<span class="small">P.Mode</span> 
													<select id="dtrtype_1" class="form-control dtrtype" style="width:100%" name="tr_type[]">
														<option value="C">C</option>
														<option value="B">B</option>
														<option value="P">P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="damount_1" value="{{$pyamt}}" placeholder="Amount" autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>
												
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Bank</span> 
													<select id="dbankid_1" class="form-control select2" style="width:100%" name="bank_id[]">
														<option value="">Bank</option>
														@foreach($banks as $bank)
														<option value="{{$bank->id}}">{{$bank->name}}</option>
														@endforeach
													</select>
												</div>
												
												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" id="dchkno_1" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="dchkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
												</div>
												
												<div class="col-xs-3" style="width:13%;">
													<span class="small">Remarks</span> <input type="text" id="drmrk_1" name="remarks[]" value="Deposit Amount" class="form-control">
												</div>
											</div>
											@else
											@foreach($drvs as $drow)
											@if($drow->entry_type=='Dr')
											<div class="col-xs-15">
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Debit A/c.</span> <input type="text" id="ddracname_1" value="{{$drow->master_name}}" autocomplete="off" name="drac_name[]" class="form-control">
													<input type="hidden" id="ddracid_1" name="drac_id[]" value="{{$drow->account_id}}">
													<input type="hidden" name="je_id[]" value="{{$drow->id}}">
												</div>
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Ref.No.</span> 
													<input type="text" id="dref_1" name="reference[]" value="{{$drow->reference}}" autocomplete="off" class="form-control">
												</div>
												<div class="col-xs-3" style="width:15%;">
													<span class="small">Description</span> 
													<input type="text" id="ddesc_1" name="description[]" value="{{$drow->description}}" autocomplete="off" class="form-control">
												</div>

												<div class="col-xs-1" style="width:7%;">
													<span class="small">P.Mode</span> 
													<select id="dtrtype_1" class="form-control dtrtype" style="width:100%" name="tr_type[]">
														<option value="C" {{($drow->voucher_type=='CASH' || $drow->voucher_type=='9')?'selected':''}}>C</option>
														<option value="B" {{($drow->voucher_type=='BANK')?'selected':''}}>B</option>
														<option value="P" {{($drow->voucher_type=='PDCR')?'selected':''}}>P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="damount_1" value="{{$drow->amount}}" placeholder="Amount" autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>
												
												<div class="col-xs-3" style="width:10%;">
													<span class="small">Bank</span> 
													<select id="dbankid_1" class="form-control select2" style="width:100%" name="bank_id[]">
														<option value="">Bank</option>
														@foreach($banks as $bank)
														<option value="{{$bank->id}}" {{($bank->id==$drow->bank_id)?'selected':''}}>{{$bank->code}}</option>
														@endforeach
													</select>
												</div>


												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" value="{{$drow->cheque_no}}" id="dchkno_1" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" value="{{($drow->cheque_date!='0000-00-00')?date('d-m-Y',strtotime($drow->cheque_date)):''}}" id="dchkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
												</div>
												
												<div class="col-xs-3" style="width:13%;">
													<span class="small">Remarks</span> <input type="text" id="drmrk_1" name="remarks[]" value="Deposit Amount" class="form-control">
												</div>
											</div>	
											@endif
											@endforeach
											@endif
										</fieldset><br/>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
											<!-- NOV27 -->
											@if($crow->renew_id=='')
												@if($crow)
												<button type="submit" class="btn btn-primary">Update</button>
												@endif
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 @if($drvs)
												 <a href="{{ url('contractbuilding/printrv/DRV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
												@endif
											@else
												@if($crow->renew_id!='' && isset($payacnts[1]) && $payacnts[1]->is_add==1)
													@if($crow)
													<button type="submit" class="btn btn-primary">Update</button>
													@endif
													<a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
													 @if($drvs)
													 <a href="{{ url('contractbuilding/printrv/DRV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
													@endif
												@endif
											@endif
										
											</div>
										</div>
										</form>
									</div>
									
									<div class="tab-pane fade {{(Session::get('active')=='otherrv') ? ' active in' : '' }}" id="otherrv">
										<div class="controls"> 
										<form class="form-horizontal" role="form" method="POST" name="frmDeposit" action="{{url('contractbuilding/save_otherrv')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" id="conid" value="{{($crow)?$crow->id:''}}">
										<input type="hidden" name="cash_ac" id="cash_ac" value="{{($rvrow)?$rvrow->cash:''}}">
										<input type="hidden" name="cash_acid" id="cash_acid" value="{{($rvrow)?$rvrow->cashid:''}}">
										<input type="hidden" name="pd_ac" id="pd_ac" value="{{($rvrow)?$rvrow->pdc:''}}">
										<input type="hidden" name="pd_acid" id="pd_acid" value="{{($rvrow)?$rvrow->pdcid:''}}">
										<input type="hidden" name="bk_ac" id="bk_ac" value="{{($rvrow)?$rvrow->bank:''}}">
										<input type="hidden" name="bk_acid" id="bk_acid" value="{{($rvrow)?$rvrow->bankid:''}}">
										<input type="hidden" name="rv_id" id="rv_id" value="{{($orvs)?$orvs[0]->rv_id:''}}">
										<input type="hidden" id="rnum" value="{{count($orvs)}}">
										<input type="hidden" name="type" value="edit">
										<br/>
										<div class="form-group">
											
											<label for="input-text" class="col-sm-2 control-label">RV No.</label>
											<div class="col-sm-2"> <!-- NOV24 -->
												<input type="hidden" name="rv_no" id="orv_no" value="{{($orvs)?$orvs[0]->voucher_no:$rvrow->voucher_no}}" >
												<select class="form-control" name="rvno" id="orvno" />
												@foreach($prvs as $rv)
												<option value="{{$rv->rv_id}}" {{($orvs)?( ($orvs[0]->rv_id==$rv->rv_id)?'selected': '' ) : ''}}>{{$rv->voucher_no}}</option>
												@endforeach
												</select>
											</div>
											<label for="input-text" class="col-sm-2 control-label">RV Date</label>
											<div class="col-sm-3" style="width:12%;">
												<input type="text" class="form-control" name="orv_date" id="orv_date" value="{{($orvs)?date('d-m-Y',strtotime($orvs[0]->voucher_date)):date('d-m-Y')}}" autocomplete="off" data-language='en' readonly />
											</div>
											
											<div class="col-sm-2" style="width:12%;">
												<input type="hidden" id="orv_amount" name="rv_amount" value="{{($orvs)?$orvs[0]->debit:0}}" >
												<a href="{{ url('contractbuilding/add/'.$crow->id) }}#otherrv"  class="btn btn-info">Add New RV</a>
											</div>
											
											<label for="input-text" class="col-sm-1 control-label"></label>
											<div class="col-sm-4" style="width:12%;">
											<h5 class="ORVD"><a href="">View Balance </a></h5>
											</div>
											
										</div>
										
										<br/>
										
										<div class="form-group" id="orvdetils">
											<div class="col-sm-8">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>#</th><th>Account Name</th> <th>Amount</th>
													</tr>
												</thead>
												<tbody>@php $i=$orvtotal=0; @endphp
												@foreach($payacnts as $k => $row)
													@if($k>2 && $row->amount > 0 && !(in_array($row->account_id,$orv)) )
														@php $i++; $orvtotal += $row->amount; @endphp
													<tr>
														<td>{{$i}} </td>
														<td>{{$oracarr[$k].' - '.$row->acname1}} </td>
														<td>{{number_format($row->amount,2)}}<input type="hidden" value="{{round($row->amount,2)}}"></td>
													</tr>
													@endif
												@endforeach
												@foreach($payacnts as $k => $row)
													@if($row->tax_amount > 0 && !(in_array($row->account_id,$txrv)))
														@php $i++; $orvtotal += $row->tax_amount; @endphp
													<tr>
														<td>{{$i}} </td>
														<td>{{$oractxarr[$k].' - '.$row->acname1}} </td>
														<td>{{number_format($row->tax_amount,2)}}<input type="hidden" value="{{round($row->amount,2)}}"></td>
													</tr>
													@endif
												@endforeach
												<tr><td colspan="2" align="right"><b>Total</b></td><td><b>{{number_format($orvtotal,2)}}</b></td></tr>
												</tbody>
											</table>
											</div>
										</div>
										
										<!-- NOV24 -->
										<div id="orvdetails">
											<fieldset>
												<legend><h5>Transaction(Dr)</h5></legend>
												<div class="itemdivPrntDr">
												@php $i=1; $drjeid=''; @endphp
												@foreach($orvs as $orow)
													@if($orow->entry_type=='Dr')
														@php $drjeid=$orow->id; @endphp <!-- NOV24 -->
												  <div class="itemdivChldDr">
													<div class="col-xs-15">
														<div class="col-xs-3" style="width:15%;">
															<span class="small">Debit A/c.</span> <input type="text" id="rvOdracname_{{$i}}" value="{{($rvrow)?$rvrow->cash:$orow->master_name}}" autocomplete="off" name="drac_name[]" class="form-control">
															<input type="hidden" id="rvOdracid_{{$i}}" name="drac_id[]" value="{{($rvrow)?$rvrow->cashid:$orow->account_id}}" >
														</div>
														<div class="col-xs-3" style="width:10%;">
															<span class="small">Ref.No.</span> 
															<input type="text" id="dref_{{$i}}" name="Dreference[]" value="{{($crow)?$crow->contract_no:$orow->reference}}" autocomplete="off" class="form-control">
														</div>
														<div class="col-xs-3" style="width:15%;">
															<span class="small">Description</span> 
															<input type="text" id="ddesc_{{$i}}" name="Ddescription[]" value="{{($crow)?('Deposit/'.$crow->contract_no.'/'.$crow->master_name):$orow->description}}" autocomplete="off" class="form-control">
														</div>

														<div class="col-xs-1" style="width:7%;">
															<span class="small">P.Mode</span> 
															<select id="drtrtype_{{$i}}" class="form-control drtrtype" style="width:100%" name="tr_type[]">
																<option value="C" {{($orow->currency_id==0)?'selected':''}}>C</option>
																<option value="B" {{($orow->currency_id==1)?'selected':''}}>B</option>
																<option value="P" {{($orow->currency_id==2)?'selected':''}}>P</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="drdamount_{{$i}}" placeholder="Amount" value="{{$orow->amount}}" autocomplete="off" step="any" name="Damount[]" class="form-control">
														</div>
														
														<div class="col-xs-3" style="width:10%;">
															<span class="small">Bank</span> 
															<select id="dbankid_{{$i}}" class="form-control dr-bank" style="width:100%" name="bank_id[]"><!-- NOV27-->
																<option value="">Bank</option>
																@foreach($banks as $bank)
																<option value="{{$bank->id}}" {{($orow->bank_id==$bank->id)?"selected":""}}>{{$bank->code}}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-sm-2" style="width:10%;"> 
															<span class="small">Cheque No</span><input type="text" autocomplete="off" id="dchkno_{{$i}}" value="{{$orow->cheque_no}}" name="cheque_no[]" class="form-control" >
														</div>

														<div class="col-xs-3" style="width:10%;">
															<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="dchkdate_{{$i}}" value="{{($orow->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($orow->cheque_date))}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>

														

														<div class="col-xs-3" style="width:10%;">
															<span class="small">Remarks</span> <input type="text" id="drmrk_{{$i}}" name="Dremarks[]" class="form-control">
														</div>
													</div>
												  </div>
												  @php $i++; @endphp
												  @endif
												  @endforeach
												</div>
											</fieldset>	
											<br/><br/>
											
											<fieldset>
												<legend><h5>Transactions(Cr)</h5></legend>
												
												<div class="col-xs-15 Crcontrols">
													<div class="itemdivPrnt">
													@php $i=1; @endphp <!-- NOV24 -->
													@foreach($orvs as $orow)
														@if($orow->entry_type=='Cr')
														<div class="itemdivChld">							
															<div class="form-group" style="margin-bottom: 1px;">
																<div class="col-sm-2" style="width:25%;"> <span class="small">Tenant A/c</span>
																<input type="text" id="craccount_{{$i}}" name="account_name[]" class="form-control acname" value="{{($crow)?$crow->master_name:$orow->master_name}}" autocomplete="off" data-toggle="modal" data-target="#account_modal">
																<input type="hidden" name="account_id[]" id="craccountid_{{$i}}" value="{{($crow)?$crow->customer_id:$orow->account_id}}">
																<input type="hidden" name="group_id[]" id="groupid_{{$i}}" value="CUSTOMER">
																<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}">
																<input type="hidden" id="invoiceid_{{$i}}" name="sales_invoice_id[]">
																<input type="hidden" name="bill_type[]" id="biltyp_{{$i}}">
																<input type="hidden" name="expacid[]" id="eacid_{{$i}}">
																<input type="hidden" name="isfc[]" id="isfc_{{$i}}">
																<input type="hidden" name="je_id[]" id="cjeid_{{$i}}" value="{{$orow->id}}"> <!-- NOV24 -->
																</div>
																<div class="col-xs-15 divchq">
																	<div class="col-xs-2" style="width:25%;">
																		<span class="small">Description/Reference</span> <input type="text" id="crdescr_{{$i}}" value="{{$orow->description}}" autocomplete="off" name="description[]" class="form-control desc-bill" data-toggle="modal" data-target="#reference_modal">
																	</div>
																	<div class="col-xs-2" style="width:25%;">
																		<span class="small">Contract No</span> 
																		<div id="refdata_{{$i}}" class="refdata">
																		<input type="text" id="crref_{{$i}}" name="reference[]" value="{{$orow->reference}}" autocomplete="off" class="form-control">
																		</div>
																		<input type="hidden" name="inv_id[]" id="invid_{{$i}}">
																		<input type="hidden" name="actual_amount[]" id="actamt_1">
																	</div>
																	
																	<div class="col-xs-2" style="width:20%;">
																		<span class="small">Amount</span> <input type="number" id="ramount_{{$i}}" value="{{$orow->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control">
																	</div>
																</div>	
															</div>
														</div>
														@php $i++; @endphp
														@endif
													@endforeach
													<input type="hidden" value="{{$i-1}}" id="orvNo"> <!-- NOV24 -->
													</div>
													<input type="hidden" name="je_id[]" value="{{$drjeid}}"> <!-- NOV24 -->
												</div>
											</fieldset>
										</div> <!-- NOV24 -->
										<br/>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												<button type="submit" class="btn btn-primary">Update</button>
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 
												 @if($prvs)
												<div class="btn-group drop_btn" role="group">
													<button type="button" class="btn btn-primary dropdown-toggle m-r-50" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
														Print
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
														@foreach($prvs as $rv)
														<li role='presentation'><a href="{{ url('contractbuilding/printrv/ORV/'.$rv->id) }}" target="_blank" role="menuitem">RV No. {{$rv->voucher_no}}</a></li>
														@endforeach
													</ul>
												</div>
												@endif
											</div>
										</div>
										</form>
										</div>
									</div>
									
									
									<div class="tab-pane fade {{(Session::get('active')=='payment') ? ' active in' : '' }}" id="payment">
										<div class="controls"> 
										<form class="form-horizontal" role="form" method="POST" name="frmPayment" action="{{url('contractbuilding/save_payment')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" id="conid" value="{{($crow)?$crow->id:''}}">
										<input type="hidden" name="pv_id" id="pv_id" value="{{($pvs)?$pvs[0]->pv_id:''}}">
										<input type="hidden" id="rnum" value="{{count($pvs)}}">
										<input type="hidden" name="type" value="edit">
										<br/>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">PV No.</label>
											<div class="col-sm-2">
												<input type="text" class="form-control" name="pv_no" id="pv_no" readonly value="{{($pvs)?$pvs[0]->voucher_no:$pvrow->voucher_no}}">
											</div>
											<label for="input-text" class="col-sm-2 control-label">PV Date</label>
											<div class="col-sm-3" style="width:12%;">
												<input type="text" class="form-control" name="pv_date" id="pv_date" value="{{($pvs)?date('d-m-Y',strtotime($pvs[0]->voucher_date)):date('d-m-Y')}}" autocomplete="off" data-language='en' readonly />
											</div>
											
											<label for="input-text" class="col-sm-1 control-label"></label>
											<div class="col-sm-4" style="width:12%;">
											
											</div>
										</div>
										
										
										<fieldset>
											<legend><h5>Transactions</h5></legend>
											<div class="itemdivPrntPv">
											@php $i = 0; @endphp
											@foreach($pvs as $pv)
											@php $i++; @endphp
											  <div class="itemdivChldPv">							
												<div class="form-group" style="margin-bottom: 1px;">
													<div class="col-xs-2" style="width:15%;">
													<span class="small">Account Name</span>
													<input type="text" id="PVdraccount_{{$i}}" name="PVaccount_name[]" value="{{$pv->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#PVaccount_modal">
													<input type="hidden" name="account_id[]" id="PVdraccountid_{{$i}}" value="{{$pv->account_id}}">
													<input type="hidden" name="group_id[]" id="PVgroupid_{{$i}}">
													<input type="hidden" name="vatamt[]" id="PVvatamt_{{$i}}">
													<input type="hidden" id="PVinvoiceid_{{$i}}" name="sales_invoice_id[]">
													<input type="hidden" name="bill_type[]" id="PVbiltyp_{{$i}}">
													<input type="hidden" name="je_id[]" id="cjeid_{{$i}}" value="{{$pv->id}}">
													</div>
													<div class="col-xs-15">
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Description</span> <input type="text" id="PVdescr_{{$i}}" value="{{$pv->description}}" autocomplete="off" name="description[]" class="form-control">
														</div>
														<div class="col-xs-3" style="width:10%;">
															<span class="small">Reference</span> 
															<div id="refdata_1" class="refdata">
															<input type="text" id="PVref_{{$i}}" name="reference[]" autocomplete="off" value="{{$pv->reference}}" class="form-control">
															</div>
															<input type="hidden" name="inv_id[]" id="PVinvid_{{$i}}">
															<input type="hidden" name="actual_amount[]" id="PVactamt_{{$i}}" value="{{$pv->amount}}">
														</div>
														<div class="col-xs-1" style="width:7%;">
															<span class="small">Type</span> 
															<select id="PVacnttype_{{$i}}" class="form-control select2 PVline-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr" {{($pv->entry_type=='Dr')? "selected" : "" }}>Dr</option>
																<option value="Cr" {{($pv->entry_type=='Cr')? "selected" : "" }}>Cr</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Amount</span> <input type="number" id="PVamount_{{$i}}" value="{{$pv->amount}}" autocomplete="off" step="any" name="line_amount[]" class="form-control PVline-amount">
														</div>
												
														<input type="hidden" name="department[]" id="PVdept_{{$i}}">
														<div id="chqdtl_1" class="divchq">
															<div class="col-xs-2" style="width:9%;">
																<span class="small">Bank</span> 
																<select id="PVbankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																	<option value="">Bank</option>
																	@foreach($banks as $bank)
																	@if($pv->entry_type=='Cr')
																	<option value="{{$bank->id}}" {{($pv->bank_id==$bank->id)? "selected" : "" }}>{{$bank->code}}</option>
																	@else
																	<option value="{{$bank->id}}">{{$bank->code}}</option>
																	@endif
																	@endforeach
																</select>
															</div>
															
															<div class="col-sm-2" style="width:8%;"> 
																<span class="small">Cheque No</span><input type="text" autocomplete="off" id="PVchkno_{{$i}}" name="cheque_no[]" class="form-control" {{($pv->entry_type=='Cr')? $pv->cheque_no : ""}}>
															</div>
															
															<div class="col-xs-2" style="width:10%;">
																<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="PVchkdate_{{$i}}" name="cheque_date[]" {{($pv->entry_type=='Cr')? (($pv->cheque_date!='0000-00-00') ? date('d-m-Y', strtotime($pv->cheque_date)) : "" ): "" }} class="form-control chqdate" data-language='en'>
															</div>
															
															<div class="col-xs-2" style="width:10%;">
																<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}">
																<span class="small">Party Name</span> <input type="text" id="PVparty_{{$i}}" name="party_name[]" class="form-control" data-toggle="modal" data-target="#paccount_modal">
															</div>
														</div>
														
														<div class="col-xs-1 abc" style="width:5%;">
															<button type="button" class="btn-success btn-add-item-pv" >
																<i class="fa fa-fw fa-plus-square"></i>
															 </button><br/>
															<button type="button" class="btn-danger btn-remove-item-pv" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
															 
														</div>
													</div>	
												</div>
											</div>
											@endforeach
											</div>
										</fieldset>	
										<br/><br>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Total Debit</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="debit" name="debit" readonly value="{{($pvs)?$pvs[0]->debit:''}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Total Credit</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="credit" name="credit" readonly value="{{($pvs)?$pvs[0]->debit:''}}">
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"> Difference</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
											</div>
										</div>
																				
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												
												<button type="submit" class="btn btn-primary">Submit</button>
												
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 
												@if($ppvs)
												<div class="btn-group drop_btn" role="group">
													<button type="button" class="btn btn-primary dropdown-toggle m-r-50" id="exampleIconDropdown1" data-toggle="dropdown" aria-expanded="false">
														Print
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu" aria-labelledby="exampleIconDropdown1" role="menu">
														@foreach($ppvs as $pv)
														<li role='presentation'><a href="{{ url('contractbuilding/printpv/'.$pv->id) }}" target="_blank" role="menuitem">PV No. {{$pv->voucher_no}}</a></li>
														@endforeach
													</ul>
												</div>
												@endif
												
											</div>
										</div>
										</form>
										</div>
									</div>
									
                                </div>
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
			
			<div id="reference_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog" style="width:60%;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Other RV</h4>
						</div>
						<div class="modal-body" id="invoiceData">
							
						</div>
					</div>
				</div>
			</div> 
			
			<div id="PVaccount_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="PVaccount_data">
						</div>
					</div>
				</div>
			</div>
			
			<div id="ac_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Account</h4>
						</div>
						<div class="modal-body" id="ac_data">
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script>
"use strict";
$('#tenantDtls').hide(); $('#owndetils').hide(); $('#orvdetils').hide();
$('#passport_exp').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});


$('#start_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#rv_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#drv_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy'} );
$('#orv_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#con_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#end_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );

@if($crow->is_day==0)
	$('#durD').hide();
@else
	$('#durM').hide();
@endif

var oldamt = parseFloat($("#orv_amount").val());

$('.itemdivPrntPv').find('.btn-add-item-pv:not(:last)').hide();
if ( $('.itemdivPrntPv').children().length == 1 ) {
	$('.itemdivPrntPv').find('.btn-remove-item-pv').hide();
}

$(document).ready(function () {
	var urltype = "{{ url('contra_type/check_type/') }}";
     $('#frmContract').bootstrapValidator({
         fields: {
 			buildingid: {
                 validators: {
                     notEmpty: {
                         message: 'Building is required and cannot be empty!'
                     }
                 }
             },
 			customer_account: {
                 validators: {
                     notEmpty: {
                         message: 'Tenant is required and cannot be empty!'
                     }
                 }
             },
			 flat_id: {
                 validators: {
                     notEmpty: {
                         message: 'Flat no is required and cannot be empty!'
                     }
                 }
             },
			 start_date: {
                 validators: {
                     notEmpty: {
                         message: 'Flat no is required and cannot be empty!'
                     }
                 }
             },
			 duration: {
                 validators: {
                     notEmpty: {
                         message: 'Duration is required and cannot be empty!'
                     }
                 }
             },
			 rent_amount: {
                 validators: {
                     notEmpty: {
                         message: 'Rent amount is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmContract').data('bootstrapValidator').resetForm();
     });
});


$(document).on('click', '.ORVD', function(e) { e.preventDefault();
	$('#orvdetils').toggle();
});

$(document).on('click', '.DT', function(e) { e.preventDefault();
	$('#tenantDtls').toggle();
});

$(document).on('click', '.DT2', function(e) { e.preventDefault();
	$('#owndetils').toggle();
});

$(document).on('change', '#duration', function(e) {
		
	var sdate = $('#start_date').val();
	$.get("{{ url('contractbuilding/get_enddate/') }}/"+sdate+"/"+this.value, function(data) {
		var det = $.parseJSON(data); console.log('dd '+det);
		$('#end_date').val(det);
	});
	
});
	
$(document).on('keyup', '#rent_amount', function(e) {
	if( $('#istx_1').val()==1 ) { console.log('gg');
		let txamt = parseFloat(($('#rent_amount').val() * 5)/100);
		$('#actax_1').val(txamt);
	}
	$('#amt').val( $('#rent_amount').val() ); $('#acamt_1').val( $('#rent_amount').val()); //$('#gtotal').val( $('#rent_amount').val());
	grandTotal();
});

$(document).on('change', '#building_id', function(e) {
	var bid = $(this).val();
	$.get("{{ url('contra_type/get_details/') }}/" + bid, function(data) {
		var det = $.parseJSON(data); 
		 $('#acname_1').val(det.acname1); $('#preinc_account').val(det.acname1);
		 $('#acname_2').val(det.acname2); $('#inc_account').val(det.acname14);
		 $('#acname_3').val(det.acname3);
		 $('#acname_4').val(det.acname4);
		 $('#acname_5').val(det.acname5);
		 $('#acname_6').val(det.acname6);
		 $('#acname_7').val(det.acname7);
		 
		 $('#acid_1').val(det.prepaid_income); $('#preinc_acid').val(det.prepaid_income); 
		 $('#acid_2').val(det.deposit);		$('#inc_acid').val(det.rental_income);
		 $('#acid_3').val(det.water_ecty);
		 $('#acid_4').val(det.commission);
		 $('#acid_5').val(det.other_deposit);
		 $('#acid_6').val(det.parking);
		 $('#acid_7').val(det.ejarie_fee);
		 
		 $('#istx_1').val(det.pi_tax);
		 $('#istx_2').val(det.d_tax);
		 $('#istx_3').val(det.we_tax);
		 $('#istx_4').val(det.c_tax);
		 $('#istx_5').val(det.od_tax);
		 $('#istx_6').val(det.p_tax);
		 $('#istx_7').val(det.ef_tax);
		 
		 $('#contract_no').val(det.type+det.increment_no);
		  
		 $('#owner_name').val(det.ownername);
		 $('#location').val(det.location);
		 $('#contact_no').val(det.mobno);
		 $('#plot_no').val(det.plot_no);
		
	});
	
	$.get("{{ url('contra_type/get_flat/') }}/" + bid, function(data) {
		var dat = $.parseJSON(data); 
		$('#flat_id').find('option').remove().end();
		$.each(dat, function(key, value) {   
			$('#flat_id').find('option').end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.flat_no)); 
		});
	});
	
});

$(document).on('keyup', '.txtAmt', function(e) {
	var res = this.id.split('_');
	var no = res[1]; 
	let amt = this.value;
	if( $('#istx_'+no).val()==1 ) {
		let txamt = (amt * 5)/100;
		$('#actax_'+no).val(txamt);
	}
	//console.log('tc '+txamt);
	grandTotal();
});

$(document).on('keyup', '.txtPer', function(e) {
	grandTotal();
});

$('#customer_account').click(function() {
	let custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customerData').load(custurl, function(result) {
		$('#myModal').modal({show:true});
		$('.input-sm').focus()
	});
});

$(document).on('click', '.custRow', function(e) {
	$('#customer_account').val($(this).attr("data-name"));
	$('#customer_id').val($(this).attr("data-id"));
	e.preventDefault();
});

$(document).on('click', '.allocate', function(e) { //NOV22
	let sdate = $('#str_date').val();
	let amt = $('#ra_amount').val();
	let cname = $('#custname').val();
	let edate = $('#ed_date').val();
	let cid = $('#conid').val();
	let dur;
	if($('#is_day').val()==0)
		dur = $('#duration option:selected').val();
	else
		dur = $('#durationD').val();
	$.ajax({
		url: "{{ url('contractbuilding/rent_reallocate/') }}", 
		type: 'get',
		data: 'cid='+cid+'&date='+sdate+'&amount='+amt+'&cname='+cname+'&edate='+edate+'&duration='+dur+'&isday='+$('#is_day').val(),
		success: function(data) { 
			$('#res_allocate').html(data);
			return true;
		}
	});
});

function grandTotal() {
	let amount=0; let total=0; let txtotal=0;
	$( '.txtAmt' ).each(function() {
		  var res = this.id.split('_');
		  var no = res[1];
		  let tax = ($('#actax_'+no).val()=='')?0:parseFloat($('#actax_'+no).val());
		  let amt = (this.value=='')?0:parseFloat(this.value);
		  total = total + amt;
		  txtotal = txtotal + tax;
		  amount = amount + amt + tax;
	});
	$('#total').val(total.toFixed(2));
	$('#txtotal').val(txtotal.toFixed(2));
	$('#gtotal').val(amount.toFixed(2));
}
 
$(document).on('click', '.rvadd', function(e) { //NOV22
	let amt = $('#rv_amount').val();
	let inst = $('#installment').val();
	let cac = $('#cash_ac').val();
	let cacid = $('#cash_acid').val();
	let ref = $('#refno').val();
	let dec = $('#tenant').val();
	let cid = $('#conid').val();
	$.ajax({
		url: "{{ url('contractbuilding/receipt_readd/') }}",
		type: 'get',
		data: 'cid='+cid+'&amount='+amt+'&inst='+inst+'&cac='+cac+'&cacid='+cacid+'&ref='+ref+'&dec='+dec,
		success: function(data) { 
			$('#receipt_add').html(data);
			return true;
		}
	});
});

$('.chqdate').datepicker({ autoClose: true, language: 'en', dateFormat: 'dd-mm-yyyy' });
			
$(document).on('keyup','#drv_amount', function(e) {
	$('#damount_1').val(this.value);
});

$(document).on('change','.dtrtype', function(e) {
	var no = this.id.split('_');  
	if(this.value=='C'){
		$('#ddracname_'+no[1]).val( $('#cash_ac').val() );
		$('#ddracid_'+no[1]).val( $('#cash_acid').val() );
		
		$('#dbankid_'+no[1]).attr('required', false); 
		$('#dchkno_'+no[1]).attr('required', false); 
		$('#dchkdate_'+no[1]).attr('required', false);
		
	} else if(this.value=='P'){
		$('#ddracname_'+no[1]).val( $('#pd_ac').val() );
		$('#ddracid_'+no[1]).val( $('#pd_acid').val() );
		
		$('#dbankid_'+no[1]).attr('required', true); 
		$('#dchkno_'+no[1]).attr('required', true); 
		$('#dchkdate_'+no[1]).attr('required', true);
		
	} else if(this.value=='B'){
		$('#ddracname_'+no[1]).val( $('#bk_ac').val() );
		$('#ddracid_'+no[1]).val( $('#bk_acid').val() );
		
		$('#dbankid_'+no[1]).attr('required', false); 
		$('#dchkno_'+no[1]).attr('required', false); 
		$('#dchkdate_'+no[1]).attr('required', false);
	}
})

$(document).on('change','.drtrtype', function(e) {
	var no = this.id.split('_'); 

	if(this.value=='C'){
		$('#rvOdracname_'+no[1]).val( $('#cash_ac').val() );
		$('#rvOdracid_'+no[1]).val( $('#cash_acid').val() );
		
		$('#obankid_'+no[1]).attr('required', false); 
		$('#ochkno_'+no[1]).attr('required', false); 
		$('#ochkdate_'+no[1]).attr('required', false);
		
	} else if(this.value=='P'){
		$('#rvOdracname_'+no[1]).val( $('#pd_ac').val() );
		$('#rvOdracid_'+no[1]).val( $('#pd_acid').val() );
		
		$('#obankid_'+no[1]).attr('required', true); 
		$('#ochkno_'+no[1]).attr('required', true); 
		$('#ochkdate_'+no[1]).attr('required', true);
		
	} else if(this.value=='B'){
		$('#rvOdracname_'+no[1]).val( $('#bk_ac').val() );
		$('#rvOdracid_'+no[1]).val( $('#bk_acid').val() );
		
		$('#obankid_'+no[1]).attr('required', false); 
		$('#ochkno_'+no[1]).attr('required', false); 
		$('#ochkdate_'+no[1]).attr('required', false);
	}
})
	
$(function() {	
	 var rowNum = 1;//$('#rnum').val()-1; 
  $('.btn-remove-item-dr').hide(); 
  $(document).on('click', '.btn-add-item-dr', function(e)  { 
  
        var group_id = $('#groupid_'+rowNum).val();
		var refno = $('#ref_'+rowNum).val();
		var curNum = rowNum;
		rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrntDr'),
            currentEntry = $(this).parents('.itemdivChldDr:first'), //drtrtype
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="drac_name[]"]')).attr('id', 'rvOdraccount_' + rowNum);
			newEntry.find($('input[name="drac_id[]"]')).attr('id', 'rvOdraccountid_' + rowNum);
			newEntry.find($('input[name="Ddescription[]"]')).attr('id', 'ddescr_' + rowNum);
			newEntry.find($('input[name="Dreference[]"]')).attr('id', 'dref_' + rowNum);
			newEntry.find($('.drtrtype')).attr('id', 'drtrtype_' + rowNum);
			newEntry.find($('input[name="Damount[]"]')).attr('id', 'drdamount_' + rowNum);
			newEntry.find($('input[name="Dcheque_no[]"]')).attr('id', 'dchkno_' + rowNum);
			newEntry.find($('input[name="Dcheque_date[]"]')).attr('id', 'dchkdate_' + rowNum); 
			newEntry.find($('.dr-bank')).attr('id', 'bankid_' + rowNum);
			newEntry.find($('input[name="Dremarks[]"]')).attr('id', 'drmrk_' + rowNum);
			
			//newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
			//newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
			//newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
			//newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
			//newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
			//newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
			//newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
			//newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
			//newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
			//newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
			//newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum);
			
			$('#draccount_'+rowNum).val('');
			$('#draccountid_'+rowNum).val('');
			$('#actamt_'+rowNum).val('');
					
			controlForm.find('.btn-add-item-dr:not(:last)').hide();
			controlForm.find('.btn-remove-item-dr').show();
			
			$('.chqdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy'
			});
			
    }).on('click', '.btn-remove-item-dr', function(e)
    { 
		$(this).parents('.itemdivChldDr:first').remove();
		
		$('.itemdivPrntDr').find('.itemdivChldDr:last').find('.btn-add-item-dr').show();
		if ( $('.itemdivPrntDr').children().length == 1 ) {
			$('.itemdivPrntDr').find('.btn-remove-item-dr').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
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
$(document).on('click', '.accountRow', function(e) { 
	var num = $('#num').val(); var vatasgn = $(this).attr("data-vatassign");
	$('#draccount_'+num).val( $(this).attr("data-name") );
	$('#draccountid_'+num).val( $(this).attr("data-id") );
	$('#groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
	$('#vatamt_'+num).val( $(this).attr("data-vat") );
});


$(document).on('click', '.add-invoice', function(e)  { //NOV24
	
	var refs = []; var amounts = []; var ids = []; var actamt = []; var descr = []; var expacid = []; var isfc = [];
	$("input[name='tag[]']:checked").each(function() { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		ids.push($(this).val());
		//refs.push( $('#refid_'+curNum).val() );
		descr.push( $('#details_'+curNum).val() );
		amounts.push( $('#lineamnt_'+curNum).val() );
		actamt.push( $('#hidamt_'+curNum).val() );
		expacid.push( $('#epacid_'+curNum).val() );
		isfc.push( $('#fc_'+curNum).val() );
	});
	
	var no = 1;
	let rowNum;
	var j = 0; rowNum = $('#orvNo').val(); 
	var drac = $('#craccount_'+no).val();
	var dracid = $('#craccountid_'+no).val();
	console.log(j+' '+no+' '+rowNum);
	$.each(descr,function(i) { 
		if(j > (rowNum-1)) { 
			var controlForm = $('.Crcontrols .itemdivPrnt'),
			currentEntry = $('.divchq').parents('.itemdivChld:first'),
			newEntry = $(currentEntry.clone()).appendTo(controlForm);
			rowNum++;
			newEntry.find($('input[name="account_name[]"]')).attr('id', 'craccount_' + rowNum);
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'craccountid_' + rowNum);
			newEntry.find($('input[name="description[]"]')).attr('id', 'crdescr_' + rowNum);
			//newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
			newEntry.find($('input[name="line_amount[]"]')).attr('id', 'ramount_' + rowNum);
			newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'cractamt_' + rowNum);
			//newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'crchkno_' + rowNum);
			//newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'crchkdate_' + rowNum); 
			newEntry.find($('input[name="expacid[]"]')).attr('id', 'eacid_' + rowNum); 
			//newEntry.find($('.line-bank')).attr('id', 'crbankid_' + rowNum);
			//newEntry.find($('.divchq')).attr('id', 'crchqdtl_' + rowNum);
			newEntry.find($('.refdata')).attr('id', 'crrefdata_' + rowNum);
			newEntry.find($('input[name="isfc[]"]')).attr('id', 'isfc_' + rowNum); 
			newEntry.find($('input[name="je_id[]"]')).attr('id', 'cjeid_' + rowNum); 
			$('#cjeid_'+rowNum).val('');
		}  
		$('#craccount_'+no).val(drac);
		$('#craccountid_'+no).val(dracid);
		$('#crdescr_'+no).val( descr[i] );
		$('#ramount_'+no).val(amounts[i])
		$('#cractamt_'+no).val( actamt[i] );
		$('#eacid_'+no).val( expacid[i] );
		$('#isfc_'+no).val( isfc[i] );
		j++; no++;
	});
	
	getNetTotal();
	//console.log($("#orv_amount").val());
	$("#orv_amount").val( $("#orv_amount").val() );
	$('#drdamount_1').val( $("#orv_amount").val() );
	
});
	
$(document).on('click', '.desc-bill', function(e) {
   var rid = $('#rv_id').val();	   
   var res = this.id.split('_');
   var curNum = res[1]; 
   if(rid=='')
		var url = "{{ url('contractbuilding/os_rvs/') }}/"+$('#conid').val()+"/"+curNum;
	else
		var url = "{{ url('contractbuilding/os_rvs/') }}/"+$('#conid').val()+"/"+curNum+"/"+rid+"/edit"; //NOV24
   $('#invoiceData').load(url, function(result) {
	  $('#myModal').modal({show:true});
   });
});

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

function getNetTotal() {
	var LineTotal = 0;
	$( '.line-amount-orv' ).each(function() { 
		var res = this.id.split('_');
		var curNum = res[1];
		if($("#tag_"+curNum).prop('checked') == true){
			LineTotal = LineTotal + parseFloat( (this.value=='')?0:this.value );
		}
	});	
	$("#orv_amount").val(LineTotal.toFixed(2));
}


$(document).on('click', '#chkedit', function(e) { 
	
	if($(this).is(':checked')) {
		$('#durD').toggle();
		$('#durM').toggle();
	} else {
		$('#durM').toggle();
		$('#durD').toggle();
	}
});

$(document).on('keyup', '#durationD', function(e) {
		
	var vdate = $('#start_date').val();
	var arr = vdate.split('-'); 
	var someDate = new Date(arr[1]+'-'+arr[0]+'-'+arr[2]);
	var numberOfDaysToAdd = parseInt(this.value)-1; 
	someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
	var dd = someDate.getDate();
	var mm = someDate.getMonth() + 1;
	var y = someDate.getFullYear();
	//var someFormattedDate = dd + '-'+ mm + '-'+ y;
	
	var someFormattedDate = ('0' + someDate.getDate()).slice(-2) + '-'+ ('0' + (someDate.getMonth()+1)).slice(-2) + '-'+ someDate.getFullYear();
		 
	$('#end_date').val(someFormattedDate);
	//console.log(someDate+' nw dt '+someFormattedDate);
	
});

$(document).on('change','.trtype', function(e) {
	var no = this.id.split('_');  
	console.log('h '+this.value);
	if(this.value=='C'){
		$('#dracname_'+no[1]).val( $('#cash_ac').val() );
		$('#dracid_'+no[1]).val( $('#cash_acid').val() );
		
		$('#bankid_'+no[1]).attr('required', false); 
		$('#chkno_'+no[1]).attr('required', false); 
		$('#chkdate_'+no[1]).attr('required', false);
	} else if(this.value=='P'){
		$('#dracname_'+no[1]).val( $('#pd_ac').val() );
		$('#dracid_'+no[1]).val( $('#pd_acid').val() );
		
		$('#bankid_'+no[1]).attr('required', true); 
		$('#chkno_'+no[1]).attr('required', true); 
		$('#chkdate_'+no[1]).attr('required', true); 
	} else if(this.value=='B'){ 
		$('#dracname_'+no[1]).val( $('#bk_ac').val() );
		$('#dracid_'+no[1]).val( $('#bk_acid').val() );
		
		$('#bankid_'+no[1]).attr('required', false); 
		$('#chkno_'+no[1]).attr('required', false); 
		$('#chkdate_'+no[1]).attr('required', false);
	}
})

//NOV24
$(document).on('change','#orvno', function(e) {
	let rid = this.value; let id = $('#conid').val();
	let orvurl = "{{ url('contractbuilding/get_orvdetails/') }}";
	$('#orvdetails').load(orvurl+'/'+rid+'/'+id ,function(result) {
		$('#rv_id').val(rid); $('#orv_no').val( $('#orvno option:selected').text() );
		$('#orv_date').val( $('#dater').val() );
	});
});

var rowNum = {{count($pvs)}} //$('#chqdtl_1').toggle();;
 $(document).on('click', '.btn-add-item-pv', function(e)  { 
  
	var group_id = $('#groupid_'+rowNum).val();
	var refno = $('#ref_'+rowNum).val();
	var curNum = rowNum;
	rowNum++; 
	e.preventDefault();
	var controlForm = $('.controls .itemdivPrntPv'),
		currentEntry = $(this).parents('.itemdivChldPv:first'),
		newEntry = $(currentEntry.clone()).appendTo(controlForm);
		newEntry.find($('input[name="PVaccount_name[]"]')).attr('id', 'PVdraccount_' + rowNum);
		newEntry.find($('input[name="account_id[]"]')).attr('id', 'PVdraccountid_' + rowNum);
		newEntry.find($('input[name="description[]"]')).attr('id', 'PVdescr_' + rowNum);
		newEntry.find($('input[name="reference[]"]')).attr('id', 'PVref_' + rowNum);
		newEntry.find($('input[name="group_id[]"]')).attr('id', 'PVgroupid_' + rowNum);
		newEntry.find($('input[name="inv_id[]"]')).attr('id', 'PVinvid_' + rowNum);
		newEntry.find($('.PVline-type')).attr('id', 'PVacnttype_' + rowNum);
		newEntry.find($('input[name="line_amount[]"]')).attr('id', 'PVamount_' + rowNum);
		//newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
		//newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
		newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'PVchkno_' + rowNum);
		newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'PVchkdate_' + rowNum); 
		newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'PVactamt_' + rowNum);
		newEntry.find($('input[name="party_name[]"]')).attr('id', 'PVparty_' + rowNum);
		newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'PVpartyac_' + rowNum);
		newEntry.find($('.line-bank')).attr('id', 'PVbankid_' + rowNum);
		newEntry.find($('.divchq')).attr('id', 'PVchqdtl_' + rowNum);
		newEntry.find($('.refdata')).attr('id', 'PVrefdata_' + rowNum);
		newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'PVinvoiceid_' + rowNum);
		newEntry.find($('input[name="vatamt[]"]')).attr('id', 'PVvatamt_' + rowNum);
		newEntry.find($('input[name="bill_type[]"]')).attr('id', 'PVbiltyp_' + rowNum);
		newEntry.find($('input[name="je_id[]"]')).attr('id', 'cjeid_' + rowNum); 
		
		$('#PVdraccount_'+rowNum).val('');
		$('#PVdraccountid_'+rowNum).val('');
		$('#PVactamt_'+rowNum).val('');
		$('#PVamount_'+rowNum).val('');
		$('#cjeid_'+rowNum).val('');
		
		if(group_id!='1') {
			if( $('#PVacnttype_'+curNum+' option:selected').val()=='Dr') { 
				//$('#acnttype_'+rowNum).val("Cr");
				$('#PVacnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
				$('#PVcredit').val($('#debit').val());
				$('#PVdifference').val(0.00);
				 $('#frmPayment').bootstrapValidator('revalidateField', 'debit');
				$('#frmPayment').bootstrapValidator('revalidateField', 'credit');
			} else {
				$('#PVacnttype_'+rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
				$('#PVdebit').val($('#credit').val());
				$('#PVdifference').val(0.00);
				 $('#frmPayment').bootstrapValidator('revalidateField', 'debit');
				$('#frmPayment').bootstrapValidator('revalidateField', 'credit');
			}
		}
			
		controlForm.find('.btn-add-item-pv:not(:last)').hide();
		controlForm.find('.btn-remove-item-pv').show();
		
		$('.chqdate').datepicker({
			language: 'en',
			dateFormat: 'dd-mm-yyyy'
		});
		
		if(group_id=='1') {// group.id VAT input expense type.
			//console.log('vat');
			var con = confirm('Do you want to update VAT account?');
			if(con) {
				$('#PVdraccount_'+rowNum).val( '<?php //echo ($account)?$account->master_name:'';?>' );
				$('#PVdraccountid_'+rowNum).val( '<?php //echo ($account)?$account->expense_account:'';?>' );
				$('#PVgroupid_'+rowNum).val( '<?php //echo ($account)?$account->id:'';?>' ); //VAT master id
				
				var amount = parseFloat($('#PVamount_'+(rowNum-1)).val());
				var vat = parseFloat($('#PVvatamt_'+(rowNum-1)).val());
				var vatamt = amount * vat / 100;
				$('#PVamount_'+rowNum).val(vatamt);
				$('#PVref_'+rowNum).val(refno);
				
				var amt = 0;
				$( '.PVline-amount' ).each(function() { 
					amt = amt + parseFloat( (this.value=='')?0:this.value );
				});

				var controlForm = $('.controls .itemdivPrntPv'),
				currentEntry = $('.btn-add-item-pv').parents('.itemdivChldPv:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);
				rowNum++;
				newEntry.find($('input[name="PVaccount_name[]"]')).attr('id', 'PVdraccount_' + rowNum).val('');
				newEntry.find($('input[name="account_id[]"]')).attr('id', 'PVdraccountid_' + rowNum);
				newEntry.find($('input[name="description[]"]')).attr('id', 'PVdescr_' + rowNum);
				newEntry.find($('input[name="reference[]"]')).attr('id', 'PVref_' + rowNum);
				newEntry.find($('input[name="group_id[]"]')).attr('id', 'PVgroupid_' + rowNum);
				newEntry.find($('input[name="inv_id[]"]')).attr('id', 'PVinvid_' + rowNum);
				newEntry.find($('.PVline-type')).attr('id', 'PVacnttype_' + rowNum);
				newEntry.find($('input[name="line_amount[]"]')).attr('id', 'PVamount_' + rowNum).val(amt);
				newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'PVactamt_' + rowNum);
				newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'PVchkno_' + rowNum);
				newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'PVchkdate_' + rowNum); 
				newEntry.find($('input[name="party_name[]"]')).attr('id', 'PVparty_' + rowNum);
				newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'PVpartyac_' + rowNum);
				newEntry.find($('.line-bank')).attr('id', 'PVbankid_' + rowNum);
				newEntry.find($('.divchq')).attr('id', 'PVchqdtl_' + rowNum);
				newEntry.find($('.refdata')).attr('id', 'PVrefdata_' + rowNum);
				newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'PVinvoiceid_' + rowNum);
				newEntry.find($('input[name="vatamt[]"]')).attr('id', 'PVvatamt_' + rowNum);
				var des = $('input[name="description[]"]').val();
				//newEntry.find('input').val('');
				$('#trndtl_1'+rowNum).toggle();
								
				controlForm.find('.btn-add-item-pv:not(:last)').hide();
				controlForm.find('.btn-remove-item-pv').show();
				
				$('.chqdate').datepicker({
					language: 'en',
					dateFormat: 'dd-mm-yyyy'
				});
			}
		}
		
}).on('click', '.btn-remove-item-pv', function(e)
{ 
	$(this).parents('.itemdivChldPv:first').remove();
	$('.itemdivPrntPv').find('.itemdivChldPv:last').find('.btn-add-item-pv').show();
	if ( $('.itemdivPrntPv').children().length == 1 ) {
		$('.itemdivPrntPv').find('.btn-remove-item-pv').hide();
	}
	
	e.preventDefault();
	return false;
});

var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="PVaccount_name[]"]', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; 
	$('#PVaccount_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
		$('#myModal').modal({show:true}); $('.input-sm').focus();
	});
});

$(document).on('click', '#PVaccount_data .accountRow', function(e) {  

	var num = $('#num').val(); var vatasgn = $(this).attr("data-vatassign");
	$('#PVdraccount_'+num).val( $(this).attr("data-name") );
	$('#PVdraccountid_'+num).val( $(this).attr("data-id") );
	$('#PVgroupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
	$('#PVvatamt_'+num).val( $(this).attr("data-vat") );
	
	if($(this).attr("data-group")=='PDCR') { 
		$('#PVchktype').val('PDCR');
		$('#PVacnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
		if( $('#PVchqdtl_'+num).is(":hidden") )
			$('#PVchqdtl_'+num).toggle();
			
	} else if($(this).attr("data-group")=='PDCI') { 
		$('#PVchktype').val('PDCI');
		$('#PVacnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
		if( $('#PVchqdtl_'+num).is(":hidden") )
			$('#PVchqdtl_'+num).toggle();
	} else {
		//$('#chktype').val('');
		//$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
		/* if( $('#chqdtl_'+num).is(":visible") )
			$('#chqdtl_'+num).toggle(); */
	}
	
	var nm = num - 1;
	if(nm > 0)
		var refval = $('#ref_'+nm).val();
	else
		refval = '';
	$('#PVrefdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+refval+'" name="reference[]" class="form-control">');
	
	
	if( $(this).attr("data-vatassign")=='1') {// group.id
		$('#trndtl_'+num).toggle();
	}
});	

$(document).on('keyup', '.PVline-amount', function(e) {
	getNetTotalPV();
});
	
$(document).on('change', '.PVline-type', function(e) {
	getNetTotalPV();
	$('#frmPayment').bootstrapValidator('revalidateField', 'debit');
	$('#frmPayment').bootstrapValidator('revalidateField', 'credit');
});

$(document).on('blur', '#receipt_add input[name="cheque_no[]"]', function(e) {
	var chqno = this.value;
	var res = this.id.split('_');
	var curNum = res[1];
	var bank = $('#receipt_add #bankid_'+curNum+' option:selected').val();
	
	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+chqno+'&bank_id='+bank,
		success: function(data) { 
			if(data=='') {
				alert('Cheque no is duplicate!');
				$('#receipt_add #chkno_'+curNum).val('');
			}
		}
	})
});

$(document).on('blur', '#deposit input[name="cheque_no[]"]', function(e) {
	var chqno = this.value;
	var res = this.id.split('_');
	var curNum = res[1];
	var bank = $('#deposit #dbankid_'+curNum+' option:selected').val();
	
	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+chqno+'&bank_id='+bank,
		success: function(data) { 
			if(data=='') {
				alert('Cheque no is duplicate!');
				$('#deposit #dchkno_'+curNum).val('');
			}
		}
	})
});

$(document).on('blur', '#payment input[name="cheque_no[]"]', function(e) {
	var chqno = this.value;
	var res = this.id.split('_');
	var curNum = res[1];
	var bank = $('#payment #PVbankid_'+curNum+' option:selected').val();
	
	$.ajax({
		url: "{{ url('account_master/check_chequeno/') }}",
		type: 'get',
		data: 'chqno='+chqno+'&bank_id='+bank,
		success: function(data) { 
			if(data=='') {
				alert('Cheque no is duplicate!');
				$('#payment #PVchkno_'+curNum).val('');
			}
		}
	})
});
	
function getNetTotalPV() {
	var drLineTotal = 0; var crLineTotal = 0;
	$( '.PVline-amount' ).each(function() {
		var res = this.id.split('_');
		var curNum = res[1];
		if( $('#PVacnttype_'+curNum+' option:selected').val()=='Dr' )
			drLineTotal = drLineTotal + parseFloat( (this.value=='')?0:this.value );
		else if( $('#PVacnttype_'+curNum+' option:selected').val()=='Cr' )
			crLineTotal = crLineTotal + parseFloat( (this.value=='')?0:this.value );
		//console.log('cr'+crLineTotal);
	});
	var difference = drLineTotal - crLineTotal;
	$("#debit").val(drLineTotal.toFixed(2));
	$("#credit").val(crLineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
}

var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="acname[]"]', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; 
	$('#ac_data').load(acurl+'/'+curNum, function(result){
		$('#myModal').modal({show:true}); $('.input-sm').focus();
	});
});

$(document).on('click', '.accountRow', function(e) {
	var num = $('#ac_data #num').val();  
	$('#acid_'+num).val( $(this).attr("data-id") );
	$('#acname_'+num).val( $(this).attr("data-name") );
});
</script>
@stop
