<?php namespace App\Repositories\SupplierDo;

use App\Models\SupplierDo;
use App\Models\SupplierDoItem;
use App\Models\SupplierDoInfo;
use App\Models\ItemStock;
use App\Models\PurchaseInvoiceItem;
use App\Models\ItemLocation;
use App\Models\ItemLocationPI;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Models\SupplierDoOtherCost; 
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Session;
use Auth;
use Storage;


class SupplierDoRepository extends AbstractValidator implements SupplierDoInterface {
	
	protected $supplier_do;
	protected $mod_sdo_qty;
	public $objUtility;
	
	protected static $rules = [];
	
	public function __construct(SupplierDo $supplier_do) {
		$this->supplier_do = $supplier_do;
		$this->objUtility = new UpdateUtility();
		$this->mod_sdo_qty = DB::table('parameter2')->where('keyname', 'mod_sdo_qty_update')->where('status',1)->select('is_active')->first();
	}
	
	public function all()
	{
		return $this->supplier_do->get();
	}
	
	public function find($id)
	{
		return $this->supplier_do->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		
		$this->supplier_do->voucher_no = $attributes['voucher_no']; 
		$this->supplier_do->reference_no = $attributes['reference_no'];
		$this->supplier_do->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->supplier_do->lpo_date = ($attributes['lpo_date']!='')?date('Y-m-d', strtotime($attributes['lpo_date'])):'';
		$this->supplier_do->document_type = ($attributes['document_type']=='')?'PI':$attributes['document_type'];//Purchase Invoice type
		$this->supplier_do->supplier_id = $attributes['supplier_id'];
		$this->supplier_do->document_id = $attributes['document_id'] ?? 0;
		$this->supplier_do->job_id = $attributes['job_id'] ?? 0;
		$this->supplier_do->description = $attributes['description'] ?? null;
		$this->supplier_do->is_fc = isset($attributes['is_fc'])?1:0;
		$this->supplier_do->currency_id = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->supplier_do->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:'';
		$this->supplier_do->location_id = $attributes['location_id'] ?? 0;
		$this->supplier_do->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description'] ?? null:'';
		
		$this->supplier_do->is_editable  = (isset($attributes['document_id']) && $attributes['document_id']!='')?2:0; //APR25
		
		return true;
	}
	
