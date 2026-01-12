<div class="row">
<div class="col-md-12">
                     <div class="panel panel-default1">
                        <div class="panel-heading" style="background-color:#86C5FF">
                            <h3 class="panel-title" style="padding-bottom:10px; color:#428BCA !important;"><i class="fa fa-fw fa-list-alt"></i><font color="white"> Approval Pending Details</font></h3>
                            <ul class="nav nav-tabs nav-float" role="tablist">
                                <li class="active">
                                    <a href="#po" role="tab" data-toggle="tab">Purchase Order</a>
                                </li>
                                <li>
                                    <a href="#qs" role="tab" data-toggle="tab">Quotation Sales</a>
                                </li>
                                <li>
                                    <a href="#pv" role="tab" data-toggle="tab">Payment Voucher</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body" style="background-color:#d6eef8">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="po">
                                    <div class="row" style="padding:10px;">
                                    <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                                <th>PO.NO</th>
                                                <th>PO Date</th>
                                                <th>Supplier</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($poapproval as $row)
                                            
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
                                                <td>{{ $row->master_name }}</td>
                                                 <td>{{ number_format($row->net_amount,2) }}</td>
                                              @if($name=='Admin')  <td><p>
												<a class="btn btn-primary btn-xs"  href='{{ url('purchase_order/views/'.$row->id) }}' target="_blank">
												<span class="fa fa-fw fa-check-square"></span></a>
											</p></td>@endif
                                                
                                            </tr>
                                            
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>
                                     <div class="tab-pane fade" id="qs">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                               <th>QS.NO</th>
                                                <th>QS Date</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($qsapproval  as $row)
                                           
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
                                                <td>{{ $row->master_name }}</td>
                                                <td>{{ number_format($row->net_total,2) }}</td>
                                              @if($name=='Admin')  <td>
                                                    
                                                  <p>
												<a class="btn btn-primary btn-xs"  href='{{ url('quotation_sales/views/'.$row->id) }}' target="_blank">
												<span class="fa fa-fw fa-check-square"></span> </a>
											</p>  
                                                </td>@endif
                                            </tr>
                                            
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                
                                <div class="tab-pane fade" id="pv">
                                    <div class="row" style="padding:10px;">
                                        <table class="table table-striped" id="tableBank">
                                        <thead>
                                            <tr>
                                               <th>PV.NO</th>
                                                <th>PV Date</th>
                                                <th>Credit Account</th>
                                                 <th>Supplier Account</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach($pvapproval  as $row)
                                           
                                            <tr>
                                                <td>{{ $row->voucher_no}}</td>
                                                <td>{{ date('d-m-Y',strtotime($row->voucher_date)) }}</td>
                                                <td>{{ $row->creditor }}</td>
                                                <td>{{ $row->debitor }}</td>
                                                <td>{{ number_format($row->amount,2) }}</td>
                                            @if($name=='Admin')<td>
                                            <p>
												<a class="btn btn-primary btn-xs"  href='{{ url('supplier_payment/views/'.$row->id) }}' target="_blank">
												<span class="fa fa-fw fa-check-square"></span> </a>
											</p>  
                                                </td> @endif
                                            </tr>
                                            
                                            @endforeach
                                            </tbody>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

</div>