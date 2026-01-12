@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
         
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/formelements.css')}}">
        <!--end of page level css-->
	
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
	<style>
		input[type=number]::-webkit-inner-spin-button, 
		input[type=number]::-webkit-outer-spin-button { 
		  -webkit-appearance: none; 
		  margin: 0; 
		}
	</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Packing List
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-briefcase"></i> Inventory
                    </a>
                </li>
                <li>
                    <a href="#">Packing List</a>
                </li>
                <li class="active">
                    Add
                </li>
            </ol>
        </section>
        <!--section ends-->
		
		<!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        
		@if(Session::has('error'))
		<div class="alert alert-danger">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
		
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="fa fa-fw fa-crosshairs"></i> New
                            </h3>
							
							<div class="pull-right">
							<?php if($printid) { ?>
								@can('so-print')
								 <a href="{{ url('packing_list/print/'.$printid.'/70') }}" target="_blank" class="btn btn-info btn-sm">
										<span class="btn-label">
										<i class="fa fa-fw fa-print"></i>
									</span> 
								</a>
								@endcan
							<?php } ?>
							</div>
                        </div>
                        <div class="panel-body">
							<div class="controls"> 
                            <form class="form-horizontal" role="form" method="POST" name="frmPackingList" id="frmPackingList" action="{{ url('packing_list/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><b>PL. No.</b></label>
									<input type="hidden" name="curno" id="curno" value="{{(old('curno'))?old('curno'):$voucherno->no}}">
									<input type="hidden" name="default_location" value="{{ Auth::user()->location_id }}">
									<div class="col-sm-10">
										<?php if($voucherno->prefix!='') { ?>
										<div class="input-group">
											<span class="input-group-addon">{{$voucherno->prefix}}</span>
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
										<?php } else { ?>
										<div class="input-group">
											<input type="text" class="form-control" id="voucher_no" name="voucher_no" <?php if($voucherno->autoincrement==1) { ?> readonly value="{{$voucherno->no}}" <?php } else { ?> value="{{old('voucher_no')}}" <?php } ?>>
											<input type="hidden" value="{{$voucherno->prefix}}" name="prefix">
											<input type="hidden" value="{{$voucherno->voucher_type}}" name="voucher_type">
											<input type="hidden" value="{{$voucherno->autoincrement}}" name="autoincrement">
											<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
										</div>
										<?php } ?>
										
                                    </div>
									
                                </div>
                                								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">PL. Date</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control pull-right" autocomplete="off" name="voucher_date" data-language='en' id="voucher_date" placeholder="{{date('d-m-Y')}}"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                   <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label <?php if($errors->has('customer_name')) echo 'form-error';?>"><b>Customer</b></label></font>
                                    <div class="col-sm-10">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control <?php if($errors->has('customer_name')) echo 'form-error';?>" value="{{ old('customer_name') }}" autocomplete="off" data-toggle="modal" data-target="#customer_modal" placeholder="Customer">
										<input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">
									</div>
                                </div>
								
								<?php if($formdata['document_id']==1) { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"> Invoice#</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="quotation_id" readonly name="quotation_id" placeholder="Invoice No" autocomplete="off" onclick="getInvoice()">
                                    </div>
                                </div>
								<?php } else { ?>
								<input type="hidden" name="quotation_id" id="quotation_id">
								<?php } ?>
								
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
										<a href="{{ url('packing_list') }}" class="btn btn-danger">Cancel</a>
										<a href="{{ url('packing_list/add') }}" class="btn btn-warning">Clear</a>
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
            </div>
       
            <!--main content-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

<script src="{{asset('assets/vendors/select2/js/select2.js')}}" type="text/javascript"></script>
        <!-- end of page level js -->

<script src="{{asset('assets/vendors/moment/js/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

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


