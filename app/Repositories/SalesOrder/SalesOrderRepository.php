<?php namespace App\Repositories\SalesOrder;

use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderInfo;
use App\Models\JobOrderDetails;
use App\Repositories\AbstractValidator;
use App\Models\ItemDescription;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\File; 
use App\Repositories\UpdateUtility;

use Session;
use Config;
use DB;
//use Carbon;     
use Carbon\Carbon;
use input;
use Auth;


class SalesOrderRepository extends AbstractValidator implements SalesOrderInterface {
	
	protected $sales_order;
	public $objUtility;
	
	protected static $rules = [];
	protected $matservice;
	protected $module;
	
	public function __construct(SalesOrder $sales_order) {
		$this->sales_order = $sales_order;
		$config = Config::get('siteconfig');
		$this->objUtility = new UpdateUtility();
		
		$this->module = DB::table('parameter2')->where('keyname', 'mod_workshop')->where('status',1)->select('is_active')->first();
		$this->matservice = DB::table('parameter2')->where('keyname', 'mod_material_service')->where('status',1)->select('is_active')->first();
		$this->modulejomanual = DB::table('parameter2')->where('keyname', 'mod_joborder_manual')->where('status',1)->select('is_active')->first();
		
		$this->width = $config['modules']['joborder']['image_size']['width'];
        $this->height = $config['modules']['joborder']['image_size']['height'];
        $this->thumbWidth = $config['modules']['joborder']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['joborder']['thumb_size']['height'];
        $this->imgDir = $config['modules']['joborder']['image_dir'];
	}
	
	public function all()
	{
		return $this->sales_order->get();
	}
	
	public function find($id)
	{
		return $this->sales_order->where('id', $id)->first();
	}
	
