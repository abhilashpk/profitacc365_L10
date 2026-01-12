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
                Stock Ledger
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Reports
                </li>
				<li>
                    <a href="#">Stock Ledger</a>
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
						<!--<p>Date From: <b><?php //echo ($fromdate=='')?'01-01-2018':$fromdate;?></b> &nbsp; To: <b><?php //echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>-->
                        <div class="col-md-12">
							
							<h5>Item Code: <b>{{$results['opn_details'][0]->item_code}}</b> &nbsp; Item Name: <b>{{$results['opn_details'][0]->description}}</b></h5>
							<?php if($voucherhead=='Stock Ledger with Quantity') { ?>
								<div class="table-responsive">
									<table class="table" border="0">
										<thead>
											<tr>
												<th style="width:50px;"><strong>Type</strong></th>
												<th><strong>Vchr.No.</strong></th>
												<th><strong>Tr.Date</strong></th>
												<th><strong>Supp/Cust.</strong></th>
												<th class="text-right"><strong>Qty.In</strong></th>
												<th class="text-right"><strong>Qty.Out</strong></th>
												<th class="text-right"><strong>Balance</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $qtyin = $qtyout = 0; ?>
										@foreach($results['opn_details'] as $opndetails)
										<tr>
											<td>OQ</td>
											<td></td>
											<td></td>
											<td>Opening Quantity</td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											<td class="text-right"></td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											<td></td> 
											<?php $qtyin += $opndetails->opn_quantity; ?>
										</tr>
										@endforeach

										@foreach($results['pursales'] as $result)
										<tr>
											<td>{{$result->type}}</td>
											<td>{{$result->voucher_no}}</td>
											<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
											<td>{{$result->master_name}}</td>
											<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR') echo $result->quantity;?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR') echo $result->quantity;?></td>
											<td class="text-right">{{$result->cur_quantity}}</td>
											<td></td> 
											<?php if($result->type=='PI' || $result->type=='SR') 
														$qtyin += $result->quantity;
												   elseif($result->type=='SI' || $result->type=='PR') 
														$qtyout += $result->quantity; ?>
										</tr>
										@endforeach
										<?php $balance = $qtyin - $qtyout; ?>
											<tr>
												<td colspan="4" class="text-right"><b>Total: </b></td>
												<td class="text-right"><b>{{$qtyin}}</b></td>
												<td class="text-right"><b>{{$qtyout}}</b></td>
												<td class="text-right"><b>{{$balance}}</b></td>
												<td></td>
											</tr>
											<tr><td colspan="8"></td></tr>
										</tbody>
									</table>
								</div>
							<?php } else if($voucherhead=='Stock Ledger with Location') { ?>
								<div class="table-responsive">
									<table class="table" border="0">
										<thead>
											<tr>
												<th style="width:50px;"><strong>Type</strong></th>
												<th><strong>Vchr.No.</strong></th>
												<th><strong>Tr.Date</strong></th>
												<th><strong>Supp/Cust.</strong></th>
												<th class="text-right"><strong>Qty.In</strong></th>
												<th class="text-right"><strong>Qty.Out</strong></th>
												<th class="text-right"><strong>Balance</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										
										<?php foreach($results['pursales'] as $results) { ?>
										<tr>
											<td colspan="3"><b>Location Code: {{$results[0]->code}}</b></td>
											<td colspan="3"><b>Location Name: {{$results[0]->name}}</b></td>
											<td class="text-right"></td>
											<td></td> 
										</tr>
										<?php $qtyin = $qtyout = 0; ?>
										<!-- open details -->

										@foreach($results as $result)
										<tr>
											<td>{{$result->type}}</td>
											<td>{{$result->voucher_no}}</td>
											<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
											<td>{{$result->master_name}}</td>
											<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR') echo $result->quantity;?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR') echo $result->quantity;?></td>
											<td class="text-right">{{$result->cur_quantity}}</td>
											<td></td> 
											<?php if($result->type=='PI' || $result->type=='SR') 
														$qtyin += $result->quantity;
												   elseif($result->type=='SI' || $result->type=='PR') 
														$qtyout += $result->quantity; ?>
										</tr>
										@endforeach
										<?php $balance = $qtyin - $qtyout; ?>
											<tr>
												<td colspan="4" class="text-right"><b>Total: </b></td>
												<td class="text-right"><b>{{$qtyin}}</b></td>
												<td class="text-right"><b>{{$qtyout}}</b></td>
												<td class="text-right"><b>{{$balance}}</b></td>
												<td></td>
											</tr>
											<tr><td colspan="8"></td></tr>
										<?php } ?>
										
										</tbody>
									</table>
								</div>
							<?php } else { ?>       
								<div class="table-responsive">
									<table class="table" border="0">
										<thead>
											<tr>
												<th style="width:50px;"><strong>Type</strong></th>
												<th><strong>Vchr.No.</strong></th>
												<th><strong>Tr.Date</strong></th>
												<th><strong>Supp/Cust.</strong></th>
												<th class="text-right"><strong>Qty.In</strong></th>
												<th class="text-right"><strong>Pur.Cost</strong></th>
												<th class="text-right"><strong>Cost Avg.</strong></th>
												<th class="text-right"><strong>Sl. Cost</strong></th>
												<th class="text-right"><strong>Qty.Out</strong></th>
												<th class="text-right"><strong>Sl. Price</strong></th>
												<th class="text-right"><strong>Balance</strong></th>
												
											</tr>
										</thead>
										<tbody>
										<?php $qtyin = $qtyout = $pcost = $scost = $costavg = $costsi = 0; ?>
										@foreach($results['opn_details'] as $opndetails)
										<tr>
											<td>OQ</td>
											<td></td>
											<td></td>
											<td>Opening Quantity</td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											<td class="text-right"></td>
											<td class="text-right"><?php echo number_format($opndetails->cost_avg,2); ?></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											<td></td> 
											<?php $qtyin += $opndetails->opn_quantity; $costavg += $opndetails->cost_avg; ?>
										</tr>
										@endforeach

										@foreach($results['pursales'] as $result)
										<tr>
											<?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR') {
													$qtyin += $result->quantity;
													$pcost += $result->unit_cost;
													$costavg += $result->cost_avg;
													
												  } elseif($result->type=='SI' || $result->type=='PR') {
													 $costsi = $result->sale_cost;
													 $qtyout += $result->quantity; 
													 $scost += $result->unit_cost;		
													 $costsi += $costsi;
													 $costavg += $result->cost_avg;
											} $curbalance = $qtyin - $qtyout; ?>
											<td>{{$result->type}}</td>
											<td>{{$result->voucher_no}}</td>
											<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
											<td>{{$result->master_name}}</td>
											<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR') echo $result->quantity;?></td>
											<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR') echo number_format($result->pur_cost,3);?></td>
											<td class="text-right"><?php /* if($result->type=='PI' || $result->type=='SR') */ echo number_format($result->cost_avg,3);?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo number_format($result->sale_cost,3);?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo $result->quantity;?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo number_format($result->unit_cost,3);?></td>
											<td class="text-right">{{$curbalance}}</td>
											<td></td> 
											
										</tr>
										@endforeach
										<?php $balance = $qtyin - $qtyout; ?>
											<tr>
												<td colspan="4" class="text-right"><b>Total: </b></td>
												<td class="text-right"><b>{{$qtyin}}</b></td>
												<td class="text-right"><b>{{number_format($pcost,2)}}</b></td>
												<td class="text-right"><b>{{$costavg}}</b></td>
												<td class="text-right"><b>{{$costsi}}</b></td>
												<td class="text-right"><b>{{$qtyout}}</b></td>
												<td class="text-right"><b>{{number_format($scost,2)}}</b></td>
												<td class="text-right"><b>{{$balance}}</b></td>
												<td></td>
											</tr>
											<tr><td colspan="11"></td></tr>
										</tbody>
									</table>
								</div>
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
