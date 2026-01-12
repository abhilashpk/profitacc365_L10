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
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Data Import
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-wrench"></i> Data Import
                    </a>
                </li>
                <li>
                    <a href="#">{{($type=="items")?'Items':'Accounts'}}</a>
                </li>
                
            </ol>
        </section>
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
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i> Import
                            </h3>
                           
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" name="frmImport" id="frmImport" enctype="multipart/form-data" action="{{ url('importdata/save') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="type" value="{{$type}}">
								
                                <?php if($type=='accounts') { ?>
								 <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Account Type</label>
                                    <div class="col-sm-10">
                                         <select id="actype" class="form-control select2" required name="actype">
                                            <option value="">Select Account Type...</option>
											<option value="customer">Customer</option>
											<option value="supplier">Supplier</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } else if($type=='accountmaster') { ?>
							<div class="form-group">
                                   <font color="#16A085"><label for="input-text" class="col-sm-2 control-label"><b>Account Type</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="actype_id" class="form-control select2" style="width:100%" name="actype_id">
                                            <option value="">Select Account Type...</option>
											@foreach ($acctype as $type)
											@if($actypid==$type['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $type['id'] }}" {{$sel}}>{{ $type['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account Category</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="category_id" class="form-control select2" style="width:100%" name="category_id">
                                            <option value="">Select Account Category...</option>
											@foreach ($category as $cat)
											@if($catid==$cat['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $cat['id'] }}" {{$sel}}>{{ $cat['name'] }}</option>
											@endforeach
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <font color="#16A085"> <label for="input-text" class="col-sm-2 control-label"><b>Account Group</b></label></font>
                                    <div class="col-sm-10">
                                        <select id="group_id" class="form-control select2" style="width:100%" name="group_id">
                                            <option value="">Select Account Group...</option>
											<?php if($catid!='') {?>
											@foreach ($groups as $group)
											@if($gpid==$group['id'])
											{{--*/ $sel = "selected" /*--}}
											@else
											{{--*/ $sel = "" /*--}}	
											@endif
											<option value="{{ $group['id'] }}" {{$sel}}>{{ $group['name'] }}</option>
											@endforeach
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
								<?php } else if($type=='opn-balance') { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer</label>
                                    <div class="col-sm-10">
                                         <select id="account_id" class="form-control select2" required name="account_id">
                                            <option value="">Select Customer...</option>
											@if(isset($customers))
												@foreach($customers as $row)
												<option value="{{$row->id}}">{{$row->master_name}}</option>
												@endforeach
											@endif
                                        </select>
                                    </div>
                                </div>
                                <?php } else if($type=='opn-balance-sup') { ?>
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Customer</label>
                                    <div class="col-sm-10">
                                         <select id="account_id" class="form-control select2" required name="account_id">
                                            <option value="">Select Supplier...</option>
											@if(isset($suppliers))
												@foreach($suppliers as $row)
												<option value="{{$row->id}}">{{$row->master_name}}</option>
												@endforeach
											@endif
                                        </select>
                                    </div>
                                </div>
                                
								<?php } ?>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Import File</label>
                                    <div class="col-sm-10">
                                         <input id="input-23" name="import_file" type="file">
                                    </div>
                                </div>
								
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"></label>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ url('importdata/items') }}" class="btn btn-danger">Cancel</a>
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
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

<script>
"use strict";

$(document).ready(function () {
    $('#frmImport').bootstrapValidator({
        fields: {
			import_file: { validators: {
						file: {
							  extension: 'csv,xls,xlsx',
							  //type: 'application/vnd.ms-excel',
							  maxSize: 5*1024*1024,   // 5 MB
							  message: 'The selected file is not valid, it should be (csv,xlsx,xlsx) and 5 MB at maximum.'
						}
					}
			}
        }
        
    }).on('reset', function (event) {
        $('#frmImport').data('bootstrapValidator').resetForm();
    });
});

$(function() {
    
$('#actype_id').on('change', function(e){
	var type_id = e.target.value;

    $.get("{{ url('accategory/getcategory/') }}/" + type_id, function(data) {
		$('#category_id').empty();
		 $('#category_id').append('<option value="">Select Account Category...</option>');
		$.each(data, function(value, display){
			 $('#category_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});


$('#category_id').on('change', function(e){
	var cat_id = e.target.value;

	$.get("{{ url('acgroup/getgroup/') }}/" + cat_id, function(data) {
		$('#group_id').empty();
		 $('#group_id').append('<option value="">Select Account Group...</option>');
		$.each(data, function(value, display){
			 $('#group_id').append('<option value="' + display.id + '">' + display.name + '</option>');
		});
	});
});


});
</script>
@stop
