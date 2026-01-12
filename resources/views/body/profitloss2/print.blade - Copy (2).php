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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
	@yield('header_styles')
    <!--end of page level css-->
	<style>
		.catrow {
			height:40px !important;
		}
		.grprow {
			height:35px !important; padding-left:10px !important;
		}
		.itmrow {
			height:30px !important;
		}
	</style>


<style>
#invoicing {
	font-size:9pt;
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
												
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="center">@include('main.print_head_stmt')</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$voucherhead}}</u></b></b></td>
									</tr>
									<tr><td><br/></td></tr>
									<tr>
										<td colspan="2" align="left" valign="top" style="padding-left:0px;">
											<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
										</td>
									</tr>
									
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
									<table class="table" style="border:1px solid black;">
                                    <thead>
                                    <tr class="bg-primary">
										<th align="center" colspan="2">Expense</th><th align="center" colspan="2">Income</th>
                                    </tr>
									
									<tr>
										<td><strong>Description</strong></td>
										<td class="text-right"><strong>Amount</strong></td>
										<td><strong>Description</strong></td>
										<td class="text-right" ><strong>Amount</strong></td>
									</tr>
										
                                    </thead>
									<body>
										<tr>
											<td colspan="2">
												<table border="0" width="100%">
													<tbody>
													<?php if($directexp) { ?>
													<tr class="catrow">
														<td height="20"><strong>{{$directexp['name']}}</strong></td>
														<?php
															if($directexp['total'] < 0) {
																$arr = explode('-', $directexp['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($directexp['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($directexp['items'] as $row)
														<tr class="catrow"><?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { if($row->cl_balance!=0) { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$clbalance = '('.number_format($arr[1],2).')';
																} else 
																	$clbalance = number_format($row->cl_balance,2);
															?>
															<?php if($row->cl_balance!=0) { ?>
															<td class="text-right" height="20">{{$clbalance}}</td>
														<?php } ?>
														</tr>
													@endforeach
													<?php } ?>
													
													<tr class="catrow">
														<td><?php if($grossprofit > 0) echo '<strong>Gross Profit C/F</strong>'; ?></td>
														<td class="text-right" height="20"><?php if($grossprofit > 0) echo '<strong>'.number_format($grossprofit,2).'</strong>';?></td>
													</tr>
													<tr class="catrow">
														<td height="20"><strong>Sub Total</strong></td>
														<?php
															if($subtotal < 0) {
																$arr = explode('-', $subtotal);
																$subtotal = '('.number_format($arr[1],2).')';
															} else 
																$subtotal = number_format($subtotal,2);
														?>
														<td class="text-right"><strong>{{$subtotal}}</strong></td>
													</tr>
													<?php if($grossprofit < 0) { 
																$arr = explode('-', $grossprofit);
																$gross_loss_bf = '('.number_format($arr[1],2).')';
													?>
													<tr class="catrow">
														<td height="20"><strong>Gross Loss B/F</strong></td>
														<td class="text-right"><strong>{{$gross_loss_bf}}</strong></td>
													</tr>
													<?php } ?>
													
													<?php if($indirectexp) { ?>
													
													<tr class="catrow">
														<td height="20"><strong>{{$indirectexp['name']}}</strong></td>
														<?php
															if($indirectexp['total'] < 0) {
																$arr = explode('-', $indirectexp['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($indirectexp['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($indirectexp['items'] as $row)
													<?php if($row->cl_balance != 0) { ?>
														<tr class="catrow" ><?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$clbalance = '('.number_format($arr[1],2).')';
																} else 
																	$clbalance = number_format($row->cl_balance,2);
															?>
															<td class="text-right" height="20">{{$clbalance}}</td>
														</tr>
													<?php } ?>
													@endforeach
													<?php } ?>
													
													<?php if($netprofit > 0) { ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr class="catrow">
														<td><h5><b>Net Profit</b></h5></td>
														<?php
															if($netprofit < 0) {
																$arr = explode('-', $netprofit);
																$netprofit = '('.number_format($arr[1],2).')';
															} else 
																$netprofit = number_format($netprofit,2);
														?>
														<td class="text-right"><h5><strong>{{$netprofit}}</strong></h5></td>
													</tr>
													<?php } ?>
													</tbody>
												</table>
											</td>
											<td colspan="2">
												<table border="0" width="100%">
													<tbody>
													<?php if($directinc) { ?>
													<tr class="catrow">
														<td height="20"><strong>{{$directinc['name']}}</strong></td>
														<?php
															if($directinc['total'] < 0) {
																$arr = explode('-', $directinc['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($directinc['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($directinc['items'] as $row)
														<tr class="catrow">
														<?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$cl_balance = '('.number_format($arr[1],2).')';
																} else 
																	$cl_balance = number_format($row->cl_balance,2);
															?>
															<td class="text-right" height="20">{{$cl_balance}}</td>
														</tr>
													@endforeach
													<?php } ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr class="catrow">
														<td height="20"><?php if($grossprofit < 0) echo '<strong>Gross Loss C/F</strong>'; ?></td>
														<td class="text-right">
														<?php if($grossprofit < 0) { 
																$arr = explode('-', $grossprofit);
																$gross_loss_cf = '('.number_format($arr[1],2).')';
																echo '<strong>'.$gross_loss_cf.'</strong>';
														}
														?></td>
													</tr>
													
													<?php if($indirectinc) { ?>
													<tr class="catrow">
														<td height="20"><strong>{{$indirectinc['name']}}</strong></td>
														<?php
															if($indirectinc['total'] < 0) {
																$arr = explode('-', $indirectinc['total']);
																$balance = '('.number_format($arr[1],2).')';
															} else 
																$balance = number_format($indirectinc['total'],2);
														?>
														<td class="text-right"><strong>{{$balance}}</strong></td>
													</tr>
													@foreach($indirectinc['items'] as $row)
														<tr class="catrow">
														<?php if($voucherhead=='Profit & Loss - Summary') {?>
															<td style="padding-left:10px;">{{$row->group_name}}</td>
														<?php } else { ?>
															<td style="padding-left:10px;">{{$row->master_name}}</td>
														<?php } ?>
															<?php
																if($row->cl_balance < 0) {
																	$arr = explode('-', $row->cl_balance);
																	$cl_balance = '('.number_format($arr[1],2).')';
																} else 
																	$cl_balance = number_format($row->cl_balance,2);
															?>
															<td class="text-right" height="20">{{$cl_balance}}</td>
														</tr>
													@endforeach
													<?php } ?>
													<tr class="catrow">
														<td height="20"><strong>Sub Total</strong></td>
														<td class="text-right">{{$subtotal}}</td>
													</tr>
													<?php if($grossprofit > 0) { ?>
													<tr >
														<td height="20"><strong>Gross Profit B/F</strong></td>
														<td class="text-right"><strong>{{number_format($grossprofit,2)}}</strong></td>
													</tr>
													<?php } elseif($grossprofit < 0) { $arr = explode('-', $netprofit);
																	$netloss = '('.number_format($arr[1],2).')'; ?>
													<tr class="catrow">
														<td></td>
														<td class="text-right"></td>
													</tr>
													<tr >
														<td height="20"><h5><strong>Net Loss</strong></h5></td>
														<td class="text-right"><strong>{{$netloss}}</strong></td>
													</tr>
													<?php } ?>
													
													</tbody>
												</table>
											</td>
										</tr>
										
										<tr>
											<td><strong>Total:</strong></td>
											<?php
												if($total < 0) {
													$arr = explode('-', $total);
													$total = '('.number_format($arr[1],2).')';
												} else 
													$total = number_format($total,2);
											?>
											<td class="text-right" ><strong>{{$total}}</strong></td>
											<td><strong>Total:</strong></td>
											<td class="text-right" ><strong>{{$total}}</strong></td>
										</tr>
									</body>
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
								
								<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
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
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('profit_loss/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<?php if($clstock) { ?><input type="text" name="cl_stock" value="{{$clstock}}"><?php } ?>
						<?php if($opstock) { ?><input type="text" name="op_stock" value="{{$opstock}}"><?php } ?>
					</form>
					
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
