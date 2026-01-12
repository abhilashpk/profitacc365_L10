<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class QuotationInfo extends Model {

	use softDeletes;
	
	protected $table = 'quotation_info';
	protected $primaryKey = 'id';
	protected $fillable = ['quotation_id','title'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
