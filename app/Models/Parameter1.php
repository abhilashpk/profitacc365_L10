<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter1 extends Model {

	protected $table = 'parameter1';
	protected $primaryKey = 'id';
	protected $fillable = ['item_class','bcurrency_id','bdecimal_place','fcurrency_id','fdecimal_place','doc_warndays','pdc_warndays','cost_method','vat_entry','vat_value','credit_limit'];
	public $timestamps = false;

}

