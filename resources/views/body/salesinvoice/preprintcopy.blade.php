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
    <table border="0" style="width:100%;">
								<tr><td width="60%" align="center" ><h3><img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" /></td></h3></td>
									
								</tr>
								
							</table><br/>
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<?php //if(count($transactions) > 0) { ?>
						@if($type=='pos_summary')
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
									    
									    
									
									
									
								
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									<tr>
											<td width="5%"><b>Customer</b></td>
											<td width="5%"><b>Net Total</b></td>
											
										</tr>
										
										
                                        <?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
									
                                        @foreach($report as $row)
										<?php
											
										
										
										$total += $row->total;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += ($row->total - $row->discount);
											 // $amount_total += $total + $discount + $vat_amount;
											
										?>
										@endforeach
									
										<tr>
											<td>{{$key}}</td>
                                            <td> {{number_format($amount_total,2)}} </td>
											
										</tr>
                                        <?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
										@endforeach	
										
                                        <tr>
									<!-- <td class="text-right"></td> -->
											<td ><b>Total:</b></td>
										
										
											<td ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</table>
									
								
								
									
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
								
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot_stmt')</td>
								</tr>
							</tfoot>
						</table>
						@elseif($type=='summary')
						
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">@include('main.print_head_stmt')</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									
								
									
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="15%"><b>Customer</b></td>
											<td width="10%"><b>Gross Amt.</b></td>
											<td width="45%">Discount</b></td>
											
											<td width="10%" class="text-right"><b>VAT Amt.</b></td>
											<td width="10%" class="text-right"><b>Net Total</b></td>
										
										</tr>
										<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										@foreach($report as $row)
										<?php
											
										
										
										$total += $row->total;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += ($row->total - $row->discount);
											 // $amount_total += $total + $discount + $vat_amount;
											
										?>
										@endforeach
									
										<tr>
											<td>{{$key}}</td>
											<td>{{number_format($total,2)}}</td>
											<td>{{number_format($discount,2)}}<?php //echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>	
											
											<td class="emptyrow text-right">{{number_format($vat_amount,2)}}</td>
											<td class="emptyrow text-right">{{number_format($amount_total,2)}}</td>
											
										</tr>
										
                                        <?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
								
										@endforeach	
										<tr>
									<!-- <td class="text-right"></td> -->
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netvat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</table>
									<hr/>
								
									</td>
								</tr>
								
							
								
								
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer"><br/>@include('main.print_foot_stmt')</td>
								</tr>
							</tfoot>
						</table>
						@elseif($type=='detail')
							<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">@include('main.print_head_stmt')</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>

								<tr>
									<td align="left" style="padding-left:0px;">
										<p>
										<br/>	</td>
									<td align="right" style="padding-left:0px;">
										<br/>
									</td>
								</tr>
								
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
								foreach($reports as $report) { ?>
									<table border="0" style="width:100%;height:100%;">
								<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Cus.Name: {{$report[0]->customer}}<br/>
											
											</b></h6>
										</td>
									
									</tr>
										</table>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<thead>
											<tr>
												<th >SI.#</th>
												<th>
													<strong>PO.No</strong>
												</th>
												<th>
													<strong>PI.No</strong>
												</th>
												<th >
													<strong>PI.Date</strong>
												</th>
												<th >
													<strong>Description</strong>
												</th>
											   <th >
                                               Unit Price.
												</th>
                                                <th >
                                                Discount
												</th>
                                                <th >
                                                VAT Amt.
												</th>

                                                <th >
                                                Net Total
												</th>
											</tr>
											</thead>
											<tbody>
                                            <?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1; 	?>
											
                                            @foreach($report as $row)
                                            <?php 	$sprices=$row->unit_price;
												//$sprice = $row['total'] ;
												$pprices = $row->vat_amount; 
												$pprice = $row->vat_amount; 
												$profit = ($row->line_total-$row->discount); 
												
												$sptotal += $sprices;
												$dtotal +=$row->discount; 
											
												$ptotal += $profit;
												 $n = $i;
												//$sqty += $row['squantity'];
												$tcst += $pprices;
										?>
										
											<tr>
											<td>{{$i++}}</td>
											<td>{{$row->document_id}}</td>
											<td >{{$row->voucher_no}}</td>
											
											<td >{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td >{{$row->item_name}}</td>
											
											<td >{{number_format($sprices,2)}}</td>
											<td >{{number_format($row->discount,2)}}</td>
											<td >{{number_format($pprices,2)}}</td>
											<td  width="18%">{{number_format($profit,2)}}</td>
											
										</tr>
												
											@endforeach
												<?php  $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $ptotal;
										$nqty += $sqty;
									?>
										<tr>
									<td></td>
										<td colspan="4" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
										<td width="5%"  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
									
									</tr>
												<?php } ?>
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
						@else
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="center">@include('main.print_head_stmt')</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
											@foreach($transactions as $key => $transaction)
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
									
											<table class="tblstyle table-bordered" border="0" width="100%">
												<thead>
												<tr>
													<th >Inv.Date</th>
													<th >
														<strong>Ref.No</strong>
													</th>
													<th >
														<strong>Description</strong>
													</th>
													@if($jobid)
													<th >
														<strong>Job No</strong>
													</th>
													@endif
													<th class="text-right">
														<strong>Due Amt.</strong>
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
												</tr>
												</thead>
												<tbody>
												<?php $cr_total = 0; $dr_total = 0; $balance = 0; $balance_prnt = 0;
														$amt1T = $amt2T = $amt3T = $amt4T = $amt5T = 0;
												//$resultrow->op_balance;?>
												@foreach($transaction as $trans)
												<?php
												//if($transaction->voucher_type == 'OBD') {
														$cr_amount = ''; $dr_amount = '';
														$balance_prnt = $trans['dr_amount'] - $trans['cr_amount'];	
														$balance += $balance_prnt;
													
														$nodays = date_diff(date_create($trans['invoice_date']),date_create(date('Y-m-d')));
														$amt1 = $amt2 = $amt3 = $amt4 = $amt5 = '';
														if($nodays->format("%a%") <= 30) {
															$amt1 = $balance_prnt;
															$amt1T += $amt1;
														} else if($nodays->format("%a%") > 30 && $nodays->format("%a%") <= 60) {
															$amt2 = $balance_prnt;
															$amt2T += $amt2;
														} else if($nodays->format("%a%") > 60 && $nodays->format("%a%") <= 90) {
															$amt3 = $balance_prnt;
															$amt3T += $amt3;
														} else if($nodays->format("%a%") > 91 && $nodays->format("%a%") <= 120) {
															$amt4 = $balance_prnt;
															$amt4T += $amt4;
														} else if($nodays->format("%a%") > 120) {
															$amt5 = $balance_prnt;
															$amt5T += $amt5;
														}
														
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
												?>
													<tr>
														<td><?php echo date('d-m-Y', strtotime($trans['invoice_date'])); ?></td>
														<td>{{$trans['reference_from']}}</td>
														<td>{{$trans['description']}}<?php //echo date('d-m-Y', strtotime($transaction['invoice_date'])); ?></td>
														@if($jobid)<td>{{$trans['jobno']}}</td>@endif
														<td class="emptyrow text-right">{{$balance_prnt}}</td>
														<td class="emptyrow text-right">{{$amt1}}</td>
														<td class="emptyrow text-right">{{$amt2}}</td>
														<td class="emptyrow text-right">{{$amt3}}</td>
														<td class="emptyrow text-right">{{$amt4}}</td>
														<td class="emptyrow text-right">{{$amt5}}</td>
													</tr>
												<?php } ?>
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
													<td></td> @if($jobid)<td></td>@endif
													<td><strong>Total:</strong></td>
													<td class="highrow text-right"><strong>{{$balance}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt1T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt2T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt3T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt4T}}</strong></td>
													<td class="emptyrow text-right"><strong>{{$amt5T}}</strong></td>
												</tr>
												</tbody>
											</table>
											<hr/>
											@endforeach
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
						<?php //} ?>
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('sales_invoice/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="customer_id" value="{{$customer}}" >
					<input type="hidden" name="item_id" value="{{$item}}" >
					<input type="hidden" name="salesman" value="{{$salesman}}" >
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

