<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Journal\JournalInterface;
use Input;
use Session;
use Response;
use DB;
use App;
use Config;
use Auth;


class YearendingquickController extends Controller
{
	protected $accountmaster;
	protected $receipt_voucher;
	protected $journal;
	protected $dbcon;
	
	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster, ReceiptVoucherInterface $receipt_voucher,PaymentVoucherInterface $payment_voucher,JournalInterface $journal) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
		$this->journal = $journal;
		$this->middleware('auth');
		
		$this->dbcon = Config::get('database.connections');
	}
	
	public function index() {
		$data = array(); 

		
	
	DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
			
		
		
		$result = $this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility('CB') ) );
			
			
		
		//print_r($this->acsettings);exit;
		
		return view('body.yearendingquick.index')
					->withDate($this->acsettings)
					->withData($data);
	}
	
	public function backup()
	{ 
	    
	    //echo '<pre>';print_r(Input::all());exit;
		DB::table('parameter1')->where('id',1)
							   ->update(['from_date' => date('Y-m-d',strtotime(Input::get('nw_from_date'))),
										 'to_date' => date('Y-m-d',strtotime(Input::get('nw_to_date'))),
										 'py_from_date' => date('Y-m-d',strtotime(Input::get('from_date'))),
										 'py_to_date' => date('Y-m-d',strtotime(Input::get('to_date'))),
										 ]);//echo '<pre>';print_r(Input::all());exit;
		//$this->backupDatabase();
		return redirect('year_endingquick/step2');  //step1
	}
	
