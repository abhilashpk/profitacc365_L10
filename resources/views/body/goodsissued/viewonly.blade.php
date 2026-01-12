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
        
    <link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
    
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
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
                    <a href="#">Goods Issued Note</a>
                </li>
                <li class="active">
                    View
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> View
                            </h3>
							
						
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmGoodsInvoice" id="frmGoodsInvoice" action="{{ url('goods_issued/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="goods_issued_id" id="goods_issued_id" value="{{ $orderrow->id }}">
								<input type="hidden" name="voucher_id" value="{{ $orderrow->voucher_id }}">
								
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
                                    <label for="input-text" class="col-sm-2 control-label">GI. No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">GI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}"  id="voucher_date" placeholder="GI. Date" autocomplete="off"/>
                                    </div>
                                </div>
									<?php if($formdata['job_entry']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Itemwise Job Entry</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="export" id="item_job" name="item_job" value="1" {{($orderrow->is_itemjob==1) ? 'checked':'' }}>
											</label>
										</div>
									</div>
                                </div>
                                	<?php } else { ?>
								        <input type="hidden" name="item_job" id="item_job">
								    <?php } ?>
								<?php if($formdata['job_code']==1) { ?>
								<div class="form-group jobDtls">
                                  <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Job Code</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" value="{{$orderrow->name}}">
										<input type="hidden" name="job_id" id="job_id" value="{{$orderrow->job_id}}">
									</div>
                                </div>
								 <?php } else { ?>
								        <input type="hidden" name="jobname" id="jobname">
								<?php } ?>
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="{{$orderrow->description}}">
                                    </div>
                                </div>
								<?php } else { ?>
								        <input type="hidden" name="description" id="description">
								<?php } ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Stock Account</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="stock_account" id="stock_account" class="form-control" autocomplete="off" data-toggle="modal" data-target="#stockac_modal" value="{{$orderrow->account}}">
										<input type="hidden" name="account_master_id" id="account_master_id" value="{{$orderrow->account_master_id}}">
									</div>
                                </div>
								
								<div class="form-group jobDtls">
                                    <label for="input-text" class="col-sm-2 control-label">Stock CONS</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="job_account" id="job_account" class="form-control" autocomplete="off" data-toggle="modal" data-target="#account_modal" value="{{$orderrow->jobaccount}}">
										<input type="hidden" name="job_account_id" id="job_account_id" value="{{$orderrow->job_account_id}}">
										<input type="hidden" name="job_account_id_old" value="{{$orderrow->job_account_id}}">
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
								
								@php $i = 0; $num = count($orditems); @endphp
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								@foreach($orditems as $item)
								@php $i++; @endphp
									<div class="itemdivChld">
										@if($orderrow->is_itemjob==0)
										<table border="0" class="table-dy-row">
											<tr>
												<td width="16%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
													<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
												</td>
												<td width="29%">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" data-toggle="modal" data-target="#item_modal" value="{{$item->item_name}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
												</td>
												<td width="8%">
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" class="form-control line-cost" value="{{$item->unit_price}}">
													<input type="hidden" name="actcost[]" id="itmcsthd_{{$i}}">
													<input type="hidden" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control cost" value="{{$item->vat.'% - '.$item->vat_amount}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control cost" value="{{$item->vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$item->vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]" class="form-control line-discount" value="0">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$item->total_price}}">
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
										@else
										<table border="0" class="table-dy-row">
											<tr>
												<td width="14%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
													<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
												</td>
												<td width="26%">
													<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" data-toggle="modal" data-target="#item_modal" value="{{$item->item_name}}">
												</td>
												<td width="13%">
													<input type="text" id="jobcod_{{$i}}" autocomplete="off" value="{{$item->jobcode}}" name="jobcod[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													<input type="hidden" name="jobid[]" id="jobid_{{$i}}" value="{{$item->job_id}}">
												</td>
												<td width="15%">
													<input type="text" id="accod_{{$i}}" name="ac_code[]" value="{{$item->master_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#ac_modal" placeholder="A/C Name">
													<input type="hidden" name="account_id[]" id="acntid_{{$i}}" value="{{$item->account_id}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
												</td>
												<td width="7%">
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" class="form-control line-cost" value="{{$item->unit_price}}">
													<input type="hidden" name="actcost[]" id="itmcsthd_{{$i}}">
													<input type="hidden" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control cost" value="{{$item->vat.'% - '.$item->vat_amount}}"><!--<div class="h5" id="vatdiv_1"></div>--> 
													<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control cost" value="{{$item->vat}}">
													<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$item->vat_amount}}">
													<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]" class="form-control line-discount" value="0">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$item->total_price}}">
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
										@endif
										<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_{{$i}}" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="more_info" id="more_info">
								            <?php } ?>
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
												<?php if($formdata['item_location']==1) { ?>
											<div id="loc" >
												<button type="button" id="loc_{{$i}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								              <input type="hidden" name="item_loc" id="item_loc">
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
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
										<span class="small" id="fc_label">Currency</span><input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{$orderrow->total}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<input type="hidden"  id="discount" name="discount" class="form-control" value="0">
								<input type="hidden"  id="discount_fc" name="discount_fc" class="form-control" value="0">
								<br/>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{$orderrow->net_amount}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly placeholder="0">
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
								<hr/>
								
								<!--<div id="showmenu">
								<button type="button" id="infoadd" class="btn btn-primary btn-xs">Add Info..</button></div>-->
								<div class="infodivPrnt">
									<div class="infodivChld">							
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Additional Info</label>
										   
											<div class="col-xs-10">
												<div class="col-xs-5">
													<input type="text" name="title[]" class="form-control" placeholder="Title">
												</div>
												<div class="col-xs-5">
													<input type="text" name="desc[]" class="form-control" placeholder="Description">
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-add-info" >
														<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add more
													 </button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                       
										<a href="{{ url('goods_issued') }}" class="btn btn-primary">Back</a>
										
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
					<input type="hidden" id="itmdsnt_1" name="line_discount[]" class="form-control line-discount" value="0">
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
				<input type="hidden" id="itmdsnt_1" name="line_discount[]" class="form-control line-discount" value="0">
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

//$('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$(document).ready(function () { 
	@if($orderrow->is_itemjob==0)
		$('#jc').toggle(); $('#ac').toggle();
	@else
		$('.jobDtls').toggle();
	@endif
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);$("#c_label").toggle();
	$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $('#other_cost_fc').toggle(); $('.OCdivPrnt').toggle(); 
    $('#frmGoodsInvoice').bootstrapValidator({
        fields: {
			voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			jobname: { validators: { notEmpty: { message: 'The job name is required and cannot be empty!' } }},
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
	var rowNum = $('#rowNum').val(); //new change........... 
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		
		$.get("{{ url('goods_issued/getdeptvoucher/') }}/" + dept_id, function(data) { 
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
	
	
	$(document).on('click', '.btn-add-item', function(e) 
    { 
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
			newEntry.find($('input[name="actcost[]"]')).attr('id', 'itmcsthd_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); //new change
			newEntry.find($('input[name="othr_cost[]"]')).attr('id', 'othrcst_' + rowNum);
			newEntry.find($('input[name="net_cost[]"]')).attr('id', 'netcst_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find('input').val(''); 
			newEntry.find('select').find('option').remove().end().append('<option value="">Select</option>');//new change
			
			newEntry.find($('input[name="jobid[]"]')).attr('id', 'jobid_' + rowNum);
			newEntry.find($('input[name="jobcod[]"]')).attr('id', 'jobcod_' + rowNum);
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'acntid_' + rowNum);
			newEntry.find($('input[name="ac_code[]"]')).attr('id', 'accod_' + rowNum);
			
			newEntry.find($('.btn-remove-item')).attr('data-id','rem_'+rowNum);
			
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
		ids = (remitem=='')?$('#p_orditmid_'+curNum).val():remitem+','+$('#p_orditmid_'+curNum).val();
		$('#remitem').val(ids);
		
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
	
	$(document).on('click', '.btn-add-info', function(e) 
    { 
        e.preventDefault();

        var controlForm = $('.controls .infodivPrnt'),
            currentEntry = $(this).parents('.infodivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
			controlForm.find('.btn-add-info:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-info').addClass('btn-remove-info')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Remove ');
    }).on('click', '.btn-remove-info', function(e)
    { 
		$(this).parents('.infodivChld:first').remove();

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
		$('#jobname').val($(this).attr("data-name"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
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
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="job_account"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#accountData').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
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
	
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		var isPrice = getAutoPrice(curNum);
		if(isPrice){
			var lntot = getLineTotal(curNum);
			if(lntot)
				getNetTotal();
		}
	});
	
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	$(document).on('keyup', '.line-quantity', function(e) {
		getNetTotal();
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


	$(document).on('blur', '#discount', function(e) { 
		var btotal = parseFloat($('#total').val());
		var bdiscount = parseFloat( ($('#discount').val()=='')?0:$('#discount').val() );
		$('#net_amount').val(btotal-bdiscount);
		if($('#is_fc').is(":checked")){
			var rate = parseFloat($('#currency_rate').val());
			$('#total_fc').val(btotal * rate);
			$('#discount_fc').val(bdiscount * rate);
			$('#net_amount_fc').val( (btotal - bdiscount) * rate);
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

$(document).on('click', '#account_modal .custRow', function(e) {
		$('#job_account').val($(this).attr("data-name"));
		$('#job_account_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

</script>
@stop

