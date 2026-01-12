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
					<table border="0" style="width:100%;">
								<tr><td  align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td  align="center"><h4><u>{{$voucherhead}}</u></h4>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                       
                        <div class="col-md-12">
						@if($stype=='summary')
						@if($input['job_id']!='' || $input['customer_id']!='' || $input['search_val']!='')
						{{--<table border="0" style="width:100%;">
							<tr>
								<td align="left" valign="top" width="33%" style="padding-left:0px;">
									<div style="border:1px solid #000; padding: 10px;">
									<b>Invoice No: {{$reports[0]->voucher_no}} </b><br/>
									<b>Invoice Date: {{$reports[0]->voucher_date}} </b><br/>
									<b>Customer: {{$reports[0]->master_name}} </b><br/>
									<b>Job No: {{$reports[0]->code}} </b><br/>
									<b> </b><br/>
									</div>
								</td>
								
								<td align="left" valign="top" width="33%" style="padding-left:10px;">
									<div style="border:1px solid #000; padding: 10px;">
									<b>Vehicle No: {{$reports[0]->reg_no}}</b><br/>
									<b>Engine No: {{$reports[0]->engine_no}} </b><br/>
									<b>Chasis No: {{$reports[0]->chasis_no}} </b><br/>
									<b>Vehicle Name: {{$reports[0]->name}} </b><br/>
									<b>Vehicle Model: {{$reports[0]->model}} </b><br/>
									</div>
								</td>
								<td align="left" width="33%" style="padding-left:10px;">
									<div style="border:1px solid #000; padding: 10px;">
									<b>Next Service Due: {{$reports[0]->kilometer}} </b><br/>
									<b>Next Service km.: {{$reports[0]->kilometer}}</b><br/>
									<b>Last Service km: {{$reports[0]->kilometer}} </b><br/>
									<b> </b><br/>
									<b> </b><br/>
								</div>
								</td>
							</tr>
						</table>--}}
						@endif
						<table class="table" border="0">					
							<thead>
								<th>Job Code</th>
								<th>Job Name</th>
								<th>Customer Name</th>
								@if($vehicle==1)<th>Vehicle No</th>@endif
								@if($vehicle==1)<th>Chasis No</th>@endif
								<th class="text-right">Income</th>
								<th class="text-right">Cost</th>
								<th class="text-right">Net Income</th>
							</thead>
							<body>
							@php $total_income = $total_expense = $total_netincome = 0; @endphp
							@foreach($reports as $report)
								<tr>
									@php
										$net_income = $report->income - $report->amount;
										$total_income += $report->income;
										$total_expense += $report->amount;
										$total_netincome += $net_income;
									@endphp
									<td>{{ $report->code }}</td>
									<td>{{ $report->jobname }}</td>
									<td>{{ $report->master_name }}</td>
									@if($vehicle==1)<td>{{ $report->reg_no }}</td>@endif
									@if($vehicle==1)<td>{{ $report->chasis_no }}</td>@endif
									<td class="text-right">{{ number_format($report->income,2) }}</td>
									<td class="text-right">{{ number_format($report->amount,2) }}</td>
									<td class="text-right">
									@if($net_income < 0)
										@php echo '('.number_format(($net_income*-1),2).')'; @endphp
									@else
										@php echo number_format($net_income,2); @endphp
									@endif
									</td>
								</tr>
							@endforeach
								<tr>
									@if($vehicle==1)<th class="text-right" colspan="5">Grand Total</th>
									@else<th class="text-right" colspan="3">Grand Total</th>@endif
									
									<th class="text-right">{{number_format($total_income,2)}}</th>
									<th class="text-right">{{number_format($total_expense,2)}}</th>
									<th class="text-right">{{number_format($total_netincome,2)}}</th>
								</tr>
							</body>
						</table>
						@elseif($stype=='detail')
						@foreach($reports as $report)
							<div style="border:1px solid #000; padding: 10px;">
							<table border="0" style="width:100%;">
								<tr>
									<td align="left" valign="top" width="33%" style="padding-left:0px;">
									    <b>Job No: {{$report[0]->code}} </b><br/>
										Customer: {{$report[0]->master_name}} <br/>
										
										<br/>
										<br/>
										<br/>
									</td>
									
							<!--	@if($vehicle==1)	<td align="left" valign="top" width="33%" style="padding-left:10px;">
										<b>Vehicle No: {{$report[0]->reg_no}}</b><br/>
										<b>Engine No: {{$report[0]->engine_no}} </b><br/>
										<b>Chasis No: {{$report[0]->chasis_no}} </b><br/>
										<b>Vehicle Name: {{$report[0]->name}} </b><br/>
										<b>Vehicle Model: {{$report[0]->model}} </b><br/>
									</td>@endif-->
								@if($vehicle==1)	<td align="left" width="33%" style="padding-left:10px;">
								    	<b>Vehicle No: {{$report[0]->reg_no}}</b><br/>
										<b>Chasis No: {{$report[0]->chasis_no}} </b><br/>
										<b>Vehicle Name: {{$report[0]->name}} </b><br/>
									<!--	<b>Next Service Due: {{($report[0]->next_due!='0000-00-00' || $report[0]->next_due!='1970-01-01')?date('d-m-Y',strtotime($report[0]->next_due)):''}} </b><br/>
										<b>Next Service km.: {{$report[0]->present_km}}</b><br/>
										<b>Last Service km: {{$report[0]->next_km}} </b><br/>-->
										<b> </b><br/>
										<b> </b><br/>
									</td>@endif
								</tr>
							</table>
							</div>
							<br/>
							
							<table class="table" border="0">					
								<thead>
									<th>Inv.No</th>
									<th>Date</th>
									<th>V.Type</th>
									<th>Item Description</th>
									<th class="text-right">Qty.</th>
									<th class="text-right">Rate</th>
									<th class="text-right">Income</th>
									<th class="text-right">Cost</th>
									<th class="text-right">Net Income</th>
								</thead>
								<body>
								@php $qty_total = $total_income = $total_expense = $total_netincome = 0; @endphp
								@foreach($report as $row)
									<tr>
										<td>{{ $row->voucher_no }}</td>
										<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
										<td>{{$row->vtype}}</td>
										<td>{{($row->description=='')?$row->jdesc:$row->description}}</td>
										<td class="text-right">{{ ($row->quantity!=0)?$row->quantity:'' }}</td>
										<td class="text-right">{{ ($row->unit_price!=0)?number_format($row->unit_price,2):'' }}</td>
										<td class="text-right">{{ ($row->income!=0)?number_format($row->income,2):'' }}</td>
										<td class="text-right">{{ ($row->amount!=0)?number_format($row->amount,2):'' }}</td>
										<td class="text-right">{{ number_format($row->income - $row->amount,2) }}</td>
									</tr>
									@php
										$qty_total += $row->quantity;
										$net_income = $row->income - $row->amount;
										$total_income += $row->income;
										$total_expense += $row->amount;
										$total_netincome += $net_income;
									@endphp
								@endforeach
									<tr>
										<th class="text-right" colspan="4">Grand Total</th>
										<th class="text-right">{{$qty_total}}</th>
										<th class="text-right"></th>
										<th class="text-right">{{number_format($total_income,2)}}</th>
										<th class="text-right">{{number_format($total_expense,2)}}</th>
										<th class="text-right">{{number_format($total_netincome,2)}}</th>
									</tr>
								</body>
							</table>
							<br/><hr/>
						@endforeach
						@elseif($stype=='stockin' || $stype=='stockout')
						<table class="table" border="0">
							<thead>
								<th style="width:10%;">Type</th>
								<th style="width:10%;">Date</th>
								<th style="width:10%;">Vehicle No.</th>
								<th style="width:10%;">Chasis No.</th>
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
								</tr>
								<tr>
									<td colspan="2">Voucher Name: <b>{{$report[0]->master_name}}</b> </td>
									<td>Inv. No: <b>{{$report[0]->voucher_no}}</b></td>
								
									<td style="width:20%;">Job Code: <b>{{$report[0]->code}}</b></td>
								</tr>
								<?php 
								$qty_total = $value_total = 0;
								foreach($report as $row) { ?>
									<tr>
										<td>{{$row->type}}</td>
										<td>{{date('d-m-Y',strtotime($report[0]->voucher_date))}}</td>
										<td>{{$row->reg_no}}</td>
										<td>{{$row->chasis_no}}</td>
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
								<td class="text-right"></td><td class="text-right"></td>
									<td align="right"><b>Total:</b></td>
									<td class="text-right"><b>{{$qty_total}}</b></td>
									<td class="text-right"></td>
									<td class="text-right"><b>{{number_format($value_total,2)}}</b></td>
								</tr>
							<?php } ?>
							</body>
						</table>
						@endif
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
					
                </div>
            </div>
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
