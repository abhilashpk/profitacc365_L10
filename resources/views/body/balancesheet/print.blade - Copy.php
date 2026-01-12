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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Balance Sheet</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Balance Sheet</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-fw fa-credit-card"></i> Report
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6 col-sm-12 col-xs-12 invoice_bg">
                                <h3>{{Session::get('company')}}</h3>
                                
                            </div>
                            
                        </div>
                        <div class="col-md-12">
						<div class="pull-center"><h3>{{$voucherhead}}</h3></div>
						
                          <?php if($report) { ?>
                                <table class="table" border="1">
                                    <thead>
                                    <tr class="bg-primary">
										<th align="center" colspan="2">LIABILITIES</th><th align="center" colspan="2">ASSETS</th>
                                    </tr>
                                    </thead>
									<body>
										<tr>
											<td colspan="2">
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													<tr>
														<td><strong>{{$curliability['name']}}</strong></td>
														<?php
															if($curliability['total'] > 0) {
																$cl_amount = '('.number_format($curliability['total']).')';
															} else 
																$cl_amount = number_format( ($curliability['total']*-1),2);
														?>
														<td class="text-right"><strong>{{$cl_amount}}</strong></td>
													</tr>
													@foreach($curliability['items'] as $row)
													<?php
														if($row['amount'] > 0) {
															$amount = '('.number_format($row['amount'],2).')';
														} else 
															$amount = number_format(($row['amount']*-1),2);
													?>
														<tr>
															<td style="padding-left:10px;">{{$row['group_name']}}</td>
															<td class="text-right">{{$amount}}</td>
														</tr>
													@endforeach
													<?php if($capital) { ?>
													<tr>
														<td><strong>{{$capital['name']}}</strong></td>
														<?php
															if($capital['total'] > 0) {
																$cp_amount = '('.number_format($capital['total'],2).')';
															} else 
																$cp_amount = number_format(($capital['total']*-1),2);
														?>
														<td class="text-right"><strong>{{$cp_amount}}</strong></td>
													</tr>
													
													@foreach($capital['items'] as $row)
													<?php
														if($row->cl_balance > 0) {
															$amount = '('.number_format($row->cl_balance,2).')';
														} else 
															$amount = number_format(($row->cl_balance*-1),2);
													?>
														<tr>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
															<td class="text-right">{{$amount}}</td>
														</tr>
													@endforeach
													<?php } ?>
													
													<?php if($equity) { ?>
													<tr>
														<td><strong>{{$equity['name']}}</strong></td>
														<?php
															if($equity['total'] > 0) {
																$eq_amount = '('.number_format($equity['total'],2).')';
															} else 
																$eq_amount = number_format(($equity['total']*-1),2);
														?>
														<td class="text-right"><strong>{{$eq_amount}}</strong></td>
													</tr>
													@foreach($equity['items'] as $row)
													<?php
														if($row->cl_balance > 0) {
															$amount = '('.number_format($row->cl_balance,2).')';
														} else 
															$amount = number_format(($row->cl_balance*-1),2);
													?>
														<tr>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
															<td class="text-right">{{$amount}}</td>
														</tr>
													@endforeach
													
													<?php } ?>
													
													<?php if($netprofit > 0) { ?>
													<tr>
														<td><strong>Net Profit</strong></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = '('.number_format($arr[1],2).')';
															} else 
																$netprofit = number_format($netprofit,2);
														?>
														<td class="text-right"><strong>{{$netprofit}}</strong></td>
													</tr>
													<?php } ?>
													
													
													<?php if($differencel) { ?>
													<tr>
														<?php
															if($differencel < 0) {
																$arr = explode('-', $differencel);
																$difference = '('.number_format($arr[1],2).')';
															} else 
																$difference = number_format($differencel,2);
														?>
														<td><strong>Difference</strong></td>
														<td class="text-right"><strong>{{$difference}}</strong></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
											</td>
											<td colspan="2">
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													<?php if($fixedassets) { ?>
													<tr>
														<td><strong>{{$fixedassets['name']}}</strong></td>
														<td class="text-right"><strong>{{$fixedassets['total']}}</strong></td>
													</tr>
													@foreach($fixedassets['items'] as $row)
														<tr>
															<td style="padding-left:10px;">{{$row['group_name']}}</td>
															<td class="text-right">{{$row['amount']}}</td>
														</tr>
													@endforeach
													<?php } ?>
													<tr>
														<td><strong>{{$curassets['name']}}</strong></td>
														<td class="text-right"><strong>{{$curassets['total']}}</strong></td>
													</tr>
													@foreach($curassets['items'] as $row)
													<?php
														if($row['amount'] < 0) {
															$arr = explode('-', $row['amount']);
															$amount = '('.number_format($arr[1],2).')';
														} else 
															$amount = number_format($row['amount'],2);
													?>
														<tr>
															<td style="padding-left:10px;">{{$row['group_name']}}</td>
															<td class="text-right">{{$amount}}</td>
														</tr>
													@endforeach
													
													<?php if($netprofit < 0) { ?>
													<tr>
														<td><strong>Net Loss</strong></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = '('.number_format($arr[1],2).')';
															} else 
																$netprofit = number_format($netprofit,2);
														?>
														<td class="text-right"><strong>{{$netprofit}}</strong></td>
													</tr>
													<?php } ?>
													
													<?php if($differencer) { ?>
													<tr>
														<?php
															if($differencer < 0) {
																$arr = explode('-', $differencer);
																$difference = '('.number_format($arr[1],2).')';
															} else 
																$difference = number_format($differencer,2);
														?>
														<td><strong>Difference</strong></td>
														<td class="text-right"><strong>{{$difference}}</strong></td>
													</tr>
													<?php } ?>
													
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
										<?php
												if($total < 0) {
													$arr = explode('-', $total);
													$total = '('.number_format($arr[1],2).')';
												} else 
													$total = number_format($total,2);
											?>
										<td><strong>Total:</strong></td><td class="text-right" style="padding-right:55px;"><strong>{{$total}}</strong></td><td><strong>Total:</strong></td><td class="text-right" style="padding-right:55px;"><strong>{{$total}}</strong></td>
                                    </tr>
									</body>
								</table>
						  <?php } else { ?>
								<div class="well well-sm">No reports were found! </div>
						  <?php } ?>
                        </div>
                      
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.print();">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
								<button type="button" onclick="location.href='{{ url('balance_sheet') }}';" class="btn btn-responsive button-alignment btn-primary" data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
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
