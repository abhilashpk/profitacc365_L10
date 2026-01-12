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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		}
		
		.ui-helper-hidden-accessible{
			display:none !important;
		}
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Goods Issued Note
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Goods Issued Note </a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
					<?php if(sizeof($vouchers)==0) { ?>
					<div class="alert alert-warning">
						<p>
							Goods Issued voucher is not found! Please create a voucher in Account Settings.
						</p>
					</div>
					<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New 
                            </h3>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmGoodsInvoice" id="frmGoodsInvoice" action="{{ url('goods_issued/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" id="voucher_id1" name="voucher_id1" value="13">
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Department</label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Voucher</label>
									 <div class="col-sm-10">
									   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
										   @foreach($vouchers as $voucher)
											 @if($voucher->department_id==$departments[0]->id)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endif
											@endforeach
										</select>
									</div>
								</div>
								@else
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Voucher</label>
									 <div class="col-sm-10">
									   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
										   @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('voucher_no')) echo 'form-error';?>">GI. No.</label>
                                    <div class="col-sm-10">
										<?php if($voucherno->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$voucherno->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly placeholder="{{$vouchers[0]['voucher_no']}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
										<?php } else { ?>
										<div class="input-group">
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly placeholder="{{$vouchers[0]['voucher_no']}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
										<?php } ?>
                                    </div>
									<input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$vouchers[0]['voucher_no']}}">
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">GI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Itemwise Job Entry</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="export" id="item_job" name="item_job">
											</label>
										</div>
									</div>
                                </div>
								
								<div class="form-group jobDtls">
                                   <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Job Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
										<input type="hidden" name="job_id" id="job_id">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Stock Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="stock_account" id="stock_account" class="form-control" autocomplete="off" data-toggle="modal" data-target="#stockac_modal" value="{{$voucher['master_name']}}">
										<input type="hidden" name="account_master_id" id="account_master_id" class="form-control" value="{{$voucher['cr_account_master_id']}}">
									</div>
                                </div>
								
								<div class="form-group jobDtls">
                                    <label for="input-text" class="col-sm-2 control-label"><!--Job Cost Account-->Stock CONS</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="job_account" id="job_account" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" value="{{$voucher['dr_master_name']}}">
										<input type="hidden" name="job_account_id" id="job_account_id" value="{{$voucher['dr_account_master_id']}}">
									</div>
                                </div>
								
							
								<br/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="14%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="24%" class="itmHd">
											<span class="small">Item Description</span>
										</th>
										<th width="12%" class="itmHd" id="jc">
											<span class="small">Job Code</span>
										</th>
										<th width="14%" class="itmHd" id="ac">
											<span class="small">A/c. Code</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Quantity</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Cost/Unit</span>
										</th>
										<th width="14%" class="itmHd">
											<span class="small">Total</span> 
										</th>
									</tr>
									</thead>
								</table>
								<div class="itemdivPrnt">
									<div class="itemdivChld">	
										<table class="table-dy-row">
											<tr>
											<td width="15%">
												<input type="hidden" name="item_id[]" id="itmid_1">
												<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
											</td>
											<td width="26%">
												<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
											</td>
											<td width="7%">
												<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
											</td>
											<td width="8%">
												<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
											</td>
											<td width="8%">
												<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
												<input type="hidden" name="actcost[]" id="itmcsthd_1">
												<input type="hidden" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" placeholder="Vat"><!--<div class="h5" id="vatdiv_1"></div>--> 
												<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost">
												<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
												<input type="hidden" id="itmdsnt_1" name="line_discount[]" value="0">
											</td>
											<td width="11%">
												<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
										
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1"></div>
												</div>
											</div>
											
											<div id="loc" >
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_1">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_1"></div>
												</div>
											</div>
										
									</div>
								</div>
								
								</fieldset>
								<br/><br/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								
								<input type="hidden" id="discount" name="discount" value="0">
								<input type="hidden" id="discount_fc" name="discount_fc" value="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('goods_issued') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('goods_issued/add') }}" class="btn btn-warning">Clear</a>
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
							
							<div id="account_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Account Master</h4>
                                        </div>
                                        <div class="modal-body" id="accountData">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="stockac_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Account List</h4>
                                        </div>
                                        <div class="modal-body" id="stockacData">
                                            
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
					<?php } ?>
                </div>
            </div>
        </section>
		
		<div class="hide" id="itemJobwise">
		  <table class="table-dy-row">
			<tr>
				<td width="14%">
					<input type="hidden" name="item_id[]" id="itmid_1">
					<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
				</td>
				<td width="26%">
					<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
				</td>
				<td width="13%">
					<input type="text" id="jobcod_1" autocomplete="off" name="jobcod[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
					<input type="hidden" name="jobid[]" id="jobid_1">
				</td>
				<td width="15%">
					<input type="text" id="accod_1" name="ac_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#ac_modal" placeholder="A/C Name">
					<input type="hidden" name="account_id[]" id="acntid_1">
				</td>
				<td width="7%">
					<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
				</td>
				<td width="7%">
					<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
				</td>
				<td width="8%">
					<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
					<input type="hidden" name="actcost[]" id="itmcsthd_1">
					<input type="hidden" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" placeholder="Vat"><!--<div class="h5" id="vatdiv_1"></div>--> 
					<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost">
					<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
					<input type="hidden" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control line-discount" placeholder="Discount">
				</td>
				<td width="11%">
					<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
		
		<div class="hide" id="itemNormal">
		  <table class="table-dy-row">
			<tr>
			<td width="15%">
				<input type="hidden" name="item_id[]" id="itmid_1">
				<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
			</td>
			<td width="26%">
				<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
			</td>
			<td width="7%">
				<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
			</td>
			<td width="8%">
				<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
			</td>
			<td width="8%">
				<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
				<input type="hidden" name="actcost[]" id="itmcsthd_1">
				<input type="hidden" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control cost" placeholder="Vat"><!--<div class="h5" id="vatdiv_1"></div>--> 
				<input type="hidden" id="vat_1" name="line_vat[]" class="form-control cost">
				<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
				<input type="hidden" id="itmdsnt_1" step="any" name="line_discount[]" class="form-control line-discount" placeholder="Discount">
			</td>
			<td width="11%">
				<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
		
		<div id="ac_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Select Account</h4>
					</div>
					<div class="modal-body" id="ac_data">
					</div>
					<div class="modal-footer">
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
<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<script>
"use strict";

