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
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Master</a>
                </li>
                <li class="active">
                    View
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-book"></i> View Account Master
                            </h3>
                        </div>
                        <div class="panel-body">
                               
                            <table id="user" class="table table-bordered table-striped m-t-10">
                                <tbody>
                                <tr>
                                    <td class="table_simple">Account Type</td>  <td class="table_superuser"> {{ $masterrow->type_name }}</td>
                                </tr>
                                <tr>
                                    <td>Account Category</td> <td> {{ $masterrow->category_name }}</td>
                                </tr>
                                <tr>
                                    <td>Account Group</td> <td>{{ $masterrow->group_name }} </td>
                                </tr>
                                <tr>
                                    <td>Account ID</td> <td>{{ $masterrow->account_id }}</td>
                                </tr>
                                <tr>
                                    <td>Account Master</td> <td>{{ $masterrow->master_name }} </td>
                                </tr>
                                <tr>
                                    <td>Open Balanace</td> <td> {{ number_format($masterrow->op_balance,2,'.',',') }} </td>
                                </tr>
                                <tr>
                                    <td>Closing Balance</td> <td> {{ number_format($masterrow->cl_balance,2,'.',',') }}</td>
                                </tr>
                                <tr>
                                    <td>Foreign Currency</td> <td> {{ $masterrow->currency }}</td>
                                </tr>
								<tr>
                                    <td>FC Open Balance</td> <td>{{ $masterrow->fcop_balance }} </td>
                                </tr>
                                <tr>
                                    <td>Department</td> <td> {{ $masterrow->department }} </td>
                                </tr>
								<tr>
                                    <td>Salesman</td> <td>{{ $masterrow->salesman }} </td>
                                </tr>
                                <tr>
                                    <td>Credit Limit</td> <td> {{ $masterrow->credit_limit }} </td>
                                </tr>
								<tr>
                                    <td>Due Days</td> <td>{{ $masterrow->duedays }} </td>
                                </tr>
                                <tr>
                                    <td>Terms</td> <td> {{ $masterrow->terms }} </td>
                                </tr>
								<tr>
                                    <td>Country</td> <td>{{ $masterrow->country }} </td>
                                </tr>
                                <tr>
                                    <td>Area</td> <td> {{ $masterrow->area }} </td>
                                </tr>
								<tr>
								@if($masterrow->job_assign==0)
									{{--*/ $chk = "No";
											
									/*--}}
									@else
									{{--*/ $chk = "Yes";
											
									/*--}}	
								@endif
                                    <td>Job Assignable?</td> <td>{{ $chk }} </td>
                                </tr>
                                <tr>
								@if($masterrow->job_compulsary==0)
									{{--*/ $chk = "No";
											
									/*--}}
									@else
									{{--*/ $chk = "Yes";
											
									/*--}}	
								@endif
                                    <td>Job Compulsary?</td> <td> {{ $chk }} </td>
                                </tr>
								<tr>
								@if($masterrow->is_hide==0)
									{{--*/ $chk = "No";
											
									/*--}}
									@else
									{{--*/ $chk = "Yes";
											
									/*--}}	
								@endif
                                    <td>Hide</td> <td>{{ $chk }} </td>
                                </tr>
								<!--- check permission code here --->
									<tr>
										<td>Created At</td> <td> {{ date('d-M-Y H:i a', strtotime($masterrow->created_at)) }} </td>
									</tr>
									<tr>
										<td>Created By</td> <td>{{ $masterrow->created_by }} </td>
									</tr>
									
									<tr>
									@if($masterrow->modified_at!='0000-00-00 00:00:00')
										{{--*/ $date = date('d-M-Y H:i a', strtotime($masterrow->modified_at)); /*--}}
									@else
									{{--*/ $date = '--'; /*--}}
									@endif
										<td>Modified At</td> <td> {{ $date }} </td>
									</tr>
									<tr>
										<td>Modified By</td> <td>{{ $masterrow->modify_by }} </td>
									</tr>
                                <!--- check permission code end --->
								
                                </tbody>
                            </table>
							<div class="col-sm-10">
                               <a href="{{ url('account_master') }}" class="btn btn-danger">Back</a>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	var urlcode = "{{ url('itemmaster/checkcode/') }}";
	var urldesc = "{{ url('itemmaster/checkdesc/') }}";
    $('#frmMaster').bootstrapValidator({
        fields: {
			actype_id: { validators: { notEmpty: { message: 'The account type is required and cannot be empty!' } } },
			category_id: { validators: { notEmpty: { message: 'The category name is required and cannot be empty!' } } },
			group_id: { validators: { notEmpty: { message: 'The group name is required and cannot be empty!' } } },
			account_id: { validators: { notEmpty: { message: 'The account id is required and cannot be empty!' } } },
            master_name: { validators: { 
					notEmpty: { message: 'The account master is required and cannot be empty!' },
					/* remote: { url: urlcode,
							  data: function(validator) {
								return { item_code: validator.getFieldElements('item_code').val() };
							  },
							  message: 'The item code is not available'
                    } */
                }
            },
			op_balance: { validators: { notEmpty: { message: 'The open balance is required and cannot be empty!' } } },
          
        }
        
    }).on('reset', function (event) {
        $('#frmMaster').data('bootstrapValidator').resetForm();
    });
});

$('#actype_id').on('change', function(e){
	var type_id = e.target.value;

	$.get("{{ url('accategory/getcategory/') }}/" + type_id, function(data) {
		$('#category_id').empty();
		 $('#category_id').append('<option value="">Select Account Category...</option>');
		$.each(data, function(value, display){
			 $('#category_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});

$('#category_id').on('change', function(e){
	var cat_id = e.target.value;

	$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
		$('#group_id').empty();
		 $('#group_id').append('<option value="">Select Account Group...</option>');
		$.each(data, function(value, display){
			 $('#group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});

$('#group_id').on('change', function(e){
	var group_id = e.target.value;
	$.get("{{ url('account_master/getcode/') }}/" + group_id, function(data) {
		$('#account_id').val(data);
		//console.log(data);
	});
});
</script>
@stop
