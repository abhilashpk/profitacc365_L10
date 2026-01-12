<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Journal\JournalInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\VoucherwiseReport\VoucherwiseReportInterface; 

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;
use DateTime;
use Config;

class ContractConnectionController extends Controller
{
    protected $contract_building;
	protected $journal;
	public $objUtility;
	protected $receipt_voucher;
	protected $accountsetting;
	protected $voucherwise_report;
	
	
	public function __construct(JournalInterface $journal,ReceiptVoucherInterface $receipt_voucher,AccountSettingInterface $accountsetting,VoucherwiseReportInterface $voucherwise_report) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$config = Config::get('siteconfig');
		$this->middleware('auth');
		$this->receipt_voucher = $receipt_voucher;
		$this->journal = $journal;
		$this->accountsetting = $accountsetting;
		$this->voucherwise_report = $voucherwise_report;
		
		$this->width = $config['modules']['salesinvoice']['image_size']['width'];
        $this->height = $config['modules']['salesinvoice']['image_size']['height'];
        $this->thumbWidth = $config['modules']['salesinvoice']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['salesinvoice']['thumb_size']['height'];
        $this->imgDir = $config['modules']['salesinvoice']['image_dir'];
	}
	
	public function index() {
	
		$connection = DB::table('contract_connection')->join('account_master AS AM','AM.id','=','contract_connection.customer_id')
		            ->join('buildingmaster AS B','B.id','=','contract_connection.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_connection.flat_id')
					->leftjoin('receipt_voucher AS R','R.sales_invoice_id','=','contract_connection.id')
					->where('contract_connection.deleted_at',NULL)
					->select('contract_connection.*','AM.master_name','F.flat_no AS flat','B.buildingcode','R.id as rv_id','AM.cl_balance')
					->groupBy('contract_connection.id')
					->get();
					//echo '<pre>';print_r($connection);exit;
					
		
		return view('body.contractconnection.index')
					->withConnection($connection);
					
	}
	
	public function add($id=null) { 
		$crow = [];
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',2)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		
		$row = DB::table('contract_connection')->where('status',1)->where('deleted_at',null)->orderBy('id','DESC')->select('id')->first();
		$rowrv = DB::table('receipt_voucher')->where('sales_invoice_id',$row->id)->orderBy('id','DESC')->select('id')->first();
		
		if($id) {
			$tenantenquiry = DB::table('tenant_enquiry')
		                ->join('buildingmaster AS B','B.id','=','tenant_enquiry.building_id')
		                ->join('account_master AS AM','AM.id','=','tenant_enquiry.tenant_id')
						->leftjoin('flat_master AS F','F.id','=','tenant_enquiry.flat_no')
						->select('tenant_enquiry.*','B.buildingcode','F.flat_no as flatno','security_deposit','connection_charge','other_charge_con','AM.cl_balance')
						->where('tenant_enquiry.id',$id)
						->first();
			$flat = DB::table('flat_master')->where('building_id',$tenantenquiry->building_id)->select('flat_no','id')->get();	
			
			return view('body.contractconnection.addtr')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withFlat($flat)
					->withEnquiry($tenantenquiry)
					->withEnqid($id)
					->withRvvoucher($vchrdata);
		}
		
		return view('body.contractconnection.add')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withRvvoucher($vchrdata)
					->withPrint(($row->id)?$row->id:null)
					->withPrintrv(($rowrv->id)?$rowrv->id:null);
	}
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;  
		
		DB::beginTransaction();
		try {
			$ctid = 2;
			$controw = DB::table('contract_type_re')->where('id',$ctid)->select('increment_no')->first();
			$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			
			$conid = DB::table('contract_connection')
						->insertGetId([
							'connection_no' => 'C'.$controw->increment_no,
							'date' => date('Y-m-d'),
							'building_id' => $request->get('building_id'),
							'flat_id' => $request->get('flat_id'),
							'customer_id' => $request->get('customer_id'),
							'grand_total' => $request->get('grand_total'),
							'is_rv' => ($request->get('is_rv')=='')?0:$request->get('is_rv'),
							'created_at' => date('Y-m-d h:i:s'),
							'created_by' => Auth::User()->id,
							'status' => 1,
							'sin_no' => $sirow->voucher_no,
							'rv_amount' => $request->get('rv_amount'),
							'new_reading' => $request->get('new_reading')
						]);
						
			$input = $request->all();
			
			if(isset($input['enqid'])) {
				DB::table('tenant_enquiry')->where('id',$input['enqid'])->update(['status'=>1]);
			}
			
			/* $readid = DB::table('meter_reading')
						->insertGetId([
							'contract_id' => $conid,
							'previous' => $request->get('new_reading'),
							'current' => 0,
							'rate' => 0,
							'cons_unit' => 0,
							'total' => 0,
							'vat' => 0,
							'grand_total' => 0,
							'created_at' => date('Y-m-d h:i:s'),
							'created_by' => Auth::User()->id,
							'sin_no' => 0,
							'from_date' => '0000-00-00',
							'to_date' => '0000-00-00'
						]); */
			
			foreach($input['acname'] as $k => $val) {
				DB::table('contract_transaction')
							->insert([
								'contract_id' => $conid,
								'con_settings_id' => $ctid,
								'account_id' => $input['acid'][$k],
								'amount'	=> $input['acamount'][$k]
							]);
			}
			
			foreach($input['photo_name'] as $pk => $pval) {
				DB::table('contract_attachment')
							->insert([
								'contract_id' => $conid,
								'file_name' => $input['photo_name'][$pk],
								'title'	=> $input['imgdesc'][$pk]
							]);
			}
						
			DB::table('contract_type_re')->where('id',$ctid)->update(['increment_no' => DB::raw('increment_no + 1')]);
				
			//SALES INVOICE NONSTOCK ENTRY.....
			$acname[] = $request->get('customer_account');
			$acid[] = $request->get('customer_id');
			$grparr[] = 'CUSTOMER';
			$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = '';
			$desarr[] = 'C'.$controw->increment_no.'/'.$sirow->voucher_no;
			$refarr[] = 'C'.$controw->increment_no;
			$actypearr[] = 'Dr';
			$lnarr[] = $request->get('grand_total');
			
			$acamount = $request->get('acamount');
			$actax = $request->get('isvat');
			$arracname = $request->get('acname');
			foreach($request->get('acid') as $key => $val) {
				$acname[] = $arracname[$key];
				$acid[] = $val;
				$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
				$refarr[] = 'C'.$controw->increment_no;
				$actypearr[] = 'Cr';
				$lnarr[] = $acamount[$key];
			}
			
			//TAX EXTRY....
			if($actax) {
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				foreach($request->get('acid') as $key => $val) {
					if($actax[$key] > 0) {
						$acname[] = $arracname[$key];
						$acid[] = $vatrow->payment_account;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$desarr[] = $arracname[$key].'/TAX';
						$refarr[] = 'C'.$controw->increment_no;
						$actypearr[] = 'Cr';
						$lnarr[] = ($acamount[$key] * 5)/100;
					}
				}
			}
			
			//INSERT SALES NONSTOCK.... 
			Input::merge(['from_jv' => 1]);
			Input::merge(['voucher' => 18]);
			Input::merge(['voucher_type' => 6]);
			Input::merge(['voucher_date' => date('Y-m-d', strtotime($request->get('date'))) ]);
			Input::merge(['voucher_no' => $sirow->voucher_no]);
			Input::merge(['account_name' => $acname]);
			Input::merge(['account_id' => $acid]);
			Input::merge(['group_id' => $grparr]);
			Input::merge(['sales_invoice_id' => $siarr]);
			Input::merge(['bill_type' => $btarr]);
			Input::merge(['description' => $desarr]);
			Input::merge(['reference' => $refarr]);
			Input::merge(['inv_id' => $invarr]);
			Input::merge(['actual_amount' => $actarr]);
			Input::merge(['account_type' => $actypearr]);
			Input::merge(['line_amount' => $lnarr]);
			Input::merge(['job_id' => $jbarr]);
			Input::merge(['difference' => 0]);
			Input::merge(['curno' => '']);
			Input::merge(['is_prefix' => 0]);
			Input::merge(['debit' => $request->get('grand_total')]);
			Input::merge(['credit' => $request->get('grand_total')]);
			
			$this->journal->createSIN(Input::all());
			//.......END SALESINVOICE NON STOCK ENTRY
			
			
			//RV ENTRY.......
			if( isset($input['is_rv']) && $input['is_rv']==1 ) { 
				$input['voucher_no'] = $sirow->voucher_no;
				$input['description'] = 'C'.$controw->increment_no;
				$this->RVformSet($input, $conid);
				$this->receipt_voucher->create(Input::all());
			}
			
			DB::commit();
			
			Session::flash('message', 'Connection details added successfully.');
			return redirect('contract-connection/add');
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('contract-connection/add')->withErrors($e->getErrors());
		}
	}
	
	private function RVformSet($attributes,$id) {
		
		$ispdc = false; 
		$ar = [1,2]; $rv_amount = 0; $voucherno = '';
		$remrv = (isset($attributes['remove_rv']))?$attributes['remove_rv']:'';
		foreach($ar as $val) {
			if($val==1) {
				foreach($attributes['rv_voucher'] as $rkey => $rval) {
					$acname[] = $attributes['rv_dr_account'][$rkey];
					$acid[] = $attributes['rv_dr_account_id'][$rkey];
					$grparr[] = $attributes['voucher_type'][$rkey];
					if($attributes['voucher_type'][$rkey]=='CASH') {
						$pryarr[] = ''; $vtype = 'CASH'; $pmode[] = 0;
					} else if($attributes['voucher_type'][$rkey]=='PDCR') {
						$ispdc = true; $vtype = 'PDCR';
						$pryarr[] = $attributes['rv_dr_account_id'][$rkey]; $pmode[] = 2;
					} else if($attributes['voucher_type'][$rkey]=='BANK') {
						$ispdc = true; $vtype = 'BANK';
						$pryarr[] = $attributes['rv_dr_account_id'][$rkey]; $pmode[] = 1;
					}
					$siarr[] = $id; $btarr[] = 'SI'; $invarr[] = $id; $actypearr[] = 'Dr';
					$desarr[] = isset($attributes['description'])?$attributes['description']:'';
					$refarr[] = $attributes['voucher_no']; $voucherno .= ($voucherno=='')?$attributes['voucher_no']:','.$attributes['voucher_no'];
					$lnarr[] = $amt = ($attributes['rv_amount']!='' && $attributes['rv_amount'] > 0)?$attributes['rv_amount']:$attributes['grand_total'];
					$bnkarr[] = (isset($attributes['bank_id'][$rkey]))?$attributes['bank_id'][$rkey]:'';
					$chqarr[] = (isset($attributes['cheque_no'][$rkey]))?$attributes['cheque_no'][$rkey]:'';
					$chqdtarr[] = (isset($attributes['cheque_date'][$rkey]) && $attributes['cheque_date'][$rkey]!='')?date('Y-m-d', strtotime($attributes['cheque_date'][$rkey])):'';
					$jearr[] = (isset($attributes['rowid'][$rkey]))?$attributes['rowid'][$rkey]:'';
					$vatamt[] = ''; $jbarr[] = ''; $dptarr[] = ''; $prtnarr[] = ''; $trarr[] = ''; 
					$actarr[] = $attributes['grand_total'];
					$rv_amount += $amt; //$attributes['grand_total'];
				}
			} else {
				//$ispdc = false;
				$acname[] = $attributes['customer_account'];
				$acid[] = $attributes['customer_id'];
				$grparr[] = 'CUSTOMER'; //$vtype = 9;
				$siarr[] = $id; $btarr[] = 'SI'; $invarr[] = $id; $actypearr[] = 'Cr';
				$desarr[] = isset($attributes['description'])?$attributes['description']:'';
				$jearr[] = (isset($attributes['rowidcr'][0]))?$attributes['rowidcr'][0]:'';
				$refarr[] = $voucherno; 
				$lnarr[] = $rv_amount;
				$pryarr[] = ''; $vatamt[] = ''; $jbarr[] = ''; $dptarr[] = ''; $prtnarr[] = ''; $trarr[] = ''; $pmode[] = '';
				$bnkarr[] = ''; $chqarr[] = ''; $chqdtarr[] = '';
				$actarr[] = $attributes['grand_total'];
			}
		}
		
		Input::merge(['from_jv' => 1]);
		Input::merge(['chktype' => ($ispdc)?'PDCR':'']);
		Input::merge(['is_onaccount' => 1]);
		Input::merge(['voucher' => $attributes['rv_voucher'][0] ]);
		Input::merge(['voucher_type' => $vtype]);
		Input::merge(['voucher_date' => ($attributes['date']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['date'])) ]);
		Input::merge(['voucher_no' => $attributes['rv_voucher_no'][0] ]); 
		Input::merge(['account_name' => $acname]);
		Input::merge(['account_id' => $acid]);
		Input::merge(['group_id' => $grparr]);
		Input::merge(['vatamt' => $vatamt]);
		Input::merge(['sales_invoice_id' => $siarr]);
		Input::merge(['bill_type' => $btarr]);
		Input::merge(['description' => $desarr]);
		Input::merge(['reference' => $refarr]);
		Input::merge(['je_id' => $jearr]);
		Input::merge(['inv_id' => $invarr]);
		Input::merge(['actual_amount' => $actarr]);
		Input::merge(['account_type' => $actypearr]);
		Input::merge(['line_amount' => $lnarr]);
		Input::merge(['job_id' => $jbarr]);
		Input::merge(['bank_id' => $bnkarr]);
		Input::merge(['cheque_no' => $chqarr]);
		Input::merge(['cheque_date' => $chqdtarr]);
		Input::merge(['department' => $dptarr]);
		Input::merge(['partyac_id' => $pryarr]);
		Input::merge(['party_name' => $prtnarr]);
		Input::merge(['tr_id' => $trarr]);
		Input::merge(['difference' => 0]);
		Input::merge(['remove_item' => $remrv]);
		Input::merge(['trn_no' => '']);
		Input::merge(['curno' => '']);
		Input::merge(['debit' => $rv_amount]);
		Input::merge(['credit' => $rv_amount]);
		Input::merge(['currency_id' => $pmode]);
		
	
		return true;
	}
	
	
	public function edit($id) { 
	
		$crow = DB::table('contract_connection')->join('account_master AS AM','AM.id','=','contract_connection.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_connection.flat_id')
					->where('contract_connection.id',$id)
					->select('contract_connection.*','AM.master_name','AM.cl_balance','F.flat_no AS flat')->first();
		
		//echo $id.'<pre>';print_r($crow);exit;			
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',2)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		
		$transactions = DB::table('contract_transaction')->where('contract_id',$id)->get();
		
		$jve = DB::table('journal')
						->join('journal_entry','journal_entry.journal_id','=','journal.id')
						->where('journal.voucher_no',$crow->sin_no)
						->where('journal.voucher_type','SIN')
						->where('journal_entry.deleted_at','0000-00-00 00:00:00')
						->where('journal_entry.status',1)
						->select('journal_entry.id','journal_entry.account_id','journal_entry.entry_type')
						->orderBy('journal_entry.id')->get();
						
		foreach($jve as $jv) {
			if($jv->entry_type=='Dr')
				$jvd[] = $jv->id;
			else
				$jvc[] = $jv->id;
		}
		
		$rvs = DB::table('receipt_voucher')->where('sales_invoice_id', $id)
						->join('receipt_voucher_entry','receipt_voucher_entry.receipt_voucher_id','=','receipt_voucher.id')
						->where('receipt_voucher_entry.deleted_at','0000-00-00 00:00:00')
						->where('receipt_voucher_entry.status',1)
						->select('receipt_voucher_entry.id','receipt_voucher_entry.account_id','receipt_voucher_entry.entry_type')
						->orderBy('receipt_voucher_entry.id')->get();
		
		$photos = DB::table('contract_attachment')->where('contract_id',$id)->get(); 
		/* $val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		} */
		//echo '<pre>';print_r($itemdesc);exit;
		
		return view('body.contractconnection.edit')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withJvs($jvc)
					->withJvd($jvd)
					->withRvs($rvs)
					->withTransactions($transactions)
					->withPhotos($photos)
					->withRvvoucher($vchrdata);
	}
	
	
	public function update(Request $request, $id)
	{ //echo '<pre>';print_r($request->all());exit;
		
		DB::beginTransaction();
		try {
			
			$acname = $acid = $grparr = $siarr = $btarr = $desarr = $refarr = $invarr = $actarr = $actypearr = $lnarr = $jbarr = $dptarr = $vatarr = $cqarr = $bkarr = $cqdarr = $cqoarr = []; 
			DB::table('contract_connection')
						->where('id', $id)
						->update([
							'building_id' => $request->get('building_id'),
							'flat_id' => $request->get('flat_id'),
							'customer_id' => $request->get('customer_id'),
							'grand_total' => $request->get('grand_total'),
							'is_rv' => ($request->get('is_rv')=='')?0:$request->get('is_rv'),
							'modify_at' => date('Y-m-d h:i:s'),
							'modify_by' => Auth::User()->id,
							'rv_amount' => $request->get('rv_amount'),
							'new_reading' => $request->get('new_reading')
						]);
						
			$input = $request->all();
			foreach($input['trnid'] as $k => $val) {
				DB::table('contract_transaction')->where('id',$val)->update(['amount' => $input['acamount'][$k]]);
			}
			
			if(isset($attributes['photo_id'])) {
					
				foreach($attributes['photo_id'] as $key => $val) {
					
					//UPDATE...
					if($val!='') {
						DB::table('contract_attachment')
							->where('id', $val)
							->update(['file_name' =>  $attributes['photo_name'][$key],
									  'title'	=> $attributes['title'][$key]
									]);
									
					} else { 
						//ADD NEW..
						DB::table('contract_attachment')
							->insert(['contract_id' => $id, 
									  'file_name' => $attributes['photo_name'][$key],
									  'title'	=> $attributes['title'][$key]
									]);
					}
				}
			}
			
			//Remove photos
			if(isset($attributes['rem_photo_id']) && $attributes['rem_photo_id']!='') {
				$rem_photos = explode(',',$attributes['rem_photo_id']);
				foreach($rem_photos as $id) {
					$rec = DB::table('contract_attachment')->find($id);
					DB::table('contract_attachment')->where('id', $id)->delete();
					
					if($rec->file_name!='') {			
						$fPath = public_path() . $this->imgDir.'/'.$rec->file_name;
						File::delete($fPath);
					}
				}
			}
			
			$sinRow = DB::table('journal')->where('voucher_no',$input['sin_no'])->where('voucher_type','SIN')->select('id')->first();
			$acarr = $request->get('acid');
			$acname[] = $request->get('customer_account');
			$acid[] = $request->get('customer_id');
			$grparr[] = 'CUSTOMER';
			$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = $dptarr[] = $bkarr[] = $cqarr[] = $cqoarr[] = $cqdarr[] = '';
			$desarr[] = $request->get('connection_no').'/'.$request->get('sin_no');
			$refarr[] = $request->get('connection_no');
			$actypearr[] = 'Dr';
			$lnarr[] = $request->get('grand_total');
			
			$acamount = $request->get('acamount');
			$actax = null;
			$arracname = $request->get('acname');
			$jearr = $request->get('je_id');
			
			foreach($request->get('acid') as $key => $val) {
				$acname[] = $arracname[$key];
				$acid[] = $val;
				$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
				$refarr[] = $request->get('connection_no');
				$actypearr[] = 'Cr';
				$lnarr[] = $acamount[$key];
			}
				
			//UPDATE SALES NONSTOCK
			Input::merge(['voucher' => 18]);
			Input::merge(['voucher_type' => 6]);
			Input::merge(['voucher_date' => date('Y-m-d', strtotime($request->get('date'))) ]); 
			Input::merge(['voucher_no' => $request->get('sin_no')]);
			Input::merge(['remove_item' => '']);
			Input::merge(['account_name' => $acname]);
			Input::merge(['account_id' => $acid]);
			Input::merge(['group_id' => $grparr]);
			Input::merge(['description' => $desarr]);
			Input::merge(['reference' => $refarr]);
			Input::merge(['je_id' => $jearr]);
			Input::merge(['department' => $dptarr]);
			Input::merge(['bank_id' => $bkarr]);
			Input::merge(['cheque_no' => $cqarr]);
			Input::merge(['oldcheque_no' => $cqoarr]);
			Input::merge(['cheque_date' => $cqdarr]);
			Input::merge(['inv_id' => '']);
			Input::merge(['actual_amount' => $actarr]);
			Input::merge(['account_type' => $actypearr]);
			Input::merge(['line_amount' => $lnarr]);
			Input::merge(['job_id' => $jbarr]);
			Input::merge(['difference' => 0]);
			Input::merge(['debit' => $request->get('grand_total')]);
			Input::merge(['credit' => $request->get('grand_total')]);
			//echo '<pre>';print_r(Input::all());exit;
			$this->journal->updateSIN($sinRow->id, Input::all());
			
			//exit;
			if( isset($input['is_rv']) && $input['is_rv']==1 ) { 
				$rvsi = DB::table('receipt_voucher')->where('sales_invoice_id',$id)->select('id')->first();
				if($rvsi) {
					$input['voucher_no'] = $input['connection_no'];
					$this->RVformSet($input, $id);
					$this->receipt_voucher->update($rvsi->id, Input::all());
				} else {
					$input['voucher_no'] = $input['connection_no'];
					$this->RVformSet($input, $id);
					$this->receipt_voucher->create(Input::all());
				}
			}
			
			
			DB::commit();
			
			Session::flash('message', 'Connection details updated successfully.');
			return redirect('contract-connection');
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('contract-connection')->withErrors($e->getErrors());
		}
						
	}
	
	
	public function reading($id) { 
	
		$crow = DB::table('contract_connection')->join('account_master AS AM','AM.id','=','contract_connection.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_connection.flat_id')
					->where('contract_connection.id',$id)
					->select('contract_connection.*','AM.master_name','F.flat_no AS flat')->first();
					
		//echo $id.'<pre>';print_r($crow);exit;			
		$buildingmaster = DB::table('buildingmaster')->where('id',$crow->building_id)->select('id','buildingcode','buildingname','unit_price')->first();
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',1)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		
		$transactions = DB::table('contract_transaction')->where('contract_id',$id)->get();
		
		$jve = DB::table('journal')
						->join('journal_entry','journal_entry.journal_id','=','journal.id')
						->where('journal.voucher_no',$crow->sin_no)
						->where('journal.voucher_type','SIN')
						->where('journal_entry.deleted_at','0000-00-00 00:00:00')
						->where('journal_entry.status',1)
						->select('journal_entry.id','journal_entry.account_id','journal_entry.entry_type')
						->orderBy('journal_entry.id')->get();
						
		foreach($jve as $jv) {
			if($jv->entry_type=='Dr')
				$jvd[] = $jv->id;
			else
				$jvc[] = $jv->id;
		}
		
		$rvs = DB::table('receipt_voucher')->where('sales_invoice_id', $id)
						->join('receipt_voucher_entry','receipt_voucher_entry.receipt_voucher_id','=','receipt_voucher.id')
						->where('receipt_voucher_entry.deleted_at','0000-00-00 00:00:00')
						->where('receipt_voucher_entry.status',1)
						->select('receipt_voucher_entry.id','receipt_voucher_entry.account_id','receipt_voucher_entry.entry_type')
						->orderBy('receipt_voucher_entry.id')->get();
		
		$photos = DB::table('contract_attachment')->where('contract_id',$id)->get(); 
		/* $val = '';
		foreach($photos as $row) {
			$val .= ($val=='')?$row->photo:','.$row->photo;
		} */
		//echo '<pre>';print_r($itemdesc);exit;
		
		$reads = DB::table('meter_reading')->where('contract_id',$id)->select('id','current')->orderBy('id','DESC')->first();
		
		return view('body.contractconnection.reading')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withJvs($jvc)
					->withJvd($jvd)
					->withRvs($rvs)
					->withTransactions($transactions)
					->withPhotos($photos)
					->withReads($reads)
					->withRvvoucher($vchrdata);
	}
	
	
	public function readingList() {
	
		$reading = DB::table('meter_reading')
					->join('contract_connection AS C','C.id','=','meter_reading.contract_id')
					->join('account_master AS AM','AM.id','=','C.customer_id')
		            ->join('buildingmaster AS B','B.id','=','C.building_id')
					->leftjoin('flat_master AS F','F.id','=','C.flat_id')
					->where('meter_reading.deleted_at',NULL)
					->select('meter_reading.*','AM.master_name','F.flat_no AS flat','B.buildingcode','C.connection_no')->get();
					//echo '<pre>';print_r($reading);exit;
		return view('body.contractconnection.reading-list')
					->withReading($reading);
	}
	

	
	public function getPrint($id,$rid=null) {
		
		
		/* $voucherhead = 'TAX INVOICE';
		$jv=DB::table('journal')->where('voucher_no',$id)->where('voucher_type','SIN')->select('id')->first();
		$jvrow = $this->journal->find($jv->id); 
		$jerow = $this->journal->findJEdata($jv->id); //echo '<pre>';print_r($jerow);exit;

		return view('body.salesvoucher.print')
					->withVoucherhead($voucherhead)
					->withDetails($jvrow)
					->withJerow($jerow); */
					
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_split->getInvoice($attributes);
			$titles = ['main_head' => 'Purchase Invoice','subhead' => 'Purchase Invoice'];
			return view('body.salessplit.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			
			return view('body.salessplit.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function getPrintRead($id,$rid=null) {
		
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		if($viewfile->print_name=='') {
			$attributes['document_id'] = $id;
			$attributes['is_fc'] = ($fc)?1:'';
			$result = $this->sales_split->getInvoice($attributes);
			$titles = ['main_head' => 'Purchase Invoice','subhead' => 'Purchase Invoice'];
			return view('body.salessplit.print')
						->withDetails($result['details'])
						->withTitles($titles)
						->withFc($attributes['is_fc'])
						->withId($id)
						->withItems($result['items']);
		} else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			
			return view('body.salessplit.viewer')->withPath($path)->withView($viewfile->print_name);
		}
		
	}
	
	public function readingAdd() {
			
		$crow = [];
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',1)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		
		$reads = DB::table('meter_reading')->select('id','current')->orderBy('id','DESC')->first();
		return view('body.contractconnection.reading-add2')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withReads($reads)
					->withRvvoucher($vchrdata);
	}
	
	public function getReading($cid,$fid,$type) {
		
		$data = DB::table('meter_reading')
					->leftjoin('contract_connection','contract_connection.id','=','meter_reading.contract_id')
					->where('contract_connection.flat_id',$fid)
					->where('contract_connection.customer_id',$cid)
					->select('meter_reading.id','meter_reading.current','meter_reading.created_at','contract_connection.id AS connection_id')
					->orderBy('meter_reading.id','DESC')->first();
		//echo '<pre>';print_r($data);exit;
		if($type=='R') {
			if($data) {
				$now = time(); 
				$date = strtotime($data->created_at);
				$datediff = $now - $date;
				$diff = round($datediff / (60 * 60 * 24));
				if($diff >= 25) {
					$status = ['status' => true, 'data' => $data, 'message' => ''];
				} else 
					$status = ['status' => false, 'data' => null, 'message' => 'Meter reading already done.'];
			} else {
				$datan = DB::table('contract_connection')
						->where('contract_connection.flat_id',$fid)
						->where('contract_connection.customer_id',$cid)
						->select('contract_connection.new_reading AS current')
						->first();
				if($datan) 
					$status = ['status' => true, 'data' => $datan, 'message' => ''];
				else
					$status = ['status' => true, 'data' => null, 'message' => 'Not found connect or tenant details.'];
			}
			
		} else {
			if($data) {
				$status = ['status' => true, 'data' => $data, 'message' => ''];
			} else {
				$status = ['status' => false, 'data' => null, 'message' => 'Previous reading details not found.'];
			}
			
		}
		echo json_encode($status);
	}
	
	public function disconnectAdd() {
			
		$crow = [];
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',3)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		
		$reads = DB::table('meter_reading')->select('id','current')->orderBy('id','DESC')->first();
		$vchrdata = $this->getVoucherRV(9,'CASH');
		return view('body.contractconnection.disconnect-add')
					->withCrow($crow)
					->withAccounts($accounts)
					->withBuildingmaster($buildingmaster)
					->withRvid(9)
					->withReads($reads)
					->withRvvoucher($vchrdata);
	}
	
	
	public function disconUpdate(Request $request) {// echo '<pre>';print_r($request->all());exit;  
		
		DB::beginTransaction();
		try {
			$ctid = 3;
			$controw = DB::table('contract_type_re')->where('id',$ctid)->select('increment_no')->first();
			$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			
			$data = DB::table('contract_connection')
					->where('contract_connection.flat_id',$request->get('flat_id'))
					->where('contract_connection.customer_id',$request->get('customer_id'))
					->select('contract_connection.id AS connection_id')
					->first();
			//echo '<pre>';print_r($data);exit;		
			$id = $data->connection_id;
					
			$readid = DB::table('disconnection')
						->insertGetId([
							'contract_id' => $id,
							'previous' => $request->get('previous'),
							'current' => $request->get('current'),
							'rate' => $request->get('rate'),
							'cons_unit' => $request->get('con_unit'),
							'total' => ($request->get('con_unit') * $request->get('rate')),
							'vat' => $request->get('vat_amount'),
							'grand_total' => $request->get('grand_total'),
							'created_at' => date('Y-m-d h:i:s'),
							'created_by' => Auth::User()->id,
							'sin_no' => $sirow->voucher_no
						]);
			
			$input = $request->all();
			foreach($input['acname'] as $k => $val) {
				DB::table('discon_transaction')
							->insert([
								'discon_id' => $readid,
								'con_settings_id' => $ctid,
								'account_id' => $input['acid'][$k],
								'amount'	=> $input['acamount'][$k]
							]);
			}
			
						
			DB::table('contract_type_re')->where('id',$ctid)->update(['increment_no' => DB::raw('increment_no + 1')]);
				
			//SALES INVOICE NONSTOCK ENTRY..... total_amount
			$acname[] = $request->get('customer_account');
			$acid[] = $request->get('customer_id');
			$grparr[] = 'CUSTOMER';
			$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = '';
			$desarr[] = 'D'.$controw->increment_no.'/'.$sirow->voucher_no;
			$refarr[] = 'D'.$controw->increment_no;
			$actypearr[] = 'Dr';
			$lnarr[] = $request->get('grand_total');
			
			$acamount = $request->get('acamount');
			$actax = $request->get('is_tax');
			$arracname = $request->get('acname');
			foreach($request->get('acid') as $key => $val) {
				$acname[] = $arracname[$key];
				$acid[] = $val;
				$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
				$refarr[] = 'D'.$controw->increment_no;
				$actypearr[] = 'Cr';
				$lnarr[] = $acamount[$key];
			}
			
			//TAX EXTRY....
			if($actax) {
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				foreach($request->get('acid') as $key => $val) {
					if($actax[$key] > 0) {
						$acname[] = 'VAT ACCOUNT';//$arracname[$key];
						$acid[] = $vatrow->payment_account;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$desarr[] = $arracname[$key].'/TAX';
						$refarr[] = 'D'.$controw->increment_no;
						$actypearr[] = 'Cr';
						$lnarr[] = $request->get('vat_amount');;
					}
				}
			}
			
			//INSERT SALES NONSTOCK.... 
			Input::merge(['from_jv' => 1]);
			Input::merge(['voucher' => 18]);
			Input::merge(['voucher_type' => 6]);
			Input::merge(['voucher_date' => date('Y-m-d', strtotime($request->get('date'))) ]);
			Input::merge(['voucher_no' => $sirow->voucher_no]);
			Input::merge(['account_name' => $acname]);
			Input::merge(['account_id' => $acid]);
			Input::merge(['group_id' => $grparr]);
			Input::merge(['sales_invoice_id' => $siarr]);
			Input::merge(['bill_type' => $btarr]);
			Input::merge(['description' => $desarr]);
			Input::merge(['reference' => $refarr]);
			Input::merge(['inv_id' => $invarr]);
			Input::merge(['actual_amount' => $actarr]);
			Input::merge(['account_type' => $actypearr]);
			Input::merge(['line_amount' => $lnarr]);
			Input::merge(['job_id' => $jbarr]);
			Input::merge(['difference' => 0]);
			Input::merge(['curno' => '']);
			Input::merge(['is_prefix' => 0]);
			Input::merge(['debit' => $request->get('grand_total')]);
			Input::merge(['credit' => $request->get('grand_total')]);
			
			$this->journal->createSIN(Input::all());
			//.......END SALESINVOICE NON STOCK ENTRY
			
			
			//RV ENTRY.......
			if( isset($input['is_rv']) && $input['is_rv']==1 ) { 
				$input['voucher_no'] = $sirow->voucher_no;
				$this->RVformSet($input, $readid);
				$this->receipt_voucher->create(Input::all());
			}
			
			DB::commit();
			//exit;
			Session::flash('message', 'Disconnection added successfully.');
			return redirect('contract-connection/disconnection-list');
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('contract-connection/disconnection-list')->withErrors($e->getErrors());
		}
	}
	
	public function disconnectionList() {
	
		$reading = DB::table('disconnection')
					->join('contract_connection AS C','C.id','=','disconnection.contract_id')
					->join('account_master AS AM','AM.id','=','C.customer_id')
		            ->join('buildingmaster AS B','B.id','=','C.building_id')
					->leftjoin('flat_master AS F','F.id','=','C.flat_id')
					->where('disconnection.deleted_at',NULL)
					->select('disconnection.*','AM.master_name','F.flat_no AS flat','B.buildingcode','C.connection_no')->get();
					//echo '<pre>';print_r($reading);exit;
		return view('body.contractconnection.disconnection-list')
					->withReading($reading);
	}
	
	protected function makeTreeAll($result)
	{
		$childsJV = $childsPV = $childsRV = $childsSIN = array();
		foreach($result as $item)
			if($item->voucher_type=="SIN")
				$childsSIN[$item->id][] = $item;
			else if($item->voucher_type=="RV")
				$childsRV[$item->id][] = $item;
			else if($item->voucher_type=="PV")
				$childsPV[$item->id][] = $item;
			else if($item->voucher_type=="JV")
				$childsJV[$item->id][] = $item;
		
		return array_merge($childsSIN,$childsJV,$childsPV,$childsRV);
	}
	
	public function printAll($id) {
		
		$crow = DB::table('contract_connection')
                    ->join('buildingmaster AS B','B.id','=','contract_connection.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_connection.flat_id')
					->leftjoin('receipt_voucher AS R','R.sales_invoice_id','=','contract_connection.id')
					->where('contract_connection.id',$id)
					->select('contract_connection.sin_no','contract_connection.connection_no','B.buildingcode AS buildcode','F.flat_no AS flat','R.id AS rvid')
					->first();
					
		//echo '<pre>';print_r($jvids);exit;
		$attributes['jvids'] = [];
		//$attributes['rvids'] = [];
		$attributes['pvids'] = [];
		if($crow) {
			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->sin_no)->where('journal.voucher_type','SIN')->first();
			$attributes['type'] = 'SIN';
			$attributes['jid'] = $jerow->id;
			$attributes['rvids'][] = $crow->rvid;
			$results = $this->makeTreeAll( $this->voucherwise_report->getConReportsByIdRE($attributes) );  
			
			//echo '<pre>';print_r($results);exit;				
		}
			
		return view('body.contractconnection.printall')
						->withDetails($crow)
						->withTransactions($results);
	}
	
	public function buildingRead(Request $request) {
		
		//
		//DB::enableQueryLog(); $fd = date('Y-m-d',strtotime('01-02-2023'));
		$res = DB::table('contract_connection')
				->leftjoin('meter_reading','meter_reading.contract_id','=','contract_connection.id')
				->join('flat_master','flat_master.id','=','contract_connection.flat_id')
				->join('buildingmaster','buildingmaster.id','=','contract_connection.building_id')
				->join('account_master','account_master.id','=','contract_connection.customer_id')
				->where('contract_connection.building_id',$request->get('bid'))
				//->where('meter_reading.is_read',0) 
				//->where('meter_reading.to_date','<',date('Y-m-d',strtotime($request->get('toDate'))))
				//->where('meter_reading.from_date','<',date('Y-m-d',strtotime($request->get('frmDate'))))
				->select('contract_connection.*','flat_master.flat_no','account_master.master_name','meter_reading.previous','meter_reading.current',
						'buildingmaster.unit_price','meter_reading.from_date','meter_reading.to_date','meter_reading.id as readid','buildingmaster.other_charge')
				->groupBy('contract_connection.id')
				->orderBy('contract_connection.flat_id')
				->get();
		//dd(DB::getQueryLog());		
		$vchrdata = $this->getVoucherRV(9,'CASH');
				
		$accounts = DB::table('contract_type_settings')
						->join('account_master','account_master.id','=','contract_type_settings.account_id')
						->where('contract_type_re_id',1)
						->where('contract_type_settings.deleted_at',null)
						->select('contract_type_settings.*','account_master.master_name')
						->get();
						
		$resdone = DB::table('contract_connection')
				->leftjoin('meter_reading','meter_reading.contract_id','=','contract_connection.id')
				->join('flat_master','flat_master.id','=','contract_connection.flat_id')
				->join('buildingmaster','buildingmaster.id','=','contract_connection.building_id')
				->join('account_master','account_master.id','=','contract_connection.customer_id')
				->where('contract_connection.building_id',$request->get('bid'))
				->where('meter_reading.is_read',1) 
				->where('meter_reading.previous','>',0) 
				//->where('meter_reading.to_date','<',date('Y-m-d',strtotime($request->get('toDate'))))  cons_unit
				//->where('meter_reading.from_date','<',date('Y-m-d',strtotime($request->get('frmDate'))))
				->select('contract_connection.id','contract_connection.sin_no','flat_master.flat_no','account_master.master_name','meter_reading.previous','meter_reading.current','meter_reading.id AS mid',
						'buildingmaster.unit_price','meter_reading.from_date','meter_reading.to_date','meter_reading.id as readid','meter_reading.cons_unit','meter_reading.grand_total')
				->groupBy('contract_connection.id')
				->orderBy('contract_connection.flat_id')
				->get();
						
		//echo '<pre>';print_r($resdone);exit;		
		return view('body.contractconnection.building-read')
						->withAccounts($accounts)
						->withRvvoucher($vchrdata)
						->withResult($res)
						->withResdone($resdone);
	} 
	
	public function readUpdate(Request $request) { //echo '<pre>';print_r($request->all());exit;  
		
		DB::beginTransaction();
		try {
			$ctid = 1;
			$controw = DB::table('contract_type_re')->where('id',$ctid)->select('increment_no')->first();
			$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			
			$data = DB::table('contract_connection')
					->where('contract_connection.flat_id',$request->get('flat_id'))
					->where('contract_connection.customer_id',$request->get('customer_id'))
					->select('contract_connection.id AS connection_id')
					->first();
			//echo '<pre>';print_r($data);exit;		
			$id = $data->connection_id;
					
			$readid = DB::table('meter_reading')
						->insertGetId([
							'contract_id' => $id,
							'previous' => $request->get('previous'),
							'current' => $request->get('current'),
							'rate' => $request->get('rate'),
							'cons_unit' => $request->get('con_unit'),
							'total' => ($request->get('con_unit') * $request->get('rate')),
							'vat' => $request->get('vat_amount'),
							'grand_total' => $request->get('grand_total'),
							'created_at' => date('Y-m-d h:i:s'),
							'created_by' => Auth::User()->id,
							'sin_no' => $sirow->voucher_no,
							'from_date' => date('Y-m-d',strtotime($request->get('frmDate'))),
							'to_date' => date('Y-m-d',strtotime($request->get('toDate')))
						]);
			
			DB::table('meter_reading')->where('id',$request->get('rdid'))->update(['is_read' => 1]);
			
			$input = $request->all();
			foreach($input['acname'] as $k => $val) {
				DB::table('reading_transaction')
							->insert([
								'reading_id' => $readid,
								'con_settings_id' => $ctid,
								'account_id' => $input['acid'][$k],
								'amount'	=> $input['acamount'][$k]
							]);
			}
			
						
			DB::table('contract_type_re')->where('id',$ctid)->update(['increment_no' => DB::raw('increment_no + 1')]);
				
			//SALES INVOICE NONSTOCK ENTRY.....
			$acname[] = $request->get('customer_account');
			$acid[] = $request->get('customer_id');
			$grparr[] = 'CUSTOMER';
			$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = '';
			$desarr[] = 'R'.$controw->increment_no.'/'.$sirow->voucher_no;
			$refarr[] = 'R'.$controw->increment_no;
			$actypearr[] = 'Dr';
			$lnarr[] = $request->get('grand_total');
			
			$acamount = $request->get('acamount');
			$actax = $request->get('is_tax');
			$arracname = $request->get('acname');
			foreach($request->get('acid') as $key => $val) {
				$acname[] = $arracname[$key];
				$acid[] = $val;
				$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
				$refarr[] = 'R'.$controw->increment_no;
				$actypearr[] = 'Cr';
				$lnarr[] = $acamount[$key];
			}
			
			//TAX EXTRY....
			if($actax) {
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				foreach($request->get('acid') as $key => $val) {
					if($actax[$key] > 0) {
						$acname[] = 'VAT ACCOUNT';//$arracname[$key];
						$acid[] = $vatrow->payment_account;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$desarr[] = $arracname[$key].'/TAX';
						$refarr[] = 'R'.$controw->increment_no;
						$actypearr[] = 'Cr';
						$lnarr[] = $request->get('vat_amount');;
					}
				}
			}
			
			//INSERT SALES NONSTOCK.... 
			Input::merge(['from_jv' => 1]);
			Input::merge(['voucher' => 18]);
			Input::merge(['voucher_type' => 6]);
			Input::merge(['voucher_date' => date('Y-m-d', strtotime($request->get('date'))) ]);
			Input::merge(['voucher_no' => $sirow->voucher_no]);
			Input::merge(['account_name' => $acname]);
			Input::merge(['account_id' => $acid]);
			Input::merge(['group_id' => $grparr]);
			Input::merge(['sales_invoice_id' => $siarr]);
			Input::merge(['bill_type' => $btarr]);
			Input::merge(['description' => $desarr]);
			Input::merge(['reference' => $refarr]);
			Input::merge(['inv_id' => $invarr]);
			Input::merge(['actual_amount' => $actarr]);
			Input::merge(['account_type' => $actypearr]);
			Input::merge(['line_amount' => $lnarr]);
			Input::merge(['job_id' => $jbarr]);
			Input::merge(['difference' => 0]);
			Input::merge(['curno' => '']);
			Input::merge(['is_prefix' => 0]);
			Input::merge(['debit' => $request->get('grand_total')]);
			Input::merge(['credit' => $request->get('grand_total')]);
			
			$this->journal->createSIN(Input::all());
			//.......END SALESINVOICE NON STOCK ENTRY
			
			
			//RV ENTRY.......
			if( isset($input['is_rv']) && $input['is_rv']==1 ) { 
				$input['voucher_no'] = $sirow->voucher_no;
				$this->RVformSet($input, $readid);
				$this->receipt_voucher->create(Input::all());
			}
			
			DB::commit();
			//exit;
			Session::flash('message', 'Meter reading details added successfully.');
			return redirect('contract-connection/reading-add');
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('contract-connection/reading-list')->withErrors($e->getErrors());
		}
	}
	
	public function destroy($id)
	{
	   //echo '<pre>';print_r($id );exit;
		DB::table('contract_connection')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Connection deleted successfully.');
		return redirect('contract-connection');
	}
	
	public function getBilldata($id) {
		
		$qry1 = DB::table('contract_connection')
					->where('contract_connection.id',$id)
					->join('journal AS J', function($join) {
						$join->on('J.voucher_no','=','contract_connection.sin_no');
						$join->where('J.voucher_type','=','SIN');
						$join->where('J.status','=',1);
						$join->where('J.deleted_at','=','0000-00-00 00:00:00');
					})
					->join('journal_entry AS JE', function($join) {
						$join->on('JE.journal_id','=','J.id');
						$join->where('JE.entry_type','=','Dr');
						$join->where('JE.status','=',1);
						$join->where('JE.deleted_at','=','0000-00-00 00:00:00');
					})
					->Join('account_transaction AS AT', function($join) {
						$join->on('AT.voucher_type_id','=','JE.id');
						$join->where('AT.voucher_type','=','SIN');
						$join->where('AT.transaction_type','=','Dr');
						$join->where('AT.status','=',1);
						$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
					})
					->select('AT.*');
						
		$qry2 = DB::table('contract_connection')->where('contract_connection.id',$id)
					->join('receipt_voucher_tr AS RVT', function($join) {
						$join->on('RVT.sales_invoice_id','=','contract_connection.id');
						$join->where('RVT.bill_type','=','SI');
						$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
					})
					->join('receipt_voucher_entry AS RVE', function($join) {
						$join->on('RVE.id','=','RVT.receipt_voucher_entry_id');
						$join->where('RVT.bill_type','=','SI');
						$join->where('RVT.status','=',1);
						$join->where('RVT.deleted_at','=','0000-00-00 00:00:00');
					})
					->Join('account_transaction AS AT', function($join) {
						$join->on('AT.voucher_type_id','=','RVE.id');
						$join->where('AT.voucher_type','=','RV');
						$join->where('AT.transaction_type','=','Cr');
						$join->where('AT.status','=',1);
						$join->where('AT.deleted_at','=','0000-00-00 00:00:00');
					})
					->select('AT.*');
					
		$result = $qry1->union($qry2)->get();
					
		//echo '<pre>';print_r($result);exit;		
						
		return view('body.salesinvoice.billdata')->withResult($result)->withId($id);
	}

	
	public function getVoucherRV($id,$type) {
		
		 $row = $this->accountsetting->getDrVoucherByID2($id);//return $row;//print_r($row);
		 $voucher = $master_name = $id = null;
		 if($row) {
			 if($row->voucher_no != '' || $row->voucher_no != null) {
				 if($row->is_prefix==0)
					 $voucher = $row->voucher_no;
				 else {
					 $no = (int)$row->voucher_no;
					 $voucher = $row->prefix.''.$no;
				 }
			 }
			 
			 if($type=='CASH') {
				 $master_name = $row->cashaccount;
				 $id = $row->cash_account_id;
			 } else if($type=='BANK') {
				 $master_name = $row->bankaccount;
				 $id = $row->bank_account_id;
			 } else if($type=='PDCR') {
				 $master_name = $row->pdcaccount;
				 $id = $row->pdc_account_id;
			} else if($type=='PDCI') {
				 $master_name = $row->pdcaccount;
				 $id = $row->pdc_account_id;
			}
			$rid = $row->asid;
		 }
		 
		 return $result = array('voucher_no' => $voucher,
								'account_name' => $master_name, 
								'id' => $id,
								'rid' => $rid);
		
	}
	
}

