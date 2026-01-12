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
                Goods Receipt Note
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Goods Receipt Note</a>
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
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
					
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Goods Receipt Note
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('po-print')
								 <a href="{{ url('suppliers_do/print/'.$printid->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
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
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" enctype="multipart/form-data" action="{{ url('suppliers_do/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{(Session::get('mod_location')==1)?Auth::user()->location_id:Session::get('location_id')}}"/>
								<div class="form-group">
                                   <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>GR. No.</b></label></font>
                                    <div class="col-sm-10">
										<div class="input-group">
                                         <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" placeholder="{{$voucherno}}">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
                                    </div>
                                </div>
								<input type="hidden" name="curno" id="curno" value="{{$voucherno}}">
								<input type="hidden" value="1" name="autoincrement">
								 
                                <?php if($formdata['copy_from']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Paste From</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="supplierdo_id" readonly name="supplierdo_id" placeholder="Copy from existing SO" autocomplete="off" onclick="getSupplierDo()">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="supplierdo_id" id="supplierdo_id">
								<?php } ?>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">

                                 <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>"><b>Reference No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" autocomplete="off" value="{{ old('reference_no') }}" placeholder="Reference No.">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">GR. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								
								<?php if($formdata['lpo_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" autocomplete="off" placeholder="LPO No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_no" id="lpo_no">
								<?php } ?>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" autocomplete="off" data-language='en' readonly placeholder="LPO Date">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
																
								<?php if($formdata['purchase_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Type</label>
                                    <div class="col-sm-10">
                                       <select id="sales_type" class="form-control select2" style="width:100%" name="sales_type">
											<option value="normal">Normal</option>
											<option value="ltol">Location to Location</option>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
									<select id="sales_type" style="display:none" name="sales_type">
										<option value="normal">Normal</option>
                                    </select>
								<?php } ?>
								
																
								<div class="form-group">
                                  <font color="#16A085">   <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" class="form-control <?php if($errors->has('supplier_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') }}">
									</div>
                                </div>
								
								<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" autocomplete="off" name="vat_no" value="{{ old('vat_no') }}" placeholder="TRN No.">
                                    </div>
                                </div>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type</label>
                                    <div class="col-sm-10">
                                       <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
                                           <option value="">Select Document...</option>
										   <option value="PO">Purchase Order</option>
										   <option value="MR">Material Requisition</option>
										   <!--<option value="SDO">Supplier Delivery Order</option>-->
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
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php } ?>
								
								<?php if($formdata['po_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Order No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="po_no" name="po_no" placeholder="Purchase Order No" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="po_no" id="po_no">
								<?php } ?>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" autocomplete="off" placeholder="Description">
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
											<option value="{{ $term['id'] }}" <?php if($term['id']==old('terms_id')) echo 'selected'; ?>>{{ $term['description'] }}</option>
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
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
										<input type="hidden" name="job_id" id="job_id">
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
											<option value="{{ $loc['id'] }}" <?php if($loc['id']==old('location_id')) echo 'selected'; ?>>{{ $loc['name'] }}</option>
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
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" @if(old('is_fc')=="1") checked @endif>
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}" <?php if($curr['id']==old('currency_id')) echo 'selected'; ?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" value="{{ old('currency_rate') }}" placeholder="Currency Rate">
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
											<input type="checkbox" class="import" id="import" name="is_import" value="1" @if(old('is_import')=="1") checked @endif>
											</label>
										</div>
									</div>
                                </div>
									<?php if($formdata['location']==1) { ?>
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
                                <?php } else { ?>
								<input type="hidden" name="location_id" id="location_id">
								<?php } ?>
								
								<?php if($formdata['item_import']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Items Import</label>
                                    <div class="col-sm-9">
                                        <input id="input-23" name="import_file" type="file" class="file-loading" data-show-preview="false">
                                    </div>
									 <div class="col-sm-1"><button type="button" class="btn btn-primary" id="importFile" >Load</button></div>
                                </div>
								<?php } ?>
								
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
									<div class="itemdivPrnt">
										<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
										<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
														<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" value="{{ $item }}" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_{{$j}}" value="{{ old('item_name')[$i]}}" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_{{$j}}" value="{{ old('quantity')[$i]}}" autocomplete="off" step="any" name="quantity[]" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_{{$j}}" value="{{ old('cost')[$i]}}" autocomplete="off" step="any" name="cost[]" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
													</td>
													<td width="6%">
														<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
														<option value="SR" @if(old('tax_code')[$i]=="SR") selected @endif>SR</option><option value="RC" @if(old('tax_code')[$i]=="RC") selected @endif>RC</option><option value="ZR" @if(old('tax_code')[$i]=="ZR") selected @endif>ZR</option><option value="EX" @if(old('tax_code')[$i]=="EX") selected @endif>EX</option></select>
													</td>
													<td width="6%">
														<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
													</td>
													<td width="9%">
														<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
														<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
														<input type="text" id="vatdiv_{{$j}}" value="{{ old('vatdiv')[$i]}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amount">
														<input type="hidden" id="vat_{{$j}}" name="line_vat[]" value="{{ old('line_vat')[$i]}}" class="form-control vat">
														<input type="hidden" id="vatlineamt_{{$j}}" value="{{ old('vatline_amt')[$i]}}" name="vatline_amt[]" class="form-control vatline-amt" value="0">
														<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" value="{{ old('line_discount')[$i]}}">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" value="{{ old('line_total')[$i]}}" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('item_total')[$i]}}">
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
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
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
								            <?php } ?>
								            <?php if($formdata['sales']==1) { ?>
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
								              <input type="hidden" name="netcost" id="netcost">
								            <?php } ?>
								            <?php if($formdata['location_item']==1) { ?>
											
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="item_loc" id="item_loc">
								            <?php } ?>
								            <?php if($formdata['supersede']==1) { ?>
											<div id="ssede">
												<button type="button" id="sede_{{$j}}" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="supersede" id="supersede">
								            <?php } ?>
											
											<div class="form-group oc" id="othrcstItm_{{$j}}">
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
														<span class="small">Other Cost/Unit</span> <input type="text" id="othrcst_{{$j}}" value="{{old('othr_cost')[$i]}}" step="any" name="othr_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-2">
														<span class="small">Net Cost/Unit</span> <input type="text" id="netcst_{{$j}}" step="any" value="{{old('net_cost')[$i]}}" name="net_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-1">
														 <!--<button type="button" class="btn btn-success btn-add-item" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>-->
													</div>
												</div>	
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
												
												<div class="sedePrntItm" id="sedePrntItm_{{$j}}">
													<div class="sedeChldItm">							
														<div class="table-responsive sede-data" id="sedeData_{{$j}}"></div>
													</div>
												</div>
											
										</div>
										<?php $i++; } } else { ?>
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="item_id[]" id="itmid_1">
														<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_1" autocomplete="off" step="any" name="quantity[]" class="form-control line-quantity" placeholder="Qty." {{($formdata['location_item']==1)?'readonly':''}} ><!--MAY25-->
													</td>
													<td width="8%">
														<input type="number" id="itmcst_1" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="6%">
														<select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
														<option value="SR">SR</option><option value="RC">RC</option><option value="ZR">ZR</option><option value="EX">EX</option></select>
													</td>
													<td width="6%">
														<select id="txincld_1" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
													</td>
													<td width="9%">
														<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
														<input type="hidden" id="packing_1" name="packing[]" value="1">
														<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amount">
														<input type="hidden" id="vat_1" name="line_vat[]" class="form-control vat">
														<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
														<input type="hidden" id="itmdsnt_1" name="line_discount[]">
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
											
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="more_info" id="more_info">
								            <?php } ?>
								             <?php if($formdata['purchase']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_1" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="purchase" id="purchase">
								            <?php } ?>
								            <?php if($formdata['sales']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_1" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="sales" id="sales">
								            <?php } ?>
											<?php if($formdata['net_cost']==1) { ?>
										
											<div id="othrcst" style="float:left;padding-right:5px;">
												<button type="button" id="ocost_1" class="btn btn-primary btn-xs net-cost">Net Cost/Unit</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="netcost" id="netcost">
								            <?php } ?>
								            <?php if($formdata['location_item']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
												<div class="form-group"><input type="text" name="iloc[]" id="iloc_1" style="border:none;color:#FFF;"></div><!-- NOV24 -->
											</div>
											<?php } else { ?>
								              <input type="hidden" name="item_loc" id="item_loc">
								            <?php } ?>
								            <?php if($formdata['supersede']==1) { ?>
											
											<div id="ssede">
												<button type="button" id="sede_1" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="supersede" id="supersede">
								            <?php } ?>
								            
								            <!--MAY25-->
											<div id="batchdiv_1" style="float:left; padding-right:5px;" class="addBatchBtn">
												<button type="button" id="btnBth_1" class="btn btn-primary btn-xs batch-add" data-toggle="modal" data-target="#batch_modal">Add Batch</button>
												<div class="form-group"><input type="text" name="batchNos[]" id="batchNos_1" style="border:none;color:#FFF;"></div>
												<input type="hidden" id="mfgDates_1" name="mfgDates[]">
                                                <input type="hidden" id="expDates_1" name="expDates[]">
                                                <input type="hidden" id="qtyBatchs_1" name="qtyBatchs[]">
											</div>
										
											
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

												<div class="infodivPrntItm" id="infodivPrntItm_1">
													<div class="infodivChldItm">							
														<div class="table-responsive item-data" id="itemData_1"></div>
													</div>
												</div>
												
												<div class="locPrntItm" id="locPrntItm_1">
													<div class="locChldItm">							
														<div class="table-responsive loc-data" id="locData_1" data-id="1"></div>
													</div>
												</div>
												
												<div class="sedePrntItm" id="sedePrntItm_1">
													<div class="sedeChldItm">							
														<div class="table-responsive sede-data" id="sedeData_1"></div>
													</div>
												</div>
										</div>
										
										<?php } ?>
									</div>
								
								</fieldset>

								<fieldset>
									<legend>
									    <?php if($formdata['other_cost']==1) { ?>
										<div id="oc_showmenu" style="padding-left:5px;"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
										<?php } else { ?>
								              <input type="hidden" name="ocadd" id="ocadd">
								         <?php } ?>
									</legend>
									<div class="OCdivPrnt">
									<?php if(old('dr_acnt')) { $k = 0; 
										$ocnum = count( old('dr_acnt') ); ?>
										<input type="hidden" id="ocrowNum" value="{{$ocnum}}">
										<?php foreach(old('dr_acnt') as $ocitem) { $l = $k+1;?>
										<div class="OCdivChld">
											<div class="form-group">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<input type="hidden" name="dr_acnt_id[]" value="{{ old('dr_acnt_id')[$k]}}" id="dracntid_{{$l}}">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_{{$l}}" name="dr_acnt[]" class="form-control" autocomplete="off" value="{{ old('dr_acnt')[$k]}}" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="1%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_{{$l}}" autocomplete="off" class="form-control" value="{{ old('oc_reference')[$k]}}" placeholder="Reference">
													</td>
													<td width="15%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_{{$l}}" autocomplete="off" class="form-control" value="{{ old('oc_description')[$k]}}" placeholder="Description">
													</td>
													<td width="8%">
															<span class="small">Currency</span>
															<select id="occrncy_{{$l}}" class="form-control select2 oc-curr" style="width:100%" name="oc_currency[]">
																@foreach($currency as $curr)
																<option value="{{$curr['id']}}">{{$curr['code']}}</option>
																@endforeach
															</select>
													</td>
													<td width="8%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_{{$l}}" autocomplete="off" value="{{ old('oc_amount')[$k]}}" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="7%">
														<span class="small">Rate</span>
														<input type="number" name="oc_rate[]" id="ocrate_{{$l}}" step="any" value="1"  class="form-control oc-rate" placeholder="Rate">
													</td>
													<td width="10%">
															<span class="small">FC Amount</span>
															<input type="number" name="oc_fc_amount[]" id="ocfcamt_{{$l}}" step="any" value="{{ old('oc_fc_amount')[$k]}}" autocomplete="off" class="form-control oc-line-fc" placeholder="FC Amount">
													</td>
													<td width="5%">
														<span class="small">VAT%</span>
														<input type="number" name="vat_oc[]" step="any" id="vatocamt_{{$l}}" autocomplete="off" value="{{ old('vat_oc')[$k]}}" class="form-control oc-line-vat" value="5" readonly>
													</td>
													<td width="5%">
														<span class="small">Tx.Code</span>
														<select class="form-control select2" style="width:100%" name="tax_sr[]">
														<option value="SR" @if(old('tax_sr')[$k]=="SR") selected @endif>SR</option><option value="RC" @if(old('tax_sr')[$k]=="RC") selected @endif>RC</option><option value="ZR" @if(old('tax_sr')[$k]=="ZR") selected @endif>ZR</option><option value="EX" @if(old('tax_sr')[$k]=="EX") selected @endif>EX</option>
														</select>
													</td>
													<td width="18%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_{{$l}}" value="{{ old('cr_acnt_id')[$k]}}">
														<input type="text" id="cracnt_{{$l}}" name="cr_acnt[]" class="form-control" value="{{ old('cr_acnt')[$k]}}" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
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
									<?php $k++; } } else { ?>
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
													<td width="8%">
															<span class="small">Currency</span>
															<select id="occrncy_1" class="form-control select2 oc-curr" style="width:100%" name="oc_currency[]">
																@foreach($currency as $curr)
																<option value="{{$curr['id']}}">{{$curr['code']}}</option>
																@endforeach
															</select>
													</td>
													<td width="10%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="7%">
														<span class="small">Rate</span>
														<input type="number" name="oc_rate[]" id="ocrate_1" step="any" value="1" class="form-control oc-rate" placeholder="Rate">
													</td>
													<td width="10%">
														<span class="small">Convrt Amt</span>
														<input type="number" name="oc_fc_amount[]" id="ocfcamt_1" step="any" readonly class="form-control oc-line-fc" placeholder="Convrt Amt">
													</td>
													<td width="5%">
														<span class="small">VAT%</span>
														<input type="number" name="vat_oc[]" step="any" id="vatocamt_1" autocomplete="off" class="form-control oc-line-vat" value="5">
													</td>
													<td width="5%">
														<span class="small">Tx.Code</span>
														<select class="form-control select2" style="width:100%" name="tax_sr[]">
														<option value="SR">SR</option><option value="RC">RC</option><option value="ZR">ZR</option><option value="EX">EX</option>
														</select>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{old('total')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency{{$bcurrency}}</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly value="{{old('total_fc')}}" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{old('discount')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" autocomplete="off" class="form-control spl" value="{{old('discount_fc')}}" placeholder="0">
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
											<input type="number" id="subtotal" step="any" name="subtotal" class="form-control spl" readonly value="{{old('subtotal')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" id="subtotal_fc" step="any" name="subtotal_fc" class="form-control spl" readonly value="{{old('subtotal_fc')}}" placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" placeholder="0" value="{{old('vat')}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" placeholder="0" value="{{old('vat_fc')}}" readonly>
										</div>
									</div>
                                </div>
								
								<input type="hidden" step="any" id="other_cost" name="other_cost" readonly class="form-control" value="{{old('other_cost')}}" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control spl" value="{{old('other_cost_fc')}}" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" class="form-control" value="{{old('net_amount_hid')}}" readonly>
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{old('net_amount')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly value="{{old('net_amount_fc')}}" placeholder="0">
										</div>
									</div>
                                </div>
                                
                                <?php if($formdata['footer_edit']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$footer}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('suppliers_do') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('suppliers_do/add') }}" class="btn btn-warning">Clear</a>
										@can('pi-history')
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
var lprice; var packing = 1;

//MAY25
$('.addBatchBtn').hide();
let bthArr = [];
let uniqueBtharr;
$('.mfg-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
$('.exp-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );

 var taxinclude = false; var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>;
 var dptTxt;
$(document).ready(function () { 
	
	if($('#department_id').length) {
		dptTxt = $( "#department_id option:selected" ).text();
	}
	$('.OCdivPrnt').toggle();
	//ROWCHNG
	<?php if(!old('item_code')) { ?>
	$('.btn-remove-item').hide();
	<?php } ?>
	
	<?php if(old('is_fc')=="1") { ?> $('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $("#subtotal_fc").toggle(); $("#other_cost_fc").toggle(); //$('.oc-amount-fc').toggle();
	});
	<?php } else { ?>
	$("#fc_label").toggle(); $("#c_label").toggle();$("#subtotal_fc").toggle(); $("#other_cost_fc").toggle();
	$("#total_fc").toggle();$("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php } ?>
	
	$('.btn-remove-item').hide(); 
	$('#saleloc').toggle();
	  $('#trninfo').toggle();
	$('#othrcstItm_1').toggle();$('.locPrntItm').toggle();
	  $('.infodivPrnt').toggle(); 
	 $('.infodivPrntItm').toggle();
	 

	 <?php  if (isset(old('dr_acnt')[0]) && isset(old('dr_acnt')[0])) { ?>
		<?php if(!old('dr_acnt')[0]) { ?>	
			$('.OCdivPrnt').toggle();$('.oc-amount-fc').toggle();
		<?php  } ?> 
	<?php  } ?>
	var urlvchr = "{{ url('suppliers_do/checkvchrno/') }}";
	var urlcode = "{{ url('suppliers_do/checkrefno/') }}"; //CHNG
	$('.sedePrntItm').toggle();
	
	<?php if($formdata['item_import']==0) { ?>
    $('#frmPurchaseInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: {
                validators: {
                   
					/*remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This PI. No. is already exist!'
                    }*/
                }
            },
			reference_no: {
                validators: {
                   notEmpty: {  message: 'The reference no is required and cannot be empty!' }, 
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
            @if($formdata['location_item']==1) ,'iloc[]': { validators: { notEmpty: { message: 'Item location quantity is required and cannot be empty!' } }}  @endif //NOV24
			
        }
        
    }).on('reset', function (event) {
        $('#frmPurchaseInvoice').data('bootstrapValidator').resetForm();
    });
	<?php } ?>
	
		
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();$("#subtotal_fc").toggle();//$('.oc-amount-fc').toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();$("#subtotal_fc").toggle(); //$('.oc-amount-fc').toggle();
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
			var fcTotal    = lineTotal*rate;//total * rate;
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var subfc = lineTotal * rate;
			$('#subtotal_fc').val(subfc.toFixed(2));
			var fcTax = vat * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax.toFixed(2));
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
		var cr = $('#currency_rate').val();
		if($('#sales_type option:selected').val()=='normal') {
			$.ajax({
				url: "{{ url('itemmaster/get_purchase_cost/') }}",
				type: 'get',
				data: 'item_id='+item_id+'&unit_id='+unit_id+'&supplier_id='+supplier_id+'&cr='+cr,
				success: function(data) {
					$('#itmcst_'+curNum).val((data==0)?'':data);
					lprice = data;
					return true;
				}
			})
			
		} else {
			$.ajax({
				url: "{{ url('itemmaster/get_cost_avg/') }}", //
				type: 'get',
				data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#itmcst_'+curNum).val(data);
					$('#itmcst_'+curNum).focus();
					return true;
				}
			});
		}
		
	}
	
	//calculation other cost...
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
	
	$(document).on('click', '.net-cost', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
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
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			//newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum);
			
			//MAY25..
			newEntry.find($('.btn-remove-item')).attr('data-id', 'rem_' + rowNum);
			newEntry.find($('.batch-add')).attr('id', 'btnBth_' + rowNum);
			newEntry.find($('input[name="batchNos[]"]')).attr('id', 'batchNos_' + rowNum);
			newEntry.find($('input[name="mfgDates[]"]')).attr('id', 'mfgDates_' + rowNum);
			newEntry.find($('input[name="expDates[]"]')).attr('id', 'expDates_' + rowNum);
			newEntry.find($('input[name="qtyBatchs[]"]')).attr('id', 'qtyBatchs_' + rowNum);
			newEntry.find($('.addBatchBtn')).attr('id', 'batchdiv_' + rowNum);
			$('#itmqty_'+rowNum).attr('readonly', false);
			//...
			
			newEntry.find($('.oc')).attr('id', 'othrcstItm_' + rowNum);
			newEntry.find($('.net-cost')).attr('id', 'ocost_' + rowNum); 
				newEntry.find($('.sedePrntItm')).attr('id', 'sedePrntItm_' + rowNum);
				newEntry.find($('.sup-sede')).attr('id', 'sede_' + rowNum); 
				newEntry.find($('.sede-data')).attr('id', 'sedeData_' + rowNum);
				$('#sedeData_'+rowNum).html('');
			$('#locData_'+rowNum).html('');
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('.taxinclude')).attr('id', 'txincld_' + rowNum);//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			newEntry.find($('input[name="iloc[]"]')).attr('id', 'iloc_' + rowNum); //NOV24
			@if($formdata['location_item']==1)
		    	newEntry.find( $('#frmPurchaseInvoice').bootstrapValidator('addField',"iloc[]") ); //NOV24
		    @endif
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
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
			
			/*  controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
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
			newEntry.find($('input[name="vat_oc[]"]')).attr('id', 'vatocamt_' + ocrowNum);
			newEntry.find($('.oc-curr')).attr('id', 'occrncy_' + ocrowNum);
			newEntry.find($('input[name="oc_rate[]"]')).attr('id', 'ocrate_' + ocrowNum);
			newEntry.find($('input[name="oc_fc_amount[]"]')).attr('id', 'ocfcamt_' + ocrowNum);
			newEntry.find('input').val(''); 
			$('#vatocamt_' + ocrowNum).val(5);
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
		
		if($(this).attr("data-type")==2)
			$('#iloc_'+num).val(1);
		else
			$('#iloc_'+num).val('');
			
	    //MAY25
	    if($(this).attr("data-batch-req")==1) {
	        $('#itmqty_'+num).attr('readonly', true);
	        $('#batchdiv_'+num).show();
	        $('#frmPurchaseInvoice').bootstrapValidator('addField', 'batchNos[]');
	        $('#frmPurchaseInvoice').data('bootstrapValidator')
                .addField('batchNos[]', {
                    validators: {
                        notEmpty: {
                            message: 'Batch no is required!'
                        }
                    }
            });
	    } else
	        $('#batchdiv_'+num).hide();
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name));
						$('#hidunit_'+num).val(value.unit_name);
			});

		});
		
		//DEC24
		$('#itmqty_'+num).val('');
		var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+itm_id+"/"+num
	   $('#locData_'+num).load(locUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	   @if($formdata['location_item']==1)
	    $('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'iloc[]');
	   @endif
	});
	
	var supurl;
	$('#supplier_name').click(function() {
		if($('#department_id').length)
			supurl = "{{ url('purchase_order/supplier_datadept/') }}"+'/'+$('#department_id option:selected').val();
		else
			supurl = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
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
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
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
	
	//updated mar 18...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		$('.loc-qty-'+curNum).val('');
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
		//$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
		
		if(curNum==1) {
    		@if(Session::get('item_location_warn')==1)
    		    alert('You should specify the quantity in location wise! otherwise default location will be allocate.');
    		@endif
		}
	});
	
	$(document).on('blur', '.line-cost', function(e) { 
		/* if( parseFloat(lprice) < parseFloat(this.value) ) {
			alert('This price is greater than your last purchase.');
		} */
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
				$('#supplier_name').val(data.customer_name);
				$('#supplier_id').val(data.account_id);
				//$('#dr_account_id').val(data.account_id);
			} else {
				$('#supplier_name').val('');
				$('#supplier_id').val('');
				//$('#dr_account_id').val('');

			}
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
				$('#itmcst_'+curNum).val(data.pur_cost);
				getNetTotal();
			});
		}
		
	});
	
	
	/* $('#document_type').on('change', function(e){
		var dpt_id = ($('#department_id').length)?$('#department_id option:selected').val():'';
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
	}); */
	
	
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
	
	$(document).on('click', '#account_data .custRow', function(e) { 
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
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
		//$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'cracnt_'+num);
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
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		
		$('.tax-code').val(this.value);
		$( '.tax-code' ).each(function() {
			var res = this.id.split('_'); 
			var curNum = res[1]; //console.log('d'+curNum);
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
		/*if(code!='') {
		 $.get("{{ url('itemmaster/item_load/') }}/" + code, function(data) { //console.log(data);
			$("#itmdes_"+curNum).val(data.description);
			$('#itmid_'+curNum).val( data.id );
			$('#vat_'+curNum).val(data.vat)
			$('#vatdiv_'+curNum).val(data.vat+'%');
			//$('#itmcst_'+curNum).val(data.pur_cost);
			
			
			/* $('#itmunt_'+curNum).find('option').remove().end();
			$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",data.unit_id)
							.text(data.unit)); */
		//});
		//} */
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
	
	$(document).on('click', '#importFile', function(e) { 
		if($('#input-23').val()=='') {
			alert('Please choose import file!');
			return false;
		} else {
			$('#frmPurchaseInvoice').attr('action', "{{url('purchase_invoice/import')}}").submit();
		}
	});
	
	$(document).on('keyup', '.num :input[type="number"]', function(e) {
		var itQty = 0; var curNum = $(this).data('id');
		$('.loc-qty-'+curNum).each(function() { 
			itQty += parseFloat( (this.value=='')?0:this.value );
		});
		$('#itmqty_'+curNum).val(itQty);
		$('#iloc_'+curNum).val((itQty>0)?itQty:''); //NOV24
		var isPrice = getAutoPrice(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
		//NOV24
		$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
		@if($formdata['location_item']==1)
		$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'iloc[]');
		@endif
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
	//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('suppliers_do/checkvchrno/') }}", 
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
	
	
	//MAY25 BATCH SCRIPT...........
	var btno = 1;
    $(document).on('click', '.funAddBacthRow', function(e) {
        e.preventDefault();
        btno++;
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
       var batchurl = "{{ url('itemmaster/batch-view') }}";
       
       if(batch!='') {
    	   var vwUrl = batchurl+'?batch='+batch+'&mfg_date='+mfgdate+'&exp_date='+expdate+'&qty='+btqty+'&act=add&no='+n; 
    	   $('#batchData').load(vwUrl, function(result) {
    		  $('#myModal').modal({show:true}); 
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
    
    $(document).on('click', '.funRemove', function(e)  { 
      e.preventDefault();
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
	
	if(doc=='PO' && $("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	console.log(doc);
	if(doc=='PO')
		var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id+"/SDO";
	else if(doc=='MR')
		var pourl = "{{ url('purchase_order/mr_data/') }}/MRI";
	else if(doc=='SDO')
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/SDO";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
function getSupplierDo() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('suppliers_do/get_supplierdo/') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function loadFile1()
{
	if($('#input-23').val()=='') {
		alert('Please choose import file!');
		return false;
	} else {
		$('#frmPurchaseInvoice').attr('action', "{{url('purchase_invoice/import')}}").submit();
	}
}

</script>
@stop

										