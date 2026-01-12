<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class VoucherNo extends Model {

	protected $table = 'voucher_no';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_type','no'];
	public $timestamps = false;
	//protected $dates = ['deleted_at'];
	
		
	
	

}
