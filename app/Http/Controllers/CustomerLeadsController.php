<?php
namespace App\Http\Controllers;
use App\Repositories\Acgroup\AcgroupInterface; 
use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use Auth;
use App;

class CustomerLeadsController extends Controller
{
	protected $group;
	protected $accountmaster;
	protected $voucherno;
	protected $itemmaster;
	
	public function __construct(AcgroupInterface $group, AccountMasterInterface $accountmaster, VoucherNoInterface $voucherno,ItemmasterInterface $itemmaster) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$this->group = $group;
		$this->accountmaster = $accountmaster;
		$this->voucherno = $voucherno;
		$this->itemmaster = $itemmaster;
		
		//Getting Salesman id...
		//Session::put('salesman_id',Auth::User()->id);
		if(Auth::user()->roles[0]->name=='Salesman') {
		    $srec = DB::table('salesman')->where('name',Auth::user()->name)->select('id')->first();
		    if($srec)
		        Session::put('salesman_id',$srec->id);
		}
	}
	
	public function index() { 
		$data = array();
		//echo '<pre>';print_r(Auth::User()->id );exit;
		$cus_code = json_decode($this->ajax_getcode_cat($category='CUSTOMER'));
		$cusid = $cuscat = $supid = $supcat = '';
		if($cus_code) {
			$cusid = $cus_code->code;
			$cuscat = $cus_code->category;
		}
		
		$leads = [];
		return view('body.customerleads.index')
					->withDoctype($leads)
					->withCusid($cusid)
					->withCcategory($cuscat)
					->withData($data);
	}
	
	private function getCustomerCount() 
	{
		$query = DB::table('account_master')->where('account_master.status',1)->where('account_master.deleted_at','0000-00-00 00:00:00')
							->where('account_master.category','CUSTOMER')
							->join('crm_followup', function($join) {
								$join->on('crm_followup.customer_id','=','account_master.id')
								->where('crm_followup.is_parent','=',1);
							});
		
				if(Auth::user()->roles[0]->name=='Salesman')
					$query->where('account_master.salesman_id',Session::get('salesman_id'));
				
		return $query->count();
	}
	
	private function getCustomerList($type,$start,$limit,$order,$dir,$search,$status)
	{	
		$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
						//	->where('crm_followup.is_parent','=',1);
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
						->where('account_master.status',1)->where('account_master.category','CUSTOMER')
						->where('crm_followup.status','!=',4)
						->where('crm_followup.status','!=',1)
						->where('crm_followup.is_open','=',0)
						->where('account_master.deleted_at','0000-00-00 00:00:00');
						
			if(Auth::user()->roles[0]->name=='Salesman')
				$query->where('account_master.salesman_id',Session::get('salesman_id'));
						
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('account_master.master_name','LIKE',"%{$search}%");
					$query->orWhere('account_master.phone', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.fax', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.contact_name', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.area_id', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.country_id', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.email', 'LIKE',"%{$search}%");
					$query->orWhere('crm_followup.product_id', 'LIKE',"%{$search}%");
					$query->orWhere('salesman.name', 'LIKE',"%{$search}%");
				});
			}
			
			if($status)
				$query->where('crm_followup.status', $status);
			
			$query->select('account_master.id','account_master.master_name','account_master.phone','account_master.email','account_master.fax','account_master.contact_name',
							'account_master.vat_assign','account_master.vat_percentage','crm_followup.status')
				->offset($start)
				->limit($limit)
				->orderBy($order,$dir);
				
			if($type=='get')
				return $query->get();
			else
				return $query->count();
	}
	
		public function setStatus() {
        
        $date = (date('Y-m-d', strtotime(Input::get('date'))));
		DB::table('crm_followup')->where('id',Input::get('id'))->update(['status' => Input::get('sts')]);

		if(Input::get('sts')==1 )
		     DB::table('crm_followup')->where('id',Input::get('id'))->where('customer_id',Input::get('cust'))->update(['is_close' => 1]);
		elseif(Input::get('sts')==2)
		DB::table('crm_followup')->where('id',Input::get('id'))->where('customer_id',Input::get('cust'))->update(['is_open' => 0]);
	
		elseif(Input::get('sts')==3)
				DB::table('crm_followup')->where('id',Input::get('id'))->where('customer_id',Input::get('cust'))->update(['is_open' => 0]);
	
		elseif(Input::get('sts')==4)
				DB::table('crm_followup')->where('id',Input::get('id'))->where('customer_id',Input::get('cust'))->update(['is_close' => 1]);
	
		return $date;
		
	}
	public function ajaxPaging(Request $request)
	{
	    
		$columns = array( 
                            0 => 'id', 
                            1 => 'master_name',
                            2 => 'phone',
                            3 => 'contact',
                    
			4 => 'status',
                   
                        );
						
		$totalData = $this->getCustomerCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'account_master.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        $status = $request->input(' $status ');
	//	echo '<pre>';print_r($status );exit;
		$leads = $this->getCustomerList('get', $start, $limit, $order, $dir, $search, $status);
		
		if($search)
			$totalFiltered =  $this->getCustomerList('count', $start, $limit, $order, $dir, $search, $status);
		
        $data = array();
        if(!empty($leads))
        {
           
			foreach ($leads as $row)
            {
                $edit =  '"'.url('customerleads/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$enquiry = url('customerleads/enquiry/'.$row->id);
				
				if($row->status==1)
					$status = '<p class="btn btn-info btn-xs">Customer</p>';
				elseif($row->status==2)
					$status = '<p class="btn btn-primary btn-xs">Enquiry</p>';
				elseif($row->status==3)
					$status = '<p class="btn btn-warning btn-xs">Prospective</p>';
				elseif($row->status==4)
					$status = '<p class="btn btn-danger btn-xs">Archive</p>'; 
				
                $nestedData['id'] = $row->id;
                $nestedData['master_name'] = $row->master_name;
				$phone2 = ($row->fax!='')?', '.$row->vat_percentage.' '.$row->fax:'';
				$nestedData['phone'] = $row->vat_assign.' '.$row->phone.$phone2;
				$nestedData['customer'] = $row->contact_name;
				$nestedData['status'] = $status;
                              
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				
				$nestedData['enquiry'] = "<p><a href='{$enquiry}' class='btn btn-primary btn-xs'><i class='fa fa-fw fa-retweet'></i> Follow Up</a></p>";
											
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
	
	public function ajaxPagingleads(Request $request)
	{
	    
	    //echo '<pre>';print_r($request );exit;
		$columns = array( 
                            0 => 'id', 
                            1 => 'master_name',
                            2 => 'phone',
                            3 => 'contact',
                    4 => 'email',
			5 => 'status'
                    
                        );
						
		$totalData = $this->getCustomerCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'account_master.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        $status = $request->input('status');
		
		$leads = $this->getCustomerList('get', $start, $limit, $order, $dir, $search, $status);
		
		if($search)
			$totalFiltered =  $this->getCustomerList('count', $start, $limit, $order, $dir, $search, $status);
		
        $data = array();
        if(!empty($leads))
        {
           
			foreach ($leads as $row)
            {
                $edit =  '"'.url('customerleads/editcust/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$enquiry = url('customerleads/enquiry/'.$row->id);
				
				if($row->status==1)
					$status = '<p class="btn btn-info btn-xs">Customer</p>';
				elseif($row->status==2)
					$status = '<p class="btn btn-primary btn-xs">Enquiry</p>';
				elseif($row->status==3)
					$status = '<p class="btn btn-warning btn-xs">Prospective</p>';
				elseif($row->status==4)
					$status = '<p class="btn btn-danger btn-xs">Archive</p>'; 
				
                $nestedData['id'] = $row->id;
                $nestedData['master_name'] = $row->master_name;
				$phone2 = ($row->fax!='')?', '.$row->vat_percentage.' '.$row->fax:'';
				$nestedData['phone'] = $row->vat_assign.' '.$row->phone.$phone2;
				$nestedData['customer'] = $row->contact_name;
				$nestedData['status'] = $status;
                                $nestedData['email'] =  $row->email;
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
												
				
				$nestedData['enquiry'] = "<p><a href='{$enquiry}' class='btn btn-primary btn-xs'><i class='fa fa-fw fa-retweet'></i> Follow Up</a></p>";
											
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
	//	echo '<pre>';print_r($group);exit;
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
  	public function updatedateFollowup($id)
	{
	$date = (date('Y-m-d', strtotime(Input::get('date_hidden'))));
	
	
		if(Input::get('status')== 2 || Input::get('status')== 3)
		{
		$lead_id = DB::table('account_master')->where('id', $id)
							->update([
								'master_name' => Input::get('company_name'),
								'contact_name' => Input::get('customer_name'),
								'address' => Input::get('address'),
								'state' => Input::get('address3'),
								'pin'	=> Input::get('website'),
								'city' => Input::get('address2'),
								'phone' => Input::get('phone'),
								'fax' => Input::get('phone2'),
								'vat_assign' => Input::get('code'),
								'vat_percentage'  => Input::get('code1'),
								'email' => Input::get('email'),
								'reference' => Input::get('email2'),
								'area_id' => Input::get('area'),
								'country_id' => Input::get('country'),
							]);
							
		DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
		
		DB::table('crm_followup')
							->insert([
										'customer_id' => $id,
										'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 0
									]);
									
		 DB::table('crm_followup')
						->where('customer_id', $id)
						->update(['status'	=> Input::get('status')]);
								
	
			Session::flash('message', 'Customer enquiry updated successfully');
				return redirect('customerleads/editdatefollow/'.$id.'/'.$date);
	
							}
							
							
							
							else
							{
								$lead_id = DB::table('account_master')->where('id', $id)
								->update([
									'master_name' => Input::get('company_name'),
									'contact_name' => Input::get('customer_name'),
									'address' => Input::get('address'),
									'state' => Input::get('address3'),
									'pin'	=> Input::get('website'),
									'city' => Input::get('address2'),
									'phone' => Input::get('phone'),
									'fax' => Input::get('phone2'),
									'vat_assign' => Input::get('code'),
									'vat_percentage'  => Input::get('code1'),
									'email' => Input::get('email'),
									'reference' => Input::get('email2'),
									'area_id' => Input::get('area'),
									'country_id' => Input::get('country'),
								]);
								
			DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
			
			DB::table('crm_followup')
								->insert([
											'customer_id' => $id,
										      	'remark' => Input::get('remark'),
											'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
											'status'	=> Input::get('status'),
											'created_at' => date('Y-m-d H:i:s'),
											'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
											'is_parent' => 0
										]);
										
			 DB::table('crm_followup')
							->where('customer_id', $id)
							->update(['status'	=> Input::get('status')]);
									
			/* DB::table('crm_followup')
							->where('id', Input::get('fid'))
							->update([
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status')
									]); */
								
			
				Session::flash('message', 'Customer enquiry updated successfully');
				return redirect('customerleads/editdatefollow/'.$id.'/'.$date);

							}}
	public function updateFollowupOld($id)
	{
			//echo '<pre>';print_r(date('Y-m-d', strtotime(Input::get('date_hidden'))));exit;
		
		$date = (date('Y-m-d', strtotime(Input::get('date_hidden'))));
		$next_date = (date('Y-m-d', strtotime(Input::get('next_date'))));
		$lead_id = DB::table('account_master')->where('id', $id)
							->update([
								'master_name' => Input::get('company_name'),
								'contact_name' => Input::get('customer_name'),
								'address' => Input::get('address'),
								'state' => Input::get('address3'),
								'pin'	=> Input::get('website'),
								'city' => Input::get('address2'),
								'phone' => Input::get('phone'),
								'fax' => Input::get('phone2'),
								'vat_assign' => Input::get('code'),
								'vat_percentage'  => Input::get('code1'),
								'email' => Input::get('email'),
								'reference' => Input::get('email2'),
								'area_id' => Input::get('area'),
								'country_id' => Input::get('country'),
							]);
							
		DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
		
		DB::table('crm_followup')
							->insert([
										'customer_id' => $id,
										'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 0
									]);
									
		 DB::table('crm_followup')
						->where('customer_id', $id)
						->update(['status'	=> Input::get('status')]);
								
$rows = DB::table('crm_followup')->join('account_master', 'account_master.id','=','crm_followup.customer_id')
						->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
						->where('crm_followup.next_date', $date)->where('crm_followup.is_open',0)
						->select('crm_followup.*','account_master.master_name','account_master.phone','account_master.fax','account_master.contact_name','account_master.email',
						  'account_master.address','account_master.state','account_master.city','account_master.reference','account_master.area_id','account_master.country_id',
						  'account_master.vat_assign','account_master.vat_percentage')
						->get();
$prodarr = [];
if($rows) {
  foreach($rows as $row) {
	  $idarr = explode(',', $row->product_id);
	  if($idarr) {
		  $prd = DB::table('itemmaster')->whereIn('id', $idarr)->select('description')->get();
		  $pname = '';
		  foreach($prd as $p) {
			  $pname .= ($pname=='')?$p->description:','.$p->description;
		  }
		  $prodarr[$row->id] = $pname;
	  }
	  
  }
}
  //echo '<pre>';print_r($rows);exit;	
  Session::flash('message', 'Customer enquiry updated successfully');	
return view('body.customerleads.followups')
		  ->withDocrow($rows)
		  ->withProducts($prodarr)
		  ->withDate($date);
							
		
		
		
							//	return redirect('customerleads/edit/'.$id);
	}
	public function editdateFollow($id,$date) { 
		//echo '<pre>';print_r($date);exit;

		$data = array();
		$row = DB::table('account_master')->where('id',$id)
				 ->select('id','master_name','address','phone','state','city','fax','reference','area_id','country_id','email','contact_name','pin','vat_assign','vat_percentage')->first();
								  
		$follow = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->first();
		$items = $this->itemmaster->activeItemmasterList();
		$cods = DB::table('country')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$remarks = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
		//echo '<pre>';print_r($row);exit;
		//echo '<pre>';print_r($remarks);exit;
		return view('body.customerleads.editfollowup')
					->withDocrow($row)
					->withDate($date)
					->withRemarks($remarks)
					->withRowfolo($follow)
					->withItems($items)
					->withCode($cods)
					->withData($data);
	}
	public function checkPhone() {

		$query = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')
						->where('vat_assign',Input::get('code'))->where('category','CUSTOMER');
						
		if(Input::get('id')!='') {
			$query->where('id', '!=', Input::get('id'));
		} 
		
		$phone1 = Input::get('phone'); $phone2 = Input::get('phone2');
		
		$query->where(function($qry) use ($phone1,$phone2) {
			$qry->where('phone',$phone1);
			$qry->orWhere('phone',$phone2);
			$qry->orWhere('fax',$phone1);
			$qry->orWhere('fax',$phone2);
		});
			
		$count = $query->count();
		echo json_encode(array('valid' => ($count) ? false : true));
	}
public function getTransfer()
{
	$data = array();
	$userss_id = DB::table('users')->where('created_at','!=','')->get();
	$customers = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','master_name')->get();
	return view('body.customerleads.datatransfer')
				->withCustomers($customers)
				->withUserssId($userss_id);

}

public function TransferSave()
{
    	$userss_id = DB::table('salesman')->get();
		$customers = Input::get('customers');
		
//	echo '<pre>';print_r(Input::get('from_transfer'));
//	echo '<pre>';print_r(Input::get('to_transfer'));
	$data = array();
	$salesid = Input::get('from_transfer');
	$qry = DB::table('crm_followup')
						->where('salesman_id', $salesid);
						
	if(isset($customers))
		$qry->whereIn('customer_id', $customers);
	
	$qry->update(['salesman_id'	=> Input::get('to_transfer'),'is_log' => 1]);
					

	$crm_id = DB::table('crm_followup')->where('salesman_id',Input::get('to_transfer'))->where('is_log',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')	->get();
	
		$crmmid = [];
		if($crm_id) {
			foreach($crm_id as $row) {
			   
				DB::table('data_transfer')
						->insert([
									'from_transfer' => Input::get('from_transfer'),
									'to_transfer' => Input::get('to_transfer'),
									'created_at' => date('Y-m-d H:i:s'),
									'crm_id'   => $row->id
								]);	
				
			}
		}

	 Session::flash('message', 'Data Transfer updated successfully');	
	 	return redirect('customerleads/data_transfer');
	//return view('body.customerleads.datatransfer')
	            //	->withUserssId($userss_id);
			//	->withData($data);

}

public function TransferSave2()
{
	//echo '<pre>';print_r(Input::all());
	$customers = Input::get('customers');//exit;
	//
	$salesid = Input::get('from_transfer');
	$qry = DB::table('crm_followup')
					->where('salesman_id', $salesid);
						
		if(isset($customers))
			$qry->whereIn('customer_id', $customers);
						
		$qry->update(['salesman_id'	=> Input::get('to_transfer'),'is_log' => 1]);

	$crm_id = DB::table('crm_followup')->where('salesman_id',Input::get('to_transfer'))->where('is_log',1)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
	
	//echo '<pre>';print_r($crm_id);exit;
	
	DB::table('crm_followup')
						->insert([
									'from_transfer' => Input::get('from_transfer'),
									'to_transfer' => Input::get('to_transfer'),
									'crm_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
								]);					
	return view('body.customerleads.datatransfer')
				->withUserssId($userss_id);

}
	public function checkEmail() {

		$query = DB::table('account_master')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->where('category','CUSTOMER');
		
		if(Input::get('id')!='') {
			$query->where('id', '!=', Input::get('id'));
		}
		
		$email1 = Input::get('email'); $email2 = Input::get('email2');
		
		$query->where(function($qry) use ($email1,$email2) {
			$qry->where('email',$email1);
			$qry->orWhere('email',$email2);
			$qry->orWhere('reference',$email1);
			$qry->orWhere('reference',$email2);
		});
			
		$count = $query->count();
		echo json_encode(array('valid' => ($count) ? false : true));
	}
	
	public function add($id=null) {
		

		$customer = null;
		$items = $this->itemmaster->activeItemmasterList();
		$cods = DB::table('country')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		if($id) {
			$customer = DB::table('account_master')->where('id',$id)->first();
		}
		return view('body.customerleads.add')
					->withItems($items)
					->withCode($cods)
					->withCustomer($customer);
	}
	
	private function create_account($attributes)
	{
		$group = DB::table('account_group')->where('category','CUSTOMER')
										->where('status',1)
										->where('deleted_at','0000-00-00 00:00:00')
										->select('id','category_id','code')
										->first();
										
		$cust_id = DB::table('account_master')
						->insertGetId([
							'master_name' => $attributes['company_name'],
							'account_category_id' => $group->category_id,
							'account_group_id' => $group->id,
							'transaction_type' => 'Dr',
							'address' => $attributes['address'],
							'city' => $attributes['address2'],
							'state' => $attributes['address3'],
							'pin'	=> $attributes['website'],
							'phone' => $attributes['phone'],
							'vat_assign' => $attributes['code'],
							'vat_percentage'  => $attributes['code1'],
							'fax' => $attributes['phone2'],
							'email' => $attributes['email'],
							'reference' => $attributes['email2'],
							'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
							'area_id' => $attributes['area'],
							'country_id' => $attributes['country'],
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => Auth::User()->id,
							'status' => 1,
							'category' => 'CUSTOMER',
							'contact_name' => $attributes['customer_name']
						]);
		DB::table('account_master')->where('id', $cust_id)->update(['account_id' => 'ACM'.$cust_id]);
		
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
		public function save()
	{
	if(Input::get('status')!=0)
		{
	     if(Input::get('status')==2 || Input::get('status')==3)
		   {
		   DB::beginTransaction();
	      	try { 
		
			if(Input::get('customer_id')==''){
				$custid = $this->create_account(Input::all());
			} else
				$custid = Input::get('customer_id');
			
				DB::table('crm_followup')
							->insert([
										'customer_id' => $custid,
										'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 1
									]);
			
		
			//echo '<pre>';print_r($remarks);exit;
			//$reamrks_remark =$popupStats[0]->status ;
			DB::commit();
			
			Session::flash('message', 'Customer enquiry added successfully.');
			if(Input::get('customer_id')=='')
		{	
				return redirect('customerleads/editadd/'.$custid);
		}
			else
			{
			
			return redirect('customerleads/editadd/'.$custid);
			    
			}
			
		} catch(\Exception $e) {
				
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			
		}
	
}else
	{
		DB::beginTransaction();
		try { 
		
			if(Input::get('customer_id')==''){
				$custid = $this->create_account(Input::all());
			} else
				$custid = Input::get('customer_id');
			
				DB::table('crm_followup')
							->insert([
										'customer_id' => $custid,
										//'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										//'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 1
									]);
			
			DB::commit();
			
			Session::flash('message', 'Customer enquiry added successfully.');
			if(Input::get('customer_id')=='')
		{	
				return redirect('customerleads/editadd/'.$custid);
		
		}
			else
			{
			
			return redirect('customerleads/editadd/'.$custid);
			    
			}
			
		} catch(\Exception $e) {
				
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage();exit;
			
		}}

	}else
		{
            Session::flash('message', 'Select Status.');
			return redirect('customerleads/add/');

		}
	}
	
	
  public function updateAdd($id)
	{
		//echo '<pre>';print_r(Input::get('sid'));exit; 	
		if(Input::get('status')!=0)
		{
	if(Input::get('status')== 2 || Input::get('status')== 3)
		{
		$lead_id = DB::table('account_master')->where('id', $id)
							->update([
								'master_name' => Input::get('company_name'),
								'contact_name' => Input::get('customer_name'),
								'address' => Input::get('address'),
								'state' => Input::get('address3'),
								'pin'	=> Input::get('website'),
								'city' => Input::get('address2'),
								'phone' => Input::get('phone'),
								'fax' => Input::get('phone2'),
								'vat_assign' => Input::get('code'),
								'vat_percentage'  => Input::get('code1'),
								'email' => Input::get('email'),
								'reference' => Input::get('email2'),
								'area_id' => Input::get('area'),
								'country_id' => Input::get('country'),
							]);
	//	if((!empty(Input::get('remark'))) || (!empty(Input::get('next_date'))))
		//{					
		DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
		
		DB::table('crm_followup')
							->insert([
										'customer_id' => $id,
										'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 0
									]);
									
		 DB::table('crm_followup')
						->where('customer_id', $id)
						->update(['status'	=> Input::get('status')]);
								
		/* DB::table('crm_followup')
						->where('id', Input::get('fid'))
						->update([
									'remark' => Input::get('remark'),
									'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
									'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
									'status'	=> Input::get('status')
								]); */
							
		             
		               	Session::flash('message', 'Customer enquiry updated successfully');
		               	//return redirect('customerleads/add/');
		               	return redirect('customerleads/editadd/'.$id);
	//	}else
							//	{
									// Session::flash('message', 'Update Remark and Next Date!');	
									
							//	}
			//return redirect('customerleads');
							}
							
							
							
							else
							{
								$lead_id = DB::table('account_master')->where('id', $id)
								->update([
									'master_name' => Input::get('company_name'),
									'contact_name' => Input::get('customer_name'),
									'address' => Input::get('address'),
									'state' => Input::get('address3'),
									'pin'	=> Input::get('website'),
									'city' => Input::get('address2'),
									'phone' => Input::get('phone'),
									'fax' => Input::get('phone2'),
									'vat_assign' => Input::get('code'),
									'vat_percentage'  => Input::get('code1'),
									'email' => Input::get('email'),
									'reference' => Input::get('email2'),
									'area_id' => Input::get('area'),
									'country_id' => Input::get('country'),
								]);
								
			DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
			
			DB::table('crm_followup')
								->insert([
											'customer_id' => $id,
											//'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
											'remark' => Input::get('remark'),
										//	'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
											'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
											'status'	=> Input::get('status'),
											'created_at' => date('Y-m-d H:i:s'),
											'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
											'is_parent' => 0
										]);
										
			 DB::table('crm_followup')
							->where('customer_id', $id)
							->update(['status'	=> Input::get('status')]);
									
			/* DB::table('crm_followup')
							->where('id', Input::get('fid'))
							->update([
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status')
									]); */
								
			
				Session::flash('message', 'Customer enquiry updated successfully');
				return redirect('customerleads/editadd/'.$id);

							}}
							else
			{
				Session::flash('message', 'Select Status.');
				return redirect('customerleads/editadd/'.$id);
	
			}						//	return redirect('customerleads/edit/'.$id);
	}	
	
		public function editAdd($id) { 
       // echo '<pre>';print_r($id);exit;
		$data = array();
		$row = DB::table('account_master')->where('id',$id)
				 ->select('id','master_name','address','phone','state','city','fax','reference','area_id','country_id','email','contact_name','pin','vat_assign','vat_percentage')->first();
								  
		$follow = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->first();
		$items = $this->itemmaster->activeItemmasterList();
		$cods = DB::table('country')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$remarks = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
	//	echo '<pre>';print_r($remarks);exit;
		//echo '<pre>';print_r($follow);exit;
		return view('body.customerleads.editadd')
					->withDocrow($row)
					->withRemarks($remarks)
					->withRowfolo($follow)
					->withItems($items)
					->withCode($cods)
					 
					->withData($data);
	}
	
	public function edit($id,$sid=null) { 
        //echo '<pre>';print_r($sid);exit;
		$data = array();
		$row = DB::table('account_master')->where('id',$id)
				 ->select('id','master_name','address','phone','state','city','fax','reference','area_id','country_id','email','contact_name','pin','vat_assign','vat_percentage')->first();
								  
		$follow = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->first();
		$items = $this->itemmaster->activeItemmasterList();
		$cods = DB::table('country')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->get();
		
		$remarks = DB::table('crm_followup')->where('customer_id',$id)->where('deleted_at','0000-00-00 00:00:00')->orderBy('id','DESC')->get();
	//	echo '<pre>';print_r($remarks);exit;
		//echo '<pre>';print_r($follow);exit;
		return view('body.customerleads.edit')
					->withDocrow($row)
					->withRemarks($remarks)
					->withRowfolo($follow)
					->withItems($items)
					->withCode($cods)
					 ->withSid($sid)
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
						]);
		
	}
	
	public function update($id)
	{
	    	//echo '<pre>';print_r(Input::get('sid'));exit; 
	    	$sid = Input::get('sid');
	    //echo '<pre>';print_r(date('Y-m-d', strtotime(Input::get('remark_date'))));
	   // echo '<pre>';print_r(date('Y-m-d', strtotime(Input::get('next_date'))));exit; 
		
	if(Input::get('status')== 2 || Input::get('status')== 3)
		{
		$lead_id = DB::table('account_master')->where('id', $id)
							->update([
								'master_name' => Input::get('company_name'),
								'contact_name' => Input::get('customer_name'),
								'address' => Input::get('address'),
								'state' => Input::get('address3'),
								'pin'	=> Input::get('website'),
								'city' => Input::get('address2'),
								'phone' => Input::get('phone'),
								'fax' => Input::get('phone2'),
								'vat_assign' => Input::get('code'),
								'vat_percentage'  => Input::get('code1'),
								'email' => Input::get('email'),
								'reference' => Input::get('email2'),
								'area_id' => Input::get('area'),
								'country_id' => Input::get('country'),
							]);
							
		DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
		
		DB::table('crm_followup')
							->insert([
										'customer_id' => $id,
										'remark_date' => date('Y-m-d', strtotime(Input::get('remark_date'))),
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status'),
										'created_at' => date('Y-m-d H:i:s'),
										'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
										'is_parent' => 0
									]);
									
		 DB::table('crm_followup')
						->where('customer_id', $id)
						->update(['status'	=> Input::get('status')]);
								
		/* DB::table('crm_followup')
						->where('id', Input::get('fid'))
						->update([
									'remark' => Input::get('remark'),
									'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
									'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
									'status'	=> Input::get('status')
								]); */
							
		
			Session::flash('message', 'Customer enquiry updated successfully');
			return redirect('customerleads/edit/'.$id.'/'.$sid);
	//	return redirect('customerleads/edit/'.$id);

			//return redirect('customerleads');
							}
							
							
							
							else
							{
								$lead_id = DB::table('account_master')->where('id', $id)
								->update([
									'master_name' => Input::get('company_name'),
									'contact_name' => Input::get('customer_name'),
									'address' => Input::get('address'),
									'state' => Input::get('address3'),
									'pin'	=> Input::get('website'),
									'city' => Input::get('address2'),
									'phone' => Input::get('phone'),
									'fax' => Input::get('phone2'),
									'vat_assign' => Input::get('code'),
									'vat_percentage'  => Input::get('code1'),
									'email' => Input::get('email'),
									'reference' => Input::get('email2'),
									'area_id' => Input::get('area'),
									'country_id' => Input::get('country'),
								]);
								
			DB::table('crm_followup')->where('customer_id',$id)->update(['is_open' => 1]);
			
			DB::table('crm_followup')
								->insert([
											'customer_id' => $id,
											'remark' => Input::get('remark'),
											'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
											'status'	=> Input::get('status'),
											'created_at' => date('Y-m-d H:i:s'),
											'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
											'is_parent' => 0
										]);
										
			 DB::table('crm_followup')
							->where('customer_id', $id)
							->update(['status'	=> Input::get('status')]);
									
			/* DB::table('crm_followup')
							->where('id', Input::get('fid'))
							->update([
										'remark' => Input::get('remark'),
										'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
										'product_id' => (Input::get('products')!='')?implode(',', Input::get('products')):'',
										'status'	=> Input::get('status')
									]); */
								
			
				Session::flash('message', 'Customer enquiry updated successfully');
			return redirect('customerleads/edit/'.$id.'/.'.$sid);

							}
	}
	public function destroy($id)
	{
		DB::table('account_master')->where('id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		DB::table('account_transaction')->where('account_master_id',$id)->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
		DB::table('crm_followup')->where('customer_id',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
		
		Session::flash('message', 'Customer deleted successfully.');
		return redirect('customerleads');
	}
	
	public function getFollowups($date)
	{
		$next_date = (date('Y-m-d', strtotime($date)));
		
		$rows = DB::table('crm_followup')->join('account_master', 'account_master.id','=','crm_followup.customer_id')
								  ->where('crm_followup.status','!=',4)
		->where('crm_followup.status','!=',1)->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
								  ->where('crm_followup.next_date', $next_date) ->where('crm_followup.is_open','=',0)
								  ->select('crm_followup.*','account_master.master_name','account_master.phone','account_master.fax','account_master.contact_name','account_master.email',
									'account_master.address','account_master.state','account_master.city','account_master.reference','account_master.area_id','account_master.country_id',
									'account_master.vat_assign','account_master.vat_percentage')
								  ->get();
		$prodarr = [];
		if($rows) {
			foreach($rows as $row) {
				$idarr = explode(',', $row->product_id);
				if($idarr) {
					$prd = DB::table('itemmaster')->whereIn('id', $idarr)->select('description')->get();
					$pname = '';
					foreach($prd as $p) {
						$pname .= ($pname=='')?$p->description:','.$p->description;
					}
					$prodarr[$row->id] = $pname;
				}
				
			}
		}
			//echo '<pre>';print_r($rows);exit;	
			
		return view('body.customerleads.followups')
					->withDocrow($rows)
					->withProducts($prodarr)
					->withDate($date);
	}
	
	public function customerType() { 

		$data = array();
		$cus_code = json_decode($this->ajax_getcode_cat($category='CUSTOMER'));
		//echo '<pre>';print_r($cus_code);exit;	
		$cusid = $cuscat = $supid = $supcat = '';
		if($cus_code) {
			$cusid = $cus_code->code;
			$cuscat = $cus_code->category;
		}
		
		$leads = [];
		return view('body.customerleads.customertype')
					->withDoctype($leads)
					->withCusid($cusid)
					->withCcategory($cuscat)
					->withData($data);
	}
	
	public function ProspectiveStatus() { 

		//$data = array();
		$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
						//	->where('crm_followup.is_parent','=',1);
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
					//	->where('account_master.status',1)->where('account_master.category','CUSTOMER')
					->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
				    ->where('crm_followup.is_open','=',0)
				
				
						->where('crm_followup.status','=',3)
						->where('account_master.deleted_at','0000-00-00 00:00:00');
		
		if(Auth::user()->roles[0]->name=='Salesman')
						$query->where('account_master.salesman_id',Session::get('salesman_id'));			
		$prospective = $query->select('account_master.id','account_master.master_name','account_master.email','account_master.reference','crm_followup.customer_id','account_master.phone','account_master.fax','account_master.contact_name',
									'account_master.vat_assign','account_master.vat_percentage','crm_followup.status','crm_followup.remark','crm_followup.next_date','crm_followup.remark_date')
								->orderBy('crm_followup.next_date','ASC')	
								->get();
//	echo '<pre>';print_r($prospective);exit;
						
		return view('body.customerleads.prospective')->withProspective($prospective);
	}
	
	
	private function getCustomerListCustomer($type,$start,$limit,$order,$dir,$search,$status)
	{
	   
			$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
						//	->where('crm_followup.is_parent','=',1);
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
					//	->where('account_master.status',1)->where('account_master.category','CUSTOMER')
					->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
				    ->where('crm_followup.is_open','=',0)
					->where('crm_followup.status','=',1)
					->where('account_master.deleted_at','0000-00-00 00:00:00');
						
			if(Auth::user()->roles[0]->name=='Salesman')
				$query->where('account_master.salesman_id',Session::get('salesman_id'));
						
			if($search) {
				$query->where(function($query) use ($search){
					$query->where('account_master.master_name','LIKE',"%{$search}%");
					$query->orWhere('account_master.phone', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.fax', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.contact_name', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.area_id', 'LIKE',"%{$search}%");
					$query->orWhere('account_master.country_id', 'LIKE',"%{$search}%");
					$query->orWhere('crm_followup.product_id', 'LIKE',"%{$search}%");
					$query->orWhere('salesman.name', 'LIKE',"%{$search}%");
				});
			}
			
			if($status ==1)
		//	echo '<pre>';print_r($status);exit;
				$query->where('crm_followup.status','=',1);
			
				$query->select('account_master.id','account_master.master_name','account_master.phone','account_master.email','account_master.fax','account_master.contact_name',
							'account_master.vat_assign','account_master.vat_percentage','crm_followup.status','crm_followup.remark','crm_followup.next_date','crm_followup.remark_date')
			//	->offset($start)
			//	->limit($limit)
				->orderBy($order,$dir);
				
			if($type=='get')
				return $query->get();
			else
				return $query->count();
	}
	
									 				
	public function ajaxCustomerStatus(Request $request) { 
	    
	   // echo '<pre>';print_r($request);
	    
	    $columns = array( 
                            0 => 'id', 
                            1 => 'master_name',
                            2 => 'phone',
                            3 => 'contact',
                    4 => 'email',
                    5 => 'remark',
			6 => 'status'
                    
                        );
                        
          $totalData = $this->getCustomerCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'account_master.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        $status = $request->input('status');
	//	echo '<pre>';print_r($status);exit;
		$leads = $this->getCustomerListCustomer('get', $start, $limit, $order, $dir, $search, 1);
	//	echo '<pre>';print_r($leads);exit;
		if($search)
			$totalFiltered =  $this->getCustomerListCustomer('count', $start, $limit, $order, $dir, $search, 1);
		
        $data = array();
        if(!empty($leads))
        {
           
			foreach ($leads as $row)
            {
                $edit =  '"'.url('customerleads/editcust/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$enquiry = url('customerleads/enquiry/'.$row->id);
				
				if($row->status==1)
					$status = '<p class="btn btn-info btn-xs">Customer</p>';
			
				
                $nestedData['id'] = $row->id;
                $nestedData['master_name'] = $row->master_name;
				$phone2 = ($row->fax!='')?', '.$row->vat_percentage.' '.$row->fax:'';
				$nestedData['phone'] = $row->vat_assign.' '.$row->phone.$phone2;
				$nestedData['customer'] = $row->contact_name;
				$nestedData['status'] = $status;
                 $nestedData['email'] =  $row->email;
                // $nestedData['remark'] =  $row->remark;
               // $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
											//	<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
			//	$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
										//		<span class='glyphicon glyphicon-trash'></span>";
												
				
			//	$nestedData['enquiry'] = "<p><a href='{$enquiry}' class='btn btn-primary btn-xs'><i class='fa fa-fw fa-retweet'></i> Follow Up</a></p>";
											
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
	
	
		public function CustomerStatus() { 

		//$data = array();
		$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
						
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
				
						->where('crm_followup.status','=',1)
						->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
				           ->where('crm_followup.is_open','=',0)
				           // ->where('crm_followup.is_close','=',1)
						->where('account_master.deleted_at','0000-00-00 00:00:00');
						
		
		if(Auth::user()->roles[0]->name=='Salesman')
						$query->where('account_master.salesman_id',Session::get('salesman_id'));			
		$prospective = $query->select('account_master.id','account_master.master_name','account_master.email','account_master.reference','crm_followup.customer_id','account_master.phone','account_master.fax','account_master.contact_name',
									'account_master.vat_assign','account_master.vat_percentage','crm_followup.status','crm_followup.next_date','crm_followup.remark','crm_followup.remark_date')
									->get();
		//echo '<pre>';print_r($prospective);exit;
						
		return view('body.customerleads.customer')->withProspective($prospective);
	}
public function EnquiryStatus() { 

		//$data = array();  
		$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
							//->where('crm_followup.is_parent','=',1);
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
					//	->where('account_master.status',1)->where('account_master.category','CUSTOMER')
						->where('crm_followup.status','=',2)
						->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
				         ->where('crm_followup.is_open','=',0)
						->where('account_master.deleted_at','0000-00-00 00:00:00');
			if(Auth::user()->roles[0]->name=='Salesman')
						$query->where('account_master.salesman_id',Session::get('salesman_id'));			
	
					
		$prospective = $query->select('account_master.id','account_master.master_name','account_master.email','account_master.reference','crm_followup.customer_id','account_master.phone','account_master.fax','account_master.contact_name',
									'account_master.vat_assign','account_master.vat_percentage','crm_followup.status','crm_followup.remark','crm_followup.next_date','crm_followup.remark_date')
								->orderBy('crm_followup.next_date','ASC')
								
									->get();
	//	echo '<pre>';print_r($prospective);exit;
						
		return view('body.customerleads.enquirystatus')->withProspective($prospective);
	}	
	
	public function ArchiveStatus() { 

		//$data = array();
		$query = DB::table('account_master')
						->join('crm_followup', function($join) {
							$join->on('crm_followup.customer_id','=','account_master.id');
						
						})
						->leftJoin('salesman','salesman.id','=','crm_followup.salesman_id')
					//	->where('account_master.status',1)->where('account_master.category','CUSTOMER')
						->where('crm_followup.status','=',4)
						->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
				         ->where('crm_followup.is_open','=',0)
				        // ->where('crm_followup.is_close','=',1)
						->where('account_master.deleted_at','0000-00-00 00:00:00');
		
		if(Auth::user()->roles[0]->name=='Salesman')
						$query->where('account_master.salesman_id',Session::get('salesman_id'));			
				
		$prospective = $query->select('account_master.id','account_master.master_name','account_master.email','account_master.reference','crm_followup.customer_id','account_master.phone','account_master.fax','account_master.contact_name',
									'account_master.vat_assign','account_master.vat_percentage','crm_followup.status','crm_followup.remark','crm_followup.remark_date')
									->get();
		//echo '<pre>';print_r($prospective);exit;
						
		return view('body.customerleads.archive')->withProspective($prospective);
	}	
	
	
		public function ajaxCreate()
	{
		$date = (Input::get('date')=='')?date('Y-m-d'):date('Y-m-d', strtotime(Input::get('date')));
	
         //echo '<pre>';print_r($date); exit;
			//  echo '<pre>';print_r($cust);
			//  exit;
			
		 $popupStats = DB::table('crm_followup')->where('id',Input::get('rowid'))
		  						->where('customer_id',Input::get('customer_id'))
		  						->select('crm_followup.status')->get();
		 $status_follow =$popupStats[0]->status ;	
		DB::table('crm_followup')
		->where('id',Input::get('rowid'))
		
		->where('customer_id',Input::get('customer_id'))
		->update(['is_open' => 1]);
		DB::table('crm_followup')  
		                        ->insert([
									'customer_id' => Input::get('customer_id'),
									'remark_date' => date('Y-m-d'),
									'remark' => Input::get('remark'),
									'next_date' => date('Y-m-d', strtotime(Input::get('next_date'))),
									'product_id' => Input::get('product_id'),
									'status' => Input::get('status'),
									
									'created_at' => date('Y-m-d H:i:s'),
									'salesman_id' => (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0,
									'parent_id' => Input::get('parent_id')
								]);

							
								
	
		DB::table('crm_followup')
		                ->where('parent_id', Input::get('parent_id'))
						->update(['status' => $status_follow]);
		
		//return 'true';
		return $date ; 
	}
	
	public function getEnquiry($id)
	{
		$enquiry = DB::table('crm_followup')->join('account_master','account_master.id','=','crm_followup.customer_id')
					->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
					->where('crm_followup.customer_id',$id)->where('crm_followup.deleted_at','0000-00-00 00:00:00')
					->where('crm_followup.is_parent',1)->orderBy('crm_followup.id','DESC')->select('crm_followup.*','account_master.master_name')->get();
		
		return view('body.customerleads.enquiry')
					->withEnquiry($enquiry)->withId($id);
	}
	public function getDetails($name){
		//echo '<pre>';print_r($name);exit;
		$data = array();
		$row = DB::table('account_master')->join('users AS U','U.id','=','account_master.created_by')
		                                    ->where('account_master.master_name','SALES')
		                                   ->select('account_master.master_name','U.name')->get();
										   echo '<pre>';print_r($row);exit;
			return view('body.customerleads.details')	
			             ->withRow($row)
					    ->withData($data);							   

	}
	
	public function getFollowup($id)
	{
		$qry1 = DB::table('crm_followup')->join('account_master','account_master.id','=','crm_followup.customer_id')
					->where('crm_followup.status','!=',4)->where('crm_followup.is_open','=',0)
		->where('crm_followup.status','!=',1)->where('crm_followup.id',$id)->where('crm_followup.deleted_at','0000-00-00 00:00:00')
					->where('crm_followup.salesman_id', (Auth::user()->roles[0]->name=='Salesman')?Session::get('salesman_id'):0)
					->select('crm_followup.*','account_master.master_name');
		
		$qry2 = DB::table('crm_followup')->join('account_master','account_master.id','=','crm_followup.customer_id')
					->where('crm_followup.parent_id',$id)->where('crm_followup.deleted_at','0000-00-00 00:00:00')
					->orderBy('crm_followup.id','ASC')->select('crm_followup.*','account_master.master_name');
					
		$result = $qry1->union($qry2)->get();
		//echo '<pre>';print_r($result);exit;
		
		$prodarr = [];
		if($result) {
			foreach($result as $row) {
				$idarr = explode(',', $row->product_id);
				if($idarr) {
					$prd = DB::table('itemmaster')->whereIn('id', $idarr)->select('description')->get();
					$pname = '';
					foreach($prd as $p) {
						$pname .= ($pname=='')?$p->description:','.$p->description;
					}
					$prodarr[$row->id] = $pname;
				}
				
			}
		}
		
		return view('body.customerleads.viewfollowup')
					->withResults($result)->withProdarr($prodarr);
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
		return view('body.customerleads.loadfollowup')
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
	
	public function doPhone() {

		$query = DB::table('account_master')->where('account_master.status',1)->where('account_master.deleted_at','0000-00-00 00:00:00')
						->where('account_master.vat_assign',Input::get('code'))
						->where('account_master.category','CUSTOMER')
						->join('salesman','salesman.id','=','account_master.salesman_id');
						
		/* if(Input::get('id')!='') {
			$query->where('id', '!=', Input::get('id'));
		}  */
		
		$phone1 = Input::get('phone'); $phone2 = Input::get('phone2');
		
		$query->where(function($qry) use ($phone1,$phone2) {
			$qry->where('account_master.phone',$phone1);
			if($phone2!='')
				$qry->orWhere('account_master.phone',$phone2);
			$qry->orWhere('account_master.fax',$phone1);
			if($phone2!='')
				$qry->orWhere('account_master.fax',$phone2);
		});
			
		$result = $query->select('salesman.name')->first();
		return ($result)?$result->name:'';
		//echo json_encode(array('valid' => ($count) ? false : true));
	}
	
}

//SELECT currency.decimal_name,purchase_order.voucher_no,purchase_order.reference_no,purchase_order.voucher_date,purchase_order.total,purchase_order.vat_amount AS total_vat,purchase_order.discount,purchase_order.net_amount,purchase_order.subtotal,purchase_order.total_fc,purchase_order.discount_fc,purchase_order.vat_amount_fc,purchase_order.net_amount_fc,currency.code,account_master.account_id,account_master.master_name,account_master.address,account_master.phone,account_master.vat_no,terms.description AS terms,purchase_order_item.item_name,purchase_order_item.quantity,purchase_order_item.unit_price,purchase_order_item.vat,purchase_order_item.vat_amount,purchase_order_item.total_price,purchase_order_item.tax_include,purchase_order_item.item_total,purchase_order_item.vat_amount AS line_vat,purchase_order_item.unit_price_fc,purchase_order_item.item_total_fc,purchase_order_item.total_price_fc,purchase_order_item.vat_amount_fc AS line_vat_fc,itemmaster.item_code,units.unit_name,header.description AS header,footer.description AS footer FROM purchase_order JOIN account_master ON(account_master.id=purchase_order.supplier_id) LEFT JOIN terms ON(terms.id=purchase_order.terms_id) JOIN purchase_order_item ON(purchase_order_item.purchase_order_id=purchase_order.id) JOIN itemmaster ON(itemmaster.id=purchase_order_item.item_id) JOIN units ON(units.id=purchase_order_item.unit_id) LEFT JOIN header_footer header ON(header.id=purchase_order.header_id) LEFT JOIN header_footer footer ON(footer.id=purchase_order.footer_id) JOIN currency ON(currency.id=purchase_order.currency_id) WHERE purchase_order_item.status=1 AND purchase_order_item.deleted_at='0000-00-00 00:00:00' AND purchase_order.id={id}

