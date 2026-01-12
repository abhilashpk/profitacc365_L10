<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class DebitNoteEntry extends Model {

	use softDeletes;
	
	protected $table = 'debit_note_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['dr_account_id','dr_description','dr_reference','type','dr_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}