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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    
    <link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
	
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
                Manufacture Voucher 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Manufacture Voucher</a>
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
					<?php if(sizeof($vouchers)==0) { ?>
					<div class="alert alert-warning">
						<p>
							PI. No. is found empty! Please create in Account Settings.
						</p>
					</div>
					<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Manufacture Voucher
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('pi-print')
								 <a href="{{ url('manufacture/print/'.$printid->id) }}" class="btn btn-info btn-sm">
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
                            <form class="form-horizontal" role="form" method="POST" name="frmManufacture" id="frmManufacture" action="{{ url('manufacture/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								 <input type="hidden" name="reference_no">
								
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
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Manufacture Voucher</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
                                           @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
									<input type="hidden" name="curno" id="curno" value="<?php echo $vouchers[0]['voucher_no']; ?>">
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="<?php echo $vouchers[0]['voucher_no']; ?>">
										<span class="input-group-addon inputvn"><i class="fa fa-fw fa-edit"></i></span>
										</div>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								 <?php if($formdata['transfer_in']==1) { ?>
								<a href="#" class="trinDVa"><b>Transfer In Accounts</b></a><hr/>
								<?php } ?>
								<div id="trinDV">									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Debit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_dr" id="account_dr_id" value="{{ $vouchers[0]['dr_account_master_id'] }}" class="form-control">
												<input type="text" name="purchase_account" id="account_dr_name" class="form-control" value="{{ $vouchers[0]['dr_master_name'] }}" readonly>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Credit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_cr" id="account_cr_id" value="{{ $vouchers[0]['cr_account_master_id'] }}" class="form-control">
												<input type="text" name="purchase_account" id="account_cr_name" class="form-control" value="{{$vouchers[0]['master_name'] }}" readonly>
										</div>
									</div>
								</div>
                                <?php if($formdata['transfer_out']==1) { ?>
								<a href="#" class="troutDVa"><b>Transfer Out Accounts</b></a><hr/>	
								<?php } ?>
								<div id="troutDV">							
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Debit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_dr_to" id="account_dr_id" value="{{ $vouchers[0]['dr_account_master_id_to'] }}" class="form-control">
												<input type="text" name="purchase_account_to" id="account_dr_name_to" class="form-control" value="{{ $vouchers[0]['dr_master_name_to'] }}" readonly>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Credit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_cr_to" id="account_cr_id_to" value="{{ $vouchers[0]['cr_account_master_id_to'] }}" class="form-control">
												<input type="text" name="purchase_account_to" id="account_cr_name_to" class="form-control" value="{{$vouchers[0]['cr_master_name_to'] }}" readonly>
										</div>
									</div>
								</div>
								<?php if($formdata['description']==1) { ?>								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" autocomplete="off" placeholder="Description">
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								<?php if($formdata['production']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Production#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Production#" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="production" id="production">
								 <?php } ?>
								<br/>
								<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="14%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="25%" class="itmHd">
											<span class="small">Item Description</span>
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
										<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
										<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
														<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" autocomplete="off" value="{{ $item }}" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_{{$j}}" value="{{ old('item_name')[$i]}}" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_{{$j}}" value="{{ old('quantity')[$i]}}" autocomplete="off" step="any" name="quantity[]" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_{{$j}}" value="{{ old('cost')[$i]}}" autocomplete="off" step="any" name="cost[]" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
														<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
														<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" value="{{ old('line_total')[$i]}}" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('item_total')[$i]}}">
													</td>
													<td width="1%">
														<?php if(count(old('item_code'))==$j) { ?>
															<button type="button" class="btn-success btn-add-item" >
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
															<?php } ?>
															 <button type="button" class="btn-danger btn-remove-item" >
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
													</td>
												</tr>
											</table>

												<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
													<div class="infodivChldItm">							
														<div class="table-responsive item-data" id="itemData_{{$j}}"></div>
													</div>
												</div>
												
												<div class="rawmatPrntItm" id="rawmatPrntItm_{{$j}}">
													<div class="rawmatChldItm">							
														<div class="table-responsive rawmat-data" id="rawmatData_{{$j}}"></div>
													</div>
												</div>
											
										</div>
										<?php $i++; } } else { ?>
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="item_id[]" id="itmid_1">
														<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_1" autocomplete="off" step="any" name="quantity[]" class="form-control line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_1" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
														<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
														<input type="hidden" id="packing_1" name="packing[]" value="1">
														<input type="hidden" id="itmdsnt_1" name="line_discount[]">
														<input type="hidden" name="actcost[]" id="itmcsthd_1">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">
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
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="more_info" id="more_info">
								            <?php } ?>
								            <?php if($formdata['purchase']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_1" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="purchase" id="purchase">
								            <?php } ?>
								            <?php if($formdata['sales']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_1" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="sales" id="sales">
								            <?php } ?>
											<?php if($formdata['rawmaterial']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="rawmat_1" class="btn btn-primary btn-xs raw-mat">Raw Materials</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="rawmaterial" id="rawmaterial">
								            <?php } ?>
											<?php if($formdata['add_rawmaterial']==1) { ?>
											<div id="admoreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="adrawmat_1" class="btn btn-primary btn-xs ad-raw-mat">Add Raw Materials</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="add_rawmaterial" id="add_rawmaterial">
								            <?php } ?>
											<?php if($formdata['net_cost']==1) { ?>
											<div id="othrcst" style="float:left;padding-right:5px;">
												<button type="button" id="ocost_1" class="btn btn-primary btn-xs net-cost">Net Cost/Unit</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="netcost" id="netcost">
								            <?php } ?>
								            
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1"></div>
												</div>
											</div>
											<?php if($formdata['item_loc']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="item_loc" id="item_loc">
								            <?php } ?>
											<div class="locPrntItm" id="locPrntItm_1">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_1"></div>
												</div>
											</div>
											
											<div class="form-group oc" id="othrcstItm_1">
												<div class="col-sm-2"></div>
												<div class="col-xs-10">
													<div class="col-xs-2">
														<span class="small"></span>	
													</div>
													<div class="col-xs-2">
														<span class="small"></span> 
													</div>
													<div class="col-xs-2">
														<span class="small"></span> 
													</div>
													<div class="col-xs-2">
														<span class="small">Other Cost/Unit</span> <input type="text" id="othrcst_1" step="any" name="othr_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-2">
														<span class="small">Net Cost/Unit</span> <input type="text" id="netcst_1" step="any" name="net_cost[]" class="form-control" readonly>
													</div>
													<div class="col-xs-1">
													</div>
												</div>	
											</div>
											
											
											<div class="rawmatPrntItm" id="rawmatPrntItm_1" style="float:left;padding-right:5px;padding-top:2px;">
												<div class="rawmatChldItm">							
													<div class="table-responsive rawmat-data" id="rawmatData_1"></div>
												</div>
											</div>
											
											<div id="rMat_1" class="rMat" style="float:right;padding-left:195px;padding-top:2px;">
												<fieldset>
												<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">New Raw Materials</span></h5></legend>
													<table class="table table-bordered" style="margin-bottom:0px !important;">
													<thead>
														<tr>
															<th width="14%" class="itmHd">
																<span class="small">Item Code</span>
															</th>
															<th width="25%" class="itmHd">
																<span class="small">Item Description</span>
															</th>
															<th width="8%" class="itmHd">
																<span class="small">Quantity</span>
															</th>
															<th width="5%" class="itmHd">
																<span class="small">Cost/Unit</span>
															</th>
															<th width="13%" class="itmHd">
																<span class="small">Total</span> 
															</th>
														</tr>
														</thead>
													</table>
												
													<table border="0" class="table-dy-row">
														<tr>
															<td width="16%">
																<input type="hidden" id="rmitmid_1" name="rmitem_id[]">
																<input type="text" id="rmitmcod_1" name="rmitemcode[]" class="form-control" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#rmitem_modal">
															</td>
															<td width="29%">
																<input type="text" name="rmitem_name[]" id="rmitmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
															</td>
															<td width="8%">
																<input type="number" id="rmitmqty_1" step="any" name="rmquantity[]" autocomplete="off" class="form-control rmline-quantity" placeholder="Qty.">
															</td>
															<td width="8%">
																<input type="number" id="rmitmcst_1" step="any" name="rmcost[]" autocomplete="off" class="form-control rmline-cost" placeholder="Cost/Unit">
															</td>
															<td width="11%">
																<input type="number" id="rmitmttl_1" step="any" name="rmline_total[]" class="form-control rmline-total" readonly placeholder="Total">
																<input type="hidden" id="rmitmtot_1" name="rmitem_total[]">
															</td>
															<td width="1%">
																 <button type="button" id="rmbtn_1" class="btn-success btn-add-rmitem">
																	<i class="fa fa-fw fa-save"></i>
																 </button>
															</td>
														</tr>
													</table>
												</fieldset>
											</div>
												
										</div>
										
										<?php } ?>
									</div>
								
								</fieldset>

								<fieldset>
									<legend>
									    <?php if($formdata['other_cost']==1) { ?>
										<div id="oc_showmenu" style="padding-left:5px;"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
										<?php } else { ?>
								              <input type="hidden" name="ocadd" id="ocadd">
								         <?php } ?>
									</legend>
									<input type="hidden" id="ocrowNum" value="1">
									<div class="OCdivPrnt">
									<div class="OCdivChld">
											<div class="form-group">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="10%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
													</td>
													<td width="15%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="10%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="15%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
														<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control"  autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
													</td>
													<td width="3%">
														<br/>
														<button type="button" class="btn-success btn-add-oc" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-oc" >
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													</td>
												</tr>
											</table>
											</div>											
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<legend>
									    <?php if($formdata['wastage']==1) { ?>
										<div id="we_showmenu" style="padding-left:5px;"><button type="button" id="weadd" class="btn btn-primary btn-xs">Wastage Entry</button></div>
										<?php } else { ?>
								              <input type="hidden" name="weadd" id="weadd">
								         <?php } ?>
									</legend>
									<div id="weData_1"></div>
								</fieldset>
								
								<hr/>
								
								<input type="hidden" id="other_cost" name="other_cost">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{old('total')}}" placeholder="0">
										<input type="hidden" id="total_hd" step="any" name="total_hd">
										</div>
										<div class="col-xs-2">
										</div>
									</div>
                                </div>
								
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Discount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{old('discount')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
										</div>
									</div>
                                </div>-->
								
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" step="any" id="discount" name="discount" class="form-control spl discount-cal" value="{{old('discount')}}">
											<input type="number" step="any" id="net_amount" name="total_price" class="form-control spl" readonly value="{{old('total_price')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
										</div>
									</div>
                                </div>
                                
                                <?php if($formdata['footer_edit']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$footer}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								<hr/>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('manufacture') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('manufacture/add') }}" class="btn btn-warning">Clear</a>
										
                                    </div>
                                </div>
								<div id="purchase_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Purchase History</h4>
                                        </div>
                                        <div class="modal-body" id="purchasehisData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="sales_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Sales History</h4>
                                        </div>
                                        <div class="modal-body" id="saleshisData">
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
							
							<div id="rmitem_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="rmitem_data">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Order History</h4>
                                        </div>
                                        <div class="modal-body" id="historyData">Please select a Supplier first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="rw_item_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Item</h4>
                                        </div>
                                        <div class="modal-body" id="rw_item_data">
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

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>


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

