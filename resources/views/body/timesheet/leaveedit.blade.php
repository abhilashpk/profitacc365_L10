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
    <link href="{{asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" media="screen" type="text/css" href="{{asset('assets/vendors/summernote/summernote.css')}}">
    <link href="{{asset('assets/vendors/trumbowyg/css/trumbowyg.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/form_editors.css')}}">
        
	<link rel="stylesheet" href="{{asset('assets/vendors/datetime/css/jquery.datetimepicker.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
	
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
                Leave
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> TimeSheet
                    </a>
                </li>
                <li>
                    <a href="#">Leave</a>
                </li>
                <li class="active">
                    Edit
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Leave Entry 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmCategory" id="frmCategory" action="{{ url('wage_entry/time/leave_update/'.$leave->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="<?php echo $leave->id; ?>">

                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="employee_code" name="employee_code" value="{{ $leave->code }}" readonly>
                                    </div>
                                </div>
				
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="employee_name" name="employee_name" value="{{ $leave->name }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Leave Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control " name="date" value="{{date('d-m-Y',strtotime($leave->date))}}" id="date" data-language='en' readonly autocomplete="off"/>
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Desigantion</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="designation" name="designation" readonly value="{{ $leave->designation }}">
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="category" name="category" readonly value="{{ $leave->category_name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Leave Status</label>
                                    <div class="col-sm-10">
                                    <select id="leave_status" name="leave_status" class="form-control ">
							                           <option value="UP" <?php if($leave->leave_status=='UP') echo 'selected';?>>Unpaid</option>
													   <option value="P" <?php if($leave->leave_status=='P') echo 'selected';?>>Paid</option>
						                                </select>
                                         </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Reason</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="leave_reason" name="leave_reason" placeholder="Reason for Leave" value="{{$leave->leave_reason}}">
                                    </div>
                                </div>

                                <input type="hidden" name="rem_photo_id" id="rem_photo_id">
								  <div class="filedivPrnt">
								  <?php if(count($photos)>0) { $i=0; ?>
										@foreach($photos as $prow)
										<?php $i++; ?>
										<div class="filedivChld">
											<div class="form-group">
												<label for="input-text" class="col-sm-2 control-label">Uploaded Image {{$i}}</label>
												<div class="col-sm-9">
													<div class="file-preview">
														<div class="file-drop-disabled">
															<div class="file-preview-thumbnails">
																<div class="file-live-thumbs">
																	<a href="{{asset('uploads/leave/'.$prow->photo)}}" target="_blank">View Image {{$i}}</a>
																</div>
															</div>
															<input type="hidden" name="photo_id[]" id="photo_id_{{$i}}" value="{{$prow->id}}">
															<input type="hidden" name="photo_name[]" id="photo_name_{{$i}}" value="{{$prow->photo}}">
															<div class="clearfix"></div>    <div class="file-preview-status text-center text-success"></div>
															<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
														</div>
													</div>
												</div>
												<div class="col-sm-1">
													<button type="button" class="btn-success btn-add-file" id="btn_{{$i}}">
														<i class="fa fa-fw fa-plus-square"></i>
													</button>
													<button type="button" class="btn-danger btn-remove-file" data-id="{{$prow->id}}">
														<i class="fa fa-fw fa-minus-square"></i>
													 </button>
												</div>
												<label for="input-text" class="col-sm-2 control-label filedslbl" id="lblifd_{{$i}}">Description</label>
												<div class="col-sm-10">
													<input type="text" class="form-control" id="imgdesc_{{$i}}" name="imgdesc[]" value="{{$prow->description}}" placeholder="Description" autocomplete="off">
												</div>
												
											</div>
										</div>
										@endforeach
								  <?php } else { ?>
										<div class="filedivChld">
										<div class="form-group">
											<label for="input-text" class="col-sm-2 control-label filelbl" id="lblif_1">Upload Image 1</label>
											<div class="col-sm-9">
												<input type="file" id="input-51" name="photos" class="file-loading" data-show-preview="true" data-url="{{url('wage_entry/leave/upload/')}}">
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
												<input type="text" class="form-control" id="imgdesc_1" name="imgdesc[]" placeholder="Description" autocomplete="off">
											</div>
										</div>
									</div>
								  <?php } ?>
								  </div>		

                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" <?php if($user!='Admin') echo 'disabled';?> onClick="location.href='{{ url('wage_entry/leave/approve/'.$leave->id)}}'"class="btn btn-warning">Approval</button>
                                   <a href="{{ url('wage_entry/timesheet/leave/') }}" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                                                            
                                
                            </form>
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
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
	
});
$(function() {
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
			url: "{{ url('job_order/get_fileform/PL') }}",
			type: 'post',
			data: {'no':fNum},
			success: function(data) {
				$('.filedivPrnt').append(data);
				return true;
			}
		}) 
		
	}).on('click', '.btn-remove-file', function(e) { console.log('id'+ $(this).attr("data-id"));
		
		var remitem = $('#rem_photo_id').val(); var ids;
		ids = (remitem=='')?$(this).attr("data-id"):remitem+','+$(this).attr("data-id");
		$('#rem_photo_id').val(ids);
		
		$(this).parents('.filedivChld:first').remove();
		
		$('.filedivPrnt').find('.filedivChld:last').find('.btn-add-file').show();
		if ( $('.filedivPrnt').children().length == 1 ) {
			$('.filedivPrnt').find('.btn-remove-file').hide();
		}
		
		e.preventDefault();
		return false;
	});
	
});
</script>
@stop
