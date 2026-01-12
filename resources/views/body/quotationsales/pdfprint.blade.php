<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
<style>

#invoicing {
	font-size:9pt;
	font-family: "Times New Roman", Times, serif;
}

</style>
	<html>
	<body>
            <div >
                
                <div >
                    <div class="print" id="invoicing">
						<div class="col-md-10">
						<table border="0" style="width:100%;height:100%;">
							
								<tr>
									<td colspan="2" align="center">
										@include('main.print_head')
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h4><b>QUOTATION</b></h4></td>
								</tr>
								<tr>
									<td align="left" valign="top" width="55%" style="padding-left:0px; padding-bottom:5px;">
										
											<div style="border:1px solid #000; padding: 10px;">
												Account No: {{$details->account_id}}<br/>
												Customer Name: <?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;?><br/>
												Address: <?php echo $details->address;?><br/>
												<br/>Telephone No: {{$details->custphone}}<br/>
												Customer TRN: <?php echo $vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no; ?><br/>
											</div>
									</td>
									
									<td align="left" style="padding-left:150px;">
										<div style="border:1px solid #000; padding: 10px;">
											Quotation No: {{$details->voucher_no}}<br/>
												Date: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
												<!--Contact Person: {{$details->contact_name}}-->
												Payment Terms: {{$details->terms}}</br>
												Sales Person: {{$details->salesman}}<br/>
												Ref: {{$details->reference_no}}<br/><br/>
										</div>
									</td>
									
								</tr>
								<tr>
									<td colspan="2" style="padding-bottom:10px;">Greetings!<br/>
									We are pleased to offer you the following quote.</td>
								</tr>
								
						
							
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:350px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="15%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" style="border:1px solid #000 !important;" align="center"><b>Unit</b></td>
											<td width="5%" style="border:1px solid #000 !important;" align="center"><b>Qty.</b></td>
											<td width="10%" style="border:1px solid #000 !important;" align="center"><b>Unt.Price</b></td>
											<td width="8%" style="border:1px solid #000 !important;" align="center"><b>VAT</b></td>
											<td width="12%" style="border:1px solid #000 !important;" align="center"><b>Total</b></td>
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
											<td align="center">{{$i}}</td>
											<td align="center">{{$item->item_code}}</td>
											<td align="center">{{$item->item_name}}</td>
											<td align="center">{{$item->unit_name}}</td>
											<td align="center">{{$item->quantity}}</td>
											<td align="center">{{number_format($unit_price,2)}}</td>
											<td align="center">{{number_format($vat_amount,2)}}</td>
											<td align="center">{{number_format($line_total,2)}}</td>
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
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:98%;">
										<tr>
											<td colspan="4"></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>Vat Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Total Inclusive VAT<?php if($fc) echo ' ('.$details->currency.')'?>: </p></b>
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
										<table border="0" style="width:100%;">
											<tr>
												<td colspan="2" style="padding-top:7px;">
												<div style="height:auto; border:1px solid #000; padding:5px;height:35px;">Note: {{$details->footer_text}}</div>
												</td>
											</tr>
											
											<tr>
												<td style="padding-left:5px;">
													
												</td>
												<td align="right"><br/>
													For {{Session::get('company')}}.<br/><br/>
													
												</td>
											</tr>
										</table>
									</td>
								</tr>
							
							
								<tr>
									<td colspan="2" align="center"><img src="{{asset('assets/footer_'.Session::get('logo').'')}}" /></td>
								</tr>
							
						</table>
							
						</div>
                    </div>
                   
                </div>
            </div>
</body>
</html>
