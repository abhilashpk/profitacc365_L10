private function SetItemAccountTransaction($objItem, $arrResult, $key, $attributes, $type) {
		
		$amount = ($type=='LINE')?$attributes['line_total'][$key]:$arrResult['tax_total'];
		
		if($type=='VAT') {
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); 
			if($vatrow) {
				$dr_acnt_id = $vatrow->collection_account;
			}
		} else {
			$dr_acnt_id = $attributes['account_id'][$key];
		} 
			
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PS',
								'voucher_type_id'   => $this->purchase_split->id,
								'account_master_id' => $dr_acnt_id,
								'transaction_type'  => 'Dr',
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['item_description'][$key],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'is_fc'				=> isset($attributes['is_fc'])?1:0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> $attributes['jobid'][$key]
							]);
								
		return true;
		
	}
	

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
                Purchase Split
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Purchase Split</a>
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
							PS. No. is found empty! Please create in Account Settings.
						</p>
					</div>
					<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Purchase Split
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@permission('pi-print')
								 <a href="{{ url('purchase_split/print/'.$printid->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span> 
								</a>
								@endpermission
							<?php } ?>
							</div>
							
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPurchaseSplit" id="frmPurchaseSplit" enctype="multipart/form-data" action="{{ url('purchase_split/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ $locdefault->id }}">
								
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
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Purchase Split</b></label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
                                           @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@else
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Purchase Split</b></label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="voucher_id">
                                           @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
								<div class="form-group">

                                   <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label"><b>PS. No.</b></label></font>
									<input type="hidden" name="curno" id="curno" value="<?php echo $voucher['voucher_no']; ?>">
                                    <div class="col-sm-10">
										<div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="<?php echo $voucher['voucher_no']; ?>">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
                                    </div>
                                </div>
								
								<?php if($formdata['reference_no']==1) { ?>
								<div class="form-group">

                                 <font color="#16A085"><label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>"><b>Reference No.</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" name="reference_no" autocomplete="off" value="{{ old('reference_no') }}" placeholder="Reference No.">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PS. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" autocomplete="off" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Purchase Account</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="account_master_id" id="account_master_id" value="{{ $vouchers[0]['dr_account_master_id'] }}" class="form-control">
										<!--<input type="text" name="purchase_account" id="purchase_account" class="form-control" value="{{ $voucher['account_id'].'-'.$voucher['master_name'] }}" readonly onclick="getAccount(this)">-->
										<div class="input-group">

											<input type="text" name="purchase_account" id="purchase_account" class="form-control" value="{{ $voucher['account_id'].'-'.$voucher['master_name'] }}" readonly>
											<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
									</div>
                                </div>
								
								<div class="form-group">
                                  <font color="#16A085">   <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('supplier_name')) echo 'form-error';?>"><b>Supplier</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="supplier_name" id="supplier_name" value="{{ old('supplier_name') }}" class="form-control <?php if($errors->has('supplier_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#supplier_modal" placeholder="Supplier">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') }}">
										
										<div class="col-xs-10" id="newsupplierInfo">
											<div class="col-xs-8">
												<span class="small">Supplier Name</span> <input type="text" id="suppliername" name="suppliername" value="{{old('suppliername')}}" class="form-control" autocomplete="off" data-toggle="modal" data-target="#newsupplier_modal">
											</div>
										</div>
									</div>
                                </div>
								
								<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" autocomplete="off" name="vat_no" value="{{ old('vat_no') }}" placeholder="TRN No.">
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
								
								
								<?php if($formdata['job']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Job</label>
                                    <div class="col-sm-10">
                                        <select id="job_id" class="form-control select2" style="width:100%" name="job_id">
                                            <option value="">Select Job...</option>
											@foreach($jobs as $job)
											<option value="{{ $job['id'] }}" <?php if($job['id']==old('job_id')) echo 'selected'; ?>>{{ $job['name'] }}</option>
											@endforeach
                                        </select>
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
											<input type="checkbox" class="custom_icheck" id="is_fc" name="is_fc" value="1" @if(old('is_fc')=="1") checked @endif>
										</label>
										</div>
										<div class="col-xs-5">
											<select id="currency_id" class="form-control select2" style="width:100%" name="currency_id">
												<option value="">Select Foreign Currency...</option>
												@foreach($currency as $curr)
												<option value="{{$curr['id']}}" <?php if($curr['id']==old('currency_id')) echo 'selected'; ?>>{{$curr['code']}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-xs-5">
											<input type="text" name="currency_rate" id="currency_rate" class="form-control" value="{{ old('currency_rate') }}" placeholder="Currency Rate">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="currency_rate" id="currency_rate">
								<?php } ?>
								
								<br/>
								<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Expense Details</span></h5></legend>
									<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="14%" class="itmHd">
											<span class="small">A/C Name</span>
										</th>
										<th width="25%" class="itmHd">
											<span class="small">Description</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="7%" class="itmHd">
											<span class="small">Qty.</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Cost/Unit</span>
										</th>
										<th width="4%" class="itmHd">
											<span class="small">Tx.Code</span>
										</th>
										<th width="5%" class="itmHd">
											<span class="small">Jobcode</span>
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
										<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="16%" >
														<input type="hidden" name="account_id[]" id="acntid_1">
														<input type="text" id="accod_1" name="ac_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#ac_modal" placeholder="A/C Name">
													</td>
													<td width="29%">
														<input type="text" name="ac_name[]" id="acdes_1" autocomplete="off" class="form-control" placeholder="Description">
													</td>
													<td width="7%">
														<select id="itmunt_1" class="form-control select2 line-unit" style="width:100%" name="unit_id[]"><option value="">Unit</option>@foreach($units as $unit)<option id="{{$unit->id}}">{{$unit->unit_name}}</option>@endforeach</select>
													</td>
													<td width="8%" class="itcod">
														<input type="number" id="itmqty_1" autocomplete="off" step="any" name="quantity[]" class="form-control line-quantity" placeholder="Qty.">
													</td>
													<td width="8%">
														<input type="number" id="itmcst_1" autocomplete="off" step="any" name="cost[]" class="form-control line-cost" placeholder="Cost/Unit">
													</td>
													<td width="6%">
														<select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]">
														<option value="SR">SR</option><option value="ZR">ZR</option></select>
													</td>
													<td width="7%">
														<input type="text" id="jobid_1" autocomplete="off" name="jobid[]" class="form-control" placeholder="Jobcode">
													</td>
													<td width="8%">
														<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
														<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amt.">
														<input type="hidden" id="vat_1" name="line_vat[]" class="form-control vat">
														<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
														<input type="hidden" id="itmdsnt_1" name="line_discount[]">
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" name="total" class="form-control spl" readonly value="{{old('total')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" name="total_fc" class="form-control spl" readonly value="{{old('total_fc')}}" placeholder="0">
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
											<input type="number" step="any" id="discount" name="discount" autocomplete="off" class="form-control spl discount-cal" value="{{old('discount')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="discount_fc" name="discount_fc" autocomplete="off" class="form-control spl" value="{{old('discount_fc')}}" placeholder="0">
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
											<input type="number" id="subtotal" step="any" name="subtotal" class="form-control spl" readonly value="{{old('subtotal')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" id="subtotal_fc" step="any" name="subtotal_fc" class="form-control spl" readonly value="{{old('subtotal_fc')}}" placeholder="0">
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
											<input type="number" step="any" id="vat" name="vat" class="form-control spl" placeholder="0" value="{{old('vat')}}" readonly>
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="vat_fc" name="vat_fc" class="form-control spl" placeholder="0" value="{{old('vat_fc')}}" readonly>
										</div>
									</div>
                                </div>
								
								<input type="hidden" step="any" id="other_cost" name="other_cost" readonly class="form-control" value="{{old('other_cost')}}" placeholder="0">
								<input type="hidden" step="any" id="other_cost_fc" name="other_cost_fc" readonly class="form-control spl" value="{{old('other_cost_fc')}}" placeholder="0">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="hidden" id="net_amount_hid" name="net_amount_hid" class="form-control" value="{{old('net_amount_hid')}}" readonly>
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{old('net_amount')}}" placeholder="0">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" class="form-control spl" readonly value="{{old('net_amount_fc')}}" placeholder="0">
										</div>
									</div>
                                </div>
								<hr/>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('purchase_split') }}" class="btn btn-danger">Cancel</a>
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
											<div class="modal-body" id="ac_data">
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

 var taxinclude = false; var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>;
 $('#vatdiv_1').val( srvat+'%' );
 $('#vat_1').val( srvat );
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
	
	var urlvchr = "{{ url('purchase_split/checkvchrno/') }}";
	var urlcode = "{{ url('purchase_split/checkrefno/') }}"; //CHNG
	$('.sedePrntItm').toggle();
	<?php if($formdata['item_import']==0) { ?>
    $('#frmPurchaseSplit').bootstrapValidator({
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
        $('#frmPurchaseSplit').data('bootstrapValidator').resetForm();
    });
	<?php } ?>
	
	<?php if( sizeof($vouchers) > 0 && $vouchers[0]->is_cash_voucher==1) { ?> //cash customer.... 
		if( $('#newsupplierInfo').is(":hidden") )
			$('#newsupplierInfo').toggle();
		$('#supplier_id').val({{$vouchers[0]->default_account_id}});
		$('#supplier_name').val('{{$vouchers[0]->default_account}}');
	<?php }  else { ?>
		if( $('#newsupplierInfo').is(":visible") )
			$('#newsupplierInfo').toggle();
	<?php } ?>
	
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
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var supplier_id = $('#supplier_id').val();
		var cr = $('#currency_rate').val();
		if($('#sales_type option:selected').val()=='normal') {
			$.ajax({
				url: "{{ url('itemmaster/get_purchase_cost/') }}",
				type: 'get',
				data: 'item_id='+item_id+'&unit_id='+unit_id+'&supplier_id='+supplier_id+'&cr='+cr,
				success: function(data) {
					$('#itmcst_'+curNum).val((data==0)?'':data);
					lprice = data;
					return true;
				}
			})
			
		} else {
			$.ajax({
				url: "{{ url('itemmaster/get_cost_avg/') }}", //
				type: 'get',
				data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) {
					$('#itmcst_'+curNum).val(data);
					$('#itmcst_'+curNum).focus();
					return true;
				}
			});
		}
		
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
	var dat = new Date();
    $('#voucher_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}') } );
	
	var rowNum = 1;
	
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select']
			newEntry.find($('input[name="ac_id[]"]')).attr('id', 'acntid_' + rowNum);
			newEntry.find($('input[name="ac_code[]"]')).attr('id', 'accod_' + rowNum);
			newEntry.find($('input[name="ac_name[]"]')).attr('id', 'acdes_' + rowNum);
			newEntry.find($('input[name="unit[]"]')).attr('id', 'itmunt_' + rowNum);
			newEntry.find($('input[name="quantity[]"]')).attr('id', 'itmqty_' + rowNum);
			newEntry.find($('input[name="cost[]"]')).attr('id', 'itmcst_' + rowNum);
			newEntry.find($('input[name="line_vat[]"]')).attr('id', 'vat_' + rowNum);
			newEntry.find($('input[name="line_discount[]"]')).attr('id', 'itmdsnt_' + rowNum);
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="vatline_amt[]"]')).attr('id', 'vatlineamt_' + rowNum);
			newEntry.find($('input[name="vatdiv[]"]')).attr('id', 'vatdiv_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find($('button[type="button"]')).attr('data-id', 'rem_' + rowNum); //NEW CHNG
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//new change
			newEntry.find($('.tax-code')).attr('id', 'taxcode_' + rowNum);//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			newEntry.find($('input[name="jobid[]"]')).attr('id', 'jobid_' + rowNum);
			
			if($('.taxinclude option:selected').val()==0 )
				$('.taxinclude').val(0);
			else
				$('.taxinclude').val(1);
			
			if($('.import').is(":checked") ) { 
				$('.tax-code').val('RC');
			}
			
						
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
	
	
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
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
	});
	
	var supurl;
	$('#supplier_name').click(function() {
		if($('#department_id').length)
			supurl = "{{ url('purchase_order/supplier_datadept/') }}"+'/'+$('#department_id option:selected').val();
		else
			supurl = "{{ url('purchase_order/supplier_data/') }}";
		$('#supplierData').load(supurl, function(result) {
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
	
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="ac_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#ac_data').load(acurl+'/'+curNum, function(result){
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#num').val();
		$('#acntid_'+num).val( $(this).attr("data-id") );
		$('#accod_'+num).val( $(this).attr("data-name") );
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
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
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
	
	$(document).on('keyup', '.discount-cal', function(e) { 
		calculateDiscount();
	});
	
	$(document).on('change', '.tax-code', function(e) {
		
		$('.tax-code').val(this.value);
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
	
	$(document).on('click', '#importFile', function(e) { 
		if($('#input-23').val()=='') {
			alert('Please choose import file!');
			return false;
		} else {
			$('#frmPurchaseSplit').attr('action', "{{url('purchase_split/import')}}").submit();
		}
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

										