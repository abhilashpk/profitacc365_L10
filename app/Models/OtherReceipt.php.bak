<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class OtherReceipt extends Model {

	use softDeletes;
	
	protected $table = 'other_receipt';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','voucher_type','dr_account_id','reference','description','job_id','department_id','customer_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function TransactionAdd()
	{
		return $this->hasMany('App\Models\OtherReceiptTr')->where('status',1);
	}

}