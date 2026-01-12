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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Purchase Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Invoice</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Invoice
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" action="{{ url('purchase_invoice/save/'.$docrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
                                           <option value="">Select Voucher...</option>
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php if($voucherid==$voucher['id']) echo 'selected';?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$voucherno}}">
                                    </div>
                                </div>
								<input type="hidden" name="curno" id="curno" value="{{$voucherno}}"> <input type="hidden" name="vat_no">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" value="{{$referenceno}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" readonly data-language='en' id="voucher_date" value="{{$voucherdt}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" placeholder="LPO No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" readonly data-language='en' value="{{$lpodt}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="purchase_account" id="purchase_account" class="form-control" readonly value="{{$purchaseac}}">
										<input type="hidden" name="account_master_id" id="account_master_id" class="form-control" value="{{$accountmstr}}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" value="{{$docrow->supplier}}">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{$docrow->supplier_id}}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type</label>
                                    <div class="col-sm-10">
                                       <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
                                           <option value="">Select Document...</option>
										   <option value="PO" <?php if($doctype=='PO') echo 'selected';?>>Purchase Order</option>
										   <!--<option value="SDO" <?php //if($doctype=='SDO') echo 'selected';?>>Supplier Delivery Order</option>-->
                                       </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" value="{{$pordid}}" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                                    </div>
                                </div>
								
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
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" <?php if($docrow->is_fc==1) echo 'checked';?>>
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}" <?php if($docrow->currency_id==$curr['id']) echo 'selected'; ?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" value="{{$docrow->currency_rate}}" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Item Details</h5></legend>
								{{--*/ $i = 0; $num = count($docitems); $total = $vattotal = $nettotal = $nettotal_dh = $total_dh = $vattotal_dh = 0; /*--}}
								@foreach($docitems as $poitem)
								{{--*/ $i++; /*--}}
								
								<div class="itemdivPrnt">
									<div class="itemdivChld">							
										<div class="form-group">
											<div class="col-sm-2">
												@if($doctype=='PO')
												<input type="hidden" name="purchase_order_item_id[]" id="p_orditmid_1" value="{{$poitem->id}}">
												@elseif($doctype=='SDO')
												<input type="hidden" name="supplier_do_item_id[]" id="sdo_itemid_1" value="{{$poitem->id}}">
												@endif
												<input type="hidden" name="item_id[]" id="itmid_1" value="{{$poitem->item_id}}">
												<input type="text" id="itmcod_1" name="item_code[]" value="{{$poitem->item_code}}" class="form-control" autocomplete="off" onclick="getItems(this)">
											</div>
											<div class="col-xs-10">
												<div class="col-xs-10">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" onclick="getItems(this)" value="{{$poitem->item_name}}">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-2"> 
												<span class="small">Unit</span>	
												<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
												<option value="{{$poitem->unit_id}}">{{$poitem->unit_name}}</option></select>
											</div>
											<div class="col-xs-10">
												<div class="col-xs-2">
												<?php 
													if($poitem->balance_quantity==0) {
														if($docrow->is_fc==1) {
															$quantity = $actual_quantity = $poitem->quantity;
															$total_price = $poitem->total_price / $docrow->currency_rate;
															$total += $total_price;
															$vattotal += ($total_price * $poitem->vat)/100;
															
															$vat_amount = round($poitem->vat_amount / $docrow->currency_rate,2);
															$unit_price = $poitem->unit_price / $docrow->currency_rate;
															
															$total_price_dh = $poitem->total_price;
															$total_dh += $total_price_dh;
															$vattotal_dh += ($total_price_dh * $poitem->vat)/100;
														} else {
															$quantity = $actual_quantity = $poitem->quantity;
															$total_price = $poitem->total_price;
															$total += $total_price;
															$vattotal += ($total_price * $poitem->vat)/100;
															//$nettotal += $total + $vattotal;
															$vat_amount = $poitem->vat_amount;
															$unit_price = $poitem->unit_price;
															
															$total_dh = $vattotal_dh = 0;
														}
													} else {
														if($docrow->is_fc==1) {
															$quantity = $actual_quantity = $poitem->balance_quantity;
															$unit_price = $poitem->unit_price / $docrow->currency_rate;
															$total_price = $quantity * $unit_price;
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
												?>
													<input type="hidden" name="actual_quantity[]" id="itmactqty_1" value="{{$actual_quantity}}"> 
													<span class="small">Quantity</span> <input type="number" id="itmqty_1" name="quantity[]" class="form-control line-quantity" value="{{$quantity}}">
												</div>
												<div class="col-xs-2">
													<span class="small">Cost/Unit</span> <input type="number" id="itmcst_1" step="any" name="cost[]" class="form-control line-cost" value="{{$unit_price}}">
												</div>
												<div class="col-xs-2">
													<span class="small">Vat% - Amount</span> <input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" value="{{$poitem->vat.'% - '.$vat_amount}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost" value="{{$poitem->vat}}">
													<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="{{$vat_amount}}">
												</div>
												<div class="col-xs-2">
													<!--<span class="small">Discount</span>--> <input type="hidden" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control line-discount" value="{{$poitem->discount}}">
												</div>
												<div class="col-xs-2">
													<span class="small">Total</span> <input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$total_price}}">
												</div>
												<div class="col-xs-1">
												</div>
											</div>	
										</div>
										
										<!--<div class="form-group">
											<div class="col-sm-2"></div>
											<div class="col-xs-10">
												<div class="col-xs-2">
													<span class="small"></span>	
												</div>
												<div class="col-xs-2">
													<span class="small"></span> 
												</div>
												<div class="col-xs-2">
													<span class="small"></span> 
												</div>
												<div class="col-xs-2">
													<span class="small">Other Cost/Unit</span> <input type="number" id="othrcst_1" step="any" name="othr_cost[]" class="form-control" readonly>
												</div>
												<div class="col-xs-2">
													<span class="small">Net Cost/Unit</span> <input type="number" id="netcst_1" step="any" name="net_cost[]" class="form-control" readonly>
												</div>
												<div class="col-xs-1">
												 @if($num==$i)
												 <button type="button" class="btn btn-success btn-add-item" >
													<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
												 </button>
												@else
													<button type="button" class="btn btn-success btn-danger btn-remove-item"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>
												@endif
												</div>
											</div>	
										</div>-->
										
										
											<div id="moreinfo">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1"></div>
												</div>
											</div>
										
										<hr/>
									</div>
								</div>
								@endforeach
								<?php $nettotal += $total + $vattotal; $nettotal_dh += $total_dh + $vattotal_dh;?>
								</fieldset>
								<br/><br/>
								<input type="hidden" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
								<!--<fieldset>
									<legend>
										<div id="oc_showmenu"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
									</legend>
									<div class="OCdivPrnt">
										<div class="OCdivChld">							
											<div class="form-group">
												<div class="col-sm-4">
													<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
													<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" onclick="getAccount(this)" placeholder="Debit Account">
												</div>
												<div class="col-xs-8">
													<div class="col-xs-3">
														<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
													</div>
													<div class="col-xs-5">
														<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
													</div>
													<div class="col-xs-3">
														<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-4">
													<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
													<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control" autocomplete="off" onclick="getAccountCr(this)" placeholder="Credit Account">
												</div>
												<div class="col-xs-8">
													<div class="col-xs-3"></div>
													<div class="col-xs-5"></div>
													<div class="col-xs-3">
														<input type="number" name="oc_fc_amount[]" id="ocfcamt_1" step="any" autocomplete="off" class="form-control" placeholder="FC Amount">
													</div>
													<div class="col-xs-1">
														 <button type="button" class="btn btn-success btn-add-oc" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>
													</div>
												</div>	
											</div>
											<hr/>
										</div>
									</div>
								</fieldset>-->
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control" readonly value="{{$total}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control" value="{{$total_dh}}" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat" name="vat" class="form-control" value="{{$vattotal}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control" value="{{$vattotal_dh}}" readonly>
										</div>
									</div>
                                </div>
								
								<input type="hidden" step="any" id="discount" name="discount" class="form-control" value="{{$discount}}">
								<input type="hidden" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">
								<input type="hidden" step="any" id="other_cost" name="other_cost" readonly class="form-control" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control" placeholder="0">
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Cost</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="other_cost" name="other_cost" readonly class="form-control" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control" value="{{$discount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">
										</div>
									</div>
                                </div>-->
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control" readonly value="{{$nettotal}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control" readonly value="{{$nettotal_dh}}">
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
                                        <div class="modal-body" id="itmData">
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


<script>
"use strict";

$(document).ready(function () { 

	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php if($docrow->is_fc==0) { ?>
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
	<?php } ?>
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('#other_cost_fc').toggle(); $('.OCdivPrnt').toggle(); 
	var urlcode = "{{ url('purchase_invoice/checkrefno/') }}";
    $('#frmPurchaseInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			reference_no: {
                validators: {
                    notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    },
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
			voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			purchase_account: { validators: { notEmpty: { message: 'The purchase account is required and cannot be empty!' } }},
			supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }},
			'cost[]': { validators: { notEmpty: { message: 'The item cost is required and cannot be empty!' } }}
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
		
		//new change..
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		console.log('taxnew: '+vatcur);
		$('#vat').val(vatcur.toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total + vat;
		$('#net_amount').val(netTotal.toFixed(2));
		
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
	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log(n);
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		var lineTax 	 = (lineCost * lineVat) / 100;
		var taxLineCost	 = lineQuantity * lineTax;
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost);
		var lineDiscount = parseFloat( ($('#itmdsnt_'+n).val()=='') ? 0 : $('#itmdsnt_'+n).val() );
		var lineTotal 	 = ( lineQuantity * lineCost ) - lineDiscount;
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost);
		$('#vatlineamt_'+n).val(taxLineCost.toFixed(2));
		
		//getOtherCost();
		return lineTotal;
	} 
	
	//calculation other cost...
	function getOtherCost() {
		
		var otherCost = 0;
		$( '.oc-line' ).each(function() {
			otherCost = otherCost + parseFloat(this.value);
		});
		$('#other_cost').val(otherCost);
		
		if(otherCost!=0) {
			var totalQty = 0;
			 $('input[name="quantity[]"]').each(function(){
				totalQty += +$(this).val();
			 });
			 
			 var n = 1;
			$( '.itemdivChld' ).each( function() { 
				var costPerUnit; var nTotal;
				var itemCost = parseFloat( ($('#itmcst_'+n).val()=='')?0:$('#itmcst_'+n).val() );
				costPerUnit = (otherCost / totalQty) * itemCost;
				$('#othrcst_'+n).val(costPerUnit.toFixed(2));
				$('#netcst_'+n).val( (costPerUnit + itemCost).toFixed(2) );
				n++;
			});
		}
		
		return true;
	}
//
$(function() {	
	var rowNum = 1;
	//$('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	//$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
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
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); //new change
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="othr_cost[]"]')).attr('id', 'othrcst_' + rowNum);
			newEntry.find($('input[name="net_cost[]"]')).attr('id', 'netcst_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find('input').val(''); 
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
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
		
		e.preventDefault();
		return false;
	});
	
	$(document).on('click', '.btn-add-oc', function(e) 
    { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .OCdivPrnt'),
            currentEntry = $(this).parents('.OCdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="dr_acnt_id[]"]')).attr('id', 'dracntid_' + rowNum);
			newEntry.find($('input[name="dr_acnt[]"]')).attr('id', 'dracnt_' + rowNum);
			newEntry.find($('input[name="oc_reference[]"]')).attr('id', 'ocref_' + rowNum);
			newEntry.find($('input[name="oc_description[]"]')).attr('id', 'ocdesc_' + rowNum);
			newEntry.find($('input[name="oc_amount[]"]')).attr('id', 'ocamt_' + rowNum);
			newEntry.find($('input[name="cr_acnt_id[]"]')).attr('id', 'cracntid_' + rowNum);
			newEntry.find($('input[name="cr_acnt[]"]')).attr('id', 'cracnt_' + rowNum);
			newEntry.find('input').val(''); 
			controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
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
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$.each(data, function(key, value) {   
			$('#itmunt_'+num)
			 .append($("<option></option>")
						.attr("value",key)
						.text(value)); 
			});

		});
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
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
	
	var acturl = "{{ url('purchase_invoice/account_data/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('purchase_invoice/account_data/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-discount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$(document).on('blur', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	//total discount section.........
	$(document).on('blur', '#discount', function(e) { 
		getNetTotal();
	});
	
	$(document).on('blur', '#vat', function(e) { 
		getNetTotal();
	});
			
	$(document).on('click', '.nam', function(e) 
    {
		//$('#voucher_no').val('clara');
		e.preventDefault();
	});
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_invoice/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			$('#voucher_no').val(data.voucher_no);
			$('#purchase_account').val(data.account_id+'-'+data.account_name);
			$('#account_master_id').val(data.id);
		});
	});
	
	//new change...
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		var item_id = $('#itmid_'+curNum).val();console.log(item_id);
		$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
			$('#vat_'+curNum).val(data);
			$('#vatdiv_'+curNum).val(data+'%');
		});
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

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccountCr(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum+"/cr";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getDocument() { 
	var supplier_id = $("#supplier_id").val();
	var doc = $('#document_type option:selected').val();
	
	if($("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	
	if(doc==2)
		var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id+"/po";
	else if(doc==3)
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/sdo";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
</script>
@stop

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
                Purchase Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Invoice</a>
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
					<?php if(!$vouchers) { ?>
					<div class="alert alert-warning">
						<p>
							Voucher No. is found empty! Please create in Account Settings.
						</p>
					</div>
					<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Invoice
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" action="{{ url('purchase_invoice/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
                                           @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
									<input type="hidden" name="curno" id="curno" value="<?php echo $voucher['voucher_no']; ?>">
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="<?php echo $voucher['voucher_no']; ?>">
										<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" autocomplete="off" placeholder="Reference No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" autocomplete="off" placeholder="LPO No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" autocomplete="off" data-language='en' readonly placeholder="LPO Date">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Account</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="account_master_id" id="account_master_id" value="{{ $voucher['dr_account_master_id'] }}" class="form-control">
										<!--<input type="text" name="purchase_account" id="purchase_account" class="form-control" value="{{ $voucher['account_id'].'-'.$voucher['master_name'] }}" readonly onclick="getAccount(this)">-->
										<div class="input-group">
											<input type="text" name="purchase_account" id="purchase_account" class="form-control" value="{{ $voucher['account_id'].'-'.$voucher['master_name'] }}" readonly>
											<span class="input-group-addon inputsa"><i class="fa fa-fw fa-edit"></i></span>
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id">
									</div>
                                </div>
								
								<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" autocomplete="off" name="vat_no" placeholder="TRN No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type</label>
                                    <div class="col-sm-10">
                                       <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
                                           <option value="">Select Document...</option>
										   <option value="PO">Purchase Order</option>
										   <!--<option value="SDO">Supplier Delivery Order</option>-->
                                       </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" autocomplete="off" placeholder="Description">
                                    </div>
                                </div>
								
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
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}">{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
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
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Import</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="import" id="import" name="is_import" value="1">
											</label>
										</div>
									</div>
                                </div>
								
								<br/>
								<fieldset>
									<legend><h5>Item Details</h5></legend>
									<div class="itemdivPrnt">
										<div class="itemdivChld">	
											<div class="form-group">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<span class="small">Item Code</span>
														<input type="hidden" name="item_id[]" id="itmid_1">
														<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<span class="small">Item Description</span><input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Description">
													</td>
													<td width="7%">
														<span class="small">Unit</span>	<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
													</td>
													<td width="8%" class="itcod">
														<span class="small">Quantity</span> <input type="number" id="itmqty_1"  name="quantity[]" class="form-control line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<span class="small">Cost/Unit</span> <input type="number" id="itmcst_1" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="6%">
														<span class="small">Tx.Code</span><select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
														<option value="SR">SR</option><option value="RC">RC</option><option value="ZR">ZR</option><option value="EX">EX</option></select>
													</td>
													<td width="6%">
														<span class="small">Tx.Inc.</span><br/>
														<select id="txincld_1" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
													</td>
													<td width="9%">
														<span class="small">VAT Amount</span>
														<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" placeholder="VAT Amount">
														<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost">
														<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
														<input type="hidden" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control line-discount" placeholder="Discount">
													</td>
													<td width="11%">
														<span class="small">Total</span> <input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
													</td>
													<td width="1%">
														<br/><button type="button" class="btn btn-success btn-add-item" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
													</td>
												</tr>
											</table></div>
											
											<div class="form-group">
												<div class="col-sm-2"></div>
												<div class="col-xs-10">
													<div class="col-xs-2">
														<span class="small"></span>	
													</div>
													<div class="col-xs-2">
														<span class="small"></span> 
													</div>
													<div class="col-xs-2">
														<span class="small"></span> 
													</div>
													<div class="col-xs-2">
														<span class="small">Other Cost/Unit</span> <input type="text" id="othrcst_1" step="any" name="othr_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-2">
														<span class="small">Net Cost/Unit</span> <input type="text" id="netcst_1" step="any" name="net_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-1">
														 <!--<button type="button" class="btn btn-success btn-add-item" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>-->
													</div>
												</div>	
											</div>
											
											
												<div id="moreinfo">
													<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
												</div>
												
												<div class="infodivPrntItm" id="infodivPrntItm_1">
													<div class="infodivChldItm">							
														<div class="table-responsive item-data" id="itemData_1"></div>
													</div>
												</div>
											
											<hr/>
										</div>
									</div>
								
								</fieldset>
								<br/><br/>
								
								<fieldset>
									<legend>
										<div id="oc_showmenu"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
									</legend>
									<div class="OCdivPrnt">
										<div class="OCdivChld">
											<div class="form-group">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="20%">
														<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="12%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
													</td>
													<td width="20%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="10%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="5%">
														<span class="small">VAT</span>
														<input type="number" name="vat_oc[]" step="any" id="vatocamt_1" autocomplete="off" class="form-control oc-line-vat" placeholder="VAT">
													</td>
													<td width="18%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
														<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
													</td>
													<td width="10%">
														<div class="oc-amount-fc">
															<span class="small">FC Amount</span>
															<input type="number" name="oc_fc_amount[]" id="ocfcamt_1" step="any" autocomplete="off" class="form-control oc-line-fc" placeholder="FC Amount">
														</div>
													</td>
													<td width="5%"><br/>
														<button type="button" class="btn btn-success btn-add-oc" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>
													</td>
												</tr>
											</table>
											</div>											
											<hr/>
										</div>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat" name="vat" class="form-control" placeholder="0" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control" placeholder="0" readonly>
										</div>
									</div>
                                </div>
								
				
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Cost</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="other_cost" name="other_cost" readonly class="form-control" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control discount-cal" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">
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
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" class="form-control" readonly>
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('purchase_invoice') }}" class="btn btn-danger">Cancel</a>
										<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">View Order History</a>
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
							
							<div id="history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Order History</h4>
                                        </div>
                                        <div class="modal-body" id="historyData">Please select a Supplier first!
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
var srvat=0;
$(document).ready(function () { 

	$("#currency_rate").prop('disabled', true);  $('#trninfo').toggle();
	$("#currency_id").prop('disabled', true);
	$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle();$("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('.OCdivPrnt').toggle(); $("#other_cost_fc").toggle(); $('.oc-amount-fc').toggle();
	var urlvchr = "{{ url('purchase_invoice/checkvchrno/') }}";
	var urlcode = "{{ url('purchase_invoice/checkrefno/') }}"; //CHNG
    $('#frmPurchaseInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: {
                validators: {
                    notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    },
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This Voucher No. is already exist!'
                    }
                }
            },
			reference_no: {
                validators: {
                    notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    },
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
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			purchase_account: { validators: { notEmpty: { message: 'The purchase account is required and cannot be empty!' } }},
			supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }},
			'cost[]': { validators: { notEmpty: { message: 'The item cost is required and cannot be empty!' } }},
			'dr_acnt[]': { validators: { notEmpty: { message: 'The debit account is required and cannot be empty!' } }},
			'oc_amount[]':{ validators: { notEmpty: { message: 'The amount is required and cannot be empty!' } }},
			'cr_acnt[]':{ validators: { notEmpty: { message: 'The credit account is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurchaseInvoice').data('bootstrapValidator').resetForm();
    });
	
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
		var n = 1; var lineTotal = 0;
		$( '.itemdivChld' ).each(function() {
		  lineTotal = lineTotal + getLineTotal(n);
		  n++;
		});
		$('#total').val(lineTotal.toFixed(2));
		
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
		var other_cost = parseFloat( ($('#other_cost').val()=='') ? 0 : $('#other_cost').val() );
		var netTotal = total + vat + other_cost;
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
		
		if(discount!=0)
			calculateDiscount();
	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log(n);
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		var lineTax 	 = (lineCost * lineVat) / 100;
		
		if($('#txincld_'+n+' option:selected').val()==1 ) {
			
			var ln_total	 = lineQuantity * lineCost;
			var taxLineCost  = (ln_total * lineVat) / parseFloat(100+lineVat);
			var lineTotal 	 = ( ln_total - taxLineCost );
			//var lineDiscount = parseFloat( ($('#itmdsnt_'+n).val()=='') ? 0 : $('#itmdsnt_'+n).val() );
			
		} else {
			
			var taxLineCost	 = lineQuantity * lineTax;
			var lineDiscount = parseFloat( ($('#itmdsnt_'+n).val()=='') ? 0 : $('#itmdsnt_'+n).val() );
			var lineTotal 	 = ( lineQuantity * lineCost ) - lineDiscount;
		}
		
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2)); //lineVat+'% - '+taxLineCost.toFixed(2)
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost.toFixed(2));//(taxLineCost + vatcur).toFixed(2)
		$('#vatlineamt_'+n).val(taxLineCost.toFixed(2));
		
		//getOtherCost();
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
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);;
		var ln_total_amt = ln_total - vat_amt; //console.log(ln_total_amt); // $('#net_amount').val(ln_total_amt);
		$('#itmttl_'+curNum).val( ln_total_amt.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() ) - vat_amt).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) - vat_amt).toFixed(2) );
	}
	
	function calculateTaxExclude(curNum)
	{
		//console.log(curNum);
		var ln_total = parseFloat( $('#itmqty_'+curNum).val()==''?0:$('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val()==''?0:$('#itmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 : $('#vat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);;
		var ln_total_amt = ln_total + vat_amt;
		$('#itmttl_'+curNum).val( ln_total_amt.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() ) + vat_amt).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) + vat_amt).toFixed(2) );
	}
	
	function calculateDiscount()
	{ 
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var lineTotal = parseFloat( ($('#total').val()=='') ? 0 : $('#total').val() );
		var vatTotal = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var netAmtHid = parseFloat( ($('#net_amount_hid').val()=='') ? 0 : $('#net_amount_hid').val() );
		var other_cost = parseFloat( ($('#other_cost').val()=='') ? 0 : $('#other_cost').val() );
		//var netTotal = parseFloat( ($('#net_amount').val()=='') ? 0 : $('#net_amount').val() );
		if(discount!=0) {
			
			var amountTotal = 0; var discountAmt; var vatLine = 0; var vatnet = 0; var amountNet = 0; var total; var lntotal = 0;
			
			$( '.line-total' ).each(function() {
				var res = this.id.split('_');
				var curNum = res[1];
				var qty = parseFloat( ($('#itmqty_'+curNum).val()=='') ? 0 :  $('#itmqty_'+curNum).val() );
				var cost = parseFloat( ($('#itmcst_'+curNum).val()=='') ? 0 :  $('#itmcst_'+curNum).val() );
				lntotal = lntotal + (qty * cost);
			});
			
			lineTotal = lntotal;
			$( '.line-total' ).each(function() {
			  var res = this.id.split('_');
			  var curNum = res[1];
			  var vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 :  $('#vat_'+curNum).val() );
			  
			  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
				  var vatPlus = parseFloat(100 + vat);
				  //lineTotal = parseFloat( ($('#net_amount_hid').val()=='') ? 0 : $('#net_amount_hid').val() );
				  total = parseFloat( $('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val() );
			  } else {
				 var vatPlus = 100;
				 total = this.value;
			  }
			  
			  //console.log('lineTotal: '+lineTotal); console.log('total: '+total);
			  //	12.5		200/800*50
			  discountAmt = (total / lineTotal) * discount; //console.log('discountAmt: '+discountAmt);
			  amountTotal = total - discountAmt; //console.log('amountTotal:'+amountTotal);
			  vatLine = (amountTotal * vat) / vatPlus; //8.93 = 187.5*5/105
			  amountNet = amountNet + amountTotal;
			  vatLine = parseFloat(vatLine.toFixed(2));
			  
			  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
				  $('#itmttl_'+curNum).val(amountTotal.toFixed(2));
				  //$('#total').val(amountNet.toFixed(2));
				  //amountNet = amountNet - discountAmt;
			  } /* else {
				  amountNet = parseFloat(amountNet + vatnet)
			  } */
			  vatnet = parseFloat(vatnet + vatLine); 
			  $('#vatdiv_'+curNum).val(vat+'% '+vatLine);//.toFixed(2)
			  $('#vatlineamt_'+curNum).val(vatLine);//.toFixed(2)
			  
			  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
				  amountNet = netAmtHid - vatnet;
				  $('#total').val(amountNet.toFixed(2));
				  amountNet = amountNet - discount;
		      }
			  
			  if(lineTotal != netAmtHid) {
				  amountNet = netAmtHid - vatnet;
				  $('#total').val(amountNet.toFixed(2));
				  amountNet = amountNet - discount;
			  }
			  
			});
			
			//console.log(amountNet);
			//$('#total').val(amountTotal.toFixed(2));
			
			$('#vat').val(vatnet.toFixed(2));//.toFixed(2)
			$('#net_amount').val( (parseFloat(amountNet + vatnet + other_cost)).toFixed(2) );
			
		}
		
		return true;
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
		   //$('input[name="dr_acnt[]"]').val( $('#purchase_account').val() );
		  // $('input[name="dr_acnt_id[]"]').val( $('#account_master_id').val() );
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
			
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('.taxinclude')).attr('id', 'txincld_' + rowNum);//NW CHNG
			
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */ //NEW CHNG
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
		
		/* if( $(this).attr("data-id") == 'rem_1' ) { 
			$('.itemdivPrnt').html('Items are empty! Please refresh the page.');
		} */ 
		e.preventDefault();
		return false;
	});
	
	$(document).on('click', '.btn-add-oc', function(e) 
    { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .OCdivPrnt'),
            currentEntry = $(this).parents('.OCdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="dr_acnt_id[]"]')).attr('id', 'dracntid_' + rowNum);
			newEntry.find($('input[name="dr_acnt[]"]')).attr('id', 'dracnt_' + rowNum);
			newEntry.find($('input[name="oc_reference[]"]')).attr('id', 'ocref_' + rowNum);
			newEntry.find($('input[name="oc_description[]"]')).attr('id', 'ocdesc_' + rowNum);
			newEntry.find($('input[name="oc_amount[]"]')).attr('id', 'ocamt_' + rowNum);
			newEntry.find($('input[name="cr_acnt_id[]"]')).attr('id', 'cracntid_' + rowNum);
			newEntry.find($('input[name="cr_acnt[]"]')).attr('id', 'cracnt_' + rowNum);
			newEntry.find('input').val(''); 
			controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
		e.preventDefault();
		return false;
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
		$('#vat_'+num).val( $(this).attr("data-vat") );
		$('#itmcst_'+num).val( $(this).attr("data-price") );
		srvat = $(this).attr("data-vat");
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').remove().end()
			 .append($("<option></option>")
						.attr("value",key)
						.text(value)); 
			});

		});
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
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
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
	
	var hisurl = "{{ url('purchase_invoice/order_history/') }}";
	$('.order-history').click(function() {
		var sup_id = $('#supplier_id').val();
		$('#historyData').load(hisurl+'/'+sup_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('purchase_invoice/account_data/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('purchase_invoice/account_data/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		//$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var isPrice = getAutoPrice(curNum);
		//if(isPrice)
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
		$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-discount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$(document).on('blur', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	//total discount section.........
	$(document).on('blur', '#discount', function(e) { 
		getNetTotal();
	});
	
	$(document).on('blur', '#vat', function(e) { 
		getNetTotal();
	});
			
	$(document).on('click', '.nam', function(e) 
    {
		//$('#voucher_no').val('clara');
		e.preventDefault();
	});
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_invoice/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			$('#voucher_no').val(data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			if(data.account_id!=null && data.account_name!=null) {
				$('#purchase_account').val(data.account_id+'-'+data.account_name);
				$('#account_master_id').val(data.id);
			} else {
				$('#purchase_account').val('');
				$('#account_master_id').val('');
			}
		});
	});
	
	//new change...
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		var item_id = $('#itmid_'+curNum).val();console.log(item_id);
		$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
			$('#vat_'+curNum).val(data);
			$('#vatdiv_'+curNum).val(data+'%');
		});
	});
	
	$('#document_type').on('change', function(e){
		var vchr_id = $('#voucher_id').val();
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		var pur_ac = $('#purchase_account').val();
		var ac_mstr = $('#account_master_id').val();
		
		$.ajax({
			url: "{{ url('purchase_invoice/set_session/') }}",
			type: 'get',
			data: 'vchr_no='+vchr_no+'&vchr_id='+vchr_id+'&ref_no='+ref_no+'&vchr_dt='+vchr_dt+'&lpo_dt='+lpo_dt+'&pur_ac='+pur_ac+'&ac_mstr='+ac_mstr,
			success: function(data) { //console.log(data);
			}
		}) 
	});
	
	//CHNG
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#purchase_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#num').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
	});
	
	$(document).on('blur', '.oc-line-vat', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	$(document).on('change', '.taxinclude', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1]; console.log(curNum);
		if(this.value==1)
			calculateTaxInclude(curNum);
		else
			calculateTaxExclude(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
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

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccountCr(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum+"/cr";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getDocument() { 
	var supplier_id = $("#supplier_id").val();
	var doc = $('#document_type option:selected').val();
	
	if($("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	
	if(doc=='PO')
		var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id+"/po";
	else if(doc=='SDO')
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/sdo";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
</script>
@stop

