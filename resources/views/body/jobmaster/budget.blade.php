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
		<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		}
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
            Project Budgeting
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Project Budgeting
                    </a>
                </li>
                <li>
                    <a href="#">Project Budgeting</a>
                </li>
                <li class="active">
                    Add
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
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
					
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Project Budgeting
                            </h3>
							
							<div class="pull-right">
						
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseSplit" id="frmPurchaseSplit" action="{{ url('jobmaster/budgsave/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
							
								
				
								
								
							 	<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">JOB</label>
                                    <div class="col-sm-5">
                                        <select id="select21" class="form-control select2"  style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}" <?php if($job['id']==old('job_id')) echo 'selected'; ?>>{{ $job['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
							
								<!-- <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                    <select id="select21" class="form-control select2"  style="width:100%" name="job_id[]">
											<option value="">Select Job No...</option>
											@foreach($jobs as $job)
												<option value="{{$job['id']}}">{{$job['name']}}</option>
											@endforeach
										</select>
                                    </div>
                               </div> -->
								
								<br/>
									<!-- income -->
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Project Cost Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="10%" class="itmHd">
											<span class="small">A/C Name</span>
										</th>
										<th width="15%" class="itmHd">
											<span class="small">Description</span>
										</th>
									
										<th width="5%" class="itmHd">
											<span class="small">Amount</span>
										</th>
									
									
										
									</tr>
									</thead>
								</table>
									<div class="itemdivPrntinc">
										<div class="itemdivChldinc">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="18%" >
														<input type="hidden" name="accountinc_id[]" id="acntidinc_1">
														<input type="text" id="accodinc_1" name="acccinc_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#acinc_modal" placeholder="A/C Name">
													</td>
													<td width="29%">
														<input type="text" name="iteminc_description[]" id="acdesinc_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													
												
													<td width="25%">
														<input type="number" id="itmcstinc_1" autocomplete="off" step="any" name="costinc[]" class="form-control lineinc-cost" placeholder="Amount">
													</td>
												
												
													<td width="1%">
														<input type="hidden" id="itmttlinc_1" step="any" name="lineinc_total[]" class="form-control lineinc-total" readonly placeholder="Total">
														<input type="hidden" id="itmtotinc_1" name="iteminc_total[]">
													</td>
													<td width="1%">
														<button type="button" class="btn-success btn-addinc-item" >
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
															 <button type="button" class="btn-danger btn-removeinc-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
													</td>
												</tr>
											</table>
										</div>
									</div>
								
								</fieldset>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label"></span>	<input type="number" id="totalinc" step="any" name="totalinc" class="form-control spl" readonly value="{{old('totalinc')}}" placeholder="0">
										</div>
										
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" step="any" id="discountinc" name="discountinc" autocomplete="off" class="form-control spl discount-cal" value="{{old('discountinc')}}" placeholder="0">
										</div>
										
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><span id="subttle" class="small"></span></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="subtotalinc" step="any" name="subtotalinc" class="form-control spl" readonly value="{{old('subtotalinc')}}" placeholder="0">
										</div>
									
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" step="any" id="vatinc" name="vatinc" class="form-control spl" placeholder="0" value="{{old('vat')}}" readonly>
										</div>
										
									</div>
                                </div>
								
								<input type="hidden" step="any" id="other_costinc" name="other_costinc" readonly class="form-control" value="{{old('other_costinc')}}" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fcinc" name="other_cost_fcinc" readonly class="form-control spl" value="{{old('other_cost_fcinc')}}" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hidinc" name="net_amount_hidinc" class="form-control" value="{{old('net_amount_hidinc')}}" readonly>
											<input type="hidden" step="any" id="net_amountinc" name="net_amount" class="form-control spl" readonly value="{{old('net_amountinc')}}" placeholder="0">
										</div>
									
									</div>
                                </div>
                                	<hr/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Project Income Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="10%" class="itmHd">
											<span class="small">A/C Name</span>
										</th>
										<th width="15%" class="itmHd">
											<span class="small">Description</span>
										</th>
									
										<th width="5%" class="itmHd">
											<span class="small">Amount</span>
										</th>
									
									
										
									</tr>
									</thead>
								</table>
									<div class="itemdivPrnt">
										<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="18%" >
														<input type="hidden" name="account_id[]" id="acntid_1">
														<input type="text" id="accod_1" name="accc_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#ac_modal" placeholder="A/C Name">
													</td>
													<td width="29%">
														<input type="text" name="item_description[]" id="acdes_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													
												
													<td width="25%">
														<input type="number" id="itmcst_1" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Amount">
													</td>
												
												
													<td width="1%">
														<input type="hidden" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_1" name="item_total[]">
													</td>
													<td width="1%">
														<button type="button" class="btn-success btn-add-item" >
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
															 <button type="button" class="btn-danger btn-remove-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
													</td>
												</tr>
											</table>
										</div>
									</div>
								
								</fieldset>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label"></span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{old('total')}}" placeholder="0">
										</div>
										
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{old('discount')}}" placeholder="0">
										</div>
										
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><span id="subttle" class="small"></span></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="subtotal" step="any" name="subtotal" class="form-control spl" readonly value="{{old('subtotal')}}" placeholder="0">
										</div>
									
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" step="any" id="vat" name="vat" class="form-control spl" placeholder="0" value="{{old('vat')}}" readonly>
										</div>
										
									</div>
                                </div>
								
								<input type="hidden" step="any" id="other_cost" name="other_cost" readonly class="form-control" value="{{old('other_cost')}}" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control spl" value="{{old('other_cost_fc')}}" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" class="form-control" value="{{old('net_amount_hid')}}" readonly>
											<input type="hidden" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{old('net_amount')}}" placeholder="0">
										</div>
									
									</div>
                                </div>
								<hr/>
							
								<hr/>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('jobmster') }}" class="btn btn-danger">Cancel</a>
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
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> 
							
								<div id="ac_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="acccc_data">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> 

								<div id="acinc_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="accccinc_data">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
							
								<div id="paccount_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="paccount_data">
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                            <div id="supplier_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Supplier</h4>
                                        </div>
                                        <div class="modal-body" id="supplierData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>  
							
							<div id="item_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="item_data">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
                            </form>
							</div>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->
        <script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

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
<script>
"use strict";
var lprice; var packing = 1;
$(document).ready(function () {
	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Job No"
    });

});
 var taxinclude = false; var srvat=0;var srvatinc=0;//<?php echo ($vatdata)?$vatdata->percentage:'0';?>;
 $('#vatdiv_1').val( srvat+'%' );
 $('#vat_1').val( srvat );

 $('#vatdivinc_1').val( srvatinc+'%' );
 $('#vatinc_1').val( srvatinc );
 var dptTxt;
