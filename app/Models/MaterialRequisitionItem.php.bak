<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class MaterialRequisitionItem extends Model {

	use softDeletes;
	
	protected $table = 'material_requisition_item';
	protected $primaryKey = 'id';
	protected $fillable = ['material_requisition_id','item_id','item_name','unit_id','quantity','unit_price','total_price'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}