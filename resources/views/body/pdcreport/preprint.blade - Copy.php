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
                PDC Report
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Reports
                </li>
				<li>
                    <a href="#">PDC Report</a>
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
								<tr><td align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td align="center"><h4><u>{{$voucherhead}}</u></h4></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($fromdate));?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
							<?php if(count($reports) > 0) { ?>
							<table class="table">
										<thead>
											<tr>
												<th style="width:50px;"><!--Type--></th>
												<th><strong>Voucher No</strong></th>
												<th><strong>Party Name</strong></th>
												<th><strong>Bank</strong></th>
												<th><strong>Trn.Date</strong></th>
												<th><strong>Cheque Dt.</strong></th>
												<th class="text-right"><strong>Amount</strong></th>
											</tr>
										</thead>
										<tbody>
										@foreach($reports as $key => $report)
											<?php 
												$total = 0;
												foreach($report as $row) {
												$total += $row->amount;
												$date = $row->voucher_date;
												?>
											<tr>
												<td></td>
												<td>{{ $row->voucher_no }}</td>
												<td>{{$row->customer}}</td>
												<td>{{ $row->code }}</td>
												<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
												<td>{{ date('d-m-Y', strtotime($row->cheque_date))}}</td>
												<td class="text-right">{{number_format($row->amount,2)}}</td>
											</tr>
											<?php } ?>
											<tr>
												<td></td><td></td><td></td><td></td><td></td>
												<td><b>Total in <?php echo date('M', strtotime($date));?>: </b></td>
												<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											</tr>
										@endforeach
										</tbody>
									</table>
							<?php } else  echo 'No records were found.'; ?>
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
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
					<?php if(count($reports) > 0) { ?>	
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('pdc_report/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="status" value="{{$status}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<input type="hidden" name="account_id" value="{{$account}}" >
						<input type="hidden" name="ob_only" value="{{$obonly}}" >
					</form>
					<?php } ?>
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
