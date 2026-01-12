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
                            @permission('jv-print')
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
                            @endpermission
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
    <!-- jQuery core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

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
    $('#voucher_date').datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });
	$('#voucher_date').data('datepicker').selectDate(new Date());
    $('.bnk-info').hide();
    $('#chkdt').datepicker({ autoClose:true ,dateFormat: 'dd-mm-yyyy' });

    "use strict";

    $(document).ready(function () {

        /**** ---------- VALIDATION HELPERS (NEW) ---------- ****/

        function msgRequired(label){ return label + " is required."; }

        function addRulesForRow(idx){
            if(!$('#trns_'+idx).length) return;

            $('#draccountid_'+idx).rules('remove');
            $('#draccountid_'+idx).rules('add', {
                required:true,
                messages:{ required: msgRequired("Account") }
            });

            $('#ref_'+idx).rules('remove');
            $('#ref_'+idx).rules('add', {
                required:true,
                messages:{ required: msgRequired("Reference") }
            });

            $('#amount_'+idx).rules('remove');
            $('#amount_'+idx).rules('add', {
                required:true,
                number:true,
                min:0.01,
                messages:{
                    required: msgRequired("Amount"),
                    number: "Enter a valid number.",
                    min: "Amount must be greater than zero."
                }
            });

            // PDC conditional fields
            var pdcDepends = function(){
                var g = $('#groupid_'+idx).val();
                return (g === 'PDCR' || g === 'PDCI');
            };

            $('#bankid_'+idx).rules('remove');
            $('#bankid_'+idx).rules('add', {
                required:{ depends: pdcDepends },
                messages:{ required: msgRequired("Bank") }
            });

            $('#chkno_'+idx).rules('remove');
            $('#chkno_'+idx).rules('add', {
                required:{ depends: pdcDepends },
                messages:{ required: msgRequired("Cheque No") }
            });

            $('#chkdate_'+idx).rules('remove');
            $('#chkdate_'+idx).rules('add', {
                required:{ depends: pdcDepends },
                messages:{ required: msgRequired("Cheque Date") }
            });

            $('#party_'+idx).rules('remove');
            $('#party_'+idx).rules('add', {
                required:{ depends: pdcDepends },
                messages:{ required: msgRequired("Party Name") }
            });
        }

        function validateRow(idx){
            var ok = true;
           addRulesForRow(idx);

            ['#draccountid_','#ref_','#amount_','#bankid_','#chkno_','#chkdate_','#party_'].forEach(function(prefix){
                var $el = $(prefix+idx);
                if($el.length){
                    if(prefix==='#bankid_'||prefix==='#chkno_'||prefix==='#chkdate_'||prefix==='#party_'){
                        var g = $('#groupid_'+idx).val();
                        if(!(g==='PDCR'||g==='PDCI')) return;
                    }
                    ok = validator.element($el) && ok;
                }
            });
            return ok;
        }

        function validateAllRows(){
            var allOk = true;
            $('.itemdivPrnt .itemdivChld .classtrn').each(function(){
                var id = $(this).attr('id');
                if(!id) return;
                var idx = parseInt(id.split('_')[1],10);
                allOk = validateRow(idx) && allOk;
            });
            return allOk;
        }

        /**** ---------- CORE FORM VALIDATION ---------- ****/

         $.validator.addMethod("equalToDebit", function(value, element) {
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
                voucher_date: { required: true },  // <— make sure name matches input
                debit:  { required: true, number: true },
                credit: { required: true, number: true, equalToDebit: true }
            },
            messages: {
                voucher_no: "Voucher number is required.",
                voucher_date: "Please provide a date.",
                credit: "Debit and Credit should be equal!"
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) error.insertAfter(element.parent());
                else error.insertAfter(element);
            },
            highlight: function(element) { $(element).addClass('is-invalid').removeClass('is-valid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid').addClass('is-valid'); },
            submitHandler: function(form) {
                if(!validateAllRows()){
                    var $firstError = $('.is-invalid:visible').first();
                    if($firstError.length) $firstError.focus();
                    return false;
                }
                form.submit();
            }
        });

        $(document).on('change', 'select, .select2', function(){
            if (this.id) validator.element('#'+this.id);
        });

        /**** ---------- EXISTING LOGIC (kept) ---------- ****/

        @if($settings->pv_approval==1)
            if(vchr_id==10) { $('#status').val(0); } else { $('#status').val(1); }
        @else
            $('#status').val(1);
        @endif

        var trnurl = "{{ url('journal/set_transactions/') }}";
        $('.btn-remove-item').hide();

        $('.bank-chk').on('ifChecked', function(){ $('.bnk-info').show(); });
        $('.bank-chk').on('ifUnchecked', function(){ $('.bnk-info').hide(); });
        $("#chequeInput").toggle();

        @if($settings->pv_approval==1)
            if(vchr_id==10) { $('#status').val(0); } else { $('#status').val(1); }
        @else
            $('#status').val(1);
        @endif

        var rowNum = 1;
       addRulesForRow(1); // attach rules for first row

        // ADD ROW
        $(document).on('click', '.btn-add-item', function(e){
            e.preventDefault();

            if(!validateRow(rowNum)) return false;

            var group_id = $('#groupid_'+rowNum).val();
            var refno    = $('#ref_'+rowNum).val();
            var curNum   = rowNum;

            rowNum++;

            var controlForm  = $('.controls .itemdivPrnt'),
                currentEntry = $(this).parents('.itemdivChld:first'),
                newEntry     = $(currentEntry.clone()).appendTo(controlForm);

            // clear any previous validation labels/classes
            newEntry.find('label.error').remove();
            newEntry.find('.is-invalid').removeClass('is-invalid');
            newEntry.find('.is-valid').removeClass('is-valid');

            // re-id & reset
            newEntry.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
            newEntry.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum).val('');
            newEntry.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum).val('');
            newEntry.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum);
            newEntry.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
            newEntry.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val('');
            newEntry.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum).val('');
            newEntry.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum).val($('#ref_'+(rowNum-1)).val()||'');
            newEntry.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum).val('');
            newEntry.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum).val('');
            newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum).val('');
            newEntry.find($('.line-dept')).attr('id', 'dept_' + rowNum);
            newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum).val('');
            newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum).val('');
            newEntry.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum).val('');
            newEntry.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum).val('');
            newEntry.find($('.line-bank')).attr('id', 'bankid_' + rowNum).val('');
            newEntry.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
            newEntry.find($('.refdata')).attr('id', 'refdata_' + rowNum);
            newEntry.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum).val('');
            newEntry.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum).val('');
            newEntry.find($('input[name="bill_type[]"]')).attr('id', 'biltyp_' + rowNum).val('');
            newEntry.find($('.classtrn')).attr('id', 'trns_' + rowNum);
            newEntry.find($('input[name="salesman_idd[]"]')).attr('id', 'salesmanid_' + rowNum).val('');
            newEntry.find($('input[name="salesman[]"]')).attr('id', 'salesman_' + rowNum).val('');
            newEntry.find($('.salem')).attr('id', 'salem_' + rowNum);

            if(group_id!='1') {
                if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') {
                    $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option><option value="Dr">Dr</option>');
                } else {
                    $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
                }
            }

            if($('#groupid_'+curNum).val()=='PDCI' || $('#groupid_'+curNum).val()=='PDCR') {
                $('#chkno_'+rowNum).hide();
                $('#chkdate_'+rowNum).hide();
                $('#party_'+rowNum).hide();
                $('#bankid_'+rowNum).hide();
                newEntry.find($('.pdcfm')).hide();
                newEntry.find($('.col-xs-2')).css('width', '20%');
                newEntry.find($('.col-xs-1')).css('width', '15%');
				
            }

            addRulesForRow(rowNum);
            controlForm.find('.btn-remove-item').show();

            // VAT special (kept)
            if(group_id=='1') {
                window.con = confirm('Do you want to update VAT account?');
                if(con) {
                    $('#draccount_'+rowNum).val( '<?php echo ($account)?$account->master_name:'';?>' );
                    $('#draccountid_'+rowNum).val( '<?php echo ($account)?$account->expense_account:'';?>' );
                    $('#groupid_'+rowNum).val( '<?php echo ($account)?$account->id:'';?>' );

                    var amount = parseFloat($('#amount_'+(rowNum-1)).val())||0;
                    var vat = parseFloat($('#vatamt_'+(rowNum-1)).val())||0;
                    var vatamt = amount * vat / 100;
                    $('#amount_'+rowNum).val(vatamt);
                    $('#ref_'+rowNum).val(refno);
                    controlForm.find('.btn-add-item').hide();

                    var amt = 0;
                    $('.jvline-amount').each(function(){ amt += parseFloat(this.value||0); });

                    var currentEntry2 = $('.btn-add-item').parents('.itemdivChld:first'),
                        newEntry2 = $(currentEntry2.clone()).appendTo(controlForm);
                    rowNum++;

                    newEntry2.find('label.error').remove();
                    newEntry2.find('.is-invalid').removeClass('is-invalid');
                    newEntry2.find('.is-valid').removeClass('is-valid');

                    newEntry2.find($('input[name="account_name[]"]')).attr('id', 'draccount_' + rowNum).val('');
                    newEntry2.find($('input[name="account_id[]"]')).attr('id', 'draccountid_' + rowNum).val('');
                    newEntry2.find($('input[name="description[]"]')).attr('id', 'descr_' + rowNum).val('');
                    newEntry2.find($('input[name="reference[]"]')).attr('id', 'ref_' + rowNum).val('');
                    newEntry2.find($('input[name="group_id[]"]')).attr('id', 'groupid_' + rowNum).val('');
                    newEntry2.find($('input[name="inv_id[]"]')).attr('id', 'invid_' + rowNum).val('');
                    newEntry2.find($('.line-type')).attr('id', 'acnttype_' + rowNum);
                    newEntry2.find($('input[name="line_amount[]"]')).attr('id', 'amount_' + rowNum).val(amt);
                    newEntry2.find($('input[name="actual_amount[]"]')).attr('id', 'actamt_' + rowNum).val('');
                    newEntry2.find($('input[name="job_id[]"]')).attr('id', 'jobid_' + rowNum).val('');
                    newEntry2.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum).val('');
                    newEntry2.find($('.line-dept')).attr('id', 'dept_' + rowNum);
                    newEntry2.find($('input[name="cheque_no[]"]')).attr('id', 'chkno_' + rowNum).val('');
                    newEntry2.find($('input[name="cheque_date[]"]')).attr('id', 'chkdate_' + rowNum).val('');
                    newEntry2.find($('input[name="party_name[]"]')).attr('id', 'party_' + rowNum).val('');
                    newEntry2.find($('input[name="partyac_id[]"]')).attr('id', 'partyac_' + rowNum).val('');
                    newEntry2.find($('.line-bank')).attr('id', 'bankid_' + rowNum).val('');
                    newEntry2.find($('.divchq')).attr('id', 'chqdtl_' + rowNum);
                    newEntry2.find($('.refdata')).attr('id', 'refdata_' + rowNum);
                    newEntry2.find($('input[name="sales_invoice_id[]"]')).attr('id', 'invoiceid_' + rowNum).val('');
                    newEntry2.find($('input[name="vatamt[]"]')).attr('id', 'vatamt_' + rowNum).val('');
                    newEntry2.find($('.classtrn')).attr('id', 'trns_' + rowNum);
                    newEntry2.find($('input[name="salesman_idd[]"]')).attr('id', 'salesmanid_' + rowNum).val('');
                    newEntry2.find($('input[name="salesman[]"]')).attr('id', 'salesman_' + rowNum).val('');
                    newEntry2.find($('.salem')).attr('id', 'salem_' + rowNum);

                    if( $('#acnttype_'+curNum+' option:selected').val()=='Dr') {
                        $('#acnttype_'+rowNum).find('option').remove().end().append('<option value="Cr">Cr</option>');
                    }
                    getNetTotal();

                    addRulesForRow(rowNum);

                    $('#trndtl_'+rowNum).toggle();
                    controlForm.find('.btn-add-item').show();
                } else {
                    addRulesForRow(rowNum);
                    getNetTotal();
                }
            } else {
                addRulesForRow(rowNum);
                getNetTotal();
            }
        });

        // REMOVE ROW
        $(document).on('click', '.btn-remove-item', function(e){
            e.preventDefault();
            $(this).parents('.itemdivChld:first').remove();

            $('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
            if ($('.itemdivPrnt').children().length == 1) {
                $('.itemdivPrnt').find('.btn-remove-item').hide();
            }

            getNetTotal();
            return false;
        });

        // misc kept
        $(':input[type=number]').on('mousewheel',function(){ $(this).blur(); });
        $('#trndtl_1').toggle(); $('#salem_1').toggle();

        $(document).on('change', '.line-type', function(){ getNetTotal(); });

        // Account modal
        var acurl = "{{ url('account_master/get_accounts/') }}";
        $(document).on('click', 'input[name="account_name[]"]', function() {
            var res = this.id.split('_');
            var curNum = res[1];
            $('#account_data').load(acurl+'/'+curNum, function(){
                $('#myModal').modal({show:true}); $('.input-sm').focus();
            });
        });

        // Choose account from modal
        $(document).on('click', '#account_data .custRow', function() {
            var num = $('#num').val(); var vatasgn = $(this).attr("data-vatassign");
            $('#draccount_'+num).val( $(this).attr("data-name") );
            $('#draccountid_'+num).val( $(this).attr("data-id") );
            $('#groupid_'+num).val( (vatasgn==0)?$(this).attr("data-group"):$(this).attr("data-vatassign") );
            $('#vatamt_'+num).val( $(this).attr("data-vat") );

            validator.element('#draccountid_'+num);
            addRulesForRow(num);

            if($(this).attr("data-group")=='PDCR') {
                $('#chktype').val('PDCR');
                $('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option>');
                var trnurl = "{{ url('journal/set_transactions/') }}";
                $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num );
            } else if($(this).attr("data-group")=='PDCI') {
                $('#chktype').val('PDCI');
                $('#acnttype_'+num).find('option').remove().end().append('<option value="Cr">Cr</option>');
                var trnurl = "{{ url('journal/set_transactions/') }}";
                $('#trns_'+num).load( trnurl+'/PDC/'+$(this).attr("data-id")+'/'+num, function() {
                    $("#descr_"+num).val($("#descr_1").val());
                    $("#ref_"+num).val($("#ref_1").val());
                    $("#amount_"+num).val(($('#amount_'+(rowNum-1)).val()));
                    window.con = confirm('Do you want to update VAT account?');
                    if(con) $("#amount_"+num).val((parseFloat($("#amount_"+(rowNum-1)).val()))+(parseFloat($("#amount_"+(rowNum-2)).val())));
                    else $("#amount_"+num).val(($('#amount_'+(rowNum-1)).val()));
                });
            } else if($(this).attr("data-group")=='BANK') {
                $('#chktype').val('BANK');
                var trnurl = "{{ url('journal/set_transactions/') }}";
                $('#trns_'+num).load( trnurl+'/BANK/'+$(this).attr("data-id")+'/'+num );
            } else {
                if($(this).attr("data-group")=='CUSTOMER'){
                    if( $('#salem_'+num).is(":hidden") ) $('#salem_'+num).toggle();
                    $('#salesmanid_'+num).val( $(this).attr("data-salesmanid") );
                    $('#salesman_'+num).val( $(this).attr("data-salesman") );
                }
                $('#acnttype_'+num).find('option').remove().end().append('<option value="Dr">Dr</option><option value="Cr">Cr</option>');
                $('#trns_'+num+' .pdcfm').hide();
                $('#trns_'+num+' .nopdc1').attr("style", "width:17%;");
                $('#trns_'+num+' .nopdc2').attr("style", "width:25%;");
                $('#trns_'+num+' .nopdc3').attr("style", "width:15%;");
                $('#trns_'+num+' .nopdc4').attr("style", "width:8%;");
                $('#trns_'+num+' .nopdc5').attr("style", "width:13%;");
                $('#trns_'+num+' .nopdc6').attr("style", "width:17%;");
                getNetTotal();
            }

            if( $(this).attr("data-group")=='SUPPLIER' || $(this).attr("data-group")=='CUSTOMER' || $(this).attr("data-group")=='VATIN' || $(this).attr("data-group")=='VATOUT') {
                $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" name="reference[]" class="form-control ref-invoice" autocomplete="off" data-toggle="modal" data-target="#reference_modal">');
            } else {
                var nm = num - 1;
                var refval = (nm > 0) ? $('#ref_'+nm).val() : '';
                $('#refdata_'+num).html('<input type="text" id="ref_'+num+'" value="'+(refval||'')+'" name="reference[]" class="form-control">');
            }

            if( $(this).attr("data-vatassign")=='1') { $('#trndtl_'+num).toggle(); }

            getNetTotal();
        });

        // Party modal
        var acurlall = "{{ url('account_master/get_account_all/') }}";
        $(document).on('click', 'input[name="party_name[]"]', function() {
            var res = this.id.split('_');
            var curNum = res[1];
            $('#paccount_data').load(acurlall+'/'+curNum, function(){ $('#myModal').modal({show:true}); });
        });

        $(document).on('click', '#paccount_data .custRow', function() {
            var num = $('#anum').val();
            $('#party_'+num).val( $(this).attr("data-name") );
            $('#partyac_'+num).val( $(this).attr("data-id") );
            checkChequeNo(num);
            validator.element('#party_'+num);
        });

        $(document).on('change', '.line-bank', function() {
            var res = this.id.split('_');
            var curNum = res[1];
            checkChequeNo(curNum);
            validator.element('#bankid_'+curNum);
        });

        $(document).on('keyup', '.jvline-amount', function(){ getNetTotal(); });

        $(document).on('blur', 'input[name="cheque_no[]"]', function() {
            var res = this.id.split('_');
            var curNum = res[1];
            checkChequeNo(curNum);
            validator.element('#chkno_'+curNum);
        });

        var joburl = "{{ url('jobmaster/job_data/') }}";
        $(document).on('click', 'input[name="jobcod[]"]', function(e) {
            var res = this.id.split('_');
            var curNum = res[1];
            $('#jobData').load(joburl+'/'+curNum, function() {
                $('#myModal').modal({show:true}); $('.input-sm').focus();
            });
        });

        $(document).on('click', '#job_modal .jobRow', function(e) {
            var num =$('#num').val();
            $('#jobcod_'+num).val($(this).attr("data-cod"));
            $('#jobid_'+num).val($(this).attr("data-id"));
            e.preventDefault();
        });

        var slurl = "{{ url('sales_invoice/salesman_data/') }}";
        $(document).on('click', 'input[name="salesman[]"]', function(e) {
            var res = this.id.split('_');
            var curNum = res[1];
            $('#salesmanData').load(slurl+'/'+curNum, function(result) {
                $('#myModal').modal({show:true});
            });
        });

        $(document).on('click', '#salesman_modal .salesmanRow', function(e) {
            var num =$('#num').val();
            $('#salesman_'+num).val($(this).attr("data-name"));
            $('#salesmanid_'+num).val($(this).attr("data-id"));
            e.preventDefault();
        });

        // Reference modal
        $(document).on('click', '.ref-invoice', function(e) {
            e.preventDefault();
            var res = this.id.split('_');
            var curNum = res[1];

            if( $('#groupid_'+curNum).val()=='CUSTOMER') {
                var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
                $('#invoiceData').load(url+'/'+curNum, function() { $('#myModal').modal({show:true}); });
            } else if( $('#groupid_'+curNum).val()=='SUPPLIER' ) {
                var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
                $('#invoiceData').load(url+'/'+curNum, function() { $('#myModal').modal({show:true}); });
            } else if( $('#groupid_'+curNum).val()=='VATIN' || $('#groupid_'+curNum).val()=='VATOUT') {
                var url = "{{ url('account_enquiry/os_bills/') }}/"+$('#draccountid_'+curNum).val();
                $('#invoiceData').load(url+'/'+curNum, function() { $('#myModal').modal({show:true}); });
            }
        });

        $(document).on('click', '.add-invoice', function(e)  {
            e.preventDefault();
            var refs = [], amounts = [], type = [], ids = [], actamt = [], invid = [], btype = [];
            $("input[name='tag[]']:checked").each(function() {
                var res = this.id.split('_');
                var curNum = res[1];
                ids.push($(this).val());
                refs.push( $('#refid_'+curNum).val() );
                amounts.push( $('#lineamnt_'+curNum).val() );
                type.push( $('#trtype_'+curNum).val() );
                actamt.push( $('#hidamt_'+curNum).val() );
                invid.push( $('#sinvoiceid_'+curNum).val() );
                btype.push( $('#billtype_'+curNum).val() );
            });

            var no = $('#bnum').val();
            var j = 0; rowNum = parseInt(no,10);
            var drac = $('#draccount_'+no).val();
            var dracid = $('#draccountid_'+no).val();
            var rnum = $('#rowNum').val();

            $.each(refs,function(i) {
                if(j>0) {
                    var controlForm = $('.controls .itemdivPrnt'),
                        currentEntry = $('.btn-add-item').parents('.itemdivChld:first'),
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);
                    rowNum++;
                    rnum++;

                    newEntry.find('label.error').remove();
                    newEntry.find('.is-invalid').removeClass('is-invalid');
                    newEntry.find('.is-valid').removeClass('is-valid');

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
                    newEntry.find($('.salem')).attr('id', 'salem_' + rowNum);
                }
                $('#draccount_'+no).val(drac);
                $('#draccountid_'+no).val(dracid);
                $('#ref_'+no).val( refs[i] );
                $('#amount_'+no).val(amounts[i]);
                $('#invid_'+no).val( ids[i] );
                $('#acnttype_'+no).find('option').remove().end().append('<option value="'+type[i]+'">'+type[i]+'</option>');
                $('#actamt_'+no).val( actamt[i] );
                $('#invoiceid_'+no).val( invid[i] );
                $('#biltyp_'+no).val( btype[i] );

               addRulesForRow(no);
                j++; no++;
            });
            getNetTotal();

            $('#is_onaccount').val(0);
        });

        // Enable voucher no edit
        $('.input-group-addon').on('click', function(e) {
            e.preventDefault();
            $('#voucher_no').attr("readonly", false);
        });

        $('input,select').keydown( function(e) {
            var key = e.which || e.keyCode || 0;
            if(key == 13) {
                e.preventDefault();
                var inputs = $(this).closest('form').find(':input:visible');
                inputs.eq( inputs.index(this)+ 1 ).focus();
            }
        });

        $('.onacnt_icheck').on('ifChecked', function(){
            $("[name='reference[]']").val('Adv.');
            $('.acname').each(function(){
                var n = this.id.split('_')[1];
                if($('#groupid_'+n).val()=='CUSTOMER') {
                    $('#ref_'+n).attr('readonly', true);
                }
            });
        });

        $('.onacnt_icheck').on('ifUnchecked', function(){
            $("[name='reference[]']").val('');
            $("[name='reference[]']").attr('readonly', false);
        });

    }); // document.ready


    /**** ---------- Existing helpers kept outside ready ---------- ****/

    function getNetTotal() {
        var drLineTotal = 0; var crLineTotal = 0;
        $('.jvline-amount').each(function() {
            var res = this.id.split('_');
            var curNum = res[1];
            if( $('#acnttype_'+curNum+' option:selected').val()=='Dr' ) {
                drLineTotal += parseFloat( ($(this).val()==='')?0:$(this).val() );
            } else if( $('#acnttype_'+curNum+' option:selected').val()=='Cr' ) {
                crLineTotal += parseFloat( ($(this).val()==='')?0:$(this).val() );
            }
        });
        var difference = drLineTotal - crLineTotal;
        $("#debit").val(drLineTotal.toFixed(2));
        $("#credit").val(crLineTotal.toFixed(2));
        $("#difference").val(difference.toFixed(2));

        if(window.validator){
            window.validator.element('#credit'); // refresh equalToDebit rule
        }
    }

    var popup;
    function getDrAccount(e) {
        var res = e.id.split('_');
        var curNum = res[1];
        var itmurl = "{{ url('account_master/get_all_account/') }}/"+curNum;
        popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
        popup.focus();
        return false;
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

    function checkChequeNo(curNum) {
        var bank = $('#bankid_'+curNum+' option:selected').val();
        var chqno = $('#chkno_'+curNum).val();
        var ac = $('#partyac_'+curNum).val();

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

    // recurring kept
    $('.rcEntry').toggle(); $('.toRc').toggle();
    $(document).on('change', '#jvtype', function() {
        $('.rcEntry').toggle();
        $('.toRc').toggle();
    });

    $(document).on('click', '.addRecu', function(e) {
        e.preventDefault();
        let vno = $('#voucher_no').val();
        let vdate = $('#voucher_date').val();
        let per = $('#rcperiod').val();

        var formData = $(".itemdivPrnt :input").serialize();
        formData += '&vno='+encodeURIComponent(vno)+'&vdate='+encodeURIComponent(vdate)+'&per='+encodeURIComponent(per);
        $.ajax({
            url: "{{ url('journal/recurring_add/') }}",
            type: 'post',
            data: formData,
            success: function(data) {
                $('.toRc').html(data);
            }
        });
    });
    </script>
@stop