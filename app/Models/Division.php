<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Division extends Model {

	use softDeletes;
	
	protected $table = 'division';
	protected $primaryKey = 'id';
	protected $fillable = ['div_code','div_name'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
