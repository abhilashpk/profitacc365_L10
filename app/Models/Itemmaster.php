<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itemmaster extends Model
{
    use softDeletes;
	
	protected $table = 'itemmaster';
	protected $primaryKey = 'id';
	protected $fillable = ['item_code','description','class_id','model_no','serial_no',
							'group_id','subgroup_id','category_id','subcategory_id','surface_cost','other_cost','assembly','batch_req'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function itemUnits()
	{
		return $this->hasMany('App\Models\ItemUnit')->where('status',1);
	}
}

