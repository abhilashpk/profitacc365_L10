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
               
<aside class="right">		   
    <section class="content p-l-r-15" id="invoice-stmt">
	    <div class="panel">
		    <div class="panel-body" style="width:100%; !important; border:0px solid red;">
			    <div class="print" id="invoicing">

                  <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">

								<tr><td width="100%" align="center"><h3>{{Session::get('company')}}</h3></td>
									<td align="left"></td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$voucherhead}}</u></h4>
									</td><td align="left"></td>
								</tr>
							</table><br/>
                        </div>

						<!-- s<p>Date From: <b><?php //echo ($fromdate=='')?date('d-m-Y',strtotime($settings->from_date)):$fromdate;?></b> &nbsp; To: <b><?php // echo ($todate=='')?date('d-m-Y'):$todate;?></b></p> -->
                        <div class="col-md-12">
					 <div class="col-md-12">

						<?php //if(count($reports) > 0) {  ?>
					
								<table class="table" border="0">
									<thead>
										<th>Edr</th>
										<th >Labour Card</th>
										<th >Routing code</th>
									<th >A/c No.</th>
										<th >Salary start date</th>
										<th >Salary end date</th>
										<th >No.of days </th>
											<th >Fixed salary</th>
											<th >Net payable</th>
											<th >No.of days leave</th>
											<th >Emp.Name</th>
											<th >MOL-File No.</th>
											<th >Company name</th>
											<th >Nationality</th>
									</thead>
									<body> 
								    @foreach($reports as $row)
								    
										<?php
									
								 
									?>
									   <tr>
											<td>{{$row->emp_detail_type}}</td>
											<td >{{$row->lc_id}}</td>
											<td>{{$row->routing_code}}</td>
											<td>{{$row->account_number}}</td>
												<td >{{$firstday}}</td>
										<td >{{$lastday}}</td>
										<td>{{$row->nwage}}</td>
										<td>{{$row->basic_pay}}</td>

										<td>{{$row->net_salary}}</td>
										<td>{{$row->lev_per_mth}}</td>
										<td>{{$row->name}}</td>
										<td>{{$row->mol_no}}</td>
										<td>{{$row->e_company}}</td>
										<td>{{$row->nationality}}</td>
										
										</tr>
										
									@endforeach
								
									</body>
								</table>
							
								
								
							
							
						<!-- <?php //} else { ?>
						<table border="0" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td colspan="2" align="center"><b style="font-size:20px;">{{Session::get('company')}}</b>
									<img src="{{asset('assets/'.Session::get('logo').'')}}" width="900px;" />
									</td>
								</tr>
								<tr>
									
								</tr>
							</thead>
						</table>
						<br/>
						<div class="alert alert-danger">
							<ul>No records were found!</ul>
						</div>
						<?php // } ?> -->
                        </div>
						
                    </div>
					
			    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                               <!-- <span class="pull-left">
									<button type="button" onclick="javascript:window.history.back();"
                                                     class="btn btn-responsive button-alignment btn-primary"
                                                     data-toggle="button">
                                                <span style="color:#fff;">
                                                    <i class="fa fa-fw fa-arrow-circle-left"></i>
                                                Back 
                                            </span>
									</button>
								</span> -->
								<span class="pull-right">
                                           
									 <button type="button" onclick="javascript:window.print();"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button">
										<span style="color:#fff;">
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('empreport/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="month" value="{{$month}}" >
					
				
					</form>
                </div>


			</div>
        <div>

	</section>		   

</aside>
            
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>

