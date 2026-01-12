<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Salesman extends Model {

	use softDeletes;
	
	protected $table = 'salesman';
	protected $primaryKey = 'id';
	protected $fillable = ['salesman_id','name','address1','address2','telephone'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
