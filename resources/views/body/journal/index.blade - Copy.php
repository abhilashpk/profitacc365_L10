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
                 Journal
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
							@permission('jv-create')
                             <a href="{{ url('journal/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endpermission
                        </div>
                    </div>
                    <div class="panel-body">
						<div class="row">
                                <div class="col-xs-6">
                                </div>
                            </div>
                        <div class="table-responsive">
								<table class="table table-striped" id="tableJVlist">
                                    <thead>
                                    <tr>
                                        <th>JV. No</th>
                                        <th>Type</th>
										<th>Date</th>
										<th>Description</th>
										<th>Amount</th>
										<th></th>
										<th></th>
										<th></th>
                                    </tr>
                                    </thead>
                                </table>
								
                                <table class="table table-striped" id="tableAcsettings" border="0">
                                    <thead>
                                    <tr>

                                        <th>JV. No</th>
                                        <th>Type</th>
										<th>Description</th>
										<th>Date</th>
										<th>Amount</th>
										
										<th></th><th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($journals as $journal)
									
                                    <tr>
                                        <td>{{ $journal->voucher_no }}</td>
                                        <td>{{ $journal->voucher_type  }}</td>
										<td>{{ $journal->description }}</td>
										<td>{{ date('d-m-Y',strtotime($journal->voucher_date)) }}</td>
										<td>{{ number_format($journal->credit,2) }}</td>
                                       
										<td>
											@permission('jv-edit')<p><button class="btn btn-primary btn-xs" onClick="location.href='{{ url('journal/edit/'.$journal->id)}}'"><span class="glyphicon glyphicon-pencil"></span></button></p>@endpermission
										</td>
									 <!--	<td>
											@permission('jv-delete')<p><button class="btn btn-danger btn-xs delete" onClick="funDelete('{{ $journal->id }}')"><span class="glyphicon glyphicon-trash"></span></button></p>@endpermission
										</td>-->
										<td>
											@permission('jv-print')<p><a href="{{url('journal/print/'.$journal->id.'/'.$prints[0]->id)}}" target='_blank'  role='menuitem' class="btn btn-primary btn-xs"><span class="fa fa-fw fa-print"></span></a></p>@endpermission
										</td>
                                    </tr>
									@endforeach
                                   <!-- @if (count($journals) == 0)
									</tbody>
									<tbody><tr class="odd danger"><td valign="top" colspan="7" class="dataTables_empty">No matching records found</td></tr></tbody>
									@endif
                                    </tbody>-->
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

$(function() {
		
		var dtInstance = $("#tableJVlist").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"ajax":{
					 "url": "{{ url('journal/paging/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			{ "data": "voucher_no" },
			{ "data": "voucher_type" },
			{ "data": "voucher_date" },
			{ "data": "description" },
			{ "data": "amount" },
			@permission('jv-edit'){ "data": "edit","bSortable": false },@endpermission
			@permission('jv-print'){ "data": "print","bSortable": false },@endpermission
			@permission('jv-delete'){ "data": "delete","bSortable": false }@endpermission
		]	
		  
		});
 });
 
function funDelete(id) {
	var con = confirm('Are you sure delete this journal?');
	if(con==true) {
		var url = "{{ url('journal/delete/') }}";
		location.href = url+'/'+id+'/JV';
	}
}
</script>

@stop
