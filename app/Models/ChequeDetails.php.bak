<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ChequeDetails extends Model {

	use softDeletes;
	
	protected $table = 'cheque_details';
	protected $primaryKey = 'id';
	protected $fillable = ['cheque_no','cheque_date','customer_id ','amount_words','amount_number '];
	public $timestamps = false;
	protected $dates = ['created_at'];
	
	// public function ManualJournalAdd()
	// {
	// 	return $this->hasMany('App\Models\ManualJournalEntry')->where('status',1);
	// }

}