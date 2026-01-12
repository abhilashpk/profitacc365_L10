@extends('layouts/default')

{{-- Page title --}}
@section('title')
    NumakPro ERP | Dashboard
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!--weathericons-->
	
	<link href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/vendors/chartist/css/chartist.min.css')}}">
    <link href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/morrisjs/morris.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/awesome-bootstrap-checkbox.css')}}">
    <link href="{{asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">

    <link href="{{asset('assets/css/custom_css/dashboard1.css')}}" rel="stylesheet" type="text/css"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <div class="header-data">
                        <h1>Dashboard</h1>
                        <p>Welcome To NumakPro ERP</p>
                    </div>
                </div>
               
            </div>
        </section>
        <section class="content sec-mar">
            <div class="row">
                <div class="col-md-12">
                    <div class="row tiles-row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 tile-bottom">
							<a href="{{ URL::to('itemmaster') }}">
                            <div class="canvas-interactive-wrapper1">
                                <canvas id="canvas-interactive1"></canvas>
                                <div class="cta-wrapper1">
                                    <div class="widget" data-count=".num" data-from="0"
                                         data-to="99.9" data-suffix="%" data-duration="2">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-shopping-cart fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box" class="count-box">{{$items}}</div>
                                                <span class="title">Total Items</span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
							</a>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 tile-bottom">
                            <div class="widget" data-count=".num" data-from="0"
                                 data-to="512" data-duration="3">
								<a href="{{ URL::to('purchase_invoice') }}">
                                <div class="canvas-interactive-wrapper2">
                                    <canvas id="canvas-interactive2"></canvas>
                                    <div class="cta-wrapper2">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-paw fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box2" class="count-box">{{$purchase}}</div>
                                                <span class="title">Total Purchase</span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
								</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 tile-bottom">
                            <div class="widget" data-suffix="k" data-count=".num"
                                 data-from="0" data-to="310" data-duration="4" data-easing="false">
								 <a href="{{ URL::to('sales_invoice') }}">
                                <div class="canvas-interactive-wrapper3">
                                    <canvas id="canvas-interactive3"></canvas>
                                    <div class="cta-wrapper3">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-fw fa-usd fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box3" class="count-box">{{$sales}}</div>
                                                <span class="title">Total Sales</span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
								</a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 tile-bottom">
                            <div class="widget">
							<a href="{{ URL::to('sales_report') }}">
                                <div class="canvas-interactive-wrapper4">
                                    <canvas id="canvas-interactive4"></canvas>
                                    <div class="cta-wrapper4">
                                        <div class="item">
                                            <div class="widget-icon pull-left icon-color animation-fadeIn">
                                                <i class="fa fa-bar-chart-o fa-size"></i>
                                            </div>
                                        </div>
                                        <div class="widget-count panel-white">
                                            <div class="item-label text-center">
                                                <div id="count-box4" class="count-box">&nbsp; </div>
                                                <span class="title">Sales Report</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			
            <div class="row">
                <div class="col-md-12">
                    <!--<div class="panel panel-default1">
                        <div class="panel-heading">
                            <h3 class="panel-title">Site Activity</h3>
                            <ul class="nav nav-tabs nav-float pull-right" role="tablist">
                                <li class="active">
                                    <a href="#home" role="tab" data-toggle="tab">Stats</a>
                                </li>
                                <li>
                                    <a href="#profile" role="tab" data-toggle="tab">Sales</a>
                                </li>
                            </ul>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12 stat-chart">
                                            <div id="chart6" class='with-3d-shadow with-transitions'>
                                                <svg></svg>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <h4>Stats</h4>
                                            <div class="task-item">
                                                Total Sold
                                                <small class="pull-right text-muted">40%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 40%;"
                                                         class="progress-bar progress-bar-primary">
                                                        <span class="sr-only">40% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Product Delivered
                                                <small class="pull-right text-muted">60%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 60%;"
                                                         class="progress-bar progress-bar-success">
                                                        <span class="sr-only">60% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Sale Reports
                                                <small class="pull-right text-muted">55%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="55" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 55%;"
                                                         class="progress-bar progress-bar-info">
                                                        <span class="sr-only">55% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                New Projects
                                                <small class="pull-right text-muted">66%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="66" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 66%;"
                                                         class="progress-bar progress-bar-warning">
                                                        <span class="sr-only">66% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                New Users
                                                <small class="pull-right text-muted">90%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 90%;"
                                                         class="progress-bar progress-bar-danger">
                                                        <span class="sr-only">90% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-item">
                                                Total Income
                                                <small class="pull-right text-muted">50%</small>
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="50" aria-valuemin="0"
                                                         aria-valuemax="100" style="width: 50%;"
                                                         class="progress-bar progress-bar-primary">
                                                        <span class="sr-only">50% Complete (success)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-12 col-xs-12 sales-tab">
                                            <div id="basicFlotLegend"></div>
                                            <div id="placeholder" style="width:100%; height: 291px"></div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-xs-12">
                                            <div id="donut" style="width:94%; height: 300px"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="row">
                
            </div>
            <div class="row">
                
            </div>
            <div class="row maps-row">
                
            </div>

           </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script src="{{asset('assets/js/backstretch.js')}}"></script>

<!--sales tiles-->
<script src="{{asset('assets/vendors/countupcircle/js/jquery.countupcircle.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/granim/js/granim.min.js')}}" type="text/javascript"></script>
<!-- end of sales tiles -->

<!-- Flot tab2-->
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.resize.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.time.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.symbol.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.pie.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotchart/js/jquery.flot.stack.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flot.tooltip/js/jquery.flot.tooltip.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/flotspline/js/jquery.flot.spline.min.js')}}" type="text/javascript"></script>
<!-- end of flot tab2 -->
<script type="text/javascript" src="{{asset('assets/vendors/chartist/js/chartist.min.js')}}"></script>

<!--morris donut-->
<script type="text/javascript" src="{{asset('assets/vendors/morrisjs/morris.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/raphael-min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/nvd3/js/nv.d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/stream_layers.js')}}"></script>

<!--maps-->
<script src="{{asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('assets/vendors/bower-jvectormap/js/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- end of maps -->

<script type="text/javascript" src="{{asset('assets/js/dashboard1.js')}}" ></script>
    <!-- end of page level js -->
@stop