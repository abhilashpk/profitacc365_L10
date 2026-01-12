<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StockTransferout\StockTransferoutInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\UpdateUtility;

use App\Http\Requests;
use Notification;
use Session;
use DB;
use Auth;
use App;


class StockTransferoutController extends Controller
{
   protected $accountsetting;
   protected $stock_transferout;
   protected $mod_autocost;
   protected $forms;
   protected $itemmaster;
	
	public function __construct(AccountSettingInterface $accountsetting,FormsInterface $forms, StockTransferoutInterface $stock_transferout,ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountsetting = $accountsetting;
		$this->stock_transferout = $stock_transferout;
		$this->itemmaster = $itemmaster;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('STO');
		$this->middleware('auth');
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();

	}

	
	public function index()
	{
		$data = array();
		$stocktrans = $this->stock_transferout->stockTransList();
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		$item = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.stocktransferout.index')
		->withCategory($category)
		->withSubcategory($subcategory)
		->withGroup($group)
		->withDepartments($departments)
		->withSubgroup($subgroup)
		->withItem($item)
		->withType('')
					->withStocktrans($stocktrans)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function add() {
		
		$data = array();
		$lastid = DB::table('stock_transferout')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=22,$is_dept,$deptid);
		
		return view('body.stocktransferout.add')
					->withVouchers($vouchers)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withMvcount($mvcount)
					->withData($data);
	}
	
