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
            Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Contract_Connection 
                    </a>
                </li>
                <li>
                    <a href="#">Enquiry</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Enquiry
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('tenantenquiry/add') }}" class="btn btn-primary btn-sm">
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
								<h4>Transfer Pending</h4>
                                <table class="table table-striped" id="tableFlat">
                                    <thead>
                                    <tr>
                                        <th>Enquiry No:</th>
										<th>Tenant</th>
										<th>Building Code</th>
										<th>Flat No:</th>
										<th>Edit</th>
                                        <th>Transfer</th> 
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									@foreach($pendingenq as $row)
                                    <tr>
                                        <td >{{$row->enquiry_no}}</td>
										<td>{{$row->tenant}}</td>
										<td>{{$row->buildingcode}}</td>
										<td>{{$row->flatno}}</td>
                                        <td>
										<p>
												<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('tenantenquiry/edit/'.$row->id) }}'">
												<span class="glyphicon glyphicon-pencil"></span></button>
											</p>
										</td>
                                        <td>
										<p>
												<button class="btn btn-primary btn-xs " onClick="location.href='{{ url('contract-connection/transfer/'.$row->id) }}'">Transfer</button>
											</p>
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
								
								<hr/><br/>
								<h4>Transfer Completed</h4>
                                <table class="table table-striped" id="tableFlat2">
                                    <thead>
                                    <tr>
                                        <th>Enquiry No:</th>
										<th>Tenant</th>
										<th>Building Code</th>
										<th>Flat No:</th>
										<th>Edit</th>
                                        <th>Transfer</th> 
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									@foreach($completed as $row)
                                    <tr>
                                        <td >{{$row->enquiry_no}}</td>
										<td>{{$row->tenant}}</td>
										<td>{{$row->buildingcode}}</td>
										<td>{{$row->flatno}}</td>
                                        <td>
										<p>
												<button class="btn btn-primary btn-xs" onClick="location.href='{{ url('tenantenquiry/edit/'.$row->id) }}'">
												<span class="glyphicon glyphicon-pencil"></span></button>
											</p>
										</td>
                                        <td>
										<p>
												Completed
											</p>
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

	var dtInstance = $("#tableFlat").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
		"aaSorting": [],
		//"order": [[ 1, "desc" ]]
		//"scrollX": true,
	});
	
	var dtInstance = $("#tableFlat2").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
		"aaSorting": [],
		//"order": [[ 1, "desc" ]]
		//"scrollX": true,
	});
	
});

function funDelete(id) {
	var con = confirm('Are you sure delete this enquiry?');
	if(con==true) {
		var url = "{{ url('tenantenquiry/delete/') }}";
	 location.href = url+'/'+id;
	}
}

</script>

@stop
