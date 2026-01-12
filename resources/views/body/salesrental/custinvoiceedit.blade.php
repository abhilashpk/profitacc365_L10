<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tableRV2">
		<thead>
		<tr>
			<th>xInvoice Date</th>
			<th>Reference No</th>
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
			<td>{{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
			<td>{{ $row->reference_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->reference_no }}"></td>
			<td>
				<?php if(in_array($row->reference_no,$rvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" <?php if(in_array($row->reference_no,$rvref)) echo 'checked'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Cr"/></td>
			<?php 
				if($row->reference_no==$refno) {
					$amount = $row->net_total - $row->balance_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->net_total:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->reference_no,$rvref)) {
					if(count($rvarr[$row->reference_no]) > 0) {
						$amt = $rvarr[$row->reference_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->reference_no==$refno && $rvdata!='') echo 'value="'.$rvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="sales_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" id="billtype_{{$i}}" value="OB">
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->net_total,2) }}</td>

		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances); /*--}}
		
		@foreach($invoices as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}<?php //print_r($rvref);?></td>
			<td>{{ $row->voucher_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->voucher_no }}"></td>
			<td>
				<?php if(in_array($row->voucher_no,$rvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" <?php if(in_array($row->voucher_no,$rvref)) echo 'checked'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Cr"/></td>
			<?php 
				if($row->voucher_no==$refno) {
					$amount = $row->assign_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->net_total:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->voucher_no,$rvref)) {
					if(count($rvarr[$row->voucher_no]) > 0) {
						$amt = $rvarr[$row->voucher_no][0];
					}
				}
			?>
			<td>
				<input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->voucher_no==$refno && $rvdata!='') echo 'value="'.$rvdata->amount.'"'; ?> style="width:8em;">
				<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
				<input type="hidden" id="sinvoiceid_{{$i}}" name="sales_invoice_id[]" value="{{ $row->id }}">
				<input type="hidden" name="bill_type[]" value="SI" id="billtype_{{$i}}">
			</td>
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->net_total,2) }}</td>
			
		</tr>
		@endforeach
		
		{{--*/ $i = count($openbalances) + count($invoices); /*--}}
		@foreach($sinbills as $row)
		{{--*/ $i++; /*--}}
		<tr>
			<td>{{ date('d-m-Y', strtotime($row->voucher_date)) }}</td>
			<td>{{ $row->voucher_no }}<input type="hidden" id="refid_{{$i}}" name="refno[]" value="{{ $row->voucher_no }}"></td>
			<td>
				<?php if(($row->voucher_no != $refno) && in_array($row->voucher_no,$rvref)) { ?>
				<i class="fa fa-fw fa-check"></i>
				<?php } else { ?>
				<input type="checkbox" id="tag_{{$i}}" name="tag[]" class="tag-line" value="{{$row->id}}" <?php if(in_array($row->voucher_no,$rvref)) echo 'checked'; //else echo 'disabled'; ?>>
				<?php } ?>
			</td>
			<td>Cr<input type="hidden" id="actype_{{$i}}" name="acnttype[]" value="Cr"/></td>
			<?php 
				if($row->voucher_no==$refno) {
					$amount = $row->assign_amount;
					$balance = $row->balance_amount;
				} else {
					$amount = $balance = ($row->balance_amount==0)?$row->amount:$row->balance_amount;
					
				}
				
				$amt = '';
				if(in_array($row->voucher_no,$rvref)) {
					if(count($rvarr[$row->voucher_no]) > 0) {
						$amt = $rvarr[$row->voucher_no][0];
					}
				}
			?>
			<td><input type="number" id="lineamnt_{{$i}}" step="any" name="ln_amount[]" class="form-control" value="{{$amt}}" <?php //if($row->voucher_no==$refno && $rvdata!='') echo 'value="'.$rvdata->amount.'"'; ?> style="width:8em;"></td>
			<input type="hidden" id="hidamt_{{$i}}" name="actl_amount[]" value="{{ $amount }}">
			<input type="hidden" id="sinvoiceid_{{$i}}" name="sales_invoice_id[]" value="{{ $row->id }}">
			<input type="hidden" name="bill_type[]" value="SIN" id="billtype_{{$i}}">
			<td>{{ number_format($balance,2) }}</td>
			<td>{{ number_format($row->amount,2) }}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<button type="button" class="btn btn-primary add-invoice" data-dismiss="modal">Add</button>
</div>