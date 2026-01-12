<div class="row">
<div class="col-xs-12">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Item Code</th>
			<th>Item Name</th>
			<th>Quantity</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		@foreach($items as $key => $row)
		<tr>
			<td>{{ $row->item_code }}</td>
			<td>{{ $row->description }}</td>
			<td><input type='texbox' size='5' id='rqty_{{$row->id}}' name='qtyreq[]' data-id="{{$row->id}}" data-no="{{$no}}" class='txt-qty' value='{{ $qty[$key] }}'/></td>
			<td><button class="btn btn-danger btn-xs funRemove" data-id="{{$row->id}}" data-no="{{$no}}"><i class="fa fa-fw fa-times-circle"></i></td>
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
		var idar = $('#asmid_'+n).val().split(",");
		var qtar = $('#asmqt_'+n).val().split(",");
		var a = idar.indexOf(v); 
		if (a !== -1) {
			qtar[a] = this.value;
			$('#asmqt_'+n).val(qtar);
		}
		//qtar.splice(a,1);
		
	});
});
</script>