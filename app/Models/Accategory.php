<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Accategory extends Model {

	use softDeletes;
	
	protected $table = 'account_category';
	protected $primaryKey = 'id';
	protected $fillable = ['parent_id','name'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
		
	public function accounttype()
	{
		return $this->hasMany('App\Models\Accategory','parent_id')->where('status',1);
	}
	
	/*public function groups()
	{
		return $this->hasMany(Acgroup::class, 'parent_id');
	}*/
	
	public function groups()
	{
		return $this->hasMany('App\Models\Acgroup', 'category_id', 'id')
                //->where('deleted_at', '0000-00-00 00:00:00')
                ->where('status', 1);
	}


	public function children()
	{
		return $this->hasMany(Accategory::class, 'parent_id', 'id')
					->where('status', 1);
					//->where('deleted_at', '0000-00-00 00:00:00');
	}


}
