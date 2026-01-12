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
                Purchase Return22
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Return</a>
                </li>
                <li class="active">
                    Add
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Order Return
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurReturn" id="frmPurReturn" action="{{ url('purchase_return/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" {{($orderrow->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Invoice</b></label>
                                    <div class="col-sm-10">
                                    <select id="voucher_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="voucher_id">
										@foreach($vouchers as $voucher)
										<option value="{{ $voucher['id'] }}" <?php if($orderrow->voucher_id==$voucher['id']) echo 'selected';?>>{{ $voucher['voucher_name'] }}</option>
										@endforeach
									</select>
                                    </div>
                                </div>
								@else
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Invoice</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PR. No.</label>
									<input type="hidden" name="curno" id="curno" value="<?php echo $vouchers[0]['voucher_no']; ?>">
                                    <div class="col-sm-10">
										<div class="input-group">
										@if($vouchers[0]['is_prefix']==1)<span class="input-group-addon">{{$vouchers[0]['prefix']}}</span>@endif
                                        <input type="text" class="form-control" id="voucher_no" placeholder="<?php echo $vouchers[0]['voucher_no']; ?>" readonly name="voucher_no">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Purchase Invoice No</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="purchase_invoice_no" name="purchase_invoice_no" value="{{$pino}}" autocomplete="off" onclick="getPurchaseInvoice(this)">
										<input type="hidden" class="form-control" id="purchase_invoice_id" name="purchase_invoice_id" value="{{$piid}}">
										<input type="hidden" name="is_prior" id="is_prior">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Stock Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="stock_account" id="stock_account" class="form-control" readonly value="<?php echo $vouchers[0]['acode'].' - '.$vouchers[0]['account']; ?>">
										<input type="hidden" name="account_master_id" id="account_master_id" class="form-control" value="<?php echo $vouchers[0]['acid']; ?>">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" value="{{$orderrow->supplier}}">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{$orderrow->supplier_id}}">
									</div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" autocomplete="off" value="{{$orderrow->reference_no}}" name="reference_no" placeholder="Reference No.">
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PR. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>

								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                         <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" value="{{$orderrow->code}}">
										<input type="hidden" name="job_id" id="job_id" value="{{$orderrow->job_id}}">
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
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" <?php if($orderrow->is_fc==1) echo 'checked';?>>
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
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Import</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="import" id="import" name="is_import" <?php if($orderrow->is_import==1) echo 'checked';?> value="1">
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
										<th width="11%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="21%" class="itmHd">
											<span class="small">Item Description</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
											@if($ismpqty==1)
										<th width="7%" class="itmHd">
											<span class="small">MP Qty</span>
										</th>
										@endif
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
								
								@php $i = 0; $num = count($pitems); $total = $vattotal = $nettotal = $nettotal_dh = $total_dh = $vattotal_dh = 0; @endphp
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
									<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
										<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
												<input type="hidden" name="purchase_invoice_item_id[]" id="p_invitmid_{{$j}}" value="{{old('purchase_invoice_item_id')[$i]}}">
												<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{old('item_id')[$i]}}">
												<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" value="{{$item}}">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_{{$j}}" autocomplete="off" class="form-control" value="{{old('item_name')[$i]}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]">
													<option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
													</select>
												</td>
												<td width="8%">
													<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$j}}" value="{{old('actual_quantity')[$i]}}"> 
													<input type="number" id="itmqty_{{$j}}" step="any" autocomplete="off" name="quantity[]" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
												</td>
												<td width="8%">
													<input type="hidden" name="actual_cost[]" id="itmactcst_{{$j}}" value="{{old('actual_cost')[$i]}}"> 
													<input type="number" id="itmcst_{{$j}}" autocomplete="off" step="any" name="cost[]" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{ old('cost')[$i]}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if(old('tax_code')[$i]=="SR") echo 'selected'; ?>>SR</option><option value="RC" <?php if(old('tax_code')[$i]=="RC") echo 'selected'; ?>>RC</option><option value="EX" <?php if(old('tax_code')[$i]=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if(old('tax_code')[$i]=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if(old('tax_include')[$i]==0) echo 'selected';?> value="0">No</option><option <?php if(old('tax_include')[$i]==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{ old('vatdiv')[$i]}}"><!--<div class="h5" id="vatdiv_{{$j}}"></div>--> 
													<input type="hidden" id="vat_{{$j}}" name="line_vat[]" class="form-control vat" value="{{old('line_vat')[$i]}}">
													<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{old('vatline_amt')[$i]}}">
													<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" >
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{ old('line_total')[$i]}}">
												</td>
												<td width="1%">
													
													@if($num==$i)
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
												<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info1</button>
											</div>
											
											<div id="loc">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$j}}">
													</div>
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
																	<td class="num"><input type="number" name="locqty[<?php echo $i-1;?>][]" value="{{$quantity}}" class="loc-qty-{{$i}}" data-id="{{$i}}">
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
									</div>
									
									<?php $i++; } } else { ?>
								@foreach($pitems as $item)
								@php $i++; @endphp
									<div class="itemdivChld">
									<?php 
											if($item->balance_quantity==0) {
												if($orderrow->is_fc==1) {
													$quantity = $actual_quantity = $item->quantity;
													$total_price = number_format($item->total_price / $orderrow->currency_rate,2, '.', '');
													$total += $total_price;
													$vattotal += ($total_price * $item->vat)/100;
													
													$vat_amount = round($item->vat_amount / $orderrow->currency_rate,2);
													$unit_price = $item->unit_price / $orderrow->currency_rate;
													
													$total_price_dh = $item->total_price;
													$total_dh += $total_price_dh;
													$vattotal_dh += ($total_price_dh * $item->vat)/100;
												} else {
													$quantity = $actual_quantity = $item->quantity;
													$total_price = $item->total_price;
													$total = $orderrow->total;//$total += $total_price;
													$vattotal += ($total_price * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = $item->vat_amount;
													$unit_price = $item->unit_price;
													
													$total_dh = $vattotal_dh = 0;
												}
											} else {
												if($orderrow->is_fc==1) {
													$quantity = $actual_quantity = $item->balance_quantity;
													$unit_price = $item->unit_price / $orderrow->currency_rate;
													$total_price = number_format($quantity * $unit_price,2, '.', '');
													$total += $total_price;
													$vattotal += ($total_price * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($total_price * $item->vat)/100,2);
													
													$total_price_dh = $quantity * $item->unit_price;
													$total_dh += $total_price_dh;
													$vattotal += ($total_price_dh * $item->vat)/100;
												} else {
													$quantity = $actual_quantity = $item->balance_quantity;
													$unit_price = $item->unit_price;
													$total_price = $quantity * $unit_price;
													$total += $total_price;
													$vattotal += ($total_price * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($total_price * $item->vat)/100,2);
													
													$total_dh = $vattotal_dh = 0;
												}
											}
										?>									
										<table border="0" class="table-dy-row">
											<tr>
												<td width="13%">
												<input type="hidden" name="item_wit[]" id="itmwit_{{$i}}" value="{{$item->width}}">
												<input type="hidden" name="item_lnt[]" id="itmlnt_{{$i}}" value="{{$item->length}}">    
												<input type="hidden" name="purchase_invoice_item_id[]" id="p_invitmid_{{$i}}" value="{{$item->id}}">
												<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
												<input type="text" id="itmcod_{{$i}}" name="item_code[]" value="{{$item->item_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
												</td>
												<td width="24%">
													<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</select>
												</td>
													@if($ismpqty==1)
													<td width="8%" class="itcodmp">
														<input type="number" id="itmmpqty_{{$i}}" autocomplete="off" step="any" name="mpquantity[]" class="form-control line-mpquantity" value="{{$item->mp_qty}}" placeholder="MP Qty.">
													</td>
													@endif
												<td width="8%">
													<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$i}}" value="{{($item->balance_quantity > 0)?$item->balance_quantity:$item->quantity}}">
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" step="any" class="form-control line-quantity" min="1" value="{{($item->balance_quantity > 0)?$item->balance_quantity:$item->quantity}}" ><!--NOV24-->
												</td>
												<td width="8%">
													<input type="hidden" name="actual_cost[]" id="itmactcst_{{$i}}" value="{{$unit_price}}">
													<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" class="form-control line-cost" value="{{$unit_price}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$i}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if($item->tax_code=="SR") echo 'selected'; ?>>SR</option><option value="EX" <?php if($item->tax_code=="EX") echo 'selected'; ?>>EX</option><option value="ZR" <?php if($item->tax_code=="ZR") echo 'selected'; ?>>ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$i}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if($item->tax_include==0) echo 'selected';?> value="0">No</option><option <?php if($item->tax_include==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:$item->pkno.'-'.$item->packing}}">
													<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control cost" value="{{$item->vat.'% - '.$vat_amount}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control cost" value="{{$item->vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$item->vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}" step="any" name="line_discount[]" class="form-control line-discount" placeholder="Discount">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$total_price}}">
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
											
											<?php if($formdata['dimension']==1) { ?>
								              <div id="itmInfo" style="float:left;padding-right:5px;">
									          <button type="button" id="itmInfo_{{$i}}" class="btn btn-primary btn-xs dimn-view">Dimension</button>
								            </div>
								             <?php } else { ?>
								            <input type="hidden" name="dimension" id="dimension">
								             <?php } ?>
											
											<div id="loc" style="float:left;padding-right:5px;">
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
												<div class="form-group"><input type="text" name="iloc[]" id="iloc_{{$i}}" style="border:none;color:#FFF;"></div><!--NOV24-->
											</div>
											
											<!--MAY25-->
            								<div id="batchdiv_1" style="float:left; padding-right:5px;" class="addBatchBtn">
            									<button type="button" id="btnBth_{{$i}}" class="btn btn-primary btn-xs batch-add" data-toggle="modal" data-target="#batch_modal">Add Batch</button>
            									<div class="form-group"><input type="text" name="batchNos[]" id="batchNos_{{$i}}" style="border:none;color:#FFF;" value="{{$batchitems[$item->id]['batches'] ?? ''}}"></div>
            									<input type="hidden" id="batchIds_{{$i}}" name="batchIds[]" value="{{$batchitems[$item->id]['ids'] ?? ''}}">
            									<input type="hidden" id="mfgDates_{{$i}}" name="mfgDates[]" value="{{$batchitems[$item->id]['mfgs'] ?? ''}}">
                                                <input type="hidden" id="expDates_{{$i}}" name="expDates[]" value="{{$batchitems[$item->id]['exps'] ?? ''}}">
                                                <input type="hidden" id="qtyBatchs_{{$i}}" name="qtyBatchs[]" value="{{$batchitems[$item->id]['qtys'] ?? ''}}">
                                                <input type="hidden" id="batchRem_{{$i}}" name="batchRem[]">
            								</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div class="dimnInfodivPrntItm" id="dimnInfodivPrntItm_{{$i}}">
												<div class="dimnInfodivChldItm">							
													<div class="table-responsive dimn-item-data" id="dimnitemData_{{$i}}"></div>
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
																<?php $m=0; $tQty = 0; $qty_entry = '';//NOV24
																foreach($itemloc as $key => $row) {
																$quantity = $id = '';
																$location_id = $row->id; $cqty = $row->cqty;
																if(isset($itemlocedit[$item->id][$m]) && $row->id==$itemlocedit[$item->id][$m]->location_id) {
																	$cqty = $itemlocedit[$item->id][$m]->cqty;
																	$quantity = $itemlocedit[$item->id][$m]->quantity;
																	$location_id = $itemlocedit[$item->id][$m]->location_id;
																	$id = $itemlocedit[$item->id][$m]->id;
																	
																	$qty_entry = $itemlocedit[$item->id][$m]->qty_entry;
																	$tQty += $cqty; //NOV24
																	$m++;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td>{{ $cqty }}</td>
																	<td class="num"><input type="number" name="locqty[<?php echo $i-1;?>][]" value="{{$qty_entry}}" class="loc-qty-{{$i}}" data-id="{{$i}}">
																	<input type="hidden" name="locid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="editid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
																</tr>
																<?php } ?>
																</tbody>
															</table>
														</div><script>document.getElementById('iloc_'+{{$i}}).value='{{$tQty}}';</script> <!--NOV24-->
															<?php  } ?>
													</div>
												</div>
											</div>
									</div>
								
								@endforeach
								<?php } ?>
								</div>
								</fieldset>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span> <input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{(old('total')?old('total'):$orderrow->is_fc==1)?$orderrow->total_fc:$orderrow->total}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span> <input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" value="{{(old('total_fc'))?old('total_fc'):$orderrow->total}}" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control spl discount-cal" value="{{(old('discount')?old('discount'):$orderrow->is_fc==1)?$orderrow->discount_fc:$orderrow->discount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control spl" value="{{(old('discount_fc'))?old('discount_fc'):$orderrow->discount}}" placeholder="0">
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
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal')?old('subtotal'):$orderrow->is_fc==1)?$orderrow->subtotal_fc:$orderrow->subtotal}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" value="{{(old('subtotal_fc'))?old('subtotal_fc'):$orderrow->subtotal}}" readonly placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="{{(old('vat')?old('vat'):$orderrow->is_fc==1)?$orderrow->vat_amount_fc:$orderrow->vat_amount}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" placeholder="0" value="{{(old('vat_fc'))?old('vat_fc'):$orderrow->vat_amount}}" readonly>
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" name="pi_amount" value="{{(old('pi_amount')?old('pi_amount'):$orderrow->is_fc==1)?$orderrow->net_amount_fc:$orderrow->net_amount}}">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{(old('net_amount')?old('net_amount'):$orderrow->is_fc==1)?$orderrow->net_amount_fc:$orderrow->net_amount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly value="{{(old('net_amount_fc'))?old('net_amount_fc'):$orderrow->net_amount}}" placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('purchase_return') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                           <div id="supplier_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Supplier</h4>
                                        </div>
                                        <div class="modal-body" id="supplierData">
                                            
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
							
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--MAY25-->
            <div id="batch_modal" class="modal fade animated" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add Batch</h4>
                        </div>
                        <div class="modal-body" id="batchData">
                            <div class="row">
                                <table class="table horizontal_table" id="batchTable">
                                    <thead>
                                    <tr>
                                        <th>Batch No</th>
                                        <th>Mfg. Date</th>
                                        <th>Exp. Date</th>
                                        <th>Qty.</th>
                                        <th><button class="btn btn-success btn-xs funAddBacthRow" data-id="1" data-no="1"><i class="fa fa-fw fa-plus-circle"></i></button></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="btno"><input type="text" size="10" id="bthno_1" class="bno" name="batch_no" autocomplete="off"></td>
                                        <td class="mfdt"><input type="text" size="12" id="bthmfg_1" name="mfg_date" readonly data-language='en' class="mfg-date" autocomplete="off"></td>
                                        <td class="exdt"><input type="text" size="12" id="bthexp_1" name="exp_date" readonly data-language='en' class="exp-date" autocomplete="off"></td>
                                        <td class="bqty"><input type="text" size="8" id="bthqty_1" name="qty" class="bth-qty" autocomplete="off"></td>
                                        <td class="del"></td> 
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary saveBatch">Save</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
//MAY25
var lprice; var lqy;
let bthArr = [];
let uniqueBtharr;
$('.mfg-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
$('.exp-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
var lqy;//MAY25

"use strict";
var srvat={{$vatdata->percentage}};
$('.dimnInfodivPrntItm').toggle();
$(document).ready(function () { 
	
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	
	<?php if($orderrow->is_fc==0) { ?>
		$("#currency_rate").prop('disabled', true);
		$("#currency_id").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); 
		$("#net_amount_fc").toggle();$("#vat_fc").toggle(); $(".oc-amount-fc").toggle();$("#subtotal_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	
	<?php if($orderrow->other_cost != 0 || $orderrow->other_cost_fc != 0) { ?>
		if( $('.OCdivPrnt').is(":hidden") )
			$('.OCdivPrnt').toggle(); 
	<?php } ?>
	$('.infodivPrntItm').toggle();  $('.locPrntItm').toggle();
	var urlcode = "{{ url('purchase_invoice/checkrefno/') }}";
    /* $('#frmPurReturn').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			stock_account: { validators: { notEmpty: { message: 'The stock account is required and cannot be empty!' } }},
			reference_no: {
                validators: {
                    notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			//supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			//'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurReturn').data('bootstrapValidator').resetForm();
    }); */
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $('.oc-amount-fc').toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $('.oc-amount-fc').toggle();
	});
	
	$('.import').on('ifChecked', function(event){ 
		$('.tax-code').val('RC');
	});
	
	$('.import').on('ifUnchecked', function(event){
		$('.tax-code').val('SR');
	});
	
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		
		var lineTotal = 0;
		$( '.line-total' ).each(function() { //$( '.itemdivChld' ).each(function() { //console.log('lineTotal: '+lineTotal);
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		//console.log('lineTotal: '+lineTotal);
		$('#total').val(lineTotal.toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
		//new change..
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		//console.log('taxnew: '+vatcur);
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
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var supplier_id = $('#supplier_id').val();
		$.ajax({
			url: "{{ url('itemmaster/get_purchase_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&supplier_id='+supplier_id,
			success: function(data) {
				$('#itmcst_'+curNum).val(data);
				return true;
			}
		}) 
	}
	
	function resetValues(n) { 
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
	
	//calculation other cost...
	function getOtherCost() {
		
		var otherCost = 0; var otherCostTx = 0;
		if( $('.OCdivPrnt').is(":visible") ) { 
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var curNum = res[1];
				var ocVat = parseFloat( ($('#vatocamt_'+curNum).val()=='')?0:$('#vatocamt_'+curNum).val() );
				var ocvatamt = (this.value * ocVat) / 100;
				otherCostTx = otherCost + parseFloat(this.value) + ocvatamt;
				otherCost = otherCost + parseFloat(this.value);
			});
			$('#other_cost').val(otherCostTx);
		}
		
		if(otherCost!=0) {
			var totalQty = 0;
			 $('input[name="quantity[]"]').each(function(){
				totalQty += +$(this).val();
			 });
			 
			 var totalCost = parseFloat( ($('#total').val()=='')?0:$('#total').val() );
			 var n = 1;
			$( '.itemdivChld' ).each( function() { 
				var costPerUnit; var nTotal;
				var itemCost = parseFloat( ($('#itmcst_'+n).val()=='')?0:$('#itmcst_'+n).val() );
				var itemQty = parseFloat( ($('#itmqty_'+n).val()=='')?0:$('#itmqty_'+n).val() );
				
				costPerUnit = (otherCost / totalCost) * itemCost;
				$('#othrcst_'+n).val(costPerUnit.toFixed(2));
				$('#netcst_'+n).val( (costPerUnit + itemCost).toFixed(2) );
				n++;
			});
		}
		
		return true;
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

$(function() {	
	var rowNum = $('#rowNum').val();
	//$('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
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
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.oc')).attr('id', 'othrcstItm_' + rowNum);
			newEntry.find($('.net-cost')).attr('id', 'ocost_' + rowNum);
			newEntry.find('input').val(''); 
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			$('#locData_'+rowNum).html('');
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('.taxinclude')).attr('id', 'txincld_' + rowNum);//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			newEntry.find($('.dimnInfodivPrntItm')).attr('id', 'dimnInfodivPrntItm_' + rowNum);
	      	newEntry.find($('.dimn-item-data')).attr('id', 'dimnitemData_' + rowNum);
			newEntry.find($('.dimn-view')).attr('id', 'itmInfo_' + rowNum);
			
			newEntry.find($('input[name="mpquantity[]"]')).attr('id', 'itmmpqty_' + rowNum);
			newEntry.find($('input[name="item_wit[]"]')).attr('id', 'itmwit_' + rowNum);
			newEntry.find($('input[name="item_lnt[]"]')).attr('id', 'itmlnt_' + rowNum);
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.import').is(":checked") ) { 
				$('.tax-code').val('RC');
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
										.attr("value",key)
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
										.attr("value",key)
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
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#p_invitmid_'+curNum).val():remitem+','+$('#p_invitmid_'+curNum).val();
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
	
	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function() {
		$('#supplierData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
		var joburl = "{{ url('jobmaster/job_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
			/* if($('#p_invitmid_'+curNum).val()!='')
				resetValues(curNum); */
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
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		if(parseFloat(this.value) > parseFloat( $('#itmactqty_'+curNum).val() )) {
			alert('Quantity should not exceed than Invoice.');
			$('#itmqty_'+curNum).val( $('#itmactqty_'+curNum).val() );
		}

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
	
	

	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_invoice/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			$('#voucher_no').attr('placeholder', data.voucher_no); //$('#voucher_no').val(data.voucher_no);
			$('#stock_account').val(data.account_id+'-'+data.account_name);
			$('#account_master_id').val(data.id);
		});
	});
	
	$(document).on('blur', '#purchase_invoice_no', function(e) { 
		var id = this.value; //console.log(id);
		if(id!='') {
			var urlcode = "{{ url('purchase_invoice/check_invoice/') }}";
			$.ajax({
				url: urlcode,
				data: 'purchase_invoice_no='+id,
				success: function(result) {
					if(result) {
						$('#is_prior').val(1);
						alert('Purchase Invoice No is not found. Do you want treat it as prior year Transaction?');
					}
				}
			});
		} else
			return true;
	});
	
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; //console.log(unit_id);
		var res = this.id.split('_');
		var curNum = res[1];
		$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id, function(data) {
			$('#vat_'+curNum).val(data);
			$('#vatdiv_'+curNum).val(data+'%');
			
			var res = getLineTotal(curNum);
			if(res) 
				getNetTotal();
		
		});
	});
	
	//CHNG
	$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#itmwit_'+num).val( $(this).attr("data-wit") );
		$('#itmlnt_'+num).val( $(this).attr("data-lnt") );
		
		$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
		$('#vat_'+num).val( $(this).attr("data-vat") );
		$('#itmcst_'+num).val( $(this).attr("data-price") );
		srvat = $(this).attr("data-vat");
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
		    $('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').end()
			 .append($("<option></option>")
						.attr("value",key)
						.text(value.unit_name)); 
			});

		});
	});
	
		$(document).on('blur','.line-mpquantity', function() {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qtywt = this.value * $('#itmwit_'+curNum).val() * $('#itmlnt_'+curNum).val();
		$('#itmqty_'+curNum).val(qtywt);
	})
	
	$(document).on('click', '.dimn-view', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_dimn_info/') }}/"+item_id;
		   $('#dimnitemData_'+curNum).load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#dimnInfodivPrntItm_'+curNum).toggle();
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
	
	/* $(document).on('click', '.loc-info', function(e) { 
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
    }); */
	
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
				
				/*$('#itmunt_'+curNum).find('option').remove().end();
				$('#itmunt_'+curNum).find('option').end()
					 .append($("<option></option>")
								.attr("value",data.unit_id)
								.text(data.unit));*/
								
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
							.text(value.unit_name)); 
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
							.text(value.unit_name));  $('#hidunit_'+curNum).val(value);
				});
			});
		}	
	});
	
	//Supplier search...
	var acmst = "{{ url('account_master/ajax_account/') }}";
	$('#supplier_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: acmst,
                dataType: "json",
                data: {
                    term : request.term, category : 'SUPPLIER'
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			$("#supplier_id").val(ui.item.id);
		},
        minLength: 2,
    });
	
	$(document).on('keyup', '.num :input[type="number"]', function(e) {
		var itQty = 0; var curNum = $(this).data('id');
		$('.loc-qty-'+curNum).each(function() { 
			itQty += parseFloat( (this.value=='')?0:this.value );
		});
		$('#itmqty_'+curNum).val(itQty);
		
		var isPrice = getAutoPrice(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
		$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
		//if( $('#locPrntItm_'+rowNum).is(":visible") ) 
			$('#locPrntItm_'+rowNum).toggle();
		
	});

	$(document).on('blur', '#voucher_no', function(e) {  
		if(parseInt($(this).val()) > parseInt($('#curno').val())) {
			alert('Voucher no is greater than current range!');
			$('#voucher_no').val('');
		}
	});

	//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('purchase_return/checkvchrno/') }}", 
			type: 'get',
			data: 'voucher_no='+this.value+'&deptid='+deptid+'&id=',
			success: function(data) { 
				data = $.parseJSON(data);
				if(data.valid==false) {
					alert('Voucher No already exist!');
					$('#voucher_no').val('');
					return false;
				}
				//console.log('ff '+data.valid);
			}
		}); 
	})
	//VOUCHER NO DUPLICATE OR NOT
	
	//MAY25  BATCH ENTRY....
	var icount = 0;
    $(document).on('click', '.funAddBacthRow', function(e) {
        e.preventDefault();
        icount++;
        var btno = parseInt($('#bth_count').val())+parseInt(icount);
        
       var table = $('#batchTable');
       var clonedRow = $('#batchTable tbody tr:first').clone();
       clonedRow.find('input').val('');
       clonedRow.find('.btno').html('<input type="text" size="10" id="bthno_'+btno+'" class="bno" name="batch_no" autocomplete="off">'); 
       clonedRow.find('.mfdt').html('<input type="text" size="12" id="bthmfg_'+btno+'" class="mfg-date" data-language="en" name="mfg_date" readonly autocomplete="off">');
       clonedRow.find('.exdt').html('<input type="text" size="12" id="bthexp_'+btno+'" class="exp-date" data-language="en" name="exp_date" readonly  autocomplete="off">');
       clonedRow.find('.bqty').html('<input type="text" size="8" id="bthqty_'+btno+'" class="bth-qty" name="qty" autocomplete="off">');
       clonedRow.find('.del').html('<button class="btn btn-danger btn-xs funRemove" data-id="'+btno+'" data-no="'+btno+'"><i class="fa fa-fw fa-times-circle"></i></button>');
       
       table.append(clonedRow);
       
       clonedRow.find($('.mfg-date')).datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
       clonedRow.find($('.exp-date')).datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
    });
    
	$(document).on('click', '.batch-add', function(e) { 
       
       var res = this.id.split('_');
	   var n = res[1];
	   
       var batch = $('#batchNos_'+n).val(); 
       var mfgdate = $('#mfgDates_'+n).val(); 
       var expdate = $('#expDates_'+n).val(); 
       var btqty = $('#qtyBatchs_'+n).val(); 
       var ids = $('#batchIds_'+n).val(); 
       var batchurl = "{{ url('itemmaster/batch-view') }}";
       
       if(batch!='') {
    	   var vwUrl = batchurl+'?batch='+batch+'&mfg_date='+mfgdate+'&exp_date='+expdate+'&qty='+btqty+'&ids='+ids+'&act=edit&no='+n; 
    	   $('#batchData').load(vwUrl, function(result) {
    		  $('#myModal').modal({show:true}); 
    		  $('.funAddBacthRow').attr('disabled',true);
    	   });
       } else {
           $('#batchData').html(`<div class="row">
                                    <table class="table horizontal_table" id="batchTable">
                                        <thead>
                                        <tr>
                                            <th>Batch No</th>
                                            <th>Mfg. Date</th>
                                            <th>Exp. Date</th>
                                            <th>Qty. <input type="hidden" id="row_no" value="${n}"></th>
                                            <th><button class="btn btn-success btn-xs funAddBacthRow" data-id="1" data-no="1"><i class="fa fa-fw fa-plus-circle"></i></button></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="btno"><input type="text" size="10" id="bthno_1" class="bno" name="batch_no" autocomplete="off"></td>
                                            <td class="mfdt"><input type="text" size="12" id="bthmfg_1" name="mfg_date" readonly data-language='en' class="mfg-date" autocomplete="off"></td>
                                            <td class="exdt"><input type="text" size="12" id="bthexp_1" name="exp_date" readonly data-language='en' class="exp-date" autocomplete="off"></td>
                                            <td class="bqty"><input type="text" size="8" id="bthqty_1" name="qty" class="bth-qty" autocomplete="off"></td>
                                            <td class="del"></td> 
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>`);
                                
                                $('.mfg-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
                                $('.exp-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
       }
    });
    
    
    var remId = '';
    $(document).on('click', '.funRemove', function(e)  { 
    e.preventDefault();
    var rowNo = $('#row_no').val();
      
    remId = $('#batchRem_'+rowNo).val();
    $('#batchRem_'+rowNo).val( (remId=='')?$('#bthid_'+$(this).attr("data-id")).val():remId+','+$('#bthid_'+$(this).attr("data-id")).val() );

      
      var remBno = $('#bthno_'+$(this).attr("data-id")).val();
      bthArr = bthArr.filter(item => item !== remBno);

      $(this).closest('tr').remove();
    });
    
    
    $(document).on('click', '.saveBatch', function(e)  { 
       e.preventDefault();
       
       var rowNo = $('#row_no').val();
       var is_batch = true; 
       var batchNo = '';
       var mfgDate = '';
       var expDate = '';
       var qtyBatch = '';
       var totalQty = parseFloat(0);
       
       $('.bno').each(function() { 
    	  var res = this.id.split('_');
    	  var n = res[1];
    	  if($('#bthno_'+n).val()=='')
    	    is_batch = false;
    	  else
    	    batchNo = (batchNo=='')?$('#bthno_'+n).val():batchNo+','+$('#bthno_'+n).val();
    	    
    	    bthArr.push($('#bthno_'+n).val());
    	    uniqueBtharr = bthArr.filter((value, index, self) => self.indexOf(value) === index);

    	});
    	
       var is_mfdate = true;
       $('.mfg-date').each(function() { 
    	  var res = this.id.split('_');
    	  var n = res[1];
    	  if($('#bthmfg_'+n).val()=='')
    	    is_mfdate = false;
    	  else
    	    mfgDate = (mfgDate=='')?$('#bthmfg_'+n).val():mfgDate+','+$('#bthmfg_'+n).val();
    	});
    	
       var is_exdate = true;
       $('.exp-date').each(function() { 
    	  var res = this.id.split('_');
    	  var n = res[1];
    	  if($('#bthexp_'+n).val()=='')
    	    is_exdate = false;
    	  else
    	    expDate = (expDate=='')?$('#bthexp_'+n).val():expDate+','+$('#bthexp_'+n).val();
    	});
    	
       var is_qty = true;
       $('.bth-qty').each(function() { 
    	  var res = this.id.split('_');
    	  var n = res[1];
    	  if($('#bthqty_'+n).val()=='')
    	    is_qty = false;
    	  else {
    	    qtyBatch = (qtyBatch=='')?$('#bthqty_'+n).val():qtyBatch+','+$('#bthqty_'+n).val();
    	    totalQty += parseFloat($('#bthqty_'+n).val());
    	  }
    	});
    	
      if(is_batch==false || is_mfdate==false || is_exdate==false || is_qty==false) {
        alert('All the batch entries are required!');
        return false;
      }
      
      if(is_batch==true || is_mfdate==true || is_exdate==true || is_qty==true) {
          
          //JUL25
           var fmla = $('#packing_'+rowNo).val();
           if(fmla!=1) {
              var res = fmla.split('-');
        	  var pkn = res[0];
        	  var pkng = res[1]
        	  
        	  totalQty = totalQty / (pkn * pkng);
           } else {
               totalQty = totalQty*fmla;
           }
           //....
           
          $('#batchNos_'+rowNo).val(batchNo);
          $('#mfgDates_'+rowNo).val(mfgDate);
          $('#expDates_'+rowNo).val(expDate);
          $('#qtyBatchs_'+rowNo).val(qtyBatch);
          $('#itmqty_'+rowNo).val(totalQty);
          var table = $('#batchTable');
          table.find('input').val('');
          $('#batch_modal').modal('hide');
      }
        $('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'batchNos[]');
       // console.log('r2 '+uniqueBtharr)
    });
    
    
    $(document).on('blur', '.bno', function(e)  { 
    
        var res = this.id.split('_');
    	var curid = res[1];
        var curNo = $(this).val();
        
        
    	 $.each(uniqueBtharr, function(index, bhno) {
            
            if(curNo==bhno) {
                alert('Batch No is duplicate!');
    			$('#bthno_'+curid).val('');
    		}
    		
        });

        $('.bno').each(function() {
    		var r = this.id.split('_');
    		var runid = r[1];
    		var runNo = $('#bthno_'+runid).val();
    		if(curNo==runNo && curid != runid) {
    			alert('Batch No is duplicate!');
    			$('#bthno_'+curid).val('');
    		} 
    	});
    	
    	if(curNo!='') {
        	$.ajax({
        		url: "{{ url('itemmaster/check_batchno/') }}",
        		type: 'get',
        		data: 'batch_no='+curNo+'&id=0',
        		success: function(data) { 
        			console.log(data)
        			if(data==1) {
        			    alert('Batch No already exist!');
        			    $('#bthno_'+curid).val('');
        			    return false;
        			}
        			
        		}
        	}); 
    	}
    });
    
    function parseDMY(dateStr) {
        var parts = dateStr.split("-");
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }
        
    $(document).on('blur', '.exp-date', function(e)  {  
        var res = this.id.split('_');
    	var n = res[1];
        var mfg_date = $('#bthmfg_'+n).val();
        var exp_date = $(this).val(); 
        
        if(parseDMY(exp_date) <= parseDMY(mfg_date)) {
           alert('Exp. date should be greater than Mfg. date!');
           $('#bthexp_'+n).val('');
           return false;
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

function getPurchaseInvoice(e) {
	if(e.value=='') { 
		var ht = $(window).height();
		var wt = $(window).width();
		var pourl = "{{ url('purchase_invoice/pi_data/') }}";
		popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
		popup.focus();
		return false
	}
}

</script>
@stop
