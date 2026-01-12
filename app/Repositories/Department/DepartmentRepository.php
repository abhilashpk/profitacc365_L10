<?php
declare(strict_types=1);
namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;

class DepartmentRepository extends AbstractValidator implements DepartmentInterface {
	
	protected $department;
	
	protected static $rules = [
		//'code' => 'required|unique:department',
		//'name' => 'required|unique:department',
	];
	
	public function __construct(Department $department) {
		$this->department = $department;
		
	}
	
	public function all()
	{
		return $this->department->get();
	}
	
	public function find($id)
	{
		return $this->department->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			$this->department->code = $attributes['code'];
			$this->department->name = $attributes['name'];
			$this->department->status = 1;
			$this->department->fill($attributes)->save();
			return true;
		}
		
		//throw new ValidationException('department validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->department = $this->find($id);
		$this->department->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->department = $this->department->find($id);
		$this->department->delete();
	}
	
	public function departmentList()
	{
		//check admin session and apply return $this->department->where('parent_id',0)->where('status', 1)->get();
		return $this->department->where('status', 1)->get();
	}
	
	public function activeDepartmentList()
	{
		return $this->department->where('status', 1)->select('id','name')->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_department_code($code, $id = null) {
		
		if($id)
			return $this->department->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->department->where('code',$code)->count();
	}
		
	public function check_department_name($name, $id = null) {
		
		if($id)
			return $this->department->where('name',$name)->where('id', '!=', $id)->count();
		else
			return $this->department->where('name',$name)->count();
	}
	
}

