<?php
declare(strict_types=1);
namespace App\Repositories\Division;

use App\Models\Division;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;

class DivisionRepository extends AbstractValidator implements DivisionInterface {
	
	protected $division;
	
	protected static $rules = [
		//'code' => 'required|unique:division',
		//'name' => 'required|unique:division',
	];
	
	public function __construct(Division $division) {
		$this->division = $division;
		
	}
	
	public function all()
	{
		return $this->division->get();
	}
	
	public function find($id)
	{
		return $this->division->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			$this->division->div_code = $attributes['div_code'];
			$this->division->div_name = $attributes['div_name'];
			$this->division->status = 1;
			$this->division->fill($attributes)->save();
			return true;
		}
		
		//throw new ValidationException('division validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->division = $this->find($id);
		$this->division->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->division = $this->division->find($id);
		$this->division->delete();
	}
	
	public function divisionList()
	{
		//check admin session and apply return $this->division->where('parent_id',0)->where('status', 1)->get();
		return $this->division->get();
	}
	
	public function activeDivisionList()
	{
		return $this->division->select('id','div_name')->where('status', 1)->orderBy('div_name', 'ASC')->get()->toArray();
	}
	
	public function check_division_code($div_code, $id = null) {
		
		if($id)
			return $this->division->where('div_code',$div_code)->where('id', '!=', $id)->count();
		else
			return $this->division->where('div_code',$div_code)->count();
	}
		
	public function check_division_name($div_name, $id = null) {
		
		if($id)
			return $this->division->where('div_name',$div_name)->where('id', '!=', $id)->count();
		else
			return $this->division->where('div_name',$div_name)->count();
	}
	
}