public function step2()
	{
		$data = array(); 
		
		################ ITEMS QUANTITY OPENING ENTRY ####################
		$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		foreach($items as $item) {
			$itemlog = DB::table('item_log')
									  ->where('item_id', $item->itemmaster_id)
									  ->where('status',1)
									  ->where('deleted_at','0000-00-00 00:00:00')
									  ->whereBetween('voucher_date', [$this->acsettings->py_from_date, $this->acsettings->py_to_date])
									  ->select('item_log.*')
									  ->orderBy('id','DESC')
									  ->first();
				
				$qty_rec = $qty_isd = $curr_qnty = 0;
				$qntys = $this->getItemQtyFromLog($item->itemmaster_id);
				
				if($qntys) {
					$qty_rec = $qntys['in'];
					$qty_isd = $qntys['out'];
					$curr_qnty = $qty_rec - $qty_isd;
				}
				//echo $curr_qnty.'<pre>';print_r($qntys);exit;	
				$cost_avg = ($itemlog)?$itemlog->cost_avg:0;
				$arr[$item->itemmaster_id] = $cost_avg;
				DB::table('item_log')->insert(['document_type' => 'OQ',
												'document_id'  => 0,
												'item_id' 	   => $item->itemmaster_id,
												'unit_id'		=> $item->unit_id,
												'quantity'		=> $curr_qnty,
												'trtype'		=> 1,
												'cur_quantity'	=> $curr_qnty,
												'cost_avg'		=> $cost_avg,
												'packing'		=> 1,
												'status'		=> 1,
												'created_at'	=> date('Y-m-d H:i:s'),
												'voucher_date'	=> $this->acsettings->from_date
											 ]);

				Db::table('item_unit')->where('itemmaster_id',$item->itemmaster_id)->where('is_baseqty',1)
								->update(['opn_quantity' => $curr_qnty, 'cur_quantity' => $curr_qnty, 'received_qty' => $curr_qnty,'cost_avg' => $cost_avg]);
		}
			//echo '<pre>';print_r($arr);exit;										 
		return view('body.yearendingquick.step2')
					->withData($data);
	}
	
		public function step2Submit()
	{
		$data = array(); 
		
		$attributes['date_from'] = $this->acsettings->py_from_date;
		$attributes['date_to'] = $this->acsettings->py_to_date;
		$attributes['curr_from_date'] = $this->acsettings->from_date;
		
		//-----------Calculate Profit of previous FY and update to Retained profit account..........
		$result = $this->accountmaster->getIncomeExpense($attributes); 
		
		echo '<pre>';print_r($result);exit;
	//	echo '<pre>';print_r(Input::all());exit;
		$directexp_tot=$indirectexp_tot=$indirectinc_tot=$directinc_tot=0;
		if(count($result['expense'][0]) > 0 ) {
		    $directexp_tot=$result['expense'][0]['total'];
		}
		
		if(count($result['expense'][1]) > 0 ) {
		    $indirectexp_tot=$result['expense'][1]['total'];
		}
		if(count($result['income'][0]) > 0 ) {
		    $directinc_tot=$result['income'][0]['total']*-1;
		}
		if(count($result['income'][1]) > 0 ) {
		    $indirectinc_tot=$result['income'][1]['total']*-1;
		}
		$total_expense=$directexp_tot+$indirectexp_tot;
		$total_income=$debit=$directinc_tot+$indirectinc_tot;
		$retailed_profit=$total_income-$total_expense;
		$credit=$total_expense+$retailed_profit;
		$ref=date('Y', strtotime($attributes['date_from']));
		$acname=[$result['expense'][0]['name'],$result['income'][0]['name'],Input::get('account_name')];
		$acid=[5634,15,Input::get('account_id')];
		$grpid=['','',''];
		 $vat=['','',''];  
		 $sid=['','',''];  
		$btype=['','',''];
		$invid=['','',''];
		$actualamt=['','',''];
		$jobid=['','',''];
		$jobcod=['','',''];
		$bankid=['','',''];
		$chequeno=['','',''];
		$chequedate=['','',''];
		$partyid=['','',''];
		$partyname=['','',''];
		$description=['Expense','Income','Retail Profit'];
		$reference=[$ref,$ref,$ref];
		$actype=['Cr','Dr','Cr'];
		$lineamt=[$total_expense,$total_income,$retailed_profit];
		//echo '<pre>';print_r($lineamt);exit;
		$vt = DB::table('account_setting')->where('voucher_type_id',16)->where('deleted_at','0000-00-00 00:00:00')->select('id','voucher_name','voucher_no')->first();
		Input::merge(['from_jv' => 1]);
		Input::merge(['status' => 1]);
		Input::merge(['voucher_type' => 16]);
		Input::merge(['voucher' => $vt->id]);
		Input::merge(['vno' => $vt->voucher_no]);
		Input::merge(['voucher_no' => $vt->voucher_no]);
		Input::merge(['voucher_date' => date('Y-m-d')]);
		Input::merge(['curno' => '']);
		Input::merge(['prefix' => '']);
		Input::merge(['is_prefix' => 0]);
		Input::merge(['chktype' =>'']);
		Input::merge(['is_onaccount' => 1]);
		Input::merge(['account_name' => $acname]);
		Input::merge(['account_id' => $acid]);
		Input::merge(['group_id' => $grpid]);
		Input::merge(['vatamt' => $vat]);
		Input::merge(['sales_invoice_id' => $sid]);
		Input::merge(['bill_type' => $btype]);
		Input::merge(['description' => $description]);
		Input::merge(['reference' => $reference]);
		Input::merge(['inv_id' => $invid]);
		Input::merge(['actual_amount' => $actualamt]);
		Input::merge(['account_type' => $actype]);
		Input::merge(['line_amount' => $lineamt]);
		Input::merge(['job_id' => $jobid]);
		Input::merge(['jobcod' => $jobcod]);
		Input::merge(['bank_id' => $bankid]);
		Input::merge(['cheque_no' => $chequeno]);
		Input::merge(['cheque_date' => $chequedate]);
		Input::merge(['partyac_id' => $partyid]);
		Input::merge(['party_name' => $partyname]);
		Input::merge(['supplier_name' => '']);
		Input::merge(['trn_no' => '']);
		Input::merge(['jvtype' => '']);
		Input::merge(['rcperiod' => '']);
		Input::merge(['debit' => $debit]);
		Input::merge(['credit' => $credit]);
		Input::merge(['difference' => 0]);
		//echo '<pre>';print_r(Input::all());exit;
		$id=$this->journal->create(Input::all());
		if($id){
		    Session::flash('message', 'Journal voucher added successfully.');
		    return redirect('journal/edit/'.$id);
		}
			else {
				Session::flash('error', 'Something went wrong, Journal voucher failed to add!');
				return redirect('year_endingquick/step2');
			}
		
	
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	protected function makeSummaryAc($results)
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
				$this->accountmaster->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}

		private function getItemQtyFromLog($item_id)
	{
		$qtyin = DB::table('item_log')->where('item_id', $item_id)->where('trtype',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->whereBetween('voucher_date', [$this->acsettings->py_from_date, $this->acsettings->py_to_date])->sum('quantity');
		
		$qtyout = DB::table('item_log')->where('item_id', $item_id)->where('trtype',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->whereBetween('voucher_date', [$this->acsettings->py_from_date, $this->acsettings->py_to_date])->sum('quantity');
		
		return ['in' => $qtyin, 'out' => $qtyout];
	}
	
	
}

