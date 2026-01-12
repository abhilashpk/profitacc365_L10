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
<div class="row" id="salesmanList">
	<div class="col-lg-12">
		<div class="panel panel-success filterable">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-fw fa-columns"></i> Salesman List
				</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive m-t-10">
					<button type="button" class="btn btn-primary createSalesman">Create</button>
					<table class="table horizontal_table table-striped" id="tblSalesman">
						<thead>
						<tr>
							<th>Salesman ID</th>
							<th>Salesman Name</th>
						</tr>
						</thead>
						<tbody>
						@foreach($salesman as $row)
						<tr>
							<td><a href="" class="salesmanRow" data-id="{{$row->id}}" data-name="{{$row->name}}" data-dismiss="modal">{{$row->salesman_id}}</a></td>
							<td><a href="" class="salesmanRow" data-id="{{$row->id}}" data-name="{{$row->name}}" data-dismiss="modal">{{$row->name}}</a></td>
						</tr>
					   @endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newSalesmanFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Salesman
		</h3>
	</div>

	<div class="panel-body"><button type="button" class="btn btn-primary listSalesman" >List Salesman</button>
		<div class="col-xs-10">
			<div id="addressDtls4">
			   <form class="form-horizontal" role="form" method="POST" name="frmSalesman" id="frmSalesman">
				  <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <hr/>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Salesman ID</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="sid" name="sid" autocomplete="off">
							</div>
					</div>
					
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Salesman Name</label>
						 <div class="col-sm-7">
							<input type="text" class="form-control" id="name" name="name" autocomplete="off">
						 </div>
				   </div>
				   
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label"></label>
							 <div class="col-sm-7">
								 <button type="button" class="btn btn-primary" id="create4">Create</button>
							</div>
					</div>
				 </form>
			</div>
			
			<div id="sucessmsg4"><br/>
				<div class="alert alert-success">
					<p>
						Salesman created successfully. Click 'Select Salesman'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary salesmanRow" id="salesmanUse" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Salesman
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
	
	if( $('#newSalesmanFrm').is(":visible") )
		$('#newSalesmanFrm').toggle();
	
    $('#sucessmsg4').hide();
    
    $('.createSalesman').on('click', function() {
        
        if( $('#salesmanList').is(":visible") )
            $('#salesmanList').toggle();
		
        if( $('#sucessmsg4').is(":visible") )
            $('#sucessmsg4').toggle();
        
        if( $('#newSalesmanFrm').is(":hidden") )
            $('#newSalesmanFrm').toggle();

        $('.listSalesman').on('click', function() {
        
			if( $('#newSalesmanFrm').is(":visible") )
				$('#newSalesmanFrm').toggle();
			
			if( $('#salesmanList').is(":hidden") )
				$('#salesmanList').toggle();

		});

		$('#create4').on('click', function(e) { 
			var sid = $('#frmSalesman #sid').val();
			var name = $('#frmSalesman #name').val();
			
			if(sid=="") {
				alert('Salesman ID is required!');
				return false;
			} else if(name=="") {
				alert('Salesman name is required!');
				return false;
			} else {        
				$('#addressDtls4').toggle();
				$.ajax({
					url: "{{ url('cargo_salesman/ajax_create/') }}",
					type: 'get',
					data: 'sid='+sid+'&name='+name,
					success: function(data) { 
						if(data > 0) {
							$('#sucessmsg4').toggle( function() {
								$('#salesmanUse').attr("data-id",data);
								$('#salesmanUse').attr("data-name",name);
							});
						} else if(data == 0){
							$('#addressDtls4').toggle();
							alert('Salesman ID already exist!');
							return false;
						} else {
							$('#addressDtls4').toggle();
							alert('Something went wrong, Salesman failed to add!');
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
	var dtInstance = $("#tblSalesman").DataTable({
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
