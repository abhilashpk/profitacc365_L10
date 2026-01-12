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
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Purchase Split-Petty Cash
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Split-Petty Cash</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-<?php echo ($orderrow->is_editable==1)?'danger':'primary';?>">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> <?php if($orderrow->is_editable==1) { echo 'Non-Editable'; } else echo 'Edit Invoice';?>
                            </h3>
							
							<div class="pull-right">
							@permission('pi-print')
							 <a href="{{ url('purchase_split/print/'.$orderrow->id.'/'.$print->id) }}" target="_blank"  class="btn btn-info btn-sm">
								<span class="btn-label">
									<i class="fa fa-fw fa-print"></i>
								</span>
							 </a>
							@endpermission
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurorder" id="frmPurorder" action="{{ url('purchase_split/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="purchase_split_id" id="purchase_split_id" value="{{ $orderrow->id }}">
								<input type="hidden" name="voucher_id" value="{{ $orderrow->voucher_id }}">
								<input type="hidden" name="is_pettycash" value="{{ $orderrow->is_pettycash }}">
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
								@endif
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label"><b>Purchase Split-Petty Cash</b></label>
									<div class="col-sm-10">
									   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
										   @foreach($vouchers as $vrow)
											<option value="{{ $vrow->id }}" <?php echo ($orderrow->voucher_id==$vrow->id)?'selected':''?>>{{ $vrow->voucher_name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>PS. No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>"><b>Reference No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" value="<?php echo (old('reference_no'))?old('reference_no'):$orderrow->reference_no; ?>" placeholder="Reference No.">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PS. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' autocomplete="off" value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" id="voucher_date" placeholder="PS. Date"/>
                                    </div>
                                </div>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" value="<?php echo (old('description'))?old('description'):$orderrow->description; ?>" name="description" placeholder="Description">
                                    </div>
                                </div>
                                <?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                       
                                        <input type="text" name="supplier_name" id="supplier_name" value="<?php echo (old('supplier_name'))?old('supplier_name'):$orderrow->supplier; ?>" class="form-control " autocomplete="off" data-toggle="modal" data-target="#acunt_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo (old('supplier_id'))?old('supplier_id'):$orderrow->supplier_id; ?>">
										<input type="hidden" name="old_supplier_id" id="old_supplier_id" value="{{$orderrow->supplier_id}}">
									<!--	<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>-->
										
									
										<div class="col-xs-10" id="newsupplierInfo">
											<div class="col-xs-8">
												<span class="small">Supplier Name</span> <input type="text" id="suppliername" name="suppliername" value="{{$orderrow->supplier_name}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#newsupplier_modal">
											</div>
										</div>
									</div>
                                </div>
								
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#jobb_modal" value="{{$orderrow->code}}">
										<input type="hidden" name="job_id" id="job_id" value="{{$orderrow->job_id}}">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								
								<?php if($formdata['foreign_currency']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" <?php if($orderrow->is_fc==1) echo 'checked';?>>
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}" <?php if($orderrow->currency_id==$curr['id']) echo 'selected'; ?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" autocomplete="off" id="currency_rate" class="form-control" value="{{$orderrow->currency_rate}}" placeholder="Currency Rate">
										</div>
									</div>

                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
								<?php } ?>
								
								<br/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Expense  Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="11%" class="itmHd">
											<span class="small">A/C Name</span>
										</th>
										<th width="18%" class="itmHd">
											<span class="small">Description</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Jobcode</span>
										</th>
								
										<th width="7%" class="itmHd">
											<span class="small">Suppplier Name</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Vat No:</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Qty.</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Cost/Unit</span>
										</th>
										<th width="3%" class="itmHd">
											<span class="small">Tx.Code</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">VAT Amt.</span> 
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
								<div class="itemdivPrnt">
								
								@foreach($orditems as $item)
								<?php if($orderrow->is_fc==1) {
								    $vat_amount = $item->item_vat_fc;
								   } else{
								       $vat_amount= $item->item_vat;
								    }
								    ?>
								{{--*/ $i++; /*--}}
								<div class="itemdivChld">							
										
											<table border="0" class="table-dy-row">
											
												<tr>
													<td width="11%">
														<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
														<input type="hidden" name="account_id[]" id="acntid_{{$i}}" value="{{$item->account_id}}">
														<input type="text" id="accod_{{$i}}" name="ac_code[]" class="form-control" autocomplete="off" value="{{$item->master_name}}" data-toggle="modal" data-target="#ac_modal" placeholder="A/C Name">
													</td>
													<td width="21%">
														<input type="text" name="item_description[]" id="acdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_description}}">
													</td>
													<td width="8%">
														<input type="hidden" name="jobid[]" id="jobid_{{$i}}" value="{{$item->item_jobid}}">
														<input type="text" id="jobcod_{{$i}}" autocomplete="off" name="jobcod[]" class="form-control" value="{{$item->jobcode}}" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Jobcode">
													</td>
											     	
													<td width="7%">
															<input type="text" id="supname_{{$i}}" autocomplete="off"step="any"  name="supname[]" class="form-control " value="{{$item->item_supname}}">
													</td>
													<td width="6%">
														
														<input type="text" id="taxno_{{$i}}" autocomplete="off"step="any"  name="taxno[]" class="form-control " value="{{$item->item_vatno}}">
													</td>
													
                                                     <td width="7%">
														<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
														@foreach($units as $unit)
														@php $sel = ($unit->unit_name==$item->unit_id)?'selected':''; @endphp
														<option id="{{$unit->id}}" {{$sel}}>{{$unit->unit_name}}</option>
														@endforeach
													</td>
													<td width="5%">
														<input type="hidden" name="actual_quantity[]" id="itmactqty_{{$i}}" value="{{$item->quantity}}">
														<input type="number" id="itmqty_{{$i}}" autocomplete="off"step="any"  name="quantity[]" class="form-control line-quantity" value="{{$item->quantity}}">
													</td>
													<td width="7%">
													    
														<input type="number" id="itmcst_{{$i}}" autocomplete="off" step="any" name="cost[]" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" value="{{($orderrow->is_fc==1)?$item->unit_price_fc:$item->unit_price}}">
													</td>
													<td width="6%">
														<select id="taxcode_{{$i}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
														<option value="SR" <?php if($item->tax_code=="SR") echo 'selected'; ?>>SR</option>
														<option value="ZR" <?php if($item->tax_code=="ZR") echo 'selected'; ?>>ZR</option>
														</select>
														<input type="hidden" name="tax_code_old[]" value="{{$item->tax_code}}">
													</td>
													
													<td width="9%">
														<input type="text" id="vatdiv_{{$i}}" step="any" readonly name="vatdiv[]" class="form-control vatdiv" value="<?php echo $item->vat.'% - '.$vat_amount?>">
														<input type="hidden" id="vat_{{$i}}" name="line_vat[]" class="form-control vat" value="{{$item->vat}}">
														<input type="hidden" id="vatlineamt_{{$i}}" name="vatline_amt[]" class="form-control vatline-amt" value="{{$vat_amount}}">
														<input type="hidden" id="itmdsnt_{{$i}}" name="line_discount[]" >
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{($orderrow->is_fc==1)?$item->item_total_fc:$item->item_total}}">
														<input type="hidden" id="itmtot_{{$i}}" name="item_total[]" value="{{$item->item_total}}">
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

									</div>
								@endforeach
								
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
										<span class="small" id="fc_label">Currency</span><input type="text" id="total" step="any" name="total" class="form-control spl" readonly value="{{(old('total'))?old('total'):(($orderrow->is_fc==1)?$orderrow->total_fc:$orderrow->total)}}">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span><input type="text" id="total_fc" step="any" name="total_fc" class="form-control spl" value="{{(old('total_fc'))?old('total_fc'):$orderrow->total}}" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{(old('discount'))?old('discount'):(($orderrow->is_fc==1)?$orderrow->discount_fc:$orderrow->discount)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" autocomplete="off" class="form-control spl" placeholder="0" value="{{(old('discount'))?old('discount'):$orderrow->discount}}">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Subtotal<span id="subttle" class="small"></span></label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal" name="subtotal" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):(($orderrow->is_fc==1)?$orderrow->subtotal_fc:$orderrow->subtotal)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="subtotal_fc" name="subtotal_fc" class="form-control spl" readonly value="{{(old('subtotal'))?old('subtotal'):$orderrow->subtotal}}" placeholder="0">
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">VAT Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="vatcur" name="vatcur" value="{{(old('vatcur'))?old('vatcur'):$orderrow->vat_amount}}">
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" value="{{(old('vat'))?old('vat'):(($orderrow->is_fc==1)?$orderrow->vat_amount_fc:$orderrow->vat_amount)}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" value="{{(old('vat_fc'))?old('vat_fc'):$orderrow->vat_amount}}" placeholder="0" readonly>
										</div>
									</div>
                                </div>
								
																
								<input type="hidden" step="any" id="other_cost" name="other_cost" value="{{(old('other_cost'))?old('other_cost'):$orderrow->other_cost}}" readonly class="form-control" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" value="{{(old('other_cost_fc'))?old('other_cost_fc'):$orderrow->other_cost_fc}}" readonly class="form-control spl" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{(old('net_amount'))?old('net_amount'):(($orderrow->is_fc==1)?$orderrow->net_amount_fc:$orderrow->net_amount)}}">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" value="{{(old('net_amount_fc'))?old('net_amount_fc'):$orderrow->net_amount}}" readonly placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
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
                                        <button type="submit" class="btn btn-primary" <?php if($orderrow->is_editable==1) echo 'disabled';?>>Submit</button>
										<a href="{{ url('purchase_split') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('purchase_split/edit/'.$orderrow->id) }}" class="btn btn-warning">Clear</a>
                                    </div>
                                </div>
							
                            <div id="customer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Customer</h4>
                                        </div>
                                        <div class="modal-body" id="customerData">
														
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
										<div class="modal-body" id="ac_data">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div> 
							
							<div id="acunt_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="acc_data">
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
                            
                             <div id="jobb_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Job Master</h4>
                                        </div>
                                        <div class="modal-body" id="jobbData">
                                            
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
								
                            <div id="supplier_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Supplier</h4>
                                        </div>
                                        <div class="modal-body" id="sup">
                                            
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
<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>

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

//$('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";
var lprice;
var taxinclude = false; var srvat={{$vatdata->percentage}};
//$('#vatdiv_1').val( srvat+'%' );
 $('#vat_1').val( srvat );
 var dptTxt;
$(document).ready(function () { 
	
	//ROWCHNG
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	<?php if($orderrow->is_fc==0) { ?>
		$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); 
		$("#net_amount_fc").toggle();$("#vat_fc").toggle(); $("#subtotal_fc").toggle(); //$(".oc-amount-fc").toggle();
	<?php } else { ?>
		$('#fc_label').html('Currency '+ $("#currency_id option:selected").text());
		$("#currency_rate").prop('disabled', false);
		$("#currency_id").prop('disabled', false);
	<?php } ?>
	
	<?php if( !empty($voucher) && $voucher->is_cash_voucher==1) { ?> //cash customer.... 
		if( $('#newsupplierInfo').is(":hidden") )
			$('#newsupplierInfo').toggle();
	<?php }  else { ?>
		if( $('#newsupplierInfo').is(":visible") )
			$('#newsupplierInfo').toggle();
	<?php } ?>
	
	var urlcode = "{{ url('purchase_split/checkrefno/') }}";
    $('#frmPurchaseSplit').bootstrapValidator({
        fields: {
			//voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			//voucher_no: { validators: { notEmpty: { message: 'The voucher no is required and cannot be empty!' } }},
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			////purchase_account: { validators: { notEmpty: { message: 'The purchase account is required and cannot be empty!' } }},
			//supplier_name: { validators: { notEmpty: { message: 'The supplier name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			//'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }},
			//'cost[]': { validators: { notEmpty: { message: 'The item cost is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPurorder').data('bootstrapValidator').resetForm();
    });
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();$("#other_cost_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle();$("#vat_fc").toggle();$("#other_cost_fc").toggle();
	});
	
});

// $('#job_id').on('change', function(e){
// 	var cat_id = e.target.value;

// 	$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
// 		$('#group_id').empty();
// 		 $('#group_id').append('<option value="">Select Account Group...</option>');
// 		$.each(data, function(value, display){
// 			 $('#group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
// 		});
// 	});
// });
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
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		//console.log('tax2: '+vatcur);
		
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineVat 	 = parseFloat( ($('#vat_'+n).val()=='') ? 0 : $('#vat_'+n).val() );
		//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
		if($('#txincld_'+n+' option:selected').val()==1 ) {
			
			var ln_total	 = lineQuantity * lineCost;
			var taxLineCost  = (ln_total * lineVat) / parseFloat(100+lineVat);
			var lineTotal 	 = ln_total;
			var lineTotalTx  = ( ln_total - taxLineCost );
			
		} else {
			
			var lineTax 	 = (lineCost * lineVat) / 100;
			var taxLineCost	 = lineQuantity * lineTax;
			var lineTotal 	 = lineQuantity * lineCost;
			var lineTotalTx 	 = lineQuantity * lineCost;
		}
		
		$('#vatdiv_'+n).val(lineVat+'% - '+taxLineCost.toFixed(2)); //val(lineVat+'% - '+taxLineCost.toFixed(2));
		$('#itmttl_'+n).val(lineTotal.toFixed(2));
		$('#vat').val(taxLineCost.toFixed(2));//(taxLineCost + vatcur).toFixed(2)
		$('#vatlineamt_'+n).val(taxLineCost.toFixed(2));
		$('#itmtot_'+n).val( lineTotalTx.toFixed(2) );
	
		return lineTotal;
	} 
	
		
	function resetValues(n) { //console.log(n);
		var lnvat = parseFloat($('#vatlineamt_'+n).val());
		var vat = parseFloat( $('#vat').val())
		var lntotal = parseFloat($('#itmttl_'+n).val());
		var total = parseFloat( $('#total').val())
		$('#vat').val(vat - lnvat);
		$('#total').val(total - lntotal);
		var net_amount = $('#net_amount').val();
		
		$('#net_amount').val( (net_amount - lntotal - lnvat).toFixed(2));
		
		$('#itmunt_'+n).find('option').remove().end().append('<option value="">Select</option>');
		$('#itmqty_'+n).val('');
		$('#itmcst_'+n).val('');
		$('#vatdiv_'+n).val('');
		$('#vatlineamt_'+n).val();
		$('#itmttl_'+n).val('');
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
	
//
$(function() {	
	var rowNum = $('#rowNum').val(); //new change........... 
	
	$('#department_id').on('change', function(e){
		var dept_id = e.target.value; 
		
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
			//$('#is_cash').val(data[0].cash_voucher);
			
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
	
	
	$(document).on('click', '.net-cost', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	    $('#othrcstItm_'+curNum).toggle();
	});
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); 
			newEntry.find($('input[name="account_id[]"]')).attr('id', 'acntid_' + rowNum);
			newEntry.find($('input[name="ac_code[]"]')).attr('id', 'accod_' + rowNum);
			newEntry.find($('input[name="item_description[]"]')).attr('id', 'acdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
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
			newEntry.find($('input[name="supname[]"]')).attr('id', 'supname_' + rowNum);
			newEntry.find($('input[name="taxno[]"]')).attr('id', 'taxno_' + rowNum);
			$('#jobcod_' + rowNum).val( $('#job_id option:selected').text() );
			$('#jobid_' + rowNum).val( $('#job_id option:selected').val() );
			
			$('#vatdiv_'+rowNum).val( srvat+'%' );
			$('#vat_'+rowNum).val( srvat );
			$('#itmqty_'+rowNum).val( 1 );		
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			$('#itmcod_'+rowNum).focus();
			
    }).on('click', '.btn-remove-item', function(e) 
    { 
		//new change..
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#p_orditmid_'+curNum).val():remitem+','+$('#p_orditmid_'+curNum).val();
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
	
	
		var jobburl = "{{ url('jobmaster/jobb_data/') }}";
	$('#jobname').click(function() {
		$('#jobbData').load(jobburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '#jobb_modal .jobRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	
	
	
	var joburl = "{{ url('jobmaster/job_data/') }}";
	$(document).on('click', 'input[name="jobcod[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#jobData').load(joburl+'/'+curNum, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#job_modal .jobRow', function(e) {
		var num = $('#num').val();
		$('#jobcod_'+num).val($(this).attr("data-cod"));
		$('#jobid_'+num).val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var acturl = "{{ url('purchase_split/account_data/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('purchase_split/account_data/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	
/*	var supurl = "{{ url('purchase_order/supplier_data/') }}";
	$('#supplier_name').click(function() {
		$('#sup').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.supp', function(e) {
		$('#supplier_name').val($(this).attr("data-name"));
		$('#supplier_id').val($(this).attr("data-id"));
		e.preventDefault();
	});*/
	
	
	var acturl = "{{ url('purchase_split/account_data/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acturl = "{{ url('purchase_split/account_data/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#itmData').load(acturl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	//new change............
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
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', '#supplier_name', function(e) {
			$('#acc_data').load(acurlall, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#acunt_modal .custRow', function(e) {
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
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			getNetTotal();
		}
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		/* if( parseFloat(lprice) < parseFloat(this.value) ) {
			alert('This price is greater than your last purchase.');
		} */
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getLineTotal(curNum);
		if(res || res==0) {
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
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('purchase_split/getvoucher/') }}/" + vchr_id, function(data) { //console.log(data);
			//$('#voucher_no').val(data.voucher_no);
			
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
	
	//new change...
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
				/* $('#itmcst_'+curNum).val(data.price);
				getNetTotal(); */
			});
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
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
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
		var num = $('#num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
		
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
	});
	
	
	
		
	$(document).on('change', '.tax-code', function(e) {
		
		$( '.tax-code' ).each(function() {
			var res = this.id.split('_'); 
			var curNum = res[1]; //console.log('d'+curNum);
			if(this.value=='EX' || this.value=='ZR') {
				
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
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
	});
	
	$('.inputsa').on('click', function(e) {
		$('#supplier_name').attr("onClick", "javascript:getAccount(this)");
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
function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_split/cash_data/') }}/purchase";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop
