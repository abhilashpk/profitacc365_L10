<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Acgroup\AcgroupInterface;

use App\Models\AccountMaster;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;

//use App\Exports\TrialBalanceExport;

use App\Http\Requests;
use Notification;
use Session;
use App;
use Excel;
use Auth;
use Carbon\Carbon;

class TrialBalanceController2 extends Controller
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
		$acmasters = [];//$this->accountmaster->accountMasterList();
		$groups = $this->acgroup->acgroupList();

		DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
			
		$this->AcUpdate($this->makeGroup( $this->accountmaster->updateUtility('CB')) );

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
		
		return view('body.trialbalance2.index')
					->withAcmasters($acmasters)
					->withGroups($groups)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}

    protected function makeGroup($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}

    protected function AcUpdate($results)
	{
		$arrSummarry = array();
		foreach($results as $result)
		{
			$arraccount = array(); 
			$dramount = $cramount = 0;
			foreach($result as $row) {
				$cl_balance = $row->cl_balance;
				$account_id = $row->id;
				if($row->transaction_type=='Dr') {
					$amountD = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$dramount += $amountD;
				} else {
					$amountC = ($row->amount < 0)?(-1*$row->amount):$row->amount;
					$cramount += $amountC;
				}
			}
			
			$amount = $dramount - $cramount;
			//$amount = ($amount < 0)?(-1*$amount):$amount;
			if($amount != $cl_balance) {
				//update the closing balance as amount.....
				$this->accountmaster->updateClosingBalance($account_id, $amount);
			}
				
		}
		return true;
	}
	
	 public function memoryLimit(){
        
        ini_set('memory_limit','512M');
    }
    
    
    public function searchReport(Request $request)
	{
        $search_type = $request->get('search_type');
        $settings = $this->acsettings;
        $trimzero = $request->get('trim_zero');
        $this->memoryLimit();
        
        $mindate = DB::table('account_transaction')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->min('invoice_date'); 

        if ($request->get('search_type') == 'opening_summary') {
                $parafrom = $mindate;//$settings->from_date;
                $voucherhead = 'Trial Balance Opening Summary (Groupwise)';
                $from = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y-m-d') : $parafrom;
                $to = date('Y-m-d');

                $accounts = AccountMaster::select(
                    'account_group.name as group_name',
                    'account_transaction.transaction_type',
                    'account_transaction.amount'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->join('account_transaction', 'account_transaction.account_master_id', '=', 'account_master.id')
                ->where('account_transaction.voucher_type', 'OB')
                ->where('account_transaction.status', 1)
                ->whereNull('account_transaction.deleted_at')
                ->whereNull('account_master.deleted_at')
                ->where('account_master.status', 1)
                ->get();

                $grouped = $accounts->groupBy('group_name')->map(function ($group) {
                    return [
                        'debit'  => $group->where('transaction_type', 'Dr')->sum('amount'),
                        'credit' => $group->where('transaction_type', 'Cr')->sum('amount'),
                    ];
                });

                if($trimzero==1) {
                    $grouped = $grouped->filter(function ($item) {
                        return ($item['debit'] != 0 || $item['credit'] != 0);
                    });
                }
//echo '<pre>';print_r($grouped);exit;
                // For export
                if ($request->has('export')) {
                    return $this->exportSearchReport($grouped, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }

                return view('body.trialbalance2.report-opening-summary', compact('grouped', 'from', 'to', 'voucherhead','search_type', 'trimzero'));


        } else if ($request->get('search_type') === 'opening_groupwise') {
                $parafrom   = $mindate;//$settings->from_date;
                $to         = $parato = date('Y-m-d');
                $voucherhead = 'Trial Balance Opening Detail (Groupwise)';

                $from = $request->get('from_date')
                    ? Carbon::parse($request->get('from_date'))->format('Y-m-d')
                    : $parafrom;

                // Build query for account masters with group info
                $query = AccountMaster::select(
                    'account_master.id',
                    'account_master.account_id',
                    'account_master.master_name',
                    'account_group.name as group_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id')
                ->where('account_master.status', 1)
                ->whereNull('account_master.deleted_at');

                // Eager load only opening balance transactions
                $query->with(['transactions' => function ($qry) {
                    $qry->where('voucher_type', 'OB')
                        ->where('status', 1)
                        ->whereNull('deleted_at');
                }]);

                $accounts = $query->get()->map(function ($acc) {
                    $opDr = $acc->transactions->where('transaction_type', 'Dr')->sum('amount');
                    $opCr = $acc->transactions->where('transaction_type', 'Cr')->sum('amount');

                    return [
                        'group_name' => $acc->group_name ?: 'No Group',
                        'account_name' => $acc->account_id.' - '.$acc->master_name,
                        'debit'  => $opDr,
                        'credit' => $opCr,
                    ];
                });

                if($trimzero==1) {
                    $accounts = $accounts->filter(function ($item) {
                        return ($item['debit'] != 0 || $item['credit'] != 0);
                    });
                }

                $grouped = $accounts->groupBy('group_name');

                if ($request->has('export')) {
                    return $this->exportSearchReport($grouped, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }
//echo '<pre>';print_r($grouped);exit;
        return view('body.trialbalance2.report-opening-groupwise', compact('grouped', 'from', 'to', 'voucherhead','search_type', 'trimzero'));


    } else if ($request->get('search_type') == 'closing_summary') {

                $parafrom = $mindate;//$settings->from_date;
                $parato = date('Y-m-d');
                $voucherhead = 'Trial Balance Closing Summary (Groupwise)';

                $from = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y-m-d') : $settings->from_date;
                $to   = $request->get('to_date')   ? Carbon::parse($request->get('to_date'))->format('Y-m-d')   : $parato;

                $query = AccountMaster::select(
                    'account_master.id',
                    'account_master.master_name',
                    'account_master.transaction_type as opening_type',
                    'account_master.op_balance as opening_balance',
                    'account_group.name as group_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id');

                if ($from != $parafrom) {
                    $query->with([
                        'prior_transactions' => function ($qry) use ($parafrom, $from) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$parafrom, date('Y-m-d', strtotime($from . ' -1 day'))]);
                        },
                        'current_transactions' => function ($qry) use ($from, $to) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$from, $to]);
                        }
                    ]);
                } else {
                    $query->with([
                        'current_transactions' => function ($qry) use ($from, $to) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$from, $to]);
                        }
                    ]);
                }

                $query->where('account_master.status', 1)
                    ->where(function ($q) {
                        $q->whereNull('account_master.deleted_at');
                    });

                $accounts = $query->get()->map(function ($account) use ($from, $parafrom) {
                    $opDr = 0;
                    $opCr = 0;

                    $priorDr = $priorCr = 0;
                    if ($from != $parafrom) {
                        $priorDr = $account->prior_transactions->where('transaction_type', 'Dr')->sum('amount');
                        $priorCr = $account->prior_transactions->where('transaction_type', 'Cr')->sum('amount');
                    }

                    $currDr = $account->current_transactions->where('transaction_type', 'Dr')->sum('amount');
                    $currCr = $account->current_transactions->where('transaction_type', 'Cr')->sum('amount');

                    $net = ($opDr + $priorDr + $currDr) - ($opCr + $priorCr + $currCr);

                    return [
                        'group_name' => $account->group_name ?: 'No Group',
                        'debit' => $net >= 0 ? $net : 0,
                        'credit' => $net < 0 ? abs($net) : 0,
                    ];
                });

                // Group by and summarize
                $groupedSummary = $accounts->groupBy('group_name')->map(function ($group) {
                    return [
                        'debit' => $group->sum('debit'),
                        'credit' => $group->sum('credit'),
                    ];
                });

                if ($trimzero==1) {
                    $groupedSummary = $groupedSummary->filter(function ($amounts) {
                        return $amounts['debit'] != 0 || $amounts['credit'] != 0;
                    });
                }

                if ($request->has('export')) {
                    return $this->exportSearchReport($groupedSummary, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }

                return view('body.trialbalance2.report-closing-summary', compact('groupedSummary', 'from', 'to', 'voucherhead', 'search_type', 'trimzero'));


        } elseif($request->get('search_type')=='closing_groupwise') {

            $parafrom = $mindate;//$settings->from_date; 
            $parato = date('Y-m-d');
            $voucherhead = 'Trial Balance Closing Detail(Groupwise)';

            if($request->get('from_date')=='') {
                $from = $settings->from_date;//$parafrom;
            } else
                $from = Carbon::parse($request->get('from_date'))->format('Y-m-d');

            if($request->get('to_date')=='') {
                $to = $parato;
            } else
                $to = Carbon::parse($request->get('to_date'))->format('Y-m-d'); 

            $query = AccountMaster::select(
                    'account_master.account_id',
                    'account_master.id',
                    'account_master.master_name',
                    'account_master.transaction_type as opening_type',
                    'account_master.op_balance as opening_balance',
                    'account_group.name as group_name',
                    'account_category.name as category_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id');

                if($request->get('group_id')!='') {
                    $grparr = explode(',', $request->get('group_id'));
                    $query->whereIn('account_master.account_group_id', $grparr);
                }
            
                if($from != $parafrom) { 
                    $query->with([
                            // Transactions before current month (for adjusted opening)
                            'prior_transactions' => function ($qry) use ($parafrom, $from) { 
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at'); //->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$parafrom, date('Y-m-d',strtotime($from .' - 1 day')) ]); 
                            },
                            // Transactions in the selected month
                            'current_transactions' => function ($qry) use ($from, $to) {
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at'); //->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$from, $to]);
                            }
                        ]);
                } else {

                    $query->with([
                            // Transactions in the selected month
                            'current_transactions' => function ($qry) use ($from, $to) {
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at');//->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$from, $to]);
                            }
                        ]);
                }

            $query->where('account_master.status', 1)
                ->where(function ($q) {
                    $q->whereNull('account_master.deleted_at');//->orWhere('account_master.deleted_at', '0000-00-00 00:00:00');
                });

                $accounts = $query->get()
                    ->map(function ($account) use ($from, $parafrom) {
                        // Opening
                        $opDr = 0;//$account->opening_type === 'Dr' ? $account->opening_balance : 0;
                        $opCr = 0;//$account->opening_type === 'Cr' ? $account->opening_balance : 0;

                        // Transactions before the month
                        if($from != $parafrom) {
                            $priorDr = $account->prior_transactions->where('transaction_type', 'Dr')->sum('amount');
                            $priorCr = $account->prior_transactions->where('transaction_type', 'Cr')->sum('amount');
                        } else {
                            $priorDr = $priorCr = 0;
                        }
                        // Current month transactions
                        $currDr = $account->current_transactions->where('transaction_type', 'Dr')->sum('amount');
                        $currCr = $account->current_transactions->where('transaction_type', 'Cr')->sum('amount');

                        // Net Balance calculation
                        $net = ($opDr + $priorDr + $currDr) - ($opCr + $priorCr + $currCr);

                        return [
                            'account_id'   => $account->account_id,
                            'account_name'   => $account->master_name,
                            'group_name'     => $account->group_name,
                            'category_name'  => $account->category_name,
                            'current_debit'  => $currDr,
                            'current_credit' => $currCr,
                            'debit'  => $net >= 0 ? $net : 0,
                            'credit' => $net < 0 ? abs($net) : 0,
                        ];
                });

                if ($trimzero==1) {
                    $accounts = $accounts->filter(function ($acc) {
                        return ($acc['debit'] != 0 || $acc['credit'] != 0);
                    })
                    ->values();
                }

                

            //echo '<pre>';print_r($accounts);exit;

            $groupedAccounts = $accounts->groupBy('group_name');

            if ($request->has('export')) { 
                return $this->exportSearchReport($groupedAccounts, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
            }

            return view('body.trialbalance2.report-closing-groupwise', compact('groupedAccounts', 'from', 'to', 'voucherhead', 'search_type', 'trimzero'));

        } else if($request->get('search_type')=='YTD') {

            $voucherhead = 'Trial Balance YTD';
            //$from = Carbon::parse($request->get('from_date'))->format('Y-m-d');
            //$to = Carbon::parse($request->get('to_date'))->format('Y-m-d');
            
            //..........
            $parafrom = $mindate; //$settings->from_date; 
            $parato = date('Y-m-d');

            if($request->get('from_date')=='') {
                $from = $settings->from_date; //$parafrom;
            } else
                $from = Carbon::parse($request->get('from_date'))->format('Y-m-d');

            if($request->get('to_date')=='') {
                $to = $parato;
            } else
                $to = Carbon::parse($request->get('to_date'))->format('Y-m-d'); 

            //....................

            $accounts = AccountMaster::with(['group.category'])->where('status', 1)->get();
            $results = [];

            foreach ($accounts as $account) {
                // Find the Opening Balance from 'OB' voucher type (for the date closest to 'from')
				$openingBalanceTransaction = AccountTransaction::where('account_master_id', $account->id)
					->where('voucher_type', 'OB')
					->where('invoice_date', '<=', $from) // Ensure the opening balance is before or on the 'from' date
					->orderBy('invoice_date', 'desc') // Get the most recent opening balance
					->first();

				$openingDr = 0;
				$openingCr = 0;

				if ($openingBalanceTransaction) {
					// If the opening balance transaction exists, use it
					$openingDr = $openingBalanceTransaction->transaction_type == 'Dr' ? $openingBalanceTransaction->amount : 0;
					$openingCr = $openingBalanceTransaction->transaction_type == 'Cr' ? $openingBalanceTransaction->amount : 0;
				}

                // Opening balance + transactions before "from" date
                $priorTransactions = AccountTransaction::where('account_master_id', $account->id)
                    //->where('invoice_date', '<', '2022-01-01')//$from
                    ->whereBetween('invoice_date', [$parafrom, date('Y-m-d',strtotime($from .' - 1 day'))])
                    ->where('status', 1)
                    ->where('voucher_type', '!=', 'OBD')->where('voucher_type', '!=', 'OB')
                    ->get();

                $priorDr = $priorTransactions->where('transaction_type', 'Dr')->sum('amount');
                $priorCr = $priorTransactions->where('transaction_type', 'Cr')->sum('amount');
				
				// Add prior transactions to opening balance
				$openingDr += $priorDr;
				$openingCr += $priorCr;

                //$openingBalance =  ($priorDr - $priorCr);//$account->op_balance +

                /*$openingNet = $priorDr - $priorCr;
                $openingDr = $openingNet >= 0 ? $openingNet : 0;
                $openingCr = $openingNet < 0 ? abs($openingNet) : 0;*/

                // YTD transactions from "from" to "to"
                $ytdTransactions = AccountTransaction::where('account_master_id', $account->id)
                    ->whereBetween('invoice_date', [$from, $to])
                    ->where('status', 1)
                    ->where('voucher_type', '!=', 'OBD')->where('voucher_type', '!=', 'OB')
                    ->get();

                $ytdDr = $ytdTransactions->where('transaction_type', 'Dr')->sum('amount');
                $ytdCr = $ytdTransactions->where('transaction_type', 'Cr')->sum('amount');

                //$closingBalance = $openingBalance + ($ytdDr - $ytdCr);

                $closingNet = $openingDr - $openingCr + ($ytdDr - $ytdCr);

                if ($trimzero==1) {

                    if ($openingDr != 0 || $openingCr != 0 || $ytdDr != 0 || $ytdCr != 0 || $closingNet != 0) {
                        $results[] = [
                            'account_id'       => $account->account_id,
                            'account_name'     => $account->master_name,
                            'group'            => $account->group->name ?? '',
                            'category'         => $account->group->category->name ?? '',
                            'opening_debit'    => $openingDr,
                            'opening_credit'   => $openingCr,
                            'ytd_debit'        => $ytdDr,
                            'ytd_credit'       => $ytdCr,
                            'closing_balance'  => $closingNet,
                        ];
                    }

                } else {

                    $results[] = [
                            'account_id'       => $account->account_id,
                            'account_name'     => $account->master_name,
                            'group'            => $account->group->name ?? '',
                            'category'         => $account->group->category->name ?? '',
                            'opening_debit'    => $openingDr,
                            'opening_credit'   => $openingCr,
                            'ytd_debit'        => $ytdDr,
                            'ytd_credit'       => $ytdCr,
                            'closing_balance'  => $closingNet,
                        ];
                }

            }
            

            if ($request->has('export')) { 
                return $this->exportSearchReport($results, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
            }

            
            return view('body.trialbalance2.report-ytd', compact('results', 'from', 'to', 'search_type','voucherhead','settings', 'trimzero'));
        }
    }


    public function searchReportOld(Request $request)
	{
        $search_type = $request->get('search_type');
        $settings = $this->acsettings;
        $trimzero = $request->get('trim_zero');
        $this->memoryLimit();

        if ($request->get('search_type') == 'opening_summary') {
                $parafrom = $settings->from_date;
                $voucherhead = 'Trial Balance Opening Summary (Groupwise)';
                $from = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y-m-d') : $parafrom;
                $to = date('Y-m-d');

                $accounts = AccountMaster::select(
                    'account_group.name as group_name',
                    'account_transaction.transaction_type',
                    'account_transaction.amount'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->join('account_transaction', 'account_transaction.account_master_id', '=', 'account_master.id')
                ->where('account_transaction.voucher_type', 'OB')
                ->where('account_transaction.status', 1)
                ->whereNull('account_transaction.deleted_at')
                ->whereNull('account_master.deleted_at')
                ->where('account_master.status', 1)
                ->get();

                $grouped = $accounts->groupBy('group_name')->map(function ($group) {
                    return [
                        'debit'  => $group->where('transaction_type', 'Dr')->sum('amount'),
                        'credit' => $group->where('transaction_type', 'Cr')->sum('amount'),
                    ];
                });

                if($trimzero==1) {
                    $grouped = $grouped->filter(function ($item) {
                        return ($item['debit'] != 0 || $item['credit'] != 0);
                    });
                }

                // For export
                if ($request->has('export')) {
                    return $this->exportSearchReport($grouped, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }

                return view('body.trialbalance2.report-opening-summary', compact('grouped', 'from', 'to', 'voucherhead','search_type', 'trimzero'));


        } else if ($request->get('search_type') === 'opening_groupwise') {
                $parafrom   = $settings->from_date;
                $to         = $parato = date('Y-m-d');
                $voucherhead = 'Trial Balance Opening Detail (Groupwise)';

                $from = $request->get('from_date')
                    ? Carbon::parse($request->get('from_date'))->format('Y-m-d')
                    : $parafrom;

                // Build query for account masters with group info
                $query = AccountMaster::select(
                    'account_master.id',
                    'account_master.account_id',
                    'account_master.master_name',
                    'account_group.name as group_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id')
                ->where('account_master.status', 1)
                ->whereNull('account_master.deleted_at');

                // Eager load only opening balance transactions
                $query->with(['transactions' => function ($qry) {
                    $qry->where('voucher_type', 'OB')
                        ->where('status', 1)
                        ->whereNull('deleted_at');
                }]);

                $accounts = $query->get()->map(function ($acc) {
                    $opDr = $acc->transactions->where('transaction_type', 'Dr')->sum('amount');
                    $opCr = $acc->transactions->where('transaction_type', 'Cr')->sum('amount');

                    return [
                        'group_name' => $acc->group_name ?: 'No Group',
                        'account_name' => $acc->account_id.' - '.$acc->master_name,
                        'debit'  => $opDr,
                        'credit' => $opCr,
                    ];
                });

                if($trimzero==1) {
                    $accounts = $accounts->filter(function ($item) {
                        return ($item['debit'] != 0 || $item['credit'] != 0);
                    });
                }

                $grouped = $accounts->groupBy('group_name');

                if ($request->has('export')) {
                    return $this->exportSearchReport($grouped, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }

        return view('body.trialbalance2.report-opening-groupwise', compact('grouped', 'from', 'to', 'voucherhead','search_type', 'trimzero'));


    } else if ($request->get('search_type') == 'closing_summary') {

                $parafrom = $settings->from_date;
                $parato = date('Y-m-d');
                $voucherhead = 'Trial Balance Closing Summary (Groupwise)';

                $from = $request->get('from_date') ? Carbon::parse($request->get('from_date'))->format('Y-m-d') : $parafrom;
                $to   = $request->get('to_date')   ? Carbon::parse($request->get('to_date'))->format('Y-m-d')   : $parato;

                $query = AccountMaster::select(
                    'account_master.id',
                    'account_master.master_name',
                    'account_master.transaction_type as opening_type',
                    'account_master.op_balance as opening_balance',
                    'account_group.name as group_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id');

                if ($from != $parafrom) {
                    $query->with([
                        'prior_transactions' => function ($qry) use ($parafrom, $from) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$parafrom, date('Y-m-d', strtotime($from . ' -1 day'))]);
                        },
                        'current_transactions' => function ($qry) use ($from, $to) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$from, $to]);
                        }
                    ]);
                } else {
                    $query->with([
                        'current_transactions' => function ($qry) use ($from, $to) {
                            $qry->where('status', 1)
                                ->where('voucher_type', '!=', 'OBD')
                                ->where(function ($q) {
                                    $q->whereNull('deleted_at');
                                })
                                ->whereBetween('invoice_date', [$from, $to]);
                        }
                    ]);
                }

                $query->where('account_master.status', 1)
                    ->where(function ($q) {
                        $q->whereNull('account_master.deleted_at');
                    });

                $accounts = $query->get()->map(function ($account) use ($from, $parafrom) {
                    $opDr = 0;
                    $opCr = 0;

                    $priorDr = $priorCr = 0;
                    if ($from != $parafrom) {
                        $priorDr = $account->prior_transactions->where('transaction_type', 'Dr')->sum('amount');
                        $priorCr = $account->prior_transactions->where('transaction_type', 'Cr')->sum('amount');
                    }

                    $currDr = $account->current_transactions->where('transaction_type', 'Dr')->sum('amount');
                    $currCr = $account->current_transactions->where('transaction_type', 'Cr')->sum('amount');

                    $net = ($opDr + $priorDr + $currDr) - ($opCr + $priorCr + $currCr);

                    return [
                        'group_name' => $account->group_name ?: 'No Group',
                        'debit' => $net >= 0 ? $net : 0,
                        'credit' => $net < 0 ? abs($net) : 0,
                    ];
                });

                // Group by and summarize
                $groupedSummary = $accounts->groupBy('group_name')->map(function ($group) {
                    return [
                        'debit' => $group->sum('debit'),
                        'credit' => $group->sum('credit'),
                    ];
                });

                if ($trimzero==1) {
                    $groupedSummary = $groupedSummary->filter(function ($amounts) {
                        return $amounts['debit'] != 0 || $amounts['credit'] != 0;
                    });
                }

                if ($request->has('export')) {
                    return $this->exportSearchReport($groupedSummary, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
                }

                return view('body.trialbalance2.report-closing-summary', compact('groupedSummary', 'from', 'to', 'voucherhead', 'search_type', 'trimzero'));


        } elseif($request->get('search_type')=='closing_groupwise') {

            $parafrom = $settings->from_date; $parato = date('Y-m-d');
            $voucherhead = 'Trial Balance Closing Detail(Groupwise)';

            if($request->get('from_date')=='') {
                $from = $parafrom;
            } else
                $from = Carbon::parse($request->get('from_date'))->format('Y-m-d');

            if($request->get('to_date')=='') {
                $to = $parato;
            } else
                $to = Carbon::parse($request->get('to_date'))->format('Y-m-d'); 

            $query = AccountMaster::select(
                    'account_master.account_id',
                    'account_master.id',
                    'account_master.master_name',
                    'account_master.transaction_type as opening_type',
                    'account_master.op_balance as opening_balance',
                    'account_group.name as group_name',
                    'account_category.name as category_name'
                )
                ->leftJoin('account_group', 'account_group.id', '=', 'account_master.account_group_id')
                ->leftJoin('account_category', 'account_category.id', '=', 'account_group.category_id');

                if($request->get('group_id')!='') {
                    $grparr = explode(',', $request->get('group_id'));
                    $query->whereIn('account_master.account_group_id', $grparr);
                }
            
                if($from != $parafrom) { 
                    $query->with([
                            // Transactions before current month (for adjusted opening)
                            'prior_transactions' => function ($qry) use ($parafrom, $from) { 
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at'); //->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$parafrom, date('Y-m-d',strtotime($from .' - 1 day')) ]); 
                            },
                            // Transactions in the selected month
                            'current_transactions' => function ($qry) use ($from, $to) {
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at'); //->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$from, $to]);
                            }
                        ]);
                } else {

                    $query->with([
                            // Transactions in the selected month
                            'current_transactions' => function ($qry) use ($from, $to) {
                                $qry->where('status', 1)
                                    ->where('voucher_type', '!=', 'OBD')
                                    ->where(function ($q) {
                                        $q->whereNull('deleted_at');//->orWhere('deleted_at', '0000-00-00 00:00:00');
                                    })
                                    ->whereBetween('invoice_date', [$from, $to]);
                            }
                        ]);
                }

            $query->where('account_master.status', 1)
                ->where(function ($q) {
                    $q->whereNull('account_master.deleted_at');//->orWhere('account_master.deleted_at', '0000-00-00 00:00:00');
                });

                $accounts = $query->get()
                    ->map(function ($account) use ($from, $parafrom) {
                        // Opening
                        $opDr = 0;//$account->opening_type === 'Dr' ? $account->opening_balance : 0;
                        $opCr = 0;//$account->opening_type === 'Cr' ? $account->opening_balance : 0;

                        // Transactions before the month
                        if($from != $parafrom) {
                            $priorDr = $account->prior_transactions->where('transaction_type', 'Dr')->sum('amount');
                            $priorCr = $account->prior_transactions->where('transaction_type', 'Cr')->sum('amount');
                        } else {
                            $priorDr = $priorCr = 0;
                        }
                        // Current month transactions
                        $currDr = $account->current_transactions->where('transaction_type', 'Dr')->sum('amount');
                        $currCr = $account->current_transactions->where('transaction_type', 'Cr')->sum('amount');

                        // Net Balance calculation
                        $net = ($opDr + $priorDr + $currDr) - ($opCr + $priorCr + $currCr);

                        return [
                            'account_id'   => $account->account_id,
                            'account_name'   => $account->master_name,
                            'group_name'     => $account->group_name,
                            'category_name'  => $account->category_name,
                            'current_debit'  => $currDr,
                            'current_credit' => $currCr,
                            'debit'  => $net >= 0 ? $net : 0,
                            'credit' => $net < 0 ? abs($net) : 0,
                        ];
                });

                if ($trimzero==1) {
                    $accounts = $accounts->filter(function ($acc) {
                        return ($acc['debit'] != 0 || $acc['credit'] != 0);
                    })
                    ->values();
                }

                

            //echo '<pre>';print_r($accounts);exit;

            $groupedAccounts = $accounts->groupBy('group_name');

            if ($request->has('export')) { 
                return $this->exportSearchReport($groupedAccounts, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
            }

            return view('body.trialbalance2.report-closing-groupwise', compact('groupedAccounts', 'from', 'to', 'voucherhead', 'search_type', 'trimzero'));

        } else if($request->get('search_type')=='YTD') {

            $voucherhead = 'Trial Balance YTD';
            //$from = Carbon::parse($request->get('from_date'))->format('Y-m-d');
            //$to = Carbon::parse($request->get('to_date'))->format('Y-m-d');
            
            //..........
            $parafrom = $settings->from_date; $parato = date('Y-m-d');

            if($request->get('from_date')=='') {
                $from = $parafrom;
            } else
                $from = Carbon::parse($request->get('from_date'))->format('Y-m-d');

            if($request->get('to_date')=='') {
                $to = $parato;
            } else
                $to = Carbon::parse($request->get('to_date'))->format('Y-m-d'); 

            //....................

            $accounts = AccountMaster::with(['group.category'])->where('status', 1)->get();
            $results = [];

            foreach ($accounts as $account) {
                // Opening balance + transactions before "from" date
                $priorTransactions = AccountTransaction::where('account_master_id', $account->id)
                    ->where('invoice_date', '<', $from)
                    ->where('status', 1)
                    ->where('voucher_type', '!=', 'OBD')
                    ->get();

                $priorDr = $priorTransactions->where('transaction_type', 'Dr')->sum('amount');
                $priorCr = $priorTransactions->where('transaction_type', 'Cr')->sum('amount');

                //$openingBalance =  ($priorDr - $priorCr);//$account->op_balance +

                $openingNet = $priorDr - $priorCr;
                $openingDr = $openingNet >= 0 ? $openingNet : 0;
                $openingCr = $openingNet < 0 ? abs($openingNet) : 0;

                // YTD transactions from "from" to "to"
                $ytdTransactions = AccountTransaction::where('account_master_id', $account->id)
                    ->whereBetween('invoice_date', [$from, $to])
                    ->where('status', 1)
                    ->where('voucher_type', '!=', 'OBD')
                    ->get();

                $ytdDr = $ytdTransactions->where('transaction_type', 'Dr')->sum('amount');
                $ytdCr = $ytdTransactions->where('transaction_type', 'Cr')->sum('amount');

                //$closingBalance = $openingBalance + ($ytdDr - $ytdCr);

                $closingNet = $openingDr - $openingCr + ($ytdDr - $ytdCr);

                if ($trimzero==1) {

                    if ($openingDr != 0 || $openingCr != 0 || $ytdDr != 0 || $ytdCr != 0 || $closingNet != 0) {
                        $results[] = [
                            'account_id'       => $account->account_id,
                            'account_name'     => $account->master_name,
                            'group'            => $account->group->name ?? '',
                            'category'         => $account->group->category->name ?? '',
                            'opening_debit'    => $openingDr,
                            'opening_credit'   => $openingCr,
                            'ytd_debit'        => $ytdDr,
                            'ytd_credit'       => $ytdCr,
                            'closing_balance'  => $closingNet,
                        ];
                    }

                } else {

                    $results[] = [
                            'account_id'       => $account->account_id,
                            'account_name'     => $account->master_name,
                            'group'            => $account->group->name ?? '',
                            'category'         => $account->group->category->name ?? '',
                            'opening_debit'    => $openingDr,
                            'opening_credit'   => $openingCr,
                            'ytd_debit'        => $ytdDr,
                            'ytd_credit'       => $ytdCr,
                            'closing_balance'  => $closingNet,
                        ];
                }

            }
            

            if ($request->has('export')) { 
                return $this->exportSearchReport($results, $from, $to, $search_type, $voucherhead, $settings, $trimzero);
            }

            
            return view('body.trialbalance2.report-ytd', compact('results', 'from', 'to', 'search_type','voucherhead','settings', 'trimzero'));
        }
    }


    public function exportSearchReport($results, $from, $to, $search_type, $voucherhead, $settings, $trimzero)
    {   
        if($search_type=='opening_summary') { 

            $fileName = 'Opening_TrialBalance_Summary_' . date('Ymd_His');

            return \Excel::create($fileName, function ($excel) use ($results, $from, $voucherhead, $trimzero) {
                $excel->sheet('Opening Summary', function ($sheet) use ($results, $from, $voucherhead, $trimzero) {
                    $row = 1;

                    $sheet->row($row++, [$voucherhead]);
                    $sheet->row($row++, ['As on', Carbon::parse($from)->format('d-m-Y')]);
                    $sheet->row($row++, []);

                    $sheet->row($row++, ['Group Name', 'Debit', 'Credit']);
                    
                    if($trimzero===1) {
                        $filteredResults = collect($results)->filter(function ($amounts) {
                            return ($amounts['debit'] != 0 || $amounts['credit'] != 0);
                        });
                    } else
                        $filteredResults = $results;

                    $grandDr = $grandCr = 0;

                    foreach ($filteredResults as $group => $amounts) {
                        $sheet->row($row++, [
                            $group,
                            number_format($amounts['debit'], 2),
                            number_format($amounts['credit'], 2),
                        ]);

                        $grandDr += $amounts['debit'];
                        $grandCr += $amounts['credit'];
                    }

                    $sheet->row($row++, []);
                    $sheet->row($row++, ['Grand Total', number_format($grandDr, 2), number_format($grandCr, 2)]);
                });
            })->download('xls');

        
        } else if($search_type=='opening_groupwise') {

            $fileName = 'Opening_TrialBalance_Groupwise_' . date('Ymd_His');

                return Excel::create($fileName, function($excel) use ($results, $from, $to, $voucherhead, $trimzero) {
                    $excel->sheet('Opening Groupwise', function($sheet) use ($results, $from, $to, $voucherhead, $trimzero) {
                        $row = 1;

                        $sheet->row($row++, [$voucherhead]);
                        $sheet->row($row++, ['As on:', Carbon::parse($from)->format('d-m-Y')]);
                        $sheet->row($row++, []);

                        $sheet->row($row++, ['Account Name', 'Debit', 'Credit']);

                        if($trimzero===1) {
                            $filteredResults = collect($results)->filter(function ($amounts) {
                                return ($amounts['debit'] != 0 || $amounts['credit'] != 0);
                            });
                        } else
                            $filteredResults = $results;

                        $grandDebit = $grandCredit = 0;

                        foreach ($filteredResults as $group => $accounts) {
                            $sheet->row($row++, [$group]);

                            $groupDebit = $groupCredit = 0;

                            foreach ($accounts as $acct) {
                                $sheet->row($row++, [
                                    $acct['account_name'],
                                    number_format($acct['debit'], 2),
                                    number_format($acct['credit'], 2),
                                ]);
                                $groupDebit += $acct['debit'];
                                $groupCredit += $acct['credit'];
                            }

                            $sheet->row($row++, [
                                'Group Total',
                                number_format($groupDebit, 2),
                                number_format($groupCredit, 2),
                            ]);
                            $sheet->row($row++, []);

                            $grandDebit += $groupDebit;
                            $grandCredit += $groupCredit;
                        }

                        $sheet->row($row++, [
                            'Grand Total',
                            number_format($grandDebit, 2),
                            number_format($grandCredit, 2),
                        ]);
                    });
                })->download('xls');


        } else if($search_type=='closing_summary') {

                $fileName = 'TrialBalance_Groupwise_Summary_' . date('Ymd_His');
                return Excel::create($fileName, function ($excel) use ($results, $from, $to, $voucherhead, $trimzero) {
                        $excel->sheet('Groupwise Summary', function ($sheet) use ($results, $from, $to, $voucherhead, $trimzero) {
                            $row = 1;

                            // Title & Date Info
                            $sheet->row($row++, [$voucherhead]);
                            $sheet->row($row++, ['From Date:', Carbon::parse($from)->format('d-m-Y'), 'To Date:', Carbon::parse($to)->format('d-m-Y')]);
                            $sheet->row($row++, []);

                            // Header
                            $sheet->row($row++, ['Group Name', 'Net Debit', 'Net Credit']);
                            
                            if($trimzero===1) {
                                $filteredResults = collect($results)->filter(function ($amounts) {
                                    return ($amounts['debit'] != 0 || $amounts['credit'] != 0);
                                });
                            } else
                                $filteredResults = $results;

                            $grandDebit = 0;
                            $grandCredit = 0;

                            foreach ($filteredResults as $groupName => $summary) {
                                $sheet->row($row++, [
                                    $groupName,
                                    number_format($summary['debit'], 2),
                                    number_format($summary['credit'], 2),
                                ]);

                                $grandDebit += $summary['debit'];
                                $grandCredit += $summary['credit'];
                            }

                            // Grand Total
                            $sheet->row($row++, []);
                            $sheet->row($row++, [
                                'Grand Total',
                                number_format($grandDebit, 2),
                                number_format($grandCredit, 2),
                            ]);
                        });
                    })->download('xls');

    
        } else if($search_type=='closing_groupwise') {

                $fileName = 'TrialBalance_Groupwise_' . date('Ymd_His');

                return Excel::create($fileName, function($excel) use ($results, $from, $to, $voucherhead, $trimzero) {
                    $excel->sheet('Trial Balance', function($sheet) use ($results, $from, $to, $voucherhead, $trimzero) {
                        $row = 1;

                        // Report title
                        $sheet->row($row++, [$voucherhead]);
                        $sheet->row($row++, ['Date From:', Carbon::parse($from)->format('d-m-Y'), 'Date To:', Carbon::parse($to)->format('d-m-Y')]);
                        $sheet->row($row++, []); // Empty row

                        // Table header
                        $sheet->row($row++, [
                            'Account Name', 
                            ' Debit', 
                            ' Credit'
                        ]);

                        if($trimzero===1) {
                            $filteredResults = collect($results)->filter(function ($amounts) {
                                return ($amounts['debit'] != 0 || $amounts['credit'] != 0);
                            });
                        } else
                            $filteredResults = $results;

                        $grandDebit = 0;
                        $grandCredit = 0;

                        foreach ($filteredResults as $groupName => $accounts) {
                            // Group heading
                            $sheet->row($row++, [$groupName]);

                            $groupDebit = 0;
                            $groupCredit = 0;

                            foreach ($accounts as $account) {
                                $sheet->row($row++, [
                                    $account['account_id'].' - '.$account['account_name'],
                                    number_format($account['debit'], 2),
                                    number_format($account['credit'], 2),
                                ]);

                                $groupDebit += $account['debit'];
                                $groupCredit += $account['credit'];
                            }

                            // Group subtotal row
                            $sheet->row($row++, [
                                'Group Total', 
                                number_format($groupDebit, 2),
                                number_format($groupCredit, 2),
                            ]);

                            $sheet->row($row++, []); // Empty line between groups

                            $grandDebit += $groupDebit;
                            $grandCredit += $groupCredit;
                        }

                        // Grand Total row
                        $sheet->row($row++, [
                            'Grand Total', 
                            number_format($grandDebit, 2),
                            number_format($grandCredit, 2),
                        ]);
                    });
                })->download('xls');

        } else if($search_type=='YTD') {
            
            $exportFileName = 'TrialBalance_YTD_' . date('Ymd_His') . '.xls';
        
            return Excel::create($exportFileName, function($excel) use ($results, $from, $to, $voucherhead, $trimzero) {
                $excel->sheet('Trial Balance YTD', function($sheet) use ($results, $voucherhead, $trimzero) {
                    $rowIndex = 1;
        
                    // Report title
                    $sheet->row($rowIndex++, [$voucherhead]);
                    $sheet->row($rowIndex++, ['']);
        
                    // Header
                    $sheet->row($rowIndex++, [
                        'Account ID', 
                        'Account Name', 
                        'Opening Debit', 
                        'Opening Credit', 
                        'Debit', 
                        'Credit', 
                        'Closing Balance'
                    ]);
        
                    $grouped = collect($results)->groupBy('group');
        
                    foreach ($grouped as $groupName => $accounts) {
                        // Group Header
                        $sheet->row($rowIndex++, [$groupName ?: 'No Group']);
        
                        // Group rows
                        foreach ($accounts as $account) {
                            $sheet->row($rowIndex++, [
                                $account['account_id'],
                                $account['account_name'],
                                number_format($account['opening_debit'], 2),
                                number_format($account['opening_credit'], 2),
                                number_format($account['ytd_debit'], 2),
                                number_format($account['ytd_credit'], 2),
                                number_format($account['closing_balance'], 2),
                            ]);
                        }
        
                        // Subtotals per group
                        $sheet->row($rowIndex++, [
                            '', 'Group Total',
                            number_format($accounts->sum('opening_debit'), 2),
                            number_format($accounts->sum('opening_credit'), 2),
                            number_format($accounts->sum('ytd_debit'), 2),
                            number_format($accounts->sum('ytd_credit'), 2),
                            number_format($accounts->sum('closing_balance'), 2),
                        ]);
        
                        // Blank line between groups
                        $sheet->row($rowIndex++, ['']);
                    }
        
                    // Grand totals
                    $resultsCollection = collect($results);
                    $sheet->row($rowIndex++, [
                        '', 'Grand Total',
                        number_format($resultsCollection->sum('opening_debit'), 2),
                        number_format($resultsCollection->sum('opening_credit'), 2),
                        number_format($resultsCollection->sum('ytd_debit'), 2),
                        number_format($resultsCollection->sum('ytd_credit'), 2),
                        number_format($resultsCollection->sum('closing_balance'), 2),
                    ]);
                });
            })->download('xls');
            
        }
    }



    
}
