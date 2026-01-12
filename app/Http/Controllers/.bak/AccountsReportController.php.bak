<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;

class AccountsReportController extends Controller
{
   
	protected $accountmaster;
	protected $sales_invoice;
	protected $purchase_invoice;


	public function __construct(AccountMasterInterface $accountmaster, SalesInvoiceInterface $sales_invoice, PurchaseInvoiceInterface $purchase_invoice) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		
		$this->accountmaster = $accountmaster;
		$this->sales_invoice = $sales_invoice;
		$this->purchase_invoice = $purchase_invoice;
		$this->middleware('auth');
	}
		
	public function index()
	{
		$data = array();
		$acmasters = [];
		return view('body.accountsreport.index')
					->withAcmasters($acmasters)
					->withSettings($this->acsettings)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'account_master.id',
							1 => 'account_id', 
                            2 => 'master_name',
                            3 => 'group_name',
                            4 => 'category_name',
                            5 => 'cl_balance',
							6 => 'op_balance'
                        );
						
		$totalData = $this->accountmaster->CustomerSupplierCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$acmasters = $this->accountmaster->CustomerSupplierList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->accountmaster->CustomerSupplierList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($acmasters))
        {
            foreach ($acmasters as $row)
            {
                $view =  '"'.url('account_master/view/'.$row->id).'"';
				$opt =  $row->id.'-'.$row->category;
				
				$nestedData['opt'] = "<input type='radio' name='account' class='opt-account' value='{$opt}'/>";
                
				$nestedData['id'] = $row->id;
                $nestedData['account_id'] = $row->account_id;
				$nestedData['master_name'] = $row->master_name;
				$nestedData['group_name'] = $row->group_name;
				$nestedData['category_name'] = $row->category_name;
				$nestedData['cl_balance'] = $row->cl_balance;
				$nestedData['op_balance'] = $row->op_balance;
				$nestedData['issued_qty'] = $row->issued_qty;
				$nestedData['view'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$view}'>
												<span class='glyphicon glyphicon-eye-open'></span></button></p>";
												
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
	
	protected function makeTreeVchr($result)
	{
		$childs = array();
		foreach($result as $item)
				$childs[$item->sales_invoice_id][] = $item;
			
		return $childs;
	}
		
	public function getSearch()
	{ 	
		$arr = explode('-',Input::get('account'));
		$id = $arr[0]; $type = $arr[1];
		
		$data = array();
		
		if($type=='CUSTOMER') {
			$voucher_head = 'Customer Itemwise Report';
			$reports = $this->makeTreeVchr( $this->sales_invoice->getCustomerIitems($id,Input::all()) );
			//echo '<pre>';print_r($reports);exit;
		} else {
			$voucher_head = 'Supplier Itemwise Report';
			$reports = $this->makeTreeVchr( $this->purchase_invoice->getPurchaseIitems($id,Input::all()) );
			//echo '<pre>';print_r($reports);exit;
		}
		
		return view('body.accountsreport.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($type)
					->withId($id)
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withData($data);
	}
	
	public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$id = Input::get('id');
		$type = Input::get('type');
		
		if($type=='CUSTOMER') {
			$voucher_head = 'Customer Itemwise Report';
			$reports = $this->makeTreeVchr( $this->sales_invoice->getCustomerIitems($id,Input::all()) );
		} else {
			$voucher_head = 'Supplier Itemwise Report';
			$reports = $this->makeTreeVchr( $this->purchase_invoice->getPurchaseIitems($id,Input::all()) );
			//echo '<pre>';print_r($reports);exit;
		}
		
				
		$datareport[] = ['','','',strtoupper($voucher_head),'','',''];	
		$datareport[] = ['','','','','','',''];	
		$qty_total = $net_total = $vat_total = $gross_total = 0;
		
		foreach ($reports as $report) {
			
			$datareport[] = ['Inv. No:',$report[0]->voucher_no, 'Inv.Date:', $report[0]->voucher_date,'',$type.':',$report[0]->master_name];
			$datareport[] = ['Item Code','Description','Quantity','Rate','Total Amt.','VAT Amt.','Net Amt.'];
			
			foreach($report as $row) {
			  $qty_total += $row->quantity;
			  $gross_total += $row->line_total;
			  $net_amount = $row->vat_amount + $row->line_total;
			  $vat_total += $row->vat_amount;
			  $net_total += $net_amount;
										  
			  $datareport[] = [ 'code' => $row->item_code,
							  'description' => $row->item_name,
							  'qty' => $row->quantity,
							  'price' => number_format($row->unit_price,2),
							  'total' => number_format($row->line_total,2),
							  'vat' => number_format($row->vat_amount,2),
							  'nettotal' => number_format($net_amount,2)
							];
			}
			$datareport[] = ['','','','','','',''];	
		}
		$datareport[] = ['','','','','','',''];	
		$datareport[] = ['','Total:', $qty_total,'',number_format($gross_total,2),number_format($vat_total,2),number_format($net_total,2)];
			
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
	
}