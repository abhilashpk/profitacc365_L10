@php $i = 0; @endphp
@foreach($orders as $row)
<input type="hidden" name="id[]" value="{{$row->id}}">
<input type="hidden" name="type[]" value="{{$type}}">
<div class="table-responsive">
	<b>Technician: {{$row->salesman}}</b> 
	<table border="0" class="table table-bordered table-striped m-t-10">
		<tr>
			<td><b>Job No:</b></td><td align="left"><b>{{$row->voucher_no}}</b></td>
			<td><b>Vehicle No:</b></td><td align="left"><b>{{$row->reg_no}}</b></td>
		</tr>
		<tr>
			<td colspan="1"><b>Job Description:</b></td><td align="left" colspan="3">{{$row->description}}</td>
		</tr>
		@if($type=='Assigned')
		<tr>
			<td style="width:15%;"><b>Start Date Time:</b></td><td align="left" style="width:20%;"><input id="timepick_1" name="datetime[]" autocomplete="off" class="form-control pull-right timepick" data-language='en' data-timepicker="true" data-time-format='hh:ii aa'/></td>
			<td><button type="submit" value="{{$i}}" name="submit[]" class="btn btn-primary">Save</button></td>
			<td>@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
		</tr>
		@elseif($type=='Working')
		<tr>
			<td style="width:15%;"><b>Start Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}</b></td>
			<td><b>End Date Time:</b></td><td><input id="timepick_1" style="width:35%;margin-right:10px;" name="datetime[]" autocomplete="off" class="form-control pull-left timepick" data-language='en' data-timepicker="true" data-time-format='hh:ii aa'/> <button type="submit" value="{{$i}}" name="submit[]" class="btn btn-primary">Save</button></td>
		</tr>
		@elseif($type=='Completed')
		@php
			$datetime1 = new DateTime($row->start_time);
			$datetime2 = new DateTime($row->end_time);
			$interval = $datetime1->diff($datetime2); 
			$format = ''; 
			if($interval->days > 0)
				$format .= $interval->days.' Days ';
			if($interval->h > 0)
				$format .= $interval->h.' Hours ';
			if($interval->i > 0)
				$format .= $interval->i.' Minutes ';
			
		@endphp
		<tr>
			<input type="hidden" name="status[]" value="1">
			<td style="width:15%;"><b>Start Date Time:<br/>End Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}<br/>{{$row->end_time}}</b></td>
			<td><b>Time Taken:</b></td><td><b>{{$format}}</b>  <button type="submit" value="{{$i}}" name="submit[]" style="margin-left:20px;" class="btn btn-primary">Approve</button>
			@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
		</tr>
		@elseif($type=='Approved')
		@php
			$datetime1 = new DateTime($row->start_time);
			$datetime2 = new DateTime($row->end_time);
			$interval = $datetime1->diff($datetime2); 
			$format = '';
			if($interval->days > 0)
				$format .= $interval->days.' Days ';
			if($interval->h > 0)
				$format .= $interval->h.' Hours ';
			if($interval->i > 0)
				$format .= $interval->i.' Minutes ';
			
		@endphp
		<tr>
			<input type="hidden" name="status[]" value="0">
			<td style="width:15%;"><b>Start Date Time:<br/>End Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}<br/>{{$row->start_time}}</b></td>
			<td><b>Time Taken:</b></td><td><b>{{$format}}</b>  <button type="submit" value="{{$i}}" name="submit[]" style="margin-left:20px;" class="btn btn-primary">Undo Approve</button>
			@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
		</tr>
		@endif
	</table>
	<div style="float:left; padding-right:5px;">
		<button type="button" id="CK_{{$i}}" class="btn btn-primary btn-xs ORVD">View Description</button>
	</div>
	<div style="float:left; padding-right:5px;">
		<button type="button" id="VH_{{$i}}" class="btn btn-primary btn-xs VEH">Vehicle Detail</button>
	</div>
	<div style="float:left; padding-right:5px;">
		<button type="button" id="IM_{{$i}}" class="btn btn-primary btn-xs IMG">View Images</button>
	</div>
	
	<div class="form-group viewDc" id="viewDesc_{{$i}}">
		<div class="col-sm-8" style="margin-left:10px;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th><th>Item Description</th> <th>Quantity</th>
				</tr>
			</thead>
			<tbody>@php $j=0; $items = isset($orditems[$row->id])?$orditems[$row->id]:[]; @endphp
			@foreach($items as $item) @php $j++; @endphp
			<tr>
				<td>{{$j}}</td>
				<td>{{$item->description}}</td>
				<td>{{$item->quantity}}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		</div>
	</div>
	
	<div class="form-group viewVH" id="viewVeh_{{$i}}">
		<div class="col-sm-8" style="margin-left:10px;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Reg. No</th><th>Issue Plate</th><th>Code Plate</th><th>Make</th><th>Model</th>
				</tr>
			</thead>
			<tbody>@php $veh = isset($vehicles[$row->id])?$vehicles[$row->id]:[]; @endphp
			@foreach($veh as $vh)
			<tr>
				<td>{{$vh->reg_no}}</td>
				<td>{{$vh->issue_plate}}</td>
				<td>{{$vh->code_plate}}</td>
				<td>{{$vh->make}}</td>
				<td>{{$vh->model}}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		</div>
	</div>
	
	<div class="form-group viewIM" id="viewImg_{{$i}}">
		<div class="col-sm-8" style="margin-left:10px;">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Image</th><th>Description</th>
				</tr>
			</thead>
			<tbody>@php $image = isset($images[$row->id])?$images[$row->id]:[]; @endphp
			@foreach($image as $img)
			<tr>
				<td><img src="{{URL::asset('uploads/joborder/'.$img->photo)}}" style="max-size:200px;" /></td>
				<td>{{$img->description}}</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		</div>
	</div>
	
	<br/>
	<hr/>
<br/><br/>
@php $i++; @endphp
@endforeach

@if(count($orders)==0)
<div class="alert alert-warning">
	<p>No jobs were found!</p>
</div>
@endif
</div>
<script>
$('.timepick').datepicker( { dateFormat: 'dd-mm-yyyy' } ); //, autoClose: true
$('.viewDc').hide();
$('.viewVH').hide();
$('.viewIM').hide();
</script>