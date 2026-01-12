<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class CustomerEnquiryItem extends Model {

	use softDeletes;
	
	protected $table = 'customer_enquiry_item';
	protected $primaryKey = 'id';
	protected $fillable = ['customer_enquiry_id','item_id','item_name','quantity','unit_price','discount','total_price','location_id'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
