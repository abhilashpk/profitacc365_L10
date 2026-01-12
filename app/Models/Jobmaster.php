<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Jobmaster extends Model {

	use softDeletes;
	
	protected $table = 'jobmaster';
	protected $primaryKey = 'id';
	protected $fillable = ['code','name','open_cost','customer_id','departmen_id','salesman_id','open_income','contract_income'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	

}

//SELECT sales_split.voucher_no,sales_split.voucher_date,sales_split.total,sales_split.discount,sales_split.vat_amount,sales_split.net_amount,sales_split.total_fc,sales_split.discount_fc,sales_split.vat_amount_fc,sales_split.net_amount_fc,sales_split.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,sales_split_item.item_description,sales_split_item.quantity,sales_split_item.unit_price,sales_split_item.unit_id,sales_split_item.vat,sales_split_item.item_vat,sales_split_item.item_total,ITMAC.master_name,jobmaster.* FROM sales_split JOIN account_master ON(account_master.id=sales_split.customer_id) JOIN sales_split_item ON(sales_split_item.sales_split_id=sales_split.id) JOIN account_master AS ITMAC ON(ITMAC.id=sales_split_item.account_id) JOIN jobmaster ON(jobmaster.id=sales_split.job_id) WHERE sales_split_item.status=1 AND sales_split_item.deleted_at='0000-00-00 00:00:00' AND sales_split.id={id}

//SELECT purchase_split.voucher_no,purchase_split.voucher_date,purchase_split.total,purchase_split.discount,purchase_split.vat_amount,purchase_split.net_amount,purchase_split.total_fc,purchase_split.discount_fc,purchase_split.vat_amount_fc,purchase_split.net_amount_fc,purchase_split.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,purchase_split_item.item_description,purchase_split_item.quantity,purchase_split_item.unit_price,purchase_split_item.unit_id,purchase_split_item.vat,purchase_split_item.item_vat,purchase_split_item.item_total,ITMAC.master_name,jobmaster.* FROM purchase_split JOIN account_master ON(account_master.id=purchase_split.supplier_id) JOIN purchase_split_item ON(purchase_split_item.purchase_split_id=purchase_split.id) JOIN account_master AS ITMAC ON(ITMAC.id=purchase_split_item.account_id) JOIN jobmaster ON(jobmaster.id=purchase_split.job_id) WHERE purchase_split_item.status=1 AND purchase_split_item.deleted_at='0000-00-00 00:00:00' AND purchase_split.id={id}
