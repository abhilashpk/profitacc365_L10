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
            <!--section starts-->
            <h1>
                Job Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    Job Order
                </li>
				<li>
                    <a href="#">Report</a>
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
							<?php if($type=="detail") { ?>
								<table class="table" border="0">
									<body>
									<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
									@foreach($reports as $report)
									<tr>
										<td><b>JO.#:</b> {{$report[0]->voucher_no}}</td>
										<td><b>JO.Ref.#:</b> {{$report[0]->reference_no}}</td>
										<td><b>Vehicle No:</b> {{$report[0]->reg_no.' '.$report[0]->issue_plate.' '.$report[0]->code_plate}}</td>
										<td><b>Technician:</b> {{$report[0]->salesman}}</td>
										<td colspan="5" class="text-right"><b>Customer:</b> {{$report[0]->master_name}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>JO.Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>VAT Amt.</b></td>
										<td class="text-right"><b>Net Amt.</b></td>
									</tr>
										@foreach($report as $row)
										<?php 
											$qty_total += $row->quantity;
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
										<td><b>JO.#:</b> {{$report[0]->voucher_no}}</td>
										<td><b>JO.Ref.#:</b> {{$report[0]->reference_no}}</td>
										<td colspan="2"><b>Vehicle No:</b> {{$report[0]->reg_no.' '.$report[0]->issue_plate.' '.$report[0]->code_plate}}</td>
										<td colspan="2"><b>Technician:</b> {{$report[0]->salesman}}</td>
										<td colspan="3" class="text-right"><b>Customer:</b> {{$report[0]->master_name}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>JO.Qty.</b></td>
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
										<th>JO.#</th>
										<th>JO.Ref.#</th>
										<th>Vehicle No</th>
										<th>Customer</th>
										<th>Technician</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
										<th class="text-right">VAT Amt.</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
										<?php $total_net = $discount = $total_vat = $total_gross = 0;?>
										@foreach($reports as $row)
										<?php 
											  $total_gross += $row['total'];
										  $total_vat += $row['vat_amount'];
										  $total_net += $row['net_total']; 
										?>
										<tr>
											<td>{{$row['voucher_no']}}</td>
											<td>{{ $row['reference_no'] }}</td>
											<td>{{ $row['vehicle'] }}</td>
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
										<td></td>
										<td>{{$row['salesman']}}</td>
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
									<th>JO.#</th>
									<th>JO.Ref.#</th>
									<th>Vehicle No</th>
									<th>Customer</th>
									<th>Technician</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $total_net = $discount = $total_vat = $total_gross = 0;?>
									@foreach($reports as $row)
									<?php 
										  $total_gross += $row->total;
										  $total_vat += $row->vat_amount;
										  $total_net += $row->net_total;
										  $discount += $row->discount;
										  
									?>
									<tr>
										<td>{{$row->voucher_no}}</td>
										<td>{{ $row->reference_no }}</td>
										<td>{{$row->reg_no.' '.$row->issue_plate.' '.$row->code_plate}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->salesman}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
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
                                <span class="pull-left">
									<button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
												     <i class="fa fa-fw fa-times"></i>
                                                       Close 
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('sales_order/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="salesman" value="{{$salesman}}" >
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
