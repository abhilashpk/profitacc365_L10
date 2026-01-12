<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
        <th>Customer</th>
			<th>Vehicle No</th>
			<th>Vehicle Name</th>
			<th>Next Due</th>
		
			
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
			<td>{{$row->customer}}</td>
			<td>{{$row->reg_no}}</td>
			<td>{{$row->vehicle}}</td>
			<td>{{date('d-m-Y', strtotime($row->expiry_date))}}</td>
			<?php 
           // echo '<pre>';print_r($row);exit;
				$now = time();
				$your_date = strtotime($row->expiry_date);
				$datediff = $your_date - $now;
				$datedif = round($datediff / (60 * 60 * 24));
			?>
            
			<!-- <td> {{($datedif<0)?'Over':$datedif}}</td> -->
		</tr>
		<?php } ?>
		</tbody>
	</tbody>
</table>