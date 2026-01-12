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
       <!-- <section class="content-header">
            section starts
            <h1>
                Customer Desk
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
			
        </section> -->
		
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
                            <i class="fa fa-fw fa-list-alt"></i> Enquiry
                        </h3>
                        <div class="pull-right">
                           
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="table5">
                                    <thead>
                                    <tr>
                                        <th>#</th>
										<th>Company Name</th>
										<th>Phone No</th>
										<th>Contact Name</th>
										<th>Email</th>
                                                                              
                                                                                <th>Remarks</th>
                                                                                <th>Next Date</th>
										<th>Status</th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($prospective)){?> 
								@foreach($prospective as $k => $row)
								
                                <tr>
                                <td class="clk" data-name="{{$row->customer_id}}">{{$row->id}} </td>
                                    <td class="clk" data-name="{{$row->customer_id}}" >{{$row->master_name}} </td>
                                    
                                   <td>{{$row->vat_assign.'-'.$row->phone}}<br/>{{$row->vat_percentage.'-'.$row->fax}}</td> 
                                    <td >{{$row->contact_name}}</td>
								<td>{{$row->email}}<br/>{{$row->reference}}</td>
                                    <td ><b>{{$row->remark}} </b><br/> <i>Date:{{date('d-m-Y', strtotime($row->remark_date))}}</i></td>
                                    <td > <i>Next Date:{{date('d-m-Y', strtotime($row->next_date))}}</i></td>
									<?php
									if($row->status==2)
										$status = '<p class="btn btn-danger btn-xs">Enquiry</p>'; 
									?>
									<td  >{!!$status!!}</td>
                                    
								
                                </tr>
                                @endforeach
                                <?php } else{ ?>
                                             <p>No Records Found...</p>
                                       <?php } ?>
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
$('#table5 tbody').on('click', '.clk', function () {
        var rid = $(this).data('name');//$('#rid').val(); console.log(rid);
        var url = "{{ url('customerleads/edit/') }}";
		location.href = url+'/'+rid;
    } );
});
</script>

@stop
