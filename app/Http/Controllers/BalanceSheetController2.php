<?php
namespace App\Http\Controllers;
use App\Repositories\AccountMaster\AccountMasterInterface;

use Illuminate\Http\Request;
use App\Models\Accategory;
use App\Models\Acgroup;
use App\Models\AccountTransaction;
use App\Models\AccountMaster;
use DB;
use Carbon\Carbon;
use Excel; // Assuming Laravel Excel is installed

use Notification;
use Session;
use App;
use Auth;

class BalanceSheetController2 extends Controller
{

    protected $accountmaster;
	protected $option;

	public function __construct(AccountMasterInterface $accountmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->middleware('auth');
		$this->option = DB::table('parameter2')->where('keyname', 'mod_opcl')->where('status',1)->select('is_active')->first();
	}

    public function index() {
		
		$data = array();
		$acmasters = [];
		
		DB::statement('DELETE t1 FROM account_transaction t1, account_transaction t2 WHERE  t1.id > t2.id AND (t1.voucher_type = t2.voucher_type AND t1.voucher_type_id = t2.voucher_type_id AND t1.account_master_id = t2.account_master_id AND t1.transaction_type = t2.transaction_type AND t1.amount = t2.amount AND t1.amount = t2.amount AND t1.reference = t2.reference AND t1.reference_from = t2.reference_from AND t1.reference_from = t2.reference_from AND t1.other_info = t2.other_info AND t1.status = t2.status AND t1.deleted_at = t2.deleted_at)');
			
		$this->makeSummaryAc($this->makeTree( $this->accountmaster->updateUtility('CB')) );
		
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
		
		return view('body.balancesheet2.index')
					->withAcmasters($acmasters)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withSettings($this->acsettings)
					->withData($data);
	}

    protected function makeSummaryAc($results)
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


	protected function makeTree($result)
	{
		$childs = array();
		foreach($result as $item)
			$childs[$item->id][] = $item;

		return $childs;
	}
    
   private function calculateAccountBalance($accountId, $fromDate = null, $toDate = null)
    {
        /*$transactions = AccountTransaction::where('account_master_id', $accountId)
            ->where('status1', 1)
            ->get();*/

            $transactions = AccountTransaction::where(function ($q) {
                                $q->whereNull('deleted_at')
                                ->orWhere('deleted_at', '0000-00-00 00:00:00');
                            })
                            ->where('account_master_id', $accountId)
                            ->where('status', 1)
                            ->where('voucher_type', '!=', 'OBD');

            // Apply date filters if provided
            if ($fromDate && $toDate) {
                $transactions->whereBetween('invoice_date', [$fromDate, $toDate]);
            }

           $transactions = $transactions->get();

//echo '<pre>';print_r($transactions);exit;
        $debit = $transactions->where('transaction_type', 'Dr')->sum('amount');
        $credit = $transactions->where('transaction_type', 'Cr')->sum('amount');

        return $debit - $credit;
    }


