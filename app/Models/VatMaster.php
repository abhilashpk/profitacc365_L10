<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class VatMaster extends Model {

	use softDeletes;
	
	protected $table = 'vat_master';
	protected $primaryKey = 'id';
	protected $fillable = ['code','name','percentage','collection_account','payment_account','expense_account','vatinput_import','vatoutput_import'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
