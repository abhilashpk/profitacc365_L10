					<div class="panel panel-success filterable" id="newVehicleList">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Vehicle List
                            </h3>
                        </div>
						
                        <div class="panel-body">
                            <div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createCust">Create Vehicle</button>
                                <table class="table horizontal_table table-striped" id="tableVehList">
                                    <thead>
                                    <tr>
                                        <th>Reg. No.</th>
                                        <th>Vehicle Name</th>
										<th>Issue Plate</th>
										<th>Code Plate</th>
                                        <th>Make</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($vehicles as $vehicle)
                                    <tr>
                                        <td><a href="" class="vclRow" data-id="{{$vehicle->id}}" data-name="{{$vehicle->name}}" data-custid="{{$vehicle->cust_id}}" data-custname="{{$vehicle->master_name}}" data-regno="{{$vehicle->reg_no}}" data-issue="{{$vehicle->issue_plate}}" data-code="{{$vehicle->code_plate}}" data-chasis="{{$vehicle->chasis_no}}" data-make="{{$vehicle->make}}" data-model="{{$vehicle->model}}" data-dismiss="modal">{{$vehicle->reg_no}}</a></td>
                                        <td><a href="" class="vclRow" data-id="{{$vehicle->id}}" data-name="{{$vehicle->name}}" data-custid="{{$vehicle->cust_id}}" data-custname="{{$vehicle->master_name}}" data-regno="{{$vehicle->reg_no}}" data-issue="{{$vehicle->issue_plate}}" data-code="{{$vehicle->code_plate}}" data-chasis="{{$vehicle->chasis_no}}" data-make="{{$vehicle->make}}" data-model="{{$vehicle->model}}" data-dismiss="modal">{{$vehicle->name}}</a></td>
                                        <td>{{ $vehicle->issue_plate }}</td>
										<td>{{ $vehicle->code_plate }}</td>
										<td>{{ $vehicle->make }}</td>
                                    </tr>
                                   @endforeach
                                    </tbody>
                                </table>

                            </div>
							
                        </div>
                    </div>
					
					<div class="panel panel-success filterable" id="newVehicleFrm">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> New Vehicle
                            </h3>
							
                        </div>
						<div class="panel-body">  <button type="button" class="btn btn-primary listCust">List Vehicle</button>
						
							<div class="col-xs-10">
								<div id="vehicleDtls">
								<form class="form-horizontal" role="form" method="POST" name="frmVehicle" id="frmVehicle">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
									<hr/>
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Reg. No.</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="reg_no" name="reg_no" autocomplete="off" placeholder="Reg. No.">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Issue Plate</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="issue_plate" name="issue_plate" autocomplete="true"  placeholder="Issue Plate">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Code Plate</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="code_plate" name="code_plate" autocomplete="true" placeholder="Code Plate">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Color Code</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="color_code" name="color_code" autocomplete="true" placeholder="Color Code">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Vehicle Name</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" autocomplete="off" placeholder="Vehicle Name">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Make</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="make" name="make" autocomplete="off" placeholder="Make">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Model</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="model" name="model" autocomplete="off" placeholder="Model">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Type</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="plate_type" name="plate_type" autocomplete="off" placeholder="Type">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Engine No.</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="engine_no" name="engine_no" autocomplete="off" placeholder="Engine No.">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Chasis No.</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="chasis_no" name="chasis_no" autocomplete="off" placeholder="Chasis No.">
										</div>
									</div>
									
									<!--<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Owner</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="owner" name="owner" autocomplete="off" placeholder="Owner">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label">Km. Done</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="km_done" name="km_done" autocomplete="off" placeholder="Km. Done">
										</div>
									</div>-->
									
									<input type="hidden" id="owner" name="owner">
									<input type="hidden" id="km_done" name="km_done">
									
									<div class="form-group">
										<label for="input-text" class="col-sm-5 control-label"></label>
										<div class="col-sm-7">
											<button type="button" class="btn btn-primary" id="createV">Create</button>
										</div>
									</div>
									
								 </form>
								</div>
								
								<div id="sucessmsgV"><br/>
									<div class="alert alert-success">
										<p>
											Vehicle created successfully. Click 'Select Vehicle'.
										</p>
									</div>
									
									<a href="" class="btn btn-primary vclRow" id="vehi" data-dismiss="modal">
											<span class="btn-label">
										</span> Select Vehicle
									</a>
								</div>
							</div>
						</div>
					</div>
<script>

$(function() {
	
	$('#newVehicleFrm').toggle();
	
	$('.createCust').on('click', function() {
		$('#newVehicleFrm').toggle();
		$('#newVehicleList').toggle();
		$('#sucessmsgV').toggle();
	});
	
	$('.listCust').on('click', function() {
		$('#newVehicleFrm').toggle();
		$('#newVehicleList').toggle();
	});
	
	$('#createV').on('click', function(e) { 
		
		var ci = $('#cust_id').val();
		var vn = $('#frmVehicle #vehicle_name').val();
		var rn = $('#frmVehicle #reg_no').val();
		var mk = $('#frmVehicle #make').val();
		//var cr = $('#frmVehicle #color').val();
		var en = $('#frmVehicle #engine_no').val();
		var cn = $('#frmVehicle #chasis_no').val();
		var ow = $('#frmVehicle #owner').val();
		var km = $('#frmVehicle #km_done').val();
		var md = $('#frmVehicle #model').val();
		var ip = $('#frmVehicle #issue_plate').val();
		var cp = $('#frmVehicle #code_plate').val();
		var cc = $('#frmVehicle #color_code').val();
		var ty = $('#frmVehicle #plate_type').val();
		
		if(vn=="") {
			alert('Vehicle name is required!');
			return false;
		} else if(rn=="") {
			alert('Reg. No. is required!');
			return false;
		} else if(cn=="") {
			alert('Chasis No. is required!');
			return false;
		} else {		
			$('#vehicleDtls').toggle();
			
			$.ajax({
				url: "{{ url('job_estimate/ajax_create/') }}",
				type: 'get',
				data: 'customer_id='+ci+'&name='+vn+'&reg_no='+rn+'&make='+mk+'&engine_no='+en+'&chasis_no='+cn+'&owner='+ow+'&km_done='+km+'&model='+md+'&issue_plate='+ip+'&code_plate='+cp+'&color_code='+cc+'&plate_type='+ty,
				success: function(data) { console.log(data);
					if(data > 0) {
						$('#sucessmsgV').toggle( function() {
							$('#vehi').attr("data-id",data);
							$('#vehi').attr("data-name",vn);
							$('#vehi').attr("data-regno",rn);
							$('#vehi').attr("data-model",md);
							$('#vehi').attr("data-make",mk);
							$('#vehi').attr("data-issue",ip);
							$('#vehi').attr("data-code",cp);
							$('#vehi').attr("data-chasis",cn);
						});
					} else if(data == 0){
						$('#vehicleDtls').toggle();
						alert('Chasis No. already exist!');
						return false;
					} else {
						$('#vehicleDtls').toggle();
						alert('Something went wrong, Vehicle failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tableVehList").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});
	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	});
});
</script>