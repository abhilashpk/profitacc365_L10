<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Unit</th>
			<th>Qty. in Hand</th>
			<th>Sell Price</th>
			<th>Avg. Cost</th>
			<!--<th>Resvd. Qty.</th>-->
		</tr>
		</thead>
		<tbody>
		@foreach($info as $row)
		<tr>
			<td>{{ $row->unit_name }}</td>
			<td>{{ ($row->is_baseqty==0)?($row->cur_quantity * $row->pkno) / $row->packing:$row->cur_quantity}}</td>
			<td>{{ $row->sell_price }}</td>
			<td>{{ ($row->is_baseqty==1)?$row->cost_avg:'' }}</td>
		<!--	<td>{{ $row->reorder_level }}</td>-->
		</tr>
		@endforeach
		</tbody>
	</table>
</div>