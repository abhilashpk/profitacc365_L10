@extends('printgeneral')
@section('contentnew')
								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th class="text-right"><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong>Rate</strong></th>
												<th class="text-right"><strong>Vat%-Amount</strong></th>
												<th class="text-right"><strong>Oth/Unt</strong></th>
												<th class="text-right"><strong>Line Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = 0;?>
										@foreach($items as $item)
										<?php $i++;?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->item_name}}</td>
											<td class="text-right">{{$item->unit_name}}</td>
											<td class="text-right">{{$item->quantity}}</td>
											<td class="text-right">{{number_format($item->unit_price,2)}}</td>
											<td class="text-right">{{$item->vat.'%-'.number_format($item->vat_amount,2)}}</td>
											<td class="text-right">{{number_format($item->netcost_unit,2)}}</td>
											<td class="text-right">{{number_format($item->net_amount,2)}}</td>
											<td></td>
										</tr>
										@endforeach
										</tbody>
									</table>
								</div>
                       
@stop
