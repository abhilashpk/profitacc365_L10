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
                Document Master
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-tower"></i>  HR Management
                    </a>
                </li>
                <li>
                    <a href="#">Document Master</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Document 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmDocument" id="frmDocument" enctype="multipart/form-data" action="{{ url('document_master/update/'.$docrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" value="<?php echo $docrow->id; ?>">
								<input type="hidden" name="current_image" value="<?php echo $docrow->image; ?>">
                                <div class="form-group">
                                                <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                                    <div class="col-sm-10">
                                                        <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
                                                                @foreach($departments as $drow)
                                                            <option value="{{ $drow->id }}" {{($docrow->department_id==$drow->id)?'selected':''}} >{{ $drow->name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                           </div>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="code" name="code" value="{{$docrow->code}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" value="{{$docrow->name}}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                                <label for="input-text" class="col-sm-2 control-label"><b>Division</b></label>
                                                <div class="col-sm-10">
                                                   <select id="division_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="division_id">
                                                        
                                                           @foreach($divisions as $drow)
                                                            <option value="{{ $drow->id }}" {{($docrow->division_id==$drow->id)?'selected':''}} >{{ $drow->div_name }}</option>
                                                                @endforeach
                                                    
                                                   </select>
                                               </div>
                                            </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control" id="amount" name="amount" value="{{$docrow->amount}}">
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="issue_date" name="issue_date" value="{{($docrow->issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($docrow->issue_date))}}" data-language='en' autocomplete="off" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="expiry_date" name="expiry_date" value="{{date('d-m-Y', strtotime($docrow->expiry_date))}}" data-language='en' autocomplete="off" readonly >
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control">{{$docrow->description}}</textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Image</label>
									<div class="col-sm-10">
										<input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true">
										<?php if($docrow->image!='') { ?>
										<a href="{{asset('uploads/document/'.$docrow->image)}}" target="_blank">View Image</a>
										<?php } ?>
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('document_master') }}" class="btn btn-danger">Cancel</a>
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
		autoClose: true 
});

$('#expiry_date').datepicker( { 
		dateFormat: 'dd-mm-yyyy',
		autoClose: true 
});
	
var url = "{{ url('category/checkname/') }}";
$(document).ready(function () {
	var url = "{{ url('category/checkname/') }}";
    $('#frmDocument').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The document code is required and cannot be empty!'
                    },
					/* remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                category_name: validator.getFieldElements('category_name').val()
                            };
                        },
                        message: 'The category name is not available'
                    } */
                }
            },
			 name: {
                validators: {
                    notEmpty: {
                        message: 'The document name is required and cannot be empty!'
                    },
					/* remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                category_name: validator.getFieldElements('category_name').val()
                            };
                        },
                        message: 'The category name is not available'
                    } */
                }
            },
			expiry_date: { validators: { notEmpty: { message: 'The expiry date is required and cannot be empty!' } }},
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
