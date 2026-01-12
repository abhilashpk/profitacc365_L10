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



        <section class="content p-l-r-15" id="cost-job">
            <div class="row">
            <div class="col-lg-12">
               <div >
                       
                       <div >
                            <div class="col-md-12">
							<table border="0" style="width:100%;">
								<tr><td align="center"><h3>{{Session::get('company')}}</h3></td>
								</tr>
								<tr>
									<td align="center"><h4><b><u>{{$heading}} Address List</u></b></h4>
									</td>
								</tr>
							</table><br/>
                        </div>
						
                            <div class="col-md-12">
                                <table class="table table-striped" id="Acmaster">
                                    <thead>
                                    <tr>
                                        <th>Account ID</th>
                                        <th>Account Name</th>
										<th>TRN No</th>
										<th>Address</th>
										<!--<th>State/City</th>-->
										<th>Phone</th>
                                        <th>Fax</th>
                                        <th>Email</th>
                                    </tr>
                                    </thead>
                                    <tbody>
										@foreach($addresslist as $row)
										<tr>
										<td>{{$row->account_id}}</td>
										<td>{{$row->master_name}}</td>
										<td>{{$row->vat_no}}</td>
										<td>{{$row->address}}</td>
										<!--<td>{{$row->state.' '.$row->city}}</td>-->
										<td>{{$row->phone}}</td>
										<td>{{$row->fax}}</td>
										<td>{{$row->email}}</td>
										</tr>
										@endforeach
									</tbody>
                                </table>
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
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('account_enquiry/address_export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="account_name" value="{{$name}}" >
						<input type="hidden" name="account_type" value="{{$type}}" >
						</form>
                    </div>
					
                        </div>
						
                    </div>
                        
              </div>
				
            </div>
        </div>
       
       
            <!--main content-->
            <!-- row -->
      
        </section>


{{-- page level scripts --}}
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
@yield('footer_scripts')
<!-- end page level js -->

    <!-- begining of page level js -->
</body>
<
<!-- end of page level js -->

<script>
function getExport() { document.frmExport.submit(); }
</script>
</html>
