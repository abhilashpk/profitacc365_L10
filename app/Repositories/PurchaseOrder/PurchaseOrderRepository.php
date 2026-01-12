<?php namespace App\Repositories\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderInfo;
use App\Repositories\AbstractValidator;
use App\Models\PurchaseOrderOtherCost;
use App\Models\ItemDescription;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Auth;


class PurchaseOrderRepository extends AbstractValidator implements PurchaseOrderInterface {
	
	protected $purchase_order;
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(PurchaseOrder $purchase_order) {
		$this->purchase_order = $purchase_order;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->purchase_order->get();
	}
	
	public function find($id)
	{
		return $this->purchase_order->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->purchase_order->voucher_no = $attributes['voucher_no'];
		$this->purchase_order->reference_no = $attributes['reference_no'] ?? null;
		$this->purchase_order->description = $attributes['description'] ?? null;
		$this->purchase_order->terms_id = $attributes['terms_id'] ?? 0;
		$this->purchase_order->job_id = $attributes['job_id'] ?? 0;
		$this->purchase_order->supplier_id = $attributes['supplier_id'];
		$this->purchase_order->header_id = isset($attributes['header_id'])?$attributes['header_id'] ?? 0:'';
		$this->purchase_order->footer_id = isset($attributes['footer_id'])?$attributes['footer_id'] ?? 0:'';
		$this->purchase_order->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->purchase_order->lpo_date = ($attributes['lpo_date']!='')?date('Y-m-d', strtotime($attributes['lpo_date'])):'';
		$this->purchase_order->is_fc = isset($attributes['is_fc'])?1:0;
		$this->purchase_order->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id'] ?? 0:'';
		$this->purchase_order->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate'] ?? 0:'';
		$this->purchase_order->is_import = isset($attributes['is_import'])?1:0;
		$this->purchase_order->location_id = isset($attributes['location_id'])?$attributes['location_id'] ?? 0:$attributes['location_id'] ?? 0;
		$this->purchase_order->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description'] ?? null:'';
		$this->purchase_order->document_type = (isset($attributes['document_type']))?$attributes['document_type']:'PO';
		$this->purchase_order->document_id = ($attributes['document_id']!='')?$attributes['document_id'] ?? 0:'';
		$this->purchase_order->is_draft = (isset($attributes['is_draft']))?$attributes['is_draft'] ?? 0:'';
		
		return true;
	}
	
	private function setItemInputValue($attributes, $purchaseOrderItem, $key, $value,$other_cost, $lineTotal, $total_quantity=null) 
	{
		$othercost_unit = $netcost_unit = 0;
		if( isset($attributes['is_fc']) ) {
			$currencyrate=isset($attributes['currency_rate'])?$attributes['currency_rate']:'';
			$tax        = (float)( ($attributes['cost'][$key] * (float)$attributes['line_vat'][$key]) / 100) * (float)$currencyrate;
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key] ) * (float)$currencyrate;
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * (float)$currencyrate;
			
			if( isset($attributes['other_cost'])) {
				$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total']; //$attributes['other_cost']
				$netcost_unit = ($othercost_unit + $attributes['cost'][$key]) * $attributes['currency_rate'];
			}
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
			
