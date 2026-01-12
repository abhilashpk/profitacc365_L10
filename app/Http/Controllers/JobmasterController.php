<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Department\DepartmentInterface;
use App\Repositories\Jobmaster\JobmasterInterface;
use App\Repositories\Forms\FormsInterface;
use App\Repositories\VoucherNo\VoucherNoInterface;
use App\Repositories\Accategory\AccategoryInterface; 



use App\Repositories\Area\AreaInterface;
use App\Repositories\Itemmaster\ItemmasterInterface;
use App\Repositories\Terms\TermsInterface;

use App\Repositories\AccountMaster\AccountMasterInterface;
use App\Repositories\Currency\CurrencyInterface;

use App\Repositories\PurchaseOrder\PurchaseOrderInterface;
use App\Repositories\SupplierDo\SupplierDoInterface;
use App\Repositories\Location\LocationInterface;
use App\Repositories\PurchaseSplit\PurchaseSplitInterface;
use App\Repositories\AccountSetting\AccountSettingInterface;
use App\Repositories\Country\CountryInterface;
use App\Repositories\Acgroup\AcgroupInterface;

use App\Repositories\MaterialRequisition\MaterialRequisitionInterface;
use App\Repositories\UpdateUtility;
use App\Http\Requests;
use Input;
use Session;
use Response;
use App;
use DB;

class JobmasterController extends Controller
{
    protected $jobmaster;

	protected $accategory;
	
	protected $accountmaster;


	protected $accountsetting;

	public function __construct(JobmasterInterface $jobmaster,AccategoryInterface $accategory, DepartmentInterface $department,FormsInterface $forms,VoucherNoInterface $voucherno,  AccountMasterInterface $accountmaster,AccountSettingInterface $accountsetting) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->jobmaster = $jobmaster;
		$this->middleware('auth');
		$this->department = $department;
		$this->forms = $forms;
		$this->accategory = $accategory;
		$this->voucherno = $voucherno;
		$this->formData = $this->forms->getFormData('JM');

	
		$this->accountmaster = $accountmaster;
	
