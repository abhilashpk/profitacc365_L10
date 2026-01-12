<div class="col-xs-15">
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
		    <th>Voucher Type</th>
		    <th>Voucher No</th>
			<th>Voucher Date</th>
			<th>Reference No</th>
			<th>Tag</th>
			<th>Type</th>
			<th>Assign Amount</th>
			<th>Balance</th>
		</tr>
		</thead>
		<tbody>
		{{--*/ $i = 0; /*--}}
		@foreach($invoices as $row)
		{{--*/ $i++; /*--}}
		<tr>
		    <td>{{$row->voucher_type}}</td>
		    <td>{{$row->voucher_no}}</td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>{{($row->tr_type=='Dr')?'Cr':'Dr'}}</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" value="{{($row->is_edit=='E')?$row->asgn_amount:'' }}" class="form-control line-amount" data-type="Dr" style="width:8em;"></td>
			<input type="hidden" name="tr_type[]"  value="{{($row->tr_type=='Dr')?'Cr':'Dr'}}">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{($row->is_edit=='E')?$row->asgn_amount:$row->balance_amount}}">
			
			@if($type=='CUSTOMER')
			<input type="hidden" id="sinvoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$row->voucher_type_id}}">
			<input type="hidden" name="bill_type[]" value="SI">
			@else
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->voucher_type_id }}">
			<input type="hidden" name="bill_type[]" value="PI">
			@endif
            <td>{{ number_format($row->balance_amount,2) }}</td>
			<input type="hidden" name="type[]">
			<input type="hidden" name="doc_id[]" value="{{$row->voucher_type_id}}">
		</tr>
		@endforeach
		</tbody>
	</table>
	
	<h4>Opening Balance</h4>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
		    <th>Voucher Type</th>
		    <th>Voucher No</th>
			<th>Voucher Date</th>
			<th>Reference No</th>
			<th>Tag</th>
			<th>Type</th>
			<th>Assign Amount</th>
			<th>Balance</th>
		</tr>
		</thead>
		<tbody>
        {{--*/ $i = count($invoices); /*--}}
		@foreach($obbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
		    <td>{{$row->voucher_type}}</td>
		    <td>{{$row->voucher_no}}</td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tagadv[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>{{($row->tr_type=='Dr')?'Cr':'Dr'}}</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" readonly value="{{($row->is_edit=='E')?$row->asgn_amount:'' }}" class="form-control line-amount" data-type="Cr" style="width:8em;"></td>
			<input type="hidden" name="tr_type[]"  value="{{($row->tr_type=='Dr')?'Cr':'Dr'}}">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{($row->is_edit=='E')?$row->asgn_amount:$row->balance_amount}}">
			
			<input type="hidden" id="sinvoiceid_{{$i}}" name="receipt_voucher_entry_id[]" value="{{$row->voucher_type_id}}">
			<input type="hidden" name="bill_type[]" value="OB">
			
            <td>{{ number_format($row->balance_amount,2) }}</td>
			<input type="hidden" name="type[]" value="{{$row->voucher_type}}">
			<input type="hidden" name="doc_id[]" value="{{$row->voucher_type_id}}">
		</tr>
		@endforeach
		</tbody>
	</table>
</div>
