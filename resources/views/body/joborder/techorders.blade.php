@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    
	<!--page level css -->
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    <!--end of page level css-->
		
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Job Order
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Inventory
                </li>
				<li>
                    <a href="#">Job Order</a>
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if(Session::has('message'))
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
                            <i class="fa fa-fw fa-list-alt"></i> {{$type}}  Job Order
                        </h3>
                        <div class="pull-right">
                            
                        </div>
                    </div>
                    <div class="panel-body">
						 <div class="row">
                               
                            </div>
							<form class="form-horizontal" role="form" method="POST" name="frmOrder" id="frmOrder" action="{{ url('job_order/update_status') }}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" id="srtype" value="{{$type}}">
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Search:</label>
									<div class="col-sm-3">
										<input type="text" class="form-control" name="search_val" id="search_val" autocomplete="off">
									</div>
									<div class="col-sm-2"><button type="button" name="search" class="btn btn-primary search">Go</button></div>
									
									<label for="input-text" class="col-sm-2 control-label">Technician:</label>
									<div class="col-sm-3">
										<select class="form-control" name="technician_id" id="technician_id">
											<option value="all">Select</option>
											@foreach($technician as $tech)
											<option value="{{$tech->id}}">{{$tech->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="table-responsive m-t-12" id="result">
									@php $i = 0; @endphp
									@foreach($orders as $row)
									<input type="hidden" name="id[]" value="{{$row->id}}">
									<input type="hidden" name="type[]" value="{{$type}}">
									<div class="table-responsive">
									<b>Technician: {{$row->salesman}}</b> 
										<table border="0" class="table table-bordered table-striped m-t-10">
											<tr>
												<td><b>Job No:</b></td><td align="left"><b>{{$row->voucher_no}}</b></td>
												<td><b>Vehicle No:</b></td><td align="left"><b>{{$row->reg_no}}</b></td>
											</tr>
											<tr>
												<td colspan="1"><b>Job Description:</b></td><td align="left" colspan="3">{{$row->description}}</td>
											</tr>
											@if($type=='Assigned')
											<tr>
												<td style="width:15%;"><b>Start Date Time:</b></td><td align="left" style="width:20%;"><input id="timepick_1" name="datetime[]" autocomplete="off" class="form-control pull-right timepick" data-language='en' data-timepicker="true" data-time-format='hh:ii aa'/></td>
												<td><button type="submit" value="{{$i}}" name="submit[]" class="btn btn-primary">Save</button></td>
												<td>@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
											</tr>
											@elseif($type=='Working')
											<tr>
												<td style="width:15%;"><b>Start Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}</b></td>
												<td><b>End Date Time:</b></td><td><input id="timepick_1" style="width:35%;margin-right:10px;" name="datetime[]" autocomplete="off" class="form-control pull-left timepick" data-language='en' data-timepicker="true" data-time-format='hh:ii aa'/> <button type="submit" value="{{$i}}" name="submit[]" class="btn btn-primary">Save</button></td>
											</tr>
											@elseif($type=='Completed')
											@php
												$datetime1 = new DateTime($row->start_time);
												$datetime2 = new DateTime($row->end_time);
												$interval = $datetime1->diff($datetime2); 
												$format = ''; 
												if($interval->days > 0)
													$format .= $interval->days.' Days ';
												if($interval->h > 0)
													$format .= $interval->h.' Hours ';
												if($interval->i > 0)
													$format .= $interval->i.' Minutes ';
												
											@endphp
											<tr>
												<input type="hidden" name="status[]" value="1">
												<td style="width:15%;"><b>Start Date Time:<br/>End Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}<br/>{{$row->end_time}}</b></td>
												<td><b>Time Taken:</b></td><td><b>{{$format}}</b>  <button type="submit" value="{{$i}}" name="submit[]" style="margin-left:20px;" class="btn btn-primary">Approve</button>
												@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
											</tr>
											@elseif($type=='Approved')
											@php
												$datetime1 = new DateTime($row->start_time);
												$datetime2 = new DateTime($row->end_time);
												$interval = $datetime1->diff($datetime2); 
												$format = '';
												if($interval->days > 0)
													$format .= $interval->days.' Days ';
												if($interval->h > 0)
													$format .= $interval->h.' Hours ';
												if($interval->i > 0)
													$format .= $interval->i.' Minutes ';
												
											@endphp
											<tr>
												<input type="hidden" name="status[]" value="0">
												<td style="width:15%;"><b>Start Date Time:<br/>End Date Time:</b></td><td align="left" style="width:20%;"><b>{{$row->start_time}}<br/>{{$row->start_time}}</b></td>
												<td><b>Time Taken:</b></td><td><b>{{$format}}</b>  <button type="submit" value="{{$i}}" name="submit[]" style="margin-left:20px;" class="btn btn-primary">Undo Approve</button>
												@if($print)<a href="{{ url('job_order/print/'.$row->id.'/'.$print->id) }}" target="_blank" class="btn btn-info">Print</a>@endif</td>
											</tr>
											@endif
										</table>
										<div style="float:left; padding-right:5px;">
											<button type="button" id="CK_{{$i}}" class="btn btn-primary btn-xs ORVD">View Description</button>
										</div>
										<div style="float:left; padding-right:5px;">
											<button type="button" id="VH_{{$i}}" class="btn btn-primary btn-xs VEH">Vehicle Detail</button>
										</div>
										<div style="float:left; padding-right:5px;">
											<button type="button" id="IM_{{$i}}" class="btn btn-primary btn-xs IMG">View Images</button>
										</div>
										
										<div class="form-group viewDc" id="viewDesc_{{$i}}">
											<div class="col-sm-8" style="margin-left:10px;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>#</th><th>Item Description</th> <th>Quantity</th>
													</tr>
												</thead>
												<tbody>@php $j=0; $items = isset($orditems[$row->id])?$orditems[$row->id]:[]; @endphp
												@foreach($items as $item) @php $j++; @endphp
												<tr>
													<td>{{$j}}</td>
													<td>{{$item->description}}</td>
													<td>{{$item->quantity}}</td>
												</tr>
												@endforeach
												</tbody>
											</table>
											</div>
										</div>
										
										<div class="form-group viewVH" id="viewVeh_{{$i}}">
											<div class="col-sm-8" style="margin-left:10px;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>Reg. No</th><th>Issue Plate</th><th>Code Plate</th><th>Make</th><th>Model</th>
													</tr>
												</thead>
												<tbody>@php $veh = isset($vehicles[$row->id])?$vehicles[$row->id]:[]; @endphp
												@foreach($veh as $vh)
												<tr>
													<td>{{$vh->reg_no}}</td>
													<td>{{$vh->issue_plate}}</td>
													<td>{{$vh->code_plate}}</td>
													<td>{{$vh->make}}</td>
													<td>{{$vh->model}}</td>
												</tr>
												@endforeach
												</tbody>
											</table>
											</div>
										</div>
										
										<div class="form-group viewIM" id="viewImg_{{$i}}">
											<div class="col-sm-8" style="margin-left:10px;">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th>Image</th><th>Description</th>
													</tr>
												</thead>
												<tbody>@php $image = isset($images[$row->id])?$images[$row->id]:[]; @endphp
												@foreach($image as $img)
												<tr>
													<td><img src="{{URL::asset('uploads/joborder/'.$img->photo)}}" style="max-size:200px;" /></td>
													<td>{{$img->description}}</td>
												</tr>
												@endforeach
												</tbody>
											</table>
											</div>
										</div>
										
										<br/>
										<hr/>
									<br/><br/>
									@php $i++; @endphp
									@endforeach
									</div>
								</div>
								@if(count($orders)==0)
									<div class="alert alert-warning">
										<p>{{$type}} jobs are empty!</p>
									</div>
								@endif
							</form>
                    </div>
                </div>
            </div>
		</section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <!-- begining of page level js -->

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
<script src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/mark.js/jquery.mark.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/vendors/datatablesmark.js/js/datatables.mark.min.js')}}" charset="UTF-8"></script>
<script src="{{asset('assets/js/custom_js/responsive_datatables.js')}}" type="text/javascript"></script>
<!-- end of page level js -->

