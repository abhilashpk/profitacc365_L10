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
                    Add New
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
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Contract 
                            </h3>
                           
                        </div>
						
						<div class="panel-body">
                             
							<div class="bs-example">
                                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                    <li class="active">
                                        <a href="#home" data-toggle="tab">Contract Entry</a>
                                    </li>
                                    <li>
                                        <a href="#rentallo" data-toggle="tab">Rent Allocation</a>
                                    </li>
									<li>
                                        <a href="#receipt" data-toggle="tab">Receipt</a>
                                    </li>
									<li>
                                        <a href="#deposit" data-toggle="tab">Deposit RV</a>
                                    </li>
									<li>
                                        <a href="#otherrv" data-toggle="tab">Other RV</a>
                                    </li>
                                </ul>
								
                                <div id="myTabContent" class="tab-content">
									<div class="tab-pane fade active in" id="home">
										<form class="form-horizontal" role="form" method="POST" name="frmContract" id="frmContract" action="{{url('contractbuilding/save')}}">
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
													<input type="text" readonly  class="form-control" id="date" name="date" value="{{($crow)?date('d-m-Y',strtotime($crow->contract_date)):''}}" placeholder="{{date('d-m-Y')}}">
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
													<input type="hidden" id="customer_id" name="customer_id">
												</div>
												
												<label for="input-text" class="col-sm-2 control-label">Flat</label>
												<div class="col-sm-4">
													<select class="form-control" name="flat_no" id="flat_id" />
													@if($crow)
													<option value="">{{$crow->flat}}</option>
													@endif
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
												<input type="text" class="form-control pull-right" name="end_date" value="{{($crow)?date('d-m-Y',strtotime($crow->end_date)):''}}" autocomplete="off" readonly id="end_date" />
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Contract Duration</label>
											<div class="col-sm-4">
												<select class="form-control" name="duration" id="duration" />
												<option value="182" {{($crow)?(($crow->duration==182)?'selected':''):''}}>6 Months</option>
												<option value="365" {{($crow)?(($crow->duration==365)?'selected':''):''}}>12 Months</option>
												<option value="730" {{($crow)?(($crow->duration==730)?'selected':''):''}}>24 Months</option>
												<option value="1095" {{($crow)?(($crow->duration==1095)?'selected':''):''}}>36 Months</option>
												<option value="1460" {{($crow)?(($crow->duration==1460)?'selected':''):''}}>48 Months</option>
												<option value="1825" {{($crow)?(($crow->duration==1825)?'selected':''):''}}>60 Months</option>
												</select>
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
												<label for="input-text" class="col-sm-2 control-label"><b>Contract Amount</b></label>
												<div class="col-sm-4">
													<input type="text" class="form-control" id="amt" value="{{($crow)?$crow->rent_amount:''}}" readonly>
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Prepaid Income</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_1" name="acname[]" value="{{($acrow)?$acrow->acname1:''}}" readonly>
													<input type="hidden" id="acid_1" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" id="acamt_1" step="any" name="acamount[]" value="{{($acrow)?$acamt[$acrow->prepaid_income]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" id="actax_1" step="any" name="actax[]" value="{{($acrow)?$acamt[$acrow->prepaid_income]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_1">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Deposit</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_2" name="acname[]" value="{{($acrow)?$acrow->acname2:''}}" readonly>
													<input type="hidden" id="acid_2" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_2" name="acamount[]" value="{{($acrow)?$acamt[$acrow->deposit]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_2" name="actax[]" value="{{($acrow)?$acamt[$acrow->deposit]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_2">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Security Deposit</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_3" name="acname[]" value="{{($acrow)?$acrow->acname3:''}}" readonly>
													<input type="hidden" id="acid_3" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_3" name="acamount[]" value="{{($acrow)?$acamt[$acrow->other_deposit]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_3" name="actax[]" value="{{($acrow)?$acamt[$acrow->other_deposit]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_3">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Commission</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_4" name="acname[]" value="{{($acrow)?$acrow->acname4:''}}" readonly>
													<input type="hidden" id="acid_4" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_4" name="acamount[]" value="{{($acrow)?$acamt[$acrow->commission]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_4" name="actax[]" value="{{($acrow)?$acamt[$acrow->commission]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_4">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Other A/c.</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_5" name="acname[]" value="{{($acrow)?$acrow->acname5:''}}" readonly>
													<input type="hidden" id="acid_5" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_5" name="acamount[]" value="{{($acrow)?$acamt[$acrow->commission]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_5" name="actax[]" value="{{($acrow)?$acamt[$acrow->commission]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_5">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Parking Imcome A/c.</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_6" name="acname[]" value="{{($acrow)?$acrow->acname6:''}}" readonly>
													<input type="hidden" id="acid_6" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_6" name="acamount[]" value="{{($acrow)?$acamt[$acrow->parking]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_6" name="actax[]" value="{{($acrow)?$acamt[$acrow->parking]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_6">
												</div>
											</div>
											
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Ejarie Fee A/c.</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="acname_7" name="acname[]" value="{{($acrow)?$acrow->acname7:''}}" readonly>
													<input type="hidden" id="acid_7" name="acid[]">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtAmt" step="any" id="acamt_7" name="acamount[]" value="{{($acrow)?$acamt[$acrow->ejarie_fee]['amount']:''}}" placeholder="Amount">
												</div>
												<div class="col-sm-2">
													<input type="number" class="form-control txtPer" step="any" id="actax_7" name="actax[]" value="{{($acrow)?$acamt[$acrow->ejarie_fee]['tax']:''}}" placeholder="Tax">
													<input type="hidden" id="istx_7">
												</div>
											</div>
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
											@if(!$conid)
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label"></label>
												<div class="col-sm-10">
													<button type="submit" class="btn btn-primary">Submit</button>
													 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												</div>
											</div>
											@endif
											</form>
                                    </div>
									
                                    <div class="tab-pane fade" id="rentallo">
                                        <form class="form-horizontal" role="form" method="POST" name="frmRentAllo" action="{{url('contractbuilding/save_rentallo')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="con_id" value="{{($crow)?$crow->id:''}}">
										<br/>
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Income Account(Cr)</label>
											<div class="col-sm-7">
												<input type="text" class="form-control" name="inc_account" id="inc_account" value="{{($acrow)?$acrow->acname14:''}}" readonly>
												<input type="hidden" name="inc_acid" id="inc_acid">
											</div>
											<div class="col-sm-3">
												<input type="number" class="form-control" name="ra_amount" id="ra_amount" step="any" value="{{($crow)?$crow->rent_amount:''}}" readonly>
											</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Prepaid Account(Dr)</label>
											<div class="col-sm-7">
												<input type="text" class="form-control" name="preinc_account" id="preinc_account" value="{{($acrow)?$acrow->acname1:''}}" readonly>
												<input type="hidden" name="preinc_acid" id="preinc_acid">
												<input type="hidden" id="str_date" value="{{($crow)?$crow->start_date:''}}">
												<input type="hidden" id="ed_date" value="{{($crow)?$crow->end_date:''}}">
												<input type="hidden" id="custname" value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" id="refno" name="reference" value="{{($crow)?$crow->contract_no:''}}">
											</div>
											<div class="col-sm-3">
												<button class="btn btn-primary allocate" type="button">Allocate</button>
											</div>
										</div>
										<div id="res_allocate">
											@if($jvs)
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>JV No.</th> <th>JV Date</th> <th>Amount</th> <th>Description</th>
													</tr>
												</thead>
												<tbody>
												@foreach($jvs as $row)
													<tr>
														<td>{{$row->voucher_no}}</td>
														<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
														<td>{{number_format($row->amount,2)}}</td>
														<td>{{$row->voucher_no.'/'.date('d-m-Y',strtotime($row->voucher_date))}}</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										@endif
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												@if($crow && $crow->rv_status < 1)
												<button type="submit" class="btn btn-primary">Submit</button>
												@endif
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 <!--<a href="{{ url('contractbuilding/printjv/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>-->
											</div>
										</div>
										</form>
                                    </div>
									
									<div class="tab-pane fade" id="receipt">
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
												<input type="number" class="form-control" id="rv_date" step="any" name="rv_date" placeholder="{{($rvs)?$drvs[0]->voucher_date:date('d-m-Y')}}">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Tenant(Cr)</label>
											<div class="col-sm-3">
												<input type="text" class="form-control" name="tenant" id="tenant" readonly value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" name="tenant_id" id="tenant_id" readonly value="{{($crow)?$crow->customer_id:''}}">
											</div>											
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-1 control-label">Amount</label>
											<div class="col-sm-2">
												<input type="number" class="form-control" id="rv_amount" step="any" name="rv_amount" placeholder="Amount" value="{{($crow)?$crow->rent_amount:''}}">
											</div>
											<label for="input-text" class="col-sm-2 control-label">Installment</label>
											<div class="col-sm-2">
												<input type="number" class="form-control" id="installment" step="any" value="{{($rvs)?count($rvs)-1:''}}" name="installment">
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
													<span class="small">Debit A/c.</span> <input type="text" id="dracname_{{$i}}" value="{{$row->master_name}}" autocomplete="off" name="drac_name[]" class="form-control">
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
													<span class="small">Type</span> 
													<select id="trtype_{{$i}}" class="form-control trtype" style="width:100%" name="tr_type[]">
														<option value="C" {{($row->cheque_no=='')?'selected':''}}>C</option>
														<option value="B" {{($row->cheque_no!='')?'selected':''}}>B</option>
														<option value="P" {{($row->cheque_no!='')?'selected':''}}>P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$row->amount}}" autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>

												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_{{$i}}" value="{{$row->cheque_no}}" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_{{$i}}" value="{{($row->cheque_no!='')?$row->cheque_date:''}}" name="cheque_date[]" class="form-control chqdate" data-language='en'>
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Bank</span> 
													<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
														<option value="">Bank</option>
														
													</select>
												</div>

												<div class="col-xs-3" style="width:13%;">
													<span class="small">Remarks</span> <input type="text" id="rmrk_{{$i}}" name="remarks[]" value="" class="form-control">
												</div>
												@endif
												@endforeach
												@endif
											</div>	
										</fieldset><br/>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												@if($crow && $crow->rv_status < 2)
												<button type="submit" class="btn btn-primary">Submit</button>
												@endif
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 <a href="{{ url('contractbuilding/printrv/RV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
											</div>
										</div>
										</form>
									</div>
									
									<div class="tab-pane fade" id="deposit">
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
												<input type="text" class="form-control" id="drv_date" name="rv_date" placeholder="{{($drvs)?date('d-m-Y',strtotime($drvs[0]->voucher_date)):date('d-m-Y')}}">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Amount</label>
											<div class="col-sm-2" style="width:12%;">
												<input type="number" class="form-control" step="any" id="drv_amount" name="rv_amount" value="{{($drvs)?$drvs[0]->amount:''}}" placeholder="Amount">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Tenant(Cr)</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" name="tenant" readonly value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" name="tenant_id" readonly value="{{($crow)?$crow->customer_id:''}}">
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
													<input type="text" id="ddesc_1" name="description[]" value="{{($crow)?($crow->contract_no.'/'.$crow->master_name):''}}" autocomplete="off" class="form-control">
												</div>

												<div class="col-xs-1" style="width:7%;">
													<span class="small">Type</span> 
													<select id="dtrtype_1" class="form-control dtrtype" style="width:100%" name="tr_type[]">
														<option value="C">C</option>
														<option value="B">B</option>
														<option value="P">P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="damount_1" placeholder="Amount" readonly autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>

												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" id="dchkno_1" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="dchkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
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
													<span class="small">Type</span> 
													<select id="dtrtype_1" class="form-control dtrtype" style="width:100%" name="tr_type[]">
														<option value="C">C</option>
														<option value="B">B</option>
														<option value="P">P</option>
													</select>
												</div>
												<div class="col-xs-2" style="width:10%;">
													<span class="small">Amount</span> <input type="number" id="damount_1" value="{{$drow->amount}}" placeholder="Amount" readonly autocomplete="off" step="any" name="amount[]" class="form-control">
												</div>

												<div class="col-sm-2" style="width:10%;"> 
													<span class="small">Cheque No</span><input type="text" autocomplete="off" id="dchkno_1" name="cheque_no[]" class="form-control" >
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="dchkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
												</div>

												<div class="col-xs-3" style="width:10%;">
													<span class="small">Bank</span> 
													<select id="dbankid_1" class="form-control select2" style="width:100%" name="bank_id[]">
														<option value="">Bank</option>
													</select>
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
												@if($crow && $crow->rv_status < 3)
												<button type="submit" class="btn btn-primary">Submit</button>
												@endif
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 <a href="{{ url('contractbuilding/printrv/DRV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
											</div>
										</div>
										</form>
									</div>
									
									<div class="tab-pane fade" id="otherrv">
										<div class="controls"> 
										<form class="form-horizontal" role="form" method="POST" name="frmDeposit" action="{{url('contractbuilding/save_otherrv')}}">
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
												<input type="text" class="form-control" name="rv_no" id="orv_no" readonly value="{{($orvs)?$orvs[0]->voucher_no:$rvrow->voucher_no}}" >
											</div>
											<label for="input-text" class="col-sm-1 control-label">RV Date</label>
											<div class="col-sm-2" style="width:12%;">
												<input type="text" class="form-control" id="orv_date" name="rv_date" value="{{($orvs)?$orvs[0]->voucher_date:date('d-m-Y')}}">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Amount</label>
											<div class="col-sm-2" style="width:12%;">
												<input type="number" class="form-control" step="any" id="orv_amount" name="rv_amount" value="{{($orvs)?$orvs[0]->amount:''}}" placeholder="Amount">
											</div>
											<label for="input-text" class="col-sm-1 control-label">Tenant(Cr)</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" name="tenant" readonly value="{{($crow)?$crow->master_name:''}}">
												<input type="hidden" name="tenant_id" readonly value="{{($crow)?$crow->customer_id:''}}">
											</div>	
										</div>
										
										<fieldset>
											<legend><h5>Transactions(Dr)</h5></legend>
											
											<div class="col-xs-15">
											@if($orvs)
											@foreach($orvs as $orow)
											@if($orow->entry_type=='Dr')
												<div class="itemdivPrnt">
													<div class="itemdivChld">							
														<div class="form-group" style="margin-bottom: 1px;">
															<div class="col-sm-2" style="width:15%;"> <span class="small">Debit A/c.</span>
															<input type="text" id="draccount_1" name="account_name[]" class="form-control acname" value="{{$orow->master_name}}"  autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" id="draccountid_1">
															<input type="hidden" name="group_id[]" id="groupid_1">
															<input type="hidden" name="vatamt[]" id="vatamt_1">
															
															<input type="hidden" id="invoiceid_1" name="sales_invoice_id[]">
															<input type="hidden" name="bill_type[]" id="biltyp_1">
															</div>
															<div class="col-xs-15">
																<div class="col-xs-2" style="width:15%;">
																	<span class="small">Description</span> <input type="text" id="descr_1" value="{{$orow->description}}"  autocomplete="off" name="description[]" class="form-control">
																</div>
																<div class="col-xs-2" style="width:10%;">
																	<span class="small">Reference</span> 
																	<div id="refdata_1" class="refdata">
																	<input type="text" id="ref_1" name="reference[]" value="{{$orow->reference}}"  autocomplete="off" class="form-control">
																	</div>
																</div>
																
																<div class="col-xs-2" style="width:12%;">
																	<span class="small">Amount</span> <input type="number" id="amount_1" value="{{$orow->amount}}"  autocomplete="off" step="any" name="line_amount[]" class="form-control line-amount">
																</div>
																	
																	<div id="chqdtl_1" class="divchq">
																		<div class="col-xs-2" style="width:10%;">
																			<span class="small">Bank</span> 
																			<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																				<option value="">Bank</option>
																				
																			</select>
																		</div>
																		
																		<div class="col-sm-2" style="width:10%;"> 
																			<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
																		</div>
																		
																		<div class="col-xs-2" style="width:10%;">
																			<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
																		</div>
																		
																		<div class="col-xs-2" style="width:12%;">
																			<input type="hidden" name="partyac_id[]" id="partyac_1">
																			<span class="small">Party Name</span> <input type="text" id="party_1" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
																		</div>
																	</div>
															
																<div class="col-xs-1 abc" style="width:3%;"><br/>
																	<button type="button" class="btn-danger btn-remove-item" >
																		<i class="fa fa-fw fa-minus-square"></i>
																	 </button>
																	 <button type="button" class="btn-success btn-add-item" >
																		<i class="fa fa-fw fa-plus-square"></i>
																	 </button>
																</div>
															</div>	
														</div>
																												
													</div>
												</div>
												@endif
												@endforeach
												@else
												<div class="itemdivPrnt">
													<div class="itemdivChld">							
														<div class="form-group" style="margin-bottom: 1px;">
															<div class="col-sm-2" style="width:15%;"> <span class="small">Debit A/c.</span>
															<input type="text" id="draccount_1" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
															<input type="hidden" name="account_id[]" id="draccountid_1">
															<input type="hidden" name="group_id[]" id="groupid_1">
															<input type="hidden" name="vatamt[]" id="vatamt_1">
															
															<input type="hidden" id="invoiceid_1" name="sales_invoice_id[]">
															<input type="hidden" name="bill_type[]" id="biltyp_1">
															</div>
															<div class="col-xs-15">
																<div class="col-xs-2" style="width:15%;">
																	<span class="small">Description</span> <input type="text" id="descr_1" value="{{($crow)?($crow->contract_no.'/'.$crow->master_name):''}}" autocomplete="off" name="description[]" class="form-control">
																</div>
																<div class="col-xs-2" style="width:10%;">
																	<span class="small">Reference</span> 
																	<div id="refdata_1" class="refdata">
																	<input type="text" id="ref_1" name="reference[]" value="{{($crow)?$crow->contract_no:''}}" autocomplete="off" class="form-control">
																	</div>
																	<input type="hidden" name="inv_id[]" id="invid_1">
																	<input type="hidden" name="actual_amount[]" id="actamt_1">
																</div>
																
																<div class="col-xs-2" style="width:12%;">
																	<span class="small">Amount</span> <input type="number" id="amount_1" autocomplete="off" step="any" name="line_amount[]" class="form-control line-amount">
																</div>
																	
																	<div id="chqdtl_1" class="divchq">
																		<div class="col-xs-2" style="width:10%;">
																			<span class="small">Bank</span> 
																			<select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																				<option value="">Bank</option>
																				@foreach($banks as $bank)
																				<option value="{{$bank->id}}">{{$bank->name}}</option>
																				@endforeach
																			</select>
																		</div>
																		
																		<div class="col-sm-2" style="width:10%;"> 
																			<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
																		</div>
																		
																		<div class="col-xs-2" style="width:10%;">
																			<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
																		</div>
																		
																		<div class="col-xs-2" style="width:12%;">
																			<input type="hidden" name="partyac_id[]" id="partyac_1">
																			<span class="small">Party Name</span> <input type="text" id="party_1" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
																		</div>
																	</div>
															
																<div class="col-xs-1 abc" style="width:3%;"><br/>
																	<button type="button" class="btn-danger btn-remove-item" >
																		<i class="fa fa-fw fa-minus-square"></i>
																	 </button>
																	 <button type="button" class="btn-success btn-add-item" >
																		<i class="fa fa-fw fa-plus-square"></i>
																	 </button>
																</div>
															</div>	
														</div>
																												
													</div>
												</div>
												@endif
											</div>
										</fieldset>
										<br/>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label"></label>
											<div class="col-sm-10">
												@if($crow && $crow->rv_status < 4)
												<button type="submit" class="btn btn-primary">Submit</button>
												@endif
												 <a href="{{ url('contractbuilding') }}" class="btn btn-danger">Cancel</a>
												 <a href="{{ url('contractbuilding/printrv/ORV/'.(($crow)?$crow->id:'')) }}" target="_blank" class="btn btn-info">Print</a>
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
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
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
$('#tenantDtls').hide(); $('#owndetils').hide();
$('#passport_exp').datepicker( { 
	dateFormat: 'dd-mm-yyyy',
	autoClose: true
});
$('#start_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );


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

$(document).on('click', '.DT', function(e) { e.preventDefault();
	$('#tenantDtls').toggle();
});

$(document).on('click', '.DT2', function(e) { e.preventDefault();
	$('#owndetils').toggle();
});

$(document).on('change', '#duration', function(e) {
		
	var vdate = $('#start_date').val();
	var arr = vdate.split('-'); 
	var someDate = new Date(arr[1]+'-'+arr[0]+'-'+arr[2]);
	var numberOfDaysToAdd = parseInt(this.value); 
	someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
	var dd = someDate.getDate();
	var mm = someDate.getMonth() + 1;
	var y = someDate.getFullYear();
	//var someFormattedDate = dd + '-'+ mm + '-'+ y;
	
	var someFormattedDate = ('0' + someDate.getDate()).slice(-2) + '-'+ ('0' + (someDate.getMonth()+1)).slice(-2) + '-'+ someDate.getFullYear();
		 
	$('#end_date').val(someFormattedDate);
	//console.log(someDate+' nw dt '+someFormattedDate);
	
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

$(document).on('click', '.allocate', function(e) {
	let sdate = $('#str_date').val();
	let amt = $('#ra_amount').val();
	let cname = $('#custname').val();
	let edate = $('#ed_date').val();
	$.ajax({
		url: "{{ url('contractbuilding/rent_allocate/') }}",
		type: 'get',
		data: 'date='+sdate+'&amount='+amt+'&cname='+cname+'&edate='+edate,
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
 
$(document).on('click', '.rvadd', function(e) {
	let amt = $('#rv_amount').val();
	let inst = $('#installment').val();
	let cac = $('#cash_ac').val();
	let cacid = $('#cash_acid').val();
	let ref = $('#refno').val();
	let dec = $('#tenant').val();
	$.ajax({
		url: "{{ url('contractbuilding/receipt_add/') }}",
		type: 'get',
		data: 'amount='+amt+'&inst='+inst+'&cac='+cac+'&cacid='+cacid+'&ref='+ref+'&dec='+dec,
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
	} else if(this.value=='P'){
		$('#ddracname_'+no[1]).val( $('#pd_ac').val() );
		$('#ddracid_'+no[1]).val( $('#pd_acid').val() );
	} else if(this.value=='B'){
		$('#ddracname_'+no[1]).val( $('#bk_ac').val() );
		$('#ddracid_'+no[1]).val( $('#bk_acid').val() );
	}
})

	var rowNum = 1; $('.btn-remove-item').hide(); 
  $(document).on('click', '.btn-add-item', function(e)  { 
  
        var group_id = $('#groupid_'+rowNum).val();
		var refno = $('#ref_'+rowNum).val();
		var curNum = rowNum;
		rowNum++; 
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
			newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
			newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
			newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
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
			
			$('#draccount_'+rowNum).val('');
			$('#draccountid_'+rowNum).val('');
			$('#actamt_'+rowNum).val('');
			if(group_id!='1') {
				if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') { 
					//$('#acnttype_'+rowNum).val("Cr");
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
					$('#credit').val($('#debit').val());
					$('#difference').val(0.00);
					 $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
				} else {
					$('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
					$('#debit').val($('#credit').val());
					$('#difference').val(0.00);
					 $('#frmJournal').bootstrapValidator('revalidateField', 'debit');
					$('#frmJournal').bootstrapValidator('revalidateField', 'credit');
				}
			}
				
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('.chqdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy'
			});
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
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
</script>
@stop
