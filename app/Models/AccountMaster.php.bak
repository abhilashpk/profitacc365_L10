<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountMaster extends Model
{
    use softDeletes;
	
	protected $table = 'account_master'; 
	protected $primaryKey = 'id';
	protected $fillable = [ 'account_id','master_name','account_category_id','account_group_id','department_id','currency_id',
							'salesman_id','credit_limit','duedays','terms_id','country_id','area_id',
							'job_assign','job_compulsary','is_hide','contact_name' ]; //'op_balance','fcop_balance',
	public $timestamps = false;
	protected $dates = ['deleted_at'];

	public function group()
	{
		return $this->belongsTo(Acgroup::class, 'account_group_id');
	}

	public function category()
	{
		return $this->belongsTo(Accategory::class, 'account_category_id');
	}

	public function transactions()
	{
		return $this->hasMany(AccountTransaction::class, 'account_master_id');
	}

	public function current_transactions()
	{
		return $this->hasMany(AccountTransaction::class, 'account_master_id');
	}

	public function prior_transactions()
	{
		return $this->hasMany(AccountTransaction::class, 'account_master_id');
	}
}
