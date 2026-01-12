<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 | ERP Software
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">
	@yield('header_styles')
    <!--end of page level css-->
	<style>
				
		table th,td { padding:5px; }
	</style>


<style>
#invoicing {
	font-size:7pt;
}
.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }

/* @media print {
	html, body {
		
		height: 530px !important;        
	}
	.page {
		margin: 0;
		border: initial;
		border-radius: initial;
		width: initial;
		min-height: initial;
		box-shadow: initial;
		background: initial;
		page-break-after: always;
	}
} */
</style>
<style type="text/css" media="print">

/*body{ page-break-after: always !important; overflow: hidden !important; }*/

thead
{
	display: table-header-group;
}

#inv
{
	 display: table-footer-group;
	 /*position: fixed;*/
     bottom: 0;
	 margin: 0 auto 0 auto;
	 width:100%;
}

.t {
	 height:250px;
}

</style>
<!-- end of global css -->
</head>
<body >


<!-- For horizontal menu -->
@yield('horizontal_header')
<!-- horizontal menu ends -->
<div>
        <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <table border="0" style="width:100%;" class="tblstyle table-bordered">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
							<h3>All</h3>
									<table border="1" style="width:100%;height:100%;">
										<thead>
											<tr><th>Account</th><th>PI</th><th>PI Stmt</th><th>SI</th><th>SI Stmt</th><th>RV</th><th>RV Stmt</th><th>PV</th><th>PV Stmt</th><th>JV</th><th>JV Stmt</th><th>PC</th><th>PC Stmt</th></tr>
										</thead>
										<tbody id="bod">
										    @php $psac = $pstr = $ssac = $sstr = $rvac = $rvtr = $pvac = $pvtr = $jvac = $jvtr = $pcac = $pctr = 0; @endphp
											@foreach($lb as $row)
											@php 
											    $psac += $row->pi->ac; $pstr += $row->pi->tr;
											    
											 @endphp
											<tr>
												<td style="width:25%">{{$row->name}}</td>
												<td>{{number_format($row->pi->ac,2)}}</td>
												<td>{{number_format($row->pi->tr,2)}}</td>
												
												<td>{{number_format($row->si->ac,2)}}</td>
												<td>{{number_format($row->si->tr,2)}}</td>
												
												<td>{{number_format($row->rv->ac,2)}}</td>
												<td>{{number_format($row->rv->tr,2)}}</td>
												
												<td>{{number_format($row->pv->ac,2)}}</td>
												<td>{{number_format($row->pv->tr,2)}}</td>
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
												
												<td>{{number_format($row->pc->ac,2)}}</td>
												<td>{{number_format($row->pc->tr,2)}}</td>
											</tr>
										</tbody>
									</table>
									<br/><br/>
									
									
									<h3>Direct Expense</h3>
									<table border="1" style="width:100%;height:100%;">
										<thead>
											<tr><th>Account</th><th>PS</th><th>PS Stmt</th><th>SS</th><th>SS Stmt</th><th>RV</th><th>RV Stmt</th><th>PV</th><th>PV Stmt</th><th>JV</th><th>JV Stmt</th><th>PC</th><th>PC Stmt</th></tr>
										</thead>
										<tbody id="bod">
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
												<td style="width:25%">{{$row->name}}</td>
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
								<br/><br/>
								
								
								<h3>Direct Income</h3>
									<table border="0" style="width:100%;" class="tblstyle table-bordered">
										<thead>
											<tr><th>Account</th><th>PS</th><th>PS Stmt</th><th>SS</th><th>SS Stmt</th><th>RV</th><th>RV Stmt</th><th>PV</th><th>PV Stmt</th><th>JV</th><th>JV Stmt</th><th>PC</th><th>PC Stmt</th></tr>
										</thead>
										<tbody id="bod">
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
												<td style="width:25%">{{$row->name}}</td>
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
		</aside>
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
<script>
function getExport() { document.frmExport.submit(); }

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
});
</script>
</html>