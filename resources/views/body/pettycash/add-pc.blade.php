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
                    Add New
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
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
				<?php if(sizeof($vouchers)==0) { ?>
				<div class="alert alert-warning">
					<p>
						Payment voucher is not found! Please create a voucher in Account Settings.
					</p>
				</div>
				<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Petty Cash 
                            </h3>
                           
						   <div class="pull-right">
							<?php if($printid) { ?>
								@can('pc-print')
								 <a href="{{ url('pettycash/print/'.$printid->id.'/'.$prints[0]->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
							<?php } ?>
							</div>
                        </div>

                        <div class="panel-body">
                          <div class="controls"> 

                            <div style="display:none;">
                                <div class="itemdivChld">
                                    <div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
                                        <div class="col-sm-2"> <span class="small">Account Name</span>
                                            <input type="text" id="draccount_2" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Select Account">
                                            <input type="hidden" name="account_id[]" id="draccountid_2">
                                            <input type="hidden" name="group_id[]" id="groupid_2">
                                        </div>
                                            <div class="col-xs-3" style="width:25%;">
                                                <span class="small">Description</span> <input type="text" id="descr_2" autocomplete="off" name="description[]" class="form-control" placeholder="Description">
                                            </div>
                                            <div class="col-xs-2" style="width:15%;">
                                                <span class="small">Reference</span> 
                                                <div id="refdata_1" class="refdata">
                                                <input type="text" id="ref_2" name="reference[]" autocomplete="off" class="form-control" placeholder="Reference No">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xs-2" style="width:13%;">
                                                <span class="small">Amount</span> <input type="number" id="amount_2" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
                                            </div>
                                            
                                            <div class="col-xs-1" style="width:8%;">
                                                <span class="small">Type</span> 
                                                <select id="acnttype_2" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
                                                    <option value="Dr">Dr</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-2" style="width:17%;"> 
                                                <span class="small">Job</span> 
                                                <input type="hidden" name="job_id[]" id="jobid_2" >
                                                <input type="text" id="jobcod_2" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                            </div>
                                            @if($isdept)
                                            <div class="col-xs-3" style="width:13%;">
                                                <span class="small">Department</span> 
                                                <select id="dept_2" class="form-control select2 line-dept" style="width:100%" name="department[]">
                                                    <option value="">Department...</option>
                                                    @foreach($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            <div class="col-xs-1 abc" style="width:3%;"><br/>
                                                <button type="button" class="btn-danger btn-remove-item" >
                                                    <i class="fa fa-fw fa-minus-square"></i>
                                                    </button>
                                            </div>

                                    </div>
                                    
                                </div>
                            </div>

                            <form class="form-horizontal" role="form" method="POST" name="frmPettyCash" id="frmPettyCash" action="{{ url('pettycash/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="voucher_type" value="PC" id="voucher_type">
                                <input type="hidden" name="isquick" value="1">
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Voucher</label>
                                    <div class="col-sm-9">
                                       <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['vid'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PC. No.</label>
									<input type="hidden" name="curno" id="curno" value="{{$vchrdata['voucher_no']}}">
                                    <div class="col-sm-9">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" placeholder="{{$vchrdata['voucher_no']}}">
										<span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">PC. Date</label>
                                    <div class="col-sm-9">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off"  id="voucher_date" <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?> placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
                                <input type="hidden" name="chktype" id="chktype">

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Cash Account(Cr)</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="draccount_1" name="account_name[]" value="{{$cashac->master_name}}" readonly autocomplete="off" data-toggle="modal" data-target="#cash_modal">
                                        <input type="hidden" name="account_id[]" id="draccountid_1" value="{{$cashac->id}}">
                                        <select id="acnttype_1" class="form-control select2 line-type" style="display:none;" name="account_type[]">
                                            <option value="Cr">Cr</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="description[]">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="line_amount[]" id="amount_1">
                                    </div>
                                </div>

                                <input type="hidden" class="form-control" name="reference[]">
                                <input type="hidden" class="form-control" name="job_id[]">

                                <fieldset>
                                <legend><h4>Transactions</h4></legend>
                                    <div class="itemdivPrnt">
                                            <div class="itemdivChld">
												<div class="form-group classtrn" style="margin-bottom:1px;" id="trns_2">
													<div class="col-sm-2"> <span class="small">Account Name</span>
														<input type="text" id="draccount_2" name="account_name[]" placeholder="Select Account" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
														<input type="hidden" name="account_id[]" id="draccountid_2">
														<input type="hidden" name="group_id[]" id="groupid_2">
														<input type="hidden" name="vatamt[]" id="vatamt_2">
														
														<input type="hidden" id="invoiceid_2" name="sales_invoice_id[]">
														<input type="hidden" name="bill_type[]" id="biltyp_2">
													</div>
														<div class="col-xs-3" style="width:25%;">
															<span class="small">Description</span> <input type="text" id="descr_2" autocomplete="off" name="description[]" class="form-control" placeholder="Description">
														</div>
														<div class="col-xs-2" style="width:15%;">
															<span class="small">Reference</span> 
															<div id="refdata_2" class="refdata">
															<input type="text" id="ref_2" name="reference[]" autocomplete="off" class="form-control" placeholder="Reference No">
															</div>
															<input type="hidden" name="inv_id[]" id="invid_2">
															<input type="hidden" name="actual_amount[]" id="actamt_2">
														</div>
														
														<div class="col-xs-2" style="width:13%;">
															<span class="small">Amount</span> <input type="number" id="amount_2" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
														</div>
														
                                                        <div class="col-xs-1" style="width:8%;">
															<span class="small">Type</span> 
															<select id="acnttype_2" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
																<option value="Dr">Dr</option>
															</select>
														</div>

														<div class="col-sm-2" style="width:17%;"> 
															<span class="small">Job</span> 
                                                            <input type="hidden" name="job_id[]" id="jobid_2" >
														    <input type="text" id="jobcod_2" autocomplete="off" name="jobcod[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
														</div>
														@if($isdept)
														<div class="col-xs-3" style="width:13%;">
															<span class="small">Department</span> 
															<select id="dept_2" class="form-control select2 line-dept" style="width:100%" name="department[]">
																<option value="">Department...</option>
																@foreach($departments as $department)
																<option value="{{ $department->id }}">{{ $department->name }}</option>
																@endforeach
															</select>
														</div>
														@endif
														<div class="col-xs-1 abc" style="width:3%;"><br/>
															<button type="button" class="btn-danger btn-remove-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
														</div>
                                                        
												</div>
												
											</div>
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
                                        <!--<button type="button" class="btn btn-primary submitBtn">Save Draft</button>-->
                                        <button type="submit" class="btn btn-success submitBtn">Submit</button>
                                        <a href="{{ url('pettycash') }}" class="btn btn-danger">Cancel</a>
                                        <a href="{{ url('pettycash') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
                            </form>
                          </div>
                        </div>
                    </div>
				<?php } ?>
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

            <div id="account_modal" class="modal fade animated" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Select Account</h4>
                        </div>
                        <div class="modal-body" id="account_data">
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

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
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
var rowNum=2;
$(document).on('click', '.btn-add-item', function(e)  { 
    rowNum++; 
		e.preventDefault();
        var controlForm;
        currentEntry = $('.itemdivChld:first');

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

                $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="'+$(this).attr("data-type")+'">'+$(this).attr("data-type")+'</option>');
                $('#chqdtl_'+rowNum).html('');

                $("#amount_"+rowNum).val(parseFloat($("#amount_"+rowNum).val()));
                $('#draccount_'+rowNum).val(''); $('#descr_'+rowNum).val('');
                $('#draccountid_'+rowNum).val(''); $('#ref_'+rowNum).val('');
                $('#chkno_'+rowNum).val(''); $('#amount_'+rowNum).val('');
                $('#chkdate_'+rowNum).val('');
                $('#party_'+rowNum).val('');
                $('#partyac_'+rowNum).val('');
                $('#groupid_'+rowNum).val('');

            } 
        
        $('#frmPettyCash').bootstrapValidator('addField',"account_name[]");

}).on('click', '.btn-remove-item', function(e)
{ 
    $(this).parents('.itemdivChld:first').remove();
    
    getNetTotal();
	$('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
	$('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');

    e.preventDefault();
    return false;
});

$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});

