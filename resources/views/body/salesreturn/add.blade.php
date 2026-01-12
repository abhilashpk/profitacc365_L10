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
                Sales Return
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Sales Return</a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
        <!--section ends-->
		
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
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
					<?php if(sizeof($vouchers)==0) { ?>
					<div class="alert alert-warning">
						<p>
							Sales Return voucher is not found! Please create a voucher in Account Settings.
						</p>
					</div>
					<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Sales Return
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('sr-print')
								 <a href="{{ url('sales_return/print/'.$printid->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
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
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesReturn" id="frmSalesReturn" action="{{ url('sales_return/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Invoice</label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="voucher_id">
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SR. No.</label>
									<input type="hidden" name="curno" id="curno">
                                    <div class="col-sm-10">
										<div class="input-group">
										@if($vouchers[0]['is_prefix']==1)<span class="input-group-addon">{{$vouchers[0]['prefix']}}</span>@endif
                                        <input type="text" class="form-control" id="voucher_no" placeholder="{{$vouchers[0]['voucher_no']}}" readonly name="voucher_no">
										<span class="input-group-addon"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
                                    </div>
                                </div>
								
								<?php if($formdata['sr_account']==1) { ?>
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('sales_invoice_id')) echo 'form-error';?>"> <b>Sales Invoice No</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('sales_invoice_id')) echo 'form-error';?>" id="sales_invoice_id" name="sales_invoice_id" value="{{old('sales_invoice_id')}}" placeholder="Sales Invoice No" autocomplete="off" onclick="getSalesInvoice(this)">
										<input type="hidden" name="is_prior" id="is_prior" value="{{old('is_prior')}}">
									</div>
                                </div>
									<?php } else { ?>
									<input type="hidden" name="sales_invoice_id" id="sales_invoice_id">
								<?php } ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Return Account</label>
                                    <div class="col-sm-10">
									<div class="input-group">
                                        <input type="text" name="sales_ret_account" id="sales_ret_account" class="form-control" value="<?php echo $vouchers[0]['acode'].' - '.$vouchers[0]['account']; ?>" readonly>
										<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
									</div>
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="<?php echo $vouchers[0]['acid']; ?>">
									</div>
                                </div>
                                
								
								<div class="form-group">
                                    <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name <?php if($errors->has('customer_name')) echo 'form-error';?>" id="customer_name" value="{{old('customer_name')}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
										<input type="hidden" name="customer_id" id="customer_id" value="{{old('customer_id')}}">
										<input type="hidden" name="cr_account_id" id="cr_account_id" value="{{old('cr_account_id')}}">
									</div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
									<div class="form-group">
										<font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>"><b>Reference No.</b></label></font>
										<div class="col-sm-10">
											<input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" autocomplete="off" value="{{old('reference_no')}}" name="reference_no" placeholder="Reference No.">
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SR. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' readonly id="voucher_date" <?php if(old('voucher_date')!='') { ?> value="{{old('voucher_date')}}" <?php } ?> placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
                                
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                         <input type="text" name="jobname" id="jobname" class="form-control" autocomplete="off" data-toggle="modal" data-target="#job_modal" placeholder="Job Code">
										<input type="hidden" name="job_id" id="job_id">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="job_id" id="job_id">
								<?php } ?>
								
								<?php if($formdata['location']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-10">
                                        <select id="location_id" class="form-control select2" style="width:100%" name="location_id">
											<?php 
											$is_default = 0;
											foreach($location as $loc) { 
											if($loc->is_default==1)
												$is_default = 1;
											?>
											<option value="{{ $loc['id'] }}" <?php if(old('location_id')==$loc['id']) echo 'selected'; ?>>{{ $loc['name'] }}</option>
											<?php } ?>
											
											<?php if($is_default==0) { ?>
											<option value="" selected>Select Location..</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="location_id" id="location_id">
								<?php } ?>
								
								
								<?php if($formdata['foreign_currency']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Foreign Currency</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1">
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}">{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Export</label>
									<div class="col-xs-10">
										<div class="col-xs-1">
											<label class="radio-inline iradio">
											<input type="checkbox" class="export" id="export" name="is_export" value="1">
											</label>
										</div>
									</div>
                                </div>
								
								<br/>
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="11%" class="itmHd">
											<span class="small">Item Code</span>
										</th>
										<th width="21%" class="itmHd">
											<span class="small">Item Description</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										@if($ismpqty==1)
										<th width="7%" class="itmHd">
											<span class="small">MP Qty</span>
										</th>
										@endif
										<th width="7%" class="itmHd">
											<span class="small">Quantity</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Cost/Unit</span>
										</th>
										<th width="4%" class="itmHd">
											<span class="small">Tx.Code</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Tx.Inc.</span>
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
								<div class="itemdivPrnt">
									<?php if(old('item_code')) { $i = 0; 
										foreach(old('item_code') as $item) { $j = $i+1;?>
										<div class="itemdivChld">							
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%">
														<input type="hidden" name="item_id[]" id="itmid_{{$j}}" value="{{ old('item_id')[$i]}}">
														<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" value="{{ $item }}" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
													</td>
													<td width="29%">
														<input type="text" name="item_name[]" value="{{ old('item_name')[$i]}}" id="itmdes_{{$j}}" autocomplete="off" class="form-control" placeholder="Item Description">
													</td>
													<td width="7%">
														<select id="itmunt_{{$j}}" class="form-control select2 <?php if($errors->has('unit_id.'.$i)) echo 'form-error';?> line-unit" style="width:100%" name="unit_id[]"><option value="{{old('unit_id')[$i]}}">{{old('hidunit')[$i]}}</option></select>
													</td>
													<td width="8%">
														<input type="number" id="itmqty_{{$j}}" step="any" name="quantity[]" value="{{ old('quantity')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('quantity.'.$i)) echo 'form-error';?> line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_{{$j}}" step="any" name="cost[]" value="{{ old('cost')[$i]}}" autocomplete="off" class="form-control <?php if($errors->has('cost.'.$i)) echo 'form-error';?> line-cost" placeholder="Cost/Unit">
														<input type="hidden" name="item_cost[]">
													</td>
													<td width="6%">
														<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
													</td>
													<td width="6%">
														<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
													</td>
													<td width="9%">
														<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
														<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
														<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" value="{{ old('vatdiv')[$i]}}" class="form-control vatdiv" placeholder="VAT Amt"> 
														<input type="hidden" id="vat_{{$j}}" name="line_vat[]" value="{{ old('line_vat')[$i]}}" class="form-control vat">
														<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" value="{{ old('vatline_amt')[$i]}}" class="form-control vatline-amt" value="0">
														<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" value="{{ old('line_discount')[$i]}}">
													</td>
													<td width="11%">
														<input type="number" id="itmttl_{{$j}}" step="any" name="line_total[]" value="{{ old('item_total')[$i]}}" class="form-control line-total" readonly placeholder="Total">
														<input type="hidden" id="itmtot_{{$j}}" name="item_total[]" value="{{ old('line_total')[$i]}}">
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
											<?php if($formdata['more_info']==1) { ?>
												<div id="moreinfo" style="float:left; padding-right:5px;">
													<button type="button" id="moreinfoItm_{{$j}}" class="btn btn-primary btn-xs more-info">More Info</button>
												</div>
													<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?>
												<?php if($formdata['item_location']==1) { ?>
												<div id="loc" style="float:left; padding-right:5px;">
													<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
												</div>
												<?php } else { ?>
								<input type="hidden" name="loc" id="loc">
								<?php } ?>
												
												@if($isconloc)
												<div id="cnloc" style="float:left; padding-right:5px;">
													<button type="button" id="cnloc_{{$j}}" data-toggle="modal" data-target="#conloc_modal" class="btn btn-primary btn-xs cnloc-info">Consignment Location</button>
													<input type="hidden" name="conloc_id[]" id="conlocid_{{$j}}">
													<input type="hidden" name="conloc_qty[]" id="conlocqty_{{$j}}">
												</div>
												@endif
                                                  	<?php if($formdata['supersede']==1) { ?>
												<div id="ssede" style="float:left; padding-right:5px;">
													<button type="button" id="sede_{{$j}}" class="btn btn-primary btn-xs sup-sede">Supersede</button>
												</div>
												<?php } else { ?>
								<input type="hidden" name="sede" id="sede">
								<?php } ?>
											<?php if($formdata['purchase']==1) { ?>
												<div style="float:left; padding-right:5px;">
													<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
												</div>
												<?php } else { ?>
								<input type="hidden" name="purchase" id="purchase">
								<?php } ?>
												<?php if($formdata['sales']==1) { ?>
												<div style="float:left;">
													<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
												</div>
												<?php } else { ?>
								<input type="hidden" name="sales" id="sales">
								<?php } ?>
											
												<div class="infodivPrntItm" id="infodivPrntItm_{{$j}}">
													<div class="infodivChldItm">							
														<div class="table-responsive item-data" id="itemData_{{$j}}">
														</div>
													</div>
												</div>
											
												<div class="locPrntItm" id="locPrntItm_{{$j}}">
													<div class="locChldItm">							
														<div class="table-responsive loc-data" id="locData_{{$j}}"></div>
													</div>
												</div>
												
												<div class="cnlocPrntItm" id="cnlocPrntItm_{{$j}}">
												<div class="cnlocChldItm">							
													<div class="table-responsive cnloc-data" id="cnlocData_{{$j}}"></div>
												</div>
											</div>

												<div class="sedePrntItm" id="sedePrntItm_{{$j}}">
													<div class="sedeChldItm">							
														<div class="table-responsive sede-data" id="sedeData_{{$j}}"></div>
													</div>
												</div>
												
										</div>
										<?php $i++; } } else { ?>
									 <div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="13%">
													<input type="hidden" name="item_id[]" id="itmid_1">
													<input type="hidden" name="item_wit[]" id="itmwit_1">
													<input type="hidden" name="item_lnt[]" id="itmlnt_1">
													<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" placeholder="Item Code">
												</td>
												<td width="24%">
													<input type="text" name="item_name[]" id="itmdes_1" autocomplete="off" class="form-control" placeholder="Item Description">
												</td>
												<td width="7%">
													<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option></select>
												</td>
												@if($ismpqty==1)
												<td width="8%" class="itcodmp">
													<input type="number" id="itmmpqty_1" autocomplete="off" step="any" name="mpquantity[]" class="form-control line-mpquantity" placeholder="MP Qty.">
												</td>
												@endif
												<td width="8%">
													<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty.">
												</td>
												<td width="8%">
													<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
													<input type="hidden" name="item_cost[]">
												</td>
												<td width="6%">
													<select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_1" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0">No</option><option value="1">Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
													<input type="hidden" id="packing_1" name="packing[]" value="1">
													<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_1" name="line_vat[]" class="form-control vat">
													<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_1" name="line_discount[]">
												</td>
												<td width="11%">
													<input type="hidden" id="itmtot_1" name="item_total[]">
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
											<?php if($formdata['more_info']==1) { ?>
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
										<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?>
								
								           <?php if($formdata['item_location']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
												<?php } else { ?>
								<input type="hidden" name="loc" id="loc">
								<?php } ?>
											@if($isconloc)
											<div id="cnloc" style="float:left; padding-right:5px;">
												<button type="button" id="cnloc_1" data-toggle="modal" data-target="#conloc_modal" class="btn btn-primary btn-xs cnloc-info">Consignment Location</button>
													<input type="hidden" name="conloc_id[]" id="conlocid_1">
													<input type="hidden" name="conloc_qty[]" id="conlocqty_1">
											</div>
											@endif
                                            <?php if($formdata['supersede']==1) { ?>
											<div id="ssede" style="float:left; padding-right:5px;">
												<button type="button" id="sede_1" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
												<?php } else { ?>
								<input type="hidden" name="sede" id="sede">
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
								<?php if($formdata['dimension']==1) { ?>
								<div tyle="float:left;" id="itmInfo">
								<button type="button" id="itmInfo_1" class="btn btn-primary btn-xs dimn-view">Dimension</button>
								</div>
								<?php } else { ?>
								<input type="hidden" name="itmInfo" id="itmInfo">
								<?php } ?>
											
											<div class="infodivPrntItm" id="infodivPrntItm_1">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_1">
													</div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_1">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_1"></div>
												</div>
											</div>
											
											<div class="cnlocPrntItm" id="cnlocPrntItm_1">
												<div class="cnlocChldItm">							
													<div class="table-responsive cnloc-data" id="cnlocData_1"></div>
												</div>
											</div>
											
											<div class="dimnInfodivPrntItm" id="dimnInfodivPrntItm_1">
													<div class="dimnInfodivChldItm">							
														<div class="table-responsive dimn-item-data" id="dimnitemData_1"></div>
													</div>
											</div>
											

											<div class="sedePrntItm" id="sedePrntItm_1">
												<div class="sedeChldItm">							
													<div class="table-responsive sede-data" id="sedeData_1"></div>
												</div>
											</div>
									</div>
								 <?php } ?>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" value="{{old('total')}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" value="{{old('total_fc')}}" class="form-control spl" readonly placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" value="{{old('discount')}}" class="form-control spl discount-cal" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" value="{{old('discount_fc')}}" class="form-control spl" placeholder="0">
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
											<input type="number" id="subtotal" step="any" name="subtotal" value="{{old('subtotal')}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" id="subtotal_fc" step="any" name="subtotal_fc" value="{{old('subtotal_fc')}}" class="form-control spl" readonly placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" value="{{old('vat')}}" class="form-control spl" placeholder="0" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" value="{{old('vat_fc')}}" class="form-control spl" placeholder="0" readonly>
										</div>
									</div>
                                </div>
								
								
								<!--<input type="hidden" step="any" id="discount" name="discount" class="form-control" placeholder="0">
								<input type="hidden" step="any" id="discount_fc" name="discount_fc" class="form-control" placeholder="0">-->
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" value="{{old('net_amount_hid')}}" readonly>
											<input type="number" step="any" id="net_amount" name="net_amount" value="{{old('net_amount')}}" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" value="{{old('net_amount_fc')}}" class="form-control spl" readonly placeholder="0">
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
										<a href="{{ url('sales_return') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('sales_return/add') }}" class="btn btn-warning">Clear</a>
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
							
							<div id="conloc_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Consignment Location</h4>
                                        </div>
                                        <div class="modal-body" id="conLocData">
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
"use strict";
var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>;
var dptTxt;
$('#voucher_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
 $('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
$(document).ready(function () { 
	@if(old('voucher_date')=='')
		$('#voucher_date').val('{{date('d-m-Y')}}');
	@else
		$('#voucher_date').val('{{old('voucher_date')}}'); 
	@endif
	//ROWCHNG
	<?php if(!old('item_code')) { ?>
	$('.btn-remove-item').hide();
	<?php } ?>
	
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);$('.locPrntItm').toggle(); $('.cnlocPrntItm').toggle();
	$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrntItm').toggle(); $("#subtotal_fc").toggle();
	$('.sedePrntItm').toggle();
	var urlcode = "{{ url('sales_return/checkrefno/') }}";
	var urlvchr = "{{ url('sales_return/checkvchrno/') }}"; //CHNG
    $('#frmSalesReturn').bootstrapValidator({
        fields: {
			//voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    }, */
					/*remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This SR. No. is already exist!'
                    }*/
                }
            },
			//sales_ret_account: { validators: { notEmpty: { message: 'The sales return account is required and cannot be empty!' } }},
			reference_no: {
                validators: {
                   /*  notEmpty: {
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
			//customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }},
			//'quantity[]': { validators: { notEmpty: { message: 'The item quantity is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmSalesReturn').data('bootstrapValidator').resetForm();
    });
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	});
	
	$('.export').on('ifChecked', function(event){ 
		$('.tax-code').val('ZR');
		$('.vat').val(0);
		$('.vatdiv').val('0%');
		$('.tax-code').attr("disabled", true); 
		getNetTotal();
	});
	
	$('.export').on('ifUnchecked', function(event){
		$('.tax-code').val('SR');
		$('.vat').val(srvat);
		$('.vatdiv').val(srvat+'%');
		$('.tax-code').attr("disabled", false); 
		getNetTotal();
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
		//console.log('lineTotal: '+lineTotal);
		$('#total').val(lineTotal.toFixed(2));
		$('#subtotal').val(lineTotal.toFixed(2));
		//new change..
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		//console.log('taxnew: '+vatcur);
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
			var fcTotal    = total * rate;
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var fcTax = vat * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax);
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
		lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
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
	
	function calculateTaxInclude(curNum)
	{
	
		var ln_total = parseFloat( $('#itmqty_'+curNum).val()==''?0:$('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val()==''?0:$('#itmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 : $('#vat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total - vat_amt;
		$('#itmttl_'+curNum).val( ln_total.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() ) - vat_amt).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) - vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) - vat_amt).toFixed(2) );
		$('#itmtot_'+curNum).val( ln_total_amt.toFixed(2) );
	}
	
	function calculateTaxExclude(curNum)
	{
		//console.log(curNum);
		var ln_total = parseFloat( $('#itmqty_'+curNum).val()==''?0:$('#itmqty_'+curNum).val() ) * parseFloat( $('#itmcst_'+curNum).val()==''?0:$('#itmcst_'+curNum).val() );
		var ln_vat = parseFloat( ($('#vat_'+curNum).val()=='') ? 0 : $('#vat_'+curNum).val() );
		var vat_amt = ln_total * ln_vat / parseFloat(100 + ln_vat);
		var ln_total_amt = ln_total + vat_amt;
		$('#itmttl_'+curNum).val( ln_total_amt.toFixed(2) );
		$('#total').val( (parseFloat( ($('#total').val()=='')?0:$('#total').val() )).toFixed(2) );
		$('#net_amount').val( (parseFloat( ($('#net_amount').val()=='')?0:$('#net_amount').val() ) + vat_amt).toFixed(2) );
		$('#net_amount_hid').val( (parseFloat( ($('#net_amount_hid').val()=='')?0:$('#net_amount_hid').val() ) + vat_amt).toFixed(2) );
		$('#itmtot_'+curNum).val( ln_total_amt.toFixed(2) );
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
			
		
		return true;
	}

$(function() {	
	var rowNum = 1;
//	$('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
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
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="item_id[]"]')).attr('id', 'itmid_' + rowNum);
			newEntry.find($('input[name="item_code[]"]')).attr('id', 'itmcod_' + rowNum);
			newEntry.find($('input[name="item_name[]"]')).attr('id', 'itmdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum); //new change
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum); //newEntry.find($('.h5')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.infodivPrntItm')).attr('id', 'infodivPrntItm_' + rowNum);
			newEntry.find($('.more-info')).attr('id', 'moreinfoItm_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('.locPrntItm')).attr('id', 'locPrntItm_' + rowNum);
			newEntry.find($('.loc-info')).attr('id', 'loc_' + rowNum);
			newEntry.find($('.loc-data')).attr('id', 'locData_' + rowNum);
			newEntry.find($('.sedePrntItm')).attr('id', 'sedePrntItm_' + rowNum);
			newEntry.find($('.sup-sede')).attr('id', 'sede_' + rowNum); 
			newEntry.find($('.sede-data')).attr('id', 'sedeData_' + rowNum);
			
			newEntry.find($('input[name="mpquantity[]"]')).attr('id', 'itmmpqty_' + rowNum);
			newEntry.find($('input[name="item_wit[]"]')).attr('id', 'itmwit_' + rowNum);
			newEntry.find($('input[name="item_lnt[]"]')).attr('id', 'itmlnt_' + rowNum);

			newEntry.find($('.cnlocPrntItm')).attr('id', 'cnlocPrntItm_' + rowNum);
			newEntry.find($('.cnloc-info')).attr('id', 'cnloc_' + rowNum);
			newEntry.find($('.cnloc-data')).attr('id', 'cnlocData_' + rowNum);
			
			newEntry.find($('input[name="conloc_id[]"]')).attr('id', 'conlocid_' + rowNum); 
			newEntry.find($('input[name="conloc_qty[]"]')).attr('id', 'conlocqty_' + rowNum); 
			
			newEntry.find($('.dimnInfodivPrntItm')).attr('id', 'dimnInfodivPrntItm_' + rowNum);
            newEntry.find($('.dimn-item-data')).attr('id', 'dimnitemData_' + rowNum);
            newEntry.find($('.dimn-view')).attr('id', 'itmInfo_' + rowNum);

			$('#sedeData_'+rowNum).html('');
				
			$('#locData_'+rowNum).html('');
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//NW CHNG
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('.taxinclude')).attr('id', 'txincld_' + rowNum);//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			
			newEntry.find($('.descdivPrntItm')).attr('id', 'descdivPrntItm_' + rowNum);
			newEntry.find($('.desc-info')).attr('id', 'descinfoItm_' + rowNum);
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.txt-desc')).attr('name', 'itemdesc['+indx+'][]');
			$('#descdivPrntItm_'+rowNum+' .descdivChldItm').slice(1).remove();
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			//VAT CHNG
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			
			$('#packing_'+rowNum).val(1);
			
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.export').is(":checked") ) { 
				$('.tax-code').val('ZR');
			}
			
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			if( $('#locPrntItm_'+rowNum).is(":visible") ) 
				$('#locPrntItm_'+rowNum).toggle();
			
			if( $('#sedePrntItm_'+rowNum).is(":visible") ) 
				$('#sedePrntItm_'+rowNum).toggle();
			
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
			
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
			//ROWCHNG
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
			$('#itmcod_'+rowNum).focus();
			
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
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
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		$('#cr_account_id').val($(this).attr("data-id"));
		$('#cr_balance').val($(this).attr("data-clbalance"));
		$('#cr_limit').val($(this).attr("data-crlimit"));
		$('#pdc').val($(this).attr("data-pdc"));
		e.preventDefault();
	});
	
		var joburl = "{{ url('jobmaster/jobb_data/') }}";
	$('#jobname').click(function() {
		$('#jobData').load(joburl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.jobbRow', function(e) {
		$('#jobname').val($(this).attr("data-cod"));
		$('#job_id').val($(this).attr("data-id"));
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
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		
		$('#itmwit_'+num).val( $(this).attr("data-wit") );
		$('#itmlnt_'+num).val( $(this).attr("data-lnt") );
		
		if($('.export').is(":checked") ) { 
			$('#vatdiv_'+num).val( '0%' );
			$('#vat_'+num).val( 0 );
			$('#itmcst_'+num).val( $(this).attr("data-price") );
		} else {
			$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#vat_'+num).val( $(this).attr("data-vat") );
			$('#itmcst_'+num).val( $(this).attr("data-price") );
			srvat = $(this).attr("data-vat");
		}
		
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
	});
	
	
		$(document).on('blur','.line-mpquantity', function() {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qtywt = this.value * $('#itmwit_'+curNum).val() * $('#itmlnt_'+curNum).val();
		$('#itmqty_'+curNum).val(qtywt);
	})
	
	
	$(document).on('blur', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('blur', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('click', '.sup-sede', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val(); //console.log(item_id);
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_sedeinfo/') }}/"+item_id+"/"+curNum
		   $('#sedeData_'+curNum).load(locUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#sedePrntItm_'+curNum).toggle();
	   }
    });
    
    $(document).on('click', '.dimn-view', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_dimn_info/') }}/"+item_id;
		   $('#dimnitemData_'+curNum).load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
			
		   $('#dimnInfodivPrntItm_'+curNum).toggle();
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
		
		$.get("{{ url('sales_return/getvoucher/') }}/" + vchr_id, function(data) {
			$('#voucher_no').attr('placeholder', data.voucher_no); //$('#voucher_no').val(data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			$('#sales_ret_account').val(data.account_id+'-'+data.account_name);
			$('#dr_account_id').val(data.id);
		});
		
	});
	
	$('#voucher_id').on('blur', function(e){
		var vchr_no = $('#voucher_no').val();
		var vchr_id = $('#voucher_id option:selected').val();
		var acnt = $('#sales_ret_account').val();
		var ac_mstr = $('#dr_account_id').val();
		$.ajax({
			url: "{{ url('sales_return/set_session/') }}",
			type: 'get',
			data: 'vchr_no='+vchr_no+'&vchr_id='+vchr_id+'&acnt='+acnt+'&ac_mstr='+ac_mstr,
			success: function(data) { 
			}
		}) 
	});
	
	$(document).on('blur', '#sales_invoice_id', function(e) { 
		var id = this.value; //console.log(id);
		if(id!='') {
			var urlcode = "{{ url('sales_invoice/check_invoice/') }}";
			$.ajax({
				url: urlcode,
				data: 'sales_invoice_id='+id,
				success: function(result) {
					if(result) {
						$('#is_prior').val(1);
						alert('Sales Invoice No is not found. Do you want treat it as prior year Transaction?');
					}
				}
			});
		} else
			return true;
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
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) { console.log(data);
				//VAT CHNG
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
			});
		}
	});
	
	//CHNG
	$('.input-group-addon').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$(document).on('change', '.taxinclude', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1]; 
		
		if($('.taxinclude option:selected').val()==0 )
			$('.taxinclude').val(0);
		else
			$('.taxinclude').val(1);
			
		if(this.value==1)
			calculateTaxInclude(curNum);
		else
			calculateTaxExclude(curNum);
		
		var res = getLineTotal(curNum);
		if(res) 
			getNetTotal();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		var res = this.id.split('_'); 
		var curNum = res[1];
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
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
		
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

   /* $(document).on('click', '.cnloc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   var cst_id = $('#customer_id').val();
	   if(item_id!='') {
		   let cnlocUrl = "{{ url('itemmaster/get_cnlocinfo/') }}/"+item_id+"/"+curNum+"/"+cst_id
		   $('#cnlocData_'+curNum).load(cnlocUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
		   $('#cnlocPrntItm_'+curNum).toggle();
	   }
    }); */
	
	$('input,select,checkbox').keydown( function(e) { 
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
			
			$('#itmunt_'+curNum).find('option').remove().end();
			$('#itmunt_'+curNum).find('option').end()
				 .append($("<option></option>")
							.attr("value",data.unit_id)
							.text(data.unit));
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
							$('#hidunit_'+curNum).val(value);
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
			if(itm_id!='') {
				$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) {
					$('#itmunt_1').find('option').remove().end();
					$.each(data, function(key, value) {   
					$('#itmunt_1').find('option').end()
					 .append($("<option></option>")
								.attr("value",value.id)
								.text(value.unit_name)); 
								$('#hidunit_1').val(value);
					});
				});
			}
		},
        minLength: 3,
    });
	
	///Customer search...
	var acmst = "{{ url('account_master/ajax_account/') }}";
	$('#customer_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: acmst,
                dataType: "json",
                data: {
                    term : request.term, category : 'CUSTOMER'
                },
                success: function(data) {
                    response(data); 
                }
            });
        },
		select: function (event, ui) { 
			$("#customer_id").val(ui.item.id);
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
	
	
	$('.inputsa').on('click', function(e) {
		$('#sales_ret_account').attr("onClick", "javascript:getAccount(this)");
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
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
		$('#frmSalesReturn').bootstrapValidator('revalidateField', 'quantity[]');
	});
	
	var cnlocUrl = "{{ url('itemmaster/conloc_data/') }}";
	$(document).on('click', '.cnloc-info', function(e) { 
	   e.preventDefault();
	    var res = this.id.split('_');
		var curNum = res[1]; 
		var item_id = $('#itmid_'+curNum).val();
	    var cst_id = $('#customer_id').val();

		$('#conLocData').load(cnlocUrl+'/'+curNum+'/'+cst_id+'/'+item_id, function(result){
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
    });
	
	$(document).on('click', '.add-loc-qty', function(e)  { 
		 var nm = $('#numasm').val();
		 $('#conlocid_'+nm).val($('#clocid').val());
		 $('#conlocqty_'+nm).val($('#clocqty').val());
		
	});
	
	$('#department_id').on('change', function(e){
		var dept_id = e.target.value; 
		dptTxt = $( "#department_id option:selected" ).text();
		$.get("{{ url('sales_return/getdeptvoucher/') }}/" + dept_id, function(data) {  console.log(data);
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
				$('#sales_ret_account').val(data[0].account_id+'-'+data[0].account_name);
				$('#dr_account_id').val(data[0].id);
			} else {
				$('#sales_ret_account').val('');
				$('#dr_account_id').val('');
			}
			
		});
	});

	$(document).on('blur', '#voucher_no', function(e) {  
		if(parseInt($(this).val()) > parseInt($('#curno').val())) {
			alert('Voucher no is greater than current range!');
			$('#voucher_no').val('');
		}
	});

	
	//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('sales_return/checkvchrno/') }}", 
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

function getSalesInvoice(e) {
	if(e.value=='') { 
		var ht = $(window).height();
		var wt = $(window).width();
		
		if($('#department_id').length) {
			var did = $('#department_id option:selected').val();
			var pourl = "{{ url('sales_invoice/invoice_data/') }}/"+did;
		} else
			var pourl = "{{ url('sales_invoice/invoice_data/') }}";
		
		popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
		popup.focus();
		return false
	}
}

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = 'SR'; 
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

</script>
@stop
