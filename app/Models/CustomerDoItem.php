<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CustomerDoItem extends Model {

	use softDeletes;
	
	protected $table = 'customer_do_item';
	protected $primaryKey = 'id';
	protected $fillable = ['customer_do_id','item_id','item_name','unit_id','quantity','unit_price','discount','line_total','conloc_id','conloc_qty'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
