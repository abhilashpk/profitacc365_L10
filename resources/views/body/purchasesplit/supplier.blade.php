<div class="panel panel-success filterable" id="newSuplierList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Supplier List
		</h3>
	</div>
	<div class="panel-body">
		<input type="hidden" name="num" id="num" value="{{$num}}">
		<div class="table-responsive m-t-10">
			<button type="button" class="btn btn-primary createCust">Create Supplier</button>
			<table class="table horizontal_table table-striped" id="tableSuppList">
				<thead>
				<tr>
					<th>Account ID</th>
					<th>Account Name</th>
					<th>Balance</th>
					<th>Open Balance</th>
				</tr>
				</thead>
				<tbody>
				@foreach($suppliers as $supplier)
				<tr>
					<td><a href="" class="supp" data-id="{{$supplier->id}}" data-name="{{$supplier->master_name}}" data-dismiss="modal">{{$supplier->account_id}}</a></td>
					<td><a href="" class="supp" data-id="{{$supplier->id}}" data-name="{{$supplier->master_name}}" data-dismiss="modal">{{$supplier->master_name}}</a></td>
					<td>{{ number_format($supplier->cl_balance, 2, '.', ',') }}</td>
					<td>{{ number_format($supplier->op_balance, 2, '.', ',') }}</td>
				</tr>
			   @endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newSupplierFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Supplier
		</h3>
	</div>
	<div class="panel-body">  <button type="button" class="btn btn-primary listCust">List Supplier</button>
		<hr/>
		<div class="col-xs-10">
			<div id="addressDtls">
			<form class="form-horizontal" role="form" method="POST" name="frmSupplier" id="frmSupplier">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" class="form-control" id="account_id" name="account_id">
			<input type="hidden" name="category" value="{{$category}}">

				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Supplier Name</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Customer Name">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Address</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="address" name="address" placeholder="Address1, Address2">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Contry</label>
					<div class="col-sm-7">
						<select id="country_id" class="form-control select2" style="width:100%" name="country_id">
							<option value="">Select Country...</option>
							@foreach ($country as $con)
							<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Area</label>
					<div class="col-sm-7">
						<select id="area_id" class="form-control select2" style="width:100%" name="area_id">
							<option value="">Select Area...</option>
							@foreach ($area as $ar)
							<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Phone</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">TRN No</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label"></label>
					<div class="col-sm-7">
						<button type="button" class="btn btn-primary" id="create">Create</button>
					</div>
				</div>
			 </form>
			</div>
			
			<div id="sucessmsg"><br/>
				<div class="alert alert-success">
					<p>
						Supplier created successfully. Click 'Select Supplier'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary supp" id="cususe" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Supplier
				</a>
			</div>
		</div>
	</div>
</div>
					
<script>
$(function() {
	
	$('#newSupplierFrm').toggle();
	
	$('.createCust').on('click', function() {
		$('#newSupplierFrm').toggle();
		$('#newSuplierList').toggle();
		$('#sucessmsg').toggle();
	});
	
	$('.listCust').on('click', function() {
		$('#newSupplierFrm').toggle();
		$('#newSuplierList').toggle();
	});
	
	$('#create').on('click', function(e){
		
		var ac = $('#frmSupplier #account_id').val();
		var name = $('#frmSupplier #supplier_name').val();
		var adrs = $('#frmSupplier #address').val();
		var ar = $('#frmSupplier #area_id option:selected').val();
		var cn = $('#frmSupplier #country_id option:selected').val();
		var ph = $('#frmSupplier #phone').val();
		var vt = $('#frmSupplier #vat_no').val();
		if(name=="") {
			alert('Supplier name is required!');
			return false;
		} else {		
			$('#addressDtls').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create/') }}",
				type: 'get',
				data: 'account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category=SUPPLIER',
				success: function(data) { //console.log(data);
					if(data > 0) {
						$('#sucessmsg').toggle( function() {
							$('#cususe').attr("data-id",data);
							$('#cususe').attr("data-name",name);
						});
					} else if(data == 0) {
						$('#addressDtls').toggle();
						alert('Supplier name already exist!');
						return false;
					} else {
						$('#addressDtls').toggle();
						//$('#sucessmsg').toggle();
						alert('Something went wrong, Account failed to add!');
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
	var dtInstance = $("#tableSuppList").DataTable({
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