		$this->accountsetting = $accountsetting;
	
		
	}
	
	public function index() {
		$data = array();
		$jobmasters = [];
		$jobs = $this->jobmaster->activeJobmasterList();
		return view('body.jobmaster.index')
					->withJobmasters($jobmasters)
					->withJobs($jobs)
					->withData($data);
	}
	
	public function ajaxPaging(Request $request)
	{
		$columns = array( 
                            0 =>'jobmaster.id', 
                            1 =>'code',
                            2=> 'customer',
                            3=> 'name',
							4=> 'date'
                        );
						
		$totalData = $this->jobmaster->jobmasterListCount();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = 'jobmaster.id';
        $dir = 'desc';
		$search = (empty($request->input('search.value')))?null:$request->input('search.value');
        
		$invoices = $this->jobmaster->jobmasterList('get', $start, $limit, $order, $dir, $search);
		
		if($search)
			$totalFiltered =  $this->jobmaster->jobmasterList('count', $start, $limit, $order, $dir, $search);
		
        $data = array();
        if(!empty($invoices))
        {
           
			foreach ($invoices as $row)
            {
                $edit =  '"'.url('jobmaster/edit/'.$row->id).'"';
                $delete =  'funDelete("'.$row->id.'")';
				$history =  '"'.url('jobmaster/history/'.$row->id).'"';

                $nestedData['id'] = $row->id;
                $nestedData['code'] = $row->code;
				$nestedData['customer'] = $row->master_name;
				$nestedData['name'] = $row->name;
				$nestedData['date'] = ($row->date=='0000-00-00' || $row->date=='1970-01-01')?'':(date('d-m-Y', strtotime( $row->date)));
                $nestedData['edit'] = "<p><button class='btn btn-primary btn-xs' onClick='location.href={$edit}'>
												<span class='glyphicon glyphicon-pencil'></span></button></p>";
												
				$nestedData['delete'] = "<button class='btn btn-danger btn-xs delete' onClick='{$delete}'>
												<span class='glyphicon glyphicon-trash'></span>";
				$nestedData['history'] = "<p><button class='btn btn-warning btn-xs' onClick='location.href={$history}'>
												<i class='fa fa-fw fa-bookmark'></i></button></p>";
										
												
												
												
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

		$data = array();
		$department = $this->department->activeDepartmentList();
		$res        = $this->voucherno->getVoucherNo('JM'); 
		$jobtype    = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		//echo '<pre>';print_r($res);exit;
		return view('body.jobmaster.add')
					->withDepartment($department)
					->withVoucherno($res)
					->withJobtype($jobtype)
					->withFormdata($this->formData)
					->withData($data);
	}
	
	public function save() {
		$document=  $this->jobmaster->create(Input::all());
		$jid=$document["id"];

		if ($document["document_type"]=="0") {
		Session::flash('message', 'Jobmaster added successfully.');
		return redirect('jobmaster/add');
		}
		else if ($document["document_type"]=='1') {
		return redirect('purchase_split/add/'.$jid);
		}
		else if ($document["document_type"]=='2') {
		return redirect('sales_split/add/'.$jid );
		}
		Session::flash('message', 'Jobmaster added successfully.');
		return redirect('jobmaster/add');
		
	}
	public function edit($id) { 

		$data = array();
		$jobmasterrow = $this->jobmaster->find($id);//echo '<pre>';print_r($jobmasterrow);exit;
		$department = $this->department->activeDepartmentList();
		$jobtype    = DB::table('jobtype')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		return view('body.jobmaster.edit')
					->withJobmasterrow($jobmasterrow)
					->withDepartment($department)
					->withFormdata($this->formData)
					->withJobtype($jobtype)
					->withData($data);
	}
	public function budget() { 
		$data = array();
		
	
		//echo '<pre>';print_r($vouchers);exit;
	$jobs = $this->jobmaster->activeJobmasterList();
		//echo '<pre>';print_r($jobs);exit;
	//	$jobmasterrow = $this->jobmaster->find($id);
		//Session::flash('message', 'Accategory added successfully.');
		return view('body.jobmaster.budget')
		               //	->withJobmasterrow($jobmasterrow)
				
					->withSettings($this->acsettings)
					->withVatdata($this->vatdata)
				
					
		->withJobs($jobs)
		->withData($data);
	}
	public function budgetsave() {
                  
	//	echo '<pre>';print_r(Input::all());exit;
		$budget_document=  $this->jobmaster->budgetcreate(Input::all());
		
		
		Session::flash('message', 'Project Budgeting added successfully.');
		return redirect('jobmaster/viewbudget');
		
	}
	public function viewbudget() { 
		$data = array();
		
$prints = DB::table('report_view_detail')
							->join('report_view','report_view.id','=','report_view_detail.report_view_id')
							->where('report_view.code','PB')
							->select('report_view_detail.name','report_view_detail.id')
							->get();//echo '<pre>';print_r($prints);exit;
		$acctype = 	DB::table('budgeting')
		                    
						
						->leftJoin('jobmaster AS J','J.id', '=', 'budgeting.job_id' )
						->select('budgeting.total_cost','J.name AS jobname','budgeting.total_income','J.code AS jobcode','budgeting.created_at','budgeting.id')
						->where('budgeting.deleted_at', '0000-00-00 00:00:00')
						->get(); 
	
						//echo '<pre>';print_r($acctype);exit;
		//Session::flash('message', 'Accategory added successfully.');
		return view('body.jobmaster.viewbudget')
		->withAcctype($acctype)
			->withPrints($prints)
		->withData($data);
	}

	public function budgetdetail($id) { 

		$data = array();
			$jobs = $this->jobmaster->activeJobmasterList();
         $orderrow = $this->jobmaster->findPOdata($id);
		$orditems = $this->jobmaster->getItems($id);
		$orditemsinc = $this->jobmaster->getItemsinc($id);
	//	echo '<pre>';print_r($orditems);exit;
	return view('body.jobmaster.editbudget')
	
		->withSettings($this->acsettings)
					->withVatdata($this->vatdata)
				->withOrditemsinc($orditemsinc)
						->withOrditems($orditems)
					->withOrderrow($orderrow)
		->withJobs($jobs)
					->withData($data);
	}
	
		
		public function updatebudget($id)
	{
		$document= $this->jobmaster->updatebudget($id, Input::all());//print_r(Input::all());exit;
		
	
		Session::flash('message', 'budget updated successfully.');
		return redirect('jobmaster/viewbudget');
	}
	
	
	public function getPrint($id)
	{
	         	
				$titles = ['main_head' => 'Budgeting','subhead' => 'Budgeting'];
				
				

				$voucherhead = 'Project Budgeting';
				$jvrow = $this->jobmaster->findPOdata($id); 
				$jerow = $this->jobmaster->findJEdata($id);
				$jerowcost = $this->jobmaster->findJEcostdata($id);
			
				//	echo '<pre>';print_r($jerow);
				//echo '<pre>';print_r($jerowcost);exit;
				$words = $this->number_to_word($jvrow->total_cost);
				$arr = explode('.',number_format($jvrow->total_cost,2));
				if(sizeof($arr) >1 ) {
					if($arr[1]!=00) {
						$dec = $this->number_to_word($arr[1]);
						$words .= ' and Fils '.$dec.' Only';
					} else 
						$words .= ' Only';
				} else
					$words .= ' Only'; 
				

		return view('body.jobmaster.print')
					->withVoucherhead($voucherhead)
					->withVoucherhead($voucherhead)
							->withDetails($jvrow)
						->withJerow($jerow)
						->withJerowcost($jerowcost)
						
							->withAmtwords($words);
	}
	public function budDestroy($id)
	{
	   
		   
	   
		$this->jobmaster->buddelete($id);
   // 	//check jobmaster name is already in use.........
   // 	// code here ********************************
		Session::flash('message', 'Projected Budget deleted successfully.');
		return redirect('jobmaster/viewbudget');
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
	public function VehiHistory($id) { 

		$data = array();
		$jobmasterrow = $this->jobmaster->findhistory($id);//
	//	echo '<pre>';print_r($jobmasterrow);exit;
	return view('body.jobmaster.vehihistory')
					->withJobmasterrow($jobmasterrow)
				
					->withFormdata($this->formData)
					
					->withData($data);
	}
	public function update($id)
	{
		$document= $this->jobmaster->update($id, Input::all());//print_r(Input::all());exit;
		
		$jid=$document["id"];
		$cid=$document["cid"];

		$data = DB::table('sales_split')->where('customer_id',$cid)->where('status',1)->where('job_id',$jid)->where('deleted_at','0000-00-00 00:00:00')->select('id')->get();

		if ($document["document_type"]=="0") {
			Session::flash('message', 'Jobmaster updated successfully');
			return redirect('jobmaster');
		}
		else if ($document["document_type"]=='1') {
			return redirect('purchase_split/add/'.$jid);
		}
		else if ($document["document_type"]=='2') {
			if($data==null)
			return redirect('sales_split/add/'.$jid );
			else	
		    $sid=$data['0']->id;
		    return redirect('sales_split/edit/'.$sid );
		}
		Session::flash('message', 'Jobmaster added successfully.');
		return redirect('jobmaster/add');
	}
	// public function destroy($id)
	// {
	// 	//echo '<pre>';print_r($this->checkjobcode($id));exit;
	// 	if( $this->checkjobcode($id) ) { 
	// 		$this->jobmaster->delete($id);
			
	// 		//AUTO COST REFRESH CHECK ENABLE OR NOT
	// 		Session::flash('error', 'Sales invoice is already in use, you can\'t delete this!');
			
	// 	} else {
	// 		Session::flash('message', 'Sales invoice deleted successfully.');
	// 	}
		
	// 	return redirect('jobmaster');
	// }

		
	 public function destroy($id)
	 {
		
			
		
	 	$this->jobmaster->delete($id);
	// 	//check jobmaster name is already in use.........
	// 	// code here ********************************
	 	Session::flash('message', 'Jobmaster deleted successfully.');
	 	return redirect('jobmaster');
	 }
	public function checkjobcode($id) {

		$check = $this->jobmaster->check_jobmaster_code(Input::get('code'), Input::get('id')); //
		echo '<pre>';print_r($check);
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	public function checkcode() {

		$check = $this->jobmaster->check_jobmaster_code(Input::get('code'), Input::get('id')); //echo '<pre>';print_r($check);
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function checkname() {

		$check = $this->jobmaster->check_jobmaster_name(Input::get('jobname'), Input::get('id'));
		$isAvailable = ($check) ? false : true;
		echo json_encode(array(
							'valid' => $isAvailable,
						));
	}
	
	public function getJobdata($num=null)
	{
		$data = array();
		$jobs = $this->jobmaster->activeJobmasterList();//echo '<pre>';print_r($jobs);exit;
		 
		$customers = $this->accountmaster->getCustomerList();
		return view('body.jobmaster.jobs')
					->withJobs($jobs)
					->withNum($num)
					->withCustomers($customers)
					->withData($data);
	}
	
	public function getJobbdata()
	{
		$data = array();
		
	 
			$customers = $this->accountmaster->getCustomerList();
		$jobs = $this->jobmaster->activeJobmasterList();//echo '<pre>';print_r($customer);exit;
		return view('body.jobmaster.jobbs')
					->withJobs($jobs)
					->withCustomers($customers)
					->withData($data);
	}
	
	
	public function getJobAssign($no)
	{
		$data = array();
		$jobs = $this->jobmaster->activeJobmasterList();//echo '<pre>';print_r($jobs);exit;
		return view('body.jobmaster.jobassign')
					->withJobs($jobs)
					->withNo($no)
					->withData($data);
	}
	public function ajaxSave() {
		
		$as = $this->jobmaster->ajaxCreate(Input::all());
		return $as;
			
	}
}

