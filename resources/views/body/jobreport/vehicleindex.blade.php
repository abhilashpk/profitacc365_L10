@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Vehicle Job Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Vehicle Job Report</a>
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
                                <i class="fa fa-fw fa-columns"></i>Vehicle Job Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmJobReport" id="frmJobReport" target="_blank" action="{{ url('job_report/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="type" value="normal">
								<div class="row">
									
									<div class="col-xs-6">
										<span>Vehicle No:</span><br/>
										<select id="vehicle_id" class="form-control select2" name="vehicle_id"  style="width:100%">
											<option value="">Select Vehicle No:</option>
											@foreach($vehicle as $row)
											<option value="{{$row->id}}">{{$row->reg_no}}</option>
											@endforeach
										</select>
										
										
										
									
									</div>
								</div>
							
							</form>
							
                        </div>
                    </div>
            </div>
        </div>
        </section>
        
        <section class="content">
            <div class="row">
				<div class="col-lg-12">
				   <div class="panel">
							<div class="panel-body" id="reportResult">
							</div>
						</div>
				</div>
			</div>
        </section>
		
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->

<script>
function getSummary() {	
	document.frmJobReport.action = "{{ url('job_report/print')}}";
	document.frmJobReport.submit();
}

	$('#vehicle_id').on('change', function(e){ 
	    console.log(e.target.value);
	    var vid=e.target.value
	    $('#reportResult').load("{{ url('job_report/vehicle_search/') }}/"+vid);
	});
$(document).ready(function () {
 
	$("#vehicle_id").select2({
        theme: "bootstrap",
        placeholder: "Select Vehicle"
    });
	
	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Select Job"
    });
	
    
});
$(function() {
var joburl = "{{ url('jobmaster/job_data/') }}";
	$('#jobname').click(function() {
	  
		$('#jobData').load(joburl, function(result) {
		    //alert('Please select a customer first!');
			$('#myModal').modal({show:true});$('.input-sm').focus();
		});
	});
	
	$(document).on('click', '.jobRow', function(e) {
		$('#jobname').val($(this).attr("data-name"));
		$('#job_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
});
</script>
@stop
