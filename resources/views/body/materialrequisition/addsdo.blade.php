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
		
		.ui-helper-hidden-accessible{
			display:none !important;
		}
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
         <section class="content-header">
            <!--section starts-->
            @foreach($mod_purchase_enquiry as $mod_purchase_enquiry)
            <h1>
            	
               <?php  if($mod_purchase_enquiry==1)
				$heading="Purchase Enquiry";
			          else 
				$heading="Material Requisition"; echo "$heading"; ?>

            </h1>
            
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#"><?php echo "$heading"; ?></a>
                </li>
                <li class="active">
                    Add
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
                                <i class="fa fa-fw fa-crosshairs"></i> New <?php echo "$heading"; ?>
                            </h3>
                        </div>
        @endforeach
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" action="{{ url('material_requisition/save/'.$docrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">MR. No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" readonly value="{{$docrow->voucher_no}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">MR. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly value="{{date('d-m-Y',strtotime($docrow->voucher_date))}}" id="voucher_date" placeholder="GI. Date"/>
                                    </div>
                                </div>
								
							
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="<?php echo (old('supplier_name'))?old('supplier_name'):$docrow->supplier; ?>" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" readonly autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo (old('supplier_id'))?old('supplier_id'):$docrow->supplier_id; ?>">
									</div>
                                </div>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type</label>
                                    <div class="col-sm-10">
                                       <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
                                           <option value="">Select Document...</option>
										   <option value="PO" <?php if($doctype=='PO') echo 'selected';?>>Purchase Order</option>
										   <option value="MR" <?php if($doctype=='MR') echo 'selected';?>>MaterialRequisition</option>
										   <option value="SDO" <?php if($doctype=='SDO') echo 'selected';?>>Supplier Delivery Order</option>
                                       </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_type" id="document_type">
								<?php } ?>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document#</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="document" readonly name="document" value="{{old('document')?old('document'):$docrow->voucher_no}}" autocomplete="off" onclick="getDocument()">
										<input type="hidden" id="document_id" name="document_id" value="{{old('document_id')?old('document_id'):$pordid}}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php } ?>
								
								
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="description" value="<?php echo (old('description'))?old('description'):$docrow->description; ?>" name="description" placeholder="Description">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								
								
								<?php if($formdata['jobname']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											@if($docrow->job_id==$job['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $job['id'] }}" {{$sel}}>{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								 <?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Engineer</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Engineer">
										<input type="hidden" name="salesman_id" id="salesman_id" value="{{ old('salesman_id') }}">
									</div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="salesman" id="salesman">
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-10">
                                        <select id="location_id" class="form-control select2" style="width:100%" name="location_id">
										<option value="" selected>Select Location..</option>
											<?php 
											foreach($location as $loc) { 
											?>
											<option value="{{ $loc['id'] }}" <?php if($loc['id']==old('location_id')) echo 'selected'; ?>>{{ $loc['name'] }}</option>
											<?php } ?>
                                        </select>
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
								{{--*/ $i = 0; $num = count($docitems); $total = $vattotal = $nettotal = $nettotal_dh = $total_dh = $vattotal_dh = 0; /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								<?php if(old('item_code')) { $i = 0; 
									foreach(old('item_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													@if($doctype=='PO' || $doctype=='MR')
													<input type="hidden" name="purchase_order_item_id[]" id="p_orditmid_{{$j}}" value="{{old('purchase_order_item_id')[$i]}}">
													@elseif($doctype=='SDO')
													<input type="hidden" name="supplier_do_item_id[]" id="sdo_itemid_{{$j}}" value="{{old('supplier_do_item_id')[$i]}}">
													@endif
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
													<input type="number" id="itmqty_{{$j}}" step="any" name="quantity[]" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
												</td>
												<td width="8%">
													<span class="small <?php if($errors->has('cost.'.$i)) echo 'form-error';?>">Cost/Unit</span> <input type="number" id="itmcst_{{$j}}" step="any" name="cost[]" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{ old('cost')[$i]}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
													<option value="SR" <?php if(old('tax_code')[$i]=="SR") echo 'selected'; ?>>SR</option>
													<option value="RC" <?php if(old('tax_code')[$i]=="RC") echo 'selected'; ?>>RC</option>
													<option value="EX" <?php if(old('tax_code')[$i]=="EX") echo 'selected'; ?>>EX</option>
													<option value="ZR" <?php if(old('tax_code')[$i]=="ZR") echo 'selected'; ?>>ZR</option>
													</select>
												</td>
												<td width="6%">
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if(old('tax_include')[$i]==0) echo 'selected';?> value="0">No</option><option <?php if(old('tax_include')[$i]==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
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
										
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$j}}"></div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$j}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$j}}"></div>
												</div>
											</div>
									</div>
								<?php $i++; } } else { ?>
								@foreach($docitems as $poitem)
								{{--*/ $i++; /*--}}
								
									<div class="itemdivChld">
										<?php 
											if($poitem->balance_quantity==0) {
												if($docrow->is_fc==1) {
													$quantity = $actual_quantity = $poitem->quantity;
													$total_price = number_format($poitem->total_price );
													$total += $total_price;
													$vattotal += ($total_price * $poitem->vat_amount)/100;
													
													$vat_amount = round($poitem->vat_amount );
													$unit_price = $poitem->unit_price ;
													
													$total_price_dh = $poitem->total_price;
													$total_dh += $total_price_dh;
													$vattotal_dh += ($total_price_dh * $poitem->vat)/100;
												} else {
													$quantity = $actual_quantity = $poitem->quantity;
													$total_price = $poitem->total_price;
													$total = $docrow->total;//$total += $total_price;
													$vattotal += ($total_price * $poitem->vat_amount)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = $poitem->vat_amount;
													$unit_price = $poitem->unit_price;
													
													$total_dh = $vattotal_dh = 0;
												}
											} else {
												if($docrow->is_fc==1) {
													$quantity = $actual_quantity = $poitem->balance_quantity;
													$unit_price = $poitem->unit_price ;
													$total_price = number_format($quantity * $unit_price,2, '.', '');
													$total += $total_price;
													$vattotal += ($total_price * $poitem->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($total_price * $poitem->vat)/100,2);
													
													$total_price_dh = $quantity * $poitem->unit_price;
													$total_dh += $total_price_dh;
													$vattotal += ($total_price_dh * $poitem->vat)/100;
												} else {
													$quantity = $actual_quantity = $poitem->balance_quantity;
													$unit_price = $poitem->unit_price;
													$total_price = $quantity * $unit_price;
													$total += $total_price;
													$vattotal += ($total_price * $poitem->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($total_price * $poitem->vat)/100,2);
													
													$total_dh = $vattotal_dh = 0;
												}
											}
											$vat = ($doctype=='MR')?5:$poitem->vat;
										?>
										
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													@if($doctype=='PO' || $doctype=='MR')
													<input type="hidden" name="purchase_order_item_id[]" id="p_orditmid_{{$i}}" value="{{$poitem->id}}">
													@elseif($doctype=='SDO')
													<input type="hidden" name="supplier_do_item_id[]" id="sdo_itemid_{{$i}}" value="{{$poitem->id}}">
													@endif
													<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$poitem->item_id}}">
													<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" value="{{$poitem->item_code}}">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" data-toggle="modal" data-target="#item_modal" value="{{$poitem->item_name}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$poitem->unit_id}}">{{$poitem->unit_name}}</option></select>
												</td>
												<td width="8%">
													<input type="hidden" name="actual_quantity[]" id="itmactqty_1" value="{{$actual_quantity}}"> 
													<input type="number" id="itmqty_{{$i}}" step="any" autocomplete="off" name="quantity[]" class="form-control line-quantity" value="{{$poitem->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$i}}" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" value="{{$unit_price}}">
												</td>
												<td width="6%">
													<select id="taxcode_{{$i}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if($poitem->tax_code=="SR") echo 'selected'; ?>>SR</option><option value="RC" <?php if($poitem->tax_code=="RC") echo 'selected'; ?>>RC</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$i}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if($poitem->tax_include==0) echo 'selected';?> value="0">No</option><option <?php if($poitem->tax_include==1) echo 'selected';?> value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($poitem->is_baseqty==1)?1:$poitem->packing}}">
													<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{$vat.'% - '.intval($poitem->vat_amount*100)/100}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control vat" value="{{$vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$poitem->vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$poitem->item_total}}">
													<input type="hidden" id="itmtot_{{$i}}" name="item_total[]" value="{{$poitem->item_total}}">
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
												
										
											
											
											<!-- <div class="locPrntItm" id="locPrntItm_{{$i}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$i}}">
													<?php 
														//if(array_key_exists($poitem->id, $itemlocedit)) { 
														
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
														
																
																<?php //} ?>
																</tbody>
															</table>
														</div>
															<?php  //} ?>
													</div>
												</div>
											</div>
											 -->
				
										
									</div>
								@endforeach
								<?php } ?>
								</div>
								<?php $nettotal += $total + $vattotal - $docrow->discount; $nettotal_dh += $total_dh + $vattotal_dh;?>
								</fieldset>
								
								
							
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{(old('total'))?old('total'):(($docrow->is_fc==1)?$docrow->total_fc:$docrow->total)}}">
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
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):(($docrow->is_fc==1)?$docrow->subtotal_fc:$docrow->subtotal)}}">
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
											<input type="number" readonly step="any" id="discount" name="discount" class="form-control spl discount-cal" autocomplete="off" value="{{(old('discount'))?old('discount'):$docrow->discount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" readonly step="any" id="discount_fc" name="discount_fc" class="form-control spl" autocomplete="off" placeholder="0">
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
											<input type="hidden" id="vatcur" name="vatcur" value="{{(old('vatcur'))?old('vatcur'):$docrow->vat_amount}}">
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="{{(old('vat'))?old('vat'):intval($vattotal*100)/100}}" >
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
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl"  value="{{(old('net_amount'))?old('net_amount'):(($docrow->is_fc==1)?$docrow->net_amount_fc:$docrow->net_amount)}}">
										</div>
									
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('purchase_invoice') }}" class="btn btn-danger">Cancel</a>
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

$(document).ready(function () { 
	
$('#frmPurchaseInvoice').bootstrapValidator({
        fields: {
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			jobname: { validators: { notEmpty: { message: 'The job name is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }}
			//'cost[]': { validators: { notEmpty: { message: 'The item cost is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurchaseInvoice').data('bootstrapValidator').resetForm();
    });
	
	$('input').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	$('input').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		var n = 1; var lineTotal = 0;
		$( '.itemdivChld' ).each(function() {
		  lineTotal = lineTotal + getLineTotal(n);
		  n++;
		});
		$('#total').val(lineTotal.toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		//var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total;// + vat;
		$('#net_amount').val(netTotal.toFixed(2));

	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log(n);
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );

		var lineDiscount = parseFloat( ($('#itmdsnt_'+n).val()=='') ? 0 : $('#itmdsnt_'+n).val() );
		var lineTotal 	 = ( lineQuantity * lineCost ) - lineDiscount;
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		
		return lineTotal;
	} 
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost_avg/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&customer_id=',
			success: function(data) {
				$('#itmcst_'+curNum).val((data==0)?'':data);
				$('#itmcst_'+curNum).focus();
				return true;
			}
		}) 
	}
	
	function getDocument() { 
	var supplier_id = $("#supplier_id").val();
	var doc = $('#document_type option:selected').val();
	
	if(doc=='PO' && $("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	
	if(doc=='PO')
		var pourl = "{{ url('purchase_order/po_datatrans/') }}/"+supplier_id+"/PO";
	else if(doc=='SDO')
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/SDO";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
//
$(function() {	
	var dat = new Date();
    $('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	var rowNum = 1;
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
		   $('input[name="dr_acnt[]"]').val( $('#purchase_account').val() );
		   $('input[name="dr_acnt_id[]"]').val( $('#account_master_id').val() );
           $('.OCdivPrnt').toggle();
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
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); //new change
			newEntry.find($('input[name="othr_cost[]"]')).attr('id', 'othrcst_' + rowNum);
			newEntry.find($('input[name="net_cost[]"]')).attr('id', 'netcst_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); //NEW CHNG
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			
			newEntry.find('input').val(''); 
			newEntry.find('select').find('option').remove().end().append('<option value="">Select</option>');//new change
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		var vat = 0;
		$('.vatline-amt').each(function() {
			vat = vat + parseFloat(this.value);
		});
		$('#total').val(btotal); $('#vat').val(vat);
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		$('#net_amount').val(btotal - bdiscount + vat);
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		$('#jobname').val($(this).attr("data-name"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	var supurl;
	$('#supplier_name').click(function() {
		supurl = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();  console.log('no '+num);
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
		$('#vat_'+num).val( $(this).attr("data-vat") );
		$('#itmcst_'+num).val( $(this).attr("data-price") );
		
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
	
	var acurl = "{{ url('account_master/expenseac_data/') }}";
	$(document).on('click', 'input[name="job_account"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#accountData').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.stockRow', function(e) {
		$('#job_account').val($(this).attr("data-name"));
		$('#job_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
		
	//new change............
	var stockurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="stock_account"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#stockacData').load(stockurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRow', function(e) {
		$('#stock_account').val($(this).attr("data-name"));
		$('#account_master_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		/* var isPrice = getAutoPrice(curNum);
		if(isPrice){
			var lntot = getLineTotal(curNum);
				getNetTotal();
		} */
	});
	
	$(document).on('keyup', '.line-quantity', function(e) {
		getNetTotal();
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-discount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	
	//total discount section.........
	$(document).on('blur', '#discount', function(e) { 
		getNetTotal();
	});

	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('goods_issued/getvoucher/') }}/" + vchr_id, function(data) { 
			$('#voucher_no').val(data.voucher_no);
			
			//if(data.cr_account_id!=null || data.dr_account_id!=null) {
				$('#stock_account').val(data.cr_account_name);
				$('#account_master_id').val(data.cr_id);
				
				$('#job_account').val(data.dr_account_name);
				$('#job_account_id').val(data.dr_id);
			//} 
		});
	});
	$(document).on('change', '.oc-curr', function(e) { //$('.oc-curr').on('change', function(e){
		var res = this.id.split('_');
		var curNum = res[1]; 
		var curr_id = e.target.value; 
		
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#ocrate_'+curNum).val((data==0)?'':data);
			var res = getOtherCost();
			if(res) 
				getNetTotal();
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
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/view_locinfo/') }}/"+item_id+"/"+curNum
		   $('#locData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#locPrntItm_'+curNum).toggle();
	   }
    });
	$('#document_type').on('change', function(e){
		var dpt_id = ($('#department_id').length)?$('#department_id option:selected').val():'';
		var vchr_id = $('#voucher_id').val();
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		var pur_ac = $('#purchase_account').val();
		var ac_mstr = $('#account_master_id').val();
		
		$.ajax({
			url: "{{ url('purchase_invoice/set_session/') }}",
			type: 'post',
			data: {'vchr_no':vchr_no,'vchr_id':vchr_id,'ref_no':ref_no,'vchr_dt':vchr_dt,'lpo_dt':lpo_dt,'pur_ac':pur_ac,'ac_mstr':ac_mstr,'dpt_id':dpt_id},
			success: function(data) { //console.log(data);
			}
		}) 
	});

	/* var smurl = "{{ url('sales_order/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(smurl, function(result) { */

	var smpurl = "{{ url('sales_order/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(smpurl, function(result) {

			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
});
	

</script>
@stop
