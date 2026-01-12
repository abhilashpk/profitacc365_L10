<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class QuotationSalesInfo extends Model {

	use softDeletes;
	
	protected $table = 'quotation_sales_info';
	protected $primaryKey = 'id';
	protected $fillable = ['quotation_sales_id','title','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
