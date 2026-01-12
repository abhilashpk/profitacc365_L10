<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PackingList extends Model {

	use softDeletes;
	
	protected $table = 'packing_list';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','quotation_id','description','customer_id','terms_id','currency_id','currency_rate','footer_id','salesman_id','is_export','vehicle_id','kilometer','job_type','jobnature','fabrication','less_description','less_amount','less_amount2','less_description2','less_amount3','less_description3','location_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function packingListItem()
	{
		return $this->hasMany('App\Models\PackingListItem')->where('status',1);
	}
	
	public function packingListInfo()
	{
		return $this->hasMany('App\Models\PackingListInfo')->where('status',1);
	}


}