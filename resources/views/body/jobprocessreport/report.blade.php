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
								<tr><td width="60%" align="right"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="60%" align="right"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
                        <div class="col-md-12">
							<?php if($stype=='summary') { ?>
								@foreach($reports as $report)
								  @foreach($report as $key => $rows)
								  <p><b>Job No: {{$rows[0]->jobcode}}</b></p>
								  @if($key=='PO')
										<table class="table" border="0">					
											<thead>
												<th>PO No</th>
												<th>PO Date</th>
												<th>Job No</th>
												<th>Supplier Name</th>
												<th class="text-right">PO Amount</th>
												<th class="text-right">VAT Amount</th>
												<th class="text-right">Total Amount</th>
											</thead>
											<body>
											@php $pototal = $povat = $ponet = 0; @endphp
											@foreach($rows as $row)
												@php $pototal += $row->total; $povat += $row->vat_amount; $ponet += $row->net_amount; @endphp
												<tr>
													<td>{{ $row->voucher_no }}</td>
													<td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
													<td>{{ $row->jobcode }}</td>
													<td>{{ $row->master_name }}</td>
													<td class="text-right">{{ number_format($row->total,2) }}</td>
													<td class="text-right">{{ number_format($row->vat_amount,2) }}</td>
													<td class="text-right">{{ number_format($row->net_amount,2) }}</td>
												</tr>
											@endforeach
											</body>
											<tr>
												<td colspan="4" align="right"><b>Total</b></td>
												<td class="text-right"><b>{{ number_format($pototal,2) }}</b></td>
												<td class="text-right"><b>{{ number_format($povat,2) }}</b></td>
												<td class="text-right"><b>{{ number_format($ponet,2) }}</b></td>
											</tr>
										</table><br/><br/>
										
									@else
											<table class="table" border="0">					
												<thead>
													<th>SO No</th>
													<th>SO Date</th>
													<th>Job No</th>
													<th>Customer Name</th>
													<th class="text-right">SO Amount</th>
													<th class="text-right">VAT Amount</th>
													<th class="text-right">Total Amount</th>
												</thead>
												<body>
												@php $sototal = $sovat = $sonet = 0; @endphp
												@foreach($rows as $row)
													@php $sototal += $row->total; $sovat += $row->vat_amount; $sonet += $row->net_amount; @endphp
													<tr>
														<td>{{ $row->voucher_no }}</td>
														<td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
														<td>{{ $row->jobcode }}</td>
														<td>{{ $row->master_name }}</td>
														<td class="text-right">{{ number_format($row->total,2) }}</td>
														<td class="text-right">{{ number_format($row->vat_amount,2) }}</td>
														<td class="text-right">{{ number_format($row->net_amount,2) }}</td>
													</tr>
												@endforeach
												</body>
													<tr>
														<td colspan="4" align="right"><b>Total</b></td>
														<td class="text-right"><b>{{ number_format($sototal,2) }}</b></td>
														<td class="text-right"><b>{{ number_format($sovat,2) }}</b></td>
														<td class="text-right"><b>{{ number_format($sonet,2) }}</b></td>
													</tr>
											</table><br/><br/>
									@endif
									@endforeach
								  @endforeach
								
							<?php } else if($stype=='detail') { ?>	
								@foreach($reports as $report)
								  @foreach($report as $key => $rows)
								  
								  @if($key=='PO')
										<table class="table" border="0">
										<tr>
											<td><b>PO No: {{$rows[0]->voucher_no}}</b></td>
											<td><b>PO Date: {{date('d-m-Y',strtotime($rows[0]->voucher_date))}}</b></td>
											<td><b>Job No: {{$rows[0]->jobcode}}</b></td>
											<td><b>Supplier: {{$rows[0]->master_name}}</b></td>
										</tr>
									  </table>
										<table class="table" border="0">					
											<thead>
												<th>Item Name</th>
												<th>Quantity</th>
												<th class="text-right">Price</th>
												<th class="text-right">VAT Amount</th>
												<th class="text-right">Total Amount</th>
											</thead>
											<body>
											@php $poqty = $povat = $ponet = 0; @endphp
											@foreach($rows as $row)
												@php $poqty += $row->quantity; $povat += $row->line_vat; $ponet += $row->total_price; @endphp
												<tr>
													<td>{{ $row->description }}</td>
													<td>{{ $row->quantity }}</td>
													<td class="text-right">{{ number_format($row->unit_price,2) }}</td>
													<td class="text-right">{{ number_format($row->line_vat,2) }}</td>
													<td class="text-right">{{ number_format($row->total_price,2) }}</td>
												</tr>
											@endforeach
											</body>
											<tr>
												<td align="right"><b>Total</b></td>
												<td><b>{{ number_format($poqty,2) }}</b></td>
												<td class="text-right"></td>
												<td class="text-right"><b>{{ number_format($povat,2) }}</b></td>
												<td class="text-right"><b>{{ number_format($ponet,2) }}</b></td>
											</tr>
										</table><br/><br/>
									@else
											<table class="table" border="0">
												<tr>
													<td><b>SO No: {{$rows[0]->voucher_no}}</b></td>
													<td><b>SO Date: {{date('d-m-Y',strtotime($rows[0]->voucher_date))}}</b></td>
													<td><b>Job No: {{$rows[0]->jobcode}}</b></td>
													<td><b>Customer: {{$rows[0]->master_name}}</b></td>
												</tr>
											  </table>
											<table class="table" border="0">					
												<thead>
													<th>Item Name</th>
													<th>Quantity</th>
													<th class="text-right">Price</th>
													<th class="text-right">VAT Amount</th>
													<th class="text-right">Total Amount</th>
												</thead>
												<body>
												@php $soqty = $sovat = $sonet = 0; @endphp
												@foreach($rows as $row)
													@php $soqty += $row->quantity; $sovat += $row->line_vat; $sonet += $row->total_price; @endphp
													<tr>
														<td>{{ $row->description }}</td>
														<td>{{ $row->quantity }}</td>
														<td class="text-right">{{ number_format($row->unit_price,2) }}</td>
														<td class="text-right">{{ number_format($row->line_vat,2) }}</td>
														<td class="text-right">{{ number_format($row->total_price,2) }}</td>
													</tr>
												@endforeach
												</body>
													<tr>
														<td align="right"><b>Total</b></td>
														<td><b>{{ number_format($soqty,2) }}</b></td>
														<td class="text-right"></td>
														<td class="text-right"><b>{{ number_format($sovat,2) }}</b></td>
														<td class="text-right"><b>{{ number_format($sonet,2) }}</b></td>
													</tr>
											</table><br/><br/>
									@endif
									@endforeach
								  @endforeach		
							<?php } ?>
							</tbody>
						
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
									
									<!--<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>-->
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
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('job_report/export') }}">
					
					</form>
					
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
