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
            <h1> PDC Report</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> PDC Report</li>
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
						
                        <div class="col-md-12">
							
									<table class="table horizontal_table table-striped" id="tableAcmaster" border="0">
										<thead>
											<tr>
												<th>SI.#</th>
                                                <th>Cheque Date</th>
												<th>Cheque No.</th>
												<th>Bank</th>
                                                <th>Voucher No.</th>
												<th>Voucher Date</th>
												<th>Party Name</th>
												<th class="text-right">Amount</th>
											</tr>
										</thead>
										<tbody>
										
											
											<?php 
												$total = $i = 0; $date = ''; 
												foreach($reports as $key => $rows) {
												 
                                                    $mtotal = 0;
                                                    foreach($rows as $row) {
                                                        if($row->cheque_no!='' && $row->code!='') { 
                                                        $i++; 
                                                        $total += $row->amount;
                                                        $date = $row->voucher_date;
                                                        $mtotal += $row->amount;
											?>
											<tr>
												<td>{{$i}}</td>
                                                <td>{{date('d-m-Y', strtotime($row->cheque_date))}}</td>
												<td>{{ $row->cheque_no }}</td>
												<td>{{$row->code}}</td>
                                                <td>{{ $row->voucher_no }}</td>
												<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
												<td>{{$row->customer}}</td>
												<td class="text-right">
                                                    {{ number_format(
                                                        is_array($row->amount)
                                                            ? (float) ($row->amount['amount'] ?? 0)
                                                            : (float) ($row->amount ?? 0),
                                                        2
                                                    ) }}
                                                </td>

											</tr>
											<?php } } $date[$key] =  $mtotal; $yr = date('Y', strtotime($row->cheque_date)); ?>
                                            <tr>
												<td colspan="7" class="text-right" style="background-color:#d4eaf7 !important;"><b>Total {{date("F", mktime(0, 0, 0, $key, 10)).' '.$yr}}: </b></td>
												<td class="text-right" style="background-color:#d4eaf7 !important;"><b>{{number_format($mtotal,2)}}</b></td>
											</tr>
                                            <?php } ?>
											
											<tr>
												<td colspan="7" class="text-right" style="background-color:#d4eaf7 !important;"><b>Net Total: </b></td>
												<td class="text-right" style="background-color:#d4eaf7 !important;"><b>{{number_format($total,2)}}</b></td>
											</tr>
											
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
								
								<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Back 
                                            </span>
                                </button>
                                </span>
                        </div>
                    </div>
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
@stop
