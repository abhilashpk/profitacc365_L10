<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
<div class="panel panel-success filterable" id="newItemAsmList">
	<div class="panel-heading">
		<h3 class="panel-title">
			<i class="fa fa-fw fa-columns"></i> Consignment Location
		</h3>
	</div>
	 <div class="panel-body">
	<input type="hidden" name="num" id="numasm" value="{{$num}}">
	<input type="hidden" name="clocid" id="clocid">
	<input type="hidden" name="clocqty" id="clocqty">

	<button type="button" class="btn btn-primary add-loc-qty" data-dismiss="modal">Add</button> 
		<table class="table horizontal_table table-striped" id="tblConLoc">
			<thead>
			<tr>
				<th></th>
				<th>Code</th>
				<th>Location</th>
				<th>Stock</th>
				<th>Quantity</th>
			</tr>
			</thead>
		</table>

	</div>
	</div>
</div>

<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script>
$(function() {
	//var locid = [];
	//var locqty = [];
	
	//$(".req-qty").attr("disabled", "disabled"); 
	
	$(function() {
            
		var dtInstance = $("#tblConLoc").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
					 "url": "{{ url('itemmaster/conloc_data/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}", "num":{{$num}}, "rowid":"{{$row}}","custid":{{$cust}},"itemid":{{$itemid}} }
				   },
			"columns": [
				{ "data": "opt","bSortable": false },
				{ "data": "code" },
				{ "data": "name" },
				{ "data": "stock" },
				{ "data": "req_qty" }
			]
		});
     });
	 
	 /* $(document).on('change', '.chk-locid', function(e) {  
		if( $(this).is(":checked") ) { 
			locid.push($(this).val());
			locqty.push( $('#clqty_'+$(this).val().toString()).val() );
		} else {
			var a = locid.indexOf($(this).val()); 
			removeA(locid, $(this).val());
			
			locqty.splice(a,1);
		}
		 $('#clocid').val(locid);
		 $('#clocqty').val(locqty);
	 }); */
	 
	/*  $(document).on('keyup', '.req-qty', function(e) {
		 
	 }); */
	
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

function btnExport() {  
	 
	var id = [];
	$("input[name='lcid[]']:checked").each(function(){
		id.push($(this).val());
	});
	location.href = purl;
}
</script>