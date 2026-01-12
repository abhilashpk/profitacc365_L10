@extends('printinvoice')
@section('contentnew')
<table class="table table-striped table-condensed">
	<thead>
		<tr>
			<th style="width:50px;"><strong>SI.#</strong></th>
			<th><strong>Desc.</strong></th>
			<th class="text-right"><strong>Unit</strong></th>
			<th class="text-right"><strong>Qty.</strong></th>
			<th class="text-right"><strong>Rate</strong></th>
			<th class="text-right"><strong>Vat%</strong></th>
			<th class="text-right"><strong>Vat Amt.</strong></th>
			<!--<th class="text-right"><strong>Oth/Unt</strong></th>-->
			<th class="text-right"><strong>Total</strong></th>
			<th class="emptyrow" style="width:10px;"></th>
		</tr>
	</thead>
	<tbody>
	<?php $i = 0; $gross_total = 0;?>
	@foreach($items as $item)
	<?php $i++; $gross_total += $item->total_price; ?>
	<tr>
		<td>{{$i}}</td>
		<td>{{$item->item_name}}</td>
		<td class="text-right">{{$item->unit_name}}</td>
		<td class="text-right">{{$item->quantity}}</td>
		<td class="text-right">{{number_format($item->unit_price,2)}}</td>
		<td class="text-right">{{$item->vat.'%'}}</td>
		<td class="text-right">{{number_format($item->vat_amount,2)}}</td>
		<!--<td class="text-right">{{number_format($item->netcost_unit,2)}}</td>-->
		<td class="text-right">{{number_format($item->total_price,2)}}</td>
		<td></td>
	</tr>
	@endforeach
	</tbody>
	<tfoot>
	<tr>
		<td colspan="7" class="text-right"><strong>Gross Total:</strong></td>
		<td class="text-right"><strong>{{number_format($gross_total,2)}}</strong></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="7" class="text-right"><strong>Discount:</strong></td>
		<td class="text-right"><strong>{{number_format($details->discount,2)}}</strong></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="7" class="text-right"><strong>Vat Amount:</strong></td>
		<td class="text-right"><strong>{{number_format($details->vat_amount,2)}}</strong></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="7" class="text-right"><strong>Net Total:</strong></td>
		<td class="text-right"><strong>{{number_format($details->net_amount,2)}}</strong></td>
		<td></td>
	</tr>
	</tfoot>
</table>

@stop
