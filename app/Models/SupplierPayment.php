<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SupplierPayment extends Model {

	use softDeletes;
	
	protected $table = 'supplier_payment';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','voucher_type','cr_account_id','reference','description','job_id','department_id','supplier_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function TransactionAdd()
	{
		return $this->hasMany('App\Models\SupplierPaymentTr')->where('status',1);
	}

}
