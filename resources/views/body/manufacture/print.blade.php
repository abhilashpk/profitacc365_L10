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
									</td
								</tr>
								
								<tr><td colspan="2"  align="center"><h5><b><u>Manufacturing Voucher</u></b></h5></td></tr>
								<tr style="border:0px solid black;">
									<td align="left" valign="top" style="width:50%; padding-left:20px; padding-bottom:5px;">
										<div>
											<!--<strong><?php //echo $details->cr_account;?><br/>
											<?php //echo $details->dr_account;?>
											</strong>-->
											
										</div>
									</td>
									<td align="left" style="padding-left:200px; width:50%;">
										<div>
											<b>Mfg. No: {{$mfgno}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
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
										
										<?php $i = $total = $net_total = $gtotal = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											
												$unit_price = $item->price;
												//$line_total = $item->item_total;
												
												$line_total = ($item->price * $item->quantity);
												
												$total += $line_total;
												$net_total += $total;
												
												$oc = $mres->other_cost/($mres->amount - $mres->other_cost) * $item->price;
												$uprice = $oc + $item->price;
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->item_name}}</td>
											<td>{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($uprice,2)}}</td>
											<td class="text-right">{{number_format($uprice * $item->quantity,2)}}</td>
										</tr>
										
										<tr>
											<td></td>
											<td colspan="5"><b>Raw Materials</b>
												<table class="table horizontal_table table-striped" id="postsrw">
													<tr>
														<th>Item Code</th>
														<th>Description</th>
														<th>Quantity</th>
														<th class="text-right">Cost/Unit</th>
														<th class="text-right">Total</th>
													</tr>
													@php $rmtotal = 0; @endphp
													@foreach($rawmat[$item->item_id] as $raw)
													@php $price = $raw->quantity * $item->quantity * $raw->unit_price; $rmtotal += $price; @endphp
													<tr>
														<td>{{$raw->item_code}}</td>
														<td>{{$raw->description}}</td>
														<td>{{$raw->quantity*$item->quantity}}</td>
														<td class="text-right">{{number_format($raw->unit_price,2)}}</td>
														<td class="text-right">{{number_format($price,2)}}</td>
													</tr>
													@endforeach
													@php $gtotal += $rmtotal; @endphp
													<tr><td colspan="4">Total:</td><td class="text-right">{{number_format($rmtotal,2)}}</td></tr>
												</table>
											</td>
											<td></td>
										</tr>
										
										@endforeach
										@php $octotal = 0; @endphp
										@foreach($ocost as $cost)
										@php $total += $cost->amount; $octotal += $cost->amount; @endphp
										<tr>
											<td></td>
											<td><b>Other Costs</b></td>
											<td>{{$cost->description}}</td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right">{{number_format($cost->amount,2)}}</td>
											</td>
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
											<p>Other Cost Total: </p>
											<p>Net Total: </p></b>
											</td>
											<td colspan="2" align="right">
											
											<b>
												<p>{{number_format($gtotal,2)}}</p>
												<p>{{number_format($octotal,2)}}</p>
												<p>{{number_format($gtotal+$octotal,2)}}</p>
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
                    
                    <form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('manufacture/printexport') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="id" value="{{$id}}" >
					</form>
                </div>
            </div>
    </section>
            
{{-- page level scripts --}}
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