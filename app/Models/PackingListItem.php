<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PackingListItem extends Model { 

	use softDeletes;
	
	protected $table = 'packing_list_item';
	protected $primaryKey = 'id';
	//protected $fillable = ['packing_list_id','item_id','item_name','unit_id','quantity'];
	protected $fillable = ['packing_list_id','item_id','item_name','unit_id','quantity','unit_price','vat','vat_amount','line_total','tax_code','tax_include','item_total','pay_pcntg','pay_amount','pay_pcntg_desc'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
