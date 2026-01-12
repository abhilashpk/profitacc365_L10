<table class="table table-bordered table-hover">
	<thead>
	<tr>
    <th>Customer</th>
		<th>Vehicle No</th>
		<th>Vehicle Name</th>

		<th>Model</th>
     
		<th>Make</th>
		<th>Present Km</th>
		<th>Service km</th>
        <th>Next Due</th>
	</tr>
	</thead>
	<tbody>
	@foreach($jobmasterrow as $item)
	<tr>
    <td>{{$item->customer}}</td>
		<td>{{$item->reg_no}}</td>
		
		<td>{{$item->vehicle}}</td>
		<td>{{$item->model}}</td>
		<td>{{$item->make}}</td>
		<td>{{$item->present_km}}</td>
        <td>{{$item->service_km}}</td>
        <td>{{date('d-m-Y',strtotime($item->next_due))}}</td>
	</tr>
	@endforeach
	@if (count($jobmasterrow) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>
</table>