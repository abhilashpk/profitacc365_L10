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
	
	<link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
	
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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Purchase Order
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Order</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> <?php if($orderrow->is_editable==1) { echo 'Non-Editable'; } else echo 'Edit Order';?>
                            </h3>
							
							<div class="pull-right">
							@permission('so-print')
							 <a href="{{ url('purchase_order/print/'.$orderrow->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
								<span class="btn-label">
									<i class="fa fa-fw fa-print"></i>
								</span>
							 </a>
							@endpermission
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurorder" id="frmPurorder" action="{{ url('purchase_order/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="purchase_order_id" id="purchase_order_id" value="{{ $orderrow->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PO. No.</label>
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
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" value="<?php echo (old('reference_no'))?old('reference_no'):$orderrow->reference_no; ?>" placeholder="Reference No.">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PO. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" id="voucher_date" placeholder="PO. Date"/>
                                    </div>
                                </div>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" data-language='en' autocomplete="off" value="<?php echo ($orderrow->lpo_date=='0000-00-00')?'':date('d-m-Y',strtotime($orderrow->lpo_date));?>" placeholder="LPO Date">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" value="<?php echo (old('description'))?old('description'):$orderrow->description; ?>" name="description" placeholder="Description">
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<?php if($formdata['terms']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
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
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Job Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" value="{{$orderrow->code}}">
										<input type="hidden" name="job_id" id="job_id" value="{{$orderrow->job_id}}">
									</div>
                                </div>
								
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="<?php echo (old('supplier_name'))?old('supplier_name'):$orderrow->supplier; ?>" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo (old('supplier_id'))?old('supplier_id'):$orderrow->supplier_id; ?>">
									</div>
                                </div>
								
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
											<input type="text" name="currency_rate" autocomplete="off" id="currency_rate" class="form-control" value="{{$orderrow->currency_rate}}" placeholder="Currency Rate">
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
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-10">
                                        <select id="location_id" class="form-control select2" style="width:100%" name="location_id">
										<option value="">Select Location..</option>
											<?php 
											foreach($location as $loc) { 
											?>
											<option value="{{ $loc['id'] }}" <?php if($loc['id']==$orderrow->location_id) echo 'selected'; ?>>{{ $loc['name'] }}</option>
											<?php } ?>
                                        </select>
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
								
								@php $i = 0; $num = count($orditems); @endphp
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
														<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item}}">
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
														<input type="number" id="itmqty_{{$j}}" step="any" name="quantity[]" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_{{$j}}" step="any" name="cost[]" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{ old('cost')[$i]}}">
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
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{ old('vatdiv')[$i]}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
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
											
											<div class="form-group oc" id="othrcstItm_1">
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
														<span class="small">Other Cost/Unit</span> <input type="hidden" id="othrcst_{{$j}}" value="{{old('othr_cost')[$i]}}" step="any" name="othr_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-2">
														<span class="small">Net Cost/Unit</span> <input type="hidden" id="netcst_{{$j}}" step="any" value="{{old('net_cost')[$i]}}" name="net_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-1">
														
													</div>
												</div>	
											</div>
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?>
								<?php if($formdata['purchase']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="purchase" id="purchase">
								<?php } ?>								<?php if($formdata['sales']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="sales" id="sales">
								<?php } ?>	
								<?php if($formdata['net_cost']==1) { ?>
									<div id="othrcst" style="float:left;padding-right:5px;">
												<button type="button" id="ocost_{{$j}}" class="btn btn-primary btn-xs net-cost">Net Cost/Unit</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="net_cost" id="net_cost">
								<?php } ?>
								<?php if($formdata['location_item']==1) { ?>
											<div id="loc">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_item" id="location_item">
								<?php } ?>
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
									
									</div>
								<?php $i++; } } else { ?>
								@foreach($orditems as $item)
								{{--*/ $i++; /*--}}
								<?php if($orderrow->is_fc==1) {
										 $unit_price = $item->unit_price_fc;// / $orderrow->currency_rate;
										 $total_price = number_format($item->total_price / $orderrow->currency_rate,2, '.', '');
										 $vat_amount = round($item->vat_amount / $orderrow->currency_rate,2);
										 $total = $orderrow->total / $orderrow->currency_rate;
										 $vat_amount_net = $orderrow->vat_amount / $orderrow->currency_rate;
										 $net_amount = round($orderrow->net_amount / $orderrow->currency_rate,2);
									  } else {
										 $unit_price = $item->unit_price;
										 $total_price = $item->total_price;
										 $vat_amount = $item->vat_amount;
										 $total = $orderrow->total;
										 $vat_amount_net = $orderrow->vat_amount;
										 $net_amount = $orderrow->net_amount;
									  }
									
								?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
												<tr>
													<td width="16%">
														<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
														<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
														<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}">
													</td>
													<td width="7%">
														<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
														<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</td>
													<td width="9%">
														<input type="number" id="itmqty_{{$i}}" step="any" autocomplete="off" name="quantity[]" class="form-control line-quantity" {{($orderrow->is_editable == 1)?'readonly':''}} value="{{$item->quantity}}">
													</td>
													<td width="9%">
														<input type="number" id="itmcst_{{$i}}" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" {{($orderrow->is_editable == 1)?'readonly':''}} value="{{$unit_price}}">
													</td>
													<td width="6%">
														<select id="taxcode_{{$i}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR" <?php if($item->tax_code=="SR") echo 'selected'; ?>>SR</option><option value="RC" <?php if($item->tax_code=="RC") echo 'selected'; ?>>RC</option></select>
													</td>
													<td width="6%">
														<select id="txincld_{{$i}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option <?php if($item->tax_include==0) echo 'selected';?> value="0">No</option><option <?php if($item->tax_include==1) echo 'selected';?> value="1">Yes</option></select>
													</td>
													<td width="9%">
														<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:(($item->pkno > $item->packing)?$item->pkno:$item->packing)}}">
														<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{$item->vat.'% - '.$vat_amount}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
														<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control vat" value="{{$item->vat}}">
														<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$vat_amount}}">
														<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$total_price}}">
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
											
											<div class="form-group oc" id="othrcstItm_{{$i}}">
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
														<span class="small">Other Cost/Unit</span> <input type="text" id="othrcst_{{$i}}" value="{{$item->othercost_unit}}" step="any" name="othr_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-2">
														<span class="small">Net Cost/Unit</span> <input type="text" id="netcst_{{$i}}" step="any" value="{{$item->netcost_unit}}" name="net_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-1">
														 <!--<button type="button" class="btn btn-success btn-add-item" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>-->
													</div>
												</div>	
											</div>
											<?php if($formdata['add_desc']==1) { ?>
								<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_{{$i}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="add_desc" id="add_desc">
								<?php } ?>	
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$i}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?>
								<?php if($formdata['purchase']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$i}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="purchase" id="purchase">
								<?php } ?>								<?php if($formdata['sales']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_{{$i}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="sales" id="sales">
								<?php } ?>	
									<?php if($formdata['net_cost']==1) { ?>
									<div id="othrcst" style="float:left;padding-right:5px;">
												<button type="button" id="ocost_{{$i}}" class="btn btn-primary btn-xs net-cost">Net Cost/Unit</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="net_cost" id="net_cost">
								<?php } ?>
								
								
								<?php if($formdata['location_item']==1) { ?>
											<div id="loc">
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_item" id="location_item">
								<?php } ?>
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$i}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$i}}"></div>
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

								<fieldset>
									<legend>
										<div id="oc_showmenu"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
									</legend>
									<input type="hidden" id="remoc" name="remove_oc">
									<div class="OCdivPrnt">
										<?php if(count($ocrow) > 0) { $i = 0; 
										$ocnum = count($ocrow); ?>
										<input type="hidden" id="ocrowNum" value="{{$ocnum}}">
										<?php foreach($ocrow as $row) { $i++; ?>
										<div class="OCdivChld">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<input type="hidden" name="oc_id[]" id="ocid_{{$i}}" value="{{$row->id}}">
														<input type="hidden" name="dr_acnt_id[]" id="dracntid_{{$i}}" value="{{$row->dr_id}}">
														<input type="hidden" name="cur_dr_acnt_id[]" id="curdracntid_{{$i}}" value="{{$row->dr_id}}">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_{{$i}}" name="dr_acnt[]" value="{{$row->dr_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="12%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_{{$i}}" value="{{$row->oc_reference}}" autocomplete="off" class="form-control" placeholder="Reference">
													</td>
													<td width="15%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_{{$i}}" value="{{$row->oc_description}}" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="8%">
														<span class="small">Currency</span>
														<select id="occrncy_{{$i}}" class="form-control select2 oc-curr" style="width:100%" name="oc_currency[]">
															@foreach($currency as $curr)
															<option value="{{$curr['id']}}" <?php if($row->currency_id==$curr['id']) echo 'selected';?>>{{$curr['code']}}</option>
															@endforeach
														</select>
													</td>
													<td width="8%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_{{$i}}" value="{{($row->is_fc==1)?$row->oc_fc_amount:$row->oc_amount}}" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="7%">
														<span class="small">Rate</span>
														<input type="number" name="oc_rate[]" id="ocrate_{{$i}}" step="any" value="{{($row->currency_rate==0)?1:$row->currency_rate}}" readonly class="form-control oc-rate" placeholder="Rate">
													</td>
													<td width="10%">
														<div class="oc-amount-fc">
															<span class="small">Convrt Amt</span>
															<input type="number" name="oc_fc_amount[]" id="ocfcamt_{{$i}}" step="any" value="{{($row->is_fc==1)?$row->oc_amount:$row->oc_amount}}" autocomplete="off" class="form-control oc-line-fc" placeholder="FC Amount">
														</div>
													</td>
													<td width="5%">
														<span class="small">VAT%</span>
														<input type="number" name="vat_oc[]" step="any" id="vatocamt_{{$i}}" value="{{$row->oc_vat}}" autocomplete="off" class="form-control oc-line-vat" placeholder="VAT">
													</td>
													<td width="5%">
														<span class="small">Tx.Code</span>
														<select class="form-control select2" style="width:100%" name="tax_sr[]">
														<option value="SR" <?php if($row->tax_code=="SR") echo 'selected';?>>SR</option><option value="RC" <?php if($row->tax_code=="RC") echo 'selected';?>>RC</option><option value="ZR" <?php if($row->tax_code=="ZR") echo 'selected';?>>ZR</option><option value="EX" <?php if($row->tax_code=="EX") echo 'selected';?>>EX</option>
														</select>
														<input type="hidden" name="tax_sr_old[]" value="{{$row->tax_code}}">
													</td>
													<td width="18%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_{{$i}}" value="{{$row->cr_id}}">
														<input type="hidden" name="cur_cr_acnt_id[]" id="curcracntid_{{$i}}" value="{{$row->cr_id}}">
														<input type="text" id="cracnt_{{$i}}" name="cr_acnt[]" value="{{$row->cr_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
													</td>
													
													<td width="5%"><br/>
													<?php if(count($ocrow) == $i) { ?>
														 <button type="button" class="btn-success btn-add-oc" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													<?php } else { ?>
														<button type="button" class="btn-success btn-add-oc" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													<?php } ?>
													</td>
												</tr>
											</table>
																						
											<hr/>
										</div>
										<?php } } else { ?>
											<input type="hidden" id="ocrowNum" value="1">
											<div class="OCdivChld">
												<div class="form-group">
												<table border="0" class="table-dy-row">
													<tr>
														<td width="20%">
															<input type="hidden" name="oc_id[]" id="ocid_1">
															<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
															<span class="small">Debit Account</span>
															<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
														</td>
														<td width="12%">
															<span class="small">Reference</span>
															<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
														</td>
														<td width="15%">
															<span class="small">Description</span>
															<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
														</td>
														<td width="8%">
															<span class="small">Currency</span>
															<select id="occrncy_1" class="form-control select2 oc-curr" style="width:100%" name="oc_currency[]">
																@foreach($currency as $curr)
																<option value="{{$curr['id']}}">{{$curr['code']}}</option>
																@endforeach
															</select>
														</td>
														<td width="8%">
															<span class="small">Amount</span>
															<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
														</td>
														<td width="7%">
															<span class="small">Rate</span>
															<input type="number" name="oc_rate[]" id="ocrate_1" step="any" value="1" class="form-control oc-rate" placeholder="Rate">
														</td>
														<td width="10%">
															<span class="small">FC Amount</span>
															<input type="number" name="oc_fc_amount[]" id="ocfcamt_1" step="any" autocomplete="off" class="form-control oc-line-fc" placeholder="FC Amount">
														</td>
														<td width="5%">
															<span class="small">VAT%</span>
															<input type="number" name="vat_oc[]" step="any" id="vatocamt_1" autocomplete="off" class="form-control oc-line-vat" value="5" placeholder="VAT">
														</td>
														<td width="5%">
															<span class="small">Tx.Code</span>
															<select class="form-control select2" style="width:100%" name="tax_sr[]">
															<option value="SR">SR</option><option value="RC">RC</option><option value="ZR">ZR</option><option value="EX">EX</option>
															</select>
															<input type="hidden" name="tax_sr_old[]">
														</td>
														<td width="18%">
															<span class="small">Credit Account</span>
															<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
															<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
														</td>
														<td width="3%"><br/>
															<?php if(count($ocrow) == $i) { ?>
																 <button type="button" class="btn-success btn-add-oc" >
																	<i class="fa fa-fw fa-plus-square"></i>
																 </button>
																 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_1">
																	<i class="fa fa-fw fa-minus-square"></i>
																 </button>
															<?php } else { ?>
																<button type="button" class="btn-success btn-add-oc" >
																	<i class="fa fa-fw fa-plus-square"></i>
																 </button>
																 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_1">
																	<i class="fa fa-fw fa-minus-square"></i>
																 </button>
															<?php } ?>
														</td>
													</tr>
												</table>
												</div>											
												<hr/>
											</div>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{(old('total'))?old('total'):(($orderrow->is_fc==1)?$orderrow->total_fc:$orderrow->total)}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency {{$bcurrency}}</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" value="{{(old('total_fc'))?old('total_fc'):$orderrow->total}}" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control spl discount-cal" autocomplete="off" value="{{(old('discount'))?old('discount'):(($orderrow->is_fc==1)?$orderrow->discount_fc:$orderrow->discount)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control spl" autocomplete="off" value="{{(old('discount_fc'))?old('discount_fc'):$orderrow->discount}}" placeholder="0">
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
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):(($orderrow->is_fc==1)?$orderrow->subtotal_fc:$orderrow->subtotal)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" readonly value="{{(old('subtotal_fc'))?old('subtotal_fc'):$orderrow->subtotal}}" placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="{{(old('vat'))?old('vat'):(($orderrow->is_fc==1)?$orderrow->vat_amount_fc:$orderrow->vat_amount)}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" value="{{(old('vat_fc'))?old('vat_fc'):$orderrow->vat_amount}}" placeholder="0" readonly>
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
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{(old('net_amount'))?old('net_amount'):(($orderrow->is_fc==1)?$orderrow->net_amount_fc:$orderrow->net_amount)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly value="{{(old('net_amount_fc'))?old('net_amount_fc'):$orderrow->net_amount}}">
										</div>
									</div>
                                </div>
								<hr/>
								<?php if($formdata['header']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Header</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="header_id" id="header_id">
                                        <input type="text" class="form-control" id="headermsg" name="header" value="{{$orderrow->header}}" autocomplete="off" data-toggle="modal" data-target="#header_modal">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="header_id" id="header_id">
								<?php } ?>
								<?php if($formdata['footer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" value="{{$orderrow->footer}}" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="footer_id" id="footer_id">
								<?php } ?>	
								<?php if($formdata['add_info']==1) { ?>
								<div id="showmenu">
								<button type="button" id="infoadd" class="btn btn-primary btn-xs">Add Info..</button></div>
								<?php } else { ?>
								<input type="hidden" name="add_info" id="add_info">
								<?php } ?>
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
								<?php if($formdata['footer_custom']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$orderrow->foot_description}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								<br/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" <?php if($orderrow->is_editable==1) echo 'disabled';?>>Submit</button>
										<a href="{{ url('purchase_order') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('purchase_order/edit/'.$orderrow->id) }}" class="btn btn-warning">Clear</a>
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
							
							<div id="item_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="itm">
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
							
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
		
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

<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>

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

//$('#voucher_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#lpo_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );

"use strict";
var srvat={{$vatdata->percentage}};
$(document).ready(function () { 
	$('.locPrntItm').toggle();
	$('.descdivPrntItm').hide();
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}

	$('.OCdivPrnt').find('.btn-add-oc:not(:last)').hide();
	if ( $('.OCdivPrnt').children().length == 1 ) {
		$('.OCdivPrnt').find('.btn-remove-oc').hide();
	}


	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true); $('.oc').toggle();
	<?php if($orderrow->is_fc==0) { ?>
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();$("#subtotal_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	$('.infodivPrnt').toggle(); $('#other_cost_fc').toggle();

    <?php if($orderrow->other_cost != 0 || $orderrow->other_cost_fc != 0) { ?>
		if( $('.OCdivPrnt').is(":hidden") )
			$('.OCdivPrnt').toggle(); 
	<?php } else { ?> $('.OCdivPrnt').toggle();<?php } ?>

	var urlcode = "{{ url('purchase_order/checkrefno/') }}";
    $('#frmPurorder').bootstrapValidator({
        fields: {
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The Reference No. is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val(),
								id: validator.getFieldElements('purchase_order_id').val()
                            };
                        },
                        message: 'This Reference No. is already exist!'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			//supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			//currency_id: { enabled: true, validators: { notEmpty: { message: 'The currency is required and cannot be empty!' } }},
			//currency_rate: { enabled: true, validators: { notEmpty: { message: 'The currency rate is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }}
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurorder').data('bootstrapValidator').resetForm();
    });
	
	$(document).on('click', '#ocadd', function(e) { console.log('h');
		   e.preventDefault();
		   //$('input[name="dr_acnt[]"]').val( $('#purchase_account').val() );
		  // $('input[name="dr_acnt_id[]"]').val( $('#account_master_id').val() );
           $('.OCdivPrnt').toggle();
    });

	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle();$("#vat_fc").toggle(); $("#net_amount_fc").toggle();$("#subtotal_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#vat_fc").toggle();$("#net_amount_fc").toggle();$("#subtotal_fc").toggle();
	});
	
	$('.import').on('ifChecked', function(event){ 
		$('.tax-code').val('RC');
		$('.tax-code').attr("disabled", true); 
	});
	
	$('.import').on('ifUnchecked', function(event){
		$('.tax-code').val('SR');
		$('.tax-code').attr("disabled", false); 
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
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var supplier_id = $('#supplier_id').val();
		var cst = $('#itmcst_'+curNum).val(); //NW
		$.ajax({
			url: "{{ url('itemmaster/get_purchase_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&supplier_id='+supplier_id,
			success: function(data) {
				if(cst=='') //NW
					$('#itmcst_'+curNum).val((data==0)?'':data);
				else
					$('#itmcst_'+curNum).val(cst);
				return true;
			}
		}) 
	}
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

	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});

	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	function getOtherCost() { //MY27
		 
		 var otherCost = 0; var otherCostTx = 0; var otherCostfc = 0; var amtfc = 0; 
		 if( $('.OCdivPrnt').is(":visible") ) { 
			 $( '.oc-line' ).each(function() { 
				 var res = this.id.split('_');
				 var curNum = res[1];
				 var ocVat = parseFloat( ($('#vatocamt_'+curNum).val()=='')?0:$('#vatocamt_'+curNum).val() );
				 var ocrate = parseFloat( ($('#ocrate_'+curNum).val()=='')?1:$('#ocrate_'+curNum).val() );
				 var ocamt_ln = this.value * ocrate;
				 var ocvatamt = (ocamt_ln * ocVat) / 100;
				 otherCostTx = otherCostTx + parseFloat(ocamt_ln) + ocvatamt;
				 otherCost = otherCost + parseFloat(ocamt_ln);
				 //otherCost = otherCost + parseFloat(this.value);//MY27
				 $('#ocfcamt_'+curNum).val(ocamt_ln);
				 
				 amtfc = parseFloat(this.value);
				 var vatamtfc = (amtfc * ocVat) / 100;
				 otherCostfc = otherCostfc + amtfc + parseFloat(vatamtfc);
				 
			 }); 
			 $('#other_cost').val(otherCostTx.toFixed(2));
			 $('#other_cost_fc').val(otherCostfc.toFixed(2));
		 }
		 
		 if(otherCost!=0) { 
			 var totalQty = 0;
			  $('input[name="quantity[]"]').each(function(){
				 totalQty += +$(this).val();
			  });
			  
			  if( $('#is_fc').is(":checked") )
				 var totalCost = parseFloat( ($('#total_fc').val()=='')?0:$('#total_fc').val() );
			  else
				  var totalCost = parseFloat( ($('#total').val()=='')?0:$('#total').val() );
			  
			  var n = 1;
			 $( '.itemdivChld' ).each( function() {
				 var costPerUnit; var nTotal;
				 var itemQty = parseFloat( ($('#itmqty_'+n).val()=='')?0:$('#itmqty_'+n).val() );
				 var itemCost = parseFloat( ($('#itmcst_'+n).val()=='')?0:$('#itmcst_'+n).val() );
				 
				 if( $('#is_fc').is(":checked") ) { 
					 var rte = parseFloat($('#currency_rate').val());
					 itemCost = itemCost * rte;
				 } 
				 console.log(otherCost+' '+totalCost+' '+itemCost);
				 costPerUnit = (otherCost / totalCost) * itemCost;
				 $('#othrcst_'+n).val(costPerUnit.toFixed(2));
				 $('#netcst_'+n).val( (costPerUnit + itemCost).toFixed(2) );
				 n++;
			 });
		 }
		 
		 return true;
	 }
	 $('.inputsa').on('click', function(e) {
		$('#purchase_account').attr("onClick", "javascript:getAccount(this)");
	});
	
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



	
	$(document).on('click', '#account_data .custRow', function(e) { 
		var num = $('#account_data #num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#paccount_data #anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
		//$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'cracnt_'+num);
	});
	 
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
//
$(function() {	
	var rowNum = $('#rowNum').val(); //new change...........
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
	  
	 $(document).on('click', '.desc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   $('#descdivPrntItm_'+curNum).toggle();
    });
    
    $(document).on('click', '.net-cost', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   console.log(curNum);
	    $('#othrcstItm_'+curNum).toggle();
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
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.descdivPrntItm')).attr('id', 'descdivPrntItm_' + rowNum);
			newEntry.find($('.desc-info')).attr('id', 'descinfoItm_' + rowNum);
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.txt-desc')).attr('name', 'itemdesc['+indx+'][]');
			$('#descdivPrntItm_'+rowNum+' .descdivChldItm').slice(1).remove();
			newEntry.find($('.hid-id')).attr('name', 'desc_id['+indx+'][]');
			
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.import').is(":checked") ) { 
				$('.tax-code').val('RC');
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
	
    var ocrowNum = $('#ocrowNum').val();
	$(document).on('click', '.btn-add-oc', function(e) 
    { 
        var vt = $('#ocrate_'+ocrowNum).val();
		ocrowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .OCdivPrnt'),
            currentEntry = $(this).parents('.OCdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="dr_acnt_id[]"]')).attr('id', 'dracntid_' + ocrowNum);
			newEntry.find($('input[name="cur_dr_acnt_id[]"]')).attr('id', 'curdracntid_' + rowNum);
			newEntry.find($('input[name="dr_acnt[]"]')).attr('id', 'dracnt_' + ocrowNum);
			newEntry.find($('input[name="oc_reference[]"]')).attr('id', 'ocref_' + ocrowNum);
			newEntry.find($('input[name="oc_description[]"]')).attr('id', 'ocdesc_' + ocrowNum);
			newEntry.find($('input[name="oc_amount[]"]')).attr('id', 'ocamt_' + ocrowNum);
			newEntry.find($('input[name="cr_acnt_id[]"]')).attr('id', 'cracntid_' + ocrowNum);
			newEntry.find($('input[name="cur_cr_acnt_id[]"]')).attr('id', 'curcracntid_' + rowNum);
			newEntry.find($('input[name="cr_acnt[]"]')).attr('id', 'cracnt_' + ocrowNum);
			newEntry.find($('input[name="vat_oc[]"]')).attr('id', 'vatocamt_' + ocrowNum);
			newEntry.find($('.oc-curr')).attr('id', 'occrncy_' + ocrowNum);
			newEntry.find($('input[name="oc_rate[]"]')).attr('id', 'ocrate_' + ocrowNum);
			newEntry.find($('input[name="oc_fc_amount[]"]')).attr('id', 'ocfcamt_' + ocrowNum);
			newEntry.find('input').val(''); 
			$('#ocrate_'+ocrowNum).val(vt);
			$('#vatocamt_' + ocrowNum).val(5);
			
			//MAR30
			/* controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
			controlForm.find('.btn-add-oc:not(:last)').hide();
			controlForm.find('.btn-remove-oc').show();
			
    }).on('click', '.btn-remove-oc', function(e)
    { 
		//MAR30..
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remoc').val();
		ids = (remitem=='')?$('#ocid_'+curNum).val():remitem+','+$('#ocid_'+curNum).val();
		$('#remoc').val(ids);
		
		$(this).parents('.OCdivChld:first').remove();
		
		//getNetTotal();
		
		$('.OCdivPrnt').find('.OCdivChld:last').find('.btn-add-oc').show();
		if ( $('.OCdivPrnt').children().length == 1 ) {
			$('.OCdivPrnt').find('.btn-remove-oc').hide();
		}
		//..MAR30
		
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
	
	var hisurl = "{{ url('purchase_order/order_history/') }}";
	$('.order-history').click(function() {
		var sup_id = $('#supplier_id').val();
		$('#historyData').load(hisurl+'/'+sup_id, function(result) {
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
						.attr("value",value.id)
						.text(value.unit_name)); 
			});
		});
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
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
				$('#vat_'+curNum).val(data);
				$('#vatdiv_'+curNum).val(data+'%');
				srvat = data;
				
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
				/* $('#itmcst_'+curNum).val(data.price);
				getNetTotal(); */
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
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
	});
	
	$(document).on('blur', '.oc-line-vat', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
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
							.text(value.unit_name)); $('#hidunit_'+curNum).val(value.unit_name);
				});
			});
		}	
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
							.text(value.unit_name)); $('#hidunit_1').val(value.unit_name);
				});
			});
		},
        minLength: 3,
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

