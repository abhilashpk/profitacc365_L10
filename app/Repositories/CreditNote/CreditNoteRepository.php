<?php
declare(strict_types=1);
namespace App\Repositories\CreditNote;

use App\Models\CreditNote;
use App\Models\CreditNoteEntry;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;

use Config;
use Illuminate\Support\Facades\DB;

class CreditNoteRepository extends AbstractValidator implements CreditNoteInterface {
	
	public $objUtility;
	
	protected $credit_note;
	
	protected static $rules = [];
	
	public function __construct(CreditNote $credit_note) {
		$this->credit_note = $credit_note;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->credit_note->get();
	}
	
	public function find($id)
	{
		return $this->credit_note->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:1;
		$this->credit_note->voucher_id    = $attributes['voucher_id'];
		$this->credit_note->voucher_no    = $attributes['voucher_no'];
		$this->credit_note->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->credit_note->dr_account_id  = $attributes['dr_account_id'];
		$this->credit_note->description   = $attributes['description'];
		$this->credit_note->amount   = $attributes['amount'];
		$this->credit_note->department_id   = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function setTrInputValue($attributes, $creditNoteEntry, $key) 
	{
		$creditNoteEntry->credit_note_id = $this->credit_note->id;
		$creditNoteEntry->cr_account_id = $attributes['cr_account_id'][$key];
		$creditNoteEntry->cr_description = $attributes['cr_description'][$key];
		$creditNoteEntry->cr_reference = $attributes['cr_reference'][$key];
		$creditNoteEntry->type = $attributes['type'][$key];
		$creditNoteEntry->cr_amount = $attributes['cr_amount'][$key];
		$creditNoteEntry->job_id = $attributes['job_id'][$key];
		$creditNoteEntry->invoice_id = $attributes['purchase_invoice_id'][$key];
		$creditNoteEntry->status = 1;
		
		return $attributes['cr_amount'][$key];
	}
	
	private function updateClosingBalance($account_id, $amount, $type)
	{
		if($type=='Dr') {
			
			$this->objUtility->tallyClosingBalance($account_id);
			
		} else if($type=='Cr') {
			
			if($voucher_type=='PDCR') {
				
				$this->objUtility->tallyClosingBalance($account_id);
				DB::table('account_master')
							->where('id', $account_id)
							->update(['pdc_amount' => DB::raw('pdc_amount + '.$amount)
							]);
			} else {
				
				$this->objUtility->tallyClosingBalance($account_id);
				
			}
		}
		
		return true;
	}
	
	private function setTransactionStatus($attributes, $key)
	{
		//if amount partially transfered, update pending amount.
		if(isset($attributes['actual_amount']) && ($attributes['cr_amount'][$key] != $attributes['actual_amount'][$key])) {
			if( isset($attributes['purchase_invoice_id'][$key]) ) {
				//if($attributes['bill_type'][$key]=='SI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
							
				/* } elseif($attributes['bill_type'][$key]=='OB') {
				
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
				} */
			}
			
		} else {
			
				//update as completely paid.
				//if($attributes['bill_type'][$key]=='SI')  {
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
								
				/* } else if($attributes['bill_type'][$key]=='OB') {
					
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				} */
		}
	}
	
	private function setTransactionStatus222($attributes, $key)
	{
		//if amount partially transfered, update pending amount.
		if(isset($attributes['actual_amount']) && ($attributes['cr_amount'][$key] != $attributes['actual_amount'][$key])) {
			if( isset($attributes['purchase_invoice_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
							
				} elseif($attributes['bill_type'][$key]=='OB') {
				
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
				}
			}
			
		} else {
			
				//update as completely paid.
				if($attributes['bill_type'][$key]=='SI')  {
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
								
				} else if($attributes['bill_type'][$key]=='OB') {
					
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				}
		}
	}
	
	private function setTransactionStatusUpdate($attributes, $key)
	{
		//if amount partially transfered, update pending amount.
		if(isset($attributes['actual_amount']) && ($attributes['cr_amount'][$key] != $attributes['actual_amount'][$key])) {
			if( isset($attributes['purchase_invoice_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2]);;
							
				} elseif($attributes['bill_type'][$key]=='OB') {
				
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['cr_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
				}
			}
			
		} /* else {
			
				//update as completely paid.
				if($attributes['bill_type'][$key]=='SI')  {
					DB::table('sales_invoice')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
								
				} else if($attributes['bill_type'][$key]=='OB') {
					
					DB::table('opening_balance_tr')
								->where('id', $attributes['purchase_invoice_id'][$key])
								->update(['balance_amount' => 0, 'amount_transfer' => 1]);
				}
		} */
	}
	
	private function setAccountTransactionCN($attributes, $voucher_id, $type, $key=null)
	{
		if($type=='Cr') {
			$account_master_id = $attributes['cr_account_id'][$key];
			$amount = $attributes['cr_amount'][$key];
			$referencefrm = $attributes['cr_reference'][$key];
			$description = $attributes['cr_description'][$key];
		} else {
			$account_master_id = $attributes['dr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['voucher_no'];
			$description = $attributes['description'];
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'CN',
						    'voucher_type_id'   => $voucher_id,
							'account_master_id' => $account_master_id,
							'transaction_type'  => $type,
							'amount'   			=> $amount,
							'status' 			=> 1,
							'created_at' 		=> now(),
							'created_by' 		=> 1,
							'description' 		=> $description,
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $referencefrm,
							'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
						]);
		
		/* if($type=='Cr') {
			DB::table('account_master')
							->where('id', $account_master_id)
							->update(['cl_balance' => DB::raw('cl_balance - '.$attributes['cr_amount'][$key])]);
		} else 
				DB::table('account_master')
								->where('id', $account_master_id)
								->update(['cl_balance' => DB::raw('cl_balance + '.$attributes['cr_amount'][$key])]); */
		return true;
	}
	
	private function setAccountTransactionCNUpdate($attributes, $voucher_id, $type, $key)
	{
		
		if($type=='Cr') {
			$account_master_id = $attributes['cr_account_id'][$key];
			$amount = $attributes['cr_amount'][$key];
			$referencefrm = $attributes['cr_reference'][$key];
			$description = $attributes['cr_description'][$key];
		} else {
			$account_master_id = $attributes['dr_account_id'];
			$amount = $attributes['amount'];
			$referencefrm = $attributes['voucher_no'];
			$description = $attributes['description'];
		}
		
		DB::table('account_transaction')
					->where('voucher_type', 'CN')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $account_master_id)
					->where('reference_from', $referencefrm)
					->update([  
							'amount'   			=> $amount,
							'modify_at' 		=> now(),
							'modify_by' 		=> 1,
							'description' 		=> $description,
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
						]);
		
		return true;
	}
	
	private function setAccountTransactionCNDelete($attributes, $voucher_id, $type, $key)
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
	{ //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
		DB::beginTransaction();
		try {
			if($this->setInputValue($attributes)) {
				$this->credit_note->status = 1;
				$this->credit_note->created_at = now();
				$this->credit_note->created_by = 1;
				$this->credit_note->fill($attributes)->save();
			}
			
			//transactions insert
			if($this->credit_note->id && !empty( array_filter($attributes['cr_amount']))) {
				$line_total = 0;
				foreach($attributes['cr_amount'] as $key => $value) { 
					$creditNoteEntry = new CreditNoteEntry();
					$line_total 		+= $this->setTrInputValue($attributes, $creditNoteEntry, $key);
					$this->credit_note->TransactionAdd()->save($creditNoteEntry);
						
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account Cr transactions...
					$this->setAccountTransactionCN($attributes, $this->credit_note->id, 'Cr', $key);
						
				}
				
				//update account Dr transactions...
				$this->setAccountTransactionCN($attributes, $this->credit_note->id, 'Dr');
				
				//update closing balance of debitor account
				if($this->objUtility->tallyClosingBalance($attributes['dr_account_id'])) {
					//update closing balance of debitor account
					$this->objUtility->tallyClosingBalance($attributes['cr_account_id'][$key]);
				}
				
			}
			
			//update voucher no........
			if($this->credit_note->id) {
					DB::table('account_setting')
						->where('id', $attributes['voucher_id'])
						->update(['voucher_no' => DB::raw('voucher_no + 1')]);
			}
			
			DB::commit();
			return true; 
				
			 } catch(\Exception $e) {  
			
				DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
				return false;
			}
		}
		
	}
	
	public function update($id, $attributes)
	{  //echo '<pre>';print_r($attributes);exit;
		$this->credit_note = $this->find($id);
		
		if($this->credit_note->id && !empty( array_filter($attributes['cr_amount']))) {
			$line_total = 0;
			foreach($attributes['cr_amount'] as $key => $value) { 
				
				if($attributes['tr_id'][$key]!='') {
					
					$creditNoteEntry = CreditNoteEntry::find($attributes['tr_id'][$key]);
					
					$invrow['cr_account_id'] = $attributes['cr_account_id'][$key];
					$invrow['cr_description'] = $attributes['cr_description'][$key];
					$invrow['cr_reference'] = $attributes['cr_reference'][$key];
					$invrow['cr_amount'] = $attributes['cr_amount'][$key];
					$invrow['job_id'] = $attributes['job_id'][$key];
					$creditNoteEntry->update($invrow);
					
					//update sales invoice transaction status...
					$this->setTransactionStatusUpdate($attributes, $key);
					
					//update account transactions...
					$this->setAccountTransactionCNUpdate($attributes, $this->credit_note->id, 'Cr', $key);
					
					
				} else {
					
					//new entry.....
					$creditNoteEntry = new CreditNoteEntry();
					$line_total 		+= $this->setTrInputValue($attributes, $creditNoteEntry, $key);
					$this->credit_note->TransactionAdd()->save($creditNoteEntry);
						
					//update sales invoice transaction status...
					$this->setTransactionStatus($attributes, $key);
					
					//update account Cr transactions...
					$this->setAccountTransactionCN($attributes, $this->credit_note->id, 'Cr', $key);
					
					
				}
				
			}
			
			$this->setAccountTransactionCNUpdate($attributes, $this->credit_note->id, 'Dr', $key);
			
			//update closing balance of debitor account
			if($this->objUtility->tallyClosingBalance($attributes['dr_account_id'])) {
				//update closing balance of debitor account
				$this->objUtility->tallyClosingBalance($attributes['cr_account_id'][$key]);
			}
				
			//manage removed items...
			/* if($attributes['remove_item']!='')
			{
				$arrids = array_unique(explode(',', $attributes['remove_item']));
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('credit_note_entry')->where('id', $attributes['id'][$row])->update(['status' => 0, 'deleted_at' => now()]);
					
					if($this->setAccountTransactionCNDelete($attributes, $this->credit_note->id, 'Cr', $row))
						$this->setAccountTransactionCNDelete($attributes, $this->credit_note->id, 'Dr', $row);
		
				}
			} */
			
			
		}
			
		$this->credit_note->amount = $attributes['amount'];
		$this->credit_note->modify_at = now();
		$this->credit_note->modify_by = 1;
		$this->credit_note->fill($attributes)->save();
		return true;
	}
	
		
	public function delete($id)
	{
		$this->credit_note = $this->credit_note->find($id);
		DB::beginTransaction();
		try {
			//Transaction update....
			DB::table('account_transaction')->where('voucher_type', 'CN')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now() ]);
			
			$this->objUtility->tallyClosingBalance($this->credit_note->dr_account_id);
			
			//credit note details.....
			$rows = DB::table('credit_note_entry')->where('credit_note_id', $id)->where('status',1)->whereNull('deleted_at')->get();
			if($rows) {
				foreach($rows as $row)
				{
					DB::table('credit_note_entry')->where('id', $row->id)->update(['status' => 0,'deleted_at' => now() ]);
					
					DB::table('sales_invoice')->where('voucher_no', $row->cr_reference)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$row->cr_amount) ]);
					
					$this->objUtility->tallyClosingBalance($row->cr_account_id);
				}
			}
			
			$this->credit_note->delete();
			DB::commit();
			return true;
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
		
	}
	
	public function CreditNoteList()
	{
		$query = $this->credit_note->where('credit_note.status',1);
		return $query->join('credit_note_entry AS ce', function($join) {
							$join->on('ce.credit_note_id','=','credit_note.id');
						} )
					->join('account_master AS am', function($join) {
							$join->on('am.id','=','ce.cr_account_id');
						} )
					->select('credit_note.*','am.master_name AS debiter')
					->orderBY('credit_note.id', 'DESC')
					->groupBy('credit_note.id')
					->get();
	}
	
	public function CreditNoteListOld()
	{
		$query = $this->credit_note->where('credit_note.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','credit_note.dr_account_id');
						} )
					->select('credit_note.*','am.master_name AS debiter')
					->orderBY('credit_note.id', 'DESC')
					->get();
	}
	
	public function PDCReceivedList()
	{
		$query = $this->credit_note->where('credit_note.status',1)->where('voucher_type', 'PDCR')->where('is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','credit_note.dr_account_id');
						} )
					->join('account_master AS c', function($join) {
							$join->on('c.id','=','credit_note.customer_id');
						} )
					->join('bank AS b', function($join) {
							$join->on('b.id','=','credit_note.bank_id');
						} )
					->select('credit_note.*','am.master_name AS creditor','b.name AS bankname','b.code','c.master_name AS customer')
					->orderBY('credit_note.cheque_date', 'ASC')
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
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
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
									'voucher_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
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
	
	public function findCN($id)
	{
		return $this->credit_note
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','credit_note.dr_account_id');
						} )
						->join('credit_note_entry AS ce', function($join) {
							$join->on('ce.credit_note_id','=','credit_note.id');
						} )
						->where('credit_note.status',1)
						->where('credit_note.id', $id)
						->select('credit_note.*','am.master_name','ce.invoice_id')->first();
	}
	
	public function findCNdata($id)
	{
		return $this->credit_note
						->join('credit_note_entry AS CNE', function($join) {
							$join->on('CNE.credit_note_id','=','credit_note.id');
						} )
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','CNE.cr_account_id');
						} )
						->where('CNE.status',1)
						->where('CNE.deleted_at','0000-00-00 00:00:00')
						->where('credit_note.id', $id)
						->select('CNE.*','am.master_name','am.category')->get();
	}
	
	public function findCRinvoices($id)
	{
		return $this->credit_note->where('credit_note.id', $id)
								   ->join('credit_note_tr AS PI', function($join) {
									   $join->on('PI.credit_note_id','=','credit_note.id');
								   })
								   ->where('PI.status',1)
								   ->select('PI.*')
								   ->get();
	}
	
	
}

