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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
                Trial Balance
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Trial Balance</a>
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
                                <i class="fa fa-fw fa-columns"></i> Trial Balance
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" name="frmTrialBalance" id="frmTrialBalance" target="_blank" action="{{ url('trial_balance/search') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="accounts_arr" id="accounts_arr">
								<input type="hidden" id="group_id" name="group_id">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" data-language='en' id="date_from" class="form-control" autocomplete="off">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' id="date_to" class="form-control" autocomplete="off">
										
										@if($isdept)
										<span>Department:</span>
										<select id="department_id" class="form-control select2" style="width:100%" name="department_id">
											<option value="">Select Department...</option>
											@foreach($departments as $dept)
											<option value="{{$dept->id}}">{{$dept->name}}</option>
											@endforeach
										</select>
										@endif
										
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
											<option value="opening_summary">Opening - Summary</option>
											<option value="groupwise">Opening - Detail(Groupwise)</option>
											<!--<option value="groupwise_bal">Opening - Detail(with Balance)</option>
											<option value="opening_group_taged">Opening - Detail Group Taged</option>-->
											<option value="closing_summary">Closing - Summary</option>
											<option value="closing_groupwise">Closing - Detail(Groupwise)</option>
										<!--	<option value="closing_groupwise_bal">Closing - Detail(with Balance)</option>
											<option value="group_taged">Detail by Group Taged</option>-->
											<option value="new_format">Trial Balance YTD</option>
											<!--<option value="taged_summary">Detail by Account Taged</option>-->
										</select>
										<span></span><br/>
										<input type="checkbox" class="onacnt_icheck" name="exclude" value="1"> Exclude Opening Balance
										&nbsp; <input type="checkbox" name="trim_zero" value="1"> Trim Zero
										&nbsp; <button type="submit" class="btn btn-primary">Search</button>
									</div>
								</div>
							</form>
							
                            <div class="table-responsive m-t-10" id="accounts">
							<strong>Please select an account for search Trial Balance</strong>
                                <table class="table horizontal_table table-striped" id="tableAcmaster">
                                    <thead>
                                    <tr>
                                        <th></th>
										<th>Account ID</th>
                                        <th>Account Master</th>
										<th>Group</th>
										<th>Category</th>
                                        <th>Closing Balance</th>
                                        <th>Open Balance</th>
										<th></th><th></th><th></th>
                                    </tr>
                                    </thead>
                                    
                                </table>
                            </div>
							
							<div class="table-responsive m-t-10" id="groups">
							<strong>Please select a group for search Trial Balance</strong>
                                <table class="table horizontal_table table-striped" id="tableAcgroup">
                                    <thead>
                                    <tr>
                                        <th></th>
										<th>Group Name</th>
                                        <th>Category</th>
										<th>Account Type</th>
										<th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($groups as $group)
                                    <tr>
                                        <td><input type="checkbox" name="group[]" class="opt-group" value="{{$group->id}}"/></td>
										<td>{{ $group->name }}</td>
                                        <td>{{ $group->category_name }}</td>
										<td>{{ $group->acc_type }}</td>
										<td></td>
                                    </tr>
                                    @endforeach
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<!-- end of page level js -->

<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

$(function() {	
	$('#accounts').hide(); $('#groups').hide(); 
	
	$(document).on('change', '#search_type', function(e) { 
	
	  if($('#search_type option:selected').val()=='taged_summary') {
			//$('#accounts').show(); $('#groups').hide(); //MY24
			$('#groups').show(); $('#accounts').hide();
		} else if($('#search_type option:selected').val()=='groupwise' || $('#search_type option:selected').val()=='groupwise_bal' || $('#search_type option:selected').val()=='closing_groupwise' || $('#search_type option:selected').val()=='new_format' || $('#search_type option:selected').val()=='closing_groupwise_bal' || $('#search_type option:selected').val()=='opening_group_taged') {
			//} else if($('#search_type option:selected').val()=='group_taged' || $('#search_type option:selected').val()=='opening_group_taged') {
			$('#groups').show(); $('#accounts').hide();
	   } else {
			$('#accounts').hide(); $('#groups').hide(); 
	   }
	   
    });
	
	//var items = [];
	$(document).on('click', '.chk-account', function(e) { 
		var items = [];
		$("input[name='account[]']:checked").each(function(){items.push($(this).val());});
		//console.log(items);
		$('#accounts_arr').val(items);
	});
	
	$(document).on('click', '.opt-group', function(e) { 
	  // $('#group_id').val(this.value);
	   
	   /*  let table=$('#tableAcgroup').DataTable();
		let arr= [];
		let checkedvalues = table.$('input:checked').each(function () {
			arr.push($(this).val())
		});
		arr=arr.toString();
		console.log('add '+arr); */
		
		var id = [];
		let table = $('#tableAcgroup').DataTable();
		$('#tableAcgroup').find("input[name='group[]']:checked").each(function(){
		//table.$("input:checked").each(function(){
			id.push($(this).val());
		}); 
		$('#group_id').val(id);
			
    });
});
</script>
@stop
