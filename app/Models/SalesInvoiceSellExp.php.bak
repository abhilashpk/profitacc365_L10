<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class SalesInvoiceSellExp extends Model {

	use softDeletes;
	
	protected $table = 'si_selling_exp';
	protected $primaryKey = 'id';
	protected $fillable = ['sales_invoice_id','dr_account_id','se_reference','se_description','cr_account_id','se_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}