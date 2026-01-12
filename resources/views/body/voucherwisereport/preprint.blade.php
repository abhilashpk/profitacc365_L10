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
        <!-- Main content -->
   <section class="content p-l-r-15" id="cost-job">
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
								<tr><td><br/></td></tr>
									<tr>
										<td colspan="2" align="left" valign="top" style="padding-left:0px;">
											<p>Date From: <b><?php echo ($dateto=='')?date('d-m-Y',strtotime($settings->from_date)):$dateto;?></b> &nbsp; To: <b><?php echo ($datefrom=='')?date('d-m-Y'):$datefrom;?></b></p>
										</td>
									</tr>
							</table><br/>
                        </div>
						
                        <div class="col-md-12">
								<div>
									<table class="table" border="0">
										<thead>
											<th style="width:30%;">Account Name</th>
											<th style="width:30%;">Description</th>
											<th style="width:10%;"></th>
											
											<th style="width:15%;" class="text-right">Debit</th>
											<th style="width:15%;" class="text-right">Credit</th>
										</thead>
										<body>
										<?php $gdrtotal = $gcrtotal = $i = 0; ?>
										@foreach($transactions as $transaction)
										<?php $i++;
											$invrow = reset($transaction);
											switch($invrow->voucher_type)
											{
												case 'PI':
													$vr_name = 'Purchase Invoice';
													break;
													
												case 'SI':
													$vr_name = 'Sales Invoice';
													break;
													
												case 'JV':
													$vr_name = 'Journal Voucher';
													break;
												
												case 'RV':
													$vr_name = 'Receipt Voucher';
													break;
													
												case 'PV':
													$vr_name = 'Payment Voucher';
													break;
													
												case 'PR':
													$vr_name = 'Purchase Return';
													break;
													
												case 'SR':
													$vr_name = 'Sales Return';
													break;
													
												case 'DB':
													$vr_name = 'PDC Received Transfer';
													break;
													
												case 'CB':
													$vr_name = 'PDC Issued Transfer';
													break;
													
												case 'GI':
													$vr_name = 'Goods Issued Note';
													break;
													
												case 'GR':
													$vr_name = 'Goods Return Note';
													break;
													
												case 'PIN':
													$vr_name = 'Purchase Invoice(Non-Stock)';
													break;
													
												case 'SIN':
													$vr_name = 'Sales Invoice(Non-Stock)';
													break;
													
												case 'PC':
													$vr_name = 'Petty Cash Voucher';
													break;
													
												case 'STI':
													$vr_name = 'Stock Transfer In';
													break;
													
												case 'STO':
													$vr_name = 'Stock Transfer Out';
													break;
													
												case 'MV':
													$vr_name = 'Manufacture Voucher';
													break;
													
												case 'SS':
													$vr_name = 'Sales Split';
													break;
												case 'SSR':
													$vr_name = 'Sales Split Return';
													break;
														
												case 'PS':
													$vr_name = 'Purchase Split';
													break;
													
												case 'PSR':
													$vr_name = 'Purchase Split Return';
													break;	
													
												case 'MJV':
													$vr_name = 'Manual Journal';
													break;
													
												case 'PIR':
													$vr_name = 'Purchase Rental';
													break;
													
												case 'SIR':
													$vr_name = 'Sales Rental';
													break;
											}
										?>
											<tr>
												<td style="width:30%;" class="txn" id="r_{{$i}}">Type: <b id="tr_{{$i}}">{{$invrow->voucher_type}}</b> &nbsp; &nbsp; Vr. <b>{{$vr_name}}</b></td>
												<td style="width:30%;" align="right">Vr. No: <b id="tid_{{$i}}">{{$invrow->voucher_no}}</b></td>
												<th style="width:10%;"align="right">Ref No:<b ><?php echo ($invrow->reference_no !='')?$invrow->reference_no:$invrow->reference;?></b></th>
												
												<td style="width:15%;" align="right">Vr. Date:</td>
												<td style="width:15%;" align="left"><b>{{date('d-m-Y',strtotime($invrow->voucher_date))}}</b></td>
											</tr>
											<?php $drtotal = $crtotal = 0; ?>
											@foreach($transaction as $row)
											<?php 
												if($row->type=='Dr')
													$drtotal += $row->amount; 
												else
													$crtotal += $row->amount; 
											?>
											<tr>
												<td style="width:30%;">{{$row->master_name}}</td>
												<td style="width:30%;" align="left">{{$row->description}}</td>
												<td style="width:10%;" align="left"></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->type=='Dr')?number_format($row->amount,2):'';?></td>
												<td style="width:15%;" class="text-right"><?php echo ($row->type=='Cr')?number_format($row->amount,2):'';?></td>
											</tr>
											
											@endforeach
											<?php 
													$gdrtotal += $drtotal; 
													$gcrtotal += $crtotal; 
											?>
											<tr>
												<td style="width:30%;"></td>
												<td style="width:30%;" align="left"></td>
												<td style="width:10%;" align="left"><b>Vr. Total</b></td>
												<td style="width:15%;" class="text-right"><b id="dr_{{$i}}">{{number_format($drtotal,2)}}</b></td>
												<td style="width:15%;" class="text-right"><b id="cr_{{$i}}">{{number_format($crtotal,2)}}</b></td>
											</tr>
											<tr>
												<td style="width:30%;"></td>
												<td style="width:30%;" align="left"></td>
												<td style="width:10%;" align="left"></td>
												<td style="width:15%;" class="text-right"></td>
												<td style="width:15%;" class="text-right"><br/></td>
											</tr>
										@endforeach
										</body>
									</table>
									
									<table border="0" style="width:100%;">
										<tr>
											<td style="width:30%;"></td>
											<td style="width:30%;" align="left"></td>
											<td style="width:10%;" align="left"><b>Grand Total</b></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><b>{{number_format($gdrtotal,2)}}</b></td>
											<td style="width:15%; padding-right:10px;" class="text-right"><b>{{number_format($gcrtotal,2)}}</b></td>
										</tr>
									</table><br/><br/>
								</div>
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
								
								<button type="button" id="findDiff"
											 class="btn btn-responsive button-alignment btn-primary"
											 data-toggle="button" >
										<span style="color:#fff;">
											<i class="fa fa-fw fa-crosshairs"></i>
										Find Difference
									</span>
									</button>
                                </span>
                        </div>
						
						<form class="form-horizontal" role="form" method="POST" name="frmExport" id="frmExport" action="{{ url('voucherwise_report/export') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="date_from" value="{{$datefrom}}" >
						<input type="hidden" name="date_to" value="{{$dateto}}" >
						<input type="hidden" name="voucher_type" value="{{$type}}" >
						</form>
						
                    </div>
                </div>
			</div>
            
	    </div>        
        
	</section>	
	
</aside>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.all.min.js"></script>
<script>
function getExport() { document.frmExport.submit(); }

$(document).on('click','#findDiff',function(){
	var ids='';
	$( '.txn' ).each(function() { 
	  var res = this.id.split('_');
	  var n = res[1]; 
	  if( $('#dr_'+n).html() != $('#cr_'+n).html()) {
		  ids = (ids=='')?$('#tr_'+n).html()+' No: '+$('#tid_'+n).html():ids+', '+$('#tr_'+n).html()+' No: '+$('#tid_'+n).html();
		 // alert(ids);
	  }
	});
	if(ids!='')
		Swal.fire("The following records are found difference. "+ids);
	else
		Swal.fire("There weren't any differences found.");
});


</script>

