<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Document Name</th>
			<th>Expiry Date</th>
			<th>Validity</th>
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
			<td>{{$row['name']}}</td>
			<td>{{date('d-m-Y', strtotime($row['expiry_date']))}}</td>
			<td>{{$row['days']}}</td>
		</tr>
		<?php } ?>
		</tbody>
	</tbody>
</table>