<div class="panel panel-success filterable" id="newSalesmanList1">
<div class="panel-heading">
	<h3 class="panel-title">
		<i class="fa fa-fw fa-columns"></i> Salesman List
	</h3>
</div>
<div class="panel-body">
	<div class="table-responsive m-t-10"><button type="button" class="btn btn-primary createSalesman1">Create Engineer</button>
	  <div class="table-responsive m-t-10">
		<table class="table horizontal_table table-striped" id="tablesm">
			<thead>
			<tr>
				<th>Salesman ID</th>
				<th>Salesman Name</th>
			</tr>
			</thead>
			<tbody>
			@foreach($salesmans as $salesman)
			<tr>
				<td><a href="" class="salesmanRow" data-id="{{$salesman->id}}" data-name="{{$salesman->salesman_id}}" data-dismiss="modal">{{$salesman->salesman_id}}</a></td>
				<td><a href="" class="salesmanRow" data-id="{{$salesman->id}}" data-name="{{$salesman->name}}" data-dismiss="modal">{{$salesman->name}}</a></td>
			</tr>
		   @endforeach
			</tbody>
		</table>
	  </div>
	</div>
</div>
</div>

<div class="panel panel-success filterable" id="allSalesmanList1">
	<div class="panel-body">
		<div class="table-responsive m-t-10">
			<table class="table horizontal_table table-striped" id="tblallSalesman"></table>
		</div>
	</div>
</div>
 
<div class="panel panel-success filterable" id="newSalesmanFrm1">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Engineer
		</h3>
	</div>

	<div class="panel-body"><button type="button" class="btn btn-primary listSalesman1" >List Engineer</button>
		<div class="col-xs-10">
			<div id="addressDtls1">
			   <form class="form-horizontal" role="form" method="POST" name="frmSalesman" id="frmSalesman">
				  <input type="hidden" name="_token" value="{{ csrf_token() }}">
				   <hr/>
					 <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Salesman ID</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="salesman_id" name="salesman_id" placeholder="Salesman ID">
							 </div>
					 </div>
					 <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Salesman Name</label>
							 <div class="col-sm-7">
								 <input type="text" class="form-control" id="name" name="name" placeholder="Salesman Name">
							</div>
					</div>
				
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Address1</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="address1" name="address1" placeholder="Address1">
							 </div>
				   </div>
					<div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Address2</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="address2" name="address2" placeholder="Address2">
							 </div>
				   </div>
				   <div class="form-group">
						 <label for="input-text" class="col-sm-5 control-label">Contact No</label>
							 <div class="col-sm-7">
								<input type="text" class="form-control" id="telephone" name="telephone" placeholder="Contact No">
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
						Engineer created successfully. Click 'Select Engineer'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary salesmanRow" id="salesmanUse" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Engineer
				</a>
			</div>
		</div>
	</div>
 </div>

<script>
$(document).ready(function() {  //createSalesman
    $(function() {

    $('#newSalesmanFrm1').toggle();
    $('#allSalesmanList1').toggle();
    $('#sucessmsg1').hide();
    
    $('.createSalesman1').on('click', function() {
        if( $('#allSalesmanList1').is(":visible") )
            $('#allSalesmanList1').toggle();
        if( $('#newSalesmanList1').is(":visible") )
            $('#newSalesmanList1').toggle();
		
        if( $('#sucessmsg1').is(":visible") )
            $('#sucessmsg1').toggle();
        
        if( $('#newSalesmanFrm1').is(":hidden") )
            $('#newSalesmanFrm1').toggle();

        $('.listSalesman1').on('click', function() {
        if( $('#allSalesmanList1').is(":visible") )
            $('#allSalesmanList1').toggle();
        if( $('#newSalesmanFrm1').is(":visible") )
            $('#newSalesmanFrm1').toggle();
        
        if( $('#newSalesmanList1').is(":hidden") )
            $('#newSalesmanList1').toggle();

    });


   

    $('#create1').on('click', function(e) { console.log('gg');
        
        var si   = $('#frmSalesman #salesman_id').val();
        var name = $('#frmSalesman #name').val();
        var ad1  = $('#frmSalesman #address1').val();
        var ad2  = $('#frmSalesman #address2').val();
        var tp   = $('#frmSalesman #telephone').val();
       
      
        
        if(name=="") {
            alert('Salesman name is required!');
            return false;
        } else {        
            $('#addressDtls1').toggle();
            
            $.ajax({
                url: "{{ url('salesman/ajax_create/') }}",
                type: 'get',
                data: 'salesman_id='+si+'&name='+name+'&address1='+ad1+'&address2='+ad2+'&telephone='+tp,
                success: function(data) { //console.log(data);
                    if(data > 0) {
                        $('#sucessmsg1').toggle( function() {
                            $('#salesmanUse').attr("data-id",data);
                            $('#salesmanUse').attr("data-salesman_id",si);
                            $('#salesmanUse').attr("data-name",name);
                            $('#salesmanUse').attr("data-address1",ad1);
                            $('#salesmanUse').attr("data-address2",ad2);
                            $('#salesmanUse').attr("data-telephone",tp);
                           
                        });
                    } else if(data == 0){
                        $('#addressDtls1').toggle();
                        alert('Salesman name already exist!');
                        return false;
                    } else {
                        $('#addressDtls1').toggle();
                        alert('Something went wrong, Salesman failed to add!');
                        return false;
                    }
                }
            })
        }
    });


        
    });


    var dtInstance = $("#tablesm").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
                filter: true,
                searching: true,
                "bSort" : false,
                "aoColumns": [null,null],
                //"searching": true,
                //"scrollX": true,
            });
       });
});
</script>