<table class="table" border="0">
	<thead>
		<tr>
			<th>Bill No</th>
			<th>Consignment No</th>
			<th>Consignee</th>
			<th>Phone</th>
			<th>Way Bill</th>
			<th>City</th>
			<th>Qty</th>
			<th class="text-right">Amount</th>
			<th>Col.Type</th>
			<th>Del.Type</th>
			<th>Remarks</th>
		</tr>
	</thead>
		@if(!empty($results))
		@php $total = 0; @endphp
			@foreach($waybills as $row)
			<tr>
				<td>{{$row->bill_no }}</td>
				<td>{{ $row->jobs }}</td>
				<td>{{ $row->consignee_name }}</td>
				<td>{{ $row->phone }}</td>
				<td>{{ $row->wbill_no }}</td>
				<td>{{ $row->destination }}</td>
				<td>{{ $row->despatch  }}</td>
				<td class="text-right">{{$row->total_amount}}</td>
				<td>{{ $row->coll_type}}</td>
				<td>{{  $row->del_type }}</td>
				<td>{{ $row->remarks}}</td>
				@php $total += $row->total_amount; @endphp
			</tr>
			@endforeach
			<tr>
				<td colspan="7" align="right"><b>Total:</b></td>
				<td align="right"><b>{{number_format($total,2)}}</b></td>
				<td colspan="3" align="right"></td>
			</tr>
		@else
			<tr><td colspan="11" align="center">No reports were found!</td></tr>
		@endif
	<tbody>
	</tbody>
</table>