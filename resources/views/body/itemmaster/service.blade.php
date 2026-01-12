<div class="panel panel-success filterable" id="newItemListLb">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Item ListLB
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="lbnum" value="{{$num}}">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createItm">Create Item</button>
		<table class="table horizontal_table table-striped" id="postsLb">
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

<div class="panel panel-success filterable" id="newItemFrmLb">
		<div class="panel-heading">
			<h3 class="panel-title">
				<i class="fa fa-fw fa-columns"></i> New Item
			</h3>
		</div>
		<div class="panel-body">  <button type="button" class="btn btn-primary listItm">List Item</button>
		<hr/>
			<div class="col-xs-10">
				<div id="itemDtlsLb">
				<form class="form-horizontal" role="form" method="POST" name="frmItemLb" id="frmItemLb">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Code</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="lbitem_code" name="item_code" placeholder="Item Code">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Decription</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="lbdescription" name="description" placeholder="Item Description">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Item Class</label>
						<div class="col-sm-7">
							<select id="lbitem_class" class="form-control select2" style="width:100%" name="item_class">
								<option value="2">Service</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">Unit</label>
						<div class="col-sm-7">
							<select id="lbunit" class="form-control select2 itemunit" style="width:100%" name="unit">
								@foreach ($units as $unit)
								<option value="{{ $unit['id'] }}">{{ $unit['unit_name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label">VAT%</label>
						<div class="col-sm-7">
							<select id="lbvat" class="form-control select2 itemunit" style="width:100%" name="vat">
								@foreach ($vats as $vat)
								<option value="{{ $vat['percentage'] }}">{{ $vat['name'] }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="input-text" class="col-sm-5 control-label"></label>
						<div class="col-sm-7">
							<button type="button" class="btn btn-primary" id="createILb">Create</button>
						</div>
					</div>
				 </form>
				</div>
				
				<div id="sucessmsgItmLb"><br/>
					<div class="alert alert-success">
						<p>
							Item created successfully. Click 'Select Item'.
						</p>
					</div>
					
					<a href="" class="btn btn-primary itemRow" id="itmuseLb" data-dismiss="modal">
							<span class="btn-label">
						</span> Select Item
					</a>
				</div>
			</div>
		</div>
	</div>
	
<script>
$(function() {
	
	$('#newItemFrmLb').toggle();
	
	$('.createItm').on('click', function() {
		$('#newItemFrmLb').toggle();
		$('#newItemListLb').toggle();
		$('#sucessmsgItmLb').toggle();
	});
	
	$('.listItm').on('click', function() {
		$('#newItemFrmLb').toggle();
		$('#newItemListLb').toggle();
	});
	
	$('#createILb').on('click', function(e){
		
		var ic = $('#frmItemLb #lbitem_code').val();
		var dc = $('#frmItemLb #lbdescription').val();
		var cl = $('#frmItemLb #lbitem_class option:selected').val();
		var ut = $('#frmItemLb #lbunit option:selected').val();
		var vt = $('#frmItemLb #lbvat option:selected').val();
		var un = $("#frmItemLb #lbunit option:selected").text();
		if(ic=="") {
			alert('Item code is required!');
			return false;
		} else if(dc=="") {
			alert('Item description is required!');
		} else {				
			$('#itemDtlsLb').toggle();
			
			$.ajax({
				url: "{{ url('itemmaster/ajax_create/') }}",
				type: 'get',
				data: 'item_code='+ic+'&description='+dc+'&class_id='+cl+'&unit='+ut+'&vat='+vt+'&uname='+un,
				success: function(data) { console.log(data);
					
					if(data > 0) {
						$('#sucessmsgItmLb').toggle( function() {
							$('#itmuseLb').attr("data-id",data);
							$('#itmuseLb').attr("data-name",dc);
							$('#itmuseLb').attr("data-code",ic);
							$('#itmuseLb').attr("data-unit",ut);
							$('#itmuseLb').attr("data-vat",vt);
						});
					} else if(data == 0) {
						$('#itemDtlsLb').toggle();
						alert('Item code/name already exist!');
						return false;
					} else {
						$('#itemDtlsLb').toggle();
						alert('Something went wrong, Item failed to add!');
						return false;
					}
				}
			})
		}
	});
	
	$(function() {
            
            var dtInstance = $("#postsLb").DataTable({
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
	 
	/* var inputMapper = {
		"name": 1,
		"position": 2,
		"office": 3,
		"age": 4
	};
	var dtInstance = $("#tableItemListLb").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true
	});
	$("input").on("input", function() {
		var $this = $(this);
		var val = $this.val();
		var key = $this.attr("name");
		dtInstance.columns(inputMapper[key] - 1).search(val).draw();
	}); */
});
</script>