<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CreditNoteJournalEntry extends Model {

	use softDeletes;
	
	protected $table = 'creditnote_jv_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['account_id','description','reference','entry_type','amount','job_id','department_id','cheque_no','bank_id','party_account_id','is_onaccount','amount_transfer','balance_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function JournalVoucherTrAdd()
	{
		return $this->hasMany('App\Models\CreditNoteJVTr')->where('status',1);
	}

}