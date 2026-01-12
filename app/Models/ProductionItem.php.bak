<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ProductionItem extends Model {

	use softDeletes;
	
	protected $table = 'production_item';
	protected $primaryKey = 'id';
	protected $fillable = ['production_id','item_id','item_name','unit_id','quantity','unit_price','discount','line_total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}