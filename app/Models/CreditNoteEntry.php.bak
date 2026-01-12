<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CreditNoteEntry extends Model {

	use softDeletes;
	
	protected $table = 'credit_note_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['cr_account_id','cr_description','cr_reference','type','cr_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}