@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Invoice
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>{{$titles['main_head']}}</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="glyphicon glyphicon-list-alt"></i> Inventory
                    </a>
                </li>
				<li> {{$titles['main_head']}}</li>
                <li class="active">
                   <a href="#">Print</a>
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center">
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" /></td>
								</tr>
								<tr>
									<td colspan="2" align="center"><h5><b><u>{{$titles['subhead']}}</u></b></h5></td>
								</tr>
							</thead>
							</table>
							<!--<table border="0" style="width:100%;">
							<tr>
								<td colspan="2"><img src="{{asset('assets/'.Session::get('logo').'')}}" width="100%" />
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><h4><b><u>{{$titles['subhead']}}</u></b></h4></td>
							</tr>
							
							
							</table>-->
                        </div>
						
						@yield('contentnew')
                       
                    </div>
                    <!--<div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
                                           
                                             <button type="button" onclick="javascript:window.print();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-print"></i>
                                                Print
                                            </span>
                                </button>
								
								<button type="button" onclick="location.href='{{ url($url)}}'"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-times"></i>
                                                Close 
                                            </span>
                                </button>
                                </span>
                        </div><br/>
                    </div>-->
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
@stop
