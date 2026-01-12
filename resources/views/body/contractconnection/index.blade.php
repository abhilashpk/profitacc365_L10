@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
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

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <!--section starts-->
            <h1>
            Contract Connection
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Real Estate
                    </a>
                </li>
                <li>
                    <a href="#">Contract Connection</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
	
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Contract Connection
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('contract-connection/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						<div class="row">
                               
                            </div>
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableCon">
                                    <thead>
                                    <tr>
                                         <th>Connection No </th>
                                        <th>Building </th>
										<th>Flat</th>
                                        <th>Tenant</th>
                                        <th>Balance</th>
                                        <th></th>
										<th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($connection as $row)
                                    <tr>
                                         <td>{{$row->connection_no}}</td>
                                        <td>{{$row->buildingcode}}</td>
										<td>{{$row->flat}}</td>
                                        <td>{{$row->master_name}}</td>
                                        <td>{{$row->cl_balance}}</td>
										<td>
										<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-shield'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href="{{ url('contract-connection/edit/'.$row->id) }}" role='menuitem'>Edit</a></li>
												<li role='presentation'><a href="" role='menuitem' class="getBill" data-toggle='modal' data-target='#amtview_modal' data-id='{{$row->id}}'>Statement</a></li>
												{{--<li role='presentation'><a href="{{ url('contract-connection/reading/'.$row->id) }}" role='menuitem'>Reading</a></li>
												<li role='presentation'><a href="{{ url('contract-connection/disconnect/'.$row->id) }}" role='menuitem'>Disconnect</a></li>--}}
											</ul>
										</div>
										</td>
                                        <td>
										
										<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<span class='fa fa-fw fa-print'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												<li role='presentation'><a href="{{ url('contract-connection/print/'.$row->id.'/23') }}" target="_blank" role='menuitem'>Print Invoice</a></li>
												@if($row->is_rv==1)
												<li role='presentation'><a href="{{ url('customer_receipt/printgrp/'.$row->rv_id) }}" target="_blank" role='menuitem'>Print RV</a></li>
												@endif
												<li role='presentation'><a href="{{ url('contract-connection/print_all/'.$row->id) }}" target="_blank" role='menuitem'>Print All</a></li>
											</ul>
										</div>
										{{--
                                        <p><a href={{url('contract-connection/print/'.$row->sin_no)}} target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>
                                        --}}
										</td>
										<td>
										<p>
												<button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $row->id }}')"><span
															class="glyphicon glyphicon-trash"></span></button>
											</p>
										</td>
                                    </tr>
									@endforeach
                                    
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
<div id="amtview_modal" class="modal fade animated" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Statement Transaction</h4>
			</div>
			<div class="modal-body" id="bill_data">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div> 

        </section>
@stop

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
		"aoColumns": [null,null,null,null,null,null,{ "bSortable": false },{ "bSortable": false } ],
		"aaSorting": [],
		//"order": [[ 1, "desc" ]]
		//"scrollX": true,
	});
	
	var stsurl = "{{ url('contract-connection/get_billdata/') }}";
	$(document).on('click','.getBill', function() { 
		$('#bill_data').load(stsurl+'/'+$(this).attr("data-id"), function(result) {
			$('#myModal').modal({show:true});
		});
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
