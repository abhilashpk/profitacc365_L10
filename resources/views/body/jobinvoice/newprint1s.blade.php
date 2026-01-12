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

<style>
#invoicing {
	font-size:10pt;
}
	
/*.header, .header-space {
  height: 10px;
}*/

.footer, .footer-space {
  height: 200px; /*285*/
}

.header {
  position: fixed;
  top: 0;
}

.footer {
  position: fixed;
  bottom: 0px;
  
}

</style>
<style type="text/css" media="print">

thead { display: table-header-group; }

</style>
</head>

<body>
@yield('horizontal_header')
<aside class="right">
       <section id="invoice-stmt">
                 <div class="" style="width:100%; !important; border:0px solid red; align=center;">
                    <div class="print" id="invoicing">
						<div>
						
						<table border="1" style="width:100%;height:100%;">
							<thead>
								<tr>
									<td style="padding-left:15px;" height="180px" width="455px">
										<strong><?php echo ($details->supplier=='CASH CUSTOMER' || $details->supplier=='Cash Customer')?'CASH CUSTOMER:<br/>'.$details->customer_name:$details->supplier;?></strong>
										<?php echo ($details->address!='')?'<br/>'.$details->address:'';?>
										<?php echo ($details->state!='')?' '.$details->state:'';?>
										<?php echo ($details->pin!='')?'<br/>'.$details->pin:'';?>
										<?php echo ($details->phone!='')?'Phone:'.$details->phone:'';?>
										<?php $vat_no = ($details->vat_no=='')?$details->customer_phone:$details->vat_no; ?>
										<?php echo ($vat_no!='')?'<br/>TRN No: <strong>'.$vat_no.'</strong>':'';?><br/>
									</td>
									<td align="left" style="padding-left:200px;padding-top:5px;" valign="top">
										<table border="0">
											<tr>
												<td height="35px" align="left"> {{$details->jobcode}}</td>
											</tr>
											<tr>
												<td height="35px" align="left"> {{date('d-m-Y',strtotime($details->jobdate))}}</td>
											</tr>
											<tr>
												<td height="35px" align="left"> {{$details->voucher_no}}</td>
											</tr>
											<tr>
												<td height="35px" align="left"> {{date('d-m-Y',strtotime($details->voucher_date))}}</td>
											</tr>
										</table>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" style="border:1px solid blue;" height="420px" align="left" valign="top">
									<?php $total = $vat_amount_net = $net_total = 0;?>
									<table border="0" style="font-size:9pt; !important;">
										<tr>
											<td width="35px" height="40px"><b></b></td>
											<td width="365px"><b></b></td>
											<td width="37px"><b></b></td>
											<td width="62px" class="text-right"><b></b></td>
											<td width="75px" class="text-right"><b></b></td>
											<td width="28px"><b></b></td>
											<td width="60px" class="text-right"><b> </b></td>
											<td width="90px" class="text-right"><b></b></td>
										</tr>
											<?php $i = 0;?>
											@foreach($items as $item)
											<?php $i++; 
												
													$unit_price = $item->unit_price;
													$vat_amount = $item->vat_amount;
													$line_total = $item->line_total;
													$amount = $unit_price * $item->quantity;
													if($details->discount!=0) {
														$line_total = ($item->unit_price * $item->quantity) + $item->vat_amount;
													}
											?>
											
											<?php if($i==23 || $i==45 || $i==67) { ?>
											<tr>
											<td width="35px" height="40px"><b>&nbsp; </b></td>
											<td width="365px"><b></b></td>
											<td width="37px"><b></b></td>
											<td width="62px" class="text-right"><b></b></td>
											<td width="75px" class="text-right"><b></b></td>
											<td width="28px"><b></b></td>
											<td width="60px" class="text-right"><b> </b></td>
											<td width="90px" class="text-right"><b>&nbsp; </b></td>
											</tr>
											<?php } ?>
											
											<tr>
												<td align="center">{{$i}}</td>
												<td>{{$item->item_name}}</td>
												<td align="center">{{$item->quantity}}</td>
												<td class="text-right">{{number_format($unit_price,2)}}</td>
												<td class="text-right">{{number_format($amount,2)}}</td>
												<td align="center">5</td>
												<td class="text-right">{{number_format($vat_amount,2)}}</td>
												<td class="text-right">{{number_format($line_total,2)}}</td>
											</tr>
										<!--<tr>
											<td>2</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>3</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>4</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>5</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>6</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>7</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>8</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>9</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>10</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>11</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>12</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>13</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>14</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>15</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>16</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>17</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>18</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>19</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>20</td>
											<td>Test 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>21</td>
											<td>Test3 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>22</td>
											<td>Test65 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td width="35px" height="40px"><b></b></td>
											<td width="365px"><b></b></td>
											<td width="37px"><b></b></td>
											<td width="62px" class="text-right"><b></b></td>
											<td width="75px" class="text-right"><b></b></td>
											<td width="28px"><b></b></td>
											<td width="60px" class="text-right"><b> </b></td>
											<td width="90px" class="text-right"><b></b></td>
										</tr>
										<tr>
											<td>23</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>24</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>25</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>26</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>27</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>28</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>29</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>30</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>31</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>32</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>33</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>34</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>35</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>36</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>37</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>38</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>39</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>40</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>41</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>42</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>43</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>44</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>45</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>46</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>47</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>48</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>49</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>
										<tr>
											<td>50</td>
											<td>Galaxy 800D</td>
											<td class="text-right">5</td>
											<td class="text-right">50.00</td>
											<td class="text-right">5.00</td>
											<td align="center">5</td>
											<td class="text-right">10.00</td>
											<td class="text-right">260.00</td>
										</tr>-->
										@endforeach
										<?php $total = $details->total;
												$vat_amount_net = $details->vat_amount;
												$net_total = $details->net_total; ?>
									</table>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2" align="center" style="border:1px solid red;"><div class="footer-space">&nbsp;</div></td>
								</tr>
							</tfoot>
						</table>
						
							<div class="footer" style="text-align:center; border:0px solid red;">
								<table border="1">
									<tr>
										<td height="40px" colspan="2" style="padding-left:40px;">
											<b style="font-size:11pt;">{{$amtwords}}</b> <?php if($details->discount > 0){?>&nbsp; &nbsp; Discount: {{number_format($details->discount,2)}} <?php }?>
										</td>
									</tr>
									<tr>
										<td height="56px" colspan="2" style="padding-left:50px;">
											<table border="0">
												<tr>
													<td width="251px" height="40px" style="padding-left:30px;"><b style="font-size:11pt;">{{number_format($total,2)}}</b></td>
													<td width="251px"  style="padding-left:50px;"><b style="font-size:11pt;">{{number_format($vat_amount_net,2)}}</b></td>
													<td width="251px"  style="padding-left:50px;"><b style="font-size:11pt;">{{number_format($net_total,2)}}</b></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="2"></td>
									</tr>
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
            <!-- row -->
        </section>
</aside>
<script src="{{asset('assets/js/app.js')}}" type="text/javascript"></script>
@section('footer_scripts')
 </body>
<script>
$(document).ready(function () {
	$('html').attr({style: 'min-height: inherit'});
	$('body').attr({style: 'min-height: inherit'});
});
</script>
</html>
