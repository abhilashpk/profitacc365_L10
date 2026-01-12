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
                Utilities
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Utilities</a>
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
		<div class="alert alert-warning">
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">
							<i class="fa fa-fw fa-folder"></i> Cross Check All Accounts
						</h3>
						<span class="pull-right">
							<i class="fa fa-fw fa-chevron-up clickable"></i>
							<i class="fa fa-fw fa-times removepanel clickable"></i>
						</span>
					</div>
								
				</div>
				<div class="panel-body">
					@if($vats)
					@foreach($vats as $vat)
					<h4>VAT Master Name: {{$vat['name']}}</h4>
					<p>Following VAT accounts are not configured!</p>
					<table class="table" border="0">
						<tr>
							<td>{{isset($vat['details'][0])?$vat['details'][0]:''}}</td>
							<td>{{isset($vat['details'][1])?$vat['details'][1]:''}}</td>
							<td>{{isset($vat['details'][2])?$vat['details'][2]:''}}</td>
							<td>{{isset($vat['details'][3])?$vat['details'][3]:''}}</td>
							<td>{{isset($vat['details'][4])?$vat['details'][4]:''}}</td>
							<td>{{isset($vat['details'][5])?$vat['details'][5]:''}}</td>
						</tr>
					</table>
					@endforeach
					@endif
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
<!-- end of page level js -->
<script>


</script>

@stop
