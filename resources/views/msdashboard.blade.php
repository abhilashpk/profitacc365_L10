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

		<section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-12 sortable">
                    <!-- BEGIN Portlet PORTLET-->
					<div class="col-md-6 sortable">
                    <!-- BEGIN Portlet PORTLET-->
					
					<div class=" portlet box">
                        <div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Work Enquiry
                            </div>
                        </div>
                        <div class="portlet-body bg-pr-new">
                            
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('ms_workenquiry/add') }}"><img src="{{asset('assets/icons/add-estimate.png')}}"><span class="info-hd"> New Enquiry</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('ms_workenquiry') }}"><img src="{{asset('assets/icons/view-estimate.png')}}"><span class="info-hd"> View Enquiry</span></a> </div>
                            </div>
							<p></br></br></p>
                      
                        </div>
                    </div>					
					
										
                   		
					<div class=" portlet box">
						 <div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Technician
                            </div>
                        </div>
                        <div class="portlet-body bg-wrn-new">
                           <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('ms_technician/add') }}"><img src="{{asset('assets/icons/add-emp.png')}}"><span class="info-hd"> New Technician</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('ms_technician') }}"><img src="{{asset('assets/icons/view-employee.png')}}"><span class="info-hd"> View Technician</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
                </div>
				
				
				<div class="col-md-6 sortable">
                    <!-- BEGIN Portlet PORTLET-->
					
					<div class=" portlet box">
						<div class="portlet-title bg-info">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Work Order 
                            </div>
                        </div>
                        <div class="portlet-body bg-inf-new">
							<div class="portlet">
								 <div class="col-md-6" ><a href="{{ URL::to('ms_workorder/add') }}"><img src="{{asset('assets/icons/add-invoice.png')}}"><span class="info-hd"> New Order</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('ms_workorder') }}"><img src="{{asset('assets/icons/view-invoice.png')}}"><span class="info-hd"> View Order</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
					
                    <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Customer
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('ms_customer/add') }}"><img src="{{asset('assets/icons/add-supplier.png')}}"><span class="info-hd"> New Customer</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('ms_customer') }}"><img src="{{asset('assets/icons/view-supplier.png')}}"><span class="info-hd"> View Customer</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
					
                    <!--<div class=" portlet box">
                        <div class="portlet-title bg-dng">
                            <div class="caption">
                               <i class="fa fa-fw fa-bars"></i> Reports
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
                            <div class="portlet">
								 <div class="col-md-6"><a href="{{ URL::to('sales_report') }}"><img src="{{asset('assets/icons/sales.png')}}"><span class="info-hd"> Sales</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('stock_ledger') }}"><img src="{{asset('assets/icons/stock.png')}}"><span class="info-hd"> Stock</span></a> </div>
                            </div>
							<p></br></br></p>
                        </div>
                    </div>-->
					
					
					
                </div>
                   
                </div>
                
            </div>
        </section>
		
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