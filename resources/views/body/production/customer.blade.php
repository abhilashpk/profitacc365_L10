	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<!--page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
    <!--end of page level css-->
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Customer List
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <span>Account ID:</span>
                                    <input type="text" name="name" placeholder="Account ID..." class="form-control input-sm">
                                </div>
                                <div class="col-xs-6">
                                    <span>Account Name:</span>
                                    <input type="text" name="position" placeholder="Account Name..." class="form-control input-sm">
                                </div>
                            </div>
                            <div class="table-responsive m-t-10">
                                <table class="table horizontal_table table-striped" id="table5">
                                    <thead>
                                    <tr>
                                        <th>Account ID</th>
                                        <th>Account Name</th>
                                        <th>Balance</th>
                                        <th>Open Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($customers as $customer)
                                    <tr>
                                        <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-clbalance="{{number_format($customer->cl_balance,2)}}" data-pdc="{{number_format($customer->pdc_amount,2)}}" data-crlimit="{{number_format($customer->credit_limit,2)}}" data-dismiss="modal">{{$customer->account_id}}</a></td>
                                        <td><a href="" class="custRow" data-id="{{$customer->id}}" data-name="{{$customer->master_name}}" data-clbalance="{{number_format($customer->cl_balance,2)}}" data-pdc="{{number_format($customer->pdc_amount,2)}}" data-crlimit="{{number_format($customer->credit_limit,2)}}" data-dismiss="modal">{{$customer->master_name}}</a></td>
                                        <td>{{ number_format($customer->cl_balance, 2, '.', ',') }}</td>
                                        <td>{{ number_format($customer->op_balance, 2, '.', ',') }}</td>
                                    </tr>
                                   @endforeach
                                    </tbody>
                                </table>

                            <div id="grid_modal2" class="modal fade animated" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal with grid arrangement</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">col-md-12
                                                    <div class="row">
                                                        <div class="col-md-6">col-md-6</div>
                                                        <div class="col-md-6">col-md-6</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">col-md-6</div>
                                                <div class="col-md-6">col-md-6</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
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

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->
