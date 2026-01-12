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
		
        <!-- Main content -->
        <section class="content p-l-r-15" id="cost-job">
            <div class="panel">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr>
									<td width="100%" align="center" colspan="2">
										<b style="font-size:20px;">{{Session::get('company')}}</b><br/>
									</td>
								</tr>
								<tr>
									<td width="100%" align="center"><h4><u>{{$reporthead}}</u></h4>
									</td><td align="left"></td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top" style="padding-left:0px;">
										<p>Date From: <b>{{date('d-m-Y',strtotime($datefrom))}}</b> &nbsp; To: <b>{{date('d-m-Y',strtotime($dateto))}}</b></p>
									</td>
								</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
						@if($wtype=='wo')
							<table class="table" border="0">
								<thead>
									<th>SI.No.</type>
									<th>Date</th>
									<th>WO.No.</type>
									<th>Cust.Ref.No.</th>
									<th>Project Name</th>
									<th>Location</th>
									<th>WO Requestor</th>
									<th>WO Description</th>
									<th>WO Type</th>
									<th>WO Status</th>
								</thead>
								<body>
									@foreach($report as $row)
									<?php 
										if($row->status==0)
											$status = 'Pending';
										elseif($row->status==1)
											$status = 'Hold';
										elseif($row->status==2)
											$status = 'Ongoing';
										elseif($row->status==3)
											$status = 'Completed';
										
									?>
									<tr>
										<td >{{$i++}}</td>
										<td >{{date('d-m-Y',strtotime($row->creation_datetime))}}</td>
										<td >{{$row->wo_no}}</td>
										<td >{{$row->reference_no}}</td>
										<td >{{$row->job}}</td>
										<td >{{$row->location}}</td>
										<td >{{$row->technician}}</td>
										<td >{{$row->description}}</td>
										<td >{{$row->wo_type}}</td>
										<td >{{$status}}</td>
									</tr>
									@endforeach
								</body>
							</table>
							@else
								<table class="table" border="0">
									<thead>
										<th >Enq. No.</type>
										<th >Date</th>
										<th >WO Type</th>
										<th >Customer</th>
										<th >Location</th>
										<th >Status</th>
									</thead>
									<body>
										@foreach($report as $row)
										<?php 
											if($row->status==0)
												$status = 'Open';
											elseif($row->status==1)
												$status = 'Transfered';
											elseif($row->status==2)
												$status = 'Cancelled';
											
										?>
										<tr>
											<td >{{$row->enq_no}}</td>
											<td >{{date('d-m-Y',strtotime($row->enquiry_datetime))}}</td>
											<td >{{$row->wo_type}}</td>
											<td >{{$row->customer}}</td>
											<td >{{$row->location}}</td>
											<td >{{$status}}</td>
										</tr>
										@endforeach
									</body>
								</table>
							@endif
                        </div>
						
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right">
								 <button type="button" class="btn btn-responsive button-alignment btn-primary" data-toggle="button">
									<span style="color:#fff;" onclick="javascript:window.print();">
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
									
								<button type="button" class="btn btn-responsive button-alignment btn-primary" data-toggle="button">
										<span style="color:#fff;" onclick="javascript:window.close();">
											<i class="fa fa-fw fa-times"></i>
										Close 
									</span>
                                </button>
                                </span>
                        </div>
                    </div>
					
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('ms_reports/export') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="date_from" value="{{$datefrom}}" >
					<input type="hidden" name="date_to" value="{{$dateto}}" >
					<input type="hidden" name="type" value="{{$wtype}}" >
					<input type="hidden" name="status" value="{{$sts}}" >
					</form>
					
                </div>
            </div>
            <!-- row -->
        
        </section>


{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script>
function getExport() {
	document.frmExport.submit();
}
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/invoice.js')}}"></script>
    <!-- end of page level js -->
@stop
