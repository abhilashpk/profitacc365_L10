<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CustomerDoInfo extends Model {

	use softDeletes;
	
	protected $table = 'customer_do_info';
	protected $primaryKey = 'id';
	protected $fillable = ['customer_do_id','title','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
