<div class="row">
<div class="col-xs-12">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Code</th>
			<th>Location</th>
			<th>Quantity</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		@foreach($items as $key => $row)
		<tr>
			<td>{{ $row->code }}</td>
			<td>{{ $row->name }}</td>
			<td><input type='texbox' size='5' id='rqty_{{$row->id}}' name='qtyreq[]' data-id="{{$row->location_id}}" data-no="{{$no}}" class='txt-qty' value='{{ $qty[$key] }}'/></td>
			<td><button class="btn btn-danger btn-xs funRemove" data-id="{{$row->location_id}}" data-no="{{$no}}"><i class="fa fa-fw fa-times-circle"></i></td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
</div>
<script>
$(function() {
	
	$(document).on('keyup', '.txt-qty', function(e) {  
		//console.log($(this).data("no"));
		var n = $(this).data("no");
		var v = $(this).data("id").toString();
		var idar = $('#hdlocid_'+n).val().split(",");
		var qtar = $('#hdlocqty_'+n).val().split(",");
		var a = idar.indexOf(v); 
		if (a !== -1) {
			qtar[a] = this.value;
			$('#hdlocqty_'+n).val(qtar);
		}
		//qtar.splice(a,1);
		
	});
});
</script>