<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Journal\JournalInterface;
use App\Repositories\ReceiptVoucher\ReceiptVoucherInterface;
use App\Repositories\PaymentVoucher\PaymentVoucherInterface;
use App\Repositories\UpdateUtility;
use App\Repositories\VoucherwiseReport\VoucherwiseReportInterface; 
use App\Repositories\TemplateName\TemplateNameInterface;

use App\Http\Requests;
use Input;
use Session;
use Response;
use DB;
use App;
use Auth;
use Config;
use DateTime;
use Mail;
use PDF;

class ContractExpiryController extends Controller
{
    protected $contract_building;
	protected $template_name;
	protected $journal;
	public $objUtility;
	protected $receipt_voucher;
	protected $payment_voucher;
	protected $voucherwise_report;
	
	public function __construct(JournalInterface $journal,ReceiptVoucherInterface $receipt_voucher,PaymentVoucherInterface $payment_voucher,VoucherwiseReportInterface $voucherwise_report,TemplateNameInterface $template_name) {
		
		parent::__construct( App::make('App\Repositories\Parameter1\Parameter1Interface'), App::make('App\Repositories\VatMaster\VatMasterInterface') );
		$this->middleware('auth');
		$config = Config::get('siteconfig');
		$this->template_name=$template_name;
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
	
	
	
	
	public function expiry() {
		$contractbuilding = [];
		$building = DB::table('buildingmaster')->where('deleted_at',null)->select('buildingcode','id')->get();
		$tenants = DB::table('account_master')->where('category','CUSTOMER')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('master_name','id')->get();
		
		//echo '<pre>';print_r($contractbuilding);exit;
		return view('body.contractbuilding.expiry')
					->withContractbuilding($contractbuilding)
					->withBuilding($building)
					->withTenants($tenants);
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
		if($attributes['building']!='') { 
						$query->where('contract_building.building_id', $attributes['building']);
					}

		if($attributes['tenant']!='') { 
						$query->where('contract_building.customer_id', $attributes['tenant']);
					}
		$query->select('contract_building.contract_date','contract_building.id AS id','contract_building.rent_amount','contract_building.description','contract_building.grand_total','contract_building.contract_no',
		'contract_building.start_date','contract_building.end_date','contract_building.duration','AM.master_name','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat');			
					

	if(isset($attributes['type']))
		return $query->groupBy('contract_building.id')->get()->toArray();
	else
		return $query->groupBy('contract_building.id')->get();				

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
	public function getSearch()
	{
		$data = array();
		$voucher_head  = '';
		$total=$spstotal=$dstotal=$cstotal=$pstotal=0;
		$txtotal  = 0;
		if(Input::get('search_type')=="expiry")
		{
			$voucher_head = 'Expiry ';
            $report = $this->getReport(Input::all());
			//$reports = $this->makeTreeexp($report);
		    $titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}
		else if(Input::get('search_type')=="buildingwise") {
			$voucher_head = 'Vacant Flats';
			$reports = $this->getReportvacant(Input::all());
			//$reports = $this->getBuildingwise($reports);
			$titles = ['main_head' => 'Account Enquiry','subhead' => $voucher_head ];


		}else if(Input::get('search_type')=='tenantwise') {
	        $voucher_head = 'DETAIL';
			$report = $this->getReportrent(Input::all());
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
		//echo '<pre>';print_r($report);exit;
		return view('body.contractbuilding.expirylist')
					->withReports($report)
					->withVoucherhead($voucher_head)
					->withType(Input::get('search_type'))
					->withFromdate(Input::get('date_from'))
					->withTodate(Input::get('date_to'))
					->withI(0)
				//	->withTotal(($total='')?$total:'' )
					//->withTxtotal($txtotal)
				    ->withTitles($titles)
				    ->withSettings($this->acsettings)
					->withData($data);
	}

	public function expiryTemplate(){
		$data = array();
		$tname = $this->template_name->activeTemplateList();
		$id=$_POST['id'];
	
		$mid = DB::table('contract_building')
		                         ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
		                        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
		                        ->whereIn('contract_building.id',$id)
								->select('contract_building.*','AM.master_name','F.flat_no AS flat')->get();
	
	    //echo '<pre>';print_r($row);exit;
		return view('body.contractbuilding.expirytemplate')	
		->withData($data)
		->withTname($tname)
		->withId($id)
		->withMid($mid);
		
	}

	public function getMessage($id){
		$row=DB::table('template_name')
		                ->where('id',$id)->first();
		
		//echo '<pre>';print_r($row);exit;
		echo json_encode($row);
	}
	
	public function expiryEmail(){
		//$data=array();
		$id=$_POST['id'];
		
		$mid = DB::table('contract_building')
		                         ->join('account_master AS AM','AM.id','=','contract_building.customer_id')
								 ->join('buildingmaster AS B','B.id','=','contract_building.building_id')
		                        ->leftjoin('flat_master AS F','F.id','=','contract_building.flat_no')
		                        ->whereIn('contract_building.id',$id)
								->select('contract_building.*','AM.master_name','AM.email','B.buildingcode AS buildcode','B.buildingname AS buildname','F.flat_no AS flat')->get();
		$titles = ['main_head' => 'Contract Expiry Details','subhead' => 'Contract Expiry Details'];
		$company = DB::select("SELECT * FROM company WHERE id=1");						
		foreach($mid as $row){
		$data = array('details'=> $row, 'titles' => $titles, 'company'=>$company);
		$mailmessage=$_POST['message'];
		$messages=$mailmessage;
		$name=$row->master_name;
		$email=$row->email;
		$body='Hello %s , This is %s .Please find the below attachment for more details.Thank you';
		$text= sprintf($body,$name,$messages);
		//echo '<pre>';print_r($text);exit;
		//return view('body.contractbuilding.expiryemailprint')->withDetails($row)->withTitles($titles)->withCompany($company);
		$pdf = pdf::loadView('body.contractbuilding.expiryemailprint', $data);
		if($email!='') {
			try{
				    Mail::raw($text,function($message) use ($email,$pdf,$text) {
					$message->from(env('MAIL_USERNAME'));	
					$message->to($email);
					$message->subject('Contract Expiry Details');
					$message->attachData($pdf->output(), "Contract Expiry.pdf");
				    });
				
			    }catch(JWTException $exception){
				$this->serverstatuscode = "0";
				$this->serverstatusdes = $exception->getMessage();
				echo '<pre>';print_r($this->serverstatusdes);exit;
			}
		}
		else{
			echo '<script>alert("Email not found");window.close();</script>';
		}
		}
			echo '<script>alert("Email sent successfully");window.close();</script>';
	}
	
	

	
	public function getEnddate($date,$dur) {
		
		$date = date('Y-m-d', strtotime($date. ' + '.$dur.' months')); 
		$dt = date('Y-m-d', strtotime($date. ' - 1 days'));
		echo json_encode(date('d-m-Y',strtotime($dt)));
		
	}
	
	
	
	
	
}
