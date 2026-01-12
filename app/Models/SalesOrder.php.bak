<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesOrder extends Model {

	use softDeletes;
	
	protected $table = 'sales_order';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','quotation_id','description','customer_id','terms_id','currency_id','currency_rate','footer_id','salesman_id','is_export','vehicle_id','kilometer','job_type','jobnature','fabrication','less_description','less_amount','less_amount2','less_description2','less_amount3','less_description3','location_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function salesOrderItem()
	{
		return $this->hasMany('App\Models\SalesOrderItem')->where('status',1);
	}
	
	public function salesOrderInfo()
	{
		return $this->hasMany('App\Models\SalesOrderInfo')->where('status',1);
	}


}