<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ItemDescription extends Model {

	use softDeletes;
	
	protected $table = 'item_description';
	protected $primaryKey = 'id';
	protected $fillable = ['invoice_type','item_detail_id','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}