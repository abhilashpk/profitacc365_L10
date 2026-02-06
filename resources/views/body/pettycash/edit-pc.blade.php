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
                Petty Cash
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
                    <a href="#">Petty Cash</a>
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
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Petty Cash 
                            </h3>
                           
						   <div class="pull-right">
								@can('pc-print')
								 <a href="{{ url('pettycash/print/'.$prow->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPettycash" id="frmPettycash" action="{{ url('pettycash/update/'.$prow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pettycash_id" id="pettycash_id" value="{{ $prow->id }}">
                                <input type="hidden" name="voucher_type" value="PC" id="voucher_type">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher</label>
                                    <div class="col-sm-9">
                                        <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
										@foreach($vouchertype as $row)
											<option value="{{$row->id}}">{{$row->voucher_name}}</option>
										@endforeach
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PC. No.</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="voucher_no" value="{{$prow->voucher_no}}" readonly name="voucher_no">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PC. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" value="{{date('d-m-Y', strtotime($prow->voucher_date))}}" data-language='en' id="voucher_date" placeholder="Voucher Date"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Cash Account(Cr)</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="draccount_1" name="account_name[]" value="{{$datacr->master_name}}" readonly autocomplete="off" data-toggle="modal" data-target="#cash_modal">
                                        <input type="hidden" name="account_id[]" id="draccountid_1" value="{{$datacr->account_id}}">
                                        <input type="hidden" name="je_id[]" id="jeid_1" value="{{$datacr->id}}">
                                        <input type="hidden" name="reference[]" id="ref_1" value="{{$datacr->reference}}">
                                        <select id="acnttype_1" class="form-control select2 line-type" style="display:none;" name="account_type[]">
                                            <option value="Cr">Cr</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="description[]" value="{{$datacr->description}}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="line_amount[]" id="amount_1" value="{{$datacr->amount}}">
                                    </div>
                                </div>

								
								<br/>
								<fieldset>
								<legend><h4>Transactions</h4></legend>
										@php $i = 1; $num = count($perow); @endphp
										<input type="hidden" id="rowNum" value="{{$num+1}}">
										<input type="hidden" id="remitem" name="remove_item">
										<div class="itemdivPrnt">
										@foreach($perow as $item)
										@php $i++; @endphp
											<div class="itemdivChld">							
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_{{$i}}">
												
													@if($isdept) <!-- CASH MODE WITH DEPARTMENT -->
													<div class="col-sm-2"> <span class="small">Account Name</span>
													<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
													<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}"><!-- acntid_1 -->
													<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
													<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
													</div>
													
														<div class="col-xs-3" style="width:20%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" autocomplete="off" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:12%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" autocomplete="off" class="form-control ref-invoice" data-toggle="modal" data-target="#reference_modal">
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="">
														</div>
														<div class="col-xs-1" style="width:7%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr" <?php if($item->entry_type=='Dr') echo 'selected'; ?>>Dr</option>
																<option value="Cr" <?php if($item->entry_type=='Cr') echo 'selected'; ?>>Cr</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:12%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" autocomplete="off" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-sm-2" style="width:15%;"> 
														<span class="small">Job</span> 
																<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">

														</div>
														
														<div class="col-xs-3" style="width:13%;">
															<span class="small">Department</span> 
															<select id="dept_{{$i}}" class="form-control select2 line-dept" style="width:100%" name="department[]">
																<option value="">Department...</option>
																@foreach($departments as $department)
																<option value="{{ $department->id }}" <?php if($item->department_id==$department->id) echo 'selected';?>>{{ $department->name }}</option>
																@endforeach
															</select>
														</div>
														
														<div class="col-xs-1 abc" style="width:3%;"><br/>
															<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item">
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
															 <?php if($prow->group_id!=1) { ?> 
																<button type="button" class="btn-success btn-add-item" >
																	<i class="fa fa-fw fa-plus-square"></i>
																</button>
															<?php } ?>
														</div>
														
													@else  <!-- CASH MODE W/O DEPARTMENT -->
													<div class="col-sm-2"> <span class="small">Account Name</span>
													<input type="text" id="draccount_{{$i}}" value="{{$item->master_name}}" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
													<input type="hidden" name="account_id[]" value="{{$item->account_id}}" id="draccountid_{{$i}}"><!-- acntid_1 -->
													<input type="hidden" name="group_id[]" value="{{$item->category}}" id="groupid_{{$i}}">
													<input type="hidden" name="je_id[]" id="jeid_{{$i}}" value="{{$item->id}}">
													</div>
													
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_{{$i}}" autocomplete="off" value="{{$item->description}}" name="description[]" class="form-control">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_{{$i}}" class="refdata">
															<input type="text" id="ref_{{$i}}" name="reference[]" value="{{$item->reference}}" autocomplete="off" class="form-control ref-invoice" data-toggle="modal" data-target="#reference_modal">
															</div>
															<input type="hidden" name="inv_id[]" id="invid_{{$i}}" value="">
															<input type="hidden" name="actual_amount[]" id="actamt_{{$i}}" value="">
														</div>
														<div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_{{$i}}" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr" <?php if($item->entry_type=='Dr') echo 'selected'; ?>>Dr</option>
																<option value="Cr" <?php if($item->entry_type=='Cr') echo 'selected'; ?>>Cr</option>
															</select>
														</div>
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_{{$i}}" autocomplete="off" value="{{$item->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
														<div class="col-sm-2" style="width:17%;"> 
														<span class="small">Job</span> 
																<input type="hidden" name="job_id[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->code}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">

														</div>
													
														<div class="col-xs-1 abc" style="width:3%;"><br/>
															<button type="button" data-id="rem_{{$i}}" class="btn-danger btn-remove-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
															 
														</div>
														<!-- END CASH MODE -->
														
														<div class="form-group" style="display:none;">
															<div class="col-xs-15">
															
																<div class="col-sm-2"> </div>
																
																<input type="hidden" name="department[]" id="dept_{{$i}}">
																
																<div id="chqdtl_{{$i}}" class="divchq" <?php if($item->category!='PDCR' && $item->category!='PDCI') echo 'style="display: none;"'; ?>>
																
																<div class="col-xs-2">
																	<span class="small">Bank</span> 
																	<select id="bankid_{{$i}}" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
																	<option value="">Select Bank...</option>
																		@foreach($banks as $bank)
																		<option value="{{$bank['id']}}" <?php if($item->bank_id==$bank['id']) echo 'selected';?>>{{$bank['code'].' - '.$bank['name']}}</option>
																		@endforeach
																	</select>
																</div>
																
																<div class="col-sm-2"> 
																	<span class="small">Cheque No</span><input type="text" autocomplete="off" id="chkno_{{$i}}" value="{{$item->cheque_no}}" name="cheque_no[]" class="form-control" >
																	<input type="hidden" id="oldchkno_{{$i}}" value="{{$item->cheque_no}}" name="oldcheque_no[]">
																</div>
																
																<div class="col-xs-2">
																	<span class="small">Cheque Date</span> <input type="text" autocomplete="off" id="chkdate_{{$i}}" value="<?php echo ($item->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($item->cheque_date));?>" name="cheque_date[]" class="form-control chqdate" data-language='en'>
																</div>
																
																<div class="col-xs-2">
																	<input type="hidden" name="partyac_id[]" id="partyac_{{$i}}" value="{{$item->party_account_id}}">
																	<span class="small">Party Name</span> <input type="text" autocomplete="off" id="party_{{$i}}" name="party_name[]" value="{{$item->party_name}}" class="form-control" data-toggle="modal" data-target="#paccount_modal">
																</div>
																
																</div>
															</div>	
														</div>
														@endif
												
												</div>
											</div>
										@endforeach
										</div>
								</fieldset>
								
								<hr/>
                                <div >
                                    <button type="button" class="btn-info btn-sm more-info btn-add-item" data-type="Dr"><i class="fa fa-fw fa-plus-square"></i> Add Dr</button>
                                </div>

                                <hr/>

								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly value="{{$prow->debit}}" placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00" value="{{$prow->credit}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00" value="{{number_format($prow->difference)}}">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submit">Submit</button>
                                        <a href="{{ url('pettycash') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('pettycash') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
                            </form>
							
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
								
								
								<div id="reference_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog" style="width:65%;">
										<div class="modal-content">
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
	
//$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy'} );

"use strict";

$(document).ready(function () {
    
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
		
    $('#frmPettycash').bootstrapValidator({
        fields: {
            voucher_type: {
                validators: {
                    notEmpty: {
                        message: 'The voucher type is required and cannot be empty!'
                    }
                }
            },
			voucher_no: {
                validators: {
                    notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
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
			"account_name[]": {
                validators: {
                    notEmpty: {
                        message: 'The account name is required and cannot be empty!'
                    }
                }
            },
			"description[]": {
                validators: {
                    notEmpty: {
                        message: 'The description is required and cannot be empty!'
                    }
                }
            },
			"line_amount[]": {
                validators: {
                    notEmpty: {
                        message: 'The amount is required and cannot be empty!'
                    }
                }
            },
			"cheque_no[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque no is required and cannot be empty!'
                    }
                }
            },
			"cheque_date[]": {
                validators: {
                    notEmpty: {
                        message: 'The cheque date is required and cannot be empty!'
                    }
                }
            },
			"bank_id[]": {
                validators: {
                    notEmpty: {
                        message: 'The bank is required and cannot be empty!'
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
        $('#frmPettycash').data('bootstrapValidator').resetForm();
    });
	
	$("#chequeInput").toggle();
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
	
	
	$('#voucher_type').on('change', function(e){
		$('#voucher_no').val('');
		var vchr_id = e.target.value; 
		$.get("{{ url('pettycash/getvouchertype/') }}/" + vchr_id, function(data) { 
			$('#voucher').empty();
			$('#voucher').append('<option value="">Select Voucher...</option>');
			$.each(data, function(value, display){
				 $('#voucher').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
		
	});
	
	$('#voucher').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('pettycash/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			$('#voucher_no').val(data);
		});
		
	});
	
});

$(function(){
	var rowNum = $('#rowNum').val();
  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  //$('#chqdtl_1').toggle();
  
  $(document).on('click', '.btn-add-item', function(e)  { 
    rowNum++; 
		e.preventDefault();
        var controlForm; var newEntry;
        var currentEntry = $('.itemdivChld:first');

            if($(this).attr("data-type") == 'Dr' || $(this).attr("data-type") == 'Cr') {
                if(currentEntry.length==1) {
                    controlForm = $('.controls .itemdivPrnt'),
                    currentEntry = $('.itemdivChld:first'); //console.log(currentEntry.length);
                    newEntry = $(currentEntry.clone()).appendTo(controlForm);

                } else {

                    controlForm = $('.controls .itemdivPrnt'),
                    currentEntry = $('.itemdivChld-1st:first'); //console.log(currentEntry.length);
                    newEntry = $(currentEntry.clone()).appendTo(controlForm); 
                }

                newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
                newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
                newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
                newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
                newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
                newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
                newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
                newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
                newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
                newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
                newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
                newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
                newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum);
                newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum);
                newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
                newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
                newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
                newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
                newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum);
                newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum);
                newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
                newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
			    newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
                newEntry.find($('input[name="je_id[]"]')).attr('id', 'jeid_' + rowNum);

                $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="'+$(this).attr("data-type")+'">'+$(this).attr("data-type")+'</option>');
                $('#chqdtl_'+rowNum).html('');

                $("#amount_"+rowNum).val(parseFloat($("#amount_"+rowNum).val()));
                $('#draccount_'+rowNum).val(''); $('#descr_'+rowNum).val('');
                $('#draccountid_'+rowNum).val(''); $('#ref_'+rowNum).val('');
                $('#chkno_'+rowNum).val(''); $('#amount_'+rowNum).val('');
                $('#chkdate_'+rowNum).val(''); $('#jeid_'+rowNum).val('');
                $('#party_'+rowNum).val('');
                $('#partyac_'+rowNum).val('');
                $('#groupid_'+rowNum).val('');

            } 
        
        $('#frmPettyCash').bootstrapValidator('addField',"account_name[]");
        
        controlForm.find('.btn-remove-item').show()

}).on('click', '.btn-remove-item', function(e)
{ 
    var res = $(this).attr('data-id').split('_');
    var curNum = res[1]; var ids;
    
    var remitem = $('#remitem').val();
    ids = (remitem=='')?$('#jeid_'+curNum).val():remitem+','+$('#jeid_'+curNum).val();
    $('#remitem').val(ids);

    $(this).parents('.itemdivChld:first').remove();
    
    if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
    
    getNetTotal();
	$('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
	$('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');

    e.preventDefault();
    return false;
});
	
	$('.chqdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',
		autoClose: 1
	});
	
	$(document).on('keyup', '.jvline-amount', function(e) { 
        //$('#amount_1').val(this.value);
        getNetTotal();
        // Revalidate the date when user change it
        $('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
        $('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');
    });
	
	$(document).on('change', '.line-type', function(e) {
		getNetTotal();
	});
	
	//new change............
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="account_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum+'?type=notin', function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	//new change.................
	$(document).on('click', '#account_data .custRow', function(e) { 
    var num = $('#account_data #num').val(); var vatasgn = $(this).attr("data-vatassign");
    $('.itemdivPrnt #draccount_'+num).val( $(this).attr("data-name") );
    $('.itemdivPrnt #draccountid_'+num).val( $(this).attr("data-id") );
    $('.itemdivPrnt #groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
    $('.itemdivPrnt #vatamt_'+num).val( $(this).attr("data-vat") );

    $('#frmPettyCash').bootstrapValidator('revalidateField', 'account_name[]');
        
});
	
	$(document).on('blur', '#voucher_date', function(e) { 
        // Revalidate the date when user change it
        //$('#frmPettycash').bootstrapValidator('revalidateField', 'voucher_date');
		$('#frmPettycash').bootstrapValidator('revalidateField', 'voucher_no');
	});
	
		
	$(document).on('blur', '.acname', function(e) { 
        // Revalidate the date when user change it
        $('#frmPettycash').bootstrapValidator('revalidateField', 'account_name[]');
	});
	
	$(document).on('change', '.submit', function(e) { 
		$('#frmPettycash').bootstrapValidator('revalidateField', 'debit');
		$('#frmPettycash').bootstrapValidator('revalidateField', 'credit');
	});

	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		var chqno = this.value;
		var res = this.id.split('_');
		var curNum = res[1];
		var oldcqno = $('#oldchkno_'+curNum).val();
		var bank = $('#bankid_'+curNum+' option:selected').val();
		if(oldcqno != chqno) {
			$.ajax({
				url: "{{ url('account_master/check_chequeno/') }}",
				type: 'get',
				data: 'chqno='+chqno+'&bank_id='+bank,
				success: function(data) { 
					if(data=='') {
						alert('Cheque no is duplicate!');
						$('#chkno_'+curNum).val('');
					}
				}
			})
		}
	});
	
	$(document).on('click', '.add-invoice', function(e)  { 
	
		var refs = []; var amounts = []; var type = []; var ids = []; var actamt = []; var btype = [];
		$("input[name='tag[]']:checked").each(function() { 
			var res = this.id.split('_');
			var curNum = res[1];
			ids.push($(this).val());
			refs.push( $('#refid_'+curNum).val() );
			amounts.push( $('#lineamnt_'+curNum).val() );
			type.push( $('#trtype_'+curNum).val() );
			actamt.push( $('#hidamt_'+curNum).val() );
			btype.push( $('#billtype_'+curNum).val() );
		});
		
		var no = $('#num').val(); //var rowNum;
		var j = 0; rowNum = parseInt(no);
		
		$.each(refs,function(i) {
			if(j>0) {
				var controlForm = $('.controls .itemdivPrnt'),
				currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);
				rowNum++;
				newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
				newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
				newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
				newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
				newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
				newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
				newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
				newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
				newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
				//newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
				newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum);
		     	newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
				newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
				newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
				newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
				newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
				newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
				newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
				
			}
			$('#ref_'+no).val( refs[i] );
			$('#amount_'+no).val(amounts[i])
			$('#invid_'+no).val( ids[i] );
			$('#acnttype_'+no).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
			$('#actamt_'+no).val( actamt[i] );
			j++;
		});
		getNetTotal();
	
	});
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
    
    
    	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="jobcod[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		console.log(curNum);
		$('#jobData').load(joburl+'/'+curNum, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#job_modal .jobRow', function(e) {
		var num =$('#num').val();
		$('#jobcod_'+num).val($(this).attr("data-cod"));
		$('#jobid_'+num).val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	$(document).on('click', '.ref-invoice', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		if( $('#groupid_'+curNum).val()=='CUSTOMER') { //customer type.............
		   
		   var reid = $('#jeid_'+curNum).val();
		   var jvid = $('#pettycash_id').val() 
		   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   if((reid!='') && (this.value!='')) {
			   $('#invoiceData').load(url+'/'+curNum+'/PC/'+jvid, function(result){ 
				  $('#myModal').modal({show:true});
			   });
		   } else {
			   $('#invoiceData').load(url+'/'+curNum, function(result){ 
					$('#myModal').modal({show:true});
				});
		   }

			
		} else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
		   
		   var reid = $('#jeid_'+curNum).val();
		   var jvid = $('#pettycash_id').val() 
		   var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
		   if((reid!='') && (this.value!='')) {
			   $('#invoiceData').load(url+'/'+curNum+'/PC/'+jvid, function(result){ 
				  $('#myModal').modal({show:true});
			   });
		   } else {
			    $('#invoiceData').load(url+'/'+curNum, function(result){ 
					$('#myModal').modal({show:true});
				});
		   }

		}
	});
	
});

$(document).on('keyup', '#amount_1', function(e) {
    var cramt = parseFloat($('#amount_1').val());
    $("#credit").val(cramt.toFixed(2));
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');
});

function getNetTotal() {
	var drLineTotal = 0; var crLineTotal = 0;
	$( '.jvline-amount' ).each(function() {
		var res = this.id.split('_');
		var curNum = res[1];
		if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' ) {
			drLineTotal = drLineTotal + parseFloat( ($(this).val()=='')?0:$(this).val() ); 
		} /*else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' ) {
			crLineTotal = crLineTotal + parseFloat( (this.value=='')?0:this.value );
		}*/
		
	});
	var difference = drLineTotal - drLineTotal;
	$("#debit").val(drLineTotal.toFixed(2));
	//$("#credit").val(drLineTotal.toFixed(2));
    //$("#amount_1").val(drLineTotal.toFixed(2));
	$("#difference").val(difference.toFixed(2));
	
	if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
		var amount_fc = parseFloat($('#currency_rate').val()) * amount;
		$('#amount_fc').val(amount_fc.toFixed(2));
	}
	
}
	
var popup;
function getDrAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('account_master/get_all_account/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
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
