<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th>Item Name</th>
		<!--<th>Unit</th>-->
		<th>Quantity</th>
	</tr>
	</thead>
	<tbody>
	@foreach($items as $item)
	<tr>
		<td>{{$item->name}}</td>
		<!--<td>{{$item->unit_name}}</td>-->
		<td>{{$item->quantity}}</td>
	</tr>
	@endforeach
	@if (count($items) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>
</table>