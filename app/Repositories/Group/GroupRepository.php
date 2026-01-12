<?php
declare(strict_types=1);
namespace App\Repositories\Group;

use App\Models\Group;
use App\Repositories\AbstractValidator;
use App\Exceptions\Validation\ValidationException;
use Image;
use Config;

class GroupRepository extends AbstractValidator implements GroupInterface {
	
	protected $group;
	
	protected static $rules = [
		//'group_name' => 'required|unique:groupcat',
	];
	
	public function __construct(Group $group) {
		$this->group = $group;
		
	}
	
	public function all()
	{
		return $this->group->get();
	}
	
	public function find($id)
	{
		return $this->group->where('id', $id)->first();
	}
	
	public function create($attributes)
	{
		if($this->isValid($attributes)) { 
			
			
			//list($parent_id, $level) = explode(':', $attributes['parent_id']);
			$this->group->parent_id = $attributes['parent_id'];
			$this->group->group_name = $attributes['group_name'];
			$this->group->description = $attributes['description'];
			$this->group->status = 1;
			$this->group->fill($attributes)->save();
			return true;
		}
		
		//throw new ValidationException('group validation error!', $this->getErrors());
	}
	
	public function update($id, $attributes)
	{
		$this->group = $this->find($id);
		$this->group->parent_id = $attributes['parent_id'];
		//list($parent_id, $level) = explode(':', $attributes['parent_id']);
		$this->group->fill($attributes)->save();
		return true;
	}
	
	public function hide($id,$status)
	{
		$this->group = $this->group->find($id);
		$this->group->status = $status;
		$this->group->save();
	}
	
	public function delete($id)
	{
		$this->group = $this->group->find($id);
		$this->group->delete();
	}
	
	public function groupList()
	{
		//check admin session and apply return $this->group->where('parent_id',0)->where('status', 1)->get();
		return $this->group->where('parent_id',0)->get();
	}
	
	public function activeGroupList()
	{
		return $this->group->select('id','group_name','parent_id')->where('status', 1)->orderBy('group_name', 'ASC')->get()->toArray();
	}
	
	public function subgroupList()
	{
		//check admin session and apply return $this->group->where('parent_id',0)->where('status', 1)->get();
		return $this->group->where('parent_id',1)->get();
	}
	
	public function allSubgroup()
	{
		//check admin session and apply return $this->group->where('parent_id',0)->where('status', 1)->get();
		return $this->group->where('parent_id','!=',0)->get();
	}
	
	public function allSubgroupList($parent_id)
	{
		//check admin session and apply return $this->group->where('parent_id',0)->where('status', 1)->get();
		return $this->group->where('parent_id',$parent_id)->select('id','name')->get()->toArray();
	}
	
	public function groupView($id)
	{
		return $this->group->where('id', $id)->join('group');
	}
	
	public function check_group_name($name, $id = null) {
		
		if($id)
			return $this->group->where('group_name',$name)->where('parent_id', 0)->where('id', '!=', $id)->count();
		else
			return $this->group->where('group_name',$name)->where('parent_id', 0)->count();
	}
	
	public function check_subgroup_name($name, $id = null) {
		
		if($id)
			return $this->group->where('group_name',$name)->where('parent_id', 1)->where('id', '!=', $id)->count();
		else
			return $this->group->where('group_name',$name)->where('parent_id', 1)->count();
	}
	
	public function productList($slug)
	{
		//return $this->group->where('slug',$slug)->with('products.productImages')->get();
		return $this->group
					->where('slug',$slug)
					->with('products.defaultImage')
					->select('group.name','group.slug','group.id')->get();

		//return $this->group->where('slug',$slug)->find(1)->products()->get();
	}
}

