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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link type="text/css" href="{{asset('assets/css/buttons_sass.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/advbuttons.css')}}" rel="stylesheet">
        <!--end of page level css-->
    <style>
        .small-i { font-size: 75%; }
    </style>
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
                
                <form role="form" method="POST" name="frmUtility" id="frmUtility">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="pono" id="pono">
					<input type="hidden" name="sono" id="sono">
					<input type="hidden" name="dono" id="dono">
					<input type="hidden" name="sino" id="sino">
					<input type="hidden" name="pino" id="pino">
				</form>
								
				<div class="col-lg-12">
					<ul class="nav  nav-tabs ">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">Inventory</a>
                        </li>
                        <li>
                            <a href="#tab2" data-toggle="tab">Utility2</a>
                        </li>
                        <li>
                            <a href="#tab3" data-toggle="tab">Utility3</a>
                        </li>
                       <li>
                            <a href="#tab4" data-toggle="tab">Utility4</a>
                        </li>
					
				</ul>
				
				<div class="tab-content m-t-12">
    				<div id="tab1" class="tab-pane fade active in">
    							<div class="row">
    								<div class="col-xs-12 col-md-12">
                                      <div class="col-lg-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    <i class="fa fa-fw fa-file-text-o"></i> Inventory Log Update(Document wise)
                                                </h3>
                                                
                                            </div>
    									<div class="panel-body">
    									    <div class="well well-sm">
                                                <i>Help Info: For updating the inventory log by document.</i>
                                            </div>
    									    <div class="flatbuttons_small">
                                                <ul>
                                                    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('SDO')">Supplier DO</button></li></n>
        										    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('PI')">Purchase Invoice</button></li>
        										    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('PR')">Purchase Return</button></li>
        										 </ul>
    										 </div>
    										 
    										 <div class="flatbuttons_small">
                                                <ul>
                                                    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('CDO')">Customer DO</button></li>
        										    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('SI')">Sales Invoice</button></li>
        										    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('SR')">Sales Return</button></li>
        										 </ul>
    										 </div>
    										 
    										 <div class="flatbuttons_small">
                                                <ul>
                                                    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('GI')">Goods Issued</button></li>
        										    <li><button type="button" class="btn btn-info button-rounded" onClick="InventoryLogUpdate('GR')">Goods Return</button></li>
        										 </ul>
    										 </div>
    									</div>
    								</div>
    							</div>
    							
    							
    							<div class="col-lg-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    <i class="fa fa-fw fa-file-text-o"></i> Inventory Log Update(All Transactions)
                                                </h3>
                                                
                                            </div>
    									<div class="panel-body">
    									    <div class="well well-sm">
                                                <i>Help Info: For updating the inventory log with all transactions.</i>
                                            </div>
    										<div class="flatbuttons_small">
                                                <ul>
                                                    <li style="width:180px !important;"><i class="small-i">Update Stock</i> <button type="button" class="btn btn-info button-rounded" onClick="updateStock()">Update Stock Items</button></li>
        										    
        										    <li style="width:180px !important;"><i class="small-i">Update Avg. Cost & Sales Cost</i><button type="button" class="btn btn-info button-rounded" onClick="updateAvgCost()">Update Average Cost</button></li>
        										    
        										    <li style="width:180px !important;"><i class="small-i">Update Cost of Sales Trns.</i><button type="button" class="btn btn-info button-rounded" onClick="updateCA()">Refresh Cost of Sale</button></li>
        										 </ul>
    										 </div>
    										 
    										
    									</div>
    								</div>
    							</div>
    							
    						</div>
    					</div>
    				</div>
    				
    				
    				<div id="tab2" class="tab-pane fade">
    							<div class="row">
    								<div class="col-md-12">
    									<div class="panel panel-primary">
    									<div class="panel-heading">
    										<h3 class="panel-title"><i class="fa fa-fw fa-crosshairs"></i>Utility2</h3>
    									</div>
    									<div class="panel-body">
    									    {{--<div class="well well-sm">
                                                <i>Help Info: For updating the inventory log with all transactions.</i>
                                            </div>
    										<div class="flatbuttons_small">
                                                <ul>
                                                    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateStock()">Update Stock Items</button></li>
                                                    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateStockLocation()">Update Stock in Location</button></li>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateAvgCost()">Update Average Cost</button></li>
        										 </ul>
    										 </div>
    										 
    										 <div class="flatbuttons_small">
                                                <ul>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateCOS('SI')">Update Cost of Sale(SI)</button></li>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateCOS('SR')">Update Cost of Sale(SR)</button></li>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateCA()">Refresh Cost of Sale</button></li>
        										 </ul>
    										 </div>--}}
    										 
    										 {{--<div class="flatbuttons_small">
                                                <ul>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateSIH('PI')">Update Stock in Hand(PI)</button></li>
        										    <li style="width:200px !important;"><button type="button" class="btn btn-info button-rounded" onClick="updateSTH('PR')">Update Stock in Hand(PR)</button></li>
        										 </ul>
    										 </div>--}}
    									</div>
    								</div>
    							</div>
    						</div>
    				</div>
    				
    				<div id="tab3" class="tab-pane fade">
    							<div class="row">
    								<div class="col-md-12">
    									<div class="panel panel-primary">
    									<div class="panel-heading">
    										<h3 class="panel-title"><i class="fa fa-fw fa-crosshairs"></i>Utility3</h3>
    									</div>
    									<div class="panel-body">
    										
    									</div>
    								</div>
    							</div>
    						</div>
    				</div>
    				
    				<div id="tab4" class="tab-pane fade">
    							<div class="row">
    								<div class="col-md-12">
    									<div class="panel panel-primary">
    									<div class="panel-heading">
    										<h3 class="panel-title"><i class="fa fa-fw fa-crosshairs"></i>Utility4</h3>
    									</div>
    									<div class="panel-body">
    										
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
<!-- end of page level js -->
<script>

function InventoryLogUpdate(doc) {
    document.frmUtility.action="{{ url('utilities/inventory-log-update') }}/"+doc;
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

function updateCA() {
	document.frmUtility.action="{{ url('utilities/update/CA') }}";
	document.frmUtility.submit();
}

function updateCOS(doc) {
    document.frmUtility.action="{{ url('utilities/update-cos') }}/"+doc;
	document.frmUtility.submit();
}

function updateSIH(doc) {
    document.frmUtility.action="{{ url('utilities/update-sih') }}/"+doc;
	document.frmUtility.submit();
}

</script>

@stop

