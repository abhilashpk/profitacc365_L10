<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseInvoice extends Model {

	use softDeletes;
	
	protected $table = 'purchase_invoice';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','reference_no','supplier_id','document_id','description','account_master_id','job_id','terms_id','lpo_no','is_export','document_no'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\PurchaseInvoiceItem');//->where('status',1)
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
	
	public function doOtherCost()
	{
		return $this->hasMany('App\Models\PurchaseInvoiceOtherCost')->where('status',1);
	}
}
