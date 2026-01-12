@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        @parent
    @stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Tools
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="">
                        <i class="fa fa-fw fa-shield"></i> Administration
                    </a>
                </li>
                <li>
                    <a href="#">Tools</a>
                </li>
                
            </ol>
			
        </section>
		
        <section class="content">
            <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-info">
					
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-fw fa-folder"></i> Search Result
                                    </h3>
                                    <span class="pull-right">
										<i class="fa fa-fw fa-chevron-up clickable"></i>
										<i class="fa fa-fw fa-times removepanel clickable"></i>
									</span>
                                </div>
								<div class="panel-body">
								<div class="row">
								<div class="col-lg-12">
								<h3>Direct Expense</h3>
									<table class="table">
										<thead>
											<tr><th>Account</th><th>PS</th><th>PS Stmt</th><th>SS</th><th>SS Stmt</th><th>RV</th><th>RV Stmt</th><th>PV</th><th>PV Stmt</th><th>JV</th><th>JV Stmt</th><th>PC</th><th>PC Stmt</th></tr>
										</thead>
										<tbody>
										    @php $psac = $pstr = $ssac = $sstr = $rvac = $rvtr = $pvac = $pvtr = $jvac = $jvtr = $pcac = $pctr = 0; @endphp
											@foreach($dexp as $row)
											@php 
											    $psac += $row->ps->ac; $pstr += $row->ps->tr;
											    $ssac += $row->ss->ac; $sstr += $row->ss->tr;
											    $rvac += $row->rv->ac; $rvtr += $row->rv->tr;
											    $pvac += $row->pv->ac; $pvtr += $row->pv->tr;
											    $jvac += $row->jv->ac; $jvtr += $row->jv->tr;
											    $pcac += $row->pc->ac; $pctr += $row->pc->tr;
											 @endphp
											<tr>
												<td>{{$row->name}}</td>
												<td>{{number_format($row->ps->ac,2)}}</td>
												<td>{{number_format($row->ps->tr,2)}}</td>
												
												<td>{{number_format($row->ss->ac,2)}}</td>
												<td>{{number_format($row->ss->tr,2)}}</td>
												
												<td>{{number_format($row->rv->ac,2)}}</td>
												<td>{{number_format($row->rv->tr,2)}}</td>
												
												<td>{{number_format($row->pv->ac,2)}}</td>
												<td>{{number_format($row->pv->tr,2)}}</td>
												
												<td>{{number_format($row->jv->ac,2)}}</td>
												<td>{{number_format($row->jv->tr,2)}}</td>
												
												<td>{{number_format($row->pc->ac,2)}}</td>
												<td>{{number_format($row->pc->tr,2)}}</td>
											</tr>
											@endforeach
											<tr>
											    <td><b>Total:</b></td>
											    <td><b>{{number_format($psac,2)}}</b></td>
												<td><b>{{number_format($pstr,2)}}</b></td>
												
												<td><b>{{number_format($ssac,2)}}</b></td>
												<td><b>{{number_format($sstr,2)}}</b></td>
												
												<td><b>{{number_format($rvac,2)}}</b></td>
												<td><b>{{number_format($rvtr,2)}}</b></td>
												
												<td><b>{{number_format($pvac,2)}}</b></td>
												<td><b>{{number_format($pvtr,2)}}</b></td>
												
												<td><b>{{number_format($jvac,2)}}</b></td>
												<td><b>{{number_format($jvtr,2)}}</b></td>
												
												<td><b>{{number_format($pcac,2)}}</b></td>
												<td><b>{{number_format($pctr,2)}}</b></td>
											</tr>
										</tbody>
									</table>
								</div>
								</div>
								
								
								<div class="row">
								<div class="col-lg-12">
								<h3>Direct Income</h3>
									<table class="table">
										<thead>
											<tr><th>Account</th><th>PS</th><th>PS Stmt</th><th>SS</th><th>SS Stmt</th><th>RV</th><th>RV Stmt</th><th>PV</th><th>PV Stmt</th><th>JV</th><th>JV Stmt</th><th>PC</th><th>PC Stmt</th></tr>
										</thead>
										<tbody>
										     @php $psac = $pstr = $ssac = $sstr = $rvac = $rvtr = $pvac = $pvtr = $jvac = $jvtr = $pcac = $pctr = 0; @endphp
											@foreach($dinc as $row)
											@php 
											    $psac += $row->ps->ac; $pstr += $row->ps->tr;
											    $ssac += $row->ss->ac; $sstr += $row->ss->tr;
											    $rvac += $row->rv->ac; $rvtr += $row->rv->tr;
											    $pvac += $row->pv->ac; $pvtr += $row->pv->tr;
											    $jvac += $row->jv->ac; $jvtr += $row->jv->tr;
											    $pcac += $row->pc->ac; $pctr += $row->pc->tr;
											 @endphp
											<tr>
												<td>{{$row->name}}</td>
												<td>{{number_format($row->ps->ac,2)}}</td>
												<td>{{number_format($row->ps->tr,2)}}</td>
												
												<td>{{number_format($row->ss->ac,2)}}</td>
												<td>{{number_format($row->ss->tr,2)}}</td>
												
												<td>{{number_format($row->rv->ac,2)}}</td>
												<td>{{number_format($row->rv->tr,2)}}</td>
												
												<td>{{number_format($row->pv->ac,2)}}</td>
												<td>{{number_format($row->pv->tr,2)}}</td>
												
												<td>{{number_format($row->jv->ac,2)}}</td>
												<td>{{number_format($row->jv->tr,2)}}</td>
												
												<td>{{number_format($row->pc->ac,2)}}</td>
												<td>{{number_format($row->pc->tr,2)}}</td>
												
											</tr>
											@endforeach
												<tr>
											    <td><b>Total:</b></td>
											    <td><b>{{number_format($psac,2)}}</b></td>
												<td><b>{{number_format($pstr,2)}}</b></td>
												
												<td><b>{{number_format($ssac,2)}}</b></td>
												<td><b>{{number_format($sstr,2)}}</b></td>
												
												<td><b>{{number_format($rvac,2)}}</b></td>
												<td><b>{{number_format($rvtr,2)}}</b></td>
												
												<td><b>{{number_format($pvac,2)}}</b></td>
												<td><b>{{number_format($pvtr,2)}}</b></td>
												
												<td><b>{{number_format($jvac,2)}}</b></td>
												<td><b>{{number_format($jvtr,2)}}</b></td>
												
												<td><b>{{number_format($pcac,2)}}</b></td>
												<td><b>{{number_format($pctr,2)}}</b></td>
											</tr>
										</tbody>
									</table>
								</div>
								</div>
								
								</div>
								
				</div>
            </div>
        </div>
        </section>
		<div class="btn-section">
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
				
				<button type="button" onclick="javascript:window.close();"
									 class="btn btn-responsive button-alignment btn-primary"
									 data-toggle="button">
								<span style="color:#fff;">
									<i class="fa fa-fw fa-times"></i>
								Close 
							</span>
				</button>
				</span>
			</div>
		</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
<!-- end of page level js -->
@stop
