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
						<?php if(count($report) > 0) { 
						if($type=='summary') { ?>
								<table class="table" border="0">
									<thead>
									    <th class="text-right">SI.No</th>
										<th class="text-right">JI.No</th>
										<th class="text-right">JI.Date</th>
										<th class="text-right">Customer</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
										<th class="text-right">Total Amt.</th>
										<th class="text-right">VAT Amt.</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=$total_amt=0; ?>
									
										<?php  $total=$discount=$vat_amount=$amount_total=$i=0; ?>
										@foreach($report as $row)
										<?php //$total += $row->line_total;
											  //$discount += $row->linediscount;
											 // $vat_amount += $row->linevat;
											 // $gtotal = $row->line_total -  $row->linediscount + $row->linevat;
											  $amount_total = $row->total -  $row->discount + $row->vat_amount;
										?>
										
										<tr>
										<td class="text-right"><b>{{ ++$i }}</b></td>
											<td class="text-right"><b>{{$row->voucher_no}}</b></td>
											<td class="text-right"><b>{{date('d-m-Y',strtotime($row->voucher_date))}}</b></td>
											<td class="text-right"><b>{{$row->master_name}}</b></td>
											<td class="text-right"><b>{{number_format($row->total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($row->discount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($row->total-$row->discount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($row->vat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal +=$row->line_total;
											  $netdiscount += $row->linediscount;
											  $netvat_amount +=  $row->linevat;
											  $net_amount_total += $amount_total;
											  $total_amt+=$row->total-$row->discount;
										?>
									
									@endforeach
									<tr>
									<td></td>
									<td></td>
									<td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
												<td class="text-right"><b>{{number_format($total_amt,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netvat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="detail") { ?>
								<table class="table" border="0">
									<thead>
										<th>JO.No</th>
										<th>JI.No</th>
										<th>JI.Date</th>
										<th>Customer</th>
										 <th>Item  Name</th> 
										<th>Salesman</th>
										<th>Vehicle no</th>
										<th class="text-right">Quantity</th>
										<th class="text-right">Rate</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">VAT Amt.</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netqty=$netvat_amount=$net_amount_total=$net_unit=0; //net_total  ?>
									
										<?php  $total=$qty=$vat_amount=$amount_total=$unit=0; ?>
										
										@foreach($report as $row)
										<tr>
											<td>{{ $row->jo_no }}</td>
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td>{{$row->customer}}</td>
											 <td>{{$row->description}}</td>
											<td>{{$row->salesman}}</td>
											<td>{{$row->reg_no}}</td>
											<td class="text-right">{{number_format($row->quantity,2)}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($row->line_total,2)}}</td>
											
											<td class="text-right">{{number_format($row->linevat,2)}}</td>
											<?php $total += $row->line_total;
											  $qty += $row->quantity;
											  $unit+=$row->unit_price;
											  $vat_amount += $row->linevat;
											 $gtotal = $row->line_total -  $row->linediscount + $row->linevat;
											  $amount_total += $gtotal;//$row->line_total //$total + $vat_amount ;
										?>
											<td class="text-right">{{number_format($gtotal,2)}}</td>
										</tr>
										
										@endforeach
										<tr>
										<td></td>
											
										<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Total: </b></td>
											<td class="text-right"><b>{{number_format($qty,2)}}</b></td>
											<td class="text-right"><b>{{number_format($unit,2)}}</b></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($vat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netqty += $qty;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
											  $net_unit+=$unit;
										?>
									
										
										<tr>
										    <td></td><td></td>
											<td></td>
											<td></td>
											<td></td><td></td>
											<td><b>Net Total: </b></td>
											<td class="text-right"><b>{{number_format($netqty,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_unit,2)}}</b></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netvat_amount,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="job_orderclosed") { ?>
								
								<table class="table" border="0">
									<thead>
											<th>JO.No</th>
										<th class="text-right">JI.No</th>
										<th class="text-right" >Date</th>
									<th class="text-right" >Vehicle no</th>
										<th class="text-right">Salesman</th>
									
										<th class="text-right" >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
												<td>{{ $row->jo_no }}</td>
											<td class="text-right">{{ $row->voucher_no }}</td>
											<td class="text-right">{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td class="text-right">{{$row->reg_no}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td class="text-right">{{$row->salesman}}</td>
									
										
											<td class="text-right">{{number_format($row->net_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->net_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
												<td></td>
											<td ></td>
											<td class="text-right" ></td>	
											<td class="text-right"><b>Total: </b></td>
											
											<td  class="text-right" ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											<td></td>		
											<td></td>	<td></td>		
											<td class="text-right"></td>
											<td class="text-right" ><b>Net Total: </b></td>
											
											<td class="text-right" ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
								
							<?php } elseif($type=="job_order") { ?>
								<table class="table" border="0">
									<thead>
											<th>JO.No</th>
										<th class="text-right">JI.No</th>
										<th class="text-right" >Date</th>
									<th class="text-right" >Vehicle no</th>
										<th class="text-right">Salesman</th>
									
										<th class="text-right" >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
												<td>{{ $row->jo_no }}</td>
											<td class="text-right">{{ $row->voucher_no }}</td>
											<td class="text-right">{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td class="text-right">{{$row->reg_no}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td class="text-right">{{$row->salesman}}</td>
									
										
											<td class="text-right">{{number_format($row->net_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->net_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
												<td></td>
											<td ></td>
											<td class="text-right" ></td>	
											<td class="text-right"><b>Total: </b></td>
											
											<td  class="text-right" ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											<td></td>		
											<td></td>	<td></td>		
											<td class="text-right"></td>
											<td class="text-right" ><b>Net Total: </b></td>
											
											<td class="text-right" ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } else if($type=='vehicle') { ?>
								<table class="table" border="0">
									<thead>
										<th>JO.No</th>
										<th>JI.No</th>
										<th>JI.Date</th>
										<th>Item Name</th>
										<th>Vehicle No</th>
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
										<th class="text-right">VAT Amt.</th>
										<th class="text-right">Net Total</th>
									</thead>
									<body>
										<?php $gross_total = $discount_total = $vat_total = $net_total = 0; ?>
										@foreach($reports as $row)
										<?php $gross_total += $row->total;
											  $discount_total += $row->discount;
											  $vat_total += $row->vat_amount;
											  $net_total += $row->net_total; ?>
										<tr>
											<td>{{ $row->jo_no }}</td>
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td>{{$row->description}}</td>
											<td>{{$row->reg_no}}</td>
											<td class="text-right">{{number_format($row->total,2)}}</td>
											<td class="text-right">{{number_format($row->discount,2)}}</td>
											<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($row->net_total,2)}}</td>
										</tr>
										@endforeach
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td><td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } else { ?>
							<table class="table" border="0">
								<thead>
									<th>JO.No</th>
									<th>JI.No</th>
									<th>JI.Date</th>
									<th>Customer Name</th>
									<th>TRN No.</th>
									<th>Vehicle No</th>
									<th class="text-right">Gross Amt.</th>
									<th class="text-right">Discount</th>
									<th class="text-right">VAT Amt.</th>
									<th class="text-right">Net Total</th>
								</thead>
								<body>
									<?php $gross_total = $discount_total = $vat_total = $net_total = 0; ?>
									@foreach($reports as $row)
									<?php $gross_total += $row->total;
										  $discount_total += $row->discount;
										  $vat_total += $row->vat_amount;
										  $net_total += $row->net_total; ?>
									<tr>
										<td>{{ $row->document_id }}</td>
										<td>{{ $row->voucher_no }}</td>
										<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->vat_no}}</td>
										<td>{{$row->reg_no}}</td>
										<td class="text-right">{{number_format($row->total,2)}}</td>
										<td class="text-right">{{number_format($row->discount,2)}}</td>
										<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
										<td class="text-right">{{number_format($row->net_total,2)}}</td>
									</tr>
									@endforeach
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td><td></td>
										<td class="text-right"><b>Total:</b></td>
										<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($discount_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
										<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
									</tr>
								</body>
							</table>
							<?php } ?>
							
						<?php } else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<!-- <tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b> -->
									<!--<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />-->
									<!-- </td>
								</tr> -->
								<!-- <tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>{{$titles['subhead']}}</u></b></b></td>
								</tr> -->
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
                               <!-- <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span> -->
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
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
									
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('job_invoice/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="vehicle_no" value="{{$vehicleno}}" >
					<input type="hidden" name="salesman" value="{{$salesman}}" >
					<input type="hidden" name="customer_id" value="{{$customerid}}" >
					</form>
                </div>
            </div>
            <!-- row -->
       
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
@stop
    <!-- end of page level js -->
	</body>
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
</html>

