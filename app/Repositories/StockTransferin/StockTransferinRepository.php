<?php namespace App\Repositories\StockTransferin;

use App\Models\StockTransferin;
use App\Models\StockTransferinItem;
use App\Models\StockTransferinInfo;
use App\Models\ItemLocationTI;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Auth;
use Session;


class StockTransferinRepository extends AbstractValidator implements StockTransferinInterface {
	
	protected $stock_transferin;
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(StockTransferin $stock_transferin) {
		$this->stock_transferin = $stock_transferin;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->stock_transferin->get();
	}
	
	public function find($id)
	{
		return $this->stock_transferin->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->stock_transferin->voucher_no = $attributes['voucher_no'];
		$this->stock_transferin->reference_no = $attributes['reference_no'] ?? null;
		$this->stock_transferin->description = $attributes['description'] ?? null;
		//$this->stock_transferin->job_id = $attributes['job_id'];
		$this->stock_transferin->account_dr = $attributes['account_dr'];
		$this->stock_transferin->account_cr = $attributes['account_cr'];
		$this->stock_transferin->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->stock_transferin->is_mfg = isset($attributes['is_mfg'])?$attributes['is_mfg']:'';
		$this->stock_transferin->department_id = isset($attributes['department_id'])?$attributes['department_id'] ?? 0:'';
		$this->stock_transferin->other_cost = isset($attributes['other_cost'])?$attributes['other_cost'] ?? 0:'';
		
		return true;
	}
	
	private function setItemInputValue($attributes, $stockTransferinItem, $key, $value) 
	{
		$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
		
		$stockTransferinItem->stock_transferin_id = $this->stock_transferin->id;
		$stockTransferinItem->item_id = $attributes['item_id'][$key];
		$stockTransferinItem->unit_id = $attributes['unit_id'][$key];
		$stockTransferinItem->item_name = $attributes['item_name'][$key];
		$stockTransferinItem->quantity = $attributes['quantity'][$key];
		$stockTransferinItem->price = $attributes['cost'][$key];
		$stockTransferinItem->item_total = $item_total;
		
		$othercost_unit = $netcost_unit = 0;
		if( isset($attributes['other_cost'])) {
			$othercost_unit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total_hd'];
			$netcost_unit = $othercost_unit + $attributes['cost'][$key];
		}
		
		$stockTransferinItem->netcost_unit = $netcost_unit;
		$stockTransferinItem->othercost_unit = $othercost_unit;
		
		return array('line_total' => $attributes['quantity'][$key], 'item_total' => $item_total, 'othercost_unit' => $othercost_unit);
		
	}
	
	//Purchase and Sales Method function............
	private function PurchaseAndSalesMethod($attributes, $net_amount, $transferin_id)
	{
		//Debit Customer Account
		if( $this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Dr') ) {
			
			//Credit Sales A/c
			$this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Cr');
		}
	}
	
	private function PurchaseAndSalesMethodUpdate($attributes, $net_amount, $transferin_id)
	{
		
		//Debit Customer Account
		if( $this->setAccountTransactionUpdate($attributes, $net_amount, $transferin_id, $type='Dr') ) {
			
			//Credit Sales A/c
			$this->setAccountTransactionUpdate($attributes, $net_amount, $transferin_id, $type='Cr');
		}

	}
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $mod=null, $key=null, $ocid=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		if($type == 'Cr') {
			$cr_acnt_id = ($mod=='OC')?$attributes['cr_acnt_id'][$key]:$attributes['account_cr'];
		} else if($type == 'Dr') {
			$dr_acnt_id = ($mod=='OC')?$attributes['dr_acnt_id'][$key]:$attributes['account_dr'];
		}
		
		if( ($cr_acnt_id!='' || $cr_acnt_id!=0) || ($dr_acnt_id!='' || $dr_acnt_id!=0) ) {
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'STI',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> ($mod=='OC')?$attributes['oc_description'][$key]:$attributes['description'] ?? 'STI',
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> ($mod=='OC')?$attributes['oc_reference'][$key]:'',
								'tr_for'			=> ($ocid)?$ocid:0,
								'other_type'		=> ($mod)?$mod:'',
								'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:'',
								'version_no'		=> $attributes['version_no'],
								'version_no'		=> $attributes['version_no']
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		}
							
