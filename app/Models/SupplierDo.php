<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SupplierDo extends Model {

	use softDeletes;
	
	protected $table = 'supplier_do';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','purchase_order_id','description','job_id','location_id','supplier_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\SupplierDoItem')->where('status',1);
	}
	
	public function doInfo()
	{
		return $this->hasMany('App\Models\SupplierDoInfo')->where('status',1);
	}
	
	public function doOtherCost()
	{
		return $this->hasMany('App\Models\SupplierDoOtherCost')->where('status',1);
	}
}
