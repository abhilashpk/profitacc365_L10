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
<div class="row" id="consigneeList">
	<div class="col-lg-12">
		<div class="panel panel-success filterable">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-fw fa-columns"></i> Consignee List
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive m-t-10">
					<button type="button" class="btn btn-primary createConsignee">Create</button>
					<table class="table horizontal_table table-striped" id="tblConsignee">
						<thead>
						<tr>
							<th>Name</th>
							<th>Phone</th>
							<th>Consignee Code</th>
						</tr>
						</thead>
						<tbody>
						@foreach($consignee as $row)
						<tr>
							<td><a href="" class="consigneeRow" data-id="{{$row->id}}" data-name="{{$row->consignee_name}}" data-address="{{$row->address}}" data-phone="{{$row->phone}}"  data-code="{{$row->consignee_code}}" data-dismiss="modal">{{$row->consignee_name}}</a></td>
							<td><a href="" class="consigneeRow" data-id="{{$row->id}}" data-name="{{$row->consignee_name}}" data-address="{{$row->address}}" data-phone="{{$row->phone}}" data-code="{{$row->consignee_code}}" data-dismiss="modal">{{$row->phone}}</a></td>
							<td><a href="" class="consigneeRow" data-id="{{$row->id}}" data-name="{{$row->consignee_name}}" data-address="{{$row->address}}" data-phone="{{$row->phone}}" data-code="{{$row->consignee_code}}" data-dismiss="modal">{{$row->consignee_code}}</a></td>
						</tr>
					   @endforeach
						</tbody>
					</table>
			  
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newConsigneeFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Consignee
		</h3>
	</div>

	<div class="panel-body"><button type="button" class="btn btn-primary listConsignee" >List Consignee</button>
		<div class="col-xs-10">
			<div id="addressDtls1">
			   <form class="form-horizontal" role="form" method="POST" name="frmCon" id="frmCon">
				  <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <hr/>
					 <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Consignee Name</label>
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
						 <label for="input-text" class="col-sm-5 control-label">Alternate Phone No</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="phone1" name="phone1" autocomplete="off">
							 </div>
				   </div>
				   <div class="form-group">
				             <label for="input-text" class="col-sm-5 control-label">Consignee Code</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="code" name="code" autocomplete="off">
                                    </div>
					 </div>				
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label"></label>
							 <div class="col-sm-7">
								 <button type="button" class="btn btn-primary" id="create1">Create</button>
							</div>
					</div>
				 </form>
			</div>
			
			<div id="sucessmsg1"><br/>
				<div class="alert alert-success">
					<p>
						Consignee created successfully. Click 'Select Consignee'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary consigneeRow" id="consigneeUse" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Consignee
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
	
	if( $('#newConsigneeFrm').is(":visible") )
		$('#newConsigneeFrm').toggle();
	
    $('#sucessmsg1').hide();
    
    $('.createConsignee').on('click', function() {
        
        if( $('#consigneeList').is(":visible") )
            $('#consigneeList').toggle();
		
        if( $('#sucessmsg1').is(":visible") )
            $('#sucessmsg1').toggle();
        
        if( $('#newConsigneeFrm').is(":hidden") )
            $('#newConsigneeFrm').toggle();

        $('.listConsignee').on('click', function() {
        
			if( $('#newConsigneeFrm').is(":visible") )
				$('#newConsigneeFrm').toggle();
			
			if( $('#consigneeList').is(":hidden") )
				$('#consigneeList').toggle();

		});

		$('#create1').on('click', function(e) { 
			var name = $('#frmCon #name').val();
			var ad1  = $('#frmCon #address').val();
			var tp   = $('#frmCon #phone').val();
			var ap   = $('#frmCon #phone1').val(); 
			var con   = $('#frmCon #code').val();
			if(name=="") {
				alert('Consignee name is required!');
				return false;
			} else if(tp=="") {
				alert('Consignee phone is required!');
				return false;
			} else {        
				$('#addressDtls1').toggle();
				$.ajax({
					url: "{{ url('consignee/ajax_create/') }}",
					type: 'get',
					data: 'name='+name+'&address='+ad1+'&phone='+tp+'&phone1='+ap+'&code='+con,
					success: function(data) { 
						if(data > 0) {
							$('#sucessmsg1').toggle( function() {
								$('#consigneeUse').attr("data-id",data);
								$('#consigneeUse').attr("data-name",name);
								$('#consigneeUse').attr("data-address",ad1);
								$('#consigneeUse').attr("data-telephone",tp);
								$('#consigneeUse').attr("data-telephone",ap);
								$('#consigneeUse').attr("data-code",con);
							});
						} else if(data == 0){
							$('#addressDtls1').toggle();
							alert('Consignee name already exist!');
							return false;
						} else if(data == -1){
							$('#addressDtls1').toggle();
							alert('Phone no already exist!');
							return false;
						} else if(data == -2){
							$('#addressDtls1').toggle();
							alert('Alternate Phone no already exist!');
							return false;
						 }else {
							$('#addressDtls1').toggle();
							alert('Something went wrong, Consignee failed to add!');
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
	var dtInstance = $("#tblConsignee").DataTable({
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
