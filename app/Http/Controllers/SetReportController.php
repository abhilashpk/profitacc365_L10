<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests;
use Notification;
use Session;
use DB;
use App;

class SetReportController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');

	}

	public function index()
	{  
		$reports = DB::table('report_view')->where('status',1)->get(); //
		//echo '<pre>';print_r($reports);
		//echo '<pre>';print_r($reports[0]->report_name);exit;
		
		return view('body.setreport.index')
					->withReports($reports);
	}
	
	public function update(Request $request) {
		
		if($request->get('id')!='') {
			if($request->get('opt')==1)
				DB::table('report_view_detail')->where('id', $request->get('id'))->update(['is_default' => 0]);
			
			DB::table('report_view_detail')->where('id', $request->get('id'))
						->update([ 'name' => $request->get('name'),
								   'print_name' => $request->get('file'),
								   'is_default' => $request->get('opt')
								 ]);
		} else { 
			DB::table('report_view_detail')
						->insert([ 'report_view_id' => $request->get('rid'),
								   'name' => $request->get('name'),
								   'print_name' => $request->get('file'),
								   'is_default' => $request->get('opt')
								 ]);
		}
	}
	
	public function assignPrint($id)
	{  
		$reports = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view_detail.report_view_id',$id)
							->select('report_view.name AS report_name','report_view_detail.name','report_view_detail.print_name',
									 'report_view.id','report_view_detail.is_default','report_view_detail.id AS rid')
							->get(); //
							//echo '<pre>';print_r($reports);exit;
							
		$files = Storage::disk('reports')->files();
		
		return view('body.setreport.detail')
					->withReports($reports)
					->withFiles($files);
	}
	
	public function delete($id) {
		
		DB::table('report_view_detail')->where('id',$id)->delete();
	}
	
	public function save($id) {
		$rec = DB::table('report_view_detail')->where('id',$id)->first();
		DB::table('design_view')->where('id',1)->update(['view_name' => $rec->print_name]);
	}
	
	public function getInfoTemplate($code) {
		
		$items = DB::table('info_template')->where('deleted_at',null)->where('doc_type',$code)->select('*')->get();
		//echo '<pre>';print_r($items);exit;
		return view('body.setreport.infotemplate')->withItems($items);
	}
	
}


//SELECT account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,receipt_voucher.voucher_no,receipt_voucher.voucher_date,receipt_voucher.tr_description,jobmaster.code AS jobode,receipt_voucher_entry.amount FROM receipt_voucher JOIN ON(receipt_voucher_entry.receipt_voucher_id=receipt_voucher.id AND receipt_voucher_entry.entry_type='Cr') JOIN ON(account_master.id=receipt_voucher_entry.account_id) LEFT JOIN ON(jobmaster.id=receipt_voucher_entry.job_id) WHERE receipt_voucher.id=
