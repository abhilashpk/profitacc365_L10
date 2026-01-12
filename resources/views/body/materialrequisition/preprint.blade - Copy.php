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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Material Requisition
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Material Requisition Report</a>
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
						<p>Date From: <b>{{($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate}}</b> &nbsp; To: <b>{{($todate=='')?date('d-m-Y'):$todate}}</b></p>
                        <div class="col-md-12">
							@if($type=="summary")
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>GR#</th>
									<th>GR.Date</th>
									<th>Job Code</th>
									<th>Job Name</th>
									<th>Engineer</th>
									<th>Description</th>
									<th class="text-right">Total Amount</th>
								</thead>
								<body>
									@foreach($reports as $row)
									@php
										$net_total = isset($net_total) ? $net_total + $row->net_amount : $row->net_amount;
									@endphp
									<tr>
										<td>{{ ++$i }}</td>
										<td>{{$row->voucher_no}}</td>
										<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										<td>{{$row->code}}</td>
										<td>{{$row->jobname}}</td>
										<td>{{$row->salesman}}</td>
										<td>{{$row->description}}</td>
										<td class="text-right">{{number_format($row->net_amount,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td colspan="6"></td>
										<td><b>Net Total</b></td>
										<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
									</tr>
								</body>
							</table>
							@endif
							@if($type=="summary_pending")
							<table class="table" border="0">
								<thead>
									<th>SI.No</th>
									<th>GR#</th>
									<th>GR.Date</th>
									<th>Job Code</th>
									<th>Job Name</th>
									<th>Engineer</th>
									<th>Description</th>
									<th class="text-right">Total Amount</th>
								</thead>
								<body>
									@foreach($reports as $row)
									@php
										$net_total = isset($net_total) ? $net_total + $row->net_amount : $row->net_amount;
									@endphp
									<tr>
										<td>{{ ++$i }}</td>
										<td>{{$row->voucher_no}}</td>
										<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										<td>{{$row->code}}</td>
										<td>{{$row->jobname}}</td>
										<td>{{$row->salesman}}</td>
										<td>{{$row->description}}</td>
										<td class="text-right">{{number_format($row->net_amount,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td colspan="6"></td>
										<td><b>Net Total</b></td>
										<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
									</tr>
								</body>
							</table>
							@endif
							@if($type=="detail")
							<?php //echo '<pre>';print_r($reports);exit; ?>
							<table class="table" border="1">
									<body>
									@foreach($reports as $row)
									<tr>
										<td><b>SI.No:</b> {{ ++$i }}</td>
										<td><b>MR#:</b>{{$reports[0]->voucher_no}}</td>
										<td colspan="4" class="text-right"><b>Engineer Name:</b>{{$reports[0]->salesman}}</td>
										<td colspan="4" class="text-right"><b>Job Name:</b> {{$reports[0]->jobname}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>MR.Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>Net Amt.</b></td>
										<td class="text-right"><b>Net Total Amt.</b></td>
									</tr>
									<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
										@foreach($reports as $row)
									<?php 
										  $unit_price = $row->unit_price;
										  $total_price = $row->quantity * $unit_price;
										  $net_amount = $total_price;
										  $net_total += $net_amount;
										  $qty_total += $row->quantity;
									?>
										<tr>
											<td>{{$row->item_code}}</td>
											<td>{{ $row->item_name }}</td>
											<td class="text-right">{{$row->quantity}}</td>
											<td class="text-right">{{number_format($unit_price,2)}}</td>
											<td class="text-right">{{number_format($total_price,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{$qty_total}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
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
							@endif
							@if($type=="detail_pending")
							<?php //echo '<pre>';print_r($reports);exit; ?>
							<table class="table" border="1">
									<body>
									@foreach($reports as $row)
									<tr>
										<td><b>SI.No:</b> {{ ++$i }}</td>
										<td><b>MR#:</b>{{$reports[0]->voucher_no}}</td>
										<td colspan="4" class="text-right"><b>Engineer Name:</b>{{$reports[0]->salesman}}</td>
										<td colspan="4" class="text-right"><b>Job Name:</b> {{$reports[0]->jobname}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>MR.Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>Net Amt.</b></td>
										<td class="text-right"><b>Net Total Amt.</b></td>
									</tr>
									<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
										@foreach($reports as $row)
									<?php 
										  $unit_price = $row->unit_price;
										  $total_price = $row->quantity * $unit_price;
										  $net_amount = $total_price;
										  $net_total += $net_amount;
										  $qty_total += $row->quantity;
									?>
										<tr>
											<td>{{$row->item_code}}</td>
											<td>{{ $row->item_name }}</td>
											<td class="text-right">{{$row->quantity}}</td>
											<td class="text-right">{{number_format($unit_price,2)}}</td>
											<td class="text-right">{{number_format($total_price,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{$qty_total}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
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
							@endif
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('material_requisition/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="salesman" value="{{$salesman}}" >
					<input type="hidden" name="jobname" value="{{$jobname}}" >
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
