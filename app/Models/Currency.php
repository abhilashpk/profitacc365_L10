<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Currency extends Model {

	use softDeletes;
	
	protected $table = 'currency';
	protected $primaryKey = 'id';
	protected $fillable = ['code','name','rate','fracode','decimal_place'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
