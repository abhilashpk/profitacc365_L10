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
     <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
            Vehicle
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Cargo Entry
                </a>
            </li>
            <li>
                <a href="#">Vehicle</a>
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
                            <i class="fa fa-fw fa-crosshairs"></i> New Vehicle
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmCargoVehicle"
                            id="frmCargoVehicle" action="{{ url('cargo_vehicle/save') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Vehicle Number</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="vnumber" name="vnumber"
                                        placeholder="Vehicle No">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Vehicle Type</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="vname" name="vname"
                                        placeholder="Vehicle Type">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Driver Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="dname" name="dname"
                                        placeholder="Driver Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Company</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="company" name="company"
                                        placeholder="Company Name" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Driver ID No:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="idno" name="idno"
                                        placeholder="ID No:" required>
                                </div>
                                 <label for="input-text" class="col-sm-2 control-label">Driver Passport No:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="passport" name="passport"
                                        placeholder="Passport No:" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Driver Mobile(UAE)</label>
                                <div class="col-sm-4">
                                    <input type="tel" class="form-control" id="mbuae" name="mbuae"
                                        placeholder="Mobile(UAE)" required>
                                </div>
                                <label for="input-text" class="col-sm-2 control-label">Driver Mobile(KSA)</label>
                                <div class="col-sm-4">
                                    <input type="tel" class="form-control" id="mbksa" name="mbksa"
                                        placeholder="Mobile(KSA)" required>
                                </div>
                            </div>
                        

                            <div class="form-group">
                             <label for="input-text" class="col-sm-2 control-label">Watsapp No:</label>
                                <div class="col-sm-4">
                                    <input type="tel" class="form-control" id="watsapp" name="watsapp"
                                        placeholder="Watsapp No:" required>
                                </div>
                            <label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control " autocomplete="off" name="expiry_date"
                                         id="expiry_date" data-language='en'  value="{{date('d-m-Y')}}" readonly required/>
								   </div>
                            </div>       
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('cargo_vehicle') }}" class="btn btn-danger">Cancel</a>
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
    <script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
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
      $('#expiry_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

        $(document).ready(function() {
        var url = "{{ url('cargo_vehicle/checknumber/') }}";
            $('#frmCargoVehicle').bootstrapValidator({
                fields: {

                    vnumber: {
                        validators: {
                            notEmpty: {
                                message: 'The vehicle number is required and cannot be empty!'
                            },
                        remote: {
                                url: url,
                                data: function(validator) {
                                    return {
                                        vnumber: validator.getFieldElements('vnumber').val()
                                    };
                                },
                                message: 'The vehicle number is not available'
                        }    
                        }
                    },
                    vname: {
                        validators: {
                            notEmpty: {
                                message: 'The vehicle name is required and cannot be empty!'
                            }
                        }
                    },
                    
                    dname: {
                        validators: {
                            notEmpty: {
                                message: 'The driver name is required and cannot be empty!'
                            }
                        }
                    },
                }

            }).on('reset', function(event) {
                $('#frmCargoVehicle').data('bootstrapValidator').resetForm();
            });
        });
    </script>
@stop
