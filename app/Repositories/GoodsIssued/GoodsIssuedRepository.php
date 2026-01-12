<?php namespace App\Repositories\GoodsIssued;

use App\Models\GoodsIssued;
use App\Models\GoodsIssuedItem;
use App\Models\ItemStock;
use App\Models\ItemLocationGI;
use App\Models\AccountTransaction;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Session;
use Auth;

class GoodsIssuedRepository extends AbstractValidator implements GoodsIssuedInterface {
	
	protected $goods_issued;
	
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(GoodsIssued $goods_issued) {
		$this->goods_issued = $goods_issued;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->goods_issued->get();
	}
	
	public function find($id)
	{
		return $this->goods_issued->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->goods_issued->voucher_id = $attributes['voucher_id']; 
		$this->goods_issued->voucher_no = $attributes['voucher_no']; 
		$this->goods_issued->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->goods_issued->job_id = isset($attributes['job_id'])?$attributes['job_id'] ?? 0:'';
		$this->goods_issued->description = $attributes['description'] ?? null;
		$this->goods_issued->account_master_id = $attributes['account_master_id'];
		$this->goods_issued->job_account_id = isset($attributes['job_account_id'])?$attributes['job_account_id'] ?? 0:'';
		$this->goods_issued->department_id   = isset($attributes['department_id'])?$attributes['department_id'] ?? 0:'';
		$this->goods_issued->is_itemjob   = isset($attributes['item_job'])?$attributes['item_job'] ?? 0:'';
		$this->goods_issued->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description']:'';
		
		return true;
	}
	
	private function getOtherCostSum($attributes)
	{
		return array_sum(array_map( function($var) {
					return $var;
					}, $attributes) );
	}
	
	private function getTotalQuantity($attributes)
	{
		return array_sum(array_map( function($var) {
					return $var;
					}, $attributes) );
	}
	
