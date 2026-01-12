@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--page level css -->
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
			Daily Report
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-folder-close"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="#">Daily Report</a>
                </li>
            </ol>
        </section>
		
      
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
               <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-columns"></i> Daily  Report
                            </h3>
                        </div>
                        <div class="panel-body">
							<form class="form-horizontal" target="_blank" role="form" method="POST" name="frmReportSearch" id="frmReportSearch" action="{{ url('daily_report/search_account') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
									
									<ul class="breadcrumb">
										<li>Groups: @if(!$groups) <span style="color:red;">Groups are not selected! Click <a href="{{url('daily_report_setting')}}">here</a> to set groups. </span>@endif</li>
										@foreach($groups as $group)
										<li class="next"> <input type="hidden" name="group_ids[]" value="{{$group->id}}">
											<a href="#">{{$group->name}}</a>
										</li>
										@endforeach
									</ul>
								
                                <input type="hidden" id="type" name="type" value="statement">
									<input type="hidden" id="is_custom" name="is_custom" value="1">
								<div class="row">
									<div class="col-xs-6">
										<span>Date From:</span>
										<input type="text" name="date_from" value="{{date('d-m-Y')}}" readonly data-language='en' id="date_from" class="form-control" autocomplete="off">
										<span>Date To:</span>
										<input type="text" name="date_to" data-language='en' value="{{date('d-m-Y')}}" readonly id="date_to" class="form-control" autocomplete="off">
									</div>
									<div class="col-xs-6">
										<span>Search By:</span>
										<select id="search_type" class="form-control select2" style="width:100%" name="search_type">
										    <option value=" ">---Select---</option>
											<option value="summary">Summary</option>
											<option value="detail">Detail</option>
										</select>
										
										<span>Group/Account</span>
										<div class="input-group  col-md-8">
                                        <div class="btn-group">
                                            <select id="select1" multiple style="width:100%" class="form-control select2" name="account_ids[]">
												@foreach($groups as $group)
												@if(isset($accounts[$group->id]))
                                                <optgroup label="{{$group->name}}"> 
													@foreach($accounts[$group->id] as $row)
                                                    <option value="{{$row->id}}" {{($accountids && in_array($row->id, $accountids))?'selected':''}}>{{$row->master_name}}</option>
                                                    @endforeach
                                                </optgroup>
												@endif
												@endforeach
                                            </select>
                                        </div>
                                    </div>
										<br/>	<!--<button type="submit" class="btn btn-primary">Search</button> Preview-->
										<button type="button" class="btn btn-primary" onClick="preview();">Search</button>
									</div>
								</div>
							</form>
                           
                        </div>
                    </div>
            </div>
        </div>
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

<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<!-- end of page level js -->
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
<script>

$('#date_from').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#date_to').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function funDelete(id) {
	var con = confirm('Are you sure delete this account master?');
	if(con==true) {
		var url = "{{ url('account_master/delete/') }}";
	 location.href = url+'/'+id;
	}
}

function preview() {
	document.frmReportSearch.action = "{{ url('daily_report/print') }}";
	document.frmReportSearch.submit();
}

 $('#example19').multiselect({
	numberDisplayed: 1
});

$(document).ready(function () {
	$("#select1").select2({
        theme: "bootstrap",
        placeholder: "Group/Account"
    });
})
	
</script>
@stop
