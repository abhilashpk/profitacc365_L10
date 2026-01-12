@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
		
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
       <section class="content-header">
            <!--section starts-->
            <h1>
                Customer Enquiry Desk
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-money"></i>Sales CRM
                    </a>
                </li>
                <li>
                    <a href="#">Customer Desk</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Customer Follow Up List
                        </h3>
                        <div class="pull-right">
                             <a href="{{ url('customerleads/add/'.$id) }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableCustLeads">
                                    <thead>
                                    <tr>
                                        <th>#</th>
										<th>Customer</th>
										<th>Follow Up on</th>
										<th>Remark</th>
										<th>Next Follow Up</th>
										<th>Status</th>
										<th></th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($enquiry as $row)
										<tr>
                                        <th>{{$row->id}}</th>
										<th>{{$row->master_name}}</th>
										<th>{{date('d-m-Y',strtotime($row->remark_date))}}</th>
										<th>{{$row->remark}}</th>
										<th>{{date('d-m-Y',strtotime($row->next_date))}}</th>
										<?php
										if($row->status==1)
											$status = '<p class="btn btn-info btn-xs">Customer</p>';
										elseif($row->status==2)
											$status = '<p class="btn btn-primary btn-xs">Enquiry</p>';
										elseif($row->status==3)
											$status = '<p class="btn btn-warning btn-xs">Prospective</p>';
										elseif($row->status==4)
											$status = '<p class="btn btn-danger btn-xs">Archive</p>';
										?>
										<th>{!!$status!!}</th>
										<th><p><a href="{{ url('customerleads/getfollowup/'.$row->id) }}" class='btn btn-primary btn-xs' data-id="{{$row->id}}">Details</a></p></th>
										
                                    </tr>
									@endforeach
                                    </tbody>
                                </table>
								
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
             
          <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
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

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

$(function() {
            
	$("#tableCustLeads").DataTable({
			"serverSide": false,
			"searching": true
	});
	
});
	
function funDelete(id) {
	var con = confirm('Are you sure delete this customer?');
	if(con==true) {
		var url = "{{ url('customerleads/delete/') }}";
		location.href = url+'/'+id;
	}
}
</script>

@stop
