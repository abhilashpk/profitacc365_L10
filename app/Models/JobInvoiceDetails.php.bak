<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class JobInvoiceDetails extends Model {

	use softDeletes;
	
	protected $table = 'jobinvoice_details';
	protected $primaryKey = 'id';
	protected $fillable = ['jobinvoice_id','description','comment'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}