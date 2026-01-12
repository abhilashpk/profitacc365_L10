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
											<p>Date From: <b><?php echo date('d-m-Y');?></b> &nbsp; To: <b><?php echo ($todate=='')?date('d-m-Y'):$todate;?></b></p>
										</td>
									</tr>
									
								</thead>
								<tbody id="bod">
									<tr style="border:0px solid black;">
										<td colspan="2" align="center">
											<?php if($type == 'Summary' ) { ?>
												<table border="1" class="table table" cellpadding="5" cellspaching="0">
													<thead>
													<tr style="background-color:#ccc">
														<th>
															<strong>Account Group</strong>
														</th>
													<th class="text-right">
															<strong></strong>
														</th>
														
													
														
														<th class="text-right">
															<strong>Balance</strong>
														</th>
													</tr>
													</thead>
													<tbody>
													<?php $cr_total = $dr_total = $bl_total = 0; ?>
													@foreach($results as $row)
												<?php	$bl_total+=$row->balance; ?>
													 <?php if($row->categories!='PDCI'){ ?>
													<tr style="background-color: #f0f0f0; font-weight: bold;">
                                                     <?php 
													         
																$cr_total += $row->balance;
															
														
														?>
														<td>{{$row->categories}}</td>
														<td></td>
														
													<!--	<td class="emptyrow text-right">{{number_format($row->debit,2)}}</td>
														<td class="emptyrow text-right">{{number_format($row->credit,2)}}</td>-->
														<td class="emptyrow text-right">{{number_format($row->balance,2)}}</td>
													</tr>
													<?php } ?>
													@endforeach
													
												<tr style="background-color:#ccc">
														<td class="highrow text-right"></td>
													
														<td class="emptyrow text-right"><strong>Cash and Other Bills Receivable :</strong></td>
															<td class="emptyrow text-right"><strong>{{number_format($cr_total,2)}}</strong></td>
													</tr>
													
													@foreach($results as $row)
													 <?php if($row->categories=='PDCI'){ ?>
													<tr style="background-color: #f0f0f0; font-weight: bold;">
                                                     <?php 
													          
														
															$dr_total+=$row->balance;
															
														
														?>
														<td>{{$row->categories}}</td>
														<td></td>
														
													<!--	<td class="emptyrow text-right">{{number_format($row->debit,2)}}</td>
														<td class="emptyrow text-right">{{number_format($row->credit,2)}}</td>-->
														<td class="emptyrow text-right">{{number_format($row->balance,2)}}</td>
													</tr>
													<?php } ?>
													@endforeach
													
											
													<tr style="background-color:#ccc">
														<td class="highrow text-right"></td>
													
														<td class="emptyrow text-right"><strong>Bills Payable :</strong></td>
															<td class="emptyrow text-right"><strong>{{number_format($dr_total,2)}}</strong></td>
													</tr>
													<tr style="background-color:#ccc">
														<td class="highrow text-right"></td>
														
														<td class="emptyrow text-right"><strong>Net Cash In Hand :</strong></td>
															<td class="emptyrow text-right"><strong>{{number_format($bl_total,2)}}</strong></td>
													</tr>
													</tbody>
												</table>
										
											<?php }else if($type == 'Details' ) {  ?>
											 <?php $net_total = 0; ?>
											@foreach($results as $category =>$rows)
                                                   <h4><strong>{{ $category }}</strong></h4>
                                                     <table border="1" class="table table" cellpadding="5" cellspaching="0">
													<thead>
													<tr style="background-color:#ccc">
														<th>
															<strong>Account Group</strong>
														</th>
														<th class="text-right">
															<strong></strong>
														</th>
														
														
														
														<th class="text-right">
															<strong>Balance</strong>
														</th>
													</tr>
													</thead>
													<tbody>
													    <?php $total = 0; ?>
                                                  @foreach($rows as $row)
                                                  <?php 
													  $total += $row['balance'];
													?>
                                                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                                                            <td>{{$row['account_name'] }}</td>
                                                            <td></td>
                                                           <!-- <td  class="emptyrow text-right">{{ number_format($row['debit'], 2)}} </td>
                                                            
                                                         <td class="emptyrow text-right">{{ number_format($row['credit'], 2) }}</td>-->
                                                            <td  class="emptyrow text-right">{{ number_format($row['balance'], 2) }}</td>
                                                          </tr>
                                                      @endforeach
                                                      <tr style="background-color: #f0f0f0; font-weight: bold;">
														<td class="highrow text-right"></td>
														
														<td class="emptyrow text-right"><strong>Total :</strong></td>
															<td class="emptyrow text-right"><strong>{{number_format($total,2)}}</strong></td>
													</tr>
													
													 
                                                      </tbody>
                                                  </table>
                                                  <?php 
													  $net_total += $total;
													?>
                                                   @endforeach
                                                   
                                                   <table class="table table">
                                                        <tr style="background-color:#ccc">
														<td class="highrow text-right"></td>
													
														<td class="emptyrow text-right"><strong>Net Cash In Hand :</strong></td>
															<td class="emptyrow text-right"><strong>{{number_format($net_total,2)}}</strong></td>
													</tr>
												</table>	
													
										<?php } ?>	
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
					<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('cash_inhand/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$fromdate}}" >
						<input type="hidden" name="date_to" value="{{$todate}}" >
						<input type="hidden" name="search_type" value="{{$type}}" >
						<input type="hidden" name="selectedcategory" value="{{$selectedcategories}}" >
						
						
						
					</form>
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
