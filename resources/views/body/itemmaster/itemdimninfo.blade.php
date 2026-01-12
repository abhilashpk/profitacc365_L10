<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Unit</th>
			<th>Qty. in Hand</th>
			<th>Width</th>
			<th>Length</th>
		<!--	<th>MP Qty.</th>-->
		</tr>
		</thead>
		<tbody>
		@foreach($info as $row)
		@if($row->is_baseqty==1)
		<tr>
			<td>{{ $row->unit_name }}</td>
			<td>{{ $row->cur_quantity }}</td>
			<td>{{ $row->itmWd }}</td>
			<td>{{ $row->itmLt }}</td>
			<!--<td>{{ (($row->itmWd*$row->itmLt) > 0)?$row->cur_quantity/($row->itmWd*$row->itmLt):'' }}</td>-->
		</tr>
		@endif
		@endforeach
		</tbody>
	</table>
</div>