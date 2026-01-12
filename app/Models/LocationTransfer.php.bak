<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class LocationTransfer extends Model {

	use softDeletes;
	
	protected $table = 'location_transfer';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','description','locfrom_id','locto_id','total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function transferItem()
	{
		return $this->hasMany('App\Models\LocationTransferItem')->where('status',1);
	}
	

}