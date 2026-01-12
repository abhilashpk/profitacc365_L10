<div class="panel panel-success filterable" id="newItemList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Item List
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createItm">Create Item</button>
		<table class="table horizontal_table table-striped" id="postsrw">
			<thead>
			<tr>
				<th>Item ID</th>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Cost Avg.</th>
				<th>Sales Price</th>
			</tr>
			</thead>
		</table>

	</div>
	</div>
</div>
<script>
$(function() {
	

	$(function() {
		
		var dtInstance = $("#postsrw").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
					 "url": "{{ url('itemmaster/item_data/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}", "num":{{$num}}, "mod":"{{$mod}}" }
				   },
			"columns": [
				{ "data": "item_code" },
				{ "data": "description" },
				{ "data": "quantity" },
				{ "data": "cost_avg" },
				{ "data": "sale_price" }
			]	
		  
		});
	 });
 });

</script>