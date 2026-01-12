<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class RentalSales extends Model {

	use softDeletes;
	
	protected $table = 'rental_sales';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','reference_no','customer_id','description','account_master_id','is_vat'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function doItem()
	{
		return $this->hasMany('App\Models\RentalSalesItem');
	}
	
	public function doTransaction()
	{
		return $this->hasMany('App\Models\AccountTransaction', 'voucher_type_id')->where('status',1);
	}
	
}
