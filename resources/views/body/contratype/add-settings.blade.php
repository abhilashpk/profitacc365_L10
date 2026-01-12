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
              Contract Type
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> RealEstate
                    </a>
                </li>
                <li>
                    <a href="#">Contract Type</a>
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
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> New Contract Type 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmContratype" id="frmContratype" action="{{url('contra_type/save-settings')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Building</label>
                                    <div class="col-sm-6">
										<select class="form-control" name="buildingid" id="buildingid" />
										<option value="">Select Building</option>
										@foreach($buildingmaster as $row)
                                        <option value="{{$row->id}}">{{$row->buildingcode}}</option>
                                        @endforeach
										</select>
                                    </div>
                                </div>
								
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Contract Type</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="type" name="type" autocomplete="off" placeholder="Contract Type">
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-3 control-label">Increment No.</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="increment_no" name="increment_no" autocomplete="off" placeholder="Increment No.">
										</div>
									</div>
									
									<hr/>
									
									<div class="form-group">
										<div class="col-sm-3">
											<label for="input-text" class="col-sm-3 control-label"><b>Title</b></label>
										</div>
										<div class="col-sm-4">
											<label for="input-text" class="control-label"><b>Account Name</b></label>
										</div>
										<div class="col-sm-2">
											<label for="input-text" class="control-label"><b>VAT Include</b></label>
										</div>
									</div>
									<div class="itemdivPrnt">
										<div class="itemdivChld">
											<div class="form-group">
												<div class="col-sm-3">
												<input type="text" class="form-control" id="title_1" name="title[]" autocomplete="off" placeholder="Account Title">
												</div>
												<div class="col-sm-4">
													<input type="text" class="form-control other-account" id="acname_1" name="acname[]" autocomplete="off" placeholder="Account Name." data-toggle="modal" data-target="#account_modal">
													<input type="hidden" id="acid_1" name="accountid[]">
												</div>
												<div class="col-sm-2">
													<select id="istax_1" class="form-control tax" style="width:100%" name="istax[]">
														<option value="">Tax Include</option>
														<option value="0">No</option>
														<option value="1">Yes</option>
													</select>
												</div>
												<div class="col-xs-1">
													 <button type="button" class="btn btn-success btn-xs btn-add-item" >
														<i class="fa fa-fw fa-plus-square"></i>
													 </button>
													 <button type="button" class="btn btn-danger btn-xs btn-remove-item" data-id="">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
											</div>
										</div>
									</div>
									
																		
									<div class="form-group">
										<label for="input-text" class="col-sm-4 control-label"></label>
										<div class="col-sm-8">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('contra_type') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
								 </form>
								 </div>
								 
								 <div id="account_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Account</h4>
											</div>
											<div class="modal-body" id="account_data">
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
        <!-- end of page level js -->

<script>
"use strict";
var rowNum = 1;
$(document).ready(function () {
	var urltype = "{{ url('contra_type/check_type/') }}";
     $('#frmContratype').bootstrapValidator({
         fields: {
 			buildingid: {
                 validators: {
                     notEmpty: {
                         message: 'Building is required and cannot be empty!'
                     },
					 remote: { url: urltype,
							   data: function(validator) {
								return { buildingid: validator.getFieldElements('buildingid').val() };
							  },
							  message: 'Contract type of this building is already created.'
                    }
                 }
             },
 			type: {
                 validators: {
                     notEmpty: {
                         message: 'Type is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmContratype').data('bootstrapValidator').resetForm();
     });
});

$(document).on('change', '#buildingid', function(e) { 
	var bid = $(this).val();
	$.get("{{ url('buildingmaster/getprefix/') }}/" + bid, function(data) {
		var pre = $.parseJSON(data);
		console.log('dd '+pre.val); $('#type').val(pre.val);
	});
});

$(function(){
	
	$(document).on('click', '.btn-add-item', function(e) { 
        rowNum++; 
		e.preventDefault();
        var controlForm = $('.controls .itemdivPrnt'),
            currentEntry = $(this).parents('.itemdivChld:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find($('input[name="title[]"]')).attr('id', 'title_' + rowNum);
			newEntry.find($('input[name="acname[]"]')).attr('id', 'acname_' + rowNum);
			newEntry.find($('input[name="accountid[]"]')).attr('id', 'acid_' + rowNum);
			newEntry.find($('.tax')).attr('id', 'istax_' + rowNum);
			
			newEntry.find('input').val(''); 
			
			controlForm.find('.btn-add-item:not(:last)').hide();
			controlForm.find('.btn-remove-item').show();
	 }).on('click', '.btn-remove-item', function(e) { 
		
			$(this).parents('.itemdivChld:first').remove();
			$('.itemdivPrnt').find('.itemdivChld:last').find('.btn-add-item').show();
			if ( $('.itemdivPrnt').children().length == 1 ) {
				$('.itemdivPrnt').find('.btn-remove-item').hide();
			}
			
			e.preventDefault();
			return false;
	});
	
			
	var acurl = "{{ url('account_master/get_accounts/') }}";
	$(document).on('click', '.other-account', function(e) {
		var res = this.id.split('_');
		var curNum = res[1]; 
		$('#account_data').load(acurl+'/'+curNum, function(result){ 
			$('#myModal').modal({show:true});
		});
	});
	
	//accounts select
	$(document).on('click', '.accountRow', function(e) { 
		
		var num = $('#num').val();
		if($('#acname_'+num).length)
			$('#acname_'+num).val( $(this).attr("data-name") );
		
		if($('#acid_'+num).length)
			$('#acid_'+num).val( $(this).attr("data-id") );
		
		
		
	});
});

</script>
@stop
