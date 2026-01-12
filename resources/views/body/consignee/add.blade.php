@extends('layouts/default')

{{-- Page title --}}
@section('title')

    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="{{ asset('assets/vendors/summernote/summernote.css') }}">
    <link href="{{ asset('assets/vendors/trumbowyg/css/trumbowyg.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/form_editors.css') }}">

    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/iCheck/css/all.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css') }}"
        media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/formelements.css') }}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Consignee
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Cargo Entry
                </a>
            </li>
            <li>
                <a href="#">Consignee</a>
            </li>
            <li class="active">
                Add New
            </li>
        </ol>
    </section>
    @if (Session::has('message'))
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
                            <i class="fa fa-fw fa-crosshairs"></i> New Consignee
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmConsignee"
                            id="frmConsignee" action="{{ url('consignee/save') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Consignee Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea name="address" id="address" class="form-control " placeholder="Address"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label ">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="Phone no:">
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label ">Alternate Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id="phone1" name="phone1"
                                        placeholder="Phone no:">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('consignee') }}" class="btn btn-danger">Cancel</a>
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
    <script src="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.all.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/trumbowyg/js/trumbowyg.js') }}" type="text/javascript"></script>
    <script type="text/javascript"
        src="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/js/bootstrap3-wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/form_editors.js') }}" type="text/javascript"></script>
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/custom_js/form_elements.js') }}"></script>
    <!-- end of page level js -->

    <script>
        "use strict";

        $(document).ready(function() {
        var urlphone = "{{ url('consignee/checkphone/') }}";
         var urlphone1 = "{{ url('consignee/checkphone1/') }}";
	    var urlname = "{{ url('consignee/checkname/') }}";
            $('#frmConsignee').bootstrapValidator({
                fields: {

                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required and cannot be empty!'
                            },
                        remote: {
                            url: urlname,
                            data: function(validator) {
                            return {
                                name: validator.getFieldElements('name').val()
                            };
                        },
                        message: 'The consignee name is not available'
                    }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'The content is required and cannot be empty!'
                            }
                        }
                    },
                  phone: {
                        validators: {
                            notEmpty: {
                                message: 'The phone no: is required and cannot be empty!'
                            },
                        remote: {
                            url: urlphone,
                            data: function(validator) {
                            return {
                                phone: validator.getFieldElements('phone').val()
                            };
                        },
                        message: 'The Phone no. is not available'
                    }
                        }
                    },

                     phone1: {
                        validators: {
                            notEmpty: {
                                message: 'The phone no: is required and cannot be empty!'
                            },
                        remote: {
                            url: urlphone1,
                            data: function(validator) {
                            return {
                                phone1: validator.getFieldElements('phone1').val()
                            };
                        },
                        message: 'The Phone no. is not available'
                    }
                        }
                    },
                }

            }).on('reset', function(event) {
                $('#frmConsignee').data('bootstrapValidator').resetForm();
            });
        });
    </script>
@stop
