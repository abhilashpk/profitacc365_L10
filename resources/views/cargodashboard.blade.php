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
                               <i class="fa fa-fw fa-bars"></i> Cargo Entry
                            </div>
                        </div>
                        <div class="portlet-body bg-inf-new">
							@if(auth()->user()->can('cae-list'))
                            <div class="portlet">
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_receipt') }}"><img src="{{asset('assets/icons/view-sale.png')}}"><span class="info-hd"> View Cargo Receipt</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_receipt/add') }}"><img src="{{asset('assets/icons/add-sale.png')}}"><span class="info-hd">Add Cargo Receipt</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_waybill') }}"><img src="{{asset('assets/icons/view-quote.png')}}"><span class="info-hd"> View Way Bill</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_waybill/add') }}"><img src="{{asset('assets/icons/add-quote.png')}}"><span class="info-hd"> Add Way Bill</span></a> </div>
                            </div>
							<p></br></br></p><p></br></p>
							<div class="portlet">
								 
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_despatchbill') }}"><img src="{{asset('assets/icons/reports.png')}}"><span class="info-hd"> Despatch Report</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_despatchbill/add') }}"><img src="{{asset('assets/icons/add-order.png')}}"><span class="info-hd"> Add Despatch Report</span></a></div>
                                 <div class="col-md-3" ><a href="{{ URL::to('cargo_despatchbill/list') }}"><img src="{{asset('assets/icons/view-order.png')}}"><span class="info-hd">Despatch Report Status</span></a></div>
								 <div class="col-md-3" ><a href="{{ URL::to('cargo_despatchbill/report') }}"><img src="{{asset('assets/icons/view-proforma.png')}}"><span class="info-hd">Report</span></a></div>
							</div>
							
							@endif
							@if(auth()->user()->can('cae-dspch-list'))
								<div class="portlet">
								 <div class="col-md-4" ><a href="{{ URL::to('cargo_despatchbill/list') }}"><img src="{{asset('assets/icons/view-order.png')}}"><span class="info-hd"> View Cargo Despatch List</span></a></div>
                            </div>
							@endif
							<p></br></br></p>
                        </div>
                        
                    </div>
                    <!-- END Portlet PORTLET-->
                   
                                   
					
					
					
					
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