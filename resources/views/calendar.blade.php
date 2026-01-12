@extends('layouts/default')

{{-- Page title --}}
@section('title')
    
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!--weathericons-->
	<link href="{{asset('assets/vendors/fullcalendar/css/fullcalendar.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/fullcalendar/css/fullcalendar.print.css')}}" rel="stylesheet" media='print' type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	
    <link rel="stylesheet" href="{{asset('assets/css/portlet.css')}}"/>
	
	<link href="{{asset('assets/vendors/c3/c3.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" rel="stylesheet" type="text/css"/>
	
	<link href="{{asset('assets/css/calendar_custom.css')}}" rel="stylesheet" type="text/css"/>
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
                                <i class="fa fa-fw fa-bars"></i> CRM Dashboard
                            </div>
                        </div>
                        <div class="portlet-body bg-suc-new">
                           
						   <div id="calendar"></div>
							
                        </div>
                        
                    </div>
					
                </div>
				
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
<script>

 var date = new Date();
 var d = date.getDate(),
 m = date.getMonth(),
 y = date.getFullYear();
//var evt = {!!$jsonevt!!};
var crmurl = "{{ url('dashboard/get_crminfo/') }}";


</script>
<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/fullcalendar/js/fullcalendar.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/calendar_custom.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>

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

// $(document).on('click', '.fc-content ', function(e)  { 
// 	var res = $('.fc-center').text();
// 	var iUrl = "{{ url('customerleads/followups/') }}/"+$(this).text()+' '+ $('.fc-center').text();
// 	location.href = iUrl;
// 	//alert('hi'+ $(this).text()+' '+ $('.fc-center').text());
// });

$(document).on('click', '.fc-past,.fc-today,.fc-future', function(e)  { 
	
	var iUrl = "{{ url('customerleads/followups/') }}/"+$(this).data('date');
	location.href = iUrl;
	//alert('hi'+ $(this).data('date')+' '+ $('.fc-past,.fc-today,.fc-future').text());
});

<<<<<<< HEAD
/* $(document).on('click', '.fc-corner-right', function(e)  { 
	var mnth = $('.fc-center h2').html();
    $.ajax({
		url: "{{ url('dashboard/get_crminfo/') }}",
		type: 'get',
		data: {'month':mnth},
			success: function(data) { console.log(data);
			evt = [{"title":"1745 VB","start":"2021-01-02","backgroundColor":"#4FC1E9"}];
		}
	}) 
	
}); */


=======
// $(document).on('click', '.fc-content', function(e)  { 
// 	var iUrl = "{{ url('customerleads/followups/') }}/"+$(this).parents('table').last().attr('class');
// 	alert($(this).parents('table').last().attr('class'));
	
// });
 
>>>>>>> eaa6ae2110bf2279727e3dca9a5d27e5d30f4973
</script>
		
    <!-- end of page level js -->
@stop