<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class JournalVoucherTr extends Model {

	use softDeletes;
	
	protected $table = 'journal_voucher_tr';
	protected $primaryKey = 'id';
	protected $fillable = ['assign_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
