<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SalesInvoice\SalesInvoiceInterface;
use App\Repositories\SalesReturn\SalesReturnInterface;
use App\Repositories\PurchaseInvoice\PurchaseInvoiceInterface;
use App\Repositories\PurchaseReturn\PurchaseReturnInterface;
use App\Repositories\SupplierDo\SupplierDoInterface;
use App\Repositories\CustomerDo\CustomerDoInterface;
use App\Repositories\GoodsIssued\GoodsIssuedInterface;
use App\Repositories\GoodsReturn\GoodsReturnInterface;
use App\Repositories\StockTransferin\StockTransferinInterface;
use App\Repositories\StockTransferout\StockTransferoutInterface;
use App\Repositories\Manufacture\ManufactureInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use Excel;
use App;
use DB;
use Auth;

class TransactionListController extends Controller
{
   
	protected $sales_invoice;
	protected $purchase_invoice;
	protected $sales_return;
	protected $purchase_return;
	protected $supplierdo;
	protected $customerdo;
	protected $goods_issued;
	protected $goods_return;
	protected $stock_transferin;
	protected $stock_transferout;
    protected $manufacture;

	public function __construct(SalesInvoiceInterface $sales_invoice, PurchaseInvoiceInterface $purchase_invoice, SalesReturnInterface $sales_return, PurchaseReturnInterface $purchase_return,SupplierDOInterface $supplierdo,CustomerDOInterface $customerdo,ManufactureInterface $manufacture,GoodsIssuedInterface $goods_issued,GoodsReturnInterface $goods_return,StockTransferinInterface $stock_transferin,StockTransferoutInterface $stock_transferout) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->sales_invoice = $sales_invoice;
		$this->purchase_invoice = $purchase_invoice;
		$this->sales_return = $sales_return;
		$this->purchase_return = $purchase_return;
		$this->supplierdo = $supplierdo;
		$this->customerdo = $customerdo;
		$this->goods_issued = $goods_issued;
		$this->goods_return = $goods_return;
		$this->stock_transferin = $stock_transferin;
		$this->stock_transferout = $stock_transferout;
		$this->manufacture = $manufacture;
		$this->mod_sdo = DB::table('parameter2')->where('keyname','mod_sdo_qty_update')->where('status',1)->select('is_active')->first();
		$this->mod_do = DB::table('parameter2')->where('keyname','mod_do_qty_update')->where('status',1)->select('is_active')->first();
	}
		
	public function index()
	{
		$data = array(); 
		$acmasters = [];
		return view('body.transactionlist.index')
					->withSettings($this->acsettings)
					->withModdo($this->mod_do->is_active)
					->withModsdo($this->mod_sdo->is_active)
					->withData($data);
	}
	
	protected function groupInvoice($result)
	{
		$childs = array();
		foreach($result as $item)
				$childs[$item->id][] = $item;
			
		return $childs;
	}
	
	public function getSearch()
	{
		if(Input::get('search_type')=='Purchase') {
			$voucherhead = 'Purchase Transaction List';
			$results = $this->groupInvoice( $this->purchase_invoice->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($results);exit;
			
		} elseif(Input::get('search_type')=='Sales') {
			$voucherhead = 'Sales Transaction List';
			$results = $this->groupInvoice( $this->sales_invoice->getTransactionList(Input::all()) );
			
		} else if(Input::get('search_type')=='PurchaseReturn') {
			$voucherhead = 'Purchase Return Transaction List';
			$results = $this->groupInvoice( $this->purchase_return->getTransactionList(Input::all()) );
			
			//echo '<pre>';print_r($result);exit;
		} elseif(Input::get('search_type')=='SalesReturn') {
			$voucherhead = 'Sales Return Transaction List';
			$results = $this->groupInvoice( $this->sales_return->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}elseif(Input::get('search_type')=='SupplierDO') {
			$voucherhead = 'Supplier DO Transaction List';
			$results = $this->groupInvoice( $this->supplierdo->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}
		elseif(Input::get('search_type')=='CustomerDO') {
			$voucherhead = 'Customer DO Transaction List';
			$results = $this->groupInvoice( $this->customerdo->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}
		elseif(Input::get('search_type')=='GoodsIssued') {
			$voucherhead = 'Goods Issued Transaction List';
			$results = $this->groupInvoice( $this->goods_issued->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}	elseif(Input::get('search_type')=='GoodsReturn') {
			$voucherhead = 'Goods Return Transaction List';
			$results = $this->groupInvoice( $this->goods_return->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}   elseif(Input::get('search_type')=='TransferIn') {
			$voucherhead = 'Stock Transfer In Transaction List';
			$results = $this->groupInvoice( $this->stock_transferin->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}elseif(Input::get('search_type')=='TransferOut') {
			$voucherhead = 'Stock Transfer Out Transaction List';
			$results = $this->groupInvoice( $this->stock_transferout->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}elseif(Input::get('search_type')=='Manufacture') {
			$voucherhead = 'Manufacture Transaction List';
			$results = $this->groupInvoice( $this->manufacture->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}
		
		
		return view('body.transactionlist.preprint')
					->withReports($results)
					->withVoucherhead($voucherhead)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'));
	}
	
	public function dataExport()
	{
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		if(Input::get('search_type')=='Purchase') {
			$voucher_head = 'Purchase Transaction List';
			$results = $this->groupInvoice( $this->purchase_invoice->getTransactionList(Input::all()) );
			
		} elseif(Input::get('search_type')=='Sales') {
			$voucher_head = 'Sales Transaction List';
			$results = $this->groupInvoice( $this->sales_invoice->getTransactionList(Input::all()) );
		} else if(Input::get('search_type')=='PurchaseReturn') {
			$voucher_head = 'Purchase Return Transaction List';
			$results = $this->groupInvoice( $this->purchase_return->getTransactionList(Input::all()) );
		} elseif(Input::get('search_type')=='SalesReturn') {
			$voucher_head = 'Sales Return Transaction List';
			$results = $this->groupInvoice( $this->sales_return->getTransactionList(Input::all()) );
		}elseif(Input::get('search_type')=='SupplierDO') {
			$voucher_head = 'Supplier DO Transaction List';
			$results = $this->groupInvoice( $this->supplierdo->getTransactionList(Input::all()) );
			
		}
		elseif(Input::get('search_type')=='CustomerDO') {
			$voucher_head = 'Customer DO Transaction List';
			$results = $this->groupInvoice( $this->customerdo->getTransactionList(Input::all()) );
			
		}
		elseif(Input::get('search_type')=='GoodsIssued') {
			$voucher_head = 'Goods Issued Transaction List';
			$results = $this->groupInvoice( $this->goods_issued->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}	elseif(Input::get('search_type')=='GoodsReturn') {
			$voucher_head = 'Goods Return Transaction List';
			$results = $this->groupInvoice( $this->goods_return->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}   elseif(Input::get('search_type')=='TransferIn') {
			$voucher_head = 'Stock Transfer In Transaction List';
			$results = $this->groupInvoice( $this->stock_transferin->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}elseif(Input::get('search_type')=='TransferOut') {
			$voucher_head = 'Stock Transfer Out Transaction List';
			$results = $this->groupInvoice( $this->stock_transferout->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}elseif(Input::get('search_type')=='Manufacture') {
			$voucher_head = 'Manufacture Transaction List';
			$results = $this->groupInvoice( $this->manufacture->getTransactionList(Input::all()) );
			//echo '<pre>';print_r($result);exit;
		}
				
		$datareport[] = ['','',strtoupper($voucher_head),'',''];	
		$qty_gtotal = $gross_gtotal = $vat_gtotal = 0;
		
		foreach ($results as $report) {
			
			$datareport[] = ['Inv. No:',$report[0]->voucher_no, 'Inv.Date:', date('d-m-Y',strtotime($report[0]->voucher_date)),''];
			$datareport[] = ['Item Code','Description','Quantity','Rate','Other Cost','Net Cost','VAT Amt','Total Amt.'];
			
			$qty_total = $net_total = $vat_total = $gross_total = $vat_gtotal = 0;
			
			foreach($report as $row) {
			  $qty_total += $row->quantity;
			  $line_total = $row->total_price + $row->vat_amount;
			  $gross_total += $line_total;
			  $vat_total += $row->vat_amount;
										  
			  $datareport[] = [ 'code' => $row->item_code,
							  'description' => $row->description,
							  'qty' => $row->quantity,
							  'price' => number_format($row->unit_price,2),
							  'other_cost' => number_format($row->othercost_unit,2),
							  'netcost' => number_format($row->netcost_unit,2),
							  'vat' => number_format($row->vat_amount,2),
							  'nettotal' => number_format($line_total,2)
							];
			}
			$datareport[] = ['','Sub Total:',$qty_total,'','','',number_format($vat_total,2),number_format($gross_total,2)];	
			$datareport[] = ['','','','',''];	
			$qty_gtotal += $qty_total;
			$gross_gtotal += $gross_total;
			$vat_gtotal += $vat_total;
		}
		 
		$datareport[] = ['','Grand Total:', $qty_gtotal,'','','',number_format($vat_gtotal,2),number_format($gross_gtotal,2)];
			
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