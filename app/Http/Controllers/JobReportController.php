<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Jobmaster\JobmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;
use DB;

class JobReportController extends Controller
{
   
	protected $jobmaster;

	public function __construct(JobmasterInterface $jobmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->jobmaster = $jobmaster;
		$this->middleware('auth');
			$this->module = DB::table('parameter2')->where('keyname', 'mod_workshop')->where('status',1)->select('is_active')->first();
	}
	
	public function index() {
		
		//GETTING REPORT TYPE... TODO...
		//$view = 'workshop';
	//	$view = 'normal';
		//$view = 'cargo';
	$view=($this->module->is_active==1) ? 'workshop':'normal';
		$data = array(); $reports = null;
		$reports = null;
		$jobmasters = $this->jobmaster->activeJobmasterList();
		$customers = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER')->orderBy('master_name','ASC')->get();
		return view('body.jobreport.'.$view)
					->withReports($reports)
					->withType('')
					->withFromdate('')
					->withTodate('')
					->withJobid('')
					->withJobmasters($jobmasters)
					->withCustomers($customers)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
		
	private function jobExists($id, $array) {
		$result = -1;
		for($i=0; $i<sizeof($array); $i++) {
			if ($array[$i]->id == $id) {
				$result = $i;
				break;
			}
		}
		return $result;
	}
	
	private function sortJob($results)
	{
		$amountArr = array();
		foreach($results as $row) {
			$index = $this->jobExists($row->id, $amountArr);
			if ($index < 0) {
				$amountArr[] = $row;
			}
			else {
				$amountArr[$index]->amount += $row->amount;
				$amountArr[$index]->income += $row->income;
				
			}
		}
		return $amountArr;
	}
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->acid][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeAc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->code][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeItm($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->item_id][] = $item;
		
		return $childs;
	}
	
	protected function sortByType($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->type][] = $item;
		
