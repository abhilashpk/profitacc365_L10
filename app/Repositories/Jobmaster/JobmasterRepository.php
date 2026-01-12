<?php
declare(strict_types=1);
namespace App\Repositories\Jobmaster;

use App\Models\Jobmaster;
use App\Models\Budgeting;
use App\Models\ProjectBudget;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class JobmasterRepository extends AbstractValidator implements JobmasterInterface {
	
	protected $jobmaster;
	protected $budgeting;
	protected $project_budget;
	protected static $rules = [

	];
	
	public function __construct(Jobmaster $jobmaster,Budgeting $budgeting,ProjectBudget $project_budget) {
		$this->jobmaster = $jobmaster;
		$this->budgeting = $budgeting;
		$this->project_budget = $project_budget;
	}
	
	public function all()
	{
		return $this->jobmaster->get();
	}
	
	public function find($id)
	{
		return $this->jobmaster->where('jobmaster.id', $id)
					->leftJoin('account_master AS am', function($join) {
							$join->on('am.id','=','jobmaster.customer_id');
						} )
					->leftJoin('salesman AS sm', function($join) {
							$join->on('sm.id','=','jobmaster.salesman_id');
						} )
					->select('jobmaster.*','am.master_name','sm.name AS salesman')
					->first();
	}

	public function create($attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		
		if($this->isValid($attributes)) { 
			$this->jobmaster->code = $attributes['code'];
			$this->jobmaster->name = $attributes['jobname'];
			$this->jobmaster->open_cost = $attributes['open_cost'];
			$this->jobmaster->customer_id = $attributes['customer_id'];
			$this->jobmaster->department_id = $attributes['department_id'];
			$this->jobmaster->salesman_id = $attributes['salesman_id'];
			$this->jobmaster->open_income = $attributes['open_income'];
			$this->jobmaster->is_close = $attributes['job_close'];
			//NEW FIELDS

			$this->jobmaster->transport_type = $attributes['transport_type'];
			$this->jobmaster->packing = $attributes['packing'];
			$this->jobmaster->date = date('Y-m-d', strtotime( $attributes['date']));
			$this->jobmaster->address = $attributes['address'];
			$this->jobmaster->mbl = $attributes['mbl'];
			$this->jobmaster->house_bl_no = $attributes['house_bl_no'];
			$this->jobmaster->origin = $attributes['origin'];
			$this->jobmaster->hbl = $attributes['hbl'];
			$this->jobmaster->por = $attributes['por'];
			$this->jobmaster->fnd = $attributes['fnd'];
			$this->jobmaster->no_of_pieces = $attributes['no_of_pieces'];
			$this->jobmaster->volume = $attributes['volume'];
			$this->jobmaster->gross_weight = $attributes['gross_weight'];
			$this->jobmaster->destination = $attributes['destination'];
			$this->jobmaster->flight_no = $attributes['flight_no'];
			$this->jobmaster->chargeable_weight = $attributes['chargeable_weight'];
			$this->jobmaster->be_no = $attributes['be_no'];
			$this->jobmaster->flight_date = $attributes['flight_date'];
			$this->jobmaster->container_no = $attributes['container_no'];
            $this->jobmaster->shipper = $attributes['shipper']; 
            $this->jobmaster->consignee= $attributes['consignee']; 

			///END
			$this->jobmaster->contract_amount = $attributes['contract_amount'];
			$this->jobmaster->start_date = ($attributes['start_date']!='')?date('Y-m-d', strtotime($attributes['start_date'])):'';
			$this->jobmaster->end_date = ($attributes['start_date']!='')?date('Y-m-d', strtotime($attributes['end_date'])):'';
			$this->jobmaster->status = 1;
			$this->jobmaster->vehicle_id = isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'';
			$document_type = isset($attributes['document_type'])?$attributes['document_type']:''; 
			
			$this->jobmaster->fill($attributes)->save();

			if($this->jobmaster->id) {
					DB::table('jobtype')->where('id', $attributes['transport_type'])->update(['job_no' => DB::raw('job_no + 1')]);
						 /* DB::table('voucher_no')
							->where('voucher_type', 'JM')
							->update(['no' => $attributes['voucher_no'] + 1]); */
			}
			$id=$this->jobmaster->id;
			return(['id'=>$id,'document_type'=>$document_type]);

		}
		
		//throw new ValidationException('jobmaster validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->jobmaster = $this->find($id);
		$this->jobmaster->name = $attributes['jobname'];
		$this->jobmaster->open_cost = $attributes['open_cost'];
		$this->jobmaster->customer_id = $attributes['customer_id'];
		$this->jobmaster->department_id = $attributes['department_id'];
		$this->jobmaster->salesman_id = $attributes['salesman_id'];
		$this->jobmaster->open_income = $attributes['open_income'];
		$this->jobmaster->is_close = $attributes['job_close'];
		$this->jobmaster->contract_amount = $attributes['contract_amount'];
		$this->jobmaster->start_date = ($attributes['start_date']!='')?date('Y-m-d', strtotime($attributes['start_date'])):'';
		$this->jobmaster->end_date = ($attributes['start_date']!='')?date('Y-m-d', strtotime($attributes['end_date'])):'';
		$this->jobmaster->vehicle_id = isset($attributes['vehicle_id'])?$attributes['vehicle_id']:'';

		//NEW FIELDS

			
			$this->jobmaster->transport_type = isset($attributes['transport_type'])?$attributes['transport_type']:'';  
		    $this->jobmaster->packing = $attributes['packing'];
			$this->jobmaster->date = $attributes['date'];
			$this->jobmaster->address = $attributes['address'];
			$this->jobmaster->mbl = $attributes['mbl'];
			$this->jobmaster->house_bl_no = $attributes['house_bl_no'];
			$this->jobmaster->origin = $attributes['origin'];
			$this->jobmaster->hbl = $attributes['hbl'];
			$this->jobmaster->por = $attributes['por'];
			$this->jobmaster->fnd = $attributes['fnd'];
			$this->jobmaster->no_of_pieces = $attributes['no_of_pieces'];
			$this->jobmaster->volume = $attributes['volume'];
			$this->jobmaster->gross_weight = $attributes['gross_weight'];
			$this->jobmaster->destination = $attributes['destination'];
			$this->jobmaster->flight_no = $attributes['flight_no'];
			$this->jobmaster->chargeable_weight = $attributes['chargeable_weight'];
			$this->jobmaster->be_no = $attributes['be_no'];
			$this->jobmaster->flight_date = $attributes['flight_date'];
			$this->jobmaster->container_no = $attributes['container_no'];
			$this->jobmaster->shipper = $attributes['shipper']; 
            $this->jobmaster->consignee= $attributes['consignee'];
            $document_type = $attributes['document_type'];
			$cid = $attributes['customer_id'];

			///END
		
		$this->jobmaster->fill($attributes)->save();
		$id=$this->jobmaster->id;
		return(['id'=>$id,'cid'=>$cid,'document_type'=>$document_type]);
	}
	

	private function setInputValuebud($attributes)
	{
		$this->budgeting->total = $attributes['total']; 
		
	//	$this->budgeting->modified_at = ($attributes['modified_at']=='')?date('Y-m-d'):date('Y-m-d', strtotime($attributes['modified_at']));
	
		$this->budgeting->job_id = $attributes['job_id'];

		
		return true;
	}
	

	private function setInputValuebudget($attributes, $projectbudget, $key, $value,$total, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
	

	
//	echo '<pre>';print_r( $this->budgeting->id);exit;
		$vatPlus = 100;
	//	$total = $attributes['total'][$key];
	//	$line_total = ($attributes['amount'][$key]);
		
		$line_total = ($attributes['cost'][$key] * 1);
		$projectbudget->budgeting_id = $this->budgeting->id;
		$projectbudget->ac_id = $attributes['account_id'][$key];

		$projectbudget->description = $attributes['item_description'][$key];
		
		$projectbudget->amount = $line_total;
		
		
	
		
		return array('line_total' => $line_total);
		
	
		return true;
	}
	
	
	
	
	
		private function setInputValuebudgetinc($attributes, $projectbudget, $key, $value,$total, $lineTotal, $total_quantity=null)
	{
		$othercost_unit = $netcost_unit = 0;
	

	
//	echo '<pre>';print_r( $this->budgeting->id);exit;
		$vatPlus = 100;
	//	$total = $attributes['total'][$key];
	//	$line_total = ($attributes['amount'][$key]);
		
		$line_total = ($attributes['costinc'][$key] * 1);
		$projectbudget->budgeting_id = $this->budgeting->id;
		$projectbudget->ac_id = $attributes['account_idinc'][$key];
    $projectbudget->is_log = 1;
		$projectbudget->description = $attributes['item_descriptioninc'][$key];
		
		$projectbudget->amount = $line_total;
		
		
	
		
		return array('line_total' => $line_total);
		
	
		return true;
	}
	
	
	
	
		public function findbudget($id)
	{
		$result = DB::table('project_budget')->where('project_budget.budgeting_id', $id)
						->join('account_master', 'account_master.id', '=', 'project_budget.ac_id')
						
						->where('project_budget.is_log', 0)
						->select('project_budget.*','account_master.master_name','account_master.category')
						->get();
	//echo '<pre>';print_r($result);exit;					
	return $result;
	}
		public function findJEdata($id)
	{
		$result = DB::table('project_budget')->where('project_budget.budgeting_id', $id)
						->join('account_master', 'account_master.id', '=', 'project_budget.ac_id')
						
						->where('project_budget.is_log', 1)
						->select('project_budget.*','account_master.master_name','account_master.category')
						->get();
	//echo '<pre>';print_r($result);exit;					
	return $result;
	}
		public function findJEcostdata($id)
	{
		$result = DB::table('project_budget')->where('project_budget.budgeting_id', $id)
						->join('account_master', 'account_master.id', '=', 'project_budget.ac_id')
						
						->where('project_budget.is_log', 0)
						->select('project_budget.*','account_master.master_name','account_master.category')
						->get();
	//echo '<pre>';print_r($result);exit;					
	return $result;
	}
	
		
		public function findbud($id)
	{
		return $this->budgeting->where('id', $id)->first();
	}
	

	
	public function buddelete($id)
	{
		$this->budgeting =$this->findbud($id);
		
		DB::beginTransaction();
		try {
			
		DB::table('project_budget')->where('budgeting_id', $id)->update(['deleted_at' => now()]);
		
			
		//	DB::table('budgeting')->where('id', $id)->update(['deleted_at' => now() ]);
			$this->budgeting->delete();
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			DB::rollback();
			return false;
		}
	}
	
	
	
	public function updatebudget($id, $attributes)
	{ 	
	 //   echo '<pre>';print_r($attributes);exit;
		
		
		
		$this->budgeting = $this->findbud($id);
		//echo '<pre>';print_r($this->budgeting->id);exit;
		DB::beginTransaction();
		try {
			
			if($this->budgeting->id && !empty( array_filter($attributes['account_id']))) {
				
				$line_total = $tax_total = 0; $cost_value = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = 0;
				$item_total = 0;
				
			
				//calculate total amount.... linetotal taxtotal
			
				foreach($attributes['account_id'] as $key => $value) { 
					
					if($attributes['order_item_id'][$key]!='') {
						
				
					//	echo '<pre>';print_r($attributes);exit;
						$total = $attributes['line_total'][$key];
					
						$projectbudget = ProjectBudget::find($attributes['order_item_id'][$key]);
						$oldqty = $projectbudget->quantity;
						$items['description'] = $attributes['item_description'][$key];
						$items['ac_id'] = $value;
					
						$items['amount'] = $attributes['line_total'][$key];
						
					
						$projectbudget->update($items);
						

											
					} else { 
						//new entry...
						$item_total_new = $tax_total_new = $item_total_new = $total = 0;
					
						$projectbudget = new ProjectBudget();
						$arrResult 		= $this->setInputValuebudget($attributes, $projectbudget, $key, $value, $total, $id);
						if($arrResult['line_total']) {
							$line_total_new			     += $arrResult['line_total'];
						
							
							$line_total			     += $arrResult['line_total'];
						
							//$taxtype				  = $arrResult['type'];
							
							$projectbudget->status = 1;
							$inv_item = $this->budgeting->doItem()->save($projectbudget);
						//	echo '<pre>';print_r($arrResult);exit;
						
						}
											
					}
					
				}
			}
			
			//UPDATED MAR 1...
			//manage removed items...
			if($attributes['remove_item']!='')
			{
			    
			    
			    	$arrids = explode(',', $attributes['remove_item']);
			
				foreach($arrids as $row) {
				
					DB::table('project_budget')->where('id', $row)->update(['status' => 1, 'deleted_at' => now()]);
					
				}
			
			}
			
			
			
				//project cost
			
			
			
			
			
				if($this->budgeting->id && !empty( array_filter($attributes['account_idinc']))) {
				
				$line_total = $tax_total = 0; $cost_value = 0; $total_quantity = $line_total_new = $tax_total_new = $cost_sum = 0;
				$item_total = 0;
				
			
				//calculate total amount.... linetotal taxtotal
			
				foreach($attributes['account_idinc'] as $key => $value) { 
					
					if($attributes['order_item_idinc'][$key]!='') {
						
				
					//	echo '<pre>';print_r($attributes);exit;
						$total = $attributes['line_totalinc'][$key];
					
						$projectbudget = ProjectBudget::find($attributes['order_item_idinc'][$key]);
						$oldqty = $projectbudget->quantity;
						$items['description'] = $attributes['item_descriptioninc'][$key];
						$items['ac_id'] = $value;
					
						$items['amount'] = $attributes['line_totalinc'][$key];
						
					
						$projectbudget->update($items);
						

											
					} else { 
						//new entry...
						$item_total_new = $tax_total_new = $item_total_new = $total = 0;
					
						$projectbudget = new ProjectBudget();
						$arrResult 		= $this->setInputValuebudgetinc($attributes, $projectbudget, $key, $value, $total, $id);
						if($arrResult['line_total']) {
							$line_total_new			     += $arrResult['line_total'];
						
							
							$line_total			     += $arrResult['line_total'];
						
							//$taxtype				  = $arrResult['type'];
							
							$projectbudget->status = 1;
							$inv_item = $this->budgeting->doItem()->save($projectbudget);
						//	echo '<pre>';print_r($arrResult);exit;
						
						}
											
					}
					
				}
			}
			
			//UPDATED MAR 1...
			//manage removed items...
			if($attributes['remove_iteminc']!='')
			{
			    
			    
			    	$arrids = explode(',', $attributes['remove_iteminc']);
			
				foreach($arrids as $row) {
				
					DB::table('project_budget')->where('id', $row)->update(['status' => 1, 'deleted_at' => now()]);
					
				}
			
			}
			
			
			
			
			if($this->setInputValuebud($attributes)) {
				
			//	$this->budgeting->modify_at = now();
			//	$this->budgeting->modify_by = 1;
				$this->budgeting->fill($attributes)->save();
				
			}
			
			
			//update discount, total amount
			DB::table('budgeting')
						->where('id', $this->budgeting->id)
						->update([
						    'total'    	  => $attributes['total'],
						
						'total_cost'    	  => $attributes['totalinc'],
						'total_income'    	  => $attributes['total'],
								   ]); //CHG
			
			//check whether Cost Accounting method or not.....
			
			DB::commit();
			return true;
			
		} catch (\Exception $e) {
			 DB::rollback(); echo $e->getLine().'-'.$e->getMessage().' '.$e->getFile();exit;
			return false;
		}
	}
	
	public function findPOdata($id)
	{
		$query = DB::table('jobmaster')->where('jobmaster.status', 1);
		return $query->join('budgeting AS am', function($join) {
							$join->on('am.job_id','=','jobmaster.id');
						} )->where('am.id', $id)
						
					->select('jobmaster.code','jobmaster.name','am.total_cost AS total_cost','am.total_income AS total_income','am.id AS id','am.job_id','jobmaster.name AS jobname')
					->orderBY('am.id', 'ASC')
					->first();
	}
	
	public function getItems($id)
	{
		$query = DB::table('budgeting')->where('budgeting.id',$id);
		
		return $query->join('project_budget AS PSI', function($join) {
							$join->on('PSI.budgeting_id','=','budgeting.id');
						} )
					  ->join('account_master AS AM', function($join){
						  $join->on('AM.id','=','PSI.ac_id');
					  })
					  ->leftJoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','budgeting.job_id');
					  })
					  	->where('PSI.is_log', 0)
					  ->where('PSI.deleted_at','0000-00-00 00:00:00')
					  ->select('PSI.*','AM.account_id AS account_code','budgeting.total','AM.master_name','J.code AS jobcode','J.transport_type','PSI.ac_id AS account_id','PSI.description AS item_description','PSI.amount AS item_total','PSI.amount AS unit_price')
					  ->orderBY('PSI.id')
					  ->groupBY('PSI.id')
					  ->get();
	}
	public function getItemsinc($id)
	{
		$query = DB::table('budgeting')->where('budgeting.id',$id);
		
		return $query->join('project_budget AS PSI', function($join) {
							$join->on('PSI.budgeting_id','=','budgeting.id');
						} )
					  ->join('account_master AS AM', function($join){
						  $join->on('AM.id','=','PSI.ac_id');
					  })
					  ->leftJoin('jobmaster AS J', function($join){
						  $join->on('J.id','=','budgeting.job_id');
					  })
					  	->where('PSI.is_log',1)
					  ->where('PSI.deleted_at','0000-00-00 00:00:00')
					  ->select('PSI.*','AM.account_id AS account_code','budgeting.total','AM.master_name','J.code AS jobcode','J.transport_type','PSI.ac_id AS account_id','PSI.description AS item_description','PSI.amount AS item_total','PSI.amount AS unit_price')
					  ->orderBY('PSI.id')
					  ->groupBY('PSI.id')
					  ->get();
	}

