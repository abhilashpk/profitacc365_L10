<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ManualJournal extends Model {

	use softDeletes;
	
	protected $table = 'manual_journal';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','debit','credit','difference'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function ManualJournalAdd()
	{
		return $this->hasMany('App\Models\ManualJournalEntry')->where('status',1);
	}

}
