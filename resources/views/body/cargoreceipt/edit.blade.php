@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
	 
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
        <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
               Consignment Receipt
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Cargo Entry
                    </a>
                </li>
                <li>
                    <a href="#"> Consignment Receipt</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
		
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Consignment Receipt
                            </h3>
                             <div class="pull-right">
							  <?php if($printid) { ?>
								 <a href="{{ url('cargo_receipt/print/'.$row->id) }}" target="_blank" class="btn btn-info">
								 Print Consignment Note
									<!-- <span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span> -->
								 </a>
								  <?php } ?>
								 </div> 
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCargoReceipt" id="frmCargoReceipt" action="{{url('cargo_receipt/update/'.$row->id)}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="remove_ids" id="remove_ids">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Consigment No.</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="job_code" name="job_code" value="{{$row->job_code}}" readonly autocomplete="off">
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Consignment Date</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control pull-right" autocomplete="off" name="job_date" id="job_date" data-language='en'  value="{{date('d-m-Y',strtotime($row->job_date))}}" readonly />
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Consignee Name</label>
                                    <div class="col-sm-4">
									<select class="form-control select2" name="consignee_name" id="select25" style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($consignee as $crow)
											<option value="{{$crow->id}}" {{$crow->id==$row->consignee_id?"selected":""}}>{{$crow->consignee_name}}</option>
											@endforeach
										</select>
										<input type="hidden" name="consignee_id" id="consignee_id"value="{{$row->consignee_id}}">
										<div class="btn-label pull-right">
										    <a href="" class="btn btn-info create-consignee" data-toggle="modal" data-target="#createconsignee_modal">
									         <span class="btn-label">
									        <i class="glyphicon glyphicon-plus" ></i>
								             </span> 
							                </a>
						                </div>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Shipper Name</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" name="shipper_name" id="select26" style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($shipper as $srow)
											<option value="{{$srow->id}}" {{$srow->id==$row->shipper_id?"selected":""}}>{{$srow->shipper_name}}</option>
											@endforeach
										</select>
										<input type="hidden" name="shipper_id" id="shipper_id" value="{{$row->shipper_id}}">
										<div class="btn-label pull-right">
										    <a href="" class="btn btn-info create-shipper" data-toggle="modal" data-target="#createshipper_modal">
									         <span class="btn-label">
									        <i class="glyphicon glyphicon-plus" ></i>
								             </span> 
							                </a>
						                </div>	
								   </div>
                                </div>
								
								<div class="form-group">
								<label for="input-text" class="col-sm-2 control-label"> Pack Qty</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="rcvd_packing" name="rcvd_packing" value="{{$row->packing_qty}}" autocomplete="off">
                                    </div>
                                   
                                    <label for="input-text" class="col-sm-2 control-label">Packing Type</label>
                                    <div class="col-sm-4">
										@php $pktype = unserialize($row->packing_type) @endphp
                                        <select class="form-control select2" name="packing_type[]" id="select22" multiple style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($ptype as $prow)
											<option value="{{$prow->id}}" {{(in_array($prow->id,$pktype))?"selected":""}}>{{$prow->description}}</option>
											@endforeach
										</select>
								   </div>
                                </div>
								
								<div class="form-group">
								     <label for="input-text" class="col-sm-2 control-label">Rate Qty</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="rcvd_quantity" name="rcvd_quantity" value="{{$row->received_qty}}" autocomplete="off" required step="any">
                                    </div>
                                    
                                    <label for="input-text" class="col-sm-2 control-label">Weight/Volume</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="weight" value="{{$row->weight}}" id="weight" required/>
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Rate Unit</label>
                                    <div class="col-sm-4">
										
                                        <select class="form-control select2" name="rate_unit" id="select24"  style="width:100%" required />
											<option value="">--Select--</option>
											@foreach($ptype as $prow)
											<option value="{{$prow->id}}" {{$prow->id==$row->rate_unit?"selected":""}}>{{$prow->description}}</option>
											@endforeach
										</select>
								   </div>
                                    <label for="input-text" class="col-sm-2 control-label">Volume</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" autocomplete="off" name="volume" value="{{$row->volume}}" id="volume" required/>
								   </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Consignee Code</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="consignee_code" name="consignee_code" value="{{$row->consignee_code}}" autocomplete="off">
                                    </div>

                                    <label for="input-text" class="col-sm-2 control-label">Delivery Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="delivery_type" id="delivery_type" />
											<option value="">--Select--</option>
											@foreach($dtype as $drow)
											<option value="{{$drow->id}}" {{($row->delivery_type==$drow->id)?"selected":""}} >{{$drow->code}}</option>
											@endforeach
										</select>
								   </div>
                                </div>
								
								<div class="form-group">
                                   <label for="input-text" class="col-sm-2 control-label">Destination</label>
                                    <div class="col-sm-4">
										<input type="text" class="form-control" id="destination" name="destination" value="{{$row->destination}}" readonly autocomplete="off" data-toggle="modal" data-target="#destination_modal">
                                    </div>


                                    <label for="input-text" class="col-sm-2 control-label">Collection Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="collection_type" id="collection_type"  />
										   <option value="">--Select--</option>
											@foreach($ctype as $crow)
											<option value="{{$crow->id}}" {{($row->collection_type==$crow->description)?"selected":""}}>{{$crow->code}}</option>
											@endforeach
										</select>
								   </div>
                                </div>
								<div class="form-group">
								    
                                      <label for="input-text" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="remarks" name="remarks" value="{{$row->remarks}}" autocomplete="off">
                                    </div>

                                    <label for="input-text" class="col-sm-2 control-label">Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="trans_type" id="trans_type" />
										@php $sel1=$sel2=$sel3='';@endphp
											@if($row->trans_type==1)
                                            $sel1 = "selected";
													$sel2="";
													$sel3="";
													
                                            @elseif($row->trans_type==2)
											$sel1 = "";
											$sel2="selected";
											$sel3="";
											 @elseif($row->trans_type==3)
											$sel1 = "";
											$sel2="";
											$sel3="selected";
											@endif
											<option value="$row->trans_type"><?php if($row->trans_type==1) echo "Full Truck"; else if($row->trans_type==2) echo "Consolidation"; else if($row->trans_type==3) echo "Transportation"; ?></option>
											<option value="1"{{$sel1}}>Full Truck</option>
											
											<option value="2"{{ $sel2 }}>Consolidation</option>
											<option value="3"{{ $sel3}}>Transportation</option>
											
										</select>
                                    </div>
								</div>	
								<div class="form-group">
								    <label for="input-text" class="col-sm-2 control-label">Salesman</label>
                                    <div class="col-sm-4">
										<input type="text" class="form-control" autocomplete="off" name="salesman_name" value="{{$row->salesman}}" required id="salesman_name"  data-toggle="modal" data-target="#salesman_modal"/>
										<input type="hidden" name="salesman" id="salesman" value="{{$row->salesman_id}}">
                                    </div>
								</div>	
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Rate</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="rate" name="rate" step="any" value="{{$row->rate}}" autocomplete="off" required >
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Collection Charges</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" autocomplete="off" name="col_charge" id="col_charge" value="{{$row->coll_charge}}" step="any"/>
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Other Charges</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" autocomplete="off" name="otr_charge" id="otr_charge" value="{{$row->other_charge}}" step="any"/>
								   </div>
                                </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Total Charges</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" id="total_charge" readonly name="total_charge" value="{{$row->total_charge}}" autocomplete="off" step="any" required>
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Amount Received</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" autocomplete="off" name="amt_received" id="amt_received" value="{{$row->amt_received}}" step="any"/>
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Balance</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" autocomplete="off" name="balance" id="balance" readonly value="{{$row->balance}}" step="any"/>
								   </div>
                                </div>
								<!--<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Lumpsum</label>
                                    <div class="col-sm-2">
                                        <input type="checkbox" class="form-control" id="is_lumpsum" name="is_lumpsum" >
                                    </div> -->
                                </div>
								<hr/>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Shippers Mob</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" id="shippers_mob" name="shippers_mob" value="{{$row->shippers_mob}}" autocomplete="off" required >
                                    </div>
                                    <label for="input-text" class="col-sm-2 control-label">Shippers Veh.No</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" autocomplete="off" name="shippers_vehno" value="{{$row->shippers_vehno}}" id="shippers_vehno" required />
								   </div>
								   <label for="input-text" class="col-sm-2 control-label">Invoice No</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" autocomplete="off" name="invoice_nos" value="{{$row->invoice_nos}}" id="invoice_nos" required />
								   </div>
                                </div>
								
								@if(count($attachments)>0)
									@foreach($attachments as $arow)
								<div class="form-group">
									<div class="rClass">
										<label for="input-text" class="col-sm-2 control-label">Attachment</label>
										<div class="col-sm-8">
											<div class="file-preview">
												<div class="close fileinput-remove removeDoc" data-val="{{$arow->id}}">×</div>
												<div class="file-drop-disabled">
													<div class="file-preview-thumbnails">
														<div class="file-live-thumbs">
															<a href="{{asset('uploads/cargoentry/'.$arow->file_name)}}" target="_blank">View Document</a>
														</div>
													</div>
													<div class="clearfix"></div><div class="file-preview-status text-center text-success"></div>
													<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
												</div>
											</div>
										</div>
										</div>
									</div>
									@endforeach
								@endif
								
								<div class="filedivPrnt">
									<div class="filedivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label">Attachment</label>
											<div class="col-sm-8">
												<input type="file" id="input-51" name="attachment" class="file-loading" data-show-preview="true" data-url="{{url('cargo_receipt/upload-attachment/')}}">
												<div id="files_list_1"></div>
												<p id="loading_1"></p>
												<input type="hidden" name="attachments[]" id="attachment_1">
											</div>
											<div class="col-sm-1">
												<button type="button" class="btn-success btn-add-file" id="btn_1">
													<i class="fa fa-fw fa-plus-square"></i>
												</button>
												<button type="button" class="btn-danger btn-remove-file">
													<i class="fa fa-fw fa-minus-square"></i>
												 </button>
											</div>
										</div>
									</div>
								</div>
								<hr/>
								<br/>
									
								<div class="form-group">
									<label for="input-text" class="col-sm-3 control-label"></label>
									<div class="col-sm-6">
									<a href="{{ url('cargo_receipt/print_receipt/'.$row->id) }}" class="btn btn-primary ">Print Cash Receipt</a> 
										<button type="submit" class="btn btn-primary">Submit</button>
										 <a href="{{ url('cargo_receipt') }}" class="btn btn-danger">Cancel</a>
									</div>
								</div>
									
						</form>
								 
								 <div id="consignee_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Consignee</h4>
											</div>
											<div class="modal-body" id="consigneeData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<div id="createconsignee_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Create Consignee</h4>
											</div>
											<div class="modal-body" id="newconsigneeData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								
								<div id="shipper_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Shipper</h4>
											</div>
											<div class="modal-body" id="shipperData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div> 
								<div id="createshipper_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Create Shipper</h4>
											</div>
											<div class="modal-body" id="newshipperData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<div id="destination_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Destination</h4>
											</div>
											<div class="modal-body" id="destinationData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								
								<div id="salesman_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Salesman</h4>
											</div>
											<div class="modal-body" id="salesmanData">
												
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
  
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
$('#job_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

function getTotal() {
	let rcvdQty = parseFloat( ($('#rcvd_quantity').val()=='')?0:$('#rcvd_quantity').val() ); 
	let rate = parseFloat( ($('#rate').val()=='')?0:$('#rate').val() ); 
	let colChrg = parseFloat( ($('#col_charge').val()=='')?0:$('#col_charge').val() ); 
	let otrChrg = parseFloat( ($('#otr_charge').val()=='')?0:$('#otr_charge').val() ); 
	let amtRcvd = parseFloat( ($('#amt_received').val()=='')?0:$('#amt_received').val() ); 
	let totalChrg = (rcvdQty * rate) + colChrg + otrChrg;
	let balance = totalChrg - amtRcvd
	$('#total_charge').val(totalChrg.toFixed(2));
	$('#balance').val(balance.toFixed(2));
}

$(document).on('keyup', '#rcvd_quantity,#rate,#col_charge,#otr_charge,#amt_received', function(e) {
	getTotal();
});

$(function() {	
	
	$('#input-51').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#attachment_1').val();
			$('#attachment_1').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});

	var fNum;
	$(document).on('click', '.btn-add-file', function(e)  { 
		var res = this.id.split('_');
		fNum = res[1];
	    fNum++;
		$.ajax({
			url: "{{ url('cargo_receipt/get_fileform/') }}",
			type: 'post',
			data: {'no':fNum},
			success: function(data) {
				$('.filedivPrnt').append(data);
				return true;
			}
		}) 
		
	}).on('click', '.btn-remove-file', function(e) { 
		$(this).parents('.filedivChld:first').remove();
		
		$('.filedivPrnt').find('.filedivChld:last').find('.btn-add-file').show();
		if ( $('.filedivPrnt').children().length == 1 ) {
			$('.filedivPrnt').find('.btn-remove-file').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Paper"
    });
	
	$("#select23").select2({
        theme: "bootstrap",
        placeholder: "Select Salesman"
    });

	$("#select25").select2({
        theme: "bootstrap",
        placeholder: "Select Consignee"
    });
	$("#select26").select2({
        theme: "bootstrap",
        placeholder: "Select Shipper"
    });
	$(document).on('change', '#select25', function(e) {
	var $id=$('#select25').val();
	$('#consignee_id').val($id);
	
	});
	$(document).on('change', '#select26', function(e) {
	var $id=$('#select26').val();
	$('#consignee_id').val($id);
	
	});
	var conurl = "{{ url('cargo_receipt/get_consignee/') }}";
	$('#consignee_name').click(function() {
		$('#consigneeData').load(conurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	var newconurl = "{{ url('cargo_receipt/create_consignee/') }}";
	 $('.create-consignee').click(function() {
	   $('#newconsigneeData').load(newconurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.consigneeRow', function(e) {
		//$('#consignee_name').val($(this).attr("data-name"));
		$("#select25").append ('<option selected="selected" value="' + $(this).attr("data-name")+ '">' + $(this).attr("data-name")+ '</option>');
		$('#consignee_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	var shpurl = "{{ url('cargo_receipt/get_shipper/') }}";
	$('#shipper_name').click(function() {
		$('#shipperData').load(shpurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	var newshpurl = "{{ url('cargo_receipt/create_shipper/') }}";
	 $('.create-shipper').click(function() {
	   $('#newshipperData').load(newshpurl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.shipperRow', function(e) {
		//$('#shipper_name').val($(this).attr("data-name"));
		$("#select26").append ('<option selected="selected" value="' + $(this).attr("data-name")+ '">' + $(this).attr("data-name")+ '</option>');
		$('#shipper_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	var desturl = "{{ url('cargo_receipt/get_destination/') }}";
	$('#destination').click(function() {
		$('#destinationData').load(desturl, function(result) {
			$('#myModal').modal({show:true});
		});
	});
	$(document).on('click', '.destinationRow', function(e) {
		$('#destination').val($(this).attr("data-destination"));
		e.preventDefault();
	});
	
	
	var smanurl = "{{ url('cargo_receipt/get_salesman/') }}";
	$('#salesman_name').click(function() {
		$('#salesmanData').load(smanurl, function(result) {
			$('#myModal').modal({show:true}); $('.input-sm').focus()
		});
	});
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman_name').val($(this).attr("data-name"));
		$('#salesman').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
});

var rid = $('#remove_ids').val();
$(document).on('click', '.removeDoc', function(e) {  
	var con = confirm('Are you sure to remove this document?');
	if(con) {
		var id = $(this).attr("data-val"); 
		rid += (rid=='')?id:','+id;
		$(this).parents('.rClass').remove(); 
		$('#remove_ids').val(rid);
	}
});
</script>
@stop
