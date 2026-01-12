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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		}
		
	</style>
	
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Job Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Job Invoice</a>
                </li>
                <li class="active">
                    Add
                </li>
				
            </ol>
			
        </section>
        <!--section ends-->
		
		<!--section ends-->
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
		
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
				<?php if(sizeof($vouchers)==0) { ?>
				<div class="alert alert-warning">
					<p>
						Voucher No. is found empty! Please create in Account Settings.
					</p>
				</div>
				<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Job Invoice
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('si-print')
								 <a href="{{ url('job_invoice/print/'.$printid->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
								
							<?php } ?>
							</div>
							
                        </div>
							
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesInvoice" id="frmSalesInvoice" action="{{ url('job_invoice/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ $locdefault->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php if(old('voucher_id')==$voucher['id']) echo 'selected'; ?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JI. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$voucher['voucher_no']}}">
                                    <div class="col-sm-10">
										@can('si-invno')
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" value="{{(old('voucher_no'))?old('voucher_no'):$voucher['voucher_no']}}" readonly name="voucher_no">
										<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
										@else
										<input type="text" class="form-control" id="voucher_no" value="{{(old('voucher_no'))?old('voucher_no'):$voucher['voucher_no']}}" readonly name="voucher_no">
										@endcan
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" autocomplete="off" name="reference_no" placeholder="Reference No.">
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right"  <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?> name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<?php if($formdata['lpo_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" value="{{old('lpo_no')}}" autocomplete="off" placeholder="LPO No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_no" id="lpo_no">
								<?php } ?>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control lpo_date1" id="lpo_date" <?php if(old('lpo_date')!='') { ?> value="{{old('lpo_date')}}" <?php } ?> name="lpo_date" data-language='en' readonly placeholder="LPO Date">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Account</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="cr_account_id" id="cr_account_id" value="{{ (old('cr_account_id'))?old('cr_account_id'):$voucher['cr_account_master_id'] }}" class="form-control">
										<div class="input-group">
											<input type="text" name="sales_account" id="sales_account" class="form-control" value="{{ (old('sales_account'))?old('sales_account'):$vouchers[0]['account_id'].'-'.$vouchers[0]['master_name'] }}" readonly>
											<span class="input-group-addon inputsa"><i class="fa fa-fw fa-edit"></i></span>
										</div>
									</div>
                                </div>
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Type</label>
                                    <div class="col-sm-10">
                                       <select id="sales_type" class="form-control select2" style="width:100%" name="sales_type">
											<option value="normal" <?php if(old('sales_type')=='normal') echo 'selected'; ?>>Normal</option>
											<option value="ltol" <?php if(old('sales_type')=='ltol') echo 'selected'; ?>>Location to Location</option>
                                        </select>
                                    </div>
                                </div>-->
								<input type="hidden" name="sales_type" id="sales_type" value="normal">
								
								<div class="form-group" id="saleloc">
                                    <label for="input-text" class="col-sm-2 control-label">Job Location</label>
                                    <div class="col-sm-10">
                                        <select id="sales_location" class="form-control select2" style="width:100%" name="sales_location">
											<option value="" selected>Select Location..</option>
											<?php 
											foreach($saleslocation as $sloc) { 
											?>
											<option value="{{ $sloc->id }}" <?php if(old('sales_location')==$sloc->id) echo 'selected'; ?>>{{ $sloc->name }}</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>">Customer</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" value="{{old('customer_name')}}" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer" readonly>
										<input type="hidden" name="customer_id" id="customer_id" value="{{old('customer_id')}}">
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="{{old('dr_account_id')}}">
										<div class="col-xs-10" id="customerInfo">
											<div class="col-xs-4">
												<span class="small">Current Balance</span> <input type="text" id="cr_balance" name="cbal" value="{{old('cbal')}}" readonly class="form-control line-quantity">
											</div>
											<div class="col-xs-4">
												<span class="small">PDC</span> <input type="text" id="pdc" name="pdc" value="{{old('pdc')}}" readonly class="form-control line-cost">
											</div>
											<div class="col-xs-3">
												<span class="small">Crdit Limit</span> <input type="text" id="cr_limit" name="clmt" value="{{old('clmt')}}" readonly readonly class="form-control cost">
											</div>
											<div class="col-xs-1"><br/>
												@can('si-history')
												<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">History</a>
												@endcan
											</div>
										</div>
										<div class="col-xs-10" id="newcustomerInfo">
											<div class="col-xs-4">
												<span class="small">Customer Name</span> <input type="text" id="customername" name="customername" value="{{old('customername')}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#newcustomer_modal">
											</div>
											<div class="col-xs-4">
												<span class="small">TRN No</span> <input type="text" id="customer_trn" name="customer_trn" value="{{old('customer_trn')}}" class="form-control" autocomplete="off">
											</div>
											<div class="col-xs-3">
												<span class="small">Phone No</span> <input type="text" id="customer_phone" name="customer_phone" value="{{old('customer_phone')}}" class="form-control" autocomplete="off">
											</div>
											<div class="col-xs-1"><br/>
												@can('si-history')
												<a href="" class="btn btn-info cust-history" data-toggle="modal" data-target="#custhistory_modal">History</a>
												@endcan
												@can('siph-history')
												<a href="" class="btn btn-info cust-history-phone" data-toggle="modal" data-target="#custphonehistory_modal">History</a>
												@endcan
											</div>
										</div>
									</div>
                                </div>
								
								<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No." value="{{old('vat_no')}}" autocomplete="off">
                                    </div>
                                </div>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document Type</label>
                                    <div class="col-sm-10">
									 <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
										<option value="">Select Document...</option>
										<option value="SQ">Job Estimate</option>
										<option value="SO">Job Order</option>
									</select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_type" id="document_type">
								<?php } ?>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document ID#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php } ?>
								
								<?php if($formdata['jobnature']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Job Nature</label>
                                    <div class="col-sm-10">
                                        <select id="jobnature" class="form-control select2" style="width:100%" name="jobnature">
											<option value="0">Workshop</option>
											<option value="1">Fabrication</option>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="jobnature" id="jobnature">
								<?php } ?>
								
								<?php if($formdata['fabrication']==1) { ?>
								<div class="form-group" id="fabData">
                                    <label for="input-text" class="col-sm-2 control-label"> Fabrication</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fabrication" name="fabrication" placeholder="Fabrication" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="fabrication" id="fabrication">
								<?php } ?>
								
								<?php if($formdata['vehicle']==1) { ?>
								<div class="form-group" id="vclData">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('vehicle_name')) echo 'form-error';?>">Vehicle</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="vehicle_name" id="vehicle_name" value="<?php echo (isset($vehicledata))?$vehicledata->name:''?>" class="form-control <?php if($errors->has('vehicle_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#vehicle_modal" placeholder="Vehicle">
										<input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo (isset($vehicledata))?$vehicledata->id:''?>">
										<div class="col-xs-10" id="vehicleInfo">
											<div class="col-xs-2">
												<span class="small">Vehicle Reg.No.</span> <input type="text" id="vehicle_regno" name="vehicle_regno" value="{{old('vehicle_regno')}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Issue Plate</span> <input type="text" id="issue_plate" name="issue_plate" value="{{old('issue_plate')}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Code Plate</span> <input type="text" id="code_plate" name="code_plate" value="{{old('code_plate')}}" readonly class="form-control">
											</div>
											<div class="col-xs-3">
												<span class="small">Make</span> <input type="text" id="make" name="make" value="{{old('make')}}" readonly class="form-control">
											</div>
											<div class="col-xs-3">
												<span class="small">Model</span> <input type="text" id="model" name="model" value="{{old('model')}}" readonly class="form-control">
											</div>
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="vehicle_id" id="vehicle_id">
								<?php } ?>
								
								<?php if($formdata['jobtype']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('job_type')) echo 'form-error';?>">Job Type</label>
                                    <div class="col-sm-10">
                                        <select id="job_type" class="form-control select2 <?php if($errors->has('job_type')) echo 'form-error';?>" style="width:100%" name="job_type">
                                            <option value="">Select Job Type...</option>
											<?php foreach($jobtype as $row) { ?>
											<option value="{{$row->id}}" <?php if(old('job_type')==$row->id) echo 'selected'; ?>>{{$row->name}}</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_type" id="job_type">
								<?php } ?>
								
								<?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Technician</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Technician">
										<input type="hidden" name="salesman_id" id="salesman_id">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<?php if($formdata['kilometer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Kilometer</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="kilometer" autocomplete="off" value="{{ old('kilometer') }}" name="kilometer" placeholder="Kilometer">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="kilometer" id="kilometer">
								<?php } ?>
								
								<?php if($formdata['terms']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
                                            <option value="">Select Terms...</option>
											@foreach ($terms as $term)
											<option value="{{ $term['id'] }}">{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="terms_id" id="terms_id">
								<?php } ?>
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('job_id')) echo 'form-error';?>">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2 <?php if($errors->has('job_id')) echo 'form-error';?>" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}" <?php if($job['id']==old('job_id')) echo 'selected'; ?>>{{ $job['code'].' ('.$job['name'].')' }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								
								<?php if($formdata['location']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-10">
                                        <select id="location_id" class="form-control select2" style="width:100%" name="location_id">
											<?php 
											$is_default = 0;
											foreach($location as $loc) { 
											if($loc->is_default==1)
												$is_default = 1;
											?>
											<option value="{{ $loc['id'] }}" <?php if(old('location_id')==$loc['id']) echo 'selected'; ?>>{{ $loc['name'] }}</option>
											<?php } ?>
											
											<?php if($is_default==0) { ?>
											<option value="" selected>Select Location..</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="location_id" id="location_id">
								<?php } ?>
								
								<?php if($formdata['foreign_currency']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
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
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
								<?php } ?>
								
								<?php if($formdata['jobdescription']==1) { ?>
								<div class="descdivPrnt">
									<div class="descdivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label desclbl" id="lblid_1">Description 1</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="oprdescid_1" autocomplete="on" name="opr_description[]" placeholder="Description">
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control" id="oprcmntid_1" autocomplete="on" name="opr_comment[]" placeholder="Comments">
											</div>
											<div class="col-sm-1">
												<button type="button" class="btn-success btn-add-desc-com">
													<i class="fa fa-fw fa-plus-square"></i>
												</button>
												<button type="button" class="btn-danger btn-remove-desc-com">
													<i class="fa fa-fw fa-minus-square"></i>
												 </button>
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Export</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="export" id="export" name="is_export" value="1">
											</label>
										</div>
									</div>
                                </div>
								
								<br/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
								<?php if($formdata['items_description']==1) { ?>
										   <div class="form-group">
											<label for="input-text" class="col-sm-2 control-label " id="">Items Description </label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="items_description" autocomplete="off" name="items_description" placeholder="Items Description">
											</div>
											</div>
								           <?php } else {?>
								       <input type="hidden" name="items_description" id="items_description">
								       <?php } ?>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="14%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="25%" class="itmHd">
											<span class="small">Item Description</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Quantity</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Cost/Unit</span>
										</th>
										<th width="4%" class="itmHd">
											<span class="small">Tx.Code</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Tx.Inc.</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">VAT Amt.</span> 
										</th>
										<th width="14%" class="itmHd">
											<span class="small">Total</span> 
										</th>
										
									</tr>
									</thead>
								</table>
								<div class="itemdivPrnt">
									<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
													<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" value="{{ $item }}" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" value="{{ old('item_name')[$i]}}" id="itmdes_{{$j}}" autocomplete="off" class="form-control" placeholder="Item Description">
												</td>
												<td width="7%">
													<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_{{$j}}" step="any" name="quantity[]" value="{{ old('quantity')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$j}}" step="any" name="cost[]" value="{{ old('cost')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" value="{{ old('vatdiv')[$i]}}" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_{{$j}}" name="line_vat[]" value="{{ old('line_vat')[$i]}}" class="form-control vat">
													<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" value="{{ old('vatline_amt')[$i]}}" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" value="{{ old('line_discount')[$i]}}">
													<input type="hidden" id="costavg_{{$j}}" name="costavg[]" value="{{ old('costavg')[$i]}}">
													<input type="hidden" id="purcost_{{$j}}" name="purcost[]" value="{{ old('purcost')[$i]}}">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" value="{{ old('item_total')[$i]}}" class="form-control line-total" readonly placeholder="Total">
													<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('line_total')[$i]}}">
												</td>
												<td width="1%">
													<?php if(count(old('item_code'))==$j) { ?>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } else { ?>
														 <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } ?>
												</td>
											</tr>
										</table>
										
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_{{$j}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$j}}">
													</div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$j}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$j}}"></div>
												</div>
											</div>
												
											<div class="descdivPrntItm" id="descdivPrntItm_{{$j}}">
												<div class="descdivChldItm" >							
													<div class="col-xs-10" style="padding-bottom:5px !important;">
														<div class="col-xs-10">
															<input type="text" name="itemdesc[0][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
														</div>
														<div class="col-xs-2">
															 <button type="button" class="btn btn-success btn-add-desc" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
															 <button type="button" class="btn btn-danger btn-remove-desc" >
																<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
															</button>
														</div>
													</div>
												</div>
											</div>
									</div>
									<?php $i++; } } else { ?>
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="item_id[]" id="itmid_1">
													<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
												</td>
												<td width="7%">
													<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
												</td>
												<td width="6%">
													<select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_1" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
													<input type="hidden" id="packing_1" name="packing[]" value="1">
													<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_1" name="line_vat[]" class="form-control vat">
													<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_1" name="line_discount[]">
													<input type="hidden" id="costavg_1" name="costavg[]">
													<input type="hidden" id="purcost_1" name="purcost[]">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
													<input type="hidden" id="itmtot_1" name="item_total[]">
												</td>
												<td width="1%">
													<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
												</td>
											</tr>
										</table>
										
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_1" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_1" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_1" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
										
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1">
													</div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_1">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_1"></div>
												</div>
											</div>
												
											<div class="descdivPrntItm" id="descdivPrntItm_1">
												<div class="descdivChldItm" >							
													<div class="col-xs-10" style="padding-bottom:5px !important;">
														<div class="col-xs-10">
															<input type="text" name="itemdesc[0][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
														</div>
														<div class="col-xs-2">
															 <button type="button" class="btn btn-success btn-add-desc" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
															 <button type="button" class="btn btn-danger btn-remove-desc" >
																<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
															</button>
														</div>
													</div>
												</div>
											</div>
									</div>
								<?php } ?>
								</div>
								</fieldset>
								
								
								<fieldset style="margin-top:120px;">
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Labour Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="13%" class="itmHd">
											<span class="small">Labour Code</span>
										</th>
										<th width="22%" class="itmHd">
											<span class="small">Labour Description</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Hour</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Rate</span>
										</th>
										<th width="4%" class="itmHd">
											<span class="small">Tx.Code</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Tx.Inc.</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">VAT Amt.</span> 
										</th>
										<th width="12%" class="itmHd">
											<span class="small">Total</span> 
										</th>
										
									</tr>
									</thead>
								</table>
								
								<div class="lbitemdivPrnt">
									<?php if(old('lbitem_code')) { $i = 0; 
										foreach(old('lbitem_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="lbitem_id[]" id="lbitmid_{{$j}}" value="{{ old('lbitem_id')[$i]}}">
													<input type="text" id="lbitmcod_{{$j}}" name="lbitem_code[]" class="form-control <?php if($errors->has('lbitem_code.'.$i)) echo 'form-error';?>" value="{{ $item }}" autocomplete="off" placeholder="Labour Code" data-toggle="modal" data-target="#lbitem_modal">
												</td>
												<td width="29%">
													<input type="text" name="lbitem_name[]" value="{{ old('lbitem_name')[$i]}}" id="lbitmdes_{{$j}}" autocomplete="off" class="form-control" placeholder="Labour Description">
												</td>
												<td width="7%">
													<select id="lbitmunt_{{$j}}" class="form-control select2 <?php if($errors->has('lbunit_id.'.$i)) echo 'form-error';?> lbline-unit" style="width:100%" name="lbunit_id[]"><option value="{{old('lbunit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
												</td>
												<td width="8%">
													<input type="number" id="lbitmqty_{{$j}}" step="any" name="lbquantity[]" value="{{ old('lbquantity')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('lbquantity.'.$i)) echo 'form-error';?> lbline-quantity" placeholder="Hrs.">
												</td>
												<td width="8%">
													<input type="number" id="lbitmcst_{{$j}}" step="any" name="lbcost[]" value="{{ old('lbcost')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('lbcost.'.$i)) echo 'form-error';?> lbline-cost" placeholder="Rate">
												</td>
												<td width="6%">
													<select id="lbtaxcode_{{$j}}" class="form-control select2 lbtax-code" style="width:100%" name="lbtax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="lbtxincld_{{$j}}" class="form-control select2 lbtaxinclude" style="width:100%" name="lbtax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="lbhidunit_{{$j}}" name="lbhidunit[]" class="lbhidunt" value="{{old('lbhidunit')[$i]}}">
													<input type="text" id="lbvatdiv_{{$j}}" step="any" readonly name="lbvatdiv[]" value="{{ old('lbvatdiv')[$i]}}" class="form-control lbvatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="lbvat_{{$j}}" name="lbline_vat[]" value="{{ old('lbline_vat')[$i]}}" class="form-control lbvat">
													<input type="hidden" id="lbvatlineamt_{{$j}}" name="lbvatline_amt[]" value="{{ old('lbvatline_amt')[$i]}}" class="form-control lbvatline-amt" value="0">
													<input type="hidden" id="lbitmdsnt_{{$j}}" name="lbline_discount[]" value="{{ old('lbline_discount')[$i]}}">
												</td>
												<td width="11%">
													<input type="number" id="lbitmttl_{{$j}}" step="any" name="lbline_total[]" value="{{ old('lbitem_total')[$i]}}" class="form-control lbline-total" readonly placeholder="Total">
													<input type="hidden" id="lbitmtot_{{$j}}" name="lbitem_total[]" value="{{ old('lbline_total')[$i]}}">
												</td>
												<td width="1%">
														<?php if(count(old('lbitem_code'))==$j) { ?>
														<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } else { ?>
														 <button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } ?>
												</td>
											</tr>
										</table>
									</div>
									<?php $i++; } } else { ?>
									
									<!-- Labor -->
									<div class="lbitemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="lbitem_id[]" id="lbitmid_1">
													<input type="text" id="lbitmcod_1" name="lbitem_code[]" class="form-control" autocomplete="off" autocomplete="off" placeholder="Labour Code" data-toggle="modal" data-target="#lbitem_modal">
												</td>
												<td width="29%">
													<input type="text" name="lbitem_name[]" id="lbitmdes_1" autocomplete="off" class="form-control" placeholder="Labour Description">
												</td>
												<td width="7%">
													<select id="lbitmunt_1" class="form-control select2 lbline-unit" style="width:100%" name="lbunit_id[]"><option value="">Unit</option></select>
												</td>
												<td width="8%">
													<input type="number" id="lbitmqty_1" step="any" name="lbquantity[]" autocomplete="off" class="form-control lbline-quantity" placeholder="Hrs.">
												</td>
												<td width="8%">
													<input type="number" id="lbitmcst_1" step="any" name="lbcost[]" autocomplete="off" class="form-control lbline-cost" placeholder="Rate">
												</td>
												<td width="6%">
													<select id="lbtaxcode_1" class="form-control select2 lbtax-code" style="width:100%" name="lbtax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="lbtxincld_1" class="form-control select2 lbtaxinclude" style="width:100%" name="lbtax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="lbhidunit_1" name="lbhidunit[]" class="lbhidunt">
													<input type="text" id="lbvatdiv_1" step="any" readonly name="lbvatdiv[]" class="form-control lbvatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="lbvat_1" name="lbline_vat[]" class="form-control lbvat">
													<input type="hidden" id="lbvatlineamt_1" name="lbvatline_amt[]" class="form-control lbvatline-amt" value="0">
													<input type="hidden" id="lbitmdsnt_1" name="lbline_discount[]">
												</td>
												<td width="11%">
													<input type="number" id="lbitmttl_1" step="any" name="lbline_total[]" class="form-control lbline-total" readonly placeholder="Total">
													<input type="hidden" id="lbitmtot_1" name="lbitem_total[]">
												</td>
												<td width="1%">
													<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-lbitem" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
												</td>
											</tr>
										</table>
									</div>
								<?php } ?>
								</div>
								</fieldset>
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" value="{{old('total')}}" name="total" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" value="{{old('total_fc')}}" name="total_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Discount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount" name="discount" value="{{old('discount')}}" class="form-control spl discount-cal" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" value="{{old('discount_fc')}}" class="form-control spl" placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Subtotal<span id="subttle" class="small"></span></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" id="subtotal" step="any" name="subtotal" value="{{old('subtotal')}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" id="subtotal_fc" step="any" name="subtotal_fc" value="{{old('subtotal_fc')}}" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat" name="vat" value="{{old('vat')}}" class="form-control spl" placeholder="0" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" value="{{old('vat_fc')}}" class="form-control spl" placeholder="0" readonly>
										</div>
									</div>
                                </div>
								
								
								
								<!--<input type="hidden" step="any" id="discount" name="discount" class="form-control" placeholder="0">
								<input type="hidden" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">-->
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" value="{{old('net_amount_hid')}}" class="form-control" readonly>
											<input type="number" step="any" id="net_amount" name="net_amount" value="{{old('net_amount')}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" value="{{old('net_amount_fc')}}" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<?php if(isset($formdata['advance']) && $formdata['advance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Advance</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control spl" id="advance" name="advance" placeholder="Advance" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="advance" id="advance">
								<?php } ?>
								
								<?php if(isset($formdata['balance']) && $formdata['balance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control spl" id="balance" readonly name="balance" placeholder="Balance" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="balance" id="balance">
								<?php } ?>
								
								<hr/>
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>-->
								<input type="hidden" name="footer_id" id="footer_id">
								<?php if(isset($formdata['rv_entry']) && $formdata['rv_entry']==1) { ?>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Receipt Voucher Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="rv_icheck" id="is_rv" name="is_rv" value="1">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="is_rv" id="is_rv">
								<?php } ?>
								
								<div id="rv_form">
								<table border="0" class="table-dy-row">
									<tr>
										<td width="10%">
											<input type="hidden" id="rv_voucher" name="rv_voucher" value="{{$rvid}}"/>
											<span class="small">Voucher Type</span>
											<select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="CASH">Cash</option>
											<option value="BANK">Bank</option>
											<option value="PDCR">PDC</option>
										</select>
										</td>
										<td width="10%">
											<span class="small">RV Voucher No</span>
											<input type="text" name="rv_voucher_no" value="" id="rv_voucher_no" autocomplete="off" class="form-control">
										</td>
										<td width="20%">
											<span class="small">Debit Account</span>
											<input type="text" name="rv_dr_account" id="rv_dr_account" autocomplete="off" class="form-control" value="">
											<input type="hidden" id="rv_dr_account_id" name="rv_dr_account_id" value="">
										</td>
										<td width="15%">
											<span class="small">Amount</span> 
											<input type="number" id="rv_amount" step="any" name="rv_amount" class="form-control" placeholder="Amount">
										</td>
										<td width="15%">
											<span class="small pdcr">Cheque No</span>
											<input type="text" name="cheque_no" id="cheque_no" class="form-control" placeholder="Cheque No">
										</td>
										<td width="15%">
											<span class="small pdcr">Cheque Date</span>
											<input type="text" name="cheque_date" id="cheque_date" autocomplete="off" class="form-control" data-language='en' readonly placeholder="Cheque Date">
										</td>
										<td width="15%">
											<span class="small pdcr">Bank</span> 
											<select id="bank_id" class="form-control select2" style="width:100%" name="bank_id">
											<option value=""></option>
											</select>
										</td>
										
									</tr>
								</table>
								</div>
								
								<input type="hidden" name="footer" id="footer">
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('sales_invoice') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('sales_invoice/add') }}" class="btn btn-warning">Clear</a>
										@can('si-history')
										<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">View Order History</a>
										@endcan
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
							
							 <div id="newcustomer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Customer</h4>
                                        </div>
                                        <div class="modal-body" id="newcustomerData">
														
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
							
							<div id="item_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="item_data">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

							<div id="lbitem_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="lbitem_data">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 							
							
							<div id="vehicle_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Vehicle</h4>
                                        </div> <input type="hidden" id="cust_id">
                                        <div class="modal-body" id="vehicleData">
                                            Please select a Customer first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Order History</h4>
                                        </div>
                                        <div class="modal-body" id="historyData">Please select a Customer first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="custhistory_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Customer History</h4>
                                        </div>
                                        <div class="modal-body" id="custhistoryData">Please select a Customer first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="custphonehistory_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Customer History</h4>
                                        </div>
                                        <div class="modal-body" id="custphonehistoryData">Please select a phone no first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            </form>
							</div>
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
"use strict";
$('#optHide').toggle(); var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>; var packing = 1; //VAT CHNG
$('#saleloc').toggle();
$(document).ready(function () { 
	
	$('.descdivPrnt').find('.btn-remove-desc-com').hide();
	$("#fabData").hide()
	<?php if(!old('item_code')) { ?>
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	if ( $('.lbitemdivPrnt').children().length == 1 ) {
		$('.lbitemdivPrnt').find('.btn-remove-lbitem').hide();
	}
	
	<?php } else { ?>
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	$('.lbitemdivPrnt').find('.btn-add-lbitem:not(:last)').hide();
	<?php } ?>
	
	<?php if( sizeof($vouchers) > 0 && $vouchers[0]->is_cash_voucher==1) { ?> //cash customer.... 
		$('#customer_name').removeAttr("data-toggle");
		if( $('#newcustomerInfo').is(":hidden") )
			$('#newcustomerInfo').toggle();
		$('#customer_id').val({{$vouchers[0]->default_account_id}});
		$('#dr_account_id').val({{$vouchers[0]->default_account_id}});
		$('#customer_name').val('{{$vouchers[0]->default_account}}');
		if( $('#customerInfo').is(":visible") )
			$('#customerInfo').toggle();
	<?php } else { ?>
		$('#customer_name').attr("data-toggle", "modal");
		if( $('#customerInfo').is(":hidden") ) 
			$('#customerInfo').toggle();
		$('#customer_id').val('');
		$('#dr_account_id').val('');
		$('#customer_name').val('');
		if( $('#newcustomerInfo').is(":visible") )
			$('#newcustomerInfo').toggle();
	<?php } ?>
	
	$('#rv_form').toggle(); $('#cheque_no').hide(); $('#cheque_date').hide(); $('#bank_id').hide(); $('.pdcr').hide();
	$('#trninfo').toggle();
	$("#currency_id").prop('disabled', true);
	$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $("#subtotal_fc").toggle();
	$('.descdivPrntItm').toggle();$('.locPrntItm').toggle();
	var urlvchr = "{{ url('sales_invoice/checkvchrno/') }}";
	var urlcode = "{{ url('sales_invoice/checkrefno/') }}";
    $('#frmSalesInvoice').bootstrapValidator({
        fields: {
			reference_no: {
                validators: {
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val()
                            };
                        },
                        message: 'This Reference No. is already exist!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmSalesInvoice').data('bootstrapValidator').resetForm();
    });
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	
	$('.export').on('ifChecked', function(event){ 
		$('.tax-code').val('ZR');
		$('.vat').val(0);
		$('.vatdiv').val('0%');
		$('.tax-code').attr("disabled", true); 
		getNetTotal();
	});
	
	$('.export').on('ifUnchecked', function(event){
		$('.tax-code').val('SR');
		$('.vat').val(srvat);
		$('.vatdiv').val(srvat+'%');
		$('.tax-code').attr("disabled", false); 
		getNetTotal();
	});
	
	$('.rv_icheck').on('ifChecked', function(event){ 
		$('#rv_form').toggle(); $('#rv_amount').val( parseFloat( ($('#net_amount').val()=='') ? '' : $('#net_amount').val() ) );
		
	});
	
	$('.rv_icheck').on('ifUnchecked', function(event){ 
		$('#rv_form').toggle();
		
	});
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		
		var lineTotal = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		
		$( '.lbline-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotalLb(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		
		$('#total').val((lineTotal).toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
		
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		
		$( '.lbvatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		
		$('#vat').val((vatcur).toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total + vat;
		$('#net_amount').val(netTotal.toFixed(2));
		$('#net_amount_hid').val(netTotal+discount);
		
		if( $('#is_fc').is(":checked") ) { 
			var rate       = parseFloat($('#currency_rate').val());
			var vat        = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
			var fcTotal    = total * rate;
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var fcTax = vat * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax);
			$('#net_amount_fc').val(fcNetTotal.toFixed(2));
		}
		
		if(netTotal!='')
			calculateDiscount();
	}
	
		
	//calculation item line total and discount...
	function getLineTotal(n) {
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //AG23
		
		if($('#txincld_'+n+' option:selected').val()==1 ) {
			
			var ln_total	 = lineQuantity * lineCost;
			var taxLineCost  = (ln_total * lineVat) / parseFloat(100+lineVat);
			var lineTotal 	 = ln_total;
			var lineTotalTx  = ( ln_total - taxLineCost );
			
		} else {
			
			var lineTax 	 = (lineCost * lineVat) / 100;
			var taxLineCost	 = lineQuantity * lineTax;
			var lineTotal 	 = lineQuantity * lineCost;
			var lineTotalTx 	 = lineQuantity * lineCost;
		}
		
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2)); //val(lineVat+'% - '+taxLineCost.toFixed(2));
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost.toFixed(2));//(taxLineCost + vatcur).toFixed(2)
		$('#vatlineamt_'+n).val(taxLineCost.toFixed(2));
		$('#itmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	} 
	
	function getLineTotalLb(n) {
		//console.log('tax2: '+vatcur);
		
		var lineQuantity = parseFloat( ($('#lbitmqty_'+n).val()=='') ? 0 : $('#lbitmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#lbitmcst_'+n).val()=='') ? 0 : $('#lbitmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#lbvat_'+n).val()=='') ? 0 : $('#lbvat_'+n).val() );
		
		if($('#lbtxincld_'+n+' option:selected').val()==1 ) {
			
			var ln_total	 = lineQuantity * lineCost;
			var taxLineCost  = (ln_total * lineVat) / parseFloat(100+lineVat);
			var lineTotal 	 = ln_total;
			var lineTotalTx  = ( ln_total - taxLineCost );
			
		} else {
			
			var lineTax 	 = (lineCost * lineVat) / 100;
			var taxLineCost	 = lineQuantity * lineTax;
			var lineTotal 	 = lineQuantity * lineCost;
			var lineTotalTx 	 = lineQuantity * lineCost;
		}
		
		$('#lbvatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2));
		$('#lbitmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost.toFixed(2));
		$('#lbvatlineamt_'+n).val(taxLineCost.toFixed(2));
		$('#lbitmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	}
	
	function getAutoPrice(curNum) {
		
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var customer_id = $('#customer_id').val();
		var crate = $('#currency_rate').val();
		if($('#sales_type option:selected').val()=='normal') {
			$.ajax({
				url: "{{ url('itemmaster/get_sale_cost/') }}", 
				type: 'get',
				//data: 'item_id='+item_id+'&unit_id='+unit_id+'&customer_id='+customer_id,
				data: 'item_id='+item_id+'&customer_id='+customer_id+'&crate='+crate,
				success: function(data) {
					$('#itmcst_'+curNum).val( (data==0)?'':data );
					$('#itmcst_'+curNum).focus();
					return true;
				}
			}); 
			getLineTotal(curNum);
		} else {
			$.ajax({
				url: "{{ url('itemmaster/get_cost_sale/') }}", //get_cost_avg
				type: 'get',
				data: 'item_id='+item_id,
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#itmcst_'+curNum).val((data==0)?'':data);
					$('#itmcst_'+curNum).focus();
					return true;
				}
			}); 
		}
	}
	
	function calculateTaxInclude(curNum)
	{
	
		var ln_total = parseFloat( $('#itmqty_'+curNum).val()==''?0:$('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val()==''?0:$('#itmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 : $('#vat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total - vat_amt;
		$('#itmttl_'+curNum).val( ln_total.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() ) - vat_amt).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) - vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) - vat_amt).toFixed(2) );
		$('#itmtot_'+curNum).val( ln_total_amt.toFixed(2) );
	}
	
	//SEP25
	function calculateTaxIncludeLb(curNum)
	{
	
		var ln_total = parseFloat( $('#lbitmqty_'+curNum).val()==''?0:$('#lbitmqty_'+curNum).val() ) * parseFloat( $('#lbitmcst_'+curNum).val()==''?0:$('#lbitmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#lbvat_'+curNum).val()=='') ? 0 : $('#lbvat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total - vat_amt;
		$('#lbitmttl_'+curNum).val( ln_total.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() ) - vat_amt).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) - vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) - vat_amt).toFixed(2) );
		$('#lbitmtot_'+curNum).val( ln_total_amt.toFixed(2) );
	}
	
	function calculateTaxExclude(curNum)
	{
		//console.log(curNum);
		var ln_total = parseFloat( $('#itmqty_'+curNum).val()==''?0:$('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val()==''?0:$('#itmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 : $('#vat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total + vat_amt;
		$('#itmttl_'+curNum).val( ln_total_amt.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() )).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) + vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) + vat_amt).toFixed(2) );
		$('#itmtot_'+curNum).val( ln_total_amt.toFixed(2) );
	}
	
	//SEP25
	function calculateTaxExcludeLb(curNum)
	{
		//console.log(curNum);
		var ln_total = parseFloat( $('#lbitmqty_'+curNum).val()==''?0:$('#lbitmqty_'+curNum).val() ) * parseFloat( $('#lbitmcst_'+curNum).val()==''?0:$('#lbitmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#lbvat_'+curNum).val()=='') ? 0 : $('#lbvat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total + vat_amt;
		$('#lbitmttl_'+curNum).val( ln_total_amt.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() )).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) + vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) + vat_amt).toFixed(2) );
		$('#lbitmtot_'+curNum).val( ln_total_amt.toFixed(2) );
	}
	
	function calculateDiscount()
	{ 
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var lineTotal = parseFloat( ($('#total').val()=='') ? 0 : $('#total').val() );
		var vatTotal = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		
			var subtotal = lineTotal - discount;
			
			var amountTotal = 0; var discountAmt; var vatLine = 0; var vatnet = 0; var amountNet = 0; var total; var taxinclude = false;
			
			$( '.line-total' ).each(function() {
				  var res = this.id.split('_');
				  var curNum = res[1];
				  var vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 :  $('#vat_'+curNum).val() );
				  
				  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
					  var vatPlus = parseFloat(100 + vat);
					  total = parseFloat( $('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val() );
				  } else {
					 var vatPlus = 100;
					 total = this.value;
				  }
				  
				  discountAmt = (total / lineTotal) * discount;
				  amountTotal = total - discountAmt;
				  vatLine = (amountTotal * vat) / vatPlus;
				  amountNet = amountNet + amountTotal;
				  vatLine = parseFloat(vatLine.toFixed(2));
				  
				  vatnet = parseFloat(vatnet + vatLine); 
				  $('#vatdiv_'+curNum).val(vat+'% - '+vatLine);//.toFixed(2)
				  $('#vatlineamt_'+curNum).val(vatLine);//.toFixed(2)
				  $('#itmdsnt_'+curNum).val(discountAmt.toFixed(2));
				  
				  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
					  taxinclude = true;
					  vatnet = (subtotal * vat)/vatPlus;
				  }
			  
			});
			
			
			$( '.lbline-total' ).each(function() {
				  var res = this.id.split('_');
				  var curNum = res[1];
				  var vat = parseFloat( ($('#lbvat_'+curNum).val()=='') ? 0 :  $('#lbvat_'+curNum).val() );
				  
				  if($('#lbtxincld_'+curNum+' option:selected').val()==1 ) {
					  var vatPlus = parseFloat(100 + vat);
					  total = parseFloat( $('#lbitmqty_'+curNum).val() ) * parseFloat( $('#lbitmcst_'+curNum).val() );
				  } else {
					 var vatPlus = 100;
					 total = this.value;
				  }
				  
				  discountAmt = (total / lineTotal) * discount;
				  amountTotal = total - discountAmt;
				  vatLine = (amountTotal * vat) / vatPlus;
				  amountNet = amountNet + amountTotal;
				  vatLine = parseFloat(vatLine.toFixed(2));
				  
				  vatnet = parseFloat(vatnet + vatLine); 
				  $('#lbvatdiv_'+curNum).val(vat+'% - '+vatLine);//.toFixed(2)
				  $('#lbvatlineamt_'+curNum).val(vatLine);//.toFixed(2)
				  $('#lbitmdsnt_'+curNum).val(discountAmt.toFixed(2));
				  
				  if($('#lbtxincld_'+curNum+' option:selected').val()==1 ) {
					  taxinclude = true;
					  vatnet = (subtotal * vat)/vatPlus;
				  }
			  
			});
			
			$('#subtotal').val(subtotal.toFixed(2));
			$('#vat').val(vatnet.toFixed(2));
			
			if(taxinclude==true && discount==0) {
				$('#subtotal').val( (subtotal - vatnet).toFixed(2) );
				$('#subttle').html('(VAT Exclude)');
				$('#net_amount').val( subtotal.toFixed(2) );
				
			} else if(taxinclude==true && discount>0) { 
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) ).toFixed(2) );
			} else {
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) + parseFloat($('#vat').val()) ).toFixed(2) );
			}
			
		
		return true;
	}
