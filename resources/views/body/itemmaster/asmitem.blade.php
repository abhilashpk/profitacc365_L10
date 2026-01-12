<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<div class="panel panel-success filterable" id="newItemAsmList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Item List
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="numasm" value="{{$num}}">
	<input type="hidden" name="asit" id="asit">
	<input type="hidden" name="asqt" id="asqt">
	<div class="table-responsive m-t-10"> <button type="button" class="btn btn-primary createItm">Create Item</button>
	<button type="button" class="btn btn-primary add-asm-item" data-dismiss="modal">Add Assembly Items</button> 
		<table class="table horizontal_table table-striped" id="postsitmsAsm">
			<thead>
			<tr>
				<th></th>
				<th>Item ID</th>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Req.Qty.</th>
			</tr>
			</thead>
		</table>

	</div>
	</div>
</div>

<div class="panel panel-success filterable" id="newItemAsmFrm">
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
							<button type="button" class="btn btn-primary" id="createIasm">Create</button>
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

<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>
$(function() {
	var iids = [];
	var rqty = [];
	var flag1 = false; var flag2 = false;
	$('#newItemAsmFrm').toggle();
	
	$('.createItm').on('click', function() {
		$('#newItemAsmFrm').toggle();
		$('#newItemAsmList').toggle();
		$('#sucessmsgItm').toggle();
	});
	
	$('.listItm').on('click', function() {
		$('#newItemAsmFrm').toggle();
		$('#newItemAsmList').toggle();
	});
	
	$('#createIasm').on('click', function(e){
		
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
            
		var dtInstance = $("#postsitmsAsm").DataTable({
			//stateSave: true,
			"processing": true,
			"serverSide": true,
			"ajax":{
					 "url": "{{ url('itemmaster/asmitem_data/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}", "num":{{$num}}, "mod":"{{$mod}}" }
				   },
			"columns": [
				{ "data": "opt","bSortable": false },
				{ "data": "item_code" },
				{ "data": "description" },
				{ "data": "quantity" },
				{ "data": "req_qty" }
			]
		});
     });
	 
	 /* $(document).on('change', '.chk-itmid', function(e) {  
		if( $(this).is(":checked") ) { 
			iids.push($(this).val());
			rqty.push( $('#rqty_'+$(this).val().toString()).val() );
		} else {
			var a = iids.indexOf($(this).val()); 
			removeA(iids, $(this).val());
			
			rqty.splice(a,1);
			
		}
		
		 $('#asit').val(iids);
		 $('#asqt').val(rqty);
		
		 if(flag1===true && flag2===true) { console.log('sgsgs');
			 iids = [];
			 rqty = [];
		 }
	 }); */
	 
	 $(document).on('change', '.chk-itmid', function(e) {  
		if( $(this).is(":checked") ) { 
			
			$('#rqty_'+$(this).val().toString()).prop("disabled", false);
		} else {
			$('#rqty_'+$(this).val().toString()).prop("disabled", true);
			var a = iids.indexOf($(this).val()); 
			removeA(iids, $(this).val());
			
			rqty.splice(a,1);
			
		}
	 }); 
	 
	 $(document).on('blur', '.req-qty', function(e) { 
		//var iids = [];
		//var rqty = [];
		//$( '.chk-itmid' ).each(function() {
			var res = this.id.split('_');
			var cn = res[1];
			if($('#chk_'+cn).is(":checked")) {
				iids.push( $('#chk_'+cn).val() ); 
				rqty.push( $('#rqty_'+cn).val() );
				flag2 = true;
				 //console.log('cn '+$('#rqty_'+cn).val()); 
			} else {
				var a = iids.indexOf($(this).val()); 
				removeA(iids, $(this).val());
				
				rqty.splice(a,1);
				//console.log(rqty);
			}
		//});
		//console.log('ar '+rqty); 
		$('#asit').val(iids);
		$('#asqt').val(rqty);
		//localStorage.setItem("asm_itm", $('#asit').val());
		//localStorage.setItem("asm_qty", $('#asqt').val());
	 });
	
});

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function btnExport() {  
	 
	var id = [];
	$("input[name='itmid[]']:checked").each(function(){
		id.push($(this).val());
	});
	location.href = purl;
}
</script>