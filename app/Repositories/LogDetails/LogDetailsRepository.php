<?php
declare(strict_types=1);
namespace App\Repositories\LogDetails;

use App\Models\LogDetails;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;
use Illuminate\Support\Facades\DB;

class LogDetailsRepository extends AbstractValidator implements LogDetailsInterface {
	
	protected $log_details;
	
	protected static $rules = [];
	
	public function __construct(LogDetails $log_details) {
		$this->log_details = $log_details;
	}
	
	public function all()
	{
		
	}
	
	public function find($id)
	{
		
	}
	
	public function create($attributes)
	{
		
	}
	
	public function update($id, $attributes)
	{
		
	}
	
	
	public function delete($id)
	{
		
	}
	
	public function bankList()
	{
		
	}
	
	public function getLogDetails($attributes)
	{	
		$date_from = ($attributes['date_from']!='')?date('Y-m-d', strtotime($attributes['date_from'])).' 00:00:00':'';
		$date_to = ($attributes['date_to']!='')?date('Y-m-d', strtotime($attributes['date_to'])).' 23:59:59':'';
										
		switch($attributes['log_module']) {
			case 'account_tr':
				$query =  DB::table('account_transaction')
										->join('account_master AS AM', function($join) {
											$join->on('AM.id', '=', 'account_transaction.account_master_id');
										});
										
				$query->where('account_transaction.voucher_type', '!=', 'OBD');
				
				if($attributes['tr_type']=='creation') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->leftJoin('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.modify_by');
							});
							
					$query->where('account_transaction.modify_at', '0000-00-00 00:00:00')
							->where('account_transaction.deleted_at', '0000-00-00 00:00:00')
							->where('account_transaction.status', 1);
							
					if($date_from!='' && $date_to!='') {		
						$query->whereBetween('account_transaction.created_at', array($date_from, $date_to));
					}
					
				} else if($attributes['tr_type']=='modification') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.modify_by');
							});
							
					$query->where('account_transaction.modify_at', '!=', '0000-00-00 00:00:00')
							->where('account_transaction.deleted_at', '0000-00-00 00:00:00')
							->where('account_transaction.status', 1);
							
				} else if($attributes['tr_type']=='deletion') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.deleted_by');
							});
							
					$query->where('account_transaction.deleted_at', '!=', '0000-00-00 00:00:00')
							->where('account_transaction.status', 0);
							
				}	
				
				return $result = $query->select('account_transaction.*','AM.master_name','AM.account_id','U.name','UM.name AS uname')
										->orderBy('account_transaction.id', 'ASC')
										->groupBy('account_transaction.voucher_type_id')
										->get();
				break;
				
			case 'inventory_tr':
				$query =  DB::table('account_transaction')
										->join('account_master AS AM', function($join) {
											$join->on('AM.id', '=', 'account_transaction.account_master_id');
										});
										
				$query->where('account_transaction.voucher_type', '=', 'SI')
						->orWhere('account_transaction.voucher_type', '=', 'SR')
						->orWhere('account_transaction.voucher_type', '=', 'PI')
						->orWhere('account_transaction.voucher_type', '=', 'PR');
				
				if($attributes['tr_type']=='creation') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->leftJoin('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.modify_by');
							});
							
					$query->where('account_transaction.modify_at', '0000-00-00 00:00:00')
							->where('account_transaction.deleted_at', '0000-00-00 00:00:00')
							->where('account_transaction.status', 1);
							
					if($date_from!='' && $date_to!='') {		
						$query->whereBetween('account_transaction.created_at', array($date_from, $date_to));
					}
					
				} else if($attributes['tr_type']=='modification') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.modify_by');
							});
							
					$query->where('account_transaction.modify_at', '!=', '0000-00-00 00:00:00')
							->where('account_transaction.deleted_at', '0000-00-00 00:00:00')
							->where('account_transaction.status', 1);
							
				} else if($attributes['tr_type']=='deletion') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_transaction.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_transaction.deleted_by');
							});
							
					$query->where('account_transaction.deleted_at', '!=', '0000-00-00 00:00:00')
							->where('account_transaction.status', 0);
							
				}	
				
				return $result = $query->select('account_transaction.*','AM.master_name','AM.account_id','U.name','UM.name AS uname')
										->orderBy('account_transaction.id', 'ASC')
										->groupBy('account_transaction.voucher_type_id')
										->get();
				
			break;
			
			case 'account_master':
				$query =  DB::table('account_master');
				
				if($attributes['tr_type']=='creation') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_master.created_by');
							});
					$query->leftJoin('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_master.modify_by');
							});
							
					$query->where('account_master.modified_at', '0000-00-00 00:00:00')
							->where('account_master.deleted_at', '0000-00-00 00:00:00')
							->where('account_master.status', 1);
							
					if($date_from!='' && $date_to!='') {		
						$query->whereBetween('account_master.created_at', array($date_from, $date_to));
					}
					
				} else if($attributes['tr_type']=='modification') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_master.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_master.modify_by');
							});
							
					$query->where('account_master.modified_at', '!=', '0000-00-00 00:00:00')
							->where('account_master.deleted_at', '0000-00-00 00:00:00')
							->where('account_master.status', 1);
							
				} else if($attributes['tr_type']=='deletion') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'account_master.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'account_master.deleted_by');
							});
							
					$query->where('account_master.deleted_at', '!=', '0000-00-00 00:00:00')
							->where('account_master.status', 0);
							
				}	
				
				return $result = $query->select('account_master.*','U.name','UM.name AS uname')
										->orderBy('account_master.id', 'ASC')
										->get();
				
			break;
			
			case 'item_master':
				$query =  DB::table('itemmaster');
				
				if($attributes['tr_type']=='creation') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'itemmaster.created_by');
							});
					$query->leftJoin('users AS UM', function($join) {
								$join->on('UM.id', '=', 'itemmaster.modify_by');
							});
							
					$query->where('itemmaster.modified_at', '0000-00-00 00:00:00')
							->where('itemmaster.deleted_at', '0000-00-00 00:00:00')
							->where('itemmaster.status', 1);
							
					if($date_from!='' && $date_to!='') {		
						$query->whereBetween('itemmaster.created_at', array($date_from, $date_to));
					}
					
				} else if($attributes['tr_type']=='modification') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'itemmaster.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'itemmaster.modify_by');
							});
							
					$query->where('itemmaster.modified_at', '!=', '0000-00-00 00:00:00')
							->where('itemmaster.deleted_at', '0000-00-00 00:00:00')
							->where('itemmaster.status', 1);
							
				} else if($attributes['tr_type']=='deletion') {
					
					$query->join('users AS U', function($join) {
								$join->on('U.id', '=', 'itemmaster.created_by');
							});
					$query->join('users AS UM', function($join) {
								$join->on('UM.id', '=', 'itemmaster.deleted_by');
							});
							
					$query->where('itemmaster.deleted_at', '!=', '0000-00-00 00:00:00')
							->where('itemmaster.status', 0);
							
				}	
				
				return $result = $query->select('itemmaster.*','U.name','UM.name AS uname')
										->orderBy('itemmaster.id', 'ASC')
										->get();
				
			break;
			
			default:
				$result = array();
		}
			
		
		return $result;
	}
	
}

