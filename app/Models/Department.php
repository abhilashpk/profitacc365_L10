<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Department extends Model {

	use softDeletes;
	
	protected $table = 'department';
	protected $primaryKey = 'id';
	protected $fillable = ['code','name'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
