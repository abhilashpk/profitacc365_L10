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
                Data Remove
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i>  Data Management
                    </a>
                </li>
                <li>
                    <a href="#">Data Remove</a>
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
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Data Remove
                            </h3>
                           
                        </div>
                        <div class="panel-body">
						<h4>Remove Custom Data</h4> <i>Note: Checked section will be remove the data.</i><hr/>
						
                            <form class="form-horizontal" role="form" method="POST" name="frmData" id="frmData">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Customers</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Customers" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Suppliers</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Suppliers" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Item Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Item" checked>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Purchase Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PO" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Purchase Invoice</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PI" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Purchase Return</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PR" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Customer Leads</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CL" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Customer Enquiry</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CE" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Quotation Sales/Estimate</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="QS" checked>
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Sales/Job Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SO" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Deliver Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="DO" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Production Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PrOD" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Sales/Job Invoice</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SI" checked>
                                    </div>
									
									 <label for="input-text" class="col-sm-3 control-label">Sales Return</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SR" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Goods Issued</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="GI" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Goods Return</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="GR" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Stock Transfer in</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="TI" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Stock Transfer out</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="TO" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Location Transfer</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="LT" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Journal</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="JV" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Customer Receipt</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="RV" checked>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Supplier Payment</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PV" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Petty Cash</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PC" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Employee</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="E" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Wage Entry</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="WE" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Document Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="DM" checked>
                                    </div>
									 <label for="input-text" class="col-sm-3 control-label">Assets Issued</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="AI" checked>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Vehicle Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="VM" checked>
                                    </div>
									 <label for="input-text" class="col-sm-3 control-label">Job Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="JM" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Salesman</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SM" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Terms</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="TM" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Account Opeing Balance</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="AcOB" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Stock Opeing Quantity</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="onacnt_icheck" name="datacol[]" value="OQ" checked>
                                    </div>
                                </div>
								
							    
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Credit Note</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CN" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Debit Note</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="DN" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Purchase Quotation</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="QP" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Purchase Split</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PS" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Sales Split</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SS" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Purchase Enquiry</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PE" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Consignment Location</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CGL" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">CRM Followups</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CRM" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Manual JV</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="MJV" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Building Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="BM" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label"> Flat Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="FM" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Duration</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Dr" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Contract Type</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="ConT" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Contract Entry</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="ConE" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Division</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Dv" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Print Contract</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="ConP" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Machine Read</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="MR" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Package Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PkgM" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Job Order Package </label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PkgJB" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Machine Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Mch" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Paper Master</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="Ppr" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Maintenance System Customers</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CUST" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">MS Area</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="AL" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">MS Technician</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="TL" checked>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">MS Work Type</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="WTL" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">MS Project</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="PL" checked>
                                    </div>
									 <label for="input-text" class="col-sm-3 control-label">MS Work Enquiry</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="WEL" checked>
                                    </div>
                                </div>

                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">MS Work Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="WOL" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">MS Location</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="MSL" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">MS Work Order Time</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="MSWOT" checked>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Manufacture</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="MFG" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Goods Receipt Note</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="GRN" checked>
                                    </div>

                                    <label for="input-text" class="col-sm-3 control-label">Cargo Consignee</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CCON" checked>
                                    </div>
                                </div>

                                <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Cargo Shipper</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SHP" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Cargo Collection Type</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CCT" checked>
                                    </div>

                                    <label for="input-text" class="col-sm-3 control-label">Cargo Delivery Type</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CDT" checked>
                                    </div>
                                </div>
								
                                 <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Cargo Vehicle</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CV" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Cargo Destination</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CDES" checked>
                                    </div>

                                    <label for="input-text" class="col-sm-3 control-label">Cargo Receipt</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="RCT" checked>
                                    </div>
                                </div>

                                 <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Cargo Despatch Bill</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CDB" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Cargo Way Bill</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CWB" checked>
                                    </div>
                                     <label for="input-text" class="col-sm-3 control-label">Rental Supplier's Driver</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="RSD" checked>
                                    </div>
                                </div>

                                <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Rental Customer's Driver</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="RCD" checked>
                                    </div>
									
									<label for="input-text" class="col-sm-3 control-label">Rental Purchase</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="REP" checked>
                                    </div>
                                     <label for="input-text" class="col-sm-3 control-label">Rental Sales</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                            <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="RES" checked>
                                    </div>
                                </div>
                             <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">Item Template</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="IT" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">CRM Template</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CRMT" checked>
                                    </div>
                                    <label for="input-text" class="col-sm-3 control-label">CRM Info</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CRMIN" checked>
                                    </div>
									
                            </div>
                             <div class="form-group">
									<label for="input-text" class="col-sm-3 control-label">SO Job Order</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SOJ" checked>
                                    </div>
									<label for="input-text" class="col-sm-3 control-label">Sales Order Booking</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="SOB" checked>
                                    </div>
                                    
                                    <label for="input-text" class="col-sm-3 control-label">Contra Voucher</label>
                                    <div class="col-sm-1">
                                        <label class="radio-inline iradio">
                                        <input type="checkbox" class="onacnt_icheck" name="datacol[]" value="CV" checked>
                                    </div>
                                    
									
                            </div>

                                

								<br/>								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label"></label>
                                    <div class="col-sm-8">
                                        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#data_modal'>Submit</button>
                                        <a href="{{ url('dashboard') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                      <hr/>                      
                                <div class="panel-body">
								<h4>Remove Whole Data</h4> <i>Note: The whole data will be remove.</i><br/>
                                    <div class="row">
                                        <button type="button" class="btn btn-primary btn-lg center-block" data-toggle='modal' data-target='#alldata_modal'>
                                            Remove All
                                        </button>
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
        <div id="data_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Data Removal</h4>
					</div>
					<div class="modal-body" >
                    <h4>Are you sure want to remove the data from the selected sections?</h4>
					</div>
					<div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#confirm_modal' data-dismiss="modal" >OK</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <div id="confirm_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body" id="confirm_data">
                    <label for="pwd">Enter the Password:</label>
                      <input type="password" id="pwd" name="pwd" placeholder="" >
                    </div>
					<div class="modal-footer">
                        <button type="button" class="btn btn-primary" onClick="clearDBcustom()">OK</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <div id="alldata_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Data Removal</h4>
					</div>
					<div class="modal-body" >
                    <h4>Are you sure want to remove all data?</h4>
					</div>
					<div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#allconfirm_modal'data-dismiss="modal">OK</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

        <div id="allconfirm_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body" id="allconfirm_data">
                    <label for="pwd">Enter the Password:</label>
                      <input type="password" id="pwd1" name="pwd1" placeholder="" >
                    </div>
					<div class="modal-footer">
                        <button type="button" class="btn btn-primary" onClick="clearDB()">OK</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
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

function clearDB() {
	//var con = confirm('Are you sure want to remove all data?'); 
	//if(con) {
		//let pswd = prompt('Please enter password'); 
        let pswd=$('#allconfirm_data #pwd1').val();
        console.log(pswd);
		if(pswd=='profit@2020') { 
			document.frmData.action="{{ url('data_remove/cleardb') }}";
			document.frmData.submit();
		} else {
			alert('Invalid password!');
			return false;
		}
	//}
}

function clearDBcustom() {
	//var con = confirm('Are you sure want to remove the data from the selected sections?');
	//if(con) {
		//let pswd = prompt('Please enter password'); 
        let pswd=$('#confirm_data #pwd').val();
		if(pswd=='profit@2020') { 
			document.frmData.action="{{ url('data_remove/cleardb_custom') }}";
			document.frmData.submit();
		} else {
			alert('Invalid password!');
			return false;
		}
	//}
}


</script>

@stop
