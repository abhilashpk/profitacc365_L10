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
        
    <link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
    
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
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
		
		#batch_modal { z-index:0; } /* MAY25  */
	</style>
	
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Sales Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Sales Invoice</a>
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
						Sales Invoice voucher is not found! Please create a voucher in Account Settings.
					</p>
				</div>
				<?php } elseif($accstatus==false) { ?>
				<div class="alert alert-warning">
					<p>
						Please set other accounts settings properly.
					</p>
				</div>
				<?php } else { ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New Sales Invoice
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('si-print')
								 <a href="{{ url('sales_invoice/print/'.$printid->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span>
								 </a>
								@endcan
								@can('si-print')
								 <!--<a href="{{ url('sales_invoice/printdo/'.$printid->id) }}" target="_blank" class="btn btn-info btn-sm">
									<span class="btn-label">
										<i class="fa fa-fw fa-print"></i> DO
									</span>
								 </a>-->
								@endcan
							<?php } ?>
							</div>
							
                        </div>
							
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmSalesInvoice" id="frmSalesInvoice" action="{{ url('sales_invoice/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
								<input type="hidden" name="is_cash" id="is_cash" value="{{($siscsh)?$siscsh:$vouchers[0]->is_cash_voucher}}">
								@if($formdata['send_email']==1)
								<input type="hidden" name="send_email" value="1">
								@else
								<input type="hidden" name="send_email" value="0">
								@endif
								<input type="hidden" name="mq_loc" value="{{($ismqloc)?1:0}}">
								@if($isdept)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                    <div class="col-sm-10">
                                       <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
											@foreach($departments as $drow)
												<option value="{{ $drow->id }}" {{($sideptid==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Sales Invoice</b></label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
                                           @foreach($vouchers as $voucher)
											<option value="{{ $voucher['id'] }}" {{($sivchrid==$voucher['id'])?'selected':''}}>{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@else
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>Sales Invoice</b></label>
                                    <div class="col-sm-10">
                                       <select id="voucher_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="voucher_id">
                                           @foreach($vouchers as $voucher)
										   @if(old('voucher_id') && old('voucher_id')==$voucher['id'])
											   @php $sel = 'selected'; @endphp
										   @elseif($sivchrid)==$voucher['id'])
												@php $sel = 'selected'; @endphp
										   @else
											   @php $sel = ''; @endphp
										   @endif
											<option value="{{ $voucher['id'] }}" {{$sel}} >{{ $voucher['voucher_name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								@endif
								
							
								<div class="form-group">
                                     <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>SI. No.</b></label></font>
									<input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$vouchers[0]['voucher_no']}}">
                                    <div class="col-sm-10">
										@can('si-invno')
										<div class="input-group">
										@if($vouchers[0]['is_prefix']==1)<span class="input-group-addon">{{$vouchers[0]['prefix']}}</span>@endif
                                        <input type="text" class="form-control" id="voucher_no" placeholder="{{(old('voucher_no'))?old('voucher_no'):$vouchers[0]['voucher_no']}}" readonly name="voucher_no">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
										@else
										@if($voucher['is_prefix']==1)<span class="input-group-addon">{{$voucher['prefix']}}</span>@endif
										<input type="text" class="form-control" id="voucher_no" placeholder="{{(old('voucher_no'))?old('voucher_no'):$vouchers[0]['voucher_no']}}" readonly name="voucher_no">
										@endcan
                                    </div>
                                </div>
                                
								<?php if($formdata['copy_from']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Paste From</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="sinvoice_id" readonly name="sinvoice_id" placeholder="Copy from existing Invoice" autocomplete="off" onclick="getSalesInvoice()">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="sinvoice_id" id="sinvoice_id">
								<?php } ?>
								

								<?php if($formdata['copy_from_loc']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Paste From LOC</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="sinvoice_id" readonly name="sinvoice_id" placeholder="Copy from existing Loc" autocomplete="off" onclick="getLocation()">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="sinvoice_id" id="sinvoice_id">
								<?php } ?>

								
								<?php if($formdata['reference_no']==1) { ?>
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label <?php if($errors->has('reference_no')) echo 'form-error';?>">Reference No.</label>
										<div class="col-sm-10">
											<input type="text" class="form-control <?php if($errors->has('reference_no')) echo 'form-error';?>" id="reference_no" autocomplete="off" name="reference_no" placeholder="Reference No.">
										</div>
									</div>
								<?php } else { ?>
									<input type="hidden" name="reference_no" id="reference_no">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SI. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right"  <?php if(old('voucher_date')!='') { ?> value="{{(old('voucher_date')=='')?date('d-m-Y'):old('voucher_date')}}"<?php } ?> name="voucher_date" data-language='en' readonly id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								
								<?php if($formdata['lpo_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lpo_no" name="lpo_no" value="{{old('lpo_no')}}" autocomplete="off" placeholder="LPO No.">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_no" id="lpo_no">
								<?php } ?>
								
								<?php if($formdata['lpo_date']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">LPO Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control lpo_date1" id="lpo_date" <?php if(old('lpo_date')!='') { ?> value="{{old('lpo_date')}}" <?php } ?> name="lpo_date" data-language='en' readonly placeholder="LPO Date">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="lpo_date" id="lpo_date">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Account</label>
                                    <div class="col-sm-10">
										<?php if($scrid) { ?>
										<input type="hidden" name="cr_account_id" id="cr_account_id" value="{{$scrid}}">
										<?php } else { ?>
										<input type="hidden" name="cr_account_id" id="cr_account_id" value="{{ (old('cr_account_id'))?old('cr_account_id'):$vouchers[0]['cr_account_master_id'] }}">
										<?php } ?>
										<div class="input-group">
											<?php if($sslsacnt) { ?>
											<input type="text" name="sales_account" id="sales_account" class="form-control" value="{{$sslsacnt}}" readonly>
											<?php } else { ?>
											<input type="text" name="sales_account" id="sales_account" class="form-control" value="{{ (old('sales_account'))?old('sales_account'):$vouchers[0]['account_id'].'-'.$vouchers[0]['master_name'] }}" readonly>
											<?php } ?>
											<span class="input-group-addon inputsa"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
									</div>
                                </div>
								
								<?php if($formdata['sales_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Type</label>
                                    <div class="col-sm-10">
                                       <select id="sales_type" class="form-control select2" style="width:100%" name="sales_type">
											<option value="normal" <?php if(old('sales_type')=='normal') echo 'selected'; ?>>Normal</option>
											<option value="ltol" <?php if(old('sales_type')=='ltol') echo 'selected'; ?>>Location to Location</option>
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
									<select id="sales_type" style="display:none" name="sales_type">
										<option value="normal">Normal</option>
                                    </select>
								<?php } ?>
								
								<div class="form-group" id="saleloc">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Location</label>
                                    <div class="col-sm-10">
                                        <select id="sales_location" class="form-control select2" style="width:100%" name="sales_location">
											<option value="" selected>Select Location..</option>
											<?php 
											foreach($saleslocation as $sloc) { 
											?>
											<option value="{{ $sloc->id }}" <?php if(old('sales_location')==$sloc->id) echo 'selected'; ?>>{{ $sloc->name }}</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                 <font color="#16A085">  <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
										
                                        <input type="text" name="customer_name" id="customer_name" value="{{(old('customer_name'))?old('customer_name'):$vouchers[0]->default_account}}" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer" readonly>
										<input type="hidden" name="customer_id" id="customer_id" value="{{(old('customer_id'))?old('customer_id'):$vouchers[0]->default_account_id}}">
										<input type="hidden" name="dr_account_id" id="dr_account_id" value="{{(old('dr_account_id'))?old('dr_account_id'):$vouchers[0]->default_account_id}}">

										<div class="col-xs-10" id="customerInfo">
											<div class="col-xs-2">
												<span class="small">Current Balance</span> <input type="text" id="cr_balance" name="cbal" value="{{old('cbal')}}" readonly class="form-control line-quantity">
											</div>
											<div class="col-xs-2">
												<span class="small">PDC</span> <input type="text" id="pdc" name="pdc" value="{{old('pdc')}}" readonly class="form-control line-cost">
											</div>
											<!--<div class="col-xs-2">
												<span class="small">Crdit Limit</span> <input type="text" id="cr_limit" name="clmt" value="{{old('clmt')}}" readonly readonly class="form-control cost">
											</div>-->
											<div class="col-xs-2">
												<span class="small">Phone No</span> <input type="text" id="customer_phone" name="customer_phone" value="{{old('customer_phone')}}" class="form-control cpno" autocomplete="off">
											</div>
											<?php if($formdata['vehicle_no']==1) { ?>
											<div class="col-xs-2">
												<span class="small">Vehicle No.</span> <input type="text" id="vehicle_no" name="vehicle_no" value="{{old('vehicle_no')}}" class="form-control vno" autocomplete="off">
											</div>
											<?php } else { ?><input type="hidden" name="vehicle_no" id="vehicle_no"><?php } ?>
											<?php if($formdata['km_miles']==1) { ?>
											<div class="col-xs-2">
												<span class="small">Kilometer/Miles</span> <input type="text" id="kilometer" name="kilometer" value="{{old('kilometer')}}" class="form-control km" autocomplete="off">
											</div>
											<?php } else { ?><input type="hidden" name="kilometer" id="kilometer"><?php } ?>
											<div class="col-xs-1"><br/>
											<?php if (('customer_phone')=='') { ?>
											@can('si-history')
												<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">History</a>
												@endcan
											
											<?php	} else { ?>
												@can('siph-history')
												<a href="" class="btn btn-info cust-history-phone" data-toggle="modal" data-target="#custphonehistory_modal">History</a>
												@endcan
												<?php } ?>
											</div>
										</div>
										<div class="col-xs-10" id="newcustomerInfo">
											<div class="col-xs-3">
												<span class="small">Customer Name</span> <input type="text" id="customername" name="customername" value="{{old('customername')}}" class="form-control" autocomplete="off" >
											</div>
											<div class="col-xs-2">
												<span class="small">Phone No</span> <input type="text" id="customer_phone" name="customer_phone" value="{{old('customer_phone')}}" class="form-control cpno" autocomplete="off">
											</div>
											<div class="col-xs-2">
												<span class="small">TRN No</span> <input type="text" id="customer_trn" name="customer_trn" value="{{old('customer_trn')}}" class="form-control" autocomplete="off">
											</div>
											<?php if($formdata['vehicle_no']==1) { ?>
											<div class="col-xs-2">
												<span class="small">Vehicle No.</span> <input type="text" id="vehicle_no" name="vehicle_no" value="{{old('vehicle_no')}}" class="form-control vno" autocomplete="off">
											</div>
											<?php } else { ?><input type="hidden" name="vehicle_no" id="vehicle_no"><?php } ?>
											<?php if($formdata['km_miles']==1) { ?>
											<div class="col-xs-2">
												<span class="small">Kilometer/Miles</span> <input type="text" id="kilometer" name="kilometer" value="{{old('kilometer')}}" class="form-control km" autocomplete="off">
											</div>
											<?php } else { ?><input type="hidden" name="kilometer" id="kilometer"><?php } ?>
											
											<div class="col-xs-1"><br/>
												<!--@can('si-history')
												<a href="" class="btn btn-info cust-history" data-toggle="modal" data-target="#custhistory_modal">History</a>
												@endcan-->
												@can('siph-history')
												<a href="" class="btn btn-info cust-history-phone" data-toggle="modal" data-target="#custphonehistory_modal">History</a>
												@endcan
											</div>
										</div>
									</div>
                                </div>
								
								<div class="form-group has-warning" id="trninfo">
                                    <label for="input-text" class="col-sm-2 control-label"> TRN No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No." value="{{old('vat_no')}}" autocomplete="off">
                                    </div>
                                </div>
								
								@if($formdata['crm_details']==1)
								<h5><a href="#" id="crmBtn"><b>CRM Details</b></a></h5>
								<hr/>
								<div class="crm_info">
									@foreach($crm as $rw)
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label}}</label>
										<input type="hidden" name="tempid[]" value="{{$rw->id}}">
										@if($rw->text_no==1)
										<div class="col-sm-10">
											<input type="text" name="crmtext[]" class="form-control" autocomplete="on">
											<input type="hidden" name="crmtext2[]">
										</div>
										@else
										<div class="col-sm-4">
											<input type="text" name="crmtext[]" class="form-control" autocomplete="on">
										</div>
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label2}}</label>
										<div class="col-sm-4">
											<input type="text" name="crmtext2[]" class="form-control" autocomplete="on">
										</div>
										@endif
									</div>
									@endforeach
								<hr/>
								</div>
								@endif
								
								<?php if($formdata['salesman']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="salesman" id="salesman" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
										<input type="hidden" name="salesman_id" id="salesman_id">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
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
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Document Type</label>
                                    <div class="col-sm-10">
									 <select id="document_type" class="form-control select2" style="width:100%" name="document_type">
										<option value="">Select Document...</option>
										<option value="SQ">Sales Quotation</option>
										<option value="SO">Sales Order</option>
										<option value="CDO">@php echo (Session::get('trip_entry')==1)?'Daily Entry':'Delivery Order'; @endphp</option>
										<!--<option value="SO">Payment Certificate</option>-->
									</select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_type" id="document_type">
								<?php } ?>
								
								<?php if($formdata['document_type']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document ID#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="document_id" readonly name="document_id" placeholder="Document ID" autocomplete="off" onclick="getDocument()">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="document_id" id="document_id">
								<?php } ?>
								
								
								<?php if($formdata['so_no']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Sales Order No.</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="so_no" name="so_no" placeholder="Sales Order No" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="so_no" id="so_no">
								<?php } ?>
								
								
								<?php if($formdata['terms']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
                                            <option value="">Select Terms...</option>
											@foreach ($terms as $term)
											<option value="{{ $term['id'] }}">{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="terms_id" id="terms_id">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Day</label>
                                    <div class="col-sm-4">
                                        	<input type="number" class="form-control" id="duedays" name="duedays" placeholder="Due Days">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Due Date</label>
                                    <div class="col-sm-4">
                                        <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="due_date" data-language='en' id="due_date" value="{{date('d-m-Y')}}"/>
										
                                    </div>
                                    </div>
                                </div>
								
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
													<input type="text" id="itmcod_{{$j}}" name="item_code[]" class="form-control <?php if($errors->has('item_code.'.$i)) echo 'form-error';?>" value="{{ $item }}" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
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
												</td>
												<td width="6%">
													<select id="taxcode_{{$j}}" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_{{$j}}" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0" {{($settings->si_vat_inc==0) ? 'selected' : ''}}>No</option><option value="1" {{($settings->si_vat_inc==1) ? 'selected' : ''}} >Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_{{$j}}" name="hidunit[]" class="hidunt" value="{{old('hidunit')[$i]}}">
													<input type="hidden" id="packing_{{$j}}" name="packing[]" value="{{ old('packing')[$i]}}">
													<input type="text" id="vatdiv_{{$j}}" step="any" readonly name="vatdiv[]" value="{{ old('vatdiv')[$i]}}" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_{{$j}}" name="line_vat[]" value="{{ old('line_vat')[$i]}}" class="form-control vat">
													<input type="hidden" id="vatlineamt_{{$j}}" name="vatline_amt[]" value="{{ old('vatline_amt')[$i]}}" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_{{$j}}" name="line_discount[]" value="{{ old('line_discount')[$i]}}">
													<input type="hidden" id="costavg_{{$j}}" name="costavg[]" value="{{ old('costavg')[$i]}}">
													<input type="hidden" id="purcost_{{$j}}" name="purcost[]" value="{{ old('purcost')[$i]}}">
													<input type="hidden" id="cqty_{{$j}}" name="curqty[]" value="{{ old('curqty')[$i]}}">
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
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } else { ?>
														 <button type="button" class="btn-success btn-add-item" >
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														<button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$j}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
														<?php } ?>
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
								<?php if($formdata['add_desc']==1) { ?>
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_{{$j}}" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div><?php } else { ?>
								<input type="hidden" name="add_desc" id="add_desc">
								<?php } ?>
								<?php if($formdata['location_item']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_{{$j}}" class="btn btn-primary btn-xs loc-info">Location</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_item" id="location_item">
								<?php } ?>	
											@if($isconloc)
											<div id="cnloc" style="float:left; padding-right:5px;">
												<button type="button" id="cnloc_{{$j}}" class="btn btn-primary btn-xs cnloc-info">Consignment Location</button>
											</div>
											@endif
											<?php if($formdata['supersede']==1) { ?>
											<div id="ssede" style="float:left; padding-right:5px;">
												<button type="button" id="sede_{{$j}}" class="btn btn-primary btn-xs sup-sede">Supersede</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="supersede" id="supersede">
								<?php } ?><?php if($formdata['purchase_item']==1) { ?>	
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_{{$j}}" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="purchase_item" id="purchase_item">
								<?php } ?><?php if($formdata['sales_item']==1) { ?>
											<div style="float:left;">
												<button type="button" id="saleshisItm_{{$j}}" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="sales_item" id="sales_item">
								<?php } ?><?php if($formdata['customer_sales']==1) { ?>	
											<div>
												<button type="button" id="custsaleshisItm_{{$j}}" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="customer_sales" id="customer_sales">
								<?php } ?>
											<!--
											<div style="float:left;">
												Amount in % <input type="text" style="width:50px;" id="per_{{$j}}" name="per[]" class="perc"/> <input type="text" style="width:250px;" placeholder="Description..." id="perdesc_{{$j}}" name="perdesc[]"/>
											</div>
											-->
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
											
											<div class="descdivPrntItm" id="descdivPrntItm_{{$j}}">
												<div class="descdivChldItm" >							
													<div class="col-xs-10" style="padding-bottom:5px !important;">
														<div class="col-xs-10">
															<input type="text" name="itemdesc[0][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
														</div>
														<div class="col-xs-2">
															 <button type="button" class="btn btn-success btn-add-desc" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
															 <button type="button" class="btn btn-danger btn-remove-desc" >
																<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
															</button>
														</div>
													</div>
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
													<input type="text" id="itmcod_1" name="item_code[]" class="form-control" autocomplete="off" placeholder="Item Code" data-toggle="modal" data-target="#item_modal">
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
													<input type="number" id="itmqty_1" step="any" name="quantity[]" autocomplete="off" class="form-control line-quantity" placeholder="Qty." {{($formdata['location_item']==1)?'readonly':''}}>
												</td>
												<td width="8%">
													<input type="number" id="itmcst_1" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" placeholder="Cost/Unit">
												</td>
												<td width="6%">
													<select id="taxcode_1" class="form-control select2 tax-code" style="width:100%" name="tax_code[]"><option value="SR">SR</option><option value="EX">EX</option><option value="ZR">ZR</option></select>
												</td>
												<td width="6%">
													<select id="txincld_1" class="form-control select2 taxinclude" style="width:100%" name="tax_include[]"><option value="0" {{($settings->si_vat_inc==0) ? 'selected' : ''}}>No</option><option value="1" {{($settings->si_vat_inc==1) ? 'selected' : ''}}>Yes</option></select>
												</td>
												<td width="9%">
													<input type="hidden" id="hidunit_1" name="hidunit[]" class="hidunt">
													<input type="hidden" id="packing_1" name="packing[]" value="1">
													<input type="text" id="vatdiv_1" step="any" readonly name="vatdiv[]" class="form-control vatdiv" placeholder="VAT Amt"> 
													<input type="hidden" id="vat_1" name="line_vat[]" class="form-control vat">
													<input type="hidden" id="vatlineamt_1" name="vatline_amt[]" class="form-control vatline-amt" value="0">
													<input type="hidden" id="itmdsnt_1" name="line_discount[]">
													<input type="hidden" id="costavg_1" name="costavg[]">
													<input type="hidden" id="purcost_1" name="purcost[]">
													<input type="hidden" id="cqty_1" name="curqty[]">
												</td>
												<td width="11%">
													<input type="number" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">													
													<!--<input type="hidden" id="itmttl_1" step="any" name="line_total[]" class="form-control line-total" readonly placeholder="Total">-->
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
											<div id="moreinfo" style="float:left; padding-right:5px;">
												<button type="button" id="moreinfoItm_1" class="btn btn-primary btn-xs more-info">More Info</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="more_info" id="more_info">
								<?php } ?>
								<?php if($formdata['add_desc']==1) { ?>
											<div id="moredesc" style="float:left; padding-right:5px;">
												<button type="button" id="descinfoItm_1" class="btn btn-primary btn-xs desc-info">Add Description</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="add_desc" id="add_desc">
								<?php } ?><?php if($formdata['location_item']==1) { ?>
											<div id="loc" style="float:left; padding-right:5px;">
												<button type="button" id="loc_1" class="btn btn-primary btn-xs loc-info">Location</button>
												<div class="form-group"><input type="text" name="iloc[]" id="iloc_1" style="border:none;color:#FFF;"></div><!-- NOV24 -->
											</div>
											<?php } else { ?>
								<input type="hidden" name="location_item" id="location_item">
								<?php } ?>	
								
								<!--MAY25-->
								<div id="batchdiv_1" style="float:left; padding-right:5px;" class="addBatchBtn">
									<button type="button" id="btnBth_1" class="btn btn-primary btn-xs batch-add" data-toggle="modal" data-target="#batch_modal">Add Batch</button>
									<div class="form-group"><input type="text" name="batchNos[]" id="bthSelIds_1" style="border:none;color:#FFF;"></div>
                                    <input type="hidden" id="bthSelQty_1" name="qtyBatchs[]">
								</div>
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
								<input type="hidden" name="supersede" id="supersede">
								<?php } ?><?php if($formdata['purchase_item']==1) { ?>	
											<div style="float:left; padding-right:5px;">
												<button type="button" id="purhisItm_1" data-toggle="modal" data-target="#purchase_modal" class="btn btn-primary btn-xs pur-his">Purchse</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="purchase_item" id="purchase_item">
								<?php } ?>								<?php if($formdata['sales_item']==1) { ?>
											
											<div style="float:left; padding-right:10px;">
												<button type="button" id="saleshisItm_1" data-toggle="modal" data-target="#sales_modal" class="btn btn-primary btn-xs sales-his">Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="sales_item" id="sales_item">
								<?php } ?><?php if($formdata['customer_sales']==1) { ?>
											<div style="float:left; padding-right:10px;">
												<button type="button" id="custsaleshisItm_1" data-toggle="modal" data-target="#cust_sales_modal" class="btn btn-primary btn-xs cust-sales-his">Customer Sales</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="customer_sales" id="customer_sales">
								<?php } ?><?php if($formdata['add_assembley']==1) { ?>
											<div style="float:left; padding-right:10px;">
												<button type="button" id="assemblyItm_1" data-toggle="modal" data-target="#assembly_modal" class="btn btn-primary btn-xs asm-itm">Add Assembly Items</button>
												<input type="hidden" name="assembly_items[]" id="asmid_1">
												<input type="hidden" name="assembly_items_qty[]" id="asmqt_1">
											</div>
											<?php } else { ?>
								<input type="hidden" name="add_assembley" id="add_assembley">
								<?php } ?><?php if($formdata['view_assembley']==1) { ?>
											<div style="float:left; padding-right:5px;">
												<button type="button" id="assemblyItmVw_1" data-toggle="modal" data-target="#view_assembly_modal" class="btn btn-primary btn-xs view-asm-itm">View Assembly Items</button>
											</div>
											<?php } else { ?>
								<input type="hidden" name="view_assembley" id="view_assembley">
								<?php } ?><?php if($formdata['dimension']==1) { ?>
								<div id="itmInfo">
								<button type="button" id="itmInfo_1" class="btn btn-primary btn-xs dimn-view">Dimension</button>
								</div>
								<?php } else { ?>
								<input type="hidden" name="itmInfo" id="itmInfo">
								<?php } ?>
											<!--
											<div style="float:left;">
												Amount in % <input type="text" style="width:50px;" id="per_1" name="per[]" class="perc"/> <input type="text" style="width:250px;" placeholder="Description..." id="perdesc_1" name="perdesc[]"/>
											</div>
											-->
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
												
											<div class="sedePrntItm" id="sedePrntItm_1">
												<div class="sedeChldItm" style="float:right; padding-right:10px;">							
													<div class="table-responsive sede-data" id="sedeData_1"></div>
												</div>
											</div>

											<div class="dimnInfodivPrntItm" id="dimnInfodivPrntItm_1">
													<div class="dimnInfodivChldItm">							
														<div class="table-responsive dimn-item-data" id="dimnitemData_1"></div>
													</div>
												</div>
											
											<div class="descdivPrntItm" id="descdivPrntItm_1">
												<div class="descdivChldItm" >							
													<div class="col-xs-10" style="padding-bottom:5px !important;">
														<div class="col-xs-10">
															<input type="text" name="itemdesc[0][]" autocomplete="off" class="form-control txt-desc" placeholder="Description">
														</div>
														<div class="col-xs-2">
															 <button type="button" class="btn btn-success btn-add-desc" >
																<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
															 </button>
															 <button type="button" class="btn btn-danger btn-remove-desc" >
																<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
															</button>
														</div>
													</div>
												</div>
											</div>
									</div>
								<?php } ?>
								</div>
								
								</fieldset>
								
								<fieldset>
									<legend>
									    <?php if($formdata['selling_expense']==1) { ?>
										<div id="oc_showmenu" style="padding-left:5px;"><button type="button" id="ocadd" class="btn btn-primary btn-xs">Selling Expense</button></div>
										<?php } else { ?>
								<input type="hidden" name="selling_expense" id="selling_expense">
								<?php } ?>
									</legend>
									<div class="OCdivPrnt">
									<input type="hidden" id="ocrowNum" value="1">
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
													<td width="3%"><br/>
														<button type="button" class="btn btn-success btn-add-oc" >
															<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
														 </button>
													</td>
												</tr>
											</table>
											</div>											
											<hr/>
										</div>
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
										<span class="small" id="fc_label">Currency</span>	<input type="number" id="total" step="any" value="{{old('total')}}" name="total" class="form-control spl" readonly placeholder="0">
										</div>
										<div class="col-xs-2">
										<span class="small" id="c_label">Currency Dhs</span>	<input type="number" id="total_fc" step="any" value="{{old('total_fc')}}" name="total_fc" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								@if($roundoff)
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Round Off</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											<input type="number" step="any" id="roundoff" name="roundoff" value="{{old('roundoff')}}" class="form-control spl round-off" placeholder="0">
											<input type="hidden" id="discount" name="discount" value="{{old('discount')}}" class="form-control spl discount-cal">
										</div>
										<div class="col-xs-2">
											<input type="number" step="any" id="roundoff_fc" name="roundoff_fc" value="{{old('roundoff_fc')}}" class="form-control spl">
											<input type="hidden" id="discount_fc" name="discount_fc" value="{{old('discount_fc')}}" class="form-control spl">
										</div>
									</div>
                                </div>
								@else
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
								@endif
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc" name="less_description" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount" name="less_amount" value="{{old('less_amount')}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount" id="less_amount">
								<?php } ?>
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc2" name="less_description2" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount2" name="less_amount2" value="{{old('less_amount2')}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount2" id="less_amount2">
								<?php } ?>
								
								<?php if($formdata['less_amount']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" step="any" class="form-control spl" id="les_amt_desc3" name="less_description3" placeholder="Description" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="less_amount3" name="less_amount3" value="{{old('less_amount3')}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount3" id="less_amount3">
								<?php } ?>
								
								<?php if($formdata['less_prev_invoice']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Less</label>
                                    <div class="col-xs-10">
										<div class="col-xs-8">
											<input type="text" class="form-control spl" id="previnv_description" name="previnv_description" placeholder="Previous Invoice Details" autocomplete="on">
										</div>
                                    
										<div class="col-xs-2">
											<input type="number" step="any" id="previnv_amount" name="previnv_amount" value="{{old('previnv_amount')}}" class="form-control spl lesamt" placeholder="0">
										</div>
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="less_amount" id="less_amount">
								<?php } ?>
								
								
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
										<div class="col-xs-8">
											<?php if($formdata['vat_description']==1) { ?><input type="text" step="any" class="form-control spl" id="vatdesc" name="vatdesc" value="5% VAT Extra" readonly><input type="hidden" id="vatd" name="vatfrm_subtotal" value="5"/><?php } ?>
										</div>
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
											<input type="hidden" step="any" id="other_cost" name="other_cost">
											<input type="number" step="any" id="net_amount_fc" name="net_amount_fc" value="{{old('net_amount_fc')}}" class="form-control spl" readonly placeholder="0">
										</div>
									</div>
                                </div>
								
								<?php if(isset($formdata['advance']) && $formdata['advance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Advance</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control spl" id="advance" name="advance" placeholder="Advance" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="advance" id="advance">
								<?php } ?>
								
								<?php if(isset($formdata['balance']) && $formdata['balance']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control spl" id="balance" readonly name="balance" placeholder="Balance" autocomplete="off">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="balance" id="balance">
								<?php } ?>
								
								<hr/>
								<?php if($formdata['images']==1) { ?>
								<div class="filedivPrnt">
									<div class="filedivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_1">Upload Image 1</label>
											<div class="col-sm-9">
												<input type="file" id="input-51" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('sales_invoice/upload/')}}">
												<div id="files_list_1"></div>
												<p id="loading_1"></p>
												<input type="hidden" name="photo_name[]" id="photo_name_1">
											</div>
											<div class="col-sm-1">
												<button type="button" class="btn-success btn-add-file" id="btn_1">
													<i class="fa fa-fw fa-plus-square"></i>
												</button>
												<button type="button" class="btn-danger btn-remove-file">
													<i class="fa fa-fw fa-minus-square"></i>
												 </button>
											</div>
											<label for="input-text" class="col-sm-2 control-label filedslbl" id="lblifd_1">Description</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="imgdesc_1" name="imgdesc[]" placeholder="Description" autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<?php } ?>
								
								<?php if($formdata['footer']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Footer</label>
                                    <div class="col-sm-10">
										<input type="hidden" name="footer_id" id="footer_id">
                                        <input type="text" class="form-control" id="footermsg" name="footer" placeholder="Footer" autocomplete="off" data-toggle="modal" data-target="#footer_modal">
                                    </div>
                                </div>
								<?php } else { ?>
									<input type="hidden" name="footer_id" id="footer_id">
								<?php } ?>
								
								<?php if(isset($formdata['rv_entry']) && $formdata['rv_entry']==1) { ?>
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Receipt Voucher Entry</label>
                                    <div class="col-sm-10">
                                        <label class="radio-inline iradio">
											<input type="checkbox" class="rv_icheck" id="is_rv" name="is_rv" value="1">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="is_rv" id="is_rv">
								<?php } ?>
								
								<div id="rv_form">
									<input type="hidden" id="rvdraccount" value="{{$rvvoucher['account_name']}}">
									<input type="hidden" id="rvdraccountid" value="{{$rvvoucher['id']}}">
									<input type="hidden" name="rvid" value="{{$rvvoucher['rid']}}">
									<div class="RVdivPrnt">
										<div class="RVdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="10%">
														<input type="hidden" id="rvvoucher_1" name="rv_voucher[]" value="{{$rvid}}"/>
														<span class="small">Voucher Type</span>
														<select id="vouchertype_1" class="form-control select2 voucher_type" style="width:100%" name="voucher_type[]">
														<option value="CASH">Cash</option>
														<option value="BANK">Bank</option>
														<option value="PDCR">PDC</option>
													</select>
													</td>
													<td width="10%">
														<span class="small">Voucher No</span>
														<input type="text" name="rv_voucher_no[]" value="{{$rvvoucher['voucher_no']}}" id="rvvoucherno_1" autocomplete="off" class="form-control">
													</td>
													<td width="20%">
														<span class="small">Debit Account</span>
														<input type="text" name="rv_dr_account[]" id="rvdraccount_1" autocomplete="off" data-toggle="modal" data-target="#customer_modalRV" class="form-control" value="{{$rvvoucher['account_name']}}">
														<input type="hidden" id="rvdraccountid_1" name="rv_dr_account_id[]" value="{{$rvvoucher['id']}}">
													</td>
													<td width="15%" class="form-group">
														<span class="small">Amount</span> 
														<input type="number" id="rvamount_1" step="any" name="rv_amount[]" class="form-control" placeholder="Amount" />
													</td>
													<td width="15%">
														<span class="small lbban_1">Bank</span> 
														<select id="bankid_1" class="form-control select2 bank" style="width:100%" name="bank_id[]">
														<option value="">Select</option>
														@foreach($banks as $bank)
														<option value="{{$bank['id']}}" <?php if(old('bank_id')==$bank['id']) echo 'selected'; ?>>{{$bank['name']}}</option>
														@endforeach
														</select>
													</td>
													<td width="15%" class="form-group">
														<span class="small lbchq_1">Cheque No</span>
														<input type="text" name="cheque_no[]" id="chequeno_1" class="form-control" placeholder="Cheque No" />
													</td>
													<td width="15%" class="form-group">
														<span class="small lbchqd_1">Cheque Date</span>
														<input type="text" name="cheque_date[]" id="chequedate_1" autocomplete="off" class="form-control" data-language='en' readonly placeholder="Cheque Date">
													</td>
													<td width="1%">
														<button type="button" class="btn-success btn-add-rv">
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-rv" data-id="rem_1">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</div>
								
								<input type="hidden" name="footer" id="footer">
									
								<?php if($formdata['footer_edit']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <textarea name="foot_description" id="foot_description" class="form-control editor-cls" placeholder="Description">{{$footer}}</textarea>
                                    </div>
                                </div>
								<?php } ?>
								
								<!--<div id="showmenu">
									<button type="button" id="infoadd" class="btn btn-primary btn-xs">Add Info..</button>
								</div>-->
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
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('sales_invoice') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('sales_invoice/add') }}" class="btn btn-warning">Clear</a>
										@can('si-history')
										<a href="" class="btn btn-info order-history" data-toggle="modal" data-target="#history_modal">View Order History</a>
										@endcan
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
							
							<div id="cust_sales_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Customer Sales History</h4>
                                        </div>
                                        <div class="modal-body" id="custsaleshisData">
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
							
							<div id="assembly_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Assembly Items</h4>
                                        </div>
                                        <div class="modal-body" id="asmblyitmData">
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
                                            <h4 class="modal-title">Assembly Items</h4>
                                        </div>
                                        <div class="modal-body" id="conlocData">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="view_assembly_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Assembly Items</h4>
                                        </div>
                                        <div class="modal-body" id="asmblyitmDataVw">
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
							
							<div id="customer_modalRV" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Account</h4>
                                        </div>
                                        <div class="modal-body" id="customerDataRV">
														
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>  
							
							 <div id="newcustomer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Customer</h4>
                                        </div>
                                        <div class="modal-body" id="newcustomerData">
														
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
							
							<div id="salesman_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Salesman</h4>
                                        </div>
                                        <div class="modal-body" id="salesmanData">
                                            
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
							
							<div id="footer_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Select Footer</h4>
                                        </div>
                                        <div class="modal-body" id="ftr">
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
                                        <div class="modal-body" id="historyData">Please select a Customer first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="custhistory_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Customer History</h4>
                                        </div>
                                        <div class="modal-body" id="custhistoryData">Please select a Customer first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div id="custphonehistory_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Customer History</h4>
                                        </div>
                                        <div class="modal-body" id="custphonehistoryData">Please select a phone no first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
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
			
			<div id="bcost_modal" class="modal fade animated" role="dialog">
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
							<button type="button" class="btn btn-primary" onClick="pswdOkSubmit()">OK</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
			<!--MAY25-->
            <div id="batch_modal" class="modal fade animated" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add Batch</h4>
                        </div>
                        <div class="modal-body" id="batchData"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary saveBatch">Add</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
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

<script src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/trumbowyg/js/trumbowyg.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/custom_js/form_editors.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
<script> 
"use strict";

//MAY25
$('.addBatchBtn').hide();
let bthArr = [];
let uniqueBtharr;

$('#optHide').toggle(); var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>; var packing = 1; //VAT CHNG
$('#saleloc').toggle(); var dptTxt; var deptid;
$('.OCdivPrnt').toggle();
$('#voucher_date').datepicker( { autoClose: true ,dateFormat: 'dd-mm-yyyy'} );
$('#lpo_date').datepicker( { dateFormat: 'dd-mm-yyyy',minDate: new Date('{{$settings->from_date}}'),maxDate: new Date('{{$settings->to_date}}'),autoClose: true } );

var is_belowCost = false;
	
$(document).ready(function () { 

	$('.crm_info').hide();
	@if(old('voucher_date')=='')
		$('#voucher_date').val('{{date('d-m-Y')}}');
	@else
		$('#voucher_date').val('{{old('voucher_date')}}'); 
	@endif

	$('.dimnInfodivPrntItm').toggle();
	
	if($('#department_id').length) {
		dptTxt = $( "#department_id option:selected" ).text();
		deptid = $( "#department_id option:selected" ).val();
	}
	
	<?php if(!old('item_code')) { ?>
	//$('.btn-remove-item').hide();
	//ROWCHNG
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	<?php } else { ?>
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	<?php } ?>
	
	@if(!old('voucher_id'))

	<?php if(!Session::has('is_cash') && sizeof($vouchers) > 0 && $vouchers[0]->is_cash_voucher==1) { ?> //cash customer....  customerInfo
		//$('#customer_name').removeAttr("data-toggle");  customername F7
		if( $('#newcustomerInfo').is(":hidden") )
			$('#newcustomerInfo').toggle();
		$('#customer_id').val({{$vouchers[0]->default_account_id}});
		$('#dr_account_id').val({{$vouchers[0]->default_account_id}});
		$('#customer_name').val('{{$vouchers[0]->default_account}}');
		if( $('#customerInfo').is(":visible") )
			$('#customerInfo').toggle();
	<?php } else {   ?>
		if( $('#newcustomerInfo').is(":visible") )
			$('#newcustomerInfo').toggle();
		if( $('#customerInfo').is(":hidden") ) 
			$('#customerInfo').toggle();
		<?php if($siscsh==0) { ?>
			$('#customer_name').attr("data-toggle", "modal");
			$('#customer_id').val('');
			$('#dr_account_id').val('');
			$('#customer_name').val('');
	<?php } } ?>
		if(localStorage.getItem("isCs")==1) {
			if( $('#newcustomerInfo').is(":hidden") )
				$('#newcustomerInfo').show();
		
			if( $('#customerInfo').is(":visible") )
				$('#customerInfo').hide();
		} else {
			if( $('#customerInfo').is(":hidden") ) 
					$('#customerInfo').show();
			
			if( $('#newcustomerInfo').is(":visible") )
				$('#newcustomerInfo').hide();
		}
	@else
		if( $('#newcustomerInfo').is(":visible") )
			$('#newcustomerInfo').toggle();
		if( $('#customerInfo').is(":hidden") ) 
			$('#customerInfo').toggle();
	@endif
	
	//console.log('dd'+localStorage.getItem("cName"));
	if(localStorage.getItem("vcId")!=null) {
		//$('#voucher_id option[value='+localStorage.getItem("vcId")+']').attr('selected','selected');
		$("#voucher_id").val(localStorage.getItem("vcId")).change();
		$('#cr_account_id').val(localStorage.getItem("crId"));
		$('#sales_account').val(localStorage.getItem("sAc"));

		$('#customer_name').val(localStorage.getItem("cName"));
		$('#customer_id').val(localStorage.getItem("cId"));
		$('#dr_account_id').val(localStorage.getItem("drId"));


	}

	//$("#currency_rate").prop('disabled', true); 
	$('#rv_form').toggle(); $('#chequeno_1').hide(); $('#chequedate_1').hide(); $('#bankid_1').hide(); $('.lbban_1').hide();$('.lbchq_1').hide();$('.lbchqd_1').hide();
	$('#trninfo').toggle();
	$("#currency_id").prop('disabled', true); 
	$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $("#subtotal_fc").toggle(); $('.sedePrntItm').toggle();
	$('.descdivPrntItm').toggle();$('.locPrntItm').toggle(); $('.cnlocPrntItm').toggle(); $('#roundoff_fc').toggle();
	var urlvchr = "{{ url('sales_invoice/checkvchrno/') }}"; //CHNG
	var urlcode = "{{ url('sales_invoice/checkrefno/') }}";
    $('#frmSalesInvoice').bootstrapValidator({
        fields: {
			//voucher_id: { validators: { notEmpty: { message: 'The voucher type is required and cannot be empty!' } }},
			voucher_no: { //CHNG
                validators: {
                    /*notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    },*/
					/*remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val(),
								deptid: deptid
                            };
                        },
                        message: 'This SI. No. is already exist!'
                    }*/
                }
            },
            customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			'rv_amount[]': { validators: { notEmpty: { message: 'The RV amount is required and cannot be empty!' } }},
			@if($formdata['location_item']==1) , 'iloc[]': { validators: { notEmpty: { message: 'Item location quantity is required and cannot be empty!' } }},@endif  //NOV24
			/*'cheque_no[]': { validators: { notEmpty: { message: 'The cheque no is required and cannot be empty!' } }},
			'cheque_date[]': { validators: { notEmpty: { message: 'The cheque date is required and cannot be empty!' } }},
			'bank_id[]': { validators: { notEmpty: { message: 'The bank is required and cannot be empty!' } }}, */
			// reference_no: {
            //     validators: {
            //         /* notEmpty: {
            //             message: 'The reference no is required and cannot be empty!'
            //         }, */
			// 		remote: {
            //             url: urlcode,
            //             data: function(validator) {
            //                 return {
            //                     code: validator.getFieldElements('reference_no').val()
            //                 };
            //             },
            //             message: 'This Reference No. is already exist!'
            //         }
            //     }
            // },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }}
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmSalesInvoice').data('bootstrapValidator').resetForm();
    });
	
	$('.custom_icheck').on('ifChecked', function(event){ 
		$("#currency_id").prop('disabled', false);
		$("#currency_rate").prop('disabled', false);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $("#subtotal_fc").toggle();
	});
	$('.custom_icheck').on('ifUnchecked', function(event){ 
		$("#currency_id").prop('disabled', true);
		$("#currency_rate").prop('disabled', true);
		$("#fc_label").toggle(); $("#c_label").toggle();$("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle(); $("#subtotal_fc").toggle();
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
	
	$(document).on('ifChecked', '.rv_icheck', function(event){ 
		$('#rv_form').toggle(); $('#rv_amount').val( parseFloat( ($('#net_amount').val()=='') ? '' : $('#net_amount').val() ) );
		//$('[name="rv_amount[]"]').attr("required",true);
	});
	
	$('.rv_icheck').on('ifUnchecked', function(event){ 
		$('#rv_form').toggle();
		//$('input[name="rv_amount[]"]').removeAttr("required");
	});
});

	//calculation item net total, tax and discount...
	function getNetTotal() {
		//console.log('nt');
		var lineTotal = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		//console.log('lineTotal: '+lineTotal);
		$('#total').val(lineTotal.toFixed(2));
		
		/* if($('#less_amount').length) {
			var less;
			less = parseFloat( ($('#less_amount').val()=='') ? 0 : $('#less_amount').val() );
			lineTotal = lineTotal - less;
		}
		
		if($('#previnv_amount').length) {
			var prvamt;
			prvamt = parseFloat( ($('#previnv_amount').val()=='') ? 0 : $('#previnv_amount').val() );
			lineTotal = lineTotal - prvamt;
		} */
		
		var less = 0;
		if($('.lesamt').length) {
			$( '.lesamt' ).each(function() {
				var v = (this.value=='')?0:parseFloat(this.value);
				less = less + v;
			});
			lineTotal = lineTotal - less;
		}
		
		$('#subtotal').val(lineTotal.toFixed(2));
		
		var vatcur = 0;
		$( '.vatline-amt' ).each(function() {
			vatcur = vatcur + parseFloat(this.value);
		});
		
		if($('#vatd').length){
			vatcur = (lineTotal * 5)/100;
		}
		
		$('#vat').val(vatcur.toFixed(2));
		
		var discount = parseFloat( ($('#discount').val()=='') ? 0 : $('#discount').val() );
		var vat      = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
		var total 	 = lineTotal - discount;
		var netTotal = total + vat;
		$('#net_amount').val(netTotal.toFixed(2));
		$('#net_amount_hid').val(netTotal+discount);
		
		if( $('#is_fc').is(":checked") ) { 
			var rate       = parseFloat($('#currency_rate').val());
			console.log(' rate: '+ rate);
			var vat        = parseFloat( ($('#vat').val()=='') ? 0 : $('#vat').val() );
			var fcTotal    = total * rate;
			console.log(' discount: '+ discount);
			var fcDiscount = discount * rate;
			$('#total_fc').val(fcTotal.toFixed(2));
			$('#discount_fc').val(fcDiscount.toFixed(2));
			var fcTax = vat * rate; var subfc = lineTotal * rate;
			var fcNetTotal = (fcTotal - fcDiscount) + fcTax;
			$('#vat_fc').val(fcTax); $('#subtotal_fc').val(subfc.toFixed(2));
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
	//AG24	//lineQuantity	 = lineQuantity * parseFloat( ($('#packing_'+n).val()=='') ? 0 : $('#packing_'+n).val() ); //VAT CHNG
		
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
		
		//SEP 17
		if($('#per_'+n).length){
			var per = parseFloat( ($('#per_'+n).val()!='')?$('#per_'+n).val():0);
			if(per!=0) {
				var lineTotal = (lineTotal * per) / 100; //console.log('ln'+lntot_nw);
				$('#itmttl_'+n).val(lineTotal)
			}
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
		var customer_id = $('#customer_id').val();
		var crate = $('#currency_rate').val();
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&customer_id='+customer_id+'&crate='+crate,
			success: function(data) {
				$('#itmcst_'+curNum).val((data==0)?'':data);
				return true;
			}
		}) 
	}
	
	function getAutoPriceBkp(curNum) {
		
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var customer_id = $('#customer_id').val(); 
		var crate = $('#currency_rate').val();
		var cst = $('#itmcst_'+curNum).val(); //NW
		if($('#sales_type option:selected').val()=='normal') {
			$.ajax({
				url: "{{ url('itemmaster/get_sale_cost/') }}", 
				type: 'get',
				data: 'item_id='+item_id+'&customer_id='+customer_id+'&crate='+crate,
				success: function(data) { 
					if(cst=='') //NW
						$('#itmcst_'+curNum).val((data==0)?'':data);
					else
						$('#itmcst_'+curNum).val(cst);
					$('#itmcst_'+curNum).focus();
					return true;
				}
			}); 
			getLineTotal(curNum);
		} else {
			$.ajax({
				url: "{{ url('itemmaster/get_cost_sale/') }}", //get_cost_avg
				type: 'get',
				data: 'item_id='+item_id,
				//data: 'item_id='+item_id+'&unit_id='+unit_id,
				success: function(data) { 
					if(cst=='') //NW
						$('#itmcst_'+curNum).val((data==0)?'':data);
					else
						$('#itmcst_'+curNum).val(cst);
					//$('#itmcst_'+curNum).focus();
					return true;
				}
			}); 
		}
	}
	
	
	//calculation other cost...
	function getOtherCost() {
		 
		var otherCost = 0; var otherCostTx = 0;
		if( $('.OCdivPrnt').is(":visible") ) { 
			$( '.oc-line' ).each(function() { 
				var res = this.id.split('_');
				var curNum = res[1];
				var ocamt_ln = this.value;
				otherCostTx = otherCostTx + parseFloat(ocamt_ln);
				otherCost = otherCost + parseFloat(ocamt_ln);
			}); 
			$('#other_cost').val(otherCostTx.toFixed(2));
		}
		return true;
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
		
		if($('#roundoff').length)
			var roundoff = parseFloat( ($('#roundoff').val()=='') ? 0 : $('#roundoff').val() );
		else
			var roundoff = 0;
		
			var subtotal = lineTotal - discount;
			
			/* if($('#less_amount').length) {
				var less;
				less = parseFloat( ($('#less_amount').val()=='') ? 0 : $('#less_amount').val() );
				subtotal = subtotal - less;
			}
			
			if($('#previnv_amount').length) {
				var prvamt;
				prvamt = parseFloat( ($('#previnv_amount').val()=='') ? 0 : $('#previnv_amount').val() );
				subtotal = subtotal - prvamt;
			} */
			
			var less = 0;
			if($('.lesamt').length) {
				$( '.lesamt' ).each(function() {
					var v = (this.value=='')?0:parseFloat(this.value);
					less = less + v;
				});
				subtotal = subtotal - less;
			}
			
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
			
			if($('#vatd').length){
				vatnet = (subtotal * 5)/100;
			}
			
			$('#subtotal').val(subtotal.toFixed(2));
			$('#vat').val(vatnet.toFixed(2));
			
			if(taxinclude==true && discount==0) {
				$('#subtotal').val( (subtotal - vatnet).toFixed(2) );
				$('#subttle').html('(VAT Exclude)');
				$('#net_amount').val( (subtotal - roundoff).toFixed(2) );
				
			} else if(taxinclude==true && discount>0) { 
				$('#subttle').html('');
				//$('#net_amount').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
				$('#subtotal').val( (parseFloat($('#subtotal').val()) - parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
				$('#net_amount').val(subtotal.toFixed(2));
			} else {
				$('#subttle').html('');
				$('#net_amount').val( (parseFloat($('#subtotal').val()) + parseFloat($('#vat').val()) - roundoff ).toFixed(2) );
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
	
	function calculateRoundoff()
	{ 
		calculateDiscount();
	}
	
	function removeAr(arr) {
		var what, a = arguments, L = a.length, ax;
		while (L > 1 && arr.length) {
			what = a[--L];
			while ((ax= arr.indexOf(what)) !== -1) {
				arr.splice(ax, 1);
			}
		}
		return arr;
	}
function calculateDueDate(days) {
           const today = new Date();
           const dueDate = new Date();
            console.log(dueDate);
            dueDate.setDate(today.getDate() + days);
            const due= dueDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD
           const formatted = dueDate.toLocaleDateString('es-CL'); // DD/MM/YYYY
           console.log(formatted);
        $('#due_date').val(formatted);

    }

var spPswd = '{{$settings->special_pswd}}';
function pswdOkSubmit() {
	//var con = confirm('Are you sure want to remove the data from the selected sections?');
	//if(con) {
		//let pswd = prompt('Please enter password'); 
        let pswd=$('#confirm_data #pwd').val();
		if(pswd==spPswd) { 
			//document.frmData.action="{{ url('data_remove/cleardb_custom') }}";
			document.getElementById("frmSalesInvoice").submit();
		} else {
			alert('Invalid password!');
			return false;
		}
	//}
}

$(function() {	

	$(document).on('click', '.saveBtn', function(e) { 
		   e.preventDefault(); 
         // $('#frmSalesInvoice').submit();
		 if(is_belowCost) {
			$('#bcost_modal').modal('show');
		 } else
			document.getElementById("frmSalesInvoice").submit();
    });
	//var flg = false;
	/*$(document).on('submit','#frmSalesInvoice',function(e){
		
		//console.log('test'); alert('ssg');
		if(flg==false) {
			alert('error');
			return false;
		}
		e.preventDefault();
	});*/

	$(document).on('click', '#crmBtn', function(e) { 
		   e.preventDefault();
           $('.crm_info').toggle();
    });
	
	$(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
	var rowNum = 1;
	 
	$('.lpo_date1').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			minDate: new Date('{{$settings->from_date}}'),
			maxDate: new Date('{{$settings->to_date}}'),
			autoclose: 1
	});
	
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	  
	  $(document).on('click', '#ocadd', function(e) { 
		   e.preventDefault();
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
	 
	 //description info view section
	$(document).on('click', '.desc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   $('#descdivPrntItm_'+curNum).toggle();
    });
	
		 
	$(document).on('click', '.btn-add-item', function(e)  { 
        rowNum++; //console.log(rowNum);
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.line-unit')).attr('id', 'itmunt_' + rowNum); //input[type='select'] line-unit
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

			
            newEntry.find($('.dimnInfodivPrntItm')).attr('id', 'dimnInfodivPrntItm_' + rowNum);
            newEntry.find($('.dimn-item-data')).attr('id', 'dimnitemData_' + rowNum);
            newEntry.find($('.dimn-view')).attr('id', 'itmInfo_' + rowNum);

            newEntry.find($('input[name="iloc[]"]')).attr('id', 'iloc_' + rowNum); //NOV24
            @if($formdata['location_item']==1)
			newEntry.find( $('#frmSalesInvoice').bootstrapValidator('addField',"iloc[]") ); //NOV24
			@endif
            
            //MAY25..
			newEntry.find($('.btn-remove-item')).attr('data-id', 'rem_' + rowNum);
			newEntry.find($('.batch-add')).attr('id', 'btnBth_' + rowNum);
			newEntry.find($('input[name="batchNos[]"]')).attr('id', 'bthSelIds_' + rowNum);
			newEntry.find($('input[name="qtyBatchs[]"]')).attr('id', 'bthSelQty_' + rowNum);
			newEntry.find($('.addBatchBtn')).attr('id', 'batchdiv_' + rowNum);
			$('#itmqty_'+rowNum).attr('readonly', false);
			//...
			
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
			
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			
			var indx = parseInt(rowNum - 1);
			newEntry.find($('.loc-qty')).attr('name', 'locqty['+indx+'][]');
			newEntry.find($('.loc-id')).attr('name', 'locid['+indx+'][]');
			
			newEntry.find($('input[name="costavg[]"]')).attr('id', 'costavg_' + rowNum);
			newEntry.find($('input[name="purcost[]"]')).attr('id', 'purcost_' + rowNum);
			newEntry.find($('input[name="curqty[]"]')).attr('id', 'cqty_' + rowNum);
			
			//VAT CHNG
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			
			newEntry.find($('.pur-his')).attr('id', 'purhisItm_' + rowNum);
			newEntry.find($('.sales-his')).attr('id', 'saleshisItm_' + rowNum);
			newEntry.find($('.cust-sales-his')).attr('id', 'custsaleshisItm_' + rowNum);
			newEntry.find($('.asm-itm')).attr('id', 'assemblyItm_' + rowNum);
			newEntry.find($('.view-asm-itm')).attr('id', 'assemblyItmVw_' + rowNum);
			newEntry.find($('input[name="assembly_items[]"]')).attr('id', 'asmid_' + rowNum); 
			newEntry.find($('input[name="assembly_items_qty[]"]')).attr('id', 'asmqt_' + rowNum); 

			newEntry.find($('input[name="conloc_id[]"]')).attr('id', 'conlocid_' + rowNum); 
			newEntry.find($('input[name="conloc_qty[]"]')).attr('id', 'conlocqty_' + rowNum); 
			
			newEntry.find($('input[name="per[]"]')).attr('id', 'per_' + rowNum); //CHNG SEP 17
			newEntry.find($('input[name="perdesc[]"]')).attr('id', 'perdesc_' + rowNum); //CHNG SEP 17
			
			$('#packing_'+rowNum).val(1);
			
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
						$('#itmcod_'+rowNum).val( ui.item.code );
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
										$('#hidunit_'+rowNum).val(value.unit_name);
							});
						});
					},
					minLength: 1,
				});
					
					var src2 = "{{ url('itemmaster/ajax_search2/') }}";
					newEntry.find($('input[name="item_name[]"]')).autocomplete({
					source: function(request, response) {
						$.ajax({
							url: src2+'/C',
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
						$('#itmcod_'+rowNum).val( ui.item.code );
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
				
				/*newEntry.find($('input[name="item_name[]"]')).autocomplete({
					
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
				});*/
			
			$('input,select,checkbox').keydown( function(e) { 
				var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
				if(key == 13) {
					e.preventDefault();
					var inputs = $(this).closest('form').find(':input:visible');
					inputs.eq( inputs.index(this)+ 1 ).focus();
				}
			});
			
			
			/* controlForm.find('.btn-add-item:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-item').addClass('btn-remove-item')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> '); */
			
			//ROWCHNG
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
			$('#vatocamt_' + ocrowNum).val(5);
			controlForm.find('.btn-add-oc:not(:last)')
            .removeClass('btn-default').addClass('btn-danger')
            .removeClass('btn-add-oc').addClass('btn-remove-oc')
            .html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> ');
    }).on('click', '.btn-remove-oc', function(e)
    { 
		$(this).parents('.OCdivChld:first').remove();
		
		var res = getOtherCost();
		if(res) 
			getNetTotal();
		
		e.preventDefault();
		return false;
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
			$('#itmcod_1').val( ui.item.code );
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
	
	
	$(document).on('click', '.btn-add-desc', function(e) 
    { 
        e.preventDefault();

        var controlForm = $(this).parents('.controls .descdivPrntItm:last'),
            currentEntry = $(this).parents('.descdivChldItm:last'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('input').val('');
    }).on('click', '.btn-remove-desc', function(e)
    { 
		$(this).parents('.descdivChldItm:first').remove();

		e.preventDefault();
		return false;
	});
	
	
	var custurl;
	$('#customer_name').click(function() { 
		if($('#department_id').length) { console.log('dept');
			custurl = "{{ url('sales_order/customer_data/') }}"+'/'+$('#department_id option:selected').val();
		} else
			custurl = "{{ url('sales_order/customer_data/') }}";
		$('#customer_modal #customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
			/* if( $('#customerInfo').is(":hidden") ) 
				$('#customerInfo').toggle(); */
		});
	});
	
	var newcusturl = "{{ url('sales_order/newcustomer_data/') }}";
	$('#customername').click(function() { 
		if($('#department_id').length)
			newcusturl = newcusturl+'/'+$('#department_id option:selected').val();
		$('#newcustomerData').load(newcusturl, function(result) {
			$('#myModal').modal({show:true});
			
		});
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
	
	$('#duedays').on('blur', function(e){
	    var days=$('#duedays').val()
	    calculateDueDate(parseInt(days));
   });

	$('#sales_type').on('change', function(e){
		if(e.target.value=='ltol') {
			if( $('#saleloc').is(":hidden") )
					$('#saleloc').toggle();
		} else {
			if( $('#saleloc').is(":visible") )
					$('#saleloc').toggle();
		}
	});
	
	$('#sales_location').on('change', function(e){
		var loc_id = e.target.value;
		if(loc_id=='') {
			alert('Please select a location!');
			return false;
		}
		$.get("{{ url('sales_invoice/getsaleloc/') }}/" + loc_id, function(data) {
			if(data) {
				$('#customer_name').val(data.customer_name);
				$('#customer_id').val(data.account_id);
				$('#dr_account_id').val(data.account_id);
			} else {
				$('#customer_name').val('');
				$('#customer_id').val('');
				$('#dr_account_id').val('');

			}
		});
	});
	
	$(document).on('keyup', '.oc-line', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var res = getOtherCost();
		if(res) 
			getNetTotal();
	});
	
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', 'input[name="dr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	$(document).on('click', '#account_data .custRow', function(e) { 
		var num = $('#account_data #num').val();
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	$(document).on('click', '.accountRow', function(e) { 
		var num = $('#account_data #num').val(); console.log(num);
		$('#dracnt_'+num).val( $(this).attr("data-name") );
		$('#dracntid_'+num).val( $(this).attr("data-id") );
	});
	
	var acurlall = "{{ url('account_master/get_account_all/') }}";
	$(document).on('click', 'input[name="cr_acnt[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#paccount_data').load(acurlall+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus();
		});
	});
	
	$(document).on('click', '#paccount_data .custRow', function(e) { 
		var num = $('#paccount_data #anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
		
	});
	
	$(document).on('click', '.accountRowall', function(e) { 
		var num = $('#anum').val();
		$('#cracnt_'+num).val( $(this).attr("data-name") );
		$('#cracntid_'+num).val( $(this).attr("data-id") );
	});
	
	$('#department_id').on('change', function(e){ 
		var dept_id = e.target.value; 
		dptTxt = $( "#department_id option:selected" ).text();
		deptid = $( "#department_id option:selected" ).val();
		$.get("{{ url('sales_invoice/getdeptvoucher/') }}/" + dept_id, function(data) { 
			$('#voucher_no').val(data[0].voucher_no);
			$('#voucher_id').find('option').remove().end();
				$.each(data, function(key, value) {  
					
					$('#voucher_id').find('option').end()
							.append($("<option></option>")
							.attr("value",value.voucher_id)
							.text(value.voucher_name)); 
				});
			
			
			$('#curno').val(data[0].voucher_no); //CHNG
			$('#is_cash').val(data[0].cash_voucher); //CHNG
			
			if(data[0].account_id!=null && data[0].account_name!=null) {
				$('#sales_account').val(data[0].account_id+'-'+data[0].account_name);
				$('#cr_account_id').val(data[0].id);
			} else {
				$('#sales_account').val('');
				$('#cr_account_id').val('');
			}
			
			if(data[0].cash_voucher==1) {
				if( $('#newcustomerInfo').is(":hidden") )
					$('#newcustomerInfo').toggle();
			
				if( $('#customerInfo').is(":visible") )
					$('#customerInfo').toggle();
				$('#customer_name').val(data[0].default_account);
				$('#customer_id').val(data[0].cash_account);
				$('#dr_account_id').val(data[0].cash_account);
				$('#customer_name').removeAttr("data-toggle");
			} else {
				if( $('#customerInfo').is(":hidden") ) 
					$('#customerInfo').toggle();
			
				if( $('#newcustomerInfo').is(":visible") )
					$('#newcustomerInfo').toggle();
				$('#customer_name').val('');
				$('#customer_id').val('');
				$('#dr_account_id').val('');
				$('#customer_name').attr("data-toggle", "modal");
			}
			
		});
	});
	
	
	$(document).on('blur', '#voucher_no', function(e) {  
		if(parseInt($(this).val()) > parseInt($('#curno').val())) {
			alert('Voucher no is greater than current range!');
			$('#voucher_no').val('');
		}
	});

	$('#voucher_id').on('change', function(e){ 
		var vchr_id = e.target.value; 
		localStorage.setItem("vcId", vchr_id);
		$.get("{{ url('sales_invoice/getvoucher/') }}/" + vchr_id, function(data) { 
			//$('#voucher_no').val(data.voucher_no);
			$('#voucher_no').val('');
			$('#voucher_no').attr('placeholder', data.voucher_no);
			$('#curno').val(data.voucher_no); //CHNG
			$('#is_cash').val(data.cash_voucher); //CHNG
			if(data.account_id!=null && data.account_name!=null) {
				$('#sales_account').val(data.account_id+'-'+data.account_name);
				$('#cr_account_id').val(data.id);

				localStorage.setItem("sAc", data.account_id+'-'+data.account_name);
				localStorage.setItem("crId", data.id);
			} else {
				$('#sales_account').val('');
				$('#cr_account_id').val('');
				localStorage.setItem("sAc", '');
				localStorage.setItem("crId", '');
			}
			
			if(data.cash_voucher==1) {
				if( $('#newcustomerInfo').is(":hidden") )
					$('#newcustomerInfo').toggle();
			
				if( $('#customerInfo').is(":visible") )
					$('#customerInfo').toggle();
				$('#customer_name').val(data.default_account);
				$('#customer_id').val(data.cash_account);
				$('#dr_account_id').val(data.cash_account);
				$('#customer_name').removeAttr("data-toggle");

				localStorage.setItem("cName", data.default_account);
				localStorage.setItem("cId", data.cash_account);
				localStorage.setItem("drId", data.cash_account);
				localStorage.setItem("isCs", 1);
			} 
			else {
				if( $('#customerInfo').is(":hidden") ) 
					$('#customerInfo').toggle();
			
				if( $('#newcustomerInfo').is(":visible") )
					$('#newcustomerInfo').toggle();
				$('#customer_name').val('');
				$('#customer_id').val('');
				$('#dr_account_id').val('');
				$('#customer_name').attr("data-toggle", "modal");

				localStorage.setItem("cName", '');
				localStorage.setItem("cId", '');
				localStorage.setItem("drId", '');
				localStorage.setItem("isCs", 0);
			}
			
		});
		
	});
	
	/* var custurl2 = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() { 
		if($('#department_id').length)
			custurl2 = custurl2+'/'+$('#department_id option:selected').val();
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		}); 
		
	}); */
	
	$(document).on('click', '#customerData .custRow', function(e) { //console.log($(this).attr("data-trnno"));
	
		//if( $('#newcustomerInfo').is(":hidden") ) { F6
			$('#customer_name').val($(this).attr("data-name"));
			$('#customer_id').val($(this).attr("data-id"));
			$('#dr_account_id').val($(this).attr("data-id"));
			$('#cr_balance').val($(this).attr("data-clbalance"));
			$('#cr_limit').val($(this).attr("data-crlimit"));
			$('#pdc').val($(this).attr("data-pdc"));

			if($(this).attr("data-new")==true)
				alert('CRM details are required!')
		//}
		
		    $('#frmSalesInvoice').bootstrapValidator('revalidateField', 'customer_name');//NOV24
		    
		/* if( $('#newcustomerInfo').is(":visible") ) {
			$('#customername').val($(this).attr("data-name"));
		} */
		
		var group_id = $(this).attr("data-groupid");
		var trnno = $(this).attr("data-trnno");
		/* if(group_id==17) { //cash customer.... 
			if( $('#newcustomerInfo').is(":hidden") )
				$('#newcustomerInfo').toggle();
			
			if( $('#customerInfo').is(":visible") )
				$('#customerInfo').toggle();
		} else {
			if( $('#customerInfo').is(":hidden") ) 
				$('#customerInfo').toggle();
			
			if( $('#newcustomerInfo').is(":visible") )
				$('#newcustomerInfo').toggle();
		} */
		
		$('#duedays').val($(this).attr("data-duedays"));
		
		if(	$('#duedays').val() >0){
		    var days=$('#duedays').val();
	     calculateDueDate(parseInt(days));
		}
		
		//JAN20
		if($(this).attr("data-term")!='')
			$("#terms_id option[value="+$(this).attr("data-term")+"]").attr('selected', 'selected'); 
		else
			$('#terms_id').prop('selectedIndex', 0); //$('#terms_id option')[0].selected = true;
		
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
	
	$(document).on('click', '.newcustRow', function(e) { //console.log($(this).attr("data-trnno"));
		$('#customername').val($(this).attr("data-name"));
		$('#customer_trn').val($(this).attr("data-trnno"));
						
		e.preventDefault();
	});
	
	var supurl = "{{ url('sales_invoice/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//new change............
	var itmurl = "{{ url('itemmaster/item_data/') }}";
	$(document).on('click', 'input[name="item_code[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ //.modal-body item
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change............ DEC2
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#item_modal #num').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
		$('#costavg_'+num).val( $(this).attr("data-costavg") );
		$('#purcost_'+num).val( $(this).attr("data-purcost") );
		$('#cqty_'+num).val( $(this).attr("data-cqty") );
		
		$('#itmwit_'+num).val( $(this).attr("data-wit") );
		$('#itmlnt_'+num).val( $(this).attr("data-lnt") );

		if($('.export').is(":checked") ) {  
			$('#vatdiv_'+num).val( '0%' );
			$('#vat_'+num).val( 0 );
			$('#itmcst_'+num).val( ($(this).attr("data-cost")==0)?'':$(this).attr("data-cost") );
		} else {
			$('#vatdiv_'+num).val( $(this).attr("data-vat")+'%' );
			$('#vat_'+num).val( $(this).attr("data-vat") );
			$('#itmcst_'+num).val( ($(this).attr("data-cost")==0)?'':$(this).attr("data-cost") );
			srvat = $(this).attr("data-vat");
		}
		
		//MAY25
	    if($(this).attr("data-batch-req")==1) {
	        $('#itmqty_'+num).attr('readonly', true);
	        $('#batchdiv_'+num).show();
	        $('#frmSalesInvoice').bootstrapValidator('addField', 'batchNos[]');
	        $('#frmSalesInvoice').data('bootstrapValidator')
                .addField('batchNos[]', {
                    validators: {
                        notEmpty: {
                            message: 'Batch no is required!'
                        }
                    }
            });
	    } else
	        $('#batchdiv_'+num).hide();
	        
		//AG24
		//if( $(this).attr("data-type")==1) {
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
			
		/* TEMPORARLY DISABLED ... } else {
			$.get("{{ url('itemmaster/getunit/') }}", function(data) {
				$('#itmunt_'+num).find('option').remove().end();
				$.each(data, function(key, value) {   
					$('#itmunt_'+num).find('option').end()
					 .append($("<option></option>")
								.attr("value",value.id)
								.text(value.unit_name)); 
				});
			});
		} */
		
		//getAutoPrice(num);
		$('#itmcst_'+num).focus();
	});

	$(document).on('blur','.line-mpquantity', function() {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var qtywt = this.value * $('#itmwit_'+curNum).val() * $('#itmlnt_'+curNum).val();
		$('#itmqty_'+curNum).val(qtywt);
	})
	
	var hdrurl = "{{ url('header_footer/header_data/') }}";
	$('#headermsg').click(function() {
		$('#hdr').load(hdrurl, function(result) {
			$('#hdrModal').modal({show:true});
		});
	});
	$(document).on('click', '.hrdcls', function(e) {
		$('#headermsg').val($(this).attr("data-name"));
		$('#header_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var ftrurl = "{{ url('header_footer/footer_data/') }}";
	$('#footermsg').click(function() {
		$('#ftr').load(ftrurl, function(result) {
			$('#ftrModal').modal({show:true});
		});
	});
	$(document).on('click', '.ftrcls', function(e) {
		$('#footermsg').val($(this).attr("data-name"));
		$('#footer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	//updated mar 25...
	$(document).on('keyup', '.line-quantity', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 || parseFloat(this.value) < 0) {
			
			var con = confirm('Item quantity is zero. Do you want to continue with zero quantity?');
			if(con==false){
				$('#itmqty_'+curNum).val('');
				$('#itmqty_'+curNum).focus();
				return false;
			} else
				return true;
			
			/* alert('Item quantity is invalid.');
			$('#itmqty_'+curNum).val('');
			$('#itmqty_'+curNum).focus();
			return false; */
		} else {
			var itmid = $('#itmid_'+curNum).val();
			$.get("{{ url('itemmaster/checkqty/') }}/" + itmid, function(data) {  
				data = JSON.parse(data); console.log(data.cur_quantity+' '+data.min_quantity);
				var cur_quantity = parseFloat(data.cur_quantity);
				var min_quantity = parseFloat(data.min_quantity);
				<?php if($settings->item_quantity==1) { ?>
				@if(auth()->user()->can('-qty-sale'))
					console.log('Minus Qty Sale');
				@else
				if(cur_quantity == 0 || cur_quantity < 0) {
					alert('Item is out of stock!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
				} else if(cur_quantity < parseFloat($('#itmqty_'+curNum).val())) {
					alert('Item is out of stock!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
						
				} else if((min_quantity == cur_quantity) || (min_quantity > cur_quantity)) {
					alert('Item quantity is reached on minimun quantity!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
				}
				@endif
				<?php } ?>
			});
		}
	});
	
	$(document).on('blur', '.line-quantity', function(e) {
		
		var res = this.id.split('_');
		var curNum = res[1];
		if($('#itmcst_'+curNum).val()=='')
			var isPrice = getAutoPrice(curNum);
		//var isPrice = getAutoPrice(curNum);
		
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

		
		@if(auth()->user()->can('bcost-sale'))
			console.log('Below Cost');
		@else
			//MAR18
			var rate = 1;
			if( $('#is_fc').is(":checked") ) { 
				var rate = parseFloat($('#currency_rate').val());
			}//..
			var salecost = parseFloat( $('#costavg_'+curNum).val() ); 
			if(this.value!='' && this.value!=0 && salecost > (this.value * rate)) { //MAR18
				/*alert('This item price is lower than cost avg.');
				$('#itmcst_'+curNum).val('');
				$('#itmcst_'+curNum).focus();*/
				is_belowCost = true;
			}
		@endif
	});
	
	var ordhisurl = "{{ url('sales_invoice/order_history/') }}";
	$('.order-history').click(function() {
		var cus_id = $('#customer_id').val();
		$('#historyData').load(ordhisurl+'/'+cus_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
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
			$.get("{{ url('itemmaster/get_vat/') }}/" + unit_id+"/"+item_id, function(data) { //console.log(data);
				//VAT CHNG
				$('#vat_'+curNum).val(data.vat);
				$('#vatdiv_'+curNum).val(data.vat+'%');
				srvat = data.vat;
				$('#packing_'+curNum).val(data.packing);
				$('#itmcst_'+curNum).val(data.price);
				getNetTotal();
			});
		}
		
	});
	
	
	$('#document_type').on('change', function(e){
		var dpt_id = ($('#department_id').length)?$('#department_id option:selected').val():'';
		var vchr_id = $('#voucher_id').val();
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		var sales_ac = $('#sales_account').val();
		var ac_mstr = $('#dr_account_id').val();
		$.ajax({
			url: "{{ url('sales_invoice/set_session/') }}",
			type: 'post',
			data: {'vchr_no':vchr_no,'vchr_id':vchr_id,'ref_no':ref_no,'vchr_dt':vchr_dt,'lpo_dt':lpo_dt,'sales_ac':sales_ac,'ac_mstr':ac_mstr,'dpt_id':dpt_id},
			success: function(data) { //console.log(data);
			}
		}) 
		
	});
	
	//CHNG 
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	$('.inputsa').on('click', function(e) {
		$('#sales_account').attr("onClick", "javascript:getAccount(this)");
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
	
	$(document).on('keyup', '.round-off', function(e) { 
		calculateRoundoff();
	});
	
	
	$(document).on('click', '.loc-info', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];  
	   var item_id = $('#itmid_'+curNum).val();
	   if(item_id!='') {
		   var locUrl = "{{ url('itemmaster/get_locinfo/') }}/"+item_id+"/"+curNum
		   if ($('#locData_'+curNum).is(':empty')){
    		   $('#locData_'+curNum).load(locUrl, function(result) {
    			  $('#myModal').modal({show:true});
    		   });
    		   $('#locPrntItm_'+curNum).toggle();
		   } else
		       $('#locPrntItm_'+curNum).toggle(); 
	   }
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

	$(document).on('click', '.add-loc-qty', function(e)  { 
		var locid = []; var locqty = [];
		$("input[name='lcid[]']:checked").each(function(){
			locid.push($(this).val());
			locqty.push( $('#clqty_'+$(this).val().toString()).val() );
			
		});
		//console.log('v'+locid+' q'+locqty);
		 var nm = $('#numasm').val(); 
		 $('#conlocid_'+nm).val(locid);  //$('#clocid').val()
		 $('#conlocqty_'+nm).val(locqty);  //$('#clocqty').val()
		 
		 let qtyout = 0;
		 var qtyarr = $('#conlocqty_'+nm).val().split(',');
		 $.each(qtyarr , function(index, val) { 
		   qtyout = qtyout + parseFloat(val);
		});
		$('#itmqty_'+nm).val(qtyout);
	});
	
	/*$(document).on('click', '.cnloc-info', function(e) { 
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
    });*/

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
	
	$('input,select,checkbox').keydown( function(e) { 
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
	
		var src2 = "{{ url('itemmaster/ajax_search2/') }}";
	$('input[name="item_name[]"]').autocomplete({
		
        source: function(request, response) {
            $.ajax({
                url: src2+'/C',
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
			$('#itmcod_1').val( ui.item.code );
			$('#itmid_1').val( itm_id );
			
			//$("#itmdes_1").val(ui.item.name);
			//$('#itmcod_1').val( ui.item.value );
			
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
	
	/* $('input[name="item_name[]"]').autocomplete({
		
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
							$('#hidunit_1').val(value.unit_name); 
				});
			});
		},
        minLength: 1,
		
    }); */
	
	$(document).on('blur', 'input[name="item_code[]"]', function(e) { 
		var res = this.id.split('_');
		var curNum = res[1]; 
		var code = this.value;
		//##COMMANTED ON DEC 31  2021
		/* if(code!='') {
			$.get("{{ url('itemmaster/item_load/') }}/" + code, function(data) {
				$("#itmdes_"+curNum).val(data.description);
				$('#itmid_'+curNum).val( data.id );
				$('#vat_'+curNum).val(data.vat)
				$('#vatdiv_'+curNum).val(data.vat+'%');
				$('#costavg_'+curNum).val(data.cost_avg);
				$('#purcost_'+curNum).val(data.pur_cost);
				
				
			});
		} */
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
	
	/*$('input[name="item_name[]"]').autocomplete({
		
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
								$('#hidunit_1').val(value.unit_name);
					});
				});
			}
		},
        minLength: 3,
    });*/
	
	
	///Customer search...
	$(document).on('focus', '#customer_trn', function(e) { 
		var name = $('#customername').val();
		$.get("{{ url('sales_invoice/get_trnno/') }}/" + name, function(data) { //console.log(data);
			$('#customer_phone').val(data.customer_phone);
			$('#customer_trn').val(data.customer_trn);
		});
	});
	
	///Customer sales history...
	
	$('.cust-history').click(function() {
		var hisurl = "{{ url('sales_invoice/cust_history/') }}";
		var cus_name = $('#customername').val();
		var phone = $('#customer_phone').val();
		$('#custhistoryData').load(hisurl+'/'+cus_name, function(result) {
			$('#myModal').modal({show:true});
		});
		
		/* var ordhisurl = "{{ url('sales_invoice/order_history/') }}";
		var cus_id = $('#customer_id').val();
		$('#custhistoryData').load(ordhisurl+'/'+cus_id, function(result) {
			$('#myModal').modal({show:true});
		}); */
		
		
	});
	
	$('.cust-history-phone').click(function() {
		var phisurl = "{{ url('sales_invoice/cust_history_phone/') }}";
		var phone = $('#customer_phone').val();
		$('#custphonehistoryData').load(phisurl+'/'+phone, function(result) {
			$('#myModal').modal({show:true});
		});
		
	});
	
	///Customer search...
	var srccust = "{{ url('sales_invoice/ajax_customer/') }}";
	$('#customername').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: srccust,
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
			$("#customer_trn").val(ui.item.trnno);
			$('#customer_phone').val( ui.item.phone );
		},
        minLength: 2,
    });
	
	$(document).on('blur', '.vatdiv', function(e) { 
		getNetTotal();
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
	
	
	$(document).on('keyup', '.line-cost', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		if( parseFloat(this.value) == 0 ) {
			
			var con = confirm('Item cost is zero. Do you want to continue with zero cost?');
			if(con==false){
				$('#itmcst_'+curNum).val('');
				$('#itmcst_'+curNum).focus();
				return false;
			} else
				return true;
		}
	});
	
	///Customer phone no search...
	/* var srccust = "{{ url('sales_invoice/ajax_customer/') }}";
	$('#customer_phone').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: srccust,
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
			$("#customer_trn").val(ui.item.trnno);
			$('#customer_phone').val( ui.item.phone );
		},
        minLength: 2,
    }); */
	
	$(document).on('keyup', '#advance', function(e) {
		console.log(this.value);
		var netamt = parseFloat( ($('#net_amount').val()=='') ? 0 : $('#net_amount').val() );
		var bal = netamt - this.value;
		$('#balance').val(bal.toFixed(2));
	});
	
	$(document).on('keyup', '#less_amount, #less_amount2, #less_amount3, #previnv_amount', function(e) { 
		getNetTotal();
	});
	
	$(document).on('blur', '.perc', function(e) {
		getNetTotal();
	});
	
	//CHNG SEP 17
	$(document).on('blur', '.perc', function(e) {
		getNetTotal();
	});
	
	$(document).on('blur', '#cheque_no', function(e) {
		var chqno = this.value;
		var bank = $('#bank_id option:selected').val();
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno+'&bank_id='+bank,
			success: function(data) { 
				if(data=='') {
					alert('Cheque no is duplicate!');
					$('#cheque_no').val('');
				}
			}
		})
	});
	
	$(document).on('click', '.cust-sales-his', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#itmid_'+curNum).val(); 
	    var cust_id = $('#customer_id').val(); 
	   if(item_id!='') {
		   var infoUrl = "{{ url('itemmaster/get_custsales_info/') }}/"+item_id+"/"+cust_id; 
		   $('#custsaleshisData').load(infoUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
    });
	
	
	var asmitmurl = "{{ url('itemmaster/asmitem_data/') }}";
	$(document).on('click', '.asm-itm', function(e) { 
	   e.preventDefault();
	    var res = this.id.split('_');
		var curNum = res[1]; 
		$('#asmblyitmData').load(asmitmurl+'/'+curNum, function(result){
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
    });
	
	
	$(document).on('click', '.add-asm-item', function(e)  { 
		 var nm = $('#numasm').val();
		 $('#asmid_'+nm).val( ($('#asmid_'+nm).val()=='')?$('#asit').val():$('#asmid_'+nm).val()+','+$('#asit').val());
		 $('#asmqt_'+nm).val( ($('#asmqt_'+nm).val()=='')?$('#asqt').val():$('#asmqt_'+nm).val()+','+$('#asqt').val());
		 
	});
	
	$(document).on('click', '.view-asm-itm', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#asmid_'+curNum).val(); 
	   var item_qty = $('#asmqt_'+curNum).val();  
	   if(item_id!='') {
		   var vwUrl = "{{ url('itemmaster/get_assembly_items/') }}/"+item_id+'/'+item_qty+'/'+curNum; 
		   $('#asmblyitmDataVw').load(vwUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   } else {
	       $('#asmblyitmDataVw').html('');
	   }
    });
	
/*	$(document).on('click', '.view-asm-itm', function(e) { 
	   e.preventDefault();
	   var res = this.id.split('_');
	   var curNum = res[1];
	   var item_id = $('#asmid_'+curNum).val(); 
	   var item_qty = $('#asmqt_'+curNum).val(); 
	   if(item_id!='') {
		   var vwUrl = "{{ url('itemmaster/get_assembly_items/') }}/"+item_id+'/'+item_qty+'/'+curNum; 
		   $('#asmblyitmDataVw').load(vwUrl, function(result) {
			  $('#myModal').modal({show:true});
		   });
	   }
    });*/
	
	$(document).on('click', '.funRemove', function(e) { 
		var n = $(this).data("no");
		var v = $(this).data("id").toString();
		var idar = $('#asmid_'+n).val().split(",");
		var qtar = $('#asmqt_'+n).val().split(",");
		var a = idar.indexOf(v); 
		removeAr(idar, v)
		qtar.splice(a,1);
		//console.log(idar); 
		$('#asmid_'+n).val(idar);
		$('#asmqt_'+n).val(qtar);
		$(this).parent().parent().remove();
		
	});
	
	$(document).on('keyup', '.vno', function(e) { 
		$('input[name="vehicle_no"]').val($(this).val());
	});
	
	$(document).on('keyup', '.cpno', function(e) { 
		$('input[name="customer_phone"]').val($(this).val());
	});
	
	$(document).on('keyup', '.km', function(e) { 
		$('input[name="kilometer"]').val($(this).val());
	});
	
	
	$(document).on('keyup', '.num :input[type="number"]', function(e) {
		var itQty = 0; var curNum = $(this).data('id');
		
		//NOV24
		if(this.value > $(this).data('qty')) {
			alert('Quantity out of stock in this location!');
			this.value='';//$(this).find($('.loc-qty-'+curNum).val(''));
			return false;
		}
		var itmid = $('#itmid_'+curNum).val();
		$.get("{{ url('itemmaster/checkqty/') }}/" + itmid, function(data) {  
			data = JSON.parse(data); 
			var cur_quantity = parseFloat(data.cur_quantity);
			var min_quantity = parseFloat(data.min_quantity);
			@if(auth()->user()->can('-qty-sale'))
				console.log('Minus Qty Sale');
			@else
				if(cur_quantity == 0 || cur_quantity < 0) {
					alert('Item is out of stock!');
					$('#itmqty_'+curNum).val('');
					$(this).find($('.loc-qty-'+curNum).val(''));
					$('#itmqty_'+curNum).focus();
					return false;
				} else if((min_quantity == cur_quantity) || (min_quantity > cur_quantity)) {
					alert('Item quantity is reached on minimun quantity!');
					$('#itmqty_'+curNum).val('');
					$('#itmqty_'+curNum).focus();
					return false;
				}
			@endif
		});
		
		$('.loc-qty-'+curNum).each(function() { 
			itQty += parseFloat( (this.value=='')?0:this.value );
		});
		$('#itmqty_'+curNum).val(itQty);
		
		$('#iloc_'+curNum).val((itQty>0)?itQty:''); //NOV24
		var isPrice = getAutoPrice(curNum);
		var res = getLineTotal(curNum);
		if(res) {
			var call = getOtherCost();
			if(call)
				getNetTotal();
		}
		
		$('#frmSalesInvoice').bootstrapValidator('revalidateField', 'quantity[]');//NOV24
	});
	
	
	$(document).on('change', '.voucher_type', function(e){ //DEC
		var res = this.id.split('_');
	    var curNum = res[1];
		var vchr = e.target.value; 
		var vchr_id = $('#rvvoucher_'+curNum).val();
		
		
    		$.get("{{ url('customer_receipt/getvoucher/') }}/" + vchr_id+'/'+vchr, function(data) {
    			$('#rvvoucherno_'+curNum).val(data.voucher_no);
    			if(data.id!=null && data.account_name!=null) {
    				$('#rvdraccount_'+curNum).val(data.account_name);
    				$('#rvdraccountid_'+curNum).val(data.id);
    			} else {
    				$('#rvdraccount_'+curNum).val('');
    				$('#rvdaccountid_'+curNum).val('');
    			}
    		});
		
		if(vchr=='PDCR') { //vchr=='BANK' || 
			$('#chequeno_'+curNum).show();$('#chequedate_'+curNum).show();$('#bankid_'+curNum).show();$('.lbban_'+curNum).show();$('.lbchq_'+curNum).show();$('.lbchqd_'+curNum).show();
			if(vchr=='PDCR') {
				$('[name="cheque_no[]"]').attr("required",true);
			} else {
				$('[name="cheque_no[]"]').removeAttr("required");
			}
		} else {
			$('#chequeno_'+curNum).hide();$('#chequedate_'+curNum).hide();$('#bankid_'+curNum).hide();$('.lbban_'+curNum).hide();$('.lbchq_'+curNum).hide();$('.lbchqd_'+curNum).hide();
			$('[name="cheque_no[]"]').removeAttr("required");
		}
	});
	
	$(document).on('blur', 'input[name="rv_amount[]"]', function(e) {
		var res = this.id.split('_');
	    var curNum = res[1];
		
		var amount = getRvTotal();
		var netamount = parseFloat( ($('#net_amount').val()=='') ? 0 : $('#net_amount').val() );
		if(netamount < amount) {
			alert('Amount should not greater than bill amount.');
			$('#rvamount_'+curNum).val('');
		}
	});
	
	$('input[name="cheque_date[]"]').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
	var rvNum = 1;
	$(document).on('click', '.btn-add-rv', function(e)  { //DEC
		rvNum++;
		e.preventDefault();
        var controlForm = $('.controls .RVdivPrnt'),
            currentEntry = $(this).parents('.RVdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="rv_voucher[]"]')).attr('id', 'rvvoucher_' + rvNum);
			newEntry.find($('input[name="rv_voucher_no[]"]')).attr('id', 'rvvoucherno_' + rvNum);
			newEntry.find($('input[name="rv_dr_account[]"]')).attr('id', 'rvdraccount_' + rvNum);
			newEntry.find($('input[name="rv_dr_account_id[]"]')).attr('id', 'rvdraccountid_' + rvNum);
			newEntry.find($('input[name="rv_amount[]"]')).attr('id', 'rvamount_' + rvNum);
			newEntry.find($('input[name="cheque_no[]"]')).attr('id', 'chequeno_' + rvNum);
			newEntry.find($('input[name="cheque_date[]"]')).attr('id', 'chequedate_' + rvNum);
			newEntry.find($('.voucher_type')).attr('id', 'vouchertype_' + rvNum);
			newEntry.find($('.bank')).attr('id', 'bankid_' + rvNum);
			newEntry.find($('.lbban_'+(rvNum-1))).attr('class', 'small lbban_' + rvNum);
			newEntry.find($('.lbchq_'+(rvNum-1))).attr('class', 'small lbchq_' + rvNum);
			newEntry.find($('.lbchqd_'+(rvNum-1))).attr('class', 'small lbchqd_' + rvNum);
			
			$('#chequeno_'+rvNum).hide();$('#chequedate_'+rvNum).hide();$('#bankid_'+rvNum).hide();
			$('.lbban_'+rvNum).hide();$('.lbchq_'+rvNum).hide();$('.lbchqd_'+rvNum).hide();
			
			$('#rvdraccount_'+rvNum).val( $('#rvdraccount').val() );
			$('#rvdraccountid_'+rvNum).val( $('#rvdraccountid').val() );
			$('#rvamount_'+rvNum).val('');
			$('#chequeno_'+rvNum).val('');
			$('#chequedate_'+rvNum).val('');
			controlForm.find('.btn-add-rv:not(:last)').hide();
			controlForm.find('.btn-remove-rv').show();
			$('input[name="cheque_date[]"]').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
			
    }).on('click', '.btn-remove-rv', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#id_'+curNum).val():remitem+','+$('#id_'+curNum).val();
		$('#remitem').val(ids);
		$(this).parents('.RVdivChld:first').remove();
		
		//ROWCHNG
		$('.RVdivPrnt').find('.RVdivChld:last').find('.btn-add-rv').show();
		if ( $('.RVdivPrnt').children().length == 1 ) {
			$('.RVdivPrnt').find('.btn-remove-rv').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	$(document).on('blur', 'input[name="cheque_no[]"]', function(e) {
		var chqno = this.value;
		var res = this.id.split('_');
		var curNum = res[1]; //console.log(res[0]);
		var bank = $('#bankid_'+curNum+' option:selected').val();
		
		$.ajax({
			url: "{{ url('account_master/check_chequeno/') }}",
			type: 'get',
			data: 'chqno='+chqno+'&bank_id='+bank,
			success: function(data) { 
				if(data=='') {
					alert('Cheque no is duplicate!');
					$('#chequeno_'+curNum).val('');
				}
			}
		})
	});
	
	$('#input-51').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#photo_name_1').val();
			$('#photo_name_1').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});
	
	var fNum;
	$(document).on('click', '.btn-add-file', function(e)  { 
		var res = this.id.split('_');
		fNum = res[1];
	    fNum++;
		$.ajax({
			url: "{{ url('job_order/get_fileform/SI') }}",
			type: 'post',
			data: {'no':fNum},
			success: function(data) {
				$('.filedivPrnt').append(data);
				return true;
			}
		}) 
		
	}).on('click', '.btn-remove-file', function(e) { 
		$(this).parents('.filedivChld:first').remove();
		
		$('.filedivPrnt').find('.filedivChld:last').find('.btn-add-file').show();
		if ( $('.filedivPrnt').children().length == 1 ) {
			$('.filedivPrnt').find('.btn-remove-file').hide();
		}
		
		e.preventDefault();
		return false;
	});

	var custurl2;
	$(document).on('click', '#rvdraccount_1', function(e)  {   
		//if($('#department_id').length)
			//custurl2 = "{{ url('sales_order/customer_data/') }}"+'/'+$('#department_id option:selected').val();
		//else
		var vtype = $('#vouchertype_1 option:selected').val();
		custurl2 = "{{ url('sales_order/account_data/') }}"+'/'+vtype;
		$('#customer_modalRV #customerDataRV').load(custurl2, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
			/* if( $('#customerInfo').is(":hidden") ) 
				$('#customerInfo').toggle(); */
		});
	});


	$(document).on('click', '#customerDataRV .custRow', function(e) { //console.log($(this).attr("data-trnno"));
	
			$('#rvdraccount_1').val($(this).attr("data-name"));
			$('#rvdraccountid_1').val($(this).attr("data-id"));
			
		e.preventDefault();
	});

	//VOUCHER NO DUPLICATE OR NOT
	$(document).on('blur', '#voucher_no', function() {
		
		$.ajax({
			url: "{{ url('sales_invoice/checkvchrno/') }}", 
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
	
	//MAY25 BATCH SYSTEM...
	
	$(document).on('click', '.batch-add', function(e) { 
       
       var res = this.id.split('_');
	   var n = res[1];
	   
	   var item_id = $('#itmid_'+n).val(); 
	   var batch = $('#bthSelIds_'+n).val(); 
	   var btqty = $('#bthSelQty_'+n).val(); 
       var batchurl = "{{ url('itemmaster/batch-get') }}";
       
       if(batch!='') {
    	   var vwUrl = batchurl+'?item_id='+item_id+'&batch='+batch+'&qty='+btqty+'&act=add&no='+n; 
    	   $('#batchData').load(vwUrl, function(result) {
    		  $('#myModal').modal({show:true}); 
    	   });
    	   
       } else {	  
           
           if(item_id!='') {
        	   var vwUrl = batchurl+'?item_id='+item_id+'&act=add&no='+n; 
        	   $('#batchData').load(vwUrl, function(result) {
        		  $('#myModal').modal({show:true}); 
        	   });
           } 
       }
    });
    
     
    $(document).on('click', '.saveBatch', function(e)  { 
         e.preventDefault();
        var isvalid = true;
        var batchId = '';
        var qtyBatch = '';
        var totalQty = parseFloat(0);
        
        $( '.req-bqty' ).each(function() { 
            
    		  var res = this.id.split('_');
    		  var n = res[1];
    		  
    		  if( $('#bthqty_'+n).is(':not(:disabled)') && this.value=='') {
    		      alert('Quantity is required!');
    		      isvalid = false;
    		      return false;
    		  } 
    		  
    		  if( $('#bthqty_'+n).is(':enabled') && this.value!='') {
    		      
    		      batchId = (batchId=='')?n:batchId+','+n; //var n is batch id
        	    
        	      bthArr.push(n);
        	      uniqueBtharr = bthArr.filter((value, index, self) => self.indexOf(value) === index);
        	      
        	      qtyBatch = (qtyBatch=='')?$(this).val():qtyBatch+','+$(this).val();
        	      totalQty += parseFloat($(this).val());
    		  }
		 
		});
		
		if(isvalid==true) {
    		 var row_no = $('#row_no').val();
            
            //JUL25
           var fmla = $('#packing_'+row_no).val();
           if(fmla!=1) {
              var res = fmla.split('-');
        	  var pkn = res[0];
        	  var pkng = res[1]
        	  
        	  totalQty = totalQty / (pkn * pkng);
           } else {
               totalQty = totalQty*fmla;
           }
           //....
           
    		 $('#bthSelIds_'+row_no).val( batchId );
    		 $('#bthSelQty_'+row_no).val( qtyBatch );
    		 $('#batch_modal').modal('hide');
    		 $('#itmqty_'+row_no).val(totalQty);
		}
	});
	
	$(document).on('change', '.chk-batch', function(e) {  
    
        if( $(this).is(":checked") ) { 
        	
        	$('#bthqty_'+$(this).val().toString()).prop("disabled", false);
        } else {
            
        	$('#bthqty_'+$(this).val().toString()).prop("disabled", true);
        }
    
    });
});

function getRvTotal() {
	var tamount = 0;
	$( 'input[name="rv_amount[]"]' ).each(function() {
		if(this.value!='') {
			tamount += parseFloat(this.value);
		}
			
	}); return tamount; 
}

function funRemove(id,n)
{
	var idar = $('#asmid_'+n).val().split(",");
	removeAr(idar, id);
	$('#asmid_'+n).val(idar);
	console.log(this); 
}


var popup;
function getItems(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var res = e.id.split('_');
	var curNum = res[1]; 
	var itmurl = "{{ url('purchase_order/item_data/') }}/"+curNum;
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return true;
}

function getDocument() { 
	if($("#customer_name").val()=='') {
		alert('Please select a customer first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var customer_id = $("#customer_id").val();
	var doc = $('#document_type option:selected').val();
	if(doc=='SQ')
		var pourl = "{{ url('quotation_sales/get_quotation/') }}/"+customer_id+"/QSI";
	else if(doc=='SO')
		var pourl = "{{ url('sales_order/get_order/') }}/"+customer_id+"/SOI";
	else if(doc=='CDO')
		var pourl = "{{ url('customers_do/get_order/') }}/"+customer_id+"/CDO";
	
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getAccount(e) { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var itmurl = "{{ url('purchase_invoice/account_data/') }}/sales";
	popup = window.open(itmurl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getSalesInvoice() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('sales_invoice/get_salesinvoice/') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getLocation() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('sales_invoice/get_locationTransfer/') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function openWin(id) {
  myWindow = window.open("{{url('sales_invoice/print/')}}/"+id, "", "width=400, height=477");
}


</script>

@stop
