<?php
declare(strict_types=1);
namespace App\Repositories\Parameter2;

use App\Models\Parameter2;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Parameter2Repository extends AbstractValidator implements Parameter2Interface {
	
	protected $parameter2;
	
	protected static $rules = [];
	
	public function __construct(Parameter2 $parameter2) {
		$this->parameter2 = $parameter2;

	}
	
	public function all()
	{
		
	}
	
	public function paginate($page = 1, $limit = 10, $all = false)
	{
		
	}
	
	public function find($id)
	{
		return $this->parameter2->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		
	}
		
	public function update($id = null, $parameter= null)
	{
	  //  echo '<pre>';print_r($parameter);exit;
		$rows = $this->getParameter2();//echo '<pre>';print_r($rows);exit;
	    $para2=	isset($parameter['para_name'])?$parameter['para_name']:'';
	foreach($rows as $row) {
			$this->parameter2 = $this->find($row->id); 
			if($para2 !=''){
			if(array_key_exists($row->id, $parameter['para_name'])) { 
				$this->parameter2->is_active = 1;
				$this->parameter2->save();
			} else { 
				$this->parameter2->is_active = 0;
				$this->parameter2->save();
			}
			}else{
			   	$this->parameter2->is_active = 0;
				$this->parameter2->save(); 
			}
		}
		return true;
	}
	public function updateold($id = null, $parameter)
	{	
		$rows = $this->getParameter2();//echo '<pre>';print_r($rows);exit;
		foreach($rows as $row) {
			$this->parameter2 = $this->find($row->id); 
			if(array_key_exists($row->id, $parameter['para_name'])) { 
				$this->parameter2->is_active = 1;
				$this->parameter2->save();
			} else { 
				$this->parameter2->is_active = 0;
				$this->parameter2->save();
			}
		}
		return true;
	}
	
	public function delete($id)
	{
			
	}

	public function getParameter2()
	{
		return $this->parameter2->where('status', 1)->get();
	}
	
	
}

