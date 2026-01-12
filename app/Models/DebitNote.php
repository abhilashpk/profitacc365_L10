<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class DebitNote extends Model {

	use softDeletes;
	
	protected $table = 'debit_note';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','cr_account_id','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function TransactionAdd()
	{
		return $this->hasMany('App\Models\DebitNoteEntry')->where('status',1);
	}

}
