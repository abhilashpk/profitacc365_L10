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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom_css/skins/skin-coreplus.css')}}" type="text/css" id="skin"/>
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

<style>
#invoicing {
	font-size:9pt;
}

.tblstyle td,
  .tblstyle th {
    height:15px;
	padding:2px;
	border:1px solid #000 !important;
  }


</style>
<style type="text/css" media="print">


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
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="right">
        <section class="content p-l-r-15" id="invoice-stmt">
            <div class="panel">
                
                <div class="panel-body" style="width:100%; !important; border:0px solid red;">
                    <div class="print" id="invoicing">
						<div class="col-md-12">
												
							<table border="0" style="width:100%;height:100%;">
								<thead>
									<tr>
										<td colspan="2" align="center">@include('main.print_head_stmt')</td>
									</tr>
									<tr>
										<td colspan="2" align="center"><b style="font-size:16px;"><br/><b><u>{{$voucherhead}}</u></b></b></td>
									</tr>
									<tr><td><br/></td></tr>
									<tr>
										<td colspan="2" align="left" valign="top" style="padding-left:0px;">
											<p>Date From: <b><?php echo ($from=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($from));?></b> &nbsp; To: <b><?php echo ($to=='')?date('d-m-Y'):date('d-m-Y',strtotime($to));?></b></p>
										</td>
									</tr>
									
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
                                            <table border="1" cellpadding="5" cellspacing="0" class="table table">
                                                <thead>
                                                    <tr>
                                                        <th>Account Name</th>
                                                        <th> Debit</th>
                                                        <th> Credit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($groupedAccounts as $groupName => $accounts)
                                                        <!-- Group Header Row -->
                                                        <tr style="background-color: #f0f0f0;">
                                                            <td colspan="5"><strong>{{ $groupName }}</strong></td>
                                                        </tr>

                                                        @foreach ($accounts as $account)
                                                            <tr>
                                                                <td>{{ $account['account_id'].' - '.$account['account_name'] }}</td>
                                                                <td style="text-align:right">{{ number_format($account['debit'], 2) }}</td>
                                                                <td style="text-align:right">{{ number_format($account['credit'], 2) }}</td>
                                                            </tr>
                                                        @endforeach

                                                        @php
                                                            $groupDebitTotal = $accounts->sum('debit');
                                                            $groupCreditTotal = $accounts->sum('credit');
                                                        @endphp

                                                        <tr style="background-color:#e0e0e0; font-weight:bold;">
                                                            <td>Group Total</td>
                                                            <td style="text-align:right">{{ number_format($groupDebitTotal, 2) }}</td>
                                                            <td style="text-align:right">{{ number_format($groupCreditTotal, 2) }}</td>
                                                        </tr>

                                                    @endforeach
                                                </tbody>
                                                @php
                                                    $grandDebit = $groupedAccounts->flatten(1)->sum('debit');
                                                    $grandCredit = $groupedAccounts->flatten(1)->sum('credit');
                                                @endphp

                                                <tfoot>
                                                    <tr style="font-weight: bold; background-color: #d0f0d0;">
                                                        <td colspan="1" style="text-align:right">Grand Total</td>
                                                        <td style="text-align:right">{{ number_format($grandDebit, 2) }}</td>
                                                        <td style="text-align:right">{{ number_format($grandCredit, 2) }}</td>
                                                    </tr>
                                                </tfoot>

                                            </table>
                                            
										</td>
									</tr>
								</tbody>
								<tfoot id="inv">
									<tr>
										<td colspan="2" class="footer"><br/>@include('main.print_foot_stmt')</td>
									</tr>
								</tfoot>
							</table>
						
						
						</div>
                    </div>
                    <div class="btn-section">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                <span class="pull-right" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                        <button type="button" onclick="javascript:window.print();" 
                                                class="btn btn-responsive button-alignment btn-primary"
                                                data-toggle="button">
                                            <span style="color:#fff;" >
                                                <i class="fa fa-fw fa-print"></i>
                                            Print
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

                                    <form method="GET" action="{{ url('trial_balance2/search') }}">
                                        <input type="hidden" name="from_date" value="{{ $from }}">
                                        <input type="hidden" name="to_date" value="{{ $to }}">
                                        <input type="hidden" name="search_type" value="{{ $search_type }}">
                                        <input type="hidden" name="trim_zero" value="{{ $trimzero }}">
                                        <button type="submit" name="export" value="1" class="btn btn-responsive button-alignment btn-primary">Export to Excel</button>
                                    </form>
								
                                </span>
                        </div>

                        
                    </div>
					
                </div>
            </div>

        </section>

    </aside>
    <!-- page wrapper-->
</div>
<!-- wrapper-->
<!-- global js -->
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
<!-- end of global js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
<script>

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
});
</script>
</html>
