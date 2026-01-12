@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Receipt Voucher
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
        <h1>Receipt Voucher</h1>
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
                <a href="#">Receipt Voucher</a>
            </li>
            <li class="active">
                Add New
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
                            @can('jv-print')
                            <form class="form-horizontal" role="form" method="GET" target="_blank" name="frmItem" id="frmItem" action="{{ url('journal/getvoucherprint') }}">
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <select id="voucher_typeprint" class="form-control select2" style="width:100%" name="voucher_typeprint" required>
                                            <option value="9">RV - Receipt Voucher</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control pull-right" name="voucherprnt_no" placeholder="voucher no" autocomplete="off"  id="voucherprnt_no" />
                                    </div>
                                    <div class="col-xs-3">
                                        <button type="submit" class="btn btn-info btn-sm">
                                            <span class="btn-label"><i class="fa fa-fw fa-print"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endcan
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="controls">
                            <form class="form-horizontal" role="form" method="POST" name="frmJournal" id="frmJournal" action="{{ url('receipt_voucher/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="from_jv" value="1">
                                <input type="hidden" name="status" id="status">

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Type</label>
                                    <div class="col-sm-10">
                                        <select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
                                            <option value="9">RV - Receipt Voucher</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher</label>
                                    <div class="col-sm-10">
                                        <select id="voucher" class="form-control select2" style="width:100%" name="voucher">
                                            @foreach($vouchers as $voucher)
                                                <option value="{{$voucher->id}}">{{$voucher->voucher_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">RV. No.</label>
                                    <input type="hidden" name="curno" id="curno" value="{{$vchrdata['voucher_no']}}">
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
                                    <label for="input-text" class="col-sm-2 control-label">RV. Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control pull-right" name="voucher_date" data-language='en' id="voucher_date" autocomplete="off"/>
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
                                                        <input type="text" autocomplete="off" id="chkno_1" name="cheque_no[]" class="form-control">
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
                                    <label for="input-text" class="col-sm-2 control-label">Enable On Account Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" id="is_onacnt" name="is_onaccount" value="1">
                                        </label>
                                    </div>
                                </div>

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
                                    <label for="input-text" class="col-sm-2 control-label">Difference</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="difference" name="difference" readonly placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group rcEntry">
                                    <label for="input-text" class="col-sm-2 control-label">Recurring Period</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="rcperiod" name="rcperiod">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-primary addRecu">Add</button>
                                    </div>
                                </div>

                                <div class="toRc"></div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
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

                        </div> <!-- /.controls -->
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel -->

                <?php } ?>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
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
    // ---- Dates ----
    $('#voucher_date').datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });
    $('#voucher_date').data('datepicker').selectDate(new Date());

    "use strict";

    $(document).ready(function () {
        // -------- utils ----------
        function msgRequired(label){ return label + " is required."; }

        // keep row counter
        var rowNum = 1;

        // make sure validator exists before adding rules
        $.validator.addMethod("equalToDebit", function() {
            var debit = parseFloat($("#debit").val()) || 0;
            var credit = parseFloat($("#credit").val()) || 0;
            return debit === credit;
        }, "Debit and Credit must be equal.");

        window.validator = $("#frmJournal").validate({
        ignore: [],
        errorClass: "text-danger",
        validClass: "is-valid",
        rules: {
            voucher_no:   { required: true },
            voucher_date: { required: true },
            debit:        { required: true, number: true },
            credit:       { required: true, number: true, equalToDebit: true }
        },
        messages: {
            voucher_no: "Voucher number is required.",
            voucher_date: "Please provide a date."
        },
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) error.insertAfter(element.parent());
            else error.insertAfter(element);
        },
        highlight: function(el){ $(el).addClass('is-invalid').removeClass('is-valid'); },
        unhighlight: function(el){ $(el).removeClass('is-invalid').addClass('is-valid'); },

        // 👇 NEW: when *any* field is invalid (e.g., Debit≠Credit),
        // also validate every row so all row errors show together.
        invalidHandler: function(event, v){
            validateAllRows();                 // <-- your existing function
            // focus first visible error for UX
            if (v.errorList && v.errorList.length){
            setTimeout(function(){
                $(v.errorList[0].element).focus();
            }, 0);
            }
        },

submitHandler: function(form) {
    // Recheck rows first
    if(!validateAllRows()){
        var $firstError = $('.is-invalid:visible').first();
        if($firstError.length) $firstError.focus();
        return false;
    }
    if(!checkDuplicateChequeNo()){
    alert("Duplicate Cheque No detected for same Bank and Party. Please correct it before submitting.");
    return false;
}

    // 🔹 Check debit and credit equality before submit
    var debit  = parseFloat($("#debit").val())  || 0;
    var credit = parseFloat($("#credit").val()) || 0;

    if(debit !== credit){
        alert("Debit and Credit must be equal before submitting.");
        $("#credit").focus();
        return false;
    }

    // ✅ All good → submit form
    form.submit();
}

        });

        $('#frmJournal').on('submit', function(e){
        // If the base form is invalid (e.g., debit != credit), also show row errors
        if(!$(this).valid()){                // triggers validator; false = invalid
            validateAllRows();                 // make row errors visible too
            e.preventDefault();
        }
        });


        // add validation rules for one row (safe to call repeatedly)
        function addRulesForRow(idx){
            // no-op if validator missing or row missing
            if(!window.validator || !$('#trns_'+idx).length) return;

            function addRule(sel, ruleObj){
                var $el = $(sel+idx);
                if(!$el.length) return;
                $el.rules('remove');       // remove previous to avoid duplicates
                $el.rules('add', ruleObj);
            }

            addRule('#draccountid_', {
                required:true,
                messages:{ required: msgRequired("Account") }
            });

            addRule('#ref_', {
                required:true,
                messages:{ required: msgRequired("Reference") }
            });

            addRule('#amount_', {
                required:true, number:true, min:0.01,
                messages:{
                    required: msgRequired("Amount"),
                    number: "Enter a valid number.",
                    min: "Amount must be greater than zero."
                }
            });

            // Conditional PDC fields
            var pdcDepends = function(){
                var g = $('#groupid_'+idx).val();
                return (g === 'PDCR' || g === 'PDCI');
            };

            addRule('#bankid_',  { required:{ depends: pdcDepends }, messages:{ required: msgRequired("Bank") } });
            addRule('#chkno_',   { required:{ depends: pdcDepends }, messages:{ required: msgRequired("Cheque No") } });
            addRule('#chkdate_', { required:{ depends: pdcDepends }, messages:{ required: msgRequired("Cheque Date") } });
            addRule('#party_',   { required:{ depends: pdcDepends }, messages:{ required: msgRequired("Party Name") } });
        }

        // validate a single row now
    function validateRow(idx){
    addRulesForRow(idx);
    var ok = true;
    var g = $('#groupid_'+idx).val();

    // Always check base fields
    ok = window.validator.element('#draccountid_'+idx) && ok;
    ok = window.validator.element('#ref_'+idx) && ok;
    ok = window.validator.element('#amount_'+idx) && ok;

    // --- Force check PDC fields explicitly ---
    if(g === 'PDCR' || g === 'PDCI'){
        ['#bankid_','#chkno_','#chkdate_','#party_'].forEach(function(prefix){
            var $el = $(prefix+idx);
            if($el.length){
                // temporarily mark as required for this validation cycle
                $el.rules('remove');
                $el.rules('add',{ required:true, messages:{ required: msgRequired($el.prev('span.small').text() || 'Field') } });
                ok = window.validator.element($el) && ok;
            }
        });
    }

    return ok;
}

        // validate all current rows
        function validateAllRows(){
            var allOk = true;
            $('.itemdivPrnt .itemdivChld .classtrn').each(function(){
                var id = $(this).attr('id'); if(!id) return;
                var idx = parseInt(id.split('_')[1],10);
                allOk = validateRow(idx) && allOk;
            });
            return allOk;
        }

        // Only last row shows Add; Remove hidden if only one row
        function refreshRowButtons(){
            var $rows = $('.itemdivPrnt .itemdivChld');
            $rows.each(function(i){
                var $row = $(this);
                var isLast = (i === $rows.length - 1);
                $row.find('.btn-add-item')[isLast ? 'show' : 'hide']();
                $row.find('.btn-remove-item')[($rows.length > 1) ? 'show' : 'hide']();
            });
        }

        // Init state
        $('.btn-remove-item').hide();
        addRulesForRow(1);
        refreshRowButtons();

        // ------- Totals -------
        function getNetTotal() {
            var dr=0, cr=0;
            $('.jvline-amount').each(function(){
                var id = this.id.split('_')[1];
                var val = parseFloat($(this).val() || 0);
                var t = $('#acnttype_'+id+' option:selected').val();
                if(t === 'Dr') dr += val;
                else if(t === 'Cr') cr += val;
            });
            $("#debit").val(dr.toFixed(2));
            $("#credit").val(cr.toFixed(2));
            $("#difference").val((dr-cr).toFixed(2));

            if(window.validator){ window.validator.element('#credit'); }
        }

        // expose (some old code calls it)
        window.getNetTotal = getNetTotal;

        // ------- Add Row -------
