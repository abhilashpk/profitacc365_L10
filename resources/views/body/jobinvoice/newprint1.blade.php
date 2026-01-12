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
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:10pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
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
        <section id="invoice-stmt">
            <div class="panel">
                <div class="" style="width:100%; !important; border:0px solid red; align=center;">
                    <div class="print" id="invoicing">
						<div>
						
						<table border="0" style="width:100%;height:100%;" >
							<thead>
								<tr>
									<td colspan="2" align="center"><!--@include('main.print_head')--></td>
								</tr>
								<tr>
									<td colspan="2" align="center" height="188px"><!--<b style="font-size:15px;"><br/><b><u>{{$titles['subhead']}}</u></b></b>--></td>
								</tr>
								<tr>
									<td style="padding-left:15px;" height="180px" width="455px">
										<strong><?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:<br/>'.$details->customer_name:$details->supplier;?></strong>
										<?php echo ($details->address!='')?'<br/>'.$details->address:'';?>
										<?php echo ($details->state!='')?' '.$details->state:'';?>
										<?php echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
										<?php echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
										<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
										<?php echo ($vat_no!='')?'<br/>TRN No: <strong>'.$vat_no.'</strong>':'';?><br/>
									</td>
									<td align="left" style="padding-left:200px;padding-top:5px;" valign="top" height="177">
										<table border="0">
											<tr>
												<td height="35px" align="left"> {{$details->jobcode}}</td>
											</tr><tr>
												<td height="35px" align="left"> {{date('d-m-Y',strtotime($details->jobdate))}}</td>
											</tr>
											<tr>
												<td height="35px" align="left"> {{$details->voucher_no}}</td>
											</tr>
											<tr>
												<td height="35px" align="left"> {{date('d-m-Y',strtotime($details->voucher_date))}}</td>
											</tr>
										</table>
									</td>
								</tr>
							</thead>
							<tbody id="bod">
								<tr>
									<td colspan="2" height="420px" align="left" valign="top">
										<table border="0" class="classtbl">
											<tr>
												<td width="35px" height="40px"><b></b></td>
												<td width="365px"><b></b></td>
												<td width="37px"><b></b></td>
												<td width="62px" class="text-right"><b></b></td>
												<td width="75px" class="text-right"><b></b></td>
												<td width="28px"><b></b></td>
												<td width="60px" class="text-right"><b> </b></td>
												<td width="90px" class="text-right"><b></b></td>
											</tr>
											<?php $i = 0;?>
											@foreach($items as $item)
											<?php $i++; 
												
													$unit_price = $item->unit_price;
													$vat_amount = $item->vat_amount;
													$line_total = $item->line_total;
													$amount = $unit_price * $item->quantity;
													if($details->discount!=0) {
														$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
													}
											?>
											<tr>
												<td align="center">{{$i}}</td>
												<td>{{$item->item_name}}</td>
												<td align="center">{{$item->quantity}}</td>
												<td class="text-right">{{number_format($unit_price,2)}}</td>
												<td class="text-right">{{number_format($amount,2)}}</td>
												<td align="center">5</td>
												<td class="text-right">{{number_format($vat_amount,2)}}</td>
												<td class="text-right">{{number_format($line_total,2)}}</td>
											</tr>
											
											<!--<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr><tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>
											<tr>
												<td align="center">A</td>
												<td>Dtwe sdf sgsds</td>
												<td align="center">3</td>
												<td class="text-right">12.00</td>
												<td class="text-right">60.00</td>
												<td align="center">5</td>
												<td class="text-right">3.00</td>
												<td class="text-right">63.00</td>
											</tr>-->
											
											@endforeach
											<?php $total = $details->total;
													$vat_amount_net = $details->vat_amount;
													$net_total = $details->net_total; ?>
										</table>
									</td>
								</tr>
								<tr>
									<td height="40px" colspan="2" style="padding-left:40px;">
										<b style="font-size:11pt;">{{$amtwords}}</b> <?php if($details->discount > 0){?>&nbsp; &nbsp; Discount: {{number_format($details->discount,2)}} <?php }?>
									</td>
								</tr>
								<tr>
									<td height="56px" colspan="2" style="padding-left:50px;">
										<table border="0">
											<tr>
												<td width="251px" height="40px" style="padding-left:30px;"><b style="font-size:11pt;">{{number_format($total,2)}}</b></td>
												<td width="251px"  style="padding-left:50px;"><b style="font-size:11pt;">{{number_format($vat_amount_net,2)}}</b></td>
												<td width="251px"  style="padding-left:50px;"><b style="font-size:11pt;">{{number_format($net_total,2)}}</b></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="" colspan="2">
										
									</td>
								</tr>
							</tbody>
							<tfoot id="inv">
								<tr>
									<td colspan="2" height="" class="footer"><!--<br/>@include('main.print_foot')--></td>
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
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
