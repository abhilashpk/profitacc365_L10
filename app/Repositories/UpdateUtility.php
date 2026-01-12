<?php
declare(strict_types=1);
namespace App\Repositories;

use DB;
use Storage;
use Log;
use Auth;
use Carbon\Carbon;

class UpdateUtility
{
	
	public function tallyClosingBalance($id)
	{
		$this->updateAccountTally( $this->groupAccount($this->updateUtilityById($id)) );
	}
	
	public function updateUtilityById($id)
	{
		$date = DB::table('parameter1')->select('from_date','to_date')->first();
		
		return $query = DB::table('account_master')->where('account_master.status',1)
						->where('account_master.id', $id)
						->join('account_transaction', 'account_transaction.account_master_id', '=', 'account_master.id')
						->where('account_transaction.voucher_type','!=','OBD')
						->where('account_transaction.status',1)
						->where('account_transaction.deleted_at','0000-00-00 00:00:00')
						->where('account_master.status',1)
						->where('account_master.deleted_at','0000-00-00 00:00:00')
						->where('account_transaction.deleted_at','0000-00-00 00:00:00')
						->whereBetween('account_transaction.invoice_date',[$date->from_date, $date->to_date])
						->select('account_master.id','account_master.master_name','account_master.cl_balance','account_master.category',
								 'account_transaction.transaction_type','account_transaction.amount','account_master.op_balance','account_transaction.invoice_date')
						->orderBy('account_master.id','ASC')
						->get();
			
	}
	
