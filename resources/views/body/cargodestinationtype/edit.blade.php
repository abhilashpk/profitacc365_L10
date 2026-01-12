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
            Destination 
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Cargo Entry
                </a>
            </li>
            <li>
                <a href="#">Destination</a>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Destination 
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmDestinationType"
                            id="frmDestinationType" action="{{ url('destination_type/update/' . $cdedit->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="destintion_id" value="<?php echo $cdedit->id; ?>">

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="code"
                                        placeholder="Code" value="{{ $cdedit->code }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Detination Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Destination Name" value="{{ $cdedit->name }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('destination_type') }}" class="btn btn-danger">Cancel</a>
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
        var url = "{{ url('destination_type/checkname/') }}";
            $('#frmDestinationType').bootstrapValidator({
                fields: {

                    code: {
                        validators: {
                            notEmpty: {
                                message: 'The code is required and cannot be empty!'
                            }
                       
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required and cannot be empty!'
                            },
                            remote: {
                                url: url,
                                data: function(validator) {
                                    return {
                                        name: validator.getFieldElements('name').val(),
                                         id: validator.getFieldElements('destination_id').val(),
                                    };
                                },
                                message: 'The destination name is not available'
                            }            
                        }
                    }

                }

            }).on('reset', function(event) {
                $('#frmDestinationType').data('bootstrapValidator').resetForm();
            });
        });
    </script>
@stop
