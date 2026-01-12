<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ManufactureItem extends Model {

	use softDeletes;
	
	protected $table = 'manufacture_item';
	protected $primaryKey = 'id';
	protected $fillable = ['manufacture_id','item_id','item_name','unit_id','quantity','price','item_total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}