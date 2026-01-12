<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StockTransferin\StockTransferinInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Http\Requests;
use Notification;
use Input;
use Response;
use Session;
use DB;
use Excel;
use Auth;
use App;

class StockTransferinController extends Controller
{
   protected $accountsetting;
   protected $stock_transferin;
   protected $mod_autocost;
   protected $forms;
   protected $itemmaster;
   
	public function __construct(AccountSettingInterface $accountsetting,FormsInterface $forms, StockTransferinInterface $stock_transferin,ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountsetting = $accountsetting;
		$this->stock_transferin = $stock_transferin;
		$this->itemmaster = $itemmaster;
		$this->middleware('auth');
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('STI');
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}

	
	public function index()
	{
		$data = array();
		$stocktrans = $this->stock_transferin->stockTransList();
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
		
		return view('body.stocktransferin.index')
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
		$lastid = DB::table('stock_transferin')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->select('id')->first();
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=21);//echo '<pre>';print_r($vouchers);exit;
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		//echo '<pre>';print_r($mvcount);exit;
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
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=21,$is_dept,$deptid); //echo $deptid.'<pre>';print_r($vouchers);exit;
		
		return view('body.stocktransferin.add')
					->withVouchers($vouchers)
					->withPrintid($lastid)
					->withIsdept($is_dept)
					->withFormdata($this->formData)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withMvcount($mvcount)
					->withData($data);
	}
	
	public function save() {
		//echo '<pre>';print_r(Input::all());exit;
		if( $this->stock_transferin->create(Input::all()) ) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity(Input::get('item_id'),$this->acsettings);
			}
			Session::flash('message', 'Stock transfered successfully.');
		} else
			Session::flash('error', 'Something went wrong, Stock failed to transfer!');
		
		return redirect('stock_transferin/add');
	}
	
	public function checkRefNo() {

		$check = $this->stock_transferin->check_reference_no(Input::get('reference_no'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}

	public function checkVchrNo() {

		$check = $this->stock_transferin->check_voucher_no(Input::get('voucher_no'), Input::get('deptid'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function destroy($id)
	{
		$this->stock_transferin->delete($id);
		//AUTO COST REFRESH CHECK ENABLE OR NOT
		if($this->mod_autocost->is_active==1) {
			$arritems = [];
			$items = DB::table('stock_transferin_item')->where('stock_transferin_id',$id)->select('item_id')->get();
			foreach($items as $rw) {
				$arritems[] = $rw->item_id;
			}
			$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
		}
		Session::flash('message', 'Stock transfer deleted successfully.');
			
		return redirect('stock_transferin');
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->trin_id][] = $item;
		
		return $childs;
	}
	
	public function edit($id) { 

		$data = array();
		$orderrow = $this->stock_transferin->findRow($id);
		$orditems = $this->stock_transferin->getItems($id);
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'TI');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'TI') );
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit); exit;
		$ocrow = DB::table('sti_other_cost')
						->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
						->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
						->where('sti_other_cost.transfer_id',$id)
						->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
						->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
						->get();
		
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
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=21,$is_dept,$deptid); //echo $deptid.'<pre>';print_r($vouchers);exit;
		
		return view('body.stocktransferin.edit')
					->withOcrow($ocrow)
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
	
	public function update($id)
	{
		
		if($this->stock_transferin->update($id, Input::all())) {
			//AUTO COST REFRESH CHECK ENABLE OR NOT
			if($this->mod_autocost->is_active==1) {
				$this->objUtility->reEvalItemCostQuantity(Input::get('item_id'),$this->acsettings);
			}
			
			Session::flash('message', 'Stock transfer updated successfully');
		} else
			Session::flash('error', 'Something went wrong, Stock failed to update!');
		
		return redirect('stock_transferin');
	}
	
	
		public function viewonly($id) { 

		$data = array();
		$orderrow = $this->stock_transferin->findRow($id);
		$orditems = $this->stock_transferin->getItems($id);
		$mvcount=DB::table('manufacture')->where('deleted_at','0000-00-00 00:00:00')->count();
		$getItemLocation = $this->itemmaster->getItemLocation($id,'TI');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'TI') );
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit); exit;
		$ocrow = DB::table('sti_other_cost')
						->join('account_master AS DrAC', 'DrAC.id', '=', 'sti_other_cost.dr_account_id')
						->join('account_master AS CrAC', 'CrAC.id', '=', 'sti_other_cost.cr_account_id')
						->where('sti_other_cost.transfer_id',$id)
						->where('sti_other_cost.deleted_at','0000-00-00 00:00:00')
						->select('sti_other_cost.*','DrAC.master_name AS dr_name','CrAC.master_name AS cr_name')
						->get();
		
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
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=21,$is_dept,$deptid); //echo $deptid.'<pre>';print_r($vouchers);exit;
		
		return view('body.stocktransferin.viewonly')
					->withOcrow($ocrow)
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
		$result = $this->stock_transferin->getDoc($attributes); //echo '<pre>';print_r($result);exit;
		$titles = ['main_head' => 'Stock Transferin','subhead' => 'Stock Transferin'];
		return view('body.stocktransferin.print')
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
				$childs[$item->voucher_no][] = $item;
		return $childs;
	}
	protected function makeTreeitems($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($result as $item)
				$childs[$item->voucher_no][] = $item;
				
		return $childs;
	}
	protected function makeTreeid($result)
	{
		$childs = array();
		foreach($result as $item)
		
			$childs[$item->id][] = $item;
		
		return $childs;
	}
	protected function makeTreeitem($result)
	{
		$childs = array();
		foreach($result as $items)
			foreach($result as $item)
			$childs[$item->item_name][] = $item;
				
		return $childs;
	}
	public function getSearch()
	{
		$data = array();
	//	echo '<pre>';print_r(Input::all());exit;
		$dname = '';
		
		$itemid = '';
		$voucher_head  = '';
		$report = $this->stock_transferin->getReport(Input::all());
		//echo '<pre>';print_r($report);exit;
		 if(Session::get('department')==1) {
		 	if(Input::get('department_id')!='') {
		 		$rec = DB::table('department')->where('id', Input::get('department_id'))->select('name')->first();
				$dname = $rec->name;
		 	}
		 }
		
		if(Input::get('search_type')=="summary")
		{
			$voucher_head = 'Stock Transfer In Summary';
			$report = $this->stock_transferin->getReport(Input::all());
			$reports = $this->makeTreeVoucher($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		
		}else if(Input::get('search_type')=="detail") {
			$voucher_head = 'Stock Transfer In Detail';
			$report =$this->stock_transferin->getReport(Input::all());
			
			//$report = $this->stock_transferin->getReport(Input::all());
		    $reports = $this->makeTreeid($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		}
		//echo '<pre>';print_r($reports);exit;
		return view('body.stocktransferin.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withI(0)
					->withItem(Input::get('item_id'))
					->withGroup(Input::get('group_id'))
					->withSubgroup(Input::get('subgroup_id'))
					->withCategory(Input::get('category_id'))
					->withSubcategory(Input::get('subcategory_id'))
					->withTitles($titles)
					
					->withSettings($this->acsettings)
					->withDname($dname)
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		//echo '<pre>';print_r(Input::all());exit;
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		Input::merge(['type' => 'export']);

		
		if(Input::get('search_type')=="summary")
		{
			$voucher_head = 'Stock Transfer In Summary';
			$reports = $this->stock_transferin->getReportexcel(Input::all());
		//	$reports = $this->makeTreeVoucher($report);
			//echo '<pre>';print_r($reports);exit;
		
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = ['STI No:','STI Date', 'Debit Account','Gross Amt.','Discount.','Net Total'];
			$i=$total=$gross=$dis=0;
			
			foreach ($reports as $row) {
				//echo '<pre>';print_r($row);exit;
					$i++;
					$datareport[] = [ 
									 'voucher' => $row->voucher_no,
									 'date'=>date('d-m-Y',strtotime($row->voucher_date)),
									 'name'=>$row->name_dr,
									  'gross' => $row ->total_amt,
									  'discount' => $row ->discount,
									  'total' => $row->net_total
									];
									$total += number_format($row->net_total,2);
								$gross += number_format($row ->total_amt,2);
								$dis+=number_format($row ->discount,2);
			}
			 $datareport[] = ['','','','','',''];			
		     $datareport[] = ['','','Total:',$gross,$dis,$total];
		}
		elseif(Input::get('search_type')=="detail") {
			$voucher_head = 'Stock Transfer In Detail';
			$reports = $this->stock_transferin->getReportexcel(Input::all());
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = ['SI.No.','STI#','Vchr.Date', 'Debit Account','Item Name','Qnty','Rate','Gross Amt','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row->voucher_no,
									  'vdate' => date('d-m-Y',strtotime($row->voucher_date)),
									  'name' => $row->name_dr,
									  'item' => $row->item_name,
									 'qty'=>$row->quantity,
									  'rate' => $row->price,
									 'gross' => $row->quantity*$row->price,
									  'total' => $row->item_total
									];
			}
			//$reports = $this->makeTree($reports);
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
		
		$depts = $this->accountsetting->getVoucherByDeptSTI($vid=21, $id); 
		
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
