
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
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/bootstrap.css')}}">
@yield('header_styles')

    <!--end of page level css-->
	<style>
		.catrow {
			height:40px !important;
		}
		.grprow {
			height:35px !important; padding-left:10px !important;
		}
		.itmrow {
			height:30px !important;
		}
    .innerTable {
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 8px; vertical-align: top; border: 1px solid #ccc !important; }
        .category { font-weight: bold; background: #f2f2f2 !important; }
        .group-row { font-style: italic; background-color: #f9f9f9 !important; }
        .account-row { padding-left: 20px !important; }
        .total { font-weight: bold; background-color: #ddd !important; }
        .column { width: 50% !important; }
    }
	</style>

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

@php
    function formatAmount($amount) {
        return $amount < 0
            ? '(' . number_format(abs($amount), 2) . ')'
            : number_format($amount, 2);
    }
@endphp

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
											<p>Date From: <b><?php echo ($fromDate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($fromDate));?></b> &nbsp; To: <b><?php echo ($toDate=='')?date('d-m-Y'):$toDate;?></b></p>
										</td>
									</tr>
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center" class="innerTable">
											<table class="table table-bordered" border="1">
                                                <thead>
                                                    <tr>
                                                        <th>Liabilities</th>
                                                        <th>Amount</th>
                                                        <th>Assets</th>
                                                        <th>Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $maxCategories = max(count($liabilitiesAndEquity), count($assets));
                                                    @endphp

                                                    @for ($i = 0; $i < $maxCategories; $i++)
                                                        <tr>
                                                            {{-- Liabilities Subcategory --}}
                                                            @if(isset($liabilitiesAndEquity[$i]))
                                                                <td class="subcategory"><strong>{{ $liabilitiesAndEquity[$i]['name'] }}</strong></td>
                                                                <td class="subcategory total"><strong>{{ formatAmount($liabilitiesAndEquity[$i]['total']) }}</strong></td>
                                                            @else
                                                                <td></td><td></td>
                                                            @endif

                                                            {{-- Assets Subcategory --}}
                                                            @if(isset($assets[$i]))
                                                                <td class="subcategory"><strong>{{ $assets[$i]['name'] }}</strong></td>
                                                                <td class="subcategory total"><strong>{{ formatAmount($assets[$i]['total']) }}</strong></td>
                                                            @else
                                                                <td></td><td></td>
                                                            @endif
                                                        </tr>

                                                        @php
                                                            $liabGroups = $liabilitiesAndEquity[$i]['groups'] ?? [];
                                                            $assetGroups = $assets[$i]['groups'] ?? [];
                                                            $maxGroups = max(count($liabGroups), count($assetGroups));
                                                        @endphp

                                                        @for ($g = 0; $g < $maxGroups; $g++)
                                                            <tr>
                                                                {{-- Liabilities Group --}}
                                                                @if(isset($liabGroups[$g]))
                                                                    <td class="group">{{ $liabGroups[$g]['group_name'] }}</td>
                                                                    <td class="group total">{{ formatAmount($liabGroups[$g]['group_total']) }}</td>
                                                                @else
                                                                    <td></td><td></td>
                                                                @endif

                                                                {{-- Assets Group --}}
                                                                @if(isset($assetGroups[$g]))
                                                                    <td class="group">{{ $assetGroups[$g]['group_name'] }}</td>
                                                                    <td class="group total">{{ formatAmount($assetGroups[$g]['group_total']) }}</td>
                                                                @else
                                                                    <td></td><td></td>
                                                                @endif
                                                            </tr>
                                                        @endfor
                                                    @endfor

                                                    {{-- Grand Totals --}}
                                                    <tr>
                                                        <td><strong>Total Liabilities</strong></td>
                                                        <td><strong>{{ formatAmount(collect($liabilitiesAndEquity)->sum('total')) }}</strong></td>
                                                        <td><strong>Total Assets</strong></td>
                                                        <td><strong>{{ formatAmount(collect($assets)->sum('total')) }}</strong></td>
                                                    </tr>
                                                </tbody>
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

                                    <form method="GET" action="{{ url('balancesheet2/search') }}">
                                        <input type="hidden" name="date_from" value="{{ $fromDate }}">
                                        <input type="hidden" name="date_to" value="{{ $toDate }}">
                                        <input type="hidden" name="search_type" value="{{ $searchtype }}">
                                        <button type="submit" name="export" value="1" class="btn btn-responsive button-alignment btn-primary">Export to Excel</button>
                                    </form>
								
                                </span>
                        </div>
                    </div>
					
					
					
                </div>
            </div>
            <!-- row -->
        
        <!-- right side bar end -->
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

