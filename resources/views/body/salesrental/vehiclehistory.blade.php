<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th>JI.No</th>
		<th>JI.Date</th>
		<th>Item Name</th>
		<th>Unit</th>
		<th>Quantity</th>
		<th>Km.</th>
		<th>Amount</th>
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
		<td>{{$item->kilometer}}</td>
		<td>{{number_format($item->unit_price,2)}}</td>
	</tr>
	@endforeach
	@if (count($items) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>
</table>