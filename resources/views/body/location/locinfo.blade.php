<br/>
<div class="col-xs-4">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Location</th>
			<th>Quantity</th>
			<th>Bin</th>
		</tr>
		</thead>
		<tbody>
		@php $i=1; @endphp
		@foreach($info as $row)
		<tr>
			<td>{{ $row->name }}<input type="hidden" name="locid[]" value="{{$row->id}}"></td>
			<td><input type="number" name="locqty[]" class="locqty"></td>
			<td><input type="text" name="bin[]" class="bin" id="bin_{{$i}}" autocomplete="off" data-toggle="modal" data-target="#bin_modal">
			<input type="hidden" name="binid[]" id="binid_{{$i}}">
			</td>
		</tr>
		@php $i++; @endphp
		@endforeach
		</tbody>
	</table>
</div>



<script>
var binurl = "{{ url('location/bin_data/') }}";
$(document).on('click', 'input[name="bin[]"]', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; 
	$('#bin_data').load(binurl+'/'+curNum, function(result){ 
		$('#myModal').modal({show:true}); 
	});
});



</script>