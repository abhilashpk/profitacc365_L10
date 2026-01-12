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
		<?php if($workshop==0) { ?>
        <!-- Main content -->
        <section class="content">
            <div class="row ui-sortable" id="sortable_portlets">
                <div class="col-md-12 sortable">
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-info">
                            <div class="caption">
                               <i class="fa fa-fw fa-bars"></i> Purchase Rental 
                            </div>
                        </div>
                        <div class="portlet-body bg-inf-new">
							
                            <div class="portlet">
                                 <div class="col-md-6" ><a href="{{ URL::to('purchase_rental/add') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd">Purchase Rental Entry</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('purchase_rental') }}"><img src="{{asset('assets/icons/view-purchase.png')}}"><span class="info-hd">Purchase Rental View</span></a></div>
								
								 
                            </div>
							<p></br></br></p><p></br></p>
							
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                    <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-danger">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i>Sales Rental
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
							 <div class="portlet">
                                <div class="col-md-6" ><a href="{{ URL::to('rental_sales/add') }}"><img src="{{asset('assets/icons/add-quote.png')}}"><span class="info-hd"> Sales Rental Entry</span></a> </div>
                                 <div class="col-md-6" ><a href="{{ URL::to('rental_sales') }}"><img src="{{asset('assets/icons/view-quote.png')}}"><span class="info-hd">  Sales Rental View</span></a></div>
								 
								  	 
                            </div>
							<p></br></br></p><p></br></p>
                        </div>
                        
                    </div>
					
					
                </div>
              
              
                <!-- BEGIN Portlet PORTLET-->
                    <div class=" portlet box">
						<div class="portlet-title bg-warning">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i>Driver Master
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
							 <div class="portlet">
                                 <div class="col-md-6"  ><a href="{{ URL::to('rental_supplierdriver') }}"><img src="{{asset('assets/icons/view-do.png')}}"><span class="info-hd"> Supplier's Driver View</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('rental_customerdriver') }}"><img src="{{asset('assets/icons/add-do.png')}}"><span class="info-hd">Customer's Driver View</span></a></div>				      
                            </div>
							<p></br></br></p><p></br></p>
					     </div>
                    </div>
					
					<div class=" portlet box">
						<div class="portlet-title bg-primary">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i>Voucher
                            </div>
                        </div>
                        <div class="portlet-body bg-dng-new">
							 <div class="portlet">
                                 <div class="col-md-6" ><a href="{{ URL::to('customer_receipt') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd"> Recepit </span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('supplier_payment') }}"><img src="{{asset('assets/icons/add-proforma.png')}}"><span class="info-hd">Payment </span></a></div>				      
                            </div>
							<p></br></br></p><p></br></p>

						</div>
                        
                    </div>
					
					
				<!-- BEGIN Portlet PORTLET-->	
                 <div class=" portlet box">
						 <div class="portlet-title bg-success">
                            <div class="caption">
                                <i class="fa fa-fw fa-bars"></i> Transaction
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           <div class="portlet">
								 <div class="col-md-6" ><a href="{{ URL::to('journal') }}"><img src="{{asset('assets/icons/view-order.png')}}"><span class="info-hd"> Journal</span></a></div>
								 <div class="col-md-6" ><a href="{{ URL::to('account_enquiry') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd"> Statement of Account</span></a></div>				      
                            </div>
							<p></br></br></p>
                        </div>
                        
                    </div>
                    </div>

                    
                    </div>
                    
                   
                </div>
            </div>
				
			</div>
			
        </section>
		<?php }  ?>
		
  <!-- /.content -->
        
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/c3/c3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script>
	$(document).ready(function () { //alert('hi');
   
    
   
});   

</script>
		
    <!-- end of page level js -->
@stop