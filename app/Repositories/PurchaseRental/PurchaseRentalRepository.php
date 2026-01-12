<?php
declare(strict_types=1);
namespace App\Repositories\PurchaseRental;

use App\Models\PurchaseRental;
use App\Models\PurchaseRentalItem;
use App\Models\AccountTransaction;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Ixudra\Curl\Facades\Curl;
use App\Repositories\UpdateUtility;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;
use Storage;

class PurchaseRentalRepository extends AbstractValidator implements PurchaseRentalInterface {
	
	public $objUtility;
	
	protected $purchase_invoice;
	
	protected static $rules = [];
	
	public function __construct(PurchaseRental $purchase_invoice) {
		$this->purchase_invoice = $purchase_invoice;
		$this->objUtility = new UpdateUtility();
	}
	
	public function all()
	{
		return $this->purchase_invoice->get();
	}
	
	public function find($id)
	{
		return $this->purchase_invoice->where('id', $id)->first();
	}
	
	public function getPurchaseRentalList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('purchase_rental')
								->join('account_master AS AC','AC.id','=','purchase_rental.supplier_id')
								->where('purchase_rental.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					
					$query->where('AC.master_name','LIKE',"%{$search}%");
					$query->orWhere('purchase_rental.voucher_no','LIKE',"%{$search}%");
					
				});
			}
			
		$query->select('purchase_rental.id','purchase_rental.voucher_no','purchase_rental.voucher_date','purchase_rental.net_amount','AC.master_name');
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->purchase_invoice->voucher_no = $attributes['voucher_no']; 
		$this->purchase_invoice->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->purchase_invoice->reference_no = $attributes['reference_no']; 
		$this->purchase_invoice->supplier_id = $attributes['supplier_id'];
		$this->purchase_invoice->description = $attributes['description'];
		$this->purchase_invoice->account_master_id = $attributes['account_master_id'];
		$this->purchase_invoice->is_vat = $attributes['is_vat'];
		$this->purchase_invoice->vat_type = isset($attributes['vat_type'])?$attributes['vat_type']:'';
		
		return true;
	}
		
	private function setItemInputValue($attributes, $purchaseRentalItem, $key)
	{
		$purchaseRentalItem->purchase_rental_id = $this->purchase_invoice->id;
		$purchaseRentalItem->service_date = date('Y-m-d',strtotime($attributes['service_date'][$key]));
		$purchaseRentalItem->item_id = $attributes['item_id'][$key];
		$purchaseRentalItem->driver_id = $attributes['drvr_id'][$key];
		$purchaseRentalItem->unit_id = $attributes['unit'][$key];
		$purchaseRentalItem->quantity = $attributes['quantity'][$key];
		$purchaseRentalItem->rate = $attributes['rate'][$key];
		$purchaseRentalItem->extra_hr = $attributes['hrextra'][$key];
		$purchaseRentalItem->extra_rate = $attributes['ratextra'][$key];
		$purchaseRentalItem->vat = $attributes['vat'][$key];
		$purchaseRentalItem->vat_amount = $attributes['vatamt'][$key];
		$purchaseRentalItem->line_total = $attributes['line_total'][$key];
		
		return true;
		
	}
	
	private function setAccountTransaction($attributes, $amount, $voucher_id, $type, $amount_type=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		if($amount!=0) {
			if($amount_type=='VAT') {
				$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); 
				
				if($vatrow) {
					$dr_acnt_id = $vatrow->collection_account;
				}
				
			} else if($amount_type == 'LNTOTAL') {
				$dr_acnt_id = $attributes['account_master_id'];
			} else if($amount_type == 'NTAMT') {
				$cr_acnt_id = $attributes['supplier_id'];
			} else if($amount_type == 'DIS') {
				$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
				$cr_acnt_id = $vatrow->account_id;
			}
			
			DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'PIR',
								'voucher_type_id'   => $voucher_id,
								'account_master_id' => ($type=='Cr')?$cr_acnt_id:$dr_acnt_id,
								'transaction_type'  => $type,
								'amount'   			=> $amount,
								'status' 			=> 1,
								'created_at' 		=> now(),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'tr_for'			=> 0,
								'fc_amount'			=> $amount,
								'other_type'		=> '',
								'is_fc'				=> 0,
								'department_id'		=> ''
							]);
			
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);	
			
		}
						
		return true;
	}
	
	
	//Accounting Method function............
	private function AccountingMethod($attributes, $line_total, $tax_total, $net_amount, $purchase_invoice_id)
	{
		$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		//Debit Stock in Hand
		if( $this->setAccountTransaction($attributes, $line_total, $purchase_invoice_id, $type='Dr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransaction($attributes, $tax_total, $purchase_invoice_id, $type='Dr', $amount_type='VAT') ) {
		
				//Credit Supplier Accounting
				if( $this->setAccountTransaction($attributes, $net_amount, $purchase_invoice_id, $type='Cr', $amount_type='NTAMT') ) {
				
					$this->setAccountTransaction($attributes, $discount, $purchase_invoice_id, $type='Cr', $amount_type='DIS');
				}
			}
		}
		
	}
	
	private function AccountingMethodUpdate($attributes, $line_total, $tax_total, $net_amount, $purchase_invoice_id)
	{
		$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
		//Debit Stock in Hand
		if( $this->setAccountTransactionUpdate($attributes, $line_total, $purchase_invoice_id, $type='Dr', $amount_type='LNTOTAL') ) {
		
			//Debit VAT Input
			if( $this->setAccountTransactionUpdate($attributes, $tax_total, $purchase_invoice_id, $type='Dr', $amount_type='VAT') ) {
			
				//Credit Supplier Accounting
				if( $this->setAccountTransactionUpdate($attributes, $net_amount, $purchase_invoice_id, $type='Cr', $amount_type='NTAMT') ) {
					
					$this->setAccountTransactionUpdate($attributes, $discount, $purchase_invoice_id, $type='Cr', $amount_type='DIS');
				}
			}
		}
	}
	
	private function setAccountTransactionUpdate($attributes, $amount, $voucher_id, $type, $amount_type=null)
	{
		$cr_acnt_id = $dr_acnt_id = '';
		$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null);
		if($amount!=0) {
			if($amount_type=='VAT') {
				
				if($vatrow) {
					$dr_acnt_id = $account_id = $vatrow->collection_account;
				}
				$cur_account_id = $account_id;
				
			} else if($amount_type == 'LNTOTAL') {
				$dr_acnt_id = $cur_account_id = $account_id = $attributes['account_master_id']; 
			} else if($amount_type == 'NTAMT') {
				$cr_acnt_id = $cur_account_id = $account_id = $attributes['supplier_id'];
				
				//CHANGING SUPPLIER..
				if($attributes['supplier_id'] != $attributes['old_supplier_id']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'PIR')
							->where('account_master_id', $attributes['old_supplier_id'])
							->update( ['account_master_id' => $attributes['supplier_id'] ]);
							
					$this->objUtility->tallyClosingBalance($attributes['old_supplier_id']);
				}
				
				//CHANGING Dr account.. 
				if($attributes['account_master_id'] != $attributes['old_account_master_id']) {
					DB::table('account_transaction')
							->where('voucher_type_id', $voucher_id)
							->where('voucher_type', 'PIR')
							->where('account_master_id', $attributes['old_account_master_id'])
							->update( ['account_master_id' => $attributes['account_master_id'] ]);
							
					$this->objUtility->tallyClosingBalance($attributes['old_supplier_id']);
				}
				
			} else if($amount_type == 'DIS') {
				
				$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
				$cr_acnt_id = $cur_account_id = $account_id = $vatrow->account_id;
				
				//IF DISCOUNT NOT ADDED PREVIOUS.. FEB21
				if($attributes['discount_old']==0) {
					DB::table('account_transaction')
						->insert([  'voucher_type' 		=> 'PIR',
									'voucher_type_id'   => $voucher_id,
									'account_master_id' => $cr_acnt_id,
									'transaction_type'  => 'Cr',
									'amount'   			=> $amount,
									'status' 			=> 1,
									'created_at' 		=> now(),
									'created_by' 		=> Auth::User()->id,
									'description' 		=> $attributes['description'],
									'reference'			=> $attributes['voucher_no'],
									'invoice_date'		=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
									'reference_from'	=> $attributes['reference_no'],
									'fc_amount'			=> $amount,
									'is_fc'				=> isset($attributes['is_fc'])?1:0,
									]);
									
				} 
			}
			
			$trfor = 0;
			DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $cur_account_id)
					->where('voucher_type', 'PIR')					
					->where('tr_for', $trfor)
					->update([  'account_master_id' => $account_id,
								'amount'   			=> $amount,
								'modify_at' 		=> now(),
								'modify_by' 		=> Auth::User()->id,
								'description' 		=> $attributes['description'],
								'reference'			=> $attributes['voucher_no'],
								'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
								'reference_from'	=> $attributes['reference_no'],
								'fc_amount'			=> $amount,
								'is_fc'				=> 0,
								'department_id'		=> (isset($attributes['department_id']))?$attributes['department_id']:''
								]);
								
			$this->objUtility->tallyClosingBalance(($type=='Cr')?$cr_acnt_id:$dr_acnt_id);
			
		} else {  //Remove vat account transaction..
			
			if( $attributes['vatcur'] != 0 && $attributes['vat'] == 0) {
				//Remove vat account...
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->collection_account)
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'PIR')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
						
				$this->objUtility->tallyClosingBalance($vatrow->collection_account);
						
				//Remove vatoutput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatoutput_import)
					->where('transaction_type' , 'Cr')
					->where('voucher_type', 'PIR')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatoutput_import);
				
				//Remove vatinput acount		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $vatrow->vatinput_import)
					->where('transaction_type' , 'Dr')
					->where('voucher_type', 'PIR')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
								
				$this->objUtility->tallyClosingBalance($vatrow->vatinput_import);
						
			} 
			
			//Remove DISCOUNT.... 
			if($amount_type == 'DIS') {
				$vatrow = DB::table('other_account_setting')->where('account_setting_name', 'Discount in Purchase')->where('status', 1)->first();
				$cr_acnt_id = $cur_account_id = $account_id = $vatrow->account_id;
				
				//Remove DISCOUNT....		
				DB::table('account_transaction')
					->where('voucher_type_id', $voucher_id)
					->where('account_master_id', $cr_acnt_id) //CHNG
					->where('transaction_type' , 'Cr')
					->where('voucher_type', 'PIR')					
					->where('tr_for', 0)
						->update(['status' => 0, 'deleted_at' => now()]);
						
				$this->objUtility->tallyClosingBalance($cr_acnt_id);
			}
			
		}
		
		return true;
	}
	
		
	public function create($attributes)
	{ 	//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			
		 DB::beginTransaction();
		 try {
				
			if($this->setInputValue($attributes)) {
				$this->purchase_invoice->status = 1;
				$this->purchase_invoice->created_at = now();
				$this->purchase_invoice->created_by = Auth::User()->id;
				$this->purchase_invoice->fill($attributes)->save();
			}
			
			//invoice items insert
			if($this->purchase_invoice->id && !empty( array_filter($attributes['item_id']))) { 
				
				foreach($attributes['item_id'] as $key => $value){ 
					$purchaseRentalItem = new PurchaseRentalItem();
					$arrResult 	= $this->setItemInputValue($attributes, $purchaseRentalItem, $key);
					if($arrResult) {
						$purchaseRentalItem->status = 1;
						$inv_item = $this->purchase_invoice->doItem()->save($purchaseRentalItem);
						
						DB::table('rental_itemlog')
									->insert([
										'row_id' => $inv_item->id,
										'doc_type' => 'PIR',
										'doc_id' => $this->purchase_invoice->id,
										'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'service_date' => date('Y-m-d',strtotime($attributes['service_date'][$key])),
										'driver_id' => $attributes['drvr_id'][$key],
										'item_id' => $value,
										'unit_id' => $attributes['unit'][$key],
										'qty'	=> $attributes['quantity'][$key],
										'rate'	=> $attributes['rate'][$key],
										'trtype'	=> 1
									]);
					}
				}
				
				//update discount, total amount, vat and other cost....
				DB::table('purchase_rental')
							->where('id', $this->purchase_invoice->id)
							->update(['total'    	  => $attributes['total'],
									  'discount' 	  => $attributes['discount'],
									  'subtotal'	  => $attributes['subtotal'],
									  'vat_amount'	  => $attributes['vat_total'],
									  'net_amount'	  => $attributes['net_amount']
									  ]); 
									  
				//Cost Accounting or Purchase and Sales Method .....
				$this->AccountingMethod($attributes, $attributes['subtotal'], $attributes['vat_total'], $attributes['net_amount'], $this->purchase_invoice->id);
				
				//update voucher no........
				if( $this->purchase_invoice->id ) {  
					
					 DB::table('account_setting')
						->where('id', $attributes['voucher_id'])
						->update(['voucher_no' => $attributes['voucher_no'] + 1 ]);
					 
				}
				
			}
			
			DB::commit();
			return true;
			
		  } catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			  return false;
		  }
		}
		
	}
	
	public function update($id, $attributes)
	{ 	//echo '<pre>';print_r($attributes);exit;
		$this->purchase_invoice = $this->find($id);
		
		DB::beginTransaction();
		try {
			
			if($this->purchase_invoice->id && !empty( array_filter($attributes['item_id']))) {
				
				foreach($attributes['item_id'] as $key => $value) { 
				
					if($attributes['rowid'][$key]!='') {
						$purchaseRentalItem = PurchaseRentalItem::find($attributes['rowid'][$key]);
						$items['item_id'] = $value;
						$items['service_date'] = date('Y-m-d',strtotime($attributes['service_date'][$key]));
						$items['driver_id'] = $attributes['drvr_id'][$key];
						$items['unit_id'] = $attributes['unit'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = $attributes['rate'][$key];
						$items['extra_hr']		 = $attributes['hrextra'][$key];
						$items['extra_rate']		 = $attributes['ratextra'][$key];
						$items['vat']		 = $attributes['vat'][$key];
						$items['vat_amount'] = $attributes['vatamt'][$key];
						$items['line_total'] = $attributes['line_total'][$key]; //echo '<pre>';print_r($items);exit;
						$purchaseRentalItem->update($items);
						
						DB::table('rental_itemlog')
									->where('row_id', $attributes['rowid'][$key])
									->where('doc_type', 'PIR')
									->where('doc_id', $this->purchase_invoice->id)
									->update([
										'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'service_date' => date('Y-m-d',strtotime($attributes['service_date'][$key])),
										'driver_id' => $attributes['drvr_id'][$key],
										'item_id' => $value,
										'unit_id' => $attributes['unit'][$key],
										'qty'	=> $attributes['quantity'][$key],
										'rate'	=> $attributes['rate'][$key]
									]);
											
					} else { 
						//new entry...
						$purchaseRentalItem = new PurchaseRentalItem();
						$arrResult 	= $this->setItemInputValue($attributes, $purchaseRentalItem, $key);
						if($arrResult) {
							$purchaseRentalItem->status = 1;
							$inv_item = $this->purchase_invoice->doItem()->save($purchaseRentalItem);
							
							DB::table('rental_itemlog')
									->insert([
										'row_id' => $inv_item->id,
										'doc_type' => 'PIR',
										'doc_id' => $this->purchase_invoice->id,
										'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
										'service_date' => date('Y-m-d',strtotime($attributes['service_date'][$key])),
										'driver_id' => $attributes['drvr_id'][$key],
										'item_id' => $value,
										'unit_id' => $attributes['unit'][$key],
										'qty'	=> $attributes['quantity'][$key],
										'rate'	=> $attributes['rate'][$key],
										'trtype'	=> 1
									]);
						}
					}
				}
			}
			
			//UPDATED MAR 1...
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				foreach($arrids as $row) {
					DB::table('purchase_rental_item')->where('id', $row)->update(['deleted_at' => now()]);
					
					DB::table('rental_itemlog')
									->where('row_id', $row)
									->where('doc_type', 'PIR')
									->where('doc_id', $this->purchase_invoice->id)
									->update(['deleted_at' => now()]);
				}
			}
			
			if($this->setInputValue($attributes)) {
				
				$this->purchase_invoice->modify_at = now();
				$this->purchase_invoice->modify_by = Auth::User()->id;
				$this->purchase_invoice->fill($attributes)->save();
				
			}
			
			DB::table('purchase_rental')
							->where('id', $this->purchase_invoice->id)
							->update(['total'    	  => $attributes['total'],
									  'discount' 	  => $attributes['discount'],
									  'subtotal'	  => $attributes['subtotal'],
									  'vat_amount'	  => $attributes['vat_total'],
									  'net_amount'	  => $attributes['net_amount']
									  ]); 
			
			//check whether Cost Accounting method or not.....
			$this->AccountingMethodUpdate($attributes, $attributes['subtotal'], $attributes['vat_total'], $attributes['net_amount'], $this->purchase_invoice->id);
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			 DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->purchase_invoice = $this->purchase_invoice->find($id);
		//inventory update...
		DB::beginTransaction();
		try {
			
			DB::table('purchase_rental_item')->where('purchase_rental_id', $id)->update(['deleted_at' => now()]);
			
			DB::table('rental_itemlog')->where('doc_type', 'PIR')->where('doc_id', $id)->update(['deleted_at' => now()]);
									
			//Transaction update....
			DB::table('account_transaction')->where('voucher_type', 'PIR')->where('voucher_type_id',$id)->update(['status' => 0,'deleted_at' => now(),'deleted_by' => Auth::User()->id ]);
			
			$this->objUtility->tallyClosingBalance( $this->purchase_invoice->supplier_id );
			
			$this->objUtility->tallyClosingBalance( $this->purchase_invoice->account_master_id );
			
			$vatrow = $this->getVatAccounts((isset($attributes['department_id']))?$attributes['department_id']:null); 
			if($vatrow) {
				$this->objUtility->tallyClosingBalance($vatrow->collection_account);
			}
			
			$this->purchase_invoice->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}	
		
	}
	
	public function check_invoice($id)
	{
		$count = DB::table('purchase_invoice')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else {
			$row = DB::table('purchase_return')->where('purchase_invoice_id', $id)->whereNull('deleted_at')->count();
			if($row > 0)
				return false;
			else {
				return true;
				
			}	
		}
	}
	
	
	
	public function check_invoice_id($invoice_id) { 
		
		return $this->purchase_invoice->where('voucher_no', $invoice_id)->count();
	}
	
	
	public function findPRdata($id)
	{
		$query = $this->purchase_invoice->where('purchase_rental.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','purchase_rental.supplier_id');
						} )
					->join('account_master AS am2', function($join){
						  $join->on('am2.id','=','purchase_rental.account_master_id');
					  })
					->select('purchase_rental.*','am.master_name AS supplier','am2.master_name AS account')
					->orderBY('purchase_rental.id', 'ASC')
					->first();
	}
	
	public function getItems($id)
	{
		$query = $this->purchase_invoice->where('purchase_rental.id',$id);
		
		return $query->join('purchase_rental_item AS PRI', function($join) {
							$join->on('PRI.purchase_rental_id','=','purchase_rental.id');
						} )
					  ->join('itemmaster AS IM', function($join){
						  $join->on('IM.id','=','PRI.item_id');
					  })
					  ->join('units AS U', function($join){
						  $join->on('U.id','=','PRI.unit_id');
					  }) 
					  ->leftjoin('rental_driver AS RD', function($join){
						  $join->on('RD.id','=','PRI.driver_id');
					  })
					  ->where('PRI.deleted_at',null)
					  ->select('PRI.*','U.unit_name','IM.description','RD.driver_name')
					  ->orderBY('PRI.id')
					  ->groupBY('PRI.id')
					  ->get();
	}
	
		
	public function check_voucher_no($refno, $deptid, $id = null) { 
		
		if($id) {
			return $result = $this->purchase_invoice->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		} else {
			$query = $this->purchase_invoice->where('voucher_no',$refno); 
			return $result = ($deptid)?$query->where('department_id', $deptid)->count():$query->count();
		}
	}
	
	
	//CLOSING BALANCE UPDATE...
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
			//$amount = ($amount < 0)?(-1*$amount):$amount;
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				$this->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}
	
	public function updateClosingBalance($account_id, $amount)
	{
		 DB::table('account_master')
					->where('id', $account_id)
					->update(['cl_balance' => $amount]);
	}

		
	private function getVatAccounts($department_id=null) {
		
		if(Session::get('department')==1 && $department_id!=null) {
			$vatdept = DB::table('vat_department')->where('department_id', $department_id)->first();
			$vatacs = DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();
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
			return DB::table('vat_master')->where('status', 1)->whereNull('deleted_at')->first();
		}
	}
	
	public function getReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$department = (Session::get('department')==1)?$attributes['department_id']:null;
		
			
				$query = $this->purchase_invoice
								->join('purchase_rental_item AS POI', function($join) {
									$join->on('POI.purchase_rental_id','=','purchase_rental.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','purchase_rental.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->join('units AS U', function($join){
									$join->on('U.id','=','POI.unit_id');
								}) 
								->leftjoin('rental_driver AS RD', function($join){
									$join->on('RD.id','=','POI.driver_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_rental.voucher_date', array($date_from, $date_to));
						}
						
						/* if(isset($attributes['job_id']))
							$query->whereIn('purchase_rental.job_id', $attributes['job_id']); */
						 
				$query->select('purchase_rental.voucher_no','purchase_rental.reference_no','IM.description','purchase_rental.total',
								'purchase_rental.vat_amount','POI.quantity','POI.rate','POI.line_total','AM.account_id','purchase_rental.id',
								'AM.master_name','AM.id','purchase_rental.net_amount','POI.vat_amount AS unit_vat','purchase_rental.discount','purchase_rental.voucher_date','RD.driver_type',
							'RD.driver_name','U.description As unit_description ','POI.extra_hr','POI.extra_rate');
				
				if($attributes['search_type']=="summary"){
					$query->groupBy('purchase_rental.id');
				}
				if($attributes['supplier']!=''){
					$query->where('AM.id', $attributes['supplier']);
				}
			$result = $query->get();
								
		return $result;
		
	}
}

