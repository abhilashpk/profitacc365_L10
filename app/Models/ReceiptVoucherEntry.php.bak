<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ReceiptVoucherEntry extends Model {

	use softDeletes;
	
	protected $table = 'receipt_voucher_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['account_id','description','reference','amount','entry_type','job_id','salesman_id','department_id','currency_id','cheque_no','cheque_date','bank_id','is_onaccount','amount_transfer','balance_amount','party_account_id','salesman_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function ReceiptVoucherTrAdd()
	{
		return $this->hasMany('App\Models\ReceiptVoucherTr')->where('status',1);
	}

}