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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/selectize/css/selectize.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/selectize/css/selectize.bootstrap3.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
        <!--end of page level css-->
		
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		  text-align:right;
		}
		
		.txtbox {
			text-align:right;
		}
		.txtboxbold {
			text-align:right;
			font-weight: bold;
		}
	</style>
	
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Item Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Item Master</a>
                </li>
                <li class="active">
                    Add New
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Item 
                            </h3>
                           
                        </div>
                        <div class="panel-body controls">
                            <form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem" enctype="multipart/form-data" action="{{ url('itemmaster/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="location_id" value="{{$location->id}}">
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Item Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itemcode" name="item_code" placeholder="Item Code">
                                    </div>
                                </div>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Item Decription</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="itmdescription" name="description" placeholder="Item Description">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Item Class</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="item_class" class="form-control select2" style="width:100%" name="item_class">
                                            <option value="">Select Class...</option>
											<option value="1">Stock</option>
											<option value="2">Service</option>
                                        </select>
                                    </div>
                                </div>
								
								<?php if($formdata['model_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Model No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="model_no" name="model_no" placeholder="Model No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="model_no" id="model_no">
								<?php } ?>
								
								<?php if($formdata['serial_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Serial No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Serial No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="serial_no" id="serial_no">
								<?php } ?>
								
								<?php if($formdata['machine_model']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Machine Model</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="machine_model" name="machine_model" placeholder="Machine Model">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="machine_model" id="machine_model">
								<?php } ?>
								
								<?php if($formdata['size']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Size</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="size" name="size" placeholder="Size">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="size" id="size">
								<?php } ?>
								
								<?php if($formdata['other_info']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Information</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="other_info" name="other_info"></textarea>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="other_info" id="other_info">
								<?php } ?>
								
								<hr/>
								<?php if($formdata['group']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Group Name</label>
                                    <div class="col-sm-10">
                                        <select id="group_id" class="form-control select2" style="width:100%" name="group_id">
                                            <option value="">Select Group...</option>
											@foreach ($groups as $group)
											<option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="group_id" id="group_id">
								<?php } ?>
								
								<?php if($formdata['subgroup']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sub Group</label>
                                    <div class="col-sm-10">
                                        <select id="subgroup_id" class="form-control select2" style="width:100%" name="subgroup_id">
                                            <option value="">Select Sub Group...</option>
											@foreach ($subgroups as $subgroup)
											<option value="{{ $subgroup['id'] }}">{{ $subgroup['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="subgroup_id" id="subgroup_id">
								<?php } ?>
								
								<?php if($formdata['category']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Category Name</label>
                                    <div class="col-sm-10">
                                        <select id="category_id" class="form-control select2" style="width:100%" name="category_id">
                                            <option value="">Select Category...</option>
											@foreach ($category as $cat)
											<option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="category_id" id="category_id">
								<?php } ?>
								
								<?php if($formdata['subcategory']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sub Category</label>
                                    <div class="col-sm-10">
                                        <select id="subcategory_id" class="form-control select2" style="width:100%" name="subcategory_id">
                                            <option value="">Select Sub Category...</option>
											@foreach ($subcategory as $subcat)
											<option value="{{ $subcat['id'] }}">{{ $subcat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="subcategory_id" id="subcategory_id">
								<?php } ?>
								
								<hr/>
								<?php if($formdata['simple_entry']==1) { ?>
								<table id="simple_user" class="table table-bordered table-striped m-t-12">
										<tbody>
										<tr>
											<td class="table_simple" style="width:12%">Unit</td>
											<td class="table_simple" style="width:10%">Open Qty.</td>
											<td class="table_simple" style="width:10%">Open Cost</td>
											<td class="table_simple" style="width:10%">VAT%</td>
											<td class="table_simple" style="width:1%"></td>
										</tr>
										<tr>
											<td>
												<select id="unit" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="unit[]" >
													<option value="">Base</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											
											<td>
												<input type="number" name="opn_quantity[]" id="opn_qty" step="any" class="form-control itemunit" >
											</td>
											
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost" class="form-control itemunit" >
											</td>
											
											<td>
												<input type="hidden" value="{{$vats[0]['percentage']}}" type="text" name="vat[]" id="vat_1" class="form-control itemunit" >
												<select id="selvat_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="selvat[]">
													
													@foreach ($vats as $vat)
													<option value="{{ $vat['percentage'] }}" default>{{ $vat['name'] }}</option>
													@endforeach
													<option value="">NULL</option>
												</select>
											</td>
											<td>
											<input type="hidden" step="any" type="text" name="packing[]" id="packing" class="form-control itemunit" >
												<input type="hidden" step="any" name="wsale_price[]" id="wsale_price" class="form-control itemunit" >
												<input type="hidden" step="any" name="sell_price[]" id="sell_price" class="form-control itemunit" >
												<input type="hidden" name="min_quantity[]" id="min_quantity" class="form-control itemunit" >
												<input type="number" name="reorder_level[]" id="reorder_level" class="form-control itemunit" >

											</td>
										</tr>
										</tbody>
										</table>
								
								<?php } else { ?>
									<table id="user" class="table table-bordered table-striped m-t-12">
										<tbody>
										<tr>
											<td class="table_simple" style="width:12%">Unit</td>
											<td class="table_simple">Item Packing</td>
											<td class="table_simple" style="width:10%">Open Qty.</td>
											<td class="table_simple" style="width:10%">Open Cost</td>
											<td class="table_simple" style="width:8%">S.Price(Rtl)</td>
											<td class="table_simple" style="width:8%">S.Price(WS)</td>
											<td class="table_simple" style="width:8%">Min.Qty.</td>
											<td class="table_simple" style="width:10%">Resvd. Qty.</td>
											<td class="table_simple" style="width:10%">VAT%</td>
										</tr>
										<tr>
											<td>
												<select id="unit_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="unit[]" >
													<option value="">Base</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>
												<input type="text" name="packing[]" id="packing_1" class="form-control" readonly>
											</td>
											<td>
												<input type="number" name="opn_quantity[]" id="opn_qty_1" step="any" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_1" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_1" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_1" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_1" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_1" class="form-control itemunit" >
											</td>
											<td>
												<input type="hidden" step="any" type="text" name="vat[]" id="vat_1" class="form-control itemunit" >
												<select id="selvat_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="selvat[]">
													
													@foreach ($vats as $vat)
													<option value="{{ $vat['percentage'] }}" default>{{ $vat['name'] }}</option>
													@endforeach
													<option value="">NULL</option>
												</select>
											</td>
										</tr>
										<?php if($formdata['p1']==1) { ?>
										<tr>
											<td>
												<select id="unit_2" class="form-control select2 itemunit" style="width:100%" name="unit[]">
													<option value="">P1</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>
												<div><span id="title_1" style="float:left;"></span><input type="text" name="packing[]" id="packing_22" style="width:40%;float:left;" class="form-control itemunit"><span id="title_12" style="padding-top:10px;"></span></div>
											</td>
											<td>
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_2" class="form-control itemunit" >
											</td>
											<td>
												<input type="hidden" step="any" type="text" name="vat[]" id="vat_2" class="form-control itemunit" >
												<input type="hidden" step="any" type="text" name="selvat[]" class="form-control itemunit" >
											</td>
										</tr>
										<?php } else { ?>
												<input type="hidden" name="unit[]"/>
												<input type="hidden" name="packing[]"/>
												<input type="hidden" name="opn_quantity[]"/>
												<input type="hidden" name="opn_cost[]"/>
												<input type="hidden" name="sell_price[]"/>
												<input type="hidden" name="wsale_price[]"/>
												<input type="hidden" name="min_quantity[]"/>
												<input type="hidden" name="reorder_level[]"/>
												<input type="hidden" name="vat[]"/>
												<input type="hidden" name="selvat[]"/>
										<?php } ?>
										
										<?php if($formdata['p2']==1) { ?>
										<tr>
											<td>
												<select id="unit_3" class="form-control select2 itemunit" style="width:100%" name="unit[]">
													<option value="">P2</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>
												<div><span id="title_2" style="float:left;"></span><input type="text" name="packing[]" id="packing_23" style="width:40%;float:left;" class="form-control itemunit"><span id="title_13" style="padding-top:10px;"></span></div>
											</td>
											<td>
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_3" class="form-control itemunit" >
											</td>
											<td>
												<input type="hidden" step="any" type="text" name="vat[]" id="vat_3" class="form-control itemunit" >
												<input type="hidden" step="any" type="text" name="selvat[]" class="form-control itemunit" >
											</td>
										</tr>
										<?php } else { ?>
											
												<input type="hidden" name="unit[]"/>
												<input type="hidden" name="packing[]"/>
												<input type="hidden" name="opn_quantity[]"/>
												<input type="hidden" name="opn_cost[]"/>
												<input type="hidden" name="sell_price[]"/>
												<input type="hidden" name="wsale_price[]"/>
												<input type="hidden" name="min_quantity[]"/>
												<input type="hidden" name="reorder_level[]"/>
												<input type="hidden" name="vat[]"/>
												<input type="hidden" name="selvat[]"/>
											
										<?php } ?>
										</tbody>
									</table>
									<?php } ?>
								
								<hr/>
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Profit%</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="profit_per" name="profit_per" placeholder="Profit%">
                                    </div>
                                </div>-->
								<input type="hidden" id="profit_per" name="profit_per">
								
								<?php if($formdata['location']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                    <button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Stock in Location</button>
                                    </div>
                                </div>
								<?php } ?>
								
								<div class="form-group locPrntItm">
									<label for="input-text" class="col-sm-2 control-label"></label>
									<div class="col-sm-10 locChldItm">							
										<div class="table-responsive loc-data" id="locData_1"></div>
									</div>
								</div>
								
								<?php if($formdata['surface_cost']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Surface Cost</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="surface_cost" name="surface_cost" placeholder="Surface Cost">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="surface_cost">
								<?php } ?>
								
								<?php if($formdata['other_cost']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Cost</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="other_cost" name="other_cost" placeholder="Other Cost">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="other_cost">
								<?php } ?>
								
								<?php if($formdata['assembly']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Assembly/Manufacture Item</label>
									<div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" class="mfgItm" name="assembly" value="0" checked>
                                            None
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" class="mfgItm" name="assembly" value="1">
                                            Mfg. Item
                                        </label>
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="assembly" id="assembly">
								<?php } ?>
								
								<?php if($formdata['image']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Item Image</label>
                                    <div class="col-sm-10">
                                    <input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="image" id="image">
								<?php } ?>
								
								<?php if($formdata['supersede']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supersede Items</label>
                                    <div class="col-sm-10">
										<select id="select22" class="form-control select2" name="supersede[]" multiple style="width:100%">
											@foreach($items as $item)
											<option value="{{$item->id}}">{{$item->description}}</option>
											@endforeach
										</select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="supersede[]" id="supersede">
								<?php } ?>
								
								<?php if($formdata['rowmaterial']==1) { ?>
								<div id="rMat">
									<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Raw Materials</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
										<thead>
										<tr>
											<th width="14%" class="itmHd">
												<span class="small">Item Code</span>
											</th>
											<th width="25%" class="itmHd">
												<span class="small">Item Description</span>
											</th>
											<th width="8%" class="itmHd">
												<span class="small">Quantity</span>
											</th>
											<th width="5%" class="itmHd">
												<span class="small">Cost/Unit</span>
											</th>
											<th width="13%" class="itmHd">
												<span class="small">Total</span> 
											</th>
										</tr>
										</thead>
									</table>
									
									<div class="itemdivPrnt">
										<div class="itemdivChld">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%">
														<input type="hidden" name="item_id[]" id="itmid_1">
														<input type="text" id="itmcod_1" name="itemcode[]" class="form-control" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="8%">
														<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_1" name="item_total[]">
													</td>
													<td width="1%">
														
															 <button type="button" class="btn-success btn-add-item">
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
															<button type="button" class="btn-danger btn-remove-item" data-id="rem_1">
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
															
													</td>
												</tr>
											</table>
										</div>
									</div>
									</fieldset>
								</div>
								<?php } ?>
								
								@if($formdata['bin_loc']==1)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Bin Location</label>
                                    <div class="col-sm-10">
										<input type="text" id="selectize-tags2" class="demo-default" value="" name="bin_location">
                                    </div>
                                </div>
								@endif
								
								<hr/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('itemmaster') }}" class="btn btn-danger">Cancel</a>
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

<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/selectize/js/standalone/selectize.min.js')}}" type="text/javascript"></script>

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
	
	$('#selectize-tags2').selectize({
        plugins: ['remove_button'],
        delimiter: ',',
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input
            }
        }
    });
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Multiple Technicians"
    });
	
	//$('.locPrntItm').toggle();
	if( $('.locPrntItm').is(":visible") ) 
		$('.locPrntItm').toggle();
	
	$('#rMat').hide();
	
	$('#inlineradio1').change(function(){
		alert('Radio Box has been changed!');
	});
	$('#inlineradio2').change(function(){
		alert('Radio Box has been changed!');
	});
	
	var urlcode = "{{ url('itemmaster/checkcode/') }}";
	var urldesc = "{{ url('itemmaster/checkdesc/') }}";
    $('#frmItem').bootstrapValidator({
        fields: {
            item_code: { validators: { 
					notEmpty: { message: 'The item code is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { item_code: validator.getFieldElements('item_code').val() };
							  },
							  message: 'The item code is not available'
                    }
                }
            },
			description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' },
										/*  remote: { url: urldesc,
												  data: function(validator) {
													return { description: validator.getFieldElements('description').val() };
												  },
												  message: 'The item description is not available'
										} */
							} 
			},
			item_class: { validators: { notEmpty: { message: 'The item class is required and cannot be empty!' } } },
			//group_id: { validators: { notEmpty: { message: 'The group name is required and cannot be empty!' } } },
			//category_id: { validators: { notEmpty: { message: 'The category name is required and cannot be empty!' } } },
			unit_1: { validators: { notEmpty: { message: 'The unit is required and cannot be empty!' } } },
			image: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,gif',
							  type: 'image/jpg,image/jpeg,image/png,image/gif',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,gif) and 5 MB at maximum.'
						}
					}
			}
          
        }
        
    }).on('reset', function (event) {
        $('#frmItem').data('bootstrapValidator').resetForm();
    });
});

