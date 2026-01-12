<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesReturn extends Model {

	use softDeletes;
	
	protected $table = 'sales_return';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','customer_id','sales_invoice_id','description','cr_account_id','dr_account_id','job_id','sales_invoice_no'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function salesReturnItemAdd()
	{
		return $this->hasMany('App\Models\SalesReturnItem')->where('status',1);
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
}
