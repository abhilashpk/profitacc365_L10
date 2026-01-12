<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class QuotationSales extends Model {

	use softDeletes;
	
	protected $table = 'quotation_sales';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','description','customer_id','subject','job_id','salesman_id','header_id','footer_id','is_export','vehicle_id','job_type','jobnature','fabrication','kilometer','terms_id','location_id','foot_description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function quotationItem()
	{
		return $this->hasMany('App\Models\QuotationSalesItem')->where('status',1);
	}
	
	public function quotationInfo()
	{
		return $this->hasMany('App\Models\QuotationSalesInfo')->where('status',1);
	}


}