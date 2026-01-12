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
        <h1>Receipt Voucher</h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-fw fa-retweet"></i> Transaction</a></li>
            <li><a href="">Vouchers Entry</a></li>
            <li><a href="#">Receipt Voucher</a></li>
            <li class="active">Add New</li>
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

    <!--section ends-->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
            <?php if(sizeof($vouchers)==0) { ?>
                <div class="alert alert-warning">
                    <p>
                        Payment Voucher voucher is not found! Please create a voucher in Account Settings.
                    </p>
                </div>
            <?php } else { ?>

                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-crosshairs"></i> New Receipt Voucher
                        </h3>

                        <div class="pull-right">
                            @permission('jv-print')
                            <form class="form-horizontal" role="form" method="GET" target="_blank" name="frmItem" id="frmItem" action="{{ url('journal/getvoucherprint') }}">
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <select id="voucher_typeprint" class="form-control select2" style="width:100%" name="voucher_typeprint" required>
                                            <option value="9">RV - Receipt Voucher</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control pull-right" name="voucherprnt_no" placeholder="voucher no" autocomplete="off" id="voucherprnt_no" />
                                    </div>

                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-info btn-sm">
                                            <span class="btn-label"><i class="fa fa-fw fa-print"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endpermission
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="controls">
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('receipt_voucher/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="from_jv" value="1">
                                <input type="hidden" name="status" id="status" value="1">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
                                            <option value="9">RV - Receipt Voucher</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
                                            @foreach($vouchers as $voucher)
                                                <option value="{{$voucher->id}}">{{$voucher->voucher_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">RV. No.</label>
                                    <input type="hidden" name="curno" id="curno">
                                    <input type="hidden" value="{{$voucher->prefix}}" name="prefix">
                                    <input type="hidden" value="{{$vchrdata['vno']}}" name="vno">
                                    <input type="hidden" value="{{$voucher->is_prefix}}" name="is_prefix">
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="voucher_no" value="{{$vchrdata['voucher_no']}}" readonly name="voucher_no">
                                            <span class="input-group-addon"><i class="fa fa-fw fa-edit"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">RV. Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" autocomplete="off" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                    <input type="hidden" name="chktype" id="chktype">
                                    <input type="hidden" name="is_onaccount" id="is_onaccount" value="1">
                                </div>

                                <br/>
                                <fieldset>
                                    <legend><h5>Transactions</h5></legend>
                                    <div class="itemdivPrnt">
                                        <div class="itemdivChld">
                                            <div class="form-group classtrn" style="margin-bottom:1px;" id="trns_1">
                                                <div class="col-sm-2">
                                                    <span class="small">Account Name</span>
                                                    <input type="text" id="draccount_1" name="account_name[]" class="form-control acname" autocomplete="off" data-toggle="modal" data-target="#account_modal">
                                                    <input type="hidden" name="account_id[]" id="draccountid_1">
                                                    <input type="hidden" name="group_id[]" id="groupid_1">
                                                    <input type="hidden" name="vatamt[]" id="vatamt_1">

                                                    <input type="hidden" id="invoiceid_1" name="sales_invoice_id[]">
                                                    <input type="hidden" name="bill_type[]" id="biltyp_1">
                                                </div>
                                                <div class="col-xs-3" style="width:22%;">
                                                    <span class="small">Description</span>
                                                    <input type="text" id="descr_1" autocomplete="off" name="description[]" class="form-control">
                                                </div>
                                                <div class="col-xs-2" style="width:14%;">
                                                    <span class="small">Reference</span>
                                                    <div id="refdata_1" class="refdata">
                                                        <input type="text" id="ref_1" name="reference[]" autocomplete="off" class="form-control">
                                                    </div>
                                                    <input type="hidden" name="inv_id[]" id="invid_1">
                                                    <input type="hidden" name="actual_amount[]" id="actamt_1">
                                                </div>
                                                <div class="col-xs-1" style="width:8%;">
                                                    <span class="small">Type</span>
                                                    <select id="acnttype_1" class="form-control select2 line-type" style="width:100%;padding-left:5px;" name="account_type[]">
                                                        <option value="Dr">Dr</option>
                                                        <option value="Cr">Cr</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-2" style="width:12%;">
                                                    <span class="small">Amount</span>
                                                    <input type="number" id="amount_1" autocomplete="off" step="any" name="line_amount[]" class="form-control jvline-amount">
                                                </div>

                                                <div class="col-sm-2" style="width:13%;">
                                                    <span class="small">Job</span>
                                                    <input type="hidden" name="job_id[]" id="jobid_1" >
                                                    <input type="text" id="jobcod_1" autocomplete="off" name="jobcod[]" class="form-control" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
                                                </div>

                                                <div id="salem_1" class="col-sm-2 salem" style="width:11%;">
                                                    <span class="small">Salesman</span>
                                                    <input type="hidden" name="salesman_idd[]" id="salesmanid_1" >
                                                    <input type="text" id="salesman_1" autocomplete="off" name="salesman[]" class="form-control" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
                                                </div>

                                                @if($isdept)
                                                <div class="col-xs-3" style="width:13%;">
                                                    <span class="small">Department</span>
                                                    <select id="dept_1" class="form-control select2 line-dept" style="width:100%" name="department[]">
                                                        <option value="">Department...</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif

                                                <div class="col-xs-1 abc" style="width:3%;"><br/>
                                                    <button type="button" class="btn-danger btn-remove-item">
                                                        <i class="fa fa-fw fa-minus-square"></i>
                                                    </button>
                                                    <button type="button" class="btn-success btn-add-item">
                                                        <i class="fa fa-fw fa-plus-square"></i>
                                                    </button>
                                                </div>

                                                <div id="chqdtl_1" class="divchq" style="display:none;">
                                                    <div class="col-xs-2">
                                                        <span class="small">Bank</span>
                                                        <select id="bankid_1" class="form-control select2 line-bank" style="width:100%" name="bank_id[]">
                                                            <option value="">Select Bank...</option>
                                                            @foreach($banks as $bank)
                                                                <option value="{{$bank['id']}}">{{$bank['code']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <span class="small">Cheque No</span>
                                                        <input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control" >
                                                    </div>

                                                    <div class="col-xs-2">
                                                        <span class="small">Cheque Date</span>
                                                        <input type="text" autocomplete="off" id="chkdate_1" name="cheque_date[]" class="form-control chqdate" data-language='en'>
                                                    </div>

                                                    <div class="col-xs-2">
                                                        <input type="hidden" name="partyac_id[]" id="partyac_1">
                                                        <span class="small">Party Name</span>
                                                        <input type="text" id="party_1" name="party_name[]" autocomplete="off" class="form-control" data-toggle="modal" data-target="#paccount_modal">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <hr/>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Enable On Account Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" id="is_onacnt" name="is_onaccount" value="1">
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Total Debit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="debit" name="debit" readonly placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Total Credit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="credit" name="credit" readonly placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group rcEntry">
                                    <label class="col-sm-2 control-label">Recurring Period</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="rcperiod" name="rcperiod">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-primary addRecu">Add</button>
                                    </div>
                                </div>

                                <div class="toRc"></div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary submitBtn">Submit</button>
                                        <a href="{{ url('journal') }}" class="btn btn-danger">Cancel</a>
                                        <a href="{{ url('journal') }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>

                            </form>

                            {{-- Modals --}}
                            <div id="account_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Account</h4>
                                        </div>
                                        <div class="modal-body" id="account_data"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="reference_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog" style="width:60%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Invoice</h4>
                                        </div>
                                        <div class="modal-body" id="invoiceData"></div>
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
                                        <div class="modal-body" id="jobData"></div>
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
                                        <div class="modal-body" id="salesmanData"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="paccount_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Account</h4>
                                        </div>
                                        <div class="modal-body" id="paccount_data"></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.controls -->
                    </div><!-- /.panel-body -->
                </div><!-- /.panel -->

            <?php } ?>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- jQuery Validate -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

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
$('#voucher_date').datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });
$('.bnk-info').hide();
$('#chkdt').datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });

"use strict";

$(document).ready(function () {

/* ---------------------------------------------------
   🧩 Base Validation Setup
--------------------------------------------------- */
$.validator.addMethod("equalToDebit", function(value, element) {
    var debit  = parseFloat($("#debit").val())  || 0;
    var credit = parseFloat($("#credit").val()) || 0;
    return debit === credit;
}, "Debit and Credit must be equal.");

var validator = $("#frmJournal").validate({
    ignore: [],
    errorClass: "text-danger",
    validClass: "is-valid",
    rules: {
        voucher_no:   { required: true },
        voucher_date: { required: true },
        "account_id[]":  { required: true },
        "reference[]":   { required: true },
        "line_amount[]": { required: true, number: true, min: 0.01 },
        debit:  { required: true, number: true },
        credit: { required: true, number: true, equalToDebit: true }
    },
    messages: {
        voucher_no: "Voucher number is required.",
        voucher_date: "Please provide a date.",
        "account_id[]": "Select an account.",
        "reference[]": "Reference required.",
        "line_amount[]": {
            required: "Enter an amount.",
            number: "Enter a valid number.",
            min: "Amount must be greater than zero."
        },
        credit: "Debit and Credit must be equal!"
    },
    errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) error.insertAfter(element.parent());
        else error.insertAfter(element);
    },
    highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
    }
});