$(document).on('click', '.btn-add-item', function(e){
    e.preventDefault();

    // validate last row before cloning
    var lastId = $('.itemdivPrnt .classtrn:last').attr('id');
    var lastIdx = lastId ? parseInt(lastId.split('_')[1],10) : rowNum;
    if(!validateRow(lastIdx)) return false;

    var controlForm  = $('.controls .itemdivPrnt'),
        currentEntry = $(this).parents('.itemdivChld:first'),
        newEntry     = $(currentEntry.clone()).appendTo(controlForm);

    // increase counter
    rowNum = lastIdx + 1;

    // wipe validation marks & errors
    newEntry.find('label.error').remove();
    newEntry.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');

    // re-id & reset values
    newEntry.find('input[name="account_name[]"]').attr('id', 'draccount_' + rowNum).val('');
    newEntry.find('input[name="account_id[]"]').attr('id', 'draccountid_' + rowNum).val('');
    newEntry.find('input[name="description[]"]').attr('id', 'descr_' + rowNum).val('');
    newEntry.find('input[name="group_id[]"]').attr('id', 'groupid_' + rowNum).val('');
    newEntry.find('.line-type').attr('id', 'acnttype_' + rowNum).val('Dr');
    newEntry.find('input[name="line_amount[]"]').attr('id', 'amount_' + rowNum).val('');
    newEntry.find('input[name="actual_amount[]"]').attr('id', 'actamt_' + rowNum).val('');
    newEntry.find('input[name="reference[]"]').attr('id', 'ref_' + rowNum).val($('#ref_'+lastIdx).val()||'');
    newEntry.find('input[name="inv_id[]"]').attr('id', 'invid_' + rowNum).val('');
    newEntry.find('input[name="job_id[]"]').attr('id', 'jobid_' + rowNum).val('');
    newEntry.find('input[name="jobcod[]"]').attr('id', 'jobcod_' + rowNum).val('');
    newEntry.find('.line-dept').attr('id', 'dept_' + rowNum).val('');
    newEntry.find('input[name="cheque_no[]"]').attr('id', 'chkno_' + rowNum).val('');
    newEntry.find('input[name="cheque_date[]"]').attr('id', 'chkdate_' + rowNum).val('');
    newEntry.find('input[name="party_name[]"]').attr('id', 'party_' + rowNum).val('');
    newEntry.find('input[name="partyac_id[]"]').attr('id', 'partyac_' + rowNum).val('');
    newEntry.find('.line-bank').attr('id', 'bankid_' + rowNum).val('');
    newEntry.find('.divchq').attr('id', 'chqdtl_' + rowNum).hide();
    newEntry.find('.refdata').attr('id', 'refdata_' + rowNum);
    newEntry.find('input[name="sales_invoice_id[]"]').attr('id', 'invoiceid_' + rowNum).val('');
    newEntry.find('input[name="vatamt[]"]').attr('id', 'vatamt_' + rowNum).val('');
    newEntry.find('.classtrn').attr('id', 'trns_' + rowNum);
    newEntry.find('input[name="salesman_idd[]"]').attr('id', 'salesmanid_' + rowNum).val('');
    newEntry.find('input[name="salesman[]"]').attr('id', 'salesman_' + rowNum).val('');
    newEntry.find('.salem').attr('id', 'salem_' + rowNum).hide();

    // init cheque datepicker for the new row if present
    if($('#chkdate_'+rowNum).length){
        $('#chkdate_'+rowNum).datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });
    }

    // ✅ just basic validation + refresh
    addRulesForRow(rowNum);
    getNetTotal();
    refreshRowButtons();
});



        // ------- Remove Row -------
        $(document).on('click', '.btn-remove-item', function(e){
            e.preventDefault();
            $(this).parents('.itemdivChld:first').remove();
            getNetTotal();
            refreshRowButtons();
        });

        // ------- Totals triggers -------
        $(document).on('keyup', '.jvline-amount', getNetTotal);
        $(document).on('change', '.line-type', getNetTotal);

        // validate selects on change (including select2)
        $(document).on('change', 'select, .select2', function(){
            if (this.id) window.validator.element('#'+this.id);
        });

        // Enable voucher no edit
        $('.input-group-addon').on('click', function(e) {
            e.preventDefault();
            $('#voucher_no').prop("readonly", false);
        });

        // Enter => next field
        $('input,select').on('keydown', function(e) {
            if((e.which||e.keyCode||0) === 13) {
                e.preventDefault();
                var inputs = $(this).closest('form').find(':input:visible');
                inputs.eq(inputs.index(this)+1).focus();
            }
        });

        // On Account toggle
        $('.onacnt_icheck').on('ifChecked', function(){
            $("[name='reference[]']").val('Adv.');
            $('.acname').each(function(){
                var n = this.id.split('_')[1];
                if($('#groupid_'+n).val()=='CUSTOMER') { $('#ref_'+n).prop('readonly', true); }
            });
        });
        $('.onacnt_icheck').on('ifUnchecked', function(){
            $("[name='reference[]']").val('').prop('readonly', false);
        });

        // ------- Modals + Ajax loads (re-attach rules after load) -------
        var trnurl  = "{{ url('journal/set_transactions/') }}";
        var acurl   = "{{ url('account_master/get_accounts/') }}";
        var acurlall= "{{ url('account_master/get_account_all/') }}";
        var joburl  = "{{ url('jobmaster/job_data/') }}";
        var slurl   = "{{ url('sales_invoice/salesman_data/') }}";

        // Account modal open
        $(document).on('click', 'input[name="account_name[]"]', function() {
            var num = this.id.split('_')[1];
            $('#account_data').load(acurl+'/'+num, function(){
                $('#myModal').modal({show:true});
            });
        });

        // Choose account
        $(document).on('click', '#account_data .custRow', function() {
            var num = $('#num').val();
            var vatasgn = $(this).attr("data-vatassign");
            $('#draccount_'+num).val( $(this).attr("data-name") );
            $('#draccountid_'+num).val( $(this).attr("data-id") );
            $('#groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
            $('#vatamt_'+num).val( $(this).attr("data-vat") );

            window.validator.element('#draccountid_'+num);
            addRulesForRow(num);

            var grp = $(this).attr("data-group");
            if(grp==='PDCR'){
                $('#acnttype_'+num).empty().append('<option value="Dr">Dr</option>');
                $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num, function(){
                    addRulesForRow(num); refreshRowButtons();
                });
            } else if(grp==='PDCI'){
                $('#acnttype_'+num).empty().append('<option value="Cr">Cr</option>');
                $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num, function(){
                    addRulesForRow(num); refreshRowButtons();
                });
            } else if(grp==='BANK'){
                $('#trns_'+num).load( trnurl+'/BANK/'+$(this).attr("data-id")+'/'+num, function(){
                    addRulesForRow(num); refreshRowButtons();
                });
            } else {
                if(grp==='CUSTOMER'){
                    $('#salem_'+num).show();
                    $('#salesmanid_'+num).val( $(this).attr("data-salesmanid") );
                    $('#salesman_'+num).val( $(this).attr("data-salesman") );
                }
                $('#acnttype_'+num).empty().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
                $('#trns_'+num+' .pdcfm').hide();
            }

            if( grp==='SUPPLIER' || grp==='CUSTOMER' || grp==='VATIN' || grp==='VATOUT') {
                $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
            } else {
                var prev = num-1, refval = (prev>0)? $('#ref_'+prev).val(): '';
                $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control" value="'+(refval||'')+'">');
            }

            getNetTotal();
        });

        // Party modal open/choose
        $(document).on('click', 'input[name="party_name[]"]', function() {
            var num = this.id.split('_')[1];
            $('#paccount_data').load(acurlall+'/'+num, function(){ $('#myModal').modal({show:true}); });
        });
        $(document).on('click', '#paccount_data .custRow', function() {
            var num = $('#anum').val();
            $('#party_'+num).val( $(this).attr("data-name") );
            $('#partyac_'+num).val( $(this).attr("data-id") );
            checkChequeNo(num);
            window.validator.element('#party_'+num);
        });

        // Salesman
        $(document).on('click', 'input[name="salesman[]"]', function() {
            var num = this.id.split('_')[1];
            $('#salesmanData').load(slurl+'/'+num, function(){ $('#myModal').modal({show:true}); });
        });
        $(document).on('click', '#salesman_modal .salesmanRow', function() {
            var num = $('#num').val();
            $('#salesman_'+num).val($(this).attr("data-name"));
            $('#salesmanid_'+num).val($(this).attr("data-id"));
        });

        // Reference modal
        $(document).on('click', '.ref-invoice', function(e) {
            e.preventDefault();
            var num = this.id.split('_')[1];
            var grp = $('#groupid_'+num).val();
            if(grp==='CUSTOMER' || grp==='SUPPLIER' || grp==='VATIN' || grp==='VATOUT'){
                var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+num).val();
                $('#invoiceData').load(url+'/'+num, function(){ $('#myModal').modal({show:true}); });
            }
        });

        // add selected invoices => fill rows
        $(document).on('click', '.add-invoice', function(e){
            e.preventDefault();

            var refs=[], amounts=[], type=[], ids=[], actamt=[], invid=[], btype=[];
            $("input[name='tag[]']:checked").each(function() {
                var i = this.id.split('_')[1];
                ids.push($(this).val());
                refs.push( $('#refid_'+i).val() );
                amounts.push( $('#lineamnt_'+i).val() );
                type.push( $('#trtype_'+i).val() );
                actamt.push( $('#hidamt_'+i).val() );
                invid.push( $('#sinvoiceid_'+i).val() );
                btype.push( $('#billtype_'+i).val() );
            });

            var no = parseInt($('#bnum').val(),10);
            var j = 0;

            refs.forEach(function(_, k){
                if(j>0){
                    $('.itemdivPrnt .itemdivChld:last .btn-add-item').trigger('click');
                    no = parseInt($('.itemdivPrnt .classtrn:last').attr('id').split('_')[1],10);
                }
                $('#ref_'+no).val( refs[k] );
                $('#amount_'+no).val( amounts[k] );
                $('#invid_'+no).val( ids[k] );
                $('#acnttype_'+no).empty().append('<option value="'+type[k]+'">'+type[k]+'</option>');
                $('#actamt_'+no).val( actamt[k] );
                $('#invoiceid_'+no).val( invid[k] );
                $('#biltyp_'+no).val( btype[k] );

                addRulesForRow(no);
                j++;
            });

            getNetTotal();
            $('#is_onaccount').val(0);
        });

        // cheque helpers
        $(document).on('change', '.line-bank', function(){
            var n = this.id.split('_')[1];
            checkChequeNo(n);
            window.validator.element('#bankid_'+n);
        });
        $(document).on('blur', 'input[name="cheque_no[]"]', function(){
            var n = this.id.split('_')[1];
            checkChequeNo(n);
            window.validator.element('#chkno_'+n);
        });

        // 🔹 Recheck duplicates whenever these fields change
        $(document).on('keyup blur change',
            'input[name="cheque_no[]"], select[name="bank_id[]"], input[name="party_name[]"]',
            function() {
                checkDuplicateChequeNo();
            }
        );

        // approval flag (kept)
        @if($settings->pv_approval==1)
            if(vchr_id==10) { $('#status').val(0); } else { $('#status').val(1); }
        @else
            $('#status').val(1);
        @endif

        // Initialize first row accessories
        $('#salem_1').hide();
        getNetTotal();
        refreshRowButtons();
    }); // document.ready

    // ===== Helpers outside ready (kept/adapted) =====
    function checkChequeNo(curNum) {
        var bank = $('#bankid_'+curNum).val();
        var chqno = $('#chkno_'+curNum).val();
        var ac = $('#partyac_'+curNum).val();
        if(!bank || !chqno || !ac) return;

        $.ajax({
            url: "{{ url('account_master/check_chequeno/') }}",
            type: 'get',
            data: 'chqno='+encodeURIComponent(chqno)+'&bank_id='+encodeURIComponent(bank)+'&ac_id='+encodeURIComponent(ac),
            success: function(data) {
                if(data=='') {
                    alert('Cheque no is duplicate!');
                    $('#chkno_'+curNum).val('');
                }
            }
        });
    }
    // 🔹 Real-time duplicate cheque number check
