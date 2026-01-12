<?php
declare(strict_types=1);
namespace App\Repositories\Parameter1;

use App\Models\Parameter1;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Parameter1Repository extends AbstractValidator implements Parameter1Interface {
	
	protected $parameter1;
	
	protected static $rules = [];
	
	public function __construct(Parameter1 $parameter1) {
		$this->parameter1 = $parameter1;

	}
	
	public function all()
	{
		
	}
	
	public function paginate($page = 1, $limit = 10, $all = false)
	{
		
	}
	
	public function find($id)
	{
		return $this->parameter1->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		
	}
	
	public function update($id, $parameter1)
	{
		$this->parameter1 = $this->find($id);
		list($from_date, $to_date) = explode(' to ', $parameter1['acc_period']);
		$this->parameter1->from_date = date('Y-m-d', strtotime($from_date));
		$this->parameter1->to_date = date('Y-m-d', strtotime($to_date));
		$this->parameter1->item_class = $parameter1['item_class'];
		$this->parameter1->bcurrency_id = $parameter1['bcurrency_id'];
		$this->parameter1->bdecimal_place = $parameter1['bdecimal_place'];
		$this->parameter1->fcurrency_id = $parameter1['fcurrency_id'];
		$this->parameter1->fdecimal_place = $parameter1['fdecimal_place'];
		$this->parameter1->doc_warndays = $parameter1['doc_warndays'];
		$this->parameter1->pdc_warndays = $parameter1['pdc_warndays'];
		$this->parameter1->cost_method = $parameter1['cost_method'];
		$this->parameter1->is_refresh = (isset($parameter1['is_refresh']))?$parameter1['is_refresh']:0;
		$this->parameter1->vat_entry = $parameter1['vat_entry'];
		$this->parameter1->vat_value = $parameter1['vat_value'];
		$this->parameter1->credit_limit = $parameter1['credit_limit'];
		$this->parameter1->item_profit = $parameter1['item_profit'];
		$this->parameter1->profit_per = $parameter1['profit_per'];
		$this->parameter1->cost_type = $parameter1['cost_type'];
		$this->parameter1->item_quantity = $parameter1['item_quantity'];
		$this->parameter1->doc_approve = $parameter1['doc_approve'];
		$this->parameter1->trip_entry = $parameter1['trip_entry'];
		$this->parameter1->vehicle_dashboard = (isset($parameter1['vehicle_dashboard']))?$parameter1['vehicle_dashboard']:0;
		$this->parameter1->adcd_dashboard = $parameter1['adcd_dashboard'];
		$this->parameter1->advanced_workshop = $parameter1['advanced_workshop'];
		$this->parameter1->pi_vat_inc = $parameter1['pi_vat_inc'];
		$this->parameter1->si_vat_inc = $parameter1['si_vat_inc'];
		$this->parameter1->pv_approval = $parameter1['pv_approval'];
		$this->parameter1->special_pswd = $parameter1['special_pswd'];
		$this->parameter1->pdc_alert = $parameter1['pdc_alert'];
		$this->parameter1->save();
		Session::put('trip_entry', $parameter1['trip_entry']);
		return true;
	}
	
	public function delete($id)
	{
			
	}

	public function getParameter1()
	{
		return $this->parameter1->first();
	}
	
	
}


