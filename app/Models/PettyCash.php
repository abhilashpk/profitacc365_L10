<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PettyCash extends Model {

	use softDeletes;
	
	protected $table = 'petty_cash';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','difference'];//'debit','credit'
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function PettyCashAdd()
	{
		return $this->hasMany('App\Models\PettyCashEntry')->where('status',1);
	}

}
