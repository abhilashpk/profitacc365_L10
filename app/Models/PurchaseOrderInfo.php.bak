<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseOrderInfo extends Model {

	use softDeletes;
	
	protected $table = 'purchase_order_info';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_order_id','title'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}