<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ProjectBudget extends Model {

	use softDeletes;
	
	protected $table = 'project_budget';
	protected $primaryKey = 'id';
	protected $fillable = ['budgeting_id','ac_id','description','amount','status'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}