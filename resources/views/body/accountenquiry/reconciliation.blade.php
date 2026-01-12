@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
	<link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">

	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

	<!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.min.css')}}"/> -->
	<style>
		#date_modal { z-index:0; }
	</style>
	
	
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Enquiry</a>
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
                                <i class="fa fa-fw fa-columns"></i> Account Master List &nbsp; 
                            </h3>
							
							@if($isdept)
							<select id="department" class="form-control select2" name="department" style="width:20%;">
								<option value="">All Department</option>
								@foreach($department as $row)
								<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
							@endif
							
							<div class="pull-right">
							@can('ac-master-create')
                             <a href="{{ url('account_master/add') }}" class="btn btn-primary btn-sm">
									<span class="btn-label">
									<i class="glyphicon glyphicon-plus"></i>
								</span> Add New
							</a>
							@endcan
							
                        </div>
                        </div>
                        <div class="panel-body">
							
                            <div class="table-responsive">
                                <table class="table table-striped" id="tableRecon">
                                    <thead>
                                    <tr>
                                        <th>#</th>
										<th>Account ID</th>
                                        <th>Account Master</th>
                                        <th>Closing Balance</th>
                                        <th>Open Balance</th>
                                    </tr>
                                    </thead>
									<tbody>
									@foreach($results as $row)
										<tr>
											<td><input type='radio' name='account' class='opt-account' value='{{$row->id}}'/></td>
											<td>{{$row->account_id}}</td>
											<td>{{$row->master_name}}</td>
											<td>{{$row->cl_balance}}</td>
											<td>{{$row->op_balance}}</td>
										</tr>
									@endforeach
									</tbody>
                                </table>
																
								<button type="button" class="btn btn-primary statement" data-toggle="modal" data-target="#date_modal">View</button>
								
                            </div>
                        </div>
                    </div>
					
					
            </div>
        </div>

        </section>
		
		<div id="date_modal" class="modal fade animated" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Reconciliation</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal" role="form" method="POST" target="_blank" name="frmSearchAccount" id="frmSearchAccount" action="{{ url('account_enquiry/search_account_ar') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" id="account_id" name="account_id">
							<input type="hidden" id="type" name="type"  value="statement">
							<input type="hidden" id="is_custom" name="is_custom" value="0">
							
								<div class="form-group">
									<label for="inputUsername" class="col-md-4 control-label">Date From</label>
									<div class="col-md-7">
										<input type="text" name="date_from" id="date_from"  class="form-control input-sm" data-language='en' autocomplete="off" value="{{date('d-m-Y',strtotime($settings->from_date))}}">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputUsername" class="col-md-4 control-label">Date To</label>
									<div class="col-md-7">
										<input type="text" name="date_to" id="date_to" class="form-control input-sm" data-language='en' autocomplete="off" value="{{date('d-m-Y')}}">
									</div>
								</div>
								
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-4 col-md-9">
											<button type="submit" class="btn btn-primary">Search</button>
											&nbsp;
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
								
							</div>
						</form>
					</div>
					
				</div>
			</div>
		</div> 
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->

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

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script src="{{asset('assets/js/custom_js/custom_elements.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script>
	
//$('#date_from').datepicker( { autoClose:true } );
//$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(function() {
            
	var dtInstance = $("#tableRecon").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"bSort" : false,
		"aoColumns": [null,null,null,null,null ],
		//"scrollX": true,
	});
	
	$(document).on('click', '.opt-account', function(e) { 
	   
	   $('#account_id').val(this.value);
	});
});
</script>
@stop
