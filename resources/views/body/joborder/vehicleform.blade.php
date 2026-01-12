<div class="panel panel-success filterable" id="newVehicleFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Vehicle
		</h3>
		
	</div>
	<div class="panel-body">
	
		<div class="col-xs-10">
			<div id="vehicleDtls">
			<form class="form-horizontal" role="form" method="POST" name="frmVehicle" id="frmVehicle">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Estimate Type</label>
					<div class="col-sm-7">
					   <select id="voucher_id" class="form-control select2" style="width:100%" name="voucher_id">
							@foreach($vouchers as $voucher)
							<option value="{{ $voucher['id'] }}">{{ $voucher['voucher_name'] }}</option>
							@endforeach
						</select>
						<input type="hidden" id="is_cahsale" name="is_cahsale"/>
					</div>
				</div>
									
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Customer</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="customerV" name="customer_name" autocomplete="off" data-toggle="modal" data-target="#customeron_modal" placeholder="Customer">
						<input type="hidden" id="customer_idV" name="customer_idV"/>
					</div>
				</div>
				
				<div class="custinfo">
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Customer Name</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="cust_name" name="cust_name" autocomplete="off" placeholder="Customer Name">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Phone No.</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="phone_no" name="phone_no" autocomplete="off" placeholder="Phone No.">
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Vehicle Name</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" autocomplete="off" placeholder="Vehicle Name">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Reg. No.</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="reg_no" name="reg_no" autocomplete="off" placeholder="Reg. No.">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Make</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="make" name="make" autocomplete="off" placeholder="Make">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Color</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="color" name="color" autocomplete="off" placeholder="Color">
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
				
				<div class="form-group">
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
				</div>
				
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
				
				<a href="" class="btn btn-primary vclfrmRow" id="vehifrm" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Vehicle
				</a>
			</div>
		</div>
	</div>
</div>

<script>

$(document).ready(function () { 
	$('#customerV').val('{{$vouchers[0]->default_account}}');
	$('#customer_idV').val('{{$vouchers[0]->default_account_id}}');
	<?php if($vouchers[0]->is_cash_voucher==0) { ?>
	$('.custinfo').toggle();
	<?php } else { ?>
	$('#is_cahsale').val(1);
	//$('#customer_idV').val(data.cash_account);
	<?php } ?>
});

$(function() {
	$('#sucessmsgV').toggle();
	
	$('#voucher_id').on('change', function(e){
		var vchr_id = e.target.value; 
		$.get("{{ url('sales_invoice/getvoucher/') }}/" + vchr_id, function(data) {
			
			if(data.cash_voucher==1) {
				if( $('.custinfo').is(":hidden") )
					$('.custinfo').toggle();
				$('#customerV').val(data.default_account);
				$('#customer_idV').val(data.cash_account);
				$('#is_cahsale').val(1);
			} else {
				if( $('.custinfo').is(":visible") )
					$('.custinfo').toggle();
				$('#customerV').val('');
				$('#customer_idV').val('');
				$('#is_cahsale').val('');
			}
			
		});
	});
	
	//$('#newVehicleFrm').toggle();
	
	/* $('.createCust').on('click', function() {
		$('#newVehicleFrm').toggle();
		$('#newVehicleList').toggle();
		$('#sucessmsgV').toggle();
	}); */
	
	/* $('.listCust').on('click', function() {
		$('#newVehicleFrm').toggle();
		$('#newVehicleList').toggle();
	}); */
	
	$('#createV').on('click', function(e) { 
		
		var isc = $('#frmVehicle #is_cahsale').val();
		var csn = $('#frmVehicle #customerV').val();
		var csnid = $('#frmVehicle #customer_idV').val();
		var cstn = ''; var pn = '';
		if(isc=='1') {
			cstn = $('#frmVehicle #cust_name').val();
			pn = $('#frmVehicle #phone_no').val();
		}
		var vn = $('#frmVehicle #vehicle_name').val();
		var rn = $('#frmVehicle #reg_no').val();
		var mk = $('#frmVehicle #make').val();
		var cr = $('#frmVehicle #color').val();
		var en = $('#frmVehicle #engine_no').val();
		var cn = $('#frmVehicle #chasis_no').val();
		var ow = $('#frmVehicle #owner').val();
		var km = $('#frmVehicle #km_done').val();
		
		if(csn=="") {
			alert('Customer is required!');
			return false;
		}
		else if(vn=="") {
			alert('Vehicle name is required!');
			return false;
		} else if(rn=="") {
			alert('Reg. No. is required!');
			return false;
		} else {		
			$('#vehicleDtls').toggle();
			
			$.ajax({
				url: "{{ url('job_order/ajax_create/') }}",
				type: 'get',
				data: 'is_cashsale='+isc+'&customer_id='+csnid+'&customer_name='+cstn+'&phone_no='+pn+'&name='+vn+'&reg_no='+rn+'&make='+mk+'&color='+cr+'&engine_no='+en+'&chasis_no='+cn+'&owner='+ow+'&km_done='+km,
				success: function(data) { console.log(data);
					if(data.status == 'OK') {
						$('#sucessmsgV').toggle( function() {
							$('#vehifrm').attr("data-vid",data.vid);
							$('#vehifrm').attr("data-vname",vn);
							$('#vehifrm').attr("data-cid",csnid);
							var nam = (cstn=='')?csn:cstn;
							$('#vehifrm').attr("data-cname",nam);
						});
					/* } else if(data.status == 'CUSTERR'){
						$('#vehicleDtls').toggle();
						alert('Customer name already exist!');
						return false; */
					} else if(data.status == 'VCLERR'){
						$('#vehicleDtls').toggle();
						//alert('Chasis No. already exist!');
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
	
	var custonurl = "{{ url('sales_order/customer_data/') }}";//quotation_sales
	$('#customerV').click(function() {
		$('#customeronData').load(custonurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	
});
</script>