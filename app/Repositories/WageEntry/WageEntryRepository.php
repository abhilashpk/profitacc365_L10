<?php
declare(strict_types=1);
namespace App\Repositories\WageEntry;

use App\Models\WageEntry;
use App\Models\WageEntryItems;
use App\Models\WageEntryJob;
use App\Models\WageEntryOthers;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Auth;

class WageEntryRepository extends AbstractValidator implements WageEntryInterface {
	
	protected $wageentry;
	
	protected static $rules = [
		/* 'code' => 'required|unique:wageentry',
		'name' => 'required|unique:wageentry' */
	];
	
	public function __construct(WageEntry $wageentry) {
		$this->wageentry = $wageentry;
		$config = Config::get('siteconfig');
		$this->width = $config['modules']['leave']['image_size']['width'];
        $this->height = $config['modules']['leave']['image_size']['height'];
        $this->thumbWidth = $config['modules']['leave']['thumb_size']['width'];
        $this->thumbHeight = $config['modules']['leave']['thumb_size']['height'];
        $this->imgDir = $config['modules']['leave']['image_dir'];
		
	}
	
	public function all()
	{
		return $this->wageentry->get();
	}
	
	public function find($id)
	{
		return $this->wageentry->where('id', $id)->first();
	}
	
	private function setInputValue($attributes)
	{
		if($attributes['entry_type']=='daily') { 
			$this->wageentry->entry_type = $attributes['entry_type'];
			$this->wageentry->year = $attributes['year'];
			$this->wageentry->month = $attributes['month'];
			$this->payment_date = date('Y-m-d');
			$this->wageentry->employee_id = $attributes['employee_id'];
			$this->wageentry->absent_hrs = isset($attributes['absent_hrs'])?$attributes['absent_hrs']:0;
			$this->wageentry->sick_leave = isset($attributes['sick_leave'])?$attributes['sick_leave']:0;
			$this->wageentry->paid_leave = isset($attributes['paid_leave'])?$attributes['paid_leave']:0;
			$this->wageentry->loan = isset($attributes['loan'])?$attributes['loan']:0;
		} else {
			$this->wageentry->entry_type = $attributes['entry_type'];
			$this->wageentry->year = $attributes['year'];
			$this->wageentry->month = $attributes['month'];
			$this->payment_date = date('Y-m-d');
			$this->wageentry->employee_id = $attributes['employee_id'];
			$this->wageentry->absent_hrs = isset($attributes['absent_hrs'])?$attributes['absent_hrs']:0;
			$this->wageentry->sick_leave = isset($attributes['sick_leave'])?$attributes['sick_leave']:0;
			$this->wageentry->paid_leave = isset($attributes['paid_leave'][0])?$attributes['paid_leave'][0]:0;
			$this->wageentry->unpaid_leave = isset($attributes['unpaid_leave'][0])?$attributes['unpaid_leave'][0]:0;
		}
		
		
		return true;
	}
	
	private function setInputItemsValue($attributes, $objWageEntryItem, $key) {
	
		$objWageEntryItem->wage_entry_id = $this->wageentry->id;
		$objWageEntryItem->day    		= isset($attributes['day'][$key])?$attributes['day'][$key]:'';
		$objWageEntryItem->job_id  		= $attributes['job_id'][$key];
		$objWageEntryItem->wage  		= $attributes['wage'][$key];
		$objWageEntryItem->nodays  		= $attributes['nodays'][$key];
		$objWageEntryItem->nwh  		= $attributes['nwh'][$key];
		$objWageEntryItem->otg  		= $attributes['otg'][$key];
		$objWageEntryItem->oth  		= $attributes['oth'][$key];
		$objWageEntryItem->leave_status	= $attributes['leave'][$key];
		//$objWageEntryItem->is_salary  		= $attributes['is_salary'][$key];
		$objWageEntryItem->job_data  		= $attributes['job_data'][$key];
		$objWageEntryItem->total_wage  		= $attributes['line_total'][$key];
		$objWageEntryItem->allowance  		= $attributes['alw'][$key];
		$objWageEntryItem->leave_type  		= isset($attributes['pstatus'][$key])?$attributes['pstatus'][$key]:0;
		$objWageEntryItem->leave_reason  	= isset($attributes['leave_reason'][$key])?$attributes['leave_reason'][$key]:'';
		$objWageEntryItem->otg_wage    		= isset($attributes['otg_wage'][$key])?$attributes['otg_wage'][$key]:'';
		$objWageEntryItem->oth_wage    		= isset($attributes['oth_wage'][$key])?$attributes['oth_wage'][$key]:'';
		$objWageEntryItem->job_date    		= isset($attributes['job_date'][$key])?$attributes['job_date'][$key]:'';
	}
	
