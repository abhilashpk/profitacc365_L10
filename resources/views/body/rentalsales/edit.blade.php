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
                Rental Sales
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Rental Sales Entry
                    </a>
                </li>
                <li>
                    <a href="#">Rental Sales</a>
                </li>
                <li class="active">
                    Edit
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Sales 
                            </h3>
							
							<div class="pull-right">
								<a href="{{ url('rental_sales/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span> 
								</a>
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" action="{{ url('rental_sales/update/'.$row->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
                                   <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>RS. No.</b></label></font>
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$row->voucher_no}}">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">RS. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" value="{{date('d-m-Y',strtotime($row->voucher_date))}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                 <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Reference No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" autocomplete="off" value="{{$row->reference_no}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Account</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="account_master_id" id="account_master_id" value="{{$row->account_master_id}}" class="form-control">
										<input type="hidden" name="old_account_master_id" value="{{$row->account_master_id}}" class="form-control">
										<div class="input-group">
											<input type="text" name="sales_account" id="sales_account" class="form-control" value="{{$row->account}}" readonly>
											<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                  <font color="#16A085">   <label for="input-text" class="col-sm-2 control-label"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" required value="{{$row->customer}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
										<input type="hidden" name="customer_id" id="customer_id" value="{{$row->customer_id}}">
										<input type="hidden" name="old_customer_id" value="{{$row->customer_id}}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{$row->description}}" autocomplete="off">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> VAT</label>
                                    <div class="col-sm-10">
                                        <select id="is_vat" class="form-control select2" name="is_vat">
											<option value="1" {{($row->is_vat==1)?'selected':''}}>Yes</option>
											<option value="0" {{($row->is_vat==0)?'selected':''}}>No</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> VAT Type</label>
                                    <div class="col-sm-10">
                                        <select id="vat_type" class="form-control select2" name="vat_type">
											<option value="0" {{($row->vat_type==0)?'selected':''}}>Exclusive</option>
											<option value="1" {{($row->vat_type==1)?'selected':''}}>Inclusive</option>
										</select>
                                    </div>
                                </div>
							
								
								<br/>
								<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="5%" class="itmHd">
											<span class="small">Ser.Date</span>
										</th>
										<th width="9%" class="itmHd">
											<span class="small">Vehicle/Description</span>
										</th>
										<th width="8%" class="itmHd">
											<span class="small">Driver</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Type</span>
										</th>
										<th width="3%" class="itmHd">
											<span class="small">Duration</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Rate</span>
										</th>
										<th width="6%" class="itmHd">
											<span class="small">Extra Hrs.</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Extra Hr. Rate</span>
										</th>
										<th width="10%" class="itmHd">
											<span class="small">Total</span> 
										</th>
									</tr>
									</thead>
								</table>
									<div class="itemdivPrnt">
											@php $i = 0; $num = count($items); @endphp
											<input type="hidden" id="remitem" name="remove_item">
											<input type="hidden" id="rowNum" value="{{$num}}">
											@foreach($items as $item)
											@php $i++; @endphp
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="8%" >
														<input type="hidden" name="rowid[]" id="rowid_{{$i}}" value="{{$item->id}}">
														<input type="text" id="serdate_{{$i}}" name="service_date[]" value="{{date('d-m-Y',strtotime($item->service_date))}}" required class="form-control serdate"  autocomplete="off">
													</td>
													<td width="17%">
														<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
														<input type="text" name="item_name[]" id="itmdes_{{$i}}" value="{{$item->description}}" required autocomplete="off" class="form-control" data-toggle="modal" data-target="#item_modal">
													</td>
													<td width="10%">
														<input type="hidden" name="drvr_id[]" id="drvrid_{{$i}}" value="{{$item->driver_id}}">
														<input type="text" name="driver_name[]" id="drvrnam_{{$i}}" value="{{$item->driver_name}}" autocomplete="off" class="form-control" data-toggle="modal" data-target="#driver_modal">
													</td>
													<td width="8%">
														<select id="itmunt_{{$i}}" class="form-control select2" required style="width:100%" name="unit[]">
															<option value="">Unit</option>
															@foreach($units as $unit)
															<option value="{{$unit->id}}" {{($item->unit_id==$unit->id)?'selected':''}}>{{$unit->unit_name}}</option>
															@endforeach
														</select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_{{$i}}" autocomplete="off" step="any" value="{{$item->quantity}}" required name="quantity[]" class="form-control line-qty" >
													</td>
													<td width="8%">
														<input type="hidden" name="vat[]" id="vat_{{$i}}" value="{{$item->vat}}">
														<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}" value="{{$item->vat_amount}}">
														<input type="number" id="itmcst_{{$i}}" autocomplete="off" step="any" required name="rate[]" value="{{$item->rate}}" class="form-control line-rate">
													</td>
													<td width="8%">
														<input type="number" id="hrxtr_{{$i}}" autocomplete="off" step="any" name="hrextra[]" value="{{$item->extra_hr}}" class="form-control line-exhr">
													</td>
													<td width="9%">
														<input type="number" id="ratextr_{{$i}}" autocomplete="off" step="any" name="ratextra[]" value="{{$item->extra_rate}}" class="form-control line-exrate">
													</td>
													<td width="10%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" value="{{$item->line_total}}" readonly >
													</td>
													<td width="1%">
														<button type="button" class="btn-success btn-add-item" >
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
										</div>
										<div class="col-xs-2">
										<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{$row->total}}" placeholder="0">
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
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl" value="{{$row->discount}}" placeholder="0">
											<input type="hidden" name="discount_old" value="{{$row->discount}}">
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
										</div>
										<div class="col-xs-2">
											<input type="number" id="subtotal" step="any" name="subtotal" class="form-control spl" readonly value="{{$row->subtotal}}" placeholder="0">
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
										</div>
										<div class="col-xs-2">
											<input type="hidden" id="vatcur" name="vatcur" value="{{$row->vat_amount}}">
											<input type="number" step="any" id="vat" name="vat_total" class="form-control spl" readonly placeholder="0" value="{{$row->vat_amount}}" >
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
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{$row->net_amount}}" placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('rental_sales') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
		<div id="customer_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Select customer</h4>
					</div>
					<div class="modal-body" id="customerData">
						
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
		<div id="driver_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Select Driver</h4>
					</div>
					<div class="modal-body" id="driver_data">
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
	
    $('.serdate').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
	var rowNum = 1;
	$(document).on('click', '.btn-add-item', function(e)  { 
		
        rowNum++;
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="rowid[]"]')).attr('id', 'rowid_' + rowNum);
			newEntry.find($('input[name="service_date[]"]')).attr('id', 'serdate_' + rowNum);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="drvr_id[]"]')).attr('id', 'drvrid_' + rowNum);
			newEntry.find($('input[name="driver_name[]"]')).attr('id', 'drvrnam_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="rate[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="hrextra[]"]')).attr('id', 'hrxtr_' + rowNum);
			newEntry.find($('input[name="ratextra[]"]')).attr('id', 'ratextr_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); 
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			$('.serdate').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#rowid_'+curNum).val():remitem+','+$('#rowid_'+curNum).val();
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
	
	$('#customer_name').click(function() {
		var custurl = "{{ url('sales_order/customer_data/') }}";
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		$('#vat_'+num).val($(this).attr("data-vat"));
	});
	
	$(document).on('keyup', '.line-qty,.line-rate,.line-exhr,.line-exrate,#discount', function(e) {
		getNetTotal();
	});
	
	$(document).on('change', '#is_vat', function(e) {
		if(this.value==0)
			$('#vat_type').prop('disabled', 'disabled');
		else 
			$('#vat_type').prop('disabled', false);
		
		getNetTotal();
	});
	
	$(document).on('change', '#vat_type', function(e) {
		getNetTotal();
	});
	
	var drvurl = "{{ url('rental_sales/get_driver/') }}";
	$(document).on('click', 'input[name="driver_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var supid = $('#customer_id').val();
		$('#driver_data').load(drvurl+'/'+curNum+'/'+supid, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.driverRow', function(e) { 
		var num = $('#num').val();
		$('#drvrid_'+num).val( $(this).attr("data-id") );
		$('#drvrnam_'+num).val( $(this).attr("data-name") );
	});

});


