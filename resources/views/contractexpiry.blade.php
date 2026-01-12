<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Customer Name</th>
			<th>Building Code</th>
			<th>Flat Code</th>
			<th>Contract No.</th>
			<th>Contract Expiry Date</th>
			<th>Days</th>
		</tr>
	</thead>
		<tbody>
		@foreach($result as $row)
		<?php 
				$now = time();
				$your_date = strtotime($row->end_date);
				$datediff = $your_date - $now;
				$datedif = round($datediff / (60 * 60 * 24));
			?>
			<?php if($datedif <= 90 ) {  ?>
		<tr>
			<td>{{ $row->master_name }}</td>
			<td>{{ $row->buildcode }}</td>
			<td>{{ $row->flat }}</td>
			
			<td>{{ $row->contract_no  }}</td>
			<td>{{ date('d-m-Y', strtotime($row->end_date)) }}</td>
			
			<td>{{($datedif<0)?'Expired':$datedif}}</td>
		</tr>
		<?php } ?>
		@endforeach
		</tbody>
	</tbody>
</table>