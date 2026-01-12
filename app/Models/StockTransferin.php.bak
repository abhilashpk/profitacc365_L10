<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class StockTransferin extends Model {

	use softDeletes;
	
	protected $table = 'stock_transferin';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','reference_no','description','job_id','account_dr','account_cr','total_qty','total_amt','net_total'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function transferItem()
	{
		return $this->hasMany('App\Models\StockTransferinItem')->where('status',1);
	}
	

}