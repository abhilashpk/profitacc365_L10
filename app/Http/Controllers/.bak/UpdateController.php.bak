<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class UpdateController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	private function makeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->receipt_voucher_id][] = $item;
		
		return $childs;
	}
	
	public function RVmodificationFix() {
		
		$res = DB::table('receipt_voucher')
					->join('receipt_voucher_entry', 'receipt_voucher_entry.receipt_voucher_id', '=', 'receipt_voucher.id')
					->join('receipt_voucher_tr', 'receipt_voucher_tr.receipt_voucher_entry_id', '=', 'receipt_voucher_entry.id')
					->join('sales_invoice AS SI', 'SI.id', '=', 'receipt_voucher_tr.sales_invoice_id')
					->where('receipt_voucher_tr.bill_type','SI')
					//->where('receipt_voucher.id',444)
					->select('receipt_voucher_entry.receipt_voucher_id','receipt_voucher_tr.*','receipt_voucher_entry.entry_type',
					         'receipt_voucher_entry.account_id','receipt_voucher_entry.description','SI.voucher_no','receipt_voucher_entry.cheque_no',
							 'receipt_voucher_entry.cheque_date','receipt_voucher_entry.bank_id','receipt_voucher_entry.party_account_id')
					->get();
					
		//$this->update_rv_entry($this->makeArr($res));
		echo 'Successfully updated!';
		
		echo '<pre>';print_r($this->makeArr($res));exit;
		
	}
	
	private function update_rv_entry($res) {
		
		DB::beginTransaction();
		try {
			foreach($res as $rows) {
			
				foreach($rows as $row) {
				
					//remove current entry...
					DB::table('receipt_voucher_entry')->where('id',$row->receipt_voucher_entry_id)
							->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							
					//Insert new rv entry row..
					$rve_id = DB::table('receipt_voucher_entry')
								->insertGetId(['receipt_voucher_id' => $row->receipt_voucher_id,
											   'account_id' => $row->account_id,
											   'description' => $row->description,
											   'reference' =>  $row->voucher_no,
											   'amount' => $row->assign_amount,
											   'entry_type' => $row->entry_type,
												'cheque_no' => $row->cheque_no,
												'cheque_date' => $row->cheque_date,
												'bank_id' => $row->bank_id,
												'status' =>1,
												'party_account_id' => $row->party_account_id
											]);
					
					//Update account transaction old rve id with new one.
					DB::table('account_transaction')->where('voucher_type','RV')->where('voucher_type_id', $row->receipt_voucher_entry_id)
								->where('transaction_type', $row->entry_type)->where('amount', $row->assign_amount)
								->update(['voucher_type_id' => $rve_id]);
								
					//Update rv transaction table entry id with new one..
					DB::table('receipt_voucher_tr')->where('id', $row->id)->update(['receipt_voucher_entry_id' => $rve_id]);
				}
						
			}
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	
	private function makeArrPV($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->payment_voucher_id][] = $item;
		
		return $childs;
	}
	
	public function PVmodificationFix() {
		
		$res = DB::table('payment_voucher')
					->join('payment_voucher_entry', 'payment_voucher_entry.payment_voucher_id', '=', 'payment_voucher.id')
					->join('payment_voucher_tr', 'payment_voucher_tr.payment_voucher_entry_id', '=', 'payment_voucher_entry.id')
					->join('purchase_invoice AS PI', 'PI.id', '=', 'payment_voucher_tr.purchase_invoice_id')
					->where('payment_voucher_tr.bill_type','PI')
					//->where('payment_voucher.id',444)
					->select('payment_voucher_entry.payment_voucher_id','payment_voucher_tr.*','payment_voucher_entry.entry_type',
					         'payment_voucher_entry.account_id','payment_voucher_entry.description','PI.voucher_no','payment_voucher_entry.cheque_no',
							 'payment_voucher_entry.cheque_date','payment_voucher_entry.bank_id','payment_voucher_entry.party_account_id')
					->get();
					
		$this->update_pv_entry($this->makeArrPV($res));
		echo 'Successfully updated!';
		
		//echo '<pre>';print_r($this->makeArrPV($res));exit;
		
	}
	
	private function update_pv_entry($res) {
		
		DB::beginTransaction();
		try {
			foreach($res as $rows) {
			
				foreach($rows as $row) {
				
					//remove current entry...
					DB::table('payment_voucher_entry')->where('id',$row->payment_voucher_entry_id)
							->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							
					//Insert new rv entry row..
					$pve_id = DB::table('payment_voucher_entry')
								->insertGetId(['payment_voucher_id' => $row->payment_voucher_id,
											   'account_id' => $row->account_id,
											   'description' => $row->description,
											   'reference' =>  $row->voucher_no,
											   'amount' => $row->assign_amount,
											   'entry_type' => $row->entry_type,
												'cheque_no' => $row->cheque_no,
												'cheque_date' => $row->cheque_date,
												'bank_id' => $row->bank_id,
												'status' =>1,
												'party_account_id' => $row->party_account_id
											]);
					
					//Update account transaction old rve id with new one.
					DB::table('account_transaction')->where('voucher_type','PV')->where('voucher_type_id', $row->payment_voucher_entry_id)
								->where('transaction_type', $row->entry_type)->where('amount', $row->assign_amount)
								->update(['voucher_type_id' => $pve_id]);
								
					//Update rv transaction table entry id with new one..
					DB::table('payment_voucher_tr')->where('id', $row->id)->update(['payment_voucher_entry_id' => $pve_id]);
				}
						
			}
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
}