$(document).ready(function () {
	//$('.btn-remove-item').hide(); 
	
	var urlvchr = "{{ url('pettycash/checkvchrno/') }}"; //CHNG
    $('#frmPettyCash').bootstrapValidator({
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
                    /*notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    },*/
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This Voucher No. is already exist!'
                    }
                }
            },
			/* voucher_date: {
                validators: {
                    notEmpty: {
                        message: 'The voucher date is required and cannot be empty!'
                    }
                }
            }, */
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
        $('#frmPettyCash').data('bootstrapValidator').resetForm();
    });

});   

$(document).on('click', '.cashRow', function(e) { 
    $('#draccount_1').val( $(this).attr("data-name") );
    $('#draccountid_1').val( $(this).attr("data-id") );
});

$(document).on('click','.datepicker--cell-day', function() {
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'cheque_date[]');
})

var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="account_name[]"]', function(e) {
    var res = this.id.split('_');
    var curNum = res[1]; 
    $('#account_data').load(acurl+'/'+curNum+'?type=notin', function(result){ //.modal-body item
        $('#myModal').modal({show:true}); $('.input-sm').focus();
    });
});

$(document).on('click', '#account_data .custRow', function(e) { 
    var num = $('#account_data #num').val(); var vatasgn = $(this).attr("data-vatassign");
    $('.itemdivPrnt #draccount_'+num).val( $(this).attr("data-name") );
    $('.itemdivPrnt #draccountid_'+num).val( $(this).attr("data-id") );
    $('.itemdivPrnt #groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
    $('.itemdivPrnt #vatamt_'+num).val( $(this).attr("data-vat") );

    $('#frmPettyCash').bootstrapValidator('revalidateField', 'account_name[]');
        
});

$(document).on('blur', '#voucher_no', function(e) {  
    if(parseInt($(this).val()) > parseInt($('#curno').val())) {
        alert('Voucher no is greater than current range!');
        $('#voucher_no').val('');
    }
});

$(document).on('keyup', '.jvline-amount', function(e) { 
    //$('#amount_1').val(this.value);
    getNetTotal();
    // Revalidate the date when user change it
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');
});

$(document).on('click', '.ref-invoice', function(e) {
    var res = this.id.split('_');
    var curNum = res[1]; 
    
    if( $('.itemdivPrnt #groupid_'+curNum).val()=='SUPPLIER' ) { //supplier type.........
        var url = "{{ url('account_enquiry/os_bills/') }}/"+$('.itemdivPrnt #draccountid_'+curNum).val();
        $('#invoiceData').load(url+'/'+curNum, function(result) {
            $('#myModal').modal({show:true});
        });
    } else if( $('.itemdivPrnt #groupid_'+curNum).val()=='VATIN' || $('#groupid_'+curNum).val()=='VATOUT') { //supplier type.........
        var url = "{{ url('account_enquiry/os_bills/') }}/"+$('.itemdivPrnt #draccountid_'+curNum).val();
        $('#invoiceData').load(url+'/'+curNum, function(result) {
            $('#myModal').modal({show:true});
        });
    }
});

var acurlall = "{{ url('account_master/get_account_all/') }}";  
$(document).on('click', 'input[name="party_name[]"]', function(e) {
    var res = this.id.split('_');
    var curNum = res[1]; 
    $('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
        $('#myModal').modal({show:true});
    });
});

