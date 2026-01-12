<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class QuotationItem extends Model {

	use softDeletes;
	
	protected $table = 'quotation_item';
	protected $primaryKey = 'id';
	protected $fillable = ['quotation_id','item_id','item_name','quantity','unit_price','discount','total_price'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
