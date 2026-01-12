<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Vehicle extends Model {

	use softDeletes;
	
	protected $table = 'vehicle';
	protected $primaryKey = 'id';
	protected $fillable = [];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
