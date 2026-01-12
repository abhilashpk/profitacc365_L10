<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use Config;
use DB;
use App;
use Auth;
use Excel;
use File;

class CargoDespatchBillController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->widthC = $config['modules']['cargodespatch']['image_size']['width'];
        $this->heightC = $config['modules']['cargodespatch']['image_size']['height'];
        $this->thumbWidthC = $config['modules']['cargodespatch']['thumb_size']['width'];
        $this->thumbHeightC = $config['modules']['cargodespatch']['thumb_size']['height'];
        $this->imgDirC = $config['modules']['cargodespatch']['image_dir'];
		
	}
	
	public function index() {
				
		return view('body.cargodespatchbill.index');
	}
	
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'cargo_despatch_bill.id', 
                            1 => 'despatch_no',
                            2 => 'despatch_date',
							3 => 'clear_agent',
                            4 => 'vehicle_no',
                            5 => 'driver',
                            6 => 'mob_uae',
							7 => 'mob_ksa'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getCargoDespatchbillList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		if($search)
			$totalFiltered = $totalData = $this->getCargoDespatchbillList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getCargoDespatchbillList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cargo_despatchbill/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print =  '"'.url('cargo_despatchbill/add/'.$row->id).'"';
				$status =  url('cargo_despatchbill/status/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['despatch_no'] = $row->despatch_no;
				$nestedData['despatch_date'] = date('d-m-Y', strtotime($row->despatch_date));
				$nestedData['clear_agent'] = $row->clear_agent;
				//$nestedData['amount'] = $row->total_amount;
				$nestedData['vehicle_no'] = $row->vehicle_no;
				$nestedData['driver'] = $row->driver;
				$nestedData['mob_uae'] = $row->mob_uae;
				$nestedData['mob_ksa'] = $row->mob_ksa;
				//$nestedData['status'] = "<button class='btn btn-primary btn-xs getSts' data-toggle='modal' data-target='#status_modal' data-id='{$row->id}'>Status</button>";
				$nestedData['status'] = '';//"<p><a href='{$status}' class='btn btn-warning btn-xs'><i class='fa fa-fw fa-eye'></i></a></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				
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
	
	private function getCargoDespatchbillList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('cargo_despatch_bill')
								->leftjoin('cargo_status AS CS','CS.id','=','cargo_despatch_bill.status_id')
								->where('cargo_despatch_bill.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					//$query->where('CON.consignee_name','LIKE',"%{$search}%");
					$query->Where('cargo_despatch_bill.despatch_no','LIKE',"%{$search}%");
					$query->orWhere('cargo_despatch_bill.vehicle_no','LIKE',"%{$search}%");
					$query->orWhere('cargo_despatch_bill.driver','LIKE',"%{$search}%");
					//$query->orWhere('cargo_despatch_bill.status',$search);
				});
			}
			
		$query->select('cargo_despatch_bill.id','cargo_despatch_bill.despatch_no','cargo_despatch_bill.despatch_date','cargo_despatch_bill.total_amount','cargo_despatch_bill.vehicle_no',
						'cargo_despatch_bill.driver','cargo_despatch_bill.mob_uae','cargo_despatch_bill.mob_ksa','cargo_despatch_bill.clear_agent','cargo_despatch_bill.status','CS.name AS dstatus','cargo_despatch_bill.status_date');
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	
	public function add() {

		$cVoucher = DB::table('voucher_no')->where('status',1)->where('voucher_type','CDB')->select('id','no')->first();
		$lastid=DB::table('cargo_despatch_bill')->where('deleted_at',null)->select('despatch_no')->orderBy('despatch_no','DESC')->first();
		return view('body.cargodespatchbill.add')
					->withVoucher($cVoucher)
					->withprintid($lastid);
	}
	
	public function save(Request $request) {
		
		DB::beginTransaction();
		try {
			$id = DB::table('cargo_despatch_bill')
					->insertGetId([
						'despatch_no' => $request->get('despatch_no'),
						'despatch_date' => date('Y-m-d', strtotime($request->get('despatch_date'))),
						'vehicle_no'	=> $request->get('vehicle_no'),
						'driver'	=> $request->get('driver'),
						'mob_uae'	=> $request->get('mob_uae'),
						'mob_ksa'	=> $request->get('mob_ksa'),
						'clear_agent_sila'	=> $request->get('clear_agent_sila'),
						'clear_agent'	=> $request->get('clear_agent'),
						'loading_place'	=> $request->get('loading_place'),
						'offloading_place'	=> $request->get('offloading_place'),
						'cargo_waybill_ids'	=> serialize($request->get('waybill_id')),
						'created_at'	=> date('Y-m-d H:i:s'),
						'created_by'	=> Auth::User()->id,
						'total_amount'	=> $request->get('total_amount'),
						'other_charge'	=> $request->get('other_charge'),
						'container_type'	=> $request->get('container_type'),
						'duty_amt'	=> $request->get('duty_amt'),
						'advance'	=> $request->get('advance'),
						'balance'	=> $request->get('balance'),
						'agreed_amt'	=> $request->get('agreed_amt'),
						'agreed_transport'	=> $request->get('agree_transport'),
						'add1_col'	=> $request->get('add1_col'),
						'add2_col'	=> $request->get('add2_col'),
						'add3_col'	=> $request->get('add3_col'),
						'add_col1'	=> $request->get('add_col1'),
						'add_col2'	=> $request->get('add_col2'),
						'add_col3'	=> $request->get('add_col3'),
						'remarks'	=> $request->get('remarks'),
						'payment_at'	=> $request->get('payment_at'),
						'weight'	=> $request->get('weight'),
						'volume'	=> $request->get('volume'),
						'exporter'	=> $request->get('exporter'),
						'importer'	=> $request->get('importer')
					]);
				
			DB::table('voucher_no')->where('voucher_type','CDB')->update(['no' => DB::raw('no + 1') ]);
			
			DB::table('cargo_waybill')->whereIn('id', $request->get('waybill_id'))->update(['status' => 1]);
			
			foreach($request->get('waybill_id') ??[] as $wrow) {
				DB::table('cargo_despatch_entry')->insert(['despatch_id' => $id, 'billentry_id' => $wrow]);
				
				DB::table('cargo_waybill')->where('id',$wrow)->update(['despatch_no' => $request->get('despatch_no')]);
			}
			
			DB::commit();
			Session::flash('message', 'Despatch bill added successfully.');
			return redirect('cargo_despatchbill/add');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_despatchbill/add')->withErrors($e->getErrors());
		}
	}
	
	private function sortJobs($jobs) {
		$result = '';
		foreach($jobs as $job) {
			$result .= ($result=='')?$job->job_code:', '.$job->job_code;
		}
		return $result;
	}
	
	public function getWaybills() {
		
		$result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						//->where('cargo_waybill.consignee_id',$id)
						->where('cargo_waybill.status',0)
						->where('cargo_waybill.deleted_at',null)
						->select('cargo_waybill.*','CON.consignee_name')
						->get();
		
		$wbills = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')->whereIn('id', unserialize($row->cargo_receipt_ids))->select('job_code')->get();
			$jobs = $this->sortJobs($res);
			//$des = $this->sortDes($res);
			$wbills[] = (object)[
							'id' => $row->id,
							'bill_no' => $row->bill_no,
							'bill_date'	=> $row->bill_date,
							'consignee_name'	=> $row->consignee_name,
							'jobs'	=> $jobs,
							'vehicle_no'	=> $row->vehicle_no,
							'driver'	=> $row->driver,
							'total_amount'	=> $row->total_amount
						];
		}
		//echo '<pre>';print_r($wbills);exit;				
		return view('body.cargodespatchbill.waybills')
						->withWaybills($wbills);
	}
	
	public function getVehicle()
	{
		$vehicle = DB::table('cargo_vehicle')->where('deleted_at',null)->get();
		return view('body.cargowaybill.vehicle')
						->withVehicle($vehicle);
	}

	
	public function edit($id) { 
		
		$drow = DB::table('cargo_despatch_bill')->where('cargo_despatch_bill.id',$id)
						->select('cargo_despatch_bill.*')
						->first();

		/* $result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						//->where('cargo_waybill.consignee_id',$drow->consignee_id)x
						//->where('cargo_waybill.status',0)x
						->join('cargo_despatch_bill AS CD','CD.despatch_no','=','cargo_waybill.despatch_no')
						->whereIn('cargo_waybill.id',unserialize($drow->cargo_waybill_ids))
						->where('cargo_waybill.deleted_at',null)
						->select('cargo_waybill.*','CON.consignee_name','CD.weight','CD.volume')
						->get(); */
						
		//-----------
		$result = DB::table('cargo_waybill')
						->leftjoin('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						->where('cargo_waybill.status',0)
						->leftjoin('cargo_despatch_bill AS CD','CD.despatch_no','=','cargo_waybill.despatch_no')
						->where('cargo_waybill.deleted_at',null)
						->orWhereIn('cargo_waybill.id',unserialize($drow->cargo_waybill_ids))
						->select('cargo_waybill.*','CON.consignee_name','CD.weight','CD.volume')
						->get();				
		
		$wbills = null;
		foreach($result  as $row) {
			$res = DB::table('cargo_receipt')->whereIn('id', unserialize($row->cargo_receipt_ids))->select('job_code')->get();
			$jobs = $this->sortJobs($res);
			
			$wbills[] = (object)[
							'id' => $row->id,
							'bill_no' => $row->bill_no,
							'bill_date'	=> $row->bill_date,
							'consignee_name'	=> $row->consignee_name,
							'jobs'	=> $jobs,
							'vehicle_no'	=> $row->vehicle_no,
							'driver'	=> $row->driver,
							'total_amount'	=> $row->total_amount,
							'weight' =>$row->weight,
							'volume' =>$row->volume
						];
					
		}
		//echo '<pre>';print_r($jobs);exit;				
		return view('body.cargodespatchbill.edit')
					->withBills($wbills)
					->withRow($drow);

	}
	
	public function update(Request $request, $id)
	{
		DB::beginTransaction();
		try {
				DB::table('cargo_despatch_bill')
					->where('id',$id)
					->update([
						'despatch_date' => date('Y-m-d', strtotime($request->get('despatch_date'))),
						'vehicle_no'	=> $request->get('vehicle_no'),
						'driver'	=> $request->get('driver'),
						'mob_uae'	=> $request->get('mob_uae'),
						'mob_ksa'	=> $request->get('mob_ksa'),
						'clear_agent_sila'	=> $request->get('clear_agent_sila'),
						'clear_agent'	=> $request->get('clear_agent'),
						'loading_place'	=> $request->get('loading_place'),
						'offloading_place'	=> $request->get('offloading_place'),
						'cargo_waybill_ids'	=> serialize($request->get('waybill_id')),
						'modify_at'	=> date('Y-m-d H:i:s'),
						'modify_by'	=> Auth::User()->id,
						'total_amount'	=> $request->get('total_amount'),
						'other_charge'	=> $request->get('other_charge'),
						'container_type'	=> $request->get('container_type'),
						'duty_amt'	=> $request->get('duty_amt'),
						'advance'	=> $request->get('advance'),
						'balance'	=> $request->get('balance'),
						'agreed_amt'	=> $request->get('agreed_amt'),
						'agreed_transport'	=> $request->get('agree_transport'),
						'add1_col'	=> $request->get('add1_col'),
						'add2_col'	=> $request->get('add2_col'),
						'add3_col'	=> $request->get('add3_col'),
						'add_col1'	=> $request->get('add_col1'),
						'add_col2'	=> $request->get('add_col2'),
						'add_col3'	=> $request->get('add_col3'),
						'remarks'	=> $request->get('remarks'),
						'payment_at'	=> $request->get('payment_at'),
						'weight'	=> $request->get('weight'),
						'volume'	=> $request->get('volume'),
						'exporter'	=> $request->get('exporter'),
						'importer'	=> $request->get('importer')
					]);
				
			DB::table('cargo_waybill')->whereIn('id', unserialize($request->get('cur_waybill_id')))->update(['status' => 0]);
			DB::table('cargo_waybill')->whereIn('id', $request->get('waybill_id'))->update(['status' => 1]);
			if(is_array($request->get('cur_waybill_id'))){
			foreach(unserialize($request->get('cur_waybill_id')) as $wrow) {
				DB::table('cargo_despatch_entry')->where('despatch_id',$id)->where('billentry_id',$wrow)->delete();
			}
		}
			
			foreach($request->get('waybill_id') ??[] as $row) {
				DB::table('cargo_despatch_entry')->insert(['despatch_id' => $id, 'billentry_id' => $row]);
			}
			
			DB::commit();
			Session::flash('message', 'Despatch bill entry updated successfully');
			return redirect('cargo_despatchbill');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_despatchbill/edit/'.$id)->withErrors($e->getErrors());
		}
		
	}
	
	public function destroy($id)
	{
		DB::beginTransaction();
		try {
			$row = DB::table('cargo_despatch_bill')->where('id',$id)->select('cargo_waybill_ids')->first();
			$billids = unserialize($row->cargo_waybill_ids);//echo '<pre>';print_r($jobids);exit;
			DB::table('cargo_waybill')->whereIn('id',$billids)->update(['status'=>0]);//echo '<pre>';print_r($ass);exit;
			DB::table('cargo_despatch_bill')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::User()->id ]);
			
			foreach($billids as $wrow) {
				DB::table('cargo_despatch_entry')->where('despatch_id',$id)->where('billentry_id',$wrow)->delete();
			}
			
			DB::commit();
			Session::flash('message', 'Despatch bill entry deleted successfully.');
			return redirect('cargo_despatchbill');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_despatchbill/edit/'.$id)->withErrors($e->getErrors());
		}
	}
	
	public function report() {
		
		return view('body.cargodespatchbill.report');
	}
	public function getPrint($id) {
		//echo '<pre>';print_r($id);exit;	
		$result = DB::table('cargo_waybill')
					->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
					->join('cargo_despatch_entry AS CDE','CDE.billentry_id','=','cargo_waybill.id')
					//->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
					->join('cargo_despatch_bill AS CR','CR.id','=','CDE.despatch_id')
					->leftjoin('cargo_vehicle AS CV','CV.vehicle_no','=','CR.vehicle_no')
					->leftjoin('cargo_status AS CS','CS.id','=','CR.status_id')

					//->where('cargo_waybill.consignee_id',$id)
					//->where('cargo_waybill.status',0)
					->where('cargo_waybill.deleted_at',null)
					->where('CR.deleted_at',null)
					->where('CR.despatch_no',$id)
					->select('cargo_waybill.bill_no','cargo_waybill.total_amount as total','cargo_waybill.instructions','cargo_waybill.loaded_qty','cargo_waybill.loaded_pack_qty',
					'cargo_waybill.cargo_receipt_ids','CON.consignee_name','CON.phone','CR.*','CV.vehicle_no As reg','CV.vehicle_name As veh_type','CV.expiry_date','CV.company','CS.name AS status')
					->get();

					//echo '<pre>';print_r($result);exit;	
	$wbills = null;
	$wbill = null;
	foreach($result as $row) {
		$res = DB::table('cargo_receipt')
										 ->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
										  ->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
										  ->whereIn('cargo_receipt.id', unserialize($row->cargo_receipt_ids))
										 ->select('job_code','destination','despatched_qty','packing_qty','packing_type','CT.code AS collType','DT.code AS deltype')->get();
										 
		 foreach($res as $rw){
		$pktypes = DB::table('units')->whereIn('id',unserialize($rw->packing_type))->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
		}
		$ptypes=$this->sortUnit($pktypes);                                
		$jobs = $this->sortJobs($res);
		//$des = $this->sortDes($res);
		$desptch = $this->sortDesptch($res);
		//$coll = $this->sortColl($res);
		//$del = $this->sortDel($res);
		$wbill[] = (object)[
					   // 'bill_no' => $row->despatch_no,
						'jobs'	=> $jobs,
						'consignee_name'	=> $row->consignee_name,
						'phone'=>$row->phone,
						'packing_qty' => $row->loaded_pack_qty,
						'wbill_no' => $row->bill_no,
						'destination'=>$res[0]->destination,
					//	'despatch'=>$row->loaded_qty,
						 'pack'=>$ptypes,
						'coll_type'	=> $res[0]->collType,
						'del_type'	=> $res[0]->deltype,
						'remarks'	=> $row->instructions,
						'total_amount'	=> $row->total
					];
	}
$wbills = collect($wbill)->sortBy('destination');
	//echo '<pre>';print_r($res);exit;	
	
	return view('body.cargodespatchbill.report-result')
					->withVoucherhead('Despatch Report')
					->withFromno($id)
					//->withTono($request->get('to_no'))
					->withResults($result)
					->withWaybills($wbills);
	
}
	public function searchReport(Request $request) {
		/*if($request->get('from_no')!="" && $request->get('to_no')!=""){
		$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS WB','WB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS WBE','WBE.waybill_id','=','WB.id')
								->join('cargo_receipt AS CR','CR.id','=','WBE.jobentry_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->join('consignee AS CON','CON.id','=','CR.consignee_id')
								->where('cargo_despatch_bill.deleted_at',null)
								//->whereBetween('cargo_despatch_bill.despatch_no',[ date('Y-m-d',strtotime($request->get('date_from'))), date('Y-m-d',strtotime($request->get('date_to'))) ]);
		                        ->whereBetween('cargo_despatch_bill.despatch_no',[ $request->get('from_no'), $request->get('to_no') ]);
		}
		if($request->get('to_no')==''){
			$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS WB','WB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS WBE','WBE.waybill_id','=','WB.id')
								->join('cargo_receipt AS CR','CR.id','=','WBE.jobentry_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->join('consignee AS CON','CON.id','=','CR.consignee_id')
								->where('cargo_despatch_bill.deleted_at',null)
								->where('cargo_despatch_bill.despatch_no',[ $request->get('from_no') ]);
		}
			 if($search) {
				$query->where(function($query) use ($search){
					$query->where('CON.consignee_name','LIKE',"%{$search}%");
					$query->orWhere('cargo_despatch_bill.despatch_no','LIKE',"%{$search}%");
					$query->orWhere('cargo_despatch_bill.vehicle_no','LIKE',"%{$search}%");
					$query->orWhere('cargo_despatch_bill.driver','LIKE',"%{$search}%");
				});
			} 
			
		$result = $query->select('CR.job_code','CON.consignee_name','CON.phone','WB.bill_no','cargo_despatch_bill.*','CR.destination',
								'CR.despatched_qty','WB.total_amount','CT.code','DT.code AS deltype','WB.instructions')
						->orderBy('cargo_despatch_bill.id','ASC')
						->get(); */
						
	
		$result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						->join('cargo_despatch_entry AS CDE','CDE.billentry_id','=','cargo_waybill.id')
						//->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
						->join('cargo_despatch_bill AS CR','CR.id','=','CDE.despatch_id')
						->leftjoin('cargo_vehicle AS CV','CV.vehicle_no','=','CR.vehicle_no')
						->leftjoin('cargo_status AS CS','CS.id','=','CR.status_id')

						//->where('cargo_waybill.consignee_id',$id)
						//->where('cargo_waybill.status',0)
						->where('cargo_waybill.deleted_at',null)
						->where('CR.deleted_at',null)
						->where('CR.despatch_no',[ $request->get('from_no') ])
						->select('cargo_waybill.bill_no','cargo_waybill.total_amount as total','cargo_waybill.instructions','cargo_waybill.loaded_qty','cargo_waybill.loaded_pack_qty',
						'cargo_waybill.cargo_receipt_ids','CON.consignee_name','CON.phone','CR.*','CV.vehicle_no As reg','CV.vehicle_name As veh_type','CV.expiry_date','CV.company','CS.name AS status')
						->get();
	
						//echo '<pre>';print_r($result);exit;	
		$wbills = null;
		$wbill = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')
			                                 ->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
			                                  ->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
											  ->whereIn('cargo_receipt.id', unserialize($row->cargo_receipt_ids))
			                                  ->select('job_code','destination','despatched_qty','packing_qty','packing_type','CT.code AS collType','DT.code AS deltype')->orderBy('cargo_receipt.destination','ASC')->get();
			foreach($res as $rw){
            $pktypes = DB::table('units')->whereIn('id',unserialize($rw->packing_type))->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
			}
			$ptypes=$this->sortUnit($pktypes);
			$jobs = $this->sortJobs($res);
			//$des = $this->sortDes($res);
			$desptch = $this->sortDesptch($res);
			//$coll = $this->sortColl($res);
			//$del = $this->sortDel($res);
			$wbill[] = (object)[
				           // 'bill_no' => $row->despatch_no,
				            'jobs'	=> $jobs,
							'consignee_name'	=> $row->consignee_name,
							'phone'=>$row->phone,
							'packing_qty' => $row->loaded_pack_qty,
							'wbill_no' => $row->bill_no,
							'destination'=>$res[0]->destination,
							//'despatch'=>$row->loaded_qty,
							'pack'=>$ptypes,
							'coll_type'	=> $res[0]->collType,
							'del_type'	=> $res[0]->deltype,
							'remarks'	=> $row->instructions,
							'total_amount'	=> $row->total
						];
		}
		$wbills = collect($wbill)->sortBy('destination');
	//echo '<pre>';print_r($pktypes);exit;	
		
		return view('body.cargodespatchbill.report-result')
						->withVoucherhead('Despatch Report')
						->withFromno($request->get('from_no'))
						->withTono($request->get('to_no'))
						->withResults($result)
						->withWaybills($wbills);
		
	}

	private function sortUnit($ptypes) {
		
		$ptype = '';
		foreach($ptypes as $pt) {
			$ptype .= ($ptype=='')?$pt->description:','.$pt->description;
		}
		return $ptype;
	}


	private function sortDes($des) {
		$result = '';
		foreach($des as $row) {
			$result .= ($result=='')?$row->destination:', '.$row->destination;
		}
		return $result;
	}

	private function sortDesptch($desptch) {
		$result = 0;
		foreach($desptch as $row) {
			//$result .= ($result=='')?$row->despatched_qty:', '.$row->despatched_qty;
			$result +=$row->despatched_qty;
		}
		return $result;
	}
	private function sortColl($coll) {
		$result = '';
		foreach($coll as $row) {
			$result .= ($result=='')?$row->collType:', '.$row->collType;
		}
		return $result;
	}
	private function sortDel($del) {
		$result = '';
		foreach($del as $row) {
			$result .= ($result=='')?$row->deltype:', '.$row->deltype;
		}
		return $result;
	}

	public function dataExport(Request $request){
		$data = array();
		$voucher_head ='Despatch Report';
		/* if($request->get('from_no')!="" && $request->get('to_no')!=""){
		$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS WB','WB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS WBE','WBE.waybill_id','=','WB.id')
								->join('cargo_receipt AS CR','CR.id','=','WBE.jobentry_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->join('consignee AS CON','CON.id','=','CR.consignee_id')
								->where('cargo_despatch_bill.deleted_at',null)
								->whereBetween('cargo_despatch_bill.despatch_no',[ $request->get('from_no'), $request->get('to_no') ]);
		}
		if($request->get('to_no')==''){
			$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS WB','WB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS WBE','WBE.waybill_id','=','WB.id')
								->join('cargo_receipt AS CR','CR.id','=','WBE.jobentry_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->join('consignee AS CON','CON.id','=','CR.consignee_id')
								->where('cargo_despatch_bill.deleted_at',null)
								->where('cargo_despatch_bill.despatch_no',[ $request->get('from_no') ]);
		}
								
		$result = $query->select('CR.job_code','CON.consignee_name','CON.phone','WB.bill_no','cargo_despatch_bill.*','CR.destination',
								'CR.despatched_qty','WB.total_amount','CT.code','DT.code AS deltype','WB.instructions')
						->orderBy('cargo_despatch_bill.id','ASC')
						->get(); */
						//echo '<pre>';print_r($result);exit;
						$result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						->join('cargo_despatch_entry AS CDE','CDE.billentry_id','=','cargo_waybill.id')
						//->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
						->join('cargo_despatch_bill AS CR','CR.id','=','CDE.despatch_id')
						->leftjoin('cargo_vehicle AS CV','CV.vehicle_no','=','CR.vehicle_no')
						->leftjoin('cargo_status AS CS','CS.id','=','CR.status_id')

						//->where('cargo_waybill.consignee_id',$id)
						//->where('cargo_waybill.status',0)
						->where('cargo_waybill.deleted_at',null)
						->where('CR.deleted_at',null)
						->where('CR.despatch_no',[ $request->get('from_no') ])
						->select('cargo_waybill.bill_no','cargo_waybill.total_amount as total','cargo_waybill.instructions','cargo_waybill.loaded_qty','cargo_waybill.loaded_pack_qty','cargo_waybill.cargo_receipt_ids',
						'CON.consignee_name','CON.phone','CR.*','CV.vehicle_no As reg','CV.vehicle_name As veh_type','CV.expiry_date','CV.company','CS.name AS status')
						->get();
	
						//echo '<pre>';print_r($result);exit;	
		/*$wbills = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')
			                                 ->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
			                                  ->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
											  ->whereIn('cargo_receipt.id', unserialize($row->cargo_receipt_ids))
			                                  ->select('job_code','destination','despatched_qty','CT.code AS collType','DT.code AS deltype')->get();
			$jobs = $this->sortJobs($res);
			//$des = $this->sortDes($res);
			$desptch = $this->sortDesptch($res);
			//$coll = $this->sortColl($res);
			//$del = $this->sortDel($res);
			$wbills[] = (object)[
				            'bill_no' => $row->despatch_no,
				            'jobs'	=> $jobs,
							'consignee_name'	=> $row->consignee_name,
							'phone'=>$row->phone,
							//'id' => $row->id,
							'wbill_no' => $row->bill_no,
							'destination'=>$res[0]->destination,
							'despatch'=>$desptch,
							'total_amount'	=> $row->total,
							'coll_type'	=> $res[0]->collType,
							'del_type'	=> $res[0]->deltype,
							'remarks'	=> $row->instructions,
							
						];
		}*/				
		$fromno=$request->get('from_no');
		$date=date('d-m-Y', strtotime($result[0]->despatch_date));
		$des=$result[0]->offloading_place;
		$type=$result[0]->container_type;
		//$tono=$request->get('to_no');		
	    $datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];	
	    $datareport[] = ['','','','','','',''];
	    $datareport[] = ['','','','',strtoupper($voucher_head),'','','','Type',$type];	
	    $datareport[] = ['','','','','','',''];
		$datareport[] = ['Despatch No.:',$fromno,'','','Date',$date,'','','Destination',$des];
		$datareport[] = ['','','','','','',''];
		$datareport[] = ['Bill No','Job No','Consignee','Phone','Way Bill','City','Qty','Pack','Amount','Col.Type','Del.Type','Remarks'];	
		$total=0;	
		$tot=0;
		/*foreach($result as $row) {
			$datareport[] = [ 'Job No' => $row->job_code ,
							  'Consignee' => $row->consignee_name,
							  'Phone' => $row->phone,
							  'Way Bill' => $row->bill_no,
							  'City' => $row->destination,
							  'Qty' => $row->despatched_qty,
							  'Amount'=>$row->total_amount,
							  'Col.Type'=>$row->code,
							  'Del.Type'=>$row->deltype,
							  'Remarks'=>$row->instructions
							];
							$total+= $row->total_amount;
							$tot=number_format($total,2) ;		
						} */
						$wbills = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')
			                                 ->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
			                                  ->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
											  ->whereIn('cargo_receipt.id', unserialize($row->cargo_receipt_ids))
			                                  ->select('job_code','destination','despatched_qty','packing_qty','packing_type','CT.code AS collType','DT.code AS deltype')->get();
			foreach($res as $rw){
				$pktypes = DB::table('units')->whereIn('id',unserialize($rw->packing_type))->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
				}
			$ptypes=$this->sortUnit($pktypes);
			$jobs = $this->sortJobs($res);
			//$des = $this->sortDes($res);
			$desptch = $this->sortDesptch($res);
			//$coll = $this->sortColl($res);
			//$del = $this->sortDel($res);
			$datareport[] = [
				            'bill_no' => $row->despatch_no,
				            'jobs'	=> $jobs,
							'consignee_name'	=> $row->consignee_name,
							'phone'=>$row->phone,
							//'id' => $row->id,
							'wbill_no' => $row->bill_no,
							'destination'=>$res[0]->destination,
							'despatch'=>$row->loaded_pack_qty,
							'pack'=>$ptypes,
							'total_amount'	=> $row->total,
							'coll_type'	=> $res[0]->collType,
							'del_type'	=> $res[0]->deltype,
							'remarks'	=> $row->instructions,
							];
							$total+= $row->total;
							$tot=number_format($total,2) ;
		}				
			$datareport[] = ['','','','','','',''];			
			$datareport[] = ['','','','','','Total:','',$tot];
            $datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Driver Details:','','','Truck Details:'];
			$datareport[] = ['Driver Name','Mobile UAE','Mobile KSA','REG','Truck Type','Reg.Exp'];
			
				$datareport[] = [ 'Driver Name' => $result[0]->driver ,
								  'Mob uae' => $result[0]->mob_uae,
								  'Mob ksa' => $result[0]->mob_ksa,
								  'reg' => $result[0]->reg ,
							      'veh_type' => $result[0]->veh_type,
								  'reg_exp' => date('d-m-Y', strtotime($result[0]->expiry_date)),
								  
								];
			/*$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Truck Details:','','','','','',''];
			$datareport[] = ['REG','Type','Company','Reg.Exp'];
								
			$datareport[] = [ 'reg' => $result[0]->reg ,
							   'veh_type' => $result[0]->veh_type,
							   'company' => $result[0]->company,
							   'reg_exp' => $result[0]->expiry_date,
													  
							];	*/				
									
							
		    $datareport[] = ['','','','','','',''];		
			$datareport[] = ['','','','','','',''];		
			$datareport[] = ['Driver Payment:','','','','','',''];
			$datareport[] = ['Labour Charge','Total','Advance','Balance','Duty'];
			
				$datareport[] = [ 'Labour Charge' => $result[0]->other_charge  ,
								  'Total' => $result[0]->total_amount,
								  'Advance' => $result[0]->advance ,
								  'Balance' => $result[0]->balance,
								  'Duty'=>$result[0]->duty_amt
								];
									
			$datareport[] = ['','','','','','',''];
		    $datareport[] = ['','','','','','',''];
			$datareport[] = ['Documentation Details:','','','','','',''];
			$datareport[] = ['Exporter','','','Importer','','','Sila Agent','','','Batha Agent','','','Cleared ON','',''];
													
								$datareport[] = [ 'exp' => '' ,
												   'imp' => '',
												   'sila' => '',
												   'batha' =>'' ,
												   'cleared' => '',
																		  
												];				
					
					
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
	
	public function reportSearch($val) {
		
		/*$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS WB','WB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS WBE','WBE.waybill_id','=','WB.id')
								->join('cargo_receipt AS CR','CR.id','=','WBE.jobentry_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->join('consignee AS CON','CON.id','=','CR.consignee_id')
								->where('cargo_despatch_bill.deleted_at',null)
		                        ->where('cargo_despatch_bill.despatch_no',$val);
								
		$result = $query->select('CR.job_code','CON.consignee_name','CON.phone','WB.bill_no','cargo_despatch_bill.despatch_no','CR.destination',
								'CR.despatched_qty','WB.total_amount','CT.code','DT.code AS deltype','WB.instructions')
						->orderBy('cargo_despatch_bill.id','ASC')
						->get(); */
						
		//echo '<pre>';print_r($result);exit;
		$result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						->join('cargo_despatch_entry AS CDE','CDE.billentry_id','=','cargo_waybill.id')
						//->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
						->join('cargo_despatch_bill AS CR','CR.id','=','CDE.despatch_id')
						//->where('cargo_waybill.consignee_id',$id)
						//->where('cargo_waybill.status',0)
						->where('cargo_waybill.deleted_at',null)
						->where('CR.deleted_at',null)
						->where('CR.despatch_no',$val)
						->select('cargo_waybill.bill_no','cargo_waybill.total_amount as total','cargo_waybill.instructions','cargo_waybill.loaded_qty','cargo_waybill.cargo_receipt_ids','CON.consignee_name','CON.phone','CR.*')
						->get();
	
						//echo '<pre>';print_r($result);exit;	
		$wbills = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')
			                                 ->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
			                                  ->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
											  ->whereIn('cargo_receipt.id', unserialize($row->cargo_receipt_ids))
			                                  ->select('job_code','destination','despatched_qty','CT.code AS collType','DT.code AS deltype')->get();
			$jobs = $this->sortJobs($res);
			//$des = $this->sortDes($res);
			$desptch = $this->sortDesptch($res);
			//$coll = $this->sortColl($res);
			//$del = $this->sortDel($res);
			$wbills[] = (object)[
				            'bill_no' => $row->despatch_no,
				            'jobs'	=> $jobs,
							'consignee_name'	=> $row->consignee_name,
							'phone'=>$row->phone,
							//'id' => $row->id,
							'wbill_no' => $row->bill_no,
							'destination'=>$res[0]->destination,
							'despatch'=>$row->loaded_qty,
							'coll_type'	=> $res[0]->collType,
							'del_type'	=> $res[0]->deltype,
							'remarks'	=> $row->instructions,
							'total_amount'	=> $row->total
						];
		}
	

		
		return view('body.cargodespatchbill.report-seach')
						->withResults($result)
						->withWaybills($wbills);
	}
	
	public function despatchList() {
		
		return view('body.cargodespatchbill.list');
	}
	
	public function ajaxPagingList(Request $request)
	{
		$columns = array( 
                            0 => 'cargo_despatch_bill.id', 
                            1 => 'despatch_no',
                            2 => 'despatch_date',
							3 => 'clear_agent',
                            4 => 'vehicle_no',
                            5 => 'driver',
                            6 => 'status_date',
							7 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getCargoDespatchbillList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		if($search)
			$totalFiltered = $totalData = $this->getCargoDespatchbillList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getCargoDespatchbillList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cargo_despatchbill/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print =  '"'.url('cargo_despatchbill/add/'.$row->id).'"';
				/* if($row->status==0)
					$status = '<span class="label label-sm label-info">Pending</span>';
				else if($row->status==1)
					$status = '<span class="label label-sm label-warning">Hold</span>';
				else if($row->status==2)
					$status = '<span class="label label-sm label-success">Delivered</span>';
				else if($row->status==-1)
					$status = '<span class="label label-sm label-danger">Return</span>'; */
				
				$view =  url('cargo_despatchbill/view/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['despatch_no'] = $row->despatch_no;
				$nestedData['despatch_date'] = date('d-m-Y', strtotime($row->despatch_date));
				$nestedData['clear_agent'] = $row->clear_agent;
				$nestedData['status_date'] = ($row->status_date!=0000-00-00)?date('d-m-Y', strtotime($row->status_date)):'';
				$nestedData['vehicle_no'] = $row->vehicle_no;
				$nestedData['driver'] = $row->driver;
				$nestedData['status'] = '<span class="label label-sm label-info">'.$row->dstatus.'</span>';
				$nestedData['view'] = "<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-eye'></i></a></p>";
												
				$nestedData['setstatus'] = "<button class='btn btn-primary btn-sm setStatus' data-id='{$row->id}' data-toggle='modal' data-target='#status_modal'>SET STATUS</button>";

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
	
	public function getStatusForm($id,$type) {
		
		$statuslist = DB::table('cargo_status')->where('type',$type)->where('deleted_at',null)->get();
		if($type==1)
			$row = DB::table('cargo_despatch_bill')->where('id',$id)->select('status_id')->first();
		else
			$row = DB::table('cargo_waybill')->where('id',$id)->select('status_id')->first();
		
		return view('body.cargodespatchbill.statusform')
					->withId($id)
					->withType($type)
					->withStatus($row->status_id)
					->withStatuslist($statuslist);
		
	}
	
	public function uploadAttachment(Request $request)
	{	
		$res = $this->ajax_upload_attachment($request->attachment);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function ajax_upload_attachment($file)
	{ 
		$photo = '';
		$fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		if($file) {
			$ext = $file->getClientOriginalExtension();
			if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
				$photo = $fname.'_'.rand(1, 999).'.'.$ext;
				$destinationPath = public_path() . $this->imgDirC.'/'.$photo;
				$destinationPathThumb = public_path() . $this->imgDirC.'/thumb_'.$photo;

				// resizing an uploaded file
				Image::make($file->getRealPath())->resize($this->widthC, $this->heightC, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Image::make($file->getRealPath())->resize($this->thumbWidthC, $this->thumbHeightC, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = $fname.'_'.rand(1, 999).'.'.$ext;
				 $destinationPath = public_path() . $this->imgDirC;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	
	public function saveStatus(Request $request) {
		
		$row = DB::table('cargo_status')->where('id',$request->get('status'))->select('is_reached')->first();
		if($request->get('type')==1) {
			DB::table('cargo_despatch_bill')->where('id',$request->get('id'))->update(['status' => $row->is_reached, 'status_id' => $request->get('status'), 'status_date'=> date('Y-m-d', strtotime($request->get('status_date'))),'attachment' => $request->get('attachment')]);
			DB::table('cargo_despatch_status_log')
							->insert(['despatch_id' => $request->get('id'), 
									  'status_id' => $request->get('status'),
									  'date'      => date('Y-m-d', strtotime($request->get('status_date'))),
									  'created_at' => date('Y-m-d H:i:s'),
									  'created_by' => Auth::User()->id
									  ]);
		} elseif($request->get('type')==2) {
			DB::table('cargo_waybill')->where('id',$request->get('id'))->update(['is_reached' => $row->is_reached,'status_id' => $request->get('status'),'status_date'=> date('Y-m-d', strtotime($request->get('status_date')))]);
			DB::table('cargo_waybill_status_log')
							->insert(['waybill_id' => $request->get('id'), 
									  'status_id' => $request->get('status'),
									  'date'      => date('Y-m-d', strtotime($request->get('status_date'))),
									  'created_at' => date('Y-m-d H:i:s'),
									  'created_by' => Auth::User()->id
									  ]);
		}
		
	}
	
	public function view($id) { 
		
		$drow = DB::table('cargo_despatch_bill')
		                 ->leftjoin('cargo_status AS CS','CS.id','=','cargo_despatch_bill.status_id')
		                 ->where('cargo_despatch_bill.id',$id)
						->select('cargo_despatch_bill.*','CS.name AS dstatus')
						->first();
						
		$result = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						//->where('cargo_waybill.consignee_id',$drow->consignee_id)
						//->where('cargo_waybill.status',0)
						->whereIn('cargo_waybill.id',unserialize($drow->cargo_waybill_ids))
						->where('cargo_waybill.deleted_at',null)
						->select('cargo_waybill.*','CON.consignee_name')
						->get();
						//echo '<pre>';print_r($drow);exit;
		$wbills = null;
		foreach($result as $row) {
			$res = DB::table('cargo_receipt')->whereIn('id', unserialize($row->cargo_receipt_ids))->select('job_code')->get();
			$jobs = $this->sortJobs($res);
			
			$wbills[] = (object)[
							'id' => $row->id,
							'bill_no' => $row->bill_no,
							'bill_date'	=> $row->bill_date,
							'consignee_name'	=> $row->consignee_name,
							'jobs'	=> $jobs,
							'vehicle_no'	=> $row->vehicle_no,
							'driver'	=> $row->driver,
							'total_amount'	=> $row->total_amount
						];
		}
						
		return view('body.cargodespatchbill.view')
					->withBills($wbills)
					->withRow($drow);

	}
	
	public function waybillList() {
		
		return view('body.cargodespatchbill.waybilllist');
	}
	
	public function ajaxPagingWbillList(Request $request)
	{
		$columns = array( 
                            0 => 'cargo_despatch_bill.id', 
                            1 => 'despatch_no',
                            2 => 'bill_no',
							3 => 'amount',
                            4 => 'coltype',
                            5 => 'deltype',
							7 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getCargoWayBillList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		if($search)
			$totalFiltered = $totalData = $this->getCargoWayBillList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getCargoWayBillList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cargo_despatchbill/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print =  '"'.url('cargo_despatchbill/add/'.$row->id).'"';
				/* if($row->status==0)
					$status = '<span class="label label-sm label-info">Pending</span>';
				else if($row->status==1)
					$status = '<span class="label label-sm label-warning">Hold</span>';
				else if($row->status==2)
					$status = '<span class="label label-sm label-success">Delivered</span>';
				else if($row->status==-1)
					$status = '<span class="label label-sm label-danger">Return</span>'; */
				
				$view =  url('cargo_despatchbill/view/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['despatch_no'] = $row->despatch_no;
				$nestedData['bill_no'] = $row->bill_no;
				$nestedData['amount'] = $row->total_amount;
				$nestedData['deltype'] = $row->delivery_type;
				$nestedData['coltype'] = $row->collection_type;
				$nestedData['status'] = '<span class="label label-sm label-info">'.$row->wbstatus.'</span>';
				$nestedData['view'] = '';//"<p><a href='{$view}' class='btn btn-info btn-xs' target='_blank'><i class='fa fa-fw fa-eye'></i></a></p>";
												
				$nestedData['setstatus'] = "<button class='btn btn-primary btn-sm setStatus' data-id='{$row->id}' data-toggle='modal' data-target='#status_modal'>SET STATUS</button>";

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
	
	private function getCargoWayBillList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('cargo_despatch_bill')
								->join('cargo_despatch_entry AS CDE','CDE.despatch_id','=','cargo_despatch_bill.id')
								->join('cargo_waybill AS CWB','CWB.id','=','CDE.billentry_id')
								->join('cargo_waybill_entry AS CWE','CWE.waybill_id','=','CWB.id')
								->join('cargo_receipt AS CR','CR.id','=','CWE.jobentry_id')
								->leftjoin('cargo_status AS CS','CS.id','=','CWB.status_id')
								->join('collection_type AS CT','CT.id','=','CR.collection_type')
								->join('delivery_type AS DT','DT.id','=','CR.delivery_type')
								->where('cargo_despatch_bill.deleted_at',null)
								->where('CWB.deleted_at',null)
								->where('CR.deleted_at',null)
								->where('cargo_despatch_bill.status',1)
								->where('CWB.is_reached',0);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->Where('cargo_despatch_bill.despatch_no','LIKE',"%{$search}%");
					$query->orWhere('CWB.bill_no','LIKE',"%{$search}%");
					//$query->orWhere('cargo_despatch_bill.status',$search);
				});
			}
			
		$query->select('CWB.id','cargo_despatch_bill.despatch_no','CWB.bill_no','CWB.total_amount','CR.job_code',
						'CS.name AS wbstatus','DT.description AS delivery_type','CT.description AS collection_type');
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
}


