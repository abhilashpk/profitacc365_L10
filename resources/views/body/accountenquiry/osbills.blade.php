<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tableRV">
		<thead>
		<button type="button" class="btn btn-primary add-invoice" data-dismiss="modal" style="margin-bottom:15px;">Add</button>
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
		@php $i = 0; @endphp
		@foreach($osbills as $row)
		@php $i++; @endphp
		@php $read = $dis = ''; $txt = $isadv = ''; @endphp
		
		@if($type=='CUSTOMER' && $row->tr_type=='Cr')
			@php
		        $read = 'readonly'; $dis = 'disabled';
		        $txt = 'Advance set off.'; $isadv = 1;
		    @endphp
		@elseif($type=='SUPPLIER' && $row->tr_type=='Dr')
			@php
		        $read = 'readonly'; $dis = 'disabled';
		        $txt = 'Advance set off.'; $isadv = 1;
		    @endphp
		@endif
		
		<tr>
		    <td>{{$row->voucher_type}}</td>
		    <td>{{$row->voucher_no}}</td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" id="refid_{{$i}}" value="{{ $row->reference_no }}"></td>
			<td>@if($row->is_edit=='E')
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" data-adv="{{$isadv}}" onclick="getTag(this)">
				@else
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line-nw" value="{{$i-1}}" data-adv="{{$isadv}}" onclick="getTag(this)">
				@endif
			</td>
			<td>{{($row->tr_type=='Dr')?'Cr':'Dr'}}<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="{{$row->tr_type}}"/>
			<input type="hidden" id="trtype_{{$i}}" value="{{($row->tr_type=='Dr')?'Cr':'Dr'}}"/></td>
			<td><input type="text" id="lineamnt_{{$i}}" step="any" name="line_amount[]" placeholder="{{$txt}}" max="100" value="{{($row->is_edit=='E')?$row->asgn_amount:'' }}" class="form-control line-amount" {{$read}} style="width:8em;">
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{($row->is_edit=='E')?$row->asgn_amount:$row->balance_amount}}">
			@if($type=='CUSTOMER')
			<input type="hidden" id="sinvoiceid_{{$i}}" name="sales_invoice_id[]" value="{{$row->voucher_type_id}}">
			@else
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->voucher_type_id }}">
			@endif
			<input type="hidden" name="bill_type[]" id="billtype_{{$i}}" value="{{$row->voucher_type}}"> 
			</td>
			<td>{{ number_format($row->balance_amount,2) }}</td>
			
		</tr>
		
		@endforeach
		</tbody>
	</table>
	<input type="hidden" name="num" id="bnum" value="{{$no}}">
</div>

<script>
$(document).ready(function () {
	$(function() {
		var dtInstance = $("#tableRV0").DataTable({
			"lengthMenu": [10, 25, 50, "ALL"],
			bLengthChange: false,
			mark: true,
			"aoColumns": [null,null,null,null,null,null,null,null],
			"aaSorting": [],
			//"scrollX": true,
			"scrollY":        500,
			"deferRender":    true,
			"scroller":       true,
		});
	
	});
});
</script>