<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ItemLocationPR extends Model {

	use softDeletes;
	
	protected $table = 'item_location_pr';
	protected $primaryKey = 'id';
	protected $fillable = [];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
