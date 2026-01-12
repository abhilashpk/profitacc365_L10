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
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
												<th>SI.No</th>
												<th>GRN#</th>
												<th>GRN Ref.#</th>
												<th>Job No.</th>
												<th>Supplier Name</th>
												<th class="text-right">Gross Amt.</th>
												<th class="text-right">Discount</th>
												<th class="text-right">Total Amt.</th>
												<th class="text-right">VAT Amt.</th>
												<th class="text-right">Net Total</th>
											</thead>
											<body>
											<?php $total_net = $net_amount = $total_vat = $total_gross = $i =$amt=$net_amt= 0;?>
												@foreach($reports as $row)
												<?php 
													  $total = $row->total;
													  $vat_amount = $row->vat_amount;
													  $net_amount = $row->net_total;
													  $amt=$row->total-$row->discount;
													  $total_gross += $total;
													  $total_vat += $vat_amount;
													  $total_net += $net_amount;
													  $net_amt+=$amt;
													  $i++;
												?>
												<tr>
													<td>{{$i}}</td>
													<td>{{ $row->voucher_no }}</td>
													<td>{{$row->reference_no}}</td>
													<td>{{$row->jobcode}}</td>
													<td>{{$row->master_name}}</td>
													<td class="text-right">{{number_format($row->total,2)}}</td>
													<td class="text-right">{{number_format($row->discount,2)}}</td>
													<td class="text-right">{{number_format($amt,2)}}</td>
													<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
													<td class="text-right">{{number_format($row->net_total,2)}}</td>
												</tr>
												@endforeach
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
													<td class="text-right"></td>
														<td class="text-right">{{number_format($net_amt,2)}}</td>
													<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
													<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
												</tr>
											</body>
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
						<?php } else if($type=="summary_pending"){ ?>
								<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
												<th>SI.No</th>
												<th>GRN#</th>
												<th>GRN Ref.#</th>
												<th>Job No</th>
												<th>Supplier Name</th>
												<th class="text-right">Gross Amt.</th>
												<th class="text-right">Discount</th>
												<th class="text-right">Total Amt.</th>
												<th class="text-right">VAT Amt.</th>
												<th class="text-right">Net Total</th>
											</thead>
											<body>
											<?php 		
												$total_net = $net_amount = $total_vat = $total_gross =$amt=$net_amt= $i = 0;?>
												@foreach($reports as $row)
											<?php 
											     $amt=$row['total']-$row['discount'];
												  $total_gross += $row['total'];
												  $total_vat += $row['vat_amount'];
												  $total_net += $row['net_amount'];
												  $net_amt+=$amt;
												  $i++;
											?>
												<tr>
													<td>{{$i}}</td>
													<td>{{$row['voucher_no'] }}</td>
													<td>{{$row['reference_no']}}</td>
													<td>{{$row['jobcode']}}</td>
													<td>{{$row['master_name']}}</td>
													<td class="text-right">{{number_format($row['total'],2)}}</td>
													<td class="text-right">{{number_format($row['discount'],2)}}</td>
													<td class="text-right">{{number_format($amt,2)}}</td>
													<td class="text-right">{{number_format($row['vat_amount'],2)}}</td>
													<td class="text-right">{{number_format($row['net_amount'],2)}}</td>
												</tr>
												@endforeach
												
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
													<td class="text-right"></td>
													<td class="text-right"><b>{{number_format($net_amt,2)}}</b></td>
													<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
													<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
												</tr>
												
											</body>
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
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
											<body>
											<?php //$qty_total2 = $net_total2 = $vat_total2 = $gross_total2 = 0; ?>
											@foreach($reports as $report)
											<tr>
												<td><b>SI.No:</b> {{ ++$i }}</td>
												<td><b>GRN#:</b> {{$report[0]->voucher_no}}</td>
												<td><b>GRN Date</b> {{date('d-m-Y', strtotime($report[0]->voucher_date))}}</td>
												<td><b>GRN Ref.#:</b> {{$report[0]->reference_no}}</td>
												<td><b>Job No:</b> {{$report[0]->jobcode}}</td>
												<td colspan="3"><b>Supplier Name:</b> {{$report[0]->master_name}}</td>
											</tr>
											<tr>
												<td><b>Item Code</b></td>
												<td><b>Description</b></td>
												<td class="text-right"><b>GNR.Qty.</b></td>
												<td class="text-right"><b>Rate</b></td>
												<td class="text-right"><b>Total Amt.</b></td>
												<td class="text-right"><b>VAT Amt.</b></td>
												<td class="text-right"><b>Net Amt.</b></td>
											</tr>
											<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
												@foreach($report as $row)
											<?php 
												  $unit_price = ($cur=='')?$row->unit_price:($row->unit_price / $row->currency_rate);
												  $total_price = ($row->quantity * $unit_price)-$row->discount;
												  $unit_vat = ($cur=='')?$row->unit_vat:($row->unit_vat / $row->currency_rate);
												  $qty_total += $row->quantity;
												  $gross_total +=  $total_price;
												  $net_amount = $unit_vat + $total_price;
												  $vat_total += $unit_vat;
												  $net_total += $net_amount;
											?>
												<tr>
													<td>{{$row->item_code}}</td>
													<td>{{ $row->description }}</td>
													<td class="text-right">{{$row->quantity}}</td>
													<td class="text-right">{{number_format($unit_price,2)}}</td>
													<td class="text-right">{{number_format($total_price,2)}}</td>
													<td class="text-right">{{number_format($unit_vat,2)}}</td>
													<td class="text-right">{{number_format($net_amount,2)}}</td>
												</tr>
												@endforeach
												<tr>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{$qty_total}}</b></td>
													<td class="text-right"></td>
													<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
													<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
													<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
												</tr>
												<tr><td colspan="9"><br/></td></tr>
											@endforeach	
												<!--<tr>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{$qty_total}}</b></td>
													<td class="text-right"></td>
													<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
													<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
													<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
												</tr>-->
											</body>
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
							
						<?php } elseif($type=="detail_pending") { ?>
								<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
											<body>
											@foreach($reports as $report)
											<tr>
												<td><b>SI.No:</b> {{$report[0]->voucher_no}}</td>
												<td ><b>GNR#:</b> {{$report[0]->voucher_no}}</td>
												<td><b>GRN Date</b> {{date('d-m-Y', strtotime($report[0]->voucher_date))}}</td>
												<td ><b>GNR Ref.#:</b> {{$report[0]->reference_no}}</td>
												<td ><b>Job No:</b> {{$report[0]->jobcode}}</td>
												<td colspan="5"><b>Supplier Name:</b> {{$report[0]->master_name}}</td>
											</tr>
											<tr>
												<td><b>Item Code</b></td>
												<td><b>Description</b></td>
												<td class="text-right"><b>GNR.Qty.</b></td>
												<td class="text-right"><b>Rate</b></td>
												<td class="text-right"><b>Total Amt.</b></td>
												<td class="text-right"><b>Inv.Qty.</b></td>
												<td class="text-right"><b>Pending Qty.</b></td>
												<td class="text-right"><b>Rate</b></td>
												<td class="text-right"><b>Total Amt.</b></td>
											</tr>
											<?php $poqty_total = $ponet_total = $invqty_total = $pndqty_total = $pndnet_total = 0; ?>
												@foreach($report as $row)
												<?php 
													$unit_price = ($cur=='')?$row->unit_price:($row->unit_price / $row->currency_rate);
													$total_amt = $row->quantity * $unit_price;
													$inv_qty = ($row->balance_quantity==0)?0:$row->quantity - $row->balance_quantity;
													$pending_qty = ($row->balance_quantity==0)?$row->quantity:$row->balance_quantity;
													$pending_amt = $pending_qty * $unit_price;
													
													$poqty_total += $row->quantity;
													$ponet_total += $total_amt;
													$invqty_total += $inv_qty;
													$pndqty_total += $pending_qty;
													$pndnet_total += $pending_amt;
												?>
												<tr>
													<td>{{$row->item_code}}</td>
													<td>{{ $row->description }}</td>
													<td class="text-right">{{$row->quantity}}</td>
													<td class="text-right">{{number_format($unit_price,2)}}</td>
													<td class="text-right">{{number_format($total_amt,2)}}</td>
													<td class="text-right">{{$inv_qty}}</td>
													<td class="text-right">{{$pending_qty}}</td>
													<td class="text-right">{{number_format($unit_price,2)}}</td>
													<td class="text-right">{{number_format($pending_amt,2)}}</td>
												</tr>
												@endforeach
												<tr>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{$poqty_total}}</b></td>
													<td class="text-right"></td>
													<td class="text-right"><b>{{number_format($ponet_total,2)}}</b></td>
													<td class="text-right"><b>{{$invqty_total}}</b></td>
													<td class="text-right"><b>{{$pndqty_total}}</b></td>
													<td class="text-right"></td>
													<td class="text-right"><b>{{number_format($pndnet_total,2)}}</b></td>
												</tr>
												<tr><td colspan="9"><br/></td></tr>
											@endforeach
												
											</body>
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
						<?php } elseif($type=="qty_report") {?>
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
											<body>
											@foreach($reports as $report)
											<tr>
												
												<td ><b>GRN#:</b> {{$report[0]->voucher_no}}</td>
												<td><b>GRN Date</b> {{date('d-m-Y', strtotime($report[0]->voucher_date))}}</td>
												<td ><b>GRN Ref.#:</b> {{$report[0]->reference_no}}</td>
												<td ><b>Job No:</b> {{$report[0]->jobcode}}</td>
												<td ><b>Supplier Name:</b> {{$report[0]->master_name}}</td>
											</tr>
											<tr>
												<td><b>Item Code</b></td>
												<td><b>Description</b></td>
												<td class="text-right"><b>Ordered</b></td>
												
												<td class="text-right"><b>Processed</b></td>
												<td class="text-right"><b>Balance</b></td>
												
											</tr>
											<?php $poqty_total = $ponet_total = $invqty_total = $pndqty_total = $pndnet_total = 0; ?>
												@foreach($report as $row)
												<?php 
													$unit_price = ($cur=='')?$row->unit_price:($row->unit_price / $row->currency_rate);
													$total_amt = $row->quantity * $unit_price;
													$inv_qty = ($row->balance_quantity==0)?0:$row->quantity - $row->balance_quantity;
													$pending_qty = ($row->balance_quantity==0)?$row->quantity:$row->balance_quantity;
													$pending_amt = $pending_qty * $unit_price;
													
													$poqty_total += $row->quantity;
													$ponet_total += $total_amt;
													$invqty_total += $inv_qty;
													$pndqty_total += $pending_qty;
													$pndnet_total += $pending_amt;
												?>
												<tr>
													<td>{{$row->item_code}}</td>
													<td>{{ $row->description }}</td>
													<td class="text-right">{{$row->quantity}}</td>
													
													<td class="text-right">{{$inv_qty}}</td>
													<td class="text-right">{{$pending_qty}}</td>
													
												</tr>
												@endforeach
												<tr>
													<td></td>
													<td class="text-right"><b>Total:</b></td>
													<td class="text-right"><b>{{$poqty_total}}</b></td>
													
													
													<td class="text-right"><b>{{$invqty_total}}</b></td>
													<td class="text-right"><b>{{$pndqty_total}}</b></td>
													
												</tr>
												<tr><td colspan="9"><br/></td></tr>
											@endforeach
												
											</body>
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
						
						
						<?php } ?>
						
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$voucherhead}}</u></b></b></td>
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
                                <span class="pull-right">
                                             <button type="button" onclick="javascript:window.print();" 
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
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
                    </div>
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('suppliers_do/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<input type="hidden" name="currency_id" value="{{$curid}}" >
						<input type="hidden" name="job_id[]" value="{{$jobids[0]}}" >
						<input type="hidden" name="supplier_id" value="{{$supplierid}}" >
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
