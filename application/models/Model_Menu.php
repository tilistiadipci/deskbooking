<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_Menu extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->load->library('database');

		// $this->load->model('', '', true);
	}
	
	// public()
	
	public function getMenu($menuname ="", $menuid = "") {
		$id = $this->session->userdata('levelid-nya');
		$menuSession = $this->session->userdata('menu-nya');
		$where = array(
			'l.id' => $id,
			'l.is_deleted' => false,
			'm.is_deleted' => false
		);
		$this->db->select('is_child, mg.icon as mg_icon ,menu_group_id,l.name as level_name, m.name as menu_name, url, m.icon, mg.id as gmenu_id,  mg.name as gmenu_name ')
		->from('level l')
		->join('level_detail ld', 'l.id = ld.level_id')
		->join('menu m', 'ld.menu_id = m.id')
		->join('menu_group mg', 'm.menu_group_id = mg.id')
		->where($where)	
		->order_by("m.sort", "asc");
		$menuDB = $this->db->get()->result_array();
		foreach ($menuDB as $key => $value) {
			if($value['menu_name'] == $menuname) $menuDB[$key]['active'] = 1;
			else $menuDB[$key]['active'] = 0;
		}
		$m = $this->getSendMenu($menuDB);
		return $m;
	}

	public function getMenuHeader($menuid = "", $id = "") {
		$id = $id == "" ? $this->session->userdata('levelid-nya') : $id;
		$this->Model_Module->get_module_pantry();
		$where = array(
			'l.id' => $id,
			'l.is_deleted' => false,
			'm.is_deleted' => false
		);
		$this->db->select('l.name as level_name, module_text,m.id as menu_id, m.name as menu_name, url, m.icon')
		->from('level l')
		->join('level_header_detail ld', 'l.id = ld.level_id')
		->join('menu_headers m', 'ld.menu_id = m.id')
		->where($where)	
		->order_by("m.sort", "asc");
		$menuDB = $this->db->get()->result_array();
		foreach ($menuDB as $key => $value) {
			if($value['menu_id'] == $menuid) $menuDB[$key]['active'] = 1;
			else $menuDB[$key]['active'] = 0;
		}
		$m = $menuDB;
		$m = $this->arrangeMenuHeaderWithModule($menuDB);

		return $m;
	}


	public function arrangeMenuHeaderWithModule($menu){
		$module_core = $this->Model_Module->get_module_core();
		$module_room = $this->Model_Module->get_module_room();
		$module_desk = $this->Model_Module->get_module_desk();
		$module_active = [];
		if ($module_core['is_enabled'] == 1 || $module_core['is_enabled'] == "1") {
			array_push($module_active, $module_core['module_text']);
		}
		if ($module_room['is_enabled'] == 1 || $module_room['is_enabled'] == "1") {
			array_push($module_active, $module_room['module_text']);
		}
		if ($module_desk['is_enabled'] == 1 || $module_desk['is_enabled'] == "1") {
			array_push($module_active, $module_desk['module_text']);
		}
		$menuar = [];
		foreach ($menu as $key => $value) {
			if (in_array($value['module_text'], $module_active)) {
				array_push($menuar, $value);
			}
			
		}
		return $menuar ;
	}
	public function getSendMenu($menu)
	{
		// $menu = $this->Model_Menu->getMenu();
		$menumaster = array();
		foreach ($menu as $key => $value) {
			// echo $value['is_child'];
			if($value['is_child'] == 1){
				$menuname=trim($value['gmenu_name']);
				if(!array_key_exists($menuname,$menumaster ) ){
					//  // childe master
					$n = $menuname;
					$array = array(
						"name" => $value['gmenu_name'],
						"url" => "",
						"icon" => $value['mg_icon'],
						"active" => $value['active'],
						"child" => array()
					);
					$menumaster[$n] = $array;
					if($value['active'] == 1){
						$menumaster[$n]['active'] == 1;
					}
					$arraymenu = array(
						"name" => $value['menu_name'],
						"icon" => $value['icon'],
						"url" => $value['url'],
						"active" => $value['active'],
					);
					array_push($menumaster[$n]['child'], $arraymenu);

				}else{
					$n = $menuname;
					if($value['active'] == 1){
						$menumaster[$n]['active'] == 1;
					}
					$arraymenu = array(
						"name" => $value['menu_name'],
						"icon" => $value['icon'],
						"url" => $value['url'],
						"active" => $value['active'],
					);
					array_push($menumaster[$n]['child'], $arraymenu);
				}
			}else{
				// echo "1";
				$menuname=trim($value['menu_name']);
				if(!array_key_exists($menuname,$menumaster ) ){
					$array = array(
						"name" => $value['menu_name'],
						"icon" => $value['icon'],
						"url" => $value['url'],
						"active" => $value['active'],
						"child" => array()
					);
					$n = trim($value['menu_name']);
					$menumaster[$n] = $array;
				}
			}
		}
		return $menumaster;
	}

}

?>