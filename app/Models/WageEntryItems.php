<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class WageEntryItems extends Model {

	use softDeletes;
	
	protected $table = 'wage_entry_items';
	protected $primaryKey = 'id';
	protected $fillable = ['wage_entry_id','day','job_id','wage','nodays','nwh','otg','oth','allowance','total_wage','leave_type','is_salary','job_data','leave_status','leave_reason','otg_wage','oth_wage','job_date'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function WageEntryJob()
	{
		return $this->hasMany('App\Models\WageEntryJob')->where('status',1);
	}

}
