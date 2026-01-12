@extends('layouts/default')

{{-- Page title --}}
@section('title')
    
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!--weathericons-->
	

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
    <link rel="stylesheet" href="{{asset('assets/css/portlet.css')}}"/>
	
	<link href="{{asset('assets/vendors/c3/c3.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" rel="stylesheet" type="text/css"/>

    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
               
            </ol>
        </section>
		
        <!-- Main content -->
        <section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                
                <div class="col-md-12 sortable">
                    
					
					<div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> HR Management
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
								 <div class="col-md-3"><a href="{{ URL::to('employee/add') }}"><img src="{{asset('assets/icons/add-employee.png')}}"><span class="info-hd"> New Employee</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('employee') }}"><img src="{{asset('assets/icons/view-employee.png')}}"><span class="info-hd"> View Employee</span></a> </div>
								 
								 <div class="col-md-3" ><a href="{{ URL::to('document_master/add') }}"><img src="{{asset('assets/icons/add-quote.png')}}"><span class="info-hd"> New Document</span></a> </div>
								 <div class="col-md-3" ><a href="{{ URL::to('document_master') }}"><img src="{{asset('assets/icons/view-quote.png')}}"><span class="info-hd"> View Document</span></a> </div>
                            </div>
							<p></br></br></p><p></br></p>
							
							<div class="portlet">
								 <div class="col-md-3" ><a href="{{ URL::to('document_report/search_form') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd"> Document Report</span></a></div>
								 
                            </div>
							<p></br></br></p>
							
                        </div>
                        
                    </div>
					
                </div>
				<!--
                <div class="col-md-6 sortable">
                    
					<div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Payroll
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('wage_entry') }}"><img src="{{asset('assets/icons/wage-entry.png')}}"><span class="info-hd"> Wage Entry</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('payroll_report') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd"> Reports</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                    </div>
					
                </div>
				-->
            </div>
				
			</div>
			
        </section>
		
		<div id="expiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Expiry Details</h4>
					</div>
					<div class="modal-body" id="expiryData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="docexpiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Document Expiry Details</h4>
					</div>
					<div class="modal-body" id="docexpiryData">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="expiry_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Expiry Details</h4>
					</div>
					<div class="modal-body" id="customerData">
						<table class="table table-striped" id="tableBank">
							<thead>
								<tr>
									<th>Emp.ID</th>
									<th>Name</th>
									<th>Document</th>
									<th>Expiry Date</th>
									<th>Validity</th>
								</tr>
							</thead>
								<tbody>
								</tbody>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="docaprv_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Documents Pending for Approval</h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped" id="tableBank">
							<thead>
							@can('qs-aprv')
								<tr>
									<th>No of Quotations Pending Approval:</th><th>{{$qtno}}</th><th><a href="{{url('quotation_sales')}}">View</a></th>
								</tr>
							@endcan
							@can('so-aprv')
								<tr>
									<th>No of Salaes Orders Pending Approval:</th><th>{{$sono}}</th><th><a href="{{url('sales_order')}}">View</a></th>
								</tr>
							@endcan
							</thead>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
        <!-- /.content -->
        
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/c3/c3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script>
$(document).ready(function () { //alert('hi');
	//$('#pdci_modal').modal('show');
	
	//$('#pdcr_modal').modal('show');
	
	//$('#expiry_modal').modal('show');
	<?php if($othrdoccount > 0) { ?>
	   $('#docexpiry_modal').modal('show');
	   var docUrl = "{{ url('document_master/get_expinfo/') }}"; 
	   $('#docexpiryData').load(docUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   <?php } ?>
   
	 <?php if($doccount > 0) { ?>
	   $('#expiry_modal').modal('show');
	   var infoUrl = "{{ url('employee/get_expinfo/') }}"; 
	   $('#expiryData').load(infoUrl, function(result) {
		  $('#myModal').modal({show:true});
	   });
   <?php } ?>
    
    <?php if($qtno > 0 || $sono > 0) { ?>
	@can('qs-aprv')
	   $('#docaprv_modal').modal('show');
	 @endcan
   <?php } ?>
   
});   
</script>
		
    <!-- end of page level js -->
@stop