<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>

$('.timepick').datepicker( { dateFormat: 'dd-mm-yyyy', autoClose: true } ); //, autoClose: true

$('.viewDc').hide();
$('.viewVH').hide();
$('.viewIM').hide();

$(document).on('click', '.ORVD', function(e) { e.preventDefault();
	var res = this.id.split('_');
	var no = res[1]; 
	$('#viewDesc_'+no).toggle();
});

$(document).on('click', '.VEH', function(e) { e.preventDefault();
	var res = this.id.split('_');
	var no = res[1]; 
	$('#viewVeh_'+no).toggle();
});

$(document).on('click', '.IMG', function(e) { e.preventDefault();
	var res = this.id.split('_');
	var no = res[1]; 
	$('#viewImg_'+no).toggle();
});

$(document).on('click', '.search', function(e) { e.preventDefault();
	var search = $('#search_val').val();
	var type = $('#srtype').val();
	var srurl = "{{ url('job_order/jobsearch/') }}";
	$('#result').load(srurl+'/'+search+'/'+type);
});

$(document).on('change', '#technician_id', function(e) { e.preventDefault();
	var type = $('#srtype').val();
	var techid = $('#technician_id option:selected').val();
	var srurl = "{{ url('job_order/jobsearch_tech/') }}";
	$('#result').load(srurl+'/'+techid+'/'+type);
});

</script>

@stop
