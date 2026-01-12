<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSetting extends Model
{
    use softDeletes;
	
	protected $table = 'account_setting';
	protected $primaryKey = 'id';
	protected $fillable = [ 'voucher_type_id','department_id','voucher_name','prefix','is_prefix','voucher_no','dr_account_master_id','cr_account_master_id','is_cash_voucher','description'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
}
