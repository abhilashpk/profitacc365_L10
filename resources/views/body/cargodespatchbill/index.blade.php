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
             Cargo Despatch Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-building-o"></i> Cargo Entry
                    </a>
                </li>
                <li>
                    <a href="#"> Cargo Despatch Report</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i>  Cargo Despatch Report
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('cargo_despatchbill/add') }}" class="btn btn-primary btn-sm">
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
                                <table class="table table-striped" id="tblCargoDsphbill">
                                    <thead>
                                    <tr>
                                        <th>Despatch No</th>
                                        <th>Despatch Date </th>
										<th>Clearing Agent</th>
										<th>Vehicle No</th>
										<th>Driver</th>
										<th>Driver Mobile(UAE)</th>
										<th>Driver Mobile(KSA)</th>
										<th></th>
										<th></th><th></th>
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
		 <section class="content">
            <div class="row">
				<div class="col-lg-12">
				   <div class="panel panel-info">
							<div class="panel-heading clearfix">
								<h3 class="panel-title pull-left m-t-6">
									<i class="fa fa-fw fa-columns"></i> Despatch Report
								</h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" name="frmReport" id="frmReport" target="_blank" action="{{ url('cargo_despatchbill/search') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<div class="row">
										<div class="col-xs-8">
											<span>Despatch No:</span>
											<input type="text" name="from_no" id="from_no" required class="form-control" autocomplete="off">
											
										</div>
										
											
										   
											<div class="col-xs-9" align="right"><button type="submit" class="btn btn-primary">Search</button></div>
										
									</div>
								</form>
								
							</div>
						</div>
				</div>
			</div>
        </section>
		
		<div id="status_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Cargo Despatch Report Status</h4>
					</div>
					<div class="modal-body" id="statusForm">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
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
            
	var dtInstance = $("#tblCargoDsphbill").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('cargo_despatchbill/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "despatch_no" },
			{ "data": "despatch_date" },
			{ "data": "clear_agent" },
			{ "data": "vehicle_no" },
			{ "data": "driver" },
			//{ "data": "amount" },
			{ "data": "mob_uae" },
			{ "data": "mob_ksa" },
			{ "data": "status","bSortable": false },
			{ "data": "edit","bSortable": false },
			{ "data": "delete","bSortable": false }
			
		]	
	});
		  
});

	
function funDelete(id) {
	var con = confirm('Are you sure delete this bill entry?');
	if(con==true) {
		var url = "{{ url('cargo_despatchbill/delete/') }}";
		location.href = url+'/'+id;
	}
}


</script>

@stop
