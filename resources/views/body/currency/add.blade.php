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
                Currency
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Maintenance
                    </a>
                </li>
                <li>
                    <a href="#">Currency</a>
                </li>
                <li class="active">
                    Add New
                </li>
            </ol>
        </section>
        @if(Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
        @endif
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Currency 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCurrency" id="frmCurrency" action="{{ url('currency/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Currency Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="code" name="code" placeholder="Currency Code">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Currency Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Currency Name">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Currency Rate</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="rate" name="rate" placeholder="Currency Rate">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Decimal Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="decimal_name" name="decimal_name" placeholder="Decimal Name">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Fra Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="fracode" name="fracode" placeholder="Fra Code">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Decimal Place</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="decimal_place" name="decimal_place" placeholder="Decimal Place">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('currency') }}" class="btn btn-danger">Cancel</a>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	var urlcode = "{{ url('currency/checkcode/') }}";
	var urlname = "{{ url('currency/checkname/') }}";
    $('#frmCurrency').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The currency code is required and cannot be empty!'
                    },
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('code').val()
                            };
                        },
                        message: 'The currency code is not available'
                    }
                }
            },
			name: {
                validators: {
                    notEmpty: {
                        message: 'The currency name is required and cannot be empty!'
                    },
					remote: {
                        url: urlname,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('name').val()
                            };
                        },
                        message: 'The currency name is not available'
                    }
                }
            },
            rate: {
                validators: {
                    notEmpty: {
                        message: 'The currency rate is required and cannot be empty!'
                    }
					
                }
            },
          
        }
        
    }).on('reset', function (event) {
        $('#frmCurrency').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
