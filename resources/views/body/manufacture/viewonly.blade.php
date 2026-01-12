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
                    View
                </li>
            </ol>
        </section>
        <!--section ends-->
		
		<!--section ends-->
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> View Manufacture Voucher
                            </h3>
							
							<div class="pull-right">
							
							 <a href="{{ url('manufacture/print/'.$orderrow->id) }}" target="_blank" class="btn btn-info btn-sm">
								<span class="btn-label">
									<i class="fa fa-fw fa-print"></i>
								</span>
							 </a>
							
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmManufacture" id="frmManufacture" action="{{ url('manufacture/update/'.$mid) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="mid" value="{{$mid}}">
								<input type="hidden" name="is_mfg" value="1">
								<input type="hidden" name="reference_no">
								
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100% ;background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" {{($orderrow->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Voucher</label>
									 <div class="col-sm-10">
									   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
										   @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" <?php echo ($orderrow->voucher_id==$voucher['id'])?'selected':'';?>>{{ $voucher['voucher_name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								@endif
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$voucherno}}">
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Voucher Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en'  id="voucher_date" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" placeholder="STI. Date"/>
                                    </div>
                                </div>
								
								<a href="#" class="trinDVa"><b>Transfer In Accounts</b></a><hr/>
								<div id="trinDV">
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Debit Account</label>
										<div class="col-sm-10">
											<input type="text" name="debit_account" id="account_dr_name" class="form-control" readonly value="{{$mfgrow->dr_name}}">
											<input type="hidden" name="account_dr" id="account_dr_id" class="form-control" value="{{$mfgrow->account_dr}}">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Credit Account</label>
										<div class="col-sm-10">
											<input type="text" name="credit_account" id="account_cr_name" class="form-control" readonly value="{{$mfgrow->cr_name}}">
											<input type="hidden" name="account_cr" id="account_cr_id" class="form-control" value="{{$mfgrow->account_dr}}">
										</div>
									</div>
								</div>

								<a href="#" class="troutDVa"><b>Transfer Out Accounts</b></a><hr/>	
								<div id="troutDV">							
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Debit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_dr_to" id="account_dr_id" value="{{$mfgrow->account_dr_to}}" class="form-control">
											<input type="text" name="purchase_account_to" id="account_dr_name_to" class="form-control" value="{{$mfgrow->dr_name_to}}" readonly>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Credit Account</label>
										<div class="col-sm-10">
											<input type="hidden" name="account_cr_to" id="account_cr_id_to" value="{{$mfgrow->account_dr_to}}" class="form-control">
												<input type="text" name="purchase_account_to" id="account_cr_name_to" class="form-control" value="{{$mfgrow->cr_name_to}}" readonly>
										</div>
									</div>
								</div>
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" autocomplete="off" value="{{$orderrow->description}}" placeholder="Description">
                                    </div>
                                </div>
								 <?php } else { ?>
								<input type="hidden" name="description" id="description">
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
								{{--*/ $i = 0; $num = count($orditems); /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<input type="hidden" id="mfremitem" name="remove_item_mf">
								<div class="itemdivPrnt">
								@foreach($orditems as $key => $item)
								{{--*/ $i++; /*--}}
								
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="transfer_item_id[]" id="ltitmid_{{$i}}" value="{{$item->id}}">
														<input type="hidden" name="mf_item_id[]" id="mfitmid_{{$i}}" value="{{$mfitems[$key]->id}}">
														<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
														
														<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code" value="{{$item->item_code}}">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</td>
													<td width="8%" class="itcod">
														<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$i}}" value="{{$item->quantity}}">
														<input type="number" id="itmqty_{{$i}}" autocomplete="off" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:$item->packing}}">
														<input type="number" id="itmcst_{{$i}}" value="{{$item->price}}" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{$item->item_total}}" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_{{$i}}" name="item_total[]">
														<input type="hidden" name="actcost[]" id="itmcsthd_{{$i}}">
													</td>
													<td width="1%">
													@if($num==$i)
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@else
														<button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													@endif
													
													</td>
												</tr>
											</table>
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$i}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="more_info" id="more_info">
								            <?php } ?>
								            <?php if($formdata['purchase']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$i}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="purchase" id="purchase">
								            <?php } ?>
								            <?php if($formdata['sales']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="saleshisItm_{{$i}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="sales" id="sales">
								            <?php } ?>
											<?php if($formdata['rawmaterial']==1) { ?>
											<div id="moreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="rawmat_{{$i}}" class="btn btn-primary btn-xs raw-mat">Raw Materials</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="rawmaterial" id="rawmaterial">
								            <?php } ?>
											<?php if($formdata['add_rawmaterial']==1) { ?>
											<div id="admoreinfo" style="float:left;padding-right:5px;">
												<button type="button" id="adrawmat_{{$i}}" class="btn btn-primary btn-xs ad-raw-mat">Add Raw Materials</button>
											</div>
												<?php } else { ?>
								              <input type="hidden" name="add_rawmaterial" id="add_rawmaterial">
								            <?php } ?>
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}"></div>
												</div>
											</div>
											
											<div class="rawmatPrntItm" id="rawmatPrntItm_{{$i}}" style="float:left;padding-right:5px;padding-top:2px;">
												<div class="rawmatChldItm">							
													<div class="table-responsive rawmat-data" id="rawmatData_{{$i}}"></div>
												</div>
											</div>
											
											<div id="rMat_{{$i}}" class="rMat" style="float:right;padding-left:195px;padding-top:2px;">
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
																<input type="hidden" id="rmitmid_{{$i}}" name="rmitem_id[]">
																<input type="text" id="rmitmcod_{{$i}}" name="rmitemcode[]" class="form-control" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#rmitem_modal">
															</td>
															<td width="29%">
																<input type="text" name="rmitem_name[]" id="rmitmdes_{{$i}}" autocomplete="off" class="form-control" placeholder="Item Description">
															</td>
															<td width="8%">
																<input type="number" id="rmitmqty_{{$i}}" step="any" name="rmquantity[]" autocomplete="off" class="form-control rmline-quantity" placeholder="Qty.">
															</td>
															<td width="8%">
																<input type="number" id="rmitmcst_{{$i}}" step="any" name="rmcost[]" autocomplete="off" class="form-control rmline-cost" placeholder="Cost/Unit">
															</td>
															<td width="11%">
																<input type="number" id="rmitmttl_{{$i}}" step="any" name="rmline_total[]" class="form-control rmline-total" readonly placeholder="Total">
																<input type="hidden" id="rmitmtot_{{$i}}" name="rmitem_total[]">
															</td>
															<td width="1%">
																 <button type="button" id="rmbtn_{{$i}}" class="btn-success btn-add-rmitem">
																	<i class="fa fa-fw fa-save"></i>
																 </button>
															</td>
														</tr>
													</table>
												</fieldset>
											</div>
										
									</div>
									@endforeach
								</div>
								</fieldset>
								
								<fieldset>
									<legend>
									    <?php if($formdata['other_cost']==1) { ?>
										<div id="oc_showmenu"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
										<?php } else { ?>
								              <input type="hidden" name="ocadd" id="ocadd">
								         <?php } ?>
									</legend>
									<?php $ocnum = count($ocrow); ?>
									<input type="hidden" id="remoc" name="remove_oc">
									<input type="hidden" id="ocrowNum" value="{{($ocnum==0)?1:$ocnum}}">
									<div class="OCdivPrnt">
										<?php if(count($ocrow) > 0) { $i = 0;  ?>
										
										<?php foreach($ocrow as $row) { $i++; ?>
										<div class="OCdivChld">
											<table border="0" class="table-dy-row">
												<tr>
													<td width="15%">
														<input type="hidden" name="oc_id[]" id="ocid_{{$i}}" value="{{$row->id}}">
														<input type="hidden" name="dr_acnt_id[]" id="dracntid_{{$i}}" value="{{$row->dr_account_id}}">
														<input type="hidden" name="cur_dr_acnt_id[]" id="curdracntid_{{$i}}" value="{{$row->dr_account_id}}">
														<span class="small">Debit Account</span>
														<input type="text" id="dracnt_{{$i}}" name="dr_acnt[]" value="{{$row->dr_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
													</td>
													<td width="12%">
														<span class="small">Reference</span>
														<input type="text" name="oc_reference[]" id="ocref_{{$i}}" value="{{$row->reference}}" autocomplete="off" class="form-control" placeholder="Reference">
													</td>
													<td width="15%">
														<span class="small">Description</span>
														<input type="text" name="oc_description[]" id="ocdesc_{{$i}}" value="{{$row->description}}" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="8%">
														<span class="small">Amount</span>
														<input type="number" name="oc_amount[]" step="any" id="ocamt_{{$i}}" value="{{$row->amount}}" autocomplete="off" class="form-control oc-line" placeholder="Amount">
													</td>
													<td width="18%">
														<span class="small">Credit Account</span>
														<input type="hidden" name="cr_acnt_id[]" id="cracntid_{{$i}}" value="{{$row->cr_account_id}}">
														<input type="hidden" name="cur_cr_acnt_id[]" id="curcracntid_{{$i}}" value="{{$row->cr_account_id}}">
														<input type="text" id="cracnt_{{$i}}" name="cr_acnt[]" value="{{$row->cr_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
													</td>
													<td width="5%"><br/>
													<?php if(count($ocrow) == $i) { ?>
														 <button type="button" class="btn-success btn-add-oc" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													<?php } else { ?>
														<button type="button" class="btn-success btn-add-oc" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													<?php } ?>
													</td>
												</tr>
											</table>
																						
										</div>
										<?php } } else { ?>
											<div class="OCdivChld">
												<div class="form-group">
												<table border="0" class="table-dy-row">
													<tr>
														<td width="15%">
															<input type="hidden" name="oc_id[]" id="ocid_1">
															<input type="hidden" name="dr_acnt_id[]" id="dracntid_1">
															<span class="small">Debit Account</span>
															<input type="text" id="dracnt_1" name="dr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" placeholder="Debit Account">
														</td>
														<td width="12%">
															<span class="small">Reference</span>
															<input type="text" name="oc_reference[]" id="ocref_1" autocomplete="off" class="form-control" placeholder="Reference">
														</td>
														<td width="15%">
															<span class="small">Description</span>
															<input type="text" name="oc_description[]" id="ocdesc_1" autocomplete="off" class="form-control" placeholder="Description">
														</td>
														<td width="8%">
															<span class="small">Amount</span>
															<input type="number" name="oc_amount[]" step="any" id="ocamt_1" autocomplete="off" class="form-control oc-line" placeholder="Amount">
														</td>
														<td width="15%">
															<span class="small">Credit Account</span>
															<input type="hidden" name="cr_acnt_id[]" id="cracntid_1">
															<input type="text" id="cracnt_1" name="cr_acnt[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#paccount_modal" placeholder="Credit Account">
														</td>
														<td width="3%"><br/>
															<?php if(count($ocrow) == $i) { ?>
																 <button type="button" class="btn-success btn-add-oc" >
																	<i class="fa fa-fw fa-plus-square"></i>
																 </button>
																 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_1">
																	<i class="fa fa-fw fa-minus-square"></i>
																 </button>
															<?php } else { ?>
																<button type="button" class="btn-success btn-add-oc" >
																	<i class="fa fa-fw fa-plus-square"></i>
																 </button>
																 <button type="button" class="btn-danger btn-remove-oc" data-id="rem_1">
																	<i class="fa fa-fw fa-minus-square"></i>
																 </button>
															<?php } ?>
														</td>
													</tr>
												</table>
												</div>											
											</div>
										<?php } ?>
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
									<div id="weData_1">
									@php $wenum = count($werow); @endphp
									@if(count($werow) > 0)
									<div class="col-xs-10">
										<table class="table table-bordered table-hover">
											<thead>
											<tr>
												<th>Item Code</th>
												<th>Description</th>
												<th>Wastage Qty.</th>
												<th style="width:45%">Cost/Unit</th>
												<th style="width:45%">Total</th>
											</tr>
											</thead>
											<tbody>
											@php $i=0; @endphp   
											@foreach($werow as $row)
											@php $i++; @endphp
											<tr>
												<td><input type="hidden" name="weid[]" value="{{$row->id}}"> {{ $row->item_code }} <input type="hidden" name="weitem[]" value="{{$row->item_id}}"></td>
												<td>{{ $row->description }}</td>
												<td><input type="text" name="wqty[]" id="wqty_{{$i}}" class="we-qty" value="{{$row->quantity}}"></td>
												<td>{{ number_format($row->unit_price,2) }}<input type="hidden" name="uprice[]" id="uprice_{{$i}}" value="{{number_format($row->unit_price,2)}}"></td>
												<td><input type="text" name="weqtytot[]" id="wqtytot_{{$i}}" value="{{$row->total}}"></td>
											</tr>
											@endforeach
											</tbody>
										</table>
									</div>
									@endif
									</div>
								</fieldset>
								
								<hr/>
								<input type="hidden" id="other_cost" name="other_cost" value="{{$mfgrow->other_cost}}">
								<input type="hidden" id="total_hd" step="any" name="total_hd" value="{{($mfgrow->amount-$mfgrow->other_cost)}}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{$orderrow->total_amt}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											
										</div>
									</div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Discount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" placeholder="0">
										</div>
										<div class="col-xs-2">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="total_price" class="form-control spl" readonly value="{{$orderrow->total_amt}}" placeholder="0">
										</div>
										<div class="col-xs-2">
										</div>
									</div>
                                </div>
								<?php if($formdata['footer_edit']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$orderrow->foot_description}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								<br/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                       
										<a href="{{ url('manufacture') }}" class="btn btn-primary">Back</a>
										
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



"use strict";

$(document).ready(function () { 
	$('#trinDV').hide(); $('#troutDV').hide();$('#weData_1').toggle();
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	<?php if(count($ocrow) == 0) { ?>
		$('.OCdivPrnt').toggle(); $('.btn-remove-oc').hide(); 
	<?php } ?>
	
	$('.rMat').hide();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle();
	var urlcode = "{{ url('location_transfer/checkrefno/') }}";
    $('#frmManufacture').bootstrapValidator({
        fields: {
			reference_no: {
                validators: { 
                    /* notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val(),
								id: validator.getFieldElements('transfer_id').val()
                            };
                        },
                        message: 'This Reference No. is already exist!'
                    }
                }
            },
			location_from: { validators: { notEmpty: { message: 'Location from is required and cannot be empty!' } }},
			location_to: { validators: { notEmpty: { message: 'Location to is required and cannot be empty!' } }},
			'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmManufacture').data('bootstrapValidator').resetForm();
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
		
		if($('.oc-line').length) {
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var n = res[1];
				lineTotal = lineTotal + parseFloat( ($('#ocamt_'+n).val()=='') ? 0 : $('#ocamt_'+n).val() );
			});
		}
		
		if($('.rmline-quantity').length) {
			$( '.rmline-quantity' ).each(function() { 
				var res = this.id.split('_');
				var n = res[1];
				
				var rmcst = parseFloat( ($('#rmitmcst_'+n).val()=='') ? 0 : $('#rmitmcst_'+n).val() );
				var rmqty = parseFloat( ($('#rmitmqty_'+n).val()=='') ? 0 : $('#rmitmqty_'+n).val() );
		
				lineTotal = lineTotal + (rmcst * rmqty);
			});
		}
		
		$('#total').val(lineTotal.toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
				
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total;
		
		$('#net_amount').val(netTotal.toFixed(2));
		$('#net_amount_hid').val(netTotal+discount);
		console.log('hi');
	}
	
	/* function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var supplier_id = $('#supplier_id').val();
		$.ajax({
			url: "{{ url('itemmaster/get_item_cost_avg/') }}",  //get_sale_cost_avg
			type: 'get',
			data: 'item_id='+item_id+'&customer_id=',
			success: function(data) {
				$('#itmcst_'+curNum).val((data==0)?'':data);
				$('#itmcst_'+curNum).focus();
				return true;
			}
		}) 
	} */
	
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
			}
		});
		return true;
	}
	
	function getLineTotal(n) {
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		
		var lineTotal 	 = lineQuantity * lineCost;
		var lineTotalTx 	 = lineQuantity * lineCost;
		
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#itmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	} 
	
	//calculation item line total and discount...
	/* function getLineTotal(n) {
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineTotal	 = lineQuantity * lineCost;
		
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
	
		return lineTotal;
	}  */
	
	
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
			$('#account_dr_name').val(data[0].cr_account_name);
			$('#account_dr_id').val(data[0].cr_id);
			
		});
	});
	
	var rowNum = $('#rowNum').val();
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
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			//newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); //NEW CHNG
			newEntry.find($('.rawmatPrntItm')).attr('id', 'rawmatPrntItm_' + rowNum);
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			if( $('#rawmatPrntItm_'+rowNum).is(":visible") ) 
				$('#rawmatPrntItm_'+rowNum).toggle();
			
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
    }).on('click', '.btn-remove-item', function(e)
    { 
		//new change..
		
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids; var mids;
		
		var rmfemitem = $('#mfremitem').val();
		mids = (rmfemitem=='')?$('#mfitmid_'+curNum).val():rmfemitem+','+$('#mfitmid_'+curNum).val();

		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#ltitmid_'+curNum).val():remitem+','+$('#ltitmid_'+curNum).val();
		
		$('#remitem').val(ids);
		$('#mfremitem').val(mids);
		
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
			/* controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
			controlForm.find('.btn-add-oc:not(:last)').hide();
			controlForm.find('.btn-remove-oc').show();
			
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
		$('.OCdivPrnt').find('.OCdivChld:last').find('.btn-add-oc').show();
		if ( $('.OCdivPrnt').children().length == 1 ) {
			$('.OCdivPrnt').find('.btn-remove-oc').hide();
		}
		getNetTotal();
		e.preventDefault();
		return false;
	});
	
	
	//new change.................
	$(document).on('click', '#item_data .itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
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
	
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
		
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
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
		
		getNetTotal();
		
	});

	$(document).on('keyup blur', '.rmline-cost', function(e) {
			
		var res = this.id.split('_');
		var curNum = res[1];
		var qty = parseFloat( ($('#rmitmqty_'+curNum).val()=='') ? 0 : $('#rmitmqty_'+curNum).val() );
		$('#rmitmttl_'+curNum).val((qty * this.value));
		getNetTotal();
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
	
	
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
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
	

	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) 
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
	
	$(document).on('blur', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	$(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
           $('.OCdivPrnt').toggle();
    });
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#account_data .custRow', function(e) { //accountRow
		var nm = $('#account_data #num').val(); console.log('d'+nm);
		$('#dracnt_'+nm).val( $(this).attr("data-name") );
		$('#dracntid_'+nm).val( $(this).attr("data-id") );
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { //accountRowall
		var nm = $('#paccount_data #anum').val();
		$('#cracnt_'+nm).val( $(this).attr("data-name") );
		$('#cracntid_'+nm).val( $(this).attr("data-id") );
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
    
     $(document).on('click', '#weadd', function(e) { 
	   e.preventDefault();
	   $('#weData_1').toggle();
     })

	$(document).on('click', '.troutDVa', function(e) { console.log('fsdf');
		e.preventDefault();
		$('#troutDV').toggle();
	})

	$(document).on('click', '.trinDVa', function(e) { console.log('q	qw');
		e.preventDefault();
		$('#trinDV').toggle();
	})
	
});

</script>
@stop