/* ---------------------------------------------------
   🧩 Helper: Attach/Remove Rules Dynamically
--------------------------------------------------- */
function attachRulesForRow(idx){
    if ($('#draccountid_' + idx).length)
        $('#draccountid_' + idx).rules('add', { required: true, messages:{ required: 'Select an account.' }});
    if ($('#ref_' + idx).length)
        $('#ref_' + idx).rules('add', { required: true, messages:{ required: 'Reference required.' }});
    if ($('#amount_' + idx).length)
        $('#amount_' + idx).rules('add', {
            required: true, number: true, min: 0.01,
            messages: {
                required: 'Amount required.',
                number: 'Enter a valid number.',
                min: 'Must be greater than zero.'
            }
        });
}
function removeRulesForRow(idx){
    $('#draccountid_' + idx).rules('remove');
    $('#ref_' + idx).rules('remove');
    $('#amount_' + idx).rules('remove');
}

/* ---------------------------------------------------
   🧩 Validate All Rows (shows inline messages)
--------------------------------------------------- */
function validateAllRows() {
    let valid = true;
    $('.itemdivChld').each(function() {
        const idx = $(this).find('input[id*="_"]').first().attr('id').split('_')[1];
        const acc = $('#draccountid_' + idx);
        const ref = $('#ref_' + idx);
        const amt = $('#amount_' + idx);

        // Clear old messages
        acc.removeClass('is-invalid'); ref.removeClass('is-invalid'); amt.removeClass('is-invalid');
        acc.next('label.error').remove(); ref.next('label.error').remove(); amt.next('label.error').remove();

        // Account field check
        if (!acc.val() || acc.val().trim() === '') {
            acc.addClass('is-invalid').after('<label class="text-danger error">Select an account.</label>');
            valid = false;
        }

        // Reference check
        if (!ref.val() || ref.val().trim() === '') {
            ref.addClass('is-invalid').after('<label class="text-danger error">Reference required.</label>');
            valid = false;
        }

        // Amount check
        if (!amt.val() || parseFloat(amt.val()) <= 0) {
            amt.addClass('is-invalid').after('<label class="text-danger error">Amount required.</label>');
            valid = false;
        }
    });
    return valid;
}