$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";
var lprice; var packing = 1;

$(document).ready(function () { 
	$('#trinDV').hide(); $('#troutDV').hide();
	//ROWCHNG
	$('.btn-remove-item').hide(); 
	
	$('#trninfo').toggle();
	$('#othrcstItm_1').toggle();$('.locPrntItm').toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('.rawmatPrntItm').toggle();
	
	$('.OCdivPrnt').toggle(); $('#weData_1').toggle(); $('.oc-amount-fc').toggle(); $('.btn-remove-oc').hide(); 
	
	var urlvchr = "{{ url('manufacture/checkvchrno/') }}";
	var urlcode = "{{ url('manufacture/checkrefno/') }}"; //CHNG
	$('.sedePrntItm').toggle();
	$('.rMat').hide();
    /* $('#frmManufacture').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: {
                validators: {
                   
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This PI. No. is already exist!'
                    }
                }
            },
			reference_no: {
                validators: {
                   
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val()
                            };
                        },
                        message: 'This Reference No. is already exist!'
                    }
                }
            }
			
        }
        
    }).on('reset', function (event) {
        $('#frmManufacture').data('bootstrapValidator').resetForm();
    });
	 */
	<?php if( sizeof($vouchers) > 0 && $vouchers[0]->is_cash_voucher==1) { ?> //cash customer.... 
		if( $('#newsupplierInfo').is(":hidden") )
			$('#newsupplierInfo').toggle();
		$('#supplier_id').val({{$vouchers[0]->default_account_id}});
		$('#supplier_name').val('{{$vouchers[0]->default_account}}');
	<?php }  else { ?>
		if( $('#newsupplierInfo').is(":visible") )
			$('#newsupplierInfo').toggle();
	<?php } ?>
	
	
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		
		var lineTotal = 0;
		var lineTotalhd = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotalhd = lineTotal + parseFloat(amt.toFixed(2));
		});
		
		if($('.oc-line').length) {
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var n = res[1];
				lineTotal = lineTotal + parseFloat( ($('#ocamt_'+n).val()=='') ? 0 : $('#ocamt_'+n).val() );
			});
		}
		
		$('#total').val(lineTotal.toFixed(2));
		$('#total_hd').val(lineTotalhd.toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
				
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total;
		
		$('#net_amount').val(netTotal.toFixed(2));
		$('#net_amount_hid').val(netTotal+discount);
		
	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		
		var lineTotal 	 = lineQuantity * lineCost;
		var lineTotalTx 	 = lineQuantity * lineCost;
		
		//$('#itmcst_'+n).val(lineCost.toFixed(2));
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#itmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	} 
	
	function getAutoPrice(curNum) {
		
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		
		$.ajax({
			url: "{{ url('itemmaster/get_cost_avg_mfg/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id,
			success: function(data) {
				$('#itmcst_'+curNum).val(data);
				$('#itmcst_'+curNum).focus();
				$('#itmcsthd_'+curNum).val(data);
			}
		});
		return true;
	}
	
	function getAutoPriceRM(curNum) {
		
		var item_id = $('#rmitmid_'+curNum).val();
		var unit_id = '';
		
		$.ajax({
			url: "{{ url('itemmaster/get_cost_avg_mfg/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id,
			success: function(data) {
				$('#rmitmcst_'+curNum).val(data);
				$('#rmitmcst_'+curNum).focus();
			}
		});
		
		return true;
	}
	
	function getOtherCost() {
		var otherCost = 0; 
		if( $('.OCdivPrnt').is(":visible") ) { 
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var curNum = res[1];
				var ocamt_ln = this.value;
				otherCost = otherCost + parseFloat(ocamt_ln);
			}); 
			$('#other_cost').val(otherCost.toFixed(2));
		}
		
		if(otherCost!=0) { 
			var totalQty = 0;
			 $('input[name="quantity[]"]').each(function(){
				totalQty += +$(this).val();
			 });
			 
			 var totalCost = parseFloat( ($('#total_hd').val()=='')?0:$('#total_hd').val() );
			 var n = 1;
			$( '.itemdivChld' ).each( function() {
				var costPerUnit; var nTotal;
				var itemCost = parseFloat( ($('#itmcst_'+n).val()=='')?0:$('#itmcst_'+n).val() );
				var itemQty = parseFloat( ($('#itmqty_'+n).val()=='')?0:$('#itmqty_'+n).val() );
				
				costPerUnit = (otherCost / totalCost) * itemCost;
				nTotal = costPerUnit + itemCost;
				$('#othrcst_'+n).val(costPerUnit.toFixed(2)); console.log(costPerUnit+' '+n);
				$('#netcst_'+n).val( nTotal.toFixed(2) );
				n++;
			}); 
		}
		
		return true;
	}
	
