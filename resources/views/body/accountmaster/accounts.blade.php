<div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10" id="accountList">
		<button type="button" class="btn btn-primary createAcc">Create Account</button>
		<table class="table horizontal_table table-striped" id="tableAcntList">
			<thead>
			<tr>
				<th>Account ID</th>
				<th>Account Name</th>
				<th>Group</th>
				<th>Balance</th>
			</tr>
			</thead>
			<input type="hidden" name="num" id="num" value="{{$num}}">
			<tbody>
			@foreach($accounts as $account)
			<tr>
				<td><a href="" class="accountRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-vat="{{$account->vat_percentage}}" data-dismiss="modal">{{$account->account_id}}</a></td>
				<td><a href="" class="accountRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-vat="{{$account->vat_percentage}}" data-dismiss="modal">{{$account->master_name}}</a></td>
				<td></td>
				<td>{{ number_format($account->cl_balance, 2, '.', ',') }}</td>
			</tr>
		   @endforeach
			</tbody>
		</table>
	</div>
</div>
<div class="panel panel-success filterable" id="newAccountFrm">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> New Account
		</h3>
	</div>
	<div class="panel-body">  <button type="button" class="btn btn-primary listCust">List Accounts</button>
		<hr/>
		<div class="col-xs-10">
			<div id="addressDtls">
			<form class="form-horizontal" role="form" method="POST" name="frmAccount" id="frmAccount">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="category" id="category">
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Type</label>
					<div class="col-sm-7">
						<select id="actype_id" class="form-control input-sm" style="width:100%" name="actype_id">
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
						<select id="category_id" class="form-control input-sm" style="width:100%" name="category_id">
							<option value="">Select Account Category...</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Group</label>
					<div class="col-sm-7">
						<select id="group_id" class="form-control input-sm" style="width:100%" name="group_id">
							<option value="">Select Account Group...</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account ID</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="account_id" name="account_id">
					</div>
				</div>
				
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Account Name</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="supplier_name1" name="supplier_name" placeholder="Customer Name">
					</div>
				</div>
				
				<?php if($formdata['account_transaction']==1) { ?>	
				<div class="form-group" id="trtype">
					<label for="input-text" class="col-sm-5 control-label">Transaction Type</label>
					<div class="col-sm-7">
						<select id="transaction" class="form-control input-sm" style="width:100%" name="transaction">
							<option value="Dr">Dr</option>
							<option value="Cr">Cr</option>
						</select>
					</div>
				</div>
				<?php } else { ?>
				 <input type="hidden" name="transaction" id="transaction">
				<?php } ?>

				<?php if($formdata['account_address']==1) { ?>			
				<div class="form-group" id="address">
					<label for="input-text" class="col-sm-5 control-label">Address</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="address" name="address" placeholder="Address1, Address2">
					</div>
				</div>
				<?php } else { ?>
				 <input type="hidden" name="address" id="address">
				<?php } ?>
				
				<?php if($formdata['account_country']==1) { ?>
				<div class="form-group"id="country_id" >
					<label for="input-text" class="col-sm-5 control-label">Contry</label>
					<div class="col-sm-7">
						<select id="country_id" class="form-control input-sm" style="width:100%" name="country_id">
							<option value="">Select Country...</option>
							@foreach ($country as $con)
							<option value="{{ $con['id'] }}">{{ $con['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<?php } else { ?>
				 <input type="hidden" name="country_id" id="country_id">
				<?php } ?>
												
				<?php if($formdata['account_area']==1) { ?>
				<div class="form-group" id="area_id">
					<label for="input-text" class="col-sm-5 control-label">Area</label>
					<div class="col-sm-7">
						<select id="area_id" class="form-control input-sm" style="width:100%" name="area_id">
							<option value="">Select Area...</option>
							@foreach ($area as $ar)
							<option value="{{ $ar['id'] }}">{{ $ar['name'] }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<?php } else { ?>
					<input type="hidden" name="area_id" id="area_id">
				<?php } ?>
				
				<?php if($formdata['account_phone']==1) { ?>
				<div class="form-group" id="phone">
					<label for="input-text" class="col-sm-5 control-label">Phone</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="phone" name="phone" placeholder="Phone">
					</div>
				</div>
				<?php } else { ?>
					<input type="hidden" name="phone" id="phone">
				<?php } ?>
				
				<?php if($formdata['account_email']==1) { ?>
				<div class="form-group" id="email">
					<label for="input-text" class="col-sm-5 control-label">Email</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="email" name="email" placeholder="Email">
					</div>
				</div>
				
				<?php } else { ?>
					<input type="hidden" name="email" id="email">
				<?php } ?>

				<?php if($formdata['account_trn']==1) { ?>										
				<div class="form-group" id="vat_no">
					<label for="input-text" class="col-sm-5 control-label">TRN No</label>
					<div class="col-sm-7">
						<input type="text" class="form-control input-sm" id="vat_no" name="vat_no" placeholder="TRN No.">
					</div>
				</div>
				<?php } else { ?>
					<input type="hidden" name="vat_no" id="vat_no">
				<?php } ?>
				
				<?php if($formdata['account_vat']==1) { ?>
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">VAT Assignable?</label>
					<div class="col-sm-7">
						<label class="radio-inline iradio">
							<input type="checkbox" class="onacnt_icheck" id="vat_assign" name="vat_assign" value="1">
					</div>
				</div>
				<?php } else { ?>
					<input type="hidden" name="vat_assign" id="vat_assign">
				<?php } ?>


				<?php if($formdata['vat_per']==1) { ?>
				<div class="form-group" id="vatPntg">
					<label for="input-text" class="col-sm-5 control-label">VAt Percentage</label>
					<div class="col-sm-7">
						<input type="number" step="any" class="form-control input-sm" id="vat_perc" name="vat_perc" placeholder="VAT %">
					</div>
				</div>
				<?php } else { ?>
					<input type="hidden" name="vat_perc" id="vat_perc">
				<?php } ?>
				
				@if($isdept)
				<div class="form-group">
					<label for="input-text" class="col-sm-5 control-label">Department</label>
					<div class="col-sm-7">
						<select id="country_id" class="form-control input-sm" style="width:100%" name="country_id">
							<option value="">Select Country...</option>
							@foreach($departments as $department)
							<option value="{{ $department->id }}">{{ $department->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
				
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
						Account created successfully. Click 'Select Account'.
					</p>
				</div>
				
				<a href="" class="btn btn-primary custRow" id="cususe" data-dismiss="modal">
						<span class="btn-label">
					</span> Select Account
				</a>
			</div>
		</div>
	</div>
</div>
					
<script>
$(function() {
	
	$('#newAccountFrm').toggle();
	$('#newAccountFrm #sucessmsg').toggle();
	
	$('.createAcc').on('click', function() {
	    if( $('#newAccountFrm').is(":hidden") )
			$('#newAccountFrm').toggle();
	if( $('#accountList').is(":visible") )
		$('#accountList').toggle();
	if( $('#newAccountFrm #sucessmsg').is(":visible") )	
		$('#newAccountFrm #sucessmsg').toggle();
	});
	
	$('.listCust').on('click', function() {
	    	if( $('#newAccountFrm').is(":visible") )
		$('#newAccountFrm').toggle();
		if( $('#accountList').is(":hidden") )
		$('#accountList').toggle();
			if( $('#newAccountFrm #sucessmsg').is(":visible") )	
		$('#newAccountFrm #sucessmsg').toggle();
	});
	
	$('#frmAccount #create').on('click', function(e){
		
		var cat = $('#frmAccount #category_id option:selected').val();
		var grp = $('#frmAccount #group_id option:selected').val();
		var ac = $('#frmAccount #account_id').val();
		var name = $('#frmAccount #supplier_name1').val();
		var adrs = $('#frmAccount #address').val();
		var ar = $('#frmAccount #area_id option:selected').val();
		var cn = $('#frmAccount #country_id option:selected').val();
		var ph = $('#frmAccount #phone').val();
		var vt = $('#frmAccount #vat_no').val();
		var ct = $('#frmAccount #category').val();
		var tr = $('#frmAccount #transaction option:selected').val();
		var em = $('#frmAccount #email').val();
		var vp = $('#frmAccount #vat_perc').val();
		if ($('#vat_assign').is(":checked"))
			var va = 1;
		else
			var va = 0;
		
		var dpt = '';
		if($('#department_id').length) {
			dpt = $( "#department_id option:selected" ).val();
		}
		
		if(name=="") {
			alert('Account name is required!');
			return false;
		} else {		
			$('#newAccountFrm #addressDtls').toggle();
			
			$.ajax({
				url: "{{ url('account_master/ajax_create_acc/') }}",
				type: 'get',
				data: 'category_id='+cat+'&group_id='+grp+'&account_id='+ac+'&master_name='+name+'&address='+adrs+'&area_id='+ar+'&country_id='+cn+'&phone='+ph+'&vat_no='+vt+'&category='+ct+'&transaction='+tr+'&email='+em+'&vtas='+va+'&vtpr='+vp+'&department_id='+dpt,
				success: function(data) { console.log(data);
					if(data > 0) {
						$('#newAccountFrm #sucessmsg').toggle( function() {
							$('#newAccountFrm #cususe').attr("data-id",data);
							$('#newAccountFrm #cususe').attr("data-name",name);
						});
					} else if(data == 0) {
						$('#addressDtls').toggle();
						alert('Account name already exist!');
						return false;
					} else { alert(data);exit;
						$('#addressDtls').toggle();
						//$('#sucessmsg').toggle();
						alert('Something went wrong, Account failed to add!');
						return false;
					}
				}
			})
		}
	});
			
	$('#newAccountFrm #actype_id').on('change', function(e){ ;
		var type_id = e.target.value;

		$.get("{{ url('accategory/getcategory/') }}/" + type_id, function(data) {
			$('#newAccountFrm #category_id').empty();
			 $('#newAccountFrm #category_id').append('<option value="">Select Account Category...</option>');
			$.each(data, function(value, display){
				 $('#newAccountFrm #category_id').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
	});
	
	$('#newAccountFrm #category_id').on('change', function(e){
		var cat_id = e.target.value;

		$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
			$('#newAccountFrm #group_id').empty();
			 $('#newAccountFrm #group_id').append('<option value="">Select Account Group...</option>');
			$.each(data, function(value, display){
				 $('#newAccountFrm #group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
			});
		});
	});
	
	$('#newAccountFrm #group_id').on('change', function(e){
		var group_id = e.target.value;
		$.get("{{ url('account_master/getcode/') }}/" + group_id, function(data) {
			var val = $.parseJSON(data);
			$('#newAccountFrm #account_id').val(val.code);
			$('#newAccountFrm #category').val(val.category);
			cat = val.category;
			if( cat=='CUSTOMER' || cat=='PDCR'){ 
				$('#transaction').find('option').remove().end().append('<option value="Dr">Dr</option>');
			} 
			else if(cat=='SUPPLIER' || cat=='PDCI') {
				$('#transaction').find('option').remove().end().append('<option value="Cr">Cr</option>');
			} else {
				$('#transaction').find('option').remove().end().append('<option value="">Select</option><option value="Dr">Dr</option><option value="Cr">Cr</option>');
			}
			if( cat=='CUSTOMER' || cat=='SUPPLIER' ){ 
			$('#newAccountFrm #address').show();
			$('#newAccountFrm #country_id').show();
			$('#newAccountFrm #area_id').show();
			$('#newAccountFrm #phone').show();
			$('#newAccountFrm #email').show();
			$('#newAccountFrm #vat_no').show();
			}
			else{
			$('#newAccountFrm #address').hide();
			$('#newAccountFrm #country_id').hide();
			$('#newAccountFrm #area_id').hide();
			$('#newAccountFrm #phone').hide();
			$('#newAccountFrm #email').hide();
			$('#newAccountFrm #vat_no').hide();
			}
		});
	});
		
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	/*var dtInstance = $("#tableAcntList").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});*/

	
	var dtInstance = $("#tableAcntList").DataTable({
		"processing": true,
		"serverSide": true,
		"bDestroy": true,
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
			//{ "data": "op_balance" }
		]	
		
	});



});
</script>
