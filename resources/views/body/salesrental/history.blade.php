<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th>Voucher No</th>
		<th>Voucher Date</th>
		<th>Item Name</th>
		<th>Unit</th>
		<th>Quantity</th>
		<th>Sell Price</th>
	</tr>
	</thead>
	<tbody>
	@foreach($items as $item)
	<tr>
		<td>{{$item->voucher_no}}</td>
		<td>{{date('d-m-Y',strtotime($item->voucher_date))}}</td>
		<td>{{$item->item_name}}</td>
		<td>{{$item->unit_name}}</td>
		<td>{{$item->quantity}}</td>
		<td>{{$item->unit_price}}</td>
	</tr>
	@endforeach
	@if (count($items) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>
</table>