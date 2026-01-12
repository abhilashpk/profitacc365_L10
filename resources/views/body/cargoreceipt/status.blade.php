<table border="0" style="font-weight:bold;cell-spacing:10px;width:100%;">
@foreach($row as $rw)
	<tr>
		<td>Way Bill No: {{$rw->bill_no}}</td>
		<td>Despatch No: {{$rw->despatch_no}}</td>
		<td>Packing Qty:	{{$rw->loaded_pack_qty}}</td>
	</tr>
@endforeach
</table>



