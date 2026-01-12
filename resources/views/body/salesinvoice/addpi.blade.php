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
    
    <link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
	
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
		
		#batch_modal { z-index:0; } /* MAY25  */
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sales Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Sales Invoice</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Sales Invoice
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesInvoice" id="frmSalesInvoice" action="{{ url('sales_invoice/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								<input type="hidden" name="mq_loc" value="{{($ismqloc)?1:0}}">
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" <?php if($dptid==$drow->id) echo 'selected'; ?>>{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Invoice</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php if($voucherid==$voucher['id']) echo 'selected';?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SI. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{$voucher['voucher_no']}}">
                                    <div class="col-sm-10">
										@if($voucher['is_prefix']==1)<span class="input-group-addon">{{$voucher['prefix']}}</span>@endif
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" placeholder="{{$voucher['voucher_no']}}">
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" autocomplete="off" value="<?php echo (old('reference_no'))?old('reference_no'):$referenceno; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off" readonly id="voucher_date" placeholder="{{date('d-m-Y')}}" value="{{($voucherdt=='')?date('d-m-Y'):$voucherdt}}"/>
                                    </div>
                                </div>
								
								<?php if($formdata['lpo_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" value="{{$docrow->reference_no}}" autocomplete="off" placeholder="LPO No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_no" id="lpo_no">
								<?php } ?>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" autocomplete="off" data-language='en' readonly value="{{$lpodt}}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="sales_account" id="sales_account" class="form-control" readonly value="{{$salesac}}">
										<input type="hidden" name="cr_account_id" id="cr_account_id" class="form-control" value="{{ $voucher['cr_account_master_id'] }}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>">Customer Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" value="<?php echo (old('customer_name'))?old('customer_name'):$docrow->customer; ?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
										<input type="hidden" name="customer_id" id="customer_id" value="<?php echo (old('customer_id'))?old('customer_id'):$docrow->customer_id; ?>">
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="<?php echo (old('dr_account_id'))?old('dr_account_id'):$docrow->customer_id;?>">
									</div>
                                </div>
								
								
								@if($formdata['crm_details']==1)
								<h5><a href="#" id="crmBtn"><b>CRM Details</b></a></h5>
								<hr/>
								<div class="crm_info">
									@foreach($crm as $rw)
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label}}</label>
										<input type="hidden" name="tempid[]" value="{{$rw->id}}">
										@if($rw->text_no==1)
										<div class="col-sm-10">
											<input type="text" name="crmtext[]" class="form-control" autocomplete="on">
											<input type="hidden" name="crmtext2[]">
										</div>
										@else
										<div class="col-sm-4">
											<input type="text" name="crmtext[]" class="form-control" autocomplete="on">
										</div>
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label2}}</label>
										<div class="col-sm-4">
											<input type="text" name="crmtext2[]" class="form-control" autocomplete="on">
										</div>
										@endif
									</div>
									@endforeach
								<hr/>
								</div>
								@endif
								
								<!--<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" autocomplete="off" name="vat_no" placeholder="TRN No.">
                                    </div>
                                </div>-->
								<input type="hidden" class="form-control" id="vat_no" name="vat_no">
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document Type</label>
                                    <div class="col-sm-10">
									 <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
										<option value="SQ" <?php if($doctype=='SQ') echo 'selected'; ?>>Sales Quotation</option>
										<option value="SO" <?php if($doctype=='SO') echo 'selected'; ?>>Sales Order</option>
										<option value="CDO" <?php if($doctype=='CDO') echo 'selected'; ?>>Customer Delivery Order</option>
									</select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_type" id="document_type">
								<?php } ?>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> <?php if($doctype=='QS') echo 'Quotation'; elseif($doctype=='SO') echo 'Sales Order'; else echo 'Delivery Order';?> No</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document" readonly name="document" value="{{old('document')?old('document'):$docnos}}" autocomplete="off" onclick="getDocument()">
										<input type="hidden" id="document_id" name="document_id" value="{{old('document_id')?old('document_id'):$docid}}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php } ?>
								
								<?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" value="{{$docrow->salesman}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
										<input type="hidden" name="salesman_id" id="salesman_id" value="{{$docrow->salesman_id}}">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" autocomplete="off" value="{{$docrow->description}}">
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
                                            <option value="">Select Terms...</option>
											@foreach ($terms as $term)
											@if($docrow->terms_id==$term['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $term['id'] }}" {{$sel}}>{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="terms_id" id="terms_id">
								<?php } ?>
								
									<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Day</label>
                                    <div class="col-sm-4">
                                        	<input type="number" class="form-control" id="duedays" name="duedays" value="{{$docrow->duedays}}" placeholder="Due Days">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Due Date</label>
                                    <div class="col-sm-4">
                                        <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="due_date" data-language='en' id="due_date" value="{{date('d-m-Y')}}"/>
										
                                    </div>
                                    </div>
                                </div>
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                       <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" value="{{$docrow->code}}">
										<input type="hidden" name="job_id" id="job_id" value="{{$docrow->job_id}}">
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
											<option value="{{ $loc['id'] }}">{{ $loc['name'] }}</option>
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
										@if($docrow->is_fc==1)
										{{--*/ $chk = "checked";
										/*--}}
										@else
										{{--*/ $chk = "";
										/*--}}
										@endif
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" {{ $chk }}>
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												@if($docrow->currency_id==$curr['id'])
												{{--*/ $sel = "selected" /*--}}
												@else
												{{--*/ $sel = "" /*--}}	
												@endif
												<option value="{{$curr['id']}}" {{$sel}}>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" value="{{ $docrow->currency_rate }}">
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
											<input type="checkbox" class="export" id="export" name="is_export" <?php if($docrow->is_export==1) echo 'checked';?> value="1">
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
								{{--*/ $i = 0; $num = count($docitems);$total = $vattotal = $nettotal = $nettotal_dh = $total_dh = $vattotal_dh = 0; /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
									<div class="itemdivChld">	
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
												@if($doctype=='QS')
												<input type="hidden" name="quote_sales_item_id[]" id="quote_sales_itmid_{{$j}}" value="{{ old('quote_sales_item_id')[$i]}}">
												@elseif($doctype=='SO')
												<input type="hidden" name="sales_order_item_id[]" id="sales_order_itmid_{{$j}}" value="{{ old('sales_order_item_id')[$i]}}">
												@elseif($doctype=='CDO')
												<input type="hidden" name="customer_do_item_id[]" id="customer_do_item_id_{{$j}}" value="{{ old('customer_do_item_id')[$i]}}">
												@endif
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
													<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$j}}" value="{{old('actual_quantity')[$i]}}"> 
													<input type="number" id="itmqty_{{$j}}" step="any" autocomplete="off" name="quantity[]" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" value="{{ old('quantity')[$i]}}">
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
													<input type="hidden" id="cqty_{{$j}}" name="curqty[]" value="{{ old('curqty')[$i]}}">
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
											
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div style="float:left; padding-left:10px;">
												<button type="button" id="custsaleshisItm_{{$j}}" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
											
											<div id="loc" style="float:left; padding-left:10px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
                                            
                                            
											@if($isconloc)
											
											<div id="cnloc" style="float:left; padding-right:5px;">
												<button type="button" id="cnloc_{{$j}}" data-toggle="modal" data-target="#conloc_modal" class="btn btn-primary btn-xs cnloc-info">Consignment Location</button>
											</div>
											<div>
												<input type="hidden" name="conloc_id[]" id="conlocid_{{$j}}" value="{{$item->conloc_id}}">
												<input type="hidden" name="conloc_qty[]" id="conlocqty_{{$j}}" value="{{$item->conloc_qty}}">
												<input type="hidden" name="conloc_id_old[]" value="{{$item->conloc_id}}">
												<input type="hidden" name="conloc_qty_old[]" value="{{$item->conloc_qty}}">
											</div>
											@endif
											
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


											<div class="cnlocPrntItm" id="cnlocPrntItm_{{$j}}">
												<div class="cnlocChldItm">							
													<div class="table-responsive cnloc-data" id="cnlocData_{{$j}}">
														<?php 
														if(array_key_exists($item->id, $cnitemlocedit)) { 
														
															 ?>
														<br/>
														<div class="col-xs-5">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Consignment Location</th>
																	<th>Quantity</th>
																</tr>
																</thead>
																<tbody>
																<?php 
																foreach($cnitemloc as $key => $row) {
																
																$quantity = $id = '';
																$location_id = $row->id; $cqty = '';
																if(array_key_exists($key, $cnitemlocedit[$item->id])) { 
																	$quantity = $cnitemlocedit[$item->id][$key]->quantity;
																	$location_id = $cnitemlocedit[$item->id][$key]->location_id;
																	$id = $cnitemlocedit[$item->id][$key]->id;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td class="cnnum"><input type="number" name="cnlocqty[<?php echo $i-1;?>][]" value="{{$quantity}}" class="cnloc-qty-{{$i}}" data-id="{{$i}}">
																	<input type="hidden" name="cnlocid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="cneditid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
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
																<input type="text" name="itemdesc[<?php echo $i-1;?>][]" autocomplete="off" class="form-control txt-desc" value="{{$desc->description}}" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
																 <button type="button" class="btn btn-success btn-add-desc" >
																	<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
																 </button>
																 <button type="button" class="btn btn-danger btn-remove-desc" data-id="remdesc_{{$j}}" data-itmid="{{$item}}">
																	<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
																 </button>
																 <input type="hidden" name="desc_id[<?php echo $i-1;?>][]" value="{{$desc->id}}" class="hid-id">
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
								@foreach($docitems as $item)
								{{--*/ $i++; /*--}}
								
									<div class="itemdivChld">	
										<?php 
											if($item->balance_quantity==0) {
												if($docrow->is_fc==1) {
													$quantity = $actual_quantity = $item->quantity;
													$line_total = number_format($item->line_total / $docrow->currency_rate,2, '.', '');
													$total += $line_total;
													$vattotal += ($line_total * $item->vat)/100;
													
													$vat_amount = round($item->vat_amount / $docrow->currency_rate,2);
													$unit_price = $item->unit_price / $docrow->currency_rate;
													
													$line_total_dh = $item->line_total;
													$total_dh += $line_total_dh;
													$vattotal_dh += ($line_total_dh * $item->vat)/100;
												} else {
													$quantity = $actual_quantity = $item->quantity;
													$line_total = $item->line_total;
													$total = $docrow->total;////$total += $line_total;
													$vattotal = $docrow->vat_amount;//($line_total * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = $docrow->vat_amount;
													$unit_price = $item->unit_price;
													
													$total_dh = $vattotal_dh = 0;
												}
											} else {
												if($docrow->is_fc==1) {
													$quantity = $actual_quantity = $item->balance_quantity;
													$unit_price = $item->unit_price / $docrow->currency_rate;
													$line_total = number_format($quantity * $unit_price,2, '.', '');
													$total += $line_total;
													$vattotal += ($line_total * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($line_total * $item->vat)/100,2);
													
													$line_total_dh = $quantity * $item->unit_price;
													$total_dh += $line_total_dh;
													$vattotal += ($line_total_dh * $item->vat)/100;
												} else {
													$quantity = $actual_quantity = $item->balance_quantity;
													$unit_price = $item->unit_price;
													$line_total = $quantity * $unit_price;
													$total += $line_total;
													$vattotal += ($line_total * $item->vat)/100;
													//$nettotal += $total + $vattotal;
													$vat_amount = round(($line_total * $item->vat)/100,2);
													
													$total_dh = $vattotal_dh = 0;
												}
											} 
										?>
										
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
												@if($doctype=='QS')
												<input type="hidden" name="quote_sales_item_id[]" id="quote_sales_itmid_{{$i}}" value="{{$item->id}}">
												@elseif($doctype=='SO')
												<input type="hidden" name="sales_order_item_id[]" id="sales_order_itmid_{{$i}}" value="{{$item->id}}">
												@elseif($doctype=='CDO')
												<input type="hidden" name="customer_do_item_id[]" id="customer_do_item_id_{{$i}}" value="{{$item->id}}">
												@endif
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
													<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$i}}" value="{{$actual_quantity}}"> 
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" step="any" autocomplete="off" class="form-control line-quantity" value="{{$quantity}}">
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
													<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:$item->pkno.'-'.$item->packing}}">
													<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="{{$item->vat.'% - '.intval($vat_amount*100)/100}}"><!--<div class="h5" id="vatdiv_{{$i}}"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control vat" value="{{$item->vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}"  name="line_discount[]" >
													<input type="hidden" id="cqty_{{$i}}" name="curqty[]" value="{{$item->cur_quantity}}">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$line_total}}">
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
											
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
												<div class="form-group"><input type="text" name="iloc[]" id="iloc_{{$i}}" style="border:none;color:#FFF;"></div><!-- NOV24 -->
											</div>
											
											<!--MAY25-->
            								<div id="batchdiv_{{$i}}" style="float:left; padding-right:5px;" class="addBatchBtn">
            									<button type="button" id="btnBth_{{$i}}" class="btn btn-primary btn-xs batch-add" data-toggle="modal" data-target="#batch_modal">Add Batch</button>
            									<div class="form-group"><input type="text" name="batchNos[]" id="bthSelNos_{{$i}}" style="border:none;color:#FFF;" value="{{$batchitems[$item->id]['batches']}}"></div>
                                                <input type="hidden" id="bthSelQty_{{$i}}" name="qtyBatchs[]" value="{{$batchitems[$item->id]['qtys']}}">
            								</div>
            								
											@if($isconloc)
											<div id="cnloc" style="float:left; padding-right:5px;">
												<button type="button" id="cnloc_{{$i}}" data-toggle="modal" data-target="#conloc_modal" class="btn btn-primary btn-xs cnloc-info">Consignment Location</button>
											</div>
											<div style="float:left; padding-right:5px;">
												<input type="hidden" name="conloc_id[]" id="conlocid_{{$i}}" value="{{$item->conloc_id}}">
												<input type="hidden" name="conloc_qty[]" id="conlocqty_{{$i}}" value="{{$item->conloc_qty}}">
												<input type="hidden" name="conloc_id_old[]" value="{{$item->conloc_id}}">
												<input type="hidden" name="conloc_qty_old[]" value="{{$item->conloc_qty}}">
											</div>
											@endif

											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$i}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$i}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="custsaleshisItm_{{$i}}" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
										
										<?php if($formdata['dimension']==1) { ?>
								<div id="itmInfo">
									<button type="button" id="itmInfo_{{$i}}" class="btn btn-primary btn-xs dimn-view">Dimension</button>
								</div>
								<?php } else { ?>
								<input type="hidden" name="dimension" id="dimension">
								<?php } ?>

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
																<?php  $tQty = 0; //NOV24
																foreach($itemloc as $key => $row) {
																//MAR25..
																$quantity2 = $id = $qty_entry = '';
																$location_id = $row->id; $cqty = 0;//$row->cqty; MAR25
																if(array_key_exists($row->id, $itemlocedit[$item->id])) { 
																	//$cqty = $itemlocedit[$item->id][$location_id]->cqty;
																	$cqty = isset($itemlocedit[$item->id][$row->id])?$itemlocedit[$item->id][$row->id]->cqty:'';
																	$quantity2 = $quantity; //$itemlocedit[$item->id][$location_id]->quantity;
																	$location_id = $itemlocedit[$item->id][$location_id]->location_id;
																	$id = $itemlocedit[$item->id][$location_id]->id;
																	$qty_entry = isset($itemlocedit[$item->id][$row->id])?$itemlocedit[$item->id][$row->id]->qty_entry:'';
																	$tQty += $qty_entry; //NOV24
																} //...
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
														</div><script>document.getElementById('iloc_'+{{$i}}).value='{{$tQty}}';</script><!-- NOV24 -->
															<?php  } ?>
													</div>
												</div>
											</div>
											

											<div class="cnlocPrntItm" id="cnlocPrntItm_{{$i}}">
												<div class="cnlocChldItm">							
													<div class="table-responsive cnloc-data" id="cnlocData_{{$i}}">
														<?php 
														if(array_key_exists($item->id, $cnitemlocedit)) { 
														
															 ?>
														<br/>
														<div class="col-xs-5">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Consignment Location</th>
																	<th>Quantity</th>
																</tr>
																</thead>
																<tbody>
																<?php 
																foreach($cnitemloc as $key => $row) {
																
																$quantity = $id = '';
																$location_id = $row->id; $cqty = '';
																if(array_key_exists($key, $cnitemlocedit[$item->id])) { 
																	$quantity = $cnitemlocedit[$item->id][$key]->quantity;
																	$location_id = $cnitemlocedit[$item->id][$key]->location_id;
																	$id = $cnitemlocedit[$item->id][$key]->id;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td class="cnnum"><input type="number" name="cnlocqty[<?php echo $i-1;?>][]" value="{{$quantity}}" class="cnloc-qty-{{$i}}" data-id="{{$i}}">
																	<input type="hidden" name="cnlocid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="cneditid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
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
																<input type="text" name="itemdesc[<?php echo $i-1;?>][]" autocomplete="off" class="form-control txt-desc" value="{{$desc->description}}" placeholder="Description">
															</div>
															<div class="col-xs-2" style="float:left;">
																 <button type="button" class="btn btn-success btn-add-desc" >
																	<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
																 </button>
																 <button type="button" class="btn btn-danger btn-remove-desc" data-id="remdesc_{{$i}}" data-itmid="{{$item->id}}">
																	<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
																 </button>
																 <input type="hidden" name="desc_id[<?php echo $i-1;?>][]" value="{{$desc->id}}" class="hid-id">
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
								<?php $nettotal += $total + $vattotal - $docrow->discount; $nettotal_dh += $total_dh + $vattotal_dh;?>
								</fieldset>
								
								
									<fieldset>
									<legend>
									    <?php if($formdata['selling_expense']==1) { ?>
										<div id="oc_showmenu" style="padding-left:5px;"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Selling Expense</button></div>
										<?php } else { ?>
								<input type="hidden" name="selling_expense" id="selling_expense">
								<?php } ?>
									</legend>
									<div class="OCdivPrnt">
									<input type="hidden" id="ocrowNum" value="1">
									<div class="OCdivChld">
											<div class="form-group">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="10%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
													</td>
													<td width="15%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="10%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="15%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
														<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
													</td>
													<td width="3%"><br/>
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
								
								
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" value="{{(old('total'))?old('total'):($docrow->is_fc==1)?$docrow->total_fc:$docrow->total}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" value="{{(old('total_fc'))?old('total_fc'):$docrow->total}}" step="any" name="total_fc" class="form-control spl" value="{{$total_dh}}" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								@if($roundoff)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Round Off</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="roundoff" name="roundoff" value="{{old('roundoff')}}" class="form-control spl round-off" placeholder="0">
											<input type="hidden" id="discount" name="discount" value="{{old('discount')}}" class="form-control spl discount-cal">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="roundoff_fc" name="roundoff_fc" value="{{old('roundoff_fc')}}" class="form-control spl">
											<input type="hidden" id="discount_fc" name="discount_fc" value="{{old('discount_fc')}}" class="form-control spl">
										</div>
									</div>
                                </div>
								@else
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Discount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount" autocomplete="off" name="discount" class="form-control spl discount-cal" value="{{(old('discount'))?old('discount'):($docrow->is_fc==1)?$docrow->discount_fc:$docrow->discount}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control spl" value="{{(old('discount_fc'))?old('discount_fc'):$docrow->discount}}" placeholder="0">
										</div>
									</div>
                                </div>
                                @endif
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc" name="less_description" value="{{(old('less_description'))?old('less_description'):$docrow->less_description}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount" name="less_amount" value="{{(old('less_amount'))?old('less_amount'):$docrow->less_amount}}" class="form-control spl lesamt" placeholder="0">
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
											<input type="text" step="any" class="form-control spl" id="les_amt_desc2" name="less_description2" value="{{(old('less_description2'))?old('less_description2'):$docrow->less_description2}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount2" name="less_amount2" value="{{(old('less_amount2'))?old('less_amount2'):$docrow->less_amount2}}" class="form-control spl lesamt" placeholder="0">
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
											<input type="text" step="any" class="form-control spl" id="les_amt_desc3" name="less_description3" value="{{(old('less_description3'))?old('less_description3'):$docrow->less_description3}}" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount3" name="less_amount3" value="{{(old('less_amount3'))?old('less_amount3'):$docrow->less_amount3}}" class="form-control spl lesamt" placeholder="0">
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
											<input type="text" class="form-control spl" id="previnv_description" name="previnv_description" value="{{(old('previnv_description'))?old('previnv_description'):$docrow->previnv_description}}" placeholder="Previous Invoice Details" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="previnv_amount" name="previnv_amount" value="{{(old('previnv_amount'))?old('previnv_amount'):$docrow->previnv_amount}}" class="form-control spl lesamt" placeholder="0">
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
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):($docrow->is_fc==1)?$docrow->subtotal_fc:$docrow->subtotal}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" value="{{(old('subtotal_fc'))?old('subtotal_fc'):$docrow->subtotal}}" readonly placeholder="0">
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
											<input type="hidden" id="vatcur" name="vatcur" value="<?php echo (old('vatcur'))?old('vatcur'):$docrow->vat_amount;?>">
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" placeholder="0" value="{{(old('vat'))?old('vat'):($docrow->is_fc==1)?$docrow->vat_amount_fc:$docrow->vat_amount}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" value="{{(old('vat_fc'))?old('vat_fc'):$docrow->vat_amount}}" placeholder="0" readonly>
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
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" value="<?php echo (old('net_amount_hid'))?old('net_amount_hid'):$docrow->net_total;?>">
											<input type="number" step="any" id="net_amount" name="net_amount" value="{{(old('net_amount'))?old('net_amount'):($docrow->is_fc==1)?$docrow->net_total_fc:$docrow->net_total}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										    <input type="hidden" step="any" id="other_cost" name="other_cost">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly  value="<?php echo (old('net_amount_fc'))?old('net_amount_fc'):$docrow->net_total; ?>" placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								<?php if($formdata['footer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="footer_id" id="footer_id">
								<?php } ?>
								
									<?php if($formdata['footer_edit']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$docrow->foot_description}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								
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
										<a href="{{ url('customers_do') }}" class="btn btn-danger">Cancel</a>
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
							
							<div id="conloc_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Consignment Location</h4>
                                        </div>
                                        <div class="modal-body" id="conLocData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="view_conloc_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">View Consignment Location</h4>
                                        </div>
                                        <div class="modal-body" id="conlocVw">
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
       
        <!--MAY25-->
        <div id="batch_modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Batch</h4>
                    </div>
                    <div class="modal-body" id="batchData"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary saveBatch">Add</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
"use strict";

//MAY25
//$('.addBatchBtn').hide();
let bthArr = [];
let uniqueBtharr;
$('#due_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
var srvat={{$vatdata->percentage}}; var dptTxt;
$('.OCdivPrnt').toggle(); $('#roundoff_fc').toggle();
$(document).ready(function () { 
    if(	$('#duedays').val() >0){
		 var days=$('#duedays').val();
	     calculateDueDate(parseInt(days));
	}
    
	$('.crm_info').hide();
	$('.cnlocPrntItm').toggle();
	$('.descdivPrntItm').hide();//MY22
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	$('.dimnInfodivPrntItm').toggle();
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php if($docrow->is_fc==0) { ?>
		$("#subtotal_fc").toggle();
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	$('.locPrntItm').toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); 
	var urlcode = "{{ url('customers_do/checkrefno/') }}";
    $('#frmSalesInvoice').bootstrapValidator({
        fields: {
			/* reference_no: {
                validators: {
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
            }, */
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			//customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
			@if($formdata['location_item']==1) 'iloc[]': { validators: { notEmpty: { message: 'Item location quantity is required and cannot be empty!' } }}  @endif //NOV24
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
	function getNetTotal2() {
		
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
	
	//calculation other cost...
	function getOtherCost() {
		 
		var otherCost = 0; var otherCostTx = 0;
		if( $('.OCdivPrnt').is(":visible") ) { 
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var curNum = res[1];
				var ocamt_ln = this.value;
				otherCostTx = otherCostTx + parseFloat(ocamt_ln);
				otherCost = otherCost + parseFloat(ocamt_ln);
			}); 
			$('#other_cost').val(otherCostTx.toFixed(2));
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
		
		if($('#roundoff').length)
			var roundoff = parseFloat( ($('#roundoff').val()=='') ? 0 : $('#roundoff').val() );
		else
			var roundoff = 0;
		
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
				$('#net_amount').val( (subtotal - roundoff).toFixed(2) );
				
			} else if(taxinclude==true && discount>0) { 
				$('#subttle').html('');
				//$('#net_amount').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
				$('#subtotal').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
				$('#net_amount').val(subtotal.toFixed(2));
			} else {
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) + parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
			}
			
			if( $('#is_fc').is(":checked") ) {
				var crate = $('#currency_rate').val();
				discount = discount * crate;
				$('#discount_fc').val(discount.toFixed(2));
				$('#subtotal_fc').val( (subtotal*crate).toFixed(2) );
				$('#vat_fc').val( (vatnet*crate).toFixed(2) );
				$('#net_amount_fc').val( (parseFloat($('#subtotal_fc').val()) + parseFloat($('#vat_fc').val()) ).toFixed(2) );
			}
		
		return true;
	}
	
	function calculateRoundoff()
	{ 
		calculateDiscount();
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
				$('#itmcst_'+curNum).val(data);
				return true;
			}
		}) 
	}
	
	function calculateDueDate(days) {
         const today = new Date();
         const dueDate = new Date();
         dueDate.setDate(today.getDate() + days);
         const due= dueDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD
         const formatted = dueDate.toLocaleDateString('es-CL'); // DD/MM/YYYY
          console.log(formatted);
        $('#due_date').val(formatted);

    }		

$(function() {	
	$(document).on('click', '#crmBtn', function(e) { 
		   e.preventDefault();
           $('.crm_info').toggle();
    });
	
	var rowNum = $('#rowNum').val();
	$('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		dptTxt = $( "#department_id option:selected" ).text();
		$.get("{{ url('sales_invoice/getdeptvoucher/') }}/" + dept_id, function(data) { 
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
				$.each(data, function(key, value) {  
					
					$('#voucher_id').find('option').end()
							.append($("<option></option>")
							.attr("value",value.voucher_id)
							.text(value.voucher_name)); 
				});
			
			
			$('#curno').val(data[0].voucher_no); //CHNG
			$('#is_cash').val(data[0].cash_voucher); //CHNG
			
			if(data[0].account_id!=null && data[0].account_name!=null) {
				$('#sales_account').val(data[0].account_id+'-'+data[0].account_name);
				$('#cr_account_id').val(data[0].id);
			} else {
				$('#sales_account').val('');
				$('#cr_account_id').val('');
			}
			
			if(data[0].cash_voucher==1) {
				if( $('#newcustomerInfo').is(":hidden") )
					$('#newcustomerInfo').toggle();
			
				if( $('#customerInfo').is(":visible") )
					$('#customerInfo').toggle();
				$('#customer_name').val(data[0].default_account);
				$('#customer_id').val(data[0].cash_account);
				$('#dr_account_id').val(data[0].cash_account);
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

			newEntry.find($('.cnlocPrntItm')).attr('id', 'cnlocPrntItm_' + rowNum);
			newEntry.find($('.cnloc-info')).attr('id', 'cnloc_' + rowNum);
			newEntry.find($('.cnloc-data')).attr('id', 'cnlocData_' + rowNum);

			$('#locData_'+rowNum).html('');
			
			newEntry.find($('.dimnInfodivPrntItm')).attr('id', 'dimnInfodivPrntItm_' + rowNum);
newEntry.find($('.dimn-item-data')).attr('id', 'dimnitemData_' + rowNum);
newEntry.find($('.dimn-view')).attr('id', 'itmInfo_' + rowNum);

            newEntry.find($('input[name="iloc[]"]')).attr('id', 'iloc_' + rowNum); //NOV24
            @if($formdata['location_item']==1)
			    newEntry.find( $('#frmSalesInvoice').bootstrapValidator('addField',"iloc[]") ); //NOV24
			@endif

			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			newEntry.find($('.cust-sales-his')).attr('id', 'custsaleshisItm_' + rowNum);
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.view-cnloc-info')).attr('id', 'cnlocVw_' + rowNum);
			newEntry.find($('input[name="conloc_id[]"]')).attr('id', 'conlocid_' + rowNum); 
			newEntry.find($('input[name="conloc_qty[]"]')).attr('id', 'conlocqty_' + rowNum);
			
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
	
	var ocrowNum = $('#ocrowNum').val();
	$(document).on('click', '.btn-add-oc', function(e) 
    { 
        ocrowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .OCdivPrnt'),
            currentEntry = $(this).parents('.OCdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="dr_acnt_id[]"]')).attr('id', 'dracntid_' + ocrowNum);
			newEntry.find($('input[name="dr_acnt[]"]')).attr('id', 'dracnt_' + ocrowNum);
			newEntry.find($('input[name="oc_reference[]"]')).attr('id', 'ocref_' + ocrowNum);
			newEntry.find($('input[name="oc_description[]"]')).attr('id', 'ocdesc_' + ocrowNum);
			newEntry.find($('input[name="oc_amount[]"]')).attr('id', 'ocamt_' + ocrowNum);
			newEntry.find($('input[name="cr_acnt_id[]"]')).attr('id', 'cracntid_' + ocrowNum);
			newEntry.find($('input[name="cr_acnt[]"]')).attr('id', 'cracnt_' + ocrowNum);
			newEntry.find('input').val(''); 
			$('#vatocamt_' + ocrowNum).val(5);
			controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
		var res = getOtherCost();
		if(res) 
			getNetTotal();
		
		e.preventDefault();
		return false;
	});
	
	
	
		$(document).on('keyup', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
		$(document).on('click', '#account_data .custRow', function(e) { 
		var num = $('#account_data #num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#account_data #num').val(); console.log(num);
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#paccount_data #anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
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
	
		$('#duedays').on('blur', function(e){
	     var days=$('#duedays').val()
	    calculateDueDate(parseInt(days));
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

		
	//new change............ APR25
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		$('#item_data').load(itmurl+'/'+curNum+'/ser', function(result){ //.modal-body item
			$('#myModal').modal({show:true});
			if($('#p_orditmid_'+curNum).val()!='')
				resetValues(curNum);
		});
		
	});
	
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/ser', function(result){  //APR25
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('keyup', '.round-off', function(e) { 
		calculateRoundoff();
	});
	
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#lbnum').val(); //APR25
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
		
	});

	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('sales_invoice/getvoucher/') }}/" + vchr_id, function(data) {
			$('#voucher_no').attr('placeholder', data.voucher_no); //$('#voucher_no').val(data.voucher_no);
			$('#sales_account').val(data.account_id+'-'+data.account_name);
			$('#cr_account_id').val(data.id);
			//$('#dr_account_id').val(data.cr_id);
			//$('#customer_name').val(data.cr_account+'-'+data.cr_master);
		});
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
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		if(parseFloat(this.value) > parseFloat( $('#itmactqty_'+curNum).val() )) {
			alert('Quantity should not exceed than Quotation/SO/DO.');
			$('#itmqty_'+curNum).val( $('#itmactqty_'+curNum).val() );
		}
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
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
				@permission('-qty-sale')
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
				@endpermission
				<?php //} ?>
			});
		}
	});
	
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();

		@permission('si-create')
			//MAR18
			var rate = 1;
			if( $('#is_fc').is(":checked") ) { 
				var rate = parseFloat($('#currency_rate').val());
			}//..
			var salecost = parseFloat( $('#costavg_'+curNum).val() ); 
			if(this.value!='' && this.value!=0 && salecost > (this.value * rate)) { //MAR18
				alert('This item price is lower than cost avg.');
				$('#itmcst_'+curNum).val('');
				$('#itmcst_'+curNum).focus();
			}
		@endpermission
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
			//console.log(data);
		});
	});
	
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
	
	/*$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+item_id
		   $('#locData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#locPrntItm_'+curNum).toggle();
	   }
    });*/

	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
		//if( $('#locPrntItm_'+rowNum).is(":visible") ) 
			$('#locPrntItm_'+rowNum).toggle();
	});

    /* $(document).on('click', '.cnloc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
		 $('#cnlocPrntItm_'+rowNum).toggle();
	}); */
	
	var cnlocUrl = "{{ url('itemmaster/conloc_data/') }}";
	$(document).on('click', '.cnloc-info', function(e) { 
	   e.preventDefault();
	    var res = this.id.split('_');
		var curNum = res[1]; 
		var item_id = $('#itmid_'+curNum).val();
		var row_id = $('#customer_do_item_id_'+curNum).val();
	    var cst_id = $('#customer_id').val();

		$('#conLocData').load(cnlocUrl+'/'+curNum+'/'+cst_id+'/'+item_id+'/DO-'+row_id, function(result){
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
    });
	
	$(document).on('click', '.add-loc-qty', function(e)  { 
		var locid = []; var locqty = [];
		$("input[name='lcid[]']:checked").each(function(){
			locid.push($(this).val());
			locqty.push( $('#clqty_'+$(this).val().toString()).val() );
			
		});
		//console.log('v'+locid+' q'+locqty);
		 var nm = $('#numasm').val(); 
		 $('#conlocid_'+nm).val(locid);  //$('#clocid').val()
		 $('#conlocqty_'+nm).val(locqty);  //$('#clocqty').val()
		 
		 let qtyout = 0;
		 var qtyarr = $('#conlocqty_'+nm).val().split(',');
		 $.each(qtyarr , function(index, val) { 
		   qtyout = qtyout + parseFloat(val);
		});
		$('#itmqty_'+nm).val(qtyout);
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
	
	$(document).on('keyup', '#less_amount, #previnv_amount', function(e) { 
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
	
	$(document).on('click', '.view-cnloc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var rwid = $('#customer_do_item_id_'+curNum).val();
	   var cnloc_id = $('#hdlocid_'+curNum).val(); 
	   var cnloc_qty = $('#hdlocqty_'+curNum).val(); 
	   if(cnloc_id!='') {
		   var vwUrl = "{{ url('itemmaster/view_conloc_items/') }}/"+cnloc_id+'/'+cnloc_qty+'/DO/'+curNum+'/'+rwid; 
		   $('#conlocVw').load(vwUrl, function(result) {
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

	$(document).on('blur', '#voucher_no', function(e) {  
		if(parseInt($(this).val()) > parseInt($('#curno').val())) {
			alert('Voucher no is greater than current range!');
			$('#voucher_no').val('');
		}
	});
	
	//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('sales_invoice/checkvchrno/') }}", 
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
	
	//MAY25 BATCH SYSTEM...
	
	$(document).on('click', '.batch-add', function(e) { 
       
       var res = this.id.split('_');
	   var n = res[1];
	   
	   var item_id = $('#itmid_'+n).val(); 
	   var batch = $('#bthSelIds_'+n).val(); 
	   var btqty = $('#bthSelQty_'+n).val(); 
       var batchurl = "{{ url('itemmaster/batch-get') }}";
       
       if(batch!='') {
    	   var vwUrl = batchurl+'?item_id='+item_id+'&batch='+batch+'&qty='+btqty+'&act=add&no='+n; 
    	   $('#batchData').load(vwUrl, function(result) {
    		  $('#myModal').modal({show:true}); 
    	   });
    	   
       } else {	  
           
           if(item_id!='') {
        	   var vwUrl = batchurl+'?item_id='+item_id+'&act=add&no='+n; 
        	   $('#batchData').load(vwUrl, function(result) {
        		  $('#myModal').modal({show:true}); 
        	   });
           } 
       }
    });
    
     
    $(document).on('click', '.saveBatch', function(e)  { 
         e.preventDefault();
        var isvalid = true;
        var batchId = '';
        var qtyBatch = '';
        var totalQty = parseFloat(0);
        
        $( '.req-bqty' ).each(function() { 
            
    		  var res = this.id.split('_');
    		  var n = res[1];
    		  
    		  if( $('#bthqty_'+n).is(':not(:disabled)') && this.value=='') {
    		      alert('Quantity is required!');
    		      isvalid = false;
    		      return false;
    		  } 
    		  
    		  if( $('#bthqty_'+n).is(':enabled') && this.value!='') {
    		      
    		      batchId = (batchId=='')?n:batchId+','+n; //var n is batch id
        	    
        	      bthArr.push(n);
        	      uniqueBtharr = bthArr.filter((value, index, self) => self.indexOf(value) === index);
        	      
        	      qtyBatch = (qtyBatch=='')?$(this).val():qtyBatch+','+$(this).val();
        	      totalQty += parseFloat($(this).val());
    		  }
		 
		});
		
		if(isvalid==true) {
    		 var row_no = $('#row_no').val();

    		 $('#bthSelIds_'+row_no).val( batchId );
    		 $('#bthSelQty_'+row_no).val( qtyBatch );
    		 $('#batch_modal').modal('hide');
		}
	});
	
	$(document).on('change', '.chk-batch', function(e) {  
    
        if( $(this).is(":checked") ) { 
        	
        	$('#bthqty_'+$(this).val().toString()).prop("disabled", false);
        } else {
            
        	$('#bthqty_'+$(this).val().toString()).prop("disabled", true);
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

function getDocument() { 
	if($("#customer_name").val()=='') {
		alert('Please select a customer first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var customer_id = $("#customer_id").val();
	var doc = $('#document_type option:selected').val();
	if(doc==1)
		var pourl = "{{ url('quotation_sales/get_quotation/') }}/"+customer_id+"/cdo";
	else if(doc==2)
		var pourl = "{{ url('sales_order/get_order/') }}/"+customer_id;
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function removeAr(arr) {
	var what, a = arguments, L = a.length, ax;
	while (L > 1 && arr.length) {
		what = a[--L];
		while ((ax= arr.indexOf(what)) !== -1) {
			arr.splice(ax, 1);
		}
	}
	return arr;
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

</script>
@stop
