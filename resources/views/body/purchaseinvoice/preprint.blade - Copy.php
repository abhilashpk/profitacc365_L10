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
            <h1>
                Job Invoice
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Job Invoice Report</a>
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
						<?php if(count($reports) > 0) { 
						if($type=='summary') { ?>
								<table class="table" border="0">
									<thead>
										<th>TO</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
									<th class="text-right">Vat Amount</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$vat_amt=$amount_total=0; ?>
										@foreach($report as $row)
										<?php $total += $row->total;
											  $discount += $row->discount;
											$vat_amt += $row->vat_amount;
											  $amount_total += $row->net_amount;
										?>
										@endforeach
										<tr>
											<td><b>{{$key}}</b></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount,2)}}</b></td>
										<td class="text-right"><b>{{number_format($vat_amt,2)}}</b></td>
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amt;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
									<tr>
								
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
												<td class="text-right"><b>{{number_format($netvat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="detail") { ?>
								<table class="table" border="0">
									<thead>
										
										<th>PI.No</th>
										<th>PI. Date</th>
										<th> Item Name</th> 
										<th> Credit Account</th> 
										<th> Quantity</th> 
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
										
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
									
										
										@foreach($report as $row)
										<?php $total += $row->unit_price;
											  $discount += $row->discount;
											
											  $amount_total += $row->total_price;
										?>
										
										<td>{{ $row->voucher_no }} </td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										
											
											<td>{{$row->item_name}}</td>
											<td>{{$row->account}}</td>
											<td >{{$row->quantity}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($row->discount,2)}}</td>
									
											<td class="text-right">{{number_format($row->total_price,2)}}</td>
										</tr>
										@endforeach
									
										
										<tr>
										<td></td>
											
											
											<td></td>
											<td></td>
											<td></td>
											<td><b>Total: </b></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount,2)}}</b></td>
											
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Net Total: </b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
											
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="job_orderclosed") { ?>
								
								<table class="table" border="0">
									<thead>
										
										<th>JI.No</th>
										<th>Date</th>
										<!-- <th>Customer Name</th> -->
										<th>Salesman</th>
									
										<th >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td>{{$row->salesman}}</td>
									
										
											<td>{{number_format($row->line_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->line_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
											
											
											<td></td>
											<td><b>Total: </b></td>
											
											<td ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											
											
											<td></td>
											<td><b>Net Total: </b></td>
											
											<td ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
								
							<?php } elseif($type=="job_order") { ?>
								<table class="table" border="0">
									<thead>
										
										<th>JI.No</th>
										<th>Date</th>
										<!-- <th>Customer Name</th> -->
										<th>Salesman</th>
									
										<th >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td>{{$row->salesman}}</td>
									
										
											<td>{{number_format($row->line_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->line_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
											
											
											<td></td>
											<td><b>Total: </b></td>
											
											<td ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											
											
											<td></td>
											<td><b>Net Total: </b></td>
											
											<td ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } else if($type=='vehicle') { ?>
								<table class="table" border="0">
									<thead>
										<th>JO.No</th>
										<th>JI.No</th>
										<th>JI.Date</th>
										<th>Item Name</th>
										<th>Vehicle No</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
										<th class="text-right">VAT Amt.</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
										<?php $gross_total = $discount_total = $vat_total = $net_total = 0; ?>
										@foreach($reports as $row)
										<?php $gross_total += $row->total;
											  $discount_total += $row->discount;
											  $vat_total += $row->vat_amount;
											  $net_total += $row->net_total; ?>
										<tr>
											<td>{{ $row->document_id }}</td>
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td>{{$row->description}}</td>
											<td>{{$row->reg_no}}</td>
											<td class="text-right">{{number_format($row->total,2)}}</td>
											<td class="text-right">{{number_format($row->discount,2)}}</td>
											<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($row->net_total,2)}}</td>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td><td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } else { ?>
							<table class="table" border="0">
								<thead>
									<th>JO.No</th>
									<th>JI.No</th>
									<th>JI.Date</th>
									<th>Customer Name</th>
									<th>TRN No.</th>
									<th>Vehicle No</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $gross_total = $discount_total = $vat_total = $net_total = 0; ?>
									@foreach($reports as $row)
									<?php $gross_total += $row->total;
										  $discount_total += $row->discount;
										  $vat_total += $row->vat_amount;
										  $net_total += $row->net_total; ?>
									<tr>
										<td>{{ $row->document_id }}</td>
										<td>{{ $row->voucher_no }}</td>
										<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->vat_no}}</td>
										<td>{{$row->reg_no}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_total,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
									</tr>
								</body>
							</table>
							<?php } ?>
							
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									</td>
								</tr>
								<tr>
									
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
                               <!-- <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span> -->
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
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
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
