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
										<tr>
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
										</tr>
										
										@endforeach
										<?php $total = $details->total;
												$vat_amount_net = $details->vat_amount;
												$net_total = $details->net_total; ?>
									</table>
									
									
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