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
            <h1> Ledger Moments </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
				<li> Ledger Moments</li>
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
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							
									<table class="table" border="0">
										<thead>
											<th>Account Code</th>
											<th>Account Name</th>
											<th class="text-right">Balance</th>
											<th class="text-right">PDC Received</th>
											<th class="text-right">PDC Issued</th>
											<th class="text-right">Net Balance</th>
										</thead>
										<body><?php $total_bal = $total_pdcr = $total_pdci = $net_bal = 0; ?>
											@foreach($reports as $report)
											<tr>
												<?php
													$total_bal += $report->cl_balance;
													$total_pdcr += $report->pdcr_amount; 
													$total_pdci += $report->pdci_amount; 
													
													if($type=='CUSTOMER') {
														$net_balance = $report->cl_balance + $report->pdcr_amount;
													} else {
														$net_balance = $report->cl_balance + $report->pdci_amount;
													}
													$net_bal += $net_balance;
												?>
												<td>{{ $report->account_id }}</td>
												<td>{{ $report->master_name }}</td>
												
												<td class="text-right">
													<?php if($report->cl_balance < 0) {
															echo '('.number_format(( $report->cl_balance *-1),2).')';
													  } else echo number_format($report->cl_balance,2); 
													?>
												</td>
												<?php if($type=='CUSTOMER') { ?>
												<td class="text-right">
													<?php if($report->pdcr_amount < 0) {
															echo '('.number_format(( $report->pdcr_amount *-1),2).')';
													  } else echo number_format($report->pdcr_amount,2); 
													?>
												</td>
												<?php } else { ?><td class="text-right">0.00</td><?php } ?>
												
												<?php if($type=='SUPPLIER') { ?>
												<td class="text-right">
													<?php if($report->pdci_amount < 0) {
															echo '('.number_format(( $report->pdci_amount *-1),2).')';
													  } else echo number_format($report->pdci_amount,2); 
													?>
												</td>
												<?php } else { ?><td class="text-right">0.00</td><?php } ?>
												<td class="text-right">
												<?php if($net_balance < 0) {
															echo '('.number_format(( $net_balance *-1),2).')';
													  } else echo number_format($net_balance,2); 
													?>
												</td>
											</tr>
											@endforeach
											<tr>
												<td colspan="2" align="right"><b>Grand Total:</b></td>
												<td class="text-right"><b>
													<?php if($total_bal < 0) {
															echo '('.number_format(( $total_bal *-1),2).')';
													  } else echo number_format($total_bal,2); 
													?>
												</b></td>
												
												<td class="text-right"><b>
													<?php if($total_pdcr < 0) {
															echo '('.number_format(( $total_pdcr *-1),2).')';
													  } else echo number_format($total_pdcr,2); 
													?>
												</b></td>
												<td class="text-right"><b>
													<?php if($total_pdci < 0) {
															echo '('.number_format(( $total_pdci *-1),2).')';
													  } else echo number_format($total_pdci,2); 
													?>
												</b></td>
												<td class="text-right"><b>
													<?php if($net_bal < 0) {
															echo '('.number_format(( $net_bal *-1),2).')';
													  } else echo number_format($net_bal,2); 
													?>
												</b></td>
											</tr>
										</body>
									</table>
							
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.print();">
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
									
								<button type="button"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;" onclick="javascript:window.close();">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('ledger_moments/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					</form>
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
	<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
