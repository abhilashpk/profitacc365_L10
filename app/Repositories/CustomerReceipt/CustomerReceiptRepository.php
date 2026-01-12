<?php
declare(strict_types=1);
namespace App\Repositories\CustomerReceipt;

use App\Models\CustomerReceipt;
use App\Models\CustomerReceiptTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class CustomerReceiptRepository extends AbstractValidator implements CustomerReceiptInterface {
	
	protected $customer_receipt;
	
	protected static $rules = [];
	
	public function __construct(CustomerReceipt $customer_receipt) {
		$this->customer_receipt = $customer_receipt;
		
	}
	
	public function all()
	{
		return $this->customer_receipt->get();
	}
	
	public function find($id)
	{
		return $this->customer_receipt->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;
		$this->customer_receipt->voucher_no    = $attributes['voucher_no'];
		$this->customer_receipt->voucher_type    = $attributes['voucher_type'];
		$this->customer_receipt->voucher_date  = date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->customer_receipt->reference  = $attributes['reference'];
		$this->customer_receipt->dr_account_id  = $attributes['dr_account_id'];
		$this->customer_receipt->description   = $attributes['description'];
		$this->customer_receipt->transaction   = $attributes['transaction'];
		$this->customer_receipt->amount   = $attributes['amount'];
		$this->customer_receipt->job_id 		= $attributes['job_id'];
		$this->customer_receipt->department_id 		= $attributes['department_id'];
		$this->customer_receipt->is_fc 		= isset($attributes['is_fc'])?1:0;
		$this->customer_receipt->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->customer_receipt->currency_rate = $currency_rate;
		$this->customer_receipt->amount_fc   = $attributes['amount'] * $currency_rate;
		$this->customer_receipt->cheque_no   = isset($attributes['cheque_no'])?$attributes['cheque_no']:'';
		$this->customer_receipt->cheque_date   = isset($attributes['cheque_date'])?date('Y-m-d', strtotime($attributes['cheque_date'])):'';
		$this->customer_receipt->bank_id   = isset($attributes['bank_id'])?$attributes['bank_id']:'';
		$this->customer_receipt->customer_id   = $attributes['customer_id'];
		$this->customer_receipt->tr_description = $attributes['tr_description'];
		$this->customer_receipt->depositor = $attributes['depositor'];
		$this->customer_receipt->is_transfer = ($attributes['voucher_type']=='PDCR')?0:1;

		return true;
	}
	
	private function setTrInputValue($attributes, $customerReceiptTr, $key) 
	{
		$customerReceiptTr->customer_receipt_id = $this->customer_receipt->id;
		$customerReceiptTr->sales_invoice_id = $attributes['sales_invoice_id'][$key];
		$customerReceiptTr->assign_amount    		= $attributes['line_amount'][$key];
		$customerReceiptTr->bill_type = $attributes['bill_type'][$key];
		$customerReceiptTr->status 		= 1;
		
		return $attributes['line_amount'][$key];
	}
	
	private function updateClosingBalance($account_id, $amount, $type, $voucher_type=null)
	{
		if($type=='Dr') {
			DB::table('account_master')
						->where('id', $account_id)
						->update(['cl_balance' => DB::raw('cl_balance + '.$amount)
						]);
		} else if($type=='Cr') {
			if($voucher_type=='PDCR') {
				DB::table('account_master')
							->where('id', $account_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount),
									  'pdc_amount' => DB::raw('pdc_amount + '.$amount)
							]);
			} else {
				DB::table('account_master')
							->where('id', $account_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
							]);
			}
		}
		
		return true;
	}
	
	private function setTransactionStatus($attributes, $key)
	{
		//if amount partially transfered, update pending amount.
		if(isset($attributes['actual_amount']) && ($attributes['line_amount'][$key] != $attributes['actual_amount'][$key])) {
			if( isset($attributes['sales_invoice_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['sales_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
							
				} elseif($attributes['bill_type'][$key]=='OB') {
				
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['sales_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
				}
			}
		} else {
			
				//update as completely paid.
				if($attributes['bill_type'][$key]=='SI')  {
					DB::table('sales_invoice')
								->where('id', $attributes['sales_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
								
				} else if($attributes['bill_type'][$key]=='OB') {
					
					DB::table('opening_balance_tr')
								->where('id', $attributes['sales_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				}
		}
	}
	
	private function setAccountTransactionRV($attributes, $voucher_id, $type, $key=null)
	{
		if($type=='Cr') {
			$account_master_id = $attributes['customer_id'];
			$amount = $attributes['line_amount'][$key];
			$referencefrm = $attributes['refno'][$key];
		} else {
			$account_master_id = $attributes['dr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['reference'];
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'RV',
						    'voucher_type_id'   => $voucher_id,
							'account_master_id' => $account_master_id,
							'transaction_type'  => $type,
							'amount'   			=> $amount,
							'status' 			=> 1,
							'created_at' 		=> now(),
							'created_by' 		=> 1,
							'description' 		=> $attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $referencefrm
						]);
		
		/* if($type=='Cr') {
			DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$attributes['line_amount'][$key])]);
		} else 
				DB::table('account_master')
								->where('id', $account_master_id)
								->update(['cl_balance' => DB::raw('cl_balance + '.$attributes['line_amount'][$key])]); */
		return true;
	}
	
	private function setAccountTransactionRVUpdate($attributes, $voucher_id, $type, $key)
	{
		$account_master_id = ($type=='Cr')?$attributes['customer_id']:$attributes['dr_account_id'];
		
		DB::table('account_transaction')
					->where('voucher_type', 'RV')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $account_master_id)
					->where('reference_from', $attributes['refno'][$key])
					->update([  
							'amount'   			=> $attributes['line_amount'][$key],
							'modify_at' 		=> now(),
							'modify_by' 		=> 1,
							'description' 		=> $attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
						]);
		
		return true;
	}
	
	private function setAccountTransactionRVDelete($attributes, $voucher_id, $type, $key)
	{
			$account_master_id = ($type=='Cr')?$attributes['customer_id']:$attributes['dr_account_id'];
			
			DB::table('account_transaction')
						->where('voucher_type', 'RV')
						->where('voucher_type_id', $voucher_id)
						->where('account_master_id', $account_master_id)
						->where('reference_from', $attributes['refno'][$key])
						->update([ 'status' 		=> 0,
								   'deleted_at' 	=> now()]);
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			if($this->setInputValue($attributes)) {
				$this->customer_receipt->status = 1;
				$this->customer_receipt->created_at = now();
				$this->customer_receipt->created_by = 1;
				$this->customer_receipt->fill($attributes)->save();
			}
			
			//transactions insert
			if($this->customer_receipt->id && !empty( array_filter($attributes['line_amount']))) {
				$line_total = 0;
				foreach($attributes['tag'] as $k => $key) { 
					$customerReceiptTr = new CustomerReceiptTr();
					$line_total 		+= $this->setTrInputValue($attributes, $customerReceiptTr, $key);
					$this->customer_receipt->TransactionAdd()->save($customerReceiptTr);
						
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account Cr transactions...
					$this->setAccountTransactionRV($attributes, $this->customer_receipt->id, 'Cr', $key);
						
				}
				
				//update account Dr transactions...
				$this->setAccountTransactionRV($attributes, $this->customer_receipt->id, 'Dr');
				
				//update debit, credit, difference amount
				DB::table('customer_receipt')
							->where('id', $this->customer_receipt->id)
							->update(['debit'     => $attributes['amount'],
									  'credit' 	  => $line_total,
									  'difference'	  => $attributes['amount'] - $line_total ]);
				
				//update closing balance of debitor account
				if($this->updateClosingBalance($attributes['dr_account_id'], $attributes['amount'], 'Dr')) {
					//update closing balance of debitor account
					$this->updateClosingBalance($attributes['customer_id'], $attributes['amount'], 'Cr', $attributes['voucher_type']);
				}
				
				//cheque no insert...
				if(isset($attributes['cheque_no']) && $attributes['cheque_no']!=''){
					DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'], 'bank_id' => $attributes['bank_id'] ]);
				}
			}
			
			//update voucher no........
			if($this->customer_receipt->id) {
				DB::table('account_setting')
						->where('id', $attributes['voucher'])
						->update(['voucher_no' => DB::raw('voucher_no + 1')]);
						
			}
			//exit;
			return true;
		}
		//throw new ValidationException('customer_receipt validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->customer_receipt = $this->find($id);
		
		if($this->customer_receipt->id && !empty( array_filter($attributes['line_amount']))) {
			$line_total = 0;
			
			foreach($attributes['tag'] as $k => $key) { 
			
				if($attributes['id'][$key]!='') {
					$customerReceiptTr = CustomerReceiptTr::find($attributes['id'][$key]);
					$invrow['assign_amount'] = $attributes['line_amount'][$key];
					$invrow['sales_invoice_id'] = $attributes['sales_invoice_id'][$key];
					$customerReceiptTr->update($invrow);
					
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account transactions...
					if($this->setAccountTransactionRVUpdate($attributes, $this->customer_receipt->id, 'Cr', $key))
						$this->setAccountTransactionRVUpdate($attributes, $this->customer_receipt->id, 'Dr', $key);
					
				} else {
					
					//new entry.....
					$customerReceiptTr = new CustomerReceiptTr();
					$line_total 		+= $this->setTrInputValue($attributes, $customerReceiptTr, $key);
					$this->customer_receipt->TransactionAdd()->save($customerReceiptTr);
						
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account transactions...
					if($this->setAccountTransactionRV($attributes, $this->customer_receipt->id, 'Cr', $key))
						$this->setAccountTransactionRV($attributes, $this->customer_receipt->id, 'Dr', $key);
				}
				
			}
			
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = array_unique(explode(',', $attributes['remove_item']));
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('customer_receipt_tr')->where('id', $attributes['id'][$row])->update(['status' => 0, 'deleted_at' => now()]);
					
					if($this->setAccountTransactionRVDelete($attributes, $this->customer_receipt->id, 'Cr', $row))
						$this->setAccountTransactionRVDelete($attributes, $this->customer_receipt->id, 'Dr', $row);
		
				}
			}
		
			//update closing balance of debitor account
			if($this->updateClosingBalance($attributes['dr_account_id'], $attributes['amount'], 'Dr')) {
				//update closing balance of debitor account
				$this->updateClosingBalance($attributes['customer_id'], $attributes['amount'], 'Cr', $attributes['voucher_type']);
			}
			
		}
			
		$this->customer_receipt->amount = $attributes['amount'];
		$this->customer_receipt->debit = $attributes['debit'];
		$this->customer_receipt->credit = $attributes['credit'];
		$this->customer_receipt->difference = $attributes['debit'] - $attributes['credit'];
		$this->customer_receipt->modify_at = now();
		$this->customer_receipt->modify_by = 1;
		$this->customer_receipt->fill($attributes)->save();
		return true;
	}
	
		
	public function delete($id)
	{
		$this->customer_receipt = $this->customer_receipt->find($id);
		
		//Transaction update....
		DB::table('account_transaction')->where('voucher_type', 'RV')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now() ]);
		
		DB::table('account_master')->where('id', $this->customer_receipt->dr_account_id)->update(['cl_balance' => DB::raw('cl_balance - '.$this->customer_receipt->amount)]);
		
		if($this->customer_receipt->voucher_type=='PDCR')
			DB::table('account_master')->where('id', $this->customer_receipt->customer_id)->update(['cl_balance' => DB::raw('cl_balance + '.$this->customer_receipt->amount), 'pdc_amount' => DB::raw('pdc_amount - '.$this->customer_receipt->amount)]);
		else
			DB::table('account_master')->where('id', $this->customer_receipt->customer_id)->update(['cl_balance' => DB::raw('cl_balance + '.$this->customer_receipt->amount)]);
		
		//sales invoice bill update.....
		$rows = DB::table('customer_receipt_tr')->where('customer_receipt_id', $id)->where('status',1)->get();
		if($rows) {
			foreach($rows as $row)
			{
				DB::table('sales_invoice')->where('id', $row->sales_invoice_id)->update(['balance_amount' => DB::raw('balance_amount + '.$row->assign_amount) ]);
				DB::table('customer_receipt_tr')->where('id', $row->id)->update(['status' => 0,'deleted_at' => now() ]);
			}
		}
		
		$this->customer_receipt->delete();
		
	}
	
	public function CustomerReceiptList()
	{
		$query = $this->customer_receipt->where('customer_receipt.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_receipt.dr_account_id');
						} )
					->join('account_master AS am2', function($join) {
							$join->on('am2.id','=','customer_receipt.customer_id');
						} )
					->select('customer_receipt.*','am.master_name AS debiter', 'am2.master_name AS creditor')
					->orderBY('customer_receipt.id', 'DESC')
					->get();
	}
	
	public function PDCReceivedList()
	{
		$query = $this->customer_receipt->where('customer_receipt.status',1)->where('voucher_type', 'PDCR')->where('is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_receipt.dr_account_id');
						} )
					->join('account_master AS c', function($join) {
							$join->on('c.id','=','customer_receipt.customer_id');
						} )
					->join('bank AS b', function($join) {
							$join->on('b.id','=','customer_receipt.bank_id');
						} )
					->select('customer_receipt.*','am.master_name AS creditor','b.name AS bankname','b.code','c.master_name AS customer')
					->orderBY('customer_receipt.cheque_date', 'ASC')
					->get();
	}
	
	private function setAccountTransaction($id, $attributes, $type, $key)
	{
		$account_master_id = ($type=='Cr')?$attributes['cr_account_id'][$key]:$attributes['dr_account_id'][$key];
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'DB',
						    'voucher_type_id'   => $id,
							'account_master_id' => $account_master_id,
							'transaction_type'  => $type,
							'amount'   			=> $attributes['amount'][$key],
							'status' 			=> 1,
							'created_at' 		=> now(),
							'created_by' 		=> 1,
							'reference'			=> $attributes['id'][$key],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key] 
						]);
						
		if($type=='Dr') {
			DB::table('account_master')
						->where('id', $attributes['dr_account_id'][$key])
						->update(['cl_balance' => DB::raw('cl_balance + '.$attributes['amount'][$key] )
						]);
		} else {
			DB::table('account_master')
						->where('id', $attributes['cr_account_id'][$key])
						->update(['cl_balance' => DB::raw('cl_balance - '.$attributes['amount'][$key] )
						]);
		}
		
		return true;
	}
	
	public function PdcReceivedSubmit($attributes)
	{
		foreach($attributes['tag'] as $k => $key) { 
			
			$id = DB::table('pdc_received')
					->insertGetId([ 'voucher_id' 		=> $attributes['id'][$key],
									'voucher_type'   => 'DB',
									'dr_account_id' => $attributes['dr_account_id'][$key],
									'cr_account_id' => $attributes['cr_account_id'][$key],
									'reference'  => $attributes['reference'][$key],
									'amount'   			=> $attributes['amount'][$key],
									'status' 			=> 1,
									'created_at' 		=> now(),
									'created_by' 		=> 1,
									'voucher_date'		=> date('Y-m-d', strtotime($attributes['voucher_date']))
								]);
							
			if($this->setAccountTransaction($id, $attributes, 'Dr', $key))
				$this->setAccountTransaction($id, $attributes, 'Cr', $key);
			
			//update PDC transfer status.......
			if($id) {
				if($attributes['voucher_type'][$key]=="PDCR") {
					
					 DB::table('receipt_voucher')
						->where('id', $attributes['id'][$key])
						->update(['is_transfer' => 1]);
						
				} else if($attributes['voucher_type'][$key]=="JV") {
					
					 DB::table('journal')
						 ->where('id', $attributes['id'][$key])
						 ->update(['is_transfer' => 1]);
						 
				} else if($attributes['voucher_type'][$key]=="OBD") {
					
					 DB::table('opening_balance_tr')
						 ->where('id', $attributes['id'][$key])
						 ->update(['amount_transfer' => 1]);
				}
			}
			
		}
	}
	
	public function findCRdata($id)
	{
		return $this->customer_receipt
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_receipt.dr_account_id');
						} )
						->join('account_master AS cus', function($join) {
							$join->on('cus.id','=','customer_receipt.customer_id');
						} )
						->where('customer_receipt.id', $id)
						->select('customer_receipt.*','am.master_name','cus.master_name AS customer')->first();
	}
	
	public function findCRinvoices($id)
	{
		return $this->customer_receipt->where('customer_receipt.id', $id)
								   ->join('customer_receipt_tr AS PI', function($join) {
									   $join->on('PI.customer_receipt_id','=','customer_receipt.id');
								   })
								   ->where('PI.status',1)
								   ->select('PI.*')
								   ->get();
	}
	
	public function PdcReceivedUndo($attributes)
	{
		foreach($attributes['tag'] as $key => $val) { 
			
			if($attributes['voucher_type'][$val]=="PDCR") {
				
				$rvEntry = DB::table('receipt_voucher_entry')->where('receipt_voucher_id',$attributes['id'][$val])->get();
				//echo '<pre>';print_r($rvEntry);exit;
							
				foreach($rvEntry as $entry) {
					if($entry->entry_type=="Dr"){
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance - '.$entry->amount)]);
					} else {
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance + '.$entry->amount),
											  'pdc_amount' => DB::raw('pdc_amount - '.$entry->amount) ]);
					}
					
					DB::table('receipt_voucher_entry')->where('id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
								
					DB::table('account_transaction')->where('voucher_type',"RV")->where('voucher_type_id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
				} 
				
				DB::table('receipt_voucher')->where('id',$attributes['id'][$val])
							->update(['status' => 0, 'deleted_at' => now()]);
							
			} else if($attributes['voucher_type'][$val]=="JV") {
				
				$rvEntry = DB::table('journal_entry')->where('journal_id',$attributes['id'][$val])->get();
				//echo '<pre>';print_r($rvEntry);exit;
							
				foreach($rvEntry as $entry) {
					if($entry->entry_type=="Dr"){
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance - '.$entry->amount)]);
					} else {
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance + '.$entry->amount),
											  'pdc_amount' => DB::raw('pdc_amount - '.$entry->amount) ]);
					}
					
					DB::table('journal_entry')->where('id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
								
					DB::table('account_transaction')->where('voucher_type',"JV")->where('voucher_type_id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
				} 
				
				DB::table('journal')->where('id',$attributes['id'][$val])
							->update(['status' => 0, 'deleted_at' => now()]);
							
			}
			
		}
		
		return true;
	}
}

