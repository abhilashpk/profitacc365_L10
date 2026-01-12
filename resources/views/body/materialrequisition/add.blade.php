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
                            <form class="form-horizontal" role="form" method="POST" name="frmMaterialRequisition" id="frmMaterialRequisition" action="{{ url('material_requisition/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
							
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('voucher_no')) echo 'form-error';?>">MR. No.</label>
                                    <div class="col-sm-10">
										<?php if($voucherno->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$voucherno->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
										<?php } else { ?>
										<div class="input-group">
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
										<?php } ?>
                                    </div>
									<input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$voucherno->no}}">
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">MR. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" value="{{(old('voucher_date')=='')?date('d-m-Y'):old('voucher_date')}}" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>

								<?php if($formdata['jobname']==1) { ?>
								<div class="form-group">
                                   <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Job Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
										<input type="hidden" name="job_id" id="job_id">
									</div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="jobname" id="jobname">
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>

								<?php if($formdata['supplier_name']==1) { ?>
                               <div class="form-group">
                                  <font color="#16A085">   <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" class="form-control <?php if($errors->has('supplier_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') }}">
									</div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="supplier_name" id="supplier_name">
								<input type="hidden" name="supplier_id" id="supplier_id">
								<?php } ?>

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
								<!-- <?php // if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Type</label>
                                    <div class="col-sm-10">
                                       <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
                                           <option value="">Select Document...</option>
										   <option value="PO">Purchase Order</option>
										   <option value="MR">Material Requisition</option>
										   <option value="SDO">Goods Receipt Note</option> 
                                       </select>
                                    </div>
                                </div>
								<?php // } else { ?>
								<input type="hidden" name="document_type" id="document_type">
								<?php // } ?>
								
								<?php // if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								<?php // } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php // } ?> -->
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
										<th width="14%" class="itmHd">
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
													<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
												</td>
												<td width="7%">
													<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
													<input type="hidden" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" placeholder="Vat"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost">
													<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control line-discount" placeholder="Discount">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?><?php if($formdata['location_item']==1) { ?>
											<div id="loc">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_item" id="location_item">
								<?php } ?>
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1"></div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_1">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_1"></div>
												</div>
											</div>
										
									</div>
								</div>
								
								</fieldset>
								<br/><br/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								
								<input type="hidden" step="any" id="discount" name="discount" class="form-control" placeholder="0">
								<input type="hidden" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('goods_issued') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('goods_issued/add') }}" class="btn btn-warning">Clear</a>
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

                            
							
							<div id="account_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Account Master</h4>
                                        </div>
                                        <div class="modal-body" id="accountData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
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
							
							<div id="stockac_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Account List</h4>
                                        </div>
                                        <div class="modal-body" id="stockacData">
                                            
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
$('#voucher_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
 $('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
$(document).ready(function () { 
	@if(old('voucher_date')=='')
		$('#voucher_date').val('{{date('d-m-Y')}}');
	@else
		$('#voucher_date').val('{{old('voucher_date')}}'); 
	@endif
	
	$('.locPrntItm').toggle();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	
	$("#c_label").toggle();$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('#other_cost_fc').toggle(); $('.OCdivPrnt').toggle(); 
    $('#frmMaterialRequisition').bootstrapValidator({
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
        $('#frmMaterialRequisition').data('bootstrapValidator').resetForm();
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
	 //var doc = $('#document_type option:selected').val();
	
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
		$('#jobname').val($(this).attr("data-cod"));
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
