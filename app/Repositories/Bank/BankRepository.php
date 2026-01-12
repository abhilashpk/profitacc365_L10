<?php
declare(strict_types=1);
namespace App\Repositories\Bank;

use App\Models\Bank;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;

class BankRepository extends AbstractValidator implements BankInterface {
	
	protected $bank;
	
	protected static $rules = [
		//'code' => 'required|unique:bank',
		//'name' => 'required|unique:bank',
	];
	
	public function __construct(Bank $bank) {
		$this->bank = $bank;
		
	}
	
	public function all()
	{
		return $this->bank->get();
	}
	
	public function find($id)
	{
		return $this->bank->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			$this->bank->code = $attributes['code'];
			$this->bank->name = $attributes['name'];
			$this->bank->status = 1;
			$this->bank->fill($attributes)->save();
			return true;
		}
		//throw new ValidationException('bank validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->bank = $this->find($id);
		$this->bank->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->bank = $this->bank->find($id);
		$this->bank->delete();
	}
	
	public function bankList()
	{
		//check admin session and apply return $this->bank->where('parent_id',0)->where('status', 1)->get();
		return $this->bank->get();
	}
	
	public function activeBankList()
	{
		return $this->bank->select('id','name','code')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_bank_code($code, $id = null) {
		
		if($id)
			return $this->bank->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->bank->where('code',$code)->count();
	}
		
	public function check_bank_name($name, $id = null) {
		
		if($id)
			return $this->bank->where('name',$name)->where('id', '!=', $id)->count();
		else
			return $this->bank->where('name',$name)->count();
	}
	
}

