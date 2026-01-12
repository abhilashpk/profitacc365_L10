<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseInvoiceItem extends Model {

	use softDeletes;
	
	protected $table = 'purchase_invoice_item';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_invoice_id','item_id','item_name','unit_id','quantity','unit_price','vat','vat_amount','discount','total_price','othercost_unit','netcost_unit','tax_code','tax_include','item_total','unit_price_fc','vat_amount_fc','total_price_fc','item_total_fc','mp_qty'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}