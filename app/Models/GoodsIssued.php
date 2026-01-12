<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class GoodsIssued extends Model {

	use softDeletes;
	
	protected $table = 'goods_issued';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','job_id','description','account_master_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\GoodsIssuedItem');//->where('status',1)
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
}
