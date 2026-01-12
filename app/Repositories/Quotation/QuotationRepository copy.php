<?php
declare(strict_types=1);
namespace App\Repositories\Quotation;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationInfo;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class QuotationRepository extends AbstractValidator implements QuotationInterface {
	
	protected $quotation;
	
	protected static $rules = [];
	
	public function __construct(Quotation $quotation) {
		$this->quotation = $quotation;
		
	}
	
	public function all()
	{
		return $this->quotation->get();
	}
	
	public function find($id)
	{
		return $this->quotation->where('id', $id)->first();
	}
	
	//set input fields values
	private function setInputValue($attributes)
	{
		$this->quotation->voucher_no = $attributes['voucher_no'];
		$this->quotation->reference_no = $attributes['reference_no'];
		$this->quotation->description = $attributes['description'];
		$this->quotation->voucher_date = date('Y-m-d', strtotime($attributes['voucher_date']));
		//$this->quotation->terms_id = $attributes['terms_id'];
		$this->quotation->job_id = $attributes['job_id'];
		$this->quotation->supplier_id = $attributes['supplier_id'];
		$this->quotation->header_id = $attributes['header_id'];
		$this->quotation->footer_id = $attributes['footer_id'];
					
		return true;
	}
	
	private function setItemInputValue($attributes, $quotationItem, $key, $value) 
	{
		$tax        = ($attributes['cost'][$key] * $attributes['line_vat'][$key]) / 100;
		$line_total = ($attributes['cost'][$key] * $attributes['quantity'][$key]) - $attributes['line_discount'][$key];
		$tax_total  = $tax * $attributes['quantity'][$key];
		
		$quotationItem->quotation_id 	   = $this->quotation->id;
		$quotationItem->item_id    		   = $value;
		$quotationItem->item_name  		   = $attributes['item_name'][$key];
		$quotationItem->unit_id   		   = $attributes['unit_id'][$key];
		$quotationItem->quantity   		   = $attributes['quantity'][$key];
		$quotationItem->unit_price 		   = $attributes['cost'][$key];
		$quotationItem->vat		   		   = $attributes['line_vat'][$key];
		$quotationItem->vat_amount 		   = $tax_total;
		$quotationItem->discount   		   = $attributes['line_discount'][$key];
		$quotationItem->line_total 		   = $line_total;
		
		return array('line_total' => $line_total, 'tax_total' => $tax_total);
	}
	
	private function setInfoInputValue($attributes, $quotationInfo, $key, $value)
	{
		$quotationInfo->quotation_id = $this->quotation->id;
		$quotationInfo->title 			   = $value;
		$quotationInfo->description 	   = $attributes['desc'][$key];
		return true;
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			if($this->setInputValue($attributes)) {
				$this->quotation->status = 1;
				$this->quotation->created_at = now();
				$this->quotation->created_by = 1;
				$this->quotation->fill($attributes)->save();
			}
			
			//quotation sales items insert
			if($this->quotation->id && !empty( array_filter($attributes['item_id']))) {
				$line_total = 0; $tax_total = 0;
				foreach($attributes['item_id'] as $key => $value){ 
					$quotationItem 		   	   = new QuotationItem();
					$arrResult 			  	   = $this->setItemInputValue($attributes, $quotationItem, $key, $value);
					if($arrResult['line_total']) {
						$line_total				  += $arrResult['line_total'];
						$tax_total      		  += $arrResult['tax_total'];
						$quotationItem->status = 1;
						$this->quotation->quotationItemAdd()->save($quotationItem);
					}
					
				}
				
				$net_amount = $line_total + $tax_total - $attributes['discount'];
				//update discount, total amount
				DB::table('quotation')
							->where('id', $this->quotation->id)
							->update(['total'      => $line_total,
									  'discount'   => $attributes['discount'],
									  'vat_amount' => $tax_total,
									  'net_amount'  => $net_amount]);
			}
			
			//quotation info insert
			if($this->quotation->id && !empty( array_filter($attributes['title']))) {
				foreach($attributes['title'] as $key => $value) {
					$quotationInfo 			= new QuotationInfo();
					if($this->setInfoInputValue($attributes, $quotationInfo, $key, $value)) {
						$quotationInfo->status = 1;
						$this->quotation->quotationInfoAdd()->save($quotationInfo);
					}
				}
			}
			
			if($this->quotation->id) {
				 DB::table('voucher_no')
					->where('voucher_type', 'QP')
					->update(['no' => DB::raw('no + 1')]);
			}
			
			return true;
		}
		//throw new ValidationException('quotation validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->quotation = $this->find($id);
		$this->quotation->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->quotation = $this->quotation->find($id);
		$this->quotation->delete();
	}
	
	public function purchaseOrderList()
	{
		$query = $this->quotation->where('quotation.status',1);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation.supplier_id');
						} )
					->select('quotation.*','am.master_name AS supplier')
					->orderBY('quotation.id', 'DESC')
					->get();
	}
	
	public function activeQuotationList()
	{
		return $this->quotation->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_reference_no($refno, $id = null) { 
		
		if($id)
			return $this->quotation->where('reference_no',$refno)->where('id', '!=', $id)->count();
		else
			return $this->quotation->where('reference_no',$refno)->count();
	}
		
	public function findQuotation($id)
	{
		$query = $this->quotation->where('quotation.id', $id);
		return $query->join('account_master AS am', function($join) {
							$join->on('am.id','=','quotation.supplier_id');
						} )
					->leftJoin('header_footer AS h',function($join) {
						$join->on('h.id','=','quotation.header_id');
					})
					->leftJoin('header_footer AS f',function($join) {
						$join->on('f.id','=','quotation.footer_id');
					})
					->select('quotation.*','am.master_name AS supplier','h.title AS header','f.title AS footer')
					->orderBY('quotation.id', 'ASC')
					->first();
	}
	
	public function getQuotationItems($id)
	{
		$query = $this->quotation->where('quotation.id',$id);
		
		return $query->join('quotation_item AS qi', function($join) {
							$join->on('qi.quotation_id','=','quotation.id');
						} )
					  ->join('units AS u', function($join){
						  $join->on('u.id','=','qi.unit_id');
					  }) 
					  ->join('itemmaster AS im', function($join){
						  $join->on('im.id','=','qi.item_id');
					  })
					  ->select('qi.*','u.unit_name','im.item_code')->get();
	}
	
	
}

