<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Group extends Model {

	use softDeletes;
	
	protected $table = 'groupcat';
	protected $primaryKey = 'id';
	protected $fillable = ['group_name','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
		
	public function subcategory()
	{
		if(Session::has('user')) {
			if(Session::get('user')[0]['role_id']==1||Session::get('user')[0]['role_id']==2) {
				return $this->hasMany('App\Models\Category','parent_id');
			} else 
				return $this->hasMany('App\Models\Category','parent_id')->where('status',1);
		} else
			return $this->hasMany('App\Models\Category','parent_id')->where('status',1);
	}
	

}
