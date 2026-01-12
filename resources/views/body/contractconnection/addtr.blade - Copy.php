@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!--<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
    <!--end of page level css-->
<style>
#invoicing {
	font-size:9pt;
	/*font-family: "Times New Roman", Times, serif;*/
}

.boxtable {padding:5px !important;}​

.footer, .footer-space {
  height: 100px; /*170*/
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

*/#inv
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
            <h1>Sales Invoice</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
				<li> TAX Invoice</li>
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
									<td colspan="2" align="center" width="50%">
									@include('main.print_head')
									</td>
								</tr>
								
								<tr><td colspan="2" align="center"><h4><b>VAT INVOICE / فاتورة ضريبة القيمة المضافة</b></h43</b></td></tr>
							            
							    <tr>
									<td align="left" valign="top" width="35%" style="padding-left:0px; padding-bottom:5px;">
									    Invoice No / رقم الفاتورة: 789
									</td>
									<td align="left" style="padding-left:150px;">
										Invoice Date / 1234:تاريخ الفاتورة: 
									</td>
								</tr>
								
								<tr>
									<td align="left" valign="top" width="50%" style="padding-right:20px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;">
											Customer Name: <?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='CASH CUSTOMERS' || $details->supplier=='Cash Customers' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->cash_customer:$details->supplier;?><br/><?php //echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='CASH CUSTOMERS' || $details->supplier=='Cash Customers' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;?>
											<br/>Tel. No: {{$details->custphone}}<br/>
											Customer VAT: <?php echo $vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no; ?><br/>
										</div>
									</td>
									<td align="left" width="50%" style="padding-left:20px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;">
											Customer Address: <?php echo $details->address;?><br/>
											PO No: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											DO No: {{$details->lpo_no}}<br/>
											Delivery Date: {!! $details->terms !!}<br/>
										</div>
									</td>
								</tr>
								
								
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:200px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="2%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="68%" align="left" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Unit</b></td>
											
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Qty.</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Rate</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Gross</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>VAT%</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>VAT Amt.</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Total</b></td>
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
												$excvat = $item->quantity * $unit_price;
												if($details->discount!=0) {
													$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
												}
												$lntotal = $line_total + $vat_amount;
											}
										?>
										<tr>
											<td align="center">{{$i}}</td>
											<td align="left">{{$item->item_name}}</td>
											<td align="center">{{$item->unit_name}}</td>
											<td align="center">{{$item->quantity}}</td>
											<td align="center">{{number_format($unit_price,2)}}</td>
											<td align="center">{{number_format($excvat,2)}}</td>
											<td align="center">5</td>
											<td align="center">{{number_format($vat_amount,2)}}</td>
											<td align="center">{{number_format($lntotal,2)}}</td>
										</tr>
										<?php if(array_key_exists($item->id, $itemdesc)) { 
											foreach($itemdesc[$item->id] as $desc) { ?>
											<tr>
												<td></td>
												<td colspan="8">{{$desc->description}}</td>
											</tr>
										<?php } } ?>
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:100%;">
										<tr>
											<td width="65%" style="padding-left:10px;">Amount in words: <b>{{$amtwords}}</b></td>
											<td colspan="2" align="right"><b>
											<p>Gross<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>Discount <?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>VAT<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Net <?php if($fc) echo ' ('.$details->currency.')'?>: </p></b>
											</td>
											<td colspan="2" align="right" style="padding-right:10px;">
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
								<tr style="border:0px solid black;">
									<td colspan="2"><?php if(!$fc) { ?>
										<table border="0" style="width:100%;">
											<tr>
												<td style="padding-top:20px;">
													Salesman:.................................
												</td>
												<td align="right" valign="middle" style="padding-top:20px;">
													Receiver:.................................
												</td>
											</tr>
											
										</table><?php } ?>
									</td>
								</tr>
								<tr style="border:0px solid black;">
									<td colspan="2" align="center">
									    <img id='barcode' 
            src="https://api.qrserver.com/v1/create-qr-code/?data=AR9NQU5BSEVMIEtIQUxFRUogVFJBRElORyBDT01QQU5ZAg8zMTAxNjAwODM4MDAwMDMDEzIwMjMtMTEtMjIgMTE6MTk6MTUEBjE0MC4zMAUFMTguMzA=&amp;size=100x100" 
            width="175" 
            height="175" />
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
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('sales_invoice/export_po') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{$id}}">
						<input type="hidden" name="fc" value="{{$fc}}">
						<input type="hidden" name="amtwrds" value="{{$amtwords}}">
						</form>
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
function getExport() { document.frmExport.submit(); }
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
    <!-- end of page level js -->
@stop
