@extends('printgeneral')
@section('contentnew')
						<div class="pull-center"><h3>{{$voucherhead}}</h3></div>
								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
										<tr class="bg-primary">
											<th style="width:70px;">
												<strong>Invoice No</strong>
											</th>
											<th>
												<strong>Date</strong>
											</th>
											<th>
												<strong>Supplier</strong>
											</th>
											<th class="text-right">
												<strong>Amount</strong>
											</th>
											<th class="text-right">
												<strong>Discount</strong>
											</th>
											<th class="text-right">
												<strong>Vat</strong>
											</th>
											<th class="text-right">
												<strong>NetAmount</strong>
											</th>
											<th class="text-right">
												<strong>Other Cost</strong>
											</th>
											<th class="emptyrow" style="width:20px;"></th>
										</tr>
										</thead>
										<tbody>
										<?php if(sizeof($cash['items']) > 0) { ?>
										<tr><td colspan="9"><strong>Cash Transactions</strong></td></tr>
										@foreach($cash['items'] as $row)
										<tr> 
											<td>{{$row['inv_no']}}</td>
											<td>{{$row['date']}}</td>
											<td>{{$row['supplier']}}<br/>TNR:{{$row['vat_no']}}</td>
											<td class="emptyrow text-right">{{number_format($row['total'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['discount'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['vat'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['net_amount'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['other_cost'],2)}}</td>
											<td class="emptyrow"></td>
										</tr>
										@endforeach
										<tr>
											<td></td><td></td><td class="highrow text-right"><strong>Cash Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($cash['cash_total'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cash['cash_discount'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cash['cash_vat'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cash['cash_net'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($cash['cash_ocost'],2)}}</strong></td>
											<td class="emptyrow"></td>
										</tr>
										<?php } ?>
										<?php if(sizeof($credit['items']) > 0) { ?>
										<tr><td colspan="9"><strong>Credit Transactions</strong></td></tr>
										@foreach($credit['items'] as $row)
										<tr> 
											<td>{{$row['inv_no']}}</td>
											<td>{{$row['date']}}</td>
											<td>{{$row['supplier']}}<br/>TNR:{{$row['vat_no']}}</td>
											<td class="emptyrow text-right">{{number_format($row['total'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['discount'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['vat'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['net_amount'],2)}}</td>
											<td class="emptyrow text-right">{{number_format($row['other_cost'],2)}}</td>
											<td class="emptyrow"></td>
										</tr>
										@endforeach
										<tr>
											<td></td><td></td><td class="highrow text-right"><strong>Credit Total:</strong></td>
											<td class="highrow text-right"><strong>{{number_format($credit['credit_total'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($credit['credit_discount'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($credit['credit_vat'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($credit['credit_net'],2)}}</strong></td>
											<td class="emptyrow text-right"><strong>{{number_format($credit['credit_ocost'],2)}}</strong></td>
											<td class="emptyrow"></td>
										</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
                        </div>
                      
                   
@stop
