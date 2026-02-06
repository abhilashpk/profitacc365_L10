<?php namespace App\Repositories\Journal;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\OtherVoucherTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use App\Models\JournalVoucherTr;

use Config;
use DB;
use Auth;
use Session;

class JournalRepository extends AbstractValidator implements JournalInterface {
	
	public $objUtility;
	
	protected $journal;
	
	protected static $rules = [];
	
	public function __construct(Journal $journal) {
		$this->journal = $journal;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->journal->get();
	}
	
	public function find($id)
	{
		return $this->journal->where('id', $id)->first();
	}
	
	private function getVoucherType($id)
	{
		switch($id) {
			
			case 5:
			return 'PIN';
			break;
			
			case 6:
			return 'SIN';
			break;
			
			case 9:
			return 'RV';
			break;
			
			case 10:
			return 'PV';
			break;
			
			case 16:
			return 'JV';
			break;
		}	
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		//echo $this->getVoucherType( $attributes['voucher_type'] );
		$this->journal->voucher_type  = $this->getVoucherType( $attributes['voucher_type'] );
		$this->journal->voucher_no = $attributes['voucher_no'];
		$this->journal->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])); //date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
		$this->journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
		$this->journal->group_id = $attributes['group_id'][0];
		$this->journal->department_id = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function setInputValueRC($attributes)
	{
		//echo $this->getVoucherType( $attributes['voucher_type'] );
		$this->journal->voucher_type  = $this->getVoucherType( $attributes['voucher_type'] );
		$this->journal->voucher_date  = ($attributes['voucher_date_rc']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date_rc'])); //date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
		$this->journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
		$this->journal->group_id = $attributes['group_id'][0];
		$this->journal->department_id = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function setTrInputValue($attributes, $journalEntryTr, $key) 
	{
		$cr_amount = 0; $dr_amount = 0;
		if($attributes['account_type'][$key]=='Dr')
			$dr_amount = $attributes['line_amount'][$key];
		else if($attributes['account_type'][$key]=='Cr')
			$cr_amount = $attributes['line_amount'][$key];
		
		$journalEntryTr->journal_id = $this->journal->id;
		$journalEntryTr->account_id = $attributes['account_id'][$key];
		$journalEntryTr->description    		= $attributes['description'][$key];
		$journalEntryTr->reference    		= $attributes['reference'][$key];
		$journalEntryTr->entry_type    		= $attributes['account_type'][$key];
		$journalEntryTr->amount = (float) $attributes['line_amount'][$key]; //$journalEntryTr->amount    		= $attributes['line_amount'][$key];
		$journalEntryTr->job_id    		= isset($attributes['job_id'][$key])?$attributes['job_id'][$key]:'';
		$journalEntryTr->department_id    		= isset($attributes['department'][$key])?$attributes['department'][$key]:'';
		$journalEntryTr->cheque_no    		= isset($attributes['cheque_no'][$key])?$attributes['cheque_no'][$key]:'';
		$journalEntryTr->cheque_date    		=  isset($attributes['cheque_date'][$key])?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):'';
		$journalEntryTr->bank_id    		= isset($attributes['bank_id'][$key])?$attributes['bank_id'][$key]:'';
		$journalEntryTr->party_account_id   = isset($attributes['partyac_id'][$key])?$attributes['partyac_id'][$key]:'';
		
		//check advance or not....
		if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr') {
			
			if(strpos(strtoupper($attributes['description'][$key]), 'ADVANCE') !== false) {
				$journalEntryTr->is_onaccount = 1;
				$journalEntryTr->reference = ($attributes['reference'][$key]=='')?'Adv.':$attributes['reference'][$key];
			} 
		} else if($attributes['group_id'][$key]=='SUPPLIER' && $attributes['account_type'][$key]=='Dr') {
			
			if(strpos(strtoupper($attributes['description'][$key]), 'ADVANCE') !== false) {
				$journalEntryTr->is_onaccount = 1;
				$journalEntryTr->reference = ($attributes['reference'][$key]=='')?'Adv.':$attributes['reference'][$key];
			} 
		}
		
		
			
		return array('dr_amount' => $dr_amount, 'cr_amount' => $cr_amount);
	}
	
	private function updateClosingBalance($account_id, $amount, $type)
	{
		$this->objUtility->tallyClosingBalance($account_id);
		return true;
	}
	
	private function setAccountTransaction($attributes, $journal_id, $key)
	{
		
	    if($this->getVoucherType($attributes['voucher_type'])=='PIN'||$this->getVoucherType($attributes['voucher_type'])=='SIN') {
			$department = isset($attributes['department_id'])?$attributes['department_id']:'';
		} else {
			$department = isset($attributes['department'][$key])?$attributes['department'][$key]:'';
		}
		
	        DB::table('account_transaction')
				->insert([  'voucher_type' 		=> $this->getVoucherType($attributes['voucher_type']),//'JV',//journal entry
						    'voucher_type_id'   => $journal_id,
							'account_master_id' => $attributes['account_id'][$key],
							'transaction_type'  => $attributes['account_type'][$key],
							'amount'   			=> $attributes['line_amount'][$key],
							'status' 			=> 1,
							'created_at' 		=> date('Y-m-d H:i:s'),
							'created_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key],
							'department_id'     => $department,
							'version_no'		=> $attributes['version_no']
							]);
		
		return true; 
	}
	
	private function setAccountTransactionUpdate($attributes, $journal_id, $key)
	{
		
		if($this->getVoucherType($attributes['voucher_type'])=='PIN'||$this->getVoucherType($attributes['voucher_type'])=='SIN') {
			$department = isset($attributes['department_id'])?$attributes['department_id']:'';
		} else {
			$department = isset($attributes['department'][$key])?$attributes['department'][$key]:'';
		}
		
		DB::table('account_transaction')
				->where('voucher_type', $this->getVoucherType($attributes['voucher_type']))
				->where('voucher_type_id', $journal_id)
				->update([ 'account_master_id' => $attributes['account_id'][$key],
							'transaction_type'  => $attributes['account_type'][$key],
							'amount'   			=> $attributes['line_amount'][$key],
							'modify_at' 		=> date('Y-m-d H:i:s'),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key],
							'department_id'    => $department
							]);
		
		return true;
	}
	
	private function setAccountTransactionDelete($attributes, $journal_id)
	{
		
		DB::table('account_transaction')
				->where('voucher_type', $this->getVoucherType($attributes['voucher_type']))
				->where('voucher_type_id', $journal_id)
				->update([ 'status' 		=> 0,
						   'deleted_at' 	=> date('Y-m-d H:i:s'),
						   'deleted_by'		=> Auth::User()->id ]);
		
		return true;
	}
	
	private function setTransactionStatus($attributes, $key, $jv_entry_id=null) //May 15 
	{
		if($attributes['group_id'][$key]=='CUSTOMER') { //customer type............
			//if amount partially transfered, update pending amount.
			if( isset($attributes['inv_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$lnamount = ($attributes['line_amount'][$key]!='')?(float)$attributes['line_amount'][$key]:0;
					$actamount = ($attributes['actual_amount'][$key]!='')?(float)$attributes['actual_amount'][$key]:0;
					$balance_amount = $actamount - $lnamount;
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2,'is_editable' => 1]);
					
					//check if bll is cleared or not...
					$bal = DB::table('sales_invoice')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('sales_invoice')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
				} else if($attributes['bill_type'][$key]=='OB') {
					
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
					
				} else if($attributes['bill_type'][$key]=='SIN') {
							
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('journal')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'is_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('journal')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('journal')->where('id', $attributes['inv_id'][$key])->update(['is_transfer' => 1]);
					}
				
				} else if($attributes['bill_type'][$key]=='OT') { //May 15......
							
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('other_voucher_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
				} //......May 15
				
				//May 15.....
				if($attributes['account_type'][$key]=='Dr' && $attributes['inv_id'][$key]=='') {
					
					$otherVchrTr = new OtherVoucherTr;
					$otherVchrTr->voucher_type = 'JV';
					$otherVchrTr->voucher_id = $jv_entry_id;
					$otherVchrTr->tr_type = 'Dr';
					$otherVchrTr->tr_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
					$otherVchrTr->reference_no = $attributes['reference'][$key];
					$otherVchrTr->amount = $attributes['line_amount'][$key];
					$otherVchrTr->account_master_id = $attributes['account_id'][$key];
					$otherVchrTr->status = 1; 
					$otherVchrTr->save();
							
				}
				//......May 15
			}
			
		} else if($attributes['group_id'][$key]=='SUPPLIER') { //supplier type............
			//if amount partially transfered, update pending amount.
			
			if( isset($attributes['inv_id'][$key]) ) {
				
				if($attributes['bill_type'][$key]=='PI') {
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('purchase_invoice')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2, 'is_editable' => 1]);
								
					//check if bll is cleared or not...
					$bal = DB::table('purchase_invoice')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('purchase_invoice')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
					
				} else if($attributes['bill_type'][$key]=='OB') {
					
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
								
				} else if($attributes['bill_type'][$key]=='PIN') {
					
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('journal')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'is_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('journal')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('journal')->where('id', $attributes['inv_id'][$key])->update(['is_transfer' => 1]);
					}
				
				} else if($attributes['bill_type'][$key]=='OT') { //May 15....
					
					$balance_amount = (float)$attributes['actual_amount'][$key] - (float)$attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('other_voucher_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => $balance_amount, 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
					
				} //....May 15
				
				
				//May 15.....
				if($attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]=='') {
					
					$otherVchrTr = new OtherVoucherTr;
					$otherVchrTr->voucher_type = 'JV';
					$otherVchrTr->voucher_id = $jv_entry_id;
					$otherVchrTr->tr_type = 'Cr';
					$otherVchrTr->tr_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
					$otherVchrTr->reference_no = $attributes['reference'][$key];
					$otherVchrTr->amount = $attributes['line_amount'][$key];
					$otherVchrTr->account_master_id = $attributes['account_id'][$key];
					$otherVchrTr->status = 1; 
					$otherVchrTr->save();
							
				}
				//......May 15
			}
			
		}
	}
	
	private function setTransactionStatusUpdate($attributes, $key, $jv_entry_id=null) //May 15
	{
		if($attributes['group_id'][$key]=='CUSTOMER') { //customer type............
			//if amount partially transfered, update pending amount.
			if( isset($attributes['inv_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('sales_invoice')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2,'is_editable' => 1]);
					
					//check if bll is cleared or not...
					$bal = DB::table('sales_invoice')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('sales_invoice')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
				} else if($attributes['bill_type'][$key]=='OB') {
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
					
				} else if($attributes['bill_type'][$key]=='SIN') {
							
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('journal')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'is_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('journal')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('journal')->where('id', $attributes['inv_id'][$key])->update(['is_transfer' => 1]);
					}
				
				} else if($attributes['bill_type'][$key]=='OT') { //May 15.....
							
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
				} //......May 15
				
				
				//May 15.....
				if($attributes['account_type'][$key]=='Dr' && $attributes['inv_id'][$key]=='') {
					
					DB::table('other_voucher_tr')
								->where('voucher_type', 'JV')
								->where('voucher_id', $jv_entry_id)
								->update([ 'tr_type' => $attributes['account_type'][$key],
										   'tr_date' => date('Y-m-d', strtotime($attributes['voucher_date'])),
										   'reference_no' => $attributes['reference'][$key],
										   'amount' => $attributes['line_amount'][$key],
										   'account_master_id' => $attributes['account_id'][$key]
								]);
							
				}
				//......May 15
			}
			
		} else if($attributes['group_id'][$key]=='SUPPLIER') { //supplier type............
			//if amount partially transfered, update pending amount.
			
			if( isset($attributes['inv_id'][$key]) ) {
				
				if($attributes['bill_type'][$key]=='PI') {
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('purchase_invoice')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2, 'is_editable' => 1]);
								
					//check if bll is cleared or not...
					$bal = DB::table('purchase_invoice')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('purchase_invoice')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
					
				} else if($attributes['bill_type'][$key]=='OB') {
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('opening_balance_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('opening_balance_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
								
				} else if($attributes['bill_type'][$key]=='PIN') {
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('journal')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'is_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('journal')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('journal')->where('id', $attributes['inv_id'][$key])->update(['is_transfer' => 1]);
					}
				
				} else if($attributes['bill_type'][$key]=='OT') { //May 15.....
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
					//update as partially paid.
					DB::table('other_voucher_tr')
								->where('id', $attributes['inv_id'][$key])
								->update(['balance_amount' => DB::raw('balance_amount + '.$balance_amount), 'amount_transfer' => 2]);
								
					//check if bll is cleared or not...
					$bal = DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->select('balance_amount')->first();
					if($bal && $bal->balance_amount == 0) {
						DB::table('other_voucher_tr')->where('id', $attributes['inv_id'][$key])->update(['amount_transfer' => 1]);
					}
				} //.....May 15
				
				
				//May 15.....
				if($attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]=='') {
					
					DB::table('other_voucher_tr')
								->where('voucher_type', 'JV')
								->where('voucher_id', $jv_entry_id)
								->update([ 'tr_type' => $attributes['account_type'][$key],
										   'tr_date' => date('Y-m-d', strtotime($attributes['voucher_date'])),
										   'reference_no' => $attributes['reference'][$key],
										   'amount' => $attributes['line_amount'][$key],
										   'account_master_id' => $attributes['account_id'][$key]
								]);
							
				}
				//......May 15
			}
			
		}
	}
	

	public function create($attributes)
	{ 
		//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) { 
		
			DB::beginTransaction();
			try {
				$attributes['version_no'] = 1;
				//VOUCHER NO LOGIC.....................
				// 2️⃣ Get the highest numeric part from voucher_master
				$maxNumeric = DB::table('journal')
					->where('deleted_at', '0000-00-00 00:0:00')
					//->where('department_id', $departmentId)
					->where('status', 1)->where('voucher_type', 'JV')
					->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
					->value('max_no');
				
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
				$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();//echo '<pre>';print_r($accset);
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
					try {
						if ($this->setInputValue($attributes)) {

							$this->journal->status = 1;
							$this->journal->created_at = date('Y-m-d H:i:s');
							$this->journal->created_by = Auth::User()->id;
							$this->journal->fill($attributes)->save();
							$saved = true; // success ✅

						}	
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
							strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$maxNumeric = DB::table('journal')
								->where('deleted_at', '0000-00-00 00:0:00')
								//->where('department_id', $departmentId)
								->where('status', 1)->where('voucher_type', 'JV')
								->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
								->value('max_no');
							
							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
							$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; //echo $ex;exit;// rethrow if different DB error
						}
					}
				}
													
				//transactions insert
				if($this->journal->id && !empty( array_filter($attributes['line_amount']))) {
					$cr_amount = 0; $dr_amount = 0;
					foreach($attributes['line_amount'] as $key => $value) {
						if($value!='' && $value!=0 && $attributes['account_id'][$key]!='' && $attributes['account_id'][$key]!=0){
							$journalEntryTr = new JournalEntry();
							$arrResult = $this->setTrInputValue($attributes, $journalEntryTr, $key);
							
							if($arrResult) {
								$cr_amount += $arrResult['cr_amount'];
								$dr_amount += $arrResult['dr_amount'];
								$journalEntryTr->status = 1;
								$jv_entry_id = $this->journal->JournalAdd()->save($journalEntryTr);
							}
							//echo $jv_entry_id->id.'<br/>';
							//PDCR list inserting....
							if($attributes['group_id'][$key]=='PDCR') {
								
								$acrow = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id')->first();
								$bnk = DB::table('account_setting')->where('voucher_type_id', 18)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('dr_account_master_id')->first();
								
								if(isset($attributes['partyac_id'][$key]) && $attributes['partyac_id'][$key]=='') {
									$party_id = '';
									$ctrow = DB::table('journal_entry')->where('journal_id',$this->jv_entry_id->id)
													->where('entry_type','Cr')->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')
													->select('account_id')->first();
									if($ctrow) {
										$party_id = $ctrow->account_id;
									}
								} else
									$party_id = $attributes['partyac_id'][$key];
									
								DB::table('pdc_received')
												->insert([ 'voucher_id' 	=>  $this->journal->id,
															'voucher_type'   => 'DB',
															'dr_account_id' => ($acrow)?$acrow->id:0,
															'cr_account_id' => $attributes['account_id'][$key],
															'reference'  => $attributes['reference'][$key],
															'amount'   			=> $attributes['line_amount'][$key],
															'status' 			=> 0,
															'created_at' 		=> date('Y-m-d H:i:s'),
															'created_by' 		=> Auth::User()->id,
															'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
															'customer_id' => $party_id,
															'cheque_no' => $attributes['cheque_no'][$key],
															'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
															'voucher_no' => $attributes['voucher_no'],
															'description' => $attributes['description'][$key],
															'bank_id'	=> (isset($attributes['bank_id'][$key]) && $attributes['bank_id'][$key]!='')?$attributes['bank_id'][$key]:1,
															'entry_id' => $jv_entry_id->id,
															'entry_type' => 'JV',
														    'dr_bank_id' => ($bnk)?$bnk->dr_account_master_id:0
														]);
							}
							
							//PDCI list inserting....
							if($attributes['group_id'][$key]=='PDCI') {
								
								$acrow = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id')->first();
								$bnk = DB::table('account_setting')->where('voucher_type_id', 19)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cr_account_master_id')->first();
								
								if(isset($attributes['partyac_id'][$key]) && $attributes['partyac_id'][$key]=='') {
									$party_id = '';
									$ctrow = DB::table('payment_voucher_entry')->where('payment_voucher_id',$this->payment_voucher->id)
													->where('entry_type','Dr')->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')
													->select('account_id')->first();
									if($ctrow) {
										$party_id = $ctrow->account_id;
									}
								} else
									$party_id = $attributes['partyac_id'][$key];

								DB::table('pdc_issued')
												->insert([ 'voucher_id' 	=>  $this->journal->id,
															'voucher_type'   => 'CB',
															'dr_account_id' => $attributes['account_id'][$key],
															'cr_account_id' => ($acrow)?$acrow->id:0,
															'reference'  => $attributes['reference'][$key],
															'amount'   			=> $attributes['line_amount'][$key],
															'status' 			=> 0,
															'created_at' 		=> date('Y-m-d H:i:s'),
															'created_by' 		=> Auth::User()->id,
															'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
															'supplier_id' => $party_id,
															'cheque_no' => $attributes['cheque_no'][$key],
															'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
															'voucher_no' => $attributes['voucher_no'],
															'description' => $attributes['description'][$key],
															'bank_id'	=> (isset($attributes['bank_id'][$key]) && $attributes['bank_id'][$key]!='')?$attributes['bank_id'][$key]:1,
															'entry_id' => $jv_entry_id->id,
															'entry_type' => 'JV',
														    'dr_bank_id' => ($bnk)?$bnk->cr_account_master_id:0
														]);
							}
							
							//JOURNAL ENTRY TRANSACTION INSERT................
							if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = $attributes['sales_invoice_id'][$key];
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							if($attributes['group_id'][$key]=='SUPPLIER' && $attributes['account_type'][$key]=='Dr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = isset($attributes['purchase_invoice_id'][$key])?$attributes['purchase_invoice_id'][$key]:'';
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							//update invoice transaction status...
							$this->setTransactionStatus($attributes, $key, $journalEntryTr->id); //May 15....
							
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!='' && isset($attributes['partyac_id'][$key]) && $attributes['partyac_id'][$key]!=''){
								if($attributes['group_id'][$key]=='PDCR')
									DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> $attributes['partyac_id'][$key] ]);
								elseif($attributes['group_id'][$key]=='PDCI')
									DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> '','ctype' => 1]);
							}
						}
					} 
					
					$difference = round($dr_amount - $cr_amount, 2);
					if(bccomp($difference, '0.00', 2) != 0) { 	
						Session::flash('error', 'Debit and Credit totals must be equal before saving this voucher.');
						return false;
						//throw new ValidationException('Receipt entry validation error! Please try again.',$this->getErrors());
					}

				}
					
				DB::commit(); 
				return $this->journal->id;
				
			} catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			  return false;
		    }
		}
		//throw new ValidationException('journal validation error12!', $this->getErrors());
	}
	
	public function createSIN($attributes)
	{ 
		//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) { 
		
			DB::beginTransaction();
			try {
			    $attributes['version_no'] = 1;
				//VOUCHER NO LOGIC.....................
				// 2️⃣ Get the highest numeric part from voucher_master
				$maxNumeric = DB::table('journal')
					->where('deleted_at', '0000-00-00 00:0:00')
					//->where('department_id', $departmentId)
					->where('status', 1)->where('voucher_type', 'SIN')
					->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
					->value('max_no');
				
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
				$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();//echo '<pre>';print_r($accset);
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
					try {
						if ($this->setInputValue($attributes)) {

							$this->journal->status = 1;
							$this->journal->created_at = date('Y-m-d H:i:s');
							$this->journal->created_by = Auth::User()->id;
							$this->journal->fill($attributes)->save();
							$saved = true; // success ✅

						}	
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
							strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$maxNumeric = DB::table('journal')
								->where('deleted_at', '0000-00-00 00:0:00')
								//->where('department_id', $departmentId)
								->where('status', 1)->where('voucher_type', 'SIN')
								->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
								->value('max_no');
							
							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
							$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; //echo $ex;exit;// rethrow if different DB error
						}
					}
				}
				

				$hasLineAmount = isset($attributes['line_amount']) && is_array($attributes['line_amount']) && !empty(array_filter($attributes['line_amount'], function ($v) {
                                    return is_numeric($v) && (float) $v != 0;
                                }));

				//transactions insert
				//if($this->journal->id && !empty( array_filter($attributes['line_amount']))) {
				if (!empty($this->journal->id) && $hasLineAmount) { 
					$cr_amount = 0; $dr_amount = 0;
					foreach($attributes['line_amount'] as $key => $value) { //echo $attributes['account_id'][$key];exit;
					
					    if (empty($attributes['account_id'][$key]) || !is_numeric($value) || (float) $value == 0 ) {
                            continue;
                        }
						
						if($attributes['account_id'][$key] !='') {
							
							$journalEntryTr = new JournalEntry();
							$arrResult = $this->setTrInputValue($attributes, $journalEntryTr, $key);
							
							if($arrResult) {
								$cr_amount += $arrResult['cr_amount'];
								$dr_amount += $arrResult['dr_amount'];
								$journalEntryTr->status = 1;
								$jv_entry_id = $this->journal->JournalAdd()->save($journalEntryTr);
							}
							
							
							//JOURNAL ENTRY TRANSACTION INSERT................
							if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = $attributes['sales_invoice_id'][$key];
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							if($attributes['group_id'][$key]=='SUPPLIER' && $attributes['account_type'][$key]=='Dr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = $attributes['purchase_invoice_id'][$key];
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							//update invoice transaction status...
							$this->setTransactionStatus($attributes, $key, $journalEntryTr->id); //May 15....
							
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!=''){
								DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key] ]);
							}
						}
					}
					
					$difference = round($dr_amount - $cr_amount, 2);
					if(bccomp($difference, '0.00', 2) != 0) { 	
						Session::flash('error', 'Debit and Credit totals must be equal before saving this voucher.');
						return false;

					} else  { //JN1
						//update debit, credit, difference amount
						DB::table('journal')
									->where('id', $this->journal->id)
									->update(['debit'     => $dr_amount,
											  'credit' 	  => $cr_amount,
											  'difference' => $difference ]);
					} 
				}
				
				DB::commit();
				return true;
				
			} catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			  return false;
		    }
		}
		//throw new ValidationException('journal validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{ 
		//echo '<pre>';print_r($attributes);exit;
		$this->journal = $this->find($id);
		
		DB::beginTransaction();
		try {
			
			if($this->journal->id && !empty( array_filter($attributes['line_amount']))) {
				$cr_amount = 0; $dr_amount = 0;
				foreach($attributes['line_amount'] as $key => $value) {
					
					if($attributes['je_id'][$key]!='') {

						//FIND CURRENT VERSION	 
						$currentVersion = DB::table('account_transaction')->where('voucher_type', 'JV')->where('voucher_type_id', $attributes['je_id'][$key])->max('version_no');
						$newVersion = $currentVersion + 1;
						$attributes['version_no'] = $newVersion;

						//SOFT DELETE OLD VERSION
						DB::table('account_transaction')->where('voucher_type', 'JV')->where('voucher_type_id', $attributes['je_id'][$key])
									->update([
												'status' => 0,
												'deleted_at' => date('Y-m-d h:i:s'),
												'deleted_by'  => Auth::User()->id,
											]);
						
						if($attributes['account_id'][$key]!='' && $attributes['account_id'][$key]!=0) {
							$journalEntryTr = JournalEntry::find($attributes['je_id'][$key]);
							
							if($attributes['account_type'][$key]=='Dr')
								$dr_amount += $attributes['line_amount'][$key];
							else if($attributes['account_type'][$key]=='Cr')
								$cr_amount += $attributes['line_amount'][$key];
							
							$jerow['account_id'] = $attributes['account_id'][$key];
							$jerow['description']    		= $attributes['description'][$key];
							$jerow['reference']    		= $attributes['reference'][$key];
							$jerow['entry_type']    		= $attributes['account_type'][$key];
							$jerow['amount']    		= $attributes['line_amount'][$key];
							$jerow['job_id']    		= isset($attributes['job_id'][$key])?$attributes['job_id'][$key]:'';
							$jerow['department_id']    	= isset($attributes['department'][$key])?$attributes['department'][$key]:'';
							$jerow['cheque_no']   		= isset($attributes['cheque_no'][$key])?$attributes['cheque_no'][$key]:'';
							$jerow['cheque_date']    	=  isset($attributes['cheque_date'][$key])?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):'';
							$jerow['bank_id']   		= isset($attributes['bank_id'][$key])?$attributes['bank_id'][$key]:'';
							$jerow['party_account_id']  = isset($attributes['partyac_id'][$key])?$attributes['partyac_id'][$key]:'';
							
							$journalEntryTr->update($jerow);
							
							if($value=='' || $value==0) {
								DB::table('journal_entry')->where('id',$attributes['je_id'][$key])->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								DB::table('account_transaction')->where('voucher_type', 'JV')->where('voucher_type_id', $attributes['je_id'][$key])->update(['status' => 0, 'deleted_at' => date('Y-m-d h:i:s')]);
							}

							//update invoice transaction status...
							$this->setTransactionStatusUpdate($attributes, $key, $journalEntryTr->id); //May 15
						
							//$this->setAccountTransactionUpdate($attributes, $journalEntryTr->id, $key);
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
								
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]); 
							
							//PDCR list updating.... NOV27
							if($attributes['group_id'][$key]=='PDCR') {
								
								//UPDATE PDC...
								$pdcrow = DB::table('pdc_received')->where('entry_id', $attributes['je_id'][$key])->where('entry_type','JV')->select('id')->first();
								$acrow = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id')->first();
								$bnk = DB::table('account_setting')->where('voucher_type_id', 18)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('dr_account_master_id')->first();

								if($pdcrow)	{			
									DB::table('pdc_received')
													->where('id', $pdcrow->id)
													->update([ 	'dr_account_id' => ($acrow)?$acrow->id:0,
																'cr_account_id' => $attributes['account_id'][$key],
																'reference'  => $attributes['reference'][$key],
																'amount'   			=> $attributes['line_amount'][$key],
																'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
																'customer_id' => $attributes['partyac_id'][$key],
																'cheque_no' => $attributes['cheque_no'][$key],
																'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
																'voucher_no' => $attributes['voucher_no'],
																'description' => $attributes['description'][$key],
																'bank_id' => (isset($attributes['bank_id'][$key]) && $attributes['bank_id'][$key]!='')?$attributes['bank_id'][$key]:1,
																'deleted_at' => '0000-00-00 00:00:00',
																'dr_bank_id' => ($bnk)?$bnk->dr_account_master_id:0
															]);
								} else {
									//INSERT NEW PDC....
								
									DB::table('pdc_received')
												->insert([ 'voucher_id' 	=>  $this->journal->id,
															'voucher_type'   => 'DB',
															'dr_account_id' => ($acrow)?$acrow->id:0,
															'cr_account_id' => $attributes['account_id'][$key],
															'reference'  => $attributes['reference'][$key],
															'amount'   			=> $attributes['line_amount'][$key],
															'status' 			=> 0,
															'created_at' 		=> date('Y-m-d H:i:s'),
															'created_by' 		=> Auth::User()->id,
															'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
															'customer_id' => $attributes['partyac_id'][$key],
															'cheque_no' => $attributes['cheque_no'][$key],
															'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
															'voucher_no' => $attributes['voucher_no'],
															'description' => $attributes['description'][$key],
															'bank_id' => (isset($attributes['bank_id'][$key]) && $attributes['bank_id'][$key]!='')?$attributes['bank_id'][$key]:1,
															'entry_id' =>  $attributes['je_id'][$key],
															'entry_type' => 'JV',
															'dr_bank_id' => ($bnk)?$bnk->dr_account_master_id:0
														]);
								}

							} else { 
									//SET AS DELETED
									DB::table('pdc_received')
													->where('entry_id', $attributes['je_id'][$key])
													->where('entry_type','JV')
													->update([ 	'deleted_at'  => date('Y-m-d H:i:s') ]);
							}

							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!='' && isset($attributes['partyac_id'][$key]) && $attributes['partyac_id'][$key]!=''){
								DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> $attributes['partyac_id'][$key] ]);
							}

						}
						
						
						
					} else {
						
						//new entry....
						if($value!='' && $value!=0 && $attributes['account_id'][$key]!='' && $attributes['account_id'][$key]!=0){
							$journalEntryTr = new JournalEntry();
							$arrResult = $this->setTrInputValue($attributes, $journalEntryTr, $key);
							
							if($arrResult) {
								$cr_amount += $arrResult['cr_amount'];
								$dr_amount += $arrResult['dr_amount'];
								$journalEntryTr->status = 1;
								$jv_entry_id = $this->journal->JournalAdd()->save($journalEntryTr);
							}

							//PDCR list inserting....
							if($attributes['group_id'][$key]=='PDCR') {
								
								$acrow = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','BANK')->select('id')->first();
								if($attributes['partyac_id'][$key]=='') {
									$party_id = '';
									$ctrow = DB::table('receipt_voucher_entry')->where('receipt_voucher_id',$this->receipt_voucher->id)
													->where('entry_type','Cr')->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')
													->select('account_id')->first();
									if($ctrow) {
										$party_id = $ctrow->account_id;
									}
								} else
									$party_id = $attributes['partyac_id'][$key];
									
								$bnk = DB::table('account_setting')->where('voucher_type_id', 18)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('dr_account_master_id')->first();
								
								DB::table('pdc_received')
												->insert([ 'voucher_id' 	=>  $this->journal->id,
															'voucher_type'   => 'DB',
															'dr_account_id' => ($acrow)?$acrow->id:0,
															'cr_account_id' => $attributes['account_id'][$key],
															'reference'  => $attributes['reference'][$key],
															'amount'   			=> $attributes['line_amount'][$key],
															'status' 			=> 0,
															'created_at' 		=> date('Y-m-d H:i:s'),
															'created_by' 		=> Auth::User()->id,
															'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
															'customer_id' => $party_id,
															'cheque_no' => $attributes['cheque_no'][$key],
															'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
															'voucher_no' => $attributes['voucher_no'],
															'description' => $attributes['description'][$key],
															'bank_id'	=> (isset($attributes['bank_id'][$key]) && $attributes['bank_id'][$key]!='')?$attributes['bank_id'][$key]:1,
															'entry_id' => $jv_entry_id->id,
															'entry_type' => 'JV',
															'dr_bank_id' => ($bnk)?$bnk->dr_account_master_id:0
														]);
							}
							
							//JOURNAL ENTRY TRANSACTION INSERT................
							if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = $attributes['inv_id'][$key];
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							//update invoice transaction status...
							$this->setTransactionStatus($attributes, $key, $journalEntryTr->id); //May 15
							
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!=''){
								DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> $attributes['partyac_id'][$key] ]);
							}
						}
					}
					
				}
				
				
				//manage removed items...
				if($attributes['remove_item']!='')
				{ //echo '<pre>';print_r($attributes);exit;
					$arrids = array_unique(explode(',', $attributes['remove_item']));
					$remline_total = $remtax_total = 0;
					foreach($arrids as $id) {
						$jes = DB::table('journal_entry')
											->leftJoin('account_master', 'account_master.id', '=', 'journal_entry.account_id')
											->select('journal_entry.*','account_master.category')
											->where('journal_entry.id', $id)->first();
						if($jes) {
							DB::table('journal_entry')->where('id', $id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							DB::table('pdc_received')->where('entry_id',$id)->where('entry_type','JV')->where('status',0)->update(['deleted_at' => date('Y-m-d H:i:s')]); 
							
							if($jes->category=='SUPPLIER') {
								$invs = DB::table('payment_voucher_tr')->where('payment_voucher_entry_id', $id)->select('id','purchase_invoice_id','assign_amount')->get();
								foreach($invs as $inv) {
									if($inv->bill_type=='PI')
										DB::table('purchase_invoice')->where('id',$inv->purchase_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
									else if($inv->bill_type=='OB')
										DB::table('opening_balance_tr')->where('id', $inv->purchase_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
									else if($inv->bill_type=='PIN')
										DB::table('journal')->where('id', $inv->purchase_invoice_id)->update(['is_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
								}
								
							} else if($jes->category=='CUSTOMER') {
								$entry = DB::table('receipt_voucher_tr')->where('receipt_voucher_entry_id', $id)->where('status',1)->get();
								if($entry) {
									foreach($entry as $ent) {
										if($ent->bill_type=='SI')
											DB::table('sales_invoice')->where('id', $ent->sales_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										else if($ent->bill_type=='OB')
											DB::table('opening_balance_tr')->where('id', $ent->sales_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										else if($ent->bill_type=='SIN')
											DB::table('journal')->where('id', $ent->sales_invoice_id)->update(['is_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										
										DB::table('receipt_voucher_tr')->where('id', $ent->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s') ]);
									}
								}
							}
							
							$this->setAccountTransactionDelete($attributes, $id);
							
							//update closing balance of debitor/creditor account 
							$this->updateClosingBalance($jes->account_id, $jes->amount, $jes->entry_type);

							//REMOVE CHEQUE NO ALSO FROM CHEQUE TABLE....
							if($jes->bank_id!=0 && $jes->cheque_no!='') {
								DB::table('cheque')->where('cheque_no',$jes->cheque_no)->where('bank_id',$jes->bank_id)->where('account_id',$jes->account_id)->delete();
							}
						}
						
					}
				}
			}
			
			
			$difference = round($dr_amount - $cr_amount, 2);
			if(bccomp($difference, '0.00', 2) == 0) { 
			
				$this->journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
				$this->journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
				$this->journal->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
				$this->journal->debit = $dr_amount;
				$this->journal->credit = $cr_amount;
				$this->journal->difference = $difference;
				$this->journal->modify_at = date('Y-m-d H:i:s');
				$this->journal->modify_by = Auth::User()->id;
				$this->journal->fill($attributes)->save();

			} else {
				// Fetch all valid (non-deleted) entries
				$entries = DB::table('journal_entry')
					->where('journal_id', $id)
					->where('status', 1)
					->where('deleted_at', '0000-00-00 00:00:00')
					->get();

				foreach ($entries as $row) {
					// check if account_transaction exists
					$exists = DB::table('account_transaction')
						->where('voucher_type', 'JV')
						->where('voucher_type_id', $row->id)
						->exists();

					$data = [
						'account_master_id' => $row->account_id,
						'transaction_type'  => $row->entry_type,
						'amount'            => round($row->amount, 2),
						'description'       => $row->description,
						'reference'         => $this->receipt_voucher->voucher_no,
						'reference_from'    => $row->reference,
						'invoice_date'      => $this->receipt_voucher->voucher_date,
						'department_id'     => $row->department_id ?? null,
						'salesman_id'       => $row->salesman_id ?? null,
						'status'            => 1,
						'deleted_at'        => '0000-00-00 00:00:00',
						'modify_at'         => date('Y-m-d H:i:s'),
						'modify_by'         => Auth::user()->id,
					];

					if ($exists) {
						DB::table('account_transaction')
							->where('voucher_type', 'JV')
							->where('voucher_type_id', $row->id)
							->update($data);
					} else {
						DB::table('account_transaction')->insert(array_merge($data, [
							'voucher_type'    => 'JV',
							'voucher_type_id' => $row->id,
							'created_at'      => date('Y-m-d H:i:s'),
							'created_by'      => Auth::user()->id,
						]));
					}
				}
			}
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
		
	}
	
	
	public function updateSIN($id, $attributes)
	{ //echo '<pre>';print_r($attributes);exit;
		$this->journal = $this->find($id);
		
		DB::beginTransaction();
		try {
		    
		    $hasLineAmount = isset($attributes['line_amount']) && is_array($attributes['line_amount']) && !empty(array_filter($attributes['line_amount'], function ($v) {
                                    return is_numeric($v) && (float) $v != 0;
                                }));

				//transactions insert
				//if($this->journal->id && !empty( array_filter($attributes['line_amount']))) {
			if (!empty($this->journal->id) && $hasLineAmount) { 
			
			        //if($this->journal->id && !empty( array_filter($attributes['line_amount']))) {
				$cr_amount = 0; $dr_amount = 0;
				foreach($attributes['line_amount'] as $key => $value) {
				    
				    if (empty($attributes['account_id'][$key]) || !is_numeric($value) || (float) $value == 0 ) {
                        continue;
                    }
					
					if($attributes['je_id'][$key]!='') {

						//FIND CURRENT VERSION	 
					   $currentVersion = DB::table('account_transaction')->where('voucher_type', 'SIN')->where('voucher_type_id', $attributes['je_id'][$key])->max('version_no');
						$newVersion = $currentVersion + 1;
						$attributes['version_no'] = $newVersion;

						//SOFT DELETE OLD VERSION
						DB::table('account_transaction')->where('voucher_type', 'SIN')->where('voucher_type_id', $attributes['je_id'][$key])//->get();//echo '<pre>';print_r($attributes);exit;
									->update([
												'status' => 0,
												'deleted_at' => date('Y-m-d h:i:s'),
												'deleted_by'  => Auth::User()->id,
											]);
						
							$journalEntryTr = JournalEntry::find($attributes['je_id'][$key]);
							
							if($attributes['account_type'][$key]=='Dr')
								$dr_amount += $attributes['line_amount'][$key];
							else if($attributes['account_type'][$key]=='Cr')
								$cr_amount += $attributes['line_amount'][$key];
							
							$jerow['account_id'] = $attributes['account_id'][$key];
							$jerow['description']    		= $attributes['description'][$key];
							$jerow['reference']    		= $attributes['reference'][$key];
							$jerow['entry_type']    		= $attributes['account_type'][$key];
							$jerow['amount']    		= $attributes['line_amount'][$key];
							$jerow['job_id']    		= $attributes['job_id'][$key];
							$jerow['department_id']    	= isset($attributes['department'][$key])?$attributes['department'][$key]:'';
							$jerow['cheque_no']   		= isset($attributes['cheque_no'][$key])?$attributes['cheque_no'][$key]:'';
							$jerow['cheque_date']    	=  isset($attributes['cheque_date'][$key])?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):'';
							$jerow['bank_id']   		= isset($attributes['bank_id'][$key])?$attributes['bank_id'][$key]:'';
							$jerow['party_account_id']  = isset($attributes['partyac_id'][$key])?$attributes['partyac_id'][$key]:'';
							
							$journalEntryTr->update($jerow);
							
							if($attributes['group_id'][$key]=='PDCR') {
			
								DB::table('pdc_received')
												->where('voucher_id', $this->journal->id)
												->where('amount', $attributes['line_amount'][$key])
												->update([ 	'reference'  => $attributes['reference'][$key],
															'amount'   			=> $attributes['line_amount'][$key],
															'customer_id' => $attributes['partyac_id'][$key],
															'cheque_no' => $attributes['cheque_no'][$key],
															'cheque_date' => ($attributes['cheque_date'][$key]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$key])):date('Y-m-d'),
															'description' => $attributes['description'][$key]
														]);
							}
							
							//UPDATE JOURNAL VOUCHER TRANSACTION......
							/* if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='') {
								$journalVoucherTr = new JournalVoucherTr($attributes['id'][$key]);
								$invrow['assign_amount'] = $attributes['line_amount'][$key];
								$invrow['invoice_id'] = $attributes['inv_id'][$key];
								$receiptVoucherTr->update($invrow);
							} */
								
							//update invoice transaction status...
							$this->setTransactionStatusUpdate($attributes, $key, $journalEntryTr->id); //May 15
							
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!=''){
								DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> $attributes['partyac_id'][$key] ]);
							} 
						
					} else {
						
						//new entry....
							$journalEntryTr = new JournalEntry();
							$arrResult = $this->setTrInputValue($attributes, $journalEntryTr, $key);
							
							if($arrResult) {
								$cr_amount += $arrResult['cr_amount'];
								$dr_amount += $arrResult['dr_amount'];
								$journalEntryTr->status = 1;
								$jv_entry_id = $this->journal->JournalAdd()->save($journalEntryTr);
							}
							
							//JOURNAL ENTRY TRANSACTION INSERT................
							if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='')
							{
								$journalVoucherTr = new JournalVoucherTr();
								
								$journalVoucherTr->journal_entry_id = $jv_entry_id;
								$journalVoucherTr->invoice_id = $attributes['inv_id'][$key];
								$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
								$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
								$journalVoucherTr->status = 1;
								$journalEntryTr->JournalVoucherTrAdd()->save($journalVoucherTr);
							}
							
							//update invoice transaction status...
							$this->setTransactionStatus($attributes, $key, $journalEntryTr->id); //May 15
							
							$this->setAccountTransaction($attributes, $journalEntryTr->id, $key);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
							
							if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!=''){
								DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key],'account_id'=> $attributes['partyac_id'][$key] ]);
							}
					}
				}
				
				
				
				$difference = (int)($dr_amount - $cr_amount);
				
				//manage removed items...
				if($attributes['remove_item']!='')
				{ //echo '<pre>';print_r($attributes);exit;
					$arrids = array_unique(explode(',', $attributes['remove_item']));
					$remline_total = $remtax_total = 0;
					foreach($arrids as $id) {
						$jes = DB::table('journal_entry')
											->leftJoin('account_master', 'account_master.id', '=', 'journal_entry.account_id')
											->select('journal_entry.*','account_master.category')
											->where('journal_entry.id', $id)->first();
						if($jes) {
							DB::table('journal_entry')->where('id', $id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							if($jes->category=='SUPPLIER') {
								$invs = DB::table('payment_voucher_tr')->where('payment_voucher_entry_id', $id)->select('id','purchase_invoice_id','assign_amount')->get();
								foreach($invs as $inv) {
									if($inv->bill_type=='PI')
										DB::table('purchase_invoice')->where('id',$inv->purchase_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
									else if($inv->bill_type=='OB')
										DB::table('opening_balance_tr')->where('id', $inv->purchase_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
									else if($inv->bill_type=='PIN')
										DB::table('journal')->where('id', $inv->purchase_invoice_id)->update(['is_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$inv->assign_amount) ]);
								}
								
							} else if($jes->category=='CUSTOMER') {
								$entry = DB::table('receipt_voucher_tr')->where('receipt_voucher_entry_id', $id)->where('status',1)->get();
								if($entry) {
									foreach($entry as $ent) {
										if($ent->bill_type=='SI')
											DB::table('sales_invoice')->where('id', $ent->sales_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										else if($ent->bill_type=='OB')
											DB::table('opening_balance_tr')->where('id', $ent->sales_invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										else if($ent->bill_type=='SIN')
											DB::table('journal')->where('id', $ent->sales_invoice_id)->update(['is_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
										
										DB::table('receipt_voucher_tr')->where('id', $ent->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s') ]);
									}
								}
							}
							
							$this->setAccountTransactionDelete($attributes, $id);
							
							//update closing balance of debitor/creditor account
							$this->updateClosingBalance($jes->account_id, $jes->amount, $jes->entry_type);
						}
						
					}
				}
			}
			
			
			if($difference==0) { //JN1
			
				$this->journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
				$this->journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
				$this->journal->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
				$this->journal->debit = $dr_amount;
				$this->journal->credit = $cr_amount;
				$this->journal->difference = $difference;
				$this->journal->modify_at = date('Y-m-d H:i:s');
				$this->journal->modify_by = Auth::User()->id;
				$this->journal->fill($attributes)->save();
			} else {
				throw new ValidationException('Journal entry validation error! Please try again.', $this->getErrors());
			}
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback();
			dd($e->getMessage(), $e->getLine());
			return false;
		}
		
	}
	
	public function delete($id)
	{
		$this->journal = $this->journal->find($id);
		
		DB::beginTransaction();
		try {
			
			$rows = DB::table('journal_entry')->where('journal_id', $id)->select('id','account_id','entry_type','amount','cheque_no','bank_id')->get();
			foreach($rows as $row) {
				
				if($row->entry_type=='Dr') {
					$account_id = $row->account_id; $amount = $row->amount;
					if($this->journal->voucher_type=='PDCR') {
						DB::table('pdc_received')->where('entry_id',$row->id)->where('entry_type','RV')->where('status',0)->update(['deleted_at' => date('Y-m-d H:i:s')]);

						DB::table('account_master')->where('id', $row->account_id)
													->update(['cl_balance' => DB::raw('IF(cl_balance < 0, cl_balance - '.$row->amount.', cl_balance + '.$row->amount.')'), 'pdc_amount' => DB::raw('IF(pdc_amount < 0, pdc_amount + '.$row->amount.', pdc_amount - '.$row->amount.')')]);

						//DELETE CHEQUE NO...
						if($row->cheque_no!='' && $row->bank_id!='' && $row->account_id!='') {
							DB::table('cheque')->where('cheque_no',$row->cheque_no)->where('bank_id',$row->bank_id)->where('account_id',$row->party_account_id)->delete();
						}

					} else
						DB::table('account_master')->where('id', $row->account_id)->update(['cl_balance' => DB::raw('cl_balance - '.$row->amount)]);

				} else {
					DB::table('account_master')->where('id', $row->account_id)
												->update(['cl_balance' => DB::raw('IF(cl_balance < 0, cl_balance - '.$row->amount.', cl_balance + '.$row->amount.')')]);
					
					//update sales invoice entry....
					$entry = DB::table('journal_voucher_tr')->where('journal_entry_id', $row->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					if($entry) {
						foreach($entry as $ent) {
							if($ent->bill_type=='SI')
								DB::table('sales_invoice')->where('id', $ent->invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
							
							DB::table('journal_voucher_tr')->where('id', $ent->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s') ]);
						}
					}

					//Transaction update....
					DB::table('account_transaction')->where('voucher_type', 'JV')->where('voucher_type_id',$row->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::User()->id  ]);
					$this->objUtility->tallyClosingBalance($row->account_id);

					//REMOVE CHEQUE NO ALSO FROM CHEQUE TABLE....
					if($row->bank_id!=0 && $row->cheque_no!='') {
						DB::table('cheque')->where('cheque_no',$row->cheque_no)->where('bank_id',$row->bank_id)->where('account_id', $row->party_account_id)->delete();
					}
				}
				
				
				if($row->entry_type=='Cr') {
					$account_id = $row->account_id; $amount = $row->amount;
					if($this->journal->voucher_type=='PDCI') {
						DB::table('pdc_issued')->where('entry_id',$row->id)->where('entry_type','PV')->where('status',0)->update(['deleted_at' => date('Y-m-d H:i:s')]);
						DB::table('account_master')->where('id', $row->account_id)
													->update(['cl_balance' => DB::raw('IF(cl_balance < 0, cl_balance - '.$row->amount.', cl_balance + '.$row->amount.')'), 'pdc_amount' => DB::raw('IF(pdc_amount < 0, pdc_amount + '.$row->amount.', pdc_amount - '.$row->amount.')')]);
						
						//DELETE CHEQUE NO...
						if($row->cheque_no!='' && $row->bank_id!='' && $row->account_id!='') {
							DB::table('cheque')->where('cheque_no',$row->cheque_no)->where('bank_id',$row->bank_id)->where('ctype',1)->delete();
						}
					} else
						DB::table('account_master')->where('id', $row->account_id)->update(['cl_balance' => DB::raw('cl_balance - '.$row->amount)]);
					
				} else {
					DB::table('account_master')->where('id', $row->account_id)
												->update(['cl_balance' => DB::raw('IF(cl_balance < 0, cl_balance - '.$row->amount.', cl_balance + '.$row->amount.')')]);
					
					DB::table('journal_entry')->where('id', $row->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id  ]);
					
					//Transaction update....
					DB::table('account_transaction')->where('voucher_type', 'JV')->where('voucher_type_id',$row->id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::User()->id  ]);
					$this->objUtility->tallyClosingBalance($row->account_id);
					
					//REMOVE CHEQUE NO ALSO FROM CHEQUE TABLE....
					if($row->bank_id!=0 && $row->cheque_no!='') {
						DB::table('cheque')->where('cheque_no',$row->cheque_no)->where('bank_id',$row->bank_id)->where('account_id', $row->party_account_id)->delete();
					}
				}
				
			}
			
			DB::table('journal_entry')->where('journal_id', $id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id  ]);

			DB::table('journal')->where('id', $id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id  ]);
			$this->journal->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	public function journalListCommon($type)
	{
		$result = $this->journal->where('journal.status', 1)
							 ->join('journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'journal.id');
							 })
							 ->Join('account_master AS AM', function($join) {
                            		$join->on('AM.id','=','JE.account_id');
                            		$join->where('JE.entry_type','=','Dr');
                            	})
							 ->where('voucher_type', $type)
							 ->where('JE.status',1)
							 ->where('JE.deleted_at','0000-00-00 00:00:00')
							 ->select('journal.*','JE.description','JE.reference','AM.master_name')
							 ->groupBy('journal.id')
							 ->orderBy('journal.id', 'DESC')
							 ->get();
		// $result = $this->journal->where('journal.status', 1)
		// 					 ->join('journal_entry AS JE', function($join) {
		// 						 $join->on('JE.journal_id', '=', 'journal.id');
		// 					 })
		// 					 ->where('voucher_type', $type)
		// 					 ->where('JE.status',1)
		// 					 ->where('JE.deleted_at','0000-00-00 00:00:00')
		// 					 ->select('journal.*','JE.description')
		// 					 ->groupBy('journal.id')
		// 					 ->orderBy('journal.id', 'DESC')
		// 					 ->get();
		return $result;
	}
	
	//public function journalList($type,$start,$limit,$order,$dir,$search)
	public function journalList()
	{
		$result = $this->journal->where('journal.status', 1)
							 ->join('journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'journal.id');
							 })
							 ->where('JE.status',1)
							 ->where('JE.deleted_at','0000-00-00 00:00:00')
							 ->select('journal.*','JE.description')
							 ->groupBy('journal.id')
							 ->orderBy('journal.id', 'DESC')
							 ->get();
							 
					//  if($search) {
					// 	 $query->where(function($query) use ($search){
					// 		 $query->where('journal.voucher_no','LIKE',"%{$search}%")
					// 				->orWhere('JE.description', 'LIKE',"%{$search}%");
					// 	});
					//  }
					// 		 $query->select('journal.*','JE.description');
							 
					// 		 $query->offset($start)
					// 				->limit($limit)
					// 				->groupBy('journal.id')
					// 				->orderBy($order,$dir);
									
					// 		if($type=='get')
					// 			return $query->get();
					// 		else
					// 			return $query->count();

		
		return $result;
	}
	public function journalListSV($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->journal->where('journal.status', 1)
							 ->join('journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'journal.id');
							 })
							 ->Join('account_master AS AM', function($join) {
                            		$join->on('AM.id','=','JE.account_id');
                            		$join->where('JE.entry_type','=','Dr');
                            	})
							 ->where('voucher_type','SIN')
							 ->where('JE.status',1)
							 ->where('JE.deleted_at','0000-00-00 00:00:00');
							 

							 if($search) {
								$query->where(function($query) use ($search){
									$query->where('journal.voucher_no','LIKE',"%{$search}%")
										   ->orWhere('JE.description', 'LIKE',"%{$search}%")
										   ->orWhere('JE.reference', 'LIKE',"%{$search}%")
										   ->orWhere('AM.master_name', 'LIKE',"%{$search}%");
										
							   });
							}

							$query->select('journal.*','JE.description','JE.reference','AM.master_name');
							if($type=='get')
							return $query->offset($start)
					 				->limit($limit)
					 				->groupBy('journal.id')
					 				->orderBy($order,$dir)
									 ->get();
					 		
					 			
					 		else
					 			return $query->orderBy($order,$dir)
								           // ->groupBy('journal.id')
								              ->count();
							
							
	}
	
	public function journalListPara($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->journal->where('journal.status', 1)->where('journal.voucher_type','JV')
							 ->join('journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'journal.id');
							 })
							 ->where('JE.status',1)
							 
							 ->where('JE.deleted_at','0000-00-00 00:00:00');
							 
					  if($search) {
					 	 $query->where(function($query) use ($search){
					 		 $query->where('journal.voucher_no','LIKE',"%{$search}%")
					 				->orWhere('JE.description', 'LIKE',"%{$search}%")
					 				->orWhere('JE.reference', 'LIKE',"%{$search}%")
					 				->orWhere('journal.voucher_type', 'LIKE',"%{$search}%");
					 	});
					  }
					 		 $query->select('journal.*','JE.description','JE.reference');
							 
					 		 $query->offset($start)
					 				->limit($limit)
					 				->groupBy('journal.id')
					 				->orderBy($order,$dir);
									
					 		if($type=='get')
					 			return $query->get();
					 		else
					 			return $query->count();

		
	}
	
	public function journalListCount()
	{
		return $this->journal->where('journal.status', 1)
							 /* ->join('journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'journal.id');
							 })
							 ->where('JE.status',1)
							 ->where('JE.deleted_at','0000-00-00 00:00:00')
							 ->select('journal.*','JE.description')
							 ->groupBy('journal.id')
							 ->orderBy('journal.id', 'DESC') */
							 ->count();
	}
	
	public function journalListprit($type,$voucher_no)
	
	{
		
		if($type ==16 )
		$result = $this->journal->where('journal.status', 1)
						 			 ->join('journal_entry AS JE', function($join) {
										 $join->on('JE.journal_id', '=', 'journal.id');
						 			 })
									->where('voucher_type','JV')->where('voucher_no', $voucher_no)
								 ->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')
						 			 ->select('journal.*','JE.description')->get();
		elseif($type ==5)
		       $result = $this->journal->where('journal.status', 1)
		                               ->join('journal_entry AS JE', function($join) {
		                                  $join->on('JE.journal_id', '=', 'journal.id');
		                                      })
	                                     ->where('voucher_type','PIN')->where('voucher_no', $voucher_no)
                                      ->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')
		                           ->select('journal.*','JE.description')->get();
		elseif($type ==6)
			   $result = $this->journal->where('journal.status', 1)
									   ->join('journal_entry AS JE', function($join) {
										  $join->on('JE.journal_id', '=', 'journal.id');
											  })
										 ->where('voucher_type','SIN')->where('voucher_no', $voucher_no)
									  ->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')
								   ->select('journal.*','JE.description')->get();
		elseif($type ==10)
			$result = DB::table('payment_voucher')->where('status',1)->where('voucher_no', $voucher_no)->get();

		elseif($type ==9)
			$result = DB::table('receipt_voucher')->where('status',1)->where('voucher_no', $voucher_no)->get();
	
		return $result;
	}

    
    public function journalListpritlast($type)
	
	{
		
		if($type ==16 )
		    $result = $this->journal->where('journal.status', 1)->where('voucher_type','JV')->orderBy('id','desc')->first();
		
		elseif($type ==5)
		  $result = $this->journal->where('journal.status', 1)->where('voucher_type','PIN')->orderBy('id','desc')->first();
		elseif($type ==6)
			$result = $this->journal->where('journal.status', 1)->where('voucher_type','SIN')->orderBy('id','desc')->first();
		elseif($type ==10)
				$result = DB::table('payment_voucher')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','desc')->first();

		elseif($type ==9)
			$result = DB::table('receipt_voucher')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','desc')->first();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}

	public function findJEdata($id)
	{
		$result = DB::table('journal_entry')->where('journal_entry.journal_id', $id)
						->join('account_master', 'account_master.id', '=', 'journal_entry.account_id')
						->leftJoin('account_master AS AM', 'AM.id', '=', 'journal_entry.party_account_id')
						->leftJoin('jobmaster AS J', 'J.id', '=', 'journal_entry.job_id')
						->where('journal_entry.status', 1)
						->select('journal_entry.*','account_master.master_name','AM.master_name AS party_name','account_master.category','J.code')
						->orderBy('journal_entry.id','ASC')
						->get();
	//echo '<pre>';print_r($result);exit;					
	return $result;
	}
	
	public function check_vno($refno, $id = null) { 
		
		if($id)
			return $this->journal->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->journal->where('voucher_no',$refno)->count();
	}
	
	public function check_voucher_no($refno, $vtype, $id = null) { 
		
		if($id)
			return $this->journal->where('voucher_no', $refno)->where('id', '!=', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
		else {
			switch($vtype) {
				case 16:
					return $this->journal->where('voucher_no', $refno)->where('voucher_type', 'JV')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					break;
				
				case 9:
					return DB::table('receipt_voucher')->where('voucher_no', $refno)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					break;
					
				case 10:
					return DB::table('payment_voucher')->where('voucher_no', $refno)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					break;
					
				case 5:
					return $this->journal->where('voucher_no', $refno)->where('voucher_type', 'PIN')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					break;
					
				case 6:
					return $this->journal->where('voucher_no', $refno)->where('voucher_type', 'SIN')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					break;
			}
		}
	}
	
	
	public function getPDCreport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		DB::enableQueryLog();
		if($attributes['search_type']=="issued")
		{
			
			$ob_only = (isset($attributes['ob_only'])  && $attributes['ob_only'] !='')?true:false;
			
			$query1 = DB::table('pdc_issued')
						->join('account_master', 'account_master.id', '=', 'pdc_issued.dr_account_id')
						->join('account_master AS AM', 'AM.id', '=', 'pdc_issued.supplier_id')
						->join('bank AS B', 'B.id', '=', 'pdc_issued.bank_id')
						->join('payment_voucher AS PV', 'PV.id', '=', 'pdc_issued.voucher_id');

			/*$query1 = DB::table('pdc_issued')
						->join('account_master', 'account_master.id', '=', 'pdc_issued.dr_account_id')
						->join('account_master AS AM', 'AM.id', '=', 'pdc_issued.supplier_id')
						->join('payment_voucher AS PV', 'PV.id', '=', 'pdc_issued.voucher_id')
						->join('payment_voucher_entry AS PVE', function($join){
							$join->on('PVE.payment_voucher_id', '=', 'pdc_issued.voucher_id');
							$join->where('PVE.entry_type', '=', 'Cr');
						})
						->join('bank AS B', 'B.id', '=', 'PVE.bank_id');*/
			
			if($date_from!='' && $date_to!='') {
				$query1->whereBetween('pdc_issued.cheque_date',[$date_from, $date_to]);
			}
			
			if($attributes['account_id']!='') {
				$acid = $attributes['account_id'];
				$query1->where('pdc_issued.supplier_id', $acid)	;
			}
			
			if($attributes['status']!='') {
				$query1->where('pdc_issued.status', $attributes['status']);
			}
					
			if($ob_only) {
				//$query1->where('PV.opening_balance_id', '>', 0);
				$query1->whereRaw('PV.opening_balance_id > 0');
			}		

		$result = $query1->where('pdc_issued.deleted_at','0000-00-00 00:00:00')
				->select('pdc_issued.*','account_master.master_name AS debitor','AM.master_name AS customer',
						'B.code','pdc_issued.entry_type AS vtype',
						DB::raw('EXTRACT(MONTH FROM pdc_issued.cheque_date) AS month'))
				->groupBy('pdc_issued.id')
				->orderBy('pdc_issued.cheque_date','ASC')
				->get();
				
				//dd(DB::getQueryLog());exit;

				/*$result = $query1->where('pdc_issued.deleted_at','0000-00-00 00:00:00')
								->select('pdc_issued.*','account_master.master_name AS debitor','AM.master_name AS customer','PV.voucher_no',
										'PVE.cheque_no','PVE.cheque_date','B.code','PV.voucher_type AS vtype',
										DB::raw('EXTRACT(MONTH FROM pdc_issued.cheque_date) AS month'))
								->groupBy('pdc_issued.id')
								->orderBy('pdc_issued.cheque_date','ASC')
								->get();*/
			return $result;
						
		} else if($attributes['search_type']=="received") {
			
			$ob_only = (isset($attributes['ob_only'])  && $attributes['ob_only'] !='')?true:false;
			
			$query1 = DB::table('pdc_received')
						->join('account_master', 'account_master.id', '=', 'pdc_received.cr_account_id')
						->join('account_master AS AM', 'AM.id', '=', 'pdc_received.customer_id')
						->join('bank AS B', 'B.id', '=', 'pdc_received.bank_id')
						->join('receipt_voucher AS RV', 'RV.id', '=', 'pdc_received.voucher_id');
			//echo '<pre>';print_r($query1);exit;
			if($date_from!='' && $date_to!='') {
				$query1->whereBetween('pdc_received.cheque_date',[$date_from, $date_to]);
			}
			
			if($attributes['account_id']!='') {
				$acid = $attributes['account_id'];
				$query1->where('pdc_received.customer_id', $acid)	;
			}
			
			if($attributes['status']!='') {
				$query1->where('pdc_received.status', $attributes['status']);
			}
					
			if($ob_only) {
				$query1->where('RV.opening_balance_id', '>', 0);
			}	
				
				$result = $query1->where('pdc_received.deleted_at','0000-00-00 00:00:00')
								->select('pdc_received.*','account_master.master_name AS debitor','AM.master_name AS customer',
										'B.code','pdc_received.entry_type AS vtype',
										DB::raw('EXTRACT(MONTH FROM pdc_received.cheque_date) AS month'))
								->groupBy('pdc_received.id')
								->orderBy('pdc_received.cheque_date','ASC')
								->get();
						
								
			return $result;
			
		} 
	}

	public function getLastId() {
		
		return $this->journal->where('status',1)
					->select('id')
					->orderBY('id', 'DESC')
					->first();

	}
}