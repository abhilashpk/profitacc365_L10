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
								$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;$i=1;
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
											$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0;	 
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
						<?php } else if($type=='occupied') { ?>
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
											<td width="6%"><b>Flat ID</b></td>
											<td width="8%"><b>Flat Name</b></td>
											
											<td width="8%"><b>Tenant</b></td>
											<td width="8%"><b>Phone</b></td>
											<td width="8%"><b>Status</b></td>
												<td width="8%"><b>Rent</b></td>
											
											
										</tr>
										<?php 
										foreach($reports as $key => $report) {
										?>
										 <tr>
										<td colspan="7" align="left"><b>{{$key}}</b></td>
										</tr>
										<?php 
										$rent = 0; $i=1;
											foreach($report as $row) { 
											$rent +=$row->rent_amount; 
											
											
												 $n = $i;	
													?>
										
										
										<tr>
										<td>{{$i++}}</td>
											<td>{{$row->flat_no}}</td>
											<td >{{$row->flat_name}}</td>
											<td >{{$row->master_name}}</td>
											<td >{{$row->phone}}</td>
											<td >Occupied</td>
												<td >{{$row->rent_amount}}</td>
											
										</tr>
									<?php } 
									?>
									<tr>
									<td></td><td></td><td></td><td></td><td></td>
										<td align="right"><b>Grant Total:</b></td>
										<td ><b>{{number_format($rent,2)}}</b></td>
										
										
									
									</tr>
									
									<?php }?>
									
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
						

						<?php } else if($type=='vaccant') { ?>
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
											<td width="6%"><b>Flat ID</b></td>
											<td width="8%"><b>Flat Name</b></td>
											
											<td width="8%"><b>Tenant</b></td>
											<td width="8%"><b>Phone</b></td>
											<td width="8%"><b>Status</b></td>
												<td width="8%"><b>Rent</b></td>
											
											
										</tr>
										<?php 
										foreach($reports as $key => $report) {
										?>
										 <tr>
										<td colspan="7" align="left"><b>{{$key}}</b></td>
										</tr>
										<?php 
										$rent = 0; $i=1;
											foreach($report as $row) { 
											$rent +=$row->rent_amount; 
											
											
												 $n = $i;	
													?>
										
										
										<tr>
										<td>{{$i++}}</td>
											<td>{{$row->flat_no}}</td>
											<td >{{$row->flat_name}}</td>
											<td >{{$row->master_name}}</td>
											<td >{{$row->phone}}</td>
											<td >Vaccant</td>
												<td >{{$row->rent_amount}}</td>
											
										</tr>
									<?php } 
									?>
									<tr>
									<td></td><td></td><td></td><td></td><td></td>
										<td align="right"><b>Grant Total:</b></td>
										<td ><b>{{number_format($rent,2)}}</b></td>
										
										
									
									</tr>
									
									<?php }?>
									
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
						

							<?php } else if($type=='all') { ?>
							
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
											<td width="6%"><b>Flat ID</b></td>
											<td width="8%"><b>Flat Name</b></td>
											
											<td width="8%"><b>Tenant</b></td>
											<td width="8%"><b>Phone</b></td>
											<td width="8%"><b>Status</b></td>
												<td width="8%"><b>Rent</b></td>
											
											
										</tr>
										<?php 
										foreach($reports as $key => $report) {
										?>
										 <tr>
										<td colspan="7" align="left"><b>{{$key}}</b></td>
										</tr>
										<?php 
										$rent = 0; $i=1;
											foreach($report as $row) { 
											$rent +=$row->rent_amount; 
											
											
												 $n = $i;	
													?>
										
										
										<tr>
										<td>{{$i++}}</td>
											<td>{{$row->flat_no}}</td>
											<td >{{$row->flat_name}}</td>
											<td >{{$row->master_name}}</td>
											<td >{{$row->phone}}</td>
											<td ><?php  if($row->end_date < date('Y-m-d') && $row->is_close == 0) echo "Expired" ;else if($row->end_date > date('Y-m-d') && $row->is_close == 0) echo"Occupied";
											else if($row->is_close == 1) echo"Vaccant" ;?></td>
												<td >{{$row->rent_amount}}</td>
											
										</tr>
									<?php } 
									?>
									<tr>
									<td></td><td></td><td></td><td></td><td></td>
										<td align="right"><b>Grant Total:</b></td>
										<td ><b>{{number_format($rent,2)}}</b></td>
										
										
									
									</tr>
									
									<?php }?>
									
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
