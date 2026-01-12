<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class OtherPaymentTr extends Model {

	use softDeletes;
	
	protected $table = 'other_payment_tr';
	protected $primaryKey = 'id';
	protected $fillable = ['dr_account_id','dr_reference','dr_description','dr_job_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}