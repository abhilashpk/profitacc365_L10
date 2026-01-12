<div class="panel-body">
	<input type="hidden" name="num" id="anum" value="{{$num}}">
	<div class="table-responsive m-t-10" id="accountListOc">
		<button type="button" class="btn btn-primary createAcc">Create Account</button>
		<table class="table horizontal_table table-striped" id="tableAcntListall">
			<thead>
			<tr>
				<th>Account ID</th>
				<th>Account Name</th>
				<th>Group</th>
				<th>Balance</th>
			</tr>
			</thead>
			<tbody>
			@foreach($accounts as $account)
			<tr>
				<td><a href="" class="accountRowall" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-dismiss="modal">{{$account->account_id}}</a></td>
				<td><a href="" class="accountRowall" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-dismiss="modal">{{$account->master_name}}</a></td>
				<td></td>
				<td>{{ number_format($account->cl_balance, 2, '.', ',') }}</td>
			</tr>
		   @endforeach
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-success filterable" id="newAccountFrmoc">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Account
		</h3>
	</div>
	<div class="panel-body">  <button type="button" class="btn btn-primary listCust">List Accounts</button>
		<hr/>
		<div class="col-xs-10">
			<div id="addressDtlsoc">
			<form class="form-horizontal" role="form" method="POST" name="frmAccountoc" id="frmAccountoc">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="category" id="category">
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Type</label>
					<div class="col-sm-7">
						<select id="actype_id" class="form-control select2" style="width:100%" name="actype_id">
							<option value="">Select Account Type...</option>
							@foreach ($acctype as $type)
							<option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Category</label>
					<div class="col-sm-7">
						<select id="category_id" class="form-control select2" style="width:100%" name="category_id">
							<option value="">Select Account Category...</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Group</label>
					<div class="col-sm-7">
						<select id="group_id" class="form-control select2" style="width:100%" name="group_id">
							<option value="">Select Account Group...</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account ID</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="account_id" name="account_id">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Name</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Customer Name">
					</div>
				</div>
				
				<div class="form-group" id="trtype">
					<label for="input-text" class="col-sm-5 control-label">Transaction Type</label>
					<div class="col-sm-7">
						<select id="transaction" class="form-control select2" style="width:100%" name="transaction">
							<option value="Dr">Dr</option>
							<option value="Cr">Cr</option>
						</select>
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
					<label for="input-text" class="col-sm-5 control-label">Email</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="email" name="email" placeholder="Email">
					</div>
				</div>
														
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">TRN No</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="TRN No.">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">VAT Assignable?</label>
					<div class="col-sm-7">
						<label class="radio-inline iradio">
							<input type="checkbox" class="onacnt_icheck" id="vat_assign" name="vat_assign" value="1">
					</div>
				</div>
				
				<div class="form-group" id="vatPntg">
					<label for="input-text" class="col-sm-5 control-label">VAt Percentage</label>
					<div class="col-sm-7">
						<input type="number" step="any" class="form-control" id="vat_perc" name="vat_percentage" placeholder="VAT %">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label"></label>
					<div class="col-sm-7">
						<button type="button" class="btn btn-primary" id="createoc">Create</button>
					</div>
				</div>
			 </form>
			</div>
			
			<div id="sucessmsgoc"><br/>
				<div class="alert alert-success">
					<p>
						Account created successfully. Click 'Select Account'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary accountRowall" id="cususeoc" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Account
				</a>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
	
	$('#newAccountFrmoc').toggle();
	
	$('.createAcc').on('click', function() {
		$('#newAccountFrmoc').toggle();
		$('#accountListOc').toggle();
		$('#sucessmsgoc').toggle();
	});
	
	$('.listCust').on('click', function() {
		$('#newAccountFrmoc').toggle();
		$('#accountListOc').toggle();
	});
	
	$('#createoc').on('click', function(e){
		
		var cat = $('#frmAccountoc #category_id option:selected').val();
		var grp = $('#frmAccountoc #group_id option:selected').val();
		var ac = $('#frmAccountoc #account_id').val();
		var name = $('#frmAccountoc #supplier_name').val();
		var adrs = $('#frmAccountoc #address').val();
		var ar = $('#frmAccountoc #area_id option:selected').val();
		var cn = $('#frmAccountoc #country_id option:selected').val();
		var ph = $('#frmAccountoc #phone').val();
		var vt = $('#frmAccountoc #vat_no').val();
		var ct = $('#frmAccountoc #category').val();
		var tr = $('#frmAccountoc #transaction option:selected').val();
		var em = $('#frmAccountoc #email').val();
		var vp = $('#frmAccount #vat_perc').val();
		if ($('#vat_assign').is(":checked"))
			var va = 1;
		else
			var va = 0;
		
		if(name=="") {
			alert('Account name is required!');
			return false;
		} else {		
			$('#addressDtlsoc').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create_acc/') }}",
				type: 'get',
				data: 'category_id='+cat+'&group_id='+grp+'&account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category='+ct+'&transaction='+tr+'&email='+em+'&vtas='+va+'&vtpr='+vp,
				success: function(data) { //console.log(data);
					if(data > 0) {
						$('#sucessmsgoc').toggle( function() {
							$('#cususeoc').attr("data-id",data);
							$('#cususeoc').attr("data-name",name);
						});
					} else if(data == 0) {
						$('#addressDtlsoc').toggle();
						alert('Account name already exist!');
						return false;
					} else { alert(data);exit;
						$('#addressDtlsoc').toggle();
						//$('#sucessmsgoc').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	$('#frmAccountoc #actype_id').on('change', function(e){
		var type_id = e.target.value;

		$.get("{{ url('accategory/getcategory/') }}/" + type_id, function(data) {
			$('#frmAccountoc #category_id').empty();
			 $('#frmAccountoc #category_id').append('<option value="">Select Account Category...</option>');
			$.each(data, function(value, display){
				 $('#frmAccountoc #category_id').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
	});
	
	$('#frmAccountoc #category_id').on('change', function(e){
		var cat_id = e.target.value;

		$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
			$('#frmAccountoc #group_id').empty();
			 $('#frmAccountoc #group_id').append('<option value="">Select Account Group...</option>');
			$.each(data, function(value, display){
				 $('#frmAccountoc #group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
	});
	
	$('#frmAccountoc #group_id').on('change', function(e){
		var group_id = e.target.value;
		$.get("{{ url('account_master/getcode/') }}/" + group_id, function(data) {
			var val = $.parseJSON(data);
			$('#frmAccountoc #account_id').val(val.code);
			$('#frmAccountoc #category').val(val.category);
			cat = val.category;
			if( cat=='CUSTOMER' || cat=='PDCR'){ 
				$('#frmAccountoc #transaction').find('option').remove().end().append('<option value="Dr">Dr</option>');
			} 
			else if(cat=='SUPPLIER' || cat=='PDCI') {
				$('#frmAccountoc #transaction').find('option').remove().end().append('<option value="Cr">Cr</option>');
			} else {
				$('#frmAccountoc #transaction').find('option').remove().end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
			}
		});
	});
	
	
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	/*var dtInstance = $("#tableAcntListall").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});*/
	
/*	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	});*/

	var dtInstance = $("#tableAcntListall").DataTable({
		"processing": true,
		"serverSide": true,
		"ajax":{
				"url": "{{ url('sales_order/customer_list/') }}",
				"dataType": "json",
				"type": "POST",
				"data":{ _token: "{{csrf_token()}}", ntype: "{{$ntype}}" }
				},
		"columns": [
			{ "data": "account_id" },
			{ "data": "master_name" },
			{ "data": "group" },
			{ "data": "cl_balance" }
		]	
		
	});
});
</script>