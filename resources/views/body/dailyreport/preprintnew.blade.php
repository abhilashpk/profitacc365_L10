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
                    <a href="#">Daily Report</a>
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
						<?php  if(count($transaction) > 0) { 
					foreach($reports as $report) {  ?>
							
								<table class="table" border="0">
								   
								
									<body>
									    	<?php  $total=$sptotal=$balance=0; ?>
									@php $Sbalance_prnt = $Sdr_total = $Scr_total = 0; @endphp
									@php $Gbalance_prnt = $Gdr_total = $Gcr_total = 0; @endphp
											
											<?php ?>
									
										@foreach($transaction as $trans)
										<?php
										$cr_total = 0; $dr_total = 0; $balance = 0; $Gbalance_prnt =0;
											$cr_amount = ''; $dr_amount = '';
											
											if($trans->transaction_type=='Cr') {
												//$cr_amount = number_format($transaction->amount,2);
													$cr_amount = number_format($trans->amount,2);
												if($trans->amount >= 0) {
													$cr_total += $trans->amount;
													$balance = bcsub($balance, $trans->amount, 2);
												} else {
													$cr_total -= $trans->amount;
													$balance += $trans->amount;
												}
											} else if($trans->transaction_type=='Dr') {
												//$dr_amount = number_format($transaction->amount,2);
													$dr_amount = number_format($trans->amount,2);
												$dr_total += $trans->amount;
												$balance += $trans->amount;
											}
											$Gbalance_prnt = $trans->amount;
												$sptotal += $Gbalance_prnt ;
											if($balance < 0) {
												if($balance != 0)
													$balance = $balance;
												
												$arr = explode('-', $balance);
												if(is_numeric($arr[1])) {
													$balance_prnt = '('.number_format($arr[1],2).')';
													//$balance_prnt = $arr[1];
												} else
													$balance_prnt = number_format(0,2);
													
												/* $arr = explode('-', $balance);
												$balance_prnt = '('.number_format($arr[1],2).')'; */
												//$balance_prnt = $arr[1];
											} else {
												if($balance > 0)
													$balance = $balance;
												$balance_prnt = number_format($balance,2);
											}
											

										?>
											
									
										
										@php 	 $total += $Gbalance_prnt ; $Gdr_total += $dr_total; $Gcr_total += $cr_total; $Sbalance_prnt= $Gdr_total +$Gcr_total ;  @endphp
										@endforeach	
												<?php  
										$Sbalance_prnt += 	$sptotal;
									
									?>
									<tr>
												
												<td >
												Account Name: {{$report[0]->master_name}}
												</td>
												<td class="emptyrow text-right">
												{{$sptotal}}
												</td>
											</tr>
									
								  <?php } ?>
								<table border="0" style="width:100%;" class="tblstyle table-bordered">
								<tr>
										<td colspan="3" align="right"><b>Total:</b></td>	
											<td class="text-right">{{$Sbalance_prnt}}</td>
										</tr>
									</table>
									</body>
								</table>	






								
						
						<?php } else { ?>
						 <table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />
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
						<?php  } ?>
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('daily_report/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					
				
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