	private function setItemInputValue($attributes, $goodsIssuedItem, $key, $value, $total_quantity=null)
	{
		$line_total = (int)($attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
				
		$goodsIssuedItem->goods_issued_id = $this->goods_issued->id;
		$goodsIssuedItem->item_id = $attributes['item_id'][$key];
		$goodsIssuedItem->unit_id = $attributes['unit_id'][$key];
		$goodsIssuedItem->item_name = $attributes['item_name'][$key];
		$goodsIssuedItem->quantity = $attributes['quantity'][$key];
		$goodsIssuedItem->unit_price = $attributes['cost'][$key];
		$goodsIssuedItem->discount = $attributes['line_discount'][$key];
		$goodsIssuedItem->total_price = $attributes['line_total'][$key];
		
		if (!empty($attributes['jobid'][$key])) {
			$goodsIssuedItem->job_id = (int) $attributes['jobid'][$key];
		} elseif (!empty($attributes['item_job'])) {
			$goodsIssuedItem->job_id = (int) $attributes['item_job'];
		} else {
			$goodsIssuedItem->job_id = (int) ($attributes['job_id'] ?? 0);
		}


		$goodsIssuedItem->account_id = isset($attributes['account_id'][$key])?$attributes['account_id'][$key]:'';
		
		$diffcost = 0;
		if($attributes['cost'][$key] < $attributes['actcost'][$key]) {
			$diffcost = ($attributes['actcost'][$key] - $attributes['cost'][$key]) * $attributes['quantity'][$key];
		}
		
		return array('line_total' => $line_total, 'diffcost' => $diffcost);
		
	}
	
	private function updateLastPurchaseCostAndCostAvg($attributes, $key)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])->where('unit_id', $attributes['unit_id'][$key])->first();
		if($item) { 
			$count = $item->pur_count + 1;
			$cost_avg = (($item->cost_avg * $item->pur_count) + $attributes['cost'][$key]) / $count;
			DB::table('item_unit')
					->where('id', $item->id)
					->update(['last_purchase_cost' => $attributes['cost'][$key],
							  'pur_count' 		   => $count,
							  'cost_avg'		   => round($cost_avg, 2),
							]);
		}
	}
	
	private function setOtherCostInputValue($attributes, $purchaseInvoiceOC, $key)
	{
		$purchaseInvoiceOC->goods_issued_id = $this->goods_issued->id;
		$purchaseInvoiceOC->dr_account_id = $attributes['dr_acnt'][$key];
		$purchaseInvoiceOC->oc_reference = $attributes['oc_reference'][$key];
		$purchaseInvoiceOC->oc_description = $attributes['oc_description'][$key];
		$purchaseInvoiceOC->cr_account_id = $attributes['cr_acnt'][$key];
		$purchaseInvoiceOC->oc_amount = $attributes['oc_amount'][$key];
		$purchaseInvoiceOC->oc_fc_amount = $attributes['oc_fc_amount'][$key];
		
		return true;
	}
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $amount_type=null, $diffcost=null)
	{
		$ispd = ($diffcost)?5:0; //IDENTIFY COST DIFF TRANS...
		$cr_acnt_id = $dr_acnt_id = '';
		/* if($amount_type == 'LNTOTAL') {
			$cr_acnt_id = $attributes['account_master_id'];
		} else if($amount_type == 'NTAMT') {
			$dr_acnt_id = $attributes['job_account_id'];
		} */
		
		//.....new
		if($type == 'Cr') {
			$cr_acnt_id = $attributes['account_master_id'];
			$amount = ($amount_type=='CD')?$diffcost:$amount;
		} else if($type == 'Dr') {
			
			if($amount_type=='CD') {
				if(Session::get('department')==1) {
					$acc = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
					$dr_acnt_id = $acc->costdif_acid;
				} else {
					$acc = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->select('account_id')->first();
					$dr_acnt_id = $acc->account_id;
				}
				$amount = $diffcost;
			} else
				$dr_acnt_id = isset($attributes['job_account_id'])?$attributes['job_account_id']:'';
		}
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'GI',
						    'voucher_type_id'   => $voucher_id,
							'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
							'transaction_type'  => $type,
							'amount'   			=> $amount,
							'status' 			=> 1,
							'created_at' 		=> date('Y-m-d H:i:s'),
							'created_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'] ?? 'GI',
							'reference'			=> '',
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'is_paid'			=> $ispd,
							'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
							'version_no'		=> $attributes['version_no']
					]);
		
		$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
		
						
		return true;
	}
	
	//JUL22...
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $amount_type=null, $diffcost=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';  $ispd = ($diffcost)?5:0; //IDENTIFY COST DIFF TRANS...
		if($amount_type == 'LNTOTAL') {
			$account_id = $attributes['account_master_id'];
		} else if($amount_type == 'NTAMT') {
			$account_id = isset($attributes['job_account_id'])?$attributes['job_account_id']:'';
			
			//CHANGING Job account..
			if(isset($attributes['job_account_id']) && $attributes['job_account_id'] != $attributes['job_account_id_old']) {
				DB::table('account_transaction')
						->where('voucher_type_id', $voucher_id)
						->where('voucher_type', 'GI')
						->where('account_master_id', $attributes['job_account_id_old'])
						->update( ['account_master_id' => $attributes['job_account_id'] ]);
						
				$this->objUtility->tallyClosingBalance($attributes['job_account_id_old']);
			}
		}
		
		if($type == 'Cr') {
			$cr_acnt_id = $attributes['account_master_id'];
			$amount = ($amount_type=='CD')?$diffcost:$amount;
		} else if($type == 'Dr') {
			
			if($amount_type=='CD') {
				if(Session::get('department')==1) {
					$acc = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
					$dr_acnt_id = $acc->costdif_acid;
				} else {
					$acc = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->select('account_id')->first();
					$dr_acnt_id = $acc->account_id;
				}
				$amount = $diffcost;
			} else {
				$dr_acnt_id = isset($attributes['job_account_id'])?$attributes['job_account_id']:'';
				
				//CHANGING Job account..
				if(isset($attributes['job_account_id']) && $attributes['job_account_id'] != $attributes['job_account_id_old']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'GI')
							->where('account_master_id', $attributes['job_account_id_old'])
							->update( ['account_master_id' => $attributes['job_account_id'] ]);
							
					$this->objUtility->tallyClosingBalance($attributes['job_account_id_old']);
				}
			}
		}
		
		DB::table('account_transaction')
				->where('voucher_type_id', $voucher_id)
				->where('account_master_id', $account_id)
				->where('voucher_type', 'GI')
				->where('is_paid', $ispd)
				->update([  'amount'   			=> $amount,
							'modify_at' 		=> date('Y-m-d H:i:s'),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
						]);
						
		$this->objUtility->tallyClosingBalance($account_id);
		
						
		return true;
	}
	//...JUL22
	
	private function setPurchaseLog($attributes, $key, $document_id)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('unit_id', $attributes['unit_id'][$key])
									  ->first();
									  
		$balance_qty = $item->cur_quantity + $attributes['quantity'][$key];
		
		DB::table('item_stock')->insert(['document_type' => 'GI',
										 'document_id'   => $document_id,
										 'item_id' 	  => $attributes['item_id'][$key],
										 'unit_id'    => $attributes['unit_id'][$key],
										 'quantity'   => $attributes['quantity'][$key],
										 'status'     => 1,
										 'created_at' => date('Y-m-d H:i:s'),
										 'created_by' => Auth::User()->id,
										 'unit_cost'  => $attributes['cost'][$key],
										 'balance_qty' => $balance_qty ]);
		return true;
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])->where('unit_id', $attributes['unit_id'][$key])
				->update(['cur_quantity' => DB::raw('cur_quantity + '.$attributes['quantity'][$key] ),
						  'received_qty' => DB::raw('received_qty + '.$attributes['quantity'][$key] )
						]);
		return true;
	}
	
	//Accounting Method function............
	private function AccountingMethod($attributes, $line_total, $net_amount, $goods_issued_id)
	{
		//Debit Stock in Hand
		$this->setAccountTransaction($attributes, $line_total, $goods_issued_id, $type='Cr', $amount_type='LNTOTAL');
		
		//CHECK ITEMWISE JOB AND ACCOUNT ENRY
		if(!isset($attributes['item_job']))
			$this->setAccountTransaction($attributes, $net_amount, $goods_issued_id, $type='Dr', $amount_type='NTAMT');
		
	}
	
	//Purchase and Sales Method function............ NEW
	private function CostDifferenceEntry($attributes, $net_amount, $gin_id, $mod=null, $diffcost=null)
	{
		//Debit Customer Account
		if( $this->setAccountTransaction($attributes, $net_amount, $gin_id, $type='Dr', $mod, $diffcost) ) {
			
			//Credit Sales A/c
			$this->setAccountTransaction($attributes, $net_amount, $gin_id, $type='Cr', $mod, $diffcost);
		}
	}
	
	//JUL22... Accounting Method function............
	private function AccountingMethodUpdate($attributes, $line_total, $goods_issued_id)
	{
		//Debit Stock in Hand
		$this->setAccountTransactionUpdate($attributes, $line_total, $goods_issued_id, $type='Cr', $amount_type='LNTOTAL');
		
		//CHECK ITEMWISE JOB AND ACCOUNT ENRY
		if(!isset($attributes['item_job']))
			$this->setAccountTransactionUpdate($attributes, $line_total, $goods_issued_id, $type='Dr', $amount_type='NTAMT');
		
	}
	//...JUL22
	
	//JUL22... Accounting Method function............
	private function CostDifferenceEntryUpdate($attributes, $line_total, $goods_issued_id,$mod=null, $diffcost=null)
	{
		//Debit Stock in Hand
		$this->setAccountTransactionUpdate($attributes, $line_total, $goods_issued_id, $type='Cr', $mod='LNTOTAL', $diffcost);
		
		//Credit Supplier Accounting
		$this->setAccountTransactionUpdate($attributes, $line_total, $goods_issued_id, $type='Dr', $mod='NTAMT', $diffcost);
		
	}
	
	public function create($attributes)
	{ 
		if($this->isValid($attributes)) {
			DB::beginTransaction();
			try {

					//VOUCHER NO LOGIC.....................
					$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
					// 2️⃣ Get the highest numeric part from voucher_master
					$qry = DB::table('goods_issued')->where('deleted_at', '0000-00-00 00:0:00')->where('status', 1);
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

									$this->goods_issued->status = 1;
									$this->goods_issued->created_at = date('Y-m-d H:i:s');
									$this->goods_issued->created_by = Auth::User()->id;
									$this->goods_issued->save();

									$saved = true; // success ✅
									
								}	
							} catch (\Illuminate\Database\QueryException $ex) {

								// Check if it's a duplicate voucher number error
								if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
									strpos($ex->getMessage(), 'duplicate key value') !== false) {

									$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
									// 2️⃣ Get the highest numeric part from voucher_master
									$qry = DB::table('goods_issued')->where('deleted_at', '0000-00-00 00:0:00')->where('status', 1);
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

				 	
				//invoice items insert
				if($this->goods_issued->id && !empty( array_filter($attributes['item_id']))) {
					
					$line_total = 0; $total_quantity = $diffcost = 0;
					
					foreach($attributes['item_id'] as $key => $value){ 
						$goodsIssuedItem = new GoodsIssuedItem();
						$arrResult 	= $this->setItemInputValue($attributes, $goodsIssuedItem, $key, $value, $total_quantity); 
						//if($arrResult['line_total']) { echo 'hi';exit;
							$line_total			   += $arrResult['line_total'];
							$diffcost += $arrResult['diffcost'];
							
							$goodsIssuedItem->status = 1;
							$itemObj = $this->goods_issued->doItem()->save($goodsIssuedItem);
							
							$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);
								$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
									$logid = $this->setSaleLog($attributes, $key, $this->goods_issued->id, $CostAvg_log, $sale_cost, 'add' );
							
							if(isset($attributes['item_job'])) {
								//ITEM JOB ACCOUNT TRANSACTION...
								$this->SetItemJobAccountTransaction($itemObj, $key, $attributes);
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
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
										
										$itemLocationGI = new ItemLocationGI();
										$itemLocationGI->location_id = $attributes['locid'][$key][$lk];
										$itemLocationGI->item_id = $value;
										$itemLocationGI->unit_id = $attributes['unit_id'][$key];
										$itemLocationGI->quantity = $lq;
										$itemLocationGI->status = 1;
										$itemLocationGI->gi_id = $itemObj->id;
										$itemLocationGI->logid = $logid;
										$itemLocationGI->save();
									}
								}
							}
							
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								if($qtys)
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									
								$itemLocationGI = new ItemLocationGI();
								$itemLocationGI->location_id = $attributes['default_location'];
								$itemLocationGI->item_id = $value;
								$itemLocationGI->unit_id = $attributes['unit_id'][$key];
								$itemLocationGI->quantity = $attributes['quantity'][$key];
								$itemLocationGI->status = 1;
								$itemLocationGI->gi_id = $itemObj->id;
								$itemLocationGI->logid = $logid;
								$itemLocationGI->save();
								
							}

						//}
					}
					
					$net_amount = $line_total - $attributes['discount'];
					
					//update discount, total amount, vat and other cost....
					DB::table('goods_issued')
								->where('id', $this->goods_issued->id)
								->update([//'voucher_no'    =>$attributes['voucher_no'],
									      'total'    	  => $line_total,
										  'discount' 	  => $attributes['discount'],
										  'net_amount'	  => $net_amount ]
										 );
										  
					//Cost Accounting or Purchase and Sales Method ..... 
					$this->AccountingMethod($attributes, $line_total, $net_amount, $this->goods_issued->id);
					
					//COST DIFFERENCE AC ENTRY....
					if($diffcost > 0)
						$this->CostDifferenceEntry($attributes, $line_total, $this->goods_issued->id, 'CD', $diffcost);
					
				}
				
				DB::commit();
				return $this->goods_issued->id;//true;
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
				return false;
			}
		}
		//throw new ValidationException('goods_issued validation error12!', $this->getErrors());
		//
	}

	private function voucherNoGenerate($attributes) {

		$cnt = 0;
		do {
			$jvset = DB::table('account_setting')->where('id', $attributes['voucher_id'])->select('prefix','is_prefix','voucher_no')->first();
			if($jvset) {
				if($jvset->is_prefix==0) {
					$newattributes['voucher_no'] = $jvset->voucher_no + $cnt;
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				} else {
					$newattributes['voucher_no'] = $jvset->prefix.($jvset->voucher_no + $cnt);
					$newattributes['vno'] = $jvset->voucher_no + $cnt;
				}
				$newattributes['curno'] = $newattributes['voucher_no'];
			}

			if(Session::get('department')==1)
				$inv = DB::table('goods_issued')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('department_id', $attributes['department_id'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
			else
				$inv = DB::table('goods_issued')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
			//echo $inv.' - ';
			$cnt++;
		} while ($inv!=0);

		return $newattributes;
	}

	
	public function update($id, $attributes)
	{	//echo '<pre>';print_r($attributes);exit;
		$this->goods_issued = $this->find($id);
		$line_total = $diffcost = 0;
		if($this->goods_issued->id && !empty( array_filter($attributes['item_id']))) {

			//FIND CURRENT VERSION	 
			$voucher_type = 'GI';
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

			foreach($attributes['item_id'] as $key => $value) { 
				
				if($attributes['order_item_id'][$key]!='') {
					
					$lntotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
					$line_total += $lntotal;
					
					$goodsIssuedItem = GoodsIssuedItem::find($attributes['order_item_id'][$key]);//print_r($goodsIssuedItem);exit;//$attributes['order_item_id'][$key]);echo $attributes['order_item_id'][$key].'<pre>';
					$exi_quantity = $goodsIssuedItem->quantity;
					$items['item_name'] = $attributes['item_name'][$key];
					$items['item_id'] = $value;
					$items['unit_id'] = $attributes['unit_id'][$key];
					$items['quantity'] = $attributes['quantity'][$key];
					$items['unit_price'] = $attributes['cost'][$key];
					$items['discount'] = $attributes['line_discount'][$key];
					$items['total_price'] = $lntotal;
					
					if (!empty($attributes['jobid'][$key])) {
						$items['job_id'] = (int) $attributes['jobid'][$key];
					} elseif (!empty($attributes['item_job'])) {
						$items['job_id'] = (int) $attributes['item_job'];
					} elseif (!empty($attributes['job_id'])) {
						$items['job_id'] = (int) $attributes['job_id'];
					} else {
						$items['job_id'] = 0;
					}


					$items['account_id'] = isset($attributes['account_id'][$key])?$attributes['account_id'][$key]:'';
					
					$exi_item_id = $goodsIssuedItem->item_id;
					$exi_unit_id = $goodsIssuedItem->unit_id;
					$itemsobj = (object)['item_id' => $exi_item_id, 'unit_id' => $exi_unit_id];
						
					$goodsIssuedItem->update($items);
					
					if(isset($attributes['item_job'])) {
						//ITEM JOB ACCOUNT TRANSACTION...
						$this->SetItemJobAccountTransaction($attributes['order_item_id'][$key], $key, $attributes);
					}
						
					if($attributes['cost'][$key] < $attributes['actcost'][$key]) {
						$diffcost += $attributes['actcost'][$key] - $attributes['cost'][$key];
					}
						
					$bquantity = $attributes['quantity'][$key] - $exi_quantity;
					$attributes['sales_invoice_id'] = $attributes['goods_issued_id'];
					$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key, $bquantity);//exit;
					$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, 0);
						$this->setSaleLog($attributes, $key, $this->goods_issued->id, $CostAvg_log, $sale_cost, 'update', $itemsobj);
						
					//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									$edit = DB::table('item_location_gi')->where('id', $attributes['editid'][$key][$lk])->first();
									$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($edit) {
										if($edit->quantity < $lq) {
											$balqty = $lq - $edit->quantity;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
										} else {
											$balqty = $edit->quantity - $lq;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
										}
									}
									
									DB::table('item_location_gi')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lq]);
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
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
								DB::table('item_location_gi')->where('invoice_id', $attributes['order_item_id'][$key] )
															 ->where('location_id', $qtys->location_id)
															 ->where('item_id', $qtys->item_id)
															 ->where('unit_id', $qtys->unit_id)
															 ->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
							} 
						}
						//################ Location Stock Entry End ####################
						//$total_amt = $item_total;
										
				} else { //new entry...
					$goodsIssuedItem = new GoodsIssuedItem();
					$arrResult 		= $this->setItemInputValue($attributes, $goodsIssuedItem, $key, $value);
					//if($arrResult['line_total']) {
						$line_total			     += $arrResult['line_total'];
						$diffcost += $arrResult['diffcost'];
						
						$goodsIssuedItem->status = 1;
						$itemObj = $this->goods_issued->doItem()->save($goodsIssuedItem);
						
						if(isset($attributes['item_job'])) {
							//ITEM JOB ACCOUNT TRANSACTION...
							$this->SetItemJobAccountTransaction($itemObj, $key, $attributes);
						}
							
						$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);//exit;
						$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
							$logid = $this->setSaleLog($attributes, $key, $this->goods_issued->id, $CostAvg_log, $sale_cost, 'add' );
							
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
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
										
										$itemLocationGI = new ItemLocationGI();
										$itemLocationGI->location_id = $attributes['locid'][$key][$lk];
										$itemLocationGI->item_id = $value;
										$itemLocationGI->unit_id = $attributes['unit_id'][$key];
										$itemLocationGI->quantity = $lq;
										$itemLocationGI->status = 1;
										$itemLocationGI->gi_id = $itemObj->id;
										$itemLocationGI->logid = $logid;
										$itemLocationGI->save();
									}
								}
							}
							
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								if($qtys)
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									
								$itemLocationGI = new ItemLocationGI();
								$itemLocationGI->location_id = $attributes['default_location'];
								$itemLocationGI->item_id = $value;
								$itemLocationGI->unit_id = $attributes['unit_id'][$key];
								$itemLocationGI->quantity = $attributes['quantity'][$key];
								$itemLocationGI->status = 1;
								$itemLocationGI->gi_id = $itemObj->id;
								$itemLocationGI->logid = $logid;
								$itemLocationGI->save();
								
							}
					//}
				}
				
			}
		}
		
		if($this->setInputValue($attributes)) {
			$this->goods_issued->modify_at = date('Y-m-d H:i:s');
			$this->goods_issued->modify_by = Auth::User()->id;
			$this->goods_issued->fill($attributes)->save();
		}
		
		
					
		//manage removed items...
		if($attributes['remove_item']!='')
		{
			$arrids = explode(',', $attributes['remove_item']);
			$remline_total = $remtax_total = 0;
			foreach($arrids as $row) {
				
				$res = DB::table('goods_issued_item')->where('id',$row)->first();
				DB::table('goods_issued_item')->where('id', $row)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
					
				$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($res, $attributes['goods_issued_id'], 'GI');
			}
		}
		$this->goods_issued->fill($attributes)->save();
		
		$net_amount = $line_total - $attributes['discount'];
		
		
		//update discount, total amount
		DB::table('goods_issued')
					->where('id', $this->goods_issued->id)
					->update(['total'    	  => $line_total,
							  'discount' 	  => $attributes['discount'],
							  'net_amount'	  => $net_amount,
							 ]);

		//Cost Accounting or Purchase and Sales Method ..... JUL22..
		$this->AccountingMethod($attributes, $line_total, $net_amount, $this->goods_issued->id);
					
		//COST DIFFERENCE AC ENTRY....
		if($diffcost > 0)
			$this->CostDifferenceEntry($attributes, $line_total, $this->goods_issued->id, 'CD', $diffcost);
									  
		return true;
	}
	
		
	public function delete($id)
	{
		$this->goods_issued = $this->goods_issued->find($id);
		
		//inventory update...
		$items = DB::table('goods_issued_item')->where('goods_issued_id', $id)->select('item_id','unit_id','quantity')->get();
		
		foreach($items as $item) {
			$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($item,$id,'GI');
		}
		DB::table('goods_issued_item')->where('goods_issued_id', $id)
								->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		
		//Transaction update.... JUL22
		DB::table('account_transaction')->where('voucher_type', 'GI')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id ]);
		
		//reset account balance....
		$this->objUtility->tallyClosingBalance($this->goods_issued->account_master_id);
		
		$this->objUtility->tallyClosingBalance($this->goods_issued->job_account_id);
		
		$this->goods_issued->delete();
	}
	

	public function goodsIssuedList1()
	{
		$query = $this->goods_issued->where('goods_issued.status',1);
		return $query->leftjoin('jobmaster AS J', function($join) {
							$join->on('J.id','=','goods_issued.job_id');
						} )
					->select('goods_issued.*','J.name AS jobname')
					->orderBY('goods_issued.id', 'DESC')
					->get();
	}
	
	public function activePurchaseInvoiceList()
	{
		return $this->goods_issued->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->goods_issued->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->goods_issued->where('reference_no',$refno)->count();
	}
	
	public function check_invoice_id($invoice_id) { 
		
		return $this->goods_issued->where('voucher_no', $invoice_id)->count();
	}

	public function check_voucher_no($refno, $deptid, $id = null) { 
		
		if($id) {
			return $result = $this->goods_issued->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		} else {
			$query = $this->goods_issued->where('voucher_no',$refno); 
			return $result = ($deptid)?$query->where('department_id', $deptid)->count():$query->count();
		}
	}
	
	public function getPIdata()
	{
		$query = $this->goods_issued->where('goods_issued.status',1);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','goods_issued.job_account_id');
						} )
					->where('goods_issued.is_return',0)
					->select('goods_issued.*','am.master_name AS supplier')
					->orderBY('goods_issued.id', 'ASC')
					->get();
	}
		
	public function getSDOitems($id)
	{
		$query = $this->goods_issued->where('goods_issued.voucher_no',$id);
		
		return $query->join('purchase_invoice_item AS poi', function($join) {
							$join->on('poi.goods_issued_id','=','goods_issued.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->select('poi.*','u.unit_name')->get();
		//return $this->itemmaster->where('status', 1)->orderBy('description','ASC')->select('id','item_code','description')->get();
	}
	
	public function getSupplierInvoice($supplier_id)
	{
		return $this->goods_issued->where('status',1)
								   ->where('supplier_id', $supplier_id)
								   ->whereIn('amount_transfer',[0,2])
								   ->orderBY('id', 'ASC')
								   ->get();
	}
	
	public function getInvoiceReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->goods_issued->where('goods_issued.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','goods_issued.job_account_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','goods_issued.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								    ->select('AM.master_name AS supplier','AM.vat_no','goods_issued.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->goods_issued->where('goods_issued.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','goods_issued.job_account_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','goods_issued.job_id');
								   })
								   ->select('AM.master_name AS supplier','AM.vat_no','goods_issued.*','JM.name AS job')
								   ->orderBY('goods_issued.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getInvoice($attributes)
	{
		$invoice = $this->goods_issued->where('goods_issued.id', $attributes['document_id'])
								   ->join('jobmaster AS J', function($join) {
									   $join->on('J.id','=','goods_issued.job_id');
								   })
								   ->select('J.name AS supplier','goods_issued.*','J.code')
								   ->orderBY('goods_issued.id', 'ASC')
								   ->first();
								   
		$items = $this->goods_issued->where('goods_issued.id', $attributes['document_id'])
								   ->join('goods_issued_item AS GI', function($join) {
									   $join->on('GI.goods_issued_id','=','goods_issued.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','GI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','GI.unit_id');
								   })
								   ->leftjoin('jobmaster AS J', function($join) {
									   $join->on('J.id','=','GI.job_id');
								   })
								   ->select('GI.*','goods_issued.id','IM.item_code','U.unit_name','J.name as jobname')
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	}
	
	public function findPOdata($id)
	{
		$query = $this->goods_issued->where('goods_issued.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','goods_issued.job_account_id');
						} )
						->join('account_master AS am2', function($join){
							  $join->on('am2.id','=','goods_issued.account_master_id');
						  })
						->leftjoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','goods_issued.job_id');
						})
					->select('goods_issued.*','am.master_name AS jobaccount','am2.master_name AS account','J.name')
					->orderBY('goods_issued.id', 'ASC')
					->first();
	}
	
	public function getItems($id)
	{
		$query = $this->goods_issued->where('goods_issued.id',$id);
		
		return $query->join('goods_issued_item AS GI', function($join) {
							$join->on('GI.goods_issued_id','=','goods_issued.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','GI.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','GI.item_id');
					  })
					  ->leftjoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','GI.job_id');
					  })
					  ->leftjoin('account_master AS AM', function($join){
						  $join->on('AM.id','=','GI.account_id');
					  })
					  ->where('GI.status',1)
					  ->where('GI.deleted_at','0000-00-00 00:00:00')
					  ->select('GI.*','u.unit_name','im.item_code','J.name AS jobname','AM.master_name')->get();
	}
	
	private function setSaleLog($attributes, $key, $document_id, $cost_avg, $sale_cost, $action, $item=null)
	{
		if($action=='add') {
								
			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'GI',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $attributes['quantity'][$key],
							 'unit_cost'  => $sale_cost,
							 'trtype'	  => 0,
							 'cost_avg' => $cost_avg,
							 'pur_cost' => $sale_cost,
							 'sale_cost' => $sale_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			
		} else if($action=='update') {
			
			$logid = true;				
			$girow = DB::table('item_log')->where('document_type','GI')
							->where('document_id', $document_id)
							->where('item_id', $item->item_id)
							->where('unit_id', $item->unit_id)
							->where('status', 1)
							->where('deleted_at', '0000-00-00 00:00:00')
							->select('id')->first();
		if($girow)	{
		    
		    DB::table('item_log')->where('id', $girow->id)
		                ->update(['item_id' => $attributes['item_id'][$key],
							     'unit_id' => $attributes['unit_id'][$key],
								 'quantity'   => $attributes['quantity'][$key],
								 'unit_cost'  => $sale_cost, //(isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg,
								 'pur_cost' => $sale_cost,
								 'sale_cost' => $sale_cost,
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
							
    		} else {
    		    
    		    $logid = DB::table('item_log')->insertGetId([
    							 'document_type' => 'GI',
    							 'document_id'   => $document_id,
    							 'item_id' 	  => $attributes['item_id'][$key],
    							 'unit_id'    => $attributes['unit_id'][$key],
    							 'quantity'   => $attributes['quantity'][$key],
    							 'unit_cost'  => $sale_cost,
    							 'trtype'	  => 0,
    							 'cost_avg' => $cost_avg,
    							 'pur_cost' => $sale_cost,
    							 'sale_cost' => $sale_cost,
    							 'packing' => 1,
    							 'status'     => 1,
    							 'created_at' => date('Y-m-d H:i:s'),
    							 'created_by' => Auth::User()->id,
    							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
    							]);
    							
    		    /*->update(['item_id' => $attributes['item_id'][$key],
    							     'unit_id' => $attributes['unit_id'][$key],
    								 'quantity'   => $attributes['quantity'][$key],
    								 'unit_cost'  => $sale_cost, //(isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
    								 'cur_quantity' => $attributes['quantity'][$key],
    								 'cost_avg' => $cost_avg,
    								 'pur_cost' => $sale_cost,
    								 'sale_cost' => $sale_cost,
    								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
    							]);*/
    		}				
							
			
		}
		
		return $logid;
	}
	

public function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
		$query = $this->goods_issued
					->leftjoin('goods_issued_item AS POI', function($join) {
						$join->on('POI.goods_issued_id','=','goods_issued.id');
					})
					->leftjoin('account_master AS am2', function($join){
						$join->on('am2.id','=','goods_issued.account_master_id');
					})
					->leftjoin('account_master AS am', function($join) {
						$join->on('am.id','=','goods_issued.job_account_id');
					})
					->leftjoin('jobmaster AS JB', function($join) {
						$join->on('JB.id','=','goods_issued.job_id');
					})
					->where('POI.status',1)->where('goods_issued.is_itemjob',0);
							
		if( $date_from!='' && $date_to!='' ) { 
			$query->whereBetween('goods_issued.voucher_date', array($date_from, $date_to));
		}
		if(isset($attributes['job_id']) && $attributes['job_id']!=''){
			$query->whereIn('goods_issued.job_id', $attributes['job_id']);	
		}

		$query->select('goods_issued.voucher_no','goods_issued.voucher_date','goods_issued.total','goods_issued.discount','goods_issued.net_amount','am2.master_name AS account','am.master_name AS jobaccount',
							  'POI.quantity','POI.item_name','POI.total_price','POI.unit_price','JB.code','JB.name AS jobname');
		
		if($attributes['search_type']=='summary')
			$query->groupBy('goods_issued.id');
		
		$result['normal'] = $query->get();//->toArray(); 
		
		$query2 = $this->goods_issued
						->leftjoin('goods_issued_item AS POI', function($join) {
							$join->on('POI.goods_issued_id','=','goods_issued.id');
						})
						->leftjoin('account_master AS am2', function($join){
							$join->on('am2.id','=','goods_issued.account_master_id');
						})
						->leftjoin('jobmaster AS IJB', function($join) {
							$join->on('IJB.id','=','POI.job_id');
						})
						->leftjoin('account_master AS IAM', function($join) {
							$join->on('IAM.id','=','POI.account_id');
						})
						->where('POI.status',1)->where('goods_issued.is_itemjob',1);
							
		if( $date_from!='' && $date_to!='' ) { 
			$query2->whereBetween('goods_issued.voucher_date', array($date_from, $date_to));
		}
		if(isset($attributes['job_id']) && $attributes['job_id']!=''){
			$query2->whereIn('POI.job_id', $attributes['job_id']);	
		}
		
		if($attributes['search_type']=='summary')
			$query2->groupBy('goods_issued.id');
		
		$query2->select('goods_issued.voucher_no','goods_issued.voucher_date','goods_issued.total','goods_issued.discount','goods_issued.net_amount','am2.master_name AS account',
							  'IAM.master_name AS jobaccount','POI.quantity','POI.total_price','POI.unit_price','IJB.code','IJB.name AS jobname');
		$result['itemjob'] = $query2->get();//->toArray(); 
						
		return $result;
	}
	
	public function goodsIssuedListCount()
	{
		$query = $this->goods_issued->where('goods_issued.status',1);
		return $query->join('jobmaster AS J', function($join) {
							$join->on('J.id','=','goods_issued.job_id');
						} )
					->count();
	}
	
	public function goodsIssuedList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->goods_issued->where('goods_issued.status',1);
		$query->leftjoin('jobmaster AS J', function($join) {
							$join->on('J.id','=','goods_issued.job_id');
						} );
			$query->leftjoin('vehicle AS V', function($join) {
							$join->on('V.id','=','J.vehicle_id');
						} );		
		if($search) {
				
			$query->where(function($qry) use($search) {
				$qry->where('goods_issued.voucher_no','LIKE',"%{$search}%")
					->orWhere('J.name', 'LIKE',"%{$search}%");
			});
		}
			
		$query->select('goods_issued.*','J.name AS jobname','J.code AS jobcode','V.reg_no')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
		if($type=='get')
			return $query->get();
		else
			return $query->count();
	}
	
	private function SetItemJobAccountTransaction($itemid, $key, $attributes) {
		
		$amount = $attributes['line_total'][$key];
		$itemid = $itemid->id;
		$dr_acnt_id = $attributes['account_id'][$key];
			
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'GI',
								'voucher_type_id'   => $this->goods_issued->id,
								'account_master_id' => $dr_acnt_id,
								'transaction_type'  => 'Dr',
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['item_name'][$key] ?? 'GI',
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> $attributes['jobid'][$key],
								'other_info'		=> $itemid,
								'version_no'		=> $attributes['version_no']
							]);
								
		return true;
	}
	
	private function SetItemJobAccountTransactionUpdate($gid, $itemid, $key, $attributes) {
		
		$amount = $attributes['line_total'][$key];
		$dr_acnt_id = $attributes['account_id'][$key];
			
		DB::table('account_transaction')
					->where('voucher_type', 'GI')
					->where('voucher_type_id', $gid)
					->where('transaction_type','Dr')
					->where('other_info', $itemid)
					->update([  'account_master_id' => $dr_acnt_id,
								'amount'   			=> $amount,
								'modify_at' 		=> date('Y-m-d H:i:s'),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['item_name'][$key],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:'',
								'job_id'			=> $attributes['jobid'][$key]
							]);
								
		return true;
		
	}
	
	public function removeItems($attributes) {
		
		$arrids = explode(',', $attributes['remove_item']);
		foreach($arrids as $row) {
			$res = DB::table('goods_issued_item')->where('id',$row)->first();
			DB::table('goods_issued_item')->where('id', $row)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
				
			$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($res, $attributes['goods_issued_id'], 'GI');
		}
	}
	
}