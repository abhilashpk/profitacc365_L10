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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/buttons.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/colReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/rowReorder.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/scroller.bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatablesmark.js/css/datatables.mark.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/responsive_datatables.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Voucher Numbers
            </h1>
            <ol class="breadcrumb">
                <li>
                      <i class="fa fa-fw fa-shield"></i> Administration
                </li>
				<li>
                    <a href="#">Voucher Numbers</a>
                </li>
            </ol>
        </section>
        <!--section ends-->
		@if(Session::has('message'))
		<div class="alert alert-success">
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                         <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-crosshairs"></i>Voucher Numbers Settings
                            </h3>
                        </div>
                        <div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" name="frmVoucherNo" id="frmVoucherNo" action="{{ url('voucher_numbers/update/') }}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<?php $i=0;?>
									@foreach($vouchers as $row)
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label">{{$row->name}}</label>
										<div class="col-sm-3">
											<div class="input-group">
											<span class="input-group-addon">Prefix</span><input type="text" name="prefix[]" value="{{$row->prefix}}" class="form-control" autocomplete="off">
											</div>
										</div>
										<div class="col-sm-3">
											<input type="text" name="no[]" value="{{$row->no}}" class="form-control" autocomplete="off">
											<input type="hidden" name="id[]" value="{{$row->id}}">
										</div>
										<div class="col-sm-3">
											<input type="checkbox" name="autoincrement[{{$i}}]" <?php if($row->autoincrement==1) echo 'checked'; ?> value="1">
											<label class="radio-inline iradio">Auto Increment</label>
										</div>
									</div>
									<?php $i++; ?>
									@endforeach
									 <div class="form-group">
										<label for="input-text" class="col-sm-5 control-label"></label>
										<div class="col-sm-4">
											<button type="submit" class="btn btn-primary">Submit</button>
											 <a href="{{ url('voucher_numbers') }}" class="btn btn-danger">Cancel</a>
										</div>
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
<script type="text/javascript" src="{{asset('assets/vendors/custom_js/form_elements.js')}}"></script>
        <!-- end of page level js -->

@stop