//
$(function() {	
	var rowNum = 1; var lbrowNum = 1; var descNum = 1;
	 $('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	$('.lpo_date1').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			minDate: new Date('{{$settings->from_date}}'),
			maxDate: new Date('{{$settings->to_date}}'),
			autoclose: 1
	});
	
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	//item more info view section
	$(document).on('click', '.more-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_info/') }}/"+item_id;
		   $('#itemData_'+curNum).load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#infodivPrntItm_'+curNum).toggle();
	   }
    });
	 
	 //description info view section
	$(document).on('click', '.desc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   $('#descdivPrntItm_'+curNum).toggle();
    });
	
		 
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); //new change
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			$('#locData_'+rowNum).html('');
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//NW CHNG
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('.taxinclude')).attr('id', 'txincld_' + rowNum);//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			newEntry.find($('.descdivPrntItm')).attr('id', 'descdivPrntItm_' + rowNum);
			newEntry.find($('.desc-info')).attr('id', 'descinfoItm_' + rowNum);
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.txt-desc')).attr('name', 'itemdesc['+indx+'][]');
			$('#descdivPrntItm_'+rowNum+' .descdivChldItm').slice(1).remove();
			
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			newEntry.find($('input[name="costavg[]"]')).attr('id', 'costavg_' + rowNum);
			newEntry.find($('input[name="purcost[]"]')).attr('id', 'purcost_' + rowNum);
			
			//VAT CHNG
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
			$('#packing_'+rowNum).val(1);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.export').is(":checked") ) { 
				$('.tax-code').val('ZR');
			}
	
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
			
			var src = "{{ url('itemmaster/ajax_search/') }}";
			newEntry.find($('input[name="item_code[]"]')).autocomplete({
					
					source: function(request, response) {
						$.ajax({
							url: src+'/C',
							dataType: "json",
							data: {
								term : request.term
							},
							success: function(data) {
								response(data); 
							}
						});
					},
					select: function (event, ui) {
						var itm_id = ui.item.id;
						$("#itmdes_"+rowNum).val(ui.item.name);
						$('#itmcod_'+rowNum).val( ui.item.value );
						$('#itmid_'+rowNum).val( itm_id );
						
						if($('.export').is(":checked") ) { 
							$('#vatdiv_'+rowNum).val( '0%' );
							$('#vat_'+rowNum).val( 0 );
						} else {
							srvat = ui.item.vat;
							$('#vatdiv_'+rowNum).val( srvat+'%' );
							$('#vat_'+rowNum).val( srvat );
						}
						
						$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
							$('#itmunt_'+rowNum).find('option').remove().end();
							$.each(data, function(key, value) {   
							$('#itmunt_'+rowNum).find('option').end()
							 .append($("<option></option>")
										.attr("value",value.id)
										.text(value.unit_name)); 
							});
						});
					},
					minLength: 1,
				});
			
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
			//ROWCHNG
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('#itmcod_'+rowNum).focus();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		
		getNetTotal();
		
		//ROWCHNG
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	//Description and comment entry..........
	$(document).on('click', '.btn-add-desc-com', function(e)  { 
        descNum++;
		e.preventDefault();
        var controlForm = $('.controls .descdivPrnt'),
            currentEntry = $(this).parents('.descdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			newEntry.find($('.desclbl')).attr('id', 'lblid_' + descNum);
			newEntry.find($('#lblid_'+descNum).text('Description '+descNum));
			newEntry.find($('input[name="opr_description[]"]')).attr('id', 'oprdescid_' + descNum);
			newEntry.find($('input[name="opr_comment[]"]')).attr('id', 'oprcmntid_' + descNum);
			
			controlForm.find('.btn-add-desc-com:not(:last)').hide();
			controlForm.find('.btn-remove-desc-com').show();
			
	}).on('click', '.btn-remove-desc-com', function(e) { 
		$(this).parents('.descdivChld:first').remove();
		
		$('.descdivPrnt').find('.descdivChld:last').find('.btn-add-desc-com').show();
		if ( $('.descdivPrnt').children().length == 1 ) {
			$('.descdivPrnt').find('.btn-remove-desc-com').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	$(document).on('click', '.btn-add-lbitem', function(e)  { 
        lbrowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .lbitemdivPrnt'),
            currentEntry = $(this).parents('.lbitemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.lbline-unit')).attr('id', 'lbitmunt_' + lbrowNum);
			newEntry.find($('input[name="lbitem_id[]"]')).attr('id', 'lbitmid_' + lbrowNum);
			newEntry.find($('input[name="lbitem_code[]"]')).attr('id', 'lbitmcod_' + lbrowNum);
			newEntry.find($('input[name="lbitem_name[]"]')).attr('id', 'lbitmdes_' + lbrowNum);
			newEntry.find($('input[name="lbunit[]"]')).attr('id', 'lbitmunt_' + lbrowNum);
			newEntry.find($('input[name="lbquantity[]"]')).attr('id', 'lbitmqty_' + lbrowNum);
			newEntry.find($('input[name="lbcost[]"]')).attr('id', 'lbitmcst_' + lbrowNum);
			newEntry.find($('input[name="lbline_vat[]"]')).attr('id', 'lbvat_' + lbrowNum);
			newEntry.find($('input[name="lbvatline_amt[]"]')).attr('id', 'lbvatlineamt_' + lbrowNum); 
			newEntry.find($('input[name="lbline_discount[]"]')).attr('id', 'lbitmdsnt_' + lbrowNum);
			newEntry.find($('input[name="lbline_total[]"]')).attr('id', 'lbitmttl_' + lbrowNum);
			newEntry.find($('input[name="lbvatdiv[]"]')).attr('id', 'lbvatdiv_' + lbrowNum); 
			newEntry.find($('.lbinfodivPrntItm')).attr('id', 'lbinfodivPrntItm_' + lbrowNum);
			
			newEntry.find('input').val(''); 
			newEntry.find('.lbline-unit').find('option').remove().end().append('<option value="">Unit</option>');
			newEntry.find($('.lbtax-code')).attr('id', 'lbtaxcode_' + lbrowNum);
			newEntry.find($('.lbtaxinclude')).attr('id', 'lbtxincld_' + lbrowNum);
			newEntry.find($('input[name="lbitem_total[]"]')).attr('id', 'lbitmtot_' + lbrowNum);
			
			newEntry.find($('.lbhidunt')).attr('id', 'lbhidunit_' + lbrowNum);
			
			if($('.lbtaxinclude option:selected').val()==0 )
				$('.lbtaxinclude').val(0);
			else
				$('.lbtaxinclude').val(1);
			
			if($('.export').is(":checked") ) { 
				$('.lbtax-code').val('ZR');
			}
	
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
			//ROWCHNG
			controlForm.find('.btn-add-lbtem:not(:last)').hide();
			controlForm.find('.btn-remove-lbitem').show();
			
			$('#lbitmcod_'+lbrowNum).focus();
			
    }).on('click', '.btn-remove-lbitem', function(e)
    { 
		//new change..
		$(this).parents('.lbitemdivChld:first').remove();
		
		getNetTotal();
		
		//ROWCHNG
		$('.lbitemdivPrnt').find('.lbitemdivChld:last').find('.btn-add-lbitem').show();
		if ( $('.lbitemdivPrnt').children().length == 1 ) {
			$('.lbitemdivPrnt').find('.btn-remove-lbitem').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	$(document).on('click', '.btn-add-info', function(e) 
    { 
        e.preventDefault();

        var controlForm = $('.controls .infodivPrnt'),
            currentEntry = $(this).parents('.infodivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			controlForm.find('.btn-add-info:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-info').addClass('btn-remove-info')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove ');
    }).on('click', '.btn-remove-info', function(e)
    { 
		$(this).parents('.infodivChld:first').remove();

		e.preventDefault();
		return false;
	});
	
	
	$(document).on('click', '.btn-add-desc', function(e) 
    { 
        e.preventDefault();

        var controlForm = $(this).parents('.controls .descdivPrntItm:last'),
            currentEntry = $(this).parents('.descdivChldItm:last'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
    }).on('click', '.btn-remove-desc', function(e)
    { 
		$(this).parents('.descdivChldItm:first').remove();

		e.preventDefault();
		return false;
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			/* if( $('#customerInfo').is(":hidden") ) 
				$('#customerInfo').toggle(); */
		});
	});
	
	var newcusturl = "{{ url('sales_order/newcustomer_data/') }}";
	$('#customername').click(function() { 
		$('#newcustomerData').load(newcusturl, function(result) {
			$('#myModal').modal({show:true});
			
		});
	});
	
	$('#sales_type').on('change', function(e){
		if(e.target.value=='ltol') {
			if( $('#saleloc').is(":hidden") )
					$('#saleloc').toggle();
		} else {
			if( $('#saleloc').is(":visible") )
					$('#saleloc').toggle();
		}
	});
	
	$('#sales_location').on('change', function(e){
		var loc_id = e.target.value;
		if(loc_id=='') {
			alert('Please select a location!');
			return false;
		}
		$.get("{{ url('sales_invoice/getsaleloc/') }}/" + loc_id, function(data) {
			if(data) {
				$('#customer_name').val(data.customer_name);
				$('#customer_id').val(data.account_id);
				$('#dr_account_id').val(data.account_id);
			} else {
				$('#customer_name').val('');
				$('#customer_id').val('');
				$('#dr_account_id').val('');

			}
		});
	});
	
	var vclurl = "{{ url('job_order/vehicle_data/') }}"
	$('#vehicle_name').click(function() { 
		$('#cust_id').val( $('#customer_id').val() );
		if(  $('#customer_id').val() != '') {
			$('#vehicleData').load(vclurl+'/'+$('#customer_id').val(), function(result) {
				$('#myModal').modal({show:true});
			});
		}
	});
	
	$(document).on('click', '.vclRow', function(e) {
		$('#vehicle_name').val($(this).attr("data-name"));
		$('#vehicle_id').val($(this).attr("data-id"));
		$('#vehicle_regno').val($(this).attr("data-regno"));
		$('#make').val($(this).attr("data-make"));
		$('#model').val($(this).attr("data-model"));
		$('#issue_plate').val($(this).attr("data-issue"));
		$('#code_plate').val($(this).attr("data-code"));
		e.preventDefault();
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('sales_invoice/getvoucher/') }}/" + vchr_id, function(data) {
			$('#voucher_no').val(data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			if(data.account_id!=null && data.account_name!=null) {
				$('#sales_account').val(data.account_id+'-'+data.account_name);
				$('#cr_account_id').val(data.id);
			} else {
				$('#sales_account').val('');
				$('#cr_account_id').val('');
			}
			
			if(data.cash_voucher==1) {
				if( $('#newcustomerInfo').is(":hidden") )
					$('#newcustomerInfo').toggle();
			
				if( $('#customerInfo').is(":visible") )
					$('#customerInfo').toggle();
				$('#customer_name').val(data.default_account);
				$('#customer_id').val(data.cash_account);
				$('#dr_account_id').val(data.cash_account);
				$('#customer_name').removeAttr("data-toggle");
			} else {
				if( $('#customerInfo').is(":hidden") ) 
					$('#customerInfo').toggle();
			
				if( $('#newcustomerInfo').is(":visible") )
					$('#newcustomerInfo').toggle();
				$('#customer_name').val('');
				$('#customer_id').val('');
				$('#dr_account_id').val('');
				$('#customer_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		}); 
		
	});
	
	$(document).on('click', '.custRow', function(e) { //console.log($(this).attr("data-trnno"));
	
		if( $('#newcustomerInfo').is(":hidden") ) {
			$('#customer_name').val($(this).attr("data-name"));
			$('#customer_id').val($(this).attr("data-id"));
			$('#dr_account_id').val($(this).attr("data-id"));
			$('#cr_balance').val($(this).attr("data-clbalance"));
			$('#cr_limit').val($(this).attr("data-crlimit"));
			$('#pdc').val($(this).attr("data-pdc"));
		}
		
		if( $('#newcustomerInfo').is(":visible") ) {
			$('#customername').val($(this).attr("data-name"));
		}
		
		var group_id = $(this).attr("data-groupid");
		var trnno = $(this).attr("data-trnno");
		
		if(trnno=='') {
			if(confirm('TRN No. is not updated, do you want to update now?')) {
				if( $('#trninfo').is(":hidden") )
					$('#trninfo').toggle();
			} else
				return false;
		} else {
			if( $('#trninfo').is(":visible") )
				$('#trninfo').toggle();
		}
		e.preventDefault();
	});
	
	$(document).on('click', '.newcustRow', function(e) { //console.log($(this).attr("data-trnno"));
		$('#customername').val($(this).attr("data-name"));
		$('#customer_trn').val($(this).attr("data-trnno"));
						
		e.preventDefault();
	});
	
	var supurl = "{{ url('sales_invoice/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; $('#item_data').html('');
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('click', 'input[name="lbitem_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#lbitem_data').load(itmurl+'/'+curNum+'/ser', function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="lbitem_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#lbitem_data').load(itmurl+'/'+curNum+'/ser', function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change.................
	$(document).on('click', '#item_data .itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		$('#costavg_'+num).val( $(this).attr("data-costavg") );
		$('#purcost_'+num).val( $(this).attr("data-purcost") );
		
		if($('.export').is(":checked") ) { 
			$('#vatdiv_'+num).val( '0%' );
			$('#vat_'+num).val( 0 );
			$('#itmcst_'+num).val( $(this).attr("data-cost") );//data-price AG23
		} else {
			$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#vat_'+num).val( $(this).attr("data-vat") );
			$('#itmcst_'+num).val( $(this).attr("data-cost") ); //data-price AG23
			srvat = $(this).attr("data-vat");
		}
		
		//AG23
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {  
			$('#itmunt_'+num).find('option').end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name)); 
			});
		});
		//getAutoPrice(num);
	});

	$(document).on('click', '#lbitem_data .itemRow', function(e) { 
		var num = $('#lbnum').val();
		$('#lbitmcod_'+num).val( $(this).attr("data-code") );
		$('#lbitmid_'+num).val( $(this).attr("data-id") );
		$('#lbitmdes_'+num).val( $(this).attr("data-name") );
		
		if($('.export').is(":checked") ) { 
			$('#lbvatdiv_'+num).val( '0%' );
			$('#lbvat_'+num).val( 0 );
			$('#lbitmcst_'+num).val( $(this).attr("data-sprice") );
		} else {
			$('#lbvatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#lbvat_'+num).val( $(this).attr("data-vat") );
			$('#lbitmcst_'+num).val( $(this).attr("data-sprice") );
			srvat = $(this).attr("data-vat");
		}
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
			$('#lbitmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#lbitmunt_'+num).find('option').end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name)); 
			});
		});
		//getAutoPrice(num);
	});
	
	var hdrurl = "{{ url('header_footer/header_data/') }}";
	$('#headermsg').click(function() {
		$('#hdr').load(hdrurl, function(result) {
			$('#hdrModal').modal({show:true});
		});
	});
	$(document).on('click', '.hrdcls', function(e) {
		$('#headermsg').val($(this).attr("data-name"));
		$('#header_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var ftrurl = "{{ url('header_footer/footer_data/') }}";
	$('#footermsg').click(function() {
		$('#ftr').load(ftrurl, function(result) {
			$('#ftrModal').modal({show:true});
		});
	});
	$(document).on('click', '.ftrcls', function(e) {
		$('#footermsg').val($(this).attr("data-name"));
		$('#footer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('keyup', '.lbline-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();
	});
	
	//updated mar 25...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			
			var con = confirm('Item quantity is zero. Do you want to continue with zero quantity?');
			if(con==false){
				$('#itmqty_'+curNum).val('');
				$('#itmqty_'+curNum).focus();
				return false;
			} else
				return true;

		} else {
			var itmid = $('#itmid_'+curNum).val();
			$.get("{{ url('itemmaster/checkqty/') }}/" + itmid, function(data) {  
				data = JSON.parse(data); console.log(data.cur_quantity+' '+data.min_quantity);
				var cur_quantity = parseFloat(data.cur_quantity);
				var min_quantity = parseFloat(data.min_quantity);
				<?php if($settings->item_quantity==1) { ?>
				if(cur_quantity == 0 || cur_quantity < 0) {
					alert('Item is out of stock!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
					
				} else if((min_quantity == cur_quantity) || (min_quantity > cur_quantity)) {
					alert('Item quantity is reached on minimun quantity!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
				}
				<?php } ?>
			});
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1];
		//var isPrice = getAutoPrice(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
		
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
		
		var is_itemprofit = {{$settings->item_profit}};
		if(is_itemprofit==1) {
			var per = {{$settings->profit_per}};
			//var costtype = {{$settings->cost_type}};
			var salecost = parseFloat( $('#{{$settings->cost_type}}_'+curNum).val() );//(costtype=='cost_avg')?parseFloat( $('#costavg_'+curNum).val() ):parseFloat( $('#purcost_'+curNum).val() );
			var p = parseFloat( (salecost * per)/100 ); console.log(salecost);
			if((salecost + p) > this.value)
				alert('This item price is lower profit percentage.');
				
		}
	});
	
	$(document).on('blur', '.lbline-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();
	});
	
	var ordhisurl = "{{ url('sales_invoice/order_history/') }}";
	$('.order-history').click(function() {
		var cus_id = $('#customer_id').val();
		$('#historyData').load(ordhisurl+'/'+cus_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	//new change...
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		var item_id = $('#itmid_'+curNum).val();
		if( $('#export').is(":checked") || ($('#taxcode_'+curNum+' option:selected').val()=='EX') ) {
			$('#vat_'+curNum).val(0);
			$('#vatdiv_'+curNum).val('0%');
		} else {
			var prc = parseFloat($('#itmcst_'+curNum).val());
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
				//VAT CHNG
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
				$('#itmcst_'+curNum).val(data.price); //AG23
				getNetTotal();
			});
		}
	});
	
	$('#document_type').on('change', function(e){
		var dpt_id = ($('#department_id').length)?$('#department_id option:selected').val():'';
		var vchr_id = $('#voucher_id').val();
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		var sales_ac = $('#sales_account').val();
		var ac_mstr = $('#dr_account_id').val();
		
		$.ajax({
			url: "{{ url('sales_invoice/set_session/') }}",
			type: 'post',
			data: {'vchr_no':vchr_no,'vchr_id':vchr_id,'ref_no':ref_no,'vchr_dt':vchr_dt,'lpo_dt':lpo_dt,'sales_ac':sales_ac,'ac_mstr':ac_mstr,'dpt_id':dpt_id},
			success: function(data) { //console.log(data);
			}
		}) 
	});
	
	//CHNG
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#sales_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	$(document).on('change', '.taxinclude', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1]; 
		
		if($('.taxinclude option:selected').val()==0 )
			$('.taxinclude').val(0);
		else
			$('.taxinclude').val(1);
			
		if(this.value==1)
			calculateTaxInclude(curNum);
		else
			calculateTaxExclude(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	//SPE25
	$(document).on('change', '.lbtaxinclude', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1]; 
		
		if($('.lbtaxinclude option:selected').val()==0 )
			$('.lbtaxinclude').val(0);
		else
			$('.lbtaxinclude').val(1);
			
		if(this.value==1)
			calculateTaxIncludeLb(curNum);
		else
			calculateTaxExcludeLb(curNum);
		
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1];
		if(this.value=='EX' || this.value=='ZR') {
			
			$('#vat_'+curNum).val(0);
			$('#vatdiv_'+curNum).val('0%');
		} else {
			$('#vat_'+curNum).val(srvat);
			$('#vatdiv_'+curNum).val(srvat+'%');
		}
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();	
	});
	
	//SEP25
	$(document).on('change', '.lbtax-code', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1];
		if(this.value=='EX' || this.value=='ZR') {
			
			$('#lbvat_'+curNum).val(0);
			$('#lbvatdiv_'+curNum).val('0%');
		} else {
			$('#lbvat_'+curNum).val(srvat);
			$('#lbvatdiv_'+curNum).val(srvat+'%');
		}
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();	
	});
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
	});
	
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+item_id+"/"+curNum
		   $('#locData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#locPrntItm_'+curNum).toggle();
	   }
    });
	
	
	$('input,select,checkbox').keydown( function(e) { 
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	var src = "{{ url('itemmaster/ajax_search/') }}";
	$('input[name="item_code[]"]').autocomplete({
		
        source: function(request, response) {
            $.ajax({
                url: src+'/C',
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
		select: function (event, ui) { 
			
			var itm_id = ui.item.id;
			$("#itmdes_1").val(ui.item.name);
			$('#itmcod_1').val( ui.item.value );
			$('#itmid_1').val( itm_id );
			
			if($('.export').is(":checked") ) { 
				$('#vatdiv_1').val( '0%' );
				$('#vat_1').val( 0 );
			} else {
				srvat = ui.item.vat;
				$('#vatdiv_1').val( srvat+'%' );
				$('#vat_1').val( srvat );
			}
			
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_1').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_1').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); 
				});
			});
		},
        minLength: 1,
		
    });
	
	$(document).on('blur', 'input[name="item_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		var code = this.value;
		if(code!='') {
		$.get("{{ url('itemmaster/item_load/') }}/" + code, function(data) { //console.log(data);
			$("#itmdes_"+curNum).val(data.description);
			$('#itmid_'+curNum).val( data.id );
			$('#vat_'+curNum).val(data.vat)
			$('#vatdiv_'+curNum).val(data.vat+'%');
			$('#costavg_'+curNum).val(data.cost_avg);
			$('#purcost_'+curNum).val(data.pur_cost);
			
			/* $('#itmunt_'+curNum).find('option').remove().end();
			$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",data.unit_id)
							.text(data.unit)); */
		});
		}
	});
	
	//Multiple Unit loading........
	$(document).on('blur', 'input[name="item_name[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		var itm_id = $('#itmid_'+curNum).val();
		if(itm_id !='') {
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_'+curNum).find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); 
							$('#hidunit_'+curNum).val(value.unit_name);
				});
			});
		}	
	});
	
	/*$('input[name="item_name[]"]').autocomplete({
		
        source: function(request, response) {
            $.ajax({
                url: src+'/N',
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			var itm_id = ui.item.id;
			$("#itmdes_1").val(ui.item.value);
			$('#itmcod_1').val( ui.item.name );
			$('#itmid_1').val( itm_id );
			
			if($('.export').is(":checked") ) { 
				$('#vatdiv_1').val( '0%' );
				$('#vat_1').val( 0 );
			} else {
				srvat = ui.item.vat;
				$('#vatdiv_1').val( srvat+'%' );
				$('#vat_1').val( srvat );
			}
			if(itm_id!='') {
				$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
					$('#itmunt_1').find('option').remove().end();
					$.each(data, function(key, value) {   
					$('#itmunt_1').find('option').end()
					 .append($("<option></option>")
								.attr("value",value.id)
								.text(value.unit_name)); 
								$('#hidunit_1').val(value.unit_name);
					});
				});
			}
		},
        minLength: 3,
    });*/
	
	
	///Customer search...
	$(document).on('focus', '#customer_trn', function(e) { 
		var name = $('#customername').val();
		$.get("{{ url('sales_invoice/get_trnno/') }}/" + name, function(data) { console.log(data);
			$('#customer_phone').val(data.customer_phone);
			$('#customer_trn').val(data.customer_trn);
		});
	});
	
	///Customer sales history...
	
	$('.cust-history').click(function() {
		var hisurl = "{{ url('sales_invoice/cust_history/') }}";
		var cus_name = $('#customername').val();
		var phone = $('#customer_phone').val();
		$('#custhistoryData').load(hisurl+'/'+cus_name, function(result) {
			$('#myModal').modal({show:true});
		});
		
		/* var ordhisurl = "{{ url('sales_invoice/order_history/') }}";
		var cus_id = $('#customer_id').val();
		$('#custhistoryData').load(ordhisurl+'/'+cus_id, function(result) {
			$('#myModal').modal({show:true});
		}); */
		
		
	});
	
	$('.cust-history-phone').click(function() {
		var phisurl = "{{ url('sales_invoice/cust_history_phone/') }}";
		var phone = $('#customer_phone').val();
		$('#custphonehistoryData').load(phisurl+'/'+phone, function(result) {
			$('#myModal').modal({show:true});
		});
		
	});
	
	///Customer search...
	var srccust = "{{ url('sales_invoice/ajax_customer/') }}";
	$('#customername').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: srccust,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) {
			var itm_id = ui.item.id;
			$("#customer_trn").val(ui.item.trnno);
			$('#customer_phone').val( ui.item.phone );
		},
        minLength: 2,
    });
	
	$(document).on('blur', '.vatdiv', function(e) { 
		getNetTotal();
	});
	
	///Customer search...
	var acmst = "{{ url('account_master/ajax_account/') }}";
	$('#customer_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: acmst,
                dataType: "json",
                data: {
                    term : request.term, category : 'CUSTOMER'
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			$("#customer_id").val(ui.item.id);
		},
        minLength: 2,
    });
	
	$(document).on('click', '.pur-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_purchase_info/') }}/"+item_id; 
		   $('#purchasehisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
	   }
    });
	
	$(document).on('click', '.sales-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_sales_info/') }}/"+item_id; 
		   $('#saleshisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
    });
	
	$('#jobnature').on('change', function(e){ //console.log('f'+this.value)
		if(this.value==1) {
			$('#fabData').toggle(); $('#vclData').toggle();
		} else {
			$('#vclData').toggle();$('#fabData').toggle();
		}
	});
	
	$('#voucher_type').on('change', function(e){
		var vchr = e.target.value; 
		var vchr_id = $('#rv_voucher').val();
		
		$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
			$('#rv_voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#rv_dr_account').val(data.account_name);
				$('#rv_dr_account_id').val(data.id);
			} else {
				$('#rv_dr_account').val('');
				$('#rv_dr_account_id').val('');
			}
		});
		
		if(vchr=='BANK' || vchr=='PDCR') {
			$('#cheque_no').show();$('#cheque_date').show();$('#bank_id').show();$('.pdcr').show();
		} else {
			$('#cheque_no').hide();$('#cheque_date').hide();$('#bank_id').hide();$('.pdcr').hide();
		}
	});
	
	$(document).on('blur', '#rv_amount', function(e) {
		var amount = this.value;
		var netamount = parseFloat( ($('#net_amount').val()=='') ? 0 : $('#net_amount').val() );
		if(netamount < amount) {
			alert('Amount should not greater than bill amount.');
			$('#rv_amount').val(netamount);
		}
		
	});
	
	$(document).on('keyup', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 ) {
			
			var con = confirm('Item cost is zero. Do you want to continue with zero cost?');
			if(con==false){
				$('#itmcst_'+curNum).val('');
				$('#itmcst_'+curNum).focus();
				return false;
			} else
				return true;
		}
	});
	
	///Customer phone no search...
	/* var srccust = "{{ url('sales_invoice/ajax_customer/') }}";
	$('#customer_phone').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: srccust,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) {
			var itm_id = ui.item.id;
			$("#customer_trn").val(ui.item.trnno);
			$('#customer_phone').val( ui.item.phone );
		},
        minLength: 2,
    }); */
	
	$(document).on('keyup', '#advance', function(e) {
		console.log(this.value);
		var netamt = parseFloat( ($('#net_amount').val()=='') ? 0 : $('#net_amount').val() );
		var bal = netamt - this.value;
		$('#balance').val(bal.toFixed(2));
	});
});

var popup;
function getItems(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_order/item_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return true;
}

function getDocument() { 
	if($("#customer_name").val()=='') {
		alert('Please select a customer first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var customer_id = $("#customer_id").val();
	var doc = $('#document_type option:selected').val();
	if(doc=='SQ')
		var pourl = "{{ url('job_estimate/get_quotation/') }}/"+customer_id+"/QSI";
	else if(doc=='SO')
		var pourl = "{{ url('job_order/get_order/') }}/"+customer_id+"/SOI";
	else if(doc=='CDO')
		var pourl = "{{ url('customers_do/get_order/') }}/"+customer_id+"/CDO";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/sales";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function openWin(id) {
  myWindow = window.open("{{url('sales_invoice/print/')}}/"+id, "", "width=400, height=477");
}

</script>
@stop