		return $childs;
	}
	
	private function sortByInvoice($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->voucher_no][] = $item;
		
		return $childs;
		
	}
	
	private function sortByJobno($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->code][] = $item;
		
		return $childs;
		
	}
	
	public function vehicleIndex() {
		$data = array(); 
	    $vehicle = DB::table('vehicle')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','reg_no')->get();
		return view('body.jobreport.vehicleindex')
				->withVehicle($vehicle)
			    ->withData($data);
	}
	
	public function vehicleSearch($val) {
	   // echo '<pre>';print_r($val);exit;
	    $result = DB::table('vehicle')
								->join('sales_order AS SO','SO.vehicle_id','=','vehicle.id')
								->join('jobmaster AS JM','JM.id','=','SO.job_id')
								->join('account_master AS AM','AM.id','=','JM.customer_id')
								->where('SO.status',1)
								->where('SO.deleted_at','0000-00-00 00:00:00')
			                     ->where('vehicle.id',$val)
			                     ->select('JM.code','JM.name','SO.voucher_date','JM.id AS job_id','AM.master_name')->get();
		$vehicle_info=DB::table('vehicle')->where('vehicle.id',$val)->select('vehicle.*')->first();
		
		
		foreach($result as $res) {
		    $income = $cost = 0;
		    
		    //INCOMES....
		    $si = DB::table('sales_invoice')->where('job_id',$res->job_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(sales_invoice.subtotal) AS total'))->first();
		    
		    $gr = DB::table('goods_return')->where('job_id',$res->job_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(goods_return.net_amount) AS total'))->first();
		    
		    $income += ($si)?$si->total:0;
		    $income += ($gr)?$gr->total:0;
		    
		    //COST....
		    $gi= DB::table('goods_issued')->where('job_id',$res->job_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(goods_issued.net_amount) AS total'))->first();
		    
		    $pi= DB::table('purchase_invoice')->where('job_id',$res->job_id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(purchase_invoice.net_amount) AS total'))->first();
		    
		    $cost += ($pi)?$pi->total:0;
		    $cost += ($gi)?$gi->total:0;
		    
		    $jobdata[$res->job_id] = (object)['income' => $income, 'cost' => $cost, 'profit' => ($income-$cost)];
		}
		                                 
			                    //echo '<pre>';print_r($si);exit; 
			 	return view('body.jobreport.vehiclereport')
			 	        ->withVinfo($vehicle_info)
						->withResults($result)
						->withJobdata($jobdata);                   
	}
	private function sortByJobtype($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->vtype][] = $item;
		
		return $childs;
		
	}
	
	 
	//JAN25
	protected function makeTreeJobAc($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->code][$item->acid][] = $item;
		
		return $childs;
	}
	
	//JAN25
	private function sumAcwise($result) {
	    $arrres = [];
	    foreach($result as $rows) {
	        //$arrres = [];
	        $acnts = [];
	        foreach($rows as $row1) {
	            $income = $cost = 0; 
	            foreach($row1 as $row) {
    	            $jobcode = $row->code;
    	            $name = $row->jobname;
    	            $income += $row->income;
    	            $cost += $row->amount;
    	            $acname = $row->master_name.
    	            $acid = $row->account_id;
	            }
	           $acnts[] = ['aacid' => $acid, 'acname' => $acname, 'income' => $income, 'cost' => $cost];
	        }
	        $arrres[] = ['jobcode'=>$jobcode,'jobname'=>$name, 'acnts' => $acnts];
	    }
	    return $arrres;
	}
	
	//JAN25
	private function makeTreeJobAcVchr($result) {
	    $arrres = [];
	    foreach($result as $rows) {
	        //$arrres = [];
	        $acnts = [];
	        foreach($rows as $row1) {
	            $income = $cost = 0; 
	            foreach($row1 as $row) {
    	            $jobcode = $row->code;
    	            $name = $row->jobname;
    	            $acname = $row->master_name.
    	            $acid = $row->account_id;
	            }
	           $acnts[] = ['aacid' => $acid, 'acname' => $acname, 'details' => $row1];
	        }
	        $arrres[] = ['jobcode'=> $jobcode,'jobname'=>$name, 'acnts' => $acnts];
	    }
	    return $arrres;
	}
	
	//JAN25
	private function sortByJobTypeTrn($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->code][$item->vtype][] = $item;
		
		return $childs;
		
	}
	
	public function jobDetails($id){
	    $attributes = ['job_id' => $id,'date_from'=>'','date_to'=>''];
	    $data   = array();
        $reports = $this->sortByJobtype( $this->jobmaster->getVehicleJobReport($attributes) ); 
		
	   //echo '<pre>';print_r($reports);exit; 
	   return view('body.jobreport.jobreport')
					->withReports($reports)
					->withData($data);
	   }
	   
	   
	   	public function jobIndex() {
		$data = array(); 
	    $job = DB::table('jobmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
		                                 ->where('is_salary_job',0)->where('is_subjob',0)
		                        ->select('id','code','name')->get();
								//echo '<pre>';print_r($job);exit;
		return view('body.jobreport.jobindex')
				->withJob($job)
			    ->withData($data);
	}
	
	public function sojobSearch($val) {
		// echo '<pre>';print_r($val);exit;
		 $result = DB::table('jobmaster AS JM')
								 ->join('sales_order AS SO','SO.job_id','=','JM.id')
								 ->join('sales_order_item AS SOI','SOI.sales_order_id','=','SO.id')
								 ->join('itemmaster AS SIM','SIM.id','=','SOI.item_id')
								 ->join('account_master AS AM','AM.id','=','JM.customer_id')
								 ->where('SO.status',1)
								 ->where('SO.deleted_at','0000-00-00 00:00:00')
								  ->where('JM.id',$val)
								  ->select('JM.code','JM.name','SO.voucher_no','SO.id AS so_id','JM.id AS job_id','AM.master_name',
								  'SIM.item_code AS itmcode','SIM.description AS item_name','SOI.quantity AS so_qty')->get();
								  //echo '<pre>';print_r($result);exit;
		return view('body.jobreport.sojobreport')
								  ->withResults($result);
	}
	
	protected function makeWorkTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->wvoucher_no][] = $item;
		
		return $childs;
	}

	public function workjobDetails($id){
	    
	    $data   = array();
        $result = DB::table('sales_order')->where('sales_order.id',$id)
		          ->join('account_master AS am','am.id','=','sales_order.customer_id')
		            ->join('sales_order AS WO','WO.fabrication','=','sales_order.id')
		           ->join('sales_order_item AS SOI','SOI.sales_order_id','=','WO.id')
		           ->join('itemmaster AS IM','IM.id','=','SOI.item_id')
				   ->where('SOI.status',1)
				   ->where('SOI.deleted_at','0000-00-00 00:00:00')
				   ->select('sales_order.voucher_no','am.master_name AS customer','IM.item_code','IM.description',
				   'SOI.quantity','SOI.balance_quantity','SOI.unit_price','SOI.line_total','SOI.is_transfer','sales_order.net_total','WO.voucher_no AS wvoucher_no')
				 ->get();
				 
		$reports = $this->makeWorkTree($result);
		//echo '<pre>';print_r($reports);exit; 
	   return view('body.jobreport.workjobreport')
					->withReports($reports)
					->withData($data);
	   }
	   
	   
	
	public function getSearch(Request $request)
	{ //echo '<pre>';print_r(Input::all());exit;
		//echo '<pre>';print_r($request->get('type'));exit;
		$data = $report = $reports = array();
	$vehicle='';
		if($request->get('type') == 'workshop') {
		    	$vehicle=(Input::get('is_vehicle'))?Input::get('is_vehicle'):'';
			if($request->get('search_type') == 'summary') {
				$voucher_head = 'Vehicle Job Summary Report';
				$reports = $this->sortJob($this->jobmaster->getVehicleJobSummaryReport($request->all())); 
				
			} else if($request->get('search_type') == 'detail') {
				$voucher_head = 'Vehicle Job Detail Report';
				
				$reports = $this->sortByJobno( $this->jobmaster->getVehicleJobReport($request->all()) ); 
				
			} else if($request->get('search_type') == 'stockin') {
				$voucher_head = 'Vehicle Job Stock In Report';
				$invoice = $this->sortByType( $this->jobmaster->getVehicleJobStockinReport($request->all()) ); 
				$reports = ['invoice' => $invoice];
			
			} else if($request->get('search_type') == 'stockout') {
				$voucher_head = 'Vehicle Job Stock Out Report';
				$invoices=$this->jobmaster->getVehicleJobStockoutReport($request->all());
				$invoice = $this->sortByType( $this->jobmaster->getVehicleJobStockoutReport($request->all()) ); 
				$reports = ['invoice' => $invoice];
				
			}
			
			$view = 'workshop_report';
			//echo '<pre>';print_r($invoices);exit;
			
		} else if($request->get('type') == 'normal') {
			
			if(Input::get('search_type')=='summary') {
				$voucher_head = 'Job Report - Summary';
				//$reports = $this->jobmaster->getJobReport(Input::all()); echo '<pre>';print_r($reports);exit;
				$reports = $this->sortJob( $this->jobmaster->getJobReport(Input::all()) ); 
				$repo=$this->jobmaster->getJobReport(Input::all());
						//echo '<pre>';print_r($repo);exit;
			} else if(Input::get('search_type')=='summary_ac') {
				$voucher_head = 'Job Report - Summary Account Wise';
				$reports = $this->makeTree($this->jobmaster->getJobReport(Input::all()));
				
			} else if(Input::get('search_type')=='detail') {
				$voucher_head = 'Job Report - Detail Account Wise';
				$report = $this->jobmaster->getJobReport(Input::all());
				
				//echo '<pre>';print_r($report);exit;
				if(count($report) > 0) {
					
					if(Input::get('is_jobsplit')==1){
						$reports = $this->makeTreeAc( $report ); 
						$report = $report[0]; 
					} else {
						$reports = $this->makeTree( $report ); 	
						$report = $report[0]; 
					}
					
					if(Input::get('is_workshopsplit')==1){
						
						$reports = $this->makeTreeAc( $report ); 
						$report = $report[0]; 
					} 
				}
                //echo '<pre>';print_r($report);exit;
			
			} else if(Input::get('search_type')=='stockin' || Input::get('search_type')=='stockout') {
				$voucher_head = (Input::get('search_type')=='stockin')?'Jobwise Stock In Report':'Jobwise Stock Out Report';
				$invoice = $this->sortByType( $this->jobmaster->getJobReport(Input::all()) );
				/* if(sizeof($results['invoice']) > 0) {
					$invoice = $this->makeTreeItm($results['invoice']);
				} else 
					$invoice = null; */
				
				$reports = ['invoice' => $invoice];
				
			} else if($request->get('search_type') == 'detail_voucherwise') { //JAN25
				$voucher_head = 'Job Detail(Voucher wise)';
				//$reports = $this->makeTreeJobAcVchr( $this->makeTreeJobAc( $this->jobmaster->getVehicleJobReportVoucherwise($request->all()) ) );  	echo '<pre>';print_r($reports);exit;
				$reports = $this->makeTreeJobAcVchr( $this->makeTreeJobAc( $this->jobmaster->getVehicleJobReportVoucherwise($request->all()) ) );  	
				//$reports = $this->jobmaster->getVehicleJobReportVoucherwise($request->all());  	
				//echo '<pre>';print_r($reports);exit;
				
			} else if($request->get('search_type') == 'job_ac_wisesummary') { //JAN25
				$voucher_head = 'Job wise - Account wise Summary';
				$report1 = $this->jobmaster->getVehicleJobAccountwiseReport($request->all());  	 //echo '<pre>';print_r($report1);exit;
				
			    $reports = $this->sumAcwise( $this->makeTreeJobAc( $report1 ) );
			//	$reports = $this->makeTreeJobAc( $report1 );
				
			
			} else if($request->get('search_type') == 'job_detailed_trn') { //JAN25
				$voucher_head = 'Job Detailed Transaction Report';
				$reports = $this->sortByJobTypeTrn( $this->jobmaster->getVehicleJobReport($request->all()) ); 	
				//echo '<pre>';print_r($reports);exit;
			} 
			
			$view = 'normal_report';
			
		} else if($request->get('type') == 'cargo') {
			
			if(Input::get('search_type')=='summary') {
				$voucher_head = 'Cargo Job Report - Summary';
				$reports = $this->sortJob( $this->jobmaster->getJobReport(Input::all()) ); 
						
			} else if(Input::get('search_type')=='detail') {
				$voucher_head = 'Cargo Job Report - Detail Account Wise';
				$report = $this->jobmaster->getJobReport(Input::all());
				//echo '<pre>';print_r($report);exit;
				
				if(count($report) > 0) {
					$reports = $this->makeTreeAc( $report ); 
					$report = $report[0]; 
				}
			
			} else if(Input::get('search_type')=='stockin' || Input::get('search_type')=='stockout') {
				$voucher_head = (Input::get('search_type')=='stockin')?'Jobwise Stock In Report':'Jobwise Stock Out Report';
				$invoice = $this->sortByType( $this->jobmaster->getJobReport(Input::all()) );
				/* if(sizeof($results['invoice']) > 0) {
					$invoice = $this->makeTreeItm($results['invoice']);
				} else 
					$invoice = null; */
				
				$reports = ['invoice' => $invoice];
				

			} 
			
			$view = 'normal_report';
			
			
			
		}
		
//	echo '<pre>';print_r($reports);exit;		
	//echo $view;exit;
		return view('body.jobreport.'.$view)
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withStype(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withSettings($this->acsettings)
					->withJobsplit(Input::get('is_jobsplit'))
					->withWorkshopsplit(Input::get('is_workshopsplit'))
					->withInput(Input::all())
					->withReport($report)
					->withVehicle($vehicle)
					->withData($data);
	}
	
	
	public function getPrint()
	{
		$data = array();
		
		if(Input::get('search_type')=='opening_quantity' || Input::get('search_type')=='qtyhand_ason_date') {
			$voucher_head = 'Job Report';
			$results = $this->jobmaster->getQuantityReport(Input::all()); 
			$titles = ['main_head' => 'Quantity Report','subhead' => 'Quantity Report'];
			
		} else if(Input::get('search_type')=='purchase_order') {
			$voucher_head = 'Job Order';
			$results = $this->jobmaster->getQuantityReport(Input::all()); 
			$titles = ['main_head' => 'Purchase Order','subhead' => 'Purchase Order'];
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.jobreport.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withUrl('quantity_report')
					->withData($data);
	}	
	
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Job Report - Summary';
			$reports = $this->sortJob( $this->jobmaster->getJobReport(Input::all()) ); 
			
			$datareport[] = ['','',strtoupper($voucher_head),'',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Job Code','Job Name','Income','Cost','Net Income'];
			$datareport[] = ['','','','','','',''];
			
			$total_income = $total_expense = $total_netincome = 0; 
			foreach($reports as $report) {
				
				$net_income = $report->income - $report->amount;
				$total_income += $report->income;
				$total_expense += $report->amount;
				$total_netincome += $net_income;
				
				if($net_income < 0)
					$net_income = '('.number_format(($net_income*-1),2).')';
				else 
					$net_income = number_format($net_income,2);
				
				$datareport[] = ['code'	=> $report->code,
								 'name' => $report->name,
								 'income'	=> number_format($report->income,2),
								 'amount'	=> number_format($report->amount,2),
								 'net_income'	=> $net_income
								];
			}
			
			if($total_netincome < 0)
				$total_netincome = '('.number_format(($total_netincome*-1),2).')';
			else 
				$total_netincome = number_format($total_netincome,2);
			
			$datareport[] = ['','Grand Total',number_format($total_income,2),number_format($total_expense,2),$total_netincome];
					
		} else if(Input::get('search_type')=='summary_ac') {
			$voucher_head = 'Job Report - Summary Account Wise';
			$reports = $this->makeTree($this->jobmaster->getJobReport(Input::all()));
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Job Report - Detail Account Wise';
			
			$datareport[] = ['','',strtoupper($voucher_head),'',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Type','Tr.No','Date','Description','Income','Cost','Balance'];
			$datareport[] = ['','','','','','',''];
			
			$report = $this->jobmaster->getJobReport(Input::all()); //echo '<pre>';print_r($report);exit;
			$reports = $this->makeTree( $report ); 	
			$report = $report[0]; 
			
			$ginctotal = $gexptotal = $gbalance_total = $inctotal = $exptotal = $balance_total = 0;
			foreach($reports as $report) {
				$datareport[] = ['Account ID:',$report[0]->account_id,'Account Name:',$report[0]->master_name];
				foreach($report as $row) {
					$inctotal += $row->income; 
					$exptotal += $row->amount; 
					$balance = $row->income - $row->amount;
					$balance_total += $balance;
					$desc = '';
					
					if($row->jdesc==''){
						if($row->type=='GI')
							$desc = 'Goods Issued Note';
						elseif($row->type=='GR')
							$desc = 'Goods Return';
						elseif($row->type=='PI')
							$desc = 'Purchase Invoice';
						elseif($row->type=='SR')
							$desc = 'Sales Invoice';
						elseif($row->type=='JV')
							$desc = 'Journal Entry';
						elseif($row->type=='SP')
							$desc = 'Payment Voucher';
						elseif($row->type=='CR')
							$desc = 'Receipt Voucher';
					} else $desc = $row->jdesc;
					
					if($balance < 0) {
						$balance = '('.number_format(($balance*-1),2).')';
					} else $balance = number_format($balance,2);
					
					$datareport[] = ['type'	=> $row->type,
									 'voucher_no' => $row->voucher_no,
									 'voucher_date'	=> date('d-m-Y',strtotime($row->voucher_date)),
									 'desc'	=> $desc,
									 'income'	=> ($row->income > 0)?number_format($row->income,2):'',
									 'amount'		=> ($row->amount > 0)?number_format($row->amount,2):'',
									 'balance'	=> $balance
									];
				}
				$datareport[] = ['','','','','','',''];
			}
			$ginctotal += $inctotal; 
			$gexptotal += $exptotal; 
			$gbalance_total += $balance_total;
			if($balance_total < 0) {
				$balance_total = '('.number_format(($balance_total*-1),2).')';
			} else $balance_total = number_format($balance_total,2);
			
			$datareport[] = ['','','','Total',number_format($inctotal,2),number_format($exptotal,2),$balance_total];
			
		} else if(Input::get('search_type')=='stockin' || Input::get('search_type')=='stockout') {
			$voucher_head = (Input::get('search_type')=='stockin')?'Jobwise Stock In Report':'Jobwise Stock Out Report';
			
			$datareport[] = ['',strtoupper($voucher_head),'',''];
			$datareport[] = ['','','','','','',''];
			
			$invoice = $this->sortByType( $this->jobmaster->getJobReport(Input::all()) );
			/* if(sizeof($results['invoice']) > 0) {
				$invoice = $this->makeTreeItm($results['invoice']);
			} else 
				$invoice = null; */
			
			$reports = ['invoice' => $invoice]; //echo '<pre>';print_r($reports);exit;
			if(Input::get('job_id')!='') {
				$arr = current($reports['invoice']);
				$datareport[] = ['Job Code:'.$arr[0]->code,'','Job Name:'.$arr[0]->name,''];
			}
				$datareport[] = ['Item Description','Qty','Rate','Value'];
				foreach($reports['invoice'] as $report) {
					
					  if($report[0]->type=='SI')
						$type = 'SALES INVOICE(STOCK)';
					  else if($report[0]->type=='GI')
						  $type = 'GOODS ISSUED';
					  else if($report[0]->type=='PI')
						  $type = 'PURCHASE INVOICE(STOCK)';
					  else if($report[0]->type=='GR')
						  $type = 'GOODS RETURN';
					  
					  $datareport[] = [$type,'',''];
					  $datareport[] = ['Voucher Name: '.$report[0]->master_name,'Inv. No: '.$report[0]->voucher_no,'Inv. Date :'.date('d-m-Y',strtotime($report[0]->voucher_date))];
					  $qty_total = $value_total = 0;
					  
					  foreach($report as $row) {
						  $value = $row->unit_price * $row->quantity;
						  $qty_total += $row->quantity;
						  $value_total += $value;
														
						  $datareport[] = ['item_code' => $row->item_code.' - '.$row->description,
											'quantity'		=> $row->quantity,
											'unit_price'		=> number_format($row->unit_price,2),
											'value'			=> number_format($value,2)
											];
					  }
					  
					  $datareport[]	= ['Total:',$qty_total,'',number_format($value_total,2)];
				}
		} 
		
		//echo '<pre>';print_r($reports);exit;
		
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

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
