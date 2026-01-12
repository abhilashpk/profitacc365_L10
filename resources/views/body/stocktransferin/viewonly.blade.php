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
                Stock Transfer in
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Stock Transfer</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> View Stock Transfer
                            </h3>
							
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmStockTransfer" id="frmStockTransfer" action="{{ url('stock_transferin/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="transfer_id" id="transfer_id" value="{{ $orderrow->id }}">
								<input type="hidden" name="is_mfg" value="0">
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
                                    <label for="input-text" class="col-sm-2 control-label">STI. No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
                                    </div>
                                </div>
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reference No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="reference_no" name="reference_no" autocomplete="off" value="{{$orderrow->reference_no}}" placeholder="Reference No.">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">STI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" placeholder="STI. Date"/>
                                    </div>
                                </div>
								
								@can('mv-list')
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Debit Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="debit_account" id="debit_account" class="form-control" readonly value="{{$orderrow->name_dr}}">
										<input type="hidden" name="account_dr" id="account_dr" class="form-control" value="{{$orderrow->account_dr}}">
									</div>
                                </div>
								@else
                                 
                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Debit Account</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="account_dr" id="account_dr" class="form-control" value="{{$orderrow->account_dr}}">
                                        <div class="input-group">
                                        <input type="text" name="debit_account" id="debit_account" class="form-control" readonly value="{{$orderrow->name_dr}}">
                                        <span class="input-group-addon inputsa"><i class="fa fa-fw fa-edit"></i></span>
										</div>
									</div>
                                </div>
                                 
                                  @endcan
                                 
                                 
								@can('mv-list')
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Credit Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="credit_account" id="credit_account" class="form-control" readonly value="{{$orderrow->name_cr}}">
										<input type="hidden" name="account_cr" id="account_cr" class="form-control" value="{{$orderrow->account_cr}}">
									</div>
                                </div>
                                 @else
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Credit Account</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" name="account_cr" id="account_cr" class="form-control" value="{{$orderrow->account_cr}}">
                                        <div class="input-group">
                                        <input type="text" name="credit_account" id="credit_account" class="form-control" readonly value="{{$orderrow->name_cr}}">
                                        <span class="input-group-addon inputcr"><i class="fa fa-fw fa-edit"></i></span>
									</div>	
									</div>
                                </div>
                                @endcan
                                
                                
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
								@php $i = $total_hd = 0; $num = count($orditems); @endphp
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								@foreach($orditems as $item)
								@php $i++; @endphp
								
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="transfer_item_id[]" id="ltitmid_{{$i}}" value="{{$item->id}}">
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
														<input type="number" id="itmqty_{{$i}}" autocomplete="off" step="any" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="hidden" id="packing_{{$i}}" name="packing[]" value="{{($item->is_baseqty==1)?1:$item->packing}}">
														<input type="number" id="itmcst_{{$i}}" value="{{$item->price}}" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" value="{{$item->item_total}}" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_{{$i}}" name="item_total[]">
														<input type="hidden" id="othrcst_1" step="any" name="othr_cost[]" class="form-control" value="{{$item->othercost_unit}}">
														<input type="hidden" id="netcst_1" step="any" name="net_cost[]" class="form-control" value="{{$item->netcost_unit}}">
													</td>
													<td width="1%"> @php $total_hd += $item->item_total; @endphp
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
											<?php if($formdata['location']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_id" id="location_id">
								<?php } ?>
											<div class="locPrntItm" id="locPrntItm_{{$i}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$i}}">
														<?php 
														if(array_key_exists($item->id, $itemlocedit)) {
														
															 ?>
														<br/>
														<div class="col-xs-4">
															<table class="table table-bordered table-hover">
																<thead>
																<tr>
																	<th>Location</th>
																	<th>Stock</th>
																	<th>Quantity</th>
																</tr>
																</thead>
																<tbody>
																<?php 
																foreach($itemloc as $key => $row) {
																$quantity = $id = '';
																$location_id = $row->id; $cqty = $row->cqty;
																if(array_key_exists($key, $itemlocedit[$item->id])) { 
																	$cqty = $itemlocedit[$item->id][$key]->cqty;
																	$quantity = $itemlocedit[$item->id][$key]->quantity;
																	$location_id = $itemlocedit[$item->id][$key]->location_id;
																	$id = $itemlocedit[$item->id][$key]->id;
																}
																?>
																<tr>
																	<td>{{ $row->name }}</td>
																	<td>{{ $cqty }}</td>
																	<td class="num"><input type="number" name="locqty[<?php echo $i-1;?>][]" value="{{$quantity}}" class="loc-qty-{{$i}}" data-id="{{$i}}">
																	<input type="hidden" name="locid[<?php echo $i-1;?>][]" value="{{$location_id}}"/>
																	<input type="hidden" name="editid[<?php echo $i-1;?>][]" value="{{$id}}"/></td>
																</tr>
																<?php } ?>
																</tbody>
															</table>
														</div>
															<?php  } ?>
													</div>
												</div>
											</div>
										
									</div>
									@endforeach
								</div>
								</fieldset>
								<?php if($formdata['other_cost']==1) { ?>
								<fieldset>
									<legend>
										<div id="oc_showmenu"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Other Cost</button></div>
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
															<input type="text" id="othrcst_1" step="any" name="othr_cost[]" class="form-control" readonly>
														<input type="text" id="netcst_1" step="any" name="net_cost[]" class="form-control" readonly>
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
								<?php } else { ?>
								<input type="hidden" name="other_cost" id="other_cost">
								<?php } ?>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Quantity</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<input type="number" id="totalqty" step="any" name="total" class="form-control spl" value="{{$orderrow->total_qty}}" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<input type="hidden" id="total" step="any" name="total_hd" value="{{$total_hd}}">
								<input type="hidden" step="any" id="other_cost" name="other_cost" readonly class="form-control" value="{{$orderrow->other_cost}}" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control" value="{{$orderrow->other_cost}}" placeholder="0">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Price</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<input type="number" id="totalamt" step="any" name="total_price" class="form-control spl" value="{{$orderrow->total_amt}}" readonly placeholder="0">
										</div>
									</div>
                                </div> 
								
								<br/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        
										<a href="{{ url('stock_transferin') }}" class="btn btn-primary">Back</a>
										
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

$(document).ready(function () { 
	
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	<?php if(count($ocrow) == 0) { ?>
		$('.OCdivPrnt').toggle(); $('.btn-remove-oc').hide(); 
	<?php } ?>
	
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle();
	var urlcode = "{{ url('location_transfer/checkrefno/') }}";
    $('#frmStockTransfer').bootstrapValidator({
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
        $('#frmStockTransfer').data('bootstrapValidator').resetForm();
    });
		
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		
		var lineqty = 0; var lineTotal = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  lineqty = lineqty + parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
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
		
		
		//console.log('lineTotal: '+lineTotal);
		$('#totalqty').val(lineqty);
		$('#totalamt').val(lineTotal.toFixed(2));
		
	}
	
	function getAutoPrice(curNum) {
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
	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log('tax2: '+vatcur);
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineTotal	 = lineQuantity * lineCost;
		
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
	
		return lineTotal;
	} 
	
	
	function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = 'debit'; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccountCr(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = 'credit'; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}	
	
	//calculation other cost...
	function getOtherCost() {
		 
		var otherCost = 0; var otherCostTx = 0; var otherCostfc = 0; var amtfc = 0; 
		if( $('.OCdivPrnt').is(":visible") ) { 
			$( '.oc-line' ).each(function() { console.log('v '+this.value);
				var res = this.id.split('_');
				var curNum = res[1];
				var ocVat = 0;
				var ocrate = 1;
				var ocamt_ln = this.value * ocrate;
				var ocvatamt = 0;//(ocamt_ln * ocVat) / 100;
				otherCostTx = parseFloat(otherCostTx + ocamt_ln);
				otherCost = parseFloat(otherCost + ocamt_ln);
				//$('#ocfcamt_'+curNum).val(ocamt_ln);
				
				amtfc = parseFloat(this.value);
				var vatamtfc = (amtfc * ocVat) / 100;
				otherCostfc = otherCostfc + amtfc + parseFloat(vatamtfc);
				
			}); console.log('oct1: '+otherCostTx);
			$('#other_cost').val(otherCostTx); //.toFixed(2)
			$('#other_cost_fc').val(otherCostfc); //.toFixed(2)
		}
		
		if(otherCost!=0) { 
			var totalQty = 0;
			 $('input[name="quantity[]"]').each(function(){
				totalQty += +$(this).val();
			 });
			 
			 var totalCost = parseFloat( ($('#total').val()=='')?0:$('#total').val() );
			 var n = 1;
			$( '.itemdivChld' ).each( function() {
				var costPerUnit; var nTotal;
				var itemCost = parseFloat( ($('#itmcst_'+n).val()=='')?0:$('#itmcst_'+n).val() );
				var itemQty = parseFloat( ($('#itmqty_'+n).val()=='')?0:$('#itmqty_'+n).val() );
				
				costPerUnit = (otherCost / totalCost) * itemCost;  console.log(otherCost+' / '+totalCost+' * '+itemCost);
				$('#othrcst_'+n).val(costPerUnit.toFixed(2));
				$('#netcst_'+n).val( (costPerUnit + itemCost).toFixed(2) );
				n++;
			});
		}
		
		return true;
	}
	
$(function() {	
	var rowNum = $('#rowNum').val();
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('stock_transferin/getdeptvoucher/') }}/" + dept_id, function(data) { 
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
			
		});
	});
	
	$('.inputsa').on('click', function(e) {
		$('#debit_account').attr("onClick", "javascript:getAccount(this)");
	});
	
	$('.inputcr').on('click', function(e) {
		$('#credit_account').attr("onClick", "javascript:getAccountCr(this)");
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
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			//newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); //NEW CHNG
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			
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
		
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#ltitmid_'+curNum).val():remitem+','+$('#ltitmid_'+curNum).val();
		$('#remitem').val(ids);
		
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
	$(document).on('click', '.itemRow', function(e) { 
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
	
	
	$(document).on('change', '.line-unit', function(e) {
		var unit_id = e.target.value; 
		var res = this.id.split('_');
		var curNum = res[1];
		
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var row = getLineTotal(curNum);
		if(row) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
	});
	
	$('input,select').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
	$(document).on('keyup', '.oc-line', function(e) {
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
	
	$(document).on('click', '.accountRow', function(e) { 
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
	
	$(document).on('click', '.accountRowall', function(e) { 
		var nm = $('#paccount_data #anum').val();
		$('#cracnt_'+nm).val( $(this).attr("data-name") );
		$('#cracntid_'+nm).val( $(this).attr("data-id") );
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

</script>
@stop