//SELECT SUM(journal_entry.amount) AS amount,account_master.master_name,account_master.address,account_master.phone,contract_connection.connection_no,contract_connection.date,contract_connection.grand_total,AM.master_name AS acname FROM contract_connection JOIN account_master ON(account_master.id=contract_connection.customer_id) JOIN journal ON(journal.voucher_no=contract_connection.sin_no AND journal.voucher_type='SIN') JOIN journal_entry ON(journal_entry.journal_id=journal.id AND journal_entry.entry_type='Cr') JOIN account_master AS AM ON(AM.id=journal_entry.account_id) WHERE journal_entry.amount > 0 AND contract_connection.id={id} GROUP BY acname ORDER  BY journal_entry.amount DESC
				
// SELECT flat_master.flat_no,account_master.master_name,account_master.cl_balance,buildingmaster.buildingname,meter_reading.created_at,meter_reading.from_date,meter_reading.to_date,meter_reading.previous,meter_reading.current,meter_reading.id AS mid,buildingmaster.unit_price,buildingmaster.other_charge,meter_reading.id as readid,meter_reading.cons_unit,meter_reading.grand_total,meter_reading.vat FROM meter_reading JOIN contract_connection ON(contract_connection.id=meter_reading.contract_id) JOIN account_master ON(account_master.id=contract_connection.customer_id) JOIN buildingmaster ON(buildingmaster.id=contract_connection.building_id) JOIN flat_master ON(flat_master.id=contract_connection.flat_id) WHERE meter_reading.id={id}