/* ---------------------------------------------------
   🧩 Initial Setup
--------------------------------------------------- */
window.rowNum = 1;
$('.btn-remove-item').hide();

/* ---------------------------------------------------
   🧩 Add Row
--------------------------------------------------- */
$(document).on('click', '.btn-add-item', function(e){
    e.preventDefault();

    // Current row check before adding new
    if ($('#draccount_' + rowNum).val() === '') { $('#draccount_' + rowNum).focus(); return false; }
    if ($('#ref_' + rowNum).val()       === '') { $('#ref_' + rowNum).focus(); return false; }
    if ($('#amount_' + rowNum).val()    === '') { $('#amount_' + rowNum).focus(); return false; }
	var curNum = rowNum;
    rowNum++;
    var controlForm  = $('.controls .itemdivPrnt'),
        currentEntry = $(this).closest('.itemdivChld'),
        newEntry     = currentEntry.clone().appendTo(controlForm);

    // Reset fields and assign new IDs
    newEntry.find('input, select').each(function(){
        var oldId = $(this).attr('id');
        if (oldId){
            var baseId = oldId.split('_')[0];
            $(this).attr('id', baseId + '_' + rowNum);
        }
        if ($(this).is('input')) $(this).val('');
        $(this).removeClass('error is-invalid is-valid');
    });

	if($('#groupid_'+curNum).val()=='PDCI' || $('#groupid_'+curNum).val()=='PDCR') { console.log('gggggg'+curNum)
		$('#chkno_'+rowNum).hide();
		$('#chkdate_'+rowNum).hide();
		$('#party_'+rowNum).hide();
		$('#bankid_'+rowNum).hide();
		newEntry.find($('.pdcfm')).hide();
		newEntry.find($('.col-xs-2')).css('width', '20%');
		newEntry.find($('.col-xs-1')).css('width', '15%');
	}
	
	$("#amount_"+rowNum).val(parseFloat($("#amount_"+rowNum).val()));
	$('#draccount_'+rowNum).val('');
	$('#draccountid_'+rowNum).val('');
	$('#chkno_'+rowNum).val('');
	$('#chkdate_'+rowNum).val('');
	$('#party_'+rowNum).val('');
	$('#partyac_'+rowNum).val('');
	$('#jobcod_'+rowNum).val('');
	$('#jobid_'+rowNum).val('');
	$('#salesman_'+rowNum).val('');
	$('#salesmanid_'+rowNum).val('');
	
    newEntry.find('.classtrn').attr('id', 'trns_' + rowNum);
    newEntry.find('.divchq').attr('id', 'chqdtl_' + rowNum).hide();
    newEntry.find('.refdata').attr('id', 'refdata_' + rowNum);
    newEntry.find('.salem').attr('id', 'salem_' + rowNum);
    controlForm.find('.btn-remove-item').show();

    attachRulesForRow(rowNum);
    getNetTotal();
});

