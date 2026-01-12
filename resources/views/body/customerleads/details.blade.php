<table border="0" style="font-weight:bold;cell-spacing:10px;width:100%;">
@foreach($row as $rows)
	<tr>
		<td>Company Name: {{$rows->master_name}}</td>
		<td>:Creator Name:{{$rows->user}}</td>
		
	</tr>
@endforeach
</table>