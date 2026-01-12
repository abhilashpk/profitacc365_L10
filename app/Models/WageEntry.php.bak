<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class WageEntry extends Model {

	use softDeletes;
	
	protected $table = 'wage_entry';
	protected $primaryKey = 'id';
	protected $fillable = ['entry_type','year','month','employee_id'];//,'absent_hrs','sick_leave','paid_leave','net_basic','net_hra','net_allowance','net_otg','net_oth','net_total','deductions','othr_allowance','unpaid_leave'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function WageEntryItems()
	{
		return $this->hasMany('App\Models\WageEntryItems')->where('status',1);
	}
	
}