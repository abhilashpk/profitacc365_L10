<?php
declare(strict_types=1);
namespace App\Repositories\ItemUnit;

use App\Models\ItemUnit;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ItemUnitRepository extends AbstractValidator implements ItemUnitInterface {
	
	protected $itemunit;
	
	protected static $rules = [
		'item_code' => 'required',
	];
	
	public function __construct(ItemUnit $itemunit) {
		$this->itemunit = $itemunit;
	}
	
	public function all()
	{
		return $this->itemunit->get();
	}
	
	public function find($id)
	{
		return $this->itemunit->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			
			$this->itemunit->opn_quantity = $attributes['opn_quantity'];
			$this->itemunit->opn_cost = $attributes['opening_cost'];
			$this->itemunit->min_quantity = $attributes['min_quantity'];
			//$this->itemunit->max_quantity = $attributes['max_quantity'];
			$this->itemunit->reorder_level = $attributes['reorder_level'];
			$this->itemunit->weight = $attributes['weight'];
			$this->itemunit->sell_price = $attributes['sell_price'];
			$this->itemunit->wsale_price = $attributes['wsale_price'];
			$this->itemunit->vat = $attributes['vat'];
			
			return true;
		}
		
		//throw new ValidationException('itemunit validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->itemunit = $this->find($id);
		if($this->isValid($attributes, ['item_code' => 'required'])) {
			
			
			$this->itemunit->image = $image;
			$this->itemunit->fill($attributes)->save();
			return true;
		}
		//throw new ValidationException('ItemUnit validation error!', $this->getErrors());
	}
	
	
	public function delete($id)
	{
		$this->itemunit = $this->itemunit->find($id);
		$this->itemunit->delete();
	}
	
	public function itemunitList()
	{
		return $this->itemunit->get();
	}
	
	public function activeItemUnitList()
	{
		return $this->itemunit->where('status', 1)->orderBy('description','ASC')->select('id','item_code','description')->get();
	}
	
	public function itemunitView($id)
	{
		return $this->itemunit->where('id', $id);
	}
	
	public function check_item_code($item_code, $id = null) {
		
		if($id)
			return $this->itemunit->where('item_code',$item_code)->where('id', '!=', $id)->count();
		else
			return $this->itemunit->where('item_code',$item_code)->count();
	}
	
	public function check_item_description($description, $id = null) {
		
		if($id)
			return $this->itemunit->where('description',$description)->where('id', '!=', $id)->count();
		else
			return $this->itemunit->where('description',$description)->count();
	}
	
	public function getActiveItemUnitList()
	{
		$query = $this->itemunit->where('itemunit.status',1);
		
		return $query->join('units AS u', function($join) {
							$join->on('u.id','=','itemunit.unit_id');
						} )
						->orderBy('itemunit.description','ASC')
						->select('itemunit.id','itemunit.item_code','itemunit.description','u.unit_name')->get();
		//return $this->itemunit->where('status', 1)->orderBy('description','ASC')->select('id','item_code','description')->get();
	}

}