$(function() {	
	var dat = new Date(); var srvat;
    $('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('manufacture/getdeptvoucher/') }}/" + dept_id, function(data) { 
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
			$.each(data, function(key, value) {  
				$('#voucher_id').find('option').end()
						.append($("<option></option>")
						.attr("value",value.voucher_id)
						.text(value.voucher_name)); 
			});
			
			$('#account_cr_name').val(data[0].cr_account_name);
			$('#account_cr_id').val(data[0].cr_id);
			$('#account_dr_name').val(data[0].dr_account_name);
			$('#account_dr_id').val(data[0].dr_id);
			
		});
	});
	
	
	var rowNum = 1;
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
		   //$('input[name="dr_acnt[]"]').val( $('#purchase_account').val() );
		  // $('input[name="dr_acnt_id[]"]').val( $('#account_master_id').val() );
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
	
	
	$(document).on('click', '.raw-mat', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var rwUrl = "{{ url('itemmaster/get_rawmat/') }}/"+item_id;
		   $('#rawmatData_'+curNum).load(rwUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#rawmatPrntItm_'+curNum).toggle();
	   }
    });
	
	$(document).on('click', '.ad-raw-mat', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	     $('#rMat_'+curNum).toggle();
    });
	
	$(document).on('click', '.net-cost', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	    $('#othrcstItm_'+curNum).toggle();
	});
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.rawmatPrntItm')).attr('id', 'rawmatPrntItm_' + rowNum);
			newEntry.find($('input[name="actcost[]"]')).attr('id', 'itmcsthd_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.raw-mat')).attr('id', 'rawmat_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.rawmat-data')).attr('id', 'rawmatData_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); //NEW CHNG
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			newEntry.find($('.ad-raw-mat')).attr('id', 'adrawmat_' + rowNum);
			newEntry.find($('.rMat')).attr('id', 'rMat_' + rowNum);
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			if( $('#rawmatPrntItm_'+rowNum).is(":visible") ) 
				$('#rawmatPrntItm_'+rowNum).toggle();
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
			if( $('#sedePrntItm_'+rowNum).is(":visible") ) 
				$('#sedePrntItm_'+rowNum).toggle();
			
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
			var src = "{{ url('itemmaster/ajax_search/') }}";
			newEntry.find($('input[name="item_code[]"]')).autocomplete({
					
					source: function(request, response) {
						$.ajax({
							url: src+'/C',
							dataType: "json",
							data: {
								term : request.term
							},
							success: function(data) {
								response(data); 
							}
						});
					},
					select: function (event, ui) {
						var itm_id = ui.item.id;
						$("#itmdes_"+rowNum).val(ui.item.name);
						$('#itmcod_'+rowNum).val( ui.item.value );
						$('#itmid_'+rowNum).val( itm_id );
						
						if($('.export').is(":checked") ) { 
							$('#vatdiv_'+rowNum).val( '0%' );
							$('#vat_'+rowNum).val( 0 );
						} else {
							srvat = ui.item.vat;
							$('#vatdiv_'+rowNum).val( srvat+'%' );
							$('#vat_'+rowNum).val( srvat );
						}
						
						$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
							$('#itmunt_'+rowNum).find('option').remove().end();
							$.each(data, function(key, value) {   
							$('#itmunt_'+rowNum).find('option').end()
							 .append($("<option></option>")
										.attr("value",value.id)
										.text(value.unit_name)); 
							});
						});
					},
					minLength: 1,
				});
				
				newEntry.find($('input[name="item_name[]"]')).autocomplete({
					
					source: function(request, response) {
						$.ajax({
							url: src+'/N',
							dataType: "json",
							data: {
								term : request.term
							},
							success: function(data) {
								response(data); 
							}
						});
					},
					select: function (event, ui) {
						var itm_id = ui.item.id;
						$("#itmdes_"+rowNum).val(ui.item.value);
						$('#itmcod_'+rowNum).val( ui.item.name );
						$('#itmid_'+rowNum).val( itm_id );
						
						if($('.export').is(":checked") ) { 
							$('#vatdiv_'+rowNum).val( '0%' );
							$('#vat_'+rowNum).val( 0 );
						} else {
							srvat = ui.item.vat;
							$('#vatdiv_'+rowNum).val( srvat+'%' );
							$('#vat_'+rowNum).val( srvat );
						}
						
						$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
							$('#itmunt_'+rowNum).find('option').remove().end();
							$.each(data, function(key, value) {   
							$('#itmunt_'+rowNum).find('option').end()
							 .append($("<option></option>")
										.attr("value",value.id)
										.text(value.unit_name)); 
							});
						});
					},
					minLength: 3,
				});
			
			//ENTER KEY for TAB functionality in dynamic field..........
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
			
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
	
	var ocrowNum = $('#ocrowNum').val();
	$(document).on('click', '.btn-add-oc', function(e) 
    { 
        ocrowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .OCdivPrnt'),
            currentEntry = $(this).parents('.OCdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="dr_acnt_id[]"]')).attr('id', 'dracntid_' + ocrowNum);
			newEntry.find($('input[name="dr_acnt[]"]')).attr('id', 'dracnt_' + ocrowNum);
			newEntry.find($('input[name="oc_reference[]"]')).attr('id', 'ocref_' + ocrowNum);
			newEntry.find($('input[name="oc_description[]"]')).attr('id', 'ocdesc_' + ocrowNum);
			newEntry.find($('input[name="oc_amount[]"]')).attr('id', 'ocamt_' + ocrowNum);
			newEntry.find($('input[name="cr_acnt_id[]"]')).attr('id', 'cracntid_' + ocrowNum);
			newEntry.find($('input[name="cr_acnt[]"]')).attr('id', 'cracnt_' + ocrowNum);
			newEntry.find('input').val(''); 
			
			controlForm.find('.btn-add-oc:not(:last)').hide();
			controlForm.find('.btn-remove-oc').show();
			
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
		$('.OCdivPrnt').find('.OCdivChld:last').find('.btn-add-oc').show();
		if ( $('.OCdivPrnt').children().length == 1 ) {
			$('.OCdivPrnt').find('.btn-remove-oc').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	//new change.................
	$(document).on('click', '#item_data .itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
		$('#vat_'+num).val( $(this).attr("data-vat") );
		$('#itmcst_'+num).val( $(this).attr("data-price") );
		srvat = $(this).attr("data-vat");
		
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name));
						$('#hidunit_'+num).val(value.unit_name);
			});

		});
		getAutoPrice(1);
	});
	
	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function() {
		$('#supplierData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});

	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//raw materials............
	var rmitmurl = "{{ url('itemmaster/rmitem_data/') }}";
	$(document).on('click', 'input[name="rmitemcode[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#rmitem_data').load(rmitmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	
	$(document).on('click', '#rmitem_data .itemRow', function(e) { 
		var num = $('#num').val();
		$('#rmitmcod_'+num).val( $(this).attr("data-code") );
		$('#rmitmid_'+num).val( $(this).attr("data-id") );
		$('#rmitmdes_'+num).val( $(this).attr("data-name") );
		
			var itm_id = $(this).attr("data-id")
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#rmitmunt_'+num).find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#rmitmunt_'+num).find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); 
				});
			});
		
		getAutoPriceRM(num);
	});
	
	var itmurlrw = "{{ url('itemmaster/item_data_rw/') }}";
	$(document).on('click', 'input[name="rwitem"]', function(e) {
		var curNum = 1; 
		$('#rw_item_data').load(itmurlrw, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('keyup blur', '.rmline-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1];
		var amt = parseFloat( ($('#rmitmcst_'+curNum).val()=='') ? 0 : $('#rmitmcst_'+curNum).val() );
		$('#rmitmttl_'+curNum).val((amt * this.value));
		
		//getNetTotal();
		
	});

	$(document).on('keyup blur', '.rmline-cost', function(e) {
			
		var res = this.id.split('_');
		var curNum = res[1];
		var qty = parseFloat( ($('#rmitmqty_'+curNum).val()=='') ? 0 : $('#rmitmqty_'+curNum).val() );
		$('#rmitmttl_'+curNum).val((qty * this.value));
		//getNetTotal();
	});
	
	$(document).on('click', '.btn-add-rmitem', function(e) {
			
		var res = this.id.split('_');
		var curNum = res[1];
		var sitmid = $('#rmitmid_'+curNum).val();
		var qty = parseFloat( ($('#rmitmqty_'+curNum).val()=='') ? 0 : $('#rmitmqty_'+curNum).val() );
		var cst = parseFloat( ($('#rmitmcst_'+curNum).val()=='') ? 0 : $('#rmitmcst_'+curNum).val() );
		var rmi = $('#itmid_'+curNum).val();
		if(rmi!='' && sitmid!='' && qty!='' && cst!='') {
			$('#itmcst_'+curNum).val( parseFloat( $('#itmcst_'+curNum).val())+(qty*cst) );
			$.ajax({
				url: "{{ url('itemmaster/add_rawmaterial/') }}",
				type: 'post',
				data: {'item_id':rmi,'sitem_id':sitmid,'qty':qty,'cost':cst},
				success: function(data) { 
					
					$('#rmitmid_'+curNum).val('');
					$('#rmitmqty_'+curNum).val('');
					$('#rmitmcst_'+curNum).val('');
					$('#rmitmcod_'+curNum).val('');
					$('#rmitmdes_'+curNum).val('');
					$('#rmitmttl_'+curNum).val('');
					alert('Item added successfully.');
					window.location.reload();
				}
			}); 
		} else {
			alert('Required fields are empty!');
		}

		var res = getOtherCost();
		if(res)
			getNetTotal();
		
	});
	
	var hisurl = "{{ url('manufacture/order_history/') }}";
	$('.order-history').click(function() {
		var sup_id = $('#supplier_id').val();
		$('#historyData').load(hisurl+'/'+sup_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('manufacture/account_data/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('manufacture/account_data/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false;
		} else {
			
			var isPrice = getAutoPrice(curNum);
			var res = getLineTotal(curNum);
			
			if(res) {
				getNetTotal();
			}
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var isPrice = getAutoPrice(curNum);
		var res = getLineTotal(curNum);
		
		if(res) {
			getNetTotal();
		}
		
		$('#frmManufacture').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	$(document).on('blur', '.line-cost', function(e) { 

		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	$(document).on('blur', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	
	$(document).on('change', '.oc-curr', function(e) { //$('.oc-curr').on('change', function(e){
		var res = this.id.split('_');
		var curNum = res[1]; 
		var curr_id = e.target.value; 
		
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#ocrate_'+curNum).val((data==0)?'':data);
			
				getNetTotal();
			});
	});
	
	

	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('manufacture/getvoucher/') }}/" + vchr_id, function(data) { 
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
	

	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		var item_id = $('#itmid_'+curNum).val();
		if( $('#export').is(":checked") || ($('#taxcode_'+curNum+' option:selected').val()=='EX') ) {
			$('#vat_'+curNum).val(0);
			$('#vatdiv_'+curNum).val('0%');
		} else {
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) {
				$('#vat_'+curNum).val(data);
				$('#vatdiv_'+curNum).val(data+'%');
				srvat = data;
				
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
				//$('#itmcst_'+curNum).val(data.price);
				//getNetTotal();
			});
		}
		
	});
	
	//CHNG
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#purchase_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#account_data .custRow', function(e) { 
		var num = $('#num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
		//$('#frmManufacture').bootstrapValidator('revalidateField', 'cracnt_'+num);
	});
	
	$(document).on('blur', '.oc-line-vat', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
			getNetTotal();
	});
	
		
	$(document).on('keyup', '.discount-cal', function(e) { 
		getNetTotal();
	});
	
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	var src = "{{ url('itemmaster/ajax_search/') }}";
	$('input[name="item_code[]"]').autocomplete({
		
        source: function(request, response) {
            $.ajax({
                url: src+'/C',
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) {
			var itm_id = ui.item.id;
			$("#itmdes_1").val(ui.item.name);
			$('#itmcod_1').val( ui.item.value );
			$('#itmid_1').val( itm_id );
			
			if($('.export').is(":checked") ) { 
				$('#vatdiv_1').val( '0%' );
				$('#vat_1').val( 0 );
			} else {
				srvat = ui.item.vat;
				$('#vatdiv_1').val( srvat+'%' );
				$('#vat_1').val( srvat );
			}
			
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_1').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_1').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); 
				});
			});
		},
        minLength: 1,
    });
	
	
	$(document).on('blur', 'input[name="item_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		var code = this.value;
		if(code!='') {
		$.get("{{ url('itemmaster/item_load/') }}/" + code, function(data) { //console.log(data);
			$("#itmdes_"+curNum).val(data.description);
			$('#itmid_'+curNum).val( data.id );
			$('#vat_'+curNum).val(data.vat)
			$('#vatdiv_'+curNum).val(data.vat+'%');
			//$('#itmcst_'+curNum).val(data.pur_cost);
			
			
			/* $('#itmunt_'+curNum).find('option').remove().end();
			$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",data.unit_id)
							.text(data.unit)); */
		});
		}
	});
	
	//Multiple Unit loading........
	$(document).on('blur', 'input[name="item_name[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		var itm_id = $('#itmid_'+curNum).val();
		if(itm_id !='') {
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_'+curNum).find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); 
							$('#hidunit_'+curNum).val(value.unit_name);
				});
			});
		}	
	});
	
	
	$('input[name="item_name[]"]').autocomplete({
		
        source: function(request, response) {
            $.ajax({
                url: src+'/N',
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) {
			var itm_id = ui.item.id;
			$("#itmdes_1").val(ui.item.value);
			$('#itmcod_1').val( ui.item.name );
			$('#itmid_1').val( itm_id );
			
			if($('.export').is(":checked") ) { 
				$('#vatdiv_1').val( '0%' );
				$('#vat_1').val( 0 );
			} else {
				srvat = ui.item.vat;
				$('#vatdiv_1').val( srvat+'%' );
				$('#vat_1').val( srvat );
			}
			
			$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
				$('#itmunt_1').find('option').remove().end();
				$.each(data, function(key, value) {   
				$('#itmunt_1').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.unit_name)); $('#hidunit_1').val(value.unit_name);
				});
			});
		},
        minLength: 3,
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
	
	$(document).on('click', '.pur-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_purchase_info/') }}/"+item_id; 
		   $('#purchasehisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
	   }
    });
	
	$(document).on('click', '.sales-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_sales_info/') }}/"+item_id; 
		   $('#saleshisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
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
    
    $(document).on('click', '#weadd', function(e) { 
	   e.preventDefault();
	   //var res = this.id.split('_');
	   var curNum = 1;
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var rwUrl = "{{ url('itemmaster/get_rawmatwe/') }}/"+item_id;
		   $('#weData_'+curNum).load(rwUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#rawmatPrntItm_'+curNum).toggle();
	   }
	   $('#weData_1').toggle();
    });
    
	$(document).on('click', '.troutDVa', function(e) { console.log('fsdf');
		e.preventDefault();
		$('#troutDV').toggle();
	})

	$(document).on('click', '.trinDVa', function(e) { console.log('q	qw');
		e.preventDefault();
		$('#trinDV').toggle();
	})

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
	var itmurl = "{{ url('manufacture/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccountCr(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('manufacture/account_data/') }}/"+curNum+"/cr";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getDocument() { 
	var supplier_id = $("#supplier_id").val();
	var doc = $('#document_type option:selected').val();
	
	if($("#supplier_name").val()=='') {
		alert('Please select a supplier first!');
		return false
	} else if(doc=='') {
		alert('Please select document type!');
		return false
	}
	
	var ht = $(window).height();
	var wt = $(window).width();
	
	if(doc=='PO')
		var pourl = "{{ url('purchase_order/po_data/') }}/"+supplier_id+"/po";
	else if(doc=='SDO')
		var pourl = "{{ url('suppliers_do/sdo_data/') }}/"+supplier_id+"/sdo";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getDocument() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('production/get_data') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop