<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class JobEstimateDetails extends Model {

	use softDeletes;
	
	protected $table = 'jobestimate_details';
	protected $primaryKey = 'id';
	protected $fillable = ['jobestimate_id','description','comment'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}