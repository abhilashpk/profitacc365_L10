<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Customer</th>
			<th>Amount</th>
			<th>Cheque No</th>
			<th>Cheque Date</th>
			<th>Bank</th>
			<th>Days</th>
		</tr>
	</thead>
		<tbody>
		@foreach($pdcr as $pd)
		<?php if($pd->cheque_no!='' && $pd->code!='') {  ?>
		<tr>
			<td>{{ $pd->customer }}</td>
			<td>{{ number_format($pd->amount,2) }}</td>
			<td>{{ $pd->cheque_no }}</td>
			<td>{{ ($pd->cheque_date=='0000-00-00')?'':date('d-m-Y',strtotime($pd->cheque_date)) }}</td>
			<td>{{ $pd->code }}</td>
			<?php 
				$now = time();
				$your_date = strtotime($pd->cheque_date);
				$datediff = $your_date - $now;
				$datedif = round($datediff / (60 * 60 * 24));
			?>
			<td>{{($datedif<0)?'Over':$datedif}}</td>
		</tr>
		<?php } ?>
		@endforeach
		</tbody>
	</tbody>
</table>