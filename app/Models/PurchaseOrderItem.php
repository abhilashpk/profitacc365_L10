<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseOrderItem extends Model {

	use softDeletes;
	
	protected $table = 'purchase_order_item';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_order_id','item_id','item_name','unit_id','tax_code','quantity','unit_price','vat','vat_amount','discount','total_price'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
