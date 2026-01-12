<?php
declare(strict_types=1);
namespace App\Repositories\SalesSplitReturn;

use App\Models\SalesSplitReturn;
use App\Models\SalesSplitReturnItem;
use App\Models\AccountTransaction;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;
use Storage;

class SalesSplitReturnRepository extends AbstractValidator implements SalesSplitReturnInterface {
	
	public $objUtility;
	
	protected $salessplit_return;
	
	protected static $rules = [];
	
	public function __construct(SalesSplitReturn $salessplit_return) {
		$this->salessplit_return = $salessplit_return;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->salessplit_return->get();
	}
	
	public function find($id)
	{
		return $this->salessplit_return->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->salessplit_return->voucher_id = $attributes['voucher_id']; 
		$this->salessplit_return->voucher_no = $attributes['voucher_no']; 
		$this->salessplit_return->reference_no = $attributes['reference_no'];
		$this->salessplit_return->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->salessplit_return->customer_id = $attributes['customer_id'];
		$this->salessplit_return->sales_split_id = isset($attributes['sales_split_id'])?$attributes['sales_split_id']:'';
		$this->salessplit_return->job_id = isset($attributes['job_id'])?$attributes['job_id']:'';
		$this->salessplit_return->description = isset($attributes['description'])?$attributes['description']:'';
		$this->salessplit_return->is_fc = isset($attributes['is_fc'])?1:0;
		$this->salessplit_return->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->salessplit_return->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:'';
		$this->salessplit_return->department_id   = isset($attributes['department_id'])?$attributes['department_id']:'';
		$this->salessplit_return->is_pettycash   = isset($attributes['is_pettycash'])?$attributes['is_pettycash']:'';
		$this->salessplit_return->vehicle_id   = isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'';
		
		return true;
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
	
	private function setItemInputValue($attributes, $salesSplitReturnItem, $key, $value, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
		if( isset($attributes['is_fc']) ) {
			
			//$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['packing'][$key]) * $attributes['currency_rate'];
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = $attributes['tax_code'][$key];
			
			if($tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = (($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key]) * $attributes['currency_rate'];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
			}
			
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			$tax_code = $attributes['tax_code'][$key];
			
			if($tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
			}
			
		}

		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		$vatPlus = 100;
		$total = $attributes['line_total'][$key];
		
		if($discount > 0) {
			$discountAmt = round( (($total / $lineTotal) * $discount),2 );
			$amountTotal = $total - $discountAmt;
			$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
			$tax_total = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine; 
		} 
		
		$salesSplitReturnItem->salessplit_return_id = $this->salessplit_return->id;
		$salesSplitReturnItem->account_id = $attributes['account_id'][$key];
		$salesSplitReturnItem->unit_id = $attributes['unit_id'][$key];
		$salesSplitReturnItem->item_description = $attributes['item_description'][$key];
		$salesSplitReturnItem->quantity = $attributes['quantity'][$key];
		$salesSplitReturnItem->unit_price = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$salesSplitReturnItem->vat = $attributes['line_vat'][$key];
		$salesSplitReturnItem->item_vat = $tax_total;
		$salesSplitReturnItem->item_total = $line_total;
		$salesSplitReturnItem->tax_code 	= $tax_code;
		$salesSplitReturnItem->line_total = $item_total;
		$salesSplitReturnItem->item_jobid = isset($attributes['jobid'][$key])?$attributes['jobid'][$key]:$attributes['job_id'];
		
		$salesSplitReturnItem->unit_price_fc = $attributes['cost'][$key];
		$salesSplitReturnItem->item_vat_fc = (isset($attributes['is_fc']))?round(($tax_total /  $attributes['currency_rate']),2):$tax_total;
		$salesSplitReturnItem->item_total_fc = (isset($attributes['is_fc']))?round(($line_total /  $attributes['currency_rate']),2):$line_total;
		$salesSplitReturnItem->line_total_fc = (isset($attributes['is_fc']))?round(($item_total /  $attributes['currency_rate']),2):$item_total;
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'item_total' => $item_total);
		
		
	}
	
