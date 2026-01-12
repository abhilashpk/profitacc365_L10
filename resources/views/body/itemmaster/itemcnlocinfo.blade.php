<br/>
<?php $num = $num - 1; ?>
<div class="col-xs-5">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Consignment Location</th>
			<th>Quantity</th>
		</tr>
		</thead>
		<tbody>
		@foreach($info as $row)
		<tr>
			<td>{{ $row->name }}</td>
			<td class="cnnum"><input type="number" name="cnlocqty[{{$num}}][]" class="cnloc-qty-{{$num+1}}" data-id="{{$num+1}}">
			<input type="hidden" class="cnloc-id" name="cnlocid[{{$num}}][]" value="{{$row->id}}"/></td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>