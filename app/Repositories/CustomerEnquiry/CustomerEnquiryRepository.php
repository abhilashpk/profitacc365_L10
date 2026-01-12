<?php
declare(strict_types=1);
namespace App\Repositories\CustomerEnquiry;

use App\Models\CustomerEnquiry;
use App\Models\CustomerEnquiryItem;
use App\Models\CustomerEnquiryInfo;
use Illuminate\Support\Facades\File;
use App\Models\ItemDescription;
use App\Models\JobEstimateDetails;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;
use Auth;

class CustomerEnquiryRepository extends AbstractValidator implements CustomerEnquiryInterface {
	
	protected $customer_enquiry;
	
	protected static $rules = [];
	protected $matservice;
	protected $module;
	
	public function __construct(CustomerEnquiry $customer_enquiry) {
		$this->customer_enquiry = $customer_enquiry;
	
		$this->module = DB::table('parameter2')->where('keyname', 'mod_workshop')->where('status',1)->select('is_active')->first();
		$this->matservice = DB::table('parameter2')->where('keyname', 'mod_material_service')->where('status',1)->select('is_active')->first();
       
		$config = Config::get('siteconfig');
		$this->width = $config['modules']['joborder']['image_size']['width'];
        $this->height = $config['modules']['joborder']['image_size']['height'];
        $this->thumbWidth = $config['modules']['joborder']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['joborder']['thumb_size']['height'];
        $this->imgDir = $config['modules']['joborder']['image_dir']; 
		
		
		//Getting Salesman id...
		//Session::put('salesman_id',Auth::User()->id);
		if (Auth::check() && Auth::user()->hasRole('Salesman')) {
		    $srec = DB::table('salesman')->where('name',Auth::user()->name)->select('id')->first();
		    if($srec)
		        Session::put('salesman_id',$srec->id);
		}

	}
	
	public function all()
	{
		return $this->customer_enquiry->get();
	}
	
	public function find($id)
	{
		return $this->customer_enquiry->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		//echo '<pre>';print_r( $attributes );exit;
		$this->customer_enquiry->voucher_no   = $attributes['voucher_no'];
		$this->customer_enquiry->reference_no = $attributes['reference_no'];
		$this->customer_enquiry->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->customer_enquiry->customer_id  = $attributes['customer_id'];
		$this->customer_enquiry->salesman_id  = $attributes['salesman_id'];
		$this->customer_enquiry->subject 	 = $attributes['subject'];
		$this->customer_enquiry->description  = $attributes['description'];
		$this->customer_enquiry->job_id 		 = $attributes['job_id'];
		$this->customer_enquiry->header_id 	 = $attributes['header_id'];
		$this->customer_enquiry->footer_id 	 = $attributes['footer_id'];
		$this->customer_enquiry->is_fc 		  = isset($attributes['is_fc'])?1:0;
		$this->customer_enquiry->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id']:'';
		$this->customer_enquiry->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate']:'';
		$this->customer_enquiry->is_export		= isset($attributes['is_export'])?1:0;
		$this->customer_enquiry->vehicle_id		= isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'';
		$this->customer_enquiry->job_type		= isset($attributes['job_type'])?$attributes['job_type']:'';
		$this->customer_enquiry->jobnature		= isset($attributes['jobnature'])?$attributes['jobnature']:'';
		$this->customer_enquiry->fabrication		= isset($attributes['fabrication'])?$attributes['fabrication']:'';
		$this->customer_enquiry->prefix			= isset($attributes['prefix'])?$attributes['prefix']:'';
		$this->customer_enquiry->kilometer		= isset($attributes['kilometer'])?$attributes['kilometer']:'';
		$this->customer_enquiry->terms_id 	 = isset($attributes['terms_id'])?$attributes['terms_id']:'';
		$this->customer_enquiry->lead_id 	 = isset($attributes['lead_id'])?$attributes['lead_id']:$attributes['lead_id'];
		$this->customer_enquiry->location_id = isset($attributes['location_id'])?$attributes['location_id']:$attributes['location_id'];
		
		return true;
	}
	
	private function setJobdetails($attributes) {
							
		if(isset($attributes['opr_description']) && !empty( array_filter($attributes['opr_description'])) ) {
			
			foreach($attributes['opr_description'] as $key => $value){ 
				if($value != '') {
					$jobEstimateDetails = new JobEstimateDetails();
					$jobEstimateDetails->jobestimate_id = $this->customer_enquiry->id;
					$jobEstimateDetails->description = $value;
					$jobEstimateDetails->comment = $attributes['opr_comment'][$key];
					$jobEstimateDetails->status = 1;
					$jobEstimateDetails->save();
				}
			}
		}
		
	}
	
