<?php namespace App\Repositories\QuotationSales;

use App\Models\QuotationSales;
use App\Models\QuotationSalesItem;
use App\Models\QuotationSalesInfo;
use App\Models\ItemDescription;
use App\Models\JobEstimateDetails;
use Illuminate\Support\Facades\File;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use App\Repositories\UpdateUtility;
use Config;
use DB;
use Auth;


class QuotationSalesRepository extends AbstractValidator implements QuotationSalesInterface {
	
	protected $quotation_sales;
	public $objUtility;

	protected static $rules = [];
	protected $matservice;
	protected $module;
	
	public function __construct(QuotationSales $quotation_sales) {
		$this->quotation_sales = $quotation_sales;
		$this->objUtility = new UpdateUtility();
		$config = Config::get('siteconfig');
		$this->width = $config['modules']['jobestimate']['image_size']['width'];
        $this->height = $config['modules']['jobestimate']['image_size']['height'];
        $this->thumbWidth = $config['modules']['jobestimate']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['jobestimate']['thumb_size']['height'];
        $this->imgDir = $config['modules']['jobestimate']['image_dir']; 
		$this->module = DB::table('parameter2')->where('keyname', 'mod_workshop')->where('status',1)->select('is_active')->first();
		$this->matservice = DB::table('parameter2')->where('keyname', 'mod_material_service')->where('status',1)->select('is_active')->first();
	}
	
	public function all()
	{
		return $this->quotation_sales->get();
	}
	
	public function find($id)
	{
		return $this->quotation_sales->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		
		$this->quotation_sales->voucher_no   = $attributes['voucher_no'];
		$this->quotation_sales->reference_no = $attributes['reference_no'] ?? null;
		$this->quotation_sales->voucher_date = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
		$this->quotation_sales->customer_id  = $attributes['customer_id'];
		$this->quotation_sales->salesman_id  = $attributes['salesman_id'] ?? 0;
		$this->quotation_sales->subject 	 = $attributes['subject'] ?? null;
		$this->quotation_sales->description  = $attributes['description'] ?? null;
		$this->quotation_sales->job_id 		 = $attributes['job_id'] ?? 0;
		$this->quotation_sales->header_id 	 = $attributes['header_id'] ?? 0;
		$this->quotation_sales->footer_id 	 = $attributes['footer_id'] ?? 0;
		$this->quotation_sales->is_fc 		  = isset($attributes['is_fc'])?1:0;
		$this->quotation_sales->currency_id   = (isset($attributes['currency_id']))?$attributes['currency_id'] ?? 0:'';
		$this->quotation_sales->currency_rate = (isset($attributes['currency_rate']))?$attributes['currency_rate'] ?? 0:'';
		$this->quotation_sales->is_export		= isset($attributes['is_export'])?1:0;
		$this->quotation_sales->vehicle_id		= isset($attributes['vehicle_id'])?$attributes['vehicle_id'] ?? 0:'';
		$this->quotation_sales->job_type		= isset($attributes['job_type'])?$attributes['job_type']:(isset($attributes['is_revice'])?$attributes['is_revice']:'');
		$this->quotation_sales->jobnature		= isset($attributes['jobnature'])?$attributes['jobnature']:'';
		$this->quotation_sales->fabrication		= isset($attributes['fabrication'])?$attributes['fabrication']:(isset($attributes['parent_id'])?$attributes['parent_id']:'');
		$this->quotation_sales->prefix			= isset($attributes['prefix'])?$attributes['prefix']:'';
		$this->quotation_sales->kilometer		= isset($attributes['kilometer'])?$attributes['kilometer']:'';
		$this->quotation_sales->terms_id 	 = isset($attributes['terms_id'])?$attributes['terms_id']:'';
		$this->quotation_sales->customer_enquiry_id  = isset($attributes['quotation_id'])?$attributes['quotation_id']:'';
		$this->quotation_sales->location_id = (isset($attributes['location_id']))?$attributes['location_id']:'';	
		$this->quotation_sales->items_description = (isset($attributes['items_description']))?$attributes['items_description']:'';
		$this->quotation_sales->foot_description = (isset($attributes['foot_description']))?$attributes['foot_description']:'';	
		$this->quotation_sales->metre_in = (isset($attributes['metre_in']))?$attributes['metre_in']:'';
		$this->quotation_sales->metre_out = (isset($attributes['metre_out']))?$attributes['metre_out']:'';
	//	$this->quotation_sales->location_id = isset($attributes['location_id'])?$attributes['location_id']:$attributes['location_id'];
		
		return true;
	}
	
	private function setJobdetails($attributes) {
							
		if(isset($attributes['opr_description']) && !empty( array_filter($attributes['opr_description'])) ) {
			
			foreach($attributes['opr_description'] as $key => $value){ 
				if($value != '') {
					$jobEstimateDetails = new JobEstimateDetails();
					$jobEstimateDetails->jobestimate_id = $this->quotation_sales->id;
					$jobEstimateDetails->description = $value;
					$jobEstimateDetails->comment = $attributes['opr_comment'][$key];
					$jobEstimateDetails->status = 1;
					$jobEstimateDetails->save();
				}
			}
		}
		
	}
	
