<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ProformaInvoice extends Model {

	use softDeletes;
	
	protected $table = 'proforma_invoice';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','quotation_id','description','customer_id','terms_id','currency_id','currency_rate','footer_id','salesman_id','is_export','vehicle_id','kilometer','job_type','jobnature','fabrication','less_description','less_amount','less_amount2','less_description2','less_amount3','less_description3','location_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function proformaInvoiceItem()
	{
		return $this->hasMany('App\Models\ProformaInvoiceItem')->where('status',1);
	}
	
	public function proformaInvoiceInfo()
	{
		return $this->hasMany('App\Models\ProformaInvoiceInfo')->where('status',1);
	}


}