	public function save(Request $request) {
		
		if( $this->stock_transferout->create($request->all()) ) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
			}
			Session::flash('message', 'Stock transfered successfully.');
		} else
			Session::flash('error', 'Something went wrong, Stock failed to transfer!');
		
		return redirect('stock_transferout/add');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->stock_transferout->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}

	public function checkVchrNo(Request $request) {

		$check = $this->stock_transferin->check_voucher_no($request->get('voucher_no'), $request->get('deptid'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	
	public function destroy($id)
	{
		$this->stock_transferout->delete($id);
		//AUTO COST REFRESH CHECK ENABLE OR NOT
		if($this->mod_autocost->is_active==1) {
			$arritems = [];
			$items = DB::table('stock_transferout_item')->where('stock_transferout_id',$id)->select('item_id')->get();
			foreach($items as $rw) {
				$arritems[] = $rw->item_id;
			}
			$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
		}
		Session::flash('message', 'Stock transfer deleted successfully.');
			
		return redirect('stock_transferout');
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->trout_id][] = $item;
		
		return $childs;
	}
	
	public function edit($id) { 

		$data = array();
		$orderrow = $this->stock_transferout->findRow($id);
		$orditems = $this->stock_transferout->getItems($id);
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $orderrow->department_id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=22,$is_dept,$deptid);
		$getItemLocation = $this->itemmaster->getItemLocation($id,'TO');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'TO') );
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		return view('body.stocktransferout.edit')
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withMvcount($mvcount)
					->withData($data);
					
		
	}
	
	public function update($id, Request $request)
	{
		//echo '<pre>';print_r($request->all());exit;
		if($this->stock_transferout->update($id, $request->all())) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
			}
			Session::flash('message', 'Stock transfer updated successfully');
		} else
			Session::flash('error', 'Something went wrong, Stock failed to update!');
		
		return redirect('stock_transferout');
	}
	
		public function viewonly($id) { 

		$data = array();
		$orderrow = $this->stock_transferout->findRow($id);
		$orditems = $this->stock_transferout->getItems($id);
		
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				$deptid = $orderrow->department_id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			$deptid = '';
		}
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=22,$is_dept,$deptid);
		$getItemLocation = $this->itemmaster->getItemLocation($id,'TO');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'TO') );
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		return view('body.stocktransferout.viewonly')
					->withOrderrow($orderrow)
					->withOrditems($orditems)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withMvcount($mvcount)
					->withData($data);
					
		
	}
	
	public function getPrint($id)
	{
		$attributes['document_id'] = $id;
		$result = $this->stock_transferout->getDoc($attributes); //echo '<pre>';print_r($result);exit;
		$titles = ['main_head' => 'Stock Transferout','subhead' => 'Stock Transferout'];
		return view('body.stocktransferout.print')
					->withDetails($result['details'])
					->withTitles($titles)
					->withItems($result['items']);
		
	}
	protected function makeTreeVoucher($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($result as $item)
				//$childs[$item->item_name][] = $item;
				$childs[$item->item_name][] = $item;
		return $childs;
	}
	protected function makeTreeid($result)
	{
		$childs = array();
		foreach($result as $item)
		
			$childs[$item->id][] = $item;
		
		return $childs;
	}
	public function getSearch(Request $request)
	{
		$data = array();
		$dname = '';
		
		$itemid = '';
		$voucher_head  = '';
    	//$report = $this->stock_transferout->getr($request->all());
		//echo '<pre>';print_r($report);exit;
		 if(Session::get('department')==1) {
		 	if($request->get('department_id')!='') {
		 		$rec = DB::table('department')->where('id', $request->get('department_id'))->select('name')->first();
				$dname = $rec->name;
		 	}
		 }
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Stock Transfer Out';
			$report = $this->stock_transferout->getr($request->all());
			$reports = $this->makeTreeid($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		
		}else if($request->get('search_type')=="detail") {
			$voucher_head = 'Stock Transfer Out ';
			$report =$this->stock_transferout->getr($request->all());
			
			//$report = $this->stock_transferin->getReport($request->all());
		    $reports = $this->makeTreeid($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.stocktransferout.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
					->withItem($itemid)
					->withTitles($titles)
					
					->withSettings($this->acsettings)
					->withDname($dname)
					->withData($data);
	}
	
	public function dataExport(Request $request)
	{
		$data = array();
		$request->merge(['type' => 'export']);
		$report = $this->sales_invoice->getReport($request->all());
		
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Purchase Invoice Summary';
			$report = $this->sales_invoice->getReport($request->all());
			$reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if($request->get('supplier_id')!==null)
				$supid = implode(',', $request->get('supplier_id'));
			else
				$supid = '';
			}
		
		else if($request->get('search_type')=="detail") {
			$voucher_head = 'Purchase Invoice Detail';
			$report = $this->sales_invoice->getReport($request->all());
		    $reports = $this->makeTreeSup($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} else if($request->get('search_type')=="item") {
			$voucher_head = 'Sales Invoice by Itemwise';
			$report = $this->sales_invoice->getReportsales($request->all());
			$reports = $this->groupbyItemwise($report);
			
			//echo '<pre>';print_r($reports);exit;
			if($request->get('item_id')!==null)
				$itemid = implode(',', $request->get('item_id'));
			else
				$itemid = '';
		
		
	}else if($request->get('search_type')=='customer') {
	//	
			$voucher_head = 'Sales Invoice by customerwise';
			
		    $reports = $this->makeTreeSup($report);
		
			if($request->get('supplier_id')!==null)
				$cusid = implode(',', $request->get('supplier_id'));
			else
				$cusid = '';
		}
		// echo '<pre>';print_r($reports);exit;
		
		if($request->get('search_type')=='sales_register') {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Salesman','Gross Amt.','Discount','VAT Amt.','Net Total'];
			$i = $gross_total = $discount_total = $vat_total = $net_total = 0;
			foreach ($reports as $row) {
				$i++;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'], 
								  'vchr_dt' => date('d-m-Y',strtotime($row['voucher_date'])),
								  'customer' => $row['master_name'],
								  'trn' => $row['vat_no'],
								  'salesman' => $row['salesman'],
								  'gross' => $row['total'],
								  'discount' => $row['discount'],
								  'vat' => $row['vat_amount'],
								  'total' => $row['net_total']
								];
			}
			
			$datareport[] = ['','','','','','Total:',number_format($gross_total,2), number_format($discount_total,2), number_format($vat_total,2), number_format($net_total,2)];
		
		} else if($request->get('search_type')=='tax_code') {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Salesman','Gross Amt.','Discount','VAT Amt.','Net Total'];
			$i=$gtotal=$gdiscount=$gvat_amount=$gamount_total=0;
			
			foreach($reports as $key => $report) {
				$i++;
				if($key=='SR')
					$datareport[] = ['Tax Code:', 'SR'];
				else if($key=='EX')
					$datareport[] = ['Tax Code:', 'EX'];
				else if($key=='ZR')
					$datareport[] = ['Tax Code:', 'ZR'];
				
				$nettotal=$netdiscount=$netvat_amount=$net_amount_total=0;
				foreach($report as $row) {
					
					$datareport[] = [ 'si' => $i,
								  'po' => $row->voucher_no, 
								  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
								  'customer' => $row->master_name,
								  'trn' => $row->vat_no,
								  'salesman' => $row->salesman,
								  'gross' => $row->total,
								  'discount' => $row->discount,
								  'vat' => $row->vat_amount,
								  'total' => $row->net_total
								];
								
				  $nettotal += $row->total;
				  $netdiscount += $row->discount;
				  $netvat_amount += $row->vat_amount;
				  $net_amount_total += $row->net_total;
								
				}
				
				$datareport[] = ['','','','','','Total:',number_format($nettotal,2), number_format($netdiscount,2), number_format($netvat_amount,2), number_format($net_amount_total,2)];
				
				$gtotal += $nettotal;
				$gdiscount += $netdiscount;
				$gvat_amount += $netvat_amount;
				$gamount_total += $net_amount_total;
			}
			
			$datareport[] = ['','','','','','Net Total:',number_format($gtotal,2), number_format($gdiscount,2), number_format($gvat_amount,2), number_format($gamount_total,2)];
		
		} else if($request->get('search_type')=='itemwise') {
			
			$netqty = $netlnt = $nettl = 0;
			foreach ($reports as $report) {
				$datareport[] = ['Customer Name:',$report[0]['master_name'],'', 'Voucher No:', $report[0]['voucher_no'],'','Voucher Date:',date('d-m-Y',strtotime($report[0]['voucher_date']))];
				$datareport[] = ['Item Code','Item Name', 'Quantity', 'Unit Cost','Total','Line Total'];
				
				$qty = $lnt = $tl = 0;
				foreach($report as $row) {
				    $qty += $row['quantity']; $tl += $row['item_total']; $lnt += $row['item_total']+$row['vat_amount'];
					$datareport[] = [ 'code' => $row['item_code'],
									  'name' => $row['item_name'],
									  'qty' => $row['quantity'],
									  'cost' => $row['unit_price'],
									  'total' => $row['item_total'],
									  'total' => $row['item_total']+$row['vat_amount']
									];
				}
				$netqty += $qty; $nettl += $tl; $netlnt += $lnt;
				$datareport[] = ['','Total:',$qty,'',number_format($tl,2),number_format($lnt,2)];
				$datareport[] = ['','','',''];
			}
			
			$datareport[] = ['','Net Total:',$netqty,'',number_format($nettl,2),number_format($netlnt,2)];
			
		} else if($request->get('search_type')=='customer_wise') {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Salesman','Quantity','Gross Amt.','Discount','VAT Amt.','Net Total'];
			$i = $gross_total = $discount_total = $vat_total = $net_total = $netqty = 0;
			foreach ($reports as $row) {
				//echo '<pre>';print_r($reports);exit;
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'vchr_dt' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'customer' => $row['master_name'],
									  'trn' => $row['vat_no'],
									  'salesman' => $row['salesman'],
									  'quantity' => $row['quantity'],
									  'gross' => $row['total'],
									  'discount' => $row['discount'],
									  'vat' => $row['vat_amount'],
									  'total' => $row['net_total']
									];
									
				  $gross_total += $row->total;
				  $discount_total += $row->discount;
				  $vat_total += $row->vat_amount;
				  $net_total += $row->net_total;
				  $netqty += $row['quantity'];
			}
			
			$datareport[] = ['','','','','Total:','',$netqty,number_format($gross_total,2), number_format($discount_total,2), number_format($vat_total,2), number_format($net_total,2)];
			
		} else {
			
			$datareport[] = ['SI.No.','SI.#', 'Vchr.Date', 'Customer','TRN No','Quantity','Gross Amt.','Discount','VAT Amt.','Net Total'];
			$i = 0;
			$gross_total = $discount_total = $vat_total = $net_total = 0;
			$gross_sr = $discount_sr = $vat_sr = $net_sr = 0;
			$gross_ex = $discount_ex = $vat_ex = $net_ex = 0;
			$gross_zr = $discount_zr = $vat_zr = $net_zr = 0;
			
			foreach ($reports as $row) {
					$i++;
					
					if($row->tax_code=='SR') {
					  $gross_sr += $row->subtotal;
					  $vat_sr += $row->vat_amount;
					  $discount_sr += $row->discount;
					  $net_sr += $row->net_total;
					  
				  } else  if($row->tax_code=='EX') {
					  $gross_ex += $row->subtotal;
					  $vat_ex += $row->vat_amount;
					  $discount_ex += $row->discount;
					  $net_ex += $row->net_total;
				  } else {
					  $gross_zr += $row->subtotal;
					  $vat_zr += $row->vat_amount;
					  $discount_zr += $row->discount;
					  $net_zr += $row->net_total;
				  }
										  
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'vchr_dt' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'customer' => $row['master_name'],
									  'trn' => $row['vat_no'],
									  'quantity' => $row['quantity'],
									  'gross' => $row['total'],
									  'discount' => $row['discount'],
									  'vat' => $row['vat_amount'],
									  'total' => $row['net_total']
									];
									
				  $gross_total += $row->total;
				  $discount_total += $row->discount;
				  $vat_total += $row->vat_amount;
				  $net_total += $row->net_total;
			}
			
			$datareport[] = ['','','','','','Total in SR:',number_format($gross_sr,2), number_format($discount_sr,2), number_format($vat_sr,2), number_format($net_sr,2)];
			$datareport[] = ['','','','','','Total in EX:',number_format($gross_ex,2), number_format($discount_ex,2), number_format($vat_ex,2), number_format($net_ex,2)];
			$datareport[] = ['','','','','','Total in ZR:',number_format($gross_zr,2), number_format($discount_zr,2), number_format($vat_zr,2), number_format($net_zr,2)];
			$datareport[] = ['','','','','','Total:',number_format($gross_total,2), number_format($discount_total,2), number_format($vat_total,2), number_format($net_total,2)];
		}
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($voucher_head);
        $excel->setCreator('NumakPro ERP')->setCompany(Session::get('company'));
        $excel->setDescription($voucher_head);

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	}
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptSTI($vid=22, $id); 
		
		foreach($depts as $row) {
			
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			  $result[] = array('voucher_no' => $voucher, 
								'cr_account_name' => ($row->master_name!='')?$row->master_name:'', 
								'cr_id' => ($row->cr_account_master_id!='')?$row->cr_account_master_id:'',
								'dr_account_name' => ($row->dr_account!='')?$row->dr_account:'', 
								'dr_id' => ($row->dr_account_master_id!='')?$row->dr_account_master_id:'',
								'voucher_name' => $row->voucher_name,
								'voucher_id' => $row->voucher_id
							);

		}
		
		return $result;
	}
	
}