		private function setTransferStatusItem($attributes, $key)
	{
		//if quantity partially deliverd, update pending quantity.
		if(isset($attributes['order_item_id'])) {
			if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
				if( isset($attributes['order_item_id'][$key]) ) {
					$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
					//update as partially delivered.
					DB::table('sales_split_item')
								->where('id', $attributes['order_item_id'][$key])
								->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
				}
			} else {
					//update as completely delivered.
					DB::table('sales_split_item')
								->where('id', $attributes['order_item_id'][$key])
								->update(['balance_quantity' => 0, 'is_transfer' => 1]);
			}
		}
	}
	
	private function SetItemAccountTransaction($itemid, $arrResult, $key, $attributes, $type) {
		
		$amount = ($type=='LINE')?$attributes['line_total'][$key]:$arrResult['tax_total'];
		
		if($type=='VAT') {
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); 
			if($vatrow) {
				$cr_acnt_id = $vatrow->payment_account;
				//$itemid = $itemid->sales_split_id.'VAT';
				$itemid = $itemid->id.'VAT';
			}
		} else {
			//$itemid = $itemid->sales_split_id;
			$itemid = $itemid->id;
			$cr_acnt_id = $attributes['account_id'][$key];
		} 
			
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'SSR',
								'voucher_type_id'   => $this->salessplit_return->id,
								'account_master_id' => $cr_acnt_id,
								'transaction_type'  => 'Dr',
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['item_description'][$key],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'is_fc'				=> isset($attributes['is_fc'])?1:0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> $attributes['jobid'][$key],
								'other_info'		=> $itemid,
							]);
								
		return true;
		
	}
	
	
	private function SetItemAccountTransactionUpdate($itemid, $amount, $key, $attributes, $type) {
		
		if($type=='VAT') {
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); 
			if($vatrow) {
				$dr_acnt_id = $vatrow->payment_account;
				$itemid = $itemid.'VAT';
			}
		} else {
			$dr_acnt_id = $attributes['account_id'][$key];
		} 
			
		DB::table('account_transaction')
					->where('voucher_type','SSR')
					->where('voucher_type_id', $this->salessplit_return->id)
					->where('transaction_type','Dr')
					->where('other_info', $itemid)
					->update([  'account_master_id' => $dr_acnt_id,
								'amount'   			=> $amount,
								'description' 		=> $attributes['item_description'][$key],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'is_fc'				=> isset($attributes['is_fc'])?1:0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> $attributes['jobid'][$key]
							]);
								
		return true;
		
	}
		
	//Accounting Method function............
	private function AccountingMethod($attributes, $line_total, $tax_total, $net_amount, $salessplit_return_id)
	{
		if( isset($attributes['is_fc']) ) 
			$discount = (isset($attributes['discount']))?((int)$attributes['discount']* (int)$attributes['currency_rate']):0;
		else
			$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		//Credit Supplier Accounting
		if( $this->setAccountTransaction($attributes, $net_amount, $salessplit_return_id, $type='Cr', $amount_type='NTAMT') ) {
		
			$this->setAccountTransaction($attributes, $discount, $salessplit_return_id, $type='Cr', $amount_type='DIS');
		}
		
	}
	
	private function AccountingMethodUpdate($attributes, $line_total, $tax_total, $net_amount, $salessplit_return_id)
	{
		if( isset($attributes['is_fc']) )
			$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
		else
			$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
			$line_total = $line_total + $discount;
			
			//Credit Supplier Accounting
			if( $this->setAccountTransactionUpdate($attributes, $net_amount, $salessplit_return_id, $type='Dr', $amount_type='NTAMT') ) {
				
				$this->setAccountTransactionUpdate($attributes, $discount, $salessplit_return_id, $type='Dr', $amount_type='DIS');
			}
	}
	
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null)
	{
		$cr_acnt_id = '';
		if($amount!=0) {
			
			if($amount_type == 'NTAMT') {
				$cr_acnt_id = $attributes['customer_id'];
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					$cr_acnt_id = $vatrow->purdis_acid;
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
					$cr_acnt_id = $vatrow->account_id;
				}
			}
				
			$fc_amount = (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount;
		
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'SSR',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => $cr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> $fc_amount,
								'is_fc'				=> isset($attributes['is_fc'])?1:0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> (isset($attributes['job_id']))?$attributes['job_id']:''
							]);
			
			$this->objUtility->tallyClosingBalance($cr_acnt_id);	
			
		}
						
		return true;
	}
	
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $amount_type=null, $key=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null);
		if($amount!=0) {
			if($amount_type=='VAT') {
					//TAx code change..... account_id
					if($attributes['tax_code_old'][0]!=$attributes['tax_code'][0]) {
						
						if($attributes['tax_code_old'][0]=='ZR' && $attributes['tax_code'][0]=='SR') {
							//Storage::prepend('stolog.txt', 'tax_code_old: ');
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'SSR',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->payment_account,
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
											
							$dr_acnt_id = $account_id = $vatrow->payment_account;
							$this->objUtility->tallyClosingBalance($vatrow->payment_account);	
							
						} else if( $attributes['tax_code_old'][0]=='SR' && $attributes['tax_code'][0]=='ZR') {
							
							//Update Vat input a/c as vat input import a/c...
							DB::table('account_transaction')
								->where('voucher_type_id', $voucher_id)
								->where('account_master_id', $vatrow->payment_account) //CHNG tax_code
								->where('transaction_type' , 'Cr')
								->where('voucher_type', 'SSR')					
									->update(['account_master_id' => $vatrow->vatinput_import]);
									
							$dr_acnt_id = $account_id = $vatrow->vatinput_import;
							
							DB::table('account_transaction')
								->insert([  'voucher_type' 		=> 'SSR',
											'voucher_type_id'   => $voucher_id,
											'account_master_id' => $vatrow->vatoutput_import,
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
											
							
							$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
						
						}
						
					} else {
					
						if($vatrow) {
							$dr_acnt_id = $account_id = $vatrow->payment_account;
						}
					}
				
				$cur_account_id = $account_id;
				
			} else if($amount_type == 'NTAMT') {
				
				$cr_acnt_id = $cur_account_id = $account_id = $attributes['customer_id'];
				
				//CHANGING SUPPLIER..
				if($attributes['customer_id'] != $attributes['old_customer_id']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'SSR')
							->where('account_master_id', $attributes['old_customer_id'])
							->update( ['account_master_id' => $attributes['customer_id'] ]);
							
					$this->objUtility->tallyClosingBalance($attributes['old_customer_id']);
				}
				
				
			} else if($amount_type == 'DIS') {
				if(Session::get('department')==1) { 
					$vatrow = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('purdis_acid')->first();
					$cr_acnt_id = $vatrow->purdis_acid;
				} else {
					$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
					$cr_acnt_id = $cur_account_id = $account_id = $vatrow->account_id;
				}
			}
			
			DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $cur_account_id) //CHNG
					->where('voucher_type', 'SSR')					
					->update([  'account_master_id' => $account_id,
								'amount'   			=> $amount,
								'modify_at' 		=> now(),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> (isset($attributes['is_fc']))?($amount/$attributes['currency_rate']):$amount,
								'is_fc'				=> isset($attributes['is_fc'])?1:0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'		=> (isset($attributes['job_id']))?$attributes['job_id']:''
							]);
								
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		} else {  //Remove vat account transaction..
			
			if( $attributes['vatcur'] != 0 && $attributes['vat'] == 0) {
				//Remove vat account...
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->payment_account)
					->where('transaction_type' , 'Cr')
					->where('voucher_type', 'SSR')					
						->update(['status' => 0, 'deleted_at' => now()]);
						
				$this->objUtility->tallyClosingBalance($vatrow->payment_account);
						
				//Remove vatoutput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatoutput_import)
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'SSR')					
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
				
				//Remove vatinput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatinput_import)
					->where('transaction_type' , 'Cr')
					->where('voucher_type', 'SSR')					
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatinput_import);
						
			} 
		}
		
		return true;
	}
	
		private function setTransferStatusQuote($attributes)
	{
		if($attributes['sales_split_id']) {
			$row1 = DB::table('sales_split_item')->where('sales_split_id', $attributes['sales_split_id'])->count();
			$row2 = DB::table('sales_split_item')->where('sales_split_id', $attributes['sales_split_id'])->where('is_transfer',1)->count();
			if($row1==$row2) {
				
				DB::table('sales_split')
						->where('id', $attributes['sales_split_id'])
						->update([ 'is_transfer' => 1,'is_editable' => 1]);
			}
		}
	}
	
		
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		foreach($attributes['account_id'] as $key => $value){ 
			
			$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
		}
		return $total;
	}
	public function create($attributes)
	{ 	//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
		 DB::beginTransaction();
		 try {
			
			if($this->setInputValue($attributes)) {
				$this->salessplit_return->status = 1;
				$this->salessplit_return->created_at = now();
				$this->salessplit_return->created_by = 1;
				$this->salessplit_return->fill($attributes)->save();
			}
			
			//invoice items insert
			if($this->salessplit_return->id && !empty( array_filter($attributes['account_id']))) { 
				
				$line_total = 0; $tax_total = 0; $total_quantity = 0; $total = 0; $item_total = 0; $other_cost = 0;
				
				//calculate total amount....
				if( isset($attributes['is_fc']) ) 
					$discount = (isset($attributes['discount']))?((int)$attributes['discount']* (int)$attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
					
                 
				foreach($attributes['account_id'] as $key => $value) { 
					$salesSplitReturnItem = new SalesSplitReturnItem();
					$vat = $attributes['line_vat'][$key];
					$arrResult 	= $this->setItemInputValue($attributes, $salesSplitReturnItem, $key, $value, $total, $total_quantity);
					if($arrResult['line_total']) {
						$line_total			   += $arrResult['line_total'];
						$tax_total      	   += $arrResult['tax_total'];
						$item_total			   += $arrResult['item_total'];
						//echo '<pre>';print_r($this->salessplit_return->id);exit;
						$salesSplitReturnItem->status = 1;
						
						$inv_item = $this->salessplit_return->doItem()->save($salesSplitReturnItem);
					//	echo '<pre>';print_r($this->salessplit_return->id);exit;
						//update item transfer status...
								$this->setTransferStatusItem($attributes, $key);
						
						if($this->SetItemAccountTransaction($inv_item, $arrResult, $key, $attributes,'LINE'))
							$this->SetItemAccountTransaction($inv_item, $arrResult, $key, $attributes,'VAT');
					}

				}
				
								
				$subtotal = $line_total - $discount;
				$net_amount = $subtotal + $tax_total;
					
				if( isset($attributes['is_fc']) ) { 
					$total_fc 	   = $line_total / $attributes['currency_rate'];
					$discount_fc   = $attributes['discount']; 
					$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
					$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
					$net_amount_fc = (int)$total_fc - (int)$discount_fc + (int)$tax_fc;
					$other_cost_fc = $other_cost / $attributes['currency_rate'];
					$subtotal_fc   = $subtotal / $attributes['currency_rate'];
					$discount      = (int)$attributes['discount'] * (int)$attributes['currency_rate']; 
				} else {
					$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0; $other_cost_fc = $subtotal_fc = 0;
					$discount      = (isset($attributes['discount']))?$attributes['discount']:0; 
				}
				
				//update discount, total amount, vat and other cost....
				DB::table('salessplit_return')
							->where('id', $this->salessplit_return->id)
							->update(['total'    	  => $line_total,
									  'discount' 	  => $discount, //M14
									  'vat_amount'	  => $tax_total,
									  'net_amount'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_amount_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal,
									  'subtotal_fc'	  => $subtotal_fc ]); 
									  
				//Cost Accounting or Purchase and Sales Method .....
				$this->AccountingMethod($attributes, $subtotal, $tax_total, $net_amount, $this->salessplit_return->id);
				
				
				//update voucher no........
				if( ($this->salessplit_return->id) && ($attributes['curno'] <= $attributes['voucher_no']) ) {  
					//Update voucher no based on department or not...
					if(Session::get('department')==1) { //if dept active...
						 DB::table('account_setting')
							->where('voucher_type_id', 29) 
							->where('department_id', $attributes['department_id'])
							->update(['voucher_no' => $attributes['voucher_no'] + 1]);
					 } else {
						 DB::table('account_setting')
							->where('voucher_type_id', 29)
							->update(['voucher_no' => $attributes['voucher_no'] + 1 ]); //DB::raw('voucher_no + 1')
					 }
					 
				}
				
			}
			
			if(isset($attributes['sales_split_id'])) 
							$this->setTransferStatusQuote($attributes);	
			
			//TRN no update....
			if(isset($attributes['vat_no']) && $attributes['vat_no']!='') {
				 DB::table('account_master')
						->where('id', $attributes['customer_id'])
						->update(['vat_no' => $attributes['vat_no'] ]);
			}
							
			DB::commit();
			return true;
			
		  } catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			  return false;
		  }
		}
		
	}
	
	public function update($id, $attributes)
	{ //echo '<pre>';print_r($attributes);exit;
		$this->salessplit_return = $this->find($id);
		
		DB::beginTransaction();
		try {
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->salessplit_return->id && !empty( array_filter($attributes['account_id']))) {
				
				$line_total = $tax_total = 0; $cost_value = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = 0;
				$item_total = 0;
				
			
				//calculate total amount.... linetotal taxtotal
				if( isset($attributes['is_fc']) ) //M14..
					$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
				
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
				
				foreach($attributes['account_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						
						$tax_code = $attributes['tax_code'][$key];
						
						if( isset($attributes['is_fc']) ) {
							
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
							
							if($tax_code=="ZR") {
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
								
							} else {
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
							}
							
						} else {
							
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
							
							if($tax_code=="ZR") {
								$tax        = 0;
								$itemtotal = ((int)$attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);								
							} else {
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
							}
						}
						
						$discount = (isset($attributes['discount']))?$attributes['discount']:0;
						
						$vatPlus = 100;
						$total = $attributes['line_total'][$key];
						
						if($discount > 0) {
							$discountAmt = round( (($total / $lineTotal) * $discount),2 );
							$amountTotal = $total - $discountAmt;
							$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
							$taxtotal = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine;
						} 
						
						$tax_total += $taxtotal;
						$line_total += $linetotal;
						$item_total += $itemtotal;
						
						$vat = $attributes['line_vat'][$key]; 
						
						$salesSplitReturnItem = SalesSplitReturnItem::find($attributes['order_item_id'][$key]);
						$oldqty = $salesSplitReturnItem->quantity;
						$items['item_description'] = $attributes['item_description'][$key];
						$items['account_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = $costchk = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
						$items['item_jobid'] = isset($attributes['jobid'][$key])?$attributes['jobid'][$key]:$attributes['job_id'];
						$items['vat']		 = $attributes['line_vat'][$key];
						$items['item_vat'] = $taxtotal;
						$items['discount'] = $attributes['line_discount'][$key];
						$items['item_total'] = $linetotal;
						$items['tax_code'] 	= $tax_code;
						$items['line_total'] = $itemtotal;
						
						$items['unit_price_fc'] = $attributes['cost'][$key];
						$items['item_vat_fc'] = (isset($attributes['is_fc']))?round(($taxtotal / $attributes['currency_rate']),2):$taxtotal;
						$items['item_total_fc'] = (isset($attributes['is_fc']))?round(($linetotal / $attributes['currency_rate']),2):$linetotal;
						$items['line_total_fc'] = (isset($attributes['is_fc']))?round(($itemtotal / $attributes['currency_rate']),2):$itemtotal;
						
						$exi_item_id = $salesSplitReturnItem->item_id;
						$exi_unit_id = $salesSplitReturnItem->unit_id;
						$exi_qty = $salesSplitReturnItem->quantity;
						$exi_price = $salesSplitReturnItem->unit_price;
						$itemsobj = (object)['item_id' => $exi_item_id, 'unit_id' => $exi_unit_id]; 
						
						$salesSplitReturnItem->update($items);
						
						if($this->SetItemAccountTransactionUpdate($attributes['order_item_id'][$key], $linetotal, $key, $attributes,'LINE'))
							$this->SetItemAccountTransactionUpdate($attributes['order_item_id'][$key], $taxtotal, $key, $attributes,'VAT');
											
					} else { 
						//new entry...
						$item_total_new = $tax_total_new = $item_total_new = $total = 0;
						if($discount > 0) 
							$total = $this->calculateTotalAmount($attributes);
						
						$vat = $attributes['line_vat'][$key];
						$salesSplitReturnItem = new SalesSplitReturnItem();
						$arrResult 		= $this->setItemInputValue($attributes, $salesSplitReturnItem, $key, $value, $total, $total_quantity);
						if($arrResult['line_total']) {
							$line_total_new			     += $arrResult['line_total'];
							$tax_total_new      	     += $arrResult['tax_total'];
							$item_total_new			 += $arrResult['item_total'];
							
							$line_total			     += $arrResult['line_total'];
							$tax_total      	     += $arrResult['tax_total'];
							$item_total 			 += $arrResult['item_total'];
							
							$salesSplitReturnItem->status = 1;
							$inv_item = $this->salessplit_return->doItem()->save($salesSplitReturnItem);
							
							if($this->SetItemAccountTransaction($inv_item, $arrResult, $key, $attributes,'LINE'))
								$this->SetItemAccountTransaction($inv_item, $arrResult, $key, $attributes,'VAT');
						}
											
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
					$itm = DB::table('salessplit_return_item')->where('id', $row)->select('id','salessplit_return_id','account_id')->first();
					DB::table('salessplit_return_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					
					//REMOVE FROM TRANSACTION TABLE..
					DB::table('account_transaction')->where('voucher_type','SSR')->where('voucher_type_id',$itm->salessplit_return_id)->where('account_master_id',$itm->account_id)
							->where('other_info',$itm->id)->update(['status'=>0,'deleted_at'=> now()]);
							
					//IF VAT ALSO
					DB::table('account_transaction')->where('voucher_type','SSR')->where('voucher_type_id',$itm->salessplit_return_id)->where('account_master_id',$itm->account_id)
							->where('other_info',$itm->id.'VAT')->update(['status'=>0,'deleted_at'=> now()]);
				}
			}
			
			if($this->setInputValue($attributes)) {
				
				$this->salessplit_return->modify_at = now();
				$this->salessplit_return->modify_by = 1;
				$this->salessplit_return->fill($attributes)->save();
				
			}
			
			if( isset($attributes['is_fc']) )
				$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
			
			$subtotal = $line_total - $discount;
			$net_amount = $subtotal + $tax_total;
			
			
			if( isset($attributes['is_fc']) ) {
				$total_fc 	   = $line_total / $attributes['currency_rate'];
				$discount_fc   = $attributes['discount']; //M14
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
				$subtotal_fc   = $subtotal / $attributes['currency_rate']; 
				$other_cost_fc = $other_cost / $attributes['currency_rate'];
				$discount      = $attributes['discount'] * $attributes['currency_rate']; //M14
			} else {
				$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $other_cost_fc = $subtotal_fc = 0;
				$discount = (isset($attributes['discount']))?$attributes['discount']:0; //M14
			}
			
			//update discount, total amount
			DB::table('salessplit_return')
						->where('id', $this->salessplit_return->id)
						->update(['total'    	  => $line_total,
								  'discount' 	  => $discount, //M14
								  'vat_amount'	  => $tax_total,
								  'net_amount'	  => $net_amount,
								  'total_fc' 	  => $total_fc,
								  'discount_fc'   => $discount_fc,
								  'vat_amount_fc' => $tax_fc,
								  'net_amount_fc'  => $net_amount_fc,
								  'subtotal'	  => $subtotal, //CHG
								  'subtotal_fc'	  => $subtotal_fc ]); //CHG
			
			//check whether Cost Accounting method or not.....
			$this->AccountingMethodUpdate($attributes, $subtotal, $tax_total, $net_amount, $this->salessplit_return->id);
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			 DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->salessplit_return = $this->salessplit_return->find($id);
		//inventory update...
		DB::beginTransaction();
		try {
		    
		    if($this->salessplit_return->sales_split_id!='') {
				DB::table('sales_split')
							->where('id', $this->salessplit_return->sales_split_id)
							->update(['is_transfer' => 0,'is_editable' => 0]);
			}
			//Transaction update....
			DB::table('account_transaction')->where('voucher_type', 'SSR')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);
			
			$this->objUtility->tallyClosingBalance( $this->salessplit_return->customer_id );
			
			$itemacs = DB::table('salessplit_return_item')->where('salessplit_return_id', $id)->select('account_id')->get();
			foreach($itemacs as $row) {
				$this->objUtility->tallyClosingBalance( $row->account_id );
			}
			
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); //DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();//DB::table('account_master')->where('master_name', 'VAT INPUT')->where('status', 1)->first();
			if($vatrow) {
				$this->objUtility->tallyClosingBalance($vatrow->payment_account);
			}
			DB::table('salessplit_return')->where('id', $id)->update(['deleted_by' => Auth::User()->id   ]);
			$this->salessplit_return->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}	
		
	}
	
		
	public function activeSalesSplitList()
	{
		return $this->salessplit_return->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->salessplit_return->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->salessplit_return->where('reference_no',$refno)->count();
	}
	
	public function check_invoice_id($invoice_id) { 
		
		return $this->salessplit_return->where('voucher_no', $invoice_id)->count();
	}
	
	public function getPIdata($did=null)
	{
		$query = $this->salessplit_return->where('salessplit_return.status',1);
		if($did)
			$query->where('salessplit_return.department_id', $did);
				
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','salessplit_return.customer_id');
						} )
					->where('salessplit_return.is_return',0)
					->select('salessplit_return.*','am.master_name AS supplier')
					->orderBY('salessplit_return.id', 'ASC')
					->get();
	}
		
	
	//ED12
	public function getSupplierInvoice($customer_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return $this->salessplit_return->where('salessplit_return.status',1)
									   ->leftJoin('payment_voucher_tr AS PV', function($join){
										   $join->on('PV.salessplit_return_id','=','salessplit_return.id');
										   $join->where('PV.deleted_at','=','0000-00-00 00:00:00');
										   $join->where('PV.status','=',1);
									   }) 
									   ->where('salessplit_return.customer_id', $customer_id)
									   ->whereIn('salessplit_return.amount_transfer',$arr)
									   ->groupBY('salessplit_return.voucher_no')
									   ->orderBY('salessplit_return.voucher_date', 'ASC')
									   ->select('salessplitreturn.*','PV.assign_amount')
									   ->get();
			
								   
		} else {
			return $this->salessplit_return->where('status',1)
									   ->where('customer_id', $customer_id)
									   ->whereIn('amount_transfer',$arr)
									   ->orderBY('voucher_date', 'ASC')
									   ->get();
		}
	}
	
	public function getOpenBalances($customer_id,$mod=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		return DB::table('opening_balance_tr')
								   //->where('tr_type','Dr')
								   ->where('status',1)
								   ->where('account_master_id', $customer_id)
								   ->where('amount','>',0)
								   ->whereNull('deleted_at')
								   ->whereIn('amount_transfer',$arr)
								   ->orderBY('tr_date', 'ASC')
								   ->select('*','amount AS net_amount')
								   ->get();
	}
	
	//ED12
	public function getPINbills($customer_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return DB::table('journal')->where('journal.status',1)
								->join('journal_entry AS JE', function($join) {
									$join->on('JE.journal_id','=','journal.id');
								})
								->leftJoin('payment_voucher_tr AS PV', function($join){
								   $join->on('PV.salessplit_return_id','=','journal.id');
								   $join->where('PV.deleted_at','=','0000-00-00 00:00:00');
								   $join->where('PV.status','=',1);
							   }) 
								//->where('JE.entry_type','Cr')
								->where('journal.deleted_at','=','0000-00-00 00:00:00')
								->where('journal.voucher_type','PIN')
								->where('JE.account_id',$customer_id)
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
								->where('JE.account_id',$customer_id)
								->whereIn('journal.is_transfer',$arr)
								->select('journal.*','JE.amount','JE.journal_id','JE.reference AS reference_no')
								->orderBY('journal.voucher_date', 'ASC')
								->get();
		}

	}
	
	//May 15.....
	public function getOthrBills($customer_id,$mod=null,$rvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		return DB::table('other_voucher_tr')->where('account_master_id', $customer_id)
										 ->whereIn('amount_transfer', $arr)
										 ->where('status',1)->whereNull('deleted_at')
										 ->get();
		
	} //......May 15
	
	
	public function getOtherCostBills($customer_id,$mod=null,$pvid=null)
	{
		$arr = ($mod)?[0,1,2]:[0,2];
		
		if($pvid) {
			
			return DB::table('pi_other_cost')->where('pi_other_cost.status',1)
							   ->join('salessplit_return AS PI', function($join) {
								   $join->on('PI.id','=','pi_other_cost.sales_split_id');
							   })
								->where('pi_other_cost.deleted_at','0000-00-00 00:00:00')
								->where('pi_other_cost.cr_account_id',$customer_id)
								->whereIn('pi_other_cost.is_transfer',$arr)
								->select('pi_other_cost.*','PI.voucher_no','PI.voucher_date')
								->get();
								
		} else {
			
			return DB::table('pi_other_cost')->where('pi_other_cost.status',1)
							   ->join('salessplit_return AS PI', function($join) {
								   $join->on('PI.id','=','pi_other_cost.sales_split_id');
							   })
								->where('pi_other_cost.deleted_at','0000-00-00 00:00:00')
								->where('pi_other_cost.cr_account_id',$customer_id)
								->whereIn('pi_other_cost.is_transfer',$arr)
								->select('pi_other_cost.*','PI.voucher_no','PI.voucher_date')
								->get();
		}

	}
	
	
	public function getInvoice($attributes)
	{
		$invoice = $this->salessplit_return->where('salessplit_return.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','salessplit_return.customer_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','salessplit_return.currency_id');
								   })
								   ->leftJoin('terms AS TR', function($join) {
									   $join->on('TR.id','=','salessplit_return.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','salessplit_return.*','AM.address','AM.city','AM.state','AM.contact_name','AM.vat_no','AM.phone','C.name AS currency','TR.description AS terms')
								   ->orderBY('salessplit_return.id', 'ASC')
								   ->first();
								   
		$items = $this->salessplit_return->where('salessplit_return.id', $attributes['document_id'])
								   ->join('salessplit_return_item AS PI', function($join) {
									   $join->on('PI.salessplit_return_id','=','salessplit_return.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   //->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','salessplit_return.id','IM.item_code','U.unit_name')
								   ->orderBY('PI.id')
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	}
	
	public function findPOdata($id)
	{
		$query = $this->salessplit_return->where('salessplit_return.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','salessplit_return.customer_id');
						} )
						->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','salessplit_return.job_id');
					    })
					    ->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','salessplit_return.vehicle_id');
					})
					->select('salessplit_return.*','am.master_name AS customer','J.code','V.name AS vehicle','V.reg_no','V.model','V.make','V.issue_plate','V.code_plate')
					->orderBY('salessplit_return.id', 'ASC')
					->first();
	}
	
	public function getItems($id)
	{
		$query = $this->salessplit_return->where('salessplit_return.id',$id);
		
		return $query->join('salessplit_return_item AS SSI', function($join) {
							$join->on('SSI.salessplit_return_id','=','salessplit_return.id');
						} )
					  ->join('account_master AS AM', function($join){
						  $join->on('AM.id','=','SSI.account_id');
					  })
					  ->leftJoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','SSI.item_jobid');
					  })
					  ->where('SSI.status',1)
					  ->where('SSI.deleted_at','0000-00-00 00:00:00')
					  ->select('SSI.*','AM.account_id AS account_code','AM.master_name','J.code AS jobcode','J.transport_type')
					  ->orderBY('SSI.id')
					  ->groupBY('SSI.id')
					  ->get();
	}
	
	public function getOrderHistory($customer_id)
	{
		$query = $this->salessplit_return->where('salessplit_return.customer_id',$customer_id)->where('salessplit_return.status',1);
		
		return $query->join('salessplit_return_item AS poi', function($join) {
							$join->on('poi.salessplit_return_id','=','salessplit_return.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->where('poi.status',1)
					  ->select('poi.*','u.unit_name','im.item_code','salessplit_return.voucher_date','salessplit_return.reference_no')->get();
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->salessplit_return->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->salessplit_return->where('voucher_no',$refno)->count();
	}
	
	public function getAdvance($customer_id)
	{
		$qry1 = DB::table('payment_voucher_entry')
								   ->join('payment_voucher', 'payment_voucher.id', '=', 'payment_voucher_entry.payment_voucher_id')
								   ->where('payment_voucher_entry.entry_type','Dr')
								   ->where('payment_voucher_entry.status',1)
								   ->where('payment_voucher_entry.account_id', $customer_id)
								   ->whereIn('payment_voucher_entry.amount_transfer',[0,2])
								   ->where('payment_voucher_entry.is_onaccount',1)
								   ->orderBY('payment_voucher_entry.id', 'ASC')
								   ->select('payment_voucher_entry.entry_type','payment_voucher_entry.id','payment_voucher_entry.balance_amount','payment_voucher_entry.reference',
											'payment_voucher.voucher_no','payment_voucher.voucher_date','payment_voucher_entry.amount AS net_total',DB::raw('"PV" AS type'));
								  
								   
		$qry2 = DB::table('journal_entry')
								   ->join('journal', 'journal.id', '=', 'journal_entry.journal_id')
								   ->where('journal_entry.entry_type','Dr')
								   ->where('journal_entry.status',1)
								   ->where('journal_entry.account_id', $customer_id)
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
			
			$result = $this->salessplit_return->where('salessplit_return.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','salessplit_return.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','salessplit_return.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								    ->select('AM.master_name AS supplier','AM.vat_no','salessplit_return.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->salessplit_return->where('salessplit_return.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','salessplit_return.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','salessplit_return.job_id');
								   })
								   ->select('AM.master_name AS supplier','AM.vat_no','salessplit_return.*','JM.name AS job')
								   ->orderBY('salessplit_return.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	public function customerWiseSummary($attributes) 
	{ //echo '<pre>';print_r($attributes);exit;
		$result = array();
		//echo '<pre>';print_r($result);exit;
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$invoice_from =(isset($attributes['invoice_from']))?$attributes['invoice_from']:'';	
		$invoice_to = (isset($attributes['invoice_to']))?$attributes['invoice_to']:'';	
		$department_id = (isset($attributes['department_id']))?$attributes['department_id']:'';	
		$query = $this->salessplit_return
					->join('salessplit_return_item AS POI', function($join) {
							$join->on('POI.salessplit_return_id','=','salessplit_return.id');
							})
							
							->join('account_master AS AM', function($join) {
	 						$join->on('AM.id','=','salessplit_return.customer_id');
		 					})
					
						->leftJoin('jobmaster AS J', function($join) {
		 					$join->on('J.id','=','salessplit_return.job_id');
						 })
	 					->where('POI.status',1);
									
		if( $date_from!='' && $date_to!='' ) { 
	 				$query->whereBetween('salessplit_return.voucher_date', array($date_from, $date_to));
					}
		if(isset($attributes['customer_id']) && $attributes['customer_id']!='')
		$query->whereIn('salessplit_return.customer_id', $attributes['customer_id']);			
	    //$orderby = ($attributes['search_type']=='jobwise')?'jobcode':'master_name';
		$result = $query->select('salessplit_return.voucher_no','salessplit_return.reference_no','salessplit_return.total','salessplit_return.vat_amount','salessplit_return.subtotal','salessplit_return.customer_id',
		 						'salessplit_return.amount_transfer','salessplit_return.discount','salessplit_return.total','salessplit_return.id AS id','salessplit_return.net_amount',
		 						'salessplit_return.voucher_date','POI.quantity','AM.id AS cid','POI.unit_price','AM.account_id','AM.master_name','AM.master_name AS supplier',
								'AM.vat_no','POI.tax_code','J.code AS jobcode','salessplit_return.amount_transfer AS amount_transfer',
								DB::raw("(SELECT SUM(SI.quantity) FROM salessplit_return_item SI WHERE (SI.salessplit_return_id=salessplit_return.id) AND (SI.status=1) AND (SI.deleted_at='0000-00-00 00:00:00')
		 						)AS quantity") )
		 						->groupBy('salessplit_return.id')
								->orderBY('salessplit_return.voucher_date','ASC')
							->get()->toArray();
							return $result;
			
			
			
	}
	public function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$department = (Session::get('department')==1)?$attributes['department_id']:null;
		//echo '<pre>';print_r($date_to);exit;
		$query = $this->salessplit_return
						->join('salessplit_return_item AS POI', function($join) {
							$join->on('POI.salessplit_return_id','=','salessplit_return.id');
						})
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','salessplit_return.customer_id');
						})
						->where('POI.status',1);
		//echo '<pre>';print_r($query);exit;
				// if($attributes['isimport']==1)
				// 	$query->where('purchase_split.is_import',1);
				// else if($attributes['isimport']==0)
				// 	$query->where('purchase_split.is_import',0);
				// else if($attributes['isimport']==2)
				// 	$query->whereIn('purchase_split.is_import',[0,1]);

				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('salessplit_return.voucher_date', array($date_from, $date_to));
				}
				
				if($department)
					$query->where('salessplit_return.department_id', $department);
					
        $query->select('salessplit_return.voucher_no','salessplit_return.net_amount','salessplit_return.reference_no','salessplit_return.total','salessplit_return.vat_amount','salessplit_return.amount_transfer','POI.tax_code','salessplit_return.discount',
							  'salessplit_return.voucher_date','POI.quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no','salessplit_return.subtotal');
						
		if(isset($attributes['type']))
		          return $query->groupBy('salessplit_return.id')->get()->toArray();
		else
		       return $query->groupBy('salessplit_return.id')->get();
					  
	}
	
// 	public function getReport($attributes)
// 	{
// 		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
// 		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
// 		$department = (Session::get('department')==1)?$attributes['department_id']:null;
// 		// if($attributes['search_type']=='customer_wise') {
// 		// 	$query = $this->sales_split
// 		// 					->join('sales_split_item AS POI', function($join) {
// 		// 						$join->on('POI.sales_split_id','=','sales_split.id');
// 		// 					})
// 		// 					->join('account_master AS AM', function($join) {
// 		// 						$join->on('AM.id','=','sales_split.customer_id');
// 		// 					})
// 		// 					// ->leftJoin('salesman AS S', function($join) {
// 		// 					// 	$join->on('S.id','=','sales_split.salesman_id');
// 		// 					//})
// 		// 					->leftJoin('jobmaster AS J', function($join) {
// 		// 					$join->on('J.id','=','sales_split.job_id');
// 		// 					 })
// 		// 					->where('POI.status',1);
							
// 		// 			if( $date_from!='' && $date_to!='' ) { 
// 		// 				$query->whereBetween('sales_split.voucher_date', array($date_from, $date_to));
// 		// 			}
					
// 		// 			//  if($attributes['salesman']!='') { 
// 		// 			// 	$query->where('sales_split.salesman_id', $attributes['salesman']);
// 		// 			//  }
					
// 		// 		 $orderby = ($attributes['search_type']=='jobwise')?'jobcode':'master_name';
// 		// 		return $query->select('sales_split.voucher_no','sales_split.reference_no','sales_split.total','sales_split.vat_amount',
// 		// 						'sales_split.amount_transfer','sales_split.discount','sales_split.total',
// 		// 						'sales_split.voucher_date','POI.quantity','POI.unit_price','AM.account_id','AM.master_name',
// 		// 						'AM.vat_no','POI.tax_code','J.code AS jobcode',
// 		// 						DB::raw("(SELECT SUM(SI.quantity) FROM sales_split_item SI WHERE (SI.sales_split_id=sales_split.id) AND (SI.status=1) AND (SI.deleted_at='0000-00-00 00:00:00')
// 		// 						)AS quantity") )
// 		// 						->groupBy('sales_split.id')
// 		// 						->orderBY('sales_split.voucher_date','ASC')
// 		// 						->get();
								

// 		// }else {
// 			if($attributes['search_type']=='summary') 
// 			{
// 		$query = $this->sales_split
// 						->join('sales_split_item AS POI', function($join) {
// 							$join->on('POI.sales_split_id','=','sales_split.id');
// 						})
// 						->join('account_master AS AM', function($join) {
// 							$join->on('AM.id','=','sales_split.customer_id');
// 						})
// 						->where('POI.status',1);

// 				// if($attributes['isimport']==1)
// 				// 	$query->where('sales_split.is_import',1);
// 				// else if($attributes['isimport']==0)
// 				// 	$query->where('sales_split.is_import',0);
// 				// else if($attributes['isimport']==2)
// 				// 	$query->whereIn('sales_split.is_import',[0,1]);

// 				if( $date_from!='' && $date_to!='' ) { 
// 					$query->whereBetween('sales_split.voucher_date', array($date_from, $date_to));
// 				}
				
// 				if($department)
// 					$query->where('sales_split.department_id', $department);
					
// 		$query->select('sales_split.voucher_no','sales_split.reference_no','sales_split.total','sales_split.vat_amount','sales_split.amount_transfer','POI.tax_code','sales_split.discount',
// 							  'sales_split.voucher_date','POI.quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no','sales_split.net_amount','sales_split.subtotal');
											
// 		if(isset($attributes['type']))
// 			return $query->groupBy('sales_split.id')->get()->toArray();
// 		else
// 			return $query->groupBy('sales_split.id')->get();}
	
// }
	public function getOtherCost($id)
	{
		$query = $this->salessplit_return->where('salessplit_return.id',$id);
		
		return $query->join('pi_other_cost AS pi', function($join) {
							$join->on('pi.sales_split_id','=','salessplit_return.id');
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
		
		$query = $this->salessplit_return->where('salessplit_return.status',1);
		if($deptid!=0)
			$query->where('salessplit_return.department_id', $deptid);
			
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','salessplit_return.customer_id');
						} )
					->count();
	}
	
	public function purchaseInvoiceList($type,$start,$limit,$order,$dir,$search,$dept=null)
	{
		//CHECK DEPARTMENT.......
		$deptid = (Session::get('department')==1)?Auth::user()->department_id:0;
		
		$query = $this->salessplit_return->where('salessplit_return.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','salessplit_return.customer_id');
						})
						->leftjoin('jobmaster','jobmaster.id','=','salessplit_return.job_id');
						
				if($deptid!=0) //dept chk
					$query->where('salessplit_return.department_id', $deptid);
				else {
					if($dept!='' && $dept!=0) {
						$query->where('salessplit_return.department_id', $dept);
					}
				}
					
				if($search) {
					$query->where(function($qry) use($search) {
						$qry->where('salessplit_return.voucher_no','LIKE',"%{$search}%")
							->orWhere('salessplit_return.reference_no', 'LIKE',"%{$search}%")
							->orWhere('am.master_name', 'LIKE',"%{$search}%");
					});
					/* $query->where('sales_split.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('sales_split.reference_no', 'LIKE',"%{$search}%")
						  ->orWhere('am.master_name', 'LIKE',"%{$search}%"); */
				}
				
				$query->select('salessplit_return.*','am.master_name AS supplier','jobmaster.code AS jobno')
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
		$query = $this->salessplit_return->where('salessplit_return.customer_id',$id);
		
		$query->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','salessplit_return.customer_id');
						})
					->join('salessplit_return_item AS poi', function($join) {
							$join->on('poi.salessplit_return_id','=','salessplit_return.id');
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
					$query->whereBetween('salessplit_return.voucher_date', [$date_from, $date_to]);
					  
						
		return $query->select('poi.*','im.item_code','iu.packing','iu.is_baseqty','poi.item_total AS line_total',
							 'salessplit_return.voucher_no','salessplit_return.voucher_date','AM.master_name')
					 ->groupBy('poi.id')
					 ->orderBY('poi.id')
					 ->get();
	}
	
	public function getTransactionList($attributes) 
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->salessplit_return
								   ->join('salessplit_return_item AS PI', function($join) {
									   $join->on('PI.salessplit_return_id','=','salessplit_return.id');
								   })
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','salessplit_return.customer_id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->where('salessplit_return.status',1);
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('salessplit_return.voucher_date',[$date_from, $date_to]);
					
		$result = $qry->select('AM.master_name AS supplier','salessplit_return.id','salessplit_return.voucher_no','salessplit_return.voucher_date',
							   'IM.item_code','IM.description','PI.quantity','PI.unit_price','PI.vat_amount','PI.total_price','PI.othercost_unit',
							   'PI.netcost_unit')
								->orderBY('salessplit_return.voucher_date', 'ASC')
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
	
	
}

