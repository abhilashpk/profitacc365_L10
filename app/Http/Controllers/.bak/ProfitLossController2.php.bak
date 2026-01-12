<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use App;
use DB;
use Excel;
use Auth;

class ProfitLossController2 extends Controller
{
   
	protected $accountmaster;
	protected $itemmaster;
	protected $option;

	public function __construct(AccountMasterInterface $accountmaster, ItemmasterInterface $itemmaster) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->accountmaster = $accountmaster;
		$this->itemmaster = $itemmaster;
		$this->middleware('auth');
		$this->option = DB::table('parameter2')->where('keyname', 'mod_opcl')->where('status',1)->select('is_active')->first();

	}
	
	public function index() {
		$data = array();
		$acmasters = [];//$this->accountmaster->accountMasterList();
		
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
		
		return view('body.profitloss2.index')
					->withAcmasters($acmasters)
					->withDepartments($departments)
					->withIsdept($is_dept)
					->withOptionsrch(($this->option->is_active==1)?true:false)
					->withSettings($this->acsettings)
					->withData($data);
	}


	public function profitLoss(Request $request)
	{
		$startDate = ($request->input('date_from')!='')?date('Y-m-d', strtotime($request->input('date_from'))):''; 
		$endDate = ($request->input('date_to')!='')?date('Y-m-d', strtotime($request->input('date_to'))):'';
		$reportType = $request->input('search_type');
	if (!$startDate || !$endDate) {
			$startDate   = $this->acsettings->from_date;
            $endDate     = $this->acsettings->to_date;
		}
//echo $startDate;exit;
		$reportData = [
			'expense' => [],
			'income' => [],
		];

		$categories = DB::table('account_category')
			//->where('actype', 2)
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

			 $parentName = $this->getParentName($category->id);

			// Group data under this parent name
			if (!isset($reportData[$side][$parentName])) {
				$reportData[$side][$parentName] = [];
			}

			// Get groups under this category
			$groups = DB::table('account_group')
				->where('category_id', $category->id)
				->where('deleted_at', '0000-00-00 00:00:00')
				->get();

			foreach ($groups as $group) {
				if (!isset($reportData[$side][$parentName][$group->name])) {
					$reportData[$side][$parentName][$group->name] = [];
				}

				// Get accounts under this group
				$accounts = DB::table('account_master')
					->where('account_group_id', $group->id)
					->where('deleted_at', '0000-00-00 00:00:00')
					->get();

				foreach ($accounts as $account) {
					// Sum transaction amount
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

					if ($amount != 0) {
						$reportData[$side][$parentName][$group->name][$account->master_name] = $amount;
					}
				}
			}
		}
			$voucherhead = ($reportType=='summary')?'Profit & Loss Summary':'Profit & Loss Detail';

			// For export
            if ($request->has('export')) { 
                return $this->exportSearchReport($reportData, $startDate, $endDate, $reportType, $voucherhead);
            }

		
		return view('body.profitloss2.'.$reportType, compact('reportData', 'startDate', 'endDate', 'reportType','voucherhead'));
	}

	private function getParentName($parentId)
	{
		$parent = DB::table('account_category')->where('id', $parentId)->first();
		return $parent ? $parent->name : 'Unknown';
	}


	public function exportSearchReport($reportData, $startDate, $endDate, $reportType, $voucherhead)
	{
		Excel::create('Profit_Loss_Report_' . date('Ymd_His'), function ($excel) use ($reportData, $startDate, $endDate, $reportType, $voucherhead) {
			$excel->sheet('Profit & Loss', function ($sheet) use ($reportData, $startDate, $endDate, $reportType, $voucherhead) {
				$row = 1;

				// Report Header
				$sheet->row($row++, ['Profit & Loss Report']);
				$sheet->row($row++, ['From: ' . date('d-m-Y', strtotime($startDate)), '', 'To: ' . date('d-m-Y', strtotime($endDate))]);
				$sheet->row($row++, [$voucherhead]);

				$row++;

				// Table Headers
				$sheet->row($row++, ['Expenses', 'Amount', 'Income', 'Amount']);

				if ($reportType === 'summary') {
					// Summary Mode
					$buildSummaryRows = function ($data) {
						$rows = [];
						$total = 0;

						foreach ($data as $parent => $groups) {
							foreach ($groups as $group => $accounts) {
								$groupTotal = 0;

								foreach ($accounts as $acc => $amt) {
									if (floatval($amt) != 0) {
										$groupTotal += $amt;
									}
								}

								if ($groupTotal != 0) {
									$rows[] = ['label' => $group, 'amount' => number_format($groupTotal, 2)];
									$total += $groupTotal;
								}
							}
						}

						return ['rows' => $rows, 'total' => $total];
					};

					$exp = $buildSummaryRows($reportData['expense']);
					$inc = $buildSummaryRows($reportData['income']);

					$max = max(count($exp['rows']), count($inc['rows']));

					for ($i = 0; $i < $max; $i++) {
						$e = isset($exp['rows'][$i]) ? $exp['rows'][$i] : ['label' => '', 'amount' => ''];
						$in = isset($inc['rows'][$i]) ? $inc['rows'][$i] : ['label' => '', 'amount' => ''];
						$sheet->row($row++, [$e['label'], $e['amount'], $in['label'], $in['amount']]);
					}

					// Totals
					$sheet->row($row++, ['Total Expense', number_format($exp['total'], 2), 'Total Income', number_format($inc['total'], 2)]);

					$net = $inc['total'] - $exp['total'];
					$sheet->row($row++, ['Net ' . ($net >= 0 ? 'Profit' : 'Loss'), '', '', number_format(abs($net), 2)]);

				} else {
					// Detail Mode
					$buildDetailRows = function ($data) {
						$rows = [];
						$total = 0;

						foreach ($data as $parent => $groups) {
							$rows[] = ['label' => $parent, 'amount' => '', 'type' => 'header'];

							foreach ($groups as $group => $accounts) {
								$groupTotal = 0;
								$accountRows = [];

								foreach ($accounts as $account => $amount) {
									if (floatval($amount) != 0) {
										$accountRows[] = [
											'label' => '    ' . $account,
											'amount' => number_format($amount, 2),
											'type' => 'account'
										];
										$groupTotal += $amount;
									}
								}

								if ($groupTotal != 0) {
									$rows[] = [
										'label' => '  ' . $group,
										'amount' => number_format($groupTotal, 2),
										'type' => 'group'
									];
									foreach ($accountRows as $accRow) {
										$rows[] = $accRow;
									}

									$total += $groupTotal;
								}
							}
						}

						return ['rows' => $rows, 'total' => $total];
					};

					$exp = $buildDetailRows($reportData['expense']);
					$inc = $buildDetailRows($reportData['income']);

					$max = max(count($exp['rows']), count($inc['rows']));

					for ($i = 0; $i < $max; $i++) {
						$e = isset($exp['rows'][$i]) ? $exp['rows'][$i] : ['label' => '', 'amount' => ''];
						$in = isset($inc['rows'][$i]) ? $inc['rows'][$i] : ['label' => '', 'amount' => ''];
						$sheet->row($row++, [$e['label'], $e['amount'], $in['label'], $in['amount']]);
					}

					$sheet->row($row++, ['Total Expense', number_format($exp['total'], 2), 'Total Income', number_format($inc['total'], 2)]);

					$net = $inc['total'] - $exp['total'];
					$sheet->row($row++, ['Net ' . ($net >= 0 ? 'Profit' : 'Loss'), '', '', number_format(abs($net), 2)]);
				}

				// Optional: auto size columns
				$sheet->setAutoSize(true);
			});
		})->export('xlsx');
	}


	//,-------------------------------------------------
	

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
	
			
}