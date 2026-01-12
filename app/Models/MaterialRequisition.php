<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class MaterialRequisition extends Model {

	use softDeletes;
	
	protected $table = 'material_requisition';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','job_id','description','supplier_id','salesman_id','location_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\MaterialRequisitionItem');//->where('status',1)
	}
	
}
