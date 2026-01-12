<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseOrderOtherCost extends Model {

	use softDeletes;
	
	protected $table = 'po_other_cost';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_order_id','dr_account_id','oc_reference','oc_description','cr_account_id','oc_amount','oc_fc_amount','oc_vat','oc_vatamt','is_fc','currency_id','currency_rate','tax_code'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}