	private function setItemInputValue($attributes, $supplierDOItem, $key, $value, $other_cost, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
		if( isset($attributes['is_fc']) ) {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
			
			if($tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate'];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = ($ln_total - $tax_total) * $attributes['currency_rate'];
				
				if( isset($attributes['other_cost'])) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = (($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key]) * $attributes['currency_rate'];
				$tax_total  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
				
				if( isset($attributes['other_cost']) && $other_cost > 0 ) { //MY27
					$othercost_unit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
					$netcost_unit = $othercost_unit + ($attributes['cost'][$key] * $attributes['currency_rate']);
				}
			}
			
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
			
			if($tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
				if( isset($attributes['other_cost'])) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
				if( isset($attributes['other_cost']) && $other_cost > 0 ) {
					$othercost_unit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
					$netcost_unit = $othercost_unit + $attributes['cost'][$key];
				}
			}
			
		}
		
		//********DISCOUNT Calculation............. M14
		/* ######COMMENTED BASED ON FORCE ONE ########
		if( isset($attributes['is_fc']) )
			$discount = (isset($attributes['discount']))?($attributes['discount'] * $attributes['currency_rate']):0;
		else   */
			$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		
		$type = 'tax_exclude';
			
		if($attributes['tax_include'][$key]==1 ) {
			$vatPlus = 100 + $attributes['line_vat'][$key];
			$total = $attributes['cost'][$key] * $attributes['quantity'][$key];
			$type = 'tax_include';
		} else {
			$vatPlus = 100;
			$total = $attributes['line_total'][$key];
			$type = 'tax_exclude';
		}
		
		if($discount > 0) {
			$discountAmt = round( (($total / $lineTotal) * $discount),2 );
			$amountTotal = $total - $discountAmt;
			$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
			//$line_total = $amountTotal;
			$tax_total = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine; //M14
		} 
				
		$supplierDOItem->supplier_do_id = $this->supplier_do->id;
		$supplierDOItem->item_id = $attributes['item_id'][$key];
		$supplierDOItem->unit_id = $attributes['unit_id'][$key];
		$supplierDOItem->item_name = $attributes['item_name'][$key];
		$supplierDOItem->quantity = $attributes['quantity'][$key];
		$supplierDOItem->unit_price = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$supplierDOItem->vat = $attributes['line_vat'][$key];
		$supplierDOItem->vat_amount = $tax_total;
		$supplierDOItem->discount = $attributes['line_discount'][$key] ?? 0;
		$supplierDOItem->total_price = $line_total;
		$supplierDOItem->othercost_unit = $othercost_unit;
		$supplierDOItem->netcost_unit = $netcost_unit;
		$supplierDOItem->tax_code 	= $tax_code;
		$supplierDOItem->tax_include = $attributes['tax_include'][$key] ?? 0;
		$supplierDOItem->item_total = $item_total;
		
		$supplierDOItem->unit_price_fc = $attributes['cost'][$key];
		$supplierDOItem->vat_amount_fc = (isset($attributes['is_fc']))?round(($tax_total /  $attributes['currency_rate']),2):$tax_total;
		$supplierDOItem->total_price_fc = (isset($attributes['is_fc']))?round(($line_total /  $attributes['currency_rate']),2):$line_total;
		$supplierDOItem->item_total_fc = (isset($attributes['is_fc']))?round(($item_total /  $attributes['currency_rate']),2):$item_total;
		
		$supplierDOItem->doc_row_id = isset($attributes['purchase_order_item_id'][$key])?$attributes['purchase_order_item_id'][$key]:0; //APR25
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total,'othercost_unit' => $othercost_unit, 'type' => $type, 'item_total' => $item_total);
		
	}
	
	private function setTransferStatusItem($attributes, $key, $doctype,$mode=null)
	{
		//if quantity partially deliverd, update pending quantity.
		if($doctype=='MR') {
			if(isset($attributes['purchase_order_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['purchase_order_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('material_requisition_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else { 
						//update as completely delivered.
						DB::table('material_requisition_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else if($doctype=='PO') {
		    if($mode=='edit') {
		        
		        if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if($attributes['doc_row_id'][$key] > 0 && ($attributes['actual_quantity'][$key] != $attributes['quantity'][$key]) ) {
						$quantity 	 = ($attributes['actual_quantity'][$key] > $attributes['quantity'][$key])?($attributes['actual_quantity'][$key] - $attributes['quantity'][$key]):($attributes['quantity'][$key] - $attributes['actual_quantity'][$key]);
						
						$DOqtyarr = DB::table('supplier_do_item')->where('doc_row_id', $attributes['doc_row_id'][$key])->where('id','!=', $attributes['order_item_id'][$key])
						                            ->select('id', DB::raw('SUM(quantity) AS quantity'))->groupBY('item_id')->get();
						$DOqty = ((isset($DOqtyarr[0]))?$DOqtyarr[0]->quantity:0) + $attributes['quantity'][$key];          
						//update as partially delivered.
						
						$DOrow = DB::table('purchase_order_item')->where('id', $attributes['doc_row_id'][$key])->select('quantity')->first();
						if($DOrow->quantity==$DOqty) {
						    DB::table('purchase_order_item')
									->where('id', $attributes['doc_row_id'][$key])
									->update(['balance_quantity' => DB::raw('quantity - '.$DOqty), 'is_transfer' => 1]); 
						} else {
						    DB::table('purchase_order_item')
									->where('id', $attributes['doc_row_id'][$key])
									->update(['balance_quantity' => DB::raw('quantity - '.$DOqty), 'is_transfer' => 2]);
						}
									
    				} elseif($attributes['doc_row_id'][$key] > 0 && ($attributes['quantity_old'][$key] == $attributes['quantity'][$key])) {
    						//update as completely delivered.
    						DB::table('purchase_order_item')
    									->where('id', $attributes['doc_row_id'][$key])
    									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
    				}
		        }

    			
		    } else {
		        
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['purchase_order_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['purchase_order_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		}
	}
	
	private function updateTransferStatus($attributes)
	{
		if($attributes['document_type']=="PO") {
			$ids = explode(',', $attributes['document_id']);
			foreach($ids as $id) {
				DB::table('purchase_order')->where('id', $id)->update(['is_editable' => 1]);
				$count1 = DB::table('purchase_order_item')->where('purchase_order_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
				$count2 = DB::table('purchase_order_item')->where('purchase_order_id',$id)->where('is_transfer',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
				if($count1 == $count2)
					DB::table('purchase_order')->where('id', $id)->update(['is_transfer' => 1]);
			} 
		} else if($attributes['document_type']=="MR") {
			$ids = explode(',', $attributes['document_id']);
			foreach($ids as $id) {
				//DB::table('material_requisition')->where('id', $id)->update(['is_editable' => 1]);
				$count1 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
				$count2 = DB::table('material_requisition_item')->where('material_requisition_id',$id)->where('is_transfer',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
				if($count1 == $count2)
					DB::table('material_requisition')->where('id', $id)->update(['is_transfer' => 1]);
			} 
		} 
		
	}
	public function getReport($attributes)
	{

		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
			$pending=isset($attributes['pending'])?$attributes['pending']:0;
		//switch($attributes['search_type']) {
			
		//	case 'summary':
			if($attributes['search_type']=='summary' && $pending==0){
			$query = $this->supplier_do
							->join('supplier_do_item AS POI', function($join) {
								$join->on('POI.supplier_do_id','=','supplier_do.id');
							})
							->join('itemmaster AS IM', function($join) {
								$join->on('IM.id','=','POI.item_id');
							})
							->join('account_master AS AM', function($join) {
								$join->on('AM.id','=','supplier_do.supplier_id');
							})
							->leftJoin('jobmaster AS J', function($join) {
								$join->on('J.id','=','supplier_do.job_id');
							})
							->where('POI.status',1)
							->where('POI.deleted_at','0000-00-00 00:00-00')
							->where('supplier_do.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('supplier_do.voucher_date', array($date_from, $date_to));
						}
						
						if( $attributes['search_type']=='daily' ) { 
							$query->whereBetween( 'supplier_do.voucher_date', array(date('Y-m-d'), date('Y-m-d')) );
						}
					

				if(isset($attributes['supplier_id']) && $attributes['supplier_id']!='')
						$query->where('supplier_do.supplier_id', $attributes['supplier_id']);		
					
		
					
			 $query->select('supplier_do.voucher_no','supplier_do.reference_no','supplier_do.total','supplier_do.vat_amount','supplier_do.subtotal',
								  'supplier_do.discount','supplier_do.net_total',
								  'POI.total_price','POI.item_total','IM.description','supplier_do.voucher_date','POI.item_id',
								  'POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no',
								  'POI.item_name','supplier_do.supplier_id','POI.tax_code','supplier_do.document_id',
								  'POI.tax_include','IM.item_code','AM.master_name AS supplier','J.code as jobcode');
								  
					   if(isset($attributes['type']))
							return $query->groupBy('supplier_do.id')->get()->toArray();
						else
							return $query->groupBy('supplier_do.id')->get();
			//break;
			}
			
			//case 'summary_pending':
			else if($attributes['search_type']=='summary' && $pending==1){
				$query = $this->supplier_do->where('POI.is_transfer','!=',1)
								->join('supplier_do_item AS POI', function($join) {
									$join->on('POI.supplier_do_id','=','supplier_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','supplier_do.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','supplier_do.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','supplier_do.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('supplier_do.voucher_date', array($date_from, $date_to));
						}
						
						if(isset($attributes['job_id']))
							$query->whereIn('supplier_do.job_id', $attributes['job_id']);

							if(isset($attributes['supplier_id']) && $attributes['supplier_id']!=''){
							$query->where('supplier_do.supplier_id', $attributes['supplier_id']);		
							}

				$query->select('supplier_do.voucher_no','supplier_do.reference_no','IM.item_code','IM.description','supplier_do.total','supplier_do.vat_amount','POI.vat_amount AS unit_vat',
								'supplier_do.description','POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','supplier_do.net_total AS net_amount',
								'C.code','supplier_do.currency_rate','J.code as jobcode');
								
				
				return $query->get();
				
			//	break;
			}	
			//	case 'detail':
			else if($attributes['search_type']=='detail' && $pending==0){
				$query = $this->supplier_do
								->join('supplier_do_item AS POI', function($join) {
									$join->on('POI.supplier_do_id','=','supplier_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','supplier_do.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','supplier_do.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','supplier_do.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('supplier_do.voucher_date', array($date_from, $date_to));
						}
						if(isset($attributes['supplier_id']) && $attributes['supplier_id']!=''){
							$query->where('supplier_do.supplier_id', $attributes['supplier_id']);		
							}

						if(isset($attributes['job_id']))
							$query->whereIn('supplier_do.job_id', $attributes['job_id']);
						 
				$query->select('supplier_do.voucher_no','supplier_do.voucher_date','supplier_do.reference_no','IM.item_code','IM.description','supplier_do.total','supplier_do.vat_amount',
								'POI.quantity','POI.balance_quantity','POI.unit_price','POI.total_price','AM.account_id','AM.master_name','supplier_do.net_total AS net_amount',
								'POI.vat_amount AS unit_vat','POI.discount','C.code','supplier_do.currency_rate','J.code as jobcode','supplier_do.voucher_date');
				
				if(isset($attributes['type']))
					return $query->get()->toArray();
				else
					return $query->get();
				
				//break;
			}
			//case 'detail_pending'||'qty_report':
				else if($attributes['search_type']=='detail' && $pending==1){
				$query = $this->supplier_do->where('POI.is_transfer','!=',1)
								->join('supplier_do_item AS POI', function($join) {
									$join->on('POI.supplier_do_id','=','supplier_do.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','supplier_do.supplier_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','POI.item_id');
								})
								->leftJoin('currency AS C', function($join) {
									$join->on('C.id','=','supplier_do.currency_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','supplier_do.job_id');
								})
								->where('POI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('supplier_do.voucher_date', array($date_from, $date_to));
						}
						if(isset($attributes['supplier_id']) && $attributes['supplier_id']!=''){
							$query->where('supplier_do.supplier_id', $attributes['supplier_id']);		
							}

						if(isset($attributes['job_id']))
							$query->whereIn('supplier_do.job_id', $attributes['job_id']);
						
				$query->select('supplier_do.voucher_no','supplier_do.voucher_date','supplier_do.reference_no','IM.item_code','IM.description','supplier_do.total','supplier_do.vat_amount',
								'POI.quantity','POI.balance_quantity','POI.unit_price','POI.total_price','AM.account_id','AM.master_name','supplier_do.net_total AS net_amount',
								'C.code','supplier_do.currency_rate','J.code as jobcode');
				
				if(isset($attributes['type']))
					return $query->get()->toArray();
				else
					return $query->get();
								
			//	break;
			   
		}
	}
		   
public function getReportExcel($attributes)
	{
			//echo '<pre>';print_r($attributes);exit;
			$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
			$department = (Session::get('department')==1)?$attributes['department_id']:null;
		
		
			$query = DB::table('supplier_do')//$this->supplierdo deleted_at
							->join('supplier_do_item AS POI', function($join) {
								$join->on('POI.supplier_do_id','=','supplier_do.id');
							})
							->join('itemmaster AS IM', function($join) {
								$join->on('IM.id','=','POI.item_id');
							})
							->join('account_master AS AM', function($join) {
								$join->on('AM.id','=','supplier_do.supplier_id');
							})->where('POI.status',1)
							->where('POI.deleted_at','0000-00-00 00:00-00')
							->where('supplier_do.status',1)
							->where('supplier_do.deleted_at','0000-00-00 00:00-00');
							
						
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('supplier_do.voucher_date', array($date_from, $date_to));
						}
						
					
				if($department)
						$query->where('supplier_do.department_id', $department);
					
	
				
					
		
					
			 $query->select('supplier_do.voucher_no','supplier_do.reference_no','supplier_do.total','supplier_do.vat_amount','supplier_do.subtotal',
								  'supplier_do.discount','supplier_do.net_total',
								  'POI.total_price','POI.item_total','POI.vat_amount','IM.description','supplier_do.voucher_date','POI.item_id',
								  'POI.quantity','POI.balance_quantity','POI.unit_price','AM.account_id','AM.master_name','AM.vat_no',
								  'POI.item_name','supplier_do.supplier_id','POI.tax_code','supplier_do.document_id',
								  'POI.tax_include','IM.item_code','AM.master_name AS supplier');
				// if(isset($attributes['type']))
				  //  return $query->groupBy('supplier_do.id')->get()->toArray();
				 //   else
					   return $query->groupBy('supplier_do.id')->get();
	}
	
	public function getTransactionList($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = $this->supplier_do
								   ->join('supplier_do_item AS SI', function($join) {
									   $join->on('SI.supplier_do_id','=','supplier_do.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','SI.item_id');
								   })
								   ->where('SI.status',1)
								   ->where('SI.deleted_at','0000-00-00 00:00:00')
								   ->where('supplier_do.status',1);
							
							if($date_from !='' && $date_to != '')	   
								$qry->whereBetween('supplier_do.voucher_date',[$date_from, $date_to]);

					       

		$result = $qry->select('supplier_do.id','supplier_do.voucher_no','supplier_do.voucher_date',
								'IM.item_code','IM.description','SI.quantity','SI.unit_price','SI.vat_amount','SI.total_price')
								   ->orderBY('supplier_do.voucher_date', 'ASC')
								   ->get();
								   
		return $result;
	}
	
	private function setInfoInputValue($attributes, $supplierDoInfo, $key, $value)
	{
		$supplierDoInfo->supplier_do_id = $this->supplier_do->id;
		$supplierDoInfo->title = $value;
		$supplierDoInfo->description = $attributes['desc'][$key];
		
		return true;
	}
	
	private function setTransferStatusDocument($attributes)
	{
		//update purchase order/quotation transfer status....
		$idarr = explode(',',$attributes['document_id']);
		if($idarr) {
			foreach($idarr as $id) {
				if($attributes['document_type']==1) {
					$row1 = DB::table('quotation_item')->where('quotation_id', $id)->count();
					$row2 = DB::table('quotation_item')->where('quotation_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('quotation')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				} else if($attributes['document_type']==2) {
					$row1 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->count();
					$row2 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('purchase_order')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				}
			}
		}
	}
	
	private function getOtherCostSum($ocamount, $vatoc)
	{ 
		//print_r($ocamount);exit;
		$amount = 0;
		foreach($ocamount as $k => $val) {
			$perc = (array_key_exists($k, $vatoc))?$vatoc[$k]:0;
			$vat = $val * $perc / 100;
			$amount += $val + $vat;
		}
		return $amount;
		
	}
	
	private function getCostSum($ocamount)
	{
		return array_sum(array_map( function($amount) {
						return $amount;
					}, $ocamount) );
	}
	
	private function getTotalQuantity($attributes)
	{
		return array_sum(array_map( function($var) {
					return $var;
					}, $attributes) );
	}
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		foreach($attributes['item_id'] as $key => $value){ 
			
			$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
		}
		return $total;
	}
	
	private function setOtherCostInputValue($attributes, $SupplierDoOC, $key)
	{
		$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
		$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
		
		$SupplierDoOC->supplier_do_id = $this->supplier_do->id;
		$SupplierDoOC->dr_account_id = $attributes['dr_acnt_id'][$key];
		$SupplierDoOC->oc_reference = $attributes['oc_reference'][$key];
		$SupplierDoOC->oc_description = $attributes['oc_description'][$key];
		$SupplierDoOC->cr_account_id = $attributes['cr_acnt_id'][$key];
		$SupplierDoOC->oc_amount = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]):$attributes['oc_amount'][$key];
		$SupplierDoOC->oc_fc_amount = $attributes['oc_amount'][$key];
		$SupplierDoOC->oc_vat = $attributes['vat_oc'][$key];
		
		if($attributes['tax_sr'][$key]=="EX" || $attributes['tax_sr'][$key]=="ZR") {
			$oc_vatamt = $ocvatamount = 0;
		} else {
			$ocvatamount = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
			$oc_vatamt = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]*$attributes['vat_oc'][$key]/100):($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
		}
		
		$SupplierDoOC->oc_vatamt = $oc_vatamt;
		$SupplierDoOC->is_fc = $is_fc;
		$SupplierDoOC->currency_id = $attributes['oc_currency'][$key];
		$SupplierDoOC->currency_rate = $attributes['oc_rate'][$key];
		$SupplierDoOC->tax_code = $attributes['tax_sr'][$key];
		
		return array('oc_vat_amt' => $ocvatamount, 'oc_amount' => $attributes['oc_amount'][$key]);
	}
	
	public function create($attributes)
	{
		//NOV24
		$locqty =isset($attributes['locqty'])? array_values($attributes['locqty']):''; 
	    $locid = isset($attributes['locid'])?array_values($attributes['locid']):'';
		
		if($this->isValid($attributes)) { 

		 DB::beginTransaction();
		 try {

			//VOUCHER NO LOGIC.....................
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

				 // 2️⃣ Get the highest numeric part from voucher_master
				$qry = DB::table('supplier_do')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
				if($dept > 0)	
					$qry->where('department_id', $dept);

				$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('SDO', $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
				try {
						if($this->setInputValue($attributes)) {
							$this->supplier_do->status = 1;
							$this->supplier_do->created_at = date('Y-m-d H:i:s');
							$this->supplier_do->created_by = 1;
							$this->supplier_do->save();
							$saved = true;
						}
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false || strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

							// 2️⃣ Get the highest numeric part from voucher_master
							$qry = DB::table('supplier_do')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
							if($dept > 0)	
								$qry->where('department_id', $dept);

							$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
							
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('SDO', $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; // rethrow if different DB error
						}
					}
				}
			 
						
			//order items insert
			if($this->supplier_do->id && !empty( array_filter($attributes['item_id']))) {
				
				$line_total = 0; $tax_total = 0; $other_cost = 0; $total_quantity = 0; $total = 0; $cost_sum = $item_total = 0; $taxtype='';
				if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					$other_cost = $this->getOtherCostSum($attributes['oc_fc_amount'],$attributes['vat_oc']);//MY27
					$total_quantity = $this->getTotalQuantity($attributes['quantity']);
					$cost_sum = $this->getCostSum($attributes['oc_fc_amount']);//MY27
				}
				
				//calculate total amount....
				if( isset($attributes['is_fc']) ) 
					$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
		
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
				
				foreach($attributes['item_id'] as $key => $value) { 
					$supplierDOItem = new SupplierDoItem();
					$vat = $attributes['line_vat'][$key];
					$arrResult 	= $this->setItemInputValue($attributes, $supplierDOItem, $key, $value, $cost_sum, $total, $total_quantity);
					
					if($arrResult['line_total']) {
						$line_total			   += $arrResult['line_total'];
						$tax_total      	   += $arrResult['tax_total'];
						$othercost_unit      	= $arrResult['othercost_unit'];
						$taxtype				= $arrResult['type'];
						$item_total			   += $arrResult['item_total'];
						
						$supplierDOItem->status = 1;
						$inv_item = $this->supplier_do->doItem()->save($supplierDOItem);
						
						//update item transfer status...
						$this->setTransferStatusItem($attributes, $key, $attributes['document_type']);
						
						//CHECK WHEATHER Update Quantity by SDO
						if($this->mod_sdo_qty->is_active==1) {
							
							//update last purchase cost and cost average....
							$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
							$attributes['item_row_id'][$key] = $inv_item->id; //OCT24
							$logid = $this->setPurchaseLog($attributes, $key, $this->supplier_do->id, $CostAvg_log,'add',$othercost_unit);
							if($logid!='')
								$this->updateItemQuantity($attributes, $key);
						}
					}
					
					//################ Location Stock Entry ####################
					//Item Location specific add....
					$updated = false;
					if(Session::get('item_location_warn')==1 && isset($locqty[$key])) {
						foreach($locqty[$key] as $lk => $lq) {
							if($lq!='') {
								$updated = true;
								//$lcqty =  $lq * $attributes['packing'][$key];
								
								$lcqty = $lq;
                        		if($attributes['packing'][$key]=="1") 
                        		    $lcqty = $lq;
                        		else {
                        		   $pkgar = explode('-', $attributes['packing'][$key]);
                        		   if($pkgar[0] > 0)
                        		        $lcqty = ($lq *  $pkgar[1]) / $pkgar[0];
                        		}
								
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $locid[$key][$lk])
															  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
													          ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
								} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $locid[$key][$lk];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $lcqty;
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
								$itemLocationPI = new ItemLocationPI();
								$itemLocationPI->location_id = $locid[$key][$lk];
								$itemLocationPI->item_id = $value;
								$itemLocationPI->unit_id = $attributes['unit_id'][$key];
								$itemLocationPI->quantity = $lcqty; //MAY25 $lq; 
								$itemLocationPI->status = 1;
								$itemLocationPI->invoice_id = $inv_item->id;
								$itemLocationPI->is_sdo = 1;
								$itemLocationPI->logid = $logid;
								$itemLocationPI->qty_entry = $lq;
								$itemLocationPI->save();
							}
						}
					}
					
					//Item default location add...
					if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
							
						$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
														  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
														  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
														  
						$lcqty = $attributes['quantity'][$key];
                		if($attributes['packing'][$key]=="1") 
                		    $lcqty = $attributes['quantity'][$key];
                		else {
                		   $pkgar = explode('-', $attributes['packing'][$key]);
                		   if($pkgar[0] > 0)
                		        $lcqty = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
                		}
						
						if($qtys) {
							DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
						} else {
								$itemLocation = new ItemLocation();
								$itemLocation->location_id = $attributes['default_location'];
								$itemLocation->item_id = $value;
								$itemLocation->unit_id = $attributes['unit_id'][$key];
								$itemLocation->quantity = $lcqty;
								$itemLocation->status = 1;
								$itemLocation->save();
							}
							
						$itemLocationPI = new ItemLocationPI();
						$itemLocationPI->location_id = $attributes['default_location'];
						$itemLocationPI->item_id = $value;
						$itemLocationPI->unit_id = $attributes['unit_id'][$key];
						$itemLocationPI->quantity = $lcqty; //MAY25  $attributes['quantity'][$key];
						$itemLocationPI->status = 1;
						$itemLocationPI->invoice_id = $inv_item->id;
						$itemLocationPI->is_sdo = 1;
						$itemLocationPI->logid = $logid;
						$itemLocationPI->qty_entry = $attributes['quantity'][$key];
						$itemLocationPI->save();
						
					}
					//################ Location Stock Entry End ####################
					
					
					//BATCH NO ENTRY............
    				if(isset($attributes['batchNos'][$key]) && $attributes['batchNos'][$key]!='' && $attributes['mfgDates'][$key]!='' && $attributes['expDates'][$key]!='' && $attributes['qtyBatchs'][$key]!='') {
    				    
    				    $batchArr = explode(',', $attributes['batchNos'][$key]);
    				    $mfgArr = explode(',', $attributes['mfgDates'][$key]);
    				    $expArr = explode(',', $attributes['expDates'][$key]);
    				    $qtyArr = explode(',', $attributes['qtyBatchs'][$key]);
    				    
    				    foreach($batchArr as $bkey => $bval) {
    				        
    				        $batch_id = DB::table('item_batch')
                				                ->insertGetId([
                				                    'item_id' => $value,
                				                    'batch_no' => $bval,
                				                    'mfg_date' => date('Y-m-d', strtotime($mfgArr[$bkey])),
                				                    'exp_date' => date('Y-m-d', strtotime($expArr[$bkey])),
                				                    'quantity' => $qtyArr[$bkey]
                				                ]);
                				                
                			if($batch_id) {
                			    DB::table('batch_log')
            				                ->insert([
            				                    'batch_id' => $batch_id,
            				                    'item_id' => $value,
            				                    'document_type' => 'SDO',
            				                    'document_id' => $this->supplier_do->id,
            				                    'doc_row_id' => $inv_item->id,
            				                    'quantity' => $qtyArr[$bkey],
            				                    'trtype' => 1,
            				                    'invoice_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
            				                    'log_id' => $logid,
            				                    'created_at' => date('Y-m-d h:i:s'),
            				                    'created_by' => Auth::User()->id
            				                    ]);
                			}	                
                				                
                				                
                				                
    				    }
    				
    				}
    				//.....END BATCH ENTRY
					
					
				} //END item_id foreach
				
				//other cost action... 
				if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					foreach($attributes['dr_acnt'] as $key => $value){ 
						$supplierDoOC = new SupplierDoOtherCost(); //MY27
						$arrOC = $this->setOtherCostInputValue($attributes, $supplierDoOC, $key);
						$supplierDoOC->status = 1;
						$objOC = $this->supplier_do->doOtherCost()->save($supplierDoOC);
						if($objOC) {
							$oc_vat_amt = $arrOC['oc_vat_amt'];
							$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
						}
					}
				}
				
				$subtotal = $line_total - $discount;
				if($taxtype=='tax_include' && $attributes['discount'] == 0) {
				  
				  $net_amount = $subtotal;
				  $tax_total = ($subtotal * $vat) / (100 + $vat);
				  $subtotal = $subtotal - $tax_total;
				  
				} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
				
				   $tax_total = ($subtotal * $vat) / (100 + $vat);
				   $net_amount = $subtotal - $tax_total;
				} else 
					$net_amount = $subtotal + $tax_total;
					
				if( isset($attributes['is_fc']) ) { 
					$total_fc 	   = $line_total / $attributes['currency_rate'];
					$discount_fc   = $attributes['discount']; //M14..
					$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
					$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
					$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
					$other_cost_fc = $other_cost / $attributes['currency_rate'];
					$subtotal_fc   = $subtotal / $attributes['currency_rate'];
					$discount      = $attributes['discount'] * $attributes['currency_rate']; //M14..
				} else {
					$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = 0; $other_cost_fc = $subtotal_fc = 0;
					$discount      = (isset($attributes['discount']))?$attributes['discount']:0; //M14..
				}
				
				//update discount, total amount, vat
				DB::table('supplier_do')
							->where('id', $this->supplier_do->id)
							->update([//'voucher_no' => $attributes['voucher_no'],
								      'total'    	  => $line_total,
									  'discount' 	  => $discount, //M14
									  'vat_amount'	  => $tax_total,
									  'net_total'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'other_cost'	  => $other_cost,
									  'other_cost_fc' => $other_cost_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_total_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal,
									  'subtotal_fc'	  => $subtotal_fc 
									  ]); 
									  
				$this->updateTransferStatus($attributes);
				
			}
			
		
			
			DB::commit();
			return true;
			
		  } catch (\Exception $e) {
			  
			  DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			  return false;
		  }
		  
		}
		
		//throw new ValidationException('supplier_do validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->supplier_do = $this->find($id);
		
		DB::beginTransaction();
		try {
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->supplier_do->id && !empty( array_filter($attributes['item_id']))) {
				
				$line_total = $tax_total = 0; $cost_value = 0; $other_cost = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = $othercost = 0;//MY27
				$item_total = $othercost_unit = $netcost_unit = $netcostunit = $othercost_unit = 0;
				
				if(isset($attributes['other_cost'])&& $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
					$othercost = $this->getOtherCostSum($attributes['oc_fc_amount'],$attributes['vat_oc']); //MY27
					$total_quantity = $this->getTotalQuantity($attributes['quantity']);
					$cost_sum = $other_cost = $this->getCostSum($attributes['oc_fc_amount']); //MY27
				}
				
				//calculate total amount.... linetotal taxtotal
				if( isset($attributes['is_fc']) ) //M14..
					$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
				else
					$discount = ($attributes['discount']=='')?0:$attributes['discount'];
				
				if($discount > 0) 
					$total = $this->calculateTotalAmount($attributes);
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						
						$tax_code = (isset($attributes['is_import']))?"RC":$attributes['tax_code'][$key];
						
						if( isset($attributes['is_fc']) ) {
							
							//UPDATED on MAR 7.... vat_amount
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
							
							if($tax_code=="EX" || $tax_code=="ZR") {
				
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate']) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'],2);
								
							} else if($attributes['tax_include'][$key]==1) {
								
								$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key] * $attributes['currency_rate'];
								$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
								$itemtotal = ($ln_total - $taxtotal);
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
								
							} else {
								
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key] * $attributes['currency_rate'], 2);
								$othercostunit = 0;
								if( isset($attributes['other_cost']) && $other_cost > 0 ) { //MY27
									$othercostunit = (($other_cost * $attributes['cost'][$key]) / $attributes['total_fc']) * $attributes['currency_rate'];
									$netcostunit = $othercostunit + ($attributes['cost'][$key] * $attributes['currency_rate']);
								}
							}
							
						} else {
							
							$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
							
							if($tax_code=="EX" || $tax_code=="ZR") {
				
								$tax        = 0;
								$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$othercostunit = 0;
								if(isset($attributes['other_cost']) && $attributes['other_cost'] != 0 ) {
									$othercostunit = ($attributes['other_cost'] * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
								
							} else if($attributes['tax_include'][$key]==1){
				
								$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
								$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
								$itemtotal = $ln_total - $taxtotal;
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
								
							} else {
								
								$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
								$itemtotal =(int)($attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$othercostunit = 0;
								if( isset($attributes['other_cost'])) {
									$othercostunit = ($other_cost * $attributes['cost'][$key]) / $attributes['total'];
									$netcostunit = $othercostunit + $attributes['cost'][$key];
								}
							}
						}
						
						$discount = (isset($attributes['discount']))?$attributes['discount']:0;
				
						$taxtype = 'tax_exclude';
							
						if($attributes['tax_include'][$key]==1 ) {
							$vatPlus = 100 + $attributes['line_vat'][$key];
							$total = $attributes['cost'][$key] * $attributes['quantity'][$key];
							$taxtype = 'tax_include';
						} else {
							$vatPlus = 100;
							$total = $attributes['line_total'][$key];
							$taxtype = 'tax_exclude';
						}
						
						if($discount > 0) {
							$discountAmt = round( (($total / $lineTotal) * $discount),2 );
							$amountTotal = $total - $discountAmt;
							$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
							//$line_total = $amountTotal;
							$taxtotal = (isset($attributes['is_fc']))?($vatLine * $attributes['currency_rate']):$vatLine; //M14 
						} 
						
						$tax_total += $taxtotal;
						$line_total += $linetotal;
						$item_total += $itemtotal;
						$othercost_unit += $othercostunit;
						$netcost_unit += $netcostunit;
						
						$vat = $attributes['line_vat'][$key]; 
						
						$supplierDoItem = SupplierDoItem::find($attributes['order_item_id'][$key]);//print_r($supplierDoItem);exit;//$attributes['order_item_id'][$key]);echo $attributes['order_item_id'][$key].'<pre>';
						$oldqty = $supplierDoItem->quantity;
						$items['item_name'] = $attributes['item_name'][$key];
						$items['item_id'] = $value;
						$items['unit_id'] = $attributes['unit_id'][$key];
						$items['quantity'] = $attributes['quantity'][$key];
						$items['unit_price'] = $costchk = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
						$items['vat']		 = $attributes['line_vat'][$key];
						$items['vat_amount'] = $taxtotal;
						$items['discount'] = $attributes['line_discount'][$key];
						$items['total_price'] = $linetotal;
						$items['tax_code'] 	= $tax_code;
						$items['tax_include'] = $attributes['tax_include'][$key];
						$items['item_total'] = $itemtotal;
						$items['othercost_unit'] = $othercostunit;
						$items['netcost_unit'] = ($netcostunit==0)?$attributes['cost'][$key]:$netcostunit;
						//echo '<pre>';print_r($items);exit;
						$items['unit_price_fc'] = $attributes['cost'][$key];
						$items['vat_amount_fc'] = (isset($attributes['is_fc']))?round(($taxtotal / $attributes['currency_rate']),2):$taxtotal;
						$items['total_price_fc'] = (isset($attributes['is_fc']))?round(($linetotal / $attributes['currency_rate']),2):$linetotal;
						$items['item_total_fc'] = (isset($attributes['is_fc']))?round(($itemtotal / $attributes['currency_rate']),2):$itemtotal;
						
						$exi_item_id = $supplierDoItem->item_id;
						$exi_unit_id = $supplierDoItem->unit_id;
						$exi_qty = $supplierDoItem->quantity;
						$exi_price = $supplierDoItem->unit_price;
						$itemsobj = (object)['item_id' => $exi_item_id, 'unit_id' => $exi_unit_id];
						
						$supplierDoItem->update($items);
						
						$this->setTransferStatusItem($attributes, $key, $attributes['document_type'], 'edit');
						
						//CHECK WHEATHER Update Quantity by SDO
						if($this->mod_sdo_qty->is_active==1) {
							
							//if($exi_qty != $attributes['quantity'][$key] || $exi_price != $costchk) {
								//echo '<pre>';print_r($key);exit;
								$CostAvg_log = $this->updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $othercost_unit);
							
    							//MAY25
    							$logid = $this->setPurchaseLog($attributes, $key, $this->supplier_do->id, $CostAvg_log,'update', $othercost_unit, $itemsobj);
    							if($logid)
    							{
    								//echo '<pre>';print_r($attributes);exit;
    								
    								$this->updateItemQuantityonEdit($attributes, $key);
    							    
    							}
							//}
						}
						
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) { 
								if($lq!='') {
									$updated = true;
									//$lcqty =  $lq * $attributes['packing'][$key];
									
									$lcqty = $lq;
                            		if($attributes['packing'][$key]=="1") 
                            		    $lcqty = $lq;
                            		else {
                            		   $pkgar = explode('-', $attributes['packing'][$key]);
                            		   if($pkgar[0] > 0)
                            		        $lcqty = ($lq *  $pkgar[1]) / $pkgar[0];
                            		}
                        		
									$edit = DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->where('is_sdo',1)->first();
									$idloc = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
																 
									if($edit) {
										
										if($edit->quantity < $lcqty) {
											$balqty = $lcqty - $edit->quantity;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$balqty)]);
										} else {
											$balqty = $edit->quantity - $lcqty;
											DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity - '.$balqty)]);
										}
										
									} else {
										//NOV24
										DB::table('item_location')->where('id', $idloc->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
										$sdolog = DB::table('item_location_pi')->where('item_id',$value)->where('unit_id',$attributes['unit_id'][$key])->where('invoice_id', $attributes['order_item_id'][$key])->where('is_sdo',1)->first();

										$itemLocationPI = new ItemLocationPI();
										$itemLocationPI->location_id = $attributes['locid'][$key][$lk];
										$itemLocationPI->item_id = $value;
										$itemLocationPI->unit_id = $attributes['unit_id'][$key];
										$itemLocationPI->quantity =  $lcqty;//$lq;
										$itemLocationPI->status = 1;
										$itemLocationPI->invoice_id = $attributes['order_item_id'][$key];
										$itemLocationPI->is_sdo = 1;
										$itemLocationPI->logid = ($sdolog)?$sdolog->logid:0;
										$itemLocationPI->qty_entry = $lq;
										$itemLocationPI->save();

									}
									
									DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lcqty,'status' => 1, 'deleted_at' => '0000-00-00 00:00:00','qty_entry' => $lq]);

								} else { //NOV24
									DB::table('item_location_pi')->where('id', $attributes['editid'][$key][$lk])->update(['quantity' => $lcqty,'status' => 0, 'deleted_at' => date('Y-m-d h:i:s'), 'qty_entry' => $lq]);
								}
							}

							//Log::info('SDO arr'.print_r($attributes['locqty'][$key],true));
						}
						
						//Item default location add...
						if(($attributes['location_id']!='') && ($updated == false)) {
								
								$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['location_id'])
																  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('*')->first();
																  
								//$lcqty =  $attributes['quantity'][$key] * $attributes['packing'][$key];
								
								$lcqty = $attributes['quantity'][$key];
                        		if($attributes['packing'][$key]=="1") 
                        		    $lcqty = $attributes['quantity'][$key];
                        		else {
                        		   $pkgar = explode('-', $attributes['packing'][$key]);
                        		   if($pkgar[0] > 0)
                        		        $lcqty = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
                        		}
                        		
								if($qtys) {
									DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
									DB::table('item_location_pi')->where('invoice_id', $attributes['order_item_id'][$key] )
																 ->where('location_id', $qtys->location_id)
																 ->where('item_id', $qtys->item_id)
																 ->where('unit_id', $qtys->unit_id)
																 ->update(['quantity' => DB::raw('quantity + '.$lcqty), 'qty_entry' => DB::raw('quantity + '.$attributes['quantity'][$key]) ]);
								} 
								
								$itemLocationPI = new ItemLocationPI();
								$itemLocationPI->location_id = $attributes['location_id'];
								$itemLocationPI->item_id = $value;
								$itemLocationPI->unit_id = $attributes['unit_id'][$key];
								$itemLocationPI->quantity = $lcqty; //$attributes['quantity'][$key] * $attributes['packing'][$key];
								$itemLocationPI->status = 1;
								$itemLocationPI->invoice_id = $attributes['order_item_id'][$key];
								$itemLocationPI->qty_entry = $attributes['quantity'][$key];
								$itemLocationPI->save();
						}
							
						//################ Location Stock Entry End ####################
						
						
					//BATCH NO ENTRY............
    				if($attributes['batchNos'][$key]!='' && $attributes['mfgDates'][$key]!='' && $attributes['expDates'][$key]!='' && $attributes['qtyBatchs'][$key]!='') {
    				    
    				    $batchArr = explode(',', $attributes['batchNos'][$key]);
    				    $mfgArr = explode(',', $attributes['mfgDates'][$key]);
    				    $expArr = explode(',', $attributes['expDates'][$key]);
    				    $qtyArr = explode(',', $attributes['qtyBatchs'][$key]);
    				    $bthidsArr = explode(',', $attributes['batchIds'][$key]);
			            $remArr = explode(',', $attributes['batchRem'][$key]);
    				    
    				    foreach($batchArr as $bkey => $bval) {
    				        
    				        if(isset($bthidsArr[$bkey]) && $bthidsArr[$bkey]!='') { //UPDATE...
    				        
    				            DB::table('item_batch')
			                            ->where('id', $bthidsArr[$bkey])
        				                ->update([
        				                    'batch_no' => $bval,
        				                    'mfg_date' => date('Y-m-d', strtotime($mfgArr[$bkey])),
        				                    'exp_date' => date('Y-m-d', strtotime($expArr[$bkey])),
        				                    'quantity' => $qtyArr[$bkey]
        				                ]);
        				                
        				        DB::table('batch_log')
        				                ->where('batch_id', $bthidsArr[$bkey])
        				                ->where('document_type','SDO')
        				                ->where('document_id', $this->supplier_do->id)
        				                ->where('doc_row_id', $supplierDoItem->id)
        				                ->update([
        				                    'quantity' => $qtyArr[$bkey],
        				                    'invoice_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
        				                    'modify_at' => date('Y-m-d h:i:s'),
        				                    'modify_by' => Auth::User()->id
        				                    ]);
        				                
    				        } else {  //INSERT NEW....
    				        
    				            $batch_id = DB::table('item_batch')
                				                ->insertGetId([
                				                    'item_id' => $value,
                				                    'batch_no' => $bval,
                				                    'mfg_date' => date('Y-m-d', strtotime($mfgArr[$bkey])),
                				                    'exp_date' => date('Y-m-d', strtotime($expArr[$bkey])),
                				                    'quantity' => $qtyArr[$bkey]
                				                ]);
                				                
                    			if($batch_id) {
                    			    DB::table('batch_log')
                				                ->insert([
                				                    'batch_id' => $batch_id,
                				                    'item_id' => $value,
                				                    'document_type' => 'SDO',
                				                    'document_id' => $this->supplier_do->id,
                				                    'doc_row_id' => $supplierDoItem->id,
                				                    'quantity' => $qtyArr[$bkey],
                				                    'trtype' => 1,
                				                    'invoice_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
                				                    'log_id' => $logid,
                				                    'created_at' => date('Y-m-d h:i:s'),
                				                    'created_by' => Auth::User()->id
                				                    ]);
                    			}	                
                				                
    				        }	                
                				                
    				    }
    				    
    				    //DELETE...
        			    foreach($remArr as $rem) {
        			        
        			        DB::table('item_batch')->where('id',$rem)->update(['deleted_at' => date('Y-m-d h:i:s')]);
        			        
        			        DB::table('batch_log')->where('batch_id',$rem)->where('document_type','SDO')->where('document_id',$this->supplier_do->id)->where('doc_row_id', $supplierDoItem->id)
        			                                        ->update(['deleted_at' => date('Y-m-d h:i:s'), 'deleted_by' => Auth::User()->id]);
        			    }
    				
    				}
    				//.....END BATCH ENTRY
					
					} else { 
					
						//new entry...
						$item_total_new = $tax_total_new = $item_total_new = $total = 0;
						if($discount > 0) 
							$total = $this->calculateTotalAmount($attributes);
						
						$vat = $attributes['line_vat'][$key];
						$supplierDoItem = new SupplierDoItem();
						$arrResult 		= $this->setItemInputValue($attributes, $supplierDoItem, $key, $value, $cost_sum, $total, $total_quantity);
						//if($arrResult['line_total']) {
							$line_total_new			     += $arrResult['line_total'];
							$tax_total_new      	     += $arrResult['tax_total'];
							$othercost_unit_new      	= $arrResult['othercost_unit'];
							$taxtype_new				= $arrResult['type'];
							$item_total_new			 += $arrResult['item_total'];
							
							$line_total			     += $arrResult['line_total'];
							$tax_total      	     += $arrResult['tax_total'];
							$item_total 			 += $arrResult['item_total'];
							$taxtype				  = $arrResult['type'];
							$othercost_unit      	= $arrResult['othercost_unit'];
														
							$supplierDoItem->status = 1;
							$inv_item = $this->supplier_do->doItem()->save($supplierDoItem);
							
							//NOV24
							$logid='';
							//CHECK WHEATHER Update Quantity by SDO
							if($this->mod_sdo_qty->is_active==1) {
								$CostAvg_log = $this->updateLastPurchaseCostAndCostAvg($attributes, $key, $othercost_unit);
								$attributes['item_row->id'][$key] = $inv_item->id; //OCT24
								$logid = $this->setPurchaseLog($attributes, $key, $this->supplier_do->id, $CostAvg_log,'add',$othercost_unit);
								if($logid!='')
								{
									$this->updateItemQuantity($attributes, $key);
								}
							}
							
					//	}
						
						
						//################ Location Stock Entry ####################
						//Item Location specific add....
						$updated = false;
						if(isset($attributes['locqty'][$key])) {
							foreach($attributes['locqty'][$key] as $lk => $lq) {
								if($lq!='') {
									$updated = true;
									//$lcqty = $lq * $attributes['packing'][$key];
									
									$lcqty = $lq;
                            		if($attributes['packing'][$key]=="1") 
                            		    $lcqty = $lq;
                            		else {
                            		   $pkgar = explode('-', $attributes['packing'][$key]);
                            		   if($pkgar[0] > 0)
                            		        $lcqty = ($lq *  $pkgar[1]) / $pkgar[0];
                            		}
                            		
									$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['locid'][$key][$lk])
																  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
																  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
									if($qtys) {
										DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
									} else {
										$itemLocation = new ItemLocation();
										$itemLocation->location_id = $attributes['locid'][$key][$lk];
										$itemLocation->item_id = $value;
										$itemLocation->unit_id = $attributes['unit_id'][$key];
										$itemLocation->quantity = $lcqty;
										$itemLocation->status = 1;
										$itemLocation->save();
									}
									
									$itemLocationPI = new ItemLocationPI();
									$itemLocationPI->location_id = $attributes['locid'][$key][$lk];
									$itemLocationPI->item_id = $value;
									$itemLocationPI->unit_id = $attributes['unit_id'][$key];
									$itemLocationPI->quantity =  $lcqty; //$lq * $attributes['packing'][$key];
									$itemLocationPI->status = 1;
									$itemLocationPI->invoice_id = $inv_item->id;
									$itemLocationPI->is_sdo = 1;
									$itemLocationPI->logid = $logid;
									$itemLocationPI->qty_entry = $lq;
									$itemLocationPI->save();
								}
							}
						}
						
						//Item default location add...
						if(isset($attributes['default_location']) && ($attributes['default_location'] > 0) && ($updated == false)) {
								
							$qtys = DB::table('item_location')->where('status',1)->where('location_id', $attributes['default_location'])
															  ->where('item_id', $value)//->where('unit_id', $attributes['unit_id'][$key])
															  ->where('deleted_at', '0000-00-00 00:00:00')->select('id')->first();
															  
							//$lcqty =  $attributes['quantity'][$key] * $attributes['packing'][$key];
							
							$lcqty = $attributes['quantity'][$key];
                    		if($attributes['packing'][$key]=="1") 
                    		    $lcqty = $attributes['quantity'][$key];
                    		else {
                    		   $pkgar = explode('-', $attributes['packing'][$key]);
                    		   if($pkgar[0] > 0)
                    		        $lcqty = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
                    		}
                    		
							if($qtys) {
								DB::table('item_location')->where('id', $qtys->id)->update(['quantity' => DB::raw('quantity + '.$lcqty) ]);
							} else {
									$itemLocation = new ItemLocation();
									$itemLocation->location_id = $attributes['default_location'];
									$itemLocation->item_id = $value;
									$itemLocation->unit_id = $attributes['unit_id'][$key];
									$itemLocation->quantity = $lcqty;
									$itemLocation->status = 1;
									$itemLocation->save();
								}
								
							$itemLocationPI = new ItemLocationPI();
							$itemLocationPI->location_id = $attributes['default_location'];
							$itemLocationPI->item_id = $value;
							$itemLocationPI->unit_id = $attributes['unit_id'][$key];
							$itemLocationPI->quantity =  $lcqty; //$attributes['quantity'][$key] * $attributes['packing'][$key];
							$itemLocationPI->status = 1;
							$itemLocationPI->invoice_id = $inv_item->id;
							$itemLocationPI->is_sdo = 1;
							$itemLocationPI->logid = $logid;
							$itemLocationPI->qty_entry = $attributes['quantity'][$key];
							$itemLocationPI->save();
							
						}
						//################ Location Stock Entry End ####################
						
						
						//BATCH NO ENTRY............
        				if($attributes['batchNos'][$key]!='' && $attributes['mfgDates'][$key]!='' && $attributes['expDates'][$key]!='' && $attributes['qtyBatchs'][$key]!='') {
        				    
        				    $batchArr = explode(',', $attributes['batchNos'][$key]);
        				    $mfgArr = explode(',', $attributes['mfgDates'][$key]);
        				    $expArr = explode(',', $attributes['expDates'][$key]);
        				    $qtyArr = explode(',', $attributes['qtyBatchs'][$key]);
        				    
        				    foreach($batchArr as $bkey => $bval) {
        				        
        				        $batch_id = DB::table('item_batch')
                    				                ->insertGetId([
                    				                    'item_id' => $value,
                    				                    'batch_no' => $bval,
                    				                    'mfg_date' => date('Y-m-d', strtotime($mfgArr[$bkey])),
                    				                    'exp_date' => date('Y-m-d', strtotime($expArr[$bkey])),
                    				                    'quantity' => $qtyArr[$bkey]
                    				                ]);
                    				                
                    			if($batch_id) {
                    			    DB::table('batch_log')
                				                ->insert([
                				                    'batch_id' => $batch_id,
                				                    'item_id' => $value,
                				                    'document_type' => 'SDO',
                				                    'document_id' => $this->supplier_do->id,
                				                    'doc_row_id' => $inv_item->id,
                				                    'quantity' => $qtyArr[$bkey],
                				                    'trtype' => 1,
                				                    'invoice_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
                				                    'log_id' => $logid,
                				                    'created_at' => date('Y-m-d h:i:s'),
                				                    'created_by' => Auth::User()->id
                				                    ]);
                    			}	                
                    				                
                    				                
                    				                
        				    }
        				
        				}
        				//.....END BATCH ENTRY
											
					}
					
				}
			}
			
			//UPDATED MAR 1...
			//manage removed items...
			if($attributes['remove_item']!='')
			{
				$arrids = explode(',', $attributes['remove_item']);
				$remline_total = $remtax_total = 0;
				foreach($arrids as $row) {
					DB::table('supplier_do_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					//$itm = DB::table('supplier_do_item')->where('id', $row)->first();
					
					$pirow = DB::table('item_location_pi')->where('invoice_id',$row)->where('is_sdo',1)->get();
					foreach($pirow as $prow) {
						DB::table('item_location_pi')->where('id',$prow->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
						
						DB::table('item_location')->where('location_id', $prow->location_id)->where('item_id',$prow->item_id)->where('unit_id',$prow->unit_id)
									->update(['quantity' => DB::raw('quantity - '.$prow->quantity) ]);
					}
					
					//MAY25 BATCH REMOVE..
					DB::table('batch_log')->where('document_type','SDO')->where('doc_row_id', $row)->update(['deleted_at' => date('Y-m-d h:i:s'), 'deleted_by' => Auth::User()->id]);
					$batches = DB::table('batch_log')->where('document_type','SDO')->where('doc_row_id', $row)->select('batch_id')->get();
					foreach($batches as $batch) {
					    DB::table('item_batch')->where('id', $batch->id)->update(['deleted_at' => date('Y-m-d h:i:s')]);
					}
				}
			}
			
			if($this->setInputValue($attributes)) {
				
				//if($this->supplier_do->voucher_date != date('Y-m-d', strtotime($attributes['voucher_date']))) {
					//VOUCHER DATE UPDATE IN LOG...
					DB::table('item_log')->where('document_type','SDO')->where('document_id',$this->supplier_do->id)
										 ->update(['voucher_date' => date('Y-m-d', strtotime($attributes['voucher_date'])) ]);
				//}
				
				$this->supplier_do->modify_at = date('Y-m-d H:i:s');
				$this->supplier_do->modify_by = 1;
				$this->supplier_do->fill($attributes)->save();
			}
			
			//other cost action...
			if( isset($attributes['other_cost']) && $attributes['other_cost'] != 0 && $attributes['other_cost'] > 0) {
				
				foreach($attributes['dr_acnt'] as $key => $value){ 
				
				  if($attributes['oc_id'][$key]!='') {
					$supplierDoOC = SupplierDoOtherCost::find($attributes['oc_id'][$key]);
					
					$bcurr = DB::table('parameter1')->where('id',1)->select('bcurrency_id')->first();
					$is_fc = ($bcurr->bcurrency_id == $attributes['oc_currency'][$key])?0:1;
					
					$cost['dr_account_id'] = $attributes['dr_acnt_id'][$key];
					$cost['oc_reference'] = $attributes['oc_reference'][$key];
					$cost['oc_description'] = $attributes['oc_description'][$key];
					$cost['cr_account_id'] = $attributes['cr_acnt_id'][$key];
					$cost['oc_amount']		 = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]):$attributes['oc_amount'][$key];
					$cost['oc_fc_amount'] = $attributes['oc_amount'][$key];
					$cost['oc_vat'] = $attributes['vat_oc'][$key];
					
					if($attributes['tax_sr'][$key]=="EX" || $attributes['tax_sr'][$key]=="ZR") {
						$oc_vatamt = $oc_vat_amt = 0;
					} else {
						$oc_vat_amt = ($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
						$oc_vatamt = ($is_fc==1)?($attributes['oc_amount'][$key]*$attributes['oc_rate'][$key]*$attributes['vat_oc'][$key]/100):($attributes['oc_amount'][$key] * $attributes['vat_oc'][$key])/100;
					}
					
					$cost['oc_vatamt'] = $oc_vatamt;
					$cost['is_fc'] = $is_fc;
					$cost['currency_id'] = $attributes['oc_currency'][$key];
					$cost['currency_rate'] = $attributes['oc_rate'][$key];
					$cost['tax_code'] = $attributes['tax_sr'][$key];
		
					$supplierDoOC->update($cost);
						
					$oc_net_aount = $oc_vat_amt + $attributes['oc_amount'][$key];
					$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$attributes['oc_amount'][$key]:($oc_vat_amt + $attributes['oc_amount'][$key]);
					
					//.............
				 } else {
					 
						//foreach($attributes['dr_acnt'] as $key => $value){ 
							$supplierDoOC = new SupplierDoOtherCost();
							$arrOC = $this->setOtherCostInputValue($attributes, $supplierDoOC, $key);
							$supplierDoOC->status = 1;
							$objOC = $this->supplier_do->doOtherCost()->save($supplierDoOC);
							if($objOC) {	
								$oc_vat_amt = $arrOC['oc_vat_amt'];
								$oc_net_aount = ($attributes['tax_sr'][$key]=='RC')?$arrOC['oc_amount']:($oc_vat_amt + $arrOC['oc_amount']);
							}
							
						//}
					}

				}
			}
			
			
			//UPDATED MAR 30 REMOVE OTHER COST...
			if($attributes['remove_oc']!='')
			{
				$arrids = explode(',', $attributes['remove_oc']);
				foreach($arrids as $row) {
					DB::table('pi_other_cost')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
				}
			}
			//...UPDATED MAR 30 REMOVE OTHER COST
			
			if( isset($attributes['is_fc']) )
				$discount = (isset($attributes['discount']))?($attributes['discount']* $attributes['currency_rate']):0;
			
			$subtotal = $line_total - $discount;
			if($taxtype=='tax_include' && $attributes['discount'] == 0) {
			  
			  $net_amount = $subtotal;
			  $tax_total = ($subtotal * $vat) / (100 + $vat);
			  $subtotal = $subtotal - $tax_total;
			  
			} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
			
			   $tax_total = ($subtotal * $vat) / (100 + $vat);
			   $net_amount = $subtotal - $tax_total;
			} else 
				$net_amount = $subtotal + $tax_total;
			
			
			if( isset($attributes['is_fc']) ) {
				$total_fc 	   = $line_total / $attributes['currency_rate'];
				$discount_fc   = $attributes['discount']; //M14
				$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
				$tax_fc 	   = round($tax_total / $attributes['currency_rate'],2);
				$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
				$subtotal_fc   = $subtotal / $attributes['currency_rate']; 
				$other_cost_fc = $othercost / $attributes['currency_rate']; //MY27
				$discount      = $attributes['discount'] * $attributes['currency_rate']; //M14
			} else {
				$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $other_cost_fc = $subtotal_fc = 0;
				$discount = (isset($attributes['discount']))?$attributes['discount']:0; //M14
			}
			
			//update discount, total amount
			DB::table('supplier_do')
						->where('id', $this->supplier_do->id)
						->update(['total'    	  => $line_total,
								  'discount' 	  => $discount, //M14
								  'vat_amount'	  => $tax_total,
								  'net_total'	  => $net_amount,
								  'total_fc' 	  => $total_fc,
								  'discount_fc'   => $discount_fc,
								  'other_cost'	  => $othercost, //MY27
								  'other_cost_fc' => $other_cost_fc,
								  'vat_amount_fc' => $tax_fc,
								  'net_total_fc'  => $net_amount_fc,
								  'subtotal'	  => $subtotal, //CHG
								  'subtotal_fc'	  => $subtotal_fc ]); //CHG
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			 DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
	}
	
	
	public function delete($id)
	{
		$this->supplier_do = $this->supplier_do->find($id);
		
		//inventory update...
		DB::beginTransaction();
		try {
			
			//Update control of MR,PO... 
			if($this->supplier_do->document_id > 0) {
				if($this->supplier_do->document_type=='MR') {
					DB::table('material_requisition')->where('id', $this->supplier_do->document_id)
										->update(['is_transfer' => 0, 'is_editable' => 0]);
										
					DB::table('material_requisition_item')->where('material_requisition_item_id', $this->supplier_do->document_id)
										->update(['is_transfer' => 0]);
										
				} else if($this->supplier_do->document_type=='PO') {
				    
				    $ids = explode(',', $this->supplier_do->document_id);
				    DB::table('purchase_order')->whereIn('id', $ids)->update(['is_transfer' => 0, 'is_editable' => 0]);

				    DB::table('purchase_order_item')->whereIn('purchase_order_id', $ids)->update(['is_transfer' => 0]);

				} 
			}
			
			$items = DB::table('supplier_do_item')->where('supplier_do_id', $id)->select('id','item_id','quantity','unit_id','unit_price','doc_row_id')->get();
			
			$this->updateLastPurchaseCostAndCostAvgonDelete($items,$id);
			DB::table('supplier_do_item')->where('supplier_do_id', $id)
								  ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			
			foreach($items as $item) {
			    
			    DB::table('purchase_order_item')->where('purchase_order_id',$this->supplier_do->document_id)->where('item_id',$item->item_id)->where('id',$item->doc_row_id)
								->update(['balance_quantity' => DB::raw('balance_quantity + '.$item->quantity),'is_transfer' => 0 ]);
			    
				$pirow = DB::table('item_location_pi')->where('invoice_id',$item->id)->where('is_sdo',1)->get();
				
				foreach($pirow as $prow) {
					DB::table('item_location_pi')->where('id',$prow->id)->update(['status'=>0,'deleted_at'=>date('Y-m-d H:i:s')]);
					
					DB::table('item_location')->where('location_id', $prow->location_id)->where('item_id',$prow->item_id)->where('unit_id',$prow->unit_id)
								->update(['quantity' => DB::raw('quantity - '.$prow->quantity) ]);
				}
			}
			DB::table('supplier_do')->where('id', $id)
									  ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id ]);	
									  
			//MAY25 BATCH REMOVE..
			DB::table('batch_log')->where('document_type','SDO')->where('document_id', $id)->update(['deleted_at' => date('Y-m-d h:i:s'), 'deleted_by' => Auth::User()->id]);
			$batches = DB::table('batch_log')->where('document_type','SDO')->where('document_id', $id)->select('batch_id')->get();
			foreach($batches as $batch) {
			    DB::table('item_batch')->where('id', $batch->id)->update(['deleted_at' => date('Y-m-d h:i:s')]);
			}
					
			$this->supplier_do->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}	
	}
	
	public function suppliersDOList()
	{
		$query = $this->supplier_do->where('supplier_do.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_do.supplier_id');
						} )
					->select('supplier_do.*','am.master_name AS supplier')
					->orderBY('supplier_do.id', 'DESC')
					->get();
	}
	
	public function getSDOdata($supplier_id = null)
	{
		if($supplier_id)
			$query = $this->supplier_do->where('supplier_do.status',1)->where('supplier_do.is_transfer',0)->where('supplier_do.supplier_id',$supplier_id);
		else
			$query = $this->supplier_do->where('supplier_do.status',1)->where('supplier_do.is_transfer',0);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_do.supplier_id');
						} )
					->select('supplier_do.*','am.master_name AS supplier')
					->orderBY('supplier_do.id', 'ASC')
					->get();
	}
	
	public function findSDOdata($id)
	{
		$query = $this->supplier_do->where('supplier_do.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_do.supplier_id');
						} )
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','supplier_do.job_id');
					})
					->select('supplier_do.*','am.master_name AS supplier','J.code','am.duedays')
					->orderBY('supplier_do.id', 'ASC')
					->first();
	}
	
	public function activeSupplierDoList()
	{
		return $this->supplier_do->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->supplier_do->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->supplier_do->where('reference_no',$refno)->count();
	}
		
	public function getSDOitems($id)
	{
		$query = $this->supplier_do->whereIn('supplier_do.id',$id);
		
		return $query->join('supplier_do_item AS poi', function($join) {
							$join->on('poi.supplier_do_id','=','supplier_do.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->join('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
						  $join->on('iu.unit_id','=','poi.unit_id');
					  })
					->where('poi.status',1)
					->whereIn('poi.is_transfer',[0,2])
					->where('poi.deleted_at', '0000-00-00 00:00:00')
					->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','iu.pkno')
					->orderBY('poi.id')->groupBy('poi.id')
					->get();
		//return $this->itemmaster->where('status', 1)->orderBy('description','ASC')->select('id','item_code','description')->get();
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->supplier_do->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->supplier_do->where('voucher_no',$refno)->count();
	}
	
	public function check_order($id)
	{
		$count = DB::table('supplier_do')->where('id', $id)->where('is_editable',1)->count();
		if($count > 0)
			return false;
		else
			return true;
	}
	
	public function getItems($id)
	{
		$query = $this->supplier_do->where('supplier_do.id',$id);
		
		return $query->join('supplier_do_item AS poi', function($join) {
							$join->on('poi.supplier_do_id','=','supplier_do.id');
						} )
						->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->join('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
						  $join->on('iu.unit_id','=','poi.unit_id');//JUN25
					    })
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->leftjoin('purchase_order_item AS ci', function($join){
						  $join->on('ci.id','=','poi.doc_row_id');
					  })
					  ->where('poi.status',1)
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.packing','ci.balance_quantity as po_balance_quantity','iu.pkno')
					  ->orderBY('poi.id')
					  ->groupBY('poi.id')
					  ->get();
	}
	
	public function getOtherCost($id)
	{	
	    
		$query = $this->supplier_do->whereIn('supplier_do.id',$id);
	//	echo '<pre>';print_r($id);exit;
		return $query->join('sdo_other_cost AS pi', function($join) {
							$join->on('pi.supplier_do_id','=','supplier_do.id');
						} )
					  ->join('account_master AS im', function($join){
						  $join->on('im.id','=','pi.dr_account_id');
					  })
					  ->leftJoin('account_master AS im2', function($join){
						  $join->on('im2.id','=','pi.cr_account_id');
					  })
					  ->where('pi.status',1)
					  ->select('pi.*','im.id AS dr_id','im.master_name AS dr_name','im2.id AS cr_id','im2.master_name AS cr_name')->get();
	}
	
	public function findPOdata($id)
	{
		$query = $this->supplier_do->where('supplier_do.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','supplier_do.supplier_id');
						} )
					->leftJoin('jobmaster AS J',function($join) {
						$join->on('J.id','=','supplier_do.job_id');
					})
					->select('supplier_do.*','am.master_name AS supplier','J.code')
					->orderBY('supplier_do.id', 'ASC')
					->first();
	}
	
	private function setPurchaseLog($attributes, $key, $document_id, $cost_avg, $action, $other_cost, $item=null)
	{
		$irow = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])->select('cur_quantity')->first();
		
		//JUN25
		$unit_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate']):($attributes['cost'][$key]);
		$pur_cost = (isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:($attributes['cost'][$key])+$other_cost;
		
		$quantity = $attributes['quantity'][$key];
		
		if($attributes['packing'][$key]=="1") 
		    $quantity = $attributes['quantity'][$key];
		else {
		   $pkgar = explode('-', $attributes['packing'][$key]);
		   if($pkgar[0] > 0)
		        $quantity = ($attributes['quantity'][$key] *  $pkgar[1]) / $pkgar[0];
		   
		   //COST...
		   $unit_cost = ($unit_cost * $pkgar[0]) / $pkgar[1];
		   $pur_cost = ($pur_cost * $pkgar[0]) / $pkgar[1];
		}
		
		if($action=='add') {
			
			$cur_quantity = ($irow)?$irow->cur_quantity + $attributes['quantity'][$key]:0;
			//-----------ITEM LOG----------------							
			$logid = DB::table('item_log')->insertGetId([
							 'document_type' => 'SDO',
							 'document_id'   => $document_id,
							 'item_id' 	  => $attributes['item_id'][$key],
							 'unit_id'    => $attributes['unit_id'][$key],
							 'quantity'   => $quantity, //$attributes['quantity'][$key] * $attributes['packing'][$key],
							 'unit_cost'  => $unit_cost, //(isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
							 'trtype'	  => 1,
							 'cur_quantity' => $attributes['quantity'][$key],
							 'cost_avg' => $cost_avg, //COMMENTED on FEB25
							 'pur_cost' => $pur_cost, //(isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost, //COMMENTED on FEB25
							 'packing' => 1,
							 'status'     => 1,
							 'created_at' => date('Y-m-d H:i:s'),
							 'created_by' => Auth::User()->id,
							 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
							 'sale_reference' => $cur_quantity,
							 'item_row_id'	=> isset($attributes['item_row_id'][$key])?$attributes['item_row_id'][$key]:'' //OCT24
							]);
			//-------------ITEM LOG------------------
			
		} else if($action=='update') {
		    
		    //MAY25
		    $slog = DB::table('item_log')->where('document_type','SDO')->where('document_id', $document_id)->where('item_id', $item->item_id)->where('unit_id', $item->unit_id)->where('item_row_id', $attributes['order_item_id'][$key])
		                ->select('id')->first();
			$logid = $slog->id;
			
			//-----------ITEM LOG----------------							
			DB::table('item_log')->where('document_type','SDO')
							->where('document_id', $document_id)
							->where('item_id', $item->item_id)
							->where('unit_id', $item->unit_id)
							->where('item_row_id', $attributes['order_item_id'][$key]) //OCT24
							->update(['item_id' => $attributes['item_id'][$key],
								 'unit_id' => $attributes['unit_id'][$key],
								 'quantity'   => $quantity, //$attributes['quantity'][$key] * $attributes['packing'][$key],
								 'unit_cost'  => $unit_cost, //(isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key],
								 'cur_quantity' => $attributes['quantity'][$key],
								 'cost_avg' => $cost_avg, //COMMENTED on FEB25
								 'pur_cost' => $pur_cost, //(isset($attributes['is_fc']))?($attributes['cost'][$key]*$attributes['currency_rate'])+$other_cost:$attributes['cost'][$key]+$other_cost, //COMMENTED on FEB25
								 'voucher_date' => ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
			//-------------ITEM LOG------------------
		}
							
		return $logid;
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
										
		$itmcost = (isset($attributes['is_fc']))? ($attributes['quantity'][$key] * $attributes['cost'][$key] * $attributes['currency_rate']) : $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
					
		DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->where('unit_id', $attributes['unit_id'][$key])
				->update([//'last_purchase_cost' => $cost + $other_cost,
						  'pur_count' 		   => DB::raw('pur_count + 1')
						  //'cost_avg'		   => $cost_avg
						]);
						
		//UPDATING ROWMATERIAL COST...
		/* $itemlog = DB::table('mfg_items')->where('subitem_id', $attributes['item_id'][$key])->where('unit_price', 0)->select('id','quantity')->first();
		if($itemlog) {
			$ucost = $cost + $other_cost;
			$mtotal = $ucost * $itemlog->quantity;
			DB::table('mfg_items')->where('id', $itemlog->id)->update(['unit_price' => $ucost, 'total'	=> $mtotal ]);
		} */
							
		return $cost_avg;
		
	}
	
	private function updateItemQuantity($attributes, $key)
	{
		$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
									  ->where('is_baseqty', 1)->first();
									  
		if($item) {
			$qty = $attributes['quantity'][$key];
			$baseqty = ($qty * $attributes['packing'][$key]);
			DB::table('item_unit')
				->where('id', $item->id)
				->update([ 'cur_quantity' => $item->cur_quantity + $baseqty,
						   'received_qty' => DB::raw('received_qty + '.$baseqty) ]);
							
		}
									  
		return true;
	}
	
	private function updateLastPurchaseCostAndCostAvgonEdit($attributes, $key, $other_cost)
	{	
		$pid = $attributes['purchase_invoice_id'];
		$itmlogs = DB::table('item_log')->where('item_id', $attributes['item_id'][$key])
										->where('status', 1)
										->where('trtype', 1)
										->where('cur_quantity', '>', 0)
										->where('deleted_at','0000-00-00 00:00:00')
										->where(function ($query) use($pid) {
											$query->where('document_id','!=',$pid)
												  ->orWhere('document_type','!=','SDO');
										})
										->select('cur_quantity','pur_cost')
										->get();
		
		$itmcost = (isset($attributes['is_fc']))? ($attributes['quantity'][$key] * $attributes['cost'][$key] * $attributes['currency_rate']) : $attributes['quantity'][$key] * $attributes['cost'][$key];
		$itmqty = $attributes['quantity'][$key];
		foreach($itmlogs as $log) {
			$itmcost += ($log->cur_quantity * $log->pur_cost);
			$itmqty += $log->cur_quantity;
		}
		
		$cost_avg = round( (($itmcost / $itmqty) + $other_cost), 3);
		$cost = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		
		/* DB::table('item_unit')
				->where('itemmaster_id', $attributes['item_id'][$key])
				->where('unit_id', $attributes['unit_id'][$key])
				->update(['last_purchase_cost' => $cost + $other_cost,
						  'cost_avg'		   => $cost_avg
						]); */
							
		return $cost_avg;
	}
	
	private function updateItemQuantityonEdit($attributes, $key)
	{
		if($attributes['actual_quantity'][$key] != $attributes['quantity'][$key]) {
			
			$item = DB::table('item_unit')->where('itemmaster_id', $attributes['item_id'][$key])
										  ->where('is_baseqty', 1)->first();
										  
			if($item) {
				$qty = $attributes['quantity'][$key];
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
	
	public function getPOitems($id)
	{
		$query = $this->supplier_do->whereIn('supplier_do.id',$id);
		
		return $query->join('supplier_do_item AS poi', function($join) {
							$join->on('poi.supplier_do_id','=','supplier_do.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					   ->join('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
					  })
					  ->where('poi.status',1)
					  ->whereIn('poi.is_transfer',[0,2])
					  ->where('poi.deleted_at', '0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty')
					  ->orderBY('poi.id')
					  ->get();
					  
	}
	
	private function updateLastPurchaseCostAndCostAvgonDelete($items, $id) {
		//UPDATE Cost avg and stock...
		foreach($items as $item) {
									
			//COST AVG Updating on DELETE section....
			DB::table('item_log')->where('document_id', $id)->where('document_type','SDO')
								 ->where('item_id',$item->item_id)->where('unit_id', $item->unit_id)
								 ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			
			DB::table('item_unit')->where('itemmaster_id', $item->item_id)->where('unit_id',$item->unit_id)
								  ->update(['cur_quantity' => DB::raw('cur_quantity - '.$item->quantity)]);
									  
		}
	}
	
}