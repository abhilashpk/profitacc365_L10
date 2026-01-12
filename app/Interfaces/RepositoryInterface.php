<?php namespace App\Interfaces;

/**
 * Class RepositoryInterface
 * @package App\Repositories
 * @author WebClikk
 */
interface RepositoryInterface {

    
    /**
     * Get al data
     * @return mixed
     */
    public function all();

    /**
     * Get data with paginate
     * @param int $page
     * @param int $limit
     * @param bool $all
     * @return mixed
     */
    //public function paginate($page = 1, $limit = 10, $all = false);
	
	/**
     * Get data by id
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create new data
     * @param $attributes
     * @return mixed
     */
    public function create($attributes);

    /**
     * Update data
     * @param $id
     * @param $attributes
     * @return mixed
     */
    public function update($id, $attributes);

    /**
     * Delete data by id
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
