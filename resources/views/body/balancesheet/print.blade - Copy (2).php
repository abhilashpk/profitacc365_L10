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
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
    <!--end of page level css-->
	<style>
		.catrow {
			height:40px !important;
		}
		.grprow {
			height:35px !important; padding-left:10px !important;
		}
		.itmrow {
			height:30px !important;
		}
	</style>
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
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="60%" align="left"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
						
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
											<?php if($type=='summary') { ?>
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													@foreach($liability as $rows)
														<tr>
															<td><strong>{{$rows['name']}}</strong></td>
															<?php
																if($rows['total'] > 0) {
																	$cl_amount = number_format($rows['total'],2);
																} else 
																	$cl_amount = number_format(($rows['total']*-1),2);
															?>
															<td class="text-right"><strong>{{$cl_amount}}</strong></td>
														</tr>
														@foreach($rows['items'] as $row)
														<?php
															if($row['amount'] > 0) {
																$amount = number_format($row['amount'],2);
															} else 
																$amount = number_format(($row['amount']*-1),2);
														?>
															<tr>
																<td style="padding-left:10px;">{{$row['group_name']}}</td>
																<td class="text-right">{{$amount}}</td>
															</tr>
														@endforeach
													@endforeach
													
													<?php if($netprofit > 0) { ?>
													<tr>
														<td><strong>Net Profit</strong></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = number_format($arr[1],2);
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
																$difference = number_format($arr[1],2);
															} else 
																$difference = number_format($differencel,2);
														?>
														<td><strong>Difference</strong></td>
														<td class="text-right"><strong>{{$difference}}</strong></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
											<?php } else { ?>
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													@foreach($liability as $rows)
														<tr class="catrow">
															<td><b>{{$rows['catname']}}</b></td>
															<?php
																if($rows['total'] > 0) {
																	$cl_amount = number_format($rows['total'],2);
																} else 
																	$cl_amount = number_format(($rows['total']*-1),2);
															?>
															<td class="text-right"><b>{{$cl_amount}}</b></td>
														</tr>
														@foreach($rows['items'] as $row)
														<tr class="grprow">
															<td style="padding-left:5px;"><b>{{$row['name']}}</b></td>
															<?php
																if($row['total'] > 0) {
																	$gamount = number_format($row['total'],2);
																} else 
																	$gamount = number_format(($row['total']*-1),2);
															?>	
															<td class="text-right"><b>{{$gamount}}</b></td>
															@foreach($row['gitems'] as $item)
															<?php
																if($item['amount'] > 0) {
																	$amount = number_format($item['amount'],2);
																} else 
																	$amount = number_format(($item['amount']*-1),2);
															?>
															<?php if($amount!=0) { ?>
															<tr class="itmrow">
																<td style="padding-left:10px;">{{$item['group_name']}}</td>
																<td class="text-right">{{$amount}}</td>
															</tr>
															<?php } ?>
															@endforeach
															
														</tr>
														@endforeach
													@endforeach
													
													<?php if($netprofit > 0) { ?>
													<tr>
														<td><strong>Net Profit</strong></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = number_format($arr[1],2);
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
																$difference = number_format($arr[1],2);
															} else 
																$difference = number_format($differencel,2);
														?>
														<td><strong>Difference</strong></td>
														<td class="text-right"><strong>{{$difference}}</strong></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
											<?php } ?>
											
											</td>
											<td colspan="2">
											
											<?php if($type=='summary') { ?>
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													@foreach($assets as $rows)
														<tr>
															<td><strong>{{$rows['name']}}</strong></td>
															<?php
																if($rows['total'] > 0) {
																	$astotal = number_format($rows['total'],2);
																} else 
																	$astotal = '('.number_format( ($rows['total']*-1),2).')';
															?>
															<td class="text-right"><strong>{{$astotal}}</strong></td>
														</tr>
														@foreach($rows['items'] as $row)
															<tr>
																<td style="padding-left:10px;">{{$row['group_name']}}</td>
																<?php
																	if($row['amount'] > 0) {
																		$asamount = number_format($row['amount'],2);
																	} else 
																		$asamount = '('.number_format( ($row['amount']*-1),2).')';
																?>
																<td class="text-right">{{$asamount}}</td>
															</tr>
														@endforeach
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
											<?php } else { ?>
												<table border="0" width="90%">
													<thead>
														<tr>
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													@foreach($assets as $rows)
														<tr class="catrow">
															<td><strong>{{$rows['catname']}}</strong></td>
															<?php
																if($rows['total'] > 0) {
																	$amount1 = number_format($rows['total'],2);
																} else 
																	$amount1 = '('.number_format( ($rows['total']*-1),2).')';
															?>
															<td class="text-right"><strong>{{$amount1}}</strong></td>
														</tr>
														@foreach($rows['items'] as $row)
															<tr class="grprow">
																<td style="padding-left:5px;"><strong>{{$row['name']}}</strong></td>
																<?php
																	if($row['total'] > 0) {
																		$amount2 = number_format($row['total'],2);
																	} else 
																		$amount2 = '('.number_format( ($row['total']*-1),2).')';
																?>
																<td class="text-right"><b>{{$amount2}}</b></td>
															</tr>
															@foreach($row['gitems'] as $item)
															<?php if($item['amount']!=0) { ?>
																<tr class="itmrow">
																	<td style="padding-left:10px;">{{$item['group_name']}}</td>
																	<?php
																		if($item['amount'] > 0) {
																			$amount3 = number_format($item['amount'],2);
																		} else 
																			$amount3 = '('.number_format( ($item['amount']*-1),2).')';
																	?>
																	<td class="text-right">{{$amount3}}</td>
																</tr>
															<?php } ?>
															@endforeach
															
														@endforeach
														
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
											<?php } ?>
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