	private function setInputItemsValueUpdate($attributes, $objWageEntryItem, $key) {
	
		$items['day']           		= $attributes['day'][$key];
		$items['job_id '] 		= $attributes['job_id'][$key];
		$items['wage']  		= $attributes['wage'][$key];
		$items['nodays']  		= $attributes['nodays'][$key];
		$items['nwh']  		= $attributes['nwh'][$key];
		$items['otg']  		= $attributes['otg'][$key];
		$items['oth']  		= $attributes['oth'][$key];
		$items['leave_status']	= $attributes['leave'][$key];
		$items['job_data']  		= $attributes['job_data'][$key];
		$items['total_wage']  		= str_replace(',', '',$attributes['line_total'][$key]);
		$items['allowance']  		= str_replace(',', '',$attributes['alw'][$key]);
		$items['leave_type']  		= isset($attributes['pstatus'][$key])?$attributes['pstatus'][$key]:0;
		$items['leave_reason']  	= $attributes['leave_reason'][$key];
		
		$items['desc_deduction4'] = $attributes['desc_deduction4'];
		$objWageEntryItem->update($items);
	}
	
	private function setInputJobsValue($itemObj, $jrow) {
		
		$jArr = explode(':',$jrow);
		$arr = explode(',',$jArr[1]);
		
		$objWageEntryJob = new WageEntryJob();
		$objWageEntryJob->wage_entry_items_id = $itemObj->id;
		$objWageEntryJob->job_id = $arr[0];
		$objWageEntryJob->job_type = $jArr[0];
		$objWageEntryJob->hour = $arr[1];
		$objWageEntryJob->save();
		
		return true;
	}
	
	private function setInputJobsValueUpdate($item_id, $jrow) {
		
		$jArr = explode(':',$jrow);
		$arr = explode(',',$jArr[1]);
		
		$objWageEntryJob = new WageEntryJob();
		$objWageEntryJob->wage_entry_items_id = $item_id;
		$objWageEntryJob->job_id = $arr[0];
		$objWageEntryJob->job_type = $jArr[0];
		$objWageEntryJob->hour = $arr[1];
		$objWageEntryJob->save();
		
		return true;
	}
	
	private function setInputJobValue($itemObj, $attributes, $key) {
		
		$objWageEntryJob = new WageEntryJob();
		$objWageEntryJob->wage_entry_items_id = $itemObj->id;
		$objWageEntryJob->job_id = $attributes['job_id'][$key];
		$objWageEntryJob->hour = $attributes['nwh'][$key];
		$objWageEntryJob->save();
		
		return true;
	}
	
