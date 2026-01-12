<?php
declare(strict_types=1);
namespace App\Repositories\ManualJournal;

use App\Models\ManualJournal;
use App\Models\ManualJournalEntry;
use App\Models\OtherVoucherTr;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use App\Models\ManualJournalVoucherTr;

use Config;
use Illuminate\Support\Facades\DB;
use Auth;

class ManualJournalRepository extends AbstractValidator implements ManualJournalInterface {
	
	public $objUtility;
	
	protected $manual_journal;
	
	protected static $rules = [];
	
	public function __construct(ManualJournal $manual_journal) {
		$this->manual_journal = $manual_journal;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->manual_journal->get();
	}
	
	public function find($id)
	{
		return $this->manual_journal->where('id', $id)->first();
	}
	
	private function getVoucherType($id)
	{
		switch($id) {
			
			case 25:
			return 'MJV';
			break;
			
		}	
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		//echo $this->getVoucherType( $attributes['voucher_type'] ); voucher_type
		$this->manual_journal->voucher_type  = $attributes['voucher_type'];
		$this->manual_journal->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])); //date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->manual_journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
		$this->manual_journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
		$this->manual_journal->group_id = $attributes['group_id'][0];
		$this->manual_journal->department_id = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function setTrInputValue($attributes, $journalEntryTr, $key) 
	{
		echo '<pre>';print_r($journalEntryTr);exit;
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
		$journalEntryTr->amount    		= $attributes['line_amount'][$key];
		//$journalEntryTr->fc_amount    		= $attributes['amount_fc'][$key];
		//$journalEntryTr->fc_id    		= $attributes['fc'][$key];
		//$journalEntryTr->currency_rate    		= $attributes['currency_rate'][$key];
		$journalEntryTr->job_id    		= $attributes['job_id'][$key];
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
		
		//PDCR list inserting....
		if($attributes['group_id'][$key]=='PDCR') {
			
			$acrow = DB::table('account_master')->where('status',1)->where('category','BANK')->select('id')->first();
			
			DB::table('pdc_received')
							->insert([ 'voucher_id' 	=>  $this->journal->id,
										'voucher_type'   => 'DB',
										'dr_account_id' => $acrow->id,
										'cr_account_id' => $attributes['account_id'][$key],
										'reference'  => $attributes['reference'][$key],
										'amount'   			=> $attributes['line_amount'][$key],
										'status' 			=> 0,
										'created_at' 		=> now(),
										'created_by' 		=> Auth::User()->id,
										'voucher_date'		=> ($attributes['voucher_date']!='')?date('Y-m-d', strtotime($attributes['voucher_date'])):date('Y-m-d'),
										'customer_id' => $attributes['partyac_id'][$key],
										'cheque_no' => $attributes['cheque_no'][$key],
										'cheque_date' => date('Y-m-d', strtotime($attributes['cheque_date'][$key])),
										'voucher_no' => $attributes['voucher_no'],
										'description' => $attributes['description'][$key]
									]);
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
							'created_at' 		=> now(),
							'created_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key],
							'department_id'     => $department
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
							'modify_at' 		=> now(),
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
						   'deleted_at' 	=> now(),
						   'deleted_by'		=> Auth::User()->id ]);
		
		return true;}
	
	private function setTransactionStatus($attributes, $key, $jv_entry_id=null) //May 15 
	{
		if($attributes['group_id'][$key]=='CUSTOMER') { //customer type............
			//if amount partially transfered, update pending amount.
			if( isset($attributes['inv_id'][$key]) ) {
				if($attributes['bill_type'][$key]=='SI') {
					$lnamount = ($attributes['line_amount'][$key]!='')?$attributes['line_amount'][$key]:0;
					$actamount = ($attributes['actual_amount'][$key]!='')?$attributes['actual_amount'][$key]:0;
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
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
							
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
							
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
					
					$balance_amount = $attributes['actual_amount'][$key] - $attributes['line_amount'][$key];
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
	
	
	public function create2($attributes)
	{ 
		echo '<pre>';print_r($attributes);exit;
		
		if($this->isValid($attributes)) { 
		
			DB::beginTransaction();
			try {
				
				do {
					$jvset = DB::table('account_setting')->where('id', $attributes['voucher'])->select('prefix','is_prefix','voucher_no')->first();
					if($jvset) {
						if($jvset->is_prefix==0) {
							$attributes['voucher_no'] = $jvset->voucher_no;
							$attributes['vno'] = $jvset->voucher_no;
						} else {
							$attributes['voucher_no'] = $jvset->prefix.$jvset->voucher_no;
							$attributes['vno'] = $jvset->voucher_no;
						}
					}
					$inv = DB::table('manual_journal')->where('voucher_no',$attributes['voucher_no'])->where('voucher_type','MJV')->where('status',1)->whereNull('deleted_at')->count();
				} while ($inv!=0);
				
				
				if($this->setInputValue($attributes)) {
					$this->manual_journal->status = 1;
					$this->manual_journal->created_at = now();
					$this->manual_journal->created_by = 1;
					$this->manual_journal->fill($attributes)->save();
				}
				
				//transactions insert
				if($this->manual_journal->id && !empty( array_filter($attributes['line_amount']))) {
					
					$cr_amount = 0; $dr_amount = 0;
					foreach($attributes['line_amount'] as $key => $value) { 
						$journalEntryTr = new ManualJournalEntry();
						
						$arrResult = $this->setTrInputValue($attributes, $journalEntryTr, $key);
						
						if($arrResult) {
							$cr_amount += $arrResult['cr_amount'];
							$dr_amount += $arrResult['dr_amount'];
							$journalEntryTr->status = 1;
							//echo '<pre>';print_r($journalEntryTr);exit;
							$jv_entry_id = $this->manual_journal->ManualJournalAdd()->save($journalEntryTr);
						}
						
						
						//JOURNAL ENTRY TRANSACTION INSERT................
						if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='')
						{
							
							$journalVoucherTr = new ManualJournalVoucherTr();
							$journalVoucherTr->journal_entry_id = $jv_entry_id;
							$journalVoucherTr->invoice_id = $attributes['sales_invoice_id'][$key];
							$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
							$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
							$journalVoucherTr->status = 1;
							
							$journalEntryTr->ManualJournalVoucherTrAdd()->save($journalVoucherTr);
						}
						
						if($attributes['group_id'][$key]=='SUPPLIER' && $attributes['account_type'][$key]=='Dr' && $attributes['inv_id'][$key]!='')
						{
							$journalVoucherTr = new ManualJournalVoucherTr();
							
							$journalVoucherTr->journal_entry_id = $jv_entry_id;
							$journalVoucherTr->invoice_id = $attributes['purchase_invoice_id'][$key];
							$journalVoucherTr->assign_amount = $attributes['line_amount'][$key];
							$journalVoucherTr->bill_type = $attributes['bill_type'][$key];
							$journalVoucherTr->status = 1;
							$journalEntryTr->ManualJournalVoucherTrAdd()->save($journalVoucherTr);
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
					
					$difference = $dr_amount - $cr_amount;
					//update debit, credit, difference amount
					DB::table('manual_journal')
								->where('id', $this->journal->id)
								->update(['debit'     => $dr_amount,
										  'credit' 	  => $cr_amount,
										  'difference' => $difference ]);
									
				}
				
				//update voucher no........
				if( ($this->manual_journal->id) && ($attributes['curno'] <= $attributes['voucher_no']) ) {
					DB::table('account_setting')
							->where('id', $attributes['voucher'])
							->update(['voucher_no' => $attributes['voucher_no'] + 1 ]);
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
	{ //echo '<pre>';print_r($attributes);exit;
		$this->journal = $this->find($id);
		
		DB::beginTransaction();
		try {
			
			if($this->manual_journal->id && !empty( array_filter($attributes['line_amount']))) {
				$cr_amount = 0; $dr_amount = 0;
				foreach($attributes['line_amount'] as $key => $value) {
					
					if($attributes['je_id'][$key]!='') {
						
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
						
						//UPDATE JOURNAL VOUCHER TRANSACTION......
						/* if($attributes['group_id'][$key]=='CUSTOMER' && $attributes['account_type'][$key]=='Cr' && $attributes['inv_id'][$key]!='') {
							$journalVoucherTr = new JournalVoucherTr($attributes['id'][$key]);
							$invrow['assign_amount'] = $attributes['line_amount'][$key];
							$invrow['invoice_id'] = $attributes['inv_id'][$key];
							$receiptVoucherTr->update($invrow);
						} */
							
						//update invoice transaction status...
						$this->setTransactionStatusUpdate($attributes, $key, $journalEntryTr->id); //May 15
						
						$this->setAccountTransactionUpdate($attributes, $journalEntryTr->id, $key);
						
						//update closing balance of debitor/creditor account
						$this->updateClosingBalance($attributes['account_id'][$key], $attributes['line_amount'][$key], $attributes['account_type'][$key]);
						
						if(isset($attributes['cheque_no'][$key]) && $attributes['cheque_no'][$key]!=''){
							DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key] ]);
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
							DB::table('cheque')->insert([ 'cheque_no' => $attributes['cheque_no'][$key], 'bank_id' => $attributes['bank_id'][$key] ]);
						}
					}
				}
				
				$difference = $dr_amount - $cr_amount;
				
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
							DB::table('journal_entry')->where('id', $id)->update(['status' => 0, 'deleted_at' => now()]);
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
										
										DB::table('receipt_voucher_tr')->where('id', $ent->id)->update(['status' => 0,'deleted_at' => now() ]);
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
			
			$this->journal->supplier_name = isset($attributes['supplier_name'])?$attributes['supplier_name']:'';
			$this->journal->trn_no = isset($attributes['trn_no'])?$attributes['trn_no']:'';
			$this->journal->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
			$this->journal->debit = $dr_amount;
			$this->journal->credit = $cr_amount;
			$this->journal->difference = $difference;
			$this->journal->modify_at = now();
			$this->journal->modify_by = 1;
			$this->journal->fill($attributes)->save();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback();
			return false;
		}
		
	}
	
	
	public function delete($id)
	{
		$this->journal = $this->journal->find($id);
		
		DB::beginTransaction();
		try {
			
			$rows = DB::table('journal_entry')->where('journal_id', $id)->select('id','account_id','entry_type','amount')->get();
			foreach($rows as $row) {
				
				//Transaction update....
				DB::table('account_transaction')->where('voucher_type', $this->journal->voucher_type)->where('voucher_type_id', $row->id)->update(['status' => 0,'deleted_at' => now(), 'deleted_by' => Auth::User()->id ]);
				$this->objUtility->tallyClosingBalance($row->account_id);
				
				//update sales invoice entry....
				$entry = DB::table('journal_voucher_tr')->where('journal_entry_id', $row->id)->where('status',1)->whereNull('deleted_at')->get();
				if($entry) {
					foreach($entry as $ent) {
						if($ent->bill_type=='SI')
							DB::table('sales_invoice')->where('id', $ent->invoice_id)->update(['amount_transfer' => 0, 'balance_amount' => DB::raw('balance_amount + '.$ent->assign_amount) ]);
						
						DB::table('journal_voucher_tr')->where('id', $ent->id)->update(['status' => 0,'deleted_at' => now() ]);
					}
				}
			}
			
			DB::table('journal_entry')->where('journal_id', $id)->update(['status' => 0,'deleted_at' => now() ]);
			$this->journal->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback();
			return false;
		}
	}
	
	public function journalList()
	{
		$result = $this->manual_journal->where('manual_journal.status', 1)
							 ->join('manual_journal_entry AS JE', function($join) {
								 $join->on('JE.journal_id', '=', 'manual_journal.id');
							 })
							// ->where('voucher_type', $type)
							 ->where('JE.status',1)
							 ->where('JE.deleted_at','0000-00-00 00:00:00')
							 ->select('manual_journal.*','JE.description')
							 ->groupBy('manual_journal.id')
							 ->orderBy('manual_journal.id', 'DESC')
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
													   $result = $this->journal->where('journal.status', 1)
																			   ->join('journal_entry AS JE', function($join) {
																				  $join->on('JE.journal_id', '=', 'journal.id');
																					  })
																				 ->where('voucher_type','PV')->where('voucher_no', $voucher_no)
																			  ->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')
																		   ->select('journal.*','JE.description')->get();

		elseif($type ==9)
								   $result = $this->journal->where('journal.status', 1)
														   ->join('journal_entry AS JE', function($join) {
															  $join->on('JE.journal_id', '=', 'journal.id');
																  })
															 ->where('voucher_type','RV')->where('voucher_no', $voucher_no)
														  ->where('JE.status',1)->where('JE.deleted_at','0000-00-00 00:00:00')
													   ->select('journal.*','JE.description')->get();
		//echo '<pre>';print_r($result);exit;
		return $result;
	}

	public function findJEdata($id)
	{
		$result = DB::table('journal_entry')->where('journal_entry.journal_id', $id)
						->join('account_master', 'account_master.id', '=', 'journal_entry.account_id')
						->leftJoin('account_master AS AM', 'AM.id', '=', 'journal_entry.party_account_id')
						->where('journal_entry.status', 1)
						->select('journal_entry.*','account_master.master_name','AM.master_name AS party_name','account_master.category')
						->get();
	//echo '<pre>';print_r($result);exit;					
	return $result;
	}
	
	public function check_voucher_no($refno, $vtype, $id = null) { 
		
		if($id)
			return $this->manual_journal->where('voucher_no', $refno)->where('id', '!=', $id)->where('status',1)->whereNull('deleted_at')->count();
		else {
			switch($vtype) {
				case 16:
					return $this->manual_journal->where('voucher_no', $refno)->where('voucher_type', 'JV')->where('status',1)->whereNull('deleted_at')->count();
					break;
				
				 case 9:
				 	return DB::table('receipt_voucher')->where('voucher_no', $refno)->where('status',1)->whereNull('deleted_at')->count();
				 	break;
					
				 case 10:
				 	return DB::table('payment_voucher')->where('voucher_no', $refno)->where('status',1)->whereNull('deleted_at')->count();
					break;
					
				case 5:
				 	return $this->manual_journal->where('voucher_no', $refno)->where('voucher_type', 'PIN')->where('status',1)->whereNull('deleted_at')->count();
				 	break;
					
				 case 6:
				 	return $this->manual_journal->where('voucher_no', $refno)->where('voucher_type', 'SIN')->where('status',1)->whereNull('deleted_at')->count();
				 	break;
			}
		}
	}
	
	
	public function getPDCreport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		if($attributes['search_type']=="issued")
		{
			
			$ob_only = (isset($attributes['ob_only']))?true:false;
			
			$query1 = DB::table('pdc_issued')
						->join('account_master', 'account_master.id', '=', 'pdc_issued.dr_account_id')
						->join('account_master AS AM', 'AM.id', '=', 'pdc_issued.supplier_id')
						->join('payment_voucher AS PV', 'PV.id', '=', 'pdc_issued.voucher_id')
						->join('payment_voucher_entry AS PVE', function($join){
							$join->on('PVE.payment_voucher_id', '=', 'pdc_issued.voucher_id');
							$join->where('PVE.entry_type', '=', 'Cr');
						})
						->join('bank AS B', 'B.id', '=', 'PVE.bank_id');
			
			if($date_from!='' && $date_to!='') {
				$query1->whereBetween('pdc_issued.voucher_date',[$date_from, $date_to]);
			}
			
			if($attributes['account_id']!='') {
				$acid = $attributes['account_id'];
				$query1->where('pdc_issued.supplier_id', $acid)	;
			}
			
			if($attributes['status']!='') {
				$query1->where('pdc_issued.status', $attributes['status']);
			}
					
			if($ob_only) {
				$query1->where('PV.opening_balance_id', '>', 0);
			}			
				
				$result = $query1->where('pdc_issued.deleted_at','0000-00-00 00:00:00')
								->select('pdc_issued.*','account_master.master_name AS debitor','AM.master_name AS customer','PV.voucher_no',
										'PVE.cheque_no','PVE.cheque_date','B.code','PV.voucher_type AS vtype',
										DB::raw('EXTRACT(MONTH FROM pdc_issued.voucher_date) AS month'))
								->get();
			return $result;
			
			
			
		} else if($attributes['search_type']=="received") {
			
			$ob_only = (isset($attributes['ob_only']))?true:false;
			
			$query1 = DB::table('pdc_received')
						->join('account_master', 'account_master.id', '=', 'pdc_received.cr_account_id')
						->join('account_master AS AM', 'AM.id', '=', 'pdc_received.customer_id')
						->join('receipt_voucher AS RV', 'RV.id', '=', 'pdc_received.voucher_id')
						->join('receipt_voucher_entry AS RVE', function($join){
							$join->on('RVE.receipt_voucher_id', '=', 'pdc_received.voucher_id');
							$join->where('RVE.entry_type', '=', 'Dr');
						})
						->join('bank AS B', 'B.id', '=', 'RVE.bank_id');
			
			if($date_from!='' && $date_to!='') {
				$query1->whereBetween('pdc_received.voucher_date',[$date_from, $date_to]);
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
								->select('pdc_received.*','account_master.master_name AS debitor','AM.master_name AS customer','RV.voucher_no',
										'RVE.cheque_no','RVE.cheque_date','B.code','RV.voucher_type AS vtype',
										DB::raw('EXTRACT(MONTH FROM pdc_received.voucher_date) AS month'))
								->get();
						
			
				
									
			return $result;
			
		} 
	}

	public function getLastId() {
		
		return $this->manual_journal->where('status',1)
					->select('id')
					->orderBY('id', 'DESC')
					->first();

	}
}

