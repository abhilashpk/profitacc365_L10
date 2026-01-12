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
                            <i class="fa fa-fw fa-list-alt"></i>Vehicle List
                        </h3>
                        <div class="pull-right">
                            <a href="{{ url('cargo_vehicle/add') }}" class="btn btn-primary btn-sm">
                                <span class="btn-label">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </span> Add New
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tableVehicle">
                                <thead>
                                    <tr>
                                        <th>Vehicle No:</th>
                                        <th>Vehicle Type</th>
                                        <th>Driver Name </th>
                                        <th>Driver Mobile(UAE) </th>
                                        <th>Driver Mobile(KSA) </th>
                                        <th>Expiry Date </th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cvtype as $row)
                                        <tr>
                                            <td>{{ $row->vehicle_no }}</td>
                                            <td>{{ $row->vehicle_name }}</td>
                                             <td>{{ $row->driver_name }}</td>
                                            <td>{{ $row->mobile_uae }}</td>
                                            <td>{{ $row->mobile_ksa }}</td>
                                           <td> {{ ($row->expiry_date!=0000-00-00)?date('d-m-Y',strtotime($row->expiry_date)):""}}</td>
                                            

                                            <td>
                                                <p>
                                                    <button class="btn btn-primary btn-xs"
                                                        onClick="location.href='{{ url('cargo_vehicle/edit/' . $row->id) }}'">
                                                        <span class="glyphicon glyphicon-pencil"></span></button>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <button class="btn btn-danger btn-xs delete"
                                                        onClick="funDelete('{{ $row->id }}')"><span
                                                            class="glyphicon glyphicon-trash"></span></button>
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($cvtype) === 0)
                                </tbody>
                                <tbody>
                                    <tr class="odd danger">
                                        <td valign="top" colspan="4" class="dataTables_empty">No matching records found
                                        </td>
                                    </tr>
                                </tbody>
                                @endif
                                </tbody>
                            </table>
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
                        <h4 class="modal-title custom_align" id="Heading5">Delete Delivery Type</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>&nbsp; Are you sure delete this?
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
            
	var dtInstance = $("#tableVehicle").DataTable({
		"lengthMenu": [10, 25, 50, "ALL"],
		bLengthChange: false,
		mark: true,
		"aoColumns": [null,null,null,null,null,null,{ "bSortable": false },{ "bSortable": false } ],
		//"scrollX": true,
	});
	
});
        function funDelete(id) {
            var con = confirm('Are you sure delete this Vehicle Information?');
            if (con == true) {
                var url = "{{ url('cargo_vehicle/delete/') }}";
                location.href = url + '/' + id;
            }
        }
    </script>

@stop
