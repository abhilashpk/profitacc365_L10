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
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
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
<div>
    <!-- Left side column. contains the logo and sidebar -->
    
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<?php if(count($transactions) > 0) { ?>
						@if($type=='ageing')
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="center">@include('main.print_head_stmt')</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									<tr><td><br/></td></tr>
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
											<table class="tblstyle table-bordered" border="0" width="100%">
												<thead>
												<tr>
													<th><strong>Account ID</strong></th>
													<th>
														<strong>Account Name</strong>
													</th>
													<th class="text-right">
														<strong>0-30</strong>
													</th>
													<th class="text-right">
														<strong>31-60</strong>
													</th>
													<th class="text-right">
														<strong>61-90</strong>
													</th>
													<th class="text-right">
														<strong>91-120</strong>
													</th>
													<th class="text-right">
														<strong>Above 121</strong>
													</th>
													<th class="text-right">
														<strong>Total</strong>
													</th>
												</tr>
												</thead>
												<tbody>
												<?php $cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
														$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
												//$resultrow->op_balance;?>
												@foreach($transactions as $results)
													<?php 
														if(count($results) > 0) { 
														$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = $lntotal = 0; ?>
													@foreach($results as $transaction)
													<?php
															$cr_amount = ''; $dr_amount = '';
															$balance_prnt = $transaction['dr_amount'] - $transaction['cr_amount'];	
															$balance += $balance_prnt;
														
															$nodays = date_diff(date_create($transaction['invoice_date']),date_create(date('Y-m-d')));
															
															if($nodays->format("%a%") <= 30) {
																$amt1 += $balance_prnt;
																$amt1T += $balance_prnt;
															} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
																$amt2 += $balance_prnt;
																$amt2T += $balance_prnt;
															} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
																$amt3 += $balance_prnt;
																$amt3T += $balance_prnt;
															} else if($nodays->format("%a%") > 90 && $nodays->format("%a%") <= 120) {
																$amt4 += $balance_prnt;
																$amt4T += $balance_prnt;
															} else if($nodays->format("%a%") > 120) {
																$amt5 += $balance_prnt;
																$amt5T += $balance_prnt;
															}
															$lntotal = $amt1+$amt2+$amt3+$amt4+$amt5;
														?>
												@endforeach
												<?php
													if($balance_prnt != 0) { 
														
															if($balance_prnt > 0)
																$balance_prnt = number_format($balance_prnt,2);
															else if($balance_prnt < 0)
																$balance_prnt = '('.number_format(($balance_prnt*-1),2).')';
															else $balance_prnt = '';
															
															if($amt1 > 0)
																$amt1 = number_format($amt1,2);
															else if($amt1 < 0)
																$amt1 = '('.number_format(($amt1*-1),2).')';
															else $amt1 = '';
															
															if($amt2 > 0)
																$amt2= number_format($amt2,2);
															else if($amt2 < 0)
																$amt2 = '('.number_format(($amt2*-1),2).')';
															else $amt2 = '';
															
															if($amt3 > 0)
																$amt3= number_format($amt3,2);
															else if($amt3 < 0)
																$amt3 = '('.number_format(($amt3*-1),2).')';
															else $amt3 = '';
															
															if($amt4 > 0)
																$amt4= number_format($amt4,2);
															else if($amt4 < 0)
																$amt4 = '('.number_format(($amt4*-1),2).')';
															else $amt4 = '';
															
															if($amt5 > 0)
																$amt5= number_format($amt5,2);
															else if($amt5 < 0)
																$amt5 = '('.number_format(($amt5*-1),2).')';
															else $amt5 = '';
															
															if($lntotal > 0)
																$lntotal= number_format($lntotal,2);
															else if($lntotal < 0)
																$lntotal = '('.number_format(($lntotal*-1),2).')';
															else $lntotal = '';
														 } 
													 if($lntotal!=0) {
														 ?>
												<tr>
													<td>{{$transaction['acid']}}</td>
													<td>{{$transaction['acname']}}</td>
													<td class="emptyrow text-right">{{$amt1}}</td>
													<td class="emptyrow text-right">{{$amt2}}</td>
													<td class="emptyrow text-right">{{$amt3}}</td>
													<td class="emptyrow text-right">{{$amt4}}</td>
													<td class="emptyrow text-right">{{$amt5}}</td>
													<td class="emptyrow text-right">{{$lntotal}}</td>
												</tr>
													<?php } } ?>
												@endforeach	
												<?php
													if($balance > 0)
														$balance = number_format($balance,2);
													else if($balance < 0)
														$balance = '('.number_format(($balance*-1),2).')';
													
													if($amt1T > 0)
														$amt1T = number_format($amt1T,2);
													else if($amt1T < 0)
														$amt1T = '('.number_format(($amt1T*-1),2).')';
													
													if($amt2T > 0)
														$amt2T = number_format($amt2T,2);
													else if($amt2T < 0)
														$amt2T = '('.number_format(($amt2T*-1),2).')';
													else $amt2T = '';
													
													if($amt3T > 0)
														$amt3T = number_format($amt3T,2);
													else if($amt3T < 0)
														$amt3T = '('.number_format(($amt3T*-1),2).')';
													else $amt3T = '';
													
													if($amt4T > 0)
														$amt4T = number_format($amt4T,2);
													else if($amt4T < 0)
														$amt4T = '('.number_format(($amt4T*-1),2).')';
													else $amt4T = '';
													
													if($amt5T > 0)
														$amt5T = number_format($amt5T,2);
													else if($amt5T < 0)
														$amt5T = '('.number_format(($amt5T*-1),2).')';
													else $amt5T = '';
													
												?>
												<tr>
													<td></td>
													<td><strong>Total:</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt1T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt2T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt3T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt4T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt5T}}</strong></td>
													<td class="highrow text-right"><strong>{{$balance}}</strong></td>
												</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot_stmt')</td>
									</tr>
								</tfoot>
							</table>
						@endif
						<?php } else echo 'Please select at least one account!';?>
						</div>
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
									 <button type="button" onclick="javascript:window.print();" 
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;" >
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
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('account_enquiry/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="type" value="ageing" >
						<input type="hidden" name="account_id" value="{{$id}}" >
						</form>
					
                    </div>
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
<script>
function getExport() { document.frmExport.submit(); }

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
});
</script>
</html>
