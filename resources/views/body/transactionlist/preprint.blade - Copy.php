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
                Transaction List
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Reports
                </li>
				<li>
                    Transaction List
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
									<td width="60%" align="center" colspan="2"><h4><u>{{$voucherhead}}</u></h4>
								</tr>
							</table><br/>
                        </div>
						<p><b><?php echo ($fromdate=='')?'':'Date From: '.$fromdate;?></b> <b><?php echo ($todate=='')?'':'&nbsp; To: '.$todate;?></b></p>
                        <div class="col-md-12">
							
								<table class="table" border="0">
									<body>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Other Cost</b></td>
										<td class="text-right"><b>Net Cost</b></td>
										<td class="text-right"><b>VAT Amt.</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
									</tr>
									<?php $qty_gtotal = $gross_gtotal = $vat_gtotal = 0; ?>
									@foreach($reports as $report)
									<tr>
										<td><b>Inv.No:</b> {{$report[0]->voucher_no}} <br> Cust.Name: {{$report[0]['supplier']}}</td>
										<td class="text-right"><b>Inv.Date:</b> {{date('d-m-Y',strtotime($report[0]->voucher_date))}}</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									
										<?php $qty_total = $net_total = $vat_total = $gross_total = $vat_gtotal = 0; ?>
										@foreach($report as $row)
										<?php 
										  $qty_total += $row->quantity;
										  $line_total = $row->total_price + $row->vat_amount;
										  $gross_total += $line_total;
										  $vat_total += $row->vat_amount;
										?>
										<tr>
											<td>{{$row->item_code}}</td>
											<td>{{ $row->description }}</td>
											<td class="text-right">{{$row->quantity}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($row->othercost_unit,2)}}</td>
											<td class="text-right">{{number_format($row->netcost_unit,2)}}</td>
											<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($line_total,2)}}</td>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td class="text-right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{$qty_total}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
										</tr>
										<?php 
										  $qty_gtotal += $qty_total;
										  $gross_gtotal += $gross_total;
										  $vat_gtotal += $vat_total;
										?>
									@endforeach	
										<tr>
											<td></td>
											<td class="text-right"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qty_gtotal}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"><b>{{number_format($vat_gtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($gross_gtotal,2)}}</b></td>
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

									<button type="button" onclick="javascript:self.close();"
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('transaction_list/export') }}">
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
