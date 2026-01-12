<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseReturn extends Model {

	use softDeletes;
	
	protected $table = 'purchase_return';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','reference_no','supplier_id','purchase_invoice_id','description','account_master_id','job_id','total','discount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\PurchaseReturnItem')->where('status',1);
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
}
