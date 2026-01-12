<div class="panel panel-success filterable" id="newItemList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Item List
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="num" value="{{$num}}">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createItm">Create Item</button>
		<table class="table horizontal_table table-striped" id="tableItemList">
			<thead>
			<tr>
				<th>Item ID</th>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Cost Avg.</th>
				<th>Sales Price</th>
			</tr>
			</thead>
			<tbody>
			@foreach($items as $item)
			<tr>
				<td><a href="" class="itemRow" data-id="{{$item->id}}" data-code="{{$item->item_code}}" data-name="{{$item->description}}" data-unit="{{$item->unit_name}}" data-vat="{{$item->vat}}" data-costavg="{{$item->cost_avg}}" data-purcost="{{$item->pur_cost}}" data-type="{{$item->class_id}}" data-dismiss="modal">{{$item->item_code}}</a></td>
				<td><a href="" class="itemRow" data-id="{{$item->id}}" data-code="{{$item->item_code}}" data-name="{{$item->description}}" data-unit="{{$item->unit_name}}" data-vat="{{$item->vat}}" data-costavg="{{$item->cost_avg}}" data-purcost="{{$item->pur_cost}}" data-type="{{$item->class_id}}" data-dismiss="modal">{{$item->description}}</a></td>
				<td>{{$item->cur_quantity}}</td>
				<td>{{number_format($item->cost_avg,2)}}</td>
				<td>{{number_format($item->sell_price,2)}}</td>
			</tr>
		   @endforeach
			</tbody>
		</table>

	</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newItemFrm">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-columns"></i> New Item
			</h3>
		</div>
		<div class="panel-body">  <button type="button" class="btn btn-primary listItm">List Item</button>
		<hr/>
			<div class="col-xs-10">
				<div id="itemDtls">
				<form class="form-horizontal" role="form" method="POST" name="frmItem" id="frmItem">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Code</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="item_code" name="item_code" placeholder="Item Code">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Decription</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="description" name="description" placeholder="Item Description">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Class</label>
						<div class="col-sm-7">
							<select id="item_class" class="form-control select2" style="width:100%" name="item_class">
								<option value="1">Stock</option>
								<option value="2">Service</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Unit</label>
						<div class="col-sm-7">
							<select id="unit" class="form-control select2 itemunit" style="width:100%" name="unit">
								@foreach ($units as $unit)
								<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">VAT%</label>
						<div class="col-sm-7">
							<select id="vat" class="form-control select2 itemunit" style="width:100%" name="vat">
								@foreach ($vats as $vat)
								<option value="{{ $vat['percentage'] }}">{{ $vat['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label"></label>
						<div class="col-sm-7">
							<button type="button" class="btn btn-primary" id="createI">Create</button>
						</div>
					</div>
				 </form>
				</div>
				
				<div id="sucessmsgItm"><br/>
					<div class="alert alert-success">
						<p>
							Item created successfully. Click 'Select Item'.
						</p>
					</div>
					
					<a href="" class="btn btn-primary itemRow" id="itmuse" data-dismiss="modal">
							<span class="btn-label">
						</span> Select Item
					</a>
				</div>
			</div>
		</div>
	</div>
	
<script>
$(function() {
	
	$('#newItemFrm').toggle();
	
	$('.createItm').on('click', function() {
		$('#newItemFrm').toggle();
		$('#newItemList').toggle();
		$('#sucessmsgItm').toggle();
	});
	
	$('.listItm').on('click', function() {
		$('#newItemFrm').toggle();
		$('#newItemList').toggle();
	});
	
	$('#createI').on('click', function(e){
		
		var ic = $('#frmItem #item_code').val();
		var dc = $('#frmItem #description').val();
		var cl = $('#frmItem #item_class option:selected').val();
		var ut = $('#frmItem #unit option:selected').val();
		var vt = $('#frmItem #vat option:selected').val();
		var un = $("#frmItem #unit option:selected").text();
		if(ic=="") {
			alert('Item code is required!');
			return false;
		} else if(dc=="") {
			alert('Item description is required!');
		} else {				
			$('#itemDtls').toggle();
			
			$.ajax({
				url: "{{ url('itemmaster/ajax_create/') }}",
				type: 'get',
				data: 'item_code='+ic+'&description='+dc+'&class_id='+cl+'&unit='+ut+'&vat='+vt+'&uname='+un,
				success: function(data) { console.log(data);
					
					if(data > 0) {
						$('#sucessmsgItm').toggle( function() {
							$('#itmuse').attr("data-id",data);
							$('#itmuse').attr("data-name",dc);
							$('#itmuse').attr("data-code",ic);
							$('#itmuse').attr("data-unit",ut);
							$('#itmuse').attr("data-vat",vt);
						});
					} else if(data == 0) {
						$('#itemDtls').toggle();
						alert('Item code/name already exist!');
						return false;
					} else {
						$('#itemDtls').toggle();
						alert('Something went wrong, Item failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	$(function() {
            
            var dtInstance = $("#posts").DataTable({
				"processing": true,
				"serverSide": true,
				"ajax":{
						 "url": "{{ url('itemmaster/paging/') }}",
						 "dataType": "json",
						 "type": "POST",
						 "data":{ _token: "{{csrf_token()}}"}
					   },
				"columns": [
                { "data": "item_code" },
                { "data": "description" },
                { "data": "quantity" },
                { "data": "cost_avg" },
                { "data": "last_purchase_cost" },
				{ "data": "received_qty" },
				{ "data": "issued_qty" },
				{ "data": "edit","bSortable": false },
				{ "data": "delete","bSortable": false }
            ]	
              
            });
     });
	 
	var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tableItemList").DataTable({
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