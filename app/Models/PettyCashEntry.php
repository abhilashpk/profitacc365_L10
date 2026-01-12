<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PettyCashEntry extends Model {

	use softDeletes;
	
	protected $table = 'petty_cash_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['account_id','description','reference','amount','job_id','department_id','cheque_no','bank_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
