<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Forms extends Model {

	
	protected $table = 'forms';
	protected $primaryKey = 'id';
	protected $fillable = [];
	public $timestamps = false;
	
	

}