$(document).ready(function () { 
	$('#jc').toggle(); $('#ac').toggle(); 
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	
	$("#c_label").toggle();$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('#other_cost_fc').toggle(); $('.OCdivPrnt').toggle(); 
    $('#frmGoodsInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			//voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//jobname: { validators: { notEmpty: { message: 'The job name is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			stock_account: { validators: { notEmpty: { message: 'The stock account is required and cannot be empty!' } }},
			job_account: { validators: { notEmpty: { message: 'The job account is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }},
			'cost[]': { validators: { notEmpty: { message: 'The item cost is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmGoodsInvoice').data('bootstrapValidator').resetForm();
    });
	
	$('input').on('ifChecked', function(event){ 
		$('.jobDtls').toggle();
		$('#jc').toggle(); $('#ac').toggle();
		
		$(".itemdivChld").html($("#itemJobwise").html());
	});
	$('input').on('ifUnchecked', function(event){ 
		$('.jobDtls').toggle();
		$('#jc').toggle(); $('#ac').toggle(); 
		$(".itemdivChld").html($("#itemNormal").html());
	});
	
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		var n = 1; var lineTotal = 0;
		$( '.itemdivChld' ).each(function() {
		  lineTotal = lineTotal + getLineTotal(n);
		  n++;
		});
		$('#total').val(lineTotal.toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		//var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total;// + vat;
		$('#net_amount').val(netTotal.toFixed(2));

	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log(n);
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );

		var lineDiscount = parseFloat( ($('#itmdsnt_'+n).val()=='') ? 0 : $('#itmdsnt_'+n).val() );
		var lineTotal 	 = ( lineQuantity * lineCost ) - lineDiscount;
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		
		return lineTotal;
	} 
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost_avg/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&customer_id=',
			success: function(data) {
				$('#itmcst_'+curNum).val((data==0)?'':data);
				$('#itmcsthd_'+curNum).val((data==0)?'':data);
				$('#itmcst_'+curNum).focus();
				return true;
			}
		}) 
	}
	

//
$(function() {	
	var dat = new Date();
    $('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy' } );
	
	var rowNum = 1;
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('goods_issued/getdeptvoucher/') }}/" + dept_id, function(data) {  
			if(data.length > 0) {
    			$('#voucher_no').val(data[0].voucher_no);
    			$('#voucher_id').find('option').remove().end();
    			$.each(data, function(key, value) {  
    				$('#voucher_id').find('option').end()
    						.append($("<option></option>")
    						.attr("value",value.voucher_id)
    						.text(value.voucher_name)); 
    			});
    			
    			$('#stock_account').val(data[0].cr_account_name);
    			$('#account_master_id').val(data[0].cr_id);
    			$('#job_account').val(data[0].dr_account_name);
    			$('#job_account_id').val(data[0].dr_id);
			} else {
			    $('#voucher_no').val('');
			    $('#stock_account').val('');
    			$('#account_master_id').val('');
    			$('#job_account').val('');
    			$('#job_account_id').val('');
    			$('#voucher_id').find('option').remove();
			}
			
		});
	});
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('goods_issued/getvoucher/') }}/" + vchr_id, function(data) { 
			$('#voucher_no').val(data.voucher_no);
			
			//if(data.cr_account_id!=null || data.dr_account_id!=null) {
				$('#stock_account').val(data.cr_account_name);
				$('#account_master_id').val(data.cr_id);
				
				$('#job_account').val(data.dr_account_name);
				$('#job_account_id').val(data.dr_id);
			//} 
		});
	});
	
	$(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
		   $('input[name="dr_acnt[]"]').val( $('#purchase_account').val() );
		   $('input[name="dr_acnt_id[]"]').val( $('#account_master_id').val() );
           $('.OCdivPrnt').toggle();
    });
	
	//item more info view section
	$(document).on('click', '.more-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_info/') }}/"+item_id;
		   $('#itemData_'+curNum).load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#infodivPrntItm_'+curNum).toggle();
	   }
    });
	  
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); 
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="actcost[]"]')).attr('id', 'itmcsthd_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); 
			newEntry.find($('input[name="othr_cost[]"]')).attr('id', 'othrcst_' + rowNum);
			newEntry.find($('input[name="net_cost[]"]')).attr('id', 'netcst_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); 
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); 
			
			newEntry.find($('input[name="jobid[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'acntid_' + rowNum);
			newEntry.find($('input[name="ac_code[]"]')).attr('id', 'accod_' + rowNum);
			
			newEntry.find('input').val(''); 
			newEntry.find('select').find('option').remove().end().append('<option value="">Select</option>');
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		$(this).parents('.itemdivChld:first').remove();
		var btotal = 0;
		$( '.line-total' ).each(function() {
		  btotal = btotal + parseFloat(this.value);
		});
		var vat = 0;
		$('.vatline-amt').each(function() {
			vat = vat + parseFloat(this.value);
		});
		$('#total').val(btotal); $('#vat').val(vat);
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		$('#net_amount').val(btotal - bdiscount + vat);
		
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	var supurl = "{{ url('jobmaster/job_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(supurl, function(result) {
			console.log(result);
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		$('#jobname').val($(this).attr("data-name"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();  console.log('no '+num);
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
		$('#vat_'+num).val( $(this).attr("data-vat") );
		$('#itmcst_'+num).val( $(this).attr("data-price") );
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
				$.each(data, function(key, value) {   
				 $('#itmunt_'+num).find('option').end()
					 .append($("<option></option>")
								.attr("value",value.id)
								.text(value.unit_name)); 
					});
			});
			
			getAutoPrice(num);
	});
	
	//var acurl = "{{ url('account_master/expenseac_data/') }}";
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="job_account"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#accountData').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#account_modal .custRow', function(e) {
		$('#job_account').val($(this).attr("data-name"));
		$('#job_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
		
	//new change............
	var stockurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="stock_account"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#stockacData').load(stockurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#stockac_modal .custRow', function(e) {
		$('#stock_account').val($(this).attr("data-name"));
		$('#account_master_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		var isPrice = getAutoPrice(curNum);
		if(isPrice){
			var lntot = getLineTotal(curNum);
			//if(lntot)
				getNetTotal();
		}
	});
	
	$(document).on('keyup', '.line-quantity', function(e) {
		getNetTotal();
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-discount', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	//total discount section.........
	$(document).on('blur', '#discount', function(e) { 
		getNetTotal();
	});
	
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+item_id+"/"+curNum
		   $('#locData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#locPrntItm_'+curNum).toggle();
	   }
    });
	
	$(document).on('keyup', '.num :input[type="number"]', function(e) {
		var itQty = 0; var curNum = $(this).data('id');
		$('.loc-qty-'+curNum).each(function() { 
			itQty += parseFloat( (this.value=='')?0:this.value );
		});
		$('#itmqty_'+curNum).val(itQty);
		
		var isPrice = getAutoPrice(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
		//$('#frmPurchaseInvoice').bootstrapValidator('revalidateField', 'quantity[]');
	});
});

var acurl = "{{ url('account_master/get_accounts/') }}";
$(document).on('click', 'input[name="ac_code[]"]', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; 
	$('#ac_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
		$('#myModal').modal({show:true}); $('.input-sm').focus();
	});
});
	
$(document).on('click', '#ac_modal .custRow', function(e) { 
	var num = $('#num').val();
	$('#acntid_'+num).val( $(this).attr("data-id") );
	$('#accod_'+num).val( $(this).attr("data-name") );
	$('#acdes_'+num).val( $(this).attr("data-name") );
});
//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('goods_issued/checkvchrno/') }}", 
			type: 'get',
			data: 'voucher_no='+this.value+'&deptid='+deptid+'&id=',
			success: function(data) { 
				data = $.parseJSON(data);
				if(data.valid==false) {
					alert('Voucher No already exist!');
					$('#voucher_no').val('');
					return false;
				}
				//console.log('ff '+data.valid);
			}
		}); 
	})
	//VOUCHER NO DUPLICATE OR NOT
		
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

</script>
@stop