$(document).ready(function () { 
	
	if($('#department_id').length) {
		dptTxt = $( "#department_id option:selected" ).text();
	}
	
	<?php if(old('is_fc')=="1") { ?> $('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $("#subtotal_fc").toggle(); $("#other_cost_fc").toggle(); //$('.oc-amount-fc').toggle();
	});
	<?php } else { ?>
	$("#fc_label").toggle(); $("#c_label").toggle();$("#subtotal_fc").toggle(); $("#other_cost_fc").toggle();
	$("#total_fc").toggle();$("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php } ?>
	
	$('.btn-remove-item').hide(); 
	// $('.btn-removeinc-item').hide(); 
	var urlvchr = "{{ url('purchase_split/checkvchrno/') }}";
	var urlcode = "{{ url('purchase_split/checkrefno/') }}"; //CHNG
	$('.sedePrntItm').toggle();

	

	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();$("#subtotal_fc").toggle();//$('.oc-amount-fc').toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#other_cost_fc").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();$("#subtotal_fc").toggle(); //$('.oc-amount-fc').toggle();
	});
	
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		
		var lineTotal = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		
		$('#total').val(lineTotal.toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
		
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		
		$('#vat').val(vatcur.toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total + vat;
		$('#net_amount').val(netTotal.toFixed(2));
		$('#net_amount_hid').val(netTotal+discount);
		
		if( $('#is_fc').is(":checked") ) { 
			var rate       = parseFloat($('#currency_rate').val());
			var vat        = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
			var fcTotal    = lineTotal*rate;//total * rate;
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var subfc = lineTotal * rate;
			$('#subtotal_fc').val(subfc.toFixed(2));
			var fcTax = vat * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax.toFixed(2));
			$('#net_amount_fc').val(fcNetTotal.toFixed(2));
		}
		
		if(netTotal!='')
			calculateDiscount();
	} 
	function getNetTotalinc() {
		
		var lineTotalinc = 0;
		$( '.lineinc-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amtinc = getLineTotalinc(n);
		  lineTotalinc = lineTotalinc + parseFloat(amtinc.toFixed(2));
		 
		});
		
		$('#totalinc').val(lineTotalinc.toFixed(2));
		$('#subtotalinc').val(lineTotalinc.toFixed(2));
		
		var vatcurinc = 0;
		$( '.vatlineinc-amt' ).each(function() {
			vatcurinc = vatcurinc + parseFloat(this.value);
		});
		
		$('#vatinc').val(vatcurinc.toFixed(2));
		
		var discountinc = parseFloat( ($('#discountinc').val()=='') ? 0 : $('#discountinc').val() );
		var vatinc      = parseFloat( ($('#vatinc').val()=='') ? 0 : $('#vatinc').val() );
		var totalinc 	 = lineTotalinc - discountinc;
		var netTotalinc = totalinc + vatinc;
		$('#net_amountinc').val(netTotalinc.toFixed(2));
		$('#net_amount_hidinc').val(netTotalinc+discountinc);
		
		if( $('#is_fc').is(":checked") ) { 
			var rate       = parseFloat($('#currency_rate').val());
			var vat        = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
			var fcTotal    = lineTotal*rate;//total * rate;
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var subfc = lineTotal * rate;
			$('#subtotal_fc').val(subfc.toFixed(2));
			var fcTax = vat * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax.toFixed(2));
			$('#net_amount_fc').val(fcNetTotal.toFixed(2));
		}
		
		if(netTotalinc!='')
			calculateDiscountinc();
	}
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log('tax2: '+vatcur);
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
		if($('#txincld_'+n+' option:selected').val()==1 ) {
			
			var ln_total	 = 1 * lineCost;
			var taxLineCost  = (ln_total * lineVat) / parseFloat(100+lineVat);
			var lineTotal 	 = ln_total;
			var lineTotalTx  = ( ln_total - taxLineCost );
			
		} else {
			
			var lineTax 	 = (lineCost * lineVat) / 100;
			var taxLineCost	 = 1 * lineTax;
			var lineTotal 	 = 1 * lineCost;
			var lineTotalTx 	 = 1 * lineCost;
		}
		
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2)); //val(lineVat+'% - '+taxLineCost.toFixed(2));
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost.toFixed(2));//(taxLineCost + vatcur).toFixed(2)
		$('#vatlineamt_'+n).val(taxLineCost.toFixed(2));
		$('#itmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	} 
	


	function getLineTotalinc(n) {
	//	console.log('linetotal: '+n);
		
		var lineQuantityinc = parseFloat( ($('#itmqtyinc_'+n).val()=='') ? 0 : $('#itmqtyinc_'+n).val() );
		var lineCostinc 	 = parseFloat( ($('#itmcstinc_'+n).val()=='') ? 0 : $('#itmcstinc_'+n).val() );
		var lineVatinc 	 = parseFloat( ($('#vatinc_'+n).val()=='') ? 0 : $('#vatinc_'+n).val() );
		//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
		if($('#txincld_'+n+' option:selected').val()==1 ) {
			
			var ln_totalinc	 = 1 * lineCostinc;
			var taxLineCostinc  = (ln_totalinc * lineVatinc) / parseFloat(100+lineVatinc);
			var lineTotalinc 	 = ln_totalinc;
			var lineTotalTxinc  = ( ln_totalinc - taxLineCostinc );
			
		} else {
			console.log('tax2: '+lineCostinc);
			var lineTaxinc 	 = (lineCostinc * lineVatinc) / 100;
			var taxLineCostinc	 = 1 * lineTaxinc;
			var lineTotalinc 	 = 1 * lineCostinc;
			var lineTotalTxinc 	 = 1 * lineCostinc;
		}
		
		$('#vatdivinc_'+n).val(lineVatinc+'% - '+taxLineCostinc.toFixed(2)); //val(lineVat+'% - '+taxLineCost.toFixed(2));
		$('#itmttlinc_'+n).val(lineTotalinc.toFixed(2));
		$('#vatinc').val(taxLineCostinc.toFixed(2));//(taxLineCost + vatcur).toFixed(2)
		$('#vatlineamtinc_'+n).val(taxLineCostinc.toFixed(2));
		$('#itmtotinc_'+n).val( lineTotalTxinc.toFixed(2) );
	
		return lineTotalinc;
	} 
	

	function calculateDiscountinc()
	{ 
		var discountinc = parseFloat( ($('#discountinc').val()=='') ? 0 : $('#discountinc').val() );
		var lineTotalinc = parseFloat( ($('#totalinc').val()=='') ? 0 : $('#totalinc').val() );
		var vatTotalinc = parseFloat( ($('#vatinc').val()=='') ? 0 : $('#vatinc').val() );
		
			var subtotalinc = lineTotalinc - discountinc;
			
			var amountTotalinc = 0; var discountAmtinc; var vatLineinc = 0; var vatnetinc = 0; var amountNetinc = 0; var totalinc; var taxincludeinc = false;
			
			$( '.lineinc-total' ).each(function() {
			  var resinc = this.id.split('_');
			  var curNuminc = resinc[1];
			  var vatinc = parseFloat( ($('#vatinc_'+curNuminc).val()=='') ? 0 :  $('#vatinc_'+curNuminc).val() );
			  
			  if($('#txincldinc_'+curNuminc+' option:selected').val()==1 ) {
				  var vatPlusinc = parseFloat(100 + vatinc);
				  totalinc = parseFloat( $('#itmqtyinc_'+curNuminc).val() ) * parseFloat( $('#itmcstinc_'+curNuminc).val() );
			  } else {
				 var vatPlusinc = 100;
				 totalinc = this.value;
			  }
			  
			  discountAmtinc = (totalinc / lineTotalinc) * discountinc;
			  amountTotalinc = totalinc - discountAmtinc;
			  vatLineinc = (amountTotalinc * vatinc) / vatPlusinc;
			  amountNetinc = amountNetinc + amountTotalinc;
			  vatLineinc = parseFloat(vatLineinc.toFixed(2));
			  
			  vatnetinc = parseFloat(vatnetinc + vatLineinc); 
			  $('#vatdivinc_'+curNuminc).val(vatinc+'% - '+vatLineinc);//.toFixed(2)
			  $('#vatlineamtinc_'+curNuminc).val(vatLineinc);//.toFixed(2)
			  $('#itmdsntinc_'+curNuminc).val(discountAmtinc.toFixed(2));
			  
			  if($('#txincldinc_'+curNuminc+' option:selected').val()==1 ) {
				  taxincludeinc = true;
				  vatnetinc = (subtotalinc * vatinc)/vatPlusinc;
			  }
			  
			});
			
			$('#subtotalinc').val(subtotalinc.toFixed(2));
			$('#vatinc').val(vatnetinc.toFixed(2));
			
			if(taxincludeinc==true && discountinc==0) {
				$('#subtotalinc').val( (subtotalinc - vatnetinc).toFixed(2) );
				$('#subttleinc').html('(VAT Exclude)');
				$('#net_amountinc').val( subtotalinc.toFixed(2) );
				
			} else if(taxincludeinc==true && discountinc>0) { 
				$('#subttleinc').html('');
				$('#net_amountinc').val( (parseFloat($('#subtotalinc').val()) - parseFloat($('#vatinc').val()) ).toFixed(2) );
			} else {
				$('#subttleinc').html('');
				$('#net_amountinc').val( (parseFloat($('#subtotalinc').val()) + parseFloat($('#vatinc').val()) ).toFixed(2) );
			}
			
			if( $('#is_fcinc').is(":checked") ) {
				var crateinc = $('#currency_rate').val();
				discountinc = discountinc * crateinc;
				$('#discount_fcinc').val(discountinc.toFixed(2));
				$('#subtotal_fcinc').val( (subtotalinc*crateinc).toFixed(2) );
				$('#vat_fcinc').val( (vatnetinc*crateinc).toFixed(2) );
				$('#net_amount_fcinc').val( (parseFloat($('#subtotal_fcinc').val()) + parseFloat($('#vat_fcinc').val()) ).toFixed(2) );
				
			}
			
		
		return true;
	}
	
	function calculateDiscount()
	{ 
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var lineTotal = parseFloat( ($('#total').val()=='') ? 0 : $('#total').val() );
		var vatTotal = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		
			var subtotal = lineTotal - discount;
			
			var amountTotal = 0; var discountAmt; var vatLine = 0; var vatnet = 0; var amountNet = 0; var total; var taxinclude = false;
			
			$( '.line-total' ).each(function() {
			  var res = this.id.split('_');
			  var curNum = res[1];
			  var vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 :  $('#vat_'+curNum).val() );
			  
			  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
				  var vatPlus = parseFloat(100 + vat);
				  total = parseFloat( $('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val() );
			  } else {
				 var vatPlus = 100;
				 total = this.value;
			  }
			  
			  discountAmt = (total / lineTotal) * discount;
			  amountTotal = total - discountAmt;
			  vatLine = (amountTotal * vat) / vatPlus;
			  amountNet = amountNet + amountTotal;
			  vatLine = parseFloat(vatLine.toFixed(2));
			  
			  vatnet = parseFloat(vatnet + vatLine); 
			  $('#vatdiv_'+curNum).val(vat+'% - '+vatLine);//.toFixed(2)
			  $('#vatlineamt_'+curNum).val(vatLine);//.toFixed(2)
			  $('#itmdsnt_'+curNum).val(discountAmt.toFixed(2));
			  
			  if($('#txincld_'+curNum+' option:selected').val()==1 ) {
				  taxinclude = true;
				  vatnet = (subtotal * vat)/vatPlus;
			  }
			  
			});
			
			$('#subtotal').val(subtotal.toFixed(2));
			$('#vat').val(vatnet.toFixed(2));
			
			if(taxinclude==true && discount==0) {
				$('#subtotal').val( (subtotal - vatnet).toFixed(2) );
				$('#subttle').html('(VAT Exclude)');
				$('#net_amount').val( subtotal.toFixed(2) );
				
			} else if(taxinclude==true && discount>0) { 
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) ).toFixed(2) );
			} else {
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) + parseFloat($('#vat').val()) ).toFixed(2) );
			}
			
			if( $('#is_fc').is(":checked") ) {
				var crate = $('#currency_rate').val();
				discount = discount * crate;
				$('#discount_fc').val(discount.toFixed(2));
				$('#subtotal_fc').val( (subtotal*crate).toFixed(2) );
				$('#vat_fc').val( (vatnet*crate).toFixed(2) );
				$('#net_amount_fc').val( (parseFloat($('#subtotal_fc').val()) + parseFloat($('#vat_fc').val()) ).toFixed(2) );
				
			}
			
		
		return true;
	}

