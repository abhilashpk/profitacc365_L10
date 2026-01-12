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
        <!--end of page level css-->
	
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
	
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
              Enquiry
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                       <i class="fa fa-fw fa-building-o"></i> Contract_Connection
                    </a>
                </li>
                <li>
                    <a href="#">Enquiry</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Enquiry
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmTenant" id="frmTenant"   action="{{url('tenantenquiry/save')}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								

									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Enquiry No:</label>
										<div class="col-sm-4">
											 <div class="input-group">
                                        <input type="text" class="form-control" id="voucher_no" readonly name="voucher_no" value="{{$voucherno}}">
										<span class="input-group-addon inputvn"><i class="fa fa-edit" style="font-size:22px;color:#ff9f2c"></i></span>
									  </div>
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Enquiry Date </label>
										<div class="col-sm-4">
									       <input type="text" class="form-control pull-right" id="enq_date" name="enq_date" value="{{date('d-m-Y')}}" autocomplete="off" data-language='en'  />
										</div>
									</div>
										
                               
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Building</label>
										<div class="col-sm-4">
											<select class="form-control" name="building_id" id="building_id"/>
													<option value="">Select</option>
													@foreach($building as $row)
													<option value="{{$row->id}}" >{{$row->buildingcode}}</option>
													@endforeach
											</select>
										</div>
												
										<label for="input-text" class="col-sm-2 control-label">Flat</label>
										<div class="col-sm-4">
									     <select class="form-control" name="flat_no" id="flat_no" />
										     <option value="">Select</option>
										</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Tenant</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" id="customer_account"  name="customer_account" autocomplete="off" data-toggle="modal" data-target="#customer_modal">
													<input type="hidden" id="customer_id" name="customer_id">
										</div>
												
										
									</div>
										
                                
                                <div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">Other Details</label>
										<div class="col-sm-10">
											<textarea class="form-control" id="description" name="description" autocomplete="off"></textarea>
										</div>
												
		                        </div>
								<hr/>
								<div class="filedivPrnt">
									<div class="filedivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_1">Upload Image</label>
											<div class="col-sm-9">
												<input type="file" id="input-51" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('sales_invoice/upload/')}}">
												<div id="files_list_1"></div>
												<p id="loading_1"></p>
												<input type="hidden" name="photo_name[]" id="photo_name_1">
											</div>
											<div class="col-sm-1">
												<button type="button" class="btn-success btn-add-file" id="btn_1">
													<i class="fa fa-fw fa-plus-square"></i>
												</button>
												<button type="button" class="btn-danger btn-remove-file">
													<i class="fa fa-fw fa-minus-square"></i>
												 </button>
											</div>
											<label for="input-text" class="col-sm-2 control-label filedslbl" id="lblifd_1">Description</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="imgdesc_1" name="imgdesc[]" placeholder="File Name" autocomplete="off">
											</div>
										</div>
									</div>
								</div>

									
									
									
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('tenantenquiry') }}" class="btn btn-danger">Cancel</a>
										</div>
									</div>
								 </form>
                        </div>
                    </div>
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

        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-fileinput/js/fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>

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

<script src="{{asset('assets/js/jquery.fileupload.js')}}"></script>


<script>
"use strict";
$('#enq_date').datepicker( { autoClose:true ,dateFormat: 'dd-mm-yyyy' } );

 $(document).ready(function () {
	 var urlcode = "{{ url('flatmaster/checkcode/') }}";
     $('#frmFlat').bootstrapValidator({
         fields: {
 			building_id: {
                 validators: {
                     notEmpty: {
                         message: 'Building is required and cannot be empty!'
                     }
                 }
             },
 			flat_no: {
                 validators: {
                     notEmpty: {
                         message: 'Flat no is required and cannot be empty!'
                     },
					 remote: { url: urlcode,
						   data: function(validator) {
							return { flat_no: validator.getFieldElements('flat_no').val(),bid: validator.getFieldElements('building_id').val() };
						  },
						  message: 'Flat no is not available'
						}
                 }
				 
             },
 			flat_name: {
                 validators: {
                     notEmpty: {
                        message: 'Flat name is required and cannot be empty!'
                     }
                 }
             }
         }
        
     }).on('reset', function (event) {
         $('#frmFlat').data('bootstrapValidator').resetForm();
     });
});

	$('#customer_account').click(function() {
	let custurl = "{{ url('sales_invoice/customer_data/') }}";
	$('#customerData').load(custurl, function(result) {
		$('#myModal').modal({show:true});
		$('.input-sm').focus()
	});
});
	
$(document).on('click', '.custRow', function(e) {
	$('#customer_account').val($(this).attr("data-name"));
	$('#customer_id').val($(this).attr("data-id"));
	e.preventDefault();
});
$('#input-51').fileupload({
		dataType: 'json',
		add: function (e, data) {
			$('#loading_1').text('Uploading...');
			data.submit();
		},
		done: function (e, data) {
			var pn = $('#photo_name_1').val();
			$('#photo_name_1').val( (pn=='')?data.result.file_name:pn+','+data.result.file_name );
			$('#loading_1').text('Completed.');
		}
	});
	
	var fNum;
	$(document).on('click', '.btn-add-file', function(e)  { 
		var res = this.id.split('_');
		fNum = res[1];
	    fNum++;
		$.ajax({
			url: "{{ url('job_order/get_fileform') }}",
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

	$(document).on('change', '#building_id', function(e) {
		var bid = $(this).val();
		$.get("{{ url('buildingmaster/get_flat/') }}/" + bid, function(data) {
			var dat = $.parseJSON(data); 
			$('#flat_no').find('option').remove().end();
			$.each(dat, function(key, value) {   
				$('#flat_no').find('option').end()
				 .append($("<option></option>")
							.attr("value",value.id)
							.text(value.flat_no)); 
			});
		});
	});

$(function() {	
	
   
});
</script>
@stop
