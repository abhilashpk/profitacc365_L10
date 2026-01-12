<button type="button" class="btn btn-primary add-invoice" data-dismiss="modal">Add</button><br/>
@if($type=='edit')
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th></th><th>Account Name</th> <th>Amount</th><th>Assign Amount</th>
		</tr>
	</thead>
	<tbody>@php $i=$orvtotal=0; @endphp
	@foreach($payacnts as $k => $row)
		@if($k>1)
			@if( $row->amount > 0 && (in_array($row->account_id,$orv)) ) 
				@php $amount = $row->amount - $pdar[$row->account_id]; $chked = true; @endphp
				@if($rid!=$rvarr[$row->account_id])
					@php $row->amount = 0; @endphp
				@endif
			@else
				@php $amount = $row->amount; $chked = false; @endphp
			@endif
			
			@if($row->amount > 0)
			@php $i++; $orvtotal += $row->amount; @endphp
			<tr>
				<td><input type="checkbox" id="tag_{{$i}}" {{($chked)? 'checked' : ''}} name="tag[]" class="tag-line" value="{{$i}}" onclick="getTag(this)"></td>
				<td>{{$oracarr[$k].' - '.$row->acname1}} <input type="hidden" id="details_{{$i}}" value="{{$oracarr[$k].'/'.$row->acname1}}"></td>
				<td>{{number_format($row->amount,2)}}<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{$row->amount}}">
					<input type="hidden" name="expacid[]" id="epacid_{{$i}}" value="{{$row->account_id}}">
					<input type="hidden" name="fc[]" id="fc_{{$i}}" value="0"></td>
				<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" max="100" value="{{($chked)? (($row->amount!=$amount)?($row->amount - $amount):'') : ''}}" class="form-control line-amount-orv" style="width:8em;"></td>
			</tr>
			@endif
		@endif
	@endforeach
	@foreach($payacnts as $k => $row)
		@if($row->tax_amount > 0 && (in_array($row->account_id,$txrv)))
			@php $tamount = $row->tax_amount - $txpdar[$row->account_id]; $chked = true; @endphp
			@if($rid!=$txrvarr[$row->account_id])
				@php $row->tax_amount = 0; @endphp
			@endif
		@else
			@php $tamount = $row->tax_amount; $chked = false; @endphp
		@endif
		
		@if($row->tax_amount > 0)
		@php $i++; $orvtotal += $tamount; @endphp
		<tr>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" {{($chked)? 'checked' : ''}} class="tag-line" value="{{$i}}" onclick="getTag(this)"></td>
			<td>{{$oractxarr[$k].' - '.$row->acname1}} <input type="hidden" id="details_{{$i}}" value="{{$oractxarr[$k].'/'.$row->acname1}}"></td>
			<td>{{number_format($row->tax_amount,2)}}<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{$row->tax_amount}}">
				<input type="hidden" name="expacid[]" id="epacid_{{$i}}" value="{{$row->account_id}}">
				<input type="hidden" name="fc[]" id="fc_{{$i}}" value="1"></td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" value="{{($chked)? (($row->tax_amount!=$tamount)?($row->tax_amount - $tamount):$row->tax_amount) : ''}}" max="100" class="form-control line-amount-orv" style="width:8em;"></td>
		</tr>
		@endif
		
	@endforeach
	<tr><td colspan="2" align="right"><b>Total</b></td><td><b>{{number_format($orvtotal,2)}}</b></td><td></td></tr>
	</tbody>
</table>
@else
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th></th><th>Account Name</th> <th>Amount</th><th>Assign Amount</th>
		</tr>
	</thead>
	<tbody>@php $i=$orvtotal=0; @endphp
	@foreach($payacnts as $k => $row)
		@if($k>1)
			@if( $row->amount > 0 && (in_array($row->account_id,$orv)) ) 
				@php $amount = $row->amount - $pdar[$row->account_id]; @endphp
			@else
				@php $amount = $row->amount; @endphp
			@endif
			
			@if($amount > 0)
			@php $i++; $orvtotal += $row->amount; @endphp
			<tr>
				<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i}}" onclick="getTag(this)"></td>
				<td>{{$oracarr[$k].' - '.$row->acname1}} <input type="hidden" id="details_{{$i}}" value="{{$oracarr[$k].'/'.$row->acname1}}"></td>
				<td>{{number_format($amount,2)}}<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{$amount}}">
					<input type="hidden" name="expacid[]" id="epacid_{{$i}}" value="{{$row->account_id}}">
					<input type="hidden" name="fc[]" id="fc_{{$i}}" value="0"></td>
				<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" max="100" class="form-control line-amount-orv" style="width:8em;"></td>
			</tr>
			@endif
		@endif
	@endforeach
	@foreach($payacnts as $k => $row)
		@if($row->tax_amount > 0 && (in_array($row->account_id,$txrv)))
			@php $amount = $row->tax_amount - $txpdar[$row->account_id]; @endphp
		@else
			@php $amount = $row->tax_amount; @endphp
		@endif
		
		@if($amount > 0)
		@php $i++; $orvtotal += $amount; @endphp
		<tr>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i}}" onclick="getTag(this)"></td>
			<td>{{$oractxarr[$k].' - '.$row->acname1}} <input type="hidden" id="details_{{$i}}" value="{{$oractxarr[$k].'/'.$row->acname1}}"></td>
			<td>{{number_format($amount,2)}}<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{$amount}}">
				<input type="hidden" name="expacid[]" id="epacid_{{$i}}" value="{{$row->account_id}}">
				<input type="hidden" name="fc[]" id="fc_{{$i}}" value="1"></td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" max="100" class="form-control line-amount-orv" style="width:8em;"></td>
		</tr>
		@endif
		
	@endforeach
	<tr><td colspan="2" align="right"><b>Total</b></td><td><b>{{number_format($orvtotal,2)}}</b></td><td></td></tr>
	</tbody>
</table>
@endif