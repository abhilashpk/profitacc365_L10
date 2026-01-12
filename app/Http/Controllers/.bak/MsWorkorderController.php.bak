<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsWorkorderController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$orders = [];//DB::table('ms_workorder')->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		return view('body.msworkorder.index')
					->withOrders($orders);
	}
	
	private function getOrderCount()
	{
		return DB::table('ms_workorder')->where('ms_workorder.deleted_at','0000-00-00 00:00:00')
								->join('ms_customer', 'ms_customer.id', '=', 'ms_workorder.customer_id')
								//->leftJoin('ms_technician', 'ms_technician.id', '=', 'ms_workorder.technician_id')
								->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workorder.type_id')
								->count();
	}
	
	private function getOrderList($type,$start,$limit,$order,$dir,$search,$status)
	{
		$qry = DB::table('ms_workorder')
								->join('ms_customer', 'ms_customer.id', '=', 'ms_workorder.customer_id')
								//->leftjoin('ms_technician', 'ms_technician.id', '=', 'ms_workorder.technician_id')
								->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workorder.type_id');
								
		if($search) {
			$qry->where(function($qry) use($search){
				$qry->where('ms_workorder.wo_no','LIKE',"%{$search}%")
				  ->orWhere('ms_customer.name', 'LIKE',"%{$search}%")
				  ->orWhere('ms_workorder.technician_id', 'LIKE',"%{$search}%");
			});
			
		}
				
		$qry->where('ms_workorder.deleted_at','0000-00-00 00:00:00');
		$qry->select('ms_workorder.id','ms_workorder.wo_no','ms_workorder.creation_datetime',
					'ms_workorder.status','ms_customer.name AS customer','ms_workorder.technician_id AS technician',
					'ms_worktype.name AS wo_type')
			->offset($start)
			->limit($limit)
			->orderBy($order,$dir);
			
		if($type=='get')
			return $qry->get();
		else
			return $qry->count();
	}
	
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'wo_no',
                            1 => 'creation_datetime',
                            2 => 'customer',
                            3 => 'wo_type',
							4 => 'technician',
                            5 => 'status'
                        );
						
		$totalData = $this->getOrderCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'ms_workorder.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$status = $request->input('status');
        
		$invoices = $this->getOrderList('get', $start, $limit, $order, $dir, $search, $status);
		
		if($search)
			$totalFiltered =  $this->getOrderList('count', $start, $limit, $order, $dir, $search, $status);
		
        $data = array();
        if(!empty($invoices))
        {
			
			foreach ($invoices as $row)
            {
				$edit =  '"'.url('ms_workorder/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('ms_workorder/print/'.$row->id);
				
				$nestedData['id'] = $i = $row->id;
				
				$opt =  $row->id;
				$nestedData['wo_no'] = $row->wo_no;
				$nestedData['customer'] = $row->customer;
				$nestedData['creation_datetime'] = date('d-m-Y H:i', strtotime($row->creation_datetime));
				$nestedData['technician'] = $row->technician;
				$nestedData['wo_type'] = $row->wo_type;
				$nestedData['status'] = $this->getStatus($row->status);
				
                $nestedData['edit'] = "<p><button type='button' class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button type='button' class='btn btn-danger btn-xs delete' onClick='{$delete}'>
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
	
	public function add($id = null) {

		$location = DB::table('ms_location')->where('deleted_at','0000-00-00 00:00:00')->get();
		$wotype = DB::table('ms_worktype')->where('deleted_at','0000-00-00 00:00:00')->get();
		$technician = DB::table('ms_technician')->where('deleted_at','0000-00-00 00:00:00')->get();
		$werow = '';
		if($id) {
			
			$werow = DB::table('ms_workenquiry')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id')
						->leftJoin('ms_location', 'ms_location.id', '=', 'ms_workenquiry.location_id')
						->where('ms_workenquiry.id', $id)
						->select('ms_workenquiry.*','ms_customer.name AS customer','ms_worktype.name AS wotype',
								 'ms_workenquiry.location')
						->first(); //echo '<pre>';print_r($werow);exit;
		}
		
		$dat = DB::table('ms_workorder')->select('id')->orderBy('id','DESC')->first();
		$wono = ($dat)?100+$dat->id+1:100+1;
				
		return view('body.msworkorder.add')
				->withLocation($location)
				->withTechnician($technician)
				->withNowdate( date('d-m-Y H:i') )
				->withWerow($werow)
				->withWono('WO'.$wono)
				->withWotype($wotype);
	}
	
	public function save() { 
		try {
			$id = DB::table('ms_workorder')
					->insertGetId([
						'creation_datetime' => date('Y-m-d H:i', strtotime(Input::get('creation_datetime'))),
						'job_id' => Input::get('job_id'),
						'location' => Input::get('location'), //'location_id' => Input::get('location_id'),
						'customer_id' => Input::get('customer_id'),
						'description' => Input::get('description'),
						'type_id' => Input::get('wo_type'),
						'technician_id' => implode(',', Input::get('technician_id')),
						'total_time' => Input::get('total_time'),
						'status' => Input::get('status'),
						'remarks' => Input::get('remarks'),
						'closed_datetime' => (Input::get('closed_datetime')!='')?date('Y-m-d H:i', strtotime(Input::get('closed_datetime'))):'0000-00-00 00:00:00',
						'created_at' => date('Y-m-d h:i:s'),
						'enquiry_id'	=> Input::get('enquiry_id'),
						'reference_no'	=> Input::get('reference_no')
					]);
				
			if($id) {
				$wono = 100+$id;
				DB::table('ms_workorder')->where('id',$id)->update(['wo_no' => 'WO'.$wono]);
				$timein = Input::get('time_in');
				$timeout = Input::get('time_out');
				
				foreach($timein as $key => $val) {
					
					DB::table('ms_wo_time')
							->insert(['workorder_id' => $id,
									  'time_in'	=> $val,
									  'time_out'	=> $timeout[$key]
							]);
				}
				
				if(Input::get('enquiry_id')!='') {
					DB::table('ms_workenquiry')->where('id', Input::get('enquiry_id'))->update(['status' => 1, 'is_transfer' => 1]);
				}
			}
		
			Session::flash('message', 'Work order added successfully.');
			return redirect('ms_workorder');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_workorder/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$worow = DB::table('ms_workorder')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workorder.customer_id')
						//->join('ms_technician', 'ms_technician.id', '=', 'ms_workorder.technician_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workorder.type_id')
						->leftJoin('ms_jobmaster', 'ms_jobmaster.id', '=', 'ms_workorder.job_id')
						->leftJoin('ms_location', 'ms_location.id', '=', 'ms_workorder.location_id')
						->leftJoin('ms_workenquiry', 'ms_workenquiry.id', '=', 'ms_workorder.enquiry_id')
						->where('ms_workorder.id', $id)
						->select('ms_workorder.*','ms_customer.name AS customer','ms_workorder.technician_id AS technician','ms_worktype.name AS wotype',
							'ms_jobmaster.name AS jobname','ms_workenquiry.enq_no','ms_workorder.location')
						->first();
		//echo '<pre>';print_r($worow);exit;	
		$times = DB::table('ms_wo_time')->where('workorder_id', $id)->where('deleted_at','0000-00-00 00:00:00')->get();
		//$location = DB::table('ms_location')->where('deleted_at','0000-00-00 00:00:00')->get();
		$wotype = DB::table('ms_worktype')->where('deleted_at','0000-00-00 00:00:00')->get();
		$technician = DB::table('ms_technician')->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.msworkorder.edit')
					->withWorow($worow)
					->withTimes($times)
					//->withLocation($location)
					->withTechnician($technician)
					->withWotype($wotype);
					
	}
	
	public function update($id)
	{	//echo '<pre>';print_r(Input::all());exit;
			DB::table('ms_workorder')
					->where('id', $id)
					->update([
						'creation_datetime' => date('Y-m-d H:i', strtotime(Input::get('creation_datetime'))),
						'job_id' => Input::get('job_id'),
						'location' => Input::get('location'), //'location_id' => Input::get('location_id'),
						'customer_id' => Input::get('customer_id'),
						'description' => Input::get('description'),
						'type_id' => Input::get('wo_type'),
						'technician_id' => implode(',', Input::get('technician_id')),
						'total_time' => Input::get('total_time'),
						'status' => Input::get('status'),
						'remarks' => Input::get('remarks'),
						'closed_datetime' => (Input::get('closed_datetime')!='')?date('Y-m-d H:i', strtotime(Input::get('closed_datetime'))):'0000-00-00 00:00:00',
						'modified_at' => date('Y-m-d h:i:s'),
						'reference_no'	=> Input::get('reference_no')
					]);
					
			$timein = Input::get('time_in');
			$timeout = Input::get('time_out');
			$timeid = Input::get('timeid');
			foreach($timeid as $key => $val) {
				
				if($val!='') {
					DB::table('ms_wo_time')
							->where('id', $val)
							->update(['time_in'	=> $timein[$key],
									  'time_out'	=> $timeout[$key]
							]);
				} else {
					
					DB::table('ms_wo_time')
							->insert(['workorder_id' => $id,
									  'time_in'	=> $timein[$key],
									  'time_out'	=> $timeout[$key]
							]);
				}
			}
		
		if(Input::get('rem_time')!='')
		{
			$arrids = explode(',', Input::get('rem_time'));
			foreach($arrids as $val) {
				DB::table('ms_wo_time')->where('id',$val)->update(['deleted_at' => date('Y-m-d H:i:s')]);
			}
			
		}
		
		Session::flash('message', 'Work order updated successfully');
		return redirect('ms_workorder');
	}
	
	public function destroy($id)
	{
		DB::table('ms_workorder')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		DB::table('ms_wo_time')->where('workorder_id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Work order deleted successfully.');
		return redirect('ms_workorder');
	}
	
	private function getStatus($status) {
		
		switch($status) {
			
			case 0:
			$name = 'Pending';
			break;
			
			case 1:
			$name = 'Hold';
			break;
			
			case 2:
			$name = 'Ongoing';
			break;
			
			case 3:
			$name = 'Completed';
			break;
		}
		
		return $name;
	}
	
}
