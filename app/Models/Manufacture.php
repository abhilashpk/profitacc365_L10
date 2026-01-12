<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Manufacture extends Model {

	use softDeletes;
	
	protected $table = 'manufacture';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','account_dr','account_cr','amount','department_id','description','discount','net_amount'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function manufactureItem()
	{
		return $this->hasMany('App\Models\ManufactureItem')->where('status',1);
	}
	

}
