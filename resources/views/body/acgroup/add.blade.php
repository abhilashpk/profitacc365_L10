@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Account Group
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Accounts
                    </a>
                </li>
                <li>
                    <a href="#">Account Group</a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
		
		<!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Group 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmGroup" id="frmGroup" action="{{ url('acgroup/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="code" value="GRP{{$acg+1}}">
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Account Category</label>
                                    <div class="col-sm-10">
                                        <select id="category_id" class="form-control select2" style="width:100%" name="category_id">
                                            <option value="">Select Account Category...</option>
											@foreach ($category as $cat)
											<option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Group Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" autocomplete="off" class="form-control" id="name" name="name" placeholder="Group Name">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-10">
                                        <select id="category" class="form-control select2" style="width:100%" name="category">
                                            <option value="">Select Category...</option>
                                        </select>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('acgroup') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                                
                            </form>
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
	var url = "{{ url('acgroup/checkname/') }}";
	var urlc = "{{ url('acgroup/checkcode/') }}";
    $('#frmGroup').bootstrapValidator({
        fields: {
			actype_id: { validators: { notEmpty: { message: 'The account type is required and cannot be empty!' } }},
            category_id: { validators: { notEmpty: { message: 'The category is required and cannot be empty!' } }},
			name: {
                validators: {
                    notEmpty: {
                        message: 'The group name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                name: validator.getFieldElements('name').val(),
								category_id: validator.getFieldElements('category_id').val()
                            };
                        },
                        message: 'The group name is not available'
                    }
                }
            },
			code: {
                validators: {
                    notEmpty: {
                        message: 'The group code is required and cannot be empty!'
                    },
					remote: {
                        url: urlc,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('code').val()
                            };
                        },
                        message: 'The group code is not available'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmGroup').data('bootstrapValidator').resetForm();
    });
});

$('#category_id').on('change', function(e){
	
	var type_id = e.target.value;

	$.get("{{ url('accategory/getparent/') }}/" + type_id, function(data) {
		console.log(data);
		
		if(data.parent_id==1)
			$('#category').find('option').remove().end().append('<option value="">Select Category...</option><option value="CUSTOMER">Customer</option><option value="PDCR">PDCR</option><option value="CASH">Cash</option><option value="BANK">Bank</option><option value="FASSET">FIXED ASSET</option>');
		else if(data.parent_id==2)
			$('#category').find('option').remove().end().append('<option value="">Select Category...</option><option value="SUPPLIER">Supplier</option><option value="PDCI">PDCI</option>');
		else if(data.parent_id==3)
			$('#category').find('option').remove().end().append('<option value="">Select Category...</option><option value="PROFIT">PROFIT</option>');
		else
			$('#category').find('option').remove().end().append('<option value="">Select Category...</option>');
	
	});
	
	
});
	
</script>
@stop
