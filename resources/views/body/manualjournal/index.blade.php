@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
        <!--end of page level css-->
		
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
	<link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                 Manual Journal
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-retweet"></i> Transaction
                    </a>
                </li>
                <li>
                    <a href="">Vouchers Entry</a>
                </li>
				<li class="active">
                    Journal
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
                            <i class="fa fa-fw fa-list-alt"></i> Journal Entry List
                        </h3>
                        <div class="pull-right">
							@can('jv-create')
                             <a href="{{ url('manual_journal/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
                        </div>
                    </div>
                    <div class="panel-body">
					<!--	<div class="row">
                                <div class="col-xs-3">
									<select class="form-control" id="searchPdc">
									<option value="">Sort by...</option>
									<option value="JV">JV</option>
									<option value="PIN">PIN</option>
									<option value="SIN">SIN</option>
								</select>
								</div>
                          </div>-->
                        <div class="table-responsive">
								<table class="table table-striped" id="tableJVlist">
                                    <thead>
                                    <tr>
                                        <th>MJV. No</th>
                                        <!--<th>Type</th>-->
										<th>Date</th>
										<th>Description</th>
										<th>Reference</th>
										<th>Amount</th>
										<th></th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
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


<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<!-- end of page level js -->
<script>
function funPdcr(id) {
	alert('PDC Received already transfered, you can\'t edit/delete');
}

$(function() {
		
		var dtInstance = $("#tableJVlist").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('manual_journal/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			//{ "data": "voucher_type" },
			{ "data": "voucher_date" },
			{ "data": "description" },
			{ "data": "reference" },
			{ "data": "amount" },
			@can('jv-edit'){ "data": "edit","bSortable": false },@endcan
			@can('jv-print'){ "data": "print","bSortable": false },@endcan
			@can('jv-delete'){ "data": "delete","bSortable": false }@endcan
		]	
		  
		});
 });
 
$(document).on('change','#searchPdc', function() {
	var table = $('#tableJVlist').DataTable();
	table.search( this.value ).draw();
}) 

function funDelete(id) {
	var con = confirm('Are you sure delete this journal?');
	if(con==true) {
		var url = "{{ url('manual_journal/delete/') }}";
		location.href = url+'/'+id+'/MJV';
	}
}
</script>

@stop
