<?php
declare(strict_types=1);
namespace App\Repositories\PurchaseInvoice;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\AccountTransaction;
use App\Models\PurchaseInvoiceOtherCost;
use App\Models\ItemStock;
use App\Models\ItemLocation;
use App\Models\ItemLocationPI;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Ixudra\Curl\Facades\Curl;
use App\Repositories\UpdateUtility;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;
use Storage;

class PurchaseInvoiceRepository extends AbstractValidator implements PurchaseInvoiceInterface {
	
	public $objUtility;
	
	protected $purchase_invoice;
	protected $mod_sdo_qty;
	
	protected static $rules = [];
	
	public function __construct(PurchaseInvoice $purchase_invoice) {
		$this->purchase_invoice = $purchase_invoice;
		$config = Config::get('siteconfig');
		$this->api_url = $config['modules']['api_url'];
		$this->objUtility = new UpdateUtility();
		$this->mod_sdo_qty = DB::table('parameter2')->where('keyname', 'mod_sdo_qty_update')->where('status',1)->select('is_active')->first();
	}
	
	public function all()
	{
		return $this->purchase_invoice->get();
	}
	
	public function find($id)
	{
		return $this->purchase_invoice->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->purchase_invoice->voucher_id = $attributes['voucher_id']; //echo ($attributes['document_type']=='')?'PI':$attributes['document_type'];
		$this->purchase_invoice->voucher_no = $attributes['voucher_no']; 
		$this->purchase_invoice->reference_no = $attributes['reference_no'];
		$this->purchase_invoice->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->purchase_invoice->lpo_date = ($attributes['lpo_date']!='')?date('Y-m-d', strtotime($attributes['lpo_date'])):'';
		$this->purchase_invoice->document_type = ($attributes['document_type']=='')?'PI':$attributes['document_type'];//Purchase Invoice type
		$this->purchase_invoice->supplier_id = $attributes['supplier_id'];
		$this->purchase_invoice->document_id = $attributes['document_id'];
		$this->purchase_invoice->job_id = $attributes['job_id'];
		$this->purchase_invoice->terms_id = $attributes['terms_id'];
		$this->purchase_invoice->description = $attributes['description'];
		$this->purchase_invoice->account_master_id = $attributes['account_master_id'];
		$this->purchase_invoice->is_fc = isset($attributes['is_fc'])?1:0;
		$this->purchase_invoice->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->purchase_invoice->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:'';
		$this->purchase_invoice->lpo_no = $attributes['lpo_no'];
		$this->purchase_invoice->is_import		= isset($attributes['is_import'])?1:0;
		$this->purchase_invoice->location_id = $attributes['location_id'];
		$this->purchase_invoice->po_no = (isset($attributes['po_no']))?$attributes['po_no']:'';
		$this->purchase_invoice->supplier_name = isset($attributes['suppliername'])?$attributes['suppliername']:$attributes['supplier_name'];
		$this->purchase_invoice->department_id   = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function getOtherCostSum($ocamount, $vatoc)
	{ 
		//print_r($ocamount);exit;
		$amount = 0;
		foreach($ocamount as $k => $val) {
			$perc = (array_key_exists($k, $vatoc))?$vatoc[$k]:0;
			$vat = $val * $perc / 100;
			$amount += $val + $vat;
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
	
	private function setItemInputValue($attributes, $purchaseInvoiceItem, $key, $value, $other_cost, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
		if( isset($attributes['is_fc']) ) {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
			
			if($tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate'];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = ($ln_total - $tax_total) * $attributes['currency_rate'];
				
				if( isset($attributes['other_cost'])) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = (($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key]) * $attributes['currency_rate'];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
				
				if( isset($attributes['other_cost']) && $other_cost > 0 ) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
			}
			
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
			
			if($tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
				if( isset($attributes['other_cost'])) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
				if( isset($attributes['other_cost']) && $other_cost > 0 ) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}
			}
			
		}

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
			$tax_total = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine; //M14
		} 
		
		//PO auto transfer settings...........
		if(isset($attributes['po_no']) && $attributes['po_no']!='') {
			$PO = DB::table('purchase_order')->where('purchase_order.voucher_no', $attributes['po_no'])->where('purchase_order.status',1)
											 ->join('purchase_order_item', 'purchase_order_item.purchase_order_id', '=', 'purchase_order.id')
											 ->where('purchase_order_item.item_id', $attributes['item_id'][$key])
											 ->where('purchase_order_item.unit_id',$attributes['unit_id'][$key])
											 ->where('purchase_order_item.deleted_at','0000-00-00 00:00:00')
											 ->where('purchase_order_item.status',1)
											 ->where('purchase_order.deleted_at','0000-00-00 00:00:00')
											 ->whereIn('purchase_order.is_transfer',[0,2])
											 ->whereIn('purchase_order_item.is_transfer',[0,2])
											 ->select('purchase_order.id','purchase_order_item.id AS pid','purchase_order_item.quantity','purchase_order_item.balance_quantity','purchase_order_item.is_transfer')->first();
			if($PO) {
				if($PO->balance_quantity==0) {
					$balqty = $PO->quantity - $attributes['quantity'][$key];
					$st = ($balqty==0)?1:2;
					DB::table('purchase_order_item')->where('id', $PO->pid)
									->update(['balance_quantity' => $balqty, 'is_transfer' => $st]);
				} else {
					$balqty = $PO->balance_quantity - $attributes['quantity'][$key];
					$st = ($balqty==0)?1:2;
					DB::table('purchase_order_item')->where('id', $PO->pid)
									->update(['balance_quantity' => $balqty, 'is_transfer' => $st]);
				}
			}
		}
		
		
		$purchaseInvoiceItem->purchase_invoice_id = $this->purchase_invoice->id;
		$purchaseInvoiceItem->item_id = $attributes['item_id'][$key];
		$purchaseInvoiceItem->unit_id = $attributes['unit_id'][$key];
		$purchaseInvoiceItem->item_name = $attributes['item_name'][$key];
		$purchaseInvoiceItem->quantity = $attributes['quantity'][$key];
		$purchaseInvoiceItem->unit_price = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$purchaseInvoiceItem->vat = $attributes['line_vat'][$key];
		$purchaseInvoiceItem->vat_amount = $tax_total;
		$purchaseInvoiceItem->discount = $attributes['line_discount'][$key];
		$purchaseInvoiceItem->total_price = $line_total;
		$purchaseInvoiceItem->othercost_unit = $othercost_unit;
		$purchaseInvoiceItem->netcost_unit = $netcost_unit;
		$purchaseInvoiceItem->tax_code 	= $tax_code;
		$purchaseInvoiceItem->tax_include = $attributes['tax_include'][$key];
		$purchaseInvoiceItem->item_total = $item_total;
		
		$purchaseInvoiceItem->unit_price_fc = $attributes['cost'][$key];
		$purchaseInvoiceItem->vat_amount_fc = (isset($attributes['is_fc']))?round(($tax_total /  $attributes['currency_rate']),2):$tax_total;
		$purchaseInvoiceItem->total_price_fc = (isset($attributes['is_fc']))?round(($line_total /  $attributes['currency_rate']),2):$line_total;
		$purchaseInvoiceItem->item_total_fc = (isset($attributes['is_fc']))?round(($item_total /  $attributes['currency_rate']),2):$item_total;
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total,'othercost_unit' => $othercost_unit, 'type' => $type, 'item_total' => $item_total);
		
		
	}
	
