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
									<td colspan="2" align="center">
									@include('main.print_head')
									</td>
								</tr>
								
								<tr><td colspan="2"  align="center"><h5><b><u>Stock Transfer-In</u></b></h5></td></tr>
								<tr style="border:0px solid black;">
									<td align="left" valign="top" style="width:50%; padding-left:20px; padding-bottom:5px;">
										<div>
											<strong><?php echo $details->cr_account;?><br/>
											<?php echo $details->dr_account;?>
											</strong>
											
										</div>
									</td>
									<td align="left" style="padding-left:200px; width:50%;">
										<div>
											<b>STI. No: {{$details->voucher_no}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											Ref. No: {{$details->reference_no}}<br/>
										</div>
										
									</td>
								</tr>
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:450px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%"><b>SI.#</b></td>
											<td width="15%"><b>Item Code</b></td>
											<td width="40%"><b>Description</b></td>
											<td width="8%"><b>Unit</b></td>
											<td width="7%" class="text-right"><b>Qty.</b></td>
											<td width="15%" class="text-right"><b>Unit Price</b></td>
											<td width="15%" class="text-right"><b>Total</b></td>
										</tr>
										<?php $i = $total = $net_total = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											
												$unit_price = $item->price;
												$line_total = $item->item_total;
												
												if($details->discount!=0) {
													$line_total = ($item->price * $item->quantity);
												}
												
												$total += $line_total;
												$net_total += $total;
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->item_name}}</td>
											<td>{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($item->price,2)}}</td>
											<td class="text-right">{{number_format($line_total,2)}}</td>
										</tr>
										
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total: </p>
											<p>Discount: </p>
											<p>Net Total: </p></b>
											</td>
											<td colspan="2" align="right">
											
											<b>
												<p>{{number_format($total,2)}}</p>
												<p>{{number_format($details->discount,2)}}</p>
												<p>{{number_format($details->net_total,2)}}</p>
											</b>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr style="border:0px solid black;">
									<td colspan="2">
										<table border="0" style="width:100%;">
											<tr>
												<td align="center"> <br/>
													<b>Received by:</b>
												</td>
												<td align="center"> <br/>
													<b>Approved by:</b>
												</td>
												<td align="center"><br/>
												<b>Prepared by:</b>
												</td>
											</tr>
											
										</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr style="border-top:0px solid red;">
									<td colspan="2" class="footer"></td>
								</tr>
							</tfoot>
						</table>
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
                                </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
  

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
<script>
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
    <!-- end of page level js -->
@stop
