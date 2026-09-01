<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Model_Menu');
		$this->load->helper('response');
		if($this->session->userdata('logged-in')){
			
		}else{
			redirect('authentication');
		}

	}
	public function test()
	{
		// echo encryp_aes("alexandre") . "<br>";
		// echo decryp_aes("UjZUSzhwZkRzbllpRVplQUM3WWZGZz09") . "<br>";
	}
	public function index()
	{
		$menu = $this->Model_Menu->getMenu();
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
						"icon" => $value['mg_icon'],
						"url" => "",
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
					// // childe 
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
		$response = response("success", $menumaster, "Get menu directory");
		echo $response;
	}
	public function getMenu()
	{
		$menuname = $this->uri->segment('2'); 
		$menu = $this->Model_Menu->getMenu($menuname);
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
					// // childe 
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
		$response = response("success", $menumaster, "Get menu directory");
		echo $response;
	}
}
