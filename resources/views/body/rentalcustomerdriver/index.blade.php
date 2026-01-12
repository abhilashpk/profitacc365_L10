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
            Cutomer's Driver
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="">
                    <i class="fa fa-fw fa-home"></i> Rental Entry
                </a>
            </li>
            <li>
                <a href="#"> Customer's Driver</a>
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
                            <i class="fa fa-fw fa-list-alt"></i>  Customer's Driver List
                        </h3>
                        <div class="pull-right">
                            <a href="{{ url('rental_customerdriver/add') }}" class="btn btn-primary btn-sm">
                                <span class="btn-label">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </span> Add New
                            </a>
                           
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="mytable" class="table table-bordred table-striped">
                                <thead>
                                    <tr>
                                        
                                        <th>Customer Name</th>
                                        <th>Driver Name</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rcdriver ??[] as $cd)
                                    
                                        <tr>
                                            <td>{{ $cd->master_name}}</td>
                                             <td>{{ $cd->driver_name}}</td>
                                           <td>
                                                <p>
                                                    <button class="btn btn-primary btn-xs"
                                                        onClick="location.href='{{ url('rental_customerdriver/edit/' . $cd->id) }}'"><span
                                                            class="glyphicon glyphicon-pencil"></span></button>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <button class="btn btn-danger btn-xs delete"
                                                        onClick="funDelete('{{ $cd->id }}')"><span
                                                            class="glyphicon glyphicon-trash"></span></button>

                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($rcdriver ??[]) === 0)
                                </tbody>
                                <tbody>
                                    <tr class="odd danger">
                                        <td valign="top" colspan="4" class="dataTables_empty">No matching records found
                                        </td>
                                    </tr>
                                </tbody>
                                @endif
                            </table>
                            <form class="form-horizontal" role="form" method="POST" name="frmUnits" id="frmUnits">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="ids" id="chkids">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading5">Delete Unit</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Are you sure delete this unit?
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <!--<button type="button" class="btn btn-danger" data-dismiss="modal" value="Yes">-->
                        <button class="btn btn-danger" value="Yes" id="btndelete">
                            <span class="glyphicon glyphicon-ok-sign"></span> Yes
                        </button>

                        <button type="button" class="btn btn-success" data-dismiss="modal" value="No">
                            <span class="glyphicon glyphicon-remove"></span> No
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
    <!-- end of page level js -->
    <script>
        function funDelete(id) {
            var con = confirm('Are you sure delete this?');
            if (con == true) {
                var url = "{{ url('rental_customerdriver/delete/') }}";
                location.href = url + '/' + id;
            }
        }

       
       
    </script>

@stop
