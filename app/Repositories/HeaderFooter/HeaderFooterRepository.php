<?php
declare(strict_types=1);
namespace App\Repositories\HeaderFooter;

use App\Models\HeaderFooter;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Config;

class HeaderFooterRepository extends AbstractValidator implements HeaderFooterInterface {
	
	protected $header_footer;
	
	protected static $rules = [
		'is_header' => 'required',
		'title' => 'required',
		'description' => 'required',
	];
	
	public function __construct(HeaderFooter $header_footer) {
		$this->header_footer = $header_footer;
		
	}
	
	public function all()
	{
		return $this->header_footer->get();
	}
	
	public function find($id)
	{
		return $this->header_footer->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) {
			
			$this->header_footer->is_header = $attributes['is_header'];
			$this->header_footer->title = $attributes['title'];
			$this->header_footer->description = $attributes['description'];
			$this->header_footer->status = 1;
			$this->header_footer->doc = $attributes['doc'];
			$this->header_footer->fill($attributes)->save();
			return true;
		}
		//throw new ValidationException('header_footer validation error12!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->header_footer = $this->find($id);
		$this->header_footer->fill($attributes)->save();
		return true;
	}
	
	
	public function delete($id)
	{
		$this->header_footer = $this->header_footer->find($id);
		$this->header_footer->delete();
	}
	
	public function header_footerList()
	{
		//check admin session and apply return $this->header_footer->where('parent_id',0)->where('status', 1)->get();
		return $this->header_footer->where('status', 1)->get();
	}
	
	public function activeHeaderFooterList()
	{
		return $this->header_footer->select('id','name')->where('status', 1)->orderBy('name', 'ASC')->get()->toArray();
	}
	
	public function check_header_footer_code($code, $id = null) {
		
		if($id)
			return $this->header_footer->where('code',$code)->where('id', '!=', $id)->count();
		else
			return $this->header_footer->where('code',$code)->count();
	}
		
	public function check_header_footer_name($name, $id = null) {
		
		if($id)
			return $this->header_footer->where('name',$name)->where('id', '!=', $id)->count();
		else
			return $this->header_footer->where('name',$name)->count();
	}
	
	public function header_or_footerList($type)
	{
		return $this->header_footer->where('is_header', $type)->get();
	}
	
}

