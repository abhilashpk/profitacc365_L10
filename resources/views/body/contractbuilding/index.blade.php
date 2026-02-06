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
            Contract
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Real Estate
                    </a>
                </li>
                <li>
                    <a href="#">Contract</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Contract 
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('contractbuilding/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						<div class="row"><div class="col-sm-3">
                               <select class="form-control" id="sortBy">
									<option value="">Sort by...</option>
									@foreach($buildings as $row)
									<option value="{{$row->buildingcode}}">{{$row->buildingcode}}</option>
									@endforeach
								</select>
								</div>
                            </div>
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableContract">
                                    <thead>
                                    <tr>
										<th>Contract No</th>
										<th>Tenant</th>
                                        <th>Building Code</th>
										<th>Flat No</th>
										<th>Start Date</th>
										<th>Expiry Date</th>
										<th>Status</th>
										<th>Renew</th>
										<!--<th>Close</th>-->
										<th>Settle/Close</th>
										<th>View/Print</th>
										<th>Edit</th>
										@if($consetting==1)<th>Delete</th>@endif
										<th>View All</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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
            
	var dtInstance = $("#tableContract").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('contractbuilding/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			//{ "data": "id" },
			/* { "data": "contract_no" },
			{ "data": "contract_date" },
			{ "data": "customer" },
			{ "data": "building_code" },
			{ "data": "flat_no" },
			{ "data": "start_date" },
			{ "data": "duration" },
			{ "data": "rent" },
			{ "data": "open","bSortable": false },
			{ "data": "edit","bSortable": false },
			{ "data": "delete","bSortable": false } */
           
			{ "data": "contract_no" },
			{ "data": "customer" },
			{ "data": "building_code" },
			{ "data": "flat_no" },
			{ "data": "start_date" },
			{ "data": "exp_date" },
			{ "data": "status" },
			//{ "data": "mailbtn","bSortable": false },
			{ "data": "renew","bSortable": false },
			//{ "data": "close","bSortable": false },
			{ "data": "settle","bSortable": false },
			{ "data": "open","bSortable": false },
			{ "data": "edit","bSortable": false },
			@if($consetting==1){ "data": "delete","bSortable": false },@endif
			{ "data": "attach","bSortable": false }
		]	
		  
	});
	
});
	
	
$(document).on('change','#sortBy', function() {
	var table = $('#tableContract').DataTable();
	table.search( this.value ).draw();
})

function funDelete(id,n) {
	if(n==0) {
		var con = confirm('Are you sure delete this contract?');
		if(con==true) {
			var url = "{{ url('contractbuilding/delete') }}";
		location.href = url+'/'+id;
		}
	} else {
		alert('This contract has history data, You can\'t delete this contract!');
		return false;
	}
}

</script>

@stop
