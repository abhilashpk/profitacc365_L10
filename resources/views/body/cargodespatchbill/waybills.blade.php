<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tableRV">
		<thead>
		<tr>
			<th></th>
			<th>Way Bill</th>
			<th>WBill Date</th>
			<th>Consignee</th>
			<th>Cons. No</th>
			<th>Vehicle No</th>
			<th>Driver</th>
			<th>Amount</th>
		</tr>
		</thead>
		<tbody>
			@php $total = '' @endphp
			@if(!empty($waybills))
			@php $total = 0 @endphp
			@foreach($waybills as $row)
			<tr>
				<td><input type="checkbox" id="tag_{{$row->id}}" name="waybill_id[]" class="tag-line-nw clschk" value="{{$row->id}}" checked ></td>
				<td>{{$row->bill_no }}</td>
				<td>{{ date('d-m-Y', strtotime($row->bill_date)) }}</td>
				<td>{{$row->consignee_name}}</td>
				<td>{{$row->jobs}}</td>
				<td>{{$row->vehicle_no}}</td>
				<td>{{$row->driver}}</td>
				<td>{{$row->total_amount}}<input type="hidden" id="amt_{{$row->id}}" value="{{$row->total_amount}}"></td>
				@php $total += $row->total_amount @endphp
			</tr>
			@endforeach
			@else
			<tr><td colspan="10" align="center">No records were found!</td></tr>	
			@endif
		</tbody>
	</table>
	<br />
<span id="spnError" class="error" style="display: none">Please select at-least one Waybill.</span>
<br />
</div>
