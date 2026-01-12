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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
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

</style>
<style type="text/css" media="print">

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
											<?php if($report) { ?>
												<table class="table" style="border:1px solid black;">
													<thead>
														<tr class="bg-primary">
															<th align="center" colspan="2">LIABILITIES</th><th align="center" colspan="2">ASSETS</th>
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
															<?php if($type=='summary') { ?>
																<table border="0" width="100%">
																	<tbody>
																	@foreach($liability as $rows)
																		<tr>
																			<td height="20"><strong>{{$rows['name']}}</strong></td>
																			<?php
																				if($rows['total'] > 0) {
																					$cl_amount = '('.number_format($rows['total'],2).')';
																				} else 
																					$cl_amount = number_format(($rows['total']*-1),2);
																			?>
																			<td class="text-right"><strong>{{$cl_amount}}</strong></td>
																		</tr>
																		@foreach($rows['items'] as $row)
																		<?php
																			if($row['amount'] > 0) {
																				$amount = '('.number_format($row['amount'],2).')';
																			} else 
																				$amount = number_format(($row['amount']*-1),2);
																		?>
																			<tr>
																				<td style="padding-left:10px;">{{$row['group_name']}}</td>
																				<td height="20" class="text-right">{{$amount}}</td>
																			</tr>
																		@endforeach
																	@endforeach
																	
																	<?php if($netprofit > 0) { ?>
																	<tr>
																		<td height="20"><strong>Net Profit</strong></td>
																		<?php
																			if($netprofit < 0) {
																				$arr = explode('-', $netprofit);
																				$netprofit = number_format($arr[1],2);
																			} else 
																				$netprofit = number_format($netprofit,2);
																		?>
																		<td class="text-right"><strong>{{$netprofit}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	
																	<?php if($differencel) { ?>
																	<tr>
																		<?php
																			if($differencel < 0) {
																				$arr = explode('-', $differencel);
																				$difference = number_format($arr[1],2);
																			} else 
																				$difference = number_format($differencel,2);
																		?>
																		<td height="20"><strong>Difference</strong></td>
																		<td class="text-right"><strong>{{$difference}}</strong></td>
																	</tr>
																	<?php } ?>
																	</tbody>
																</table>
															<?php } else { ?>
																<table border="0" width="100%">
																	
																	<tbody>
																	@foreach($liability as $rows)
																		<tr class="catrow">
																			<td height="20"><b>{{$rows['catname']}}</b></td>
																			<?php
																				if($rows['total'] > 0) {
																					//$cl_amount = number_format($rows['total'],2);
																					$cl_amount = '('.number_format( ($rows['total']),2).')';
																				} else 
																					$cl_amount = number_format(($rows['total']*-1),2);
																			?>
																			<td class="text-right"><b>{{$cl_amount}}</b></td>
																		</tr>
																		@foreach($rows['items'] as $row)
																		<tr class="grprow">
																			<td height="20" style="padding-left:5px;"><b>{{$row['name']}}</b></td>
																			<?php
																				if($row['total'] > 0) {
																					//$gamount = number_format($row['total'],2);
																					$gamount = '('.number_format($row['total'],2).')';
																				} else 
																					$gamount = number_format(($row['total']*-1),2);
																			?>	
																			<td class="text-right"><b>{{$gamount}}</b></td>
																			@foreach($row['gitems'] as $item)
																			<?php
																				if($item['amount'] > 0) {
																					//$amount = number_format($item['amount'],2);
																					$amount = '('.number_format($item['amount'],2).')';
																				} else 
																					$amount = number_format(($item['amount']*-1),2);
																			?>
																			<?php if($amount!=0) { ?>
																			<tr class="itmrow">
																				<td style="padding-left:10px;">{{$item['group_name']}}</td>
																				<td class="text-right">{{$amount}}</td>
																			</tr>
																			<?php } ?>
																			@endforeach
																			
																		</tr>
																		@endforeach
																	@endforeach
																	
																	<?php if($netprofit > 0) { ?>
																	<tr>
																		<td height="20"><strong>Net Profit</strong></td>
																		<?php
																			if($netprofit < 0) {
																				$arr = explode('-', $netprofit);
																				$netprofit = number_format($arr[1],2);
																			} else 
																				$netprofit = number_format($netprofit,2);
																		?>
																		<td class="text-right"><strong>{{$netprofit}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	
																	<?php if($differencel) { ?>
																	<tr>
																		<?php
																			if($differencel < 0) {
																				$arr = explode('-', $differencel);
																				$difference = number_format($arr[1],2);
																			} else 
																				$difference = number_format($differencel,2);
																		?>
																		<td height="20"><strong>Difference</strong></td>
																		<td class="text-right"><strong>{{$difference}}</strong></td>
																	</tr>
																	<?php } ?>
																	</tbody>
																</table>
															<?php } ?>
															
															</td>
															<td colspan="2">
															
															<?php if($type=='summary') { ?>
																<table border="0" width="100%">
																	
																	<tbody>
																	   
																	@foreach($assets as $rows)
																	
																		<tr>
																			<td height="20"><strong>{{$rows['name']}}</strong></td>
																			<?php
																				if($rows['total'] > 0) {
																					$astotal = number_format($rows['total'],2);
																				} else 
																					$astotal = '('.number_format( ($rows['total']*-1),2).')';
																			?>
																			<td class="text-right"><strong>{{$astotal}}</strong></td>
																		</tr>
																		@foreach($rows['items'] as $row)
																			<tr>
																				<td style="padding-left:10px;">{{$row['group_name']}}</td>
																				<?php
																					if($row['amount'] > 0) {
																						$asamount = number_format($row['amount'],2);
																					} else 
																						$asamount = '('.number_format( ($row['amount']*-1),2).')';
																				?>
																				<td class="text-right">{{$asamount}}</td>
																			</tr>
																		@endforeach
																	@endforeach
																														
																	<?php if($netprofit < 0) { ?>
																	<tr>
																		<td height="20"><strong>Net Loss</strong></td>
																		<?php
																			if($netprofit < 0) {
																				$arr = explode('-', $netprofit);
																				$netprofit = '('.number_format($arr[1],2).')';
																			} else 
																				$netprofit = number_format($netprofit,2);
																		?>
																		<td class="text-right"><strong>{{$netprofit}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	<?php if($differencer) { ?>
																	<tr>
																		<?php
																			if($differencer < 0) {
																				$arr = explode('-', $differencer);
																				$difference = '('.number_format($arr[1],2).')';
																			} else 
																				$difference = number_format($differencer,2);
																		?>
																		<td height="20"><strong>Difference</strong></td>
																		<td class="text-right"><strong>{{$difference}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	</tbody>
																</table>
															<?php } else { ?>
																<table border="0" width="100%">
																	
																	<tbody>
																	@foreach($assets as $rows)
																		<tr class="catrow">
																			<td height="20"><strong>{{$rows['catname']}}</strong></td>
																			<?php
																				if($rows['total'] > 0) {
																					$amount1 = number_format($rows['total'],2);
																				} else 
																					$amount1 = '('.number_format( ($rows['total']*-1),2).')';
																			?>
																			<td class="text-right"><strong>{{$amount1}}</strong></td>
																		</tr>
																		@foreach($rows['items'] as $row)
																			<tr class="grprow">
																				<td height="20" style="padding-left:5px;"><strong>{{$row['name']}}</strong></td>
																				<?php
																					if($row['total'] > 0) {
																						$amount2 = number_format($row['total'],2);
																					} else 
																						$amount2 = '('.number_format( ($row['total']*-1),2).')';
																				?>
																				<td class="text-right"><b>{{$amount2}}</b></td>
																			</tr>
																			@foreach($row['gitems'] as $item)
																			<?php if($item['amount']!=0) { ?>
																				<tr class="itmrow">
																					<td height="20" style="padding-left:10px;">{{$item['group_name']}}</td>
																					<?php
																						if($item['amount'] > 0) {
																							$amount3 = number_format($item['amount'],2);
																						} else 
																							$amount3 = '('.number_format( ($item['amount']*-1),2).')';
																					?>
																					<td class="text-right">{{$amount3}}</td>
																				</tr>
																			<?php } ?>
																			@endforeach
																			
																		@endforeach
																		
																	@endforeach
																														
																	<?php if($netprofit < 0) { ?>
																	<tr>
																		<td height="20"><strong>Net Loss</strong></td>
																		<?php
																			if($netprofit < 0) {
																				$arr = explode('-', $netprofit);
																				$netprofit = '('.number_format($arr[1],2).')';
																			} else 
																				$netprofit = number_format($netprofit,2);
																		?>
																		<td class="text-right"><strong>{{$netprofit}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	<?php if($differencer) { ?>
																	<tr>
																		<?php
																			if($differencer < 0) {
																				$arr = explode('-', $differencer);
																				$difference = '('.number_format($arr[1],2).')';
																			} else 
																				$difference = number_format($differencer,2);
																		?>
																		<td height="20"><strong>Difference</strong></td>
																		<td class="text-right"><strong>{{$difference}}</strong></td>
																	</tr>
																	<?php } ?>
																	
																	</tbody>
																</table>
															<?php } ?>
															</td>
														</tr>
														<tr>
														<?php
																if($total < 0) {
																	$arr = explode('-', $total);
																	$total = '('.number_format($arr[1],2).')';
																} else 
																	$total = number_format($total,2);
															?>
														<td><strong>Total: {{$currency}}</strong></td><td class="text-right" ><strong>{{$total}}</strong></td><td><strong>Total: {{$currency}}</strong></td><td class="text-right" ><strong>{{$total}} </strong></td>
													</tr>
													</body>
												</table>
										  <?php } else { ?>
												<div class="well well-sm">No reports were found! </div>
										  <?php } ?>
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
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('balance_sheet/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<!--<input type="hidden" name="chkob" value="{{$chkob}}" >-->
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
