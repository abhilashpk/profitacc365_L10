<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 - ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:7pt;
}

</style>
<style type="text/css" media="print">


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



</style>
<!-- end of global css -->
</head>
<body>


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div>
    <!-- Left side column. contains the logo and sidebar -->
    
    <aside class="right-side">


        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div >
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:18px;">{{Session::get('company')}}</b>
										<div>
											Tel: 06-5428181, P.O. Box: 44341, Muwaileh, <br/>
											Behind Fire Station Sharjah - U.A.E<br/>
											 
											<strong>TRN No: {{Session::get('vatno')}}</strong>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><b style="font-size:10px;"><u>TAX INVOICE</u></b></td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding-left:20px;">
										Customer: <b>{{$details->cash_customer}}</b><br/>
										<b>Invoice No: {{$details->voucher_no}}</b>
									</td>
									<td align="right" style="padding-left:0px;">
										<div>
											Date: {{date('d-m-Y',strtotime($details->voucher_date))}} &nbsp;
										</div>
									</td>
								</tr>
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="53%"><b>Description</b></td>
											<td width="10%" class="text-right"><b>Qty.</b></td>
											<td width="12%" class="text-right"><b>Price</b></td>
											<td width="10%" class="text-right"><b>VAT</b></td>
											<td width="15%" class="text-right"><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											
												$unit_price = $item->unit_price;
												$vat_amount = $item->vat_amount;
												$line_total = $item->line_total;
												
												if($details->discount!=0) {
													$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
												}
										?>
										<tr>
											<td>{{$item->item_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($unit_price,2)}}</td>
											<td class="text-right">{{number_format($vat_amount,2)}}</td>
											<td class="text-right">{{number_format($line_total,2)}}</td>
										</tr>
										@endforeach
										<?php 
											$total = $details->total;
											$vat_amount_net = $details->vat_amount;
											$net_total = $details->net_total;
										?>
											<tr>																														<tr>
												<td colspan="4" align="right"><b>Gross Total:</b></td>
												<td class="text-right"></b>{{number_format($total,2)}}</b></td>
											</tr>
											<tr>																														<tr>
												<td colspan="4" align="right"><b>VAT Total:</b></td>
												<td class="text-right"></b>{{number_format($vat_amount_net,2)}}</b></td>
											</tr>
											<tr>																														<tr>
												<td colspan="4" align="right"><b>Net Total:</b></td>
												<td class="text-right"></b>{{number_format($net_total,2)}}</b></td>
											</tr>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" class="footer">
									<table border="0" width="100%">
										<tr>
											<td align="center">
												<h5>Delivery Date: {{date('d-m-Y H:i A')}}</h5> 
												
												
												<h6>Thank You, Please Visit Again.</h6>
												<i><h6>Customer Copy</h6></i>
											</td>
										</tr>
									</table>
									</td>
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
$(document).ready(function () {
	
	//$('body').html('Hi welcome');
	//$('html').attr({style: 'min-height:240px;'});
	//$('body').attr({style: 'min-height:240px;'});
	//$('#tst').attr({style: 'min-height:240px !important'});
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});

	
});
</script>
</html>
