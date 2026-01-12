


<table class="table table-striped" id="tableBank">
	<thead>
		<tr>
        
			<th>Vehicle No</th>
			<th>Vehicle Name</th>
			
			<th>Present Km</th>
			
			<th>Next Km</th>
			<th>Next Due</th>
		
			
		</tr>
	</thead>
		<tbody>
		<?php foreach($docs as $row) { ?>
		<tr>
		
			<td>{{$row->reg_no}}</td>
			<td>{{$row->vehicle}}</td>
			
			<td>{{$row->present_km}}</td>
			
			<td>{{$row->next_km}}</td>
			<td>{{date('d-m-Y', strtotime($row->expiry_date))}}</td>
		
		</tr>
		<?php } ?>
	
			@if (count($docs) === 0)
	</tbody>
	<tbody><tr class="odd danger"><td valign="top" colspan="6" class="dataTables_empty">No matching records found</td></tr></tbody>
	@endif
	</tbody>

</table>
<script>

$(function() {

	var dtInstance = $("#tableBank").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
		"aaSorting": [],
		//"order": [[ 1, "desc" ]]
		//"scrollX": true,
	});
	
});


</script>




<div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10" id="accountList">
		<button type="button" class="btn btn-primary createAcc">Create Account</button>
		<table class="table horizontal_table table-striped" id="tableAcntList">
			<thead>
			<tr>
				<th>Account ID</th>
				<th>Account Name</th>
				<th>Balance</th>
				<th>Open Balance</th>
			</tr>
			</thead>
			<input type="hidden" name="num" id="num" value="{{$num}}">
			<tbody>
			@foreach($accounts as $account)
			<tr>
				<td><a href="" class="accountRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-vat="{{$account->vat_percentage}}" data-dismiss="modal">{{$account->account_id}}</a></td>
				<td><a href="" class="accountRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-vat="{{$account->vat_percentage}}" data-dismiss="modal">{{$account->master_name}}</a></td>
				<td>{{ number_format($account->cl_balance, 2, '.', ',') }}</td>
				<td>{{ number_format($account->op_balance, 2, '.', ',') }}</td>
			</tr>
		   @endforeach
			</tbody>
		</table>
	</div>
</div>

					
<script>
$(function() {

	var dtInstance = $("#tableAcntList").DataTable({
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