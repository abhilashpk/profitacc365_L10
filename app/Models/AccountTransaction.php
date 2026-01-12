<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class AccountTransaction extends Model {

	use softDeletes;
	
	protected $table = 'account_transaction';
	protected $primaryKey = 'id';
	protected $fillable = ['account_master_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function account()
	{
		return $this->belongsTo(AccountMaster::class, 'account_master_id');
	}	

}
