<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Budgeting extends Model {

	use softDeletes;
	
	protected $table = 'budgeting';
	protected $primaryKey = 'id';
	protected $fillable = ['job_id','total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\ProjectBudget');//->where('status',1)
	}
	


}
