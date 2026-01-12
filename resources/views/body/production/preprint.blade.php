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
							<?php if($type=="detail") { ?>
								<table class="table" border="0">
									<body>
									<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
									@foreach($reports as $report)
									<tr>
										<td><b>SI.No:</b> {{$report[0]->voucher_no}}</td>
										<td><b>PrO.#:</b> {{$report[0]->voucher_no}}</td>
										<td><b>PrO.Ref.#:</b> {{$report[0]->reference_no}}</td>
										<td><b>Salesman:</b> {{$report[0]->salesman}}</td>
										<td colspan="5" class="text-right"><b>Customer:</b> {{$report[0]->master_name}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>PrO.Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>VAT Amt.</b></td>
										<td class="text-right"><b>Net Amt.</b></td>
									</tr>
										@foreach($report as $row)
										<?php $qty_total += $row->quantity;
										  $gross_total += $row->line_total;
										  $net_amount = $row->unit_vat + $row->line_total;
										  $vat_total += $row->unit_vat;
										  $net_total += $row->net_total;
										?>
										<tr>
											<td>{{$row->item_code}}</td>
											<td>{{ $row->description }}</td>
											<td class="text-right">{{$row->quantity}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($row->line_total,2)}}</td>
											<td class="text-right">{{number_format($row->unit_vat,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
										</tr>
										@endforeach
										<tr><td colspan="9"><br/></td></tr>
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
									</body>
								</table>
							<?php } elseif($type=="detail_pending") { ?>
								<table class="table" border="0">
									<body>
									<?php $poqty_total = $ponet_total = $invqty_total = $pndqty_total = $pndnet_total = 0; ?>
									@foreach($reports as $report)
									<tr>
										<td><b>SI.No:</b> {{$report[0]->voucher_no}}</td>
										<td><b>DO.#:</b> {{$report[0]->voucher_no}}</td>
										<td colspan="2"><b>DO.Ref.#:</b> {{$report[0]->reference_no}}</td>
										<td colspan="2"><b>Salesman:</b> {{$report[0]->salesman}}</td>
										<td colspan="3" class="text-right"><b>Customer:</b> {{$report[0]->master_name}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>DO.Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>Inv.Qty.</b></td>
										<td class="text-right"><b>Pending Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
									</tr>
										@foreach($report as $row)
										<?php 
											$total_amt = $row->quantity * $row->unit_price;
											$inv_qty = ($row->balance_quantity==0)?0:$row->quantity - $row->balance_quantity;
											$pending_qty = ($row->balance_quantity==0)?$row->quantity:$row->balance_quantity;
											$pending_amt = $pending_qty * $row->unit_price;
											
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
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($total_amt,2)}}</td>
											<td class="text-right">{{$inv_qty}}</td>
											<td class="text-right">{{$pending_qty}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($pending_amt,2)}}</td>
										</tr>
										@endforeach
										<tr><td colspan="9"><br/></td></tr>
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
									</body>
								</table>
							<?php } elseif($type=="summary_pending") { ?>
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>DO.#</th>
									<th>DO.Ref.#</th>
									<th>Customer</th>
									<th>Salesman</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body><?php $total_net = $discount = $total_vat = $total_gross = 0;?>

									@foreach($reports as $row)
									<?php 
											$total_gross += $row['total'];
										  $total_vat += $row['vat_amount'];
										  $total_net += $row['net_total']; 
										  
									?>
									<tr>
										<td>{{$row['voucher_no']}}</td>
										<td>{{ $row['voucher_no'] }}</td>
										<td>{{$row['reference_no']}}</td>
										<td>{{$row['master_name']}}</td>
										<td>{{$row['salesman']}}</td>
										<td class="text-right">{{number_format($row['total'],2)}}</td>
										<td class="text-right">{{number_format($row['discount'],2)}}</td>
										<td class="text-right">{{number_format($row['vat_amount'],2)}}</td>
										<td class="text-right">{{number_format($row['net_total'],2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
										<td class="text-right"><b>{{number_format($discount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
									</tr>
								</body>
							</table>
							<?php } else { ?>
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>PrO.#</th>
									<th>PrO.Ref.#</th>
									<th>Customer</th>
									<th>Salesman</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">Total Amt.</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $total_net = $discount = $total_vat = $total_gross =$total_amt= 0;?>
									@foreach($reports as $row)
									<?php 
										  $total_gross += $row->total;
										  $total_vat += $row->vat_amount;
										  $total_net += $row->net_total;
										  $discount += $row->discount;
										  $total_amt+=$row->total-$row->discount;
										  
									?>
									<tr>
										<td>{{$row->voucher_no}}</td>
										<td>{{ $row->voucher_no }}</td>
										<td>{{$row->reference_no}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->salesman}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->total-$row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_total,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($total_gross,2)}}</b></td>
										<td class="text-right"><b>{{number_format($row->discount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_amt,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_vat,2)}}</b></td>
										<td class="text-right"><b>{{number_format($total_net,2)}}</b></td>
									</tr>
								</body>
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
                                                <span style="color:#fff;">
												   <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
									</button>
								
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('production/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="salesman" value="{{$salesman}}" >
					</form>
                </div>
            </div>
            <!-- row -->
       
        </section>


{{-- page level scripts --}}
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

