<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Employee extends Model {

	use softDeletes;
	
	protected $table = 'employee';
	protected $primaryKey = 'id';
	protected $fillable = ['code','name','designation','nationality','gender','address1','address2','address3','email','phone','pp_id','pp_issue_place','v_designation','v_id','lc_id','hc_id','hc_info','wages','contract_status','nwh','ot_general','ot_holiday','contract_salary','basic_pay','hra','transport','allowance','payment_method','loan','advance_salary','wage_calculation','ot_calculation','remarks','duty_status','other_info','nwage','otwage'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}