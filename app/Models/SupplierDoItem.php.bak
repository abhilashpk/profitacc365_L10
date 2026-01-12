<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SupplierDoItem extends Model {

	use softDeletes;
	
	protected $table = 'supplier_do_item';
	protected $primaryKey = 'id';
	protected $fillable = ['supplier_do_id','item_id','item_name','unit_id','quantity','unit_price','vat_amount','discount','total_price','othercost_unit','netcost_unit'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}