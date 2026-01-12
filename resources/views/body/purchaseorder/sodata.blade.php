	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <!--end of page level css-->
	<style>
body .table-responsive m-t-10,th,td {
   font-size:12px !important;
}
</style>
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Sales Order
                            </h3>
                        </div>
                        <div class="panel-body">
                           
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="table5">
                                    <thead>
                                    <tr>
										<th></th>
                                        <th>SO. No</th>
										<th>SO. Date</th>
										<th>Customer</th>
                                        <th>Amount</th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($mrdata as $po)
                                    <tr>
										<td><input type="checkbox" name="purchaseOrd[]" value="{{$po->id}}"/></td>
                                        <td>{{$po->voucher_no}}</td>
                                        <td>{{ date('d-m-Y',strtotime($po->voucher_date)) }}</td>
										<td>{{$po->master_name}}</td>
										<td>{{$po->net_total}}</td>
										<td><a href="#" class="poclk" data-id="{{$po->id}}" data-name="{{$po->voucher_no}}" data-toggle="modal" data-target="#item_modal">View Items</a></td>
                                    </tr>
                                   @endforeach
                                    </tbody>
                                </table>
                            </div>
							
							<div id="item_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Sales Order</h4>
                                        </div>
                                        <div class="modal-body" id="itm">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>   
							<input type="hidden" name="url" id="url" value="{{$url}}">
							<button type="button" class="btn btn-info" onClick="setTransfer()">Transfer</button>
                        </div>
                    </div>
                </div>
            </div>

<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->
<script>
function setTransfer() {
	
	var checked=false;
	var elements = document.getElementsByName("purchaseOrd[]");
	for(var i=0; i < elements.length; i++){
		if(elements[i].checked) {
			checked = true;
		}
	}
	if (!checked) {
		alert('Please select atleast one order!');
		return checked;
	}
	var items = [];
	$("input[name='purchaseOrd[]']:checked").each(function(){items.push($(this).val());});
	var curl = $('#url').val();
	
	if(curl=='SO')
		sdourl = "{{ url('purchase_order/add-from-so/') }}/"+items+"/SO";

	 if (window.opener != null && !window.opener.closed) {
		  window.opener.location.href = sdourl;
		   //document.frm.action=sdourl;
		   //document.frm.submit();
      }
	  window.close();
	  
}

$(document).on('click', '.poclk', function(e) {
	var id = $(this).attr("data-id");
	var itmurl = "{{ url('sales_order/item_details/') }}";
	$('#itm').load(itmurl+'/'+id, function(result){ 
		$('#myModal').modal({show:true});
	});
});
</script>