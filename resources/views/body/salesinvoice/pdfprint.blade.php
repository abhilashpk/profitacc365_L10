    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
<style>
#invoicing {
	font-size:9pt;
	font-family: "Times New Roman", Times, serif;
}

</style>
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
								
								<tr><td colspan="2" align="center"><h4><b>TAX INVOICE</b></h4></td></tr>
								<tr>
									<td align="left" valign="top" width="35%" style="padding-left:0px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;">Bill To:<br/>
											Account No: {{$details->account_id}}<br/>
											Customer Name: <?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='CASH CUSTOMERS' || $details->supplier=='Cash Customers' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->cash_customer:$details->supplier;?><br/><?php //echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='CASH CUSTOMERS' || $details->supplier=='Cash Customers' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;?>
											Address: <?php echo $details->address;?><br/>
											<br/>Telephone No: {{$details->custphone}}<br/>
											Customer TRN: <?php echo $vat_no = ($details->vat_no=='')?$details->customer_trn:$details->vat_no; ?><br/>
										</div>
									</td>
									<td align="left" style="padding-left:150px;">
										<div style="border:1px solid #000; padding: 10px;">
											Invoice No: {{$details->voucher_no}}<br/>
											Invoice Date: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
											LPO No: {{$details->lpo_no}}<br/>
											Payment Terms: {!! $details->terms !!}<br/>
											Sales Person: {{$details->salesman}}<br/>
											Ship To: {{$details->description}}<br/>
										</div>
									</td>
								</tr>
								
								
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:400px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="15%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Unit</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Qty.</b></td>
											<td width="10%" align="center" style="border:1px solid #000 !important;" ><b>Unt.Price</b></td>
											<td width="8%" align="center" style="border:1px solid #000 !important;" ><b>VAT</b></td>
											<td width="12%" align="center" style="border:1px solid #000 !important;" ><b>Total</b></td>
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
												
												if($details->discount!=0) {
													$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
												}
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
												<td ></td>
												<td colspan="6">{{$desc->description}}</td>
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
											<p>Gross Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<p>Vat Total<?php if($fc) echo ' ('.$details->currency.')'?>: </p>
											<?php if($details->discount!=0) { ?><p>Discount: </p><?php } ?>
											<p>Total Inclusive VAT<?php if($fc) echo ' ('.$details->currency.')'?>: </p></b>
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
												<td align="left" style="padding-top:40px;"> 
												<b>Bank Account Details:</b><br/>
												Account No: 11395278820001<br/>
												Bank: Islamic Business Choice Account<br/>
												IBAN: AE52003001195278820001<br/>
												SWIFT Code: ADCBAEAA
												</td>
												<td align="right" valign="middle" style="padding-right:10px;"><br/>
												For {{Session::get('company')}}.
												</td>
											</tr>
										</table><?php } ?>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" align="center">
										<div class="footer" style="text-align:center; border:0px solid red; width:86%;">
											<img src="{{asset('assets/footer_'.Session::get('logo').'')}}" />
										</div>
									</td>
								</tr>
							</tfoot>
						</table>
						
							
						</div>
                    </div>
					
                </div>
            </div>

