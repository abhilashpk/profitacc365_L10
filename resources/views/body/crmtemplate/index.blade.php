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
	.itmHd { text-align: center !important; }
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
              CRM Template
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Sales Order Job
                    </a>
                </li>
                <li>
                    <a href="#">CRM Template</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> CRM Template
                            </h3>
							
                           <div class="pull-right">
						  
							</div>
								 
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmItemTemp" id="frmItemTemp" action="{{url('crm_template/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
																	
								<fieldset>
									<legend style="margin-bottom:0px !important;"></legend>
									<div class="itemdivPrnt">
											<table border="0" class="table-dy-row">
											<thead>
												<tr>
													<th width="4%" class="itmHd" align="center"><span class="small"> No. of Text Box</span></th>
													<th width="4%" class="itmHd" align="center"><span class="small"> Label 1</span></th>
													<th width="4%" class="itmHd" align="center"><span class="small"> Values 1</span></th>
													<th width="3%" class="itmHd" align="center"><span class="small"> Mandatory</span></th>
													<th width="4%" class="itmHd" align="center"><span class="small"> Label 2</span></th>
													<th width="4%" class="itmHd" align="center"><span class="small"> Values 2</span></th>
													<th width="4%" class="itmHd" align="center"><span class="small"> Mandatory</span></th>
													<th width="1%"></th>
												</tr>
												</thead>
											</table>
											@php $i=1; $num = count($items); @endphp
											<input type="hidden" id="remitem" name="remove_item">
											@if($num > 0)
											<input type="hidden" id="rowNum" value="{{$num}}">
											@foreach($items as $row)
											<div class="itemdivChld">	
											<table border="0" class="table-dy-row">
												<tr>
													<td width="4%" >
														<select id="tbox_{{$i}}" class="form-control select2" style="width:100%" name="text_no[]">
															<option value="1" {{($row->text_no==1)?'selected':'' }}>1</option>
															<option value="2" {{($row->text_no==2)?'selected':'' }}>2</option>
														</select>
														<input type="hidden" name="input_text[]" id="txt_{{$i}}" value="Text Box">
													</td>
													<td width="4%" >
														<input type="hidden" name="id[]" id="id_{{$i}}" value="{{$row->id}}">
														<input type="text" name="label[]" id="lbl_{{$i}}" value="{{$row->label}}" autocomplete="off" required class="form-control">
													</td>
													<td width="4%" >
														<input type="text" name="values1[]" id="val1_{{$i}}" value="{{$row->values1}}" autocomplete="off" class="form-control">
													</td>
													<td width="4%" >
														<select id="req1_{{$i}}" class="form-control r1select2" style="width:100%" name="req1[]">
															<option value="0" {{($row->reqd1==0)?'selected':'' }}>No</option>
															<option value="1" {{($row->reqd1==1)?'selected':'' }}>Yes</option>
														</select>
													</td>
													<td width="4%" >
														<input type="text" name="label2[]" id="lbl2_{{$i}}" value="{{$row->label2}}" autocomplete="off" required class="form-control">
													</td>
													<td width="4%" >
														<input type="text" name="values2[]" id="val2_{{$i}}" value="{{$row->values2}}" autocomplete="off" class="form-control">
													</td>
													<td width="4%" >
														<select id="req2_{{$i}}" class="form-control r2select2" style="width:100%" name="req2[]">
															<option value="0" {{($row->reqd2==0)?'selected':'' }}>No</option>
															<option value="1" {{($row->reqd2==1)?'selected':'' }}>Yes</option>
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
										@else
											<input type="hidden" id="rowNum" value="1">
											<div class="itemdivChld">	
												<table border="0" class="table-dy-row">
													<tr>
														<td width="4%" >
															<select id="tbox_1" class="form-control select2" style="width:100%" name="text_no[]">
																<option value="1">1</option>
																<option value="2">2</option>
															</select>
															<input type="hidden" name="input_text[]" id="txt_1"  value="Text Box">
														</td>
														<td width="4%" >
															<input type="hidden" name="id[]" id="id_1" >
															<input type="text" name="label[]" id="lbl_1"  autocomplete="off" required class="form-control">
														</td>
														<td width="4%" >
														<select id="req1_1" class="form-control select2" style="width:100%" name="req1[]">
															<option value="0">No</option>
															<option value="1">Yes</option>
														</select>
													</td>
														<td width="4%" >
															<input type="text" name="label2[]" id="lbl2_1"  autocomplete="off" required class="form-control">
														</td>
														<select id="req2_1" class="form-control select2" style="width:100%" name="req2[]">
															<option value="0">No</option>
															<option value="1">Yes</option>
														</select>
														<td width="1%">
															<button type="button" class="btn-success btn-add-item">
																<i class="fa fa-fw fa-plus-square"></i>
															 </button>
															 <button type="button" class="btn-danger btn-remove-item" data-id="rem_1">
																<i class="fa fa-fw fa-minus-square"></i>
															 </button>
														</td>
													</tr>
												</table>
											</div>
										@endif
									</div>
								</fieldset>
								<hr/>
								<br/>
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('crm_template') }}" class="btn btn-danger">Cancel</a>
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
	var i=0;
	@foreach($items as $row)
		i++;
		@if($row->text_no==1)
			$('#lbl2_'+i).hide();
			$('#req2_'+i).hide();
			$('#lbl2_'+i).attr("required", false);
			$('#val2_'+i).hide();
		@else
			$('#lbl2_'+i).show();
			$('#req2_'+i).show();
			$('#lbl2_'+i).attr("required", true);
			$('#val2_'+i).show();
		@endif
	@endforeach
});
$(function() {	

	var rowNum = $('#rowNum').val();
	$(document).on('click', '.btn-add-item', function(e)  { 
		
        rowNum++;
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="label[]"]')).attr('id', 'lbl_' + rowNum);
			newEntry.find($('input[name="input_text[]"]')).attr('id', 'txt_' + rowNum);
			newEntry.find($('input[name="id[]"]')).attr('id', 'id_' + rowNum);
			newEntry.find($('.select2')).attr('id', 'tbox_' + rowNum);
			newEntry.find($('input[name="label2[]"]')).attr('id', 'lbl2_' + rowNum);
			newEntry.find($('#lbl_'+rowNum).val('')); 
			newEntry.find($('#lbl2_'+rowNum).val('')); 
			newEntry.find($('#id_'+rowNum).val(''));
			newEntry.find($('input[name="values1[]"]')).attr('id', 'val1_' + rowNum);
			newEntry.find($('input[name="values2[]"]')).attr('id', 'val2_' + rowNum);
			newEntry.find($('#val1_'+rowNum).val('')); 
			newEntry.find($('#val2_'+rowNum).val('')); 
newEntry.find($('.r1select2')).attr('id', 'req1_' + rowNum);			
newEntry.find($('.r2select2')).attr('id', 'req2_' + rowNum);			
			//newEntry.find($('#tbox_'+rowNum+' select').val(1).change()); 
			$('#tbox_'+rowNum).val(1).change();
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
			
    }).on('click', '.btn-remove-item', function(e)
    { 
		var res = $(this).attr('data-id').split('_');
		var curNum = res[1]; var ids;
		
		var remitem = $('#remitem').val();
		ids = (remitem=='')?$('#id_'+curNum).val():remitem+','+$('#id_'+curNum).val();
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
	
	$(document).on('change', '.select2', function(e)  { 
		var res = this.id.split('_');
		var no = res[1];
		if(this.value==1) {
			$('#lbl2_'+no).hide();
			$('#req2_'+no).hide();
			$('#lbl2_'+no).attr("required", false);
			$('#val2_'+no).hide();
		} else {
			$('#lbl2_'+no).show();
			$('#req2_'+no).show();
			$('#lbl2_'+no).attr("required", true);
			$('#val2_'+no).show();
		}
	});

});

</script>
@stop
