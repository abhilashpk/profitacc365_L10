<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;   // ✅ Needed for DB::table()
use Session;

class PaymentVoucher extends Model {

	use softDeletes;
	
	protected $table = 'payment_voucher';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_id','voucher_no','debit','credit','difference'];//'voucher_type',
	public $timestamps = false;
	protected $dates = ['deleted_at'];

	protected static function boot()
	{
		parent::boot();

		static::deleted(function ($voucher) {
			// ✅ Detect soft delete manually: deleted_at column should now be non-null
			if (isset($voucher->deleted_at) && !is_null($voucher->deleted_at)) {

				$suffix = date('YmdHis');
				$newVoucherNo = $voucher->voucher_no . '-' . $suffix;

				// Direct DB update (no recursion or re-trigger)
				\DB::table('payment_voucher')
					->where('id', $voucher->id)
					->update(['voucher_no' => $newVoucherNo]);
			}
		});
	}

	
	public function PaymentVoucherAdd()
	{
		return $this->hasMany('App\Models\PaymentVoucherEntry')->where('status',1);
	}

}