	private function setInputOthersValue($attributes) {
		
		$objWageEntryOthers = new WageEntryOthers();
		$objWageEntryOthers->wage_entry_id = $this->wageentry->id;
		$objWageEntryOthers->oth_allowance1 = isset($attributes['oth_allowance1'])?$attributes['oth_allowance1']:'';
		$objWageEntryOthers->desc_allowance1 = isset($attributes['desc_allowance1'])?$attributes['desc_allowance1']:'';
		$objWageEntryOthers->oth_allowance2 = isset($attributes['oth_allowance2'])?$attributes['oth_allowance2']:'';
		$objWageEntryOthers->desc_allowance2 = isset($attributes['desc_allowance2'])?$attributes['desc_allowance2']:'';
		$objWageEntryOthers->oth_allowance3 = isset($attributes['oth_allowance3'])?$attributes['oth_allowance3']:'';
		$objWageEntryOthers->desc_allowance3 = isset($attributes['desc_allowance3'])?$attributes['desc_allowance3']:'';
		$objWageEntryOthers->oth_allowance4 = isset($attributes['oth_allowance4'])?$attributes['oth_allowance4']:'';
		$objWageEntryOthers->desc_allowance4 = isset($attributes['desc_allowance4'])?$attributes['desc_allowance4']:'';
		
		$objWageEntryOthers->oth_deduction1 = isset($attributes['oth_deduction1'])?$attributes['oth_deduction1']:'';
		$objWageEntryOthers->desc_deduction1 = isset($attributes['desc_deduction1'])?$attributes['desc_deduction1']:'';
		$objWageEntryOthers->oth_deduction2 = isset($attributes['oth_deduction2'])?$attributes['oth_deduction2']:'';
		$objWageEntryOthers->desc_deduction2 = isset($attributes['desc_deduction2'])?$attributes['desc_deduction2']:'';
		$objWageEntryOthers->oth_deduction3 = isset($attributes['oth_deduction3'])?$attributes['oth_deduction3']:'';
		$objWageEntryOthers->desc_deduction3 = isset($attributes['desc_deduction3'])?$attributes['desc_deduction3']:'';
		$objWageEntryOthers->oth_deduction4 = isset($attributes['oth_deduction4'])?$attributes['oth_deduction4']:'';
		$objWageEntryOthers->desc_deduction4 = isset($attributes['desc_deduction4'])?$attributes['desc_deduction4']:'';
		$objWageEntryOthers->save();
		
		return true;
	}
	
	private function setInputOthersValueUpdate($attributes) {
		
		$objWageEntryOthers = WageEntryOthers::find($attributes['weo_id']);
		$items['oth_allowance1'] = $attributes['oth_allowance1'];
		$items['desc_allowance1'] = $attributes['desc_allowance1'];
		$items['oth_allowance2'] = $attributes['oth_allowance2'];
		$items['desc_allowance2'] = $attributes['desc_allowance2'];
		$items['oth_allowance3'] = $attributes['oth_allowance3'];
		$items['desc_allowance3'] = $attributes['desc_allowance3'];
		$items['oth_allowance4'] = $attributes['oth_allowance4'];
		$items['desc_allowance4'] = $attributes['desc_allowance4'];
		
		$items['oth_deduction1'] = $attributes['oth_deduction1'];
		$items['desc_deduction1'] = $attributes['desc_deduction1'];
		$items['oth_deduction2'] = $attributes['oth_deduction2'];
		$items['desc_deduction2'] = $attributes['desc_deduction2'];
		$items['oth_deduction3'] = $attributes['oth_deduction3'];
		$items['desc_deduction3'] = $attributes['desc_deduction3'];
		$items['oth_deduction4'] = $attributes['oth_deduction4'];
		$items['desc_deduction4'] = $attributes['desc_deduction4'];
		$objWageEntryOthers->update($items);
		
		return true;
	}
	
	private function setInputJobValueUpdate($wei_id, $attributes, $key) {
		
		DB::table('wage_entry_job')->where('wage_entry_items_id',$wei_id)->where('job_id',$attributes['job_id'][$key])
					->update(['hour' => $attributes['nwh'][$key]]);
				
		return true;
	}
	
