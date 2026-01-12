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
     <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/formelements.css') }}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Supplier's Driver
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-home"></i> Rental Entry
                </a>
            </li>
            <li>
                <a href="#">Supplier's Driver</a>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Supplier's Driver
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                            <i class="fa fa-fw fa-times removepanel clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmSdriver" id="frmSdriver"
                            action="{{ url('rental_supplierdriver/update/' . $rsdriver->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="rsdriver_id" value="<?php echo $rsdriver->id; ?>">
                            <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Supplier</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="supplier" id="select23" style="width:100%" />
											<option value="">--Select--</option>
											@foreach($supplier as $srow)
											<option value="{{$srow->id}}" {{($rsdriver->supplier_id==$srow->id)?"selected":""}} >{{$srow->master_name}}</option>
											@endforeach
										</select>
                                        
                                    </div>
                            </div>

                            <div class="form-group">
                               <label for="input-text" class="col-sm-2 control-label">Driver</label>
                                    <div class="col-sm-4">
                                    @php $sdriver = unserialize($rsdriver->driver_id) @endphp
                                        <select class="form-control select2" name="driver[]" id="select22" multiple style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($driver as $drow)
											<option value="{{$drow->id}}" {{(in_array($drow->id,$sdriver))?"selected":""}}>{{$drow->driver_name}}</option>
											@endforeach
										</select>
								   </div>
                            </div>




                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ url('rental_supplierdriver') }}" class="btn btn-danger">Cancel</a>
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
     <script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/custom_js/form_elements.js') }}"></script>
    <!-- end of page level js -->

    <script>
        "use strict";

        $(document).ready(function() {
        $("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Driver"
    });
	
          /*  var url = "{{ url('cargounit/checkname/') }}";
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
            });*/
        });
    </script>
@stop
