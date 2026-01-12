<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Profit ACC 365 - ERP Software
        @show
    </title>
    
<style>
@media print {
	
	.btn-section {display : none;}
}

</style>
<style type="text/css" media="print">

</style>

</head>
<body >

<table border="0" style="width:100%;height:100%">
	<thead>
		<tr>
			<td align="left" width="45%" height="85px;"></td>
			<td  align="left"></td>
			<td width="40%"></td>
		</tr>
		
		<tr>
			<td align="left" valign="top">
				<div style="padding-left:35px;"> 
					<?php if($details->supplier == 'CASH - CAR WASH') {
						echo $details->customer_name;
					} else { echo $details->supplier; } ?>
					<?php //echo ($details->customer_name=='')?$details->supplier.$details->customer_name:$details->customer_name;?>
					<?php echo ($details->address!='')?'<br/>'.$details->address:'';?>
					
					<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
					<?php echo ($vat_no!='')?', TRN No: '.$vat_no:'';?>
				</div>
			</td>
			<td align="center">
			    <?php echo ($details->reg_no!='')?'<br/>Vehicle No: '.$details->reg_no:'';?>
			</td>
			<td align="left" valign="top" style="padding-left:100px;" height="78px;">
				<?php date_default_timezone_set('Asia/Dubai'); $datetime = date('Y-m-d H:i:s'); ?>
					<b> {{$details->voucher_no}}</b></br>
					{{date('d-m-Y',strtotime($details->voucher_date))}}
				
			</td>
		</tr>
		
	</thead>
	<tbody id="bod">
		<tr style="border:0px solid red;">
			<td colspan="3" style="padding-left:10px;">
			<table border="0" width="100%">
				<tr>
					<td height="204px" colspan="6" valign="top" align="left">
					<table border="0" width="100%">
					<?php $i = 0;?>
					@foreach($items as $item)
					<?php $i++; 
						
							$unit_price = $item->unit_price;
							$vat_amount = $item->vat_amount;
							$line_total = $item->line_total;
							
							if($details->discount!=0) {
								$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
							}
					?>
					<tr>
						<td width="3%" style="padding-left:5px;">{{$i}}</td>
						<td width="42%" style="padding-left:5px;">{{$item->item_name}}</td>
						<td width="10%" align="center">{{$item->quantity}}</td>
						<td width="10%" align="center">{{$item->unit_name}}</td>
						<td width="10%" align="right" style="padding-right:10px;">{{number_format($unit_price,2)}}</td>
						<td width="10%" align="center" style="padding-right:0px;">{{number_format($line_total,2)}}</td>
						<td width="15%"></td>
					</tr>
					@endforeach
					</table>
					</td>
				</tr>
				<?php 
					$total = $details->subtotal;
					$vat_amount_net = $details->vat_amount;
					$net_total = $details->net_total;
				?>
					<tr>																														
						<td colspan="4" align="right" height="15px"></td>
						<td align="center" width="10%" height="15px" style="padding-right:0px;"><b>{{number_format($total,2)}}</b></td>
						<td align="right" width="15%" height="15px"></td>
					</tr>
					<tr>																														
						<td colspan="4" align="right" height="15px"></td>
						<td align="center" height="15px" width="10%" style="padding-right:0px;"><b>{{number_format($vat_amount_net,2)}}</b></td>
						<td align="right" height="15px" width="15%"></td>
					</tr>
					<tr>	
						<td colspan="4" align="left" height="15px" style="padding-left:140px;"> {{$amtwords}}.</td>
						<td align="center" height="15px" width="10%" style="padding-right:0px;"><b>{{number_format($net_total,2)}}</b></td>
						<td align="right" height="15px" width="15%"></td>
					</tr>
			</table>
			</td>
		</tr>
	</tbody>
	
</table>
		
 <button type="button" onclick="javascript:window.print();" 
					 class="btn btn-responsive button-alignment btn-primary btn-section"
					 data-toggle="button">
				<span style="color:#fff;" >
					<i class="fa fa-fw fa-print"></i>
				Print
			</span>
</button>		
</body>
<script>

</script>
</html>
