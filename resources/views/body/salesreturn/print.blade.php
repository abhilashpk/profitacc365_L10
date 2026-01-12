@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
<style>
#invoicing {
	font-size:9pt;
	font-family: "Times New Roman", Times, serif;
}

.footer, .footer-space {
  height: 110px;
}

.header {
  position: fixed;
  top: 0;
}

.footer {
  position: fixed;
  bottom: 0;
  
}

</style>
<style type="text/css" media="print">

thead { display: table-header-group; }

/* #inv
{
	display: table-footer-group;
	 position: fixed;
     bottom: 0;
	 margin: 0 auto 0 auto;
}*/


</style>

@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
         <section class="content-header">
            <h1>Sales Return</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
				<li> Sales Return</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
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
								<tr>
									<td colspan="2" align="center"><h4><b><u>SALES RETURN</u></b></h4></td>
								</tr>
								<tr>
									<td colspan="2" align="center" style="padding-bottom:25px;">
										<div style="border:1px solid #000; padding: 5px;">
											<div style="width:70%; border:0px solid red;float:left; text-align: left;">
												Account No: {{$details->account_id}}<br/>
												Customer Name: <?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;?><br/>
												Address: <?php echo $details->address;?><br/>
												<br/>Telephone No: {{$details->custphone}}<br/>
												Customer TRN: <?php echo $vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no; ?><br/><br/>
											</div>
											
											<div style="border:0px solid #000;text-align:left;">
												SR. No: {{$details->voucher_no}}</b><br/>
												Invoice No: {{$details->sales_invoice_no}}<br/>
												Date: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
												LPO No: {{$details->lpo_no}}<br/>
												Payment Terms: {!! $details->terms !!}<br/>
												Sales Person: {{$details->salesman}}<br/>
												Ship To: {{$details->description}}<br/>
											</div>
										</div>
									</td>
								</tr>
								<!--<tr>
									<td align="left" valign="top" width="45%" style="padding-left:0px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;">Customer:<br/>
											<strong><?php //echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:<br/>'.$details->customer_name:$details->supplier;?></strong>
											<?php //echo ($details->address!='')?'<br/>'.$details->address:'';?>
											<?php //echo ($details->state!='')?' '.$details->state:'';?>
											<?php //echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
											<?php //echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
											<?php //$vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
											<?php //echo ($vat_no!='')?'<br/>TRN No: <strong>'.$vat_no.'</strong>':'';?><br/>
										</div><br/>
									</td>
									<td align="left" style="padding-left:150px;">
										<div style="border:1px solid #000; padding: 10px;">
											<b>SR. No: {{$details->voucher_no}}</b><br/>
											Date:  {{date('d-m-Y',strtotime($details->voucher_date))}}
											
										</div><br/>
									</td>
								</tr>-->
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:380px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="15%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Unit</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Qty.</b></td>
											<td width="10%" align="center" style="border:1px solid #000 !important;"><b>Unt.Price</b></td>
											<td width="8%" align="center" style="border:1px solid #000 !important;"><b>VAT</b></td>
											<td width="12%" align="center" style="border:1px solid #000 !important;"><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++; 
											if($fc) {
												$unit_price = $item->unit_price / $details->currency_rate;
												$vat_amount = $item->vat_amount / $details->currency_rate;
												$line_total = $item->total_price / $details->currency_rate;
											} else {
												$unit_price = $item->unit_price;
												$vat_amount = $item->vat_amount;
												$line_total = $item->total_price;
											}
										?>
										<tr>
											<td align="center">{{$i}}</td>
											<td align="center">{{$item->item_code}}</td>
											<td align="center">{{$item->item_name}}</td>
											<td align="center">{{$item->unit_name}}</td>
											<td align="center">{{$item->quantity}}</td>
											<td align="center">{{number_format($unit_price,2)}}</td>
											<td align="center">{{number_format($vat_amount,2)}}</td>
											<td align="center">{{number_format($line_total,2)}}</td>
										</tr>
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td colspan="4"></td>
											<td colspan="2" align="right"><b>
											<p>Total Before VAT<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>Vat Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Total Inclusive VAT<?php if($fc) echo ' ('.$details->currency.')'?>: </p></b>
											</td>
											<td colspan="2" align="right">
											<?php 
												if($fc) {
													$total = $details->total / $details->currency_rate;
													$vat_amount_net = $details->vat_amount / $details->currency_rate;
													$net_total = $details->net_amount / $details->currency_rate;
												} else {
													$total = $details->total;
													$vat_amount_net = $details->vat_amount;
													$net_total = $details->net_amount;
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
										<table border="0" style="width:100%;">
											<tr>
												<td colspan="2"><br/>Items received in good condition. Please notify within 7 days from the date of invoice for any discrepancy.</td>
											</tr>
											<!--<tr>
												<td style="padding-top:50px;">
													Issued By:.................................
												</td>
												<td align="right" style="padding-top:50px;">
													Received By:....................................
												</td>
											</tr>
											<tr>
												<td>
													
												</td>
												<td align="right"><br/>
													For {{Session::get('company')}}.<br/><br/>
													
												</td>
											</tr>-->
										</table>
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
							<div style="width:70%; border:0px solid red;float:left; text-align: left; padding-bottom:10px;">
									Issued By:.................................
							</div>
							<div style="border:0px solid #000;text-align:left;padding-bottom:10px;">
									Received By:....................................
							</div>
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
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

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
