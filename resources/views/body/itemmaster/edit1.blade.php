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
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem" enctype="multipart/form-data" action="{{ url('itemmaster/update/'.$itemrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="item_id" value="{{ $itemrow->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Item Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="item_code" name="item_code" value="{{ $itemrow->item_code }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Item Decription</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $itemrow->description }}">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Item Class</label>
                                    <div class="col-sm-10">
                                        <select id="item_class" class="form-control select2" style="width:100%" name="item_class">
											@if($itemrow->class_id==1)
											{{--*/ $sel1 = "selected";
													$sel2="";
											/*--}}
											@else
											{{--*/ $sel2 = "selected";
												$sel1="";
											/*--}}	
											@endif
											<option value="1" {{ $sel1 }}>Stock</option>
											<option value="2" {{ $sel2 }}>Service</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Model No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="model_no" name="model_no" placeholder="Model No." value="{{ $itemrow->model_no }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Serial No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Serial No." value="{{ $itemrow->serial_no }}">
                                    </div>
                                </div>
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Group Name</label>
                                    <div class="col-sm-10">
                                        <select id="group_id" class="form-control select2" style="width:100%" name="group_id">
											@foreach ($groups as $group)
											@if($itemrow->group_id==$group['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $group['id'] }}" {{ $sel }}>{{ $group['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sub Group</label>
                                    <div class="col-sm-10">
                                        <select id="subgroup_id" class="form-control select2" style="width:100%" name="subgroup_id">
                                            <option value="">Select Sub Group...</option>
											@foreach ($subgroups as $subgroup)
											@if($itemrow->subgroup_id==$subgroup['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $subgroup['id'] }}" {{ $sel }}>{{ $subgroup['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Category Name</label>
                                    <div class="col-sm-10">
                                        <select id="category_id" class="form-control select2" style="width:100%" name="category_id">
                                            <option value="">Select Category...</option>
											@foreach ($category as $cat)
											@if($itemrow->category_id==$cat['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $cat['id'] }}" {{ $sel }}>{{ $cat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sub Category</label>
                                    <div class="col-sm-10">
                                        <select id="subcategory_id" class="form-control select2" style="width:100%" name="subcategory_id">
                                            <option value="">Select Sub Category...</option>
											@foreach ($subcategory as $subcat)
											@if($itemrow->subcategory_id==$subcat['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $subcat['id'] }}" {{ $sel }}>{{ $subcat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								
								<hr/>
								
								<table id="user" class="table table-bordered table-striped m-t-10">
										<tbody>
										<tr>
											<td class="table_simple" style="width:12%">Unit</td>
											<td class="table_simple">Packing</td>
											<td class="table_simple">Open Qty.</td>
											<td class="table_simple">Open Cost</td>
											<td class="table_simple">S.Price(Retail)</td>
											<td class="table_simple">S.Price(WS)</td>
											<td class="table_simple">Min.Qty.</td>
											<td class="table_simple">Reord. Level</td>
											<td class="table_simple">VAT %</td>
										</tr>
										<tr>
											<td>
												<select id="unit_1" class="form-control select2" style="width:100%" name="unit[]">
													<option value="">Select Unit...</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>
												<input type="text" name="packing[]" id="packing_1" class="form-control" value="{{ $itemunits[0]->packing }}" readonly placeholder="Packing">
											</td>
											<td>
												<input type="number" name="opn_quantity[]" id="opn_qty_1" class="form-control" value="{{ $itemunits[0]->opn_quantity }}" placeholder="Open Qty.">
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
												<input type="number" step="any" type="text" name="vat[]" id="vat_1" class="form-control" value="{{ $itemunits[0]->vat }}" placeholder="VAT %">
											</td>
											
										</tr>
										<tr>
											<td>
												<select id="unit_2" class="form-control select2" style="width:100%" name="unit[]">
													<option value="">Select Unit...</option>
													@foreach ($units as $unit)
													<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
													@endforeach
												</select>
											</td>
											<td>@if(sizeof($itemunits) > 1)
													{{--*/ $packing = $itemunits->packing /*--}}
												@else
													{{--*/ $packing = '' /*--}}
												@endif
												<div><span id="title_1"></span><input type="text" name="packing[]" id="packing_22" value="{{ $packing }}" style="width:50%" class="form-control" > <span id="title_12"></span></div>
											</td>
											<td>
												<input type="number" name="opn_quantity[]" id="opn_qty_2" class="form-control" placeholder="Open Qty.">
											</td>
											<td>
												<input type="number" step="any" name="opn_cost[]" id="opn_cost_2" class="form-control" placeholder="Open Cost">
											</td>
											<td>
												<input type="number" step="any" name="sell_price[]" id="sell_price_2" class="form-control" placeholder="S.Price(Retail)">
											</td>
											<td>
												<input type="number" step="any" name="wsale_price[]" id="wsale_price_2" class="form-control" placeholder="S.Price(WS)">
											</td>
											<td>
												<input type="number" name="min_quantity[]" id="min_quantity_2" class="form-control" placeholder="Min.Qty.">
											</td>
											<td>
												<input type="number" name="reorder_level[]" id="reorder_level_2" class="form-control" placeholder="Reord. Level">
											</td>
											<td>
												<input type="number" step="any" type="text" name="vat[]" id="vat_2" class="form-control" placeholder="VAT %">
											</td>
											
										</tr>
										
										</tbody>
									</table>
								
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Assembly</label>
											@if($itemrow->assembly==0)
											{{--*/ $chk1 = "checked";
													$chk2 = "";
											/*--}}
											@else
											{{--*/ $chk2 = "checked";
													$chk1 = "";
											/*--}}	
											@endif
									<div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio1" name="assembly" value="0" {{ $chk1 }}>
                                            None
                                        </label>
                                        <label class="radio-inline iradio">
                                            <input type="radio" id="inlineradio2" name="assembly" value="1" {{ $chk2 }}>
                                            Mfg. Item
                                        </label>
                                    </div>
                                </div>
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
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                         <a href="{{ url('itemmaster') }}" class="btn btn-danger">Cancel</a>
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

<script>
"use strict";

$(document).ready(function () {
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
										 remote: { url: urldesc,
												  data: function(validator) {
													return { description: validator.getFieldElements('description').val(),
															 id: validator.getFieldElements('item_id').val()
															};
												  },
												  message: 'The item description is not available'
										}
							} 
			},
			item_class: { validators: { notEmpty: { message: 'The item class is required and cannot be empty!' } } },
			group_id: { validators: { notEmpty: { message: 'The group name is required and cannot be empty!' } } },
			category_id: { validators: { notEmpty: { message: 'The category name is required and cannot be empty!' } } },
			unit_id: { validators: { notEmpty: { message: 'The unit is required and cannot be empty!' } } },
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
</script>
@stop
