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
                Account Category
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Account
                    </a>
                </li>
                <li>
                    <a href="#">Account Category</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Category 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCategory" id="frmCategory" action="{{ url('accategory/update/'.$catrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="category_id" value="<?php echo $catrow->id; ?>">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Account Type</label>
                                    <div class="col-sm-10">
                                        <select id="parent_id" class="form-control select2" style="width:100%" name="parent_id">
                                            <option value="">Select Account Type...</option>
											@foreach ($acctype as $type)
											@if($catrow->parent_id==$type['id'])
											@php $sel = "selected" @endphp
											@else
											@php $sel = "" @endphp
											@endif
											<option value="{{ $type['id'] }}" {{ $sel }}>{{ $type['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Category Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Category Name" value="{{ $catrow->name }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
										<a href="{{ url('accategory') }}" class="btn btn-danger">Cancel</a>
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
var url = "{{ url('accategory/checkname/') }}";
$(document).ready(function () {
	var url = "{{ url('accategory/checkname/') }}";
    $('#frmCategory').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The category name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                name: validator.getFieldElements('name').val(),
								id: validator.getFieldElements('category_id').val()
                            };
                        },
                        message: 'The category name is not available'
                    }
                }
            }
          
        },
       /*  submitHandler: function (validator, form, submitButton) {
            var fullName = [validator.getFieldElements('category_name').val(),
                validator.getFieldElements('category_name').val()
            ].join(' ');
            $('#helloModal')
                .find('.modal-title').html('Hello ' + fullName).end()
                .modal();
        } */
    }).on('reset', function (event) {
        $('#frmCategory').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
