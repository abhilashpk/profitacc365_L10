<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class OtherReceiptTr extends Model {

	use softDeletes;
	
	protected $table = 'other_receipt_tr';
	protected $primaryKey = 'id';
	protected $fillable = ['cr_account_id','cr_reference','cr_description','cr_job_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}