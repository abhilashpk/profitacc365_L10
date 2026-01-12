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
					<?php if(count($reports) > 0) { 
						if($type=='expiry') { ?>
								<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								
							</thead>
							
							<tbody id="bod">
								<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
								foreach($reports as $report) { ?>
							
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="1%"><b>SI.#</b></td>
											<td width="6%"><b>Customer Name</b></td>
											<td width="8%"><b>Building Code</b></td>
											
											<td width="8%"><b>Building Name</b></td>
											<td width="8%"><b>Flat Code</b></td>
											<td width="8%"><b>Contract No.</b></td>
											<td width="7%" ><b>Contract Expiry Date</b></td>
											<td width="8%" class="text-right"><b>Rent Amount</b></td>
											<!-- <td width="18%"><b>Description</b></td>
											<td width="7%" ><b>Duration</b></td>
											<td width="8%" class="text-right"><b>Rent Amount</b></td>
											<td width="8%" class="text-right"><b>Grand Total</b></td> -->
											
										
											
										</tr>
										
										<?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
											foreach($report as $row) { 
												
												$sprices=$row->rent_amount;
												//$sprice = $row['total'] ;
											
												$profit = $row->grand_total; 
												
												$sptotal += $sprices;
												
											
												$ptotal += $profit;
												 $n = $i;
												//$sqty += $row['squantity'];
												
										?>
										
										
										<tr>
											<td>{{$i++}}</td>
											<td>{{$row->master_name}}</td>
											<td >{{$row->buildcode}}</td>
											<td >{{$row->buildname}}</td>
											<td >{{$row->flat}}</td>
											<td >{{$row->contract_no}}</td>
												<td >{{date('d-m-Y',strtotime($report[0]->end_date))}}</td>
												<td class="text-right">{{number_format($sprices,2)}}</td>
											<!-- <td >{{$row->description}}</td>
											<td >{{$row->duration}}</td>
											<td class="text-right">{{number_format($sprices,2)}}</td>
											
											<td   class="text-right">{{number_format($profit,2)}}</td>
											 -->
										</tr>
									<?php } $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $ptotal;
										$nqty += $sqty;
									?>
									
								 <tr>
									<td></td><td></td><td></td>
										<td colspan="4" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
										
										
									
									</tr>
									</table>
									</td>
								</tr>
							  <?php } ?>
							 <tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
										
										
											<td width="37%" colspan="4" align="right"><b>Net Total:</b></td>
											<td width="4%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
										
										
											
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
						<?php } else if($type=='buildingwise') { ?>
							<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr>
								
							</thead>
							
							<tbody id="bod">
								<?php 
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;$i=1;
								foreach($reports as $report) { ?>
								<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b><br/>
											
											</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
										<td width="7%"><b>SI.#</b></td>
											<td width="6%"><b>Customer Name</b></td>
											<td width="8%"><b>Building Code</b></td>
											
											<td width="8%"><b>Building Name</b></td>
											<td width="8%"><b>Flat Code</b></td>
											<td width="8%"><b>Contract No.</b></td>
												<td width="8%"><b>Contract Date</b></td>
											<td width="8%"><b></b></td>
											
										</tr>
										
										<?php 
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; 
											foreach($report as $row) { 
												
												$sprices=isset($row->unit_price)?$row->unit_price:0;
												//$sprice = $row['total'] ;
												$pprices = isset($row->vat_amount)?$row->vat_amount:0; 
												//$pprice = $row->vat_amount; 
												$profit = isset($row->line_total)?$row->line_total:0; 
												
												$sptotal += $sprices;
												//$dtotal +=$row->discount; 
											
												$ptotal += $profit;
												 $n = $i;
												//$sqty += $row['squantity'];
												$tcst += $pprices;
										?>
										
										
										<tr>
										<td>{{$i++}}</td>
											<td>{{$row->master_name}}</td>
											<td >{{$row->buildcode}}</td>
											<td >{{$row->buildname}}</td>
											<td >{{$row->flat}}</td>
											<td >{{$row->contract_no}}</td>
												<td >{{date('d-m-Y',strtotime($row->contract_date))}}</td>
											<td >Vacant</td>
										</tr>
									<?php } $peravg = $pertotal / $n; 
										$nsptotal += $sptotal;
										$ndtotal += $dtotal;
										$nctotal += $tcst;
										$nptotal += $ptotal;
										$nqty += $sqty;
									?>
									
									 <tr>
									<td></td>
										<!--	<td colspan="4" align="right"><b>Sub Total:</b></td>
										<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
										<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
										<td width="5%"  class="text-right"><b>{{number_format($ptotal,2)}}</b></td>-->
									
									</tr> 
									</table>
									</td>
								</tr>
							  <?php } ?>
								 <tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
									
										<td></td>
											<!--	<td width="18%" colspan="4" align="right"><b>Net Total:</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="6%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>-->
											
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
						

						<?php } else if($type=='tenantwise') { ?>
							<table border="0" class="table">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal =$ndqty=$nspftotal=	$ndftotal=$ncftotal	=$npftotal= $nqty = 0; foreach($reports as $report) {  if(!empty($report[7])){?>
									
										<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>  <br/>
											
											</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p></p>
										</td>
									</tr>
									<tr >
										<td colspan="2" align="center" style="font-size:12px;">
										<table border="0">
											<tr>
											<td width="2%"><b>SI.#</b></td>
										    <td width="8%"><b>Tenant</b></td>
											<td width="8%"><b>Building Code</b></td>
											<!-- <td width="8%"><b>Building Name</b></td> -->
											<td width="8%"><b>Flat Code</b></td>
											<td width="5%"><b>Contract No.</b></td>
												<td width="5%"><b>Contract Date</b></td>
											<td width="5%"><b>Expiry Date</b></td>
											
											
											</tr>
											
											<?php 
												$sptotal = $dtotal = $sptrotal=$sptaotal=$ctotal =$sprteotal=$spstotal=$cstotal =$sprtowtal=$pstotal = $dstotal=$ptotal = $pertotal = $peravg = 0; $i=1;
												
												$sptotal =$report[0]->taxdeposit;  
												 $dtotal = $report[5]->taxdeposit;  
												 $ctotal =  $report[1]->taxdeposit;
												 $ptotal =$report[6]->taxdeposit; 
													$sptrotal += $sptotal ;
														$sprteotal += $dtotal;
															$sprtowtal += $ctotal;
																$sptaotal += $ptotal;
												
													$spstotal +=$report[0]->Deposit;  
											 $dstotal += $report[5]->Deposit;  
												 $cstotal +=  $report[1]->Deposit;
												 $pstotal +=$report[6]->Deposit; 
												 
												 
												$n = $i; 
											?>
											
											<tr>
											<td>{{$i++}}</td>
										    <td >{{$report[7]->tenant}}</td>
											<td >{{$report[7]->buildcode}}</td>
											<!-- <td >{{$report[7]->buildname}}</td> -->
											<td >{{$report[7]->flat}}</td>
											<td >{{$report[7]->contract_no}}</td><br/>
											<td >{{date('d-m-Y',strtotime($report[7]->contract_date))}}</td>
                                           <td >{{date('d-m-Y',strtotime($report[7]->end_date))}}</td><br/>
											
									
											</tr>
										<?php $peravg = $pertotal / $n;
										
										$ndqty += $spstotal + $dstotal + $cstotal+ $pstotal ;
										
										$nqty += $sptrotal + $sprteotal + $sprtowtal + $sptaotal ;
										
										
									?>
										
										 <tr >
									
										
											<td colspan="6" class="text-right" style="font-size:12px;">
											    	<br/>
											<b>Details/Account Heads</b><br/><br/>
												<b>Rent:</b>{{$report[0]->Deposit}}<br/>
												<b>Parking Deposit:</b>{{$report[5]->Deposit}}<br/>
												<b>Deposit:</b>{{$report[1]->Deposit}}<br/>
												<b>Ejarie  :</b>{{$report[6]->Deposit}}<br/><br/><br/>
												<b>Grand Total   :</b>{{$report[7]->grand_total}}<br/>
												
											
											</td>
											<td  colspan="1"   style="font-size:12px;" class="text-right">
											    	<br/>
											<b>Tax</b><br/><br/>
												{{$report[0]->taxdeposit}}<br/>
												{{$report[5]->taxdeposit}}<br/>
												{{$report[1]->taxdeposit}}<br/>
											{{$report[6]->taxdeposit}}<br/><br/><br/>
												<br/>
												
											
											</td>
											
										</tr>
										
										</table>
										</td>
									</tr>
								  <?php } ?>
								  <?php } ?>
								  	<!-- <tr style="border:0px solid black;">
									<td colspan="2" align="center"><br/>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
									
										<tr>
											<td width="20%" colspan="2" align="right"><b>Net Total:</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
											<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
											<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
											<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
											
										</tr> 
									</table>
									</td>
								</tr> -->
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot')</td>
									</tr>
								</tfoot>
							</table>
							<?php } else if($type=='salesman') { ?>
							
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="left">@include('main.print_head_text')<br/></td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
									</tr>
									
								</thead>
								
								<tbody id="bod">
									<?php 
									$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
									foreach($reports as $report) { ?>
									<tr>
										<td align="left" valign="top" style="padding-left:0px;">
											<h6><b>Salesman: {{$report[0]['salesman']}}</b></h6>
										</td>
										<td align="right" style="padding-left:0px;">
											<p>Date: {{date('d-m-Y',strtotime($report[0]['voucher_date']))}}</p>
										</td>
									</tr>
									
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
											<tr>
												<td width="7%"><b>SI.#</b></td>
												<td width="13%"><b>Item Code</b></td>
												<td width="20%"><b>Description</b></td>
												<td width="8%"><b>Qty.</b></td>
												<td width="12%" class="text-right"><b>Sale Price</b></td>
												<td width="8%" class="text-right"><b>Discount</b></td>
												<td width="10%" class="text-right"><b>Cost</b></td>
												<td width="12%" class="text-right"><b>Profit</b></td>
												<td width="10%" class="text-right"><b>Profit%</b></td>
											</tr>
											
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0; $i=1;
												foreach($report as $row) { 
													$sprice = $row['squantity'] * $row['sunit_price'];
													if($row['class_id']==1)
														$cost = $row['sale_cost'];
													else
														$cost = 0;
													$pprice = $row['squantity'] * $cost;
													$profit = $sprice - $pprice - $row['discount'];
													$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
													$sptotal += $sprice;
													$dtotal += $row['discount'];
													$ctotal += $cost;
													$ptotal += $profit;
													$pertotal += $percentage; $n = $i;
													$sqty += $row['squantity'];
													$tcst += $pprice;
											?>
											
											<tr>
												<td>{{$i++}}</td>
												<td>{{$row['item_code']}}</td>
												<td >{{$row['description']}}</td>
												<td>{{$row['squantity']}}</td>
												<td class="text-right">{{number_format($sprice,2)}}</td>
												<td class="text-right">{{number_format($row['discount'],2)}}</td>
												<td class="text-right">{{number_format($pprice,2)}}</td>
												<td class="text-right">{{number_format($profit,2)}}</td>
												<td class="text-right">{{number_format($percentage,2)}}</td>
											</tr>
										<?php } $peravg = $pertotal / $n; 
											$nsptotal += $sptotal;
											$ndtotal += $dtotal;
											$nctotal += $tcst;
											$nptotal += $ptotal;
											$nqty += $sqty;
										?>
										
										<tr>
											<td colspan="4" align="right"><b>Sub Total:</b></td>
											<td class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($tcst,2)}}</b></td>
											<td class="text-right"><b>{{number_format($ptotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($peravg,2)}}</b></td>
										</tr>
										</table>
										</td>
									</tr>
								  <?php } ?>
									<tr style="border:0px solid black;">
										<td colspan="2" align="center"><br/>
										<table border="0" style="width:100%;" class="tblstyle table-bordered">
										
											<tr>
												<td width="48%" colspan="4" align="right"><b>Net Total:</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
												<td width="8%" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
												<td width="10%" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
												<td width="12%" class="text-right"><b>{{number_format($nptotal,2)}}</b></td>
												<td width="10%" class="text-right"></td>
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
						
							<?php }  ?>
								
						
						
					
						
						
							
						
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
										<span style="color:#fff;">
											<i class="fa fa-fw fa-print"></i>
										Print
									</span>
									</button>
									
									<!-- <button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button> -->
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
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('sales_invoice/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					
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
function getExport() {
	document.frmExport.submit();
}

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
