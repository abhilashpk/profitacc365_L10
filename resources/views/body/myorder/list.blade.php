@foreach($orders as $row)
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-align-justify"></i> Order No: {{$row->voucher_no}}
			</h3>
			<span class="pull-right">
				   
				</span>
		</div>
		<div class="panel-body">
			<div class="box-body">
				<dl class="dl-vertical">
					
					<dd>
					   Customer Name: <b>{{$row->master_name}}</b>
					</dd>
					@php $delivery .= ($row->less_description!='')?'<br/>Location: '.$row->less_description:''; @endphp
					<dd>
					   Delivery details: <b>{{$row->remarks}}</b>
					</dd>
						
					<dd><br/>
						Status: <select id="sts_{{$row->id}}" class="form-control select2 ordStatus" name="status">
									<option value="">Select</option>
									<option value="1">Delivered</option>
								</select>
					</dd>
					
				</dl>
			</div>
		</div>
	</div>
@endforeach	
<script>
$(document).ready(function () { 
	$('.ordResn').hide();
});
</script>