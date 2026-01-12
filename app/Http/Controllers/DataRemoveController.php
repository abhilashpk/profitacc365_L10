<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Excel;
use App;

class DataRemoveController extends Controller
{
	protected $accountmaster;
	
	public function __construct() {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array(); 
		return view('body.dataremove.index')
					->withData($data);
	}
	
	public function clearDBcustom()
	{
		//echo '<pre>';print_r(Input::all());exit;
		foreach(Input::get('datacol') as $val) {
			switch($val) {
				
				case 'Customers':
					DB::statement("DELETE FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND `category` = 'CUSTOMER'");
					DB::statement("DELETE FROM `account_transaction` WHERE `account_master_id` IN(SELECT id FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND (`category` = 'CUSTOMER') )");
				break;
				
				case 'Suppliers':
					DB::statement("DELETE FROM `account_master` WHERE `category` = 'SUPPLIER'");
					DB::statement("DELETE FROM `account_transaction` WHERE `account_master_id` IN(SELECT id FROM `account_master` WHERE (`category` ='SUPPLIER') )");
				break;
				
				case 'Item':
					DB::statement("TRUNCATE TABLE `itemmaster`");
					DB::statement("TRUNCATE TABLE `item_location`");
					DB::statement("TRUNCATE TABLE `item_location_pi`");
					DB::statement("TRUNCATE TABLE `item_location_pr`");
					DB::statement("TRUNCATE TABLE `item_location_si`");
					DB::statement("TRUNCATE TABLE `item_location_sr`");
					DB::statement("TRUNCATE TABLE `item_location_ti`;");
					DB::statement("TRUNCATE TABLE `item_location_gi`;");
					DB::statement("TRUNCATE TABLE `item_location_gr`;");
					DB::statement("TRUNCATE TABLE `item_location_to`;");
					DB::statement("TRUNCATE TABLE `item_log`");
					DB::statement("TRUNCATE TABLE `item_sale_log`");
					DB::statement("TRUNCATE TABLE `item_stock`");
					DB::statement("TRUNCATE TABLE `item_unit`");
				break;
				
				case 'PO':
					DB::statement("TRUNCATE TABLE `purchase_order`");
					DB::statement("TRUNCATE TABLE `purchase_order_item`");
					DB::statement("TRUNCATE TABLE `purchase_order_info`");
					DB::statement("TRUNCATE TABLE `po_other_cost`");
				break;
				
				case 'PI':
					DB::statement("TRUNCATE TABLE `purchase_invoice`");
					DB::statement("TRUNCATE TABLE `purchase_invoice_item`");
					DB::statement("TRUNCATE TABLE `pi_other_cost`");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PI'");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'PI'");
				break;
				
				case 'PR':
					DB::statement("TRUNCATE TABLE `purchase_return`;");
					DB::statement("TRUNCATE TABLE `purchase_return_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PR'");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'PR'");
				break;
				
				case 'CL':
					DB::statement("TRUNCATE TABLE `leads`");
					DB::statement("TRUNCATE TABLE `followups`;");
				break;
				
				case 'CE':
					DB::statement("TRUNCATE TABLE `customer_enquiry`;");
					DB::statement("TRUNCATE TABLE `customer_enquiry_item`;");
				break;
				
				case 'QS':
					DB::statement("TRUNCATE TABLE `quotation_sales`;");
					DB::statement("TRUNCATE TABLE `quotation_sales_info`;");
					DB::statement("TRUNCATE TABLE `quotation_sales_item`;");
					DB::statement("TRUNCATE TABLE `jobestimate_details`;");
				break;
				
				case 'SO':
					DB::statement("TRUNCATE TABLE `sales_order`;");
					DB::statement("TRUNCATE TABLE `sales_order_info`;");
					DB::statement("TRUNCATE TABLE `sales_order_item`;");
					DB::statement("TRUNCATE TABLE `joborder_details`;");
					DB::statement("TRUNCATE TABLE `job_photos`;");
				break;
				
				case 'DO':
					DB::statement("TRUNCATE TABLE `customer_do`;");
					DB::statement("TRUNCATE TABLE `customer_do_item`;");
					DB::statement("TRUNCATE TABLE `customer_do_item`;");
				break;
				
				case 'SI':
					DB::statement("TRUNCATE TABLE `sales_invoice`;");
					DB::statement("TRUNCATE TABLE `sales_invoice_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SI'");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'SI'");
					DB::statement("TRUNCATE TABLE `jobinvoice_details`;");
				break;
				
				case 'SR':
					DB::statement("TRUNCATE TABLE `sales_return`;");
					DB::statement("TRUNCATE TABLE `sales_return_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SR'");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'SR'");
				break;
				
				case 'PrOD':
					DB::statement("TRUNCATE TABLE `production`;");
					DB::statement("TRUNCATE TABLE `production_item`;");
				break;
				
				case 'GI':
					DB::statement("TRUNCATE TABLE `goods_issued`;");
					DB::statement("TRUNCATE TABLE `goods_issued_item`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'GI'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'GI'");
				break;
				
				case 'GR':
					DB::statement("TRUNCATE TABLE `goods_return`;");
					DB::statement("TRUNCATE TABLE `goods_return_item`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'GR'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'GR'");
				break;
				
				case 'TI':
					DB::statement("TRUNCATE TABLE `stock_transferin`;");
					DB::statement("TRUNCATE TABLE `stock_transferin_item`;");
					DB::statement("TRUNCATE TABLE `sti_other_cost`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'TI'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'STI'");
				break;
				
				case 'TO':
					DB::statement("TRUNCATE TABLE `stock_transferout`;");
					DB::statement("TRUNCATE TABLE `stock_transferout_item`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'TO'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'STO'");
				break;
				
				case 'LT':
					DB::statement("TRUNCATE TABLE `location_transfer`;");
					DB::statement("TRUNCATE TABLE `location_transfer_item`;");
					
				break;
				
				case 'JV':
					DB::statement("TRUNCATE TABLE `journal`;");
					DB::statement("TRUNCATE TABLE `journal_entry`;");
					DB::statement("TRUNCATE TABLE `journal_voucher_tr`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'JV'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PIN'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SIN'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'JV'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'SIN'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'PIN'");
				break;
				
				case 'RV':
					DB::statement("TRUNCATE TABLE `receipt_voucher`;");
					DB::statement("TRUNCATE TABLE `receipt_voucher_entry`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'RV'");
					DB::statement("TRUNCATE TABLE `receipt_voucher_tr`;");
					DB::statement("TRUNCATE TABLE `pdc_received`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'DB'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'RV'");
				break;
				
				case 'PV':
					DB::statement("TRUNCATE TABLE `payment_voucher`;");
					DB::statement("TRUNCATE TABLE `payment_voucher_entry`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PV'");
					DB::statement("TRUNCATE TABLE `payment_voucher_tr`;");
					DB::statement("TRUNCATE TABLE `pdc_issued`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'CB'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'PV'");
				break;
				
				case 'PC':
					DB::statement("TRUNCATE TABLE `petty_cash`;");
					DB::statement("TRUNCATE TABLE `petty_cash_entry`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PC'");
					DB::statement("DELETE FROM `other_voucher_tr` WHERE `voucher_type` = 'PC'");
				break;
				
				case 'E':
					DB::statement("TRUNCATE TABLE `employee`;");
					DB::statement("TRUNCATE TABLE `employee_document`;");
					DB::statement("TRUNCATE TABLE `expiry_docs`;");
					DB::statement("TRUNCATE TABLE `wage_entry`;");
					DB::statement("TRUNCATE TABLE `wage_entry_items`;");
					DB::statement("TRUNCATE TABLE `wage_entry_job`;");
					DB::statement("TRUNCATE TABLE `wage_entry_others`;");
					DB::statement("TRUNCATE TABLE `resign`;");
					DB::statement("TRUNCATE TABLE `onleave`;");
					DB::statement("TRUNCATE TABLE `emp_photos`;");
				break;
				
				case 'WE':
					DB::statement("TRUNCATE TABLE `wage_entry`;");
					DB::statement("TRUNCATE TABLE `wage_entry_items`;");
					DB::statement("TRUNCATE TABLE `wage_entry_job`;");
					DB::statement("TRUNCATE TABLE `wage_entry_others`;");
				break;
				
				case 'DM':
					DB::statement("TRUNCATE TABLE `document_master`;");
				break;
				
				case 'AI':
					DB::statement("TRUNCATE TABLE `assets_issued`;");
				break;
				
				case 'VM':
					DB::statement("TRUNCATE TABLE `vehicle`;");
				break;
				
				case 'SM':
					DB::statement("TRUNCATE TABLE `salesman`;");
				break;
				
				case 'SM':
					DB::statement("TRUNCATE TABLE `terms`;");
				break;
				
				case 'JM':
					DB::statement("DELETE FROM `jobmaster` WHERE is_salary_job = 0;");
				break;
				
				case 'AcOB':
					DB::statement("UPDATE `account_master` set `cl_balance`=0,`op_balance`=0,`fcop_balance`=0,`pdc_amount`=0,`fy_balance`=0;");
					DB::statement("UPDATE `account_transaction` set `amount`=0;");
					DB::statement("TRUNCATE TABLE `opening_balance_tr`");
				break;
				
				case 'OQ':
					DB::statement("UPDATE `item_log` set `quantity`=0,`unit_cost`=0,`cur_quantity`=0,`cost_avg`=0,`pur_cost`=0,`sale_cost`=0;");
					DB::statement("UPDATE `item_unit` set `opn_quantity`=0,`opn_cost`=0,`cur_quantity`=0,`received_qty`=0,`last_purchase_cost`=0,`pur_count`=0,`cost_avg`=0,`issued_qty`=0;");
				break;

				//Done By Sachu Alex
				case 'CUST':
					DB::statement("TRUNCATE TABLE `ms_customer`;");
				break;

				case 'AL':
					DB::statement("TRUNCATE TABLE `ms_area`;");
				break;

				case 'TL':
					DB::statement("TRUNCATE TABLE `ms_technician`;");
				break;

				case 'WTL':
					DB::statement("TRUNCATE TABLE `ms_worktype`;");
				break;

				case 'PL':
					DB::statement("TRUNCATE TABLE `ms_jobmaster`;");
				break;

				case 'WEL':
					DB::statement("TRUNCATE TABLE `ms_workenquiry`;");
				break;

				case 'WOL':
					DB::statement("TRUNCATE TABLE `ms_workorder`;");
				break;
				
				case 'MFG':
					DB::statement("TRUNCATE TABLE `manufacture`;");
					DB::statement("TRUNCATE TABLE `manufacture_item`;");
					DB::statement("TRUNCATE TABLE `stock_transferin`;");
					DB::statement("TRUNCATE TABLE `stock_transferin_item`;");
					DB::statement("TRUNCATE TABLE `sti_other_cost`;");
					DB::statement("TRUNCATE TABLE `mfg_items`;");
					DB::statement("TRUNCATE TABLE `mfg_other_cost`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'TI'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'STI'");
					DB::statement("TRUNCATE TABLE `stock_transferout`;");
					DB::statement("TRUNCATE TABLE `stock_transferout_item`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'TO'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'STO'");
				break;
				
				case 'GRN':
					DB::statement("TRUNCATE TABLE `supplier_do`");
					DB::statement("TRUNCATE TABLE `supplier_do_info`");
					DB::statement("TRUNCATE TABLE `supplier_do_item`");
					DB::statement("TRUNCATE TABLE `sdo_other_cost`");
				break;
				
				case 'CN':
					DB::statement("TRUNCATE TABLE `credit_note`;");
					DB::statement("TRUNCATE TABLE `credit_note_entry`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'CN'");
				break;
				
				case 'DN':
					DB::statement("TRUNCATE TABLE `debit_note`;");
					DB::statement("TRUNCATE TABLE `debit_note_entry`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'DN'");
				break;	
				case 'QP':
					DB::statement("TRUNCATE TABLE `quotation`;");
					DB::statement("TRUNCATE TABLE `quotation_item`;");
				break;
				case 'PS':
					DB::statement("TRUNCATE TABLE `purchase_split`;");
					DB::statement("TRUNCATE TABLE `purchase_split_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PS'");
				break;	
				case 'SS':
					DB::statement("TRUNCATE TABLE `sales_split`;");
					DB::statement("TRUNCATE TABLE `sales_split_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SS'");
				break;	
				case 'PE':
					DB::statement("TRUNCATE TABLE `material_requisition`;");
					DB::statement("TRUNCATE TABLE `material_requisition_item`;");
				break;
				case 'CGL':	
					DB::statement("TRUNCATE TABLE `con_location`;");
					DB::statement("TRUNCATE TABLE `con_location_sr`;");
					DB::statement("DELETE FROM `location` WHERE `is_default` = 0");
				break;	
				case 'CRM':	
					DB::statement("TRUNCATE TABLE `crm_followup`;");
				break;						
				case 'MJV':
					DB::statement("TRUNCATE TABLE `manual_journal`;");
					DB::statement("TRUNCATE TABLE `manual_journal_entry`;");
					DB::statement("TRUNCATE TABLE `manual_journal_voucher_tr`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'MJV'");
				break;	
				case 'BM':	
					DB::statement("TRUNCATE TABLE `buildingmaster`;");
				break;	
				case 'FM':	
					DB::statement("TRUNCATE TABLE `flat_master`;");
				break;	
				case 'Dr':	
					DB::statement("TRUNCATE TABLE `duration`;");
				break;	
				case 'ConT':	
					DB::statement("TRUNCATE TABLE `contract_type`;");
					DB::statement("TRUNCATE TABLE `contra_type`;");
				break;	
				case 'ConE':	
					DB::statement("TRUNCATE TABLE `contract_building`;");
					DB::statement("TRUNCATE TABLE `contract_prepaid`;");
					DB::statement("TRUNCATE TABLE `contract_rvs`;");
					DB::statement("TRUNCATE TABLE `contract_jv`;");
					DB::statement("TRUNCATE TABLE `contract_settlement`;");
					DB::statement("TRUNCATE TABLE `contract_pvs`;");
				break;	
				case 'Dv':	
					DB::statement("TRUNCATE TABLE `division`;");
				break;	
				case 'ConP':	
					DB::statement("TRUNCATE TABLE `contract`;");
				break;	
				case 'MR':	
					DB::statement("TRUNCATE TABLE `machine_read`;");
				break;	
				case 'PkgM':	
					DB::statement("TRUNCATE TABLE `package_master`;");
				break;	
				case 'PkgJB':	
					DB::statement("TRUNCATE TABLE `joborder_pkgs`;");
					DB::statement("TRUNCATE TABLE `jobtype`;");
				break;	
				case 'Mch':	
					DB::statement("TRUNCATE TABLE `machine`;");
				break;	
				case 'Ppr':	
					DB::statement("TRUNCATE TABLE `paper`;");
				break;	
				case 'MSL':	
					DB::statement("TRUNCATE TABLE `ms_location`;");
				break;	
				case 'MSWOT':	
					DB::statement("TRUNCATE TABLE `ms_wo_time`;");
					
				break;
				case 'CCON':	
					DB::statement("TRUNCATE TABLE `consignee`;");
				break;
                case 'SHP':	
					DB::statement("TRUNCATE TABLE `shipper`;");
				break;
				case 'CCT':	
					DB::statement("TRUNCATE TABLE `collection_type`;");
				break;
				case 'CDT':	
					DB::statement("TRUNCATE TABLE `delivery_type`;");
				break;
                case 'CV':	
					DB::statement("TRUNCATE TABLE `cargo_vehicle`;");
				break;
				case 'CDES':	
					DB::statement("TRUNCATE TABLE `cargo_destination`;");
				break;
				case 'RCT':	
					DB::statement("TRUNCATE TABLE `cargo_receipt`;");
				break;
				case 'CDB':	
					DB::statement("TRUNCATE TABLE `cargo_despatch_bill`;");
					DB::statement("TRUNCATE TABLE `cargo_despatch_entry`;");
				break;
				case 'CWB':	
					DB::statement("TRUNCATE TABLE `cargo_waybill`;");
					DB::statement("TRUNCATE TABLE `cargo_waybill_entry`;");
				break;
                case 'RSD':	
					DB::statement("TRUNCATE TABLE `rental_supplierdriver`;");
				break;
				case 'RCD':	
					DB::statement("TRUNCATE TABLE `rental_customerdriver`;");
				break;
				case 'REP':	
					DB::statement("TRUNCATE TABLE `purchase_rental`;");
					DB::statement("TRUNCATE TABLE `purchase_rental_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PIR'");
				break;
				case 'RES':	
					DB::statement("TRUNCATE TABLE `rental_sales`;");
					DB::statement("TRUNCATE TABLE `rental_sales_item`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SIR'");
				break;
				case 'IT':	
					DB::statement("TRUNCATE TABLE `item_template`;");
				break;
				case 'CRMT':	
					DB::statement("TRUNCATE TABLE `crm_template`;");
				break;
				case 'CRMIN':	
					DB::statement("TRUNCATE TABLE `crm_info`;");
				break;
				case 'SOJ':	
					DB::statement("TRUNCATE TABLE `so_joborder`;");
					DB::statement("TRUNCATE TABLE `sales_order_item`;");
				break;
				case 'SOB':	
					DB::statement("TRUNCATE TABLE `sales_order_gi`;");
					DB::statement("TRUNCATE TABLE `sales_order_item`;");
					DB::statement("TRUNCATE TABLE `sales_order`;");
					DB::statement("TRUNCATE TABLE `sales_order_info`;");
					DB::statement("TRUNCATE TABLE `joborder_details`;");
					DB::statement("TRUNCATE TABLE `job_photos`;");
					DB::statement("TRUNCATE TABLE `goods_issued`;");
					DB::statement("TRUNCATE TABLE `goods_issued_item`;");
					DB::statement("DELETE FROM `item_log` WHERE `document_type` = 'GI'");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'GI'");
				break;
				case 'CV':
					DB::statement("TRUNCATE TABLE `contra_voucher`;");
					DB::statement("TRUNCATE TABLE `contra_voucher_details`;");
					DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'CV'");
				break;
			}
		}
		
		//DB::statement("UPDATE `item_unit` SET `received_qty`=0,`last_purchase_cost`=0,`pur_count`=0,`cost_avg`=0,`issued_qty`=0");
		//DB::statement("UPDATE `account_master` set `cl_balance`=0,`op_balance`=0,`fy_balance`=0;");
		//DB::statement("UPDATE `account_transaction` set `amount`=0;");
				
		
		Session::flash('message', 'Data removed successfully');
		return redirect('data_remove');
		
	}
	