$(function() {	
	
	$('.mfgItm').on('ifChecked', function(event){ 
		 if(this.value==1)
			 $('#rMat').show();
		 else
			 $('#rMat').hide();
     });
	
	$(document).on('change', '#unit_1', function(e) { 
		 $('#packing_1').val($("#unit_1 option:selected").text());      
     });
	
	 $(document).on('change', '#selvat_1', function(e) { 
		  $('#vat_1').val($("#selvat_1 option:selected").val()); 
		  $('#vat_2').val($("#selvat_1 option:selected").val());      		  
     });
	 $(document).on('change', '#selvat_2', function(e) { 
		  $('#vat_2').val($("#selvat_2 option:selected").val());      
     });
	 
	 $(document).on('change', '#unit_2', function(e) { //console.log( $(this).find("option:selected").text() );
		var title = $(this).find("option:selected").text();
		   $('#title_1').html('1 '+title+' ='); //$('#packing_2').val('1 '+$("#unit_2 option:selected").text()+' =');
		   $('#packing_22').val('1');
		   $('#title_12').html($("#unit_1 option:selected").text());
		    var qty = parseInt( $('#opn_qty_1').val() );
		   var ans = qty / 1;
		   $('#opn_qty_2').val(ans.toFixed(2));
		   var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		  $('#opn_cost_2').val( ocost * 1 );
		   
			//var qty = parseInt( $('#opn_qty_1').val() );
			//var qty2 = parseInt( (qty=='')?'':$('#opn_qty_2').val()); 
     });
	 
	 $(document).on('change', '#unit_3', function(e) { 
		var title = $(this).find("option:selected").text();
		   $('#title_2').html('1 '+title+' ='); 
		   $('#packing_23').val('1');
		   $('#title_13').html($("#unit_1 option:selected").text());
		    var qty = parseInt( $('#opn_qty_1').val() );
		   var ans = qty / 1;
		   $('#opn_qty_3').val(ans.toFixed(2));
		   var ocost = parseInt( ($('#opn_cost_3').val()=='')?0:$('#opn_cost_3').val() );
		  $('#opn_cost_3').val( ocost * 1 );
		   
			
     });
	 
	 $(document).on('blur', '#packing_22', function(e) { 
		 var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		 var ans = qty / this.value; 
		 $('#opn_qty_2').val(ans.toFixed(2));
		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 $('#opn_cost_2').val( ocost * this.value );
			
     });
	 
	 $(document).on('blur', '#packing_23', function(e) { 
		 var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		 var ans = qty / this.value; 
		 $('#opn_qty_3').val(ans.toFixed(2));
		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 $('#opn_cost_3').val( ocost * this.value );
			
     });
	 
	 $(document).on('blur', '#opn_qty_1', function(e) {
		 var qty = parseInt( $('#opn_qty_1').val() );
		 var pkg = parseInt( ($('#packing_22').val()=='')? 0 : $('#packing_22').val() ); //console.log(pkg);
		 
		   if(pkg!='') {
			   var ans = qty / pkg;
			   $('#opn_qty_2').val(ans);//.toFixed(2)
			   var ocost = parseInt( $('#opn_cost_1').val() );
			   $('#opn_cost_2').val( ocost * pkg );
		   }
	 });
	 
	 $(document).on('blur', '#opn_cost_1', function(e) {
		 var ocost = parseInt( $('#opn_cost_1').val() );
		 var pkg = parseInt( $('#packing_22').val() );
		   $('#opn_cost_2').val( ocost * pkg );
	 });
	  /* $(document).on('blur', '.itemunit', function(e) { 
		console.log('hi '+this.value);
	  }); */
	  
	  $(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   
		   var locUrl = "{{ url('location/get_loc') }}";
		   $('#locData_1').load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('.locPrntItm').toggle();
    });
	 
	 $('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	var rowNum = 1;
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="itemcode[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			$('#locData_'+rowNum).html('');
			newEntry.find('input').val(''); 
			
			$('#packing_'+rowNum).val(1);
			
			//ROWCHNG
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('#itmcod_'+rowNum).focus();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		
		//ROWCHNG
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="itemcode[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
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
	
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
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
		
		getAutoPrice(num);
	});
	
	
});
	  