	public function create($attributes)
	{ //echo '<pre>';print_r($attributes);exit;
		if($this->isValid($attributes)) {
			DB::beginTransaction();
			try {
				
				if($this->setInputValue($attributes)) {
					
					$this->wageentry->net_basic = $attributes['basic_net'];
					$this->wageentry->net_hra = $attributes['hra_net'];
					$this->wageentry->net_allowance = $attributes['allowance_net'];
					$this->wageentry->net_otg = $attributes['otg_net'];
					$this->wageentry->net_oth = $attributes['oth_net'];
					$this->wageentry->deductions = $attributes['deductions'];
					$this->wageentry->net_total = $attributes['net_total'];
					$this->wageentry->othr_allowance = $attributes['othr_allowance'];
					$this->wageentry->otgs_total = $attributes['otgs_tot'];
					$this->wageentry->oths_total = $attributes['oths_tot'];
					$this->wageentry->net_oth = $attributes['oth_net'];
					$this->wageentry->wdays_total = $attributes['total_wdays'];
					$this->wageentry->status = 1;
					$this->wageentry->created_at = now();
					$this->wageentry->created_by = Auth::User()->id;
					$this->wageentry->fill($attributes)->save();
					
					if($this->wageentry->id){ // && !empty( array_filter($attributes['day']))) {
						
						$this->setInputOthersValue($attributes);
						
						//if($attributes['entry_type']=='daily') { 
						if(isset($attributes['day'])){
							foreach($attributes['day'] as $key => $value){ 
							
								$objWageEntryItem = new WageEntryItems();
								$this->setInputItemsValue($attributes, $objWageEntryItem, $key);
								$objWageEntryItem->status = 1;
								$itemObj = $this->wageentry->WageEntryItems()->save($objWageEntryItem);
								
								if(isset($attributes['job_data'][$key]) && $attributes['job_data'][$key]!='') {
										
									$jobArr = explode('|', $attributes['job_data'][$key]);
									foreach($jobArr as $jrow) {
										$this->setInputJobsValue($itemObj, $jrow);
									}
									
								} /* else {
									
									$this->setInputJobValue($itemObj, $attributes, $key);
								} */
							}
						}
						//}
					}
				}
				
				DB::commit();
				return true; 
				
			} catch(\Exception $e) { 
			
				DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
				return false;
			}
		}
	}
	