			if($tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;

				if( isset($attributes['other_cost'])) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
				
				/*if( isset($attributes['other_cost'])) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}*/
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
				/*if( isset($attributes['other_cost']) && $other_cost > 0 ) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}*/
				if( is_numeric (isset($attributes['other_cost']) )&& is_numeric ($other_cost > 0) ) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
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
		$currencyrate=isset($attributes['currency_rate'])?(float)$attributes['currency_rate']:'';		
		$purchaseOrderItem->purchase_order_id = $this->purchase_order->id;
		$purchaseOrderItem->item_id = $attributes['item_id'][$key];
		$purchaseOrderItem->unit_id = $attributes['unit_id'][$key];
		$purchaseOrderItem->item_name = $attributes['item_name'][$key];
		$purchaseOrderItem->quantity = $attributes['quantity'][$key] ?? 0;
		$purchaseOrderItem->unit_price = (isset($attributes['is_fc']))?(float)$attributes['cost'][$key]*(float)$currencyrate:(float)$attributes['cost'][$key];
		$purchaseOrderItem->vat = $attributes['line_vat'][$key] ?? 0;
		$purchaseOrderItem->vat_amount = $tax_total;
		$purchaseOrderItem->discount = (int)$attributes['line_discount'][$key] ?? 0;
		$purchaseOrderItem->total_price = $line_total;
		$purchaseOrderItem->othercost_unit = $othercost_unit;
		$purchaseOrderItem->netcost_unit = $netcost_unit;
		$purchaseOrderItem->tax_code 	= $tax_code;
		$purchaseOrderItem->tax_include = $attributes['tax_include'][$key] ?? 0;
		$purchaseOrderItem->item_total 		= $item_total;

		$purchaseOrderItem->unit_price_fc = $attributes['cost'][$key];
		$purchaseOrderItem->vat_amount_fc = (isset($attributes['is_fc']))?round(((float)$tax_total /$currencyrate),2):(float)$tax_total;
		$purchaseOrderItem->total_price_fc = (isset($attributes['is_fc']))?round(($line_total /  $currencyrate),2):$line_total;
		$purchaseOrderItem->item_total_fc = (isset($attributes['is_fc']))?round(($item_total /  $currencyrate),2):$item_total;
		
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total,'othercost_unit' => $othercost_unit, 'type' => $type, 'item_total' => $item_total);
		
	}
	
	
	private function setTransferStatusItem($attributes, $key)
	{ 
		if(isset($attributes['document_type']) && $attributes['document_type']=='SO')  {
		    
		    if(isset($attributes['purchase_order_item_id'])) {
    			if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
    				if( isset($attributes['purchase_order_item_id'][$key]) ) {
    					$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
    					//update as partially delivered.
    					DB::table('sales_order_item')
    								->where('id', $attributes['purchase_order_item_id'][$key])
    								->update(['balance_quantity_po' => $quantity, 'is_transfer_po' => 2]);
    				}
    			} else { 
    					//update as completely delivered.
    					DB::table('sales_order_item')
    								->where('id', $attributes['purchase_order_item_id'][$key])
    								->update(['balance_quantity_po' => 0, 'is_transfer_po' => 1]);
    			}
    		}

    		
		} else {
		
    		if(isset($attributes['purchase_order_item_id'])) {
    			if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
    				if( isset($attributes['purchase_order_item_id'][$key]) ) {
    					$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
    					//update as partially delivered.
    					DB::table('material_requisition_item')
    								->where('id', $attributes['purchase_order_item_id'][$key])
    								->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
    				}
    			} else { 
    					//update as completely delivered.
    					DB::table('material_requisition_item')
    								->where('id', $attributes['purchase_order_item_id'][$key])
    								->update(['balance_quantity' => 0, 'is_transfer' => 1]);
    			}
    		}
		}
	}
	
	
	private function updateTransferStatus($attributes)
	{
		$ids = explode(',', $attributes['document_id']);
		if(isset($attributes['document_type']) && $attributes['document_type']=='SO')  {
		    foreach($ids as $id) {
    			$count1 = DB::table('sales_order_item')->where('sales_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
    			$count2 = DB::table('sales_order_item')->where('sales_order_id',$id)->where('is_transfer_po',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
    			if($count1 == $count2)
    				DB::table('sales_order')->where('id', $id)->update(['is_transfer_po' => 1]);
    		}
		} else {
    		foreach($ids as $id) {
    			$count1 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
    			$count2 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('is_transfer',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
    			if($count1 == $count2)
    				DB::table('material_requisition')->where('id', $id)->update(['is_transfer' => 1]);
    		} 
		}
	}
	
	private function setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)
	{
		$purchaseOrderInfo->purchase_order_id = $this->purchase_order->id;
		$purchaseOrderInfo->title = $value;
		$purchaseOrderInfo->description = $attributes['desc'][$key];
		
		return true;
	}
	
	private function getOtherCostSum($ocamount, $vatoc)
	{ 
		//print_r($ocamount);exit;
		$amount = 0;
		foreach($ocamount as $k => $val) {
			if($val!='') {
				$perc = (array_key_exists($k, $vatoc))?$vatoc[$k]:0;
				$vat = $val * $perc / 100;
				$amount += $val + $vat;
			}
		}
		return $amount;
		
	}
	
	private function getCostSum($ocamount)
	{
		return array_sum(array_map( function($amount) {
						return $amount;
					}, $ocamount) );
	}
	
	private function getTotalQuantity($attributes)
	{
		return array_sum(array_map( function($var) {
					return $var;
					}, $attributes) );
	}
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		foreach($attributes['item_id'] as $key => $value){ 
			
			$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
		}
		return $total;
	}
	private function setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key)
	{
		$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
		$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
		
		$purchaseInvoiceOC->purchase_order_id = $this->purchase_order->id;
		$purchaseInvoiceOC->dr_account_id = $attributes['dr_acnt_id'][$key];
		$purchaseInvoiceOC->oc_reference = $attributes['oc_reference'][$key];
		$purchaseInvoiceOC->oc_description = $attributes['oc_description'][$key];
		$purchaseInvoiceOC->cr_account_id = $attributes['cr_acnt_id'][$key];
		$purchaseInvoiceOC->oc_amount = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]):$attributes['oc_amount'][$key];
		$purchaseInvoiceOC->oc_fc_amount = $attributes['oc_amount'][$key];
		$purchaseInvoiceOC->oc_vat = $attributes['vat_oc'][$key];
		
		if($attributes['tax_sr'][$key]=="EX" || $attributes['tax_sr'][$key]=="ZR") {
			$oc_vatamt = $ocvatamount = 0;
		} else {
			$ocvatamount = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
			$oc_vatamt = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]*$attributes['vat_oc'][$key]/100):($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
		}
		
		$purchaseInvoiceOC->oc_vatamt = $oc_vatamt;
		$purchaseInvoiceOC->is_fc = $is_fc;
		$purchaseInvoiceOC->currency_id = $attributes['oc_currency'][$key];
		$purchaseInvoiceOC->currency_rate = $attributes['oc_rate'][$key];
		$purchaseInvoiceOC->tax_code = $attributes['tax_sr'][$key];
		
		return array('oc_vat_amt' => $ocvatamount, 'oc_amount' => $attributes['oc_amount'][$key]);
	}
	
	public function create($attributes)
	{  //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
		  DB::beginTransaction();
		  try {
				
				//VOUCHER NO LOGIC.....................
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

				 // 2️⃣ Get the highest numeric part from voucher_master
				$qry = DB::table('purchase_order')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
				if($dept > 0)	
					$qry->where('department_id', $dept);

				$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('PO', $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
				try {
						if($this->setInputValue($attributes)) {
							$this->purchase_order->status = 1;
							$this->purchase_order->created_at = date('Y-m-d H:i:s');
							$this->purchase_order->created_by = 1;
							//$this->purchase_order->fill($attributes)->save();
							$this->purchase_order->save();
							$saved = true;
						}
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false || strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

							// 2️⃣ Get the highest numeric part from voucher_master
							$qry = DB::table('purchase_order')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
							if($dept > 0)	
								$qry->where('department_id', $dept);

							$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
							
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('PO', $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; // rethrow if different DB error
						}
					}
				}
			  //echo $attributes['voucher_no'];exit;
				//order items insert
				if($this->purchase_order->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = 0; $tax_total = 0; $other_cost = 0; $total_quantity = 0; $total = 0; $cost_sum = $item_total = 0;
					
					if(isset($attributes['other_cost'])) {
						$other_cost = $this->getOtherCostSum($attributes['oc_amount'],$attributes['vat_oc']);
						$total_quantity = $this->getTotalQuantity($attributes['quantity']);
						$cost_sum = $this->getCostSum($attributes['oc_amount']);
					}
					
					//calculate total amount....
					$discount = (isset($attributes['discount']))?(($attributes['discount']!='')?$attributes['discount']:0):0;
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
					
					foreach($attributes['item_id'] as $key => $value){ 
						$purchaseOrderItem = new PurchaseOrderItem();
						$vat = $attributes['line_vat'][$key];
						$arrResult 		= $this->setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $cost_sum, $total, $total_quantity);
						if($arrResult['line_total']) {
							$line_total			   += $arrResult['line_total'];
							$tax_total      	   += $arrResult['tax_total'];
							$othercost_unit      	= $arrResult['othercost_unit'];
							$taxtype				= isset($arrResult['type'])?$arrResult['type']:'';
							$item_total			   += $arrResult['item_total'];
						
							$purchaseOrderItem->status = 1;
							$itemObj = $this->purchase_order->orderItem()->save($purchaseOrderItem);
							
							//update item transfer status...
							$this->setTransferStatusItem($attributes, $key);
							
							//item description section....
							if(isset($attributes['itemdesc'][$key])) {
								foreach($attributes['itemdesc'][$key] as $descrow) {
									if($descrow != '') {
										$itemDescription = new ItemDescription();
										$itemDescription->invoice_type = 'PO';
										$itemDescription->item_detail_id = $itemObj->id;
										$itemDescription->description = $descrow;
										$itemDescription->status = 1;
										$itemDescription->save();
									}
								}
							}
						}
					}
					
					//other cost action...
					if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
						foreach($attributes['dr_acnt'] as $key => $value){ 
							$purchaseInvoiceOC = new PurchaseOrderOtherCost();
							$arrOC =$this->setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key);
								$purchaseInvoiceOC->status = 1;
								$objOC=$this->purchase_order->doOtherCost()->save($purchaseInvoiceOC);
								if($objOC) {
								$oc_vat_amt = $arrOC['oc_vat_amt'];
							$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
								//$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key]) / 100;
								//$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
								}
								//set account Cr/Dr amount transaction....
							/*	if($this->setAccountTransaction($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key)) {
									if( $this->setAccountTransaction($attributes, $attributes['oc_amount'][$key], $this->purchase_invoice->id, $type='Dr', 'OC', $key) )
										$this->setAccountTransaction($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key);
								}*/
							
						}
					}
				
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
					
					if( isset($attributes['is_fc']) ) {
						
						$total_fc 	   = $line_total / $attributes['currency_rate'];
						$discount_fc   = $attributes['discount'] ;
						$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
						$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
						$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
						$other_cost_fc = $other_cost / $attributes['currency_rate'];
						$subtotal_fc   = $subtotal / $attributes['currency_rate'];
						$discount      = $attributes['discount'] * $attributes['currency_rate'];
					} else {
						$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0; $other_cost_fc = $subtotal_fc = 0;
						$discount      = (isset($attributes['discount']))?$attributes['discount']:0;
					}
					  
										
					//update discount, total amount
                     DB::table('purchase_order')
								->where('id', $this->purchase_order->id)
								->update([//'voucher_no' => (isset($attributes['is_draft']) && $attributes['is_draft']==1 )?'Draft-'.$attributes['voucher_no']:$attributes['voucher_no'],
									    'total'    	  => $line_total,
										  'discount' 	  => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat_amount'	  => $tax_total,
										  'net_amount'	  => $net_amount,
										  'total_fc' 	  => $total_fc,
										  'discount_fc'   => $discount_fc,
										  'vat_amount_fc' => $tax_fc,
										  'net_amount_fc'  => $net_amount_fc,
										  'other_cost'	  => $other_cost,
										  'other_cost_fc' => $other_cost_fc,
										  'subtotal'	  => $subtotal,
										  'subtotal_fc'	  => $subtotal_fc ]); 
										  
					$this->updateTransferStatus($attributes);
				}
				
				//order info insert
				if($this->purchase_order->id && !empty( array_filter($attributes['title'])) ) {
					foreach($attributes['title'] as $key => $value) {
						$purchaseOrderInfo = new PurchaseOrderInfo();
						if($this->setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)) {
							$purchaseOrderInfo->status = 1;
							$this->purchase_order->orderInfo()->save($purchaseOrderInfo);
						}
					}
				}
				
				DB::commit();
				return true;
				
		  } catch(\Exception $e) {
				
				DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
				return false;
		  }
		  
		}
		
	}
	
	public function update($id, $attributes)
	
	{
	    
	   // echo '<pre>';print_r($attributes);exit;
		$this->purchase_order = $this->find($id);
		$line_total = $tax_total = 0; $cost_value = 0; $other_cost = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = 0;
		$item_total = $othercost_unit = $netcost_unit = 0;
		
		DB::beginTransaction();
		try {
		    
		    
		    //draft
				if(isset($attributes['is_draft']) && $attributes['is_draft']==0) {
				    $voucherno = explode('-',$attributes['voucher_no']);
				    //echo '<pre>';print_r($voucherno[1]);exit;
				    $attributes['voucher_no']=$voucherno[1];
				   
					}
				
				//end
				
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->purchase_order->id && !empty( array_filter($attributes['item_id']))) { 
			
				$line_total = $tax_total = 0; $cost_value = 0; $other_cost = 0; $total_quantity = $line_total_new = $tax_total_new = 0;
				$item_total = $othercost_unit = $netcost_unit = 0; $othercostunit = 0; $netcostunit = 0;
				
				if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					$other_cost = $this->getOtherCostSum($attributes['oc_fc_amount'],$attributes['vat_oc']);
					$total_quantity = $this->getTotalQuantity($attributes['quantity']);
					$cost_sum = $this->getCostSum($attributes['oc_fc_amount']);
				}
				
				//calculate total amount....
				$discount = (isset($attributes['discount']))?$attributes['discount']:0;
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
				
				foreach($attributes['item_id'] as $key => $value) { 
					$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
				
					if($attributes['order_item_id'][$key]!='') { 
						$deskey = $attributes['order_item_id'][$key];
						$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
							
						if( isset($attributes['is_fc']) ) {
							$lndiscount = ((int)$attributes['line_discount'][$key]!='')?(int)$attributes['line_discount'][$key]:0;
							$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
							$itemtotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $lndiscount ) * $attributes['currency_rate'];
							$taxtotal  = round($tax * $attributes['quantity'][$key],2);
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
							
							if(isset($attributes['other_cost']) && $attributes['other_cost'] != 0 ) {
								$othercostunit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total'];
								$netcostunit = ($othercost_unit + $attributes['cost'][$key]) * $attributes['currency_rate'];
							}

						} else {
							
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
							
							if($tax_code=="EX" || $tax_code=="ZR") {
								$lndiscount = ((int)$attributes['line_discount'][$key]!='')?(int)$attributes['line_discount'][$key]:0;
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $lndiscount;
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								
								if(isset($attributes['other_cost']) && $attributes['other_cost'] != 0 ) {
									$othercostunit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercost_unit + $attributes['cost'][$key];
								}
								
							} else if($attributes['tax_include'][$key]==1){
				                   
								$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
								$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
								$itemtotal = $ln_total - $tax_total;

								$othercostunit = 0;
								if( isset($attributes['other_cost'])) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
								
								/*if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercost_unit + $attributes['cost'][$key];
								}*/
								
							} else {
							    
								$lndiscount = ((int)$attributes['line_discount'][$key]!='')?(int)$attributes['line_discount'][$key]:0;
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $lndiscount;
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								
								$othercostunit = 0;
								if( isset($attributes['other_cost']) && $other_cost > 0 ) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
								/*if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercost_unit + $attributes['cost'][$key];
								}*/
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
							//$line_total = $amountTotal;
							$taxtotal = $vatLine; 
						} 
						
						$tax_total += $taxtotal;
						$line_total += $linetotal;
						$item_total += $itemtotal;
						$othercost_unit += $othercostunit;
						$netcost_unit += $netcost_unit;
						
						$vat = $attributes['line_vat'][$key];
						
						$purchaseOrderItem = PurchaseOrderItem::find($attributes['order_item_id'][$key]);
						//echo '<pre>';print_r($tax_code);exit;
						
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key]; //$attributes['cost'][$key];
						$items['vat']		 = $attributes['line_vat'][$key];
						$items['vat_amount'] = $taxtotal;
						$items['discount'] = (float)$attributes['line_discount'][$key];
						$items['total_price'] = $linetotal;
						$items['tax_code'] 	= $tax_code; 
						
						$items['tax_include'] = $attributes['tax_include'][$key];
						$items['item_total'] = $itemtotal;
						$items['othercost_unit'] = $othercostunit;
						$items['netcost_unit'] = ($netcostunit==0)?$attributes['cost'][$key]:$netcostunit;

						$items['unit_price_fc'] = $attributes['cost'][$key];
						$items['vat_amount_fc'] = (isset($attributes['is_fc']))?round(($taxtotal / $attributes['currency_rate']),2):$taxtotal;
						$items['total_price_fc'] = (isset($attributes['is_fc']))?round(($linetotal / $attributes['currency_rate']),2):$linetotal;
						$items['item_total_fc'] = (isset($attributes['is_fc']))?round(($itemtotal / $attributes['currency_rate']),2):$itemtotal;
						//echo '<pre>';print_r($items['tax_code']);exit;
						$purchaseOrderItem->update($items);
						
						//description update...
							if(isset($attributes['desc_id'])) {
								if(array_key_exists($deskey, $attributes['desc_id'])) {
									foreach($attributes['desc_id'][$deskey] as $k => $v) {
										if($v!='') {
											$itemDescription = ItemDescription::find($v);
											$desc['description'] = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->update($desc);
										} else {
											//new entry.........
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'PO';
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
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'PO';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}
							}
						
					} else { //new entry...
					
						$item_total_new = $tax_total_new = $item_total_new = 0;
						if($discount > 0) 
							$total = $this->calculateTotalAmount($attributes);
						
						$vat = $attributes['line_vat'][$key];
						
						$purchaseOrderItem = new PurchaseOrderItem();
						$arrResult 		= $this->setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $cost_sum, $total, $total_quantity);
						if($arrResult['line_total']) {
							$line_total_new			     += $arrResult['line_total'];
							$tax_total_new      	     += $arrResult['tax_total'];
							$othercost_unit_new      	= $arrResult['othercost_unit'];
							$taxtype_new				= $arrResult['type'];
							$item_total_new			 += $arrResult['item_total'];
							
							$line_total			     += $arrResult['line_total'];
							$tax_total      	     += $arrResult['tax_total'];
							$item_total 			 += $arrResult['item_total'];
							$taxtype				  = $arrResult['type'];
							$othercost_unit      	= $arrResult['othercost_unit'];
							$purchaseOrderItem->status = 1;
							$itemObj = $this->purchase_order->orderItem()->save($purchaseOrderItem);
							
							//new entry description.........
								if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'PO';
											$itemDescription->item_detail_id = $itemObj->id;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}
						}
					}
					
				}
			}
			
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('purchase_order_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d h:i:s')]);
				}
			}
			$this->purchase_order->fill($attributes)->save();
			
			//other cost action...
			if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
				
				foreach($attributes['dr_acnt'] as $key => $value){ 
				
					if($attributes['oc_id'][$key]!='') {
					$purchaseInvoiceOC = PurchaseOrderOtherCost::find($attributes['oc_id'][$key]);
					$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
					$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
					$cost['dr_account_id'] = $attributes['dr_acnt_id'][$key];
					$cost['oc_reference'] = $attributes['oc_reference'][$key];
					$cost['oc_description'] = $attributes['oc_description'][$key];
					$cost['cr_account_id'] = $attributes['cr_acnt_id'][$key];
					$cost['oc_amount']		 = $attributes['oc_amount'][$key];
					$cost['oc_fc_amount'] = $attributes['oc_fc_amount'][$key];
					$cost['oc_vat'] = $attributes['vat_oc'][$key];

					if($attributes['tax_sr'][$key]=="EX" || $attributes['tax_sr'][$key]=="ZR") {
						$oc_vatamt = $oc_vat_amt = 0;
					} else {
						$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
						$oc_vatamt = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]*$attributes['vat_oc'][$key]/100):($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
					}
					
					$cost['oc_vatamt'] = $oc_vatamt;
					$cost['is_fc'] = $is_fc;
					$cost['currency_id'] = $attributes['oc_currency'][$key];
					$cost['currency_rate'] = $attributes['oc_rate'][$key];
					$cost['tax_code'] = $attributes['tax_sr'][$key];
					$purchaseInvoiceOC->update($cost);
						
					//$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key]) / 100;
					//$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
					$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
					$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$attributes['oc_amount'][$key]:($oc_vat_amt + $attributes['oc_amount'][$key]);

					
					//set account Cr/Dr amount transaction....
					if($this->setAccountTransactionUpdate($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key)) {
						if( $this->setAccountTransactionUpdate($attributes, $attributes['oc_amount'][$key], $this->purchase_invoice->id, $type='Dr', 'OC', $key) )
							$this->setAccountTransactionUpdate($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key);
					}
				}else{
					$purchaseInvoiceOC = new PurchaseOrderOtherCost();
							$arrOC =$this->setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key);
								$purchaseInvoiceOC->status = 1;
								$objOC=$this->purchase_order->doOtherCost()->save($purchaseInvoiceOC);
								if($objOC) {
								$oc_vat_amt = $arrOC['oc_vat_amt'];
							$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
								}
				}

				}
			}
			
			$subtotal = (float)$line_total - (float)$discount;
			if($taxtype=='tax_include' && $attributes['discount'] == 0) {
			  
			  $net_amount = $subtotal;
			  $tax_total = ($subtotal * $vat) / (100 + $vat);
			  $subtotal = $subtotal - $tax_total;
			  
			} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
			
			   $tax_total = ($subtotal * $vat) / (100 + $vat);
			   $net_amount = $subtotal - $tax_total;
			} else 
				$net_amount = $subtotal + $tax_total;
			
			
			if( isset($attributes['is_fc']) ) {
				$total_fc 	   = $line_total / $attributes['currency_rate'];
				$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
				$subtotal_fc	   = $subtotal / $attributes['currency_rate']; 
				$other_cost_fc = $other_cost / $attributes['currency_rate'];
				
			} else {
				$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $other_cost_fc = $subtotal_fc = 0;
			}
			
			//update discount, total amount
			DB::table('purchase_order')
						->where('id', $this->purchase_order->id)
						->update(['total'    	  => $line_total,
							      'voucher_date'  => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),//sachu
							      'lpo_date'      =>($attributes['lpo_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['lpo_date'])),
							       'foot_description' =>  (isset($attributes['foot_description']))?$attributes['foot_description']:'',
								  'discount' 	  => $attributes['discount'],
								  'vat_amount'	  => $tax_total,
								  'net_amount'	  => $net_amount,
								   'is_import'    =>isset($attributes['is_import'])?1:0,
								  'total_fc' 	  => $total_fc,
								  'discount_fc'   => $discount_fc,
								  'other_cost'	  => $other_cost,
								  'other_cost_fc' => $other_cost_fc,
								  'vat_amount_fc' => $tax_fc,
								  'net_amount_fc'  => $net_amount_fc,
								  'subtotal'	  => $subtotal, //CHG
								  'subtotal_fc'	  => $subtotal_fc,//CHG
								  'is_draft'	  => isset($attributes['is_draft'])?$attributes['is_draft']:''
								  
								  ]); 
			
			DB::commit();
			return true;
			
		 } catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' - '.$e->getMessage();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->purchase_order = $this->purchase_order->find($id);
			if($this->purchase_order->document_id > 0) {
			    $ids = explode(',', $this->purchase_order->document_id);
					DB::table('material_requisition')->whereIn('id', $ids)
										->update(['is_transfer' => 0]);
										
					DB::table('material_requisition_item')->whereIn('material_requisition_id', $ids)
										->update(['is_transfer' => 0,'is_editable' => 0]);
					
			}
			
		DB::table('purchase_order')->where('id', $id)->update(['deleted_by' => Auth::User()->id]);
		$this->purchase_order->delete($id);
	}
	
	public function check_order($id)
	{
		$count = DB::table('purchase_order')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else
			return true;
	}
	
		public function getapprovalList()
	{
		return $this->purchase_order
					 ->Join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_order.supplier_id');
						} )
					 ->where('purchase_order.status', 1)
					 ->where('purchase_order.approval_status', 0)
					 ->select('purchase_order.id','purchase_order.voucher_no','purchase_order.voucher_date','purchase_order.net_amount','AM.master_name')
					 ->get();
		
	}
	
	public function purchaseOrderList1()
	{
		$query = $this->purchase_order->where('purchase_order.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} )
					->select('purchase_order.*','am.master_name AS supplier')
					->orderBY('purchase_order.id', 'DESC')
					->get();
	}
	
	public function getPOdata($supplier_id = null)
	{
		if($supplier_id)
			$query = $this->purchase_order->where('purchase_order.status',1)->where('purchase_order.is_transfer',0)->where('purchase_order.is_settled',0)->where('purchase_order.supplier_id',$supplier_id);
		else
			$query = $this->purchase_order->where('purchase_order.status',1)->where('purchase_order.is_transfer',0)->where('purchase_order.is_settled',0);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} )
					->select('purchase_order.*','am.master_name AS supplier')
					->orderBY('purchase_order.id', 'ASC')
					->get();
	}

	public function findPOdata($id)
	{
		//echo '<pre>';print_r($id);exit;
		$query = $this->purchase_order->where('purchase_order.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','purchase_order.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','purchase_order.footer_id');
					})
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','purchase_order.job_id');
					})
					->select('purchase_order.*','am.master_name AS supplier','h.title AS header','f.title AS footer','J.code')
					->orderBY('purchase_order.id', 'ASC')
					->first();
	}
	
	public function activePurchaseOrderList()
	{
		return $this->purchase_order->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_order->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->purchase_order->where('reference_no',$refno)->count();
	}
		
	public function getPOitems($id)
	{
		/*$query = $this->purchase_order->whereIn('purchase_order.id',$id);
		
		return $query->join('purchase_order_item AS poi', function($join) {
							$join->on('poi.purchase_order_id','=','purchase_order.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					   ->join('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
					  })
					  ->where('poi.status',1)
					  ->whereIn('poi.is_transfer',[0,2])
					  ->where('poi.deleted_at', '0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','iu.pkno')
					  ->orderBY('poi.id')
					  ->groupBy('poi.id')
					  ->get();*/
					  
					  
		$query = $this->purchase_order->where('purchase_order.id',$id);
		
		return $query->join('purchase_order_item AS poi', function($join) {
							$join->on('poi.purchase_order_id','=','purchase_order.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('item_unit AS iu', function($join){
						  $join->on('iu.unit_id','=','poi.unit_id')
							   ->on('iu.itemmaster_id','=','poi.item_id');
					  })
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->where('poi.status',1)
					  ->where('poi.deleted_at', '0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','iu.pkno','im.batch_req')
					  ->groupBy('poi.id')
					  ->orderBY('poi.id')
					  ->get();
	
	}
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_order->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->purchase_order->where('voucher_no',$refno)->count();
	}
	public function getOrderHistory($supplier_id)
	{
		$query = $this->purchase_order->where('purchase_order.supplier_id',$supplier_id)->where('purchase_order.status',1);
		
		return $query->join('purchase_order_item AS poi', function($join) {
							$join->on('poi.purchase_order_id','=','purchase_order.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->where('poi.status',1)
					  ->select('poi.*','u.unit_name','im.item_code','purchase_order.voucher_date','purchase_order.reference_no')->get();
	}
	
	public function getOrderReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->purchase_order->where('purchase_order.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_order.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_order.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','purchase_order.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->purchase_order->where('purchase_order.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_order.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_order.job_id');
								   })
								   ->select('AM.master_name AS supplier','purchase_order.*','JM.name AS job')
								   ->orderBY('purchase_order.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}

	
	public function getOrder($attributes)
	{
		$order = $this->purchase_order->where('purchase_order.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_order.supplier_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','purchase_order.currency_id');
								   })
								   ->leftJoin('terms AS TR', function($join) {
									   $join->on('TR.id','=','purchase_order.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','AM.address','AM.city','AM.state','AM.contact_name','AM.vat_no','AM.phone','purchase_order.*','C.name AS currency','TR.description AS terms')
								   ->orderBY('purchase_order.id', 'ASC')
								   ->first();
								   
		$items = $this->purchase_order->where('purchase_order.id', $attributes['document_id'])
								   ->join('purchase_order_item AS PI', function($join) {
									   $join->on('PI.purchase_order_id','=','purchase_order.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','purchase_order.id','IM.item_code','U.unit_name')
								   ->orderBY('PI.id')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	
	public function getItems($id)
	{
		
		$query = $this->purchase_order->where('purchase_order.id',$id);
		
		return $query->join('purchase_order_item AS poi', function($join) {
							$join->on('poi.purchase_order_id','=','purchase_order.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('item_unit AS iu', function($join){
						  $join->on('iu.unit_id','=','poi.unit_id')
							   ->on('iu.itemmaster_id','=','poi.item_id');
					  })
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->where('poi.status',1)
					  ->where('poi.deleted_at', '0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','iu.pkno')
					  ->groupBy('poi.id')
					  ->orderBY('poi.id')
					  ->get();
	}
	public function PurchaseOrder($supplier_id=null)
	{
		 $qry = $this->purchase_order
							 ->where('purchase_order.status', 1);
							 
				if($supplier_id)
					$qry->where('purchase_order.supplier_id', $supplier_id);
					
				return	$qry->where('purchase_order.is_transfer', 0)
							 ->select('purchase_order.id','purchase_order.voucher_no','purchase_order.voucher_date',
							 'purchase_order.net_amount')
							 ->get();
		
	}
	public function findOrderData($id)
	{
		$query = $this->purchase_order->where('purchase_order.id', $id)->where('purchase_order.is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','purchase_order.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','purchase_order.footer_id');
					})
					
					->select('purchase_order.*','am.master_name AS supplier','h.title AS header','f.title AS footer')
					->orderBY('purchase_order.id', 'ASC')
					->first();
	}
	
	
	public function getPendingReport($attributes)
	{	//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$pending=isset($attributes['pending'])?$attributes['pending']:0;
	//	switch($attributes['search_type']) {
		//	case 'summary':
			if($attributes['search_type']=='summary' && $pending==0){
				$query = $this->purchase_order
								->join('purchase_order_item AS POI', function($join) {
									$join->on('POI.purchase_order_id','=','purchase_order.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_order.supplier_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','purchase_order.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','purchase_order.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}

						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);
						
							if($attributes['supplier_id']!='')
							$query->where('purchase_order.supplier_id', $attributes['supplier_id']);	


					if(isset($attributes['currency_id']) && $attributes['currency_id']!='')
					$query->whereIn('purchase_order.currency_id', $attributes['currency_id']);
						// if($attributes['currency_id']!='')
						// 	$query->where('purchase_order.currency_id', $attributes['currency_id']);
						 
				 $query->select('purchase_order.voucher_no','purchase_order.reference_no','IM.item_code','IM.description','purchase_order.total','purchase_order.discount','purchase_order.vat_amount',
									  'purchase_order.description','POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','purchase_order.net_amount',
									  'purchase_order.total_fc','purchase_order.vat_amount_fc','purchase_order.net_amount_fc','J.code as jobcode');
								
				if(isset($attributes['type']))
					return $query->groupBy('purchase_order.id')->get()->toArray();
				else
					return $query->groupBy('purchase_order.id')->get();
								
			//	break;
			}
				
			//case 'summary_pending':
		else if($attributes['search_type']=='summary' && $pending==1){
				$query = $this->purchase_order->where('POI.is_transfer','!=',1)->where('purchase_order.is_settled',0)
								->join('purchase_order_item AS POI', function($join) {
									$join->on('POI.purchase_order_id','=','purchase_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_order.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','purchase_order.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','purchase_order.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}
						
						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);

							if($attributes['supplier_id']!='')
							$query->where('purchase_order.supplier_id', $attributes['supplier_id']);


				$query->select('purchase_order.voucher_no','purchase_order.reference_no','IM.item_code','IM.description','purchase_order.total','purchase_order.vat_amount','POI.vat_amount AS unit_vat',
								'purchase_order.description','POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','purchase_order.net_amount',
								'C.code','purchase_order.currency_rate','J.code as jobcode');
								
				/* if(isset($attributes['type']))
					return $query->get();//groupBy('purchase_order.id')
				else */
				return $query->get();//groupBy('purchase_order.id')->
				
				//break;
		}
				
			//case 'detail':
			else if($attributes['search_type']=='detail' && $pending==0){
				$query = $this->purchase_order
								->join('purchase_order_item AS POI', function($join) {
									$join->on('POI.purchase_order_id','=','purchase_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_order.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','purchase_order.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','purchase_order.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}
						
						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);

							if($attributes['supplier_id']!='')
							$query->where('purchase_order.supplier_id', $attributes['supplier_id']);

						 
				$query->select('purchase_order.voucher_no','purchase_order.voucher_date','purchase_order.reference_no','IM.item_code','IM.description','purchase_order.total','purchase_order.vat_amount',
								'POI.quantity','POI.balance_quantity','POI.unit_price','POI.total_price','AM.account_id','AM.master_name','purchase_order.net_amount',
								'POI.vat_amount AS unit_vat','POI.discount','C.code','purchase_order.currency_rate','J.code as jobcode','purchase_order.voucher_date');
				
				if(isset($attributes['type']))
					return $query->get()->toArray();
				else
					return $query->get();
				
			//	break;
			}
				
			//case 'detail_pending'||'qty_report':
			else if($attributes['search_type']=='detail' && $pending==1){
				$query = $this->purchase_order->where('POI.is_transfer','!=',1)->where('purchase_order.is_settled',0)
								->join('purchase_order_item AS POI', function($join) {
									$join->on('POI.purchase_order_id','=','purchase_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_order.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','purchase_order.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','purchase_order.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}
						 
						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);

							if($attributes['supplier_id']!='')
							$query->where('purchase_order.supplier_id', $attributes['supplier_id']);

						
				$query->select('purchase_order.voucher_no','purchase_order.voucher_date','purchase_order.reference_no','IM.item_code','IM.description','purchase_order.total','purchase_order.vat_amount',
								'POI.quantity','POI.balance_quantity','POI.unit_price','POI.total_price','AM.account_id','AM.master_name','purchase_order.net_amount',
								'C.code','purchase_order.currency_rate','J.code as jobcode');
				
				if(isset($attributes['type']))
					return $query->orderBY('purchase_order.voucher_no', 'ASC')->get()->toArray();
				else
					return $query->orderBY('purchase_order.voucher_date', 'ASC')->get();
								
			//	break;
		}
	}
	
	public function getOtherCost($id)
	{
		$query = $this->purchase_order->where('purchase_order.id',$id);
		
		return $query->join('po_other_cost AS pi', function($join) {
							$join->on('pi.purchase_order_id','=','purchase_order.id');
						} )
					  ->join('account_master AS im', function($join){
						  $join->on('im.id','=','pi.dr_account_id');
					  })
					  ->join('account_master AS im2', function($join){
						  $join->on('im2.id','=','pi.cr_account_id');
					  })
					  ->where('pi.status',1)
					  ->select('pi.*','im.id AS dr_id','im.master_name AS dr_name','im2.id AS cr_id','im2.master_name AS cr_name')->get();
	}
	
	public function purchaseOrderListCount()
	{
		$query = $this->purchase_order->where('purchase_order.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} )
					->count();
	}
	
	public function purchaseOrderList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->purchase_order->where('purchase_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_order.supplier_id');
						} );
						
				if($search) {
					$query->where('purchase_order.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('purchase_order.reference_no', 'LIKE',"%{$search}%")
						  ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('purchase_order.*','am.master_name AS supplier')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	public function getJobPurOrd($job_id)
	{
		return $this->purchase_order
					 ->Join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_order.supplier_id');
						} )
					 ->where('purchase_order.status', 1)
					 ->where('purchase_order.job_id', $job_id)
					 ->where('purchase_order.is_transfer', 0)
					 ->select('purchase_order.id','purchase_order.voucher_no','purchase_order.voucher_date','purchase_order.net_amount','AM.master_name')
					 ->get();
		
	}
	
	public function getItemDesc($id)
	{
		return DB::table('purchase_order')
						->join('purchase_order_item AS QSI', function($join) {
							$join->on('QSI.purchase_order_id', '=', 'purchase_order.id');
						})
						->join('item_description AS D', function($join) {
							$join->on('D.item_detail_id', '=', 'QSI.id');
						})
						->where('purchase_order.id', $id)
						->where('D.invoice_type','PO')
						->where('QSI.status',1)
						->where('QSI.deleted_at','0000-00-00 00:00:00')
						->where('D.status',1)
						->where('D.deleted_at','0000-00-00 00:00:00')
						->select('D.*')
						->get();
	}
}
