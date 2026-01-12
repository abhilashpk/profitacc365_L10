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
                Credit Note
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li>
                    <a href="#">Credit Note</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Credit Note 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
						<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmCreditNote" id="frmCreditNote" action="{{ url('credit_note/update/'.$cnrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="from_jv" value="0">
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100% ;background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" {{($cnrow->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Voucher</label>
									 <div class="col-sm-10">
									   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
										   @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php echo ($cnrow->voucher_id==$voucher['id'])?'selected':'';?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								@endif
								
															
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{ $cnrow->voucher_no }}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" value="{{ date('d-m-Y', strtotime($cnrow->voucher_date)) }}" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Debit Account</label>
									<input type="hidden" name="curno" id="curno">
                                    <div class="col-sm-10">
										
                                        <input type="text" class="form-control" id="sales_account" value="{{ $cnrow->master_name }}" readonly name="sales_account" autocomplete="off" data-toggle="modal" data-target="#dr_account_modal">
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="{{ $cnrow->dr_account_id }}" class="form-control">
										
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-addon">Dr<input type="hidden" id="transaction" value="Dr" name="transaction"></span>
											<input type="number" step="any" class="form-control" id="amount" name="amount" value="{{$cnrow->amount}}">
											<span class="input-group-addon">.00</span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" value="{{ $cnrow->description }}" autocomplete="off" name="description">
                                    </div>
                                </div>
								
								<br/>
								
								<fieldset>
								<legend><h5>Transactions</h5></legend>
										{{--*/ $i = 0; $num = count($cnitems); /*--}}
										<input type="hidden" id="rowNum" value="{{$num}}">
										<input type="hidden" id="remitem" name="remove_item">
										<div class="itemdivPrnt">
										@foreach($cnitems as $item)
										{{--*/ $i++; /*--}}
										<?php 
										$invoice_id = $tr_id = '';
										?>
											<div class="itemdivChld">							
												<div class="form-group">
													<div class="col-sm-2"> <span class="small">Account Name<!-- acntname_1 --></span>
													<input type="text" id="craccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
													<input type="hidden" name="cr_account_id[]" value="{{$item->cr_account_id}}" id="craccountid_{{$i}}"><!-- acntid_1 -->
													<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
													<input type="hidden" name="vatamt[]" id="vatamt_{{$i}}">
													<input type="hidden" id="trid_{{$i}}" name="tr_id[]" value="{{$item->id}}">
													
													<input type="hidden" id="invoiceid_1" name="purchase_invoice_id[]" value="{{$item->invoice_id}}">
													<input type="hidden" name="bill_type[]" value="SI">
													</div>
													<div class="col-xs-15">
														<div class="col-xs-3">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" value="{{$item->cr_description}}" name="cr_description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:10%;">
															<span class="small">Reference</span> 
															<div id="refdata_1" class="refdata">
															<input type="text" id="ref_{{$i}}" name="cr_reference[]" value="{{$item->cr_reference}}" class="form-control ref-invoice" data-toggle="modal" data-target="#reference_modal">
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="{{$item->cr_amount}}">
														</div>
														<div class="col-xs-1">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="type[]">
																<option value="Cr">Cr</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" value="{{$item->cr_amount}}" step="any" name="cr_amount[]" class="form-control line-amount">
														</div>
														<div class="col-sm-2"> 
															<span class="small">Job</span> 
															<select id="jobid_{{$i}}" class="form-control select2 line-job" style="width:100%" name="job_id[]">
																<option value="">Select Job...</option>
																@foreach($jobs as $job)
																<option value="{{ $job['id'] }}" <?php if($item->job_id==$job['id']) echo 'selected';?>>{{ $job['name'] }}</option>
																@endforeach
															</select>
														</div>
														<div class="col-xs-1"><br/>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
															
														</div>
													</div>	
												</div>
												
												<div class="form-group">
													<div class="col-sm-2"> 
														
													</div>
													<div class="col-xs-15">
														<input type="hidden" name="department[]" id="dept_1">
														<div id="chqdtl_{{$i}}" class="divchq" <?php if(($item->category!='PDCR') && ($item->category!='PDCI')) echo 'style="display: none;"'; ?>>
														
														<div class="col-sm-2"> 
															<span class="small">Cheque No</span><input type="text" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
														</div>
														
														<div class="col-xs-2">
															<span class="small">Cheque Date</span> <input type="text" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
														</div>
														
														<div class="col-xs-2">
															<span class="small">Bank</span> 
															<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
															<option value="">Select Bank...</option>
																@foreach($banks as $bank)
																<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																@endforeach
															</select>
														</div>
														</div>
													</div>	
												</div>
												
												
											</div>
										@endforeach
										</div>
								</fieldset>
								<hr/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly value="{{ number_format($cnrow->amount,2) }}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly value="{{ number_format($cnrow->amount,2) }}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" value="{{ number_format($cnrow->difference,2) }}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('credit_note') }}" class="btn btn-danger">Cancel</a>
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
								
								<div id="dr_account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Debit Account</h4>
											</div>
											<div class="modal-body" id="drAccountData">
												
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
								
								<div id="reference_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog" style="width:60%">
										<div class="modal-content" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Invoice</h4>
											</div>
											<div class="modal-body" id="invoiceData">
												
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
<script>

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$(document).ready(function () {
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
		
    $('#frmCreditNote').bootstrapValidator({
        fields: {
            voucher_type: {
                validators: {
                    notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    }
                }
            },
			voucher_date: {
                validators: {
                    notEmpty: {
                        message: 'The voucher date is required and cannot be empty!'
                    }
                }
            },
			dr_account: {
                validators: {
                    notEmpty: {
                        message: 'The debit account is required and cannot be empty!'
                    }
                }
            },
			/* description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            }, */
			amount: {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			customer_account: {
                validators: {
                    notEmpty: {
                        message: 'The customer account is required and cannot be empty!'
                    }
                }
            },
			cheque_no: {
                validators: {
					callback: {
                            message: 'The cheque no is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCreditNote').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
                        }
                    /* notEmpty: {
                        message: 'The cheque no is required and cannot be empty!'
                    } */
                }
            },
			cheque_date: {
                validators: {
                    callback: {
                            message: 'The cheque date is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCreditNote').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
                        }
                }
            },
			bank_id: {
                validators: {
                    callback: {
                            message: 'The bank is required and cannot be empty',
                            callback: function(value, validator, $field) {
                                var voucher_type = $('#frmCreditNote').find('[name="voucher_type"]').val();
                                return (voucher_type !== 'PDCR') ? true : (value !== '');
                            }
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
        
    }).on('change', '[name="voucher_type"]', function(e) {
        $('#frmCreditNote').formValidation('revalidateField', 'cheque_no');
		$('#frmCreditNote').formValidation('revalidateField', 'cheque_date');
		$('#frmCreditNote').formValidation('revalidateField', 'bank_id');
    }).on('blur', '.line-amount', function(e) { 
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'debit');
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'credit');
	}).on('click', '#frmCreditNote', function(e) { 
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'debit');
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'credit');
	});
	/* .on('reset', function (event) {
        $('#frmCreditNote').data('bootstrapValidator').resetForm();
    }); */
	<?php if($cnrow->voucher_type=='CASH') { ?>
		$("#chequeInput").toggle();
	<?php } ?>
		
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		
	});
	
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$('#amount_fc').val('');$("#currency_rate").val('');
	});
	
	$('.onacnt_icheck').on('ifChecked', function(event){ 
		$('#onAccount').toggle();
		$('#crTransactionData').toggle();
	});
	
	$('.onacnt_icheck').on('ifUnchecked', function(event){ 
		$('#onAccount').toggle();
		$('#crTransactionData').toggle();
	});
	
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
	var rowNum = $('#rowNum').val();
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  
   $('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('credit_note/getdeptvoucher/') }}/" + dept_id, function(data) { 
		
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
			$.each(data, function(key, value) {  
				$('#voucher_id').find('option').end()
						.append($("<option></option>")
						.attr("value",value.voucher_id)
						.text(value.voucher_name)); 
			});
			
			$('#sales_account').val(data[0].dr_account_name);
			$('#dr_account_id').val(data[0].dr_id);
			
		});
	});
	
	
   $(document).on('click', '.btn-add-item', function(e)  { 
  
        var group_id = $('#groupid_'+rowNum).val();
		var refno = $('#ref_'+rowNum).val();
		
		rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="account_name[]"]')).attr('id', 'craccount_' + rowNum);
			newEntry.find($('input[name="cr_account_id[]"]')).attr('id', 'craccountid_' + rowNum);
			newEntry.find($('input[name="cr_description[]"]')).attr('id', 'descr_' + rowNum);
			newEntry.find($('input[name="cr_reference[]"]')).attr('id', 'ref_' + rowNum);
			newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
			newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
			newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
			newEntry.find($('input[name="cr_amount[]"]')).attr('id', 'amount_' + rowNum);
			newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
			newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
			newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
			newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
			newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
			newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
			newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
			newEntry.find($('input[name="purchase_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
			newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
			var des = $('input[name="cr_description[]"]').val();
			newEntry.find('input').val(''); 
			newEntry.find($('input[name="cr_description[]"]')).val(des);
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('.chqdate').datepicker({
				language: 'en',
				dateFormat: 'dd-mm-yyyy'
			});
			
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		$(this).parents('.itemdivChld:first').remove();
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
  var custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customer_account').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_account').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
		
	   var url = "{{ url('sales_invoice/get_invoice/') }}/"+$(this).attr("data-id");
	   $('#crTransactionData').load(url, function(result) {
		  $('#myModal').modal({show:true});
	   });
	   
	});
	
	$(document).on('blur', '.line-amount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		getNetTotal();
	});
	
	$(document).on('blur', '#amount', function(e) {
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'debit');
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'credit');
		getNetTotal();
	});
	
	
	$('#dr_account').click(function() {
		var vtype = $('#voucher_type option:selected').val();
		vtype = (vtype=='')?0:vtype;
		var acnturl = "{{ url('account_master/get_account_list/') }}/"+vtype; 
		$('#drAccountData').load(acnturl, function(result) { 
			$('#myModal').modal({show:true});
		});
	});
	
	var acurl;
	$(document).on('click', 'input[name="account_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		if($('#department_id').length)
			acurl = "{{ url('account_master/get_accounts/') }}"+'/'+curNum+'/'+$('#department_id option:selected').val();
		else
			acurl = "{{ url('account_master/get_accounts/') }}"+'/'+curNum;
		$('#account_data').load(acurl, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	
	//new change.................
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val(); var vatasgn = $(this).attr("data-vatassign");
		$('#craccount_'+num).val( $(this).attr("data-name") );
		$('#craccountid_'+num).val( $(this).attr("data-id") );
		$('#groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
		$('#vatamt_'+num).val( $(this).attr("data-vat") );
		
		if($(this).attr("data-group")=='PDCR') { 
			$('#chktype').val('PDCR');
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
			if( $('#chqdtl_'+num).is(":hidden") )
				$('#chqdtl_'+num).toggle();
				
		} else if($(this).attr("data-group")=='PDCI') { 
			$('#chktype').val('PDCI');
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
			if( $('#chqdtl_'+num).is(":hidden") )
				$('#chqdtl_'+num).toggle();
		} else {
			//$('#chktype').val('');
			$('#acnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
			if( $('#chqdtl_'+num).is(":visible") )
				$('#chqdtl_'+num).toggle();
		}
		
		if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER') {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="cr_reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
		} else {// group.id
			$('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="cr_reference[]" class="form-control">');
		}
		
		if( $(this).attr("data-vatassign")=='1') {// group.id
			$('#trndtl_'+num).toggle();
		}
	});
	
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		var vchr_type = $('#voucher_type option:selected').val();
		$.get("{{ url('credit_note/getvoucher/') }}/" + vchr_id+'/'+vchr_type, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#dr_account').val(data.account_name);
				$('#dr_account_id').val(data.id);
			} else {
				$('#dr_account').val('');
				$('#dr_account_id').val('');
			}
		});
	});
	
	$(document).on('blur', '#cheque_no', function(e) {
		var chqno = this.value;
		
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno,
			success: function(data) { 
				if(data=='') {
					alert('Cheque no is duplicate!');
					$('#cheque_no').val('');
				}
			}
		})
	});
	
	$(document).on('click', '.ref-invoice', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		if( $('#groupid_'+curNum).val()=='CUSTOMER') { //customer type.............
			var url = "{{ url('sales_invoice/get_invoice/') }}/"+$('#craccountid_'+curNum).val();
			var reid = $('#trid_'+curNum).val();
			if((reid!='') && (this.value!='')) {
				$('#invoiceData').load(url+'/'+curNum+'/'+this.value+'/'+reid, function(result){ 
					$('#myModal').modal({show:true});
				});
			}
			
		} else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
			var url = "{{ url('purchase_invoice/get_invoice/') }}/"+$('#craccountid_'+curNum).val();
			$('#invoiceData').load(url+'/'+curNum, function(result){ //.modal-body item
				$('#myModal').modal({show:true});
			});
		}
	});
	
	$(document).on('click', '.add-invoice', function(e)  { 
	
		var refs = []; var amounts = []; var type = []; var ids = []; var actamt = []; var invid = [];
		$("input[name='tag[]']:checked").each(function() { 
			var res = this.id.split('_');
			var curNum = res[1];
			ids.push($(this).val());
			refs.push( $('#refid_'+curNum).val() );
			amounts.push( $('#lineamnt_'+curNum).val() );
			type.push( $('#actype_'+curNum).val() );
			actamt.push( $('#hidamt_'+curNum).val() );
			invid.push( $('#sinvoiceid_'+curNum).val() );
		});
		
		var no = $('#num').val(); //var rowNum;
		var j = 0; rowNum = parseInt(no);
		
		$.each(refs,function(i) {
			if(j>0) {
				var controlForm = $('.controls .itemdivPrnt'),
				currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);
				rowNum++;
				newEntry.find($('input[name="account_name[]"]')).attr('id', 'craccount_' + rowNum);
				newEntry.find($('input[name="cr_account_id[]"]')).attr('id', 'craccountid_' + rowNum);
				newEntry.find($('input[name="cr_description[]"]')).attr('id', 'descr_' + rowNum);
				newEntry.find($('input[name="cr_reference[]"]')).attr('id', 'ref_' + rowNum);
				newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
				newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
				newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
				newEntry.find($('input[name="cr_amount[]"]')).attr('id', 'amount_' + rowNum);
				newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
				newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
				newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
				newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
				newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
				newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
				newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
				newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
				newEntry.find($('input[name="purchase_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
				
			}
			$('#ref_'+no).val( refs[i] );
			$('#amount_'+no).val(amounts[i])
			$('#invid_'+no).val( ids[i] );
			$('#acnttype_'+no).find('option').remove().end().append('<option value="Cr">Cr</option>');
			$('#actamt_'+no).val( actamt[i] );
			$('#invoiceid_'+no).val( invid[i] );
			j++;
		});
		getNetTotal();
	
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'debit');
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'credit');
	});
	
	//CHNG
	$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	//remove check box items.......
	$(document).on('click', '.tag-line', function(e) { 
		var remitem = $('#remitem').val(); var ids;
	   if($(this).is(":not(:checked)")) {
		  //alert(this.value);
		  ids = (remitem=='')?this.value:remitem+','+this.value;
		  $('#remitem').val(ids);
	   }
		
	});
	
	
	
	$(document).on('keyup', '#amount', function(e) {
		getNetTotal();
		
		// Revalidate the date when user change it
        $('#frmCreditNote').bootstrapValidator('revalidateField', 'debit');
		$('#frmCreditNote').bootstrapValidator('revalidateField', 'credit');
	});
	
	$(document).on('blur', '#on_amount', function(e) {
		var onamt = $('#on_amount').val();
		$("#credit").val( parseFloat(onamt).toFixed(2) );
		
		var crdt = parseFloat( $("#credit").val() );
		var dbt = parseFloat( $("#debit").val() );
		var difference = dbt - crdt;
		$("#difference").val(difference.toFixed(2));
	});
	
	$('#voucher_type').on('change', function(e){
		 var vchr = e.target.value; 
		//$.get("{{ url('account_master/get_account/') }}/" + vchr, function(data) {
			 //$('#dr_account').val(data.account_id+' - '+data.account_name);
			//$('#dr_account_id').val(data.id);
		//});
		
		var vchr_id = $('#voucher option:selected').val();
		
		$.get("{{ url('credit_note/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
			$('#voucher_no').val(data.voucher_no);
			if(data.id!=null && data.account_name!=null) {
				$('#dr_account').val(data.account_name);
				$('#dr_account_id').val(data.id);
			} else {
				$('#dr_account').val('');
				$('#dr_account_id').val('');
			}
		});
		
		if(vchr=='BANK' || vchr=='PDCR') {
			if( $("#chequeInput").is(":hidden") )
				$("#chequeInput").toggle();
		} else {
			if( $("#chequeInput").is(":visible") )
				$("#chequeInput").toggle();
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
	
	
});

function getNetTotal() {
	var amount = parseFloat( ($('#amount').val()=='') ? 0 : $('#amount').val() );
	var lineTotal = 0;
	$( '.line-amount' ).each(function() {
	  lineTotal = lineTotal + parseFloat( (this.value=='')?0:this.value );
	});
	var difference = amount - lineTotal;
	$("#debit").val(amount.toFixed(2));
	$("#credit").val(lineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var amount_fc = parseFloat($('#currency_rate').val()) * amount;
		$('#amount_fc').val(amount_fc.toFixed(2));
	}
	
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
	
}

</script>
@stop
