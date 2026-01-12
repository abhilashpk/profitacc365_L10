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
            Cargo Salesman
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Cargo Entry
                </a>
            </li>
            <li>
                <a href="#">cargo Salesman</a>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Cargo Salesman 
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmCargoSalesman"
                            id="frmCargoSalesman" action="{{ url('cargo_salesman/update/' . $csedit->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="salesman_id" value="<?php echo $csedit->id; ?>">

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Salesman Id</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="saleid" name="saleid"
                                        placeholder="Salesman Id" value="{{ $csedit->salesman_id }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Salesman Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Salesman Name" value="{{ $csedit->name }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('cargo_salesman') }}" class="btn btn-danger">Cancel</a>
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
        var urlid = "{{ url('cargo_salesman/checkid/') }}";    
        var urlname = "{{ url('cargo_salesman/checkname/') }}";
            $('#frmCargoSalesman').bootstrapValidator({
                fields: {

                    saleid: {
                        validators: {
                            notEmpty: {
                                message: 'The Id is required and cannot be empty!'
                            },
                            remote: {
                                url: urlid,
                                data: function(validator) {
                                    return {
                                        saleid: validator.getFieldElements('saleid').val(),
                                         id: validator.getFieldElements('salesman_id').val(),
                                    };
                                },
                                message: 'The Salesman Id is not available'
                            }           
                        }
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required and cannot be empty!'
                            },
                            remote: {
                                url: urlname,
                                data: function(validator) {
                                    return {
                                        name: validator.getFieldElements('name').val(),
                                         id: validator.getFieldElements('salesman_id').val(),
                                    };
                                },
                                message: 'The salesman name is not available'
                            }            
                        }
                    }

                }

            }).on('reset', function(event) {
                $('#frmCargoSalesman').data('bootstrapValidator').resetForm();
            });
        });
    </script>
@stop
