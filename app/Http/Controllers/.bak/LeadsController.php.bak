<?php
namespace App\Http\Controllers;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Auth;
use App;

class LeadsController extends Controller
{
	protected $group;
	protected $accountmaster;
	protected $voucherno;
	
	public function __construct(AcgroupInterface $group, AccountMasterInterface $accountmaster, VoucherNoInterface $voucherno) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->group = $group;
		$this->accountmaster = $accountmaster;
		$this->voucherno = $voucherno;
	}
	
	public function index() {
		$data = array();
		$cus_code = json_decode($this->ajax_getcode_cat($category='CUSTOMER'));
		$cusid = $cuscat = $supid = $supcat = '';
		if($cus_code) {
			$cusid = $cus_code->code;
			$cuscat = $cus_code->category;
		}
		
		$leads = [];
		return view('body.leads.index')
					->withDoctype($leads)
					->withCusid($cusid)
					->withCcategory($cuscat)
					->withData($data);
	}
	
	private function getLeadsCount()
	{
		return DB::table('leads')->where('leads.status',1)->where('leads.deleted_at','0000-00-00 00:00:00')
							->join('account_master', 'account_master.id','=','leads.customer_id')
							->count();
	}
	
	private function getLeadsList($type,$start,$limit,$order,$dir,$search)
	{
			$query = DB::table('leads')->join('account_master', 'account_master.id','=','leads.customer_id')
									->leftJoin('salesman', 'salesman.id','=','leads.salesman_id');
							
				if($search) {
					$query->where(function($query) use ($search){
						$query->where('account_master.master_name','LIKE',"%{$search}%");
						$query->orWhere('leads.lead_no', 'LIKE',"%{$search}%");
						$query->orWhere('leads.lead_status', 'LIKE',"%{$search}%");
						$query->orWhere('salesman.name', 'LIKE',"%{$search}%");
					});
				}
				
				$query->where('leads.status',1)->where('leads.deleted_at','0000-00-00 00:00:00');
				
				$query->select('leads.id','leads.lead_no','leads.lead_date','leads.lead_status','account_master.master_name AS customer',
								'salesman.name AS salesman')
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
                            0 =>'leads.id', 
                            1 =>'lead_no',
                            2=> 'lead_date',
                            3=> 'customer',
							4=> 'salesman',
							5=> 'status'
                        );
						
		$totalData = $this->getLeadsCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'leads.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$leads = $this->getLeadsList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->getLeadsList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($leads))
        {
           
			foreach ($leads as $row)
            {
                $edit =  '"'.url('leads/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$followup = url('leads/followup/'.$row->id);
				
                $nestedData['id'] = $row->id;
                $nestedData['lead_no'] = $row->lead_no;
				$nestedData['lead_date'] = date('d-m-Y', strtotime($row->lead_date));
				$nestedData['customer'] = $row->customer;
				$nestedData['salesman'] = $row->salesman;
				$nestedData['status'] = $row->lead_status;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				
				$nestedData['followup'] = "<p><a href='{$followup}' target='_blank' class='btn btn-primary btn-xs'><i class='fa fa-fw fa-retweet'></i> Follow Up</a></p>";
											
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
	
	
	public function ajax_getcode_cat($category)
	{
		$group = $this->group->getGroupCode($category);
		if($group) {
			$row = $this->accountmaster->getGroupCode($category);
			if($row)
				$no = intval(preg_replace('/[^0-9]+/', '', $row->account_id), 10);
			else 
				$no = 0;
			
			$no++;
			return json_encode(array(
								'code' => strtoupper($group->code).''.$no,
								'category' => $group->category
							));
		} else
			return json_encode(array());
		//return $code = strtoupper($group->code).''.$no;

	}
	
	public function add() {

		$data = array();
		$res = $this->voucherno->getVoucherNo('LD');
		$vno = $res->no;
		$employee = DB::table('employee')->where('status',1)->where('duty_status','!=',-1)->where('deleted_at','0000-00-00 00:00:00')->get();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.leads.add')
					->withEmployee($employee)
					->withSalesman($salesman)
					->withVoucherno($vno)
					->withData($data);
	}
	
	private function create_account($attributes)
	{
		$group = DB::table('account_group')->where('category','CUSTOMER')
										->where('status',1)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('id','category_id','code')
										->first();
										
		$rowac = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('account_id')->orderBy('id','DESC')->first();
		$no = intval(preg_replace('/[^0-9]+/', '', $rowac->account_id), 10);
		$no++;
		
		$cust_id = DB::table('account_master')
						->insertGetId([
							'account_id' =>  $group->code.$no,
							'master_name' => $attributes['customer_name'],
							'account_category_id' => $group->category_id,
							'account_group_id' => $group->id,
							'transaction_type' => 'Dr',
							'address' => $attributes['address'],
							'phone' => $attributes['phone'],
							'email' => $attributes['email'],
							'contact_name' => $attributes['contact_name'],
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => Auth::User()->id,
							'status' => 1,
							'category' => 'CUSTOMER'
						]);
		
		$settings = DB::table('parameter1')->select('from_date','to_date')->first();
		
		DB::table('account_transaction')
					->insert([  'voucher_type' 		=> 'OB',
								'voucher_type_id'   => $cust_id,
								'account_master_id' => $cust_id,
								'transaction_type'  => 'Dr',
								'amount'   			=> 0,
								'status' 			=> 1,
								'created_at' 		=> date('Y-m-d H:i:s'),
								'created_by' 		=> Auth::User()->id,
								'description' 		=> '',
								'reference'			=> '',
								'invoice_date'		=> $settings->from_date ]);
								
		return $cust_id;
		
	}
	
	public function save() {
		//echo date('Y-m-d',strtotime(Input::get('voucher_date')));exit;
		DB::beginTransaction();
		try { 
			if(Input::get('customer_type')==0)
				$custid = $this->create_account(Input::all());
			else 
				$custid = Input::get('customer_id');
			
			$lead_date = (Input::get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d', strtotime(Input::get('voucher_date')));
			$lead_id = DB::table('leads')->insertGetId([
									'lead_no' => Input::get('lead_no'),
									'lead_date' => $lead_date,
									'customer_id' => $custid,
									'description' => Input::get('description'),
									'lead_status' => Input::get('lead_status'),
									'status' => 1,
									'created_by' => Auth::User()->id,
									'customer_type' => Input::get('customer_type'),
									'salesman_id' => Input::get('salesman_id')
								]);
								
			DB::table('followups')->insert([
									'lead_id' => $lead_id,
									'date' => $lead_date,
									'title' => Input::get('title'),
									'description' => Input::get('remarks'),
									'status' => 1,
									'created_by' => Auth::User()->id
								]);
			
			DB::table('voucher_no')
						->where('voucher_type', 'LD')
						->update(['no' => Input::get('lead_no') + 1]);
						
			DB::commit();
			
			
			if(Input::get('lead_status')=='Enquiry') 
				return redirect('customer_enquiry/add/'.$lead_id);
			else {
				Session::flash('message', 'Lead enquiry added successfully.');
				return redirect('leads');
			}
			
		} catch(\Exception $e) {
				
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			
		}
		//return redirect('leads');
	}
	
	public function edit($id) { 

		$data = array();
		$lead = DB::table('leads')->join('account_master', 'account_master.id','=','leads.customer_id')
								  ->where('leads.id',$id)
								  ->select('leads.*','account_master.master_name','account_master.address','account_master.phone',
									'account_master.fax','account_master.email','account_master.contact_name')
								  ->first();
		$follow = DB::table('followups')->where('lead_id',$id)->select('id','title','description')->first();
		$salesman = DB::table('salesman')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		return view('body.leads.edit')
					->withDocrow($lead)
					->withRowfolo($follow)
					->withSalesman($salesman)
					->withData($data);
	}
	
	private function update_account($attributes)
	{
		
		$cust_id = DB::table('account_master')->where('id',$attributes['customer_id'])
						->update([
							'master_name' => $attributes['customer_name'],
							'address' => $attributes['address'],
							'phone' => $attributes['phone'],
							'email' => $attributes['email'],
							'contact_name' => $attributes['contact_name']
						]);
		
	}
	
	public function update($id)
	{
		if(Input::get('customer_type')==0) {
			$this->update_account(Input::all());	
			$custid = Input::get('customer_id');
		} else 
			$custid = Input::get('customer_id');
		
		$lead_date = (Input::get('voucher_date')=='')?date('Y-m-d'):date('Y-m-d', strtotime(Input::get('voucher_date')));
		$lead_id = DB::table('leads')->where('id', $id)
							->update([
								'lead_date' => $lead_date,
								'customer_id' => $custid,
								'description' => Input::get('description'),
								'lead_status' => Input::get('lead_status'),
								'modified_by' => Auth::User()->id,
								'modified_at' => date('Y-m-d H:i:s'),
								'customer_type' => Input::get('customer_type'),
								'salesman_id' => Input::get('salesman_id')
							]);
								
		DB::table('followups')->where('id',Input::get('followup_id'))
							->update([
								'date' => $lead_date,
								'title' => Input::get('title'),
								'description' => Input::get('remarks'),
								'modified_by' => Auth::User()->id,
								'modified_at' => date('Y-m-d H:i:s')
							]);
							
		if(Input::get('lead_status')=='Enquiry') 
			return redirect('customer_enquiry/add/'.$id);
		else {
			Session::flash('message', 'Lead enquiry updated successfully');
			return redirect('leads');
		}
	}
	
	public function destroy($id)
	{
		DB::table('leads')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Lead enquiry deleted successfully.');
		return redirect('leads');
	}
	
	public function getFollowup($id)
	{
		$data = array();
		$lead = DB::table('leads')->join('account_master', 'account_master.id','=','leads.customer_id')
								  ->where('leads.id',$id)
								  ->select('leads.*','account_master.master_name','account_master.address','account_master.phone',
									'account_master.fax','account_master.email','account_master.contact_name')
								  ->first();
		$follow = DB::table('followups')->where('lead_id',$id)->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('date','DESC')->get();
		return view('body.leads.followup')
					->withDocrow($lead)
					->withFollos($follow)
					->withData($data);
	}
	
	public function ajaxSaveFollowup()
	{
		$date = (Input::get('date')=='')?date('Y-m-d'):date('Y-m-d', strtotime(Input::get('date')));
		DB::table('followups')->insert([
									'lead_id' => Input::get('lead_id'),
									'date' => $date,
									'title' => Input::get('title'),
									'description' => Input::get('description'),
									'status' => 1,
									'created_by' => Auth::User()->id
								]);
		Session::flash('message', 'New entry added successfully');						
		//return true;
	}
	
	
	public function destroyFollowup($id,$lid)
	{
		DB::table('followups')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s'),'deleted_by' => Auth::User()->id,'deleted_at' => date('Y-m-d H:i:s')]);
		Session::flash('message', 'Follow up entry deleted successfully.');
		return redirect('leads/followup/'.$lid);
	}
	
	public function loadFollowup($id)
	{
		$data = array();
		$row = DB::table('followups')->where('id',$id)->first();
		return view('body.leads.loadfollowup')
					->withRow($row)
					->withData($data);
	}
	
	public function ajaxUpdateFollowup()
	{
		$date = (Input::get('date')=='')?date('Y-m-d'):date('Y-m-d', strtotime(Input::get('date')));
		DB::table('followups')->where('id',Input::get('id'))
							->update([
									'date' => $date,
									'title' => Input::get('title'),
									'description' => Input::get('description'),
									'modified_by' => Auth::User()->id,
									'modified_at' => date('Y-m-d H:i:s')
								]);
		Session::flash('message', 'Entry updated successfully');						
		//return true;
	}
	
	public function setEnquiry($id)
	{
		DB::table('leads')->where('id',$id)->update(['lead_status' => 'Enquiry']);
		return redirect('customer_enquiry/add/'.$id);
	}
	
}