/* ---------------------------------------------------
   🧩 Remove Row
--------------------------------------------------- */
$(document).on('click', '.btn-remove-item', function(e){
    e.preventDefault();
    var $row = $(this).closest('.itemdivChld');
    var idx = $row.find('input[id*="_"]').first().attr('id').split('_')[1];
    removeRulesForRow(idx);
    $row.remove();
    if ($('.itemdivPrnt .itemdivChld').length === 1) $('.itemdivPrnt .btn-remove-item').hide();
    getNetTotal();
});

/* ---------------------------------------------------
   🧩 Calculate Totals
--------------------------------------------------- */
$(document).on('keyup change', '.jvline-amount, .line-type', getNetTotal);
function getNetTotal(){
    var dr = 0, cr = 0;
    $('.jvline-amount').each(function(){
        var id = (this.id||'').split('_')[1];
        var val = parseFloat($(this).val()) || 0;
        var type = $('#acnttype_' + id).val();
        if (type === 'Dr') dr += val; else cr += val;
    });
    $('#debit').val(dr.toFixed(2));
    $('#credit').val(cr.toFixed(2));
    $('#difference').val((dr - cr).toFixed(2));
    $("#frmJournal").validate().element('#credit');
}

/* ---------------------------------------------------
   🧩 Modals + Cheque check + OnAccount + Recurring
   (All retained from your existing code)
--------------------------------------------------- */

var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="account_name[]"]', function() {
    var curNum = this.id.split('_')[1];
    $('#account_data').load(acurl + '/' + curNum, function(){
        $('#myModal').modal({show:true});
    });
});
$(document).on('click', '#account_data .custRow', function() {
    var num = $('#num').val(); 
    var gid = $(this).attr("data-group");
    $('#draccount_'  + num).val($(this).attr("data-name"));
    $('#draccountid_'+ num).val($(this).attr("data-id"));
    $('#groupid_'    + num).val($(this).attr("data-group"));
    attachRulesForRow(num);
    if (gid=='SUPPLIER' || gid=='CUSTOMER' || gid=='VATIN' || gid=='VATOUT') {
        $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
    } else {
        var nm = num - 1;
        var refval = (nm > 0) ? $('#ref_'+nm).val() : '';
        $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+refval+'" name="reference[]" class="form-control">');
    }
    attachRulesForRow(num);
    getNetTotal();
});

function checkChequeNo(curNum){
    var bank = $('#bankid_'+curNum+' option:selected').val();
    var chqno = $('#chkno_'+curNum).val();
    var ac    = $('#partyac_'+curNum).val();
    $.ajax({
        url: "{{ url('account_master/check_chequeno/') }}",
        type: 'get',
        data: 'chqno='+encodeURIComponent(chqno)+'&bank_id='+encodeURIComponent(bank)+'&ac_id='+encodeURIComponent(ac),
        success: function(data) { 
            if (data=='') {
                alert('Cheque no is duplicate!');
                $('#chkno_'+curNum).val('');
            }
        }
    });
}

$('.onacnt_icheck').on('ifChecked', function(){
    $("[name='reference[]']").val('Adv.');
    $('.acname').each(function(){
        var n = this.id.split('_')[1];
        if ($('#groupid_'+n).val()=='CUSTOMER') $('#ref_'+n).prop('readonly', true);
    });
});
$('.onacnt_icheck').on('ifUnchecked', function(){
    $("[name='reference[]']").val('').prop('readonly', false);
});

/* ---------------------------------------------------
   🧩 Final Submit Validation
--------------------------------------------------- */
$('#frmJournal').off('submit').on('submit', function(e){
    e.preventDefault();
    $('.text-danger.error').remove();

    const rowsOk = validateAllRows();
    const formOk = $("#frmJournal").valid();

    if (rowsOk && formOk) {
        this.submit();
    } else {
        $('html, body').animate({
            scrollTop: $(".is-invalid:first").offset().top - 150
        }, 400);
    }
});

});
</script>
@stop
