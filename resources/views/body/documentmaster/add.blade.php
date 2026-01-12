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
        <!--end of page level css-->
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
                    Add New
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
                                <i class="fa fa-fw fa-crosshairs"></i> New Document 
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmDocument" id="frmDocument" action="{{ url('document_master/save') }}" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">
                                                <label for="input-text" class="col-sm-2 control-label"><b>Department</b></label>
                                                <div class="col-sm-10">
                                                   <select id="department_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="department_id">
                                                        <option>Select Department</option>
                                                        @foreach($departments as $drow)
                                                           <option value="{{ $drow->id }}" >{{ $drow->name }}</option>
                                                      @endforeach
                                                   </select>
                                               </div>
                                            </div>

								 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Code</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="code" name="code" placeholder="Document Code">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Document Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Document Name">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                                <label for="input-text" class="col-sm-2 control-label"><b>Division</b></label>
                                                <div class="col-sm-10">
                                                   <select id="division_id" class="form-control select2" style="width:100%; background-color:#85d3ef;" name="division_id">
                                                        <option>Select Division</option>
                                                        @foreach($divisions as $drow)
                                                           <option value="{{ $drow->id }}" >{{ $drow->div_name }}</option>
                                                      @endforeach
                                                   </select>
                                               </div>
                                  </div>
								
								 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Amount</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="any" class="form-control" id="amount" name="amount" placeholder="Amount">
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="issue_date" name="issue_date" data-language='en' readonly autocomplete="off">
									</div>
								</div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Expiry Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="expiry_date" name="expiry_date" data-language='en' readonly autocomplete="off">
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Remarks</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control"></textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Image</label>
									<div class="col-sm-10">
										<input id="input-23" name="image" type="file" class="file-loading" data-show-preview="true">
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

$(document).ready(function () {
	
	$('#issue_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoClose:true
	});
	
	$('#expiry_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoClose:true
	});
	
	var url = "{{ url('document_master/checkname/') }}";
	var urlcd = "{{ url('document_master/checkcode/') }}";
    $('#frmDocument').bootstrapValidator({
        fields: {
            code: {
                validators: {
                    notEmpty: {
                        message: 'The document code is required and cannot be empty!'
                    },
					remote: {
                        url: urlcd,
                        data: function(validator) {
                            return {
                                name: validator.getFieldElements('code').val()
                            };
                        },
                        message: 'The document code is already exist!'
                    }
                }
            },
			 name: {
                validators: {
                    notEmpty: {
                        message: 'The document name is required and cannot be empty!'
                    },
					remote: {
                        url: url,
                        data: function(validator) {
                            return {
                                name: validator.getFieldElements('name').val()
                            };
                        },
                        message: 'The document name is already exist!'
                    }
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
