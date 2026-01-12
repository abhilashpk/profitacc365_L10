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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Employee Document
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-tower"></i>  HR Management
                    </a>
                </li>
                <li>
                    <a href="#">Employee Document</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Employee Document 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmDocument" id="frmDocument" enctype="multipart/form-data" action="{{ url('employee_document/update/'.$docrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="<?php echo $docrow->id; ?>">
								<input type="hidden" name="current_image" value="<?php echo $docrow->file_name; ?>">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee</label>
                                    <div class="col-sm-10">
                                        <select id="employee_id" class="form-control select2" style="width:100%" name="employee_id">
											@foreach($employee as $row)
											<option value="{{$row->id}}" <?php if($docrow->employee_id==$row->id) echo 'selected';?>>{{$row->name}}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{$docrow->name}}">
                                    </div>
                                </div>
                                
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control">{{$docrow->description}}</textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Attachment</label>
									<div class="col-sm-10">
										<input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true">
										<?php if($docrow->file_name!='') { ?>
										<a href="{{asset('uploads/employee_document/'.$docrow->file_name)}}" target="_blank">View</a>
										<?php } ?>
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('employee_document') }}" class="btn btn-danger">Cancel</a>
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
        <!-- end of page level js -->
<script src="{{asset('assets/vendors/datetime/js/jquery.datetimepicker.full.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/custom_js/advanceddate_pickers.js')}}"></script>

<script>
"use strict";

$('#issue_date').datepicker( { 
		dateFormat: 'dd-mm-yyyy',
		autoclose: 1
});

$('#expiry_date').datepicker( { 
		dateFormat: 'dd-mm-yyyy',
		autoclose: 1
});
	
$(document).ready(function () {
	var url = "{{ url('category/checkname/') }}";
    $('#frmDocument').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The document name is required and cannot be empty!'
                    }
                }
            },
			image: { validators: {
						file: {
							  extension: 'jpg,jpeg,png,pdf',
							  type: 'image/jpg,image/jpeg,image/png,application/pdf',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (jpg,jpeg,png,pdf) and 5 MB at maximum.'
						}
					}
			}
          
        }
    }).on('reset', function (event) {
        $('#frmDocument').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