    private function calculateProfitLossOLD($fromDate = null, $toDate = null)
    {
        $incomeCategoryIds = Accategory::whereIn('id', [4,5]) //whereIn('name', ['Direct Income', 'Indirect Income'])
            //->where('actype', 2)
            //->where('parent_id', 0)
            ->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('deleted_at')
                ->orWhere('deleted_at', '0000-00-00 00:00:00');
            })
            ->pluck('id')
            ->toArray();

        $expenseCategoryIds = Accategory::whereIn('id', [6,7]) //whereIn('name', ['Direct Expense', 'Indirect Expense'])
            //->where('actype', 2)
            //->where('parent_id', 0)
            ->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('deleted_at')
                ->orWhere('deleted_at', '0000-00-00 00:00:00');
            })
            ->pluck('id')
            ->toArray();

        $income = 0;
        $expense = 0;

        // INCOME CATEGORIES
        foreach ($incomeCategoryIds as $parentId) {
            $subCategories = Accategory::where('parent_id', $parentId)
                ->where('status', 1)
                ->where(function ($q) {
                    $q->whereNull('deleted_at')
                    ->orWhere('deleted_at', '0000-00-00 00:00:00');
                })
                ->get();

            foreach ($subCategories as $subCategory) {
                foreach ($subCategory->groups as $group) {
                    foreach ($group->accounts as $account) {
                        $balance = $this->calculateAccountBalance($account->id, $fromDate, $toDate);
                        // income should be negative balance (i.e., Cr > Dr)
                        $income += $balance < 0 ? abs($balance) : 0;
                    }
                }
            }
        }

        // EXPENSE CATEGORIES
        foreach ($expenseCategoryIds as $parentId) {
            $subCategories = Accategory::where('parent_id', $parentId)
                ->where('status', 1)
                ->where(function ($q) {
                    $q->whereNull('deleted_at')
                    ->orWhere('deleted_at', '0000-00-00 00:00:00');
                })
                ->get();

            foreach ($subCategories as $subCategory) {
                foreach ($subCategory->groups as $group) {
                    foreach ($group->accounts as $account) {
                        $balance = $this->calculateAccountBalance($account->id, $fromDate, $toDate);
                        // expense should be positive balance (i.e., Dr > Cr)
                        $expense += $balance > 0 ? $balance : 0;
                    }
                }
            }
        }

        return $income - $expense; // positive = profit, negative = loss
    }


    private function calculateProfitLoss($fromDate = null, $toDate = null)
    {
        $startDate = $fromDate ?? $this->acsettings->from_date;
        $endDate = $toDate ?? date('Y-m-d');

        $income = 0;
        $expense = 0;

        $categories = DB::table('account_category')
            ->where('status', 1)
            ->where('deleted_at', '0000-00-00 00:00:00')
            ->get();

        foreach ($categories as $category) {
            $side = null;

            if (in_array($category->parent_id, [6, 7])) {
                $side = 'expense';
            } elseif (in_array($category->parent_id, [4, 5])) {
                $side = 'income';
            } else {
                continue;
            }

            // Get groups under this category
            $groups = DB::table('account_group')
                ->where('category_id', $category->id)
                ->where('deleted_at', '0000-00-00 00:00:00')
                ->get();

            foreach ($groups as $group) {
                // Get accounts under this group
                $accounts = DB::table('account_master')
                    ->where('account_group_id', $group->id)
                    ->whereNull('deleted_at')
                    ->get();

                foreach ($accounts as $account) {
                    // Sum transactions for this account
                    $transactions = DB::table('account_transaction')
                        ->where('account_master_id', $account->id)
                        ->whereBetween('invoice_date', [$startDate, $endDate])
                        ->where('deleted_at', '0000-00-00 00:00:00')
                        ->select('transaction_type', DB::raw('SUM(amount) as total'))
                        ->groupBy('transaction_type')
                        ->get();

                    $amount = 0;

                    foreach ($transactions as $txn) {
                        if ($side === 'income') {
                            $amount += ($txn->transaction_type === 'Cr' ? $txn->total : -$txn->total);
                        } else {
                            $amount += ($txn->transaction_type === 'Dr' ? $txn->total : -$txn->total);
                        }
                    }

                    if ($side === 'income') {
                        $income += $amount;
                    } else {
                        $expense += $amount;
                    }
                }
            }
        }

        return $income - $expense; // Positive = profit, Negative = loss
    }



    public function report(Request $request)
    {
        //$mindate = DB::table('account_transaction')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->min('invoice_date'); 
        $fromDate = ($request->get('date_from')=='')?$this->acsettings->from_date:Carbon::parse($request->get('date_from'))->format('Y-m-d'); //$this->acsettings->from_date
        $toDate = ($request->get('date_to')=='')?$this->acsettings->to_date:Carbon::parse($request->get('date_to'))->format('Y-m-d'); //$request->get('date_to');
        $searchtype = $request->get('search_type');

        if($request->get('search_type')=='summary') {

            $assets = [];
            $liabilitiesAndEquity = [];

            $netProfit = $this->calculateProfitLoss($fromDate, $toDate);

            $topCategories = Accategory::where('actype', 1)
                ->where('parent_id', 0)
                ->where('status', 1)
                ->get();

            foreach ($topCategories as $topCategory) {
                $subCategories = Accategory::where('parent_id', $topCategory->id)
                    ->where('status', 1)
                    ->where(function ($q) {
                        $q->whereNull('deleted_at')
                        ->orWhere('deleted_at', '0000-00-00 00:00:00');
                    })
                    ->get();

                foreach ($subCategories as $subCategory) {
                    $categoryGroups = [];
                    $subCategoryTotal = 0;

                    foreach ($subCategory->groups as $group) {
                        $groupTotal = 0;

                        foreach ($group->accounts as $account) {
                            $balance = $this->calculateAccountBalance($account->id, $fromDate, $toDate);
                            if (abs($balance) < 0.0001) continue;

                            $groupTotal += $balance;
                        }

                        if (abs($groupTotal) < 0.0001) continue;

                        $categoryGroups[] = [
                            'group_name' => $group->name,
                            'group_total' => $groupTotal
                        ];
                        $subCategoryTotal += $groupTotal;
                    }

                    if (count($categoryGroups) === 0) continue;

                    $data = [
                        'name' => $subCategory->name,
                        'groups' => $categoryGroups,
                        'total' => $subCategoryTotal
                    ];

                    if (strtolower($topCategory->name) === 'assets') {
                        $assets[] = $data;
                    } else {
                        $liabilitiesAndEquity[] = $data;
                    }
                }
            }

            if (abs($netProfit) >= 0.0001) {
                $plEntry = [
                    'name' => $netProfit >= 0 ? 'Net Profit' : 'Net Loss',
                    'groups' => [],
                    'total' => abs($netProfit)
                ];

                if ($netProfit >= 0) {
                    // Net Profit â†’ Liabilities
                    $liabilitiesAndEquity[] = $plEntry;
                } else {
                    // Net Loss â†’ Assets
                    $assets[] = $plEntry;
                }
            }

            $voucherhead = 'Balance Sheet Summary (Group-wise)';

            // For export
            if ($request->has('export')) { 
                return $this->exportSearchReport($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate, $searchtype);
            }

            return view('body.balancesheet2.summary', compact('assets', 'liabilitiesAndEquity', 'voucherhead', 'fromDate', 'toDate', 'searchtype'));

        } else if($request->get('search_type')=='detail') { 

            $assets = [];
            $liabilitiesAndEquity = [];

            $netProfit = $this->calculateProfitLoss($fromDate, $toDate);

            $topCategories = Accategory::where('actype', 1)
                ->where('parent_id', 0)
                ->where('status', 1)
                ->get();

            foreach ($topCategories as $topCategory) {
                $subCategories = Accategory::where('parent_id', $topCategory->id)
                    ->where('status', 1)
                    ->where(function ($q) {
                        $q->whereNull('deleted_at')
                        ->orWhere('deleted_at', '0000-00-00 00:00:00');
                    })
                    ->get();

                foreach ($subCategories as $subCategory) {
                    $categoryGroups = [];
                    $subCategoryTotal = 0;

                    foreach ($subCategory->groups as $group) {
                        $groupAccounts = [];
                        $groupTotal = 0;

                        // âœ… Loop only non-zero balance accounts
                        foreach ($group->accounts as $account) {
                            $balance = $this->calculateAccountBalance($account->id, $fromDate, $toDate);
                            if (abs($balance) < 0.0001) continue; // â— prevent floating point edge cases

                            $groupAccounts[] = [
                                'name' => $account->master_name,
                                'balance' => $balance
                            ];
                            $groupTotal += $balance;
                        }

                        // âœ… Skip empty groups (no accounts with non-zero balance)
                        if (count($groupAccounts) === 0) continue;

                        $categoryGroups[] = [
                            'group_name' => $group->name,
                            'accounts' => $groupAccounts,
                            'group_total' => $groupTotal
                        ];
                        $subCategoryTotal += $groupTotal;
                    }

                    // âœ… Skip subcategories with no groups
                    if (count($categoryGroups) === 0) continue;

                    $data = [
                        'name' => $subCategory->name,
                        'groups' => $categoryGroups,
                        'total' => $subCategoryTotal
                    ];

                    if (strtolower($topCategory->name) === 'assets') {
                        $assets[] = $data;
                    } else {
                        $liabilitiesAndEquity[] = $data;
                    }
                }
            }

            // âœ… Add Net Profit or Loss under liabilities side
            if (abs($netProfit) >= 0.0001) {
                $plEntry = [
                    'name' => $netProfit >= 0 ? 'Net Profit' : 'Net Loss',
                    'groups' => [],
                    'total' => abs($netProfit)
                ];

                if ($netProfit >= 0) {
                    // Net Profit â†’ Liabilities
                    $liabilitiesAndEquity[] = $plEntry;
                } else {
                    // Net Loss â†’ Assets
                    $assets[] = $plEntry;
                }
            }


            $voucherhead = 'Balancesheet Details';

            // For export
            if ($request->has('export')) {
                return $this->exportSearchReport($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate, $searchtype);
            }
            
            return view('body.balancesheet2.result', compact('assets', 'liabilitiesAndEquity','voucherhead', 'fromDate', 'toDate', 'searchtype'));
        }
        
        
    }


    public function exportSearchReport($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate, $searchtype)
    {
         
        if($searchtype=='summary') {
            $fileName = 'BalanceSheet_' . $fromDate . '_to_' . $toDate;

            Excel::create($fileName, function($excel) use ($assets, $liabilitiesAndEquity, $fromDate, $toDate, $voucherhead) {
                $excel->sheet('Balance Sheet', function($sheet) use ($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate) {
                    $row = 1;

                    // Title
                    $sheet->mergeCells('A1:D1');
                    $sheet->row($row++, [$voucherhead . " (From $fromDate to $toDate)"]);

                    // Headers
                    $sheet->row($row++, [
                        'Liabilities', 'Amount', 'Assets', 'Amount'
                    ]);

                    $maxCategories = max(count($liabilitiesAndEquity), count($assets));

                    for ($i = 0; $i < $maxCategories; $i++) {
                        $liab = $liabilitiesAndEquity[$i] ?? null;
                        $asset = $assets[$i] ?? null;

                        $sheet->row($row++, [
                            $liab ? $liab['name'] : '',
                            $liab ? number_format($liab['total'], 2) : '',
                            $asset ? $asset['name'] : '',
                            $asset ? number_format($asset['total'], 2) : '',
                        ]);

                        $liabGroups = $liab['groups'] ?? [];
                        $assetGroups = $asset['groups'] ?? [];
                        $maxGroups = max(count($liabGroups), count($assetGroups));

                        for ($g = 0; $g < $maxGroups; $g++) {
                            $liabGroup = $liabGroups[$g] ?? null;
                            $assetGroup = $assetGroups[$g] ?? null;

                            $sheet->row($row++, [
                                $liabGroup ? '  - ' . $liabGroup['group_name'] : '',
                                $liabGroup ? number_format($liabGroup['group_total'], 2) : '',
                                $assetGroup ? '  - ' . $assetGroup['group_name'] : '',
                                $assetGroup ? number_format($assetGroup['group_total'], 2) : '',
                            ]);
                        }
                    }

                    // Totals
                    $totalLiab = collect($liabilitiesAndEquity)->sum('total');
                    $totalAsset = collect($assets)->sum('total');

                    $sheet->row($row++, [
                        'Total Liabilities', number_format($totalLiab, 2),
                        'Total Assets', number_format($totalAsset, 2)
                    ]);

                    $sheet->setAutoSize(true);
                });
            })->download('xls');

        } elseif($searchtype=="detail") {

            $fileName = 'BalanceSheetDetail_' . $fromDate . '_to_' . $toDate;

            Excel::create($fileName, function($excel) use ($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate) {
                $excel->sheet('Balance Sheet Detail', function($sheet) use ($assets, $liabilitiesAndEquity, $voucherhead, $fromDate, $toDate) {
                    $row = 1;

                    // Header
                    $sheet->mergeCells('A1:D1');
                    $sheet->row($row++, [$voucherhead . " (From $fromDate to $toDate)"]);

                    $sheet->row($row++, ['Liabilities', 'Amount', 'Assets', 'Amount']);

                    $maxCategories = max(count($liabilitiesAndEquity), count($assets));

                    for ($i = 0; $i < $maxCategories; $i++) {
                        $liab = $liabilitiesAndEquity[$i] ?? null;
                        $asset = $assets[$i] ?? null;

                        // Category row
                        $sheet->row($row++, [
                            $liab['name'] ?? '',
                            isset($liab['total']) ? number_format($liab['total'], 2) : '',
                            $asset['name'] ?? '',
                            isset($asset['total']) ? number_format($asset['total'], 2) : '',
                        ]);

                        // Get groups
                        $liabGroups = array_values(array_filter($liab['groups'] ?? [], function ($group) {
                            return collect($group['accounts'] ?? [])->pluck('balance')->filter()->count() > 0;
                        }));

                        $assetGroups = array_values(array_filter($asset['groups'] ?? [], function ($group) {
                            return collect($group['accounts'] ?? [])->pluck('balance')->filter()->count() > 0;
                        }));

                        $maxGroups = max(count($liabGroups), count($assetGroups));

                        for ($g = 0; $g < $maxGroups; $g++) {
                            $liabGroup = $liabGroups[$g] ?? null;
                            $assetGroup = $assetGroups[$g] ?? null;

                            // Group row
                            $sheet->row($row++, [
                                $liabGroup ? '  - ' . $liabGroup['group_name'] : '',
                                $liabGroup ? number_format($liabGroup['group_total'], 2) : '',
                                $assetGroup ? '  - ' . $assetGroup['group_name'] : '',
                                $assetGroup ? number_format($assetGroup['group_total'], 2) : '',
                            ]);

                            // Accounts under group
                            $liabAccounts = collect($liabGroup['accounts'] ?? [])->filter(function ($acc) {
                                return $acc['balance'] != 0;
                            })->values();

                            $assetAccounts = collect($assetGroup['accounts'] ?? [])->filter(function ($acc) {
                                return $acc['balance'] != 0;
                            })->values();

                            $maxAccounts = max($liabAccounts->count(), $assetAccounts->count());

                            for ($a = 0; $a < $maxAccounts; $a++) {
                                $liabAcc = $liabAccounts[$a] ?? null;
                                $assetAcc = $assetAccounts[$a] ?? null;

                                $sheet->row($row++, [
                                    $liabAcc ? '     * ' . $liabAcc['name'] : '',
                                    $liabAcc ? number_format($liabAcc['balance'], 2) : '',
                                    $assetAcc ? '     * ' . $assetAcc['name'] : '',
                                    $assetAcc ? number_format($assetAcc['balance'], 2) : '',
                                ]);
                            }
                        }
                    }

                    // Totals
                    $sheet->row($row++, [
                        'Total Liabilities',
                        number_format(collect($liabilitiesAndEquity)->sum('total'), 2),
                        'Total Assets',
                        number_format(collect($assets)->sum('total'), 2),
                    ]);

                    $sheet->setAutoSize(true);
                });
            })->download('xls');
        }
    }


    

}

