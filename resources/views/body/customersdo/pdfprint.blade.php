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
									<td colspan="2" align="center">
									@include('main.print_head')
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h4><b>DELIVERY NOTE</b></h4></td>
								</tr>
								<tr>
									<td colspan="2" align="center" style="padding-bottom:25px;">
										<div style="border:1px solid #000; padding: 5px;">
											<div style="width:70%; border:0px solid red;float:left; text-align: left;">
												Account No: {{$details->account_id}}<br/>
												Customer Name: <?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:'.$details->customer_name:$details->supplier;?><br/>
												Address: <?php echo $details->address;?><br/>
												<br/>Telephone No: {{$details->phone}}<br/>
											</div>
											
											<div style="border:0px solid #000;text-align:left;">
												DO.No: {{$details->voucher_no}}<br/>
												Date: {{date('d-m-Y',strtotime($details->voucher_date))}}<br/>
												LPO No: {{$details->reference_no}}<br/>
												Ship To: {{$details->description}}<br/>
												Payment Terms: {!! $details->terms !!}<br/>
											</div>
										</div>
									</td>
								</tr>
								
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:480px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<td width="10%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>
											<td width="20%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="15%" style="border:1px solid #000 !important;" align="center"><b>Unit</b></td>
											<td width="15%" style="border:1px solid #000 !important;" align="center"><b>Qty.</b></td>
										</tr>
										<?php $i = $qtytotal = 0;?>
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
											$qtytotal += $item->quantity;
										?>
										<tr>
											<td align="center">{{$i}}</td>
											<td align="center">{{$item->item_code}}</td>
											<td align="center">{{$item->item_name}}</td>
											<td align="center">{{$item->unit_name}}</td>
											<td align="center">{{$item->quantity}}</td>
										</tr>
										@endforeach
									</table>
									</td>
								</tr>
								
								<tr style="border:1px solid black;">
									<td class="text-right" height="30px"><b>Total Quantity:</b></td>
									<td class="text-right" style="padding-right:10px;"><b>{{$qtytotal}}</b></td>
								</tr>
								
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" align="center">
										<div class="footer" style="text-align:center; border:0px solid red; width:86%;">
											<div style="width:70%; border:0px solid red;float:left; text-align: left; padding-bottom:10px;">
													Issued By:.................................
											</div>
											<div style="border:0px solid #000;text-align:left;padding-bottom:10px;">
													Received By:....................................
											</div>
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


