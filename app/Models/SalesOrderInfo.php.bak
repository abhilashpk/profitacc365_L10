<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesOrderInfo extends Model {

	use softDeletes;
	
	protected $table = 'sales_order_info';
	protected $primaryKey = 'id';
	protected $fillable = ['sales_order_id','title','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}