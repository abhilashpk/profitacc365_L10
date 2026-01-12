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
							
								@foreach($orditems as $key => $orditem)
								<form class="form-horizontal" role="form" method="POST" name="frmJob" id="frmJob" >
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="{{$orderrow->id}}">
								<input type="hidden" name="jobid" value="{{$orderrow->job_id}}">
								<input type="hidden" name="irow" value="{{$orditem->id}}">
								<input type="hidden" name="jobval_id" value="{{$orditem->pay_pcntg_desc}}">
                                <table class="table horizontal_table table-striped" id="table5">
                                    <thead>
                                    <tr>
                                        <th>Item Code: {{$orditem->item_code}}</th>
                                        <th>Item Name: {!! $orditem->item_name !!}</th>
                                        <th>Unit: {{$orditem->unit_name}}</th>
                                        <th>Quantity: {{$orditem->quantity}}</th>
                                    </tr>
                                </table>
								
									@php $i = $j = 0; $jqty = ($jobqty[$key]!='')?unserialize($jobqty[$key]):[]; @endphp
									@foreach($template[$key] as $k => $row)
									@if($row->input_type=="item")
										<input type="hidden" name="unit[]" id="unt_{{$i+1}}" value="{{$row->unit_id}}">
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-3">
											<input type="text" class="form-control" name="item_name[]" value="{{$items[$key][$k]->text}}" readonly>
											<input type="hidden" name="item_id[]" value="{{$jobdata[$key][$k]->input_value}}" readonly>
										</div>
										<span style="float:left !important; font-size: 14px; margin:10px;">{{$row->unit_name}}</span>
										<label for="input-text" class="col-sm-1 control-label">Quantity</label>
										<div class="col-sm-1">
											<input type="text" class="form-control" id="qty_{{$i+1}}" name="quantity[{{$j}}]" value="{{(isset($jqty[$j]))?$jqty[$j]:$jobdata[$key][$k]->other_value->quantity}}" autocomplete="off" >
										</div> 
										
										@if($row->is_dimension==1)
										<div class="dim">	
										<label for="input-text" class="col-sm-1 control-label">Length</label>
										<div class="col-sm-1">
											<input type="text" class="form-control ht" name="height[{{$j}}]" id="ht_{{$i+1}}" readonly value="{{$jobdata[$key][$k]->other_value->height}}" autocomplete="off" >
										</div>
										<label for="input-text" class="col-sm-1 control-label">Width</label>
										<div class="col-sm-1">
											<input type="text" class="form-control wt" name="width[{{$j}}]" id="wt_{{$i+1}}" readonly autocomplete="off" value="{{$jobdata[$key][$k]->other_value->width}}" >
										</div>
										</div>
										@endif
										
									</div>
									
										@if($row->frame_no==1)
											@php $jqtysub = ($jobqtysub[$key]!='')?unserialize($jobqtysub[$key]):[]; @endphp
											@foreach($jobdata[$key][$k]->dmond as $j => $drow)
											<div class="form-group dm1">
												<label for="input-text" class="col-sm-2 control-label">Frame</label>
												<div class="col-sm-3">
													<input type="text" class="form-control" name="subitem_name[{{$key}}][]" value="{{$subitems[$key][$k][$j]->text}}" readonly>
													<input type="hidden" name="subitem_id[{{$key}}][]" value="{{$drow->dm_tem}}" readonly>
												</div>
												<label for="input-text" class="col-sm-1 control-label tiny">Qty.</label>
												<div class="col-sm-1">
													<input type="text" class="form-control" name="subquantity[{{$key}}][]" value="{{(isset($jqtysub[$k][$j]))?$jqtysub[$k][$j]:$drow->qty}}" value="{{$drow->qty}}" autocomplete="off">
												</div>
											</div>
											@endforeach
										@endif
										
									@php  $j++; @endphp
									@elseif($row->input_type=="text")
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="input_item[]" readonly value="{{$jobdata[$key][$k]->input_value}}" autocomplete="off" {{($row->is_required==1)?"required": ""}} >
										</div>
									</div>
									@elseif($row->input_type=="file" && $jobdata[$key][$k]->input_value!='')
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->type_name}}</label>
										<div class="col-sm-8">
											<img src="{{ asset('uploads/sojob/'.$jobdata[$key][$k]->input_value) }}" height="200px">
										</div>
									</div>
									@endif
									@php $i++; @endphp
									@endforeach
									
									@if($orditem->discount==1)
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Location</label>
										<div class="col-sm-3">
											<select class="form-control" name="location">
											@foreach($locations as $loc)
												<option value="{{$loc->id}}" {{($orditem->tax_code==$loc->id)?'selected':''}}>{{$loc->name}}</option>
											@endforeach
											</select>
										</div>
									</div>
									@endif 
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Status</label>
										<div class="col-sm-3">
											<select class="form-control sts" name="status" required>
												<option value="1" {{($orditem->discount==1)?'selected':''}}>Completed</option>
												<option value="0" {{($orditem->discount==0)?'selected':''}}>Pending</option>
											</select>
										</div>
										<div class="col-sm-">
											@if($orditem->discount==0)<button type="submit" class="btn btn-primary">Submit</button>
											@else<button type="submit" class="btn btn-primary">Update</button>@endif
											 <button type="button" onclick="javascript:window.close();"
                                                     class="btn btn-responsive button-alignment btn-danger"
                                                     data-toggle="button">
                                                <span style="color:#fff;" >
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
											</button>
											<a href="" class="btn btn-info btn-sm btDim">
													<span class="btn-label">
													Dimension
												</span> 
											</a>
											
										</div>
									</div>
									
								</form>
								<hr/>
							@endforeach
							
							@if(count($photos)>0)
								<h4>Other Images</h4>
								<table class="table" width="50%">
									<tr><td align="center">Image</td><td align="center">Description</td></tr>
								@foreach($photos as $prow)
									<tr>
										<td align="center">@if($prow->photo!='')<img src="{{ asset('uploads/joborder/'.$prow->photo) }}" height="230">@endif</td><td align="center">{{$prow->description}}</td>
									</tr>
								@endforeach
								</table>
								<hr/>
							@endif
									
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
<script>
$(".dim").toggle();
$(function() {	
	$(document).on('blur', '.ht,.wt', function(e) {
		var res = this.id.split('_');
		var curNum = res[1];
		var ht = parseFloat( ($('#ht_'+curNum).val()=='') ? 0 : $('#ht_'+curNum).val());
		var wt = parseFloat( ($('#wt_'+curNum).val()=='') ? 0 : $('#wt_'+curNum).val());
		var qty = '';
		if($('#unt_'+curNum).val()==26) { 
			qty = ht * wt * 2; 
		} else if($('#unt_'+curNum).val()==32){
			qty = ht * wt; 
		} 
		$('#qty_'+curNum).val(qty);
	});
	
	$(document).on('click', '.btDim', function(e) {
		 e.preventDefault();
		$(".dim").toggle();
	});
	
	$(document).on('change', '.sts', function(e) {
		if($(this).val()==1)
			alert('Please confirm item quantity is correct or not before submit!');
	});
});
</script>