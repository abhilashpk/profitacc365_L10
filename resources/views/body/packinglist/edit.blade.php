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
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
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
                Packing List
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Packing List</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
		
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Packing list
                            </h3>
							
							<div class="pull-right">
							@if($printid)
								
								 <a href="{{ url('packing_list/print/'.$printid.'/70') }}" target="_blank" class="btn btn-info btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span> 
								</a>
							
							@endif
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
							<form class="form-horizontal" role="form" method="POST" name="frmPackingList" id="frmPackingList" action="{{ url('packing_list/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="packing_list_id" id="packing_list_id" value="{{ $orderrow->id }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>PL. No.</b></label>
                                    <div class="col-sm-10">
											<input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
											<input type="hidden" value="" name="prefix">
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PL. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" id="voucher_date" data-language='en' autocomplete="off"/>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" value="<?php echo (old('customer_name'))?old('customer_name'):$orderrow->master_name; ?>">
										<input type="hidden" name="customer_id" id="customer_id" value="<?php echo (old('customer_id'))?old('customer_id'):$orderrow->customer_id; ?>">
									</div>
                                </div>
								
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Invoice#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="invoice_nos" readonly name="invoice_nos" placeholder="Invoice No" autocomplete="off" value="{{$orderrow->invoice_nos}}">
										<input type="hidden" id="quotation_id" name="quotation_id" value="{{$orderrow->invoice_ids}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{$orderrow->description}}">
                                    </div>
                                </div>
								
								
								<br/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="3%" class="itmHd">
											<span class="small">#</span>
										</th>
										<th width="18%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="30%" class="itmHd">
											<span class="small">Item Description</span>
										</th>
										<th width="10%" class="itmHd">
											<span class="small">Quantity</span>
										</th>
										<th width="15%" class="itmHd">
											<span class="small">Carton No</span>
										</th>
										<th width="10%" class="itmHd">
											<span class="small">Carton Qty.</span>
										</th>
										<th width="12%" class="itmHd">
											<span class="small">Balance Qty.</span>
										</th>
										<th width="3%" class="itmHd">
											<span class="small"></span> 
										</th>
									</tr>
									</thead>
								</table>

								{{--*/ $i = 0; $m = 1; $num = count($orditems); $total = $vattotal = $nettotal = $nettotal_dh = $total_dh = $vattotal_dh = 0; /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="bqty">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="ui-sortable" id="sortable_portlets">
									<div class="itemdivPrnt sortable">
									@foreach($orditems as $item)
									{{--*/ $i++; /*--}}
										<div class="itemdivChld portlet box">		
																																
											<table border="0" style="border-spacing: 2px; margin-top:10px;">
												<tr>
													<td width="3%" align="center">
														<input type="text" name="orderno[]" id="ordr_{{$i}}" readonly class="form-control" value="{{$m}}" @if($item->is_sub==1) style="display: none;" @else  @php $m++; @endphp @endif>
													</td>
													<td width="18%" style="padding-left:2px;">
														<input type="hidden" name="row_id[]" id="rowid_{{$i}}" value="{{$item->id}}">
														<input type="hidden" name="is_sub[]" id="isub_{{$i}}" value="{{$item->is_sub}}">
														<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
														<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
													</td>
													<td width="30%" style="padding-left:2px;">
														<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->description}}">
													</td>
													
													<td width="10%" style="padding-left:2px;">
														<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$i}}" value="{{$item->quantity}}">
														<input type="number" id="itmqty_{{$i}}" readonly autocomplete="off" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}">
													</td>
													<td width="15%" style="padding-left:2px;">
														<input type="number" id="crtno_{{$i}}" autocomplete="on" step="any" name="crtno[]" value="{{$item->carton_no}}" class="form-control crtn-no" >
													</td>
													<td width="10%" style="padding-left:2px;">
														<input type="number" id="crtnqty_{{$i}}" autocomplete="off" step="any" name="crtqty[]" value="{{$item->carton_qty}}" class="form-control crtn-qty" >
													</td>
													<td width="12%" style="padding-left:2px;">
														<input type="number" id="balqty_{{$i}}" readonly name="balqty[]" class="form-control bal-qty" value="{{$item->balance_qty}}">
													</td>
													<td width="3%" style="padding-left:2px;">
														<button type="button" class="btn btn-success btn-xs btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														</button>
														<button type="button" class="btn btn-danger btn-xs btn-remove-item" data-id="{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														</button>
													</td>
												</tr>
											</table>
										</div>
									@endforeach
									</div>
								</div>
								
								</fieldset><hr/>

								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Carton</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" id="total_crtn" name="total_crtn" class="form-control" value="{{$orderrow->carton_qty}}" readonly>
										</div>
									</div>
                                </div>

								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Quantity</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" id="total_qty" name="total_qty" class="form-control" value="{{$orderrow->item_qty}}" readonly>
										</div>
									</div>
                                </div>
								
								
								<hr/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('packing_list') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('packing_list/edit/'.$orderrow->id) }}" class="btn btn-warning">Clear</a>
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
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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

//$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$(document).ready(function () { 
	
	$('.locPrntItm').toggle();
	$('.itemdivPrnt').find('.btn-add-item').hide();
		
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('.maildivPrnt').toggle();
	var urlcode = "{{ url('packing_list/checkrefno/') }}";
    $('#frmPackingList').bootstrapValidator({
        fields: {
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val(),
								id: validator.getFieldElements('packing_list_id').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			//customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPackingList').data('bootstrapValidator').resetForm();
    });
	
});


//
$(function() {	
	var rowNum = $('#rowNum').val(); 
	function refreshSlNum() {
		$('#listTable tbody tr').each(function(index){
		$(this).find('.sino').text(index + 1);

	  })
	}
 
	$(document).on('click', '.btn-add-item', function(e)  { 
		var bqty = $('#bqty').val();
        rowNum++; //console.log(rowNum);
		e.preventDefault();
		var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
			newEntry = $(currentEntry.clone()).insertAfter($(this).parents('.itemdivChld'));
			newEntry.find($('input[name="row_id[]"]')).attr('id', 'rowid_' + rowNum);
			newEntry.find($('input[name="is_sub[]"]')).attr('id', 'isub_' + rowNum);
			newEntry.find($('input[name="orderno[]"]')).attr('id', 'ordr_' + rowNum);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="crtno[]"]')).attr('id', 'crtno_' + rowNum);
			newEntry.find($('input[name="crtqty[]"]')).attr('id', 'crtnqty_' + rowNum);
			newEntry.find($('input[name="balqty[]"]')).attr('id', 'balqty_' + rowNum);
			
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			
			newEntry.find('#rowid_'+rowNum).val(''); 
			newEntry.find('#crtno_'+rowNum).val(''); 
			newEntry.find('#crtnqty_'+rowNum).val('');
			newEntry.find('#itmqty_'+rowNum).val( bqty ); 
			newEntry.find('#balqty_'+rowNum).val(''); 
			newEntry.find('#ordr_'+rowNum).hide(); 
			newEntry.find('#isub_'+rowNum).val('1'); 
			
			controlForm.find('.btn-add-item').hide();
			controlForm.find('.btn-remove-item').show();
			
						
    }).on('click', '.btn-remove-item', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#p_orditmid_'+curNum).val():remitem+','+$('#p_orditmid_'+curNum).val();
		$('#remitem').val(ids);
		$(this).parents('.itemdivChld:first').remove();
		
		//ROWCHNG
		/*$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}*/
		
		var carr = [];
		$( '.crtn-no' ).each(function() {
			if(this.value!='' && carr.indexOf(this.value) === -1)
				carr.push(this.value);
		 });
		 
		 var tqty = 0;
		$( '.crtn-qty' ).each(function() {
			if(this.value!='')
				tqty = parseFloat(this.value) + parseFloat(tqty);
		 });

		 $('#total_crtn').val(carr.length);
		$('#total_qty').val(tqty);

		e.preventDefault();
		return false;
	});
	

	$(document).on('keyup', '.crtn-qty', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var balqty = parseFloat($('#itmqty_'+curNum).val()-this.value);
		$('#balqty_'+curNum).val(balqty);
		$('#bqty').val(balqty);

		if(balqty > 0) {
			$(this).parents('.itemdivChld:first').find('.btn-add-item').show();
		} else
			$(this).parents('.itemdivChld:first').find('.btn-add-item').hide();

		var carr = [];
		$( '.crtn-no' ).each(function() {
			if(this.value!='' && carr.indexOf(this.value) === -1)
				carr.push(this.value);
		 });
		 
		 var tqty = 0;
		$( '.crtn-qty' ).each(function() {
			if(this.value!='')
			tqty = parseFloat(this.value) + parseFloat(tqty);
		 });

		$('#total_crtn').val(carr.length);
		$('#total_qty').val(tqty);
	});


	var custurl = "{{ url('packing_list/customer_data/') }}";
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

	var supurl = "{{ url('packing_list/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
		
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		if($('.export').is(":checked") ) { 
			$('#vatdiv_'+num).val( '0%' );
			$('#vat_'+num).val( 0 );
			$('#itmcst_'+num).val( $(this).attr("data-price") );
		} else {
			$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#vat_'+num).val( $(this).attr("data-vat") );
			$('#itmcst_'+num).val( $(this).attr("data-price") );
			srvat = $(this).attr("data-vat");
		}
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').remove().end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name)); 
			});

		});
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
