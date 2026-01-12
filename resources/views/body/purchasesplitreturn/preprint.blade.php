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
									<td width="100%" align="center"><h4><u>{{$voucherhead}} {{($dname!='')?'('.$dname.')':''}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>
						<p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
                        <div class="col-md-12">
						<?php if(count($reports) > 0) {if($type=="supplier") { ?>
									<table class="table" border="0">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $vatamount = $npertotal = $nqty = 0; foreach($reports as $report) { ?>
									
										<!-- <thead>
											<th style="width:37%;" colspan="3">Cust.#: {{$report[0]['account_id']}}</th>
											<th style="width:40%;" colspan="3">Cust.Name: {{$report[0]['supplier']}}</th>
											<th style="width:23%;" colspan="1"></th>
										</thead>
										 -->
										<thead>
											<th style="width:12%;">PSR.#</th>
											<th style="width:14%;">PSR. Date</th>
											<th style="width:16%;" class="text-right">Gross Amount</th>
											<th style="width:10%;" class="text-right">Discount</th>
											<th style="width:10%;" class="text-right">Vat.Amount</th>
											<th style="width:18%;" class="text-right">Net.Total</th>
											
										</thead>
										<tbody>
										<tr>
													<td style="width:12%;">Cust.#: {{$report[0]['account_id']}}</td>
													<td style="width:14%;">Cust.Name: {{$report[0]['supplier']}}</td>
													
												</tr>
											<?php 
												$sptotal = $dtotal = $ctotal = $ptotal = $pertotal =$vatamt= $peravg = 0; $i=1;
												foreach($report as $row) { 
												
												$sptotal += $row['total'];
												$dtotal += $row['discount'];
												$vatamt+= $row['vat_amount'];
												$ctotal += $row['net_amount'];
											
												$n = $i; $i++;
											?>
												<tr>
													<td style="width:12%;">{{$row['voucher_no']}}</td>
													<td style="width:14%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
													<td style="width:16%;" class="text-right">{{number_format($row['total'],2)}}</td>
													<td style="width:10%;" class="text-right">{{number_format($row['discount'],2)}}</td>
													<td style="width:10%;" class="text-right">{{number_format($row['vat_amount'],2)}}</td>
													<td style="width:18%;" class="text-right">{{number_format($row['net_amount'],2)}}</td>
												
												</tr>
												<?php } $peravg = $pertotal / $n; 
												$nsptotal += $sptotal;
												$ndtotal += $dtotal;
												$nctotal += $ctotal;
												$vatamount += $vatamt;
												$nptotal += $ptotal;?>
											<tr>
												<td colspan="2" align="right"><b>Sub Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($sptotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($dtotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($vatamt,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($ctotal,2)}}</b></td>
						
											</tr>
											
											<tr>
												<td colspan="7" align="right"><br/></td>
											</tr>
											<?php } ?>
											<tr>
												<td colspan="2" align="right"><b> Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($vatamount,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
						
											</tr>
											
									</table>
									</td>
								</tr>
								</tbody>
								</table>
								<?php //}?>
							<?php } else { ?>
								
									<table class="table" border="0">
									<?php $nsptotal = $ndtotal = $nctotal = $nptotal = $vatamount = $npertotal =  $gross_total = $discount_total = $vat_total = $net_total = 0;  foreach($reports as $report) { ?>
									
										<thead>
									<th>SI.No</th>
									<th>PSR#</th>
									<th>Vchr.Date</th>
									<th>Supplier Name</th>
									<th>TRN No.</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
										<tbody>
									
											<?php 
												$gross_total = $discount_total = $vat_total = $net_total = 0; $i=1;
												foreach($report as $row) { 
												
													$gross_total += $row['subtotal'];
													$discount_total += $row['discount'];
													$vat_total += $row['vat_amount'];
													$net_total +=$row['net_amount'];
											
												$n = $i; $i++;
											?>
												<tr>
												<td>{{ ++$i }}</td>
													<td style="width:12%;">{{$row['voucher_no']}}</td>
													<td style="width:14%;">{{date('d-m-Y',strtotime($row['voucher_date']))}}</td>
													<td style="width:14%;">{{$row['master_name']}}</td>
										               <td style="width:14%;">{{$row['vat_no']}}</td>
													<td style="width:16%;" class="text-right">{{number_format($row['subtotal'],2)}}</td>
													<td style="width:10%;" class="text-right">{{number_format($row['discount'],2)}}</td>
													<td style="width:10%;" class="text-right">{{number_format($row['vat_amount'],2)}}</td>
													<td style="width:18%;" class="text-right">{{number_format($row['net_amount'],2)}}</td>
												
												</tr>
												<?php } 
												$nsptotal += $gross_total;
												$ndtotal += $discount_total;
												$nctotal += $vat_total;
												$vatamount += $net_total;
												//$nptotal += $ptotal;
												?>
											<tr>
											
													<td ></td>
													<td ></td>
													<td></td>
										               
												<td colspan="2" align="right"><b> Total:</b></td>
												<td style="width:10%;" class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
												<td style="width:5%;" class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
												<td style="width:18%;" class="text-right"><b>{{number_format($net_total,2)}}</b></td>
						
											</tr>
											
											<tr>
												<td colspan="7" align="right"><br/></td>
											</tr>
											
											<?php } ?>
											<tr>
											
											<td ></td>
											<td ></td>
											<td></td>
											   
										<td colspan="2" align="right"><b> Total:</b></td>
										<td style="width:10%;" class="text-right"><b>{{number_format($nsptotal,2)}}</b></td>
										<td style="width:5%;" class="text-right"><b>{{number_format($ndtotal,2)}}</b></td>
										<td style="width:5%;" class="text-right"><b>{{number_format($nctotal,2)}}</b></td>
										<td style="width:18%;" class="text-right"><b>{{number_format($vatamount,2)}}</b></td>
				
									</tr>
											
									</table>
									</td>
								</tr>
								</tbody>
								</table>
								<?php }?>
								<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<!-- <td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b> -->
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									<!-- </td> -->
								<!-- </tr> -->
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u></u></b></b></td>
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
                                                <span style="color:#fff;">
												  <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                   </button>
								
								
                                </span>
                        </div>
                    </div>
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('purchase_split_return/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
					 <input type="hidden" name="isimport" value="{{$isimport}}" >  
					</form>
					
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
@stop
