<div class="panel-body">
	
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10">
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
				<td><a href="" class="stockRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-dismiss="modal">{{$account->account_id}}</a></td>
				<td><a href="" class="stockRow" data-id="{{$account->id}}" data-code="{{$account->account_id}}" data-name="{{$account->master_name}}" data-group="{{$account->category}}" data-vatassign="{{$account->vat_assign}}" data-dismiss="modal">{{$account->master_name}}</a></td>
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
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
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