function checkDuplicateChequeNo() {
    var duplicates = [];
    var seen = {};

    $('.itemdivPrnt .classtrn').each(function(){
        var id = this.id.split('_')[1];
        var bank  = $('#bankid_'+id).val();
        var chq   = ($('#chkno_'+id).val() || '').trim();
        var party = $('#partyac_'+id).val();

        if(bank && chq && party){
            var key = bank + '|' + chq + '|' + party;
            if(seen[key]) {
                duplicates.push(id);
                duplicates.push(seen[key]); // add both rows
            } else {
                seen[key] = id;
            }
        }
    });

    // Remove duplicates in array
    duplicates = [...new Set(duplicates)];

    // Clear all old duplicate errors
    $('input[name="cheque_no[]"]').each(function(){
        var id = this.id.split('_')[1];
        $('#chkno_'+id).removeClass('is-invalid');
        $('#chkno_'+id+'-dup-error').remove();
    });

    // Add new duplicate highlights
    duplicates.forEach(function(id){
        var $chq = $('#chkno_'+id);
        if($chq.length){
            $chq.addClass('is-invalid');
            if(!$('#chkno_'+id+'-dup-error').length){
                $('<label id="chkno_'+id+'-dup-error" class="text-danger">Duplicate cheque no for same bank & party.</label>')
                    .insertAfter($chq);
            }
        }
    });

    return duplicates.length === 0;
}

    function getTag(e) {
        var res = e.id.split('_');
        var curNum = res[1];
        if( $("#tag_"+curNum).is(':checked') ) {
            var curamount = $("#hidamt_"+curNum).val();
            $("#lineamnt_"+curNum).val(curamount);	
            //getNetTotal();
        } else {
            $("#lineamnt_"+curNum).val('');
            //getNetTotal();
        }
        
    }
    </script>
@stop
