<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Acgroup extends Model {

	use softDeletes;
	
	protected $table = 'account_group';
	protected $primaryKey = 'id';
	protected $fillable = ['category_id','name','category','code'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
		
	/* public function accounttype()
	{
		return $this->hasMany('App\Models\Acgroup','parent_id')->where('status',1);
	} */
	
	public function category()
	{
		return $this->belongsTo(Accategory::class, 'category_id');
	}

	public function accounts()
	{
		return $this->hasMany(AccountMaster::class, 'account_group_id');
	}
}