	private function updateLastPurchaseCostAndCostAvg($attributes, $key, $other_cost)
	{
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->whereNull('deleted_at')
										->select('cur_quantity','pur_cost')
										->get();
										
		//$itmcost = $itmqty = 0;
		$itmcost = (isset($attributes['is_fc']))? ($attributes['quantity'][$key] * $attributes['cost'][$key] * $attributes['currency_rate']) : $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		//$cost_avg = (isset($attributes['is_fc']))?round( (($itmcost / $itmqty) * $attributes['currency_rate']) + $other_cost, 3): round( (($itmcost / $itmqty) + $other_cost), 3);
		//$cost_avg = (isset($attributes['is_fc']))?round( (($itmcost / $itmqty) + $other_cost) * $attributes['currency_rate'], 3): round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
					
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->where('unit_id', $attributes['unit_id'][$key])
				->update(['last_purchase_cost' => $cost + $other_cost,
						  'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
						
		//UPDATING ROWMATERIAL COST...
		$itemlog = DB::table('mfg_items')->where('subitem_id', $attributes['item_id'][$key])->select('id','quantity')->get();
		if($itemlog) {
			foreach($itemlog as $log) {
				$ucost = $cost + $other_cost;
				$mtotal = $ucost * $log->quantity;
				DB::table('mfg_items')->where('id', $log->id)->update(['unit_price' => $ucost, 'total'	=> $mtotal ]);
			}
		}
							
		return $cost_avg;
		
	}
	
	private function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $other_cost)
	{	
		$pid = $attributes['purchase_invoice_id'];
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->whereNull('deleted_at')
										->where(function ($query) use($pid) {
											$query->where('document_id','!=',$pid)
												  ->orWhere('document_type','!=','PI');
										})
										->select('cur_quantity','pur_cost')
										->get();
		echo '<pre>';print_r($itmlogs);exit;								
		//$itmcost = $itmqty = 0;
		$itmcost = (isset($attributes['is_fc']))? ($attributes['quantity'][$key] * $attributes['cost'][$key] * $attributes['currency_rate']) : $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		//$cost_avg = (isset($attributes['is_fc']))?round( (($itmcost / $itmqty) + $other_cost) * $attributes['currency_rate'], 3): round( (($itmcost / $itmqty) + $other_cost), 3);
		//$cost_avg = (isset($attributes['is_fc']))?round( (($itmcost / $itmqty) + $other_cost) * $attributes['currency_rate'], 3): round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		//echo $cost;exit;
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->where('unit_id', $attributes['unit_id'][$key])
				->update(['last_purchase_cost' => $cost + $other_cost,
						  'cost_avg'		   => $cost_avg
						]);
		
		//UPDATING ROWMATERIAL COST...
		$itemlog = DB::table('mfg_items')->where('subitem_id', $attributes['item_id'][$key])->select('id','quantity')->get();
		if($itemlog) {
			foreach($itemlog as $log) {
				$ucost = $cost + $other_cost;
				$mtotal = $ucost * $log->quantity;
				DB::table('mfg_items')->where('id', $log->id)->update(['unit_price' => $ucost, 'total'	=> $mtotal ]);
			}
		}
		
		return $cost_avg;
	}
	
	//UPDATED MAR1...
	private function updateLastPurchaseCostAndCostAvgonDelete($items, $id) {
		//UPDATE Cost avg and stock...
		foreach($items as $item) {
									
			//COST AVG Updating on DELETE section....
			DB::table('item_log')->where('document_id', $id)->where('document_type','PI')
								 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
								 ->update(['status' => 0, 'deleted_at' => now()]);
			
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity - '.$item->quantity)]);
									  
			/* DB::table('item_location')->where('item_id', $item->item_id)->where('unit_id', $item->unit_id)
									  ->where('location_id', $this->purchase_invoice->location_id)
									  ->update(['quantity' => DB::raw('quantity - '.$item->quantity) ]); */
									  
			$this->objUtility->autoUpdateAVGCost($item->item_id);
		}
	}

	private function setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key)
	{
		$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
		$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
		
		$purchaseInvoiceOC->purchase_invoice_id = $this->purchase_invoice->id;
		$purchaseInvoiceOC->dr_account_id = $attributes['dr_acnt_id'][$key];
		$purchaseInvoiceOC->oc_reference = $attributes['oc_reference'][$key];
		$purchaseInvoiceOC->oc_description = $attributes['oc_description'][$key];
		$purchaseInvoiceOC->cr_account_id = $attributes['cr_acnt_id'][$key];
		$purchaseInvoiceOC->oc_amount = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]):$attributes['oc_amount'][$key];
		$purchaseInvoiceOC->oc_fc_amount = $attributes['oc_amount'][$key];
		$purchaseInvoiceOC->oc_vat = $attributes['vat_oc'][$key];
		//$purchaseInvoiceOC->oc_vatamt = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]*$attributes['vat_oc'][$key]/100):($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
		
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
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null, $objOC=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		if($amount!=0) {
			
			if($amount_type=='VAT' || $amount_type=='VATOC') {
				$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();
				
				//if(isset($attributes['is_import']) && $amount_type=='VAT') { //if vat import is checked....
				if( (isset($attributes['is_import']) && $amount_type=='VAT') || ($amount_type=='VATOC' && $attributes['tax_sr'][$key]=='RC') ) {
					if($vatrow) {
						$dr_acnt_id = $vatrow->vatinput_import;
					}
					
					$vatrowout = DB::table('account_master')->where('id', $vatrow->vatoutput_import)->where('status', 1)->first(); //$vatrowout = DB::table('account_master')->where('master_name', 'VAT OUTPUT on IMPORT')->where('status', 1)->first();
					if($vatrowout) {
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'PI',
										'voucher_type_id'   => $voucher_id,
										'account_master_id' => $vatrowout->id,
										'transaction_type'  => 'Cr',
										'amount'   			=> $amount,
										'status' 			=> 1,
										'created_at' 		=> now(),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'reference_from'	=> $attributes['reference_no'],
										'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
										'is_fc'				=> isset($attributes['is_fc'])?1:0,
										'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
										]);
										
						
						$this->objUtility->tallyClosingBalance($vatrowout->id);	
						
					}
					
				} else {
					
					if($vatrow) {
						$dr_acnt_id = $vatrow->collection_account;
					}
				}
				
			} else if($amount_type == 'LNTOTAL') {
				$dr_acnt_id = $attributes['account_master_id'];
			} else if($amount_type == 'NTAMT') {
				$cr_acnt_id = $attributes['supplier_id'];
			} else if($amount_type == 'OC') {
				$cr_acnt_id = $attributes['cr_acnt_id'][$key];
				$dr_acnt_id = $attributes['dr_acnt_id'][$key];
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					$cr_acnt_id = $vatrow->purdis_acid;
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
					$cr_acnt_id = $vatrow->account_id;
				}
			}
			
			if(isset($attributes['is_import']) && $amount_type=='NTAMT') { 
				$vatamt = isset($attributes['currency_rate'])?round($attributes['vat'] * $attributes['currency_rate'],2):$attributes['vat'];
				$amount = $amount - $vatamt;
				//echo $amount.' '.$attributes['vat'].' '.$attributes['currency_rate'];exit;// = $amount - ($attributes['vat'] * $attributes['currency_rate']);exit;
			}
			
			$trfor = ($amount_type=='OC' || $amount_type=='VATOC')?$objOC->id:0;
			
			if($trfor==0) {
				$is_fc = isset($attributes['is_fc'])?1:0;
				
				$fc_amount = (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount;
			} else {
				/* $is_fc = ($attributes['oc_rate'][$key]!='')?1:0;
				$amount = $amount * $attributes['oc_rate'][$key];
				$fc_amount = ($is_fc==1)?($amount/$attributes['oc_rate'][$key]):$amount */; //$attributes['oc_amount'][$key];
				
				$is_fc = ($attributes['oc_rate'][$key]!='')?1:0;
				if($is_fc==1) {
					$amount = $amount * $attributes['oc_rate'][$key];
					$fc_amount = $amount/$attributes['oc_rate'][$key];
				} else {
					$fc_amount = $amount;
				}
			}
			
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PI',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> ($amount_type=='OC')?$attributes['oc_description'][$key]:$attributes['description'],
								'reference'			=> ($amount_type=='OC')?$attributes['reference_no']:$attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> ($amount_type=='OC' || $amount_type=='VATOC')?$attributes['oc_reference'][$key]:$attributes['reference_no'],
								'tr_for'			=> $trfor,
								'fc_amount'			=> $fc_amount,
								'other_type'		=> ($amount_type=='OC' || $amount_type=='VATOC')?'OC':'',
								'is_fc'				=> $is_fc,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);	
			
		}
						
		return true;
	}
	
	private function setTransferStatusItem($attributes, $key, $doctype)
	{ 
		//if quantity partially deliverd, update pending quantity.
		if($doctype=='QO') {
			if(isset($attributes['quote_purchase_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['quote_purchase_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('quotation_item')
									->where('id', $attributes['quote_purchase_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('quotation_item')
									->where('id', $attributes['quote_purchase_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else if($doctype=='PO') { //echo $attributes['purchase_order_item_id'][$key];exit;
			if(isset($attributes['purchase_order_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['purchase_order_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else { 
						//update as completely delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else if($doctype=='MR') { //echo $attributes['purchase_order_item_id'][$key];exit;
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
		} else if($doctype=='SDO') {
			if(isset($attributes['supplier_do_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['supplier_do_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('supplier_do_item')
									->where('id', $attributes['supplier_do_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('supplier_do_item')
									->where('id', $attributes['supplier_do_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		}
	}
	
	
	private function setPurchaseLog($attributes, $key, $document_id, $cost_avg, $action,$other_cost, $item=null)
	{
		$irow = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])->select('cur_quantity')->first();
		if($action=='add') {
			
			$cur_quantity = ($irow)?$irow->cur_quantity + $attributes['quantity'][$key]:0;
			$cquantity = (isset($attributes['sale_qty'][$key]))?($attributes['quantity'][$key]-$attributes['sale_qty'][$key]):$attributes['quantity'][$key];
			//-----------ITEM LOG----------------							
			DB::table('item_log')->insert([
							 'document_type' => 'PI',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $attributes['quantity'][$key] * $attributes['packing'][$key],
							 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
							 'trtype'	  => 1,
							 'cur_quantity' => $cquantity,
							 'cost_avg' => $cost_avg,
							 'pur_cost' => (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => now(),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							 'sale_reference' => $cur_quantity,
							 'other_cost'	 => $other_cost
							]);
			//-------------ITEM LOG------------------
			
		} else if($action=='update') {
			
			$cquantity = (isset($attributes['sale_qty'][$key]))?($attributes['quantity'][$key]-$attributes['sale_qty'][$key]):$attributes['quantity'][$key];
			//-----------ITEM LOG----------------							
			DB::table('item_log')->where('document_type','PI')
							->where('document_id', $document_id)
							->where('item_id', $item->item_id)
							->where('unit_id', $item->unit_id)
							->update(['item_id' => $attributes['item_id'][$key],
								 'unit_id' => $attributes['unit_id'][$key],
								 'quantity'   => $attributes['quantity'][$key] * $attributes['packing'][$key],
								 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
								 'cur_quantity' => $cquantity,
								 'cost_avg' => $cost_avg,
								 'pur_cost' => (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost,
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								 'other_cost'	 => $other_cost
							]);
			//-------------ITEM LOG------------------
		}
							
		return true;
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('is_baseqty', 1)->first();
									  
		if($item) {
			$qty = $attributes['quantity'][$key];
			$baseqty = ($qty * $attributes['packing'][$key]);
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => $item->cur_quantity + $baseqty,
						   'received_qty' => DB::raw('received_qty + '.$baseqty) ]);
							
		}
									  
		return true;
	}
	
	//UPDATED MAR 1
	private function updateItemQuantityonEdit($attributes, $key)
	{
		if($attributes['actual_quantity'][$key] != $attributes['quantity'][$key]) {
			
			$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
										  ->where('is_baseqty', 1)->first();
										  
			if($item) {
				$qty = $attributes['quantity'][$key];
				//$packing = ($item->is_baseqty==1)?1:$item->packing;
				$baseqty = ($qty * $attributes['packing'][$key]);
				$diffqty = ($attributes['actual_quantity'][$key] * $attributes['packing'][$key]) - ($qty * $attributes['packing'][$key]);
				$received_qty = $diffqty * -1;
				
				if($attributes['actual_quantity'][$key] < $qty) {
					$cur_quantity = $item->cur_quantity + $received_qty;
				} else { 
					$cur_quantity = $item->cur_quantity - $diffqty;
				}
				
				DB::table('item_unit')
					->where('itemmaster_id',  $attributes['item_id'][$key])
					->where('is_baseqty',1)
					->update([ 'cur_quantity' => $cur_quantity,
								'received_qty' => DB::raw('received_qty + '.$received_qty) ]);
					
			}

			return true;
		}
	}
	
	//Accounting Method function............
	private function AccountingMethod($attributes, $line_total, $tax_total, $net_amount, $purchase_invoice_id, $taxtype)
	{
		if( isset($attributes['is_fc']) ) //M14..
			$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
		else
			$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		if($taxtype=='tax_include' && $discount > 0) {
			$temp = $net_amount;
			$net_amount = $line_total;
			$line_total = $temp + $discount;
		} else if($taxtype=='tax_exclude' && $discount > 0) {
			$line_total = $line_total + $discount;
		}
		
		//Debit Stock in Hand
		if( $this->setAccountTransaction($attributes, $line_total, $purchase_invoice_id, $type='Dr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransaction($attributes, $tax_total, $purchase_invoice_id, $type='Dr', $amount_type='VAT') ) {
		
				//Credit Supplier Accounting
				if( $this->setAccountTransaction($attributes, $net_amount, $purchase_invoice_id, $type='Cr', $amount_type='NTAMT') ) {
				
					$this->setAccountTransaction($attributes, $discount, $purchase_invoice_id, $type='Cr', $amount_type='DIS');
				}
			}
		}
		
	}
	
	private function AccountingMethodUpdate($attributes, $line_total, $tax_total, $net_amount, $purchase_invoice_id, $taxtype)
	{
		if( isset($attributes['is_fc']) ) //M14..
			$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
		else
			$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		if($taxtype=='tax_include' && $discount > 0) {
			$temp = $net_amount;
			$net_amount = $line_total;
			$line_total = $temp + $discount;
		} else if($taxtype=='tax_exclude' && $discount > 0) {
			$line_total = $line_total + $discount;
		}
		
		//Debit Stock in Hand
		if( $this->setAccountTransactionUpdate($attributes, $line_total, $purchase_invoice_id, $type='Dr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransactionUpdate($attributes, $tax_total, $purchase_invoice_id, $type='Dr', $amount_type='VAT') ) {
			
				//Credit Supplier Accounting
				if( $this->setAccountTransactionUpdate($attributes, $net_amount, $purchase_invoice_id, $type='Cr', $amount_type='NTAMT') ) {
					
					$this->setAccountTransactionUpdate($attributes, $discount, $purchase_invoice_id, $type='Cr', $amount_type='DIS');
				}
			}
		}
		
		//set account Cr/Dr discount amount transaction....**********needed to implement
	}
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null, $objOC=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();
		if($amount!=0) {
			if($amount_type=='VAT' || $amount_type=='VATOC') {
				
				if( (isset($attributes['is_import']) && $amount_type=='VAT') || ($amount_type=='VATOC' && $attributes['tax_sr'][$key]=='RC') ) {
					
					if($vatrow) {
						$dr_acnt_id = $account_id = $vatrow->vatinput_import;
					}
					
					$vatrowout = DB::table('account_master')->where('id', $vatrow->vatoutput_import)->where('status', 1)->first();
					
					//New entries of vat import accounts...
					if(($attributes['is_import_old']==0 && $vatrowout) || (isset($attributes['tax_sr_old'][$key]) && $attributes['tax_sr_old'][$key]!='RC')) {
						
						//Update Vat input a/c as vat input import a/c...
						$trfor = ($amount_type=='VATOC')?1:0;
						DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('account_master_id', $vatrow->collection_account) //CHNG tax_code
							->where('transaction_type' , 'Dr')
							->where('voucher_type', 'PI')					
							->where('tr_for', $trfor)
								->update(['account_master_id' => $vatrow->vatinput_import]);
						
						
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'PI',
										'voucher_type_id'   => $voucher_id,
										'account_master_id' => $vatrowout->id,
										'transaction_type'  => 'Cr',
										'amount'   			=> $amount,
										'status' 			=> 1,
										'created_at' 		=> now(),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'reference_from'	=> $attributes['reference_no'],
										'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
										'is_fc'				=> isset($attributes['is_fc'])?1:0,
										'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
										]);
										
						
						$this->objUtility->tallyClosingBalance($vatrowout->id);	
						
					} else {
					
						DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('account_master_id', $vatrowout->id) //CHNG
							->where('voucher_type', 'PI')
							->update([  'amount'   			=> $amount,
										'modify_at' 		=> now(),
										'modify_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'reference_from'	=> $attributes['reference_no'],
										'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
										'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
										]);
					
						$this->objUtility->tallyClosingBalance($vatrowout->id);
					}
					
				} else {
					
					//Remove vat import accounts....
					if(($attributes['is_import_old']==1 && $vatrow) || (isset($attributes['tax_sr_old'][$key]) && $attributes['tax_sr_old'][$key]=='RC') ) {
						
						$trfor = ($amount_type=='VATOC')?1:0;
						
						//Update vat input import as Vat input a/c ...
						DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('account_master_id', $vatrow->vatinput_import) //CHNG
							->where('transaction_type' , 'Dr')
							->where('voucher_type', 'PI')					
							->where('tr_for', $trfor)
								->update(['account_master_id' => $vatrow->collection_account]);
								
						//Remove vatoutput acount		
						DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('account_master_id', $vatrow->vatoutput_import) //CHNG
							->where('transaction_type' , 'Cr')
							->where('voucher_type', 'PI')					
							->where('tr_for', $trfor)
								->update(['status' => 0, 'deleted_at' => now()]);
								
						$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
					}
					
					//TAx code change..... account_id
					if($attributes['tax_code_old'][0]!=$attributes['tax_code'][0]) {
						
						if( ($attributes['tax_code_old'][0]=='ZR' && $attributes['tax_code'][0]=='SR') || ($attributes['tax_code_old'][0]=='EX' && $attributes['tax_code'][0]=='SR') ) {
							//Storage::prepend('stolog.txt', 'tax_code_old: ');
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'PI',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->collection_account,
											'transaction_type'  => 'Dr',
											'amount'   			=> $amount,
											'status' 			=> 1,
											'created_at' 		=> now(),
											'created_by' 		=> Auth::User()->id,
											'description' 		=> $attributes['description'],
											'reference'			=> $attributes['voucher_no'],
											'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
											'reference_from'	=> $attributes['reference_no'],
											'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
											'is_fc'				=> isset($attributes['is_fc'])?1:0,
											'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
											]);
											
							$dr_acnt_id = $account_id = $vatrow->collection_account;
							$this->objUtility->tallyClosingBalance($vatrow->collection_account);	
							
						} else if( ($attributes['tax_code_old'][0]=='ZR' && $attributes['tax_code'][0]=='RC') || ($attributes['tax_code_old'][0]=='EX' && $attributes['tax_code'][0]=='RC') ) {
							
							//Vat input import insert...
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'PI',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->vatinput_import,
											'transaction_type'  => 'Dr',
											'amount'   			=> $amount,
											'status' 			=> 1,
											'created_at' 		=> now(),
											'created_by' 		=> Auth::User()->id,
											'description' 		=> $attributes['description'],
											'reference'			=> $attributes['voucher_no'],
											'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
											'reference_from'	=> $attributes['reference_no'],
											'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
											'is_fc'				=> isset($attributes['is_fc'])?1:0,
											'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
											]);
											
							$dr_acnt_id = $account_id = $vatrow->vatinput_import;
							$this->objUtility->tallyClosingBalance($vatrow->vatinput_import);
							
							//Vat output import insert...
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'PI',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->vatoutput_import,
											'transaction_type'  => 'Cr',
											'amount'   			=> $amount,
											'status' 			=> 1,
											'created_at' 		=> now(),
											'created_by' 		=> Auth::User()->id,
											'description' 		=> $attributes['description'],
											'reference'			=> $attributes['voucher_no'],
											'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
											'reference_from'	=> $attributes['reference_no'],
											'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
											'is_fc'				=> isset($attributes['is_fc'])?1:0,
											'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
											]);
											
							$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
							
						} else if( $attributes['tax_code_old'][0]=='SR' && $attributes['tax_code'][0]=='RC') {
							
							//Update Vat input a/c as vat input import a/c...
							DB::table('account_transaction')
								->where('voucher_type_id', $voucher_id)
								->where('account_master_id', $vatrow->collection_account) //CHNG tax_code
								->where('transaction_type' , 'Dr')
								->where('voucher_type', 'PI')					
								->where('tr_for', 0)
									->update(['account_master_id' => $vatrow->vatinput_import]);
									
							$dr_acnt_id = $account_id = $vatrow->vatinput_import;
							
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'PI',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->vatoutput_import,
											'transaction_type'  => 'Cr',
											'amount'   			=> $amount,
											'status' 			=> 1,
											'created_at' 		=> now(),
											'created_by' 		=> Auth::User()->id,
											'description' 		=> $attributes['description'],
											'reference'			=> $attributes['voucher_no'],
											'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
											'reference_from'	=> $attributes['reference_no'],
											'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
											'is_fc'				=> isset($attributes['is_fc'])?1:0,
											'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
											]);
											
							
							$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
						
						} else if( $attributes['tax_code_old'][0]=='RC' && $attributes['tax_code'][0]=='SR') {
							
							//Update Vat input a/c as vat a/c...
							DB::table('account_transaction')
								->where('voucher_type_id', $voucher_id)
								->where('account_master_id', $vatrow->vatinput_import) //CHNG tax_code
								->where('transaction_type' , 'Dr')
								->where('voucher_type', 'PI')					
								->where('tr_for', 0)
									->update(['account_master_id' => $vatrow->collection_account, 'department_id' => (isset($attributes['department_id']))?$attributes['department_id']:'']);
									
							$dr_acnt_id = $account_id = $vatrow->collection_account;
							

							//Remove vatoutput acount		
							DB::table('account_transaction')
								->where('voucher_type_id', $voucher_id)
								->where('account_master_id', $vatrow->vatoutput_import) //CHNG
								->where('transaction_type' , 'Cr')
								->where('voucher_type', 'PI')					
								->where('tr_for', 0)
									->update(['status' => 0, 'deleted_at' => now()]);
								
						}
						
						
					} else {
					
						if($vatrow) {
							$dr_acnt_id = $account_id = $vatrow->collection_account;
						}
					}
				}
				
				$cur_account_id = $account_id;
				
			} else if($amount_type == 'LNTOTAL') {
				$dr_acnt_id = $cur_account_id = $account_id = $attributes['account_master_id']; //CHNG
			} else if($amount_type == 'NTAMT') {
				$cr_acnt_id = $cur_account_id = $account_id = $attributes['supplier_id']; //CHNG
				
				//CHANGING SUPPLIER..
				if($attributes['supplier_id'] != $attributes['old_supplier_id']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'PI')
							->where('account_master_id', $attributes['old_supplier_id'])
							->update( ['account_master_id' => $attributes['supplier_id'] ]);
							
					$this->objUtility->tallyClosingBalance($attributes['old_supplier_id']);
				}
				
				//CHANGING Dr account.. //Storage::prepend('stolog.txt', 'type:'.$type.' amt:'.$amount.' id:'.$voucher_id.' acid:'.$cur_account_id.' tr:'.$trfor);
				if($attributes['account_master_id'] != $attributes['old_account_master_id']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'PI')
							->where('account_master_id', $attributes['old_account_master_id'])
							->update( ['account_master_id' => $attributes['account_master_id'], 'department_id' => (isset($attributes['department_id']))?$attributes['department_id']:'' ]);
							
					$this->objUtility->tallyClosingBalance($attributes['old_supplier_id']);
				}
				
			} else if($amount_type == 'OC') {
				if($type=='Cr') {
					$cr_acnt_id = $account_id = $attributes['cr_acnt_id'][$key];
					$cur_account_id = $attributes['cur_cr_acnt_id'][$key];
				} else {
					$dr_acnt_id = $account_id = $attributes['dr_acnt_id'][$key];
					$cur_account_id = $attributes['cur_dr_acnt_id'][$key];
				}
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					$cr_acnt_id = $vatrow->purdis_acid;
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
					$cr_acnt_id = $cur_account_id = $account_id = $vatrow->account_id;
				}
			}
			
			if(isset($attributes['is_import']) && $amount_type=='NTAMT') { 
				$vatamt = isset($attributes['currency_rate'])?round(($attributes['vat'] * $attributes['currency_rate']),2):$attributes['vat'];
				$amount = $amount - $vatamt;
			}
			
			$trfor = ($amount_type=='OC' || $amount_type=='VATOC')?$attributes['oc_id'][$key]:0;
			
			if($trfor==0) {
				$is_fc = isset($attributes['is_fc'])?1:0;
				$fc_amount = (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount;
			} else {
				$is_fc = ($attributes['oc_rate'][$key]!='')?1:0;
				if($is_fc==1) {
					$amount = $amount * $attributes['oc_rate'][$key];
					$fc_amount = $amount/$attributes['oc_rate'][$key];
				} else {
					$fc_amount = $amount;
				}
				
			}
			
			DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $cur_account_id) //CHNG
					->where('voucher_type', 'PI')					
					->where('tr_for', $trfor)
					->update([  'account_master_id' => $account_id,
								'amount'   			=> $amount,
								'modify_at' 		=> now(),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> ($amount_type=='OC')?$attributes['oc_description'][$key]:$attributes['description'],
								'reference'			=> ($amount_type=='OC')?$attributes['reference_no']:$attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> ($amount_type=='OC' || $amount_type=='VATOC')?$attributes['oc_reference'][$key]:$attributes['reference_no'],
								'fc_amount'			=> $fc_amount,
								'is_fc'				=> $is_fc,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
								]);
								
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		} else {  //Remove vat account transaction..
			
			if( $attributes['vatcur'] != 0 && $attributes['vat'] == 0) {
				//Remove vat account...
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->collection_account)
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'PI')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
						
				$this->objUtility->tallyClosingBalance($vatrow->collection_account);
						
				//Remove vatoutput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatoutput_import)
					->where('transaction_type' , 'Cr')
					->where('voucher_type', 'PI')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
				
				//Remove vatinput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatinput_import)
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'PI')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatinput_import);
						
			} 
		}
		
		return true;
	}
	
	private function updateTransferStatus($attributes)
	{
		if($attributes['document_type']=="PO") {
			$ids = explode(',', $attributes['document_id']);
			foreach($ids as $id) {
				DB::table('purchase_order')->where('id', $id)->update(['is_editable' => 1]);
				$count1 = DB::table('purchase_order_item')->where('purchase_order_id',$id)->where('status',1)->whereNull('deleted_at')->count();
				$count2 = DB::table('purchase_order_item')->where('purchase_order_id',$id)->where('is_transfer',1)->where('status',1)->whereNull('deleted_at')->count();
				if($count1 == $count2)
					DB::table('purchase_order')->where('id', $id)->update(['is_transfer' => 1]);
			} 
		} else if($attributes['document_type']=="MR") {
			$ids = explode(',', $attributes['document_id']);
			foreach($ids as $id) {
				//DB::table('material_requisition')->where('id', $id)->update(['is_editable' => 1]);
				$count1 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('status',1)->whereNull('deleted_at')->count();
				$count2 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('is_transfer',1)->where('status',1)->whereNull('deleted_at')->count();
				if($count1 == $count2)
					DB::table('material_requisition')->where('id', $id)->update(['is_transfer' => 1]);
			} 
		} else if($attributes['document_type']=="SDO") {
			$ids = explode(',', $attributes['document_id']);
			foreach($ids as $id) {
				//DB::table('material_requisition')->where('id', $id)->update(['is_editable' => 1]);
				$count1 = DB::table('supplier_do_item')->where('supplier_do_id',$id)->where('status',1)->whereNull('deleted_at')->count();
				$count2 = DB::table('supplier_do_item')->where('supplier_do_id',$id)->where('is_transfer',1)->where('status',1)->whereNull('deleted_at')->count();
				if($count1 == $count2)
					DB::table('supplier_do')->where('id', $id)->update(['is_transfer' => 1]);
			} 
		}
		
	}
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		foreach($attributes['item_id'] as $key => $value){ 
			
			$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
		}
		return $total;
	}
	
	public function create($attributes)
	{ 	//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
		 DB::beginTransaction();
		 try {
			 
			 /* if( $attributes['curno'] <= $attributes['voucher_no'] ) { 
				$pi = $this->purchase_invoice->where('status',1)->select(DB::raw('voucher_no + 1 AS voucher_no'))->orderBy('id', 'DESC')->first();
				$attributes['voucher_no'] = ($pi)?$pi->voucher_no:$attributes['voucher_no'];
			 } */
			 
			if($this->setInputValue($attributes)) {
				$this->purchase_invoice->status = 1;
				$this->purchase_invoice->created_at = now();
				$this->purchase_invoice->created_by = 1;
				$this->purchase_invoice->fill($attributes)->save();
			}
			
			//invoice items insert
			if($this->purchase_invoice->id && !empty( array_filter($attributes['item_id']))) { 
				
				$line_total = 0; $tax_total = 0; $other_cost = 0; $total_quantity = 0; $total = 0; $cost_sum = $item_total = 0;
				
				if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					$other_cost = $this->getOtherCostSum($attributes['oc_amount'],$attributes['vat_oc']);
					$total_quantity = $this->getTotalQuantity($attributes['quantity']);
					$cost_sum = $this->getCostSum($attributes['oc_amount']);
				}
				
				//calculate total amount....
				if( isset($attributes['is_fc']) ) 
					$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
					
				foreach($attributes['item_id'] as $key => $value){ 
					$purchaseInvoiceItem = new PurchaseInvoiceItem();
					$vat = $attributes['line_vat'][$key];
					$arrResult 	= $this->setItemInputValue($attributes, $purchaseInvoiceItem, $key, $value, $cost_sum, $total, $total_quantity);
					if($arrResult['line_total']) {
						$line_total			   += $arrResult['line_total'];
						$tax_total      	   += $arrResult['tax_total'];
						$othercost_unit      	= $arrResult['othercost_unit'];
						$taxtype				= $arrResult['type'];
						$item_total			   += $arrResult['item_total'];
						
						//CHECK WHEATHER Update Quantity by SDO
						if($this->mod_sdo_qty->is_active==1) {
							if($this->checkSDOLogs($attributes, $key)==false) { //CHECK SDO LOG INSERTED IN LOG TABLE OR NOT...
								//update last purchase cost and cost average....
								$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							} else {
								
								$CostAvg_log = $this->updateLastPurchaseCostCostAvgOtherCostOnTransfer($attributes, $key, $othercost_unit);
								//UPDATE SALES LOG BEFORE PURCHASE
								$attributes['sale_qty'][$key] = $this->setSaleLogUpdate($attributes, $key, $attributes['document_id'], $CostAvg_log,$othercost_unit);
							}
						} else {
							
							//update last purchase cost and cost average....
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							
							//UPDATE SALES LOG BEFORE PURCHASE
							$attributes['sale_qty'][$key] = $this->setSaleLogUpdate($attributes, $key, $attributes['document_id'], $CostAvg_log,$othercost_unit);
						}
						
						$purchaseInvoiceItem->status = 1;
						$inv_item = $this->purchase_invoice->doItem()->save($purchaseInvoiceItem);
						
						//update item transfer status...
						$this->setTransferStatusItem($attributes, $key, $attributes['document_type']);
						
						//SET PURCHASE LOGS..
						if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'add',$othercost_unit))
							$this->updateItemQuantity($attributes, $key);
									
						//check whether suppliers DO or not
						//if($attributes['document_type']!='SDO') {
							//CHECK WHEATHER Update Quantity by SDO
							/* if($this->mod_sdo_qty->is_active==1) {
								if($this->checkSDOLogs($attributes, $key)==false) { //CHECK SDO LOG INSERTED IN LOG TABLE OR NOT...
									if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'add',$othercost_unit))
										$this->updateItemQuantity($attributes, $key);
								}
							} else {
								if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'add',$othercost_unit))
									$this->updateItemQuantity($attributes, $key);
							} */
						//}
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
													          ->whereNull('deleted_at')->select('id')->first();
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lq) ]);
								} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $attributes['locid'][$key][$lk];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $lq;
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
								$itemLocationPI = new ItemLocationPI();
								$itemLocationPI->location_id = $attributes['locid'][$key][$lk];
								$itemLocationPI->item_id = $value;
								$itemLocationPI->unit_id = $attributes['unit_id'][$key];
								$itemLocationPI->quantity = $lq;
								$itemLocationPI->status = 1;
								$itemLocationPI->invoice_id = $inv_item->id;
								$itemLocationPI->save();
							}
						}
					}
					
					//Item default location add...
					if(isset($attributes['default_location']) && ($attributes['default_location']!='') && ($updated == false)) {
							
						$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
														  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
														  ->whereNull('deleted_at')->select('id')->first();
						if($qtys) {
							DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
						} else {
								$itemLocation = new ItemLocation();
								$itemLocation->location_id = $attributes['default_location'];
								$itemLocation->item_id = $value;
								$itemLocation->unit_id = $attributes['unit_id'][$key];
								$itemLocation->quantity = $attributes['quantity'][$key];
								$itemLocation->status = 1;
								$itemLocation->save();
							}
							
						$itemLocationPI = new ItemLocationPI();
						$itemLocationPI->location_id = $attributes['default_location'];
						$itemLocationPI->item_id = $value;
						$itemLocationPI->unit_id = $attributes['unit_id'][$key];
						$itemLocationPI->quantity = $attributes['quantity'][$key];
						$itemLocationPI->status = 1;
						$itemLocationPI->invoice_id = $inv_item->id;
						$itemLocationPI->save();
						
					}
						
					//SALES STOCK ENTRY...
					if(isset($attributes['sales_type']) && $attributes['sales_type']=='ltol') {
						DB::table('item_location')->where('location_id', $attributes['sales_location'])
												  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
												  ->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
					}
						
					//################ Location Stock Entry End ####################
				}
				
				//other cost action...
				if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					foreach($attributes['dr_acnt'] as $key => $value){ 
						$purchaseInvoiceOC = new PurchaseInvoiceOtherCost();
						$arrOC = $this->setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key);
						$purchaseInvoiceOC->status = 1;
						$objOC = $this->purchase_invoice->doOtherCost()->save($purchaseInvoiceOC);
						if($objOC) {
						
							//$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key]) / 100; 
							//$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
							$oc_vat_amt = $arrOC['oc_vat_amt'];
							$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
							
							//set account Cr/Dr amount transaction....
							if($this->setAccountTransaction($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key, $objOC)) {
								if( $this->setAccountTransaction($attributes, $arrOC['oc_amount'], $this->purchase_invoice->id, $type='Dr', 'OC', $key, $objOC) )
									$this->setAccountTransaction($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key, $objOC);
							}
						}
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
					$discount_fc   = $attributes['discount']; //M14..
					$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
					$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
					$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
					$other_cost_fc = $other_cost / $attributes['currency_rate'];
					$subtotal_fc   = $subtotal / $attributes['currency_rate'];
					$discount      = $attributes['discount'] * $attributes['currency_rate']; //M14..
				} else {
					$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0; $other_cost_fc = $subtotal_fc = 0;
					$discount      = (isset($attributes['discount']))?$attributes['discount']:0; //M14..
				}
				
				//update discount, total amount, vat and other cost....
				DB::table('purchase_invoice')
							->where('id', $this->purchase_invoice->id)
							->update(['total'    	  => $line_total,
									  'discount' 	  => $discount, //M14
									  'vat_amount'	  => $tax_total,
									  'net_amount'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'other_cost'	  => $other_cost,
									  'other_cost_fc' => $other_cost_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_amount_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal,
									  'subtotal_fc'	  => $subtotal_fc ]); 
									  
				//Cost Accounting or Purchase and Sales Method .....
				$this->AccountingMethod($attributes, $subtotal, $tax_total, $net_amount, $this->purchase_invoice->id, $taxtype);
				
				/* if(Session::get('cost_accounting')==1) {
					$this->CostAccountingMethod($attributes, $line_total, $tax_total, $supplier_amount, $this->purchase_invoice->id);
				} else {
					$this->PurchaseAndSalesMethod($attributes, $line_total, $tax_total, $supplier_amount, $this->purchase_invoice->id);
				} */
				
				//update is transfer.....
				$this->updateTransferStatus($attributes);
				
				//update voucher no........
				if( ($this->purchase_invoice->id) && ($attributes['curno'] <= $attributes['voucher_no']) ) {  
					//Update voucher no based on department or not...
					if(Session::get('department')==1) { //if dept active...
						 DB::table('account_setting')
							->where('voucher_type_id', 1) 
							->where('department_id', $attributes['department_id'])
							->update(['voucher_no' => $attributes['voucher_no'] + 1]);
					 } else {
						 DB::table('account_setting')
							->where('voucher_type_id', 1)
							->update(['voucher_no' => $attributes['voucher_no'] + 1 ]); //DB::raw('voucher_no + 1')
					 }
					 
				}
				
			}
			
			//TRN no update....
			if($attributes['vat_no']!='') {
				 DB::table('account_master')
						->where('id', $attributes['supplier_id'])
						->update(['vat_no' => $attributes['vat_no'] ]);
			}
			
			//API calls...
			/* $attributes['invoice_id'] = $this->purchase_invoice->id;
			$response = Curl::to($this->api_url.'purchase.php')
						->withData($attributes)
						->asJson()
						->post(); */ //echo '<pre>';print_r($response);exit;
							
			DB::commit();
			return true;
			
		  } catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			  return false;
		  }
		}
		
	}
	
	public function update($id, $attributes)
	{ 	//echo '<pre>';print_r($attributes);exit;
		$this->purchase_invoice = $this->find($id);
		
		DB::beginTransaction();
		try {
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->purchase_invoice->id && !empty( array_filter($attributes['item_id']))) {
				
				$line_total = $tax_total = 0; $cost_value = 0; $other_cost = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = $othercost = 0; //MY27
				$item_total = $othercost_unit = $netcost_unit = $netcostunit = $othercost_unit = 0;
				
				if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					$othercost = $this->getOtherCostSum($attributes['oc_amount'],$attributes['vat_oc']); //MY27
					$total_quantity = $this->getTotalQuantity($attributes['quantity']);
					$cost_sum = $this->getCostSum($attributes['oc_amount']);
				}
				
				//calculate total amount.... linetotal taxtotal
				if( isset($attributes['is_fc']) ) //M14..
					$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
				
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						
						$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
						
						if( isset($attributes['is_fc']) ) {
							
							//UPDATED on MAR 7.... vat_amount
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
							
							if($tax_code=="EX" || $tax_code=="ZR") {
				
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
								
							} else if($attributes['tax_include'][$key]==1) {
								
								$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate'];
								$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
								$itemtotal = ($ln_total - $taxtotal);
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
								
							} else {
								
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
								$othercostunit = 0;
								if( isset($attributes['other_cost']) && $other_cost > 0 ) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
							}
							//MAR 7...
							
							
							/*MAR 7 CMNTED $tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
							$itemtotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
							$taxtotal  = round($tax * $attributes['quantity'][$key],2);
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
							$othercostunit = 0;
							if(isset($attributes['other_cost']) && $attributes['other_cost'] != 0 ) {
								$othercostunit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total'];
								$netcostunit = ($othercost_unit + $attributes['cost'][$key]) * $attributes['currency_rate'];
							} */

						} else {
							
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
							
							if($tax_code=="EX" || $tax_code=="ZR") {
				
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$othercostunit = 0;
								if(isset($attributes['other_cost']) && $attributes['other_cost'] != 0 ) {
									$othercostunit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
								
							} else if($attributes['tax_include'][$key]==1){
				
								$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
								$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
								$itemtotal = $ln_total - $taxtotal;
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
								
							} else {
								
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
							}
						}
						
						//********DISCOUNT Calculation.............
						/* if( isset($attributes['is_fc']) ) //M14..
							$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
						else */
							//$discount = ($attributes['discount']=='')?0:$attributes['discount'];
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
							$taxtotal = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine; //M14 
						} 
						
						$tax_total += $taxtotal;
						$line_total += $linetotal;
						$item_total += $itemtotal;
						$othercost_unit += $othercostunit;
						$netcost_unit += $netcostunit;
						
						$vat = $attributes['line_vat'][$key]; 
						
						$purchaseInvoiceItem = PurchaseInvoiceItem::find($attributes['order_item_id'][$key]);//print_r($purchaseInvoiceItem);exit;//$attributes['order_item_id'][$key]);echo $attributes['order_item_id'][$key].'<pre>';
						$oldqty = $purchaseInvoiceItem->quantity;
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = $costchk = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
						$items['vat']		 = $attributes['line_vat'][$key];
						$items['vat_amount'] = $taxtotal;
						$items['discount'] = $attributes['line_discount'][$key];
						$items['total_price'] = $linetotal;
						$items['tax_code'] 	= $tax_code;
						$items['tax_include'] = $attributes['tax_include'][$key];
						$items['item_total'] = $itemtotal;
						$items['othercost_unit'] = $othercostunit;
						$items['netcost_unit'] = ($netcostunit==0)?$attributes['cost'][$key]:$netcostunit;
						//echo '<pre>';print_r($items);exit;
						$items['unit_price_fc'] = $attributes['cost'][$key];
						$items['vat_amount_fc'] = (isset($attributes['is_fc']))?round(($taxtotal / $attributes['currency_rate']),2):$taxtotal;
						$items['total_price_fc'] = (isset($attributes['is_fc']))?round(($linetotal / $attributes['currency_rate']),2):$linetotal;
						$items['item_total_fc'] = (isset($attributes['is_fc']))?round(($itemtotal / $attributes['currency_rate']),2):$itemtotal;
						
						$exi_item_id = $purchaseInvoiceItem->item_id;//DEC 23 UPDATE...
						$exi_unit_id = $purchaseInvoiceItem->unit_id;//DEC 23 UPDATE...
						$exi_qty = $purchaseInvoiceItem->quantity;
						$exi_price = $purchaseInvoiceItem->unit_price;
						$itemsobj = (object)['item_id' => $exi_item_id, 'unit_id' => $exi_unit_id]; //DEC 23 UPDATE...
						
						$purchaseInvoiceItem->update($items);
						
						if($exi_qty != $attributes['quantity'][$key] || $exi_price != $costchk) {
							//CHECK WHEATHER Update Quantity by SDO
							/* if($this->mod_sdo_qty->is_active==1) {
								if($this->checkSDOLogs($attributes, $key)==false) { //CHECK SDO LOG INSERTED IN LOG TABLE OR NOT...
							
									$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit);
									if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'update', $othercost_unit, $itemsobj))
										$this->updateItemQuantityonEdit($attributes, $key);
								}	
							} else { */
								$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit);
								
								//UPDATE SALES LOG BEFORE PURCHASE
								$attributes['sale_qty'][$key] = $this->setSaleLogUpdate($attributes, $key, $attributes['document_id'], $CostAvg_log,$othercost_unit);
								
								if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'update', $othercost_unit, $itemsobj))
									$this->updateItemQuantityonEdit($attributes, $key);
							//}
						} else {
							//UPDATE COST AVG ON EDIT...  MY27
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit);
						}
						
					//################ Location Stock Entry ####################
					//Item Location specific add....
					$updated = false;
					if(isset($attributes['locqty'][$key])) {
						foreach($attributes['locqty'][$key] as $lk => $lq) {
							if($lq!='') {
								$updated = true;
								$edit = DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->first();
								$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
													          ->whereNull('deleted_at')->select('id')->first();
															  //echo '<pre>';print_r($edit);exit;
								if($edit) {
									
									if($edit->quantity < $lq) {
										$balqty = $lq - $edit->quantity;
										DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
									} else {
										$balqty = $edit->quantity - $lq;
										DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
									}
									
								} else {
									
									$itemLocationPI = new ItemLocationPI();
									$itemLocationPI->location_id = $attributes['locid'][$key][$lk];
									$itemLocationPI->item_id = $value;
									$itemLocationPI->unit_id = $attributes['unit_id'][$key];
									$itemLocationPI->quantity = $lq;
									$itemLocationPI->status = 1;
									$itemLocationPI->invoice_id = $attributes['order_item_id'][$key];
									$itemLocationPI->save();
								}
								
								DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lq]);

							}
						}
					}
					
					//Item default location add...
					if(($attributes['location_id']!='') && ($updated == false)) {
							
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
													          ->whereNull('deleted_at')->select('*')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
								DB::table('item_location_pi')->where('invoice_id', $attributes['order_item_id'][$key] )
															 ->where('location_id', $qtys->location_id)
															 ->where('item_id', $qtys->item_id)
															 ->where('unit_id', $qtys->unit_id)
															 ->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
							} 
							
							$itemLocationPI = new ItemLocationPI();
							$itemLocationPI->location_id = $attributes['location_id'];
							$itemLocationPI->item_id = $value;
							$itemLocationPI->unit_id = $attributes['unit_id'][$key];
							$itemLocationPI->quantity = $attributes['quantity'][$key];
							$itemLocationPI->status = 1;
							$itemLocationPI->invoice_id = $attributes['order_item_id'][$key];
							$itemLocationPI->save();
						}
						
					//################ Location Stock Entry End ####################
					
											
					} else { 
						//new entry...
						$item_total_new = $tax_total_new = $item_total_new = $total = 0;
						if($discount > 0) 
							$total = $this->calculateTotalAmount($attributes);
						
						$vat = $attributes['line_vat'][$key];
						$purchaseInvoiceItem = new PurchaseInvoiceItem();
						$arrResult 		= $this->setItemInputValue($attributes, $purchaseInvoiceItem, $key, $value, $cost_sum, $total, $total_quantity);
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
							
							//UPADTED FEB 28...
							//update last purchase cost and cost average....
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							
							//UPDATE SALES LOG BEFORE PURCHASE
							$attributes['sale_qty'][$key] = $this->setSaleLogUpdate($attributes, $key, $attributes['document_id'], $CostAvg_log,$othercost_unit);
							
							$purchaseInvoiceItem->status = 1;
							$inv_item = $this->purchase_invoice->doItem()->save($purchaseInvoiceItem);
							
							//UPDATED MAR 1
							//check whether suppliers DO or not
							//if($attributes['document_type']!='SDO') {
								if($this->setPurchaseLog($attributes, $key, $this->purchase_invoice->id, $CostAvg_log,'add',$othercost_unit))
									$this->updateItemQuantity($attributes, $key);
							//}
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
													          ->whereNull('deleted_at')->select('id')->first();
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lq) ]);
								} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $attributes['locid'][$key][$lk];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $lq;
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
								$itemLocationPI = new ItemLocationPI();
								$itemLocationPI->location_id = $attributes['locid'][$key][$lk];
								$itemLocationPI->item_id = $value;
								$itemLocationPI->unit_id = $attributes['unit_id'][$key];
								$itemLocationPI->quantity = $lq;
								$itemLocationPI->status = 1;
								$itemLocationPI->invoice_id = $inv_item->id;
								$itemLocationPI->save();
							}
						}
					}
					
					//Item default location add...
					if(($attributes['location_id']!='') && ($updated == false)) {
							
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
													          ->whereNull('deleted_at')->select('id')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
							} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $attributes['location_id'];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $attributes['quantity'][$key];
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
							$itemLocationPI = new ItemLocationPI();
							$itemLocationPI->location_id = $attributes['location_id'];
							$itemLocationPI->item_id = $value;
							$itemLocationPI->unit_id = $attributes['unit_id'][$key];
							$itemLocationPI->quantity = $attributes['quantity'][$key];
							$itemLocationPI->status = 1;
							$itemLocationPI->invoice_id = $inv_item->id;
							$itemLocationPI->save();
							
						}
						
					//################ Location Stock Entry End ####################
					
											
					}
					
				}
			}
			
			//UPDATED MAR 1...
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('purchase_invoice_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					$itm = DB::table('purchase_invoice_item')->where('id', $row)->get();
					//print_r($itm);exit;
					$this->updateLastPurchaseCostAndCostAvgonDelete($itm, $attributes['purchase_invoice_id']);
					
					/* DB::table('item_location')->where('item_id', $itm[0]->item_id)
								->where('location_id', $attributes['location_id'])
								->where('unit_id', $itm[0]->unit_id)
								->update(['quantity' => DB::raw('quantity - '.$itm[0]->quantity) ]);
								
					DB::table('item_location_pi')->where('item_id', $itm[0]->item_id)
								->where('invoice_id', $row)
								->where('location_id', $attributes['location_id'])
								->where('unit_id', $itm[0]->unit_id)
								->update(['status' => 1, 'deleted_at' => now() ]); */
								
					
				}
			}
			
			if($this->setInputValue($attributes)) {
				
				//if($this->purchase_invoice->voucher_date != date('Y-m-d', strtotime($attributes['voucher_date']))) {
					//VOUCHER DATE UPDATE IN LOG...
					DB::table('item_log')->where('document_type','PI')->where('document_id',$this->purchase_invoice->id)
										 ->update(['voucher_date' => date('Y-m-d', strtotime($attributes['voucher_date'])) ]);
				//}
				
				$this->purchase_invoice->modify_at = now();
				$this->purchase_invoice->modify_by = 1;
				$this->purchase_invoice->fill($attributes)->save();
				
			}
			
			//other cost action...
			if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
				
				foreach($attributes['dr_acnt'] as $key => $value){ 
				
				  if($attributes['oc_id'][$key]!='') {
					$purchaseInvoiceOC = PurchaseInvoiceOtherCost::find($attributes['oc_id'][$key]);
					
					$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
					$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
					
					$cost['dr_account_id'] = $attributes['dr_acnt_id'][$key];
					$cost['oc_reference'] = $attributes['oc_reference'][$key];
					$cost['oc_description'] = $attributes['oc_description'][$key];
					$cost['cr_account_id'] = $attributes['cr_acnt_id'][$key];
					$cost['oc_amount']		 = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]):$attributes['oc_amount'][$key];
					$cost['oc_fc_amount'] = $attributes['oc_amount'][$key];
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
						
					$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
					$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$attributes['oc_amount'][$key]:($oc_vat_amt + $attributes['oc_amount'][$key]);
					
					//set account Cr/Dr amount transaction....
					if($this->setAccountTransactionUpdate($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key)) {
						if( $this->setAccountTransactionUpdate($attributes, $attributes['oc_amount'][$key], $this->purchase_invoice->id, $type='Dr', 'OC', $key) )
							$this->setAccountTransactionUpdate($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key);
					}
					
					//.............
				 } else {
					 
						//foreach($attributes['dr_acnt'] as $key => $value){ 
							$purchaseInvoiceOC = new PurchaseInvoiceOtherCost();
							$arrOC = $this->setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key);
							$purchaseInvoiceOC->status = 1;
							$objOC = $this->purchase_invoice->doOtherCost()->save($purchaseInvoiceOC);
							if($objOC) {	
								//$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key]) / 100;
								//$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
								$oc_vat_amt = $arrOC['oc_vat_amt'];
								$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
								
								//set account Cr/Dr amount transaction....
								if($this->setAccountTransaction($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key, $objOC)) {
									if( $this->setAccountTransaction($attributes, $arrOC['oc_amount'], $this->purchase_invoice->id, $type='Dr', 'OC', $key, $objOC) )
										$this->setAccountTransaction($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key, $objOC);
								}
							}
							
						//}
					}

				}
			}
			
			
			//UPDATED MAR 30 REMOVE OTHER COST...
			if($attributes['remove_oc']!='')
			{
				$arrids = explode(',', $attributes['remove_oc']);
				
				foreach($arrids as $row) {
					DB::table('pi_other_cost')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					
					DB::table('account_transaction')->where('voucher_type', 'PI')->where('voucher_type_id', $this->purchase_invoice->id)
								->where('tr_for', $row)->where('other_type','OC')
								->update(['status' => 0, 'deleted_at' => now()]);
					
				}
			}
			//...UPDATED MAR 30 REMOVE OTHER COST
			
			if( isset($attributes['is_fc']) )
				$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
			
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
				$discount_fc   = $attributes['discount']; //M14
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
				$subtotal_fc   = $subtotal / $attributes['currency_rate']; 
				$other_cost_fc = $othercost / $attributes['currency_rate'];
				$discount      = $attributes['discount'] * $attributes['currency_rate']; //M14
			} else {
				$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $other_cost_fc = $subtotal_fc = 0;
				$discount = (isset($attributes['discount']))?$attributes['discount']:0; //M14
			}
			
			//update discount, total amount
			DB::table('purchase_invoice')
						->where('id', $this->purchase_invoice->id)
						->update(['total'    	  => $line_total,
								  'discount' 	  => $discount, //M14
								  'vat_amount'	  => $tax_total,
								  'net_amount'	  => $net_amount,
								  'total_fc' 	  => $total_fc,
								  'discount_fc'   => $discount_fc,
								  'other_cost'	  => $othercost,
								  'other_cost_fc' => $other_cost_fc,
								  'vat_amount_fc' => $tax_fc,
								  'net_amount_fc'  => $net_amount_fc,
								  'subtotal'	  => $subtotal, //CHG
								  'subtotal_fc'	  => $subtotal_fc ]); //CHG
			
			//check whether Cost Accounting method or not.....
			$this->AccountingMethodUpdate($attributes, $subtotal, $tax_total, $net_amount, $this->purchase_invoice->id,$taxtype); //CHNG
			//$this->AccountingMethodUpdate($attributes, $line_total_new, $tax_total_new, $net_amount, $this->purchase_invoice->id);
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			 DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		
		$this->purchase_invoice = $this->purchase_invoice->find($id);
		//inventory update...
		DB::beginTransaction();
		try {
			
			//Update control of PO,SDO...
			if($this->purchase_invoice->document_id > 0) {
				if($this->purchase_invoice->document_type=='PO') {
					$ids = explode(',', $this->purchase_invoice->document_id);
					DB::table('purchase_order')->whereIn('id', $ids)
									->update(['is_transfer' => 0, 'is_editable' => 0]);
				} else if($this->purchase_invoice->document_type=='SDO') {
					$ids = explode(',', $this->purchase_invoice->document_id);
					DB::table('supplier_do')->whereIn('id', $ids)
										->update(['is_transfer' => 0, 'is_editable' => 0]);
										
					DB::table('supplier_do_item')->whereIn('supplier_do_id', $ids)
										->update(['is_transfer' => 0]);
					
					DB::table('item_log')->where('document_type','SDO')->whereIn('document_id',$ids)
										 ->update(['status' => 1,'deleted_at' => '0000-00-00 00:00:00']);
				} 
			}
			
			$items = DB::table('purchase_invoice_item')->where('purchase_invoice_id', $id)->select('item_id','quantity','unit_id','unit_price')->get();
			//echo '<pre>';print_r($items);exit;
			//foreach($items as $item) {
				$this->updateLastPurchaseCostAndCostAvgonDelete($items,$id);
				 DB::table('purchase_invoice_item')->where('purchase_invoice_id', $id)
									  ->update(['status' => 0, 'deleted_at' => now()]);
									  
				/*DB::table('item_location')->where('item_id', $item->item_id)->where('unit_id', $item->unit_id)
										  ->where('location_id', $this->purchase_invoice->location_id)
										  ->update(['quantity' => DB::raw('quantity - '.$item->quantity) ]); */
			//}
			
			//Transaction update....
			DB::table('account_transaction')->where('voucher_type', 'PI')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);
			
			$this->objUtility->tallyClosingBalance( $this->purchase_invoice->supplier_id );
			
			$this->objUtility->tallyClosingBalance( $this->purchase_invoice->account_master_id );
			
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();//DB::table('account_master')->where('master_name', 'VAT INPUT')->where('status', 1)->first();
			if($vatrow) {
				//DB::table('account_master')->where('id', $vatrow->collection_account)->update(['cl_balance' => DB::raw('cl_balance - '.$this->purchase_invoice->vat_amount)]);
				$this->objUtility->tallyClosingBalance($vatrow->collection_account);
			}
			
			$this->purchase_invoice->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}	
		
	}
	
	public function check_invoice($id)
	{
		$count = DB::table('purchase_invoice')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else {
			$row = DB::table('purchase_return')->where('purchase_invoice_id', $id)->whereNull('deleted_at')->count();
			if($row > 0)
				return false;
			else {
				return true;
				/* $count = DB::table('payment_voucher_tr')->where('purchase_invoice_id', $id)->count();
				if($count > 0)
					return false;
				else
					return true; */
			}	
		}
	}
	
	public function suppliersDOList()
	{
		$query = $this->purchase_invoice->where('purchase_invoice.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->select('purchase_invoice.*','am.master_name AS supplier')
					->orderBY('purchase_invoice.id', 'DESC')
					->get();
	}
	
	public function getSDOdata($supplier_id = null)
	{
		if($supplier_id)
			$query = $this->purchase_invoice->where('purchase_invoice.status',1)->where('purchase_invoice.is_transfer',0)->where('purchase_invoice.supplier_id',$supplier_id);
		else
			$query = $this->purchase_invoice->where('purchase_invoice.status',1)->where('purchase_invoice.is_transfer',0);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->select('purchase_invoice.*','am.master_name AS supplier')
					->orderBY('purchase_invoice.id', 'ASC')
					->get();
	}
	
	public function findSDOdata($id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.voucher_no', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->select('purchase_invoice.*','am.master_name AS supplier')
					->orderBY('purchase_invoice.id', 'ASC')
					->first();
	}
	
	public function purchaseInvoiceList2()
	{
		$query = $this->purchase_invoice->where('purchase_invoice.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->select('purchase_invoice.*','am.master_name AS supplier')
					->orderBY('purchase_invoice.id', 'DESC')
					->get();
	}
	
	public function activePurchaseInvoiceList()
	{
		return $this->purchase_invoice->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_invoice->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->purchase_invoice->where('reference_no',$refno)->count();
	}
	
	public function check_invoice_id($invoice_id) { 
		
		return $this->purchase_invoice->where('voucher_no', $invoice_id)->count();
	}
	
	public function getPIdata($did=null)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.status',1);
		if($did)
			$query->where('purchase_invoice.department_id', $did);
				
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->where('purchase_invoice.is_return',0)
					->select('purchase_invoice.*','am.master_name AS supplier')
					->orderBY('purchase_invoice.id', 'ASC')
					->get();
	}
		
	public function getSDOitems($id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.voucher_no',$id);
		
		return $query->join('purchase_invoice_item AS poi', function($join) {
							$join->on('poi.purchase_invoice_id','=','purchase_invoice.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('item_unit AS iu', function($join){
						  $join->on('iu.unit_id','=','poi.unit_id');
					  }) 
					  ->where('poi.status',1)
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','iu.is_baseqty')
					  ->groupBy('poi.id')
					  ->get();
	}
	
	//ED12
	public function getSupplierInvoice($supplier_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return $this->purchase_invoice->where('purchase_invoice.status',1)
									   ->leftJoin('payment_voucher_tr AS PV', function($join){
										   $join->on('PV.purchase_invoice_id','=','purchase_invoice.id');
										   $join->where('PV.deleted_at','=','0000-00-00 00:00:00');
										   $join->where('PV.status','=',1);
									   }) 
									   ->where('purchase_invoice.supplier_id', $supplier_id)
									   ->whereIn('purchase_invoice.amount_transfer',$arr)
									   ->groupBY('purchase_invoice.voucher_no')
									   ->orderBY('purchase_invoice.voucher_date', 'ASC')
									   ->select('purchase_invoice.*','PV.assign_amount')
									   ->get();
			
								   
		} else {
			return $this->purchase_invoice->where('status',1)
									   ->where('supplier_id', $supplier_id)
									   ->whereIn('amount_transfer',$arr)
									   ->orderBY('voucher_date', 'ASC')
									   ->get();
		}
	}
	
	public function getOpenBalances($supplier_id,$mod=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		return DB::table('opening_balance_tr')
								   //->where('tr_type','Dr')
								   ->where('status',1)
								   ->where('account_master_id', $supplier_id)
								   ->where('amount','>',0)
								   ->whereNull('deleted_at')
								   ->whereIn('amount_transfer',$arr)
								   ->orderBY('tr_date', 'ASC')
								   ->select('*','amount AS net_amount')
								   ->get();
	}
	
	//ED12
	public function getPINbills($supplier_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return DB::table('journal')->where('journal.status',1)
								->join('journal_entry AS JE', function($join) {
									$join->on('JE.journal_id','=','journal.id');
								})
								->leftJoin('payment_voucher_tr AS PV', function($join){
								   $join->on('PV.purchase_invoice_id','=','journal.id');
								   $join->where('PV.deleted_at','=','0000-00-00 00:00:00');
								   $join->where('PV.status','=',1);
							   }) 
								//->where('JE.entry_type','Cr')
								->where('journal.deleted_at','=','0000-00-00 00:00:00')
								->where('journal.voucher_type','PIN')
								->where('JE.account_id',$supplier_id)
								->whereIn('journal.is_transfer',$arr)
								->select('journal.*','PV.assign_amount','JE.amount','JE.reference AS reference_no')
								->groupBy('journal.voucher_no')
								->orderBY('journal.voucher_date', 'ASC')
								->get();
								
		} else {
			return DB::table('journal')->where('journal.status',1)
								->join('journal_entry AS JE', function($join) {
									$join->on('JE.journal_id','=','journal.id');
								})
								//->where('JE.entry_type','Cr')
								->where('journal.deleted_at','=','0000-00-00 00:00:00')
								->where('journal.voucher_type','PIN')
								->where('JE.account_id',$supplier_id)
								->whereIn('journal.is_transfer',$arr)
								->select('journal.*','JE.amount','JE.journal_id','JE.reference AS reference_no')
								->orderBY('journal.voucher_date', 'ASC')
								->get();
		}

	}
	
	//May 15.....
	public function getOthrBills($supplier_id,$mod=null,$rvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		return DB::table('other_voucher_tr')->where('account_master_id', $supplier_id)
										 ->whereIn('amount_transfer', $arr)
										 ->where('status',1)->whereNull('deleted_at')
										 ->get();
		
	} //......May 15
	
	
	public function getSplitBills($supplier_id) {
		
		$arr = [0,2];
		
		return DB::table('purchase_split')
						   ->where('supplier_id', $supplier_id)
						   ->whereIn('amount_transfer',$arr)
						   ->where('status',1)
						   ->whereNull('deleted_at')
						   ->orderBY('voucher_date', 'ASC')
						   ->orderBY('id', 'ASC')
						   ->get();
								   
	}
	
	
	public function getOtherCostBills($supplier_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return DB::table('pi_other_cost')->where('pi_other_cost.status',1)
							   ->join('purchase_invoice AS PI', function($join) {
								   $join->on('PI.id','=','pi_other_cost.purchase_invoice_id');
							   })
								->where('pi_other_cost.deleted_at','0000-00-00 00:00:00')
								->where('pi_other_cost.cr_account_id',$supplier_id)
								->whereIn('pi_other_cost.is_transfer',$arr)
								->select('pi_other_cost.*','PI.voucher_no','PI.voucher_date')
								->get();
								
		} else {
			
			return DB::table('pi_other_cost')->where('pi_other_cost.status',1)
							   ->join('purchase_invoice AS PI', function($join) {
								   $join->on('PI.id','=','pi_other_cost.purchase_invoice_id');
							   })
								->where('pi_other_cost.deleted_at','0000-00-00 00:00:00')
								->where('pi_other_cost.cr_account_id',$supplier_id)
								->whereIn('pi_other_cost.is_transfer',$arr)
								->select('pi_other_cost.*','PI.voucher_no','PI.voucher_date')
								->get();
		}

	}
	
	
	public function getInvoice($attributes)
	{
		$invoice = $this->purchase_invoice->where('purchase_invoice.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_invoice.supplier_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','purchase_invoice.currency_id');
								   })
								   ->leftJoin('terms AS TR', function($join) {
									   $join->on('TR.id','=','purchase_invoice.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','purchase_invoice.*','AM.address','AM.city','AM.state','AM.contact_name','AM.vat_no','AM.phone','C.name AS currency','TR.description AS terms')
								   ->orderBY('purchase_invoice.id', 'ASC')
								   ->first();
								   
		$items = $this->purchase_invoice->where('purchase_invoice.id', $attributes['document_id'])
								   ->join('purchase_invoice_item AS PI', function($join) {
									   $join->on('PI.purchase_invoice_id','=','purchase_invoice.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   //->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','purchase_invoice.id','IM.item_code','U.unit_name')
								   ->orderBY('PI.id')
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	}
	
	public function findPOdata($id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->join('account_master AS am2', function($join){
						  $join->on('am2.id','=','purchase_invoice.account_master_id');
					  })
					->select('purchase_invoice.*','am.master_name AS supplier','am2.master_name AS account')
					->orderBY('purchase_invoice.id', 'ASC')
					->first();
	}
	
	public function getItems($id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.id',$id);
		
		return $query->join('purchase_invoice_item AS poi', function($join) {
							$join->on('poi.purchase_invoice_id','=','purchase_invoice.id');
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
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing')
					  ->orderBY('poi.id')
					  ->groupBY('poi.id')
					  ->get();
	}
	
	public function getOrderHistory($supplier_id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.supplier_id',$supplier_id)->where('purchase_invoice.status',1);
		
		return $query->join('purchase_invoice_item AS poi', function($join) {
							$join->on('poi.purchase_invoice_id','=','purchase_invoice.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->where('poi.status',1)
					  ->select('poi.*','u.unit_name','im.item_code','purchase_invoice.voucher_date','purchase_invoice.reference_no')->get();
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_invoice->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->purchase_invoice->where('voucher_no',$refno)->count();
	}
	
	public function getAdvance($supplier_id)
	{
		$qry1 = DB::table('payment_voucher_entry')
								   ->join('payment_voucher', 'payment_voucher.id', '=', 'payment_voucher_entry.payment_voucher_id')
								   ->where('payment_voucher_entry.entry_type','Dr')
								   ->where('payment_voucher_entry.status',1)
								   ->where('payment_voucher_entry.account_id', $supplier_id)
								   ->whereIn('payment_voucher_entry.amount_transfer',[0,2])
								   ->where('payment_voucher_entry.is_onaccount',1)
								   ->orderBY('payment_voucher_entry.id', 'ASC')
								   ->select('payment_voucher_entry.entry_type','payment_voucher_entry.id','payment_voucher_entry.balance_amount','payment_voucher_entry.reference',
											'payment_voucher.voucher_no','payment_voucher.voucher_date','payment_voucher_entry.amount AS net_total',DB::raw('"PV" AS type'));
								  
								   
		$qry2 = DB::table('journal_entry')
								   ->join('journal', 'journal.id', '=', 'journal_entry.journal_id')
								   ->where('journal_entry.entry_type','Dr')
								   ->where('journal_entry.status',1)
								   ->where('journal_entry.account_id', $supplier_id)
								   ->whereIn('journal_entry.amount_transfer',[0,2])
								   ->where('journal_entry.is_onaccount',1)
								   ->orderBY('journal_entry.id', 'ASC')
								   ->select('journal_entry.entry_type','journal_entry.id','journal_entry.balance_amount','journal_entry.reference',
											'journal.voucher_no','journal.voucher_date','journal_entry.amount AS net_total',DB::raw('"JV" AS type'));
								   
		return $qry1->union($qry2)->get();
	}
	
	public function getInvoiceReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->purchase_invoice->where('purchase_invoice.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_invoice.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_invoice.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								    ->select('AM.master_name AS supplier','AM.vat_no','purchase_invoice.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->purchase_invoice->where('purchase_invoice.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_invoice.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_invoice.job_id');
								   })
								   ->select('AM.master_name AS supplier','AM.vat_no','purchase_invoice.*','JM.name AS job')
								   ->orderBY('purchase_invoice.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$department = (Session::get('department')==1)?$attributes['department_id']:null;
		
		$query = $this->purchase_invoice
						->join('purchase_invoice_item AS POI', function($join) {
							$join->on('POI.purchase_invoice_id','=','purchase_invoice.id');
						})
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_invoice.supplier_id');
						})
						->where('POI.status',1);

				if($attributes['isimport']==1)
					$query->where('purchase_invoice.is_import',1);
				else if($attributes['isimport']==0)
					$query->where('purchase_invoice.is_import',0);
				else if($attributes['isimport']==2)
					$query->whereIn('purchase_invoice.is_import',[0,1]);

				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('purchase_invoice.voucher_date', array($date_from, $date_to));
				}
				
				if($department)
					$query->where('purchase_invoice.department_id', $department);
					
		$query->select('purchase_invoice.voucher_no','purchase_invoice.reference_no','purchase_invoice.total','purchase_invoice.vat_amount','purchase_invoice.amount_transfer','POI.tax_code','purchase_invoice.discount',
							  'purchase_invoice.voucher_date','POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no','purchase_invoice.net_amount','purchase_invoice.subtotal');
						
		if(isset($attributes['type']))
			return $query->groupBy('purchase_invoice.id')->get()->toArray();
		else
			return $query->groupBy('purchase_invoice.id')->get();
	}
	
	public function getOtherCost($id)
	{
		$query = $this->purchase_invoice->where('purchase_invoice.id',$id);
		
		return $query->join('pi_other_cost AS pi', function($join) {
							$join->on('pi.purchase_invoice_id','=','purchase_invoice.id');
						} )
					  ->join('account_master AS im', function($join){
						  $join->on('im.id','=','pi.dr_account_id');
					  })
					  ->leftJoin('account_master AS im2', function($join){
						  $join->on('im2.id','=','pi.cr_account_id');
					  })
					  ->where('pi.status',1)
					  ->select('pi.*','im.id AS dr_id','im.master_name AS dr_name','im2.id AS cr_id','im2.master_name AS cr_name')->get();
	}
	
	public function getItemLocation($id) {
		
		return DB::table('item_location_pi')->where('invoice_id', $id)->where('status',1)->whereNull('deleted_at')->get();
	}
	
	public function InvoiceLogProcess()
	{
		//API ...
		$location = DB::table('location')->where('is_default',1)->where('status',1)->whereNull('deleted_at')->select('id')->first();
		$response = Curl::to($this->api_url.'silog-process.php')
					->withData( array('id' => $location->id))
					->get();
					
		if($response) {
			$data = json_decode($response, true);
			//echo '<pre>';print_r($data);exit;
			if(isset($data['invoice'])) {
				
				foreach($data['invoice'] as $inv) {
					$res = $this->createByLogProcess($inv,$location->id);
					//print_r($res);exit;
				}
				return true;
			} 
		} 
	}
	
	private function createByLogProcess($attributes,$location_id) 
	{
		//GET PI voucher...
		$voucher = DB::table('account_setting')->where('status',1)->where('voucher_type_id',1)->select('id','voucher_no','dr_account_master_id')->first();
		
		//GET SUPPLIER ID
		//$supplier = DB::table('parameter3')->where('location_id',$attributes['invoice']['location_id'])->select('account_id')->first();
		$supplier = DB::table('parameter3')->where('location_id',$location_id)->select('account_id')->first();
		//print_r($supplier);exit;
		
		$this->purchase_invoice->voucher_id = $voucher->id;
		$this->purchase_invoice->voucher_no = $voucher->voucher_no;
		$this->purchase_invoice->reference_no = $attributes['invoice']['reference_no'] = $voucher->voucher_no;
		$this->purchase_invoice->voucher_date = $attributes['invoice']['voucher_date'];
		$this->purchase_invoice->document_type = 'PI';
		$this->purchase_invoice->supplier_id = $supplier->account_id;
		$this->purchase_invoice->description = $attributes['invoice']['description'];
		$this->purchase_invoice->account_master_id = $attributes['invoice']['account_master_id'] = $voucher->dr_account_master_id;
		$this->purchase_invoice->total = $attributes['invoice']['total'];
		$this->purchase_invoice->vat_amount = $attributes['invoice']['vat_amount'];
		$this->purchase_invoice->net_amount = $attributes['invoice']['net_total'];
		$this->purchase_invoice->status = 1;
		$this->purchase_invoice->created_at = now();
		$this->purchase_invoice->subtotal = $attributes['invoice']['subtotal'];
		$this->purchase_invoice->location_id = $location_id;
		$this->purchase_invoice->fill($attributes)->save(); //fill($attributes)
		$purchase_invoice_id = $this->purchase_invoice->id;
		
		if($purchase_invoice_id) {
			
			foreach($attributes['items'] as $row){
				
				$purchaseInvoiceItem = new PurchaseInvoiceItem();
				$purchaseInvoiceItem->purchase_invoice_id = $purchase_invoice_id;
				$purchaseInvoiceItem->item_id = $row['item_id'];
				$purchaseInvoiceItem->item_name = $row['item_name'];
				$purchaseInvoiceItem->unit_id = $row['unit_id'];
				$purchaseInvoiceItem->quantity = $row['quantity'];
				$purchaseInvoiceItem->unit_price = $row['unit_price'];
				$purchaseInvoiceItem->vat = $row['vat'];
				$purchaseInvoiceItem->vat_amount = $row['vat_amount'];
				$purchaseInvoiceItem->total_price = $row['line_total'];
				$purchaseInvoiceItem->status = 1;
				$purchaseInvoiceItem->tax_code = 'SR';
				$purchaseInvoiceItem->item_total = $row['item_total'];
				$this->purchase_invoice->doItem()->save($purchaseInvoiceItem);
				
				//UPDATE ITEM QUANTITY....
				$item = DB::table('item_unit')->where('itemmaster_id', $row['item_id'])
									  ->where('unit_id', $row['unit_id'])
									  ->first();
									  
				if($item) {
					$qty = $row['quantity'];
					
						$packing = ($item->is_baseqty==1)?1:$item->packing;
						$baseqty = ($qty * $packing);
						DB::table('item_unit')
							->where('itemmaster_id', $row['item_id'])
							->where('is_baseqty',1)
							->update([ 'cur_quantity' => $item->cur_quantity + $baseqty,
										'received_qty' => DB::raw('received_qty + '.$baseqty) ]);
										
					if($item->is_baseqty==0){ 
						DB::table('item_unit')
								->where('id', $item->id)
								->update([ 'cur_quantity' => $item->cur_quantity + $qty,
											'received_qty' => DB::raw('received_qty + '.$qty) ]);
					}
						
				}
				//UPDATE ITEM QUANTITY END....
				
				//################ Location Stock Entry ####################
					$updated = false;
					//Item default location add...
					if(($location_id!='') && ($updated == false)) {
							
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $location_id)
															  ->where('item_id', $row['item_id'])->where('unit_id', $row['unit_id'])
													          ->whereNull('deleted_at')->select('id')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$row['quantity']) ]);
							} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $location_id;
									$itemLocation->item_id = $row['item_id'];
									$itemLocation->unit_id = $row['unit_id'];
									$itemLocation->quantity = $row['quantity'];
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
							$itemLocationPI = new ItemLocationPI();
							$itemLocationPI->location_id = $location_id;
							$itemLocationPI->item_id = $row['item_id'];
							$itemLocationPI->unit_id = $row['unit_id'];
							$itemLocationPI->quantity = $row['quantity'];
							$itemLocationPI->status = 1;
							$itemLocationPI->invoice_id = $purchase_invoice_id;
							$itemLocationPI->save();
							
						}
					//################ Location Stock Entry End ####################
				}
				
			//ACCOUNT TRANSACTIONS....
			$invoice_id = $attributes['invoice_id'];
			$attributes = $attributes['invoice'];
			$attributes['supplier_id'] = $supplier->account_id;
			//Debit Stock in Hand
			if( $this->setAccountTransaction($attributes, $attributes['subtotal'], $purchase_invoice_id, $type='Dr', $amount_type='LNTOTAL') ) {
			
				//Debit VAT Input
				if( $this->setAccountTransaction($attributes, $attributes['vat_amount'], $purchase_invoice_id, $type='Dr', $amount_type='VAT') ) {
			
					//Credit Supplier Accounting
					if( $this->setAccountTransaction($attributes, $attributes['net_total'], $purchase_invoice_id, $type='Cr', $amount_type='NTAMT') ) {
					
						$this->setAccountTransaction($attributes, $discount=0, $purchase_invoice_id, $type='Cr', $amount_type='DIS');
					}
				}
			}
			
			//update voucher no........
			 DB::table('account_setting')
				->where('id', $voucher->id)
				->update(['voucher_no' => $voucher->voucher_no + 1 ]);
			
			//UPDATE STATUS...
			$response = Curl::to($this->api_url.'silog-process.php')
						->withData( array('id' => $invoice_id))
						->asJson()
						->put();
				
		}
		
		return true;
		
	}
	
	//CLOSING BALANCE UPDATE...
	public function tallyClosingBalance($id)
	{
		$this->updateAccountTally( $this->groupAccount($this->updateUtilityById($id)) );
	}
	
	public function updateUtilityById($id)
	{
		$date = DB::table('parameter1')->select('from_date','to_date')->first();
		
		return $query = DB::table('account_master')->where('account_master.status',1)
						->where('account_master.id', $id)
						->join('account_transaction', 'account_transaction.account_master_id', '=', 'account_master.id')
						->where('account_transaction.voucher_type','!=','OBD')
						->where('account_transaction.status',1)
						->where('account_transaction.deleted_at','0000-00-00 00:00:00')
						->where('account_master.status',1)
						->where('account_master.deleted_at','0000-00-00 00:00:00')
						->where('account_transaction.deleted_at','0000-00-00 00:00:00')
						->whereBetween('account_transaction.invoice_date',[$date->from_date, $date->to_date])
						->select('account_master.id','account_master.master_name','account_master.cl_balance','account_master.category',
								 'account_transaction.transaction_type','account_transaction.amount','account_master.op_balance','account_transaction.invoice_date')
						->orderBy('account_master.id','ASC')
						->get();
			
	}
	
	protected function groupAccount($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	
	protected function updateAccountTally($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$arraccount = array(); 
			$dramount = $cramount = 0;
			foreach($result as $row) {
				$cl_balance = $row->cl_balance;
				$account_id = $row->id;
				if($row->transaction_type=='Dr') {
					$amountD = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$dramount += $amountD;
				} else {
					$amountC = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$cramount += $amountC;
				}
			}
			
			$amount = $dramount - $cramount;
			//$amount = ($amount < 0)?(-1*$amount):$amount;
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				$this->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}
	
	public function updateClosingBalance($account_id, $amount)
	{
		 DB::table('account_master')
					->where('id', $account_id)
					->update(['cl_balance' => $amount]);
	}

	
	public function purchaseInvoiceListCount()
	{
		//CHECK DEPARTMENT.......
		$deptid = (Session::get('department')==1)?Auth::user()->department_id:0;
		
		$query = $this->purchase_invoice->where('purchase_invoice.status',1);
		if($deptid!=0)
			$query->where('purchase_invoice.department_id', $deptid);
			
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} )
					->count();
	}
	
	public function purchaseInvoiceList($type,$start,$limit,$order,$dir,$search,$dept=null)
	{
		//CHECK DEPARTMENT.......
		$deptid = (Session::get('department')==1)?Auth::user()->department_id:0;
		
		$query = $this->purchase_invoice->where('purchase_invoice.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_invoice.supplier_id');
						} );
						
				if($deptid!=0) //dept chk
					$query->where('purchase_invoice.department_id', $deptid);
				else {
					if($dept!='' && $dept!=0) {
						$query->where('purchase_invoice.department_id', $dept);
					}
				}
					
				if($search) {
					$query->where(function($qry) use($search) {
						$qry->where('purchase_invoice.voucher_no','LIKE',"%{$search}%")
							->orWhere('purchase_invoice.reference_no', 'LIKE',"%{$search}%")
							->orWhere('am.master_name', 'LIKE',"%{$search}%");
					});
					/* $query->where('purchase_invoice.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('purchase_invoice.reference_no', 'LIKE',"%{$search}%")
						  ->orWhere('am.master_name', 'LIKE',"%{$search}%"); */
				}
				
				$query->select('purchase_invoice.*','am.master_name AS supplier')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	public function getPurchaseIitems($id, $attributes)
	{
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$query = $this->purchase_invoice->where('purchase_invoice.supplier_id',$id);
		
		$query->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_invoice.supplier_id');
						})
					->join('purchase_invoice_item AS poi', function($join) {
							$join->on('poi.purchase_invoice_id','=','purchase_invoice.id');
					  })
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
					  ->where('poi.status',1);
					  
				if($date_from!='' && $date_to!='')
					$query->whereBetween('purchase_invoice.voucher_date', [$date_from, $date_to]);
					  
						
		return $query->select('poi.*','u.unit_name','im.item_code','iu.packing','iu.is_baseqty','poi.item_total AS line_total',
							 'purchase_invoice.voucher_no','purchase_invoice.voucher_date','AM.master_name')
					 ->groupBy('poi.id')
					 ->orderBY('poi.id')
					 ->get();
	}
	
	public function getTransactionList($attributes) 
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->purchase_invoice
								   ->join('purchase_invoice_item AS PI', function($join) {
									   $join->on('PI.purchase_invoice_id','=','purchase_invoice.id');
								   })
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_invoice.supplier_id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->where('purchase_invoice.status',1);
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('purchase_invoice.voucher_date',[$date_from, $date_to]);
					
		$result = $qry->select('AM.master_name AS supplier','purchase_invoice.id','purchase_invoice.voucher_no','purchase_invoice.voucher_date',
							   'IM.item_code','IM.description','PI.quantity','PI.unit_price','PI.vat_amount','PI.total_price','PI.othercost_unit',
							   'PI.netcost_unit')
								->orderBY('purchase_invoice.voucher_date', 'ASC')
								->get();
								   
		return $result;
	}
	
	private function getVatAccounts($department_id=null) {
		
		if(Session::get('department')==1 && $department_id!=null) {
			return DB::table('vat_department')->where('department_id', $department_id)->first();
		} else {
			return DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();
		}
	}
	
	private function checkSDOLogs($attributes, $key) {
		
		$ids = explode(',', $attributes['document_id']);
		$row = DB::table('item_log')->where('document_type','SDO')
						->whereIn('document_id', $ids)
						->where('item_id',$attributes['item_id'][$key])
						->where('status',1)->whereNull('deleted_at')
						->select('id', DB::raw('SUM(quantity) AS quantity'))
						->groupBY('item_id')
						->first();
		
		return ($row)?true:false;
	}
	
	
	private function updateLastPurchaseCostCostAvgOtherCostOnTransfer($attributes, $key, $other_cost)
	{
		//$pid = $attributes['document_id'];
		$pid = explode(',', $attributes['document_id']);
		
		//DISABLE SDO TRANSACTION LOG....
		DB::table('item_log')->where('document_type','SDO')->whereIn('document_id',$pid)->update(['status'=>0,'deleted_at'=>now()]);
		
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->whereNull('deleted_at')
										->where(function ($query) use($pid) {
											$query->whereNotIn('document_id',$pid)
												  ->orWhere('document_type','!=','SDO');
										})
										->select('cur_quantity','pur_cost','unit_cost')
										->get();
		
		$itmcost = (isset($attributes['is_fc']))? ($attributes['quantity'][$key] * $attributes['cost'][$key] * $attributes['currency_rate']) : $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * ($log->pur_cost==0)?$log->unit_cost:$log->pur_cost);//FEB25
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->where('unit_id', $attributes['unit_id'][$key])
				->update(['last_purchase_cost' => $cost + $other_cost,
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
	}
	
	private function setSaleLogUpdate($attributes, $key, $document_id, $cost_avg, $other_cost)
	{	
		//FEB25  
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 0)
										->where('cost_avg', 0)
										->whereNull('deleted_at')
										->select('id','quantity')
										->get();
		$quantity = 0;
		foreach($itmlogs as $log) {
			$quantity += $log->quantity;
			DB::table('item_log')->where('id',$log->id)
						->update([
							 'cost_avg' => $cost_avg,
							 'pur_cost' => (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost,
							 'sale_cost' => (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost
						 ]);
		}	 						
		/* DB::table('item_log')->where('document_type','SDO')
						->where('document_id', $document_id)
						->where('item_id', $attributes['item_id'][$key])
						->where('unit_id', $attributes['unit_id'][$key])
						->update([
							 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
							 'cost_avg' => $cost_avg,
							 'pur_cost' => (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost
						 ]); */
							
		return $quantity;
	}
	
}