		return true;
	}
	
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $mod=null, $key=null, $ocid=null)
	{
		
		if($type == 'Cr') {
			$account_id = ($mod=='OC')?$attributes['cr_acnt_id'][$key]:$attributes['account_cr'];
		} else if($type == 'Dr') {
			$account_id = ($mod=='OC')?$attributes['dr_acnt_id'][$key]:$attributes['account_dr'];
		}
		
		DB::table('account_transaction')
				->where('voucher_type_id', $voucher_id)
				->where('account_master_id', $account_id)
				->where('voucher_type', 'STI')
				->update([  'amount'   			=> $amount,
							'modify_at' 		=> date('Y-m-d H:i:s'),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> ($mod=='OC')?$attributes['oc_description'][$key]:$attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'tr_for'			=> ($ocid)?$ocid:0,
							'other_type'		=> ($mod)?$mod:'',
							'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
						]);
						
		$this->objUtility->tallyClosingBalance($account_id);
		
						
		return true;
	}
	
	
	private function OtherCostTransaction($attributes, $oc_amount, $transferin_id, $k, $ocid)
	{
		//Debit Customer Account
		if( $this->setAccountTransaction($attributes, $oc_amount, $transferin_id, $type='Dr', 'OC', $k, $ocid) ) {
			//Credit Sales A/c
			$this->setAccountTransaction($attributes, $oc_amount, $transferin_id, $type='Cr', 'OC', $k, $ocid);
		}
	}
	
	
	private function OtherCostTransactionUpdate($attributes, $oc_amount, $transferin_id, $k, $ocid)
	{
		//Debit Customer Account
		if( $this->setAccountTransactionUpdate($attributes, $oc_amount, $transferin_id, $type='Dr', 'OC', $k, $ocid) ) {
			//Credit Sales A/c
			$this->setAccountTransactionUpdate($attributes, $oc_amount, $transferin_id, $type='Cr', 'OC', $k, $ocid);
		}
	}
	
	
	public function create($attributes)
	{	//echo '<pre>';print_r($attributes);exit; 
		if($this->isValid($attributes)) {
			
		  DB::beginTransaction();
		  try {
					//VOUCHER NO LOGIC.....................
					$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
					// 2️⃣ Get the highest numeric part from voucher_master
					$qry = DB::table('stock_transferin')->where('deleted_at', '0000-00-00 00:0:00')->where('status', 1);
					if($dept > 0)	
						$qry->where('department_id', $dept);

					$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
					
					$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($attributes['voucher_id'], $maxNumeric, $dept, $attributes['voucher_no']);
					//VOUCHER NO LOGIC.....................
					
					//exit;
					$maxRetries = 5; // prevent infinite loop
					$retryCount = 0;
					$saved = false;

					while (!$saved && $retryCount < $maxRetries) {
						try {
								if ($this->setInputValue($attributes)) {

									$this->stock_transferin->status = 1;
									$this->stock_transferin->created_at = date('Y-m-d H:i:s');
									$this->stock_transferin->created_by = 1;
									$this->stock_transferin->save();

									$saved = true; // success ✅
									
								}	
							} catch (\Illuminate\Database\QueryException $ex) {

								// Check if it's a duplicate voucher number error
								if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
									strpos($ex->getMessage(), 'duplicate key value') !== false) {

									$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
									// 2️⃣ Get the highest numeric part from voucher_master
									$qry = DB::table('stock_transferin')->where('deleted_at', '0000-00-00 00:0:00')->where('status', 1);
									if($dept > 0)	
										$qry->where('department_id', $dept);

									$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
									
									$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($attributes['voucher_id'], $maxNumeric, $dept, $attributes['voucher_no']);

									$retryCount++;
								} else {
									throw $ex; // rethrow if different DB error
								}
							}
						}
					
					$attributes['version_no'] = 1;

				//order items insert
				if($this->stock_transferin->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = $item_total = $othercost_unit = 0;
					
					foreach($attributes['item_id'] as $key => $value) { 
						$stockTransferinItem = new StockTransferinItem();
						$arrResult 		= $this->setItemInputValue($attributes, $stockTransferinItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total'];
							$othercost_unit = $arrResult['othercost_unit'];
							
							$stockTransferinItem->status = 1;
							$itemObj = $this->stock_transferin->transferItem()->save($stockTransferinItem);
							
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							$logid = $this->setPurchaseLog($attributes, $key, $this->stock_transferin->id, $CostAvg_log,'add',$othercost_unit);
							if($logid!='')							
								$this->updateItemQuantity($attributes, $key);
						}
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($qtys)
										DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lq) ]);
									
									$itemLocationTI = new ItemLocationTI();
									$itemLocationTI->location_id = $attributes['locid'][$key][$lk];
									$itemLocationTI->item_id = $value;
									$itemLocationTI->unit_id = $attributes['unit_id'][$key];
									$itemLocationTI->quantity = $lq;
									$itemLocationTI->status = 1;
									$itemLocationTI->trin_id = $itemObj->id;
									$itemLocationTI->logid = $logid;
									$itemLocationTI->save();
								}
							}
						}
						
						
						//Item default location add...
						if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
								
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
															  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
							if($qtys)
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
								
							$itemLocationTI = new ItemLocationTI();
							$itemLocationTI->location_id = $attributes['default_location'];
							$itemLocationTI->item_id = $value;
							$itemLocationTI->unit_id = $attributes['unit_id'][$key];
							$itemLocationTI->quantity = $attributes['quantity'][$key];
							$itemLocationTI->status = 1;
							$itemLocationTI->trin_id = $itemObj->id;
							$itemLocationTI->logid = $logid;
							$itemLocationTI->save();
							
						}
					}
					
					$total_amt = $item_total;
					if(count($attributes['dr_acnt_id']) > 0 && count($attributes['oc_amount']) > 0) {
						foreach($attributes['dr_acnt_id'] as $k => $val) {
							if($val!='' && $attributes['oc_amount'][$k]!='' && $attributes['dr_acnt_id'][$k]!='') {
								$ocid = DB::table('sti_other_cost')
												->insertGetId(['transfer_id' => $this->stock_transferin->id,
														  'dr_account_id' => $val,
														  'reference'	=> $attributes['oc_reference'][$k],
														  'description'	=> $attributes['oc_description'][$k],
														  'amount'	=> $attributes['oc_amount'][$k],
														  'cr_account_id'	=> $attributes['cr_acnt_id'][$k]
												]);
										
								$total_amt = $total_amt + $attributes['oc_amount'][$k];
								
								$this->OtherCostTransaction($attributes, $attributes['oc_amount'][$k], $this->stock_transferin->id, $k, $ocid);
							}
						}
					}


					
					//update discount, total amount
					DB::table('stock_transferin')
								->where('id', $this->stock_transferin->id)
								->update([//'voucher_no'    =>$attributes['voucher_no'],
									'total_qty' => $line_total, 
									'total_amt' => $total_amt,
									 'net_total' => $total_amt]);
					
					$this->PurchaseAndSalesMethod($attributes, $item_total, $this->stock_transferin->id);
								
				}
				
				
				
				DB::commit();
				return $this->stock_transferin->id;
				
		  } catch(\Exception $e) {
				
				DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
				return false;
		  }
		  
		}
		
	}
	

	
	public function update($id, $attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$this->stock_transferin = $this->find($id);
		$line_total = $item_total = 0;
		
		DB::beginTransaction();
		try {

			//FIND CURRENT VERSION	 
			$voucher_type = 'STI';
			$currentVersion = DB::table('account_transaction')->where('voucher_type', $voucher_type)->where('voucher_type_id', $id)->max('version_no');
			$newVersion = $currentVersion + 1;
			$attributes['version_no'] = $newVersion;

			//SOFT DELETE OLD VERSION
			DB::table('account_transaction')->where('voucher_type', $voucher_type)->where('voucher_type_id', $id)
						->update([
									'status' => 0,
									'deleted_at' => date('Y-m-d h:i:s'),
									'deleted_by'  => Auth::User()->id,
								]);
			
			if($this->stock_transferin->id && !empty( array_filter($attributes['item_id']))) { 
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['transfer_item_id'][$key]!='') { 
						
						$item_total   += $attributes['cost'][$key] * $attributes['quantity'][$key];
						
						$othercost_unit = 0;
						if( isset($attributes['other_cost'])) {
							$othercost_unit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total_hd'];
							$netcostunit = $othercost_unit + $attributes['cost'][$key];
						}
								
						$stockTransferinItem = StockTransferinItem::find($attributes['transfer_item_id'][$key]); //echo '<pre>';print_r($stockTransferinItem);exit;
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['price'] = $attributes['cost'][$key];
						$items['item_total'] = $attributes['quantity'][$key] * $attributes['cost'][$key];
						$items['othercost_unit'] = $othercost_unit;
						$items['netcost_unit'] = $netcostunit;
						
						$stockTransferinItem->update($items);
						$line_total	+= $attributes['quantity'][$key];
						
						$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit);
							$this->setPurchaseLog($attributes, $key, $this->stock_transferin->id, $CostAvg_log,'update',$othercost_unit);
							//if($logid)
								$this->updateItemQuantityonEdit($attributes, $key);
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									$edit = DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->first();
									$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($edit) {
										if($edit->quantity < $lq) {
											$balqty = $lq - $edit->quantity;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
										} else {
											$balqty = $edit->quantity - $lq;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
										}
									}
									
									DB::table('item_location_ti')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lq]);
								}
							}
						}
						
						//Item default location add...
						//if(($attributes['location_id']!='') && ($updated == false)) {
						if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
															  ->where('deleted_at', '0000-00-00 00:00:00')->select('*')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
								DB::table('item_location_pi')->where('invoice_id', $attributes['order_item_id'][$key] )
															 ->where('location_id', $qtys->location_id)
															 ->where('item_id', $qtys->item_id)
															 ->where('unit_id', $qtys->unit_id)
															 ->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
							} 
						}
						//################ Location Stock Entry End ####################
						
						$total_amt = $item_total;
						if(count($attributes['dr_acnt_id']) > 0 && count($attributes['oc_amount']) > 0) {
							foreach($attributes['dr_acnt_id'] as $k => $val) {
								
								if($attributes['oc_id'][$k]!='') {
									
									DB::table('sti_other_cost')
											->where('id', $attributes['oc_id'][$k])
											->update([
												'dr_account_id'		=>	$attributes['dr_acnt_id'][$k],
												'reference'	=> $attributes['oc_reference'][$k],
												'description'	=> $attributes['oc_description'][$k],
												'amount'	=> $attributes['oc_amount'][$k],
												'cr_account_id'	=> $attributes['cr_acnt_id'][$k]
											]);
									
									$total_amt = $total_amt + $attributes['oc_amount'][$k];									
									$this->OtherCostTransaction($attributes, $attributes['oc_amount'][$k], $this->stock_transferin->id, $k, $attributes['oc_id'][$k]);
									
								} else {
									
									//new entry...
									if($val!='' && $attributes['oc_amount'][$k]!='' && $attributes['dr_acnt_id'][$k]!='') {
										$ocid = DB::table('sti_other_cost')
												->insertGetId(['transfer_id' => $this->stock_transferin->id,
														  'dr_account_id' => $val,
														  'reference'	=> $attributes['oc_reference'][$k],
														  'description'	=> $attributes['oc_description'][$k],
														  'amount'	=> $attributes['oc_amount'][$k],
														  'cr_account_id'	=> $attributes['cr_acnt_id'][$k]
												]);
												
										$total_amt = $total_amt + $attributes['oc_amount'][$k];
										
										$this->OtherCostTransaction($attributes, $attributes['oc_amount'][$k], $this->stock_transferin->id, $k, $ocid);
									}
								}
							}
						}
						
						
					} else { //new entry...
						$line_total_new = $item_total_new = 0;
						
						$stockTransferinItem = new StockTransferinItem();
						$arrResult 		= $this->setItemInputValue($attributes, $stockTransferinItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total_new	+= $arrResult['line_total'];
							$item_total_new	+= $arrResult['item_total'];
							$othercost_unit = $arrResult['othercost_unit'];
							
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total'];
							
							$stockTransferinItem->status = 1;
							$itemObj = $this->stock_transferin->transferItem()->save($stockTransferinItem);
							
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							$logid = $this->setPurchaseLog($attributes, $key, $this->stock_transferin->id, $CostAvg_log,'add',$othercost_unit);
							if($logid)
								$this->updateItemQuantity($attributes, $key);
							
							//################ Location Stock Entry ####################
							//Item Location specific add....
							$updated = false;
							if(isset($attributes['locqty'][$key])) {
								foreach($attributes['locqty'][$key] as $lk => $lq) {
									if($lq!='') {
										$updated = true;
										$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
										if($qtys)
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lq) ]);
										
										$itemLocationTI = new ItemLocationTI();
										$itemLocationTI->location_id = $attributes['locid'][$key][$lk];
										$itemLocationTI->item_id = $value;
										$itemLocationTI->unit_id = $attributes['unit_id'][$key];
										$itemLocationTI->quantity = $lq;
										$itemLocationTI->status = 1;
										$itemLocationTI->trin_id = $itemObj->id;
										$itemLocationTI->logid = $logid;
										$itemLocationTI->save();
									}
								}
							}
							
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								if($qtys)
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
									
								$itemLocationTI = new ItemLocationTI();
								$itemLocationTI->location_id = $attributes['default_location'];
								$itemLocationTI->item_id = $value;
								$itemLocationTI->unit_id = $attributes['unit_id'][$key];
								$itemLocationTI->quantity = $attributes['quantity'][$key];
								$itemLocationTI->status = 1;
								$itemLocationTI->trin_id = $itemObj->id;
								$itemLocationTI->logid = $logid;
								$itemLocationTI->save();
								
							}
						}
					}
					
				}
			}
			
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					
					$res = DB::table('stock_transferin_item')->where('id', $row)->first();
					DB::table('stock_transferin_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($res, $this->stock_transferin->id, 'TI');
				}
			}
			
			if($this->setInputValue($attributes)) {
				$this->stock_transferin->modify_at = date('Y-m-d H:i:s');
				$this->stock_transferin->modify_by = Auth::User()->id;
				$this->stock_transferin->fill($attributes)->save();
			}
			
			//$total = $line_total + $line_total_new;
			
			//update discount, total amount
			DB::table('stock_transferin')
						->where('id', $id)
						//->update(['total_qty' => $line_total,'total_amt' => $item_total, 'net_total' => $item_total]); //CHG
						->update(['total_qty' => $line_total, 'total_amt' => $total_amt, 'net_total' => $total_amt]);
						
			$this->PurchaseAndSalesMethod($attributes, $item_total, $this->stock_transferin->id); 
			
			DB::commit();
			return true;
			
		 } catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->stock_transferin = $this->stock_transferin->find($id);
		
		$items = DB::table('stock_transferin_item')->where('stock_transferin_id',$id)->get();
		
		//Transaction update....
		DB::table('account_transaction')->where('voucher_type', 'STI')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id ]);
		
		//reset account balance....
		$this->objUtility->tallyClosingBalance($this->stock_transferin->account_dr);
		
		$this->objUtility->tallyClosingBalance($this->stock_transferin->account_cr);
		
		//inventory update...
		foreach($items as $item) {
			$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($item, $id, 'TI');
		}
		
		DB::table('stock_transferin_item')->where('stock_transferin_id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);					  
		$this->stock_transferin->delete($id);
		
		return true;
	}
	
	
	public function stockUpdate($attributes,$key,$type)
	{
		if($type=='from') {
		
			DB::table('item_location')->where('item_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->where('location_id', $attributes['locfrom_id'])
									  ->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key])]);
		} else {
			
			DB::table('item_location')->where('item_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->where('location_id', $attributes['locto_id'])
									  ->update(['quantity' => DB::raw('quantity + '.$attributes['quantity'][$key])]);
		}
		
		return true;
	}
	public function getReportexcel($attributes)
	{
	//	echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$query = DB::table('stock_transferin')//$this->sales_invoice deleted_at
						->leftjoin('stock_transferin_item AS POI', function($join) {
							$join->on('POI.stock_transferin_id','=','stock_transferin.id');
						})
						->leftjoin('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','POI.item_id');
						})
						->leftjoin('account_master AS AMD', function($join) {
							$join->on('AMD.id','=','stock_transferin.account_dr');
						} )
						->leftjoin('account_setting AS AST', function($join) {
							$join->on('AST.id','=','stock_transferin.voucher_no');
						})
					
						->leftJoin('department AS D', function($join) {
							$join->on('D.id','=','stock_transferin.department_id');
								 //->where('sales_invoice.department_id','>',0);
						})
						->leftJoin('groupcat AS GP', function($join) {
							$join->on('GP.id','=','IM.group_id');
						})
						->leftJoin('groupcat AS SGP', function($join) {
							$join->on('GP.id','=','IM.subgroup_id');
						})
						->leftJoin('category AS CAT', function($join) {
							$join->on('CAT.id','=','IM.category_id');
						})
						->leftJoin('category AS SCAT', function($join) {
							$join->on('CAT.id','=','IM.subcategory_id');
						})
						
						->where('POI.status',1)
						->where('POI.deleted_at','0000-00-00 00:00-00')
						->where('stock_transferin.status',1)
						->where('stock_transferin.deleted_at','0000-00-00 00:00-00');
						
				if(isset($attributes['group_id']))
					$query->whereIn('IM.group_id', $attributes['group_id']);
				
				if(isset($attributes['subgroup_id']))
					$query->whereIn('IM.subgroup_id', $attributes['subgroup_id']);
				
				if(isset($attributes['category_id']))
					$query->whereIn('IM.category_id', $attributes['category_id']);
				
				if(isset($attributes['subcategory_id']))
					$query->whereIn('IM.subcategory_id', $attributes['subcategory_id']);
						
				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('stock_transferin.voucher_date', array($date_from, $date_to));
				}
				
				if( $attributes['search_type']=='daily' ) { 
					$query->whereBetween( 'stock_transferin.voucher_date', array(date('Y-m-d'), date('Y-m-d')) );
				}
				
				/*if($attributes['salesman']!='') { 
					$query->where('sales_invoice.salesman_id', $attributes['salesman']);
				}*/
			
				
				 if( $attributes['search_type']=='department') { 
				 	$query->where('stock_transferin.department_id','=',0);
				 }
			
			

		if(isset($attributes['item_id']) && $attributes['item_id']!='')
					$query->whereIn('POI.item_id', $attributes['item_id']);	

		$query->select('stock_transferin.voucher_no','stock_transferin.reference_no','stock_transferin.account_dr','stock_transferin.total_amt','stock_transferin.net_total',
					'stock_transferin.discount','AST.is_cash_voucher',
					'POI.item_total','POI.item_name','POI.price','IM.description','stock_transferin.voucher_date','POI.item_id',
					'POI.quantity','AMD.master_name AS name_dr',
					'stock_transferin.net_total','D.name','stock_transferin.department_id','POI.stock_transferin_id',
					'IM.item_code','GP.group_name AS group','SGP.group_name AS subgroup','stock_transferin.id AS id','AST.voucher_name',
					'CAT.category_name AS category','SCAT.category_name AS subcategory');
						

	//if(isset($attributes['type']))
		//return $query->groupBy('stock_transferin.id')->get()->toArray();
