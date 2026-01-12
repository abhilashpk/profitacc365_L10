<?php
declare(strict_types=1);
namespace App\Repositories\Manufacture;

use App\Models\Manufacture;
use App\Models\ManufactureItem;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use Illuminate\Support\Facades\DB;
use Auth;

class ManufactureRepository extends AbstractValidator implements ManufactureInterface {
	
	protected $manufacture;
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(Manufacture $manufacture) {
		$this->manufacture = $manufacture;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->manufacture->get();
	}
	
	public function find($id)
	{
		return $this->manufacture->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->manufacture->voucher_no = $attributes['voucher_no'];
		$this->manufacture->account_dr = $attributes['account_dr'];
		$this->manufacture->account_cr = $attributes['account_cr'];
		$this->manufacture->description = $attributes['description'];
		//$this->manufacture->department_id = $attributes['department_id'];
		$this->manufacture->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->manufacture->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description']:'';
		
		return true;
	}
	
	private function setItemInputValue($attributes, $ManufactureItem, $key, $value) 
	{
		$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
		
		$ManufactureItem->manufacture_id = $this->manufacture->id;
		$ManufactureItem->item_id = $attributes['item_id'][$key];
		$ManufactureItem->unit_id = $attributes['unit_id'][$key];
		$ManufactureItem->item_name = $attributes['item_name'][$key];
		$ManufactureItem->quantity = $attributes['quantity'][$key];
		$ManufactureItem->price = $attributes['cost'][$key];
		$ManufactureItem->item_total = $item_total;
		
		return array('line_total' => $attributes['quantity'][$key], 'item_total' => $item_total);
		
	}
	
