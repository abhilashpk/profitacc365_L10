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
                Vehicle Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-building-o"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Vehicle Enquiry</a>
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
                            <i class="fa fa-fw fa-list-alt"></i> Vehicle Enquiry
                        </h3>
                  
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableVehicle">
                                    <thead>
                                    <tr>
                                    <th>Vehicle Reg. No</th>
                                      
                                       <th>Chasis No</th>
                                       <th>Model</th>
										
										<th>Customer</th>
										<th>View History</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($vehrow as $vehicle)
                                    <tr>
                               	<td>{{ $vehicle->reg_no }}</td>
										<td>{{ $vehicle->make }}</td>
                                        <td>{{ $vehicle->name }}</td>
										<td>
                                        {{ $vehicle->customer }}
										</td>
										<td>
                                        <button class="btn btn-primary btn-xs" onClick="funHistory('{{ $vehicle->id }}')"><span class="glyphicon glyphicon-eye-open"></button>
                                        <!-- <a href="" class="btn btn-info vehicle-history" data-toggle="modal" data-id="{{$vehicle->id}}" data-target="#veh_history_modal"><span class="glyphicon glyphicon-eye-open"></span></a> -->
                                        
                                        <!-- <button class="btn btn-primary btn-xs" onClick="location.href='{{ url('/sales_invoice/vehicle_history/'.$vehicle->id) }}'">
										<span class="glyphicon glyphicon-eye-open"></span></button> -->
										</td>
                                    </tr>
									@endforeach
                                    @if (count($vehrow) === 0)
									</tbody>
									<tbody><tr class="odd danger"><td valign="top" colspan="4" class="dataTables_empty">No matching records found</td></tr></tbody>
									@endif
                                    </tbody>
                                </table>
                            </div>
                    </div>


                    <!-- <div id="veh_history_modal" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="width:700px;">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Recent Service History</h4>
                                        </div>
                                        <div class="modal-body" id="VehHistoryData">Please select a Vehicle first!
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
							
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<!-- end of page level js -->
<script>

$(function() {
            
            var dtInstance = $("#tableVehicle").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            
        });
var vehurl = "{{ url('sales_invoice/vehicle_history/') }}";
	$('.vehicle-history').click(function() {
		var veh_id = $('#vehicle_id').val();
		$('#VehHistoryData').load(vehurl+'/'+veh_id, function(result) {
			$('#myModal').modal({show:true});
		});
	});	
function funHistory(id) {
	
		var url = "{{ url('vehicle/gethistory/') }}";
		location.href = url+'/'+id;

}
</script>

@stop