//	else
		return $query->groupBy('stock_transferin.id')->get();
}
	public function getReport($attributes)
	{
	//	echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$query = DB::table('stock_transferin')//$this->sales_invoice deleted_at
						->leftjoin('stock_transferin_item AS POI', function($join) {
							$join->on('POI.stock_transferin_id','=','stock_transferin.id');
						})
						->leftjoin('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','POI.item_id');
						})
						->leftjoin('account_master AS AMD', function($join) {
							$join->on('AMD.id','=','stock_transferin.account_dr');
						} )
						->leftjoin('account_setting AS AST', function($join) {
							$join->on('AST.id','=','stock_transferin.voucher_no');
						})
					
						->leftJoin('department AS D', function($join) {
							$join->on('D.id','=','stock_transferin.department_id');
								 //->where('sales_invoice.department_id','>',0);
						})
						->leftJoin('groupcat AS GP', function($join) {
							$join->on('GP.id','=','IM.group_id');
						})
						->leftJoin('groupcat AS SGP', function($join) {
							$join->on('GP.id','=','IM.subgroup_id');
						})
						->leftJoin('category AS CAT', function($join) {
							$join->on('CAT.id','=','IM.category_id');
						})
						->leftJoin('category AS SCAT', function($join) {
							$join->on('CAT.id','=','IM.subcategory_id');
						})
						
						->where('POI.status',1)
						->where('POI.deleted_at','0000-00-00 00:00-00')
						->where('stock_transferin.status',1)
						->where('stock_transferin.deleted_at','0000-00-00 00:00-00');
						
				if(isset($attributes['group_id']))
					$query->whereIn('IM.group_id', $attributes['group_id']);
				
				if(isset($attributes['subgroup_id']))
					$query->whereIn('IM.subgroup_id', $attributes['subgroup_id']);
				
				if(isset($attributes['category_id']))
					$query->whereIn('IM.category_id', $attributes['category_id']);
				
				if(isset($attributes['subcategory_id']))
					$query->whereIn('IM.subcategory_id', $attributes['subcategory_id']);
						
				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('stock_transferin.voucher_date', array($date_from, $date_to));
				}
				
				if( $attributes['search_type']=='daily' ) { 
					$query->whereBetween( 'stock_transferin.voucher_date', array(date('Y-m-d'), date('Y-m-d')) );
				}
				
				/*if($attributes['salesman']!='') { 
					$query->where('sales_invoice.salesman_id', $attributes['salesman']);
				}*/
			
				
				 if( $attributes['search_type']=='department') { 
				 	$query->where('stock_transferin.department_id','=',0);
				 }
			
			

		if(isset($attributes['item_id']) && $attributes['item_id']!='')
					$query->whereIn('POI.item_id', $attributes['item_id']);	

		$query->select('stock_transferin.voucher_no','stock_transferin.reference_no','stock_transferin.account_dr','stock_transferin.total_amt','stock_transferin.net_total',
					'stock_transferin.discount','AST.is_cash_voucher',
					'POI.item_total','POI.item_name','POI.price','IM.description','stock_transferin.voucher_date','POI.item_id',
					'POI.quantity','AMD.master_name AS name_dr',
					'stock_transferin.net_total','D.name','stock_transferin.department_id','POI.stock_transferin_id',
					'IM.item_code','GP.group_name AS group','SGP.group_name AS subgroup','stock_transferin.id AS id','AST.voucher_name',
					'CAT.category_name AS category','SCAT.category_name AS subcategory');
						

	if(isset($attributes['type']))
		return $query->groupBy('stock_transferin.id')->get()->toArray();
	else
		return $query->groupBy('stock_transferin.id')->get();
}
	public function check_order($id)
	{
		$count = DB::table('stock_transferin')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else
			return true;
	}

	public function check_voucher_no($refno, $deptid, $id = null) { 
		
		if($id) {
			return $result = $this->stock_transferin->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		} else {
			$query = $this->stock_transferin->where('voucher_no',$refno); 
			return $result = ($deptid)?$query->where('department_id', $deptid)->count():$query->count();
		}
	}
	
	public function stockTransList()
	{
		return $this->stock_transferin->where('status',1)
					->orderBY('stock_transferin.id', 'DESC')->get();
		
	}
	
	
	public function findRow($id)
	{
		return $this->stock_transferin->where('stock_transferin.id', $id)
					->join('account_master AS AMD', function($join) {
							$join->on('AMD.id','=','stock_transferin.account_dr');
						} )
					->join('account_master AS AMC', function($join) {
							$join->on('AMC.id','=','stock_transferin.account_cr');
						} )
					->select('AMD.master_name AS name_dr','AMC.master_name AS name_cr','stock_transferin.*')
					->first();
	}
	
	public function activeStockTransferinList()
	{
		return $this->stock_transferin->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->stock_transferin->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->stock_transferin->where('reference_no',$refno)->count();
	}
		
	
	public function getItems($id)
	{
		
		$query = $this->stock_transferin->where('stock_transferin.id',$id);
		
		return $query->join('stock_transferin_item AS ITM', function($join) {
							$join->on('ITM.stock_transferin_id','=','stock_transferin.id');
						} )
					  ->join('units AS U', function($join){
						  $join->on('U.id','=','ITM.unit_id');
					  }) 
					  ->join('itemmaster AS IM', function($join){
						  $join->on('IM.id','=','ITM.item_id');
					  })
					   ->join('item_unit AS IU', function($join){
						  $join->on('IU.itemmaster_id','=','IM.id');
					  }) 
					  ->where('ITM.status',1)
					  ->select('ITM.*','U.unit_name','IM.item_code','IU.is_baseqty')->groupBy('ITM.id')->get();
	}

	public function getDocReport()
	{
		
		$invoice = $this->stock_transferin->where('stock_transferin.is_mfg', 1)
								   ->join('account_master AS CR', function($join) {
									   $join->on('CR.id','=','stock_transferin.account_cr');
								   })
								   ->join('account_master AS DR', function($join) {
									   $join->on('DR.id','=','stock_transferin.account_dr');
								   })
								   ->select('CR.master_name AS cr_account','DR.master_name AS dr_account','stock_transferin.*','CR.address','CR.city','CR.state')
								   ->orderBY('stock_transferin.id', 'ASC')
								   ->first();
								   
		$items = $this->stock_transferin->where('stock_transferin.is_mfg', 1)
								   ->join('stock_transferin_item AS STI', function($join) {
									   $join->on('STI.stock_transferin_id','=','stock_transferin.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','STI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','STI.unit_id');
								   })
								   ->where('STI.status', 1)
								   ->where('STI.deleted_at', '0000-00-00 00:00:00')
								   ->select('STI.*','IM.item_code','U.unit_name')//'sales_invoice.id',
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	} 

	public function getDoc($attributes)
	{
		
		$invoice = $this->stock_transferin->where('stock_transferin.id', $attributes['document_id'])
								   ->join('account_master AS CR', function($join) {
									   $join->on('CR.id','=','stock_transferin.account_cr');
								   })
								   ->join('account_master AS DR', function($join) {
									   $join->on('DR.id','=','stock_transferin.account_dr');
								   })
								   ->select('CR.master_name AS cr_account','DR.master_name AS dr_account','stock_transferin.*','CR.address','CR.city','CR.state')
								   ->orderBY('stock_transferin.id', 'ASC')
								   ->first();
								   
		$items = $this->stock_transferin->where('stock_transferin.id', $attributes['document_id'])
								   ->join('stock_transferin_item AS STI', function($join) {
									   $join->on('STI.stock_transferin_id','=','stock_transferin.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','STI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','STI.unit_id');
								   })
								   ->where('STI.status', 1)
								   ->where('STI.deleted_at', '0000-00-00 00:00:00')
								   ->select('STI.*','IM.item_code','U.unit_name')//'sales_invoice.id',
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	}
	
	private function updateLastPurchaseCostAndCostAvg($attributes, $key, $other_cost)
	{
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('cur_quantity','pur_cost')
										->get();
										
		//$itmcost = $itmqty = 0;
		$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = $attributes['cost'][$key];
		
					
		DB::table('item_unit')
				->where('id', $attributes['item_id'][$key])
				->update(['last_purchase_cost' => $cost  + $other_cost,
						  'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	private function setPurchaseLog($attributes, $key, $document_id, $cost_avg, $action, $other_cost)
	{
		if($action=='add') {
			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'TI',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $attributes['quantity'][$key],
							 'unit_cost'  => $attributes['cost'][$key]+$other_cost,
							 'trtype'	  => 1,
							 'cur_quantity' => $attributes['quantity'][$key],
							 'cost_avg' => $cost_avg,
							 'pur_cost' => $attributes['cost'][$key]+$other_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			
		} else if($action=='update') {
			
			DB::table('item_log')->where('document_type','TI')
							->where('document_id', $document_id)
							->where('item_id', $attributes['item_id'][$key])
							->where('unit_id', $attributes['unit_id'][$key])
							->update([
								 'quantity'   => $attributes['quantity'][$key],
								 'unit_cost'  => $attributes['cost'][$key]+$other_cost,
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg,
								 'pur_cost' => $attributes['cost'][$key]+$other_cost,
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			$logid = true;
		}
							
		return $logid;
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('is_baseqty', 1)->first();
									  
		if($item) {
			//$qty = $attributes['quantity'][$key];
			$baseqty = $attributes['quantity'][$key];
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => $item->cur_quantity + $baseqty,
						   'received_qty' => DB::raw('received_qty + '.$baseqty) ]);
							
		}
									  
		return true;
	}
	
	private function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $other_cost)
	{	
		$pid = $attributes['transfer_id'];
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->where(function ($query) use($pid) {
											$query->where('document_id','!=',$pid)
												  ->orWhere('document_type','!=','TI');
										})
										->select('cur_quantity','pur_cost')
										->get();
		$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = $attributes['cost'][$key];
		
					
		DB::table('item_unit')
				->where('id', $attributes['item_id'][$key])
				->update(['last_purchase_cost' => $cost + $other_cost,
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
	}
	
	private function updateItemQuantityonEdit($attributes, $key)
	{
		if($attributes['actual_quantity'][$key] != $attributes['quantity'][$key]) {
			
			$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
										  ->where('is_baseqty', 1)->first();
										  
			if($item) {
				$qty = $attributes['quantity'][$key];
				//$packing = ($item->is_baseqty==1)?1:$item->packing;
				$baseqty = ($qty * $attributes['packing'][$key]);
				$diffqty = ($attributes['actual_quantity'][$key] * $attributes['packing'][$key]) - ($qty * $attributes['packing'][$key]);
				$received_qty = $diffqty * -1;
				
				if($attributes['actual_quantity'][$key] < $qty) {
					$cur_quantity = $item->cur_quantity + $received_qty;
				} else { 
					$cur_quantity = $item->cur_quantity - $diffqty;
				}
				
				DB::table('item_unit')
					->where('itemmaster_id',  $attributes['item_id'][$key])
					->where('is_baseqty',1)
					->update([ 'cur_quantity' => $cur_quantity,
								'received_qty' => DB::raw('received_qty + '.$received_qty) ]);
					
			}

			return true;
		}
	}
	
}
