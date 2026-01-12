<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Emp.ID</th>
			<th>Name</th>
			<th>Document</th>
			<th>Expiry Date</th>
			<th>Validity</th>
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
			<td>{{$row['code']}}</td>
			<td>{{$row['name']}}</td>
			<td>{{$row['doc']}}</td>
			<td>{{date('d-m-Y', strtotime($row['expiry_date']))}}</td>
			<td>{{$row['days']}}</td>
		</tr>
		<?php } ?>
		</tbody>
	</tbody>
</table>