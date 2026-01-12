<table class="table horizontal_table table-striped" style="font-weight:bold;cell-spacing:10px;width:100%;">
<thead>
<tr>
	<th>Type</th><th>Vch.No</th><th>Date</th><th>Debit</th><th>Credit</th><th>Balance</th>
</tr>
</thead>
<tbody>
@php $balance = 0; @endphp
@foreach($result as $row)
	<tr>
		<td>{{$row->voucher_type}}</td>
		<td>{{$row->reference}}</td>
		<td>{{date('d-m-Y',strtotime($row->invoice_date))}}</td>
		<td>{{($row->transaction_type=='Dr')?$row->amount:''}}</td>
		<td>{{($row->transaction_type=='Cr')?$row->amount:''}}</td>
		@php $balance = ($row->amount > $balance)?($row->amount - $balance):($balance - $row->amount); @endphp
		<td>{{number_format($balance,2)}}</td>
	</tr>
@endforeach
</tbody>
</table>
@if($balance > 0)<a href="{{ url('customer_receipt/add/'.$id) }}" target="_blank" class="btn btn-info">Settle RV</a>@endif