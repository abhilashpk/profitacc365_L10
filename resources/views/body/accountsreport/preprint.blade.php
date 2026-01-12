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
									<td width="100%" align="center" colspan="2"><h4><u>{{$voucherhead}}</u></h4>
								</tr>
							</table><br/>
                        </div>
						<p><b><?php echo ($fromdate=='')?'':'Date From: '.$fromdate;?></b> <b><?php echo ($todate=='')?'':'&nbsp; To: '.$todate;?></b></p>
                        <div class="col-md-12">
							<?php //if($type=="detail") { ?>
								<table class="table" border="0">
									<body>
									<?php $qty_total = $net_total = $vat_total = $gross_total = 0; ?>
									@foreach($reports as $report)
									<tr>
										<td><b>Inv.No:</b> {{$report[0]->voucher_no}}</td>
										<td><b>Inv.Date:</b> {{date('d-m-Y',strtotime($report[0]->voucher_date))}}</td>
										<td></td>
										<td></td>
										<td colspan="5" class="text-right"><b><?php echo ($type=='CUSTOMER')?'Customer: ':'Supplier: ';?></b> {{$report[0]->master_name}}</td>
									</tr>
									<tr>
										<td><b>Item Code</b></td>
										<td><b>Description</b></td>
										<td class="text-right"><b>Qty.</b></td>
										<td class="text-right"><b>Rate</b></td>
										<td class="text-right"><b>Total Amt.</b></td>
										<td class="text-right"><b>VAT Amt.</b></td>
										<td class="text-right"><b>Net Amt.</b></td>
									</tr>
										@foreach($report as $row)
										<?php 
										  $qty_total += $row->quantity;
										  $gross_total += $row->line_total;
										  $net_amount = $row->vat_amount + $row->line_total;
										  $vat_total += $row->vat_amount;
										  $net_total += $net_amount;
										?>
										<tr>
											<td>{{$row->item_code}}</td>
											<td>{{ $row->item_name }}</td>
											<td class="text-right">{{$row->quantity}}</td>
											<td class="text-right">{{number_format($row->unit_price,2)}}</td>
											<td class="text-right">{{number_format($row->line_total,2)}}</td>
											<td class="text-right">{{number_format($row->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($net_amount,2)}}</td>
										</tr>
										@endforeach
										<tr><td colspan="9"><br/></td></tr>
									@endforeach	
										<tr>
											<td></td>
											<td class="text-right"><b>Total:</b></td>
											<td class="text-right"><b>{{$qty_total}}</b></td>
											<td class="text-right"></td>
											<td class="text-right"><b>{{number_format($gross_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($vat_total,2)}}</b></td>
											<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
										</tr>
									</body>
								</table>
							<?php //} ?>
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('account_reports/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
					<input type="hidden" name="type" value="{{$type}}" >
					<input type="hidden" name="id" value="{{$id}}" >
					</form>
                </div>
            </div>
            <!-- row -->
    
        <!-- right side bar end -->
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

