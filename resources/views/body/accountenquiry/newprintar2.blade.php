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
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
						
						@if($type=='statement')  
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">@include('main.print_head_stmt')</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:15px;"><br/><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									@php $Sbalance_prnt = $Sdr_total = $Scr_total = 0; @endphp
									@foreach($transactions as $head => $transactionarr)
									@if($iscustom==1)<div><h5><b>{{$headarr[$head]->heading}}</b></h5></div>@endif
									@php $Gbalance_prnt = $Gdr_total = $Gcr_total = 0; @endphp
									@foreach($transactionarr as $key => $transaction)
									@if(isset($resultrow[$key]))
									<table border="0" style="width:100%;" >
										<tr>
											<td align="left" style="padding-left:0px;">
												<p><b>{{$resultrow[$key]->master_name.' ('.$resultrow[$key]->account_id.')'}}
												<br/>{{$resultrow[$key]->address}}{{($resultrow[$key]->phone!='')?' Ph:'.$resultrow[$key]->phone:''}} TRN No: {{$resultrow[$key]->vat_no}}</b></p>
											</td>
											<td align="right" style="padding-left:0px;">
												<p><b>From: {{$fromdate}} - To: {{$todate}}</b></p>
											</td>
										</tr>
									</table>
									
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="5%"><b>Type</b></td>
											<td width="5%"><b>No</b></td>
											<td width="14%" ><b>Date</b></td>
											<td width="20%" ><b>Description</b></td>
											<td width="5%" ><b>Ref.No</b></td>
											<td width="10%" ><b>Bank Date</b></td>
											<td width="9%" class="text-right"><b>Debit</b></td>
											<td width="9%" class="text-right"><b>Credit</b></td>
											<td width="10%" class="text-right"><b>Balance</b></td>
										</tr>
										
										<?php 
											$cr_total_cb = $cr_total = 0; $dr_total_cb = $dr_total = 0; $balance = $balance_cb = $balance_prnt_cb = 0;
											if(isset($openbalance[$key])) { ?>
											<tr>
												<td>{{$openbalance[$key]['type']}}</td>
												<td></td>
												<td>{{$fromdate}}</td>
												<td></td>
												<td></td> <td></td>
												<td class="emptyrow text-right">
													<?php  if($openbalance[$key]['transaction_type']=='Dr') { 
													$balance = $openbalance[$key]['amount'];
													$dr_total = ($balance < 0)?($balance*-1):$balance;
													
													echo number_format( $dr_total, 2); } ?>
												</td>
												<td class="emptyrow text-right">
												<?php if($openbalance[$key]['transaction_type']=='Cr') { 
												$balance = $openbalance[$key]['amount'];
												$cr_total = ($balance < 0)?($balance*-1):$balance;
												echo number_format( $cr_total, 2); } ?>
												</td>
												<td class="emptyrow text-right">
												<?php
													if($balance < 0) {
														$balance_cb = $balance;
														$arr = explode('-', $balance);
														$balance_prnt = '('.number_format($arr[1],2).')';
													} else {
														$balance_cb = $balance;
														$balance_prnt = number_format($balance,2);
													}
													
													echo $balance_prnt;
												?>
												</td>
											</tr>
											<?php } ?>
										
										@if($uncleared)
											@foreach($uncleared[$key] as $urow)
											<?php
												$ucr_amount = ''; $udr_amount = '';
												$trdate = $fdate = $tdate = '';
												if(isset($actrndata[$urow->id])) {
													$trdate = strtotime($actrndata[$urow->id]);
													$fdate = strtotime($fromdate);
													$tdate = strtotime($todate);
												}
												
												if($urow->transaction_type=='Cr') {
													
													$ucr_amount = number_format($urow->amount,2);
													if($urow->amount >= 0) {
														$cr_total += $urow->amount;
														$balance = bcsub($balance, $urow->amount, 2);
														
														if(isset($actrndata[$urow->id])) {
															$cr_total_cb += $urow->amount;
															$balance_cb = bcsub($balance_cb, $urow->amount, 2);
														}
													
													} else {
														$cr_total -= $urow->amount;
														$balance += $urow->amount;
														
														if(isset($actrndata[$urow->id])) {
															$cr_total_cb += $urow->amount;
															$balance_cb += $urow->amount;
														}
													}
													
													
												} else if($urow->transaction_type=='Dr') {
													
													$udr_amount = number_format($urow->amount,2);
													$dr_total += $urow->amount;
													$balance += $urow->amount;
													
													if(isset($actrndata[$urow->id])) {
														$dr_amount_cb = number_format($urow->amount,2);
														$dr_total_cb += $urow->amount;
														$balance_cb += $urow->amount;
													}
												}
												
												$nbalance = $balance;
												if($balance < 0) {
													if($balance != 0)
														$balance = $balance;
													
													$arr = explode('-', $balance);
													if(is_numeric($arr[1])) {
														$balance_prnt = '('.number_format($arr[1],2).')';
													} else
														$balance_prnt = number_format(0,2);
												} else {
													if($balance > 0)
														$balance = $balance;
													$balance_prnt = number_format($balance,2);
												}
												
												if($balance_cb < 0) {
													if($balance_cb != 0)
														$balance_cb = $balance_cb;
													
													$arr = explode('-', $balance_cb);
													if(is_numeric($arr[1])) {
														$balance_prnt_cb = '('.number_format($arr[1],2).')';
														
													} else
														$balance_prnt_cb = number_format(0,2);
												} else {
													if($balance_cb > 0)
														$balance_cb = $balance_cb;
													$balance_prnt_cb = number_format($balance_cb,2);
												}
												
												$balance_prnt_ucb = ($nbalance - $balance_cb);
											?>
											<tr>
												<td>{{$urow->voucher_type}}</td>
												<td><?php  echo $urow->reference; ?></td>
												<td><?php echo date('d-m-Y', strtotime($urow->invoice_date)); ?></td>
												<td><?php echo $urow->description;?></td>
												<td>{{$urow->reference_from}}</td>	
												<td>
													@if(isset($actrndata[$urow->id]))
													{{($trdate >= $fdate && $trdate <= $tdate)?date('d-m-Y',strtotime($actrndata[$urow->id])):''}}
													@endif
												</td>
												<td class="emptyrow text-right">{{$udr_amount}}</td>
												<td class="emptyrow text-right">{{$ucr_amount}}</td>
												<td class="emptyrow text-right">{{$balance_prnt}}</td>
											</tr>
											@endforeach
										@endif
										
										@foreach($transaction as $trans)
										<?php
											$cr_amount = ''; $dr_amount = '';
											
											$trdate = $fdate = $tdate = '';
											if(isset($actrndata[$trans->id])) {
												$trdate = strtotime($actrndata[$trans->id]);
												$fdate = strtotime($fromdate);
												$tdate = strtotime($todate);
											}
											
											if($trans->transaction_type=='Cr') {
												
												$cr_amount = number_format($trans->amount,2);
												if($trans->amount >= 0) {
													$cr_total += $trans->amount;
													$balance = bcsub($balance, $trans->amount, 2);
													
													if($trans->voucher_type=='OB') {
														$cr_total_cb = $trans->amount;
														$balance_cb = $cr_total_cb;
													}
													
													if(isset($actrndata[$trans->id]) && ($trdate >= $fdate && $trdate <= $tdate)) {
														$cr_total_cb += $trans->amount;
														$balance_cb = bcsub($balance_cb, $trans->amount, 2);
													}
												
												} else {
													$cr_total -= $trans->amount;
													$balance += $trans->amount;
													
													if($trans->voucher_type=='OB') {
														$cr_total_cb = $trans->amount;
														$balance_cb = $cr_total_cb;
													}
													
													if(isset($actrndata[$trans->id]) && ($trdate >= $fdate && $trdate <= $tdate)) {
														$cr_total_cb += $trans->amount;
														$balance_cb += $trans->amount;
													}
												}
												
												
											} else if($trans->transaction_type=='Dr') {
												
												$dr_amount = number_format($trans->amount,2);
												$dr_total += $trans->amount;
												$balance += $trans->amount;
												
												if($trans->voucher_type=='OB') {
														//$dr_total_cb = $trans->amount;
														$balance_cb = $trans->amount;
														//$ob_balance = $dr_total_cb;
												}
												
												if(isset($actrndata[$trans->id]) && ($trdate >= $fdate && $trdate <= $tdate)) {
													$dr_amount_cb = number_format($trans->amount,2);
													$dr_total_cb += $trans->amount;
													$balance_cb += $trans->amount;
												}
											}
											
											
											$nbalance = $balance;
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
											
											if($balance_cb < 0) {
												if($balance_cb != 0)
													$balance_cb = $balance_cb;
												
												$arr = explode('-', $balance_cb);
												if(is_numeric($arr[1])) {
													$balance_prnt_cb = '('.number_format($arr[1],2).')';
													//$balance_prnt = $arr[1];
												} else
													$balance_prnt_cb = number_format(0,2);
											} else {
												if($balance_cb > 0)
													$balance_cb = $balance_cb;
												$balance_prnt_cb = number_format($balance_cb,2);
											}
											
											$balance_prnt_ucb = ($nbalance - $balance_cb);
										?>
										<tr>
											<td>{{$trans->voucher_type}}</td>
											<td><?php  echo $trans->reference; ?></td>
											<td><?php echo ($trans->invoice_date=='0000-00-00' || $trans->invoice_date=='01-01-1970')?date('d-m-Y', strtotime($settings->from_date)):date('d-m-Y', strtotime($trans->invoice_date)); ?></td>
											<td><?php echo ($trans->voucher_type=="OB")?'Opening Balance':$trans->description;?></td>
											<td>{{($trans->reference_from=="")?$trans->reference:$trans->reference_from}}</td>	
											<td>@if(isset($actrndata[$trans->id]))
											{{($trdate >= $fdate && $trdate <= $tdate)?date('d-m-Y',strtotime($actrndata[$trans->id])):''}}
											@endif</td>
											<td class="emptyrow text-right"><?php echo $dr_amount;?></td>
											<td class="emptyrow text-right">{{$cr_amount}}</td>
											<td class="emptyrow text-right">{{$balance_prnt}}</td>
										</tr>
										
										@endforeach	
										@php $Gdr_total += $dr_total; $Gcr_total += $cr_total; @endphp
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td> <td></td>
											<td class="highrow text-right"><strong><?php echo ($ispdc && count($pdcs) > 0)?'Total with PDC':'Total';?>:</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{$balance_prnt}}</strong></td>
										</tr>
									</table>
									@endif
									<hr/>
									@endforeach
									@php $Sdr_total += $Gdr_total; $Scr_total += $Gcr_total; @endphp
									
									@endforeach
								
									</td>
								</tr>
								
								<tr><td align="right">
									<p><b>Cleared Balance: {{$balance_prnt_cb}}</b></p>
									<p><b>Uncleared Balance: {{ $balance_prnt_ucb }}</b></p>
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
								
                                </span>
                        </div>
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

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>
function getExport() { document.frmExport.submit(); }

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
	$('.chqdate').datepicker({
		language: 'en',
		dateFormat: 'dd-mm-yyyy',
		autoClose: 1
	});
	
});
</script>
</html>

