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
                Contract
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Contract</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Contract 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmContract" id="frmContract" action="{{ url('contract/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract No</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="contract_no" name="contract_no" value="{{$no}}" readonly>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="date" autocomplete="off" readonly id="date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                 <label for="input-text" class="col-sm-2 control-label"><b>Customer</b></label>
                                    <div class="col-sm-10">
										<input type="text" name="customer_name" id="customer_name" class="form-control" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer" readonly>
										<input type="hidden" name="customer_id" id="customer_id">
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract Type</label>
                                    <div class="col-sm-10">
                                        <select id="select1" multiple style="width:100%" class="form-control select2" required name="type_id[]" >
										<?php foreach($types as $row) { ?>
										<option value="{{$row->id}}">{{$row->name}}</option>
										<?php } ?>
										</select>
                                    </div>
                                </div>
								
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contarct Start Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="start_date" autocomplete="off" data-language='en' readonly id="start_date" />
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contract Duration</label>
                                    <div class="col-sm-10">
										<select class="form-control" name="duration" id="duration" />
										<option value="">Select</option>
										<option value="182">6 Months</option>
										<option value="365">12 Months</option>
										<option value="730">24 Months</option>
										<option value="1095">36 Months</option>
										<option value="1460">48 Months</option>
										<option value="1825">60 Months</option>
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Contarct End Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" name="end_date" autocomplete="off" readonly id="end_date" />
                                    </div>
                                </div>
								
								
								
								<div class="form-group">
                                 <label for="input-text" class="col-sm-2 control-label">Machine</label>
                                    <div class="col-sm-10">
										 <select id="select24" class="form-control" name="machine_id" style="width:100%">
										 <option></option>
										 @foreach($machine as $mrow)
										 <option value="{{$mrow->id}}">{{$mrow->brand.'  -  '.$mrow->name.'  ('.$mrow->model.')'}}</option>
										 @endforeach
										 </select>
									</div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Paper</label>
                                    <div class="col-sm-10">
                                        <select id="select22" class="form-control select2" name="paper_id[]" multiple style="width:100%">
											@foreach($paper as $row)
											<option value="{{$row->id}}">{{$row->name}}</option>
											@endforeach
										</select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="remarks" rows="4" name="remarks" ></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('contract') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                
								<div id="customer_modal" class="modal fade animated" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Select Customer</h4>
											</div>
											<div class="modal-body" id="customerData">
															
											</div>
											<div class="modal-footer">
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
        <!-- end of page level js -->

<script>

$('#start_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
$('#end_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

"use strict";

$(document).ready(function () {
	
	//$('#start_date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy' } );
    $('#date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

	$("#select1").select2({
        theme: "bootstrap",
        placeholder: "Contract Type"
    });


	
	$("#multiselect5").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
	
	$('#select24').select2({
        allowClear: true,
        theme: "bootstrap",
        placeholder: "Select Machine"
    });
	
	$("#select22").select2({
        theme: "bootstrap",
        placeholder: "Select Paper"
    });
	
	var urlname = "{{ url('contract/check_code/') }}";
    $('#frmContract').bootstrapValidator({
        fields: {
			customer_name: {
                validators: {
                    notEmpty: {
                        message: 'Customer name is required and cannot be empty!'
                    }
                }
            },
			multiselect5: {
                validators: {
                    notEmpty: {
                        message: 'Machine is required and cannot be empty!'
                    }
                }
            },
			start_date: {
                validators: {
                    notEmpty: {
                        message: 'Start date is required and cannot be empty!'
                    }
                }
            },
			duration: {
                validators: {
                    notEmpty: {
                        message: 'Duration is required and cannot be empty!'
                    }
                }
            },
			select24: {
                validators: {
                    notEmpty: {
                        message: 'Machine is required and cannot be empty!'
                    }
                }
            },
			select22: {
                validators: {
                    notEmpty: {
                        message: 'Paper is required and cannot be empty!'
                    }
                }
            }
        }
        
    }).on('reset', function (event) {
        $('#frmContract').data('bootstrapValidator').resetForm();
    });
	
	var custurl;
	$('#customer_name').click(function() { 
		if($('#department_id').length)
			custurl = "{{ url('sales_order/customer_data/') }}"+'/'+$('#department_id option:selected').val();
		else
			custurl = "{{ url('sales_order/customer_data/') }}";
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});
			$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		
		e.preventDefault();
	});
	
	$(document).on('change', '#duration', function(e) {
		
		var vdate = $('#start_date').val();
		var arr = vdate.split('-');  console.log('GG '+arr[1]+'-'+arr[0]+'-'+arr[2]);
		var someDate = new Date(arr[1]+'-'+arr[0]+'-'+arr[2]);
		var numberOfDaysToAdd = parseInt(this.value); 
		someDate.setDate(someDate.getDate() + numberOfDaysToAdd); 
		var dd = someDate.getDate();
		var mm = someDate.getMonth() + 1;
		var y = someDate.getFullYear();
		//var someFormattedDate = dd + '-'+ mm + '-'+ y;
		
		var someFormattedDate = ('0' + someDate.getDate()).slice(-2) + '-'+ ('0' + (someDate.getMonth()+1)).slice(-2) + '-'+ someDate.getFullYear();
			 
		$('#end_date').val(someFormattedDate);
		//console.log(someDate+' nw dt '+someFormattedDate);
		
	});
	
});

</script>
@stop
