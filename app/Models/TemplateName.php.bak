<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class TemplateName extends Model {

	use softDeletes;
	
	protected $table = 'template_name';
	protected $primaryKey = 'id';
	protected $fillable = ['name','message'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}