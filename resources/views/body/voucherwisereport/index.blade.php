@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--page level css -->

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Voucherwise Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Voucherwise Report</a>
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
            <div class="col-lg-12">
               <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> Voucherwise Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmReportSearch" id="frmReportSearch" action="{{ url('voucherwise_report/print') }}" target="_blank">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" value="{{$datefrom}}" data-language='en' id="date_from" class="form-control" autocomplete="off">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' value="{{$dateto}}" id="date_to" class="form-control" autocomplete="off">
									</div>
									<div class="col-xs-6">
										<span>Type of Voucher:</span>
										<select id="voucher_type" class="form-control select2" style="width:100%" name="voucher_type">
											<option value="">Select Voucher...</option>
											<option value="ALL" <?php if($type=='ALL') echo 'selected';?>>All Transaction</option>
											<option value="JV" <?php if($type=='JV') echo 'selected';?>>JV - Journal Voucher</option>
											<option value="PI" <?php if($type=='PI') echo 'selected';?>>PI - Purchase Invoice</option>
											<option value="SI" <?php if($type=='SI') echo 'selected';?>>SI - Sales Invoice</option>
											<option value="PIR" <?php if($type=='PIR') echo 'selected';?>>PIR - Purchase Rental</option>
											<option value="SIR" <?php if($type=='SIR') echo 'selected';?>>SIR - Sales Rental</option>
											<option value="RV" <?php if($type=='RV') echo 'selected';?>>RV - Receipt Voucher</option>
											<option value="PV" <?php if($type=='PV') echo 'selected';?>>PV - Payment Voucher</option>
											<option value="PC" <?php if($type=='PC') echo 'selected';?>>PC - Petty Cash Voucher</option>
											<option value="PR" <?php if($type=='PR') echo 'selected';?>>PR - Purchase Return</option>
											<option value="SR" <?php if($type=='SR') echo 'selected';?>>SR - Sales Return</option>
											<option value="DB" <?php if($type=='DB') echo 'selected';?>>PDC Reveived Transfer</option>
											<option value="CB" <?php if($type=='CB') echo 'selected';?>>PDC Issued Transfer</option>
											<option value="GI" <?php if($type=='GI') echo 'selected';?>>Goods Issued Note</option>
											<option value="GR" <?php if($type=='GR') echo 'selected';?>>Goods Return Note</option>
											<option value="PIN" <?php if($type=='PIN') echo 'selected';?>>PIN - Purchase Invoice(Non-Stock)</option>
											<option value="SIN" <?php if($type=='SIN') echo 'selected';?>>SIN - Sales Invoice(Non-Stock)</option>
											<option value="STI" <?php if($type=='STI') echo 'selected';?>>Stock Transfer In</option>
											<option value="STO" <?php if($type=='STO') echo 'selected';?>>Stock Transfer Out</option>
											<option value="MV" <?php if($type=='MV') echo 'selected';?>>Manufacture Voucher</option>
											<option value="PS" <?php if($type=='PS') echo 'selected';?>>Purchase Split</option>
											<option value="PSR" <?php if($type=='PSR') echo 'selected';?>>Purchase Split Return</option>
											<option value="SS" <?php if($type=='SS') echo 'selected';?>>Sales Split</option>
											<option value="SSR" <?php if($type=='SSR') echo 'selected';?>>Sales Split Return</option>
											<option value="MJV" <?php if($type=='MJV') echo 'selected';?>>Manual Journal</option>
										</select>
										<br>
										<div class="col-xs-10" style="border:0px solid red;" id="invoice"></div>
										
                                                
										@if($isdept)
										<span>Department:</span>
										<select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											<option value="">Select Department...</option>
											@foreach($departments as $dept)
											<option value="{{$dept->id}}">{{$dept->name}}</option>
											@endforeach
										</select>
										@endif
										<span></span><br/>
										<!--<button type="submit" class="btn btn-primary">Search</button> Preview-->
										<div class="col-xs-12" align="right"><button type="button" class="btn btn-primary" onClick="preview();">Search</button></div>
									</div>
								</div>
							</form>
                           
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
<!-- end of page level js -->
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#invoice_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#invoice_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

 $("#voucher_type").change(function () { console.log('hhhh');
var value = $(this).val(); var toAppend = '';
     toAppend = "From :<input type='text' name='invoice_from' data-language='en' id='invoice_from' autocomplete='off'>&nbsp;To :<input type='textbox' name='invoice_to' data-language='en' id='invoice_to' autocomplete='off' >"; $("#invoice").html(toAppend); return;
 });

function funDelete(id) {
	var con = confirm('Are you sure delete this account master?');
	if(con==true) {
		var url = "{{ url('account_master/delete/') }}";
	 location.href = url+'/'+id;
	}
}

function preview() {
	document.frmReportSearch.action = "{{ url('voucherwise_report/print') }}";
	document.frmReportSearch.submit();
}

</script>
@stop