	public function update($id, $attributes)
	{
		$this->wageentry = $this->find($id);
		DB::beginTransaction();
		try {
			
			if($this->setInputValue($attributes)) {
					
				$this->wageentry->net_basic = $attributes['basic_net'];
				$this->wageentry->net_hra = $attributes['hra_net'];
				$this->wageentry->net_allowance = $attributes['allowance_net'];
				$this->wageentry->net_otg = $attributes['otg_net'];
				$this->wageentry->net_oth = $attributes['oth_net'];
				$this->wageentry->deductions = $attributes['deductions'];
				$this->wageentry->net_total = $attributes['net_total'];
				$this->wageentry->othr_allowance = $attributes['othr_allowance'];
				$this->wageentry->otgs_total = $attributes['otgs_tot'];
				$this->wageentry->oths_total = $attributes['oths_tot'];
				$this->wageentry->wdays_total = $attributes['total_wdays'];
				$this->wageentry->modify_at = now();
				$this->wageentry->modify_by = Auth::User()->id;
				$this->wageentry->fill($attributes)->save();
					
				$this->setInputOthersValueUpdate($attributes);
				
				foreach($attributes['day'] as $key => $value){ 
				
					$objWageEntryItem = WageEntryItems::find($attributes['wei_id'][$key]);
					$this->setInputItemsValueUpdate($attributes, $objWageEntryItem, $key);
					
					if(isset($attributes['job_data'][$key]) && $attributes['job_data'][$key]!='') {
							
						$jobArr = explode('|', $attributes['job_data'][$key]);
						foreach($jobArr as $jrow) {
							$this->setInputJobsValueUpdate($attributes['wei_id'][$key], $jrow);
						}
						
					} /* else {
						
						$this->setInputJobValueUpdate($attributes['wei_id'][$key], $attributes, $key);
					} */
				}
			}
			
			DB::commit();
			return true; 
			
		 } catch(\Exception $e) { 
		
			DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
		
		$this->wageentry->dob = ($attributes['dob']!='')?date('Y-m-d', strtotime($attributes['dob'])):'';
		$this->wageentry->gender = isset($attributes['gender'])?$attributes['gender']:0;
		$this->wageentry->join_date = ($attributes['join_date']!='')?date('Y-m-d', strtotime($attributes['join_date'])):'';
		$this->wageentry->pp_issue_date = ($attributes['pp_issue_date']!='')?date('Y-m-d', strtotime($attributes['pp_issue_date'])):'';
		$this->wageentry->pp_expiry_date = ($attributes['pp_expiry_date']!='')?date('Y-m-d', strtotime($attributes['pp_expiry_date'])):'';
		$this->wageentry->v_issue_date = ($attributes['v_issue_date']!='')?date('Y-m-d', strtotime($attributes['v_issue_date'])):'';
		$this->wageentry->v_expiry_date = ($attributes['v_expiry_date']!='')?date('Y-m-d', strtotime($attributes['v_expiry_date'])):''; 
		$this->wageentry->lc_issue_date = ($attributes['lc_issue_date']!='')?date('Y-m-d', strtotime($attributes['lc_issue_date'])):'';
		$this->wageentry->lc_expiry_date = ($attributes['lc_expiry_date']!='')?date('Y-m-d', strtotime($attributes['lc_expiry_date'])):'';
		$this->wageentry->hc_issue_date = ($attributes['hc_issue_date']!='')?date('Y-m-d', strtotime($attributes['hc_issue_date'])):'';
		$this->wageentry->hc_expiry_date = ($attributes['hc_expiry_date']!='')?date('Y-m-d', strtotime($attributes['hc_expiry_date'])):'';
		$this->wageentry->ic_issue_date = ($attributes['ic_issue_date']!='')?date('Y-m-d', strtotime($attributes['ic_issue_date'])):'';
		$this->wageentry->ic_expiry_date = ($attributes['ic_expiry_date']!='')?date('Y-m-d', strtotime($attributes['ic_expiry_date'])):'';
		$this->wageentry->contract_status = $attributes['contract_status'];
		$this->wageentry->contract_salary = $attributes['contract_salary'];
		$this->wageentry->basic_pay = $attributes['basic_pay'];
		$this->wageentry->hra = $attributes['hra'];
		$this->wageentry->transport = $attributes['transport'];
		$this->wageentry->allowance = $attributes['allowance'];
		$this->wageentry->allowance2 = $attributes['allowance2'];
		//$this->wageentry->payment_method = isset($attributes['payment_method'])?$attributes['payment_method']:0;
		//$this->wageentry->wage_calculation = isset($attributes['wage_calculation'])?$attributes['wage_calculation']:0;
		//$this->wageentry->ot_calculation = isset($attributes['ot_calculation'])?$attributes['ot_calculation']:0;
		$this->wageentry->remarks = $attributes['remarks'];
			$this->wageentry->duty_status = $attributes['duty_status'];
			$this->wageentry->other_info = $attributes['other_info'];
		$this->wageentry->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->wageentry = $this->wageentry->find($id);
		$this->wageentry->delete();
	}
	
	public function wageentryList()
	{
		//check admin session and apply return $this->wageentry->where('parent_id',0)->where('status', 1)->get();
		return $this->wageentry->get();
	}
	
	public function activeWageEntryList()
	{
		return $this->wageentry->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_wageentry_code($code, $id = null) {
		
		if($id)
			return $this->wageentry->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->wageentry->where('code',$code)->count();
	}
		
	public function check_wageentry_name($name, $id = null) {
		
		if($id)
			return $this->wageentry->where('name',$name)->where('id', '!=', $id)->count();
		else
			return $this->wageentry->where('name',$name)->count();
	}
	
	/* public function getWageEntrys() {
		
		return $this->wageentry->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	} */
	
	
		public function get_wage_entryts($id,$mon)
	{
		return $this->wageentry->where('wage_entry.status',1)->where('wage_entry.employee_id',$id)->where('wage_entry.month',$mon)
						->join('wage_entry_others AS WEO', function($join) {
							$join->on('WEO.wage_entry_id','=','wage_entry.id');
						} )
						->join('employee AS E', function($join) {
							$join->on('E.id','=','wage_entry.employee_id');
						} )
						->select('wage_entry.*','WEO.*','E.code','E.name','E.nwh','wage_entry.id AS weid','WEO.id AS weo_id')
						->first();
	}
	
	public function get_wage_entryts_items($id,$mon)
	{
		return $this->wageentry->where('wage_entry.status',1)->where('wage_entry.employee_id',$id)->where('wage_entry.month',$mon)
						->leftJoin('wage_entry_items AS WEI', function($join) {
							$join->on('WEI.wage_entry_id','=','wage_entry.id');
						} )
						->leftJoin('jobmaster AS J2', function($join) {
							$join->on('J2.id','=','WEI.job_id');
						} )
						->select('WEI.*','WEI.id AS wei_id','wage_entry.paid_leave','wage_entry.unpaid_leave',
								 'J2.name AS j2name','J2.code AS j2code')
						->get();
	}
	
	public function timesheetdailySearch($attributes) {
		
		$query=DB::table('timesheet_entry AS TSE')->where('TSE.employee_id',$attributes['employee_id'])
		            ->join('employee AS E','E.id','=','TSE.employee_id')
					->leftjoin('jobmaster AS JM','JM.id','=','TSE.job_id');
				$date=	date('Y-m-d', strtotime($attributes['date']));
		    if($attributes['month']!='')  {    
				   $query->where('TSE.month',$attributes['month']);
			 }
			
			 if($attributes['date'] !=''){
				$query->where('TSE.date',$date);

			 }
			 return $query->select('TSE.*','E.name','JM.name AS job_name','JM.code AS job_code')->get();
	} 

	public function timesheetmonthlySearch($attributes) {
		
		$query=DB::table('timesheet_entry AS TSE')->where('TSE.employee_id',$attributes['employee_id'])
		            ->join('employee AS E','E.id','=','TSE.employee_id')
					->leftjoin('jobmaster AS JM','JM.id','=','TSE.job_id');
				$date=	date('Y-m-d', strtotime($attributes['date']));
		    if($attributes['month']!='')  {    
				   $query->where('TSE.month',$attributes['month']);
			 }
			
			 
			 return $query->select('TSE.*','E.name','JM.name AS job_name','JM.code AS job_code')->get();
	} 
	
	
		public function timesheetEntry($eid,$cid,$mon) {
		
		$query=DB::table('timesheet_entry AS TSE')
		            ->join('employee AS E','E.id','=','TSE.employee_id')
					->leftjoin('jobmaster AS JM','JM.id','=','TSE.job_id');
		     $date=date('Y-m-d', strtotime($mon))  ;                
		     if($mon!=0)  {    
				   $query->where('TSE.date',$date);
			 }
			 if($eid!=0)  {
				$query->where('TSE.employee_id',$eid);
			 }
			 if($cid !=0){
				$query->where('E.category_id',$cid);

			 }
			 return $query->select('TSE.*','E.name','JM.name AS job_name')->get();
	} 
	
	
	public function timesheetSearch($eid,$cid,$mon) {
		
		$query=DB::table('timesheet_entry AS TSE')->where('TSE.is_approved', 0)
		            ->join('employee AS E','E.id','=','TSE.employee_id')
					->leftjoin('jobmaster AS JM','JM.id','=','TSE.job_id');
		    $date=date('Y-m-d', strtotime($mon))  ;                    
		     if($mon!=0)  {    
				   $query->where('TSE.date',$date);
			 }
			 if($eid!=0)  {
				$query->where('TSE.employee_id',$eid);
			 }
			 if($cid !=0){
				$query->where('E.category_id',$cid);

			 }
			 return $query->select('TSE.*','E.name','JM.name AS job_name')->get();
	} 
	
	public function timeLeaveSearch($eid,$cid,$mon) {
		
		$query=DB::table('timesheet_entry AS TSE')->where('TSE.leave_type',0)
		            ->join('employee AS E','E.id','=','TSE.employee_id')
					->leftjoin('employee_category AS EC','EC.id','=','E.category_id');
			
		    if($mon!=0)  {    
						$query->where('TSE.month',$mon);
				  }		
		      
			if($eid!=0)  {
				$query->where('TSE.employee_id',$eid);
			 }
			 if($cid !=0){
				$query->where('E.category_id',$cid);

			 }
			 return $query->select('TSE.id','TSE.date','E.code','E.name','E.designation','EC.category_name')->get();
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
				Intervention\Image\Facades\Image::make($file->getRealPath())->resize($this->width, $this->height, function($constraint) { $constraint->aspectRatio(); })->save($destinationPath);

				// thumb
				Intervention\Image\Facades\Image::make($file->getRealPath())->resize($this->thumbWidth, $this->thumbHeight, function($constraint) { $constraint->aspectRatio(); })->save($destinationPathThumb);
			} else {
				 $photo = rand(1, 999).$fname.'.'.$ext;
				 $destinationPath = public_path() . $this->imgDir;
				 $file->move($destinationPath,$photo);
			}
		}
		
		return $photo;

	}
	
	
	
	

	
	public function getPayslip($attributes) {
		
		return $this->wageentry->where('wage_entry.employee_id', $attributes['employee_id'])
								->leftJoin('wage_entry_others AS WEO', function($join) {
									$join->on('WEO.wage_entry_id','=','wage_entry.id');
								} )
								->where('wage_entry.month', $attributes['month'])
								->where('wage_entry.year', $attributes['year'])
								->where('wage_entry.status',1)
								->select('wage_entry.*','WEO.*')
								->first();
	}
	
