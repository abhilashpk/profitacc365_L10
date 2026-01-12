<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th>Consignment No</th>
		<th>Consignment Date</th>
		<th>Rate</th>
		<th>Rate Unit</th>
		
	</tr>
	</thead>
	<tbody>
	
	
     @foreach($pktype as $rw)

	<tr>
		<td>{{$rw->job_code}}</td>
		<td>{{date('d-m-Y',strtotime($rw->job_date))}}</td>
		<td>{{$rw->rate}}</td>
	
		<td>{{$rw->ptypes}}</td>
		
	</tr>
	
	@endforeach
	@if (count($rate) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>
</table>