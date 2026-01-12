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

class ContractController extends Controller
{
	
	public function __construct() {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		
	}
	
	public function index() {
		$data = array();
		$contract = [];
		return view('body.contract.index')
					->withPaper($contract)
					->withData($data);
	}
	
	private function getContractList($type,$start,$limit,$order,$dir,$search)
	{	
		$query = DB::table('contract')
						->join('account_master', function($join) {
							$join->on('contract.customer_id','=','account_master.id');
						})
						->join('machine','machine.id','=','contract.machine_id')
						->where('contract.deleted_at',null);
						
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('account_master.master_name','LIKE',"%{$search}%");
					$query->orWhere('contract.contract_no', 'LIKE',"%{$search}%");
				});
			}
			
		$query->select('contract.*','account_master.master_name','machine.name AS machine')
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir);
				
			if($type=='get')
				return $query->get();
			else
				return $query->count();
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 => 'contract.id', 
                            1 => 'contract_no',
                            2 => 'contract_date',
							3 => 'customer',
                            4 => 'machine'
                        );
						
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')]; //'sales_split.id';
        $dir = $request->input('order.0.dir'); //'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$totalData = $this->getContractList('count', $start, $limit, $order, $dir, $search);
        $totalFiltered = $totalData;
		
		$invoices = $this->getContractList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getContractList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('contract/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$read =  '"'.url('contract/read/'.$row->id).'"';
                $nestedData['id'] = $row->id;
                $nestedData['contract_no'] = $row->contract_no;
				$nestedData['contract_date'] = date('d-m-Y', strtotime($row->contract_date));
				$nestedData['customer'] = $row->master_name;
				$nestedData['machine'] = $row->machine;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				$nestedData['read'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$read}'>
												<i class='fa fa-fw fa-rss-square'></i></button></p>";
				
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

		$vno = DB::table('voucher_no')->where('voucher_type','CO')->where('status',1)->select('no')->first();
		$types = DB::table('contract_type')->where('deleted_at',null)->select('id','name')->orderBy('name','ASC')->get();
		$paper = DB::table('paper')->where('deleted_at',null)->select('id','name')->orderBy('name','ASC')->get();
		$machine = DB::table('machine')->where('deleted_at',null)->select('id','name','brand','model')->orderBy('name','ASC')->get();
		return view('body.contract.add')
					->withNo($vno->no)
					->withTypes($types)
					->withMachine($machine)
					->withSettings($this->acsettings)
					->withPaper($paper);
	}
	
	public function save(Request $request) {
		try { //echo '<pre>';print_r($request->all());exit;
			
			//$vrow = DB::table('voucher_no')->where('voucher_type','CO')->select('no')->first();
			
			DB::table('contract')
				->insert([
					'contract_no' => $request->get('contract_no'),
					'contract_date' => date('Y-m-d'),
					'customer_id' => $request->get('customer_id'),
					'contract_type_id' => (!empty($request->get('type_id')))?serialize($request->get('type_id')):null,
					'start_date' => date('Y-m-d',strtotime($request->get('start_date'))),
					'end_date' => date('Y-m-d',strtotime($request->get('end_date'))),
					'duration' => $request->get('duration'),
					'machine_id' => $request->get('machine_id'),
					'paper_id' => (!empty($request->get('type_id')))?serialize($request->get('paper_id')):null,
					'remarks' => $request->get('remarks'),
					'created_by' => Auth::User()->id
				]);
			DB::table('voucher_no')->where('voucher_type','CO')->update(['no' => DB::raw('no + 1')]);
			Session::flash('message', 'Contract added successfully.');
			return redirect('contract/add');
		} catch(ValidationException $e) { 
			return Redirect::to('contract/add')->withErrors($e->getErrors());
		}
	}
	
	public function edit($id) { 

		$types = DB::table('contract_type')->where('deleted_at',null)->select('id','name')->orderBy('name','ASC')->get();
		$paper = DB::table('paper')->where('deleted_at',null)->select('id','name')->orderBy('name','ASC')->get();
		$machine = DB::table('machine')->where('deleted_at',null)->select('id','name','brand','model')->orderBy('name','ASC')->get();
		$contract = DB::table('contract')->where('contract.id',$id)
						->join('account_master', function($join) {
								$join->on('contract.customer_id','=','account_master.id');
							})
						->join('machine','machine.id','=','contract.machine_id')
						->select('contract.*','account_master.master_name','machine.name AS machine')->first();
						
		return view('body.contract.edit')
					->withTypes($types)
					->withMachine($machine)
					->withPaper($paper)
					->withSettings($this->acsettings)
					->withContract($contract);
	}
	
	public function update(Request $request,$id)
	{
		DB::table('contract')->where('id',$id)
				->update([
					'customer_id' => $request->get('customer_id'),
					'contract_type_id' => serialize($request->get('type_id')),
					'start_date' => date('Y-m-d',strtotime($request->get('start_date'))),
					'end_date' => date('Y-m-d',strtotime($request->get('contract_no'))),
					'duration' => $request->get('duration'),
					'machine_id' => $request->get('machine_id'),
					'paper_id' => serialize($request->get('paper_id')),
					'remarks' => $request->get('remarks'),
					'modify_at' => date('Y-m-d H:i:s'),
					'modify_by' => Auth::User()->id
				]);
		Session::flash('message', 'Contract updated successfully');
		return redirect('contract');
	}
	
	public function destroy($id)
	{
		DB::table('contract')->where('id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Contract deleted successfully.');
		return redirect('contract');
	}
	
	public function machineRead($id) {
		
		$contract = DB::table('contract')->where('contract.id',$id)
						->join('account_master', function($join) {
								$join->on('contract.customer_id','=','account_master.id');
							})
						->join('machine','machine.id','=','contract.machine_id')
						->select('contract.*','account_master.master_name','machine.name AS machine','machine.brand','machine.model')->first();
						
		
		$mdatas = DB::table('machine_read')->where('contract_id',$id)->where('deleted_at',null)->orderBy('id','DESC')->get();
		$types = DB::table('contract_type')->whereIn('id',unserialize($contract->contract_type_id))->select('name')->get();
		$paper = DB::table('paper')->whereIn('id', unserialize($contract->paper_id))->select('id','name')->get();
		
		$typename = '';
		foreach($types as $type) {
			$typename .= ($typename=='')?$type->name:', '.$type->name;
		}
		
		$pname = '';
		foreach($paper as $row) {
			$pname .= ($pname=='')?$row->name:', '.$row->name;
		}
		
		return view('body.contract.read')
					->withTypename($typename)
					->withPname($pname)
					->withPaper($paper)
					->withMdatas($mdatas)
					->withContract($contract);
					
	}
	
	public function machineReadSave(Request $request, $id) {
		
		//echo '<pre>';print_r($request->all());exit;
		$namearr = $request->get('paper_name');
		$qtyarr = $request->get('paper_qty');
		foreach($request->get('paper_id') as $key => $row) {
			$paperdata[] = ['id' => $row, 'name' => $namearr[$key], 'qty' => $qtyarr[$key]];
		}
		
		DB::table('machine_read')
					->insert(['contract_id' => $request->get('contract_id'),
							  'read_date'	=> date('Y-m-d',strtotime($request->get('read_date'))),
							  'paper_and_qty'	=> serialize($paperdata),
							  'created_by'	=> Auth::User()->id
							]);
							
		return redirect('contract/read/'.$id);
	}
	
	public function machineReadDelete($id,$rid) {
		
		DB::table('machine_read')->where('id',$rid)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Machine entry deleted successfully');
		return redirect('contract/read/'.$id);
	}
	
	public function machineReadEdit($id,$rid) {
		
		$reads = DB::table('machine_read')->where('id',$rid)->first(); //echo '<pre>';print_r($reads);;exit;
		
		return view('body.contract.readedit')
					->withId($id)
					->withReads($reads);
					
	}
	
	
	public function machineReadEditSave(Request $request,$id,$rid) {
		
		$namearr = $request->get('paper_name');
		$qtyarr = $request->get('paper_qty');
		foreach($request->get('paper_id') as $key => $row) {
			$paperdata[] = ['id' => $row, 'name' => $namearr[$key], 'qty' => $qtyarr[$key]];
		}
		
		DB::table('machine_read')
					->where('id',$rid)
					->update(['read_date'	=> date('Y-m-d',strtotime($request->get('read_date'))),
							  'paper_and_qty'	=> serialize($paperdata),
							  'created_at'	=> date('Y-m-d H:i:s'),
							  'created_by'	=> Auth::User()->id
							]);
		Session::flash('message', 'Machine entry updated successfully');			
		return redirect('contract/read/'.$id);
	}
	
}

