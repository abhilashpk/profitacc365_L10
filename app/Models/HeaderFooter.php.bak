<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class HeaderFooter extends Model {

	use softDeletes;
	
	protected $table = 'header_footer';
	protected $primaryKey = 'id';
	protected $fillable = ['is_header','title','description','doc'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}