<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class RentalSalesItem extends Model {

	use softDeletes;
	
	protected $table = 'rental_sales_item';
	protected $primaryKey = 'id';
	protected $fillable = ['rental_sales_id','service_date','item_id','driver_id','unit_id','quantity','rate','vat','vat_amount','extra_hr','extra_rate','line_total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
