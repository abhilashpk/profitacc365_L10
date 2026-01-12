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
								
								
								<tr><td colspan="2" align="center"><h4><b>SALES INVOICE NEW  </b></h4></td></tr>
								<tr>
									<td align="left" valign="top" width="35%" style="padding-left:0px; padding-bottom:5px;">
										<div style="border:1px solid #000; padding: 10px;"><br/>
											SI No: {{$salesitems[0]->voucher_no}}<br/>
											Modified by: <?php echo $salesitems[0]->name;?><br/>
											Modified at: {{date('d-m-Y',strtotime($salesitems[0]->created_at))}}<br/>

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
										<?php $i = 0; ?>
										@foreach($salesitems as $item)
										<?php $i++; $unit_price=$vat_amount=$line_total=0;
											
												$unit_price = $item->unit_price;
												$vat_amount = $item->vat_amount;
												$line_total = $item->line_total;
												
												
											
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
									
										<table border="0" style="width:100%;">
										<tr>
											<td width="65%" style="padding-left:10px;">Amount in words: <b>{{$words}}</b></td>
											<td colspan="2" align="right"><b>
											<p>Gross Total: </p>
											<p>Vat Total: </p>
											<p>Net Total: </p></b>
											</td>
											<td colspan="2" align="right" style="padding-right:10px;">
											<?php 
												
													$total = $salesitems[0]->total;
													$vat_amount_net = $salesitems[0]->vat_amount;
													$net_total =$salesitems[0]->net_total;
												
											?>
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
								<tr style="border:0px solid black;">
									
								</tr>
							</tbody>
						
						</table>
						
							
						</div>
                    </div>
					
                </div>
            </div>

