<?php
declare(strict_types=1);
namespace App\Repositories\SupplierPayment;

use App\Models\SupplierPayment;
use App\Models\SupplierPaymentTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class SupplierPaymentRepository extends AbstractValidator implements SupplierPaymentInterface {
	
	protected $supplier_payment;
	
	protected static $rules = [];
	
	public function __construct(SupplierPayment $supplier_payment) {
		$this->supplier_payment = $supplier_payment;
		
	}
	
	public function all()
	{
		return $this->supplier_payment->get();
	}
	
	public function find($id)
	{
		return $this->supplier_payment->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;
		$this->supplier_payment->voucher_no    = $attributes['voucher_no'];
		$this->supplier_payment->voucher_type    = $attributes['voucher_type'];
		$this->supplier_payment->voucher_date  = date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->supplier_payment->reference  = $attributes['reference'];
		$this->supplier_payment->cr_account_id  = $attributes['cr_account_id'];
		$this->supplier_payment->description   = $attributes['description'];
		$this->supplier_payment->transaction   = $attributes['transaction'];
		$this->supplier_payment->amount   = $attributes['amount'];
		$this->supplier_payment->job_id 		= $attributes['job_id'];
		$this->supplier_payment->department_id 		= $attributes['department_id'];
		$this->supplier_payment->is_fc 		= isset($attributes['is_fc'])?1:0;
		$this->supplier_payment->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->supplier_payment->currency_rate = $currency_rate;
		$this->supplier_payment->amount_fc   = $attributes['amount'] * $currency_rate;
		$this->supplier_payment->cheque_no   = isset($attributes['cheque_no'])?$attributes['cheque_no']:'';
		$this->supplier_payment->cheque_date   = isset($attributes['cheque_date'])?date('Y-m-d', strtotime($attributes['cheque_date'])):'';
		$this->supplier_payment->bank_id   = isset($attributes['bank_id'])?$attributes['bank_id']:'';
		$this->supplier_payment->supplier_id   = $attributes['supplier_id'];
		$this->supplier_payment->tr_description = $attributes['tr_description'];
		$this->supplier_payment->depositor = $attributes['depositor'];
		$this->supplier_payment->is_transfer = ($attributes['voucher_type']=='PDCI')?0:1;

		return true;
	}
	
	private function setTrInputValue($attributes, $supplierPaymentTr, $key) 
	{
		$supplierPaymentTr->supplier_payment_id = $this->supplier_payment->id;
		$supplierPaymentTr->purchase_invoice_id = $attributes['purchase_invoice_id'][$key];
		$supplierPaymentTr->assign_amount    	= $attributes['line_amount'][$key];
		$supplierPaymentTr->bill_type 			= $attributes['bill_type'][$key];
		$supplierPaymentTr->status 		= 1;
		
		return $attributes['line_amount'][$key];
	}
	
	private function updateClosingBalance($account_id, $amount, $type, $voucher_type=null)
	{
		if($type=='Dr') {
			if($voucher_type=='PDCI') {
				DB::table('account_master')
							->where('id', $account_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount),//***************needed to recheck....**************************
									  'pdc_amount' => DB::raw('pdc_amount + '.$amount)
							]);
			} else {
				DB::table('account_master')
							->where('id', $account_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount) //***************needed to recheck....**************************
							]);
			}
		} else {
			DB::table('account_master')
						->where('id', $account_id)
						->update(['cl_balance' => DB::raw('cl_balance + '.$amount)
						]);
		}
		
		return true;
	}
	
	private function setTransactionStatus($attributes, $key)
	{
		//if amount partially transfered, update pending amount.
		if(isset($attributes['actual_amount']) && ($attributes['line_amount'][$key] != $attributes['actual_amount'][$key])) {
			if( isset($attributes['purchase_invoice_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='PI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('purchase_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
				} else if($attributes['bill_type'][$key]=='OB') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
								
				} else if($attributes['bill_type'][$key]=='PIN') {
					echo $balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];exit;
					//update as partially paid.
					DB::table('journal')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'is_transfer' => 2]);
				}
			}
		} else {
				//update as completely paid.
				if($attributes['bill_type'][$key]=='PI')  {
					DB::table('purchase_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				} else if($attributes['bill_type'][$key]=='OB') {
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				} else if($attributes['bill_type'][$key]=='PIN') {
					DB::table('journal')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'is_transfer' => 1]);
				}
		}
	}
	
	private function setAccountTransactionPV($attributes, $voucher_id, $type, $key=null)
	{
		//$account_master_id = ($type=='Cr')?$attributes['cr_account_id']:$attributes['supplier_id'];
		
		if($type=='Dr') {
			$account_master_id = $attributes['supplier_id'];
			$amount = $attributes['line_amount'][$key];
			$referencefrm = $attributes['refno'][$key];
		} else {
			$account_master_id = $attributes['cr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['reference'];
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'PV',
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
							->update(['cl_balance' => DB::raw('cl_balance + '.$attributes['line_amount'][$key])]);
		} else 
				DB::table('account_master')
								->where('id', $account_master_id)
								->update(['cl_balance' => DB::raw('cl_balance - '.$attributes['line_amount'][$key])]); */
		return true;
	}
	
	private function setAccountTransactionPVUpdate($attributes, $voucher_id, $type, $key)
	{
		$account_master_id = ($type=='Cr')?$attributes['cr_account_id']:$attributes['supplier_id'];
		
		DB::table('account_transaction')
					->where('voucher_type', 'PV')
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
	
	private function setAccountTransactionPVDelete($attributes, $voucher_id, $type, $key)
	{
			$account_master_id = ($type=='Cr')?$attributes['cr_account_id']:$attributes['supplier_id'];
			
			DB::table('account_transaction')
						->where('voucher_type', 'PV')
						->where('voucher_type_id', $voucher_id)
						->where('account_master_id', $account_master_id)
						->where('reference_from', $attributes['refno'][$key])
						->update([ 'status' 		=> 0,
								   'deleted_at' 	=> now()]);
	}
	
	public function create($attributes)
	{  //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
			if($this->setInputValue($attributes)) {
				$this->supplier_payment->status = 1;
				$this->supplier_payment->created_at = now();
				$this->supplier_payment->created_by = 1;
				$this->supplier_payment->fill($attributes)->save();
			}
			
			//transactions insert
			if($this->supplier_payment->id && !empty( array_filter($attributes['line_amount']))) {
				$line_total = 0;
				foreach($attributes['tag'] as $k => $key) { 
					$supplierPaymentTr = new SupplierPaymentTr();
					$line_total 	+= $this->setTrInputValue($attributes, $supplierPaymentTr, $key);
					$this->supplier_payment->TransactionAdd()->save($supplierPaymentTr);
						
					//update purchase invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account Dr. transactions...
					$this->setAccountTransactionPV($attributes, $this->supplier_payment->id, 'Dr', $key);
				}
				
				//update account Dr. transactions...
				$this->setAccountTransactionPV($attributes, $this->supplier_payment->id, 'Cr');
				
				//update debit, credit, difference amount
				DB::table('supplier_payment')
							->where('id', $this->supplier_payment->id)
							->update(['debit'     => $line_total,
									  'credit' 	  => $attributes['amount'],
									  'difference'	  => $line_total - $attributes['amount'] ]);
				
				//update closing balance of debitor account
				if($this->updateClosingBalance($attributes['supplier_id'], $attributes['amount'], 'Dr',$attributes['voucher_type'])) {
					//update closing balance of debitor account
					$this->updateClosingBalance($attributes['cr_account_id'], $attributes['amount'], 'Cr');
				}
				
				//cheque no insert...
				if(isset($attributes['cheque_no']) && $attributes['cheque_no']!=''){
					DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'], 'bank_id' => $attributes['bank_id'] ]);
				}
				
			}
			
			//update voucher no........
			if($this->supplier_payment->id) {
				DB::table('account_setting')
						->where('id', $attributes['voucher'])
						->update(['voucher_no' => DB::raw('voucher_no + 1')]);
			}			
			/* if($this->supplier_payment->id) {
				 DB::table('voucher_no')
					->where('voucher_type', 'SP')
					->update(['no' => $this->supplier_payment->id]);
			} */
			
			return true;
		}
		//throw new ValidationException('supplier_payment validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->supplier_payment = $this->find($id);
		
		if($this->supplier_payment->id && !empty( array_filter($attributes['line_amount']))) {
			$line_total = 0;
			
			foreach($attributes['tag'] as $k => $key) { 
			
				if($attributes['id'][$key]!='') {
					$supplierPaymentTr = SupplierPaymentTr::find($attributes['id'][$key]);
					$invrow['assign_amount'] = $attributes['line_amount'][$key];
					$invrow['purchase_invoice_id'] = $attributes['purchase_invoice_id'][$key];
					$supplierPaymentTr->update($invrow);
					
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account transactions...
					if($this->setAccountTransactionPVUpdate($attributes, $this->supplier_payment->id, 'Cr', $key))
						$this->setAccountTransactionPVUpdate($attributes, $this->supplier_payment->id, 'Dr', $key);
					
				} else {
					
					//new entry.....
					$supplierPaymentTr = new SupplierPaymentTr();
					$line_total 		+= $this->setTrInputValue($attributes, $supplierPaymentTr, $key);
					$this->supplier_payment->TransactionAdd()->save($supplierPaymentTr);
						
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account transactions...
					if($this->setAccountTransactionPV($attributes, $this->supplier_payment->id, 'Cr', $key))
						$this->setAccountTransactionPV($attributes, $this->supplier_payment->id, 'Dr', $key);
				}
				
			}
			
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = array_unique(explode(',', $attributes['remove_item']));
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('supplier_payment_tr')->where('id', $attributes['id'][$row])->update(['status' => 0, 'deleted_at' => now()]);
					
					if($this->setAccountTransactionPVDelete($attributes, $this->supplier_payment->id, 'Cr', $row))
						$this->setAccountTransactionPVDelete($attributes, $this->supplier_payment->id, 'Dr', $row);
		
				}
			}
		
			//update closing balance of debitor account
			if($this->updateClosingBalance($attributes['supplier_id'], $attributes['amount'], 'Dr',$attributes['voucher_type'])) {
				//update closing balance of debitor account
				$this->updateClosingBalance($attributes['cr_account_id'], $attributes['amount'], 'Cr');
			}
			
		}
			
		$this->supplier_payment->amount = $attributes['amount'];
		$this->supplier_payment->debit = $attributes['debit'];
		$this->supplier_payment->credit = $attributes['credit'];
		$this->supplier_payment->difference = $attributes['debit'] - $attributes['credit'];
		$this->supplier_payment->modify_at = now();
		$this->supplier_payment->modify_by = 1;
		$this->supplier_payment->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->supplier_payment = $this->supplier_payment->find($id);
		
		//Transaction update....
		DB::table('account_transaction')->where('voucher_type', 'PV')->where('voucher_type_id', $id)->update(['status' => 0,'deleted_at' => now() ]);
		
		DB::table('account_master')->where('id', $this->supplier_payment->cr_account_id)->update(['cl_balance' => DB::raw('cl_balance + '.$this->supplier_payment->amount)]);
		
		if($this->supplier_payment->voucher_type=='PDCI')
			DB::table('account_master')->where('id', $this->supplier_payment->supplier_id)->update(['cl_balance' => DB::raw('cl_balance - '.$this->supplier_payment->amount), 'pdc_amount' => DB::raw('pdc_amount - '.$this->supplier_payment->amount)]);
		else
			DB::table('account_master')->where('id', $this->supplier_payment->supplier_id)->update(['cl_balance' => DB::raw('cl_balance - '.$this->supplier_payment->amount)]);
		
		//purchase invoice bill update.....
		$rows = DB::table('supplier_payment_tr')->where('supplier_payment_id', $id)->where('status',1)->get();
		if($rows) {
			foreach($rows as $row)
			{
				DB::table('purchase_invoice')->where('id', $row->purchase_invoice_id)->update(['balance_amount' => DB::raw('balance_amount + '.$row->assign_amount) ]);
				DB::table('supplier_payment_tr')->where('id', $row->id)->update(['status' => 0,'deleted_at' => now() ]);
			}
		}
		
		$this->supplier_payment->delete();
	}
	
	public function SupplierPaymentList()
	{
		$query = $this->supplier_payment->where('supplier_payment.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_payment.cr_account_id');
						} )
					->join('account_master AS am2', function($join) {
							$join->on('am2.id','=','supplier_payment.supplier_id');
						} )
					->select('supplier_payment.*','am.master_name AS creditor', 'am2.master_name AS debitor')
					->orderBY('supplier_payment.id', 'DESC')
					->get();
	}
	
	public function PDCIssuedList()
	{
		$query = $this->supplier_payment->where('supplier_payment.status',1)->where('voucher_type', 'PDCI')->where('is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_payment.cr_account_id');
						} )
					->join('account_master AS s', function($join) {
							$join->on('s.id','=','supplier_payment.supplier_id');
						} )
					->join('bank AS b', function($join) {
							$join->on('b.id','=','supplier_payment.bank_id');
						} )
					->select('supplier_payment.*','am.master_name AS creditor','b.code','s.master_name AS supplier')
					->orderBY('supplier_payment.cheque_date', 'ASC')
					->get();
	}
	
	private function setAccountTransaction($id, $attributes, $type, $key)
	{
		$account_master_id = ($type=='Cr')?$attributes['cr_account_id'][$key]:$attributes['dr_account_id'][$key];
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'CB',
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
						
		if($type=='Cr') {
			DB::table('account_master')
						->where('id', $attributes['cr_account_id'][$key])
						->update(['cl_balance' => DB::raw('cl_balance + '.$attributes['amount'][$key] )
						]);
		} else {
			DB::table('account_master')
						->where('id', $attributes['dr_account_id'][$key])
						->update(['cl_balance' => DB::raw('cl_balance - '.$attributes['amount'][$key] )
						]);
		}
		
		return true;
	}
	
	public function PdcIssuedSubmit($attributes)
	{
		foreach($attributes['tag'] as $k => $key) { 
			
			$id = DB::table('pdc_issued')
					->insertGetId(['voucher_id' => $attributes['id'][$key],
								'voucher_type'  => 'CB',
								'cr_account_id' => $attributes['cr_account_id'][$key],
								'dr_account_id' => $attributes['dr_account_id'][$key],
								'reference'  => $attributes['reference'][$key],
								'amount'   	=> $attributes['amount'][$key],
								'status' 	=> 1,
								'created_at' => now(),
								'created_by' => 1,
								'voucher_date'	=> date('Y-m-d', strtotime($attributes['voucher_date']))
								]);
							
			if($this->setAccountTransaction($id, $attributes, 'Cr', $key))
				$this->setAccountTransaction($id, $attributes, 'Dr', $key);
			
			//update PDC transfer status.......
			if($id) {
				if($attributes['voucher_type'][$key]=="PDCI") {
					 DB::table('payment_voucher')
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
	
	public function findSPdata($id)
	{
		return $this->supplier_payment
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_payment.cr_account_id');
						} )
						->join('account_master AS cus', function($join) {
							$join->on('cus.id','=','supplier_payment.supplier_id');
						} )
						->where('supplier_payment.id', $id)
						->select('supplier_payment.*','am.master_name','cus.master_name AS customer')->first();
	}
	
	public function findSPinvoices($id)
	{
		return $this->supplier_payment->where('supplier_payment.id', $id)
								   ->join('supplier_payment_tr AS PI', function($join) {
									   $join->on('PI.supplier_payment_id','=','supplier_payment.id');
								   })
								   ->where('PI.status',1)
								   ->select('PI.*')
								   ->get();
	}
	
	public function PdcIssuedUndo($attributes)
	{
		foreach($attributes['tag'] as $key => $val) { 
			
			if($attributes['voucher_type'][$val]=="PDCI") {
				
				$rvEntry = DB::table('payment_voucher_entry')->where('payment_voucher_id',$attributes['id'][$val])->get();
				//echo '<pre>';print_r($rvEntry);exit;
							
				foreach($rvEntry as $entry) {
					if($entry->entry_type=="Cr"){
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance - '.$entry->amount)]);
					} else {
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance + '.$entry->amount),
											  'pdc_amount' => DB::raw('pdc_amount - '.$entry->amount) ]);
					}
					
					DB::table('payment_voucher_entry')->where('id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
								
					DB::table('account_transaction')->where('voucher_type',"PV")->where('voucher_type_id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
				} 
				
				DB::table('payment_voucher')->where('id',$attributes['id'][$val])
							->update(['status' => 0, 'deleted_at' => now()]);
							
			} else if($attributes['voucher_type'][$val]=="JV") {
				
				$rvEntry = DB::table('journal_entry')->where('journal_id',$attributes['id'][$val])->get();
				//echo '<pre>';print_r($rvEntry);exit;
							
				foreach($rvEntry as $entry) {
					if($entry->entry_type=="Cr"){
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
							
			} else if($attributes['voucher_type'][$val]=="PC") {
				
				$rvEntry = DB::table('petty_cash_entry')->where('petty_cash_id',$attributes['id'][$val])->get();
				//echo '<pre>';print_r($rvEntry);exit;
							
				foreach($rvEntry as $entry) {
					if($entry->entry_type=="Cr"){
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance - '.$entry->amount)]);
					} else {
						DB::table('account_master')->where('id',$entry->account_id)
									->update(['cl_balance' => DB::raw('cl_balance + '.$entry->amount),
											  'pdc_amount' => DB::raw('pdc_amount - '.$entry->amount) ]);
					}
					
					DB::table('petty_cash_entry')->where('id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
								
					DB::table('account_transaction')->where('voucher_type',"PC")->where('voucher_type_id',$entry->id)
								->update(['status' => 0, 'deleted_at' => now() ]);
				} 
				
				DB::table('petty_cash')->where('id',$attributes['id'][$val])
							->update(['status' => 0, 'deleted_at' => now()]);
							
			} 
			
			
		}
		
		return true;
	}
	
	
}

