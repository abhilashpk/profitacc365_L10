@extends('layouts/default')

{{-- Page title --}}
@section('title')

    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
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
            Unit
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-home"></i> Cargo Entry
                </a>
            </li>
            <li>
                <a href="#">Unit</a>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Unit
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                            <i class="fa fa-fw fa-times removepanel clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmUnit" id="frmUnit"
                            action="{{ url('cargounit/update/' . $unitrow->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="unit_id" value="<?php echo $unitrow->id; ?>">
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Unit Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="unit_name" name="unit_name"
                                        value="{{ $unitrow->unit_name }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Unit Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Unit Description" value="{{ $unitrow->description }}">
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('cargounit') }}" class="btn btn-danger">Cancel</a>
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
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/custom_js/form_elements.js') }}"></script>
    <!-- end of page level js -->

    <script>
        "use strict";

        $(document).ready(function() {
            var url = "{{ url('cargounit/checkname/') }}";
            $('#frmUnit').bootstrapValidator({
                fields: {
                    unit_name: {
                        validators: {
                            notEmpty: {
                                message: 'The unit name is required and cannot be empty!'
                            },
                            remote: {
                                url: url,
                                data: function(validator) {
                                    return {
                                        unit_name: validator.getFieldElements('unit_name').val(),
                                        id: validator.getFieldElements('unit_id').val(),
                                    };
                                },
                                message: 'The unit name is not available'
                            }
                        }
                    }

                }

            }).on('reset', function(event) {
                $('#frmUnit').data('bootstrapValidator').resetForm();
            });
        });
    </script>
@stop
