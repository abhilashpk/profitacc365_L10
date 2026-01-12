	<div class="col-xs-12" id="vehicleInfo">
	     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#d6eef8">
											<div class="col-xs-2">
												<span class="small">Vehicle Name</span> <input type="text" id="vehicle_name" name="vehicle_name" value="{{$vinfo->name}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Chasis No</span> <input type="text" id="chasis_no" name="chasis_no" value="{{$vinfo->chasis_no}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Issue Plate</span> <input type="text" id="issue_plate" name="issue_plate" value="{{$vinfo->issue_plate}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Code Plate</span> <input type="text" id="code_plate" name="code_plate" value="{{$vinfo->code_plate}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Make</span> <input type="text" id="make" name="make" value="{{$vinfo->make}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Model</span> <input type="text" id="model" name="model" value="{{$vinfo->model}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Color Code</span> <input type="text" id="color" name="color" value="{{$vinfo->color}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Year</span> <input type="text" id="year" name="year" value="{{$vinfo->year}}" readonly class="form-control">
											</div>
											<div class="col-xs-2">
												<span class="small">Plate Type</span> <input type="text" id="plate_type" name="plate_type" value="{{$vinfo->plate_type}}" readonly class="form-control">
											</div>
									</div>
							</div>		
											
											
											
</div>
									
<div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="si">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
	<thead>
		<tr>
			<th>Job Code</th>
			<th>Job Name</th>
			<th>Customer Name</th>
			<th>Cost</th>
			<th>Income</th>
			<th>Profit</th>
			<th></th>
			
		</tr>
	</thead>
		@if(!empty($results))
	
			@foreach($results as $row)
			<tr>
				<td>{{$row->code }}</td>
				<td>{{ $row->name }}</td>
				<td>{{$row->master_name }}</td>
				<td>{{number_format(($jobdata[$row->job_id])?$jobdata[$row->job_id]->cost:0,2)}}</td>
				<td>{{number_format(($jobdata[$row->job_id])?$jobdata[$row->job_id]->income:0,2)}}</td>
				<td>{{number_format(($jobdata[$row->job_id])?$jobdata[$row->job_id]->profit:0,2)}}</td>
				<td>
					<p>
						<button type="button" class="btn btn-primary btn-job-details" data-id="{{$row->job_id}}" >Job Details</button>
					</p>
				</td>
			</tr>
			@endforeach
			
		@else
			<tr><td colspan="11" align="center">No reports were found!</td></tr>
		@endif
	<tbody>
	</tbody>
</table>
</div>
</div>
</div>
</div>

<section class="content">
            <div class="row">
				<div class="col-lg-12">
				   <div class="panel">
							<div class="panel-body" id="jobReport">
							</div>
						</div>
				</div>
			</div>
</section>

<script>
$(document).on('click', '.btn-job-details', function(e)  { 
    var res = $(this).attr('data-id');
    console.log(res);
    $('#jobReport').load("{{ url('job_report/job_details/') }}/"+res);
});
</script>