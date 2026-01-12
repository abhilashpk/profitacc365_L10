<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Item Code</th>
			<th>Description</th>
			<th>Qty.</th>
			<th style="width:45%">Cost/Unit(inc. OC)</th>
		</tr>
		</thead>
		<tbody>
		@foreach($info as $row)
		<tr>
			<td>{{ $row->item_code }}</td>
			<td>{{ $row->description }}</td>
			<td>{{ $row->quantity }}</td>
			<td>{{ number_format($row->unit_price,2) }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>