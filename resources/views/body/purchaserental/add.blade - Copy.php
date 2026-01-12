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
                Purchase Rental
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Rental Entry
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Rental</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Purchase 
                            </h3>
							
							<div class="pull-right">
							
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseInvoice" id="frmPurchaseInvoice" enctype="multipart/form-data" action="{{ url('purchase_invoice/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								
								<div class="form-group">

                                   <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>PI. No.</b></label></font>
									<input type="hidden" name="curno" id="curno" value="">
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Account</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="account_master_id" id="account_master_id"  class="form-control">
										<div class="input-group">

											<input type="text" name="purchase_account" id="purchase_account" class="form-control" value="" readonly>
											<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                  <font color="#16A085">   <label for="input-text" class="col-sm-2 control-label"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') }}">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" autocomplete="off" placeholder="Description">
                                    </div>
                                </div>
								
								<br/>
								<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="9%" class="itmHd">
											<span class="small">Service Date</span>
										</th>
										<th width="15%" class="itmHd">
											<span class="small">Vehicle/Description</span>
										</th>
										<th width="9%" class="itmHd">
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
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="12%" >
														<input type="text" id="serdate_1" name="service_date[]" class="form-control serdate" data-language='en' autocomplete="off">
													</td>
													<td width="22%">
														<input type="hidden" name="item_id[]" id="itmid_1">
														<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" data-toggle="modal" data-target="#item_modal">
													</td>
													<td width="12%">
														<select id="itmunt_1" class="form-control select2" style="width:100%" name="unit[]">
															<option value="">Unit</option>
															@foreach($units as $unit)
															<option value="{{$unit->id}}">{{$unit->unit_name}}</option>
															@endforeach
														</select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_1" autocomplete="off" step="any" name="quantity[]" class="form-control line-qty" >
													</td>
													<td width="8%">
														<input type="number" id="itmcst_1" autocomplete="off" step="any" name="rate[]" class="form-control line-rate">
													</td>
													<td width="8%">
														<input type="number" id="hrxtr_1" autocomplete="off" step="any" name="hrextra[]" class="form-control line-exhr">
													</td>
													<td width="9%">
														<input type="number" id="ratextr_1" autocomplete="off" step="any" name="ratextra[]" class="form-control line-exrate">
													</td>
													<td width="10%">
														<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly >
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
										</div>
										<div class="col-xs-2">
										<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{old('total')}}" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl" value="{{old('discount')}}" placeholder="0">
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
											<input type="number" id="subtotal" step="any" name="subtotal" class="form-control spl" readonly value="{{old('subtotal')}}" placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" placeholder="0" value="{{old('vat')}}" >
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
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{old('net_amount')}}" placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('purchase_invoice') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
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
	var urlvchr = ''; var urlcode = '';
    $('#frmPurchaseInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: {
                validators: {
                   
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val(),
								deptid: deptid
                            };
                        },
                        message: 'This PI. No. is already exist!'
                    }
                }
            },
			reference_no: {
                validators: {
                   
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
			
        }
        
    }).on('reset', function (event) {
        $('#frmPurchaseInvoice').data('bootstrapValidator').resetForm();
    });
	
	var rowNum = 1;
	$(document).on('click', '.btn-add-item', function(e)  { 
		
        rowNum++;
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="service_date[]"]')).attr('id', 'serdate_' + rowNum);
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="rate[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="hrextra[]"]')).attr('id', 'hrxtr_' + rowNum);
			newEntry.find($('input[name="ratextra[]"]')).attr('id', 'ratextr_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); 
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			//$('#serdate_'+rowNum).focus();
			$('.serdate').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		
		//getNetTotal();
		
		//ROWCHNG
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
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
	});
	
	$(document).on('keyup', '.line-qty,.line-rate,.line-exhr,.line-exrate,#discount,#vat', function(e) {
		getNetTotal();
	});

});

function getLineTotal(n) {
	var lineQty = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
	var rate 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
	var exHr 	 = parseFloat( ($('#hrxtr_'+n).val()=='') ? 0 : $('#hrxtr_'+n).val() );
	var exRate 	 = parseFloat( ($('#ratextr_'+n).val()=='') ? 0 : $('#ratextr_'+n).val() );
	var total = (lineQty * rate) + (exHr * exRate);
	$('#itmttl_'+n).val(total.toFixed(2));
	return total;
}

function getNetTotal() {
	var lineTotal = 0;
	$( '.line-total' ).each(function() { 
	  var res = this.id.split('_');
	  var n = res[1];
	  var amt = getLineTotal(n);
	  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
	  lineTotal = lineTotal.toFixed(2);
	});
	
	var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
	var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
	var subtotal = (lineTotal - discount).toFixed(2);
	var netTotal = parseFloat(subtotal) + vat;
	$('#total').val(lineTotal);
	$('#subtotal').val(subtotal);
	$('#net_amount').val(netTotal.toFixed(2));
}

</script>
@stop

										