	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
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


<div class="panel panel-success filterable" id="newShipperFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Shipper
		</h3>
	</div>

	<div class="panel-body">
		<div class="col-xs-10">
			<div id="addressDtls2">
			   <form class="form-horizontal" role="form" method="POST" name="frmShp" id="frmShp">
				  <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <hr/>
					 <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Shipper Name</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="name" name="name" autocomplete="off">
							</div>
					</div>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Address</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="address" name="address" autocomplete="off">
							 </div>
				   </div>
				   <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Phone No</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="phone" name="phone" autocomplete="off">
							 </div>
				   </div>
				
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label"></label>
							 <div class="col-sm-7">
								 <button type="button" class="btn btn-primary" id="create2">Create</button>
							</div>
					</div>
				 </form>
			</div>
			
			<div id="sucessmsg2"><br/>
				<div class="alert alert-success">
					<p>
						Shipper created successfully. Click 'Select Shipper'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary shipperRow" id="shipperUse" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Shipper
				</a>
			</div>
		</div>
	</div>
 </div>
 
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
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
$(document).ready(function() {  //createSalesman
	
	
	
    $('#sucessmsg2').hide();
    
    

       
		$('#create2').on('click', function(e) { 
			var name = $('#frmShp #name').val();
			var ad1  = $('#frmShp #address').val();
			var tp   = $('#frmShp #phone').val();
			
			if(name=="") {
				alert('Shipper name is required!');
				return false;
			} else if(tp=="") {
				alert('Shipper phone is required!');
				return false;
			} else {        
				$('#addressDtls2').toggle();
				$.ajax({
					url: "{{ url('shipper/ajax_create/') }}",
					type: 'get',
					data: 'name='+name+'&address='+ad1+'&phone='+tp,
					success: function(data) { 
						if(data > 0) {
							$('#sucessmsg2').toggle( function() {
								$('#shipperUse').attr("data-id",data);
								$('#shipperUse').attr("data-name",name);
								$('#shipperUse').attr("data-address",ad1);
								$('#shipperUse').attr("data-telephone",tp);
							});
						} else if(data == 0){
							$('#addressDtls2').toggle();
							alert('Shipper name already exist!');
							return false;
						} else if(data == -1){
							$('#addressDtls2').toggle();
							alert('Phone no already exist!');
							return false;
						} else {
							$('#addressDtls2').toggle();
							alert('Something went wrong, Shipper failed to add!');
							return false;
						}
					}
				})
			}
		});
        
    
});

/*var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tblShipper").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});
	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	});*/
</script>
