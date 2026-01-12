<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Code</th>
			<th>Name</th>
			<th>Department</th>
			<th>Expiry Date</th>
			<th>Validity</th>
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
			<td>{{$row->code}}</td>
			<td>{{$row->name}}</td>
			<td>{{$row->department_name}}</td>
			<td>{{date('d-m-Y', strtotime($row->expiry_date))}}</td>
			<?php 
				$now = time();
				$your_date = strtotime($row->expiry_date);
				$datediff = $your_date - $now;
				$datedif = round($datediff / (60 * 60 * 24));
			?>
			<td>{{($datedif<0)?'Over':$datedif}}</td>
		</tr>
		<?php } ?>
		</tbody>
	</tbody>
</table>