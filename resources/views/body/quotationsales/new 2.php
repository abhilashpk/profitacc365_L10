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
	font-size:8pt;
	/*font-family: "Times New Roman", Times, serif; */
}

.footer, .footer-space {
  height: 110px;
}

/*.header {
  position: fixed;
  top: 0;
}

.footer {
  position: fixed;
  bottom: 0;
  
}/*

thead
{
    display: table-row;
}

</style>

@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
         <section class="content-header">
            <h1>Quotation Sales</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
				<li> Quotation</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section id="invoice-stmt">
            <div class="panel">
                
                <div >
                    <div class="print" id="invoicing">
						<div>
						<table border="0" >
							<thead>
								<tr>
									<td colspan="2" align="right">
										<img src="{{asset('assets/'.Session::get('logo').'')}}" /><br/>
									</td>
								</tr>
								<tr>
									<td align="left" valign="top" width="55%" style="padding-left:0px; padding-bottom:5px;">
										<div style="padding: 10px;">
											Customer: <b>{{$details->supplier}}</b><br/>
											Attn.: <b>{{$details->reference_no}}</b><br/>
											Project: <b>{{$details->description}}</b><br/>
											Subject: <b>{{$details->subject}}</b><br/>
										</div>
									</td>
									
									<td align="left" style="padding-left:150px;">
										<div style="padding: 10px;">
											Date: <b>{{date('d-m-Y',strtotime($details->voucher_date))}}</b><br/>
											Qtn. Ref.: <b>{{$details->voucher_no}}</b><br/>
										</div>
									</td>
								</tr>
								
							</thead>
							<tbody>
								<tr style="border:1px solid grey !important;">
									<td colspan="2" align="center" valign="top">
									<table border="0" style="width:100%;" >
										<tr>
											<td width="3%" align="center" style="font-size: 7pt; border:1px solid #000 !important;">Si.No</td>
											<td width="10%" align="center" style="font-size: 7pt; border:1px solid #000 !important;">Item Reference</td>
											<td width="7%" align="center" style="font-size: 7pt; border:1px solid #000 !important;">Brand</td>
											<td width="25%" align="center" style="font-size: 7pt; border:1px solid #000 !important;">Material Description</td>
											<td width="10%" align="center" style="font-size: 7pt; border:1px solid #000 !important;">Code</td>
											<td width="5%" style="font-size: 7pt; border:1px solid #000 !important;" align="center">Qty.</td>
											<td width="20%" style="font-size: 7pt; border:1px solid #000 !important;" align="center">Image</td>
											<td width="10%" style="font-size: 7pt; border:1px solid #000 !important;" align="center">AED Unit Price</td>
											<td width="10%" style="font-size: 7pt; border:1px solid #000 !important;" align="center">AED Total Price</td>
										</tr>
										@php $i = 0;@endphp
										@foreach($items as $item)
										@php $i++; 
												$unit_price = $item->unit_price;
												$vat_amount = $item->vat_amount;
												$line_total = $item->item_total;
										@endphp
										<tr style="border:1px solid grey !important;">
											<td align="center">{{$i}}</td>
											<td align="center">{{$item->remarks}}</td>
											<td align="center">{{$item->group_name}}</td>
											<td align="center">{{$item->item_name}}</td>
											<td align="center">{{$item->item_code}}</td>
											<td align="center">{{$item->quantity}}</td>
											<td align="center" style="padding:5px !important;">@if($item->image!='')<img src="{{asset('uploads/item/'.$item->image.'')}}" style="max-height:100px;max-width:100px;" />@endif</td>
											<td align="center">{{number_format($unit_price,2)}}</td>
											<td align="center">{{number_format($line_total,2)}}</td>
										</tr>
										@endforeach
									</table>
									</td>
								</tr>
								<tr style="border:1px solid #000;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td align="right">
											<p>Net Price:</p>
											<p>Tax 5%:</p>
											<p>Total Price with Tax:</p>
											</td>
											<td  align="right" style="border-left:1px solid #000;">
											@php 
													$total = $details->total;
													$vat_amount_net = $details->vat_amount;
													$net_total = $details->net_total;
												
											@endphp
											<b>
												<p>{{number_format($total,2)}}</p>
												<p>{{number_format($vat_amount_net,2)}}</p>
												<p>{{number_format($net_total,2)}}</p>
											</b>
											</td>
										</tr>
										
										</table>
									</td>
								</tr>
								<tr style="border:1px solid #000;">
									<td colspan="2" align="center">
										<b>{{$amtwords}}</b>
									</td>
								</tr>
							</tbody>
						</table>
						
						<table border="0" style="width:100%;">
							<tr>
								<td style="padding-top:7px;">
								<b>Terms & Conditions</b><br/>
								<div style="padding-left:25px;">
									1. Prices covered delivery to the site.<br/>
									2. Delivery will be within 14-16 weeks from the date of issuing LC.<br/>
									3. Installation is not included in this offer.<br/>
									4. Manufacturer's Warranty Applicable.<br/>
									5. Payment Terms: Irrevocable Letter of Credit.<br/>
									6. Offer is valid for 30 Days.<br/><br/>
									</div>
										For Eqwep Building & Construction Materials Trading LLC.	<br/><br/><br/>
										Ramzi Taiyem<br/>
										ramzi@eqwep-me.com<br/>
										+971544949929<br/>
								</td>
							</tr>
							
							<tr>
								<td style="padding-top:25px;">
										Dubai Showroom:<br/>
										Sheikh Zayed Rd, 2020 Building<br/><br/>
										
											Head Office : Concord Tower 2306, Media city, Dubai<br/>
											T: +971 4 564 1453
								</td>
							</tr>
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

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
<script>
$(document).ready(function () {
	//$('html').attr({style: 'min-height: inherit'});
//	$('body').attr({style: 'min-height: inherit'});
});
</script>
    <!-- end of page level js -->
@stop
