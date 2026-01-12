<?php
declare(strict_types=1);
namespace App\Repositories\Terms;

use App\Models\Terms;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;

class TermsRepository extends AbstractValidator implements TermsInterface {
	
	protected $terms;
	
	protected static $rules = [
		//'code' => 'required|unique:terms'
	];
	
	public function __construct(Terms $terms) {
		$this->terms = $terms;
	}
	
	public function all()
	{
		return $this->terms->get();
	}
	
	public function find($id)
	{
		return $this->terms->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		
		if($this->isValid($attributes)) { 
			
			$this->terms->code = $attributes['code'];
			$this->terms->description = $attributes['description'];
			$this->terms->file=$_FILES['userfile']['name'];
			$this->terms->status = 1;
			$this->terms->fill($attributes)->save();
			return true;
		}
		
		//throw new ValidationException('terms validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->terms = $this->find($id);
		$this->terms->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->terms = $this->terms->find($id);
		$this->terms->delete();
	}
	
	public function termsList()
	{
		//check admin session and apply return $this->terms->where('parent_id',0)->where('status', 1)->get();
		return $this->terms->where('status', 1)->get();
	}
	
	public function activeTermsList()
	{
		return $this->terms->select('id','description')->where('status', 1)->orderBy('description', 'ASC')->get()->toArray();
	}
	
	public function check_terms_code($code, $id = null) {
		
		if($id)
			return $this->terms->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->terms->where('code',$code)->count();
	}

	
}

