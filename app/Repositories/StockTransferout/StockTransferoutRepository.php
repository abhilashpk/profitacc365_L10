<?php
declare(strict_types=1);
namespace App\Repositories\StockTransferout;

use App\Models\StockTransferout;
use App\Models\StockTransferoutItem;
use App\Models\StockTransferinItem;
use App\Models\StockTransferoutInfo;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use App\Models\ItemLocationTO;
use Config;
use Illuminate\Support\Facades\DB;
use Auth;
use Storage;
use Illuminate\Support\Facades\Session;

class StockTransferoutRepository extends AbstractValidator implements StockTransferoutInterface {
	
	protected $stock_transferout;
	
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(StockTransferout $stock_transferout) {
		$this->stock_transferout = $stock_transferout;
		$this->objUtility = new UpdateUtility();
		
		$this->modbatch = DB::table('parameter2')->where('keyname','mod_item_batch')->select('is_active')->first();
	}
	
	public function all()
	{
		return $this->stock_transferout->get();
	}
	
	public function find($id)
	{
		return $this->stock_transferout->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->stock_transferout->voucher_no = $attributes['voucher_no'];
		$this->stock_transferout->reference_no = isset($attributes['reference_no'])?$attributes['reference_no']:'';
		$this->stock_transferout->description = isset($attributes['description'])?$attributes['description']:'';
		//$this->stock_transferout->job_id = $attributes['job_id'];
		$this->stock_transferout->account_dr = $attributes['account_dr'];
		$this->stock_transferout->account_cr = $attributes['account_cr'];
		$this->stock_transferout->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->stock_transferout->is_mfg =  isset($attributes['is_mfg'])?$attributes['is_mfg']:'';
		$this->stock_transferout->department_id = isset($attributes['department_id'])?$attributes['department_id']:'';
		
		return true;
	}
	
	private function setItemInputValue($attributes, $stockTransferinItem, $key, $value) 
	{
		$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
		
		$stockTransferinItem->stock_transferout_id = $this->stock_transferout->id;
		$stockTransferinItem->item_id = $attributes['item_id'][$key];
		$stockTransferinItem->unit_id = $attributes['unit_id'][$key];
		$stockTransferinItem->item_name = $attributes['item_name'][$key];
		$stockTransferinItem->quantity = $attributes['quantity'][$key];
		$stockTransferinItem->price = $attributes['cost'][$key];
		$stockTransferinItem->item_total = $item_total;
		$stockTransferinItem->mfg_item_id = isset($attributes['mfg_item_id'][$key])?$attributes['mfg_item_id'][$key]:0;
		
		$diffcost = 0;
		if($attributes['cost'][$key] < $attributes['actcost'][$key]) {
			$diffcost = $attributes['actcost'][$key] - ($attributes['cost'][$key] * $attributes['quantity'][$key]);
		}
		
		return array('line_total' => $attributes['quantity'][$key], 'item_total' => $item_total, 'diffcost' => $diffcost);
		
	}
	
	//Purchase and Sales Method function............
	private function PurchaseAndSalesMethod($attributes, $net_amount, $transferin_id, $mod=null, $diffcost=null)
	{
		//Debit Customer Account
		if( $this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Dr', $mod, $diffcost) ) {
			
			//Credit Sales A/c
			$this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Cr', $mod, $diffcost);
		}
	}
	
	private function PurchaseAndSalesMethodUpdate($attributes, $net_amount, $transferin_id, $mod=null, $diffcost=null)
	{
		//Debit Customer Account
		if( $this->setAccountTransactionUpdate($attributes, $net_amount, $transferin_id, $type='Dr', $mod, $diffcost) ) {
			
			//Credit Sales A/c
			$this->setAccountTransactionUpdate($attributes, $net_amount, $transferin_id, $type='Cr', $mod, $diffcost);
		}
	}
	
