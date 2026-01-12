<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PaymentVoucherEntry extends Model {

	use softDeletes;
	
	protected $table = 'payment_voucher_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['account_id','description','reference','amount','entry_type','job_id','department_id','cheque_no','cheque_date','bank_id','is_onaccount','amount_transfer','balance_amount','party_account_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function PaymentVoucherTrAdd()
	{
		return $this->hasMany('App\Models\PaymentVoucherTr')->where('status',1);
	}

}