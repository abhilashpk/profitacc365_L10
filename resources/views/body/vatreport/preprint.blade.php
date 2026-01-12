<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
  


        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if($type=='summary') { ?>
									<table class="table" border="0">
										<thead>
											<tr>
												<th>VAT Account Name</th>
												<th class="text-right">Gross Amt.</th>
												<th class="text-right">Discount</th>
												<th class="text-right">Taxable Amt.</th>
												<th class="text-right">VAT Amount</th>
												<th class="text-right">Net Amount</th>
											</tr>
										</thead>
										<tbody>
										@php $i=0;  $vat=$totalvat=0; @endphp
										   @if($reports!='')
											@foreach($reports as $report)
											
											{{-- @if($report->vat_amt > 0) --}}
											@php $rname = $report->name; @endphp
											<tr>
												<td>{{ $report->name.$report->sub }}</td>
												<td class="text-right">{{number_format($report->gross_amt,2)}}</td>
												<td class="text-right">{{number_format($report->discount,2)}}</td>
												<td class="text-right">{{number_format($report->taxable,2)}}</td>
												<td class="text-right">
												@if($report->vat_amt < 0)
													{{'('.number_format(($report->vat_amt*-1),2).')'}}
												@else
													{{ number_format($report->vat_amt,2)}}
												@endif
												@php $totalvat += $report->vat_amt; @endphp
												</td>
												<td class="text-right">{{number_format($report->net_amt,2)}}</td>
											</tr>
											{{-- @endif --}}
											@if($report->end==true)
											<tr>
												<td colspan="4" class="text-right"><b>Total {{$rname}}:</b></td>
												<td class="text-right"><b>{{number_format($totalvat, 2)}}</b></td>
												<td></td>
											</tr>
											@php $totalvat = 0; @endphp
											@endif
											
											@endforeach
											@endif
											<tr>
												<td colspan="4" class="text-right"><b>Total VAT Payable:</b></td>
												<td class="text-right"><b>
												@if($payable < 0)
												{{'('.number_format($payable * -1, 2).')'}}
												@else
												{{number_format($payable, 2)}}
												@endif
												</b></td>
												<td></td>
												
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
												<strong>Discount</strong>
											</th>
											<th class="text-right">
												<strong>Taxable Amt.</strong>
											</th>
											<th class="text-right">
												<strong>VAT Amt.</strong>
											</th>
											<th class="text-right">
												<strong>Net Amt.</strong>
											</th>
										</tr>
										</thead>
										<tbody>
										
										@php $payable = $vatinput = $vatoutput = 0; @endphp
										@foreach($reports as $key => $report) 
											<tr><td colspan="9"><strong>{{$report[0]->vat_name}}</strong></td></tr>
											@php $i = $dramount = $cramount = $vatamount = $gtotal = $dstotal = $tatotal = $nmttal = 0; @endphp
											@foreach($report as $row)
												<?php //@if($row->trtype==$row->transaction_type) ?>
												@php $i++; $vatname = $row->vat_name; @endphp
													@if($row->trtype=='Dr')
														@php $dramount += $row->vat_amount; @endphp
													@else
														@php $cramount += $row->vat_amount; @endphp
													@endif
												
												<tr> <td>{{$i}}</td>
													<td>{{$row->voucher_no}}</td>
													<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
													<td>{{$row->voucher_type}}</td>
													<td>{{$row->master_name}}</td>
													<td>{{$row->trn_no}}</td>
													@php 
														$gross_total = ($row->voucher_type=='PC' || $row->voucher_type=='SIN'|| $row->voucher_type=='JV' || $row->voucher_type=='PV')?($row->net_total - $row->vat_amount):$row->gross_total;
														//$subtotal=($row->voucher_type=='PI' || $row->voucher_type=='PR' || $row->voucher_type=='PS'|| $row->voucher_type=='SI'|| $row->voucher_type=='SR'|| $row->voucher_type=='SS')?($row->gross_total - $row->discount):$row->subtotal;
														//$nettotal=($row->voucher_type=='PI' || $row->voucher_type=='PR' || $row->voucher_type=='PS'|| $row->voucher_type=='SI'|| $row->voucher_type=='SR'|| $row->voucher_type=='SS')?($subtotal+$row->vat_amount):$row->net_total;
														$vatamount += ($row->trtype=='Dr')?$row->vat_amount:($row->vat_amount*-1);
														//$payable += $row->vat_amount;
														$gtotal +=($row->trtype=='Dr')?$gross_total:($gross_total*-1); //$gross_total;
														$dstotal += $row->discount;
														$tatotal +=($row->trtype=='Dr')?$row->subtotal:($row->subtotal*-1) ;//$row->subtotal;
														$nmttal += ($row->trtype=='Dr')?$row->net_total:($row->net_total*-1); //$row->net_total;
													@endphp
													<td class="emptyrow text-right">{{($row->trtype=='Dr')?number_format($gross_total,2):'('.number_format($gross_total,2).')'}}</td>
													<td class="emptyrow text-right">{{number_format($row->discount,2)}}</td>
													<td class="emptyrow text-right">{{($row->voucher_type=='JV')?($row->subtotal-$row->vat_amount):(($row->trtype=='Dr')?number_format($gross_total,2):'('.number_format($gross_total,2).')')}}</td> 
													<td class="emptyrow text-right">{{($row->trtype=='Dr')?number_format($row->vat_amount,2):'('.number_format($row->vat_amount,2).')'}}</td>
													<td class="emptyrow text-right">{{($row->trtype=='Dr')?number_format($row->net_total,2):'('.number_format($row->net_total,2).')'}}</td>
												</tr>
												<?php //@endif ?>
											@endforeach
											@php $amount = $dramount - $cramount; $payable += $amount; $vatar[$key]['name'] = $vatname; $vatar[$key]['amt'] = $amount; @endphp
											<tr><td colspan="6" class="text-right"><strong>{{$vatname}} Total:</strong></td>
											    <td  class="text-right"><strong>{{number_format($gtotal,2)}}</strong></td>
											    <td  class="text-right"><strong>{{number_format($dstotal,2)}}</strong></td>
											    <td  class="text-right"><strong>{{number_format($tatotal,2)}}</strong></td>
												<td  class="text-right"><strong>{{number_format($vatamount,2)}}</strong></td>
												<td  class="text-right"><strong>{{number_format($nmttal,2)}}</strong></td>
											</tr>
										@endforeach
										
										</tbody>
									</table>
									
									<table border="0" style="width:100%;">
									    @if(isset($vatar))
									    @foreach($vatar as $val)
										<tr>
											<td style="width:30%;"></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>{{$val['name']}} Total:</b></h6></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><h6><b>
											    {{number_format($val['amt'],2)}}</b></h6></td>
										</tr>
										@endforeach
										@endif
										<tr>
											<td style="width:30%;"></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>VAT Payable:</b></h5></td>
											<td style="width:15%; padding-right:10px; border-top:1px solid black;" class="text-right"><h5><b>
											    @if($payable < 0)
												{{'('.number_format($payable*-1,2).')'}}
												@else
												{{number_format($payable,2)}}
												@endif
											    </b></h5></td>
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
									
								<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
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
					<input type="hidden" name="code_type" value="{{$codetype}}" >
					<input type="hidden" name="department_id" value="{{$department}}" >
					</form>
                    </div>
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>


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
