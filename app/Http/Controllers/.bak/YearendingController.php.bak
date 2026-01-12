<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use Input;
use Session;
use Response;
use DB;
use App;
use Config;
use Auth;


class YearendingController extends Controller
{
	protected $accountmaster;
	protected $receipt_voucher;
	protected $dbcon;
	
	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster, ReceiptVoucherInterface $receipt_voucher,PaymentVoucherInterface $payment_voucher) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
		$this->middleware('auth');
		
		$this->dbcon = Config::get('database.connections');
	}
	
	public function index() {
		$data = array(); 

		
		//$pdcrs = $this->makePDCRBill( $this->receipt_voucher->PDCReceivedList($this->acsettings) );echo '<pre>';print_r($pdcrs);exit;
		
		//echo '<pre>';print_r($this->acsettings);exit;
//$arresult = $this->outStandingBills();
//echo '<pre>';print_r($arresult);exit;
			
		//Update Closing Balance......... 
		//REMOVE duplicate entries...
		DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
		
		
		
		
		if( $result = $this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility('CB') ) )) {
			
			//Update Stock...	
			$result = $this->makeSummaryStock( $this->itemmaster->updateUtility() );
			//QUICK UPDATE ITEM STOCK ....
			$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			foreach($items as $item) {
				
				$itemlog = DB::table('item_log')
								  ->where('item_id', $item->itemmaster_id)
								  ->where('status',1)
								  ->where('deleted_at','0000-00-00 00:00:00')
								  ->select('item_log.*')
								  ->orderBy('id','DESC')
								  ->first();
				if($itemlog) {
					
					$qty_rec = $qty_isd = $curr_qnty = 0;
					$qntys = $this->getItemQtyFromLog($item->itemmaster_id);
					if($qntys) {
						$qty_rec = $qntys['in'];
						$qty_isd = $qntys['out'];
						$curr_qnty = $qty_rec - $qty_isd;
					}
						
					if($itemlog->document_type=='SI'||$itemlog->document_type=='PR'||$itemlog->document_type=='GR'||$itemlog->document_type=='TO') {
						
						DB::table('item_unit')->where('id', $item->id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'received_qty' => $qty_rec,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg
											  ]);
											  
					} else if($itemlog->document_type=='PI'||$itemlog->document_type=='SR'||$itemlog->document_type=='GI'||$itemlog->document_type=='TI') { 
						DB::table('item_unit')->where('id', $item->id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg,
												'issued_qty' => $qty_isd
											  ]);
					}
				} 
								  
			}
		} //print_r($this->acsettings);exit;
		
		return view('body.yearending.index')
					->withDate($this->acsettings)
					->withData($data);
	}
	
	public function backup()
	{ 
		DB::table('parameter1')->where('id',1)
							   ->update(['from_date' => date('Y-m-d',strtotime(Input::get('nw_from_date'))),
										 'to_date' => date('Y-m-d',strtotime(Input::get('nw_to_date'))),
										 'py_from_date' => date('Y-m-d',strtotime(Input::get('from_date'))),
										 'py_to_date' => date('Y-m-d',strtotime(Input::get('to_date'))),
										 ]);//echo '<pre>';print_r(Input::all());exit;
		//$this->backupDatabase();
		return redirect('year_ending/step2');  //step1
	}
	
	public function step1()
	{
		$data = array();
		return view('body.yearending.step1')
					->withDate($this->acsettings)
					->withData($data);
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
		return view('body.yearending.step2')
					->withData($data);
	}
	
	public function step2Submit()
	{
		$data = array(); 
		
		$attributes['date_from'] = $this->acsettings->py_from_date;
		$attributes['date_to'] = $this->acsettings->py_to_date;
		$attributes['search_type']='summary';
		$attributes['curr_from_date'] = $this->acsettings->from_date;
		
		//-----------Calculate Profit of previous FY and update to Retained profit account..........
		$result = $this->accountmaster->getProfitLoss($attributes); 
		if(count($result['income'][0]) > 0 && count($result['expense'][0]) > 0) {
			$grossprofit = $result['income'][0]['total'] - $result['expense'][0]['total'];
		} else if(count($result['income'][0]) > 0 && count($result['expense'][0]) == 0) {
			$grossprofit = $result['income'][0]['total'];
		} else if(count($result['income'][0]) == 0 && count($result['expense'][0]) > 0) {
			$grossprofit = 0 - $result['expense'][0]['total'];
		} else {
			$grossprofit = null;
		}
		
		if(sizeof($result['expense'][1]) > 0) {
			if($grossprofit > 0) {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
			} else {
				$netprofit = $grossprofit - $result['expense'][1]['total'];
			}
		} else {
			$netprofit = $grossprofit;
		}
			
		//if indirect income is there...
		if(count($result['income'][1]) > 0) {  //sizeof
			$netprofit = $netprofit + $result['income'][1]['total'];//$netprofit + $subtotal;
		} 
		
		
		
		//Update to Retained profit account....
		DB::table('account_master')->where('id',Input::get('account_id'))->update(['cl_balance' => $netprofit,'op_balance' => $netprofit]);
		
		DB::table('account_transaction')->where('voucher_type','OB')->where('account_master_id',Input::get('account_id'))->update(['amount' => $netprofit]);
		
		return redirect('year_ending/step3/'.Input::get('account_id'));
		
		
	}
	
	public function step3($id)
	{
		$data = array(); 
		
		return view('body.yearending.step3')
					->withPid($id);
	}
	
	public function step3Submit(Request $request)
	{
		$data = array();
		
		DB::beginTransaction();
		try {
			
			$arresult = $this->outStandingBills();
			//echo '<pre>';print_r($arresult);exit;
			//Read all Customer's bill dues...........
			$sales = $arresult['sales'];
										  
			//Opening balance detail entry as pending bills.......
			foreach($sales as $sale) {
				$total_amount = 0;
				
					//customerwise looping...
					foreach($sale as $row) {

						if($row->voucher_no!='OB') {
						
							$dat = explode('-', $this->acsettings->py_from_date); //$row->voucher_date 2024-01-01  01-01-2024
							$openingBalanceId = DB::table('opening_balance_tr')->insertGetId([
																'tr_type' => $row->tr_type,
																'tr_date' => $this->acsettings->from_date, //date('Y-m-d'),//$row->voucher_date,
																'reference_no' => $row->voucher_no.'/'.$dat[0],
																'description' => 'SALES',
																'amount' 	  => $row->balance_amount,
																'account_master_id' => $row->owner_id,
																'status' => 1,
																'fc_amount' => $row->balance_amount
															]);
															
															
							//Opening balance detail entry in transaction....
							DB::table('account_transaction')->insert(['voucher_type' => 'OBD',
																	'voucher_type_id' => $row->owner_id,
																	'account_master_id' => $row->owner_id,
																	'transaction_type' => $row->tr_type,
																	'amount'	=> $row->balance_amount,
																	'status' => 1,
																	'created_at' => date('Y-m-d H:i:s'),
																	'created_by' => 1,
																	'reference'	=> $row->voucher_no.'/'.$dat[0],
																	'invoice_date' => $this->acsettings->from_date
																	]);
																	
							//Delete the transfer invoice...
							DB::table('sales_invoice')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						}

						if($row->tr_type=='Cr') {
							$bamount = ($row->balance_amount > 0)?($row->balance_amount * -1):$row->balance_amount;
						} else {
							$bamount = $row->balance_amount;
						}

						$customer_id = $row->owner_id;
						$total_amount += $bamount;
						$voucher_date = $row->voucher_date;
						$reference = $row->voucher_no.'/'.$dat[0];
						$tr_type = ($total_amount > 0)?'Dr':'Cr';
					}
				
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
														  'voucher_type_id' => $customer_id,
														  'account_master_id' => $customer_id,
														  'transaction_type' => $tr_type,
														  'amount'	=> $total_amount,
														  'status' => 1,
														  'created_at' => date('Y-m-d H:i:s'),
														  'created_by' => 1,
														  'reference' => $reference, 
														  'invoice_date' => $this->acsettings->from_date //date('Y-m-d')
														 ]);
														 
				//Update Opening balance of each customers.....
				DB::table('account_master')->where('id',$customer_id)->update(['op_balance' => $total_amount]);
													 
			}
			
			
			//Read all Supplier's bill dues...........
			$prchs = $arresult['purchase'];
							  
			//Opening balance detail entry as pending bills.......
			 foreach($prchs as $prch) {
				$total_amount = 0;
				
				//supplierwise looping...
				foreach($prch as $row) {
					if($row->voucher_no!='OB') {
						$dat = explode('-',$this->acsettings->py_from_date ); //$row->voucher_date
						DB::table('opening_balance_tr')->insert([
															'tr_type' => $row->tr_type,
															'tr_date' => $this->acsettings->from_date, //$row->voucher_date,
															'reference_no' => $row->voucher_no.'/'.$dat[0],
															'description' => 'PURCHASE',
															'amount' 	  => $row->balance_amount,
															'account_master_id' => $row->owner_id,
															'status' => 1,
															'fc_amount' => $row->balance_amount
														]);
														
						//Opening balance detail entry in transaction....
						DB::table('account_transaction')->insert(['voucher_type' => 'OBD',
																'voucher_type_id' => $row->owner_id,
																'account_master_id' => $row->owner_id,
																'transaction_type' => $row->tr_type,
																'amount'	=> $row->balance_amount,
																'status' => 1,
																'created_at' => date('Y-m-d H:i:s'),
																'created_by' => 1,
																'reference'	=> $row->voucher_no.'/'.$dat[0],
																'invoice_date' => $this->acsettings->from_date //date('Y-m-d')
																]);
													
						//Delete the transfer invoice...
						DB::table('purchase_invoice')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					}
					
					if($row->tr_type=='Cr') {
						$bamount = ($row->balance_amount > 0)?($row->balance_amount * -1):$row->balance_amount;
					} else {
						$bamount = $row->balance_amount;
					}

					$supplier_id = $row->owner_id;
					$total_amount += $bamount;
					$voucher_date = $row->voucher_date;
					$reference = $row->voucher_no.'/'.$dat[0];
					$tr_type = ($total_amount > 0)?'Dr':'Cr';
					//$tr_type = $row->tr_type;
				}
				
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
														  'voucher_type_id' => $supplier_id,
														  'account_master_id' => $supplier_id,
														  'transaction_type' => $tr_type,
														  'amount'	=> ($total_amount * -1),
														  'status' => 1,
														  'created_at' => date('Y-m-d H:i:s'),
														  'created_by' => 1,
														  'reference' => $reference, 
														  'invoice_date' => $this->acsettings->from_date //date('Y-m-d')
														 ]);
														 
				//Update Opening balance of each suppliers.....
				DB::table('account_master')->where('id',$supplier_id)->update(['op_balance' => ($total_amount * -1)]);
														 
			}
			
			//Read all PDCR uncleared cheques........... frmaccount_id
			$pdcrs = $this->makePDCRBill( $this->receipt_voucher->PDCReceivedList($this->acsettings) );//echo '<pre>';print_r($pdcrs);exit;
			if(count($pdcrs) > 0) {
				$total_amount = 0;
				foreach($pdcrs as $pdcr) {
					  
					  //customerwise looping...
					  foreach($pdcr as $row) {
						$dat = explode('-',$this->acsettings->py_from_date);
														
						$openingBalanceId = DB::table('opening_balance_tr')->insertGetId([
															'tr_type' => 'Dr',
															'tr_date' => $this->acsettings->from_date,
															'reference_no' => $row->voucher_no.'/'.$dat[0],
															'description' => 'PDCR',
															'amount' 	  => $row->amount,
															'account_master_id' => $row->pdcr_id,
															'cheque_no' => $row->cheque_no,
															'cheque_date' => $row->cheque_date,
															'bank_id' => $row->bank_id,
															'status' => 1,
															'frmaccount_id' => $row->customer_id,
															'fc_amount' => $row->amount
														]);
						
						//--------------------
						//get Voucher no...
						$res = DB::table('account_setting')->where('voucher_type_id', 9)->where('status',1)->select('id','voucher_type_id','voucher_no')->first();//print_r($res);exit;
						
						$receipt_voucher_id = DB::table('receipt_voucher')
												->insertGetId([ 'voucher_type' => 'PDCR',
																'voucher_id'		=> $res->id,
																'voucher_no'		=> $res->voucher_no+1,
																'voucher_date'		=> $this->acsettings->from_date,
																'from_jv'			=> 0,
																'debit'				=> $row->amount,
																'credit'			=> $row->amount,
																'status'			=> 1,
																'created_at' 		=> date('Y-m-d H:i:s'),
																//'created_by' 		=> 1,
																'opening_balance_id' => $openingBalanceId
																]);
														
						DB::table('receipt_voucher_entry')
										->insert([ 'receipt_voucher_id'		=> $receipt_voucher_id,
												   'account_id'				=> $row->pdcr_id, //$acrow->id,
												   'description'			=> 'PDCR '.$row->voucher_no.'/'.$dat[0],
												   'reference'				=> $res->voucher_no+1,
												   'amount'					=> $row->amount,
												   'entry_type'				=> 'Dr',
												   'cheque_no'				=> $row->cheque_no,
												   'cheque_date'			=> $row->cheque_date,
												   'bank_id'				=> $row->bank_id,
												   'status'					=> 1,
												   'party_account_id' 		=> $row->customer_id
												]);
														
						DB::table('receipt_voucher_entry')
										->insert([ 'receipt_voucher_id'		=> $receipt_voucher_id,
												   'account_id'				=> $row->pdcr_id,
												   'description'			=> 'PDCR '.$row->voucher_no.'/'.$dat[0],
												   'reference'				=> $res->voucher_no+1,
												   'amount'					=> $row->amount,
												   'entry_type'				=> 'Cr',
												   'cheque_no'				=> $row->cheque_no,
												   'cheque_date'			=> $row->cheque_date,
												   'bank_id'				=> $row->bank_id,
												   'status'					=> 1,
												   'party_account_id' 		=> $row->customer_id
												]);
														
						
						//PDCR table inserting...
						$acrow = DB::table('account_master')->where('status',1)->where('category','BANK')->select('id')->first();
						
						DB::table('pdc_received')
								->insert([ 'voucher_id' 	=> $receipt_voucher_id,
											'voucher_type'   => 'DB',
											'dr_account_id' => $acrow->id,
											'cr_account_id' => $row->pdcr_id,
											'reference'  => $row->voucher_no.'/'.$dat[0],
											'amount'   			=> $row->amount,
											'status' 			=> 0,
											'created_at' 		=> date('Y-m-d H:i:s'),
											//'created_by' 		=> Auth::User()->id,
											'voucher_date'		=> $this->acsettings->from_date,
											'customer_id' => $row->customer_id,
											'cheque_no' => $row->cheque_no,
											'cheque_date' => $row->cheque_date,
											'voucher_no' => $res->voucher_no+1
											//'description' => $attributes['customer_account']
										]);
					
														
								//update voucher no........
								 DB::table('account_setting')
										->where('id', $res->id)
										->update(['voucher_no' => DB::raw('voucher_no + 1')]);
					//-------------------
				
				
						//Opening balance detail entry in transaction....
						DB::table('account_transaction')->insert(['voucher_type' => 'OBD',
																  'voucher_type_id' => $openingBalanceId,
																  'account_master_id' => $row->pdcr_id,
																  'transaction_type' => 'Dr',
																  'amount'	=> $row->amount,
																  'status' => 1,
																  'created_at' => date('Y-m-d H:i:s'),
																  'created_by' => 1,
																  'description'	=> 'PDCR '.$row->voucher_no.'/'.$dat[0],
																  'reference'	=> $row->voucher_no.'/'.$dat[0],
																  'invoice_date' => $this->acsettings->from_date
																 ]);
														
						$total_amount += $row->amount;
						$customer_id = $row->customer_id;
						$reference = $row->voucher_no.'/'.$dat[0];
						
						//Delete the transfer invoice...
						if($row->type=='RV')
							DB::table('receipt_voucher')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						else if($row->type=='JV')
							DB::table('journal')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					  }
															 
				}
				
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
															  'voucher_type_id' => $row->pdcr_id,
															  'account_master_id' => $row->pdcr_id,
															  'transaction_type' => 'Dr',
															  'amount'	=> $total_amount,
															  'status' => 1,
															  'created_at' => date('Y-m-d H:i:s'),
															  'created_by' => 1,
															  'description'	=> 'PDCR Opening',
															  'reference' => $reference,
															  'invoice_date' => $this->acsettings->from_date
															 ]);
				//Update Opening balance of PDCR.....
				DB::table('account_master')->where('id',$row->pdcr_id)->update(['op_balance' => $total_amount]);
			}
			 
			 
			//Read all PDCI uncleared cheques...........
			$pdcis = $this->makePDCIBill( $this->payment_voucher->PDCIssuedList($this->acsettings) ); //echo '<pre>';print_r($pdcis);exit;
			if(count($pdcis) > 0) {
				$total_amount = 0; $pdci_id = '';
				foreach($pdcis as $pdci) {
					 
					  foreach($pdci as $row) {
						$pdci_id = $row->pdci_id;
						$dat = explode('-',$this->acsettings->py_from_date);
						$openingBalanceid = DB::table('opening_balance_tr')->insertGetId([
															'tr_type' => 'Cr',
															'tr_date' => $this->acsettings->from_date,
															'reference_no' => $row->voucher_no.'/'.$dat[0],
															'description' => 'PDCI',
															'amount' 	  => $row->amount,
															'account_master_id' => $row->pdci_id,
															'cheque_no' => $row->cheque_no,
															'cheque_date' => $row->cheque_date,
															'bank_id' => $row->bank_id,
															'status' => 1,
															'frmaccount_id' => $row->supplier_id,
															'fc_amount' => $row->amount
														]);
						
					
						
						//--------------------
						$res = DB::table('account_setting')->where('voucher_type_id', 10)->where('status',1)->select('id','voucher_type_id','voucher_no')->first();
											
						$payment_voucher_id = DB::table('payment_voucher')
														->insertGetId([ 'voucher_type' => 'PDCI',
																		'voucher_id'		=> $res->id,
																		'voucher_no'		=> $res->voucher_no+1,
																		'voucher_date'		=> $this->acsettings->from_date,
																		'from_jv'			=> 0,
																		'debit'				=> $row->amount,
																		'credit'			=> $row->amount,
																		'status'			=> 1,
																		'created_at' 		=> date('Y-m-d H:i:s'),
																		'created_by' 		=> 1,
																		'opening_balance_id' => $openingBalanceid]);
																		
								DB::table('payment_voucher_entry')
												->insert([ 'payment_voucher_id'		=> $payment_voucher_id,
														   'account_id'				=> $row->pdci_id,
														   'description'			=> 'PDCI '.$row->voucher_no.'/'.$dat[0],
														   'reference'				=> $res->voucher_no+1,
														   'amount'					=> $row->amount,
														   'entry_type'				=> 'Cr',
														   'cheque_no'				=> $row->cheque_no,
														   'cheque_date'			=> $row->cheque_date,
														   'bank_id'				=> $row->bank_id,
														   'status'					=> 1,
														   'party_account_id' 		=> $row->supplier_id
														]);
														
								DB::table('payment_voucher_entry')
												->insert([ 'payment_voucher_id'		=> $payment_voucher_id,
														   'account_id'				=> $row->supplier_id,
														   'description'			=> 'PDCI '.$row->voucher_no.'/'.$dat[0],
														   'reference'				=> $res->voucher_no+1,
														   'amount'					=> $row->amount,
														   'entry_type'				=> 'Dr',
														   'cheque_no'				=> $row->cheque_no,
														   'cheque_date'			=> $row->cheque_date,
														   'bank_id'				=> $row->bank_id,
														   'status'					=> 1,
														   'party_account_id' 		=> $row->supplier_id
														]);
														
								//PDCI table inserting...
								$acrow = DB::table('account_master')->where('status',1)->where('category','BANK')->select('id')->first();
								
								DB::table('pdc_issued')
										->insert([ 'voucher_id' 	=> $payment_voucher_id,
													'voucher_type'   => 'CB',
													'cr_account_id' => $acrow->id,
													'dr_account_id' => $row->pdci_id,
													'reference'  => $res->voucher_no+1,
													'amount'   			=> $row->amount,
													'status' 			=> 0,
													'created_at' 		=> date('Y-m-d H:i:s'),
													//'created_by' 		=> Auth::User()->id,
													'voucher_date'		=> $this->acsettings->from_date,
													'supplier_id' => $row->supplier_id,
													'cheque_no' => $row->cheque_no,
													'cheque_date' => $row->cheque_date,
													'voucher_no' => $res->voucher_no+1
													//'description' => $attributes['customer_account']
												]);
												
														
								//update voucher no........
								 DB::table('account_setting')
										->where('id', $res->id)
										->update(['voucher_no' => DB::raw('voucher_no + 1')]);
									
							 
					//-------------------
						
						
						//Opening balance detail entry in transaction....
						DB::table('account_transaction')->insert(['voucher_type' => 'OBD',
																  'voucher_type_id' => $openingBalanceid,
																  'account_master_id' => $row->pdci_id,
																  'transaction_type' => 'Cr',
																  'amount'	=> $row->amount,
																  'status' => 1,
																  'created_at' => date('Y-m-d H:i:s'),
																  'created_by' => 1,
																  'description'	=> 'PDCI '.$row->voucher_no.'/'.$dat[0],
																  'reference'	=> $row->voucher_no.'/'.$dat[0],
																  'invoice_date' => $this->acsettings->from_date
																 ]);
						
						$total_amount += $row->amount;
						$creditor_id = $row->supplier_id;
						$reference = $row->voucher_no.'/'.$dat[2];
						
						//Delete the transfer invoice...
						if($row->type=='PV')
							DB::table('payment_voucher')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						else if($row->type=='JV')
							DB::table('journal')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						else if($row->type=='PC')
							DB::table('payment_voucher')->where('id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					  }
															 
				}
				$total_amount = ($total_amount < 0)?($total_amount * -1):$total_amount;
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
															  'voucher_type_id' => $row->pdci_id,
															  'account_master_id' => $row->pdci_id,
															  'transaction_type' => 'Cr',
															  'amount'	=> $total_amount,
															  'status' => 1,
															  'created_at' => date('Y-m-d H:i:s'),
															  'created_by' => 1,
															  'description'	=> 'PDCI Openingx',
															  'reference' => $reference,
															  'invoice_date' => $this->acsettings->from_date
															 ]);
															 
				DB::table('account_master')->where('id',$row->pdci_id)->update(['op_balance' => $total_amount]); //creditor_id
			}
			
			
			//Read Assets,Liability,Equity accounts and carry forward its balance.......................
			$acbal = DB::table('account_master')
						->join('account_category AS AC', function($join) {
							$join->on('AC.id','=','account_master.account_category_id')
								 ->whereIn('AC.parent_id', [1,2,3]);
						})
						->whereNotIn('account_master.category',['CUSTOMER','SUPPLIER','PDCR','PDCI'])
						->where('account_master.id','!=',$request->get('pid'))
						->select('account_master.id','account_master.master_name','AC.name','account_master.cl_balance','account_master.op_balance')
						->get();
			
			foreach($acbal as $row) {
				DB::table('account_master')->where('id', $row->id)->update(['cl_balance' => $row->cl_balance, 'op_balance' => $row->cl_balance]);
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
														  'voucher_type_id' => $row->id,
														  'account_master_id' => $row->id,
														  'transaction_type' => ($row->cl_balance < 0)?'Cr':'Dr',
														  'amount'	=> $row->cl_balance,
														  'status' => 1,
														  'created_at' => date('Y-m-d H:i:s'),
														  'created_by' => 1,
														  //'reference' => '',
														  'invoice_date' => $this->acsettings->from_date
														 ]);
			}
			//---------------------------------------
			
			//Read Income, Expense accounts and clear its balance.......................
			$acbal = DB::table('account_master')
						->join('account_category AS AC', function($join) {
							$join->on('AC.id','=','account_master.account_category_id')
								 ->whereIn('AC.parent_id', [4,5,6,7]);
						})
						->select('account_master.id','account_master.master_name','AC.name','account_master.cl_balance','account_master.op_balance')
						->get();
						
			 foreach($acbal as $row) {
				DB::table('account_master')->where('id', $row->id)->update(['cl_balance' => 0, 'op_balance' => 0]);
				DB::table('account_transaction')->insert(['voucher_type' => 'OB',
															  'voucher_type_id' => $row->id,
															  'account_master_id' => $row->id,
															  'transaction_type' => 'Dr',
															  'amount'	=> 0,
															  'status' => 1,
															  'created_at' => date('Y-m-d H:i:s'),
															  'created_by' => 1,
															  //'reference' => '',
															  'invoice_date' => $this->acsettings->from_date
															 ]);
			 }
			 //--------------------------------------------------
						
			
			DB::commit();
			return redirect('year_ending/step4');
			
		} catch(\Exception $e) { 
			
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			return false;
		}
		 
		 //echo '<pre>';print_r($pdcrs);exit;
		
	}
	
	public function step4()
	{
		$data = array(); 
		//exit;
		//Update Closing Balance based on FY......... 
		$this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility('FY', $this->acsettings)),'FY');
		//$ar=$this->makeTree( $this->accountmaster->updateUtility('FY', $this->acsettings));
		//exit;
		return view('body.yearending.step4')
					->withData($data);
	}
	
	
	protected function makeTreeBill($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->owner_id][] = $item;

		return $childs;
	}
	
	protected function makePDCRBill($result)
	{
		$childs = array();
		foreach($result as $item) {
			if($item->cheque_no!='' && $item->code!='')
				$childs[$item->customer_id][] = $item;
		}
		return $childs;
	}
	
	protected function makePDCIBill($result)
	{
		$childs = array();
		foreach($result as $item) {
			if($item->cheque_no!='' && $item->code!='')
				$childs[$item->creditor_id][] = $item;
		}
		return $childs;
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
	
	protected function makeSummaryStock($result)
	{
		foreach($result as $row) {
				
			$res = DB::table('item_log')
						->where('item_id',$row->item_id)
						->where('unit_id', $row->unit_id)
						->where('status',1)
						->where('deleted_at','0000-00-00 00:00:00')
						->where('document_type','PI')
						->where('id','>',$row->id)
						->first(); //echo '<pre>';print_r($res);exit;
						
			if($res) {
				DB::table('item_log')
						->where('id',$row->id)
						->update(['cost_avg' => $res->pur_cost, 'sale_cost' => $res->pur_cost]);
			}
							
		}
				
		return true;
	}
	
	
	public function step4Submit()
	{
		 $data = array();
		 $ar = $this->truncateTable($this->acsettings);//echo '<pre>';print_r($ar);exit;
		 $status = $this->updateItemStock();

		 Session::flash('message', 'Year ending  process successfully completed.');
		 return view('body.yearending.finish')
					->withData($data);
	}
	
	
	protected function truncateTable($date)
	{
		$excepts = ['account_category','account_group','account_master','account_setting','area','bank','category','cheque','company',
					'country','currency','department','design_view','document_master','doc_department',
					'employee','expiry_docs','forms','form_details','groupcat','header_footer','item_unit','itemmaster','item_location',
					'jobmaster','jobtype','location','onleave','migrations','other_account_setting','parameter1','parameter2','parameter3',
					'parameter4','password_resets','permissions','permission_role','resign','roles','role_user','salesman','terms','units',
					'users','vat_master','vehicle','voucher_account','voucher_no','voucher_type','wage_entry','wage_entry_items',
					'wage_entry_job','wage_entry_others','purchase_invoice_item','report_view','report_view_detail','sales_invoice_item',
					'credit_note_entry','customer_do_item','debit_note_entry','goods_issued_item','goods_return_item','journal_entry',
					'payment_voucher_entry','petty_cash_entry','pi_other_cost','purchase_order_item','po_other_cost','purchase_return_item',
					'quotation_sales_item','receipt_voucher_entry','sales_order_item','sales_return_item','receipt_voucher_tr','payment_voucher_tr'];
					
		$tables = DB::connection()->getPdo()->query("SHOW FULL TABLES")->fetchAll();
		$tableNames = [];

		$keys = array_keys($tables[0]);
		$keyName = $keys[0];
		$keyType = $keys[1];
		
		 foreach ($tables as $name) { //$tableNames
		 
			//if you don't want to truncate migrations
			if (in_array($name[$keyName], $excepts))
				continue;
			//return $name[$keyType];
			// truncate tables only
			/* if('BASE TABLE' !== $name[$keyType])
				continue; */
			
			$tablename = $name[$keyName];
			switch($tablename) {
				
				case 'account_transaction':
					$field = 'invoice_date';
					break;

				case 'credit_note':
					$field = 'voucher_date';
					$ids = $this->makeArr(DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('credit_note_entry')->whereIn('credit_note_id', $ids)->delete();
					break;
				
				case 'customer_do':
					$field = 'voucher_date';
					$ids = $this->makeArr(DB::table($tablename)->where('is_transfer',1)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('customer_do_item')->whereIn('customer_do_id', $ids)->delete();
					break;
					
				case 'debit_note':
					$field = 'voucher_date';
					$ids = $this->makeArr(DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('debit_note_entry')->whereIn('debit_note_id', $ids)->delete();
					break;
					
				case 'goods_issued':
					$field = 'voucher_date';
					$ids = $this->makeArr(DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('goods_issued_item')->whereIn('goods_issued_id', $ids)->delete();
					break;
					
				case 'goods_return':
					$field = 'voucher_date';
					$ids = $this->makeArr(DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('goods_return_item')->whereIn('goods_return_id', $ids)->delete();
					break;
					
				case 'journal':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('journal_entry')->whereIn('journal_id', $ids)->delete();
					break;
				
				case 'opening_balance_tr':				
					$field = 'tr_date';
					break;
					
				case 'item_log':				
					$field = 'voucher_date';
					break;
				
				case 'payment_voucher':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('payment_voucher_entry')->whereIn('payment_voucher_id', $ids)->delete();
					//DB::table('payment_voucher_tr')->whereIn($ids)->delete(); needed to recheck
					break;
				
				case 'petty_cash':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('petty_cash_entry')->whereIn('petty_cash_id', $ids)->delete();
					break;
					
				case 'purchase_invoice':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('purchase_invoice_item')->whereIn('purchase_invoice_id', $ids)->delete();
					DB::table('pi_other_cost')->whereIn('purchase_invoice_id', $ids)->delete();
					break;
				
				case 'purchase_order':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->where('is_transfer',1)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('purchase_order_item')->whereIn('purchase_order_id', $ids)->delete();
					DB::table('po_other_cost')->whereIn('purchase_order_id', $ids)->delete();
					break;
					
				case 'purchase_return':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('purchase_return_item')->whereIn('purchase_return_id', $ids)->delete();
					break;
				
				case 'quotation_sales':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->where('is_transfer',1)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('quotation_sales_item')->whereIn('quotation_sales_id', $ids)->delete();
					break;
				
				case 'receipt_voucher':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('receipt_voucher_entry')->whereIn('receipt_voucher_id', $ids)->delete();
					//DB::table('receipt_voucher_tr')->whereIn('receipt_voucher_entry_id', $ids)->delete(); needed to recheck
					break;
					
				case 'sales_invoice':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('sales_invoice_item')->whereIn('sales_invoice_id', $ids)->delete();
					break;
				
				case 'sales_order':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->where('is_transfer',1)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('sales_order_item')->whereIn('sales_order_id', $ids)->delete();
					break;
					
				case 'sales_return':
					$field = 'voucher_date';
					$ids = $this->makeArr( DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->select('id')->get());
					DB::table('sales_return_item')->whereIn('sales_return_id', $ids)->delete();
					break;

				case 'pdc_received':
					$field = 'voucher_date';
					DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->delete();
					break;
					
				case 'pdc_issued':
					$field = 'voucher_date';
					DB::table($tablename)->whereBetween($field, [$date->py_from_date, $date->py_to_date])->delete();
					break;
					
				default: 
					$field = '';
				
			}
			
			if($field=='')
				DB::table($tablename)->delete();
			else
				DB::table($tablename)->whereBetween($field, ['1970-01-01', $date->py_to_date])->delete(); //$date->py_from_date
		}
		return true;
	}
	
	private function makeArr($result) {
		
		$arr = array();
		foreach($result as $row) {
			$arr[] = $row->id;
		}
		
		return $arr;
	}
	
	private function backupDatabase()
	{
		//GETTING PREVIOUS FINC. YEAR....
		$year = date('Y',strtotime(Input::get('from_date')));
		
		//CREATE NEW DATABASE FOR BACKUP..
		exec('F:\xampp\mysql\bin\mysql.exe -u'.$this->dbcon['mysql']['username'].' -p'.$this->dbcon['mysql']['password'].' -e "CREATE DATABASE "'.$this->dbcon['mysql']['database'].'yr'.$year);
		
		
		//CURRENT DATA BACKUP..
		//exec('F:\xampp\mysql\bin\mysqldump.exe  -u'.$this->dbcon['mysql']['username'].' -p'.$this->dbcon['mysql']['password'].' '.$this->dbcon['mysql']['database'].' > F:\DataBackup.sql');
		exec("\"F:\\xampp\\mysql\\bin\\mysqldump.exe\" -u".env('DB_USERNAME')." -p".env('DB_PASSWORD')."  ".env('DB_DATABASE')." > F:\Databackup_".env('DB_DATABASE').".sql");
		
		//IMPORTING DUMP DATA..
		//exec('F:\xampp\mysql\bin\mysql.exe  -u'.$this->dbcon['mysql']['username'].' -p'.$this->dbcon['mysql']['password'].' '.$this->dbcon['mysql']['database'].'yr'.$year.' < F:\DataBackup.sql');
		exec("\"F:\\xampp\\mysql\\bin\\mysqldump.exe\" -u".env('DB_USERNAME')." -p".env('DB_PASSWORD')."  ".env('DB_DATABASE')."yr".$year." < F:\Databackup_".env('DB_DATABASE').".sql");
	}
	
	
	private function backupDatabase2()
	{
		$tables = array();
		$db = DB::select('SELECT DATABASE()');
		$DbName = $db[0]->{'DATABASE()'};
		$result = DB::select('SHOW TABLES');//$this->bank->getTables();
		foreach($result as $row)
		{
		  $key = 'Tables_in_'.$DbName;
		  $tables[] = $row->$key;
		} 
		
	  
		$return = 'SET FOREIGN_KEY_CHECKS=0;' . "\r\n";
		$return.= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n";
		$return.= 'SET AUTOCOMMIT=0;' . "\r\n";
		$return.= 'START TRANSACTION;' . "\r\n";
	  
		$data = '';
		  foreach($tables as $table)
		  {
			$result = DB::table($table)->get(); 
			$num_fields = (sizeof($result) > 0)?count((array)$result[0]):0; //$num_fields = mysqli_num_fields($result);
			
			$data.= 'DROP TABLE IF EXISTS '.$table.';';
			$row2 = DB::select('SHOW CREATE TABLE '.$table);
			
			$data.= "\n\n".$row2[0]->{'Create Table'}.";\n\n";
			
			for ($i = 0; $i<$num_fields; $i++) 
			{
			  foreach($result as $row)//while($row = mysqli_fetch_row($result))
			  { $row = array_values((array)$row);
				$data.= 'INSERT INTO '.$table.' VALUES('; //$ar =[];
				for($x=0; $x<$num_fields; $x++) 
				{  //$ar[]=[$k,$v];
				  
				  $row[$x] = addslashes($row[$x]);
				  $row[$x] = $this->clean($row[$x]); // CLEAN QUERIES
				  
				  if (isset($row[$x])) { 
					$data.= '"'.$row[$x].'"';
				  } else { 
					$data.= '""';
				  }
				  
				  if ($x<($num_fields-1)) { 
					$data.= ','; 
				  }
				  
				}  //return $ar;// end of the for loop 2
				$data.= ");\n";
			  } // end of the while loop 
			} // end of the for loop 1
			
			$data.="\n\n\n";
		  }  // end of the foreach*/
	  
		$return .= 'SET FOREIGN_KEY_CHECKS=1;' . "\r\n";
		$return.= 'COMMIT;';
	  
	  //SAVE THE BACKUP AS SQL FILE
	  $fileName = strtoupper($DbName).'-Database-Backup-'.date('Y-m-d @ h-i-s').'.sql';
	  $handle = fopen($fileName,'w+'); //fopen($DbName.'-Database-Backup-'.date('Y-m-d @ h-i-s').'.sql','w+');
	  fwrite($handle,$data);
	  fclose($handle);
	   
	   if($data) {
		    header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($fileName));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fileName));
			readfile($fileName);

			//exit;
			return true;
	   } else
			return false;
	}  // end of the function
	 
	 //  CLEAN THE QUERIES
	private function clean($str) {
		if(@isset($str)){
			$str = @trim($str);
			if(get_magic_quotes_gpc()) {
				$str = stripslashes($str);
			}
			return DB::connection()->getPdo()->quote($str);//mysqli_real_escape_string($str);
		}
		else{
			return 'NULL';
		}
	}
	
	private function outStandingBills() {
		
		Input::merge(['date_from' => $this->acsettings->py_from_date ]); 
		Input::merge(['date_to' => $this->acsettings->py_to_date ]);
		Input::merge(['type' => 'outstanding']);
		Input::merge(['is_custom' => 0]);
		
		$accounts = DB::table('account_master')->whereIn('category',['CUSTOMER','SUPPLIER'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','category')->get();
		$sales = $purchase = []; 
		foreach($accounts as $acrow) {
			
			Input::merge(['account_id' => $acrow->id]);
			
			$category = $acrow->category;
			$results = $this->accountmaster->getPrintViewByAccount(Input::all()); 
			
			//$results = $this->sortByRefno($results); echo '<pre>';print_r($results);exit;
			$transactions = $this->groupAccounts($this->sortByRefno($results)); //echo '<pre>';print_r($transactions);exit;
			usort($transactions, array($this, "date_compare"));
			//$transactions1[] = $results;
			$datas = [];

			if (!$results) {
				$datas[] = (object)['voucher_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
										'voucher_no' => 'OB',
										'balance_amount'	=> 0.00,
										'owner_id'	=> $acrow->id,
										'id'		=> 0,
										'tr_type'	=> ($category=='CUSTOMER')?'Dr':'Cr'
										];
		    } 

			foreach($transactions as $trans) {
				$balance = bcsub($trans['dr_amount'], $trans['cr_amount'], 2);
				
				if($category=='CUSTOMER') {	 // && $balance !=0
					
						if($balance < 0) {
							$balance = $balance*-1; $type = 'Cr';
						} else
							$type = 'Dr';
						
							$datas[] = (object)['voucher_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
										'voucher_no' => $trans['reference_from'],
										'balance_amount'	=> $balance,
										'owner_id'	=> $acrow->id,
										'id'		=> $trans['voucher_type_id'],
										'tr_type'	=> $type
										];
					
				} else if($category=='SUPPLIER') {	// && $balance !=0
				
					if($balance < 0) {
						$balance = $balance*-1; $type = 'Cr';
					} else
						$type = 'Dr';
					
						$datas[] = (object)['voucher_date' => date('d-m-Y', strtotime($trans['invoice_date'])),
									'voucher_no' => $trans['reference_from'],
									'balance_amount'	=> $balance,
									'owner_id'	=> $acrow->id,
									'id'	=> $trans['voucher_type_id'],
									'tr_type'	=> $type
									];
				}
			}		
			
			usort($datas, array($this, "date_compare_bill"));
			if($category=='CUSTOMER' && !empty($datas)) {
				$sales[$acrow->id] = $datas;
			}
			else if($category=='SUPPLIER' && !empty($datas)) {
				$purchase[$acrow->id] = $datas;
			}
			
		}//end foreach
		
		$arrset['sales'] = $sales;
		$arrset['purchase'] = $purchase;
		
		//echo '<pre>';print_r($otharr);exit;
		//echo '<pre>';print_r($datas1);exit;
		return $arrset;
		//echo '<pre>';print_r($arrset);exit;
		
	}
	
	protected function sortByRefno($result)
	{
		$childs = array();
		foreach($result as $item)
			if($item->reference_from=='')
				$childs[$item->reference][] = $item;
			else
				$childs[$item->reference_from][] = $item;

		foreach($result as $item) if (isset($childs[$item->id]))
			$item->childs = $childs[$item->id];
		
		return $childs;
	}
	
	protected function groupAccounts($results)
	{
		$arrSummarry = array();
		foreach($results as $rows)
		{
			$dramount = $cramount = 0; $invoice_date = $voucher_typeid = $voucher_type = $edit = '';
			$vtype = ['SI','PI','OBD','PIN','SIN','SIR','PIR'];
			foreach($rows as $row) {
				$reference = $row->reference;
				$due_date = $row->invoice_date;
				$description = $row->description; 
				$jobno = isset($row->jobno)?$row->jobno:''; 
				$reference_frm = isset($row->reference_from)?$row->reference_from:'';
				$cheque_date = isset($row->cheque_date)?$row->cheque_date:'';
				$cheque_no = isset($row->cheque_no)?$row->cheque_no:'';
				$account_id = isset($row->account_master_id)?$row->account_master_id:'';
				
				if(!isset($row->is_edit)) {
					if($row->transaction_type=='Dr') {
						$dramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					} else {
						$cramount += $row->amount; //COMMENTED 22 DEC 2021 (isset($row->is_fc))?(($row->is_fc==1)?$row->fc_amount:$row->amount):$row->amount;
					}
				}
				if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='PIN' || $row->voucher_type=='SIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='PS' || $row->voucher_type=='SS' || $row->voucher_type=='SIR' || $row->voucher_type=='PIR') {
					$voucher_typeid = $row->voucher_type_id;
					$voucher_type = $row->voucher_type;
				}
					
				if(in_array($row->voucher_type, $vtype)) {
					$invoice_date = $row->invoice_date;
				}
				
				if($edit=='')
					$edit = (isset($row->is_edit))?$row->is_edit:'';
				
				//if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV')
				/* if($row->voucher_type=='PI' || $row->voucher_type=='SI' || $row->voucher_type=='OBD' || $row->voucher_type=='CN' || $row->voucher_type=='PIN' || $row->voucher_type=='SR' || $row->voucher_type=='PR' || $row->voucher_type=='JV' || $row->voucher_type=='RV' || $row->voucher_type=='PV' || $row->voucher_type=='DB' || $row->voucher_type=='CB')
					$invoice_date = $row->invoice_date; */
				/*  IF RV NOT IN IF CONDITION TAKING THE INVOICE DATE CORRECTLY IN THE OUTSTANDIN BILL OTHERWISE INVOICE DATE IS 01-01-1970 ??? */
			}
			
			$invoice_date = ($invoice_date=='')?$row->invoice_date:$invoice_date; /* ABOVE SOLUTION HERE ON 5 APR 2021 */
			$arrSummarry[] = ['name' => $reference, 
							  'cr_amount' => $cramount, 
							  'dr_amount' => $dramount,
							  'invoice_date' => $invoice_date,
							  'description' => $description,
							  'jobno' => $jobno,
							  'reference_from'	=> ($reference_frm=='')?$reference:$reference_frm,
							  'inv_month' => date('m', strtotime($invoice_date)),
							  'due_date' => $due_date,
							  'cheque_date' => $cheque_date,
							  'cheque_no' => $cheque_no,
							  'account_master_id' => $account_id,
							  'voucher_type'	=> $voucher_type,
							  'voucher_type_id'	=> $voucher_typeid,
							  'is_edit'		=> $edit
							 ];

		}
		return $arrSummarry;
	}


	public function updateItemStock()
	{ 
			
			$result = $this->makeSummaryStock( $this->itemmaster->updateUtility() ); 
			
			//QUICK UPDATE ITEM STOCK ....
			$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
				
			foreach($items as $item) {
				
				$itemlog = DB::table('item_log')
								  ->where('item_id', $item->itemmaster_id)
								  ->where('status',1)
								  ->where('deleted_at','0000-00-00 00:00:00')
								  ->select('item_log.*')
								  ->orderBy('id','DESC')
								  ->first(); 
				if($itemlog) {
					
					$qty_rec = $qty_isd = $curr_qnty = 0;
					$qntys = $this->getItemQtyLog($item->itemmaster_id);
					if($qntys) {
						$qty_rec = $qntys['in'];
						$qty_isd = $qntys['out'];
						$curr_qnty = $qty_rec - $qty_isd;
					}
					//echo $curr_qnty.' '.$item->itemmaster_id.'<br/>'; //<pre>';print_r($itemlog); exit;
					if($itemlog->document_type=='SI'||$itemlog->document_type=='PR'||$itemlog->document_type=='GR'||$itemlog->document_type=='TO') {
						
						DB::table('item_unit')->where('itemmaster_id', $item->itemmaster_id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'issued_qty' => $qty_isd, 'received_qty' => $qty_rec,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg
											  ]);
					} else if($itemlog->document_type=='PI'||$itemlog->document_type=='SR'||$itemlog->document_type=='GI'||$itemlog->document_type=='TI') { 
						DB::table('item_unit')->where('itemmaster_id', $item->itemmaster_id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg,
												'received_qty' => $qty_rec, 'issued_qty' => $qty_isd
											  ]);
					} else if($itemlog->document_type=='OQ') { 
						DB::table('item_unit')->where('itemmaster_id', $item->itemmaster_id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg,
												'received_qty' => $qty_rec,
												'issued_qty' => 0
											  ]);
					}
				}
								  
		}


	}
	

	private function getItemQtyLog($item_id)
	{
		$qtyin = DB::table('item_log')->where('item_id', $item_id)->where('trtype',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
		
		$qtyout = DB::table('item_log')->where('item_id', $item_id)->where('trtype',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
		
		return ['in' => $qtyin, 'out' => $qtyout];
	}

	private static function date_compare($a, $b) {
		$t1 = strtotime($a['invoice_date']);
		$t2 = strtotime($b['invoice_date']);
		return $t1 - $t2;
	}
	
	
	private static function date_compare_bill($a, $b) {
		$t1 = strtotime($a->voucher_date);
		$t2 = strtotime($b->voucher_date);
		return $t1 - $t2;
	}
}
