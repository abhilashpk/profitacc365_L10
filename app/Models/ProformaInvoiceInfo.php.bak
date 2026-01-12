<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ProformaInvoiceInfo extends Model {

	use softDeletes;
	
	protected $table = 'proforma_invoice_info';
	protected $primaryKey = 'id';
	protected $fillable = ['proforma_invoice_id','title','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}