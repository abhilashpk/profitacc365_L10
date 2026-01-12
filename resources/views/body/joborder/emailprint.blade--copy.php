<!DOCTYPE html>
<html>
<head>
	<title>Job Order Soft Copy</title>
</head>
<body>
	  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	  	<tbody><tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
					<tbody><tr>
						<td align="center" valign="top"  style="background-size:cover; background-position:top;height=" 200""="">
							<table class="col-600" width="600" height="100" border="0" align="center" cellpadding="0" cellspacing="0">

								<tbody>
								<tr>
									<td align="center" style="line-height: 0px;">
										<img style="display:block; line-height:0px; font-size:0px; border:0px;" src="{{asset('assets/majestic_logo.png')}}"  width="150" height="100" alt="logo">
									</td>
								</tr>
							</tbody></table>
						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>

		<tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:20px; margin-right:20px; border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
					<tbody>

					<tr>
						<td align="center" style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#333;">JOB ORDER</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
		

		<tr>
			<td align="center">
				<table width="600" class="col-600" align="center" border="0" cellspacing="0" cellpadding="0" style=" border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
					<tbody><tr>
						<td height="50"></td>
					</tr>
					<tr>
						<td>


							<table style="border:1px solid #e2e2e2;" class="col2" width="287" border="0" align="left" cellpadding="0" cellspacing="0">


								<tbody>
								<tr>
									<td align="center">
										<table class="insider" width="237" border="0" align="center" cellpadding="0" cellspacing="0">
											<tbody><tr>
												<td height="20"></td>
											</tr>

											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Customer Name : {{$details[0]->master_name}}
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Address       : <?php echo $details[0]->address;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Telephone Number  :<?php echo $details[0]->phone;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Customer TRN  :<?php echo $details[0]->vat_no;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Vehicle Type :<?php echo $details[0]->plate_type;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Plate Number :<?php echo $details[0]->reg_no;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Code :<?php echo $details[0]->code_plate;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Vehicle Model	:<?php echo $details[0]->model;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Vehicle Make :<?php echo $details[0]->make;?>	
												</td>
											</tr>
									


											<tr>
												<td height="10"></td>
											</tr>
	
										</tbody></table>
									</td>
								</tr>
								<tr>
									<td height="30"></td>
								</tr>
							</tbody></table>





							<table width="1" height="20" border="0" cellpadding="0" cellspacing="0" align="left">
								<tbody><tr>
									<td height="20" style="font-size: 0;line-height: 0;border-collapse: collapse;">
										<p style="padding-left: 24px;">&nbsp;</p>
									</td>
								</tr>
							</tbody></table>


							<table style="border:1px solid #e2e2e2;" class="col2" width="287" border="0" align="right" cellpadding="0" cellspacing="0">


								<tbody>
								<tr>
									<td align="center">
										<table class="insider" width="237" border="0" align="center" cellpadding="0" cellspacing="0">
											<tbody><tr>
												<td height="20"></td>
											</tr>

											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													JO No  : <?php echo $details[0]->year;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000;line-height:24px; font-weight: 300;">
													JO Date       : <?php echo $details[0]->voucher_date;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													LPO No : <?php echo $details[0]->reference_no;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Payment Terms :<?php echo $details[0]->terms;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000;line-height:24px; font-weight: 300;">
													Technician : <?php echo $details[0]->salesman;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													
												</td> 
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Mileage	:<?php echo $details[0]->kilometer;?>	
												</td>
											</tr>
												<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Fuel Level:<?php echo $details[0]->fuel_level;?>
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000;line-height:24px; font-weight: 300;">
													Dashboard Warning lights :<?php echo $details[0]->year;?>		
												</td>
											</tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Vehicle Year	:<?php echo $details[0]->year;?>
												</td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
										</tbody></table>
									</td>
								</tr>
								<tr>
									<td height="30"></td>
								</tr>
							</tbody></table>

						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
<tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:20px; margin-right:20px;">



		<tbody><tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style=" border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
					<tbody><tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td align="right">
      					<table width="287" border="0" align="left" cellpadding="0" cellspacing="0" class="col2" style="">
								<tbody><tr>
									<td align="center">
										<table class="insider" width="500" border="0" align="center" cellpadding="0" cellspacing="0">



											<tbody>											<tr>
												<td height="5"></td>
											</tr>


											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Items inside the vehicle:<?php echo $details[0]->items_inside;?>		
												</td>
											</tr>
											<tr>
						                        <td height="20"></td>
					                        </tr>
											<tr>
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													Additional Remark on Damage Report:<?php echo $details[0]->remarks;?>	
												</td>
											</tr>



										</tbody></table>
									</td>
								</tr>
							</tbody></table>
						</td>
					</tr>



						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>

			<tr>
					<td height="5"></td>
		</tr>
		<tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:20px; margin-right:20px; border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
					<tbody><tr>
						<td height="20"></td>
					</tr>

					<tr>
						<td align="center" style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#0000000;">Check Report of Damages	</td>
					</tr>

					<tr>
						<td height="10"></td>
					</tr>
				</tbody></table>
			</td>
		</tr>
		

		@foreach($photos as $prow)
		
		<tr>
			<td align="center">
				<table align="center" class="col-600" width="600" border="0" cellspacing="0" cellpadding="0">
					<tbody><tr>
						<td align="center" bgcolor="">
							<table class="col-600" width="600" align="center" border="0" cellspacing="0" cellpadding="0">
								<tbody><tr>
									<td height="33"></td>
								</tr>
								<tr>
									<td>
									     

										<table class="col1" width="183" border="0" align="left" cellpadding="0" cellspacing="0">

											<tbody><tr>
											<td height="18"></td>
											</tr>

											<tr>
												<td align="center">
													<img style="display:block; line-height:0px; font-size:0px; border:0px;" class="images_style" src="{{asset('uploads/joborder/'.$prow->photo)}}" alt="img" width="156" height="160">
														
												</td>
												



											</tr>
										</tbody></table>



										<table class="col3_one" width="380" border="0" align="right" cellpadding="0" cellspacing="0">

											<tbody>


											<tr>
												<td height="5"></td>
											</tr>


											<tr align="left" valign="top">
												<td style="font-family: 'Lato', sans-serif; font-size:14px; color:#0000000; line-height:24px; font-weight: 300;">
													"{{$prow->description}}"
												</td>
											</tr>

											<tr>
												<td height="10"></td>
											</tr>

										</tbody></table>
									
									</td>
								</tr>
								<tr>
									<td height="33"></td>
								</tr>
							</tbody></table>
						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
       @endforeach
	   
       <tr>
			<td align="center">
				<table class="col-600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:20px; margin-right:20px; border-left: 1px solid #dbd9d9; border-right: 1px solid #dbd9d9;">
					<tbody><tr>
						<td height="20"></td>
					</tr>

					<tr>
						<td align="center" style="font-family: 'Raleway', sans-serif; font-size:22px; font-weight: bold; color:#333;">	Limitation of Liability & Agreement of Service	</td>
					</tr>

					<tr>
						<td height="10"></td>
					</tr>
				</tbody></table>
			</td>
		</tr>

		<TR>

                        <TD style="HEIGHT: 450px; PADDING-BOTTOM: 10px; TEXT-ALIGN: left; PADDING-TOP: 20px; PADDING-LEFT: 20px; PADDING-RIGHT: 20px; WIDTH: 600px; BACKGROUND-COLOR: #fcfcfc" vAlign=top>1) I hereby understand and agree that Majestic Car Care is not liable for including but not limited to mechanical or electrical failure of the
vehicle upon arrival or release.<BR><BR>
2) I hereby understand and agree that Majestic Car Care cannot guarantee the removal of all contaminants, defects, scratches and vehicle damage (including but not limited to, irremovable stains or damage on carpet & seat, irreversible paint defects, improperly prepared and repainted surface, deep scratches, etc.). <BR><BR>
3) I hereby release Majestic Car Care from any liability for any damage or incidental, visible otherwise, prior to the vehicle being submitted to service. <BR><BR>
4) I hereby authorize Majestic Car Care to operate submitted vehicle for purpose of delivery or service at my risk. <BR><BR>
5) I hereby validate that I am the registered owner or have the full authority of the registered owner(s) of the vehicle being submitted to
Majestic Car Care for services. Majestic Car Care cannot be liable in the event of an un-authorized individual submits a vehicle for
service.<BR><BR>
6) I hereby authorize Majestic Car Care to perform additional services quoted to me at the shop rate including but not limited to
excessive pet hair removal, organic substance removal, off-road contamination, etc. <BR><BR>
7) I hereby agree to remit payment of any and all services agreed upon, orally or written prior to my vehicle release. I authorize Majestic
Car Care to withhold, detain, and issue a lien on my vehicle should I refuse to remit payment <BR><BR>
8) I hereby agree that Majestic Car Care cannot be held liable for any personal belonging left in the vehicle.<BR><BR>
9) I hereby release Majestic Car Care from any and all liability and claims arising out of or related to any loss, damage or injury, as a
result of services performed.<BR><BR>
10) The Headlight restoration warranty will be void if the headlights is polished, buffed, sanded, or tampered with.Any significant damage
(including but not limited to cracks, deep scratches, abuse, polishing, buffing, sanding, etc.) or a vehicle collision will immediately
void the warranty. The restoration does not protect against internal headlamp condensation. HID Haze, and internal damage, but in fact
can be repaired for an additional fee. Warranty claims can take up to 30 days upon inspection. <BR><BR>
11) The paint protection film warranty will be void if any of the following occur but not limited to improper care for the product such as
 polishing, buffing, harsh chemicals, and or tampering of the product, and also if the maintenance is not performed by our certified
technician once in every six months. Any significant damage (including but not limited to deep scratches, rock chips, vehicle collision
or abuse to the product) will void the warranty upon inspection. In the event of damage occurring, the section which was inflicted can be
replaced for an additional cost. Warranty claims can take up to 30 days. <BR><BR>

12) The warranty for the paint protection solutions is void if the customer does the following:<BR><BR>

a. Uses an Automatic Car Wash<BR>
b. Use improper washing techniques<BR>
c. Uses harsh chemicals or detergents<BR>
d. Uses rough texture rags for washing or dying<BR>
e. Polishes the vehicle<BR><BR>


13) Majestic Car Care is not liable for<BR><BR>

a. Any incidental, special consequential or punitive damages arising from any defect in any other product<BR><BR>
b. Any damages cause by outside forces other than product defect or abnormality<BR><BR>
c. Any damages caused by dents, scratches, accidents, use of harsh soaps/detergents, or use of automatic car washes, use of
damaging rags.<BR><BR>
d. Any and all other expressed or implied warranties including warranties of merchantability and fitness for a particular purposed.<BR><BR>


14) I hereby understand that if my vehicle currently has a window tint of any kind, Majestic Car Care is not liable for any discoloration,
 damage or air pockets on tint film before arrival or upon release of the vehicle.<BR><BR>

15)The self-healing coated cars should be inspected at Majestic Car Care at least once in six months otherwise the warranty will be void<BR><BR>

Sincerely,<BR><BR>
    <IMG alt=Signature src="http://proapp.majesticuae.store/uploads/signatures/<?php echo $details[0]->signature;?>" width=200  height=100>

                        </TD>

                  </TR>
			
		</body>
</html>

