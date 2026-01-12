<br/>
<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Code</th>
			<th>Location Name</th>
			<th>Quantity</th>
			<th>Bin</th>
		</tr>
		</thead>
		<tbody>
		@foreach($items as $row)
		<tr>
			<td>{{ $row->code }}</td>
			<td>{{ $row->name }}</td>
			<td>{{$row->quantity}}</td>
			<td>{{$row->bin}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>