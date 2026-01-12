<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseSplit extends Model {

	use softDeletes;
	
	protected $table = 'purchase_split';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','reference_no','supplier_id','description','job_id','is_export'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\PurchaseSplitItem');//->where('status',1)
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}

}
