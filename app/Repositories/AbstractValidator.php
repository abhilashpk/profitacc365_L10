<?php
declare(strict_types=1);
namespace App\Repositories;

use Validator;

abstract class AbstractValidator 
{
	protected $errors;
	
	public function isValid($attributes, array $rules = null)
	{
		$val = Validator::make($attributes, ($rules) ? $rules : static::$rules);
		
		if($val->fails()) {
			$this->setErrors($val->messages());
			return false;
		}
		return true;
	}
	
	public function getErrors() 
	{
		return $this->errors;
	}
	
	public function setErrors($errors) 
	{
		$this->errors = $errors;
	}
}

