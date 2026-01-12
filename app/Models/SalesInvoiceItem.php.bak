<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesInvoiceItem extends Model {

	use softDeletes;
	
	protected $table = 'sales_invoice_item';
	protected $primaryKey = 'id';
	protected $fillable = ['sales_invoice_id','item_id','item_name','unit_id','quantity','unit_price','vat','vat_amount','line_total','tax_code','tax_include','item_total','pay_pcntg','pay_amount','pay_pcntg_desc','assembly_items','assembly_items_qty','unit_price_fc','vat_amount_fc','total_price_fc','item_total_fc','rate','rate_fc','row_total','row_total_fc','vat_exc','vat_exc_fc'];//31MY
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}