	public function getPayslipMonth($id,$month,$year) {
		
		return $this->wageentry->where('wage_entry.employee_id', $id)
								->leftJoin('wage_entry_others AS WEO', function($join) {
									$join->on('WEO.wage_entry_id','=','wage_entry.id');
								} )
								->where('wage_entry.month', $month)
								->where('wage_entry.year', $year)
								->where('wage_entry.status',1)
								->select('wage_entry.*','WEO.*')
								->first();
	}
	
	public function getPayslip_summery($attributes) {
		
		return $this->wageentry->where('wage_entry.employee_id', $attributes['employee_id'])
								->join('wage_entry_items AS WEI', function($join) {
									$join->on('WEI.wage_entry_id','=','wage_entry.id');
								} )
								->where('wage_entry.month', $attributes['month'])
								->where('wage_entry.year', $attributes['year'])
								->where('wage_entry.status',1)
								->select('wage_entry.*','WEI.*')
								->get();
	}
	
	public function get_jobwise_summery($attributes)
	{
		return DB::table('wage_entry_items AS WEI')
						->join('jobmaster AS jobmaster', function($join) {
							$join->on('jobmaster.id', '=', 'WEI.job_id');
						})
						->where('jobmaster.status',1)
						->where('jobmaster.is_salary_job',0)
						->where('jobmaster.deleted_at','0000-00-00 00:00:00')
						->where('WEI.deleted_at','0000-00-00 00:00:00')
						->select('jobmaster.id AS job_id','jobmaster.code','jobmaster.name',
								 'WEI.wage','WEI.allowance')
						->get();
	}
	
