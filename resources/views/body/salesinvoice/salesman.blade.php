	
<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Salesman List
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
                                        <th>Salesman ID</th>
                                        <th>Salesman Name</th>
                                    </tr>
                                    </thead>
                                    <input type="hidden" name="num" id="num" value="{{$num}}">
                                    <tbody>
									@foreach($salesmans as $salesman)
                                    <tr>
                                        <td><a href="" class="salesmanRow" data-id="{{$salesman->id}}" data-name="{{$salesman->salesman_id}}" data-dismiss="modal">{{$salesman->salesman_id}}</a></td>
                                        <td><a href="" class="salesmanRow" data-id="{{$salesman->id}}" data-name="{{$salesman->name}}" data-dismiss="modal">{{$salesman->name}}</a></td>
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
