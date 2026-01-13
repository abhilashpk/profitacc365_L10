<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Journal\JournalInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\VoucherwiseReport\VoucherwiseReportInterface; 


use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;
use Config;
use DateTime;

class ContractBuildingController extends Controller
{
    protected $contract_building;
	protected $journal;
	public $objUtility;
	protected $receipt_voucher;
	protected $payment_voucher;
	protected $voucherwise_report;
	
	public function __construct(JournalInterface $journal,ReceiptVoucherInterface $receipt_voucher,PaymentVoucherInterface $payment_voucher,VoucherwiseReportInterface $voucherwise_report) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		
		$this->journal = $journal;
		$this->objUtility = new UpdateUtility();
		$this->receipt_voucher = $receipt_voucher;
		$this->payment_voucher = $payment_voucher;
		$this->voucherwise_report = $voucherwise_report;
		
		$this->width = $config['modules']['tenant']['image_size']['width'];
        $this->height = $config['modules']['tenant']['image_size']['height'];
        $this->thumbWidth = $config['modules']['tenant']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['tenant']['thumb_size']['height'];
        $this->imgDir = $config['modules']['tenant']['image_dir'];
        
        $this->widthC = $config['modules']['contract']['image_size']['width'];
        $this->heightC = $config['modules']['contract']['image_size']['height'];
        $this->thumbWidthC = $config['modules']['contract']['thumb_size']['width'];
        $this->thumbHeightC = $config['modules']['contract']['thumb_size']['height'];
        $this->imgDirC = $config['modules']['contract']['image_dir'];
        
	}
	
	public function index() {
		$data = array();
		$contractbuilding = [];
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();		
		//echo '<pre>';print_r($this->acsettings);exit;		
		//echo this->parameter1->contract_delete;exit;							
	//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.index')
					->withContractbuilding($contractbuilding)
					->withBuildings($buildingmaster)
					->withConsetting($this->acsettings->contract_delete)
					->withData($data);
	}
	
	public function allContractList() {
		$data = array();
		$contractbuilding = [];
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();											
	//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.allcontractlist')
					->withContractbuilding($contractbuilding)
					->withBuildings($buildingmaster)
					->withData($data);
	}
	
	
	public function enquiry() {
		$contractbuilding = [];
		$building = DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id','buildingname')->get();
		$tenants = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('master_name','id')->get();
		
		//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.enquiry')
					->withContractbuilding($contractbuilding)
					->withBuilding($building)
					->withTenants($tenants);
	}
	
	public function Report() {
		$contractbuilding = [];
		$building = DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id')->get();
		//$tenants = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('master_name','id')->get();
		
		//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.report')
					->withContractbuilding($contractbuilding)
					->withBuilding($building);
					//->withTenants($tenants);
	}
	
	public function closed() {
		$contractbuilding = [];
		$building = [];//DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id')->get();
		$tenants = [];//DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('master_name','id')->get();
		
		//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.closed')
					->withContractbuilding($contractbuilding)
					->withBuilding($building)
					->withTenants($tenants);
	}
	
	
	public function history() {
		return view('body.contractbuilding.history');
	}
	
	
	private function getContractList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								->where('contract_building.status',1)
								->where('contract_building.is_close',0)
								//->where('contract_building.renew_id',null)
								->where('contract_building.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('AM.master_name','LIKE',"%{$search}%");
					$query->orWhere('B.buildingcode','LIKE',"%{$search}%");
					$query->orWhere('F.flat_no','LIKE',"%{$search}%");
					$query->orWhere('contract_building.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','contract_building.start_date','contract_building.duration',
						'contract_building.end_date','contract_building.status','contract_building.is_close','AM.master_name','B.buildingcode','F.flat_no','contract_building.renew_id');
				
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'contract_building.id', 
                            1 => 'contract_no',
                            2 => 'contract_date',
							3 => 'customer',
                            4 => 'building_code',
                            5 => 'flat_no',
                            6 => 'duration',
                            7 => 'rent'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getContractList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		//$invoices = $this->getContractList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered = $totalData = $this->getContractList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getContractList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $del = ($row->renew_id==null&&$row->is_close==0)?0:1;
				$delete =  'funDelete("'.$row->id.'","'.$del.'")';
				
				$edit =  '"'.url('contractbuilding/edit/'.$row->id).'"';
                
				$open =  '"'.url('contractbuilding/add/'.$row->id).'"';
				$renew =  '"'.url('contractbuilding/renew/'.$row->id).'"';
                $close =  '"'.url('contractbuilding/close/'.$row->id).'"';
				$settle =  '"'.url('contractbuilding/settle/'.$row->id).'"';
				$mail =  '"'.url('contractbuilding/mail/'.$row->id).'"';
				$attach =  '"'.url('contractbuilding/attach/'.$row->id).'"';
				
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['start_date'] = date('d-m-Y', strtotime($row->start_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['building_code'] = $row->buildingcode;
				$nestedData['flat_no'] = $row->flat_no;
				
				$nestedData['exp_date'] = date('d-m-Y', strtotime($row->end_date));
				$nestedData['status'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?'<span class="btn-danger btn-xs">Expired</span>':'Occupied';
				
                $nestedData['renew'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?"<p><button class='btn btn-success btn-xs' onClick='location.href={$renew}'>
												<i class='fa fa-fw fa-refresh'></i></button></p>":'';
												
				//$nestedData['close'] = "<button class='btn btn-danger btn-xs delete' onClick='location.href={$close}'><i class='fa fa-fw fa-sign-out'></i></button>";
												
				$nestedData['settle'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$settle}'>
												<i class='fa fa-fw fa-bookmark'></i></button></p>";
												
				$nestedData['mailbtn'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$mail}'>
												<i class='fa fa-fw fa-envelope-o'></i></button></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs' onClick='location.href={$open}'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
												
				$nestedData['attach'] = "<p><a href={$attach}' class='btn btn-info btn-xs' target='_blank'><span class='glyphicon glyphicon-paperclip'></span></a></p>";
				
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
	
	private function getAllContractList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								//->where('contract_building.status',1)
								//->where('contract_building.is_close',0)
								//->where('contract_building.renew_id',null)
								->where('contract_building.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('AM.master_name','LIKE',"%{$search}%");
					$query->orWhere('B.buildingcode','LIKE',"%{$search}%");
					$query->orWhere('F.flat_no','LIKE',"%{$search}%");
					$query->orWhere('contract_building.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','contract_building.start_date','contract_building.duration',
						'contract_building.end_date','contract_building.status','contract_building.is_close','AM.master_name','B.buildingcode','F.flat_no');
				
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	
	public function ajaxAllContractList(Request $request)
	{
		$columns = array( 
                            0 => 'contract_building.id', 
                            1 => 'contract_no',
                            2 => 'contract_date',
							3 => 'customer',
                            4 => 'building_code',
                            5 => 'flat_no',
                            6 => 'duration',
                            7 => 'rent'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getAllContractList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		//$invoices = $this->getAllContractList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered = $totalData = $this->getAllContractList('count', $start, $limit, $order, $dir, $search);
			
		$invoices = $this->getAllContractList('get', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('contractbuilding/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$open =  '"'.url('contractbuilding/add/'.$row->id).'"';
				$renew =  '"'.url('contractbuilding/renew/'.$row->id).'"';
                $close =  '"'.url('contractbuilding/close/'.$row->id).'"';
				$settle =  '"'.url('contractbuilding/settle/'.$row->id).'"';
				$mail =  '"'.url('contractbuilding/mail/'.$row->id).'"';
				$attach =  '"'.url('contractbuilding/attach/'.$row->id).'"';
				
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['start_date'] = date('d-m-Y', strtotime($row->start_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['building_code'] = $row->buildingcode;
				$nestedData['flat_no'] = $row->flat_no;
				
				$nestedData['exp_date'] = date('d-m-Y', strtotime($row->end_date));
				$nestedData['status'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?'<span class="btn-danger btn-xs">Expired</span>':'Occupied';
				
                $nestedData['renew'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?"<p><button class='btn btn-success btn-xs' onClick='location.href={$renew}'>
												<i class='fa fa-fw fa-refresh'></i></button></p>":'';
												
				//$nestedData['close'] = "<button class='btn btn-danger btn-xs delete' onClick='location.href={$close}'><i class='fa fa-fw fa-sign-out'></i></button>";
												
				$nestedData['settle'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$settle}'>
												<i class='fa fa-fw fa-bookmark'></i></button></p>";
												
				$nestedData['mailbtn'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$mail}'>
												<i class='fa fa-fw fa-envelope-o'></i></button></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs' onClick='location.href={$open}'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
												
				$nestedData['attach'] = "<p><a href={$attach}' class='btn btn-info btn-xs' target='_blank'><span class='glyphicon glyphicon-paperclip'></span></a></p>";
				
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
	
	
	private function getContractEnqList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								->where('contract_building.status',1)
								->where('contract_building.is_close',0)
								//->where('contract_building.renew_id',null)
								->where('contract_building.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('AM.master_name','LIKE',"%{$search}%");
					$query->orWhere('B.buildingcode','LIKE',"%{$search}%");
					$query->orWhere('F.flat_no','LIKE',"%{$search}%");
					$query->orWhere('contract_building.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','contract_building.start_date','contract_building.duration',
						'contract_building.end_date','contract_building.status','contract_building.is_close','AM.master_name','B.buildingcode','F.flat_no');
						
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	private function getContractClosedList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								->where('contract_building.status',0)
								->where('contract_building.is_close',1)
								//->where('contract_building.renew_id',null)
								->where('contract_building.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('AM.master_name','LIKE',"%{$search}%");
					$query->orWhere('B.buildingcode','LIKE',"%{$search}%");
					$query->orWhere('F.flat_no','LIKE',"%{$search}%");
					$query->orWhere('contract_building.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','contract_building.start_date','contract_building.duration',
						'contract_building.renew_id','contract_building.end_date','contract_building.status','contract_building.is_close','AM.master_name','B.buildingcode','F.flat_no');
						
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	private function getContractHistoryList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								->where('contract_building.status',0)
								->where('contract_building.is_close',0)
								->where('contract_building.renew_id',null)
								->where('contract_building.deleted_at',null);
		
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('AM.master_name','LIKE',"%{$search}%");
					$query->orWhere('B.buildingcode','LIKE',"%{$search}%");
					$query->orWhere('F.flat_no','LIKE',"%{$search}%");
					$query->orWhere('contract_building.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','contract_building.start_date','contract_building.duration',
						'contract_building.end_date','contract_building.status','contract_building.is_close','AM.master_name','B.buildingcode','F.flat_no');
						
				
			if($type=='get')
				return $query->offset($start)
							 ->limit($limit)
							 ->orderBy($order,$dir)->get();
			else
				return $query->count();
	}
	
	//Enquiry
	public function ajaxEnquiry(Request $request)
	{
		$columns = array( 
                            0 => 'contract_building.id', 
                            1 => 'contract_no',
                           // 2 => 'contract_date',
							2 => 'customer',
                           3 => 'building_code',
                            4 => 'flat_no',
                            5 => 'exp_date',
                            6 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'contract_building.end_date';
        $dir = 'asc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getContractEnqList('count', $start, $limit, $order, $dir, $search);

        $totalFiltered = $totalData;
		
		$invoices = $this->getContractEnqList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getContractEnqList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                
				$edit =  '"'.url('contractbuilding/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$open =  '"'.url('contractbuilding/add/'.$row->id).'"';
				$renew =  '"'.url('contractbuilding/renew/'.$row->id).'"';
                $close =  '"'.url('contractbuilding/close/'.$row->id).'"';
				$settle =  '"'.url('contractbuilding/settle/'.$row->id).'"';
				$mail =  '"'.url('contractbuilding/mail/'.$row->id).'"';
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['start_date'] = date('d-m-Y', strtotime($row->start_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['building_code'] = $row->buildingcode;
				$nestedData['flat_no'] = $row->flat_no;
				$nestedData['exp_date'] = date('d-m-Y', strtotime($row->end_date));
				$nestedData['status'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?'<span class="btn-danger btn-xs">Expired</span>':'Occupied';
				
                $nestedData['renew'] = ($row->end_date < date('Y-m-d') && $row->is_close == 0)?"<p><button class='btn btn-success btn-xs' onClick='location.href={$renew}'>
												<i class='fa fa-fw fa-refresh'></i></button></p>":'';
												
				$nestedData['close'] = "<button class='btn btn-danger btn-xs delete' onClick='location.href={$close}'>
												<i class='fa fa-fw fa-sign-out'></i></button>";
												
				$nestedData['settle'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$settle}'>
												<i class='fa fa-fw fa-bookmark'></i></button></p>";
												
				$nestedData['mailbtn'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$mail}'>
												<i class='fa fa-fw fa-envelope-o'></i></button></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs' onClick='location.href={$open}'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
												
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
	
	//Closed
	public function ajaxClosed(Request $request)
	{
		$columns = array( 
                            0 => 'contract_building.id', 
                            1 => 'contract_no',
							2 => 'customer',
                            3 => 'building_code',
                            4 => 'flat_no',
                            5 => 'exp_date',
                            6 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'contract_building.end_date';
        $dir = 'asc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getContractClosedList('count', $start, $limit, $order, $dir, $search);

        $totalFiltered = $totalData;
		
		$invoices = $this->getContractClosedList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getContractClosedList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                
				$edit =  '"'.url('contractbuilding/edit/'.$row->id).'"';
                //$delete =  'funDelete("'.$row->id.'")';
				$del = ($row->renew_id==null&&$row->is_close==0)?0:1;
				$delete =  'funDelete("'.$row->id.'","'.$del.'")';

				$open =  '"'.url('contractbuilding/add/'.$row->id).'"';
				$renew =  '"'.url('contractbuilding/renew/'.$row->id).'"';
                $close =  '"'.url('contractbuilding/close/'.$row->id).'"';
				$settle =  '"'.url('contractbuilding/settle/'.$row->id).'"';
				$mail =  '"'.url('contractbuilding/mail/'.$row->id).'"';
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['start_date'] = date('d-m-Y', strtotime($row->start_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['building_code'] = $row->buildingcode;
				$nestedData['flat_no'] = $row->flat_no;
				$nestedData['exp_date'] = date('d-m-Y', strtotime($row->end_date));
				$nestedData['status'] = ($row->is_close == 1)?'<span class="btn-danger btn-xs">Closed</span>':'';
				
                $nestedData['renew'] = ($row->is_close == 1)?'':"<p><button class='btn btn-success btn-xs' onClick='location.href={$renew}'>
												<i class='fa fa-fw fa-refresh'></i></button></p>";
												
				$nestedData['close'] = "<button class='btn btn-danger btn-xs delete' onClick='location.href={$close}'>
												<i class='fa fa-fw fa-sign-out'></i></button>";
												
				$nestedData['settle'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$settle}'>
												<i class='fa fa-fw fa-bookmark'></i></button></p>";
												
				$nestedData['mailbtn'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$mail}'>
												<i class='fa fa-fw fa-envelope-o'></i></button></p>";
				
				$nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs' onClick='location.href={$open}'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
												
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
	
	public function ajaxHistory(Request $request)
	{
		$columns = array( 
                            0 => 'contract_building.id', 
                            1 => 'contract_no',
							2 => 'customer',
                            3 => 'building_code',
                            4 => 'flat_no',
                            5 => 'exp_date',
                            6 => 'status'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'contract_building.end_date';
        $dir = 'asc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getContractHistoryList('count', $start, $limit, $order, $dir, $search);

        $totalFiltered = $totalData;
		
		$invoices = $this->getContractHistoryList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getContractHistoryList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
				$open =  '"'.url('contractbuilding/add/'.$row->id).'"';
				$attach =  '"'.url('contractbuilding/attach/'.$row->id).'"';
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['start_date'] = date('d-m-Y', strtotime($row->start_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['building_code'] = $row->buildingcode;
				$nestedData['flat_no'] = $row->flat_no;
				$nestedData['exp_date'] = date('d-m-Y', strtotime($row->end_date));
				
				$nestedData['open'] = "<p><button class='btn btn-success btn-xs' onClick='location.href={$open}'>
												<i class='fa fa-fw fa-folder-open'></i></button></p>";
				$nestedData['attach'] = "<p><a href={$attach}' class='btn btn-info btn-xs' target='_blank'><span class='glyphicon glyphicon-paperclip'></span></a></p>";								
												
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
	
	
	public function add($id=null) { 
		//NOV26		
		$renewid = $printrvs = $crow = $acrow = $acarr = $jvs = $rvs = $drvs = $orvs = $orvac_arr = $orvactx_arr = null; $total = $txtotal = $acamounts = $othr_rv_amt = 0;
		$orvarr = $txarr = $incmac = $renewdet = [];

		$rentOpt = DB::table('parameter1')->where('id',1)->select('daily_rent')->first();
		
		$prints = DB::table('report_view_detail')
					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
					->where('report_view.code','REALINVO')
					->select('report_view_detail.name','report_view_detail.id')
					->get();
					
		$printz = DB::table('report_view_detail')
				->join('report_view','report_view.id','=','report_view_detail.report_view_id') 
				->where('report_view.code','CONTRACT')
				->select('report_view_detail.name','report_view_detail.id')
				->get();
		
		$duration = DB::table('duration')->whereNull('deleted_at')->get();
		$heads = DB::table('contra_type_head')->pluck('head_text', 'head');
		
		if($id) {
			$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();
			
											
			$incmac = DB::table('contra_type')  
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M14.master_name AS acname14','contra_type.rental_income','contra_type.prepaid_income')
								->where('contra_type.buildingid',$crow->building_id)->first(); 
								
			$acrow = DB::table('contract_prepaid')  
							->join('contract_building AS CB', 'CB.id', '=', 'contract_prepaid.contract_id')
							->join('contra_type AS CT', 'CT.buildingid', '=', 'CB.building_id')
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname','contract_prepaid.account_id','contract_prepaid.amount','contract_prepaid.tax_amount','CT.*')
							->where('contract_prepaid.contract_id',$id)->whereNull('CT.deleted_at')->get(); 
								
			//echo '<pre>';print_r($acrow);exit;
			
			//NOV26..
			if($crow->renew_id=='') {
				$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->where('contract_prepaid.is_add',1)
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->select('M1.master_name AS acname1','contract_prepaid.*')
								->get(); 
			} else {
				$renewid = $crow->renew_id;
				$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)//->where('contract_prepaid.is_add',1)
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->select('M1.master_name AS acname1','contract_prepaid.*')
								->get();
			}
							
			//$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->get();
			$acarr = [];
			foreach($acamounts as $k => $row) {
				$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
				$total += $row->amount;
				$txtotal += $row->tax_amount;
				
				if($k > 1) {
					$othr_rv_amt += $row->amount;
				}
			}
			
			$orvactx_arr = [0 => 'Prepaid Income Tax', 1 => 'Deposit Tax', 2 => 'Security Deposit Tax', 3 => 'Commission Tax', 4 => 'Other Deposit Tax', 5 => 'Parking Income Tax', 6 => 'Ejarie Fee Tax'];
			//echo '<pre>';print_r($crow);exit;
		
			$orvac_arr = [0 => '', 1 => '', 2 => 'Security Deposit', 3 => 'Commission', 4 => 'Other Deposit', 5 => 'Parking Income', 6 => 'Ejarie Fee'];
			
			$jvs = DB::table('contract_jv')->where('contract_jv.contract_id',$id)
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount')
								->get();//echo '<pre>';print_r($jvs);exit;
								
			$rvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','RV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.description','RE.reference','RE.amount','R.voucher_type','RE.cheque_no',
										'RE.cheque_date','RE.entry_type','M.master_name','RE.bank_id','RE.currency_id')//NOV23
								->get();
								
			
			$drvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','DRV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.description','RE.reference','RE.amount','R.voucher_type','RE.cheque_no',
										'RE.cheque_date','RE.entry_type','M.master_name','RE.bank_id','RE.account_id') //NOV27
								->get();
			
			//echo '<pre>';print_r($drvs);exit;
								
			$orvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.*','M.master_name','contract_rvs.rv_id')
								->get();
								
			$printrvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV') //NOV5
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->select('R.voucher_no','contract_rvs.id')
								->get();
								
			//echo '<pre>';print_r($orvs);exit;
			
			//GETTING PAID A/c IDS...
			foreach($orvs as $orv) {
				if($orv->entry_type=='Cr') {
					$orvarr[] = $orv->department_id; 
					if($orv->is_fc==1)
						$txarr[] = $orv->department_id;
				}
			}
			
			//GET RENEW DETAILS...
			$renewdet = DB::table('contract_prepaid')->where('contract_id',$id)
								->join('contract_building AS CB', 'CB.id', '=', 'contract_prepaid.contract_id')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->where('contract_prepaid.is_add',1)->where('CB.renew_id','!=',null)
								->select('M1.master_name AS acname1','contract_prepaid.*')
								->get();
			//echo '<pre>';print_r($renewdet);exit;
			
			$view = 'view';
			if(!Session::has('message'))
				Session::flash('active', 'home');
		} else
			$view = 'add';
		
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
		$rvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',9)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
						
		$pvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',10)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		$depo = DB::table('contract_prepaid')->where('contract_id',$id)->get();
		
		//echo '<pre>';print_r($acrow);exit;
		return view('body.contractbuilding.'.$view)
	            	->withBuildingmaster($buildingmaster)
					->withSirow($sirow)
					->withConid($id)
					->withCrow($crow)
					->withBanks($banks)
					->withRvrow($rvrow)
					->withPrints($prints)
					->withPrintz($printz)
					->withAcrow($acrow)
					->withAcamt($acarr)
					->withPayacnts($acamounts)
					->withJvs($jvs)
					->withRvs($rvs)
					->withDrvs($drvs)
					->withOrvs($orvs)
					->withTotal($total)
					->withDuration($duration)
					->withOramt($othr_rv_amt+$txtotal)
					->withOracarr($orvac_arr)
					->withOractxarr($orvactx_arr)
					->withOrv($orvarr)
					->withTxrv($txarr)
					->withPrvs($printrvs)
					->withSettings($this->acsettings)
					->withPvrow($pvrow)
					->withRenewid($renewid) //NOV26
					->withIncmac($incmac)
					->withRenewdet($renewdet)
					->withDepo(isset($depo[1])?$depo[1]:null)
					->withHeads($heads)
					->withTxtotal($txtotal)
					->withRentopt(($rentOpt)?$rentOpt->daily_rent:0);
	}

	public function printcontract($id,$rid=null)
	{ 
	 $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if($viewfile->print_name=='') {
		$crow = DB::table('contract_building')
		                 ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
                      	->join('buildingmaster AS B','B.id','=','contract_building.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','B.buildingcode AS buildcode','B.buildingname AS buildname','AM.master_name AS tenant','AM.id AS tenantid','F.flat_no AS flat')->first();
		
		$jvs = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')
					->join('journal_entry AS RE','RE.journal_id','=','journal.id')
					->join('account_master AS M','M.id','=','RE.account_id')
					->select('journal.voucher_no','journal.voucher_date','journal.debit AS amount','RE.description','RE.reference','RE.amount','RE.cheque_no',
	 						'RE.cheque_date','RE.entry_type','M.master_name AS bankacc')->orderBy('RE.id','ASC')->get();

		$voucherhead = 'Contract Details';
		//echo '<pre>';print_r($jvs);exit;
			
		return view('body.contractbuilding.printdetails')
						->withVoucherhead($voucherhead)
						->withCrow($crow)
						->withJerows($jvs);
		}
		else {
					
			$path = app_path() . '/stimulsoft/helper.php';
			return view('body.contractbuilding.viewer')->withPath($path)->withView($viewfile->print_name);
		}
	}


	public function printinvo($id,$rid=null)
	{ 
	    
	    
	     $viewfile = DB::table('report_view_detail')->where('id', $rid)->select('print_name')->first(); 
			
		if($viewfile->print_name=='') {
		$crow = $acrow = $acarr =$othr_rv_amt=null; $total = $txtotal = $acamounts =  0;
	//	echo '<pre>';print_r($id);exit;
		$crow = DB::table('contract_building')
		                 ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
                      	->join('buildingmaster AS B','B.id','=','contract_building.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','B.buildingcode AS buildcode','B.buildingname AS buildname','AM.master_name AS tenant','AM.id AS tenantid','F.flat_no AS flat')->first();
					
		$acrow = DB::table('contra_type')  
					->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
					->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
					->leftJoin('account_master AS M2', 'M2.id', '=', 'contra_type.deposit')
					->leftJoin('account_master AS M3', 'M3.id', '=', 'contra_type.water_ecty')
					->leftJoin('account_master AS M4', 'M4.id', '=', 'contra_type.commission')
					->leftJoin('account_master AS M5', 'M5.id', '=', 'contra_type.other_deposit')
					->leftJoin('account_master AS M6', 'M6.id', '=', 'contra_type.parking')
					->leftJoin('account_master AS M7', 'M7.id', '=', 'contra_type.ejarie_fee')
					->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
					->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
					->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
					->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
					->leftJoin('account_master AS M12', 'M12.id', '=', 'contra_type.booking_oth')
					->leftJoin('account_master AS M13', 'M13.id', '=', 'contra_type.chq_charge')
					->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
					->select('M1.master_name AS acname1','M2.master_name AS acname2','M3.master_name AS acname3','M4.master_name AS acname4',
							'M5.master_name AS acname5','M6.master_name AS acname6','M7.master_name AS acname7','M8.master_name AS acname8',
							'M9.master_name AS acname9','M10.master_name AS acname10','M11.master_name AS acname11','M12.master_name AS acname12',
							'M13.master_name AS acname13','M14.master_name AS acname14','contra_type.type','contra_type.increment_no','B.ownername',
							'B.location','B.mobno','B.plot_no','contra_type.*')
					->where('contra_type.buildingid',$crow->building_id)->first(); 



		$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)
				->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
				->select('M1.master_name AS acname1','contract_prepaid.*')
				->get();
				$acarr = [];
				foreach($acamounts as $k => $row) {
					$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
					$total += $row->amount;
					$txtotal += $row->tax_amount;
					
					if($k > 1) {
						$othr_rv_amt += $row->amount;
					}
				}
				
		$voucherhead = 'Invoice';
		//echo '<pre>';print_r($jvs);exit;
			
		return view('body.contractbuilding.preprintinvo')
						->withVoucherhead($voucherhead)
						->withCrow($crow)
						->withAcrow($acrow)
						->withAcamt($acarr)
						->withPayacnts($acamounts);
		}
			else {
					
			$path = app_path() . '/stimulsoft/helper.php';

			//return view('body.contractbuilding.viewer')->withPath($path)->withView($viewfile->print_name);

			return view('body.contractbuilding.viewers')->withPath($path)->withView($viewfile->print_name);
		}
	}
	
	public function printJv($id)
	{ 
		
		$jvs = DB::table('contract_jv')->where('contract_jv.contract_id',$id)
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->join('journal_entry AS RE','RE.journal_id','=','J.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount','RE.description','RE.reference','RE.amount','RE.cheque_no',
								'RE.cheque_date','RE.entry_type','M.master_name')
								//->orderBy('RE.entry_type','DESC')
								->orderBy('RE.id','ASC')//->orderBy('J.voucher_no','ASC')
								->get();
		$voucherhead = 'Journal Voucher';
		//echo '<pre>';print_r($jvs);exit;
			
		return view('body.contractbuilding.print')
						->withVoucherhead($voucherhead)
						
						->withJerows($jvs);
	}
	public function sendmail($id)
	{ 
		
		$mid = DB::table('contract_building')
		                         ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
		                        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
		                        ->where('contract_building.id',$id)
								->select('contract_building.*','AM.master_name','F.flat_no AS flat')->get();
	
		
		//echo '<pre>';print_r($mid);exit;
			
		return view('body.contractbuilding.email')
					->withMid($mid);
	}

	public function getReportvacant($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								->where('contract_building.status',0)
								->where('contract_building.is_close',1)
								->where('contract_building.deleted_at',null);
								if( $date_from!='' && $date_to!='' ) { 
									$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
								}
								
			if($attributes['building']!='') { 
				$query->where('contract_building.building_id', $attributes['building']);
			}
			
					if($attributes['tenant']!='') { 
						$query->where('contract_building.customer_id', $attributes['tenant']);
					}
			$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no','contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat');			
								
			
			if(isset($attributes['type']))
				return $query->groupBy('contract_building.id')->get()->toArray();
			else
				return $query->groupBy('contract_building.id')->get();
	}
	public function getReport($attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
		          ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				  ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
			        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')->where('contract_building.status',1)
					->where('contract_building.is_close',0)
					->where('contract_building.deleted_at',null);

		if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.end_date', array($date_from, $date_to));
					}
		if( $date_from =='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.end_date', array($date_from, $date_to));
					}			
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		if($attributes['tenant']!='') { 
						$query->where('contract_building.customer_id', $attributes['tenant']);
					}
		$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat');			
					

	if(isset($attributes['type']))
		return $query->groupBy('contract_building.id')->orderBy('contract_building.end_date')->get()->toArray();
	else
		return $query->groupBy('contract_building.id')->orderBy('contract_building.end_date')->get();				

	}
	
		public function getReportAll($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
								->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								->join('buildingmaster AS B','B.id','=','contract_building.building_id')
								->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
								//->where('contract_building.status',0)
								//->where('contract_building.is_close',1)
								->where('contract_building.deleted_at',null);
								if( $date_from!='' && $date_to!='' ) { 
									$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
								}
									if( $date_from =='' && $date_to!='' ) { 
									$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
								}
								
			if($attributes['building']!='') { 
				$query->where('contract_building.building_id', $attributes['building']);
			}
			
					if($attributes['tenant']!='') { 
						$query->where('contract_building.customer_id', $attributes['tenant']);
					}
			$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no','contract_building.is_close','contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat');			
								
			
			if(isset($attributes['type']))
				return $query->groupBy('contract_building.id')->orderBy('contract_building.contract_no')->get()->toArray();
			else
				return $query->groupBy('contract_building.id')->orderBy('contract_building.contract_no')->get();
	}
	
	public function getReportrent($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		
		$crow = DB::table('contract_building')
		     ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
			 ->join('buildingmaster AS B', 'B.id', '=', 'contract_building.building_id')
			 ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
			 ->where('contract_building.status',1)
			 ->where('contract_building.is_close',0);
		$crow->select('contract_building.contract_date','contract_building.id AS conid',
			 'contract_building.rent_amount','contract_building.description','contract_building.grand_total',
			 'contract_building.contract_no','contract_building.start_date','contract_building.end_date','contract_building.duration',
			 'AM.master_name AS tenant','AM.id AS customer','F.flat_no AS flat','B.buildingcode AS buildcode','B.buildingname AS buildname');
		if( $date_from!='' && $date_to!='' ) { 
				$crow->whereBetween('contract_building.contract_date', array($date_from, $date_to));
			}
		if( $date_from =='' && $date_to!='' ) { 
				$crow->whereBetween('contract_building.contract_date', array($date_from, $date_to));
			}	
		if($attributes['building']!=''){ 
				$crow->where('contract_building.building_id', $attributes['building']);}
		if($attributes['tenant']!='') { 
					$crow->where('contract_building.customer_id', $attributes['tenant']);}	
				
        $qry1= $crow->groupBy('contract_building.id')->get();
        $acamounts = DB::table('contract_prepaid') 
                  ->join('contract_building AS CB','CB.id','=','contract_prepaid.contract_id')  
			      ->leftJoin('account_master AS M2', 'M2.id', '=', 'contract_prepaid.account_id')
			->where('CB.status',1)
			->where('CB.is_close',0); 
		$acamounts->select('CB.id AS conid','contract_prepaid.amount AS Deposit','contract_prepaid.tax_amount AS taxdeposit');
		$qry2= $acamounts->get();
		return array_merge($qry2, $qry1);
   
	}

protected function makeTreeexp($result)
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->end_date][] = $item;
		
			
		return $childs;
	}	
	
	 protected function makeTreeSup($result)
	 {
	 	$childs = array();
	 	foreach($result as $item)
	 		$childs[$item->conid][] = $item;
		
	 	return $childs;
	 }
	 
	  public function getExpiryReport($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
		          ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				  ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
			        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
			        ->where('contract_building.status',1)
					->where('contract_building.is_close',0)
					->where('contract_building.end_date','<',date('Y-m-d'))
					->where('contract_building.deleted_at',null);

		if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.end_date', array($date_from, $date_to));
					}
		if($date_from =='' && $date_to !='' ) { 
						$query->whereBetween('contract_building.end_date', array($date_from, $date_to));
					}			
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		
		$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat');			
					

	//echo '<pre>';print_r($query);exit;
		return $query->groupBy('contract_building.id')->orderBy('contract_building.end_date')->get();				

	}
	
		public function getOccupiedReport($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
		          ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				  ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
			        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
			        ->where('contract_building.status',1)
					->where('contract_building.is_close',0)
					->where('contract_building.end_date','>',date('Y-m-d'))
					->where('contract_building.deleted_at',null);

		if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}
		if($date_from =='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}			
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		
		$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','AM.phone','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no','F.flat_name');			
					

	//echo '<pre>';print_r($query);exit;
		return $query->groupBy('contract_building.id')->get();				

	}

		public function getVacantReport($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
		          ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				  ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
			        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
			        ->where('contract_building.status',0)
					->where('contract_building.is_close',1)
					->where('contract_building.deleted_at',null);

		if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}
		if($date_from =='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}			
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		
		$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','AM.phone','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no','F.flat_name');			
					

	//echo '<pre>';print_r($query);exit;
		return $query->groupBy('contract_building.id')->get();				

	}
	
		public function getAllReport($attributes) 
	{
		//echo '<pre>';print_r($attributes);exit;
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';

		$query = DB::table('contract_building')
		          ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				  ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
			        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.deleted_at',null);

		if( $date_from!='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}
		if($date_from =='' && $date_to!='' ) { 
						$query->whereBetween('contract_building.contract_date', array($date_from, $date_to));
					}			
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		
		$query->select('contract_building.contract_date','contract_building.id AS conid','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','contract_building.is_close','AM.master_name','AM.phone','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no','F.flat_name');			
					

	//echo '<pre>';print_r($query);exit;
		return $query->groupBy('contract_building.id')->get();				

	}
	
	protected function makeTreeexprep($result) 
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->end_date][] = $item;
		
			
		return $childs;
	}	
	
	protected function makeTreevacant($result) 
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->buildname][] = $item;
		
			
		return $childs;
	}	
	
		protected function makeTreeallflat($result) 
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->buildname][] = $item;
		
			
		return $childs;
	}	
		protected function makeTreeoccupied($result) 
	{
		$childs = array();
		foreach($result as $item)
		//echo '<pre>';print_r($$item); exit();
		$childs[$item->buildname][] = $item;
		
			
		return $childs;
	}	
	 
	
	
	public function getSearch()
	{
		$data = array();
		$voucher_head  = '';
		$total=$spstotal=$dstotal=$cstotal=$pstotal=0;
		$txtotal  = 0;
		if($request->get('search_type')=="expiry")
		{
			$voucher_head = 'Expiry ';
            $report = $this->getReport($request->all());
			$reports = $this->makeTreeexp($report);
		    $titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}else if($request->get('search_type')=="buildingwise") {
			$voucher_head = 'All Flats';
			$reports = $this->getReportAll($request->all());
			//$reports = $this->getBuildingwise($reports);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}else if($request->get('search_type')=='tenantwise') {
	        $voucher_head = 'DETAIL';
			$report = $this->getReportrent($request->all());
			$reports = $this->makeTreeSup($report);
			// foreach($reports as $k => $row) {
				
			// 	$spstotal +=$row[0]->Deposit;  
			// 	$dstotal += $row[5]->Deposit;  
			// 		$cstotal += $row[1]->Deposit;
			// 		$pstotal +=$row[6]->Deposit; 
			// 		$total += $spstotal+$dstotal+$cstotal+$pstotal;
			// 	//$txtotal += $row->tax_amount;
				
				
			// }
			
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		
		}
		//echo '<pre>';print_r($total);exit;
		//echo '<pre>';print_r($reports);exit;
		return view('body.contractbuilding.preprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
				//	->withTotal(($total='')?$total:'' )
					//->withTxtotal($txtotal)
				    ->withTitles($titles)
				    ->withSettings($this->acsettings)
					->withData($data);
	}
	
public function dataExport()
	{
		$data = array();
		$datareport[] = [strtoupper(Session::get('company')),'','',''];
		$datareport[] = ['','','','','','',''];
		
		$request->merge(['type' => 'export']);
		//$reports = $this->purchase_invoice->getReportExcel($request->all());
		
		if($request->get('search_type')=="summary")
		{
			$voucher_head = 'Sales Invoice Summary';
			$reports = $this->sales_invoice->getReportExcel($request->all());
		
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = [ 'customer','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 
									 'supplier' => $row['master_name'],
									 
									  'gross' => $row['total'],
									  'ref' => $row['reference_no'],
									  'total' => $row['net_amount']
									];
			}
		}
		elseif($request->get('search_type')=="detail") {
			$voucher_head = 'Sales Invoice Detail';
			$reports = $this->sales_invoice->getReportExcel($request->all());
				$datareport[] = ['','','','',strtoupper($voucher_head), '','',''];
		     $datareport[] = ['','','','','','',''];
		
			$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Customer','Gross Amt.','VAT Amt.','Net Total'];
			$i=0;
			foreach ($reports as $row) {
					$i++;
					$datareport[] = [ 'si' => $i,
									  'po' => $row['voucher_no'],
									  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
									  'ref' => $row['reference_no'],
									  'supplier' => $row['master_name'],
									 
									  'gross' => $row['unit_price'],
									 'vat' => $row['vat_amount'],
									  'total' => $row['total_price']
									];
			}
			//$reports = $this->makeTree($reports);
		}
		
	
		//echo '<pre>';print_r($reports);exit;
		/* if($request->get('search_type')=='purchase_register') {
			
			$datareport[] = ['SI.No.','PI#','Vchr.Date','PI.Ref#', 'Supplier','TRN No','PI.Qty','Rate','Total Amt.'];
			$i=0;
			foreach ($reports as $row) {
				$i++;
				$datareport[] = [ 'si' => $i,
								  'po' => $row['voucher_no'],
								  'vdate' => date('d-m-Y',strtotime($row['voucher_date'])),
								  'ref' => $row['reference_no'],
								  'supplier' => $row['master_name'],
								  'vat_no' => $row['vat_no'],
								  'item_code' => $row['item_code'],
								  'description' => $row['description'],
								  'quantity' => $row['quantity'],
								  'unit_price' => $row['unit_price'],
								  'net_amount' => $row['net_amount']
								];
			}
		} else { */
			
		
			
		//}
		 //echo $voucher_head.'<pre>';print_r($datareport);exit;
		Excel::create($voucher_head, function($excel) use ($datareport,$voucher_head) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle($voucher_head);
        $excel->setCreator('NumakPro ERP')->setCompany(Session::get('company'));
        $excel->setDescription($voucher_head);

        // Build the spreadsheet, passing in the payments array
		$excel->sheet('sheet1', function($sheet) use ($datareport) {
			$sheet->fromArray($datareport, null, 'A1', false, false);
		});

		})->download('xlsx');
		
	}
	
	
	
		public function reportSearch()
	{
		$data = array();
		$voucher_head  = '';
	//	$type=$request->get('search_type');
		//echo '<pre>';print_r($type);exit;
		if($request->get('search_type')=="expiry")
		{
			$voucher_head = 'Expiry Flats ';
            $report = $this->getExpiryReport($request->all());
            
			$reports = $this->makeTreeexprep($report);
			//echo '<pre>';print_r($reports);exit;
		    $titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}else if($request->get('search_type')=="vaccant") {
			$voucher_head = 'Vacant Flats';
			$report = $this->getVacantReport($request->all());
			$reports = $this->makeTreevacant($report);
				//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}else if($request->get('search_type')=='all') {
	        $voucher_head = 'All Flats';
			$report = $this->getAllReport($request->all());
			
			$reports = $this->makeTreeallflat($report);
		
				//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];
		
		}
		else if($request->get('search_type')=="occupied") {
			$voucher_head = 'Occupied Flats';
			$reports = $this->getOccupiedReport($request->all());
			$reports = $this->makeTreeoccupied($reports);
				//echo '<pre>';print_r($reports);exit;
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}
		//echo '<pre>';print_r($total);exit;
		//echo '<pre>';print_r($reports);exit;
		return view('body.contractbuilding.reportprint')
					->withReports($reports)
					->withVoucherhead($voucher_head)
					->withType($request->get('search_type'))
					->withFromdate($request->get('date_from'))
					->withTodate($request->get('date_to'))
					->withI(0)
				    ->withTitles($titles)
				    ->withSettings($this->acsettings)
					->withData($data);
	}
	
		
	
		
	public function renew($id=null) {

		$crow = $acrow = $acarr = $jvs = $rvs = $drvs = $orvs = null; $total = $txtotal = 0;  
		
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
				->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
				->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();
				
		/* $acrow = DB::table('contra_type')
							->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
							->leftJoin('account_master AS M2', 'M2.id', '=', 'contra_type.deposit')
							->leftJoin('account_master AS M3', 'M3.id', '=', 'contra_type.water_ecty')
							->leftJoin('account_master AS M4', 'M4.id', '=', 'contra_type.commission')
							->leftJoin('account_master AS M5', 'M5.id', '=', 'contra_type.other_deposit')
							->leftJoin('account_master AS M6', 'M6.id', '=', 'contra_type.parking')
							->leftJoin('account_master AS M7', 'M7.id', '=', 'contra_type.ejarie_fee')
							->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
							->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
							->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
							->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
							->leftJoin('account_master AS M12', 'M12.id', '=', 'contra_type.booking_oth')
							->leftJoin('account_master AS M13', 'M13.id', '=', 'contra_type.chq_charge')
							->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
							->select('M1.master_name AS acname1','M2.master_name AS acname2','M3.master_name AS acname3','M4.master_name AS acname4',
									'M5.master_name AS acname5','M6.master_name AS acname6','M7.master_name AS acname7','M8.master_name AS acname8',
									'M9.master_name AS acname9','M10.master_name AS acname10','M11.master_name AS acname11','M12.master_name AS acname12',
									'M13.master_name AS acname13','M14.master_name AS acname14','contra_type.type','contra_type.increment_no','B.ownername',
									'B.location','B.mobno','B.plot_no','contra_type.*')
							->where('contra_type.buildingid',$crow->building_id)->first();  */
		
		$incmac = DB::table('contra_type')  
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
								->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
								->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
								->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M8.master_name AS acname8','M9.master_name AS acname9','M10.master_name AS acname10',
										 'M11.master_name AS acname11','M14.master_name AS acname14','contra_type.rental_income','contra_type.prepaid_income',
										 'contra_type.cancellation','contra_type.repair','contra_type.water_ecty_bill','contra_type.closing_oth','contra_type.type','contra_type.increment_no')
								->where('contra_type.buildingid',$crow->building_id)->first();
								
		$acrow = DB::table('contract_prepaid')  
						->join('contract_building AS CB', 'CB.id', '=', 'contract_prepaid.contract_id')
						->join('contra_type AS CT', 'CT.buildingid', '=', 'CB.building_id')
						->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
						->select('M1.master_name AS acname','contract_prepaid.account_id','contract_prepaid.amount','contract_prepaid.tax_amount','CT.*')
						->where('contract_prepaid.contract_id',$id)->get(); 
							
		$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->get();
		$acarr = [];
		foreach($acamounts as $row) {
			$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
			$total += $row->amount;
			$txtotal += $row->tax_amount;
		}
		
		
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
		$rvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',9)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		$duration = DB::table('duration')->where('deleted_at','0000-00-00 00:00:00')->get();
		
		//echo '<pre>';print_r($rvrow);exit;
		return view('body.contractbuilding.renew')
	            	->withBuildingmaster($buildingmaster)
					->withSirow($sirow)
					->withConid($id)
					->withCrow($crow)
					->withBanks($banks)
					->withRvrow($rvrow)
					->withAcrow($acrow)
					->withDuration($duration)
					->withSettings($this->acsettings)
					->withIncmac($incmac)
					->withAcamt($acarr);
	}
	
	
	public function save(Request $request) { //echo '<pre>';print_r($request->all());exit;  

		DB::beginTransaction();
		try {
			
			// Generate Initial Contract No
			$contract_no = $request->get('contract_no');

			// Try limit (avoid infinite loop)
			$maxRetries = 10;
			$retry = 0;
			while (true) {

				// 1) Check if contract_no already exists
				$exists = DB::table('contract_building')->where('contract_no', $request->get('contract_no'))->whereNull('deleted_at')->exists();

				if (!$exists) {
					// Available → break and use this contract no
					break;
				}

				// 2) Duplicate found → generate a NEW contract_no
				// (Example: increase numeric part, or append random token)
				DB::table('contra_type')->where('buildingid',$request->get('building_id'))->update(['increment_no' => DB::raw('increment_no + 1')]);
				$condata = DB::table('contra_type')->where('buildingid',$request->get('building_id'))->whereNull('deleted_at')->select('type','increment_no')->first();
				if($condata)
					$contract_no = $condata->type.$condata->increment_no;

				$retry++;
				if ($retry >= $maxRetries) {
					throw new \Exception("Failed to generate unique Contract No after multiple attempts.");
				}
			}

			$acname = $acid = $grparr = $siarr = $btarr = $desarr = $refarr = $invarr = $actarr = $actypearr = $lnarr = $jbarr = $vatarr = []; 
			$conid = DB::table('contract_building')
						->insertGetId([
							'building_id' => $request->get('building_id'),
							'contract_date' => date('Y-m-d', strtotime($request->get('start_date'))), //date
							'contract_no' => $contract_no,
							'si_no' => $request->get('si_no'),
							'customer_id' => $request->get('customer_id'),
							'flat_no' => $request->get('flat_no'),
							'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
							'duration' => ($request->get('chkedit'))?$request->get('durationD'):$request->get('duration'),
							'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
							'rent_amount' => $request->get('rent_amount'),
							'passport_no' => $request->get('passport_no'),
							'passport_exp' => $request->get('passport_exp'),
							'nationality' => $request->get('nationality'),
							'description' => $request->get('description'),
							'document' => $request->get('photo_name'),
							'file_no' => $request->get('file_no'),
							'terms' => $request->get('terms'),
							'observations' => $request->get('observations'),
							'observations_ot' => $request->get('observations_ot'),
							'grand_total' => $request->get('grand_total'),
							'is_day' => ($request->get('chkedit'))?1:0,
							'total_rent' => $request->get('rent_total')
						]);
						
			if($conid) {
				$acarr = $request->get('acid');
				if($request->get('contract_no')==$request->get('increment_no')) {
			    	DB::table('contra_type')->where('buildingid',$request->get('building_id'))
								->update(['increment_no' => DB::raw('increment_no + 1'),
								]);
				}
				
				$acname[] = $request->get('customer_account');
				$acid[] = $request->get('customer_id');
				$grparr[] = 'CUSTOMER';
				$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = '';
				$desarr[] = $contract_no.'/'.$request->get('si_no');
				$refarr[] = $contract_no;
				$actypearr[] = 'Dr';
				$lnarr[] = $request->get('grand_total');
				
				$acamount = $request->get('acamount');
				$actax = $request->get('actax');
				$arracname = $request->get('acname');
				foreach($request->get('acid') as $key => $val) {
					$acname[] = $arracname[$key];
					$acid[] = $val;
					$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
					$refarr[] = $contract_no;
					$actypearr[] = 'Cr';
					$lnarr[] = $acamount[$key];
					
					DB::table('contract_prepaid')
							->insert([
								'contract_id' => $conid,
								'account_id' => $val,
								'amount' => $acamount[$key],
								'tax_amount' => $actax[$key],
								'is_add'	=> 1
							]);
					
				}
				
				//TAX EXTRY....
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				if($vatrow) {
					foreach($request->get('acid') as $key => $val) {
						if($actax[$key] > 0) {
							$acname[] = $arracname[$key];
							$acid[] = $vatrow->payment_account;
							$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
							$desarr[] = $arracname[$key].'/TAX';
							$refarr[] = $contract_no;
							$actypearr[] = 'Cr';
							$lnarr[] = $actax[$key];
						}
					}
				}
				
				//INSERT SALES NONSTOCK....
				$request->merge(['from_jv' => 1]);
				$request->merge(['voucher' => 18]);
				$request->merge(['voucher_type' => 6]);
				$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('start_date'))) ]);
				$request->merge(['voucher_no' => '']);//$request->get('si_no')]);
				$request->merge(['account_name' => $acname]);
				$request->merge(['account_id' => $acid]);
				$request->merge(['group_id' => $grparr]);
				$request->merge(['sales_invoice_id' => $siarr]);
				$request->merge(['bill_type' => $btarr]);
				$request->merge(['description' => $desarr]);
				$request->merge(['reference' => $refarr]);
				$request->merge(['inv_id' => $invarr]);
				$request->merge(['actual_amount' => $actarr]);
				$request->merge(['account_type' => $actypearr]);
				$request->merge(['line_amount' => $lnarr]);
				$request->merge(['job_id' => $jbarr]);
				$request->merge(['difference' => 0]);
				$request->merge(['curno' => '']);
				$request->merge(['is_prefix' => 0]);
				$request->merge(['debit' => $request->get('grand_total')]);
				$request->merge(['credit' => $request->get('grand_total')]);
				
				$this->journal->createSIN($request->all());
			}

			DB::commit();
		
			Session::flash('message', 'Contract details added successfully.');
			Session::flash('active', 'home');
			return redirect('contractbuilding/add/'.$conid);

		} catch(ValidationException $e) { 

			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			//return false;
			return Redirect::to('contractbuilding/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) {
		
		$printrvs = $crow = $acrow = $acarr = $jvs = $rvs = $drvs = $orvs = $orvac_arr = $orvactx_arr = null; $total = $txtotal = $acamounts = $othr_rv_amt = 0;
		$orvarr = $txarr = [];
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();

		$heads = DB::table('contra_type_head')->pluck('head_text', 'head');

		$rentOpt = DB::table('parameter1')->where('id',1)->select('daily_rent')->first();
					
		if($crow) {
			$flats = DB::table('flat_master')->where('building_id', $crow->building_id)->where('deleted_at',null)
					->whereNotIn('id', DB::table('contract_building')->where('status',1)->where('is_close',0)->where('deleted_at',null)->pluck('flat_no'))
					->select('id','flat_no')->get();

			$incmac = DB::table('contra_type')  
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M14.master_name AS acname14','contra_type.rental_income','contra_type.prepaid_income')
								->where('contra_type.buildingid',$crow->building_id)->first(); 

			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')
								->join('journal_entry AS JE','JE.journal_id','=','journal.id')
								->select('JE.id','journal.id AS jid' /* ,'JE.account_id','JE.entry_type','JE.amount' */)->orderBy('JE.id','ASC')->get();
		}

		$prints = DB::table('report_view_detail')
					->join('report_view','report_view.id','=','report_view_detail.report_view_id')
					->where('report_view.code','REALINVO')
					->select('report_view_detail.name','report_view_detail.id')
					->get();
					
		$printz = DB::table('report_view_detail')
				->join('report_view','report_view.id','=','report_view_detail.report_view_id')
				->where('report_view.code','CONTRACT')
				->select('report_view_detail.name','report_view_detail.id')
				->get();	
								
				$acrow = DB::table('contract_prepaid')  
								->join('contract_building AS CB', 'CB.id', '=', 'contract_prepaid.contract_id')
								->join('contra_type AS CT', 'CT.buildingid', '=', 'CB.building_id')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->select('M1.master_name AS acname','contract_prepaid.account_id','contract_prepaid.amount','contract_prepaid.tax_amount','CT.*')
								->where('contract_prepaid.contract_id',$id)->whereNull('CT.deleted_at')->get(); 
								
			//echo '<pre>';print_r($acrow);exit;
			
			//echo '<pre>';print_r($acrow);exit;
			//if($crow->renew_id=='') {
				$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)//->where('is_add',1)
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->select('M1.master_name AS acname1','contract_prepaid.*')
								->get(); //echo '<pre>';print_r($acamounts);exit;
			/* } else {
				$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->where('is_add',1)
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
								->select('M1.master_name AS acname1','contract_prepaid.*')
								->get();
			} */
										
			
			//$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->get();
			$acarr = []; 
			foreach($acamounts as $k => $row) {
				$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
				if($crow->renew_id=='')
					$total += $row->amount;
				else {
					$total += ($row->is_add==1)?$row->amount:0;
				}
				
				$txtotal += $row->tax_amount;
				
				if($k > 1) {
					$othr_rv_amt += $row->amount;
				}
			}
			//echo '<pre>';print_r($acamounts);exit;
			$orvactx_arr = [0 => 'Prepaid Income Tax', 1 => 'Deposit Tax', 2 => 'Security Deposit Tax', 3 => 'Commission Tax', 4 => 'Other Deposit Tax', 5 => 'Parking Income Tax', 6 => 'Ejarie Fee Tax'];
			$orvac_arr = [0 => '', 1 => '', 2 => 'Security Deposit', 3 => 'Commission', 4 => 'Other Deposit', 5 => 'Parking Income', 6 => 'Ejarie Fee'];
			
			$jvs = DB::table('contract_jv')->where('contract_jv.contract_id',$id)
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount')
								->get();
								
			$rvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','RV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->where('RE.status',1)
								->where('RE.deleted_at','0000-00-00 00:00:00')
								->select('R.voucher_no','R.voucher_date','M.master_name','RE.*','contract_rvs.installment','R.voucher_type')
								->orderBy('RE.id','ASC')->get();  //echo '<pre>';print_r($rvs);exit;
								
			$drvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','DRV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','R.voucher_type','RE.*','M.master_name')
								->orderBy('RE.id','ASC')->get(); //echo '<pre>';print_r($drvs);exit;
								
			//NOV24
			$crv = DB::table('contract_rvs')->where('contract_id',$id)->where('type','ORV')->select('id')->orderBy('id','DESC')->first();
			if($crv) { 
				$orvs = DB::table('contract_rvs')->where('contract_rvs.id',$crv->id)->where('contract_rvs.type','ORV')
									->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
									->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
									->join('account_master AS M','M.id','=','RE.account_id')
									->select('R.voucher_no','R.voucher_date','R.debit','RE.*','M.master_name','contract_rvs.rv_id')
									->orderBy('RE.id','ASC')->get(); //echo '<pre>';print_r($orvs);exit;
			} else 
				$orvs = [];
			
			
			$ispv = DB::table('contract_pvs')->where('contract_id',$id)->select('id')->first();
			if($ispv) { 
				$pvs = DB::table('contract_pvs')->where('contract_pvs.id',$ispv->id)
									->join('payment_voucher AS P','P.id','=','contract_pvs.pv_id')
									->join('payment_voucher_entry AS PE','PE.payment_voucher_id','=','P.id')
									->join('account_master AS M','M.id','=','PE.account_id')
									->select('P.voucher_no','P.voucher_date','P.debit','PE.*','M.master_name','contract_pvs.pv_id')
									->orderBy('PE.id','ASC')->get(); 
			} else 
				$pvs = [];
			
			//echo '<pre>';print_r($pvs);exit; 
			
			$printrvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV') //NOV5
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->select('R.voucher_no','contract_rvs.id','contract_rvs.rv_id') //NOV24
								->get();
			
			$printpvs = DB::table('contract_pvs')->where('contract_pvs.contract_id',$id)
								->join('payment_voucher AS P','P.id','=','contract_pvs.pv_id')
								->select('P.voucher_no','contract_pvs.id','contract_pvs.pv_id') //NOV24
								->get();
								
			//GETTING PAID A/c IDS...
			foreach($orvs as $orv) {
				if($orv->entry_type=='Cr') {
					$orvarr[] = $orv->department_id; 
					if($orv->is_fc==1)
						$txarr[] = $orv->department_id;
				}
			}
			
			if(!Session::has('message'))
				Session::flash('active', 'home');
			
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
		$rvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',9)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
						
		$pvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',10)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		$duration = DB::table('duration')->where('deleted_at','0000-00-00 00:00:00')->get();			
		
		if($crow->renew_id=='')
			$view = 'edit';
		else
			$view = 'editrenew';
			
	//echo '<pre>';print_r($jerow);exit; 
		return view('body.contractbuilding.'.$view)
					->withBuildingmaster($buildingmaster)
					->withSirow($sirow)
					->withConid($id)
					->withCrow($crow)
					->withBanks($banks)
					->withRvrow($rvrow)
					->withPvrow($pvrow)
					->withAcrow($acrow)
					->withAcamt($acarr)
					->withPrints($prints)
					->withPrintz($printz)
					->withJvs($jvs)
					->withRvs($rvs)
					->withDrvs($drvs)
					->withOrvs($orvs)
					->withTotal($total)
					->withPayacnts($acamounts)
					->withOramt($othr_rv_amt+$txtotal)
					->withOracarr($orvac_arr)
					->withOractxarr($orvactx_arr)
					->withJerow($jerow)
					->withOrv($orvarr)
					->withTxrv($txarr)
					->withPrvs($printrvs)
					->withPpvs($printpvs)
					->withPvs($pvs)
					->withDuration($duration)
					->withSettings($this->acsettings)
					->withFlats($flats)
					->withIncmac($incmac)
					->withHeads($heads)
					->withTxtotal($txtotal)
					->withRentopt(($rentOpt)?$rentOpt->daily_rent:0); //pvrow 
	}
	
	public function update(Request $request, $id)
	{ //echo '<pre>';print_r($request->all());exit;
		try {
			$acname = $acid = $grparr = $siarr = $btarr = $desarr = $refarr = $invarr = $actarr = $actypearr = $lnarr = $jbarr = $dptarr = $vatarr = $cqarr = $bkarr = $cqdarr = $cqoarr = []; 
			DB::table('contract_building')
						->where('id', $id)
						->update([
							'building_id' => $request->get('building_id'),
							'customer_id' => $request->get('customer_id'),
							'flat_no' => $request->get('flat_no'),
							'contract_date' => date('Y-m-d', strtotime($request->get('date'))),
							'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
							'duration' => ($request->get('chkedit'))?$request->get('durationD'):$request->get('duration'),
							'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
							'rent_amount' => $request->get('rent_amount'),
							'passport_no' => $request->get('passport_no'),
							'passport_exp' => $request->get('passport_exp'),
							'nationality' => $request->get('nationality'),
							'description' => $request->get('description'),
							'document' => $document='',
							'file_no' => $request->get('file_no'),
							'terms' => $request->get('terms'),
							'observations' => $request->get('observations'),
							'observations_ot' => $request->get('observations_ot'),
							'grand_total' => $request->get('grand_total'),
							'total_rent' => $request->get('rent_total')
						]);
						
			if($id) {
				
				$acarr = $request->get('acid');
				/* DB::table('contra_type')->where('buildingid',$request->get('building_id'))
								->update(['prepaid_income' => $acarr[0],
										  'deposit' => $acarr[1],
										  'water_ecty' => $acarr[2],
										  'commission' => $acarr[3],
										  'other_deposit' => $acarr[4],
										  'parking' => $acarr[5],
										  'ejarie_fee' => $acarr[6]
								]); */
								
				$acname[] = $request->get('customer_account');
				$acid[] = $request->get('customer_id');
				$grparr[] = 'CUSTOMER';
				$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = $dptarr[] = $bkarr[] = $cqarr[] = $cqoarr[] = $cqdarr[] = '';
				$desarr[] = $request->get('contract_no').'/'.$request->get('si_no');
				$refarr[] = $request->get('contract_no');
				$actypearr[] = 'Dr';
				$lnarr[] = $request->get('grand_total');
				
				$acamount = $request->get('acamount');
				$actax = $request->get('actax');
				$arracname = $request->get('acname');
				$jearr = $request->get('je_id');
				$arrchk = $request->get('check'); //NOV26
				$prearr = DB::table('contract_prepaid')->where('contract_id',$id)->orderBy('id','ASC')->get();
				$gtotal = 0;//NOV26
				foreach($request->get('acid') as $key => $val) {
					
					//NOV26
					if(isset($arrchk[$val])) { 
						$acname[] = $arracname[$key];
						$acid[] = $val;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = '';
						$bkarr[] = ''; $cqarr[] = ''; $cqoarr[] = ''; $cqdarr[] = '';
						$refarr[] = $request->get('contract_no');
						$actypearr[] = 'Cr';
						$lnarr[] = $acamount[$key];
						$gtotal += $acamount[$key];
						
						DB::table('contract_prepaid')
							->where('id', $prearr[$key]->id)
							->update([
								'account_id' => $val,
								'amount' => $acamount[$key],
								'tax_amount' => $actax[$key],
								//'is_add' => (isset($arrchk[$val]))?1:0
							]);
					}
					
					$acname[] = $arracname[$key];
					$acid[] = $val;
					$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = '';
					$bkarr[] = ''; $cqarr[] = ''; $cqoarr[] = ''; $cqdarr[] = '';
					$refarr[] = $request->get('contract_no');
					$actypearr[] = 'Cr';
					$lnarr[] = $acamount[$key];
					
					DB::table('contract_prepaid')
							->where('id', $prearr[$key]->id)
							->update([
								'account_id' => $val,
								'amount' => $acamount[$key],
								'tax_amount' => $actax[$key],
								//'is_add' => (isset($arrchk[$val]))?1:0
							]);
				}
				
				//TAX EXTRY.... NOV26
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				if($vatrow) {
					foreach($request->get('acid') as $key => $val) {
						//if($actax[$key] > 0 && isset($arrchk[$val])) {
							$acname[] = $arracname[$key];
							$acid[] = $vatrow->payment_account;
							$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
							$desarr[] = $arracname[$key].'/TAX';
							$refarr[] = $request->get('contract_no');
							$actypearr[] = 'Cr';
							$lnarr[] = $actax[$key];
						//}
					}
				}
				
				//UPDATE SALES NONSTOCK
				$request->merge(['voucher' => 18]);
				$request->merge(['voucher_type' => 6]);
				$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('start_date'))) ]); //$request->get('date')]);
				$request->merge(['voucher_no' => $request->get('si_no')]);
				$request->merge(['remove_item' => '']);
				$request->merge(['account_name' => $acname]);
				$request->merge(['account_id' => $acid]);
				$request->merge(['group_id' => $grparr]);
				$request->merge(['description' => $desarr]);
				$request->merge(['reference' => $refarr]);
				$request->merge(['je_id' => $jearr]);
				$request->merge(['department' => $dptarr]);
				$request->merge(['bank_id' => $bkarr]);
				$request->merge(['cheque_no' => $cqarr]);
				$request->merge(['oldcheque_no' => $cqoarr]);
				$request->merge(['cheque_date' => $cqdarr]);
				$request->merge(['inv_id' => '']);
				$request->merge(['actual_amount' => $actarr]);
				$request->merge(['account_type' => $actypearr]);
				$request->merge(['line_amount' => $lnarr]);
				$request->merge(['job_id' => $jbarr]);
				$request->merge(['difference' => 0]);
				$request->merge(['debit' => $request->get('grand_total')]);
				$request->merge(['credit' => $request->get('grand_total')]);
				//echo '<pre>';print_r($request->all());exit;
				$this->journal->updateSIN($request->get('jid'), $request->all());
			}
			
			Session::flash('message', 'Contract details updated successfully.');
			Session::flash('active', 'home');
			return redirect('contractbuilding/edit/'.$id);
		} catch(ValidationException $e) { 
			return Redirect::to('contractbuilding/add')->withErrors($e->getErrors());
		}

	}
	
	
	public function updateRenew(Request $request, $id)
	{ //echo '<pre>';print_r($request->all());exit;
		try {
			$acname = $acid = $grparr = $siarr = $btarr = $desarr = $refarr = $invarr = $actarr = $actypearr = $lnarr = $jbarr = $dptarr = $vatarr = $cqarr = $bkarr = $cqdarr = $cqoarr = []; 
			DB::table('contract_building')
						->where('id', $id)
						->update([
							'building_id' => $request->get('building_id'),
							'customer_id' => $request->get('customer_id'),
							'flat_no' => $request->get('flat_no'),
							'contract_date' => date('Y-m-d', strtotime($request->get('date'))),
							'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
							'duration' => ($request->get('chkedit'))?$request->get('durationD'):$request->get('duration'),
							'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
							'rent_amount' => $request->get('rent_amount'),
							'passport_no' => $request->get('passport_no'),
							'passport_exp' => $request->get('passport_exp'),
							'nationality' => $request->get('nationality'),
							'description' => $request->get('description'),
							'document' => $document='',
							'file_no' => $request->get('file_no'),
							'terms' => $request->get('terms'),
							'observations' => $request->get('observations'),
							'observations_ot' => $request->get('observations_ot'),
							'grand_total' => $request->get('grand_total')
						]);
						
			if($id) {
				
				$acarr = $request->get('acid');
												
				$acname[] = $request->get('customer_account');
				$acid[] = $request->get('customer_id');
				$grparr[] = 'CUSTOMER';
				$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = $dptarr[] = $bkarr[] = $cqarr[] = $cqoarr[] = $cqdarr[] = '';
				$desarr[] = $request->get('contract_no').'/'.$request->get('si_no');
				$refarr[] = $request->get('contract_no');
				$actypearr[] = 'Dr';
				$lnarr[] = $request->get('grand_total');
				
				$acamount = $request->get('acamount');
				$actax = $request->get('actax');
				$arracname = $request->get('acname');
				$jearr = $request->get('je_id');
				$arrchk = $request->get('check'); //NOV26
				$prearr = DB::table('contract_prepaid')->where('contract_id',$id)->orderBy('id','ASC')->get();
				$gtotal = 0;//NOV26
				$acar = $request->get('acid');
				
				//PREPAID INCOME CR ENTRY.... 
				$acname[] = $arracname[0];
				$acid[] = $acar[0];
				$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = '';
				$bkarr[] = ''; $cqarr[] = ''; $cqoarr[] = ''; $cqdarr[] = '';
				$refarr[] = $request->get('contract_no');
				$actypearr[] = 'Cr';
				$lnarr[] = $acamount[0];
				
				DB::table('contract_prepaid')
						->where('id', $prearr[0]->id)
						->update([
							'account_id' => $acar[0],
							'amount' => $acamount[0],
							'tax_amount' => $actax[0],
						]);
							
				foreach($request->get('acid') as $key => $val) {
							
					if(isset($arrchk[$val])) { 
						$acname[] = $arracname[$key];
						$acid[] = $val;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = '';
						$bkarr[] = ''; $cqarr[] = ''; $cqoarr[] = ''; $cqdarr[] = '';
						$refarr[] = $request->get('contract_no');
						$actypearr[] = 'Cr';
						$lnarr[] = $acamount[$key];
						$gtotal += $acamount[$key];
						
						DB::table('contract_prepaid')
							->where('id', $prearr[$key]->id)
							->update([
								'account_id' => $val,
								'amount' => $acamount[$key],
								'tax_amount' => $actax[$key],
								'is_add' => (isset($arrchk[$val]))?1:0
							]);
					}
					
				}
				
				//TAX EXTRY.... NOV26
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				
				//PREPAID INCOME TAX CR ENTRY...
				if($actax[0] > 0) {
					$acname[] = $arracname[0];
					$acid[] = $vatrow->payment_account;
					$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
					$desarr[] = $arracname[0].'/TAX';
					$refarr[] = $request->get('contract_no');
					$actypearr[] = 'Cr';
					$lnarr[] = $actax[0];
				}	
				
				foreach($request->get('acid') as $key => $val) {
					if(isset($arrchk[$val])) { 
						$acname[] = $arracname[$arrchk[$val]-1];
						$acid[] = $vatrow->payment_account;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$desarr[] = $arracname[$arrchk[$val]-1].'/TAX';
						$refarr[] = $request->get('contract_no');
						$actypearr[] = 'Cr';
						$lnarr[] = $actax[$arrchk[$val]-1];
					}
				}
				
				//UPDATE SALES NONSTOCK
				$request->merge(['voucher' => 18]);
				$request->merge(['voucher_type' => 6]);
				$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('start_date'))) ]); //$request->get('date')]);
				$request->merge(['voucher_no' => $request->get('si_no')]);
				$request->merge(['remove_item' => '']);
				$request->merge(['account_name' => $acname]);
				$request->merge(['account_id' => $acid]);
				$request->merge(['group_id' => $grparr]);
				$request->merge(['description' => $desarr]);
				$request->merge(['reference' => $refarr]);
				$request->merge(['je_id' => $jearr]);
				$request->merge(['department' => $dptarr]);
				$request->merge(['bank_id' => $bkarr]);
				$request->merge(['cheque_no' => $cqarr]);
				$request->merge(['oldcheque_no' => $cqoarr]);
				$request->merge(['cheque_date' => $cqdarr]);
				$request->merge(['inv_id' => '']);
				$request->merge(['actual_amount' => $actarr]);
				$request->merge(['account_type' => $actypearr]);
				$request->merge(['line_amount' => $lnarr]);
				$request->merge(['job_id' => $jbarr]);
				$request->merge(['difference' => 0]);
				$request->merge(['debit' => $request->get('grand_total')]);
				$request->merge(['credit' => $request->get('grand_total')]);
				//echo '<pre>';print_r($request->all());exit;
				$this->journal->updateSIN($request->get('jid'), $request->all());
			}
			
			Session::flash('message', 'Contract details updated successfully.');
			Session::flash('active', 'home');
			return redirect('contractbuilding/edit/'.$id);
		} catch(ValidationException $e) { 
			return Redirect::to('contractbuilding/add')->withErrors($e->getErrors());
		}

	}
	
	
	public function destroy($id)
	{
		$drow = DB::table('contract_building')->where('id',$id)->select('rv_status','drv_status','crv_status')->first();
		if($drow->rv_status > 0) {
			//CHECK PDC SUBMITTED OR NOT IN RV,DRV,ORV..
			$rvscount = DB::table('contract_rvs')
								->join('pdc_received','pdc_received.voucher_id','=','contract_rvs.rv_id')
								->where('contract_rvs.contract_id',$id)
								->where('pdc_received.deleted_at','0000-00-00 00:00:00')
								->where('pdc_received.status',1)
								->count();
			if($rvscount==0) {
				DB::table('contract_building')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			
				$jvs = DB::table('contract_jv')->where('contract_id',$id)->get();
				foreach($jvs as $jv) {
					$this->journal->delete($jv->jv_id);
				}
				
				$pvs = DB::table('contract_pvs')->where('contract_id',$id)->get();
				foreach($pvs as $pv) {
					$this->payment_voucher->delete($pv->pv_id);
				}
				
				$rvs = DB::table('contract_rvs')->where('contract_id',$id)->get();
				foreach($rvs as $rv) {
					$this->receipt_voucher->delete($rv->rv_id);
				}
				
				Session::flash('message', 'Contract deleted successfully.');
			} else 		
				Session::flash('error', 'Contract conaints transactions, you can\'t delete this!');
		} else {
			DB::table('contract_building')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			
			$jvs = DB::table('contract_jv')->where('contract_id',$id)->get();
			foreach($jvs as $jv) {
				$this->journal->delete($jv->jv_id);
			}
			
			$pvs = DB::table('contract_pvs')->where('contract_id',$id)->get();
			foreach($pvs as $pv) {
				$this->payment_voucher->delete($pv->pv_id);
			}
			
			$rvs = DB::table('contract_rvs')->where('contract_id',$id)->get();
			foreach($rvs as $rv) {
				$this->receipt_voucher->delete($rv->rv_id);
			}
			
			Session::flash('message', 'Contract deleted successfully.');
		}
		return redirect('contractbuilding');
	}
	
	
public function destroy2($id)
	{
		$drow = DB::table('contract_building')->where('id',$id)->select('rv_status','drv_status','crv_status')->first();
		if($drow->rv_status > 0 || $drow->drv_status > 0 || $drow->crv_status > 0) {
			Session::flash('error', 'Contract conaints transactions, you can\'t delete this!');
		} else {
			DB::table('contract_building')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			
			$jvs = DB::table('contract_jv')->where('contract_id',$id)->get();
			foreach($jvs as $jv) {
				$this->journal->delete($jv->jv_id);
			}
			
			$pvs = DB::table('contract_pvs')->where('contract_id',$id)->get();
			foreach($pvs as $pv) {
				$this->payment_voucher->delete($pv->pv_id);
			}
			
			$pvs = DB::table('contract_rvs')->where('contract_id',$id)->get();
			foreach($rvs as $rv) {
				$this->receipt_voucher->delete($rv->rv_id);
			}
			
			Session::flash('message', 'Contract deleted successfully.');
		}
		return redirect('contractbuilding');
	}
	
	public function ajaxAllocate(Request $request) {  //NOV8
		
		if($request->get('isday')==0) {  //MONTH CALCULATION...
			
			$adata = []; $duration = $request->get('duration');//floor($request->get('duration')/30);
			$amount = $request->get('amount')/$duration;
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			$day = date('d',strtotime($request->get('date')));
			$month = date('m',strtotime($request->get('date')));
			$year = date('Y',strtotime($request->get('date')));
			if($day==01 || $day==1) {
				$d = 0; 
				for($i=0;$i<$duration;$i++) {
					$jvno = $jvrow->voucher_no + $i;
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $dy = ($nod - $day);
				$amount1 = $dy * $day_amount;
				$jvno = $jvrow->voucher_no;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<$duration;$i++) {
					$jvno = $jvrow->voucher_no + $i;
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
				$dy2 = $nod - $dy;
				$amount2 = $dy2 * $day_amount;
				$jvno = $jvrow->voucher_no + $duration;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));//date('d-m-Y',strtotime($date2)).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($date2)), 'amount' => $amount2, 'desc' => $desc];
			}
			
		} else { //DAYS CALCULATION...
			
			$adata = []; $duration = ($request->get('duration') > 30)?floor($request->get('duration')/30):1;
			$amount = $request->get('amount')/$request->get('duration');
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			$day = date('d',strtotime($request->get('date')));
			$month = date('m',strtotime($request->get('date')));
			$year = date('Y',strtotime($request->get('date')));
			if($day==01 || $day==1) {
				$d = $nodTot = 0; 
				for($i=0;$i<$duration;$i++) {
					$jvno = $jvrow->voucher_no + $i;
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$nod = $nodTot = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
						$day = date('d',strtotime($date));
						$month = date('m',strtotime($date));
						$year = date('Y',strtotime($date));
						$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$nodTot += $nod;
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nod, 'desc' => $desc];
				}
				$remDays = $request->get('duration') - $nodTot;
				if($remDays > 0){
					$jvno = $jvrow->voucher_no + $duration;
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $nodTot = $dy = ($nod - $day);
				$amount1 = ($dy+1) * $amount;
				$jvno = $jvrow->voucher_no;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<ceil($request->get('duration')/30);$i++) {
					$jvno = $jvrow->voucher_no + $i;
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					
					$day = date('d',strtotime($date));
					$month = date('m',strtotime($date));
					$year = date('Y',strtotime($date));
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					
					$remDays = $request->get('duration') - $nodTot;
					if($nod > $remDays) {
						$nd = $remDays;
					} else
						$nd = $nod;
					
					$nodTot += $nd;					
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nd, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
								
				$remDays = $request->get('duration') - ($nodTot+1);
				if($remDays > 0){
					if($d==$duration) {
						$duration = $duration+1;
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					} else
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$jvno = $jvrow->voucher_no + $duration;
					
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			}
		}
		//echo '<pre>';print_r($adata);exit;
		return view('body.contractbuilding.allocate')
		            ->withAmount($request->get('amount'))
					->withAdata($adata);
	}
	
	public function saveRentAllocation(Request $request) {
		ini_set('max_execution_time', 300); 
		//echo '<pre>';print_r($request->all());exit; 
		//JN16
		DB::beginTransaction();
		try {
			$jvset = DB::table('account_setting')->where('voucher_type_id', 16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('id','voucher_no')->first();
			$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
			$jvarr = DB::table('contract_jv')->where('contract_id', $attributes['con_id'])->select('id','jv_id')->orderBy('id','ASC')->get();
			$edit = false;
			if(isset($attributes['voucher_date'])){

					foreach($attributes['voucher_date'] as $key => $val) {
						
						$input['from_jv'] = 1;
						$input['voucher'] = $jvset->id; //JN16
						$input['voucher_type'] = 16;
						$input['voucher_date'] = $attributes['voucher_date'][$key];
						$input['voucher_no'] = '';//$attributes['voucher_no'][$key];
						$input['account_name'] = [ $attributes['preinc_account'],$attributes['inc_account'] ];
						$input['account_id'] = [$attributes['preinc_acid'], $attributes['inc_acid'] ];
						$input['group_id'] = ['',''];
						$input['sales_invoice_id'] = ['',''];
						$input['bill_type'] = ['',''];
						$input['description'] = [ $attributes['description'][$key],$attributes['description'][$key] ];
						$input['reference'] = [$attributes['reference'], $attributes['reference'] ];
						$input['inv_id'] = ['',''];
						$input['actual_amount'] = ['',''];
						$input['account_type'] = ['Dr','Cr'];
						$input['line_amount'] = [$attributes['line_amount'][$key], $attributes['line_amount'][$key] ];
						$input['job_id'] = ['',''];
						$input['difference'] = 0;
						$input['curno'] = '';
						$input['debit'] = $attributes['line_amount'][$key];
						$input['credit'] =  $attributes['line_amount'][$key];
						
						if(isset($jvarr[$key])) {
							$this->updateJV($jvarr[$key], $input); 
							$edit = true;
						} else {
							$jvid = $this->createJV($input); 
							DB::table('contract_jv')->insert(['contract_id'=> $attributes['con_id'], 'jv_id' => $jvid]);
						}
					}
			}
			DB::table('contract_building')->where('id',$attributes['con_id'])->update(['rv_status' => 1]);

			DB::commit();
			
			if($edit) {
				Session::flash('message', 'Rent allocation has been updated successfully.');
				Session::flash('active', 'rentallo');
				return redirect('contractbuilding/edit/'.$attributes['con_id']);
			} else {
				Session::flash('message', 'Rent allocation has been successfully completed.');
				Session::flash('active', 'rentallo');
				return redirect('contractbuilding/add/'.$attributes['con_id']);
			}

		} catch (\Exception $e) {
			  
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			return false;
		}
	}
	
	private function createJV($attributes) {
		
		$jvid = null;
		//VOUCHER NO LOGIC.....................
		// 2️⃣ Get the highest numeric part from voucher_master
		$maxNumeric = DB::table('journal')
			->where('deleted_at', '0000-00-00 00:0:00')
			//->where('department_id', $departmentId)
			->where('status', 1)->where('voucher_type', 'JV')
			->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
			->value('max_no');
		
		$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
		$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();//echo '<pre>';print_r($accset);
		$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);
		//VOUCHER NO LOGIC.....................
		//exit;
		$maxRetries = 5; // prevent infinite loop
		$retryCount = 0;
		$saved = false;
		
		while (!$saved && $retryCount < $maxRetries) {
			try {
				$jvid = DB::table('journal')->insertGetId([
							'voucher_type' => 'JV',
							'voucher_no'	=> $attributes['voucher_no'],
							'voucher_date' => date('Y-m-d',strtotime($attributes['voucher_date'])),
							'debit' => $attributes['debit'],
							'credit' => $attributes['credit'],
							'status' => 1,
							'created_at' => date('Y-m-d H:i:s')
						]);

				} catch (\Illuminate\Database\QueryException $ex) {

					// Check if it's a duplicate voucher number error
					if (strpos($ex->getMessage(), 'Duplicate entry') !== false ||
						strpos($ex->getMessage(), 'duplicate key value') !== false) {

						$maxNumeric = DB::table('journal')
							->where('deleted_at', '0000-00-00 00:0:00')
							//->where('department_id', $departmentId)
							->where('status', 1)->where('voucher_type', 'JV')
							->select(DB::raw("MAX(CAST(REGEXP_REPLACE(voucher_no, '[^0-9]', '') AS UNSIGNED)) AS max_no"))
							->value('max_no');
						
						$dept = isset($attributes['department_id'])?$attributes['department_id']:0;
						$accset = DB::table('account_setting')->where('id',$attributes['voucher'])->first();
						$attributes['voucher_no'] = $this->objUtility->generateVoucherNo($accset->id, $maxNumeric, $dept, $attributes['voucher_no']);

						$retryCount++;
					} else {
						throw $ex; //echo $ex;exit;// rethrow if different DB error
					}
				}
		}

		if($jvid) {
				
			foreach($attributes['line_amount'] as $key => $value) { 
				$jveid = DB::table('journal_entry')->insertGetId([
						'journal_id' => $jvid,
						'account_id' => $attributes['account_id'][$key],
						'description' => $attributes['description'][$key],
						'reference' => $attributes['reference'][$key],
						'entry_type' => $attributes['account_type'][$key],
						'amount' => $value,
						'status' => 1
					]);
					
				$this->setAccountTransaction($attributes, $jveid, $key);
				$this->objUtility->tallyClosingBalance($attributes['account_id'][$key]);
			}
		}
		
		return $jvid;
	}
	
	
	private function updateJV($jvd, $attributes) {
		
		DB::table('journal')
				->where('id',$jvd->jv_id)
				->update([
					'voucher_date' => date('Y-m-d',strtotime($attributes['voucher_date'])),
					'debit' => $attributes['debit'],
					'credit' => $attributes['credit']
				]);
		
		$jearr = DB::table('journal_entry')->where('journal_id',$jvd->jv_id)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','ASC')->select('id')->get();
		
		foreach($attributes['line_amount'] as $key => $value) { 
			DB::table('journal_entry')
				->where('id',$jearr[$key]->id)
				->update([
					'account_id' => $attributes['account_id'][$key],
					'description' => $attributes['description'][$key],
					'reference' => $attributes['reference'][$key],
					'entry_type' => $attributes['account_type'][$key],
					'amount' => $value,
				]);
				
			$this->setAccountTransactionUpdate($attributes, $jearr[$key]->id, $key);
			$this->objUtility->tallyClosingBalance($attributes['account_id'][$key]);
		}
		return true;			
	}
	
	private function setAccountTransactionUpdate($attributes, $journal_id, $key)
	{
		
		DB::table('account_transaction')
				->where('voucher_type', 'JV')
				->where('voucher_type_id', $journal_id)
				->update([ 'account_master_id' => $attributes['account_id'][$key],
							'transaction_type'  => $attributes['account_type'][$key],
							'amount'   			=> $attributes['line_amount'][$key],
							'modify_at' 		=> date('Y-m-d H:i:s'),
							'modify_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key]
							]);
		
		return true;
	}
	
	private function setAccountTransaction($attributes, $journal_id, $key)
	{
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'JV',
						    'voucher_type_id'   => $journal_id,
							'account_master_id' => $attributes['account_id'][$key],
							'transaction_type'  => $attributes['account_type'][$key],
							'amount'   			=> $attributes['line_amount'][$key],
							'status' 			=> 1,
							'created_at' 		=> date('Y-m-d H:i:s'),
							'created_by' 		=> Auth::User()->id,
							'description' 		=> $attributes['description'][$key],
							'reference'			=> $attributes['voucher_no'],
							'invoice_date'		=> date('Y-m-d', strtotime($attributes['voucher_date'])),
							'reference_from'	=> $attributes['reference'][$key]
							]);
		
		return true;
	}
	
	public function ajaxReceiptAdd(Request $request) {
		$data = [];
		$inst = $request->get('inst');
		$amount = $request->get('amount');
		$divamt = $amount/$inst; $n=0;
		for($i=0;$i<$inst;$i++) { $n++;
			if($n==1)
				$instmnt = '1st Installment';
			elseif($n==2)
				$instmnt = '2nd Installment';
			if($n==3)
				$instmnt = '3rd Installment';
			elseif($n > 3)
				$instmnt = ($i+1).'th Installment';
			$data[] = (object)['acname' => $request->get('cac'),'acid' => $request->get('cacid'),'ref' => urldecode($request->get('ref')),
						'desc' => urldecode($request->get('ref')).'/'.urldecode($request->get('dec')),'amount' => round($divamt,2),'inst' => $instmnt,'reid' => ''];
		}
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();//NOV8
		return view('body.contractbuilding.receiptadd')
					->withType($request->get('type'))
					->withCac($request->get('cac'))
					->withCacid($request->get('cacid'))
					->withBanks($banks)
					->withData($data);
		
	}
	
	public function saveReceipt(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
		//$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
			
			$rvrow = DB::table('contract_rvs')->where('contract_id', $request->get('con_id'))->where('type','RV')->first();
			
			$acname[] = $request->get('tenant');
			$acid[] = $request->get('tenant_id');
			$actypearr[] = 'Cr';
			$lnarr[] = $request->get('rv_amount');
			$arr_acname = $request->get('drac_name');
			$arr_trtype = $request->get('tr_type');
			$arr_desc = $request->get('description');
			$arr_ref = $request->get('reference');
			$arr_amt = $request->get('amount');
			$arr_bnk = $request->get('bank_id');
			$arr_chq = $request->get('cheque_no');
			$arr_chqdt = $request->get('cheque_date');
			$jearr = $request->get('je_id');
			$desarr[] = $arr_desc[0];
			$refarr[] = $arr_ref[0]; $bnkarr[] = ''; $chqarr[] = ''; $chqdtarr[] = ''; $pmode[] = '';
			$grparr[] = 'CUSTOMER'; $vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = ''; $pryarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
			$ispdc = false; $vtype = 9;
			foreach($request->get('drac_id') as $key => $val) {
				$acname[] = $arr_acname[$key];
				$acid[] = $val;
				if($arr_trtype[$key]=='C') {
					$grparr[] = 'CASH';
					$pryarr[] = ''; $vtype = 9; $pmode[] = 0;
				} else if($arr_trtype[$key]=='P') {
					$grparr[] = 'PDCR'; $ispdc = true; $vtype = 'PDCR';
					$pryarr[] = $request->get('tenant_id'); $pmode[] = 2;
				} else if($arr_trtype[$key]=='B') {
					$grparr[] = 'BANK'; $ispdc = true; $vtype = 'BANK';
					$pryarr[] = $request->get('tenant_id'); $pmode[] = 1;
				}
				$desarr[] = $arr_desc[$key];
				$refarr[] = $arr_ref[$key];
				
				$vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
				$actypearr[] = 'Dr';
				$lnarr[] = $arr_amt[$key];
				$bnkarr[] = $arr_bnk[$key];
				$chqarr[] = $arr_chq[$key];
				$chqdtarr[] = $arr_chqdt[$key];
				 
			}

			$voucherRv = DB::table('account_setting')->where('voucher_type_id',9)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();
			
			$request->merge(['from_jv' => 1]);
			$request->merge(['chktype' => ($ispdc)?'PDCR':'']);
			$request->merge(['is_onaccount' => 1]);
			$request->merge(['voucher' => $voucherRv->id]);
			$request->merge(['voucher_type' => $vtype]);
			$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('rv_date')))]);
			$request->merge(['voucher_no' => '']);//$request->get('rv_no')
			$request->merge(['account_name' => $acname]);
			$request->merge(['account_id' => $acid]);
			$request->merge(['group_id' => $grparr]);
			$request->merge(['vatamt' => $vatamt]);
			$request->merge(['sales_invoice_id' => $siarr]);
			$request->merge(['bill_type' => $btarr]);
			$request->merge(['description' => $desarr]);
			$request->merge(['reference' => $refarr]);
			$request->merge(['je_id' => $jearr]);
			$request->merge(['inv_id' => $invarr]);
			$request->merge(['actual_amount' => $actarr]);
			$request->merge(['account_type' => $actypearr]);
			$request->merge(['line_amount' => $lnarr]);
			$request->merge(['job_id' => $jbarr]);
			$request->merge(['bank_id' => $bnkarr]);
			$request->merge(['cheque_no' => $chqarr]);
			$request->merge(['cheque_date' => $chqdtarr]);
			$request->merge(['department' => $dptarr]);
			$request->merge(['partyac_id' => $pryarr]);
			$request->merge(['party_name' => $prtnarr]);
			$request->merge(['tr_id' => $trarr]);
			$request->merge(['difference' => 0]);
			$request->merge(['remove_item' => '']);
			$request->merge(['trn_no' => '']);
			$request->merge(['curno' => '']);
			$request->merge(['debit' => $request->get('rv_amount')]);
			$request->merge(['credit' => $request->get('rv_amount')]);
			$request->merge(['currency_id' => $pmode]); //CURRENCY ID SUBSTITUTE AS P.MODE... NOV23
			//echo '<pre>';print_r($request->all());exit;
			
			if($rvrow) {
				$rvid = $this->receipt_voucher->update($rvrow->rv_id, $request->all());
				DB::table('contract_rvs')->where('contract_id', $request->get('con_id'))->where('type','RV')->update(['installment' => $request->get('installment'),'amount' => $request->get('rv_amount')]);
				
				DB::table('contract_building')->where('id',$request->get('con_id'))->update(['rv_status' => 2]);
				Session::flash('message', 'Receipt updated successfully.');
				Session::flash('active', 'receipt');
				return redirect('contractbuilding/edit/'.$request->get('con_id'));
				
			} else {
				$rvid = $this->receipt_voucher->create($request->all());
				if($rvid) {
					DB::table('contract_rvs')->insert(['contract_id' => $request->get('con_id'), 'rv_id' => $rvid, 'installment' => $request->get('installment'),
											 'amount' => $request->get('rv_amount'), 'type' => 'RV']);

					DB::table('contract_building')->where('id',$request->get('con_id'))->update(['rv_status' => 2]);
					Session::flash('message', 'Receipt added successfully.');
					Session::flash('active', 'receipt');
				}
											 
				
				return redirect('contractbuilding/add/'.$request->get('con_id'));
			}
			
		
	}
	
	
	public function saveDeposit(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit;
		//$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
			$drvrow = DB::table('contract_rvs')->where('contract_id', $request->get('con_id'))->where('type','DRV')->first();
			
			$acname[] = $request->get('tenant');
			$acid[] = $request->get('tenant_id');
			$actypearr[] = 'Cr';
			$lnarr[] = $request->get('rv_amount');
			$arr_acname = $request->get('drac_name');
			$arr_trtype = $request->get('tr_type');
			$arr_desc = $request->get('description');
			$arr_ref = $request->get('reference');
			$arr_amt = $request->get('amount');
			$arr_bnk = $request->get('bank_id');
			$arr_chq = $request->get('cheque_no');
			$arr_chqdt = $request->get('cheque_date');
			$jearr = $request->get('je_id');
			$desarr[] = $arr_desc[0];
			$refarr[] = $arr_ref[0]; $bnkarr[] = ''; $chqarr[] = ''; $chqdtarr[] = '';
			$grparr[] = $vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = ''; $pryarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
			$ispdc = false; $vtype = 9;
			foreach($request->get('drac_id') as $key => $val) {
				$acname[] = $arr_acname[$key];
				$acid[] = $val;
				if($arr_trtype[$key]=='C') {
					$grparr[] = 'CASH'; $pryarr[] = ''; $vtype = 9;
				} else if($arr_trtype[$key]=='P') {
					$grparr[] = $vtype = 'PDCR'; $ispdc = true; $pryarr[] = $request->get('tenant_id'); //NOV8
				} else if($arr_trtype[$key]=='B') {
					$grparr[] = $vtype = 'BANK'; $ispdc = true;
					$pryarr[] = $request->get('tenant_id');
				}
				$desarr[] = $arr_desc[$key];
				$refarr[] = $arr_ref[$key];
				
				$vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $dptarr[] = '';  $prtnarr[] = ''; $trarr[] = '';
				$actypearr[] = 'Dr';
				$lnarr[] = $arr_amt[$key];
				$bnkarr[] = $arr_bnk[$key];
				$chqarr[] = $arr_chq[$key];
				$chqdtarr[] = $arr_chqdt[$key];
			}
			
			$voucherRv = DB::table('account_setting')->where('voucher_type_id',9)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();

			$request->merge(['from_jv' => 1]);
			$request->merge(['chktype' => ($ispdc)?'PDCR':'']);
			$request->merge(['is_onaccount' => 1]);
			$request->merge(['voucher' => $voucherRv->id]);
			$request->merge(['voucher_type' => $vtype]);
			$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('drv_date')))]);
			$request->merge(['voucher_no' => '']);//$request->get('rv_no')
			$request->merge(['account_name' => $acname]);
			$request->merge(['account_id' => $acid]);
			$request->merge(['group_id' => $grparr]);
			$request->merge(['vatamt' => $vatamt]);
			$request->merge(['sales_invoice_id' => $siarr]);
			$request->merge(['bill_type' => $btarr]);
			$request->merge(['description' => $desarr]);
			$request->merge(['reference' => $refarr]);
			$request->merge(['inv_id' => $invarr]);
			$request->merge(['actual_amount' => $actarr]);
			$request->merge(['account_type' => $actypearr]);
			$request->merge(['line_amount' => $lnarr]);
			$request->merge(['job_id' => $jbarr]);
			$request->merge(['bank_id' => $bnkarr]);
			$request->merge(['cheque_no' => $chqarr]);
			$request->merge(['cheque_date' => $chqdtarr]);
			$request->merge(['department' => $dptarr]);
			$request->merge(['partyac_id' => $pryarr]);
			$request->merge(['party_name' => $prtnarr]);
			$request->merge(['je_id' => $jearr]);
			$request->merge(['difference' => 0]);
			$request->merge(['tr_id' => $trarr]);
			$request->merge(['trn_no' => '']);
			$request->merge(['curno' => '']);
			$request->merge(['remove_item' => '']);
			$request->merge(['debit' => $request->get('rv_amount')]);
			$request->merge(['credit' => $request->get('rv_amount')]);
			//echo '<pre>';print_r($request->all());exit;
			if($drvrow) {
				
				$this->receipt_voucher->update($drvrow->rv_id,$request->all());
				
				DB::table('contract_rvs')->where('contract_id',$request->get('con_id'))->where('type','DRV')->update(['amount' => $request->get('rv_amount')]);
												 
				Session::flash('message', 'Deposit receipt updated successfully.');
				Session::flash('active', 'deposit');
				return redirect('contractbuilding/edit/'.$request->get('con_id'));
				
			} else { 
				$rvid = $this->receipt_voucher->create($request->all());
				if($rvid) {
					DB::table('contract_rvs')->insert(['contract_id' => $request->get('con_id'), 'rv_id' => $rvid,
													'amount' => $request->get('rv_amount'), 'type' => 'DRV']);

					DB::table('contract_building')->where('id',$request->get('con_id'))->update(['rv_status' => 3]);
		
					Session::flash('message', 'Deposit receipt added successfully.');
					Session::flash('active', 'deposit');
				}
													
				
				return redirect('contractbuilding/add/'.$request->get('con_id'));
				
				
			}
			
		
	}
	
	public function saveOtherRv(Request $request) {
		
		//echo '<pre>';print_r($request->all());exit; lineamount
		//$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
			$orvrow = DB::table('contract_rvs')->where('contract_id', $request->get('con_id'))->where('rv_id',$request->get('rv_id'))->where('type','ORV')->first();  //NOV24
			//$acname[] = $request->get('tenant');
			//$acid[] = $request->get('tenant_id'); Dcheque_no
			
			$arr_acname = $request->get('account_name');
			$arr_desc = $request->get('description');
			$arr_ref = $request->get('reference');
			$arr_amt = $request->get('line_amount');
			$arr_bnk = $request->get('bank_id');
			$arr_chq = $request->get('cheque_no');
			$arr_chqdt = $request->get('cheque_date');
			$jearr = $request->get('je_id');
			$arr_exp = $request->get('expacid');
			$arr_fc = $request->get('isfc');
			
			//$desarr[] = $arr_desc[0];
			$vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $pryarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
			$arrgrp = $request->get('group_id');
			foreach($request->get('account_id') as $key => $val) {
				$acname[] = $arr_acname[$key];
				$acid[] = $partyid = $val;
				$grparr[] = $arrgrp[$key];
				$desarr[] = $arr_desc[$key];
				$refarr[] = $arr_ref[$key];
				$vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $pryarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
				$actypearr[] = 'Cr';
				$lnarr[] = $arr_amt[$key];
				$dptarr[] = $arr_exp[$key];
				$fcarr[] = $arr_fc[$key];
				$bnkarr[] = '';
				$chqarr[] = '';
				$chqdtarr[] = '';
				$pmode[] = '';
			}
			
			$arr_dracname = $request->get('drac_name');
			$arr_ddscp = $request->get('Ddescription');
			$arr_drfc = $request->get('Dreference');
			$arr_damt = $request->get('Damount');
			$arr_trtype = $request->get('tr_type');
			$ispdc = false; $vtype = 9;
			foreach($request->get('drac_id') as $key => $val) {
				$actypearr[] = 'Dr';
				$acname[] = $arr_dracname[$key];
				$acid[] = $val;
				
				if($arr_trtype[$key]=='C') {
					$grparr[] = 'CASH'; $pryarr[] = ''; $vtype = 9; $pmode[] = 0;
				} else if($arr_trtype[$key]=='P') {
					$grparr[] = $vtype = 'PDCR'; $ispdc = true; $pryarr[] = $partyid; $pmode[] = 2;
				} else if($arr_trtype[$key]=='B') {
					$grparr[] = $vtype = 'BANK'; $ispdc = true;
					$pryarr[] = $partyid; $pmode[] = 1;
				}
				
				$desarr[] = $arr_ddscp[$key];
				$refarr[] = $arr_drfc[$key];
				$bnkarr[] = $arr_bnk[$key];
				$chqarr[] = $arr_chq[$key];
				$chqdtarr[] = $arr_chqdt[$key];
				$lnarr[] = $arr_damt[$key];
				$dptarr[] = '';
				$fcarr[] = '';
			}
			
			$voucherRv = DB::table('account_setting')->where('voucher_type_id',9)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->first();

			$request->merge(['from_jv' => 1]);
			$request->merge(['chktype' => '']);
			$request->merge(['is_onaccount' => 1]);
			$request->merge(['voucher' => $voucherRv->id]);
			$request->merge(['voucher_type' => $vtype]);
			$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('orv_date')))]);
			$request->merge(['voucher_no' => '']);//$request->get('rv_no')
			$request->merge(['account_name' => $acname]);
			$request->merge(['account_id' => $acid]);
			$request->merge(['group_id' => $grparr]);
			$request->merge(['vatamt' => $vatamt]);
			$request->merge(['sales_invoice_id' => $siarr]);
			$request->merge(['bill_type' => $btarr]);
			$request->merge(['description' => $desarr]);
			$request->merge(['reference' => $refarr]);
			$request->merge(['inv_id' => $invarr]);
			$request->merge(['actual_amount' => $actarr]);
			$request->merge(['account_type' => $actypearr]);
			$request->merge(['line_amount' => $lnarr]);
			$request->merge(['job_id' => $jbarr]);
			$request->merge(['bank_id' => $bnkarr]);
			$request->merge(['cheque_no' => $chqarr]);
			$request->merge(['cheque_date' => $chqdtarr]);
			$request->merge(['department' => $dptarr]); //ID OF EXP AC ID
			$request->merge(['is_fc' => $fcarr]); //IS TAX A/c. OR NOT
			$request->merge(['partyac_id' => $pryarr]);
			$request->merge(['party_name' => $prtnarr]);
			$request->merge(['difference' => 0]);
			$request->merge(['tr_id' => $trarr]);
			$request->merge(['je_id' => $jearr]);
			$request->merge(['remove_item' => '']);
			$request->merge(['trn_no' => '']);
			$request->merge(['curno' => '']);
			$request->merge(['debit' => $request->get('rv_amount')]);
			$request->merge(['credit' => $request->get('rv_amount')]);
			$request->merge(['currency_id' => $pmode]);
			//echo '<pre>';print_r($request->all());exit;
			
			if($request->get('type')=='edit') {
				$this->receipt_voucher->update($orvrow->rv_id,$request->all());
				
				DB::table('contract_rvs')->where('contract_id',$request->get('con_id'))->where('type','ORV')->update(['amount' => $request->get('rv_amount')]);
				
				Session::flash('message', 'Other receipt updated successfully.');
				Session::flash('active', 'otherrv');
				return redirect('contractbuilding/edit/'.$request->get('con_id'));
			} else {
				$rvid = $this->receipt_voucher->create($request->all());
				
				if($rvid) {
					DB::table('contract_rvs')->insert(['contract_id' => $request->get('con_id'), 'rv_id' => $rvid,
												 'amount' => $request->get('rv_amount'), 'type' => 'ORV']);

					DB::table('contract_building')->where('id',$request->get('con_id'))->update(['rv_status' => 4]);
				
					Session::flash('message', 'Other receipt added successfully.');
					Session::flash('active', 'otherrv');
				}

				
				return redirect('contractbuilding/add/'.$request->get('con_id'));
			}
	}
	
	public function printRv($type,$id)
	{ 
		if($type=='ORV') //NOV5
			$rowrv = DB::table('contract_rvs')->where('id',$id)->where('type',$type)->select('rv_id')->first(); 
		else
			$rowrv = DB::table('contract_rvs')->where('contract_id',$id)->where('type',$type)->select('rv_id')->first(); //...
		
		$voucherhead = 'Receipt Voucher';
		$crrow = $this->receipt_voucher->find($rowrv->rv_id); 
		$invoicerow = $this->receipt_voucher->findRVdata($rowrv->rv_id); //echo '<pre>';print_r($invoicerow);exit;
				
		$words = $this->number_to_word($crrow->debit);
		$arr = explode('.',number_format($crrow->debit,2));
		if(sizeof($arr) >1 ) {
			if($arr[1]!=00) {
				$dec = $this->number_to_word($arr[1]);
				$words .= ' and Fils '.$dec.' Only';
			} else 
				$words .= ' Only';
		} else
			$words .= ' Only'; 
		
		return view('body.contractbuilding.printgrp')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}
	
	
	public function printPv($id)
	{ 
		$rowpv = DB::table('contract_pvs')->where('id',$id)->select('pv_id')->first(); 
		
		$voucherhead = 'Payment Voucher';
		$crrow = $this->payment_voucher->find($rowpv->pv_id);
		$invoicerow = $this->payment_voucher->findPVdata($rowpv->pv_id); // echo '<pre>';print_r($invoicerow);exit;
				
		$words = $this->number_to_word($crrow->debit);
		$arr = explode('.',number_format($crrow->debit,2));
		if(sizeof($arr) >1 ) {
			if($arr[1]!=00) {
				$dec = $this->number_to_word($arr[1]);
				$words .= ' and Fils '.$dec.' Only';
			} else 
				$words .= ' Only';
		} else
			$words .= ' Only'; 
		
		return view('body.contractbuilding.printpv')
					->withVoucherhead($voucherhead)
					->withDetails($crrow)
					->withInvoicerow($invoicerow)
					->withAmtwords($words);
	}

	public function printSi($id) { 
		
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();
			
		$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')
								->join('journal_entry AS JE','JE.journal_id','=','journal.id')
								->select('JE.id','journal.id AS jid')->orderBy('JE.id','ASC')->get();
		
	}
	
	
	public function osRvs($id,$n,$rid=null,$type=null) {
		
		$rowcon = DB::table('contract_building')->where('id',$id)->select('renew_id')->first();
		
		if($rowcon->renew_id=='') {
			$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->where('contract_prepaid.is_add',1)
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname1','contract_prepaid.*')
							->get();
		} else {
			$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)//->where('contract_prepaid.is_add',1)
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname1','contract_prepaid.*')
							->get();
		}
		
		
		$orvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.*','M.master_name','contract_rvs.rv_id')
								->orderBy('RE.id','ASC')->get();
		
								
		$acarr = $osarr = []; $total = $txtotal = $othr_rv_amt = 0;
		foreach($acamounts as $k => $row) {
			$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
			$total += $row->amount;
			$txtotal += $row->tax_amount;
			
			if($k > 1) {
				$othr_rv_amt += $row->amount;
			}
		}
		
		$orvarr = $txarr = $paidarr = $txpaidarr = $rvarr = $txrvarr = [];
		if($rid) { //GETTING PAID A/c IDS...
			foreach($orvs as $orv) {
				if($orv->entry_type=='Cr') {
					$orvarr[] = $orv->department_id; 
					
					if($orv->is_fc==1) {
						$txarr[] = $orv->department_id;
						$txpaidarr[$orv->department_id] = $orv->amount; 
						
						if($type)
							$txrvarr[$orv->department_id] = $orv->receipt_voucher_id;
					
					} else {
						$paidarr[$orv->department_id] = $orv->amount; 
						
						if($type)
							$rvarr[$orv->department_id] = $orv->receipt_voucher_id;
					}
				}
			}
		}
		
		//echo '<pre>';print_r($acamounts);print_r($orvs);print_r($txarr);print_r($orvarr);print_r($paidarr);print_r($txpaidarr);print_r($rvarr);print_r($txrvarr);exit;  
		
		$orvactx_arr = [0 => 'Prepaid Income Tax', 1 => 'Deposit Tax', 2 => 'Security Deposit Tax', 3 => 'Commission Tax', 4 => 'Other Deposit Tax', 5 => 'Parking Income Tax', 6 => 'Ejarie Fee Tax'];
		$orvac_arr = [0 => '', 1 => '', 2 => 'Security Deposit', 3 => 'Commission', 4 => 'Other Deposit', 5 => 'Parking Income', 6 => 'Ejarie Fee'];
		return view('body.contractbuilding.osrvs')
						->withPayacnts($acamounts)
						->withOracarr($orvac_arr)
						->withOrv($orvarr)
						->withTxrv($txarr)
						->withPdar($paidarr)
						->withTxpdar($txpaidarr)
						->withRvarr($rvarr)
						->withTxrvarr($txrvarr)
						->withType($type) //NOV24
						->withRid($rid)
						->withOractxarr($orvactx_arr);
			
	}
	
	
	public function doClose($id) {
		
		$printrvs = $crow = $acrow = $acarr = $jvs = $rvs = $drvs = $orvs = $orvac_arr = $orvactx_arr = null; $total = $txtotal = $acamounts = $othr_rv_amt = 0;
		$orvarr = $txarr = [];
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();
					
			$acrow = DB::table('contra_type')  
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M2', 'M2.id', '=', 'contra_type.deposit')
								->leftJoin('account_master AS M3', 'M3.id', '=', 'contra_type.water_ecty')
								->leftJoin('account_master AS M4', 'M4.id', '=', 'contra_type.commission')
								->leftJoin('account_master AS M5', 'M5.id', '=', 'contra_type.other_deposit')
								->leftJoin('account_master AS M6', 'M6.id', '=', 'contra_type.parking')
								->leftJoin('account_master AS M7', 'M7.id', '=', 'contra_type.ejarie_fee')
								->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
								->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
								->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
								->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
								->leftJoin('account_master AS M12', 'M12.id', '=', 'contra_type.booking_oth')
								->leftJoin('account_master AS M13', 'M13.id', '=', 'contra_type.chq_charge')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M2.master_name AS acname2','M3.master_name AS acname3','M4.master_name AS acname4',
										'M5.master_name AS acname5','M6.master_name AS acname6','M7.master_name AS acname7','M8.master_name AS acname8',
										'M9.master_name AS acname9','M10.master_name AS acname10','M11.master_name AS acname11','M12.master_name AS acname12',
										'M13.master_name AS acname13','M14.master_name AS acname14','contra_type.type','contra_type.increment_no','B.ownername',
										'B.location','B.mobno','B.plot_no','contra_type.*')
								->where('contra_type.buildingid',$crow->building_id)->first(); 
			
			
			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')
								->join('journal_entry AS JE','JE.journal_id','=','journal.id')
								->select('JE.id','journal.id AS jid' /* ,'JE.account_id','JE.entry_type','JE.amount' */)->orderBy('JE.id','ASC')->get();
			
			//echo '<pre>';print_r($acrow);exit;
			$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->where('is_add',1)
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname1','contract_prepaid.*')
							->get(); //echo '<pre>';print_r($acamounts);exit;
										
			
			//$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->get();
			$acarr = []; 
			foreach($acamounts as $k => $row) {
				$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
				$total += $row->amount;
				$txtotal += $row->tax_amount;
				
				if($k > 1) {
					$othr_rv_amt += $row->amount;
				}
			}
			//echo '<pre>';print_r($acarr);exit;
			$orvactx_arr = [0 => 'Prepaid Income Tax', 1 => 'Deposit Tax', 2 => 'Security Deposit Tax', 3 => 'Commission Tax', 4 => 'Other Deposit Tax', 5 => 'Parking Income Tax', 6 => 'Ejarie Fee Tax'];
			$orvac_arr = [0 => '', 1 => '', 2 => 'Security Deposit', 3 => 'Commission', 4 => 'Other Deposit', 5 => 'Parking Income', 6 => 'Ejarie Fee'];
			
			$jvs = DB::table('contract_jv')->where('contract_jv.contract_id',$id)
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount')
								->get();
								
			$rvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','RV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','M.master_name','RE.*','contract_rvs.installment')
								->orderBy('RE.id','ASC')->get();  //echo '<pre>';print_r($rvs);exit;
								
			$drvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','DRV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.*','M.master_name')
								->orderBy('RE.id','ASC')->get(); //echo '<pre>';print_r($drvs);exit;
								
			$orvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','R.debit','RE.*','M.master_name','contract_rvs.rv_id')
								->orderBy('RE.id','ASC')->get();
			
			$printrvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV') //NOV5
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->select('R.voucher_no','contract_rvs.id')
								->get();
								
			//echo '<pre>';print_r($orvs);exit; 
			
			//GETTING PAID A/c IDS...
			foreach($orvs as $orv) {
				if($orv->entry_type=='Cr') {
					$orvarr[] = $orv->department_id; 
					if($orv->is_fc==1)
						$txarr[] = $orv->department_id;
				}
			}
			
			if(!Session::has('message'))
				Session::flash('active', 'home');
			
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
		$rvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',9)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		$duration = DB::table('duration')->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.contractbuilding.close')
					->withBuildingmaster($buildingmaster)
					->withSirow($sirow)
					->withConid($id)
					->withCrow($crow)
					->withBanks($banks)
					->withRvrow($rvrow)
					->withAcrow($acrow)
					->withAcamt($acarr)
					->withJvs($jvs)
					->withRvs($rvs)
					->withDrvs($drvs)
					->withOrvs($orvs)
					->withTotal($total)
					->withPayacnts($acamounts)
					->withOramt($othr_rv_amt+$txtotal)
					->withOracarr($orvac_arr)
					->withOractxarr($orvactx_arr)
					->withJerow($jerow)
					->withOrv($orvarr)
					->withTxrv($txarr)
					->withPrvs($printrvs)
					->withDuration($duration)
					->withSettings($this->acsettings)
					->withTxtotal($txtotal);
	}
	
	public function submitClose($id) {
		
		DB::table('contract_building')->where('id',$id)->update(['status' => 0, 'is_close' => 1]);
		Session::flash('message', 'Contract has been closed successfully.');
		return redirect('contractbuilding/enquiry');
	}
	
	public function renewSave(Request $request) { //echo '<pre>';print_r($request->all());exit; grand_total total tax_total
		try {
			$acname = $acid = $grparr = $siarr = $btarr = $desarr = $refarr = $invarr = $actarr = $actypearr = $lnarr = $jbarr = $vatarr = []; 
			$conid = DB::table('contract_building')
						->insertGetId([
							'building_id' => $request->get('building_id'),
							'contract_date' => date('Y-m-d', strtotime($request->get('date'))),
							'contract_no' => $request->get('contract_no'),
							'si_no' => $request->get('si_no'),
							'customer_id' => $request->get('customer_id'),
							'flat_no' => $request->get('flat_no'),
							'start_date' => date('Y-m-d', strtotime($request->get('start_date'))),
							'duration' => ($request->get('chkedit'))?$request->get('durationD'):$request->get('duration'),
							'end_date' => date('Y-m-d', strtotime($request->get('end_date'))),
							'rent_amount' => $request->get('rent_amount'),
							'passport_no' => $request->get('passport_no'),
							'passport_exp' => $request->get('passport_exp'),
							'nationality' => $request->get('nationality'),
							'description' => $request->get('description'),
							'document' => $document='',
							'file_no' => $request->get('file_no'),
							'terms' => $request->get('terms'),
							'observations' => $request->get('observations'),
							'observations_ot' => $request->get('observations_ot'),
							'grand_total' => $request->get('grand_total'),
							'status' => 1,
							'renew_id'	=> $request->get('conid'),
							'is_day' => ($request->get('chkedit'))?1:0
						]);
						
						DB::table('contract_building')->where('id',$request->get('conid'))->update(['status' => 0,'is_close' => 0]);
						
						$attachment=DB::table('contract_attachment')->where('contract_id',$request->get('conid'))->get();
						//echo '<pre>';print_r($attachment);exit;
					if(isset($attachment)){
			 	       foreach($attachment as $attach) {
			
				              DB::table('contract_attachment')
						           ->insert(['contract_id' => $conid, 
								  'file_name' => $attach->file_name,
								  'name'	=> $attach->name,
							     	]);  
			 	        }
					}
			if($conid) {
				DB::table('contra_type')->where('buildingid',$request->get('building_id'))->update(['increment_no' => DB::raw('increment_no + 1')]);
				
				$gtotal = 0;
				$acamount = $request->get('acamount');
				$actax = $request->get('actax');
				$arracname = $request->get('acname');
				$arrchk = $request->get('check');
				foreach($request->get('acid') as $key => $val) {
					
					if(isset($arrchk[$val])) {
						$acname[] = $arracname[$key];
						$acid[] = $val;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $desarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$refarr[] = $request->get('contract_no');
						$actypearr[] = 'Cr';
						$lnarr[] = $acamount[$key];
						$gtotal += $acamount[$key];
					}
					
					DB::table('contract_prepaid')
							->insert([
								'contract_id' => $conid,
								'account_id' => $val,
								'amount' => $acamount[$key],
								'tax_amount' => $actax[$key],
								'is_add' => (isset($arrchk[$val]))?1:0
							]);
					
				}
				
				//TAX EXTRY....
				$vatrow = DB::table('vat_master')->where('status', 1)->where('deleted_at','0000-00-00 00:00:00')->select('payment_account')->first();
				foreach($request->get('acid') as $key => $val) {
					if($actax[$key] > 0 && isset($arrchk[$val])) {
						$acname[] = $arracname[$key];
						$acid[] = $vatrow->payment_account;
						$grparr[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = '';
						$desarr[] = $arracname[$key].'/TAX';
						$refarr[] = $request->get('contract_no');
						$actypearr[] = 'Cr';
						$lnarr[] = $actax[$key];
						$gtotal += $actax[$key];
					}
				}
				
				$acname[] = $request->get('customer_account');
				$acid[] = $request->get('customer_id');
				$grparr[] = 'CUSTOMER';
				$siarr[] = $btarr[] = $invarr[] = $actarr[] = $jbarr[] = '';
				$desarr[] = $request->get('contract_no').'/'.$request->get('si_no');
				$refarr[] = $request->get('contract_no');
				$actypearr[] = 'Dr';
				$lnarr[] = $gtotal;
				
				
				//INSERT SALES NONSTOCK....
				$request->merge(['from_jv' => 1]);
				$request->merge(['voucher' => 18]);
				$request->merge(['voucher_type' => 6]);
				$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('start_date'))) ]);
				$request->merge(['voucher_no' => $request->get('si_no')]);
				$request->merge(['account_name' => $acname]);
				$request->merge(['account_id' => $acid]);
				$request->merge(['group_id' => $grparr]);
				$request->merge(['sales_invoice_id' => $siarr]);
				$request->merge(['bill_type' => $btarr]);
				$request->merge(['description' => $desarr]);
				$request->merge(['reference' => $refarr]);
				$request->merge(['inv_id' => $invarr]);
				$request->merge(['actual_amount' => $actarr]);
				$request->merge(['account_type' => $actypearr]);
				$request->merge(['line_amount' => $lnarr]);
				$request->merge(['job_id' => $jbarr]);
				$request->merge(['difference' => 0]);
				$request->merge(['curno' => '']);
				$request->merge(['is_prefix' => 0]);
				$request->merge(['debit' => $request->get('grand_total')]);
				$request->merge(['credit' => $request->get('grand_total')]);
				
				$this->journal->createSIN($request->all());
			}
			
			Session::flash('message', 'Contract details added successfully.');
			Session::flash('active', 'home');
			return redirect('contractbuilding/add/'.$conid);
		} catch(ValidationException $e) { 
			return Redirect::to('contractbuilding/add')->withErrors($e->getErrors());
		}
	}
	
	
	public function settlement($id) {
		
		/* $jvs = DB::table('contract_jv')->where('contract_jv.contract_id', 63)
						->join('journal_entry','journal_entry.journal_id','=','contract_jv.jv_id')
						->orderBy('journal_entry.id','ASC')->get();
						
		echo '<pre>';print_r($this->sortByConID($jvs));exit; */				
		$jvrow = $pdcs = $printrvs = $crow = $acrow = $acarr = $jvs = $rvs = $drvs = $orvs = $orvac_arr = $orvactx_arr = null; $total = $txtotal = $acamounts = $othr_rv_amt = 0;
		$orvarr = $txarr = $stlrow = [];
		
		$settleJv = null;
		$settleRow = DB::table('contract_settlement')->where('contract_id', $id)->where('deleted_at', null)->first();
		if($settleRow) {
			$settleJv = DB::table('journal')->where('journal.id', $settleRow->jv_id)->where('journal.voucher_type','JV')
								->join('journal_entry AS JE','JE.journal_id','=','journal.id')
								->select('JE.id','journal.id AS jid','JE.amount','journal.voucher_date','journal.voucher_no','JE.description')
								->groupBy('journal.id')->get();
		}
								
		//echo '<pre>';print_r($settleJv);exit;
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.*','AM.master_name','F.flat_no AS flat')->first();
					
			$incmac = DB::table('contra_type')  
								->join('buildingmaster AS B', 'B.id', '=', 'contra_type.buildingid')
								->leftJoin('account_master AS M1', 'M1.id', '=', 'contra_type.prepaid_income')
								->leftJoin('account_master AS M8', 'M8.id', '=', 'contra_type.cancellation')
								->leftJoin('account_master AS M9', 'M9.id', '=', 'contra_type.repair')
								->leftJoin('account_master AS M10', 'M10.id', '=', 'contra_type.water_ecty_bill')
								->leftJoin('account_master AS M11', 'M11.id', '=', 'contra_type.closing_oth')
								->leftJoin('account_master AS M14', 'M14.id', '=', 'contra_type.rental_income')
								->select('M1.master_name AS acname1','M8.master_name AS acname8','M9.master_name AS acname9','M10.master_name AS acname10',
										 'M11.master_name AS acname11','M14.master_name AS acname14','contra_type.rental_income','contra_type.prepaid_income',
										 'contra_type.cancellation','contra_type.repair','contra_type.water_ecty_bill','contra_type.closing_oth')
								->where('contra_type.buildingid',$crow->building_id)->first(); 
								
			$acrow = DB::table('contract_prepaid')  
							->join('contract_building AS CB', 'CB.id', '=', 'contract_prepaid.contract_id')
							->join('contra_type AS CT', 'CT.buildingid', '=', 'CB.building_id')
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname','contract_prepaid.account_id','contract_prepaid.amount','contract_prepaid.tax_amount','CT.*')
							->where('contract_prepaid.contract_id',$id)->get();
							
			$stlrow = [];
							
			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')
								->join('journal_entry AS JE','JE.journal_id','=','journal.id')
								->select('JE.id','journal.id AS jid' /* ,'JE.account_id','JE.entry_type','JE.amount' */)->orderBy('JE.id','ASC')->get();
			
			//echo '<pre>';print_r($stlrow);exit;
			$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->where('is_add',1)
							->leftJoin('account_master AS M1', 'M1.id', '=', 'contract_prepaid.account_id')
							->select('M1.master_name AS acname1','contract_prepaid.*')
							->get(); //echo '<pre>';print_r($acamounts);exit;
										
			
			//$acamounts = DB::table('contract_prepaid')->where('contract_id',$id)->get();
			$acarr = []; 
			foreach($acamounts as $k => $row) {
				$acarr[$row->account_id] = ['amount' => $row->amount, 'tax' => $row->tax_amount ];
				$total += $row->amount;
				$txtotal += $row->tax_amount;
				
				if($k > 1) {
					$othr_rv_amt += $row->amount;
				}
			}
			//echo '<pre>';print_r($acarr);exit;
			$orvactx_arr = [0 => 'Prepaid Income Tax', 1 => 'Deposit Tax', 2 => 'Security Deposit Tax', 3 => 'Commission Tax', 4 => 'Other Deposit Tax', 5 => 'Parking Income Tax', 6 => 'Ejarie Fee Tax'];
			$orvac_arr = [0 => '', 1 => '', 2 => 'Security Deposit', 3 => 'Commission', 4 => 'Other Deposit', 5 => 'Parking Income', 6 => 'Ejarie Fee'];
			
			$jvs = DB::table('contract_jv')->where('contract_jv.contract_id',$id)
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount')
								->get();
								
			$rvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','RV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','M.master_name','RE.*','contract_rvs.installment')
								->orderBy('RE.id','ASC')->get();  //echo '<pre>';print_r($rvs);exit;
								
			$drvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','DRV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','RE.*','M.master_name')
								->orderBy('RE.id','ASC')->get(); //echo '<pre>';print_r($drvs);exit;
								
			$orvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->select('R.voucher_no','R.voucher_date','R.debit','RE.*','M.master_name','contract_rvs.rv_id')
								->orderBy('RE.id','ASC')->get();
			
			$printrvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$id)->where('contract_rvs.type','ORV') //NOV5
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->select('R.voucher_no','contract_rvs.id')
								->get();
			
			$pdcs = DB::table('pdc_received')->where('reference',$crow->contract_no)->where('status',0)->where('deleted_at','0000-00-00 00:00:00')->select(DB::raw('SUM(amount) AS pdc_amount'))->first();
			
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			//echo '<pre>';print_r($jvs);exit; 
			
			//GETTING PAID A/c IDS...
			foreach($orvs as $orv) {
				if($orv->entry_type=='Cr') {
					$orvarr[] = $orv->department_id; 
					if($orv->is_fc==1)
						$txarr[] = $orv->department_id;
				}
			}
			
			if(!Session::has('message'))
				Session::flash('active', 'settle');
			
		$buildingmaster = DB::table('buildingmaster')->where('deleted_at',null)->select('id','buildingcode','buildingname')->get();
		$sirow = DB::table('account_setting')->where('voucher_type_id',6)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
		$rvrow = DB::table('account_setting AS S')
						->join('account_master AS M1','M1.id','=','S.cash_account_id')
						->join('account_master AS M2','M2.id','=','S.pdc_account_id')
						->join('account_master AS M3','M3.id','=','S.bank_account_id')
						->where('S.voucher_type_id',9)->where('S.status',1)->where('S.deleted_at','0000-00-00 00:00:00')->where('S.department_id',0)
						->select('S.voucher_no','M1.master_name AS cash','M1.id AS cashid','M2.master_name AS pdc','M2.id AS pdcid',
						'M3.master_name AS bank','M3.id AS bankid')->first();
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		$duration = DB::table('duration')->where('deleted_at','0000-00-00 00:00:00')->get();

		//echo '<pre>';print_r($rvrow);exit;			
		return view('body.contractbuilding.settle')
					->withBuildingmaster($buildingmaster)
					->withSirow($sirow)
					->withConid($id)
					->withCrow($crow)
					->withBanks($banks)
					->withRvrow($rvrow)
					->withAcrow($acrow)
					->withAcamt($acarr)
					->withJvs($jvs)
					->withRvs($rvs)
					->withDrvs($drvs)
					->withOrvs($orvs)
					->withTotal($total)
					->withPayacnts($acamounts)
					->withOramt($othr_rv_amt+$txtotal)
					->withOracarr($orvac_arr)
					->withOractxarr($orvactx_arr)
					->withJerow($jerow)
					->withOrv($orvarr)
					->withTxrv($txarr)
					->withPrvs($printrvs)
					->withPdc($pdcs)
					->withJv($jvrow)
					->withDuration($duration)
					->withSettings($this->acsettings)
					->withSettle($settleRow)
					->withJvsettle($settleJv)
					->withIncmac($incmac)
					->withStlrow($stlrow)
					->withTxtotal($txtotal);
					
	}
	
	
	public function saveSettlement(Request $request) { //echo '<pre>';print_r($request->all());exit; 
		
		DB::beginTransaction();
		try {
			$arrSetAmt = $request->get('settleAmount');
			$arrAcnts = $request->get('acid');
			$arrAcname = $request->get('acname');
			
			if($request->get('settle_id')=='') {
				$sid = DB::table('contract_settlement')
						->insertGetId([
							'contract_id'	=> $request->get('con_id'),
							'settle_date'	=> date('Y-m-d',strtotime($request->get('settlement_date'))),
							'vacate_date'	=>	date('Y-m-d',strtotime($request->get('vacating_date')))
						]);
			} 
			
			DB::table('contract_building')->where('id',$request->get('con_id'))->update(['status' => 0, 'is_close' => 1]);
			
			DB::commit();
			Session::flash('message', 'Settlement has been completed successfully.');
			
			if($request->get('settle_id')=='')
				return redirect('contractbuilding/enquiry');
			else
				return redirect('contractbuilding/closed');
			
		} catch(\Exception $e) {
			
			DB::rollback(); echo $e->getLine().' '.$e->getMessage();exit;
			Session::flash('error', 'Settlement faied.');
			return redirect('contractbuilding/enquiry');
		}
	}
	
	
	private function setAccountTransactionPDCReSubmit($id, $trnarr, $type)
	{
		$account_master_id = ($type=='Cr')?$trnarr['crid']:$trnarr['drid'];
		$description = ($type=='Dr')?$trnarr['cname']:'';
		
		DB::table('account_transaction')->where('voucher_type', 'DB')->where('voucher_type_id', $id)
										->where('account_master_id', $account_master_id)
										->update([  'transaction_type'  => $type,
													'amount'   			=> $trnarr['amt'],
													'status'			=> 1,
													'modify_at' 		=> date('Y-m-d H:i:s'),
													'modify_by' 		=> 1,
													'deleted_at'		=> '0000-00-00 00:00:00',
													'description'		=> $description,
													'reference'			=> $trnarr['id'],
													'invoice_date'		=> date('Y-m-d', strtotime($trnarr['vdate'])),
													'reference_from'	=> $trnarr['ref'] 
												]);
						
		if($type=='Dr') {
			$this->objUtility->tallyClosingBalance( $trnarr['drid'] );
			
		} else {
			$this->objUtility->tallyClosingBalance( $trnarr['crid'] );
		}
		
		return true;
	}
	
	
	private function setAccountTransactionPDC($id, $trans, $type)
	{
		$account_master_id = ($type=='Cr')?$trans['crid']:$trans['drid'];
		$description = ($type=='Dr')?$trans['cname']:'';
		
		DB::table('account_transaction')
				->insert([  'voucher_type' 		=> 'DB',
						    'voucher_type_id'   => $id,
							'account_master_id' => $account_master_id,
							'transaction_type'  => $type,
							'amount'   			=> $trans['amt'],
							'status' 			=> 1,
							'created_at' 		=> date('Y-m-d H:i:s'),
							'created_by' 		=> Auth::User()->id,
							'description'		=> $description,
							'reference'			=> $trans['id'],
							'invoice_date'		=> date('Y-m-d', strtotime($trans['vdate'])),
							'reference_from'	=> $trans['ref'] 
						]);
						
		if($type=='Dr') {
			$this->objUtility->tallyClosingBalance( $trans['drid'] );
			
		} else {
			$this->objUtility->tallyClosingBalance( $trans['crid'] );
			
		}
		
		return true;
	}
	
	
	public function getEnddate($date,$dur) {
		
		$date = date('Y-m-d', strtotime($date. ' + '.$dur.' months')); 
		$dt = date('Y-m-d', strtotime($date. ' - 1 days'));
		echo json_encode(date('d-m-Y',strtotime($dt)));
		
	}
	
	public function savePayment(Request $request) {
		
		if($request->get('account_id')!=null) {
		//echo '<pre>';print_r($request->all());exit;
		//$attributes = $request->all(); //echo '<pre>';print_r($attributes);exit;
			$pvrow = DB::table('contract_pvs')->where('contract_id', $request->get('con_id'))->first();
			//$acname[] = $request->get('tenant');
			//$acid[] = $request->get('tenant_id');
			
			$arr_acname = $request->get('PVaccount_name');
			$arr_desc = $request->get('description');
			$arr_ref = $request->get('reference');
			$arr_amt = $request->get('line_amount');
			$arr_bnk = $request->get('bank_id');
			$arr_chq = $request->get('cheque_no');
			$arr_chqdt = $request->get('cheque_date');
			$jearr = $request->get('je_id');
			$arr_exp = $request->get('expacid');
			$arr_fc = $request->get('isfc');
			$arr_actype = $request->get('account_type');
			
			//$desarr[] = $arr_desc[0];
			$arrgrp = $request->get('group_id');
			foreach($request->get('account_id') as $key => $val) {
				$acname[] = $arr_acname[$key];
				$acid[] = $partyid = $val;
				$grparr[] = $arrgrp[$key];
				$desarr[] = $arr_desc[$key];
				$refarr[] = $arr_ref[$key];
				$vatamt[] = ''; $siarr[] = ''; $btarr[] = ''; $invarr[] = ''; $actarr[] = ''; $jbarr[] = ''; $pryarr[] = ''; $prtnarr[] = ''; $trarr[] = '';
				$actypearr[] = $arr_actype[$key];
				$lnarr[] = $arr_amt[$key];
				$dptarr[] = $arr_exp[$key];
				$fcarr[] = $arr_fc[$key];
				$bnkarr[] = $arr_bnk[$key];
				$chqarr[] = $arr_chq[$key];
				$chqdtarr[] = $arr_chqdt[$key];
				
			}
			
			$request->merge(['from_jv' => 1]);
			$request->merge(['chktype' => '']);
			$request->merge(['is_onaccount' => 1]);
			$request->merge(['voucher' => 1]);
			$request->merge(['voucher_type' => 10]);
			$request->merge(['voucher_date' => date('Y-m-d', strtotime($request->get('pv_date')))]);
			$request->merge(['voucher_no' => $request->get('pv_no')]);
			$request->merge(['account_name' => $acname]);
			$request->merge(['account_id' => $acid]);
			$request->merge(['group_id' => $grparr]);
			$request->merge(['vatamt' => $vatamt]);
			$request->merge(['purchase_invoice_id' => $siarr]);
			$request->merge(['bill_type' => $btarr]);
			$request->merge(['description' => $desarr]);
			$request->merge(['reference' => $refarr]);
			$request->merge(['inv_id' => $invarr]);
			$request->merge(['actual_amount' => $actarr]);
			$request->merge(['account_type' => $actypearr]);
			$request->merge(['line_amount' => $lnarr]);
			$request->merge(['job_id' => $jbarr]);
			$request->merge(['bank_id' => $bnkarr]);
			$request->merge(['cheque_no' => $chqarr]);
			$request->merge(['cheque_date' => $chqdtarr]);
			$request->merge(['department' => $dptarr]); 
			$request->merge(['is_fc' => $fcarr]); 
			$request->merge(['partyac_id' => $pryarr]);
			$request->merge(['party_name' => $prtnarr]);
			$request->merge(['difference' => 0]);
			$request->merge(['tr_id' => $trarr]);
			$request->merge(['je_id' => $jearr]);
			$request->merge(['remove_item' => '']);
			$request->merge(['trn_no' => '']);
			$request->merge(['curno' => '']);
			$request->merge(['debit' => $request->get('debit')]);
			$request->merge(['credit' => $request->get('credit')]);
			//echo '<pre>';print_r($request->all());exit;
			
			if($request->get('type')=='edit') {
				$this->payment_voucher->update($pvrow->pv_id,$request->all());
				
				DB::table('contract_pvs')->where('contract_id',$request->get('con_id'))->update(['amount' => $request->get('debit')]);
				
				Session::flash('message', 'Payment voucher updated successfully.');
				Session::flash('active', 'payment');
				return redirect('contractbuilding/edit/'.$request->get('con_id'));
			} else {
				$pvid = $this->payment_voucher->create($request->all());
			
				DB::table('contract_pvs')->insert(['contract_id' => $request->get('con_id'), 'pv_id' => $pvid,
												 'amount' => $request->get('debit') ]);
				
				DB::table('contract_building')->where('id',$request->get('con_id'))->update(['pv_status' => 1]);
				
				Session::flash('message', 'Payment voucher added successfully.');
				Session::flash('active', 'payment');
				return redirect('contractbuilding/add/'.$request->get('con_id'));
			}
		} else {
			return redirect('contractbuilding');
		}
	}
	
	public function ajaxReallocate(Request $request) {  //NOV8
		
		$jvs = DB::table('contract_jv')->where('contract_jv.contract_id', $request->get('cid'))
								->join('journal AS J','J.id','=','contract_jv.jv_id')
								->select('J.voucher_no','J.voucher_date','J.debit AS amount')
								->get();
								
		if($request->get('isday')==0) { //MONTH CALCULATION...
			
			$adata = []; $duration = $request->get('duration');//floor($request->get('duration')/30);
			$amount = $request->get('amount')/$duration;
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			$day = date('d',strtotime($request->get('date')));
			$month = date('m',strtotime($request->get('date')));
			$year = date('Y',strtotime($request->get('date')));
			if($day==01 || $day==1) {
				$d = 0; 
				for($i=0;$i<$duration;$i++) {
					
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $dy = ($nod - $day);
				$amount1 = $dy * $day_amount;
				$jvno = (isset($jvs[0]))?$jvs[0]->voucher_no:$jvrow->voucher_no;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<$duration;$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
				$dy2 = $nod - $dy;
				$amount2 = $dy2 * $day_amount;
				$jvno = $jvrow->voucher_no + $duration;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));//date('d-m-Y',strtotime($date2)).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($date2)), 'amount' => $amount2, 'desc' => $desc];
			}
			
		} else { //DAYS CALCULATION...
			
			$adata = []; $duration = ($request->get('duration') > 30)?floor($request->get('duration')/30):1;
			$amount = $request->get('amount')/$request->get('duration');
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			$day = date('d',strtotime($request->get('date')));
			$month = date('m',strtotime($request->get('date')));
			$year = date('Y',strtotime($request->get('date')));
			if($day==01 || $day==1) {
				$d = $nodTot = 0; 
				for($i=0;$i<$duration;$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$nod = $nodTot = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
						$day = date('d',strtotime($date));
						$month = date('m',strtotime($date));
						$year = date('Y',strtotime($date));
						$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$nodTot += $nod;
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nod, 'desc' => $desc];
				}
				$remDays = $request->get('duration') - $nodTot;
				if($remDays > 0){
					$jvno = $jvrow->voucher_no + $duration;
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $nodTot = $dy = ($nod - $day);
				$amount1 = $dy * $amount; 
				$jvno = (isset($jvs[0]))?$jvs[0]->voucher_no:($jvrow->voucher_no + 1);
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<ceil($request->get('duration')/30);$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					
					$day = date('d',strtotime($date));
					$month = date('m',strtotime($date));
					$year = date('Y',strtotime($date));
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					
					$remDays = $request->get('duration') - $nodTot;
					if($nod > $remDays) {
						$nd = $remDays;
					} else
						$nd = $nod;
					
					$nodTot += $nd;					
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nd, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
								
				$remDays = $request->get('duration') - ($nodTot+1);
				if($remDays > 0){
					if($d==$duration) {
						$duration = $duration+1;
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					} else
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$jvno = $jvrow->voucher_no + $duration;
					
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			}
		}
		//echo '<pre>';print_r($adata);exit;
		return view('body.contractbuilding.allocate')
		            ->withAmount($request->get('amount'))
					->withAdata($adata);
	}
	
	
	public function ajaxReceiptReAdd(Request $request) {  //NOV22
		
		$rvs = DB::table('contract_rvs')->where('contract_rvs.contract_id',$request->get('cid'))->where('contract_rvs.type','RV')
								->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
								->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
								->join('account_master AS M','M.id','=','RE.account_id')
								->where('RE.entry_type','Dr')
								->where('RE.status',1)
								->where('RE.deleted_at','0000-00-00 00:00:00')
								->select('R.voucher_no','R.voucher_date','M.master_name','RE.*','contract_rvs.installment')
								->orderBy('RE.id','ASC')->get(); 
		//echo '<pre>';print_r($rvs);exit;						
		$data = [];
		$inst = $request->get('inst');
		$amount = $request->get('amount');
		$divamt = $amount/$inst; $n=0;
		for($i=0;$i<$inst;$i++) { $n++;
			
			$reid = (isset($rvs[$i]))?$rvs[$i]->id:'';
			if($n==1)
				$instmnt = '1st Installment';
			elseif($n==2)
				$instmnt = '2nd Installment';
			if($n==3)
				$instmnt = '3rd Installment';
			elseif($n > 3)
				$instmnt = ($i+1).'th Installment';
			$data[] = (object)['acname' => $request->get('cac'),'acid' => $request->get('cacid'),'ref' => urldecode($request->get('ref')),
						'desc' => urldecode($request->get('ref')).'/'.urldecode($request->get('dec')),'amount' => round($divamt,2),'inst' => $instmnt, 'reid' => $reid];
		}
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();//NOV8
		return view('body.contractbuilding.receiptadd')
					->withType($request->get('type'))
					->withCac($request->get('cac'))
					->withCacid($request->get('cacid'))
					->withBanks($banks)
					->withData($data);
		
	}
	
	//NOV24
	public function getOrvDetails($rid,$id) {
		
		$orvs = DB::table('contract_rvs')->where('contract_rvs.rv_id',$rid)->where('contract_rvs.type','ORV')
							->join('receipt_voucher AS R','R.id','=','contract_rvs.rv_id')
							->join('receipt_voucher_entry AS RE','RE.receipt_voucher_id','=','R.id')
							->join('account_master AS M','M.id','=','RE.account_id')
							->select('R.voucher_no','R.voucher_date','R.debit','RE.*','M.master_name','contract_rvs.rv_id')
							->orderBy('RE.id','ASC')->get(); //echo '<pre>';print_r($orvs);exit;
		
		$banks = DB::table('bank')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','code','name')->get();
		
		return view('body.contractbuilding.orvdetails')
						->withBanks($banks)
						->withOrvs($orvs);
	}
	
	
	public function uploadSubmit(Request $request)
	{	
		$res = $this->ajax_upload($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	
	public function ajax_upload($file)
	{ 
		$photo = '';
		$fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		if($file) {
			$ext = $file->getClientOriginalExtension();
			if($ext=='.jpg' ||$ext=='.JPG' || $ext=='.png' || $ext=='.PNG') {
				$photo = rand(1, 999).$fname.'.'.$ext;
				$destinationPath = public_path() . $this->imgDir.'/'.$photo;
				$destinationPathThumb = public_path() . $this->imgDir.'/thumb_'.$photo;

				// resizing an uploaded file
				Image::make($file->getRealPath())->resize($this->width, $this->height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Image::make($file->getRealPath())->resize($this->thumbWidth, $this->thumbHeight, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = rand(1, 999).$fname.'.'.$ext;
				 $destinationPath = public_path() . $this->imgDir;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	
	public function ajaxRentCalculate(Request $request) {  //NOV8
		
		if($request->get('duration')==12)
			$days = 365;
		elseif($request->get('duration')==6)
			$days = 180;
		else
			$days = $request->get('duration') * 30;
		
		//CALCULATE MONTH DIFFERENCE...
		$d1 = new DateTime(date('Y-m-d',strtotime($request->get('sdate'))));
		$d2 = new DateTime(date('Y-m-d',strtotime($request->get('vdate'))));
		$interval = $d1->diff($d2); //echo '<pre>';print_r($interval);exit;
		$dur = $interval->m+1;
		
		//$rentPerday = $request->get('amount') / $days;
		$rentPermth = $request->get('amount') / $request->get('duration');
		$amount = $usedays = 0;
		
				
		$i = 0; 
		do {
			
			
			if($i==0) {
				$day = date('d',strtotime($request->get('sdate')));
				$month = date('m',strtotime($request->get('sdate')));
				$year = date('Y',strtotime($request->get('sdate')));
				//FROM THE BEGINING OF MONTH...
				if($day==01 || $day==1) {
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$amount += $rentPermth;
					$usedays += $nod;
				} else { //PARTIAL MONTH....
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$eod = date("t-m-Y", strtotime($request->get('sdate')));
					$amount += ($eod - $day) * $rentPerday;
					$usedays += ($eod - $day);
				}
			} else {
				//$date = date('d-m-Y',strtotime('+'.$i.' months',strtotime($request->get('sdate'))));
				$cmth = date('m',strtotime($date));
				$cyr = date('Y',strtotime($date));
				$vmth = date('m',strtotime($request->get('vdate')));
				$eday = date('d',strtotime($request->get('vdate')));
				$eod = date("t-m-Y", strtotime($request->get('vdate')));
				
				if($cmth != $vmth) {
					$month = date('m',strtotime($date));
					$year = date('Y',strtotime($date));
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$amount += $rentPermth;
					$usedays += $nod;
					
				} else {
					//END OF DATE AND VACATING DATE ARE SAME...
					if($eday==$eod) {
						//$date = date('d-m-Y',strtotime('+'.$i.' months',strtotime($request->get('sdate'))));
						$month = date('m',strtotime($date));
						$year = date('Y',strtotime($date));
						$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$rentPerday = $rentPermth / $nod;
						$amount += $rentPermth;
						$usedays += $nod;
					} else {
						$vmonth = date('m',strtotime($request->get('vdate')));
						$vyear = date('Y',strtotime($request->get('vdate')));
						$nod = cal_days_in_month(CAL_GREGORIAN, $vmonth, $vyear);
						$rentPerday = $rentPermth / $nod;
						$amount += $eday * $rentPerday;
						$usedays += $eday;
					}
				}
			}
			$i++;
			$date = date('d-m-Y',strtotime('+'.$i.' months',strtotime($request->get('sdate'))));
			$crmth = date('m',strtotime($date));
			$vcmth = date('m',strtotime($request->get('vdate')));
		} while ($crmth<=$vcmth); //
		
		
		//echo $request->get('amount').' / '.$request->get('duration');exit;
		echo json_encode(['amount' => $amount,'nod' => $usedays, 'c'=>$i]);
		
		//echo '<pre>';print_r($adata);exit;
		/* return view('body.contractbuilding.allocate')
					->withAdata($adata); */
	}
	
	public function ajaxRentCalculate2(Request $request) {  //NOV8
		
		if($request->get('duration')==12)
			$days = 365;
		elseif($request->get('duration')==6)
			$days = 180;
		else
			$days = $request->get('duration') * 30;
		
		//CALCULATE MONTH DIFFERENCE...
		$d1 = new DateTime(date('Y-m-d',strtotime($request->get('sdate'))));
		$d2 = new DateTime(date('Y-m-d',strtotime($request->get('vdate'))));
		$interval = $d1->diff($d2); 
		$dur = $interval->m+1;
		
		//$rentPerday = $request->get('amount') / $days;
		$rentPermth = $request->get('amount') / $request->get('duration');
		$amount = $usedays = 0;
		for($i=0;$i<$dur;$i++) {
			if($i==0) {
				$day = date('d',strtotime($request->get('sdate')));
				$month = date('m',strtotime($request->get('sdate')));
				$year = date('Y',strtotime($request->get('sdate')));
				//FROM THE BEGINING OF MONTH...
				if($day==01 || $day==1) {
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$amount += $rentPermth;
					$usedays += $nod;
				} else { //PARTIAL MONTH....
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$eod = date("t-m-Y", strtotime($request->get('sdate')));
					$amount += ($eod - $day) * $rentPerday;
					$usedays += ($eod - $day);
				}
			} else {
				$eday = date('d',strtotime($request->get('vdate')));
				$eod = date("t-m-Y", strtotime($request->get('vdate')));
				//END OF DATE AND VACATING DATE ARE SAME...
				if($eday==$eod) {
					$date = date('d-m-Y',strtotime('+'.$i.' months',strtotime($request->get('sdate'))));
					$month = date('m',strtotime($date));
					$year = date('Y',strtotime($date));
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$rentPerday = $rentPermth / $nod;
					$amount += $rentPermth;
					$usedays += $nod;
				} else {
					$vmonth = date('m',strtotime($request->get('vdate')));
					$vyear = date('Y',strtotime($request->get('vdate')));
					$nod = cal_days_in_month(CAL_GREGORIAN, $vmonth, $vyear);
					$rentPerday = $rentPermth / $nod;
					$amount += $eday * $rentPerday;
					$usedays += $eday;
				}
			}
			
		}
		//echo $request->get('amount').' / '.$request->get('duration');exit;
		echo json_encode(['amount' => $amount,'nod' => $usedays]);exit;
		
		if($request->get('isday')==0) { //MONTH CALCULATION...
			
			if($day==01 || $day==1) {
				$d = 0; 
				for($i=0;$i<$duration;$i++) {
					
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $dy = ($nod - $day);
				$amount1 = $dy * $day_amount;
				$jvno = (isset($jvs[0]))?$jvs[0]->voucher_no:$jvrow->voucher_no;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<$duration;$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
				$dy2 = $nod - $dy;
				$amount2 = $dy2 * $day_amount;
				$jvno = $jvrow->voucher_no + $duration;
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));//date('d-m-Y',strtotime($date2)).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($date2)), 'amount' => $amount2, 'desc' => $desc];
			}
			
		} else { //DAYS CALCULATION...
			
			$adata = []; $duration = floor($request->get('duration')/30);
			$amount = $request->get('amount')/$request->get('duration');
			$jvrow = DB::table('account_setting')->where('voucher_type_id',16)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('department_id',0)->select('voucher_no')->first();
			$day = date('d',strtotime($request->get('date')));
			$month = date('m',strtotime($request->get('date')));
			$year = date('Y',strtotime($request->get('date')));
			if($day==01 || $day==1) {
				$d = $nodTot = 0; 
				for($i=0;$i<$duration;$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					if($i==0) {
						$nod = $nodTot = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$date = date('d-m-Y',strtotime($request->get('date')));
					} else {
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
						$day = date('d',strtotime($date));
						$month = date('m',strtotime($date));
						$year = date('Y',strtotime($date));
						$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
						$nodTot += $nod;
					}
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nod, 'desc' => $desc];
				}
				$remDays = $request->get('duration') - $nodTot;
				if($remDays > 0){
					$jvno = $jvrow->voucher_no + $duration;
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			} else {
				
				$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$day_amount = ($amount/$nod); $nodTot = $dy = ($nod - $day);
				$amount1 = ($dy+1) * $amount;
				$jvno = (isset($jvs[0]))?$jvs[0]->voucher_no:($jvrow->voucher_no + 1);
				$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
				$adata[] = (object)['jvno' => $jvno, 'date' => date('d-m-Y',strtotime($request->get('date'))), 'amount' => $amount1, 'desc' => $desc];
				$date1 = date('d-m-Y',strtotime('+'.($dy+1).' days',strtotime($request->get('date'))));
				$d = 0; 
				for($i=1;$i<ceil($request->get('duration')/30);$i++) {
					$jvno = (isset($jvs[$i]))?$jvs[$i]->voucher_no:($jvrow->voucher_no + $i);
					$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					$desc = $request->get('cname').'/'.date('d-m-Y',strtotime($request->get('date'))).' to '.date('d-m-Y',strtotime($request->get('edate')));
					
					$day = date('d',strtotime($date));
					$month = date('m',strtotime($date));
					$year = date('Y',strtotime($date));
					$nod = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					
					$remDays = $request->get('duration') - $nodTot;
					if($nod > $remDays) {
						$nd = $remDays;
					} else
						$nd = $nod;
					
					$nodTot += $nd;					
					$d++;
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$nd, 'desc' => $desc];
					$date2 = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
				}
								
				$remDays = $request->get('duration') - ($nodTot+1);
				if($remDays > 0){
					if($d==$duration) {
						$duration = $duration+1;
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($date1)));
					} else
						$date = date('d-m-Y',strtotime('+'.$d.' months',strtotime($request->get('date'))));
					$jvno = $jvrow->voucher_no + $duration;
					
					$adata[] = (object)['jvno' => $jvno, 'date' => $date, 'amount' => $amount*$remDays, 'desc' => $desc];
				}
			}
		}
		//echo '<pre>';print_r($adata);exit;
		return view('body.contractbuilding.allocate')
		            ->withAmount($request->get('amount'))
					->withAdata($adata);
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
		
		$crow = DB::table('contract_building')->where('id', $id)->select('si_no')->first();
		
		$crow = DB::table('contract_building')
                    ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.si_no','contract_building.contract_no','B.buildingcode AS buildcode','F.flat_no AS flat')->first();
					
		$jvs = DB::table('contract_jv')->where('contract_id',$id)->select('jv_id')->get();
		$rvs = DB::table('contract_rvs')->where('contract_id',$id)->select('rv_id')->get();
		$pvs = DB::table('contract_pvs')->where('contract_id',$id)->select('pv_id')->get();
		
		$attributes['jvids'] = array_map(function ($jvs) { return $jvs->jv_id;}, $jvs);
		$attributes['rvids'] = array_map(function ($rvs) { return $jvs->jv_id;}, $pvs);
		$attributes['pvids'] = array_map(function ($rvs) { return $jvs->jv_id;}, $pvs);
		//echo '<pre>';print_r($jvids);exit;
		if($crow) {
			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')->first();
			$attributes['type'] = 'SIN';
			$attributes['jid'] = $jerow->id;
			$results = $this->makeTreeAll( $this->voucherwise_report->getReportsByIdRE($attributes) );  
			
			//echo '<pre>';print_r($results);exit;				
		}
			
		return view('body.contractbuilding.printall')
						->withDetails($crow)
						->withTransactions($results);
	}
	
	
	public function attachment($id) {
		
		$crow = DB::table('contract_building')->join('account_master AS AM','AM.id','=','contract_building.customer_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)
					->select('contract_building.id','contract_building.contract_no','contract_building.contract_date','AM.master_name','F.flat_no AS flat')
					->first();
		
		$files = DB::table('contract_attachment')->where('contract_id',$id)->get();

		
		return view('body.contractbuilding.attachment')
						->withFiles($files)
						->withCrow($crow);
	}
	
		
	public function getFileform() {
		
		return view('body.contractbuilding.fileform')
					->withNo($request->get('no'));
				
	}
	
	
    public function attachmentSave(Request $request) {
		
		$attributes = $request->all();
		foreach($attributes['photo_name'] as $key => $val) {
			if($val!='') {
				DB::table('contract_attachment')
						->insert(['contract_id' => $attributes['contract_id'], 
								  'file_name' => $val,
								  'name'	=> ($attributes['name'][$key]=='')?'New Document':$attributes['name'][$key]
								]);
			}
			
		}
		
		if(isset($attributes['curname'])) {
		
    		foreach($attributes['curname'] as $k => $v) {
    			
    			if($attributes['curname'][$k]!='' && isset($attributes['fid'][$k]))
    				DB::table('contract_attachment')->where('id', $attributes['fid'][$k])->update(['name' => $attributes['curname'][$k]]);
    		}
		}
		
		$ids = explode(',',$attributes['remove_ids']);
		if($ids)
			DB::table('contract_attachment')->whereIn('id', $ids)->delete();
		
		
		Session::flash('message', 'Attachment added successfully.');
			
		return redirect('contractbuilding/attach/'.$attributes['contract_id']);
	}
	
	public function uploadContract(Request $request)
	{	
		$res = $this->ajax_upload_contract($request->photos);
		return response()->json(array('file_name' => $res), 200);
	}
	
	public function ajax_upload_contract($file)
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
	
	private function number_to_word( $num = '' )
	{
		$num    = ( string ) ( ( int ) $num );
	   
		if( ( int ) ( $num ) && ctype_digit( $num ) )
		{
			$words  = array( );
		   
			$num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
		   
			$list1  = array('','one','two','three','four','five','six','seven',
				'eight','nine','ten','eleven','twelve','thirteen','fourteen',
				'fifteen','sixteen','seventeen','eighteen','nineteen');
		   
			$list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
				'seventy','eighty','ninety','hundred');
		   
			$list3  = array('','thousand','million','billion','trillion',
				'quadrillion','quintillion','sextillion','septillion',
				'octillion','nonillion','decillion','undecillion',
				'duodecillion','tredecillion','quattuordecillion',
				'quindecillion','sexdecillion','septendecillion',
				'octodecillion','novemdecillion','vigintillion');
		   
			$num_length = strlen( $num );
			$levels = ( int ) ( ( $num_length + 2 ) / 3 );
			$max_length = $levels * 3;
			$num    = substr( '00'.$num , -$max_length );
			$num_levels = str_split( $num , 3 );
		   
			foreach( $num_levels as $num_part )
			{
				$levels--;
				$hundreds   = ( int ) ( $num_part / 100 );
				$hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
				$tens       = ( int ) ( $num_part % 100 );
				$singles    = '';
			   
				if( $tens < 20 )
				{
					$tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
				}
				else
				{
					$tens   = ( int ) ( $tens / 10 );
					$tens   = ' ' . $list2[$tens] . ' ';
					$singles    = ( int ) ( $num_part % 10 );
					$singles    = ' ' . $list1[$singles] . ' ';
				}
				$words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
			}
		   
			$commas = count( $words );
		   
			if( $commas > 1 )
			{
				$commas = $commas - 1;
			}
		   
			$words  = implode( ', ' , $words );
		   
			//Some Finishing Touch
			//Replacing multiples of spaces with one space
			$words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
			if( $commas )
			{
				$words  = $this->str_replace_last( ',' , ' and' , $words );
			}
		   
			return $words;
		}
		else if( ! ( ( int ) $num ) )
		{
			return 'Zero';
		}
		return '';
	}
	
	private function trim_all( $str , $what = NULL , $with = ' ' )
	{
		if( $what === NULL )
		{
			//  Character      Decimal      Use
			//  "\0"            0           Null Character
			//  "\t"            9           Tab
			//  "\n"           10           New line
			//  "\x0B"         11           Vertical Tab
			//  "\r"           13           New Line in Mac
			//  " "            32           Space
		   
			$what   = "\\x00-\\x20";    //all white-spaces and control chars
		}
	   
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
	
	private function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
	}


	public function print_voucher($id) {
		
		$crow = DB::table('contract_building')->where('id', $id)->select('si_no')->first();
		
		$crow = DB::table('contract_building')
                    ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
					->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
					->where('contract_building.id',$id)->select('contract_building.si_no','contract_building.contract_no','B.buildingcode AS buildcode','F.flat_no AS flat')->first();
					
		$jvs = DB::table('contract_jv')->where('contract_id',$id)->select('jv_id')->get();
		
		$attributes['jvids'] = array_map(function ($jvs) { return $jvs->jv_id;}, $jvs);
		//echo '<pre>';print_r($jvids);exit;
		if($crow) {
			$jerow = DB::table('journal')->where('journal.voucher_no', $crow->si_no)->where('journal.voucher_type','SIN')->first();
			$attributes['type'] = 'SIN';
			$attributes['jid'] = $jerow->id;
			$results = $this->makeTreeAll( $this->voucherwise_report->getReportsByIdRE($attributes) );  
			
			//echo '<pre>';print_r($results);exit;				
		}
			
		return view('body.contractbuilding.printall')
						->withDetails($crow)
						->withTransactions($results);
	}
	
}