	public function get_jobwise_summery2($attributes)
	{
		return DB::table('wage_entry_items AS WEI')
						->join('wage_entry_job AS WEJ', function($join) {
							$join->on('WEJ.wage_entry_items_id', '=', 'WEI.id');
						})
						->join('jobmaster AS jobmaster', function($join) {
							$join->on('jobmaster.id', '=', 'WEJ.job_id');
						})
						->where('jobmaster.status',1)
						->where('jobmaster.is_salary_job',0)
						->where('jobmaster.deleted_at','0000-00-00 00:00:00')
						->where('WEI.deleted_at','0000-00-00 00:00:00')
						->select('jobmaster.id AS job_id','jobmaster.code','jobmaster.name',
								 'WEJ.*','WEI.wage','WEI.allowance')
						->get();
	}
	
	public function payslip_summery($attributes)
	{
		$qry = DB::table('employee AS E')
						->join('wage_entry AS WE', function($join) {
							$join->on('WE.employee_id', '=', 'E.id');
						})
						->leftJoin('wage_entry_others AS WEO', function($join) {
							$join->on('WEO.wage_entry_id', '=', 'WE.id');
						})
						->where('E.status',1)
						->where('E.deleted_at','0000-00-00 00:00:00')
						->where('WE.status',1)
						->where('WE.deleted_at','0000-00-00 00:00:00')
						->where('WE.month', $attributes['month'])
						->where('WE.year', $attributes['year']);
					if($attributes['employee_id']!='')
						$qry->where('E.id',$attributes['employee_id']);
					
				return $qry->select('E.name','E.code','WE.*','WEO.*')
						->get();
	}
	
	public function get_attendance($attributes)
	{
		$qry = DB::table('employee AS E')
						->join('wage_entry AS WE', function($join) {
							$join->on('WE.employee_id', '=', 'E.id');
						})
						->leftJoin('wage_entry_items AS WEI', function($join) {
							$join->on('WEI.wage_entry_id', '=', 'WE.id');
						})
						->where('WE.entry_type','daily')
						->where('E.status',1)
						->where('E.deleted_at','0000-00-00 00:00:00')
						->where('WE.status',1)
						->where('WE.deleted_at','0000-00-00 00:00:00')
						->where('WE.month', $attributes['month'])
						->where('WE.year', $attributes['year']);
					if($attributes['employee_id']!='')
						$qry->where('E.id',$attributes['employee_id']);
					
				return $qry->select('E.id','E.name','E.code','WE.month','WE.year','WEI.otg','WEI.leave_status','WEI.leave_type')
						->get();
	}
	
