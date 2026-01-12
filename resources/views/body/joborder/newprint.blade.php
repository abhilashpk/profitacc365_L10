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
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
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
    
    <aside class="right-side">


        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td align="left" width="45%"><img src="{{asset('assets/'.Session::get('logo').'')}}"  /></td>
									<td  align="left"><b style="font-size:10px;"><u>JOB ORDER</u></b></td>
									<td width="40%">P.O. Box-35574, Al Khan Ring Road, <br/>Ind.Area No. 10, Sharjah, UAE,<br/> Tel: 6 555 0076, Mob: 050 917 4790,<br/> altoudcarservice@gmail.com<br/>TRN No: {{Session::get('vatno')}}</td>
								</tr>
								
								<tr>
									<td align="left" valign="top" style="padding-left:10px; padding-top:10px;">
										<div>
											<?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?$details->customer_name:$details->supplier;?>
											<?php echo ($details->address!='')?', Address: '.$details->address:'';?>
											<?php //echo ($details->state!='')?' '.$details->state:'';?>
											<?php //echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
											<?php //echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
											<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
											<?php echo ($vat_no!='')?', TRN No: '.$vat_no:'';?>
										</div>
									</td>
									<td></td>
									<td align="right" style="padding-left:0px;">
										<div><?php date_default_timezone_set('Asia/Dubai'); $datetime = date('Y-m-d H:i:s'); ?>
											<b>Order No: {{$details->voucher_no}}</b> | 
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											Vch.Name: {{$details->name}} | 
											Reg.No: {{$details->reg_no}} | <br/>
											Make: {{$details->make}} | Color: {{$details->color}}
										</div>
									</td>
								</tr>
								
								
							</thead>
							<tbody id="bod">
								<tr style="border:0px solid black;">
									<td colspan="3" align="center">
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<tr>
											<td width="5%"><b>SI.#</b></td>
											<td width="53%"><b>Description</b></td>
											<td width="5%" class="text-right"><b>Qty.</b></td>
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
											<td>{{$i}}</td>
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
												<td colspan="5" align="right"><b>Gross Total:</b></td>
												<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											</tr>
											<tr>																														<tr>
												<td colspan="5" align="right"><b>VAT Total:</b></td>
												<td class="text-right"><b>{{number_format($vat_amount_net,2)}}</b></td>
											</tr>
											<tr>																														<tr>
												<td colspan="5" align="right"><b>Net Total:</b></td>
												<td class="text-right"><b>{{number_format($net_total,2)}}</b></td>
											</tr>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="3" class="footer"><br/>
									<table border="0" width="100%"><tr>
										<td style="width:50%;" valign="bottom">
											Customer's Signature: _________________ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
										</td>
										<td align="right" style="font-size:7pt;padding-left:5px;width:50%;">
											For {{Session::get('company')}}.
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
