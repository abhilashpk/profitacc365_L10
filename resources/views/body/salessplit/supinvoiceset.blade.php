<div class="col-xs-15">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Invoice Date</th>
			<th>Reference No</th>
			<th>Tag</th>
			<th>Type</th>
			<th>Assign Amount</th>
			<th>Balance</th>
			<th>Invoice Amount</th>
			<th>FC Amount</th>
			<th>FC</th>
			<th>FC Rate</th>
			<th>Description</th>
		</tr>
		</thead>
		<tbody>
		{{--*/ $i = 0; /*--}}
		@foreach($invoices as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>{{ $row->voucher_no }}<input type="hidden" name="refno[]" value="{{ $row->voucher_no }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Cr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
			?>
			<input type="hidden" name="tr_type[]" value="Dr">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="PI">
			<input type="hidden" name="type[]">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>
			<td>{{ number_format($row->net_amount_fc,2) }}</td>
			<td>{{ $fc=($row->is_fc==1)?'Yes':'No' }}</td>
			<td>{{ number_format($row->currency_rate,2) }}</td>
			<td>{{ $row->description }}</td>
		</tr>
		@endforeach
		
		{{--*/ $i = count($invoices); /*--}}
		@foreach($otbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="{{($row->tr_type=='Dr')?'tagadv':'tag'}}[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>{{$row->tr_type}}</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="{{($row->tr_type=='Dr')?'advance_amount':'line_amount'}}[]" class="form-control line-amount" data-type="{{$row->tr_type}}" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
			?>
			<input type="hidden" name="tr_type[]" value="{{($row->tr_type=='Dr')?'Cr':'Dr'}}">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="pinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OT">
			<input type="hidden" name="type[]">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ $row->amount }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
		
		{{--*/ $i = count($invoices) + count($otbills); /*--}}
		@foreach($openbalances as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="{{($row->tr_type=='Dr')?'tagadv':'tag'}}[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>{{$row->tr_type}}</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="{{($row->tr_type=='Dr')?'advance_amount':'line_amount'}}[]" class="form-control line-amount" data-type="{{$row->tr_type}}" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
			?>
			<input type="hidden" name="tr_type[]" value="{{($row->tr_type=='Dr')?'Cr':'Dr'}}">
			<input type="hidden" id="hidamt_{{$i}}" name="{{($row->tr_type=='Dr')?'actual_amountadv':'actual_amount'}}[]" value="{{ $amount }}">
			<input type="hidden" id="pinvoiceid_{{$i}}" name="{{($row->tr_type=='Dr')?'payment_voucher_entry_id':'purchase_invoice_id'}}[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OB">
			<input type="hidden" name="type[]">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances) + count($invoices) + count($otbills); /*--}}
		@foreach($advance as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>On Adv.<input type="hidden" name="refno[]" value="{{ $row->reference }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tagadv[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Dr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="advance_amount[]" class="form-control line-amount" data-type="Dr" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->net_total:$row->balance_amount;
			?>
			<input type="hidden" name="tr_type[]" value="Cr">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amountadv[]" value="{{ $amount }}">
			<input type="hidden" id="pinvoiceid_{{$i}}" name="payment_voucher_entry_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OnAc">
			<input type="hidden" name="type[]" value="{{$row->type}}">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->net_total,2) }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
