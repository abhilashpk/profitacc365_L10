@extends('printgeneral')
@section('contentnew')

								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
											<tr>
												<th style="width:50px;"><strong>SI.No.</strong></th>
												<th><strong>Item Code</strong></th>
												<th><strong>Description</strong></th>
												<th><strong>Unit</strong></th>
												<th class="text-right"><strong>Quantity</strong></th>
												<th class="text-right"><strong><?php echo ($type=='opening_quantity')?'Cost Open':'Cost Avg.';?></strong></th>
												<th class="text-right"><strong>Total</strong></th>
												<th class="emptyrow" style="width:10px;"></th>
											</tr>
										</thead>
										<tbody>
										<?php $i = $total = $qtytotal = 0;?>
										@foreach($results as $item)
										<?php $i++; 
											if($type=='opening_quantity') {
												$qty = $item->opn_quantity;
												$cost = $item->opn_cost;
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											} else {
												$qty = $item->cur_quantity;
												$cost = $item->cost_avg;
												$subtotal = $cost * $qty;
												$total += $subtotal;
												$qtytotal += $qty;
											}
										?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$item->item_code}}</td>
											<td>{{$item->description}}</td>
											<td>{{$item->packing}}</td>
											<td class="text-right">{{$qty}}</td>
											<td class="text-right">{{number_format($cost,2)}}</td>
											<td class="text-right">{{number_format($subtotal,2)}}</td>
											<td></td> 
										</tr>
										@endforeach
										<tr>
											<td colspan="4"><b>Grand Total:</b></td>
											<td class="text-right"><b>{{$qtytotal}}</b></td>
											<td></td>
											<td class="text-right"><b>{{number_format($total,2)}}</b></td>
											<td></td>
										</tr>
										</tbody>
									</table>
								</div>
                        </div>
                      
                    </div>
                    
@stop
