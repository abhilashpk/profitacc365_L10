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
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center" width="50%">
									@include('main.print_head')
									</td>
								</tr>
								
								<tr><td colspan="2" align="center"><h4><b>Material Requisition</b></h4></td></tr>
								<tr>
									<td align="left" valign="top" width="35%" style="padding-left:0px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;">
											Job No: {{$details->code}}<br/>
											Job Name: {{$details->name}}<br/>
											Engineer: {{$details->salesman}}<br/>
										</div>
									</td>
									<td align="left" style="padding-left:150px;">
										<div style="border:1px solid #000; padding: 10px;">
											MR No: {{$details->voucher_no}}<br/>
											MR Date: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
										</div>
									</td>
								</tr>
								
								
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:400px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="15%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Unit</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Qty.</b></td>
											<td width="10%" align="center" style="border:1px solid #000 !important;" ><b>Unt.Price</b></td>
											<td width="12%" align="center" style="border:1px solid #000 !important;" ><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++; 
										?>
										<tr>
											<td align="center">{{$i}}</td>
											<td align="center">{{$item->item_code}}</td>
											<td align="center">{{$item->item_name}}</td>
											<td align="center">{{$item->unit_name}}</td>
											<td align="center">{{$item->quantity}}</td>
											<td align="center">{{number_format($item->unit_price,2)}}</td>
											<td align="center">{{number_format($item->total_price,2)}}</td>
										</tr>
										
										@endforeach
										
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:100%;">
										<tr>
											<td width="65%" style="padding-left:10px;"></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Net Total : </p></b>
											</td>
											<td colspan="2" align="right" style="padding-right:10px;">
											
											<b>
												<p>{{number_format($details->total,2)}}</p>
												<?php if($details->discount!=0) { ?><p>{{number_format($details->discount,2)}}</p><?php } ?>
												<p>{{number_format($details->net_amount,2)}}</p>
											</b>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr style="border:0px solid black;">
									<td colspan="2">
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" align="center"><div class="footer-space">&nbsp;</div></td>
								</tr>
							</tfoot>
						</table>
						
							<div class="footer" style="text-align:center; border:0px solid red; width:86%;">
								
								<img src="{{asset('assets/footer_'.Session::get('logo').'')}}" />
							</div>
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
                                                    <!-- /<i class="fa fa-fw fa-times"></i> -->
													<i class="fa fa-fw fa-times"></i>													
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div>
						
                    </div>
                </div>
            </div>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
<script>
function getExport() { document.frmExport.submit(); }
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
    <!-- end of page level js -->
@stop
