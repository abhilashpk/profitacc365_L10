<?php
declare(strict_types=1);
namespace App\Repositories\VoucherType;

use App\Models\VoucherType;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VoucherTypeRepository extends AbstractValidator implements VoucherTypeInterface {
	
	protected $voucher_type;
	
	protected static $rules = [];
	
	public function __construct(VoucherType $voucher_type) {
		$this->voucher_type = $voucher_type;

	}
	
	public function all()
	{
		
	}
	
	public function paginate($page = 1, $limit = 10, $all = false)
	{
		
	}
	
	public function find($id)
	{
		return $this->voucher_type->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		
	}
	
	public function update($id, $voucher_type)
	{
		
	}
	
	public function delete($id)
	{
			
	}

	public function getVoucherType()
	{
		return $this->voucher_type->where('status',1)->get();
	}
	
	
}

