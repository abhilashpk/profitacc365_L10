<div class="col-xs-15">
	<table class="table table-bordered table-hover" id="tableRV">
		<thead>
		<tr>
			<th></th>
			<th>Cons.No:</th>
			<th>Cons.Date</th>
			<th>Shipper</th>
			<th>Pack Qty</th>
			<th>Loaded Qty</th>
			<th>Loaded Pack Qty</th>
			<th>Col.Type</th>
			<th>Delivery Type</th>
			
		</tr>
		</thead>
		<tbody>
			@php $total = '' @endphp
			@if(!empty($jobs))
			@php $total = 0 @endphp
			@foreach($jobs as $row)
			<tr>
				<td><input type="checkbox" id="tag_{{$row->id}}" name="jobid[]" class="tag-line-nw clschk" value="{{$row->id}}" checked ></td>
				<td>{{$row->job_code }}</td>
				<td>{{ date('d-m-Y', strtotime($row->job_date)) }}</td>
				<td>{{$row->shipper_name}}</td>
				<td>{{$row->packing_qty}}
				<input type="hidden" id="received_qty_{{$row->id}}" value="{{$row->packing_qty}}">
				</td>
				<td> 
				<input type="number" id="loaded_qty_{{$row->id}}" name="loaded_qty[]" class="form-control loaded-qty" value="{{$row->received_qty-$row->despatched_qty}}" style="width:55%">
				<input type="hidden" id="amt_{{$row->id}}" value="{{(($row->received_qty-$row->despatched_qty)*$row->rate)+$row->coll_charge+$row->other_charge}}">
				<input type="hidden" id="rate_{{$row->id}}" value="{{$row->rate}}">
				<input type="hidden" id="coll_charge_{{$row->id}}" value="{{$row->coll_charge}}">
				<input type="hidden" id="other_charge_{{$row->id}}" value="{{$row->other_charge}}">
	            </td>
				<td> 
				<input type="number" id="loaded_pack_qty_{{$row->id}}" name="loaded_pack_qty[]" class="form-control loadedpack-qty" value="" style="width:55%">
                 </td>
				<td>{{$row->collection_type}}</td>
				<td>{{$row->delivery_type}}</td>
				<!-- <td>{{$row->total_charge}}</td>
				<td>{{$row->balance}}</td> -->
				@php $total += (($row->received_qty-$row->despatched_qty)*$row->rate)+$row->coll_charge+$row->other_charge @endphp
			</tr>
			@endforeach
			@else
			<tr><td colspan="10" align="center">No records were found!</td></tr>	
			@endif
		</tbody>
	</table>
</div>
<div class="form-group">
	<label for="input-text" class="col-sm-2 control-label">Total Amount</label>
	<div class="col-sm-4">
		<input type="number" class="form-control" id="total_amount" name="total_amount" autocomplete="off" readonly value="{{$total}}" required >
	</div>
</div>

