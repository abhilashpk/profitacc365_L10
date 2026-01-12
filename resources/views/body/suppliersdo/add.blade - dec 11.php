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
                Suppliers Delivery Order
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Suppliers Delivery Order</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Order
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurorder" id="frmPurorder" action="{{ url('suppliers_do/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$voucherno}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Reference No.">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_date" name="lpo_date" data-language='en' placeholder="LPO Date">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Purchase Order#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="purchase_order" readonly name="purchase_order_id" placeholder="Purchase Order" autocomplete="off" onclick="getPurchaseOrder()">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" autocomplete="off">
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
                                    <label for="input-text" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-10">
										<select id="location_id" class="form-control select2" style="width:100%" name="location_id">
                                            <option value="">Select Location...</option>
											@foreach ($location as $loc)
											<option value="{{ $loc['id'] }}">{{ $loc['name'] }}</option>
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
								<br/>
								<fieldset>
								<legend><h5>Item Details</h5></legend>
								<!--<a onclick="window.open('{{url('purchase_order/testdata/')}}', '_blank', 'location=yes,height=500,width=1020,scrollbars=yes,status=yes,top=100,left=60');">Share Page</a>-->
								<div class="itemdivPrnt">
									<div class="itemdivChld">							
										<div class="form-group">
											<div class="col-sm-2">
												<input type="hidden" name="item_id[]" id="itmid_1">
												<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" onclick="getItems(this)" placeholder="Item Code">
											</div>
											<div class="col-xs-10">
												<div class="col-xs-10">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-2"></div>
											<div class="col-xs-10">
												<div class="col-xs-2">
													<span class="small">Unit</span>	<!--<input type="text" name="unit[]" id="itmunt_1" readonly class="form-control" placeholder="Unit">-->
													<select id="itmunt_1" class="form-control select2" style="width:100%" name="unit_id[]" onclick="getUnit(this)"><option value="">Select</option></select>
												</div>
												<div class="col-xs-2">
													<span class="small">Quantity</span> <input type="number" id="itmqty_1" name="quantity[]" class="form-control" placeholder="Qty.">
												</div>
												<div class="col-xs-2">
													<span class="small">Cost/Unit</span> <input type="number" id="itmcst_1" step="any" name="cost[]" class="form-control cost" placeholder="Cost/Unit">
												</div>
												<div class="col-xs-2">
													<span class="small">Discount</span> <input type="number" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control" placeholder="Discount">
												</div>
												<div class="col-xs-2">
													<span class="small">Total</span> <input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-item" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
													 </button>
												</div>
											</div>	
										</div>
										
										
											<div id="moreinfo">
												<button type="button" id="moreinfoItm" class="btn btn-primary btn-xs">More Info</button>
											</div>
											
											<div class="infodivPrntItm">
												<div class="infodivChldItm">							
													<div class="table-responsive">
														<div class="col-xs-10">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Unit</th>
																	<th>Quantity in Hand</th>
																	<th>Selling Price</th>
																	<th>Average Cost</th>
																</tr>
																</thead>
																<tbody>
																<tr>
																	<td>PCs</td>
																	<td>1250</td>
																	<td>1600.00</td>
																	<td>1500.00</td>
																</tr>
																
																</tbody>
															</table>
														</div>
													</div>
												</div>
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
										<span class="small">Currency Dhs</span>	<input type="number" id="total" step="any" name="total" class="form-control" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">FC Currency</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" class="form-control" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT%</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat" name="vat" class="form-control" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control" placeholder="0">
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
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Header</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="header_id" id="header_id">
                                        <input type="text" class="form-control" id="headermsg" name="header" placeholder="Header" autocomplete="off" data-toggle="modal" data-target="#header_modal">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								
								
								
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
										<a href="{{ url('suppliers_do') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            
							<div id="purchase_order_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Purchase Order</h4>
                                        </div>
                                        <div class="modal-body" id="po">
                                            
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
                                        <div class="modal-body" id="sup">
                                            
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
							
							<div id="history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Order History</h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered table-hover">
												<thead>
												<tr>
													<th>#</th>
													<th>Supplier Name</th>
													<th>Item Name</th>
													<th>Quantity</th>
													<th>Unit Price</th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td>1</td>
													<td>Loyce</td>
													<td>Larson</td>
													<td>1000</td>
													<td>123</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Vincenzo</td>
													<td>Bashirian</td>
													<td>500</td>
													<td>45</td>
												</tr>
												</tbody>
											</table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            <div id="grid_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with grid arrangement</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">col-md-12
                                                    <div class="row">
                                                        <div class="col-md-6">col-md-6</div>
                                                        <div class="col-md-6">col-md-6</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
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
	$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#vat_fc").toggle(); $("#net_amount_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); 
	var urlcode = "{{ url('purchase_order/checkrefno/') }}";
    $('#frmPurorder').bootstrapValidator({
        fields: {
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
			/* reference_no: { validators: { notEmpty: { message: 'The reference no is required and cannot be empty!' },
										  remote: { url: urlcode, data: function(validator) {
																	return { code: validator.getFieldElements('reference_no').val() },
																  message: 'The reference no is not available'
										  } }
			}}, */
			voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			currency_id: { enabled: true, validators: { notEmpty: { message: 'The currency is required and cannot be empty!' } }},
			currency_rate: { enabled: true, validators: { notEmpty: { message: 'The currency rate is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurorder').data('bootstrapValidator').resetForm();
    });
	
	$('input').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#vat_fc").toggle(); $("#net_amount_fc").toggle();
	});
	$('input').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#vat_fc").toggle(); $("#net_amount_fc").toggle();
	});
	
});