<script>
"use strict";
var srvat=<?php echo ($vatdata)?$vatdata->percentage:'0';?>; var packing = 1;
$(document).ready(function () { 
	$('.parnt-job').hide();
	$('.locPrntItm').toggle();
	<?php if(!old('item_code')) { ?>
	$('.btn-remove-item').hide();
	<?php } ?>
	$("#currency_rate").prop('disabled', true);
	$("#currency_id").prop('disabled', true);
	$("#fc_label").toggle(); $("#c_label").toggle(); $("#total_fc").toggle(); $("#discount_fc").toggle(); $("#net_amount_fc").toggle(); $("#vat_fc").toggle();
	$('.infodivPrnt').toggle(); $('.infodivPrntItm').toggle(); $("#subtotal_fc").toggle();
	var urlcode = "{{ url('packing_list/checkrefno/') }}";
	var urlvchr = "{{ url('packing_list/checkvchrno/') }}"; //CHNG
    $('#frmPackingList').bootstrapValidator({
        fields: {
			/* voucher_no: { //CHNG
                validators: {
                    notEmpty: {
                        message: 'The voucher no is required and cannot be empty!'
                    },
					remote: {
                        url: urlvchr,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('voucher_no').val()
                            };
                        },
                        message: 'This SO. No. is already exist!'
                    }
                }
            }, */
			reference_no: {
                validators: {
                    /* notEmpty: {
                        message: 'The reference no id is required and cannot be empty!'
                    }, */
					remote: {
                        url: urlcode,
                        data: function(validator) {
                            return {
                                code: validator.getFieldElements('reference_no').val()
                            };
                        },
                        message: 'The reference no is not available'
                    }
                }
            },
			//voucher_date: { validators: { notEmpty: { message: 'The voucher date is required and cannot be empty!' } }},
			//description: { validators: { notEmpty: { message: 'The description is required and cannot be empty!' } }},
			//customer_name: { validators: { notEmpty: { message: 'The customer name is required and cannot be empty!' } }},
			//'item_code[]': { validators: { notEmpty: { message: 'The item code is required and cannot be empty!' } }},
			//'item_name[]': { validators: { notEmpty: { message: 'The item description is required and cannot be empty!' } }}
        }
        
    }).on('reset', function (event) {
        $('#frmPackingList').data('bootstrapValidator').resetForm();
    });
	
});

$(function() {	
	
	
	$('#voucher_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );
	
	$('.inputvn').on('click', function(e) {
		$('#voucher_no').attr("readonly", false);
	});
	
	
		function refreshSlNum(){
				$('#listTable tbody tr').each(function(index){
				$(this).find('.sino').text(index + 1);

			})
		}

	
	
	var custurl = "{{ url('packing_list/customer_data/') }}";
	$('#customer_name').click(function() {
		$('#customerData').load(custurl, function(result) {
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.custRow', function(e) {
		$('#customer_name').val($(this).attr("data-name"));
		$('#customer_id').val($(this).attr("data-id"));
		e.preventDefault();
	});

	var supurl = "{{ url('packing_list/salesman_data/') }}";
	$('#salesman').click(function() {
		$('#salesmanData').load(supurl, function(result) {
			$('#myModal').modal({show:true});$('.input-sm').focus()
		});
	});
	
	$(document).on('click', '.salesmanRow', function(e) {
		$('#salesman').val($(this).attr("data-name"));
		$('#salesman_id').val($(this).attr("data-id"));
		e.preventDefault();
	});
	
	
	
});

var popup;

function getInvoice() { 
	if($("#customer_name").val()=='') {
		alert('Please select a customer first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var customer_id = $("#customer_id").val();
	var sourl = "{{ url('packing_list/get_invoice/') }}/"+customer_id+"/SI";
	popup = window.open(sourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
function getPackingList() { 
	
	var ht = $(window).height();
	var wt = $(window).width();
	var pourl = "{{ url('packing_list/get_packinglist/') }}";
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}

function getPO() { 
	if($("#job_id option:selected").val()=='') {
		alert('Please select a Job No first!');
		return false
	}
	var ht = $(window).height();
	var wt = $(window).width();
	var job_id = $("#job_id option:selected").val();
	var pourl = "{{ url('purchase_order/get_jobpo/') }}/"+job_id;
	popup = window.open(pourl, "Popup", "width=900,height=500,top=100,left=200");
	popup.focus();
	return false
}
</script>
@stop


