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
                                <i class="fa fa-fw fa-columns"></i> Sales Split List
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    &nbsp; <button type="button" class="btn btn-info" onClick="setTransfer()">Transfer</button>
                                </div>
                            </div>
							
							 <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="tableInvDataList">
                                    <thead>
                                    <tr>
										<th></th>
										<th>SS. No</th>
										<th>SS. Date</th>
										<th>Customer</th>
										<th>Amount</th>
										<th></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
														
                        </div>
                    </div>
                </div>
				
				<div id="item_modal" class="modal fade animated" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Sales Invoice</h4>
							</div>
							<div class="modal-body" id="itm">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
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
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
	$(function() {
		
		var dtInstance = $("#tableInvDataList").DataTable({
			"processing": true,
			"serverSide": true,
			"ajax":{
					 "url": "{{ url('sales_split/paging_split_data/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data": function(data){
						  data._token = "{{csrf_token()}}";
						  @if($did=='')
							  data.dept = 0;
						  @else
							  data.dept = {{$did}};
						  @endif
					  }
					 //"data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "opt","bSortable": false },
			{ "data": "voucher_no" },
			{ "data": "voucher_date" },
			{ "data": "customer" },
			{ "data": "net_total" },
			{ "data": "view" }
		]	
		  
		});
 });
});


 
function setTransfer() {
	
	var checked=false;
	var elements = document.getElementsByName("salesDO");
	for(var i=0; i < elements.length; i++) {
		if(elements[i].checked) {
			checked = true;
		}
	}
	if (!checked) {
		alert('Please select a Sales Split!');
		return checked;
	}
	var item = $("input[name='salesDO']:checked").val();
	//alert(items);
	 invoiceurl = "{{ url('sales_split_return/add/') }}/"+item;
	 
	 if (window.opener != null && !window.opener.closed) {
		   window.opener.location.href = invoiceurl;
      }
	  window.close();
}

$(document).on('click', '.poclk', function(e) {
	var id = $(this).attr("data-id");
	var itmurl = "{{ url('sales_split/item_details/') }}";
	$('#itm').load(itmurl+'/'+id, function(result){ 
		$('#myModal').modal({show:true});
	});
});

</script>