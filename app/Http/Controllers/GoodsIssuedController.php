<?php

namespace App\Http\Controllers;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\GoodsIssued\GoodsIssuedInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\UpdateUtility;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Response;
use App;
use Excel;
use Auth;
use DB;

class GoodsIssuedController extends Controller
{

	protected $itemmaster;
	protected $accountmaster;
	protected $voucherno;
	protected $goods_issued;
	protected $accountsetting;
	protected $mod_autocost;
	protected $forms;
	
	public function __construct(GoodsIssuedInterface $goods_issued, ItemmasterInterface $itemmaster, AccountMasterInterface $accountmaster, VoucherNoInterface $voucherno, AccountSettingInterface $accountsetting,FormsInterface $forms) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->middleware('auth');
		$this->itemmaster = $itemmaster;
		$this->accountmaster = $accountmaster;
		$this->voucherno = $voucherno;
		$this->goods_issued = $goods_issued;
		$this->accountsetting = $accountsetting;
		$this->forms = $forms;
		$this->formData = $this->forms->getFormData('GIN');
		
		$this->mod_autocost = DB::table('parameter2')->where('keyname', 'mod_autocost_refresh')->where('status',1)->select('is_active')->first();
		$this->objUtility = new UpdateUtility();
	}
	
    public function index() {
		
		//Session::put('cost_accounting', 0);
		$data = array();
		$orders = [];//$this->goods_issued->goodsIssuedList();//echo '<pre>';print_r($orders);exit;
		$job = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		/*$job_id=DB::table('goods_issued_item')
		                    ->leftjoin('goods_issued','goods_issued.id','=','goods_issued_item.goods_issued_id')
		                     ->where('goods_issued.status',1)->where('goods_issued.deleted_at','0000-00-00 00:00:00')
		                     ->where('goods_issued.is_itemjob',0)->where('goods_issued_item.job_id',0)
							  ->select('goods_issued_item.*','goods_issued.job_id AS job')->get();
		foreach($job_id as $row){
		    DB::table('goods_issued_item')->where('id',$row->id)->update(['job_id' => $row->job]);
		}			*/		  
		//echo '<pre>';print_r($job_id);exit;
		if(Session::get('department')==1) {
			$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			$is_dept = true;
		} else {
			$departments = []; $is_dept = false;
		}
		return view('body.goodsissued.index')
		            ->withJob($job)
					->withType('')
					->withOrders($orders)
					->withSettings($this->acsettings)
					->withDepartments($departments)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'voucher_no',
							1 => 'voucher_date',
                            2 => 'jobname',
                            3 => 'net_amount'
                        );
						
		$totalData = $this->goods_issued->goodsIssuedListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'goods_issued.id';//$columns[$request->input('order.0.column')];
        $dir = 'desc';//$request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->goods_issued->goodsIssuedList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->goods_issued->goodsIssuedList('count', $start, $limit, $order, $dir, $search);
		
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','GIN')
							->select('report_view_detail.name','report_view_detail.id')
							->get();
							
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('goods_issued/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('goods_issued/print/'.$row->id);
				$viewonly =  url('goods_issued/viewonly/'.$row->id);
                $nestedData['id'] = $row->id;
                $nestedData['voucher_no'] = $row->voucher_no;
				$nestedData['voucher_date'] = date('d-m-Y', strtotime($row->voucher_date));
				$nestedData['jobname'] = $row->jobname;
				$nestedData['net_amount'] = number_format($row->net_amount,2);
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['viewonly'] = "<p><a href='{$viewonly}' class='btn btn-info btn-xs' target='_blank'><i class='glyphicon glyphicon-eye-open'></i></a></p>";
																
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
											<span class='glyphicon glyphicon-trash'></span>";
				
				$opts = '';					
				foreach($prints as $doc) {
					$opts .= "<li role='presentation'><a href='{$print}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}
				
				$nestedData['print'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
											
                $data[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
	}
	
	public function add(Request $request, $id = null, $doctype = null) {

		$data = $vouchers = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$res = $this->voucherno->getVoucherNo('GI');
		//$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=13);
		$vno = $res->no;
		$settings = $this->accountsetting->getAccountPeriod();//echo '<pre>';print_r($vouchers);exit;
		$footertxt = DB::table('header_footer')->where('doc','GI')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
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
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=13,$is_dept,$deptid); //echo '<pre>';print_r($vouchers);exit;

		
		foreach($departments as $dept) { 
    		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=13,$is_dept,$dept->id); //echo '<pre>';print_r($vouchers);exit;
    		if(!$vouchers->isEmpty()) { 
    		    break; 
    		}
        }
		
		return view('body.goodsissued.add')
					->withItems($itemmaster)
					->withSettings($settings)
					->withVoucherno($vno)
					->withVoucherno($res)
					->withVouchers($vouchers)
					->withFormdata($this->formData)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withDeptid($deptid)
					->withFooter(isset($footertxt)?$footertxt->description:'')
					->withData($data);
	}
	
		
	public function getVoucher($id) {
		
		 $row = $this->accountsetting->getVoucherAcById($id);
		 if($row->voucher_no != '' || $row->voucher_no != null) {
			 if($row->is_prefix==0)
				 $voucher = $row->voucher_no+1;
			 else {
				 $no = (int)$row->voucher_no+1;
				 $voucher = $row->prefix.''.$no;
			 }
		 }
		 return $result = array('voucher_no' => $voucher, 
								'cr_account_id' => ($row->codecr!='')?$row->codecr:'', 
								'cr_account_name' => ($row->accountcr!='')?$row->accountcr:'', 
								'cr_id' => ($row->idcr!='')?$row->idcr:'',
								'dr_account_id' => ($row->codedr!='')?$row->codedr:'', 
								'dr_account_name' => ($row->accountdr!='')?$row->accountdr:'', 
								'dr_id' => ($row->iddr!='')?$row->iddr:''
							);

	}
	
	public function save(Request $request, $id = null) {
		//echo '<pre>';print_r($request->all());exit;
		$this->goods_issued->create($request->all());
		//AUTO COST REFRESH CHECK ENABLE OR NOT
		if($this->mod_autocost->is_active==1) {
			$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
		}
		Session::flash('message', 'Goods Issued Note added successfully.');
		return redirect('goods_issued');
	}
	
	public function destroy($id)
	{
		$this->goods_issued->delete($id);
		//AUTO COST REFRESH CHECK ENABLE OR NOT
		if($this->mod_autocost->is_active==1) {
			$arritems = [];
			$items = DB::table('goods_issued_item')->where('goods_issued_id',$id)->select('item_id')->get();
			foreach($items as $rw) {
				$arritems[] = $rw->item_id;
			}
			$this->objUtility->reEvalItemCostQuantity($arritems,$this->acsettings);
		}
		//check accountmaster name is already in use.........
		// code here ********************************
		Session::flash('message', 'Goods Issued Note deleted successfully.');
		return redirect('goods_issued');
	}
	
	public function checkRefNo(Request $request) {

		$check = $this->goods_issued->check_reference_no($request->get('reference_no'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkInvoice(Request $request) {

		$check = $this->goods_issued->check_invoice_id( $request->get('purchase_invoice_id') );
		$isAvailable = ($check) ? false : true;
		echo $isAvailable;
	}
	public function checkVchrNo(Request $request) {

		$check = $this->goods_issued->check_voucher_no($request->get('voucher_no'), $request->get('deptid'), $request->get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	private function makeTreeArr($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->gi_id][] = $item;
		
		return $childs;
	}
	
	public function edit($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$orderrow = $this->goods_issued->findPOdata($id);
		$orditems = $this->goods_issued->getItems($id); //echo '<pre>';print_r($orditems);exit;
		
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
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=13,$is_dept,$deptid);
		
		$getItemLocation = $this->itemmaster->getItemLocation($id,'GI');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'GI') );
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit);exit;
		return view('body.goodsissued.edit')
					->withItems($itemmaster)
					->withOrditems($orditems)
					->withOrderrow($orderrow)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withFormdata($this->formData)
					->withDeptid($deptid)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withSettings($this->acsettings)
					->withData($data);

	}
	
		
	public function update($id, Request $request)
	{
		$this->goods_issued->update($id, $request->all());
		//AUTO COST REFRESH CHECK ENABLE OR NOT
		if($this->mod_autocost->is_active==1) {
			$this->objUtility->reEvalItemCostQuantity($request->get('item_id'),$this->acsettings);
		}
		Session::flash('message', 'Goods Issued Note updated successfully');
		return redirect('goods_issued');
	}
	
	
		public function viewonly($id) { 

		$data = array();
		$itemmaster = $this->itemmaster->activeItemmasterList();
		$orderrow = $this->goods_issued->findPOdata($id);
		$orditems = $this->goods_issued->getItems($id); //echo '<pre>';print_r($orditems);exit;
		
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
		
		$vouchers = $this->accountsetting->getAccountSettingsDefault2($vid=13,$is_dept,$deptid);
		
		$getItemLocation = $this->itemmaster->getItemLocation($id,'GI');
		$itemlocedit = $this->makeTreeArr( $this->itemmaster->getItemLocEdit($id,'GI') );
		//echo '<pre>';print_r($getItemLocation);print_r($itemlocedit);exit;
		return view('body.goodsissued.viewonly')
					->withItems($itemmaster)
					->withOrditems($orditems)
					->withOrderrow($orderrow)
					->withVouchers($vouchers)
					->withIsdept($is_dept)
					->withDepartments($departments)
					->withFormdata($this->formData)
					->withDeptid($deptid)
					->withItemlocedit($itemlocedit)
					->withItemloc($getItemLocation)
					->withSettings($this->acsettings)
					->withData($data);

	}
	
	
	public function ajax_getcode($group_id)
	{
		$group = $this->group->getGroupCode($group_id);
		$row = $this->accountmaster->getGroupCode($group_id);
		if($row)
			$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
		else 
			$no = 0;
		
		$no++;
		return $code = strtoupper($group->code).''.$no;
		//return $group->account_id;

	}
	
	public function show($id) { 

		$data = array();
		$acmasterrow = $this->accountmaster->accountMasterView($id);
		//echo '<pre>';print_r($acmasterrow);exit;
		return view('body.accountmaster.view')
					->withMasterrow($acmasterrow)
					->withData($data);
	}
	
	public function getSupplier($num=null)
	{
		$data = array();
		$suppliers = $this->accountmaster->getSupplierList();
		return view('body.goodsissued.supplier')
					->withSuppliers($suppliers)
					->withNum($num)
					->withData($data);
	}
	
	public function getAccount($num,$cr=null)
	{
		$data = array();
		$accounts = $this->accountmaster->activeAccountList();
		return view('body.goodsissued.account')
					->withAccounts($accounts)
					->withNum($num)
					->withCr($cr)
					->withData($data);
	}
	
	public function getPI()
	{
		$data = array();
		$pidata = $this->goods_issued->getPIdata();//echo '<pre>';print_r($pidata);exit;
		return view('body.goodsissued.pidata')
					->withPidata($pidata)
					->withData($data);
	}
	
	public function getInvoiceBySupplier($supplier_id,$no=null)
	{
		$invoices = $this->goods_issued->getSupplierInvoice($supplier_id);
		return view('body.goodsissued.supinvoice')
					->withNum($no)
					->withInvoices($invoices);
	}
	
	public function setSessionVal()
	{
		//print_r($request->all());
		Session::put('voucher_id', $request->get('vchr_id'));
		Session::put('voucher_no', $request->get('vchr_no'));
		Session::put('reference_no', $request->get('ref_no'));
		Session::put('voucher_date', $request->get('vchr_dt'));
		Session::put('lpo_date', $request->get('lpo_dt'));
		Session::put('purchase_acnt', $request->get('pur_ac'));
		Session::put('acnt_master', $request->get('ac_mstr'));

	}
	
	public function getPrint($id,$rid=null)
	{
		//$viewfile = DB::table('report_view')->where('code', 'SO')->where('status',1)->select('view_name')->first();
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_order->getOrder($attributes);
			$titles = ['main_head' => 'Goods Inssued Note','subhead' => 'Goods Inssued Note'];
			return view('body.goodsissued.print') //printsp print
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withItems($result['items']);
		} else {
			$path = app_path() . '/stimulsoft/helper.php';
			if(env('STIMULSOFT_VER')==2)
			        return view('body.reports')->withPath($path)->withView($viewfile->print_name);
			   else
			        return view('body.salesorder.viewer')->withPath($path)->withView($viewfile->print_name);
			
		}
		
	}
	
	public function getPrint3($id)
	{
		$attributes['document_id'] = $id;
		$result = $this->goods_issued->getInvoice($attributes);
		/* $jobname = '';
		if($result['details']->is_itemjob==1) {
		   $arrjob = DB::table('goods_issued_item')
		                ->join('jobmaster','jobmaster.id','=','goods_issued_item.job_id')
		                ->where('goods_issued_item.goods_issued_id',$result['details']->id)->where('goods_issued_item.status',1)->where('goods_issued_item.deleted_at','0000-00-00 00:00:00')
		                ->select('jobmaster.name')
		                ->groupBy('jobmaster.id')->get();
		                
		  foreach($arrjob as $job) {
		      $jobname .= ($jobname=='')?$job->name:', '.$job->name;
		  }
		} */ //echo $jobname;exit;
		$titles = ['main_head' => 'Goods Issued Note','subhead' => 'Goods Issued Note'];
		//echo '<pre>';print_r($arrjob);exit;
		return view('body.goodsissued.print')
					->withDetails($result['details'])
					->withTitles($titles)
					//->withJobname($jobname)
					->withItems($result['items']);
	
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
		$childs[$item['jobname']][] = $item;
		//$childs[$item->jobname][] = $item;
		
			
		return $childs;
	}
	public function getSearch(Request $request)
	{
		$data = array();
		//echo '<pre>';print_r($request->all());exit;
		if($request->get('search_type')=="summary") {
			$voucher_head = 'Goods Issued Summary';
			$report = $this->goods_issued->getReport($request->all());
			$reports = array_merge($this->makeTree($report['normal']),$this->makeTree($report['itemjob']));//$reports = $this->makeTree($report);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		}else if($request->get('search_type')=="detail") {
			$voucher_head = 'Goods Issued Detail';
			$report = $this->goods_issued->getReport($request->all()); //echo '<pre>';print_r($report);exit;
			$reports = array_merge($this->makeTree($report['normal']),$this->makeTree($report['itemjob']));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		}
	//	echo '<pre>';print_r($reports);exit;
		return view('body.goodsissued.preprint')
					->withReports($reports)
					->withTitles($titles)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
				     ->withJobids(json_encode($request->get('job_id')))
					->withI(0)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function dataExport(Request $request)
	{
		$data = array();
	//	echo '<pre>';print_r($request->all());exit;
	   
		
		$datareport[] = ['','','',strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		$voucher_head = 'Goods Issued ';
	      
			$request->merge(['type' => 'export']);
		 $request->merge(['job_id' => json_decode($request->get('job_id'))]);
		$report = $this->goods_issued->getReport($request->all());
		$reports = array_merge($this->makeTree($report['normal']),$this->makeTree($report['itemjob']));
		if($request->get('search_type')=='summary') {
			$voucher_head = 'Goods Issued Summary ';
			
			//echo '<pre>';print_r($report);exit;
		       $datareport[] = ['','','',strtoupper($voucher_head), '','',''];
		      $datareport[] = ['','','','','','',''];
			$datareport[] = ['SI.No.','GI.No', 'GI.Date', 'Stock Account','Job Name','Gross Amount','Total Amount'];
			$i = $net_total =$gross= 0;
			foreach ($reports as $key => $report) {
			foreach ($report as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row->voucher_no,
									  'vchr_dt' => date('d-m-Y',strtotime($row->voucher_date)),
									  'account' => $row->account,
									  'name' => $key,
									  'gross' => $row->total,
									  'total' => $row->net_amount
									];
					$gross+=$row->total;				
				  $net_total += $row->net_amount;
			}
			}
			
			$datareport[] = ['','','','','Total:',number_format($gross,2),number_format($net_total,2)];
		}
		 elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Goods Issued Detail';
		 	
		
			$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		     $net_cost=$net_qty=$net_total=0;
		    foreach ($reports as $key => $report) {
		        $datareport[] = ['Job Name:',$key,'','','', '','',''];
		      
		 	 $datareport[] = ['SI.No.','GI#','Vchr.Date','Stock Account', 'Stock CONS','Cost/Unit','Qty.','Net Total'];
		 	$i=$cost=$qty=$total=0;
			foreach ($report as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row->voucher_no,
									  'vdate' => date('d-m-Y',strtotime($row->voucher_date)),
									  'ref' => $row->account,
		 							  'item' => $row->jobaccount,
									 
									  'unit' => $row->unit_price,
									 'qty' => $row->quantity,
									  'total' => $row->total_price
								];
								$cost+=$row->unit_price;
								$qty+=$row->quantity;
								$total+=$row->total_price;
								
	 	}
	 		                    $net_cost+=$cost;
								$net_qty+=$qty;
								$net_total+=$total;
	 	$datareport[] = ['','','','','','',''];
	 	$datareport[] = ['','','','','Sub Total:',number_format($cost,2),$qty,number_format($total,2)];
		}
		$datareport[] = ['','','','','','',''];
	 	$datareport[] = ['','','','','Net Total:',number_format($net_cost,2),'',number_format($net_total,2)];
		
		}
		
	
	
		
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($voucher_head);
        $excel->setCreator('Profit Acc 365')->setCompany(Session::get('company'));
        $excel->setDescription($voucher_head);

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	}
	
	public function getDeptVoucher($id) {
		
		$depts = $this->accountsetting->getVoucherByDeptGIR($vid=13, $id); 
		$result = [];
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
								'dr_account_name' => ($row->dr_master_name!='')?$row->dr_master_name:'', 
								'dr_id' => ($row->dr_account_master_id!='')?$row->dr_account_master_id:'',
								'voucher_name' => $row->voucher_name,
								'voucher_id' => $row->voucher_id
							);

		}
		
		return $result;
	}
	
}

// SELECT goods_issued.voucher_no,goods_issued.voucher_date,goods_issued.description,goods_issued.total,goods_issued.discount,goods_issued.net_amount,jobmaster.code AS jcode,jobmaster.name AS jobname,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,goods_issued_item.item_name,goods_issued_item.quantity,goods_issued_item.unit_price,goods_issued_item.total_price,itemmaster.item_code,units.unit_name FROM goods_issued JOIN account_master ON(account_master.id=goods_issued.account_master_id) JOIN goods_issued_item ON(goods_issued_item.goods_issued_id=goods_issued.id) LEFT JOIN jobmaster ON(jobmaster.id=goods_issued.job_account_id) JOIN itemmaster ON(itemmaster.id=goods_issued_item.item_id) JOIN units ON(units.id=goods_issued_item.unit_id) WHERE goods_issued_item.status=1 AND goods_issued_item.deleted_at='0000-00-00 00:00:00' AND goods_issued.id={id} ORDER BY goods_issued_item.id ASC



