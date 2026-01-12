<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>#</th><th>JV No.</th> <th>JV Date</th> <th>Amount</th> <th>Description</th>
		</tr>
	</thead>
	<tbody>@php $i=$total=$dif=0; end($adata); $lastKey = key($adata); @endphp
	@foreach($adata as $k => $row)
		@php $i++; $total += round($row->amount,2); $rowamt = $row->amount; @endphp
		@if($k==$lastKey)
    		@if($total > $amount)
    		   @php $dif = $total - $amount; $rowamt = $row->amount-$dif; @endphp
    		@elseif($total < $amount)
    		     @php $dif = $amount - $total; $rowamt = $row->amount+$dif; @endphp
    		@endif
		@endif
		<tr>
			<td>{{$i}}</td>
			<td>{{$row->jvno}} <input type="hidden" name="voucher_no[]" value="{{$row->jvno}}"></td>
			<td>{{date('d-m-Y',strtotime($row->date))}}<input type="hidden" name="voucher_date[]" value="{{date('d-m-Y',strtotime($row->date))}}"></td>
			<td>{{number_format($rowamt,2)}}<input type="hidden" name="line_amount[]" value="{{round($rowamt,2)}}"></td>
			<td>{{$row->desc}}<input type="hidden" name="description[]" value="{{$row->desc}}"></td>
		</tr>
	@endforeach
	</tbody>
</table>