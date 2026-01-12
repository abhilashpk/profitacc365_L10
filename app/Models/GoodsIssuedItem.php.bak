<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class GoodsIssuedItem extends Model {

	use softDeletes;
	
	protected $table = 'goods_issued_item';
	protected $primaryKey = 'id';
	protected $fillable = ['goods_issued_id','item_id','item_name','unit_id','quantity','unit_price','discount','total_price','job_id','account_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}