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
               Sales Order 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Sales Order Job
                    </a>
                </li>
                <li>
                    <a href="#">Sales Order </a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
		
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
                    <div class="panel panel-<?php echo ($orderrow->is_editable==1)?'danger':'primary';?>">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> <?php if($orderrow->is_editable==1) { echo 'Non-Editable'; } else echo 'Edit';?>
                            </h3>
							
							<div class="pull-right">
							<a href="{{ url('sales_order_booking/edit_force/'.$orderrow->id) }}" class="btn btn-primary btn-sm">
								<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Force to Edit
							</a>
							<?php if($isprint) { ?>
							@can('so-print')
							 <a href="{{ url('sales_order_booking/print/'.$orderrow->id.'/'.$print->id) }}" target="_blank" class="btn btn-info btn-sm">
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
							<form class="form-horizontal" role="form" method="POST" name="frmSalesOrder" id="frmSalesOrder" action="{{ url('sales_order_booking/update/'.$orderrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="sales_order_id" id="sales_order_id" value="{{ $orderrow->id }}">
								<div class="form-group">
								
                                    <label for="input-text" class="col-sm-2 control-label"><b>Order No.</b></label>
                                    <div class="col-sm-10">
										<?php if($orderrow->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$orderrow->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
											<input type="hidden" value="{{$orderrow->prefix}}" name="prefix">
										</div>
										<?php } else { ?>
											<input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$orderrow->voucher_no}}">
											<input type="hidden" value="{{$orderrow->prefix}}" name="prefix">
										<?php } ?>
                                    </div>
                                </div>
								
								<input type="hidden" name="reference_no" id="reference_no">
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" autocomplete="off" data-toggle="modal" data-target="#customer_modal" value="<?php echo (old('customer_name'))?old('customer_name'):$orderrow->customer; ?>">
										<input type="hidden" name="customer_id" id="customer_id" value="<?php echo (old('customer_id'))?old('customer_id'):$orderrow->customer_id; ?>">
									</div>
                                </div>
								
								@if(count($crm) > 0)
								<h5><a href="#" id="crmBtn"><b>CRM Details</b></a></h5>
								<hr/>
								<div class="crm_info">
									@foreach($crm as $rw)
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label}}</label>
										<input type="hidden" name="tempid[]" value="{{$rw->id}}">
										@if($rw->text_no==1)
										<div class="col-sm-10">
										@if( $rw->values1!='' )
											@php $vals1 = explode(',',$rw->values1); @endphp
											<select class="form-control select2" name="crmtext[]" {{($rw->reqd1==1)?"required" : ""}}>
												<option value="">Select...</option>
												@foreach ($vals1 as $val1)
												<option value="{{ $val1 }}" {{($rw->textval==$val1)?'selected':''}}>{{ $val1 }}</option>
												@endforeach
											</select>
										@else
											<input type="text" name="crmtext[]" value="{{$rw->textval}}" class="form-control" autocomplete="on" {{($rw->reqd1==1)?"required" : ""}}>
											<input type="hidden" name="crmtext2[]">
										@endif
										</div>
										@else
										<div class="col-sm-4">
										@if( $rw->values1!='' )
											@php $vals1 = explode(',',$rw->values1); @endphp
											<select class="form-control select2" name="crmtext[]" {{($rw->reqd1==1)?"required" : ""}}>
												<option value="">Select...</option>
												@foreach ($vals1 as $val1)
												<option value="{{ $val1 }}" {{($rw->textval==$val1)?'selected':''}}>{{ $val1 }}</option>
												@endforeach
											</select>
										@else
											<input type="text" name="crmtext[]" value="{{$rw->textval}}" class="form-control" autocomplete="on" {{($rw->reqd1==1)?"required" : ""}}>
										@endif
										</div>
										<label for="input-text" class="col-sm-2 control-label">{{$rw->label2}}</label>
										<div class="col-sm-4">
										@if( $rw->values2!='' )
											@php $vals2 = explode(',',$rw->values2); @endphp
											<select class="form-control select2" name="crmtext2[]" {{($rw->reqd2==1)?"required" : ""}}>
												<option value="">Select...</option>
												@foreach ($vals2 as $val2)
												<option value="{{ $val2 }}" {{($rw->textval2==$val2)?'selected':''}}>{{ $val2 }}</option>
												@endforeach
											</select>
										@else
											<input type="text" name="crmtext2[]" value="{{$rw->textval2}}" class="form-control" autocomplete="on" {{($rw->reqd2==1)?"required" : ""}}>
										@endif
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
                                        <input type="text" name="salesman" id="salesman" value="<?php echo (old('salesman'))?old('salesman'):$orderrow->salesman; ?>" class="form-control" autocomplete="off" data-toggle="modal" data-target="#salesman_modal" placeholder="Salesman">
										<input type="hidden" name="salesman_id" id="salesman_id" value="<?php echo (old('salesman_id'))?old('salesman_id'):$orderrow->salesman_id; ?>">
									</div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="salesman_id" id="salesman_id">
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">SO. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="voucher_date" data-language='en' value="{{date('d-m-Y',strtotime($orderrow->voucher_date))}}" id="voucher_date">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Due Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="due_date" autocomplete="off" name="next_due" data-language='en' value="{{($orderrow->next_due!='0000-00-00')?date('d-m-Y',strtotime($orderrow->next_due)):''}}">
                                    </div>
                                </div>
								
								<input type="hidden" name="lpo_date" id="lpo_date">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Ship To</label>
                                    <div class="col-sm-10">
                                        <textarea name="remarks" id="remarks" class="form-control" placeholder="">{{$orderrow->remarks}}</textarea>
                                    </div>
                                </div>
								
								<input type="hidden" name="description" id="description">
								
								<?php if($formdata['description']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" value="<?php echo (old('description'))?old('description'):$orderrow->description; ?>">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="description" id="description">
								<?php } ?>
								
								<?php if($formdata['terms']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Terms</label>
                                    <div class="col-sm-10">
                                        <select id="terms_id" class="form-control select2" style="width:100%" name="terms_id">
                                            <option value="">Select Terms...</option>
											@foreach ($terms as $term)
											<option value="{{ $term['id'] }}" <?php if($term['id']==$orderrow->terms_id) echo 'selected'; ?>>{{ $term['description'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="terms_id" id="terms_id">
								<?php } ?>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label ">Mobile Number</label>
									<div class="col-sm-10">
										<input type="tel" class="form-control" id="fuel_level" name="fuel_level" placeholder="Phone no" value="{{$orderrow->fuel_level}}">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label ">Delivery Info</label>
									<div class="col-sm-10">
										<select id="delivery" class="form-control select2" style="width:100%" name="delivery">
											<option value="0" {{($orderrow->kilometer==0)?'selected':''}}>No</option>
											<option value="1" {{($orderrow->kilometer==1)?'selected':''}}>Delivery Only</option>
											<option value="2" {{($orderrow->kilometer==2)?'selected':''}}>Delivery with Installation</option>
										</select>
										
										<div class="col-xs-10" id="delvInfo">
											<div class="col-xs-3">
												<span class="small">Location</span> <input type="text" name="location" value="{{$orderrow->less_description}}" class="form-control">
											</div>
											<div class="col-xs-4">
												<span class="small">Other Info</span> <input type="text" name="info_delivery" value="{{$orderrow->less_description2}}" class="form-control">
											</div>
										</div>
									</div>
								</div>
							
								<fieldset>
								<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Item Details</span></h5></legend>
								<table class="table table-bordered" style="margin-bottom:0px !important;">
									<thead>
									<tr>
										<th width="14%" class="itmHd">
											<span class="small">Product Code</span>
										</th>
										<th width="25%" class="itmHd">
											<span class="small">Product Name</span>
										</th>
										<th width="8%" class="itmHd">
											<span class="small">Unit</span>
										</th>
										<th width="8%" class="itmHd">
											<span class="small">Quantity</span>
										</th>
										<th width="8%" class="itmHd">
											<span class="small">Rate</span>
										</th>
										<th width="11%" class="itmHd">
											<span class="small">Job Order</span> 
										</th>
										<th width="16%" class="itmHd">
											<span class="small">Grand Total</span> 
										</th>
									</tr>
									</thead>
								</table>
								
								<!-- ROWCHNG -->
								{{--*/ $i = 0; $num = count($orditems); /*--}}
								<input type="hidden" id="rowNum" value="{{$num}}">
								<input type="hidden" id="remitem" name="remove_item">
								<div class="itemdivPrnt">
								
								@foreach($orditems as $item)
								{{--*/ $i++; /*--}}
								<?php if($orderrow->is_fc==1) {
										 $unit_price = $item->unit_price / $orderrow->currency_rate;
										 $line_total = number_format($item->line_total / $orderrow->currency_rate,2, '.', '');
										 $vat_amount = round($item->vat_amount / $orderrow->currency_rate,2);
										 $total = $orderrow->total / $orderrow->currency_rate;
										 $vat_amount_net = $orderrow->vat_amount / $orderrow->currency_rate;
										 $net_total = round($orderrow->net_total / $orderrow->currency_rate,2);
									  } else {
										 $unit_price = $item->unit_price;
										 $line_total = $item->line_total;
										 $vat_amount = $item->vat_amount;
										 $total = $orderrow->total;
										 $vat_amount_net = $orderrow->vat_amount;
										 $net_total = $orderrow->net_total;
									  }
									
									?>
									<div class="itemdivChld">							
										<table border="0" class="table-dy-row">
											<tr>
												<td width="13%">
													<input type="hidden" name="order_item_id[]" id="p_orditmid_{{$i}}" value="{{$item->id}}">
													<input type="hidden" name="item_id[]" id="itmid_{{$i}}" value="{{$item->item_id}}">
													<input type="text" id="itmcod_{{$i}}" name="item_code[]" class="form-control" autocomplete="off" data-toggle="modal" data-target="#item_modal" value="{{$item->item_code}}">
												</td>
												<td width="24%">
													<input type="text" name="item_name[]" id="itmdes_{{$i}}" autocomplete="off" class="form-control" value="{{$item->item_name}}">
												</td>
												<td width="7%">
													<select id="itmunt_{{$i}}" class="form-control select2 line-unit" style="width:100%" name="unit_id[]">
													<option value="{{$item->unit_id}}">{{$item->unit_name}}</option></select>
													</select>
												</td>
												<td width="7%">
													<input type="number" id="itmqty_{{$i}}" name="quantity[]" step="any" autocomplete="off" class="form-control line-quantity" value="{{$item->quantity}}">
												</td>
												<td width="7%">
													<input type="number" id="itmcst_{{$i}}" step="any" name="cost[]" autocomplete="off" class="form-control line-cost" value="{{$unit_price}}">
												</td>
												<td width="10%" align="center">
													<input type="hidden" id="sjbid_{{$i}}" name="sojob_id[]" value="{{$item->pay_pcntg_desc}}">
													<a href="" class="btn btn-primary btn-sm job-order" data-toggle="modal" data-target="#job_modal" id="jb_{{$i}}">Job Order</a>
												</td>
												<td width="11%">
													<input type="number" id="itmttl_{{$i}}" step="any" name="line_total[]" class="form-control line-total" readonly value="{{$line_total}}">
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
										
											<div class="infodivPrntItm" id="infodivPrntItm_{{$i}}">
												<div class="infodivChldItm">							
													<div class="table-responsive item-data" id="itemData_{{$i}}">
													</div>
												</div>
											</div>
											
											<div class="locPrntItm" id="locPrntItm_{{$i}}">
												<div class="locChldItm">							
													<div class="table-responsive loc-data" id="locData_{{$i}}"></div>
												</div>
											</div>
											
									</div>
								@endforeach
								</div>
								
								</fieldset><hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Net Amount</label>
                                    <div class="col-xs-10">
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2"></div>
										<div class="col-xs-2">
											
											<input type="number" step="any" id="net_amount" name="net_amount" class="form-control spl" readonly value="{{(old('net_amount')?old('net_amount'):$orderrow->is_fc==1)?$orderrow->net_total_fc:$orderrow->net_total}}">
										</div>
										<div class="col-xs-2">
											
										</div>
									</div>
                                </div>
								
								<input type="hidden" name="rem_photo_id" id="rem_photo_id">
								  <div class="filedivPrnt">
								  <?php if(count($photos)>0) { $i=0; ?>
										@foreach($photos as $prow)
										<?php $i++; ?>
										<div class="filedivChld">
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Uploaded Image {{$i}}</label>
												<div class="col-sm-9">
													<div class="file-preview">
														<!--<div class="close fileinput-remove removeP" data-val="{{$prow->id}}">×</div>-->
														<div class="file-drop-disabled">
															<div class="file-preview-thumbnails">
																<div class="file-live-thumbs">
																	<a href="{{asset('uploads/joborder/'.$prow->photo)}}" target="_blank">View Image {{$i}}</a>
																</div>
															</div>
															<input type="hidden" name="photo_id[]" id="photo_id_{{$i}}" value="{{$prow->id}}">
															<input type="hidden" name="photo_name[]" id="photo_name_{{$i}}" value="{{$prow->photo}}">
															<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
															<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												</div>
												<div class="col-sm-1">
													<button type="button" class="btn-success btn-add-file" id="btn_{{$i}}">
														<i class="fa fa-fw fa-plus-square"></i>
													</button>
													<button type="button" class="btn-danger btn-remove-file" data-id="{{$prow->id}}">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
												<label for="input-text" class="col-sm-2 control-label filedslbl" id="lblifd_{{$i}}">Description</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="imgdesc_{{$i}}" name="imgdesc[]" value="{{$prow->description}}" placeholder="Description" autocomplete="off">
												</div>
												
											</div>
										</div>
										@endforeach
								  <?php } else { ?>
										<div class="filedivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_1">Upload Image 1</label>
											<div class="col-sm-9">
												<input type="file" id="input-51" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('job_order/upload/')}}">
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
								  <?php } ?>
								  </div>
								  
								<hr/>
								
															
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary" <?php if($orderrow->is_editable==1) echo 'disabled';?>>Submit</button>
										<a href="{{ url('sales_order_booking') }}" class="btn btn-danger">Cancel</a>
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
							
                            </form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		
		<div id="job_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header" style="background-color:#428BCA !important;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" style="color:white !important;">Job Order</h4>
					</div>
					<div class="modal-body" id="jobData"></div>
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
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
<script>

"use strict";
var srvat={{$vatdata->percentage}};
$(document).ready(function () { 
	
	$('.crm_info').hide();
	$('.locPrntItm').toggle();
	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	$('.filedivPrnt').find('.btn-add-file:not(:last)').hide();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); 
	var urlcode = "{{ url('sales_order/checkrefno/') }}";
    $('#frmSalesOrder').bootstrapValidator({
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
								id: validator.getFieldElements('sales_order_id').val()
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
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmSalesOrder').data('bootstrapValidator').resetForm();
    });
	
});

	function getNetTotal() {
		
		var lineTotal = 0;
		$( '.line-total' ).each(function() { 
		  var res = this.id.split('_');
		  var n = res[1];
		  var amt = getLineTotal(n);
		  lineTotal = lineTotal + parseFloat(amt.toFixed(2));
		 
		});
		
		//console.log('lineTotal: '+lineTotal);
		$('#net_amount').val(lineTotal.toFixed(2));
	}
	
	//calculation item line total and discount...
	function getLineTotal(n) {
		var lineQuantity = parseFloat( ($('#itmqty_'+n).val()=='') ? 0 : $('#itmqty_'+n).val() );
		var lineCost 	 = parseFloat( ($('#itmcst_'+n).val()=='') ? 0 : $('#itmcst_'+n).val() );
		var lineTotal = lineCost * lineQuantity;
		$('#itmttl_'+n).val(lineTotal);
		return lineTotal;
	} 
	
	function getAutoPrice(curNum) {
		var item_id = $('#itmid_'+curNum).val();
		var unit_id = $('#itmunt_'+curNum+' option:selected').val();
		var customer_id = $('#customer_id').val();
		var crate = $('#currency_rate').val();
		var cst = $('#itmcst_'+curNum).val(); //NW
		$.ajax({
			url: "{{ url('itemmaster/get_sale_cost/') }}",
			type: 'get',
			data: 'item_id='+item_id+'&unit_id='+unit_id+'&customer_id='+customer_id+'&crate='+crate,
			success: function(data) {
				if(cst=='') //NW
					$('#itmcst_'+curNum).val((data==0)?'':data);
				else
					$('#itmcst_'+curNum).val(cst);
				return true;
			}
		}) 
	}
	
	
//
$(function() {	
	
	$(document).on('click', '#crmBtn', function(e) { 
		   e.preventDefault();
           $('.crm_info').toggle();
    });
	
	$('#due_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
	var rowNum = $('#rowNum').val(); //new change...........
	$(document).on('click', '#infoadd', function(e) { 
		   e.preventDefault();
           $('.infodivPrnt').toggle();
      });
	
	$(document).on('click', '#mailform', function(e) { 
	   e.preventDefault();
	   $('.maildivPrnt').toggle();
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
			newEntry.find($('input[name="line_total[]"]')).attr('id', 'itmttl_' + rowNum);
			newEntry.find($('input[name="sojob_id[]"]')).attr('id', 'sjbid_' + rowNum);
			newEntry.find($('.item-data')).attr('id', 'itemData_' + rowNum);
			newEntry.find('input').val(''); 
			newEntry.find('.line-unit').find('option').remove().end().append('<option value="">Unit</option>');//NW CHNG
			newEntry.find($('input[name="item_total[]"]')).attr('id', 'itmtot_' + rowNum);
			newEntry.find($('.hidunt')).attr('id', 'hidunit_' + rowNum);
			newEntry.find($('input[name="packing[]"]')).attr('id', 'packing_' + rowNum);
			newEntry.find($('.job-order')).attr('id', 'jb_' + rowNum);
			$('#packing_'+rowNum).val(1);
						
			if( $('#infodivPrntItm_'+rowNum).is(":visible") ) 
				$('#infodivPrntItm_'+rowNum).toggle();
			
			//ENTER KEY for TAB functionality in dynamic field..........
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
	
	
	var custurl = "{{ url('sales_order/customer_data/') }}";
	$('#customer_name').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	var supurl = "{{ url('sales_order/salesman_data/') }}";
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
		$('#item_data').load(itmurl+'/'+curNum+'/ser', function(result){ //.modal-body item
			$('#myModal').modal({show:true});
		});
	});
	
	//new change............
	$(document).on('click', 'input[name="item_name[]"]', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#item_data').load(itmurl+'/'+curNum+'/ser', function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	//new change.................
	$(document).on('click', '.itemRow', function(e) { 
		var num = $('#lbnum').val();
		$('#itmcod_'+num).val( $(this).attr("data-code") );
		$('#itmid_'+num).val( $(this).attr("data-id") );
		$('#itmdes_'+num).val( $(this).attr("data-name") );
				
		var itm_id = $(this).attr("data-id")
		$.get("{{ url('purchase_order/getunit/') }}/" + itm_id, function(data) { 
			$('#itmunt_'+num).find('option').remove().end();
			$.each(data, function(key, value) {   
			$('#itmunt_'+num).find('option').remove().end()
			 .append($("<option></option>")
						.attr("value",value.id)
						.text(value.unit_name)); 
			});

		});
	});
	
	$('#customer_name').on('blur', function(e){
		var vchr_no = $('#voucher_no').val();
		var ref_no = $('#reference_no').val();
		var vchr_dt = $('#voucher_date').val();
		var lpo_dt = $('#lpo_date').val();
		
		$.ajax({
			url: "{{ url('sales_order/set_session/') }}",
			type: 'get',
			data: 'vchr_no='+vchr_no+'&ref_no='+ref_no+'&vchr_dt='+vchr_dt+'&lpo_dt='+lpo_dt,
			success: function(data) { //console.log(data);
			}
		}) 
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
	});
	
		
	$('#currency_id').on('change', function(e){
		var curr_id = e.target.value; 
		$('#fc_label').html('FC Currency '+ $("#currency_id option:selected").text());
		$.get("{{ url('currency/getrate/') }}/" + curr_id, function(data) {
			$('#currency_rate').val(data);
			//console.log(data);
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
							.text(value.unit_name));  $('#hidunit_'+curNum).val(value.unit_name);
				});
			});
		}	
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
		
	
	$(document).on('click', '.job-order', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		var sid = $('#sjbid_'+curNum).val();
		if(sid=='') {
			var jburl = "{{ url('item_template/get_template/') }}";
			$('#jobData').load(jburl+'/'+$('#itmid_'+curNum).val()+'/'+curNum, function(result){ 
				$('#myModal').modal({show:true}); $('.input-sm').focus()
			});
		} else {
			var jburl = "{{ url('item_template/get_template_edit/') }}";
			$('#jobData').load(jburl+'/'+$('#itmid_'+curNum).val()+'/'+sid+'/'+curNum, function(result){ 
				$('#myModal').modal({show:true}); $('.input-sm').focus()
			});
		}
	});
	
	$(document).on('click', '.jobRow', function(e) {
		var no = $('#jnum').val();
		$('#sjbid_'+no).val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var fNum;
	$(document).on('click', '.btn-add-file', function(e)  { 
		var res = this.id.split('_');
		fNum = res[1];
	    fNum++;
		$.ajax({
			url: "{{ url('job_order/get_fileform/') }}",
			type: 'post',
			data: {'no':fNum},
			success: function(data) {
				$('.filedivPrnt').append(data);
				return true;
			}
		}) 
		
	}).on('click', '.btn-remove-file', function(e) { console.log('id'+ $(this).attr("data-id"));
		
		var remitem = $('#rem_photo_id').val(); var ids;
		ids = (remitem=='')?$(this).attr("data-id"):remitem+','+$(this).attr("data-id");
		$('#rem_photo_id').val(ids);
		
		$(this).parents('.filedivChld:first').remove();
		
		$('.filedivPrnt').find('.filedivChld:last').find('.btn-add-file').show();
		if ( $('.filedivPrnt').children().length == 1 ) {
			$('.filedivPrnt').find('.btn-remove-file').hide();
		}
		
		e.preventDefault();
		return false;
	});
});

</script>
@stop
