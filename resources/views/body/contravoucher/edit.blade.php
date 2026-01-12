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
            <h1>
                Contra Voucher
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
                    <a href="#">Contra Voucher</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        @if(Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
        @endif
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Contra Voucher 
                            </h3>
                           <div class="pull-right">
							
								 <a href="{{ url('contra_voucher/printgrp/'.$row->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								
							</div>
                        </div>
                        <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmContraVchr" id="frmContraVchr" action="{{ url('contra_voucher/update/'.$row->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="voucher_type" value="27">
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">CV. No.</label>
                                    <div class="col-sm-9">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$row->voucher_no}}">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">CV. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off"  id="voucher_date" value="{{date('d-m-Y',strtotime($row->voucher_date))}}"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Type</label>
                                    <div class="col-sm-9">
                                       <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											@if($row->voucher_type==0)<option value="0">Withdraw</option>@endif
                                            @if($row->voucher_type==1)<option value="1">Deposit</option>@endif
                                        </select>
                                    </div>
                                </div>
                                
                                <fieldset>
                                <legend><h4>Transaction</h4></legend>
                                    <div class="itemdivPrnt">
											<div class="itemdivChld">
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_1" name="account_name[]" placeholder="Select Account" value="{{$items[0]->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#bank_modal">
														<input type="hidden" name="account_id[]" id="draccountid_1" value="{{$items[0]->account_id}}">
                                                        <input type="hidden" name="old_account_id[]" value="{{$items[0]->account_id}}">
                                                        <input type="hidden" name="row_id[]" value="{{$items[0]->id}}">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_1" autocomplete="off" value="{{$items[0]->description}}" name="description[]" class="form-control" placeholder="Description">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_1" class="refdata">
															<input type="text" id="ref_1" name="reference[]" autocomplete="off" value="{{$items[0]->reference}}" class="form-control" placeholder="Reference No">
															</div>
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_1" autocomplete="off" value="{{$items[0]->amount}}" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_1" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$items[0]->tr_type}}">{{$items[0]->tr_type}}</option>
															</select>
														</div>

														<div class="col-sm-2" style="width:17%;display:none;"> 
															<span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_1" >
														    <input type="text" id="jobcod_1" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>

												</div>
												
											</div>

                                            <div class="itemdivChld">
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_2">
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_2" name="account_name[]" placeholder="Select Account" value="{{$items[1]->master_name}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#cash_modal">
														<input type="hidden" name="account_id[]" id="draccountid_2" value="{{$items[1]->account_id}}">
                                                        <input type="hidden" name="old_account_id[]" value="{{$items[1]->account_id}}">
														<input type="hidden" name="row_id[]" value="{{$items[1]->id}}">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_2" autocomplete="off" name="description[]" class="form-control" value="{{$items[1]->description}}" placeholder="Description">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_2" class="refdata">
															<input type="text" id="ref_2" name="reference[]" autocomplete="off" class="form-control" value="{{$items[1]->reference}}" placeholder="Reference No">
															</div>
															<input type="hidden" name="actual_amount[]" id="actamt_2" value="{{$items[1]->amount}}">
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_2" autocomplete="off" step="any" name="line_amount[]" value="{{$items[1]->amount}}" readonly class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_2" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="{{$items[1]->tr_type}}">{{$items[1]->tr_type}}</option>
															</select>
														</div>

														<div class="col-sm-2" style="width:17%;display:none;"> 
															<span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_2" >
														    <input type="text" id="jobcod_2" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
												</div>
												
											</div>
										</div>
                                </fieldset>
                                
                                <hr/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" value="{{$row->amount}}" readonly placeholder="0.00">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" value="{{$row->amount}}" readonly placeholder="0.00">
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
                                        <button type="submit" class="btn btn-success submitBtn">Submit</button>
                                        <a href="{{ url('contra_voucher') }}" class="btn btn-danger">Cancel</a>
                                        <a href="{{ url('contra_voucher') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="bank_modal" class="modal fade animated" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select Account</h4>
                        </div>
                        <div class="modal-body" id="bank_data">
                            <table class="table horizontal_table table-striped">
                                <thead>
                                <tr>
                                    <th>Account ID</th>
                                    <th>Account Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bank as $account)
                                <tr>
                                    <td><a href="" class="bankRow" data-id="{{$account->id}}" data-name="{{$account->master_name}}" data-dismiss="modal">{{$account->account_id}}</a></td>
                                    <td><a href="" class="bankRow" data-id="{{$account->id}}" data-name="{{$account->master_name}}" data-dismiss="modal">{{$account->master_name}}</a></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div> 

            <div id="cash_modal" class="modal fade animated" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select Account</h4>
                        </div>
                        <div class="modal-body" id="cash_data">
                            <table class="table horizontal_table table-striped">
                                <thead>
                                <tr>
                                    <th>Account ID</th>
                                    <th>Account Name</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cash as $account)
                                <tr>
                                    <td><a href="" class="cashRow" data-id="{{$account->id}}" data-name="{{$account->master_name}}" data-dismiss="modal">{{$account->account_id}}</a></td>
                                    <td><a href="" class="cashRow" data-id="{{$account->id}}" data-name="{{$account->master_name}}" data-dismiss="modal">{{$account->master_name}}</a></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<script>
"use strict";

$(document).ready(function () {
    /*$('#frmArea').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The area code is required and cannot be empty!'
                    },
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('code').val()
                            };
                        },
                        message: 'The area code is not available'
                    }
                }
            },
			name: {
                validators: {
                    notEmpty: {
                        message: 'The area name is required and cannot be empty!'
                    },
					remote: {
                        url: urlname,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('name').val()
                            };
                        },
                        message: 'The area name is not available'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmArea').data('bootstrapValidator').resetForm();
    });*/

    $(document).on('change','#voucher_type', function() {
        if(this.value==0) {
            $('#acnttype_1').find('option').remove().end().append('<option value="Dr">Dr</option>');
            $('#acnttype_2').find('option').remove().end().append('<option value="Cr">Cr</option>');
        } else {
            $('#acnttype_1').find('option').remove().end().append('<option value="Cr">Cr</option>');
            $('#acnttype_2').find('option').remove().end().append('<option value="Dr">Dr</option>');
        }
            
    });

    $(document).on('click', '.bankRow', function(e) { 
		$('#draccount_1').val( $(this).attr("data-name") );
		$('#draccountid_1').val( $(this).attr("data-id") );
    });

    $(document).on('click', '.cashRow', function(e) { 
		$('#draccount_2').val( $(this).attr("data-name") );
		$('#draccountid_2').val( $(this).attr("data-id") );
    });

    $(document).on('keyup', '.jvline-amount', function(e) { 
        $('#amount_2').val(this.value);
        getNetTotal();
        // Revalidate the date when user change it
        $('#frmContraVchr').bootstrapValidator('revalidateField', 'debit');
        $('#frmContraVchr').bootstrapValidator('revalidateField', 'credit');
    });

});

function getNetTotal() {
    var drLineTotal = 0; var crLineTotal = 0;
    $( '.jvline-amount' ).each(function() {
        var res = this.id.split('_');
        var curNum = res[1];
        if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' ) {
            drLineTotal = drLineTotal + parseFloat( ($(this).val()=='')?0:$(this).val() ); 
        } else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' ) {
            crLineTotal = crLineTotal + parseFloat( (this.value=='')?0:this.value );
        }
        
    });
    var difference = drLineTotal - crLineTotal;
    $("#debit").val(drLineTotal.toFixed(2));
    $("#credit").val(crLineTotal.toFixed(2));
    $("#difference").val(difference.toFixed(2));
    
    if($("#is_fc").prop('checked') == true && $('#currency_rate').val()!=''){
        var amount_fc = parseFloat($('#currency_rate').val()) * amount;
        $('#amount_fc').val(amount_fc.toFixed(2));
    }
    
}
</script>
@stop
