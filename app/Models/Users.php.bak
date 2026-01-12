<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model {

	use softDeletes;
	
	protected $table = 'user';
	protected $primaryKey = 'id';
	protected $fillable = ['name','username','email','company','address','phone'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];

}
