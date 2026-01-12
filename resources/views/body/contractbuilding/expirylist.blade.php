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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}" />
    <!--end of page level css-->
    <link rel="stylesheet" href="{{ asset('assets/vendors/datetime/css/jquery.datetimepicker.css') }}">
    <link href="{{ asset('assets/vendors/airdatepicker/css/datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/colReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/rowReorder.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/scroller.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom_css/responsive_datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datepicker.css') }}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Contract Expiry List
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-retweet"></i> Real Estate
                </a>
            </li>
            <li>
                <a href="">Contract Expiry</a>
            </li>
            <li class="active">
                Contract Expiry List
            </li>

        </ol>

    </section>

    <!--section ends-->
    @if (Session::has('message'))
        <div class="alert alert-success">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left m-t-6">
                            <i class="fa fa-fw fa-list-alt"></i> Contract Expiry List
                        </h3>

                    </div>
                    <div class="panel-body">

                        <form method="POST" name="frmExpList" id="frmExpList"
                            action="{{ url('contractbuilding/expirytemplate') }}">
                            <div class="table-responsive">
                                <table class="table horizontal_table table-striped" id="tableEXPL" border="0">
                                    <thead>
                                        <tr>
                                            <td colspan="2" align="left">@include('main.print_head_text')<br /></td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>Customer Name</th>
                                            <th>Building Code</th>
                                            <th>Building Name</th>
                                            <th>Flat Code</th>
                                            <th>Contract No.</th>
                                            <th>Contract Expiry Date</th>
                                            <th>Rent Amount</th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach ($reports as $row)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="id[]" class="tag-line"
                                                        value="{{ $row->id }}">
                                                </td>
                                                </td>
                                                <td>{{ $row->master_name }} </td>
                                                <td>{{ $row->buildcode }}</td>
                                                <td>{{ $row->buildname }}</td>
                                                <td>{{ $row->flat }}</td>
                                                <td>{{ $row->contract_no }}</td>
                                                <td>{{ date('d-m-Y', strtotime($row->end_date)) }}</td>
                                                <td>{{ number_format($row->rent_amount, 2) }}</td>
                                             </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                <input type="submit" class="btn btn-primary" value="Submit">
                                        </tr>
                                        </td>
                        </form>
                        </tbody>
                        </table>

                    </div>
                    <div id="example-console-form"></div>

                </div>
            </div>
        </div>
        </div>
        <div class="cdata" id="customerData"></div>

    </section>


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/vendors/airdatepicker/js/datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/airdatepicker/js/datepicker.en.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/custom_js/advanceddate_pickers.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.responsive.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.rowReorder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.scroller.js') }}"></script>

    <script src="{{ asset('assets/vendors/mark.js/jquery.mark.js') }}" charset="UTF-8"></script>
    <script src="{{ asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js') }}" charset="UTF-8"></script>
    <script src="{{ asset('assets/js/custom_js/responsive_datatables.js') }}" type="text/javascript"></script>

    <!-- end of page level js -->
    <script>
        $(document).ready(function() {


            $('html').attr({
                style: 'min-height: inherit'
            });
            $('body').attr({
                style: 'min-height: inherit'
            });




        });
    </script>

@stop
