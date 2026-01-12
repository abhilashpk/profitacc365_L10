<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class JobOrderDetails extends Model {

	use softDeletes;
	
	protected $table = 'joborder_details';
	protected $primaryKey = 'id';
	protected $fillable = ['joborder_id','description','comment'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}