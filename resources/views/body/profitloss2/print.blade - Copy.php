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
            <h1>Profit & Loss</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Profit & Loss</li>
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
                        <div class="col-md-12">
                          
                                <table class="table" border="1">
                                    <thead>
                                    <tr class="bg-primary">
										<th align="center" colspan="2">Expense</th><th align="center" colspan="2">Income</th>
                                    </tr>
                                    </thead>
									<body>
										<tr>
											<td colspan="2">
												<table border="0" width="90%">
													<thead>
														<tr class="catrow">
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													<?php if($directexp) { ?>
													<tr class="catrow">
														<td><strong>{{$directexp['name']}}</strong></td>
														<?php
															if($directexp['total'] < 0) {
																$arr = explode('-', $directexp['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($directexp['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($directexp['items'] as $row)
														<tr class="catrow"><?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { if($row->cl_balance!=0) { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$clbalance = '('.number_format($arr[1],2).')';
																} else 
																	$clbalance = number_format($row->cl_balance,2);
															?>
															<?php if($row->cl_balance!=0) { ?>
															<td class="text-right">{{$clbalance}}</td>
														<?php } ?>
														</tr>
													@endforeach
													<?php } ?>
													
													<tr class="catrow">
														<td><?php if($grossprofit > 0) echo '<strong>Gross Profit C/F</strong>'; ?></td>
														<td class="text-right"><?php if($grossprofit > 0) echo '<strong>'.number_format($grossprofit,2).'</strong>';?></td>
													</tr>
													<tr class="catrow">
														<td><strong>Sub Total</strong></td>
														<?php
															if($subtotal < 0) {
																$arr = explode('-', $subtotal);
																$subtotal = '('.number_format($arr[1],2).')';
															} else 
																$subtotal = number_format($subtotal,2);
														?>
														<td class="text-right"><strong>{{$subtotal}}</strong></td>
													</tr>
													<?php if($grossprofit < 0) { 
																$arr = explode('-', $grossprofit);
																$gross_loss_bf = '('.number_format($arr[1],2).')';
													?>
													<tr class="catrow">
														<td><strong>Gross Loss B/F</strong></td>
														<td class="text-right"><strong>{{$gross_loss_bf}}</strong></td>
													</tr>
													<?php } ?>
													
													<?php if($indirectexp) { ?>
													
													<tr class="catrow">
														<td><strong>{{$indirectexp['name']}}</strong></td>
														<?php
															if($indirectexp['total'] < 0) {
																$arr = explode('-', $indirectexp['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($indirectexp['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($indirectexp['items'] as $row)
													<?php if($row->cl_balance != 0) { ?>
														<tr class="catrow"><?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$clbalance = '('.number_format($arr[1],2).')';
																} else 
																	$clbalance = number_format($row->cl_balance,2);
															?>
															<td class="text-right">{{$clbalance}}</td>
														</tr>
													<?php } ?>
													@endforeach
													<?php } ?>
													
													<?php if($netprofit > 0) { ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr class="catrow">
														<td><h5><b>Net Profit</b></h5></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = '('.number_format($arr[1],2).')';
															} else 
																$netprofit = number_format($netprofit,2);
														?>
														<td class="text-right"><h5><strong>{{$netprofit}}</strong></h5></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
											</td>
											<td colspan="2">
												<table border="0" width="90%">
													<thead>
														<tr class="catrow">
															<td><b>Description</b><hr/></td>
															<td class="text-right"><b>Amount</b><hr/></td>
														</tr>
													</thead>
													<tbody>
													<?php if($directinc) { ?>
													<tr class="catrow">
														<td><strong>{{$directinc['name']}}</strong></td>
														<?php
															if($directinc['total'] < 0) {
																$arr = explode('-', $directinc['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($directinc['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($directinc['items'] as $row)
														<tr class="catrow">
														<?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$cl_balance = '('.number_format($arr[1],2).')';
																} else 
																	$cl_balance = number_format($row->cl_balance,2);
															?>
															<td class="text-right">{{$cl_balance}}</td>
														</tr>
													@endforeach
													<?php } ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr class="catrow">
														<td><?php if($grossprofit < 0) echo '<strong>Gross Loss C/F</strong>'; ?></td>
														<td class="text-right">
														<?php if($grossprofit < 0) { 
																$arr = explode('-', $grossprofit);
																$gross_loss_cf = '('.number_format($arr[1],2).')';
																echo '<strong>'.$gross_loss_cf.'</strong>';
														}
														?></td>
													</tr>
													
													<?php if($indirectinc) { ?>
													<tr class="catrow">
														<td><strong>{{$indirectinc['name']}}</strong></td>
														<?php
															if($indirectinc['total'] < 0) {
																$arr = explode('-', $indirectinc['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($indirectinc['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($indirectinc['items'] as $row)
														<tr class="catrow">
														<?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$cl_balance = '('.number_format($arr[1],2).')';
																} else 
																	$cl_balance = number_format($row->cl_balance,2);
															?>
															<td class="text-right">{{$cl_balance}}</td>
														</tr>
													@endforeach
													<?php } ?>
													<tr class="catrow">
														<td><strong>Sub Total</strong></td>
														<td class="text-right">{{$subtotal}}</td>
													</tr>
													<?php if($grossprofit > 0) { ?>
													<tr >
														<td><strong>Gross Profit B/F</strong></td>
														<td class="text-right"><strong>{{number_format($grossprofit,2)}}</strong></td>
													</tr>
													<?php } elseif($grossprofit < 0) { $arr = explode('-', $netprofit);
																	$netloss = '('.number_format($arr[1],2).')'; ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr >
														<td><h5><strong>Net Loss</strong></h5></td>
														<td class="text-right"><strong>{{$netloss}}</strong></td>
													</tr>
													<?php } ?>
													
													</tbody>
												</table>
											</td>
										</tr>
										
										<tr>
											<td><strong>Total:</strong></td>
											<?php
												if($total < 0) {
													$arr = explode('-', $total);
													$total = '('.number_format($arr[1],2).')';
												} else 
													$total = number_format($total,2);
											?>
											<td class="text-right" style="padding-right:55px;"><strong>{{$total}}</strong></td>
											<td><strong>Total:</strong></td>
											<td class="text-right" style="padding-right:55px;"><strong>{{$total}}</strong></td>
										</tr>
									</body>
								</table>
							
                        </div>
                      
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
									 <button type="button" onclick="javascript:window.print();"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;" >
											<i class="fa fa-fw fa-print"></i>
										Print
									</span>
									</button>
									<button type="button" onclick="location.href='{{ url('profit_loss')}}'"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
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
