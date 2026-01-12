<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class GoodsReturnItem extends Model {

	use softDeletes;
	
	protected $table = 'goods_return_item';
	protected $primaryKey = 'id';
	protected $fillable = ['goods_return_id','item_id','item_name','unit_id','quantity','unit_price','discount','total_price'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}