function getLineTotal(n) {
	var lineQty = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
	var rate 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
	var exHr 	 = parseFloat( ($('#hrxtr_'+n).val()=='') ? 0 : $('#hrxtr_'+n).val() );
	var exRate 	 = parseFloat( ($('#ratextr_'+n).val()=='') ? 0 : $('#ratextr_'+n).val() );
	var vatp 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
	var isvat 	 = parseInt( $('#is_vat option:selected').val() );
	var total = (lineQty * rate) + (exHr * exRate);
	var vatamt = 0;
	if(isvat == 1) {
		var vatype = parseInt( $('#vat_type option:selected').val() );
		if(vatype==1) {
			var vatamt = (total * vatp)/105;
		} else {
			var vatamt = (total * vatp)/100;
		}
	} 
	
	$('#itmttl_'+n).val(total.toFixed(2));
	$('#vatamt_'+n).val(vatamt.toFixed(2));
	return total;
}

function getNetTotal() {
	var lineTotal = 0; var lineVat = 0;
	$( '.line-total' ).each(function() { 
	  var res = this.id.split('_');
	  var n = res[1];
	  var amt = getLineTotal(n);
	  lineTotal = parseFloat(lineTotal) + parseFloat(amt.toFixed(2));
	  lineTotal = lineTotal.toFixed(2);
	  
	  lineVat = parseFloat(lineVat) + parseFloat( ($('#vatamt_'+n).val()=='') ? 0 : $('#vatamt_'+n).val() );
	  lineVat = lineVat.toFixed(2);
	});
	
	var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
	var subtotal = 0;
	var netTotal = 0;
	var isvat 	 = parseInt( $('#is_vat option:selected').val() );
	if(isvat == 1) {
		var vatype = parseInt( $('#vat_type option:selected').val() );
		if(vatype==1) {
			subtotal = lineTotal - lineVat;
			subtotal = (subtotal - discount).toFixed(2);
			netTotal = parseFloat(subtotal) + parseFloat(lineVat);
		} else {
			subtotal = (lineTotal - discount).toFixed(2);
			netTotal = parseFloat(subtotal) + parseFloat(lineVat);
		}
	} else {
		subtotal = (lineTotal - discount).toFixed(2);
		netTotal = parseFloat(subtotal) + parseFloat(lineVat);
	}
	$('#total').val(lineTotal);
	$('#vat').val(lineVat);
	$('#subtotal').val(subtotal);
	$('#net_amount').val(netTotal.toFixed(2));
}
</script>
@stop

										