$(document).on('click', '#paccount_data .custRow', function(e) { //paccount_data
    var num = $('#anum').val();
    $('#party_'+num).val( $(this).attr("data-name") );
    $('#partyac_'+num).val( $(this).attr("data-id") );
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'party_name[]');
    checkChequeNo(num);
});

$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
    var res = this.id.split('_');
    var curNum = res[1];

    checkChequeNo(curNum);
});

$(document).on('click', '.add-invoice', function(e)  { 
	
    var refs = []; var amounts = []; var type = []; var ids = []; var actamt = []; var invid = []; var btype = [];
    $("input[name='tag[]']:checked").each(function() { 
        var res = this.id.split('_');
        var curNum = res[1];
        ids.push($(this).val());
        refs.push( $('#refid_'+curNum).val() );
        amounts.push( $('#lineamnt_'+curNum).val() );
        type.push( $('#trtype_'+curNum).val() ); //actype_
        actamt.push( $('#hidamt_'+curNum).val() );
        invid.push( $('#sinvoiceid_'+curNum).val() );
        btype.push( $('#billtype_'+curNum).val() );
    });
    
    var no = $('#bnum').val(); //var rowNum;
    var j = 0; rowNum = parseInt(no);
    var drac = $('.itemdivPrnt #draccount_'+no).val();
    var dracid = $('.itemdivPrnt #draccountid_'+no).val();
    var rnum = $('#rowNum').val();
    console.log('r '+refs);
    $.each(refs,function(i) {
        if(j>0) {
            var controlForm; var newEntry;
            var currentEntry = $('.itemdivChld:first');
            if(currentEntry.length==1) {
                controlForm = $('.controls .itemdivPrnt'),
                currentEntry = $('.itemdivChld:first'); 
                newEntry = $(currentEntry.clone()).appendTo(controlForm);
            } else {

                controlForm = $('.controls .itemdivPrnt'),
                currentEntry = $('.itemdivChld-1st:first');
                newEntry = $(currentEntry.clone()).appendTo(controlForm); 
            }

            /*var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);*/
            rowNum++;
            rnum++; 
            newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum);
            newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum);
            newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum);
            newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum);
            newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
            newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum);
            newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
            newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum);
            newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum);
            newEntry.find($('.line-job')).attr('id', 'jobid_' + rowNum);
            newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
            newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum);
            newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum); 
            newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum);
            newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
            newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
            newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum);
            
        } 
        $('.itemdivPrnt #draccount_'+no).val(drac);
        $('.itemdivPrnt #draccountid_'+no).val(dracid);
        $('.itemdivPrnt #ref_'+no).val( refs[i] );
        $('.itemdivPrnt #amount_'+no).val(amounts[i])
        $('.itemdivPrnt #invid_'+no).val( ids[i] );
        $('.itemdivPrnt #acnttype_'+no).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
        $('.itemdivPrnt #actamt_'+no).val( actamt[i] );
        $('.itemdivPrnt #invoiceid_'+no).val( invid[i] );
        $('.itemdivPrnt #biltyp_'+no).val( btype[i] );
        j++; no++;
    });
    getNetTotal();

    $('#frmPettyCash').bootstrapValidator('revalidateField', 'debit');
    $('#frmPettyCash').bootstrapValidator('revalidateField', 'credit');
    $('#is_onaccount').val(0);
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
    var num =$('#jobData #num').val();
    $('.itemdivPrnt #jobcod_'+num).val($(this).attr("data-cod"));
    $('.itemdivPrnt #jobid_'+num).val($(this).attr("data-id"));
    e.preventDefault();
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




</script>
@stop
