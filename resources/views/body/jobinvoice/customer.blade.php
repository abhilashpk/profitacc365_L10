
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Customer List
                            </h3>
                        </div>
                        <div class="panel-body">
                           
							<input type="hidden" name="num" id="num" value="{{$num}}">
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableCustList">
                                    <thead>
                                    <tr>
                                        <th>Account ID</th>
                                        <th>Account Name</th>
                                        <th>Balance</th>
                                        <th>Open Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($customers as $customer)
                                    <tr>
                                        <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-dismiss="modal" <?php if($num!=null) { ?>onclick="selectCust(this)"<?php } ?>>{{$customer->account_id}}</a></td>
                                        <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-dismiss="modal" <?php if($num!=null) { ?>onclick="selectCust(this)"<?php } ?>>{{$customer->master_name}}</a></td>
                                        <td>{{ number_format($customer->cl_balance, 2, '.', ',') }}</td>
                                        <td>{{ number_format($customer->op_balance, 2, '.', ',') }}</td>
                                    </tr>
                                   @endforeach
                                    </tbody>
                                </table>
                            </div>
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
	var dtInstance = $("#tableCustList").DataTable({
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