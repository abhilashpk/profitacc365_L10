<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemStock extends Model
{
    use softDeletes;
	
	protected $table = 'item_stock';
	protected $primaryKey = 'id';
	protected $fillable = ['item_id','unit_id','quantity'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
}

