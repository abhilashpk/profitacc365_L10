<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class StockTransferinItem extends Model {

	use softDeletes;
	
	protected $table = 'stock_transferin_item';
	protected $primaryKey = 'id';
	protected $fillable = ['stock_transfer_id','item_id','item_name','unit_id','quantity','price','item_total','othercost_unit','netcost_unit'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}