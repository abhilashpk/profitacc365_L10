<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CustomerDo extends Model {

	use softDeletes;
	
	protected $table = 'customer_do';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','customer_id','document_type','document_id','description','job_id','terms_id','currency_id','currency_rate','footer_id','salesman_id','is_export'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function AddCustomerDoItem()
	{
		return $this->hasMany('App\Models\CustomerDoItem')->where('status',1);
	}

	public function customerDoInfo()
	{
		return $this->hasMany('App\Models\CustomerDoInfo')->where('status',1);
	}

}