	protected function groupAccount($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
	
	protected function updateAccountTally($results)
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
			
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				DB::table('account_master')
					->where('id', $account_id)
					->update(['cl_balance' => $amount]);
			}
				
		}
		return true;
	}	
	
		
	public function updateItemQuantitySales($attributes, $key, $bquantity=null)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
										->where('is_baseqty', 1)->first();
		if($item) {
			//$qty = $attributes['quantity'][$key];
			/*$packing = isset($attributes['packing'][$key])?$attributes['packing'][$key]:1; //Storage::prepend('stolog.txt', 'bquantity: '.$bquantity);
			//$qty = ($bquantity!=null)?$bquantity:$attributes['quantity'][$key]; JUL 23
			$qty = (is_null($bquantity))?$attributes['quantity'][$key]:$bquantity;  //Storage::prepend('stolog.txt', 'qty: '.$qty);
			$baseqty = ($qty * $packing); */
			//$baseqty = ($attributes['unit_id'][$key]==2)?(number_format( $attributes['quantity'][$key] /$attributes['packing'][$key],2)): ($attributes['quantity'][$key] * $attributes['packing'][$key]);
			
			if(isset($attributes['unit_id'][$key]) && isset($attributes['packing'][$key])) {
		    	$baseqty = ($attributes['unit_id'][$key]==2)?($attributes['quantity'][$key] /$attributes['packing'][$key]): ($attributes['quantity'][$key] * $attributes['packing'][$key]);
		    } else {
		        $packing = isset($attributes['packing'][$key])?$attributes['packing'][$key]:1; //Storage::prepend('stolog.txt', 'bquantity: '.$bquantity);
    			//$qty = ($bquantity!=null)?$bquantity:$attributes['quantity'][$key]; JUL 23
    			$qty = (is_null($bquantity))?$attributes['quantity'][$key]:$bquantity;  //Storage::prepend('stolog.txt', 'qty: '.$qty);
    			$baseqty = ($qty * $packing);
		    }

			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => DB::raw('cur_quantity - '.$baseqty),
							'issued_qty' => DB::raw('issued_qty + '.$baseqty) ]);
							
			//UPDATE into ITEM STOCK LOG 
			$stocks = DB::table('item_log')->where('item_id',$attributes['item_id'][$key])
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->where('cur_quantity', '>', 0)
								   ->orderBy('id','ASC')->get();
			//echo '<pre>';print_r($stocks);exit;					   
			$squantity = $attributes['quantity'][$key];
								   
			if(!empty($stocks)) {
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
				
				$itm_sale_cost = ($sale_qty > 0)?$sale_cost / $sale_qty:0; // $avg_cost = ($total_qty > 0)?$total_cost / $total_qty:0;
				
				return $itm_sale_cost;
				
			} else {
				
				$stocks = DB::table('item_log')->where('item_id',$attributes['item_id'][$key])
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->select('pur_cost')
								   ->orderBy('id','DESC')->first(); //echo '<pre>';print_r($stocks);exit;
				
				if($stocks)				
					return $stocks->pur_cost;
				else
					return 0;
			}
		}
		
	}
	
	public function updateLastPurchaseCostAndCostAvg($attributes, $key, $type, $other_cost=null)
	{
		
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('cur_quantity','pur_cost')
										->get(); //echo '<pre>';print_r($itmlogs);exit;
		if($type==0) {								
			$itmcost = $itmqty = 0;
		} else {
			$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
			$itmqty = $attributes['quantity'][$key];
		}
		
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
			$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		} else {
			$row = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			if($row)
				$cost_avg = $cost = $row->cost_avg;
			else
				$cost_avg = $cost = 0;
		}
		
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->update([//'last_purchase_cost' => $cost,
						  //'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	public function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $type, $other_cost=null)
	{
		
		$pid = $attributes['sales_invoice_id'];
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->where(function ($query) use($pid) {
											$query->where('document_id','!=',$pid)
												  ->orWhere('document_type','!=','PI');
										})
										->select('cur_quantity','pur_cost')
										->get();
		
		if($type==0) {								
			$itmcost = $itmqty = 0;
		} else {
			$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
			$itmqty = $attributes['quantity'][$key];
		}
		
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
			$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		} else {
			$row = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			if($row)
				$cost_avg = $cost = $row->cost_avg;
			else
				$cost_avg = $cost = 0;
		}
		
		//$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		//$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
					
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->update([//'last_purchase_cost' => $cost,
						  //'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	public function autoUpdateAVGCost($item_id)
	{
		$logs = DB::table('item_log')->where('trtype',1)->where('status',1)
							 ->where('item_id', $item_id)
							 ->where('deleted_at','0000-00-00 00:00:00')
							 ->select('pur_cost','cur_quantity')
							 ->get();
							 
		if($logs) {
			$total_qty = $total_cost = 0;
			foreach($logs as $row) {
				$total_cost += $row->pur_cost * $row->cur_quantity;
				$total_qty += $row->cur_quantity;
			}
			
			$avg_cost = ($total_qty > 0)?$total_cost / $total_qty:0;
			
			DB::table('item_unit')->where('itemmaster_id',$item_id)->where('is_baseqty',1)
								 ->update([ 'cost_avg' => $avg_cost]);
		} else {
			
			DB::table('item_unit')->where('itemmaster_id',$item_id)->where('is_baseqty',1)
								 ->update([ 'cur_quantity' => 0,
											'received_qty' => 0,
											'last_purchase_cost' => 0,
											'pur_count' => 0,
											'cost_avg' => 0,
											'issued_qty' => 0
								 ]);
		}
	}
	
	//SEP25
	public function updateLastPurchaseCostAndCostAvgonDelete($item, $id) {
		//UPDATE Cost avg and stock...
									
		//COST AVG Updating on DELETE section....
		DB::table('item_log')->where('document_id', $id)->where('document_type','SI')
		                     ->where('item_row_id',$item->id)
							 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
							 ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		
		DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
							  ->update(['cur_quantity' => DB::raw('cur_quantity + '.$item->quantity)]);
								  
		$this->autoUpdateAVGCost($item->item_id);
	}
	
	public function updateLastPurchaseCostAndCostAvgonDeleteGsec($item, $id, $type) {
		//UPDATE Cost avg and stock...
									
		//COST AVG Updating on DELETE section....
		DB::table('item_log')->where('document_id', $id)->where('document_type', $type)
							 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
							 ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		if($type=='GI' || $type=='TO') {
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity + '.$item->quantity)]);
		} else if($type=='GR' || $type=='TI') {
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity - '.$item->quantity)]);
		}
								  
		$this->autoUpdateAVGCost($item->item_id);
	}
	
	public function reEvaluateItemCostQuantity($item_id)
	{
		$logs = DB::table('item_log')->where('item_id', $item_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$date = DB::table('parameter1')->select('from_date')->first();
		//echo '<pre>';print_r($logs);exit;
		if($logs) {
			foreach($logs as $log) {
			
				if($log->document_type=='OQ')
					$from_date = $log->voucher_date;
				else 
					$from_date = $date->from_date;
				
				if($log->trtype==1) {
					$this->setQty($log);
					$cost_avg = $this->getCostAvgonPur($log,$other_cost=0,$from_date);
					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg ]);
				} else {
					
					$sale_cost = $this->getCost($log, $from_date);
					$cost_avg = $this->getCostAvgonSale($log,$other_cost=0,$from_date);
					
					//$sale_cost = ($sale_cost==0)?$log->pur_cost:$sale_cost; //FEB25

					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg, 'sale_cost' => $sale_cost ]);
				}
				
			}
		}
		
		return true;
	}
	
	public function updateItemQuantitySalesAssembly($itemid, $qty)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $itemid)->where('is_baseqty', 1)->first();
		if($item) {
			
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => DB::raw('cur_quantity - '.$qty),
							'issued_qty' => DB::raw('issued_qty + '.$qty) ]);
							
			//UPDATE into ITEM STOCK LOG 
			$stocks = DB::table('item_log')->where('item_id',$itemid)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->where('cur_quantity', '>', 0)
								   ->orderBy('id','ASC')->get();
			//echo '<pre>';print_r($stocks);exit;					   
			$squantity = $qty;
								   
			if(!empty($stocks)) {
				$sale_cost = $sale_qty = 0;
				foreach($stocks as $stock) {
					
					if($stock->cur_quantity > 0) { 
						
						if($stock->cur_quantity >= $squantity) {
							$cur_quantity = $stock->cur_quantity - $squantity;
							$break = true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += $stock->pur_cost * $squantity;
							$sale_qty += $squantity;
						
						} else if($stock->cur_quantity < $squantity) {
							$squantity = $squantity - $stock->cur_quantity;
							$cur_quantity = 0;
							$break = ($squantity > 0)?false:true;
							
							//CALCULATE ITEM COST for PROFIT ANALYSIS...
							$sale_cost += $stock->pur_cost * $stock->cur_quantity;
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
				
				$stocks = DB::table('item_log')->where('item_id',$itemid)
								   ->where('trtype', 1)
								   ->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
								   ->select('pur_cost')
								   ->orderBy('id','DESC')->first();
				
				if($stocks)				
					return $stocks->pur_cost;
				else
					return 0;
			}
		}
		
	}
	
	public function updateLastPurchaseCostAndCostAvgAssembly($itemid, $itmqty, $itmattr, $attributes)
	{
		$itmlogs = DB::table('item_log')->where('item_id', $itemid)
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('cur_quantity','pur_cost')
										->get();
		
		$itmcost = 1 * ($itmattr->sell_price==0)?$itmattr->cost_avg:$itmattr->sell_price;
		
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		if($itmcost > 0 && $itmqty > 0) {
			$cost_avg = round(($itmcost / $itmqty), 3);
			$cost = (isset($attributes['is_fc']))?(($itmattr->sell_price==0)?$itmattr->cost_avg:$itmattr->sell_price)*$attributes['currency_rate']:(($itmattr->sell_price==0)?$itmattr->cost_avg:$itmattr->sell_price);
		} else {
			$row = DB::table('item_log')->where('item_id', $itemid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('cost_avg')->orderBy('id', 'DESC')->first();
			if($row)
				$cost_avg = $cost = $row->cost_avg;
			else
				$cost_avg = $cost = 0;
		}
		
		DB::table('item_unit')
				->where('itemmaster_id', $itemid)
				->update([//'last_purchase_cost' => $cost,
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	
	//AUTO REFRESH COSTING....
	public function reEvalItemCostQuantity($items,$dateobj)
	{
		foreach($items as $item) {
			
			$logs = DB::table('item_log')->where('item_id', $item)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
			//echo '<pre>';print_r($logs);exit;
			if($logs) {
				$result = $this->reProcessLogs($logs,$dateobj);
				//UPDATING ITEM COST AVG..
				DB::table('item_unit')->where('itemmaster_id', $item)->update(['cost_avg' => $result['cost_avg'] ]);
			}
		}
		
		return true;
	}
	
	private function reProcessLogs($logs,$dateobj) 
	{
		$sale_ref = 0;
		foreach($logs as $log) {
			$cost_avg = 0;
			if($log->document_type=='OQ')
				$from_date = $log->voucher_date;
			else 
				$from_date = $dateobj->from_date;
			
			if($log->trtype==1) {
				$sale_ref += $log->quantity;
				$this->setQty($log, $sale_ref);
				
				$arMqty = $this->checkMinusQty($log);
				
				if($log->document_type=='SDO') { 
					$cost_avg = $this->getCostAvgonSdo($log,$other_cost=0,$from_date);
					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg]); //, 'pur_cost' => $cost_avg
				} else {
					$cost_avg = $this->getCostAvgonPur($log,$other_cost=0,$from_date);
					DB::table('item_log')->where('id', $log->id)
										->update([ 'cost_avg' => $cost_avg]);
				}
				
				if($arMqty)
			    	DB::table('item_log')->where('id', $arMqty['id'])->update(['cur_quantity' => $arMqty['cqty'] ]);
				
			} else {
				$sale_ref = $sale_ref - $log->quantity; //$sale_ref -= $log->quantity;
				DB::table('item_log')->where('id', $log->id)
									->update([ 'sale_reference' => $sale_ref ]);
									
				$sale_cost = $this->getCost($log, $from_date);
				$cost_avg = $this->getCostAvgonSale($log,$other_cost=0,$from_date);
				
				$cost_avg = ($cost_avg==0 && $sale_cost!=0)?$sale_cost:$cost_avg;  
				

				DB::table('item_log')->where('id', $log->id)
									->update([ 'cost_avg' => $cost_avg, 'sale_cost' => $sale_cost, 'sale_reference' => $sale_ref ]);
			}
			
		}
		return ['cost_avg' => $cost_avg];
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
										//->where('document_type', '!=', 'SDO')
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
	
	
	private function setQty($row, $sale_ref)
	{
		if($row->document_type=='SR') {
			$itmlog = DB::table('item_log')
								   ->where('item_id',$row->item_id)
								   ->where('document_id',$row->return_ref_id)
								   ->where('status',1)
								   ->where('deleted_at','0000-00-00 00:00:00')
								   ->where('document_type','SI')
								   ->select('cost_avg','pur_cost','unit_cost')
								   ->first();
			if($itmlog)					   
				DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => $itmlog->cost_avg, 'pur_cost' => $itmlog->pur_cost,'sale_reference' => $sale_ref ]); //'pur_cost' => $itmlog->pur_cost,
				
				//DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => $itmlog->unit_cost, 'pur_cost' => $itmlog->pur_cost,'sale_reference' => $sale_ref ]); //MAR17
		
		} else if($row->document_type=='SDO') {
			DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => 0, 'pur_cost' => DB::raw('unit_cost + other_cost'),'sale_reference' => $sale_ref ]);
			//DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => 0, 'pur_cost' => 0,'sale_reference' => $sale_ref ]); //MAR17
		} else 
			DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => DB::raw('unit_cost + other_cost'), 'pur_cost' => DB::raw('unit_cost + other_cost'),'sale_reference' => $sale_ref ]); 
			
			//DB::table('item_log')->where('id',$row->id)->update(['cur_quantity' => DB::raw('quantity'), 'cost_avg' => DB::raw('unit_cost'), 'pur_cost' => DB::raw('unit_cost + other_cost'),'sale_reference' => $sale_ref ]); //MAR17
		
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
								   
			$is_return = (count($itmlogs) > 0)?false:true; //echo '<pre>';print_r($itmlogs);exit;
			
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
										
	//	echo '<pre>';print_r($itmlogs);exit;
		//Log::info($from_date.' '.$row->voucher_date.print_r($itmlogs, true));
		//Log::info('d '.print_r($itmlogs, true));
		
		##### COMMENTED ON 16MAR2023 ISSUE FOUND IN QTY ADDING WITH 0 COST O KIM 
		/* $itmcost = $row->quantity * $row->pur_cost;
		$itmqty = $row->quantity;
		foreach($itmlogs as $log) {
			$itmcost += $log->quantity * $log->pur_cost;
			$itmqty += $log->quantity;
		} */
		
		//NEWUPDATE15JL24
		$itmcost = 0;//$row->quantity * $row->pur_cost; 
		$itmqty = 0;//($itmcost > 0)?$row->quantity:0; 
		$cost_avg=0;
		foreach($itmlogs as $log) {
			$itmcost += $log->quantity * $log->pur_cost;
			$itmqty += ($log->pur_cost > 0)?$log->quantity:0;
		}
		//Log::info('Ab '.$itmcost.' '.$itmqty);
		//NEWUPDATE15JL24
		if($row->document_type=='SR') {
		    $cost_avg = isset($log->cost_avg)?$log->cost_avg:0;//exit;
		} else 
		    $cost_avg = ($itmqty > 0)?($itmcost / $itmqty):0 + $other_cost;
		
		//Log::info('Ab2 '.$itmcost.' '.$itmqty.' '.$cost_avg);			
		return $cost_avg;
		
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
		$arrMqty = null;
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
	  //DB::table('item_log')->where('id', $row->id)->update(['cur_quantity' => $mn_quantity ]); 
	                $arrMqty = ['id' => $row->id, 'cqty' => $mn_quantity];
					
			    } else {
					$cost_avg = ($row->quantity * $row->pur_cost) / $row->quantity;
					$qty = $row->cur_quantity - $mn_quantity;
					
					//$sale_cost = ($cost_arr['sale_qty'] + ($qty * $row->pur_cost)) / ($qty + $cost_arr['sale_cost']); //CALCULATE COST WITH CURRENT ASSIGNED ITEM QTY AND COST
					$sale_cost = ($log->pur_cost + $row->pur_cost) / 2;
				    DB::table('item_log')->where('id',$log->id)->update(['cost_avg' => $cost_avg, 'sale_cost' => $row->pur_cost,'sale_reference' => $mn_quantity]);
		//DB::table('item_log')->where('id', $row->id)->update(['cur_quantity' => 0 ]);
                    $arrMqty = ['id' => $row->id, 'cqty' => 0];
			    }
		    }
		
		}	
		
		return $arrMqty;
		
	}
	
	//END AUTOMATED SCRIPT....
	
	
	public function UpdateAccountTransactions($attributes) {
		
		//CHECK TRANSACTION ALREADY POSTED OR NOT..
		$transData = DB::table('account_transaction')->where('voucher_type_id', $attributes['voucher_id'])->where('account_master_id', $attributes['account_id'])
										->where('voucher_type', $attributes['voucher_type'])->where('tr_for', $attributes['tr_for'])->first();
		if($transData) {
			
			DB::table('account_transaction')
							->where('voucher_type_id', $attributes['voucher_id'])
							->where('account_master_id', $attributes['account_id']) 
							->where('voucher_type', $attributes['voucher_type'])
							->where('tr_for', $attributes['tr_for'])
							->update([  'transaction_type'	=> $attributes['type'],
										'amount'   			=> $attributes['amount'],
										'modify_at' 		=> date('Y-m-d H:i:s'),
										'modify_by' 		=> Auth::User()->id,
										'description' 		=> $attributes['description'],
										'reference'			=> $attributes['voucher_no'],
										'invoice_date'		=> $attributes['voucher_date'],
										'reference_from'	=> $attributes['reference_no'],
										'fc_amount'			=> ($attributes['is_fc']==1)?($attributes['amount']/$attributes['currency_rate']):$attributes['amount'],
										'department_id'		=> ($attributes['department_id']!=0)?$attributes['department_id']:''
									]);
		} else { //INSERT TRANSACTION...  
			
			if($attributes['amount'] > 0) {
				
				DB::table('account_transaction')
								->insert([  'voucher_type' 		=> $attributes['voucher_type'],
											'voucher_type_id'   => $attributes['voucher_id'],
											'account_master_id' => $attributes['account_id'],
											'transaction_type'  => $attributes['type'],
											'amount'   			=> $attributes['amount'],
											'status' 			=> 1,
											'created_at' 		=> date('Y-m-d H:i:s'),
											'created_by' 		=> Auth::User()->id,
											'description' 		=> $attributes['description'],
											'reference'			=> $attributes['voucher_no'],
											'invoice_date'		=> $attributes['voucher_date'],
											'reference_from'	=> $attributes['reference_no'],
											'tr_for'			=> $attributes['tr_for'],
											'fc_amount'			=> ($attributes['is_fc']==1)?($attributes['amount']/$attributes['currency_rate']):$attributes['amount'],
											'is_fc'				=> ($attributes['is_fc']==1)?1:0,
											'department_id'		=> ($attributes['department_id']!=0)?$attributes['department_id']:''
											]);
			}
		}
		
		return true;
	}

	function previewVoucherNo($voucherType, $is_prefix, $prefix = '', $departmentId = null)
	{
		$setting = DB::table('account_setting')
			->where('voucher_type_id', $voucherType)
			->where('prefix', $prefix)
			->where('is_prefix', $is_prefix)
			//->where('department_id', $departmentId)
			->where('status',1)
			->where('deleted_at','0000-00-00 00:00:00')
			->first();

		$nextNo = $setting ? $setting->voucher_no + 1 : 1;

		//return ($prefix ? $prefix : '') . str_pad($nextNo, 6, '0', STR_PAD_LEFT);
		$voucherNo = ($prefix ? $prefix : '') . $nextNo;
		return $voucherNo;
	}


	function generateVoucherNo($voucher_id, $maxNumeric, $departmentId = null, $manualVoucherNo = null)
    {
        return DB::transaction(function () use ($voucher_id, $maxNumeric, $departmentId, $manualVoucherNo) {
            $now = Carbon::now();

			// 1️⃣ Lock settings row
            $qry = DB::table('account_setting')->where('id',$voucher_id);
			if($departmentId)
                $qry->where('department_id', $departmentId);

            $setting = $qry->lockForUpdate()->first();

			// ✅ If user entered manually
			if (!empty($manualVoucherNo)) {
				
				// Update current counter if manual number is higher
				$numPart = (int)preg_replace('/\D/', '', $manualVoucherNo);
				if ($numPart > $setting->voucher_no) {
					DB::table('account_setting')->where('id', $setting->id)->update([
						'voucher_no' => $numPart,
						'modified_at' => $now,
					]);
				}

				return $manualVoucherNo; // ✅ use user’s number
			}


            $nextNo = (int)$setting->voucher_no + 1; //$nextNo = (int)$maxNumeric + 1;

			$prefix = ($setting->is_prefix==1)?$setting->prefix:'';

            // 3️⃣ Format voucher number
		    $voucherNo = ($prefix ? $prefix : '') . $setting->voucher_no;

            // 4️⃣ Update settings
            DB::table('account_setting')->where('voucher_type_id', $setting->voucher_type_id)->update([
                'voucher_no' => $nextNo,
                'modified_at' => $now,
            ]);

            return $voucherNo;
        });
    }

	function generateVoucherNoDoc($doc, $maxNumeric, $departmentId = null, $manualVoucherNo = null)
    {
        return DB::transaction(function () use ($doc, $maxNumeric, $departmentId, $manualVoucherNo) {
            $now = Carbon::now();

            // 1️⃣ Lock settings row
            $qry = DB::table('voucher_no')->where('voucher_type',$doc);
			if($departmentId)
                $qry->where('department_id', $departmentId);

            $setting = $qry->lockForUpdate()->first();

			// ✅ If user entered manually
			if (!empty($manualVoucherNo)) {
				
				// Update current counter if manual number is higher
				$numPart = (int)preg_replace('/\D/', '', $manualVoucherNo);
				if ($numPart > $setting->no) {
					$qry1 = DB::table('voucher_no')->where('voucher_type', $doc);
					if($departmentId)
                		$qry1->where('department_id', $departmentId);
					
					$qry1->update(['no' => $numPart,'modified_at' => $now]);
				}

				return $manualVoucherNo; // ✅ use user’s number
			}

            $nextNo = (int)$setting->no + 1;
			$prefix = $setting->prefix;

            // 3️⃣ Format voucher number
		    $voucherNo = ($prefix ? $prefix : '') . $setting->no;

            // 4️⃣ Update settings
            DB::table('voucher_no')->where('voucher_type', $doc)->update(['no' => $nextNo,'modified_at' => $now]);

            return $voucherNo;
        });
    }
	
}