	private function setJobdetailsUpdate($attributes) {
		
		foreach($attributes['opr_description'] as $key => $value) { 
		
			if(isset($attributes['jobdetail_id'][$key]) && $attributes['jobdetail_id'][$key]!='') {
				
				$jobEstimateDetails = JobEstimateDetails::find($attributes['jobdetail_id'][$key]);
				$items['description'] = $value;
				$items['comment'] = $attributes['opr_comment'][$key];
				$jobEstimateDetails->update($items);
				
			} else { //new entry.....
				
				if($value != '') {
					$jobEstimateDetails = new JobEstimateDetails();
					$jobEstimateDetails->jobestimate_id = $this->customer_enquiry->id;
					$jobEstimateDetails->description = $value;
					$jobEstimateDetails->comment = $attributes['opr_comment'][$key];
					$jobEstimateDetails->status = 1;
					$jobEstimateDetails->save();
				}
			}
		}
		
		//manage removed items...
		if($attributes['remove_desc']!='')
		{
			$arrids = explode(',', $attributes['remove_desc']);
			$remline_total = $remtax_total = 0;
			foreach($arrids as $row) {
				DB::table('jobestimate_details')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
			}
		}
	}
	
	private function setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $lineTotal) 
	{
		if( isset($attributes['is_fc']) ) {
			$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key] ) * $attributes['currency_rate'];
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
			$type = 'tax_exclude';
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			
			$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
						
			if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
			}
		}
		
		//********DISCOUNT Calculation.............
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
			$tax_total = $vatLine; 
		} 
		
		$purchaseOrderItem->customer_enquiry_id = $this->customer_enquiry->id;
		$purchaseOrderItem->item_id    		   = $value;
		$purchaseOrderItem->item_name  		   = $attributes['item_name'][$key];
		$purchaseOrderItem->unit_id   		   = $attributes['unit_id'][$key];
		$purchaseOrderItem->quantity   		   = $attributes['quantity'][$key];
		$purchaseOrderItem->unit_price 		   = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$purchaseOrderItem->vat		   		   = $attributes['line_vat'][$key];
		$purchaseOrderItem->vat_amount 		   = $tax_total;
		$purchaseOrderItem->discount   		   = $attributes['line_discount'][$key];
		$purchaseOrderItem->line_total 		   = $line_total;
		$purchaseOrderItem->tax_code 		   = $tax_code;
		$purchaseOrderItem->tax_include 	   = $attributes['tax_include'][$key];
		$purchaseOrderItem->item_total 		   = $item_total;
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'type' => $type, 'item_total' => $item_total);//CHG
	}
	
	private function setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)
	{
		$purchaseOrderInfo->customer_enquiry_id = $this->customer_enquiry->id;
		$purchaseOrderInfo->title 			   = $value;
		$purchaseOrderInfo->description 	   = $attributes['desc'][$key];
		return true;
	}
	
	
	
	private function calculateTotalAmount($attributes) {
		
		$total = 0;
		if(isset($attributes['item_id'])) {
			foreach($attributes['item_id'] as $key => $value){ 
				
				$total += $attributes['quantity'][$key] * $attributes['cost'][$key];
			}
		}
		
		if(isset($attributes['lbitem_id'])) {
			foreach($attributes['lbitem_id'] as $key => $value){ 
				
				$total += $attributes['lbquantity'][$key] * $attributes['lbcost'][$key];
			}
		}
		
		return $total;
	}
	
	//SEP25
	protected function addService($attributes) {
		
		$linetotal = $taxtotal = $itemtotal = $lbtax_total = $vat = 0; $type = 'tax_include';
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		if($discount > 0)
			$lineTotal = $this->calculateTotalAmount($attributes);
		
		if(isset($attributes['lbitem_id'])) {
			foreach($attributes['lbitem_id'] as $key => $value){ 
			
				$quotationSalesItem = new CustomerEnquiryItem();
				
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
					
					/* $tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
					$item_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);// - $attributes['line_discount'][$key];
					$tax_total  = round($tax * $attributes['lbquantity'][$key],2); */
					
					$tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
					$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
					$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
					$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
				}
				//--------
				
				/* $tax        = ($attributes['lbcost'][$key] * $attributes['lbline_vat'][$key]) / 100;
				$lbitem_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]);
				$lbtax_total  = round($tax * $attributes['lbquantity'][$key],2);
				$lbline_total = ($attributes['lbcost'][$key] * $attributes['lbquantity'][$key]); */
				
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
					
				$quotationSalesItem->customer_enquiry_id = $this->customer_enquiry->id;
				$quotationSalesItem->item_id    		= $value;
				$quotationSalesItem->item_name  		= $attributes['lbitem_name'][$key];
				$quotationSalesItem->unit_id 			= $attributes['lbunit_id'][$key];
				$quotationSalesItem->quantity   		= $attributes['lbquantity'][$key];
				$quotationSalesItem->unit_price 		= (isset($attributes['is_fc']))?$attributes['lbcost'][$key]*$attributes['currency_rate']:$attributes['lbcost'][$key];
				$quotationSalesItem->vat		    	= $attributes['lbline_vat'][$key];
				$quotationSalesItem->vat_amount 		= $lbtax_total;
				$quotationSalesItem->discount   		= $attributes['lbline_discount'][$key];
				$quotationSalesItem->line_total 		= $lbline_total;
				$quotationSalesItem->tax_code 			= $attributes['lbtax_code'][$key];
				$quotationSalesItem->tax_include 		= $attributes['lbtax_include'][$key];
				$quotationSalesItem->item_total 		= $lbitem_total;
				$quotationSalesItem->item_type = 1;
				$quotationSalesItem->status = 1;
				$itemObj = $this->customer_enquiry->quotationItem()->save($quotationSalesItem);
				
				$linetotal += $lbline_total;
				$taxtotal += $lbtax_total;
				$itemtotal += $lbitem_total;
				$vat = $attributes['lbline_vat'][$key];
			}
		}
		return array('line_total' => $linetotal, 'tax_total' => $taxtotal, 'item_total' => $itemtotal, 'type' => $type, 'vat' => $vat );//SEP25
	}
	
	//SEP25
	protected function updateService($attributes)
	{
		$line_total = $tax_total = $item_total = $lbtax_total = 0;
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
				
				$quotationSalesItem = CustomerEnquiryItem::find($attributes['lborder_item_id'][$key]);
				$oldqty = $quotationSalesItem->quantity;
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
				$exi_quantity = $quotationSalesItem->quantity;
				$quotationSalesItem->update($items);
				
				$tax_total += $lbtax_total;
				$line_total += $linetotal;
				$item_total += $itemtotal;
				
			} else { //add new service
				
				$quotationSalesItem = new CustomerEnquiryItem();
				
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
				
				$quotationSalesItem->customer_enquiry_id = $this->customer_enquiry->id;
				$quotationSalesItem->item_id    		= $value;
				$quotationSalesItem->item_name  		= $attributes['lbitem_name'][$key];
				$quotationSalesItem->unit_id 			= $attributes['lbunit_id'][$key];
				$quotationSalesItem->quantity   		= $attributes['lbquantity'][$key];
				$quotationSalesItem->unit_price 		= (isset($attributes['is_fc']))?$attributes['lbcost'][$key]*$attributes['currency_rate']:$attributes['lbcost'][$key];
				$quotationSalesItem->vat		    	= $attributes['lbline_vat'][$key];
				$quotationSalesItem->vat_amount 		= $lbtax_total;
				$quotationSalesItem->discount   		= $attributes['lbline_discount'][$key];
				$quotationSalesItem->line_total 		= $lbline_total;
				$quotationSalesItem->tax_code 		= $attributes['lbtax_code'][$key];
				$quotationSalesItem->tax_include 		= $attributes['lbtax_include'][$key];
				$quotationSalesItem->item_total 		= $lbitem_total;
				$quotationSalesItem->item_type = 1;
				$quotationSalesItem->status = 1;
				
				$itemObj = $this->customer_enquiry->quotationItem()->save($quotationSalesItem);
				
				$line_total += $lbline_total;
				$tax_total += $lbtax_total;
				$item_total += $lbitem_total;
			}
			
			$vat = $attributes['lbline_vat'][$key];
		}
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'item_total' => $item_total, 'type' => $type, 'vat' => $vat );
		//return array('line_total' => $line_total, 'tax_total' => $tax_total, 'item_total' => $item_total);	
	}
	
	
	public function create($attributes)
	{
		//echo '<pre>';print_r(Auth::User()->id );exit;
		if($this->isValid($attributes)) {
			//echo '<pre>';print_r($attributes);exit;
			DB::beginTransaction();
			try {
				
				/* $qs = DB::table('voucher_no')->where('voucher_type', 'QS')->where('status',1)->select('no AS voucher_no')->first();
				$attributes['voucher_no'] = $qs->voucher_no; */
				$attributes['salesman_id'] = (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0;
				if($this->setInputValue($attributes)) {
					$this->customer_enquiry->status 	   = 1;
					$this->customer_enquiry->created_at = now();
					$this->customer_enquiry->created_by = 1;
					$this->customer_enquiry->fill($attributes)->save();
					
					//check workshop version active...
					if($this->module->is_active==1 && $this->customer_enquiry->id) {
						$this->setJobdetails($attributes);
					}
				}
				
				$line_total = 0; $tax_total = 0; $total = $item_total = 0; $taxtype = '';
				$discount = (isset($attributes['discount']))?$attributes['discount']:0;
				
				//quotation sales items insert
				if($this->customer_enquiry->id && !empty( array_filter($attributes['item_id']))) {
					
					
					//calculate total amount....2
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
					
					foreach($attributes['item_id'] as $key => $value) { 
						$purchaseOrderItem 		   = new CustomerEnquiryItem();
						$vat = $attributes['line_vat'][$key];
						$arrResult 			  	   = $this->setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $total);
						//if($arrResult['line_total']) {
							$line_total				  += $arrResult['line_total'];
							$tax_total      		  += $arrResult['tax_total'];
							$taxtype				  = $arrResult['type'];
							$item_total				 += $arrResult['item_total'];
								
							$purchaseOrderItem->status = 1;
							$itemObj = $this->customer_enquiry->quotationItem()->save($purchaseOrderItem);
							
							//item description section....
							if(isset($attributes['itemdesc'][$key])) {
								foreach($attributes['itemdesc'][$key] as $descrow) {
									if($descrow != '') {
										$itemDescription = new ItemDescription();
										$itemDescription->invoice_type = 'QS';
										$itemDescription->item_detail_id = $itemObj->id;
										$itemDescription->description = $descrow;
										$itemDescription->status = 1;
										$itemDescription->save();
									}
								}
							}
						//}
						
					}
				 }
					//Check material service module..........
					if($this->matservice->is_active==1) {
						
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
					
					//update discount, total amount
					DB::table('customer_enquiry')
								->where('id', $this->customer_enquiry->id)
								->update(['total'      => $line_total,
										  'discount'   => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat_amount' => $tax_total,
										  'net_total'  => $net_amount,
										  'total_fc'   => $total_fc,
										  'discount_fc'=> $discount_fc,
										  'net_total_fc' => $net_amount_fc,
										  'vat_amount_fc' => $tax_fc,
										  'subtotal'	  => $subtotal,
										  'subtotal_fc'	  => $subtotal_fc,
										  'footer_text'	  =>isset($attributes['footer'])?$attributes['footer']:''  ]);
				//}
				
				//quotation info insert
				if($this->customer_enquiry->id && !empty( array_filter($attributes['title']))) {
					foreach($attributes['title'] as $key => $value) {
						$purchaseOrderInfo 			= new CustomerEnquiryInfo();
						if($this->setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)) {
							$purchaseOrderInfo->status = 1;
							$this->customer_enquiry->quotationInfo()->save($purchaseOrderInfo);
						}
					}
				}
				
				//update voucher number
				if($this->customer_enquiry->id && $attributes['autoincrement']==1) {
					 DB::table('voucher_no')
						->where('voucher_type', $attributes['voucher_type'])
						->update(['no' => $attributes['voucher_no'] + 1]);
						//->update(['no' => DB::raw('no + 1')]);//$this->customer_enquiry->id
				}
				
                // for crm

				if($this->customer_enquiry->id && isset($attributes['photo_name'])) {
					//echo '<pre>';print_r(explode(',',$attributes['photo_name']));exit;
					
					$photos = explode(',',$attributes['photo_name']);
					foreach($photos as $photo) {
						if($photo!='') {
							DB::table('enq_fotos')
									->insert(['enq_id' => $this->customer_enquiry->id, 
											  'photo' => $photo,
											  
											]);
						}
					}
				}

				

				DB::commit();
				return $this->customer_enquiry->id;//true;
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().' - '.$e->getMessage();exit;
				return false;
			}
		}
		
	}
	
	public function update($id, $attributes)
	{
		$this->customer_enquiry = $this->find($id);
		$line_total = $tax_total = $line_total_new = $tax_total_new = $item_total = $total = 0;
		
		DB::beginTransaction();
		try {
			
			//check workshop version active...
			if($this->module->is_active==1 && $this->customer_enquiry->id) {
				$this->setJobdetailsUpdate($attributes);
			}
			
			$discount = (isset($attributes['discount']))?$attributes['discount']:0; $taxtype = '';
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->customer_enquiry->id && !empty( array_filter($attributes['item_id']))) {
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
							$deskey = $attributes['order_item_id'][$key];
							$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
							
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
									
								} else if($attributes['tax_include'][$key]==1){
									
									$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
									$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
									$itemtotal = $ln_total - $taxtotal;
									
								} else {
									
									$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
									$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
								}
							}
							
							//********DISCOUNT Calculation.............
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
								$taxtotal = $vatLine; 
							} 
							
							$tax_total += $taxtotal;
							$line_total += $linetotal;
							$item_total += $itemtotal;
							
							$vat = $attributes['line_vat'][$key];
						
							$quotationSalesItem = CustomerEnquiryItem::find($attributes['order_item_id'][$key]);
							$items['item_name'] = $attributes['item_name'][$key];
							$items['item_id'] = $value;
							$items['unit_id'] = $attributes['unit_id'][$key];
							$items['quantity'] = $attributes['quantity'][$key];
							$items['unit_price'] = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
							$items['vat']		 = $attributes['line_vat'][$key];
							$items['vat_amount'] = $taxtotal;
							$items['discount'] = $attributes['line_discount'][$key];
							$items['line_total'] = $linetotal;
							$items['item_total'] = $itemtotal; //CHG
							$items['tax_code'] 	= $tax_code;
							$items['tax_include'] = $attributes['tax_include'][$key];
							$quotationSalesItem->update($items);
							
							//description update...
							if(isset($attributes['desc_id'])) {
								if(array_key_exists($deskey, $attributes['desc_id'])) {
									foreach($attributes['desc_id'][$deskey] as $k => $v) {
										if($v!='') {
											$itemDescription = ItemDescription::find($v);
											$desc['description'] = $attributes['itemdesc'][$deskey][$k];
											$itemDescription->update($desc);
										} else {
											//new entry.........
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'QS';
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
											$itemDescription->invoice_type = 'QS';
											$itemDescription->item_detail_id = $deskey;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}
							}

							//manage removed item description...
							if(isset($attributes['remove_itemdesc']) && isset($attributes['remove_itemdesc'][$key]) && $attributes['remove_itemdesc'][$key]!='')
							{
								$arrids = explode(',', $attributes['remove_itemdesc'][$key]);
								foreach($arrids as $row) {
									DB::table('item_description')->where('id', $row)->update(['status' => 0,'deleted_at' => now()]);
								}
							}
							
							
						} else { //new entry...
							$item_total_new = $tax_total_new = $item_total_new = 0;
							if($discount > 0) 
								$total = $this->calculateTotalAmount($attributes);
							
							$vat = $attributes['line_vat'][$key];
							$quotationSalesItem = new CustomerEnquiryItem();
							$arrResult 		= $this->setItemInputValue($attributes, $quotationSalesItem, $key, $value ,$total);//CHG
							if($arrResult['line_total']) {
								$line_total_new 		 += $arrResult['line_total'];
								$tax_total_new      	 += $arrResult['tax_total'];
								$item_total_new			 += $arrResult['item_total']; //CHG
									
								$line_total			     += $arrResult['line_total'];
								$tax_total      	     += $arrResult['tax_total'];
								$item_total 			 += $arrResult['item_total'];
								$taxtype				  = $arrResult['type'];
								$quotationSalesItem->status = 1;
								$itemObj = $this->customer_enquiry->quotationItem()->save($quotationSalesItem);
								
								//new entry description.........
								if(isset($attributes['itemdesc'][$key])) {
									foreach($attributes['itemdesc'][$key] as $descrow) {
										if($descrow != '') {
											$itemDescription = new ItemDescription();
											$itemDescription->invoice_type = 'QS';
											$itemDescription->item_detail_id = $itemObj->id;
											$itemDescription->description = $descrow;
											$itemDescription->status = 1;
											$itemDescription->save();
										}
									}
								}
							}
						}
						
					}
				}
				
				
				//check workshop version active...
				if($this->matservice->is_active==1 && $this->customer_enquiry->id) {
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
							DB::table('customer_enquiry_item')->where('id', $row)->update(['status' => 0,'deleted_at' => now()]);
						}
					}
				}
				
				
				//manage removed items...
				if($attributes['remove_item']!='')
				{
					$arrids = explode(',', $attributes['remove_item']);
					$remline_total = $remtax_total = 0;
					foreach($arrids as $row) {
						DB::table('customer_enquiry_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => now()]);
					}
				}
				
				$this->customer_enquiry->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
				$this->customer_enquiry->fill($attributes)->save();
				
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
				
				//update discount, total amount
				DB::table('customer_enquiry')
							->where('id', $this->customer_enquiry->id)
							->update(['total'    	  => $line_total,
									  'discount' 	  => $attributes['discount'],
									  'vat_amount'	  => $tax_total,
									  'net_total'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_total_fc'  => $net_amount_fc,
									  'subtotal'	  => $subtotal, //CHG
									  'subtotal_fc'	  => $subtotal_fc,
									  'footer_text'	  => isset($attributes['footer'])?$attributes['footer']:'',
									  'doc_status' 	  => (isset($attributes['doc_status']))?$attributes['doc_status']:'',
									  'comment'		  => (isset($attributes['comment']))?$attributes['comment']:((isset($attributes['comment_hd']))?$attributes['comment_hd']:'')
									  ]); //CHG

									  $photos = [];
		$phoo =  $attributes['photo_name'] ;
		$old_pho = $attributes['old_photo_name'] ;
		$rem_phot = $attributes['rem_photo_name'] ;
				if(isset($phoo)) {
					$photos = explode(',',$attributes['photo_name']);
				}
			
				//Update photos...request()->input('old_photo_name')
				if(isset($old_pho) && $old_pho!='') {
					
					$exi_photos = explode(',',$attributes['old_photo_name']);
					
					foreach($photos as $ky => $val) {
						if(isset($exi_photos[$ky])) {
							if($val!='') {
								DB::table('enq_fotos')
										->where('enq_id', $id)
										->where('photo', $photos[$ky])
										->update(['photo' => $val]);
							}
						} else {
							DB::table('enq_fotos')->insert(['enq_id' => $id, 'photo' => $val]);
						}
					}
					
				} else { //Add photos
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('enq_fotos')->insert(['enq_id' => $id, 'photo' => $photo]);
					}
				}
				
				
				//Remove photos request()->input('rem_photo_name')
				if(isset($rem_phot)) {
					$rem_photos = explode(',',$attributes['rem_photo_name']);
					foreach($rem_photos as $photo) {
						DB::table('enq_fotos')->where('enq_id',$id)
									->where('photo', $photo)
									->delete();
									
						$fPath = public_path() . $this->imgDir.'/'.$photo;
						File::delete($fPath);
					}
				}
										  
			DB::commit();
			return true;
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' - '.$e->getMessage();exit;
			return false;
		}
	}
	
	
	public function delete($id)
	{
		$this->customer_enquiry = $this->customer_enquiry->find($id);
		$this->customer_enquiry->delete();
	}
	
	public function quotationSalesList()
	{
		$query = $this->customer_enquiry->where('customer_enquiry.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_enquiry.customer_id');
						} )
					->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','customer_enquiry.vehicle_id');
						} )
					->select('customer_enquiry.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->orderBY('customer_enquiry.id', 'DESC')
					->get();
	}
	
	public function getCustomerEnquiry($customer_id)
	{
		return $this->customer_enquiry
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_enquiry.salesman_id');
								} )
							 ->where('customer_enquiry.status', 1)
							 ->where('customer_enquiry.customer_id', $customer_id)
							 ->where('customer_enquiry.is_transfer', 0)
							 ->select('customer_enquiry.id','customer_enquiry.voucher_no','customer_enquiry.voucher_date',
								'customer_enquiry.prefix','S.name','customer_enquiry.net_total')
							 ->get();
		
	}
	
		
	public function findQuoteData($id)
	{
		$query = $this->customer_enquiry->where('customer_enquiry.id', $id)->where('customer_enquiry.is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_enquiry.customer_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','customer_enquiry.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','customer_enquiry.footer_id');
					})
					->leftJoin('salesman AS S', function($join) {
							$join->on('S.id','=','customer_enquiry.salesman_id');
						} )
					->select('customer_enquiry.*','am.master_name AS customer','h.title AS header','f.title AS footer',
							 'S.name AS salesman')
					->orderBY('customer_enquiry.id', 'ASC')
					->first();
	}
	
	public function getCEItems($id)
	{
		$query = $this->customer_enquiry->whereIn('customer_enquiry.id',$id);
		
		return $query->join('customer_enquiry_item AS poi', function($join) {
							$join->on('poi.customer_enquiry_id','=','customer_enquiry.id');
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
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty')
					  ->orderBY('poi.id')->groupBy('poi.id')
					  ->get();
					  
		
	}
	
	
	public function activeCustomerEnquiryList()
	{
		return $this->customer_enquiry->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->customer_enquiry->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->customer_enquiry->where('reference_no',$refno)->count();
	}
		
	public function getQuotationReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->customer_enquiry->where('customer_enquiry.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_enquiry.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','customer_enquiry.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','customer_enquiry.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->customer_enquiry->where('customer_enquiry.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_enquiry.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','customer_enquiry.job_id');
								   })
								   ->select('AM.master_name AS supplier','customer_enquiry.*','JM.name AS job')
								   ->orderBY('customer_enquiry.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getQuotation($attributes)
	{
		$order = $this->customer_enquiry->where('customer_enquiry.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','customer_enquiry.customer_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','customer_enquiry.currency_id');
								   })
								   ->leftJoin('header_footer AS F', function($join) {
									   $join->on('F.id','=','customer_enquiry.footer_id');
								   })
								   ->leftJoin('header_footer AS H', function($join) {
									   $join->on('H.id','=','customer_enquiry.header_id');
								   })
								   ->leftJoin('vehicle AS V', function($join) {
									   $join->on('V.id','=','customer_enquiry.vehicle_id');
								   })
								    ->leftJoin('salesman AS SL', function($join) {
									   $join->on('SL.id','=','customer_enquiry.salesman_id');
								   })
								   ->leftJoin('terms AS T', function($join) {
									   $join->on('T.id','=','customer_enquiry.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','customer_enquiry.*','AM.address','AM.city','H.description AS header','T.description AS terms',
											'AM.state','AM.contact_name','AM.vat_no','AM.phone AS custphone','C.name AS currency','F.description AS footer','V.*','SL.name AS salesman')
								   ->orderBY('customer_enquiry.id', 'ASC')
								   ->first();
								   
		$items = $this->customer_enquiry->where('customer_enquiry.id', $attributes['document_id'])
								   ->join('customer_enquiry_item AS PI', function($join) {
									   $join->on('PI.customer_enquiry_id','=','customer_enquiry.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','IM.item_code','U.unit_name')
								   ->orderBY('PI.id')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	
	public function findPOdata($id)
	{
		$query = $this->customer_enquiry->where('customer_enquiry.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_enquiry.customer_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','customer_enquiry.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','customer_enquiry.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','customer_enquiry.salesman_id');
					})
					->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','customer_enquiry.vehicle_id');
					})
					->select('customer_enquiry.*','am.master_name AS customer','h.title AS header','V.make','V.model','am.email',
							 'f.description AS footer','S.name AS salesman','h.id AS hid','f.id AS fid','V.name AS vehicle','V.reg_no')
					->first();
	}
	
	public function getItems($id,$mod=null)
	{
		$query = $this->customer_enquiry->where('customer_enquiry.id',$id);
		
		$query->join('customer_enquiry_item AS poi', function($join) {
							$join->on('poi.customer_enquiry_id','=','customer_enquiry.id');
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
	
	
	
	public function getItemDesc($id)
	{
		return DB::table('customer_enquiry')
						->join('customer_enquiry_item AS QSI', function($join) {
							$join->on('QSI.customer_enquiry_id', '=', 'customer_enquiry.id');
						})
						->join('item_description AS D', function($join) {
							$join->on('D.item_detail_id', '=', 'QSI.id');
						})
						->where('customer_enquiry.id', $id)
						->where('D.invoice_type','QS')
						->where('QSI.status',1)
						->where('QSI.deleted_at','0000-00-00 00:00:00')
						->where('D.status',1)
						->where('D.deleted_at','0000-00-00 00:00:00')
						->select('D.*')
						->get();
	}
	

	public function getPendingReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$pending=isset($attributes['pending'])?$attributes['pending']:0;
		//switch($attributes['search_type']) {
		//	case 'summary':
		if($attributes['search_type']=='summary' && $pending==0){
				$query = $this->customer_enquiry
								->join('customer_enquiry_item AS QSI', function($join) {
									$join->on('QSI.customer_enquiry_id','=','customer_enquiry.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_enquiry.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_enquiry.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_enquiry.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_enquiry.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_enquiry.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('customer_enquiry.job_id', $attributes['job_id']);
						 
				return $query->select('customer_enquiry.voucher_no','customer_enquiry.reference_no','customer_enquiry.total','customer_enquiry.vat_amount','S.name AS salesman',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','customer_enquiry.net_total','customer_enquiry.discount')
								->groupBy('customer_enquiry.id')->get();
				//break;
		}
			//case 'summary_pending':
			else if($attributes['search_type']=='summary' && $pending==1){
				$query = $this->customer_enquiry->where('QSI.is_transfer','!=',1)
								->join('customer_enquiry_item AS QSI', function($join) {
									$join->on('QSI.customer_enquiry_id','=','customer_enquiry.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_enquiry.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_enquiry.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_enquiry.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_enquiry.salesman_id', $attributes['salesman']);
						}
						
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_enquiry.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('customer_enquiry.job_id', $attributes['job_id']);
						 
				return $query->select('customer_enquiry.voucher_no','customer_enquiry.reference_no','customer_enquiry.total','customer_enquiry.vat_amount','customer_enquiry.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','customer_enquiry.net_total','S.name AS salesman','QSI.vat_amount AS unit_vat')
								->get(); //->groupBy('customer_enquiry.id')
								
			//	break;
			}	
			//case 'detail':
			else if($attributes['search_type']=='detail' && $pending==0){
				$query = $this->customer_enquiry
								->join('customer_enquiry_item AS QSI', function($join) {
									$join->on('QSI.customer_enquiry_id','=','customer_enquiry.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_enquiry.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_enquiry.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_enquiry.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_enquiry.salesman_id', $attributes['salesman']);
						}
						
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_enquiry.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('customer_enquiry.job_id', $attributes['job_id']);
						
				return $query->select('customer_enquiry.voucher_no','customer_enquiry.reference_no','IM.item_code','IM.description','S.name AS salesman','QSI.vat_amount AS unit_vat',
									  'QSI.quantity','QSI.unit_price','QSI.line_total','AM.master_name','customer_enquiry.net_total','customer_enquiry.discount')
								->get();
				//break;
			}
			//case 'detail_pending':
			else if($attributes['search_type']=='detail' && $pending==1){
				$query = $this->customer_enquiry->where('QSI.is_transfer','!=',1)
								->join('customer_enquiry_item AS QSI', function($join) {
									$join->on('QSI.customer_enquiry_id','=','customer_enquiry.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','customer_enquiry.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','customer_enquiry.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('customer_enquiry.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('customer_enquiry.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('customer_enquiry.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
								$query->whereIn('customer_enquiry.job_id', $attributes['job_id']);
						
				return $query->select('customer_enquiry.voucher_no','customer_enquiry.reference_no','IM.item_code','IM.description','customer_enquiry.total','customer_enquiry.vat_amount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','AM.master_name','customer_enquiry.net_total','S.name AS salesman','customer_enquiry.discount')
								->get();
				//break;
		}
	}
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->customer_enquiry->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->customer_enquiry->where('voucher_no',$refno)->count();
	}
	
	public function getjobDescription($id)
	{
		return DB::table('jobestimate_details')->where('jobestimate_id',$id)->where('status',1)->whereNull('deleted_at')->get();
	}
	
	public function salesEstimateListCount()
	{

		$query = $this->customer_enquiry->where('customer_enquiry.status',1);
		if(Auth::user()->roles[0]->name=='Salesman')
					$query->where('customer_enquiry.salesman_id',Session::get('salesman_id'));  // for CRM
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_enquiry.customer_id');
						} )
					->count();
	}
	
	public function salesEstimateList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->customer_enquiry->where('customer_enquiry.status',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','customer_enquiry.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','customer_enquiry.vehicle_id');
						} );
						
				if($search) {
					$query->where('customer_enquiry.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				if(Auth::user()->roles[0]->name=='Salesman')
					$query->where('customer_enquiry.salesman_id',Session::get('salesman_id'));  // for CRM
				$query->select('customer_enquiry.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
}


