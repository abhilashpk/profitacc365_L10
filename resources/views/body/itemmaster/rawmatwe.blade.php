<div class="col-xs-10">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Item Code</th>
			<th>Description</th>
			<th>Wastage Qty.</th>
			<th style="width:45%">Cost/Unit</th>
            <th style="width:45%">Total</th>
		</tr>
		</thead>
		<tbody>
         @php $i=0; @endphp   
		@foreach($info as $row)
        @php $i++; @endphp
		<tr>
			<td>{{ $row->item_code }} <input type="hidden" name="weitem[]" value="{{$row->subitem_id}}"></td>
			<td>{{ $row->description }}</td>
			<td><input type="text" name="wqty[]" id="wqty_{{$i}}" class="we-qty"></td>
			<td>{{ number_format($row->unit_price,2) }}<input type="hidden" name="uprice[]" id="uprice_{{$i}}" value="{{number_format($row->unit_price,2)}}"></td>
            <td><input type="text" name="weqtytot[]" id="wqtytot_{{$i}}"></td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>

<script>
$(document).on('keyup', '.we-qty', function(e) {
	var res = this.id.split('_');
	var curNum = res[1]; //console.log(curNum);
    var qty = parseFloat( ($('#wqty_'+curNum).val()=='')?0:$('#wqty_'+curNum).val() );
    var prc = parseFloat( ($('#uprice_'+curNum).val()=='')?0:$('#uprice_'+curNum).val() );
    var cst = qty * prc;
    $('#wqtytot_'+curNum).val(cst.toFixed(2));
});
</script>