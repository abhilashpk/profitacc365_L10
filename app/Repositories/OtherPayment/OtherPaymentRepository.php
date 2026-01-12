<?php
declare(strict_types=1);
namespace App\Repositories\OtherPayment;

use App\Models\OtherPayment;
use App\Models\OtherPaymentTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class OtherPaymentRepository extends AbstractValidator implements OtherPaymentInterface {
	
	protected $other_payment;
	
	protected static $rules = [];
	
	public function __construct(OtherPayment $other_payment) {
		$this->other_payment = $other_payment;
		
	}
	
	public function all()
	{
		return $this->other_payment->get();
	}
	
	public function find($id)
	{
		return $this->other_payment->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;
		$this->other_payment->voucher_no    = $attributes['voucher_no'];
		$this->other_payment->voucher_type    = $attributes['voucher_type'];
		$this->other_payment->voucher_date  = date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->other_payment->reference  = $attributes['reference'];
		$this->other_payment->cr_account_id  = $attributes['cr_account_id'];
		$this->other_payment->description   = $attributes['description'];
		$this->other_payment->transaction   = $attributes['transaction'];
		$this->other_payment->amount   = $attributes['amount'];
		$this->other_payment->job_id 		= $attributes['job_id'];
		$this->other_payment->department_id 		= $attributes['department_id'];
		$this->other_payment->is_fc 		= isset($attributes['is_fc'])?1:0;
		$this->other_payment->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->other_payment->currency_rate = $currency_rate;
		$this->other_payment->amount_fc   = $attributes['amount'] * $currency_rate;
		$this->other_payment->customer_id   = $attributes['supplier_id'];
		$this->other_payment->tr_description = $attributes['tr_description'];
		$this->other_payment->depositor = $attributes['depositor'];
		$this->other_payment->cheque_no   = isset($attributes['cheque_no'])?$attributes['cheque_no']:'';
		$this->other_payment->cheque_date   = isset($attributes['cheque_date'])?date('Y-m-d', strtotime($attributes['cheque_date'])):'';
		$this->other_payment->bank_id   = isset($attributes['bank_id'])?$attributes['bank_id']:'';
		$this->other_payment->is_transfer = ($attributes['voucher_type']=='PDCR')?0:1;
		

		return true;
	}
	
	private function setTrInputValue($attributes, $otherPaymentTr, $key) 
	{
		$otherPaymentTr->other_payment_id = $this->other_payment->id;
		$otherPaymentTr->dr_account_id = $attributes['dr_account_id'][$key];
		$otherPaymentTr->dr_reference = $attributes['dr_reference'][$key];
		$otherPaymentTr->dr_description = $attributes['dr_description'][$key];
		$otherPaymentTr->dr_job_id = $attributes['dr_job_id'][$key];
		$otherPaymentTr->dr_amount = $attributes['dr_amount'][$key];
		$otherPaymentTr->dr_amount_fc = $attributes['dr_amount_fc'][$key];
		$otherPaymentTr->status = 1;
		
		return $attributes['dr_amount'][$key];
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
		if($type=='Dr') {
			$account_master_id = $attributes['dr_account_id'][$key];
			$amount = $attributes['dr_amount'][$key];
			$referencefrm = $attributes['dr_reference'][$key];
		} else {
			$account_master_id = $attributes['cr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['reference'];
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'OP',//other payment
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
							
		if($type=='Cr') {
			DB::table('account_master')
						->where('id', $account_master_id)
						->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
						]);
		} else if($type=='Dr') {
			if($attributes['voucher_type']=='PDCI') {
				DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance + '.$amount),
									  'pdc_amount' => DB::raw('pdc_amount - '.$amount)
							]);
			} else {
				DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance + '.$amount)
							]);
			}
			
		}
		
		if($type=='Dr') {
			DB::table('account_master')
						->where('id', $attributes['dr_account_id'][$key])
						->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
						]);
		} else {
			DB::table('account_master')
						->where('id', $attributes['cr_account_id'])
						->update(['cl_balance' => DB::raw('cl_balance - '.$amount)
						]);
		}
		
		return true;
	}
	
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			if($this->setInputValue($attributes)) {
				$this->other_payment->status = 1;
				$this->other_payment->created_at = now();
				$this->other_payment->created_by = 1;
				$this->other_payment->fill($attributes)->save();
			}
			
			//transactions insert
			if($this->other_payment->id && !empty( array_filter($attributes['dr_amount']))) {
				$line_total = 0;
				foreach($attributes['dr_account_id'] as $key => $value) { 
					$otherPaymentTr = new OtherPaymentTr();
					$line_total 		+= $this->setTrInputValue($attributes, $otherPaymentTr, $key);
					$this->other_payment->TransactionAdd()->save($otherPaymentTr);
						
					//set account Dr amount transaction....
					$this->setAccountTransaction($attributes, $attributes['dr_amount'][$key], $this->other_payment->id, $type='Dr', $key);
					
				}
				
				$this->setAccountTransaction($attributes, $attributes['amount'], $this->other_payment->id, $type='Cr');
				
				//update debit, credit, difference amount
				DB::table('other_payment')
							->where('id', $this->other_payment->id)
							->update(['debit'     => $line_total,
									  'credit' 	  => $attributes['amount'],
									  'difference'	  => $line_total - $attributes['amount'] ]);
									  
				//cheque no insert...
				if(isset($attributes['cheque_no'])){
					DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'] ]);
				}
				
			}
			
			//update voucher no........
			if($this->other_payment->id) {
				DB::table('account_setting')
						->where('id', $attributes['voucher'])
						->update(['voucher_no' => DB::raw('voucher_no + 1')]);
			}
			
			return true;
		}
		//throw new ValidationException('other_payment validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->other_payment = $this->find($id);
		$this->other_payment->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->other_payment = $this->other_payment->find($id);
		$this->other_payment->delete();
	}
	
	public function OtherPaymentList()
	{
		$query = $this->other_payment->where('other_payment.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','other_payment.cr_account_id');
						} )
					->join('account_master AS am2', function($join) {
							$join->on('am2.id','=','other_payment.customer_id');
						} )
					->select('other_payment.*','am.master_name AS creditor', 'am2.master_name AS debitor')
					->orderBY('other_payment.id', 'DESC')
					->get();
	}
}

