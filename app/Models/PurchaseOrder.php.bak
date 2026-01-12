<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseOrder extends Model {

	use softDeletes;
	
	protected $table = 'purchase_order';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','description','terms_id','job_id','is_fc','currency_id','currency_rate','supplier_id','header_id','footer_id','total','discount','net_amount','total_fc','discount_fc','net_amount_fc','is_import','location_id','foot_description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function orderItem()
	{
		return $this->hasMany('App\Models\PurchaseOrderItem')->where('status',1);
	}
	
	public function orderInfo()
	{
		return $this->hasMany('App\Models\PurchaseOrderInfo')->where('status',1);
	}
		public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
   public function doOtherCost()
	{
		return $this->hasMany('App\Models\PurchaseOrderOtherCost')->where('status',1);
	}

}