function getAutoPrice(curNum) {
		
	var item_id = $('#itmid_'+curNum).val();
	var unit_id = '';//$('#itmunt_'+curNum+' option:selected').val();
	
	$.ajax({
		url: "{{ url('itemmaster/get_cost_avg/') }}",
		type: 'get',
		data: 'item_id='+item_id+'&unit_id='+unit_id,
		success: function(data) { console.log(data);
			$('#itmcst_'+curNum).val((data==0)?'':data);
			$('#itmcst_'+curNum).focus();
			return true;
		}
	}); 
}

$(document).on('keyup blur', '.line-quantity', function(e) {
		
	var res = this.id.split('_');
	var curNum = res[1];
	var amt = parseFloat( ($('#itmcst_'+curNum).val()=='') ? 0 : $('#itmcst_'+curNum).val() );
	$('#itmttl_'+curNum).val((amt * this.value));
	
});

$(document).on('keyup blur', '.line-cost', function(e) {
		
	var res = this.id.split('_');
	var curNum = res[1];
	var qty = parseFloat( ($('#itmqty_'+curNum).val()=='') ? 0 : $('#itmqty_'+curNum).val() );
	$('#itmttl_'+curNum).val((qty * this.value));
	
});

$(document).on('keyup', '.locqty', function(e) {
	var itQty = 0;
	$('.locqty').each(function() { 
		itQty += parseFloat( (this.value=='')?0:this.value );
	});
	$('#opn_qty_1').val(itQty);
});

$(document).on('keyup', '#opn_qty_1', function(e) {
	$('.locqty').val('');
});

</script>
@stop
