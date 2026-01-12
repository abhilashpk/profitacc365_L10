

@section('header_styles')
   		
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
@stop


		
      
	
		
       
						
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableCon">
                                    <thead>
                                    <tr>
                                        <th>Connection No </th>
                                        <th>Building </th>
										<th>Flat</th>
                                        <th>Tenant</th>
                                        <th>Reading Date</th>
                                        <th></th>
										{{--<th></th><th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($reading as $row)
                                    <tr>
                                         <td >{{$row->connection_no}}</td>
                                        <td >{{$row->buildingcode}}</td>
										<td>{{$row->flat}}</td>
                                        <td>{{$row->master_name}}</td>
                                        <td>{{date('d-m-Y',strtotime($row->created_at))}}</td>
											{{--<td>
										<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href="{{ url('contract-connection/edit/'.$row->id) }}" role='menuitem'>Edit</a></li>
												<li role='presentation'><a href="{{ url('contract-connection/reading/'.$row->id) }}" role='menuitem'>Reading</a></li>
												<li role='presentation'><a href="{{ url('contract-connection/edit/'.$row->id) }}" role='menuitem'>Disconnect</a></li>
											</ul>
										</div>
											</td>--}}
                                        <td>
                                        {{--<p><a href={{url('contract-connection/print-read/'.$row->id.'/32')}} target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>--}}
											
											<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<span class='fa fa-fw fa-print'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href="{{ url('contract-connection/print-read/'.$row->id.'/32') }}" target="_blank" role='menuitem'>Print Bill</a></li>
												
												<li role='presentation'><a href="{{ url('customer_receipt/print2/'.$row->receipt_voucher_id.'/25') }}" target="_blank" role='menuitem'>Print RV</a></li>
											</ul>
										</div>
											
                                        </td>
										{{--<td>
										<p>
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $row->id }}')"><span
															class="glyphicon glyphicon-trash"></span></button>
											</p>
										</td>--}}
                                    </tr>
									@endforeach
                                    
                                    </tbody>
                                </table>
                            </div>
                   

{{-- page level scripts --}}
@section('footer_scripts')

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

$(function() {

	var dtInstance = $("#tableCon").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,null,null ], //{ "bSortable": false },{ "bSortable": false }
		"aaSorting": [],
		"order": [[ 0, "desc" ]]
		//"scrollX": true,
	});
	
});


function funDelete(id) {
	var con = confirm('Are you sure delete this connection?');
	if(con==true) {
		var url = "{{ url('contract-connection/delete/') }}";
	 location.href = url+'/'+id;
	}
}


</script>

@stop
