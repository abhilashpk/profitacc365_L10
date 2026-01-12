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
                Job Order
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Job Order</a>
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
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-<?php echo ($orderrow->is_editable==1)?'danger':'primary';?>">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> <?php if($orderrow->is_editable==1) { echo 'Non-Editable'; } else echo 'Edit Job Order';?>
                            </h3>
							
							<div class="pull-right">
							@can('so-print')
							 <a href="{{ url('job_order/print/'.$orderrow->id) }}" target="_blank" class="btn btn-info btn-sm">
								<span class="btn-label">
									<i class="fa fa-fw fa-print"></i>
								</span>
							 </a>
							@endcan
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
							<form class="form-horizontal" role="form" method="POST" name="frmSalesOrder" id="frmSalesOrder" action="{{ url('job_order/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="sales_order_id" id="sales_order_id" value="{{ $orderrow->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Order No.</label>
                                    <div class="col-sm-10">
										<?php if($orderrow->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$orderrow->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
											<input type="hidden" value="{{$orderrow->prefix}}" name="prefix">
										</div>
										<?php } else { ?>
											<input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
											<input type="hidden" value="{{$orderrow->prefix}}" name="prefix">
										<?php } ?>
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" value="<?php echo (old('reference_no'))?old('reference_no'):$orderrow->reference_no; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job Order Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" data-language='en' value="<?php echo ($orderrow->lpo_date=='0000-00-00')?'':date('d-m-Y',strtotime($orderrow->lpo_date)); ?>">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>">Customer</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" value="<?php echo (old('customer_name'))?old('customer_name'):$orderrow->customer; ?>">
										<input type="hidden" name="customer_id" id="customer_id" value="<?php echo (old('customer_id'))?old('customer_id'):$orderrow->customer_id; ?>">
									</div>
                                </div>
								
								<?php if($formdata['document_id']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Estimate#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="quotation_id" readonly name="quotation_id" placeholder="Quotation Id" autocomplete="off" onclick="getQuotation()">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="quotation_id" id="quotation_id">
								<?php } ?>
								
								<?php if($formdata['jobnature']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Job Nature</label>
                                    <div class="col-sm-10">
                                        <select id="jobnature" class="form-control select2" style="width:100%" name="jobnature">
											<option value="0" <?php if($orderrow->jobnature==0) echo 'selected';?>>Workshop</option>
											<option value="1" <?php if($orderrow->jobnature==1) echo 'selected';?>>Fabrication</option>
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
                                        <input type="text" class="form-control" id="fabrication" name="fabrication" value="{{$orderrow->fabrication}}" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="fabrication" id="fabrication">
								<?php } ?>
								
								<?php if($formdata['vehicle']==1) { ?>
								<div class="form-group" id="vclData">
                                    <label for="input-text" class="col-sm-2 control-label">Vehicle</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="vehicle_name" id="vehicle_name" value="<?php echo (old('vehicle_name'))?old('vehicle_name'):$orderrow->vehicle; ?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#vehicle_modal" placeholder="Vehicle">
										<input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo (old('vehicle_id'))?old('vehicle_id'):$orderrow->vehicle_id; ?>">
										<div class="col-xs-10" id="vehicleInfo">
											<div class="col-xs-2">
												<span class="small">Vehicle Reg.No.</span> <input type="text" id="vehicle_regno" name="vehicle_regno" value="<?php echo (old('vehicle_regno'))?old('vehicle_regno'):$orderrow->reg_no; ?>" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Issue Plate</span> <input type="text" id="issue_plate" name="issue_plate" value="<?php echo (old('issue_plate'))?old('issue_plate'):$orderrow->issue_plate; ?>" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Code Plate</span> <input type="text" id="code_plate" name="code_plate" value="<?php echo (old('code_plate'))?old('code_plate'):$orderrow->code_plate; ?>" readonly class="form-control">
											</div>
											<div class="col-xs-3">
												<span class="small">Make</span> <input type="text" id="make" name="make" value="<?php echo (old('make'))?old('make'):$orderrow->make; ?>" readonly class="form-control">
											</div>
											<div class="col-xs-3">
												<span class="small">Model</span> <input type="text" id="model" name="model" value="<?php echo (old('model'))?old('model'):$orderrow->model; ?>" readonly class="form-control">
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
											<?php foreach($jobtype as $row) { ?>
											<option value="{{$row->id}}" <?php if($orderrow->job_type==$row->id) echo 'selected'; ?>>{{$row->name}}</option>
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
                                        <input type="text" name="salesman" id="salesman" value="<?php echo (old('salesman'))?old('salesman'):$orderrow->salesman; ?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Technician">
										<input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (old('salesman_id'))?old('salesman_id'):$orderrow->salesman_id; ?>">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="<?php echo (old('description'))?old('description'):$orderrow->description; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<?php if($formdata['kilometer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Kilometer</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="kilometer" autocomplete="off" value="<?php echo (old('kilometer'))?old('kilometer'):$orderrow->kilometer; ?>" name="kilometer" placeholder="Kilometer">
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
											<option value="{{ $term['id'] }}" <?php if($term['id']==$orderrow->term_id) echo 'selected'; ?>>{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="terms_id" id="terms_id">
								<?php } ?>
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}" <?php if($job['id']==$orderrow->job_id) echo 'selected'; ?>>{{ $job['name'] }}</option>
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
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" <?php if($orderrow->is_fc==1) echo 'checked';?> value="1">
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}" <?php if($orderrow->currency_id==$curr['id']) echo 'selected'; ?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" value="{{$orderrow->currency_rate}}" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
								<?php } ?>
								
								<?php if($formdata['jobdescription']==1) { $descnum = count($jobdesc); ?>
								<div class="descdivPrnt">
								<input type="hidden" id="descNum" value="{{$descnum}}">
								<input type="hidden" id="remdesc" name="remove_desc">
									<?php 
									if($descnum > 0) {
									$i=1; foreach($jobdesc as $val) { ?>
									<div class="descdivChld">
										<div class="form-group">
											<input type="hidden" id="descid_{{$i}}" name="jobdetail_id[]" value="{{$val->id}}">
											<label for="input-text" class="col-sm-2 control-label desclbl" id="lblid_{{$i}}">Description {{$i}}</label>
											<div class="col-sm-6">
												<input type="text" class="form-control" id="oprdescid_{{$i}}" autocomplete="on" name="opr_description[]" value="{{$val->description}}" placeholder="Description">
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control" id="oprcmntid_{{$i}}" autocomplete="on" name="opr_comment[]" value="{{$val->comment}}" placeholder="Comments">
											</div>
											<div class="col-sm-1">
												<button type="button" class="btn-success btn-add-desc">
													<i class="fa fa-fw fa-plus-square"></i>
												</button>
												<button type="button" class="btn-danger btn-remove-desc" data-id="rem_{{$i}}">
													<i class="fa fa-fw fa-minus-square"></i>
												 </button>
											</div>
										</div>
									</div>
									<?php $i++; }  } else { ?>
										<div class="descdivChld">
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label desclbl" id="lblid_1">Description 1</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="oprdescid_1" autocomplete="off" name="opr_description[]" placeholder="Description">
												</div>
												<div class="col-sm-3">
													<input type="text" class="form-control" id="oprcmntid_1" autocomplete="off" name="opr_comment[]" placeholder="Comments">
												</div>
												<div class="col-sm-1">
													<button type="button" class="btn-success btn-add-desc">
														<i class="fa fa-fw fa-plus-square"></i>
													</button>
													<button type="button" class="btn-danger btn-remove-desc">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Export</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="export" id="export" name="is_export" <?php if($orderrow->is_export==1) echo 'checked';?> value="1">
											</label>
										</div>
									</div>
                                </div>
								
								<br/>
								<fieldset><?php $total = $vat_amount_net = $net_total = 0; ?>
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
								<?php if(count($orditems) > 0) { ?>
								{{--*/ $i = 0; $num = count($orditems); /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$j}}" value="{{ old('order_item_id')[$i]}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
													<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" value="{{$item}}" data-toggle="modal" data-target="#item_modal">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_{{$j}}" autocomplete="off" class="form-control" value="{{ old('item_name')[$i]}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]">
													<option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
													</select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_{{$j}}" name="quantity[]" step="any" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$j}}" step="any" name="cost[]" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{ old('cost')[$i]}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if(old('tax_code')[$i]=="SR") echo 'selected'; ?>>SR</option><option value="EX" <?php if(old('tax_code')[$i]=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if(old('tax_code')[$i]=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if(old('tax_include')[$i]==0) echo 'selected';?> value="0">No</option><option <?php if(old('tax_include')[$i]==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{ old('vatdiv')[$i]}}"> 
													<input type="hidden" id="vat_{{$j}}" name="line_vat[]" class="form-control vat" value="{{ old('line_vat')[$i]}}">
													<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{ old('vatline_amt')[$i]}}">
													<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{ old('line_total')[$i]}}">
													<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('item_total')[$i]}}">
												</td>
												<td width="1%">
													@if($num==$j)
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@else
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@endif
												</td>
											</tr>
										</table>
										
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
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
									</div>	
								<?php $i++; } } else { ?>
								
								@foreach($orditems as $item)
								{{--*/ $i++; /*--}}
								<?php 
									$total = 0;
									if($orderrow->is_fc==1) {
										 $unit_price = $item->unit_price / $orderrow->currency_rate;
										 $line_total = $item->line_total / $orderrow->currency_rate;
										 $vat_amount = round($item->vat_amount / $orderrow->currency_rate,2);
										 $total = $orderrow->total / $orderrow->currency_rate;
										 $vat_amount_net = $orderrow->vat_amount / $orderrow->currency_rate;
										 $net_total = round($orderrow->net_total / $orderrow->currency_rate,2);
									  } else {
										 $unit_price = $item->unit_price;
										 $line_total = $item->line_total;
										 $vat_amount = $item->vat_amount;
										 $total = $orderrow->total;
										 $vat_amount_net = $orderrow->vat_amount;
										 $net_total = $orderrow->net_total;
									  }
									
									?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
													<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" value="{{$item->item_code}}" data-toggle="modal" data-target="#item_modal">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" step="any" autocomplete="off" class="form-control line-quantity" value="{{$item->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" value="{{$unit_price}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$i}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if($item->tax_code=="SR") echo 'selected'; ?>>SR</option><option value="EX" <?php if($item->tax_code=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if($item->tax_code=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$i}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if($item->tax_include==0) echo 'selected';?> value="0">No</option><option <?php if($item->tax_include==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:$item->packing}}">
													<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{$item->vat.'% - '.intval($vat_amount*100)/100}}"><!--<div class="h5" id="vatdiv_{{$i}}"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control vat" value="{{$item->vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{intval($line_total*100)/100}}">
													<input type="hidden" id="itmtot_{{$i}}" name="item_total[]" value="{{$item->item_total}}">
												</td>
												<td width="1%">
													@if($num==$i)
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@else
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@endif
												</td>
											</tr>
										</table>
										
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$i}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$i}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$i}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
									</div>
								@endforeach
								</div>
								<?php } } else { ?>
									<input type="hidden" id="rowNum" value="1">
									<input type="hidden" id="remitem" name="remove_item">
									<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$j}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
													<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" value="{{ $item }}" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" value="{{ old('item_name')[$i]}}" id="itmdes_{{$j}}" autocomplete="off" class="form-control" placeholder="Item Description">
												</td>
												<td width="7%">
													<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_{{$j}}" step="any" value="{{ old('quantity')[$i]}}" name="quantity[]" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$j}}" value="{{ old('cost')[$i]}}" step="any" name="cost[]" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<span class="small">Tx.Inc.</span><br/>
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" value="{{ old('vatdiv')[$i]}}" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_{{$j}}" name="line_vat[]" value="{{ old('line_vat')[$i]}}" class="form-control vat">
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
										
										<hr/>
									</div>
								<?php $i++; } } else { ?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="item_id[]" id="itmid_1">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_1">
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
													<span class="small">Total</span> 
													<input type="hidden" id="itmtot_1" name="item_total[]">
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
												</td>
												<td width="1%">
													<br/><button type="button" class="btn-success btn-add-item" >
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
										
									</div>
								<?php } } ?>
								</fieldset>
								
								<fieldset style="margin-top:130px;">
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
								<?php if(count($seritems) > 0) { ?>
								{{--*/ $i = 0; $num = count($seritems); /*--}}
								<input type="hidden" id="lbrowNum" value="{{$num}}">
								<input type="hidden" id="lbremitem" name="lbremove_item">
								<?php if(old('lbitem_code')) { $i = 0; 
										foreach(old('lbitem_code') as $item) { $j = $i+1;?>
									<div class="lbitemdivChld">	
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="lborder_item_id[]" id="lbp_orditmid_{{$j}}" value="{{ old('lborder_item_id')[$i]}}">
													<input type="hidden" name="lbitem_id[]" id="lbitmid_{{$j}}" value="{{ old('lbitem_id')[$i]}}">
													<input type="text" id="lbitmcod_{{$j}}" name="lbitem_code[]" class="form-control <?php if($errors->has('lbitem_code.'.$i)) echo 'form-error';?>" autocomplete="off" value="{{$item}}" data-toggle="modal" data-target="#lbitem_modal">
												</td>
												<td width="29%">
													<input type="text" name="lbitem_name[]" id="lbitmdes_{{$j}}" autocomplete="off" class="form-control" value="{{ old('lbitem_name')[$i]}}">
												</td>
												<td width="7%">
													<select id="lbitmunt_{{$j}}" class="form-control select2 <?php if($errors->has('lbunit_id.'.$i)) echo 'form-error';?> lbline-unit" style="width:100%" name="lbunit_id[]">
													<option value="{{old('lbunit_id')[$i]}}">{{old('lbhidunit')[$i]}}</option></select>
													</select>
												</td>
												<td width="8%">
													<input type="number" id="lbitmqty_{{$j}}" autocomplete="off" name="lbquantity[]" step="any" class="form-control <?php if($errors->has('lbquantity.'.$i)) echo 'form-error';?> lbline-quantity" value="{{ old('lbquantity')[$i]}}">
												</td>
												<td width="8%">
													<input type="number" id="lbitmcst_{{$j}}" autocomplete="off" step="any" name="lbcost[]" class="form-control <?php if($errors->has('lbcost.'.$i)) echo 'form-error';?> lbline-cost" value="{{ old('lbcost')[$i]}}">
												</td>
												<td width="6%">
													<select id="lbtaxcode_{{$j}}" class="form-control select2 lbtax-code" style="width:100%" name="lbtax_code[]"><option value="SR" <?php if(old('lbtax_code')[$i]=="SR") echo 'selected'; ?>>SR</option><option value="EX" <?php if(old('lbtax_code')[$i]=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if(old('lbtax_code')[$i]=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="lbtxincld_{{$j}}" class="form-control select2 lbtaxinclude" style="width:100%" name="lbtax_include[]"><option <?php if(old('lbtax_include')[$i]==0) echo 'selected';?> value="0">No</option><option <?php if(old('lbtax_include')[$i]==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="text" id="lbvatdiv_{{$j}}" step="any" readonly name="lbvatdiv[]" class="form-control lbvatdiv" value="{{ old('lbvatdiv')[$i]}}">
													<input type="hidden" id="lbvat_{{$j}}" name="lbline_vat[]" class="form-control lbvat" value="{{ old('lbline_vat')[$i]}}">
													<input type="hidden" id="lbvatlineamt_{{$j}}" name="lbvatline_amt[]" class="form-control lbvatline-amt" value="{{ old('lbvatline_amt')[$i]}}">
													<input type="hidden" id="lbitmdsnt_{{$j}}" name="lbline_discount[]">
												</td>
												<td width="11%">
													<input type="number" id="lbitmttl_{{$j}}" step="any" name="lbline_total[]" class="form-control lbline-total" readonly value="{{ old('lbline_total')[$i]}}">
													<input type="hidden" id="lbitmtot_{{$j}}" name="lbitem_total[]" value="{{ old('lbitem_total')[$i]}}">
												</td>
												<td width="1%">
													@if($num==$j)
														<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@else
														<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														</button>
													@endif
												</td>
											</tr>
										</table>
									</div>
								<?php $i++; } } else { //Labour...?>
								
								@foreach($seritems as $item)
								{{--*/ $i++; /*--}}
								<?php 
									$total = 0;
									if($orderrow->is_fc==1) {
										 $unit_price = $item->unit_price / $orderrow->currency_rate;
										 $line_total = $item->line_total / $orderrow->currency_rate;
										 $vat_amount = round($item->vat_amount / $orderrow->currency_rate,2);
										 $total = $orderrow->total / $orderrow->currency_rate;
										 $vat_amount_net = $orderrow->vat_amount / $orderrow->currency_rate;
										 $net_total = round($orderrow->net_total / $orderrow->currency_rate,2);
									  } else {
										 $unit_price = $item->unit_price;
										 $line_total = $item->line_total;
										 $vat_amount = $item->vat_amount;
										 $total = $orderrow->total;
										 $vat_amount_net = $orderrow->vat_amount;
										 $net_total = $orderrow->net_total;
									  }
									
									?>
									<div class="lbitemdivChld">	
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="lborder_item_id[]" id="lbp_orditmid_{{$i}}" value="{{$item->id}}">
													<input type="hidden" name="lbitem_id[]" id="lbitmid_{{$i}}" value="{{$item->item_id}}">
													<input type="text" id="lbitmcod_{{$i}}" name="lbitem_code[]" class="form-control" autocomplete="off" value="{{$item->item_code}}" data-toggle="modal" data-target="#lbitem_modal">
												</td>
												<td width="29%">
													<input type="text" name="lbitem_name[]" id="lbitmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}">
												</td>
												<td width="7%">
													<select id="lbitmunt_{{$i}}" class="form-control select2 lbline-unit" style="width:100%" name="lbunit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</select>
												</td>
												<td width="8%">
													<input type="number" id="lbitmqty_{{$i}}" autocomplete="off" name="lbquantity[]" step="any" class="form-control lbline-quantity" value="{{$item->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="lbitmcst_{{$i}}" autocomplete="off" step="any" name="lbcost[]" class="form-control lbline-cost" value="{{$unit_price}}">
												</td>
												<td width="6%">
													<select id="lbtaxcode_{{$i}}" class="form-control select2 lbtax-code" style="width:100%" name="lbtax_code[]"><option value="SR" <?php if($item->tax_code=="SR") echo 'selected'; ?>>SR</option><option value="EX" <?php if($item->tax_code=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if($item->tax_code=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="lbtxincld_{{$i}}" class="form-control select2 lbtaxinclude" style="width:100%" name="lbtax_include[]"><option <?php if($item->tax_include==0) echo 'selected';?> value="0">No</option><option <?php if($item->tax_include==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="text" id="lbvatdiv_{{$i}}" step="any" readonly name="lbvatdiv[]" class="form-control lbvatdiv" value="{{$item->vat.'% - '.intval($vat_amount*100)/100}}"><!--<div class="h5" id="vatdiv_{{$i}}"></div>--> 
													<input type="hidden" id="lbvat_{{$i}}" name="lbline_vat[]" class="form-control lbvat" value="{{$item->vat}}">
													<input type="hidden" id="lbvatlineamt_{{$i}}" name="lbvatline_amt[]" class="form-control lbvatline-amt" value="{{$vat_amount}}">
													<input type="hidden" id="lbitmdsnt_{{$i}}" name="lbline_discount[]" value="{{$item->discount}}">
												</td>
												<td width="11%">
													<input type="text" id="lbitmttl_{{$i}}" name="lbline_total[]" class="form-control lbline-total" readonly value="{{intval($line_total*100)/100}}">
													<input type="hidden" id="lbitmtot_{{$i}}" name="lbitem_total[]" value="{{$item->item_total}}">
												</td>
												<td width="1%">
													@if($num==$i)
														<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@else
														<button type="button" class="btn-success btn-add-lbitem" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-lbitem" data-id="lbrem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@endif
												</td>
											</tr>
										</table>
									</div>
								@endforeach
								<?php } } else { ?>
									<input type="hidden" id="lbrowNum" value="1">
									<input type="hidden" id="lbremitem" name="lbremove_item">
									<?php if(old('lbitem_code')) { $i = 0; 
										foreach(old('lbitem_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="lborder_item_id[]" id="lbp_orditmid_{{$j}}">
													<span class="small <?php if($errors->has('lbitem_code.'.$i)) echo 'form-error';?>">Labour Code</span>
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
													<input type="hidden" name="lborder_item_id[]" id="lbp_orditmid_1">
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
								<?php }  } ?>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="<?php echo (old('total'))?old('total'):intval($total*100)/100; ?>">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly value="<?php echo (old('total_fc'))?old('total_fc'):intval($orderrow->total*100)/100; ?>" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control spl discount-cal" value="<?php echo (old('discount'))?old('discount'):$orderrow->discount; ?>">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control spl" placeholder="0">
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
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="<?php echo (old('subtotal'))?old('subtotal'):intval($orderrow->subtotal*100)/100; ?>">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" readonly placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="<?php echo (old('vat'))?old('vat'):intval($vat_amount_net*100)/100; ?>" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" value="<?php echo (old('vat_fc'))?old('vat_fc'):intval($orderrow->vat_amount*100)/100; ?>" placeholder="0" readonly>
										</div>
									</div>
                                </div>
								
								
								
								<!--<input type="hidden" step="any" id="discount" name="discount" class="form-control" value="{{$orderrow->discount}}">
								<input type="hidden" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">-->
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="<?php echo (old('net_amount'))?old('net_amount'):intval($net_total*100)/100; ?>">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" value="<?php echo (old('net_amount_fc'))?old('net_amount_fc'):$orderrow->net_total; ?>" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
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
                                        <button type="submit" class="btn btn-primary" <?php if($orderrow->is_editable==1) echo 'disabled';?>>Submit</button>
										<a href="{{ url('job_order') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('job_order/edit/'.$orderrow->id) }}" class="btn btn-warning">Clear</a>
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
var srvat={{$vatdata->percentage}};
$(document).ready(function () { 
	
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	$('.descdivPrnt').find('.btn-add-desc:not(:last)').hide();
	if ( $('.descdivPrnt').children().length == 1 ) {
		$('.descdivPrnt').find('.btn-remove-desc').hide();
	}
	
	$('.lbitemdivPrnt').find('.btn-add-lbitem:not(:last)').hide();
	if ( $('.lbitemdivPrnt').children().length == 1 ) {
		$('.lbitemdivPrnt').find('.btn-remove-lbitem').hide();
	}
	
	<?php if($orderrow->jobnature==0) { ?>
		$('#fabData').hide();
	<?php } else { ?>
		$('#vclData').hide();
	<?php } ?>
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php if($orderrow->is_fc==0) { ?>
	$("#subtotal_fc").toggle();
	$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); 
	var urlcode = "{{ url('sales_order/checkrefno/') }}";
    $('#frmSalesOrder').bootstrapValidator({
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
                                code: validator.getFieldElements('reference_no').val(),
								id: validator.getFieldElements('sales_order_id').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
        }
        
    }).on('reset', function (event) {
        $('#frmSalesOrder').data('bootstrapValidator').resetForm();
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
			var lineTotalTx = lineQuantity * lineCost;
		} 
		
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2));//val(lineVat+'% - '+taxLineCost.toFixed(2));
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
	
	
//
$(function() {	
	var rowNum = $('#rowNum').val(); //new change...........
	var descNum = $('#descNum').val();
	var lbrowNum = $('#lbrowNum').val();
	
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
			newEntry.find($('input[name="order_item_id[]"]')).attr('id', 'p_orditmid_' + rowNum);
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
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
			
			//ROWCHNG
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('#itmcod_'+rowNum).focus();

    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#p_orditmid_'+curNum).val():remitem+','+$('#p_orditmid_'+curNum).val();
		$('#remitem').val(ids);
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
	$(document).on('click', '.btn-add-desc', function(e)  { 
        descNum++;
		e.preventDefault();
        var controlForm = $('.controls .descdivPrnt'),
            currentEntry = $(this).parents('.descdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			newEntry.find($('.desclbl')).attr('id', 'lblid_' + descNum);
			newEntry.find($('#lblid_'+descNum).text('Description '+descNum));
			newEntry.find($('input[name="jobdetail_id[]"]')).attr('id', 'descid_' + descNum);
			newEntry.find($('input[name="opr_description[]"]')).attr('id', 'oprdescid_' + descNum);
			newEntry.find($('input[name="opr_comment[]"]')).attr('id', 'oprcmntid_' + descNum);
			
			controlForm.find('.btn-add-desc:not(:last)').hide();
			controlForm.find('.btn-remove-desc').show();
			
	}).on('click', '.btn-remove-desc', function(e) { 
		
		//new change..
		var res = $(this).attr('data-id').split('_');
		var descNum = res[1]; var dids;
		
		var dremitem = $('#remdesc').val();
		dids = (dremitem=='')?$('#descid_'+descNum).val():dremitem+','+$('#descid_'+descNum).val();
		$('#remdesc').val(dids);
		
		$(this).parents('.descdivChld:first').remove();
		
		$('.descdivPrnt').find('.descdivChld:last').find('.btn-add-desc').show();
		if ( $('.descdivPrnt').children().length == 1 ) {
			$('.descdivPrnt').find('.btn-remove-desc').hide();
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
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#lbremitem').val();
		ids = (remitem=='')?$('#lbp_orditmid_'+curNum).val():remitem+','+$('#lbp_orditmid_'+curNum).val();
		$('#lbremitem').val(ids);
		
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
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	var supurl = "{{ url('sales_order/salesman_data/') }}";
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
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/itm', function(result){ 
			$('#myModal').modal({show:true});
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
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').remove().end()
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
	
	
	$('#customer_name').on('blur', function(e){
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		
		$.ajax({
			url: "{{ url('sales_order/set_session/') }}",
			type: 'get',
			data: 'vchr_no='+vchr_no+'&ref_no='+ref_no+'&vchr_dt='+vchr_dt+'&lpo_dt='+lpo_dt,
			success: function(data) { //console.log(data);
			}
		}) 
	});
	
	$('#jobnature').on('change', function(e){ //console.log('f'+this.value)
		if(this.value==1) {
			$('#fabData').toggle(); $('#vclData').hide(); $('#vehicle_id').val('');
		} else {
			$('#vclData').toggle();$('#fabData').hide();
		}
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
		var curNum = res[1]; 
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
	
	$(document).on('blur', '.lbline-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotalLb(curNum);
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
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	/* $(document).on('change', '.line-unit', function(e) {
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
			});
		}
		
	}); */
	
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
							.attr("value",value.id)
							.text(value.unit_name)); 
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
							.attr("value",value.id)
							.text(value.unit_name)); 
					$('#hidunit_1').val(value.unit_name);
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
							.attr("value",value.id)
							.text(value.unit_name));  $('#hidunit_'+curNum).val(value.unit_name);
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

function getQuotation() { 
	if($("#customer_name").val()=='') {
		alert('Please select a customer first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var customer_id = $("#customer_id").val();
	var pourl = "{{ url('quotation_sales/get_quotation/') }}/"+customer_id+"/so";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
</script>
@stop
