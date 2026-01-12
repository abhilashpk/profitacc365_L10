<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\Accategory\AccategoryInterface;

use App\Http\Requests;
use Notification;
use Input;
use Session;
use DB;
use App;

class DailySettingController extends Controller
{
   
	protected $acgroup;

	public function __construct(AccategoryInterface $accategory, AcgroupInterface $acgroup) {

		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->acgroup = $acgroup;
		$this->accategory = $accategory;

	}
	public function index() { 
		$data = array();
		$forms =$this->acgroup->acgroupsetting();
		$accounts = $this->sortByGroupId( DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','account_group_id','master_name')->get());
		$groupdata = DB::table('daily_report_setting')->first();
		$groups = ($groupdata->group_ids!='')?unserialize($groupdata->group_ids):null;
		$accountdat = ($groupdata->account_ids!='')?unserialize($groupdata->account_ids):null;
		//echo '<pre>';print_r($forms);print_r($accounts);exit;
		return view('body.dailysetting.index')
					->withForms($forms)
					->withGroups($groups)
					->withAccounts($accounts)
					->withAccountdat($accountdat)
					->withData($data);
	}
	
	private function sortByGroupId($result) {
		$childs = array();
		foreach($result as $item)
			$childs[$item->account_group_id][] = $item;
		
		return $childs;
	}
	
	public function update(Request $request) { 
		//echo '<pre>';print_r($request->all());exit;
		DB::table('daily_report_setting')->where('id',1)->update(['group_ids' => serialize($request->get('dailyid')), 'account_ids' => serialize($request->get('acntid'))]);
		Session::flash('message', 'Daily report  settings updated successfully.');
		return redirect('daily_report_setting');
		
	}
	
	public function detail() { 
		$data = array();
	
		$forms =	DB::table('daily_settings')->where('active',1)->get();
		return view('body.dailysetting.detail')
					->withForms($forms)
					->withData($data);
	}
	
	
}