public function budgetcreate($attributes)
	{
	
	$grandtotal =0;
//	echo '<pre>';print_r($attributes);exit;
	$job_id = isset($attributes['jobmaster_id']);
	//echo '<pre>';print_r($job_id);exit;
	$grandtotal = $attributes['total'] + $attributes['totalinc'];
		$budget_id=DB::table('budgeting')
		->insertGetId([
									
										     'job_id' => $job_id,
												'total'	=>$grandtotal ,
												 'total_cost'	=> $attributes['totalinc'],
												  'total_income'	=> $attributes['total'],
											'created_at' => now(),
											
										]);
									//	$blogs[0]->title

if(!empty( array_filter($attributes['account_id']))) { 
								//	echo '<pre>';print_r($budget_id);exit;
	     foreach($attributes['account_id'] as $key => $value) { 
			$line_total = ($attributes['line_total'][$key] );
			$total = +$line_total;
			DB::table('project_budget')
					 ->insert([
									
		 						'ac_id' => $value,
		 						'amount'	=>$line_total ,
		 						'budgeting_id' => $budget_id,
		 						'description' => $attributes['item_description'][$key] ,
								'created_at' => now(),
											
										]);

		}
}
	if(!empty( array_filter($attributes['accountinc_id']))) {

		foreach($attributes['accountinc_id'] as $key => $value) { 
			$line_total = ($attributes['lineinc_total'][$key] );
			$total = +$line_total;
			DB::table('project_budget')
					 ->insert([
									
		 						'ac_id' => $value,
		 						'amount'	=>$line_total ,
		 						'budgeting_id' => $budget_id,
		 						'description' => $attributes['iteminc_description'][$key] ,
		 						'is_log' => 1,
								'created_at' => now(),
											
										]);

		}

	}

	
	//	echo '<pre>';print_r($total);exit;

	}
	
	public function delete($id)
	{
		$this->jobmaster = $this->jobmaster->find($id);
		$this->jobmaster->delete();
	}
	
	public function jobmasterListCount()
	{
		$query = $this->jobmaster->where('jobmaster.status',1)->where('jobmaster.is_salary_job',0);
		return $query->leftJoin('account_master AS am', function($join) {
							$join->on('am.id','=','jobmaster.customer_id');
						} )
					->count();
	}
	
	public function jobmasterList($type,$start,$limit,$order,$dir,$search)
	{
		$query = $this->jobmaster->where('jobmaster.status', 1)
								->leftJoin('account_master AS AC', function($join) {
									$join->on('AC.id','=','jobmaster.customer_id');
								} )
								->where('jobmaster.status',1)->where('jobmaster.is_salary_job',0);
								
				if($search) {
					$query->where('jobmaster.code','LIKE',"%{$search}%")
                          ->orWhere('AC.master_name', 'LIKE',"%{$search}%")
						   ->orWhere('jobmaster.name', 'LIKE',"%{$search}%");
				}

						$query->select('jobmaster.id','jobmaster.code','jobmaster.name','AC.master_name','jobmaster.date')
								->offset($start)
								->limit($limit)
								->orderBy($order,$dir)->groupBy('jobmaster.code');
					
							if($type=='get')
								return $query->get();
							else
								return $query->count();
								
	}
	
	public function jobmasterList2()
	{
		//check admin session and apply return $this->jobmaster->where('parent_id',0)->where('status', 1)->get();
		return DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','jobmaster.customer_id');
								} )
								->orderBy('code','ASC')
								->select('jobmaster.id','jobmaster.code','jobmaster.name','AC.master_name')
								->get();
								
		//return $this->jobmaster->orderBy('code','ASC')->get();
	}
	
	public function activeJobmasterList()
	{
		return $this->jobmaster->leftjoin('account_master AS AM', function($join) {
									$join->on('AM.id','=','jobmaster.customer_id');
								} )
		                     ->select('jobmaster.id','jobmaster.name','jobmaster.code','jobmaster.transport_type','AM.master_name')
		                     ->where('jobmaster.status', 1)
		                     ->where('jobmaster.is_salary_job',0)
		                     //->where('jobmaster.deleted_at', '0000-00-00 00:00:00')
		                      ->orderBy('name', 'ASC')->groupBy('jobmaster.code')->get()->toArray();
	}
	
	public function getOpenJobs()
	{
		return $this->jobmaster->select('id','name','code')->where('status', 1)->where('is_close',0)->where('is_salary_job',0)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_jobmaster_code($code, $id = null) {
		
		if($id)
			return $this->jobmaster->where('code',$code)->where('id', '!=', $id)->where('status',1)->count();
		else
			return $this->jobmaster->where('code',$code)->where('status',1)->count();
	}
		
	public function check_jobmaster_name($name, $id = null) {
		
		if($id)
			return $this->jobmaster->where('name',$name)->where('id', '!=', $id)->where('status',1)->count();
		else
			return $this->jobmaster->where('name',$name)->where('status',1)->count();
	}
	public function findhistory()
	{
	return $query = DB::table('jobmaster')->where('jobmaster.status', 1)
		->join('sales_invoice AS SI', function($join) {
				$join->on('SI.job_id','=','jobmaster.id');
			} )
		->join('sales_invoice_item AS SIM', function($join) {
				$join->on('SIM.sales_invoice_id','=','SI.id');
			} )
			->join('account_master AS am', function($join) {
				$join->on('am.id','=','SI.customer_id');
			} )->leftJoin('sales_order AS SO', function($join) {
				$join->on('SO.id','=','SI.document_id');
			} )
			->leftJoin('vehicle AS V', function($join) {
				$join->on('V.id','=','SI.vehicle_id');
			} )->select('SO.next_due','SO.present_km','SO.service_km','SO.next_km','am.master_name AS customer',
			'V.name AS vehicle','V.reg_no','V.model','V.make','V.issue_plate','V.code_plate','SO.voucher_no AS doc_no')
			->where('SI.vehicle_id','!=','')
   ->get();
	

					}
	public function getJobReportvehi($attributes)
	{
		//echo '<pre>';print_r($attributes);exit;
		$is_workshopsplit = (isset($attributes['is_workshopsplit']))?true:false; 
		if($is_workshopsplit) {
		switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					

					//purchase split

					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				//	if($job_id)
						//$qry1->where('jobmaster.id', $job_id);
						
						
				if(isset($attributes['job_id']) && $attributes['job_id']!='')
	                $qry1->whereIn('jobmaster.id', $attributes['job_id']);	
 
	
	
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.*','PS.net_amount AS amount','jobmaster.incexp AS income','AM.master_name AS customer');
					
					
					//sales split

					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.*',DB::raw('"0" AS amount'),'PS.net_amount AS income','AM.master_name AS customer');
					$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('sales_invoice_item AS SIM', function($join) {
										$join->on('SIM.sales_invoice_id','=','SI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
							
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry16->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry16->select('jobmaster.*','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.less_description AS vehiclemodel',DB::raw('"SI" AS type'),
								'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake');
							
				$results = $qry1->union($qry2)->union($qry16)->get();
				//echo '<pre>';print_r($results);exit;	
				case 'detail': //journal
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				   $job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
				
					
		$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('sales_invoice_item AS SIM', function($join) {
										$join->on('SIM.sales_invoice_id','=','SI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
							
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry16->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry16->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.less_description AS vehiclemodel',DB::raw('"SI" AS type'),
								'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake');
									
				$results = $qry16->orderBy('type','ASC')->get();	
				
								//->groupBy('PS.voucher_no')
			}
		}
		return $results;
	}
	
	
	public function getJobReport($attributes)
	{
		$is_jobsplit = (isset($attributes['is_jobsplit']))?true:false;

	  $is_workshopsplit = (isset($attributes['is_workshopsplit']))?true:false;
        
        
		if($is_workshopsplit) 
		{
			switch($attributes['search_type']) 
			{
			//echo '<pre>';print_r($attributes);exit;	is_workshopsplit
			case 'summary':
				$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
				$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
				

				//purchase split

				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchase_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
					
					
			if(isset($attributes['job_id']) && $attributes['job_id']!='')
				$qry1->whereIn('jobmaster.id', $attributes['job_id']);	

				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.*','PS.net_amount AS amount','jobmaster.incexp AS income','AM.master_name AS customer');
				
				
				//sales split

				$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.*',DB::raw('"0" AS amount'),'PS.net_amount AS income','AM.master_name AS customer');
				
			$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
				->join('sales_invoice AS SI', function($join) {
						$join->on('SI.job_id','=','jobmaster.id');
					} )
				->join('sales_invoice_item AS SIM', function($join) {
						$join->on('SIM.sales_invoice_id','=','SI.id');
					} )
				->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','SI.cr_account_id');
					} )
			
				->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
	         if($job_id)
			 
			 {
				$qry16 = $qry16->where('jobmaster.id', $job_id);

					}
	        if($date_from!='' && $date_to!=''){
				$qry16 = $qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
			}
	
	        $qry16 = $qry16->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name', 'SI.net_total AS income',
				'jobmaster.incexp AS amount','AC.account_id','SI.less_description AS vehiclemodel',
				'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake','SI.less_description3 AS nextservice','SI.previnv_description AS servicedby','SI.kilometer AS kilometer');
				$results2 = $qry16->get();
		
		
				$results1 =$qry1->union($qry2)->get();
			
			return array_merge($results1,$results2);
           // echo '<pre>';print_r($results);exit;	

			case 'detail': //journal
				$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
				$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
			   $job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
				
				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchase_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('purchase_split_item AS PIM', function($join) {
									$join->on('PIM.purchase_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.*','PIM.item_total AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer');
							
				$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('sales_split_item AS PIM', function($join) {
									$join->on('PIM.sales_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.*',DB::raw('"0" AS amount'), //DB::raw('SUM(PI.net_amount) AS amount'),
							'PIM.item_total AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"SS" AS type'),
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer');
							
				
				$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('payment_voucher_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('payment_voucher AS PV', function($join) {
									$join->on('PV.id','=','PVE.payment_voucher_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)
							->where('PVE.entry_type','Dr')
							->where('PVE.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry3->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry3->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PV" AS type'),
							DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
				$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('petty_cash_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('petty_cash AS PV', function($join) {
									$join->on('PV.id','=','PVE.petty_cash_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								})
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)
							->where('PVE.entry_type','Dr')
							->where('PVE.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry4->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry4->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
							DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
				$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('journal_entry AS PVE', function($join) {
									$join->on('PVE.job_id','=','jobmaster.id');
								} )
							->join('journal AS PV', function($join) {
									$join->on('PV.id','=','PVE.journal_id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PVE.account_id');
								} )
							->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00')
							->where('account_category.parent_id',4);
				if($job_id)
					$qry5->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
				
				$qry5->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', 
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"JV" AS type'),
							DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
							'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
							->groupBy('PV.voucher_no');
							
							
							$qry16 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_invoice AS SI', function($join) {
									$join->on('SI.job_id','=','jobmaster.id');
								} )
							->join('sales_invoice_item AS SIM', function($join) {
									$join->on('SIM.sales_invoice_id','=','SI.id');
								} )
							->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SI.cr_account_id');
								} )
						
							->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
							
						 if($job_id)
						 
						 {
							$qry16 = $qry16->where('jobmaster.id', $job_id);
			
								}
						if($date_from!='' && $date_to!=''){
							$qry16 = $qry16->whereBetween('SI.voucher_date', array($date_from, $date_to));
						}
				
						$qry16 = $qry16->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name', 
							'jobmaster.incexp AS amount','AC.account_id','SI.less_description AS vehiclemodel','AC.id AS acid',DB::raw('"" AS item_code'),
							'SIM.quantity','SIM.id AS itemid','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake','SI.less_description3 AS nextservice','SI.previnv_description AS servicedby','SI.kilometer AS kilometer');
							$results2 = $qry16->get();
								
			$results1 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->orderBy('type','ASC')->get();	
			return array_merge($results1,$results2);	
	   

			}
		}
		
		
		 if($is_jobsplit) {
			
			switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					

					//purchase split

					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
					           ->join('purchase_split_item AS PSI', function($join) {
										$join->on('PSI.item_jobid','=','jobmaster.id');
									} )
								->join('purchase_split AS PS', function($join) {
										$join->on('PSI.purchase_split_id','=','PS.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				//	if($job_id)
						//$qry1->where('jobmaster.id', $job_id);
						
						
				if(isset($attributes['job_id']) && $attributes['job_id']!='')
	                $qry1->whereIn('jobmaster.id', $attributes['job_id']);	
 
	
	
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.*','PS.net_amount AS amount','jobmaster.incexp AS income','AM.master_name AS customer');
					
					
					//sales split

					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.*',DB::raw('"0" AS amount'),'PS.net_amount AS income','AM.master_name AS customer');
					
				$results = $qry1->union($qry2)->get();
					
				case 'detail': //journal
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				   $job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.purchase_split_id','=','PS.id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.*','PIM.item_total AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
								'PS.description AS jdesc','AM.master_name AS customer');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->join('sales_split_item AS PIM', function($join) {
										$join->on('PIM.sales_split_id','=','PS.id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.*',DB::raw('"0" AS amount'), //DB::raw('SUM(PI.net_amount) AS amount'),
								'PIM.item_total AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"SS" AS type'),
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
								'PS.description AS jdesc','AM.master_name AS customer');
								
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)
								->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PV" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									})
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)
								->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('journal AS PV', function($join) {
										$join->on('PV.id','=','PVE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00')
								->where('account_category.parent_id',4);
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.*',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
				$results = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->orderBy('type','ASC')->get();	
				
								//->groupBy('PS.voucher_no')
			}
			
		}
		 else
		{
		    ############ NORMAL TYPE JOB SEARCH HERE #############
			switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT... job_assign
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					//JOB EXPENSE SECTION...
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('PI.status', 1)
								->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$query1_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->where('AC.job_assign', 1)
								->where('PI.status', 1)
								->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1_1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1_1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1_1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.job_id','=','jobmaster.id');
									} )
									->join('goods_issued AS GI', function($join) {
										$join->on('GI.id','=','GIM.goods_issued_id');
									} )
								->where('GI.status', 1)
								->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(GIM.total_price) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					
					

					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Dr')
								->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('JE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('JE.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
						
						
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$query3_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Dr')
								->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereIn('JE.account_id', function($qury) {
									$qury->select('id')->from('account_master')->where('job_assign', 1); 
								});
					if($job_id)
						$query3_1->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('JE.voucher_date', array($date_from, $date_to));
					
					$query3_1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('PVE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$query4_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00')
								->whereIn('PVE.account_id', function($qury) {
									$qury->select('id')->from('account_master')->where('job_assign', 1);  
								});
					if($job_id)
						$query4_1->where('jobmaster.id', $job_id);
					
					$query4_1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->where('PCE.status', 1)->where('PCE.entry_type','Dr')
								->where('PCE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('PCE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('PCE.voucher_date', array($date_from, $date_to));
					
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$query5_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->where('PCE.status', 1)->where('PCE.entry_type','Dr')
								->where('PCE.deleted_at', '0000-00-00 00:00:00')
								->whereIn('PCE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->where('job_assign', 1);  
								});
					if($job_id)
						$query5_1->where('jobmaster.id', $job_id);
					
					//if($date_from!='' && $date_to!='')
						//$query3->whereBetween('PCE.voucher_date', array($date_from, $date_to));
					
					$query5_1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$query6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchase_split_id');
									} )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
                       $query6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PIM.item_total) AS amount'),
								'jobmaster.incexp AS income')->groupBy('jobmaster.id');	
								
					$query6_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('salessplit_return_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('salessplit_return AS SS', function($join) {
										$join->on('SS.id','=','SIM.salessplit_return_id');
									} )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6_1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6_1->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
                       $query6_1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(SIM.item_total) AS amount'),
								'jobmaster.incexp AS income')->groupBy('jobmaster.id');			
											
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query1_1)->union($query3_1)->union($query4_1)->union($query5_1)->union($query6)->union($query6_1)->get();
					
					
					//JOB INCOME SECTION...
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)		
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
								
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$qry1_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->where('AC.job_assign',1)		
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1_1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1_1->whereBetween('SI.voucher_date', array($date_from, $date_to));
								
					$qry1_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
								
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.total) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$qry2_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->where('AC.job_assign',1)
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2_1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2_1->whereBetween('GR.voucher_date', array($date_from, $date_to));
								
					$qry2_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.total) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('JE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$qry3_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00')
								->whereIn('JE.account_id', function($qury) {
									$qury->select('id')->from('account_master')->where('job_assign', 1); 
								});
					if($job_id)
						$qry3_1->where('jobmaster.id', $job_id);
					
					$qry3_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
								
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00')
								->whereNotIn('RVE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								});
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
								
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$qry4_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00')
								->whereIn('RVE.account_id', function($qury) {
									$qury->select('id')->from('account_master')->where('job_assign', 1); 
								});
					if($job_id)
						$qry4_1->where('jobmaster.id', $job_id);
								
					$qry4_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->whereNotIn('PCE.account_id', function($qury) use($excludearr) {
									$qury->select('id')->from('account_master')->whereIn('account_category_id', $excludearr); 
								})
								->where('PCE.status', 1)->where('PCE.entry_type','Cr')->where('PCE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					//QRY GET FROM JOB ASSIGNABLE ACCOUNTS TRANSACTIONS....
					$qry5_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->whereNotIn('PCE.account_id', function($qury) {
									$qury->select('id')->from('account_master')->where('job_assign', 1); 
								})
								->where('PCE.status', 1)->where('PCE.entry_type','Cr')->where('PCE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5_1->where('jobmaster.id', $job_id);
					
					$qry5_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('sales_split AS SS', function($join) {
										$join->on('SS.id','=','SIM.sales_split_id');
									} )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
                       $qry6->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SIM.item_total) AS amount'),
								'jobmaster.incexp AS income')->groupBy('jobmaster.id');	
								
								
				$qry6_1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchasesplit_return_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchasesplit_return AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchasesplit_return_id');
									} )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6_1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6_1->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
                       $qry6_1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PIM.item_total) AS amount'),
								'jobmaster.incexp AS income')->groupBy('jobmaster.id');					
								
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry1_1)->union($qry2_1)->union($qry3_1)->union($qry4_1)->union($qry5_1)->union($qry6)->union($qry6_1)->get();
					
					return array_merge($results1,$results2);
					
				break;
				
				case 'summary_ac':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type') )
								->groupBy('jobmaster.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GI.net_amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'))
								->groupBy('jobmaster.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'))
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'))
								->groupBy('jobmaster.id');
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->get();
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'))
								->groupBy('jobmaster.id');
								
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.net_amount) AS income'),
										 'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'))
								->groupBy('jobmaster.id');
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'))
								->groupBy('jobmaster.id');
					
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),
										 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'))
								->groupBy('jobmaster.id');
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->get();
					
					return array_merge($results1,$results2);
					
				break;
				
				case 'detail':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','PIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PI.subtotal AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type'),'PIM.id AS itemid',
								'PIM.quantity','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc');
							//	->groupBy('PI.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.job_id','=','jobmaster.id');
									} )
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.id','=','GIM.goods_issued_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','GI.net_amount AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'),'GIM.id AS itemid',
								'GIM.quantity','GIM.unit_price','IM.item_code','IM.description','GI.voucher_date','GI.description AS jdesc');
							//	->groupBy('GI.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');
			
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc');
							//->groupBy('PV.id');
			
			$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query5->whereBetween('PV.voucher_date', array($date_from, $date_to));	
						
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc');
								//->groupBy('PV.id');
					//	$results1 = $query5	->get();
					
					$query6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');
								
								
								
				$query7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
					//	->groupBy('J.id');
					
					$query8 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchase_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query8->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query8->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $query8->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),'PIM.id AS itemid',
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date','PS.description AS jdesc');
								
								
				$query9 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('salessplit_return_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('salessplit_return AS SS', function($join) {
										$join->on('SS.id','=','SIM.salessplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query9->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query9->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
    $query9->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no',DB::raw('"SSR" AS type'),'SIM.id AS itemid',
								'SIM.quantity','SIM.unit_price','SIM.account_id AS item_code','SIM.item_description AS description','SS.voucher_date','SS.description AS jdesc');
												
					
					
					
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query6)->union($query7)->union($query8)->union($query9)->get();
					
					//echo '<pre>';print_r($results1);exit;
				
					//SALES INVO;
				
				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('sales_invoice_item AS SIM', function($join) {
										$join->on('SIM.sales_invoice_id','=','SI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SIM.item_id');
								} )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'),'SIM.id AS itemid',
								'SIM.quantity','SIM.unit_price','IM.item_code','IM.description','SI.voucher_date','SI.description AS jdesc')
								->groupBy('SI.id');
					
					
						//Goods Return;			
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GRM.item_id');
								} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','GR.net_amount AS income',//DB::raw('SUM(GR.net_amount) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'),'GRM.id AS itemid',
								'GRM.quantity','GRM.unit_price','IM.item_code','IM.description','GR.voucher_date','GR.description AS jdesc')
								->groupBy('GR.id');
					
						//Journal;		
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc')
								->groupBy('J.id');
								
					
					//Receipt Voucher;	
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc')
								->groupBy('RV.id');
								
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS RV', function($join) {
										$join->on('RV.id','=','RVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"PC" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc')
								->groupBy('RV.id');
						
				
				
				
				
				$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
							//	->groupBy('J.id');
								
								
					$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),DB::raw('"" AS itemid'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');	
								
					$qry8 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('sales_split AS SS', function($join) {
										$join->on('SS.id','=','SIM.sales_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry8->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry8->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
    $qry8->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no',DB::raw('"SS" AS type'),'SIM.id AS itemid',
								'SIM.quantity','SIM.unit_price','SIM.account_id AS item_code','SIM.item_description AS description','SS.voucher_date','SS.description AS jdesc');
								
			$qry9 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchasesplit_return_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchasesplit_return AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchasesplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PIM.account_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry9->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry9->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $qry9->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PSR" AS type'),'PIM.id AS itemid',
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date','PS.description AS jdesc');
													
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->union($qry8)->union($qry9)->get();
					
					return array_merge($results1,$results2);
					
				break;
					
				case 'stockin':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query = DB::table('jobmaster')->where('jobmaster.status', 1)
												->join('purchase_invoice AS PI', function($join) {
														$join->on('PI.job_id','=','jobmaster.id');
													} )
												->join('account_master AS AC', function($join) {
														$join->on('AC.id','=','PI.supplier_id');
													} )
												->join('purchase_invoice_item AS PIM', function($join) {
														$join->on('PIM.purchase_invoice_id','=','PI.id');
													} )
												->join('itemmaster AS IM', function($join) {
														$join->on('IM.id','=','PIM.item_id');
													} )
												->whereNotIn('AC.account_category_id',$excludearr)
												->where('PI.status', 1)
												->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query->select('jobmaster.id','jobmaster.code','jobmaster.name','PI.id AS piid','IM.id AS item_id',DB::raw('"PI" AS type'),
														 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.reference_no',
														 'IM.item_code','IM.description','PIM.quantity','PIM.id AS itemid','PIM.unit_price','PI.voucher_date','PI.voucher_no');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.job_account_id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('itemmaster AS IM', function($join) {
										$join->on('IM.id','=','GRM.item_id');
									} )
								->where('GR.status', 1)
								->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GR.id AS siid','IM.id AS item_id',DB::raw('"GR" AS type'),
									'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no AS reference_no',
									'IM.item_code','IM.description','GRM.quantity','GRM.id AS itemid','GRM.unit_price','GR.voucher_date','GR.voucher_no');
					
					
					//$results = $query2->get
					
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchase_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PS.supplier_id');
								} )
								
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PS.id AS siid',DB::raw('"PS" AS type'), //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no AS reference_no',
								'PIM.account_id AS item_code','PIM.item_description AS description','PIM.quantity','PIM.id AS itemid','PIM.unit_price','PS.voucher_date','PS.voucher_no');
					
			
			$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('salessplit_return_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('salessplit_return AS SS', function($join) {
										$join->on('SS.id','=','SIM.salessplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SS.customer_id');
								} )
								
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
   $query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SS.id AS siid',DB::raw('"SSR" AS type'), //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no AS reference_no',
								'SIM.account_id AS item_code','SIM.item_description AS description','SIM.quantity','SIM.id AS itemid','SIM.unit_price','SS.voucher_date','SS.voucher_no');
					
			
					$results = $query->union($query2)->union($query3)->union($query4)->get();
					
					return $results;			
				
				case 'stockout':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
				$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
												->join('sales_invoice AS SI', function($join) {
														$join->on('SI.job_id','=','jobmaster.id');
													} )
												->join('account_master AS AC', function($join) {
														$join->on('AC.id','=','SI.customer_id');
													} )
												->join('sales_invoice_item AS SIM', function($join) {
														$join->on('SIM.sales_invoice_id','=','SI.id');
													} )
												->join('itemmaster AS IM', function($join) {
														$join->on('IM.id','=','SIM.item_id');
													} )
												->where('IM.class_id',1)
												->where('SI.status', 1)
												->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.name','SI.id AS siid','IM.id AS item_id',DB::raw('"SI" AS type'),
														 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SI.reference_no',
														 'IM.item_code','IM.description','SIM.quantity','SIM.id AS itemid','SIM.unit_price','SI.voucher_date','SI.voucher_no');
												
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.job_id','=','jobmaster.id');
									} )
								
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.id','=','GIM.goods_issued_id');
									} )
									->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
										$join->on('IM.id','=','GIM.item_id');
									} )
								->where('GI.status', 1)
								->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GI.id AS siid','IM.id AS item_id',DB::raw('"GI" AS type'),
									'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no AS reference_no',
									'IM.item_code','IM.description','GIM.quantity','GIM.id AS itemid','GIM.unit_price','GI.voucher_date','GI.voucher_no');
									//->groupBy('GI.id');
					
					//$results = $query2->get(); 
					
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('sales_split AS SS', function($join) {
										$join->on('SS.id','=','SIM.sales_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SS.customer_id');
								} )
								
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
   $query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SS.id AS siid',DB::raw('"SS" AS type'), //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no AS reference_no',
								'SIM.account_id AS item_code','SIM.item_description AS description','SIM.quantity','SIM.id AS itemid','SIM.unit_price','SS.voucher_date','SS.voucher_no');
					
					
					
						$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchasesplit_return_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchasesplit_return AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchasesplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PS.supplier_id');
								} )
								
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PS.id AS siid',DB::raw('"PSR" AS type'), //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no AS reference_no',
								'PIM.account_id AS item_code','PIM.item_description AS description','PIM.quantity','PIM.id AS itemid','PIM.unit_price','PS.voucher_date','PS.voucher_no');
					
			
					$results = $query1->union($query2)->union($query3)->union($query4)->get();//['invoice']
					
					return $results;	
					
				default;
					$results = array();
						
			}
		}
		return $results;
	}
	
	public function getVehicleDetails($attributes) {
		
		return DB::table('vehicle')->where('vehicle.reg_no', $attributes['vehicle'])
							->join('account_master AS CA', function($join) {
								$join->on('CA.id','=','vehicle.customer_id');
							} )
							->select('vehicle.id','vehicle.name','CA.master_name As customer','vehicle.customer_id','vehicle.reg_no',
									 'vehicle.customer_name','vehicle.phone','vehicle.is_cashsale')
							->first();
	}
	public function ajaxCreate($attributes)
	{
		
		DB::beginTransaction();
		try { 
			
			$check1 = $this->jobmaster->where('name', trim($attributes['name']))->where('status',1)->count();
			$check2 = $this->jobmaster->where('code', trim($attributes['code']))->where('status',1)->count();
			if(($check1 > 0) || ($check2 > 0))
				return 0;
				
			$this->jobmaster->code = trim($attributes['code']);
			$this->jobmaster->name = trim($attributes['name']);
			$this->jobmaster->open_cost = trim($attributes['open_cost']);
			$this->jobmaster->customer_id = trim($attributes['customer_id']);
			$this->jobmaster->status = 1;
			$this->jobmaster->fill($attributes)->save();
			
				
			DB::commit();
			return $this->jobmaster->id;
			
		} catch(\Exception $e) {
				
			DB::rollback();
			return -1;
		}
	}
	
	public function getVehicleJobSummaryReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
		
		$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->where('PI.status', 1)
								->where('PI.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query1->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
			
			$query1->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(PI.subtotal) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
						
			$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('goods_issued AS GI', function($join) {
								$join->on('GI.job_id','=','jobmaster.id');
							} )
						->where('GI.status', 1)
						->where('GI.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query2->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
			
			$query2->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(GI.total) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('journal_entry AS JE', function($join) {
								$join->on('JE.job_id','=','jobmaster.id');
							} )
						->where('JE.status', 1)->where('JE.entry_type','Dr')
						->where('JE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query3->where('jobmaster.id', $job_id);
			
			//if($date_from!='' && $date_to!='')
				//$query3->whereBetween('JE.voucher_date', array($date_from, $date_to));
			
			$query3->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('payment_voucher_entry AS PVE', function($join) {
								$join->on('PVE.job_id','=','jobmaster.id');
							} )
						->where('PVE.status', 1)->where('PVE.entry_type','Dr')
						->where('PVE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query4->where('jobmaster.id', $job_id);
			
			$query4->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(PVE.amount) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('petty_cash_entry AS PCE', function($join) {
								$join->on('PCE.job_id','=','jobmaster.id');
							} )
						->where('PCE.status', 1)->where('PCE.entry_type','Dr')
						->where('PCE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query5->where('jobmaster.id', $job_id);
			
			//if($date_from!='' && $date_to!='')
				//$query3->whereBetween('PCE.voucher_date', array($date_from, $date_to));
			
			$query5->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
						
			$query6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.job_id','=','jobmaster.id');
									} )
								->where('PS.status', 1)
								->where('PS.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$query6->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$query6->whereBetween('PS.voucher_date', array($date_from, $date_to));
			
			$query6->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(PS.subtotal) AS amount'),'jobmaster.incexp AS income',
							DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');			
						
			$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query6)->get();
			
			$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('sales_invoice AS SI', function($join) {
								$join->on('SI.job_id','=','jobmaster.id');
							} )
						->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','SI.customer_id');
						} )	
						->join('vehicle AS V', function($join) {
							$join->on('V.id','=','SI.vehicle_id');
						} )
						->where('SI.is_rental', 2)		
						->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry1->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
			
			if($customer_id)
				$qry1->where('SI.customer_id', $customer_id);

			$qry1->where(function($qry) use($search_val) {
				$qry->where('V.reg_no','LIKE',"%{$search_val}%")
					->orWhere('V.engine_no', 'LIKE',"%{$search_val}%")
					->orWhere('V.chasis_no','LIKE',"%{$search_val}%");
			});
		
			$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(SI.subtotal) AS income'),'jobmaster.incexp AS amount',
						  'AC.master_name','V.reg_no','V.chasis_no')
						->groupBy('jobmaster.id');
						
			$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('goods_return AS GR', function($join) {
								$join->on('GR.job_id','=','jobmaster.id');
							} )
						->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry2->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
						
			$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(GR.total) AS income'),'jobmaster.incexp AS amount',
						  DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('journal_entry AS JE', function($join) {
								$join->on('JE.job_id','=','jobmaster.id');
							} )
						->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry3->where('jobmaster.id', $job_id);
			
			$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income',
						  DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('receipt_voucher_entry AS RVE', function($join) {
								$join->on('RVE.job_id','=','jobmaster.id');
							} )
						->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry4->where('jobmaster.id', $job_id);
						
			$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'jobmaster.incexp AS income',
						  DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
			
			$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('petty_cash_entry AS PCE', function($join) {
								$join->on('PCE.job_id','=','jobmaster.id');
							} )
						->where('PCE.status', 1)->where('PCE.entry_type','Cr')->where('PCE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry5->where('jobmaster.id', $job_id);
			
			$qry5->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income',
						  DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');
						
			$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
						->join('sales_split AS SS', function($join) {
								$join->on('SS.job_id','=','jobmaster.id');
							} )
						->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry6->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry6->whereBetween('SS.voucher_date', array($date_from, $date_to));
						
			$qry6->select('jobmaster.id','jobmaster.code','jobmaster.name as jobname',DB::raw('SUM(SS.total) AS income'),'jobmaster.incexp AS amount',
						  DB::raw('"" AS master_name'),DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'))
						->groupBy('jobmaster.id');			
			
			/* $qry->select('SI.voucher_date','SI.voucher_no','jobmaster.code','V.reg_no','V.engine_no','SI.net_total AS income',
									   'jobmaster.name as jobname','jobmaster.incexp AS amount','V.chasis_no','JO.kilometer','AC.master_name','V.name','V.model')
							  ->get(); */
							  
		$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->get();
		
		return array_merge($results2,$results1);
					
	//-----------------------------------------------------------				
		/* $qry = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('sales_invoice AS SI', function($join) {
							$join->on('SI.job_id','=','jobmaster.id');
						} )
					->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','SI.customer_id');
						} )
					->join('vehicle AS V', function($join) {
							$join->on('V.id','=','SI.vehicle_id');
						} )
					->join('sales_order AS JO', function($join) {
							$join->on('JO.id','=','SI.document_id')->where('SI.document_type','=','SO');
						} )
					->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00')->where('SI.is_rental', 2);
		
		if($job_id)
			$qry = $qry->where('jobmaster.id', $job_id);
		
		if($customer_id)
			$qry = $qry->where('SI.customer_id', $customer_id);

		if($date_from!='' && $date_to!='')
			$qry = $qry->whereBetween('SI.voucher_date', array($date_from, $date_to));
		
		$qry->where(function($qry1) use($search_val) {
			$qry1->where('V.reg_no','LIKE',"%{$search_val}%")
				->orWhere('V.engine_no', 'LIKE',"%{$search_val}%")
				->orWhere('V.chasis_no','LIKE',"%{$search_val}%");
		});
		
		$results = $qry = $qry->select('SI.voucher_date','SI.voucher_no','jobmaster.code','V.reg_no','V.engine_no','SI.net_total AS income',
									   'jobmaster.name as jobname','jobmaster.incexp AS amount','V.chasis_no','JO.kilometer','AC.master_name','V.name','V.model')
							  ->get();
							  
		return $results; */
	}
	
	public function getVehicleJobReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
		
		$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->leftjoin('purchase_split AS PS', function($join) {
								$join->on('PS.job_id','=','jobmaster.id');
							})
							->join('purchase_split_item AS PIM', function($join) {
								$join->on('PIM.purchase_split_id','=','PS.id');
							})
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.code','PIM.item_total AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),type
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer',DB::raw('"PS" AS vtype '),
							DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
							
		$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('salessplit_return AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('salessplit_return_item AS PIM', function($join) {
									$join->on('PIM.salessplit_return_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.code','PIM.item_total AS amount', 
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer',DB::raw('"SSR" AS vtype '),
							DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
							
				
		$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('payment_voucher_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('payment_voucher AS PV', function($join) {
							$join->on('PV.id','=','PVE.payment_voucher_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						} )
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)
					->where('PVE.entry_type','Dr')
					->where('PVE.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$qry3->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry3->select('jobmaster.code',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"PV" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'))
					->groupBy('PV.voucher_no');
					
		$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('petty_cash_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('petty_cash AS PV', function($join) {
							$join->on('PV.id','=','PVE.petty_cash_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						})
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)
					->where('PVE.entry_type','Dr')
					->where('PVE.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$qry4->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry4->select('jobmaster.code',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"PC" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'))
					->groupBy('PV.voucher_no');
					
		$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('journal_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('journal AS PV', function($join) {
							$join->on('PV.id','=','PVE.journal_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						} )
					->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					//->where('account_category.parent_id',4);
		if($job_id)
			$qry5->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry5->select('jobmaster.code',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', 
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"JV" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'))
					->groupBy('PV.voucher_no');
					
		$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','PIM.item_id');
								} )
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00')
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.code','PIM.total_price AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc',
								'AC.master_name AS customer',
								DB::raw('"PI" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
							
		
		$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.goods_issued_id','=','GI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GIM.item_id');
								} )
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00')
								->where('GIM.status', 1)->where('GIM.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.code','GIM.total_price AS amount', 
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',
								'GIM.quantity','GIM.id AS itemid','GIM.unit_price','IM.item_code','IM.description','GI.voucher_date','GI.description AS jdesc',
								'AC.master_name AS customer',
								DB::raw('"GI" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
					
		$results1 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->orderBy('voucher_date','ASC')->get();	
								
		//GETTING JOB INVOICE.....		
		$qry = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('sales_invoice AS SI', function($join) {
							$join->on('SI.job_id','=','jobmaster.id');
						} )
					->join('sales_invoice_item AS SIM', function($join) {
							$join->on('SIM.sales_invoice_id','=','SI.id');
						} )
					->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','SIM.item_id');
						} )
					->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','SI.cr_account_id');
						} )
					->leftjoin('vehicle AS V', function($join) {
							$join->on('V.id','=','SI.vehicle_id');
						} )
					->leftjoin('sales_order AS JO', function($join) {
							$join->on('JO.id','=','SI.document_id')->where('SI.document_type','=','SO');
						} )
					->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00')//
					->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00');
					
		
				
		if($job_id)
			$qry = $qry->where('jobmaster.id', $job_id);
		
		if($customer_id)
			$qry = $qry->where('SI.customer_id', $customer_id);

		if($date_from!='' && $date_to!='')
			$qry = $qry->whereBetween('SI.voucher_date', array($date_from, $date_to));
		
		if($attributes['type']=='workshop') {
		    $qry->where('SI.is_rental', 2); 
		    
    		$qry->where(function($qry1) use($search_val) {
    			$qry1->where('V.reg_no','LIKE',"%{$search_val}%")
    				->orWhere('V.engine_no', 'LIKE',"%{$search_val}%")
    				->orWhere('V.chasis_no','LIKE',"%{$search_val}%");
    		}); 
		}
		
		$qry->select('SI.voucher_date','SI.voucher_no','IM.description','SIM.quantity','SIM.id AS itemid','SIM.unit_price','jobmaster.code','V.reg_no','V.engine_no',
					 DB::raw('"SI" AS vtype '),'V.chasis_no','SIM.line_total AS income','AC.master_name','V.name','V.model','jobmaster.incexp AS amount',
									'JO.next_due','JO.present_km','JO.next_km');
							 
		
		$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GRM.item_id');
								} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00')
								->where('GRM.status', 1)->where('GRM.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry2->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
								
		$qry2->select('GR.voucher_date','GR.voucher_no','IM.description','GRM.quantity','GRM.id AS itemid','GRM.unit_price','jobmaster.code','GR.net_amount AS income',
					  'AC.master_name','jobmaster.incexp AS amount',
					  DB::raw('"GR" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'))->groupBy('GR.id');
									
									
		$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry3->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
								
			$qry3->select('J.voucher_date','J.voucher_no','JE.description',DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'),DB::raw('"0" AS unit_price'),'jobmaster.code',
						'jobmaster.incexp AS income','AC.master_name','JE.amount AS amount',
					  DB::raw('"JV" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'))->groupBy('J.id');
								
		$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry4->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
		
			$qry4->select('RV.voucher_date','RV.voucher_no','jobmaster.code','jobmaster.incexp AS income',
						DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),'RVE.description',
					  'AC.master_name','RVE.amount AS amount',
					  DB::raw('"RV" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'))->groupBy('RV.id');
					  
		$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS RV', function($join) {
										$join->on('RV.id','=','RVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
				
								
				$qry5->select('RV.voucher_date','RV.voucher_no','jobmaster.code','jobmaster.incexp AS income',
						DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'),DB::raw('"0" AS unit_price'),'RVE.description',
					  'AC.master_name','RVE.amount AS amount',
					  DB::raw('"PC" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'))->groupBy('RV.id');
					  
					  
					  $qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('sales_split_item AS PIM', function($join) {
									$join->on('PIM.sales_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry6->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry6->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry6->select('PS.voucher_no','PS.voucher_date','jobmaster.code','jobmaster.incexp AS income','PIM.quantity','PIM.id AS itemid',
			                	'PIM.unit_price','PIM.item_description AS description','AC.master_name','PIM.item_total AS amount',DB::raw('"SS" AS vtype'), 
							DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
							
				$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchasesplit_return AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('purchasesplit_return_item AS PIM', function($join) {
									$join->on('PIM.purchasesplit_return_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry7->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry7->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry7->select('PS.voucher_no','PS.voucher_date','jobmaster.code','jobmaster.incexp AS income','PIM.quantity','PIM.id AS itemid',
			                	'PIM.unit_price','PIM.item_description AS description','AC.master_name','PIM.item_total AS amount',DB::raw('"PSR" AS vtype'), 
							DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'));
								  
			
		$jobResults = $qry->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->get();
		
		return array_merge($jobResults, $results1);
		//echo '<pre>';print_r($ar);exit;
		//return $jobResults;
	}
	
	public function getVehicleJobStockinReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
					
		$query = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.supplier_id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('itemmaster AS IM', function($join) {
										$join->on('IM.id','=','PIM.item_id');
									} )
								->where('PI.status', 1)
								->where('PI.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$query->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$query->whereBetween('PI.voucher_date', array($date_from, $date_to));
		
		$query->select('jobmaster.id','jobmaster.code','jobmaster.name','PI.id AS piid','IM.id AS item_id',DB::raw('"PI" AS type'),
											 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.reference_no',
											 'IM.item_code','IM.description','PIM.quantity','PIM.unit_price','PI.voucher_date','PI.voucher_no',DB::raw('"" AS reg_no'), DB::raw('"" AS chasis_no'));
		
		$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('goods_return AS GR', function($join) {
							$join->on('GR.job_id','=','jobmaster.id');
						} )
					->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','GR.job_account_id');
						} )
					->join('goods_return_item AS GRM', function($join) {
							$join->on('GRM.goods_return_id','=','GR.id');
						} )
					->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','GRM.item_id');
						} )
					->where('GR.status', 1)
					->where('GR.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$query2->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$query2->whereBetween('GR.voucher_date', array($date_from, $date_to));
		
		$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GR.id AS siid','IM.id AS item_id',DB::raw('"GR" AS type'),
						'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no AS reference_no',
						'IM.item_code','IM.description','GRM.quantity','GRM.unit_price','GR.voucher_date','GR.voucher_no',DB::raw('"" AS reg_no'), DB::raw('"" AS chasis_no'));
		
		
		$results = $query->union($query2)->get();
					
		return $results;	
	}
	
	public function getVehicleJobStockoutReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
		
		$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
									->join('sales_invoice AS SI', function($join) {
											$join->on('SI.job_id','=','jobmaster.id');
										} )
									->join('account_master AS AC', function($join) {
											$join->on('AC.id','=','SI.customer_id');
										} )
									->join('sales_invoice_item AS SIM', function($join) {
											$join->on('SIM.sales_invoice_id','=','SI.id');
										} )
									->join('itemmaster AS IM', function($join) {
											$join->on('IM.id','=','SIM.item_id');
										} )
									->leftjoin('vehicle AS V', function($join) {
							             $join->on('V.id','=','SI.vehicle_id');
										} )
									->where('IM.class_id',1)
									->where('SI.status', 1)
									->where('SI.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$query1->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$query1->whereBetween('SI.voucher_date', array($date_from, $date_to));
		
		$query1->select('jobmaster.id','jobmaster.code','jobmaster.name','SI.id AS siid','IM.id AS item_id',DB::raw('"SI" AS type'),
											 'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SI.reference_no',
											 'IM.item_code','IM.description','SIM.quantity','SIM.unit_price','SI.voucher_date','SI.voucher_no','V.reg_no','V.chasis_no');
									
		
		$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('goods_issued AS GI', function($join) {
							$join->on('GI.job_id','=','jobmaster.id');
						} )
					->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','GI.job_account_id');
						} )
					->join('goods_issued_item AS GIM', function($join) {
							$join->on('GIM.goods_issued_id','=','GI.id');
						} )
					->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','GIM.item_id');
						} )
					->where('GI.status', 1)
					->where('GI.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$query2->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
		
		$query2->select('jobmaster.id','jobmaster.code','jobmaster.name','GI.id AS siid','IM.id AS item_id',DB::raw('"GI" AS type'),
						'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no AS reference_no',
						'IM.item_code','IM.description','GIM.quantity','GIM.unit_price','GI.voucher_date','GI.voucher_no',DB::raw('"" AS reg_no'), DB::raw('"" AS chasis_no'));
		
		$results = $query1->union($query2)->get();
		
		return $results;
	}
	
	
	public function getJobProcessReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$query = DB::table('purchase_order')
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_order.supplier_id');
						})
						->Join('jobmaster AS J', function($join) {
							$join->on('J.id','=','purchase_order.job_id');
						})
						->where('purchase_order.status',1)
						->where('purchase_order.deleted_at','0000-00-00 00:00:00');
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}

						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);
						 
		$query->select('purchase_order.voucher_no','purchase_order.voucher_date','J.code as jobcode','AM.master_name','purchase_order.total',
								'purchase_order.vat_amount','purchase_order.net_amount','purchase_order.job_id',DB::raw('"PO" AS type'));
								
		$query2 = DB::table('sales_order')
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','sales_order.customer_id');
						})
						->Join('jobmaster AS J', function($join) {
							$join->on('J.id','=','sales_order.job_id');
						})
						->where('sales_order.status',1)
						->where('sales_order.deleted_at','0000-00-00 00:00:00');
								
						if( $date_from!='' && $date_to!='' ) { 
							$query2->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}

						if(isset($attributes['job_id']))
							$query2->whereIn('sales_order.job_id', $attributes['job_id']);
						 
		$query2->select('sales_order.voucher_no','sales_order.voucher_date','J.code as jobcode','AM.master_name','sales_order.total',
								'sales_order.vat_amount','sales_order.net_total AS net_amount','sales_order.job_id',DB::raw('"SO" AS type'));
		
		$results = $this->sortByJob( $query->union($query2)->orderBy('type','ASC')->get() );	
		
		return $results;
	}
	
	private function sortByJob($result) {
		$childs = array();
		foreach($result as $item)
			$childs[$item->job_id][] = $item;
		
		return $childs;
	}
	
	
	public function getJobProcessReportDetail($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		
		$query = DB::table('purchase_order')
						->join('purchase_order_item AS POI', function($join) {
							$join->on('POI.purchase_order_id','=','purchase_order.id');
						})
						->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','POI.item_id');
						})
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','purchase_order.supplier_id');
						})
						->Join('jobmaster AS J', function($join) {
							$join->on('J.id','=','purchase_order.job_id');
						})
						->where('purchase_order.status',1)
						->where('POI.status',1)
						->where('purchase_order.deleted_at','0000-00-00 00:00:00')
						->where('POI.deleted_at','0000-00-00 00:00:00');
								
						if( $date_from!='' && $date_to!='' ) { 
							$query->whereBetween('purchase_order.voucher_date', array($date_from, $date_to));
						}

						if(isset($attributes['job_id']))
							$query->whereIn('purchase_order.job_id', $attributes['job_id']);
						 
		$query->select('purchase_order.voucher_no','purchase_order.voucher_date','J.code as jobcode','AM.master_name','purchase_order.total',
						'purchase_order.vat_amount','purchase_order.net_amount','purchase_order.job_id',DB::raw('"PO" AS type'),
						'POI.quantity','POI.vat_amount AS line_vat','POI.unit_price','POI.total_price','IM.description','POI.id','purchase_order.id AS pid'
					)->orderBY('purchase_order.voucher_no','ASC');
		
		$query2 = DB::table('sales_order')
						->join('sales_order_item AS SOI', function($join) {
							$join->on('SOI.sales_order_id','=','sales_order.id');
						})
						->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','SOI.item_id');
						})
						->join('account_master AS AM', function($join) {
							$join->on('AM.id','=','sales_order.customer_id');
						})
						->Join('jobmaster AS J', function($join) {
							$join->on('J.id','=','sales_order.job_id');
						})
						->where('sales_order.status',1)
						->where('sales_order.deleted_at','0000-00-00 00:00:00');
								
						if( $date_from!='' && $date_to!='' ) { 
							$query2->whereBetween('sales_order.voucher_date', array($date_from, $date_to));
						}

						if(isset($attributes['job_id']))
							$query2->whereIn('sales_order.job_id', $attributes['job_id']);
						 
		$query2->select('sales_order.voucher_no','sales_order.voucher_date','J.code as jobcode','AM.master_name','sales_order.total',
						'sales_order.vat_amount','sales_order.net_total AS net_amount','sales_order.job_id',DB::raw('"SO" AS type'),
						'SOI.quantity','SOI.vat_amount AS line_vat','SOI.unit_price','SOI.line_total AS total_price','IM.description','SOI.id','sales_order.id AS sid'
						)->orderBY('sales_order.voucher_no','ASC');
								
		$results = $this->sortByJob($query->union($query2)->get());	
		//$results = $query->union($query2)->get();	
		
		return $results;
	}
	
	//JAN25
	public function getVehicleJobAccountwiseReport($attributes) {
	    
	    $date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
					//GETTING ASSETS,LIABILITY,EQUITY CATEGORY ACCOUNTS FOR EXCLUDING RV,PV,JV,PC JOBREPORT...
					$exarr = DB::table('account_category')->whereIn('parent_id',[1,2,3])->select('id')->get();
					$excludearr = [];
					foreach($exarr as $arr) {
						$excludearr[] = $arr->id;
					}
					
					$query1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','PIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','PI.subtotal AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type'),
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
							//	->groupBy('PI.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.job_id','=','jobmaster.id');
									} )
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.id','=','GIM.goods_issued_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GIM.item_id');
								} )
								->whereNotIn('AC.account_category_id',$excludearr)
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','GI.net_amount AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'),
								'GIM.quantity','GIM.id AS itemid','GIM.unit_price','IM.item_code','IM.description','GI.voucher_date','GI.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
							//	->groupBy('GI.id');
					
					$query3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								//->groupBy('J.id');
			
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('payment_voucher AS PV', function($join) {
										$join->on('PV.id','=','PVE.payment_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
							//->groupBy('PV.id');
			
			$query5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS PV', function($join) {
										$join->on('PV.id','=','PVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PVE.account_id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query5->whereBetween('PV.voucher_date', array($date_from, $date_to));	
						
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								//->groupBy('PV.id');
					//	$results1 = $query5	->get();
					
					$query6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								//->groupBy('J.id');
								
								
								
				$query7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Dr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
					//	->groupBy('J.id');
					
					$query8 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_split_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchase_split AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchase_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PS.supplier_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query8->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query8->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $query8->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','PIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PS" AS type'),
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date','PS.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
					
					
						$query9 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('salessplit_return_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('salessplit_return AS SS', function($join) {
										$join->on('SS.id','=','SIM.salessplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SS.customer_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query9->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query9->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
                    $query9->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','SIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no',DB::raw('"SSR" AS type'),
								'SIM.quantity','SIM.id AS itemid','SIM.unit_price','SIM.account_id AS item_code','SIM.item_description AS description','SS.voucher_date','SS.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								
								
					
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query6)->union($query7)->union($query8)->union($query9)->get();
				//	echo '<pre>';print_r($results1);exit;
				
					//SALES INVO;
				
				$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
								->join('sales_invoice_item AS SIM', function($join) {
										$join->on('SIM.sales_invoice_id','=','SI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','SI.cr_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','SIM.item_id');
								} )
								->leftjoin('vehicle AS V', function($join) {
        							$join->on('V.id','=','SI.vehicle_id');
        						} )
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'),
								'SIM.quantity','SIM.id AS itemid','SIM.unit_price','IM.item_code','IM.description','SI.voucher_date','SI.description AS jdesc',
								'V.reg_no','V.chasis_no','V.name')
								->groupBy('SI.id');
					
					
						//Goods Return;			
					$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GRM.item_id');
								} )
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
					
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','GR.net_amount AS income',//DB::raw('SUM(GR.net_amount) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'),
								'GRM.quantity','GRM.id AS itemid','GRM.unit_price','IM.item_code','IM.description','GR.voucher_date','GR.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'))
								->groupBy('GR.id');
					
						//Journal;		
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'))
								->groupBy('J.id');
								
					
					//Receipt Voucher;	
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'))
								->groupBy('RV.id');
								
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS RV', function($join) {
										$join->on('RV.id','=','RVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"PC" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'RV.voucher_date','RVE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'))
								->groupBy('RV.id');
						
				
				
				
				
				$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
							//	->groupBy('J.id');
								
								
					$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Cr')
								->whereNotIn('AC.account_category_id',$excludearr)->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc',DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								//->groupBy('J.id');	
								
					$qry8 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_split_item AS SIM', function($join) {
										$join->on('SIM.item_jobid','=','jobmaster.id');
									} )
								->join('sales_split AS SS', function($join) {
										$join->on('SS.id','=','SIM.sales_split_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','SS.customer_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00')
								->where('SS.status', 1)->where('SS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry8->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry8->whereBetween('SS.voucher_date', array($date_from, $date_to));
					
					
                    $qry8->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','SIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','SS.voucher_no',DB::raw('"SS" AS type'),
								'SIM.quantity','SIM.id AS itemid','SIM.unit_price','SIM.account_id AS item_code','SIM.item_description AS description','SS.voucher_date','SS.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
								
								
				$qry9 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchasesplit_return_item AS PIM', function($join) {
										$join->on('PIM.item_jobid','=','jobmaster.id');
									} )
								->join('purchasesplit_return AS PS', function($join) {
										$join->on('PS.id','=','PIM.purchasesplit_return_id');
									} )
								->join('account_master AS AC', function($join) {
									$join->on('AC.id','=','PS.supplier_id');
								} )
								->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
								->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry9->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry9->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
   $qry9->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name AS jobname','PIM.item_total AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',DB::raw('"PSR" AS type'),
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date','PS.description AS jdesc',
								DB::raw('"" AS reg_no'),DB::raw('"" AS chasis_no'), DB::raw('"" AS name'));
					
					
				
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->union($qry8)->union($qry9)->get();
					
					return array_merge($results1,$results2);
	}
	
	
	//JAN25...
	public function getVehicleJobReportVoucherwise($attributes) {
	
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
		
		$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->leftjoin('purchase_split AS PS', function($join) {
								$join->on('PS.job_id','=','jobmaster.id');
							})
							->join('purchase_split_item AS PIM', function($join) {
								$join->on('PIM.purchase_split_id','=','PS.id');
							})
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry1->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry1->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry1->select('jobmaster.code','jobmaster.name AS jobname', //DB::raw('SUM(PI.net_amount) AS amount'),type
							'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer',DB::raw('"PS" AS vtype '),
							DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PIM.id AS rowid',DB::raw('"0" AS type'),
							DB::raw('SUM(PIM.item_total) AS amount'))->groupBy('PS.voucher_no');
							
		$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('salessplit_return AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('salessplit_return_item AS PIM', function($join) {
									$join->on('PIM.salessplit_return_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry2->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry2->whereBetween('PS.voucher_date', array($date_from, $date_to));
				
				$qry2->select('jobmaster.code','jobmaster.name AS jobname',DB::raw('"0" AS amount'), //DB::raw('SUM(PI.net_amount) AS amount'),
							'AC.account_id','AC.master_name','AC.id AS acid','PS.voucher_no',
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
							'PS.description AS jdesc','AM.master_name AS customer',DB::raw('"PSR" AS vtype '),
							DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
							DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PS.id AS rowid',DB::raw('"1" AS type'),
							DB::raw('SUM(PIM.item_total) AS income'))->groupBy('PS.voucher_no');
							
				
		$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('payment_voucher_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('payment_voucher AS PV', function($join) {
							$join->on('PV.id','=','PVE.payment_voucher_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						} )
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)
					->where('PVE.entry_type','Dr')
					->where('PVE.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$qry3->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry3->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry3->select('jobmaster.code','jobmaster.name AS jobname',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"PV" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PVE.id AS rowid',DB::raw('"0" AS type'))
					->groupBy('PV.voucher_no');
					
		$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('petty_cash_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('petty_cash AS PV', function($join) {
							$join->on('PV.id','=','PVE.petty_cash_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						})
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)
					->where('PVE.entry_type','Dr')
					->where('PVE.deleted_at', '0000-00-00 00:00:00');
		if($job_id)
			$qry4->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry4->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry4->select('jobmaster.code','jobmaster.name AS jobname',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', //,
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"PC" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PVE.id AS rowid',DB::raw('"0" AS type'))
					->groupBy('PV.voucher_no');
					
		$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('journal_entry AS PVE', function($join) {
							$join->on('PVE.job_id','=','jobmaster.id');
						} )
					->join('journal AS PV', function($join) {
							$join->on('PV.id','=','PVE.journal_id');
						} )
					->join('account_master AS AC', function($join) {
						$join->on('AC.id','=','PVE.account_id');
						} )
					->join('account_category', 'account_category.id', '=', 'AC.account_category_id')
					->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
					->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					//->where('account_category.parent_id',4);
		if($job_id)
			$qry5->where('jobmaster.id', $job_id);
		
		if($date_from!='' && $date_to!='')
			$qry5->whereBetween('PV.voucher_date', array($date_from, $date_to));
		
		$qry5->select('jobmaster.code','jobmaster.name AS jobname',DB::raw('SUM(PVE.amount) AS amount'),//'PVE.amount AS amount',//'jobmaster.code','jobmaster.name', 
					'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',
					DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
					'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer',DB::raw('"JV" AS vtype '),
					DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PVE.id AS rowid',DB::raw('"0" AS type'))
					->groupBy('PV.voucher_no');
					
		$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('purchase_invoice AS PI', function($join) {
										$join->on('PI.job_id','=','jobmaster.id');
									} )
								->join('purchase_invoice_item AS PIM', function($join) {
										$join->on('PIM.purchase_invoice_id','=','PI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','PI.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','PIM.item_id');
								} )
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00')
								->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.code','jobmaster.name AS jobname','PIM.total_price AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',
								'PIM.quantity','PIM.id AS itemid','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc',
								'AC.master_name AS customer',
								DB::raw('"PI" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'PIM.id AS rowid',DB::raw('"0" AS type'),
					DB::raw('SUM(PIM.total_price) AS income'))->groupBy('PI.voucher_no');
							
		
		$qry7 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_issued AS GI', function($join) {
										$join->on('GI.job_id','=','jobmaster.id');
									} )
								->join('goods_issued_item AS GIM', function($join) {
										$join->on('GIM.goods_issued_id','=','GI.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GI.job_account_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GIM.item_id');
								} )
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00')
								->where('GIM.status', 1)->where('GIM.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.code','jobmaster.name AS jobname','GIM.total_price AS amount', 
								'AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',
								'GIM.quantity','GIM.id AS itemid','GIM.unit_price','IM.item_code','IM.description','GI.voucher_date','GI.description AS jdesc',
								'AC.master_name AS customer',
								DB::raw('"GI" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), DB::raw('"" AS model'),'GIM.id AS rowid',DB::raw('"0" AS type'),
					DB::raw('SUM(GIM.total_price) AS income'))->groupBy('GI.voucher_no');
					
		$results1 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->orderBy('voucher_date','ASC')->get();	//echo '<pre>';print_r($results1);exit;
								
		//GETTING JOB INVOICE.....		
		$qry = DB::table('jobmaster')->where('jobmaster.status', 1)
					->join('sales_invoice AS SI', function($join) {
							$join->on('SI.job_id','=','jobmaster.id');
						} )
					->join('sales_invoice_item AS SIM', function($join) {
							$join->on('SIM.sales_invoice_id','=','SI.id');
						} )
					->join('itemmaster AS IM', function($join) {
							$join->on('IM.id','=','SIM.item_id');
						} )
					->join('account_master AS AC', function($join) {
							$join->on('AC.id','=','SI.cr_account_id');//customer_id
						} )
					->leftjoin('vehicle AS V', function($join) {
							$join->on('V.id','=','SI.vehicle_id');
						} )
					->leftjoin('sales_order AS JO', function($join) {
							$join->on('JO.id','=','SI.document_id')->where('SI.document_type','=','SO');
						} )
					->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00')//
					->where('SIM.status', 1)->where('SIM.deleted_at', '0000-00-00 00:00:00');
				
		if($job_id)
			$qry = $qry->where('jobmaster.id', $job_id);
		
		if($customer_id)
			$qry = $qry->where('SI.customer_id', $customer_id);

		if($date_from!='' && $date_to!='')
			$qry = $qry->whereBetween('SI.voucher_date', array($date_from, $date_to));
		
		if($attributes['type']=='workshop') {
		    
		    $qry->where('SI.is_rental', 2);
		    
    		$qry->where(function($qry1) use($search_val) {
    			$qry1->where('V.reg_no','LIKE',"%{$search_val}%")
    				->orWhere('V.engine_no', 'LIKE',"%{$search_val}%")
    				->orWhere('V.chasis_no','LIKE',"%{$search_val}%");
    		});
		}
		
		$qry->select('jobmaster.name AS jobname','SI.voucher_date','SI.voucher_no','IM.description','SIM.quantity','SIM.id AS itemid','SIM.unit_price','jobmaster.code','V.reg_no','V.engine_no','AC.id AS acid',
					 DB::raw('"SI" AS vtype '),'V.chasis_no','AC.account_id','AC.master_name','V.name','V.model','jobmaster.incexp AS amount',
									'JO.next_due','JO.present_km','JO.next_km',DB::raw('"1" AS type'),DB::raw('SUM(SIM.line_total) AS income'))->groupBy('SI.voucher_no');
							 
		
		$qry2 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('goods_return AS GR', function($join) {
										$join->on('GR.job_id','=','jobmaster.id');
									} )
								->join('goods_return_item AS GRM', function($join) {
										$join->on('GRM.goods_return_id','=','GR.id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','GR.account_master_id');
									} )
								->join('itemmaster AS IM', function($join) {
									$join->on('IM.id','=','GRM.item_id');
								} )
								->where('GRM.status', 1)->where('GRM.deleted_at', '0000-00-00 00:00:00')
								->where('GR.status', 1)->where('GR.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry2->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry2->whereBetween('GR.voucher_date', array($date_from, $date_to));
								
		$qry2->select('jobmaster.name AS jobname','GR.voucher_date','GR.voucher_no','IM.description','GRM.quantity','GRM.id AS itemid','GRM.unit_price','jobmaster.code','GR.net_amount AS income',
					  'AC.master_name','jobmaster.incexp AS amount','AC.id AS acid','AC.account_id',
					  DB::raw('"GR" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'),DB::raw('"1" AS type'))->groupBy('GR.id');
									
									
		$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->join('journal AS J', function($join) {
										$join->on('J.id','=','JE.journal_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','JE.account_id');
									} )
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry3->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
								
			$qry3->select('jobmaster.name AS jobname','J.voucher_date','J.voucher_no','JE.description',DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'),DB::raw('"0" AS unit_price'),'jobmaster.code',
						'jobmaster.incexp AS income','AC.master_name','JE.amount AS amount','AC.id AS acid','AC.account_id',
					  DB::raw('"JV" AS vtype'),DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'),DB::raw('"1" AS type'))->groupBy('J.id');
								
		$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('receipt_voucher AS RV', function($join) {
										$join->on('RV.id','=','RVE.receipt_voucher_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
			if($job_id)
				$qry4->where('jobmaster.id', $job_id);
			
			if($date_from!='' && $date_to!='')
				$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
		
			$qry4->select('jobmaster.name AS jobname','RV.voucher_date','RV.voucher_no','jobmaster.code','jobmaster.incexp AS income',
						DB::raw('"0" AS quantity'),DB::raw('"" AS itemid'), DB::raw('"0" AS unit_price'),'RVE.description',
					  'AC.master_name','RVE.amount AS amount','AC.id AS acid','AC.account_id',
					  DB::raw('"RV" AS vtype '),DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'),DB::raw('"1" AS type'))->groupBy('RV.id');
					  
		$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->join('petty_cash AS RV', function($join) {
										$join->on('RV.id','=','RVE.petty_cash_id');
									} )
								->join('account_master AS AC', function($join) {
										$join->on('AC.id','=','RVE.account_id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
				
								
				$qry5->select('jobmaster.name AS jobname','RV.voucher_date','RV.voucher_no','jobmaster.code','jobmaster.incexp AS income',
						DB::raw('"0" AS quantity'), DB::raw('"" AS itemid'),
						DB::raw('"0" AS unit_price'),'RVE.description','AC.account_id',
					  'AC.master_name','RVE.amount AS amount','AC.id AS acid',
					  DB::raw('"PC" AS vtype '),DB::raw('"" AS next_due '),DB::raw('"" AS next_km '),DB::raw('"" AS present_km '),
					  DB::raw('"" AS reg_no'), DB::raw('"" AS engine_no'), DB::raw('"" AS chasis_no'), DB::raw('"" AS name'), 
					  DB::raw('"" AS model'),DB::raw('"0" AS type'))->groupBy('RV.id');
					  
			
				$qry6 = DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('sales_split AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('sales_split_item AS PIM', function($join) {
									$join->on('PIM.sales_split_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							->leftjoin('vehicle AS V', function($join) {
							       $join->on('V.id','=','PS.vehicle_id');
							} )
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry6->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry6->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
				$qry6->select('jobmaster.name AS jobname','PS.voucher_date','PS.voucher_no','jobmaster.code',DB::raw('SUM(PIM.item_total) AS income'), 
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.item_description AS description','AC.account_id','AC.master_name','jobmaster.incexp AS amount','AC.id AS acid',
							DB::raw('"SS" AS vtype'),DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),'V.reg_no',
							 'V.engine_no','V.chasis_no', 'V.name','V.model',DB::raw('"1" AS type'))->groupBy('PS.voucher_no');
							
			$qry7= DB::table('jobmaster')->where('jobmaster.status', 1)
							->join('purchasesplit_return AS PS', function($join) {
									$join->on('PS.job_id','=','jobmaster.id');
								} )
							->join('purchasesplit_return_item AS PIM', function($join) {
									$join->on('PIM.purchasesplit_return_id','=','PS.id');
								} )
							->join('account_master AS AC', function($join) {
								$join->on('AC.id','=','PIM.account_id');
							} )
							
							->leftJoin('account_master AS AM','AM.id', '=', 'jobmaster.customer_id' )
							->where('PIM.status', 1)->where('PIM.deleted_at', '0000-00-00 00:00:00')
							->where('PS.status', 1)->where('PS.deleted_at', '0000-00-00 00:00:00');
				if($job_id)
					$qry6->where('jobmaster.id', $job_id);
				
				if($date_from!='' && $date_to!='')
					$qry6->whereBetween('PS.voucher_date', array($date_from, $date_to));
					
					
				$qry6->select('jobmaster.name AS jobname','PS.voucher_date','PS.voucher_no','jobmaster.code',DB::raw('SUM(PIM.item_total) AS income'), 
							'PIM.quantity','PIM.id AS itemid','PIM.unit_price','PIM.item_description AS description','AC.account_id','AC.master_name','jobmaster.incexp AS amount','AC.id AS acid',
							DB::raw('"PSR" AS vtype'),DB::raw('"" AS next_due'),DB::raw('"" AS next_km'),DB::raw('"" AS present_km'),DB::raw('"" AS reg_no'),DB::raw('"" AS engine_no'),
							 DB::raw('"" AS chasis_no'), DB::raw('"" AS name'),DB::raw('"" AS model'),DB::raw('"1" AS type'))->groupBy('PS.voucher_no');
							
			
		$jobResults = $qry->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->get();
		
		return array_merge($jobResults, $results1);
		//echo '<pre>';print_r($ar);exit;
		//return $jobResults;
	}
	
}

