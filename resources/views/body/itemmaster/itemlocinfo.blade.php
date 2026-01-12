<br/>
<?php $num = $num - 1;?>
<div class="col-xs-4">
    @if(sizeof($itemunits) > 1 && isset($itemunits[1]))
		{{--*/ $packing = $itemunits[1]->packing;
			   $base = $itemunits[0]->packing;
			   $sub = $itemunits[1]->unit_name;
			   $sub = ' '.$sub.' =';
			   $pkno = $itemunits[1]->pkno;
		/*--}}
	@else
		{{--*/ $pkno = $packing = $item_unit_id = $base = $sub = ''; /*--}}
	@endif
	
	@if(sizeof($itemunits) > 2)
		{{--*/ $packing2 = $itemunits[2]->packing;
			   $pkno2 = $itemunits[2]->pkno;
			   $sub2 = $itemunits[2]->unit_name;
		/*--}}
	@else
		{{--*/ $packing2 = $sub2 = ''; /*--}}
	@endif

	<div>** @if($munits[0]->active==1){{$pkno}} {{$sub}} {{ $packing }} {{$base}},@endif @if($munits[1]->active==1){{$pkno2}} {{$sub2}} = {{ $packing2 }} {{$base}}@endif</div>									
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Location</th>
			<th>Stock</th>
			<th>Quantity</th>
			<th>Bin1</th>
		</tr>
		</thead>
		<tbody>
		@foreach($info as $row)
		<tr>
			<td>{{ $row->name }}</td>
			<td>{{ $row->quantity }}</td>
			<td class="num"><input type="number" name="locqty[{{$num}}][]" class="loc-qty-{{$num+1}}" data-id="{{$num+1}}" data-qty="{{$row->quantity}}" value="{{isset($row->qty_entry)?$row->qty_entry:''}}">
			<input type="hidden" class="loc-id" name="locid[{{$num}}][]" value="{{$row->id}}"/>
			<input type="hidden" class="loc-bin" name="locbn[{{$num}}][]" value="{{$row->bin}}"/>
			<input type="hidden" class="loc-nam" name="locnm[{{$num}}][]" value="{{$row->code}}"/>
			</td>
			<td>{{$row->bin}}</td>
		</tr>
		@endforeach
		</tbody>
	</table>
</div>