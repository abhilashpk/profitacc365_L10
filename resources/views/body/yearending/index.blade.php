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
                Year Ending Wizard
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Year Ending Wizard</a>
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
            <div class="col-lg-12">
                <div class="panel panel-info">
					
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-fw fa-folder"></i> Financial Year Set
                                    </h3>
                                    <span class="pull-right">
										<i class="fa fa-fw fa-chevron-up clickable"></i>
										<i class="fa fa-fw fa-times removepanel clickable"></i>

									</span>
                                </div>
							<div class="panel-body">
								<form role="form" class="form-horizontal" method="POST" name="frmBackup" id="frmBackup" action="{{url('year_ending/backup')}}">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Opening Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="from_date" value="{{date('d-m-Y',strtotime($date->from_date))}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Closing Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="to_date" value="{{date('d-m-Y',strtotime($date->to_date))}}">
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">New FY Start Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nw_from_date" value="{{date('d-m-Y', strtotime('+1 year', strtotime($date->from_date)) )}}">
                                    </div>
                                </div>
                                
								<div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">New FY End Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nw_to_date" value="{{date('d-m-Y', strtotime('+1 year', strtotime($date->to_date)) )}}">
                                    </div>
                                </div>
									
									<div class="form-group">
										<label for="input-text" class="col-sm-2 control-label"></label>
										<div class="col-sm-10">
											<button type="submit" class="btn btn-primary">Submit</button>
											<a href="{{ url('dashboard') }}" class="btn btn-danger">Cancel</a>
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
<!-- end of page level js -->
<script>

function downloadDB() {
	document.frmBackup.action="{{ url('backup/submit') }}";
	document.frmBackup.submit();
}



</script>

@stop
