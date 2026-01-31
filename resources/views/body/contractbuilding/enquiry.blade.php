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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <!--section starts-->
            <h1>
            Contract
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Real Estate
                    </a>
                </li>
                <li>
                    <a href="#">Contract</a>
                </li>
                
            </ol>
			
        </section>
		
        <!--section ends-->
	
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Contract 
                        </h3>
                        <div class="pull-right">
						<a href="{{ url('contractbuilding/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
                        </div>
                    </div>
                    <div class="panel-body">
						<div class="row"><div class="col-sm-3">
							<select class="form-control" id="sortBy">
								<option value="">Sort by...</option>
								@foreach($building as $row)
								<option value="{{$row->buildingcode}}">{{$row->buildingcode}}</option>
								@endforeach
							</select>
							</div>
						</div>
                        <div class="table-responsive">
                                <table class="table table-striped" id="tableContract">
                                    <thead>
                                    <tr>
                                        <th>Contract No</th>
										<th>Tenant</th>
                                        <th>Building Code</th>
										<th>Flat No</th>
										<th>Start Date</th>
										<th>Expiry Date</th>
										<th>Status</th>
										<!--<th>Letter</th>-->
										<!--<th>Renew</th>
										<th>Close</th>
										<th>Settle/Close</th>
										<th>View/Print</th>
										<th>Edit</th>
										<th>Delete</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
             </div>
           </div>
        </section>
		
		<section class="content">
			<form class="form-horizontal" role="form" method="POST" name="frmQReport" id="frmQReport" target="_blank" action="{{ url('contractbuilding/search') }}">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
			 <input type="hidden" name="department_id" id="department_id">
			 <div class="row">
			 <div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left m-t-6">
								<i class="fa fa-fw fa-list-alt"></i> Contract Report
							</h3>
						</div>
						<div class="panel-body">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' autocomplete="off" id="date_from" class="form-control">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' autocomplete="off" id="date_to" class="form-control">
										
										 <span>Building:</span> <br/>
										
										<select id="select22" class="form-control select2" style="width:100%" name="building">
											<option value="">Select building...</option>
											@foreach($building as $bldg)
											  <option value="{{$bldg->id}}">{{$bldg->buildingcode}}</option>
											@endforeach
										</select> 
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="expiry">Expiry</option>
											<option value="buildingwise">Buildingwise</option>
											<option value="tenantwise">Tenant Rent Detail</option>
											
										</select>
										
										 <span>Tenant:</span> <br/>
											<select id="select21" class="form-control select2" style="width:100%" name="tenant">
												<option value="">Select Tenant...</option>
												@foreach($tenants as $tenant)
												  <option value="{{$tenant->id}}">{{$tenant->master_name}}</option>
												@endforeach
											</select>
											<span></span>
										<br/>

										<button type="submit" class="btn btn-primary">Search</button>
										<a href="{{ url('contractbuilding/enquiry') }}" class="btn btn-danger">Clear</a>
									</div>
								</div>
						</div>
					</div>
			</div>
			</div>
			</form>
		</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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

<!-- end of page level js -->
<script>

$('#date_from').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );

$(document).ready(function () {
	$("#select21").select2({
        theme: "bootstrap",
        placeholder: "Tenant select"
    });
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Building select"
    });
});
	
$(document).on('change','#sortBy', function() {
	var table = $('#tableContract').DataTable();
	table.search( this.value ).draw();
})


$(function() {
            
	var dtInstance = $("#tableContract").DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"order": [[ 0, 'desc' ]],
			"ajax":{
					 "url": "{{ url('contractbuilding/ajax-enquiry/') }}",
					 "dataType": "json",
					 "type": "POST",
					 "data":{ _token: "{{csrf_token()}}"}
				   },
			"columns": [
			//{ "data": "id" },
			{ "data": "contract_no" },
			{ "data": "customer" },
			{ "data": "building_code" },
			{ "data": "flat_no" },
			{ "data": "start_date" },
			{ "data": "exp_date" },
			{ "data": "status" },
			//{ "data": "mailbtn","bSortable": false },
			/*{ "data": "renew","bSortable": false },
			{ "data": "close","bSortable": false },
			{ "data": "settle","bSortable": false }
			{ "data": "open","bSortable": false },
			{ "data": "edit","bSortable": false },
			{ "data": "delete","bSortable": false }*/
		]	
		  
	});
	
});
	

</script>

@stop
