<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ItemInfo extends Model {

	use softDeletes;
	
	protected $table = 'item_info';
	protected $primaryKey = 'id';
	protected $fillable = ['si_no','si_date','customer_id','item_id','item_code','item_description','unit','quantity','in_out','created_at','is_open'];
	public $timestamps = false;
	protected $dates = ['deleted_at'];
	
	public function ItemInfoAdd()
	{
		return $this->hasMany('App\Models\ItemInfo')->where('status',1);
	}
	

}

//SELECT sales_invoice.voucher_no,sales_invoice.voucher_date,sales_invoice.lpo_no,sales_invoice.total,sales_invoice.discount,sales_invoice.vat_amount,sales_invoice.net_total,sales_invoice.total_fc,sales_invoice.discount_fc,sales_invoice.vat_amount_fc,sales_invoice.net_total_fc,sales_invoice.customer_name,sales_invoice.customer_phone,sales_invoice.subtotal,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,salesman.name AS salesman,sales_invoice_item.item_name,sales_invoice_item.quantity,sales_invoice_item.unit_price,sales_invoice_item.vat,sales_invoice_item.vat,sales_invoice_item.vat_amount AS line_vat,sales_invoice_item.line_total,sales_invoice_item.tax_include,sales_invoice_item.item_total,itemmaster.item_code,units.unit_name,sales_invoice_item.id AS sii_id,department.name AS department,sales_invoice.roundoff,sales_invoice.total_roundoff FROM sales_invoice JOIN account_master ON(account_master.id=sales_invoice.customer_id) LEFT JOIN terms ON(terms.id=sales_invoice.terms_id) LEFT JOIN salesman ON(salesman.id=sales_invoice.salesman_id) JOIN sales_invoice_item ON(sales_invoice_item.sales_invoice_id=sales_invoice.id) JOIN itemmaster ON(itemmaster.id=sales_invoice_item.item_id) JOIN units ON(units.id=sales_invoice_item.unit_id) LEFT JOIN department ON(department.id=sales_invoice.department_id) WHERE sales_invoice_item.status=1 AND sales_invoice_item.deleted_at='0000-00-00 00:00:00' AND sales_invoice.id={id}