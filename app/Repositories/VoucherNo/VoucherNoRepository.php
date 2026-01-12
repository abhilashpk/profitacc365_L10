<?php
declare(strict_types=1);
namespace App\Repositories\VoucherNo;

use App\Models\VoucherNo;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;

class VoucherNoRepository extends AbstractValidator implements VoucherNoInterface {
	
	protected $voucherno;
	
	protected static $rules = [];
	
	public function __construct(VoucherNo $voucherno) {
		$this->voucherno = $voucherno;
		
	}
	
	public function all()
	{
		return $this->voucherno->get();
	}
	
	public function find($id)
	{
		return $this->voucherno->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			$this->voucherno->voucherno_name = $attributes['voucherno_name'];
			$this->voucherno->fill($attributes)->save();
			return true;
		}
		
	}
	
	/* public function update($id, $attributes)
	{
		$this->voucherno = $this->find($id);
		$this->voucherno->fill($attributes)->save();
		return true;
	} */
	
	public function update($id,$parameter)
	{ //echo '<pre>';print_r($parameter);exit;
		foreach($parameter['id'] as $key => $row) {
			$this->voucherno = $this->find($parameter['id'][$key]); 
			$this->voucherno->no = $parameter['no'][$key];
			$this->voucherno->prefix = $parameter['prefix'][$key];
			$this->voucherno->autoincrement = (isset($parameter['autoincrement'][$key]))?$parameter['autoincrement'][$key]:0;
			$this->voucherno->save();
		}
		return true;
	}
	
	
	public function delete($id)
	{
		$this->voucherno = $this->voucherno->find($id);
		$this->voucherno->delete();
	}
	
	public function getVoucherNo($type) 
	{
		return $this->voucherno->where('voucher_type', $type)->first();
	}

	public function getVoucherNoSetting()
	{
		return $this->voucherno->where('status',1)->get();
	}
	
}

