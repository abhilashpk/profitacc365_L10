<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Quotation extends Model {

	use softDeletes;
	
	protected $table = 'quotation';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','description','terms_id','job_id','supplier_id','header_id','footer_id','total','discount','net_amount','total_fc','discount_fc','net_amount_fc'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function quotationItemAdd()
	{
		return $this->hasMany('App\Models\QuotationItem')->where('status',1);
	}
	
	public function quotationInfoAdd()
	{
		return $this->hasMany('App\Models\QuotationInfo')->where('status',1);
	}


}