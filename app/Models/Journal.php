<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;   // âœ… Needed for DB::table()
use Session;

class Journal extends Model {

	use softDeletes;
	
	protected $table = 'journal';
	protected $primaryKey = 'id';
	protected $fillable = ['voucher_no','debit','credit','difference'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	protected static function boot()
	{
		parent::boot();

		static::deleted(function ($voucher) {
			// âœ… Detect soft delete manually: deleted_at column should now be non-null
			if (isset($voucher->deleted_at) && !is_null($voucher->deleted_at)) {

				$suffix = date('YmdHis');
				$newVoucherNo = $voucher->voucher_no . '-' . $suffix;

				// Direct DB update (no recursion or re-trigger)
				\DB::table('journal')
					->where('id', $voucher->id)
					->update(['voucher_no' => $newVoucherNo]);
			}
		});
	}

	public function JournalAdd()
	{
		return $this->hasMany('App\Models\JournalEntry')->where('status',1);
	}

}
