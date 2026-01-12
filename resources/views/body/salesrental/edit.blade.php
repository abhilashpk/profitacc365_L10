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
                Sales Rental 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Sales Rental</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
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
                    <div class="panel panel-<?php echo ($orderrow->is_editable==1)?'danger':'primary';?>">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> <?php if($orderrow->is_editable==1) { echo 'Non-Editable'; } else echo 'Edit Invoice';?>
                            </h3>
							
							<div class="pull-right">
						
						
						
							</div>
                        </div>
						
						
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesInvoice" id="frmSalesInvoice" action="{{ url('sales_rental/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="sales_invoice_id" id="sales_invoice_id" value="{{ $orderrow->id }}">
								<input type="hidden" name="voucher_id" value="{{ $orderrow->voucher_id }}">
								<input type="hidden" name="is_rental" id="is_rental" value="1">
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" {{($orderrow->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Invoice</b></label>
                                    <div class="col-sm-10">
                                    <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
											<option value="{{ $vouchers->id }}" {{($orderrow->voucher_id==$vouchers->id)?'selected':''}}>{{ $vouchers->voucher_name }}</option>
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>SI. No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
                                    </div>
                                </div>
								
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" autocomplete="off" value="<?php echo (old('reference_no'))?old('reference_no'):$orderrow->reference_no; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}">
                                    </div>
                                </div>
								
								<?php if($formdata['lpo_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" autocomplete="off" placeholder="LPO No." value="{{$orderrow->lpo_no}}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_no" id="lpo_no">
								<?php } ?>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" data-language='en' autocomplete="off" readonly value="<?php echo ($orderrow->lpo_date=='0000-00-00')?'':date('d-m-Y',strtotime($orderrow->lpo_date));?>" placeholder="LPO Date"/>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>

								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Account</label>
                                    <div class="col-sm-10">
									  <div class="input-group">
                                        <input type="text" name="sales_account" id="sales_account" class="form-control" readonly value="{{$orderrow->account}}">
										<input type="hidden" name="cr_account_id" id="cr_account_id" class="form-control" value="{{$orderrow->cr_account_id}}">
										<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
									 </div>
									</div>
                                </div>

								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" value="<?php echo (old('customer_name'))?old('customer_name'):$orderrow->customer; ?>">
										<input type="hidden" name="customer_id" id="customer_id" value="<?php echo (old('customer_id'))?old('customer_id'):$orderrow->customer_id; ?>">
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="<?php echo (old('dr_account_id'))?old('dr_account_id'):$orderrow->dr_account_id;?>">
										<input type="hidden" name="old_customer_id" id="old_customer_id" value="{{$orderrow->customer_id}}">
										<div class="col-xs-10" id="customerInfo">
											<div class="col-xs-4">
												<span class="small">Current Balance</span> <input type="text" id="cr_balance" readonly class="form-control line-quantity" value="{{number_format($custdata->cl_balance,2)}}">
											</div>
											<div class="col-xs-4">
												<span class="small">PDC</span> <input type="text" id="pdc" readonly class="form-control line-cost" value="{{number_format($custdata->pdc_amount,2)}}">
											</div>
											<div class="col-xs-4">
												<span class="small">Crdit Limit</span> <input type="text" id="cr_limit" readonly readonly class="form-control cost" value="{{number_format($custdata->credit_limit,2)}}">
											</div>
										</div>
										
										<div class="col-xs-10" id="newcustomerInfo">
											<div class="col-xs-4">
												<span class="small">Customer Name</span> <input type="text" id="customername" name="customername" value="{{$orderrow->customer_name}}" class="form-control" autocomplete="off">
											</div>
											<div class="col-xs-4">
												<span class="small">TRN No</span> <input type="text" id="customer_trn" name="customer_trn" value="{{$orderrow->customer_trn}}" class="form-control" autocomplete="off">
											</div>
											<div class="col-xs-3">
												<span class="small">Phone No</span> <input type="text" id="customer_phone" name="customer_phone" value="{{$orderrow->customer_phone}}" class="form-control" autocomplete="off">
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
								
								<?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" value="<?php echo (old('salesman'))?old('salesman'):$orderrow->salesman; ?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
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
                                        <input type="text" class="form-control" id="description" name="description" autocomplete="off" value="<?php //echo (old('description'))?old('description'):$orderrow->description; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document Type</label>
                                    <div class="col-sm-10">
									 <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
										<option value="">Select Document...</option>
										<option value="SQ">Sales Quotation</option>
										<option value="SO">Sales Order</option>
										<option value="CDO">Delivery Order</option>
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
								
								
								<?php if($formdata['so_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Order No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="so_no" name="so_no" placeholder="Sales Order No" value="<?php echo (old('so_no'))?old('so_no'):$orderrow->so_no; ?>" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="so_no" id="so_no">
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
											<option value="{{ $job['id'] }}" <?php if($job['id']==$orderrow->job_id) echo 'selected'; ?>>{{ $job['code'] }}</option>
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
											<option value="{{ $loc['id'] }}" <?php if($loc['id']==$orderrow->location_id) echo 'selected';?>>{{ $loc['name'] }}</option>
											<?php } ?>
											
											<?php if($orderrow->location_id==0) { ?>
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
											<input type="text" name="currency_rate" id="currency_rate" autocomplete="off" value="{{$orderrow->currency_rate}}" class="form-control" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
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
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
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
								<!-- ROWCHNG -->
								{{--*/ $i = 0; $num = count($orditems); /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
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
													<input type="number" id="itmqty_{{$j}}" autocomplete="off" step="any" name="quantity[]" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$j}}" autocomplete="off" step="any" name="cost[]" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{ old('cost')[$i]}}">
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
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$j}}">
													</div>
												</div>
											</div>
											
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_{{$j}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div id="ssede" style="float:left; padding-right:5px;">
												<button type="button" id="sede_{{$j}}" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div style="float:left; padding-left:10px;">
												<button type="button" id="custsaleshisItm_{{$j}}" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
											
											
											<div class="sedePrntItm" id="sedePrntItm_{{$j}}">
												<div class="sedeChldItm">							
													<div class="table-responsive sede-data" id="sedeData_{{$j}}"></div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$j}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$j}}">
														<?php 
														if(array_key_exists($item, $itemlocedit)) { 
														
															 ?>
														<br/>
														<div class="col-xs-4">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Location</th>
																	<th>Stock</th>
																	<th>Quantity</th>
																</tr>
																</thead>
																<tbody>
																<?php 
																foreach($itemloc as $key => $row) {
																
																$quantity = $id = '';
																$location_id = $row->id; $cqty = $row->cqty;
																if(array_key_exists($key, $itemlocedit[$item])) { 
																	$cqty = $itemlocedit[$item][$key]->cqty;
																	$quantity = $itemlocedit[$item][$key]->quantity;
																	$location_id = $itemlocedit[$item][$key]->location_id;
																	$id = $itemlocedit[$item][$key]->id;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td>{{ $cqty }}</td>
																	<td><input type="number" name="locqty[<?php echo $i-1;?>][]" value="{{$quantity}}">
																	<input type="hidden" name="locid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="editid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
																</tr>
																<?php } ?>
																</tbody>
															</table>
														</div>
															<?php  } ?>
													</div>
												</div>
											</div>
											
											<?php 
											if(array_key_exists($item, $itemdesc)) {
												
											echo '<input type="hidden" id="remitemdesc_'.$i.'" name="remove_itemdesc[]">';
											foreach($itemdesc[$item] as $desc) { ?>
												<div class="descdivPrntItm" id="descdivPrntItm_{{$j}}">
													<div class="descdivChldItm" >							
														<div class="col-xs-10" style="padding-bottom:5px !important;">
															<div class="col-xs-10">
																<input type="text" name="itemdesc[<?php echo $item;?>][]" autocomplete="off" class="form-control txt-desc" value="{{$desc->description}}" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
																 <button type="button" class="btn btn-success btn-add-desc" >
																	<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
																 </button>
																 <button type="button" class="btn btn-danger btn-remove-desc" data-id="remdesc_{{$j}}" data-itmid="{{$item}}">
																	<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
																 </button>
																 <input type="hidden" name="desc_id[<?php echo $item;?>][]" value="{{$desc->id}}" class="hid-id">
															</div>
														</div>
													</div>
												</div>
											<?php } } else { ?>
												<div class="descdivPrntItm" id="descdivPrntItm_{{$j}}" style="display: none;">
													<div class="descdivChldItm" >							
														<div class="col-xs-10" style="padding-bottom:5px !important;">
															<div class="col-xs-10">
																<input type="text" name="itemdesc[<?php echo $i-1;?>][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
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
											<?php } ?>
											
										
									</div>
								<?php $i++; } } else { ?>
								
								@foreach($orditems as $item)
								{{--*/ $i++; /*--}}
								<?php if($orderrow->is_fc==1) {
										 $unit_price = $item->unit_price / $orderrow->currency_rate;
										 $line_total = number_format($item->line_total / $orderrow->currency_rate,2, '.', '');
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
													<input type="number" id="itmqty_{{$i}}" autocomplete="off" step="any" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$i}}" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" value="{{$unit_price}}">
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
													<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]" value="{{$item->discount}}">
												</td>
												<td width="11%">
													<input type="text" id="itmttl_{{$i}}" name="line_total[]" class="form-control line-total" readonly value="{{$line_total}}">
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
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_{{$i}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div id="ssede" style="float:left; padding-right:5px;">
												<button type="button" id="sede_{{$i}}" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$i}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="saleshisItm_{{$i}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="custsaleshisItm_{{$i}}" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="assemblyItm_{{$i}}" data-toggle="modal" data-target="#assembly_modal" class="btn btn-primary btn-xs asm-itm">Add Assembly Items</button>
												<input type="hidden" name="assembly_items[]" id="asmid_{{$i}}" value="{{$item->assembly_items}}">
												<input type="hidden" name="assembly_items_qty[]" id="asmqt_{{$i}}" value="{{$item->assembly_items_qty}}">
												<input type="hidden" name="assembly_items_old[]" value="{{$item->assembly_items}}">
												<input type="hidden" name="assembly_items_qty_old[]" value="{{$item->assembly_items_qty}}">
											</div>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="assemblyItmVw_{{$i}}" data-toggle="modal" data-target="#view_assembly_modal" class="btn btn-primary btn-xs view-asm-itm">View Assembly Items</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div class="sedePrntItm" id="sedePrntItm_{{$i}}">
												<div class="sedeChldItm" style="float:right; padding-right:5px;">							
													<div class="table-responsive sede-data" id="sedeData_{{$i}}"></div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$i}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$i}}">
														<?php 
														if(array_key_exists($item->id, $itemlocedit)) { 
														
															 ?>
														<br/>
														<div class="col-xs-4">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Location</th>
																	<th>Stock</th>
																	<th>Quantity</th>
																</tr>
																</thead>
																<tbody>
																<?php 
																foreach($itemloc as $key => $row) {
																//foreach($itemlocedit[$item->id] as $row) { 
																$quantity = $id = '';
																$location_id = $row->id; $cqty = $row->cqty;
																if(array_key_exists($key, $itemlocedit[$item->id])) { 
																	$cqty = $itemlocedit[$item->id][$key]->cqty;
																	$quantity = $itemlocedit[$item->id][$key]->quantity;
																	$location_id = $itemlocedit[$item->id][$key]->location_id;
																	$id = $itemlocedit[$item->id][$key]->id;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td>{{ $cqty }}</td>
																	<td><input type="number" name="locqty[<?php echo $i-1;?>][]" value="{{$quantity}}">
																	<input type="hidden" name="locid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="editid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
																</tr>
																<?php } ?>
																</tbody>
															</table>
														</div>
															<?php  } ?>
													</div>
												</div>
											</div>
											
											<?php 
											if(array_key_exists($item->id, $itemdesc)) {
												
											echo '<input type="hidden" id="remitemdesc_'.$i.'" name="remove_itemdesc[]">';
											foreach($itemdesc[$item->id] as $desc) { ?>
												<div class="descdivPrntItm" id="descdivPrntItm_{{$i}}">
													<div class="descdivChldItm" >							
														<div class="col-xs-10" style="padding-bottom:5px !important;">
															<div class="col-xs-10">
																<input type="text" name="itemdesc[<?php echo $item->id;?>][]" autocomplete="off" class="form-control txt-desc" value="{{$desc->description}}" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
																 <button type="button" class="btn btn-success btn-add-desc" >
																	<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
																 </button>
																 <button type="button" class="btn btn-danger btn-remove-desc" data-id="remdesc_{{$i}}" data-itmid="{{$item->id}}">
																	<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
																 </button>
																 <input type="hidden" name="desc_id[<?php echo $item->id;?>][]" value="{{$desc->id}}" class="hid-id">
															</div>
														</div>
													</div>
												</div>
											<?php } } else { ?>
												<div class="descdivPrntItm" id="descdivPrntItm_{{$i}}" style="display: none;">
													<div class="descdivChldItm" >							
														<div class="col-xs-10" style="padding-bottom:5px !important;">
															<div class="col-xs-10">
																<input type="text" name="itemdesc[<?php echo $i-1;?>][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
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
											<?php } ?>
											
										
									</div>
								@endforeach
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" step="any" id="total" step="any" name="total" class="form-control spl" readonly value="{{(old('total'))?old('total'):($orderrow->is_fc==1)?$orderrow->total_fc:$orderrow->total}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" step="any" id="total_fc" name="total_fc" class="form-control spl" value="{{(old('total_fc'))?old('total_fc'):$orderrow->total}}" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{(old('discount'))?old('discount'):($orderrow->is_fc==1)?$orderrow->discount_fc:$orderrow->discount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control spl" value="{{(old('discount_fc'))?old('discount_fc'):$orderrow->discount}}" placeholder="0">
										</div>
									</div>
                                </div>
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc" name="less_description" value="{{(old('less_description'))?old('less_description'):$orderrow->less_description}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount" name="less_amount" value="{{(old('less_amount'))?old('less_amount'):$orderrow->less_amount}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount" id="less_amount">
								<?php } ?>
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc2" name="less_description2" value="{{(old('less_description2'))?old('less_description2'):$orderrow->less_description2}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount2" name="less_amount2" value="{{(old('less_amount2'))?old('less_amount2'):$orderrow->less_amount2}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount2" id="less_amount2">
								<?php } ?>
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc3" name="less_description3" value="{{(old('less_description3'))?old('less_description3'):$orderrow->less_description3}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount3" name="less_amount3" value="{{(old('less_amount3'))?old('less_amount3'):$orderrow->less_amount3}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount3" id="less_amount3">
								<?php } ?>
								
								<?php if($formdata['less_prev_invoice']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" class="form-control spl" id="previnv_description" name="previnv_description" value="{{(old('previnv_description'))?old('previnv_description'):$orderrow->previnv_description}}" placeholder="Previous Invoice Details" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="previnv_amount" name="previnv_amount" value="{{(old('previnv_amount'))?old('previnv_amount'):$orderrow->previnv_amount}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount" id="less_amount">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Subtotal<span id="subttle" class="small"></span></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):($orderrow->is_fc==1)?$orderrow->subtotal_fc:$orderrow->subtotal}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" readonly value="{{(old('subtotal_fc'))?old('subtotal_fc'):$orderrow->subtotal}}">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<?php if($formdata['vat_description']==1) { ?><input type="text" step="any" class="form-control spl" id="vatdesc" name="vatdesc" value="5% VAT Extra" readonly><input type="hidden" id="vatd" name="vatfrm_subtotal" value="5"/><?php } ?>
										</div>
										<div class="col-xs-2">
											<input type="hidden" id="vatcur" name="vatcur" value="{{(old('vatcur'))?old('vatcur'):($orderrow->is_fc==1)?$orderrow->vat_amount_fc:$orderrow->vat_amount}}">
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="{{(old('vat'))?old('vat'):($orderrow->is_fc==1)?$orderrow->vat_amount_fc:$orderrow->vat_amount}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" value="<?php echo (old('vat_fc'))?old('vat_fc'):$orderrow->vat_amount; ?>" placeholder="0" readonly>
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
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" class="form-control" readonly value="{{(old('net_amount_hid'))?old('net_amount_hid'):($orderrow->is_fc==1)?$orderrow->net_total_fc:$orderrow->net_total}}">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{(old('net_amount'))?old('net_amount'):($orderrow->is_fc==1)?$orderrow->net_total_fc:$orderrow->net_total}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" value="<?php echo (old('net_amount_fc'))?old('net_amount_fc'):$orderrow->net_total; ?>" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								
								<hr/><?php if(isset($formdata['advance']) && $formdata['advance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Balance</label>
                                    <div class="col-sm-10">
										<input type="hidden" id="advance_pd" name="advance_pd" value="<?php echo $orderrow->advance; ?>">
                                        <input type="number" step="any" class="form-control spl" id="advance" name="advance" value="<?php //echo $orderrow->balance; ?>" autocomplete="off">
									</div>
                                </div>
								
								<?php } else { ?>
								<input type="hidden" name="advance" id="advance">
								<?php } ?>
								
								<?php if(isset($formdata['balance']) && $formdata['balance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Balance Outstanding</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control spl" id="balance" readonly name="balance" value="<?php echo $orderrow->balance; ?>" placeholder="Balance" autocomplete="off">
										<input type="hidden" id="balance_os" name="balance_os" value="<?php echo $orderrow->balance; ?>">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="balance" id="balance">
								<?php } ?>
								
								<?php if($formdata['footer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id" value="{{$orderrow->fid}}">
                                        <input type="text" class="form-control" id="footermsg" name="footer" value="{{$orderrow->footer}}" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="footer_id" id="footer_id">
								<?php } ?>
								
								<?php if(isset($formdata['rv_entry']) && $formdata['rv_entry']==1) { ?>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Receipt Voucher Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="rv_icheck" id="is_rv" name="is_rv" <?php if($orderrow->is_rventry==1) echo 'checked';?> value="1">
											<input type="hidden" id="rv_entry_id" name="rv_entry_id" value="{{($rventry)?$rventry->rvid:''}}"/>
                                    </div>
                                </div>
								
								
								<div id="rv_form">
								<table border="0" class="table-dy-row">
									<tr>
										<td width="10%">
											<input type="hidden" id="rv_voucher" name="rv_voucher" value="{{$rvid}}"/>
											<span class="small">Voucher Type </span>
											<select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="CASH" <?php if($rventry && $rventry->voucher_type=='CASH') echo 'selected'; ?>>Cash</option>
											<option value="BANK" <?php if($rventry && $rventry->voucher_type=='BANK') echo 'selected'; ?>>Bank</option>
											<option value="PDCR" <?php if($rventry && $rventry->voucher_type=='PDCR') echo 'selected'; ?>>PDCR</option>
										</select>
										</td>
										<td width="10%">
											<span class="small">RV Voucher No</span>
											<input type="text" name="rv_voucher_no" value="{{($rventry)?$rventry->receipt_voucher_id:$rvvoucher['voucher_no']}}" id="rv_voucher_no" autocomplete="off" class="form-control">
										</td>
										<td width="20%">
											<span class="small">Debit Account</span>
											<input type="text" name="rv_dr_account" id="rv_dr_account" autocomplete="off" class="form-control" value="{{($rventry)?$rventry->master_name:$rvvoucher['account_name']}}">
											<input type="hidden" id="rv_dr_account_id" name="rv_dr_account_id" value="{{($rventry)?$rventry->account_id:$rvvoucher['id']}}">
										</td>
										<td width="15%">
											<span class="small">Amount</span> 
											<input type="number" id="rv_amount" step="any" value="{{($rventry)?$rventry->amount:''}}" name="rv_amount" class="form-control" placeholder="Amount">
										</td>
										<td width="15%">
											<span class="small pdcr">Bank</span> 
											<select id="bank_id" class="form-control select2" style="width:100%" name="bank_id">
											@foreach($banks as $bank)
											<option value="{{$bank['id']}}" <?php if(old('bank_id')==$bank['id']) echo 'selected'; ?>>{{$bank['name']}}</option>
											@endforeach
											</select>
										</td>
										<td width="15%">
											<span class="small pdcr">Cheque No</span>
											<input type="text" name="cheque_no" id="cheque_no" class="form-control" value="{{($rventry)?$rventry->cheque_no:''}}" placeholder="Cheque No">
										</td>
										<td width="15%">
											<span class="small pdcr">Cheque Date</span>
											<input type="text" name="cheque_date" id="cheque_date" autocomplete="off" value="{{($rventry)?date('d-m-Y',strtotime($rventry->cheque_date)):''}}" class="form-control" data-language='en' readonly placeholder="Cheque Date">
										</td>
									</tr>
								</table>
								</div>
								<?php } else { ?>
								<input type="hidden" name="is_rv" id="is_rv">
								<input type="hidden" name="rv_entry_id" id="rv_entry_id">
								<?php } ?>
								
								<!--<div id="showmenu">
									<button type="button" id="infoadd" class="btn btn-primary btn-xs">Add Info..</button>
								</div>-->
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
								
								@can('qs-aprv')
								<?php if($settings->doc_approve==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Status</label>
                                    <div class="col-sm-10">
                                        <select id="doc_status" class="form-control select2" style="width:100%" name="doc_status">
                                            <option value="0" <?php if($orderrow->doc_status==0) echo 'selected';?>>Pending</option>
                                            <option value="1" <?php if($orderrow->doc_status==1) echo 'selected';?>>Approve</option>
											<option value="0">Undo Approve</option>
                                            <option value="2" <?php if($orderrow->doc_status==2) echo 'selected';?>>Reject</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Comment</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="comment" value="<?php echo (old('comment'))?old('comment'):$orderrow->comment; ?>" name="comment" placeholder="comment">
                                    </div>
                                </div>
								<?php } ?>
								@endcan
								<input type="hidden" value="<?php echo $orderrow->comment; ?>" name="comment_hd">
								<?php if($settings->doc_approve==1) { ?>
								<div class="form-group">
								<label for="input-text" class="col-sm-2 control-label"></label>
								<p>
									<i style="color:red;">Comments: {{$orderrow->comment}}</i>
								</p>
								</div>
								<br/>
								
								<div id="showmail">
									<button type="button" id="mailform" class="btn btn-primary btn-xs">Email Settings</button>
								</div>
								<div class="maildivPrnt">
									<div class="maildivChld">	
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Send Email</label>
												 <div class="col-xs-10">
													<div class="col-xs-1">
														<label class="radio-inline iradio">
														<input type="checkbox" id="chkmail" name="chkmail" value="1">
														</label>
													</div>
												</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Email</label>
												 <div class="col-sm-10">
													<input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{$orderrow->email}}">
													<small><i>Note: Use comma(,) to multiple email.</i></small>
												</div>
										</div>
										
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Message</label>
												 <div class="col-sm-10">
													<textarea name="email_message" id="email_message" class="form-control" placeholder="Message"></textarea>
												</div>
										</div>
									</div>
								</div>
								<br/>
								
								<?php } ?>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" <?php if($orderrow->is_editable==1) echo 'disabled';?>>Submit</button>
										<a href="{{ url('sales_rental') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('sales_rental/edit/'.$orderrow->id) }}" class="btn btn-warning">Clear</a>
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
							
							<div id="cust_sales_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Customer Sales History</h4>
                                        </div>
                                        <div class="modal-body" id="custsaleshisData">
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
							
							<div id="assembly_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Assembly Items</h4>
                                        </div>
                                        <div class="modal-body" id="asmblyitmData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="view_assembly_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Assembly Items</h4>
                                        </div>
                                        <div class="modal-body" id="asmblyitmDataVw">
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

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";
var taxinclude = false;
$('#optHide').toggle(); var srvat={{$vatdata->percentage}};
$(document).ready(function () { 
	
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	<?php if( sizeof($vouchers) > 0 && $vouchers->is_cash_voucher==1) { ?> //cash customer.... 
		if( $('#newcustomerInfo').is(":hidden") )
			$('#newcustomerInfo').toggle();
		$('#customer_id').val({{$vouchers->default_account_id}});
		$('#dr_account_id').val({{$vouchers->default_account_id}});
		
		if( $('#customerInfo').is(":visible") )
			$('#customerInfo').toggle();
	<?php } else { ?>
		if( $('#customerInfo').is(":hidden") ) 
			$('#customerInfo').toggle();
		
		if( $('#newcustomerInfo').is(":visible") )
			$('#newcustomerInfo').toggle();
	<?php } ?>
	
	<?php if($orderrow->is_rventry==0) { ?>
		$('#rv_form').toggle(); $('#cheque_no').hide(); $('#cheque_date').hide(); $('#bank_id').hide(); $('.pdcr').hide();
	<?php } ?>
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php if($orderrow->is_fc==0) { ?>
	$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();$("#subtotal_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	
	<?php if(($rventry && $rventry->voucher_type=='BANK') || ($rventry && $rventry->voucher_type=='PDCR')) { ?>
		$('#cheque_no').show();$('#cheque_date').show();$('#bank_id').show();$('.pdcr').show();
	<?php } else { ?>
		$('#cheque_no').hide();$('#cheque_date').hide();$('#bank_id').hide();$('.pdcr').hide();
	<?php } ?>
	
	$('.locPrntItm').toggle(); $('.sedePrntItm').toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('.maildivPrnt').toggle();
	var urlcode = "{{ url('sales_rental/checkrefno/') }}";
    $('#frmSalesInvoice').bootstrapValidator({
        fields: {
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The reference no is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val(),
								id: validator.getFieldElements('sales_invoice_id').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmSalesInvoice').data('bootstrapValidator').resetForm();
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
		//console.log('lineTotal: '+lineTotal);
		$('#total').val(lineTotal.toFixed(2));
		
		/* if($('#less_amount').length) {
			var less;
			less = parseFloat( ($('#less_amount').val()=='') ? 0 : $('#less_amount').val() );
			lineTotal = lineTotal - less;
		}
		
		if($('#previnv_amount').length) {
			var prvamt;
			prvamt = parseFloat( ($('#previnv_amount').val()=='') ? 0 : $('#previnv_amount').val() );
			lineTotal = lineTotal - prvamt;
		} */
		
		var less = 0;
		if($('.lesamt').length) {
			$( '.lesamt' ).each(function() {
				var v = (this.value=='')?0:parseFloat(this.value);
				less = less + v;
			});
			lineTotal = lineTotal - less;
		}
		
		$('#subtotal').val(lineTotal.toFixed(2));
		//new change..
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		
		if($('#vatd').length){
			vatcur = (lineTotal * 5)/100;
		}
		
		$('#vat').val(vatcur.toFixed(2));
		
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
			var fcTax = vat * rate; var subfc = lineTotal * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax.toFixed(2)); $('#subtotal_fc').val(subfc.toFixed(2));
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
	
	function calculateDiscount()
	{ 
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var lineTotal = parseFloat( ($('#total').val()=='') ? 0 : $('#total').val() );
		var vatTotal = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		
			var subtotal = lineTotal - discount; 
			
			/* if($('#less_amount').length) {
				var less;
				less = parseFloat( ($('#less_amount').val()=='') ? 0 : $('#less_amount').val() );
				subtotal = subtotal - less;
			}
			
			if($('#previnv_amount').length) {
				var prvamt;
				prvamt = parseFloat( ($('#previnv_amount').val()=='') ? 0 : $('#previnv_amount').val() );
				subtotal = subtotal - prvamt;
			} */
			
			var less = 0;
			if($('.lesamt').length) {
				$( '.lesamt' ).each(function() {
					var v = (this.value=='')?0:parseFloat(this.value);
					less = less + v;
				});
				subtotal = subtotal - less;
			}
			
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
				  console.log('tot:'+total);
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
			
			if($('#vatd').length){
				vatnet = (subtotal * 5)/100;
			}
			
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
	
	/* function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var customer_id = $('#customer_id').val();
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&customer_id='+customer_id,
			success: function(data) {
				$('#itmcst_'+curNum).val(data);
				return true;
			}
		}) 
	} */
	
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
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				data: 'item_id='+item_id,
				success: function(data) {
					$('#itmcst_'+curNum).val((data==0)?'':data);
					$('#itmcst_'+curNum).focus();
					return true;
				}
			}); 
		}
	}
	
	function resetValues(n) { console.log(n);
		var lnvat = parseFloat($('#vatlineamt_'+n).val());
		var vat = parseFloat( $('#vat').val())
		var lntotal = parseFloat($('#itmttl_'+n).val());
		var total = parseFloat( $('#total').val())
		$('#vat').val(vat - lnvat);
		$('#total').val(total - lntotal);
		var net_amount = $('#net_amount').val();
		
		$('#net_amount').val( (net_amount - lntotal - lnvat).toFixed(2));
		
		$('#itmunt_'+n).find('option').remove().end().append('<option value="">Select</option>');
		$('#itmqty_'+n).val('');
		$('#itmcst_'+n).val('');
		$('#vatdiv_'+n).val('');
		$('#vatlineamt_'+n).val();
		$('#itmttl_'+n).val('');
	}
