<?php if($num) { ?>
<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tablePV2">
		<thead>
		<tr>
			<th>Voucher No</th>
			<th>Reference No</th>
			<th>Invoice Date</th>
			<th>Tag</th>
			<th>Type</th>
			<th>Assign Amount</th>
			<th>Balance</th>
			<th>Invoice Amount</th>
		</tr>
		</thead>
		<tbody>
		{{--*/ $i = 0; /*--}}
		@foreach($openbalances as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->reference_no }}<input type="hidden" id="vhr_{{$i}}" name="vchrno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>
				<?php if(($row->reference_no != $refno) && in_array($row->reference_no,$pvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { if($row->tr_type=='Cr') { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" onclick="getTag(this)" <?php if(in_array($row->reference_no,$pvref)) echo 'checked'; ?>>
				<?php } } ?>
			</td>
			<td>{{$row->tr_type}}<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Dr"/></td>
			<?php 
				//$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
				if($row->reference_no==$refno) {
					$amount = $row->net_amount - $row->balance_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->reference_no,$pvref)) {
					if(count($pvarr[$row->reference_no]) > 0) {
						$amt = $pvarr[$row->reference_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->reference_no==$refno && $pvdata!='') echo 'value="'.$pvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" id="billtype_{{$i}}" value="OB">
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>
		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances); /*--}}
		@foreach($invoices as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" id="vhr_{{$i}}" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>
				<?php if(($row->voucher_no != $refno) && in_array($row->voucher_no,$pvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" onclick="getTag(this)" <?php if(in_array($row->voucher_no,$pvref)) echo 'checked'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Dr"/></td>
			<?php 
				//$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
				if($row->voucher_no==$refno) {
					$amount = $row->net_amount - $row->balance_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->voucher_no,$pvref)) {
					if(count($pvarr[$row->voucher_no]) > 0) {
						$amt = $pvarr[$row->voucher_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->voucher_no==$refno && $pvdata!='') echo 'value="'.$pvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="PI" id="billtype_{{$i}}">
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>
		</tr>
		@endforeach
		
		
		{{--*/ $i = count($openbalances) + count($invoices); /*--}}
		@foreach($pinbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" id="vhr_{{$i}}" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>
				<?php if(($row->voucher_no != $refno) && in_array($row->voucher_no,$pvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" onclick="getTag(this)" <?php if(in_array($row->voucher_no,$pvref)) echo 'checked'; //else echo 'disabled'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Dr"/></td>
			
			<?php 
				if($row->voucher_no==$refno) {
					$amount = $row->assign_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->voucher_no,$pvref)) {
					if(count($pvarr[$row->voucher_no]) > 0) {
						$amt = $pvarr[$row->voucher_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->voucher_no==$refno && $pvdata!='') echo 'value="'.$pvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="PIN" id="billtype_{{$i}}">
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->amount,2) }}</td>
		</tr>
		
		@endforeach
		
		
		{{--*/ $i = count($openbalances) + count($invoices) + count($pinbills) + count($ocbills); /*--}}
		@foreach($ocbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" id="vhr_{{$i}}" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->oc_reference }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->oc_reference }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>
				<?php if(($row->voucher_no != $refno) && in_array($row->voucher_no,$pvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" onclick="getTag(this)" <?php if(in_array($row->voucher_no,$pvref)) echo 'checked'; //else echo 'disabled'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Dr"/></td>
			
			<?php 
				if($row->voucher_no==$refno) {
					$amount = $row->assign_amount;
					$balance = $row->balance_amount;
				} else {
					//$amount = $balance = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
					$amount = ($row->balance_amount==0)?($row->oc_amount+$row->oc_vatamt):$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->voucher_no,$pvref)) {
					if(count($pvarr[$row->voucher_no]) > 0) {
						$amt = $pvarr[$row->voucher_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->voucher_no==$refno && $pvdata!='') echo 'value="'.$pvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OC">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format(($row->oc_amount+$row->oc_vatamt),2)}}</td>
		</tr>
		
		@endforeach
		
		</tbody>
	</table>
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<button type="button" class="btn btn-primary add-invoice" data-dismiss="modal">Add</button>
</div>
<?php } else { ?>
<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tablePV">
		<thead>
		<tr>
			<th>Voucher No</th>
			<th>Reference No</th>
			<th>Invoice Date</th>
			<th>Tag</th>
			<th>Type</th>
			<th>Assign Amount</th>
			<th>Balance</th>
			<th>Invoice Amount</th>
		</tr>
		</thead>
		<tbody>
		{{--*/ $i = 0; /*--}}
		@foreach($openbalances as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->reference_no }}<input type="hidden" name="vchrno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td><?php if($row->tr_type=='Cr') {?><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"><?php } ?></td>
			<td>{{$row->tr_type}}</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
			?>
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OB">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>

		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances); /*--}}
		@foreach($invoices as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Cr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->net_amount:$row->balance_amount;
			?>
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="PI">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->net_amount,2) }}</td>

		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances) + count($invoices); /*--}}
		@foreach($pinbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Cr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
			?>
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->journal_id }}">
			<input type="hidden" name="bill_type[]" value="PIN">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format($row->amount,2) }}</td>

		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances) + count($invoices) + count($pinbills); /*--}}
		@foreach($ocbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->voucher_no }}<input type="hidden" name="vchrno[]" value="{{ $row->voucher_no }}"></td>
			<td>{{ $row->oc_reference }}<input type="hidden" name="refno[]" value="{{ $row->oc_reference }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Cr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?($row->oc_amount+$row->oc_vatamt):$row->balance_amount;
			?>
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OC">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ number_format(($row->oc_amount+$row->oc_vatamt),2) }}</td>

		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances) + count($invoices) + count($pinbills) + count($ocbills); /*--}}
		@foreach($otbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ $row->reference_no }}<input type="hidden" name="vchrno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ $row->reference_no }}<input type="hidden" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td><input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$i-1}}" onclick="getTag(this)"></td>
			<td>Cr</td>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="line_amount[]" class="form-control line-amount" style="width:8em;"></td>
			<?php 
				$amount = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
			?>
			<input type="hidden" id="hidamt_{{$i}}" name="actual_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="purchase_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="OT">
			<td>{{ number_format($amount,2) }}</td>
			<td>{{ $row->amount }}</td>

		</tr>
		@endforeach
		
		</tbody>
	</table>
</div>
<?php } ?>
<script>
$(document).ready(function () {
$(function() {
            
	/* var dtInstance = $("#tablePV").DataTable({
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
	
	var dtInstance = $("#tablePV2").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,null,null,null,null],
		"aaSorting": [],
		//"scrollX": true,
		"scrollY":        500,
		"deferRender":    true,
		"scroller":       true,
	}); */
	
	});
});
</script>