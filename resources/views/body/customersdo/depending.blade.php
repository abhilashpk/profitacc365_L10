<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>DE.#</th>
			<th>Ref.No.</th>
			<th>DE.Date</th>
			<th>Customer</th>
			<th class="text-right">Gross Amt.</th>
			<th class="text-right">VAT Amt.</th>
			<th class="text-right">Net Total</th>
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
			<td>{{ $row['voucher_no'] }}</td>
			<td>{{$row['reference_no']}}</td>
			<td>{{date('d-m-Y', strtotime($row['voucher_date']))}}</td>
			<td>{{$row['master_name']}}</td>
			<td class="text-right">{{number_format($row['total'],2)}}</td>
			<td class="text-right">{{number_format($row['vat_amount'],2)}}</td>
			<td class="text-right">{{number_format($row['net_total'],2)}}</td>
		</tr>
		<?php } ?>
		</tbody>
	</tbody>
</table>