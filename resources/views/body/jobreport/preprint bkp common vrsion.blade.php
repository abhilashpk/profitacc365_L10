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
            <h1> Job Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Job Report</li>
                <li class="active">
                   <a href="#">Print</a>
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
								<tr>
									<td align="left" colspan="2">
										<b style="font-size:20px;">{{Session::get('company')}}</b><br/>
										<b style="font-size:12px;">Ph: {{Session::get('phone')}}, {{Session::get('address')}}</b>
										<!--<b style="font-size:15px;">TRN No: {{Session::get('vatno')}}</b>-->
									</td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
							<?php if($stype=='summary') { ?>
							
									<table class="table" border="0">
										<thead>
											<th>Job Code</th>
											<th>Job Name</th>
											<th class="text-right">Income</th>
											<th class="text-right">Cost</th>
											<th class="text-right">Net Income</th>
										</thead>
										<body>
										<?php $total_income = $total_expense = $total_netincome = 0; 
											if(count($reports) > 0) {
										?>
											@foreach($reports as $report)
											<tr>
												<?php 
													$net_income = $report->income - $report->amount;
													$total_income += $report->income;
													$total_expense += $report->amount;
													$total_netincome += $net_income;
												?>
												<td>{{ $report->code }}</td>
												<td>{{ $report->name }}</td>
												<td class="text-right">{{ number_format($report->income,2) }}</td>
												<td class="text-right">{{ number_format($report->amount,2) }}</td>
												<td class="text-right">
												<?php if($net_income < 0) {
													echo '('.number_format(($net_income*-1),2).')';
												} else echo number_format($net_income,2); ?></td>
											</tr>
											@endforeach
											<tr>
												<td colspan="2" align="right"><b>Grand Total:</b></td>
												<td class="text-right"><b>{{ number_format($total_income,2) }}</b></td>
												<td class="text-right"><b>{{ number_format($total_expense,2) }}</b></td>
												<td class="text-right"><b>
													<?php if($total_netincome < 0) {
													echo '('.number_format(($total_netincome*-1),2).')';
												} else echo number_format($total_netincome,2); ?>
												</b></td>
											</tr>
											<?php } else { ?>
											<tr>
												<td colspan="5">
												<div class="well well-sm">No records were found!</div>
												</td>
											</tr>
											<?php } ?>
										</body>
									</table>
							<?php } else if($stype=='summary_ac' || $stype=='detail1') { ?>	
							<?php if($jobid!=''){ ?><h6><b>Job Code: {{$report->code}}</b>, <b>Job Name: {{$report->name}}</b></h6><?php } ?>
									<?php if(count($reports) > 0) { ?>
									<table class="table" border="0">
										<?php $ginctotal = $gexptotal = $gbalance_total = 0;?>
										@foreach($reports as $report)
										<thead>
											<th colspan="2">Account Name: {{$report[0]->master_name}} </th>
											<th >Tr.No: {{$report[0]->voucher_no}}</type>
											<th></th>
											<th >Type: {{$report[0]->type}}</th>
										</thead>
										<body>
											<?php $inctotal = $exptotal = $balance_total = 0; ?>
											
											<tr>
												<th style="width:35%;">Item Code</th>
												<th style="width:50%;">Description</th>
												<th style="width:15%;" class="text-right">Income</th>
												<th style="width:15%;" class="text-right">Cost</th>
												<th style="width:15%;" class="text-right">Balance</th>
											</tr>
											@foreach($report as $row)
											<?php 
													$inctotal += $row->income; 
													$exptotal += ($row->unit_price * $row->quantity);//$row->amount; 
													$balance = $row->income - ($row->unit_price * $row->quantity);
													$balance_total += $balance;
											?>
											
											<tr>
												<td style="width:20%;">{{$row->item_code}}</td>
												<td style="width:30%;" align="left">{{$row->description}}</td>
												<td style="width:15%;" class="text-right"><?php echo ($row->income > 0)?number_format($row->income,2):'';?></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->amount > 0)?number_format(($row->unit_price * $row->quantity),2):'';?></td>
												<td style="width:15%;" class="text-right">
												<?php if($balance < 0) {
													echo '('.number_format(($balance*-1),2).')';
												} else echo number_format($balance,2); ?></td>
											</tr>
											
											@endforeach
											<?php 
													$ginctotal += $inctotal; 
													$gexptotal += $exptotal; 
													$gbalance_total += $balance_total;
											?>
											<tr>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="right"><b>Total:</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($inctotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($exptotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>
												<?php if($balance_total < 0) {
													echo '('.number_format(($balance_total*-1),2).')';
												} else echo number_format($balance_total,2); ?>
												</b></td>
											</tr>
											<tr>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="left"></td>
												<td style="width:15%;" class="text-right"></td>
												<td style="width:15%;" class="text-right"><br/></td>
												<td style="width:15%;" class="text-right"></td>
											</tr>
										
										</body>
										@endforeach
									</table>
									
									<table border="0" style="width:100%;">
										<tr>
											<td style="width:55%;" colspan="3" align="right"><b>Grand Total:</b></td>
											<td style="width:16%;" class="text-right"><b>{{number_format($ginctotal,2)}}</b></td>
											<td style="width:15%;" class="text-right"><b>{{number_format($gexptotal,2)}}</b></td>
											<td style="width:14%;" class="text-right"><b>
											<?php if($gbalance_total < 0) {
													echo '('.number_format(($gbalance_total*-1),2).')';
												} else echo number_format($gbalance_total,2); ?>
											</b></td>
										</tr>
									</table><br/>
									<?php } ?>
							<?php } else if($stype=='detail') { ?>	
									<table class="table" border="0">
										<thead>
											<th style="width:1%;">Type</type>
											<th style="width:4%;">Tr.No</th>
											<th style="width:20%;">Date</th>
											<th style="width:30%;">Description</th>
											<th style="width:15%;" class="text-right">Income</th>
											<th style="width:15%;" class="text-right">Cost</th>
											<th style="width:15%;" class="text-right">Balance</th>
										</thead>
										<body>
										<?php $ginctotal = $gexptotal = $gbalance_total = 0; 
											if(count($reports) > 0) {
										?>
										
											
											<?php $inctotal = $exptotal = $balance_total = 0; ?>
											@foreach($reports as $report)
											
											<tr>
												@if($jobsplit==1)
												<td style="width:20%;">Job Code: <b>{{$report[0]->code}}</b></td>
												<td style="width:30%;" align="left">Job Name: <b>{{$report[0]->name}}</b></td>
												@else
												<td style="width:20%;">Account ID: <b>{{$report[0]->account_id}}</b></td>
												<td style="width:30%;" align="left">Account Name: <b>{{$report[0]->master_name}}</b></td>
												@endif
												<td style="width:1%;"></td>
												<td style="width:4%;"></td>
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;"></td>
											</tr>
											@foreach($report as $row)
											<?php 
													$inctotal += $row->income; 
													$exptotal += $row->amount; 
													$balance = $row->income - $row->amount;
													$balance_total += $balance;
													$desc = '';
													if($row->jdesc==''){
														if($row->type=='GI')
															$desc = 'Goods Issued Note';
														elseif($row->type=='GR')
															$desc = 'Goods Return';
														elseif($row->type=='PI')
															$desc = 'Purchase Invoice';
														elseif($row->type=='SR')
															$desc = 'Sales Invoice';
														elseif($row->type=='JV')
															$desc = 'Journal Entry';
														elseif($row->type=='SP')
															$desc = 'Payment Voucher';
														elseif($row->type=='CR')
															$desc = 'Receipt Voucher';
														elseif($row->type=='PS')
															$desc = $row->description;
														elseif($row->type=='SS')
															$desc = $row->description;
													} else $desc = $row->jdesc;
											?>
											<tr>
												<td style="width:1%;">{{$row->type}}</td>
												<td style="width:4%;">{{$row->voucher_no}}</td>
												<td style="width:20%;">{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
												<td style="width:30%;" align="left">{{$desc}}</td>
												<td style="width:15%;" class="text-right"><?php echo ($row->income > 0)?number_format($row->income,2):'';?></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->amount > 0)?number_format($row->amount,2):'';?></td>
												<td style="width:15%;" class="text-right">
												<?php if($balance < 0) {
													echo '('.number_format(($balance*-1),2).')';
												} else echo number_format($balance,2); ?></td>
											</tr>
											@endforeach
											<tr>
												<td style="width:20%;"></td>
												<td style="width:30%;" align="left"></td>
												<td style="width:1%;"></td>
												<td style="width:4%;"></td>
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;" align="left"></td>
												<td style="width:15%;"><br/></td>
											</tr>
											@endforeach
											<?php 
													$ginctotal += $inctotal; 
													$gexptotal += $exptotal; 
													$gbalance_total += $balance_total;
											?>
											<tr>
												<td style="width:20%;"></td>
												<td style="width:30%;"></td>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="right"><b>Total:</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($inctotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($exptotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>
												<?php if($balance_total < 0) {
													echo '('.number_format(($balance_total*-1),2).')';
												} else echo number_format($balance_total,2); ?>
												</b></td>
											</tr>
											<tr>
												<td style="width:20%;"></td>
												<td style="width:30%;"></td>
												<td style="width:1%;"></td>
												<td style="width:4%;" align="left"></td>
												<td style="width:15%;" class="text-right"></td>
												<td style="width:15%;" class="text-right"><br/></td>
												<td style="width:15%;" class="text-right"></td>
											</tr>
										

											<?php } else { ?>
											<tr>
												<td colspan="7">
												<div class="well well-sm">No records were found!</div>
												</td>
											</tr>
											<?php } ?>
										</body>
									</table>
									
									<!--<table border="1" style="width:100%;" class="table">
										<tr>
											<td style="width:55%;" colspan="3" align="right"></td>
											<td style="width:16%;" class="text-right"><b>Total Income:</b></td>
											<td style="width:15%;" class="text-right"><b>Total Cost:</b></td>
											<td style="width:14%;" class="text-right"><b>Net Income/Loss</b></td>
										</tr>
										<tr>
											<td style="width:55%;" colspan="3" align="right"><b>Grand Total:</b></td>
											<td style="width:16%;" class="text-right"><b>{{number_format($ginctotal,2)}}</b></td>
											<td style="width:15%;" class="text-right"><b>{{number_format($gexptotal,2)}}</b></td>
											<td style="width:14%;" class="text-right"><b>
											<?php if($gbalance_total < 0) {
													echo '('.number_format(($gbalance_total*-1),2).')';
												} else echo number_format($gbalance_total,2); ?>
											</b></td>
										</tr>
									</table><br/>-->
							<?php } else if($stype=='stockin' || $stype=='stockout') { 
									if(count($reports['invoice']) > 0) {
							?>
									<?php if($reports['invoice']) { ?>
									<?php if($jobid!='') { $arr = current($reports['invoice']); ?>
									<p>Job Code: <b>{{$arr[0]->code}}</b>&nbsp; &nbsp; Job Name: <b>{{$arr[0]->name}}</b></p>
									<?php } ?>
									<table class="table" border="0">
										<thead>
											<!--<th style="width:10%;">Tr.Date</th>
											<th style="width:10%;">Inv.No.</th>
											<th style="width:10%;">Ref.No.</th>-->
											<th style="width:35%;">Item Description</th>
											<th style="width:10%;" class="text-right">Qty.</th>
											<th style="width:10%;" class="text-right">Rate</th>
											<th style="width:15%;" class="text-right">Value</th>
										</thead>
										<body>
										
										<?php foreach($reports['invoice'] as $report) { ?>
											<tr><?php if($report[0]->type=='SI')
													$type = 'SALES INVOICE(STOCK)';
												  else if($report[0]->type=='GI')
													  $type = 'GOODS ISSUED';
												  else if($report[0]->type=='PI')
													  $type = 'PURCHASE INVOICE(STOCK)';
												  else if($report[0]->type=='GR')
													  $type = 'GOODS RETURN';
												?>
												<td colspan="7"><b><?php echo $type;?><b></td>
											</tr>
											<tr>
												<td colspan="2">Voucher Name: <b>{{$report[0]->master_name}}</b> </td>
												<td>Inv. No: <b>{{$report[0]->voucher_no}}</b></td>
												<td>Inv. Date: <b>{{date('d-m-Y',strtotime($report[0]->voucher_date))}}</b></td>
											</tr>
											<?php 
											$qty_total = $value_total = 0;
											foreach($report as $row) { ?>
												<tr>
													<!--<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
													<td>{{$row->voucher_no}}</td>
													<td>{{$row->reference_no}}</td>-->
													<td>{{$row->item_code.' - '.$row->description}}</td>
													<td class="text-right">{{$row->quantity}}</td>
													<td class="text-right">{{number_format($row->unit_price,2)}}</td>
													<?php $value = $row->unit_price * $row->quantity;
														$qty_total += $row->quantity;
														$value_total += $value;
													?>
													<td class="text-right">{{number_format($value,2)}}</td>
												</tr>
											<?php } ?>
											<tr>
												<td align="right"><b>Total:</b></td>
												<td class="text-right"><b>{{$qty_total}}</b></td>
												<td class="text-right"></td>
												<td class="text-right"><b>{{number_format($value_total,2)}}</b></td>
											</tr>
										<?php } ?>
										</body>
									</table>
									<?php } ?>
									<?php } else { ?>
										<div class="well well-sm">No records were found!</div>
									<?php } ?>
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
								
								<!--<button type="button" onclick="getExport()"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;">
										<i class="fa fa-fw fa-upload"></i>
									Export Excel
								</span>
								</button>-->
									
								<button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.history.back();">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('job_report/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$stype}}" >
					<input type="hidden" name="job_id" value="{{$jobid}}" >
					</form>
					
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
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
