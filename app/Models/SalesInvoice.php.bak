<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesInvoice extends Model {

	use softDeletes;
	
	protected $table = 'sales_invoice';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','reference_no','customer_id','document_id','description','dr_account_id','cr_account_id','job_id','terms_id','is_fc','currency_id','currency_rate','footer_id','lpo_no','salesman_id','is_export','subtotal','subtotal_fc','advance','balance','vehicle_id','kilometer','job_type','jobnature','fabrication','less_amount','less_description','previnv_description','previnv_amount','less_amount2','less_description2','less_amount3','less_description3','vehicle_no','roundoff','total_roundoff','discount','discount_fc'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function SalesInvoiceItemAdd()
	{
		return $this->hasMany('App\Models\SalesInvoiceItem')->where('status',1);
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
	
	public function doOtherSellExp()
	{
		return $this->hasMany('App\Models\PurchaseInvoiceOtherCost')->where('status',1);
	}
}

