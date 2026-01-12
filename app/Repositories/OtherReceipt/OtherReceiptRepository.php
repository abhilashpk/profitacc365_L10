<?php
declare(strict_types=1);
namespace App\Repositories\OtherReceipt;

use App\Models\OtherReceipt;
use App\Models\OtherReceiptTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class OtherReceiptRepository extends AbstractValidator implements OtherReceiptInterface {
	
	protected $other_receipt;
	
	protected static $rules = [];
	
	public function __construct(OtherReceipt $other_receipt) {
		$this->other_receipt = $other_receipt;
		
	}
	
	public function all()
	{
		return $this->other_receipt->get();
	}
	
	public function find($id)
	{
		return $this->other_receipt->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;
		$this->other_receipt->voucher_no    = $attributes['voucher_no'];
		$this->other_receipt->voucher_type    = $attributes['voucher_type'];
		$this->other_receipt->voucher_date  = date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->other_receipt->reference  = $attributes['reference'];
		$this->other_receipt->dr_account_id  = $attributes['dr_account_id'];
		$this->other_receipt->description   = $attributes['description'];
		$this->other_receipt->transaction   = $attributes['transaction'];
		$this->other_receipt->amount   = $attributes['amount'];
		$this->other_receipt->job_id 		= $attributes['job_id'];
		$this->other_receipt->department_id 		= $attributes['department_id'];
		$this->other_receipt->is_fc 		= isset($attributes['is_fc'])?1:0;
		$this->other_receipt->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->other_receipt->currency_rate = $currency_rate;
		$this->other_receipt->amount_fc   = $attributes['amount'] * $currency_rate;
		$this->other_receipt->customer_id   = $attributes['customer_id'];
		$this->other_receipt->tr_description = $attributes['tr_description'];
		$this->other_receipt->depositor = $attributes['depositor'];
		$this->other_receipt->cheque_no   = isset($attributes['cheque_no'])?$attributes['cheque_no']:'';
		$this->other_receipt->cheque_date   = isset($attributes['cheque_date'])?date('Y-m-d', strtotime($attributes['cheque_date'])):'';
		$this->other_receipt->bank_id   = isset($attributes['bank_id'])?$attributes['bank_id']:'';
		$this->other_receipt->is_transfer = ($attributes['voucher_type']=='PDCR')?0:1;

		return true;
	}
	
	private function setTrInputValue($attributes, $otherReceiptTr, $key) 
	{
		$otherReceiptTr->other_receipt_id = $this->other_receipt->id;
		$otherReceiptTr->cr_account_id = $attributes['cr_account_id'][$key];
		$otherReceiptTr->cr_reference = $attributes['cr_reference'][$key];
		$otherReceiptTr->cr_description = $attributes['cr_description'][$key];
		$otherReceiptTr->cr_job_id = $attributes['cr_job_id'][$key];
		$otherReceiptTr->cr_amount = $attributes['cr_amount'][$key];
		$otherReceiptTr->cr_amount_fc = $attributes['cr_amount_fc'][$key];
		$otherReceiptTr->status = 1;
		
		return $attributes['cr_amount'][$key];
	}
	
	private function updateClosingBalance($account_id, $amount, $type)
	{
		if($type=='Dr') {
			DB::table('account_master')
						->where('id', $account_id)
						->update(['cl_balance' => DB::raw('cl_balance + '.$amount)
						]);
		} else {
			DB::table('account_master')
						->where('id', $account_id)
						->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
						]);
		}
		
		return true;
	}
	
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $key=null)
	{
		if($type=='Cr') {
			$account_master_id = $attributes['cr_account_id'][$key];
			$amount = $attributes['cr_amount'][$key];
			$referencefrm = $attributes['cr_reference'][$key];
		} else {
			$account_master_id = $attributes['dr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['reference'];
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'OR',//other receipt
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
							
		if($type=='Dr') {
			DB::table('account_master')
						->where('id', $account_master_id)
						->update(['cl_balance' => DB::raw('cl_balance + '.$amount)
						]);
		} else if($type=='Cr') {
			if($attributes['voucher_type']=='PDCR') {
				DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount),
									  'pdc_amount' => DB::raw('pdc_amount + '.$amount)
							]);
			} else {
				DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
							]);
			}
			
		}
		
		return true;
	}
	
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			if($this->setInputValue($attributes)) {
				$this->other_receipt->status = 1;
				$this->other_receipt->created_at = now();
				$this->other_receipt->created_by = 1;
				$this->other_receipt->fill($attributes)->save();
			}
			
			//transactions insert
			if($this->other_receipt->id && !empty( array_filter($attributes['cr_amount']))) {
				$line_total = 0;
				foreach($attributes['cr_account_id'] as $key => $value) { 
					$otherReceiptTr = new OtherReceiptTr();
					$line_total 		+= $this->setTrInputValue($attributes, $otherReceiptTr, $key);
					$this->other_receipt->TransactionAdd()->save($otherReceiptTr);
						
					//set account Cr amount transaction....
					$this->setAccountTransaction($attributes, $attributes['cr_amount'][$key], $this->other_receipt->id, $type='Cr', $key);
				}
				
				$this->setAccountTransaction($attributes, $attributes['amount'], $this->other_receipt->id, $type='Dr');
				
				//update debit, credit, difference amount
				DB::table('other_receipt')
							->where('id', $this->other_receipt->id)
							->update(['debit'     => $attributes['amount'],
									  'credit' 	  => $line_total,
									  'difference'	  => $attributes['amount'] - $line_total ]);
									  
				//cheque no insert...
				if(isset($attributes['cheque_no'])){
					DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'] ]);
				}
				
			}
			
			//update voucher no........
			if($this->other_receipt->id) {
				DB::table('account_setting')
						->where('id', $attributes['voucher'])
						->update(['voucher_no' => DB::raw('voucher_no + 1')]);
			}
			
			return true;
		}
		//throw new ValidationException('other_receipt validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->other_receipt = $this->find($id);
		$this->other_receipt->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->other_receipt = $this->other_receipt->find($id);
		$this->other_receipt->delete();
	}
	
	public function OtherReceiptList()
	{
		$query = $this->other_receipt->where('other_receipt.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','other_receipt.dr_account_id');
						} )
					->join('account_master AS am2', function($join) {
							$join->on('am2.id','=','other_receipt.customer_id');
						} )
					->select('other_receipt.*','am.master_name AS debiter', 'am2.master_name AS creditor')
					->orderBY('other_receipt.id', 'DESC')
					->get();
	}
}

