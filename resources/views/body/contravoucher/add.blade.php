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
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Contra Voucher 
                            </h3>
                           <div class="pull-right">
							<?php if($printid) { ?>
							
								 <a href="{{ url('contra_voucher/printgrp/'.$printid->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								
							<?php } ?>
							</div>
                        </div>
                        <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmContraVchr" id="frmContraVchr" action="{{ url('contra_voucher/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="voucher_type" value="27">
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">CV. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{$vchrdata->voucher_no}}">
                                    <div class="col-sm-9">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$vchrdata->voucher_no}}">
										<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">CV. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off"  id="voucher_date" <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?> placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher Type</label>
                                    <div class="col-sm-9">
                                       <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="0">Withdraw</option>
                                            <option value="1">Deposit</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <fieldset>
                                <legend><h4>Transaction</h4></legend>
                                    <div class="itemdivPrnt">
											<div class="itemdivChld">
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_1" name="account_name[]" placeholder="Select Account" value="{{$vchrdata->bank}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#bank_modal">
														<input type="hidden" name="account_id[]" id="draccountid_1" value="{{$vchrdata->bank_account_id}}">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_1" autocomplete="off" name="description[]" class="form-control cv-desc" placeholder="Description">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_1" class="refdata">
															<input type="text" id="ref_1" name="reference[]" autocomplete="off" class="form-control cv-ref" placeholder="Reference No">
															</div>
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_1" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_1" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Cr">Cr</option>
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
														<input type="text" id="draccount_2" name="account_name[]" placeholder="Select Account" value="{{$vchrdata->cash}}" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#cash_modal">
														<input type="hidden" name="account_id[]" id="draccountid_2" value="{{$vchrdata->cash_account_id}}">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_2" autocomplete="off" name="description[]" class="form-control" placeholder="Description">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_2" class="refdata">
															<input type="text" id="ref_2" name="reference[]" autocomplete="off" class="form-control" placeholder="Reference No">
															</div>
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_2" autocomplete="off" step="any" name="line_amount[]" readonly class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_2" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr">Dr</option>
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
                            <table class="table horizontal_table table-striped" id="table-bank">
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
                            <table class="table horizontal_table table-striped" id="table-cash">
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

        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
    
    var dtInstance = $("#table-bank").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null],
	});
	
	var dtInstance = $("#table-cash").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null],
	});
	
	//CHNG 
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	var urlcv = "{{ url('contra_voucher/checkvchrno/') }}"; 
    $('#frmContraVchr').bootstrapValidator({
        fields: {
            voucher_no: {
                validators: {
                    notEmpty: {
                        message: 'Voucher no required and cannot be empty!'
                    },
					remote: {
                        url: urlcv,
                        data: function(validator) {
                            return {
                                voucher_no: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'Voucher no is not available!'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmContraVchr').data('bootstrapValidator').resetForm();
    });

    $(document).on('change','#voucher_type', function() {
        if(this.value==1) {
            $('#acnttype_1').find('option').remove().end().append('<option value="Dr">Dr</option>');
            $('#acnttype_2').find('option').remove().end().append('<option value="Cr">Cr</option>');
        } else {
            $('#acnttype_1').find('option').remove().end().append('<option value="Cr">Cr</option>');
            $('#acnttype_2').find('option').remove().end().append('<option value="Dr">Dr</option>');
        }
            
    });
    
    $(document).on('keyup', '.cv-desc', function(e) { 
        $('#descr_2').val(this.value);
    });
    
    $(document).on('keyup', '.cv-ref', function(e) { 
        $('#ref_2').val(this.value);
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
        //$('#frmContraVchr').bootstrapValidator('revalidateField', 'debit');
        //$('#frmContraVchr').bootstrapValidator('revalidateField', 'credit');
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
