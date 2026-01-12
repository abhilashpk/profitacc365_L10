<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Config;
use Auth;
use File;
use Excel;

class CargoReceiptController extends Controller
{

	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->widthC = $config['modules']['cargoentry']['image_size']['width'];
        $this->heightC = $config['modules']['cargoentry']['image_size']['height'];
        $this->thumbWidthC = $config['modules']['cargoentry']['thumb_size']['width'];
        $this->thumbHeightC = $config['modules']['cargoentry']['thumb_size']['height'];
        $this->imgDirC = $config['modules']['cargoentry']['image_dir'];
		
	}
	
	public function index() {
				
		return view('body.cargoreceipt.index');
	}
	
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'cargo_receipt.id', 
                            1 => 'jobcode',
                            2 => 'jobdate',
							3 => 'time',
							4 => 'consignee',
                            5 => 'shipper',
                            6 => 'recvdqty',
                            7 => 'dspedqty',
                            8 => 'balanceqty',
                            9 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getCargoRecList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','CR')
							->select('report_view_detail.name','report_view_detail.id','report_view_detail.print_name')
							->get();
		if($search)
			$totalFiltered = $totalData = $this->getCargoRecList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getCargoRecList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('cargo_receipt/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$return =  'funReturn("'.$row->id.'")';
				$print = url('cargo_receipt/print/'.$row->id);
				$prnt = url('cargo_receipt/preprint/'.$row->id);
				$return_status=DB::table('cargo_receipt')->where('cargo_receipt.id',$row->id)->select('status')->get();
				$ret=$return_status[0]->status;
				$retsat=($ret==-1)?'Returned':'Return';
                $nestedData['id'] = $row->id;
                $nestedData['jobcode'] = $row->job_code;
				$nestedData['jobdate'] = date('d-m-Y', strtotime($row->job_date));
				$nestedData['time'] = date('H:i:A',strtotime($row->created_at));
				$nestedData['consignee'] = $row->consignee_name;
				$nestedData['shipper'] = $row->shipper_name;
				$nestedData['dspedqty'] = $row->despatched_qty;
				$nestedData['Balncqty'] =  ($row->packing_qty-($row->loaded_pack_qty));
				$nestedData['status']  =  "<button class='btn btn-primary btn-xs getSts' data-toggle='modal' data-target='#status_modal' data-id='{$row->id}'>Status</button>";
				$nestedData['return'] = "<button class='btn btn-primary btn-xs getSts' onClick='{$return}'>$retsat</button>";
				$nestedData['packqty'] = $row->packing_qty;
				$nestedData['balanceqty'] = $row->packing_qty - $row->despatched_qty;
				$opts = '';					
				foreach($prints as $doc) {
				//	echo '<pre>';print_r($doc);exit;
					$opts .= "<li role='presentation'><a href='{$prnt}/".$doc->id."' target='_blank' role='menuitem'>".$doc->name."</a></li>";
				}

				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
			//	if($row->collection_type=='PP')								
					$nestedData['print'] = "<p><a href='{$print}' target='_blank'  role='menuitem' class='btn btn-primary btn-xs'><span class='fa fa-fw fa-print'></span></a></p>";
				//else								
				//	$nestedData['print'] = '';
				$nestedData['preprint'] = "<div class='btn-group drop_btn' role='group'>
											<button type='button' class='btn btn-primary btn-xs dropdown-toggle m-r-50'
													id='exampleIconDropdown1' data-toggle='dropdown' aria-expanded='false'>
												<i class='fa fa-fw fa-print' aria-hidden='true'></i><span class='caret'></span>
											</button>
											<ul style='min-width:100px !important;' class='dropdown-menu' aria-labelledby='exampleIconDropdown1' role='menu'>
												".$opts."
											</ul>
										</div>";
				
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
	
	private function getCargoRecList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('cargo_receipt')
								->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
								->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
								->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
								->leftjoin('cargo_waybill AS CW','CW.bill_no','=','cargo_receipt.wbill_no')
								->where('cargo_receipt.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					if($search=="Closed")
						$query->orWhere('cargo_receipt.status',1);
					else if($search=="Open")
						$query->orWhere('cargo_receipt.status',0);
					else if($search=="Return")
						$query->orWhere('cargo_receipt.status',-1);	
					else {
						$query->where('CON.consignee_name','LIKE',"%{$search}%");
						$query->orWhere('SHP.shipper_name','LIKE',"%{$search}%");
						$query->orWhere('cargo_receipt.job_code','LIKE',"%{$search}%");
					}
				});
			}
			
		$query->select('cargo_receipt.id','cargo_receipt.job_code','cargo_receipt.job_date','cargo_receipt.created_at','cargo_receipt.received_qty','cargo_receipt.packing_qty','cargo_receipt.total_charge','cargo_receipt.balance',
						'CON.consignee_name','SHP.shipper_name','CT.code AS collection_type','cargo_receipt.despatched_qty','CW.bill_no','CW.loaded_qty','CW.loaded_pack_qty','CW.despatch_no');
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->groupBy('cargo_receipt.id')->get();
			else
				return $query->count();
	}
	
	
	public function add() {

		$cVoucher = DB::table('voucher_no')->where('status',1)->where('voucher_type','CJ')->select('id','no')->first();
		$dtype = DB::table('delivery_type')->where('deleted_at',null)->select('id','description','code')->get();
		$ctype = DB::table('collection_type')->where('deleted_at',null)->select('id','description','code')->get();
		$ptype = DB::table('units')->where('deleted_at','0000-00-00 00:00:00')->where('status',1)->select('id','description')->get();
		$lastid = DB::table('cargo_receipt')->where('deleted_at',null)->select('id')->orderBy('id','DESC')->first();
		$consignee = DB::table('consignee')->where('deleted_at',null)->get();
		$shipper = DB::table('shipper')->where('deleted_at',null)->get();
		//echo '<pre>';print_r($lastid);exit;
		return view('body.cargoreceipt.add')
					->withDtype($dtype)
					->withCtype($ctype)
					->withPtype($ptype)
					->withVoucher($cVoucher)
					->withConsignee($consignee)
					->withShipper($shipper)
					->withprintid($lastid);
	}
	
	public function save(Request $request) {
		
		DB::beginTransaction();
		try {
			$id = DB::table('cargo_receipt')
					->insertGetId([
						'job_code' => '',//$request->get('job_code'),
						'job_date' => date('Y-m-d', strtotime($request->get('job_date'))),
						'consignee_id' => $request->get('consignee_id'),
						'shipper_id'	=> $request->get('shipper_id'),
						'received_qty'	=> $request->get('rcvd_quantity'),
						'packing_qty'	=> $request->get('rcvd_packing'),
						'packing_type'	=> serialize($request->get('packing_type')),
						'rate_unit'	=> $request->get('rate_unit'),
						'consignee_code'	=> $request->get('consignee_code'),
						'weight'	=> $request->get('weight'),
						'volume'	=> $request->get('volume'),
						'destination'	=> $request->get('destination'),
						'delivery_type'	=> $request->get('delivery_type'),
						'trans_type'	=> $request->get('trans_type'),
						'remarks'	=> $request->get('remarks'),
						'collection_type'	=> $request->get('collection_type'),
						'rate'	=> $request->get('rate'),
						'coll_charge'	=> $request->get('col_charge'),
						'other_charge'	=> $request->get('otr_charge'),
						'total_charge'	=> $request->get('total_charge'),
						'amt_received'	=> $request->get('amt_received'),
						'balance'	=> $request->get('balance'),
						'is_lumpsum'	=> $request->get('is_lumpsum'),
						'shippers_mob'	=> $request->get('shippers_mob'),
						'shippers_vehno'	=> $request->get('shippers_vehno'),
						'invoice_nos'	=> $request->get('invoice_nos'),
						'created_at'	=> date('Y-m-d H:i:s'),
						'created_by'	=> Auth::User()->id,
						'salesman_id'	=> $request->get('salesman')
					]);
				
				if($id!='') {
				    $vid=DB::table('voucher_no')->where('voucher_type','CJ')->where('status',1)->select('no')->first();
					DB::table('cargo_receipt')->where('id',$id)->update(['job_code' => $vid->no]);
				    
					foreach($request->get('attachments') as $key => $val) {
						if($val!='') {
							DB::table('cargo_attachment')
									->insert(['cargo_receipt_id' => $id, 
											  'file_name' => $val
											]);
						}
						
					}
				}
				
			DB::table('voucher_no')->where('voucher_type','CJ')->update(['no' => DB::raw('no + 1') ]);
				
			DB::commit();
			Session::flash('message', 'Cargo entry added successfully.');
			return redirect('cargo_receipt/add');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_receipt/add')->withErrors($e->getErrors());
		}
	}
	
	public function getConsignee()
	{
		$consignee = DB::table('consignee')->where('deleted_at',null)->get();
		return view('body.cargoreceipt.consignee')
						->withConsignee($consignee);
	}
	
	public function getShipper()
	{
		$shipper = DB::table('shipper')->where('deleted_at',null)->get();
		return view('body.cargoreceipt.shipper')
						->withShipper($shipper);
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
	
	public function getFileform() {
		
		return view('body.cargoreceipt.fileform')
					->withNo(Input::get('no'));
				
	}
	
	public function edit($id) { 

		$row = DB::table('cargo_receipt')
						->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
						->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
						->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
						->leftJoin('salesman AS S','S.id','=','cargo_receipt.salesman_id')
						->where('cargo_receipt.id',$id)
						->select('cargo_receipt.*','CON.consignee_name','SHP.shipper_name','CT.description AS collection_type','S.name AS salesman')
						->first();
		
		$dtype = DB::table('delivery_type')->where('deleted_at',null)->select('id','description','code')->get();
		$ctype = DB::table('collection_type')->where('deleted_at',null)->select('id','description','code')->get();
		$ptype = DB::table('units')->where('deleted_at','0000-00-00 00:00:00')->where('status',1)->select('id','description')->get();
		$attachments = DB::table('cargo_attachment')->where('cargo_receipt_id', $id)->get();
        $lastid=DB::table('cargo_receipt')->where('status',1)->select('id')->orderBy('id','DESC')->first();
		$consignee = DB::table('consignee')->where('deleted_at',null)->select('id','consignee_name')->get();
		$shipper = DB::table('shipper')->where('deleted_at',null)->select('id','shipper_name')->get();
		//echo '<pre>';print_r($);exit;
		return view('body.cargoreceipt.edit')
					->withDtype($dtype)
					->withCtype($ctype)
					->withPtype($ptype)
					->withAttachments($attachments)
					->withRow($row)
					->withConsignee($consignee)
					->withShipper($shipper)
					->withPrintid($lastid);

	}
	
	public function update(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			DB::table('cargo_receipt')
					->where('id', $id)
					->update([
						'job_date' => date('Y-m-d', strtotime($request->get('job_date'))),
						'consignee_id' => $request->get('consignee_id'),
						'shipper_id'	=> $request->get('shipper_id'),
						'received_qty'	=> $request->get('rcvd_quantity'),
						'packing_qty'	=> $request->get('rcvd_packing'),
						'packing_type'	=> serialize($request->get('packing_type')),
						'rate_unit'	=> $request->get('rate_unit'),
						'consignee_code'	=> $request->get('consignee_code'),
						'weight'	=> $request->get('weight'),
						'destination'	=> $request->get('destination'),
						'delivery_type'	=> $request->get('delivery_type'),
						'remarks'	=> $request->get('remarks'),
						'collection_type'	=> $request->get('collection_type'),
						'trans_type'	=> $request->get('trans_type'),
						'rate'	=> $request->get('rate'),
						'coll_charge'	=> $request->get('col_charge'),
						'other_charge'	=> $request->get('otr_charge'),
						'total_charge'	=> $request->get('total_charge'),
						'amt_received'	=> $request->get('amt_received'),
						'balance'	=> $request->get('balance'),
						'is_lumpsum'	=> $request->get('is_lumpsum'),
						'shippers_mob'	=> $request->get('shippers_mob'),
						'shippers_vehno'	=> $request->get('shippers_vehno'),
						'invoice_nos'	=> $request->get('invoice_nos'),
						'modify_at'	=> date('Y-m-d H:i:s'),
						'modify_by'	=> Auth::User()->id,
						'salesman_id'	=> $request->get('salesman')
					]);
				
					foreach($request->get('attachments') as $key => $val) {
						if($val!='') {
							DB::table('cargo_attachment')
									->insert(['cargo_receipt_id' => $id, 
											  'file_name' => $val
											]);
						}
						
					}
					
					$ids = explode(',',$request->get('remove_ids'));
					if($ids) {
						$files = DB::table('cargo_attachment')->whereIn('id', $ids)->get();  
						foreach($files as $frow) {
							if(File::exists(public_path('uploads/cargoentry/'.$frow->file_name))) {
								File::delete(public_path('uploads/cargoentry/'.$frow->file_name));
							}
						}
						DB::table('cargo_attachment')->whereIn('id', $ids)->delete();
					}
				
			DB::commit();
			Session::flash('message', 'Cargo entry updated successfully');
			return redirect('cargo_receipt');
			
		} catch(\Exception $e) {
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return Redirect::to('cargo_receipt/edit/'.$id)->withErrors($e->getErrors());
		}
		
		
	}
	
	public function destroy($id)
	{
		DB::table('cargo_receipt')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s'), 'deleted_by' => Auth::User()->id ]);
		Session::flash('message', 'Cargo entry deleted successfully.');
		return redirect('cargo_receipt');
	}
	public function return($id)
	{
	    $return_status=DB::table('cargo_receipt')->where('id',$id)->select('status')->get();
		$ret=$return_status[0]->status;
		if($ret==-1){
		//	alert('Cargo entry is dispatched' );
			Session::flash('message', 'Cargo entry is dispatched.');
		}
		else{
		DB::table('cargo_receipt')->where('id',$id)->update(['status' => -1]);
		Session::flash('message', 'Cargo entry return successfully.');
		}
		return redirect('cargo_receipt');
	}
	public function getPreprint($id,$rid=null)
	{ 
		$viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first();
		//echo '<pre>';print_r($viewfile);exit;
		$path = app_path() . '/stimulsoft/helper.php';
			if(isset($viewfile))
				return view('body.cargoreceipt.viewer')->withPath($path)->withView($viewfile->print_name);
	}
	public function getPrint($id)
	{ 
		$voucherhead = 'CONSIGNMENT';
		$row = DB::table('cargo_receipt')
						->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
						->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
						->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
						->join('delivery_type AS DT','DT.id','=','cargo_receipt.delivery_type')
						->where('cargo_receipt.id',$id)
						->select('cargo_receipt.*','CON.consignee_name','CON.phone As consignee_mobile','CON.alter_phone As consignee_tele','CON.address As consignee_city','SHP.shipper_name','SHP.phone As shipper_mobile','SHP.address As shipper_city','CT.code AS collection_type','DT.code AS delivery_type')
						->first();			
		$pktype=unserialize($row->packing_type)	;
		$ptypes = DB::table('units')->whereIn('id',$pktype)->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
		$ptype = '';
		foreach($ptypes as $pt) {
			$ptype .= ($ptype=='')?$pt->description:','.$pt->description;
		}
        $scode =$row->salesman_id;
		$salescode = DB::table('salesman')->where('deleted_at','0000-00-00 00:00:00')->where('id',$scode)->select('salesman_id','name')->first();
		
						//echo '<pre>';print_r($ret);exit;
		return view('body.cargoreceipt.print')
					->withVoucherhead($voucherhead)
					->withRow($row)
					->withPtype($ptype)
					->withSalescode($salescode);
		
	}

	public function getPrintRecepit($id)
	{ 
		$voucherhead = 'RECEIPT VOUCHER Ø³Ù†Ø¯Ùƒ Ù‚Ø¨Ø¶	';
		$row=DB::table('cargo_receipt')
		        ->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
		          ->where('cargo_receipt.id',$id)
				  ->select('cargo_receipt.*','CON.consignee_name')
				  ->first();
				  //echo '<pre>';print_r($row);exit;
		return view('body.cargoreceipt.printreceipt')
		          ->withVoucherhead($voucherhead)
		          ->withRow($row);	  
	}
	
	public function getDestination()
	{
		$destination = DB::table('cargo_destination')->where('deleted_at',null)->get();
		return view('body.cargoreceipt.destination')
						->withDestination($destination);
	}
	
	public function getSalesman()
	{
		$salesman = DB::table('salesman')->where('deleted_at',null)->get();
		return view('body.cargoreceipt.salesman')
						->withSalesman($salesman);
	}
	public function createConsignee() {
		$consignee = DB::table('consignee')->where('deleted_at',null)->get();
		return view('body.cargoreceipt.consigneenew')
						->withConsignee($consignee);
	
	}
	public function createShipper() {
		
		return view('body.cargoreceipt.shippernew');
						
	
	}
	
	public function getRate(Request $request) {
		
		$res = DB::table('cargo_receipt')->where('consignee_id', $request->get('cid'))
					->where('packing_type', serialize([$request->get('uid')]))
					->select('rate')
					->orderBy('id','DESC')
					->first();
		if($res)
			echo $res->rate;
		
	}

	public function getRateHistory($consignee_id)
	{
		$rate = DB::table('cargo_receipt')->where('consignee_id',$consignee_id )
		                           ->select('job_code','job_date','rate','packing_type','rate_unit')
								   ->orderBy('id','DESC')
								   ->limit(5)
								   ->get();
	     $pktype = null;
		
		foreach($rate as $rt){
        //$pktypes = DB::table('units')->whereIn('id',unserialize($rt->packing_type))->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
         $pktypes = DB::table('units')->where('id',$rt->rate_unit)->where('deleted_at','0000-00-00 00:00:00')->select('description')->get();
		$ptypes=$this->sortUnit($pktypes);
		
		$pktype[]=(object)[
			               'ptypes'=>$ptypes,
			               'job_code'=>$rt->job_code,
						   'job_date'=>date('d-m-Y',strtotime($rt->job_date)),
						   'rate'=>$rt->rate,

		                   ];	
	}				   
								  // echo '<pre>';print_r($pktypes);exit;
				return view('body.cargoreceipt.history')
								   ->withRate($rate)
								   ->withPktype($pktype);
	}
	private function sortUnit($ptypes) {
		
		$ptype = '';
		foreach($ptypes as $pt) {
			$ptype .= ($ptype=='')?$pt->description:','.$pt->description;
		}
		return $ptype;
	}
	
	public function getStatus($id) {
		
	/*	$row = DB::table('cargo_receipt')
								->leftjoin('cargo_waybill AS CW','CW.bill_no','=','cargo_receipt.wbill_no')
								->where('cargo_receipt.id',$id)
								->select('CW.bill_no','CW.loaded_qty','CW.despatch_no','cargo_receipt.job_code')
								->get(); 
							
		$res = null;						
		if($row) {
			$res = DB::table('cargo_despatch_bill')
						->join('cargo_despatch_entry AS CD','CD.despatch_id','=','cargo_despatch_bill.id')
						->join('cargo_waybill AS CW','CW.id','=','CD.billentry_id')
						->join('cargo_receipt AS CR','CR.wbill_no','=','CW.bill_no')
						->wherein('CR.job_code',$row->job_code)
						->where('cargo_despatch_bill.despatch_no',$row->despatch_no)
						->select('cargo_despatch_bill.despatch_no','CW.bill_no','CW.loaded_qty')
						->get();
						
		}*/
					
		$res = DB::table('cargo_waybill_entry')	->where('cargo_waybill_entry.jobentry_id',$id)
					->join('cargo_waybill AS CW','CW.id','=','cargo_waybill_entry.waybill_id')
					->join('cargo_receipt AS CR','CR.id','=','cargo_waybill_entry.jobentry_id')
				->where('CW.deleted_at',null)
					->select('CW.despatch_no','CW.bill_no','cargo_waybill_entry.loaded_qty' ,'cargo_waybill_entry.loaded_pack_qty')
					->get();	
					//echo '<pre>';print_r($res);exit;
		return view('body.cargoreceipt.status')->withRow($res);
	}
	
	public function report(Request $request) {
		//echo '<pre>';print_r($request->all());exit;
		$date_from = ($request->get('date_from')!='')?date('d-m-Y', strtotime($request->get('date_from'))):'';
		$date_to = ($request->get('date_to')!='')?date('d-m-Y', strtotime($request->get('date_to'))):'';
		$search_type=$request->get('search_type');
		
		$query = DB::table('cargo_receipt')
								->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
								->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
								->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
								->leftjoin('cargo_waybill AS CW','CW.bill_no','=','cargo_receipt.wbill_no')
								->where('cargo_receipt.deleted_at',null);
		
		if( $date_from!='' && $date_to!='' ) { 
			$query->whereBetween('cargo_receipt.job_date', [date('Y-m-d',strtotime($request->get('date_from'))), date('Y-m-d',strtotime($request->get('date_to')))]);
		}
		
		if( $request->get('search_type')!='' && ($request->get('search_type')==1 || $request->get('search_type')==0 || $request->get('search_type')==-1)) { 
			$query->where('cargo_receipt.status', $request->get('search_type'));
		}
			
		$result = $query->select('cargo_receipt.id','cargo_receipt.job_code','cargo_receipt.job_date','cargo_receipt.created_at','cargo_receipt.received_qty','cargo_receipt.packing_qty','cargo_receipt.total_charge','cargo_receipt.balance',
						'CON.consignee_name','SHP.shipper_name','CT.description AS collection_type','cargo_receipt.despatched_qty','CW.bill_no','CW.loaded_qty','CW.loaded_pack_qty','CW.despatch_no')
						->get();
						
		//echo '<pre>';print_r($result);exit;
		return view('body.cargoreceipt.report')
						->withDatef($date_from)
						->withDatet($date_to)
						->withSearchtype($search_type)
						->withResults($result);
	}

	public function dataExport(Request $request){
		//echo '<pre>';print_r($request->all());exit;
		$date_from = ($request->get('date_from')!='')?date('d-m-Y', strtotime($request->get('date_from'))):'';
		$date_to = ($request->get('date_to')!='')?date('d-m-Y', strtotime($request->get('date_to'))):'';
		
		$query = DB::table('cargo_receipt')
								->join('consignee AS CON','CON.id','=','cargo_receipt.consignee_id')
								->join('shipper AS SHP','SHP.id','=','cargo_receipt.shipper_id')
								->join('collection_type AS CT','CT.id','=','cargo_receipt.collection_type')
								->leftjoin('cargo_waybill AS CW','CW.bill_no','=','cargo_receipt.wbill_no')
								->where('cargo_receipt.deleted_at',null);
		
		if( $date_from!='' && $date_to!='' ) { 
			$query->whereBetween('cargo_receipt.job_date', [date('Y-m-d',strtotime($request->get('date_from'))), date('Y-m-d',strtotime($request->get('date_to')))]);
		}
		
		if( $request->get('search_type')==1 || $request->get('search_type')==0 || $request->get('search_type')==-1) { 
			$query->where('cargo_receipt.status', $request->get('search_type'));
		}
			
		$result = $query->select('cargo_receipt.id','cargo_receipt.job_code','cargo_receipt.job_date','cargo_receipt.created_at','cargo_receipt.received_qty','cargo_receipt.total_charge','cargo_receipt.balance',
						'CON.consignee_name','SHP.shipper_name','CT.description AS collection_type','cargo_receipt.despatched_qty','CW.bill_no','CW.loaded_qty','CW.despatch_no')
						->get();
		//echo '<pre>';print_r($result);exit;
		$voucherhead='Cargo Receipt Report';
		$datareport[] = ['','','','',strtoupper(Session::get('company')),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','',strtoupper($voucherhead),'','',''];	
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Date.  From:',$date_from,'To:',$date_to,'',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['','','','','','',''];
			$datareport[] = ['Cons.No:','Cons.Date','Consinee','Shipper','Rcvd.Qty','Dspchd.Qty.','Bal.Qty.'];	
			
			
			foreach($result as $row) {
				
				$datareport[] = [ 'Cons.No.' => $row->job_code  ,
								  'Cons.Date' => date('d-m-Y', strtotime($row->job_date)),
								  'Consinee' =>  $row->consignee_name,
								  'Shipper' => $row->shipper_name,
								  'Rcvd.Qty' =>  $row->received_qty ,
								  'Dspchd.Qty.'=> $row->despatched_qty,
								  'Bal.Qty.'=>($row->received_qty-($row->loaded_qty)) 
								];
								
							}
				$datareport[] = ['','','','','','',''];			
				
			Excel::create($voucherhead, function($excel) use ($datareport,$voucherhead) {
	
									// Set the spreadsheet title, creator, and description
									$excel->setTitle($voucherhead);
									$excel->setCreator('Profit ACC 365 - ERP')->setCompany(Session::get('company'));
									$excel->setDescription($voucherhead);
						
									// Build the spreadsheet, passing in the payments array
									$excel->sheet('sheet1', function($sheet) use ($datareport) {
										$sheet->fromArray($datareport, null, 'A1', false, false);
									});
						
								})->download('xlsx');					
	}
}


