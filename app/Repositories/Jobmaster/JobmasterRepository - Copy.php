<?php
declare(strict_types=1);
namespace App\Repositories\Jobmaster;

use App\Models\Jobmaster;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class JobmasterRepository extends AbstractValidator implements JobmasterInterface {
	
	protected $jobmaster;
	
	protected static $rules = [

	];
	
	public function __construct(Jobmaster $jobmaster) {
		$this->jobmaster = $jobmaster;
		
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
            $document_type = $attributes['document_type'];
			$cid = $attributes['customer_id'];

			///END
		
		$this->jobmaster->fill($attributes)->save();
		$id=$this->jobmaster->id;
		return(['id'=>$id,'cid'=>$cid,'document_type'=>$document_type]);
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
								->orderBy($order,$dir);
					
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
		return $this->jobmaster->select('id','name','code','transport_type')->where('status', 1)->where('is_salary_job',0)->orderBy('name', 'ASC')->get()->toArray();
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
			//echo '<pre>';print_r($attributes);exit;	is_workshopsplit purchase_invoice
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
							'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
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
							'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
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
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
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
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
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
							DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
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
							'SIM.quantity','SIM.unit_price','SI.vehicle_no AS vehicleno','SI.less_description2 AS vehiclemake','SI.less_description3 AS nextservice','SI.previnv_description AS servicedby','SI.kilometer AS kilometer');
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
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
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
								'PIM.quantity','PIM.unit_price','PIM.account_id AS item_code','PIM.item_description AS description','PS.voucher_date',
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
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
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
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
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
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'PV.voucher_date','PVE.description AS jdesc','AM.master_name AS customer')
								->groupBy('PV.voucher_no');
								
				$results = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->orderBy('type','ASC')->get();	
				
								//->groupBy('PS.voucher_no')
			}
			
		}
		 else
		{
		
			switch($attributes['search_type']) 
			{
				case 'summary':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
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
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PI.subtotal) AS amount'),'jobmaster.incexp AS income')
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
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(GI.total) AS amount'),'jobmaster.incexp AS income')
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
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$query4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('payment_voucher_entry AS PVE', function($join) {
										$join->on('PVE.job_id','=','jobmaster.id');
									} )
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')
								->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PVE.amount) AS amount'),'jobmaster.incexp AS income')
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
					
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->get();
					
					$qry1 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('sales_invoice AS SI', function($join) {
										$join->on('SI.job_id','=','jobmaster.id');
									} )
										
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
								
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(SI.subtotal) AS income'),'jobmaster.incexp AS amount')
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
								
					$qry2->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(GR.total) AS income'),'jobmaster.incexp AS amount')
								->groupBy('jobmaster.id');
					
					$qry3 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('journal_entry AS JE', function($join) {
										$join->on('JE.job_id','=','jobmaster.id');
									} )
								->where('JE.status', 1)->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(JE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$qry4 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('receipt_voucher_entry AS RVE', function($join) {
										$join->on('RVE.job_id','=','jobmaster.id');
									} )
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
								
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(RVE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
					
					$qry5 = DB::table('jobmaster')->where('jobmaster.status', 1)
								->join('petty_cash_entry AS PCE', function($join) {
										$join->on('PCE.job_id','=','jobmaster.id');
									} )
								->where('PCE.status', 1)->where('PCE.entry_type','Cr')->where('PCE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.name',DB::raw('SUM(PCE.amount) AS amount'),'jobmaster.incexp AS income')
								->groupBy('jobmaster.id');
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->get();
					
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
								->where('PI.status', 1)->where('PI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query1->whereBetween('PI.voucher_date', array($date_from, $date_to));
					
					$query1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PI.subtotal AS amount', //DB::raw('SUM(PI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PI.voucher_no',DB::raw('"PI" AS type'),
								'PIM.quantity','PIM.unit_price','IM.item_code','IM.description','PI.voucher_date','PI.description AS jdesc');
							//	->groupBy('PI.id');
					
					$query2 = DB::table('jobmaster')->where('jobmaster.status', 1)
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
								->where('GI.status', 1)->where('GI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query2->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query2->whereBetween('GI.voucher_date', array($date_from, $date_to));
					
					$query2->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','GI.net_amount AS amount', //DB::raw('SUM(GI.net_amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','GI.voucher_no',DB::raw('"GI" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
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
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query4->whereBetween('PV.voucher_date', array($date_from, $date_to));
					
					$query4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"SP" AS type'),
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
								->where('PVE.status', 1)->where('PVE.entry_type','Dr')->where('PVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query5->whereBetween('PV.voucher_date', array($date_from, $date_to));	
						
					$query5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','PVE.amount AS amount', //DB::raw('SUM(PVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','PV.voucher_no',DB::raw('"PC" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Dr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$query7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$query7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$query7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount', //DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
					//	->groupBy('J.id');
					
					
					
					
					
					$results1 = $query1->union($query2)->union($query3)->union($query4)->union($query5)->union($query6)->union($query7)->get();
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
								->where('SI.status', 1)->where('SI.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry1->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry1->whereBetween('SI.voucher_date', array($date_from, $date_to));
					
					$qry1->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','SI.subtotal AS income', //DB::raw('SUM(SI.net_total) AS income'),
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','SI.voucher_no',DB::raw('"SI" AS type'),
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
								'jobmaster.incexp AS amount','AC.account_id','AC.master_name','AC.id AS acid','GR.voucher_no',DB::raw('"GR" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','JV')->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry3->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry3->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry3->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"JV" AS type'),
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
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry4->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry4->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry4->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"CR" AS type'),
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
								->where('RVE.status', 1)->where('RVE.entry_type','Cr')->where('RVE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry5->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry5->whereBetween('RV.voucher_date', array($date_from, $date_to));
					
					$qry5->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','RVE.amount AS amount',//DB::raw('SUM(RVE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','RV.voucher_no',DB::raw('"PC" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','PIN')->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry6->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry6->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry6->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"PIN" AS type'),
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
								->where('JE.status', 1)->where('J.voucher_type','SIN')->where('JE.entry_type','Cr')->where('JE.deleted_at', '0000-00-00 00:00:00');
					if($job_id)
						$qry7->where('jobmaster.id', $job_id);
					
					if($date_from!='' && $date_to!='')
						$qry7->whereBetween('J.voucher_date', array($date_from, $date_to));
					
					$qry7->select('jobmaster.id','jobmaster.code','jobmaster.code AS code','jobmaster.name','JE.amount AS amount',//DB::raw('SUM(JE.amount) AS amount'),
								'jobmaster.incexp AS income','AC.account_id','AC.master_name','AC.id AS acid','J.voucher_no',DB::raw('"SIN" AS type'),
								DB::raw('"0" AS quantity'), DB::raw('"0" AS unit_price'),DB::raw('"" AS item_code'),DB::raw('"" AS description'),
								'J.voucher_date','JE.description AS jdesc');
								//->groupBy('J.id');			
								
								
					$results2 = $qry1->union($qry2)->union($qry3)->union($qry4)->union($qry5)->union($qry6)->union($qry7)->get();
					
					return array_merge($results1,$results2);
					
				break;
					
				case 'stockin':
					$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
					$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
					$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
					
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
														 'IM.item_code','IM.description','PIM.quantity','PIM.unit_price','PI.voucher_date','PI.voucher_no');
					
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
									'IM.item_code','IM.description','GRM.quantity','GRM.unit_price','GR.voucher_date','GR.voucher_no');
					
					
					//$results = $query2->get();
					$results = $query->union($query2)->get();
					
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
														 'IM.item_code','IM.description','SIM.quantity','SIM.unit_price','SI.voucher_date','SI.voucher_no');
												
					
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
									'IM.item_code','IM.description','GIM.quantity','GIM.unit_price','GI.voucher_date','GI.voucher_no');
									//->groupBy('GI.id');
					
					//$results = $query2->get(); 
					$results = $query1->union($query2)->get();//['invoice']
					
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
			$this->jobmaster->status = 1;
			$this->jobmaster->fill($attributes)->save();
			
				
			DB::commit();
			return $this->jobmaster->id;
			
		} catch(\Exception $e) {
				
			DB::rollback();
			return -1;
		}
	}
	
	public function getVehicleJobReport($attributes) {
		
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])):'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])):'';
		$job_id = isset($attributes['job_id'])?$attributes['job_id']:''; 
		$customer_id = isset($attributes['customer_id'])?$attributes['customer_id']:''; 
		$search_val = isset($attributes['search_val'])?$attributes['search_val']:''; 
				
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

		$results = $qry = $qry->select('SI.voucher_date','SI.voucher_no','IM.description','SIM.quantity','SIM.unit_price','jobmaster.code','V.reg_no','V.engine_no',
									'V.chasis_no','JO.kilometer','SIM.line_total','AC.master_name','V.name','V.model')
							  ->get();
							
		return $results;
	}
	
	
}