	public function clearDB()
	{
		DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type`!='OB'");
		DB::statement("UPDATE `account_setting` set `voucher_no`=100");
		DB::statement("UPDATE `account_master` set `cl_balance`=0,`op_balance`=0,`fy_balance`=0");
		DB::statement("UPDATE `account_transaction` set `amount`=0");
		DB::statement("DELETE FROM `account_transaction` WHERE `account_master_id` IN(SELECT id FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND (`category` ='CUSTOMER' OR `category` ='SUPPLIER') )");
		DB::statement("DELETE FROM `account_master` WHERE `master_name`!='CASH CUSTOMERS' AND (`category` ='CUSTOMER' OR `category` ='SUPPLIER')");
		DB::statement("DELETE FROM `jobmaster` WHERE is_salary_job = 0");
		DB::statement("UPDATE `voucher_no` set `no`=100, `autoincrement`=1");
		DB::statement("TRUNCATE TABLE `assets_issued`");
		DB::statement("TRUNCATE TABLE `cheque`");
		DB::statement("TRUNCATE TABLE `credit_note`");
		DB::statement("TRUNCATE TABLE `credit_note_entry`");
		DB::statement("TRUNCATE TABLE `debit_note`");
		DB::statement("TRUNCATE TABLE `debit_note_entry`");
		DB::statement("TRUNCATE TABLE `customer_do`");
		DB::statement("TRUNCATE TABLE `customer_do_item`");
		DB::statement("TRUNCATE TABLE `customer_enquiry`;");
		DB::statement("TRUNCATE TABLE `customer_enquiry_item`;");
		DB::statement("TRUNCATE TABLE `customer_receipt`");
		DB::statement("TRUNCATE TABLE `customer_receipt_tr`");
		DB::statement("TRUNCATE TABLE `doc_department`");
		DB::statement("TRUNCATE TABLE `document_master`");
		DB::statement("TRUNCATE TABLE `employee`");
		DB::statement("TRUNCATE TABLE `employee_document`");
		DB::statement("TRUNCATE TABLE `expiry_docs`");
		DB::statement("TRUNCATE TABLE `goods_issued`");
		DB::statement("TRUNCATE TABLE `goods_issued_item`");
		DB::statement("TRUNCATE TABLE `goods_return`");
		DB::statement("TRUNCATE TABLE `goods_return_item`");
		DB::statement("TRUNCATE TABLE `groupcat`");
		DB::statement("TRUNCATE TABLE `header_footer`");
		DB::statement("TRUNCATE TABLE `itemmaster`");
		DB::statement("TRUNCATE TABLE `item_description`");
		DB::statement("TRUNCATE TABLE `item_location`");
		DB::statement("TRUNCATE TABLE `item_location_pi`");
		DB::statement("TRUNCATE TABLE `item_location_pr`");
		DB::statement("TRUNCATE TABLE `item_location_si`");
		DB::statement("TRUNCATE TABLE `item_location_sr`");
		DB::statement("TRUNCATE TABLE `item_log`");
		DB::statement("TRUNCATE TABLE `item_sale_log`");
		DB::statement("TRUNCATE TABLE `item_stock`");
		DB::statement("TRUNCATE TABLE `item_unit`");
		DB::statement("TRUNCATE TABLE `jobestimate_details`");
		DB::statement("TRUNCATE TABLE `jobinvoice_details`");
		DB::statement("TRUNCATE TABLE `joborder_details`");
		DB::statement("TRUNCATE TABLE `jobtype`");
		DB::statement("TRUNCATE TABLE `journal`");
		DB::statement("TRUNCATE TABLE `journal_entry`");
		DB::statement("TRUNCATE TABLE `location_transfer`");
		DB::statement("TRUNCATE TABLE `location_transfer_item`");
		DB::statement("TRUNCATE TABLE `onleave`");
		DB::statement("TRUNCATE TABLE `opening_balance_tr`");
		DB::statement("TRUNCATE TABLE `other_payment`");
		DB::statement("TRUNCATE TABLE `other_payment_tr`");
		DB::statement("TRUNCATE TABLE `other_receipt`");
		DB::statement("TRUNCATE TABLE `other_receipt_tr`");
		DB::statement("TRUNCATE TABLE `payment_voucher`");
		DB::statement("TRUNCATE TABLE `payment_voucher_entry`");
		DB::statement("TRUNCATE TABLE `payment_voucher_tr`");
		DB::statement("TRUNCATE TABLE `pdc_issued`");
		DB::statement("TRUNCATE TABLE `pdc_received`");
		DB::statement("TRUNCATE TABLE `petty_cash`");
		DB::statement("TRUNCATE TABLE `petty_cash_entry`");
		DB::statement("TRUNCATE TABLE `pi_other_cost`");
		DB::statement("TRUNCATE TABLE `po_other_cost`");
		DB::statement("TRUNCATE TABLE `purchase_invoice`");
		DB::statement("TRUNCATE TABLE `purchase_invoice_item`");
		DB::statement("TRUNCATE TABLE `purchase_order`");
		DB::statement("TRUNCATE TABLE `purchase_order_item`");
		DB::statement("TRUNCATE TABLE `purchase_order_info`");
		DB::statement("TRUNCATE TABLE `purchase_return`");
		DB::statement("TRUNCATE TABLE `purchase_return_item`");
		DB::statement("TRUNCATE TABLE `quotation`");
		DB::statement("TRUNCATE TABLE `quotation_info`");
		DB::statement("TRUNCATE TABLE `quotation_item`");
		DB::statement("TRUNCATE TABLE `quotation_sales`");
		DB::statement("TRUNCATE TABLE `quotation_sales_info`");
		DB::statement("TRUNCATE TABLE `quotation_sales_item`");
		DB::statement("TRUNCATE TABLE `receipt_voucher`");
		DB::statement("TRUNCATE TABLE `receipt_voucher_entry`");
		DB::statement("TRUNCATE TABLE `receipt_voucher_tr`");
		DB::statement("TRUNCATE TABLE `resign`");
		DB::statement("TRUNCATE TABLE `salesman`");
		DB::statement("TRUNCATE TABLE `sales_invoice`");
		DB::statement("TRUNCATE TABLE `sales_invoice_item`");
		DB::statement("TRUNCATE TABLE `sales_order`");
		DB::statement("TRUNCATE TABLE `sales_order_info`");
		DB::statement("TRUNCATE TABLE `sales_order_item`");
		DB::statement("TRUNCATE TABLE `sales_return`");
		DB::statement("TRUNCATE TABLE `sales_return_item`");
		DB::statement("TRUNCATE TABLE `stock_transferin`");
		DB::statement("TRUNCATE TABLE `stock_transferin_item`");
		DB::statement("TRUNCATE TABLE `stock_transferout`");
		DB::statement("TRUNCATE TABLE `stock_transferout_item`");
		DB::statement("TRUNCATE TABLE `supplier_do`");
		DB::statement("TRUNCATE TABLE `supplier_do_info`");
		DB::statement("TRUNCATE TABLE `supplier_do_item`");
		DB::statement("TRUNCATE TABLE `supplier_payment`");
		DB::statement("TRUNCATE TABLE `supplier_payment`");
		DB::statement("TRUNCATE TABLE `terms`");
		DB::statement("TRUNCATE TABLE `vehicle`");
		DB::statement("TRUNCATE TABLE `wage_entry`");
		DB::statement("TRUNCATE TABLE `wage_entry_items`");
		DB::statement("TRUNCATE TABLE `wage_entry_job`");
		DB::statement("TRUNCATE TABLE `wage_entry_others`");
		//Done By Sachu Alex 
		DB::statement("TRUNCATE TABLE `ms_customer`");
		DB::statement("TRUNCATE TABLE `ms_area`");
		DB::statement("TRUNCATE TABLE `ms_technician`");
		DB::statement("TRUNCATE TABLE `ms_worktype`");
		DB::statement("TRUNCATE TABLE `ms_jobmaster`");
		DB::statement("TRUNCATE TABLE `ms_workenquiry`");
		DB::statement("TRUNCATE TABLE `ms_workorder`");
		DB::statement("TRUNCATE TABLE `manufacture`;");
		DB::statement("TRUNCATE TABLE `manufacture_item`;");
		DB::statement("TRUNCATE TABLE `sdo_other_cost`;");
		DB::statement("TRUNCATE TABLE `job_photos`;");
		DB::statement("TRUNCATE TABLE `sti_other_cost`;");

	//JAN22
		DB::statement("TRUNCATE TABLE `journal_voucher_tr`;");
		DB::statement("TRUNCATE TABLE `purchase_split`;");
		DB::statement("TRUNCATE TABLE `purchase_split_item`;");
		DB::statement("TRUNCATE TABLE `sales_split`;");
		DB::statement("TRUNCATE TABLE `sales_split_item`;");
		DB::statement("TRUNCATE TABLE `crm_followup`;");
		DB::statement("TRUNCATE TABLE `con_location`;");
		DB::statement("TRUNCATE TABLE `con_location_sr`;");
		DB::statement("TRUNCATE TABLE `item_location_ti`;");
		DB::statement("TRUNCATE TABLE `contract_type`;");
		DB::statement("TRUNCATE TABLE `contract`;");
		DB::statement("TRUNCATE TABLE `machine_read`;");
		DB::statement("TRUNCATE TABLE `buildingmaster`;");
		DB::statement("TRUNCATE TABLE `contract_building`;");
		DB::statement("TRUNCATE TABLE `contra_type`;");
		DB::statement("TRUNCATE TABLE `contract_type`;");
		DB::statement("TRUNCATE TABLE `contract_prepaid`;");
		DB::statement("TRUNCATE TABLE `contract_rvs`;");
		DB::statement("TRUNCATE TABLE `contract_jv`;");
		DB::statement("TRUNCATE TABLE `contract_settlement`;");
		DB::statement("TRUNCATE TABLE `manual_journal`;");
		DB::statement("TRUNCATE TABLE `manual_journal_entry`;");
		DB::statement("TRUNCATE TABLE `manual_journal_voucher_tr`;");
		DB::statement("TRUNCATE TABLE `package_master`;");
		DB::statement("TRUNCATE TABLE `joborder_pkgs`;");
		DB::statement("TRUNCATE TABLE `contract_pvs`;");
		DB::statement("TRUNCATE TABLE `material_requisition`;");
		DB::statement("TRUNCATE TABLE `material_requisition_item`;");
		DB::statement("TRUNCATE TABLE `mfg_items`;");
		DB::statement("TRUNCATE TABLE `mfg_other_cost`;");
		DB::statement("TRUNCATE TABLE `leads`;");
		DB::statement("TRUNCATE TABLE `followups`;");
		DB::statement("TRUNCATE TABLE `flat_master`;");
		DB::statement("TRUNCATE TABLE `duration`;");
		DB::statement("TRUNCATE TABLE `division`;");
		DB::statement("TRUNCATE TABLE `machine`;");
		DB::statement("TRUNCATE TABLE `paper`;");
		DB::statement("TRUNCATE TABLE `emp_photos`;");
		DB::statement("TRUNCATE TABLE `ms_location`;");
		DB::statement("TRUNCATE TABLE `ms_wo_time`;");
		DB::statement("TRUNCATE TABLE `other_voucher_tr`;");
		DB::statement("TRUNCATE TABLE `production`;");
		DB::statement("TRUNCATE TABLE `production_item`;");
		DB::statement("TRUNCATE TABLE `supplier_payment_tr`;");
		DB::statement("DELETE FROM `location` WHERE `is_default` = 0");
		//by veena...
		DB::statement("TRUNCATE TABLE `consignee`;");
		DB::statement("TRUNCATE TABLE `shipper`;");
		DB::statement("TRUNCATE TABLE `collection_type`;");
		DB::statement("TRUNCATE TABLE `delivery_type`;");
		DB::statement("TRUNCATE TABLE `cargo_vehicle`;");
		DB::statement("TRUNCATE TABLE `cargo_destination`;");
		DB::statement("TRUNCATE TABLE `cargo_receipt`;");
		DB::statement("TRUNCATE TABLE `cargo_despatch_bill`;");
	    DB::statement("TRUNCATE TABLE `cargo_despatch_entry`;");
		DB::statement("TRUNCATE TABLE `cargo_waybill`;");
		DB::statement("TRUNCATE TABLE `cargo_waybill_entry`;");
		DB::statement("TRUNCATE TABLE `purchase_rental`;");
		DB::statement("TRUNCATE TABLE `purchase_rental_item`;");
		DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'PIR'");
		DB::statement("TRUNCATE TABLE `rental_sales`;");
		DB::statement("TRUNCATE TABLE `rental_sales_item`;");
		DB::statement("DELETE FROM `account_transaction` WHERE `voucher_type` = 'SIR'");
		DB::statement("TRUNCATE TABLE `item_template`;");
		DB::statement("TRUNCATE TABLE `crm_template`;");
		DB::statement("TRUNCATE TABLE `crm_info`;");
		DB::statement("TRUNCATE TABLE `so_joborder`;");
		DB::statement("TRUNCATE TABLE `sales_order_gi`;");
		DB::statement("TRUNCATE TABLE `contra_voucher`;");
		DB::statement("TRUNCATE TABLE `contra_voucher_details`;");
		
		DB::statement("UPDATE `voucher_account` set `account_id`=0");
		DB::statement("UPDATE `other_account_setting` set `account_id`=0");
		
		//**********************TO DO
		//MATERIAL REQUISITION & ITS ITEMS,ROW MATERIALS OR MFG ITEMS
					
		Session::flash('message', 'All data has been removed successfully');
		return redirect('data_remove');
	}
	
} 

