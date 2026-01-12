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
											<p>Date From: <b><?php echo ($startDate=='')?date('d-m-Y',strtotime($settings->from_date)):date('d-m-Y',strtotime($startDate));?></b> &nbsp; To: <b><?php echo ($endDate=='')?date('d-m-Y'):date('d-m-Y',strtotime($endDate));?></b></p>
										</td>
									</tr>
									
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
                                            @if(isset($reportData))
                                                <table class="table table" border="1" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="2">Expenses</th>
                                                            <th colspan="2">Income</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Particulars</th>
                                                            <th>Amount</th>
                                                            <th>Particulars</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            function buildPLRows($data)
                                                            {
                                                                $rows = array();
                                                                $total = 0;

                                                                foreach ($data as $parentName => $groups) {
                                                                    $rows[] = array('label' => $parentName, 'amount' => '', 'type' => 'header');
                                                                    
                                                                    foreach ($groups as $groupName => $accounts) {
                                                                        $groupTotal = 0;
                                                                        $accountRows = array();

                                                                        foreach ($accounts as $account => $amount) {
                                                                            if (floatval($amount) != 0) {
                                                                                $accountRows[] = array(
                                                                                    'label' => '    ' . $account,
                                                                                    'amount' => number_format($amount, 2),
                                                                                    'type' => 'account'
                                                                                );
                                                                                $groupTotal += $amount;
                                                                            }
                                                                        }

                                                                        if ($groupTotal != 0) {
                                                                            // ✅ Group heading with total (in amount column)
                                                                            $rows[] = array(
                                                                                'label' => '  ' . $groupName,
                                                                                'amount' => number_format($groupTotal, 2),
                                                                                'type' => 'group'
                                                                            );

                                                                            foreach ($accountRows as $accRow) {
                                                                                $rows[] = $accRow;
                                                                            }

                                                                            $total += $groupTotal;
                                                                        }
                                                                    }
                                                                }

                                                                return array('rows' => $rows, 'total' => $total);
                                                            }

                                                            $expenseResult = buildPLRows($reportData['expense']);
                                                            $expenseRows = $expenseResult['rows'];
                                                            $totalExpense = $expenseResult['total'];

                                                            $incomeResult = buildPLRows($reportData['income']);
                                                            $incomeRows = $incomeResult['rows'];
                                                            $totalIncome = $incomeResult['total'];

                                                            $maxRows = max(count($expenseRows), count($incomeRows));
                                                        @endphp

                                                        @for($i = 0; $i < $maxRows; $i++)
                                                            <tr>
                                                                <td>
                                                                    @if(isset($expenseRows[$i]['type']) && $expenseRows[$i]['type'] == 'group')
                                                                        <strong>{{ $expenseRows[$i]['label'] }}</strong>
                                                                    @else
                                                                        {{ isset($expenseRows[$i]['label']) ? $expenseRows[$i]['label'] : '' }}
                                                                    @endif
                                                                </td>
                                                                <td align="right">{{ isset($expenseRows[$i]['amount']) ? $expenseRows[$i]['amount'] : '' }}</td>

                                                                <td>
                                                                    @if(isset($incomeRows[$i]['type']) && $incomeRows[$i]['type'] == 'group')
                                                                        <strong>{{ $incomeRows[$i]['label'] }}</strong>
                                                                    @else
                                                                        {{ isset($incomeRows[$i]['label']) ? $incomeRows[$i]['label'] : '' }}
                                                                    @endif
                                                                </td>
                                                                <td align="right">{{ isset($incomeRows[$i]['amount']) ? $incomeRows[$i]['amount'] : '' }}</td>
                                                            </tr>
                                                        @endfor

                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="font-weight: bold; background-color: #f0f0f0;">
                                                            <td>Total Expense</td>
                                                            <td align="right">{{ number_format($totalExpense, 2) }}</td>
                                                            <td>Total Income</td>
                                                            <td align="right">{{ number_format($totalIncome, 2) }}</td>
                                                        </tr>
                                                        <tr style="font-weight: bold; background-color: #e0e0e0;">
                                                            <td colspan="2">
                                                                Net {{ $totalIncome - $totalExpense >= 0 ? 'Profit' : 'Loss' }}
                                                            </td>
                                                            <td colspan="2" align="right">
                                                                {{ number_format(abs($totalIncome - $totalExpense), 2) }}
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                                @endif
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
function getExport() { document.frmExport.submit(); }

$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
	
});
</script>
</html>

