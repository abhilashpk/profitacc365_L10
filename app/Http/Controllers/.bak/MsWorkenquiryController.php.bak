<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;

class MsWorkenquiryController extends Controller
{
	
	public function __construct() {
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
	}
	
	public function index() {
		$orders = [];
		return view('body.msworkenquiry.index')
					->withOrders($orders);
	}
	
	private function getOrderCount($etype=null)
	{
		$qry = DB::table('ms_workenquiry')->where('ms_workenquiry.deleted_at','0000-00-00 00:00:00')
								->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
								->leftJoin('ms_location', 'ms_location.id', '=', 'ms_workenquiry.location_id')
								->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id');
				if($etype)
					$qry->where('ms_workenquiry.status',0)->where('ms_workenquiry.is_transfer',0);
								
		return $qry->count();
	}
	
	private function getOrderList($type,$start,$limit,$order,$dir,$search,$etype=null)
	{
		$qry = DB::table('ms_workenquiry')
								->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
								->leftJoin('ms_location', 'ms_location.id', '=', 'ms_workenquiry.location_id')
								->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id');
			if($etype)
					$qry->where('ms_workenquiry.status',0)->where('ms_workenquiry.is_transfer',0);
								
		if($search) {
			$qry->where(function($qry) use($search){ 
				$qry->where('ms_workenquiry.enq_no','LIKE',"%{$search}%")
				  ->orWhere('ms_customer.name', 'LIKE',"%{$search}%")
				  ->orWhere('ms_location.name', 'LIKE',"%{$search}%");
			});
			
		}
				
		$qry->where('ms_workenquiry.deleted_at','0000-00-00 00:00:00');
		$qry->select('ms_workenquiry.id','ms_workenquiry.enq_no','ms_workenquiry.enquiry_datetime',
					'ms_workenquiry.status','ms_customer.name AS customer','ms_workenquiry.location',
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
                            0 =>'enq_no',
                            1 => 'enquiry_datetime',
                            2 => 'customer',
                            3 => 'wo_type',
							4 => 'location',
                            5 => 'status'
                        );
						
		$totalData = $this->getOrderCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'ms_workenquiry.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
		$status = $request->input('status');
        
		$invoices = $this->getOrderList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getOrderList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			
			foreach ($invoices as $row)
            {
				$edit =  '"'.url('ms_workenquiry/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$print = url('ms_workenquiry/print/'.$row->id);
				
				$nestedData['id'] = $i = $row->id;
				
				$opt =  $row->id;
				$nestedData['enq_no'] = $row->enq_no;
				$nestedData['customer'] = $row->customer;
				$nestedData['enquiry_datetime'] = date('d-m-Y H:i', strtotime($row->enquiry_datetime));
				$nestedData['location'] = $row->location;
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
	
	public function add() {

		$location = DB::table('ms_location')->where('deleted_at','0000-00-00 00:00:00')->get();
		$wotype = DB::table('ms_worktype')->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$dat = DB::table('ms_workenquiry')->select('id')->orderBy('id','DESC')->first();
		$enqno = ($dat)?100+$dat->id+1:100+1;
				
				
		return view('body.msworkenquiry.add')
				->withLocation($location)
				->withNowdate( date('d-m-Y H:i') )
				->withEnqno('EQ'.$enqno)
				->withWotype($wotype);
	}
	
	public function save() { //echo '<pre>';print_r(Input::all());exit;
		try {
			$id = DB::table('ms_workenquiry')
					->insertGetId([
						'enquiry_datetime' => date('Y-m-d H:i', strtotime(Input::get('creation_datetime'))),
						'location' => Input::get('location'),
						'customer_id' => Input::get('customer_id'),
						'description' => Input::get('description'),
						'type_id' => Input::get('wo_type'),
						//'status' => Input::get('status'),
						'remarks' => Input::get('remarks'),
						'created_at' => date('Y-m-d h:i:s')
					]);
				
			if($id) {
				$wono = 100+$id;
				DB::table('ms_workenquiry')->where('id',$id)->update(['enq_no' => 'EQ'.$wono]);
			}
		
			Session::flash('message', 'Work enquiry added successfully.');
			return redirect('ms_workenquiry');
		} catch(ValidationException $e) { 
			return Redirect::to('ms_workenquiry/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$worow = DB::table('ms_workenquiry')
						->join('ms_customer', 'ms_customer.id', '=', 'ms_workenquiry.customer_id')
						->join('ms_worktype', 'ms_worktype.id', '=', 'ms_workenquiry.type_id')
						->leftJoin('ms_location', 'ms_location.id', '=', 'ms_workenquiry.location_id')
						->where('ms_workenquiry.id', $id)
						->select('ms_workenquiry.*','ms_customer.name AS customer','ms_worktype.name AS wotype')
						->first();
						
		//$location = DB::table('ms_location')->where('deleted_at','0000-00-00 00:00:00')->get();
		$wotype = DB::table('ms_worktype')->where('deleted_at','0000-00-00 00:00:00')->get();
		
		return view('body.msworkenquiry.edit')
					->withWorow($worow)
					//->withLocation($location)
					->withWotype($wotype);
					
	}
	
	public function update($id)
	{	//echo '<pre>';print_r(Input::all());exit;
			DB::table('ms_workenquiry')
					->where('id', $id)
					->update([
						'enquiry_datetime' => date('Y-m-d H:i', strtotime(Input::get('creation_datetime'))),
						'location' => Input::get('location'),
						'customer_id' => Input::get('customer_id'),
						'description' => Input::get('description'),
						'type_id' => Input::get('wo_type'),
						'status' => Input::get('status'),
						'remarks' => Input::get('remarks'),
						'modified_at' => date('Y-m-d h:i:s')
					]);
			
		Session::flash('message', 'Work enquiry updated successfully');
		return redirect('ms_workenquiry');
	}
	
	public function destroy($id)
	{
		DB::table('ms_workenquiry')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Work enquiry deleted successfully.');
		return redirect('ms_workenquiry');
	}
	
	
	public function getEnquiry()
	{
		return view('body.msworkenquiry.enquiry');
	}
	
	public function ajaxGetEnquiry(Request $request)
	{
		$columns = array( 
                            0 =>'ms_workenquiry.id', 
                            1 =>'enq_no',
                            2=> 'enquiry_datetime',
                            3=> 'customer',
                            4=> 'wo_type',
							5=> 'location'
                        );
						
		$totalData = $this->getOrderCount('list');
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->getOrderList('get', $start, $limit, $order, $dir, $search, 'list');
		
		if($search)
			$totalFiltered =  $this->getOrderList('count', $start, $limit, $order, $dir, $search, 'list');
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
				$opt =  $row->id;
				$nestedData['opt'] = "<input type='radio' name='salesDO' value='{$opt}'/>";
				
                $nestedData['id'] = $row->id;
                $nestedData['enq_no'] = $row->enq_no;
				$nestedData['enquiry_datetime'] = $row->enquiry_datetime;
				$nestedData['customer'] = $row->customer;
				$nestedData['wo_type'] = $row->wo_type;
				$nestedData['location'] = $row->location;
							
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
	
	
	private function getStatus($status) {
		
		switch($status) {
			
			case 0:
			$name = 'Open';
			break;
			
			case 1:
			$name = 'Transfered';
			break;
			
			case 2:
			$name = 'Cancelled';
			break;

		}
		
		return $name;
	}
	
}
