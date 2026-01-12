@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}" media="all" />
	
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
	<style>
	.itmHd { text-align:center !important; }
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
              Item Template
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Sales Order Job
                    </a>
                </li>
                <li>
                    <a href="#">Item Template</a>
                </li>
                <li class="active">
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Item Template
                            </h3>
							
                           <div class="pull-right">
						  
							</div>
								 
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmItemTemp" id="frmItemTemp" action="{{url('item_template/update')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Service Name</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" name="item_id" id="select22" style="width:100%" required>
											@foreach($items as $row)
											<option value="{{$row->id}}" {{($id==$row->id)?'selected': ''}}>{{$row->description}}</option>
											@endforeach
										</select>
                                    </div>
                                </div>
									
								<fieldset>
									<legend style="margin-bottom:0px !important;"><h5><span class="itmDtls">Template Design</span></h5></legend>
									<div class="itemdivPrnt">
									<table border="0" class="table-dy-row">
									<thead>
										<tr>
											<th width="1%" class="itmHd" align="center"><span class="small">No</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Input Item</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Type Name</span></th>
											{{--<th width="4%" class="itmHd" align="center"><span class="small">D.Mond</span></th>--}}
											<th width="4%" class="itmHd" align="center"><span class="small">Stock Item</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Unit</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Group</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Dimension</span></th>
											<th width="4%" class="itmHd" align="center"><span class="small">Mandatory</span></th>
											<th width="1%"></th>
										</tr>
										</thead>
									</table>
									@php $i=1; $num = count($templates); @endphp
									<input type="hidden" id="rowNum" value="{{$num}}">
									<input type="hidden" id="remitem" name="remove_item">
									@foreach($templates as $trow)
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="1%" >
														<input type="hidden" name="id[]" id="itid_{{$i}}" value="{{$trow->id}}">
														<input type="text" name="ordno[]" id="ordno_{{$i}}" autocomplete="off" class="form-control" value="{{$trow->order_no}}">
													</td>
													<td width="4%">
														<select id="iptype_{{$i}}" class="form-control input-type" name="input_type[]" required>
														<option value="item" {{($trow->input_type=='item')?'selected':''}} >Item</option>
															<option value="text" {{($trow->input_type=='text')?'selected':''}}>Text Box</option>
															<option value="file" {{($trow->input_type=='file')?'selected':''}}>Image Upload</option>
														</select>
													</td>
													<td width="4%" >
														<input type="text" name="type_name[]" id="typnam_{{$i}}" autocomplete="off" value="{{$trow->type_name}}" required class="form-control" placeholder="Type Name">
													</td>
													{{--<td width="4%" >
														<select id="frameno_{{$i}}" class="form-control frame-no" name="frameno[]">
															<option value="0" {{($trow->frame_no==0)?'selected':''}}>No</option>
															<option value="1" {{($trow->frame_no==1)?'selected':''}}>Yes</option>
														</select>
													</td>--}}
													<td width="4%" >
														<select id="stockitm_{{$i}}" class="form-control stock-item" name="is_stockitm[]">
															<option value="">Stock Item</option>
															<option value="1" {{($trow->is_stock==1)?'selected':''}}>Yes</option>
															<option value="0" {{($trow->is_stock==0)?'selected':''}}>No</option>
														</select>
													</td>
													<td width="4%">
														<select id="unit_1" class="form-control unit" name="unit[]" >
															<option value="">Unit</option>
															@foreach($units as $unit)
															<option value="{{$unit->id}}" {{($trow->unit_id==$unit->id)?'selected':''}}>{{$unit->unit_name}}</option>
															@endforeach
														</select>
													</td>
													<td width="4%">
														<select id="group_{{$i}}" class="form-control group" name="group[]">
															<option value="">Select Group</option>
															@foreach($group as $row)
															<option value="{{$row->id}}" {{($trow->group_id==$row->id)?'selected':''}}>{{$row->description}}</option>
															@endforeach
														</select>
													</td>
													<td width="5%">
														<select id="dimsn_{{$i}}" class="form-control dimension" name="dimension[]" >
															<option value="">Dimension</option>
															<option value="1" {{($trow->is_dimension==1)?'selected':''}}>Yes</option>
															<option value="0" {{($trow->is_dimension==0)?'selected':''}}>No</option>
														</select>
													</td>
													<td width="4%">
														<select id="rqrd_{{$i}}" class="form-control required" name="required[]" >
															<option value="">Mandatory</option>
															<option value="1" {{($trow->is_required==1)?'selected':''}}>Yes</option>
															<option value="0" {{($trow->is_required==0)?'selected':''}}>No</option>
														</select>
													</td>
													<td width="1%">
														<button type="button" class="btn-success btn-add-item">
															<i class="fa fa-fw fa-plus-square"></i>
														 </button>
														 <button type="button" class="btn-danger btn-remove-item" data-id="rem_{{$i}}">
															<i class="fa fa-fw fa-minus-square"></i>
														 </button>
													</td>
													
												</tr>
											</table>
										</div>
										@php $i++; @endphp
										@endforeach
									</div>
								</fieldset>
								<hr/>
								<br/>
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('item_template') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
								 </form>
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
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>


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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>

<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";
$(document).ready(function () { 

	$('.itemdivPrnt').find('.btn-add-item:not(:last)').hide();
	if ( $('.itemdivPrnt').children().length == 1 ) {
		$('.itemdivPrnt').find('.btn-remove-item').hide();
	}
	
	var nm = 1;
	@foreach($templates as $trow)
		@if($trow->input_type=='item')
			$('#stockitm_'+nm).show();
			$('#group_'+nm).show();
			$('#dimsn_'+nm).show();
			$('#unit_'+nm).show();
			//$('#frameno_'+nm).show();
		@else
			$('#stockitm_'+nm).hide();
			$('#group_'+nm).hide();
			$('#dimsn_'+nm).hide();
			$('#unit_'+nm).hide();
			//$('#frameno_'+nm).hide();
		@endif
	nm++;
	@endforeach
});
$(function() {	
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Service Item"
    });
	
	var rowNum = $('#rowNum').val();
	$(document).on('click', '.btn-add-item', function(e)  { 
		
        rowNum++;
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('.input-type')).attr('id', 'iptype_' + rowNum);
			newEntry.find($('input[name="ordno[]"]')).attr('id', 'ordno_' + rowNum);
			newEntry.find($('input[name="type_name[]"]')).attr('id', 'typnam_' + rowNum);
			newEntry.find($('.stock-item')).attr('id', 'stockitm_' + rowNum);
			newEntry.find($('.group')).attr('id', 'group_' + rowNum);
			newEntry.find($('.dimension')).attr('id', 'dimsn_' + rowNum);
			newEntry.find($('.unit')).attr('id', 'unit_' + rowNum);
			newEntry.find($('.required')).attr('id', 'rqrd_' + rowNum);
			newEntry.find('input').val(''); 
			
			$('#iptype_' + rowNum).append('<option value="" selected="selected">Select Type</option>');
			newEntry.find($('#ordno_'+rowNum).val(rowNum));
			//newEntry.find($('.frame-no')).attr('id', 'frameno_' + rowNum);
			
			$('#stockitm_'+rowNum).hide();
			$('#group_'+rowNum).hide();
			$('#dimsn_'+rowNum).hide();
			$('#unit_'+rowNum).hide();
			//$('#frameno_'+rowNum).hide();
			
			$('#ipvalues_'+rowNum).prop("readonly", true);
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#itid_'+curNum).val():remitem+','+$('#itid_'+curNum).val();
		$('#remitem').val(ids);
		$(this).parents('.itemdivChld:first').remove();
		
		//ROWCHNG
		$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
		if ( $('.itemdivPrnt').children().length == 1 ) {
			$('.itemdivPrnt').find('.btn-remove-item').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
	
	$(document).on('change', '.input-type', function(e) { 
		var res = this.id.split('_');
		var no = res[1];
		if(this.value=='item') {
			$('#stockitm_'+no).show();
			$('#group_'+no).show();
			$('#dimsn_'+no).show();
			$('#unit_'+no).show();
			//$('#frameno_'+no).show();
		} else {
			$('#stockitm_'+no).hide();
			$('#group_'+no).hide();
			$('#dimsn_'+no).hide();
			$('#unit_'+no).hide();
			//$('#frameno_'+no).hide();
		}
	});
	

});

</script>
@stop