$(function() {	
	var dat = new Date();
    $('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	var rowNuminc = 1;
	console.log('rowNuminc1: '+rowNuminc);
	$(document).on('click', '.btn-addinc-item', function(e)  { 
        rowNuminc++; 



	//	console.log('rowNuminc: '+rowNuminc);
		e.preventDefault(); 
        var controlForminc = $('.controls .itemdivPrntinc'),
            currentEntryinc = $(this).parents('.itemdivChldinc:first'),
            newEntry = $(currentEntryinc.clone()).appendTo(controlForminc);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNuminc); 
			newEntry.find($('input[name="accountinc_id[]"]')).attr('id', 'acntidinc_' + rowNuminc);
			newEntry.find($('input[name="acccinc_code[]"]')).attr('id', 'accodinc_' + rowNuminc);
			newEntry.find($('input[name="iteminc_description[]"]')).attr('id', 'acdesinc_' + rowNuminc);
			//newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNuminc);
			newEntry.find($('input[name="costinc[]"]')).attr('id', 'itmcstinc_' + rowNuminc);
			newEntry.find($('input[name="lineinc_vat[]"]')).attr('id', 'vatinc_' + rowNuminc);
			newEntry.find($('input[name="lineinc_discount[]"]')).attr('id', 'itmdsntinc_' + rowNuminc);
			newEntry.find($('input[name="lineinc_total[]"]')).attr('id', 'itmttlinc_' + rowNuminc);
			newEntry.find($('input[name="vatlineinc_amt[]"]')).attr('id', 'vatlineamtinc_' + rowNuminc);
			newEntry.find($('input[name="vatdivinc[]"]')).attr('id', 'vatdivinc_' + rowNuminc);
			newEntry.find($('.item-data')).attr('id', 'itemDatainc_' + rowNuminc);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNuminc); 
			newEntry.find('input').val(''); 
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNuminc);
			newEntry.find($('input[name="iteminc_total[]"]')).attr('id', 'itmtotinc_' + rowNuminc);
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNuminc);
			newEntry.find($('input[name="jobid[]"]')).attr('id', 'jobid_' + rowNuminc);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNuminc);
			
			$('#vatdivinc_'+rowNuminc).val( srvat+'%' );
			$('#vatinc_'+rowNuminc).val( srvat );
					
			controlForminc.find('.btn-addinc-item:not(:last)').hide();
			controlForminc.find('.btn-removeinc-item').show();
			$('#itmcodinc_'+rowNuminc).focus();
			
    }).on('click', '.btn-removeinc-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChldinc:first').remove();
		
		getNetTotalinc();
		
		//ROWCHNG
		$('.itemdivPrntinc').find('.itemdivChldinc:last').find('.btn-addinc-item').show();
		if ( $('.itemdivPrntinc').children().length == 1 ) {
			$('.itemdivPrntinc').find('.btn-removeinc-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="jobcod[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#jobData').load(joburl+'/'+curNum, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var num = $('#num').val();
		$('#jobcod_'+num).val($(this).attr("data-cod"));
		$('#jobid_'+num).val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var supurl2;
	$('#supplier_name').click(function() {
		if($('#department_id').length)
			supurl2 = "{{ url('purchase_order/supplier_datadept/') }}"+'/'+$('#department_id option:selected').val();
		else
			supurl2 = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(supurl2, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		var trnno = $(this).attr("data-trnno");
		if(trnno=='') {
			if(confirm('TRN No. is not updated, do you want to update now?')) {
				if( $('#trninfo').is(":hidden") )
					$('#trninfo').toggle();
			} else
				return false;
		} else {
			if( $('#trninfo').is(":visible") )
				$('#trninfo').toggle();
		}
		e.preventDefault();
	});
	
	//new change............
	 var acurl = "{{ url('account_master/get_accountbudginc/') }}";

//	var acurl = "{{ url('account_master/get_account_all/') }}";
	 $(document).on('click', 'input[name="acccinc_code[]"]', function(e) {
	 	var res = this.id.split('_');
	 	var curNum = res[1]; 
	 	console.log(curNum);
	 	$('#accccinc_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			//console.log(result);
			//console.log(resultccc);
	 		$('#myModal').modal({show:true}); $('.input-sm').focus();
	 	});
	 });
	
	$(document).on('click', '.accountRowinc', function(e) { 
	 	var num = $('#num').val();
	 	$('#acntidinc_'+num).val( $(this).attr("data-id") );
	 	$('#accodinc_'+num).val( $(this).attr("data-name") );
	 	$('#acdesinc_'+num).val( $(this).attr("data-name") );
	 });
		
	 var acturl = "{{ url('purchase_split/account_data/') }}";
	 $(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
	 	var res = this.id.split('_');
	 	var curNum = res[1]; 
	 	$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
		$('#myModal').modal({show:true});
	 	});
	 });
	
	// var acturl = "{{ url('purchase_split/account_data/') }}";
	// $(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	//updated mar 18...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false;
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
		$('#frmPurchaseSplit').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	$(document).on('blur', '.lineinc-cost', function(e) { 
		
		var res = this.id.split('_');
		console.log('res: '+res);
		var curNum = res[1]; 
		//console.log('cost: '+curNum);
		var res = getLineTotalinc(curNum);
		if(res) {
			getNetTotalinc();
		}
	});
	
		
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	
	$('#department_id').on('change', function(e){
		var dept_id = e.target.value; 
		dptTxt = $( "#department_id option:selected" ).text();
		$.get("{{ url('purchase_split/getdeptvoucher/') }}/" + dept_id, function(data) {  console.log(data);
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
				$.each(data, function(key, value) {  
					
					$('#voucher_id').find('option').end()
							.append($("<option></option>")
							.attr("value",value.voucher_id)
							.text(value.voucher_name)); 
				});
				
			$('#curno').val(data[0].voucher_no);
			
			if(data[0].account_id!=null && data[0].account_name!=null) {
				$('#purchase_account').val(data[0].account_id+'-'+data[0].account_name);
				$('#account_master_id').val(data[0].id);
			} else {
				$('#purchase_account').val('');
				$('#account_master_id').val('');
			}
			
			if(data[0].cash_voucher==1) {
				if( $('#newsupplierInfo').is(":hidden") )
					$('#newsupplierInfo').toggle();
			
				$('#supplier_name').val(data[0].default_account);
				$('#supplier_id').val(data[0].cash_account);
				$('#supplier_name').removeAttr("data-toggle");
			} else {
				if( $('#newsupplierInfo').is(":visible") )
					$('#newsupplierInfo').toggle();
				$('#supplier_name').val('');
				$('#supplier_id').val('');
				$('#supplier_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_split/getvoucher/') }}/" + vchr_id, function(data) { console.log(data);
			$('#voucher_no').val(data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			if(data.account_id!=null && data.account_name!=null) {
				$('#purchase_account').val(data.account_id+'-'+data.account_name);
				$('#account_master_id').val(data.id);
			} else {
				$('#purchase_account').val('');
				$('#account_master_id').val('');
			}
			
			if(data.cash_voucher==1) {
				if( $('#newsupplierInfo').is(":hidden") )
					$('#newsupplierInfo').toggle();
			
				$('#supplier_name').val(data.default_account);
				$('#supplier_id').val(data.cash_account);
				$('#supplier_name').removeAttr("data-toggle");
			} else {
				if( $('#newsupplierInfo').is(":visible") )
					$('#newsupplierInfo').toggle();
				$('#supplier_name').val('');
				$('#supplier_id').val('');
				$('#supplier_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#purchase_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	// var acurl = "{{ url('account_master/get_accounts/') }}";
	// $(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	// $(document).on('click', '.accountRow', function(e) { 
	// 	var num = $('#num').val();
	// 	$('#dracnt_'+num).val( $(this).attr("data-name") );
	// 	$('#dracntid_'+num).val( $(this).attr("data-id") );
	// });
	
	// var acurlall = "{{ url('account_master/get_account_all/') }}";
	// $(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	// $(document).on('click', '.accountRowall', function(e) { 
	// 	var num = $('#num').val();
	// 	$('#cracnt_'+num).val( $(this).attr("data-name") );
	// 	$('#cracntid_'+num).val( $(this).attr("data-id") );
		
	// });
	
		
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		
		//$('.tax-code').val(this.value);
		$( '.tax-code' ).each(function() {
			var res = this.id.split('_'); 
			var curNum = res[1]; 
			if(this.value=='ZR') {
				$('#vat_'+curNum).val(0);
				$('#vatdiv_'+curNum).val('0%');
			} else {
				$('#vat_'+curNum).val(srvat);
				$('#vatdiv_'+curNum).val(srvat+'%');
			}
			
			var res = getLineTotal(curNum);
			if(res) 
				getNetTotal();	
		});
		
	});
	
		
	//Supplier search...
	var acmst = "{{ url('account_master/ajax_account/') }}";
	$('#supplier_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: acmst,
                dataType: "json",
                data: {
                    term : request.term, category : 'SUPPLIER'
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			$("#supplier_id").val(ui.item.id);
		},
        minLength: 2,
    });
	
});












$(function() {	
	var dat = new Date();
    $('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	var rowNum = 1;
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; 
		e.preventDefault(); 
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); 
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'acntid_' + rowNum);
			newEntry.find($('input[name="accc_code[]"]')).attr('id', 'accod_' + rowNum);
			newEntry.find($('input[name="item_description[]"]')).attr('id', 'acdes_' + rowNum);
			//newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); 
			newEntry.find('input').val(''); 
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			newEntry.find($('input[name="jobid[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
			
			$('#vatdiv_'+rowNum).val( srvat+'%' );
			$('#vat_'+rowNum).val( srvat );
					
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			$('#itmcod_'+rowNum).focus();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		
		getNetTotal();
		
		//ROWCHNG
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
		var joburl = "{{ url('jobmaster/job_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	

	
	var supurl2;
	$('#supplier_name').click(function() {
		if($('#department_id').length)
			supurl2 = "{{ url('purchase_order/supplier_datadept/') }}"+'/'+$('#department_id option:selected').val();
		else
			supurl2 = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(supurl2, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		var trnno = $(this).attr("data-trnno");
		if(trnno=='') {
			if(confirm('TRN No. is not updated, do you want to update now?')) {
				if( $('#trninfo').is(":hidden") )
					$('#trninfo').toggle();
			} else
				return false;
		} else {
			if( $('#trninfo').is(":visible") )
				$('#trninfo').toggle();
		}
		e.preventDefault();
	});
	
	//new change............
	

	  var acurl = "{{ url('account_master/get_accountbudg/') }}";
//	var acurl = "{{ url('account_master/get_account_all/') }}";
	  $(document).on('click', 'input[name="accc_code[]"]', function(e) {
	  	var res = this.id.split('_');
	  	var curNum = res[1]; 
	  	console.log(curNum);
	  	$('#acccc_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
	 		//console.log(result);
	 		//console.log(resultccc);
	  		$('#myModal').modal({show:true}); $('.input-sm').focus();
	  	});
	  });
	
	 $(document).on('click', '.accountRow', function(e) { 
	  	var num = $('#num').val();
	  	$('#acntid_'+num).val( $(this).attr("data-id") );
	  	$('#accod_'+num).val( $(this).attr("data-name") );
	  	$('#acdes_'+num).val( $(this).attr("data-name") );
	  });
		
	 var acturl = "{{ url('purchase_split/account_data/') }}";
	 $(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
	 	var res = this.id.split('_');
	 	var curNum = res[1]; 
	 	$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
		$('#myModal').modal({show:true});
	 	});
	 });
	
	// var acturl = "{{ url('purchase_split/account_data/') }}";
	// $(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	//updated mar 18...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false;
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
		$('#frmPurchaseSplit').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	$(document).on('blur', '.line-cost', function(e) { 
		
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
		
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
		});
	});
	
	
	$('#department_id').on('change', function(e){
		var dept_id = e.target.value; 
		dptTxt = $( "#department_id option:selected" ).text();
		$.get("{{ url('purchase_split/getdeptvoucher/') }}/" + dept_id, function(data) {  console.log(data);
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
				$.each(data, function(key, value) {  
					
					$('#voucher_id').find('option').end()
							.append($("<option></option>")
							.attr("value",value.voucher_id)
							.text(value.voucher_name)); 
				});
				
			$('#curno').val(data[0].voucher_no);
			
			if(data[0].account_id!=null && data[0].account_name!=null) {
				$('#purchase_account').val(data[0].account_id+'-'+data[0].account_name);
				$('#account_master_id').val(data[0].id);
			} else {
				$('#purchase_account').val('');
				$('#account_master_id').val('');
			}
			
			if(data[0].cash_voucher==1) {
				if( $('#newsupplierInfo').is(":hidden") )
					$('#newsupplierInfo').toggle();
			
				$('#supplier_name').val(data[0].default_account);
				$('#supplier_id').val(data[0].cash_account);
				$('#supplier_name').removeAttr("data-toggle");
			} else {
				if( $('#newsupplierInfo').is(":visible") )
					$('#newsupplierInfo').toggle();
				$('#supplier_name').val('');
				$('#supplier_id').val('');
				$('#supplier_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_split/getvoucher/') }}/" + vchr_id, function(data) { console.log(data);
			$('#voucher_no').val(data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			if(data.account_id!=null && data.account_name!=null) {
				$('#purchase_account').val(data.account_id+'-'+data.account_name);
				$('#account_master_id').val(data.id);
			} else {
				$('#purchase_account').val('');
				$('#account_master_id').val('');
			}
			
			if(data.cash_voucher==1) {
				if( $('#newsupplierInfo').is(":hidden") )
					$('#newsupplierInfo').toggle();
			
				$('#supplier_name').val(data.default_account);
				$('#supplier_id').val(data.cash_account);
				$('#supplier_name').removeAttr("data-toggle");
			} else {
				if( $('#newsupplierInfo').is(":visible") )
					$('#newsupplierInfo').toggle();
				$('#supplier_name').val('');
				$('#supplier_id').val('');
				$('#supplier_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#purchase_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	// var acurl = "{{ url('account_master/get_accounts/') }}";
	// $(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	// $(document).on('click', '.accountRow', function(e) { 
	// 	var num = $('#num').val();
	// 	$('#dracnt_'+num).val( $(this).attr("data-name") );
	// 	$('#dracntid_'+num).val( $(this).attr("data-id") );
	// });
	
	// var acurlall = "{{ url('account_master/get_account_all/') }}";
	// $(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
	// 	var res = this.id.split('_');
	// 	var curNum = res[1]; 
	// 	$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
	// 		$('#myModal').modal({show:true});
	// 	});
	// });
	
	// $(document).on('click', '.accountRowall', function(e) { 
	// 	var num = $('#num').val();
	// 	$('#cracnt_'+num).val( $(this).attr("data-name") );
	// 	$('#cracntid_'+num).val( $(this).attr("data-id") );
		
	// });
	
		
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		
		//$('.tax-code').val(this.value);
		$( '.tax-code' ).each(function() {
			var res = this.id.split('_'); 
			var curNum = res[1]; 
			if(this.value=='ZR') {
				$('#vat_'+curNum).val(0);
				$('#vatdiv_'+curNum).val('0%');
			} else {
				$('#vat_'+curNum).val(srvat);
				$('#vatdiv_'+curNum).val(srvat+'%');
			}
			
			var res = getLineTotal(curNum);
			if(res) 
				getNetTotal();	
		});
		
	});
	
		
	//Supplier search...
	var acmst = "{{ url('account_master/ajax_account/') }}";
	$('#supplier_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: acmst,
                dataType: "json",
                data: {
                    term : request.term, category : 'SUPPLIER'
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			$("#supplier_id").val(ui.item.id);
		},
        minLength: 2,
    });
	
});












var popup;
function getItems(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_order/item_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_split/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccountCr(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_split/account_data/') }}/"+curNum+"/cr";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getDocument() { 
	var supplier_id = $("#supplier_id").val();
	var doc = $('#document_type option:selected').val();
	
	if(doc=='PO' && $("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	
	if(doc=='PO')
		var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id+"/PO";
	else if(doc=='MR')
		var pourl = "{{ url('purchase_order/mr_data/') }}/MRI";
	else if(doc=='SDO')
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/SDO";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop

										