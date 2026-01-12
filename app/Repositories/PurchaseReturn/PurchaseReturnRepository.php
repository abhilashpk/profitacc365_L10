<?php namespace App\Repositories\PurchaseReturn;

use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\AccountTransaction;
use App\Models\ItemStock;
use App\Models\PurchaseInvoice;
use App\Models\ItemLocationPR;
use App\Models\PurchaseInvoiceItem;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;

use Config;
use DB;
use Session;
use Auth;


class PurchaseReturnRepository extends AbstractValidator implements PurchaseReturnInterface {
	
	public $objUtility;
	
	protected $purchase_return;
	
	protected static $rules = [];
	
	public function __construct(PurchaseReturn $purchase_return) {
		$this->purchase_return = $purchase_return;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->purchase_return->get();
	}
	
	public function find($id)
	{
		return $this->purchase_return->where('id', $id)->first();
	}
	
	//set input fields values
	private function setPurchaseInvoiceInputValue($attributes, $purchaseInvoice)
	{
		$purchaseInvoice->voucher_id = 0;//previous year
		$purchaseInvoice->voucher_no = $attributes['purchase_invoice_id'];
		$purchaseInvoice->reference_no = $attributes['reference_no'];
		$purchaseInvoice->voucher_date = date('Y-m-d', strtotime($attributes['voucher_date']));
		$purchaseInvoice->document_type = 4; //puchase invoice
		$purchaseInvoice->supplier_id = $attributes['supplier_id'];
		$purchaseInvoice->document_id = '';
		$purchaseInvoice->job_id = $attributes['job_id'] ?? 0;
		$purchaseInvoice->description = $attributes['description'] ?? null;
		$purchaseInvoice->account_master_id = $attributes['account_master_id'];
		$purchaseInvoice->is_fc = isset($attributes['is_fc'])?1:0;
		$purchaseInvoice->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id'] ?? 0:'';
		$purchaseInvoice->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate'] ?? 0:'';
		$purchaseInvoice->status = 1;
		$purchaseInvoice->created_at = date('Y-m-d H:i:s');
		$purchaseInvoice->created_by = Auth::User()->id;
		//$this->sales_return->location_id = $attributes['location_id'];
		$purchaseInvoice->save();
		
		return $purchaseInvoice->id;
		
	}
	
