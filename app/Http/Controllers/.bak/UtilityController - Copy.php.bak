<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\UpdateUtility;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;
use Log;

class UtilityController extends Controller
{
    protected $accountmaster;
	protected $itemmaster;
	public $objUtility;
	
	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->objUtility = new UpdateUtility();
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array(); 
		
		/* $drTr = DB::table('account_transaction')
								->where('voucher_type', 'SR')
								->where('voucher_type_id', 33)
								->select(DB::raw('SUM(amount) AS amount'))
								->first();
								
		echo '<pre>';print_r($drTr);exit; */
		
		//$this->evalItemQuantity();
		return view('body.utilities.index')
					->withData($data);
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
	
	public function update($type)
	{ //echo '<pre>';print_r(Input::all());exit; other_info
		if($type=='CB') {
			//REMOVE duplicate entries...
			DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info)');
			
			$result = $this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility($type)) ); //echo '<pre>';print_r($result);exit;
			Session::flash('message', 'Closing balance recalculated and updated successfully');
		
		} else if($type=='stock') {
			
			//$result = $this->makeSummaryStock( $this->itemmaster->updateUtility() ); 
			
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
					//echo '<pre>';print_r($itemlog); exit;
					if($itemlog->document_type=='SI'||$itemlog->document_type=='PR'||$itemlog->document_type=='GR'||$itemlog->document_type=='TO') {
						
						DB::table('item_unit')->where('itemmaster_id', $item->id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'issued_qty' => $qty_isd, 'received_qty' => $qty_rec,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg
											  ]);
					} else if($itemlog->document_type=='PI'||$itemlog->document_type=='SR'||$itemlog->document_type=='GI'||$itemlog->document_type=='TI') { 
						DB::table('item_unit')->where('itemmaster_id', $item->id)
											  ->update([
												'cur_quantity' => $curr_qnty,
												'last_purchase_cost' => $itemlog->pur_cost,
												'cost_avg' => $itemlog->cost_avg,
												'received_qty' => $qty_rec, 'issued_qty' => $qty_isd
											  ]);
					} else if($itemlog->document_type=='OQ') { 
						DB::table('item_unit')->where('itemmaster_id', $item->id)
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
			
			Session::flash('message', 'Stock updated successfully');
			
		} else if($type=='stockLoc') {
			
			//QUICK UPDATE ITEM STOCK ....
			$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
				
			foreach($items as $item) {
				
				$itemLogs = $this->sumLoc($this->groupItemLoc($this->groupLoc( $this->itemmaster->ItemLogLocation($item->itemmaster_id) )));
				//echo '<pre>';print_r($itemLogs);exit;
				foreach($itemLogs as $loc => $rows) {
				   foreach($rows as $row) {
					DB::table('item_location')->where('location_id',$loc)->where('item_id',$row['item_id'])->where('unit_id',$row['unit'])
							->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->update(['quantity' => $row['quantity'] ]);
					
				   }
				   
				}
			}
			
			Session::flash('message', 'Stock updated successfully');
			
		} else if($type=='avgcost') {
			
			$i = $this->reEvalItemCostQuantity();
			
			Session::flash('message', 'Average cost recalculated successfully');
			
		} else if($type=='POi') {
			
			//GETTING PURCHASE ORDER ID and RESET ITMS BALANCE QTY...
			$res = DB::table('purchase_order')->where('voucher_no', Input::get('pono'))->select('id')->first();
			if($res) {
				
				//GETTING ITEMS UNIT ID AND RESET IN SO ITEMS.....
				$doarr = DB::table('purchase_order_item')->where('purchase_order_id',$res->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
				foreach($doarr as $row) {
					//******* RETHINK THE LOGIN WHEN ITEM HAS MULTIPLE UNIT ********
					$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
					
					if($row->unit_id!=$itm->unit_id) {
						DB::table('purchase_order_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]);
					}
				}
				//........................................ 
				
				//GETTING PURCHASE INVOICE ITEMS WITH TOTAL QUANTITY TRANSFERED...
				$itms = DB::table('purchase_invoice')->where('purchase_invoice.document_id', $res->id)
							->join('purchase_invoice_item','purchase_invoice_item.purchase_invoice_id','=','purchase_invoice.id')
							->where('purchase_invoice.status',1)
							->where('purchase_invoice.deleted_at','0000-00-00 00:00:00')
							->select('purchase_invoice_item.item_id', DB::raw('SUM(purchase_invoice_item.quantity) AS quantity'))
							->groupBy('purchase_invoice_item.item_id')
							->get();
			
				DB::table('purchase_order_item')
								->where('purchase_order_id',$res->id)
								->update(['is_transfer' => 0, 'balance_quantity' => 0]);
			}
			
			if($itms) {
				
				foreach($itms as $row) {
					
					$itmrow = DB::table('purchase_order')->where('purchase_order.id', $res->id)
									->join('purchase_order_item','purchase_order_item.purchase_order_id','=','purchase_order.id')
									->where('purchase_order.status',1)
									->where('purchase_order.deleted_at','0000-00-00 00:00:00')
									->where('purchase_order_item.status',1)
									->where('purchase_order_item.deleted_at','0000-00-00 00:00:00')
									->where('purchase_order_item.item_id', $row->item_id)
									->select('purchase_order_item.id','purchase_order_item.quantity')
									->first();
									
					if($itmrow) {
						$balqty = $itmrow->quantity - $row->quantity;
						$istr = ($balqty==0)?1:2;
						DB::table('purchase_order_item')
									->where('id',$itmrow->id)
									->update(['is_transfer' => $istr, 'balance_quantity' => $balqty]);
					}
					//print_r($itmrow);exit;
					
				}
			}
			
			//echo '<pre>';print_r($itms);exit;
			Session::flash('message', 'Purchase order items update successfully');
			
		} else if($type=='SOi') {
			
			//GETTING SALES ORDER ID and RESET ITMS BALANCE QTY...
			$res = DB::table('sales_order')->where('voucher_no', Input::get('sono'))->select('id','reference_no')->first(); //print_r($res);exit;
			$itms = null;
			if($res) {
				
				//GETTING ITEMS UNIT ID AND RESET IN SO ITEMS.....
				$doarr = DB::table('sales_order_item')->where('sales_order_id',$res->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
				foreach($doarr as $row) {
					//******* RETHINK THE LOGIN WHEN ITEM HAS MULTIPLE UNIT ********
					$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
					
					if($row->unit_id!=$itm->unit_id) {
						DB::table('sales_order_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]);
					}
				}
				//........................................ 
				
				//GETTING SALES INVOICE ITEMS WITH TOTAL QUANTITY TRANSFERED...
				$itms = DB::table('sales_invoice')->where('sales_invoice.lpo_no', $res->reference_no)
							->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_invoice.id')
							->where('sales_invoice.status',1)
							->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
							->select('sales_invoice_item.item_id', DB::raw('SUM(sales_invoice_item.quantity) AS quantity'))
							->groupBy('sales_invoice_item.item_id')
							->get();
							
				/* $itms = DB::table('sales_invoice')->where('sales_invoice.document_id', $res->id)
							->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_invoice.id')
							->where('sales_invoice.status',1)
							->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
							->select('sales_invoice_item.item_id', DB::raw('SUM(sales_invoice_item.quantity) AS quantity'))
							->groupBy('sales_invoice_item.item_id')
							->get(); */
			
				DB::table('sales_order_item')
								->where('sales_order_id',$res->id)
								->update(['is_transfer' => 0, 'balance_quantity' => 0]);
			}
			
			if($itms) {
				
				foreach($itms as $row) {
					
					$itmrow = DB::table('sales_order')->where('sales_order.id', $res->id)
									->join('sales_order_item','sales_order_item.sales_order_id','=','sales_order.id')
									->where('sales_order.status',1)
									->where('sales_order.deleted_at','0000-00-00 00:00:00')
									->where('sales_order_item.status',1)
									->where('sales_order_item.deleted_at','0000-00-00 00:00:00')
									->where('sales_order_item.item_id', $row->item_id)
									->select('sales_order_item.id','sales_order_item.quantity')
									->first();
									
					if($itmrow) {
						$balqty = $itmrow->quantity - $row->quantity;
						$istr = ($balqty==0)?1:2;
						DB::table('sales_order_item')
									->where('id',$itmrow->id)
									->update(['is_transfer' => $istr, 'balance_quantity' => $balqty]);
					}
					//print_r($itmrow);exit;
					
				}
			}
			
			//echo '<pre>';print_r($itms);exit;
			Session::flash('message', 'Sales order items update successfully');
			
		} else if($type=='DOi') {
			
			//GETTING DELIVERY ORDER ID and RESET ITMS BALANCE QTY...
			$res = DB::table('customer_do')->where('voucher_no', Input::get('dono'))->select('id','reference_no')->first(); //print_r($res);exit;
			$itms = null;
			if($res) {
				
				//GETTING ITEMS UNIT ID AND RESET IN DO ITEMS.....
				$doarr = DB::table('customer_do_item')->where('customer_do_id',$res->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
				foreach($doarr as $row) {
					//******* RETHINK THE LOGIN WHEN ITEM HAS MULTIPLE UNIT ********
					$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
					
					if($row->unit_id!=$itm->unit_id) {
						DB::table('customer_do_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]);
					}
				}
				//........................................ 
				
				
				//GETTING SALES INVOICE ITEMS WITH TOTAL QUANTITY TRANSFERED...
				$itms = DB::table('sales_invoice')->where('sales_invoice.document_id', $res->id)
							->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_invoice.id')
							->where('sales_invoice.status',1)
							->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
							->select('sales_invoice_item.item_id', DB::raw('SUM(sales_invoice_item.quantity) AS quantity'))
							->groupBy('sales_invoice_item.item_id')
							->get();
			
				DB::table('customer_do_item')
								->where('customer_do_id',$res->id)
								->update(['is_transfer' => 0, 'balance_quantity' => 0]);
			}
			
			if($itms) {
				
				foreach($itms as $row) {
					
					$itmrow = DB::table('customer_do')->where('customer_do.id', $res->id)
									->join('customer_do_item','customer_do_item.customer_do_id','=','customer_do.id')
									->where('customer_do.status',1)
									->where('customer_do.deleted_at','0000-00-00 00:00:00')
									->where('customer_do_item.status',1)
									->where('customer_do_item.deleted_at','0000-00-00 00:00:00')
									->where('customer_do_item.item_id', $row->item_id)
									->select('customer_do_item.id','customer_do_item.quantity')
									->first();
									
					if($itmrow) {
						$balqty = $itmrow->quantity - $row->quantity;
						$istr = ($balqty==0)?1:2;
						DB::table('customer_do_item')
									->where('id',$itmrow->id)
									->update(['is_transfer' => $istr, 'balance_quantity' => $balqty]);
					}
					//print_r($itmrow);exit;
					
				}
			}
			
			//echo '<pre>';print_r($itms);exit;
			Session::flash('message', 'Delivery order items update successfully');
		
		} else if($type=='SIi') {
			
			$is_update = false;
			//GETTING SALES INVOICE ID and RESET ITMS BALANCE QTY...
			$res = DB::table('sales_invoice')->where('voucher_no', Input::get('sino'))->select('id')->first(); //print_r($res);exit;
			if($res) {
				
				//GETTING ITEMS UNIT ID AND RESET IN DO ITEMS.....
				$siarr = DB::table('sales_invoice_item')->where('sales_invoice_id',$res->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
				foreach($siarr as $row) {
					//******* RETHINK THE LOGIN WHEN ITEM HAS MULTIPLE UNIT ********
					$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
					
					if($row->unit_id!=$itm->unit_id) {
						DB::table('sales_invoice_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]);
						DB::table('item_log')->where('document_type','SI')->where('document_id',$res->id)->where('item_id',$row->item_id)->update(['unit_id' => $itm->unit_id]);
					}
					$is_update = true;
				}
				//........................................ 
				
			}
			
			if($is_update)
				Session::flash('message', 'Sales invoice updated successfully');
			else
				Session::flash('error', 'There were nothing to update.');
			
		} else if($type=='PIi') {
			
			$is_update = false;
			//GETTING PURCHASE INVOICE ID and RESET ITMS BALANCE QTY...
			$res = DB::table('purchase_invoice')->where('voucher_no', Input::get('pino'))->select('id')->first(); //print_r($res);exit;
			if($res) {
				
				//GETTING ITEMS UNIT ID AND RESET IN DO ITEMS.....
				$siarr = DB::table('purchase_invoice_item')->where('purchase_invoice_id',$res->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','item_id','unit_id')->get();
				foreach($siarr as $row) {
					//******* RETHINK THE LOGIN WHEN ITEM HAS MULTIPLE UNIT ********
					$itm = DB::table('item_unit')->where('itemmaster_id',$row->item_id)->where('is_baseqty',1)->select('unit_id')->first();
					
					if($row->unit_id!=$itm->unit_id) {
						DB::table('purchase_invoice_item')->where('id',$row->id)->update(['unit_id' => $itm->unit_id]);
						DB::table('item_log')->where('document_type','PI')->where('document_id',$res->id)->where('item_id',$row->item_id)->update(['unit_id' => $itm->unit_id]);
					}
					$is_update = true;
				}
				//........................................ 
				
			}
			
			if($is_update)
				Session::flash('message', 'Purchase invoice updated successfully');
			else
				Session::flash('error', 'There were nothing to update.');
			
		} else if($type=='SI') {
			
			$res = DB::table('receipt_voucher_tr')->where('bill_type','SI')
							->where('status',1)
							->where('deleted_at','0000-00-00 00:00:00')
							->select('sales_invoice_id',DB::raw('SUM(assign_amount) AS amount'))
							->groupBy('sales_invoice_id')
							->get();
							//echo '<pre>';print_r($res);exit;
			if($res) {
				foreach($res as $row) {
					$si = DB::table('sales_invoice')->where('id',$row->sales_invoice_id)
								->select('net_total')->first();
								
					//JOURNAL ENTRY AMOUNT...
					$jedata = DB::table('journal_voucher_tr')->where('invoice_id', $row->sales_invoice_id)
													->where('bill_type','SI')->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')
													->select(DB::raw('SUM(assign_amount) AS amount'))
													->groupBy('invoice_id')
													->first();
													
					//CREDITNOTE AMOUNT...
					$cndata = DB::table('credit_note_entry')->where('invoice_id', $row->sales_invoice_id)
													->where('status',1)
													->where('deleted_at','0000-00-00 00:00:00')
													->select(DB::raw('SUM(cr_amount) AS amount'))
													->groupBy('invoice_id')
													->first();
					
					if($si) {
						$jeamount = ($jedata)?$jedata->amount:0;
						$cnamount = ($cndata)?$cndata->amount:0;
						$balamount = $si->net_total - $row->amount - $jeamount - $cnamount;
						DB::table('sales_invoice')
									->where('id',$row->sales_invoice_id)
									->update(['amount_transfer' => ($balamount==0)?1:2, 'balance_amount' => $balamount]);
					}
				}
			}
			
			Session::flash('message', 'Sales Invoice amount update successfully');
		
		} else if($type=='PI') {
			
			$res = DB::table('payment_voucher_tr')->where('bill_type','PI')
							->where('status',1)
							->where('deleted_at','0000-00-00 00:00:00')
							->select('purchase_invoice_id',DB::raw('SUM(assign_amount) AS amount'))
							->groupBy('purchase_invoice_id')
							->get();
											
			if($res) {
				foreach($res as $row) {
					$pi = DB::table('purchase_invoice')->where('id',$row->purchase_invoice_id)
								->select('net_amount')->first();
								
					//JOURNAL ENTRY AMOUNT...
					$jedata = DB::table('journal_voucher_tr')->where('invoice_id', $row->purchase_invoice_id)
											->where('bill_type','PI')->where('status',1)
											->where('deleted_at','0000-00-00 00:00:00')
											->select(DB::raw('SUM(assign_amount) AS amount'))
											->groupBy('invoice_id')
											->first();
											
					//CREDITNOTE AMOUNT...
					$dndata = DB::table('debit_note_entry')->where('invoice_id', $row->purchase_invoice_id)
											->where('status',1)
											->where('deleted_at','0000-00-00 00:00:00')
											->select(DB::raw('SUM(dr_amount) AS amount'))
											->groupBy('invoice_id')
											->first();
											
					//echo '<pre>';print_r($pi);
					if($pi) {
						$jeamount = ($jedata)?$jedata->amount:0;
						$dnamount = ($dndata)?$dndata->amount:0;
						
						$balamount = $pi->net_amount - $row->amount - $jeamount - $dnamount;
						DB::table('purchase_invoice')
									->where('id',$row->purchase_invoice_id)
									->update(['amount_transfer' => ($balamount==0)?1:2, 'balance_amount' => $balamount]);
					}
				}
			}
			
			Session::flash('message', 'Purchase Invoice amount update successfully');
		
		} else if($type=='CA') {
			
			$res = DB::table('parameter2')->where('keyname','mod_cost_accounting')->select('is_active')->first();
			//echo Session::get('department');echo '<pre>';print_r($res);	exit;	
			if($res->is_active==0) 
			{
				//CHECK DEPARTMENT ACTIVE IS ACTIVE...
				if(Session::get('department')==1) {
					$ac = DB::table('department_accounts')->select('stock_acid','cost_acid')->first();
					
					//STOCK AC SECTION....
					DB::table('account_transaction')
								->where('voucher_type','SI')
								->where('transaction_type','Cr')
								->where('account_master_id',$ac->stock_acid)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
					DB::table('account_transaction')
						->where('voucher_type','SR')
						->where('transaction_type','Dr')
						->where('account_master_id',$ac->stock_acid)
						->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
						
					DB::table('account_transaction')
						->where('voucher_type','PR')
						->where('transaction_type','Cr')
						->where('account_master_id',$ac->stock_acid)
						->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
						->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					
					//COST AC SECTION...		
					DB::table('account_transaction')
						->where('voucher_type','SI')
						->where('transaction_type','Dr')
						->where('account_master_id',$ac->cost_acid)
						->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);	
						
					DB::table('account_transaction')
						->where('voucher_type','SR')
						->where('transaction_type','Cr')
						->where('account_master_id',$ac->cost_acid)
						->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);	
						
					//COST DIFF AC SECTION...
					DB::table('account_transaction')
								->where('voucher_type','PR')
								->where('transaction_type','Dr')
								->where('account_master_id',$ac->costdif_acid)
								->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							
					DB::table('account_transaction')
							->where('voucher_type','TO')
							->where('transaction_type','Dr')
							->where('account_master_id',$ac->costdif_acid)
							->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
							->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
							
					DB::table('account_transaction')
							->where('voucher_type','GI')
							->where('transaction_type','Dr')
							->where('account_master_id',$ac->costdif_acid)
							->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
							->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
					
				} else {
					
					//DEPARTMENT IS NOT ACTIVE.....
					//RESET STOCK A/C AND COST A/C IN SI,SR,PR
					$acs = DB::table('voucher_account')->get();
					foreach($acs as $ac) { 
						if($ac->account_name=='Stock') { //REST STOCK A/C
							DB::table('account_transaction')
								->where('voucher_type','SI')
								->where('transaction_type','Cr')
								->where('account_master_id',$ac->account_id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
							DB::table('account_transaction')
								->where('voucher_type','SR')
								->where('transaction_type','Dr')
								->where('account_master_id',$ac->account_id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
							DB::table('account_transaction')
								->where('voucher_type','PR')
								->where('transaction_type','Cr')
								->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
								->where('account_master_id',$ac->account_id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
						} else {	//RESET COST A/C
						
							DB::table('account_transaction')
								->where('voucher_type','SI')
								->where('transaction_type','Dr')
								->where('account_master_id',$ac->account_id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);	
								
							DB::table('account_transaction')
								->where('voucher_type','SR')
								->where('transaction_type','Cr')
								->where('account_master_id',$ac->account_id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);	
						
						}
					}
					
					//RESET COST DIFF A/C IN PR,TRO,GI
					$acdiff = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->where('status',1)->first();
					if($acdiff) {
						DB::table('account_transaction')
								->where('voucher_type','PR')
								->where('transaction_type','Dr')
								->where('account_master_id',$acdiff->account_id)
								->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
						DB::table('account_transaction')
								->where('voucher_type','TO')
								->where('transaction_type','Dr')
								->where('account_master_id',$acdiff->account_id)
								->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
								
						DB::table('account_transaction')
								->where('voucher_type','GI')
								->where('transaction_type','Dr')
								->where('account_master_id',$acdiff->account_id)
								->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					}
				}
				
			} else {
				
				if(Session::get('department')==1) {
					//DEPARTMENT IS ACTIVE...
					
					//Sales Invoice Cost Calculating....
					$si = DB::table('sales_invoice')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id','!=',0)->get();
					foreach($si as $row) {
						
						$dpt = DB::table('department_accounts')->where('department_id',$row->department_id)->select('stock_acid','cost_acid')->first();
						$stock_ac = $dpt->stock_acid;
						$cost_ac = $dpt->cost_acid;
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'SI')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $cost_ac)
									->where('transaction_type', 'Dr')
									->where('department_id', $row->department_id)
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'SI')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('department_id', $row->department_id)
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
						} else {
							
							$itm = DB::table('sales_invoice_item')->where('sales_invoice_id', $row->id)->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->select(DB::raw('SUM(item_cost) AS cost'))->first();
							
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SI',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $cost_ac,
												  'transaction_type'	=> 'Dr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost,
												  'department_id' => $row->department_id
												]);
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SI',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $stock_ac,
												  'transaction_type'	=> 'Cr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost,
												  'department_id' => $row->department_id
												]);
						}
					}
					
					//Sales Return Cost Calculating....
					$sr = DB::table('sales_return')->where('sales_return.status',1)->where('sales_return.deleted_at','0000-00-00 00:00:00')
							->join('sales_invoice','sales_invoice.id','=','sales_return.sales_invoice_id')
							->where('sales_invoice.department_id','!=',0)
							->select('sales_return.*','sales_invoice.department_id')
							->get();
					foreach($sr as $row) {
						
						$dpt = DB::table('department_accounts')->where('department_id',$row->department_id)->select('stock_acid','cost_acid')->first();
						$stock_ac = $dpt->stock_acid;
						$cost_ac = $dpt->cost_acid;
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'SR')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $cost_ac)
									->where('transaction_type', 'Cr')
									->where('department_id', $row->department_id)
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'SR')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Dr')
										->where('department_id', $row->department_id)
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
						} else {
							
							$itm = DB::table('sales_return_item')->where('sales_return_id', $row->id)->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->select(DB::raw('SUM(item_cost) AS cost'))->first();
							
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $cost_ac,
												  'transaction_type'	=> 'Cr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost,
												  'department_id' => $row->department_id
												]);
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $stock_ac,
												  'transaction_type'	=> 'Dr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost,
												  'department_id' => $row->department_id
												]);
						}
					}
					
				} else {
					
					//DEPARTMENT IS NOT ACTIVE...
					$acs = DB::table('voucher_account')->get();
					$stock_ac = ($acs)?$acs[0]->account_id:'';
					$cost_ac = ($acs)?$acs[1]->account_id:'';
					$acdiff = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->where('status',1)->first();
					
					//Sales Invoice Cost Calculating....
					$si = DB::table('sales_invoice')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					foreach($si as $row) {
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'SI')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $cost_ac)
									->where('transaction_type', 'Dr')
									->select('id','status','deleted_at')
									->first();
						//echo '<pre>';print_r($drTr);	exit;			
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'SI')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
							//UPDATE ACCOUNT TRANSACTION...
							$itm = DB::table('item_log')->where('document_type', 'SI')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select(DB::raw('SUM(quantity*sale_cost) AS cost'))->first();
							
							//Dr Account....
							DB::table('account_transaction')
										->where('id', $drTr->id)
										->update(['amount'		=> $itm->cost,
												  'fc_amount'	=> $itm->cost
												]);
							
							//Cr Account....
							DB::table('account_transaction')
										->where('voucher_type', 'SI')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->update(['amount'		=> $itm->cost,
												  'fc_amount'	=> $itm->cost
												]);
												
						} else {
							
							//INSERT ACCOUNT TRANSACTION...
							//$itm = DB::table('sales_invoice_item')->where('sales_invoice_id', $row->id)->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->select(DB::raw('SUM(item_cost) AS cost'))->first();
							$itm = DB::table('item_log')->where('document_type', 'SI')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select(DB::raw('SUM(quantity*sale_cost) AS cost'))->first();
							
							//echo '<pre>';print_r($itm);exit;
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SI',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $cost_ac,
												  'transaction_type'	=> 'Dr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost
												]);
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SI',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $stock_ac,
												  'transaction_type'	=> 'Cr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost
												]);
						}
					}
					
					//Sales Return Cost Calculating....
					$sr = DB::table('sales_return')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					foreach($sr as $row) {
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'SR')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $cost_ac)
									->where('transaction_type', 'Cr')
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'SR')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Dr')
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
							//UPDATE ACCOUNT TRANSACTION...
							$itm = DB::table('item_log')->where('document_type', 'SR')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select(DB::raw('SUM(quantity*pur_cost) AS cost'))->first();
							
							//Dr Account....
							DB::table('account_transaction')
										->where('id', $drTr->id)
										->update(['amount'		=> $itm->cost,
												  'fc_amount'	=> $itm->cost
												]);
							
							//Cr Account....
							DB::table('account_transaction')
										->where('voucher_type', 'SR')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->update(['amount'		=> $itm->cost,
												  'fc_amount'	=> $itm->cost
												]);
							
						} else {
							
							//$itm = DB::table('sales_return_item')->where('sales_return_id', $row->id)->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->select(DB::raw('SUM(item_cost) AS cost'))->first();
							
							$itm = DB::table('item_log')->where('document_type', 'SR')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select(DB::raw('SUM(quantity*pur_cost) AS cost'))->first();
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $cost_ac,
												  'transaction_type'	=> 'Cr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost
												]);
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'SR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $stock_ac,
												  'transaction_type'	=> 'Dr',
												  'amount'		=> $itm->cost,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'fc_amount'	=> $itm->cost
												]);
						}
					}
					
					//PURCHASE RETURN CALCULATING....... DEC17
					$sr = DB::table('purchase_return')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					foreach($sr as $row) {
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'PR')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $acdiff->account_id)
									->where('transaction_type', 'Dr')
									->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'PR')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
							//UPDATE ACCOUNT TRANSACTION...
							$itm_pr = DB::table('item_log')->where('document_type', 'PR')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('item_id','unit_id','quantity','unit_cost','return_ref_id')->get();
							if($itm_pr)
								$itm_pi = DB::table('item_log')->where('document_type', 'PI')->where('document_id', $itm_pr[0]->return_ref_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('item_id','unit_id','quantity','unit_cost')->get();
							$costitm = 0;					
							foreach($itm_pr as $valpr) {
								foreach($itm_pi as $valpi) {
									if($valpr->item_id == $valpi->item_id && $valpr->unit_id == $valpi->unit_id && $valpr->unit_cost != $valpi->unit_cost) {
										$costitm += ($valpi->unit_cost * $valpi->quantity) - ($valpr->unit_cost * $valpr->quantity);
									}
								}
							}
							
							//Dr Account....
							DB::table('account_transaction')
										->where('id', $drTr->id)
										->update(['amount'		=> $costitm,
												  'fc_amount'	=> $costitm
												]);
							
							//Cr Account....
							DB::table('account_transaction')
										->where('voucher_type', 'PR')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['amount'		=> $costitm,
												  'fc_amount'	=> $costitm
												]);
												
						
							
						} else {
							
							$itm_pr = DB::table('item_log')->where('document_type', 'PR')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('item_id','unit_id','quantity','unit_cost','return_ref_id')->get();
							if($itm_pr)
								$itm_pi = DB::table('item_log')->where('document_type', 'PI')->where('document_id', $itm_pr[0]->return_ref_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('item_id','unit_id','quantity','unit_cost')->get(); //echo '<pre>';print_r($itm_pr);exit;
							$costitm = 0;					
							foreach($itm_pr as $valpr) {
								foreach($itm_pi as $valpi) {
									if($valpr->item_id == $valpi->item_id && $valpr->unit_id == $valpi->unit_id && $valpr->unit_cost != $valpi->unit_cost) {
										$costitm += ($valpi->unit_cost * $valpi->quantity) - ($valpr->unit_cost * $valpr->quantity);
									}
								}
							}
							
							DB::table('account_transaction')
										->insert(['voucher_type' => 'PR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $stock_ac,
												  'transaction_type'	=> 'Cr',
												  'amount'		=> $costitm,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
												  'fc_amount'	=> $costitm
												]);
												
							DB::table('account_transaction')
										->insert(['voucher_type' => 'PR',
												  'voucher_type_id'	=> $row->id,
												  'account_master_id' => $acdiff->account_id,
												  'transaction_type'	=> 'Dr',
												  'amount'		=> $costitm,
												  'status'		=> 1,
												  'created_at'	=> date('Y-m-d H:i:s'),
												  'created_by'	=> Auth::User()->id,
												  'reference'	=> $row->reference_no,
												  'invoice_date'	=> $row->voucher_date,
												  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
												  'fc_amount'	=> $costitm
												]);
						}
					} //END PURCHASE RETURN
					
					//TRANSFER OUT CALCULATING....... DEC17
					$sr = DB::table('stock_transferout')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					foreach($sr as $row) {
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'TO')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $acdiff->account_id)
									->where('transaction_type', 'Dr')
									->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'TO')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
							//UPDATE ACCOUNT TRANSACTION...
							$itm = DB::table('item_log')->where('document_type', 'TO')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('id','item_id','unit_id')->first();
							
							$itm_to = DB::table('item_log')->where('id', '<', $itm->id)->where('item_id', $itm->item_id)->where('unit_id', $itm->unit_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->where('cost_avg', '>', 0)
												->select('cost_avg')->first();
							
							//Dr Account....
							DB::table('account_transaction')
										->where('id', $drTr->id)
										->update(['amount'		=> $itm_to->cost_avg,
												  'fc_amount'	=> $itm_to->cost_avg
												]);
							
							//Cr Account....
							DB::table('account_transaction')
										->where('voucher_type', 'TO')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['amount'		=> $itm_to->cost_avg,
												  'fc_amount'	=> $itm_to->cost_avg
												]);
												
						
							
						} else {
							
							$itm = DB::table('item_log')->where('document_type', 'TO')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('id','item_id','unit_id')->first();
							
							$itm_to = DB::table('item_log')->where('id', '<', $itm->id)->where('item_id', $itm->item_id)->where('unit_id', $itm->unit_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->where('cost_avg', '>', 0)
												->select('cost_avg')->first();
							
							if($itm_to) {
								DB::table('account_transaction')
											->insert(['voucher_type' => 'TO',
													  'voucher_type_id'	=> $row->id,
													  'account_master_id' => $stock_ac,
													  'transaction_type'	=> 'Cr',
													  'amount'		=> $itm_to->cost_avg,
													  'status'		=> 1,
													  'created_at'	=> date('Y-m-d H:i:s'),
													  'created_by'	=> Auth::User()->id,
													  'reference'	=> $row->reference_no,
													  'invoice_date'	=> $row->voucher_date,
													  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
													  'fc_amount'	=> $itm_to->cost_avg,
													]);
													
								DB::table('account_transaction')
											->insert(['voucher_type' => 'TO',
													  'voucher_type_id'	=> $row->id,
													  'account_master_id' => $acdiff->account_id,
													  'transaction_type'	=> 'Dr',
													  'amount'		=> $itm_to->cost_avg,
													  'status'		=> 1,
													  'created_at'	=> date('Y-m-d H:i:s'),
													  'created_by'	=> Auth::User()->id,
													  'reference'	=> $row->reference_no,
													  'invoice_date'	=> $row->voucher_date,
													  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
													  'fc_amount'	=> $itm_to->cost_avg,
													]);
							}
						}
					} //END TRANSFER OUT
					
					//GOODS ISSUED CALCULATING....... DEC17
					$sr = DB::table('goods_issued')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
					foreach($sr as $row) {
						
						$drTr = DB::table('account_transaction')
									->where('voucher_type', 'GI')
									->where('voucher_type_id', $row->id)
									->where('account_master_id', $acdiff->account_id)
									->where('transaction_type', 'Dr')
									->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
									->select('id','status','deleted_at')
									->first();
									
						if($drTr) {
							
							if($drTr->status==0 && $drTr->deleted_at!='0000-00-00 00:00:00') {
							
								DB::table('account_transaction')->where('id', $drTr->id)->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
								
								DB::table('account_transaction')
										->where('voucher_type', 'GI')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
										
							}	
							
							//UPDATE ACCOUNT TRANSACTION...
							$itm = DB::table('item_log')->where('document_type', 'GI')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('id','item_id','unit_id')->first();
							
							$itm_gi = DB::table('item_log')->where('id', '<', $itm->id)->where('item_id', $itm->item_id)->where('unit_id', $itm->unit_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->where('cost_avg', '>', 0)
												->select('cost_avg')->first();
							
							//Dr Account....
							DB::table('account_transaction')
										->where('id', $drTr->id)
										->update(['amount'		=> $itm_gi->cost_avg,
												  'fc_amount'	=> $itm_gi->cost_avg
												]);
							
							//Cr Account....
							DB::table('account_transaction')
										->where('voucher_type', 'GI')
										->where('voucher_type_id', $row->id)
										->where('account_master_id', $stock_ac)
										->where('transaction_type', 'Cr')
										->where('is_paid', 5) //IDENTIFY COST DIFF TRANS...
										->update(['amount'		=> $itm_gi->cost_avg,
												  'fc_amount'	=> $itm_gi->cost_avg
												]);
												
						
							
						} else {
							
							$itm = DB::table('item_log')->where('document_type', 'GI')->where('document_id', $row->id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')
												->select('id','item_id','unit_id')->first();
							
							$itm_gi = DB::table('item_log')->where('id', '<', $itm->id)->where('item_id', $itm->item_id)->where('unit_id', $itm->unit_id)
												->where('status',1)->where('deleted_at', '0000-00-00 00:00:00')->where('cost_avg', '>', 0)
												->select('cost_avg')->first();
							
							if($itm_gi) {
								DB::table('account_transaction')
											->insert(['voucher_type' => 'GI',
													  'voucher_type_id'	=> $row->id,
													  'account_master_id' => $stock_ac,
													  'transaction_type'	=> 'Cr',
													  'amount'		=> $itm_gi->cost_avg,
													  'status'		=> 1,
													  'created_at'	=> date('Y-m-d H:i:s'),
													  'created_by'	=> Auth::User()->id,
													  'reference'	=> $row->voucher_no,
													  'invoice_date'	=> $row->voucher_date,
													  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
													  'fc_amount'	=> $itm_gi->cost_avg,
													]);
													
								DB::table('account_transaction')
											->insert(['voucher_type' => 'GI',
													  'voucher_type_id'	=> $row->id,
													  'account_master_id' => $acdiff->account_id,
													  'transaction_type'	=> 'Dr',
													  'amount'		=> $itm_gi->cost_avg,
													  'status'		=> 1,
													  'created_at'	=> date('Y-m-d H:i:s'),
													  'created_by'	=> Auth::User()->id,
													  'reference'	=> $row->voucher_no,
													  'invoice_date'	=> $row->voucher_date,
													  'is_paid'		=> 5, //IDENTIFY COST DIFF TRANS...
													  'fc_amount'	=> $itm_gi->cost_avg,
													]);
							}
						}
					} //END TRANSFER OUT
					
				}
			}
			
			Session::flash('message', 'Cost Account updated successfully');
			
		} else if($type=='PDCR') {
			
			$pdcs = DB::table('pdc_received')->where('deleted_at','0000-00-00 00:00:00')->select('id','status')->get();
			foreach($pdcs as $row) {
				$tran = DB::table('account_transaction')->where('voucher_type','DB')->where('voucher_type_id',$row->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
				if($tran)
					DB::table('pdc_received')->where('id',$row->id)->update(['status' => 1]);
				 else 
					DB::table('pdc_received')->where('id',$row->id)->update(['status' => 0]);
			}
			
			Session::flash('message', 'PDCR refresh successfully');
			
		} else if($type=='PDCI') {
			
			$pdcs = DB::table('pdc_issued')->where('deleted_at','0000-00-00 00:00:00')->select('id','status')->get();
			foreach($pdcs as $row) {
				$tran = DB::table('account_transaction')->where('voucher_type','CB')->where('voucher_type_id',$row->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
				if($tran)
					DB::table('pdc_issued')->where('id',$row->id)->update(['status' => 1]);
				 else 
					DB::table('pdc_issued')->where('id',$row->id)->update(['status' => 0]);
			}
			
			Session::flash('message', 'PDCI refresh successfully');
			
		} else if($type=='SDO') {
			
			$row = DB::table('parameter2')->where('keyname', 'mod_sdo_qty_update')->where('status',1)->select('is_active')->first();
			if($row->is_active==1) {
				$sdos = DB::table('supplier_do')
							->join('supplier_do_item','supplier_do_item.supplier_do_id','=','supplier_do.id')
							->where('supplier_do.status',1)->where('supplier_do.deleted_at','0000-00-00 00:00:00')
							->where('supplier_do_item.status',1)->where('supplier_do_item.deleted_at','0000-00-00 00:00:00')
							->select('supplier_do.voucher_date','supplier_do_item.supplier_do_id','supplier_do_item.item_id',
									 'supplier_do_item.unit_id','supplier_do_item.quantity','supplier_do_item.unit_price')
							->get();
							
				foreach($sdos as $sdo) {
					$chklog = DB::table('item_log')->where('document_type','SDO')->where('document_id', $sdo->supplier_do_id)->where('item_id',$sdo->item_id)->where('unit_id',$sdo->unit_id)->first();
					if($chklog) {
						DB::table('item_log')->where('document_type','SDO')->update(['status' => 1, 'deleted_at' => '0000-00-00 00:00:00']);
					} else {
						
						DB::table('item_log')->insert([
							 'document_type' => 'SDO',
							 'document_id'   => $sdo->supplier_do_id,
							 'item_id' 	  => $sdo->item_id,
							 'unit_id'    => $sdo->unit_id,
							 'quantity'   => $sdo->quantity,
							 'unit_cost'  => $sdo->unit_price,
							 'trtype'	  => 1,
							 'cur_quantity' => $sdo->quantity,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => $sdo->voucher_date,
							 'sale_reference' => $sdo->quantity
							]);
							
					}
				}
				
			} else {
				DB::table('item_log')->where('document_type','SDO')->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			}
			
			Session::flash('message', 'SDO refresh successfully');
			
		} else if($type=='PITrns') { //UPDATE ALL TRANSACTION OF PURCHASE INVOICE...
			
			$allPIs = DB::table('purchase_invoice')->get();
			foreach($allPIs as $row) {
				if($row->deleted_at=='0000-00-00 00:00:00') {
					
					$attributes['voucher_id'] = $row->id;
					$attributes['voucher_type'] = 'PI';
					$attributes['description'] = $row->description;
					$attributes['voucher_no'] = $row->voucher_no;
					$attributes['voucher_date'] = $row->voucher_date;
					$attributes['reference_no'] = $row->reference_no;
					$attributes['is_fc'] = $row->is_fc;
					$attributes['department_id'] = $row->department_id;
					$attributes['currency_rate'] = $row->currency_rate;
					
						//CALCULATE LINE TOTAL, NET TOTAL AND DISCOUNT ...
						if($row->is_fc==1)
							$discount = ($row->discount > 0)?($row->discount * $row->currency_rate):0;
						else
							$discount = $row->discount;
						
						//GETTING TAX TYPE..
						$piItem = DB::table('purchase_invoice_item')->where('purchase_invoice_id', $row->id)->where('tax_include',1)->select('id')->first();
						$taxtype = ($piItem)?1:0;
						$line_total = $row->subtotal; $net_amount = $row->net_amount;;
						if($taxtype==1 && $discount > 0) {
							$temp = $row->net_amount;
							$net_amount = $row->subtotal;
							$line_total = $temp + $discount;
						} else if($taxtype==0 && $discount > 0) {
							$line_total = $row->subtotal + $discount;
							$net_amount = $row->net_amount;
						}
					
					//Dr. Purchase A/c.  LNTOTAL
					$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0;
					$attributes['account_id'] = $row->account_master_id; $attributes['amount'] = $line_total; 
					if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
						//GETTING VAT A/C FROM SETTINGS..
						$vatAccounts = $this->getVatAccounts(($row->department_id==0)?null:$row->department_id);
						$vatAccount_id = $vatAccounts->collection_account;
						
						//CHECK VAT IMPORT OR NOT..
						if($row->is_import==1) {
							
							//Cr. VAT Input Import   VAT
							$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0; 
							$attributes['amount'] = $row->vat_amount; $attributes['account_id'] = $vatAccounts->vatoutput_import;
							$this->objUtility->UpdateAccountTransactions($attributes);
							
							$vatAccount_id = $vatAccounts->vatinput_import;
						}
						
						//Debit VAT Input   VAT
						$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0; 
						$attributes['amount'] = $row->vat_amount; $attributes['account_id'] = $vatAccount_id;
						if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
							//CHECK VAT IMPORT OR NOT..
							if($row->is_import==1) {
								$vatamt = ($row->currency_rate > 0)?round($row->vat_amount * $row->currency_rate,2):$row->vat_amount;
								$net_amount = $net_amount - $vatamt;
							}
							
							//Credit Supplier Accounting  NTAMT
							$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0; 
							$attributes['account_id'] = $row->supplier_id; $attributes['amount'] = $net_amount;
							if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
								
								//GETTING DISCOUNT A/C FROM SETTINGS.... 
								if(Session::get('department')==1) { 
									$vatrow = DB::table('department_accounts')->where('department_id', $row->department_id)->select('purdis_acid')->first();
									if($vatrow)
										$discountac_id = $vatrow->purdis_acid;
									else {
										$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
										$discountac_id = $vatrow->account_id;
									}
								} else {
									$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
									$discountac_id = $vatrow->account_id;
								}
								
								//Cr. Discount  DIS
								$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0; 
								$attributes['amount'] = $discount;  $attributes['account_id'] = $discountac_id; 
								$this->objUtility->UpdateAccountTransactions($attributes);
							}
						}
					}
					
				} else { //CHECK DELETED PIS AND TRANSACTIONS UPDATE AS DELETED...
					DB::table('purchase_invoice')->where('id', $row->id)->update(['status' => 0 ]);
					DB::table('account_transaction')->where('voucher_type', 'PI')->where('voucher_type_id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
			
			Session::flash('message', 'Purchase transactions update successfully');
			
		} else if($type=='SITrns') { //UPDATE ALL TRANSACTION OF SALES INVOICE...
			
			$allSIs = DB::table('sales_invoice')->get();
			foreach($allSIs as $row) {
				if($row->deleted_at=='0000-00-00 00:00:00') {
					
					$attributes['voucher_id'] = $row->id;
					$attributes['voucher_type'] = 'SI';
					$attributes['description'] = $row->description;
					$attributes['voucher_no'] = $row->voucher_no;
					$attributes['voucher_date'] = $row->voucher_date;
					$attributes['reference_no'] = $row->reference_no;
					$attributes['is_fc'] = $row->is_fc;
					$attributes['department_id'] = $row->department_id;
					$attributes['currency_rate'] = $row->currency_rate;
					
					//CALCULATE DISCOUNT ...
					$discount = ($row->is_fc == 1)?($row->discount * $row->currency_rate):$row->discount;
					
					//GETTING TAX TYPE..
					$siItem = DB::table('sales_invoice_item')->where('sales_invoice_id', $row->id)->where('tax_include',1)->select('id')->first();
					$taxtype = ($siItem)?1:0;
					$line_total = $row->subtotal; $net_amount = $row->net_total;
					if($taxtype==1 && $discount > 0) {
						$temp = $row->net_total;
						$net_amount = $row->subtotal;
						$line_total = $temp + $discount;
					} else if($taxtype==0 && $discount > 0) {
						$line_total = $row->subtotal + $discount;
						$net_amount = $row->net_total;
					}
				
				
				
					//Debit Customer Account  NTAMT
					$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0;
					$attributes['account_id'] = $row->dr_account_id; $attributes['amount'] = $net_amount; 
					if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
						//Credit Sales A/c.  LNTOTAL
						$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0;
						$attributes['account_id'] = $row->cr_account_id; $attributes['amount'] = $line_total; 
						if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
							//GETTING VAT A/C FROM SETTINGS..
							$vatAccounts = $this->getVatAccounts(($row->department_id==0)?null:$row->department_id);
							$vatAccount_id = $vatAccounts->payment_account;
							
							//Credit VAT output  VAT
							$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0; 
							$attributes['amount'] = $row->vat_amount; $attributes['account_id'] = $vatAccount_id;
							if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
							
								//GETTING DISCOUNT A/C FROM SETTINGS.... 
								if(Session::get('cost_accounting')==1 && Session::get('department')==1) { 
									$vatrow = DB::table('department_accounts')->where('department_id', $row->department_id)->select('saledis_acid')->first();
									if($vatrow)
										$discountac_id = $vatrow->saledis_acid;
									else {
										$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
										$discountac_id = $vatrow->account_id;
									}
									
								} else {
									$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
									$discountac_id = $vatrow->account_id;
								}
								
								//Dr. Discount  DIS
								$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0; 
								$attributes['amount'] = $discount;  $attributes['account_id'] = $discountac_id; 
								$this->objUtility->UpdateAccountTransactions($attributes);
									
								if(Session::get('cost_accounting')==1) { //COST ACCOUNTING METHOD....
									//Credit Stock A/c  STOCK  (TODO)....
									
									//Debit Cost of Sales  COSTOFSALE  (TODO)...
								}
							}
						
						}
					}
					
				} else { //CHECK DELETED PIS AND TRANSACTIONS UPDATE AS DELETED...
					DB::table('sales_invoice')->where('id', $row->id)->update(['status' => 0 ]);
					DB::table('account_transaction')->where('voucher_type', 'SI')->where('voucher_type_id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
			Session::flash('message', 'Sales transactions update successfully');
			
		} else if($type=='SSTrns') { //UPDATE ALL TRANSACTION OF SALES INVOICE...
			
			$allSIs = DB::table('sales_split')->get();
			foreach($allSIs as $row) {
				if($row->deleted_at=='0000-00-00 00:00:00') {
					
					$attributes['voucher_id'] = $row->id;
					$attributes['voucher_type'] = 'SI';
					$attributes['description'] = $row->description;
					$attributes['voucher_no'] = $row->voucher_no;
					$attributes['voucher_date'] = $row->voucher_date;
					$attributes['reference_no'] = $row->reference_no;
					$attributes['is_fc'] = $row->is_fc;
					$attributes['department_id'] = $row->department_id;
					$attributes['currency_rate'] = $row->currency_rate;
					
					//CALCULATE DISCOUNT ...
					$discount = ($row->is_fc == 1)?($row->discount * $row->currency_rate):$row->discount;
					
					//GETTING TAX TYPE..
					$siItem = DB::table('sales_invoice_item')->where('sales_invoice_id', $row->id)->where('tax_include',1)->select('id')->first();
					$taxtype = ($siItem)?1:0;
					$line_total = $row->subtotal; $net_amount = $row->net_total;
					if($taxtype==1 && $discount > 0) {
						$temp = $row->net_total;
						$net_amount = $row->subtotal;
						$line_total = $temp + $discount;
					} else if($taxtype==0 && $discount > 0) {
						$line_total = $row->subtotal + $discount;
						$net_amount = $row->net_total;
					}
				
					//Debit Customer Account  NTAMT
					$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0;
					$attributes['account_id'] = $row->dr_account_id; $attributes['amount'] = $net_amount; 
					if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
						//Credit Sales A/c.  LNTOTAL
						$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0;
						$attributes['account_id'] = $row->cr_account_id; $attributes['amount'] = $line_total; 
						if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
						
							//GETTING VAT A/C FROM SETTINGS..
							$vatAccounts = $this->getVatAccounts(($row->department_id==0)?null:$row->department_id);
							$vatAccount_id = $vatAccounts->payment_account;
							
							//Credit VAT output  VAT
							$attributes['type'] = 'Cr'; $attributes['tr_for'] = 0; 
							$attributes['amount'] = $row->vat_amount; $attributes['account_id'] = $vatAccount_id;
							if( $this->objUtility->UpdateAccountTransactions($attributes) ) {
							
								//GETTING DISCOUNT A/C FROM SETTINGS.... 
								if(Session::get('cost_accounting')==1 && Session::get('department')==1) { 
									$vatrow = DB::table('department_accounts')->where('department_id', $row->department_id)->select('saledis_acid')->first();
									if($vatrow)
										$discountac_id = $vatrow->saledis_acid;
									else {
										$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
										$discountac_id = $vatrow->account_id;
									}
									
								} else {
									$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Sales')->where('status', 1)->first();
									$discountac_id = $vatrow->account_id;
								}
								
								//Dr. Discount  DIS
								$attributes['type'] = 'Dr'; $attributes['tr_for'] = 0; 
								$attributes['amount'] = $discount;  $attributes['account_id'] = $discountac_id; 
								$this->objUtility->UpdateAccountTransactions($attributes);
									
								if(Session::get('cost_accounting')==1) { //COST ACCOUNTING METHOD....
									//Credit Stock A/c  STOCK  (TODO)....
									
									//Debit Cost of Sales  COSTOFSALE  (TODO)...
								}
							}
						
						}
					}
					
				} else { //CHECK DELETED PIS AND TRANSACTIONS UPDATE AS DELETED...
					DB::table('sales_invoice')->where('id', $row->id)->update(['status' => 0 ]);
					DB::table('account_transaction')->where('voucher_type', 'SI')->where('voucher_type_id', $row->id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
			Session::flash('message', 'Sales transactions update successfully');
		
		} else if($type=='remSI') {
			$ar = DB::select('SELECT t1.id FROM sales_invoice t1, sales_invoice t2 WHERE  t1.id > t2.id AND (t1.voucher_no = t2.voucher_no) GROUP BY t1.id');
			foreach($ar as $r) {
				DB::table('sales_invoice')->where('id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
				DB::table('sales_invoice_item')->where('sales_invoice_id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
				DB::table('account_transaction')->where('voucher_type','SI')->where('voucher_type_id',$r->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
				
			}
			Session::flash('message', 'Duplicate invoices removed successfully');
		}
		
		return redirect('utilities');
	}
	
	private function getItemQtyFromLog($item_id)
	{
		$qtyin = DB::table('item_log')->where('item_id', $item_id)->where('trtype',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
		
		$qtyout = DB::table('item_log')->where('item_id', $item_id)->where('trtype',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
		
		return ['in' => $qtyin, 'out' => $qtyout];
	}
	
	public function evalItemQuantity()
	{
		$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('itemmaster_id')->get();
		
		foreach($items as $item) {
			
			$qtyin = DB::table('item_log')->where('item_id', $item->itemmaster_id)->where('trtype',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
			
			$qtyout = DB::table('item_log')->where('item_id', $item->itemmaster_id)->where('trtype',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->sum('quantity');
			
			// $item->itemmaster_id.' '.$qty = $qtyin - $qtyout;exit;
		}
	}
	
	private function reEvalItemCostQuantity()
	{
		//$items = DB::table('itemmaster')->where('item_code','ck1')->select('id AS itemmaster_id')->get(); //FEB27
		$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('itemmaster_id')->get();
		
		foreach($items as $item) {
			
			$logs = DB::table('item_log')->where('item_id', $item->itemmaster_id)->where('status',1)
							->where('deleted_at','0000-00-00 00:00:00')->orderBy('voucher_date','ASC')->get();
			//echo '<pre>';print_r($logs);exit;
			if($logs) {
				$result = $this->reProcessLogs($logs);
				//UPDATING ITEM COST AVG.. pur_cost
				DB::table('item_unit')->where('itemmaster_id', $item->itemmaster_id)->update(['cost_avg' => $result['cost_avg'] ]);
			}
		}
		
		return true;
	}
	
	
	private function reProcessLogs($logs) 
	{
		$sale_ref = 0;
		foreach($logs as $log) {
			$cost_avg = 0;
			if($log->document_type=='OQ')
				$from_date = $log->voucher_date;
			else 
				$from_date = $this->acsettings->from_date;
			
			if($log->trtype==1) {
				$sale_ref += $log->quantity;
				$this->setQty($log, $sale_ref);
				
				$this->checkMinusQty($log);
				
				if($log->document_type=='SDO') { 
					$cost_avg = $this->getCostAvgonSdo($log,$other_cost=0,$from_date);
					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg, 'pur_cost' => $cost_avg]);
				} else {
					$cost_avg = $this->getCostAvgonPur($log,$other_cost=0,$from_date);
					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg]); //, 'pur_cost' => $cost_avg //MAR17
				}
			} else {
				$sale_ref = $sale_ref - $log->quantity;
				DB::table('item_log')->where('id', $log->id)
									->update([ 'sale_reference' => $sale_ref ]); //Log::info('S '.$log->id.' '.$sale_ref);
									
				$sale_cost = $this->getCost($log, $from_date);
				$cost_avg = $this->getCostAvgonSale($log,$other_cost=0,$from_date);
				
				$cost_avg = ($cost_avg==0 && $sale_cost!=0)?$sale_cost:$cost_avg; //Log::info($cost_avg.' '.$sale_cost);
				
				DB::table('item_log')->where('id', $log->id)
									->update([ 'cost_avg' => $cost_avg, 'sale_cost' => $sale_cost ]);
									
			}
			
		}
		
		return ['cost_avg' => $cost_avg];
	}
	
	private function getCostAvgonPur($row, $other_cost,$from_date )
	{
		$is_return = true;
			
		//CHECK WITH RETURNED STOCK...
		if($row->document_type=='SR') {
			$itmlogs = DB::table('item_log')
								   ->where('item_id',$row->item_id)
								   ->where('document_id',$row->return_ref_id)
								   ->where('status',1)
								   ->where('deleted_at','0000-00-00 00:00:00')
								   ->where('document_type','SI')
								   ->orderBy('id','ASC')->get();
								   
			$is_return = (count($itmlogs) > 0)?false:true;
		}
		
		if($is_return) {
			$itmlogs = DB::table('item_log')->where('item_id', $row->item_id)
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('id', '<=', $row->id)
										->where('deleted_at','0000-00-00 00:00:00')
										->whereBetween('voucher_date',[$from_date, $row->voucher_date])
										->select('cur_quantity AS quantity','pur_cost')//cur_quantity
										->get();
		}
										
		//echo '<pre>';print_r($itmlogs);exit;
		Log::info($from_date.' '.$row->voucher_date.print_r($itmlogs, true));

		$itmcost = $row->quantity * $row->pur_cost;
		$itmqty = $row->quantity;
		foreach($itmlogs as $log) {
			$itmcost += $log->quantity * $log->pur_cost;
			$itmqty += $log->quantity;
		}
		
		$cost_avg = ($itmqty > 0)?($itmcost / $itmqty):0 + $other_cost;
					
		return $cost_avg;
		
	}
	
	private function getCostAvgonSdo($row, $other_cost,$from_date )
	{
		$itmlogs = DB::table('item_log')->where('item_id', $row->item_id)
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->whereBetween('voucher_date',[$from_date, $row->voucher_date])
										//->where('voucher_date','<=',$row->voucher_date) //->where('id','<=',$row->id)
										->where('document_type', '!=', 'SDO')
										->select('cur_quantity AS quantity','pur_cost')//cur_quantity
										->get();
										
		//echo '<pre>';print_r($itmlogs);exit;
		//Log::info($from_date.print_r($itmlogs, true));

		$itmcost = 0;//$row->quantity * $row->pur_cost;
		$itmqty = 0;//$row->quantity;
		foreach($itmlogs as $log) {
			$itmcost += ($log->quantity * $log->pur_cost);
			$itmqty += $log->quantity;
		}
		
		$cost_avg = ($itmqty > 0)?($itmcost / $itmqty):0 + $other_cost;
		$cost = $row->pur_cost;
					
		return $cost_avg;
		
	}
	
	private function getCostAvgonSale($row,$other_cost=0,$from_date)
	{
		$type=0;
		$itmlogs = DB::table('item_log')->where('item_id', $row->item_id)
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->whereBetween('voucher_date',[$from_date, $row->voucher_date])
										//->where('voucher_date','<=',$row->voucher_date) 
										->select('document_type','unit_cost','cur_quantity','quantity','pur_cost') //cur_quantity
										->get(); 
		
		//Log::info($from_date.' '.$row->voucher_date.print_r($itmlogs, true));
		//echo '<pre>';print_r($itmlogs);exit;
		if($type==0) {								
			$itmcost = $itmqty = 0;
		} else {
			$itmcost = $row->quantity * $row->pur_cost;
			$itmqty = $row->quantity; //quantity
		}
		
		foreach($itmlogs as $log) {
			/* if($log->document_type=='SDO')
				$itmcost += 0;
			else */
				$itmcost += ($log->cur_quantity * (($log->pur_cost==0)?$log->unit_cost:$log->pur_cost));
			
			$itmqty += $log->cur_quantity;//cur_quantity
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
			//$cost = $row->pur_cost;
		} else {
			/* $res = DB::table('item_log')->where('item_id', $row->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			if($res)
				$cost_avg = $cost = $res->cost_avg;
			else //MAR6 */
				$cost_avg = 0;
		}

		return $cost_avg;
		
	}
	
	private function setQty($row, $sale_ref)
	{
		if($row->document_type=='SR') {
			$itmlog = DB::table('item_log')
								   ->where('item_id',$row->item_id)
								   ->where('document_id',$row->return_ref_id)
								   ->where('status',1)
								   ->where('deleted_at','0000-00-00 00:00:00')
								   ->where('document_type','SI')
								   ->select('cost_avg','pur_cost')
								   ->first();
			if($itmlog)					   
				DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => $itmlog->cost_avg, 'pur_cost' => $itmlog->pur_cost,'sale_reference' => $sale_ref ]);
		
		} else if($row->document_type=='SDO') {
			DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => 0, 'pur_cost' => 0,'sale_reference' => $sale_ref ]);
		} else 
			DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => DB::raw('unit_cost'), 'pur_cost' => DB::raw('unit_cost + other_cost'),'sale_reference' => $sale_ref ]); 
		
		//Log::info('P '.$row->id.' '.$sale_ref);
	}
	
		
	private function getCost($row, $from_date) //mar18
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $row->item_id)
										->where('is_baseqty', 1)->first();
		if($item) {
			
			$packing = 1;
			$qty = $row->quantity;
			$baseqty = ($qty * $packing);
			$is_return = true;
			
			//CHECK WITH RETURNED STOCK...
			if($row->document_type=='PR') {
				$stocks = DB::table('item_log')
									   ->where('item_id',$row->item_id)
									   ->where('document_id',$row->return_ref_id)
									   ->where('status',1)
									   ->where('deleted_at','0000-00-00 00:00:00')
									   ->where('document_type','PI')
									   ->orderBy('voucher_date','ASC')
									   ->get();
									   
				$is_return = (count($stocks) > 0)?false:true;
			} 
			
			if($is_return) {
				//UPDATE into ITEM STOCK LOG 
				$stocks = DB::table('item_log')->where('item_id',$row->item_id)
									   ->where('trtype', 1)
									   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
									   ->where('cur_quantity', '>', 0)
									   ->whereBetween('voucher_date',[$from_date, $row->voucher_date])
									   ->where('document_type','!=','SDO')
									   ->orderBy('voucher_date','ASC')
									   ->get();
			}	
			
			//echo '<pre>';print_r($stocks);exit;		
			//Log::info(print_r($stocks, true));
			//Log::info($from_date.' '.$row->voucher_date.print_r($stocks, true));
			
			$squantity = $row->quantity;
								   
			if(count($stocks) > 0) {
				$sale_cost = $sale_qty = 0;
				foreach($stocks as $stock) {
					
					if($stock->cur_quantity > 0) { 
						
						if($stock->cur_quantity >= $squantity) {
							$cur_quantity = $stock->cur_quantity - $squantity;
							$break = true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $squantity;  //FEB25
							$sale_qty += $squantity;
						
						} else if($stock->cur_quantity < $squantity) {
							$squantity = $squantity - $stock->cur_quantity;
							$cur_quantity = 0;
							$break = ($squantity > 0)?false:true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $stock->cur_quantity; //FEB25
							$sale_qty += $stock->cur_quantity;
							
						}
						
						//UPDATE ITEM STOCK LOG QUANTITY
						DB::table('item_log')->where('id', $stock->id)->update(['cur_quantity' => $cur_quantity ]);
						
						if($break)
							break;
						
					}
				}
				
				$itm_sale_cost = ($sale_qty > 0)?$sale_cost / $sale_qty:0;
				
				return $itm_sale_cost;
				
			} else {
				
				$stocks = DB::table('item_log')->where('item_id',$row->item_id)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->whereBetween('voucher_date',[$from_date, $row->voucher_date])
								   ->where('document_type','!=','SDO')
								   ->select('pur_cost')
								   ->orderBy('id','DESC')->first(); 
											
				//Log::info(print_r($stocks, true));
				if($stocks)				
					return $stocks->pur_cost;
				else { //MAR6
					
					$stocks = DB::table('item_log')->where('item_id',$row->item_id)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->where('voucher_date', '>', $row->voucher_date)
								   ->where('document_type','!=','SDO')
								   ->select('pur_cost')
								   ->orderBy('id','ASC')->first(); 
					//Log::info(print_r($stocks, true));			   
					return ($stocks)?$stocks->pur_cost:0;
					
				}
			}
		}
		
	}
	
	
	private function checkMinusQty($row)
	{
		$row = DB::table('item_log')->where('id', $row->id)->first();
		
		$itmlog = DB::table('item_log')
							   ->where('item_id',$row->item_id)
							   ->where('document_type','SI')
							   ->where('status',1)
							   ->where('deleted_at','0000-00-00 00:00:00')
							   ->where('voucher_date','<', $row->voucher_date)
							   ->where('sale_reference','<',0)
							   ->get();
		
		//Log::info($row->cur_quantity.' '.$row->voucher_date.print_r($itmlog, true));
		
		if(count($itmlog) > 0 && $row->quantity > 0) {
		    
			foreach($itmlog as $log) {
				
				//$cost_arr = $this->getCostAvgSI($log); //COST AVG GET FROM SALES LOG
		    	$mn_quantity = $row->cur_quantity - ($log->sale_reference * -1);
		    	if($mn_quantity >= 0) {
					$cost_avg = ($row->quantity * $row->pur_cost) / $row->quantity;
					$qty = $row->cur_quantity - $mn_quantity;
					//$sale_cost = ($cost_arr['sale_qty'] + ($qty * $row->pur_cost)) / ($qty + $cost_arr['sale_cost']); //CALCULATE COST WITH CURRENT ASSIGNED ITEM QTY AND COST
				    $sale_cost = ($log->pur_cost + $row->pur_cost) / 2;
					
					DB::table('item_log')->where('id',$log->id)->update(['cost_avg' => $cost_avg, 'sale_cost' => $row->pur_cost,'sale_reference' => 0]);
			    	DB::table('item_log')->where('id', $row->id)->update(['cur_quantity' => $mn_quantity ]); //purchae
					$rs = DB::table('item_log')->where('id',$log->id)->first();
					//Log::info(print_r($rs, true));
			    } else {
					$cost_avg = ($row->quantity * $row->pur_cost) / $row->quantity;
					$qty = $row->cur_quantity - $mn_quantity;
					
					//$sale_cost = ($cost_arr['sale_qty'] + ($qty * $row->pur_cost)) / ($qty + $cost_arr['sale_cost']); //CALCULATE COST WITH CURRENT ASSIGNED ITEM QTY AND COST
					$sale_cost = ($log->pur_cost + $row->pur_cost) / 2;
				    DB::table('item_log')->where('id',$log->id)->update(['cost_avg' => $cost_avg, 'sale_cost' => $row->pur_cost,'sale_reference' => $mn_quantity]);
				    DB::table('item_log')->where('id', $row->id)->update(['cur_quantity' => 0 ]);
					
					
			    }
		    }
		
		}	
		
	}
	
	
	private function getCostAvgSI($row) { //new
		
		$from_date = $this->acsettings->from_date;
		
		$stocks = DB::table('item_log')->where('item_id',$row->item_id)
									   ->where('trtype', 1)
									   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
									   ->where('cur_quantity', '>', 0)
									   ->whereBetween('voucher_date',[$from_date, $row->voucher_date])
									   ->where('document_type','!=','SDO')
									   ->orderBy('voucher_date','ASC')
									   ->get();
		
		$squantity = $row->quantity;
		$sale_cost = $sale_qty = 0;
		foreach($stocks as $stock) {
			
			if($stock->cur_quantity > 0) { 
				
				if($stock->cur_quantity >= $squantity) {
					$cur_quantity = $stock->cur_quantity - $squantity;
					$break = true;
					
					//CALCULATE ITEM COST for PROFIT ANALYSIS...
					$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $squantity;  //FEB25
					$sale_qty += $squantity;
				
				} else if($stock->cur_quantity < $squantity) {
					$squantity = $squantity - $stock->cur_quantity;
					$cur_quantity = 0;
					$break = ($squantity > 0)?false:true;
					
					//CALCULATE ITEM COST for PROFIT ANALYSIS...
					$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $stock->cur_quantity; //FEB25
					$sale_qty += $stock->cur_quantity;
					
				}
				
				if($break)
					break;
				
			}
		}
		
		return ['sale_cost' => $sale_cost, 'sale_qty' => $sale_qty];
	}
	
	private function groupItems($items)
	{
		$childs = array();
		foreach($items as $item)
			$childs[$item->item_id][] = $item;

		return $childs;
	}
	
	private function items_log() {
		
		return DB::table('item_log')
								  ->where('status',1)
								  ->where('deleted_at','0000-00-00 00:00:00')
								  ->select('item_log.*')
								  ->get();
		
		/* return DB::table('item_unit')->where('item_unit.is_baseqty',1)
								  ->join('item_log','item_log.item_id','=','item_unit.itemmaster_id')
								  ->where('item_unit.status',1)
								  ->where('item_unit.deleted_at','0000-00-00 00:00:00')
								  ->where('item_log.status',1)
								  ->where('item_log.deleted_at','0000-00-00 00:00:00')
								  ->select('item_unit.id AS iuid','item_unit.itemmaster_id','item_log.*')
								  ->get(); */
	}
	
	public function itemLogOBAdd()
	{
		$items = DB::table('item_unit')->where('is_baseqty',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		foreach($items as $row) {
			DB::table('item_log')
				->insert([ 'document_type' => 'OQ',
						   'item_id' => $row->itemmaster_id,
						   'unit_id' => $row->unit_id,
						   'quantity' => $row->opn_quantity,
						   'unit_cost' => $row->opn_cost,
						   'trtype' => 1,
						   'cur_quantity' => $row->opn_quantity,
						   'cost_avg' => $row->opn_cost,
						   'pur_cost' => $row->opn_cost,
						   'sale_cost' => $row->sell_price,
						   'packing' => 1,
						   'status' => 1,
						   'created_at' => date('Y-m-d H:i:s'),
						   'created_by' => 1,
						   'voucher_date' => '2019-01-01'
					
			]);
		
		}
		
		//echo '<pre>';print_r($items);exit;
	}
	
	public function itemLogUnitReset()
	{
		$qry = DB::table('item_unit')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		foreach($qry as $row) {
			DB::table('item_unit')->where('id', $row->id)->update(['cur_quantity' => 0, 'received_qty' => 0, 'issued_qty' => 0]);
		}
	}
	
	public function itemLogINVAdd()
	{
		DB::beginTransaction();
			try {
				$qry1 = DB::table('purchase_invoice')->where('purchase_invoice.status',1)
							->where('purchase_invoice.deleted_at','0000-00-00 00:00:00')
							->join('purchase_invoice_item','purchase_invoice_item.purchase_invoice_id','=','purchase_invoice.id')
							->where('purchase_invoice_item.deleted_at','0000-00-00 00:00:00')
							->where('purchase_invoice_item.status',1)
							->select('purchase_invoice.id','purchase_invoice.voucher_date AS invoice_date',DB::raw('"PI" AS type'),
									'purchase_invoice_item.item_id','purchase_invoice_item.unit_id','purchase_invoice_item.quantity',
									'purchase_invoice_item.unit_price',DB::raw('"1" AS trtype'));
				
				$qry2 = DB::table('sales_invoice')->where('sales_invoice.status',1)
							->where('sales_invoice.deleted_at','0000-00-00 00:00:00')
							->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_invoice.id')
							->where('sales_invoice_item.deleted_at','0000-00-00 00:00:00')
							->where('sales_invoice_item.status',1)
							->select('sales_invoice.id','sales_invoice.voucher_date AS invoice_date',DB::raw('"SI" AS type'),
									'sales_invoice_item.item_id','sales_invoice_item.unit_id','sales_invoice_item.quantity',
									'sales_invoice_item.unit_price',DB::raw('"0" AS trtype'));
				
				$qry3 = DB::table('purchase_return')->where('purchase_return.status',1)
							->where('purchase_return.deleted_at','0000-00-00 00:00:00')
							->join('purchase_invoice_item','purchase_invoice_item.purchase_invoice_id','=','purchase_return.id')
							->where('purchase_invoice_item.deleted_at','0000-00-00 00:00:00')
							->where('purchase_invoice_item.status',1)
							->select('purchase_return.id','purchase_return.voucher_date AS invoice_date',DB::raw('"PR" AS type'),
									'purchase_invoice_item.item_id','purchase_invoice_item.unit_id','purchase_invoice_item.quantity',
									'purchase_invoice_item.unit_price',DB::raw('"0" AS trtype'));
				
				$qry4 = DB::table('sales_return')->where('sales_return.status',1)
							->where('sales_return.deleted_at','0000-00-00 00:00:00')
							->join('sales_invoice_item','sales_invoice_item.sales_invoice_id','=','sales_return.id')
							->where('sales_invoice_item.deleted_at','0000-00-00 00:00:00')
							->where('sales_invoice_item.status',1)
							->select('sales_return.id','sales_return.voucher_date AS invoice_date',DB::raw('"SR" AS type'),
									'sales_invoice_item.item_id','sales_invoice_item.unit_id','sales_invoice_item.quantity',
									'sales_invoice_item.unit_price',DB::raw('"1" AS trtype'));
									
				$invs = $qry2->union($qry1)->union($qry3)->union($qry4)->orderBy('invoice_date')->get();
				//echo count($invs).'<pre>';print_r($invs);exit;
				
				//DB::table('item_unit')->update(['cur_quantity' => 0, 'received_qty' => 0, 'issued_qty' => 0]);
				
				foreach($invs as $row) {
					
					if($row->type=='PI' || $row->type=='SR') {
						$cost_avg = $this->updateLastPurchaseCostAndCostAvg($row,1);
						$pur_cost = $row->unit_price;
						$sale_cost = 0;
						$this->updateItemQuantity($row);
					} else if($row->type=='SI' || $row->type=='PR') {
						$pur_cost = $this->updateItemQuantitySales($row);
						$cost_avg = $this->updateLastPurchaseCostAndCostAvg($row,0);
						$sale_cost = $pur_cost;
					}
					
					DB::table('item_log')
						->insert([ 'document_type' => $row->type,
								   'document_id' => $row->id,
								   'item_id' => $row->item_id,
								   'unit_id' => $row->unit_id,
								   'quantity' => $row->quantity,
								   'unit_cost' => $row->unit_price,
								   'trtype' => $row->trtype,
								   'cur_quantity' => $row->quantity,
								   'cost_avg' => $cost_avg,
								   'pur_cost' => $pur_cost,
								   'sale_cost' => $sale_cost,
								   'packing' => 1,
								   'status' => 1,
								   'created_at' => date('Y-m-d H:i:s'),
								   'created_by' => 1,
								   'voucher_date' => $row->invoice_date
							
					]);
				}
				
				//echo '<pre>';print_r($invs);exit;
			DB::commit();
			//return true; 
			
		 } catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			//return false;
		}
	}
	
	
	private function updateItemQuantity($attributes)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes->item_id)
									  ->where('is_baseqty', 1)->first();
									  
		if($item) {
			$qty = $attributes->quantity;
			$baseqty = $qty;
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => $item->cur_quantity + $baseqty,
						   'received_qty' => DB::raw('received_qty + '.$baseqty) ]);
							
		}
									  
		//return true;
	}
	
	public function updateItemQuantitySales($attributes)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes->item_id)
										->where('is_baseqty', 1)->first();
		if($item) {
			//$qty = $attributes['quantity'][$key];
			$packing = 1;
			$qty = $attributes->quantity;
			$baseqty = ($qty * $packing);
			
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => DB::raw('cur_quantity - '.$baseqty),
							'issued_qty' => DB::raw('issued_qty + '.$baseqty) ]);
							
			//UPDATE into ITEM STOCK LOG 
			$stocks = DB::table('item_log')->where('item_id',$attributes->item_id)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->where('cur_quantity', '>', 0)
								   ->orderBy('id','ASC')->get();
			//echo '<pre>';print_r($stocks);exit;					   
			$squantity = $attributes->quantity;
								   
			if(count($stocks) > 0) {
				$sale_cost = $sale_qty = 0;
				foreach($stocks as $stock) {
					
					if($stock->cur_quantity > 0) { 
						
						if($stock->cur_quantity >= $squantity) {
							$cur_quantity = $stock->cur_quantity - $squantity;
							$break = true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $squantity; //FEB25
							$sale_qty += $squantity;
						
						} else if($stock->cur_quantity < $squantity) {
							$squantity = $squantity - $stock->cur_quantity;
							$cur_quantity = 0;
							$break = ($squantity > 0)?false:true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += (($stock->pur_cost==0)?$stock->unit_cost:$stock->pur_cost) * $stock->cur_quantity; //FEB25
							$sale_qty += $stock->cur_quantity;
							
						}
						
						//UPDATE ITEM STOCK LOG QUANTITY
						DB::table('item_log')->where('id', $stock->id)->update(['cur_quantity' => $cur_quantity ]);
						
						if($break)
							break;
						
					}
				}
				
				$itm_sale_cost = $sale_cost / $sale_qty;
				
				return $itm_sale_cost;
				
			} else {
				
				return 0;
				/* $stocks = DB::table('item_log')->where('item_id',$attributes->item_id)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->select('pur_cost')
								   ->orderBy('id','DESC')->first(); //echo '<pre>';print_r($stocks);exit;
								   
				return $stocks->pur_cost; */
			}
		}
		
	}
	
	public function updateLastPurchaseCostAndCostAvg($attributes,$type)
	{
		
		$itmlogs = DB::table('item_log')->where('item_id', $attributes->item_id)
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('cur_quantity','pur_cost')
										->get(); //echo '<pre>';print_r($itmlogs);exit;
		if($type==0) {								
			$itmcost = $itmqty = 0;
		} else {
			$itmcost = $attributes->quantity * $attributes->unit_price;
			$itmqty = $attributes->quantity;
		}
		
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round( ($itmcost / $itmqty), 3);
			$cost = $attributes->unit_price;
		} else {
			$row = DB::table('item_log')->where('item_id', $attributes->item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			$cost_avg = $cost = $row->cost_avg;
		}
		
		DB::table('item_unit')
				->where('itemmaster_id', $attributes->item_id)
				->update([//'last_purchase_cost' => $cost,
						  //'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	public function statementUpdate()
	{
		DB::beginTransaction();
		try {
			$rvtr = DB::table('receipt_voucher_tr')
						->join('sales_invoice','sales_invoice.id','=','receipt_voucher_tr.sales_invoice_id')
						->where('receipt_voucher_tr.receipt_voucher_entry_id',819)
						->select('receipt_voucher_tr.*','sales_invoice.voucher_no')
						->get();
						
			$trs = DB::table('account_transaction')->where('voucher_type','RV')->where('voucher_type_id',819)->where('account_master_id',290)->get();
			
			//echo '<pre>';print_r($trs);exit;
			foreach($rvtr as $k => $row) { 
				echo $id = $trs[$k]->id;
				DB::table('account_transaction')->where('id', $id)
						->update(['amount' => $row->assign_amount, 'reference_from' => $row->voucher_no]);
						
			} //echo '<pre>';print_r($r);exit;
			DB::commit();
			echo 'updated';
			//return true; 
			
		 } catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			//return false;
		}
	}
	
	public function item_log_entry()
	{
		$items = DB::table('item_unit')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_baseqty',1)->select('itemmaster_id','unit_id')->get();
		foreach($items as $item) {
			$log = DB::table('item_log')->where('item_id', $item->itemmaster_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
			if($log==0) {
				DB::table('item_log')->insert([
					'document_type' => 'OQ',
					'item_id' => $item->itemmaster_id,
					'unit_id' => $item->unit_id,
					'trtype' => 1,
					'packing' => 1,
					'status' => 1
				]);
			}
		}
	}
	
	public function ac_transaction_clear() {
		$res = DB::table('account_transaction')->where('voucher_type','SI')->where('account_master_id',0)->where('amount',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','voucher_type_id')->get();
		foreach($res as $row) {
			$inv = DB::table('sales_invoice')->where('id', $row->voucher_type_id)->select('customer_id','dr_account_id','net_total')->first();
			if($inv->dr_account_id==0)
				DB::table('sales_invoice')->where('id',$row->voucher_type_id)->update(['dr_account_id' => $inv->customer_id]);
			
			DB::table('account_transaction')->where('id', $row->id)->update(['account_master_id' => $inv->customer_id, 'amount' => $inv->net_total]);
		}
		//echo '<pre>';print_r($res);exit;
	}	
	
	public function update_pi_ref()
	{
		$refs = DB::table('purchase_invoice')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('voucher_no','reference_no','id')->get();
		foreach($refs as $ref) {
			DB::table('account_transaction')->where('voucher_type','PI')->where('voucher_type_id',$ref->id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->update(['reference' => $ref->voucher_no,'reference_from' => $ref->reference_no]);
		}
	}
	
	public function update_pv_ref()
	{
		$refs = DB::table('payment_voucher_tr')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('bill_type','PI')->select('purchase_invoice_id','payment_voucher_entry_id')->get();
		//echo '<pre>';print_r($refs);exit;
		foreach($refs as $ref) {
			$res = DB::table('purchase_invoice')->where('id', $ref->purchase_invoice_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference_no')->first();
				if($res)		
					DB::table('account_transaction')->where('voucher_type','PV')->where('voucher_type_id', $ref->payment_voucher_entry_id)->update(['reference_from' => $res->reference_no]);
		}
		
		$pvrefs = DB::table('account_transaction')->where('voucher_type','PV')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','reference_from')->get();
		foreach($pvrefs as $pvref) {
			$rec = DB::table('purchase_invoice')->where('voucher_no', $pvref->reference_from)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('reference_no')->first();
			if($rec) 
				DB::table('account_transaction')->where('id',$pvref->id)->update(['reference_from' => $rec->reference_no]);
		}
	}
	
	public function ob_date_active()
	{
		$dt = DB::table('parameter1')->select('from_date')->first();
		DB::table('account_transaction')->where('voucher_type','OB')->where('invoice_date','0000-00-00')->update(['invoice_date' => $dt->from_date]);
		
		DB::table('account_transaction')->where('voucher_type','OB')->where('invoice_date','>','2019-01-01')->update(['invoice_date' => $dt->from_date]);
	}
	public function updateAccMaster($type)
	{ //echo '<pre>';print_r(Input::all());exit;
		if($type=='CB') {
			//REMOVE duplicate entries...
			DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info)');
			
			$result = $this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility($type)) ); //echo '<pre>';print_r($result);exit;
			Session::flash('message', 'Closing balance recalculated and updated successfully');
			return redirect('account_enquiry');
		
		}

	}
	public function updateItemMasterStock($type)
	{ 
		if($type=='stock') {
			
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
					//echo '<pre>';print_r($itemlog); exit;
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
			
			Session::flash('message', 'Stock updated successfully');
			return redirect('itemenquiry');
			
		}


	}
	
	protected function groupLoc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['location_id']][] = $item;

			return $childs;
	}
	
	protected function groupItemLoc($results)
	{
		$childs = array();
		foreach($results as $k => $result)
			foreach($result as $item)
			$childs[$k][$item['item_id']][] = $item;

		return $childs;
	}
	
	protected function sumLoc($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			foreach($result as $rows) {
				$in = $out = $quantity = 0;
				foreach($rows as $row) {
					$item_id = $row['id'];
					$itemcode = $row['item_code'];
					$description = $row['description'];
					$unit = $row['unit_id']; 
					$cost_avg = $row['cost_avg'];
					$opn_cost = $row['pur_cost']; //todo
					$opn_quantity = 0;//$row['opn_quantity']; todo
					$location_id = $row['location_id'];
					$lcode = $row['code'];
					$lname = $row['name'];
					if($row['trtype']=='0')
						$out += $row['quantity'];
					else
						$in += $row['quantity'];
					
				}
				$quantity = $in - $out;
				
				$total = $quantity * $cost_avg;
			
			$arrSummarry[$location_id][] = ['itemcode' => $itemcode, 
							  'unit' => $unit,
							  'quantity' => $quantity, 
							  'cost_avg' => $cost_avg,
							  'description' => $description,
							  'opn_cost' => $opn_cost,
							  'total' => $total,
							  'opn_quantity' => $opn_quantity,
							  'code' => $lcode,
							  'name' => $lname,
							  'item_id' => $item_id
							  ];
			}
				
			

		}
		return $arrSummarry;
	}
	
	public function clearDB()
	{
		DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type`!='OB'");
		DB::statement("UPDATE `account_setting` set `voucher_no`=100");
		DB::statement("UPDATE `account_master` set `cl_balance`=0,`op_balance`=0,`fy_balance`=0");
		DB::statement("UPDATE `account_transaction` set `amount`=0");
		DB::statement("DELETE FROM `account_transaction` WHERE `account_master_id` IN(SELECT id FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND (`category` ='CUSTOMER' OR `category` ='SUPPLIER') )");
		DB::statement("DELETE FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND (`category` ='CUSTOMER' OR `category` ='SUPPLIER')");
		DB::statement("DELETE FROM `jobmaster` WHERE is_salary_job = 0");
		DB::statement("UPDATE `voucher_no` set `no`=100, `autoincrement`=1");
		DB::statement("TRUNCATE TABLE `assets_issued`");
		DB::statement("TRUNCATE TABLE `cheque`");
		DB::statement("TRUNCATE TABLE `credit_note`");
		DB::statement("TRUNCATE TABLE `credit_note_entry`");
		DB::statement("TRUNCATE TABLE `debit_note`");
		DB::statement("TRUNCATE TABLE `debit_note_entry`");
		DB::statement("TRUNCATE TABLE `customer_do`");
		DB::statement("TRUNCATE TABLE `customer_do_item`");
		DB::statement("TRUNCATE TABLE `customer_receipt`");
		DB::statement("TRUNCATE TABLE `customer_receipt_tr`");
		DB::statement("TRUNCATE TABLE `doc_department`");
		DB::statement("TRUNCATE TABLE `document_master`");
		DB::statement("TRUNCATE TABLE `employee`");
		DB::statement("TRUNCATE TABLE `expiry_docs`");
		DB::statement("TRUNCATE TABLE `goods_issued`");
		DB::statement("TRUNCATE TABLE `goods_issued_item`");
		DB::statement("TRUNCATE TABLE `goods_return`");
		DB::statement("TRUNCATE TABLE `goods_return_item`");
		DB::statement("TRUNCATE TABLE `groupcat`");
		DB::statement("TRUNCATE TABLE `header_footer`");
		DB::statement("TRUNCATE TABLE `itemmaster`");
		DB::statement("TRUNCATE TABLE `item_description`");
		DB::statement("TRUNCATE TABLE `item_location`");
		DB::statement("TRUNCATE TABLE `item_location_pi`");
		DB::statement("TRUNCATE TABLE `item_location_pr`");
		DB::statement("TRUNCATE TABLE `item_location_si`");
		DB::statement("TRUNCATE TABLE `item_location_sr`");
		DB::statement("TRUNCATE TABLE `item_log`");
		DB::statement("TRUNCATE TABLE `item_sale_log`");
		DB::statement("TRUNCATE TABLE `item_stock`");
		DB::statement("TRUNCATE TABLE `item_unit`");
		DB::statement("TRUNCATE TABLE `jobestimate_details`");
		DB::statement("TRUNCATE TABLE `jobinvoice_details`");
		DB::statement("TRUNCATE TABLE `joborder_details`");
		DB::statement("TRUNCATE TABLE `jobtype`");
		DB::statement("TRUNCATE TABLE `journal`");
		DB::statement("TRUNCATE TABLE `journal_entry`");
		DB::statement("TRUNCATE TABLE `location_transfer`");
		DB::statement("TRUNCATE TABLE `location_transfer_item`");
		DB::statement("TRUNCATE TABLE `onleave`");
		DB::statement("TRUNCATE TABLE `opening_balance_tr`");
		DB::statement("TRUNCATE TABLE `other_payment`");
		DB::statement("TRUNCATE TABLE `other_payment_tr`");
		DB::statement("TRUNCATE TABLE `other_receipt`");
		DB::statement("TRUNCATE TABLE `other_receipt_tr`");
		DB::statement("TRUNCATE TABLE `payment_voucher`");
		DB::statement("TRUNCATE TABLE `payment_voucher_entry`");
		DB::statement("TRUNCATE TABLE `payment_voucher_tr`");
		DB::statement("TRUNCATE TABLE `pdc_issued`");
		DB::statement("TRUNCATE TABLE `pdc_received`");
		DB::statement("TRUNCATE TABLE `petty_cash`");
		DB::statement("TRUNCATE TABLE `petty_cash_entry`");
		DB::statement("TRUNCATE TABLE `pi_other_cost`");
		DB::statement("TRUNCATE TABLE `po_other_cost`");
		DB::statement("TRUNCATE TABLE `purchase_invoice`");
		DB::statement("TRUNCATE TABLE `purchase_invoice_item`");
		DB::statement("TRUNCATE TABLE `purchase_order`");
		DB::statement("TRUNCATE TABLE `purchase_order_item`");
		DB::statement("TRUNCATE TABLE `purchase_order_info`");
		DB::statement("TRUNCATE TABLE `purchase_return`");
		DB::statement("TRUNCATE TABLE `purchase_return_item`");
		DB::statement("TRUNCATE TABLE `quotation`");
		DB::statement("TRUNCATE TABLE `quotation_info`");
		DB::statement("TRUNCATE TABLE `quotation_item`");
		DB::statement("TRUNCATE TABLE `quotation_sales`");
		DB::statement("TRUNCATE TABLE `quotation_sales_info`");
		DB::statement("TRUNCATE TABLE `quotation_sales_item`");
		DB::statement("TRUNCATE TABLE `receipt_voucher`");
		DB::statement("TRUNCATE TABLE `receipt_voucher_entry`");
		DB::statement("TRUNCATE TABLE `receipt_voucher_tr`");
		DB::statement("TRUNCATE TABLE `resign`");
		DB::statement("TRUNCATE TABLE `salesman`");
		DB::statement("TRUNCATE TABLE `sales_invoice`");
		DB::statement("TRUNCATE TABLE `sales_invoice_item`");
		DB::statement("TRUNCATE TABLE `sales_order`");
		DB::statement("TRUNCATE TABLE `sales_order_info`");
		DB::statement("TRUNCATE TABLE `sales_order_item`");
		DB::statement("TRUNCATE TABLE `sales_return`");
		DB::statement("TRUNCATE TABLE `sales_return_item`");
		DB::statement("TRUNCATE TABLE `stock_transferin`");
		DB::statement("TRUNCATE TABLE `stock_transferin_item`");
		DB::statement("TRUNCATE TABLE `stock_transferout`");
		DB::statement("TRUNCATE TABLE `stock_transferout_item`");
		DB::statement("TRUNCATE TABLE `supplier_do`");
		DB::statement("TRUNCATE TABLE `supplier_do_info`");
		DB::statement("TRUNCATE TABLE `supplier_do_item`");
		DB::statement("TRUNCATE TABLE `supplier_payment`");
		DB::statement("TRUNCATE TABLE `supplier_payment`");
		DB::statement("TRUNCATE TABLE `terms`");
		DB::statement("TRUNCATE TABLE `vehicle`");
		DB::statement("TRUNCATE TABLE `wage_entry`");
		DB::statement("TRUNCATE TABLE `wage_entry_items`");
		DB::statement("TRUNCATE TABLE `wage_entry_job`");
		DB::statement("TRUNCATE TABLE `wage_entry_others`");
		
		Session::flash('message', 'Data has been cleared successfully');
		return redirect('utilities');
	}
	
	private function getVatAccounts($department_id=null) {
		
		if(Session::get('department')==1 && $department_id!=null) {
			$vatdept = DB::table('vat_department')->where('department_id', $department_id)->first();
			$vatacs = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
			if(!$vatdept)
				return $vatacs;
			else {
				$vatres = (object)[ 'id'				=> $vatdept->id,
									'vatmaster_id'		=> $vatdept->vatmaster_id,
									'department_id'		=> $vatdept->department_id,
									'collection_account' => ($vatdept->collection_account=='')?$vatacs->collection_account:$vatdept->collection_account,
									'payment_account' => ($vatdept->payment_account=='')?$vatacs->payment_account:$vatdept->payment_account,
									'expense_account' => ($vatdept->expense_account=='')?$vatacs->expense_account:$vatdept->expense_account,
									'vatinput_import' => ($vatdept->vatinput_import=='')?$vatacs->vatinput_import:$vatdept->vatinput_import,
									'vatoutput_import' => ($vatdept->vatoutput_import=='')?$vatacs->vatoutput_import:$vatdept->vatoutput_import
								  ];
				
				return $vatres;
			}
			
		} else {
			return DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->first();
		}
	}
	
	private function isValidAccount($account_id) {
		
		if($account_id > 0 ) {
			$acdata = DB::table('account_master')->where('id',$account_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id')->first();
			if($acdata)
				return true;
			else
				return false;
		} else 
			return false;
	}
	
	
	public function checkAccounts() {
		
		//CHECK VAT ACCOUNTS..
		$vatAccounts = DB::table('vat_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$rowArr = $vatAlertArr = null;
		foreach($vatAccounts as $row){
			if(!$this->isValidAccount($row->collection_account))
				$rowArr[] = 'Collection Account';
			
			if(!$this->isValidAccount($row->payment_account))
				$rowArr[] = 'Payment Account';
			
			if(!$this->isValidAccount($row->expense_account))
				$rowArr[] = 'Expense Account';
			
			if(!$this->isValidAccount($row->vatinput_import))
				$rowArr[] = 'Input Import Account';
			
			if(!$this->isValidAccount($row->vatoutput_import))
				$rowArr[] = 'Output Import Account';
			
			if($rowArr)
				$vatAlertArr[] = ['name' => $row->name, 'details' => $rowArr];
		}
		
		//CHECK VAT ACCOUNTS DEPARTMENTS ..
		$vatAccountsDpt = DB::table('vat_master')
								->join('vat_department AS V','V.vatmaster_id', '=', 'vat_master.id')
								->join('department AS D','D.id', '=', 'V.department_id')
								->where('vat_master.status',1)->where('vat_master.deleted_at','0000-00-00 00:00:00')
								->select('V.collection_account','V.payment_account','V.expense_account','V.vatinput_import','V.vatoutput_import',
										'D.name as department','vat_master.name')
								->get();
								
		$rowArrD = $vatAlertArrD = null;
		foreach($vatAccountsDpt as $row){
			if(!$this->isValidAccount($row->collection_account))
				$rowArrD[] = 'Collection Account';
			
			if(!$this->isValidAccount($row->payment_account))
				$rowArrD[] = 'Payment Account';
			
			if(!$this->isValidAccount($row->expense_account))
				$rowArrD[] = 'Expense Account';
			
			if(!$this->isValidAccount($row->vatinput_import))
				$rowArrD[] = 'Input Import Account';
			
			if(!$this->isValidAccount($row->vatoutput_import))
				$rowArrD[] = 'Output Import Account';
			
			if($rowArrD)
				$vatAlertArrD[] = (object)['name' => $row->name, 'department' => $row->department, 'details' => (object)$rowArrD];
		}	

		//OTHER ACCOUNTS SETTINGS......
		$othrAcnts = DB::table('other_account_setting')->get();
		$rowArr = $othrAcntAlertArr = null;
		foreach($othrAcnts as $row){
			if(!$this->isValidAccount($row->account_id))
				$rowArr[] = $row->account_setting_name;
			
			if($rowArr)
				$othrAcntAlertArr[] = (object)['name' => 'Other Account Settings', 'details' => (object)$rowArr];
		}	
		//echo '<pre>';print_r($othrAcntAlertArr);
		
		
		//DEPARTMENT OTHER ACCOUNTS SETTINGS......
		$othrAcntsD = DB::table('department_accounts')->join('department AS D','D.id', '=', 'department_accounts.department_id')
								->select('D.name as department','department_accounts.*')->get();
		$rowArr = $othrAcntAlertArr = null;
		foreach($othrAcntsD as $row){
			if(!$this->isValidAccount($row->stock_acid))
				$rowArr[] = 'Stock Account';
			
			if(!$this->isValidAccount($row->cost_acid))
				$rowArr[] = 'Cost of Sales Account';
			
			if(!$this->isValidAccount($row->costdif_acid))
				$rowArr[] = 'Cost Difference Account';
			
			if(!$this->isValidAccount($row->purdis_acid))
				$rowArr[] = 'Discount in Purchase Account';
			
			if(!$this->isValidAccount($row->saledis_acid))
				$rowArr[] = 'Discount in Sales Account';
			
			if(!$this->isValidAccount($row->stock_excess_acid))
				$rowArr[] = 'Stock Excess Account';
			
			if(!$this->isValidAccount($row->stock_shortage_acid))
				$rowArr[] = 'Stock Storage Account';
			
			if($rowArr)
				$othrAcntAlertArr[] = (object)['name' => 'Other Account Settings(Department)', 'department' => $row->department, 'details' => (object)$rowArr];
		}	
		
		//ACCOUNTS SETTINGS...... ###### TO DO###
		$othrAcnts = DB::table('account_setting')->where('status',1)->get();
		
		//TRANSACTION POSTED BUT ACCOUNT NOT EXISTS....
		$delTrnsAccounts = DB::table('account_master')->join('account_transaction AS AT','AT.account_master_id', '=', 'account_master.id')
									->where('AT.deleted_at','0000-00-00 00:00:00')->where('AT.status',1)
									->where('account_master.deleted_at', '!=', '0000-00-00 00:00:00')
									->select('master_name','account_id','AT.voucher_type','AT.reference')
									->get();
		
		//echo '<pre>';print_r($vatAlertArr);exit;
		
		
		return view('body.utilities.check-accounts')
							->withVats($vatAlertArr)
							->withVatsdept($vatAlertArrD)
							->withOtherac($othrAcntAlertArr)
							->withOtherac($othrAcntAlertArr);
	}
	
	
	
}

//SELECT t1.voucher_type_id,t2.voucher_type_id FROM account_transaction t1, account_transaction t2 WHERE  (t1.voucher_type='SS' AND t2.voucher_type='SS' ) AND (t1.voucher_type_id = t2.voucher_type_id) AND (t1.invoice_date != t2.invoice_date)
