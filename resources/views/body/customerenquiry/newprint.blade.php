<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            NumakPro ERP
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
	 position: fixed;
     bottom: 0;
	 margin: 0 auto 0 auto;
	
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
    
    <aside class="right-side">


        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<table border="1" style="width:100%;height:100%;" >
							<thead>
								<tr>
									<td align="left" width="45%">
									<img src="{{asset('assets/'.Session::get('logo').'')}}" /></td>
									<td  align="center">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h5><b><u>QUOTATION</u></b></h5></td>
								</tr>
								<tr>
									<td align="left" valign="top" style="padding-left:20px; padding-bottom:0px;">
										<div>
											<strong><?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:<br/>'.$details->customer_name:$details->supplier;?></strong>
											<?php echo ($details->address!='')?'<br/>'.$details->address:'';?>
											<?php echo ($details->state!='')?' '.$details->state:'';?>
											<?php echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
											<?php echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
											<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
											<?php echo ($vat_no!='')?'<br/>TRN No: <strong>'.$vat_no.'</strong>':'';?>
										</div>
									</td>
									<td align="right" style="padding-left:0px;">
										<div>
											<b>Quote. No: {{$details->voucher_no}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											<strong>TRN No: {{Session::get('vatno')}}</strong>
										</div>
									</td>
								</tr>
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%"><b>QO.#</b></td>
											<td width="58%"><b>Description</b></td>
											<td width="7%" class="text-right"><b>Qty.</b></td>
											<td width="15%" class="text-right"><b>Unit Price</b></td>
											<td width="10%" class="text-right"><b>VAT</b></td>
											<td width="15%" class="text-right"><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											if($fc) {
												$unit_price = $item->unit_price / $details->currency_rate;
												$vat_amount = $item->vat_amount / $details->currency_rate;
												$line_total = $item->line_total / $details->currency_rate;
											} else {
												$unit_price = $item->unit_price;
												$vat_amount = $item->vat_amount;
												$line_total = $item->line_total;
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($unit_price,2)}}</td>
											<td class="text-right">{{number_format($vat_amount,2)}}</td>
											<td class="text-right">{{number_format($line_total,2)}}</td>
										</tr>
										<?php if(array_key_exists($item->id, $itemdesc)) { 
											foreach($itemdesc[$item->id] as $desc) { ?>
											<tr>
												<td></td>
												<td style="padding-left:20px;">{{$desc->description}}</td>
												<td class="text-right"></td>
												<td class="text-right"></td>
												<td class="text-right"></td>
												<td class="text-right"></td>
											</tr>
											<?php } } ?>
										@endforeach
										</tr>
																																								<tr>
											<td>1</td>
											<td>Acrylic Paper Holder  of A4 Size for Reception Table</td>
											<td class="text-right">3</td>
											<td class="text-right">80.00</td>
											<td class="text-right">12.00</td>
											<td class="text-right">240.00</td>
										</tr>
																																								<tr>
											<td>2</td>
											<td>3mm thickness aluminium powder coated sheet of A3 size with reflective stricker</td>
											<td class="text-right">1</td>
											<td class="text-right">300.00</td>
											<td class="text-right">15.00</td>
											<td class="text-right">300.00</td>
										</tr>
																																								<tr>
											<td>3</td>
											<td>4mm thickness acrylic sheet of A4 size with digital printed stricker</td>
											<td class="text-right">1</td>
											<td class="text-right">80.00</td>
											<td class="text-right">4.00</td>
											<td class="text-right">80.00</td>
										</tr>
																																								<tr>
											<td>4</td>
											<td>1 mm thickness brush finished stainless steel with etching letter</td>
											<td class="text-right">10</td>
											<td class="text-right">150.00</td>
											<td class="text-right">75.00</td>
											<td class="text-right">1,500.00</td>
										</tr>
																																								<tr>
											<td>5</td>
											<td>Digital Printed Vinyl Sticker</td>
											<td class="text-right">10</td>
											<td class="text-right">10.00</td>
											<td class="text-right">5.00</td>
											<td class="text-right">100.00</td>
										</tr>
																																								<tr>
											<td>6</td>
											<td>3 mm thickness PVC Sheet of 10X4 cm with engraved letter</td>
											<td class="text-right">20</td>
											<td class="text-right">25.00</td>
											<td class="text-right">25.00</td>
											<td class="text-right">500.00</td>
										</tr>
																																								<tr>
											<td>7</td>
											<td>4 mm thickness Acrylic Sheet of 80x60 cm with Sticker</td>
											<td class="text-right">4</td>
											<td class="text-right">400.00</td>
											<td class="text-right">80.00</td>
											<td class="text-right">1,600.00</td>
										</tr>
																																								<tr>
											<td>8</td>
											<td>1 mm thickness Brush Finished SS of 25x10 cm With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">80.00</td>
											<td class="text-right">4.00</td>
											<td class="text-right">80.00</td>
										</tr>
																																								<tr>
											<td>9</td>
											<td>1 mm Thickness Brush Finished SS Of 30x15 cm </td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>10</td>
											<td>1 mm Thickness Brush Finished SS Of 30x15 cm With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>11</td>
											<td>4 mm Thickness Acrylic Of A3 Size With Digital Printed Sticker</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>12</td>
											<td>1 mm Thickness Brush Finished SS Of A5 Size With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>13</td>
											<td>1 mm Thickness Brushed Finished SS Of A5 Size With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>14</td>
											<td>1 mm Thickness Brush Finished SS of 30x15 cm With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>15</td>
											<td>4 mm Thickness Acrylic of A3 Size With Digital Printed Sticker</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>16</td>
											<td>4 mm Thickness Acrylic of A3 Size With Digital Printed Sticker</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>17</td>
											<td>1 mm Thickness Brush Finished SS Of 30x10 cm With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">80.00</td>
											<td class="text-right">4.00</td>
											<td class="text-right">80.00</td>
										</tr>
																																								<tr>
											<td>18</td>
											<td>1 mm thickness Brushed Finished SS Of 30x10 cm With Etching Letter</td>
											<td class="text-right">1</td>
											<td class="text-right">80.00</td>
											<td class="text-right">4.00</td>
											<td class="text-right">80.00</td>
										</tr>
																																								<tr>
											<td>19</td>
											<td>Digital Printed Sticker</td>
											<td class="text-right">1</td>
											<td class="text-right">100.00</td>
											<td class="text-right">5.00</td>
											<td class="text-right">100.00</td>
										</tr>
																																								<tr>
											<td>20</td>
											<td>4 mm Thickness Acrylic Of A3 Size With Sticker</td>
											<td class="text-right">1</td>
											<td class="text-right">150.00</td>
											<td class="text-right">7.50</td>
											<td class="text-right">150.00</td>
										</tr>
																																								<tr>
											<td>21</td>
											<td>Made Out of Brown Colour Painted Wooden Frame With Glass Door And Lock ,90x75 cm</td>
											<td class="text-right">4</td>
											<td class="text-right">1,200.00</td>
											<td class="text-right">240.00</td>
											<td class="text-right">4,800.00</td>
										</tr>
									</table>
									</td>
								</tr>
								<tr style="border:1px solid #dddddd;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td colspan="4"></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>Vat Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Net Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p></b>
											</td>
											<td colspan="2" align="right">
											<?php 
												if($fc) {
													$total = $details->total / $details->currency_rate;
													$vat_amount_net = $details->vat_amount / $details->currency_rate;
													$net_total = $details->net_total / $details->currency_rate;
												} else {
													$total = $details->total;
													$vat_amount_net = $details->vat_amount;
													$net_total = $details->net_total;
												}
											?>
											<b>
												<p>{{number_format($total,2)}}</p>
												<p>{{number_format($vat_amount_net,2)}}</p>
												<?php if($details->discount!=0) { ?><p>{{number_format($details->discount,2)}}</p><?php } ?>
												<p>{{number_format($net_total,2)}}</p>
											</b>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2">
									{!! $details->description !!}
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
									<tr style="border-top: 1px solid red;">
										<td colspan="2" class="footer" align="center"> &nbsp; &nbsp; &nbsp; &nbsp; P.O. Box: 237926, Dubai - U.A.E  |  Email: info@betteradvdubai.com	 |  Web: www.betteradvdubai.com  &nbsp; &nbsp; &nbsp; &nbsp;</td>
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
