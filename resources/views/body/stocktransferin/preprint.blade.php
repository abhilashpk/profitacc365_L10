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
						<?php if(count($reports) > 0) { 
						if($type=='summary') { ?>
								<table class="table" border="0">
									<thead>
										<th>STI No</th>
										<th>STI Date</th>
										<th> Debit Account</th> 
										<th class="text-right">Gross Amt.</th>
										<th class="text-right">Discount</th>
									
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										@foreach($report as $row)
										<?php $total = $row->total_amt;
											  $discount = $row->discount;
											
											  $amount_total = $row->net_total;
										?>
										@endforeach
										<tr>
											<td><b>{{$key}}</b></td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<td>{{$row->name_dr}}</td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount,2)}}</b></td>
										
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
									<tr>
								           <td></td><td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
											
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="detail") { ?>
								<table class="table" border="0">
									<thead>
										
										<th>STI.No</th>
										<th>STI. Date</th>
										<th> Item Name</th> 
										<th> Debit Account</th> 
										<th> Quantity</th> 
										<th class="text-right">Rate</th>
										<th class="text-right">Gross Amount</th>
										
										<th class="text-right">Net Total</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
									
										
										@foreach($report as $row)
										
										
										<td>{{ $row->voucher_no }} </td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
										
											
											<td>{{$row->item_name}}</td>
											<td>{{$row->name_dr}}</td>
											<td >{{$row->quantity}}</td>
											<td class="text-right">{{number_format($row->price,2)}}</td>
											<td class="text-right">{{number_format($row->quantity*$row->price,2)}}</td>
									
											<td class="text-right">{{number_format($row->item_total,2)}}</td>
										</tr>
										@endforeach
										<?php $total += $row->price;
											  $discount += $row->quantity*$row->price;
											
											  $amount_total += $row->item_total;
										?>
										
										<tr>
										<td></td>
											
											
											<td></td>
											<td></td>
											<td><b>Total: </b></td>
											<td></td>
											
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($discount,2)}}</b></td>
											
											<td class="text-right"><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Net Total: </b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($nettotal,2)}}</b></td>
											<td class="text-right"><b>{{number_format($netdiscount,2)}}</b></td>
											
											<td class="text-right"><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php } elseif($type=="job_orderclosed") { ?>
								
								<table class="table" border="0">
									<thead>
										
										<th>JI.No</th>
										<th>Date</th>
										<!-- <th>Customer Name</th> -->
										<th>Salesman</th>
									
										<th >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td>{{$row->salesman}}</td>
									
										
											<td>{{number_format($row->line_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->line_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
											
											
											<td></td>
											<td><b>Total: </b></td>
											
											<td ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											
											
											<td></td>
											<td><b>Net Total: </b></td>
											
											<td ><b>{{number_format($net_amount_total,2)}}</b></td>
										</tr>
									</body>
								</table>
								
							<?php } elseif($type=="job_order") { ?>
								<table class="table" border="0">
									<thead>
										
										<th>JI.No</th>
										<th>Date</th>
										<!-- <th>Customer Name</th> -->
										<th>Salesman</th>
									
										<th >Amount</th>
									</thead>
									<body>
									<?php  $nettotal=$netdiscount=$netvat_amount=$net_amount_total=0; //net_total  ?>
									@foreach($reports as $key => $report)
										<?php  $total=$discount=$vat_amount=$amount_total=0; ?>
										<tr><td colspan="9"><b>Cust.Name:{{$key}}</b></td></tr>
										@foreach($report as $row)
										<tr>
											
											<td>{{ $row->voucher_no }}</td>
											<td>{{date('d-m-Y',strtotime($row->voucher_date))}}</td>
											<!-- <td>{{$row->master_name}}</td> -->
											<td>{{$row->salesman}}</td>
									
										
											<td>{{number_format($row->line_total,2)}}</td>
										</tr>
										<?php $total += $row->subtotal;
											  $discount += $row->discount;
											  $vat_amount += $row->vat_amount;
											  $amount_total += $row->line_total;
										?>
										@endforeach
										<tr>
										<td></td>
											
											
											
											
											<td></td>
											<td><b>Total: </b></td>
											
											<td ><b>{{number_format($amount_total,2)}}</b></td>
										</tr>
										<?php $nettotal += $total;
											  $netdiscount += $discount;
											  $netvat_amount += $vat_amount;
											  $net_amount_total += $amount_total;
										?>
									@endforeach
										
										<tr>
											
											
											<td></td>
											<td><b>Net Total: </b></td>
											
											<td ><b>{{number_format($net_amount_total,2)}}</b></td>
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
											<td>{{ $row->document_id }}</td>
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
								<tr>
									
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
									<?php if($type!='job_order') { ?>
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
									<?php } ?>
                                </span>
                        </div>
                    </div>
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('stock_transferin/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="search_type" value="{{$type}}" >
					<input type="hidden" name="item_id[]" value="{{$item[0]}}" >
					<input type="hidden" name="group_id[]" value="{{$group[0]}}" >
					<input type="hidden" name="subgroup_id[]" value="{{$subgroup[0]}}" >
					<input type="hidden" name="category_id[]" value="{{$category[0]}}" >
					<input type="hidden" name="subcategory_id[]" value="{{$subcategory[0]}}" >
				
					</form>
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
        </section>


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
