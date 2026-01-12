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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
   <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/invoice.css')}}">-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:8pt;
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

<!-- horizontal menu ends -->
<!-- <style>
 table, th {
            
           text-align:center;
         }
 .txtdoc { text-align:left; padding: 10px; line-height: 20px; }
</style> -->

                       <!-- <div class="col-md-12"> -->
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        <!-- </div> -->

<div>
	
	<?php if(count($reports) > 0) { ?>
	<b>Date From: {{$fromdate}} To Date: {{$todate}}</b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:30px;">
				<th>Emp. Code</th>
				<th>Emp. Name</th>
				<th>Passport#</th>
				<th>Exp.Date</th>
				<th>Visa#</th>
				<th>Exp.Date</th>
				<th>Labour Card#</th>
				<th>Exp.Date</th>
				<th>Health Card#</th>
				<th>Exp.Date</th>
				<th>ID Card#</th>
				<th>Exp.Date</th>
				<th>Medical Exam#</th>
				<th>Exp.Date</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr style="height:100px;">
				<td class="txtdoc">{{$result->code}}</td>
				<td class="txtdoc">{{$result->name}}</td>
				<td class="txtdoc">{{$result->pp_id}}</td>
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->pp_expiry_date))}}</td>
				<td class="txtdoc">{{$result->v_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->v_expiry_date))}}</td>
				<td class="txtdoc">{{$result->lc_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->lc_expiry_date))}}</td>
				<td class="txtdoc">{{$result->hc_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->hc_expiry_date))}}</td>
				<td class="txtdoc">{{$result->ic_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->ic_expiry_date))}}</td>
				<td class="txtdoc">{{$result->me_id}}
				<td class="txtdoc">{{date('d-m-Y', strtotime($result->me_expiry_date))}}</td>
			</tr>
			<?php } ?>
		</table>
		<?php } else { ?>
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div>
		<?php } ?>
	
	<br/><br/><br/><br/>
	</div>
			
	<div class="btn-section">
			<div class="col-md-12 col-sm-12 col-xs-12">
					
									
					<span class="pull-right">
								 <button type="button" onclick="javascript:window.print();" 
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-print"></i>
									Print
								</span>
					</button>

					<button type="button" onclick="getExport()"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
											<i class="fa fa-fw fa-upload"></i>
										Export Excel
									</span>
									</button>
					
					<button type="button" onclick="javascript:window.close();"
										 class="btn btn-responsive button-alignment btn-primary"
										 data-toggle="button">
									<span style="color:#fff;" >
										<i class="fa fa-fw fa-times"></i>
									Close 
								</span>
					</button>
					</span>
			</div>
		</div>
		
		<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('document_report/export') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="date_from" value="{{$fromdate}}" >
		<input type="hidden" name="date_to" value="{{$todate}}" >
		</form>
						
<script>
	function getExport() { document.frmExport.submit(); }
</script>                  

