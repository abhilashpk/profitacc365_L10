<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CreditNoteJVTr extends Model {

	use softDeletes;
	
	protected $table = 'creditnote_jv_tr';
	protected $primaryKey = 'id';
	protected $fillable = ['assign_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
