<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class WageEntryJob extends Model {

	use softDeletes;
	
	protected $table = 'wage_entry_job';
	protected $primaryKey = 'id';
	protected $fillable = ['wage_entry_items_id','job_id','job_type','hour'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
