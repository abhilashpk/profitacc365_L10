<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesReturnItem extends Model {

	use softDeletes;
	
	protected $table = 'sales_return_item';
	protected $primaryKey = 'id';
	protected $fillable = ['sales_invoice_id','item_id','item_name','unit_id','quantity','cat_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}