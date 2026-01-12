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
                Group
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-home"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Group</a>
                </li>
                <li class="active">
                    Add New
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
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Group 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmGroup" id="frmGroup" action="{{ url('group/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="parent_id" value="0">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Group Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="group_name" name="group_name"
                                               placeholder="Group Name">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Group Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Group Description">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit
                                                                </button>

                                                         <a href="{{ url('group') }}" class="btn btn-danger">Cancel</a>
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
	var url = "{{ url('group/checkname/') }}";
    $('#frmGroup').bootstrapValidator({
        fields: {
            group_name: {
                validators: {
                    notEmpty: {
                        message: 'The group name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                group_name: validator.getFieldElements('group_name').val()
                            };
                        },
                        message: 'The group name is not available'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmGroup').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