	//Purchase and Sales Method function............
	private function PurchaseAndSalesMethod($attributes, $net_amount, $transferin_id)
	{
		//Debit Customer Account
		if( $this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Dr') ) {
			
			//Credit Sales A/c
			$this->setAccountTransaction($attributes, $net_amount, $transferin_id, $type='Cr');
		}
		
		if( $this->setAccountTransactionReverseAc($attributes, $net_amount, $transferin_id, $type='Dr') ) {
			
			$this->setAccountTransactionReverseAc($attributes, $net_amount, $transferin_id, $type='Cr');
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
					->insert([  'voucher_type' 		=> 'MFG',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> ($mod=='OC')?$attributes['oc_description'][$key]:$attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'tr_for'			=> ($ocid)?$ocid:0,
								'other_type'		=> ($mod)?$mod:''
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		}
							
		return true;
	}
	
	private function setAccountTransactionReverseAc($attributes, $amount, $voucher_id, $type)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		if($type == 'Dr') {
			$cr_acnt_id = $attributes['account_cr'];
		} else if($type == 'Cr') {
			$dr_acnt_id = $attributes['account_dr'];
		}
		
		if( ($cr_acnt_id!='' || $cr_acnt_id!=0) || ($dr_acnt_id!='' || $dr_acnt_id!=0) ) {
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'MFG',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Dr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'tr_for'			=> 0,
								'other_type'		=> ''
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Dr')?$cr_acnt_id:$dr_acnt_id);
			
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
							'modify_at' 		=> now(),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> ($mod=='OC')?$attributes['oc_description'][$key]:$attributes['description'],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							'tr_for'			=> ($ocid)?$ocid:0,
							'other_type'		=> ($mod)?$mod:''
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
				if($this->setInputValue($attributes)) {
					$this->manufacture->status = 1;
					$this->manufacture->created_at = now();
					$this->manufacture->created_by = 1;
					$this->manufacture->fill($attributes)->save();
				}
				
				//order items insert
				if($this->manufacture->id && !empty( array_filter($attributes['item_id']))) {
					$line_total = $item_total = 0;
					
					foreach($attributes['item_id'] as $key => $value) { 
						$ManufactureItem = new ManufactureItem();
						$arrResult 		= $this->setItemInputValue($attributes, $ManufactureItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total']; //amount
							
							$ManufactureItem->status = 1;
							$this->manufacture->manufactureItem()->save($ManufactureItem);
							
							//Manufactured item stock in...........
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key);
							if($this->setPurchaseLog($attributes, $key, $this->manufacture->id, $CostAvg_log,'add'))
								$this->updateItemQuantity($attributes, $key);
						}
					}
					
					$total_amt = $item_total;
					if(count($attributes['dr_acnt_id']) > 0 && count($attributes['oc_amount']) > 0) {
						foreach($attributes['dr_acnt_id'] as $k => $val) {
							if($val!='' && $attributes['oc_amount'][$k]!='' && $attributes['dr_acnt_id'][$k]!='') {
								$ocid = DB::table('mfg_other_cost')
												->insertGetId(['manufacture_id' => $this->manufacture->id,
														  'dr_account_id' => $val,
														  'reference'	=> $attributes['oc_reference'][$k],
														  'description'	=> $attributes['oc_description'][$k],
														  'amount'	=> $attributes['oc_amount'][$k],
														  'cr_account_id'	=> $attributes['cr_acnt_id'][$k]
												]);
										
								$total_amt = $total_amt + $attributes['oc_amount'][$k];
								
								$this->OtherCostTransaction($attributes, $attributes['oc_amount'][$k], $this->manufacture->id, $k, $ocid);
							}
						}
					}
					
					//update discount, total amount
					DB::table('manufacture')
								->where('id', $this->manufacture->id)
								->update(['amount' => $total_amt, 'discount' => $attributes['discount'], 'net_amount' => $total_amt]);
					
					$this->PurchaseAndSalesMethod($attributes, $item_total, $this->manufacture->id);
								
				}
				
				//update voucher no........
				if( ($this->manufacture->id) && ($attributes['curno'] <= $attributes['voucher_no']) ) { 
					 DB::table('account_setting')
						->where('id', $attributes['voucher_id']) 
						->update(['voucher_no' => $attributes['voucher_no'] + 1 ]);
				}
				
				DB::commit();
				return true;
				
		  } catch(\Exception $e) {
				
				DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
				return false;
		  }
		  
		}
		
	}
	
	
	public function update($id, $attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$this->manufacture = $this->find($id);
		$line_total = $item_total = 0;
		
		DB::beginTransaction();
		try {
			
			if($this->manufacture->id && !empty( array_filter($attributes['item_id']))) { 
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['transfer_item_id'][$key]!='') { 
						
						$item_total   += $attributes['cost'][$key] * $attributes['quantity'][$key];
						
						$ManufactureItem = ManufactureItem::find($attributes['transfer_item_id'][$key]);
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['price'] = $attributes['cost'][$key];
						$items['item_total'] = $attributes['quantity'][$key] * $attributes['cost'][$key];
						$ManufactureItem->update($items);
						$line_total	+= $attributes['quantity'][$key];
						
						$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key);
							if($this->setPurchaseLog($attributes, $key, $this->manufacture->id, $CostAvg_log,'update'))
								$this->updateItemQuantityonEdit($attributes, $key);
							
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
									$this->OtherCostTransactionUpdate($attributes, $attributes['oc_amount'][$k], $this->manufacture->id, $k, $attributes['oc_id'][$k]);
									
								} else {
									
									//new entry...
									if($val!='' && $attributes['oc_amount'][$k]!='' && $attributes['dr_acnt_id'][$k]!='') {
										$ocid = DB::table('sti_other_cost')
												->insertGetId(['transfer_id' => $this->manufacture->id,
														  'dr_account_id' => $val,
														  'reference'	=> $attributes['oc_reference'][$k],
														  'description'	=> $attributes['oc_description'][$k],
														  'amount'	=> $attributes['oc_amount'][$k],
														  'cr_account_id'	=> $attributes['cr_acnt_id'][$k]
												]);
												
										$total_amt = $total_amt + $attributes['oc_amount'][$k];
										
										$this->OtherCostTransaction($attributes, $attributes['oc_amount'][$k], $this->manufacture->id, $k, $ocid);
									}
								}
							}
						}
						
						
					} else { //new entry...
						$line_total_new = $item_total_new = 0;
						
						$ManufactureItem = new ManufactureItem();
						$arrResult 		= $this->setItemInputValue($attributes, $ManufactureItem, $key, $value);
						if($arrResult['line_total']) {
							$line_total_new	+= $arrResult['line_total'];
							$item_total_new	+= $arrResult['item_total'];
							
							$line_total	+= $arrResult['line_total'];
							$item_total += $arrResult['item_total'];
							
							$ManufactureItem->status = 1;
							$this->manufacture->transferItem()->save($ManufactureItem);
							
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key);
							if($this->setPurchaseLog($attributes, $key, $this->manufacture->id, $CostAvg_log,'add'))
								$this->updateItemQuantity($attributes, $key);
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
					
					$res = DB::table('manufacture_item')->where('id', $row)->first();
					DB::table('manufacture_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($res, $this->manufacture->id, 'TI');
				}
			}
			
			if($this->setInputValue($attributes)) {
				$this->manufacture->modify_at = now();
				$this->manufacture->modify_by = Auth::User()->id;
				$this->manufacture->fill($attributes)->save();
			}
			
			//$total = $line_total + $line_total_new;
			
			//update discount, total amount
			DB::table('manufacture')
						->where('id', $id)
						//->update(['total_qty' => $line_total,'total_amt' => $item_total, 'net_total' => $item_total]); //CHG
						->update(['total_qty' => $line_total, 'total_amt' => $total_amt, 'net_total' => $total_amt]);
						
			$this->PurchaseAndSalesMethodUpdate($attributes, $item_total, $this->manufacture->id); 
			
			DB::commit();
			return true;
			
		 } catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->manufacture = $this->manufacture->find($id);
		
		$items = DB::table('manufacture_item')->where('manufacture_id',$id)->get();
		
		//Transaction update....
		DB::table('account_transaction')->where('voucher_type', 'STI')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);
		
		//reset account balance....
		$this->objUtility->tallyClosingBalance($this->manufacture->account_dr);
		
		$this->objUtility->tallyClosingBalance($this->manufacture->account_cr);
		
		//inventory update...
		foreach($items as $item) {
			$this->objUtility->updateLastPurchaseCostAndCostAvgonDeleteGsec($item, $id, 'TI');
		}
		
		DB::table('manufacture_item')->where('manufacture_id',$id)->update(['status' => 0, 'deleted_at' => now()]);					  
		$this->manufacture->delete($id);
	}
	
	public function getTransactionList($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->manufacture
								   ->join('manufacture_item AS MI', function($join) {
									   $join->on('MI.manufacture_id','=','manufacture.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','MI.item_id');
								   })
								   ->where('MI.status',1)
								   ->where('MI.deleted_at','0000-00-00 00:00:00')
								   ->where('manufacture.deleted_at','0000-00-00 00:00:00');
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('manufacture.voucher_date',[$date_from, $date_to]);

					       

		$result = $qry->select('manufacture.id','manufacture.voucher_no','manufacture.voucher_date',
								'IM.item_code','IM.description','MI.quantity','MI.price AS unit_price',DB::raw('"" AS vat_amount'),'MI.item_total AS total_price')
								   ->orderBY('manufacture.voucher_date', 'ASC')
								   ->get();
								   
		return $result;
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
	
	
	public function check_order($id)
	{
		$count = DB::table('manufacture')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else
			return true;
	}
	
	public function stockTransList()
	{
		return $this->manufacture->where('status',1)
					->orderBY('manufacture.id', 'DESC')->get();
		
	}
	
	
	public function findRow($id)
	{
		return $this->manufacture->where('manufacture.id', $id)
					->join('account_master AS AMD', function($join) {
							$join->on('AMD.id','=','manufacture.account_dr');
						} )
					->join('account_master AS AMC', function($join) {
							$join->on('AMC.id','=','manufacture.account_cr');
						} )
					->select('AMD.master_name AS name_dr','AMC.master_name AS name_cr','manufacture.*')
					->first();
	}
	
	public function activeManufactureList()
	{
		return $this->manufacture->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->manufacture->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->manufacture->where('reference_no',$refno)->count();
	}
		
	
	public function getItems($id)
	{
		
		$query = $this->manufacture->where('manufacture.id',$id);
		
		return $query->join('manufacture_item AS ITM', function($join) {
							$join->on('ITM.manufacture_id','=','manufacture.id');
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
					  ->select('ITM.*','U.unit_name','IM.item_code','IU.is_baseqty')->get();
	}
	
	
	public function getDoc($attributes)
	{
		$invoice = $this->manufacture->where('manufacture.id', $attributes['document_id'])
								   ->join('account_master AS CR', function($join) {
									   $join->on('CR.id','=','manufacture.account_cr');
								   })
								   ->join('account_master AS DR', function($join) {
									   $join->on('DR.id','=','manufacture.account_dr');
								   })
								   ->select('CR.master_name AS cr_account','DR.master_name AS dr_account','manufacture.*','CR.address','CR.city','CR.state')
								   ->orderBY('manufacture.id', 'ASC')
								   ->first();
								   
		$items = $this->manufacture->where('manufacture.id', $attributes['document_id'])
								   ->join('manufacture_item AS STI', function($join) {
									   $join->on('STI.manufacture_id','=','manufacture.id');
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
	
	private function updateLastPurchaseCostAndCostAvg($attributes, $key)
	{
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->whereNull('deleted_at')
										->select('cur_quantity','pur_cost')
										->get();
										
		//$itmcost = $itmqty = 0;
		$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round(($itmcost / $itmqty), 3);
		$cost = $attributes['cost'][$key];
		
					
		DB::table('item_unit')
				->where('id', $attributes['item_id'][$key])
				->update(['last_purchase_cost' => $cost,
						  'pur_count' 		   => DB::raw('pur_count + 1'),
						  'cost_avg'		   => $cost_avg
						]);
							
		return $cost_avg;
		
	}
	
	private function setPurchaseLog($attributes, $key, $document_id, $cost_avg, $action)
	{
		if($action=='add') {
			DB::table('item_log')->insert([
							 'document_type' => 'TI',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $attributes['quantity'][$key],
							 'unit_cost'  => $attributes['cost'][$key],
							 'trtype'	  => 1,
							 'cur_quantity' => $attributes['quantity'][$key],
							 'cost_avg' => $cost_avg,
							 'pur_cost' => $attributes['cost'][$key],
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => now(),
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
								 'unit_cost'  => $attributes['cost'][$key],
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg,
								 'pur_cost' => $attributes['cost'][$key],
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
		}
							
		return true;
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
	
	private function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key)
	{	
		$pid = $attributes['transfer_id'];
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->whereNull('deleted_at')
										->where(function ($query) use($pid) {
											$query->where('document_id','!=',$pid)
												  ->orWhere('document_type','!=','TI');
										})
										->select('cur_quantity','pur_cost')
										->get();
		//echo '<pre>';print_r($itmlogs);exit;								
		//$itmcost = $itmqty = 0;
		$itmcost = $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( ($itmcost / $itmqty), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		
					
		DB::table('item_unit')
				->where('id', $attributes['item_id'][$key])
				->update(['last_purchase_cost' => $cost,
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

