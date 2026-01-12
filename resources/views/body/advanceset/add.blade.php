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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                 Advance Set
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Advance Set</a>
                </li>
				<li class="active">
                    Advance Set
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
                                <i class="fa fa-fw fa-crosshairs"></i> Advance Set for Customer/Supplier
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmAdvanceset" id="frmAdvanceset" action="{{ url('advance_set/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="from_jv" value="0">
								<input type="hidden" name="job_id" value="0">
								<input type="hidden" name="department_id" value="0">
								<input type="hidden" name="description">
								<input type="hidden" name="cheque_date">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Account for</label>
                                    <div class="col-sm-10">
                                       <select id="account_for" class="form-control select2" style="width:100%" name="account_for">
                                           <option value="1">Customer</option>
										   <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group" id="custip">
                                    <label for="input-text" class="col-sm-2 control-label">Customer Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="customer_account" name="customer_account" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
										<input type="hidden" class="form-control" id="customer_id" name="customer_id">
                                    </div>
                                </div>
								
								<div class="form-group" id="suppip">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="supplier_account" name="supplier_account" autocomplete="off" data-toggle="modal" data-target="#supplier_modal">
										<input type="hidden" class="form-control" id="supplier_id" name="supplier_id">
                                    </div>
                                </div>
								
								<br/>
								<fieldset>
								<legend><h5>Customer Credit Transactions</h5></legend>
										<div class="table-responsive item-data" id="crTransactionData">
										</div>
										<div class="table-responsive item-data" id="drTransactionData">
										</div>
								</fieldset>
																
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('customer_receipt') }}" class="btn btn-danger">Cancel</a>
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
								
                            </form>
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
	$('#suppip').toggle();
    $('#frmAdvanceset').bootstrapValidator({
        fields: {
            customer_account: {
                validators: {
                    notEmpty: {
                        message: 'The customer account is required and cannot be empty!'
                    }
                }
            },
			debit: {
                validators: {
                    identical: {
                        field: 'credit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            },
            credit: {
                validators: {
                    identical: {
                        field: 'debit',
                        message: 'The Debit and Credit amount should be equal!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmAdvanceset').data('bootstrapValidator').resetForm();
    });
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			getNetTotal();
		});
	});

});

$(function(){
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
	
	$(document).on('change', '#account_for', function(e) {
		var acfor = this.value;
		
		if(acfor==1) {
			if( $("#custip").is(":hidden") )
				$("#custip").toggle();
			
			if( $("#suppip").is(":visible") )
				$("#suppip").toggle();
			
		} else {
			if( $("#suppip").is(":hidden") )
				$("#suppip").toggle();
			
			if( $("#custip").is(":visible") )
				$("#custip").toggle();
		}
	});
			
	var custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customer_account').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
	    
	   $('#customer_account').val($(this).attr("data-name"));
	   $('#customer_id').val($(this).attr("data-id"));

	   var url = "{{ url('account_enquiry/os_bills_adv/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
		/*$('#customer_account').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('sales_invoice/get_invoiceset/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });*/
	   
	});
	
	
	var suppurl = "{{ url('purchase_invoice/supplier_data/') }}";
	$('#supplier_account').click(function() {
		$('#supplierData').load(suppurl, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '.supp', function(e) {
		$('#supplier_account').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('account_enquiry/os_bills_adv/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
		
	   /*var url = "{{ url('purchase_invoice/get_invoiceset/') }}/"+$(this).attr("data-id");
	   $('#drTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });*/
	   
	});
	
	
	
	$(document).on('keyup', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmAdvanceset').bootstrapValidator('revalidateField', 'debit');
		$('#frmAdvanceset').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmAdvanceset').bootstrapValidator('revalidateField', 'debit');
		$('#frmAdvanceset').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('click', '.accountRow', function(e) {
		$('#dr_account').val( $(this).attr("data-code")+" - "+$(this).attr("data-name") );
		$('#dr_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmAdvanceset').bootstrapValidator('revalidateField', 'debit');
		$('#frmAdvanceset').bootstrapValidator('revalidateField', 'credit');
	});
	
});

function getNetTotal() {
	//var amount = parseFloat( ($('#amount').val()=='') ? 0 : $('#amount').val() );
	var crtotal = 0; var drtotal = 0; var lineTotal = 0; 
	$( '.line-amount' ).each(function() { 
		if( $(this).attr("data-type") == 'Dr')
			drtotal = drtotal + parseFloat( (this.value=='')?0:this.value );
		else
			crtotal = crtotal + parseFloat( (this.value=='')?0:this.value );
			//lineTotal = lineTotal + parseFloat( (this.value=='')?0:this.value );
	});
	var difference = drtotal - crtotal;
	$("#debit").val(crtotal.toFixed(2));
	$("#credit").val(drtotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	$('#frmAdvanceset').bootstrapValidator('revalidateField', 'debit');
	$('#frmAdvanceset').bootstrapValidator('revalidateField', 'credit');
}

function getTag(e) {
		
	var res = e.id.split('_');
	var curNum = res[1];
	if( $("#tag_"+curNum).is(':checked') ) {
		var curamount = $("#hidamt_"+curNum).val();
		$("#lineamnt_"+curNum).val(curamount);	
		getNetTotal();
	} else {
		$("#lineamnt_"+curNum).val('');
		getNetTotal();
	}

	$('input[name="tagadv[]"]').change(function() { 
		if (this.checked) {
			// Uncheck and disable all other data-adv="1" checkboxes
			$('input[name="tagadv[]"]').not(this)
			.prop('checked', false)
			.prop('disabled', true);
		} else {
			// Re-enable all data-adv="1" checkboxes
			$('input[name="tagadv[]"]')
				.prop('disabled', false)
				.prop('checked', false);
		}
	});
	
}

</script>
@stop
