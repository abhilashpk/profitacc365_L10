<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class QuotationSalesItem extends Model {

	use softDeletes;
	
	protected $table = 'quotation_sales_item';
	protected $primaryKey = 'id';
	protected $fillable = ['quotation_sales_id','item_id','item_name','unit_id','quantity','unit_price','vat','vat_amount','discount','line_total','tax_code','tax_include','item_type','item_total','orderno'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