//
$(function() {	
	var rowNum = 1;
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$(document).on('click', '#moreinfoItm', function(e) { 
		   e.preventDefault();
           $('.infodivPrntItm').toggle();
      });
	  
	$(document).on('click', '.btn-add-item', function(e) 
    { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('select[name="unit_id[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find('input').val(''); newEntry.find('select').empty();
			controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		$('#total').val(btotal);
		
		//discount calcu
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		
		//tax calcu
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		$('#net_amount').val(net_amount + vtotal);
		
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
	
	var pourl = "{{ url('purchase_order/po_data/') }}";
	$('#purchase_order').click(function() {
		$('#po').load(pourl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	/* $(document).on('click', '.poclk', function(e) {
		var url = "{{ url('suppliers_do/add/') }}";
		var id = $(this).attr("data-id");
		location.href = url+'/'+id;
	
	}); */
	
	
	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function() {
		$('#sup').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var itmurl = "{{ url('purchase_order/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itm').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
		
		$(document).on('click', '.itms', function(e) { 
			//console.log( $("#num").val() );
			var id = $("#num").val();
			$("#itmid_"+id).val($(this).attr("data-id"));
			$("#itmcod_"+id).val($(this).attr("data-code"));
			$("#itmdes_"+id).val($(this).attr("data-name"));
			$("#itmunt_"+id).val($(this).attr("data-unit"));
			
			e.preventDefault();
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
	
	$(document).on('blur', 'input[name="quantity[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qty = parseInt(this.value);
		var cost = parseFloat( ($('#itmcst_'+curNum).val()=='')?0:$('#itmcst_'+curNum).val()); 
		var discount = parseFloat( ($('#itmdsnt_'+curNum).val()=='')?0:$('#itmdsnt_'+curNum).val()); 
		var total = (qty * cost) - discount;
		$('#itmttl_'+curNum).val(total); //$('#total').val(total); $('#net_amount').val(total);
		
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		$('#total').val(btotal);
		
		//discount calcu
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		
		//tax calcu
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		$('#net_amount').val(net_amount + vtotal);
		
		if($('#is_fc').is(":checked")){
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#net_amount_fc').val( (btotal - bdiscount) * rate);
		}
		/* var btotal = parseFloat( ($('#total').val()=='')?0:$('#total').val() );
		$('#total').val(btotal+total);
		var ntotal = parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() );
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		$('#net_amount').val(btotal+total-bdiscount); */
	});
	
	//$('input[name="cost[]"]').blur(function(){
	$(document).on('blur', 'input[name="cost[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qty = parseInt( ($('#itmqty_'+curNum).val()=='')?0:$('#itmqty_'+curNum).val() );
		var cost = parseFloat(this.value);
		var discount = parseFloat( ($('#itmdsnt_'+curNum).val()=='')?0:$('#itmdsnt_'+curNum).val() ); 
		var total = (qty * cost) - discount;
		$('#itmttl_'+curNum).val(total);
		
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		$('#total').val(btotal);
		
		//dicount calcu
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		
		//tax calcu
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		$('#net_amount').val(net_amount + vtotal);
		
		if($('#is_fc').is(":checked")){
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#net_amount_fc').val( (btotal - bdiscount) * rate);
		}
	});
	
	//$('input[name="line_discount[]"]').blur(function(){
	$(document).on('blur', 'input[name="line_discount[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qty = parseInt( ($('#itmqty_'+curNum).val()=='')?0:$('#itmqty_'+curNum).val() );
		var cost = parseFloat( ($('#itmcst_'+curNum).val()=='')?0:$('#itmcst_'+curNum).val() ); 
		var discount = parseFloat( (this.value=='')?0:this.value );
		var total = (qty * cost) - discount;
		$('#itmttl_'+curNum).val(total);
		
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		$('#total').val(btotal);
		
		//discount calcu
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		
		//tax calcu
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		$('#net_amount').val(net_amount + vtotal);
		
		if($('#is_fc').is(":checked")){
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#net_amount_fc').val( (btotal - bdiscount) * rate);
		}
	});
	
	//total discount section.........
	$(document).on('blur', '#discount', function(e) { 
		var btotal = parseFloat($('#total').val());
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		
		//tax calcu
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		$('#net_amount').val(net_amount + vtotal);
		
		if($('#is_fc').is(":checked")){
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#net_amount_fc').val( (btotal - bdiscount) * rate);
		}
	});
	
	
	//Vat calculation section...........
	$(document).on('blur', '#vat', function(e) { 
		
		var btotal = parseFloat($('#total').val());
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		var net_amount = btotal - bdiscount;
		var vat = parseFloat( ($('#vat').val()=='')?0:$('#vat').val() );
		var vtotal = net_amount * vat / 100;
		
		$('#net_amount').val(net_amount + vtotal);
		if($('#is_fc').is(":checked")) {
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#vat_fc').val(vat);
			var fc_net_amount = (btotal - bdiscount) * rate;
			var vtotal = fc_net_amount * vat / 100;
			$('#net_amount_fc').val( fc_net_amount + vtotal );
		}
	});
	
	
	/* var modurl = "{{ url('purchase_order/testdata/') }}";
	$('.form-control').click(function(){
		$('.modal-body').load(modurl, function(result){
			$('#myModal').modal({show:true});
		});
	}); */
	
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
	
	$(document).on('click', '.closebt', function(e) { 
	//$('#closebt').on('click', function(e){
		e.preventDefault();
		$('#grid_modal2').modal('toggle');
	});
	
	
});

var popup;
function getPurchaseOrder() { 
	if($("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var supplier_id = $("#supplier_id").val();
	var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id;
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

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
