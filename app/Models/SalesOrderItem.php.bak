<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesOrderItem extends Model {

	use softDeletes;
	
	protected $table = 'sales_order_item';
	protected $primaryKey = 'id';
	//protected $fillable = ['sales_order_id','item_id','item_name','unit_id','quantity'];
	protected $fillable = ['sales_order_id','item_id','item_name','unit_id','quantity','unit_price','vat','vat_amount','line_total','tax_code','tax_include','item_total','pay_pcntg','pay_amount','pay_pcntg_desc'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}