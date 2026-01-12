<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseReturnItem extends Model {

	use softDeletes;
	
	protected $table = 'purchase_return_item';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_return_id','item_id','item_name','unit_id','quantity','unit_price','discount','total_price'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}