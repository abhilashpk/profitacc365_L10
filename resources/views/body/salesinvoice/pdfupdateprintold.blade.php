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
								
								
								<tr><td colspan="2" align="center"><h4><b>SALES INVOICE ITEM </b></h4></td></tr>
							
								
								
							</thead>
							<tbody>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center" valign="top" style="height:400px;">
									<table border="0" style="width:100%;" class="table table-bordered">
										<tr>
											<!--<td width="5%" align="center" style="border:1px solid #000 !important;"><b>Si.#</b></td>-->
											<td width="15%" align="center" style="border:1px solid #000 !important;"><b>Item Code</b></td>
											<td width="40%" align="center" style="border:1px solid #000 !important;"><b>Description</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Unit</b></td>
											<td width="5%" align="center" style="border:1px solid #000 !important;" ><b>Qty.</b></td>
											<td width="10%" align="center" style="border:1px solid #000 !important;" ><b>Unt.Price</b></td>
											<td width="8%" align="center" style="border:1px solid #000 !important;" ><b>VAT</b></td>
											<td width="12%" align="center" style="border:1px solid #000 !important;" ><b>Total</b></td>
										</tr>
										<?php $i = 0;?>
										
										<?php $i++; 
											
										?>
										
											<!--<td align="center">{{$i}}</td> -->
											@foreach($codeold as $code)
											<tr>
											<td align="center">{{$code}}</td>
											</tr>
											@endforeach
											
											
											@foreach($nameold as $name)
											<tr>
											<td align="center">{{$name}}</td>
											</tr>
											@endforeach
											
											
											@foreach($unitold as $unit)
											<tr>
											<td align="center">{{$unit}}</td>
											</tr>
											@endforeach
											
											
											@foreach($qtyold as $qty)
											<tr>
											<td align="center">{{$qty}}</td>
											</tr>
											@endforeach
											
											
											@foreach($costold as $cost)
											<tr>
											<td align="center">{{number_format( $cost,2)}}</td>
											</tr>
											@endforeach
											
											
											@foreach($vatold as $vat)
											<tr>
											<td align="center">{{$vat}}</td>
											</tr>
											@endforeach
											
											
											@foreach($totalold as $total)
											<tr>
											<td align="center">{{number_format($total,2)}}</td>
											</tr>
											@endforeach
										
										
										
									</table>
									</td>
								</tr>
								<tr style="border:1px solid black;">
									<td colspan="2" align="center">
										<table border="0" style="width:100%;">
										<tr>
											<td width="65%" style="padding-left:10px;">Amount in words: <b>{{$words}}</b></td>
											
											<td colspan="2" align="right" style="padding-right:10px;">
											
											<b>
												<p>{{number_format($grandtotalold,2)}}</p>
												<p>{{number_format($totvatold,2)}}</p>
												
												<p>{{number_format($netold,2)}}</p>
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

