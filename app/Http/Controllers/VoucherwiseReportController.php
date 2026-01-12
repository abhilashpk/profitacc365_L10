<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VoucherwiseReport\VoucherwiseReportInterface; 

use App\Http\Requests;
use Notification;
use Session;
use DB;
use Excel;
use Auth;
use App;

class VoucherwiseReportController extends Controller
{
   
	protected $voucherwise_report;

	public function __construct(VoucherwiseReportInterface $voucherwise_report) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->voucherwise_report = $voucherwise_report;
		$this->middleware('auth');
	}
	
	protected function index(Request $request) {
		$data = array();
		$reports = array();//$this->accategory->accategoryList();
		if($request->all()!=null) { 
			$reports = $this->voucherwise_report->getReportResult($request->all()); 
		}
				
		//CHECK DEPARTMENT.......
		if(Session::get('department')==1) { //if active...
			$deptid = Auth::user()->department_id;
			if($deptid!=0)
				$departments = DB::table('department')->where('id',$deptid)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
			else {
				$departments = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
				//$deptid = $departments[0]->id;
			}
			$is_dept = true;
		} else {
			$is_dept = false;
			$departments = [];
			//$deptid = '';
		}
		
		return view('body.voucherwisereport.index')
					->withReports($reports)
					->withType($request->get('voucher_type'))
					->withDateto('')
					->withDatefrom('')
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	private function getVoucherType($type)
	{
		switch($type) {
			case 'JV':
				$result = 'Journal Voucher';
				break;
				
			case 'SI':
				$result = 'Sales Invoice';
				break;
				
			case 'PI':
				$result = 'Purchase Invoice';
				break;
				
			case 'PR':
				$result = 'Purchase Return';
				break;
				
			case 'SR':
				$result = 'Sales Return';
				break;
				
			case 'PIN':
				$result = 'Purchase Invoice(Non-Stock)';
				break;
				
			case 'SIN':
				$result = 'Sales Invoice(Non-Stock)';
				break;
				
			case 'PC':
				$result = 'Petty Cash Voucher';
				break;
				
			case 'GI':
				$result = 'Goods Issued Note';
				break;
				
			case 'GR':
				$result = 'Goods Return Note';
				break;
				
			case 'STI':
				$result = 'Stock Transfer In';
				break;
				
			case 'STO':
				$result = 'Stock Transfer Out';
				break;
				
			case 'MV':
				$result = 'Manufacture Voucher';
				break;
				
			case 'PS':
				$result = 'Purchase Split';
				break;
				
			case 'SS':
				$result = 'Sales Split';
				break;
				
			case 'MJV':
				$result = 'Manual Journal';
				break;
				
			case 'PIR':
				$result = 'Purchase Rental';
				break;
				
			case 'SIR':
				$result = 'Sales Rental';
				break;
				
			default:
				$result = '';
		}
		return $result;
	}
	
	public function printReport($id,$type) { 

		$data = array();
		$voucher_head = $this->getVoucherType($type);
		$transactions = $this->voucherwise_report->getPrintView($id,$type); //echo '<pre>';print_r($transactions);exit;
		$titles = ['main_head' => 'Voucherwise Report','subhead' => $voucher_head];
		//$resultrow = $this->voucherwise_report->findRow($id,$type); 
		return view('body.voucherwisereport.print')
					->withTransactions($transactions)
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withData($data);

	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;
			 
		return $childs;
	}
	
	protected function makeTreeAll($result)
	{
		$childsSI = $childsPI = $childsJV = $childsPV = $childsRV = $childsPR = $childsSR = $childsDB = $childsCB = $childsGI = $childsGR = $childsTI = $childsTO = $childsMV = $childsPS = $childsSS = $childsPIN = $childsSIN = $childsMJV = $childsSIR = $childsPIR = $childsPC = $childsPSR = $childsSSR = array();
		foreach($result as $item)
			if($item->voucher_type=="SI")
				$childsSI[$item->id][] = $item;
			else if($item->voucher_type=="PI")
				$childsPI[$item->id][] = $item;
			else if($item->voucher_type=="RV")
				$childsRV[$item->id][] = $item;
			else if($item->voucher_type=="PV")
				$childsPV[$item->id][] = $item;
			else if($item->voucher_type=="JV")
				$childsJV[$item->id][] = $item;
			else if($item->voucher_type=="PC")
				$childsPC[$item->id][] = $item;	
			else if($item->voucher_type=="SR")
				$childsSR[$item->id][] = $item;
			else if($item->voucher_type=="PR")
				$childsPR[$item->id][] = $item;
			else if($item->voucher_type=="CB")
				$childsCB[$item->id][] = $item;
			else if($item->voucher_type=="DB")
				$childsDB[$item->id][] = $item;
			else if($item->voucher_type=="GI")
				$childsGI[$item->id][] = $item;
			else if($item->voucher_type=="GR")
				$childsGR[$item->id][] = $item;
			else if($item->voucher_type=="STI")
				$childsTI[$item->id][] = $item;
			else if($item->voucher_type=="STO")
				$childsTO[$item->id][] = $item;
			else if($item->voucher_type=="MV")
				$childsMV[$item->id][] = $item;
			else if($item->voucher_type=="PS")
				$childsPS[$item->id][] = $item;
			else if($item->voucher_type=="PSR")
				$childsPSR[$item->id][] = $item;	
			else if($item->voucher_type=="SS")
				$childsSS[$item->id][] = $item;
			else if($item->voucher_type=="SSR")
				$childsSSR[$item->id][] = $item;	
			else if($item->voucher_type=="PIN")
				$childsPIN[$item->id][] = $item;
			else if($item->voucher_type=="SIN")
				$childsSIN[$item->id][] = $item;
			else if($item->voucher_type=="MJV")
				$childsMJV[$item->id][] = $item;
			else if($item->voucher_type=="PIR")
				$childsPIR[$item->id][] = $item;
			else if($item->voucher_type=="SIR")
				$childsSIR[$item->id][] = $item;
		
		
		return array_merge($childsSI,$childsPI,$childsJV,$childsPV,$childsRV,$childsPC,$childsPR,$childsSR,$childsDB,$childsCB,$childsGI,$childsGR,$childsTI,$childsTO,$childsMV,$childsPS,$childsPSR,$childsSS,$childsSSR,$childsPIN,$childsSIN,$childsMJV,$childsPIR,$childsSIR);
	}
	
	public function getPrint(Request $request)
	{
		$data = array();
		//echo '<pre>';print_r($request->all());exit;
		$results = $this->voucherwise_report->getReportResult($request->all());  
		//echo '<pre>';print_r($results);exit;
		if($request->get('voucher_type')=='ALL')
			$transactions = $this->makeTreeAll($results); 
		else
			$transactions = $this->makeTree($results); 
		
	//	echo '<pre>';print_r($transactions);exit;
		$titles = ['main_head' => 'Voucherwise Report','subhead' => ''];
		$voucher_head = 'VOUCHERWISE REPORT';
		return view('body.voucherwisereport.preprint')
					->withTransactions($transactions)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withVoucherhead($voucher_head)
					->withType($request->get('voucher_type'))
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	
	public function pisiReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisireport')
					->withCustomers($customers)
					//->withJobs($jobs)
					->withData($data);
	}
	
	public function getPisiPrint(Request $request)
	{
		$data = array();
		$results = $this->voucherwise_report->getReportPisi($request->all());
		//echo '<pre>';print_r($results);exit;
		$titles = ['main_head' => 'PC & SI Report','subhead' => ''];
		return view('body.voucherwisereport.pisipreprint')
					->withResults($results)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withCustomer($request->get('customer_id'))
					->withJob($request->get('job_id'))
					->withData($data);
	}
	
	
	public function pisijobReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisijobreport')
					->withCustomers($customers)
					->withJobs($jobs)
					->withData($data);
	}
	
	
	public function dataExport(Request $request)
	{
		$data = array();
		$voucher_head = 'Voucherwise Report';
		
		$results = $this->voucherwise_report->getReportResult($request->all());  //echo '<pre>';print_r($results);exit;
		if($request->get('voucher_type')=='ALL')
			$reports = $this->makeTreeAll($results); 
		else
			$reports = $this->makeTree($results);
		
		//echo '<pre>';print_r($reports);exit;
		$datareport[] = ['Account Name','Description','Reference','Debit','Credit'];
	 $balance = 0; $i=0;
		$vtype=$vno=$vdate='';
		$gdrtotal = 0; 	$gcrtotal = 0;
		foreach ($reports as $report) {
		    $invrow = reset($report);
			//echo '<pre>';print_r($report);exit;
			$vtype=$invrow->voucher_type;
			$vno=$invrow->voucher_no;
			$vdate=date('d-m-Y',strtotime($invrow->voucher_date));
			 $datareport[] = ['','','','','','',''];			
			  $datareport[] = ['Voucher Type:'.$vtype,'Voucher No:'.$vno,'','Voucher Date:'.$vdate,''];
			
			 
			  	$cr_total = 0; $dr_total = 0;
			foreach($report as $row) {
				
				$cr_amount = ''; $dr_amount = '';
				if($row->type=='Cr') {
					$cr_amount = number_format($row->amount,2);
					if($row->amount >= 0) {
						$cr_total += $row->amount;
					} else {
						$cr_total -= $row->amount;
					}
				} else if($row->type=='Dr') {
					$dr_amount = number_format($row->amount,2);
					$dr_total += $row->amount;
				}
					
				$datareport[] = [ 'account' => $row->master_name,
								  'description' => $row->description,
								  'reference' => ($row->other_type=='')?$row->reference_no:$row->reference_from,
								  'debit' => $dr_amount,
								  'credit' => $cr_amount
								];
			}
			
			$datareport[] = [ 'account' => '',
							  'description' => '',
							  'reference' => 'Vr.Total:',
							  'debit' => $dr_total,
							  'credit' => $cr_total
							];
			$gdrtotal += $dr_total; 
			$gcrtotal += $cr_total; 
			
			
		}
		
		 $datareport[] = [ 'account' => '',
							  'description' => '',
							  'reference' => 'Grand Total:',
							  'debit' => $gdrtotal,
							  'credit' => 	$gcrtotal
							];
		   //echo $voucher_head.'<pre>';print_r($datareport);exit;
		
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}
	
	
	public function datapisiExport(Request $request)
	{
		$data = array();
		$voucher_head = 'Submission of Payment Report & Sales Invoice Report';
		
		$results = $this->voucherwise_report->getReportPisi($request->all());
		//echo '<pre>';print_r($results);exit;
		$pinettotal = 0;
		if(count($results['pi_res']) > 0) {
			$datareport[] = ['SUBMISSION OF PAYMENT CERTIFICATES','','','','','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Gross Amt.','Retn.Amt.','Discount','VAT Amt.','Net Total'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['pi_res'] as $row) {
				$total += $row->total;
				$discount += $row->discount;
				$vat += $row->vat_amount;
				$nettotal += $row->net_total;
				$less_amount += $row->less_amount;
				$pinettotal = $nettotal;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'total' => number_format($row->total,2),
								  'rtamount' => number_format($row->less_amount,2),
								  'discount' => number_format($row->discount,2),
								  'vat' => number_format($row->vat_amount,2),
								  'nettotal' => number_format($row->net_total,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'total' => number_format($total,2),
							  'rtamount' => number_format($less_amount,2),
							  'discount' => number_format($discount,2),
							  'vat' => number_format($vat,2),
							  'nettotal' => number_format($nettotal,2)
							];
		}
		
		if(count($results['si_res']) > 0) {
			$datareport[] = ['SALES INVOICE','','','','','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Gross Amt.','Retn.Amt.','Discount','VAT Amt.','Net Total'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['si_res'] as $row) {
				$total += $row->total;
				$discount += $row->discount;
				$vat += $row->vat_amount;
				$nettotal += $row->net_total;
				$less_amount += $row->less_amount;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'total' => number_format($row->total,2),
								  'rtamount' => number_format($row->less_amount,2),
								  'discount' => number_format($row->discount,2),
								  'vat' => number_format($row->vat_amount,2),
								  'nettotal' => number_format($row->net_total,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'total' => number_format($total,2),
							  'rtamount' => number_format($less_amount,2),
							  'discount' => number_format($discount,2),
							  'vat' => number_format($vat,2),
							  'nettotal' => number_format($nettotal,2)
							];
			$bal = ($pinettotal <= $nettotal)?0:($pinettotal - $nettotal);				
			$datareport[] = [ 'pino' => 'Balance Amount:',
							  'piso' => number_format($bal,2),
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => '',
							  'total' => '',
							  'rtamount' => '',
							  'discount' => '',
							  'vat' => '',
							  'nettotal' => ''
							];
		}
		
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}
	
	public function pisirtnReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisirtnreport')
					->withCustomers($customers)
					->withData($data);
	}
	
	
	public function getPisirtnPrint(Request $request)
	{
		$data = array();
		$results = $this->voucherwise_report->getReportPisi($request->all());
		//echo '<pre>';print_r($results);exit;
		$titles = ['main_head' => 'PC & SI Report','subhead' => ''];
		return view('body.voucherwisereport.pisirtnpreprint')
					->withResults($results)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withCustomer($request->get('customer_id'))
					->withJob($request->get('job_id'))
					->withData($data);
	}
	
	public function pisirtnjobReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisirtnjobreport')
					->withCustomers($customers)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function datapisirtnExport()
	{
		$data = array();
		$voucher_head = 'Submission of Payment Report & Sales Invoice Report';
		
		$results = $this->voucherwise_report->getReportPisi($request->all());
		//echo '<pre>';print_r($results);exit;
		$pinettotal = 0;
		if(count($results['pi_res']) > 0) {
			$datareport[] = ['SUBMISSION OF PAYMENT CERTIFICATES','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Retention Amt.'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['pi_res'] as $row) {
				
				$less_amount += $row->less_amount;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'rtamount' => number_format($row->less_amount,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'rtamount' => number_format($less_amount,2)
							];
		}
		
		if(count($results['si_res']) > 0) {
			$datareport[] = ['SALES INVOICE','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Retention Amt.'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['si_res'] as $row) {
				$less_amount += $row->less_amount;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'rtamount' => number_format($row->less_amount,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'rtamount' => number_format($less_amount,2)
							];
			$bal = ($pinettotal <= $nettotal)?0:($pinettotal - $nettotal);				
			$datareport[] = [ 'pino' => 'Balance Amount:',
							  'piso' => number_format($bal,2),
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => '',
							  'rtamount' => ''
							];
		}
		
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}
	
	public function pisirvReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisirvreport')
					->withCustomers($customers)
					->withData($data);
	}
	
	
	public function getPisirvPrint(Request $request)
	{
		$data = array();
		$results = $this->voucherwise_report->getReportPisi($request->all());
		$rvres = $this->voucherwise_report->getReportPisiRv($request->all());
		//echo '<pre>';print_r($rvres);exit;
		$titles = ['main_head' => 'PC & SI RV Report','subhead' => ''];
		return view('body.voucherwisereport.pisirvpreprint')
					->withResults($results)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withCustomer($request->get('customer_id'))
					->withJob($request->get('job_id'))
					->withRvres($rvres)
					->withData($data);
	}
	
	public function datapisirvExport(Request $request)
	{
		$data = array();
		$voucher_head = 'Submission of Payment Report & Sales Invoice Report';
		
		$results = $this->voucherwise_report->getReportPisi($request->all());
		$rvres = $this->voucherwise_report->getReportPisiRv($request->all());
		//echo '<pre>';print_r($results);exit;
		$pinettotal = 0;
		if(count($results['pi_res']) > 0) {
			$datareport[] = ['SUBMISSION OF PAYMENT CERTIFICATES','','','','','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Gross Amt.','Retn.Amt.','Discount','VAT Amt.','Net Total'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['pi_res'] as $row) {
				$total += $row->total;
				$discount += $row->discount;
				$vat += $row->vat_amount;
				$nettotal += $row->net_total;
				$less_amount += $row->less_amount;
				$pinettotal = $nettotal;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'total' => number_format($row->total,2),
								  'rtamount' => number_format($row->less_amount,2),
								  'discount' => number_format($row->discount,2),
								  'vat' => number_format($row->vat_amount,2),
								  'nettotal' => number_format($row->net_total,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'total' => number_format($total,2),
							  'rtamount' => number_format($less_amount,2),
							  'discount' => number_format($discount,2),
							  'vat' => number_format($vat,2),
							  'nettotal' => number_format($nettotal,2)
							];
		}
		
		if(count($results['si_res']) > 0) {
			$datareport[] = ['SALES INVOICE','','','','','','','','','',''];
			$datareport[] = ['SI.No','SO.#','SO.Ref.#','Customer','Job.No','Jobname','Gross Amt.','Retn.Amt.','Discount','VAT Amt.','Net Total'];
			$total = 0; $discount = 0; $vat = 0; $nettotal = $less_amount = 0;
			foreach($results['si_res'] as $row) {
				$total += $row->total;
				$discount += $row->discount;
				$vat += $row->vat_amount;
				$nettotal += $row->net_total;
				$less_amount += $row->less_amount;
				$datareport[] = [ 'pino' => $row->voucher_no,
								  'piso' => $row->voucher_no,
								  'piref' => $row->reference_no,
								  'customer' => $row->master_name,
								  'code' => $row->code,
								  'jobname' => $row->jobname,
								  'total' => number_format($row->total,2),
								  'rtamount' => number_format($row->less_amount,2),
								  'discount' => number_format($row->discount,2),
								  'vat' => number_format($row->vat_amount,2),
								  'nettotal' => number_format($row->net_total,2)
								];
			}
			
			$datareport[] = [ 'pino' => '',
							  'piso' => '',
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total:',
							  'total' => number_format($total,2),
							  'rtamount' => number_format($less_amount,2),
							  'discount' => number_format($discount,2),
							  'vat' => number_format($vat,2),
							  'nettotal' => number_format($nettotal,2)
							];
			$bal = ($pinettotal <= $nettotal)?0:($pinettotal - $nettotal);				
			$datareport[] = [ 'pino' => 'Balance Amount:',
							  'piso' => number_format($bal,2),
							  'piref' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => '',
							  'total' => '',
							  'rtamount' => '',
							  'discount' => '',
							  'vat' => '',
							  'nettotal' => ''
							];
		}
		
		
		if(count($rvres) > 0)	{
			$datareport[] = ['RECEIPT VOUCHER','','','','','',''];
			$datareport[] = ['SI.No','RV.NO','RV Date','Customer','Job.No','Jobname','Amount'];
			$total_rv = 0;
			foreach($rvres as $row) {
				$total_rv += $row->amount;
				$datareport[] = [ 'sino' => $row->voucher_no,
							  'rvno' => $row->voucher_no,
							  'date' => date('d-m-Y',strtotime($row->voucher_date)),
							  'customer' => $row->master_name,
							  'code' => $row->code,
							  'jobname' => $row->jobname,
							  'amount' => number_format($row->amount,2)
							];
				
			}
			$datareport[] = [ 'sino' => '',
							  'rvno' => '',
							  'date' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => 'Total',
							  'amount' => number_format($total_rv,2)
							];
							
			$rvbal = $pinettotal - $total_rv; //+ $nettotal
			$datareport[] = [ 'sino' => 'Balance Amount:',
							  'rvno' => $rvbal,
							  'date' => '',
							  'customer' => '',
							  'code' => '',
							  'jobname' => '',
							  'amount' => ''
							];
		}
		
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}
	
	public function pisirvjobReport()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisirvjobreport')
					->withCustomers($customers)
					->withJobs($jobs)
					->withData($data);
	}
	
	
	public function pisiSummary()
	{
		$data = array();
		
		$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$jobs = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.voucherwisereport.pisisummary')
					->withCustomers($customers)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function pisiSummaryPrint()
	{
		$data = array();
		$results = $this->check_balance($this->voucherwise_report->getReportPisiPending($request->all()));
		//echo '<pre>';print_r($results);exit;
		$titles = ['main_head' => 'PC & SI Pending Summary Report','subhead' => ''];
		return view('body.voucherwisereport.pisisummaryprint')
					->withResults($results)
					->withTitles($titles)
					->withDateto($request->get('date_from'))
					->withDatefrom($request->get('date_to'))
					->withCustomer($request->get('customer_id'))
					->withJob($request->get('job_id'))
					->withData($data);
	}
	
	private function check_balance($reports) {
		
		$newarr = []; //$piamt = $balance = $siamt = 0;
		foreach($reports['pi_res'] as $row1) {
			$piamt = $balance = $siamt = 0;
			foreach($reports['si_res'] as $row2) {
				
				if($row1->code == $row2->code) {
					$balance = $row1->amount - $row2->amount;
					$piamt = $row1->amount;
					$siamt = $row2->amount;
				} /* else {
					$balance = $piamt = $row1->amount;
					$siamt = 0;
				} */
			}
			
			if($piamt == 0 && $balance == 0 && $siamt ==0) {
				$balance = $piamt = $row1->amount;
			}
			
			$newarr[] = (object)['master_name' => $row1->master_name, 'code' => $row1->code, 'jobname' => $row1->jobname, 'piamount' => $piamt, 'siamount' => $siamt, 'balance' => $balance];
			
		}
		
		return $newarr;
		
	}
	
	public function datapisisummaryExport(Request $request)
	{
		$data = array();
		$voucher_head = 'PC and SI Pending Summary Report';
		
		$results = $this->check_balance($this->voucherwise_report->getReportPisiPending($request->all()));
		
		$datareport[] = [$voucher_head,'','','','',''];
		$datareport[] = ['Customer','Job.No','Jobname','PC Amount','Inv. Amount','Balance Amount'];
		
		$totalbalance = $totalpi = $totalsi = 0;
		foreach($results as $row) {
			
			$datareport[] = [ 'customer' => $row->master_name,
							  'code' => $row->code,
							  'jobname' => $row->jobname,
							  'piamount' => number_format($row->piamount,2),
							  'siamount' => number_format($row->siamount,2),
							  'balance' => number_format($row->balance,2)
							];
							
			$totalpi += $row->piamount; 
			$totalsi += $row->siamount; 
			$totalbalance += $row->balance; 
		}
		
		$datareport[] = [ 'customer' => '',
						  'code' => '',
						  'jobname' => 'Total:',
						  'piamount' => number_format($totalpi,2),
						  'siamount' => number_format($totalsi,2),
						  'balance' => number_format($totalbalance,2)
						];
	
		Excel::create($voucher_head.' on '.date('d-m-Y'), function($excel) use ($datareport,$voucher_head) {

			// Set the spreadsheet title, creator, and description
			$excel->setTitle($voucher_head);
			$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
			$excel->setDescription($voucher_head);

			// Build the spreadsheet, passing in the payments array
			$excel->sheet('sheet1', function($sheet) use ($datareport) {
				$sheet->fromArray($datareport, null, 'A1', false, false);
			});

		})->download('xlsx');
		
	}
}


