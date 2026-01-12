<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Terms extends Model {

	use softDeletes;
	
	protected $table = 'terms';
	protected $primaryKey = 'id';
	protected $fillable = ['code','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
