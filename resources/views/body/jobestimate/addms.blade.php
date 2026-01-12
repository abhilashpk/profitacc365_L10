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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
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
                Job Estimate
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Job Estimate</a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
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
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Estimate
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('qs-print')
								 <a href="{{ url('job_estimate/print/'.$printid->id) }}" target="_blank" class="btn btn-info btn-sm">
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
                            <form class="form-horizontal" role="form" method="POST" autocomplete="on" name="frmQuotationSales" id="frmQuotationSales" action="{{ url('job_estimate/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Estimate No.</label>
                                    <div class="col-sm-10">
										<?php if($voucherno->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$voucherno->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
										</div>
										<?php } else { ?>
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
										<?php } ?>
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" autocomplete="off" name="reference_no" value="{{ old('reference_no') }}" placeholder="Reference No.">
										
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Estimate Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>">Customer</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" value="{{ old('customer_name') }}" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
										<input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
									</div>
                                </div>
								
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
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="vehicle_name" id="vehicle_name" value="<?php echo (isset($vehicledata))?$vehicledata->name:''?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#vehicle_modal" placeholder="Vehicle">
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
                                        <input type="text" name="salesman" id="salesman" class="form-control" value="{{ old('salesman') }}" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Technician">
										<input type="hidden" name="salesman_id" id="salesman_id" value="{{ old('salesman_id') }}">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								<?php if($formdata['subject']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Subject</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="subject" id="subject">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" autocomplete="on" name="description" value="{{ old('description') }}" placeholder="Description">
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
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}" <?php if($job['id']==old('job_id')) echo 'selected'; ?>>{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
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
										foreach(old('item_code') as $product) { $j = $i+1;?>
											<div class="itemdivChld">
												<table border="0" class="table-dy-row">
													<tr>
														<td width="16%">
															<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
															<input type="text" id="itmcod_{{$j}}" name="item_code[]" value="{{ $product }}" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
														</td>
														<td width="29%">
															<input type="text" value="{{ old('item_name')[$i]}}" name="item_name[]" id="itmdes_{{$j}}" autocomplete="off" class="form-control <?php if($errors->has('item_name.'.$i)) echo 'form-error';?>" placeholder="Item Description">
														</td>
														<td width="7%">
															<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
														</td>
														<td width="8%">
															<input type="number" value="{{ old('quantity')[$i]}}" id="itmqty_{{$j}}" step="any" name="quantity[]" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
														</td>
														<td width="8%">
															<input type="number" id="itmcst_{{$j}}" value="{{ old('cost')[$i]}}" step="any" name="cost[]" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
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
															<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amt" value="{{ old('vatdiv')[$i]}}"> 
															<input type="hidden" id="vat_{{$j}}" name="line_vat[]" class="form-control vat" value="{{ old('line_vat')[$i]}}">
															<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{ old('vatline_amt')[$i]}}">
															<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" value="{{ old('line_discount')[$i]}}">
														</td>
														<td width="11%">
															<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('item_total')[$i]}}">
															<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" value="{{ old('line_total')[$i]}}" class="form-control line-total" readonly placeholder="Total">
														</td>
														<td width="1%">
															<?php if(count(old('item_code'))==$j) { ?>
																<button type="button" class="btn-success btn-add-item" >
																	<i class="fa fa-fw fa-plus-square"></i>
																 </button>
																<?php } ?>
																 <button type="button" class="btn-danger btn-remove-item" >
																	<i class="fa fa-fw fa-minus-square"></i>
																 </button>
														</td>
													</tr>
												</table>
												
												<div id="moreinfo" style="float:left; padding-right:5px;">
													<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
												</div>
												<div id="moredesc" style="float:left; padding-right:5px;">
													<button type="button" id="descinfoItm_{{$j}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
												</div>
												
												<div style="float:left; padding-right:5px;">
													<button type="button" id="purhisItm_{{$j}}" class="btn btn-primary btn-xs pur-his">Purchse</button>
												</div>
												
												<div style="float:left;">
													<button type="button" id="saleshisItm_{{$j}}" class="btn btn-primary btn-xs sales-his">Sales</button>
												</div>
												
												<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
													<div class="infodivChldItm">							
														<div class="table-responsive item-data" id="itemData_{{$j}}">
														</div>
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
													<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
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
												</td>
												<td width="11%">
													<input type="hidden" id="itmtot_1" name="item_total[]">
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" value="{{old('total')}}" step="any" name="total" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" value="{{old('total_fc')}}" step="any" name="total_fc" class="form-control spl" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" value="{{old('dicount')}}" class="form-control spl discount-cal" placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" value="{{old('vat')}}" readonly class="form-control spl" placeholder="0">
										</div>
										<div class="col-xs-2"> 
											<input type="number" step="any" id="vat_fc" name="vat_fc" value="{{old('vat_fc')}}" class="form-control spl" placeholder="0">
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
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Header</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="header_id" id="header_id">
                                        <input type="text" class="form-control" id="headermsg" name="header" placeholder="Header" autocomplete="off" data-toggle="modal" data-target="#header_modal">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								
								<div id="showmenu">
									<button type="button" id="infoadd" class="btn btn-primary btn-xs">Add Info..</button>
								</div>
								<div class="infodivPrnt">
									<div class="infodivChld">							
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Additional Info</label>
										   
											<div class="col-xs-10">
												<div class="col-xs-5">
													<input type="text" name="title[]" class="form-control" placeholder="Title">
												</div>
												<div class="col-xs-5">
													<input type="text" name="desc[]" class="form-control" placeholder="Description">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-info" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add more
													 </button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('job_estimate') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('job_estimate/add') }}" class="btn btn-warning">Clear</a>
										@can('qs-history')
										<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">View Order History</a>
										@endcan
                                    </div>
                                </div>
								
							<div id="purchase_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Purchase History</h4>
                                        </div>
                                        <div class="modal-body" id="purchasehisData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="sales_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Sales History</h4>
                                        </div>
                                        <div class="modal-body" id="saleshisData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
							
							<div id="header_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Header</h4>
                                        </div>
                                        <div class="modal-body" id="hdr">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="footer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Footer</h4>
                                        </div>
                                        <div class="modal-body" id="ftr">
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
<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<script>
"use strict";
var srvat=<?php echo ($vatdata)?$vatdata->percentage:0;?>; var packing = 1; var cid;
$(document).ready(function () { 
	//ROWCHNG
	<?php if(!old('item_code')) { ?>
	$('.btn-remove-item').hide();
	$('.btn-remove-lbitem').hide();
	<?php } ?>
	$("#fabData").hide()
	$('.descdivPrnt').find('.btn-remove-desc-com').hide();
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $("#subtotal_fc").toggle(); $('.descdivPrntItm').toggle();
	var urlcode = "{{ url('quotation_sales/checkrefno/') }}";
	var urlvchr = "{{ url('quotation_sales/checkvchrno/') }}"; //CHNG
    $('#frmQuotationSales').bootstrapValidator({
        fields: {
			
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
			
        }
        
    }).on('reset', function (event) {
        $('#frmQuotationSales').data('bootstrapValidator').resetForm();
    });
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle();$("#vat_fc").toggle(); $("#net_amount_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#vat_fc").toggle();$("#net_amount_fc").toggle();
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
		//console.log('tax2: '+vatcur);
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
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
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&customer_id='+customer_id+'&crate='+crate,
			success: function(data) {
				$('#itmcst_'+curNum).val((data==0)?'':data);
				return true;
			}
		}) 
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
	var rowNum = 1; var descNum = 1; var lbrowNum = 1;
	$('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
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
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.export').is(":checked") ) { 
				$('.tax-code').val('ZR');
			}
			
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			if( $('#descdivPrntItm_'+rowNum).is(":visible") ) 
				$('#descdivPrntItm_'+rowNum).toggle();
			
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
				
				newEntry.find($('input[name="item_name[]"]')).autocomplete({
					
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
						$("#itmdes_"+rowNum).val(ui.item.value);
						$('#itmcod_'+rowNum).val( ui.item.name );
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
					minLength: 3,
				});
			
			//ENTER KEY for TAB functionality in dynamic field..........
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
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
	
	$('#jobnature').on('change', function(e){
		if(this.value==1) {
			$('#fabData').toggle(); $('#vclData').toggle();
		} else {
			$('#vclData').toggle();$('#fabData').toggle();
		}
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
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ //.modal-body item
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ 
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', 'input[name="lbitem_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#lbitem_data').load(itmurl+'/'+curNum+'/ser', function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
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
		
		if($('.export').is(":checked") ) { 
			$('#vatdiv_'+num).val( '0%' );
			$('#vat_'+num).val( 0 );
			$('#itmcst_'+num).val( $(this).attr("data-cost") );
		} else {
			$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#vat_'+num).val( $(this).attr("data-vat") );
			$('#itmcst_'+num).val( $(this).attr("data-cost") );
			srvat = $(this).attr("data-vat");
		}
		
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
	
	
	var hisurl = "{{ url('sales_invoice/order_history/') }}";
	$('.order-history').click(function() {
		var cus_id = $('#customer_id').val();
		$('#historyData').load(hisurl+'/'+cus_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";//quotation_sales
	$('#customer_name').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	var supurl = "{{ url('quotation_sales/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
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
	
	//updated mar 18...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false;
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		if($('#itmcst_'+curNum).val()=='')
			var isPrice = getAutoPrice(curNum);
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
	});
	
	$(document).on('keyup', '.lbline-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.lbline-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();
	});
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
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
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
				$('#vat_'+curNum).val(data);
				$('#vatdiv_'+curNum).val(data+'%');
				srvat = data;
				
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
				$('#itmcst_'+curNum).val(data.price);
				getNetTotal();
			});
		}
		
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
	
	//SEP25
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
		
		var res = getLineTotalLb(curNum);
		if(res) 
			getNetTotal();	
	});
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
	});
	
	$('input,select').keydown( function(e) {
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
							.attr("value",key)
							.text(value)); 
				});
			});
		},
        minLength: 1,
    });
	
	$(document).on('blur', '.vatdiv', function(e) { 
		getNetTotal();
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
				
				/* $('#itmunt_'+curNum).find('option').remove().end();
				$('#itmunt_'+curNum).find('option').end()
					 .append($("<option></option>")
								.attr("value",data.unit_id)
								.text(data.unit)); */
								
			});
		}
	});
	
	$('input[name="item_name[]"]').autocomplete({
		
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
			
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_1').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_1').find('option').end()
				 .append($("<option></option>")
							.attr("value",key)
							.text(value)); 
					$('#hidunit_1').val(value);
				});
			});
		},
        minLength: 3,
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
							.attr("value",key)
							.text(value));  $('#hidunit_'+curNum).val(value);
				});
			});
		}	
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
	return false
}

</script>
@stop
