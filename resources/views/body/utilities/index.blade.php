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
            <!--section starts-->
            <h1>
                Utilities
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Utilities</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
		@if(Session::has('error'))
		<div class="alert alert-warning">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
					
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-fw fa-folder"></i> Utilities
                                    </h3>
                                    <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>

                                </span>
                                </div>
								<form role="form" method="POST" name="frmUtility" id="frmUtility">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pono" id="pono">
								<input type="hidden" name="sono" id="sono">
								<input type="hidden" name="dono" id="dono">
								<input type="hidden" name="sino" id="sino">
								<input type="hidden" name="pino" id="pino">
								
								
								<div class="panel-body">
								<div class="row">
									<button type="button" class="btn btn-primary btn-lg center-block" onClick="updateAvgCost()">
										Update Item Average Cost
									</button>
									
								</div>
							
								</div>
								
								<div class="panel-body">
								<div class="row">
									<button type="button" class="btn btn-primary btn-lg center-block" onClick="updateStock()">
										Update Item Stock
									</button>
								</div>
							
								</div>
								
								<div class="panel-body">
								<div class="row">
									<button type="button" class="btn btn-primary btn-lg center-block" onClick="updateStockLocation()">
										Update Item Stock in Location
									</button>
								</div>
							
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateCB()">
                                            Update Account Closing &nbsp; Balance
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePOi()">
                                            Update Purchase Order Items
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSOi()">
                                            Update Sales Order Items
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateDOi()">
                                            Update Delivery Order
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSIi()">
                                            Update Sales Invoice Items
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePIi()">
                                            Update Purchase Invoice Items
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSI()">
                                            Update Sales Invoice Bills
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePI()">
                                            Update Purchase Invoice Bills
                                        </button>
									</div>
								
								</div>
								
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateCA()">
                                            Refresh Cost Account Effect
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePDCR()">
                                            Refresh PDCRs
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePDCI()">
                                            Refresh PDCIs
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
								
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSDO()">
                                            Refresh SDO
                                        </button>
									</div>
								
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updatePITrns()">
                                            Update Purchase Invoice Transactions
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSITrns()">
                                            Update Sales Invoice Transactions
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="ceckAllAccounts()">
                                            Cross Check All Accounts
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="updateSSTrns()">
                                            Update All Sales Split Transactions
                                        </button>
									</div>
								</div>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="removeSIduplicate()">
                                            Remove Duplicate Sales Invoices
                                        </button>
									</div>
								</div>
								
												
								<!--<hr/>
								
								<div class="panel-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" onClick="clearDB()">
                                            Clear Database
                                        </button>
									</div>
								</div>-->
								
							</form>
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
<!-- end of page level js -->
<script>

function updateCB() {
	document.frmUtility.action="{{ url('utilities/update/CB') }}";
	document.frmUtility.submit();
}

function updateStock() {
	document.frmUtility.action="{{ url('utilities/update/stock') }}";
	document.frmUtility.submit();
}

function updateStockLocation() {
	document.frmUtility.action="{{ url('utilities/update/stockLoc') }}";
	document.frmUtility.submit();
}


function updateAvgCost() {
	document.frmUtility.action="{{ url('utilities/update/avgcost') }}";
	document.frmUtility.submit();
}

function clearDB() {
	var con = confirm('Are you sure want to clear the database?');
	if(con) {
		let pswd = prompt('Please enter password'); 
		if(pswd=='profit@2020') { alert('ok');
			document.frmUtility.action="{{ url('utilities/cleardb') }}";
			document.frmUtility.submit();
		} else {
			alert('Invalid password!');
			return false;
		}
	}
}

function updatePOi() {
	
	let pono = prompt('Please enter PO No.'); 
	$('#pono').val(pono);
	if(pono!=null) {
		document.frmUtility.action="{{ url('utilities/update/POi') }}";
		document.frmUtility.submit();
	} else 
		return false;
}


function updateSOi() {
	
	let sono = prompt('Please enter SO No.'); 
	$('#sono').val(sono);
	if(sono!=null) {
		document.frmUtility.action="{{ url('utilities/update/SOi') }}";
		document.frmUtility.submit();
	} else 
		return false;
}

function updateDOi() {
	
	let dono = prompt('Please enter DO No.'); 
	$('#dono').val(dono);
	if(dono!=null) {
		document.frmUtility.action="{{ url('utilities/update/DOi') }}";
		document.frmUtility.submit();
	} else 
		return false;
}

function updateSIi() {
	
	let sino = prompt('Please enter SI No.'); 
	$('#sino').val(sino);
	if(sino!=null) {
		document.frmUtility.action="{{ url('utilities/update/SIi') }}";
		document.frmUtility.submit();
	} else 
		return false;
}

function updatePIi() {
	
	let pino = prompt('Please enter PI No.'); 
	$('#pino').val(pino);
	if(pino!=null) {
		document.frmUtility.action="{{ url('utilities/update/PIi') }}";
		document.frmUtility.submit();
	} else 
		return false;
}

function updateSI() {
	document.frmUtility.action="{{ url('utilities/update/SI') }}";
	document.frmUtility.submit();
}

function updatePI() {
	document.frmUtility.action="{{ url('utilities/update/PI') }}";
	document.frmUtility.submit();
}

function updateCA() {
	document.frmUtility.action="{{ url('utilities/update/CA') }}";
	document.frmUtility.submit();
}

function updatePDCR() {
	document.frmUtility.action="{{ url('utilities/update/PDCR') }}";
	document.frmUtility.submit();
}

function updatePDCI() {
	document.frmUtility.action="{{ url('utilities/update/PDCI') }}";
	document.frmUtility.submit();
}

function updateSDO() {
	document.frmUtility.action="{{ url('utilities/update/SDO') }}";
	document.frmUtility.submit();
}

function updatePITrns() {
	document.frmUtility.action="{{ url('utilities/update/PITrns') }}";
	document.frmUtility.submit();
}

function updateSITrns() {
	document.frmUtility.action="{{ url('utilities/update/SITrns') }}";
	document.frmUtility.submit();
}

function ceckAllAccounts() {
	document.frmUtility.action="{{ url('utilities/check-accounts') }}";
	document.frmUtility.submit();
}

function updateSSTrns() {
	document.frmUtility.action="{{ url('utilities/update/SSTrns') }}";
	document.frmUtility.submit();
}

function removeSIduplicate() {
	document.frmUtility.action="{{ url('utilities/update/remSI') }}";
	document.frmUtility.submit();
}


</script>

@stop

