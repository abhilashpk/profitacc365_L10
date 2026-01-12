@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Quantity Report
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Reports
                </li>
				<li>
                    <a href="#">Quantity Report</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="60%" align="left"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if($type=='qtyhand_ason_date_loc') { ?>
							<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $gtotal = $gqtytotal = 0;
										foreach($results as $items) { ?>
										<tr>
											<td colspan="2"><b>Location Code: {{$items[0]->code}}</b></td>
											<td colspan="3"><b>Location Name: {{$items[0]->name}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td></td> 
										</tr>
										
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$qty = $item->opn_quantity;
												$cost = $item->opn_cost;
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											} else {
												$qty = $item->cur_quantity;
												$cost = $item->cost_avg;
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->description}}</td>
											<td>{{$item->packing}}</td>
											<td class="text-right">{{$qty}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="3"></td>
											<td><b>Sub Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										<?php 
											$gtotal += $total; $gqtytotal += $qtytotal;
										
										} ?>
										<?php if($locid=='all') { ?>
										<tr>
											<td colspan="3"></td>
											<td><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$gqtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($gtotal,2)}}</b></td>
											<td></td>
										</tr>
										<?php } ?>
										</tbody>
									</table>
									
							<?php } else { ?>
								<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($results as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$quantity = $item['opn_quantity'];
												$cost = $item['opn_cost'];
												$subtotal = $quantity * $item['opn_cost'];
												$total += $subtotal;
												$qtytotal += $item['quantity'];
											} else {
												$quantity = $item['quantity'];
												$cost = $item['cost_avg'];
												$subtotal = $item['quantity'] * $item['cost_avg'];
												$total += $subtotal;
												$qtytotal += $quantity;
												
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item['itemcode']}}</td>
											<td>{{$item['description']}}</td>
											<td>{{$item['unit']}}</td>
											<td class="text-right">{{$quantity}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="4"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										</tbody>
									</table>
							<?php } ?>
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button" onclick="javascript:window.print();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
