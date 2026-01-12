<?php
declare(strict_types=1);
namespace App\Repositories\Parameter4;

use App\Models\Parameter4;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Parameter4Repository extends AbstractValidator implements Parameter4Interface {
	
	protected $parameter4;
	
	protected static $rules = [];
	
	public function __construct(Parameter4 $parameter4) {
		$this->parameter4 = $parameter4;
	}
	
	public function all()
	{
		
	}
	
	public function paginate($page = 1, $limit = 10, $all = false)
	{
		
	}
	
	public function find($id)
	{
		return $this->parameter4->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		
	}
	
	public function update($id, $parameter4)
	{
		$this->parameter4 = $this->find($id);
		$this->parameter4->payroll_by = $parameter4['payroll_by'];
		$this->parameter4->nwh = $parameter4['nwh'];
		$this->parameter4->ot_general = $parameter4['ot_general'];
		$this->parameter4->ot_holiday = $parameter4['ot_holiday'];
		$this->parameter4->holiday = (isset($parameter4['holiday']))?implode(',',$parameter4['holiday']):' ';
		$this->parameter4->ot_calculation = (isset($parameter4['ot_calculation']))?implode(',',$parameter4['ot_calculation']):1;
		$this->parameter4->save();
		return true;
	}
	
	public function delete($id)
	{
			
	}

	public function getParameter4()
	{
		return $this->parameter4->first();
	}
	
	
}

