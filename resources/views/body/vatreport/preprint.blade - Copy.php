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
            <h1> Vat Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Vat Report</li>
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
							<?php if($type=='summary') { ?>
									<table class="table">
										<thead>
											<tr>
												<th>SI.No</th>
												<th>Group Name</th>
												<th>Account ID</th>
												<th>Account Name</th>
												<th class="text-right">VAT Amount</th>
												<th></th>
												<th></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										@php $i=0;  $vat=0; @endphp
											@foreach($reports as $report)
											@php $i++; 
												$vat += $report->amount;
											@endphp
											<tr>
												<td>{{$i}}</td>
												<td>{{ $report->group_name }}</td>
												<td>{{ $report->account_id }}</td>
												<td>{{ $report->master_name }}</td>
												<td class="text-right">
												@if($report->amount < 0)
													{{'('.number_format(($report->amount*-1),2).')'}}
												@else
													{{ number_format($report->amount,2)}}
												@endif
												</td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											@endforeach
											
											<tr>
												<td></td><td></td><td></td><td><b>Total VAT Payable:</b></td>
												<td class="text-right"><b>
												@if($vat < 0)
												{{'('.number_format($vat*-1,2).')'}}
												@else
												{{number_format($vat,2)}}
												@endif
												</b></td>
												<td></td>
												<td></td><td></td>
											</tr>
										</tbody>
									</table>
							<?php } else if($type=='detail') { ?>	
									<table class="table" border="0">
										<thead>
										<tr>
											<th>
												<strong>SI.#</strong>
											</th>
											<th>
												<strong>Voucher No.</strong>
											</th>
											<th>
												<strong>Voucher Date</strong>
											</th>
											<th>
												<strong>Type</strong>
											</th>
											<th>
												<strong>Acc.Desc.</strong>
											</th>
											
											<th>
												<strong>TRN No</strong>
											</th>
											<th class="text-right">
												<strong>Gross Amt.</strong>
											</th>
											<th class="text-right">
												<strong>Net Amt.</strong>
											</th>
											<th class="text-right">
												<strong>VAT Amt.</strong>
											</th>
											
										</tr>
										</thead>
										<tbody>
										<?php 
										$vatinput = $vatoutput = 0;
										foreach($reports as $key => $report) { 
											if($key=='Dr') { ?>
											<tr><td colspan="9"><strong>VAT INPUT</strong></td></tr>
											<?php } else {  ?>
											<tr><td colspan="9"><strong>VAT OUTPUT</strong></td></tr>
											<?php } $i=0;?>
											@foreach($report as $row)
											<?php $i++; 
												if($row->transaction_type=='Dr') {
													if($row->trtype=='Cr') {
														$vat_amount = '('.number_format($row->vat_amount,2).')';
														$vatinput += ($row->vat_amount*-1);
													} else {
														$vatinput += $row->vat_amount;
														$vat_amount = number_format($row->vat_amount,2);
													}
													 
												} else {
													if($row->trtype=='Dr') {
														$vat_amount = '('.number_format($row->vat_amount,2).')';
														$vatoutput += ($row->vat_amount*-1);
													} else {
														$vatoutput += $row->vat_amount;
														$vat_amount = number_format($row->vat_amount,2);
													}
													 
												}
												?>
											<tr> <td>{{$i}}</td>
												<td>{{$row->voucher_no}}</td>
												<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
												<td>{{$row->voucher_type}}</td>
												<td>{{$row->master_name}}</td>
												<td>{{$row->trn_no}}</td>
												<?php 
													$gross_total = ($row->voucher_type=='PC' || $row->voucher_type=='SIN')?($row->net_total - $vat_amount):$row->gross_total;
												?>
												<td class="emptyrow text-right"><?php echo number_format($gross_total,2);?></td>
												<td class="emptyrow text-right">{{number_format($row->net_total,2)}}</td>
												<td class="emptyrow text-right">{{$vat_amount}}</td>
												
											</tr>
											@endforeach
											<?php if($key=='Dr') { ?>
											<tr><td colspan="6" class="text-right"><strong>INPUT Total:</strong></td>
												<td colspan="3" class="text-right"><strong>{{number_format($vatinput,2)}}</strong></td>
											</tr>
											<?php } else { ?>
											<tr><td colspan="6" class="text-right"><strong>OUTPUT Total:</strong></td>
												<td colspan="3" class="text-right"><strong>{{number_format($vatoutput,2)}}</strong></td>
											</tr>
											<?php } 
										} ?>
										
										</tbody>
									</table>
									<?php 
										
										  $payable = $vatoutput - $vatinput; 
										  $payable = ($payable < 0)?$payable*-1:$payable;
									?>
									<table border="0" style="width:100%;">
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>Total OutPut:</b></h6></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>{{number_format($vatoutput,2)}}</b></h6></td>
										</tr>
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>Total InPut:</b></h6></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>{{number_format($vatinput,2)}}</b></h6></td>
										</tr>
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>VAT Payable:</b></h5></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>{{number_format($payable,2)}}</b></h5></td>
										</tr>
									</table>
							<?php } else if($type=='partywise') { ?>	
							
									<table class="table" border="0">
										<thead>
											<th style="width:5%;">SI.#</th>
											<th style="width:10%;">Voucher No.</th>
											<th style="width:10%;">Voucher Date</th>
											<th style="width:5%;">Type</th>
											<th style="width:18%;">Acc.Desc.</th>
											<th style="width:10%;">TRN No</th>
											<th style="width:15%;" class="text-right">Gross Amt.</th>
											<th style="width:15%;" class="text-right">Net Amt.</th>
											<th style="width:12%;" class="text-right">VAT Amt.</th>
										</thead>
										<tbody>
										<?php foreach($reports as $key => $report) { ?>
											
											<?php foreach($report as $code => $rows) { ?>
											<tr>
												<td colspan="9"><b style="font-size:10pt;"><?php echo $code.' - '.$rows[0]->tax_code; ?></b></td>
											</tr>
												<?php $i=0;
													$total = $nettotal = $vattotal = 0;
													foreach($rows as $row) { 
													$i++;
													$total += $row->gross_total;
													$nettotal += $row->net_total;
													$vattotal += $row->vat_amount;
												?>
														<tr>
															<td style="width:5%;">{{$i}}</td>
															<td style="width:10%;">{{$row->voucher_no}}</td>
															<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
															<td style="width:5%;">{{$row->voucher_type}}</td>
															<td style="width:18%;">{{$row->master_name}}</td>
															<td style="width:10%;">{{$row->trn_no}}</td>
															<td style="width:15%;" class="text-right"><?php echo number_format($row->gross_total,2);?></td>
															<td style="width:15%;" class="text-right">{{number_format($row->net_total,2)}}</td>
															<td style="width:12%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
														</tr>
													<?php } ?>
											<tr>
												<td colspan="8" class="text-right"><b>Total:</b></td>
												<td colspan="1" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php } } ?>
										</tbody>
										
									</table>
									
									<br/>
							<?php } else if($type=='summary_taxcode') { ?>	
							
									<table class="table" border="0">
										<thead>
											<th style="width:10%;" colspan="8">VAT Account</th>
											<th style="width:12%;" colspan="1" class="text-right">VAT Amt.</th>
										</thead>
										<tbody>
										<?php foreach($reports as $key => $report) { 
												 
												 foreach($report as $code => $rows) { 
											
													$total = $nettotal = $vattotal = 0;
													foreach($rows as $row) { 
														$total += $row->gross_total;
														$nettotal += $row->net_total;
														$vattotal += $row->vat_amount;
													} 
											?>
											<tr>
												<td colspan="8"><b style="font-size:10pt;"><?php echo $code.' - '.$rows[0]->tax_code; ?></b></td>
												<td colspan="1" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php } } ?>
										</tbody>
										
									</table>
									
									<br/>
							<?php } else if($type=='areawise') { ?>	
							
									<table class="table" border="0">
										<thead>
											<th style="width:5%;">SI.#</th>
											<th style="width:10%;">Inv.#</th>
											<th style="width:10%;">Tr.Date</th>
											<th style="width:5%;">Tr.Type</th>
											<th style="width:20%;">Acc.Desc.</th>
											<th style="width:10%;">TRN</th>
											<th style="width:15%;" class="text-right">Inv.Gross Amt.</th>
											<th style="width:15%;" class="text-right">Inv.Net Amt.</th>
											<th style="width:10%;" class="text-right">VAT Amt.</th>
										</thead>
										<tbody>
											
											<?php $i = $net_total = $net_nettotal = $net_vattotal = 0;
												foreach($reports as $area => $report) { 
													$area_total = $area_nettotal = $area_vattotal = 0;
													$sr_total = $sr_vattotal = $zr_total = $zr_vattotal = $ex_total = $ex_vattotal = 0;
											?>
											<tr>
												<td colspan="9" style="padding-left:50px;"><b>Area: {{$area}}</b></td>
											</tr>
											<?php foreach($report as $code => $rows) { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>Tax Code: {{$code}}</b></td>
											</tr>
												<?php 
													$total = $nettotal = $vattotal = 0;
													foreach($rows as $row) { 
													$i++;
													$total += $row->total;
													$nettotal += $row->net_total;
													$vattotal += $row->vat_amount;
												?>
													<tr>
														<td style="width:5%;">{{$i}}</td>
														<td style="width:10%;">{{$row->voucher_no}}</td>
														<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
														<td style="width:5%;">{{$row->type}}</td>
														<td style="width:20%;">{{$row->master_name}}</td>
														<td style="width:10%;">{{$row->vat_no}}</td>
														<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
														<td style="width:15%;" class="text-right">{{number_format($row->net_total,2)}}</td>
														<td style="width:10%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
													</tr>
												<?php } ?>
											<tr>
												<td colspan="6" align="right"><b>{{$code}} Total:</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php 
													$area_total += $total;
													$area_nettotal += $nettotal;
													$area_vattotal += $vattotal;
													
													if($code=='SR') {
														$sr_total = $total; $sr_vattotal = $vattotal;
													} elseif($code=='ZR') {
														$zr_total = $total; $zr_vattotal = $vattotal;
													} elseif($code=='EX') {
														$ex_total = $total; $ex_vattotal = $vattotal;
													}	
												} 
											?>
											
											<tr>
												<td colspan="6" align="right" ><b>Total SR Sales:</b></td>
												<td style="width:15%;" class="text-right" ><b>{{number_format($sr_total,2)}}</b></td>
												<td style="width:15%;" class="text-right" style="border:0px !important;"><b>Total SR:</b></td>
												<td style="width:10%;" class="text-right" style="border:0px !important;"><b>{{number_format($sr_vattotal,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="6" align="right" style="border:0px !important;"><b>Total EX Sales:</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>{{number_format($ex_total,2)}}</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>Total EX:</b></td>
												<td style="width:10%;border:0px !important;" class="text-right"><b>{{number_format($ex_vattotal,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="6" align="right" style="border:0px !important;"><b>Total ZR Sales:</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>{{number_format($zr_total,2)}}</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>Total ZR:</b></td>
												<td style="width:10%;border:0px !important;" class="text-right"><b>{{number_format($zr_vattotal,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="6" align="right" style="border:0px !important;"><b>{{$area}} Total:</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>{{number_format($area_total,2)}}</b></td>
												<td style="width:15%;border:0px !important;" class="text-right"><b>{{number_format($area_nettotal,2)}}</b></td>
												<td style="width:10%;border:0px !important;" class="text-right"><b>{{number_format($area_vattotal,2)}}</b></td>
											</tr>
											<tr>
												<td colspan="9" style="border:0px !important;"><br/></td>
											</tr>
											<?php 
													$net_total += $area_total;
													$net_nettotal += $area_nettotal;
													$net_vattotal += $area_vattotal;
												} 
												
											?>
											
											<tr>
												<td colspan="6" align="right"><b>Net Total:</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($net_total,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($net_nettotal,2)}}</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($net_vattotal,2)}}</b></td>
											</tr>
										</tbody>
										
									</table>
									
									<br/>
							<?php } else if($type=='tax_code') { ?>	
							
									<table class="table" border="0">
										<thead>
											<th style="width:5%;">SI.#</th>
											<th style="width:10%;">Inv.#</th>
											<th style="width:10%;">Tr.Date</th>
											<th style="width:5%;">Tr.Type</th>
											<th style="width:15%;">Acc.Desc.</th>
											<th style="width:5%;">TRN</th>
											<th style="width:15%;" class="text-right">Inv.Gross Amt.</th>
											<th style="width:20%;" class="text-right">Inv.Net Amt.</th>
											<th style="width:15%;" class="text-right">Vat Amt.</th>
										</thead>
										<tbody>
											<tr>
												<td colspan="9"><b style="font-size:10pt;">VAT INPUT</b></td>
											</tr>
											<?php $i=$totalin=0; foreach($reports['purchase'] as $report) { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>Tax Code: </b>{{$report[0]->tax_code}}</td>
											</tr>
													<?php 
														$total = $nettotal = $vattotal = 0;
														foreach($report as $row) { 
														$i++;
														$total += $row->total;
														$nettotal += $row->net_amount;
														$vattotal += $row->vat_amount;
													?>
														<tr>
															<td style="width:5%;">{{$i}}</td>
															<td style="width:10%;">{{$row->voucher_no}}</td>
															<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
															<td style="width:5%;">PI</td>
															<td style="width:15%;">{{$row->master_name}}</td>
															<td style="width:5%;">{{$row->vat_no}}</td>
															<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
															<td style="width:20%;" class="text-right">{{number_format($row->net_amount,2)}}</td>
															<td style="width:15%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
														</tr>
													<?php } $totalin += $vattotal; ?>
											<tr>
												<td colspan="6" align="right"><b>Total:</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php } ?>
											<tr>
												<td colspan="9" align="right"><b>{{ number_format($totalin,2)}}</b></td>
											</tr>
											
											<tr>
												<td colspan="9"><b style="font-size:10pt;">VAT OUTPUT</b></td>
											</tr>
											<?php $i=$totalout=0; foreach($reports['sales'] as $report) { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>Tax Code: </b>{{$report[0]->tax_code}}</td>
											</tr>
													<?php 
														$total = $nettotal = $vattotal = 0;
														foreach($report as $row) { 
														$i++;
														$total += $row->total;
														$nettotal += $row->net_total;
														$vattotal += $row->vat_amount;
													?>
														<tr>
															<td style="width:5%;">{{$i}}</td>
															<td style="width:10%;">{{$row->voucher_no}}</td>
															<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
															<td style="width:5%;">SI</td>
															<td style="width:15%;">{{$row->master_name}}</td>
															<td style="width:5%;">{{$row->vat_no}}</td>
															<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
															<td style="width:20%;" class="text-right">{{number_format($row->net_total,2)}}</td>
															<td style="width:15%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
														</tr>
													<?php } $totalout += $vattotal; ?>
											<tr>
												<td colspan="6" align="right"><b>Total:</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php } ?>
											<tr>
												<td colspan="9" align="right"><b>{{ number_format($totalout,2)}}</b></td>
											</tr>
										</tbody>
										
									</table>
									<?php $payable = $totalout - $totalin; ?>
									<table border="0" style="width:100%;">
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>Total OutPut:</b></h6></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>{{number_format($totalout,2)}}</b></h6></td>
										</tr>
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>Total InPut:</b></h6></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>{{number_format($totalin,2)}}</b></h6></td>
										</tr>
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="right"></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>Vat Payable:</b></h5></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>{{number_format($payable,2)}}</b></h5></td>
										</tr>
									</table>
									<br/>
							<?php } else if($type=='categorywise') { ?>	
							
									<table class="table" border="0">
										<thead>
											<th style="width:5%;">SI.#</th>
											<th style="width:10%;">Inv.#</th>
											<th style="width:10%;">Tr.Date</th>
											<th style="width:5%;">Tr.Type</th>
											<th style="width:15%;">Acc.Desc.</th>
											<th style="width:5%;">TRN</th>
											<th style="width:15%;" class="text-right">Inv.Gross Amt.</th>
											<th style="width:20%;" class="text-right">Inv.Net Amt.</th>
											<th style="width:15%;" class="text-right">Vat Amt.</th>
										</thead>
										<tbody>
											<?php $i=$totalin=0; foreach($reports as $key => $report) { ?>
											<tr>
												<td colspan="9"><b style="font-size:10pt;">Tax Code: {{$key}}</b></td>
											</tr>
											<?php if(sizeof($report['purchase']) > 0 ) { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>VAT INPUT </b></td>
											</tr>
										<?php 
											$total = $nettotal = $vattotal = 0;
											foreach($report['purchase'] as $row) { 
											$i++;
											$total += $row->total;
											$nettotal += $row->net_amount;
											$vattotal += $row->vat_amount;
										?>
											<tr>
												<td style="width:5%;">{{$i}}</td>
												<td style="width:10%;">{{$row->voucher_no}}</td>
												<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
												<td style="width:5%;">PI</td>
												<td style="width:15%;">{{$row->master_name}}</td>
												<td style="width:5%;">{{$row->vat_no}}</td>
												<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
												<td style="width:20%;" class="text-right">{{number_format($row->net_amount,2)}}</td>
												<td style="width:15%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
											</tr>
										<?php } $totalin += $vattotal; ?>
											<tr>
												<td colspan="6" align="right"><b>Total:</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
											<?php } ?>	
											<?php $i=$totalout=0; if(array_key_exists('sales',$report) && sizeof($report['sales']) > 0 )  { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>VAT OUTPUT </b></td>
											</tr>
											
											<?php  
													$total = $nettotal = $vattotal = 0;
													foreach($report['sales'] as $row) { 
													$i++;
													$total += $row->total;
													$nettotal += $row->net_total;
													$vattotal += $row->vat_amount;
											?>
												<tr>
													<td style="width:5%;">{{$i}}</td>
													<td style="width:10%;">{{$row->voucher_no}}</td>
													<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
													<td style="width:5%;">SI</td>
													<td style="width:15%;">{{$row->master_name}}</td>
													<td style="width:5%;">{{$row->vat_no}}</td>
													<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
													<td style="width:20%;" class="text-right">{{number_format($row->net_total,2)}}</td>
													<td style="width:15%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
												</tr>
											<?php } $totalout += $vattotal; ?>
											<tr>
												<td colspan="6" align="right"><b>Total:</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
												<?php } ?>
												
											<?php $i=$inputexp=0; if(array_key_exists('inputexp',$report) && sizeof($report['sales']) > 0 ) { ?>
											<tr>
												<td colspan="9" style="padding-left:70px;"><b>INPUT EXPENSE</b></td>
											</tr>
											
											<?php  
													$total = $nettotal = $vattotal = 0;
													foreach($report['inputexp'] as $row) { 
													$i++;
													$total += $row->total;
													$nettotal += $row->net_amount;
													$vattotal += $row->vat_amount;
											?>
												<tr>
													<td style="width:5%;">{{$i}}</td>
													<td style="width:10%;">{{$row->voucher_no}}</td>
													<td style="width:10%;">{{date('d-m-Y', strtotime($row->voucher_date))}}</td>
													<td style="width:5%;">SI</td>
													<td style="width:15%;">{{$row->master_name}}</td>
													<td style="width:5%;">{{$row->vat_no}}</td>
													<td style="width:15%;" class="text-right">{{number_format($row->total,2)}}</td>
													<td style="width:20%;" class="text-right">{{number_format($row->net_amount,2)}}</td>
													<td style="width:15%;" class="text-right">{{number_format($row->vat_amount,2)}}</td>
												</tr>
											<?php } $inputexp += $vattotal; ?>
											<tr>
												<td colspan="6" align="right"><b>Total:</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($total,2)}}</b></td>
												<td style="width:20%;" class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b>{{number_format($vattotal,2)}}</b></td>
											</tr>
												<?php } ?>
												
											<?php } ?>
										</tbody>
										
									</table>
									
									
									<br/>		
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
								
									<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>
									
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
                                            </span>
                                </button>
                                </span>
                        </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('vat_report/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="curr_from_date" value="{{$settings->from_date}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					</form>
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
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
