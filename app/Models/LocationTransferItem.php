<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class LocationTransferItem extends Model {

	use softDeletes;
	
	protected $table = 'location_transfer_item';
	protected $primaryKey = 'id';
	protected $fillable = ['location_transfer_id','item_id','item_name','unit_id','quantity'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