	public function getReportVehicle($attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$query =  DB::table('stock_transferout')
		->join('stock_transferout_item AS POI', function($join) {
			$join->on('POI.stock_transferout_id','=','stock_transferout.id');
		})
		->join('itemmaster AS IM', function($join) {
			$join->on('IM.id','=','POI.item_id');
		})
	->join('account_master AS AMD', function($join) {
			$join->on('AMD.id','=','stock_transferout.account_dr');
		} )
		->join('account_setting AS AST', function($join) {
			$join->on('AST.id','=','stock_transferout.voucher_no');
		})
	
		->leftJoin('department AS D', function($join) {
			$join->on('D.id','=','stock_transferout.department_id');
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
						->where('stock_transferout.status',1)
						->where('stock_transferout.deleted_at','0000-00-00 00:00-00');
						
				if(isset($attributes['group_id']))
					$query->whereIn('IM.group_id', $attributes['group_id']);
				
				if(isset($attributes['subgroup_id']))
					$query->whereIn('IM.subgroup_id', $attributes['subgroup_id']);
				
				if(isset($attributes['category_id']))
					$query->whereIn('IM.category_id', $attributes['category_id']);
				
				if(isset($attributes['subcategory_id']))
					$query->whereIn('IM.subcategory_id', $attributes['subcategory_id']);
						
				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('stock_transferout.voucher_date', array($date_from, $date_to));
				}
				
				if( $attributes['search_type']=='daily' ) { 
					$query->whereBetween( 'stock_transferout.voucher_date', array(date('Y-m-d'), date('Y-m-d')) );
				}
			

		if(isset($attributes['item_id']) && $attributes['item_id']!='')
					$query->whereIn('POI.item_id', $attributes['item_id']);	

				
				if( $attributes['search_type']=='department') { 
					$query->where('stock_transferout.department_id','!=',0);
				}
				
			
		//echo '<pre>';print_r($query);exit;		
		 $query->select('stock_transferout.voucher_no','stock_transferout.reference_no','stock_transferout.total_amt','stock_transferout.net_total',
		 'stock_transferout.discount','AMD.master_name AS name_dr',
		 'POI.item_total','POI.item_name','POI.price','IM.description','stock_transferout.voucher_date','POI.item_id',
		 'POI.quantity','stock_transferout.id AS id','stock_transferout.voucher_no AS voucher_no',
		 'stock_transferout.net_total','D.name','stock_transferout.department_id','POI.stock_transferout_id',
		 'IM.item_code','GP.group_name AS group','SGP.group_name AS subgroup',
		 'CAT.category_name AS category','SCAT.category_name AS subcategory');
		 
		 //echo '<pre>';print_r($attributes);exit;		
				
		
			
								 
			return $query->get();//->groupBy('sales_invoice.id')
		
	}
	public function getr($attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$query =  DB::table('stock_transferout')
		->join('stock_transferout_item AS STOI', function($join) {
			$join->on('STOI.stock_transferout_id','=','stock_transferout.id');
		})
		->join('itemmaster AS IM', function($join) {
			$join->on('IM.id','=','STOI.item_id');
		})
	
		->join('account_master AS AMD', function($join) {
			$join->on('AMD.id','=','stock_transferout.account_cr');
		} )
		->leftJoin('department AS D', function($join) {
			$join->on('D.id','=','stock_transferout.department_id');
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
		->where('STOI.status',1)
		->where('STOI.deleted_at','0000-00-00 00:00-00')
		->where('stock_transferout.status',1)
		->where('stock_transferout.deleted_at','0000-00-00 00:00-00');
		if(isset($attributes['dept_id']))
					$query->whereIn('stock_transferout.department_id', $attributes['dept_id']);
		if(isset($attributes['group_id']))
					$query->whereIn('IM.group_id', $attributes['group_id']);
				
				if(isset($attributes['subgroup_id']))
					$query->whereIn('IM.subgroup_id', $attributes['subgroup_id']);
				
				if(isset($attributes['category_id']))
					$query->whereIn('IM.category_id', $attributes['category_id']);
				
				if(isset($attributes['subcategory_id']))
					$query->whereIn('IM.subcategory_id', $attributes['subcategory_id']);
						
				if( $date_from!='' && $date_to!='' ) { 
					$query->whereBetween('stock_transferout.voucher_date', array($date_from, $date_to));
				}
				
				if( $attributes['search_type']=='daily' ) { 
					$query->whereBetween( 'stock_transferout.voucher_date', array(date('Y-m-d'), date('Y-m-d')) );
				}
				
				/*if($attributes['salesman']!='') { 
					$query->where('sales_invoice.salesman_id', $attributes['salesman']);
				}*/
			
				
				 if( $attributes['search_type']=='department') { 
				 	$query->where('stock_transferout.department_id','!=',0);
				 }
			
			

		if(isset($attributes['item_id']) && $attributes['item_id']!='')
					$query->whereIn('STOI.item_id', $attributes['item_id']);	
		
					$query->select('stock_transferout.voucher_no','stock_transferout.reference_no','stock_transferout.total_amt','stock_transferout.net_total',
					'stock_transferout.discount','AMD.master_name AS name_cr',
					'STOI.item_total','STOI.item_name','STOI.price','IM.description','stock_transferout.voucher_date','STOI.item_id',
					'STOI.quantity','stock_transferout.id AS id',
					'stock_transferout.net_total','D.name','stock_transferout.department_id','STOI.stock_transferout_id',
					'IM.item_code','GP.group_name AS group','SGP.group_name AS subgroup',
					'CAT.category_name AS category','SCAT.category_name AS subcategory');
		return $query->get();				
		

}
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $mod=null, $diffcost=null)
	{
		$cr_acnt_id = $dr_acnt_id = ''; $ispd = ($diffcost)?5:0; //IDENTIFY COST DIFF TRANS...
		if($type == 'Cr') {
			$cr_acnt_id = $attributes['account_cr'];
			$amount = ($mod=='CD')?$diffcost:$amount;
		} else if($type == 'Dr') {
			
			if($mod=='CD') {
				if(Session::get('department')==1) {
					$acc = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
					$dr_acnt_id = $acc->costdif_acid;
				} else {
					$acc = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->select('account_id')->first();
					$dr_acnt_id = $acc->account_id;
				}
				$amount = $diffcost;
			} else
				$dr_acnt_id = $attributes['account_dr'];
		}
		
		if( ($cr_acnt_id!='' || $cr_acnt_id!=0) || ($dr_acnt_id!='' || $dr_acnt_id!=0) ) {
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'STO',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> isset($attributes['description'])?$attributes['description']:'',
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'is_paid'			=> $ispd,
								'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
		}
							
		return true;
	}
	
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $mod=null, $diffcost=null)
	{
		$ispd = ($diffcost)?5:0; //IDENTIFY COST DIFF TRANS...
		if($type == 'Cr') {
			$account_id = $attributes['account_cr'];
			$amount = ($mod=='CD')?$diffcost:$amount;
		} else if($type == 'Dr') {
			if($mod=='CD') {
				if(Session::get('department')==1) {
					$acc = DB::table('department_accounts')->where('department_id', $attributes['department_id'])->select('costdif_acid')->first();
					$dr_acnt_id = $acc->costdif_acid;
				} else {
					$acc = DB::table('other_account_setting')->where('account_setting_name', 'Cost Difference')->select('account_id')->first();
					$account_id = $acc->account_id;
				}
				$amount = $diffcost;
			} else
				$account_id = $attributes['account_dr'];
		}
		
		DB::table('account_transaction')
				->where('voucher_type_id', $voucher_id)
				->where('account_master_id', $account_id)
				->where('voucher_type', 'STO')
				->where('is_paid', $ispd)
				->update([  'amount'   			=> $amount,
							'modify_at' 		=> now(),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'department_id'		=> isset($attributes['department_id'])?$attributes['department_id']:''
						]);
						
		
		$this->objUtility->tallyClosingBalance($account_id);
						
		return true;
	}
	
	
	public function create($attributes)
	{
	   // echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
		  DB::beginTransaction();
		  try {
                
			  
				if($this->setInputValue($attributes)) {
					$this->stock_transferout->status = 1;
					$this->stock_transferout->created_at = now();
					$this->stock_transferout->created_by = 1;
					$this->stock_transferout->fill($attributes)->save();
				}
				
				//order items insert
				if($this->stock_transferout->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = $item_total = $diffcost = 0;
					
					foreach($attributes['item_id'] as $key => $value) { 
						$stockTransferinItem = new StockTransferoutItem();
						$arrResult 		= $this->setItemInputValue($attributes, $stockTransferinItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total'];
							$diffcost += $arrResult['diffcost'];
							
							$stockTransferinItem->status = 1;
							$itemObj = $this->stock_transferout->transferItem()->save($stockTransferinItem);
							
							$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);
								$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
									$logid = $this->setSaleLog($attributes, $key, $this->stock_transferout->id, $CostAvg_log, $sale_cost, 'add' );
									
									
							//################ Location Stock Entry ####################
							//Item Location specific add....
							$updated = false;
							if(isset($attributes['locqty'][$key])) {
								foreach($attributes['locqty'][$key] as $lk => $lq) {
									if($lq!='') {
										$updated = true;
										$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->whereNull('deleted_at')->select('id')->first();
										if($qtys)
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
										
										$itemLocationTO = new ItemLocationTO();
										$itemLocationTO->location_id = $attributes['locid'][$key][$lk];
										$itemLocationTO->item_id = $value;
										$itemLocationTO->unit_id = $attributes['unit_id'][$key];
										$itemLocationTO->quantity = $lq;
										$itemLocationTO->status = 1;
										$itemLocationTO->trout_id = $itemObj->id;
										$itemLocationTO->logid = $logid;
										$itemLocationTO->save();
									}
								}
							}
							
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->whereNull('deleted_at')->select('id')->first();
								if($qtys)
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									
								$itemLocationTO = new ItemLocationTO();
								$itemLocationTO->location_id = $attributes['default_location'];
								$itemLocationTO->item_id = $value;
								$itemLocationTO->unit_id = $attributes['unit_id'][$key];
								$itemLocationTO->quantity = $attributes['quantity'][$key];
								$itemLocationTO->status = 1;
								$itemLocationTO->trout_id = $itemObj->id;
								$itemLocationTO->logid = $logid;
								$itemLocationTO->save();
								
							}
						//exit;
							//BATCH ENTRY...
							if(isset($this->modbatch) && $this->modbatch->is_active==1 && $attributes['is_batch']==true) {
    							$d = 0;
                                $reqQty = $attributes['quantity'][$key];
                                $batchArr = DB::table('item_batch')->where('item_id', $value)->where('quantity','>',0)->whereNull('deleted_at')->orderBy('exp_date')->select('id','batch_no','quantity')->get();
                                
                                while ($reqQty > 0 && $d < count($batchArr)) {
                                    $currentBatch = $batchArr[$d];
                                    
                                    if ($reqQty < $currentBatch->quantity) {
                                        $bQty = $reqQty;
                                        $reqQty = 0;
                                    } else {
                                        $bQty = $currentBatch->quantity;
                                        $reqQty -= $bQty;
                                    }
                                
                                    DB::table('batch_log')->insert([
                                        'batch_id' => $currentBatch->id,
                                        'item_id' => $value,
                                        'document_type' => 'TO',
                                        'document_id' => $this->stock_transferout->id,
                                        'doc_row_id' => $itemObj->id,
                                        'quantity' => $bQty,
                                        'trtype' => 0,
                                        'invoice_date' => ($attributes['voucher_date'] == '') 
                                            ? date('Y-m-d') 
                                            : date('Y-m-d', strtotime($attributes['voucher_date'])),
                                        'log_id' => $logid,
                                        'created_at' => now(),
                                        'created_by' => Auth::user()->id
                                    ]);
                                
                                    DB::table('item_batch')
                                        ->where('id', $currentBatch->id)
                                        ->update([
                                            'quantity' => DB::raw('quantity - '.$bQty)
                                        ]);
                                
                                    $d++;
                                }
                                
                                if ($reqQty > 0) {
                                    // Handle insufficient stock scenario
                                    throw new \Exception("Not enough stock in batches for item ID {$value}");
                                }
							}

						
						}
					} 
					
					$total_amt = $item_total;
					//VOUCHER NO INCREMENT LOGIC//
				if( $attributes['curno'] == $attributes['voucher_no'] ) {
					$attributes['rowid'] = $this->stock_transferout->id;
					$newattributes = $this->voucherNoGenerate($attributes);
					$attributes['voucher_no'] = $newattributes['voucher_no'];
					$attributes['vno'] = $newattributes['vno'];
					$attributes['curno'] = $newattributes['curno'];
				} 
				//VOUCHER NO INCREMENT LOGIC//
					//update discount, total amount
					DB::table('stock_transferout')
								->where('id', $this->stock_transferout->id)
								->update(['voucher_no'    =>$attributes['voucher_no'],
									'total_qty' => $line_total, 
									'total_amt' => $total_amt, 
									'net_total' => $total_amt]);
					
					$this->PurchaseAndSalesMethod($attributes, $item_total, $this->stock_transferout->id);
					
					//COST DIFFERENCE AC ENTRY....
					if($diffcost > 0)
						$this->PurchaseAndSalesMethod($attributes, $item_total, $this->stock_transferout->id, 'CD', $diffcost);
				}
				
				//update voucher no........
				if( ($this->stock_transferout->id) && ($attributes['curno'] == $attributes['voucher_no']) ) { 
					 if(Session::get('department')==1) {
						DB::table('account_setting')
							->where('voucher_type_id', 22) 
							->where('department_id', $attributes['department_id'])
							->update(['voucher_no' => $DB::raw('voucher_no + 1') ]);
					 } else {
						 DB::table('account_setting')
							->where('voucher_type_id', 22) 
							->update(['voucher_no' => DB::raw('voucher_no + 1') ]);
					 }
				}
					
				
				DB::commit();
				return $this->stock_transferout->id;
				
		  } catch(\Exception $e) {
				
				DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
				return false;
		  }
		  
		}
		
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
				$inv = DB::table('stock_transferout')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('department_id', $attributes['department_id'])->where('status',1)->whereNull('deleted_at')->count();
			else
				$inv = DB::table('stock_transferout')->where('id','!=',$attributes['rowid'])->where('voucher_no',$newattributes['voucher_no'])->where('status',1)->whereNull('deleted_at')->count();
			//echo $inv.' - ';
			$cnt++;
		} while ($inv!=0);

		return $newattributes;
	}

	
	
	public function update($id, $attributes)
	{  //echo '<pre>';print_r($attributes);exit;
		$this->stock_transferout = $this->find($id); 
		$line_total = $item_total = $diffcost = 0; 
		
		DB::beginTransaction();
		try {
			
			if($this->stock_transferout->id && !empty( array_filter($attributes['item_id']))) {  
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['transfer_item_id'][$key]!='') { 
						
						$item_total += $attributes['cost'][$key] * $attributes['quantity'][$key];
						$stockTransferinItem = StockTransferoutItem::find($attributes['transfer_item_id'][$key]); 
						//echo '<pre>';print_r($stockTransferinItem);exit;
						
						$exi_quantity = $stockTransferinItem->quantity;
						
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['price'] = $attributes['cost'][$key];
						$items['item_total'] = $attributes['quantity'][$key] * $attributes['cost'][$key];
						$stockTransferinItem->update($items);
						
						$line_total	+= $attributes['quantity'][$key];
						
						$bquantity = $attributes['quantity'][$key] - $exi_quantity; 
						$attributes['sales_invoice_id'] = $this->stock_transferout->id;//JUL22
						
						if($attributes['cost'][$key] < $attributes['actcost'][$key]) {
							$diffcost += $attributes['actcost'][$key] - $attributes['cost'][$key];
						}
		
						$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key, $bquantity);
						$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, 0);
						$logid = $this->setSaleLog($attributes, $key, $this->stock_transferout->id, $CostAvg_log, $sale_cost, 'update' );
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									$edit = DB::table('item_location_to')->where('id', $attributes['editid'][$key][$lk])->first();
									$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->whereNull('deleted_at')->select('id')->first();
									if($edit) {
										if($edit->quantity < $lq) {
											$balqty = $lq - $edit->quantity;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
										} else {
											$balqty = $edit->quantity - $lq;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
										}
									}
									
									DB::table('item_location_to')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lq]);
								}
							}
						}
						
						//Item default location add...
						//if(($attributes['location_id']!='') && ($updated == false)) {
						if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
															  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
															  ->whereNull('deleted_at')->select('*')->first();
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
								DB::table('item_location_to')->where('invoice_id', $attributes['order_item_id'][$key] )
															 ->where('location_id', $qtys->location_id)
															 ->where('item_id', $qtys->item_id)
															 ->where('unit_id', $qtys->unit_id)
															 ->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
							} 
						}
						//################ Location Stock Entry End ####################
						//$total_amt = $item_total;
						
						//BATCH ENTRY...
						if(isset($this->modbatch) && $this->modbatch->is_active==1 && isset($attributes['is_batch'])&& $attributes['is_batch']==true) {

                            
                            ///new
                            // 1. Reverse the previous batch operations
                            $existingLogs = DB::table('batch_log')
                                ->where('document_type', 'TO')
                                ->where('document_id', $this->stock_transferout->id)
                                ->where('doc_row_id', $stockTransferinItem->id)
                                ->get();
                            
                            foreach ($existingLogs as $log) {
                                // Restore the batch quantity
                                DB::table('item_batch')
                                    ->where('id', $log->batch_id)
                                    ->update([
                                        'quantity' => DB::raw('quantity + '.$log->quantity)
                                    ]);
                            }
                            
                            // Delete the old logs
                            DB::table('batch_log')
                                ->where('document_type', 'TO')
                                ->where('document_id', $this->stock_transferout->id)
                                ->where('doc_row_id', $stockTransferinItem->id)
                                ->delete();
                            
                            
                            // 2. Re-run the allocation logic with updated quantity
                            $d = 0;
                            $reqQty = $attributes['quantity'][$key];
                            
                            $batchArr = DB::table('item_batch')
                                ->where('item_id', $value)
                                ->where('quantity', '>', 0)
                                ->whereNull('deleted_at')
                                ->orderBy('exp_date')
                                ->select('id', 'batch_no', 'quantity')
                                ->get();
                            
                            while ($reqQty > 0 && $d < count($batchArr)) {
                                $currentBatch = $batchArr[$d];
                            
                                if ($reqQty < $currentBatch->quantity) {
                                    $bQty = $reqQty;
                                    $reqQty = 0;
                                } else {
                                    $bQty = $currentBatch->quantity;
                                    $reqQty -= $bQty;
                                }
                            
                                DB::table('batch_log')->insert([
                                    'batch_id' => $currentBatch->id,
                                    'item_id' => $value,
                                    'document_type' => 'TO',
                                    'document_id' => $this->stock_transferout->id,
                                    'doc_row_id' => $stockTransferinItem->id,
                                    'quantity' => $bQty,
                                    'trtype' => 0,
                                    'invoice_date' => empty($attributes['voucher_date']) 
                                        ? date('Y-m-d') 
                                        : date('Y-m-d', strtotime($attributes['voucher_date'])),
                                    'log_id' => $logid,
                                    'created_at' => now(),
                                    'created_by' => Auth::user()->id
                                ]);
                            
                                DB::table('item_batch')
                                    ->where('id', $currentBatch->id)
                                    ->update([
                                        'quantity' => DB::raw('quantity - '.$bQty)
                                    ]);
                            
                                $d++;
                            }
                            
                            if ($reqQty > 0) {
                                throw new \Exception("Not enough stock in batches for item ID {$value}");
                            }
                            
                            //end...new
						}
						
					} else { //new entry...
						$line_total_new = $item_total_new = 0;
						
						$stockTransferinItem = new StockTransferoutItem();
						$arrResult 		= $this->setItemInputValue($attributes, $stockTransferinItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total_new	+= $arrResult['line_total'];
							$item_total_new	+= $arrResult['item_total'];
							
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total'];
							
							$diffcost += $arrResult['diffcost'];
							
							$stockTransferinItem->status = 1;
							$itemObj = $this->stock_transferout->transferItem()->save($stockTransferinItem);
							
							$sale_cost = $this->objUtility->updateItemQuantitySales($attributes, $key);
								$CostAvg_log = $this->objUtility->updateLastPurchaseCostAndCostAvg($attributes, $key, 0);
									$logid = $this->setSaleLog($attributes, $key, $this->stock_transferout->id, $CostAvg_log, $sale_cost, 'add' );
									
							//################ Location Stock Entry ####################
							//Item Location specific add....
							$updated = false;
							if(isset($attributes['locqty'][$key])) {
								foreach($attributes['locqty'][$key] as $lk => $lq) {
									if($lq!='') {
										$updated = true;
										$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																	  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																	  ->whereNull('deleted_at')->select('id')->first();
										if($qtys)
											DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$lq) ]);
										
										$itemLocationTO = new ItemLocationTO();
										$itemLocationTO->location_id = $attributes['locid'][$key][$lk];
										$itemLocationTO->item_id = $value;
										$itemLocationTO->unit_id = $attributes['unit_id'][$key];
										$itemLocationTO->quantity = $lq;
										$itemLocationTO->status = 1;
										$itemLocationTO->trout_id = $itemObj->id;
										$itemLocationTO->logid = $logid;
										$itemLocationTO->save();
									}
								}
							}
							
							
							//Item default location add...
							if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
									
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
																  ->where('item_id', $value)->where('unit_id', $attributes['unit_id'][$key])
																  ->whereNull('deleted_at')->select('id')->first();
								if($qtys)
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity - '.$attributes['quantity'][$key]) ]);
									
								$itemLocationTO = new ItemLocationTO();
								$itemLocationTO->location_id = $attributes['default_location'];
								$itemLocationTO->item_id = $value;
								$itemLocationTO->unit_id = $attributes['unit_id'][$key];
								$itemLocationTO->quantity = $attributes['quantity'][$key];
								$itemLocationTO->status = 1;
								$itemLocationTO->trout_id = $itemObj->id;
								$itemLocationTO->logid = $logid;
								$itemLocationTO->save();
								
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
					
					$res = DB::table('stock_transferout_item')->where('id', $row)->first();
					DB::table('stock_transferout_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					
					$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($res, $id, 'TO');
				}
			}
			
			if($this->setInputValue($attributes)) {
				$this->stock_transferout->modify_at = now();
				$this->stock_transferout->modify_by = Auth::User()->id;
				$this->stock_transferout->fill($attributes)->save();
			}
			
			$this->PurchaseAndSalesMethodUpdate($attributes, $item_total, $this->stock_transferout->id);
			
			//COST DIFFERENCE AC ENTRY....
			if($diffcost > 0)
				$this->PurchaseAndSalesMethodUpdate($attributes, $item_total, $this->stock_transferout->id, 'CD', $diffcost);
					
			//$total = $line_total + $line_total_new;
			
			//update discount, total amount
			DB::table('stock_transferout')
						->where('id', $id)
						->update(['total_qty' => $line_total,'total_amt' => $item_total, 'net_total' => $item_total]); //CHG
			
			DB::commit();
			return true;
			
		 } catch(\Exception $e) {
			
			DB::rollback(); echo 'Fr: '.$e->getLine().' '.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
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
	
	
	
	public function delete($id)
	{
		$this->stock_transferout = $this->stock_transferout->find($id);
		
		$items = DB::table('stock_transferout_item')->where('stock_transferout_id',$id)->get();
		
		//Transaction update....
		DB::table('account_transaction')->where('voucher_type', 'STO')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);
		
		//reset account balance....
		$this->objUtility->tallyClosingBalance($this->stock_transferout->account_dr);
		
		$this->objUtility->tallyClosingBalance($this->stock_transferout->account_cr);
		
		//inventory update...
		foreach($items as $item) {
			$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($item,$id,'TO');
		}
			
		DB::table('stock_transferout_item')->where('stock_transferout_id',$id)->update(['status' => 0, 'deleted_at' => now()]);
		DB::table('stock_transferout')->where('id', $id)
									  ->update(['status' => 0, 'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);	
						  
		$this->stock_transferout->delete($id);
	}
	
	public function check_order($id)
	{
		$count = DB::table('stock_transferout')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else
			return true;
	}
	
	public function stockTransList()
	{
		return $this->stock_transferout->where('status',1)
					->orderBY('stock_transferout.id', 'DESC')->get();
		
	}
		public function getTransactionList($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->stock_transferout
								   ->join('stock_transferout_item AS SI', function($join) {
									   $join->on('SI.stock_transferout_id','=','stock_transferout.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','SI.item_id');
								   })
								   ->where('SI.status',1)
								   ->where('SI.deleted_at','0000-00-00 00:00:00')
								   ->where('stock_transferout.status',1);
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('stock_transferout.voucher_date',[$date_from, $date_to]);

					       

		$result = $qry->select('stock_transferout.id','stock_transferout.voucher_no','stock_transferout.voucher_date',
								'IM.item_code','IM.description','SI.quantity','SI.price AS unit_price',DB::raw('"" AS vat_amount'),'SI.item_total AS total_price')
								   ->orderBY('stock_transferout.voucher_date', 'ASC')
								   ->get();
								   
		return $result;
	}
	
	
	public function findRow($id)
	{
		return $this->stock_transferout->where('stock_transferout.id', $id)
					->join('account_master AS AMD', function($join) {
							$join->on('AMD.id','=','stock_transferout.account_dr');
						} )
					->join('account_master AS AMC', function($join) {
							$join->on('AMC.id','=','stock_transferout.account_cr');
						} )
					->select('AMD.master_name AS name_dr','AMC.master_name AS name_cr','stock_transferout.*')
					->first();
	}
	
	public function activeStockTransferoutList()
	{
		return $this->stock_transferout->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->stock_transferout->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->stock_transferout->where('reference_no',$refno)->count();
	}
	public function check_voucher_no($refno, $deptid, $id = null) { 
		
		if($id) {
			return $result = $this->stock_transferout->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		} else {
			$query = $this->stock_transferout->where('voucher_no',$refno); 
			return $result = ($deptid)?$query->where('department_id', $deptid)->count():$query->count();
		}
	}
		
	
	public function getItems($id)
	{
		
		$query = $this->stock_transferout->where('stock_transferout.id',$id);
		
		return $query->join('stock_transferout_item AS ITM', function($join) {
							$join->on('ITM.stock_transferout_id','=','stock_transferout.id');
						} )
					  ->join('units AS U', function($join){
						  $join->on('U.id','=','ITM.unit_id');
					  }) 
					  ->join('itemmaster AS IM', function($join){
						  $join->on('IM.id','=','ITM.item_id');
					  })
					  ->where('ITM.status',1)
					  ->select('ITM.*','U.unit_name','IM.item_code')->groupBy('ITM.id')->get();
	}
	
	public function getDoc($attributes)
	{
		$invoice = $this->stock_transferout->where('stock_transferout.id', $attributes['document_id'])
								   ->join('account_master AS CR', function($join) {
									   $join->on('CR.id','=','stock_transferout.account_cr');
								   })
								   ->join('account_master AS DR', function($join) {
									   $join->on('DR.id','=','stock_transferout.account_dr');
								   })
								   ->select('CR.master_name AS cr_account','DR.master_name AS dr_account','stock_transferout.*','CR.address','CR.city','CR.state')
								   ->orderBY('stock_transferout.id', 'ASC')
								   ->first();
								   
		$items = $this->stock_transferout->where('stock_transferout.id', $attributes['document_id'])
								   ->join('stock_transferout_item AS STO', function($join) {
									   $join->on('STO.stock_transferout_id','=','stock_transferout.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','STO.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','STO.unit_id');
								   })
								   ->where('STO.status', 1)
								   ->where('STO.deleted_at', '0000-00-00 00:00:00')
								   ->select('STO.*','IM.item_code','U.unit_name')//'sales_invoice.id',
								   ->get();
								   
		return $result = ['details' => $invoice, 'items' => $items];
	}
	
	private function setSaleLog($attributes, $key, $document_id, $cost_avg, $sale_cost, $action)
	{
		if($action=='add') {
								
			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'TO',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $attributes['quantity'][$key],
							 'unit_cost'  => $attributes['cost'][$key],
							 'trtype'	  => 0,
							 'cost_avg' => $cost_avg,
							 'pur_cost' => $sale_cost,
							 'sale_cost' => $sale_cost,
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => now(),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			
		} else if($action=='update') {
			
			$log = DB::table('item_log')->where('document_type','TO')
							->where('document_id', $document_id)
							->where('item_id', $attributes['item_id'][$key])
							->where('unit_id', $attributes['unit_id'][$key])
							->select('id')->first();
			$logid = $log->id;				
			DB::table('item_log')->where('document_type','TO')
							->where('id', $logid)
							->update([
								 'quantity'   => $attributes['quantity'][$key],
								 'unit_cost'  => (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg,
								 'pur_cost' => $sale_cost,
								 'sale_cost' => $sale_cost,
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			
		}
		
		return $logid;
	}
	
}

