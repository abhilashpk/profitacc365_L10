<?php namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {

	use softDeletes;
	
	protected $table = 'product';
	protected $primaryKey = 'id';
	protected $fillable = ['category_id','subcategory_id','name','brand_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function defaultImage()
	{
		return $this->hasMany('App\Models\ProductImage')->where('is_default',1)->where('status',1);
	}
	
	public function productImages()
	{
		return $this->hasMany('App\Models\ProductImage')->where('status',1);
	}
	
	public function productBrand()
	{
		return $this->belongsTo('App\Models\Brand', 'brand_id');
	}
	
	public function proAttributes()
	{
		return $this->hasMany('App\Models\ProductAttribute');
	}
	
	public function specification()
	{
		return $this->hasOne('App\Models\Specification');
	}
	
	public function productAttachment()
	{
		return $this->hasMany('App\Models\Attachment')->where('status',1);
	}
	
	public function productLinks()
	{
		return $this->hasOne('App\Models\ProductCrosslink');
	}
	
	public function cartItems()
	{
		return $this->hasMany('App\Models\Cart');
	}
	
	public function searchAttributes()
	{
		return $this->hasMany('App\Models\ProductAttribute','product_id')->where('attr_type',1);
	}

}

