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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
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
                            <i class="fa fa-fw fa-list-alt"></i> Unit List
                        </h3>
                        <div class="pull-right">
                            <a href="{{ url('cargounit/add') }}" class="btn btn-primary btn-sm">
                                <span class="btn-label">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </span> Add New
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" onClick="btnDelete()"
                                data-toggle="dropdown" aria-expanded="false">
                                <span class='glyphicon glyphicon-trash'></span> Remove
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tableUnit" class="table table-bordred table-striped">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Unit Name</th>
                                        <th>Description</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $unit)
                                        <tr>
                                            <td><input type='checkbox' name='unitid[]' class='chk-unitid'
                                                    value='{{ $unit->id }}' /></td>
                                            <td>{{ $unit->unit_name }}</td>
                                            <td>{{ $unit->description }}</td>
                                            <td>
                                                <p>
                                                    <button class="btn btn-primary btn-xs"
                                                        onClick="location.href='{{ url('cargounit/edit/' . $unit->id) }}'"><span
                                                            class="glyphicon glyphicon-pencil"></span></button>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <button class="btn btn-danger btn-xs delete"
                                                        onClick="funDelete('{{ $unit->id }}')"><span
                                                            class="glyphicon glyphicon-trash"></span></button>

                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($units) === 0)
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
    <script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.buttons.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.colReorder.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.responsive.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.rowReorder.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.colVis.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.html5.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/buttons.print.js') }}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.scroller.js')}}"></script>
    <!-- end of page level js -->
    <script>
      $(function() {
            
	var dtInstance = $("#tableUnit").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,{ "bSortable": false },{ "bSortable": false } ],
		//"scrollX": true,
	});
	
});
        function funDelete(id) {
            var con = confirm('Are you sure delete this unit?');
            if (con == true) {
                var url = "{{ url('cargounit/delete/') }}";
                location.href = url + '/' + id;
            }
        }

        function btnDelete() {

            var checked = false;
            var elements = document.getElementsByName("unitid[]");
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].checked) {
                    checked = true;
                }
            }
            if (!checked) {
                alert('Please select atleast one unit!');
                return checked;
            } else {
                var con = confirm('Are you sure delete these units?');
                if (con == true) {
                    var id = [];
                    $("input[name='unitid[]']:checked").each(function() {
                        id.push($(this).val());
                    });
                    $('#chkids').val(id);

                    document.frmUnits.action = "{{ url('cargounit/group_delete') }}"
                    document.frmUnits.submit();
                }
            }
        }

        function deleteRow() {

            var checked = false;
            var elements = document.getElementsByName("unitid[]");
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].checked) {
                    checked = true;
                }
            }
            if (!checked) {
                alert('Please select atleast one order!');
                return checked;
            } else {
                var con = confirm('Are you sure delete these orders?');
                if (con == true) {
                    var id = [];;
                    $("input[name='unitid[]']:checked").each(function() {
                        id.push($(this).val());
                    });
                    $('#chkids').val(id);

                    document.frmOrders.action = "{{ url('cargounit/group_delete') }}"
                    document.frmOrders.submit();
                }
            }
        }
    </script>

@stop