	private function jobmasterEntry($attributes) {
	    
	    	$jcount=DB::table('jobmaster')->where('jobmaster.id', $attributes['job_id'])->where('status',1)->where('is_salary_job',0)
		                    ->where('deleted_at','0000-00-00 00:00:00')->count();
      if($jcount==0){
		
		$id = DB::table('jobmaster')
							->insertGetId([
								'code'  => $attributes['prefix'].$attributes['voucher_no'],
								'name'  => ($attributes['description']=='')?'Job - '.$attributes['prefix'].$attributes['voucher_no']:$attributes['description'],
								'customer_id'	=> $attributes['customer_id'],
								'salesman_id'	=> $attributes['salesman_id'],
								'start_date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'end_date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'status'		=> 1,
								'vehicle_id'	=> isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'',
								'date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
							
      }
       else{
						DB::table('jobmaster')->where('jobmaster.id', $attributes['job_id'])
							->update([
							'code'  => $attributes['prefix'].$attributes['voucher_no'],
								'name'  => ($attributes['description']=='')?'Job - '.$attributes['prefix'].$attributes['voucher_no']:$attributes['description'],
								'customer_id'	=> $attributes['customer_id'],
								'salesman_id'	=> $attributes['salesman_id'],
								'start_date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'end_date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date'])),
								'status'		=> 1,
								'vehicle_id'	=> isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'',
								'date' 	=> ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']))
							]);
                $id=$attributes['job_id'];
					}	
							
		return $id;
			
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		//Joborder enter into job master....
		if($this->module->is_active==1 && $this->modulejomanual->is_active==0)
			$attributes['job_id'] = $this->jobmasterEntry($attributes);  
		   // echo '<pre>';print_r($attributes['job_id']);exit;
		
		$this->sales_order->voucher_no    = $attributes['voucher_no'];
		$this->sales_order->reference_no  = $attributes['reference_no'] ?? '';
		$this->sales_order->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->sales_order->lpo_date      = ($attributes['lpo_date']!='')?date('Y-m-d', strtotime($attributes['lpo_date'])):'';
		$this->sales_order->customer_id   = $attributes['customer_id'];
		$this->sales_order->quotation_id  = isset($attributes['quotation_id'])?$attributes['quotation_id']:'';
		$this->sales_order->description   = $attributes['description'] ?? '';
		$this->sales_order->items_description  = isset($attributes['items_description'])?$attributes['items_description']:'';
		$this->sales_order->job_id 		  = isset($attributes['job_id'])?$attributes['job_id']:'';
		$this->sales_order->terms_id 	  = $attributes['terms_id'] ?? 0;
		$this->sales_order->footer_id 	  = isset($attributes['footer_id'])?$attributes['footer_id'] ?? 0:'';
		$this->sales_order->is_fc 		  = isset($attributes['is_fc'])?1:0;
		$this->sales_order->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id'] ?? 0:'';
		$this->sales_order->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate'] ?? 0:'';
		$this->sales_order->salesman_id  = (isset($attributes['salesman_id']))?$attributes['salesman_id'] ?? 0:'';
		$this->sales_order->is_export		= isset($attributes['is_export'])?1:0;
		$this->sales_order->vehicle_id		= isset($attributes['vehicle_id'])?$attributes['vehicle_id'] ?? 0:'';
		
		$this->sales_order->next_due  = (isset($attributes['next_due'])&&$attributes['next_due']!='')?date('Y-m-d', strtotime($attributes['next_due'])):'';
		$this->sales_order->kilometer		= isset($attributes['kilometer'])?$attributes['kilometer']:(isset($attributes['delivery'])?$attributes['delivery']:'');
		$this->sales_order->present_km		= isset($attributes['present_km'])?$attributes['present_km']:'';
		$this->sales_order->service_km		= isset($attributes['service_km'])?$attributes['service_km']:'';
		$this->sales_order->next_km		= isset($attributes['next_km'])?$attributes['next_km']:'';
		$this->sales_order->job_type		= isset($attributes['job_type'])?$attributes['job_type']:(isset($attributes['jobtype'])?$attributes['jobtype']:''); //SUPPLIMENTRY JOB TYPE
		$this->sales_order->jobnature		= isset($attributes['jobnature'])?$attributes['jobnature']:'';
		$this->sales_order->fabrication		= isset($attributes['fabrication'])?$attributes['fabrication']:(isset($attributes['parent_job'])?$attributes['parent_job']:''); //SUPPLIMENTRY JOB PARENT id
		$this->sales_order->prefix			= isset($attributes['prefix'])?$attributes['prefix']:'';
		$this->sales_order->less_amount	    = isset($attributes['less_amount'])?$attributes['less_amount']:'';
		$this->sales_order->less_description = isset($attributes['less_description'])?$attributes['less_description']:(isset($attributes['location'])?$attributes['location']:'');
		
		$this->sales_order->less_amount2	    = isset($attributes['less_amount2'])?$attributes['less_amount2']:'';
		$this->sales_order->less_description2 = isset($attributes['less_description2'])?$attributes['less_description2']:(isset($attributes['info_delivery'])?$attributes['info_delivery']:'');
		$this->sales_order->less_amount3	    = isset($attributes['less_amount3'])?$attributes['less_amount3']:'';
		$this->sales_order->less_description3 = isset($attributes['less_description3'])?$attributes['less_description3']:'';
		$this->sales_order->location_id = (isset($attributes['location_id']))?$attributes['location_id']:(isset($attributes['department_id'])?$attributes['department_id']:'');
		$this->sales_order->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description']:'';
		$this->sales_order->metre_in = (isset($attributes['metre_in']))?$attributes['metre_in']:'';
		$this->sales_order->metre_out = (isset($attributes['metre_out']))?$attributes['metre_out']:'';
		
		$jbno = '';
		if(isset($attributes['jobtype']) && $attributes['jobtype']==1) {
			$narr = explode('/',$attributes['voucher_no']);
			$jbno = $narr[0];
			if($attributes['btn']=='submit')
				DB::table('sales_order')->where('id', $attributes['parent_job'])->update(['jctype' => $narr[1]]);
		} 
		
		$this->sales_order->jctype 	  = isset($attributes['jctype'])?$attributes['jctype']:'';
		$this->sales_order->is_warning 	  = isset($attributes['is_warning'])?$attributes['is_warning']:'';
		$this->sales_order->items_inside 	  = isset($attributes['items_inside'])?$attributes['items_inside']:$jbno; //SUPPLIMENTRY PARENT JOB No
		$this->sales_order->remarks 	  = isset($attributes['remarks'])?$attributes['remarks']:'';
		$this->sales_order->signature 	  = isset($attributes['sign'])?($attributes['customer_id'].'_'.date('d-m-Y').'.png') : '';
		$this->sales_order->fuel_level 	  = isset($attributes['fuel_level'])?$attributes['fuel_level']:'';
		
		return true;
	}
	
	private function setJobdetails($attributes) {
							
		if(isset($attributes['opr_description']) && !empty( array_filter($attributes['opr_description'])) ) {
			
			foreach($attributes['opr_description'] as $key => $value){ 
				if($value != '') {
					$jobOrderDetails = new JobOrderDetails();
					$jobOrderDetails->joborder_id = $this->sales_order->id;
					$jobOrderDetails->description = $value;
					$jobOrderDetails->comment = $attributes['opr_comment'][$key];
					$jobOrderDetails->status = 1;
					$jobOrderDetails->save();
				}
			}
		}
		
	}
	
	private function setJobdetailsUpdate($attributes) {
		
		if(isset($attributes['opr_description']) && !empty( array_filter($attributes['opr_description'])) ) {
			foreach($attributes['opr_description'] as $key => $value) { 
			
				if(isset($attributes['jobdetail_id'][$key]) && $attributes['jobdetail_id'][$key]!='') {
					
					$jobOrderDetails = jobOrderDetails::find($attributes['jobdetail_id'][$key]);
					$items['description'] = $value;
					$items['comment'] = $attributes['opr_comment'][$key];
					$jobOrderDetails->update($items);
					
				} else { //new entry.....
					
					if($value != '') {
						$jobOrderDetails = new JobOrderDetails();
						$jobOrderDetails->joborder_id = $this->sales_order->id;
						$jobOrderDetails->description = $value;
						$jobOrderDetails->comment = $attributes['opr_comment'][$key];
						$jobOrderDetails->status = 1;
						$jobOrderDetails->save();
					}
				}
			}
		}
		
		//manage removed items...
		if(isset($attributes['opr_description']) && $attributes['remove_desc']!='')
		{
			$arrids = explode(',', $attributes['remove_desc']);
			$remline_total = $remtax_total = 0;
			foreach($arrids as $row) {
				DB::table('joborder_details')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			}
		}
	}
	
	
	private function setItemInputValue($attributes, $salesOrderItem, $key, $value,$lineTotal) 
	{
		if( isset($attributes['is_fc']) ) {
			$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			
			$tax_code = (isset($attributes['is_export']))?"ZR":(isset($attributes['tax_code'][$key])?$attributes['tax_code'][$key]:'');
						
			if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if(isset($attributes['tax_include'][$key]) && $attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
			} else {
				$tax = $tax_total = 0;
				if(isset($attributes['line_vat'][$key])){
					$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
					$tax_total  = round($tax * $attributes['quantity'][$key],2);
				}
				$item_total = (int)($attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)(isset($attributes['line_discount'][$key])?$attributes['line_discount'][$key]:'');
			}
		}
		
		//********DISCOUNT Calculation.............
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		$type = 'tax_exclude';
			
		if(isset($attributes['tax_include'][$key]) && $attributes['tax_include'][$key]==1 ) {
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
			$tax_total = $vatLine; 
		} 
		
		//CHNG SEP 17
		$pay_pcntg = 100; $pay_amount = $line_total; $pay_pcntg_desc = '';
		if(isset($attributes['per'][$key]) && $attributes['per'][$key]!='' && $attributes['per'][$key] > 0) {
			$pay_pcntg = $attributes['per'][$key];
			$pay_amount = ($line_total * $pay_pcntg) / 100;
			$pay_pcntg_desc = $attributes['perdesc'][$key];
		} else {
			$pay_pcntg_desc = isset($attributes['sojob_id'][$key])?$attributes['sojob_id'][$key]:'';
		}
		
		if(isset($attributes['metname'][$key])) {
			$metname = '<br/>'.$attributes['metname'][$key];
		} else
			$metname = '';
		
		$salesOrderItem->sales_order_id = $this->sales_order->id;
		$salesOrderItem->item_id    	= $value;
		$salesOrderItem->item_name  	= $attributes['item_name'][$key].$metname;
		$salesOrderItem->unit_id 		= $attributes['unit_id'][$key];
		$salesOrderItem->quantity   	= $attributes['quantity'][$key];
		$salesOrderItem->unit_price 	= (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$salesOrderItem->vat		    = isset($attributes['line_vat'][$key])?$attributes['line_vat'][$key]:'';
		$salesOrderItem->vat_amount 	= isset($tax_total)?$tax_total:'';
		$salesOrderItem->discount   	= isset($attributes['line_discount'][$key])?$attributes['line_discount'][$key] ?? 0:'';
		$salesOrderItem->line_total 	= $line_total;
		$salesOrderItem->tax_code 		= isset($tax_code)?$tax_code:'';
		$salesOrderItem->tax_include 	= isset($attributes['tax_include'][$key])?$attributes['tax_include'][$key]:'';
		$salesOrderItem->item_total 	= $item_total;
		
		//CHNG SEP 17
		$salesOrderItem->pay_pcntg 		= $pay_pcntg;
		$salesOrderItem->pay_amount 	= $pay_amount;
		$salesOrderItem->pay_pcntg_desc 		= $pay_pcntg_desc;
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'type' => $type, 'item_total' => $item_total);
	}
	
	private function setInfoInputValue($attributes, $salesOrderInfo, $key, $value)
	{
		$salesOrderInfo->sales_order_id = $this->sales_order->id;
		$salesOrderInfo->title 			= $attributes['title'][$key];
		$salesOrderInfo->description 	= $attributes['desc'][$key];
		return true;
	}
	
	private function setTransferStatusItem($attributes, $key)
	{
		if(isset($attributes['po_to_so'])) { //CHECK PO TO SO OR NOT...
			//if quantity partially deliverd, update pending quantity.
			if(isset($attributes['quote_sales_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['quote_sales_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('purchase_order_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		} else {
			//if quantity partially deliverd, update pending quantity.
			if(isset($attributes['quote_sales_item_id'])) {
				if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
					if( isset($attributes['quote_sales_item_id'][$key]) ) {
						$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
						//update as partially delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
					}
				} else {
						//update as completely delivered.
						DB::table('quotation_sales_item')
									->where('id', $attributes['quote_sales_item_id'][$key])
									->update(['balance_quantity' => 0, 'is_transfer' => 1]);
				}
			}
		}
	}
	
	private function setTransferStatusService($attributes, $key)
	{
		//if quantity partially deliverd, update pending quantity.
		if(isset($attributes['lbquote_sales_item_id'][$key])) {
			if(isset($attributes['lbactual_quantity']) && ($attributes['lbquantity'][$key] != $attributes['lbactual_quantity'][$key])) {
				if( isset($attributes['lbquote_sales_item_id'][$key]) ) {
					$quantity 	 = $attributes['lbactual_quantity'][$key] - $attributes['lbquantity'][$key];
					//update as partially delivered.
					DB::table('quotation_sales_item')
								->where('id', $attributes['lbquote_sales_item_id'][$key])
								->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
				}
			} else {
					//update as completely delivered.
					DB::table('quotation_sales_item')
								->where('id', $attributes['lbquote_sales_item_id'][$key])
								->update(['balance_quantity' => 0, 'is_transfer' => 1]);
			}
		}
	}
	
	private function setTransferStatusQuote($attributes)
	{
		if($this->matservice->is_active==1) {
			$idarr = explode(',',$attributes['quotation_id']);
			if($idarr) {
				foreach($idarr as $id) {
					DB::table('quotation_sales')->where('id', $id)->update(['is_editable' => 1, 'is_transfer' => 1]);
				}
			}
			
		} else {
			
			if(isset($attributes['po_to_so'])) { //CHECK PO TO SO OR NOT
				//update purchase order transfer status....
				$idarr = explode(',',$attributes['quotation_id']);
				if($idarr) {
					foreach($idarr as $id) {
						DB::table('purchase_order')->where('id', $id)->update(['is_editable' => 1]);
						$row1 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
						$row2 = DB::table('purchase_order_item')->where('purchase_order_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_transfer',1)->count();
						if($row1==$row2) {
							DB::table('purchase_order')
									->where('id', $id)
									->update(['is_transfer' => 1]);
						}
					}
				}

			} else {
				//update purchase order transfer status....
				if(isset($attributes['quotation_id'])) {
					$idarr = explode(',',$attributes['quotation_id']);
					if($idarr) {
						foreach($idarr as $id) {
							DB::table('quotation_sales')->where('id', $id)->update(['is_editable' => 1]);
							$row1 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
							$row2 = DB::table('quotation_sales_item')->where('quotation_sales_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_transfer',1)->count();
							if($row1==$row2) {
								DB::table('quotation_sales')
										->where('id', $id)
										->update(['is_transfer' => 1]);
							}
						}
					}
				}
			}
		}
	}
	
	private function calculateTotalAmount2($attributes) {
		
		$total = 0;
		foreach($attributes['item_id'] as $key => $value){ 
			
			$total += (float)$attributes['quantity'][$key] *(float)$attributes['cost'][$key];
		}
		return $total;
	}
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		if(isset($attributes['item_id'])) {
			foreach($attributes['item_id'] as $key => $value){ 
				
				$total += (float)$attributes['quantity'][$key] * (float)$attributes['cost'][$key];
			}
		}
		
		if(isset($attributes['lbitem_id'])) {
			foreach($attributes['lbitem_id'] as $key => $value){ 
				
				$total += $attributes['lbquantity'][$key] * $attributes['lbcost'][$key];
			}
		}
		
		return $total;
	}
	
	protected function addService($attributes) {
		
		$linetotal = $taxtotal = $itemtotal = $lbtax_total = 0; $type = 'tax_exclude'; $vat = 0;
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		if($discount > 0)
			$lineTotal = $this->calculateTotalAmount($attributes);
		
			foreach($attributes['lbitem_id'] as $key => $value){ 
				
				if($value!='') {
					$salesOrderItem = new SalesOrderItem();
					
					//------------ SEP25
					$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['lbtax_code'][$key];
					$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);		
					if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
						$tax        = 0;
						$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);// - $attributes['line_discount'][$key];
						$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
						
					} else if($attributes['lbtax_include'][$key]==1){
						
						$ln_total   = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
						$lbtax_total  = $ln_total *  $attributes['lbline_vat'][$key] / (100 +  $attributes['lbline_vat'][$key]);
						$lbitem_total = $ln_total - $lbtax_total;
						
					} else {
						
						$tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
						$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
						$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
						$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
					}
					
					$type = 'tax_exclude'; //SEP25
					if($attributes['lbtax_include'][$key]==1 ) {
						$vatPlus = 100 + $attributes['lbline_vat'][$key];
						$total = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
						$type = 'tax_include';
					} else {
						$vatPlus = 100;
						$total = $attributes['lbline_total'][$key];
						$type = 'tax_exclude';
					}
					
					if($discount > 0) {
						$discountAmt = round( (($total / $lineTotal) * $discount),2 );
						$amountTotal = $total - $discountAmt;
						$vatLine = round( (($amountTotal * $attributes['lbline_vat'][$key]) / $vatPlus),2 );
						$lbtax_total = $vatLine; 
					}
						
					$salesOrderItem->sales_order_id = $this->sales_order->id;
					$salesOrderItem->item_id    		= $value;
					$salesOrderItem->item_name  		= $attributes['lbitem_name'][$key];
					$salesOrderItem->unit_id 			= $attributes['lbunit_id'][$key];
					$salesOrderItem->quantity   		= $attributes['lbquantity'][$key];
					$salesOrderItem->unit_price 		= (isset($attributes['is_fc']))?$attributes['lbcost'][$key]*$attributes['currency_rate']:$attributes['lbcost'][$key];
					$salesOrderItem->vat		    	= $attributes['lbline_vat'][$key];
					$salesOrderItem->vat_amount 		= $lbtax_total;
					$salesOrderItem->discount   		= $attributes['lbline_discount'][$key];
					$salesOrderItem->line_total 		= $lbline_total;
					$salesOrderItem->tax_code 			= $attributes['lbtax_code'][$key];
					$salesOrderItem->tax_include 		= $attributes['lbtax_include'][$key];
					$salesOrderItem->item_total 		= $lbitem_total;
					$salesOrderItem->item_type = 1;
					$salesOrderItem->status = 1;
					$itemObj = $this->sales_order->salesOrderItem()->save($salesOrderItem);
					
					$linetotal += $lbline_total;
					$taxtotal += $lbtax_total;
					$itemtotal += $lbitem_total;
					$vat = $attributes['lbline_vat'][$key]; //SEP25
				}
			}
		return array('line_total' => $linetotal, 'tax_total' => $taxtotal, 'item_total' => $itemtotal, 'type' => $type, 'vat' => $vat );//SEP25
		//return array('line_total' => $linetotal, 'tax_total' => $taxtotal, 'item_total' => $itemtotal);	
	}
	
	protected function updateService($attributes)
	{
		$line_total = $tax_total = $item_total = 0;
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		if($discount > 0)
			$lineTotal = $this->calculateTotalAmount($attributes);
		
		foreach($attributes['lbitem_id'] as $key => $value) { 
					
			if($attributes['lborder_item_id'][$key]!='') {
				
				$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['lbtax_code'][$key];
				$linetotal = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
				
				if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
					
					$tax        = 0;
					$itemtotal = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);// - $attributes['line_discount'][$key];
					$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
					
				} else if($attributes['lbtax_include'][$key]==1){
					
					$ln_total   = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
					$lbtax_total  = $ln_total *  $attributes['lbline_vat'][$key] / (100 +  $attributes['lbline_vat'][$key]);
					$itemtotal = $ln_total - $lbtax_total;
					
				} else {
					
					$tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
					$itemtotal = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
					$lbtax_total  = round($tax * $attributes['lbquantity'][$key], 2);
				}
				
				$type = 'tax_exclude';
				if($attributes['lbtax_include'][$key]==1 ) {
					$vatPlus = 100 + $attributes['lbline_vat'][$key];
					$total = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
					$type = 'tax_include';
				} else {
					$vatPlus = 100;
					$total = $attributes['lbline_total'][$key];
					$type = 'tax_exclude';
				}
				
				if($discount > 0) {
					$discountAmt = round( (($total / $lineTotal) * $discount),2 );
					$amountTotal = $total - $discountAmt;
					$vatLine = round( (($amountTotal * $attributes['lbline_vat'][$key]) / $vatPlus),2 );
					$lbtax_total = $vatLine; 
				}
					
				$salesOrderItem = SalesOrderItem::find($attributes['lborder_item_id'][$key]);
				$oldqty = $salesOrderItem->quantity;
				$items['item_name'] = $attributes['lbitem_name'][$key];
				$items['item_id'] = $value;
				$items['unit_id'] = $attributes['lbunit_id'][$key];
				$items['quantity'] = $attributes['lbquantity'][$key];
				$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['lbcost'][$key]*$attributes['currency_rate']:$attributes['lbcost'][$key];
				$items['vat']		 = $attributes['lbline_vat'][$key];
				$items['vat_amount'] = $lbtax_total;
				$items['discount'] = $attributes['lbline_discount'][$key];
				$items['line_total'] = $linetotal;
				$items['item_total'] = $itemtotal;
				$items['tax_code'] 	= $attributes['lbtax_code'][$key];
				$items['tax_include'] = $attributes['lbtax_include'][$key];//CHG
				$exi_quantity = $salesOrderItem->quantity;
				$salesOrderItem->update($items);
				
				$tax_total += $lbtax_total;
				$line_total += $linetotal;
				$item_total += $itemtotal;
				
			} else { //add new service
				
				$salesOrderItem = new SalesOrderItem();
				
				$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['lbtax_code'][$key];
				$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);		
				if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
					$tax        = 0;
					$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);// - $attributes['line_discount'][$key];
					$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
					
				} else if($attributes['lbtax_include'][$key]==1){
					
					$ln_total   = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
					$lbtax_total  = $ln_total *  $attributes['lbline_vat'][$key] / (100 +  $attributes['lbline_vat'][$key]);
					$lbitem_total = $ln_total - $lbtax_total;
					
				} else {
					$tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
					$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
					$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
					$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
				}
				
				$type = 'tax_exclude';
				if($attributes['lbtax_include'][$key]==1 ) {
					$vatPlus = 100 + $attributes['lbline_vat'][$key];
					$total = $attributes['lbcost'][$key] * $attributes['lbquantity'][$key];
					$type = 'tax_include';
				} else {
					$vatPlus = 100;
					$total = $attributes['lbline_total'][$key];
					$type = 'tax_exclude';
				}
				
				if($discount > 0) {
					$discountAmt = round( (($total / $lineTotal) * $discount),2 );
					$amountTotal = $total - $discountAmt;
					$vatLine = round( (($amountTotal * $attributes['lbline_vat'][$key]) / $vatPlus),2 );
					$lbtax_total = $vatLine; 
				}
				
				$salesOrderItem->sales_order_id = $this->sales_order->id;
				$salesOrderItem->item_id    		= $value;
				$salesOrderItem->item_name  		= $attributes['lbitem_name'][$key];
				$salesOrderItem->unit_id 			= $attributes['lbunit_id'][$key];
				$salesOrderItem->quantity   		= $attributes['lbquantity'][$key];
				$salesOrderItem->unit_price 		= (isset($attributes['is_fc']))?$attributes['lbcost'][$key]*$attributes['currency_rate']:$attributes['lbcost'][$key];
				$salesOrderItem->vat		    	= $attributes['lbline_vat'][$key];
				$salesOrderItem->vat_amount 		= $lbtax_total;
				$salesOrderItem->discount   		= $attributes['lbline_discount'][$key];
				$salesOrderItem->line_total 		= $lbline_total;
				$salesOrderItem->tax_code 		= $attributes['lbtax_code'][$key];
				$salesOrderItem->tax_include 		= $attributes['lbtax_include'][$key];
				$salesOrderItem->item_total 		= $lbitem_total;
				$salesOrderItem->item_type = 1;
				$salesOrderItem->status = 1;
				
				$itemObj = $this->sales_order->salesOrderItem()->save($salesOrderItem);
				
				$line_total += $lbline_total;
				$tax_total += $lbtax_total;
				$item_total += $lbitem_total;
			}
			$vat = $attributes['lbline_vat'][$key]; //SEP25
		}
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'item_total' => $item_total, 'type' => $type, 'vat' => $vat );
		//return array('line_total' => $line_total, 'tax_total' => $tax_total, 'item_total' => $item_total);	
	}
	
	
	public function create($attributes)
	{ //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			DB::beginTransaction();
			try {
                 //$attributes['salesman_id'] =(isset($attributes['salesman_id']))?implode(',', $attributes['salesman_id']):'';
				 $attributes['salesman_id'] = isset($attributes['salesman_id'])
					? (is_array($attributes['salesman_id'])
						? implode(',', $attributes['salesman_id'])
						: $attributes['salesman_id'])
					: '';

				 //VOUCHER NO LOGIC.....................
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

				 // 2️⃣ Get the highest numeric part from voucher_master
				$qry = DB::table('sales_order')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
				if($dept > 0)	
					$qry->where('department_id', $dept);

				$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('SO', $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
				try {
						
						if($this->setInputValue($attributes)) {
							$this->sales_order->status 	   = 1;
							$this->sales_order->created_at = date('Y-m-d H:i:s');
							$this->sales_order->created_by = 1;
							$this->sales_order->save();
							$saved = true;
							
							//check workshop version active...
							if($this->module->is_active==1 && $this->sales_order->id) {
								$this->setJobdetails($attributes);
							}
						}
						
						
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false || strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

							// 2️⃣ Get the highest numeric part from voucher_master
							$qry = DB::table('sales_order')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
							if($dept > 0)	
								$qry->where('department_id', $dept);

							$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
							
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('SO', $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; // rethrow if different DB error
						}
					}
				}

				
				
				$line_total = 0; $tax_total = 0; $total = $item_total = 0; $taxtype = '';
				$discount = (isset($attributes['discount']))?$attributes['discount']:0;
				//sales order items insert
				if($this->sales_order->id && isset($attributes['item_id']) && !empty( array_filter($attributes['item_id']))) {
										
					//calculate total amount....
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
					
					foreach($attributes['item_id'] as $key => $value){ 
						$salesOrderItem = new SalesOrderItem();
						$vat = isset($attributes['line_vat'][$key])?$attributes['line_vat'][$key]:'';
						$arrResult 		= $this->setItemInputValue($attributes, $salesOrderItem, $key, $value,$total);
						//if($arrResult['line_total']) {
							$line_total			   += $arrResult['line_total'];
							$tax_total      	   += $arrResult['tax_total'];
							$taxtype				  = $arrResult['type'];
							$item_total				 += $arrResult['item_total'];
							
							$salesOrderItem->status = 1;
							$itemObj = $this->sales_order->salesOrderItem()->save($salesOrderItem);
							
						$zero = DB::table('sales_invoice_item')->where('id', $itemObj->id)->where('unit_id',0)->first();
					    if($zero && $zero->item_id != 0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('sales_invoice_item')->where('id', $itemObj->id)->update(['unit_id' => $uid->unit_id]);
						}
						
							
							$this->setTransferStatusItem($attributes, $key);
							
							//update service item....
							if($this->matservice->is_active==1) {
								$this->setTransferStatusService($attributes, $key);
							}
							
							//item description section....
							/*if(isset($attributes['itemdesc'][$key])) {
								foreach($attributes['itemdesc'][$key] as $descrow) {
									//if($descrow != '') {
										$itemDescription = new ItemDescription();
										$itemDescription->invoice_type = 'SO';
										$itemDescription->item_detail_id = $itemObj->id;
										$itemDescription->description = $descrow;
										$itemDescription->status = 1;
										$itemDescription->save();
									//}
								}
							}*/
						//}
					}
				  }
				  
					//Check material service module..........
					if($this->matservice->is_active==1 && isset($attributes['lbitem_id'])) {
						
						$arrResult = $this->addService($attributes);
						$line_total			     += $arrResult['line_total'];
						$tax_total      	     += $arrResult['tax_total'];
						$item_total				 += $arrResult['item_total'];
						$taxtype				  = $arrResult['type'];//SEP25
						$vat					  = $arrResult['vat'];//SEP25
					}
					
					/*CHG*/
					$subtotal = (int)$line_total - (int)$discount;
					if($taxtype=='tax_include' && $attributes['discount'] == 0) {
					  
					  $net_amount = $subtotal;
					  $tax_total = ($subtotal * $vat) / (100 + $vat);
					  $subtotal = $subtotal - $tax_total;
					  
					} elseif($taxtype=='tax_include' && $attributes['discount'] > 0) { 
					
					   $tax_total = ($subtotal * $vat) / (100 + $vat);
					   $net_amount = $subtotal - $tax_total;
					} else 
						$net_amount = $subtotal + $tax_total;
					/*CHG*/
					
					//VAT calculate from subtotal...
					$net_total_pay = '';
					if(isset($attributes['vatfrm_subtotal'])) {
						
						$tax_total = $attributes['vat'];
						$less_amount = (isset($attributes['less_amount']))?$attributes['less_amount']:0;
						$less_amount2 = (isset($attributes['less_amount2']))?$attributes['less_amount2']:0;
						$less_amount3 = (isset($attributes['less_amount3']))?$attributes['less_amount3']:0;
						$line_total = $attributes['total']; //CHNG SEP 17
						$subtotal = $line_total - $less_amount - $less_amount2 - $less_amount3;
						$net_amount = $net_total_pay = $attributes['net_amount']; //CHNG SEP 17
					}
					
					if( isset($attributes['is_fc']) ) {
						$total_fc 	   = $line_total / $attributes['currency_rate'];
						$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
						$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
						$tax_fc 	   = $tax_total / $attributes['currency_rate'];
						$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
						$subtotal_fc	   = $subtotal / $attributes['currency_rate'];
					} else {
						$total_fc = $discount_fc = $tax_fc = $net_amount_fc = $vat_fc = $subtotal_fc = 0;
					}
					
					if(isset($attributes['jobtype']) && $attributes['jobtype']==1) {
						$narr = explode('/',$attributes['voucher_no']);
						$jbno = $narr[1];
					}

					

					//update discount, total amount
					DB::table('sales_order')
								->where('id', $this->sales_order->id)
								->update([//'voucher_no' => $attributes['voucher_no'],
										   'total'    	  => $line_total,
										  'discount' 	  => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat' 	 	  => $tax_total, //$attributes['vat']
										  'vat_amount'	  => $tax_total,
										  'is_rental' => (isset($attributes['is_rental']))?$attributes['is_rental']:'', 
										  'net_total'	  => $net_amount,
										  'total_fc' 	  => $total_fc,
										  'discount_fc'   => $discount_fc,
										  'vat_fc' 		  => $vat_fc,
										  'vat_amount_fc' => $tax_fc,
										  'net_total_fc'  => $net_amount_fc,
										  'subtotal'	  => $subtotal,
										  'subtotal_fc'	  => $subtotal_fc,
										  'net_total_pay' => $net_total_pay,
										  'footer_text'	  => (isset($attributes['footer']))?$attributes['footer']:''
										  ]); //CHNG SEP 17
				//}
				
				if(isset($attributes['tempid'])) {
					foreach($attributes['tempid'] as $key => $row) {
						DB::table('crm_info')->insert(['temp_id' => $row, 'textval' => $attributes['crmtext'][$key], 'doc_id' => $this->sales_order->id, 'textval2' => (isset($attributes['crmtext2'][$key]))?$attributes['crmtext2'][$key]:'']);
					}
				}
				
				if($this->sales_order->id && isset($attributes['packageid'])) { 
				
					foreach($attributes['packageid'] as $pkg) {
						DB::table('joborder_pkgs')
									->insert(['job_order_id' => $this->sales_order->id, 
											  'package_id'	=> $pkg
											]);
					}
				}
				
				/* if($this->sales_order->id && isset($attributes['photo_name'])) {
					$photos = explode(',',$attributes['photo_name']);
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('job_photos')->insert(['job_order_id' => $this->sales_order->id, 'photo' => $photo]);
					}
					
				} */
				
				if($this->sales_order->id && isset($attributes['photo_name'])) {
					
					if($attributes['photo_name']!='') {
						foreach($attributes['photo_name'] as $key => $val) {
						    
							if($val!='') {
								DB::table('job_photos')
										->insert(['job_order_id' => $this->sales_order->id, 
												  'photo' => $val,
												  'description'	=> $attributes['imgdesc'][$key]
												]);
							}
						}
					}
				}
				
				//sales order info insert
				if($this->sales_order->id && isset($attributes['title']) && !empty( array_filter($attributes['title'])) ) {
					foreach($attributes['title'] as $key => $value) {
						$salesOrderInfo = new SalesOrderInfo();
						if($this->setInfoInputValue($attributes, $salesOrderInfo, $key, $value)) {
							$salesOrderInfo->status = 1;
							$this->sales_order->salesOrderInfo()->save($salesOrderInfo);
						}
					}
				}
				
				$this->setTransferStatusQuote($attributes);
				
				
				DB::commit();
				return true;
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
				return false;
			}
				
		}
		//throw new ValidationException('sales_order validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->sales_order = $this->find($id);
		$line_total = $tax_total = $line_total_new = $tax_total_new = $item_total = $total = 0;
		//echo '<pre>';print_r($attributes);exit;
		DB::beginTransaction();
		try {
			
			//check workshop version active...
			if($this->module->is_active==1 && $this->sales_order->id) {
				$this->setJobdetailsUpdate($attributes);
			}
			//echo '<pre>';print_r($attributes);exit;
			$discount = (isset($attributes['discount']))?$attributes['discount']:0; $taxtype = '';
			$lineTotal = $this->calculateTotalAmount($attributes); 
			//$ar = array_filter($attributes['item_id']);print_r($ar);
			//echo (empty($ar))?'e':'n';
			//exit;
			//if(count($ar) === 0) { // && !empty(array_filter($attributes['item_code']))) {// &&  && isset($attributes['item_id'])
				//echo 'hi';//exit;
			//} else {
			//if(count($ar) != 0) {
			if($this->sales_order->id && isset($attributes['item_id']) && !empty(array_filter($attributes['item_id']))) {
				//echo '<pre>';print_r($attributes['item_id']);exit;
				foreach($attributes['item_id'] as $key => $value) { 
				    //if($value!=''){ 
					$orderitems =  (isset($attributes['order_item_id'][$key]))?$attributes['order_item_id'][$key]:'';
					if($attributes['order_item_id'][$key]!='') {
						$deskey = $attributes['order_item_id'][$key];
						$tax_code = (isset($attributes['is_export']))?"ZR":(isset($attributes['tax_code'][$key])?$attributes['tax_code'][$key]:'');
						if( isset($attributes['is_fc']) ) {
								$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
								$itemtotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
								$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
								
							} else {
								
								$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
								
								if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
									
									$tax        = 0;
									$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
									
								} else if(isset($attributes['tax_include'][$key]) && $attributes['tax_include'][$key]==1){
									
									$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
									$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
									$itemtotal = $ln_total - $taxtotal;
									
								} else {
									if(isset($attributes['line_vat'][$key])) {
										$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
										$itemtotal = (int)($attributes['cost'][$key] * (int)$attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
									} else {
										$tax = $itemtotal = 0;
									}
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								}
							}
							
							//********DISCOUNT Calculation.............
							$discount = (isset($attributes['discount']))?$attributes['discount']:0;
							$taxtype = 'tax_exclude';
								
							if(isset($attributes['tax_include'][$key]) && $attributes['tax_include'][$key]==1 ) {
								$vatPlus = 100 + $attributes['line_vat'][$key];
								$total = $attributes['cost'][$key] * $attributes['quantity'][$key];
								$taxtype = 'tax_include';
							} else {
								$vatPlus = 100;
								$total = isset($attributes['line_total'][$key])?$attributes['line_total'][$key]:0;
								$taxtype = 'tax_exclude';
							}
							
							if($discount > 0) {
								$discountAmt = round( (($total / $lineTotal) * $discount),2 );
								$amountTotal = $total - $discountAmt;
								$vatLine = round( (($amountTotal * $attributes['line_vat'][$key]) / $vatPlus),2 );
								$taxtotal = $vatLine; 
							} 
							
							$tax_total += $taxtotal;
							$line_total += $linetotal;
							$item_total += $itemtotal;
							
							$vat = isset($attributes['line_vat'][$key])?$attributes['line_vat'][$key]:0;
							// $is_rental = (isset($attributes['is_rental']))?$attributes['is_rental']:''; 
							$salesOrderItem = SalesOrderItem::find($attributes['order_item_id'][$key]);
							$items['item_name'] = $attributes['item_name'][$key];
							
							$items['item_id'] = $attributes['item_id'][$key];//$value;
							$items['unit_id'] = $attributes['unit_id'][$key];
							$items['quantity'] = $attributes['quantity'][$key];
							$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
							$items['vat']		 = isset($attributes['line_vat'][$key])?$attributes['line_vat'][$key]:0;
							$items['vat_amount'] = $taxtotal;
							$items['discount'] = isset($attributes['line_discount'][$key])?$attributes['line_discount'][$key]:0;
							$items['line_total'] = $linetotal;
							$items['item_total'] = $itemtotal; //CHG
							$items['tax_code'] 	= $tax_code;
							 // $items['is_rental'] 	= $is_rental;
							$items['tax_include'] = isset($attributes['tax_include'][$key])?$attributes['tax_include'][$key]:0;
							
							//CHNG SEP 17
							$pay_pcntg = 100; $pay_amount = $line_total; $pay_pcntg_desc = '';
							if(isset($attributes['per'][$key]) && $attributes['per'][$key]!='' && $attributes['per'][$key] > 0) {
								$pay_pcntg = $attributes['per'][$key];
								$pay_amount = ($linetotal * $pay_pcntg) / 100;
								$pay_pcntg_desc = $attributes['perdesc'][$key];
							} else {
								$items['pay_pcntg'] = $pay_pcntg;
								$items['pay_amount'] = $pay_amount;
								$items['pay_pcntg_desc'] = isset($attributes['sojob_id'][$key])?$attributes['sojob_id'][$key]:'';
							}
							
							
							$salesOrderItem->update($items);
					      $zero = DB::table('sales_order_item')->where('id', $attributes['order_item_id'][$key])->where('unit_id',0)->first();
						if($zero && $zero->item_id !=0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('sales_order_item')->where('id', $attributes['order_item_id'][$key])->update(['unit_id' => $uid->unit_id]);
						}
						
							//description update...
							/*if(isset($attributes['desc_id'])) {
								if(array_key_exists($deskey, $attributes['desc_id'])) {
									foreach($attributes['desc_id'][$deskey] as $k => $v) {
										if($v!='') {
											$itemDescription = ItemDescription::find($v);
											$desc['description'] = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->update($desc);
										} else {
											//new entry.........
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'SO';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->status = 1;
											$itemDescription->save();
											
										}
									}
								}
							} else {
								//new entry description.........
								if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'SO';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}
							}*/
							
						} else { //new entry...
							$item_total_new = $tax_total_new = $item_total_new = 0;
							if($discount > 0) 
								$total = $this->calculateTotalAmount($attributes);
							
							$vat = $attributes['line_vat'][$key];
							$salesOrderItem = new SalesOrderItem();
							$arrResult 		= $this->setItemInputValue($attributes, $salesOrderItem, $key, $value, $total);
							//if($arrResult['line_total']) {
								$line_total_new 		 += $arrResult['line_total'];
								$tax_total_new      	 += $arrResult['tax_total'];
								$item_total_new			 += $arrResult['item_total']; //CHG
								
								$line_total			     += $arrResult['line_total'];
								$tax_total      	     += $arrResult['tax_total'];
								$item_total 			 += $arrResult['item_total'];
								$taxtype				  = $arrResult['type'];
								
								$salesOrderItem->status = 1;
								$itemObj = $this->sales_order->salesOrderItem()->save($salesOrderItem);
								
					$zero = DB::table('sales_order_item')->where('id', $itemObj->id)->where('unit_id',0)->first();
					    if($zero && $zero->item_id != 0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('sales_order_item')->where('id', $itemObj->id)->update(['unit_id' => $uid->unit_id]);
						}
								
								//new entry description.........
								/*if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'SO';
											$itemDescription->item_detail_id = $itemObj->id;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}*/
							//}
						}
						
					} 




				}
				
				//check workshop version active...
				if($this->matservice->is_active==1 && $this->sales_order->id && isset($attributes['lbitem_id'])) {
					$arrResult = $this->updateService($attributes);
					
					$line_total	+= $arrResult['line_total'];
					$tax_total  += $arrResult['tax_total'];
					$item_total += $arrResult['item_total'];
					$taxtype				  = $arrResult['type'];//SEP25
					$vat					  = $arrResult['vat'];//SEP25
					
					//manage removed service...
					if($attributes['lbremove_item']!='')
					{
						$arrids = explode(',', $attributes['lbremove_item']);
						$remline_total = $remtax_total = 0;
						foreach($arrids as $row) {
							DB::table('sales_order_item')->where('id', $row)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
						}
					}
				}
			
				if(isset($attributes['photo_id'])) {
					
					foreach($attributes['photo_id'] as $key => $val) {
						
						//UPDATE...
						if($val!='') {
							DB::table('job_photos')
								->where('id', $val)
								->update(['photo' =>  $attributes['photo_name'][$key],
										  'description'	=> $attributes['imgdesc'][$key]
										]);
										
						} else { 
							//ADD NEW..
							DB::table('job_photos')
								->insert(['job_order_id' => $this->sales_order->id, 
										  'photo' => $attributes['photo_name'][$key],
										  'description'	=> $attributes['imgdesc'][$key]
										]);
						}
					}
				}
				
				else{
				    if(isset($attributes['photo_name']) && $attributes['photo_name']!='') {
						foreach($attributes['photo_name'] as $key => $val) {
						    
							if($val!='') {
								DB::table('job_photos')
										->insert(['job_order_id' => $this->sales_order->id, 
												  'photo' => $val,
												  'description'	=> $attributes['imgdesc'][$key]
												]);
							}
						}
					}
				    
				    
				    
				}
			
				
				//Remove photos
				if(isset($attributes['rem_photo_id']) && $attributes['rem_photo_id']!='') {
					$rem_photos = explode(',',$attributes['rem_photo_id']);
					foreach($rem_photos as $id) {
						$rec = DB::table('job_photos')->find($id);
						DB::table('job_photos')->where('id', $id)->delete();
						
						if($rec->photo!='') {			
							$fPath = public_path() . $this->imgDir.'/'.$rec->photo;
							File::delete($fPath);
						}
					}
				}
			
                
				//DEC22   sales order info insert
				if(isset($attributes['infoid'])) {
				foreach($attributes['infoid'] as $key => $value) {
					if($value=='') {
						$salesOrderInfo= new SalesOrderInfo();
						if($this->setInfoInputValue($attributes, $salesOrderInfo, $key, $value)) {
							$salesOrderInfo->status = 1;
							$this->sales_order->salesOrderInfo()->save($salesOrderInfo);
						}
					} else {
						DB::table('sales_order_info')->where('id',$value)->update(['title' => $attributes['title'][$key], 'description' => $attributes['desc'][$key]]);
					}
				}
				}
				//manage removed items...
				
				if($attributes['remove_item']!='')
				{
					$arrids = explode(',', $attributes['remove_item']);
					$remline_total = $remtax_total = 0;
					foreach($arrids as $row) {
						DB::table('sales_order_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					}
				}
				$this->sales_order->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));	
				//echo '<pre>';print_r($attributes);exit;
				//$attributes['salesman_id'] =(isset($attributes['salesman_id']))?implode(',', $attributes['salesman_id']):'';
				$attributes['salesman_id'] = isset($attributes['salesman_id'])
					? (is_array($attributes['salesman_id'])
						? implode(',', $attributes['salesman_id'])
						: $attributes['salesman_id'])
					: '';

				$this->sales_order->fill($attributes)->save();
				

				if($this->setInputValue($attributes)) {
					$this->sales_order->modify_at = date('Y-m-d H:i:s');
					$this->sales_order->modify_by = Auth::User()->id;
					$this->sales_order->fill($attributes)->save();
				}
			
				/*CHG*/
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
				/*CHG*/
				
				//VAT calculate from subtotal...
				$net_total_pay = '';
				if(isset($attributes['vatfrm_subtotal'])) {
					//$subtotal = $line_total - $attributes['less_amount'];
					$tax_total = $attributes['vat'];//($subtotal * $attributes['vatfrm_subtotal']) / 100;
					
					$less_amount = (isset($attributes['less_amount']))?$attributes['less_amount']:0;
					$less_amount2 = (isset($attributes['less_amount2']))?$attributes['less_amount2']:0;
					$less_amount3 = (isset($attributes['less_amount3']))?$attributes['less_amount3']:0;
					$line_total = $attributes['total']; //CHNG SEP 17
					$subtotal = $line_total - $less_amount - $less_amount2 - $less_amount3;
						
					$net_amount = $net_total_pay = $attributes['net_amount']; //CHNG SEP 17
				}
				
				if( isset($attributes['is_fc']) ) {
					$total_fc 	   = $line_total / $attributes['currency_rate'];
					$discount_fc   = $attributes['discount'] / $attributes['currency_rate'];
					$vat_fc 	   = $attributes['vat_fc'] / $attributes['currency_rate'];
					$tax_fc 	   = $tax_total / $attributes['currency_rate'];
					$net_amount_fc = $total_fc - $discount_fc + $tax_fc;
					$subtotal_fc	   = $subtotal / $attributes['currency_rate']; //CHG
				} else {
					$total_fc = 0; $discount_fc = 0; $tax_fc = 0; $net_amount_fc = 0; $vat_fc = $subtotal_fc = 0;
				}
				
				//echo 'n: '.$net_amount;exit;
				//update discount, total amount
				DB::table('sales_order')
							->where('id', $this->sales_order->id)
							->update(['total'    	  => $line_total,
									  'discount' 	  => isset($attributes['discount'])?$attributes['discount']:0,
									  'is_rental' => (isset($attributes['is_rental']))?$attributes['is_rental']:'',
									  'vat_amount'	  => $tax_total,
									  'net_total'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'vat_amount_fc' => $tax_fc,
									  
									  'net_total_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal,
									  'subtotal_fc'	  => $subtotal_fc,
									  'net_total_pay' => $net_total_pay,
									  'footer_text'	  => isset($attributes['footer'])?$attributes['footer']:'',
									  'doc_status' 	  => (isset($attributes['doc_status']))?$attributes['doc_status']:'',
									   'metre_in'	  => isset($attributes['metre_in'])?$attributes['metre_in']:'',
									  'metre_out'	  => isset($attributes['metre_out'])?$attributes['metre_out']:'',
									  'comment'		  => (isset($attributes['comment']))?$attributes['comment']:((isset($attributes['comment_hd']))?$attributes['comment_hd']:'')
									  ]);
									  
				if(isset($attributes['tempid'])) {
					DB::table('crm_info')->where('doc_id',$this->sales_order->id)->delete();
					foreach($attributes['tempid'] as $key => $row) {
						DB::table('crm_info')->insert(['temp_id' => $row, 'textval' => $attributes['crmtext'][$key],'doc_id' => $this->sales_order->id, 'textval2' => isset($attributes['crmtext2'][$key])?$attributes['crmtext2'][$key]:'']);
					}
				}
				
			DB::commit(); 
			return true;
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			return false;
		}
	}
	
	public function delete($id)
	{
		$this->sales_order = $this->sales_order->find($id);
		
		//Quotatio unlock....
		DB::table('quotation_sales')->where('id',$this->sales_order->quotation_id)->update(['is_transfer' => 0, 'is_editable' => 0]);
			
		$this->sales_order->delete();
	}
	
	public function quotationSalesList()
	{
		$query = $this->sales_order->where('sales_order.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
					->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->orderBY('sales_order.id', 'DESC')
					->get();
	}
	
		
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->sales_order->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->sales_order->where('reference_no',$refno)->count();
	}
		
	public function getCustomerOrder($customer_id, $type=null)
	{
		 $query = $this->sales_order
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
						->leftJoin('jobmaster AS J', function($join) {
							$join->on('J.id','=','sales_order.job_id');
						} )
					 ->where('sales_order.status', 1)
					 ->where('sales_order.customer_id', $customer_id)
					 ->where('sales_order.is_transfer', 0);
					
					if($type) {
						$query->where(function($qry) {
							$qry->where('sales_order.salesman_id', 0)->where('sales_order.doc_status', 0)
								->orWhere('sales_order.salesman_id','>',0)->where('sales_order.doc_status', 1);
						});
					}
					 
		return	$query->select('sales_order.id','sales_order.voucher_no','sales_order.voucher_date','sales_order.net_total','V.reg_no','V.name AS vehicle',
							   'sales_order.prefix','J.code')->get();
		
	}
	
	public function findOrderData($id)
	{
		$query = $this->sales_order->where('sales_order.id', $id)->where('sales_order.is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','sales_order.footer_id');
					})
					->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
					->leftjoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','sales_order.job_id');
						})	
					->leftJoin('salesman AS S', function($join) {
							$join->on('S.id','=','sales_order.salesman_id');
						} )
					->select('sales_order.*','am.master_name AS customer','f.title AS footer','V.reg_no','V.name AS vehicle',
							 'S.name AS salesman','V.issue_plate','V.code_plate','V.make','V.model','V.chasis_no','J.code')
					->orderBY('sales_order.id', 'ASC')
					->first();
	}
	

	public function getOrderReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->sales_order->where('sales_order.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','sales_order.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','sales_order.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','sales_order.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->sales_order->where('sales_order.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','sales_order.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','sales_order.job_id');
								   })
								   ->select('AM.master_name AS supplier','sales_order.*','JM.name AS job')
								   ->orderBY('sales_order.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getOrder($attributes)
	{
		$order = $this->sales_order->where('sales_order.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','sales_order.customer_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','sales_order.currency_id');
								   })
								   ->leftJoin('jobmaster AS J', function($join) {
									   $join->on('J.id','=','sales_order.job_id');
								   })
								    ->leftJoin('vehicle AS V', function($join) {
									   $join->on('V.id','=','sales_order.vehicle_id');
								   })
								    ->leftJoin('salesman AS SL', function($join) {
									   $join->on('SL.id','=','sales_order.salesman_id');
								   })
								   ->leftJoin('terms AS TR', function($join) {
									   $join->on('TR.id','=','sales_order.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','sales_order.*','AM.address','AM.city','AM.state','AM.pin','AM.vat_no','AM.phone','C.name AS currency',
								   'V.name AS vehicle','V.reg_no','V.make','V.color','V.issue_plate','V.code_plate','SL.name AS salesman','TR.description AS terms')
								   ->orderBY('sales_order.id', 'ASC')
								   ->first();
								   
		$items = $this->sales_order->where('sales_order.id', $attributes['document_id'])
								   ->join('sales_order_item AS PI', function($join) {
									   $join->on('PI.sales_order_id','=','sales_order.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','sales_order.id','IM.item_code','U.unit_name')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	public function findPOdata($id)
	{
		$query = $this->sales_order->where('sales_order.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->leftjoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','sales_order.job_id');
						})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','sales_order.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','sales_order.salesman_id');
					})
					->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','sales_order.vehicle_id');
					})
					->select('sales_order.*','am.master_name AS customer','f.title AS footer','S.name AS salesman','V.name AS vehicle','J.name','J.code',
							 'V.reg_no','V.model','V.make','V.issue_plate','V.code_plate','V.chasis_no','am.email','V.engine_no','V.color','V.year')
					->first();
	}
	public function findPOdataold($id)
	{
		$query = $this->sales_order->where('sales_order.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','sales_order.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','sales_order.salesman_id');
					})
					->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','sales_order.vehicle_id');
					})
					->select('sales_order.*','am.master_name AS customer','f.title AS footer','S.name AS salesman','V.name AS vehicle',
							 'V.reg_no','V.model','V.make','V.issue_plate','V.code_plate','am.email')
					->first();
	}
	
	
	public function getItems($id,$mod=null)
	{
		$query = $this->sales_order->where('sales_order.id',$id);
		
		$query->join('sales_order_item AS poi', function($join) {
							$join->on('poi.sales_order_id','=','sales_order.id');
						} )
					  ->leftjoin('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->leftjoin('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','poi.item_id');
					  })
					  ->leftjoin('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  })
					  ->where('poi.status',1);
					  
					  if($mod) {
						$val = ($mod=='ser')?2:1;
						$query->where('im.class_id',$val);
					  }
					  
		return $query->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty')
					  ->groupBy('poi.id')
					  ->orderBY('poi.id')
					  ->get();
	}
	
	public function getSOItems($id)
	{
		
		$query = $this->sales_order->whereIn('sales_order.id',$id);
		
		return $query->join('sales_order_item AS poi', function($join) {
							$join->on('poi.sales_order_id','=','sales_order.id');
						} )
					  ->leftjoin('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->leftjoin('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->leftjoin('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
						  $join->on('iu.unit_id','=','poi.unit_id');
					  })
					  ->where('poi.status',1)
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.cur_quantity')
					  ->whereIn('poi.is_transfer',[0,2])
					  ->where('poi.deleted_at', '0000-00-00 00:00:00')
					  ->orderBY('poi.id')
					  ->groupBy('poi.id')
					  ->get();
		
	}
	public function getSOdata($customer_id = null)
	{
		if($customer_id)
			$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_transfer',0)->where('sales_order.customer_id',$customer_id);
		else
			$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_transfer',0);
		
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->select('sales_order.*','am.master_name AS customer')
					->orderBY('sales_order.id', 'ASC')
					->get();
	}


	public function getVehicleExpiryInfo() {
		//	$parameter1 = DB::table('sales_order')->where('sales_order.is_editable','=',1)->get();
		//	$doc_warndays = $parameter1->next_due;
		//	echo '<pre>';print_r($doc_warndays);
		//	$todate = date('Y-m-d', strtotime($doc_warndays . "+10 days"));
		//	echo '<pre>';print_r($todate);exit;
		$fromdate = date('Y-m-d');
		//$endDate = Carbon::now()->addDays(30);
	//	$endDate = Carbon::now()->subdays(10);
	//	$endDate = Carbon::today()->addDays(7);
		//$endDate = date('Y-m-d', strtotime("+". ." 10 days"));
		//echo '<pre>';print_r($endDate);exit;
		//Carbon::today()->addDays(10);
		$parameter1 = DB::table('parameter1')->first();
		$doc_warndays = $parameter1->doc_warndays;
		
		$endDate = date('Y-m-d', strtotime("+".$doc_warndays." days"));
	//	->where('sales_order.is_editable','=',1)
	//->whereBetween('sales_order.next_due', array($fromdate,$endDate))
			$query = $this->sales_order	->where('sales_order.is_editable','=',1)
			->where('sales_order.next_due','!=',0);
			$doc_warnda =  $query->join('account_master AS am', function($join) {
								$join->on('am.id','=','sales_order.customer_id');
							} )->leftJoin('sales_invoice AS SI', function($join) {
								$join->on('SI.document_id','=','sales_order.id');
							} )
					
						
						->leftJoin('vehicle AS V',function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						})
						->select('sales_order.*','am.master_name AS customer','sales_order.next_due AS expiry_date','V.name AS vehicle',
								 'V.reg_no','V.model','V.make','V.issue_plate','V.code_plate','am.email')
						->get();

						//echo '<pre>';print_r($doc_warnda);exit;
						return $doc_warnda;
	
	}
	public function getPendingReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->sales_order->where('is_rental',0)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('sales_order.job_id', $attributes['job_id']);	
							
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'V.reg_no','V.issue_plate','V.code_plate','sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','J.code AS jobcode')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'jobwise':
				$query = $this->sales_order->where('is_rental',0)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('jobcode','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'customer_wise':
				$query = $this->sales_order->where('is_rental',0)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);	
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('master_name','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->sales_order->where('is_rental',0)->where('SOI.is_transfer','!=',1)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','sales_order.discount','J.code AS jobcode',
									  'SOI.quantity','SOI.balance_quantity','J.code AS jobcode','J.name AS jobname','SOI.unit_price','AM.master_name','sales_order.net_total','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();//->groupBy('sales_order.id')
								
				break;
				
			case 'detail':
				$query = $this->sales_order->where('is_rental',0)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SOI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						} 
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.voucher_date','sales_order.reference_no','IM.item_code','IM.description','S.name AS salesman','SOI.vat_amount AS unit_vat','J.code as jobcode',
									  'SOI.quantity','SOI.unit_price','SOI.line_total','J.code AS jobcode','J.name AS jobname','AM.master_name','sales_order.net_total','sales_order.discount',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();
				break;
				
			case 'detail_pending'||'qty_report':
				$query = $this->sales_order->where('is_rental',0)->where('QSI.is_transfer','!=',1)
								->join('sales_order_item AS QSI', function($join) {
									$join->on('QSI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.voucher_date','sales_order.reference_no','IM.item_code','IM.description','sales_order.total','sales_order.vat_amount','sales_order.discount','J.code AS jobcode',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','J.code AS jobcode','J.name AS jobname','QSI.line_total','AM.master_name','sales_order.net_total','S.name AS salesman',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();
				break;
		}
	}
	
public function getPendingReportJob($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->sales_order->where('is_rental',2)
								->leftjoin('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('sales_order.status',1)
								->where('SOI.status',1);
								
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('sales_order.job_id', $attributes['job_id']);	
							
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman','V.name as vehicle_name','V.chasis_no',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount','V.engine_no',
									  'V.reg_no','V.issue_plate','V.code_plate','sales_order.voucher_date','J.code AS jobcode','J.name AS jobname')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'jobwise':
				$query = $this->sales_order->where('is_rental',2)
								->leftjoin('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->where('SOI.status',1)
								->where('sales_order.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('jobcode','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'customer_wise':
				$query = $this->sales_order->where('is_rental',2)
								->leftjoin('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->where('SOI.status',1)
								->where('sales_order.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);	
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('master_name','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->sales_order->where('is_rental',2)
								->leftjoin('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('sales_order.is_transfer','!=',1)
								->where('SOI.status',1)
								->where('sales_order.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
							if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','sales_order.discount','J.code AS jobcode','V.name as vehicle_name','V.chasis_no',
									  'SOI.quantity','SOI.balance_quantity','J.code AS jobcode','J.name AS jobname','SOI.unit_price','AM.master_name','sales_order.net_total','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'V.reg_no','V.issue_plate','V.code_plate','J.name as jobname','V.engine_no')
								->get();//->groupBy('sales_order.id')
								
				break;
				
			case 'detail':
				$query = $this->sales_order->where('is_rental',2)
								->leftjoin('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftjoin('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SOI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1)
								->where('sales_order.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						} 
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.voucher_date','sales_order.reference_no','IM.item_code','IM.description','S.name AS salesman','SOI.vat_amount AS unit_vat','J.code as jobcode',
									  'SOI.quantity','SOI.unit_price','SOI.line_total','J.name AS jobname','AM.master_name','sales_order.net_total','sales_order.discount','V.name as vehicle_name','V.chasis_no',
									  'V.reg_no','V.issue_plate','V.code_plate','V.engine_no')
								->get();
				break;
				
			case 'detail_pending'||'qty_report':
				$query = $this->sales_order->where('is_rental',2)->where('QSI.is_transfer','!=',1)
								->leftjoin('sales_order_item AS QSI', function($join) {
									$join->on('QSI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftjoin('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('QSI.status',1)
								->where('sales_order.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('sales_order.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('sales_order.job_id', $attributes['job_id']);
						
				return $query->select('sales_order.voucher_no','sales_order.voucher_date','sales_order.reference_no','IM.item_code','IM.description','sales_order.total','sales_order.vat_amount','sales_order.discount','J.code AS jobcode',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','J.name AS jobname','QSI.line_total','AM.master_name','sales_order.net_total','S.name AS salesman',
									  'V.reg_no','V.issue_plate','V.code_plate','V.name as vehicle_name','V.chasis_no','V.engine_no')
								->get();
				break;
		}
	}
	


	public function getPendingReportrental($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								}) ;
								 //->where('SOI.status',1)->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);		
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'V.reg_no','V.issue_plate','V.code_plate','sales_order.voucher_date','J.code AS jobcode','J.name AS jobname')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'jobwise':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								//->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('jobcode','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'customer_wise':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								//->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('J.id', $attributes['job_id']);	
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','S.name AS salesman',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','sales_order.discount',
									  'sales_order.voucher_date','J.code AS jobcode','J.name AS jobname','sales_order.less_amount','sales_order.less_amount2','sales_order.less_amount3')
								->orderBY('master_name','ASC')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->sales_order->where('SOI.is_transfer','!=',1)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								//->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('jobmaster.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','sales_order.discount',
									  'SOI.quantity','SOI.balance_quantity','J.code AS jobcode','J.name AS jobname','SOI.unit_price','AM.master_name','sales_order.net_total','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();//->groupBy('sales_order.id')
								
				break;
				
			case 'detail':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SOI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('SOI.status',1);
								//->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						/* if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						} */
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('jobmaster.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','IM.item_code','IM.description','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'SOI.quantity','SOI.unit_price','SOI.line_total','J.code AS jobcode','J.name AS jobname','AM.master_name','sales_order.net_total','sales_order.discount',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();
				break;
				
			case 'detail_pending':
				$query = $this->sales_order->where('QSI.is_transfer','!=',1)
								->join('sales_order_item AS QSI', function($join) {
									$join->on('QSI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','sales_order.job_id');
								})
								->where('QSI.status',1);
								//->where('is_rental',1)
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('jobmaster.id', $attributes['job_id']);
				return $query->select('sales_order.voucher_no','sales_order.reference_no','IM.item_code','IM.description','sales_order.total','sales_order.vat_amount','sales_order.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','J.code AS jobcode','J.name AS jobname','QSI.line_total','AM.master_name','sales_order.net_total','S.name AS salesman',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();
				break;
		}
	}
	



	public function getItemDesc($id)
	{
		return DB::table('sales_order')
						->join('sales_order_item AS QSI', function($join) {
							$join->on('QSI.sales_order_id', '=', 'sales_order.id');
						})
						->join('item_description AS D', function($join) {
							$join->on('D.item_detail_id', '=', 'QSI.id');
						})
						->where('sales_order.id', $id)
						->where('D.invoice_type','SO')
						->where('QSI.status',1)
						->where('QSI.deleted_at','0000-00-00 00:00:00')
						->where('D.status',1)
						->where('D.deleted_at','0000-00-00 00:00:00')
						->select('D.*')
						->get();
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->sales_order->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->sales_order->where('voucher_no',$refno)->count();
	}
	
	public function getjobDescription($id)
	{
		return DB::table('joborder_details')->where('joborder_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
	}


	public function salesOrderListCountRent()
	{
		$query = $this->sales_order->where('sales_order.status',1);
		//->where('is_rental',1)
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->count();
	}

	public function salesOrderListrental($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} );
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	public function jobOrderListCount()
	{
		$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_rental',2);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->count();
	}
		public function jobOrderList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_rental',2)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} );
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('sales_order.reference_no', 'LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle','V.chasis_no')
							//	DB::raw('SUBSTRING_INDEX(sales_order.voucher_no, "/", 1) AS bin_name1'))
								//DB::raw('SUBSTRING_INDEX(sales_order.voucher_no, "/", -1) AS bin_name2'))
					->offset($start)
                    ->limit($limit)
					->orderBy($order,$dir);
					//->orderBy('bin_name1','DESC');
					//->orderBy('bin_name2','ASC');
					//->sortBy('sales_order.voucher_no');
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	
	public function salesOrderListCount()
	{
		$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_rental',0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
					->count();
	}
	
	public function salesOrderList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)->where('sales_order.is_rental',0)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} );
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('sales_order.reference_no', 'LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle',
								DB::raw('SUBSTRING_INDEX(sales_order.voucher_no, "/", 1) AS bin_name1'))
								//DB::raw('SUBSTRING_INDEX(sales_order.voucher_no, "/", -1) AS bin_name2'))
					->offset($start)
                    ->limit($limit)
					//->orderBy($order,$dir)
					->orderBy('bin_name1','DESC');
					//->orderBy('bin_name2','ASC');
					//->sortBy('sales_order.voucher_no');
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	public function salesOrderPendingList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
						->join('sales_order_item AS soi', function($join) {
							$join->on('soi.sales_order_id','=','sales_order.id');
						} )
						->where('sales_order.is_warning',0);
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('sales_order.reference_no', 'LIKE',"%{$search}%")
                    ->orWhere('am.master_name', 'LIKE',"%{$search}%")
                    ->orWhere('soi.item_id', $search);
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->groupBy('soi.sales_order_id')
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	
	public function salesOrderPendingListCom($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
						->join('sales_order_item AS soi', function($join) {
							$join->on('soi.sales_order_id','=','sales_order.id');
						} )
						->where('sales_order.is_warning',1);
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('sales_order.reference_no', 'LIKE',"%{$search}%")
                    ->orWhere('am.master_name', 'LIKE',"%{$search}%")
                    ->orWhere('soi.item_id', $search);
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->groupBy('soi.sales_order_id')
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	
	public function getPaymentCertificate($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'jobwise':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['jobmaster_id']!='') { 
							$query->where('sales_order.job_id', $attributes['jobmaster_id']);
						}
						 
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total',
									  'sales_order.voucher_date')
								->groupBy('sales_order.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->sales_order->where('SOI.is_transfer','!=',1)
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						}
						 
				return $query->select('sales_order.voucher_no','sales_order.reference_no','sales_order.total','sales_order.vat_amount','sales_order.discount',
									  'SOI.quantity','SOI.balance_quantity','SOI.unit_price','AM.master_name','sales_order.net_total','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();//->groupBy('sales_order.id')
								
				break;
				
			case 'detail':
				$query = $this->sales_order
								->join('sales_order_item AS SOI', function($join) {
									$join->on('SOI.sales_order_id','=','sales_order.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','sales_order.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SOI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','sales_order.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','sales_order.vehicle_id');
								})
								->where('SOI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}
						
						/* if($attributes['salesman']!='') { 
							$query->where('sales_order.salesman_id', $attributes['salesman']);
						} */
						
				return $query->select('sales_order.voucher_no','sales_order.reference_no','IM.item_code','IM.description','S.name AS salesman','SOI.vat_amount AS unit_vat',
									  'SOI.quantity','SOI.unit_price','SOI.line_total','AM.master_name','sales_order.net_total','sales_order.discount',
									  'V.reg_no','V.issue_plate','V.code_plate')
								->get();
				break;
		}
	}
	
	public function ajax_upload($file)
	{ 
		$photo = '';
		$fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		if($file) {
			$ext = $file->getClientOriginalExtension();
			if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
				$photo = rand(1, 999).$fname.'.'.$ext;
				$destinationPath = public_path() . $this->imgDir.'/'.$photo;
				$destinationPathThumb = public_path() . $this->imgDir.'/thumb_'.$photo;

				// resizing an uploaded file
				Image::make($file->getRealPath())->resize($this->width, $this->height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Image::make($file->getRealPath())->resize($this->thumbWidth, $this->thumbHeight, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = rand(1, 999).$fname.'.'.$ext;
				 $destinationPath = public_path() . $this->imgDir;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	
	public function TechnicianOrderList($type,$search=null)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftjoin('salesman AS S', 'S.id','=','sales_order.salesman_id')
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} );
				
				if(Session::has('technician_id')){
				    $query->where('sales_order.salesman_id', Session::get('technician_id'));
						 // ->where('sales_order.doc_status',0)
				}
				
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
							   ->orWhere('V.reg_no', 'LIKE',"%{$search}%");
				}
				
				if($type=='Assigned') {
					$query->where('sales_order.start_time','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Working') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Completed') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',0);
				}
				
				if($type=='Approved') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',1);
				}
				
			$result = $query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle','S.name AS salesman')->get();
				
			return $result;

	}
	
	public function TechnicianOrderListById($techid,$type)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftjoin('salesman AS S', 'S.id','=','sales_order.salesman_id')
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} );
				
				if(Session::has('technician_id')){
				    $query->where('sales_order.salesman_id', Session::get('technician_id'))
						  ->where('sales_order.doc_status',0);
				}
				
				if($techid && $techid!='all') {
					$query->where('sales_order.salesman_id',$techid);
				}
				
				if($type=='Assigned') {
					$query->where('sales_order.start_time','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Working') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Completed') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',0);
				}
				
				if($type=='Approved') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',1);
				}
				
			$result = $query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle','S.name AS salesman')->get();
				
			return $result;

	}
	
	public function TechnicianOrderListItems($type)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('sales_order_item AS IT', function($join) {
							$join->on('IT.sales_order_id','=','sales_order.id');
						})
						->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','IT.item_id');
						})
						->join('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						});
						
				if(Session::has('technician_id')){
				    $query->where('sales_order.salesman_id', Session::get('technician_id'));
						 // ->where('sales_order.doc_status',0);
				}
				
								
				if($type=='Assigned') {
					$query->where('sales_order.start_time','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Working') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Completed') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',0);
				}
				
				if($type=='Approved') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',1);
				}
				
			$result = $query->select('sales_order.id','IM.description','IT.quantity')->get();
				
			return $result;

	}
	
	
	
	public function VehicleDetails($type)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						});
						
				if(Session::has('technician_id')){
				    $query->where('sales_order.salesman_id', Session::get('technician_id'))
						  ->where('sales_order.doc_status',0);
				}
				
				if($type=='Assigned') {
					$query->where('sales_order.start_time','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Working') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Completed') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',0);
				}
				
				if($type=='Approved') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',1);
				}
				
			$result = $query->select('sales_order.id','V.reg_no','V.issue_plate','V.code_plate','V.make','V.model')->get();
				
			return $result;

	}
	
	public function getJobImages($type)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('job_photos AS P', function($join) {
							$join->on('P.job_order_id','=','sales_order.id');
						});
						
				if(Session::has('technician_id')){
				    $query->where('sales_order.salesman_id', Session::get('technician_id'))
						  ->where('sales_order.doc_status',0);
				}
				
				if($type=='Assigned') {
					$query->where('sales_order.start_time','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Working') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','');
				}
				
				if($type=='Completed') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',0);
				}
				
				if($type=='Approved') {
					$query->where('sales_order.start_time','!=','')
							->where('sales_order.end_time','!=','')
							->where('sales_order.doc_status',1);
				}
				
			$result = $query->select('sales_order.id','P.photo','P.description')->get();
				
			return $result;

	}
	
	
	public function TechnicianOrderReport($type,$search=null)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
						->leftJoin('salesman AS S', function($join) {
							$join->on('S.id','=','sales_order.salesman_id');
						} );
				
				if(Session::has('technician_id')){
					
				    $query->where('sales_order.salesman_id', Session::get('technician_id'))
						  ->where('sales_order.doc_status',0);
						  
					if(Input::get('search_type')=='Pending') {
						$query->where('sales_order.start_time','')
								->where('sales_order.end_time','');
					}
					
					if(Input::get('search_type')=='Completed') {
						$query->where('sales_order.start_time','!=','')
								->where('sales_order.end_time','!=','')
								->where('sales_order.doc_status',0);
					}
					
				} else {
					
					if(Input::get('search_type')=='Pending') {
						$query->where('sales_order.start_time','!=','')
								->where('sales_order.end_time','!=','')
								->where('sales_order.doc_status',0);
					}
					
					if(Input::get('search_type')=='Completed') {
						$query->where('sales_order.start_time','!=','')
								->where('sales_order.end_time','!=','')
								->where('sales_order.doc_status',1);
					}
				}
				
			$result = $query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle','S.name AS salesman')->get();
				
			return $result;

	}
	
	
	public function salesOrderListDelivery($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->sales_order->where('sales_order.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','sales_order.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','sales_order.vehicle_id');
						} )
						->join('sales_order_item AS soi', function($join) {
							$join->on('soi.sales_order_id','=','sales_order.id');
						} )
						->where('sales_order.is_warning',1)
						->where('sales_order.job_type',0) //CHECK ASSIGNED TO DRIVER
						->where('sales_order.jctype',0); //CHECK ASSIGNED TO DRIVER
						
				if($search) {
					$query->where('sales_order.voucher_no','LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('sales_order.reference_no', 'LIKE',"%{$search}%")
                    ->orWhere('am.master_name', 'LIKE',"%{$search}%")
                    ->orWhere('soi.item_id', $search);
				}
				
				$query->select('sales_order.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->groupBy('soi.sales_order_id')
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	
	
	public function getReportById($id) {
		
		$query = $this->sales_order->where('QSI.is_transfer','!=',1)
								->join('sales_order_item AS QSI', function($join) {
									$join->on('QSI.sales_order_id','=','sales_order.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->where('sales_order.id',$id)
								->where('QSI.status',1);
						
		return $query->select('sales_order.voucher_no','sales_order.reference_no','IM.item_code','IM.description','sales_order.total','sales_order.vat_amount','sales_order.discount',
							  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','sales_order.net_total')
							->get();
	}
	
//	
	
}