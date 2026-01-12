	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <!--end of page level css-->
<div class="row" id="vehicleList">
	<div class="col-lg-12">
		<div class="panel panel-success filterable">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-fw fa-columns"></i> Vehicle List
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive m-t-10">
					<button type="button" class="btn btn-primary createVehicle">Create</button>
					<table class="table horizontal_table table-striped" id="tblVehicle">
						<thead>
						<tr>
							<th>Vehicle Name</th>
							<th>Reg.No</th>
							<th>Expiry Date</th>
							<th>Driver</th>
						</tr>
						</thead>
						<tbody>
						@foreach($vehicle as $row)
						<tr>
							<td><a href="" class="vehicleRow" data-id="{{$row->id}}" data-vehno="{{$row->vehicle_no}}" data-driver="{{$row->driver_name}}" data-mobuae="{{$row->mobile_uae}}" data-mobksa="{{$row->mobile_ksa}}" data-dismiss="modal">{{$row->vehicle_name}}</a></td>
							<td><a href="" class="vehicleRow" data-id="{{$row->id}}" data-vehno="{{$row->vehicle_no}}" data-driver="{{$row->driver_name}}" data-mobuae="{{$row->mobile_uae}}" data-mobksa="{{$row->mobile_ksa}}" data-dismiss="modal">{{$row->vehicle_no}}</a></td>
							@if($row->expiry_date > date('Y-m-d'))
							<td>{{($row->expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->expiry_date)):''}}</td>
							@else
							<td><span style="color:red !important;">{{($row->expiry_date!='0000-00-00')?date('d-m-Y',strtotime($row->expiry_date)):''}}</span></td>
							@endif
							<td>{{$row->driver_name}}</td>
						</tr>
					   @endforeach
						</tbody>
					</table>
			  
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newVehicleFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Vehicle
		</h3>
	</div>

	<div class="panel-body"><button type="button" class="btn btn-primary listVehicle" >List Vehicle</button>
		<div class="col-xs-10">
			<div id="addressDtls3">
			   <form class="form-horizontal" role="form" method="POST" name="frmVcle" id="frmVcle">
				  <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <hr/>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Vehicle No</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="vno" name="vno" autocomplete="off">
							</div>
					</div>
					 <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Vehicle Type</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="vtype" name="vtype" autocomplete="off">
							</div>
					</div>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Driver Name</label>
						 <div class="col-sm-7">
							<input type="text" class="form-control" id="dname" name="dname" autocomplete="off">
						 </div>
				   </div>
				   <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Driver Mobile(UAE)</label>
						 <div class="col-sm-7">
							<input type="text" class="form-control" id="mobu" name="mobu" autocomplete="off">
						 </div>
				   </div>
				   <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Driver Mobile(KSA)</label>
						 <div class="col-sm-7">
							<input type="text" class="form-control" id="mobk" name="mobk" autocomplete="off">
						 </div>
				   </div>
				   <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Expiry Date</label>
						 <div class="col-sm-7">
							<input type="text" class="form-control pull-right" autocomplete="off" name="expiry_date" id="expiry_date" data-language='en' readonly />
						 </div>
				   </div>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label"></label>
							 <div class="col-sm-7">
								 <button type="button" class="btn btn-primary" id="create3">Create</button>
							</div>
					</div>
				 </form>
			</div>
			
			<div id="sucessmsg3"><br/>
				<div class="alert alert-success">
					<p>
						Vehicle created successfully. Click 'Select Vehicle'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary vehicleRow" id="vehicleUse" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Vehicle
				</a>
			</div>
		</div>
	</div>
 </div>
 
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->
<script>
$('#expiry_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$(document).ready(function() {  //createSalesman
	
	if( $('#newVehicleFrm').is(":visible") )
		$('#newVehicleFrm').toggle();
	
    $('#sucessmsg3').hide();
    
    $('.createVehicle').on('click', function() {
        
        if( $('#vehicleList').is(":visible") )
            $('#vehicleList').toggle();
		
        if( $('#sucessmsg3').is(":visible") )
            $('#sucessmsg3').toggle();
        
        if( $('#newVehicleFrm').is(":hidden") )
            $('#newVehicleFrm').toggle();

        $('.listVehicle').on('click', function() {
        
			if( $('#newVehicleFrm').is(":visible") )
				$('#newVehicleFrm').toggle();
			
			if( $('#vehicleList').is(":hidden") )
				$('#vehicleList').toggle();

		});

		$('#create3').on('click', function(e) { 
			var vno = $('#frmVcle #vno').val();
			var vtype  = $('#frmVcle #vtype').val();
			var dname   = $('#frmVcle #dname').val();
			var mobu   = $('#frmVcle #mobu').val();
			var mobk   = $('#frmVcle #mobk').val();
			var edt   = $('#frmVcle #expiry_date').val();
			
			if(vno=="") {
				alert('Vehicle no is required!');
				return false;
			} else if(vtype=="") {
				alert('Vehicle type is required!');
				return false;
			} else if(dname=="") {
				alert('Driver name is required!');
				return false;
			} else {        
				$('#addressDtls3').toggle();
				$.ajax({
					url: "{{ url('cargo_vehicle/ajax_create/') }}",
					type: 'get',
					data: 'vno='+vno+'&vtype='+vtype+'&dname='+dname+'&mobu='+mobu+'&mobk='+mobk+'&edate='+edt,
					success: function(data) { 
						if(data > 0) {
							$('#sucessmsg3').toggle( function() {
								$('#vehicleUse').attr("data-id",data);
								$('#vehicleUse').attr("data-vehno",vno);
								$('#vehicleUse').attr("data-driver",dname);
								$('#vehicleUse').attr("data-mobuae",mobu);
								$('#vehicleUse').attr("data-mobksa",mobk);
							});
						} else if(data == 0){
							$('#addressDtls3').toggle();
							alert('Vehicle no already exist!');
							return false;
						} else {
							$('#addressDtls3').toggle();
							alert('Something went wrong, Vehicle failed to add!');
							return false;
						}
					}
				})
			}
		});
        
    });
});

var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tblVehicle").DataTable({
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
</script>
