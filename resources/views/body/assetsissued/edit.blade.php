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
                Assets Issued
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-tower"></i>  HR Management
                    </a>
                </li>
                <li>
                    <a href="#">Assets Issued</a>
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
                                <i class="fa fa-fw fa-crosshairs"></i> Edit Assets Issued 
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmAssets" id="frmAssets" action="{{ url('assets_issued/update/'.$docrow->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="<?php echo $docrow->id; ?>">
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Employee Name</label>
                                    <div class="col-sm-10">
                                        <select id="employee_id" class="form-control select2" style="width:100%" name="employee_id">
                                            <option value="">Select Employee...</option>
											@foreach ($employee as $row)
											<option value="{{ $row->id }}" <?php if($docrow->employee_id==$row->id) echo 'selected';?>>{{ $row->name }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Asset Name </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Asset Name" value="{{ $docrow->name }}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" id="description" class="form-control">{{ $docrow->description }}</textarea>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Issue Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="issue_date" name="issue_date" value="{{($docrow->issue_date=='0000-00-00')?'':date('d-m-Y', strtotime($docrow->issue_date))}}" data-language='en' readonly>
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Set Status</label>
                                    <div class="col-sm-10">
                                        <select id="asset_status" class="form-control select2" style="width:100%" name="asset_status">
                                            <option value="">Select Status...</option>
											<option value="1" <?php if($docrow->asset_status==1) echo 'selected';?>>Issued</option>
											<option value="2" <?php if($docrow->asset_status==2) echo 'selected';?>>Received</option>
											<option value="-1" <?php if($docrow->asset_status==-1) echo 'selected';?>>Lost</option>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
									<label for="input-text" class="col-sm-2 control-label">Received Date</label>
									<div class="col-sm-10">
										 <input type="text" class="form-control" id="received_date" name="received_date" value="{{($docrow->received_date=='0000-00-00')?'':date('d-m-Y', strtotime($docrow->received_date))}}" data-language='en' readonly>
									</div>
								</div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Other Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="othr_description" id="othr_description" class="form-control">{{ $docrow->othr_description }}</textarea>
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('assets_issued') }}" class="btn btn-danger">Cancel</a>
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

$('#received_date').datepicker( { 
			dateFormat: 'dd-mm-yyyy',
			autoclose: 1
	});
	
$(document).ready(function () {
    $('#frmAssets').bootstrapValidator({
        fields: {
            employee_id: { validators: { notEmpty: { message: 'The employee name is required and cannot be empty!' } }},
			name: {
                validators: {
                    notEmpty: {
                        message: 'The assets name is required and cannot be empty!'
                    }
                }
            }
          
        }
        
    }).on('reset', function (event) {
        $('#frmAssets').data('bootstrapValidator').resetForm();
    });
});
</script>
@stop
