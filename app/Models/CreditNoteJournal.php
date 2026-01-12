<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CreditNoteJournal extends Model {

	use softDeletes;
	
	protected $table = 'creditnote_jv';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','debit','credit','difference'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function JournalAdd()
	{
		return $this->hasMany('App\Models\CreditNoteJournalEntry')->where('status',1);
	}

}
