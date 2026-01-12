<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
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
</head>
<body>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success filterable">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-columns"></i> Job Order Details 
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive m-t-12">
							@if(Session::has('message'))
							<div class="alert alert-success">
								<p>{{ Session::get('message') }}</p>
							</div>
							@endif
							<p><h4>Order No: {{$orderrow->voucher_no}}  &nbsp;  &nbsp; Order Date: {{date('d-m-Y',strtotime($orderrow->voucher_date))}} &nbsp;  </h4></p>
							<form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob" >
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{$orderrow->id}}">
								<input type="hidden" name="jobid" value="{{$orderrow->job_id}}">
								@foreach($orditems as $key => $orditem)
                                <table class="table horizontal_table table-striped" id="table5">
                                    <thead>
                                    <tr>
                                        <th>Item Code: {{$orditem->item_code}}</th>
                                        <th>Item Name: {{$orditem->item_name}}</th>
                                        <th>Unit: {{$orditem->unit_name}}</th>
                                        <th>Quantity: {{$orditem->quantity}}</th>
                                    </tr>
                                </table>
								
									@php $i = 0; @endphp
									@foreach($template[$key] as $k => $row)
									@if($row->input_type=="item")
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-3">
											<input type="text" class="form-control" value="{{$items[$key][$k]->text}}" readonly>
										</div>
										<span style="float:left !important; font-size: 14px; margin:10px;">{{$row->unit_name}}</span>
										@if($row->is_dimension==1)
										<label for="input-text" class="col-sm-1 control-label">Length</label>
										<div class="col-sm-1">
											<input type="text" class="form-control" name="height[{{$i}}]" value="{{$jobdata[$key][$k]->other_value->height}}" autocomplete="off" >
										</div>
										<label for="input-text" class="col-sm-1 control-label">Width</label>
										<div class="col-sm-1">
											<input type="text" class="form-control" name="width[{{$i}}]" autocomplete="off" value="{{$jobdata[$key][$k]->other_value->width}}" >
										</div>
										@endif
										<label for="input-text" class="col-sm-1 control-label">Quantity</label>
										<div class="col-sm-1">
											<input type="text" class="form-control" value="{{$jobdata[$key][$k]->other_value->quantity}}" autocomplete="off" >
										</div>
									</div>
									@elseif($row->input_type=="text")
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="input_item[]" readonly value="{{$jobdata[$key][$k]->input_value}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} >
										</div>
									</div>
									@elseif($row->input_type=="file")
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-8">
											<img src="{{ asset('uploads/sojob/'.$jobdata[$key][$k]->input_value) }}" height="200px">
										</div>
									</div>
									@endif
									@php $i++; @endphp
									@endforeach
									
									<hr/>
							@endforeach
							
							@if(count($photos)>0)
								<h4>Other Images</h4>
								<table class="table" width="50%">
									<tr><td align="center">Image</td><td align="center">Description</td></tr>
								@foreach($photos as $prow)
									<tr>
										<td align="center"><img src="{{ asset('uploads/joborder/'.$prow->photo) }}" height="230"></td><td align="center">{{$prow->description}}</td>
									</tr>
								@endforeach
								</table>
								<hr/>
							@endif
							
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-4">
											@if($orderrow->is_warning==0)<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#status_modal">Completed</button>@endif
											 <button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-danger"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
										</div>
									</div>
									
									<div id="status_modal" class="modal fade animated" role="dialog">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Job Status Update</h4>
												</div>
												<div class="modal-body" id="status_data">
													@foreach($orditems as $key => $orditem)
														@php $i = 0; @endphp
														@foreach($template[$key] as $k => $row)
															@if($row->input_type=="item")
															<div class="form-group">
																<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
																<div class="col-sm-3">
																	<input type="hidden" name="item_id[]" value="{{$jobdata[$key][$k]->input_value}}" readonly>
																	<input type="text" class="form-control" name="item_name[]" value="{{$items[$key][$k]->text}}" readonly>
																</div>
																<input type="hidden" id="grp_{{$i+1}}" value="{{$row->group_id}}">
																<span style="float:left !important; font-size: 14px; margin:10px;">{{$row->unit_name}}</span>
																
																<label for="input-text" class="col-sm-2 control-label">Quantity</label>
																<div class="col-sm-2">
																	<input type="text" class="form-control" name="quantity[{{$i}}]" value="{{$jobdata[$key][$k]->other_value->quantity}}" autocomplete="off" required>
																</div>
															</div>
															@endif
															@php $i++; @endphp
														@endforeach
													@endforeach
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Submit</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>
											</div>
										</div>
									</div> 
							</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</body>
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
</html>