	public function get_jobwise($attributes)
	{
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$qry = DB::table('wage_entry AS WE')
						->join('wage_entry_items AS WEI', function($join) {
							$join->on('WEI.wage_entry_id', '=', 'WE.id');
						})
						->join('jobmaster AS jobmaster', function($join) {
							$join->on('jobmaster.id', '=', 'WEI.job_id');
						})
						->join('employee AS E', function($join) {
							$join->on('E.id', '=', 'WE.employee_id');
						})
						->where('jobmaster.is_salary_job',0)
						->where('jobmaster.status',1)
						->where('jobmaster.deleted_at','0000-00-00 00:00:00')
						->where('WEI.deleted_at','0000-00-00 00:00:00');
						
					if($attributes['job_id']!='')
						$qry->where('jobmaster.id',$attributes['job_id']);
					
					if($date_from!='' && $date_to!='') {
						$qry->whereBetween('WE.created_at',[$date_from, $date_to]);
					}
						
		return $qry->select('jobmaster.id AS job_id','jobmaster.code AS job_code','jobmaster.name AS job_name','E.code','E.name','WEI.wage','WEI.otg_wage','WEI.job_date',
							'WEI.wage','WEI.allowance','WEI.day','WEI.nwh','WEI.otg','WEI.oth','WE.month','WE.year','jobmaster.is_salary_job','WEI.oth_wage')
						->get();
	}
	
	public function get_jobwise2($attributes)
	{
		return DB::table('wage_entry AS WE')
						->join('wage_entry_items AS WEI', function($join) {
							$join->on('WEI.wage_entry_id', '=', 'WE.id');
						})
						->join('wage_entry_job AS WEJ', function($join) {
							$join->on('WEJ.wage_entry_items_id', '=', 'WEI.id');
						})
						->join('jobmaster AS jobmaster', function($join) {
							$join->on('jobmaster.id', '=', 'WEJ.job_id');
						})
						->join('employee AS E', function($join) {
							$join->on('E.id', '=', 'WE.employee_id');
						})
						->where('jobmaster.id',$attributes['job_id'])
						->where('jobmaster.status',1)
						->where('jobmaster.is_salary_job',0)
						->where('jobmaster.deleted_at','0000-00-00 00:00:00')
						->where('WEI.deleted_at','0000-00-00 00:00:00')
						->select('jobmaster.id AS job_id','jobmaster.code AS job_code','jobmaster.name AS job_name','E.code','E.name',
								 'WEJ.*','WEI.wage','WEI.allowance','WEI.day','WE.month','WE.year')
						->get();
	}
	
	public function get_wage_entry($id)
	{
		return $this->wageentry->where('wage_entry.status',1)->where('wage_entry.id',$id)
						->join('wage_entry_others AS WEO', function($join) {
							$join->on('WEO.wage_entry_id','=','wage_entry.id');
						} )
						->join('employee AS E', function($join) {
							$join->on('E.id','=','wage_entry.employee_id');
						} )
						->select('wage_entry.*','WEO.*','E.code','E.name','E.nwh','wage_entry.id AS weid','WEO.id AS weo_id')
						->first();
	}
	
	public function get_wage_entry_items($id)
	{
		return $this->wageentry->where('wage_entry.status',1)->where('wage_entry.id',$id)
						->leftJoin('wage_entry_items AS WEI', function($join) {
							$join->on('WEI.wage_entry_id','=','wage_entry.id');
						} )
						->leftJoin('jobmaster AS J2', function($join) {
							$join->on('J2.id','=','WEI.job_id');
						} )
						->select('WEI.*','WEI.id AS wei_id','wage_entry.paid_leave','wage_entry.unpaid_leave',
								 'J2.name AS j2name','J2.code AS j2code')
						->get();
	}
}

