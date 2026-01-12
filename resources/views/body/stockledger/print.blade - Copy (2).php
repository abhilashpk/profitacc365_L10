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
<div>
    <!-- Left side column. contains the logo and sidebar -->
    
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
					<?php if(count($results) > 0) { 
					
						if($voucherhead=='Stock Ledger with Quantity') { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding-left:0px;">
										<h6><b>Item Code: <b>{{$results['opn_details'][0]->item_code}}<br/>
											   Item Name: <b>{{$results['opn_details'][0]->description}}</b></h6>
									</td>
									<td align="right" style="padding-left:0px;"><br/>
									</td>
								</tr>
							</thead>
							
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="10%"><b>Type</b></td>
											<td width="10%"><b>Vchr.No.</b></td>
											<td width="15%"><b>Tr.Date</b></td>
											<td width="20%"><b>Supp/Cust.</b></td>
											@if($jobno)<td width="20%"><b>Job No</b></td>@endif
											<td width="15%" class="text-right"><b>Qty.In</b></td>
											<td width="15%" class="text-right"><b>Qty.Out</b></td>
											<td width="15%" class="text-right"><b>Balance</b></td>
										</tr>
										
										<?php $qtyin = $qtyout = $bal = 0; ?>
										@foreach($results['opn_details'] as $opndetails)
										<tr>
											<td>OQ</td>
											<td></td>
											<td></td>
											<td>Opening Quantity</td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											@if($jobno)<td></td>@endif
											<td class="text-right"></td>
											<td class="text-right">{{$opndetails->opn_quantity}}</td>
											<?php $qtyin += $opndetails->opn_quantity; $bal = $qtyin; ?>
										</tr>
										@endforeach
										
										@foreach($results['pursales'] as $result)
										<tr>
											<?php if($result->type=='PI' || $result->type=='SR' || $result->type=='GR' || $result->type=='TI' || $result->type=='SDO') {
													$bal += $result->quantity;
													$qtyin += $result->quantity;
												 } elseif($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO') {
													$bal -= $result->quantity;
													$qtyout += $result->quantity; 
												 } 
											?>
											
											<td>{{$result->type}}</td>
											<td>{{$result->voucher_no}}</td>
											<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
											<td>{{$result->master_name}}</td>
											@if($jobno)<td>{{$result->jobno}}</td>@endif
											<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR' || $result->type=='GR' || $result->type=='TI' || $result->type=='SDO') echo $result->quantity;?></td>
											<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO') echo $result->quantity;?></td>
											<td class="text-right">{{$bal}}</td>
											
										</tr>
										@endforeach
										
									<?php $balance = $qtyin - $qtyout; ?>
										<tr>
											<td colspan="4" class="text-right"><b>Total: </b></td>
											@if($jobno)<td></td>@endif
											<td class="text-right"><b>{{$qtyin}}</b></td>
											<td class="text-right"><b>{{$qtyout}}</b></td>
											<td class="text-right"><b>{{$balance}}</b></td>
										</tr>
									
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
								</tr>
							</tfoot>
						</table>
						<?php } else if($voucherhead=='Stock Ledger with Location') { ?>
								<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									<tr>
										<td align="left" colspan="2" valign="top" style="padding-left:0px;">
											<h6><b>Item Code: <b>{{$results['opn_details'][1][0]->item_code}}, Item Name: <b>{{$results['opn_details'][1][0]->description}}</b></h6>
										</td>
									</tr>
								</thead>
									<tbody id="bod">
										<tr style="border:0px solid black;">
											<td colspan="2" align="center">
												<table border="0" style="width:100%;" class="tblstyle table-bordered">
												<tr>
													<td width="10%"><b>Type</b></td>
													<td width="10%"><b>Vchr.No.</b></td>
													<td width="15%"><b>Tr.Date</b></td>
													<td width="20%"><b>Supp/Cust.</b></td>
													@if($jobno)<td width="20%"><b>Job No</b></td>@endif
													<td width="15%" class="text-right"><b>Qty.In</b></td>
													<td width="15%" class="text-right"><b>Qty.Out</b></td>
													<td width="15%" class="text-right"><b>Balance</b></td>
												</tr>
												@foreach($results['opn_details'] as $k => $opndetails)
													@foreach($opndetails as $opndetail)
													<tr>
														<td>OQ</td>
														<td></td>
														<td></td>
														<td>Opening Quantity</td>
														<td class="text-right">{{$opndetail->opn_quantity}}</td>
														@if($jobno)<td></td>@endif
														<td class="text-right"></td>
														<td class="text-right">{{$opndetail->opn_quantity}}</td>
														<?php $qtyin = $opndetail->opn_quantity;?>
													</tr>
													@endforeach
												@endforeach

											<?php foreach($results['pursales'] as $results) { ?>
												<tr>
													<td align="left" valign="top" style="padding-left:0px;" colspan="7">
														<h6><b>Location Code: {{$results[0]->code}}, Location Name: {{$results[0]->name}}</b></h6>
													</td>
												</tr>
												<?php $qtyout = 0; ?>
												@foreach($results as $result)
												<tr>
													<td>{{$result->type}}</td>
													<td>{{$result->voucher_no}}</td>
													<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
													<td>{{$result->master_name}}</td>
													<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR') echo $result->lqty;?></td>
													<td class="text-right"><?php if($result->type=='CDO' || $result->type=='SI' || $result->type=='PR') echo $result->lqty;?></td>
													<td class="text-right">{{$result->cur_quantity}}</td>
												
													<?php if($result->type=='PI' || $result->type=='SR') 
																$qtyin += $result->lqty;
														   elseif($result->type=='SI' || $result->type=='PR' || $result->type=='CDO') 
																$qtyout += $result->lqty; ?>
												</tr>
												@endforeach
												<?php $balance = $qtyin - $qtyout; ?>
													<tr>
														<td colspan="4" class="text-right"><b>Total: </b></td>
														<td class="text-right"><b>{{$qtyin}}</b></td>
														<td class="text-right"><b>{{$qtyout}}</b></td>
														<td class="text-right"><b>{{$balance}}</b></td>
													</tr>
													<?php } ?>
													</table>
													</td>
												</tr>
											
											</tbody>
											<tfoot id="inv">
												<tr>
													<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
												</tr>
											</tfoot>
								</table>
						<?php } else { ?>
								<table border="0" style="width:100%;height:100%;">
									<thead>
										<tr>
											<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
										</tr>
										<tr>
											<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b><br/></td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding-left:0px;">
												<h6><b>Item Code: <b>{{$results['opn_details'][0]->item_code}}<br/>
													   Item Name: <b>{{$results['opn_details'][0]->description}}</b></h6>
											</td>
											<td align="right" style="padding-left:0px;"><br/>
											</td>
										</tr>
									</thead>
									
									<tbody id="bod">
										<tr style="border:0px solid black;">
											<td colspan="2" align="center">
											<table border="0" style="width:100%;" class="tblstyle table-bordered">
												<tr>
													<td style="width:50px;"><strong>Type</strong></td>
													<td><strong>Vchr.No.</strong></td>
													<td><strong>Tr.Date</strong></td>
													<td><strong>Supp/Cust.</strong></td>
													@if($jobno)<td><strong>Job No</strong></td>@endif
													<td class="text-right"><strong>Qty.In</strong></td>
													<td class="text-right"><strong>Pur.Cost</strong></td>
													<td class="text-right"><strong>Cost Avg.</strong></td>
													<td class="text-right"><strong>Sl. Cost</strong></td>
													<td class="text-right"><strong>Qty.Out</strong></td>
													<td class="text-right"><strong>Sl. Price</strong></td>
													<td class="text-right"><strong>Balance</strong></td>
												</tr>
												<?php $qtyin = $qtyout = $pcost = $scost = $costavg = $costsi = 0; ?>
												@foreach($results['opn_details'] as $opndetails)
												<tr>
													<td>OQ</td>
													<td></td>
													<td></td>
													<td>Opening Quantity</td>
													@if($jobno)<td></td>@endif
													<td class="text-right">{{$opndetails->opn_quantity}}</td>
													<td class="text-right"></td>
													<td class="text-right"><?php echo number_format($opndetails->cost_avg,2); ?></td>
													<td class="text-right"></td>
													<td class="text-right"></td>
													<td class="text-right"></td>
													<td class="text-right">{{$opndetails->opn_quantity}}</td>
													<?php $qtyin += $opndetails->opn_quantity; $costavg += $opndetails->cost_avg; ?>
												</tr>
												@endforeach
												
												@foreach($results['pursales'] as $result)
												<tr>
													<?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO') {
															$qtyin += $result->quantity;
															$pcost += $result->pur_cost;
															$costavg += $result->cost_avg;
															
														  } elseif($result->type=='SI' || $result->type=='PR' || $result->type=='GI' || $result->type=='TO') {
															 $costsi = $result->sale_cost;
															 $qtyout += $result->quantity; 
															 $scost += $result->unit_cost;		
															 $costsi += $costsi;
															 $costavg += $result->cost_avg;
													} $curbalance = $qtyin - $qtyout; ?>
													<td>{{$result->type}}</td>
													<td>{{$result->voucher_no}}</td>
													<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
													<td>{{$result->master_name}}</td>
													@if($jobno)<td>{{$result->jobno}}</td>@endif
													<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO') echo $result->quantity;?></td>
													<td class="text-right"><?php if($result->type=='PI' || $result->type=='SR' || $result->type=='TI' || $result->type=='GR' || $result->type=='SDO') echo number_format($result->pur_cost,3);?></td>
													<td class="text-right"><?php /* if($result->type=='PI' || $result->type=='SR') */ echo number_format($result->cost_avg,3);?></td>
													<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo number_format($result->sale_cost,3);?></td>
													<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo $result->quantity;?></td>
													<td class="text-right"><?php if($result->type=='SI' || $result->type=='PR' || $result->type=='TO' || $result->type=='GI') echo number_format($result->unit_cost,3);?></td>
													<td class="text-right">{{$curbalance}}</td>
												</tr>
												@endforeach
												
											<?php $balance = $qtyin - $qtyout; ?>
											<tr>
												<td colspan="4" class="text-right"><b>Total: </b></td>
												@if($jobno)<td></td>@endif
												<td class="text-right"><b>{{$qtyin}}</b></td>
												<td class="text-right"><b>{{number_format($pcost,2)}}</b></td>
												<td class="text-right"><b>{{$costavg}}</b></td>
												<td class="text-right"><b>{{$costsi}}</b></td>
												<td class="text-right"><b>{{$qtyout}}</b></td>
												<td class="text-right"><b>{{number_format($scost,2)}}</b></td>
												<td class="text-right"><b>{{$balance}}</b></td>
											</tr>
											
											</table>
											</td>
										</tr>
									</tbody>
									<tfoot id="inv">
										<tr>
											<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
										</tr>
									</tfoot>
								</table>
						<?php } //end if ?>
						
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php } ?>
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
						
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('stock_ledger/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="document_id" value="{{$docid}}" >
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
function getExport() {
	document.frmExport.submit();
}

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
