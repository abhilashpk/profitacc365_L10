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
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/selectize/css/selectize.bootstrap3.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	
	<style>
	    	#batch_modal { z-index:0; }
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
                    Edit
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Item 
                            </h3>
                           
                        </div>
                        <div class="panel-body controls">
                            <form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem" enctype="multipart/form-data" action="{{ url('itemmaster/update/'.$itemrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="item_id" value="{{ $itemrow->id }}">
								<input type="hidden" name="fromurl" value="{{ $fromurl }}">
								<input type="hidden" name="formd" id="formd" value="{{$formdata['simple_entry']}}">

								<input type="hidden" name="itmHt" id="itmHt" value="{{ $itemrow->itmHt }}">
								<input type="hidden" name="itmWd" id="itmWd" value="{{ $itemrow->itmWd }}">
								<input type="hidden" name="itmLt" id="itmLt" value="{{ $itemrow->itmLt }}">

                                <input type="hidden" name="mpqty" id="mpqty" value="{{ $itemrow->mpqty }}">
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Item Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="item_code" name="item_code" value="{{ $itemrow->item_code }}">
                                    </div>
                                </div>
								<?php if($formdata['description_arabic']==1) { ?>
								
								<div class="form-group">
                                  <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>Item Decription</b></label></font>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $itemrow->description }}">
                                    </div>
                                    <div class="col-sm-2">
                                    <button type="button" id="langtrans" class="btn btn-primary btn-xs lang-trans">Translate</button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                  <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>وصف السلعة</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="descriptionar" name="descriptionar" value="{{ $itemrow->description_ar }}">
                                    </div>
                                </div>
								<?php } else { ?>
								<div class="form-group">
                                  <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>Item Decription</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $itemrow->description }}">
                                    </div>
                                </div>
								<?php } ?>
                                <div class="form-group">
                                  <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>Item Class</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="item_class" class="form-control select2" style="width:100%" name="item_class">
											@if($itemrow->class_id==1)
											@php $sel1 = "selected";
													$sel2="";
											@endphp
											@else
											@php $sel2 = "selected";
												$sel1="";
											@endphp	
											@endif
											<option value="1" {{ $sel1 }}>Stock</option>
											<option value="2" {{ $sel2 }}>Service</option>
                                        </select>
                                    </div>
                                </div>
								
								<?php if($formdata['model_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Model No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="model_no" name="model_no" placeholder="Model No." value="{{ $itemrow->model_no }}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="model_no" id="model_no">
								<?php } ?>
								
								<?php if($formdata['serial_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Serial No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Serial No." value="{{ $itemrow->serial_no }}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="serial_no" id="serial_no">
								<?php } ?>
								
								<?php if($formdata['machine_model']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Machine Model</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="machine_model" name="machine_model" value="{{ $itemrow->bin }}" placeholder="Machine Model" >
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="machine_model" id="machine_model">
								<?php } ?>
								
								<?php if($formdata['size']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Size</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="size" name="size" value="{{ $itemrow->weight }}" placeholder="Size">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="size" id="size">
								<?php } ?>
								
								<?php if($formdata['other_info']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Information</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="other_info" name="other_info">{{ $itemrow->other_info }}</textarea>
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
											@if($itemrow->group_id==$group['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $group['id'] }}" {{ $sel }}>{{ $group['name'] }}</option>
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
											@if($itemrow->subgroup_id==$subgroup['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $subgroup['id'] }}" {{ $sel }}>{{ $subgroup['name'] }}</option>
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
											@if($itemrow->category_id==$cat['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $cat['id'] }}" {{ $sel }}>{{ $cat['name'] }}</option>
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
											@if($itemrow->subcategory_id==$subcat['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp	
											@endif
											<option value="{{ $subcat['id'] }}" {{ $sel }}>{{ $subcat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="subcategory_id" id="subcategory_id">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Dimension Required</label>
                                    <div class="col-sm-10">
                                        <select id="dimension" class="form-control select2" style="width:100%" name="dimension">
											<option value="0" {{($itemrow->dimension==0)?'selected':''}}>No</option>
											<option value="1" {{($itemrow->dimension==1)?'selected':''}}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Batch Required</label>
                                    <div class="col-sm-10">
                                        <select id="batch_req" class="form-control select2" style="width:100%" name="batch_req">
											<option value="0" {{($itemrow->batch_req==0)?'selected':''}}>No</option>
											<option value="1" {{($itemrow->batch_req==1)?'selected':''}}>Yes</option>
                                        </select>
                                    </div>
                                </div>

								<hr/>
								<?php if($formdata['simple_entry']==1) { ?>
								<div id="sEntry">
								<table id="user" class="table table-bordered table-striped m-t-10">
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
												<select id="unit" class="form-control select2" style="width:100%;background:#87CEFA;{{($readonly)?'pointer-events: none;':''}}" name="unit[]">
													<option value="">Base</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if($itemunits[0]->unit_id==$unit['id']) echo 'selected';?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="item_unit_id[]" id="item_unit_id" class="form-control" value="{{ $itemunits[0]->id }}">
											</td>
											
											<td>
												<input type="hidden" name="opn_quantity_cur[]" id="opn_qty_cur_1" value="{{ $itemunits[0]->opn_quantity }}">
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_1" class="form-control" value="{{ $itemunits[0]->opn_quantity }}" placeholder="Open Qty.">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_1" class="form-control" value="{{ $itemunits[0]->opn_cost }}" placeholder="Open Cost">
											</td>
											
											<td>
												<input type="hidden" name="vat[]" id="vat_1" class="form-control" value="{{ $itemunits[0]->vat }}">
												<select id="selvat_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="selvat[]">
													<option value="">VAT%</option>
													@foreach ($vats as $vat)
													<option value="{{ $vat['percentage'] }}" <?php if($vat['percentage']==$itemunits[0]->vat) echo 'selected';?>>{{ $vat['name'] }}</option>
													@endforeach
												</select>
											</td>
										<td><input type="hidden" name="pkno[]" id="pkno_1" value="1">
											<input type="hidden" step="any" type="text" name="packing[]" id="packing" class="form-control itemunit" value="{{ $itemunits[0]->packing }}" >
												<input type="hidden" step="any" name="wsale_price[]" id="wsale_price" class="form-control itemunit" >
												<input type="hidden" step="any" name="sell_price[]" id="sell_price" class="form-control itemunit" >
												<input type="hidden" name="min_quantity[]" id="min_quantity" class="form-control itemunit" >
												<input type="number" name="reorder_level[]" id="reorder_level" class="form-control itemunit" >

											</td>	
										</tr>
										</tbody>
										</table>
										</div>
								<?php } else { ?>
								<div id="norEntry">
								<table id="user" class="table table-bordered table-striped m-t-10">
										<tbody>
										<tr>
											<td class="table_simple" style="width:12%">Unit</td>
											<td class="table_simple">Item Packing</td>
											<td class="table_simple" style="width:10%">Open Qty.</td>
											<td class="table_simple" style="width:10%">Open Cost</td>
											<td class="table_simple" style="width:8%">S.Price(Retail)</td>
											<td class="table_simple" style="width:8%">S.Price(WS)</td>
											<td class="table_simple" style="width:8%">Min.Qty.</td>
											<td class="table_simple" style="width:10%">Resvd. Qty.</td>
											<td class="table_simple" style="width:10%">VAT%</td>
										</tr>
										<tr>
											<td>
												<select id="unit_1" class="form-control select2" style="width:100%;background:#87CEFA;{{($readonly)?'pointer-events: none;':''}}" name="unit[]"> 
													<option value="">Base</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if($itemunits[0]->unit_id==$unit['id']) echo 'selected';?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="item_unit_id[]" id="item_unit_id_1" class="form-control" value="{{ $itemunits[0]->id }}">
											</td>
											<td><input type="hidden" name="pkno[]" id="pkno_1" value="1">
												<input type="text" name="packing[]" id="packing_1" class="form-control" value="{{ $itemunits[0]->packing }}" readonly placeholder="Packing">
											</td>
											<td>
												<input type="hidden" name="opn_quantity_cur[]" id="opn_qty_cur_1" value="{{ $itemunits[0]->opn_quantity }}">
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_1" class="form-control" value="{{ $itemunits[0]->opn_quantity }}" placeholder="Open Qty.">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_1" class="form-control" value="{{ $itemunits[0]->opn_cost }}" placeholder="Open Cost">
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_1" class="form-control" value="{{ $itemunits[0]->sell_price }}" placeholder="S.Price(Retail)">
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_1" class="form-control" value="{{ $itemunits[0]->wsale_price }}" placeholder="S.Price(WS)">
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_1" class="form-control" value="{{ $itemunits[0]->min_quantity }}" placeholder="Min.Qty.">
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_1" class="form-control" value="{{ $itemunits[0]->reorder_level }}" placeholder="Reord. Level">
											</td>
											<td>
												<input type="hidden" name="vat[]" id="vat_1" class="form-control" value="{{ $itemunits[0]->vat }}">
												<select id="selvat_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="selvat[]">
													<option value="">VAT%</option>
													@foreach ($vats as $vat)
													<option value="{{ $vat['percentage'] }}" <?php if($vat['percentage']==$itemunits[0]->vat) echo 'selected';?>>{{ $vat['name'] }}</option>
													@endforeach
												</select>
											</td>
											
										</tr>
										
										<?php if($formdata['p1']==1) { ?>
										<tr>
											<td>
												<select id="unit_2" class="form-control select2" style="width:100%;{{($readonly)?'pointer-events: none;':''}}"  name="unit[]">
													<option value="">P1</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if( (sizeof($itemunits) > 1) && isset($itemunits[1]) && $itemunits[1]->unit_id==$unit['id']) { echo 'selected'; $sub = $unit['unit_name']; }?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
												
											</td>
											<td>@if(sizeof($itemunits) > 1 && isset($itemunits[1]))
													@php $packing = $itemunits[1]->packing;
														   $opn_qty = $itemunits[1]->opn_quantity;
														   $opn_qty_cur = $itemunits[1]->opn_quantity;
														   $opn_cost = $itemunits[1]->opn_cost;
														   $sell_price = $itemunits[1]->sell_price;
														   $wsale_price = $itemunits[1]->wsale_price;
														   $min_quantity = $itemunits[1]->min_quantity;
														   $reorder_level = $itemunits[1]->reorder_level;
														   $vat = $itemunits[1]->vat;
														   $item_unit_id = $itemunits[1]->id;
														   $base = $itemunits[0]->packing;
														   $sub = ' '.$sub.' =';
														   $pkno = $itemunits[1]->pkno;
													@endphp
												@else
													@php $pkno = $packing = $opn_cost = $opn_qty_cur = $opn_qty = $sell_price = $wsale_price = $min_quantity = $reorder_level = $vat = $item_unit_id = $base = $sub = ''; @endphp
												@endif
												<input type="hidden" name="item_unit_id[]" id="item_unit_id_2" class="form-control" value="{{ $item_unit_id }}">
												<div><input type="text" name="pkno[]" id="pkno_22" {{($readonly)?'readonly':''}} value="{{$pkno}}" style="width:15%;float:left;" class="form-control"> <span id="title_1" style="float:left;">{{$sub}}</span><input type="text" name="packing[]" id="packing_22" {{($readonly)?'readonly':''}} value="{{ $packing }}" style="width:40%;float:left;" class="form-control" > <span id="title_12">{{$base}}</span>
												<!--<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#editqty_modal">Edit</button></div>-->
											</td>
											<td>
												<input type="hidden" name="opn_quantity_cur[]" id="opn_qty_cur_2" value="{{ $opn_qty_cur }}">
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_2" readonly class="form-control" value="{{$opn_qty}}" placeholder="Open Qty.">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_2" readonly class="form-control" value="{{ $opn_cost }}" placeholder="Open Cost">
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_2" readonly class="form-control" value="{{$sell_price}}" placeholder="S.Price(Retail)">
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_2" readonly class="form-control" placeholder="S.Price(WS)">
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_2" class="form-control" placeholder="Min.Qty.">
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_2" class="form-control" placeholder="Reord. Level">
											</td>
											<td>
												<input type="hidden" value="{{$vat}}" name="vat[]" id="vat_2">
												<!--<input type="hidden" step="any" type="text" name="vat[]" id="vat_2" class="form-control itemunit" >
												<input type="hidden" step="any" type="text" name="selvat[]" class="form-control itemunit" >-->
											</td>
										</tr>
										
										<?php } ?>
										
										<?php if($formdata['p2']==1) { ?>
										<tr>
											<td>
												<select id="unit_3" class="form-control select2" style="width:100%;{{($readonly)?'pointer-events: none;':''}}" name="unit[]">
													<option value="">P2</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if( (sizeof($itemunits) > 2) && $itemunits[2]->unit_id==$unit['id']) { echo 'selected'; $sub = $unit['unit_name']; }?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>@if(sizeof($itemunits) > 2)
													@php $packing = $itemunits[2]->packing;
														   $opn_qty = $itemunits[2]->opn_quantity;
														   $opn_qty_cur = $itemunits[2]->opn_quantity;
														   $opn_cost = $itemunits[2]->opn_cost;
														   $sell_price = $itemunits[2]->sell_price;
														   $wsale_price = $itemunits[2]->wsale_price;
														   $min_quantity = $itemunits[2]->min_quantity;
														   $reorder_level = $itemunits[2]->reorder_level;
														   $vat = $itemunits[2]->vat;
														   $item_unit_id = $itemunits[2]->id;
														   $base = $itemunits[0]->packing;
														   $pkno = $itemunits[2]->pkno;
														   //$sub = '1 '.$sub.' =';
													@endphp
												@else
													@php $packing = $opn_cost = $opn_qty_cur = $opn_qty = $sell_price = $wsale_price = $min_quantity = $reorder_level = $vat = $item_unit_id = $base = $sub = ''; @endphp
												@endif
												<input type="hidden" name="item_unit_id[]" id="item_unit_id_3" class="form-control" value="{{ $item_unit_id }}"> 
												<div><input type="text" name="pkno[]" id="pkno_23" {{($readonly)?'readonly':''}} value="{{$pkno}}" style="width:15%;float:left;" class="form-control"> <span id="title_2" style="float:left;"> {{$sub}}=</span><input type="text" name="packing[]" id="packing_23" {{($readonly)?'readonly':''}} value="{{ $packing }}" style="width:40%;float:left;" class="form-control" > <span id="title_13">{{$base}}</span></div>
											</td>
											<td>
												<input type="hidden" name="opn_quantity_cur[]" id="opn_qty_cur_3" value="{{ $opn_qty_cur }}">
												<input type="number" name="opn_quantity[]" step="any" id="opn_qty_3" readonly class="form-control" value="{{$opn_qty}}" placeholder="Open Qty.">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_3" readonly class="form-control" value="{{ $opn_cost }}" placeholder="Open Cost">
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_3" readonly class="form-control" value="{{$sell_price}}" placeholder="S.Price(Retail)">
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_3" readonly class="form-control" placeholder="S.Price(WS)">
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_3" class="form-control" placeholder="Min.Qty.">
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_3" class="form-control" placeholder="Reord. Level">
											</td>
											<td>
												<input type="hidden" value="{{$vat}}" name="vat[]" id="vat_3">
											</td>
										</tr>
										
										<?php } ?>
										
										</tbody>
									</table>
									</div>
									
									
									<div id="dimEntry">
									<table id="user" class="table table-bordered table-striped m-t-12">
										<tbody>
										<tr>
											<td class="table_simple" style="width:12%">Unit</td>
											<td class="table_simple" style="width:12%">P1</td>
											<td class="table_simple" style="width:10%">Open Qty. PCs</td>
											<td class="table_simple" style="width:12%">Dimension</td>
											<td class="table_simple" style="width:10%">Qty. Kg</td>
											<td class="table_simple" style="width:10%">Open Cost</td>
											<td class="table_simple" style="width:10%">VAT%</td>
										</tr>
										<tr>
											<td>
												<select id="unitD_1" class="form-control select2 itemunit" style="pointer-events: none;" style="width:100%;background:#87CEFA" name="unit_d[]" >
													<option value="">Unit</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if($itemunits[0]->unit_id==$unit['id']) echo 'selected';?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="packing_d[]" id="packingD_1" value="{{ $itemunits[0]->packing }}" class="form-control" readonly>
											</td>

											<td>
											<select id="unitD_2" class="form-control select2 itemunitD" style="pointer-events: none;" style="width:100%" name="unit_d[]">
													<option value="">P1</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}" <?php if( (sizeof($itemunits) > 1) && $itemunits[1]->unit_id==$unit['id']) { echo 'selected'; $sub = $unit['unit_name']; }?>>{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
												<input type="hidden" name="packing_d[]" id="packingD_22" style="width:40%;float:left;" class="form-control itemunit">
											</td>

											<td>
												<input type="hidden" name="opn_quantity_d[]" id="opn_qtyD_1" >
												<input type="number" name="opn_quantity_d[]" step="any" id="opn_qtyD_2" value="{{isset($itemunits[1])?$itemunits[1]->opn_quantity:''}}" class="form-control itemunit" >
											</td>

											<td>
												<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#editqty_modal">Dimension</button>
											</td>

											<td>
												<input type="number" id="opn_qtyD" step="any" class="form-control" readonly value="{{ $itemunits[0]->opn_quantity }}">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost_d[]" id="opn_costD_1" class="form-control" value="{{ $itemunits[0]->opn_cost }}">
												<input type="hidden" name="opn_cost_d[]" id="opn_costD_2" class="form-control" >
											</td>
											
											<td>
												<input type="hidden" step="any" type="text" name="vat_d[]" id="vat_1" class="form-control itemunit" >
												<select id="selvat_1" class="form-control select2 itemunit" style="width:100%;background:#87CEFA" name="selvat_d[]">
													@foreach ($vats as $vat)
													<option value="{{ $vat['percentage'] }}" default>{{ $vat['name'] }}</option>
													@endforeach
													<option value="">NULL</option>
												</select>
												<input type="hidden" step="any" type="text" name="vat_d[]" id="vat_2" class="form-control itemunit" >
												<input type="hidden" step="any" type="text" name="selvat_d[]" class="form-control itemunit" >
											</td>
										</tr>
										</tbody>
									</table>
									</div>


                                   	<?php } ?>
									
							
								
								<hr/>
								
								<input type="hidden" id="profit_per" name="profit_per">
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Profit%</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="profit_per" name="profit_per" value="{{$itemrow->profit_per}}" placeholder="Profit%">
                                    </div>
                                </div>-->
								
								<?php if($formdata['location']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                    <button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Stock in Location</button>
                                    </div>
									
                                </div>
								
								
								<div class="form-group locPrntItm">
									<label for="input-text" class="col-sm-2 control-label"></label>
									<div class="col-sm-10 locChldItm">							
										<div class="table-responsive loc-data" id="locData_1">
										<?php if($stockloc) { $i=0; ?>
											<div class="col-xs-4">
												<table class="table table-bordered table-hover">
													<thead>
													<tr>
														<th>Location</th>
														<th>Opn.Quantity</th>
														<th>Quantity</th>
														<th>Bin</th>
													</tr>
													</thead>
													<tbody>
													@foreach($locations as $key => $row)
													<?php $qty = $id = $opnqty = ''; $i++;
														if(array_key_exists($row->id, $stockloc)) {
															$qty = isset($stockloc[$row->id])?$stockloc[$row->id][0]->quantity:'';
															$id = isset($stockloc[$row->id])?$stockloc[$row->id][0]->id:'';
															$opnqty = isset($stockloc[$row->id])?$stockloc[$row->id][0]->opn_qty:'';
														}
													?>
													<tr>
														<td>{{ $row->name }}<input type="hidden" name="locid[]" value="{{$row->id}}"></td>
														<td><input type="number" value="{{$opnqty}}" name="locqty[]" class="locqty" @if(auth()->user()->can('item-qty-cost-edit')) console.log('') @else readonly @endif>
															<input type="hidden" name="itlocid[]" value="{{$id}}">
														</td>
														<td>{{ $qty }}</td>
														<td><input type="text" name="bin[]" class="bin" id="bin_{{$i}}" value="{{isset($stockloc[$row->id])?$stockloc[$row->id][0]->code:''}}" autocomplete="off" data-toggle="modal" data-target="#bin_modal">
															<input type="hidden" name="binid[]" id="binid_{{$i}}" value="{{isset($stockloc[$row->id])?$stockloc[$row->id][0]->bin_id:''}}">
														</td>
													</tr>
													@endforeach
													</tbody>
												</table>
											</div>
										<?php } ?>
										</div>
									</div>
								</div>
								<?php } ?>
								
								
								<div class="form-group batch-entry">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <input type="hidden" id="batchIds" name="batchIds" value="{{ $batchitems['ids'] ?? '' }}">
                                        <input type="hidden" id="batchNos" name="batchNos" value="{{$batchitems['batches'] ?? ''}}">
                                        <input type="hidden" id="mfgDates" name="mfgDates" value="{{$batchitems['mfgs'] ?? ''}}">
                                        <input type="hidden" id="expDates" name="expDates" value="{{$batchitems['exps'] ?? ''}}">
                                        <input type="hidden" id="qtyBatchs" name="qtyBatchs" value="{{$batchitems['qtys'] ?? ''}}">
                                        <input type="hidden" id="batchRem" name="batchRem">
                                    <button type="button" id="bth_add" class="btn btn-primary btn-xs batch-add"  data-toggle="modal" data-target="#batch_modal">Add Batch</button>
                                    <input type="text" id="batches" name="batches" style="border:none;color:#FFF;">
                                    </div>
                                </div>

								<?php if($formdata['surface_cost']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Surface Cost</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="surface_cost" name="surface_cost" value="{{$itemrow->surface_cost}}" placeholder="Surface Cost">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="surface_cost">
								<?php } ?>
								
								<?php if($formdata['other_cost']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Cost</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="other_cost" name="other_cost" value="{{$itemrow->other_cost}}" placeholder="Other Cost">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="other_cost">
								<?php } ?>
								
								<?php if($formdata['assembly']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Assembly</label>
											@if($itemrow->assembly==0)
											@php $chk1 = "checked";
													$chk2 = "";
											@endphp
											@else
											@php $chk2 = "checked";
													$chk1 = "";
											@endphp	
											@endif
									<div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" class="mfgItm" name="assembly" value="0" {{ $chk1 }}>
                                            None
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" class="mfgItm" name="assembly" value="1" {{ $chk2 }}>
                                            Mfg. Item
                                        </label>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="assembly" id="assembly">
								<?php } ?>
								
								<?php if($formdata['image']==1) { ?>
                                @if($itemrow->image != "")
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Current Image</label>
                                    <div class="col-sm-10">
										<img src="{{ url('uploads/item/thumb_'.$itemrow->image) }}">
                                    </div>
                                </div>
								@endif
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Change Image</label>
                                    <div class="col-sm-10">
                                    <input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true">
									 <input name="current_image" type="hidden" value="{{ $itemrow->image }}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="current_image" id="current_image">
								<?php } ?>
								
								<?php if($formdata['supersede']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supersede Items</label>
                                    <div class="col-sm-10">
                                        <select id="select22" name="supersede[]" class="form-control select2" multiple style="width:100%">
											@foreach($items as $item)
											<?php 
											$values = explode(',', $itemrow->supersede_items);
											$sel = (in_array($item->id, $values))?'selected':'';?>
											<option value="{{$item->id}}" {{$sel}}>{{$item->description}}</option>
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
									<input type="hidden" id="rowNum" value="{{(count($rowmaterials)==0)?1:count($rowmaterials)}}">
									<input type="hidden" id="remitem" name="remove_item">
									<div class="itemdivPrnt">
									<?php if(count($rowmaterials) > 0) { $i=0; ?>
										@foreach($rowmaterials as $irow)
										<?php $i++; ?>
										<div class="itemdivChld">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%">
														<input type="hidden" name="row_id[]" id="rowid_{{$i}}" value="{{$irow->id}}">
														<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$irow->subitem_id}}">
														<input type="text" id="itmcod_{{$i}}" name="itemcode[]" value="{{$irow->item_code}}" class="form-control" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_{{$i}}" value="{{$irow->description}}" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="8%">
														<input type="number" id="itmqty_{{$i}}" step="any" name="quantity[]" value="{{$irow->quantity}}" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" value="{{$irow->unit_price}}" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{$irow->total}}" class="form-control line-total" readonly placeholder="Total">
													</td>
													<td width="1%">
														
													 <button type="button" class="btn-success btn-add-item">
														<i class="fa fa-fw fa-plus-square"></i>
													 </button>
													<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
															
													</td>
												</tr>
											</table>
										</div>
										@endforeach
									<?php } else { ?>
										<div class="itemdivChld">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%">
														<input type="hidden" name="row_id[]" id="rowid_1">
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
									<?php } ?>
									</div>
									</fieldset>
								</div>
								<?php } ?>
								<hr/>
								@if($formdata['bin_loc']==1)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Bin Location</label>
                                    <div class="col-sm-10">
										<input type="text" id="selectize-tags2" class="demo-default" value="{{$itemrow->bin_location}}" name="bin_location">
                                    </div>
                                </div>
								@endif
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('itemmaster') }}" class="btn btn-danger">Cancel</a><br/>
										
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

			<div id="editqty_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Dimension</h4>
						</div>
						<div class="modal-body"><form class="form-vertical">
														
							<div class="form-group">
								<label for="input-text" class="col-sm-2 control-label">Length</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="length" autocomplete="off" value="{{ $itemrow->itmLt }}">
								</div>
							</div>

							<div class="form-group">
								<label for="input-text" class="col-sm-2 control-label">Width</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="weight" autocomplete="off" value="{{ $itemrow->itmWd }}">
								</div>
							</div>
							
							
							</form>
							<br/><br/>
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-info addQty" data-dismiss="modal">Add
							</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close
							</button>
						</div>
					</div>
				</div>
			</div>

			<div id="bin_modal" class="modal fade animated" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Select Bin Location</h4>
						</div>
						<div class="modal-body" id="bin_data">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div> 
			
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
        
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

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
$('.mfg-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
$('.exp-date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
$(document).ready(function () {
    
	
	@if($itemrow->dimension==1)
		$('#norEntry').hide()
		$('#dimEntry').show();
	@else
		$('#norEntry').show()
		$('#sEntry').show()
		$('#dimEntry').hide();
	@endif
	
	@if($itemrow->batch_req==1)
		$('.batch-entry').show()
	@else
		$('.batch-entry').hide()
	@endif
    
        @if($itemrow->assembly==1)
		  $('#rMat').show();
		@else
		$('#rMat').hide();
		@endif
    
   
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
	
	<?php if(count($rowmaterials) == 0) { ?>
	<?php } ?>
	if( $('.locPrntItm').is(":hidden") ) 
		$('.locPrntItm').toggle();
	
	var urlcode = "{{ url('itemmaster/checkcode/') }}";
	var urldesc = "{{ url('itemmaster/checkdesc/') }}";
    $('#frmItem').bootstrapValidator({
        fields: {
            item_code: { validators: { 
					notEmpty: { message: 'The item code is required and cannot be empty!' },
					remote: { url: urlcode,
							  data: function(validator) {
								return { item_code: validator.getFieldElements('item_code').val(),
										 id: validator.getFieldElements('item_id').val() };
							  },
							  message: 'The item code is not available'
                    }
                }
            },
			description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' },
										/*  remote: { url: urldesc,
												  data: function(validator) {
													return { description: validator.getFieldElements('description').val(),
															 id: validator.getFieldElements('item_id').val()
															};
												  },
												  message: 'The item description is not available'
										} */
							} 
			},
			item_class: { validators: { notEmpty: { message: 'The item class is required and cannot be empty!' } } },
			//group_id: { validators: { notEmpty: { message: 'The group name is required and cannot be empty!' } } },
			//category_id: { validators: { notEmpty: { message: 'The category name is required and cannot be empty!' } } },
			unit_1: { validators: { notEmpty: { message: 'The unit is required and cannot be empty!' } } },
			//batches: { validators: { notEmpty: { message: 'Batch No. is required and cannot be empty!' } } }, 
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
    
    
    $(document).on('change', '#batch_req', function(e) { 
		  if($('#opn_qty_1').val()!='' && this.value==1) {
		  
    		    $('#frmItem').bootstrapValidator('addField', 'batches');
    	        $('#frmItem').data('bootstrapValidator')
                    .addField('batches', {
                        validators: {
                            notEmpty: {
                                message: 'Batch no is required!'
                            }
                        }
                });
		  }
    });

	$('.mfgItm').on('ifChecked', function(event){ 
		 if(this.value==1)
			 $('#rMat').show();
		 else
			 $('#rMat').hide();
     });
	 
	 $(document).on('change', '#unit_1', function(e) { 
		 $('#packing_1').val($("#unit_1 option:selected").text());  
		 if($("#title_12").html()!='')
		    $('#title_12').html($("#unit_1 option:selected").text());
		    
		 if($("#title_13").html()!='')
		    $('#title_13').html($("#unit_1 option:selected").text());
     });
	 $(document).on('change', '#selvat_1', function(e) { 
		  $('#vat_1').val($("#selvat_1 option:selected").val()); 
		  $('#vat_2').val($("#selvat_1 option:selected").val());      		  
     });
	 $(document).on('change', '#selvat_2', function(e) { 
		  $('#vat_2').val($("#selvat_2 option:selected").val());      
     });
     
     $(document).on('click', '.lang-trans', function(e) { 
	   e.preventDefault();   
	   var text=$('#description').val();
	   console.log(text);
     if(text!='') {
		   $.ajax({
			url: "{{ url('itemmaster/get_langtrans/') }}",
			type: 'get',
			data: 'text='+text,
			success: function(data) {
				console.log(data);
			
			$('#descriptionar').val(data);
			}
		})
	   }
		  
    });
	 
	 $(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   
		   var locUrl = "{{ url('location/get_loc') }}";
		   $('#locData_1').load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('.locPrntItm').toggle();
    });
	
	
    $(document).on('change', '#unit_2', function(e) { //console.log( $(this).find("option:selected").text() );
		var title = $(this).find("option:selected").text();
		   $('#title_1').html(title+' ='); //$('#packing_2').val('1 '+$("#unit_2 option:selected").text()+' =');
		   var pval = parseFloat(($('#packing_22').val()=='')?1:$('#packing_22').val());
		   $('#packing_22').val(pval);
		   $('#title_12').html($("#unit_1 option:selected").text());
		   
		     var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
    		 var pkno = parseFloat($('#pkno_22').val());
    		 var packing = parseFloat($('#packing_22').val());
    		
    		 var ans = (qty * pkno)/packing; 
    		 $('#opn_qty_2').val(ans.toFixed(2));
    		 
    		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
    		 $('#opn_cost_2').val( (ocost * packing / pkno ).toFixed(2) );
    		 
    		 var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
	    	$('#sell_price_2').val( (price * packing / pkno ).toFixed(2) );
	    	
	    	var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
	    	$('#wsale_price_2').val( (wprice * packing / pkno ).toFixed(2) );
	    	
     });
	 
	  //24JN24
	 $(document).on('change', '#unit_3', function(e) { 
		var title = $(this).find("option:selected").text();
		   $('#title_2').html(title+' ='); //$('#title_2').html('1 '+$("#unit_1 option:selected").text()+' ='); 
		   var pval = parseFloat(($('#packing_23').val()=='')?1:$('#packing_23').val());
		   $('#packing_23').val(pval);
		   $('#title_13').html($("#unit_1 option:selected").text()); //$('#title_13').html(title);

		    var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
    	    var pkg3 = $('#packing_23').val();
    		var pkno3 = parseInt( $('#pkno_23').val() );
    
    		var ans = (qty * pkno3) / pkg3; 
    		var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
     		$('#opn_cost_3').val( (ocost * pkg3 / pkno3).toFixed(2) );
    		
    		$('#opn_qty_3').val(ans.toFixed(2));
    		
    		var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
    		var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
    		$('#sell_price_3').val( (price * pkg3 / pkno3).toFixed(2) );
			$('#wsale_price_3').val( (wprice * pkg3 / pkno3).toFixed(2) );
     });
	 
	 //24JN24
	 $(document).on('blur', '#packing_22,#pkno_22', function(e) { 
		 var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		 var pkno = parseFloat($('#pkno_22').val());
		 var packing = parseFloat($('#packing_22').val());
		
		 var ans = (qty * pkno)/packing; 
		 $('#opn_qty_2').val(ans.toFixed(2));
		 
		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 $('#opn_cost_2').val( (ocost * packing / pkno ).toFixed(2) );
		 
		var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
		$('#sell_price_2').val( (price * packing / pkno ).toFixed(2) );

		var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
	    $('#wsale_price_2').val( (wprice * packing / pkno ).toFixed(2) );
			
     });
     
     $(document).on('blur', '#packing_23,#pkno_23', function(e) { 
		 var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		 var pkno = parseInt($('#pkno_23').val());
		 var packing = parseFloat($('#packing_23').val());
		 
		 var ans = (qty * pkno)/packing;
		 
		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 $('#opn_cost_3').val( (ocost * pkno * packing).toFixed(2) );

		 $('#opn_qty_3').val(ans.toFixed(2));
		

		 var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		// $('#opn_cost_3').val( $('#opn_cost_1').val() );  //$('#opn_cost_3').val( ocost * this.value );
		
		var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
	    var pkg3 = $('#packing_23').val();
		var pkno3 = parseInt( $('#pkno_23').val() );

		var ans = (qty * pkno3) / pkg3; 
		var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
 		$('#opn_cost_3').val( (ocost * pkg3 / pkno3).toFixed(2) );
		
		$('#opn_qty_3').val(ans.toFixed(2));
		
		var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
		var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
		$('#sell_price_3').val( (price * pkg3 / pkno3 ).toFixed(2) );
		$('#wsale_price_3').val( (wprice * pkg3 / pkno3 ).toFixed(2) );
			
     });
	 
	 //24JN24
	  $(document).on('blur', '#opn_qty_1', function(e) {
		 var qty = parseInt( $('#opn_qty_1').val() );
		 var pkg = parseInt( ($('#packing_22').val()=='')? 0 : $('#packing_22').val() ); 
		 
		   if(pkg!='') {
			   var pkno = parseInt( $('#pkno_22').val() );

				var ans = (qty * pkno) / pkg; 
				var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 		$('#opn_cost_2').val( (ocost * pkg / pkno ).toFixed(2) );

			   $('#opn_qty_2').val(ans);

			   var ocost3 = parseInt( ($('#packing_23').val()=='')?0:$('#packing_23').val() );

			    var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
			    var pkg3 = $('#packing_23').val();
				var pkno3 = parseInt( $('#pkno_23').val() );

				var ans = (qty * pkno3) / pkg3; 
				var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		 		$('#opn_cost_3').val( (ocost * pkg3 / pkno3).toFixed(2) );
				
        		$('#opn_qty_3').val(ans.toFixed(2));
        		
        		var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
        		$('#sell_price_2').val( (price * pkg / pkno ).toFixed(2) );
        		
        		var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
	    	    $('#wsale_price_2').val( (wprice * pkg / pkno ).toFixed(2) );
	    	    
	    	    
        		$('#sell_price_3').val( (price * pkg3 / pkno3 ).toFixed(2) );
        		$('#wsale_price_3').val( (wprice * pkg3 / pkno3 ).toFixed(2) );
        		
        		
		   }
	 });
	 
	 $(document).on('blur', '#opn_cost_1', function(e) {
		 var ocost = parseInt( $('#opn_cost_1').val() );
		 var pkg = parseInt( $('#packing_22').val() );
		 
		 $('#opn_cost_2').val( ocost * pkg );

		var ocost3 = parseInt( ($('#packing_23').val()=='')?0:$('#packing_23').val() );
		$('#opn_cost_3').val( (ocost / ocost3).toFixed(2) );

		var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		var pkno = parseFloat($('#pkno_22').val());
		var pkng = parseFloat($('#packing_22').val());
		
		var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		$('#opn_cost_2').val( ( (ocost * pkng) / pkno).toFixed(2) );
		
        var price =  parseFloat( ($('#sell_price_1').val()=='')?0:$('#sell_price_1').val());
		$('#sell_price_2').val( (price * pkng /pkno ).toFixed(2) );
		
		var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
	    $('#wsale_price_2').val( (wprice * pkng / pkno ).toFixed(2) );
		
		var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
		var pkno = parseInt($('#pkno_23').val());
		var pkng = parseFloat($('#packing_23').val());
		 
		var ans = (qty * pkno) / pkng; 
		var ocost = parseInt( ($('#opn_cost_1').val()=='')?0:$('#opn_cost_1').val() );
		$('#opn_cost_3').val( (ocost * pkng / pkno ).toFixed(2) );

		$('#opn_qty_3').val(ans.toFixed(2));
		
		$('#sell_price_3').val( (price * pkng / pkno ).toFixed(2) );
		$('#wsale_price_3').val( (wprice * pkng / pkno ).toFixed(2) );
		

	 });
	 
	 
	  $('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	var rowNum = $('#rowNum').val();
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="itemcode[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('input[name="row_id[]"]')).attr('id', 'rowid_' + rowNum);
			$('#locData_'+rowNum).html('');
			newEntry.find('input').val(''); 
			
			$('#packing_'+rowNum).val(1);
			
			//ROWCHNG
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('#itmcod_'+rowNum).focus();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#rowid_'+curNum).val():remitem+','+$('#rowid_'+curNum).val();
		$('#remitem').val(ids);
		
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
	var unit_id = '';
	
	$.ajax({
		url: "{{ url('itemmaster/get_cost_avg/') }}", //get_cost_sale
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

$(document).on('click','.addQty', function() {
	if($('#length').val()=='')
		alert('Length is required!');
	else if($('#weight').val()=='')
		alert('Weight is required!');
	else {
		var qty = $('#opn_qtyD_2').val() * $('#weight').val() * $('#length').val();
		$('#opn_qtyD_1').val(qty.toFixed(2));
		$('#opn_qtyD').val(qty.toFixed(2));
		$('#itmWd').val($('#weight').val());
		$('#itmLt').val($('#length').val());
	}
})

$(document).on('change','#dimension', function() { 
	if($(this).val()==1) {
		$('#norEntry').hide()
		$('#dimEntry').show();
	} else {
		$('#norEntry').show()
		$('#sEntry').show()
		$('#dimEntry').hide();
	} 
})

$(document).on('change', '#unitD_1', function(e) { 
	$('#packingD_1').val($("#unitD_1 option:selected").text());      
});

$(document).on('blur', '#sell_price_1', function(e) {

	var price =  $('#sell_price_1').val();
	var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
	var pkno = parseFloat($('#pkno_22').val());
	var pkng = parseFloat($('#packing_22').val());
	
	$('#sell_price_2').val( (price * pkng / pkno ).toFixed(2) );
	
	var wprice =  parseFloat( ($('#wsale_price_1').val()=='')?0:$('#wsale_price_1').val());
	$('#wsale_price_2').val( (wprice * pkng / pkno ).toFixed(2) );

	var pkno3 = parseInt($('#pkno_23').val());
	var pkng3 = parseFloat($('#packing_23').val());
	
	$('#sell_price_3').val( (price * pkng3 / pkno3 ).toFixed(2) );
	$('#wsale_price_3').val( (wprice * pkng3 / pkno3 ).toFixed(2) );
	

 });

 
$(document).on('blur', '#wsale_price_1', function(e) {

	var price =  $('#wsale_price_1').val() ;
	var qty = parseInt( ($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val() );
	var pkno = parseFloat($('#pkno_22').val());
	var pkng = parseFloat($('#packing_22').val());
	
	$('#wsale_price_2').val( (price * pkng / pkno).toFixed(2) );

	var pkno3 = parseInt($('#pkno_23').val());
	var pkng3 = parseFloat($('#packing_23').val());
	
	$('#wsale_price_3').val( (price * pkng3 / pkno3 ).toFixed(2) );

});

var binurl = "{{ url('location/bin_data/') }}";
$(document).on('click', 'input[name="bin[]"]', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; 
	$('#bin_data').load(binurl+'/'+curNum, function(result){ 
		$('#myModal').modal({show:true}); 
	});
});

$(document).on('click', '.binRow', function(e) {
	var num = $('#num').val(); 
	$('#bin_'+num).val( $(this).attr("data-code") );
	$('#binid_'+num).val( $(this).attr("data-id") );
	e.preventDefault();
});

var btno = {{($batchcount==0)?1:$batchcount}};
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
   clonedRow.find('.del').html('<button class="btn btn-danger btn-xs funRemove" data-id="" data-no=""><i class="fa fa-fw fa-times-circle"></i></button>');
   
   //clonedRow.find('.opt').html('<input type="radio" id="dflt_" name="is_default"/>');
   //newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
   table.append(clonedRow);
   
   clonedRow.find($('.mfg-date')).datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
   clonedRow.find($('.exp-date')).datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
});

var remId = '';
$(document).on('click', '.funRemove', function(e)  {  
  e.preventDefault();
  remId = $('#remId').val();
  //remId = $(this).attr("data-id");
  $('#remId').val( (remId=='')?$(this).attr("data-id"):remId+','+$(this).attr("data-id") );
  
  //var con = confirm('Are you sure delete this batch?');
	//if(con==true) {
		$(this).closest('tr').remove();
		$('#batchRem').val( $('#remId').val() );
	//}
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

$(document).on('blur', '.bno', function(e)  { 
    
    var res = this.id.split('_');
	var curid = res[1];
    var curNo = $(this).val();
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
    		data: 'batch_no='+curNo+'&id='+$('#bthid_'+curid).val(),
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

$(document).on('click', '.saveBatch', function(e)  { 
   e.preventDefault();
   var is_batch = true; 
   var batchNo = '';//$('#batchNos').val();
   var mfgDate = '';//$('#mfgDates').val();
   var expDate = '';//$('#expDates').val();
   var qtyBatch = '';//$('#qtyBatchs').val();
   var batchId = '';
   var totalQty = parseFloat(0); //($('#opn_qty_1').val()=='')?0:$('#opn_qty_1').val()
   
   $('.bno').each(function() { 
	  var res = this.id.split('_');
	  var n = res[1];
	  if($('#bthno_'+n).val()=='')
	    is_batch = false;
	  else
	    batchNo = (batchNo=='')?$('#bthno_'+n).val():batchNo+','+$('#bthno_'+n).val();
	});

	$('.bid').each(function() { 
	  var res = this.id.split('_');
	  var n = res[1];
	  if($('#bthid_'+n).val()!='')
	    batchId = (batchId=='')?$('#bthid_'+n).val():batchId+','+$('#bthid_'+n).val();
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
      $('#batchNos').val(batchNo);
      $('#mfgDates').val(mfgDate);
      $('#expDates').val(expDate);
      $('#qtyBatchs').val(qtyBatch);
      $('#opn_qty_1').val(totalQty);
      $('#batchIds').val(batchId);
      var table = $('#batchTable');
      table.find('input').val('');
      $('#batch_modal').modal('hide');
      $('#batches').val('true');
      $('#frmItem').bootstrapValidator('revalidateField', 'batches'); 
  }

});

$(document).on('click', '.batch-add', function(e) { 
   
   var batch = $('#batchNos').val(); 
   var mfgdate = $('#mfgDates').val(); 
   var expdate = $('#expDates').val(); 
   var btqty = $('#qtyBatchs').val(); 
   var ids = $('#batchIds').val(); 
   var rem = $('#batchRem').val(); 
   var batchurl = "{{ url('itemmaster/batch-view') }}";
   
   if(batch!='') {
	   var vwUrl = batchurl+'?batch='+batch+'&mfg_date='+mfgdate+'&exp_date='+expdate+'&qty='+btqty+'&ids='+ids+'&act=edit&rem='+rem; 
	   $('#batchData').load(vwUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   } /*else {
       $('#batchDataView').html('');
   }*/
});

$(document).on('change','#batch_req', function() { 
    
    if($(this).val()==1) {
        $('.batch-entry').show();
        $('#opn_qty_1').attr('readonly', true);
    } else {
        $('.batch-entry').hide();
        $('#opn_qty_1').attr('readonly', false);
    }
});

</script>
@stop
