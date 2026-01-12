<br/>
<?php $num = $num - 1; ?>
<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Item Code</th>
			<th>Description</th>
			<th>Quantity</th>
			<th>Unit</th>
			<th class="text-right">Cost Avg.</th>
			<th class="text-right">Seles Price</th>
		</tr>
		</thead>
		<tbody>
		@foreach($items as $row)
		<tr>
			<td>{{ $row->item_code }}</td>
			<td>{{ $row->description }}</td>
			<td>{{$row->quantity}}</td>
			<td>{{$row->packing}}</td>
			<td>{{ number_format($row->cost_avg,2) }}</td>
			<td>{{ number_format($row->sell_price,2) }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>