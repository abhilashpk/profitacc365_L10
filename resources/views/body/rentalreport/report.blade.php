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
					<?php if(count($report) > 0) {  ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">&nbsp;@include('main.print_head_text')<br/></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>Rental Item Report</u></b></b></td>
								</tr>
								
							</thead>
							
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="">
									@foreach($report as $results)
									<p><h6><b>Item Code: {{$results[0]->item_code}} | 
										   Item Name: {{$results[0]->description}}</b></h6></p>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="10%"><b>Type</b></td>
											<td width="10%"><b>Vchr.No.</b></td>
											<td width="15%"><b>Tr.Date</b></td>
											<td width="20%"><b>Supplier/Customer.</b></td>
											<td width="15%" class="text-right"><b>Rental In</b></td>
											<td width="15%" class="text-right"><b>Rental Out</b></td>
											<td width="15%" class="text-right"><b>Balance</b></td>
										</tr>
										<?php $bal = 0; ?>
										@foreach($results as $result)
										<tr>
											<?php if($result->doc_type=='PIR') {
													$bal += $result->qty;
												 } elseif($result->doc_type=='SIR') {
													$bal -= $result->qty;
												 } 
											?>
											
											<td>{{$result->doc_type}}</td>
											<td>{{$result->voucher_no}}</td>
											<td>{{date('d-m-Y',strtotime($result->voucher_date))}}</td>
											<td>{{$result->master_name}}</td>
											<td class="text-right"><?php if($result->doc_type=='PIR') echo $result->qty;?></td>
											<td class="text-right"><?php if($result->doc_type=='SIR') echo $result->qty;?></td>
											<td class="text-right">{{$bal}}</td>
										</tr>
										@endforeach
									</table> <br/><br/>
									@endforeach
									
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
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:16px;"><b><u>Rental Item Report</u></b></b></td>
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
								
								<!--<button type="button" onclick="getExport()"
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
						
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('stock_ledger/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$fromdate}}" >
					<input type="hidden" name="date_to" value="{{$todate}}" >
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