	private function setPurchaseInvoiceItemInputValue($attributes, $purchaseInvoiceItem, $key, $value, $purchase_invoice_id,$total)
	{
		$othercost_unit = $netcost_unit = 0;
		$type = 'tax_exclude';
		
		$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
		$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
		$tax_total  = $tax * $attributes['quantity'][$key];
		$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['packing'][$key]) - (int)$attributes['line_discount'][$key] );
		
		$purchaseInvoiceItem->purchase_invoice_id = $purchase_invoice_id;
		$purchaseInvoiceItem->item_id = $attributes['item_id'][$key];
		$purchaseInvoiceItem->unit_id = $attributes['unit_id'][$key];
		$purchaseInvoiceItem->item_name = $attributes['item_name'][$key];
		$purchaseInvoiceItem->quantity = $attributes['quantity'][$key];
		$purchaseInvoiceItem->unit_price = $attributes['cost'][$key];
		$purchaseInvoiceItem->vat = $attributes['line_vat'][$key];
		$purchaseInvoiceItem->vat_amount = $tax_total;
		$purchaseInvoiceItem->discount = (float)$attributes['line_discount'][$key] ?? 0;
		$purchaseInvoiceItem->total_price = $attributes['line_total'][$key];
		
		if( isset($attributes['other_cost'])) {
			$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
			$netcost_unit = $othercost_unit + $attributes['cost'][$key];
		}
				
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'othercost_unit' => $othercost_unit, 'type' => $type,'item_total' => $item_total);

	}
	
	private function setPurchaseLog($attributes, $key, $document_id)
	{
		DB::table('item_stock')->insert(['document_type' => 'PI',
										 'document_id'   => $document_id,
										 'item_id' 	  => $attributes['item_id'][$key],
										 'unit_id'    => $attributes['unit_id'][$key],
										 'quantity'   => $attributes['quantity'][$key],
										 'status'     => 1,
										 'created_at' => date('Y-m-d H:i:s'),
										 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
										 'created_by' => Auth::User()->id]);
		return true;
	}
	
	private function setPurchaseReturnLog($attributes, $key, $document_id)
	{
		
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->first();
									  
		$balance_qty = $item->cur_quantity - $attributes['quantity'][$key];
		
		DB::table('item_stock')->insert(['document_type' => 'PR',
										 'document_id'   => $document_id,
										 'item_id' 	  => $attributes['item_id'][$key],
										 'unit_id'    => $attributes['unit_id'][$key],
										 'quantity'   => $attributes['quantity'][$key],
										 'status'     => 1,
										 'created_at' => date('Y-m-d H:i:s'),
										 'created_by' =>Auth::User()->id,
										 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
										 'balance_qty' => $balance_qty,
										 'action' => 'add'
										 ]);
										 
		if($attributes['quantity'][$key]==$attributes['actual_quantity'][$key]) {								 
			DB::table('item_stock')
					->where('document_id', $attributes['purchase_invoice_id'])->where('document_type', 'PI')->where('item_id', $attributes['item_id'][$key])
					->update(['is_return' => 1]);
		}
		
		return true;
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])->where('unit_id', $attributes['unit_id'][$key])
				->update(['cur_quantity' => DB::raw('cur_quantity + '.$attributes['quantity'][$key] ),
						  'received_qty' => $attributes['quantity'][$key]
						]);
		return true;
	}
	
	private function updateItemReturnQuantity($attributes, $key) 
	{
		if($attributes['quantity'][$key]==$attributes['actual_quantity'][$key]) {
			$row = DB::table('item_stock')->where('item_id', $attributes['item_id'][$key])
					->where('document_type', 'PI')->where('document_id', '!=', $attributes['purchase_invoice_id'])
					->orderBy('id','DESC')->select('unit_cost')->first();
			$last_purchase_cost = ($row)?$row->unit_cost:0;
		}
		
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])->where('is_baseqty', 1)
				->update(['cur_quantity' => DB::raw('cur_quantity - '.$attributes['quantity'][$key] * $attributes['packing'][$key] ),
						  'received_qty' => DB::raw('received_qty - '.$attributes['quantity'][$key] * $attributes['packing'][$key])
						 ]);
		return true;
	}
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null)
	{
		
		$cr_acnt_id = $dr_acnt_id = '';
		if($amount!=0) {
			
			if($amount_type=='VAT' || $amount_type=='VATOC') {
				$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
				
				if(isset($attributes['is_import']) && $amount_type=='VAT') { //if vat import is checked....
					if($vatrow) {
						$cr_acnt_id = $vatrow->vatinput_import;
					}
					
					$vatrowout = DB::table('account_master')->where('id', $vatrow->vatoutput_import)->where('status', 1)->first(); //$vatrowout = DB::table('account_master')->where('master_name', 'VAT OUTPUT on IMPORT')->where('status', 1)->first();
					if($vatrowout) {
						DB::table('account_transaction')
							->insert([  'voucher_type' 		=> 'PR',
										'voucher_type_id'   => $voucher_id,
										'account_master_id' => $vatrowout->id,
										'transaction_type'  => 'Dr',
										'amount'   			=> $amount,
										'status' 			=> 1,
										'created_at' 		=> date('Y-m-d H:i:s'),
										'created_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'] ?? $attributes['voucher_no'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'reference_from'	=> $attributes['reference_no'],
										'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
										'version_no'		=> $attributes['version_no']
										]);
					
						$this->objUtility->tallyClosingBalance($vatrowout->id);
						
						/* if($vatrowout->cl_balance >= 0)
							DB::table('account_master')->where('id',$vatrowout->id)->update(['cl_balance' => $vatrowout->cl_balance - $amount]);
						else 
							DB::table('account_master')->where('id',$vatrowout->id)->update(['cl_balance' => $vatrowout->cl_balance + $amount]); */
					}
					
				} else {
					
					
					if($vatrow) {
						$cr_acnt_id = $vatrow->collection_account;
					}
				}
				
			} else if($amount_type == 'LNTOTAL') {
				$cr_acnt_id = $attributes['account_master_id'];
			} else if($amount_type == 'NTAMT') {
				$dr_acnt_id = $attributes['supplier_id'];
			} else if($amount_type == 'OC') {
				$dr_acnt_id = $attributes['cr_acnt_id'][$key];
				$cr_acnt_id = $attributes['dr_acnt_id'][$key];
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					if($vatrow)	
						$dr_acnt_id = $vatrow->purdis_acid;
					else {
						$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
						$dr_acnt_id = $vatrow->account_id;
					}
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
					$dr_acnt_id = $vatrow->account_id;
				}
			}
			
			if(isset($attributes['is_import']) && $amount_type=='NTAMT') { 
				$amount = $amount - $attributes['vat'];
			}
			
			$trfor = ($amount_type=='VATOC')?1:0;
			
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PR',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> ($amount_type=='OC')?$attributes['oc_description'][$key]:$attributes['description'] ?? $attributes['voucher_no'],
								'reference'			=> ($amount_type=='OC')?$attributes['oc_reference'][$key]:$attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'tr_for'			=> $trfor,
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'version_no'		=> $attributes['version_no']
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
			/* $res = DB::table('account_master')->where('id', ($type=='Cr')?$cr_acnt_id:$dr_acnt_id)->select('cl_balance','id')->first();
			if($res->cl_balance >= 0)
				DB::table('account_master')->where('id',$res->id)->update(['cl_balance' => $res->cl_balance - $amount]);
			else 
				DB::table('account_master')->where('id',$res->id)->update(['cl_balance' => $res->cl_balance + $amount]); */
			
		}
		
		return true;
	}
	
		
	private function AccountingMethodUpdate($attributes, $line_total, $tax_total, $net_amount, $purchase_return,$taxtype)
	{
		$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		if($taxtype=='tax_include' && $discount > 0) {
			$temp = $net_amount;
			$net_amount = $line_total;
			$line_total = $temp + $discount;
		} else if($taxtype=='tax_exclude' && $discount > 0) {
			$line_total = $line_total + $discount;
		}
		
		//Debit Stock in Hand
		if( $this->setAccountTransactionUpdate($attributes, $line_total, $purchase_return, $type='Cr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransactionUpdate($attributes, $tax_total, $purchase_return, $type='Cr', $amount_type='VAT') ) {
		
				//Credit Supplier Accounting
				if( $this->setAccountTransactionUpdate($attributes, $net_amount, $purchase_return, $type='Dr', $amount_type='NTAMT') ) {
				
					$this->setAccountTransactionUpdate($attributes, $discount, $purchase_return, $type='Dr', $amount_type='DIS');
				}
			}
		}
	}
	
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null)
	{
		
		$cr_acnt_id = $dr_acnt_id = '';
		if($amount!=0) {
			
			if($amount_type=='VAT' || $amount_type=='VATOC') {
				$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
				
				if(isset($attributes['is_import']) && $amount_type=='VAT') { //if vat import is checked....
					if($vatrow) {
						$cr_acnt_id = $vatrow->vatinput_import;
					}
					
					$vatrowout = DB::table('account_master')->where('id', $vatrow->vatoutput_import)->where('status', 1)->first(); //$vatrowout = DB::table('account_master')->where('master_name', 'VAT OUTPUT on IMPORT')->where('status', 1)->first();
					if($vatrowout) {
						DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('account_master_id', $vatrowout->id) //CHNG
							->where('voucher_type', 'PR')
							->update([  'amount'   			=> $amount,
										'modify_at' 		=> date('Y-m-d H:i:s'),
										'modify_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'reference_from'    => $attributes['reference_no'],
										'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount
										]);
					
						$this->objUtility->tallyClosingBalance($vatrowout->id);
					}
					
				} else {
					if($vatrow) {
						$cr_acnt_id = $vatrow->collection_account;
					}
				}
				
			} else if($amount_type == 'LNTOTAL') {
				$cr_acnt_id = $attributes['account_master_id'];
			} else if($amount_type == 'NTAMT') {
				$dr_acnt_id = $attributes['supplier_id'];
			} else if($amount_type == 'OC') {
				$dr_acnt_id = $attributes['cr_acnt_id'][$key];
				$cr_acnt_id = $attributes['dr_acnt_id'][$key];
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					if($vatrow)	
						$dr_acnt_id = $vatrow->purdis_acid;
					else {
						$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
						$dr_acnt_id = $vatrow->account_id;
					}
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
					$dr_acnt_id = $vatrow->account_id;
				}
				
				//IF DISCOUNT NOT ADDED PREVIOUS.. FEB21
				if($attributes['discount_old']==0) {
					DB::table('account_transaction')
						->insert([  'voucher_type' 		=> 'PR',
									'voucher_type_id'   => $voucher_id,
									'account_master_id' => $dr_acnt_id,
									'transaction_type'  => 'Dr',
									'amount'   			=> $amount,
									'status' 			=> 1,
									'created_at' 		=> date('Y-m-d H:i:s'),
									'created_by' 		=> Auth::User()->id,
									'description' 		=> $attributes['description'],
									'reference'			=> $attributes['voucher_no'],
									'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
									'reference_from'	=> $attributes['reference_no'],
									'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
									'is_fc'				=> isset($attributes['is_fc'])?1:0,
									'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
									]);
									
				} 
			}
			
			if(isset($attributes['is_import']) && $amount_type=='NTAMT') { 
				$amount = $amount - $attributes['vat'];
			}
			
			$trfor = ($amount_type=='VATOC')?1:0;
			
			DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', ($type=='Cr')?$cr_acnt_id:$dr_acnt_id) //CHNG
					->where('voucher_type', 'PR')
					->update([  'amount'   			=> $amount,
								'modify_at' 		=> date('Y-m-d H:i:s'),
								'modify_by' 		=> Auth::User()->id,
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount
						]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		} else { //FEB21
			
			//Remove DISCOUNT.... FEB21
			if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					if($vatrow)	
						$dr_acnt_id = $vatrow->purdis_acid;
					else {
						$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
						$dr_acnt_id = $vatrow->account_id;
					}
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
					$dr_acnt_id = $vatrow->account_id;
				}
				
				//Remove DISCOUNT....		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $dr_acnt_id) //CHNG
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'PR')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						
				$this->objUtility->tallyClosingBalance($dr_acnt_id);
			}
		}
		
		return true;
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->purchase_return->voucher_id = $attributes['voucher_id'];
		$this->purchase_return->voucher_no = $attributes['voucher_no'];
		$this->purchase_return->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->purchase_return->supplier_id = $attributes['supplier_id'];
		$this->purchase_return->purchase_invoice_id = $attributes['purchase_invoice_id'];
		$this->purchase_return->job_id = $attributes['job_id'];
		$this->purchase_return->description = $attributes['description'];
		$this->purchase_return->account_master_id = $attributes['account_master_id'];
		$this->purchase_return->is_fc = isset($attributes['is_fc'])?1:0;
		$this->purchase_return->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->purchase_return->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:'';
		$this->purchase_return->purchase_invoice_no = isset($attributes['purchase_invoice_no'])?$attributes['purchase_invoice_no']:$attributes['purchase_invoice_id'];
		$this->purchase_return->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description']:'';
		
		
		return true;
	}
	
	private function setItemInputValue($attributes, $purchaseReturnItem, $key, $value, $other_cost, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
		$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
		if( isset($attributes['is_fc']) ) {
			
			$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key] ) * $attributes['currency_rate'];
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			
			if( isset($attributes['other_cost'])) {
				$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total']; //$attributes['other_cost']
				$netcost_unit = ($othercost_unit + $attributes['cost'][$key]) * $attributes['currency_rate'];
			}
			
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
				
				if( isset($attributes['other_cost'])) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$idiscount = ((int)$attributes['line_discount'][$key]!='')?(int)$attributes['line_discount'][$key]:0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $idiscount;
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
				if( isset($attributes['other_cost'])) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
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
		
		$purchaseReturnItem->purchase_return_id = $this->purchase_return->id;
		$purchaseReturnItem->item_id = $attributes['item_id'][$key];
		$purchaseReturnItem->unit_id = $attributes['unit_id'][$key];
		$purchaseReturnItem->item_name = $attributes['item_name'][$key];
		$purchaseReturnItem->quantity = $attributes['quantity'][$key];
		$purchaseReturnItem->unit_price = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$purchaseReturnItem->vat		    = $attributes['line_vat'][$key];
		$purchaseReturnItem->vat_amount 	= $tax_total;
		$purchaseReturnItem->discount = (int)$attributes['line_discount'][$key];
		$purchaseReturnItem->total_price = $line_total;
		$purchaseReturnItem->othercost_unit = $othercost_unit;
		$purchaseReturnItem->netcost_unit = ($netcost_unit==0)?$attributes['cost'][$key]:$netcost_unit;
		$purchaseReturnItem->tax_code 	= $tax_code;
		$purchaseReturnItem->tax_include = $attributes['tax_include'][$key];
		$purchaseReturnItem->item_total = $item_total;
		$purchaseReturnItem->width = isset($attributes['item_wit'][$key])?$attributes['item_wit'][$key]:0;
		$purchaseReturnItem->length = isset($attributes['item_lnt'][$key])?$attributes['item_lnt'][$key]:0;
		$purchaseReturnItem->mp_qty = isset($attributes['mpquantity'][$key])?$attributes['mpquantity'][$key]:0;
		
		
		//COST DIFFERENCE CALCULATING FOR AC ENTRY....
		$cost_diff = 0;
		
		if(isset($attributes['actual_cost'][$key]) && $attributes['actual_cost'][$key]!=$attributes['cost'][$key]) {
			
			$cost_diff += (float)$attributes['actual_cost'][$key] - ((float)$attributes['cost'][$key] * (float)$attributes['quantity'][$key]);
		}
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total,'othercost_unit' => $othercost_unit, 'type' => $type, 'item_total' => $item_total, 'cost_diff' => $cost_diff);
	}
	
	private function setTransferStatusItem($attributes, $key)
	{
		//if quantity partially deliverd, update pending quantity.
		if(isset($attributes['purchase_invoice_item_id'])) {
			if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
				if( isset($attributes['purchase_invoice_item_id'][$key]) ) {
					$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
					//update as partially delivered.
					DB::table('purchase_invoice_item')
								->where('id', $attributes['purchase_invoice_item_id'][$key])
								->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
				}
			} else {
					//update as completely delivered.
					DB::table('purchase_invoice_item')
								->where('id', $attributes['purchase_invoice_item_id'][$key])
								->update(['balance_quantity' => 0, 'is_transfer' => 1]);
			}
		}
	}
	
	//Cost Accounting Method function............
	private function AccountingMethod($attributes, $line_total, $tax_total, $net_amount, $purchase_return,$taxtype)
	{
		$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		if($taxtype=='tax_include' && $discount > 0) {
			$temp = $net_amount;
			$net_amount = $line_total;
			$line_total = $temp + $discount;
		} else if($taxtype=='tax_exclude' && $discount > 0) {
			$line_total = $line_total + $discount;
		}
		
		//Debit Stock in Hand
		if( $this->setAccountTransaction($attributes, $line_total, $purchase_return, $type='Cr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransaction($attributes, $tax_total, $purchase_return, $type='Cr', $amount_type='VAT') ) {
		
				//Credit Supplier Accounting
				if( $this->setAccountTransaction($attributes, $net_amount, $purchase_return, $type='Dr', $amount_type='NTAMT') ) {
				
					$this->setAccountTransaction($attributes, $discount, $purchase_return, $type='Dr', $amount_type='DIS');
				}
			}
		}
		
		//Debit Stock in Hand
		/* $this->setAccountTransaction($attributes, $line_total, $purchase_return, $type='Cr', $amount_type='LNTOTAL');
		
		//Debit VAT Input
		$this->setAccountTransaction($attributes, $tax_total, $purchase_return, $type='Cr', $amount_type='VAT');
		
		//Credit Supplier Accounting
		$this->setAccountTransaction($attributes, $net_amount, $purchase_return, $type='Dr', $amount_type='NTAMT'); */
		
		//set account Cr/Dr discount amount transaction....**********needed to implement
	}
	
	
	
	private function setTransferStatusQuote($attributes)
	{
		if($attributes['purchase_invoice_id']) {
			$row1 = DB::table('purchase_invoice_item')->where('purchase_invoice_id', $attributes['purchase_invoice_id'])->count();
			$row2 = DB::table('purchase_invoice_item')->where('purchase_invoice_id', $attributes['purchase_invoice_id'])->where('is_transfer',1)->count();
			if($row1==$row2) {
				$is_transfer = (isset($attributes['pi_amount'])==$attributes['net_amount'])?1:2;
				DB::table('purchase_invoice')
						->where('id', $attributes['purchase_invoice_id'])
						->update(['amount_transfer' => $is_transfer, 'is_return' => 1,'is_editable' => 1]);
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
	
	private function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $other_cost)
	{
		$prev = DB::table('item_stock')->where('document_type', 'PI')->where('document_id', $attributes['purchase_invoice_id'])
									   ->where('action','!=','delete')->where('item_id',$attributes['item_id'][$key])
									   ->orderBY('id', 'desc')->first();
		
		if($prev) { 
			$retqty = ($attributes['actual_quantity'][$key] * $attributes['packing'][$key]) - ($attributes['quantity'][$key] * $attributes['packing'][$key]);
			
			$total_qty = ($prev->prev_quantity * $prev->packing) + $retqty;
			$total_cost = ($prev->prev_quantity * $prev->packing * $prev->prev_purchase_cost) + ($retqty * $attributes['cost'][$key]);
			$cost_avg = ($total_qty > 0)?round($total_cost / $total_qty,2):$total_qty;
			
			$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
			
			DB::table('item_unit')
					->where('itemmaster_id', $prev->item_id)
					->where('is_baseqty', 1)
					->update(['last_purchase_cost' => ($retqty==0)?$prev->prev_purchase_cost:$cost,
							  'cost_avg'		   => round($cost_avg + $other_cost, 2),
							]);
							
			return array( 'prev_qty' => $prev->prev_quantity, 'prev_purcost' => $prev->prev_purchase_cost, 'cost_avg' => round($cost_avg + $other_cost, 2),'prev_cost_avg' => $prev->prev_cost_avg );
		
		} else 
			return null;
	}
	
	/* private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $mod=null, $diffcost=null)
	{
		
		if($type == 'Cr') {
			$account_id = $attributes['account_cr'];
			$amount = ($mod=='CD')?$diffcost:$amount;
		} else if($type == 'Dr') {
			if($mod=='CD') {
				if(Session::get('department')==1) {
					$acc = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
					$dr_acnt_id = $acc->costdif_acid;
				} else {
					$acc = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->select('account_id')->first();
					$account_id = $acc->account_id;
				}
				$amount = $diffcost;
			} else
				$account_id = $attributes['account_dr'];
		}
		
		DB::table('account_transaction')
				->where('voucher_type_id', $voucher_id)
				->where('account_master_id', $account_id)
				->where('voucher_type', 'STO')
				->update([  'amount'   			=> $amount,
							'modify_at' 		=> date('Y-m-d H:i:s'),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
						]);
						
		
		$this->objUtility->tallyClosingBalance($account_id);
						
		return true;
	} */
	
	
	
	public function create($attributes)
	{ //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
			DB::beginTransaction();
			try {
			//check whether previous year transaction or not....
			/*if($attributes['is_prior']) { 
				$purchaseInvoice = new PurchaseInvoice();
				$purchase_invoice_id = $this->setPurchaseInvoiceInputValue($attributes, $purchaseInvoice);
					//order items insert
					if($purchase_invoice_id && !empty( array_filter($attributes['item_id']))) {
						
						$line_total = 0; $tax_total = 0; $other_cost = 0; $total_quantity = 0; $total = 0; $cost_sum = $item_total = 0;
						
						if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
							$other_cost = $this->getOtherCostSum($attributes['oc_amount'],$attributes['vat_oc']);
							$total_quantity = $this->getTotalQuantity($attributes['quantity']);
							$cost_sum = $this->getCostSum($attributes['oc_amount']);
						}
						
						//calculate total amount....
						$discount = (isset($attributes['discount']))?$attributes['discount']:0;
						if($discount > 0) 
							$total = $this->calculateTotalAmount($attributes);
				
						foreach($attributes['item_id'] as $key => $value) { 
							$purchaseInvoiceItem = new PurchaseInvoiceItem();
							$vat = $attributes['line_vat'][$key];
							$arrResult = $this->setInputValue($attributes, $purchaseInvoiceItem, $key, $value, $purchase_invoice_id,$total);
							if($arrResult['line_total']) {
								$line_total			   += $arrResult['line_total'];
								$tax_total      	   += $arrResult['tax_total'];
								$othercost_unit      	= $arrResult['othercost_unit'];
								$taxtype				= $arrResult['type'];
								$item_total			   += $arrResult['item_total'];
								$purchaseInvoiceItem->status = 1;
								$inv_item = $purchaseInvoiceItem->save();
								
								//update item transfer status...
								$this->setTransferStatusItem($attributes, $key);
									
								//stock update section............................
								//check whether stock updation enable or not in settings.. ****************needed to implement********************
								if($this->setPurchaseLog($attributes, $key, $purchase_invoice_id))
									$this->updateItemQuantity($attributes, $key);
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
										} 
										
										$itemLocationPR = new ItemLocationPR();
										$itemLocationPR->location_id = $attributes['locid'][$key][$lk];
										$itemLocationPR->item_id = $value;
										$itemLocationPR->unit_id = $attributes['unit_id'][$key];
										$itemLocationPR->quantity = $lq;
										$itemLocationPR->status = 1;
										$itemLocationPR->invoice_id = $inv_item->id;
										$itemLocationPR->save();
									}
								}
							}
							
							//Item default location add...
							if(($attributes['location_id']!='') && ($updated == false)) {
									
									$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($qtys) {
										DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									}
									
									$itemLocationPR = new ItemLocationPR();
									$itemLocationPR->location_id = $attributes['location_id'];
									$itemLocationPR->item_id = $value;
									$itemLocationPR->unit_id = $attributes['unit_id'][$key];
									$itemLocationPR->quantity = $attributes['quantity'][$key];
									$itemLocationPR->status = 1;
									//$itemLocationPR->invoice_id = $inv_item->id;
									$itemLocationPR->save();
									
								}
								
							//################ Location Stock Entry End ####################
						}
						
						//other cost action...
						if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
							foreach($attributes['dr_acnt'] as $key => $value){ 
								$purchaseInvoiceOC = new PurchaseInvoiceOtherCost();
								if($this->setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key)) {
									$purchaseInvoiceOC->status = 1;
									$this->purchase_invoice->doOtherCost()->save($purchaseInvoiceOC);
									
									$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key]) / 100;
									$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
									//set account Cr/Dr amount transaction....
									if($this->setAccountTransaction($attributes, $oc_net_aount, $this->purchase_invoice->id, $type='Cr', 'OC', $key)) {
										if( $this->setAccountTransaction($attributes, $attributes['oc_amount'][$key], $this->purchase_invoice->id, $type='Dr', 'OC', $key) )
											$this->setAccountTransaction($attributes, $oc_vat_amt, $this->purchase_invoice->id, $type='Dr', 'VATOC', $key);
									}
								}
							}
						}
				
						$subtotal = (float)$line_total - (float)$discount;
						$taxtype='';
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
							$other_cost_fc = $other_cost / $attributes['currency_rate'];
							$subtotal_fc   = $subtotal / $attributes['currency_rate'];
							
						} else {
							$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0; $other_cost_fc = $subtotal_fc = 0;
						}
						//update discount, total amount, vat and other cost....
						DB::table('purchase_return')
									->where('id', $purchase_return->id)
									->update(['total'    	  => $line_total,
											  'discount' 	  => (isset($attributes['discount']))?$attributes['discount']:0,
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
											  
						//set account Cr/Dr amount transaction....
						$this->AccountingMethod($attributes, $subtotal, $tax_total, $net_amount, $purchase_invoice_id, $taxtype);

						//update voucher no........
						if( ($purchase_invoice_id) && ($attributes['curno'] <= $attributes['voucher_no']) ) { 
							 DB::table('account_setting')
								->where('id', $attributes['voucher_id'])
								->update(['voucher_no' => $attributes['voucher_no'] + 1 ]);//$this->sales_invoice->id
						}
						
					}
				
			} else { *///purchase return section..........
				$attributes['version_no'] = 1;
				//VOUCHER NO LOGIC.....................
				// 2️⃣ Get the highest numeric part from voucher_master
				$maxNumeric = DB::table('purchase_return')
					->where('deleted_at', '0000-00-00 00:0:00')
					//->where('department_id', $departmentId)
					->where('status', 1)
					->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
					->value('max_no');
				
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($attributes['voucher_id'], $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
					try {
								
							if($this->setInputValue($attributes)) {
								$this->purchase_return->status = 1;
								$this->purchase_return->created_at = date('Y-m-d H:i:s');
								$this->purchase_return->created_by = Auth::User()->id;
								$this->purchase_return->save();
								$saved = true; // success ✅
							}

						} catch (\Illuminate\Database\QueryException $ex) {

							// Check if it's a duplicate voucher number error
							if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
								strpos($ex->getMessage(), 'duplicate key value') !== false) {

								$maxNumeric = DB::table('purchase_return')
									->where('deleted_at', '0000-00-00 00:0:00')
									//->where('department_id', $departmentId)
									->where('status', 1)
									->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
									->value('max_no');
								
								$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
								
								$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($attributes['voucher_id'], $maxNumeric, $dept, $attributes['voucher_no']);

								$retryCount++;
							} else {
								throw $ex; // rethrow if different DB error
							}
						}
				}

				
				
				//order items insert
				if($this->purchase_return->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = 0; $tax_total = 0; $cost_value = 0; $item_total = $total = $cost_sum = $total_quantity = $costdiff = 0;
					
					//calculate total amount....
					$discount = (isset($attributes['discount']))?$attributes['discount']:0;
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
						
					foreach($attributes['item_id'] as $key => $value) { 
						$purchaseReturnItem = new PurchaseReturnItem();
						$vat = $attributes['line_vat'][$key]; //CHG
						$arrResult 	= $this->setItemInputValue($attributes, $purchaseReturnItem, $key, $value, $cost_sum, $total, $total_quantity);
						if($arrResult['line_total']) {
							$line_total			     += $arrResult['line_total'];
							$tax_total      	     += $arrResult['tax_total'];
							//$cost_value 			 += $arrResult['cost_value'];
							$taxtype				  = $arrResult['type'];
							$item_total				 += $arrResult['item_total']; //CHG
							//$cost_value 		   += $arrResult['cost_value'];
							$costdiff				+= $arrResult['cost_diff'];
							
							
							$purchaseReturnItem->status = 1;
							$inv_item = $this->purchase_return->doItem()->save($purchaseReturnItem);
							
							//UPDATED FEB 28..
							//update last purchase cost and cost average....
							//$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit=0);
						
							//update item transfer status...
							$this->setTransferStatusItem($attributes, $key);
						
							//stock update section............................
							/* if($this->setPurchaseReturnLog($attributes, $key, $this->purchase_return->id))
							{
								$this->updateItemReturnQuantity($attributes, $key);
							} */
							$attributes['item_row_id'][$key] = $inv_item->id; //OCT24
							
							$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);
								$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
									$logid = $this->setSaleLog($attributes, $key, $this->purchase_return->id, $CostAvg_log, $sale_cost, 'add' );
							
						}
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									//$lcqty =  $lq * $attributes['packing'][$key];
									$lcqty = $lq;
                            		if($attributes['packing'][$key]=="1") 
                            		    $lcqty = $lq;
                            		else {
                            		   $pkgar = explode('-', $attributes['packing'][$key]);
                            		   if($pkgar[0] > 0)
                            		        $lcqty = ($lq *  $pkgar[1]) / $pkgar[0];
                            		}
                            		
									$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($qtys) {
										DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lcqty) ]);
									} else {
										$itemLocation = new ItemLocation();
										$itemLocation->location_id = $attributes['locid'][$key][$lk];
										$itemLocation->item_id = $value;
										$itemLocation->unit_id = $attributes['unit_id'][$key];
										$itemLocation->quantity = $lcqty;
										$itemLocation->status = 1;
										$itemLocation->save();
									}
									
									$itemLocationPR = new ItemLocationPR();
									$itemLocationPR->location_id = $attributes['locid'][$key][$lk];
									$itemLocationPR->item_id = $value;
									$itemLocationPR->unit_id = $attributes['unit_id'][$key];
									$itemLocationPR->quantity = $lcqty;
									$itemLocationPR->status = 1;
									$itemLocationPR->invoice_id = $inv_item->id;
									$itemLocationPR->logid = $logid;
									$itemLocationPR->qty_entry = $lq;
									$itemLocationPR->save();
								}
							}
						}
						
						//Item default location add...
						if(($attributes['location_id']!='') && ($updated == false)) {
								
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
																  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								
								//$lcqty =  $attributes['quantity'][$key] * $attributes['packing'][$key];
								$lcqty = $attributes['quantity'][$key];
                        		if($attributes['packing'][$key]=="1") 
                        		    $lcqty = $attributes['quantity'][$key];
                        		else {
                        		   $pkgar = explode('-', $attributes['packing'][$key]);
                        		   if($pkgar[0] > 0)
                        		        $lcqty = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
                        		}
                        		
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lcqty) ]);
								} 
									
								$itemLocationPR = new ItemLocationPR();
								$itemLocationPR->location_id = $attributes['location_id'];
								$itemLocationPR->item_id = $value;
								$itemLocationPR->unit_id = $attributes['unit_id'][$key];
								$itemLocationPR->quantity = $lcqty;
								$itemLocationPR->status = 1;
								$itemLocationPR->invoice_id = $inv_item->id;
								$itemLocationPR->qty_entry = $attributes['quantity'][$key];
								$itemLocationPR->save();
								
							}
							
						//################ Location Stock Entry End ####################
						
						
						//MAY25 BATCH NO ENTRY............
        				if($attributes['batchNos'][$key]!='' && $attributes['qtyBatchs'][$key]!='') {
        				    
        				    $batchArr = explode(',', $attributes['batchNos'][$key]);
        				    $qtyArr = explode(',', $attributes['qtyBatchs'][$key]);
        				    
        				    foreach($batchArr as $bkey => $bval) {

                			    DB::table('batch_log')
            				                ->insert([
            				                    'batch_id' => $bval,
            				                    'item_id' => $value,
            				                    'document_type' => 'PR',
            				                    'document_id' => $this->purchase_return->id,
            				                    'doc_row_id' => $inv_item->id,
            				                    'quantity' => $qtyArr[$bkey],
            				                    'trtype' => 0,
            				                    'invoice_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
            				                    'log_id' => $logid,
            				                    'created_at' => date('Y-m-d h:i:s'),
            				                    'created_by' => Auth::User()->id
            				                    ]);

                    			DB::table('item_batch')
                    			                    ->where('id',$bval)
                    				                ->update([
                    				                    'quantity' => DB::raw('quantity - '.$qtyArr[$bkey])
                    				                ]);	                
                    				                
        				    }
        				
        				}
        				//.....END BATCH ENTRY
					
						
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
						
					} else {
						$total_fc = $discount_fc = $tax_fc = $net_amount_fc = $vat_fc = $subtotal_fc = 0; 
					}
					
					//update discount, total amount, vat and other cost....
					DB::table('purchase_return')
								->where('id', $this->purchase_return->id)
								->update([ //'voucher_no'	  => $attributes['voucher_no'],
									       'total'    	  => $line_total,
										  'discount' 	  => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat_amount'	  => $tax_total,
										  'net_amount'	  => $net_amount,
										  'total_fc' 	  => $total_fc,
										  'discount_fc'   => $discount_fc,
										  'vat_amount_fc' => $tax_fc,
										  'net_amount_fc'  => $net_amount_fc,
										  'subtotal'	  => $subtotal, //CHG
										  'subtotal_fc'	  => $subtotal_fc ]); //CHG
					
					//update invoice  balance amount..
					$pirow = DB::table('purchase_invoice')->where('id', $attributes['purchase_invoice_id'])->select('balance_amount')->first();
					if($pirow){
					if($pirow->balance_amount==0) {
						DB::table('purchase_invoice')
								->where('id', $attributes['purchase_invoice_id'])
								->update(['balance_amount' => DB::raw('balance_amount + net_amount - '.$net_amount) ]);
					} else {
						DB::table('purchase_invoice')
								->where('id', $attributes['purchase_invoice_id'])
								->update(['balance_amount' => DB::raw('balance_amount - '.$net_amount) ]);
					}
				}
					
					//Cost Accounting or Purchase and Sales Method.....
					$this->AccountingMethod($attributes, $subtotal, $tax_total, $net_amount, $this->purchase_return->id,$taxtype);
					
					//COST DIFFERENCE AC ENTRY...
					if($costdiff > 0)
						$this->CostDifferenceEntry($attributes,$costdiff,$this->purchase_return->id);
					
					
					/* if(Session::get('cost_accounting')==1) {
						$this->CostAccountingMethod($attributes, $line_total, $tax_total, $net_amount, $this->purchase_return->id);
					} else {
						$this->PurchaseAndSalesMethod($attributes, $line_total, $tax_total, $net_amount, $this->purchase_return->id);
					} */
					
					//update purchase return transfer status....
					if(isset($attributes['purchase_invoice_id'])) 
						$this->setTransferStatusQuote($attributes);
							
					
				}
			
		  //}
				DB::commit();
				return true;
			} catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			  return false;
		  }
		}
		//throw new ValidationException('purchase_return validation error12!', $this->getErrors());
	}
	
	
	public function update($id, $attributes)
	{	//echo '<pre>';print_r($attributes);exit;
		$this->purchase_return = $this->find($id);
		$line_total = $tax_total = $cost_value = $line_total_new = $tax_total_new = $costdiff = $lntotal = 0;
		
		DB::beginTransaction();
		try {

			//FIND CURRENT VERSION	 
			$voucher_type = 'PR';
			$currentVersion = DB::table('account_transaction')->where('voucher_type', $voucher_type)->where('voucher_type_id', $id)->max('version_no');
			$newVersion = $currentVersion + 1;
			$attributes['version_no'] = $newVersion;

			//SOFT DELETE OLD VERSION
			DB::table('account_transaction')->where('voucher_type', $voucher_type)->where('voucher_type_id', $id)
						->update([
									'status' => 0,
									'deleted_at' => date('Y-m-d h:i:s'),
									'deleted_by'  => Auth::User()->id,
								]);

			if($this->purchase_return->id && !empty( array_filter($attributes['item_id']))) {
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						
						if( isset($attributes['is_fc']) ) {
							$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
							$lntotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key] ) * $attributes['currency_rate'];
							$txtotal  = round($tax * $attributes['quantity'][$key],2);
							
						} else if($attributes['tax_include'][$key]==1){
				
							$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
							$txtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
							$lntotal = $ln_total - $txtotal;
				
						} else {
						
							$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
							$idiscount = ((int)$attributes['line_discount'][$key]!='')?(int)$attributes['line_discount'][$key]:0;
							$lntotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $idiscount;
							$txtotal  = $tax * $attributes['quantity'][$key];
						}
						
						$tax_total += $txtotal;
						$line_total += $lntotal;
						$taxtype = 'tax_exclude';
						
						$purchaseReturnItem = PurchaseReturnItem::find($attributes['order_item_id'][$key]);
						$oldqty = $purchaseReturnItem->quantity;
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
						$items['vat']		 = $attributes['line_vat'][$key];
						$items['vat_amount'] = $txtotal;
						$items['discount'] = (int)$attributes['line_discount'][$key];
						$items['total_price'] = $lntotal;
						$items['width'] = isset($attributes['item_wit'][$key])?$attributes['item_wit'][$key]:0;
						$items['length'] = isset($attributes['item_lnt'][$key])?$attributes['item_lnt'][$key]:0;
						$items['mp_qty'] = isset($attributes['mpquantity'][$key])?$attributes['mpquantity'][$key]:0;
						$purchaseReturnItem->update($items);
						
						 $sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);//exit;
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, 0); //JUL10
								$this->setSaleLog($attributes, $key, $this->purchase_return->id, $CostAvg_log, $sale_cost, 'update' );
										
						//################ Location Stock Entry ####################
						/* if($attributes['location_id']!='') {
							$idloc = DB::table('item_location')
									->where('location_id', $attributes['location_id'])
									->where('item_id', $value)
									->where('unit_id', $attributes['unit_id'][$key])
									->where('status', 1)
									->where('deleted_at', '0000-00-00 00:00:00')
									->select('id')->first();//echo $oldqty; print_r($idloc);exit;
									
							if($idloc) {
								if($oldqty < $attributes['quantity'][$key]) {
									$balqty = $attributes['quantity'][$key] - $oldqty;
									DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
								} else {
									$balqty = $oldqty - $attributes['quantity'][$key];
									DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
								}
							} 
						} */
						//################ Location Stock Entry End ####################
						
					} else { //new entry...
					
						$purchaseReturnItem = new PurchaseReturnItem();
						$arrResult 		= $this->setItemInputValue($attributes, $purchaseReturnItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total_new 		 += $arrResult['line_total'];
							$tax_total_new      	 += $arrResult['tax_total'];
							
							$taxtype				  = $arrResult['type'];
							
							$line_total			     += $arrResult['line_total'];
							$tax_total      	     += $arrResult['tax_total'];
							$purchaseReturnItem->status = 1;
							$itemObj = $this->purchase_return->purchaseReturnItemAdd()->save($purchaseReturnItem);
							
							$attributes['item_row_id'][$key] = $itemObj->id; //OCT24
							
							$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);
								$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
									$this->setSaleLog($attributes, $key, $this->purchase_return->id, $CostAvg_log, $sale_cost, 'add' );
						}
						
						
					
						//################ Location Stock Entry ####################
						/* if($attributes['location_id']!='') {
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
													          ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
							} 
						} */
						//################ Location Stock Entry End####################
					}
					
				}
			}
			
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					$res = DB::table('purchase_return_item')->where('id',$row)->first();
					DB::table('purchase_return_item')->where('id', $row)->update(['status' => 0]);
					
					DB::table('item_location')->where('item_id', $res->item_id)
								->where('location_id', $attributes['location_id'])
								->where('unit_id', $res->unit_id)
								->update(['quantity' => DB::raw('quantity + '.$res->quantity) ]);
				}
			}
			
			if($this->setInputValue($attributes)) {
				$this->purchase_return->modify_at = date('Y-m-d H:i:s');
				$this->purchase_return->modify_by = Auth::User()->id;
				$this->purchase_return->fill($attributes)->save();
			}
			$this->purchase_return->fill($attributes)->save();
			
			$discount = (isset($attributes['discount']))?$attributes['discount']:0;
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
				$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
				$subtotal_fc	   = $subtotal / $attributes['currency_rate'];
				
			} else {
				$total_fc = $discount_fc = $tax_fc = $net_amount_fc = $vat_fc = $subtotal_fc = 0; 
			}
			
			/* $net_amount = $line_total + $tax_total - $attributes['discount'];
			if( isset($attributes['is_fc']) ) {
				$total_fc 	   = $line_total / $attributes['currency_rate'];
				$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = $tax_total / $attributes['currency_rate'];
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
			} else {
				$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0;
			} */
			
			//update discount, total amount
			DB::table('purchase_return')
					->where('id', $this->purchase_return->id)
					->update(['total'    	  => $line_total,
							  'discount' 	  => $attributes['discount'],
							  'vat_amount'	  => $tax_total,
							  'net_amount'	  => $net_amount,
							  'total_fc' 	  => $total_fc,
							  'discount_fc'   => $discount_fc,
							  'vat_amount_fc' => $tax_fc,
							  'net_amount_fc'  => $net_amount_fc,
							  'subtotal'	  => $subtotal, //CHG
							  'subtotal_fc'	  => $subtotal_fc ]); //CHG
			
			//update invoice  balance amount..  JUL10
			DB::table('purchase_invoice')
							->where('id', $attributes['purchase_invoice_id'])
							->update(['balance_amount' => $net_amount]);

			$this->AccountingMethod($attributes, $subtotal, $tax_total, $net_amount, $this->purchase_return->id, $taxtype);
			
			//COST DIFFERENCE AC ENTRY...
			if($costdiff > 0)
				$this->CostDifferenceEntry($attributes,$costdiff,$this->purchase_return->id);
						
			DB::commit();
			return true;
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	
	public function delete($id)
	{
		$this->purchase_return = $this->purchase_return->find($id);
		DB::beginTransaction();
		try {
			if($this->purchase_return->purchase_invoice_id!='') {
				DB::table('purchase_invoice')
							->where('id', $this->purchase_return->purchase_invoice_id)
							->update(['is_return' => 0,'is_editable' => 0,'amount_transfer' => 0, 'balance_amount' => 0]);
			}
						
			//inventory update...
			$items = DB::table('purchase_return_item')->where('purchase_return_id', $id)->select('item_id','unit_id','quantity')->get();
			
			$this->updateLastPurchaseCostAndCostAvgonDelete($items,$id);
			
			/* foreach($items as $item) {
				DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
									  ->update(['cur_quantity' => DB::raw('cur_quantity - '.$item->quantity)]);
									  
				DB::table('item_location')->where('item_id', $item->item_id)->where('unit_id', $item->unit_id)
										  ->where('location_id', $this->purchase_return->location_id)
										  ->update(['quantity' => DB::raw('quantity - '.$item->quantity) ]);
			} */
			
			//Transaction update....
			DB::table('account_transaction')->where('voucher_type', 'PR')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id ]);
			
			DB::table('account_master')->where('id', $this->purchase_return->dr_account_id)->update(['cl_balance' => DB::raw('cl_balance + '.$this->purchase_return->net_amount)]);
			
			DB::table('account_master')->where('id', $this->purchase_return->cr_account_id)->update(['cl_balance' => DB::raw('cl_balance + '.$this->purchase_return->total)]);
			
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
			if($vatrow) {
				DB::table('account_master')->where('id', $vatrow->collection_account)->update(['cl_balance' => DB::raw('cl_balance + '.$this->purchase_return->vat_amount)]);
			}
			
			//update transfer status...
			/* DB::table('purchase_invoice')->where('id', $this->purchase_return->purchase_invoice_id)
									  ->update(['is_return' => 0]); */
				DB::table('purchase_return')->where('id', $id)
									  ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id ]);						  
			$this->purchase_return->delete();
			
			DB::commit();
			return true;
			
		} catch(\Exception $e) {
			
			DB::rollback();
			return false;
		}
	}
	
	public function suppliersDOList()
	{
		$query = $this->purchase_return->where('purchase_return.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->select('purchase_return.*','am.master_name AS supplier')
					->orderBY('purchase_return.id', 'DESC')
					->get();
	}
	
	public function getSDOdata($supplier_id = null)
	{
		if($supplier_id)
			$query = $this->purchase_return->where('purchase_return.status',1)->where('purchase_return.is_transfer',0)->where('purchase_return.supplier_id',$supplier_id);
		else
			$query = $this->purchase_return->where('purchase_return.status',1)->where('purchase_return.is_transfer',0);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->select('purchase_return.*','am.master_name AS supplier')
					->orderBY('purchase_return.id', 'ASC')
					->get();
	}
	
	public function findPRdata($id)
	{
		$query = $this->purchase_return->where('purchase_return.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->join('account_master AS am2', function($join){
						  $join->on('am2.id','=','purchase_return.account_master_id');
					  }) 
					   ->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','purchase_return.job_id');
					})
					->select('purchase_return.*','am.master_name AS supplier','am2.master_name AS account','J.code')
					->orderBY('purchase_return.id', 'ASC')
					->first();


	}
	
	public function purchaseReturnList1()
	{
		$query = $this->purchase_return->where('purchase_return.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->join('purchase_invoice AS pi', function($join) {
							$join->on('pi.id','=','purchase_return.purchase_invoice_id');
						} )
					->select('purchase_return.*','am.master_name AS supplier','pi.voucher_no AS pi_voucher_no','pi.reference_no AS supinv_no')
					->orderBY('purchase_return.id', 'DESC')
					->get();
	}
	
	public function activePurchaseReturnList()
	{
		return $this->purchase_return->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_return->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->purchase_return->where('reference_no',$refno)->count();
	}
	
	public function getPidata()
	{
		$query = $this->purchase_return->where('purchase_return.status',1);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->select('purchase_return.*','am.master_name AS supplier')
					->orderBY('purchase_return.id', 'ASC')
					->get();
	}
	
	public function getItems($id)
	{
		$query = $this->purchase_return->where('purchase_return.id',$id);
		
		return $query->join('purchase_return_item AS poi', function($join) {
							$join->on('poi.purchase_return_id','=','purchase_return.id');
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
					  ->where('poi.status',1)->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->orderBY('poi.id')->groupBY('poi.id')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','iu.pkno')->get();
	}
	
	public function getPRitems($id)
	{
		$query = $this->purchase_return->where('purchase_return.id',$id);
		
		return $query->join('purchase_return_item AS poi', function($join) {
							$join->on('poi.purchase_return_id','=','purchase_return.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
						->select('poi.*','u.unit_name')->get();
		//return $this->itemmaster->where('status', 1)->orderBy('description','ASC')->select('id','item_code','description')->get();
	}
	
	public function getReturnReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->purchase_return->where('purchase_return.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_return.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_return.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','purchase_return.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->purchase_return->where('purchase_return.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_return.supplier_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','purchase_return.job_id');
								   })
								   ->select('AM.master_name AS supplier','purchase_return.*','JM.name AS job')
								   ->orderBY('purchase_return.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getOrder($attributes)
	{
		$order = $this->purchase_return->where('purchase_return.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','purchase_return.supplier_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','purchase_return.*','AM.address','AM.city','AM.state','AM.contact_name','AM.vat_no','AM.phone')
								   ->orderBY('purchase_return.id', 'ASC')
								   ->first();
								   
	
		$items = $this->purchase_return->where('purchase_return.id', $attributes['document_id'])
								   ->join('purchase_return_item AS PI', function($join) {
									   $join->on('PI.purchase_return_id','=','purchase_return.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->select('PI.*','purchase_return.id','IM.item_code','U.unit_name')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->purchase_return->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else{
			$query = $this->purchase_return->where('voucher_no',$refno);
			return $result = ($deptid)?$query->where('department_id', $deptid)->count():$query->count();
		}
	}
	
// 	public function getReport($attributes)
// 	{
// 		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
// 		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
// 		switch($attributes['search_type']) {
// 			case 'summary':
// 				$query = $this->purchase_return
// 								->join('purchase_return_item AS POI', function($join) {
// 									$join->on('POI.purchase_return_id','=','purchase_return.id');
// 								})
// 								->join('account_master AS AM', function($join) {
// 									$join->on('AM.id','=','purchase_return.supplier_id');
// 								})
// 								->where('POI.status',1);
								
// 						if( $date_from!='' && $date_to!='' ) { 
// 							$query->whereBetween('purchase_return.voucher_date', array($date_from, $date_to));
// 						}
						 
// 				$query->select('purchase_return.voucher_no','purchase_return.reference_no','purchase_return.total','purchase_return.vat_amount',
// 							   'purchase_return.voucher_date','POI.quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no','purchase_return.net_amount');
								
// /* 				if(isset($attributes['type']))
// 					return $query->groupBy('purchase_return.id')->get()->toArray();
// 				else */
// 					return $query->groupBy('purchase_return.id')->get();
// 			break;
			
// 			case 'detail':
// 				$query = $this->purchase_return
// 								->join('purchase_return_item AS POI', function($join) {
// 									$join->on('POI.purchase_return_id','=','purchase_return.id');
// 								})
// 								->join('account_master AS AM', function($join) {
// 									$join->on('AM.id','=','purchase_return.supplier_id');
// 								})
// 								->join('itemmaster AS IM', function($join) {
// 									$join->on('IM.id','=','POI.item_id');
// 								})
// 								->where('POI.status',1);
								
// 						if( $date_from!='' && $date_to!='' ) { 
// 							$query->whereBetween('purchase_return.voucher_date', array($date_from, $date_to));
// 						}
						 
// 				$query->select('purchase_return.voucher_no','purchase_return.reference_no','IM.item_code','IM.description','purchase_return.total','purchase_return.vat_amount',
// 								'POI.quantity','POI.unit_price','POI.total_price','AM.account_id','AM.master_name','purchase_return.net_amount');
				
// 				/* if(isset($attributes['type']))
// 					return $query->get()->toArray();
// 				else */
// 					return $query->get();
				
// 				break;
// 		}
// 	}
public function getReport($attributes)
{
	$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
	$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
	
	if($attributes['search_type']=="detail") {
	    	$query = $this->purchase_return
 								->join('purchase_return_item AS POI', function($join) {
									$join->on('POI.purchase_return_id','=','purchase_return.id');
								})
							->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_return.supplier_id');
								})
 								->join('itemmaster AS IM', function($join) {
								$join->on('IM.id','=','POI.item_id');
								})								
								
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) {  							
						    $query->whereBetween('purchase_return.voucher_date', array($date_from, $date_to));
						}
						
						if(isset($attributes['supplier_id']) && $attributes['supplier_id']!='')
			$query->where('purchase_return.supplier_id', $attributes['supplier_id']);
			
			if(isset($attributes['job_id']))
							$query->whereIn('purchase_return.job_id', $attributes['job_id']);
						 
				$query->select('purchase_return.voucher_no','purchase_return.reference_no','IM.item_code','IM.description','purchase_return.total','purchase_return.vat_amount',
								'POI.quantity','POI.unit_price','POI.total_price','POI.discount','POI.vat_amount AS unit_vat','AM.account_id','AM.master_name','purchase_return.net_amount');
				
 				/* if(isset($attributes['type']))
 					return $query->get()->toArray();
			else */
				return $query->get();
				
	}
	    else{
	$query = $this->purchase_return
					->join('purchase_return_item AS POI', function($join) {
						$join->on('POI.purchase_return_id','=','purchase_return.id');
					})
					->join('account_master AS AM', function($join) {
						$join->on('AM.id','=','purchase_return.supplier_id');
					})
					->join('item_log AS IS', function($join) {
						$join->on('IS.item_id','=', 'POI.item_id');
						$join->on('IS.document_id','=', 'purchase_return.id');
					})
					->join('itemmaster AS IM', function($join) {
						$join->on('IM.id','=','POI.item_id');
					})
					->leftJoin('groupcat AS GP', function($join) {
						$join->on('GP.id','=','IM.group_id');
					})
					->leftJoin('groupcat AS SGP', function($join) {
						$join->on('GP.id','=','IM.subgroup_id');
					})
					->leftJoin('category AS CAT', function($join) {
						$join->on('CAT.id','=','IM.category_id');
					})
					->leftJoin('category AS SCAT', function($join) {
						$join->on('CAT.id','=','IM.subcategory_id');
					})
					->where('purchase_return.status',1)
					->where('POI.status',1);

			// if($attributes['isimport']==1)
			// 	$query->where('purchase_invoice.is_import',1);
			// else if($attributes['isimport']==0)
			// 	$query->where('purchase_invoice.is_import',0);
			// else if($attributes['isimport']==2)
			// 	$query->whereIn('purchase_invoice.is_import',[0,1]);

			if( $date_from!='' && $date_to!='' ) { 
				$query->whereBetween('purchase_return.voucher_date', array($date_from, $date_to));
			}
		if(isset($attributes['group_id']))
			$query->whereIn('IM.group_id', $attributes['group_id']);
		
		if(isset($attributes['subgroup_id']))
			$query->whereIn('IM.subgroup_id', $attributes['subgroup_id']);
		
		if(isset($attributes['category_id']))
			$query->whereIn('IM.category_id', $attributes['category_id']);
		
		if(isset($attributes['subcategory_id']))
			$query->whereIn('IM.subcategory_id', $attributes['subcategory_id']);
		if(isset($attributes['supplier_id']) && $attributes['supplier_id']!='')
			$query->where('purchase_return.supplier_id', $attributes['supplier_id']);		
		if(isset($attributes['job_id']))
							$query->whereIn('purchase_return.job_id', $attributes['job_id']);

	if(isset($attributes['item_id']) && $attributes['item_id']!='')
				$query->whereIn('POI.item_id', $attributes['item_id']);			
	$query->select('purchase_return.voucher_no','purchase_return.supplier_id','purchase_return.reference_no','purchase_return.total','purchase_return.vat_amount','POI.tax_code','purchase_return.discount',
						  'purchase_return.voucher_date','AM.master_name AS supplier','IM.item_code','GP.group_name AS group','SGP.group_name AS subgroup','POI.quantity','POI.item_name','POI.discount','POI.vat_amount AS unit_vat','POI.total_price','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no','purchase_return.net_amount','purchase_return.subtotal');
					
	if(isset($attributes['type']))
		return $query->groupBy('purchase_return.id')->get()->toArray();
	else
		return $query->groupBy('purchase_return.id')->get();
	    }
}

	public function check_order($id)
	{
		$row = $this->purchase_return->find($id);
		
		//$count = DB::table('payment_voucher_tr')->where('purchase_invoice_id', $row->purchase_invoice_id)->count();
		if($row) //$count
			return true; //false
		else
			return false; //true
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
	
	private function setSaleLog($attributes, $key, $document_id, $cost_avg, $sale_cost, $action)
	{
	    
	   
		$irow = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])->select('cur_quantity')->first();
		$attributes['packing'][$key]=isset($attributes['packing'][$key])?$attributes['packing'][$key]:1;
		/*if(($attributes['unit_id'][$key]==1||$attributes['unit_id'][$key]==2)) {
			$unit_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']*$attributes['packing'][$key]):($attributes['cost'][$key]*$attributes['packing'][$key]);
			$pur_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']*$attributes['packing'][$key]):($attributes['cost'][$key]*$attributes['packing'][$key]);
		} else {
			$unit_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']/$attributes['packing'][$key]):($attributes['cost'][$key]/$attributes['packing'][$key]);
			$pur_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']/$attributes['packing'][$key]):($attributes['cost'][$key]/$attributes['packing'][$key]);
		} */
	   
	   
	   	$unit_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']):($attributes['cost'][$key]);
		$pur_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']):($attributes['cost'][$key]);
		
		if($attributes['packing'][$key]=="1") 
		    $quantity = $attributes['quantity'][$key];
		else {
		   $pkgar = explode('-', $attributes['packing'][$key]);
		   $quantity = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
		   
		   //COST...
		   $unit_cost = ($unit_cost * $pkgar[0]) / $pkgar[1];
		   $pur_cost = ($pur_cost * $pkgar[0]) / $pkgar[1];
		}
		
		if($action=='add') {
			$cur_quantity = ($irow)?$irow->cur_quantity:0;				
			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'PR',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   =>  $quantity, //$attributes['quantity'][$key] * $attributes['packing'][$key],
							 'unit_cost'  => $unit_cost,
							 'trtype'	  => 0,
							 'cost_avg' => $cost_avg,
							 'pur_cost' => $sale_cost,
							 'sale_cost' => $sale_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							 'sale_reference' => $cur_quantity,
							 'return_ref_id'  => $attributes['purchase_invoice_id'],
							 'item_row_id'	=> $attributes['item_row_id'][$key] //OCT24
							]);
			
		} else if($action=='update') {
			
			$logid = '';					
			DB::table('item_log')->where('document_type','PR')
							->where('document_id', $document_id)
							->where('item_id', $attributes['item_id'][$key])
							->where('unit_id', $attributes['unit_id'][$key])
							->where('item_row_id', $attributes['order_item_id'][$key]) //OCT24
							->update([
								 'quantity'   =>  $quantity, //$attributes['quantity'][$key] * $attributes['packing'][$key],
								 'unit_cost'  => $unit_cost, //(isset($attributes['is_fc']))?$unit_cost*$attributes['currency_rate']:$unit_cost,
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg,
								 'pur_cost' => $sale_cost,
								 'sale_cost' => $sale_cost,
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			
		}
		
		return $logid;
	}
	
	public function updateLastPurchaseCostAndCostAvgonDelete($items, $id) {
		//UPDATE Cost avg and stock...
		foreach($items as $item) {
									
			//COST AVG Updating on DELETE section....
			DB::table('item_log')->where('document_id', $id)->where('document_type','PR')
								 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
								 ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity + '.$item->quantity)]);
									  
			$this->objUtility->autoUpdateAVGCost($item->item_id);
		}
	}
	
	
	public function getTransactionList($attributes) 
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->purchase_return
								   ->join('purchase_return_item AS PI', function($join) {
									   $join->on('PI.purchase_return_id','=','purchase_return.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->where('purchase_return.status',1);
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('purchase_return.voucher_date',[$date_from, $date_to]);

							/*	if($attributes['search_by']=='Group')
						        $qry->where('IM.group_id','!=',0);
							
							if($attributes['search_by']=='Subgroup')
						        $qry->where('IM.subgroup_id','!=',0);
								
							if($attributes['search_by']=='Category')
						        $qry->where('IM.category_id','!=',0);

							if($attributes['search_by']=='Subcategory')
						        $qry->where('IM.subcategory_id','!=',0);*/	
					
		$result = $qry->select('purchase_return.id','purchase_return.voucher_no','purchase_return.voucher_date',
								'IM.item_code','IM.description','PI.quantity','PI.unit_price','PI.vat_amount','PI.total_price')
								   ->orderBY('purchase_return.voucher_date', 'ASC')
								   ->get();
								   
		return $result;
	}
	
	public function purchaseReturnListCount()
	{
		$query = $this->purchase_return->where('purchase_return.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} )
					->count();
	}
	
	public function purchaseReturnList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->purchase_return->where('purchase_return.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_return.supplier_id');
						} );
						
				if($search) {
					$query->where('purchase_return.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('purchase_return.reference_no', 'LIKE',"%{$search}%")
						  ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('purchase_return.*','am.master_name AS supplier')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	private function getVatAccounts($department_id=null) {
		
		if(Session::get('department')==1 && $department_id!=null) {
			$vatres = DB::table('vat_department')->where('department_id', $department_id)->first();
			if(!$vatres)
				$vatres = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
			return $vatres;
		} else {
			return DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
		}
	}
	
	private function CostDifferenceEntry($attributes,$amount,$voucher_id) {
		
		if(Session::get('department')==1) {
			$costac = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
			if($costac)	
				$dr_acnt_id = $costac->costdif_acid;
			else {
				$costac = DB::table('other_account_setting')->where('account_setting_name','Cost Difference')->where('status',1)->select('account_id')->first();
				$dr_acnt_id = $costac->account_id;
			}
		} else {
			$costac = DB::table('other_account_setting')->where('account_setting_name','Cost Difference')->where('status',1)->select('account_id')->first();
			$dr_acnt_id = $costac->account_id;
		}
		
		
		$stockac = DB::table('voucher_account')->where('account_field','stock')->select('account_id')->first();
		
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PR',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => $costac->account_id,
								'transaction_type'  => 'Dr',
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'is_paid'			=> 5, //IDENTIFY COST DIFF TRANS.
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'version_no'		=> $attributes['version_no']
								]);
				
		$this->objUtility->tallyClosingBalance($costac->account_id);
		
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PR',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => $stockac->account_id,
								'transaction_type'  => 'Cr',
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'is_paid'			=> 5, //IDENTIFY COST DIFF TRANS.
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'version_no'		=> $attributes['version_no']
								]);
				
		$this->objUtility->tallyClosingBalance($costac->account_id);
	}
	
	private function CostDifferenceEntryUpdate($attributes,$amount,$voucher_id) {
		
		if(Session::get('department')==1) {
			$costac = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
			if($costac)
				$dr_acnt_id = $costac->costdif_acid;
			else {
				$costac = DB::table('other_account_setting')->where('account_setting_name','Cost Difference')->where('status',1)->select('account_id')->first();
				$dr_acnt_id = $costac->account_id;
			}
		} else {
			$costac = DB::table('other_account_setting')->where('account_setting_name','Cost Difference')->where('status',1)->select('account_id')->first();
			$dr_acnt_id = $costac->account_id;
		}
		
		$stockac = DB::table('voucher_account')->where('account_field','stock')->select('account_id')->first();
		
		DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $costac->account_id)
					->where('voucher_type', 'PR')
					->where('transaction_type','Cr')
					->where('is_paid',5) //IDENTIFY COST DIFF TRANS.
					->update([  'amount'   			=> $amount,
								'modify_at' 		=> date('Y-m-d H:i:s'),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
								]);
				
		$this->objUtility->tallyClosingBalance($costac->account_id);
		
		DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $stockac->account_id)
					->where('voucher_type', 'PR')
					->where('transaction_type','Dr')
					->where('is_paid',5) //IDENTIFY COST DIFF TRANS.
					->update([  'amount'   			=> $amount,
								'modify_at' 		=> date('Y-m-d H:i:s'),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount
								]);
				
		$this->objUtility->tallyClosingBalance($costac->account_id);
	}
	
}