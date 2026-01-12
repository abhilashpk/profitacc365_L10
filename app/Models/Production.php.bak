<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Production extends Model {

	use softDeletes;
	
	protected $table = 'production';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','customer_id','document_type','document_id','description','job_id','terms_id','currency_id','currency_rate','footer_id','salesman_id','is_export'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function AddProductionItem()
	{
		return $this->hasMany('App\Models\ProductionItem')->where('status',1);
	}

}