<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class PurchaseSplitItem extends Model {

	use softDeletes;
	
	protected $table = 'purchase_split_item';
	protected $primaryKey = 'id';
	protected $fillable = ['purchase_split_id','account_id','item_description','unit_id','quantity','unit_price','vat','item_vat','item_jobid','item_supname','item_vatno','item_total','tax_code','unit_price_fc','item_vat_fc','item_total_fc','line_total_fc','line_total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}
