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
@yield('horizontal_header')
<!-- horizontal menu ends -->
           <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td  width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4></td>
								</tr>
							</table><br/>
             </div>
			 <!-- <p>Date From: <b><?php echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($fromdate));?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p> -->

 <div>
	
	<?php if(count($reports) > 0) { ?>
	<b><?php if($fromdate!='' && $todate!='') { echo 'Date From: '.$fromdate.' To Date: '.$todate; } ?></b><br/><br/>
		<table border="1" width="100%" class="pay">
			<tr style="height:30px;">
				<th>Code</th>
				<th>Name</th>
				<th>Designation</th>
				<th>Nationality</th>
				<th>Gender</th>
				<th>Address</th>
				<th>Phone</th>
				<th>Date of Birth</th>
				<th>Join Date</th>
			</tr>
			<?php 
			foreach($reports as $result) {
			?>
			<tr>
				<td class="txtdoc">{{$result->code}}</td>
				<td class="txtdoc">{{$result->name}}</td>
				<td class="txtdoc">{{$result->designation}}</td>
				<td class="txtdoc">{{$result->nationality}}</td>
				<td class="txtdoc">{{ ($result->gender==1)?'Male':'Female' }}</td>
				<td class="txtdoc">{{$result->address1}} {{$result->address2}} {{$result->address3}}</td>
				<td class="txtdoc">{{$result->phone}}</td>
				<td class="txtdoc">{{($result->dob=='0000-00-00')?'':date('d-m-Y', strtotime($result->dob))}}</td>
				<td class="txtdoc">{{($result->join_date=='0000-00-00')?'':date('d-m-Y', strtotime($result->join_date))}}</td>
			</tr>
			<?php } ?>
		</table>
		<?php } else { ?>
			<div class="col-md-12">
			<div class="alert alert-warning">
				<p>No records were found!</p>
			</div></div>
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
			
			<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{url('employee_report/export')}}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="date_from" value="{{$fromdate}}" >
				<input type="hidden" name="date_to" value="{{$todate}}" >
				<input type="hidden" name="designation" value="{{$designation}}" >
				<input type="hidden" name="nationality" value="{{$nationality}}" >
			</form>
						
		</div>
 <script>
	function getExport() { document.frmExport.submit(); }
</script>                   

