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
<div>
    <!-- Left side column. contains the logo and sidebar -->
    
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
					<?php if(count($reports) > 0) { 
						if($type=='summary') { ?>
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
									<tr>
										<td align="left" valign="top" style="padding-left:0px;"><br/>
										</td>
										<td align="right" style="padding-left:0px;">
											<?php if($fromdate!='' && $todate!='') { ?><p>From: {{$fromdate}} - To: {{$todate}}</p><?php } ?>
										</td>
									</tr>
								</thead>
								
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table class="tblstyle table-bordered" width="100%">
										<thead>
                                <th>SI.#</th>
									
									<th class="text-right">PI.No</th>
									<th class="text-right">PI.Date</th>
									<th class="text-right">Supplier Name</th>
									<th class="text-right">TRN No.</th>
								  
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
											<tbody>
											<?php $gross_total = $discount_total = $vat_total = $net_total = 0; ?>
									@foreach($reports as $row)
									<?php $gross_total += $row->total;
										  $discount_total += $row->discount;
										  $vat_total += $row->vat_amount;
										  $net_total += $row->net_amount; ?>
												<tr>
                                    <td >{{ ++$i }}</td>
										
										<td class="text-right">{{ $row->voucher_no }}</td>
										<td class="text-right">{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										<td class="text-right">{{$row->master_name}}</td>
										<td class="text-right">{{$row->vat_no}}</td>
                                       
										
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_amount,2)}}</td>
									</tr>
												@endforeach
												<tr>
									
										<td></td>
										<td></td><td></td>
                                        <td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
									</tr>
											</tbody>
										</table>
										</td>
									</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
						<?php } else if($type=='detail') { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								
							</thead>
							
							<tbody id="bod">
								<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
								foreach($reports as $report) { ?>
								<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Sup.Name: {{$report[0]['supplier']}}<br/>
											  Item Name: {{$report[0]['item_name']}}
											</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="7%"><b>SI.#</b></td>
											<td width="13%"><b>PO.No</b></td>
											<td width="20%"><b>PI.No</b></td>
											<td width="8%"><b>PI.Date</b></td>
											
											<td width="12%" class="text-right"><b>Gross Amt.</b></td>
											<td width="8%" class="text-right"><b>Discount</b></td>
											<td width="10%" class="text-right"><b>VAT Amt.</b></td>
											<td width="10%" class="text-right"><b>Net Total</b></td>
											
										</tr>
										
										<?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
											foreach($report as $row) { 
												$sprice = $row['subtotal'] ;
												if($row['class_id']==1)
													$cost = $row['sale_cost'];
												else
													$cost = 0;
												$pprice = $row['vat_amount'] ;
												$profit =  $row['net_amount'];
												$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i;
												$sqty += $row['squantity'];
												$tcst += $pprice;
										?>
										
										
										<tr>
											<td>{{$i++}}</td>
											<td>{{$row['document_id']}}</td>
											<td >{{$row['voucher_no']}}</td>
											<td >{{date('d-m-Y',strtotime($report[0]['voucher_date']))}}</td>
											<td class="text-right">{{number_format($sprice,2)}}</td>
											<td class="text-right">{{number_format($row['discount'],2)}}</td>
											<td class="text-right">{{number_format($pprice,2)}}</td>
											<td class="text-right">{{number_format($profit,2)}}</td>
											
										</tr>
									<?php } $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $ptotal;
										$nqty += $sqty;
									?>
									
									<tr>
										<td colspan="4" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
										<td class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
									
									</tr>
									</table>
									</td>
								</tr>
							  <?php } ?>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="48%" colspan="4" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											
										</tr>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
								</tr>
							</tfoot>
						</table>
						<?php }else if($type=='invoice') { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								
							</thead>
							
							<tbody id="bod">
								<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
								foreach($reports as $report) { ?>
								<tr>
									<td align="left" valign="top" style="padding-left:0px;">
										<h6><b>Inv.#: {{$report[0]['voucher_no']}}<br/>
										Cust.Name: {{$report[0]['supplier']}}</b></h6>
									</td>
									<td align="right" style="padding-left:0px;">
										<p>Date: {{date('d-m-Y',strtotime($report[0]['voucher_date']))}}</p>
									</td>
								</tr>
								
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="7%"><b>SI.#</b></td>
											<td width="13%"><b>Item Code</b></td>
											<td width="20%"><b>Description</b></td>
											<td width="8%"><b>Qty.</b></td>
											<td width="12%" class="text-right"><b>Sale Price</b></td>
											<td width="8%" class="text-right"><b>Discount</b></td>
											<td width="10%" class="text-right"><b>Cost</b></td>
											<td width="12%" class="text-right"><b>Profit</b></td>
											<td width="10%" class="text-right"><b>Profit%</b></td>
										</tr>
										
										<?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
											foreach($report as $row) { 
												$sprice = $row['squantity'] * $row['sunit_price'];
												if($row['class_id']==1)
													$cost = $row['sale_cost'];
												else
													$cost = 0;
												$pprice = $row['squantity'] * $cost;
												$profit = $sprice - $pprice - $row['discount'];
												$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
												$sptotal += $sprice;
												$dtotal += $row['discount'];
												$ctotal += $cost;
												$ptotal += $profit;
												$pertotal += $percentage; $n = $i;
												$sqty += $row['squantity'];
												$tcst += $pprice;
										?>
										
										<tr>
											<td>{{$i++}}</td>
											<td>{{$row['item_code']}}</td>
											<td >{{$row['description']}}</td>
											<td>{{$row['squantity']}}</td>
											<td class="text-right">{{number_format($sprice,2)}}</td>
											<td class="text-right">{{number_format($row['discount'],2)}}</td>
											<td class="text-right">{{number_format($pprice,2)}}</td>
											<td class="text-right">{{number_format($profit,2)}}</td>
											<td class="text-right">{{number_format($percentage,2)}}</td>
										</tr>
									<?php } $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $ptotal;
										$nqty += $sqty;
									?>
									
									<tr>
										<td colspan="4" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
										<td class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($peravg,2)}}</b></td>
									</tr>
									</table>
									</td>
								</tr>
							  <?php } ?>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="48%" colspan="4" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											<td width="10%" class="text-right"></td>
										</tr>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
								</tr>
							</tfoot>
						</table>

						<?php } else if($type=='supplier') { ?>
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
									<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Cust.#: {{$report[0]['account_id']}}<br/>
											Sup.Name: {{$report[0]['supplier']}}</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
									
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="7%"><b>Inv.#</b></td>
												<td width="13%"><b>Inv. Date</b></td>
												<td width="12%" class="text-right"><b>Gross Amount</b></td>
												<td width="8%" class="text-right"><b>Discount</b></td>
												<td width="10%" class="text-right"><b>Vat.Amount</b></td>
												<td width="12%" class="text-right"><b>Net.Total</b></td>
												
											</tr>
											
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
													
												$sptotal += $row['total'];
												$dtotal += $row['discount'];
												$ctotal += $row['vat_amount'];
												$ptotal += $row['net_amount'];
											
												$n = $i; $i++;
											?>
											
											<tr>
												<td >{{$row['voucher_no']}}</td>
												<td >{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
												<td class="text-right">{{number_format($row['total'],2)}}</td>
												<td class="text-right">{{number_format($row['discount'],2)}}</td>
												<td  class="text-right">{{number_format($row['vat_amount'],2)}}</td>
												<td  class="text-right">{{number_format($row['net_amount'],2)}}</td>
												
											</tr>
										<?php } $peravg = $pertotal / $n;
										
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $ctotal;
										$nptotal += $ptotal;
										//$nqty += $sqty;
									?>
										
										<tr>
											<td colspan="2" align="right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
											
										</tr>
										
										</table>
										</td>
									</tr>
								  <?php } ?>
								  
								  	<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="20%" colspan="2" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											
										</tr>
									</table>
									</td>
								</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
							<?php } else if($type=='salesman') { ?>
							
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php 
									$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
									foreach($reports as $report) { ?>
									<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Salesman: {{$report[0]['salesman']}}</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p>Date: {{date('d-m-Y',strtotime($report[0]['voucher_date']))}}</p>
										</td>
									</tr>
									
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="7%"><b>SI.#</b></td>
												<td width="13%"><b>Item Code</b></td>
												<td width="20%"><b>Description</b></td>
												<td width="8%"><b>Qty.</b></td>
												<td width="12%" class="text-right"><b>Sale Price</b></td>
												<td width="8%" class="text-right"><b>Discount</b></td>
												<td width="10%" class="text-right"><b>Cost</b></td>
												<td width="12%" class="text-right"><b>Profit</b></td>
												<td width="10%" class="text-right"><b>Profit%</b></td>
											</tr>
											
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
												foreach($report as $row) { 
													$sprice = $row['squantity'] * $row['sunit_price'];
													if($row['class_id']==1)
														$cost = $row['sale_cost'];
													else
														$cost = 0;
													$pprice = $row['squantity'] * $cost;
													$profit = $sprice - $pprice - $row['discount'];
													$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
													$sptotal += $sprice;
													$dtotal += $row['discount'];
													$ctotal += $cost;
													$ptotal += $profit;
													$pertotal += $percentage; $n = $i;
													$sqty += $row['squantity'];
													$tcst += $pprice;
											?>
											
											<tr>
												<td>{{$i++}}</td>
												<td>{{$row['item_code']}}</td>
												<td >{{$row['description']}}</td>
												<td>{{$row['squantity']}}</td>
												<td class="text-right">{{number_format($sprice,2)}}</td>
												<td class="text-right">{{number_format($row['discount'],2)}}</td>
												<td class="text-right">{{number_format($pprice,2)}}</td>
												<td class="text-right">{{number_format($profit,2)}}</td>
												<td class="text-right">{{number_format($percentage,2)}}</td>
											</tr>
										<?php } $peravg = $pertotal / $n; 
											$nsptotal += $sptotal;
											$ndtotal += $dtotal;
											$nctotal += $tcst;
											$nptotal += $ptotal;
											$nqty += $sqty;
										?>
										
										<tr>
											<td colspan="4" align="right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
											<td class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($peravg,2)}}</b></td>
										</tr>
										</table>
										</td>
									</tr>
								  <?php } ?>
									<tr style="border:0px solid black;">
										<td colspan="2" align="center"><br/>
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
										
											<tr>
												<td width="48%" colspan="4" align="right"><b>Net Total:</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
												<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
												<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
												<td width="10%" class="text-right"></td>
											</tr>
										</table>
										</td>
									</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
						
							<?php } else if($type=='summarysalesman') { ?>
								<table border="0" style="width:100%;height:100%;">
									<thead>
										<tr>
											<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
										</tr>
										<tr>
											<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
										</tr>
										
									</thead>
									
									<tbody id="bod">
										<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
										<tr>
											<td align="left" valign="top" style="padding-left:0px;">
												<h6><b>Salesman: {{$report[0]['salesman']}}</b></h6>
											</td>
											<td align="right" style="padding-left:0px;">
												<p></p>
											</td>
										</tr>
										
										<tr style="border:0px solid black;">
											<td colspan="2" align="center">
											<table border="0" style="width:100%;" class="tblstyle table-bordered">
												<tr>
													<td width="7%"><b>Inv.#</b></td>
													<td width="13%"><b>Inv. Date</b></td>
													<td width="12%" class="text-right"><b>Sale Price</b></td>
													<td width="8%" class="text-right"><b>Discount</b></td>
													<td width="10%" class="text-right"><b>Cost</b></td>
													<td width="12%" class="text-right"><b>Profit</b></td>
													<td width="10%" class="text-right"><b>Profit%</b></td>
												</tr>
												
												<?php 
													$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
													foreach($report as $row) { 
													
													$sptotal += $row['sprice'];
													$dtotal += $row['discount'];
													$ctotal += $row['cost'];
													$ptotal += $row['profit'];
													$pertotal += $row['percentage'];
													$n = $i; $i++;
												?>
												
												<tr>
													<td >{{$row['voucher_no']}}</td>
													<td >{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
													<td class="text-right">{{number_format($row['sprice'],2)}}</td>
													<td class="text-right">{{number_format($row['discount'],2)}}</td>
													<td  class="text-right">{{number_format($row['cost'],2)}}</td>
													<td  class="text-right">{{number_format($row['profit'],2)}}</td>
													<td  class="text-right">{{number_format($row['percentage'],2)}}</td>
												</tr>
											<?php } $peravg = $pertotal / $n;
											
											$nsptotal += $sptotal;
											$ndtotal += $dtotal;
											$nctotal += $ctotal;
											$nptotal += $ptotal;
											//$nqty += $sqty;
										?>
											
											<tr>
												<td colspan="2" align="right"><b>Sub Total:</b></td>
												<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
												<td  class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
												<td class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
												<td  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
												<td  class="text-right"><!--<b>{{number_format($peravg,2)}}</b>--></td>
											</tr>
											
											</table>
											</td>
										</tr>
									  <?php } ?>
									  
										<tr style="border:0px solid black;">
										<td colspan="2" align="center"><br/>
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
										
											<tr>
												<td width="20%" colspan="2" align="right"><b>Net Total:</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
												<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
												<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
												<td width="10%" class="text-right"></td>
											</tr>
										</table>
										</td>
									</tr>
									</tbody>
									<tfoot id="inv">
										<tr>
											<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
										</tr>
									</tfoot>
								</table>
							<?php } else if($type=='area') { ?>
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
									<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Cust.#: {{$report[0]['account_id']}}<br/>
											Cust.Name: {{$report[0]['supplier']}}</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
									
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="7%"><b>Inv.#</b></td>
												<td width="13%"><b>Inv. Date</b></td>
												<td width="12%" class="text-right"><b>Sale Price</b></td>
												<td width="8%" class="text-right"><b>Discount</b></td>
												<td width="10%" class="text-right"><b>Cost</b></td>
												<td width="12%" class="text-right"><b>Profit</b></td>
												<td width="10%" class="text-right"><b>Profit%</b></td>
											</tr>
											
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
												
												$sptotal += $row['sprice'];
												$dtotal += $row['discount'];
												$ctotal += $row['cost'];
												$ptotal += $row['profit'];
												$pertotal += $row['percentage'];
												$n = $i; $i++;
											?>
											
											<tr>
												<td >{{$row['voucher_no']}}</td>
												<td >{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
												<td class="text-right">{{number_format($row['sprice'],2)}}</td>
												<td class="text-right">{{number_format($row['discount'],2)}}</td>
												<td  class="text-right">{{number_format($row['cost'],2)}}</td>
												<td  class="text-right">{{number_format($row['profit'],2)}}</td>
												<td  class="text-right">{{number_format($row['percentage'],2)}}</td>
											</tr>
										<?php } $peravg = $pertotal / $n;
										
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $ctotal;
										$nptotal += $ptotal;
										//$nqty += $sqty;
									?>
										
										<tr>
											<td colspan="2" align="right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
											<td  class="text-right"><!--<b>{{number_format($peravg,2)}}</b>--></td>
										</tr>
										
										</table>
										</td>
									</tr>
								  <?php } ?>
								  
								  	<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="20%" colspan="2" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											<td width="10%" class="text-right"></td>
										</tr>
									</table>
									</td>
								</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
						<?php } else if($type=='group') { ?>
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
									<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Group.#: <br/>
											Group.Name: </b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
									
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="7%"><b>Inv.#</b></td>
												<td width="13%"><b>Inv. Date</b></td>
												<td width="12%" class="text-right"><b>Sale Price</b></td>
												<td width="8%" class="text-right"><b>Discount</b></td>
												<td width="10%" class="text-right"><b>Cost</b></td>
												<td width="12%" class="text-right"><b>Profit</b></td>
												<td width="10%" class="text-right"><b>Profit%</b></td>
											</tr>
											
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
												foreach($report as $row) { 
												
												$sptotal += $row['sprice'];
												$dtotal += $row['discount'];
												$ctotal += $row['cost'];
												$ptotal += $row['profit'];
												$pertotal += $row['percentage'];
												$n = $i; $i++;
											?>
											
											<tr>
												<td >{{$row['voucher_no']}}</td>
												<td >{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
												<td class="text-right">{{number_format($row['sprice'],2)}}</td>
												<td class="text-right">{{number_format($row['discount'],2)}}</td>
												<td  class="text-right">{{number_format($row['cost'],2)}}</td>
												<td  class="text-right">{{number_format($row['profit'],2)}}</td>
												<td  class="text-right">{{number_format($row['percentage'],2)}}</td>
											</tr>
										<?php } $peravg = $pertotal / $n;
										
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $ctotal;
										$nptotal += $ptotal;
										//$nqty += $sqty;
									?>
										
										<tr>
											<td colspan="2" align="right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
											<td  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
											<td  class="text-right"><!--<b>{{number_format($peravg,2)}}</b>--></td>
										</tr>
										
										</table>
										</td>
									</tr>
								  <?php } ?>
								  
								  	<tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="20%" colspan="2" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											<td width="10%" class="text-right"></td>
										</tr>
									</table>
									</td>
								</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
						<?php } else if($type=="item") { ?>
								<table border="0" style="width:100%;height:100%;">
										<thead>
											<tr>
												<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
											</tr>
											<tr>
												<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
											</tr>
											
										</thead>
										
										<tbody id="bod">
											<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
											<tr>
												<td align="left" valign="top" style="padding-left:0px;">
													<h6><b>
													Item Name: {{$report[0]['item_name']}}</b></h6>
												</td>
												<td align="right" style="padding-left:0px;">
													<p></p>
												</td>
											</tr>
											
											<tr style="border:0px solid black;">
												<td colspan="2" align="center">
												<table border="0" style="width:100%;" class="tblstyle table-bordered">
													<tr>
														<td width="7%"><b>Inv.#</b></td>
														<td width="10%"><b>Inv. Date</b></td>
														<td width="15%"><b>Sup Name</b></td>
														<th width="8%">Qty.</th>
														<td width="10%" class="text-right"><b>Price</b></td>
														<td width="8%" class="text-right"><b>Discount</b></td>
														<td width="10%" class="text-right"><b>Vat.Amount</b></td>
														<td width="12%" class="text-right"><b>Net.Total</b></td>
														
													</tr>
													
													<?php 
														$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $tcst = 0; $i=1;
														foreach($report as $row) { 
														$sprice =  $row['unit_price'];
														if($row['class_id']==1)
															$cost = $row['sale_cost'];
														else
															$cost = 0;
														$pprice = $row['vat_amount'] ;
														$profit = $row['total_price'];
														$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
														$sptotal += $sprice;
														$dtotal += $row['discount'];
														$ctotal += $cost;
														$ptotal += $profit;
														$pertotal += $percentage; $n = $i;$i++;
														$tcst += $pprice;
													?>
													<tr>
														<td >{{$row['voucher_no']}}</td>
														<td >{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
														<td >{{$row['supplier']}}</td>
														<td >{{$row['quantity']}}</td>
														<td  class="text-right">{{number_format($sprice,2)}}</td>
														<td  class="text-right">{{number_format($row['discount'],2)}}</td>
														<td  class="text-right">{{number_format($pprice,2)}}</td>
														<td class="text-right">{{number_format($profit,2)}}</td>
														
													</tr>
												<?php } $peravg = $pertotal / $n; 
												        $nsptotal += $sptotal;
                										$ndtotal += $dtotal;
                										$nctotal += $tcst;
                										$nptotal += $ptotal;
												?>
												
												<tr>
													<td colspan="4" align="right"><b>Sub Total:</b></td>
													<td  class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
													<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
													<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
												<td  class="text-right"><b>{{number_format($ptotal,2)}}</b></td> 
												
												</tr>
												
												</table>
												</td>
											</tr>
										  <?php } ?>
    										  <tr style="border:0px solid black;">
            									<td colspan="2" align="center"><br/>
            									<table border="0" style="width:100%;" class="tblstyle table-bordered">
            									
            										<tr>
            											<td width="40%" colspan="2" align="right"><b>Net Total:</b></td>
            											<td width="10%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
            											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
            											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
            											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
            											
            										</tr>
            									</table>
            									</td>
            								</tr>
										</tbody>
										<tfoot id="inv">
											<tr>
												<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
											</tr>
										</tfoot>
									</table>
						<?php } //end if ?>
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php } ?>
						</div>
                    </div>
					<div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span>
								<span class="pull-right">
                                           
									 <button type="button" onclick="javascript:window.print();"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
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
								
                                </span>
                        </div>
                    </div>
					
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('purchase_invoice/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="customer_id" value="{{$supplier}}" >
					<input type="hidden" name="item_id" value="{{$item}}" >
					</form>
					
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
<script>
function getExport() {
	document.frmExport.submit();
}

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