	private function setJobdetailsUpdate($attributes) {
		
		if(isset($attributes['opr_description']) && !empty( array_filter($attributes['opr_description'])) ) {
			foreach($attributes['opr_description'] as $key => $value) { 
			
				if(isset($attributes['jobdetail_id'][$key]) && $attributes['jobdetail_id'][$key]!='') {
					
					$jobEstimateDetails = JobEstimateDetails::find($attributes['jobdetail_id'][$key]);
					$items['description'] = $value;
					$items['comment'] = $attributes['opr_comment'][$key];
					$jobEstimateDetails->update($items);
					
				} else { //new entry.....
					
					if($value != '') {
						$jobEstimateDetails = new JobEstimateDetails();
						$jobEstimateDetails->jobestimate_id = $this->quotation_sales->id;
						$jobEstimateDetails->description = $value;
						$jobEstimateDetails->comment = $attributes['opr_comment'][$key];
						$jobEstimateDetails->status = 1;
						$jobEstimateDetails->save();
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
				DB::table('jobestimate_details')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
			}
		}
	}
	
	private function setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $lineTotal) 
	{
		if( isset($attributes['is_fc']) ) {
			$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
			$item_total = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key] ) * $attributes['currency_rate'];
			$tax_total  = round($tax * $attributes['quantity'][$key],2);
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
			$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
			$type = 'tax_exclude';
		} else {
			
			$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
			
			$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
						
			if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
				
				$tax        = 0;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
				$tax_total  = round($tax * $attributes['quantity'][$key],2);
				
			} else if($attributes['tax_include'][$key]==1){
				
				$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
				$tax_total  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
				$item_total = $ln_total - $tax_total;
				
			} else {
				
				$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
				$item_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
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
		
		$purchaseOrderItem->quotation_sales_id = $this->quotation_sales->id;
		$purchaseOrderItem->item_id    		   = $value;
		$purchaseOrderItem->item_name  		   = $attributes['item_name'][$key];
		$purchaseOrderItem->unit_id   		   = $attributes['unit_id'][$key];
		$purchaseOrderItem->quantity   		   = $attributes['quantity'][$key];
		$purchaseOrderItem->unit_price 		   = (isset($attributes['is_fc']))?$attributes['cost'][$key]*$attributes['currency_rate']:$attributes['cost'][$key];
		$purchaseOrderItem->vat		   		   = $attributes['line_vat'][$key];
		$purchaseOrderItem->vat_amount 		   = $tax_total;
		$purchaseOrderItem->discount   		   = $attributes['line_discount'][$key] ?? 0;
		$purchaseOrderItem->line_total 		   = $line_total;
		$purchaseOrderItem->tax_code 		   = $tax_code;
		$purchaseOrderItem->tax_include 	   = $attributes['tax_include'][$key];
		$purchaseOrderItem->item_total 		   = $item_total;
		$purchaseOrderItem->orderno 		   = isset($attributes['orderno'][$key])?$attributes['orderno'][$key]:''; //JN23
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total, 'type' => $type, 'item_total' => $item_total);//CHG
	}
	
	private function setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)
	{
		$purchaseOrderInfo->quotation_sales_id = $this->quotation_sales->id;
		$purchaseOrderInfo->title 			   = $attributes['title'][$key];
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
	
	private function setTransferStatusItem($attributes, $key)
	{
		//if quantity partially deliverd, update pending quantity.
		if(isset($attributes['quote_sales_item_id'])) {
			if(isset($attributes['actual_quantity']) && ($attributes['quantity'][$key] != $attributes['actual_quantity'][$key])) {
				if( isset($attributes['quote_sales_item_id'][$key]) ) {
					$quantity 	 = $attributes['actual_quantity'][$key] - $attributes['quantity'][$key];
					//update as partially delivered.
					DB::table('customer_enquiry_item')
								->where('id', $attributes['quote_sales_item_id'][$key])
								->update(['balance_quantity' => $quantity, 'is_transfer' => 2]);
				}
			} else {
					//update as completely delivered.
					DB::table('customer_enquiry_item')
								->where('id', $attributes['quote_sales_item_id'][$key])
								->update(['balance_quantity' => 0, 'is_transfer' => 1]);
			}
		}
	}
	
	private function setTransferStatusQuote($attributes)
	{
		//update purchase order transfer status....
		if(isset($attributes['quotation_id'])) {
			$idarr = explode(',',$attributes['quotation_id']);
			if($idarr) {
				foreach($idarr as $id) {
					DB::table('customer_enquiry')->where('id', $id)->update(['is_editable' => 0]);
					$row1 = DB::table('customer_enquiry_item')->where('customer_enquiry_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->count();
					$row2 = DB::table('customer_enquiry_item')->where('customer_enquiry_id', $id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('is_transfer',1)->count();
					if($row1==$row2) {
						DB::table('customer_enquiry')
								->where('id', $id)
								->update(['is_transfer' => 1]);
					}
				}
			}
		}
	}
	
	//SEP25
	protected function addService($attributes) {
		
		$linetotal = $taxtotal = $itemtotal = $lbtax_total = $vat = 0; $type = 'tax_include';
		$discount = (isset($attributes['discount']))?$attributes['discount']:0;
		if($discount > 0)
			$lineTotal = $this->calculateTotalAmount($attributes);
		
		if(isset($attributes['lbitem_id'])) {
			foreach($attributes['lbitem_id'] as $key => $value){ 
			
				$quotationSalesItem = new QuotationSalesItem();
				
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
					
				$quotationSalesItem->quotation_sales_id = $this->quotation_sales->id;
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
				$itemObj = $this->quotation_sales->quotationItem()->save($quotationSalesItem);
				
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
				
				$quotationSalesItem = QuotationSalesItem::find($attributes['lborder_item_id'][$key]);
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
				
				$quotationSalesItem = new QuotationSalesItem();
				
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
				
				$quotationSalesItem->quotation_sales_id = $this->quotation_sales->id;
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
				
				$itemObj = $this->quotation_sales->quotationItem()->save($quotationSalesItem);
				
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
		//echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			DB::beginTransaction();
			try {
				if(isset($attributes['is_revice'])) {
					$parentid = $attributes['parent_id'];
					$qrow = DB::table('quotation_sales')->where('id',$parentid)->select('voucher_no','jobnature')->first();
					$count = $qrow->jobnature + 1;
					$voucherno = $qrow->voucher_no.'/R-'.$count;
					
				} 
				//$attributes['salesman_id'] = (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):implode(',', $attributes['salesman_id']);
				if (Auth::check() && Auth::user()->hasRole('Salesman')) {

					$attributes['salesman_id'] = Session::get('salesman_id');

				} else {

					$attributes['salesman_id'] = collect($attributes['salesman_id'] ?? [])
						->filter()
						->implode(',');
				}



				//VOUCHER NO LOGIC.....................
				$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

				 // 2️⃣ Get the highest numeric part from voucher_master
				$qry = DB::table('quotation_sales')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
				if($dept > 0)	
					$qry->where('department_id', $dept);

				$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
				
				$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('QS', $maxNumeric, $dept, $attributes['voucher_no']);
				//VOUCHER NO LOGIC.....................
				
				//exit;
				$maxRetries = 5; // prevent infinite loop
				$retryCount = 0;
				$saved = false;

				while (!$saved && $retryCount < $maxRetries) {
				try {
						if($this->setInputValue($attributes)) {
							$this->quotation_sales->status 	   = 1;
							$this->quotation_sales->created_at = date('Y-m-d H:i:s');
							$this->quotation_sales->created_by = 1;
							$this->quotation_sales->save();
							$saved = true;
							
							//check workshop version active...
							if($this->module->is_active==1 && $this->quotation_sales->id) {
								$this->setJobdetails($attributes);
							}
							
						}
					} catch (\Illuminate\Database\QueryException $ex) {

						// Check if it's a duplicate voucher number error
						if (strpos($ex->getMessage(), 'Duplicate entry') !== false || strpos($ex->getMessage(), 'duplicate key value') !== false) {

							$dept = isset($attributes['department_id'])?$attributes['department_id']:0;

							// 2️⃣ Get the highest numeric part from voucher_master
							$qry = DB::table('quotation_sales')->where('deleted_at', '0000-00-00 00:00:00')->where('status', 1);
							if($dept > 0)	
								$qry->where('department_id', $dept);

							$maxNumeric = $qry->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))->value('max_no');
							
							$attributes['voucher_no'] = $this->objUtility->generateVoucherNoDoc('QS', $maxNumeric, $dept, $attributes['voucher_no']);

							$retryCount++;
						} else {
							throw $ex; // rethrow if different DB error
						}
					}
				}
				
				
				$line_total = 0; $tax_total = 0; $total = $item_total = 0; $taxtype = '';
				$discount = (isset($attributes['discount']))?$attributes['discount']:0;
				
				//quotation sales items insert
				if($this->quotation_sales->id && !empty( array_filter($attributes['item_id']))) {
					
					
					//calculate total amount....2
					if($discount > 0) 
						$total = $this->calculateTotalAmount($attributes);
					
					foreach($attributes['item_id'] as $key => $value) { 
						$purchaseOrderItem 		   = new QuotationSalesItem();
						$vat = $attributes['line_vat'][$key];
						$arrResult 			  	   = $this->setItemInputValue($attributes, $purchaseOrderItem, $key, $value, $total);
						if($arrResult['line_total']) {
							$line_total				  += $arrResult['line_total'];
							$tax_total      		  += $arrResult['tax_total'];
							$taxtype				  = $arrResult['type'];
							$item_total				 += $arrResult['item_total'];
								
							$purchaseOrderItem->status = 1;
							$itemObj = $this->quotation_sales->quotationItem()->save($purchaseOrderItem);
					$zero = DB::table('quotation_sales_item')->where('id', $itemObj->id)->where('unit_id',0)->first();
					    if($zero && $zero->item_id != 0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('quotation_sales_item')->where('id', $itemObj->id)->update(['unit_id' => $uid->unit_id]);
						}
							$this->setTransferStatusItem($attributes, $key);
							
							//item description section....
							/*if(isset($attributes['itemdesc'][$key])) {
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
							}*/
						}
						
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
					//echo $line_total.' - '.$discount;
					$subtotal =(int)$line_total - (int)$discount;
					
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
					
					#### REVICE QUOTATION COUNT UPDATE #####
					if(isset($attributes['is_revice'])) {
						DB::table('quotation_sales')->where('id',$attributes['parent_id'])->update(['jobnature' => DB::raw('jobnature + 1')]);
					}
					
					
					//update discount, total amount
					DB::table('quotation_sales')
								->where('id', $this->quotation_sales->id)
								->update([//'voucher_no' => $attributes['voucher_no'],
									      'total'      => $line_total,
										  'discount'   => (isset($attributes['discount']))?$attributes['discount']:0,
										  'vat_amount' => $tax_total,
										  'is_rental' => (isset($attributes['is_rental']))?$attributes['is_rental']:'', 
										  'net_total'  => $net_amount,
										  'total_fc'   => $total_fc,
										  'discount_fc'=> $discount_fc,
										  'net_total_fc' => $net_amount_fc,
										  'vat_amount_fc' => $tax_fc,
										  'subtotal'	  => $subtotal,
										  'subtotal_fc'	  => $subtotal_fc,
										  'footer_text'	  => isset($attributes['footer'])?$attributes['footer']:''
										 ]);
				//}
				
				//quotation info insert
				if($this->quotation_sales->id && isset($attributes['title']) && !empty( array_filter($attributes['title']))) {
					foreach($attributes['title'] as $key => $value) {
						$purchaseOrderInfo 			= new QuotationSalesInfo();
						if($this->setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)) {
							$purchaseOrderInfo->status = 1;
							$this->quotation_sales->quotationInfo()->save($purchaseOrderInfo);
						}
					}
				}
				
				$this->setTransferStatusQuote($attributes);
				
								
					if($this->quotation_sales->id && isset($attributes['photo_name'])) {
						
						if($attributes['photo_name']!='') {
							foreach($attributes['photo_name'] as $key => $val) {
								if($val!='') {
									DB::table('quot_fotos')
											->insert(['quot_id' => $this->quotation_sales->id, 
													  'photo' => $val,
													  'description'	=> $attributes['imgdesc'][$key]
													]);
								}
							}
						}
					}
					
				
			/*	if($this->quotation_sales->id && isset($attributes['photo_name'])) {
					//echo '<pre>';print_r(explode(',',$attributes['photo_name']));exit;
					
					//$photos = explode(',',$attributes['photo_name']);
					foreach($attributes['photo_name'] as $photo) {
						if($photo!='') {
							DB::table('quot_fotos')
									->insert(['quot_id' => $this->quotation_sales->id, 
											  'photo' => $photo,
											  'description'	=> $attributes['imgdesc'][$key]
											]);
						}
					}
				}*/

				DB::commit();
				return $this->quotation_sales->id;//true;
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().' - '.$e->getMessage();exit;
				return false;
			}
		}
		
	}
	
	public function update($id, $attributes)
	{
		$this->quotation_sales = $this->find($id);
		$line_total = $tax_total = $line_total_new = $tax_total_new = $item_total = $total = 0;
		
		DB::beginTransaction();
		try {
			
			//check workshop version active...
			if($this->module->is_active==1 && $this->quotation_sales->id) {
				$this->setJobdetailsUpdate($attributes);
			}
			//$attributes['salesman_id'] = (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):implode(',', $attributes['salesman_id']);
			if (Auth::check() && Auth::user()->hasRole('Salesman')) {

				$attributes['salesman_id'] = Session::get('salesman_id');

			} else {

				$attributes['salesman_id'] = collect($attributes['salesman_id'] ?? [])
					->filter()
					->implode(',');
			}

			$discount = (isset($attributes['discount']))?$attributes['discount']:0; $taxtype = '';
			$lineTotal = $this->calculateTotalAmount($attributes);
			if($this->quotation_sales->id && !empty( array_filter($attributes['item_id']))) {
				
				foreach($attributes['item_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
							$deskey = $attributes['order_item_id'][$key];
							$tax_code = (isset($attributes['is_export']))?"ZR":$attributes['tax_code'][$key];
							
							if( isset($attributes['is_fc']) ) {
									$tax        = ( ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100) * $attributes['currency_rate'];
									$itemtotal = ( ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key] ) * $attributes['currency_rate'];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
									$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) * $attributes['currency_rate'];
									
							} else {
								
								$linetotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]);
								
								if(isset($attributes['is_export']) || $tax_code=="EX" || $tax_code=="ZR") {
									
									$tax        = 0;
									$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
									$taxtotal  = round($tax * $attributes['quantity'][$key],2);
									
								} else if($attributes['tax_include'][$key]==1){
									
									$ln_total   = $attributes['cost'][$key] * $attributes['quantity'][$key];
									$taxtotal  = $ln_total *  $attributes['line_vat'][$key] / (100 +  $attributes['line_vat'][$key]);
									$itemtotal = $ln_total - $taxtotal;
									
								} else {
									
									$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
									$itemtotal = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - (int)$attributes['line_discount'][$key];
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
						//	$is_rental = (isset($attributes['is_rental']))?$attributes['is_rental']:''; 
										
							$quotationSalesItem = QuotationSalesItem::find($attributes['order_item_id'][$key]);
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
							$items['orderno'] = isset($attributes['orderno'][$key])?$attributes['orderno'][$key]:''; //JN23
							$quotationSalesItem->update($items);
							
							$zero = DB::table('quotation_sales_item')->where('id', $attributes['order_item_id'][$key])->where('unit_id',0)->first();
						if($zero && $zero->item_id !=0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('quotation_sales_item')->where('id', $attributes['order_item_id'][$key])->update(['unit_id' => $uid->unit_id]);
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
							}*/

							//manage removed item description...
							if(isset($attributes['remove_itemdesc']) && isset($attributes['remove_itemdesc'][$key]) && $attributes['remove_itemdesc'][$key]!='')
							{
								$arrids = explode(',', $attributes['remove_itemdesc'][$key]);
								foreach($arrids as $row) {
									DB::table('item_description')->where('id', $row)->update(['status' => 0,'deleted_at' => date('Y-m-d h:i:s')]);
								}
							}
							
							
						} else { //new entry...
							$item_total_new = $tax_total_new = $item_total_new = 0;
							if($discount > 0) 
								$total = $this->calculateTotalAmount($attributes);
							
							$vat = $attributes['line_vat'][$key];
							$quotationSalesItem = new QuotationSalesItem();
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
								$itemObj = $this->quotation_sales->quotationItem()->save($quotationSalesItem);
					$zero = DB::table('quotation_sales_item')->where('id', $itemObj->id)->where('unit_id',0)->first();
					    if($zero && $zero->item_id !=0){
						     $uid=  DB::table('item_unit')->where('itemmaster_id', $zero->item_id)->first();
						     DB::table('quotation_sales_item')->where('id', $itemObj->id)->update(['unit_id' => $uid->unit_id]);
						}
								//new entry description.........
								/*if(isset($attributes['itemdesc'][$key])) {
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
								}*/
							}
						}
						
					}
				}
				
				
				//check workshop version active...
				if($this->matservice->is_active==1 && $this->quotation_sales->id) {
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
							DB::table('quotation_sales_item')->where('id', $row)->update(['status' => 0,'deleted_at' => date('Y-m-d H:i:s')]);
						}
					}
				}
				
				//DEC22   quotation info insert
				if(isset($attributes['infoid'])) {
					foreach($attributes['infoid'] as $key => $value) {
						if($value=='') {
							$purchaseOrderInfo = new QuotationSalesInfo();
							if($this->setInfoInputValue($attributes, $purchaseOrderInfo, $key, $value)) {
								$purchaseOrderInfo->status = 1;
								$this->quotation_sales->quotationInfo()->save($purchaseOrderInfo);
							}
						} else {
							DB::table('quotation_sales_info')->where('id',$value)->update(['title' => $attributes['title'][$key], 'description' => $attributes['desc'][$key]]);
						}
					}
				}
				
				//manage removed items...
				if($attributes['remove_item']!='')
				{
					$arrids = explode(',', $attributes['remove_item']);
					$remline_total = $remtax_total = 0;
					foreach($arrids as $row) {
						DB::table('quotation_sales_item')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
					}
				}
				
				$this->quotation_sales->voucher_date  = ($attributes['voucher_date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['voucher_date']));
				$this->quotation_sales->items_description=isset($attributes['items_description'])?$attributes['items_description']:'';
				$this->quotation_sales->fill($attributes)->save();
				
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
				DB::table('quotation_sales')
							->where('id', $this->quotation_sales->id)
							->update(['total'    	  => $line_total,
									  'discount' 	  => isset($attributes['discount'])?$attributes['discount']:'',
									  'is_rental' => (isset($attributes['is_rental']))?$attributes['is_rental']:'',
									  'vat_amount'	  => $tax_total,
									  'net_total'	  => $net_amount,
									  'total_fc' 	  => $total_fc,
									  'discount_fc'   => $discount_fc,
									  'vat_amount_fc' => $tax_fc,
									  'net_total_fc'  => $net_amount_fc,
									  'modify_at' 	  => date('Y-m-d H:i:s'),
									  'subtotal'	  => $subtotal, //CHG
									  'subtotal_fc'	  => $subtotal_fc,
									  'footer_text'	  => isset($attributes['footer'])?$attributes['footer']:'',
									  'doc_status' 	  => (isset($attributes['doc_status']))?$attributes['doc_status']:'',
									  'metre_in'	  => isset($attributes['metre_in'])?$attributes['metre_in']:'',
									  'metre_out'	  => isset($attributes['metre_out'])?$attributes['metre_out']:'',
									  'comment'		  => (isset($attributes['comment']))?$attributes['comment']:((isset($attributes['comment_hd']))?$attributes['comment_hd']:'')
									  ]); //CHG
									  
				
			/*	$photos = [];
				$phoo =  isset($attributes['photo_name'])?$attributes['photo_name']:'' ;
				$old_pho = isset($attributes['old_photo_name'])?$attributes['old_photo_name']:'' ;
				$rem_phot = isset($attributes['rem_photo_name'])?$attributes['rem_photo_name']:'' ;
				if(isset($phoo)) {
					$photos = explode(',',isset($attributes['photo_name']));
				}
			
				//Update photos...Input::get('old_photo_name')
				if(isset($old_pho) && $old_pho!='') {
					
					$exi_photos = explode(',',$attributes['old_photo_name']);
					
					foreach($photos as $ky => $val) {
						if(isset($exi_photos[$ky])) {
							if($val!='') {
								DB::table('quot_fotos')
										->where('quot_id', $id)
										->where('photo', $photos[$ky])
										->update(['photo' => $val, 'description'=> $attributes['imgdesc'][$key]]);
							}
						} else {
							DB::table('quot_fotos')->insert(['quot_id' => $id, 'photo' => $val,'description'=> $attributes['imgdesc'][$key]]);
						}
					}
					
				} else { //Add photos
					foreach($photos as $photo) {
						if($photo!='')
							DB::table('quot_fotos')->insert(['quot_id' => $id, 'photo' => $photo,'description'=> $attributes['imgdesc'][$key]]);
					}
				}
				
				
				//Remove photos Input::get('rem_photo_name')
				if(isset($rem_phot)) {
					$rem_photos = explode(',',isset($attributes['rem_photo_name']));
					foreach($rem_photos as $photo) {
						DB::table('quot_fotos')->where('quot_id',$id)
									->where('photo', $photo)
									->delete();
									
						$fPath = public_path() . $this->imgDir.'/'.$photo;
						File::delete($fPath);
					}
				}*/
				
				//Photos
				
					if(isset($attributes['photo_id'])) {
					
				foreach($attributes['photo_id'] as $key => $val) {
					
					//UPDATE...
					if($val!='') {
						DB::table('quot_fotos')
							->where('id', $val)
							->update(['photo' =>  $attributes['photo_name'][$key],
									  'description'	=> $attributes['imgdesc'][$key]
									]);
									
					} else { 
						//ADD NEW..
						DB::table('quot_fotos')
							->insert(['quot_id' => $this->quotation_sales->id, 
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
									DB::table('quot_fotos')
											->insert(['quot_id' => $this->quotation_sales->id, 
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
					$rec = DB::table('quot_fotos')->find($id);
					DB::table('quot_fotos')->where('id', $id)->delete();
					
					if($rec->photo!='') {			
						$fPath = public_path() . $this->imgDir.'/'.$rec->photo;
						File::delete($fPath);
					}
				}
			}
				
				
				//DEC22  UPDATED REMOVE INFO...
				if(isset($attributes['remove_info']) && $attributes['remove_info']!='')
				{
					$arrids = explode(',', $attributes['remove_info']);
					
					foreach($arrids as $row) {
						DB::table('quotation_sales_info')->where('id', $row)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
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
		$this->quotation_sales = $this->quotation_sales->find($id);
		$this->quotation_sales->delete();
	}
	
	public function quotationSalesList()
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','quotation_sales.vehicle_id');
						} )
					->select('quotation_sales.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->orderBY('quotation_sales.id', 'DESC')
					->get();
	}
	
	public function getCustomerQuotation($customer_id=null)
	{
		 $qry = $this->quotation_sales
								->leftJoin('vehicle AS V', function($join) {
									$join->on('V.id','=','quotation_sales.vehicle_id');
								} )
							 ->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								} )
							 ->where('quotation_sales.status', 1);
							 
				if($customer_id)
					$qry->where('quotation_sales.customer_id', $customer_id);
					
				return	$qry->where('quotation_sales.is_transfer', 0)
							 ->select('quotation_sales.id','quotation_sales.voucher_no','quotation_sales.voucher_date','V.reg_no',
							 'V.name AS vehicle','quotation_sales.prefix','quotation_sales.net_total','S.name')
							 ->get();
		
	}
	
		
	public function findQuoteData($id)
	{
		$query = $this->quotation_sales->where('quotation_sales.id', $id)->where('quotation_sales.is_transfer', 0);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','quotation_sales.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','quotation_sales.footer_id');
					})
					->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','quotation_sales.vehicle_id');
						} )
						->leftJoin('jobmaster AS J', function($join) {
							$join->on('J.id','=','quotation_sales.job_id');
						} )
					->leftJoin('salesman AS S', function($join) {
							$join->on('S.id','=','quotation_sales.salesman_id');
						} )
					->select('quotation_sales.*','am.master_name AS customer','h.title AS header','f.title AS footer','V.reg_no',
							 'V.name AS vehicle','V.make','V.model','S.name AS salesman','V.issue_plate','V.code_plate','V.chasis_no','J.code')
					->orderBY('quotation_sales.id', 'ASC')
					->first();
	}
	
	public function activeQuotationSalesList()
	{
		return $this->quotation_sales->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->quotation_sales->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->quotation_sales->where('reference_no',$refno)->count();
	}
		
	public function getQuotationReport($attributes) 
	{
		$result = array();
		
		if($attributes['date_from']!=null && $attributes['date_to']!=null) {
			$date_from = (isset($attributes['date_from']))?date('Y-m-d', strtotime($attributes['date_from'])):'';
			$date_to = (isset($attributes['date_to']))?date('Y-m-d', strtotime($attributes['date_to'])):'';
			
			$result = $this->quotation_sales->where('quotation_sales.status',1)
								    ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','quotation_sales.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','quotation_sales.job_id');
								   })
								   ->whereBetween('voucher_date', array($date_from, $date_to))
								   ->select('AM.master_name AS supplier','quotation_sales.*','JM.name AS job')
								   ->orderBY('id', 'ASC')
								   ->get();
		} else {
			$result = $this->quotation_sales->where('quotation_sales.status',1)
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','quotation_sales.customer_id');
								   })
								   ->leftJoin('jobmaster AS JM', function($join) {
									   $join->on('JM.id','=','quotation_sales.job_id');
								   })
								   ->select('AM.master_name AS supplier','quotation_sales.*','JM.name AS job')
								   ->orderBY('quotation_sales.id', 'ASC')
								   ->get();
		}
		
		return $result; 
	}
	
	public function getQuotation($attributes)
	{
		$order = $this->quotation_sales->where('quotation_sales.id', $attributes['document_id'])
								   ->join('account_master AS AM', function($join) {
									   $join->on('AM.id','=','quotation_sales.customer_id');
								   })
								   ->leftJoin('currency AS C', function($join) {
									   $join->on('C.id','=','quotation_sales.currency_id');
								   })
								   ->leftJoin('header_footer AS F', function($join) {
									   $join->on('F.id','=','quotation_sales.footer_id');
								   })
								   ->leftJoin('header_footer AS H', function($join) {
									   $join->on('H.id','=','quotation_sales.header_id');
								   })
								   ->leftJoin('vehicle AS V', function($join) {
									   $join->on('V.id','=','quotation_sales.vehicle_id');
								   })
								    ->leftJoin('salesman AS SL', function($join) {
									   $join->on('SL.id','=','quotation_sales.salesman_id');
								   })
								   ->leftJoin('terms AS T', function($join) {
									   $join->on('T.id','=','quotation_sales.terms_id');
								   })
								   ->select('AM.account_id','AM.master_name AS supplier','quotation_sales.*','AM.address','AM.city','H.description AS header','T.description AS terms',
											'AM.state','AM.contact_name','AM.vat_no','AM.phone AS custphone','C.name AS currency','F.description AS footer','V.*','SL.name AS salesman'
											)
								   ->orderBY('quotation_sales.id', 'ASC')
								   ->first();
								   
		$items = $this->quotation_sales->where('quotation_sales.id', $attributes['document_id'])
								   ->join('quotation_sales_item AS PI', function($join) {
									   $join->on('PI.quotation_sales_id','=','quotation_sales.id');
								   })
								   ->join('itemmaster AS IM', function($join) {
									   $join->on('IM.id','=','PI.item_id');
								   })
								   ->join('units AS U', function($join) {
									   $join->on('U.id','=','PI.unit_id');
								   })
								   ->leftjoin('groupcat AS G', function($join) {
									   $join->on('G.id','=','IM.group_id');
								   })
								   ->where('PI.status',1)
								   ->where('PI.deleted_at','0000-00-00 00:00:00')
								   ->select('PI.*','IM.item_code','U.unit_name','IM.image','G.group_name')
								   ->orderBY('PI.id')
								   ->get();
								   
		return $result = ['details' => $order, 'items' => $items];
	}
	public function findPOdata($id)
	{
		$query = $this->quotation_sales->where('quotation_sales.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
						->leftJoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','quotation_sales.job_id');
						})
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','quotation_sales.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','quotation_sales.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','quotation_sales.salesman_id');
					})
					->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','quotation_sales.vehicle_id');
					})
				
					->select('quotation_sales.*','am.master_name AS customer','h.title AS header','V.make','V.model','am.email','J.name',
							 'f.description AS footer','S.name AS salesman','h.id AS hid','f.id AS fid','V.name AS vehicle','V.reg_no','V.chasis_no','V.issue_plate','V.code_plate')
					->first();
	}
	
	public function findPOdataold($id)
	{
		$query = $this->quotation_sales->where('quotation_sales.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','quotation_sales.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','quotation_sales.footer_id');
					})
					->leftJoin('salesman AS S',function($join) {
						$join->on('S.id','=','quotation_sales.salesman_id');
					})
					->leftJoin('vehicle AS V',function($join) {
						$join->on('V.id','=','quotation_sales.vehicle_id');
					})
					->select('quotation_sales.*','am.master_name AS customer','h.title AS header','V.make','V.model','am.email',
							 'f.description AS footer','S.name AS salesman','h.id AS hid','f.id AS fid','V.name AS vehicle','V.reg_no')
					->first();
	}
	
	public function getItems($id,$mod=null)
	{
		$query = $this->quotation_sales->where('quotation_sales.id',$id);
		
		$query->join('quotation_sales_item AS poi', function($join) {
							$join->on('poi.quotation_sales_id','=','quotation_sales.id');
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
					  ->orderBY('poi.orderno') //JN23
					  ->get();
	}
	
	public function getQSItems($id)
	{
		$query = $this->quotation_sales->whereIn('quotation_sales.id',$id);
		
		return $query->join('quotation_sales_item AS poi', function($join) {
							$join->on('poi.quotation_sales_id','=','quotation_sales.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','poi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','poi.item_id');
					  })
					  ->leftjoin('item_unit AS iu', function($join){
						  $join->on('iu.itemmaster_id','=','im.id');
						  $join->on('iu.unit_id','=','poi.unit_id');
					  })
					  ->where('poi.status',1)
					  ->whereIn('poi.is_transfer',[0,2])
					  ->where('poi.deleted_at','0000-00-00 00:00:00')
					  ->select('poi.*','u.unit_name','im.item_code','iu.is_baseqty','iu.cur_quantity')
					  ->orderBY('poi.id')->groupBy('poi.id')
					  ->get();
					  
		
	}
	
	public function getItemDesc($id)
	{
		return DB::table('quotation_sales')
						->join('quotation_sales_item AS QSI', function($join) {
							$join->on('QSI.quotation_sales_id', '=', 'quotation_sales.id');
						})
						->join('item_description AS D', function($join) {
							$join->on('D.item_detail_id', '=', 'QSI.id');
						})
						->where('quotation_sales.id', $id)
						->where('D.invoice_type','QS')
						->where('QSI.status',1)
						->where('QSI.deleted_at','0000-00-00 00:00:00')
						->where('D.status',1)
						->where('D.deleted_at','0000-00-00 00:00:00')
						->select('D.*')
						->get();
	}
	public function getPendingReportrent($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->quotation_sales->where('is_rental',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						 
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','S.name AS salesman',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','quotation_sales.discount')
								->groupBy('quotation_sales.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)->where('is_rental',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						 
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','quotation_sales.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','S.name AS salesman','QSI.vat_amount AS unit_vat')
								->get(); //->groupBy('quotation_sales.id')
								
				break;
				
			case 'detail':
				$query = $this->quotation_sales->where('is_rental',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','IM.item_code','IM.description','S.name AS salesman','QSI.vat_amount AS unit_vat',
									  'QSI.quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','quotation_sales.discount')
								->get();
				break;
				
			case 'detail_pending':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)->where('is_rental',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','IM.item_code','IM.description','quotation_sales.total','quotation_sales.vat_amount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','S.name AS salesman','quotation_sales.discount')
								->get();
				break;
		}
	}	

	public function getPendingReport($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		switch($attributes['search_type']) {
			case 'summary':
				$query = $this->quotation_sales->where('is_rental',0)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','quotation_sales.job_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','S.name AS salesman',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','quotation_sales.discount','J.code AS jobcode')
								->groupBy('quotation_sales.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)->where('is_rental',0)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','quotation_sales.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','S.name AS salesman','QSI.vat_amount AS unit_vat')
								->get(); //->groupBy('quotation_sales.id')
								
				break;
				
			case 'detail':
				$query = $this->quotation_sales->where('is_rental',0)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.voucher_date','quotation_sales.reference_no','IM.item_code','IM.description','S.name AS salesman','QSI.vat_amount AS unit_vat',
									  'QSI.quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','quotation_sales.discount')
								->get();
				break;
				
			case 'detail_pending'||'qty_report':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)->where('is_rental',0)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->where('QSI.status',1);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.voucher_date','quotation_sales.reference_no','IM.item_code','IM.description','quotation_sales.total','quotation_sales.vat_amount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','S.name AS salesman','quotation_sales.discount')
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
				$query = $this->quotation_sales
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->leftJoin('jobmaster AS J', function($join) {
									$join->on('J.id','=','quotation_sales.job_id');
								})
								->leftJoin('vehicle AS V', function($join) {
							       $join->on('V.id','=','quotation_sales.vehicle_id');
						         })
								->where('QSI.status',1)
								->where('quotation_sales.is_rental',2);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
							
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }	
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','S.name AS salesman',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','quotation_sales.discount','J.code AS jobcode')
								->groupBy('quotation_sales.id')->get();
				break;
				
			case 'summary_pending':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
							       $join->on('V.id','=','quotation_sales.vehicle_id');
						         })
								->where('QSI.status',1)->where('quotation_sales.is_rental',2);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }	
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
						$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.reference_no','quotation_sales.total','quotation_sales.vat_amount','quotation_sales.discount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','AM.master_name','quotation_sales.net_total','S.name AS salesman','QSI.vat_amount AS unit_vat')
								->get(); //->groupBy('quotation_sales.id')
								
				break;
				
			case 'detail':
				$query = $this->quotation_sales
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
								->leftJoin('vehicle AS V', function($join) {
							       $join->on('V.id','=','quotation_sales.vehicle_id');
						         })
								->where('QSI.status',1)->where('quotation_sales.is_rental',2);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
							if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }	
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.voucher_date','quotation_sales.reference_no','IM.item_code','IM.description','S.name AS salesman','QSI.vat_amount AS unit_vat',
									  'QSI.quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','quotation_sales.discount')
								->get();
				break;
				
			case 'detail_pending'||'qty_report':
				$query = $this->quotation_sales->where('QSI.is_transfer','!=',1)
								->join('quotation_sales_item AS QSI', function($join) {
									$join->on('QSI.quotation_sales_id','=','quotation_sales.id');
								})
								->join('account_master AS AM', function($join) {
									$join->on('AM.id','=','quotation_sales.customer_id');
								})
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','QSI.item_id');
								})
								->leftJoin('salesman AS S', function($join) {
									$join->on('S.id','=','quotation_sales.salesman_id');
								})
									->leftJoin('vehicle AS V', function($join) {
							       $join->on('V.id','=','quotation_sales.vehicle_id');
						         })
								->where('QSI.status',1)->where('quotation_sales.is_rental',2);
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('quotation_sales.voucher_date', array($date_from, $date_to));
						}
						
						if($attributes['salesman']!='') { 
							$query->where('quotation_sales.salesman_id', $attributes['salesman']);
						}
						if($attributes['vehicle_no']!='') { 
					       $query->where('V.reg_no', $attributes['vehicle_no']);
				           }	
						if(isset($attributes['customer_id']) && $attributes['customer_id']!=''){
							$query->where('quotation_sales.customer_id', $attributes['customer_id']);	
						} 
						if(isset($attributes['job_id']) && $attributes['job_id']!='')
							$query->whereIn('quotation_sales.job_id', $attributes['job_id']);
				return $query->select('quotation_sales.voucher_no','quotation_sales.voucher_date','quotation_sales.reference_no','IM.item_code','IM.description','quotation_sales.total','quotation_sales.vat_amount',
									  'QSI.quantity','QSI.balance_quantity','QSI.unit_price','QSI.line_total','AM.master_name','quotation_sales.net_total','S.name AS salesman','quotation_sales.discount')
								->get();
				break;
		}
	}
	
	
	public function check_voucher_no($refno, $id = null) { 
		
		if($id)
			return $this->quotation_sales->where('voucher_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->quotation_sales->where('voucher_no',$refno)->count();
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
	
	
	
	public function getjobDescription($id)
	{
		return DB::table('jobestimate_details')->where('jobestimate_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
	}
	public function jobEstimateList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('quotation_sales.is_rental',2)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','quotation_sales.vehicle_id');
						} );
						
				if($search) {
					$query->where('quotation_sales.voucher_no','LIKE',"%{$search}%")
					->orWhere('quotation_sales.reference_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
			
				$query->select('quotation_sales.*','am.master_name AS customer','V.reg_no','V.name AS vehicle','V.chasis_no',
								DB::raw('SUBSTRING_INDEX(quotation_sales.voucher_no, "/R-", 1) AS bin_name1'))
					->offset($start)
                    ->limit($limit);
					
				if(!$search)
					$query->orderBy($order,$dir);
				
					//->orderBy('bin_name1','DESC');
                    
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	public function jobEstimateListCount()
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('quotation_sales.is_rental',2);
		if(Auth::user()->roles[0]->name=='Salesman')
					$query->where('quotation_sales.salesman_id',Session::get('salesman_id'));  // for CRM
	
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->count();
	}
	public function salesEstimateListCount()
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('quotation_sales.is_rental',0);
		if(Auth::user()->roles[0]->name=='Salesman')
					$query->where('quotation_sales.salesman_id',Session::get('salesman_id'));  // for CRM
	
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->count();
	}
	public function salesEstimateListCountRental()
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('is_rental',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
					->count();
	}
	public function salesEstimateListRen($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('is_rental',1)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','quotation_sales.vehicle_id');
						} );
						
				if($search) {
					$query->where('quotation_sales.voucher_no','LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
				
				$query->select('quotation_sales.*','am.master_name AS customer','V.reg_no','V.name AS vehicle')
					->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir);
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	public function salesEstimateList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->quotation_sales->where('quotation_sales.status',1)->where('quotation_sales.is_rental',0)
						->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation_sales.customer_id');
						} )
						->leftJoin('vehicle AS V', function($join) {
							$join->on('V.id','=','quotation_sales.vehicle_id');
						} );
						
				if($search) {
					$query->where('quotation_sales.voucher_no','LIKE',"%{$search}%")
					->orWhere('quotation_sales.reference_no', 'LIKE',"%{$search}%")
					->orWhere('V.name', 'LIKE',"%{$search}%")
					->orWhere('V.reg_no', 'LIKE',"%{$search}%")
                          ->orWhere('am.master_name', 'LIKE',"%{$search}%");
				}
			
				$query->select('quotation_sales.*','am.master_name AS customer','V.reg_no','V.name AS vehicle',
								DB::raw('SUBSTRING_INDEX(quotation_sales.voucher_no, "/R-", 1) AS bin_name1'))
					->offset($start)
                    ->limit($limit);
					
				if(!$search)
					$query->orderBy($order,$dir);
				
					//->orderBy('bin_name1','DESC');
                    
					
				if($type=='get')
					return $query->get();
				else
					return $query->count();
	}
	

}

