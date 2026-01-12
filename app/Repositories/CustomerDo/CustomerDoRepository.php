<?php namespace App\Repositories\CustomerDo;

use App\Models\CustomerDo;
use App\Models\CustomerDoItem;
use App\Models\CustomerDoInfo;
use App\Models\ItemStock;
use App\Models\ItemLocation;
use App\Models\ConLocation;
use App\Models\ItemLocationSI;
use App\Models\ItemDescription;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Auth;
use Storage;


class CustomerDoRepository extends AbstractValidator implements CustomerDoInterface {
	
	protected $customer_do;
	protected $mod_do_qty;
	protected static $rules = [];
	public $objUtility;
	
	public function __construct(CustomerDo $customer_do) {
		$this->customer_do = $customer_do;
		$this->objUtility = new UpdateUtility();
		$this->mod_do_qty = DB::table('parameter2')->where('keyname', 'mod_do_qty_update')->where('status',1)->select('is_active')->first();
	}
	
	public function all()
	{
		return $this->customer_do->get();
	}
	
	public function find($id)
	{
		return $this->customer_do->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->customer_do->voucher_no    = $attributes['voucher_no'];
		$this->customer_do->reference_no  = $attributes['reference_no'];
		$this->customer_do->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));//date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->customer_do->lpo_date      = date('Y-m-d', strtotime($attributes['lpo_date']));
		$this->customer_do->customer_id   = $attributes['customer_id'];
		$this->customer_do->document_type = $attributes['document_type'] ?? null;
		$this->customer_do->document_id   = $attributes['document_id'] ?? 0;
		$this->customer_do->description   = $attributes['description'] ?? null;
		$this->customer_do->job_id 		  = $attributes['job_id'] ?? 0;
		$this->customer_do->terms_id 	  = $attributes['terms_id'] ?? 0;
		$this->customer_do->footer_id 	  = $attributes['footer_id'] ?? 0;
		$this->customer_do->is_fc 		  = isset($attributes['is_fc'])?1:0;
		$this->customer_do->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id'] ?? 0:'';
		$this->customer_do->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate'] ?? 0:'';
		$this->customer_do->salesman_id  = (isset($attributes['salesman_id']))?$attributes['salesman_id'] ?? 0:'';
		$this->customer_do->is_export		= isset($attributes['is_export'])?1:0;
		$this->customer_do->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description'] ?? null:'';
		
		return true;
	}
	
	private function setItemInputValue($attributes, $customerDoItem, $key, $value,$lineTotal) 
	{
		$attributes['currency_rate'] = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;//EDT
		$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
		if( isset($attributes['is_fc']) ) {
			$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			
			//$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
						
			if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
			} else {
				
				$tax        = (int)($attributes['cost'][$key] * (int)$attributes['line_vat'][$key]) / 100;
				$item_total = (int)($attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
				$tax_total  = round($tax * (int)$attributes['quantity'][$key],2);
			}
		}
		
		//********DISCOUNT Calculation.............
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		$type = 'tax_exclude';
			
		if($attributes['tax_include'][$key]==1 ) {
			$vatPlus = 100 + $attributes['line_vat'][$key];
			$total = $attributes['cost'][$key] * $attributes['quantity'][$key];
			$type = 'tax_include';
		} else {
			$vatPlus = 100;
			$total = $attributes['line_total'][$key];
			$type = 'tax_exclude';
		}
		
		if($discount > 0) {
			$discountAmt = round( (($total / $lineTotal) * $discount),2 );
			$amountTotal = $total - $discountAmt;
			$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
			//$line_total = $amountTotal;
			$tax_total = $vatLine; 
		}  
		
		$customerDoItem->customer_do_id = $this->customer_do->id;
		$customerDoItem->item_id    	= $value;
		$customerDoItem->item_name  	= $attributes['item_name'][$key];
		$customerDoItem->unit_id 		= $attributes['unit_id'][$key];
		$customerDoItem->quantity   	= $attributes['quantity'][$key];
		//$customerDoItem->balance_quantity  = $attributes['balance_quantity'][$key];
		$customerDoItem->unit_price 	= (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$customerDoItem->vat		    = $attributes['line_vat'][$key];
		$customerDoItem->vat_amount 	= $tax_total;
		$customerDoItem->discount   	= $attributes['line_discount'][$key] ?? 0;
		$customerDoItem->line_total 	= $line_total;
		$customerDoItem->tax_code 		= $tax_code;
		$customerDoItem->tax_include 	= $attributes['tax_include'][$key];
		$customerDoItem->item_total 	= $item_total;

		if(isset($attributes['conloc_id'][$key]) && $attributes['conloc_id'][$key]!='') {
			$customerDoItem->conloc_id = $attributes['conloc_id'][$key];
			$customerDoItem->conloc_qty = $attributes['conloc_qty'][$key];
		}
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'type' => $type, 'item_total' => $item_total);
	}
	
	private function setTransferStatusItem($attributes, $key, $doctype)
	{
		//if quantity partially deliverd, update pending quantity.
		if($doctype=='SQ') {
			if(isset($attributes['quote_sales_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['quote_sales_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else if($doctype=='SO') {
			if(isset($attributes['sales_order_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['sales_order_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('sales_order_item')
									->where('id', $attributes['sales_order_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('sales_order_item')
									->where('id', $attributes['sales_order_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		}
		/* if($doctype==1) {
			if(isset($attributes['quote_sales_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['quote_sales_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else if($doctype==2) {
			if(isset($attributes['sales_order_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['sales_order_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('sales_order_item')
									->where('id', $attributes['sales_order_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('sales_order_item')
									->where('id', $attributes['sales_order_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} */
	}
	
	private function setTransferStatusQuote($attributes)
	{
		//update purchase order transfer status....
		$idarr = explode(',',$attributes['document_id']);
		if($idarr) {
			foreach($idarr as $id) {
				if($attributes['document_type']=='SQ') {
					DB::table('quotation_sales')->where('id', $id)->update(['is_editable' => 1]);
					$row1 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->count();
					$row2 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('quotation_sales')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				} else if($attributes['document_type']=='SO') {
					DB::table('sales_order')->where('id', $id)->update(['is_editable' => 1]);
					$row1 = DB::table('sales_order_item')->where('sales_order_id', $id)->count();
					$row2 = DB::table('sales_order_item')->where('sales_order_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('sales_order')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				}
				/* if($attributes['document_type']==1) {
					$row1 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->count();
					$row2 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('quotation_sales')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				} else if($attributes['document_type']==2) {
					$row1 = DB::table('sales_order_item')->where('sales_order_id', $id)->count();
					$row2 = DB::table('sales_order_item')->where('sales_order_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('sales_order')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				} */
			}
		}
	}
	
	private function setSaleLog($attributes, $key, $document_id, $cost_avg, $sale_cost, $action, $item=null) 
	{
		//CHECK ITEM UNIT QUANTITY AS 0
		$irow = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])->select('cur_quantity')->first();
		if($irow->cur_quantity == 0) {
			$stocks = DB::table('item_log')->where('item_id',$attributes['item_id'][$key])
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->select('pur_cost','cur_quantity','unit_cost')
								   ->orderBy('id','DESC')->first();
								   
			$cost_avg = ($stocks->pur_cost==0)?$stocks->unit_cost:$stocks->pur_cost;
		}
		
		if($action=='add') {
			$cur_quantity = ($irow)?$irow->cur_quantity:0;		
			
			$qty     = (float) ($attributes['quantity'][$key] ?? 0); 
			$packing = (float) ($attributes['packing'][$key] ?? 1);

			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'CDO',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $qty * $packing,
							 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
							 'trtype'	  => 0,
							 //'cost_avg' => $cost_avg,
							 //'pur_cost' => $sale_cost,
							 //'sale_cost' => $sale_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							 'sale_reference' => $cur_quantity
							]);
			
		} else if($action=='update') {
				$logid = '';
				DB::table('item_log')->where('document_type','CDO')
								->where('document_id', $document_id)
								->where('item_id', $item->item_id)
								->where('unit_id', $item->unit_id)
								->update(['item_id' => $attributes['item_id'][$key],
									 'unit_id' => $attributes['unit_id'][$key],
									 'quantity'   => $attributes['quantity'][$key] * $attributes['packing'][$key],
									 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
									 'cur_quantity' => $attributes['quantity'][$key],
									 //'cost_avg' => $cost_avg,
									 //'pur_cost' => $sale_cost,
									 //'sale_cost' => $sale_cost,
									 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
								]);

			
		}
		
		return $logid;
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->first();
		if($item) {
			DB::table('item_unit')
					->where('id', $item->id)
					->update([ 'cur_quantity' => $item->cur_quantity - $attributes['quantity'][$key] ]);
			return true;		
		}
	}
	private function setInfoInputValue($attributes, $customerDoInfo, $key, $value)
	{
		$customerDoInfo->customer_do_id = $this->customer_do->id;
		$customerDoInfo->title 			=$attributes['title'][$key];
		$customerDoInfo->description 	= $attributes['desc'][$key];
		return true;
	}
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		foreach($attributes['item_id'] as $key => $value){ 
			
			$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
		}
		return $total;
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			DB::beginTransaction();
			try {

				//VOUCHER NO LOGIC.....................
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

				 // 2️⃣ Get the highest numeric part from voucher_master
				$qry = DB::table('customer_do')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
				if($dept > 0)	
					$qry->where('department_id', $dept);

				$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('CDO', $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
				try {
						if($this->setInputValue($attributes)) {
							$this->customer_do->status = 1;
							$this->customer_do->created_at = date('Y-m-d H:i:s');
							$this->customer_do->created_by = 1;
							$this->customer_do->save();
							$saved = true;
						}
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false || strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

							// 2️⃣ Get the highest numeric part from voucher_master
							$qry = DB::table('customer_do')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
							if($dept > 0)	
								$qry->where('department_id', $dept);

							$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
							
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('CDO', $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; // rethrow if different DB error
						}
					}
				}

				
				//order items insert
				if($this->customer_do->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = 0; $tax_total = 0; $total = $item_total = 0;
					
					//calculate total amount....
					$discount = (isset($attributes['discount']))?$attributes['discount']:0;
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
					
					foreach($attributes['item_id'] as $key => $value){ 
						$customerDoItem = new CustomerDoItem();
						$vat = $attributes['line_vat'][$key];
						$arrResult 		= $this->setItemInputValue($attributes, $customerDoItem, $key, $value,$total);
						//if($arrResult['line_total']) {
							$line_total			   += $arrResult['line_total'];
							$tax_total      	   += $arrResult['tax_total'];
							$taxtype				  = $arrResult['type'];
							$item_total				 += $arrResult['item_total'];
							
							$customerDoItem->status = 1;
							$inv_item = $this->customer_do->AddCustomerDoItem()->save($customerDoItem);
							
							//update item transfer status...
							$this->setTransferStatusItem($attributes, $key, $attributes['document_type']);
							
							//CHECK WHEATHER Update Quantity by CDO
							if($this->mod_do_qty->is_active==1) {
								
								$sale_cost = $this->updateItemQuantitySales($attributes, $key);
									$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
										$logid = $this->setSaleLog($attributes, $key, $this->customer_do->id, $CostAvg_log, $sale_cost, 'add' );

							}
						//}
						
						//item description section....
							/*if(isset($attributes['itemdesc'][$key])) {
								foreach($attributes['itemdesc'][$key] as $descrow) {
									//if($descrow != '') {
										$itemDescription = new ItemDescription();
										$itemDescription->invoice_type = 'DO';
										$itemDescription->item_detail_id = $inv_item->id;
										$itemDescription->description = $descrow;
										$itemDescription->status = 1;
										$itemDescription->save();
									//}
								}
							}*/
						
						
						//################ Location Stock Entry ####################
						if($this->mod_do_qty->is_active==1) {
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($qtys) {
										DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
									} else {
										$itemLocation = new ItemLocation();
										$itemLocation->location_id = $attributes['locid'][$key][$lk];
										$itemLocation->item_id = $value;
										$itemLocation->unit_id = $attributes['unit_id'][$key];
										$itemLocation->quantity = $lq;
										$itemLocation->status = 1;
										$itemLocation->save();
									}
									
									$itemLocationSI = new ItemLocationSI();
									$itemLocationSI->location_id = $attributes['locid'][$key][$lk];
									$itemLocationSI->item_id = $value;
									$itemLocationSI->unit_id = $attributes['unit_id'][$key];
									$itemLocationSI->quantity = $lq;
									$itemLocationSI->status = 1;
									$itemLocationSI->invoice_id = $inv_item->id;
									$itemLocationSI->is_do = 1;
									$itemLocationSI->logid = $logid;
									$itemLocationSI->save();
								}
							}
						}
						
						//Item default location add...
						if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
								
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
															  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
							} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $attributes['default_location'];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $attributes['quantity'][$key];
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
							$itemLocationSI = new ItemLocationSI();
							$itemLocationSI->location_id = $attributes['default_location'];
							$itemLocationSI->item_id = $value;
							$itemLocationSI->unit_id = $attributes['unit_id'][$key];
							$itemLocationSI->quantity = $attributes['quantity'][$key];
							$itemLocationSI->status = 1;
							$itemLocationSI->invoice_id = $inv_item->id;
							$itemLocationSI->is_do = 1;
							$itemLocationSI->logid = $logid;
							$itemLocationSI->save();
							
						}
						
					    }
						//################ Location Stock Entry End ####################
						

						//**************** Consignment Location *********************
						if(isset($attributes['conloc_id'][$key]) && $attributes['conloc_id'][$key]!='') {
							if(isset($attributes['conloc_qty'][$key]))
								$this->DoLocationTransfer($attributes,$key,$inv_item->id);
						}
						//***********************************************************

					}
					
					/*CHG*/
					$subtotal = (int)$line_total - (int)$discount;
					if($taxtype=='tax_include' && $attributes['discount'] == 0) {
					  
					  $net_amount = $subtotal;
					  $tax_total = ($subtotal * $vat) / (100 + $vat);
					  $subtotal = $subtotal - $tax_total;
					  
					} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
					
					   $tax_total = ($subtotal * $vat) / (100 + $vat);
					   $net_amount = $subtotal - $tax_total;
					} else 
						$net_amount = $subtotal + $tax_total;
					/*CHG*/
					
					if( isset($attributes['is_fc']) ) {
						$total_fc 	   = $line_total / $attributes['currency_rate'];
						$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
						$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
						$tax_fc 	   = $tax_total / $attributes['currency_rate'];
						$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
						$subtotal_fc	   = $subtotal / $attributes['currency_rate'];
					} else {
						$total_fc = $discount_fc = $tax_fc = $net_amount_fc = $vat_fc = $subtotal_fc = 0;
					}
					
					//update discount, total amount
					DB::table('customer_do')
								->where('id', $this->customer_do->id)
								->update([//'voucher_no' => $attributes['voucher_no'],
									     'total'    	  => $line_total,
										  'discount' 	  => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat_amount'	  => $tax_total,
										  'net_total'	  => $net_amount,
										  'total_fc' 	  => $total_fc,
										  'discount_fc'   => $discount_fc,
										  'vat_amount_fc' => $tax_fc,
										  'net_total_fc'  => $net_amount_fc,
										  'subtotal'	  => $subtotal,
										  'subtotal_fc'	  => $subtotal_fc ]);
					
					
				
				
				//customer do info insert
				if($this->customer_do->id && !empty( array_filter($attributes['title']))) {
					foreach($attributes['title'] as $key => $value) {
						$customerDoInfo 			= new CustomerDoInfo();
						if($this->setInfoInputValue($attributes, $customerDoInfo, $key, $value)) {
							$customerDoInfo->status = 1;
							$this->customer_do->customerDoInfo()->save($customerDoInfo);
						}
					}
				}
			}	
				
								
				DB::commit();
				return $this->customer_do->id;//true;
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
				return false;
			}
		}
		//throw new ValidationException('customer_do validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$this->customer_do = $this->find($id);
		$line_total = $tax_total = $line_total_new = $tax_total_new = $item_total = 0;
		
		DB::beginTransaction();
		try {
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->customer_do->id && !empty( array_filter($attributes['item_id']))) {
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						$deskey = $attributes['order_item_id'][$key];
						$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
						if( isset($attributes['is_fc']) ) {
								$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
								$itemtotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
								
							} else {
								
								$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
								
								if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
									
									$tax        = 0;
									$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
									
								} else if($attributes['tax_include'][$key]==1){
									
									$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
									$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
									$itemtotal = $ln_total - $taxtotal;
									
								} else {
									
									$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
									$itemtotal = ((int)$attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								}
							}
							
							//********DISCOUNT Calculation.............
							$discount = (isset($attributes['discount']))?$attributes['discount']:0;
							$taxtype = 'tax_exclude';
								
							if($attributes['tax_include'][$key]==1 ) {
								$vatPlus = 100 + $attributes['line_vat'][$key];
								$total = $attributes['cost'][$key] * $attributes['quantity'][$key];
								$taxtype = 'tax_include';
							} else {
								$vatPlus = 100;
								$total = $attributes['line_total'][$key];
								$taxtype = 'tax_exclude';
							}
							
							if($discount > 0) {
								$discountAmt = round( (($total / $lineTotal) * $discount),2 );
								$amountTotal = $total - $discountAmt;
								$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
								$taxtotal = $vatLine; 
							} 
							
							$tax_total += $taxtotal;
							$line_total += $linetotal;
							$item_total += $itemtotal;
							
							$vat = $attributes['line_vat'][$key];
						
							$customerDoItem = CustomerDoItem::find($attributes['order_item_id'][$key]);
							$items['item_name'] = $attributes['item_name'][$key];
							$items['item_id'] = $value;
							$items['unit_id'] = $attributes['unit_id'][$key];
							$items['quantity'] = $attributes['quantity'][$key];
							$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
							$items['vat']		 = $attributes['line_vat'][$key];
							$items['vat_amount'] = $taxtotal;
							$items['discount'] = $attributes['line_discount'][$key];
							$items['line_total'] = $linetotal;
							$items['item_total'] = $itemtotal; //CHG
							$items['tax_code'] 	= $tax_code;
							$items['tax_include'] = $attributes['tax_include'][$key];
							$items['conloc_id'] 	= (isset($attributes['conloc_id'][$key]))?$attributes['conloc_id'][$key]:''; 
							$items['conloc_qty'] 	= (isset($attributes['conloc_qty'][$key]))?$attributes['conloc_qty'][$key]:''; 
							$customerDoItem->update($items);
							
							
							//description update...
							/*if(isset($attributes['desc_id'])) {
								if(array_key_exists($deskey, $attributes['desc_id'])) {
									foreach($attributes['desc_id'][$deskey] as $k => $v) {
										if($v!='') {
											$itemDescription = ItemDescription::find($v);
											$desc['description'] = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->update($desc);
										} else {
											//new entry.........
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'DO';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->status = 1;
											$itemDescription->save();
											
										}
									}
								}
							} else {
								//new entry description.........
								if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										//if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'DO';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										//}
									}
								}
							}*/
							
							//################ Location Stock Entry ####################
							//Item Location specific add....
							$updated = false;
							if(isset($attributes['locqty'][$key])) {
								foreach($attributes['locqty'][$key] as $lk => $lq) {
									if($lq!='') {
										$updated = true;
										$edit = DB::table('item_location_si')->where('id', $attributes['editid'][$key][$lk])->first();
										$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
																	  //echo '<pre>';print_r($edit);exit;
										if($edit) {
											
											if($edit->quantity < $lq) {
												$balqty = $lq - $edit->quantity;
												DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
											} else {
												$balqty = $edit->quantity - $lq;
												DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
											}
											
										} else {
											
											$itemLocationSI = new ItemLocationSI();
											$itemLocationSI->location_id = $attributes['locid'][$key][$lk];
											$itemLocationSI->item_id = $value;
											$itemLocationSI->unit_id = $attributes['unit_id'][$key];
											$itemLocationSI->quantity = $lq;
											$itemLocationSI->status = 1;
											$itemLocationSI->invoice_id = $attributes['order_item_id'][$key];
											$itemLocationSI->is_do = 1;
											$itemLocationSI->save();
										}
										
										DB::table('item_location_si')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lq]);

									}
								}
							}
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('*')->first();
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									DB::table('item_location_si')->where('invoice_id', $attributes['order_item_id'][$key] )
																 ->where('location_id', $qtys->location_id)
																 ->where('item_id', $qtys->item_id)
																 ->where('unit_id', $qtys->unit_id)
																 ->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
								} 
								
								$itemLocationSI = new ItemLocationSI();
								$itemLocationSI->location_id = $attributes['default_location'];
								$itemLocationSI->item_id = $value;
								$itemLocationSI->unit_id = $attributes['unit_id'][$key];
								$itemLocationSI->quantity = $attributes['quantity'][$key];
								$itemLocationSI->status = 1;
								$itemLocationSI->invoice_id = $attributes['order_item_id'][$key];
								$itemLocationSI->is_do = 1;
								$itemLocationSI->save();
							}
								
							
							//**************** Consignment Location *********************
							if(isset($attributes['conloc_id'][$key]) && $attributes['conloc_id'][$key]!='') {
								
								if($attributes['conloc_qty'][$key]!=$attributes['conloc_qty_old'][$key]) {
									
									$this->DoLocationTransferUpdate($attributes,$key);
								}
							}
							//***********************************************************
							
						   //################ Location Stock Entry End ####################
							
						} else { //new entry...
							$item_total_new = $tax_total_new = $item_total_new = 0;
							if($discount > 0) 
								$total = $this->calculateTotalAmount($attributes);
							
							$vat = $attributes['line_vat'][$key];
							$customerDoItem = new CustomerDoItem();
							$arrResult 		= $this->setItemInputValue($attributes, $customerDoItem, $key, $value,$total);
							//echo '<pre>';print_r($arrResult);exit;
							if($arrResult['line_total']) {
								$line_total_new 		 += $arrResult['line_total'];
								$tax_total_new      	 += $arrResult['tax_total'];
								$item_total_new			 += $arrResult['item_total']; //CHG
								
								$line_total			     += $arrResult['line_total'];
								$tax_total      	     += $arrResult['tax_total'];
								$item_total 			 += $arrResult['item_total'];
								$taxtype				  = $arrResult['type'];
								
								$customerDoItem->status = 1;
								$itemObj = $this->customer_do->AddCustomerDoItem()->save($customerDoItem);
								
								//new entry description.........
								/*if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'DO';
											$itemDescription->item_detail_id = $itemObj->id;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}*/
							}
							
							//################ Location Stock Entry ####################
							//Item Location specific add....
							$updated = false;
							if(isset($attributes['locqty'][$key])) {
								foreach($attributes['locqty'][$key] as $lk => $lq) {
									if($lq!='') {
										$updated = true;
										$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
										if($qtys) {
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
										} else {
											$itemLocation = new ItemLocation();
											$itemLocation->location_id = $attributes['locid'][$key][$lk];
											$itemLocation->item_id = $value;
											$itemLocation->unit_id = $attributes['unit_id'][$key];
											$itemLocation->quantity = $lq;
											$itemLocation->status = 1;
											$itemLocation->save();
										}
										
										$itemLocationSI = new ItemLocationSI();
										$itemLocationSI->location_id = $attributes['locid'][$key][$lk];
										$itemLocationSI->item_id = $value;
										$itemLocationSI->unit_id = $attributes['unit_id'][$key];
										$itemLocationSI->quantity = $lq;
										$itemLocationSI->status = 1;
										$itemLocationSI->invoice_id = $inv_item->id;
										$itemLocationSI->is_do = 1;
										$itemLocationSI->save();
									}
								}
							}
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
								} else {
										$itemLocation = new ItemLocation();
										$itemLocation->location_id = $attributes['default_location'];
										$itemLocation->item_id = $value;
										$itemLocation->unit_id = $attributes['unit_id'][$key];
										$itemLocation->quantity = $attributes['quantity'][$key];
										$itemLocation->status = 1;
										$itemLocation->save();
									}
									
								$itemLocationSI = new ItemLocationSI();
								$itemLocationSI->location_id = $attributes['default_location'];
								$itemLocationSI->item_id = $value;
								$itemLocationSI->unit_id = $attributes['unit_id'][$key];
								$itemLocationSI->quantity = $attributes['quantity'][$key];
								$itemLocationSI->status = 1;
								$itemLocationSI->invoice_id = $inv_item->id;
								$itemLocationSI->is_do = 1;
								$itemLocationSI->save();
								
							}
							
							//**************** Consignment Location *********************
							if(isset($attributes['conloc_id'][$key]) && $attributes['conloc_id'][$key]!='') {
								if(isset($attributes['conloc_qty'][$key]))
									$this->DoLocationTransfer($attributes,$key,$inv_item->id);
							}
							//***********************************************************
						
						
							//################ Location Stock Entry End ####################
						}
						
					}
				}

				//DEC22   delivery info insert
				if(isset($attributes['infoid'])){
				foreach($attributes['infoid'] as $key => $value) {
					if($value=='') {
						$customerDoInfo = new CustomerDoInfo();
						if($this->setInfoInputValue($attributes, $customerDoInfo, $key, $value)) {
							$customerDoInfo->status = 1;
							$this->customer_do->customerDoInfo()->save($customerDoInfo);
						}
					} else {
						DB::table('customer_do_info')->where('id',$value)->update(['title' => $attributes['title'][$key], 'description' => $attributes['desc'][$key]]);
					}
				}
				}
				
				if($attributes['remove_item']!='')
				{
					$arrids = explode(',', $attributes['remove_item']);
					$remline_total = $remtax_total = 0;
					foreach($arrids as $row) {
						DB::table('customer_do_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						//$itm = DB::table('supplier_do_item')->where('id', $row)->first();
						
						$pirow = DB::table('item_location_si')->where('invoice_id',$row)->where('is_do',1)->get();
						DB::table('con_location')->where('invoice_id',$row)->where('is_do',1)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
						foreach($pirow as $prow) {
							DB::table('item_location_si')->where('id',$prow->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
							
							DB::table('item_location')->where('location_id', $prow->location_id)->where('item_id',$prow->item_id)->where('unit_id',$prow->unit_id)
										->update(['quantity' => DB::raw('quantity + '.$prow->quantity) ]);
										
							//REMOVE FROM CONSIGNMENT LOCATION
							DB::table('con_location')->where('invoice_id',$row)->where('is_do',1)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						}
					}
				}
				
				$this->customer_do->fill($attributes)->save();
				
				/*CHG*/
				$subtotal = $line_total - $discount;
				if($taxtype=='tax_include' && $attributes['discount'] == 0) {
				  
				  $net_amount = $subtotal;
				  $tax_total = ($subtotal * $vat) / (100 + $vat);
				  $subtotal = $subtotal - $tax_total;
				  
				} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
				
				   $tax_total = ($subtotal * $vat) / (100 + $vat);
				   $net_amount = $subtotal - $tax_total;
				} else 
					$net_amount = $subtotal + $tax_total;
				/*CHG*/
					
				if( isset($attributes['is_fc']) ) {
					$total_fc 	   = $line_total / $attributes['currency_rate'];
					$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
					$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
					$tax_fc 	   = $tax_total / $attributes['currency_rate'];
					$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
					$subtotal_fc	   = $subtotal / $attributes['currency_rate']; //CHG
				} else {
					$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $subtotal_fc = 0;
				}
				
				//update discount, total amount
				DB::table('customer_do')
							->where('id', $this->customer_do->id)
							->update([
									  'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
									  'total'    	  => $line_total,
									  'discount' 	  => $attributes['discount'],
									  'vat_amount'	  => $tax_total,
									  'net_total'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_total_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal, //CHG
									  'subtotal_fc'	  => $subtotal_fc,
									  'doc_status' 	  => (isset($attributes['doc_status']))?$attributes['doc_status']:'',
									  'comment'		  => (isset($attributes['comment']))?$attributes['comment']:((isset($attributes['comment_hd']))?$attributes['comment_hd']:''),
									  'foot_description' => (isset($attributes['foot_description']))?$attributes['foot_description']:''
									  ]); //CHG
				
			//DEC22  UPDATED REMOVE INFO...
			if(isset($attributes['remove_info']) && $attributes['remove_info']!='')
			{
				$arrids = explode(',', $attributes['remove_info']);
				
				foreach($arrids as $row) {
					DB::table('customer_do_info')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
				}
			}						  

			DB::commit();
			return true;
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
		
	}
	
	public function delete($id)
	{
		$this->customer_do = $this->customer_do->find($id);
		
		//inventory update...
		DB::beginTransaction();
		try {
			
			//Update control of QS,SO... 
			if($this->customer_do->document_id > 0) {
				if($this->customer_do->document_type=='QS') {
					DB::table('quotation_sales')->where('id', $this->customer_do->document_id)
										->update(['is_transfer' => 0]);
				} else if($this->customer_do->document_type=='SO') {
					DB::table('sales_order')->where('id', $this->customer_do->document_id)
										->update(['is_transfer' => 0, 'is_editable' => 0]);
				} 
			}
			
			$items = DB::table('customer_do_item')->where('customer_do_id', $id)->select('id','item_id','quantity','unit_id','unit_price')->get();
			
			$this->updateLastPurchaseCostAndCostAvgonDelete($items,$id);
			DB::table('customer_do_item')->where('customer_do_id', $id)
								  ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			
			foreach($items as $item) {
				$pirow = DB::table('item_location_si')->where('invoice_id',$item->id)->where('is_do',1)->get();
				
				DB::table('con_location')->where('invoice_id',$item->id)->where('is_do',1)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
				foreach($pirow as $prow) {
					DB::table('item_location_si')->where('id',$prow->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
					
					DB::table('item_location')->where('location_id', $prow->location_id)->where('item_id',$prow->item_id)->where('unit_id',$prow->unit_id)
								->update(['quantity' => DB::raw('quantity + '.$prow->quantity) ]);
					
				}
				
				//REMOVE FROM CONSIGNMENT LOCATION
				DB::table('con_location')->where('invoice_id',$item->id)->where('is_do',1)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			}
			
			$this->customer_do->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}	
	}
	
	/* public function delete($id)
	{
		$this->customer_do = $this->customer_do->find($id);
		DB::table('sales_order')->where('id',$this->customer_do->document_id)->update(['is_transfer' => 0, 'is_editable' => 0]);
		$this->customer_do->delete();
	} */
	
	public function customerDOList2()
	{
		$query = $this->customer_do->where('customer_do.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} )
					->select('customer_do.*','am.master_name AS customer')
					->orderBY('customer_do.id', 'DESC')
					->get();
	}
	
	public function customerDOListCount()
	{
		$query = $this->customer_do->where('customer_do.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} )
					->count();
	}
	
	public function customerDOList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->customer_do->where('customer_do.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} );
						
				if($search) {
					$query->where('customer_do.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('customer_do.*','am.master_name AS customer')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	public function findOrderData($id)
	{
		$query = $this->customer_do->where('customer_do.id', $id)->where('customer_do.is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} )
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','customer_do.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','customer_do.salesman_id');
					})
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','customer_do.job_id');
					})
					->select('customer_do.*','am.master_name AS customer','f.title AS footer','S.name AS salesman','J.code')
					->first();
		
	}
	
	public function getItems($id)
	{
		
		$query = $this->customer_do->where('customer_do.id',$id);
		
		return $query->join('customer_do_item AS poi', function($join) {
							$join->on('poi.customer_do_id','=','customer_do.id');
						} )
					  ->leftjoin('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->leftjoin('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					   ->leftjoin('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
					  })
					  ->where('poi.status',1)
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty')
					  ->orderBY('poi.id')
					  ->groupBy('poi.id')
					  ->get();
	}
	
	public function getDOItems($id)
	{
		
		$query = $this->customer_do->whereIn('customer_do.id',$id);
		
		return $query->join('customer_do_item AS poi', function($join) {
							$join->on('poi.customer_do_id','=','customer_do.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					   ->join('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
						  $join->on('iu.unit_id','=','poi.unit_id');
					  })
					  ->where('poi.status',1)
					  ->whereIn('poi.is_transfer',[0,2])
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.cur_quantity')
					  ->orderBY('poi.id')
					  ->groupBy('poi.id')
					  ->get();
	}
	
	public function activeCustomerDoList()
	{
		return $this->customer_do->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->customer_do->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->customer_do->where('reference_no',$refno)->count();
	}
		
	public function findCDOdata($id)
	{
		$query = $this->customer_do->where('customer_do.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} )
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','customer_do.job_id');
					})
					->select('customer_do.*','am.master_name AS customer','J.code')
					->orderBY('customer_do.id', 'ASC')
					->first();
	}
	
	
	public function getCustomerOrder($customer_id=null)
	{
		if($customer_id)
		$query = $this->customer_do->where('customer_do.status', 1)
									 ->where('customer_id', $customer_id)
									 ->where('is_transfer', 0);
		else		
		$query = $this->customer_do->where('customer_do.status', 1)
		                            ->where('is_transfer', 0); 
									
		return $query ->select('id','voucher_no','reference_no','description','voucher_date','net_total') ->get();
		
	}
	
	public function getOrderReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->customer_do->where('customer_do.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_do.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','customer_do.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','customer_do.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->customer_do->where('customer_do.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_do.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','customer_do.job_id');
								   })
								   ->select('AM.master_name AS supplier','customer_do.*','JM.name AS job')
								   ->orderBY('customer_do.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getOrder($attributes)
	{
		$order = $this->customer_do->where('customer_do.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_do.customer_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','customer_do.currency_id');
								   })
								   ->leftJoin('salesman AS SL', function($join) {
									   $join->on('SL.id','=','customer_do.salesman_id');
								   })
								   ->leftJoin('terms AS TR', function($join) {
									   $join->on('TR.id','=','customer_do.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','customer_do.*','AM.address','SL.name AS salesman',
								   'AM.city','AM.state','AM.pin','AM.vat_no','AM.phone','C.name AS currency','TR.description AS terms')
								   ->orderBY('customer_do.id', 'ASC')
								   ->first();
								   
		$items = $this->customer_do->where('customer_do.id', $attributes['document_id'])
								   ->join('customer_do_item AS PI', function($join) {
									   $join->on('PI.customer_do_id','=','customer_do.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','customer_do.id','IM.item_code','U.unit_name')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	
	public function findPOdata($id)
	{
		$query = $this->customer_do->where('customer_do.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_do.customer_id');
						} )
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','customer_do.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','customer_do.salesman_id');
					})
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','customer_do.job_id');
					})
					->select('customer_do.*','am.master_name AS customer','f.title AS footer','S.name AS salesman','am.email','J.code')
					->orderBY('customer_do.id', 'ASC')
					->first();
	}
	
	public function getPendingReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->customer_do
								->join('customer_do_item AS SOI', function($join) {
									$join->on('SOI.customer_do_id','=','customer_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_do.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_do.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','customer_do.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_do.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_do.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_do.customer_id', $attributes['customer_id']);	
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('customer_do.job_id', $attributes['job_id']);
						 
				return $query->select('customer_do.voucher_no','customer_do.reference_no','customer_do.total','customer_do.vat_amount','S.name AS salesman','J.code AS jobcode',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','customer_do.net_total','customer_do.discount')
								->groupBy('customer_do.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->customer_do->where('SOI.is_transfer','!=',1)
								->join('customer_do_item AS SOI', function($join) {
									$join->on('SOI.customer_do_id','=','customer_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_do.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_do.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','customer_do.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_do.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_do.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_do.customer_id', $attributes['customer_id']);	
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('customer_do.job_id', $attributes['job_id']);
						 
				return $query->select('customer_do.voucher_no','customer_do.reference_no','customer_do.total','customer_do.vat_amount','customer_do.discount','J.code AS jobcode',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','customer_do.net_total','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'customer_do.voucher_date')
								->get();
								
				break;
				
			case 'detail':
				$query = $this->customer_do
								->join('customer_do_item AS SOI', function($join) {
									$join->on('SOI.customer_do_id','=','customer_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_do.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SOI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_do.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','customer_do.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_do.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_do.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_do.customer_id', $attributes['customer_id']);	
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('customer_do.job_id', $attributes['job_id']);
						
				return $query->select('customer_do.voucher_no','customer_do.voucher_date','customer_do.reference_no','IM.item_code','IM.description','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'SOI.quantity','SOI.unit_price','SOI.line_total','AM.master_name','customer_do.net_total','customer_do.discount','J.code AS jobcode')
								->get();
				break;
				
			case 'detail_pending'||'qty_report':
				$query = $this->customer_do->where('QSI.is_transfer','!=',1)
								->join('customer_do_item AS QSI', function($join) {
									$join->on('QSI.customer_do_id','=','customer_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_do.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_do.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','customer_do.job_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_do.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_do.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_do.customer_id', $attributes['customer_id']);	
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('customer_do.job_id', $attributes['job_id']);
						
				return $query->select('customer_do.voucher_no','customer_do.voucher_date','customer_do.reference_no','IM.item_code','IM.description','customer_do.total','customer_do.vat_amount','customer_do.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','AM.master_name','customer_do.net_total','S.name AS salesman','J.code AS jobcode')
								->get();
				break;
		}
	}
	
	public function getItemDesc($id)
	{
		return DB::table('customer_do')
						->join('customer_do_item AS QSI', function($join) {
							$join->on('QSI.customer_do_id', '=', 'customer_do.id');
						})
						->join('item_description AS D', function($join) {
							$join->on('D.item_detail_id', '=', 'QSI.id');
						})
						->where('customer_do.id', $id)
						->where('D.invoice_type','DO')
						->where('QSI.status',1)
						->where('QSI.deleted_at','0000-00-00 00:00:00')
						->where('D.status',1)
						->where('D.deleted_at','0000-00-00 00:00:00')
						->select('D.*')
						->get();
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->customer_do->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->customer_do->where('voucher_no',$refno)->count();
	}
	
	public function updateItemQuantitySales($attributes, $key, $bquantity=null)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
										->where('is_baseqty', 1)->first();
		if($item) {
			
			$packing = isset($attributes['packing'][$key])?$attributes['packing'][$key]:1; //Storage::prepend('stolog.txt', 'bquantity: '.$bquantity);
			$qty = (is_null($bquantity))?$attributes['quantity'][$key]:$bquantity;  //Storage::prepend('stolog.txt', 'qty: '.$qty);
			//$baseqty = ($qty * $packing); 

			$packing = isset($attributes['packing'][$key])
				? (float) $attributes['packing'][$key]
				: 1;

			$qty = is_null($bquantity)
				? (float) $attributes['quantity'][$key]
				: (float) $bquantity;

			$baseqty = $qty * $packing;
			
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => DB::raw('cur_quantity - '.$baseqty),
							'issued_qty' => DB::raw('issued_qty + '.$baseqty) ]);
							
			//UPDATE into ITEM STOCK LOG 
			$stocks = DB::table('item_log')->where('item_id',$attributes['item_id'][$key])
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->where('cur_quantity', '>', 0)
								   ->orderBy('id','ASC')->get();
			//echo '<pre>';print_r($stocks);exit;					   
			$squantity = $attributes['quantity'][$key];
								   
			if(!empty($stocks)) {
				$sale_cost = $sale_qty = 0;
				foreach($stocks as $stock) {
					
					if($stock->cur_quantity > 0) { 
						
						if($stock->cur_quantity >= $squantity) {
							$cur_quantity = $stock->cur_quantity - $squantity;
							$break = true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $squantity; //FEB25
							$sale_qty += $squantity;
						
						} else if($stock->cur_quantity < $squantity) {
							$squantity = $squantity - $stock->cur_quantity;
							$cur_quantity = 0;
							$break = ($squantity > 0)?false:true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $stock->cur_quantity; //FEB25
							$sale_qty += $stock->cur_quantity;
							
						}
						
						//UPDATE ITEM STOCK LOG QUANTITY
						DB::table('item_log')->where('id', $stock->id)->update(['cur_quantity' => $cur_quantity ]);
						
						if($break)
							break;
						
					}
				}
				
				$itm_sale_cost = ($sale_qty > 0)?$sale_cost / $sale_qty:0; // $avg_cost = ($total_qty > 0)?$total_cost / $total_qty:0;
				
				return $itm_sale_cost;
				
			} else {
				
				$stocks = DB::table('item_log')->where('item_id',$attributes['item_id'][$key])
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->select('pur_cost')
								   ->orderBy('id','DESC')->first(); //echo '<pre>';print_r($stocks);exit;
				
				if($stocks)				
					return $stocks->pur_cost;
				else
					return 0;
			}
		}
		
	}
	
	public function updateLastPurchaseCostAndCostAvg($attributes, $key, $type, $other_cost=null)
	{
		
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('cur_quantity','pur_cost')
										->get(); //echo '<pre>';print_r($itmlogs);exit;
		if($type==0) {								
			$itmcost = $itmqty = 0;
		} else {
			$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
			$itmqty = $attributes['quantity'][$key];
		}
		
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
			$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		} else {
			$row = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			if($row)
				$cost_avg = $cost = $row->cost_avg;
			else
				$cost_avg = $cost = 0;
		}
		
							
		return $cost_avg;
		
	}
	
	private function updateLastPurchaseCostAndCostAvgonDelete($items, $id) {
		//UPDATE Cost avg and stock...
		foreach($items as $item) {
									
			//COST AVG Updating on DELETE section....
			DB::table('item_log')->where('document_id', $id)->where('document_type','CDO')
								 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
								 ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity + '.$item->quantity)]);
									  
		}
	}
	
	private function DoLocationTransfer($attributes,$key,$typeid) {
		
		
		//GET DEFAULT LOCATION
		if($attributes['default_location']==0) {
			$locData = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
			$fromLoc = ($locData)?$locData->id:'';
		} else
			$fromLoc = $attributes['default_location'];
		
		$locarr = explode(',',$attributes['conloc_id'][$key]);
		$qtyarr = explode(',',$attributes['conloc_qty'][$key]);

		foreach($locarr as $lk => $loc) {
			
			//GET LOCATION TRANSFER VOUCHER
			$voucher = DB::table('voucher_no')->where('status',1)->where('voucher_type','LT')->select('no')->first();
		
			$locTRid = DB::table('location_transfer')
						->insertGetId([
							'voucher_no' => $voucher->no,
							'locfrom_id' => $fromLoc,
							'locto_id'	 => $loc,
							'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'total'		=> $qtyarr[$lk],
							'status'	=> 1,
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => Auth::User()->id,
							'type'		=> 'DO',
							'typeid'	=> $typeid
						]);
			//INSERT TRANFER ITEM..			
			DB::table('location_transfer_item')
				->insertGetId([
					'location_transfer_id' => $locTRid,
					'item_id'		=> $attributes['item_id'][$key],
					'unit_id'		=> $attributes['unit_id'][$key],
					'item_name'	=> $attributes['item_name'][$key],
					'quantity'	=> $qtyarr[$lk],
					'status'	=> 1
				]);
			
			//UPDATE STOCK QUANTITY IN LOCATION			
			if($this->stockUpdate($fromLoc,$loc,$qtyarr[$lk],$attributes,$key,'from'))
				$this->stockUpdate($fromLoc,$loc,$qtyarr[$lk],$attributes,$key,'to');
			
			//INCREMENT VOUCHER NO..
			DB::table('voucher_no')->where('voucher_type', 'LT')->update(['no' =>  DB::raw('no + 1') ]);
						
		}
								
			
	}
	
	
	private function stockUpdate($fromLoc,$loc,$qty,$attributes,$key,$type)
	{
		if($type=='from') {
		
			DB::table('item_location')->where('item_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->where('location_id', $fromLoc)
									  ->update(['quantity' => DB::raw('quantity - '.$qty)]);
		} else {
			
			DB::table('item_location')->where('item_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->where('location_id', $loc)
									  ->update(['quantity' => DB::raw('quantity + '.$qty)]);
		}
		
		return true;
	}
	
	
	private function DoLocationTransferUpdate($attributes,$key) { 
		
		//GET DEFAULT LOCATION
		if($attributes['default_location']==0) {
			$locData = DB::table('location')->where('is_default',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
			$fromLoc = ($locData)?$locData->id:'';
		} else
			$fromLoc = $attributes['default_location'];
		
		$locarr_old = explode(',',$attributes['conloc_id_old'][$key]);
		$locarr = explode(',',$attributes['conloc_id'][$key]);
		$qtyarr = explode(',',$attributes['conloc_qty'][$key]);
		$qtyarr_old = explode(',',$attributes['conloc_qty_old'][$key]);  
		//print_r($locarr_old);echo 'hi';exit;
		//SET DELETE AS OLD LOCATION TRANSFER...
		foreach($locarr_old as $lk => $loc) { Storage::prepend('dolog.txt', 'old: '.$loc);
			$locdataold = DB::table('location_transfer')//->where('type','DO')
					->where('locto_id', $loc)
					->where('typeid', $attributes['order_item_id'][$key])
					->where('status',1)->where('deleyed_at','0000-00-00 00:00:00')->select('id')->first();
					
			if($locdataold) { Storage::prepend('dolog.txt', 'old data: ');
				DB::table('location_transfer')->where('id',$locdataold->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
				DB::table('location_transfer_item')->where('location_transfer_id',$locdataold->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
				
				//UPDATE STOCK QUANTITY IN LOCATION			
				if($this->stockUpdate($fromLoc,$loc,$qtyarr_old[$lk],$attributes,$key,'from'))
					$this->stockUpdate($fromLoc,$loc,$qtyarr_old[$lk],$attributes,$key,'to');
			}
		}
		
		//UPDATE WITH NEW LOCATION TRANSFER DATA...
		foreach($locarr as $lk => $loc) {  Storage::prepend('dolog.txt', 'new: ');
			
			$locdata_new = DB::table('location_transfer')->where('type','DO')
					->where('locto_id', $loc)
					->where('typeid', $attributes['order_item_id'][$key])
					->where('status',1)->where('deleyed_at','0000-00-00 00:00:00')->select('id')->first();
					
			if($locdata_new) { //Storage::prepend('dolog.txt', 'new data: ');
				DB::table('location_transfer')->where('id',$locdata_new->id)
						->update([
							'locto_id' => $loc,
							'total'		=> $qtyarr[$lk]
						]);
						
				DB::table('location_transfer_item')->where('location_transfer_id',$locdata_new->id)
						->update([
							'item_id'		=> $attributes['item_id'][$key],
							'unit_id'		=> $attributes['unit_id'][$key],
							'item_name'	=> $attributes['item_name'][$key],
							'quantity'	=> $qtyarr[$lk]
						]);
						
				//UPDATE STOCK QUANTITY IN LOCATION			
				if($this->stockUpdate($fromLoc,$loc,$qtyarr[$lk],$attributes,$key,'from'))
					$this->stockUpdate($fromLoc,$loc,$qtyarr[$lk],$attributes,$key,'to');
			}
					
		}
		
		
	}
	
	
}

