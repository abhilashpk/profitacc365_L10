<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->


        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
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
    
        </section>


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
