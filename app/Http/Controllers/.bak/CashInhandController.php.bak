<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Acgroup\AcgroupInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class CashInhandController extends Controller
{
   
	protected $accountmaster;
	protected $acgroup;

	public function __construct(AccountMasterInterface $accountmaster, AcgroupInterface $acgroup) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );$this->accountmaster = $accountmaster;
		$this->acgroup = $acgroup;
		$this->middleware('auth');
	}
	
	public function index() {
		$data = array();
		
		
		return view('body.cashinhand.index')
					
					->withSettings($this->acsettings)
					->withData($data);
	}
	

	public function getSearch()
	{	//echo '<pre>';print_r(Input::all());exit;
		$data = array(); 
		$from=Input::get('from_date');
		$date_from =$this->acsettings->from_date; //date('Y-m-d', strtotime(Input::get('from_date')));
		$date_to = (Input::get('to_date')!='')?date('Y-m-d', strtotime(Input::get('to_date'))):date('Y-m-d');
		//Input::merge(['curr_from_date' => $this->acsettings->from_date]);

  //echo '<pre>';print_r($date_from);exit;
  
  $selectedCategories = [];
$balances=[];
if (Input::get('bank') == 1)$selectedCategories[] = 'BANK';
if (Input::get('cash') == 1)   $selectedCategories[] = 'CASH';
if (Input::get('PDCR') == 1)$selectedCategories[] = 'PDCR';
if (Input::get('PDCI') == 1)   $selectedCategories[] = 'PDCI';

// If nothing is selected, include all categories

if (empty($selectedCategories)) {
    $selectedCategories = ['BANK', 'CASH', 'PDCR', 'PDCI'];
}
$selectedCategory=implode(',',$selectedCategories);
//echo '<pre>';print_r($selectedCategories);exit;
if(Input::get('search_type')=='Summary'){
foreach($selectedCategories as $category){
    $cashAccountIds = DB::table('account_master')
    ->where('deleted_at', '0000-00-00 00:00:00')
    ->where('category', $category)
    ->pluck('id');
    $transactions = DB::table('account_transaction')
    ->whereIn('account_master_id', $cashAccountIds)
    ->where('deleted_at', '0000-00-00 00:00:00')
    ->where('voucher_type', '!=', 'OBD')
    //->whereBetween('invoice_date', array($date_from, $date_to))
    ->where('invoice_date','<=',$date_to)
    ->where('amount', '!=', 0)
    ->select(
        DB::raw("SUM(CASE WHEN transaction_type = 'Dr' THEN amount ELSE 0 END) AS total_debit"),
        DB::raw("SUM(CASE WHEN transaction_type = 'Cr' THEN amount ELSE 0 END) AS total_credit"))
       ->first();

  //echo '<pre>';print_r($transactions);exit;
    $net = ($transactions->total_debit ?? 0) - ($transactions->total_credit ?? 0);
    
    $res[] = (object)(array('categories'=>$category,
                             'debit' => $transactions->total_debit,
							'credit'=>$transactions->total_credit,
							'balance'=>$net
									));
    $balances[$category]=$net;
}
$voucher_head = 'Cash Balance Summary of '.$from;
}
else if(Input::get('search_type')=='Details'){
    
    foreach ($selectedCategories as $category) {
    $accounts = DB::table('account_master')
        ->where('category',$category)
        ->where('deleted_at', '0000-00-00 00:00:00')
        ->get(['id', 'master_name']);

    foreach ($accounts as $account) {
        $transactions = DB::table('account_transaction')
            ->where('account_master_id',$account->id)
            ->where('deleted_at', '0000-00-00 00:00:00')
            ->where('voucher_type', '!=', 'OBD')
            //->whereBetween('invoice_date', [$date_from,$date_to])
            ->where('invoice_date','<=',$date_to)
            ->where('amount', '!=', 0)
            ->selectRaw("
                SUM(CASE WHEN transaction_type = 'Dr' THEN amount ELSE 0 END) AS debit,
                SUM(CASE WHEN transaction_type = 'Cr' THEN amount ELSE 0 END) AS credit
            ")
            ->first();
            
            if ($transactions->debit != 0 ||$transactions->credit != 0) {
            $res[$category][] = [
                'account_name' => $account->master_name,
                'debit' => (float)$transactions->debit,
                'credit' => (float) $transactions->credit,
                'balance' => (float)$transactions->debit - (float) $transactions->credit,
            ];
}
}
}
$voucher_head = 'Cash Balance Details of'.$from;
}
		//echo '<pre>';print_r($res);exit;
	
		//echo '<pre>';print_r($voucher_head);exit;
		return view('body.cashinhand.print')
					->withResults($res)
					->withFromdate($from)
					->withTodate(Input::get('to_date'))
					->withVoucherhead($voucher_head)
					->withSettings($this->acsettings)
					->withType(Input::get('search_type'))
					->withSelectedcategories($selectedCategory)
					->withData($data);
	}
	
	
	public function dataExport() {
		
		$data = array(); 
		//echo '<pre>';print_r(Input::all());exit;
		$selectedCategories=explode(',',Input::get('selectedcategory'));
		$from=Input::get('date_from');
		$date_to = (Input::get('date_to')!='')?date('Y-m-d', strtotime(Input::get('date_to'))):date('Y-m-d');
		
	
		//echo '<pre>';print_r($selectedCategories);exit;
		$datareport[] = ['','',Session::get('company'),'',''];
		
		
	if(Input::get('search_type')=='Summary') {
			
            foreach($selectedCategories as $category){
                    $cashAccountIds = DB::table('account_master')
                                      ->where('deleted_at', '0000-00-00 00:00:00')
                                       ->where('category', $category)
                                       ->pluck('id');
                    $transactions = DB::table('account_transaction')
                                    ->whereIn('account_master_id', $cashAccountIds)
                                    ->where('deleted_at', '0000-00-00 00:00:00')
                                    ->where('voucher_type', '!=', 'OBD')
                                  //->whereBetween('invoice_date', array($date_from, $date_to))
                                   ->where('invoice_date','<=',$date_to)
                                  ->where('amount', '!=', 0)
                                   ->select(
                                            DB::raw("SUM(CASE WHEN transaction_type = 'Dr' THEN amount ELSE 0 END) AS total_debit"),
                                             DB::raw("SUM(CASE WHEN transaction_type = 'Cr' THEN amount ELSE 0 END) AS total_credit"))
                                              ->first();

             //echo '<pre>';print_r($transactions);exit;
                              $net = ($transactions->total_debit ?? 0) - ($transactions->total_credit ?? 0);
    
                        $res[] = (object)(array('categories'=>$category,
                             'debit' => $transactions->total_debit,
							'credit'=>$transactions->total_credit,
							'balance'=>$net
									));
                       $balances[$category]=$net;
        }
                       $voucher_head = 'Cash Balance Summary of '.$from;
			//echo '<pre>';print_r($results);exit;
			$datareport[] = ['',$voucher_head];
			$datareport[] = ['From Date:',$from,'To Date:',date('d-m-Y', strtotime($date_to))];
			$datareport[] =['','','','',''];
			$datareport[] = ['Account Group','','Balance'];
			$cr_total = $dr_total = $bl_total = 0;
				
			foreach($res as $row) {
			    $bl_total+=$row->balance;
			    if($row->categories!='PDCI'){
			       $cr_total += $row->balance;
				
				$datareport[] = ['group' => $row->categories,
				                  'x'=>'',
							      'bal'	=> number_format($row->balance,2),
							 
							];
							
			    }
			}
			$datareport[] =['','','','',''];
			$datareport[] = ['Cash and Other Bills Receivable :','',number_format($cr_total,2)];
			
			
			foreach($res as $row) {
			   
			    if($row->categories=='PDCI'){
			       $dr_total+=$row->balance;;
				
				$datareport[] = ['group' => $row->categories,
				                  'x'=>'',
							      'bal'	=> number_format($row->balance,2),
							 
							];
							
			    }
			}
			$datareport[] =['','','','',''];
			$datareport[] = ['Bills Payable :','',number_format($dr_total,2)];
			$datareport[] =['','','','',''];
			$datareport[] = ['Net Cash In Hand  :','',number_format($bl_total,2)];
			
		} else if(Input::get('search_type')=='Details') {
			 
                        foreach ($selectedCategories as $category) {
                               $accounts = DB::table('account_master')
                                       ->where('category',$category)
                                        ->where('deleted_at', '0000-00-00 00:00:00')
                                         ->get(['id', 'master_name']);

                             foreach ($accounts as $account) {
                                           $transactions = DB::table('account_transaction')
                                                           ->where('account_master_id',$account->id)
                                                         ->where('deleted_at', '0000-00-00 00:00:00')
                                                         ->where('voucher_type', '!=', 'OBD')
                                                           //->whereBetween('invoice_date', [$date_from,$date_to])
                                                           ->where('invoice_date','<=',$date_to)
                                                           ->where('amount', '!=', 0)
                                                         ->selectRaw("
                                                                     SUM(CASE WHEN transaction_type = 'Dr' THEN amount ELSE 0 END) AS debit,
                                                                    SUM(CASE WHEN transaction_type = 'Cr' THEN amount ELSE 0 END) AS credit ")
                                                                    ->first();
            
                                    if ($transactions->debit != 0 ||$transactions->credit != 0) {
                                      $res[$category][] = [
                                                            'account_name' => $account->master_name,
                                                            'debit' => (float)$transactions->debit,
                                                             'credit' => (float) $transactions->credit,
                                                             'balance' => (float)$transactions->debit - (float) $transactions->credit,
                                                             ];
                                        }
                                }
                           }
                            $voucher_head = 'Cash Balance Details of'.$from;
                            
                            $datareport[] = ['','',$voucher_head,''];
			                  $datareport[] = ['From Date:',$from,'To Date:',date('d-m-Y', strtotime($date_to))];
			                 $datareport[] =['','','','',''];
			                 
			                        $net_total  = 0;
				            foreach($res as $category =>$rows) {
			                     $datareport[] =['','',$category,'',''];
			                      $datareport[] = ['Account Group','','Balance'];
			                      
			                 $total = 0;
			                 foreach($rows as $row){
			                     $total += $row['balance'];
			                     
			     $datareport[] = ['group' => $row['account_name'],
				                  'x'=>'',
							      'bal'	=> number_format($row['balance'], 2),
							 
							];
							
			              }
			              $datareport[] =['','','','',''];
			            $datareport[] = ['','Total:',number_format($total,2)];
			             $net_total += $total;
			             }
			              $datareport[] =['','','','',''];
			            $datareport[] = ['','Net Total:',number_format($net_total,2)];
		}  
		$titles = ['main_head' => 'Trial Balance','subhead' => $voucher_head ];
		
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
    
   
	
	

			
}