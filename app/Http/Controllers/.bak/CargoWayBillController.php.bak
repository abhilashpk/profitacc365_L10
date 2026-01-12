<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;

class CargoWayBillController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
				
		return view('body.cargowaybill.index');
	}
	
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'cargo_waybill.id', 
                            1 => 'bill_no',
                            2 => 'bill_date',
							3 => 'consignee',
                            4 => 'qty',
                            5 => 'dno'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getCargoWaybillList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		if($search)
			$totalFiltered = $totalData = $this->getCargoWaybillList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getCargoWaybillList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cargo_waybill/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('cargo_waybill/print/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['bill_no'] = $row->bill_no;
				$nestedData['bill_date'] = date('d-m-Y', strtotime($row->bill_date));
				$nestedData['consignee'] = $row->consignee_name;
				$nestedData['amount'] = $row->total_amount;
				$nestedData['loadedqty'] = $row->loaded_pack_qty;
				$nestedData['despatchno'] = $row->despatch_no;
				$nestedData['print'] = "<p><a href='{$print}' target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				
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
	
	private function getCargoWaybillList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('cargo_waybill')
								->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
								->where('cargo_waybill.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					if($search=="Closed")
						$query->orWhere('cargo_waybill.status',1);
					else if($search=="Open")
						$query->orWhere('cargo_waybill.status',0);
					else {
						$query->where('CON.consignee_name','LIKE',"%{$search}%");
						$query->orWhere('cargo_waybill.bill_no','LIKE',"%{$search}%");
						$query->orWhere('cargo_waybill.vehicle_no','LIKE',"%{$search}%");
						$query->orWhere('cargo_waybill.driver','LIKE',"%{$search}%");
					}
				});
			}
			
		$query->select('cargo_waybill.id','cargo_waybill.bill_no','cargo_waybill.bill_date','cargo_waybill.total_amount','cargo_waybill.vehicle_no',
						'cargo_waybill.driver','CON.consignee_name','cargo_waybill.loaded_qty','cargo_waybill.loaded_pack_qty','cargo_waybill.despatch_no');
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	
	public function add() {

		$cVoucher = DB::table('voucher_no')->where('status',1)->where('voucher_type','CWB')->select('id','no')->first();
		$lastid=DB::table('cargo_waybill')->where('deleted_at',null)->select('id')->orderBy('id','DESC')->first();
		$consignee = DB::table('consignee')->where('deleted_at',null)->get();

		return view('body.cargowaybill.add')
					->withVoucher($cVoucher)
					->withConsignee($consignee)
					->withprintid($lastid);
	}
	
	public function save(Request $request) {
		//echo '<pre>';print_r(Input::all());exit;
		DB::beginTransaction();
		try {
			$id = DB::table('cargo_waybill')
					->insertGetId([
						'bill_no' => '',//$request->get('billno'),
						'bill_date' => date('Y-m-d', strtotime($request->get('bill_date'))),
						'consignee_id' => $request->get('consignee_name'),
						'vehicle_no'	=> $request->get('vehicle_no'),
						'driver'	=> $request->get('driver'),
						'mob_uae'	=> $request->get('mob_uae'),
						'mob_ksa'	=> $request->get('mob_ksa'),
						'instructions'	=> $request->get('instructions'),
						'cargo_receipt_ids'	=> serialize($request->get('jobid')),
						'created_at'	=> date('Y-m-d H:i:s'),
						'created_by'	=> Auth::User()->id,
						'total_amount'	=> $request->get('total_amount'),
						'mobile'	=> $request->get('mobile')
					]);
			if($id!='') {
					$vid = DB::table('voucher_no')->where('status',1)->where('voucher_type','CWB')->select('no')->first();
					DB::table('cargo_waybill')->where('id',$id)->update(['bill_no' => $vid->no]);
					    }
			DB::table('voucher_no')->where('voucher_type','CWB')->update(['no' => DB::raw('no + 1') ]);
			
			//DB::table('cargo_receipt')->whereIn('id', $request->get('jobid'))->update(['status' =>1]);
			
			$jobarr = $request->get('jobid');
			$qtyarr = $request->get('loaded_qty');
			$packqtyarr=$request->get('loaded_pack_qty');
			$qtyTotal = 0;
			$pqtyTotal=0;
			foreach($jobarr as $k => $jrow) {
				DB::table('cargo_waybill_entry')->insert(['waybill_id' => $id, 'jobentry_id' => $jrow, 'loaded_qty' => $qtyarr[$k] ,'loaded_pack_qty' => $packqtyarr[$k] ]);
				
				$reprow = DB::table('cargo_receipt')->where('id',$jrow)->select('received_qty','despatched_qty')->first();
				$wbrow = DB::table('cargo_waybill_entry')->where('jobentry_id',$jrow)->select(DB::raw('SUM(loaded_pack_qty) AS loaded_qty'))->first();
				$status = ($reprow->received_qty - $wbrow->loaded_qty);
				$despatched_qty = $wbrow->loaded_qty;
				
				DB::table('cargo_receipt')->where('id',$jrow)->update(['despatched_qty' => $despatched_qty, 'wbill_no' => $request->get('billno'), 'status' => ($status==0)?1:0 ]);
				$qtyTotal += $qtyarr[$k];
				$pqtyTotal+= $packqtyarr[$k];
				
			}
			DB::table('cargo_waybill')->where('id',$id)->update(['loaded_qty' => $qtyTotal,'loaded_pack_qty' => $pqtyTotal]);
			
			DB::commit();
			Session::flash('message', 'Way bill added successfully.');
			return redirect('cargo_waybill/add');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_waybill/add')->withErrors($e->getErrors());
		}
	}
	
	public function getConsigneeJobs($id) {
		
		$jobs = DB::table('cargo_receipt')
						->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
						->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
						->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
						->where('cargo_receipt.consignee_id',$id)
						->where('cargo_receipt.status',0)
						->where('cargo_receipt.deleted_at',null)
						->select('cargo_receipt.*','SHP.shipper_name','CT.description AS collection_type','DT.description AS delivery_type')
						->get();
						
		return view('body.cargowaybill.conjobs')
						->withJobs($jobs);
	}
	public function getConsignee($id) {
		$cons = DB::table('consignee')->where('deleted_at',null)->where('consignee.id',$id)->first();
		return json_encode($cons);

	}
	
	public function getVehicle()
	{
		$vehicle = DB::table('cargo_vehicle')->where('deleted_at',null)->get();
		return view('body.cargowaybill.vehicle')
						->withVehicle($vehicle);
	}
	
	private function sortByJob($result) {
		$childs = array();
		foreach($result as $item)
			$childs[$item->jobentry_id] = $item;
		
		return $childs;
	}
	
	public function edit($id) { 
		
		$row = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						//->join('cargo_receipt AS CR','CR.wbill_no','=','cargo_waybill.bill_no')
						->where('cargo_waybill.id',$id)
						->select('cargo_waybill.*','CON.consignee_name','CON.address','CON.phone')
						->first();
						
		$wbentry = DB::table('cargo_waybill_entry')->where('waybill_id',$id)->select('jobentry_id','loaded_qty','loaded_pack_qty')->get();
		$wbentry = $this->sortByJob($wbentry);
		
		$jobs = DB::table('cargo_receipt')
						->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
						->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
						->leftjoin('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
						//->where('cargo_receipt.consignee_id',$row->consignee_id)
						->whereIn('cargo_receipt.id',unserialize($row->cargo_receipt_ids))
						//->where('cargo_receipt.status',1)
						->where('cargo_receipt.deleted_at',null)
						->select('cargo_receipt.*','SHP.shipper_name','CT.description AS collection_type','DT.description AS delivery_type')
						->get();
		$consignee = DB::table('consignee')->where('deleted_at',null)->select('id','consignee_name')->get();
			//echo '<pre>';print_r($wbentry);exit;			
		return view('body.cargowaybill.edit')
					->withJobs($jobs)
					->withQtyarr($wbentry)
					->withConsignee($consignee)
					->withRow($row);

	}
	
	public function update(Request $request, $id)
	{ //echo '<pre>';print_r($request->all());exit;
		DB::beginTransaction();
		try {
			DB::table('cargo_waybill')
					->where('id', $id)
					->update([
						'bill_date' => date('Y-m-d', strtotime($request->get('bill_date'))),
						'consignee_id' => $request->get('consignee_name'),
						'vehicle_no'	=> $request->get('vehicle_no'),
						'driver'	=> $request->get('driver'),
						'mob_uae'	=> $request->get('mob_uae'),
						'mob_ksa'	=> $request->get('mob_ksa'),
						'instructions'	=> $request->get('instructions'),
						'cargo_receipt_ids'	=> serialize($request->get('jobid')),
						'modify_at'	=> date('Y-m-d H:i:s'),
						'modify_by'	=> Auth::User()->id,
						'total_amount'	=> $request->get('total_amount'),
						'mobile'	=> $request->get('mobile')
					]);
				
			DB::table('cargo_receipt')->whereIn('id', unserialize($request->get('cur_jobids')))->update(['status' => 0]);
			//DB::table('cargo_receipt')->whereIn('id', $request->get('jobid'))->update(['status' => 1]);
			
			foreach(unserialize($request->get('cur_jobids')) as $jrow) {
				DB::table('cargo_waybill_entry')->where('waybill_id',$id)->where('jobentry_id',$jrow)->delete();
			}
			
			$jobarr = $request->get('jobid');
			$qtyarr = $request->get('loaded_qty');
			$packqtyarr=$request->get('loaded_pack_qty');
			$qtyTotal = 0;
			$pqtyTotal = 0;
			foreach($jobarr as $k => $row) {
				DB::table('cargo_waybill_entry')->insert(['waybill_id' => $id, 'jobentry_id' => $row, 'loaded_qty' => $qtyarr[$k] ,'loaded_pack_qty' => $packqtyarr[$k] ]);
				
				$reprow = DB::table('cargo_receipt')->where('id',$row)->select('received_qty','despatched_qty')->first();
				$wbrow=DB::table('cargo_waybill_entry')->where('jobentry_id',$row)->select(DB::raw('SUM(loaded_pack_qty) AS loaded_qty'))->first();
				$status = ($reprow->received_qty - $wbrow->loaded_qty);
				$despatched_qty = $wbrow->loaded_qty;
				
				DB::table('cargo_receipt')->where('id',$row)->update(['despatched_qty' => $despatched_qty, 'status' => ($status==0)?1:0 ]);
				$qtyTotal += $qtyarr[$k];
				$pqtyTotal+= $packqtyarr[$k];
			}
			DB::table('cargo_waybill')->where('id',$id)->update(['loaded_qty' => $qtyTotal,'loaded_pack_qty' => $pqtyTotal]);
			DB::commit();
			Session::flash('message', 'Way bill entry updated successfully');
			return redirect('cargo_waybill');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_waybill/edit/'.$id)->withErrors($e->getErrors());
		}
		
	}
	
	public function destroy($id)
	{
		DB::beginTransaction();
		try {
			$row = DB::table('cargo_waybill')->where('id',$id)->select('cargo_receipt_ids')->first();
			$jobids = unserialize($row->cargo_receipt_ids);//echo '<pre>';print_r($jobids);exit;
			DB::table('cargo_receipt')->whereIn('id',$jobids)->update(['status'=>0]);//echo '<pre>';print_r($ass);exit;
			DB::table('cargo_waybill')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::User()->id ]);
			
			foreach($jobids as $jrow) {
				DB::table('cargo_waybill_entry')->where('waybill_id',$id)->where('jobentry_id',$jrow)->delete();
			}
			
			DB::commit();
			Session::flash('message', 'Way bill entry deleted successfully.');
			return redirect('cargo_waybill');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_receipt/edit/'.$id)->withErrors($e->getErrors());
		}
	}
	
	public function getPrint($id)
	{ 
		$voucherhead = 'WAYBILL    البوليسة	';
		$row = DB::table('cargo_waybill')
						->join('consignee AS CON','CON.id','=','cargo_waybill.consignee_id')
						->join('cargo_vehicle AS CV','CV.vehicle_no','=','cargo_waybill.vehicle_no')
						->where('cargo_waybill.id',$id)
						->select('cargo_waybill.*','CON.consignee_name','CON.phone','CON.address','CON.alter_phone','CV.vehicle_name','CV.driver_id','CV.passport_no')
						->first();	
						
		$cids = unserialize($row->cargo_receipt_ids)	;
		$items = DB::table('cargo_receipt')
						->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
						->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
						->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
						->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
						->whereIn('cargo_receipt.id',$cids)
						->select('cargo_receipt.*','CON.consignee_name','CON.phone As consignee_mobile','CON.address As consignee_city','SHP.shipper_name','SHP.phone As shipper_mobile','SHP.address As shipper_city','CT.code AS collection_type','DT.code AS delivery_type')
						->get();
		
		$pktype = null;
		
		foreach($items as $item){
						$pktypes = DB::table('units')->whereIn('id',unserialize($item->packing_type))->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
						$ptypes=$this->sortUnit($pktypes);
						
						$itm[]=(object)[
										   'ptypes'=>$ptypes,
										   'job_code'=>$item->job_code,
										   'shipper'=>$item->shipper_name,
										   'received'=>$item->packing_qty,
										   'despatched'=>$item->despatched_qty,
										   'remarks'=>$item->remarks,
										   ];	
					}				   
						//echo '<pre>';print_r($row);exit;
		return view('body.cargowaybill.print')
					->withVoucherhead($voucherhead)
					->withRow($row)
					->withItm($itm)
					->withItems($items);
		
	}
	private function sortUnit($ptypes) {
		
		$ptype = '';
		foreach($ptypes as $pt) {
			$ptype .= ($ptype=='')?$pt->description:','.$pt->description;
		}
		return $ptype;
	}
}

