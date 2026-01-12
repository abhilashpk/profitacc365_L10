<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class OtherVoucherTr extends Model {

	use softDeletes;
	
	protected $table = 'other_voucher_tr';
	protected $primaryKey = 'id';
	protected $fillable = [''];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