//
$(function() {	
	var rowNum = $('#rowNum').val(); //new change...........
	
	$(document).on('click', '#infoadd', function(e) { 
		 e.preventDefault();
         $('.infodivPrnt').toggle();
     });
	
	$(document).on('click', '#mailform', function(e) { 
	   e.preventDefault();
	   $('.maildivPrnt').toggle();
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
        rowNum++; console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="order_item_id[]"]')).attr('id', 'p_orditmid_' + rowNum);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); 
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
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
			newEntry.find($('.hid-id')).attr('name', 'desc_id['+indx+'][]');
			
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
			newEntry.find($('.asm-itm')).attr('id', 'assemblyItm_' + rowNum);
			newEntry.find($('.view-asm-itm')).attr('id', 'assemblyItmVw_' + rowNum);
			newEntry.find($('input[name="assembly_items[]"]')).attr('id', 'asmid_' + rowNum); 
			newEntry.find($('input[name="assembly_items_qty[]"]')).attr('id', 'asmqt_' + rowNum);
						
			$('#locData_'+rowNum).html('');
			newEntry.find($('.cust-sales-his')).attr('id', 'custsaleshisItm_' + rowNum);
			newEntry.find($('.sedePrntItm')).attr('id', 'sedePrntItm_' + rowNum);
				newEntry.find($('.sup-sede')).attr('id', 'sede_' + rowNum); 
				newEntry.find($('.sede-data')).attr('id', 'sedeData_' + rowNum);
				$('#sedeData_'+rowNum).html('');
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
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
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
			if( $('#sedePrntItm_'+rowNum).is(":visible") ) 
				$('#sedePrntItm_'+rowNum).toggle();
			
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
			/* controlForm.find('.btn-add-desc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-desc').addClass('btn-remove-desc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>'); */
			
    }).on('click', '.btn-remove-desc', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		var desid = $(this).next().val();
		//var g = $('input:hidden[name=desc_id['+itmid+'][]]').val();
		
		//console.log($(this).next().val());
		var remitemdesc = $('#remitemdesc_'+curNum).val();
		ids = (remitemdesc=='')?desid:remitemdesc+','+desid;
		$('#remitemdesc_'+curNum).val(ids);
		
		$(this).parents('.descdivChldItm:first').remove();

		e.preventDefault();
		return false;
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			if( $('#customerInfo').is(":hidden") ) 
				$('#customerInfo').toggle();
		});
			
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('sales_rental/getvoucher/') }}/" + vchr_id, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.account_id!=null && data.account_name!=null) {
				$('#sales_account').val(data.account_id+'-'+data.account_name);
				$('#cr_account_id').val(data.id);
			} else {
				$('#sales_account').val('');
				$('#cr_account_id').val('');
			}
		});
	});
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		}); 
		
	});
	
	$(document).on('click', '.sup-sede', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val(); //console.log(item_id);
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_sedeinfo/') }}/"+item_id+"/"+curNum
		   $('#sedeData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#sedePrntItm_'+curNum).toggle();
	   }
    });
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		$('#dr_account_id').val($(this).attr("data-id"));
		$('#cr_balance').val($(this).attr("data-clbalance"));
		$('#cr_limit').val($(this).attr("data-crlimit"));
		$('#pdc').val($(this).attr("data-pdc"));
		e.preventDefault();
	});

	var supurl = "{{ url('sales_rental/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
			if($('#p_orditmid_'+curNum).val()!='')
				resetValues(curNum);
		});
		
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
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
		
		//if( $(this).attr("data-type")==1) {
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
			
		/* TEMPORARLY DISABLED ... } else {
			$.get("{{ url('itemmaster/getunit/') }}", function(data) {
				$('#itmunt_'+num).find('option').remove().end();
				$.each(data, function(key, value) {   
					$('#itmunt_'+num).find('option').end()
					 .append($("<option></option>")
								.attr("value",value.id)
								.text(value.unit_name)); 
				});
			});
		} */
		getAutoPrice(num);
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
	
	//updated mar 25...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false;
		} else {
			var itmid = $('#itmid_'+curNum).val();
			$.get("{{ url('itemmaster/checkqty/') }}/" + itmid, function(data) {  
				data = JSON.parse(data); console.log(data.cur_quantity+' '+data.min_quantity);
				var cur_quantity = parseFloat(data.cur_quantity);
				var min_quantity = parseFloat(data.min_quantity);
				<?php //if($settings->item_quantity==1) { ?>
				@can('-qty-sale')
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
				@endcan
				<?php //} ?>
			});
		}
	});
	
	
	$('.inputsa').on('click', function(e) {
		$('#sales_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();

		@can('si-create')
			var salecost = parseFloat( $('#costavg_'+curNum).val() ); 
			if(this.value!='' && this.value!=0 && salecost > this.value) {
				alert('This item price is lower than cost avg.');
				$('#itmcst_'+curNum).val('');
				$('#itmcst_'+curNum).focus();
			}
		@endcan
	});
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
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
	});
	
	//total discount section.........
				
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	//new change...
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
				$('#itmcst_'+curNum).val((data==0)?'':data);
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
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
	});
	
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   var si_id = $('#sales_invoice_id').val();
	   if(item_id!='') {
		    var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+item_id+"/"+curNum+"/"+si_id+"/SI";
		   $('#locData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#locPrntItm_'+curNum).toggle();
	   }
    });
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	$('.cust-history').click(function() {
		var hisurl = "{{ url('sales_rental/cust_history/') }}";
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
		var phisurl = "{{ url('sales_rental/cust_history_phone/') }}";
		var phone = $('#customer_phone').val();
		$('#custphonehistoryData').load(phisurl+'/'+phone, function(result) {
			$('#myModal').modal({show:true});
		});
		
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
	
	var supurl = "{{ url('sales_rental/salesman_data/') }}";
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
	
	var newcusturl = "{{ url('sales_order/newcustomer_data/') }}";
	$('#customername').click(function() { 
		$('#newcustomerData').load(newcusturl, function(result) {
			$('#myModal').modal({show:true});
			
		});
	});
	
	$(document).on('click', '.newcustRow', function(e) { //console.log($(this).attr("data-trnno"));
		$('#customername').val($(this).attr("data-name"));
		$('#customer_trn').val($(this).attr("data-trnno"));
						
		e.preventDefault();
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
	
	///Customer search...
	var srccust = "{{ url('sales_rental/ajax_customer/') }}";
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
	
	$(document).on('keyup', '#advance', function(e) {
		var netamt = parseFloat( ($('#balance_os').val()=='') ? 0 : $('#balance_os').val() );console.log(netamt);
		var bal = netamt - this.value;
		$('#balance').val(bal.toFixed(2));
	});
	
	$(document).on('keyup', '#less_amount, #less_amount2, #less_amount3, #previnv_amount', function(e) { 
		getNetTotal();
	});
	
	$(document).on('click', '.cust-sales-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	    var cust_id = $('#customer_id').val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_custsales_info/') }}/"+item_id+"/"+cust_id; 
		   $('#custsaleshisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
    });
	
	var asmitmurl = "{{ url('itemmaster/asmitem_data/') }}";
	$(document).on('click', '.asm-itm', function(e) { 
	   e.preventDefault();
	    var res = this.id.split('_');
		var curNum = res[1]; 
		$('#asmblyitmData').load(asmitmurl+'/'+curNum, function(result){
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
    });
	
	$(document).on('click', '.add-asm-item', function(e)  { 
		 var nm = $('#numasm').val();
		 $('#asmid_'+nm).val($('#asit').val());
		 $('#asmqt_'+nm).val($('#asqt').val());
		 
		/*var itid = $('#asmid_'+nm).val();
		var ids = [];
		$("input[name='itmid[]']:checked").each(function() { 
			ids.push($(this).val());
		});
		$('#asmid_'+nm).val((itid=='')?ids:itid+','+ids); */
		
	});
	
	$(document).on('click', '.view-asm-itm', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#asmid_'+curNum).val(); 
	   var item_qty = $('#asmqt_'+curNum).val(); 
	   if(item_id!='') {
		   var vwUrl = "{{ url('itemmaster/get_assembly_items/') }}/"+item_id+'/'+item_qty+'/'+curNum; 
		   $('#asmblyitmDataVw').load(vwUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
    });
	
	$(document).on('click', '.funRemove', function(e) { 
		var n = $(this).data("no");
		var v = $(this).data("id").toString();
		var idar = $('#asmid_'+n).val().split(",");
		var qtar = $('#asmqt_'+n).val().split(",");
		var a = idar.indexOf(v); 
		removeAr(idar, v)
		qtar.splice(a,1);
		//console.log(idar); 
		$('#asmid_'+n).val(idar);
		$('#asmqt_'+n).val(qtar);
		$(this).parent().parent().remove();
		
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
		var pourl = "{{ url('quotation_sales/get_quotation/') }}/"+customer_id+"/QS";
	else if(doc=='SO')
		var pourl = "{{ url('sales_order/get_order/') }}/"+customer_id+"/SO";
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

</script>
@stop
