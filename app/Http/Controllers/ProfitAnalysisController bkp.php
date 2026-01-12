<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Salesman\SalesmanInterface;
use App\Repositories\Area\AreaInterface;
use App\Repositories\Group\GroupInterface;


use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use Auth;
use DB;
use App;


class ProfitAnalysisController extends Controller
{
   
	protected $sales_invoice;
	protected $accountmaster;
	protected $itemmaster;
	protected $salesman;
	protected $area;
	protected $group;
	protected $subGroup;

	public function __construct(SalesInvoiceInterface $sales_invoice, AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster, SalesmanInterface $salesman, AreaInterface $area, GroupInterface $group,GroupInterface $subGroup) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->sales_invoice = $sales_invoice;
		$this->middleware('auth');
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->salesman = $salesman;
		$this->area = $area;
		$this->group = $group;
		$this->subGroup = $subGroup;

	}
	
	public function index() {
		$data = array(); $reports = null;
		$reports = null;
		$salesmans = $this->salesman->getSalesmanList();
		
		$customer = $this->accountmaster->getCustomerList();
		
	
        $item = DB::table('itemmaster')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$category = DB::table('category')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subcategory = DB::table('category')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$group = DB::table('groupcat')->where('parent_id',0)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$subgroup = DB::table('groupcat')->where('parent_id',1)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		// $customers = [];//$this->accountmaster->getAccountByGroup('CUSTOMER');
		// $items = [];//	$this->itemmaster->activeItemmasterList();
		// $group=[];//$this->itemmaster->activeGroupList();
		// $subGroup=[];
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
		
		return view('body.profitanalysis.index')
					->withReports($reports)
					->withType('')
					->withCategory($category)
					->withSalesman($salesmans)
		            ->withSubcategory($subcategory)
					->withFromdate('')
					->withTodate('')
					->withCustomer($customer)
					->withItem($item)
					->withGroup($group)
					->withSubgroup($subgroup)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	private function profitCalc($results) {
		
		$report = array();
		foreach($results as $result) {
			$sprice = $cost = $profit = $percentage = $discount = $pprice = 0;
			foreach($result as $row) {
				$sprice += $row['squantity'] * $row['sunit_price'];
				if($row['class_id']==1)
					$cost = $row['sale_cost'];
				
				$discount += $row['discount'];
				$pprice += $row['squantity'] * $cost;
			}
			$profit = $sprice - $pprice - $discount;
			$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
			$arr['voucher_no'] = $row['voucher_no'];
			$arr['voucher_date'] = $row['voucher_date'];
			$arr['supplier'] = $row['supplier'];
			$arr['sprice'] = $sprice;
			$arr['discount'] = $discount;
			$arr['cost'] = $pprice;
			$arr['profit'] = $profit;
			$arr['percentage'] = $percentage;
			$arr['cid'] = $row['cid'];
			$arr['sid'] = $row['sid'];
			$arr['smid'] = $row['smid'];
			$arr['account_id'] = $row['account_id'];
			$arr['salesman'] = $row['salesman'];
			$report[] = $arr;
		}
		
		return $report;
	}
	
	public function getSearch()
	{
		$data = array();
		$custid = $itemid =$smanid= '';
      // echo '<pre>';print_r(Input::all());exit;
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Invoicewise - Profit Analysis Summary';
			$repor=  $this->sales_invoice->getProfitSummary(Input::all());
			//echo '<pre>';print_r($repor); exit();
			$reports =  $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()) ));
		     	$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		     	if(Input::get('customer_id')!==null)
				$custid = implode(',', Input::get('customer_id'));
			else
				$custid = '';
				
				if(Input::get('salesman_id')!==null)
				$smanid = implode(',', Input::get('salesman_id'));
			else
				$smanid = '';
				
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Invoicewise - Profit Analysis Details';
			$reports = $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()) ); 
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
				if(Input::get('customer_id')!==null)
				$custid = implode(',', Input::get('customer_id'));
			else
				$custid = '';
				
				if(Input::get('salesman_id')!==null)
				$smanid = implode(',', Input::get('salesman_id'));
			else
				$smanid = '';
		} else if(Input::get('search_type')=='customer') {
			$voucher_head = 'Profit Analysis by Customerwise';
			$reports =  $this->makeTreeCus( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()))));
			//echo '<pre>';print_r($reports); exit();
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if(Input::get('customer_id')!==null)
				$custid = implode(',', Input::get('customer_id'));
			else
				$custid = '';
		} else if(Input::get('search_type')=='item') {
			$voucher_head = 'Profit Analysis by Itemwise';
			$reports = $this->makeTreeItm($this->sales_invoice->getProfitSummary(Input::all()));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];		

			if(Input::get('item_id')!==null)
				$itemid = implode(',', Input::get('item_id'));
			else
				$itemid = '';
		/* } else if(Input::get('search_type')=='salesman') {
			$voucher_head = 'Profit Analysis by salesmanwise';
			$reports =  $this->makeTreeCus( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()))));
			$itemid = implode(',', Input::get('item_id'));
			 */
		} else if(Input::get('search_type')=='summarysalesman') {
			$voucher_head = 'Profit Analysis by Salesmanwise - Summary';
			$reports =$this->makeTreeSal($this->profitCalc($this->makeTree($this->sales_invoice->getProfitSummary(Input::all()))));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			if(Input::get('salesman_id')!==null)
				$smanid = implode(',', Input::get('salesman_id'));
			else
				$smanid = '';
			
		} else if(Input::get('search_type')=='salesman') {
			$voucher_head = 'Profit Analysis by Salesmanwise - Detail';
			$reports = $this->makeTree($this->sales_invoice->getProfitSummary(Input::all()));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			if(Input::get('salesman_id')!==null)
				$smanid = implode(',', Input::get('salesman_id'));
			else
				$smanid = '';
			
		} else if(Input::get('search_type')=='area') {
			$voucher_head = 'Profit Analysis by areawise';
			//$reports =  $this->makeTreeCus( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()))));
			$reports =$this->makeTreeSal($this->profitCalc($this->makeTree($this->sales_invoice->getProfitSummary(Input::all()))));

			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			//$custid = implode(',', Input::get('salesman_id'));
		}  else if(Input::get('search_type')=='group') {
			$voucher_head = 'Profit Analysis by Groupwises';
			$reports =$this->makeTreeSal($this->profitCalc($this->makeTree($this->sales_invoice->getProfitSummary(Input::all()))));
			
           // echo '<pre>';print_r($reports); exit();
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			if(Input::get('group_id')!==null)
				$smanid = implode(',', Input::get('group_id'));
			else
				$smanid = '';
		} else if(Input::get('search_type')=='levelwise') {
			$voucher_head = 'POS Levelwise Report';
			$reports = $this->sales_invoice->getLevelwiseReport(Input::all()); 
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} else if(Input::get('search_type')=='pos_itemwise') {
			$voucher_head = 'POS Itemwise Report';
			$reports = $this->sortByGroupId( $this->sales_invoice->getItemwiseReport(Input::all()) ); 
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		}
		
		if(Input::get('search_type')=='invoice') {
			$voucher_head = 'Invoice Number wise - Profit Analysis Summary';
			//echo '<pre>';print_r($data); exit();
			$reports = $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()) ); 
			//echo '<pre>';print_r($reports); exit();
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		}

		

		
		//echo '<pre>';print_r($reports);exit;
		return view('body.profitanalysis.print')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withCustomer($custid)
					->withSalesman($smanid)
					->withItem($itemid)
					->withTitles($titles)
					->withSearchval(json_encode(Input::all()))
					->withData($data);
	}
	
	private function sortByGroupId($result) {
		
		$childs = array();
		foreach($result as $item)
			$childs[$item->group_id][] = $item;
		
		return $childs;
	}
	
	public function getPrint()
	{
		$data = array();
		
		if(Input::get('search_type')=='summary') {
			$voucher_head = 'Profit Analysis Summary - Invoicewise';
			$results = $this->sales_invoice->getInvoiceReport(Input::all()); 
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} else if(Input::get('search_type')=='customer') {
			$voucher_head = 'Profit Analysis - Customerwise';
			$results = $this->itemmaster->getQuantityReport(Input::all()); 
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
		} 
		
		//echo '<pre>';print_r($results);exit;
		return view('body.profitanalysis.print')
					->withResults($results)
					->withType(Input::get('search_type'))
					->withVoucherhead($voucher_head)
					->withTitles($titles)
					->withData($data);
	}	
	
	
	public function dataExport()
	{
	    
	    
	    $data = json_decode(Input::get('search_val')); //echo '<pre>';print_r($data);exit;
		Input::merge(['date_from' => $data->date_from]);
		Input::merge(['date_to' => $data->date_to]);
		Input::merge(['item_id' => (isset($data->item_id))?$data->item_id:'']);
		Input::merge(['search_type' => $data->search_type]);
		Input::merge(['customer_id' =>(isset($data->customer_id))?$data->customer_id:'']);
	    Input::merge(['salesman_id' =>(isset($data->salesman_id))?$data->salesman_id:'']);
	    Input::merge(['group_id' =>(isset($data->group_id))?$data->group_id:'']);
	    Input::merge(['subgroup_id' =>(isset($data->subgroup_id))?$data->subgroup_id:'']);
	    Input::merge(['category_id' =>(isset($data->category_id))?$data->category_id:'']);
	    Input::merge(['subcategory_id' =>(isset($data->subcategory_id))?$data->subcategory_id:'']);
			//echo '<pre>';print_r(Input::all());exit;	
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='summary') {
			
			$voucher_head = 'Invoicewise - Profit Analysis Summary';
			$reports =  $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()) ));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Inv.No','Inv.Date','Customer','Salesman','Total Sale','Discount','Net Sale','Cost','Profit','Pft.%'];
			
			$tot_sprice = $tot_discount = $tot_cost = $tot_profit = 0;
			foreach($reports as $report) {
				
				$tot_sprice += $report['sprice'];
				$tot_discount += $report['discount'];
				$tot_cost += $report['cost'];
				$tot_profit += $report['profit'];
				
				$datareport[] = ['voucher_no' => $report['voucher_no'],
								 'voucher_date'	=> date('d-m-Y', strtotime($report['voucher_date'])),
								 'supplier'	=> $report['supplier'],
								  'salesman'	=> $report['salesman'],
								 'sprice'	=> number_format($report['sprice'],2),
								 'discount'	=> number_format($report['discount'],2),
								 'price'	=> number_format($report['sprice'], 2),
								 'cost'		=> number_format($report['cost'],2),
								 'profit'	=> number_format($report['profit'], 2),
								 'percentage'	=> number_format($report['percentage'],2)
								];
				
			}
			
			$datareport[] = ['','','','Total',number_format($tot_sprice,2),number_format($tot_discount,2),number_format($tot_sprice,2),number_format($tot_cost,2),number_format($tot_profit,2)];
			
		} else if(Input::get('search_type')=='detail') {
			$voucher_head = 'Invoicewise - Profit Analysis Details';
			$reports = $this->makeTree( $this->sales_invoice->getProfitSummary(Input::all()) ); 
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			foreach($reports as $report) {
			
				$datareport[] = ['Inv.No:',$report[0]['voucher_no'],'','Customer:',$report[0]['supplier'],'','Date:',date('d-m-Y',strtotime($report[0]['voucher_date']))];
				
				$datareport[] = ['SI.#','Salesman','Item Code','Description','Qty.','Sale Price','Cost','Profit','Pft.%'];
				
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $sqty = $tcst = 0;$i=1; 
				foreach($report as $row) { 
				
					$sprice = $row['squantity'] * $row['sunit_price'];
					if($row['class_id']==1)
						$cost = $row['sale_cost'];
					else
						$cost = 0;
					$pprice = $row['squantity'] * $cost;
					$profit = $sprice - $pprice - $row['discount'];
					$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
					$sptotal += $sprice;
					$dtotal += $row['discount'];
					$ctotal += $cost;
					$ptotal += $profit;
					$pertotal += $percentage; $n = $i;
					$sqty += $row['squantity'];
					$tcst += $pprice;
						
					$datareport[] = ['i' => $i,
					                  'salesman'=>$row['salesman'],
									 'item_code'	=> $row['item_code'],
									 'description'	=> $row['description'],
									 'qty'			=> $row['squantity'],
									 'sprice'		=> number_format($sprice,2),
									 'pprice'		=> number_format($pprice,2),
									 'profit'		=> number_format($profit,2),
									 'per'			=> number_format($percentage,2)
									];
								$i++;
				}
				
				$peravg = $pertotal / $n;
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $tcst;
				$nptotal += $ptotal;
				$nqty += $sqty;
				
				$datareport[] = ['','','','','Sub Total:',number_format($sptotal,2),number_format($tcst,2),number_format($ptotal,2),number_format($peravg,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','','Net Total:',number_format($nsptotal,2),number_format($nctotal,2),number_format($nptotal,2)];
			
			
		} else if(Input::get('search_type')=='customer') {
			$voucher_head = 'Profit Analysis by Customerwise';
			$attributes = Input::all();
			$attributes['customer_id'] = explode(',',$attributes['customer_id']); 
			$reports =  $this->makeTreeCus( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary($attributes))));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			foreach($reports as $report) {
				$datareport[] = ['Cust.#',$report[0]['account_id'],'',''];
				
				$datareport[] = ['SI.#','Item Code','Description','Qty.','Sale Price','Discount','Cost','Profit','Pft.%'];
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
				foreach($report as $row) { 
					$sptotal += $row['sprice'];
					$dtotal += $row['discount'];
					$ctotal += $row['cost'];
					$ptotal += $row['profit'];
					$pertotal += $row['percentage'];
					$n = $i; $i++;
					
					$datareport[] = ['voucher_no'	=> $row['voucher_no'],
									 'voucher_date'	=> date('d-m-Y',strtotime($row['voucher_date'])),
									 'sprice'		=> number_format($row['sprice'],2),
									 'discount'		=> number_format($row['discount'],2),
									 'pprice'		=> number_format($row['cost'],2),
									 'profit'		=> number_format($row['profit'],2),
									 'per'			=> number_format($row['percentage'],2)
									];
				}
				
				$peravg = $pertotal / $n;
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $ctotal;
				$nptotal += $ptotal;
				
				$datareport[] = ['','','','Sub Total:',number_format($sptotal,2),number_format($dtotal,2),number_format($ctotal,2),number_format($ptotal,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','Net Total:',number_format($nsptotal,2),number_format($ndtotal,2),number_format($nctotal,2),number_format($nptotal,2)];
			
		} 	else if(Input::get('search_type')=='levelwise') {
			
				$voucher_head = 'POS Levelwise Report';
			$reports = $this->sales_invoice->getLevelwiseReport(Input::all()); 
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Group Name','Quantity','Sales Value'];
			
			$total_qty = $stock_total = 0;
			foreach($reports as $row) {
				
				$stock_total += $row['amount'];
				$total_qty += $row['quantity'];
				
				$datareport[] = ['voucher_no' => $row['group_name'],
								 'voucher_date'	=> $row['quantity'],
								 'supplier'	=> number_format($row['amount'],2),
								  
								];
				
			}
			
			$datareport[] = ['Total',number_format($total_qty,2),number_format($stock_total,2)];
			
		}
		
		else if(Input::get('search_type')=='pos_itemwise') {
			
					$voucher_head = 'POS Itemwise Report';
			$reports = $this->sortByGroupId( $this->sales_invoice->getItemwiseReport(Input::all()) ); 
			//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$grtotal = 0;
		   foreach($reports as $rows) {
		  $datareport[] = ['Group Name:',$rows[0]->group_name,''];
		       
			$datareport[] = ['Item Code','Item Name','Quantity'];
			
			$total_qty =  0;
			foreach($rows as $row) {
				
				
				$total_qty += $row['quantity'];
				
				$datareport[] = ['code' => $row['item_code'],
								 'desc'	=> $row['description'],
								 'qty'	=> $row['quantity'],
								  
								];
				
			}
			$grtotal+=$total_qty;
			
			$datareport[] = ['','Total',number_format($total_qty,2)];
		}
				$datareport[] = ['','Grand Total',number_format($grtotal,2)];
		}
		else if(Input::get('search_type')=='item') {
			$voucher_head = 'Profit Analysis by Itemwise';
			$attributes = Input::all();
			$attributes['item_id'] = explode(',',$attributes['item_id']); //echo '<pre>';print_r($attributes);exit;
			$reports = $this->makeTreeItm($this->sales_invoice->getProfitSummary($attributes));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];		
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			
			foreach($reports as $report) {
				$datareport[] = ['Item Code:',$report[0]['item_code'],'','Item Name:',$report[0]['description']];
				$datareport[] = ['Inv.#','Inv. Date','Customer Name','Qty.','Sale Price','Discount','Cost','Profit','Pft.%'];
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $tcst = 0; $i=1;
				foreach($report as $row) { 
					$sprice = $row['squantity'] * $row['sunit_price'];
					if($row['class_id']==1)
						$cost = $row['sale_cost'];
					else
						$cost = 0;
					
					$pprice = $row['squantity'] * $cost;
					$profit = $sprice - $pprice - $row['discount'];
					$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
					$sptotal += $sprice;
					$dtotal += $row['discount'];
					$ctotal += $cost;
					$ptotal += $profit;
					$pertotal += $percentage; $n = $i;$i++;
					$tcst += $pprice;
														
					$datareport[] = ['voucher_no'	=> $row['voucher_no'],
									 'voucher_date'	=> date('d-m-Y',strtotime($row['voucher_date'])),
									 'supplier'		=> $row['supplier'],
									 'sqty'			=> $row['squantity'],
									 'sprice'		=> number_format($sprice,2),
									 'discount'		=> number_format($row['discount'],2),
									 'pprice'		=> number_format($pprice,2),
									 'profit'		=> number_format($profit,2),
									 'per'			=> number_format($percentage,2)
									];
				}
				
				$peravg = $pertotal / $n; 
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $tcst;
				$nptotal += $ptotal;
				
				$datareport[] = ['','','','Sub Total:',number_format($sptotal,2),number_format($dtotal,2),number_format($tcst,2),number_format($ptotal,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','Net Total:',number_format($nsptotal,2),number_format($ndtotal,2),number_format($nctotal,2),number_format($nptotal,2)];
		}else if(Input::get('search_type')=='group') {
			$voucher_head = 'Profit Analysis by Itemwise';
			$attributes = Input::all();
			$attributes['item_id'] = explode(',',$attributes['item_id']); //echo '<pre>';print_r($attributes);exit;
			$reports = $this->makeTreeItm($this->sales_invoice->getProfitSummary($attributes));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];		
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			
			foreach($reports as $report) {
				$datareport[] = ['Item Code:',$report[0]['item_code'],'','Item Name:',$report[0]['description']];
				$datareport[] = ['Inv.#','Inv. Date','Customer Name','Qty.','Sale Price','Discount','Cost','Profit','Pft.%'];
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = $tcst = 0; $i=1;
				foreach($report as $row) { 
					$sprice = $row['squantity'] * $row['sunit_price'];
					if($row['class_id']==1)
						$cost = $row['sale_cost'];
					else
						$cost = 0;
					
					$pprice = $row['squantity'] * $cost;
					$profit = $sprice - $pprice - $row['discount'];
					$percentage = ($profit > 0 && $sprice > 0)?($profit / $sprice * 100):0;
					$sptotal += $sprice;
					$dtotal += $row['discount'];
					$ctotal += $cost;
					$ptotal += $profit;
					$pertotal += $percentage; $n = $i;$i++;
					$tcst += $pprice;
														
					$datareport[] = ['voucher_no'	=> $row['voucher_no'],
									 'voucher_date'	=> date('d-m-Y',strtotime($row['voucher_date'])),
									 'supplier'		=> $row['supplier'],
									 'sqty'			=> $row['squantity'],
									 'sprice'		=> number_format($sprice,2),
									 'discount'		=> number_format($row['discount'],2),
									 'pprice'		=> number_format($pprice,2),
									 'profit'		=> number_format($profit,2),
									 'per'			=> number_format($percentage,2)
									];
				}
				
				$peravg = $pertotal / $n; 
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $tcst;
				$nptotal += $ptotal;
				
				$datareport[] = ['','','','Sub Total:',number_format($sptotal,2),number_format($dtotal,2),number_format($tcst,2),number_format($ptotal,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','Net Total:',number_format($nsptotal,2),number_format($ndtotal,2),number_format($nctotal,2),number_format($nptotal,2)];
		}else if(Input::get('search_type')=='salesman') {
			$voucher_head = 'Profit Analysis by Salesmanwise';
			$attributes = Input::all();
			$attributes['salesman_id'] = explode(',',$attributes['salesman_id']); 
			$reports =  $this->makeTreeSal( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary($attributes))));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			foreach($reports as $report) {
				$datareport[] = ['Cust.#',$report[0]['account_id'],'',''];
				
				$datareport[] = ['SI.#','Item Code','Description','Qty.','Sale Price','Discount','Cost','Profit','Pft.%'];
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
				foreach($report as $row) { 
					$sptotal += $row['sprice'];
					$dtotal += $row['discount'];
					$ctotal += $row['cost'];
					$ptotal += $row['profit'];
					$pertotal += $row['percentage'];
					$n = $i; $i++;
					
					$datareport[] = ['voucher_no'	=> $row['voucher_no'],
									 'voucher_date'	=> date('d-m-Y',strtotime($row['voucher_date'])),
									 'sprice'		=> number_format($row['sprice'],2),
									 'discount'		=> number_format($row['discount'],2),
									 'pprice'		=> number_format($row['cost'],2),
									 'profit'		=> number_format($row['profit'],2),
									 'per'			=> number_format($row['percentage'],2)
									];
				}
				
				$peravg = $pertotal / $n;
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $ctotal;
				$nptotal += $ptotal;
				
				$datareport[] = ['','','','Sub Total:',number_format($sptotal,2),number_format($dtotal,2),number_format($ctotal,2),number_format($ptotal,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','Net Total:',number_format($nsptotal,2),number_format($ndtotal,2),number_format($nctotal,2),number_format($nptotal,2)];
			
		}else if(Input::get('search_type')=='summarysalesman') {
			$voucher_head = 'Profit Analysis by Salesmanwise - summary';
			$attributes = Input::all();
			$attributes['salesman_id'] = explode(',',$attributes['salesman_id']); 
			$reports =  $this->makeTreeSal( $this->profitCalc( $this->makeTree( $this->sales_invoice->getProfitSummary($attributes))));
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
			
			$datareport[] = ['','','',strtoupper($voucher_head),'','',''];
			$datareport[] = ['','','','','','',''];
			$nsptotal = $ndtotal = $nctotal = $nptotal = $npertotal = $nqty = 0;
			foreach($reports as $report) {
				$datareport[] = ['Cust.#',$report[0]['account_id'],'',''];
				
				$datareport[] = ['SI.#','Item Code','Description','Qty.','Sale Price','Discount','Cost','Profit','Pft.%'];
				$sptotal = $dtotal = $ctotal = $ptotal = $pertotal = $peravg = 0; $i=1;
				foreach($report as $row) { 
					$sptotal += $row['sprice'];
					$dtotal += $row['discount'];
					$ctotal += $row['cost'];
					$ptotal += $row['profit'];
					$pertotal += $row['percentage'];
					$n = $i; $i++;
					
					$datareport[] = ['voucher_no'	=> $row['voucher_no'],
									 'voucher_date'	=> date('d-m-Y',strtotime($row['voucher_date'])),
									 'sprice'		=> number_format($row['sprice'],2),
									 'discount'		=> number_format($row['discount'],2),
									 'pprice'		=> number_format($row['cost'],2),
									 'profit'		=> number_format($row['profit'],2),
									 'per'			=> number_format($row['percentage'],2)
									];
				}
				
				$peravg = $pertotal / $n;
				$nsptotal += $sptotal;
				$ndtotal += $dtotal;
				$nctotal += $ctotal;
				$nptotal += $ptotal;
				
				$datareport[] = ['','','','Sub Total:',number_format($sptotal,2),number_format($dtotal,2),number_format($ctotal,2),number_format($ptotal,2)];
				$datareport[] = ['','',''];
			}
			
			$datareport[] = ['','','','Net Total:',number_format($nsptotal,2),number_format($ndtotal,2),number_format($nctotal,2),number_format($nptotal,2)];
			
		}
		
		//echo '<pre>';print_r($reports);exit;
		//echo $voucher_head.'<pre>';print_r($datareport);exit;
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
	
	
	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['id']][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeCus($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['cid']][] = $item;
		
		return $childs;
	}
	protected function makeTreeSal($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['smid']][] = $item;
		
		return $childs;
	}
	
	protected function makeTreeItm($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item['itid']][] = $item;
		
		return $childs;
	}
	
	public function getCustomer()
	{
		$data = array();
		$customer = $this->accountmaster->getAccountByGroup('CUSTOMER');
		return view('body.profitanalysis.multiselect')
					->withCustomers($customer)
					->withType('CUST')
					->withData($data);
					
		/* $data = array();
		$data = $this->accountmaster->getAccountByGroup('CUSTOMER');
		if($data) {
			$result = array();
			foreach($data as $val) {
				$result[$val['id']] = $val['master_name'];
			}
			return $result;
		} else 
			return null; */
		
		//echo '<pre>';print_r($unit);
		//return $tdata = array( 1 => 'test1', 2 => 'test2' );
		
	}
	public function getsalesman()
	{
		$data = array();
		$salesman = $this->salesman->getSalesmanList(); 
		return view('body.profitanalysis.multiselect')
					->withSalesman($salesman)
					->withType('SALE')
					->withData($data);
					
		/* $data = array();
		$data = $this->accountmaster->getAccountByGroup('CUSTOMER');
		if($data) {
			$result = array();
			foreach($data as $val) {
				$result[$val['id']] = $val['master_name'];
			}
			return $result;
		} else 
			return null; */
		
		//echo '<pre>';print_r($unit);
		//return $tdata = array( 1 => 'test1', 2 => 'test2' );
		
	}
	
	public function getItems()
	{
		$data = array();
		$items = $this->itemmaster->activeItemmasterList();
		return view('body.profitanalysis.multiselect')
					->withItems($items)
					->withType('ITEM')
					->withData($data);

	}
	public function getArea()
	{
		$data = array();
		$area = $this->area->activeAreaList();
		return view('body.profitanalysis.multiselect')
					->withArea($area)
					->withType('AREA')
					->withData($data);

	}
	public function getgroup()
	{
		$data = array();
		$group= $this->group->groupList();
		return view('body.profitanalysis.multiselect')
					->withGroup($group)
					->withType('GROUP')
					->withData($data);

	}
	public function getSubGroup()
	{
		$data = array();
		$subGroup= $this->group->subgroupList();
		//echo '<pre>';print_r($subGroup);
		return view('body.profitanalysis.multiselect')
					->withSubGroup($subGroup)
					->withType('SUBGROUP')
					->withData($data);

	}
}
