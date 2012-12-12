<?php
/**
 * @author: zhz
 */
class Category
{	private $id;
    private $data_resource_id;
    private $department_id;
    private $is_viewed_only_admin;
    private $menu_grade;
	private $parent_id;
	private $menu_name;
	private $url;

	public function __construct($id) {
		$this->id = $id;
		if (!$this->from_db())
			$this->id = -1;
	}
	public function from_db() {
		$cache_menu = get_full_menus();
		foreach($cache_menu as $_row){
			if($this->id==$_row['id']){
				$this->data_resource_id = $_row['data_resource_id'];
				$this->department_id = $_row['department_id'];
				$this->is_viewed_only_admin = $_row['is_viewed_only_admin'];
				$this->menu_grade = $_row['menu_grade'];
				$this->parent_id = $_row['parent_id'];
				$this->menu_name = $_row['menu_name'];
				$this->url = $_row['url'];
				return true;
			}
		}

		return false;
	}


	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $data_resource_id
	 */
	public function getData_resource_id() {
		return $this->data_resource_id;
	}

	/**
	 * @return the $department_id
	 */
	public function getDepartment_id() {
		return $this->department_id;
	}

	/**
	 * @return the $is_viewed_only_admin
	 */
	public function getIs_viewed_only_admin() {
		return $this->is_viewed_only_admin;
	}

	/**
	 * @return the $menu_grade
	 */
	public function getMenu_grade() {
		return $this->menu_grade;
	}

	/**
	 * @return the $parent_id
	 */
	public function getParent_id() {
		return $this->parent_id;
	}

	/**
	 * @return the $menu_name
	 */
	public function getMenu_name() {
		return $this->menu_name;
	}

	/**
	 * @return the $url
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param $id the $id to set
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param $data_resource_id the $data_resource_id to set
	 */
	public function setData_resource_id($data_resource_id) {
		$this->data_resource_id = $data_resource_id;
	}

	/**
	 * @param $department_id the $department_id to set
	 */
	public function setDepartment_id($department_id) {
		$this->department_id = $department_id;
	}

	/**
	 * @param $is_viewed_only_admin the $is_viewed_only_admin to set
	 */
	public function setIs_viewed_only_admin($is_viewed_only_admin) {
		$this->is_viewed_only_admin = $is_viewed_only_admin;
	}

	/**
	 * @param $menu_grade the $menu_grade to set
	 */
	public function setMenu_grade($menu_grade) {
		$this->menu_grade = $menu_grade;
	}

	/**
	 * @param $parent_id the $parent_id to set
	 */
	public function setParent_id($parent_id) {
		$this->parent_id = $parent_id;
	}

	/**
	 * @param $menu_name the $menu_name to set
	 */
	public function setMenu_name($menu_name) {
		$this->menu_name = $menu_name;
	}

	/**
	 * @param $url the $url to set
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	public function can_expand() {
		$cache_menu = get_full_menus();
		foreach($cache_menu as $_row){
			if($this->id==$_row['parent_id']){
				return true;
			}
		}

		return false;
	}
	/*
	 * 返回该记录的所有子结点
	 */
	public function get_children() {
		$element = array();
		$children = array();
		$cache_menu = get_full_menus();
		foreach($cache_menu as $_row){
			if($this->id==$_row['parent_id']){
				$element[] = $_row['id'];
			}
		}
		
    	if (count($element) <= 0) {
			return null;
		}else{
	    	for ($i=0;$i<count($element);$i++){
	    		$id = intval($element[$i]);
	    		$children[$i] = new Category($id);
	    	}
	    	return $children;
	    }
	}
}