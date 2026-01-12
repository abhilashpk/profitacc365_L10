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
            Choose Email Template
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-wrench"></i> Real Estate
                </a>
            </li>
            <li>
                <a href="#">Contract Expiry</a>
            </li>
            <li>
                <a href="#">Contract Expiry List</a>
            </li>
            <li class="active">
                Choose Email Template
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
                            <i class="fa fa-fw fa-crosshairs"></i> <?php echo count($id); ?> contacts are selected
                        </h3>

                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" name="frmTemplateName"
                            id="frmHeaderFooter" action="{{ url('contractbuilding/expiryemail') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="header_footer_id" value="">
                            <!-- <div class="form-group">
                                    <h4 class="panel-title">
                                    <i class="fa fa-fw fa-crosshairs"></i> members Choosen
                                     </h4>
                                    </div> -->
                            <div class="form-group">
                                <div class="col-sm-10">
                                    @foreach ($mid as $md)
                                        <input type="hidden" name="id[]" value="{{ $md->id }}">
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Choose Template</label>
                                <div class="col-sm-10">
                                    <select id="template_id" class="form-control select2" style="width:100%"
                                        name="template_id">
                                        <option value="" selected>Select Template...</option>
                                        @foreach ($tname as $tn)
                                            <option value="{{ $tn['id'] }}" <?php if ($tn['id'] == old('template_id')) {
                                                echo 'selected';
                                            } ?>>{{ $tn['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Email Message</label>
                                <div class="col-sm-10">
                                    <textarea name="message" id="message" class="form-control" placeholder="Message"> </textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label"></label>
                                <div class="col-sm-10">
                                    <input type="submit" class="btn btn-primary" value="Send Email">

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
            $("#template_id").on('change', function(e) {
                var id = $(this).val();
                console.log(id);

                $.get("{{ url('contractbuilding/getmessage/') }}/" + id, function(data) {

                    var det = $.parseJSON(data);
                    console.log(det);

                    $("#message").val(det.message);

                })




            });
        });
    </script>
@stop
